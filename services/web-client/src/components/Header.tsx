import { useLocation } from 'react-router-dom';

const titleMap: Record<string, string> = {
  '/quality/form': 'Chất Lượng',
  '/quality/result': 'Chất Lượng',
  '/quality/list': 'Chất Lượng',
  '/dashboard/hr': 'Dashboard',
  '/dashboard/production': 'Dashboard',
  '/dashboard/sales': 'Dashboard',
  '/dashboard/inventory': 'Dashboard',
  '/plan/detail': 'Kế Hoạch',
  '/plan/materials': 'Kế Hoạch',
  '/plan/assignment': 'Kế Hoạch'
};

export default function Header() {
  const location = useLocation();
  const sectionTitle = titleMap[location.pathname] ?? 'ERP';

  return (
    <header className="app-header">
      <div>
        <div className="app-header__title">{sectionTitle}</div>
        <div style={{ color: '#64748b', fontSize: 14 }}>Sinh Viên 5 Tốt ERP</div>
      </div>
      <div className="app-header__actions">
        <div className="search-input">
          <span role="img" aria-hidden="true">
            🔍
          </span>
          <input placeholder="Tìm kiếm nhanh" />
        </div>
        <div className="avatar">
          <div className="avatar__circle">TK</div>
          <div>
            <div style={{ fontWeight: 600 }}>Trần Lê Kiệt</div>
            <div style={{ fontSize: 12, color: '#64748b' }}>Quản trị viên</div>
          </div>
        </div>
      </div>
    </header>
  );
}
