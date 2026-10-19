<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class TaskModel extends Model
{
    protected $table            = 'tasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'description',
        'created_by',
        'status',
        'due_date',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'title'       => 'required|min_length[3]|max_length[150]',
        'description' => 'permit_empty|max_length[2000]',
        'created_by'  => 'required|is_natural_no_zero|is_not_unique[users.id]',
        'status'      => 'required|in_list[pending,in_progress,completed]',
        'due_date'    => 'permit_empty|valid_date[Y-m-d]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /** Tasks assigned to the given user. */
    public function assignedTo(int $userId): static
    {
        return $this->whereIn('tasks.id', static fn (BaseBuilder $b) => $b
            ->select('task_id')
            ->from('task_assignments')
            ->where('user_id', $userId));
    }

    /** Adds assignee_names (comma-separated) to each task row. */
    public function withAssignees(): static
    {
        return $this->select('tasks.*, GROUP_CONCAT(users.name ORDER BY users.name SEPARATOR ", ") AS assignee_names')
                    ->join('task_assignments ta', 'ta.task_id = tasks.id', 'left')
                    ->join('users', 'users.id = ta.user_id', 'left')
                    ->groupBy('tasks.id');
    }
}
