<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('title') ?>Member Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$stats = $stats ?? [
    'total' => 0,
    'pending' => 0,
    'in_progress' => 0,
    'completed' => 0,
];

$cards = [
    [
        'label' => 'Total Tasks',
        'value' => $stats['total'],
        'icon' => 'bi-list-task',
        'color' => 'primary',
    ],
    [
        'label' => 'Pending',
        'value' => $stats['pending'],
        'icon' => 'bi-hourglass-split',
        'color' => 'secondary',
    ],
    [
        'label' => 'In Progress',
        'value' => $stats['in_progress'],
        'icon' => 'bi-arrow-repeat',
        'color' => 'warning',
    ],
    [
        'label' => 'Completed',
        'value' => $stats['completed'],
        'icon' => 'bi-check-circle',
        'color' => 'success',
    ],
];

$badge = [
    'pending' => 'secondary',
    'in_progress' => 'warning',
    'completed' => 'success',
];

$label = static fn (string $status) =>
    ucwords(str_replace('_', ' ', $status));
?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="h3 mb-1">Welcome, <?= esc($userName ?? 'Member') ?>!</h1>
        <p class="text-secondary mb-0">
            Here's an overview of your assigned tasks.
        </p>
    </div>

    <a href="<?= site_url('member/tasks') ?>" class="btn btn-primary">
        <i class="bi bi-list-task me-1"></i> View My Tasks
    </a>
</div>

<div class="row g-3 mb-4">
    <?php foreach ($cards as $card): ?>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary small">
                            <?= esc($card['label']) ?>
                        </span>

                        <span class="rounded-3 bg-<?= esc($card['color']) ?>-subtle
                                     text-<?= esc($card['color']) ?> p-2">
                            <i class="bi <?= esc($card['icon']) ?> fs-5"></i>
                        </span>
                    </div>

                    <div class="h2 fw-bold mb-0">
                        <?= esc((string) $card['value']) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 px-4 pt-4 pb-2">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="h5 mb-1">Upcoming Tasks</h2>
                <p class="text-secondary small mb-0">
                    Your next tasks that are not yet completed.
                </p>
            </div>

            <a href="<?= site_url('member/tasks') ?>"
               class="text-decoration-none small">
                View all tasks →
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <?php if (empty($upcomingTasks)): ?>
            <div class="text-center py-5 px-3">
                <i class="bi bi-clipboard-check text-secondary fs-1"></i>
                <h3 class="h6 mt-3">No upcoming tasks</h3>
                <p class="text-secondary small mb-0">
                    You're all caught up, or no upcoming tasks have been assigned to you.
                </p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Task</th>
                            <th>Status</th>
                            <th>Due Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($upcomingTasks as $task): ?>
                            <?php
                            $due = $task->due_date
                                ? substr($task->due_date, 0, 10)
                                : null;
                            ?>

                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">
                                        <?= esc($task->title) ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge text-bg-<?= esc(
                                        $badge[$task->status] ?? 'secondary'
                                    ) ?>">
                                        <?= esc($label($task->status)) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= $due
                                        ? esc(date('d M Y', strtotime($due)))
                                        : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
    </div>
</div>

<?= $this->endSection() ?>