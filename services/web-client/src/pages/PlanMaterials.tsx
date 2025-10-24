const materials = [
  { code: 'NL01', name: 'Nhựa PC A1', required: '5,000 Kilogram', available: '4,500 Kilogram', status: 'Còn đủ', note: 'Sẵn sàng giao trong kho' },
  { code: 'NL07', name: 'Switch Blue', required: '20,000 Cái', available: '16,000 Cái', status: 'Cần bổ sung', note: 'Đặt hàng nhà cung cấp A' }
];

export default function PlanMaterials() {
  return (
    <div className="page">
      <div className="section-card">
        <div className="section-title">Thông tin kế hoạch xưởng</div>
        <div className="form-grid">
          <div className="form-field">
            <label>Mã kế hoạch</label>
            <input value="KH-2025-0823-1002" readOnly />
          </div>
          <div className="form-field">
            <label>Xưởng</label>
            <input value="Xưởng 2 - Lắp ráp" readOnly />
          </div>
          <div className="form-field">
            <label>Sản phẩm</label>
            <input value="Switch Blue" readOnly />
          </div>
          <div className="form-field">
            <label>Ngày bắt đầu</label>
            <input value="24/10/2025" readOnly />
          </div>
          <div className="form-field">
            <label>Ngày kết thúc</label>
            <input value="29/10/2025" readOnly />
          </div>
        </div>
      </div>

      <div className="section-card">
        <div className="flex-between">
          <div className="section-title">Nguyên liệu từ kho</div>
          <button className="tag-button">Thêm nguyên liệu</button>
        </div>
        <div className="table-wrapper">
          <table className="table">
            <thead>
              <tr>
                <th>Mã NL</th>
                <th>Tên NL</th>
                <th>Số lượng cần</th>
                <th>Số lượng có</th>
                <th>Ghi chú</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              {materials.map((material) => (
                <tr key={material.code}>
                  <td>{material.code}</td>
                  <td>{material.name}</td>
                  <td>{material.required}</td>
                  <td>
                    <span className={`badge ${material.status === 'Còn đủ' ? 'badge--success' : 'badge--warning'}`}>
                      {material.available}
                    </span>
                  </td>
                  <td>{material.note}</td>
                  <td>
                    <button className="button-danger">Xóa</button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      <div className="section-card">
        <div className="section-title">Tiến độ thực hiện</div>
        <div className="progress-bar">
          <div className="progress-bar__value" style={{ width: '75%' }} />
        </div>
        <div className="flex-end">
          <button className="button-secondary">Quay lại kế hoạch</button>
          <button className="button-primary">Lưu cập nhật</button>
        </div>
      </div>
    </div>
  );
}
