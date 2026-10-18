
<?= $this->extend('layouts/auth_layout') ?>

<?= $this->section('title') ?>
Login
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<div class="login-wrapper">
    <div class="login-card">

        <section class="brand-panel">
            <div>
                <div class="brand-logo">✓ TaskManager</div>

                <h1 class="brand-heading">
                    Work smarter.<br>
                    Achieve more.
                </h1>

                <p class="brand-description mt-3">
                    Organize your team's work, manage priorities,
                    and keep every project moving forward in one place.
                </p>
            </div>

            <small>© <?= date('Y') ?> TaskManager. All rights reserved.</small>
        </section>

        <section class="form-panel">
            <div class="form-content">

                <p class="welcome-label">Welcome back</p>

                <h2 class="form-title">Sign in to your account</h2>

                <p class="form-subtitle">
                    Enter your credentials to access your workspace.
                </p>

                <?php if ($error = session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= esc($error) ?>
                    </div>
                <?php endif; ?>

                <?php if ($errors = session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <div><?= esc($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success = session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= esc($success) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label for="email" class="form-label">
                            Email address
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            placeholder="you@example.com"
                            value="<?= esc(old('email') ?? '') ?>"
                            autocomplete="username"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <button type="submit" class="login-btn">
                        Sign in to TaskManager &rarr;
                    </button>
                </form>

                <div class="security-note">
                    🔒 Secure login · Your workspace awaits
                </div>

                <p class="text-center mt-4 mb-0">
                    Don't have an account?
                    <a href="<?= site_url('signup') ?>">Create an account</a>
                </p>

            </div>
        </section>

    </div>
</div>

<?= $this->endSection() ?>
