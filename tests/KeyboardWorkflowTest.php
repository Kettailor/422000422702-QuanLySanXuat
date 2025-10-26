<?php
declare(strict_types=1);

ini_set('assert.exception', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../models/BaseModel.php';
require_once __DIR__ . '/../models/WorkshopPlan.php';
require_once __DIR__ . '/../models/NotificationStore.php';
require_once __DIR__ . '/../models/Material.php';
require_once __DIR__ . '/../models/ProductComponent.php';
require_once __DIR__ . '/../models/ProductComponentMaterial.php';
require_once __DIR__ . '/../models/NotificationSetting.php';
require_once __DIR__ . '/../models/InventoryAlertJob.php';
require_once __DIR__ . '/../models/ProductionAutomation.php';

class WorkshopPlanStub extends WorkshopPlan
{
    public array $created = [];
    private array $existing;

    public function __construct(array $existing = [])
    {
        $this->existing = $existing;
    }

    public function getByPlan(string $planId): array
    {
        return $this->existing[$planId] ?? [];
    }

    public function create(array $data): bool
    {
        $this->created[] = $data;
        return true;
    }
}

class NotificationStoreStub extends NotificationStore
{
    public array $entries = [];

    public function __construct()
    {
        // Bypass parent constructor to avoid filesystem operations.
    }

    public function push(array $entry): void
    {
        $this->entries[] = $entry;
    }

    public function pushMany(array $entries): void
    {
        foreach ($entries as $entry) {
            $this->push($entry);
        }
    }
}

class MaterialStub extends Material
{
    private array $materials;

    public function __construct(array $materials = [])
    {
        $this->materials = $materials;
    }

    public function findMany(array $ids): array
    {
        $result = [];
        foreach ($ids as $id) {
            if (isset($this->materials[$id])) {
                $result[$id] = $this->materials[$id];
            }
        }
        return $result;
    }
}

class ProductComponentStub extends ProductComponent
{
    private array $configurationAssignments;
    private array $productAssignments;
    private array $defaultAssignments;

    public function __construct(
        array $configurationAssignments = [],
        array $productAssignments = [],
        array $defaultAssignments = []
    ) {
        $this->configurationAssignments = $configurationAssignments;
        $this->productAssignments = $productAssignments;
        $this->defaultAssignments = $defaultAssignments;
    }

    public function getByConfiguration(string $configurationId): array
    {
        return $this->configurationAssignments[$configurationId] ?? [];
    }

    public function getByProduct(string $productId): array
    {
        return $this->productAssignments[$productId] ?? [];
    }

    public function getDefaultComponents(): array
    {
        return $this->defaultAssignments;
    }
}

class ProductComponentMaterialStub extends ProductComponentMaterial
{
    private array $materialsByConfiguration;

    public function __construct(array $materialsByConfiguration = [])
    {
        $this->materialsByConfiguration = $materialsByConfiguration;
    }

    public function getMaterialsForComponents(array $configurationIds): array
    {
        $result = [];
        foreach ($configurationIds as $configurationId) {
            if (isset($this->materialsByConfiguration[$configurationId])) {
                $result[$configurationId] = $this->materialsByConfiguration[$configurationId];
            }
        }
        return $result;
    }
}

class NotificationSettingStub extends NotificationSetting
{
    private array $values;

    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    public function getValue(string $key): ?string
    {
        $value = $this->values[$key] ?? null;
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        return $value;
    }

    public function getRecipients(string $key): array
    {
        $value = $this->values[$key] ?? [];
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }
            return array_filter(array_map('trim', explode(',', $value)), static fn ($item) => $item !== '');
        }
        return is_array($value) ? $value : [];
    }
}

function createAutomation(array $options = []): array
{
    $workshopPlan = new WorkshopPlanStub($options['existingPlans'] ?? []);
    $notificationStore = new NotificationStoreStub();
    $material = new MaterialStub($options['materials'] ?? []);
    $componentModel = new ProductComponentStub(
        $options['configurationAssignments'] ?? [],
        $options['productComponents'] ?? [],
        $options['defaultComponents'] ?? []
    );
    $componentMaterialModel = new ProductComponentMaterialStub($options['componentMaterials'] ?? []);
    $notificationSetting = new NotificationSettingStub($options['settings'] ?? []);

    $automation = new ProductionAutomation(
        $workshopPlan,
        $notificationStore,
        $material,
        $componentModel,
        $componentMaterialModel,
        $notificationSetting
    );

    return [$automation, $workshopPlan, $notificationStore];
}

// Scenario 1: cấu hình có phân công cụ thể
[$automation, $workshopPlan, $notificationStore] = createAutomation([
    'configurationAssignments' => [
        'CFGKB87RGB' => [
            [
                'IdCongDoan' => 'ASSIGN_RGB_PCB',
                'IdCauHinh' => 'CFGKB87RGB',
                'TenCongDoan' => 'Hàn PCB RGB',
                'TyLeSoLuong' => 1,
                'DonVi' => 'bộ',
                'IdXuong' => 'XU-PCB',
                'IncludeYeuCau' => 1,
                'LogisticsKey' => 'pcb',
                'LogisticsLabel' => 'PCB hotswap',
                'ThuTu' => 1,
            ],
            [
                'IdCongDoan' => 'ASSIGN_RGB_PACK',
                'IdCauHinh' => 'CFGKB87RGB',
                'TenCongDoan' => 'Đóng gói full kit RGB',
                'TyLeSoLuong' => 1,
                'DonVi' => 'bộ',
                'IdXuong' => 'XU-PACK',
                'IncludeYeuCau' => 0,
                'LogisticsKey' => 'package',
                'LogisticsLabel' => 'Đóng gói full kit',
                'ThuTu' => 2,
            ],
        ],
    ],
    'componentMaterials' => [
        'CFGKB87RGB' => [
            ['id' => 'NLPCB', 'quantity_per_unit' => 1, 'label' => 'PCB SV5TOT', 'unit' => 'tấm'],
            ['id' => 'NLBADGE', 'quantity_per_unit' => 1, 'label' => 'Badge TechHub', 'unit' => 'chiếc'],
        ],
    ],
    'materials' => [
        'NLPCB' => ['SoLuong' => 120],
        'NLBADGE' => ['SoLuong' => 80],
    ],
    'settings' => [
        'workshop_channel' => 'workshop',
        'warehouse_channel' => 'warehouse',
        'warehouse_recipients' => ['VT_NHANVIEN_KHO'],
    ],
]);

$plan = [
    'IdKeHoachSanXuat' => 'KHTEST1',
    'SoLuong' => 10,
    'ThoiGianBD' => '2024-01-01 08:00:00',
    'ThoiGianKetThuc' => '2024-01-02 17:00:00',
];

$orderDetail = [
    'IdSanPham' => 'SPKB87',
    'IdCauHinh' => 'CFGKB87RGB',
    'IdDonHang' => 'DHTEST',
    'YeuCau' => 'Tape mod thêm',
    'SoLuong' => 10,
];

$automation->handleNewPlan($plan, $orderDetail);

assert(count($workshopPlan->created) === 2, 'BOM phải sinh 2 công đoạn xưởng.');
assert($workshopPlan->created[0]['IdKeHoachSanXuat'] === 'KHTEST1');
assert($workshopPlan->created[0]['IdXuong'] === 'XU-PCB');
assert(strpos($workshopPlan->created[0]['TenThanhThanhPhanSP'], 'Tape mod thêm') !== false, 'Tên công đoạn phải chứa yêu cầu khi IncludeYeuCau = 1.');
assert($workshopPlan->created[1]['IdXuong'] === 'XU-PACK');
assert(!empty($notificationStore->entries), 'Phải đẩy thông báo kho/xưởng.');

// Scenario 2: Fallback khi không có BOM gắn kèm
[$automationFallback, $workshopPlanFallback] = createAutomation([
    'productComponents' => [
        'SPKB87' => [
            [
                'IdCongDoan' => 'ASSIGN_STD',
                'IdCauHinh' => 'CFGKB87STD',
                'TenCongDoan' => 'Gia công fallback',
                'TyLeSoLuong' => 1,
                'DonVi' => 'bộ',
                'IdXuong' => 'XU-FB',
                'IncludeYeuCau' => 0,
                'LogisticsKey' => 'fallback',
                'LogisticsLabel' => 'Nguyên liệu fallback',
                'ThuTu' => 1,
            ],
        ],
    ],
    'componentMaterials' => [
        'CFGKB87STD' => [
            ['id' => 'NLFOAM', 'quantity_per_unit' => 1, 'label' => 'Foam fallback', 'unit' => 'bộ'],
        ],
    ],
    'materials' => [
        'NLFOAM' => ['SoLuong' => 60],
    ],
    'settings' => [],
]);

$planFallback = [
    'IdKeHoachSanXuat' => 'KHTEST2',
    'SoLuong' => 5,
];

$orderDetailFallback = [
    'IdSanPham' => 'SPKB87',
    'IdCauHinh' => null,
    'IdDonHang' => 'DHFALLBACK',
    'SoLuong' => 5,
];

$automationFallback->handleNewPlan($planFallback, $orderDetailFallback);

assert(count($workshopPlanFallback->created) === 1, 'Fallback sản phẩm phải sinh công đoạn mặc định.');
assert($workshopPlanFallback->created[0]['IdXuong'] === 'XU-FB');

fwrite(STDOUT, "All keyboard configuration workflow tests passed.\n");
