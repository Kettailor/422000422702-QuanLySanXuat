<?php

class Workshop_plan_personalController extends Controller
{
    private WorkshopPlan $workshopPlanModel;
    private WorkshopPlanAssignment $planAssignmentModel;
    private WorkshopPlanMaterialDetail $materialDetailModel;
    private WorkshopAssignment $workshopAssignmentModel;

    public function __construct()
    {
        $this->authorize(['VT_NHANVIEN_SANXUAT', 'VT_NHANVIEN_KHO']);
        $this->workshopPlanModel = new WorkshopPlan();
        $this->planAssignmentModel = new WorkshopPlanAssignment();
        $this->materialDetailModel = new WorkshopPlanMaterialDetail();
        $this->workshopAssignmentModel = new WorkshopAssignment();
    }

    public function index(): void
    {
        $user = $this->currentUser();
        $employeeId = $user['IdNhanVien'] ?? null;
        $role = $user['IdVaiTro'] ?? null;

        $assignedPlanIds = $employeeId ? $this->planAssignmentModel->getPlanIdsByEmployee($employeeId) : [];
        $plans = [];

        if ($assignedPlanIds) {
            $plans = $this->workshopPlanModel->getDetailedPlans(200);
            $allowed = array_fill_keys($assignedPlanIds, true);
            $plans = array_values(array_filter($plans, static function (array $plan) use ($allowed): bool {
                $planId = $plan['IdKeHoachSanXuatXuong'] ?? null;
                return $planId !== null && isset($allowed[$planId]);
            }));
        } elseif ($role === 'VT_NHANVIEN_KHO' && $employeeId) {
            $workshopIds = $this->workshopAssignmentModel->getWorkshopsByEmployee($employeeId);
            if ($workshopIds) {
                $plans = [];
                foreach ($workshopIds as $workshopId) {
                    $plans = array_merge($plans, $this->workshopPlanModel->getDashboardPlans($workshopId));
                }
            }
        }

        $this->render('workshop_plan_personal/index', [
            'title' => 'Kế hoạch xưởng được phân công',
            'plans' => $plans,
            'employeeId' => $employeeId,
        ]);
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->setFlash('warning', 'Thiếu mã kế hoạch xưởng.');
            $this->redirect('?controller=workshop_plan_personal&action=index');
            return;
        }

        $user = $this->currentUser();
        $employeeId = $user['IdNhanVien'] ?? null;
        $role = $user['IdVaiTro'] ?? null;

        $assignedPlanIds = $employeeId ? $this->planAssignmentModel->getPlanIdsByEmployee($employeeId) : [];
        $workshopIds = $employeeId && $role === 'VT_NHANVIEN_KHO'
            ? $this->workshopAssignmentModel->getWorkshopsByEmployee($employeeId)
            : [];

        $plan = $this->workshopPlanModel->findWithRelations($id);
        if (!$plan) {
            $this->setFlash('warning', 'Không tìm thấy kế hoạch xưởng.');
            $this->redirect('?controller=workshop_plan_personal&action=index');
            return;
        }

        $planId = $plan['IdKeHoachSanXuatXuong'] ?? null;
        $isAssigned = $planId && in_array($planId, $assignedPlanIds, true);
        $isWorkshopMember = $plan['IdXuong'] && in_array($plan['IdXuong'], $workshopIds, true);

        if (!$isAssigned && !$isWorkshopMember) {
            $this->setFlash('danger', 'Bạn không được phép xem kế hoạch này.');
            $this->redirect('?controller=workshop_plan_personal&action=index');
            return;
        }

        $materials = $this->materialDetailModel->getByWorkshopPlan($id);
        $assignments = $this->planAssignmentModel->getByPlan($id);
        if ($employeeId) {
            $assignments = array_values(array_filter($assignments, static function (array $row) use ($employeeId): bool {
                return ($row['IdNhanVien'] ?? null) === $employeeId;
            }));
        }

        $this->render('workshop_plan_personal/read', [
            'title' => 'Chi tiết kế hoạch xưởng',
            'plan' => $plan,
            'materials' => $materials,
            'assignments' => $assignments,
        ]);
    }
}
