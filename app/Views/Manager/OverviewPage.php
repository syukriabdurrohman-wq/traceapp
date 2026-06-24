<?= $this->extend('Layouts/MobileLayout') ?>

<?= $this->section('content') ?>
<?php
$totalReports = array_sum(array_map(static fn (array $item): int => (int) ($item['total_report'] ?? 0), $overview['trend']));
$activeDays = count($overview['trend']);
?>
<?= view('Components/PageHeader', [
    'eyebrow' => 'TRACE Insight',
    'title' => 'Trend & Rekap',
    'subtitle' => 'Ringkasan progres pelaporan dan cuaca kerja.',
]) ?>

<section class="CompactStatGrid">
    <article class="MiniMetricCard">
        <span class="MetricLabel">Total Laporan</span>
        <strong><?= esc((string) $totalReports) ?></strong>
    </article>
    <article class="MiniMetricCard">
        <span class="MetricLabel">Hari Aktif</span>
        <strong><?= esc((string) $activeDays) ?></strong>
    </article>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Trend 7 Hari</h2>
        <span><?= esc((string) count($overview['trend'])) ?> titik</span>
    </div>
    <div class="TrendMetricGrid">
        <?php foreach ($overview['trend'] as $item) : ?>
            <article class="MetricCard isTrend">
                <span class="MetricLabel"><?= esc(date('d M', strtotime($item['report_date']))) ?></span>
                <strong><?= esc((string) $item['total_report']) ?></strong>
                <p><?= esc((int) $item['total_report'] === 1 ? 'laporan' : 'laporan') ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Distribusi Cuaca</h2>
        <span>30 hari</span>
    </div>
    <div class="WeatherSummaryGrid">
        <?php foreach ($overview['weatherSummary'] as $item) : ?>
            <article class="WeatherSummaryCard">
                <strong><?= esc($item['weather_code']) ?></strong>
                <span><?= esc((string) $item['total']) ?> hari</span>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Laporan Terbaru</h2>
        <span>10 data</span>
    </div>
    <div class="StatusList">
        <?php foreach ($reports as $report) : ?>
            <div class="ReportCard isTrend">
                <div>
                    <strong><?= esc($report['worker_name']) ?></strong>
                    <p><?= esc(date('d M Y', strtotime($report['report_date']))) ?> • <?= esc($report['status']) ?></p>
                </div>
                <a href="<?= base_url('reports/detail/' . $report['id']) ?>" class="InlineAction isIconOnly" aria-label="Lihat detail laporan" title="Lihat detail laporan"><?= trace_icon('detail') ?></a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?= $this->endSection() ?>
