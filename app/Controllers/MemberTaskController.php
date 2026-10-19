<?php

namespace App\Controllers;

use App\Repositories\TaskRepository;

class MemberTaskController extends BaseController
{
    private const STATUSES = ['pending', 'in_progress', 'completed'];

    private TaskRepository $tasks;

    public function __construct()
    {
        $this->tasks = new TaskRepository();
    }

    public function index(): string
    {
        $status = $this->request->getGet('status');
        $status = in_array($status, self::STATUSES, true) ? $status : null;

        return view('member/tasks/index', [
            'tasks'    => $this->tasks->listAssignedTo((int) session()->get('user_id'), $status),
            'statuses' => self::STATUSES,
            'current'  => $status,
        ]);
    }
}