<?php
$profilePhotoUrl = trace_user_photo_url($currentUser ?? null);
$currentUriPath = trim((string) ($currentUriPath ?? current_url(true)->getPath()), '/');
$showPartnerLogo = ! str_starts_with($currentUriPath, 'reports/create');
$partnerLogoVersion = @filemtime(FCPATH . 'Assets/Image/Logo_KSO.png') ?: time();
?>
<header class="TopBar">
    <a href="<?= base_url('/') ?>" class="TopBarBrand">
        <span class="TopBarBrandMain">
            <span class="TopBarBrandIcon">
                <img src="<?= trace_logo_url() ?>" alt="<?= esc(trace_app_name()) ?>">
            </span>
            <span class="TopBarBrandText">
                <strong><?= esc(trace_app_name()) ?></strong>
                <small><?= esc($currentUser['role_name'] ?? trace_app_tagline()) ?></small>
            </span>
        </span>
        <?php if ($showPartnerLogo) : ?>
            <span class="TopBarPartnerLogo" aria-label="Logo KSO">
                <img src="<?= base_url('Assets/Image/Logo_KSO.png?v=' . $partnerLogoVersion) ?>" alt="Logo KSO">
            </span>
        <?php endif; ?>
    </a>

    <div class="TopBarActions">
        <a href="<?= base_url('profile') ?>" class="ProfileChip" aria-label="Buka profil">
            <?php if ($profilePhotoUrl !== null) : ?>
                <img src="<?= esc($profilePhotoUrl) ?>" alt="<?= esc($currentUser['full_name'] ?? trace_app_name()) ?>" class="ProfilePhoto">
            <?php else : ?>
                <span class="ProfileAvatar"><?= esc(trace_user_initial($currentUser ?? null)) ?></span>
            <?php endif; ?>
        </a>

        <form method="post" action="<?= base_url('logout') ?>" class="LogoutForm">
            <?= csrf_field() ?>
            <button type="submit" class="IconButton" aria-label="Logout">
                <?= trace_icon('logout') ?>
            </button>
        </form>
    </div>
</header>
