<?= $this->extend('Layouts/MobileLayout') ?>

<?= $this->section('content') ?>
<?= view('Components/PageHeader', [
    'eyebrow' => 'Admin Panel',
    'title' => 'Kelola User',
    'subtitle' => 'Tambah, edit, dan aktif/nonaktifkan akun pengguna.',
]) ?>

<section class="InfoCard">
    <div class="CardHeading">
        <h2><?= $editUser ? 'Edit User' : 'Tambah User' ?></h2>
        <span><?= $editUser ? esc($editUser['full_name']) : 'User baru' ?></span>
    </div>
    <form method="post" action="<?= base_url('admin/users/save') ?>" class="StackForm">
        <?= csrf_field() ?>
        <input type="hidden" name="userId" value="<?= esc((string) ($editUser['id'] ?? '')) ?>">
        <input type="hidden" name="status" id="StatusInput" value="<?= esc(old('status', $editUser['status'] ?? 'Active')) ?>">

        <label class="FieldBlock">
            <span>Nama Lengkap</span>
            <input type="text" name="fullName" value="<?= esc(old('fullName', $editUser['full_name'] ?? '')) ?>" required>
        </label>

        <div class="FieldGrid">
             <label class="FieldBlock">
                <span>Role</span>
                <select name="roleId" required>
                    <?php foreach ($roles as $role) : ?>
                        <option value="<?= esc((string) $role['id']) ?>" <?= (string) old('roleId', $editUser['role_id'] ?? '') === (string) $role['id'] ? 'selected' : '' ?>>
                            <?= esc($role['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="FieldBlock">
                <span>Username</span>
                <input type="text" name="username" value="<?= esc(old('username', $editUser['username'] ?? '')) ?>" required>
            </label>
        </div>

        <div class="FieldGrid">
           
        </div>

        <div class="StatusToggleField">
            <span>Status User</span>
            <button
                type="button"
                class="UserStatusToggle <?= old('status', $editUser['status'] ?? 'Active') === 'Active' ? 'isActive' : 'isInactive' ?>"
                id="StatusToggleButton"
                data-target-input="StatusInput"
                data-status-active="Active"
                data-status-inactive="Inactive"
                aria-pressed="<?= old('status', $editUser['status'] ?? 'Active') === 'Active' ? 'true' : 'false' ?>"
                title="Toggle status user"
            >
                <?= trace_icon('toggle') ?>
            </button>
        </div>

        <label class="FieldBlock">
            <span>Nomor HP</span>
            <input type="tel" name="phone" value="<?= esc(old('phone', $editUser['phone'] ?? '')) ?>" required>
        </label>

        <label class="FieldBlock">
            <span>Password <?= $editUser ? '(kosongkan jika tidak diubah)' : '' ?></span>
            <input type="password" name="password" <?= $editUser ? '' : 'required' ?>>
        </label>

        <button type="submit" class="PrimaryButton"><?= $editUser ? 'Simpan Perubahan' : 'Tambah User' ?></button>
    </form>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Daftar User</h2>
        <span><?= esc((string) count($users)) ?> akun</span>
    </div>
    <div class="StatusList">
        <?php foreach ($users as $user) : ?>
            <div class="UserCard">
                <div>
                    <strong><?= esc($user['full_name']) ?></strong>
                    <p><?= esc($user['role_name']) ?> • <?= esc($user['email']) ?></p>
                </div>
                <div class="InlineActions">
                    <a href="<?= base_url('admin/users?edit=' . $user['id']) ?>" class="InlineAction isIconOnly" aria-label="Edit user" title="Edit user"><?= trace_icon('edit') ?></a>
                    <a href="<?= base_url('admin/users/toggle/' . $user['id']) ?>" class="InlineAction isIconOnly UserStatusLink <?= $user['status'] === 'Active' ? 'isActive' : 'isInactive' ?>" aria-label="Toggle status user" title="Toggle status user"><?= trace_icon('toggle') ?></a>
                    <a href="<?= base_url('admin/users/delete/' . $user['id']) ?>" class="InlineAction isIconOnly" style="color: #d71920;" onclick="return confirm('Hapus user dan seluruh data laporannya secara permanen? Data yang sudah dihapus tidak dapat dikembalikan.')" aria-label="Delete user" title="Delete user"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?= $this->endSection() ?>
