<?= $this->extend('Layouts/MobileLayout') ?>

<?= $this->section('content') ?>
<?php
$latestReport = $homeData['latestReport'] ?? null;
$menuItems = [
    ['label' => 'Input', 'href' => base_url('reports/create'), 'icon' => 'document'],
    ['label' => 'Lokasi', 'href' => base_url('reports/create#section-location'), 'icon' => 'pin'],
    ['label' => 'Foto', 'href' => base_url('reports/create#section-photo'), 'icon' => 'camera'],
    ['label' => 'Pekerja', 'href' => base_url('reports/create#section-worker'), 'icon' => 'team'],
    ['label' => 'Progress', 'href' => base_url('reports/create#section-realization'), 'icon' => 'clipboard'],
    ['label' => 'Alat', 'href' => base_url('reports/create#section-heavy'), 'icon' => 'truck'],
    ['label' => 'Material', 'href' => base_url('reports/create#section-light-tool'), 'icon' => 'box'],
    ['label' => 'Kendala', 'href' => base_url('reports/create#section-obstacle'), 'icon' => 'alert'],
    ['label' => 'Review', 'href' => $latestReport ? base_url('reports/detail/' . $latestReport['id']) : base_url('reports/create'), 'icon' => 'detail'],
];

$primaryMenuItems = array_slice($menuItems, 0, 7);
$secondaryMenuItems = array_slice($menuItems, 7);
?>

<section class="WelcomeBanner">
    <div class="WelcomeBannerContent">
        <p class="Eyebrow isLight"><?= esc(trace_app_name()) ?> Workspace</p>
        <h1>Halo, <?= esc($currentUser['full_name'] ?? '-') ?></h1>
        <p><?= esc(trace_app_tagline()) ?></p>
        <div class="WelcomeBannerMeta">
            <span><?= esc(date('d M Y', strtotime($homeData['today']))) ?></span>
            <span><?= esc((string) $homeData['submittedCount']) ?> selesai</span>
        </div>
    </div>
</section>

<section class="DashboardPrimaryAction">
    <a href="<?= base_url('reports/create') ?>" class="PrimaryButton">Isi</a>
</section>
<br>
<section class="CompactStatGrid">
    <article class="MiniMetricCard">
        <span class="MetricLabel">Sudah Isi</span>
        <strong><?= esc((string) $homeData['submittedCount']) ?></strong>
    </article>
    <article class="MiniMetricCard">
        <span class="MetricLabel">Belum Isi</span>
        <strong><?= esc((string) $homeData['pendingCount']) ?></strong>
    </article>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Menu Cepat</h2>
        <span>8 pintasan</span>
    </div>
    <div class="QuickMenuGridSimple">
        <?php foreach ($primaryMenuItems as $item) : ?>
            <a href="<?= esc($item['href']) ?>" class="QuickMenuMini">
                <span class="QuickMenuMiniIcon"><?= trace_icon((string) $item['icon']) ?></span>
                <strong><?= esc($item['label']) ?></strong>
            </a>
        <?php endforeach; ?>
        <button type="button" class="QuickMenuMini isOtherToggle" id="QuickMenuToggle" aria-expanded="false" aria-controls="QuickMenuExtra">
            <span class="QuickMenuMiniIcon"><?= trace_icon('analytics') ?></span>
            <strong>Other</strong>
        </button>
    </div>
    <div class="QuickMenuGridSimple isExtra" id="QuickMenuExtra" hidden>
        <?php foreach ($secondaryMenuItems as $item) : ?>
            <a href="<?= esc($item['href']) ?>" class="QuickMenuMini">
                <span class="QuickMenuMiniIcon"><?= trace_icon((string) $item['icon']) ?></span>
                <strong><?= esc($item['label']) ?></strong>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="CarouselSection">
    <div class="CardHeading">
        <h2>Info Harian</h2>
        <span>Swipe</span>
    </div>
    <div class="BannerCarousel" id="BannerCarousel">
        <article class="BannerSlide isOne">
            <div>
                <small>Summary</small>
                <strong>Format laporan seragam dan cepat dibagikan ke WhatsApp.</strong>
            </div>
        </article>
        <article class="BannerSlide isTwo">
            <div>
                <small>Monitoring</small>
                <strong>Filter laporan per tanggal, user, dan status dengan tampilan mobile.</strong>
            </div>
        </article>
        <article class="BannerSlide isThree">
            <div>
                <small>Insight</small>
                <strong>Lihat trend progres dan monitoring disiplin pengisian laporan.</strong>
            </div>
        </article>
    </div>
    <div class="CarouselDots" id="CarouselDots">
        <span class="isActive"></span><span></span><span></span>
    </div>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Quick Summary</h2>
        <span>Terbaru</span>
    </div>
    <?php if ($latestReport !== null) : ?>
        <div class="CompactHighlightCard">
            <div>
                <strong><?= esc($latestReport['full_name']) ?></strong>
                <p><?= esc(character_limiter($latestReport['realization_summary'], 72)) ?></p>
            </div>
            <a href="<?= base_url('reports/detail/' . $latestReport['id']) ?>" class="InlineAction isIconOnly" aria-label="Lihat detail laporan terbaru" title="Lihat detail laporan terbaru"><?= trace_icon('detail') ?></a>
        </div>
    <?php else : ?>
        <p class="InfoText">Belum ada laporan final yang masuk.</p>
    <?php endif; ?>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Leaderboard</h2>
        <span>30 hari</span>
    </div>
    <div class="StatusList isCompact">
        <?php foreach ($homeData['leaderboard'] as $index => $item) : ?>
            <div class="StatusItem isCompact">
                <div>
                    <strong>#<?= $index + 1 ?> <?= esc($item['full_name']) ?></strong>
                    <p><?= esc((string) $item['total_report']) ?> laporan terkirim</p>
                </div>
                <span class="StatusBadge isDone">Top</span>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Status Hari Ini</h2>
        <span>Pengisian</span>
    </div>
    <div class="StatusList isCompact">
        <?php foreach ($homeData['statusBoard'] as $item) : ?>
            <div class="StatusItem isCompact">
                <div>
                    <strong><?= esc($item['name']) ?></strong>
                    <p><?= esc($item['status']) ?></p>
                </div>
                <span class="StatusBadge <?= $item['done'] ? 'isDone' : 'isPending' ?>"><?= $item['done'] ? 'Done' : 'Pending' ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?= $this->endSection() ?>
