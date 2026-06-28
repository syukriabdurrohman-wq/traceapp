<?= $this->extend('Layouts/MobileLayout') ?>

<?= $this->section('content') ?>
<?php
$workerUpdates    = $formData['workerUpdates'] ?? [];
$heavyEquipment   = $formData['heavyEquipment'] ?? [];
$heavyEquipmentSelections = old('heavyEquipmentSelections', $formData['heavyEquipmentSelections'] ?? []);
$heavyEquipmentManual = old('heavyEquipmentManual', $formData['heavyEquipmentManual'] ?? []);
$existingPhotos   = $reportBundle['photos'] ?? [];
$currentStep      = (int) old('currentStep', $formData['currentStep'] ?? 1);
$realizationItems = old('realizationItems', $formData['realizationItems'] ?? []);
$workerCustomRows = old('workerCustomRows', $formData['workerCustomRows'] ?? []);
$heavyCustomRows  = old('heavyCustomRows', $formData['heavyCustomRows'] ?? []);
$lightTools       = old('lightTools', $formData['lightTools'] ?? []);
$lightToolCounts  = old('lightToolCounts', $formData['lightToolCounts'] ?? []);
$lightToolSelections = old('lightToolSelections', $formData['lightToolSelections'] ?? []);
$lightToolManual  = old('lightToolManual', $formData['lightToolManual'] ?? []);
$currentLocationOptions = $formOptions['currentLocations'] ?? ['Area Laut', 'Area Swangi', 'Area Lanal', 'Area RPI', 'Lainnya'];
$structureLocationOptions = $formOptions['structureLocations'] ?? ['PL1', 'PL2', 'P22', 'P23', 'P24', 'P25', 'P26', 'P27', 'P28', 'P29', 'P30', 'P31', 'P32', 'P33', 'P34', 'Fender PL1', 'Fender PL2', 'Fender P22', 'Fender P23'];
$currentLocationValue = old('currentLocation', $formData['currentLocation'] ?? '');
$isCustomCurrentLocation = $currentLocationValue !== '' && ! in_array($currentLocationValue, $currentLocationOptions, true);
$currentLocationSelectValue = $isCustomCurrentLocation ? 'Lainnya' : $currentLocationValue;
$currentLocationManualValue = old('currentLocationManual', $isCustomCurrentLocation ? $currentLocationValue : '');
$workItemOptions = [
    'Pekerjaan Preboring',
    'Pekerjaan Cleaning Core',
    'Pekerjaan Cleaning Rock',
    'Pekerjaan Install & Pancang Casing',
    'Pekerjaan Bracing',
    'Pekerjaan Pengecoran',
    'Pekerjaan Pilecap',
    'Pekerjaan Kolom',
    'Pekerjaan Pierhead',
    'Pekerjaan Bearing Pad',
    'Pekerjaan Cross Beam',
    'Pekerjaan Deck',
    'Pekerjaan Perbaikan Alat & Iddle Alat',
];
$workItemAliases = [
    'Preboring' => 'Pekerjaan Preboring',
    'Cleaning Core' => 'Pekerjaan Cleaning Core',
    'Cleaning Rock' => 'Pekerjaan Cleaning Rock',
    'Install Casing' => 'Pekerjaan Install & Pancang Casing',
    'Bracing' => 'Pekerjaan Bracing',
    'Pengecoran' => 'Pekerjaan Pengecoran',
    'Pekerjaan Bearding Pad' => 'Pekerjaan Bearing Pad',
    'Pekerjaan Barcing' => 'Pekerjaan Bracing',
];
$partnerOptions = ['RPI', 'Berdikari'];
$heavyDropdownOptions = [
    'tongkang' => ['Palmindo', 'PCF-1861', 'PCF-1865', 'Aquaria', 'BDU', 'Pipe Carrier', 'Bayu Bahtera'],
    'boring-machine' => ['SR 405', 'SR 215', 'SR 265', 'XR 280'],
    'crane' => ['Kobelco 7150', 'QUY 150', 'LS 248 RH', 'BM 800 HD', 'SC800', 'Hitachi 275 DC'],
];
$lightCounterOptions = [
    'genset' => 'Genset',
    'winch' => 'Winch',
    'guide-beam' => 'Guide Beam',
    'trafo-las' => 'Trafo Las',
    'bar-bender' => 'Bar Bender',
    'bar-cutter' => 'Bar Cutter',
    'gerinda' => 'Gerinda',
    'alat-surveying' => 'Alat Surveying (per Set)',
];
$lightDropdownOptions = [
    'core-barrel' => [
        'label' => 'Core Barrel',
        'options' => ['Dia. 1', 'Dia. 1,2', 'Dia. 1,4', 'Dia. 1,5', 'Dia. 1,6', 'Dia. 1,7', 'Dia. 1,8', 'Dia. 2,0', 'Dia. 2,2', 'Dia. 2,4'],
    ],
    'bucket-barrel' => [
        'label' => 'Bucket Barrel',
        'options' => ['Dia. 1', 'Dia. 1,2', 'Dia. 1,4', 'Dia. 1,5', 'Dia. 1,6', 'Dia. 1,7', 'Dia. 2,0'],
    ],
];

$realizationItems = is_array($realizationItems) && $realizationItems !== [] ? $realizationItems : [['work_item' => '', 'unit' => '', 'plan_text' => '', 'realization_text' => '', 'deviation_text' => '', 'partner' => '']];
$workerCustomRows = is_array($workerCustomRows) && $workerCustomRows !== [] ? $workerCustomRows : [['label' => '', 'quantity' => '']];
$heavyCustomRows  = is_array($heavyCustomRows) && $heavyCustomRows !== [] ? $heavyCustomRows : [['label' => '', 'quantity' => '']];
$lightToolCounts = is_array($lightToolCounts) ? $lightToolCounts : [];
$lightToolSelections = is_array($lightToolSelections) ? $lightToolSelections : [];
$lightToolManual = is_array($lightToolManual) ? $lightToolManual : [];
$lightTools = is_array($lightTools) ? $lightTools : [];

if ($lightToolCounts === [] && $lightToolSelections === [] && $lightTools !== []) {
    $customLightTools = [];

    foreach ($lightTools as $lightTool) {
        $toolLabel = trim((string) ($lightTool['tool_label'] ?? ''));
        $toolVolume = trim((string) ($lightTool['volume'] ?? ''));
        $matched = false;

        foreach ($lightCounterOptions as $slug => $label) {
            if (strcasecmp($toolLabel, $label) === 0) {
                $lightToolCounts[$slug] = $toolVolume;
                $matched = true;
                break;
            }
        }

        if ($matched) {
            continue;
        }

        foreach ($lightDropdownOptions as $slug => $config) {
            $prefix = $config['label'] . ' - ';
            if (str_starts_with($toolLabel, $prefix)) {
                $selection = trim(substr($toolLabel, strlen($prefix)));
                $lightToolSelections[$slug][] = $selection;
                $matched = true;
                break;
            }
        }

        if (! $matched) {
            $customLightTools[] = $lightTool;
        }
    }

    $lightTools = $customLightTools;
}

$lightTools = $lightTools !== [] ? $lightTools : [['tool_label' => '', 'volume' => '', 'unit' => '']];
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
        border: 1px solid #d1d5db;
        border-radius: 12px;
        padding: 12px;
        background: #f3f4f6;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.06);
    }

    .BoxedCounterField span {
        font-size: 13px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 8px;
    }

    .BoxedCounterField input,
    .BoxedCounterField select {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 14px;
        width: 100%;
        background: #ffffff;
        color: #111827;
        text-align: left;
        outline: none;
    }

    .BoxedCounterField input:focus,
    .BoxedCounterField select:focus {
        border-color: #9ca3af;
        box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.16);
        background: #ffffff;
    }

    .BoxedCounterField .HeavyManualInput {
        margin-top: 8px;
    }

    .LightToolMultiCard {
        display: grid;
        gap: 10px;
        margin-bottom: 16px;
    }

    .LightToolMultiCard > strong {
        color: #374151;
        font-size: 0.78rem;
    }

    .LightToolMultiGrid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .LightToolMultiGrid .BoxedCounterField select {
        padding: 8px 9px;
        font-size: 13px;
        min-height: 38px;
    }

    .LightToolMultiGrid .BoxedCounterField span {
        font-size: 12px;
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

    .AccordionGroupHint {
        margin: -6px 0 4px;
        font-size: 11px;
        line-height: 1.35;
        color: #6b7280;
        font-weight: 600;
    }

</style>

<?= view('Components/PageHeader', [
    'eyebrow' => 'Input Laporan Harian',
    'title' => $pageTitle ?? 'Input Laporan',
    'subtitle' => 'Satu form lengkap untuk seluruh aktivitas pekerjaan harian lapangan.',
]) ?>

<form method="post" action="<?= base_url('reports/save-draft') ?>" enctype="multipart/form-data" class="StackForm" id="ReportWizardForm" data-step="<?= esc((string) max(1, min(7, $currentStep))) ?>" data-draft-key="<?= esc('trace-report-draft:' . ($currentUser['id'] ?? 'guest') . ':' . ($formData['reportId'] ?? 'new')) ?>" novalidate>
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
                <input type="date" name="reportDate" value="<?= esc(old('reportDate', $formData['reportDate'] ?? '')) ?>">
            </label>

            <label class="FieldBlock">
                <span>Supervisor / Pelaksana</span>
                <small class="RequiredHint">wajib diisi</small>
                <select name="workerUserId">
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
            <select name="currentLocation" id="CurrentLocationSelect">
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

        <label class="FieldBlock" id="StructureLocationField">
            <span>Lokasi Struktur</span>
            <small class="RequiredHint">wajib diisi jika memilih Area Laut</small>
            <select name="structureLocation" id="StructureLocationSelect">
                <option value="">Pilih lokasi struktur</option>
                <?php foreach ($structureLocationOptions as $structureLocation) : ?>
                    <option value="<?= esc($structureLocation) ?>" <?= old('structureLocation', $formData['structureLocation'] ?? '') === $structureLocation ? 'selected' : '' ?>>
                        <?= esc($structureLocation) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="FieldBlock" id="StructurePointField">
            <span>Titik Struktur</span>
            <small class="RequiredHint">wajib diisi jika memilih Area Laut</small>
            <input type="text" name="structurePoint" id="StructurePointInput" value="<?= esc(old('structurePoint', $formData['structurePoint'] ?? '')) ?>" placeholder="Isi titik struktur">
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
                    <input type="radio" name="weatherCode" value="<?= esc($weather) ?>" <?= old('weatherCode', $formData['weatherCode'] ?? '') === $weather ? 'checked' : '' ?>>
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
                <?php
                $workItemValue = trim((string) ($item['work_item'] ?? ''));
                $workItemManualValue = trim((string) ($item['work_item_manual'] ?? ''));
                $workItemValue = $workItemAliases[$workItemValue] ?? $workItemValue;
                $workItemSelectValue = in_array($workItemValue, $workItemOptions, true) ? $workItemValue : ($workItemValue !== '' || $workItemManualValue !== '' ? 'Lainnya' : '');
                $workItemManualValue = $workItemManualValue !== '' ? $workItemManualValue : ($workItemSelectValue === 'Lainnya' ? $workItemValue : '');
                $partnerValue = trim((string) ($item['partner'] ?? ''));
                $partnerManualValue = trim((string) ($item['partner_manual'] ?? ''));
                $partnerSelectValue = in_array($partnerValue, $partnerOptions, true) ? $partnerValue : ($partnerValue !== '' || $partnerManualValue !== '' ? 'Lainnya' : '');
                $partnerManualValue = $partnerManualValue !== '' ? $partnerManualValue : ($partnerSelectValue === 'Lainnya' ? $partnerValue : '');
                ?>
                <div class="DynamicRow" data-dynamic-row>
                    <label class="FieldBlock">
                        <span>Deskripsi Pekerjaan</span>
                        <select name="realizationItems[<?= esc((string) $index) ?>][work_item]" data-work-item-select>
                            <option value="">Pilih deskripsi pekerjaan</option>
                            <?php foreach ($workItemOptions as $workItemOption) : ?>
                                <option value="<?= esc($workItemOption) ?>" <?= $workItemSelectValue === $workItemOption ? 'selected' : '' ?>>
                                    <?= esc($workItemOption) ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="Lainnya" <?= $workItemSelectValue === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                    </label>
                    <label class="FieldBlock" data-work-item-manual-field>
                        <span>Deskripsi Pekerjaan Lainnya</span>
                        <input type="text" name="realizationItems[<?= esc((string) $index) ?>][work_item_manual]" value="<?= esc($workItemManualValue) ?>" placeholder="Isi deskripsi pekerjaan lainnya" data-work-item-manual-input>
                    </label>
                    <label class="FieldBlock">
                        <span>Satuan</span>
                        <input type="text" name="realizationItems[<?= esc((string) $index) ?>][unit]" value="<?= esc($item['unit'] ?? '') ?>" placeholder="m / m2 / unit">
                    </label>
                    <label class="FieldBlock">
                        <span>Rencana</span>
                        <input type="text" name="realizationItems[<?= esc((string) $index) ?>][plan_text]" value="<?= esc($item['plan_text'] ?? '') ?>" placeholder="Rencana" data-plan-input>
                    </label>
                    <label class="FieldBlock">
                        <span>Realisasi</span>
                        <input type="text" name="realizationItems[<?= esc((string) $index) ?>][realization_text]" value="<?= esc($item['realization_text'] ?? '') ?>" placeholder="Realisasi" data-realization-input>
                    </label>
                    <label class="FieldBlock">
                        <span>Deviasi</span>
                        <input type="text" name="realizationItems[<?= esc((string) $index) ?>][deviation_text]" value="<?= esc($item['deviation_text'] ?? '') ?>" placeholder="Otomatis" readonly data-deviation-input>
                    </label>
                    <label class="FieldBlock">
                        <span>Rekanan</span>
                        <select name="realizationItems[<?= esc((string) $index) ?>][partner]" data-partner-select>
                            <option value="">Pilih rekanan</option>
                            <?php foreach ($partnerOptions as $partnerOption) : ?>
                                <option value="<?= esc($partnerOption) ?>" <?= $partnerSelectValue === $partnerOption ? 'selected' : '' ?>>
                                    <?= esc($partnerOption) ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="Lainnya" <?= $partnerSelectValue === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                    </label>
                    <label class="FieldBlock" data-partner-manual-field>
                        <span>Rekanan Lainnya</span>
                        <input type="text" name="realizationItems[<?= esc((string) $index) ?>][partner_manual]" value="<?= esc($partnerManualValue) ?>" placeholder="Isi rekanan lainnya" data-partner-manual-input>
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
                <?php
                $categoryId = (string) $category['id'];
                $categorySlug = (string) ($category['slug'] ?? '');
                $dropdownOptions = $heavyDropdownOptions[$categorySlug] ?? null;
                $selectionValue = trim((string) ($heavyEquipmentSelections[$category['id']] ?? ''));
                $manualValue = trim((string) ($heavyEquipmentManual[$category['id']] ?? ''));
                $selectValue = $dropdownOptions !== null && in_array($selectionValue, $dropdownOptions, true) ? $selectionValue : ($selectionValue !== '' || $manualValue !== '' ? 'Lainnya' : '');
                $manualValue = $manualValue !== '' ? $manualValue : ($selectValue === 'Lainnya' ? $selectionValue : '');
                ?>
                <label class="BoxedCounterField">
                    <span><?= esc($category['name']) ?></span>
                    <?php if ($dropdownOptions !== null) : ?>
                        <select name="heavyEquipmentSelections[<?= esc($categoryId) ?>]" data-heavy-select>
                            <option value="">Pilih <?= esc($category['name']) ?></option>
                            <?php foreach ($dropdownOptions as $dropdownOption) : ?>
                                <option value="<?= esc($dropdownOption) ?>" <?= $selectValue === $dropdownOption ? 'selected' : '' ?>>
                                    <?= esc($dropdownOption) ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="Lainnya" <?= $selectValue === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                        </select>
                        <input class="HeavyManualInput" type="text" name="heavyEquipmentManual[<?= esc($categoryId) ?>]" value="<?= esc($manualValue) ?>" placeholder="Isi <?= esc($category['name']) ?> lainnya" data-heavy-manual>
                    <?php else : ?>
                        <input type="number" min="0" name="heavyEquipment[<?= esc($categoryId) ?>]" value="<?= esc((string) old('heavyEquipment.' . $category['id'], $heavyEquipment[$category['id']] ?? '')) ?>" placeholder="0">
                    <?php endif; ?>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="DynamicRows" data-dynamic-rows="heavyCustomRows">
            <p class="AccordionGroupTitle">Alat Berat Tambahan</p>
            <p class="AccordionGroupHint">Tulis Disini Jika Tidak Ada Pilihan Diatas</p>
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

        <div class="GridTwoColumns">
            <?php foreach ($lightCounterOptions as $slug => $label) : ?>
                <label class="BoxedCounterField">
                    <span><?= esc($label) ?></span>
                    <input type="number" min="0" name="lightToolCounts[<?= esc($slug) ?>]" value="<?= esc((string) ($lightToolCounts[$slug] ?? '')) ?>" placeholder="0">
                </label>
            <?php endforeach; ?>

        </div>

        <?php foreach ($lightDropdownOptions as $slug => $config) : ?>
            <?php
            $selectionValues = $lightToolSelections[$slug] ?? [];
            $manualValues = $lightToolManual[$slug] ?? [];
            $selectionValues = is_array($selectionValues) ? array_values($selectionValues) : [$selectionValues];
            $manualValues = is_array($manualValues) ? array_values($manualValues) : [$manualValues];
            ?>
            <div class="LightToolMultiCard">
                <strong><?= esc($config['label']) ?></strong>
                <div class="LightToolMultiGrid">
                    <?php for ($slotIndex = 0; $slotIndex < 4; $slotIndex++) : ?>
                        <?php
                        $selectionValue = trim((string) ($selectionValues[$slotIndex] ?? ''));
                        $manualValue = trim((string) ($manualValues[$slotIndex] ?? ''));
                        $selectValue = in_array($selectionValue, $config['options'], true) ? $selectionValue : ($selectionValue !== '' || $manualValue !== '' ? 'Lainnya' : '');
                        $manualValue = $manualValue !== '' ? $manualValue : ($selectValue === 'Lainnya' ? $selectionValue : '');
                        ?>
                        <label class="BoxedCounterField">
                            <span><?= esc($config['label']) ?> <?= esc((string) ($slotIndex + 1)) ?></span>
                            <select name="lightToolSelections[<?= esc($slug) ?>][<?= esc((string) $slotIndex) ?>]" data-light-select>
                                <option value="">Pilih diameter</option>
                                <?php foreach ($config['options'] as $option) : ?>
                                    <option value="<?= esc($option) ?>" <?= $selectValue === $option ? 'selected' : '' ?>>
                                        <?= esc($option) ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="Lainnya" <?= $selectValue === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                            <input class="HeavyManualInput" type="text" name="lightToolManual[<?= esc($slug) ?>][<?= esc((string) $slotIndex) ?>]" value="<?= esc($manualValue) ?>" placeholder="Isi diameter lainnya" data-light-manual>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="DynamicRows" data-dynamic-rows="lightTools">
            <p class="AccordionGroupTitle">Alat Ringan Lainnya</p>
            <p class="AccordionGroupHint">Tulis Disini Jika Tidak Ada Pilihan Diatas</p>
            <?php foreach ($lightTools as $index => $item) : ?>
                <div class="DynamicRow isThreeColumn" data-dynamic-row>
                    <label class="FieldBlock">
                        <span>Nama Alat Ringan</span>
                        <input type="text" name="lightTools[<?= esc((string) $index) ?>][tool_label]" value="<?= esc($item['tool_label'] ?? '') ?>" placeholder="Nama alat">
                    </label>
                    <label class="FieldBlock">
                        <span>Jumlah Alat</span>
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
            <textarea name="materialSummary" rows="4" placeholder="Contoh: menggunakan Material XXX dan Bahan ZZZ untuk pekerjaan AAA"><?= esc(old('materialSummary', $formData['materialSummary'] ?? '')) ?></textarea>
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

        <label class="FieldBlock">
            <span>Bentuk Kendala</span>
            <textarea name="obstacleShape" rows="4" placeholder="Contoh: tidak ada kendala, cuaca buruk, akses material terlambat"><?= esc(old('obstacleShape', $formData['obstacleShape'] ?? '')) ?></textarea>
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
