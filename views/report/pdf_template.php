<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo thống kê</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 30px;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        h3 {
            text-align: center;
            font-size: 16px;
            margin: 30px 0 15px 0;
            color: #34495e;
            background-color: #ecf0f1;
            padding: 8px;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #bdc3c7;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tbody tr:hover {
            background-color: #e8f4fd;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <h1>Báo cáo thống kê</h1>

    <h3>Hóa đơn</h3>
    <table>
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
                                <td><?= htmlspecialchars($bill['TrangThai']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="no-data">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
    </table>

       <h3>Các Đơn Hàng</h3>
    <table>
         <thead>
                    <tr>
                        <!-- <th>ID Chi Tiết</th> -->
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
                                    <td><?php echo number_format($detail['ThanhTien']);  ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
    </table>

    <h3>Nhân viên</h3>
    <table>
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
                            <td colspan="4" class="no-data">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

    <h3>Đơn hàng</h3>
                <table>
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
                                <td><?php echo htmlspecialchars(number_format($order['TongTien'], 2)); ?></td>
                                <td><?php echo htmlspecialchars($order['TrangThai']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="no-data">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

    <h3>Nguyên liệu</h3>
   <table>
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
                            <td colspan="4" class="no-data">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

    <h3>Sản phẩm</h3>
    <table>
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
                                <td><?php echo htmlspecialchars(number_format($product['GiaBan'], 2)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="no-data">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

</body>
</html>
