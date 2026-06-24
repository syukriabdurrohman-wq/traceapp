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
        <a class="SecondaryActionButton isIconOnly" href="<?= esc($actionHref) ?>" aria-label="<?= esc($actionLabel) ?>" title="<?= esc($actionLabel) ?>">
            <?= trace_icon((string) $actionIcon) ?>
        </a>
    <?php endif; ?>
</section>
