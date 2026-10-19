<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('title') ?>Manage Tasks<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$badge = ['pending' => 'secondary', 'in_progress' => 'warning', 'completed' => 'success'];
$label = static fn (string $s) => ucwords(str_replace('_', ' ', $s));
$today = date('Y-m-d');
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Manage Tasks</h1>
    <a href="<?= site_url('admin/tasks/create') ?>" class="btn btn-sm btn-success">+ New task</a>
</div>

<!-- Status filter -->
<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link py-1 <?= $current === null ? 'active' : '' ?>" href="<?= site_url('admin/tasks') ?>">All</a>
    </li>
    <?php foreach ($statuses as $s): ?>
        <li class="nav-item">
            <a class="nav-link py-1 <?= $current === $s ? 'active' : '' ?>"
               href="<?= site_url('admin/tasks') ?>?status=<?= esc($s, 'url') ?>">
                <?= esc($label($s)) ?>
            </a>
        </li>
    <?php endforeach ?>
</ul>

<div class="bg-white border rounded">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Assigned to</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tasks)): ?>
                    <tr><td colspan="5" class="text-center text-secondary py-4">No tasks found.</td></tr>
                <?php endif ?>

                <?php foreach ($tasks as $task): ?>
                    <?php
                    $due     = $task->due_date ? substr($task->due_date, 0, 10) : null;
                    $overdue = $due && $due < $today && $task->status !== 'completed';
                    ?>
                    <tr>
                        <td>
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
                        <td class="text-end">
                            <a href="<?= site_url('admin/tasks/' . $task->id . '/edit') ?>" class="btn btn-sm btn-outline-secondary">Edit</a>

                            <form action="<?= site_url('admin/tasks/' . $task->id . '/delete') ?>" method="post" class="d-inline"
                                  onsubmit="return confirm('Delete this task?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>