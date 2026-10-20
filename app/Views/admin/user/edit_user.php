
<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('title') ?>Edit User<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= site_url('admin/users') ?>"
       class="text-decoration-none text-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Users
    </a>

    <h1 class="h4 mt-3 mb-1">
        <i class="bi bi-person-gear me-2"></i>Edit User
    </h1>

    <p class="text-secondary mb-0">
        Update account information and permissions.
    </p>
</div>

<?php
$errors = session()->getFlashdata('errors') ?? [];
$isSelf = (int) session()->get('user_id') === (int) $user->id;
?>

<?php if ($message = session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= esc($message) ?>
    </div>
<?php endif; ?>

<?php if (! empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <form action="<?= site_url('admin/users/' . $user->id . '/update') ?>"
              method="post">

            <?= csrf_field() ?>

            <div class="row g-3">

                <!-- Name -->
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>

                    <input type="text"
                           name="name"
                           id="name"
                           class="form-control"
                           value="<?= esc(old('name', $user->name)) ?>"
                           required>

                    <?php if (isset($errors['name'])): ?>
                        <div class="text-danger small mt-1">
                            <?= esc($errors['name']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label for="email" class="form-label">Email Address</label>

                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control"
                           value="<?= esc(old('email', $user->email)) ?>"
                           required>

                    <?php if (isset($errors['email'])): ?>
                        <div class="text-danger small mt-1">
                            <?= esc($errors['email']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Role -->
                <div class="col-md-6">
                    <label for="role" class="form-label">User Role</label>

                    <select name="role"
                            id="role"
                            class="form-select"
                            <?= $isSelf ? 'disabled' : '' ?>>

                        <?php foreach (['member', 'admin'] as $role): ?>
                            <option value="<?= esc($role) ?>"
                                <?= old('role', $user->role) === $role ? 'selected' : '' ?>>
                                <?= esc(ucfirst($role)) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                    <?php if ($isSelf): ?>
                        <small class="text-secondary">
                            You cannot change your own role.
                        </small>
                    <?php endif; ?>
                </div>

                <!-- Account Status -->
                <div class="col-md-6">
                    <label for="status" class="form-label">Account Status</label>

                    <select name="status"
                            id="status"
                            class="form-select"
                            <?= $isSelf ? 'disabled' : '' ?>>

                        <?php foreach (['pending', 'active', 'rejected'] as $status): ?>
                            <option value="<?= esc($status) ?>"
                                <?= old('status', $user->status) === $status ? 'selected' : '' ?>>
                                <?= esc(ucfirst($status)) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                    <?php if ($isSelf): ?>
                        <small class="text-secondary">
                            You cannot change your own account status.
                        </small>
                    <?php endif; ?>
                </div>

                <!-- Email Verification -->
                <div class="col-md-6">
                    <label for="email_verification" class="form-label">
                        Email Verification
                    </label>

                    <select name="email_verification"
                            id="email_verification"
                            class="form-select"
                            required>

                        <option value="verified"
                            <?= old(
                                'email_verification',
                                ! empty($user->email_verified_at) ? 'verified' : 'unverified'
                            ) === 'verified' ? 'selected' : '' ?>>
                            Verified
                        </option>

                        <option value="unverified"
                            <?= old(
                                'email_verification',
                                ! empty($user->email_verified_at) ? 'verified' : 'unverified'
                            ) === 'unverified' ? 'selected' : '' ?>>
                            Not Verified
                        </option>

                    </select>

                    <small class="text-secondary">
                        This controls whether the account is marked as email verified.
                    </small>
                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Save Changes
                </button>

                <a href="<?= site_url('admin/users') ?>"
                   class="btn btn-light border">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<?= $this->endSection() ?>
