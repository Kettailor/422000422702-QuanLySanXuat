<?php

class Workshop_planController extends Controller
{
    private WorkshopPlan $workshopPlanModel;
    private WorkshopPlanMaterialDetail $materialDetailModel;
    private WorkshopPlanHistory $historyModel;
    private WarehouseRequest $warehouseRequestModel;
    private Material $materialModel;

    public function __construct()
    {
        $this->authorize(['VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC']);
        $this->workshopPlanModel = new WorkshopPlan();
        $this->materialDetailModel = new WorkshopPlanMaterialDetail();
        $this->historyModel = new WorkshopPlanHistory();
        $this->warehouseRequestModel = new WarehouseRequest();
        $this->materialModel = new Material();
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $plan = $id ? $this->workshopPlanModel->findWithRelations($id) : null;
        $materials = $id ? $this->materialDetailModel->getByWorkshopPlan($id) : [];
        $history = $id ? $this->historyModel->getByPlan($id) : [];
        $warehouseRequests = $id ? $this->warehouseRequestModel->getByPlan($id) : [];
        $materialCheckResult = $_SESSION['material_check_result'] ?? null;
        unset($_SESSION['material_check_result']);

        $materialSource = 'plan';
        $materialOptions = $this->materialModel->all(500);

        if ($plan && empty($materials)) {
            $materialSource = 'inventory';
            $materials = $this->buildMaterialFromInventory();
        }

        $this->render('workshop_plan/read', [
            'title' => 'Kiểm tra nguyên liệu kế hoạch xưởng',
            'plan' => $plan,
            'materials' => $materials,
            'history' => $history,
            'warehouseRequests' => $warehouseRequests,
            'materialCheckResult' => $materialCheckResult,
            'materialSource' => $materialSource,
            'materialOptions' => $materialOptions,
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

        $materialsInput = $_POST['materials'] ?? [];
        $note = trim($_POST['note'] ?? '');
        $persistMaterials = ($_POST['persist_materials'] ?? '') === '1';

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

        if ($persistMaterials || empty($this->materialDetailModel->getByWorkshopPlan($planId))) {
            try {
                $this->materialDetailModel->replaceForPlan($planId, $requirements);
            } catch (Throwable $exception) {
                Logger::error('Không thể lưu danh sách nguyên liệu cho kế hoạch ' . $planId . ': ' . $exception->getMessage());
            }
        }

        $this->workshopPlanModel->update($planId, ['TinhTrangVatTu' => $status]);

        if ($checkResult['is_sufficient']) {
            $this->setFlash('success', 'Nguyên liệu đáp ứng đủ nhu cầu thực tế.');
        } else {
            $this->workshopPlanModel->update($planId, ['TrangThai' => 'Chờ bổ sung']);
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

    private function buildMaterialFromInventory(): array
    {
        $inventory = $this->materialModel->all(500);

        return array_map(static function (array $row): array {
            return [
                'IdNguyenLieu' => $row['IdNguyenLieu'] ?? '',
                'TenNL' => $row['TenNL'] ?? ($row['IdNguyenLieu'] ?? ''),
                'SoLuongKeHoach' => 0,
                'DonVi' => $row['DonVi'] ?? null,
                'SoLuongTonKho' => $row['SoLuong'] ?? 0,
            ];
        }, $inventory);
    }
}
