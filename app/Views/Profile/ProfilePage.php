<?= $this->extend('Layouts/MobileLayout') ?>

<?= $this->section('content') ?>
<?php $profilePhotoUrl = trace_user_photo_url($currentUser ?? null); ?>
<section class="ProfileHeroCard">
    <div class="ProfileHeroAvatar">
        <?php if ($profilePhotoUrl !== null) : ?>
            <img src="<?= esc($profilePhotoUrl) ?>" alt="<?= esc($currentUser['full_name'] ?? trace_app_name()) ?>" class="ProfileHeroPhoto">
        <?php else : ?>
            <?= esc(trace_user_initial($currentUser ?? null)) ?>
        <?php endif; ?>
    </div>
    <h1><?= esc($currentUser['full_name'] ?? '-') ?></h1>
    <p><?= esc($currentUser['role_name'] ?? '-') ?></p>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Foto Profil</h2>
        <span>Maks. 3 MB</span>
    </div>
    <form method="post" action="<?= base_url('profile/photo') ?>" enctype="multipart/form-data" class="StackForm">
        <?= csrf_field() ?>
        <label class="FieldBlock">
            <span>Upload Foto</span>
            <input type="file" name="profilePhoto" accept="image/png,image/jpeg,image/webp" required>
        </label>
        <button type="submit" class="PrimaryButton">Update Foto</button>
    </form>
</section>

<section class="InfoCard">
    <div class="DetailList">
        <div><span>Nama</span><strong><?= esc($currentUser['full_name'] ?? '-') ?></strong></div>
        <div><span>Role</span><strong><?= esc($currentUser['role_name'] ?? '-') ?></strong></div>
        <div><span>Email</span><strong><?= esc($currentUser['email'] ?? '-') ?></strong></div>
        <div><span>Username</span><strong><?= esc($currentUser['username'] ?? '-') ?></strong></div>
    </div>
    <form method="post" action="<?= base_url('logout') ?>" class="LogoutBlock">
        <?= csrf_field() ?>
        <button type="submit" class="PrimaryButton">Logout</button>
    </form>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Endpoint API JWT</h2>
        <span>Integrasi mobile</span>
    </div>
    <div class="DetailList">
        <div><span>Issue Token</span><strong>/api/auth/token</strong></div>
        <div><span>Refresh Token</span><strong>/api/auth/refresh</strong></div>
        <div><span>Status Hari Ini</span><strong>/api/reports/today</strong></div>
    </div>
</section>
<?= $this->endSection() ?>
