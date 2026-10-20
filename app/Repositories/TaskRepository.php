<?php

namespace App\Repositories;

use App\Models\TaskAssignmentModel;
use App\Models\TaskModel;
use CodeIgniter\Database\BaseConnection;

class TaskRepository
{
    private TaskModel $tasks;
    private TaskAssignmentModel $assignments;
    private BaseConnection $db;   // used only for transactions

    public function __construct()
    {
        $this->tasks       = model(TaskModel::class);
        $this->assignments = model(TaskAssignmentModel::class);
        $this->db          = db_connect();
    }

    // Validation errors from the last failed create/update.
    public function errors(): array
    {
        try {
            return $this->tasks->errors();
        } catch (\Throwable $e) {
            log_message('error', 'Could not read task errors: {message}', ['message' => $e->getMessage()]);

            return [];
        }
    }

    public function getPager()
    {
        return $this->tasks->pager;
    }

    // Every task (admin view)
    public function listAll(?string $status = null, ?string $search = null, int $perPage = 10): array
    {
        $query = $this->tasks->withAssignees();

        if ($status !== null) {
            $query->where('tasks.status', $status);
        }

        if ($search !== null) {
            $query->groupStart()
                    ->like('tasks.title', $search)
                    ->orLike('tasks.description', $search)
                    ->groupEnd();
        }

        return $query->orderBy('tasks.due_date', 'ASC')->paginate($perPage);
    }

    // Only the tasks assigned to this user (member view).
    public function listAssignedTo(int $userId, ?string $status = null, ?string $search = null, int $perPage = 10): array
    {
        $query = $this->tasks->assignedTo($userId)->withAssignees();

        if ($status !== null) {
            $query->where('tasks.status', $status);
        }

        if ($search !== null) {
            $query->groupStart()
                    ->like('tasks.title', $search)
                    ->orLike('tasks.description', $search)
                    ->groupEnd();
        }


        return $query->orderBy('tasks.id', 'DESC')->paginate($perPage);
    }

    public function find(int $id): ?object
    {
        return $this->tasks->withAssignees()->find($id);
    }

    public function assigneeIds(int $taskId): array
    {
        return $this->assignments->userIdsForTask($taskId);
    }

    // Creates the task and its assignees together. Returns the new ID, or false.
    public function create(array $data, array $assigneeIds = []): int|false
    {
        $this->db->transStart();

        $id = $this->tasks->insert($data);

        if ($id) {
            $this->assignments->syncForTask((int) $id, $assigneeIds);
        }

        $this->db->transComplete();

        return ($id && $this->db->transStatus()) ? (int) $id : false;
    }

    // Updates the task and replaces its assignees together.
    public function update(int $id, array $data, array $assigneeIds = []): bool
    {
        $this->db->transStart();

        $ok = $this->tasks->update($id, $data);

        if ($ok) {
            $this->assignments->syncForTask($id, $assigneeIds);
        }

        $this->db->transComplete();

        return $ok && $this->db->transStatus();
    }

    // Assignments are removed automatically by the ON DELETE CASCADE foreign key.
    public function delete(int $id): bool
    {
        return (bool) $this->tasks->delete($id);
    }

    public function updateAssignedStatus( int $taskId, int $userId, string $status ): bool 
    {
        $task = $this->tasks
            ->where('id', $taskId)
            ->join('task_assignments ta', 'ta.task_id = tasks.id')
            ->where('ta.user_id', $userId)
            ->first();

        if (! $task) {
            return false;
        }

        if ($task->status === $status) {
            return true;
        }

        return $this->tasks->update($taskId, ['status' => $status]);
    }

    public function getMemberStats(int $userId): array
    {
        $row = $this->tasks
            ->select("
                COUNT(DISTINCT tasks.id) AS total,
                COUNT(DISTINCT CASE WHEN tasks.status = 'pending' THEN tasks.id END) AS pending,
                COUNT(DISTINCT CASE WHEN tasks.status = 'in_progress' THEN tasks.id END) AS in_progress,
                COUNT(DISTINCT CASE WHEN tasks.status = 'completed' THEN tasks.id END) AS completed
            ")
            ->join('task_assignments ta', 'ta.task_id = tasks.id')
            ->where('ta.user_id', $userId)
            ->get()
            ->getRowArray();

        return $row ?? [
            'total' => 0,
            'pending' => 0,
            'in_progress' => 0,
            'completed' => 0,
        ];
    }

    public function getTaskStats(): array
    {
        return $this->tasks
            ->select("
                COUNT(*) AS total,
                SUM(status = 'pending') AS pending,
                SUM(status = 'in_progress') AS in_progress,
                SUM(status = 'completed') AS completed,
                SUM(
                    due_date < CURDATE()
                    AND status != 'completed'
                ) AS overdue
            ")
            ->get()
            ->getRowArray();
    }

    public function getUpcomingTasks(int $userId): array
    {
        return $this->tasks
            ->select('tasks.*')
            ->join('task_assignments ta', 'ta.task_id = tasks.id')
            ->where('ta.user_id', $userId)
            ->where('tasks.due_date >=', date('Y-m-d'))
            ->where('tasks.status !=', 'completed')
            ->orderBy('tasks.due_date', 'ASC')
            ->findAll(5);
    }
}