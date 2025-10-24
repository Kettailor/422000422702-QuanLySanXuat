# Hệ thống Quản lý Sản xuất (MVC PHP)

Dự án được viết lại theo yêu cầu với kiến trúc MVC sử dụng PHP, HTML, CSS và JavaScript.

## Cấu trúc thư mục

```
app/
├── controllers/        # Bộ điều khiển xử lý luồng nghiệp vụ
├── models/             # Lớp truy cập và xử lý dữ liệu
└── views/              # Giao diện người dùng (chia nhỏ theo layout và module)
assets/
├── css/                # Tệp định dạng giao diện
└── js/                 # Script tương tác phía client
config/                 # Cấu hình ứng dụng và cơ sở dữ liệu
core/                   # Lớp nền tảng cho MVC (App, Controller, Model, View)
public/                 # Điểm vào ứng dụng, phục vụ tĩnh (index.php, .htaccess)
database/               # Giữ nguyên cấu trúc và script khởi tạo cơ sở dữ liệu
```

## Yêu cầu hệ thống

* PHP 8.1+
* Apache/Nginx (ví dụ: sử dụng `.htaccess` để định tuyến cho Apache)
* MySQL/MariaDB (script khởi tạo nằm tại `database/init/production.sql`)

## Khởi chạy nhanh với PHP built-in server

```bash
php -S localhost:8000 -t public
```

Sau đó truy cập `http://localhost:8000` để xem dashboard.

## Tùy chỉnh kết nối CSDL

Chỉnh sửa `config/database.php` cho phù hợp với môi trường triển khai. Ứng dụng sẽ tự động đọc dữ liệu dashboard nếu các bảng tương ứng tồn tại, hoặc hiển thị dữ liệu mẫu khi chưa kết nối được cơ sở dữ liệu.
