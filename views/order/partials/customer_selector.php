<?php
$selectedCustomerId = $selectedCustomerId ?? null;
$customerFormData = $customerFormData ?? ['name' => '', 'phone' => '', 'email' => '', 'address' => '', 'type' => ''];
$customerMode = $customerMode ?? 'existing';
?>
<div class="col-lg-6" data-customer-selector>
    <label class="form-label">Khách hàng</label>
    <div class="btn-group w-100 mb-2" role="group" aria-label="Lựa chọn khách hàng">
        <button type="button"
                class="btn btn-outline-primary customer-mode-btn <?= $customerMode === 'existing' ? 'active' : '' ?>"
                data-mode="existing">
            Chọn từ danh sách
        </button>
        <button type="button"
                class="btn btn-outline-primary customer-mode-btn <?= $customerMode === 'new' ? 'active' : '' ?>"
                data-mode="new">
            Thêm khách hàng mới
        </button>
    </div>
    <input type="hidden" name="customer_mode" value="<?= htmlspecialchars($customerMode) ?>" data-customer-field="mode">
    <div class="existing-customer-fields" data-customer-field="existing">
        <select name="customer_existing_id" class="form-select" <?= $customerMode === 'existing' ? 'required' : '' ?>>
            <option value="">-- Chọn khách hàng/đối tác --</option>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= htmlspecialchars($customer['IdKhachHang']) ?>"
                    <?= $customer['IdKhachHang'] === $selectedCustomerId ? 'selected' : '' ?>>
                    <?= htmlspecialchars($customer['HoTen']) ?><?= !empty($customer['TenCongTy']) ? ' - ' . htmlspecialchars($customer['TenCongTy']) : '' ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="border rounded p-3 bg-light new-customer-fields <?= $customerMode === 'new' ? '' : 'd-none' ?>" data-customer-field="new">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Tên khách hàng</label>
                <input type="text" class="form-control" name="customer_name"
                       value="<?= htmlspecialchars($customerFormData['name'] ?? '') ?>"
                       <?= $customerMode === 'new' ? 'required' : 'disabled' ?>
                       placeholder="Nhập tên khách hàng hoặc doanh nghiệp">
            </div>
            <div class="col-12">
                <label class="form-label">Tên công ty dự án</label>
                <input type="text" class="form-control" name="customer_company"
                       value="<?= htmlspecialchars($customerFormData['company'] ?? '') ?>"
                       <?= $customerMode === 'new' ? '' : 'disabled' ?>
                       placeholder="Ví dụ: Công ty TNHH ABC">
            </div>
            <div class="col-md-6">
                <label class="form-label">Số điện thoại</label>
                <input type="tel" class="form-control" name="customer_phone"
                       value="<?= htmlspecialchars($customerFormData['phone'] ?? '') ?>"
                       <?= $customerMode === 'new' ? '' : 'disabled' ?>
                       placeholder="Ví dụ: 0901234567">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="customer_email"
                       value="<?= htmlspecialchars($customerFormData['email'] ?? '') ?>"
                       <?= $customerMode === 'new' ? '' : 'disabled' ?>
                       placeholder="Ví dụ: customer@example.com">
            </div>
            <div class="col-md-6">
                <label class="form-label">Nhóm khách hàng</label>
                <input type="text" class="form-control" name="customer_type"
                       value="<?= htmlspecialchars($customerFormData['type'] ?? '') ?>"
                       <?= $customerMode === 'new' ? '' : 'disabled' ?>
                       placeholder="Đại lý, bán lẻ, doanh nghiệp...">
            </div>
            <div class="col-12">
                <label class="form-label">Địa chỉ</label>
                <textarea class="form-control" rows="2" name="customer_address"
                          <?= $customerMode === 'new' ? '' : 'disabled' ?>
                          placeholder="Địa chỉ giao dịch hoặc giao hàng"><?= htmlspecialchars($customerFormData['address'] ?? '') ?></textarea>
            </div>
        </div>
    </div>
</div>
<?php if (!defined('CUSTOMER_SELECTOR_INITIALIZED')): ?>
    <?php define('CUSTOMER_SELECTOR_INITIALIZED', true); ?>
    <script>
        (function () {
            document.querySelectorAll('[data-customer-selector]').forEach(function (wrapper) {
                const modeInput = wrapper.querySelector('[data-customer-field="mode"]');
                const existingSection = wrapper.querySelector('[data-customer-field="existing"]');
                const newSection = wrapper.querySelector('[data-customer-field="new"]');
                const existingSelect = existingSection.querySelector('select');
                const newInputs = newSection.querySelectorAll('input, textarea');

                function setMode(mode) {
                    modeInput.value = mode;
                    wrapper.querySelectorAll('.customer-mode-btn').forEach(function (btn) {
                        btn.classList.toggle('active', btn.getAttribute('data-mode') === mode);
                    });

                    if (mode === 'existing') {
                        existingSection.classList.remove('d-none');
                        existingSelect.removeAttribute('disabled');
                        existingSelect.setAttribute('required', 'required');
                        newSection.classList.add('d-none');
                        newInputs.forEach(function (input) {
                            input.setAttribute('disabled', 'disabled');
                            if (input.hasAttribute('required')) {
                                input.removeAttribute('required');
                            }
                        });
                    } else {
                        existingSection.classList.add('d-none');
                        existingSelect.setAttribute('disabled', 'disabled');
                        existingSelect.removeAttribute('required');
                        newSection.classList.remove('d-none');
                        newInputs.forEach(function (input) {
                            input.removeAttribute('disabled');
                            if (input.name === 'customer_name') {
                                input.setAttribute('required', 'required');
                            }
                        });
                    }
                }

                wrapper.querySelectorAll('.customer-mode-btn').forEach(function (button) {
                    button.addEventListener('click', function () {
                        setMode(button.getAttribute('data-mode'));
                    });
                });

                setMode(modeInput.value || 'existing');
            });
        })();
    </script>
<?php endif; ?>
