<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('title') ?>My Tasks<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$badge = ['pending' => 'secondary', 'in_progress' => 'warning', 'completed' => 'success'];
$label = static fn (string $s) => ucwords(str_replace('_', ' ', $s));
$today = date('Y-m-d');
$total = $pager->getTotal();

// Filter links keep the current search
$filterUrl = static function (?string $status) use ($search): string {
    $query = http_build_query(array_filter(['status' => $status, 'search' => $search]));

    return site_url('member/tasks') . ($query !== '' ? '?' . $query : '');
};
?>

<div class="mb-4">
    <h1 class="h4 mb-1"><i class="bi bi-list-check me-2"></i>My Tasks</h1>
    <p class="text-secondary mb-0">Tasks assigned to you. Update the status as you make progress.</p>
</div>

<div class="card border-0 shadow-sm">

    <!-- Toolbar -->
    <div class="card-header bg-white border-bottom p-3">
        <form action="<?= site_url('member/tasks') ?>" method="get" class="row g-2 align-items-center">

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
                    <th>Team</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tasks)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5 text-secondary">
                            <i class="bi bi-list-check fs-2 d-block mb-2"></i>
                            No tasks assigned to you.
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
                                <small class="text-secondary"><?= esc(mb_strimwidth($task->description, 0, 90, '…')) ?></small>
                            <?php endif ?>
                        </td>
                        <td><?= esc($task->assignee_names ?? '-') ?></td>
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
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#updateTaskStatusModal"
                                data-task-id="<?= esc($task->id) ?>"
                                data-task-title="<?= esc($task->title) ?>"
                                data-task-status="<?= esc($task->status) ?>">

                                <i class="bi bi-pencil-square me-1"></i> Update Status
                            </button>
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

<!-- Update Task Status Modal -->
<div class="modal fade" id="updateTaskStatusModal" tabindex="-1"
     aria-labelledby="updateTaskStatusModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="updateTaskStatusModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Update Task Status
                </h5>

                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="updateTaskStatusForm" method="post">
                <?= csrf_field() ?>

                <div class="modal-body">
                    <p class="text-secondary mb-3">
                        Task: <strong id="modalTaskTitle"></strong>
                    </p>

                    <label for="modalTaskStatus" class="form-label">
                        Status
                    </label>

                    <select name="status" id="modalTaskStatus"
                            class="form-select" required>
                        <?php foreach ($statuses as $s): ?>
                            <option value="<?= esc($s) ?>">
                                <?= esc($label($s)) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2 me-1"></i>Save Status
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
        $('#updateTaskStatusModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);

            const taskId = button.attr('data-task-id');
            const taskTitle = button.attr('data-task-title');
            const taskStatus = button.attr('data-task-status');

            $('#modalTaskTitle').text(taskTitle);
            $('#modalTaskStatus').val(taskStatus);

            $('#updateTaskStatusForm').attr(
                'action',
                '<?= site_url('member/tasks') ?>/' + taskId + '/status'
            );
        });
    });
</script>
<?= $this->endSection() ?>