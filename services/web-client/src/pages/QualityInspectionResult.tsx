const criteria = [
  { code: 'TC021', name: 'Kiểm tra ngoại hình', score: 85, level: 'Cao', note: 'Không thấy trầy xước', evidence: 'Biên bản kiểm tra', result: 'Đạt' },
  { code: 'TC028', name: 'Kiểm tra tính năng', score: 74, level: 'Trung bình', note: '1 điểm hơi cứng', evidence: 'Video kiểm tra', result: 'Đạt' },
  { code: 'TC083', name: 'Kiểm tra đóng gói', score: 68, level: 'Trung bình', note: 'Cần bổ sung xốp đỡ', evidence: 'Ảnh chụp', result: 'Không đạt' }
];

export default function QualityInspectionResult() {
  return (
    <div className="page">
      <div className="section-card">
        <div className="section-title">Thông tin chung</div>
        <div className="form-grid">
          <div className="form-field">
            <label>Mã lô hàng</label>
            <input value="L0-2025-10-05" readOnly />
          </div>
          <div className="form-field">
            <label>Xưởng</label>
            <input value="Xưởng 3 - Cố định phím" readOnly />
          </div>
          <div className="form-field">
            <label>Sản phẩm</label>
            <input value="Bàn phím cơ A" readOnly />
          </div>
          <div className="form-field">
            <label>Ngày kiểm tra</label>
            <input value="30/10/2025" readOnly />
          </div>
        </div>
      </div>

      <div className="section-card">
        <div className="flex-between">
          <div className="section-title">Tiêu chí kiểm tra</div>
          <button className="tag-button">Thêm tiêu chí</button>
        </div>
        <div className="table-wrapper">
          <table className="table">
            <thead>
              <tr>
                <th>Tiêu chí</th>
                <th>Điểm số</th>
                <th>Mức độ</th>
                <th>Kết quả</th>
                <th>Minh chứng</th>
                <th>Ghi chú</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              {criteria.map((item) => (
                <tr key={item.code}>
                  <td>
                    <div style={{ fontWeight: 600 }}>{item.code}</div>
                    <div style={{ color: '#64748b', fontSize: 13 }}>{item.name}</div>
                  </td>
                  <td>{item.score}</td>
                  <td>
                    <span className={`badge ${badgeClass(item.level)}`}>{item.level}</span>
                  </td>
                  <td>
                    <span className={`badge ${item.result === 'Đạt' ? 'badge--success' : 'badge--danger'}`}>
                      {item.result}
                    </span>
                  </td>
                  <td>{item.evidence}</td>
                  <td style={{ maxWidth: 220 }}>{item.note}</td>
                  <td>
                    <div className="table-actions">
                      <button className="tag-button">Sửa</button>
                      <button className="button-danger">Xóa</button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      <div className="section-card">
        <div className="flex-between">
          <div className="section-title">Kết luận</div>
          <div style={{ fontWeight: 600, color: '#22c55e' }}>Tỉ lệ đạt 90%</div>
        </div>
        <textarea
          rows={3}
          value="Lô hàng đạt yêu cầu kiểm tra chất lượng. Đề nghị xuất kho trong ngày và nhắc bộ phận đóng gói bổ sung xốp."
          readOnly
        />
        <div className="progress-bar">
          <div className="progress-bar__value" style={{ width: '90%' }} />
        </div>
        <div className="flex-end">
          <button className="button-secondary">Quay lại</button>
          <button className="button-primary">Lưu biên bản</button>
        </div>
      </div>
    </div>
  );
}

function badgeClass(level: string) {
  switch (level) {
    case 'Cao':
      return 'badge--success';
    case 'Trung bình':
      return 'badge--warning';
    default:
      return 'badge--info';
  }
}
