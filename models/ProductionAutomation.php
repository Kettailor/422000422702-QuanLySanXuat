<?php

class ProductionAutomation
{
    private WorkshopPlan $workshopPlanModel;
    private NotificationStore $notificationStore;
    private Material $materialModel;
    private array $config;

    public function __construct(
        ?WorkshopPlan $workshopPlanModel = null,
        ?NotificationStore $notificationStore = null,
        ?Material $materialModel = null,
        ?array $config = null
    ) {
        $this->workshopPlanModel = $workshopPlanModel ?? new WorkshopPlan();
        $this->notificationStore = $notificationStore ?? new NotificationStore();
        $this->materialModel = $materialModel ?? new Material();
        $this->config = $config ?? $this->loadConfig();
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

        $profile = $this->resolveProfile($orderDetail);
        $components = $profile['components'] ?? [];
        if (empty($components)) {
            $components = $this->config['defaults']['profile']['components'] ?? [];
        }

        if (empty($components)) {
            return;
        }

        $planQuantity = $this->resolvePlanQuantity($plan, $orderDetail);

        $notifications = [];
        $logistics = [];
        $materialIds = [];

        foreach ($components as $component) {
            $baseName = $component['name'] ?? $this->config['defaults']['component_name'];
            $componentName = $this->buildComponentName($component, $orderDetail, $baseName);
            $componentQuantity = $this->calculateComponentQuantity($component, $planQuantity);
            $workshopId = $component['workshop'] ?? $this->config['defaults']['workshop'];
            $unit = $component['unit'] ?? $this->config['defaults']['unit'];
            $status = $component['status'] ?? $this->config['defaults']['status'];

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
                'channel' => $this->config['notifications']['channels']['workshop'] ?? 'workshop',
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
                    'component' => $baseName,
                    'component_label' => $componentName,
                    'required_quantity' => $componentQuantity,
                    'unit' => $unit,
                    'order_request' => $orderDetail['YeuCau'] ?? $orderDetail['YeuCauDonHang'] ?? null,
                ],
            ];

            $logisticsKey = $component['logistics_key'] ?? $baseName;
            if (!isset($logistics[$logisticsKey])) {
                $logistics[$logisticsKey] = [
                    'label' => $component['logistics_label'] ?? $baseName,
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

    private function loadConfig(): array
    {
        $path = __DIR__ . '/../config/production.php';
        if (!file_exists($path)) {
            throw new RuntimeException('Không tìm thấy cấu hình production.');
        }

        $config = require $path;
        if (!is_array($config)) {
            throw new RuntimeException('Cấu hình production không hợp lệ.');
        }

        return $config;
    }

    private function hasExistingWorkshopPlans(string $planId): bool
    {
        $existing = $this->workshopPlanModel->getByPlan($planId);
        return !empty($existing);
    }

    private function resolveProfile(array $orderDetail): array
    {
        $productId = $orderDetail['IdSanPham'] ?? null;
        foreach ($this->config['profiles'] ?? [] as $profile) {
            $productIds = $profile['product_ids'] ?? [];
            if ($productId && in_array($productId, $productIds, true)) {
                return $profile;
            }
        }

        return $this->config['defaults']['profile'] ?? ['components' => []];
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
        $name = $component['name'] ?? $fallback;
        $request = trim((string) ($orderDetail['YeuCau'] ?? $orderDetail['YeuCauDonHang'] ?? ''));

        if ($request !== '' && !empty($component['include_request'])) {
            $name .= ' - ' . $request;
        }

        return $name;
    }

    private function calculateComponentQuantity(array $component, int $planQuantity): int
    {
        $ratio = $component['quantity_per_unit'] ?? 1;
        if (!is_numeric($ratio)) {
            $ratio = 1;
        }

        $quantity = (int) round($planQuantity * (float) $ratio);
        return max($quantity, 1);
    }

    private function calculateMaterialQuantity(array $material, int $planQuantity): int
    {
        $ratio = $material['quantity_per_unit'] ?? 1;
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
        $roles = $this->config['notifications']['warehouse_roles'] ?? [];
        if (empty($roles) || empty($logistics)) {
            return [];
        }

        $channel = $this->config['notifications']['channels']['warehouse'] ?? 'warehouse';
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
        foreach ($roles as $role) {
            $notifications[] = [
                'channel' => $channel,
                'recipient' => $role,
                'message' => $message,
                'metadata' => $metadata,
            ];
        }

        return $notifications;
    }
}

