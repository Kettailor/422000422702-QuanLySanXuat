<?php

class Workshop_planController extends Controller
{
    private WorkshopPlan $workshopPlanModel;
    private WorkshopPlanMaterialDetail $materialDetailModel;
    private WorkshopPlanHistory $historyModel;
    private WarehouseRequest $warehouseRequestModel;
    private ProductComponentMaterial $componentMaterialModel;
    private Material $materialModel;

    public function __construct()
    {
        $this->authorize(['VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC']);
        $this->workshopPlanModel = new WorkshopPlan();
        $this->materialDetailModel = new WorkshopPlanMaterialDetail();
        $this->historyModel = new WorkshopPlanHistory();
        $this->warehouseRequestModel = new WarehouseRequest();
        $this->componentMaterialModel = new ProductComponentMaterial();
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
            $configurationId = $plan['AssignmentConfigurationId'] ?? null;
            if ($configurationId) {
                $materialSource = 'configuration';
                $materials = $this->buildMaterialFromConfiguration(
                    $configurationId,
                    (int) ($plan['SoLuong'] ?? 0)
                );
                if (!empty($materials)) {
                    $requirements = array_map(static function (array $material): array {
                        return [
                            'id' => $material['IdNguyenLieu'] ?? null,
                            'required' => $material['SoLuongKeHoach'] ?? 0,
                        ];
                    }, $materials);
                    try {
                        $this->materialDetailModel->replaceForPlan($plan['IdKeHoachSanXuatXuong'], $requirements);
                        $materials = $this->materialDetailModel->getByWorkshopPlan($plan['IdKeHoachSanXuatXuong']);
                        $materialSource = 'plan';
                    } catch (Throwable $exception) {
                        Logger::error('Không thể tự động lưu nguyên liệu cho kế hoạch ' . $plan['IdKeHoachSanXuatXuong'] . ': ' . $exception->getMessage());
                    }
                }
            } else {
                $materialSource = 'custom';
            }
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

    private function buildMaterialFromConfiguration(string $configurationId, int $quantity): array
    {
        $materials = $this->componentMaterialModel->getMaterialsForComponent($configurationId);
        if (empty($materials)) {
            return [];
        }

        $materialIds = array_column($materials, 'id');
        $inventory = $this->materialModel->findMany($materialIds);

        $result = [];
        foreach ($materials as $material) {
            $ratioValue = $material['quantity_per_unit'] ?? null;
            $ratio = is_numeric($ratioValue) ? (float) $ratioValue : 1.0;
            $required = (int) round(max(1, $quantity) * $ratio);
            $stock = (int) ($inventory[$material['id']]['SoLuong'] ?? 0);
            $result[] = [
                'IdNguyenLieu' => $material['id'],
                'TenNL' => $inventory[$material['id']]['TenNL'] ?? ($material['label'] ?? $material['id']),
                'SoLuongKeHoach' => $required,
                'DonVi' => $material['unit'] ?? ($inventory[$material['id']]['DonVi'] ?? null),
                'SoLuongTonKho' => $stock,
            ];
        }

        return $result;
    }
}
