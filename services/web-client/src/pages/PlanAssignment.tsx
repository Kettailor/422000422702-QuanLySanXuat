const products = [
  { name: 'Bàn phím cơ A', quantity: '20,000', delivered: '10/2025', deadline: '20/10/2025' }
];

const workshops = [
  { code: 'Xưởng 1', component: 'Khung nhôm', quantity: '10,000', start: '05/10/2025', end: '16/10/2025' },
  { code: 'Xưởng 2', component: 'Switch Blue', quantity: '20,000', start: '07/10/2025', end: '18/10/2025' },
  { code: 'Xưởng 3', component: 'Keycap PBT', quantity: '20,000', start: '10/10/2025', end: '22/10/2025' }
];

export default function PlanAssignment() {
  return (
    <div className="page">
      <div className="section-card">
        <div className="section-title">Thông tin kế hoạch</div>
        <div className="form-grid">
          <div className="form-field">
            <label>Mã kế hoạch</label>
            <input value="KH-2025-0823-1003" readOnly />
          </div>
          <div className="form-field">
            <label>Trạng thái</label>
            <input value="Đang triển khai" readOnly />
          </div>
          <div className="form-field">
            <label>Mốc hoàn thành</label>
            <input value="Cập nhật mới nhất 20/10/2025" readOnly />
          </div>
        </div>
      </div>

      <div className="section-card">
        <div className="section-title">Sản phẩm trong kế hoạch</div>
        <div className="table-wrapper">
          <table className="table">
            <thead>
              <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
              </tr>
            </thead>
            <tbody>
              {products.map((product) => (
                <tr key={product.name}>
                  <td>{product.name}</td>
                  <td>{product.quantity}</td>
                  <td>{product.delivered}</td>
                  <td>{product.deadline}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      <div className="section-card">
        <div className="flex-between">
          <div className="section-title">Phân bổ cho các xưởng</div>
          <button className="tag-button">Thêm xưởng</button>
        </div>
        <div className="table-wrapper">
          <table className="table">
            <thead>
              <tr>
                <th>Xưởng</th>
                <th>Thành phần</th>
                <th>Số lượng</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              {workshops.map((workshop) => (
                <tr key={workshop.code}>
                  <td>{workshop.code}</td>
                  <td>{workshop.component}</td>
                  <td>{workshop.quantity}</td>
                  <td>{workshop.start}</td>
                  <td>{workshop.end}</td>
                  <td>
                    <button className="button-danger">Xóa</button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        <div className="flex-end">
          <button className="button-secondary">Hủy bỏ</button>
          <button className="button-primary">Lưu thay đổi</button>
        </div>
      </div>
    </div>
  );
}
