const stats = [
  { label: 'Giá trị tồn kho', value: '2,850,000,000đ', accent: '#1d4ed8', description: 'Giảm 3% so với tháng trước' },
  { label: 'Số loại vật tư', value: '5 loại', accent: '#f97316', description: '2 vật tư mới' },
  { label: 'Giá trị đặt hàng', value: '620,000,000đ', accent: '#22c55e', description: 'Chờ nhận 15 ngày' },
  { label: 'Tỉ lệ luân chuyển', value: '85%', accent: '#38bdf8', description: 'Tăng 6% trong quý' }
];

const alerts = [
  'NL01 - Nhựa PC A1 còn 12 ngày sử dụng',
  'NL05 - Keo kết dính sắp hết hạn 15-12-2025',
  'Xưởng 2 yêu cầu bổ sung bao bì carton'
];

export default function DashboardInventory() {
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
        <div className="section-title">Xu hướng tồn kho 3 tháng gần nhất (Theo số lượng)</div>
        <div className="placeholder-chart">[Placeholder] Biểu đồ & Dự báo Tồn kho</div>
      </div>

      <div className="grid-2">
        <div className="section-card">
          <div className="section-title">Thông báo tồn kho</div>
          <ul style={{ margin: 0, paddingLeft: 18, color: '#475569', fontSize: 13 }}>
            {alerts.map((item) => (
              <li key={item} style={{ marginBottom: 8 }}>{item}</li>
            ))}
          </ul>
        </div>
        <div className="section-card">
          <div className="section-title">Báo cáo tồn kho quan trọng</div>
          <div className="chip-list">
            <span className="chip">Tồn kho an toàn: 75%</span>
            <span className="chip">Đơn mua đang vận chuyển: 8</span>
            <span className="chip">Số lô cần kiểm tra: 4</span>
          </div>
        </div>
      </div>
    </div>
  );
}
