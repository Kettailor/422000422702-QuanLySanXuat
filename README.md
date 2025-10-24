# Hệ thống quản lý sản xuất bánh quy

Ứng dụng mô phỏng một hệ thống ERP nội bộ cho doanh nghiệp sản xuất bánh quy, xây dựng bằng PHP thuần với kiến trúc MVC đơn giản. Mục tiêu là giúp quản lý chuỗi giá trị sản xuất từ khâu nhận đơn hàng đến điều hành xưởng, kiểm soát chất lượng, kho vận và tài chính.

## Tính năng chính
- Tổng quan hoạt động với dashboard thống kê nhanh.
- Quản lý đơn hàng và khách hàng doanh nghiệp.
- Lập kế hoạch sản xuất tổng thể và cho từng xưởng.
- Theo dõi nhân sự, chấm công và phân công ca làm.
- Quản lý kho, phiếu nhập – xuất và các lô nguyên vật liệu/thành phẩm.
- Kiểm soát chất lượng lô sản xuất và lưu vết biên bản đánh giá.
- Quản lý hóa đơn, bảng lương và hoạt động hệ thống.

## Vai trò và phân quyền
Hệ thống hỗ trợ nhiều nhóm người dùng tương ứng với actor trong sơ đồ use case:

| Mã vai trò | Diễn giải | Phạm vi tính năng |
|------------|-----------|-------------------|
| `VT_ADMIN` | Quản trị hệ thống | Toàn quyền các module và mục cài đặt. |
| `VT_BAN_GIAM_DOC` | Ban giám đốc | Dashboard, kế hoạch sản xuất, nhân sự, chất lượng, lương, đơn hàng. |
| `VT_QUANLY_XUONG` | Quản lý xưởng | Dashboard, kế hoạch sản xuất & xưởng, kho, chất lượng. |
| `VT_NHANVIEN_SANXUAT` | Nhân viên sản xuất | Dashboard, kế hoạch xưởng được giao. |
| `VT_NHANVIEN_KHO` | Nhân viên kho | Dashboard, kho và phiếu kho. |
| `VT_KETOAN` | Kế toán | Dashboard, hóa đơn và bảng lương. |
| `VT_KIEM_SOAT_CL` | Kiểm soát chất lượng | Dashboard, module chất lượng. |
| `VT_KINH_DOANH` | Nhân viên kinh doanh | Dashboard, module đơn hàng và hóa đơn. |
| `VT_NHAN_SU` | Nhân sự | Dashboard, quản lý nhân sự. |
| `VT_DOI_TAC_VAN_TAI` | Điều phối vận tải/đối tác | Chỉ truy cập dashboard và các thông báo liên quan. |
| `VT_KHACH` | Khách nội bộ | Dành cho tích hợp về sau, không có quyền thao tác hiện tại. |

> Lưu ý: Người dùng có vai trò `VT_ADMIN` luôn được phép truy cập mọi chức năng, các vai trò khác chỉ thấy menu và sử dụng được các module đã liệt kê.

## Cấu trúc thư mục
```
config/          Cấu hình kết nối cơ sở dữ liệu
controllers/    Bộ điều khiển cho từng module nghiệp vụ
core/           Lớp nền tảng (Controller, Database)
data/           Kịch bản SQL khởi tạo dữ liệu mẫu
models/         Lớp truy xuất dữ liệu
public/         Tài nguyên tĩnh (CSS, JS)
services/       Các lớp xử lý bổ trợ (nếu có)
views/          Giao diện hiển thị (PHP + HTML)
```

## Khởi chạy cục bộ
1. Cài đặt PHP >= 8.1 và một máy chủ web (Apache/Nginx) hoặc sử dụng PHP built-in server.
2. Cập nhật thông tin kết nối trong `config/config.php` cho phù hợp với môi trường.
3. Tạo cơ sở dữ liệu MySQL/MariaDB rồi chạy script `data/script.sql` để khởi tạo schema và dữ liệu mẫu.
4. Khởi động ứng dụng bằng lệnh:
   ```bash
   php -S localhost:8000 index.php
   ```
5. Truy cập [http://localhost:8000](http://localhost:8000) và đăng nhập bằng một trong các tài khoản mẫu.

## Tài khoản mẫu
| Tên đăng nhập | Mật khẩu | Vai trò |
|---------------|----------|---------|
| `admin.minh` | `Matkhau!2023` | Quản trị hệ thống |
| `ql.lan` | `matkhau@123` | Quản lý xưởng |
| `sx.anh` | `matkhau@123` | Nhân viên sản xuất |
| `kho.trang` | `matkhau@123` | Nhân viên kho |
| `cl.hanh` | `matkhau@123` | Kiểm soát chất lượng |
| `kd.long` | `matkhau@123` | Kinh doanh |
| `ketoan.tai` | `matkhau@123` | Kế toán |
| `nhansu.mai` | `matkhau@123` | Nhân sự |

Các tài khoản khác có thể được thêm trong `data/script.sql`.

## Đóng góp & phát triển
- Xem thêm tài liệu trong [`CONTRIBUTING.md`](CONTRIBUTING.md) trước khi mở pull request.
- Mỗi module được phân tách theo controller/model/view riêng biệt; nên giữ phong cách code PHP thuần, tránh phụ thuộc mới không cần thiết.
- Khi bổ sung tính năng, đảm bảo cập nhật tài liệu và script dữ liệu mẫu tương ứng với phân quyền.

## Giấy phép
Dự án phục vụ mục đích học tập và có thể được tái sử dụng tự do trong phạm vi học thuật.
