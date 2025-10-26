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
    private array $bomComponents;
    private array $productComponents;
    private array $defaultComponents;

    public function __construct(
        array $bomComponents = [],
        array $productComponents = [],
        array $defaultComponents = []
    ) {
        $this->bomComponents = $bomComponents;
        $this->productComponents = $productComponents;
        $this->defaultComponents = $defaultComponents;
    }

    public function getByBom(string $bomId): array
    {
        return $this->bomComponents[$bomId] ?? [];
    }

    public function getByProduct(string $productId): array
    {
        return $this->productComponents[$productId] ?? [];
    }

    public function getDefaultComponents(): array
    {
        return $this->defaultComponents;
    }
}

class ProductComponentMaterialStub extends ProductComponentMaterial
{
    private array $materialsByComponent;

    public function __construct(array $materialsByComponent = [])
    {
        $this->materialsByComponent = $materialsByComponent;
    }

    public function getMaterialsForComponent(string $componentId): array
    {
        return $this->materialsByComponent[$componentId] ?? [];
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
        $options['bomComponents'] ?? [],
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

// Scenario 1: BOM gắn với cấu hình
[$automation, $workshopPlan, $notificationStore] = createAutomation([
    'bomComponents' => [
        'BOMKB87FULL' => [
            [
                'IdCongDoan' => 'CDPCB',
                'TenCongDoan' => 'Hàn PCB',
                'TyLeSoLuong' => 1,
                'DonVi' => 'bộ',
                'IdXuong' => 'XU-PCB',
                'IncludeYeuCau' => 1,
                'LogisticsKey' => 'pcb',
                'LogisticsLabel' => 'PCB hotswap',
                'ThuTu' => 1,
            ],
            [
                'IdCongDoan' => 'CDPACK',
                'TenCongDoan' => 'Đóng gói full kit',
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
        'CDPCB' => [
            ['id' => 'NLPCB', 'quantity_per_unit' => 1, 'label' => 'PCB SV5TOT', 'unit' => 'tấm'],
        ],
        'CDPACK' => [
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
    'IdBOM' => 'BOMKB87FULL',
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
    'bomComponents' => [],
    'productComponents' => [
        'SPKB87' => [
            [
                'IdCongDoan' => 'CDFALLBACK',
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
        'CDFALLBACK' => [
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
    'IdBOM' => null,
    'IdDonHang' => 'DHFALLBACK',
    'SoLuong' => 5,
];

$automationFallback->handleNewPlan($planFallback, $orderDetailFallback);

assert(count($workshopPlanFallback->created) === 1, 'Fallback sản phẩm phải sinh công đoạn mặc định.');
assert($workshopPlanFallback->created[0]['IdXuong'] === 'XU-FB');

fwrite(STDOUT, "All keyboard configuration workflow tests passed.\n");
