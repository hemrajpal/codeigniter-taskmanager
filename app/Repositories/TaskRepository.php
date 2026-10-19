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

    // Every task (admin view)
    public function listAll(?string $status = null): array
    {
        $query = $this->tasks->withAssignees();

        if ($status !== null) {
            $query->where('tasks.status', $status);
        }

        return $query->orderBy('tasks.due_date', 'ASC')->findAll();
    }

    // Only the tasks assigned to this user (member view).
    public function listAssignedTo(int $userId, ?string $status = null): array
    {
        $query = $this->tasks->assignedTo($userId)->withAssignees();

        if ($status !== null) {
            $query->where('tasks.status', $status);
        }

        return $query->orderBy('tasks.due_date', 'ASC')->findAll();
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
}