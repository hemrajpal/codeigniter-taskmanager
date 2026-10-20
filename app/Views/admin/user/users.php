
<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('title') ?>Manage Users<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h1 class="h4 mb-1">
        <i class="bi bi-people me-2"></i>Manage Users
    </h1>
    <p class="text-secondary mb-0">
        View and manage registered members.
    </p>
</div>

<?php $total = isset($pager) ? $pager->getTotal() : count($users ?? []); ?>

<div class="card border-0 shadow-sm">
    <!-- Toolbar -->
    <div class="card-header bg-white border-bottom p-3">
        <form action="<?= site_url('admin/users') ?>" method="get"
              class="row g-2 align-items-center">

            <div class="col-12 col-md-7 col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-secondary"></i>
                    </span>
                    <input type="search" name="search"
                           class="form-control border-start-0"
                           placeholder="Search by name or email"
                           aria-label="Search users"
                           value="<?= esc($search ?? '') ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>

            <?php if (! empty($search)): ?>
                <div class="col-auto">
                    <a href="<?= site_url('admin/users') ?>" class="btn btn-link text-decoration-none px-1">
                        <i class="bi bi-x-circle me-1"></i>Clear
                    </a>
                </div>
            <?php endif ?>

            <div class="col-12 col-md text-md-end text-secondary small">
                <?= esc($total) ?> user<?= $total === 1 ? '' : 's' ?>
                <?php if (! empty($search)): ?>
                    matching “<?= esc($search) ?>”
                <?php endif ?>
            </div>

        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-3 py-3">ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Email Verified</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (! empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <?php
                        $statusClass = match ($user->status) {
                            'active' => 'success',
                            'pending'  => 'warning',
                            'rejected' => 'danger',
                            default    => 'secondary',
                        };

                        $isSelf = (int) session()->get('user_id')
                            === (int) $user->id;
                        ?>

                        <tr>
                            <td class="px-3"><?= esc($user->id) ?></td>

                            <td class="fw-semibold">
                                <?= esc($user->name) ?>
                            </td>

                            <td><?= esc($user->email) ?></td>

                            <td>
                                <span class="badge bg-secondary">
                                    <?= esc(ucfirst($user->role)) ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-<?= esc($statusClass) ?>">
                                    <?= esc(ucfirst($user->status)) ?>
                                </span>
                            </td>

                            <td>
                                <?php if (! empty($user->email_verified_at)): ?>
                                    <span class="text-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Verified
                                    </span>
                                <?php else: ?>
                                    <span class="text-danger">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Not verified
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-2 flex-wrap">

                                    <a href="<?= site_url('admin/users/' . $user->id . '/edit') ?>"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Edit
                                    </a>

                                    <?php if (! $isSelf): ?>

                                        <button type="button"
                                                class="btn btn-sm btn-outline-success js-status"
                                                data-bs-toggle="modal"
                                                data-bs-target="#statusModal"
                                                data-user-id="<?= esc($user->id) ?>"
                                                data-user-name="<?= esc($user->name) ?>"
                                                data-current-status="<?= esc($user->status) ?>">
                                            <i class="bi bi-arrow-repeat me-1"></i>
                                            Status
                                        </button>

                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger js-delete"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                data-user-id="<?= esc($user->id) ?>"
                                                data-user-name="<?= esc($user->name) ?>">
                                            <i class="bi bi-trash me-1"></i>
                                            Delete
                                        </button>

                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-secondary">
                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                            No users found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (! empty($users)): ?>
        <?= $this->include('partials/pagination') ?>
    <?php endif; ?>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1"
     aria-labelledby="statusModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <form id="statusForm" method="post">
                <?= csrf_field() ?>

                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">
                        <i class="bi bi-arrow-repeat me-2"></i>
                        Update User Status
                    </h5>

                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>
                        Change the status for
                        <strong id="statusUserName"></strong>.
                    </p>

                    <label for="userStatus" class="form-label">
                        Select Status
                    </label>

                    <select name="status" id="userStatus"
                            class="form-select" required>
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>
                        Update Status
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1"
     aria-labelledby="deleteModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <form id="deleteForm" method="post">
                <?= csrf_field() ?>

                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Delete User
                    </h5>

                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    Are you sure you want to delete
                    <strong id="deleteUserName"></strong>?

                    <p class="text-secondary small mt-2 mb-0">
                        This action cannot be undone.
                    </p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        Yes, Delete
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>

<script>
    $(function () {
        const baseUrl = '<?= site_url('admin/users') ?>';

        // Status modal
        $('#statusModal').on('show.bs.modal', function (event) {
            const $btn = $(event.relatedTarget);

            const userId = $btn.attr('data-user-id');
            const name   = $btn.attr('data-user-name');
            const status = String($btn.attr('data-current-status') || '').trim().toLowerCase();

            $('#statusUserName').text(name);
            $('#userStatus').val(status);
            $('#statusForm').attr('action', baseUrl + '/' + userId + '/status');

            console.log(status)
            console.log($('#userStatus').val())

            if ($('#userStatus').val() !== status) {
                console.warn('No <option> matches status:', status);
            }
        });

        // Delete modal
        $('#deleteModal').on('show.bs.modal', function (event) {
            const $btn = $(event.relatedTarget);

            const userId = $btn.attr('data-user-id');
            const name   = $btn.attr('data-user-name');

            $('#deleteUserName').text(name);
            $('#deleteForm').attr('action', baseUrl + '/' + userId + '/delete');
        });
    });
</script>

<?= $this->endSection() ?>
