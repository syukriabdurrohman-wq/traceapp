<!doctype html>
<html lang="id">
<head>
    <?php
    $cssVersion = @filemtime(FCPATH . 'Assets/Css/MobileApp.css') ?: time();
    $jsVersion = @filemtime(FCPATH . 'Assets/Js/MobileApp.js') ?: time();
    $swVersion = @filemtime(FCPATH . 'service-worker.js') ?: time();
    $aosCssVersion = @filemtime(FCPATH . 'Assets/Vendor/AOS/aos.css') ?: $cssVersion;
    $aosJsVersion = @filemtime(FCPATH . 'Assets/Vendor/AOS/aos.js') ?: $jsVersion;
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#17345f">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?= esc($appName ?? trace_app_name()) ?>">
    <meta name="description" content="<?= esc(($pageTitle ?? ($appName ?? trace_app_name())) . ' - ' . ($appTagline ?? trace_app_tagline())) ?>">
    <title><?= esc(($pageTitle ?? ($appName ?? trace_app_name())) . ' | ' . ($appName ?? trace_app_name())) ?></title>
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <link rel="icon" href="<?= trace_logo_url() ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= base_url('Assets/Icons/AppIcon-192.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('Assets/Vendor/AOS/aos.css?v=' . $aosCssVersion) ?>">
    <link rel="stylesheet" href="<?= base_url('Assets/Css/MobileApp.css?v=' . $cssVersion) ?>">
</head>
<body class="MobileBody <?= esc($pageClass ?? '') ?>" data-page-class="<?= esc($pageClass ?? '') ?>">
    <div class="AmbientBackground"></div>
    <div class="DeviceWrap">
        <div class="MobileFrame">
            <?php if (($isAuthenticated ?? false) === true) : ?>
                <?= view('Components/TopBar', ['currentUser' => $currentUser ?? null]) ?>
            <?php endif; ?>

            <main class="MobileShell">
                <?= view('Components/FlashMessage') ?>
                <?= $this->renderSection('content') ?>
            </main>

            <?php if (($isAuthenticated ?? false) === true) : ?>
                <?= view('Components/BottomNavigation', ['items' => $bottomNavigation ?? [], 'currentUriPath' => $currentUriPath ?? '']) ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="PwaInstallPrompt" id="PwaInstallPrompt" hidden>
        <div class="PwaInstallPromptBody">
            <img src="<?= trace_logo_url() ?>" alt="<?= esc($appName ?? trace_app_name()) ?>" class="PwaInstallPromptLogo">
            <div>
                <strong><?= esc($appName ?? trace_app_name()) ?></strong>
                <p><?= esc($appTagline ?? trace_app_tagline()) ?></p>
            </div>
        </div>
        <div class="PwaInstallPromptActions">
            <button type="button" class="IconOnlyButton isSolid" id="InstallAppButton" aria-label="Install aplikasi" title="Install aplikasi">
                <?= trace_icon('install') ?>
            </button>
            <button type="button" class="IconOnlyButton" id="DismissInstallPrompt" aria-label="Tutup notifikasi install" title="Tutup notifikasi install">
                <?= trace_icon('close') ?>
            </button>
        </div>
    </div>

    <script>
        window.baseUrl = '<?= base_url('/') ?>';
        window.appAssetVersion = '<?= esc((string) max($cssVersion, $jsVersion, $swVersion)) ?>';
        window.appName = '<?= esc($appName ?? trace_app_name()) ?>';
        window.appTagline = '<?= esc($appTagline ?? trace_app_tagline()) ?>';
    </script>
    <script src="<?= base_url('Assets/Vendor/AOS/aos.js?v=' . $aosJsVersion) ?>"></script>
    <script src="<?= base_url('Assets/Js/MobileApp.js?v=' . $jsVersion) ?>"></script>
</body>
</html>
