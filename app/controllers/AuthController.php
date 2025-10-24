<?php

namespace App\Controllers;

use Core\Controller;

class AuthController extends Controller
{
    public function login(): void
    {
        $this->view('auth/login', [
            'pageTitle' => 'Đăng nhập hệ thống',
        ], 'layouts/auth');
    }
}
