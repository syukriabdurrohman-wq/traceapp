<?php
$actionIcon = $actionIcon ?? 'detail';
?>
<section class="PageHeader">
    <div>
        <p class="Eyebrow"><?= esc($eyebrow ?? trace_app_name()) ?></p>
        <h1><?= esc($title ?? 'Halaman') ?></h1>
        <?php if (! empty($subtitle ?? '')) : ?>
            <p class="PageSubtitle"><?= esc($subtitle) ?></p>
        <?php endif; ?>
    </div>

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
