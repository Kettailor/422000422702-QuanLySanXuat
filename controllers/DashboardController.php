<?php

class DashboardController extends Controller
{
    public function index(): void
    {
        $orderModel = new Order();
        $employeeModel = new Employee();
        $planModel = new ProductionPlan();
        $activityModel = new SystemActivity();
        $qualityModel = new QualityReport();
        $salaryModel = new Salary();
        $workshopModel = new Workshop();

        $orders = $orderModel->getOrdersWithCustomer(5);
        $employees = $employeeModel->getActiveEmployees();
        $plans = $planModel->getPlansWithOrders(5);
        $activities = $activityModel->latest(6);
        $qualitySummary = $qualityModel->getQualitySummary();
        $monthlyRevenue = $orderModel->getMonthlyRevenue();
        $payrollTrend = $salaryModel->getMonthlyPayoutTrend();
        $orderStats = $orderModel->getOrderStatistics();
        $payrollSummary = $salaryModel->getPayrollSummary();
        $workshopSummary = $workshopModel->getCapacitySummary();
        $pendingPayrolls = $salaryModel->getPendingPayrolls();

        $stats = [
            'totalWorkingDays' => 22,
            'participationRate' => count($employees),
            'completedPlans' => array_reduce($plans, fn($carry, $plan) => $carry + ($plan['TrangThai'] === 'Hoàn thành' ? 1 : 0), 0),
            'newNotifications' => count($activities),
        ];

        $this->render('dashboard/index', [
            'title' => 'Tổng quan hệ thống',
            'orders' => $orders,
            'employees' => $employees,
            'plans' => $plans,
            'activities' => $activities,
            'qualitySummary' => $qualitySummary,
            'stats' => $stats,
            'monthlyRevenue' => $monthlyRevenue,
            'payrollTrend' => $payrollTrend,
            'orderStats' => $orderStats,
            'payrollSummary' => $payrollSummary,
            'workshopSummary' => $workshopSummary,
            'pendingPayrolls' => $pendingPayrolls,
        ]);
    }
}
