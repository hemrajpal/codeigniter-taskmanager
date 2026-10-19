<?= $this->extend('layouts/auth_layout') ?>

<?= $this->section('styles') ?>

<link href="<?= base_url('assets/css/home.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/home.css') ?>" rel="stylesheet">

<?= $this->endSection() ?>

<?= $this->section('title') ?>
Home
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<?php
$isLoggedIn = session()->get('isLoggedIn') === true;
$userRole   = session()->get('user_role');
$userName   = session()->get('user_name') ?? 'User';

// Role logic written once, used everywhere below
$dashboardUrl = match ($userRole) {
    'admin'  => site_url('admin/dashboard'),
    'member' => site_url('member/dashboard'),
    default  => null,
};
$dashboardLabel = $userRole === 'admin' ? 'Admin Dashboard' : 'My Dashboard';

$features = [
    ['Tasks',    'Create tasks, set deadlines, and keep every responsibility clear.'],
    ['Teams',    'Add members and assign work to the right person.'],
    ['Progress', 'See what is pending, in progress, and done at a glance.'],
];
?>

<div class="home-page">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-md border-bottom py-3">
        <div class="container">

            <a class="brand" href="<?= site_url('/') ?>">TaskManager<span>.</span></a>

            <button class="navbar-toggler border-0" type="button"
                    data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-md-center gap-2">

                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>

                    <?php if ($isLoggedIn): ?>

                        <li class="nav-item">
                            <span class="nav-link">Hello, <?= esc($userName) ?></span>
                        </li>

                        <?php if ($dashboardUrl): ?>
                            <li class="nav-item">
                                <a class="btn btn-accent btn-sm px-3" href="<?= $dashboardUrl ?>">
                                    <?= esc($dashboardLabel) ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <form action="<?= site_url('logout') ?>" method="post" class="m-0">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-line btn-sm px-3">Logout</button>
                            </form>
                        </li>

                    <?php else: ?>

                        <li class="nav-item">
                            <a class="btn btn-line btn-sm px-3" href="<?= site_url('login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-accent btn-sm px-3" href="<?= site_url('signup') ?>">Get Started</a>
                        </li>

                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>


    <!-- Hero -->
    <header class="container hero">

        <div class="eyebrow mb-3">
            <?= $isLoggedIn ? 'Welcome back, ' . esc($userName) : 'Task management for teams' ?>
        </div>

        <h1>Plan it. Assign it. Finish it.</h1>

        <p class="hero-text mt-4 mb-4">
            One simple workspace to manage tasks, assign responsibilities,
            and track your team's progress.
        </p>

        <div class="d-flex flex-wrap gap-2">
            <?php if ($isLoggedIn): ?>

                <?php if ($dashboardUrl): ?>
                    <a href="<?= $dashboardUrl ?>" class="btn btn-accent btn-lg px-4">
                        Go to <?= esc($dashboardLabel) ?> →
                    </a>
                <?php endif; ?>

            <?php else: ?>

                <a href="<?= site_url('signup') ?>" class="btn btn-accent btn-lg px-4">Get Started</a>
                <a href="<?= site_url('login') ?>" class="btn btn-line btn-lg px-4">Login</a>

            <?php endif; ?>
        </div>

    </header>


    <!-- Features -->
    <section class="features" id="features">
        <div class="container">
            <div class="row g-4">
                <?php foreach ($features as $i => [$title, $text]): ?>
                    <div class="col-md-4">
                        <div class="feature">
                            <div class="feature-number"><?= sprintf('%02d', $i + 1) ?></div>
                            <h2 class="h5 fw-bold"><?= esc($title) ?></h2>
                            <p><?= esc($text) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


    <!-- Call to action -->
    <section class="cta">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">

            <div>
                <h2 class="h3 fw-bold mb-2">Get your work organized today.</h2>
                <p class="mb-0">Keep tasks and team responsibilities in one place.</p>
            </div>

            <div>
                <?php if ($isLoggedIn): ?>
                    <?php if ($dashboardUrl): ?>
                        <a href="<?= $dashboardUrl ?>" class="btn btn-light btn-lg px-4">
                            Open <?= esc($dashboardLabel) ?>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= site_url('signup') ?>" class="btn btn-light btn-lg px-4">
                        Create an Account
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </section>


    <!-- Footer -->
    <footer class="site-footer py-4">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">

            <span>&copy; <?= date('Y') ?> TaskManager. All rights reserved.</span>

            <?php if (! $isLoggedIn): ?>
                <div class="d-flex gap-3">
                    <a href="<?= site_url('login') ?>">Login</a>
                    <a href="<?= site_url('signup') ?>">Sign Up</a>
                </div>
            <?php endif; ?>

        </div>
    </footer>

</div>

<?= $this->endSection() ?>