<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <h1 class="h4 mb-3">Welcome, <?= esc(session()->get('user_name')) ?></h1>
    <p class="text-secondary">Your tasks will appear here.</p>
<?= $this->endSection() ?>