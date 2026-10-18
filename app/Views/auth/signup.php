 
<?= $this->extend('layouts/auth_layout') ?>

<?= $this->section('title') ?>
Create Account
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="login-wrapper">
    <div class="login-card">

        <section class="brand-panel">
            <div>
                <div class="brand-logo">✓ TaskManager</div>

                <h1 class="brand-heading">
                    Great teams<br>
                    start here.
                </h1>

                <p class="brand-description mt-3">
                    Create your account to collaborate with your team,
                    organize tasks, and track your progress.
                </p>
            </div>

            <small>
                © <?= date('Y') ?> TaskManager. All rights reserved.
            </small>
        </section>

        <section class="form-panel">
            <div class="form-content">

                <p class="welcome-label">Get started</p>

                <h2 class="form-title">Create your account</h2>

                <p class="form-subtitle">
                    Fill in your details to request access.
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

                <form action="<?= site_url('signup') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Full name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control"
                            placeholder="Enter your full name"
                            value="<?= esc(old('name') ?? '') ?>"
                            maxlength="100"
                            autocomplete="name"
                            required
                        >
                    </div>

                    <div class="mb-3">
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
                            maxlength="150"
                            autocomplete="email"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Create a strong password"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="password_confirm" class="form-label">
                            Confirm password
                        </label>

                        <input
                            type="password"
                            id="password_confirm"
                            name="password_confirm"
                            class="form-control"
                            placeholder="Enter your password again"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button type="submit" class="login-btn">
                        Create account &rarr;
                    </button>
                </form>

                <div class="security-note">
                    🔒 Your account requires email verification
                    and admin approval.
                </div>

                <p class="text-center mt-4 mb-0">
                    Already have an account?
                    <a href="<?= site_url('login') ?>">Sign in</a>
                </p>

            </div>
        </section>

    </div>
</div>
<?= $this->endSection() ?>
