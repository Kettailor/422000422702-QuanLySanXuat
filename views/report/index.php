<?php
// views/report/index.php - Redesigned version

include_once 'views/header.php';

// Calculate KPIs
$totalOrders = count($orders ?? []);
$totalRevenue = array_sum(array_column($orders ?? [], 'TongTien'));
$totalProducts = count($products ?? []);
$totalEmployees = count($employees ?? []);
$totalMaterials = count($materials ?? []);
$totalBills = count($bills ?? []);
?>

<style>
    :root {
        --primary-blue: #2E7EF1;
        --primary-green: #00B074;
        --primary-cyan: #00B9D8;
        --primary-yellow: #F5A524;
        --primary-red: #EF4444;
        --primary-gray: #6B7280;
        --bg-white: #FFFFFF;
        --text-dark: #1E293B;
        --text-gray: #64748B;
        --border-light: #E2E8F0;
    }
    
    .report-container {
        padding: 2rem;
        background: var(--bg-white);
        min-height: 100vh;
    }
    
    .report-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .report-header h1 {
        font-size: 2rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    
    /* KPI Cards Section */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    
    .kpi-card {
        background: var(--bg-white);
        border-radius: 16px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
    }
    
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--card-color);
    }
    
    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    }
    
    .kpi-card.blue {
        --card-color: #2E7EF1;
    }
    
    .kpi-card.green {
        --card-color: #00B074;
    }
    
    .kpi-card.cyan {
        --card-color: #00B9D8;
    }
    
    .kpi-card.yellow {
        --card-color: #F5A524;
    }
    
    .kpi-card.red {
        --card-color: #EF4444;
    }
    
    .kpi-card.gray {
        --card-color: #6B7280;
    }
    
    .kpi-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        background: var(--card-color);
    }
    
    .kpi-icon i {
        font-size: 1.5rem;
        color: white;
    }
    
    .kpi-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-gray);
        margin-bottom: 0.5rem;
    }
    
    .kpi-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
    }
    
    /* Export Button */
    .export-section {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 2rem;
    }
    
    .btn-export {
        background: #2E7EF1;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .btn-export:hover {
        background: #1E6FE1;
        transform: translateY(-2px);
        color: white;
    }
    
    /* Tabs Section */
    .tabs-container {
        background: var(--bg-white);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
    }
    
    .custom-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid var(--border-light);
        overflow-x: auto;
    }
    
    .custom-tab {
        background: transparent;
        border: none;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-gray);
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease;
        white-space: nowrap;
        border-radius: 8px 8px 0 0;
    }
    
    .custom-tab::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: #2E7EF1;
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .custom-tab.active {
        color: #2E7EF1;
    }
    
    .custom-tab.active::after {
        transform: scaleX(1);
    }
    
    .custom-tab:hover:not(.active) {
        color: var(--text-dark);
    }
    
    .tab-content-item {
        display: none;
    }
    
    .tab-content-item.active {
        display: block;
    }
    
    /* Table Styles */
    .table-section {
        margin-bottom: 2rem;
    }
    
    .table-section h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }
    
    .table-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid var(--border-light);
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table thead {
        background: #F8FAFC;
    }
    
    .data-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--text-dark);
        border-bottom: 2px solid var(--border-light);
    }
    
    .data-table tbody tr {
        transition: background 0.2s ease;
        border-bottom: 1px solid var(--border-light);
    }
    
    .data-table tbody tr:hover {
        background: #F8FAFC;
    }
    
    .data-table tbody tr:last-child {
        border-bottom: none;
    }
    
    .data-table td {
        padding: 1rem 1.5rem;
        color: var(--text-dark);
        font-size: 0.95rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-gray);
    }
    
    .empty-state i {
        font-size: 3rem;
        color: var(--border-light);
        margin-bottom: 1rem;
    }
</style>

<div class="report-container">
    <div class="report-header">
        <h1>Báo cáo thống kê</h1>
    </div>

    <!-- KPI Cards -->
    <div class="kpi-grid">
        <div class="kpi-card blue">
            <div class="kpi-icon">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="kpi-label">Đơn Hàng</div>
            <div class="kpi-value"><?php echo $totalOrders; ?></div>
        </div>
        <div class="kpi-card green">
            <div class="kpi-icon">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="kpi-label">Doanh Thu</div>
            <div class="kpi-value"><?php echo number_format($totalRevenue, 0); ?> ₫</div>
        </div>
        <div class="kpi-card cyan">
            <div class="kpi-icon">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="kpi-label">Sản Phẩm</div>
            <div class="kpi-value"><?php echo $totalProducts; ?></div>
        </div>
        <div class="kpi-card yellow">
            <div class="kpi-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="kpi-label">Nhân Viên</div>
            <div class="kpi-value"><?php echo $totalEmployees; ?></div>
        </div>
        <div class="kpi-card red">
            <div class="kpi-icon">
                <i class="bi bi-tree"></i>
            </div>
            <div class="kpi-label">Nguyên Liệu</div>
            <div class="kpi-value"><?php echo $totalMaterials; ?></div>
        </div>
        <div class="kpi-card gray">
            <div class="kpi-icon">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="kpi-label">Hóa Đơn</div>
            <div class="kpi-value"><?php echo $totalBills; ?></div>
        </div>
    </div>

    <div class="export-section">
        <a href="?controller=report&action=export_pdf" class="btn-export">
            <i class="bi bi-file-earmark-pdf"></i>
            Xuất PDF
        </a>
    </div>

    <!-- Tabs -->
    <div class="tabs-container">
        <div class="custom-tabs">
            <button class="custom-tab active" data-tab="products">Sản Phẩm</button>
            <button class="custom-tab" data-tab="bills">Hóa Đơn</button>
            <button class="custom-tab" data-tab="orders">Đơn Hàng</button>
            <button class="custom-tab" data-tab="employees">Nhân Viên</button>
            <button class="custom-tab" data-tab="materials">Nguyên Liệu</button>
        </div>

        <!-- Sản Phẩm Tab -->
        <div class="tab-content-item active" id="tab-products">
            <div class="table-section">
                <h3>Sản phẩm</h3>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID Sản Phẩm</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Đơn Vị</th>
                                <th>Giá Bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($products)): ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['IdSanPham']); ?></td>
                                        <td><?php echo htmlspecialchars($product['TenSanPham']); ?></td>
                                        <td><?php echo htmlspecialchars($product['DonVi']); ?></td>
                                        <td><?php echo number_format($product['GiaBan'], 0); ?> ₫</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <p>Không có dữ liệu</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Hóa đơn Tab -->
        <div class="tab-content-item" id="tab-bills">
            <div class="table-section">
                <h3>Hóa đơn</h3>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID Hóa Đơn</th>
                                <th>ID Đơn Hàng</th>
                                <th>Ngày Lập</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($bills)): ?>
                                <?php foreach ($bills as $bill): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($bill['IdHoaDon']); ?></td>
                                        <td><?php echo htmlspecialchars($bill['IdDonHang']); ?></td>
                                        <td><?php echo htmlspecialchars($bill['NgayLap']); ?></td>
                                        <td><?php echo htmlspecialchars($bill['TrangThai']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <p>Không có dữ liệu</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Đơn Hàng Tab -->
        <div class="tab-content-item" id="tab-orders">
            <div class="table-section">
                <h3>Đơn hàng</h3>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID Đơn Hàng</th>
                                <th>ID Khách Hàng</th>
                                <th>Ngày Lập</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order['IdDonHang']); ?></td>
                                        <td><?php echo htmlspecialchars($order['IdKhachHang']); ?></td>
                                        <td><?php echo htmlspecialchars($order['NgayLap']); ?></td>
                                        <td><?php echo number_format($order['TongTien'], 0); ?> ₫</td>
                                        <td><?php echo htmlspecialchars($order['TrangThai']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <p>Không có dữ liệu</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-section">
                <h3>Chi tiết đơn hàng</h3>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID Đơn Hàng</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Số Lượng</th>
                                <th>Tổng Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orderDetails)): ?>
                                <?php foreach ($orderDetails as $detail): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($detail['IdDonHang']); ?></td>
                                        <td><?php echo htmlspecialchars($detail['TenSanPham']); ?></td>
                                        <td><?php echo htmlspecialchars($detail['SoLuong']); ?></td>
                                        <td><?php echo number_format($detail['ThanhTien'], 0); ?> ₫</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <p>Không có dữ liệu</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Nhân Viên Tab -->
        <div class="tab-content-item" id="tab-employees">
            <div class="table-section">
                <h3>Nhân viên</h3>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID Nhân Viên</th>
                                <th>Họ Tên</th>
                                <th>Chức Vụ</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($employees)): ?>
                                <?php foreach ($employees as $employee): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($employee['IdNhanVien']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['HoTen']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['ChucVu']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['TrangThai']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <p>Không có dữ liệu</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Nguyên Liệu Tab -->
        <div class="tab-content-item" id="tab-materials">
            <div class="table-section">
                <h3>Nguyên liệu</h3>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID Nguyên Liệu</th>
                                <th>Tên Nguyên Liệu</th>
                                <th>Số Lượng</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($materials)): ?>
                                <?php foreach ($materials as $material): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($material['IdNguyenLieu']); ?></td>
                                        <td><?php echo htmlspecialchars($material['TenNL']); ?></td>
                                        <td><?php echo htmlspecialchars($material['SoLuong']); ?></td>
                                        <td><?php echo htmlspecialchars($material['TrangThai']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <p>Không có dữ liệu</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab switching functionality
    document.querySelectorAll('.custom-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs and contents
            document.querySelectorAll('.custom-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content-item').forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Show corresponding content
            const tabId = this.getAttribute('data-tab');
            document.getElementById('tab-' + tabId).classList.add('active');
        });
    });
</script>
