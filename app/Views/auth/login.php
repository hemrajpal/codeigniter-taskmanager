<?= $this->extend('layouts/auth_layout') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$errors = (array) session()->getFlashdata('errors');
$other  = array_diff_key($errors, array_flip(['email', 'password', 'captcha']));
?>

<div class="auth-wrapper">

    <a class="auth-brand" href="<?= site_url('/') ?>">TaskManager<span>.</span></a>

    <div class="auth-card">

        <h1 class="auth-title">Sign in</h1>
        <p class="auth-subtitle">Enter your details to access your workspace.</p>

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

        <form action="<?= site_url('login') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" id="email" name="email"
                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                       placeholder="you@example.com"
                       value="<?= esc(old('email') ?? '') ?>"
                       autocomplete="username" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
                <?php endif ?>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password"
                       class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                       autocomplete="current-password" required>
                <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback"><?= esc($errors['password']) ?></div>
                <?php endif ?>
            </div>

            <button type="submit" class="auth-btn">Sign in</button>
        </form>

        <p class="auth-footer">
            Don't have an account? <a href="<?= site_url('signup') ?>">Create one</a>
        </p>

    </div>
</div>

<?= $this->endSection() ?>