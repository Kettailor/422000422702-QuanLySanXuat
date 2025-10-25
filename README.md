# Hệ thống quản lý sản xuất bàn phím Aurora

Ứng dụng mô phỏng một hệ thống ERP nội bộ cho doanh nghiệp lắp ráp bàn phím cơ Aurora, xây dựng bằng PHP thuần với kiến trúc MVC đơn giản. Mục tiêu là giúp điều hành chuỗi giá trị sản xuất bàn phím từ khâu nhận đơn hàng OEM/ODM, phân rã kế hoạch cho từng xưởng lắp ráp, kiểm soát chất lượng linh kiện và thành phẩm, quản lý kho linh kiện – thành phẩm cho tới theo dõi tài chính.

## Tính năng chính
- Tổng quan hoạt động sản xuất Aurora với dashboard thống kê nhanh.
- Quản lý đơn hàng và khách hàng doanh nghiệp (gear store, nhà phân phối,...).
- Lập kế hoạch sản xuất tổng thể và kế hoạch chi tiết cho từng xưởng Aurora.
- Theo dõi nhân sự, chấm công và phân công ca lắp ráp/kiểm thử.
- Quản lý kho linh kiện, phiếu nhập – xuất và các lô switch/PCB/thành phẩm.
- Kiểm soát chất lượng lô bàn phím Aurora và lưu vết biên bản đánh giá.
- Quản lý hóa đơn OEM, bảng lương và nhật ký hoạt động hệ thống.

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

## Dữ liệu mẫu Aurora
Script `data/script.sql` tái hiện dây chuyền Aurora với:
- Đơn hàng OEM (`DH20231101`, `DH20231105`,...) yêu cầu bàn phím Aurora 87/108 và kit custom.
- Kế hoạch sản xuất tổng và kế hoạch xưởng cho các hạng mục như lắp switch, kiểm thử PCB, đóng gói thành phẩm.
- Kho linh kiện (`KHO01`), kho thành phẩm (`KHO02`) với các lô switch Lotus, PCB Aurora R3, lô Aurora hoàn thiện.
- Biên bản kiểm tra ESD, đánh giá thành phẩm, phiếu nhập/xuất và lịch ca làm cho đội kỹ thuật.

Có thể điều chỉnh/seed lại dữ liệu bằng cách chạy lại script trên MySQL.

## Khởi chạy cục bộ
1. Cài đặt PHP >= 8.1 và một máy chủ web (Apache/Nginx) hoặc sử dụng PHP built-in server.
2. Cập nhật thông tin kết nối trong `config/config.php` cho phù hợp với môi trường.
3. Tạo cơ sở dữ liệu MySQL/MariaDB rồi chạy script `data/script.sql` để khởi tạo schema và dữ liệu mẫu Aurora.
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
