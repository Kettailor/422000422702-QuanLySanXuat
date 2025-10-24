<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\DashboardModel;

class DashboardController extends Controller
{
    private DashboardModel $model;

    public function __construct()
    {
        $this->model = new DashboardModel();
    }

    public function index(): void
    {
        $data = $this->model->getDashboardData();
        $this->view('dashboard/index', [
            'pageTitle' => 'Dashboard tổng quan',
            'data' => $data,
        ]);
    }
}
