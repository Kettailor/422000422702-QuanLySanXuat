<?php

class ProductionAutomation
{
    private WorkshopPlan $workshopPlanModel;
    private NotificationStore $notificationStore;
    private Material $materialModel;
    private ProductComponent $componentModel;
    private ProductComponentMaterial $componentMaterialModel;
    private NotificationSetting $notificationSettingModel;
    private InventoryAlertJob $inventoryAlertJob;

    public function __construct(
        ?WorkshopPlan $workshopPlanModel = null,
        ?NotificationStore $notificationStore = null,
        ?Material $materialModel = null,
        ?ProductComponent $componentModel = null,
        ?ProductComponentMaterial $componentMaterialModel = null,
        ?NotificationSetting $notificationSettingModel = null,
        ?InventoryAlertJob $inventoryAlertJob = null
    ) {
        $this->workshopPlanModel = $workshopPlanModel ?? new WorkshopPlan();
        $this->notificationStore = $notificationStore ?? new NotificationStore();
        $this->materialModel = $materialModel ?? new Material();
        $this->componentModel = $componentModel ?? new ProductComponent();
        $this->componentMaterialModel = $componentMaterialModel ?? new ProductComponentMaterial();
        $this->notificationSettingModel = $notificationSettingModel ?? new NotificationSetting();
        $this->inventoryAlertJob = $inventoryAlertJob ?? new InventoryAlertJob($this->notificationStore, $this->notificationSettingModel);
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

        $components = $this->loadComponentsForProduct(
            $orderDetail['IdSanPham'] ?? null,
            $orderDetail['IdCauHinh'] ?? null
        );
        if (empty($components)) {
            return;
        }

        $planQuantity = $this->resolvePlanQuantity($plan, $orderDetail);

        $notifications = [];
        $logistics = [];
        $materialIds = [];
        $componentMaterialRequirements = [];
        $componentMeta = [];

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
                'IdCongDoan' => $component['IdCongDoan'] ?? null,
                'TinhTrangVatTu' => 'Chưa kiểm tra',
            ];

            if ($status !== null) {
                $workshopPlan['TrangThai'] = $status;
            }

            $this->workshopPlanModel->create($workshopPlan);

            $componentMeta[$workshopPlan['IdKeHoachSanXuatXuong']] = [
                'component_id' => $component['IdCongDoan'] ?? null,
                'component_name' => $componentName,
                'workshop_id' => $workshopId,
                'unit' => $unit,
                'required_quantity' => $componentQuantity,
                'logistics_key' => $component['LogisticsKey'] ?? $component['logistics_key'] ?? null,
            ];

            $componentMaterialRequirements[$workshopPlan['IdKeHoachSanXuatXuong']] = [];

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
                    'workshop_plan_ids' => [],
                ];
            }
            $logistics[$logisticsKey]['required'] += $componentQuantity;
            $logistics[$logisticsKey]['workshop_plan_ids'][] = $workshopPlan['IdKeHoachSanXuatXuong'];

            foreach ($component['materials'] ?? [] as $material) {
                $materialId = $material['id'] ?? null;
                if (!$materialId) {
                    continue;
                }

                $materialIds[$materialId] = true;
                $materialUnit = $material['unit'] ?? $unit;
                $materialLabel = $material['label'] ?? $componentName;
                $materialRequired = $this->calculateMaterialQuantity($material, $planQuantity);

                $componentMaterialRequirements[$workshopPlan['IdKeHoachSanXuatXuong']][$materialId] = [
                    'id' => $materialId,
                    'label' => $materialLabel,
                    'unit' => $materialUnit,
                    'required' => $materialRequired,
                ];

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
            $this->attachInventorySnapshots($logistics, array_keys($materialIds), $componentMaterialRequirements);
        }

        $shortages = $this->updateMaterialStatuses($componentMaterialRequirements, $componentMeta);

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

        if (!empty($shortages)) {
            $this->inventoryAlertJob->dispatch(
                [
                    'plan_id' => $planId,
                    'order_id' => $orderDetail['IdDonHang'] ?? null,
                    'order_request' => $orderDetail['YeuCau'] ?? $orderDetail['YeuCauDonHang'] ?? null,
                ],
                $shortages
            );
        }

        $this->notificationStore->pushMany($notifications);
    }

    private function hasExistingWorkshopPlans(string $planId): bool
    {
        $existing = $this->workshopPlanModel->getByPlan($planId);
        return !empty($existing);
    }

    private function loadComponentsForProduct(?string $productId, ?string $configurationId): array
    {
        $components = $this->componentModel->getComponentsForProductConfiguration($productId, $configurationId);

        if (empty($components)) {
            return [];
        }

        $componentIds = array_values(array_filter(array_map(fn ($component) => $component['IdCongDoan'] ?? null, $components)));
        $materialsByComponent = $this->componentMaterialModel->getMaterialsForComponents($componentIds);

        foreach ($components as &$component) {
            $componentId = $component['IdCongDoan'] ?? null;
            $component['materials'] = $componentId && isset($materialsByComponent[$componentId])
                ? $materialsByComponent[$componentId]
                : [];
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

    private function attachInventorySnapshots(array &$logistics, array $materialIds, array &$componentMaterialRequirements): void
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

        foreach ($componentMaterialRequirements as &$materials) {
            foreach ($materials as $id => &$material) {
                $material['stock'] = (int) ($inventory[$id]['SoLuong'] ?? 0);
                $material['deficit'] = max(0, ($material['required'] ?? 0) - ($material['stock'] ?? 0));
            }
            unset($material);
        }
        unset($materials);
    }

    private function updateMaterialStatuses(array $componentMaterialRequirements, array $componentMeta): array
    {
        if (empty($componentMaterialRequirements)) {
            return [];
        }

        $shortages = [];

        foreach ($componentMaterialRequirements as $planId => $materials) {
            $result = $this->resolveMaterialStatusDetails($materials);
            $status = $result['status'];

            try {
                $this->workshopPlanModel->update($planId, ['TinhTrangVatTu' => $status]);
            } catch (Throwable $exception) {
                // Bỏ qua lỗi cập nhật trạng thái để không chặn luồng tự động hóa.
            }

            if (!empty($result['shortages'])) {
                $meta = $componentMeta[$planId] ?? [];
                $shortages[] = [
                    'workshop_plan_id' => $planId,
                    'component_id' => $meta['component_id'] ?? null,
                    'component' => $meta['component_name'] ?? $planId,
                    'workshop_id' => $meta['workshop_id'] ?? null,
                    'materials' => $result['shortages'],
                ];
            }
        }

        return $shortages;
    }

    private function resolveMaterialStatusDetails(array $materials): array
    {
        if (empty($materials)) {
            return [
                'status' => 'Không yêu cầu vật tư',
                'shortages' => [],
            ];
        }

        $shortages = [];

        foreach ($materials as $material) {
            $required = max(0, (int) ($material['required'] ?? 0));
            $stock = max(0, (int) ($material['stock'] ?? 0));

            if ($required === 0) {
                continue;
            }

            if ($stock < $required) {
                $shortages[] = [
                    'id' => $material['id'] ?? null,
                    'label' => $material['label'] ?? null,
                    'unit' => $material['unit'] ?? null,
                    'required' => $required,
                    'stock' => $stock,
                    'deficit' => max(0, $material['deficit'] ?? ($required - $stock)),
                ];
            }
        }

        if (!empty($shortages)) {
            return [
                'status' => 'Thiếu vật tư',
                'shortages' => $shortages,
            ];
        }

        return [
            'status' => 'Đủ vật tư',
            'shortages' => [],
        ];
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
