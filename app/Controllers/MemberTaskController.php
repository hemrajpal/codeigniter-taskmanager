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

    public function dashboard()
    {
        $userId = (int) session()->get('user_id');

        return view('member/dashboard', [
            'userName' => session()->get('user_name'),
            'stats'    => $this->tasks->getMemberStats($userId),
            'upcomingTasks' => $this->tasks->getUpcomingTasks($userId, 5),
        ]);
    }

    public function index(): string
    {
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        $status = in_array($status, self::STATUSES, true) ? $status : null;

        return view('member/task/index', [
            'tasks'    => $this->tasks->listAssignedTo((int) session()->get('user_id'), $status, $search !== '' ? $search : null),
            'statuses' => self::STATUSES,
            'current'  => $status,
            'pager'    => $this->tasks->getPager(),
            'search'   => $search,
        ]);
    }

    public function updateStatus(int $taskId)
    {
        $userId = (int) session()->get('user_id');
        $status = $this->request->getPost('status');

        if (! in_array($status, self::STATUSES, true)) {
            return redirect()->back()
                ->with('error', 'Invalid task status.');
        }

        $updated = $this->tasks->updateAssignedStatus($taskId, $userId, $status);

        if (!$updated) {
            return redirect()->back()
                ->with('error', 'Task not found or not assigned to you.');
        }

        return redirect()->back()
            ->with('success', 'Task status updated successfully.');
    }
}