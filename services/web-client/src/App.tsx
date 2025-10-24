import { Navigate, Outlet, Route, Routes } from 'react-router-dom';
import Sidebar from './components/Sidebar';
import Header from './components/Header';
import QualityInspectionForm from './pages/QualityInspectionForm';
import QualityInspectionResult from './pages/QualityInspectionResult';
import QualityInspectionList from './pages/QualityInspectionList';
import DashboardOverview from './pages/DashboardOverview';
import DashboardHR from './pages/DashboardHR';
import DashboardProduction from './pages/DashboardProduction';
import DashboardSales from './pages/DashboardSales';
import DashboardInventory from './pages/DashboardInventory';
import PlanDetail from './pages/PlanDetail';
import PlanMaterials from './pages/PlanMaterials';
import PlanAssignment from './pages/PlanAssignment';
import Login from './pages/Login';
import { useAuth } from './context/AuthContext';

const ProtectedLayout = () => {
  const { token, initializing } = useAuth();

  if (initializing) {
    return (
      <div className="page-loading">
        <div className="page-loading__spinner" aria-hidden />
        <p>Đang chuẩn bị dữ liệu...</p>
      </div>
    );
  }

  if (!token) {
    return <Navigate to="/login" replace />;
  }

  return (
    <div className="app-shell">
      <Sidebar />
      <main className="app-main">
        <Header />
        <Outlet />
      </main>
    </div>
  );
};

export default function App() {
  const { token } = useAuth();

  return (
    <Routes>
      <Route path="/login" element={<Login />} />
      <Route element={<ProtectedLayout />}>
        <Route path="/" element={<Navigate to="/dashboard/overview" replace />} />
        <Route path="/quality/form" element={<QualityInspectionForm />} />
        <Route path="/quality/result" element={<QualityInspectionResult />} />
        <Route path="/quality/list" element={<QualityInspectionList />} />
        <Route path="/dashboard/overview" element={<DashboardOverview />} />
        <Route path="/dashboard/hr" element={<DashboardHR />} />
        <Route path="/dashboard/production" element={<DashboardProduction />} />
        <Route path="/dashboard/sales" element={<DashboardSales />} />
        <Route path="/dashboard/inventory" element={<DashboardInventory />} />
        <Route path="/plan/detail" element={<PlanDetail />} />
        <Route path="/plan/materials" element={<PlanMaterials />} />
        <Route path="/plan/assignment" element={<PlanAssignment />} />
      </Route>
      <Route path="*" element={<Navigate to={token ? '/dashboard/overview' : '/login'} replace />} />
    </Routes>
  );
}
