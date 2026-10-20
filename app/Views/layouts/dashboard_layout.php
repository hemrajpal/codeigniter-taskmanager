<?php
$role = session()->get('user_role');
$name = session()->get('user_name') ?? 'User';

// Sidebar links: [label, path]. Add more as you build pages.
$nav = [];

if ($role === 'admin') {
    $nav[] = ['Admin Dashboard', 'admin/dashboard'];
    $nav[] = ['Manage Users', 'admin/users'];
    $nav[] = ['Manage Tasks', 'admin/tasks'];
}

// Everyone, admins included
$nav[] = ['My Dashboard', 'member/dashboard'];
$nav[] = ['My Tasks', 'member/tasks'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ?: 'Dashboard' ?> | TaskManager</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="<?= base_url('assets/css/dashboard.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/dashboard.css') ?>" rel="stylesheet">

    <?= $this->renderSection('styles') ?>
</head>
<body>

<div class="dash-wrap">

    <!-- Sidebar (slides in on mobile) -->
    <aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar">
        <div class="offcanvas-header d-lg-none">
            <span class="fw-bold">Menu</span>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Close"></button>
        </div>

        <a class="brand d-none d-lg-block" href="<?= site_url('/') ?>">TaskManager<span>.</span></a>

        <nav class="nav flex-column">
            <?php foreach ($nav as [$label, $path]): ?>
                <a class="nav-link <?= url_is($path . '*') ? 'active' : '' ?>"
                   href="<?= site_url($path) ?>">
                    <?= esc($label) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <!-- Main area -->
    <div class="main">

        <div class="topbar d-flex justify-content-between align-items-center">

            <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#sidebar"
                    aria-controls="sidebar" aria-label="Open menu">
                ☰
            </button>

            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-secondary small">
                    <?= esc($name) ?> <span class="badge text-bg-light border"><?= esc(ucfirst((string) $role)) ?></span>
                </span>

                <form action="<?= site_url('logout') ?>" method="post" class="m-0">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
                </form>
            </div>
        </div>

        <main class="content">

            <?php foreach (['success' => 'success', 'warning' => 'warning', 'error' => 'danger'] as $key => $type): ?>
                <?php if (session()->getFlashdata($key)): ?>
                    <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
                        <?= esc(session()->getFlashdata($key)) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <?= $this->renderSection('content') ?>

        </main>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>