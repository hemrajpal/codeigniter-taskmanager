<div class="mb-3">
    <label class="form-label d-block">
        What is <?= esc($question) ?>?
        <input type="text" name="captcha" inputmode="numeric" autocomplete="off" required
               class="form-control mt-1 <?= session('errors.captcha') ? 'is-invalid' : '' ?>">
    </label>

    <?php if ($error = session('errors.captcha')): ?>
        <div class="text-danger small"><?= esc($error) ?></div>
    <?php endif ?>
</div>