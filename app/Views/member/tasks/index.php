<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('title') ?>My Tasks<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$badge = ['pending' => 'secondary', 'in_progress' => 'warning', 'completed' => 'success'];
$label = static fn (string $s) => ucwords(str_replace('_', ' ', $s));
$today = date('Y-m-d');
?>

<h1 class="h4 mb-3">My Tasks</h1>

<!-- Status filter -->
<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link py-1 <?= $current === null ? 'active' : '' ?>" href="<?= site_url('member/tasks') ?>">All</a>
    </li>
    <?php foreach ($statuses as $s): ?>
        <li class="nav-item">
            <a class="nav-link py-1 <?= $current === $s ? 'active' : '' ?>"
               href="<?= site_url('member/tasks') ?>?status=<?= esc($s, 'url') ?>">
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
                    <th>Team</th>
                    <th>Due</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tasks)): ?>
                    <tr><td colspan="4" class="text-center text-secondary py-4">No tasks assigned to you.</td></tr>
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
                                <small class="text-secondary"><?= esc($task->description) ?></small>
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
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>