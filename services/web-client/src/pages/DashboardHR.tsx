const stats = [
  { label: 'Tổng nhân viên', value: '128', accent: '#2563eb' },
  { label: 'Tỉ lệ đạt KPI', value: '85%', accent: '#10b981', description: 'Tăng 12% so với Q3' },
  { label: 'Tỉ lệ nghỉ việc', value: '1.5%', accent: '#f97316', description: 'Giảm còn 2 người' },
  { label: 'Lương trung bình', value: '12.5 Triệu', accent: '#6366f1', description: 'Tăng 0.5 Triệu' }
];

const notices = [
  { date: '30-11', title: 'Thông báo tuyển dụng & đào tạo', items: ['30/11: Onboarding nhân viên mới', '02/12: Đào tạo QC lô tháng 12', '05/12: Workshop Văn hóa doanh nghiệp'] },
  { date: '05-12', title: 'Nhắc nhở KPI tháng 12', items: ['Cập nhật mục tiêu cá nhân', 'Hoàn thành đánh giá 360 độ trước 10/12'] }
];

export default function DashboardHR() {
  return (
    <div className="page">
      <div className="grid-4">
        {stats.map((stat) => (
          <div key={stat.label} className="section-card" style={{ borderLeft: `6px solid ${stat.accent}` }}>
            <div style={{ color: '#94a3b8', fontWeight: 600, fontSize: 13 }}>{stat.label}</div>
            <div style={{ fontSize: 28, fontWeight: 700 }}>{stat.value}</div>
            {stat.description && (
              <div style={{ color: '#0f766e', fontSize: 12, fontWeight: 600 }}>{stat.description}</div>
            )}
          </div>
        ))}
      </div>

      <div className="section-card">
        <div className="section-title">Biểu đồ phân bổ điểm hiệu suất (Rating theo phòng ban)</div>
        <div className="placeholder-chart">[Placeholder] Biểu đồ Cột Điểm Hiệu suất theo Phòng ban</div>
      </div>

      <div className="grid-2">
        <div className="section-card">
          <div className="section-title">Thông báo & Sự kiện</div>
          {notices.map((notice) => (
            <div key={notice.date} className="card-highlight" style={{ background: '#f8fafc' }}>
              <div className="card-highlight__label">{notice.title}</div>
              <div className="card-highlight__value" style={{ fontSize: 16 }}>{notice.date}</div>
              <ul style={{ margin: 0, paddingLeft: 18, color: '#475569', fontSize: 13 }}>
                {notice.items.map((item) => (
                  <li key={item}>{item}</li>
                ))}
              </ul>
            </div>
          ))}
        </div>
        <div className="section-card">
          <div className="section-title">Chỉ số nhân sự nổi bật</div>
          <div className="chip-list">
            <span className="chip">Tỉ lệ đi làm đúng giờ: 92%</span>
            <span className="chip">Tỉ lệ hoàn thành đào tạo: 88%</span>
            <span className="chip">Sáng kiến cải tiến: 12 ý tưởng</span>
          </div>
        </div>
      </div>
    </div>
  );
}
