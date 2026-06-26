<?php
$actionIcon = $actionIcon ?? 'detail';
$logoSrc = $logoSrc ?? '';
$logoAlt = $logoAlt ?? 'Logo';
?>
<section class="PageHeader">
    <div class="PageHeaderContent">
        <p class="Eyebrow"><?= esc($eyebrow ?? trace_app_name()) ?></p>
        <h1><?= esc($title ?? 'Halaman') ?></h1>
        <?php if (! empty($subtitle ?? '')) : ?>
            <p class="PageSubtitle"><?= esc($subtitle) ?></p>
        <?php endif; ?>
    </div>

    <?php if ($logoSrc !== '') : ?>
        <div class="PageHeaderLogoWrap">
            <img class="PageHeaderLogo" src="<?= esc($logoSrc) ?>" alt="<?= esc($logoAlt) ?>">
        </div>
    <?php endif; ?>

    <?php if (! empty($actionHref ?? '') && ! empty($actionLabel ?? '')) : ?>
        <a class="SecondaryActionButton <?= ! empty($actionText ?? '') ? 'hasText' : 'isIconOnly' ?>" href="<?= esc($actionHref) ?>" aria-label="<?= esc($actionLabel) ?>" title="<?= esc($actionLabel) ?>"<?= ! empty($actionDownload ?? '') ? ' download="' . esc($actionDownload) . '"' : '' ?>>
            <?= trace_icon((string) $actionIcon) ?>
            <?php if (! empty($actionText ?? '')) : ?>
                <span>
                    <?= esc($actionText) ?>
                    <?php if (! empty($actionHint ?? '')) : ?>
                        <small><?= esc($actionHint) ?></small>
                    <?php endif; ?>
                </span>
            <?php endif; ?>
        </a>
    <?php endif; ?>
</section>

<style>
    .PageHeader {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .PageHeaderContent {
        flex: 1;
    }

    .PageHeaderLogoWrap {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-left: auto;
    }

    .PageHeaderLogo {
        max-height: 48px;
        max-width: 120px;
        object-fit: contain;
    }
</style>
