<?php

namespace App\Models;

use Core\Model;
use DateTime;

class DashboardModel extends Model
{
    public function getDashboardData(): array
    {
        $stats = $this->loadOverviewStats();
        $activities = $this->loadRecentActivities();
        $alerts = $this->loadAlerts();
        $timeline = $this->loadTimeline();

        return [
            'statistics' => $stats,
            'activities' => $activities,
            'alerts' => $alerts,
            'timeline' => $timeline,
        ];
    }

    private function loadOverviewStats(): array
    {
        if ($this->db) {
            $query = $this->db->query('SELECT metric, value FROM dashboard_statistics ORDER BY display_order');
            $rows = $query ? $query->fetchAll() : [];
            if (!empty($rows)) {
                return array_map(function ($row) {
                    return [
                        'label' => $row['metric'],
                        'value' => (int) $row['value'],
                    ];
                }, $rows);
            }
        }

        return [
            ['label' => 'Tổng ngày làm', 'value' => 22],
            ['label' => 'Tổng nhân sự tham gia', 'value' => 40],
            ['label' => 'Phiếu hoàn thành', 'value' => 18],
            ['label' => 'Thông báo mới', 'value' => 3],
        ];
    }

    private function loadRecentActivities(): array
    {
        if ($this->db) {
            $query = $this->db->query('SELECT title, description, category FROM dashboard_activities ORDER BY occurred_at DESC LIMIT 5');
            $rows = $query ? $query->fetchAll() : [];
            if (!empty($rows)) {
                return $rows;
            }
        }

        return [
            [
                'title' => 'Hoạt động trong tháng',
                'description' => 'Đang triển khai 02 kế hoạch sản xuất tuần 43',
                'category' => 'production',
            ],
            [
                'title' => 'Phòng Kinh doanh',
                'description' => 'Hoàn tất ký kết hợp đồng mới với đối tác TDP-2025-10.',
                'category' => 'business',
            ],
            [
                'title' => 'Phòng Nhân sự',
                'description' => 'Đã hoàn thành đánh giá năng lực định kỳ cho tổ sản xuất số 3.',
                'category' => 'hr',
            ],
        ];
    }

    private function loadAlerts(): array
    {
        if ($this->db) {
            $query = $this->db->query('SELECT message, type, highlight FROM dashboard_alerts ORDER BY created_at DESC LIMIT 5');
            $rows = $query ? $query->fetchAll() : [];
            if (!empty($rows)) {
                return $rows;
            }
        }

        return [
            [
                'message' => 'Báo cáo nội bộ: Băng chuyền số 2 bảo trì định kỳ ngày 25/10.',
                'type' => 'info',
                'highlight' => 'Bảo trì ngày 25/10',
            ],
            [
                'message' => 'Cảnh báo chất lượng: Lô hàng KH-2025-003 cần tái kiểm tra.',
                'type' => 'warning',
                'highlight' => 'Lô KH-2025-003',
            ],
        ];
    }

    private function loadTimeline(): array
    {
        if ($this->db) {
            $query = $this->db->query('SELECT schedule_date, title, status FROM dashboard_timeline ORDER BY schedule_date');
            $rows = $query ? $query->fetchAll() : [];
            if (!empty($rows)) {
                return array_map(function ($row) {
                    return [
                        'date' => $row['schedule_date'],
                        'title' => $row['title'],
                        'status' => $row['status'],
                    ];
                }, $rows);
            }
        }

        $today = new DateTime();
        return [
            [
                'date' => $today->modify('+1 day')->format('d/m'),
                'title' => 'Kiểm tra chất lượng lô TDP-2025-11',
                'status' => 'Đang xử lý',
            ],
            [
                'date' => $today->modify('+1 day')->format('d/m'),
                'title' => 'Đóng gói đơn hàng xuất khẩu',
                'status' => 'Hoàn thành',
            ],
            [
                'date' => $today->modify('+1 day')->format('d/m'),
                'title' => 'Họp giao ban sản xuất tuần',
                'status' => 'Chuẩn bị',
            ],
        ];
    }
}
