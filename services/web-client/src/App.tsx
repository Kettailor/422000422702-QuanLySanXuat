import { Navigate, Route, Routes } from 'react-router-dom';
import Sidebar from './components/Sidebar';
import Header from './components/Header';
import QualityInspectionForm from './pages/QualityInspectionForm';
import QualityInspectionResult from './pages/QualityInspectionResult';
import QualityInspectionList from './pages/QualityInspectionList';
import DashboardHR from './pages/DashboardHR';
import DashboardProduction from './pages/DashboardProduction';
import DashboardSales from './pages/DashboardSales';
import DashboardInventory from './pages/DashboardInventory';
import PlanDetail from './pages/PlanDetail';
import PlanMaterials from './pages/PlanMaterials';
import PlanAssignment from './pages/PlanAssignment';

export default function App() {
  return (
    <div className="app-shell">
      <Sidebar />
      <main className="app-main">
        <Header />
        <Routes>
          <Route path="/" element={<Navigate to="/quality/form" replace />} />
          <Route path="/quality/form" element={<QualityInspectionForm />} />
          <Route path="/quality/result" element={<QualityInspectionResult />} />
          <Route path="/quality/list" element={<QualityInspectionList />} />
          <Route path="/dashboard/hr" element={<DashboardHR />} />
          <Route path="/dashboard/production" element={<DashboardProduction />} />
          <Route path="/dashboard/sales" element={<DashboardSales />} />
          <Route path="/dashboard/inventory" element={<DashboardInventory />} />
          <Route path="/plan/detail" element={<PlanDetail />} />
          <Route path="/plan/materials" element={<PlanMaterials />} />
          <Route path="/plan/assignment" element={<PlanAssignment />} />
        </Routes>
      </main>
    </div>
  );
}
