# Đánh giá nghiệp vụ kho và phiếu kho

Tóm tắt những khoảng trống nghiệp vụ và rủi ro hiện tại trong module Kho/ Phiếu kho, dựa trên mã nguồn ở `controllers/WarehouseController.php`, `controllers/Warehouse_sheetController.php`, cùng các model kho, phiếu, lô.

## 1. Quản lý số dư tồn kho chưa được cập nhật tự động
- **Phiếu nhập/xuất không cập nhật tồn kho**: Khi tạo phiếu (`Warehouse_sheetController::store`), hệ thống chỉ lưu header phiếu và (nếu nhập nhanh) tạo lô và chi tiết phiếu, nhưng không cộng/trừ tồn lượng kho hay lô hiện hữu. Điều này khiến số liệu trong `KHO.TongSL`, `KHO.ThanhTien` và lượng tồn thực tế không biến động theo giao dịch.【F:controllers/Warehouse_sheetController.php†L36-L117】【F:models/InventorySheet.php†L139-L172】
- **Không kiểm soát số lượng khi xuất**: Logic hiện tại không kiểm tra số lượng xuất so với tồn lô/kho; không có giới hạn âm hoặc cảnh báo thiếu hàng nên có thể tạo phiếu xuất vượt tồn.【F:controllers/Warehouse_sheetController.php†L36-L117】【F:models/InventorySheetDetail.php†L8-L34】

## 2. Thiếu ràng buộc giữa loại kho, loại phiếu và sản phẩm
- **Không xác thực hướng phiếu**: Trường `LoaiPhieu` chỉ kiểm tra không rỗng; không buộc theo danh mục (nhập/xuất) hay khớp với loại kho. Người dùng có thể lưu giá trị bất kỳ, dẫn tới thống kê sai hoặc lọc theo `LIKE 'Phiếu nhập%'` không chính xác.【F:controllers/Warehouse_sheetController.php†L19-L98】【F:models/InventorySheet.php†L12-L54】
- **Chưa khớp sản phẩm với loại kho**: Khi nhập nhanh, hệ thống không kiểm tra sản phẩm thuộc kho nguyên liệu/thành phẩm/XL lỗi; `WarehouseController::classifyProductForWarehouseTypes` chỉ dùng heuristics gợi ý danh sách mà không ràng buộc ở bước lưu. Có thể đưa thành phẩm vào kho lỗi hoặc ngược lại mà không bị chặn.【F:controllers/WarehouseController.php†L104-L209】【F:controllers/Warehouse_sheetController.php†L86-L155】

## 3. Vấn đề về lô hàng và chi tiết phiếu
- **Lô mới không cập nhật thống kê kho**: `InventoryLot::createLot` chỉ chèn bản ghi lô, không cộng `TongSLLo` hay `TongSL` của kho. Thống kê sử dụng truy vấn tổng hợp nên chưa sai lệch, nhưng các cột tổng trong bảng KHO sẽ không phản ánh tự động.【F:models/InventoryLot.php†L8-L76】【F:models/Warehouse.php†L129-L181】
- **Thiếu gắn chi tiết phiếu với tồn lô hiện hữu**: Khi lập phiếu xuất, không chọn lô nguồn hay giảm `LO.SoLuong`; `CT_PHIEU` chỉ lưu số lượng mà không ràng buộc hoặc cập nhật số lượng lô tương ứng.【F:models/InventorySheetDetail.php†L8-L34】【F:controllers/Warehouse_sheetController.php†L86-L155】
- **Không có cơ chế hủy/rollback lô khi xóa phiếu**: Xóa phiếu chỉ xóa header và chi tiết nhưng không xóa/hoàn tồn lô tạo kèm trong nhập nhanh, có thể để lại lô mồ côi không còn phiếu tham chiếu.【F:controllers/Warehouse_sheetController.php†L118-L188】【F:models/InventoryLot.php†L8-L76】

## 4. Quy trình phê duyệt và kiểm soát trách nhiệm
- **Không có trạng thái phê duyệt phiếu**: Phiếu chỉ lưu ngày lập/xác nhận và người lập/xác nhận, nhưng không có cờ trạng thái (nháp/chờ duyệt/đã duyệt/đã hủy). Không có kiểm soát quyền giữa người lập và người xác nhận hay luồng duyệt nhiều bước.【F:models/InventorySheet.php†L12-L138】
- **Thiếu kiểm tra vai trò**: `Warehouse_sheetController` chỉ yêu cầu vai trò `VT_NHANVIEN_KHO`; không kiểm tra vai trò người xác nhận hoặc hạn chế tự duyệt. Điều này làm giảm tính phân quyền kiểm soát kho.【F:controllers/Warehouse_sheetController.php†L1-L49】

## 5. Kiểm tra dữ liệu và tính nhất quán
- **Thiếu kiểm tra trùng lô/phiếu**: ID phiếu/lô sinh từ timestamp nhưng cho phép nhập tay; không có kiểm tra tồn tại trước khi chèn nên có thể trùng khóa gây lỗi DB hoặc ghi đè khi cập nhật.【F:controllers/Warehouse_sheetController.php†L36-L117】【F:models/InventoryLot.php†L46-L66】
- **Không chuẩn hóa đơn vị/tiền tệ**: Đơn vị tính được nhập tự do hoặc lấy từ sản phẩm nhưng không đảm bảo bắt buộc; giá trị tiền chỉ lưu tổng, không có chi tiết đơn giá, thuế, chiết khấu. Dễ sai lệch khi tổng hợp hoặc đối soát hóa đơn.【F:controllers/Warehouse_sheetController.php†L86-L155】【F:models/InventorySheet.php†L139-L191】

## 6. Báo cáo và truy vết
- **Thiếu nhật ký biến động tồn**: Không có bảng/logic ghi lại biến động tồn kho (stock ledger). Việc phân tích sai lệch chỉ dựa vào phiếu, không có bảng running balance để truy vết nhanh.
- **Chưa có cảnh báo tồn tối thiểu/tối đa**: Mã không tính toán hay cảnh báo khi tồn dưới mức an toàn hoặc vượt sức chứa, dù bảng KHO có cột `TongSL`. Điều này hạn chế khả năng quản lý tồn chủ động.【F:models/Warehouse.php†L129-L181】

## 7. Gợi ý hoàn thiện
- Thêm service tính tồn kho, cập nhật tồn khi lập/sửa/xóa phiếu (có transaction và khóa lô/kho).
- Bổ sung trạng thái phiếu và quy trình duyệt (nháp ➜ chờ duyệt ➜ duyệt ➜ hủy) cùng kiểm tra vai trò.
- Ràng buộc loại phiếu với loại kho, sản phẩm, và hướng nhập/xuất; kiểm tra số lượng xuất không vượt tồn.
- Chuẩn hóa dữ liệu (danh mục loại phiếu, đơn vị, giá/thuế), kiểm tra trùng ID lô/phiếu trước khi chèn.
- Thêm nhật ký tồn và cảnh báo min/max tồn kho.

## 8. Quy trình vận hành đề xuất (tham chiếu khi thiết kế/đối chiếu mã nguồn)

### 8.1. Chuỗi nhập kho chuẩn
1. **Khởi tạo yêu cầu nhập (nháp)**: Người lập chọn *loại kho đích* (nguyên liệu/thành phẩm/lỗi), *nguồn* (NCC, chuyển kho, hoàn trả sản xuất), và danh sách sản phẩm. Kiểm tra nhanh đơn vị tính, hạn sử dụng.
2. **Xác nhận/duyệt nhập**: Người có quyền duyệt kiểm tra chứng từ (PO, hóa đơn, phiếu chuyển) và duyệt. Hệ thống tạo ID phiếu, sinh lô (mỗi SKU theo PO hoặc theo ngày) và cập nhật sẵn *trạng thái phiếu=Đã duyệt*.
3. **Cập nhật tồn & ledger**: Sau duyệt, thực hiện transaction: tăng tồn kho và tồn lô, ghi dòng sổ cái tồn (ledger) với số dư trước/sau. Nếu nhập một phần, cho phép trạng thái *Một phần* và tạo các phiếu nhập bổ sung.
4. **Đối soát chất lượng**: Nếu kho lỗi, ghi nhận nguyên nhân; nếu kho tốt, có cờ QC/đóng dấu chất lượng. Các bước này làm rõ trách nhiệm và hạn chế nhập nhầm kho.

### 8.2. Chuỗi xuất kho chuẩn
1. **Đề nghị xuất (nháp)**: Người lập chọn kho nguồn, loại phiếu (xuất bán, xuất sản xuất, chuyển kho, thanh lý), và danh sách sản phẩm. Hệ thống kiểm tra tồn tối thiểu và cảnh báo nếu thiếu.
2. **Chọn lô theo chiến lược**: Bắt buộc chọn lô theo chính sách (FIFO, FEFO hoặc theo số batch chỉ định). Kiểm tra tồn lô và tồn kho; chặn xuất âm.
3. **Duyệt và khóa tồn**: Người duyệt xác nhận số lượng/lô; hệ thống trừ tạm tồn (đặt giữ) để tránh over-issue song song.
4. **Hoàn tất xuất**: Khi giao/nhận đủ, thực hiện transaction trừ tồn lô và tồn kho, ghi ledger. Nếu giao thiếu, cập nhật trạng thái *Một phần* và ghi lại lượng còn giữ/đặt.

### 8.3. Chuyển kho (nội bộ)
- **Hai bước nhập/xuất**: Tạo cặp phiếu (xuất kho nguồn ➜ nhập kho đích) cùng một mã tham chiếu. Phiếu xuất giảm tồn kho nguồn và giữ trạng thái *Đang vận chuyển*; phiếu nhập chỉ cộng tồn khi kho đích xác nhận nhận hàng.
- **Theo dõi vận chuyển**: Lưu thời gian giao/nhận, người giao/nhận, đơn vị vận chuyển. Hạn chế kho đích tự nhận trước khi kho nguồn duyệt xuất.

### 8.4. Kiểm kê và điều chỉnh
- **Phiếu kiểm kê**: Lập phiếu kiểm kê theo kho/lô, khóa giao dịch tạm thời hoặc dùng cột “đang kiểm kê” để tránh chồng chéo.
- **So sánh và tạo chênh lệch**: Sau kiểm kê, tự động sinh phiếu điều chỉnh (tăng/giảm) có căn cứ số liệu kiểm kê, được duyệt bởi cấp có thẩm quyền trước khi cập nhật tồn và ledger.

### 8.5. Quy trình hủy/hoàn tác
- **Hủy phiếu chưa duyệt**: Xóa mềm, không ảnh hưởng tồn.
- **Hủy phiếu đã duyệt**: Tạo phiếu đảo (reverse) hoặc rollback transaction: trả lại số lượng cho lô/kho, ghi ledger âm để bảo toàn lịch sử.
- **Kiểm tra phụ thuộc**: Chỉ cho hủy nếu chưa dùng trong hóa đơn/bán hàng/đơn sản xuất, hoặc cần phiếu điều chỉnh thay vì hủy.

### 8.6. Cảnh báo và kiểm soát thời gian thực
- Cảnh báo tồn dưới mức tối thiểu, tồn quá hạn sử dụng, hoặc lô sắp hết hạn.
- Báo trạng thái phiếu chờ duyệt quá SLA; gửi thông báo cho người duyệt.
- Log đầy đủ thao tác (ai lập, ai duyệt, ai hủy, thời điểm) để truy vết trách nhiệm.

## 9. Đề xuất yêu cầu chi tiết cho Kho và Phiếu kho (bổ sung những thiếu hụt)

### 9.1. Trạng thái, vòng đời phiếu và kiểm soát tồn
- **Bắt buộc trạng thái rõ ràng**: `Nháp → Chờ duyệt → Đã duyệt/Đã xuất/Đã nhập → Đã hủy → Đảo/Điều chỉnh`. Hành động thay đổi trạng thái phải ghi nhật ký người thực hiện và thời gian.
- **Giữ chỗ tồn (reservation)**: Khi phiếu xuất ở trạng thái *Chờ duyệt*, thực hiện giữ chỗ trên từng lô (QtyReserved) để tránh xuất vượt. Khi duyệt hoặc hủy, lượng giữ chỗ phải được giải phóng/cập nhật tồn thực tế.
- **Khóa kỳ kho**: Cấu hình khóa kỳ (theo tháng) để chặn lập/sửa/hủy phiếu trong kỳ đã chốt; thao tác điều chỉnh phải thông qua phiếu điều chỉnh kỳ sau.
- **Ngăn tồn âm**: Mọi thao tác xuất/điều chỉnh giảm phải kiểm tra tồn lô và tồn kho, chặn âm trừ khi người duyệt có quyền đặc biệt và ghi nhận lý do.

### 9.2. Đánh số chứng từ và liên kết tham chiếu
- **Sinh số phiếu chuẩn hóa**: Quy tắc `PNK/PKX/CK/KK/YYYYMM/sequence` theo loại phiếu, reset mỗi tháng. Khi cho phép nhập tay phải kiểm tra trùng (unique) và log người sửa.
- **Liên kết chứng từ**: Lưu reference tới PO/Đơn sản xuất/Đơn bán hàng/Đề nghị chuyển kho. Khi hủy, cần kiểm tra phụ thuộc và tự động cập nhật trạng thái chứng từ nguồn (ví dụ: trả lại phần chưa nhận của PO khi hủy phiếu nhập).
- **Cặp phiếu chuyển kho**: Bắt buộc lưu `TransferId` dùng chung cho phiếu xuất và phiếu nhập, cùng trạng thái vận chuyển (*Đang giao*, *Đã nhận*, *Thất lạc*).

### 9.3. Quản lý lô, hạn sử dụng và chất lượng
- **Bắt buộc chọn/chia lô khi xuất**: UI và API phải yêu cầu chọn lô (theo FIFO/FEFO hoặc chỉ định). Phiếu xuất phải lưu `LotId`, `ExpireDate`, `SoLuong`, `SoLuongTonSau`. Không cho phép xuất lô hết hạn.
- **Nhập tạo lô theo quy tắc**: Cho phép quy tắc tạo lô (theo PO, theo ngày, theo nhà cung cấp). Validate trùng số lô trong cùng sản phẩm/kho.
- **Trạng thái chất lượng**: Mỗi lô cần cờ QC (*Chưa kiểm*, *Đạt*, *Không đạt*). Phiếu xuất kho tốt chỉ dùng lô đạt; phiếu chuyển kho lỗi/thu hồi phải đánh dấu nguyên nhân.
- **Hoàn tác lô**: Khi đảo phiếu nhập/xuất, cập nhật `Lot.OnHand`, `Lot.Reserved`, `Lot.Status` và ghi ledger âm để giữ lịch sử.

### 9.4. Dữ liệu chi tiết dòng phiếu
- **Chi tiết số lượng và giá**: Mỗi dòng cần `DonViTinh` chuẩn hóa, `SoLuong`, `DonGia`, `ThanhTien`, `Thue`, `ChietKhau`, `SoLuongDaNhan/DaGiao` (để theo dõi phần giao/nhận). Đối với sản xuất, thêm `CaSX/LenhSX`.
- **Đơn vị quy đổi**: Cho phép chọn UOM khác nhưng phải quy đổi về đơn vị chuẩn sản phẩm, lưu cả hệ số quy đổi để tính tồn thống nhất.
- **Ghi chú kiểm soát**: Trường lý do/ghi chú bắt buộc khi điều chỉnh tăng/giảm, xuất hủy, xuất vượt tồn (nếu cho phép). Kèm mã lỗi chất lượng khi đưa vào kho lỗi.

### 9.5. Phân quyền và tách biệt nhiệm vụ
- **Ma trận quyền**:
  - Lập phiếu: Nhân viên kho/điều phối.
  - Duyệt: Quản lý kho hoặc cấp có quyền; không cho tự duyệt phiếu do mình lập.
  - Hủy/đảo: Quản lý kho hoặc kế toán kho; nếu phiếu đã liên kết hóa đơn/đơn sản xuất cần quyền cao hơn.
  - Kiểm kê: Tổ kiểm kê độc lập; người kiểm kê không trùng người duyệt điều chỉnh.
- **Log và chữ ký số**: Ghi log thao tác; cho phép chữ ký số/OTP cho phiếu quan trọng (nhập kho mua hàng, xuất bán).

### 9.6. Tích hợp quy trình liên quan
- **Mua hàng**: Phiếu nhập phải đối chiếu PO và hóa đơn; chỉ nhận tối đa phần còn lại của PO. Cho phép nhập một phần và cập nhật `PO.ReceivedQty`.
- **Bán hàng**: Phiếu xuất bán phải khớp với đơn bán; trừ tồn đặt giữ cho khách. In packing list/biên bản giao nhận từ phiếu.
- **Sản xuất**: Xuất NVL theo lệnh sản xuất; nhập thành phẩm theo ca/định mức, ghi nhận chênh lệch định mức. Hàng lỗi trả về kho lỗi có phiếu riêng, liên kết nguyên nhân.
- **Kế toán**: Ghi nhận giá vốn (FIFO/định mức) và thuế; khóa kỳ phải được đồng bộ với khóa sổ kế toán.

### 9.7. Báo cáo và đối soát
- **Sổ cái tồn kho (Stock Ledger)**: Bảng bắt buộc gồm `Time, Kho, Lot, Product, Phiếu, Loại, Nhập, Xuất, OnHandBefore, OnHandAfter, User`. Dùng cho truy vết và đối chiếu.
- **Báo cáo tuổi lô & hạn dùng**: Phát hiện lô sắp hết hạn, quá hạn; gắn với cảnh báo để xử lý (giảm giá, hủy, chuyển kho lỗi).
- **Đối soát PO/SO/LSX**: Báo cáo số lượng đã nhập/xuất so với đơn nguồn, cảnh báo phần thiếu/thừa.
- **Đối chiếu kiểm kê**: Kết quả kiểm kê, phiếu điều chỉnh, và số dư sổ phải khớp; cung cấp dashboard sai lệch theo SKU/kho.

### 9.8. Hiệu năng và độ tin cậy
- **Giao dịch (transaction) quanh cập nhật tồn**: Mọi thao tác lập/sửa/hủy phiếu tác động tồn phải thực hiện trong transaction, khóa theo kho/lô để tránh race condition.
- **Idempotent & retry**: API lập phiếu/lô cần idempotent (dựa trên `ClientRequestId`) để tránh nhân đôi khi người dùng gửi lại.
- **Validation đầu vào**: Bắt buộc kiểm tra tồn tại `WarehouseId`, `ProductId`, `LotId`, trạng thái kho (*Hoạt động*), và quyền truy cập kho trước khi lưu chi tiết.

### 9.9. Quy trình vận hành chi tiết (bổ sung điểm kiểm soát)
- **Nhập**: (1) Lập phiếu/nháp với danh sách sản phẩm; (2) Duyệt và tạo lô; (3) Cập nhật tồn+lô+ledger; (4) In/đính kèm chứng từ; (5) Theo dõi phần còn nhận nếu nhận thiếu.
- **Xuất**: (1) Đề nghị xuất, kiểm tra tồn và giữ chỗ; (2) Chọn lô, xác nhận vận chuyển; (3) Duyệt, cập nhật tồn+lô+ledger; (4) Cập nhật tình trạng giao nhận, đóng phiếu khi hoàn tất.
- **Chuyển kho**: (1) Tạo phiếu xuất kèm TransferId; (2) Duyệt và cập nhật trạng thái *Đang giao*; (3) Kho đích nhận và lập phiếu nhập dùng chung TransferId; (4) Đóng vận chuyển khi đủ.
- **Kiểm kê**: (1) Lập kế hoạch phạm vi và thời gian khóa giao dịch; (2) Đếm thực tế theo lô; (3) Nhập kết quả, đối chiếu; (4) Sinh phiếu điều chỉnh, duyệt; (5) Mở khóa giao dịch.
- **Hủy/đảo**: (1) Kiểm tra phụ thuộc; (2) Lập phiếu đảo hoặc đánh dấu hủy; (3) Cập nhật tồn/lô/ledger; (4) Log lý do và người phê duyệt.
