<?= $this->extend('Layouts/MobileLayout') ?>

<?= $this->section('content') ?>
<?= view('Components/PageHeader', [
    'eyebrow' => 'Resume Sebelum Submit',
    'title' => 'Checklist Pengisian',
    'subtitle' => 'Pastikan semua komponen laporan sudah sesuai sebelum final submit.',
    'actionHref' => base_url('reports/edit/' . $bundle['report']['id']),
    'actionLabel' => 'Ubah Draft',
    'actionIcon' => 'edit',
]) ?>

<section class="MetricCard isAccent">
    <span class="MetricLabel">Progress Checklist</span>
    <strong><?= esc((string) $summary['doneCount']) ?>/<?= esc((string) $summary['totalCount']) ?> lengkap</strong>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Ringkasan WhatsApp</h2>
        <span>Preview hasil input Anda</span>
    </div>
    <pre class="SummaryBox" style="white-space: pre-wrap; font-size: 13px; line-height: 1.6; background: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid #e2e8f0; font-family: inherit; margin: 0; color: #1e293b;"><?= esc($waSummary) ?></pre>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Checklist Done</h2>
        <span><?= esc($bundle['report']['report_code']) ?></span>
    </div>
    <div class="ChecklistList">
        <?php foreach ($summary['items'] as $item) : ?>
            <div class="ChecklistItem <?= $item['done'] ? 'isDone' : 'isMissing' ?>">
                <span><?= esc($item['label']) ?></span>
                <strong><?= $item['done'] ? '✓' : '✕' ?></strong>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Import PDF</h2>
        <span>Siap setelah submit final</span>
    </div>
    <p class="InfoText">Setelah submit, laporan akan menghasilkan ringkasan WhatsApp dan PDF terstruktur beserta dokumentasi foto.</p>
</section>

<form method="post" action="<?= base_url('reports/submit/' . $bundle['report']['id']) ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="autoSendWa" value="0" data-auto-wa-input>
    <div class="StickyActionBar">
        <a href="<?= base_url('reports/edit/' . $bundle['report']['id']) ?>" class="GhostButton isIconOnly" aria-label="Kembali ke edit draft" title="Kembali ke edit draft"><?= trace_icon('back') ?></a>
        <button type="submit" class="PrimaryButton">Submit Final</button>
    </div>
</form>
<?= $this->endSection() ?>