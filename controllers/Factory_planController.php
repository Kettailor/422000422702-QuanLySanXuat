<?php

class Factory_planController extends Controller
{
    private WorkshopPlan $workshopPlanModel;
    private Workshop $workshopModel;

    public function __construct()
    {
        $this->authorize(['VT_QUANLY_XUONG', 'VT_NHANVIEN_SANXUAT', 'VT_BAN_GIAM_DOC']);
        $this->workshopPlanModel = new WorkshopPlan();
        $this->workshopModel = new Workshop();
    }

    public function index(): void
    {
        $selectedWorkshop = $_GET['workshop_id'] ?? null;
        $workshops = $this->workshopModel->getAllWithManagers();
        $workshopMap = [];
        foreach ($workshops as $workshop) {
            $workshopMap[$workshop['IdXuong'] ?? ''] = $workshop;
        }

        $plans = $this->workshopPlanModel->getDetailedPlans(200);
        if ($selectedWorkshop) {
            $plans = array_values(array_filter($plans, static function (array $plan) use ($selectedWorkshop): bool {
                return ($plan['IdXuong'] ?? null) === $selectedWorkshop;
            }));
        }

        $groupedPlans = $this->groupPlansByWorkshop($plans, $workshopMap);

        $this->render('factory_plan/index', [
            'title' => 'Tiến độ sản xuất xưởng',
            'groupedPlans' => $groupedPlans,
            'workshops' => $workshops,
            'selectedWorkshop' => $selectedWorkshop,
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $plan = $id ? $this->workshopPlanModel->findWithRelations($id) : null;

        $this->render('factory_plan/read', [
            'title' => 'Chi tiết hạng mục xưởng',
            'plan' => $plan,
        ]);
    }

    private function groupPlansByWorkshop(array $plans, array $workshopMap): array
    {
        if (empty($plans)) {
            return [];
        }

        $grouped = [];
        foreach ($plans as $plan) {
            $workshopId = $plan['IdXuong'] ?? '';
            if (!isset($grouped[$workshopId])) {
                $workshopInfo = $workshopMap[$workshopId] ?? [];
                $grouped[$workshopId] = [
                    'workshop' => $workshopInfo + [
                        'IdXuong' => $workshopId,
                        'TenXuong' => $plan['TenXuong'] ?? ($workshopInfo['TenXuong'] ?? 'Chưa xác định'),
                    ],
                    'items' => [],
                    'stats' => [
                        'total' => 0,
                        'in_progress' => 0,
                        'completed' => 0,
                        'upcoming_deadline' => null,
                    ],
                ];
            }

            $grouped[$workshopId]['items'][] = $plan;
            $grouped[$workshopId]['stats']['total']++;

            $status = $this->normalizeStatus($plan['TrangThai'] ?? '');
            if ($status === 'hoàn thành') {
                $grouped[$workshopId]['stats']['completed']++;
            } elseif ($status !== 'đã hủy') {
                $grouped[$workshopId]['stats']['in_progress']++;
            }

            $deadline = $plan['ThoiGianKetThuc'] ?? null;
            if ($deadline) {
                $timestamp = strtotime($deadline);
                if ($timestamp !== false) {
                    $current = $grouped[$workshopId]['stats']['upcoming_deadline'] ?? null;
                    if (!$current || $timestamp < strtotime($current)) {
                        $grouped[$workshopId]['stats']['upcoming_deadline'] = date('Y-m-d H:i:s', $timestamp);
                    }
                }
            }
        }

        return array_values($grouped);
    }

    private function normalizeStatus(string $status): string
    {
        $status = trim($status);
        if ($status === '') {
            return '';
        }

        if (function_exists('mb_strtolower')) {
            $status = mb_strtolower($status, 'UTF-8');
        } else {
            $status = strtolower($status);
        }

        if (str_contains($status, 'hoàn thành')) {
            return 'hoàn thành';
        }

        if (str_contains($status, 'hủy')) {
            return 'đã hủy';
        }

        return $status;
    }
}
