# Hướng dẫn đóng góp

Cảm ơn bạn đã quan tâm đóng góp cho dự án. Vui lòng tuân thủ các nguyên tắc sau để giúp quy trình làm việc diễn ra trôi chảy.

## Quy trình chung

### 1. Tạo Issue
- Mọi thay đổi đều bắt đầu bằng issue mô tả rõ **mục tiêu**, **phạm vi** và **ảnh hưởng** (frontend/backend, API, DB,...).
- Gắn label phù hợp (`bug`, `feature`, `enhancement`,... ) và gán người phụ trách.
- Liên kết các tài liệu hoặc hình ảnh liên quan để reviewer nắm bắt nhanh bối cảnh.

### 2. Tạo nhánh làm việc từ `dev`
```bash
# Luôn đảm bảo đang ở dev mới nhất
git checkout dev
git pull origin dev

# Tạo nhánh mới cho từng thay đổi
git checkout -b feature/<ten-module>_<muc-dich>
```

Quy tắc đặt tên nhánh:

| Loại nhánh | Cấu trúc | Ví dụ |
| --- | --- | --- |
| Tính năng mới | `feature/<module>_<muc-dich>` | `feature/user_login` |
| Sửa lỗi | `bugfix/<ma-van-de>` | `bugfix/api_update_user` |
| Khẩn cấp | `hotfix/<ma-van-de>` | `hotfix/docker_build_error` |
| Tái cấu trúc | `refactor/<module>` | `refactor/auth_service` |

Nếu làm việc trên repo fork, push nhánh lên fork của bạn trước:

```bash
git push origin <ten-nhanh>
```

### 3. Commit rõ ràng theo Conventional Commits
- Stage thay đổi bằng `git add` với phạm vi nhỏ, có ý nghĩa.
- Sử dụng mẫu commit: `type(scope): mô tả ngắn gọn`.

| type | Khi sử dụng |
| --- | --- |
| `feat` | Thêm chức năng mới |
| `fix` | Sửa lỗi |
| `docs` | Cập nhật tài liệu |
| `style` | Định dạng, chỉnh CSS, không đổi logic |
| `refactor` | Tái cấu trúc không đổi hành vi |
| `chore` | Thiết lập, cấu hình, thay đổi nhỏ |
| `test` | Thêm hoặc chỉnh test |

Ví dụ: `feat(auth): them xac thuc OTP cho nguoi dung`.

### 4. Đẩy nhánh lên remote
```bash
git push origin <ten-nhanh>
```

### 5. Mở Pull Request vào `dev`
- PR phải liên kết issue: `Closes #<so-issue>`.
- Tóm tắt thay đổi, ảnh hưởng hệ thống và hướng dẫn kiểm thử.
- Yêu cầu reviewer phù hợp và đảm bảo CI/CD (nếu có) đã chạy thành công.

### 6. Review và hợp nhất
1. Reviewer phản hồi, yêu cầu chỉnh sửa nếu cần.
2. Khi đồng ý, merge vào `dev` bằng `--no-ff` để giữ lịch sử rõ ràng.
3. Khi `dev` ổn định, merge vào `main` trong đợt phát hành.

Sau khi merge:
```bash
git branch -d <ten-nhanh>
git push origin --delete <ten-nhanh>
```

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

## CI/CD và bảo vệ nhánh
- `main` là nhánh bảo vệ, chỉ merge từ `dev` sau khi phát hành và đã qua kiểm thử.
- `dev` nhận code thông qua PR từ các nhánh chức năng/sửa lỗi.
- Kích hoạt workflow CI để tự động build/lint/test mỗi khi có PR.
- Thiết lập tự động deploy khi merge vào `main` (nếu hệ thống có hỗ trợ).
- Workflow GitHub Actions mặc định nằm tại `.github/workflows/ci.yml` và sẽ lint toàn bộ file PHP trên mọi PR/push vào `dev`.

Mọi đóng góp đều được ghi nhận. Hãy mở issue nếu có câu hỏi!
