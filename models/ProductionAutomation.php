<?php

class ProductionAutomation
{
    private WorkshopPlan $workshopPlanModel;
    private NotificationStore $notificationStore;
    private Material $materialModel;
    private ProductComponent $componentModel;
    private ProductComponentMaterial $componentMaterialModel;
    private NotificationSetting $notificationSettingModel;

    public function __construct(
        ?WorkshopPlan $workshopPlanModel = null,
        ?NotificationStore $notificationStore = null,
        ?Material $materialModel = null,
        ?ProductComponent $componentModel = null,
        ?ProductComponentMaterial $componentMaterialModel = null,
        ?NotificationSetting $notificationSettingModel = null
    ) {
        $this->workshopPlanModel = $workshopPlanModel ?? new WorkshopPlan();
        $this->notificationStore = $notificationStore ?? new NotificationStore();
        $this->materialModel = $materialModel ?? new Material();
        $this->componentModel = $componentModel ?? new ProductComponent();
        $this->componentMaterialModel = $componentMaterialModel ?? new ProductComponentMaterial();
        $this->notificationSettingModel = $notificationSettingModel ?? new NotificationSetting();
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

        $workshopChannel = $this->notificationSettingModel->getValue('workshop_channel');
        $warehouseChannel = $this->notificationSettingModel->getValue('warehouse_channel');
        $warehouseRecipients = $this->notificationSettingModel->getRecipients('warehouse_recipients');

        foreach ($components as $component) {
            $baseName = $component['TenCongDoan'] ?? $component['name'] ?? null;
            $componentName = $this->buildComponentName($component, $orderDetail, $baseName);
            if ($componentName === null) {
                continue;
            }
            $componentQuantity = $this->calculateComponentQuantity($component, $planQuantity);
            $workshopId = $component['IdXuong'] ?? null;
            $unit = $component['DonVi'] ?? null;
            $status = $component['TrangThaiMacDinh'] ?? null;

            if (!$workshopId || !$unit) {
                continue;
            }

            $workshopPlan = [
                'IdKeHoachSanXuatXuong' => uniqid('KXX'),
                'TenThanhThanhPhanSP' => $componentName,
                'SoLuong' => $componentQuantity,
                'ThoiGianBatDau' => $plan['ThoiGianBD'] ?? null,
                'ThoiGianKetThuc' => $plan['ThoiGianKetThuc'] ?? null,
                'IdKeHoachSanXuat' => $planId,
                'IdXuong' => $workshopId,
            ];

            if ($status !== null) {
                $workshopPlan['TrangThai'] = $status;
            }

            $this->workshopPlanModel->create($workshopPlan);

            if ($workshopChannel) {
                $notifications[] = [
                    'channel' => $workshopChannel,
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
            }

            $logisticsKey = $component['LogisticsKey'] ?? $component['logistics_key'] ?? ($component['IdCongDoan'] ?? md5($componentName));
            if (!isset($logistics[$logisticsKey])) {
                $logistics[$logisticsKey] = [
                    'label' => $component['LogisticsLabel'] ?? $component['logistics_label'] ?? $componentName,
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
                $materialLabel = $material['label'] ?? $componentName;
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

        $notifications = array_merge(
            $notifications,
            $this->buildWarehouseNotifications(
                $planId,
                $orderDetail,
                $logistics,
                $warehouseChannel,
                $warehouseRecipients
            )
        );

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

    private function buildComponentName(array $component, array $orderDetail, ?string $fallback): ?string
    {
        $name = $component['TenCongDoan'] ?? $component['name'] ?? $fallback ?? '';
        $name = trim((string) $name);
        if ($name === '') {
            return null;
        }
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

    private function buildWarehouseNotifications(
        string $planId,
        array $orderDetail,
        array $logistics,
        ?string $channel,
        array $recipients
    ): array
    {
        if (empty($logistics) || !$channel || empty($recipients)) {
            return [];
        }

        $summaryParts = [];

        foreach ($logistics as $item) {
            if (empty($item['label']) || empty($item['unit'])) {
                continue;
            }

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

        if (empty($summaryParts)) {
            return [];
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
        foreach ($recipients as $recipient) {
            $notifications[] = [
                'channel' => $channel,
                'recipient' => $recipient,
                'message' => $message,
                'metadata' => $metadata,
            ];
        }

        return $notifications;
    }
}
