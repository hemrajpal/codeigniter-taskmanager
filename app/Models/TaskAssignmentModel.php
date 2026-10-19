<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskAssignmentModel extends Model
{
    protected $table            = 'task_assignments';
    protected $primaryKey       = 'task_id';  
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = ['task_id', 'user_id'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function syncForTask(int $taskId, array $userIds): void
    {
        $userIds = array_values(array_unique(array_map('intval', $userIds)));

        $this->where('task_id', $taskId)->delete();

        if ($userIds === []) {
            return;
        }

        $this->insertBatch(array_map(
            static fn (int $uid) => ['task_id' => $taskId, 'user_id' => $uid],
            $userIds
        ));
    }

    public function userIdsForTask(int $taskId): array
    {
        $rows = $this->select('user_id')->where('task_id', $taskId)->findAll();

        return array_map('intval', array_column($rows, 'user_id'));
    }
}
