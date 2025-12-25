<?php
$document = $document ?? [];
$details = $details ?? [];
$warehouse = $warehouse ?? [];
$destination = $destination ?? [];
$classification = $classification ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: "DejaVu Sans", Arial, sans-serif; font-size: 13px; color: #0f172a; margin: 18px; background: #f6f7fb; }
        .page { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px 18px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08); }
        h1 { font-size: 20px; text-align: center; margin: 6px 0 16px 0; text-transform: uppercase; letter-spacing: 0.08em; color: #0f172a; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 6px; }
        .mb-2 { margin-bottom: 12px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #cbd5e1; padding: 7px 9px; }
        .table th { background: #f1f5f9; font-weight: 700; color: #0f172a; }
        .table tbody tr:nth-child(every) { background: #fff; }
        .meta-grid { display: grid; grid-template-columns: 160px 1fr; gap: 7px 14px; margin-bottom: 12px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 11px; border: 1px solid #cbd5e1; }
        .badge-in { background: #ecfdf3; color: #166534; border-color: #bbf7d0; }
        .badge-out { background: #fef2f2; color: #991b1b; border-color: #fecdd3; }
        .badge-info { background: #e0f2fe; color: #075985; border-color: #bae6fd; }
        .signature { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-top: 28px; text-align: center; }
        .signature .title { font-weight: 700; margin-bottom: 50px; text-transform: uppercase; letter-spacing: 0.06em; }
        .signature .line { border-top: 1px dashed #cbd5e1; margin: 32px 18px 10px 18px; }
        .muted { color: #475569; }
        .header-row { width: 100%; margin-bottom: 14px; }
        .section-label { font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; font-size: 12px; color: #0f172a; }
        .bordered { border: 1px solid #e2e8f0; padding: 12px; border-radius: 8px; background: #f8fafc; }
        .subtext { font-size: 11px; color: #64748b; }
    </style>
</head>
<body>
<div class="page">
<table class="header-row">
    <tr>
        <td>
            <div class="section-label">Đơn vị</div>
            <div><strong>SV5TOT</strong></div>
            <div><strong>Kho:</strong> <?= htmlspecialchars($warehouse['TenKho'] ?? ($document['IdKho'] ?? '-')) ?></div>
            <div><strong>Địa chỉ:</strong> <?= htmlspecialchars($warehouse['DiaChi'] ?? 'Chưa cập nhật') ?></div>
        </td>
        <td class="text-end">
            <div class="section-label mb-1">Loại chứng từ</div>
            <div class="badge <?= ($classification['direction'] ?? 'inbound') === 'inbound' ? 'badge-in' : 'badge-out' ?>">
                <?= htmlspecialchars($classification['direction_label'] ?? '') ?>
            </div>
            <?php if (!empty($classification['category'])): ?>
                <div class="badge badge-info" style="margin-top:4px;"><?= htmlspecialchars($classification['category']) ?></div>
            <?php endif; ?>
        </td>
    </tr>
</table>

<h1><?= htmlspecialchars($classification['title'] ?? 'PHIẾU KHO') ?></h1>

<div class="bordered">
    <div class="meta-grid">
        <div>Mã phiếu:</div>
        <div><strong><?= htmlspecialchars($document['IdPhieu'] ?? '-') ?></strong></div>
        <div>Ngày lập:</div>
        <div><?= !empty($document['NgayLP']) ? date('d/m/Y', strtotime($document['NgayLP'])) : '-' ?></div>
        <div>Ngày xác nhận:</div>
        <div><?= !empty($document['NgayXN']) ? date('d/m/Y', strtotime($document['NgayXN'])) : '-' ?></div>
        <div>Đối tác/Đơn vị:</div>
        <div><?= htmlspecialchars($document['DoiTac'] ?? '-') ?> (<?= htmlspecialchars($document['LoaiDoiTac'] ?? '') ?>)</div>
        <?php if (!empty($document['IdKhoNhan'])): ?>
            <div>Kho nhận:</div>
            <div><?= htmlspecialchars($destination['TenKho'] ?? ($document['IdKhoNhan'] ?? '-')) ?></div>
        <?php endif; ?>
        <div>Lý do:</div>
        <div><?= nl2br(htmlspecialchars($document['LyDo'] ?? '-')) ?></div>
        <div>Số tham chiếu:</div>
        <div><?= htmlspecialchars($document['SoThamChieu'] ?? '-') ?></div>
        <div>Tổng giá trị:</div>
        <div><strong><?= number_format($document['TongTien'] ?? 0, 0, ',', '.') ?> đ</strong></div>
    </div>
</div>

<table class="table">
    <thead>
    <tr>
        <th style="width: 18%;">Mã lô</th>
        <th style="width: 22%;">Tên lô</th>
        <th style="width: 25%;">Mặt hàng</th>
        <th style="width: 10%;">Số lượng</th>
        <th style="width: 10%;">Thực nhận</th>
        <th style="width: 10%;">Đơn vị</th>
    </tr>
    </thead>
    <tbody>
    <?php if (empty($details)): ?>
        <tr><td colspan="7" class="text-center muted">Chưa có chi tiết.</td></tr>
    <?php else: ?>
        <?php foreach ($details as $detail): ?>
            <tr>
                <td><?= htmlspecialchars($detail['IdLo'] ?? '-') ?></td>
                <td><?= htmlspecialchars($detail['TenLo'] ?? '-') ?></td>
                <td>
                    <div><strong><?= htmlspecialchars($detail['TenSanPham'] ?? '-') ?></strong></div>
                    <div class="muted">Mã SP: <?= htmlspecialchars($detail['IdSanPham'] ?? '-') ?></div>
                </td>
                <td class="text-end"><?= number_format($detail['SoLuong'] ?? 0) ?></td>
                <td class="text-end"><?= number_format($detail['ThucNhan'] ?? $detail['SoLuong'] ?? 0) ?></td>
                <td class="text-center"><?= htmlspecialchars($detail['DonViTinh'] ?? $detail['DonVi'] ?? '-') ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
<tfoot>
    <tr>
        <th colspan="3" class="text-end">Tổng cộng</th>
        <th class="text-end"><?= number_format($document['TongMatHang'] ?? 0) ?> mặt hàng</th>
        <th class="text-center"><?= number_format($document['TongSoLuong'] ?? 0) ?></th>
    </tr>
    </tfoot>
</table>

<?php if (!empty($document['GhiChu'])): ?>
    <div style="margin-top: 12px;"><strong>Ghi chú:</strong> <?= nl2br(htmlspecialchars($document['GhiChu'])) ?></div>
<?php endif; ?>

<div class="signature">
    <div>
        <div class="title">Người lập phiếu</div>
        <div class="muted subtext">(Ký, ghi rõ họ tên)</div>
        <div class="line"></div>
        <div class="mb-1"><strong><?= htmlspecialchars($document['NguoiLap'] ?? '') ?></strong></div>
    </div>
    <div>
        <div class="title">Người xác nhận</div>
        <div class="muted subtext">(Ký, ghi rõ họ tên)</div>
        <div class="line"></div>
        <div class="mb-1"><strong><?= htmlspecialchars($document['NguoiXacNhan'] ?? '') ?></strong></div>
    </div>
    <div>
        <div class="title">Thủ kho</div>
        <div class="muted subtext">(Ký, ghi rõ họ tên)</div>
        <div class="line"></div>
        <div class="mb-1"><strong><?= htmlspecialchars($warehouse['TenQuanKho'] ?? '') ?></strong></div>
    </div>
</div>
</div>
</body>
</html>
