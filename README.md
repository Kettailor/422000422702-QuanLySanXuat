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

* Docker Desktop hoặc Docker Engine 20.10+
* Docker Compose v2
* (Tùy chọn cho cách chạy thủ công) PHP 8.1+, MySQL/MariaDB

## Chạy dự án bằng Docker

Docker Compose đã được cấu hình để khởi chạy toàn bộ hệ thống (ứng dụng PHP + MySQL) chỉ với một lệnh.

1. Bảo đảm bạn đang ở thư mục gốc của dự án.
2. Khởi động stack:
   ```bash
   docker compose up --build
   ```
   Lần chạy đầu tiên sẽ build image PHP dựa trên `Dockerfile`, đồng thời MySQL sẽ được khởi tạo với dữ liệu trong `database/init/production.sql`.
3. Truy cập ứng dụng tại `http://localhost:8080`.

### Biến môi trường

Các thông số kết nối mặc định đã được cấu hình trong `docker-compose.yml`:

* `DB_HOST=mysql`
* `DB_DATABASE=quan_ly_san_xuat`
* `DB_USERNAME=qlsx_user`
* `DB_PASSWORD=qlsx_password`

Có thể thay đổi các giá trị này trong file compose, sau đó chạy lại `docker compose up --build` để áp dụng.

### Dừng và xóa container

* Dừng dịch vụ: `docker compose down`
* Dừng và xóa volume dữ liệu MySQL: `docker compose down -v`

## Hướng dẫn chạy thủ công (tuỳ chọn)

1. Cài đặt PHP 8.1 trở lên và bảo đảm có sẵn tiện ích dòng lệnh `php`.
2. Cài đặt MySQL/MariaDB nếu muốn dùng dữ liệu thật.
3. Clone dự án và truy cập vào thư mục gốc:
   ```bash
   git clone <repo-url>
   cd 422000422702-QuanLySanXuat
   ```
4. (Tuỳ chọn) Cập nhật thông tin kết nối CSDL trong `config/database.php` cho phù hợp môi trường của bạn.
5. Khởi động webserver tích hợp của PHP trỏ tới thư mục `public/`:
   ```bash
   php -S localhost:8000 -t public
   ```
6. Mở trình duyệt và truy cập `http://localhost:8000` để xem dashboard. Ứng dụng sẽ tự động hiển thị dữ liệu mẫu nếu không kết nối được cơ sở dữ liệu.

### Kết nối cơ sở dữ liệu (tuỳ chọn)

* Tạo sẵn một database MySQL/MariaDB trống và cấu hình quyền truy cập.
* Import các bảng cần thiết từ file `database/init/production.sql`. File chứa lại toàn bộ schema gốc (MySQL) kèm một số bảng mẫu phục vụ dashboard.
* Điều chỉnh `config/database.php` cho đúng host, username, password, tên database.
* Khởi tạo các bảng dữ liệu cho dashboard nếu muốn hiển thị số liệu thật:
  ```sql
  CREATE TABLE dashboard_statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    metric VARCHAR(120) NOT NULL,
    value INT NOT NULL,
    display_order INT DEFAULT 0
  );

  CREATE TABLE dashboard_activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(50),
    occurred_at DATETIME DEFAULT CURRENT_TIMESTAMP
  );

  CREATE TABLE dashboard_alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    type VARCHAR(30) NOT NULL,
    highlight VARCHAR(120),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  );

  CREATE TABLE dashboard_timeline (
    id INT AUTO_INCREMENT PRIMARY KEY,
    schedule_date DATE NOT NULL,
    title VARCHAR(255) NOT NULL,
    status VARCHAR(60)
  );
  ```
* Sau khi có bảng, chèn dữ liệu vào các bảng trên để thay thế số liệu mặc định hiển thị trên dashboard.

## Triển khai với Apache/Nginx

* **Apache**: Bật module `mod_rewrite` và trỏ VirtualHost vào thư mục `public/`. File `.htaccess` đã được chuẩn bị để tất cả request được điều hướng về `index.php`.
* **Nginx + PHP-FPM**: Cấu hình server block phục vụ `public/index.php` làm entry point. Tham khảo mẫu:
  ```nginx
  server {
      listen 80;
      server_name your-domain.test;
      root /var/www/422000422702-QuanLySanXuat/public;

      index index.php;

      location / {
          try_files $uri $uri/ /index.php?$query_string;
      }

      location ~ \.php$ {
          include fastcgi_params;
          fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
          fastcgi_pass unix:/run/php/php8.2-fpm.sock;
      }
  }
  ```

## Cấu hình bổ sung

* Thay đổi layout, stylesheet tại `public/assets/css/styles.css`.
* Các controller chính nằm trong `app/controllers/` (ví dụ `DashboardController` xử lý trang dashboard).
* Mọi request mặc định điều hướng tới `DashboardController@index`; có thể bổ sung controller/method mới bằng cách tạo file tương ứng và truy cập theo định dạng `/{controller}/{action}`.
