export default function PlanDetail() {
  return (
    <div className="page">
      <div className="section-card">
        <div className="section-title">Thông tin chi tiết kế hoạch</div>
        <div className="form-grid">
          <div className="form-field">
            <label>Mã kế hoạch</label>
            <input value="KH-2025-0823-1001" readOnly />
          </div>
          <div className="form-field">
            <label>Mã đơn hàng</label>
            <input value="ERP/PO/GRB-324343" readOnly />
          </div>
          <div className="form-field">
            <label>Thời gian bắt đầu</label>
            <input value="Ngày 26/10/2025" readOnly />
          </div>
          <div className="form-field">
            <label>Thời gian kết thúc</label>
            <input value="Ngày 29/10/2025" readOnly />
          </div>
          <div className="form-field">
            <label>Sản phẩm</label>
            <input value="Bàn phím - Bản Phím ABC" readOnly />
          </div>
          <div className="form-field">
            <label>Trạng thái</label>
            <input value="Đang theo tiến độ" readOnly />
          </div>
        </div>
      </div>

      <div className="section-card" style={{ border: '1px solid rgba(248, 113, 113, 0.4)', background: '#fff5f5' }}>
        <div style={{ fontWeight: 600, color: '#b91c1c' }}>
          ⚠️ Bạn có chắc chắn muốn xóa kế hoạch này?
        </div>
        <div style={{ color: '#b91c1c', fontSize: 13 }}>
          Hành động này không thể hoàn tác và có thể ảnh hưởng đến dữ liệu liên quan.
        </div>
        <div className="flex-end">
          <button className="button-danger" style={{ background: '#f87171', color: '#fff' }}>Xóa đơn hàng</button>
          <button className="button-secondary">Hủy bỏ</button>
        </div>
      </div>
    </div>
  );
}
