<?php

class WorkshopController extends Controller
{
    private Workshop $workshopModel;
    private WorkshopPlan $workshopPlanModel;
    private ProductComponentMaterial $componentMaterialModel;
    private Material $materialModel;

    public function __construct()
    {
        $this->authorize(['VT_QUANLY_XUONG', 'VT_BAN_GIAM_DOC']);
        $this->workshopModel = new Workshop();
        $this->workshopPlanModel = new WorkshopPlan();
        $this->componentMaterialModel = new ProductComponentMaterial();
        $this->materialModel = new Material();
    }

    public function index(): void
    {
        $workshops = $this->workshopModel->getAllWithManagers();
        $summary = $this->workshopModel->getCapacitySummary();
        $statusDistribution = $this->workshopModel->getStatusDistribution();

        $this->render('workshop/index', [
            'title' => 'Quản lý xưởng sản xuất',
            'workshops' => $workshops,
            'summary' => $summary,
            'statusDistribution' => $statusDistribution,
        ]);
    }

    public function create(): void
    {
        $this->render('workshop/create', [
            'title' => 'Thêm xưởng mới',
        ]);
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=workshop&action=index');
        }

        $data = $this->extractWorkshopData($_POST);
        $data['IdXuong'] = $data['IdXuong'] ?: uniqid('XUONG');

        try {
            $this->workshopModel->create($data);
            $this->setFlash('success', 'Đã thêm xưởng sản xuất mới.');
        } catch (Throwable $exception) {
            $this->setFlash('danger', 'Không thể thêm xưởng: ' . $exception->getMessage());
        }

        $this->redirect('?controller=workshop&action=index');
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Không xác định được xưởng cần cập nhật.');
            $this->redirect('?controller=workshop&action=index');
        }

        $workshop = $this->workshopModel->find($id);

        $this->render('workshop/edit', [
            'title' => 'Cập nhật thông tin xưởng',
            'workshop' => $workshop,
        ]);
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=workshop&action=index');
        }

        $id = $_POST['IdXuong'] ?? null;
        if (!$id) {
            $this->setFlash('danger', 'Không xác định được xưởng cần cập nhật.');
            $this->redirect('?controller=workshop&action=index');
        }

        $data = $this->extractWorkshopData($_POST);
        unset($data['IdXuong']);

        try {
            $this->workshopModel->update($id, $data);
            $this->setFlash('success', 'Cập nhật thông tin xưởng thành công.');
        } catch (Throwable $exception) {
            $this->setFlash('danger', 'Không thể cập nhật xưởng: ' . $exception->getMessage());
        }

        $this->redirect('?controller=workshop&action=index');
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $this->workshopModel->delete($id);
                $this->setFlash('success', 'Đã xóa xưởng sản xuất.');
            } catch (Throwable $exception) {
                $this->setFlash('danger', 'Không thể xóa xưởng: ' . $exception->getMessage());
            }
        }

        $this->redirect('?controller=workshop&action=index');
    }

    public function read(): void
    {
        $id = $_GET['id'] ?? null;
        $workshop = $id ? $this->workshopModel->find($id) : null;

        $this->render('workshop/read', [
            'title' => 'Chi tiết xưởng sản xuất',
            'workshop' => $workshop,
        ]);
    }

    public function dashboard(): void
    {
        $workshops = $this->workshopModel->getAllWithManagers();
        $selectedWorkshop = $_GET['workshop'] ?? null;
        $selectedWorkshop = $selectedWorkshop !== '' ? $selectedWorkshop : null;

        $plans = $this->workshopPlanModel->getDashboardPlans($selectedWorkshop);

        $componentIds = array_values(array_filter(array_map(fn ($plan) => $plan['IdCongDoan'] ?? null, $plans)));
        $materialsByComponent = $this->componentMaterialModel->getMaterialsForComponents($componentIds);

        $materialIds = [];
        foreach ($materialsByComponent as $materials) {
            foreach ($materials as $material) {
                $materialId = $material['id'] ?? null;
                if ($materialId) {
                    $materialIds[$materialId] = true;
                }
            }
        }

        $inventory = [];
        if (!empty($materialIds)) {
            try {
                $inventory = $this->materialModel->findMany(array_keys($materialIds));
            } catch (Throwable $exception) {
                $inventory = [];
            }
        }

        $grouped = [];
        $metrics = [
            'total_plans' => 0,
            'shortage_plans' => 0,
            'total_materials' => 0,
        ];

        foreach ($plans as $plan) {
            $planId = $plan['IdKeHoachSanXuatXuong'];
            $workshopId = $plan['IdXuong'];
            $componentId = $plan['IdCongDoan'] ?? null;
            $planMaterials = [];
            $hasShortage = false;

            if ($componentId && isset($materialsByComponent[$componentId])) {
                foreach ($materialsByComponent[$componentId] as $material) {
                    $materialId = $material['id'];
                    $ratio = is_numeric($material['quantity_per_unit'] ?? null) ? (float) $material['quantity_per_unit'] : 1.0;
                    $required = (int) round(((int) ($plan['SoLuong'] ?? 0)) * $ratio);
                    $stock = (int) ($inventory[$materialId]['SoLuong'] ?? 0);
                    $deficit = max(0, $required - $stock);

                    $planMaterials[] = [
                        'id' => $materialId,
                        'label' => $material['label'] ?? $materialId,
                        'unit' => $material['unit'] ?? null,
                        'required' => $required,
                        'stock' => $stock,
                        'deficit' => $deficit,
                    ];

                    if ($deficit > 0) {
                        $hasShortage = true;
                    }
                }
            }

            $metrics['total_plans']++;
            $metrics['total_materials'] += count($planMaterials);
            if ($hasShortage) {
                $metrics['shortage_plans']++;
            }

            $status = $plan['TinhTrangVatTu'] ?? null;
            if (!$status) {
                $status = $hasShortage ? 'Thiếu vật tư' : (empty($planMaterials) ? 'Không yêu cầu vật tư' : 'Đủ vật tư');
            }

            if (!isset($grouped[$workshopId])) {
                $grouped[$workshopId] = [
                    'info' => [
                        'IdXuong' => $workshopId,
                        'TenXuong' => $plan['TenXuong'] ?? 'Không xác định',
                    ],
                    'plans' => [],
                ];
            }

            $grouped[$workshopId]['plans'][] = [
                'data' => $plan,
                'materials' => $planMaterials,
                'material_status' => $status,
                'has_shortage' => $hasShortage,
            ];
        }

        $this->render('workshop/dashboard', [
            'title' => 'Dashboard cấp phát vật tư xưởng',
            'workshops' => $workshops,
            'selectedWorkshop' => $selectedWorkshop,
            'groupedPlans' => $grouped,
            'metrics' => $metrics,
        ]);
    }

    public function confirmPlan(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=workshop&action=dashboard');
        }

        $planId = $_POST['IdKeHoachSanXuatXuong'] ?? null;
        $redirectWorkshop = $_POST['redirect_workshop'] ?? null;
        $quantity = isset($_POST['SoLuong']) ? (int) $_POST['SoLuong'] : null;
        $materialStatus = trim($_POST['TinhTrangVatTu'] ?? '');
        $progressStatus = trim($_POST['TrangThai'] ?? '');

        if (!$planId) {
            $this->setFlash('danger', 'Không xác định được kế hoạch xưởng cần cập nhật.');
            $this->redirect($this->buildDashboardRedirect($redirectWorkshop));
        }

        $payload = [];

        if ($quantity !== null) {
            if ($quantity <= 0) {
                $this->setFlash('danger', 'Số lượng cần lớn hơn 0.');
                $this->redirect($this->buildDashboardRedirect($redirectWorkshop));
            }
            $payload['SoLuong'] = $quantity;
        }

        if ($materialStatus !== '') {
            $payload['TinhTrangVatTu'] = $materialStatus;
        }

        if ($progressStatus !== '') {
            $payload['TrangThai'] = $progressStatus;
        }

        if (empty($payload)) {
            $this->setFlash('info', 'Không có dữ liệu nào được cập nhật.');
            $this->redirect($this->buildDashboardRedirect($redirectWorkshop));
        }

        try {
            $this->workshopPlanModel->update($planId, $payload);
            $this->setFlash('success', 'Đã cập nhật kế hoạch xưởng.');
        } catch (Throwable $exception) {
            $this->setFlash('danger', 'Không thể cập nhật kế hoạch: ' . $exception->getMessage());
        }

        $this->redirect($this->buildDashboardRedirect($redirectWorkshop));
    }

    private function buildDashboardRedirect(?string $workshopId): string
    {
        $params = ['controller' => 'workshop', 'action' => 'dashboard'];
        if ($workshopId) {
            $params['workshop'] = $workshopId;
        }

        return '?' . http_build_query($params);
    }

    private function extractWorkshopData(array $input): array
    {
        return [
            'IdXuong' => trim($input['IdXuong'] ?? ''),
            'TenXuong' => trim($input['TenXuong'] ?? ''),
            'DiaDiem' => trim($input['DiaDiem'] ?? ''),
            'IdTruongXuong' => trim($input['IdTruongXuong'] ?? ''),
            'SoLuongCongNhan' => (int) ($input['SoLuongCongNhan'] ?? 0),
            'CongSuatToiDa' => (float) ($input['CongSuatToiDa'] ?? 0),
            'CongSuatDangSuDung' => (float) ($input['CongSuatDangSuDung'] ?? 0),
            'NgayThanhLap' => $input['NgayThanhLap'] ?? null,
            'TrangThai' => $input['TrangThai'] ?? 'Đang hoạt động',
            'MoTa' => trim($input['MoTa'] ?? ''),
        ];
    }
}
