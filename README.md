# Quản Lý Sản Xuất - Microservice Monorepo

Dự án mẫu cung cấp kiến trúc microservice cho hệ thống quản lý sản xuất nhà máy. Mục tiêu là giúp team phát triển đồng bộ, chia sẻ dữ liệu sản xuất (lệnh, chuyền, hiệu suất máy) giữa các dịch vụ mà không cần triển khai lên môi trường host bên ngoài.

## Tổng quan kiến trúc

Kiến trúc bao gồm ba dịch vụ Node.js triển khai bằng Docker cùng với PostgreSQL và MongoDB để lưu trữ dữ liệu quan hệ và phi cấu trúc:

- **API Gateway** (`services/api-gateway`): cung cấp điểm truy cập REST chung, xác thực yêu cầu và định tuyến đến các dịch vụ con.
- **Production Service** (`services/production-service`): quản lý lệnh sản xuất, chuyền, ca làm việc và hiệu suất sản lượng trong PostgreSQL.
- **Reporting Service** (`services/reporting-service`): tổng hợp báo cáo sản xuất (OEE, log máy) kết hợp dữ liệu từ PostgreSQL và MongoDB.
- **PostgreSQL** (`postgres` service): chứa dữ liệu quan hệ (lệnh sản xuất, nhân sự, ca làm việc,…).
- **MongoDB** (`mongo` service): chứa dữ liệu phi cấu trúc (log máy móc, cảm biến,…).

Tất cả dịch vụ giao tiếp qua HTTP nội bộ và chia sẻ dữ liệu thông qua API và hàng đợi sự kiện (gợi ý mở rộng).

## Chạy dự án nội bộ với Docker Compose

```bash
docker compose up --build
```

Mặc định các cổng:

- API Gateway: `http://localhost:3000`
- Production Service: `http://localhost:4000`
- Reporting Service: `http://localhost:5000`
- PostgreSQL: `localhost:5432` (user: `prod_admin`, password: `prod_admin`, database: `production`)
- MongoDB: `localhost:27017` (user: `mongo_admin`, password: `mongo_admin`)

## Quy ước phát triển và Git Flow

- `main`: nhánh ổn định, luôn triển khai được. Bảo vệ bởi review bắt buộc và kiểm tra CI.
- `develop`: tích hợp các tính năng đã review, dùng để kiểm thử nội bộ.
- `feature/<tên>`: phát triển chức năng mới, merge vào `develop` thông qua Pull Request sau khi đạt yêu cầu CI và review.
- `hotfix/<tên>`: sửa lỗi khẩn cấp trên `main`. Sau khi merge vào `main`, cần đồng bộ lại với `develop`.

### Quyền truy cập gợi ý

| Vai trò | Quyền | Ghi chú |
| --- | --- | --- |
| Tech Lead | push trực tiếp `develop`, approve PR, quản lý release | Ít nhất 2 người approve khi merge vào `main`. |
| Senior Dev | tạo/merge PR vào `develop`, review chéo | Không được push trực tiếp `main`. |
| Junior Dev | tạo PR từ `feature/*` | Yêu cầu 1 review từ Senior trở lên. |

### Quy tắc commit & PR

- Sử dụng Conventional Commits (`feat:`, `fix:`, `chore:`, `docs:`...).
- Mỗi PR phải đi kèm mô tả, checklist kiểm thử, và phải pass workflow CI.
- Không merge khi pipeline đỏ.

## CI/CD

Workflow GitHub Actions (`.github/workflows/ci.yml`):

- Chạy trên `push`/`pull_request` với nhánh `main`, `develop`, `feature/**`, `hotfix/**`.
- Kiểm tra định dạng (`npm run lint`) và unit test (`npm test`) cho từng dịch vụ.
- Dễ dàng mở rộng thêm bước build Docker hoặc triển khai nội bộ (ví dụ với self-hosted runner).

Đối với môi trường nội bộ (không cần host công khai), có thể sử dụng:

1. **Runner tự quản lý**: cài GitHub Actions runner trên máy nội bộ (NAS, server nội bộ) để chạy pipeline và triển khai Docker Compose.
2. **Quy trình triển khai**: sau khi merge vào `develop` hoặc `main`, pipeline có thể chạy script `docker compose pull && docker compose up -d` trên máy nội bộ.

## Chia sẻ dữ liệu giữa dịch vụ

- Production Service cung cấp REST API phục vụ nghiệp vụ nhà máy:
  - `GET /work-orders`: danh sách lệnh sản xuất kèm chuyền phụ trách và sản lượng.
  - `POST /work-orders`: tạo lệnh sản xuất mới.
  - `GET /production-lines`: trạng thái từng chuyền với kế hoạch/thực tế và downtime trong ngày.
  - `GET /performance/daily`: tổng hợp sản lượng theo ca trong 3 ngày gần nhất.
- Reporting Service gọi API Gateway để nhận dữ liệu sản xuất, tính toán KPI (ví dụ OEE) và kết hợp với log máy từ MongoDB (`machine_logs`).
- API Gateway gộp các endpoint `/api/production/...` (work-order, line, performance) và `/api/reports/...` (summary, OEE) để client nội bộ sử dụng thống nhất.
- Các dịch vụ có thể phát sự kiện (RabbitMQ/Kafka - placeholder) qua HTTP hoặc message broker (chưa cấu hình, gợi ý mở rộng trong `docs/integration.md`).

## Phát triển cục bộ

1. Cài đặt `Node.js >= 18` và `Docker`.
2. Trong mỗi thư mục dịch vụ, chạy `npm install`.
3. Chạy unit test: `npm test`.
4. Khởi chạy một dịch vụ riêng: `npm start` (sử dụng `.env` để cấu hình biến môi trường cục bộ).

Các biến môi trường mặc định được định nghĩa trong `docker-compose.yml` và có thể override bằng file `.env` ở thư mục gốc.

## Tài liệu bổ sung

- [`docs/integration.md`](docs/integration.md): mô tả chi tiết luồng dữ liệu và đồng bộ giữa SQL/Mongo.
- [`docs/branch-protection.md`](docs/branch-protection.md): đề xuất cấu hình bảo vệ nhánh và quyền truy cập GitHub.

