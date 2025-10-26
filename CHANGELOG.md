# Changelog
Tất cả thay đổi đáng chú ý của dự án sẽ được ghi lại trong tài liệu này.

## [Unreleased]
### Added
- Tài liệu dự án (`README.md`), hướng dẫn đóng góp (`CONTRIBUTING.md`) và nhật ký thay đổi.
- Lớp `Role` cùng cơ chế phân quyền trong `Controller` và các controller nghiệp vụ.
- Bộ vai trò mới tương ứng actor trong use case và tài khoản mẫu trong `data/script.sql`.

### Changed
- Navbar hiển thị động theo quyền truy cập và bổ sung nhãn vai trò người dùng.
- Trang hồ sơ hiển thị tên vai trò và mã quyền.
- Script khởi tạo dữ liệu cập nhật nhân sự, người dùng và quyền hạn.
- Nội dung tài liệu và giao diện chính (dashboard, đơn hàng, kho, chất lượng) được điều chỉnh để phản ánh dây chuyền bàn phím SV5TOT.
- Tái thương hiệu toàn bộ hệ thống sang tên mới "SV5TOT" kèm tinh chỉnh UI/UX cho dashboard và bảng lương/kho.

### Fixed
- Ngăn truy cập trái phép vào các module bằng cơ chế `authorize` mới.
