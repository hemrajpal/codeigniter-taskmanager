<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('title') ?><?= $task ? 'Edit Task' : 'New Task' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$errors = session('errors') ?? [];
$action = $task ? site_url('admin/tasks/' . $task->id . '/update') : site_url('admin/tasks');
$label  = static fn (string $s) => ucwords(str_replace('_', ' ', $s));
$dueVal = $task && $task->due_date ? substr($task->due_date, 0, 10) : '';
?>

<h1 class="h4 mb-3"><?= $task ? 'Edit Task' : 'New Task' ?></h1>

<?php if (! empty($errors['error'])): ?>
    <div class="alert alert-danger"><?= esc($errors['error']) ?></div>
<?php endif ?>

<form action="<?= $action ?>" method="post" class="bg-white border rounded p-4" style="max-width: 640px;">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" id="title" name="title"
               class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>"
               value="<?= esc(old('title', $task->title ?? '')) ?>">
        <?php if (isset($errors['title'])): ?>
            <div class="invalid-feedback"><?= esc($errors['title']) ?></div>
        <?php endif ?>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" rows="4"
                  class="form-control <?= isset($errors['description']) ? 'is-invalid' : '' ?>"><?= esc(old('description', $task->description ?? '')) ?></textarea>
        <?php if (isset($errors['description'])): ?>
            <div class="invalid-feedback"><?= esc($errors['description']) ?></div>
        <?php endif ?>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-6">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select <?= isset($errors['status']) ? 'is-invalid' : '' ?>">
                <?php foreach ($statuses as $s): ?>
                    <option value="<?= esc($s) ?>" <?= old('status', $task->status ?? 'pending') === $s ? 'selected' : '' ?>>
                        <?= esc($label($s)) ?>
                    </option>
                <?php endforeach ?>
            </select>
            <?php if (isset($errors['status'])): ?>
                <div class="invalid-feedback"><?= esc($errors['status']) ?></div>
            <?php endif ?>
        </div>

        <div class="col-sm-6">
            <label for="due_date" class="form-label">Due date</label>
            <input type="date" id="due_date" name="due_date"
                   class="form-control <?= isset($errors['due_date']) ? 'is-invalid' : '' ?>"
                   value="<?= esc(old('due_date', $dueVal)) ?>">
            <?php if (isset($errors['due_date'])): ?>
                <div class="invalid-feedback"><?= esc($errors['due_date']) ?></div>
            <?php endif ?>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label">Assign to</label>

        <?php if (empty($members)): ?>
            <p class="text-secondary small mb-0">No active members yet.</p>
        <?php endif ?>

        <?php $checked = (array) old('assignees', $assigned) ?>
        <?php foreach ($members as $m): ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="assignees[]"
                       value="<?= esc($m->id) ?>" id="user<?= esc($m->id) ?>"
                       <?= in_array($m->id, $checked) ? 'checked' : '' ?>>
                <label class="form-check-label" for="user<?= esc($m->id) ?>"><?= esc($m->name) ?></label>
            </div>
        <?php endforeach ?>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success"><?= $task ? 'Save changes' : 'Create task' ?></button>
        <a href="<?= site_url('admin/tasks') ?>" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<?= $this->endSection() ?>