<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $userModel = model(UserModel::class);

        $total   = $userModel->countAllResults();
        $pending = $userModel->where('status', 'pending')->countAllResults();

        return view('admin/dashboard', [
            'totalUsers'   => $total,
            'pendingUsers' => $pending,
        ]);
    }
}
