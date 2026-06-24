<nav class="BottomNavigation">
    <?php foreach ($items as $item) :
        $targetPath = trim((string) parse_url((string) $item['href'], PHP_URL_PATH), '/');
        $isActive   = $currentUriPath === $targetPath || ($targetPath === '' && $currentUriPath === '');
        ?>
        <a class="BottomNavItem <?= $isActive ? 'isActive' : '' ?>" href="<?= esc($item['href']) ?>">
            <span class="BottomNavIcon"><?= trace_icon((string) $item['icon']) ?></span>
            <span><?= esc($item['label']) ?></span>
        </a>
    <?php endforeach; ?>
</nav>
