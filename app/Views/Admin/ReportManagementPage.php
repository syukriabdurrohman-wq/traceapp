<?= $this->extend('Layouts/MobileLayout') ?>

<?= $this->section('content') ?>
<?= view('Components/PageHeader', [
    'eyebrow' => 'Monitoring Harian',
    'title' => 'Daftar Laporan',
    'subtitle' => 'Filter laporan berdasarkan tanggal, user, dan status.',
]) ?>

<section class="InfoCard">
    <form method="get" action="<?= base_url('admin/reports') ?>" class="StackForm">
        <div class="FieldGrid">
            <label class="FieldBlock">
                <span>Tanggal</span>
                <input type="date" name="reportDate" value="<?= esc($filters['reportDate']) ?>">
            </label>
            <?php if (! ($isSupervisor ?? false)) : ?>
                <label class="FieldBlock">
                    <span>User</span>
                    <select name="workerUserId">
                        <option value="">Semua user</option>
                        <?php foreach ($reportUsers as $user) : ?>
                            <option value="<?= esc((string) $user['id']) ?>" <?= $filters['workerUserId'] === (string) $user['id'] ? 'selected' : '' ?>>
                                <?= esc($user['full_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            <?php endif; ?>
        </div>

        <div class="FieldGrid">
            <label class="FieldBlock">
                <span>Status</span>
                <select name="status">
                    <option value="">Semua status</option>
                    <option value="Draft" <?= $filters['status'] === 'Draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="Submitted" <?= $filters['status'] === 'Submitted' ? 'selected' : '' ?>>Submitted</option>
                </select>
            </label>
            <div class="FieldBlock alignEnd">
                <button type="submit" class="PrimaryButton">Terapkan Filter</button>
            </div>
        </div>
    </form>
</section>

<section class="InfoCard">
    <div class="CardHeading">
        <h2>Hasil Monitoring</h2>
        <span><?= esc((string) count($reports)) ?> laporan</span>
    </div>
    <div class="StatusList">
        <?php foreach ($reports as $report) : ?>
            <div class="ReportCard">
                <div>
                    <strong><?= esc($report['worker_name']) ?></strong>
                    <p><?= esc(date('d M Y', strtotime($report['report_date']))) ?> • <?= esc($report['area_label'] ?? '-') ?></p>
                    <p><?= esc(character_limiter($report['current_location'] ?? '-', 70)) ?></p>
                    <?php if (!empty($report['edited_at'])) : ?>
                        <p style="color: #e67e22; font-weight: bold; font-size: 0.75rem; margin-top: 4px;">Diedit: <?= date('d M Y H:i', strtotime($report['edited_at'])) ?></p>
                    <?php endif; ?>
                </div>
                <div class="InlineActions">
                    <span class="StatusBadge <?= $report['status'] === 'Submitted' ? 'isDone' : 'isPending' ?>">
                        <?= $report['status'] === 'Submitted' ? '✓' : esc($report['status']) ?>
                    </span>
                    <a href="<?= base_url('reports/detail/' . $report['id']) ?>" class="InlineAction isIconOnly" aria-label="Lihat detail laporan" title="Lihat detail laporan"><?= trace_icon('detail') ?></a>
                    <a href="<?= base_url('reports/pdf/' . $report['id']) ?>" class="InlineAction isIconOnly" aria-label="Generate PDF laporan" title="Generate PDF laporan"><?= trace_icon('pdf') ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?= $this->endSection() ?>