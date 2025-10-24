const stats = [
  { label: 'Số lượng hoàn thành', value: '15,850', accent: '#1d4ed8', description: 'Hoàn thành 102% kế hoạch' },
  { label: 'Tỉ lệ lỗi (PQA)', value: '2.1%', accent: '#f97316', description: 'Giảm 0.6% so với tuần trước' },
  { label: 'Thời gian chờ', value: '4.5 giờ', accent: '#38bdf8', description: 'Giảm 1.2 giờ' },
  { label: 'Tỉ lệ giao hàng đúng hạn', value: '78%', accent: '#22c55e', description: 'Cần cải thiện tuần tới' }
];

const notifications = [
  'Xưởng 1 hoàn thành 3/4 đơn hàng',
  'Xưởng 3 cần bổ sung 500 switch Blue',
  'Xưởng 5 đang bảo trì máy CNC - dự kiến 3 giờ'
];

export default function DashboardProduction() {
  return (
    <div className="page">
      <div className="grid-4">
        {stats.map((stat) => (
          <div key={stat.label} className="section-card" style={{ borderLeft: `6px solid ${stat.accent}` }}>
            <div style={{ color: '#94a3b8', fontWeight: 600, fontSize: 13 }}>{stat.label}</div>
            <div style={{ fontSize: 28, fontWeight: 700 }}>{stat.value}</div>
            <div style={{ color: '#0f766e', fontSize: 12, fontWeight: 600 }}>{stat.description}</div>
          </div>
        ))}
      </div>

      <div className="section-card">
        <div className="section-title">Phân tích nguyên nhân lỗi (Top 5)</div>
        <div className="placeholder-chart">[Placeholder] Biểu đồ Pareto Nguyên nhân lỗi</div>
      </div>

      <div className="grid-2">
        <div className="section-card">
          <div className="section-title">Thông báo sản xuất</div>
          <ul style={{ margin: 0, paddingLeft: 18, color: '#475569', fontSize: 13 }}>
            {notifications.map((item) => (
              <li key={item} style={{ marginBottom: 8 }}>{item}</li>
            ))}
          </ul>
        </div>
        <div className="section-card">
          <div className="section-title">Báo cáo tiến độ đơn hàng</div>
          <div className="progress-bar" style={{ marginBottom: 16 }}>
            <div className="progress-bar__value" style={{ width: '75%' }} />
          </div>
          <div style={{ display: 'grid', gap: 12 }}>
            <div className="card-highlight">
              <div className="card-highlight__label">Đơn hàng ưu tiên</div>
              <div className="card-highlight__value">HO-2025-201</div>
              <div className="card-highlight__trend">Đạt 90% tiến độ</div>
            </div>
            <div className="card-highlight">
              <div className="card-highlight__label">Lô hàng trễ</div>
              <div className="card-highlight__value">L0-2025-199</div>
              <div className="card-highlight__trend" style={{ color: '#ef4444' }}>Chậm 1.5 ngày</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
