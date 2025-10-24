import { NavLink } from 'react-router-dom';

const groups = [
  {
    title: 'Chất lượng',
    links: [
      { to: '/quality/form', label: 'Phiếu kiểm tra' },
      { to: '/quality/result', label: 'Biên bản kết quả' },
      { to: '/quality/list', label: 'Danh sách lô' }
    ]
  },
  {
    title: 'Dashboard',
    links: [
      { to: '/dashboard/overview', label: 'Tổng quan' },
      { to: '/dashboard/hr', label: 'Nhân sự & hiệu suất' },
      { to: '/dashboard/production', label: 'Sản xuất & chất lượng' },
      { to: '/dashboard/sales', label: 'Đơn hàng & bán hàng' },
      { to: '/dashboard/inventory', label: 'Nguyên vật liệu & kho' }
    ]
  },
  {
    title: 'Kế hoạch',
    links: [
      { to: '/plan/detail', label: 'Chi tiết kế hoạch' },
      { to: '/plan/materials', label: 'Nguyên liệu sản xuất' },
      { to: '/plan/assignment', label: 'Phân bổ chuyền' }
    ]
  }
];

export default function Sidebar() {
  return (
    <aside className="sidebar">
      <div className="sidebar__brand">
        <span role="img" aria-label="logo">
          🏭
        </span>
        <span>Sinh Viên 5 Tốt ERP</span>
      </div>
      <nav className="sidebar__nav">
        {groups.map((group) => (
          <div key={group.title} className="nav-group">
            <div className="nav-group__title">{group.title}</div>
            {group.links.map((link) => (
              <NavLink
                key={link.to}
                to={link.to}
                className={({ isActive }) =>
                  `nav-link${isActive ? ' active' : ''}`
                }
              >
                {link.label}
              </NavLink>
            ))}
          </div>
        ))}
      </nav>
    </aside>
  );
}
