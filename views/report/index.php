<?php
// views/report/index.php

include_once 'views/header.php';

// Calculate KPIs
$totalOrders = count($orders ?? []);
$totalRevenue = array_sum(array_column($orders ?? [], 'TongTien'));
$totalProducts = count($products ?? []);
$totalEmployees = count($employees ?? []);
$totalMaterials = count($materials ?? []);
$totalBills = count($bills ?? []);
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Báo cáo thống kê</h1>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <i class="bi bi-receipt fs-1"></i>
                    <h5 class="card-title">Đơn Hàng</h5>
                    <p class="card-text fs-4"><?php echo $totalOrders; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <i class="bi bi-cash-stack fs-1"></i>
                    <h5 class="card-title">Doanh Thu</h5>
                    <p class="card-text fs-4"><?php echo number_format($totalRevenue, 0); ?> VND</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <i class="bi bi-box-seam fs-1"></i>
                    <h5 class="card-title">Sản Phẩm</h5>
                    <p class="card-text fs-4"><?php echo $totalProducts; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <i class="bi bi-people fs-1"></i>
                    <h5 class="card-title">Nhân Viên</h5>
                    <p class="card-text fs-4"><?php echo $totalEmployees; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center bg-danger text-white">
                <div class="card-body">
                    <i class="bi bi-tree fs-1"></i>
                    <h5 class="card-title">Nguyên Liệu</h5>
                    <p class="card-text fs-4"><?php echo $totalMaterials; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center bg-secondary text-white">
                <div class="card-body">
                    <i class="bi bi-file-earmark-text fs-1"></i>
                    <h5 class="card-title">Hóa Đơn</h5>
                    <p class="card-text fs-4"><?php echo $totalBills; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="?controller=report&action=export_pdf" class="btn btn-primary">Xuất PDF</a>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="reportTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab" aria-controls="products" aria-selected="true">Sản Phẩm</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="bills-tab" data-bs-toggle="tab" data-bs-target="#bills" type="button" role="tab" aria-controls="bills" aria-selected="false">Hóa Đơn</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab" aria-controls="orders" aria-selected="false">Đơn Hàng</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="employees-tab" data-bs-toggle="tab" data-bs-target="#employees" type="button" role="tab" aria-controls="employees" aria-selected="false">Nhân Viên</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="materials-tab" data-bs-toggle="tab" data-bs-target="#materials" type="button" role="tab" aria-controls="materials" aria-selected="false">Nguyên Liệu</button>
        </li>
    </ul>
    <div class="tab-content" id="reportTabsContent">
        <!-- Sản Phẩm Tab -->
        <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
            <!-- Sản phẩm -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Sản phẩm</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
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
                                    <tr class="product-row" data-id="<?php echo htmlspecialchars($product['IdSanPham']); ?>">
                                        <td><?php echo htmlspecialchars($product['IdSanPham']); ?></td>
                                        <td><?php echo htmlspecialchars($product['TenSanPham']); ?></td>
                                        <td><?php echo htmlspecialchars($product['DonVi']); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($product['GiaBan'], 2)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Hóa đơn Tab -->
        <div class="tab-pane fade" id="bills" role="tabpanel" aria-labelledby="bills-tab">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Hóa đơn</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
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
                                    <tr class="bill-row" data-id="<?php echo htmlspecialchars($bill['IdHoaDon']); ?>">
                                        <td><?php echo htmlspecialchars($bill['IdHoaDon']); ?></td>
                                        <td><?php echo htmlspecialchars($bill['IdDonHang']); ?></td>
                                        <td><?php echo htmlspecialchars($bill['NgayLap']); ?></td>
                                        <td><?php echo htmlspecialchars($bill['TrangThai']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Đơn Hàng Tab -->
        <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
            <!-- Đơn hàng -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Đơn hàng</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
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
                                    <tr class="order-row" data-id="<?php echo htmlspecialchars($order['IdDonHang']); ?>">
                                        <td><?php echo htmlspecialchars($order['IdDonHang']); ?></td>
                                        <td><?php echo htmlspecialchars($order['IdKhachHang']); ?></td>
                                        <td><?php echo htmlspecialchars($order['NgayLap']); ?></td>
                                        <td><?php echo htmlspecialchars(number_format($order['TongTien'], 2)); ?></td>
                                        <td><?php echo htmlspecialchars($order['TrangThai']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Chi tiết đơn hàng -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Chi tiết đơn hàng</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
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
                                        <td><?php echo number_format($detail['ThanhTien']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Nhân Viên Tab -->
        <div class="tab-pane fade" id="employees" role="tabpanel" aria-labelledby="employees-tab">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Nhân viên</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
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
                                    <tr class="employee-row" data-id="<?php echo htmlspecialchars($employee['IdNhanVien']); ?>">
                                        <td><?php echo htmlspecialchars($employee['IdNhanVien']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['HoTen']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['ChucVu']); ?></td>
                                        <td><?php echo htmlspecialchars($employee['TrangThai']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Nguyên Liệu Tab -->
        <div class="tab-pane fade" id="materials" role="tabpanel" aria-labelledby="materials-tab">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Nguyên liệu</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
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
                                    <tr class="material-row" data-id="<?php echo htmlspecialchars($material['IdNguyenLieu']); ?>">
                                        <td><?php echo htmlspecialchars($material['IdNguyenLieu']); ?></td>
                                        <td><?php echo htmlspecialchars($material['TenNL']); ?></td>
                                        <td><?php echo htmlspecialchars($material['SoLuong']); ?></td>
                                        <td><?php echo htmlspecialchars($material['TrangThai']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>





</div>

