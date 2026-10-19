<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Repositories\TaskRepository;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $userModel = model(UserModel::class);
        $taskRepository = new TaskRepository();

        $totalUsers = $userModel->countAll();
        $pendingUsers = $userModel->where('status', 'pending')->countAllResults();
        $approvedUsers = $userModel->where('status', 'approved')->countAllResults();

        $taskStats = $taskRepository->getTaskStats() ?? [
            'total'      => 0,
            'pending'    => 0,
            'in_progress'=> 0,
            'completed'  => 0,
            'overdue'    => 0,
        ];

        return view('admin/dashboard', [
            'totalUsers'   => $totalUsers,
            'pendingUsers' => $pendingUsers,
            'approvedUsers' => $approvedUsers,
            'taskStats'    => $taskStats,
        ]);
    }
}
