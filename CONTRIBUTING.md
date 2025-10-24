# Hướng dẫn đóng góp

Cảm ơn bạn đã quan tâm đóng góp cho dự án. Vui lòng tuân thủ các nguyên tắc sau để giúp quy trình làm việc diễn ra trôi chảy.

## Quy trình chung
1. Tạo issue mô tả rõ nhu cầu hoặc lỗi.
2. Fork hoặc tạo nhánh mới từ `dev` cho từng thay đổi.
````bash
# Luôn đặt vị trí ở branch dev để xem tiến độ
git checkout dev
git pull origin dev

# Khi cập nhật dự án của bạn lên có thể bỏ nếu bạn làm việc tại repo mà bạn fork
git add .
git commmit -m "[Lí do cập nhật]"
git checkout feature/[vị trí code] #VD: feature/views_auth
git push origin feature/[vị trí code]
````
3. Đảm bảo commit có thông điệp ngắn gọn, nêu bật mục đích thay đổi.
4. Mở pull request và mô tả rõ phạm vi, ảnh hưởng, cách kiểm thử.

## Tiêu chuẩn code
- Sử dụng PHP >= 8.1, tuân thủ PSR-12 ở mức cơ bản (khoảng trắng, thụt lề 4 spaces).
- Không thêm framework/bộ thư viện mới nếu không thực sự cần thiết.
- Hạn chế logic trong view, ưu tiên xử lý trong controller/service.
- Với các truy vấn SQL mới, ưu tiên câu lệnh chuẩn bị (prepared statement).
- Khi thêm vai trò/quyền mới, cập nhật `data/script.sql`, navbar và tài liệu liên quan.

## Kiểm thử và kiểm tra
- Chạy `php -l <file>` cho những file PHP chỉnh sửa để đảm bảo không lỗi cú pháp.
- Nếu thêm logic phức tạp, cung cấp mô tả cách kiểm thử thủ công trong PR.
- Đảm bảo script SQL có thể chạy lại nhiều lần (sử dụng `ON DUPLICATE KEY UPDATE` khi phù hợp).

## Tài liệu
- Cập nhật `README.md`, `CHANGELOG.md` và các hướng dẫn người dùng khi có thay đổi chức năng.
- Thêm chú thích code khi xử lý nghiệp vụ khó hiểu.

Mọi đóng góp đều được ghi nhận. Hãy mở issue nếu có câu hỏi!
