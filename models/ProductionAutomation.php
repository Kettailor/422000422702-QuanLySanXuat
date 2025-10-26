<?php

class ProductionAutomation
{
    private const DEFAULT_STATUS = 'Đang chờ xưởng xác nhận';
    private const DEFAULT_WORKSHOP = 'XU001';
    private const DEFAULT_UNIT = 'thành phẩm';
    private const DEFAULT_COMPONENT_NAME = 'Công đoạn sản xuất';
    private const WORKSHOP_CHANNEL = 'workshop';
    private const WAREHOUSE_CHANNEL = 'warehouse';
    private const WAREHOUSE_ROLES = ['VT_NHANVIEN_KHO', 'VT_DOI_TAC_VAN_TAI'];

    private WorkshopPlan $workshopPlanModel;
    private NotificationStore $notificationStore;
    private Material $materialModel;
    private ProductComponent $componentModel;
    private ProductComponentMaterial $componentMaterialModel;

    public function __construct(
        ?WorkshopPlan $workshopPlanModel = null,
        ?NotificationStore $notificationStore = null,
        ?Material $materialModel = null,
        ?ProductComponent $componentModel = null,
        ?ProductComponentMaterial $componentMaterialModel = null
    ) {
        $this->workshopPlanModel = $workshopPlanModel ?? new WorkshopPlan();
        $this->notificationStore = $notificationStore ?? new NotificationStore();
        $this->materialModel = $materialModel ?? new Material();
        $this->componentModel = $componentModel ?? new ProductComponent();
        $this->componentMaterialModel = $componentMaterialModel ?? new ProductComponentMaterial();
    }

    public function handleNewPlan(array $plan, array $orderDetail): void
    {
        $planId = $plan['IdKeHoachSanXuat'] ?? null;
        if (!$planId) {
            throw new InvalidArgumentException('Thiếu mã kế hoạch sản xuất.');
        }

        if ($this->hasExistingWorkshopPlans($planId)) {
            return;
        }

        $components = $this->loadComponentsForProduct($orderDetail['IdSanPham'] ?? null);
        if (empty($components)) {
            return;
        }

        $planQuantity = $this->resolvePlanQuantity($plan, $orderDetail);

        $notifications = [];
        $logistics = [];
        $materialIds = [];

        foreach ($components as $component) {
            $baseName = $component['TenCongDoan'] ?? $component['name'] ?? self::DEFAULT_COMPONENT_NAME;
            $componentName = $this->buildComponentName($component, $orderDetail, $baseName);
            $componentQuantity = $this->calculateComponentQuantity($component, $planQuantity);
            $workshopId = $component['IdXuong'] ?? self::DEFAULT_WORKSHOP;
            $unit = $component['DonVi'] ?? self::DEFAULT_UNIT;
            $status = $component['TrangThaiMacDinh'] ?? self::DEFAULT_STATUS;

            $workshopPlan = [
                'IdKeHoachSanXuatXuong' => uniqid('KXX'),
                'TenThanhThanhPhanSP' => $componentName,
                'SoLuong' => $componentQuantity,
                'ThoiGianBatDau' => $plan['ThoiGianBD'] ?? null,
                'ThoiGianKetThuc' => $plan['ThoiGianKetThuc'] ?? null,
                'TrangThai' => $status,
                'IdKeHoachSanXuat' => $planId,
                'IdXuong' => $workshopId,
            ];

            $this->workshopPlanModel->create($workshopPlan);

            $notifications[] = [
                'channel' => self::WORKSHOP_CHANNEL,
                'recipient' => $workshopId,
                'message' => sprintf(
                    'Kế hoạch %s: %s (%d %s).',
                    $planId,
                    $componentName,
                    $componentQuantity,
                    $unit
                ),
                'metadata' => [
                    'plan_id' => $planId,
                    'order_id' => $orderDetail['IdDonHang'] ?? null,
                    'workshop_plan_id' => $workshopPlan['IdKeHoachSanXuatXuong'],
                    'component' => $component['IdCongDoan'] ?? $baseName,
                    'component_label' => $componentName,
                    'required_quantity' => $componentQuantity,
                    'unit' => $unit,
                    'order_request' => $orderDetail['YeuCau'] ?? $orderDetail['YeuCauDonHang'] ?? null,
                ],
            ];

            $logisticsKey = $component['LogisticsKey'] ?? $component['logistics_key'] ?? ($component['IdCongDoan'] ?? $baseName);
            if (!isset($logistics[$logisticsKey])) {
                $logistics[$logisticsKey] = [
                    'label' => $component['LogisticsLabel'] ?? $component['logistics_label'] ?? $baseName,
                    'unit' => $unit,
                    'required' => 0,
                    'materials' => [],
                ];
            }
            $logistics[$logisticsKey]['required'] += $componentQuantity;

            foreach ($component['materials'] ?? [] as $material) {
                $materialId = $material['id'] ?? null;
                if (!$materialId) {
                    continue;
                }

                $materialIds[$materialId] = true;
                $materialUnit = $material['unit'] ?? $unit;
                $materialLabel = $material['label'] ?? $baseName;
                $materialRequired = $this->calculateMaterialQuantity($material, $planQuantity);

                if (!isset($logistics[$logisticsKey]['materials'][$materialId])) {
                    $logistics[$logisticsKey]['materials'][$materialId] = [
                        'id' => $materialId,
                        'label' => $materialLabel,
                        'unit' => $materialUnit,
                        'required' => 0,
                    ];
                }

                $logistics[$logisticsKey]['materials'][$materialId]['required'] += $materialRequired;
            }
        }

        if (!empty($materialIds)) {
            $this->attachInventorySnapshots($logistics, array_keys($materialIds));
        }

        $notifications = array_merge($notifications, $this->buildWarehouseNotifications($planId, $orderDetail, $logistics));

        $this->notificationStore->pushMany($notifications);
    }

    private function hasExistingWorkshopPlans(string $planId): bool
    {
        $existing = $this->workshopPlanModel->getByPlan($planId);
        return !empty($existing);
    }

    private function loadComponentsForProduct(?string $productId): array
    {
        $components = [];

        if ($productId) {
            $components = $this->componentModel->getByProduct($productId);
        }

        if (empty($components)) {
            $components = $this->componentModel->getDefaultComponents();
        }

        if (empty($components)) {
            return [];
        }

        foreach ($components as &$component) {
            $component['materials'] = $this->componentMaterialModel->getMaterialsForComponent($component['IdCongDoan']);
        }
        unset($component);

        return $components;
    }

    private function resolvePlanQuantity(array $plan, array $orderDetail): int
    {
        $quantity = (int) ($plan['SoLuong'] ?? 0);
        if ($quantity <= 0) {
            $quantity = (int) ($orderDetail['SoLuong'] ?? 0);
        }

        return max($quantity, 1);
    }

    private function buildComponentName(array $component, array $orderDetail, string $fallback): string
    {
        $name = $component['TenCongDoan'] ?? $component['name'] ?? $fallback;
        $request = trim((string) ($orderDetail['YeuCau'] ?? $orderDetail['YeuCauDonHang'] ?? ''));
        $includeRequest = $component['IncludeYeuCau'] ?? $component['include_request'] ?? false;

        if ($request !== '' && !empty($includeRequest)) {
            $name .= ' - ' . $request;
        }

        return $name;
    }

    private function calculateComponentQuantity(array $component, int $planQuantity): int
    {
        $ratio = $component['TyLeSoLuong'] ?? $component['quantity_per_unit'] ?? 1;
        if (!is_numeric($ratio)) {
            $ratio = 1;
        }

        $quantity = (int) round($planQuantity * (float) $ratio);
        return max($quantity, 1);
    }

    private function calculateMaterialQuantity(array $material, int $planQuantity): int
    {
        $ratio = $material['TyLeSoLuong'] ?? $material['quantity_per_unit'] ?? 1;
        if (!is_numeric($ratio)) {
            $ratio = 1;
        }

        $quantity = (int) round($planQuantity * (float) $ratio);
        return max($quantity, 1);
    }

    private function attachInventorySnapshots(array &$logistics, array $materialIds): void
    {
        try {
            $inventory = $this->materialModel->findMany($materialIds);
        } catch (Throwable $e) {
            $inventory = [];
        }

        foreach ($logistics as &$item) {
            if (empty($item['materials'])) {
                continue;
            }

            foreach ($item['materials'] as $id => &$material) {
                $material['stock'] = (int) ($inventory[$id]['SoLuong'] ?? 0);
            }
            unset($material);
        }
        unset($item);
    }

    private function buildWarehouseNotifications(string $planId, array $orderDetail, array $logistics): array
    {
        if (empty(self::WAREHOUSE_ROLES) || empty($logistics)) {
            return [];
        }

        $summaryParts = [];

        foreach ($logistics as $item) {
            $line = sprintf('%s: %d %s', $item['label'], $item['required'], $item['unit']);
            if (!empty($item['materials'])) {
                $materials = [];
                foreach ($item['materials'] as $material) {
                    $materials[] = sprintf(
                        '%s %s (cần %d, tồn %d)',
                        $material['label'],
                        $material['unit'],
                        $material['required'],
                        $material['stock'] ?? 0
                    );
                }
                $line .= ' [' . implode('; ', $materials) . ']';
            }
            $summaryParts[] = $line;
        }

        $message = sprintf(
            'Chuẩn bị nguyên liệu cho kế hoạch %s: %s.',
            $planId,
            implode(', ', $summaryParts)
        );

        $metadata = [
            'plan_id' => $planId,
            'order_id' => $orderDetail['IdDonHang'] ?? null,
            'order_request' => $orderDetail['YeuCau'] ?? $orderDetail['YeuCauDonHang'] ?? null,
            'requirements' => $logistics,
        ];

        $notifications = [];
        foreach (self::WAREHOUSE_ROLES as $role) {
            $notifications[] = [
                'channel' => self::WAREHOUSE_CHANNEL,
                'recipient' => $role,
                'message' => $message,
                'metadata' => $metadata,
            ];
        }

        return $notifications;
    }
}
