# Đề xuất cấu hình nhánh và quyền trên GitHub

## Bảo vệ nhánh `main`

- Bật **Require a pull request before merging**.
  - Tối thiểu 2 approvers.
  - Bắt buộc review từ Tech Lead.
- Bật **Require status checks to pass before merging**.
  - Bật `build-and-test` workflow.
  - Bật `Require branches to be up to date before merging`.
- Bật **Require signed commits** (nếu team có thể cấu hình GPG).
- Tắt quyền push trực tiếp cho mọi role ngoại trừ bot release (nếu có).

## Bảo vệ nhánh `develop`

- Require PR với ít nhất 1 approval.
- Cho phép squash merge để giữ lịch sử gọn.
- Cho phép Tech Lead push trực tiếp khi cần hotfix khẩn cấp.

## Nhánh `feature/*`

- Cho phép developer push.
- Khuyến khích rebase thường xuyên từ `develop`.
- Tên nhánh theo mẫu: `feature/<module>-<mo-ta-ngan>`.

## Quy trình release

1. Hoàn tất kiểm thử trên `develop`.
2. Tạo PR `develop -> main`, đính kèm checklist QA và link ticket.
3. Sau khi merge, tạo tag `vX.Y.Z` và cập nhật changelog.
4. Pipeline triển khai tự động cập nhật môi trường nội bộ thông qua self-hosted runner.

## Quyền truy cập

| Nhóm | Quyền gợi ý |
| --- | --- |
| Tech Lead | Admin hoặc Maintain | 
| Senior Dev | Write |
| Junior Dev | Triage |
| QA | Triage |

