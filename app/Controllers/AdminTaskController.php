<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Repositories\TaskRepository;
use CodeIgniter\HTTP\RedirectResponse;

class AdminTaskController extends BaseController
{
    private const FIELDS   = ['title', 'description', 'status', 'due_date'];
    private const STATUSES = ['pending', 'in_progress', 'completed'];

    private TaskRepository $tasks;

    public function __construct()
    {
        $this->tasks = new TaskRepository();
    }

    public function index(): string
    {
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        
        $status = in_array($status, self::STATUSES, true) ? $status : null;

        return view('admin/task/index', [
            'tasks'    => $this->tasks->listAll($status, $search !== '' ? $search : null),
            'statuses' => self::STATUSES,
            'current'  => $status,
            'pager'    => $this->tasks->getPager(),
            'search'   => $search,
        ]);
    }

    public function create(): string
    {
        return view('admin/task/form', $this->formData(null));
    }

    public function store(): RedirectResponse
    {
        $data = $this->postedData();
        $data['created_by'] = (int) session()->get('user_id');

        $id = $this->tasks->create($data, (array) $this->request->getPost('assignees'));

        if ($id === false) {
            return $this->backWithErrors($this->tasks->errors() ?: ['error' => 'Could not save the task.']);
        }

        return redirect()->to('/admin/tasks')->with('success', 'Task created.');
    }

    public function edit(int $id): string|RedirectResponse
    {
        $task = $this->tasks->find($id);

        return $task === null ? $this->notFound() : view('admin/task/form', $this->formData($task));
    }

    public function update(int $id): RedirectResponse
    {
        if ($this->tasks->find($id) === null) {
            return $this->notFound();
        }

        $ok = $this->tasks->update($id, $this->postedData(), (array) $this->request->getPost('assignees'));

        if (! $ok) {
            return $this->backWithErrors($this->tasks->errors() ?: ['error' => 'Could not update the task.']);
        }

        return redirect()->to('/admin/tasks')->with('success', 'Task updated.');
    }

    public function delete(int $id): RedirectResponse
    {
        if ($this->tasks->find($id) === null) {
            return $this->notFound();
        }

        $this->tasks->delete($id);

        return redirect()->to('/admin/tasks')->with('success', 'Task deleted.');
    }

    private function postedData(): array
    {
        $data = $this->request->getPost(self::FIELDS);

        // An empty date input sends '', which a DATE column rejects
        $data['due_date'] = ($data['due_date'] ?? '') !== '' ? $data['due_date'] : null;

        return $data;
    }

    private function formData(?object $task): array
    {
        return [
            'task'     => $task,
            'members'  => model(UserModel::class)->where('status', 'active')->findAll(),
            'assigned' => $task ? $this->tasks->assigneeIds((int) $task->id) : [],
            'statuses' => self::STATUSES,
        ];
    }

    private function backWithErrors(array $errors): RedirectResponse
    {
        return redirect()->back()->withInput()->with('errors', $errors);
    }

    private function notFound(): RedirectResponse
    {
        return redirect()->to('/admin/tasks')->with('error', 'Task not found.');
    }
}