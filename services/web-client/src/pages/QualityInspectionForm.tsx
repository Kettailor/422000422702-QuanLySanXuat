const criteria = [
  {
    code: 'DK01',
    name: 'Số vết khuyết tật trên vỏ',
    level: 'Trung bình',
    result: 'Đạt',
    note: 'Không phát hiện lỗi xước',
    evidence: 'Biên bản QA 10/10'
  },
  {
    code: 'DK02',
    name: 'Độ chính xác kích thước',
    level: 'Cao',
    result: 'Đạt',
    note: 'Kiểm tra bằng thước laser',
    evidence: 'File đo 10-10'
  },
  {
    code: 'DK03',
    name: 'Độ bền chịu lực',
    level: 'Thấp',
    result: 'Không đạt',
    note: 'Lô 3 bị nứt cạnh trái',
    evidence: 'Ảnh minh chứng'
  }
];

export default function QualityInspectionForm() {
  return (
    <div className="page">
      <div className="section-card">
        <div className="section-title">Thông tin kiểm tra</div>
        <div className="form-grid">
          <div className="form-field">
            <label>Mã kiểm tra</label>
            <input value="KTXB/2025-10-30" readOnly />
          </div>
          <div className="form-field">
            <label>Xưởng</label>
            <input value="Xưởng 3 - Gia công CNC" readOnly />
          </div>
          <div className="form-field">
            <label>Ngày kiểm tra</label>
            <input value="30/10/2025" readOnly />
          </div>
          <div className="form-field">
            <label>Nhân sự phụ trách</label>
            <input value="Lê Hoàng My" readOnly />
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
                <th>Mức độ</th>
                <th>Kết quả</th>
                <th>Ghi chú</th>
                <th>Minh chứng</th>
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
                  <td>
                    <span className={`badge ${badgeClass(item.level)}`}>
                      {item.level}
                    </span>
                  </td>
                  <td>
                    <span className={`badge ${item.result === 'Đạt' ? 'badge--success' : 'badge--danger'}`}>
                      {item.result}
                    </span>
                  </td>
                  <td style={{ maxWidth: 240 }}>{item.note}</td>
                  <td>{item.evidence}</td>
                  <td>
                    <div className="table-actions">
                      <button className="tag-button">Chọn</button>
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
        <div className="section-title">Kết luận</div>
        <textarea
          rows={3}
          value="Sản phẩm đạt mức chất lượng trung bình. Đề xuất tăng cường kiểm tra độ bền với lô 3."
          readOnly
        />
        <div className="flex-end">
          <button className="button-secondary">Hủy bỏ</button>
          <button className="button-primary">Lưu biên bản kiểm tra</button>
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
