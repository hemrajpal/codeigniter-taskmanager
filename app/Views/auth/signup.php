<?= $this->extend('layouts/auth_layout') ?>

<?= $this->section('title') ?>Create Account<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$errors = (array) session()->getFlashdata('errors');
$other  = array_diff_key($errors, array_flip(['name', 'email', 'password', 'password_confirm', 'captcha']));
?>

<div class="auth-wrapper">

    <a class="auth-brand" href="<?= site_url('/') ?>">TaskManager<span>.</span></a>

    <div class="auth-card">

        <h1 class="auth-title">Create your account</h1>
        <p class="auth-subtitle">Fill in your details to request access.</p>

        <?php foreach (['success' => 'success', 'warning' => 'warning', 'error' => 'danger'] as $key => $type): ?>
            <?php if ($message = session()->getFlashdata($key)): ?>
                <div class="alert alert-<?= $type ?>" role="alert"><?= esc($message) ?></div>
            <?php endif ?>
        <?php endforeach ?>

        <?php if ($other): ?>
            <div class="alert alert-danger" role="alert">
                <?php foreach ($other as $message): ?>
                    <div><?= esc($message) ?></div>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <form action="<?= site_url('signup') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="name" class="form-label">Full name</label>
                <input type="text" id="name" name="name"
                       class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                       value="<?= esc(old('name') ?? '') ?>"
                       maxlength="100" autocomplete="name" required>
                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback"><?= esc($errors['name']) ?></div>
                <?php endif ?>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" id="email" name="email"
                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                       placeholder="you@example.com"
                       value="<?= esc(old('email') ?? '') ?>"
                       maxlength="150" autocomplete="email" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
                <?php endif ?>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password"
                       class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                       placeholder="At least 8 characters"
                       minlength="8" autocomplete="new-password" required>
                <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback"><?= esc($errors['password']) ?></div>
                <?php endif ?>
            </div>

            <div class="mb-4">
                <label for="password_confirm" class="form-label">Confirm password</label>
                <input type="password" id="password_confirm" name="password_confirm"
                       class="form-control <?= isset($errors['password_confirm']) ? 'is-invalid' : '' ?>"
                       minlength="8" autocomplete="new-password" required>
                <?php if (isset($errors['password_confirm'])): ?>
                    <div class="invalid-feedback"><?= esc($errors['password_confirm']) ?></div>
                <?php endif ?>
            </div>

            <button type="submit" class="auth-btn">Create account</button>
        </form>

        <p class="auth-note">Accounts need email verification and admin approval.</p>

        <p class="auth-footer">
            Already have an account? <a href="<?= site_url('login') ?>">Sign in</a>
        </p>

    </div>
</div>

<?= $this->endSection() ?>