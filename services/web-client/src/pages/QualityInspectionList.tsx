const summaryCards = [
  { label: 'Lô chờ kiểm tra', value: '12', badge: 'badge--info' },
  { label: 'Đang kiểm tra', value: '5', badge: 'badge--warning' },
  { label: 'Đạt kiểm tra', value: '4', badge: 'badge--success' },
  { label: 'Sản phẩm lỗi', value: '3', badge: 'badge--danger' }
];

const lots = [
  { code: 'L0-2025-1005', product: 'Bàn phím cơ A', status: 'Chờ kiểm tra', statusBadge: 'badge--info', workshop: 'Xưởng 3' },
  { code: 'L0-2025-1006', product: 'Switch Blue', status: 'Đang kiểm tra', statusBadge: 'badge--warning', workshop: 'Xưởng 2' },
  { code: 'L0-2025-1007', product: 'Keycap PBT', status: 'Chưa kiểm tra', statusBadge: 'badge--info', workshop: 'Xưởng 1' },
  { code: 'L0-2025-1008', product: 'Case nhôm', status: 'Chờ kiểm tra', statusBadge: 'badge--info', workshop: 'Xưởng 3' },
  { code: 'L0-2025-1009', product: 'PCB led RGB', status: 'Không đạt', statusBadge: 'badge--danger', workshop: 'Xưởng 4' }
];

export default function QualityInspectionList() {
  return (
    <div className="page">
      <div className="section-card">
        <div className="flex-between">
          <div>
            <div className="section-title">Kiểm tra chất lượng sản phẩm</div>
            <div style={{ color: '#64748b', fontSize: 13 }}>Theo dõi trạng thái lô hàng đang xử lý</div>
          </div>
          <div className="search-input" style={{ minWidth: 320 }}>
            <span role="img" aria-hidden="true">
              🔎
            </span>
            <input placeholder="Tìm kiếm mã lô hoặc sản phẩm" />
          </div>
        </div>
      </div>

      <div className="grid-4">
        {summaryCards.map((card) => (
          <div key={card.label} className="section-card" style={{ padding: 20 }}>
            <div style={{ color: '#64748b', fontSize: 13 }}>{card.label}</div>
            <div style={{ fontSize: 28, fontWeight: 700 }}>{card.value}</div>
            <span className={`badge ${card.badge}`}>Cập nhật mới</span>
          </div>
        ))}
      </div>

      <div className="section-card">
        <div className="flex-between">
          <div className="section-title">Danh sách lô sản phẩm cần kiểm tra</div>
          <div className="tabs">
            <button className="tab active">Tất cả</button>
            <button className="tab">Đang kiểm tra</button>
            <button className="tab">Đã kiểm tra</button>
          </div>
        </div>
        <div className="table-wrapper">
          <table className="table">
            <thead>
              <tr>
                <th>Mã lô</th>
                <th>Sản phẩm</th>
                <th>Trạng thái</th>
                <th>Xưởng</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              {lots.map((lot) => (
                <tr key={lot.code}>
                  <td>{lot.code}</td>
                  <td>{lot.product}</td>
                  <td>
                    <span className={`badge ${lot.statusBadge}`}>{lot.status}</span>
                  </td>
                  <td>{lot.workshop}</td>
                  <td>
                    <div className="table-actions">
                      <button className="tag-button">Xem</button>
                      <button className="button-primary">Tạo biên bản</button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
