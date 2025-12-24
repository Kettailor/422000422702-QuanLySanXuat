<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo thống kê</title>
    <style>
        @page {
            size: A4;
            margin: 2cm 1.5cm;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.6;
            color: #2c3e50;
            margin: 0;
            padding: 0;
        }

        /* Cover Page */
        .cover-page {
            text-align: center;
            padding: 80px 40px;
            page-break-after: always;
        }

        .cover-title {
            font-size: 36pt;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .cover-subtitle {
            font-size: 18pt;
            color: #7f8c8d;
            margin-bottom: 60px;
        }

        .cover-date {
            font-size: 14pt;
            color: #95a5a6;
            margin-top: 80px;
        }

        .cover-logo {
            width: 150px;
            height: 150px;
            margin: 40px auto;
            border: 3px solid #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48pt;
            color: #3498db;
            font-weight: bold;
        }

        /* Executive Summary / KPI Dashboard */
        .executive-summary {
            page-break-after: always;
            padding: 20px 0;
        }

        .section-title {
            font-size: 20pt;
            font-weight: bold;
            color: #2c3e50;
            margin: 30px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 3px solid #3498db;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .kpi-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-left: 4px solid #3498db;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .kpi-card.blue { border-left-color: #2E7EF1; }
        .kpi-card.green { border-left-color: #00B074; }
        .kpi-card.cyan { border-left-color: #00B9D8; }
        .kpi-card.yellow { border-left-color: #F5A524; }
        .kpi-card.red { border-left-color: #EF4444; }
        .kpi-card.gray { border-left-color: #6B7280; }

        .kpi-label {
            font-size: 9pt;
            color: #7f8c8d;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .kpi-value {
            font-size: 24pt;
            font-weight: bold;
            color: #2c3e50;
            line-height: 1;
        }

        .kpi-subtext {
            font-size: 8pt;
            color: #95a5a6;
            margin-top: 5px;
        }

        /* Data Tables */
        .data-section {
            margin-bottom: 40px;
        }

        .section-header {
            font-size: 16pt;
            font-weight: bold;
            color: #34495e;
            margin: 25px 0 15px 0;
            padding: 10px 15px;
            background: linear-gradient(to right, #ecf0f1, transparent);
            border-left: 4px solid #3498db;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        thead {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }

        th {
            padding: 12px 10px;
            text-align: left;
            font-size: 9pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-right: 1px solid rgba(255,255,255,0.2);
        }

        th:last-child {
            border-right: none;
        }

        td {
            padding: 10px;
            font-size: 9pt;
            border-bottom: 1px solid #ecf0f1;
            border-right: 1px solid #ecf0f1;
        }

        td:last-child {
            border-right: none;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:last-child td {
            border-bottom: 2px solid #3498db;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #95a5a6;
            font-style: italic;
            font-size: 10pt;
        }

        /* Compact tables for pages with multiple tables */
        .compact-table {
            page-break-inside: avoid;
        }

        .compact-table table {
            margin-bottom: 20px;
            font-size: 8.5pt;
        }

        .compact-table th,
        .compact-table td {
            padding: 8px;
        }

        /* Footer */
        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 8pt;
            color: #95a5a6;
            border-top: 1px solid #ecf0f1;
            padding-top: 8px;
        }

        /* Text formatting */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        /* Highlight important numbers */
        .highlight-number {
            color: #3498db;
            font-weight: bold;
        }

        /* Page breaks */
        .page-break {
            page-break-after: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>

    <!-- COVER PAGE -->
    <div class="cover-page">
        <div class="cover-logo">BC</div>
        <h1 class="cover-title">Báo Cáo Thống Kê</h1>
        <p class="cover-subtitle">Tổng Quan Hoạt Động Kinh Doanh</p>
        <p class="cover-date">
            Ngày lập: <?php echo date('d/m/Y'); ?><br>
            Người lập: Giám Đốc
        </p>
    </div>

    <!-- EXECUTIVE SUMMARY / KPI DASHBOARD -->
    <div class="executive-summary">
        <h2 class="section-title">Tổng Quan Chỉ Số Chính (KPI)</h2>
        
        <div class="kpi-grid">
            <div class="kpi-card blue">
                <div class="kpi-label">Tổng Đơn Hàng</div>
                <div class="kpi-value"><?php echo count($orders ?? []); ?></div>
                <div class="kpi-subtext">Đơn hàng đã xử lý</div>
            </div>
            
            <div class="kpi-card green">
                <div class="kpi-label">Doanh Thu</div>
                <div class="kpi-value"><?php echo number_format(array_sum(array_column($orders ?? [], 'TongTien')), 0); ?> ₫</div>
                <div class="kpi-subtext">Tổng doanh thu</div>
            </div>
            
            <div class="kpi-card cyan">
                <div class="kpi-label">Sản Phẩm</div>
                <div class="kpi-value"><?php echo count($products ?? []); ?></div>
                <div class="kpi-subtext">Sản phẩm trong hệ thống</div>
            </div>
            
            <div class="kpi-card yellow">
                <div class="kpi-label">Nhân Viên</div>
                <div class="kpi-value"><?php echo count($employees ?? []); ?></div>
                <div class="kpi-subtext">Nhân sự hiện tại</div>
            </div>
            
            <div class="kpi-card red">
                <div class="kpi-label">Nguyên Liệu</div>
                <div class="kpi-value"><?php echo count($materials ?? []); ?></div>
                <div class="kpi-subtext">Loại nguyên liệu</div>
            </div>
            
            <div class="kpi-card gray">
                <div class="kpi-label">Hóa Đơn</div>
                <div class="kpi-value"><?php echo count($bills ?? []); ?></div>
                <div class="kpi-subtext">Hóa đơn đã xuất</div>
            </div>
        </div>

        <div style="margin-top: 40px; padding: 20px; background: #ecf0f1; border-radius: 8px;">
            <h3 style="margin-top: 0; color: #2c3e50; font-size: 14pt;">Tóm Tắt Báo Cáo</h3>
            <p style="margin: 10px 0; line-height: 1.8;">
                Báo cáo này tổng hợp toàn bộ dữ liệu hoạt động kinh doanh bao gồm thông tin về đơn hàng, 
                doanh thu, sản phẩm, nhân sự, nguyên liệu và hóa đơn. Tất cả các số liệu được cập nhật 
                đến thời điểm hiện tại và được trình bày một cách chi tiết trong các phần tiếp theo.
            </p>
        </div>
    </div>

    <!-- DETAILED SECTIONS -->

    <!-- 1. ĐỐN HÀNG & CHI TIẾT -->
    <div class="data-section avoid-break">
        <h2 class="section-header">1. Thông Tin Đơn Hàng</h2>
        
        <h3 style="font-size: 12pt; color: #34495e; margin: 15px 0 10px 0;">1.1. Danh Sách Đơn Hàng</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">ID Đơn Hàng</th>
                    <th style="width: 15%;">ID Khách Hàng</th>
                    <th style="width: 20%;">Ngày Lập</th>
                    <th style="width: 25%;">Tổng Tiền</th>
                    <th style="width: 25%;">Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['IdDonHang']); ?></td>
                            <td><?php echo htmlspecialchars($order['IdKhachHang']); ?></td>
                            <td><?php echo htmlspecialchars($order['NgayLap']); ?></td>
                            <td class="text-right highlight-number"><?php echo number_format($order['TongTien'], 0); ?> ₫</td>
                            <td><?php echo htmlspecialchars($order['TrangThai']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">Không có dữ liệu đơn hàng</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3 style="font-size: 12pt; color: #34495e; margin: 25px 0 10px 0;">1.2. Chi Tiết Đơn Hàng</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">ID Đơn Hàng</th>
                    <th style="width: 40%;">Tên Sản Phẩm</th>
                    <th style="width: 15%;" class="text-center">Số Lượng</th>
                    <th style="width: 25%;" class="text-right">Thành Tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orderDetails)): ?>
                    <?php foreach ($orderDetails as $detail): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($detail['IdDonHang']); ?></td>
                            <td><?php echo htmlspecialchars($detail['TenSanPham']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($detail['SoLuong']); ?></td>
                            <td class="text-right highlight-number"><?php echo number_format($detail['ThanhTien'], 0); ?> ₫</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="no-data">Không có chi tiết đơn hàng</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- 2. HÓA ĐƠN -->
    <div class="data-section avoid-break">
        <h2 class="section-header">2. Thông Tin Hóa Đơn</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">ID Hóa Đơn</th>
                    <th style="width: 20%;">ID Đơn Hàng</th>
                    <th style="width: 30%;">Ngày Lập</th>
                    <th style="width: 30%;">Trạng Thái</th>
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
                        <td colspan="4" class="no-data">Không có dữ liệu hóa đơn</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- 3. SẢN PHẨM -->
    <div class="data-section avoid-break">
        <h2 class="section-header">3. Danh Mục Sản Phẩm</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">ID Sản Phẩm</th>
                    <th style="width: 40%;">Tên Sản Phẩm</th>
                    <th style="width: 15%;">Đơn Vị</th>
                    <th style="width: 25%;" class="text-right">Giá Bán</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['IdSanPham']); ?></td>
                            <td><?php echo htmlspecialchars($product['TenSanPham']); ?></td>
                            <td><?php echo htmlspecialchars($product['DonVi']); ?></td>
                            <td class="text-right highlight-number"><?php echo number_format($product['GiaBan'], 0); ?> ₫</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="no-data">Không có dữ liệu sản phẩm</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- 4. NHÂN VIÊN -->
    <div class="data-section avoid-break">
        <h2 class="section-header">4. Danh Sách Nhân Viên</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">ID Nhân Viên</th>
                    <th style="width: 35%;">Họ Tên</th>
                    <th style="width: 25%;">Chức Vụ</th>
                    <th style="width: 20%;">Trạng Thái</th>
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
                        <td colspan="4" class="no-data">Không có dữ liệu nhân viên</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- 5. NGUYÊN LIỆU -->
    <div class="data-section avoid-break">
        <h2 class="section-header">5. Kho Nguyên Liệu</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">ID Nguyên Liệu</th>
                    <th style="width: 40%;">Tên Nguyên Liệu</th>
                    <th style="width: 20%;" class="text-center">Số Lượng</th>
                    <th style="width: 20%;">Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($materials)): ?>
                    <?php foreach ($materials as $material): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($material['IdNguyenLieu']); ?></td>
                            <td><?php echo htmlspecialchars($material['TenNL']); ?></td>
                            <td class="text-center highlight-number"><?php echo htmlspecialchars($material['SoLuong']); ?></td>
                            <td><?php echo htmlspecialchars($material['TrangThai']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="no-data">Không có dữ liệu nguyên liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="page-footer">
        <p>Báo Cáo Thống Kê - Tài liệu mật | © <?php echo date('Y'); ?> | Chỉ dành cho nội bộ</p>
    </div>

</body>
</html>