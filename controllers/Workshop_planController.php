<?php

class Workshop_planController extends Controller
{
    private WorkshopPlan $workshopPlanModel;
    private WorkshopPlanMaterialDetail $materialDetailModel;
    private WorkshopPlanHistory $historyModel;
    private WarehouseRequest $warehouseRequestModel;
    private WorkshopAssignment $assignmentModel;
    private WorkshopPlanAssignment $planAssignmentModel;
    private WorkShift $workShiftModel;
    private InventoryLot $inventoryLotModel;
    private Warehouse $warehouseModel;
    private Material $materialModel;

    public function __construct()
    {
        $this->authorize(['VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC']);
        $this->workshopPlanModel = new WorkshopPlan();
        $this->materialDetailModel = new WorkshopPlanMaterialDetail();
        $this->historyModel = new WorkshopPlanHistory();
        $this->warehouseRequestModel = new WarehouseRequest();
        $this->assignmentModel = new WorkshopAssignment();
        $this->planAssignmentModel = new WorkshopPlanAssignment();
        $this->workShiftModel = new WorkShift();
        $this->inventoryLotModel = new InventoryLot();
        $this->warehouseModel = new Warehouse();
        $this->materialModel = new Material();
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $plan = $id ? $this->workshopPlanModel->findWithRelations($id) : null;
        $materials = $id ? $this->materialDetailModel->getByWorkshopPlan($id) : [];
        $history = $id ? $this->historyModel->getByPlan($id) : [];
        $warehouseRequests = $id ? $this->warehouseRequestModel->getByPlan($id) : [];
        $planAssignments = $id ? $this->planAssignmentModel->getByPlan($id) : [];
        $availableEmployees = [];
        if ($plan && !empty($plan['IdXuong'])) {
            $assignments = $this->assignmentModel->getAssignmentsByWorkshop($plan['IdXuong']);
            $availableEmployees = $assignments['nhan_vien_san_xuat'] ?? [];
        }
        $workShifts = $id ? $this->workShiftModel->getShiftsByPlan($id) : [];
        $materialCheckResult = $_SESSION['material_check_result'] ?? null;
        unset($_SESSION['material_check_result']);

        $materialSource = 'plan';
        $materialOptions = $this->materialModel->all(500);

        if ($plan && empty($materials)) {
            $materialSource = 'custom';
        }

        $hasAssignments = !empty($planAssignments);
        $canUpdateProgress = $plan && $this->isMaterialSufficient($plan['TinhTrangVatTu'] ?? null) && $hasAssignments;

        $this->render('workshop_plan/read', [
            'title' => 'Kiểm tra nguyên liệu kế hoạch xưởng',
            'plan' => $plan,
            'materials' => $materials,
            'history' => $history,
            'warehouseRequests' => $warehouseRequests,
            'materialCheckResult' => $materialCheckResult,
            'materialSource' => $materialSource,
            'materialOptions' => $materialOptions,
            'planAssignments' => $planAssignments,
            'availableEmployees' => $availableEmployees,
            'canUpdateProgress' => $canUpdateProgress,
            'workShifts' => $workShifts,
        ]);
    }

    public function checkMaterials(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=factory_plan&action=index');
            return;
        }

        $planId = $_POST['IdKeHoachSanXuatXuong'] ?? null;
        if (!$planId) {
            $this->setFlash('danger', 'Thiếu mã kế hoạch xưởng.');
            $this->redirect('?controller=factory_plan&action=index');
            return;
        }

        $plan = $this->workshopPlanModel->findWithRelations($planId);
        if (!$plan) {
            $this->setFlash('danger', 'Không tìm thấy kế hoạch xưởng.');
            $this->redirect('?controller=factory_plan&action=index');
            return;
        }

        $materialsInput = $_POST['materials'] ?? [];
        $note = trim($_POST['note'] ?? '');

        $requirements = [];
        foreach ($materialsInput as $input) {
            $materialId = $input['IdNguyenLieu'] ?? $input['id'] ?? null;
            if (!$materialId) {
                continue;
            }
            $required = (int) ($input['required'] ?? $input['SoLuongThucTe'] ?? $input['SoLuong'] ?? 0);
            if ($required < 0) {
                $required = 0;
            }
            $requirements[] = [
                'id' => $materialId,
                'required' => $required,
            ];
        }

        if (empty($requirements)) {
            $this->setFlash('danger', 'Vui lòng nhập nhu cầu nguyên liệu.');
            $this->redirect('?controller=workshop_plan&action=read&id=' . urlencode($planId));
            return;
        }

        $checkResult = Material::checkAvailability($requirements);

        $currentUser = $this->currentUser();
        $actorId = $currentUser['IdNhanVien'] ?? null;

        $status = $checkResult['is_sufficient'] ? 'Đủ nguyên liệu' : 'Thiếu nguyên liệu';
        $action = $checkResult['is_sufficient'] ? 'Kiểm tra tồn kho' : 'Tạo yêu cầu bổ sung';
        $requestId = null;

        try {
            $this->materialDetailModel->replaceForPlan($planId, $requirements);
            $this->notifyWarehouseAssignments($plan, $requirements);
        } catch (Throwable $exception) {
            Logger::error('Không thể lưu danh sách nguyên liệu cho kế hoạch ' . $planId . ': ' . $exception->getMessage());
        }

        $hasAssignments = !empty($this->planAssignmentModel->getByPlan($planId));
        $this->updatePlanStatus($planId, $status, $hasAssignments);

        if ($checkResult['is_sufficient']) {
            $this->setFlash('success', 'Nguyên liệu đáp ứng đủ nhu cầu thực tế.');
        } else {
            $requestId = $this->warehouseRequestModel->createFromShortages($planId, $checkResult['items'], $actorId, $note);
            $this->setFlash('warning', 'Thiếu nguyên liệu, đã chuyển kế hoạch sang trạng thái "Chờ bổ sung" và tạo yêu cầu kho.');
        }

        $details = [
            'materials' => $checkResult['items'],
            'note' => $note,
            'checked_at' => date('c'),
        ];

        $this->historyModel->log($planId, $status, $action, $note, $actorId, $details, $requestId);

        $_SESSION['material_check_result'] = $checkResult;

        $this->redirect('?controller=workshop_plan&action=read&id=' . urlencode($planId));
    }

    public function assignEmployees(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=factory_plan&action=index');
            return;
        }

        $planId = $_POST['IdKeHoachSanXuatXuong'] ?? null;
        if (!$planId) {
            $this->setFlash('danger', 'Thiếu mã kế hoạch xưởng.');
            $this->redirect('?controller=factory_plan&action=index');
            return;
        }

        $plan = $this->workshopPlanModel->findWithRelations($planId);
        if (!$plan) {
            $this->setFlash('danger', 'Không tìm thấy kế hoạch xưởng.');
            $this->redirect('?controller=factory_plan&action=index');
            return;
        }

        $employeeIds = $_POST['employee_ids'] ?? [];
        if (!is_array($employeeIds)) {
            $employeeIds = [];
        }

        try {
            $this->planAssignmentModel->replaceForPlan($planId, $employeeIds, 'nhan_vien_san_xuat');
            $this->setFlash('success', 'Đã cập nhật phân công nhân sự.');
        } catch (Throwable $exception) {
            Logger::error('Không thể cập nhật phân công kế hoạch ' . $planId . ': ' . $exception->getMessage());
            $this->setFlash('danger', 'Không thể lưu phân công, vui lòng kiểm tra log.');
        }

        $materialStatus = $plan['TinhTrangVatTu'] ?? null;
        $hasAssignments = !empty($this->planAssignmentModel->getByPlan($planId));
        $this->updatePlanStatus($planId, $materialStatus, $hasAssignments);

        $this->redirect('?controller=workshop_plan&action=read&id=' . urlencode($planId));
    }

    public function updateProgress(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=factory_plan&action=index');
            return;
        }

        $planId = $_POST['IdKeHoachSanXuatXuong'] ?? null;
        if (!$planId) {
            $this->setFlash('danger', 'Thiếu mã kế hoạch xưởng.');
            $this->redirect('?controller=factory_plan&action=index');
            return;
        }

        $plan = $this->workshopPlanModel->findWithRelations($planId);
        if (!$plan) {
            $this->setFlash('danger', 'Không tìm thấy kế hoạch xưởng.');
            $this->redirect('?controller=factory_plan&action=index');
            return;
        }

        $shiftId = $_POST['shift_id'] ?? null;
        $quantity = isset($_POST['produced_quantity']) ? (int) $_POST['produced_quantity'] : 0;
        $lotName = trim($_POST['lot_name'] ?? '');

        if (!$shiftId || $quantity <= 0) {
            $this->setFlash('danger', 'Vui lòng chọn ca làm việc và nhập số lượng thành phẩm.');
            $this->redirect('?controller=workshop_plan&action=read&id=' . urlencode($planId));
            return;
        }

        $warehouse = $this->warehouseModel->findFinishedWarehouseByWorkshop($plan['IdXuong'] ?? null);
        if (!$warehouse) {
            $this->setFlash('danger', 'Không tìm thấy kho thành phẩm cho xưởng.');
            $this->redirect('?controller=workshop_plan&action=read&id=' . urlencode($planId));
            return;
        }

        $lotId = $this->inventoryLotModel->generateLotId('LOTP');
        $lotPayload = [
            'IdLo' => $lotId,
            'TenLo' => $lotName !== '' ? $lotName : ('Lô thành phẩm ' . $planId),
            'SoLuong' => $quantity,
            'LoaiLo' => 'Thành phẩm',
            'IdSanPham' => $plan['IdSanPham'] ?? null,
            'IdKho' => $warehouse['IdKho'] ?? null,
        ];

        if (empty($lotPayload['IdSanPham']) || empty($lotPayload['IdKho'])) {
            $this->setFlash('danger', 'Thiếu thông tin sản phẩm hoặc kho thành phẩm.');
            $this->redirect('?controller=workshop_plan&action=read&id=' . urlencode($planId));
            return;
        }

        try {
            $this->inventoryLotModel->createLot($lotPayload);
            $status = ($quantity >= (int) ($plan['SoLuong'] ?? 0)) ? 'Hoàn thành' : 'Đang sản xuất';
            $this->workshopPlanModel->update($planId, ['TrangThai' => $status]);
            $this->historyModel->log(
                $planId,
                $status,
                'Cập nhật tiến độ cuối ca',
                'Sản lượng: ' . $quantity . ' (ca ' . $shiftId . ')',
                $this->currentUser()['IdNhanVien'] ?? null,
                [
                    'shift_id' => $shiftId,
                    'lot_id' => $lotId,
                    'quantity' => $quantity,
                ],
                null
            );
            $this->setFlash('success', 'Đã cập nhật tiến độ và tạo lô thành phẩm.');
        } catch (Throwable $exception) {
            Logger::error('Không thể cập nhật tiến độ kế hoạch ' . $planId . ': ' . $exception->getMessage());
            $this->setFlash('danger', 'Không thể cập nhật tiến độ, vui lòng kiểm tra log.');
        }

        $this->redirect('?controller=workshop_plan&action=read&id=' . urlencode($planId));
    }

    private function updatePlanStatus(string $planId, ?string $materialStatus, bool $hasAssignments): void
    {
        $payload = [];
        if ($materialStatus !== null) {
            $payload['TinhTrangVatTu'] = $materialStatus;
        }

        if ($this->isMaterialSufficient($materialStatus)) {
            $payload['TrangThai'] = $hasAssignments ? 'Đang sản xuất' : 'Chờ phân công';
        } else {
            $payload['TrangThai'] = 'Chờ bổ sung';
        }

        if (!empty($payload)) {
            $this->workshopPlanModel->update($planId, $payload);
        }
    }

    private function isMaterialSufficient(?string $status): bool
    {
        if (!$status) {
            return false;
        }

        $normalized = mb_strtolower(trim($status));
        return str_contains($normalized, 'đủ');
    }

    private function notifyWarehouseAssignments(array $plan, array $requirements): void
    {
        $workshopId = $plan['IdXuong'] ?? null;
        if (!$workshopId) {
            return;
        }

        $assignments = $this->assignmentModel->getAssignmentsByWorkshop($workshopId);
        $warehouseEmployees = $assignments['nhan_vien_kho'] ?? [];
        if (empty($warehouseEmployees)) {
            return;
        }

        $notificationStore = new NotificationStore();
        $message = sprintf(
            'Kế hoạch xưởng %s cập nhật nguyên liệu (%d mục). Vui lòng kiểm tra tồn kho.',
            $plan['IdKeHoachSanXuatXuong'] ?? '',
            count($requirements)
        );

        $entries = [];
        foreach ($warehouseEmployees as $employee) {
            $employeeId = $employee['IdNhanVien'] ?? null;
            if (!$employeeId) {
                continue;
            }

            $entries[] = [
                'channel' => 'warehouse_assignment',
                'recipient' => $employeeId,
                'title' => 'Cập nhật nguyên liệu kế hoạch xưởng',
                'message' => $message,
                'link' => '?controller=workshop_plan&action=read&id=' . urlencode($plan['IdKeHoachSanXuatXuong']),
                'metadata' => [
                    'workshop_id' => $workshopId,
                    'workshop_plan_id' => $plan['IdKeHoachSanXuatXuong'] ?? null,
                    'materials' => $requirements,
                ],
            ];
        }

        $notificationStore->pushMany($entries);
    }
}
