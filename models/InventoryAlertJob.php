<?php

class InventoryAlertJob
{
    private NotificationStore $notificationStore;
    private NotificationSetting $notificationSetting;

    public function __construct(?NotificationStore $notificationStore = null, ?NotificationSetting $notificationSetting = null)
    {
        $this->notificationStore = $notificationStore ?? new NotificationStore();
        $this->notificationSetting = $notificationSetting ?? new NotificationSetting();
    }

    public function dispatch(array $context, array $shortages): void
    {
        if (empty($shortages)) {
            return;
        }

        $channel = $this->notificationSetting->getValue('inventory_alert_channel') ?? 'inventory_alert';
        $recipients = $this->notificationSetting->getRecipients('inventory_alert_recipients');
        if (empty($recipients)) {
            $recipients = ['system'];
        }

        $planId = $context['plan_id'] ?? null;
        $orderId = $context['order_id'] ?? null;
        $orderRequest = $context['order_request'] ?? null;

        $entries = [];
        foreach ($recipients as $recipient) {
            $entries[] = [
                'channel' => $channel,
                'recipient' => $recipient,
                'message' => $this->buildMessage($planId, $shortages),
                'metadata' => [
                    'type' => 'inventory_shortage',
                    'plan_id' => $planId,
                    'order_id' => $orderId,
                    'order_request' => $orderRequest,
                    'shortages' => $shortages,
                ],
            ];
        }

        $this->notificationStore->pushMany($entries);
    }

    private function buildMessage(?string $planId, array $shortages): string
    {
        $summary = [];
        foreach ($shortages as $shortage) {
            $component = $shortage['component'] ?? 'Công đoạn';
            $materials = $shortage['materials'] ?? [];
            $materialsLine = [];
            foreach ($materials as $material) {
                $label = $material['label'] ?? $material['id'] ?? 'Nguyên liệu';
                $required = $material['required'] ?? 0;
                $stock = $material['stock'] ?? 0;
                $materialsLine[] = sprintf('%s cần %d, tồn %d', $label, $required, $stock);
            }
            $summary[] = sprintf('%s thiếu: %s', $component, implode('; ', $materialsLine));
        }

        $prefix = $planId ? sprintf('KHSX %s', $planId) : 'Kế hoạch sản xuất';

        return sprintf('%s thiếu vật tư: %s.', $prefix, implode(' | ', $summary));
    }
}
