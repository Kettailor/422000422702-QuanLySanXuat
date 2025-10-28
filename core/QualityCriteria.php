<?php
/**
 * core/QualityCriteria.php
 * Tiêu chí kiểm tra mặc định cho từng xưởng trong hệ thống SV5TOT
 */

return [
    // ---------------- XƯỞNG LẮP RÁP SV5TOT ----------------
    'Xưởng Lắp Ráp SV5TOT' => [
        ['TC01', 'Kiểm tra đầy đủ linh kiện trước lắp ráp'],
        ['TC02', 'Độ khớp chính xác giữa các bộ phận'],
        ['TC03', 'Không thiếu ốc/vít trong quá trình lắp'],
        ['TC04', 'Dây cáp kết nối đúng vị trí, không gấp khúc'],
        ['TC05', 'Kiểm tra cố định bo mạch chắc chắn'],
        ['TC06', 'Kiểm tra hoạt động LED và phím cơ'],
        ['TC07', 'Không cong, vênh khung bàn phím'],
        ['TC08', 'Độ bám của keo và ron cao su'],
        ['TC09', 'Không trầy xước sau lắp ráp'],
        ['TC10', 'Kiểm tra lần cuối trước bàn giao QC'],
    ],

    // ---------------- XƯỞNG KIỂM ĐỊNH & ĐÓNG GÓI ----------------
    'Xưởng Kiểm Định & Đóng Gói' => [
        ['TC01', 'Kiểm tra ngoại hình tổng thể sản phẩm'],
        ['TC02', 'Kiểm tra chức năng phím và đèn LED'],
        ['TC03', 'Đảm bảo tem nhãn đúng vị trí và không bong'],
        ['TC04', 'Phụ kiện đầy đủ theo quy chuẩn xuất xưởng'],
        ['TC05', 'Bao bì sạch sẽ, không rách hoặc móp méo'],
        ['TC06', 'Tem bảo hành và niêm phong đúng quy cách'],
        ['TC07', 'Trọng lượng gói hàng đúng tiêu chuẩn'],
        ['TC08', 'Mã lô, số serial trùng khớp hệ thống'],
        ['TC09', 'Độ sạch bề mặt và tem không dính bụi'],
        ['TC10', 'Kiểm tra lần cuối trước lưu kho hoặc xuất hàng'],
    ],
];
