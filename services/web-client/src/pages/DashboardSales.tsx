const stats = [
  { label: 'Tổng doanh thu', value: '1.5 Tỷ', accent: '#2563eb', description: 'Tăng 8% so với tháng trước' },
  { label: 'Tổng đơn hàng', value: '345', accent: '#f97316', description: '45 đơn hàng mới' },
  { label: 'Đơn hàng đang đóng', value: '42', accent: '#22c55e', description: 'Hoàn thành trong 3 ngày tới' },
  { label: 'Tỉ lệ thành công', value: '81%', accent: '#a855f7', description: 'Tăng 4% trong tuần' }
];

const topCustomers = [
  { name: 'Cửa hàng Linh Kiện ABC', value: '120 Triệu' },
  { name: 'Ecom Shop Xanh', value: '95 Triệu' },
  { name: 'Hợp tác xã Công nghệ', value: '82 Triệu' }
];

export default function DashboardSales() {
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
        <div className="section-title">Xu hướng doanh thu theo tuần (3 tháng gần nhất)</div>
        <div className="placeholder-chart">[Placeholder] Biểu đồ đường doanh thu theo tuần</div>
      </div>

      <div className="grid-2">
        <div className="section-card">
          <div className="section-title">Top đơn hàng quan trọng</div>
          <ul style={{ margin: 0, paddingLeft: 18, color: '#475569', fontSize: 13 }}>
            <li>HO-2025-189 - Switch Blue - 65 Triệu</li>
            <li>HO-2025-190 - Bàn phím cơ - 58 Triệu</li>
            <li>HO-2025-191 - Keycap PBT - 42 Triệu</li>
          </ul>
        </div>
        <div className="section-card">
          <div className="section-title">Top khách hàng</div>
          <div style={{ display: 'grid', gap: 12 }}>
            {topCustomers.map((customer) => (
              <div key={customer.name} className="card-highlight" style={{ background: '#fef9c3' }}>
                <div className="card-highlight__label">{customer.name}</div>
                <div className="card-highlight__value">{customer.value}</div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
