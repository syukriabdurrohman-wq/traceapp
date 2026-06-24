<?= $this->extend('Layouts/MobileLayout') ?>

<?= $this->section('content') ?>
<?php
$workerUpdates    = $formData['workerUpdates'] ?? [];
$heavyEquipment   = $formData['heavyEquipment'] ?? [];
$existingPhotos   = $reportBundle['photos'] ?? [];
$currentStep      = (int) old('currentStep', $formData['currentStep'] ?? 1);
$realizationItems = old('realizationItems', $formData['realizationItems'] ?? []);
$workerCustomRows = old('workerCustomRows', $formData['workerCustomRows'] ?? []);
$heavyCustomRows  = old('heavyCustomRows', $formData['heavyCustomRows'] ?? []);
$lightTools       = old('lightTools', $formData['lightTools'] ?? []);
$currentLocationOptions = $formOptions['currentLocations'] ?? ['Area Swangi', 'Area Lanal', 'Area RPI', 'Area Laut', 'Lainnya'];
$structureLocationOptions = $formOptions['structureLocations'] ?? ['PL1', 'PL2', 'P23', 'P24', 'P25', 'P26', 'P27', 'P28', 'P29', 'P30', 'P32', 'P33', 'P34', 'Fender PL1', 'Fender PL2', 'Fender P22', 'Fender P23'];
$currentLocationValue = old('currentLocation', $formData['currentLocation'] ?? '');
$isCustomCurrentLocation = $currentLocationValue !== '' && ! in_array($currentLocationValue, $currentLocationOptions, true);
$currentLocationSelectValue = $isCustomCurrentLocation ? 'Lainnya' : $currentLocationValue;
$currentLocationManualValue = old('currentLocationManual', $isCustomCurrentLocation ? $currentLocationValue : '');

$realizationItems = is_array($realizationItems) && $realizationItems !== [] ? $realizationItems : [['work_item' => '', 'unit' => '', 'plan_text' => '', 'realization_text' => '', 'deviation_text' => '', 'partner' => '']];
$workerCustomRows = is_array($workerCustomRows) && $workerCustomRows !== [] ? $workerCustomRows : [['label' => '', 'quantity' => '']];
$heavyCustomRows  = is_array($heavyCustomRows) && $heavyCustomRows !== [] ? $heavyCustomRows : [['label' => '', 'quantity' => '']];
$lightTools       = is_array($lightTools) && $lightTools !== [] ? $lightTools : [['tool_label' => '', 'volume' => '', 'unit' => '']];
?>

<style>
    /* Styling khusus Grid Pekerja dan Alat Berat */
    .GridTwoColumns {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }

    .BoxedCounterField {
        display: flex;
        flex-direction: column;
        border: 1px solid #f9a8d4;
        border-radius: 12px;
        padding: 12px;
        background: linear-gradient(135deg, #fff7ed, #fdf2f8);
        box-shadow: 0 4px 10px rgba(249, 115, 22, 0.12);
    }

    .BoxedCounterField span {
        font-size: 13px;
        font-weight: 700;
        color: #be185d;
        margin-bottom: 8px;
    }

    .BoxedCounterField input {
        border: 1px solid #fb923c;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 14px;
        width: 100%;
        background: #fff7ed;
        color: #831843;
        text-align: left;
        outline: none;
    }

    .BoxedCounterField input:focus {
        border-color: #ec4899;
        box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.18);
        background: #ffffff;
    }

    .StickyActionBar.isWizard {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        background: #fff7ed;
        border-top: 1px solid #fed7aa;
    }

    .StickyActionBar .GhostButton,
    .StickyActionBar .PrimaryButton {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 14px;
        font-size: 14px;
        border-radius: 10px;
        min-height: 44px;
        flex-shrink: 0;
        border: none;
        cursor: pointer;
        font-weight: 700;
    }

    .StickyActionBar .GhostButton {
        background: #fdf2f8;
        color: #be185d;
        border: 1px solid #f9a8d4;
    }

    .StickyActionBar .PrimaryButton {
        background: linear-gradient(135deg, #ec4899, #f97316);
        color: #ffffff;
        box-shadow: 0 6px 14px rgba(249, 115, 22, 0.28);
    }

    .StickyActionBar .PrimaryButton:hover {
        background: linear-gradient(135deg, #db2777, #ea580c);
    }

    .StickyActionBar .GhostButton:hover {
        background: #fce7f3;
    }

    .StickyActionBar svg {
        width: 16px;
        height: 16px;
    }

    .RequiredHint {
        display: block;
        margin-top: 4px;
        font-size: 11px;
        line-height: 1.2;
        color: #e11d48;
        font-weight: 600;
    }
</style>

<?= view('Components/PageHeader', [
    'eyebrow' => 'Input Laporan Harian',
    'title' => $pageTitle ?? 'Input Laporan',
    'subtitle' => 'Satu form lengkap untuk seluruh aktivitas pekerjaan harian lapangan.',
]) ?>

<form method="post" action="<?= base_url('reports/save-draft') ?>" enctype="multipart/form-data" class="StackForm" id="ReportWizardForm" data-step="<?= esc((string) max(1, min(7, $currentStep))) ?>" data-draft-key="<?= esc('trace-report-draft:' . ($currentUser['id'] ?? 'guest') . ':' . ($formData['reportId'] ?? 'new')) ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="reportId" value="<?= esc((string) ($formData['reportId'] ?? '')) ?>">
    <input type="hidden" name="currentStep" id="CurrentStepInput" value="<?= esc((string) max(1, min(7, $currentStep))) ?>">

    <div class="WizardProgress">
        <button type="button" class="WizardChip" data-wizard-jump="1">1. Identitas</button>
        <button type="button" class="WizardChip" data-wizard-jump="2">2. Lokasi</button>
        <button type="button" class="WizardChip" data-wizard-jump="3">3. Realisasi</button>
        <button type="button" class="WizardChip" data-wizard-jump="4">4. Pekerja</button>
        <button type="button" class="WizardChip" data-wizard-jump="5">5. Alat Berat</button>
        <button type="button" class="WizardChip" data-wizard-jump="6">6. Alat Ringan</button>
        <button type="button" class="WizardChip" data-wizard-jump="7">7. Kendala</button>
    </div>

    <section class="FormSectionCard WizardStep" id="section-identity" data-wizard-step="1">
        <div class="CardHeading">
            <h2>1. Identitas Laporan</h2>
            <span>Wajib diisi dulu</span>
        </div>
        <div class="FieldGrid">
            <label class="FieldBlock">
                <span>Tanggal Laporan</span>
                <small class="RequiredHint">wajib diisi</small>
                <input type="date" name="reportDate" value="<?= esc(old('reportDate', $formData['reportDate'] ?? '')) ?>" required>
            </label>

            <label class="FieldBlock">
                <span>Supervisor / Pelaksana</span>
                <small class="RequiredHint">wajib diisi</small>
                <select name="workerUserId" required>
                    <option value="">Pilih user</option>
                    <?php foreach ($formOptions['workerUsers'] as $user) : ?>
                        <option value="<?= esc((string) $user['id']) ?>" <?= (string) old('workerUserId', $formData['workerUserId'] ?? '') === (string) $user['id'] ? 'selected' : '' ?>>
                            <?= esc($user['full_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>

        <div class="StickyActionBar isWizard">
            <a href="<?= base_url('/') ?>" class="GhostButton">
                <?= trace_icon('back') ?>
                <span>Back</span>
            </a>

            <button type="button" class="PrimaryButton" data-wizard-next>
                <span>Next</span>
                <?= trace_icon('next') ?>
            </button>
        </div>
    </section>

    <section class="FormSectionCard WizardStep" id="section-location" data-wizard-step="2">
        <div class="CardHeading">
            <h2>2. Lokasi, Foto & Cuaca</h2>
            <span>Lokasi aktual pekerjaan</span>
        </div>
        <label class="FieldBlock">
            <span>Lokasi Terkini</span>
            <small class="RequiredHint">wajib diisi</small>
            <select name="currentLocation" id="CurrentLocationSelect" required>
                <option value="">Pilih lokasi terkini</option>
                <?php foreach ($currentLocationOptions as $locationOption) : ?>
                    <option value="<?= esc($locationOption) ?>" <?= $currentLocationSelectValue === $locationOption ? 'selected' : '' ?>>
                        <?= esc($locationOption) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="FieldBlock" id="CurrentLocationManualField">
            <span>Lokasi Terkini Lainnya</span>
            <small class="RequiredHint">wajib diisi jika memilih Lainnya</small>
            <input type="text" name="currentLocationManual" value="<?= esc($currentLocationManualValue) ?>" placeholder="Isi lokasi terkini secara manual">
        </label>

        <label class="FieldBlock">
            <span>Lokasi Struktur</span>
            <small class="RequiredHint">wajib diisi</small>
            <select name="structureLocation" required>
                <option value="">Pilih lokasi struktur</option>
                <?php foreach ($structureLocationOptions as $structureLocation) : ?>
                    <option value="<?= esc($structureLocation) ?>" <?= old('structureLocation', $formData['structureLocation'] ?? '') === $structureLocation ? 'selected' : '' ?>>
                        <?= esc($structureLocation) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="FieldBlock">
            <span>Titik Struktur</span>
            <small class="RequiredHint">wajib diisi</small>
            <input type="text" name="structurePoint" value="<?= esc(old('structurePoint', $formData['structurePoint'] ?? '')) ?>" placeholder="Isi titik struktur" required>
        </label>

        <div class="UploadCard" id="section-photo">
            <strong>Dokumentasi Pekerjaan</strong>
            <small class="RequiredHint">wajib diisi</small>
            <p>Upload bisa dari galeri atau ambil foto langsung dari device.</p>
            <input type="file" name="photos[]" id="PhotoInput" accept="image/*" multiple>
            <div id="PhotoPreview" class="PhotoPreviewGrid"></div>
            <?php if ($existingPhotos !== []) : ?>
                <div class="PhotoPreviewGrid">
                    <?php foreach ($existingPhotos as $photo) : ?>
                        <img src="<?= base_url($photo['file_path']) ?>" alt="Dokumentasi tersimpan">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <label class="FieldBlock">
            <span>Keterangan Cuaca</span>
            <small class="RequiredHint">wajib diisi</small>
        </label>
        <div class="WeatherOptions">
            <?php foreach ($formOptions['weatherOptions'] as $weather) : ?>
                <label class="ChoiceChip">
                    <input type="radio" name="weatherCode" value="<?= esc($weather) ?>" <?= old('weatherCode', $formData['weatherCode'] ?? '') === $weather ? 'checked' : '' ?> required>
                    <span><?= esc($weather) ?></span>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="StickyActionBar isWizard">
            <button type="button" class="GhostButton" data-wizard-prev aria-label="Kembali ke langkah sebelumnya" title="Kembali ke langkah sebelumnya"><?= trace_icon('back') ?><span>Back</span></button>
            <button type="button" class="PrimaryButton" data-wizard-next aria-label="Lanjut ke langkah berikutnya" title="Lanjut ke langkah berikutnya"><span>Next</span><?= trace_icon('next') ?></button>
        </div>
    </section>

    <section class="FormSectionCard WizardStep" id="section-realization" data-wizard-step="3">
        <div class="CardHeading">
            <h2>3. Realisasi Pekerjaan</h2>
            <span>Detail sesuai template</span>
        </div>

        <div class="DynamicRows" data-dynamic-rows="realizationItems">
            <?php foreach ($realizationItems as $index => $item) : ?>
                <div class="DynamicRow" data-dynamic-row>
                    <label class="FieldBlock">
                        <span>Item Pekerjaan</span>
                        <input type="text" name="realizationItems[<?= esc((string) $index) ?>][work_item]" value="<?= esc($item['work_item'] ?? '') ?>" placeholder="Item pekerjaan">
                    </label>
                    <label class="FieldBlock">
                        <span>Satuan</span>
                        <input type="text" name="realizationItems[<?= esc((string) $index) ?>][unit]" value="<?= esc($item['unit'] ?? '') ?>" placeholder="m / m2 / unit">
                    </label>
                    <label class="FieldBlock">
                        <span>Rencana</span>
                        <input type="text" name="realizationItems[<?= esc((string) $index) ?>][plan_text]" value="<?= esc($item['plan_text'] ?? '') ?>" placeholder="Rencana">
                    </label>
                    <label class="FieldBlock">
                        <span>Realisasi</span>
                        <input type="text" name="realizationItems[<?= esc((string) $index) ?>][realization_text]" value="<?= esc($item['realization_text'] ?? '') ?>" placeholder="Realisasi">
                    </label>
                    <label class="FieldBlock">
                        <span>Deviasi</span>
                        <input type="text" name="realizationItems[<?= esc((string) $index) ?>][deviation_text]" value="<?= esc($item['deviation_text'] ?? '') ?>" placeholder="Deviasi">
                    </label>
                    <label class="FieldBlock">
                        <span>Rekanan</span>
                        <input type="text" name="realizationItems[<?= esc((string) $index) ?>][partner]" value="<?= esc($item['partner'] ?? '') ?>" placeholder="Rekanan">
                    </label>
                    <button type="button" class="GhostButton DynamicRemoveButton" data-remove-row>Hapus</button>
                </div>
            <?php endforeach; ?>
            <button type="button" class="PrimaryButton DynamicAddButton" data-add-row>Tambah Baris</button>
        </div>

        <div class="StickyActionBar isWizard">
            <button type="button" class="GhostButton" data-wizard-prev><?= trace_icon('back') ?><span>Back</span></button>
            <button type="button" class="PrimaryButton" data-wizard-next><span>Next</span><?= trace_icon('next') ?></button>
        </div>
    </section>

    <section class="FormSectionCard WizardStep" id="section-worker" data-wizard-step="4">
        <div class="CardHeading">
            <h2>4. Update Pekerja & Realisasi</h2>
            <span>Isi jumlah tenaga kerja yang hadir</span>
        </div>
        <div class="GridTwoColumns">
            <?php foreach ($formOptions['workerCategories'] as $category) : ?>
                <label class="BoxedCounterField">
                    <span><?= esc($category['name']) ?></span>
                    <input type="number" min="0" name="workerUpdates[<?= esc((string) $category['id']) ?>]" value="<?= esc((string) old('workerUpdates.' . $category['id'], $workerUpdates[$category['id']] ?? '')) ?>" placeholder="0">
                </label>
            <?php endforeach; ?>
        </div>

        <div class="DynamicRows" data-dynamic-rows="workerCustomRows">
            <p class="AccordionGroupTitle">Tambahan Posisi dan Jumlah</p>
            <?php foreach ($workerCustomRows as $index => $item) : ?>
                <div class="DynamicRow isTwoColumn" data-dynamic-row>
                    <label class="FieldBlock">
                        <span>Tambahan Posisi</span>
                        <input type="text" name="workerCustomRows[<?= esc((string) $index) ?>][label]" value="<?= esc($item['label'] ?? '') ?>" placeholder="Isi disini jika tidak ada pilihan">
                    </label>
                    <label class="FieldBlock">
                        <span>Jumlah</span>
                        <input type="number" min="0" name="workerCustomRows[<?= esc((string) $index) ?>][quantity]" value="<?= esc($item['quantity'] ?? '') ?>" placeholder="0">
                    </label>
                    <button type="button" class="GhostButton DynamicRemoveButton" data-remove-row>Hapus</button>
                </div>
            <?php endforeach; ?>
            <button type="button" class="PrimaryButton DynamicAddButton" data-add-row>Tambah Posisi</button>
        </div>

        <div class="StickyActionBar isWizard">
            <button type="button" class="GhostButton" data-wizard-prev><?= trace_icon('back') ?><span>Back</span></button>
            <button type="button" class="PrimaryButton" data-wizard-next><span>Next</span><?= trace_icon('next') ?></button>
        </div>
    </section>

    <section class="FormSectionCard WizardStep" id="section-heavy" data-wizard-step="5">
        <div class="CardHeading">
            <h2>5. Alat Berat, Alat Ringan & Material</h2>
            <span>Input operasional hari ini</span>
        </div>
        <div class="GridTwoColumns">
            <?php foreach ($formOptions['heavyCategories'] as $category) : ?>
                <label class="BoxedCounterField">
                    <span><?= esc($category['name']) ?></span>
                    <input type="number" min="0" name="heavyEquipment[<?= esc((string) $category['id']) ?>]" value="<?= esc((string) old('heavyEquipment.' . $category['id'], $heavyEquipment[$category['id']] ?? '')) ?>" placeholder="0">
                </label>
            <?php endforeach; ?>
        </div>

        <div class="DynamicRows" data-dynamic-rows="heavyCustomRows">
            <p class="AccordionGroupTitle">Alat Berat Tambahan</p>
            <?php foreach ($heavyCustomRows as $index => $item) : ?>
                <div class="DynamicRow isTwoColumn" data-dynamic-row>
                    <label class="FieldBlock">
                        <span>Nama Alat</span>
                        <input type="text" name="heavyCustomRows[<?= esc((string) $index) ?>][label]" value="<?= esc($item['label'] ?? '') ?>" placeholder="Isi disini jika tidak ada pilihan">
                    </label>
                    <label class="FieldBlock">
                        <span>Jumlah</span>
                        <input type="number" min="0" name="heavyCustomRows[<?= esc((string) $index) ?>][quantity]" value="<?= esc($item['quantity'] ?? '') ?>" placeholder="0">
                    </label>
                    <button type="button" class="GhostButton DynamicRemoveButton" data-remove-row>Hapus</button>
                </div>
            <?php endforeach; ?>
            <button type="button" class="PrimaryButton DynamicAddButton" data-add-row>Tambah Alat Berat</button>
        </div>

        <div class="StickyActionBar isWizard">
            <button type="button" class="GhostButton" data-wizard-prev aria-label="Kembali ke langkah sebelumnya" title="Kembali ke langkah sebelumnya"><?= trace_icon('back') ?><span>Back</span></button>
            <button type="button" class="PrimaryButton" data-wizard-next aria-label="Lanjut ke langkah berikutnya" title="Lanjut ke langkah berikutnya"><span>Next</span><?= trace_icon('next') ?></button>
        </div>
    </section>

    <section class="FormSectionCard WizardStep" id="section-light-tool" data-wizard-step="6">
        <div class="CardHeading">
            <h2>6. Alat Kerja Ringan & Material</h2>
            <span>Volume dan satuan alat ringan</span>
        </div>

        <div class="DynamicRows" data-dynamic-rows="lightTools">
            <p class="AccordionGroupTitle">Alat Kerja Ringan</p>
            <?php foreach ($lightTools as $index => $item) : ?>
                <div class="DynamicRow isThreeColumn" data-dynamic-row>
                    <label class="FieldBlock">
                        <span>Nama Alat</span>
                        <input type="text" name="lightTools[<?= esc((string) $index) ?>][tool_label]" value="<?= esc($item['tool_label'] ?? '') ?>" placeholder="Nama alat">
                    </label>
                    <label class="FieldBlock">
                        <span>Volume</span>
                        <input type="text" name="lightTools[<?= esc((string) $index) ?>][volume]" value="<?= esc($item['volume'] ?? '') ?>" placeholder="Volume">
                    </label>
                    <label class="FieldBlock">
                        <span>Satuan</span>
                        <input type="text" name="lightTools[<?= esc((string) $index) ?>][unit]" value="<?= esc($item['unit'] ?? '') ?>" placeholder="pcs / unit">
                    </label>
                    <button type="button" class="GhostButton DynamicRemoveButton" data-remove-row>Hapus</button>
                </div>
            <?php endforeach; ?>
            <button type="button" class="PrimaryButton DynamicAddButton" data-add-row>Tambah Alat Ringan</button>
        </div>

        <label class="FieldBlock" id="section-material">
            <span>Material & Bahan Kerja</span>
            <small class="RequiredHint">wajib diisi</small>
            <textarea name="materialSummary" rows="4" placeholder="Contoh: menggunakan Material XXX dan Bahan ZZZ untuk pekerjaan AAA" required><?= esc(old('materialSummary', $formData['materialSummary'] ?? '')) ?></textarea>
        </label>

        <div class="StickyActionBar isWizard">
            <button type="button" class="GhostButton" data-wizard-prev><?= trace_icon('back') ?><span>Back</span></button>
            <button type="button" class="PrimaryButton" data-wizard-next><span>Next</span><?= trace_icon('next') ?></button>
        </div>
    </section>

    <section class="FormSectionCard WizardStep" id="section-obstacle" data-wizard-step="7">
        <div class="CardHeading">
            <h2>7. Kendala, Rencana Esok & Lembur</h2>
            <span>Lengkapi penutup laporan (Opsional)</span>
        </div>

        <div class="FieldGrid">
            <label class="FieldBlock">
                <span>Bentuk Kendala</span>
                <input type="text" name="obstacleShape" value="<?= esc(old('obstacleShape', $formData['obstacleShape'] ?? '')) ?>">
            </label>
            <label class="FieldBlock">
                <span>Penyebab Kendala</span>
                <input type="text" name="obstacleCause" value="<?= esc(old('obstacleCause', $formData['obstacleCause'] ?? '')) ?>">
            </label>
        </div>

        <label class="FieldBlock">
            <span>Dampak Pekerjaan</span>
            <input type="text" name="obstacleImpact" value="<?= esc(old('obstacleImpact', $formData['obstacleImpact'] ?? '')) ?>">
        </label>

        <label class="FieldBlock">
            <span>Penjelasan Tambahan</span>
            <textarea name="obstacleNote" rows="3" placeholder="Bila diperlukan"><?= esc(old('obstacleNote', $formData['obstacleNote'] ?? '')) ?></textarea>
        </label>

        <label class="FieldBlock">
            <span>Rencana Pekerjaan Esok</span>
            <textarea name="tomorrowPlan" rows="5" placeholder="Contoh: Besok melanjutkan sisa pekerjaan..."><?= esc(old('tomorrowPlan', $formData['tomorrowPlan'] ?? '')) ?></textarea>
        </label>

        <div class="FieldGrid">
            <label class="FieldBlock">
                <span>Apakah Ada Lembur?</span>
                <select name="overtimeEnabled" id="OvertimeToggle">
                    <option value="0" <?= old('overtimeEnabled', $formData['overtimeEnabled'] ?? '0') === '0' ? 'selected' : '' ?>>Tidak</option>
                    <option value="1" <?= old('overtimeEnabled', $formData['overtimeEnabled'] ?? '0') === '1' ? 'selected' : '' ?>>Ya</option>
                </select>
            </label>
            <label class="FieldBlock">
                <span>Ringkasan Lembur</span>
                <input type="text" name="overtimeSummary" value="<?= esc(old('overtimeSummary', $formData['overtimeSummary'] ?? '')) ?>" placeholder="Opsional">
            </label>
        </div>

        <div class="FieldGrid" id="OvertimeFields">
            <label class="FieldBlock">
                <span>Jam Mulai</span>
                <input type="time" name="overtimeStart" value="<?= esc(old('overtimeStart', $formData['overtimeStart'] ?? '18:00')) ?>">
            </label>
            <label class="FieldBlock">
                <span>Jam Selesai</span>
                <input type="time" name="overtimeEnd" value="<?= esc(old('overtimeEnd', $formData['overtimeEnd'] ?? '19:00')) ?>">
            </label>
        </div>

        <br>
        <?= view('Components/AutoSendWAToggle', [
            'toggleId' => 'CreateAutoSendWaToggle',
            'hint'     => 'Preferensi ini dipakai saat Anda submit final laporan dari halaman review.',
        ]) ?>

        <div class="StickyActionBar isWizard">
            <button type="button" class="GhostButton" data-wizard-prev aria-label="Kembali ke langkah sebelumnya" title="Kembali ke langkah sebelumnya"><?= trace_icon('back') ?><span>Back</span></button>
            <button type="submit" class="PrimaryButton">Simpan Draft & Review</button>
        </div>
    </section>
</form>

<div class="ReportDraftPrompt" id="ReportDraftPrompt" hidden>
    <div class="ReportDraftDialog">
        <strong>Simpan draft?</strong>
        <p>Data yang sudah Anda ketik akan disimpan sebagai draft di perangkat ini sebelum keluar dari halaman.</p>
        <div class="ReportDraftActions">
            <button type="button" class="PrimaryButton" data-draft-save-exit>Simpan Draft</button>
            <button type="button" class="GhostButton" data-draft-stay>Lanjut</button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
