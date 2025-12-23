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
        $this->authorize(array_merge($this->getWorkshopManagerRoles(), ['VT_BAN_GIAM_DOC']));
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
        $materialCheckResult = $_SESSION['material_check_result'] ?? null;
        unset($_SESSION['material_check_result']);

        $typeConfig = $this->getWorkshopTypeConfig($plan['LoaiXuong'] ?? null);
        $materialEnabled = $typeConfig['supports_materials'] ?? true;
        $progressEnabled = $typeConfig['supports_progress'] ?? true;

        $materialSource = 'plan';
        $materialOptions = $materialEnabled ? $this->materialModel->all(500) : [];

        if ($plan && empty($materials) && $materialEnabled) {
            $materialSource = 'custom';
        }

        $hasAssignments = !empty($planAssignments);
        $canUpdateProgress = $plan
            && $progressEnabled
            && $this->isMaterialSufficient($plan['TinhTrangVatTu'] ?? null)
            && $hasAssignments;

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
            'canUpdateProgress' => $canUpdateProgress,
            'materialEnabled' => $materialEnabled,
            'progressEnabled' => $progressEnabled,
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
        if (!$this->supportsMaterials($plan)) {
            $this->setFlash('danger', 'Loại xưởng này không cần kiểm tra nguyên liệu.');
            $this->redirect('?controller=workshop_plan&action=read&id=' . urlencode($planId));
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

    public function assign(): void
    {
        $planId = $_GET['id'] ?? null;
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

        $this->workShiftModel->ensureDefaultShiftsForPlan(
            $planId,
            $plan['ThoiGianBatDau'] ?? null,
            $plan['ThoiGianKetThuc'] ?? null
        );

        $assignments = $this->assignmentModel->getAssignmentsByWorkshop($plan['IdXuong'] ?? '');
        $warehouseEmployees = $assignments['nhan_vien_kho'] ?? [];
        $productionEmployees = $assignments['nhan_vien_san_xuat'] ?? [];
        $availableEmployees = array_merge($warehouseEmployees, $productionEmployees);
        $planAssignments = $this->planAssignmentModel->getByPlan($planId);
        $workShifts = $this->workShiftModel->getShiftsByPlan($planId);

        $this->render('workshop_plan/assign', [
            'title' => 'Phân công kế hoạch xưởng',
            'plan' => $plan,
            'availableEmployees' => $availableEmployees,
            'planAssignments' => $planAssignments,
            'workShifts' => $workShifts,
        ]);
    }

    public function saveAssignments(): void
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

        $assignmentsInput = $_POST['assignments'] ?? [];
        if (!is_array($assignmentsInput)) {
            $assignmentsInput = [];
        }

        $workShifts = $this->workShiftModel->getShiftsByPlan($planId);
        $now = time();
        $today = date('Y-m-d');
        $editableShiftIds = [];
        $addOnlyShiftIds = [];
        foreach ($workShifts as $shift) {
            $shiftId = $shift['IdCaLamViec'] ?? null;
            if (!$shiftId) {
                continue;
            }
            $start = $shift['ThoiGianBatDau'] ?? null;
            $end = $shift['ThoiGianKetThuc'] ?? null;
            $startTs = $start ? strtotime($start) : null;
            $endTs = $end ? strtotime($end) : null;
            $shiftDate = $shift['NgayLamViec'] ?? '';
            $isToday = $shiftDate === $today;
            $isPastDate = $shiftDate !== '' && strcmp($shiftDate, $today) < 0;
            $isFutureDate = $shiftDate !== '' && strcmp($shiftDate, $today) > 0;
            $isInProgress = $isToday && $startTs && $endTs && $now >= $startTs && $now <= $endTs;
            $isAfterEnd = $isToday && $endTs && $now > $endTs;

            if ($isPastDate || $isAfterEnd) {
                continue;
            }

            if ($isFutureDate) {
                $editableShiftIds[$shiftId] = true;
                continue;
            }

            if ($isToday && !$isAfterEnd) {
                $addOnlyShiftIds[$shiftId] = true;
            }
        }
        foreach ($assignmentsInput as $shiftId => $employees) {
            if (!isset($editableShiftIds[$shiftId]) && !isset($addOnlyShiftIds[$shiftId])) {
                unset($assignmentsInput[$shiftId]);
            }
        }

        try {
            if (empty($assignmentsInput)) {
                $this->setFlash('warning', 'Không có ca nào hợp lệ để cập nhật phân công.');
            } else {
                $assignmentRole = $this->getAssignmentRole($plan);
                $this->planAssignmentModel->syncByShiftPolicy(
                    $planId,
                    $assignmentsInput,
                    $editableShiftIds,
                    $addOnlyShiftIds,
                    $assignmentRole
                );
                $this->setFlash('success', 'Đã cập nhật phân công nhân sự theo ca.');
            }
        } catch (Throwable $exception) {
            Logger::error('Không thể cập nhật phân công kế hoạch ' . $planId . ': ' . $exception->getMessage());
            $this->setFlash('danger', 'Không thể lưu phân công, vui lòng kiểm tra log.');
        }

        $materialStatus = $plan['TinhTrangVatTu'] ?? null;
        $hasAssignments = !empty($this->planAssignmentModel->getByPlan($planId));
        $this->updatePlanStatus($planId, $materialStatus, $hasAssignments);

        $this->redirect('?controller=workshop_plan&action=assign&id=' . urlencode($planId));
    }

    public function progress(): void
    {
        $planId = $_GET['id'] ?? null;
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
        if (!$this->supportsProgress($plan)) {
            $this->setFlash('danger', 'Loại xưởng này chỉ hỗ trợ phân công, không cập nhật tiến độ.');
            $this->redirect('?controller=workshop_plan&action=assign&id=' . urlencode($planId));
            return;
        }

        $this->workShiftModel->ensureDefaultShiftsForPlan(
            $planId,
            $plan['ThoiGianBatDau'] ?? null,
            $plan['ThoiGianKetThuc'] ?? null
        );

        $planAssignments = $this->planAssignmentModel->getByPlan($planId);
        $assignmentShiftIds = array_values(array_unique(array_filter(array_column($planAssignments, 'IdCaLamViec'))));
        $workShifts = $this->workShiftModel->getShiftsByPlan($planId);
        $availableShifts = array_values(array_filter($workShifts, function (array $shift) use ($assignmentShiftIds): bool {
            $shiftId = $shift['IdCaLamViec'] ?? null;
            return $shiftId && in_array($shiftId, $assignmentShiftIds, true);
        }));

        $hasAssignments = !empty($planAssignments);
        $canUpdateProgress = $this->supportsProgress($plan)
            && $this->isMaterialSufficient($plan['TinhTrangVatTu'] ?? null)
            && $hasAssignments;

        $this->render('workshop_plan/progress', [
            'title' => 'Cập nhật tiến độ cuối ca',
            'plan' => $plan,
            'availableShifts' => $availableShifts,
            'canUpdateProgress' => $canUpdateProgress,
        ]);
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
        if (!$this->supportsProgress($plan)) {
            $this->setFlash('danger', 'Loại xưởng này chỉ hỗ trợ phân công, không cập nhật tiến độ.');
            $this->redirect('?controller=workshop_plan&action=assign&id=' . urlencode($planId));
            return;
        }

        $shiftId = $_POST['shift_id'] ?? null;
        $quantity = isset($_POST['produced_quantity']) ? (int) $_POST['produced_quantity'] : 0;
        $lotName = trim($_POST['lot_name'] ?? '');

        if (!$shiftId || $quantity <= 0) {
            $this->setFlash('danger', 'Vui lòng chọn ca làm việc và nhập số lượng thành phẩm.');
            $this->redirect('?controller=workshop_plan&action=progress&id=' . urlencode($planId));
            return;
        }

        $warehouse = $this->warehouseModel->findFinishedWarehouseByWorkshop($plan['IdXuong'] ?? null);
        if (!$warehouse) {
            $this->setFlash('danger', 'Không tìm thấy kho thành phẩm cho xưởng.');
            $this->redirect('?controller=workshop_plan&action=progress&id=' . urlencode($planId));
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
            $this->redirect('?controller=workshop_plan&action=progress&id=' . urlencode($planId));
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

        $this->redirect('?controller=workshop_plan&action=progress&id=' . urlencode($planId));
    }

    private function updatePlanStatus(string $planId, ?string $materialStatus, bool $hasAssignments): void
    {
        $payload = [];
        $plan = $this->workshopPlanModel->findWithRelations($planId);
        $assignmentOnly = $plan && !$this->supportsMaterials($plan);

        if (!$assignmentOnly && $materialStatus !== null) {
            $payload['TinhTrangVatTu'] = $materialStatus;
        }

        if ($assignmentOnly) {
            $payload['TrangThai'] = $hasAssignments ? 'Đang thực hiện' : 'Chờ phân công';
        } elseif ($this->isMaterialSufficient($materialStatus)) {
            $payload['TrangThai'] = $hasAssignments ? 'Đang sản xuất' : 'Chờ phân công';
        } else {
            $payload['TrangThai'] = 'Chờ bổ sung';
        }

        if (!empty($payload)) {
            try {
                $this->workshopPlanModel->update($planId, $payload);
            } catch (PDOException $exception) {
                Logger::error('Không thể cập nhật trạng thái kế hoạch ' . $planId . ': ' . $exception->getMessage());
                $this->setFlash('danger', $this->resolveDateRuleMessage($exception->getMessage()));
            }
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

    private function resolveDateRuleMessage(string $message): string
    {
        if (str_contains($message, 'Ngày bắt đầu không được bé hơn ngày hiện tại')) {
            return 'Ngày bắt đầu không được bé hơn ngày hiện tại.';
        }
        if (str_contains($message, 'Ngày kết thúc không được bé hơn ngày bắt đầu')) {
            return 'Ngày kết thúc không được bé hơn ngày bắt đầu.';
        }

        return 'Không thể cập nhật trạng thái kế hoạch, vui lòng kiểm tra lại thời gian.';
    }

    private function getWorkshopTypeConfig(?string $workshopType): array
    {
        $normalized = mb_strtolower(trim((string) $workshopType));

        if ($normalized === 'xưởng kiểm định') {
            return [
                'supports_materials' => false,
                'supports_progress' => false,
                'assignment_role' => 'nhan_vien_san_xuat',
            ];
        }

        if ($normalized === 'xưởng lưu trữ hàng hóa') {
            return [
                'supports_materials' => false,
                'supports_progress' => false,
                'assignment_role' => 'nhan_vien_kho',
            ];
        }

        return [
            'supports_materials' => true,
            'supports_progress' => true,
            'assignment_role' => 'nhan_vien_san_xuat',
        ];
    }

    private function getWorkshopManagerRoles(): array
    {
        return [
            'VT_TRUONG_XUONG_KIEM_DINH',
            'VT_TRUONG_XUONG_LAP_RAP_DONG_GOI',
            'VT_TRUONG_XUONG_SAN_XUAT',
            'VT_TRUONG_XUONG_LUU_TRU',
        ];
    }

    private function supportsMaterials(array $plan): bool
    {
        $config = $this->getWorkshopTypeConfig($plan['LoaiXuong'] ?? null);
        return $config['supports_materials'] ?? true;
    }

    private function supportsProgress(array $plan): bool
    {
        $config = $this->getWorkshopTypeConfig($plan['LoaiXuong'] ?? null);
        return $config['supports_progress'] ?? true;
    }

    private function getAssignmentRole(array $plan): string
    {
        $config = $this->getWorkshopTypeConfig($plan['LoaiXuong'] ?? null);
        return $config['assignment_role'] ?? 'nhan_vien_san_xuat';
    }
}
