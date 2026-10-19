<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('title') ?>Admin Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<h1 class="h4 mb-4">
    <i class="bi bi-speedometer2 me-2"></i>
    Admin Dashboard
</h1>

<?php
$cards = [
    ['title' => 'Total Users', 'count' => $totalUsers, 'color' => 'primary', 'icon' => 'bi-people'],
    ['title' => 'Pending Users', 'count' => $pendingUsers, 'color' => 'warning', 'icon' => 'bi-person-exclamation'],
    ['title' => 'Approved Users', 'count' => $approvedUsers, 'color' => 'success', 'icon' => 'bi-person-check'],
    ['title' => 'Total Tasks', 'count' => $taskStats['total'], 'color' => 'info', 'icon' => 'bi-list-task'],
    ['title' => 'Pending Tasks', 'count' => $taskStats['pending'], 'color' => 'warning', 'icon' => 'bi-hourglass-split'],
    ['title' => 'In Progress', 'count' => $taskStats['in_progress'], 'color' => 'primary', 'icon' => 'bi-arrow-repeat'],
    ['title' => 'Completed Tasks', 'count' => $taskStats['completed'], 'color' => 'success', 'icon' => 'bi-check-circle'],
    ['title' => 'Overdue Tasks', 'count' => $taskStats['overdue'], 'color' => 'danger', 'icon' => 'bi-exclamation-triangle'],
];
?>

<div class="row g-3">
    <?php foreach ($cards as $card): ?>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="text-secondary mb-0">
                            <?= esc($card['title']) ?>
                        </p>

                        <i class="bi <?= esc($card['icon']) ?> fs-4 text-<?= esc($card['color']) ?>"></i>
                    </div>

                    <h3 class="fw-bold mb-0 text-<?= esc($card['color']) ?>">
                        <?= esc($card['count']) ?>
                    </h3>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>