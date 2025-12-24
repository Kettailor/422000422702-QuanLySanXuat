<?php

/**
 * core/QualityCriteria.php
 * Tiêu chí kiểm tra mặc định dùng chung cho hệ thống SV5TOT
 * - factory     : áp dụng cho từng xưởng sản xuất
 * - production  : áp dụng cho biên bản dây chuyền, thiết bị, quy trình
 * - worker      : áp dụng cho biên bản nhân công
 */

return [

    // =======================
    // 1) NHÓM XƯỞNG (factory)
    // =======================
    'factory' => [

        // ---------------- XƯỞNG LẮP RÁP SV5TOT ----------------
        'Xưởng Lắp Ráp SV5TOT' => [
            ['XG01', 'Kiểm tra đầy đủ linh kiện trước lắp ráp'],
            ['XG02', 'Độ khớp chính xác giữa các bộ phận'],
            ['XG03', 'Không thiếu ốc/vít trong quá trình lắp'],
            ['XG04', 'Dây cáp kết nối đúng vị trí, không gấp khúc'],
            ['XG05', 'Kiểm tra cố định bo mạch chắc chắn'],
            ['XG06', 'Kiểm tra hoạt động LED và phím cơ'],
            ['XG07', 'Không cong, vênh khung bàn phím'],
            ['XG08', 'Độ bám của keo và ron cao su'],
            ['XG09', 'Không trầy xước sau lắp ráp'],
            ['XG10', 'Kiểm tra lần cuối trước bàn giao QC'],
        ],

        // ---------------- XƯỞNG KIỂM ĐỊNH & ĐÓNG GÓI ----------------
        'Xưởng Kiểm Định & Đóng Gói' => [
            ['XG11', 'Kiểm tra ngoại hình tổng thể sản phẩm'],
            ['XG12', 'Kiểm tra chức năng phím và đèn LED'],
            ['XG13', 'Đảm bảo tem nhãn đúng vị trí và không bong'],
            ['XG14', 'Phụ kiện đầy đủ theo quy chuẩn xuất xưởng'],
            ['XG15', 'Bao bì sạch sẽ, không rách hoặc móp méo'],
            ['XG16', 'Tem bảo hành và niêm phong đúng quy cách'],
            ['XG17', 'Trọng lượng gói hàng đúng tiêu chuẩn'],
            ['XG18', 'Mã lô, số serial trùng khớp hệ thống'],
            ['XG19', 'Độ sạch bề mặt và tem không dính bụi'],
            ['XG20', 'Kiểm tra lần cuối trước lưu kho hoặc xuất hàng'],
        ],
    ],

    // =======================
    // 2) NHÓM DÂY CHUYỀN (production)
    // =======================
    'production' => [

        // ---------------- 1. LẮP RÁP CƠ KHÍ ----------------
        'Lắp ráp cơ khí' => [
            ['DC01', 'Kiểm tra đầy đủ linh kiện cơ khí trước khi lắp ráp'],
            ['DC02', 'Độ khớp chính xác giữa khung và plate'],
            ['DC03', 'Không cong, vênh, trầy xước khung bàn phím'],
            ['DC04', 'Ốc vít được siết chặt và đủ số lượng'],
            ['DC05', 'Không thiếu hoặc dư ron cao su, spacer'],
            ['DC06', 'Lắp keycap đúng layout quy định'],
            ['DC07', 'Bề mặt khung sạch, không bụi bẩn hay dầu mỡ'],
            ['DC08', 'Các phím được gắn đều, không lệch hàng'],
            ['DC09', 'Bộ vỏ không có khe hở, khít kín toàn bộ'],
            ['DC10', 'Kiểm tra tổng thể cơ cấu sau lắp ráp hoàn chỉnh'],
        ],

        // ---------------- 2. ĐIỆN TỬ - BO MẠCH ----------------
        'Điện tử - bo mạch' => [
            ['DC11', 'Bo mạch PCB được kiểm tra không lỗi mạch in'],
            ['DC12', 'Linh kiện được hàn đúng cực tính và vị trí'],
            ['DC13', 'Không có vết cháy, khét, hoặc gãy mối hàn'],
            ['DC14', 'Cáp nối giữa PCB và LED đúng đầu kết nối'],
            ['DC15', 'Điện áp kiểm tra đầu ra đúng tiêu chuẩn 5V'],
            ['DC16', 'Không có chân linh kiện bị cong hoặc thiếu'],
            ['DC17', 'Cảm biến, IC, chip hoạt động bình thường'],
            ['DC18', 'PCB được cố định chắc chắn vào khung đỡ'],
            ['DC19', 'Kiểm tra giao tiếp USB và cổng kết nối'],
            ['DC20', 'Đèn LED hoạt động đồng đều trên toàn bộ phím'],
        ],

        // ---------------- 3. ĐÓNG GÓI - TEM NHÃN ----------------
        'Đóng gói - tem nhãn' => [
            ['DC21', 'Tem nhãn in rõ ràng, không lem, đúng mã sản phẩm'],
            ['DC22', 'Bao bì không bị rách, móp, bẩn hoặc dính keo thừa'],
            ['DC23', 'Phụ kiện kèm theo đủ theo danh sách checklist'],
            ['DC24', 'Tem bảo hành dán đúng vị trí, không bong tróc'],
            ['DC25', 'Hộp carton có tem dán bên ngoài theo quy định'],
            ['DC26', 'Hướng dẫn sử dụng, phiếu bảo hành có trong hộp'],
            ['DC27', 'Sản phẩm được bọc túi chống tĩnh điện đúng quy cách'],
            ['DC28', 'Kiểm tra khối lượng tổng gói hàng đạt tiêu chuẩn'],
            ['DC29', 'Mã serial trùng khớp với hệ thống quản lý'],
            ['DC30', 'Hộp hoàn thiện đạt yêu cầu thẩm mỹ và đồng nhất'],
        ],

        // ---------------- 4. KIỂM THỬ SẢN PHẨM ----------------
        'Kiểm thử sản phẩm' => [
            ['DC31', 'Kiểm tra toàn bộ phím có phản hồi tín hiệu'],
            ['DC32', 'Kiểm tra độ sáng đồng đều của hệ thống LED'],
            ['DC33', 'Không có phím chết hoặc đèn lỗi'],
            ['DC34', 'Firmware hoạt động đúng phiên bản yêu cầu'],
            ['DC35', 'Tốc độ phản hồi phím đạt chuẩn dưới 10ms'],
            ['DC36', 'Không có tiếng ồn, rung hoặc chạm chập bất thường'],
            ['DC37', 'Cáp kết nối và driver nhận dạng đúng trên PC'],
            ['DC38', 'Thử nghiệm độ bền nhấn phím ngẫu nhiên 100 lần'],
            ['DC39', 'Thử nghiệm chế độ macro, lighting đúng chức năng'],
            ['DC40', 'Ghi nhận kết quả cuối cùng trước bàn giao QC'],
        ],

        // ---------------- 5. AN TOÀN & VỆ SINH ----------------
        'An toàn & vệ sinh' => [
            ['DC41', 'Nhân viên mang đầy đủ bảo hộ (găng, khẩu trang, áo)'],
            ['DC42', 'Khu vực sản xuất sạch sẽ, không bụi hoặc dầu mỡ'],
            ['DC43', 'Không có vật cản gây nguy hiểm quanh khu vực máy'],
            ['DC44', 'Các dây điện được cố định và sắp xếp gọn gàng'],
            ['DC45', 'Thiết bị PCCC, bình chữa cháy sẵn sàng sử dụng'],
            ['DC46', 'Lối thoát hiểm được thông thoáng, có đèn báo hiệu'],
            ['DC47', 'Bảng nội quy, cảnh báo được dán đúng vị trí'],
            ['DC48', 'Khu vực không có tiếng ồn vượt mức quy định'],
            ['DC49', 'Rác thải công nghiệp được phân loại đúng nơi'],
            ['DC50', 'Đảm bảo quy trình vệ sinh cuối ca làm việc'],
        ],
    ],

    // =======================
    // 3) NHÓM NHÂN CÔNG (worker)
    // =======================
    'worker' => [

        'Tác phong và kỷ luật lao động' => [
            ['NC01', 'Đi làm đúng giờ, không đi trễ về sớm'],
            ['NC02', 'Đeo đồ bảo hộ đúng quy định'],
            ['NC03', 'Giữ vệ sinh cá nhân/khu vực'],
            ['NC04', 'Thái độ hợp tác, tôn trọng đồng nghiệp'],
            ['NC05', 'Không dùng điện thoại khi làm việc'],
            ['NC06', 'Không tự ý rời vị trí'],
            ['NC07', 'Không gây ồn ào, mất trật tự'],
            ['NC08', 'Không mang đồ ăn/uống vào line'],
            ['NC09', 'Không hút thuốc trong xưởng'],
            ['NC10', 'Tuân thủ PCCC cơ bản'],
        ],

        'Kỹ năng thao tác và tay nghề' => [
            ['NC11', 'Thao tác đúng quy trình'],
            ['NC12', 'Dùng công cụ đúng cách'],
            ['NC13', 'Tốc độ thao tác đạt chuẩn'],
            ['NC14', 'Không gây lỗi trong thao tác'],
            ['NC15', 'Xử lý được sự cố nhỏ'],
            ['NC16', 'Tuân thủ thứ tự công đoạn'],
            ['NC17', 'Tốc độ ổn định, ít gián đoạn'],
            ['NC18', 'Tư thế làm việc đúng'],
            ['NC19', 'Tự kiểm lỗi sau công đoạn'],
            ['NC20', 'Đạt yêu cầu chất lượng thao tác'],
        ],

        'An toàn lao động' => [
            ['NC21', 'Đầy đủ mũ, găng, kính, khẩu trang'],
            ['NC22', 'Tuân thủ hướng dẫn an toàn khu vực'],
            ['NC23', 'Không dùng máy khi chưa đào tạo'],
            ['NC24', 'Báo cáo nguy cơ mất an toàn kịp thời'],
            ['NC25', 'Không đùa giỡn trong khu vực sản xuất'],
            ['NC26', 'Không vào vùng cấm'],
            ['NC27', 'Lối thoát hiểm luôn thông thoáng'],
            ['NC28', 'Biết dùng bình chữa cháy/sơ cứu'],
            ['NC29', 'Không vận hành khi có cảnh báo'],
            ['NC30', 'Check an toàn trước khi vào ca'],
        ],

        'Năng suất và hiệu quả công việc' => [
            ['NC31', 'Đạt sản lượng/định mức ngày'],
            ['NC32', 'Tỷ lệ lỗi thấp, ổn định'],
            ['NC33', 'Tiết kiệm NVL, đúng quy định'],
            ['NC34', 'Giảm thời gian chờ/thao tác thừa'],
            ['NC35', 'Đề xuất cải tiến hợp lý'],
            ['NC36', 'Tinh thần làm việc liên tục'],
            ['NC37', 'Tỷ lệ pass cao ở công đoạn'],
            ['NC38', 'Ghi chép số liệu chính xác'],
            ['NC39', 'Hỗ trợ đồng đội khi cần'],
            ['NC40', 'Tuân thủ khi đổi kế hoạch'],
        ],

        'Tinh thần và thái độ làm việc' => [
            ['NC41', 'Có trách nhiệm với công việc'],
            ['NC42', 'Hòa đồng, hỗ trợ đồng nghiệp'],
            ['NC43', 'Tuân thủ yêu cầu sản xuất'],
            ['NC44', 'Thái độ tích cực, cầu tiến'],
            ['NC45', 'Không than phiền, gây ảnh hưởng'],
            ['NC46', 'Tự giác học hỏi, sửa sai'],
            ['NC47', 'Không gian dối, không giấu lỗi'],
            ['NC48', 'Sẵn sàng tăng ca khi hợp lý'],
            ['NC49', 'Giữ gìn tài sản công ty'],
            ['NC50', 'Tham gia đầy đủ đào tạo/họp an toàn'],
        ],
    ],
];
