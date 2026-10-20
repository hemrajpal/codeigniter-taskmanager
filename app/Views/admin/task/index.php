<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('title') ?>Manage Tasks<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$badge = ['pending' => 'secondary', 'in_progress' => 'warning', 'completed' => 'success'];
$label = static fn (string $s) => ucwords(str_replace('_', ' ', $s));
$today = date('Y-m-d');
$total = $pager->getTotal();

// Filter links keep the current search
$filterUrl = static function (?string $status) use ($search): string {
    $query = http_build_query(array_filter(['status' => $status, 'search' => $search]));

    return site_url('admin/tasks') . ($query !== '' ? '?' . $query : '');
};
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1"><i class="bi bi-list-check me-2"></i>Manage Tasks</h1>
        <p class="text-secondary mb-0">Create tasks and assign them to members.</p>
    </div>
    <a href="<?= site_url('admin/tasks/create') ?>" class="btn btn-success">
        <i class="bi bi-plus-lg me-1"></i>New task
    </a>
</div>

<div class="card border-0 shadow-sm">

    <!-- Toolbar -->
    <div class="card-header bg-white border-bottom p-3">
        <form action="<?= site_url('admin/tasks') ?>" method="get" class="row g-2 align-items-center">

            <?php if ($current !== null): ?>
                <input type="hidden" name="status" value="<?= esc($current) ?>">
            <?php endif ?>

            <div class="col-12 col-md-7 col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-secondary"></i>
                    </span>
                    <input type="search" name="search"
                           class="form-control border-start-0"
                           placeholder="Search by title or description"
                           aria-label="Search tasks"
                           value="<?= esc($search) ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>

        <!-- Status filter -->
        <ul class="nav nav-pills mt-3">
            <li class="nav-item">
                <a class="nav-link py-1 <?= $current === null ? 'active' : '' ?>"
                   href="<?= esc($filterUrl(null)) ?>">All</a>
            </li>
            <?php foreach ($statuses as $s): ?>
                <li class="nav-item">
                    <a class="nav-link py-1 <?= $current === $s ? 'active' : '' ?>"
                       href="<?= esc($filterUrl($s)) ?>">
                        <?= esc($label($s)) ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-3 py-3">Title</th>
                    <th>Assigned to</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tasks)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <i class="bi bi-list-check fs-2 d-block mb-2"></i>
                            No tasks found.
                        </td>
                    </tr>
                <?php endif ?>

                <?php foreach ($tasks as $task): ?>
                    <?php
                    $due     = $task->due_date ? substr($task->due_date, 0, 10) : null;
                    $overdue = $due && $due < $today && $task->status !== 'completed';
                    ?>
                    <tr>
                        <td class="px-3">
                            <div class="fw-semibold"><?= esc($task->title) ?></div>
                            <?php if ($task->description): ?>
                                <small class="text-secondary"><?= esc(mb_strimwidth($task->description, 0, 70, '…')) ?></small>
                            <?php endif ?>
                        </td>
                        <td><?= esc($task->assignee_names ?? 'Unassigned') ?></td>
                        <td class="<?= $overdue ? 'text-danger fw-semibold' : '' ?>">
                            <?= $due ? esc(date('d M Y', strtotime($due))) : '-' ?>
                            <?= $overdue ? '<small>(overdue)</small>' : '' ?>
                        </td>
                        <td>
                            <span class="badge text-bg-<?= $badge[$task->status] ?? 'secondary' ?>">
                                <?= esc($label($task->status)) ?>
                            </span>
                        </td>
                        <td class="text-end pe-3">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= site_url('admin/tasks/' . $task->id . '/edit') ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>

                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteTaskModal"
                                        data-task-id="<?= esc($task->id) ?>"
                                        data-task-title="<?= esc($task->title) ?>">
                                    <i class="bi bi-trash me-1"></i>Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <?php if (! empty($tasks)): ?>
        <?= $this->include('partials/pagination') ?>
    <?php endif ?>
</div>

<!-- Delete Task Modal -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1"
     aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <form id="deleteTaskForm" method="post">
                <?= csrf_field() ?>

                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteTaskModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>Delete Task
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    Are you sure you want to delete <strong id="deleteTaskTitle"></strong>?
                    <p class="text-secondary small mt-2 mb-0">This action cannot be undone.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Yes, Delete
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#deleteTaskModal').on('show.bs.modal', function (event) {
            const $btn = $(event.relatedTarget);

            $('#deleteTaskTitle').text($btn.attr('data-task-title'));
            $('#deleteTaskForm').attr(
                'action',
                '<?= site_url('admin/tasks') ?>/' + $btn.attr('data-task-id') + '/delete'
            );
        });
    });
</script>
<?= $this->endSection() ?>