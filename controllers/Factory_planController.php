<?php

class Factory_planController extends Controller
{
    private WorkshopPlan $workshopPlanModel;
    private Workshop $workshopModel;
    private WorkshopAssignment $assignmentModel;
    private WorkshopPlanAssignment $planAssignmentModel;
    private Employee $employeeModel;

    public function __construct()
    {
        $this->authorize(['VT_QUANLY_XUONG', 'VT_NHANVIEN_SANXUAT', 'VT_BAN_GIAM_DOC']);
        $this->workshopPlanModel = new WorkshopPlan();
        $this->workshopModel = new Workshop();
        $this->assignmentModel = new WorkshopAssignment();
        $this->planAssignmentModel = new WorkshopPlanAssignment();
        $this->employeeModel = new Employee();
    }

    public function index(): void
    {
        $selectedWorkshop = $_GET['workshop_id'] ?? null;
        $employeeId = $_GET['employee_id'] ?? null;
        $workshops = $this->getVisibleWorkshops();
        $selectedWorkshop = $this->normalizeSelectedWorkshop($selectedWorkshop, $workshops);
        $workshopMap = [];
        foreach ($workshops as $workshop) {
            $workshopMap[$workshop['IdXuong'] ?? ''] = $workshop;
        }

        $plans = $this->workshopPlanModel->getDetailedPlans(200);
        if ($employeeId) {
            $employeePlanIds = $this->planAssignmentModel->getPlanIdsByEmployee($employeeId);
            if ($employeePlanIds) {
                $allowed = array_fill_keys($employeePlanIds, true);
                $plans = array_values(array_filter($plans, static function (array $plan) use ($allowed): bool {
                    $planId = $plan['IdKeHoachSanXuatXuong'] ?? null;
                    return $planId !== null && isset($allowed[$planId]);
                }));
            } else {
                $plans = [];
            }
        }
        $plans = $this->filterPlansByVisibleWorkshops($plans, $workshops, $selectedWorkshop);

        $groupedPlans = $this->groupPlansByWorkshop($plans, $workshopMap);
        $employee = $employeeId ? $this->employeeModel->find($employeeId) : null;

        $this->render('factory_plan/index', [
            'title' => 'Tiến độ sản xuất xưởng',
            'groupedPlans' => $groupedPlans,
            'workshops' => $workshops,
            'selectedWorkshop' => $selectedWorkshop,
            'employeeFilter' => $employee,
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $plan = $id ? $this->workshopPlanModel->findWithRelations($id) : null;
        $plan = $this->filterPlanByAccess($plan);

        $stockList = $plan ? ($this->workshopPlanModel->getMaterialStock($id) ?? []) : [];
        $planAssignments = $plan ? $this->planAssignmentModel->getByPlan($plan['IdKeHoachSanXuatXuong']) : [];
        $canUpdateProgress = $plan && $this->isMaterialSufficient($plan['TinhTrangVatTu'] ?? null) && !empty($planAssignments);

        $this->render('factory_plan/read', [
            'title' => 'Chi tiết hạng mục xưởng',
            'plan' => $plan,
            'stock_list_need' =>  $stockList,
            'assignments' => $plan ? $this->assignmentModel->getAssignmentsByWorkshop($plan['IdXuong']) : [],
            'progress' => $plan ? $this->calculateProgress($plan['ThoiGianBatDau'] ?? null, $plan['ThoiGianKetThuc'] ?? null, $plan['TrangThai'] ?? null) : null,
            'materialStatus' => $this->summarizeMaterialStatus($stockList, $plan['TinhTrangVatTu'] ?? null),
            'canUpdateProgress' => $canUpdateProgress,
        ]);
    }

    public function delete(): void
    {
        $id = $_POST['id'] ?? ($_GET['id'] ?? null);
        if (!$id) {
            $this->redirect('?controller=factory_plan&action=index');
            return;
        }

        $plan = $this->workshopPlanModel->findWithRelations($id);
        $plan = $this->filterPlanByAccess($plan);
        if (!$plan) {
            return;
        }

        if (!$this->canModifyPlan($plan)) {
            $this->setFlash('danger', 'Bạn không được phép xóa kế hoạch xưởng này.');
            $this->redirect('?controller=factory_plan&action=read&id=' . urlencode($id));
            return;
        }

        try {
            $this->workshopPlanModel->deleteWithRelations($id);
            $this->setFlash('success', 'Đã xóa kế hoạch xưởng. Vui lòng lập lại từ kế hoạch tổng nếu cần.');
        } catch (Throwable $exception) {
            Logger::error('Lỗi khi xóa kế hoạch xưởng ' . $id . ': ' . $exception->getMessage());
            $this->setFlash('danger', 'Không thể xóa kế hoạch xưởng. Vui lòng kiểm tra log.');
            $this->redirect('?controller=factory_plan&action=read&id=' . urlencode($id));
            return;
        }

        $this->redirect('?controller=factory_plan&action=index');
    }

    private function getVisibleWorkshops(): array
    {
        $user = $this->currentUser();
        $role = $user['ActualIdVaiTro'] ?? $user['IdVaiTro'] ?? null;

        if (in_array($role, ['VT_ADMIN', 'VT_BAN_GIAM_DOC', 'VT_QUANLY_XUONG'], true)) {
            return $this->workshopModel->getAllWithManagers();
        }

        $employeeId = $user['IdNhanVien'] ?? null;
        if ($role === 'VT_NHANVIEN_SANXUAT' && $employeeId) {
            $workshopIds = $this->assignmentModel->getWorkshopsByEmployee($employeeId);
            return $this->workshopModel->findByIds($workshopIds);
        }

        return [];
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

    private function normalizeSelectedWorkshop(?string $selected, array $visibleWorkshops): ?string
    {
        if ($selected) {
            foreach ($visibleWorkshops as $workshop) {
                if (($workshop['IdXuong'] ?? null) === $selected) {
                    return $selected;
                }
            }
        }

        if (count($visibleWorkshops) === 1) {
            return $visibleWorkshops[0]['IdXuong'] ?? null;
        }

        return null;
    }

    private function filterPlansByVisibleWorkshops(array $plans, array $visibleWorkshops, ?string $selectedWorkshop): array
    {
        $allowed = array_column($visibleWorkshops, 'IdXuong');
        $plans = array_values(array_filter($plans, static function (array $plan) use ($allowed): bool {
            return in_array($plan['IdXuong'] ?? null, $allowed, true);
        }));

        if ($selectedWorkshop) {
            $plans = array_values(array_filter($plans, static function (array $plan) use ($selectedWorkshop): bool {
                return ($plan['IdXuong'] ?? null) === $selectedWorkshop;
            }));
        }

        return $plans;
    }

    public function sendMaterialNotification(): void
    {
        header('Content-Type: application/json');
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['success' => false, 'message' => 'Invalid JSON input.']);
            return;
        }

        $requiredFields = ['IdNguyenLieu', 'TenNL', 'SoLuongCan', 'SoLuongTon', 'TenLo', 'IdKeHoachSanXuatXuong'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                echo json_encode(['success' => false, 'message' => 'Missing field: ' . $field]);
                return;
            }
        }

        $notificationDir = __DIR__ . '/../data/notification';
        if (!is_dir($notificationDir)) {
            mkdir($notificationDir, 0777, true);
        }

        $filename = 'notification_' . $data['IdKeHoachSanXuatXuong'] . '_' . $data['IdNguyenLieu'] . '_' . time() . '.json';
        $filepath = $notificationDir . '/' . $filename;

        $notificationContent = [
            'timestamp' => date('Y-m-d H:i:s'),
            'ke_hoach_san_xuat_xuong_id' => $data['IdKeHoachSanXuatXuong'],
            'nguyen_lieu' => [
                'id' => $data['IdNguyenLieu'],
                'ten' => $data['TenNL'],
                'so_luong_can' => $data['SoLuongCan'],
                'so_luong_ton' => $data['SoLuongTon'],
                'ten_lo' => $data['TenLo']
            ],
            'message' => 'Yêu cầu cung cấp nguyên liệu.'
        ];

        if (file_put_contents($filepath, json_encode($notificationContent, JSON_PRETTY_PRINT))) {
            echo json_encode(['success' => true, 'message' => 'Thông báo đã được tạo và lưu.', 'filename' => $filename]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể lưu thông báo.']);
        }
    }

    private function filterPlanByAccess(?array $plan): ?array
    {
        if (!$plan) {
            return null;
        }

        $visibleIds = array_column($this->getVisibleWorkshops(), 'IdXuong');
        if (in_array($plan['IdXuong'] ?? null, $visibleIds, true)) {
            return $plan;
        }

        $this->setFlash('danger', 'Bạn không có quyền xem hạng mục xưởng này.');
        $this->redirect('?controller=factory_plan&action=index');
        return null;
    }

    private function canModifyPlan(array $plan): bool
    {
        $user = $this->currentUser();
        $role = $user['ActualIdVaiTro'] ?? $user['IdVaiTro'] ?? null;

        if (in_array($role, ['VT_ADMIN', 'VT_BAN_GIAM_DOC'], true)) {
            return true;
        }

        if ($role === 'VT_QUANLY_XUONG') {
            $employeeId = $user['IdNhanVien'] ?? null;
            if (!$employeeId) {
                return false;
            }

            $managedWorkshops = $this->assignmentModel->getWorkshopsManagedBy($employeeId);
            return in_array($plan['IdXuong'] ?? null, $managedWorkshops, true);
        }

        return false;
    }

    private function calculateProgress(?string $start, ?string $end, ?string $status): array
    {
        $now = time();
        $percent = 0;
        $label = 'Chưa bắt đầu';

        $startTs = $start ? strtotime($start) : null;
        $endTs = $end ? strtotime($end) : null;

        if ($startTs && $endTs && $endTs > $startTs) {
            $percent = (int) round(min(1, max(0, ($now - $startTs) / ($endTs - $startTs))) * 100);
            if ($now > $endTs) {
                $label = 'Quá hạn';
            } else {
                $label = $percent >= 100 ? 'Đến hạn' : 'Đang thực hiện';
            }
        } elseif ($startTs && !$endTs) {
            $percent = $now >= $startTs ? 10 : 0;
            $label = $now >= $startTs ? 'Đang thực hiện' : 'Chưa bắt đầu';
        }

        if ($status && str_contains(mb_strtolower($status), 'hoàn thành')) {
            $percent = 100;
            $label = 'Hoàn thành';
        }

        return [
            'percent' => $percent,
            'label' => $label,
        ];
    }

    private function summarizeMaterialStatus(array $stocks, ?string $existingStatus): string
    {
        if ($existingStatus) {
            return $existingStatus;
        }

        foreach ($stocks as $item) {
            if (($item['SoLuongTon'] ?? 0) < ($item['SoLuongCan'] ?? 0)) {
                return 'Thiếu nguyên liệu';
            }
        }

        return empty($stocks) ? 'Chưa cấu hình' : 'Đủ nguyên liệu';
    }

    private function isMaterialSufficient(?string $status): bool
    {
        if (!$status) {
            return false;
        }

        $normalized = mb_strtolower(trim($status));
        return str_contains($normalized, 'đủ');
    }
}
