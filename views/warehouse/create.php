<?php
$workshops = $workshops ?? [];
$employees = $employees ?? [];
$types = $types ?? [];
$workshops = $workshops ?? [];
$managers = $managers ?? [];
$statuses = $statuses ?? ['Đang sử dụng', 'Tạm dừng', 'Bảo trì'];
$workshopEmployees = $workshopEmployees ?? [];
$workshopManagerMap = $workshopManagerMap ?? [];
$workshopEmployeesJson = htmlspecialchars(json_encode($workshopEmployees, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}', ENT_QUOTES, 'UTF-8');
$workshopManagerJson = htmlspecialchars(json_encode($workshopManagerMap, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}', ENT_QUOTES, 'UTF-8');
?>

<style>
    .warehouse-form-section {
        border: 1px solid rgba(15, 23, 42, 0.06);
        border-radius: 1rem;
        padding: 1.25rem;
        background: #f8fafc;
        height: 100%;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Thêm kho SV5TOT mới</h3>
        <p class="text-muted mb-0">Khai báo kho SV5TOT và thông tin người phụ trách.</p>
    </div>
    <a href="?controller=warehouse&action=index" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="?controller=warehouse&action=store" method="post" class="row g-4">
            <input type="hidden" name="IdKho" value="">

            <div class="col-lg-8">
                <div class="warehouse-form-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="text-uppercase small text-muted">Thông tin kho</div>
                            <h5 class="fw-semibold mb-0">Định danh & loại kho</h5>
                        </div>
                            <span class="badge bg-primary-subtle text-primary border">Mã tự sinh</span>
                        </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên kho <span class="text-danger">*</span></label>
                            <input type="text" name="TenKho" class="form-control" placeholder="Ví dụ: Kho NL01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Loại kho <span class="text-danger">*</span></label>
                            <select name="TenLoaiKho" class="form-select" required>
                                <option value="" disabled selected>Chọn loại kho</option>
                                <?php foreach ($types as $typeLabel): ?>
                                    <option value="<?= htmlspecialchars($typeLabel) ?>"><?= htmlspecialchars($typeLabel) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="DiaChi" class="form-control" placeholder="Địa chỉ kho">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Trạng thái</label>
                            <select name="TrangThai" class="form-select">
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?= htmlspecialchars($status) ?>"><?= htmlspecialchars($status) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Xưởng phụ trách <span class="text-danger">*</span></label>
                            <select name="IdXuong" class="form-select" required>
                                <option value="" disabled selected>Chọn xưởng phụ trách</option>
                                <?php foreach ($workshops as $workshop): ?>
                                    <option value="<?= htmlspecialchars($workshop['IdXuong']) ?>">
                                        <?= htmlspecialchars($workshop['TenXuong']) ?> (<?= htmlspecialchars($workshop['IdXuong']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="warehouse-form-section">
                    <div class="text-uppercase small text-muted mb-2">Người phụ trách</div>
                    <h6 class="fw-semibold mb-3">Thông tin quản lý kho</h6>
                    <div class="mb-3">
                        <label class="form-label">Nhân viên quản kho <span class="text-danger">*</span></label>
                        <div class="form-control-plaintext" data-role="manager-display">
                            <span class="text-muted">Chọn xưởng để hiển thị xưởng trưởng.</span>
                        </div>
                        <input type="hidden" name="warehouse_managers[]" value="" data-role="manager-input">
                        <div class="form-text text-muted">Hệ thống tự động gán xưởng trưởng của xưởng phụ trách làm quản kho mặc định.</div>
                        <div class="form-text text-danger d-none" data-role="no-employee-alert">Xưởng chưa có xưởng trưởng. Vui lòng cập nhật thông tin trước khi tạo kho.</div>
                    </div>
                    <div class="text-muted small">Mã kho và mã lô sẽ được tạo tự động khi lưu để đồng bộ với hệ thống phiếu.</div>
                </div>
            </div>

            <div class="col-12">
                <div class="warehouse-form-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <div class="text-uppercase small text-muted">Sức chứa & số liệu</div>
                            <h6 class="fw-semibold mb-0">Quy mô kho</h6>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tổng số lô</label>
                            <input type="number" name="TongSLLo" class="form-control" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tổng sức chứa</label>
                            <input type="number" name="TongSL" class="form-control" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tổng giá trị hàng tồn (đ)</label>
                            <input type="number" name="ThanhTien" class="form-control" min="0" value="0">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 text-end">
                <button class="btn btn-primary px-4" type="submit" data-role="submit-btn">Lưu kho SV5TOT</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const workshopEmployees = JSON.parse('<?= $workshopEmployeesJson ?>');
        const workshopManagers = JSON.parse('<?= $workshopManagerJson ?>');
        const workshopSelect = document.querySelector('select[name="IdXuong"]');
        const managerDisplay = document.querySelector('[data-role="manager-display"]');
        const managerInput = document.querySelector('[data-role="manager-input"]');
        const submitBtn = document.querySelector('[data-role="submit-btn"]');
        const alertEl = document.querySelector('[data-role="no-employee-alert"]');

        const renderManagers = () => {
            if (!workshopSelect || !managerDisplay || !managerInput) {
                return;
            }

            const workshopId = workshopSelect.value || '';
            const employees = workshopEmployees[workshopId] || [];
            const defaultManager = workshopManagers[workshopId] || {};
            const defaultManagerId = defaultManager.id || '';

            const manager = employees.find((employee) => employee.IdNhanVien === defaultManagerId)
                || (defaultManagerId ? { HoTen: defaultManager.name, ChucVu: defaultManager.title } : null);

            if (defaultManagerId) {
                let display = manager && manager.HoTen ? manager.HoTen : defaultManagerId;
                if (manager && manager.ChucVu) {
                    display += ' · ' + manager.ChucVu;
                }
                managerDisplay.textContent = display;
                managerInput.value = defaultManagerId;
            } else {
                managerDisplay.innerHTML = '<span class="text-muted">Xưởng chưa có xưởng trưởng.</span>';
                managerInput.value = '';
            }

            const hasManager = Boolean(defaultManagerId);
            if (submitBtn) {
                submitBtn.disabled = !hasManager;
            }
            if (alertEl) {
                alertEl.classList.toggle('d-none', hasManager);
            }
        };

        if (workshopSelect) {
            workshopSelect.addEventListener('change', () => {
                renderManagers();
            });
        }

        renderManagers();
    });
</script>
