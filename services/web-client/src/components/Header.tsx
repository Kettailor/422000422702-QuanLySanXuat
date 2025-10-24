import { useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

const titleMap: Record<string, string> = {
  '/quality/form': 'Chất Lượng',
  '/quality/result': 'Chất Lượng',
  '/quality/list': 'Chất Lượng',
  '/dashboard/overview': 'Tổng Quan',
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
  const navigate = useNavigate();
  const { user, logout } = useAuth();
  const sectionTitle = titleMap[location.pathname] ?? 'ERP';

  const initials = (user?.username ?? 'SV').substring(0, 2).toUpperCase();

  const handleLogout = async () => {
    await logout();
    navigate('/login', { replace: true });
  };

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
          <div className="avatar__circle">{initials}</div>
          <div>
            <div style={{ fontWeight: 600 }}>{user?.username ?? 'Người dùng'}</div>
            <div style={{ fontSize: 12, color: '#64748b' }}>{user?.role ?? 'Quản trị viên'}</div>
          </div>
        </div>
        <button className="button-secondary" onClick={handleLogout} type="button">
          Đăng xuất
        </button>
      </div>
    </header>
  );
}
