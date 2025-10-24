<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Phiếu xuất nhập kho</h3>
        <p class="text-muted mb-0">Theo dõi các phiếu nhập xuất kho gần đây.</p>
    </div>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã phiếu</th>
                <th>Kho</th>
                <th>Loại phiếu</th>
                <th>Ngày lập</th>
                <th>Ngày xác nhận</th>
                <th>Tổng tiền</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($documents as $document): ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($document['IdPhieu']) ?></td>
                    <td><?= htmlspecialchars($document['TenKho']) ?></td>
                    <td><?= htmlspecialchars($document['LoaiPhieu']) ?></td>
                    <td><?= $document['NgayLP'] ? date('d/m/Y', strtotime($document['NgayLP'])) : '-' ?></td>
                    <td><?= $document['NgayXN'] ? date('d/m/Y', strtotime($document['NgayXN'])) : '-' ?></td>
                    <td class="fw-semibold text-primary"><?= number_format($document['TongTien'], 0, ',', '.') ?> đ</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
