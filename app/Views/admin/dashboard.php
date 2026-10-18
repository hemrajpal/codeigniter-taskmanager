<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('title') ?>Admin Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <h1 class="h4 mb-4">Admin Dashboard</h1>

    <div class="row g-3">

        <div class="col-sm-6 col-lg-3">
            <div class="bg-white border rounded p-3">
                <div class="text-secondary small">Total users</div>
                <div class="h3 fw-bold mb-0"><?= esc($totalUsers) ?></div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="bg-white border rounded p-3">
                <div class="text-secondary small">Pending approval</div>
                <div class="h3 fw-bold mb-0"><?= esc($pendingUsers) ?></div>
            </div>
        </div>

    </div>

<?= $this->endSection() ?>