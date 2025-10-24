# Luồng tích hợp dữ liệu giữa dịch vụ

```
[Client] -> [API Gateway] -> [Production Service]
                          \-> [Reporting Service]
```

## Production Service

- Lưu trữ dữ liệu quan hệ trong PostgreSQL (`production_lines`, `work_orders`, `shifts`, `line_shift_metrics`).
- REST endpoint chính:
  - `GET /work-orders`, `GET /work-orders/:orderCode`, `POST /work-orders`.
  - `GET /production-lines`, `GET /production-lines/:lineCode/work-orders`.
  - `GET /performance/daily` để cung cấp số liệu sản lượng theo ca.

## Reporting Service

- Truy vấn dữ liệu từ Production Service thông qua API Gateway (`/api/production/...`).
- Truy cập MongoDB để đọc log thiết bị (`machine_logs` collection).
- Kết hợp dữ liệu và trả về báo cáo tổng hợp (`/reports/summary`) và KPI hiệu suất (`/reports/oee`).

## Đồng bộ hóa sự kiện

1. Production Service tạo lệnh mới -> phát sự kiện HTTP `POST /events/order-created` đến Reporting Service.
2. Reporting Service lưu trạng thái báo cáo (`reports` collection) và thông báo gateway để cache lại dữ liệu.

> Tương lai có thể thay HTTP events bằng message broker như RabbitMQ, Kafka hoặc NATS. Các hook nằm trong `services/*/src/events` để dễ mở rộng.

## Kiểm soát truy cập & bảo mật nội bộ

- Tất cả yêu cầu bên ngoài phải đi qua API Gateway.
- Dùng token nội bộ (ví dụ JWT với audience là `internal-services`) để xác thực giữa các dịch vụ.
- Sử dụng mạng nội bộ trong Docker Compose (`internal` network) để cô lập cơ sở dữ liệu và dịch vụ không công khai.

