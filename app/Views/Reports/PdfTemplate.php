<?php
$report   = $bundle['report'] ?? [];
$worker   = $bundle['worker'] ?? [];
$location = $bundle['location'] ?? [];
$photos   = $bundle['photos'] ?? [];

$embedImage = static function (string $path): string {
    if (! is_file($path) || filesize($path) === 0) {
        return '';
    }

    $mime = mime_content_type($path) ?: 'image/png';

    return 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($path));
};

$formatValue = static function (?string $value): string {
    $value = trim((string) $value);

    return $value !== '' ? $value : '-';
};

$formatDate = static function (?string $value): string {
    if (! $value) {
        return '-';
    }

    $timestamp = strtotime($value);

    return $timestamp ? date('d M Y', $timestamp) : (string) $value;
};

$splitTextLines = static function (?string $text): array {
    $normalized = trim(str_replace("\r", "\n", (string) $text));

    if ($normalized === '') {
        return [];
    }

    $parts = preg_split('/\n+/', $normalized) ?: [];
    $parts = array_values(array_filter(array_map(static fn (string $line): string => trim($line), $parts), static fn (string $line): bool => $line !== ''));

    if ($parts !== []) {
        return $parts;
    }

    return [$normalized];
};

$buildObstacleText = static function (array $obstacle) use ($formatValue): string {
    $lines = [];

    if (trim((string) ($obstacle['obstacle_shape'] ?? '')) !== '') {
        $lines[] = 'Bentuk: ' . $formatValue($obstacle['obstacle_shape']);
    }

    if (trim((string) ($obstacle['obstacle_cause'] ?? '')) !== '') {
        $lines[] = 'Penyebab: ' . $formatValue($obstacle['obstacle_cause']);
    }

    if (trim((string) ($obstacle['obstacle_impact'] ?? '')) !== '') {
        $lines[] = 'Dampak: ' . $formatValue($obstacle['obstacle_impact']);
    }

    return $lines === [] ? '-' : implode("\n", $lines);
};

$photoSources = [];
foreach ($photos as $photo) {
    $photoPath = FCPATH . str_replace('/', DIRECTORY_SEPARATOR, (string) ($photo['file_path'] ?? ''));
    $photoSrc  = $embedImage($photoPath);

    if ($photoSrc !== '') {
        $photoSources[] = $photoSrc;
    }
}

$workerRows = $bundle['workerUpdates'] ?? [];
$heavyRows  = $bundle['heavyEquipment'] ?? [];
$realizationRows = $bundle['realizationItems'] ?? [];
$lightToolRows = $bundle['lightTools'] ?? [];
$materialLines = $splitTextLines($bundle['material']['summary_text'] ?? '');
$toolLines     = $splitTextLines($bundle['tool']['summary_text'] ?? '');
$realizationLines = $splitTextLines($report['realization_summary'] ?? '');
$tomorrowLines    = $splitTextLines($bundle['tomorrow']['summary_text'] ?? '');

$hasOvertime = (int) ($bundle['overtime']['is_enabled'] ?? 0) === 1;
$overtimeValue = $hasOvertime
    ? trim((string) ($bundle['overtime']['start_time'] ?? '-')) . ' - ' . trim((string) ($bundle['overtime']['end_time'] ?? '-'))
    : '-';

$normalWorkValue = '-';
$reportDateValue = $formatDate($report['report_date'] ?? null);
$reportCodeValue = $formatValue($report['report_code'] ?? '');
$statusValue     = $formatValue($report['status'] ?? '');
$targetDateValue = ! empty($report['report_date']) ? date('d M Y', strtotime((string) $report['report_date'] . ' +1 day')) : '-';
$photoCount      = count($photoSources);

$photoPerRow = 3;
$photoBoxSize = '150px';

if ($photoCount <= 1) {
    $photoPerRow = 1;
    $photoBoxSize = '310px';
} elseif ($photoCount === 2 || $photoCount === 4) {
    $photoPerRow = 2;
    $photoBoxSize = '230px';
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 24px 26px;
            size: A4 portrait;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #1f2937;
            line-height: 1.45;
        }

        * {
            box-sizing: border-box;
        }

        .LetterHead {
            width: 100%;
            border-bottom: 3px solid #183b5b;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .LetterHeadTable {
            width: 100%;
            border-collapse: collapse;
        }

        .LogoCell {
            width: 95px;
            vertical-align: middle;
        }

        .LogoImg {
            width: 78px;
            max-height: 54px;
        }

        .HeadTextCell {
            vertical-align: middle;
            padding-left: 8px;
        }

        .BrandName {
            margin: 0;
            font-size: 17px;
            font-weight: bold;
            color: #183b5b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .ReportTitle {
            margin: 3px 0 0;
            font-size: 13px;
            font-weight: bold;
            color: #d71920;
            text-transform: uppercase;
        }

        .ReportSubtitle {
            margin: 3px 0 0;
            font-size: 8.5px;
            color: #64748b;
        }

        .HeadInfoCell {
            width: 170px;
            vertical-align: middle;
            text-align: right;
        }

        .HeadInfoBox {
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            padding: 7px 8px;
            font-size: 8.5px;
            color: #334155;
        }

        .HeadInfoCode {
            font-size: 10px;
            font-weight: bold;
            color: #183b5b;
            margin-bottom: 3px;
        }

        .RedLine {
            height: 1px;
            background: #d71920;
            margin-top: 4px;
        }

        .MetaTable {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
            margin: 0 -6px 10px;
        }

        .MetaCell {
            width: 33.333%;
            vertical-align: top;
        }

        .MetaBox {
            border: 1px solid #d6dee8;
            background: #f8fafc;
            min-height: 48px;
        }

        .MetaLabel {
            padding: 5px 8px;
            background: #183b5b;
            color: #ffffff;
            font-size: 7.8px;
            font-weight: bold;
            letter-spacing: 0.7px;
            text-transform: uppercase;
        }

        .MetaValue {
            padding: 7px 8px;
            color: #111827;
            font-size: 9.5px;
            font-weight: bold;
            word-break: break-word;
        }

        .Section {
            margin-top: 13px;
        }

        .SectionTitle {
            margin: 0 0 7px;
            padding: 7px 10px;
            border-left: 5px solid #d71920;
            background: #183b5b;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            page-break-after: avoid;
        }

        .Panel {
            background: #f8fafc;
            border: 1px solid #d6dee8;
            padding: 9px 10px;
            word-break: break-word;
        }

        .SummaryTable,
        .DataTable {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }

        .SummaryTable tr,
        .DataTable tr {
            page-break-inside: avoid;
        }

        thead {
            display: table-header-group;
        }

        .SummaryTable td {
            border: 1px solid #d6dee8;
            background: #f8fafc;
            padding: 8px 9px;
            vertical-align: top;
        }

        .SummaryLabel {
            width: 19%;
            font-weight: bold;
            color: #183b5b;
            background: #eef4fb !important;
        }

        .DataTable th,
        .DataTable td {
            border: 1px solid #d6dee8;
            padding: 8px 9px;
            vertical-align: top;
        }

        .DataTable th {
            background: #183b5b;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .DataTable td {
            background: #f8fafc;
        }

        .TextCenter {
            text-align: center;
        }

        .MutedCell {
            color: #64748b;
            font-style: italic;
        }

        .ListBlock {
            margin: 0;
            padding-left: 16px;
        }

        .ListBlock li {
            margin: 0 0 4px;
        }

        .PhotoTable {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin: 0 -8px;
        }

        .PhotoCell {
            text-align: center;
            vertical-align: top;
            page-break-inside: avoid;
        }

        .PhotoFrame {
            display: inline-block;
            width: <?= $photoBoxSize ?>;
            height: <?= $photoBoxSize ?>;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            padding: 7px;
            text-align: center;
            vertical-align: middle;
        }

        .PhotoInner {
            width: 100%;
            height: 100%;
            border: 1px solid #e2e8f0;
            background: #eef2f7;
            text-align: center;
            vertical-align: middle;
        }

        .PhotoInner img {
            max-width: 100%;
            max-height: 100%;
            vertical-align: middle;
        }

        .PhotoCaption {
            margin-top: 5px;
            font-size: 8px;
            color: #475569;
            text-align: center;
        }

        .FooterNote {
            margin-top: 16px;
            padding-top: 8px;
            border-top: 1px solid #cbd5e1;
            color: #64748b;
            font-size: 8px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="LetterHead">
        <table class="LetterHeadTable">
            <tr>
                <td class="LogoCell">
                    <img class="LogoImg" src="https://traceapp.bytecorner.site/Assets/Image/logo.png" alt="Logo">
                </td>
                <td class="HeadTextCell">
                    <p class="BrandName"><?= esc(trace_app_brand()) ?></p>
                    <p class="ReportTitle">Monitoring Progres Harian</p>
                    <p class="ReportSubtitle">Laporan dokumentasi pekerjaan, realisasi, kendala, dan rencana kerja harian.</p>
                </td>
                <td class="HeadInfoCell">
                    <div class="HeadInfoBox">
                        <div class="HeadInfoCode"><?= esc($reportCodeValue) ?></div>
                        <div>Tanggal: <?= esc($reportDateValue) ?></div>
                        <div>Status: <?= esc($statusValue) ?></div>
                    </div>
                </td>
            </tr>
        </table>
        <div class="RedLine"></div>
    </div>

    <table class="MetaTable">
        <tr>
            <td class="MetaCell">
                <div class="MetaBox">
                    <div class="MetaLabel">Tanggal</div>
                    <div class="MetaValue"><?= esc($reportDateValue) ?></div>
                </div>
            </td>
            <td class="MetaCell">
                <div class="MetaBox">
                    <div class="MetaLabel">Kondisi Cuaca</div>
                    <div class="MetaValue"><?= esc($formatValue($report['weather_code'] ?? '')) ?></div>
                </div>
            </td>
            <td class="MetaCell">
                <div class="MetaBox">
                    <div class="MetaLabel">Jam Kerja Normal</div>
                    <div class="MetaValue">07:00 - 17:00</div>
                </div>
            </td>
        </tr>
        <tr>
            <td class="MetaCell">
                <div class="MetaBox">
                    <div class="MetaLabel">Nama Pelaksana</div>
                    <div class="MetaValue"><?= esc($formatValue($worker['full_name'] ?? '')) ?></div>
                </div>
            </td>
            <td class="MetaCell">
                <div class="MetaBox">
                    <div class="MetaLabel">Lokasi Pekerjaan</div>
                    <div class="MetaValue"><?= esc($formatValue(($location['area_label'] ?? '-') . ' - ' . ($location['current_location'] ?? '-'))) ?></div>
                </div>
            </td>
            <td class="MetaCell">
                <div class="MetaBox">
                    <div class="MetaLabel">Jam Kerja Lembur</div>
                    <div class="MetaValue"><?= esc($overtimeValue) ?></div>
                </div>
            </td>
        </tr>
    </table>

    <div class="Section">
        <p class="SectionTitle">Resume Realisasi Kerja Hari Ini</p>
        <table class="SummaryTable">
            <tr>
                <td class="SummaryLabel">Kode Laporan</td>
                <td><?= esc($reportCodeValue) ?></td>
                <td class="SummaryLabel">Status</td>
                <td><?= esc($statusValue) ?></td>
            </tr>
            <tr>
                <td class="SummaryLabel">Created By</td>
                <td><?= esc($formatValue($report['creator_name'] ?? '')) ?></td>
                <td class="SummaryLabel">Area</td>
                <td><?= esc($formatValue($location['area_label'] ?? '')) ?></td>
            </tr>
            <tr>
                <td class="SummaryLabel">Lokasi</td>
                <td colspan="3"><?= esc($formatValue($location['current_location'] ?? '')) ?></td>
            </tr>
            <?php if (trim((string) ($location['reason'] ?? '')) !== '') : ?>
                <tr>
                    <td class="SummaryLabel">Keterangan Lokasi</td>
                    <td colspan="3"><?= nl2br(esc($location['reason'])) ?></td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($report['edited_at'])) : ?>
                <tr>
                    <td class="SummaryLabel">Diedit Pada</td>
                    <td colspan="3" style="color: #d71920; font-weight: bold;"><?= date('d F Y H:i:s', strtotime($report['edited_at'])) ?></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

    <div class="Section">
        <p class="SectionTitle">Detail Realisasi Pekerjaan</p>
        <table class="DataTable">
            <thead>
                <tr>
                    <th>Item Pekerjaan</th>
                    <th>Satuan</th>
                    <th>Rencana</th>
                    <th>Realisasi</th>
                    <th>Deviasi</th>
                    <th>Rekanan</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($realizationRows !== []) : ?>
                    <?php foreach ($realizationRows as $item) : ?>
                        <tr>
                            <td><?= esc($formatValue($item['work_item'] ?? '')) ?></td>
                            <td><?= esc($formatValue($item['unit'] ?? '')) ?></td>
                            <td><?= esc($formatValue($item['plan_text'] ?? '')) ?></td>
                            <td><?= esc($formatValue($item['realization_text'] ?? '')) ?></td>
                            <td><?= esc($formatValue($item['deviation_text'] ?? '')) ?></td>
                            <td><?= esc($formatValue($item['partner'] ?? '')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php elseif ($realizationLines !== []) : ?>
                    <?php foreach ($realizationLines as $line) : ?>
                        <tr>
                            <td colspan="6"><?= esc($line) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="MutedCell">Belum ada data realisasi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="Section">
        <p class="SectionTitle">Dokumentasi Pekerjaan</p>
        <?php if ($photoSources !== []) : ?>
            <table class="PhotoTable">
                <?php foreach (array_chunk($photoSources, $photoPerRow) as $photoRow) : ?>
                    <tr>
                        <?php foreach ($photoRow as $index => $photoSrc) : ?>
                            <td class="PhotoCell" style="width: <?= esc((string) (100 / $photoPerRow)) ?>%;">
                                <div class="PhotoFrame">
                                    <table class="PhotoInner">
                                        <tr>
                                            <td>
                                                <img src="<?= esc($photoSrc) ?>" alt="Dokumentasi pekerjaan">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="PhotoCaption">Dokumentasi</div>
                            </td>
                        <?php endforeach; ?>

                        <?php for ($i = count($photoRow); $i < $photoPerRow; $i++) : ?>
                            <td class="PhotoCell" style="width: <?= esc((string) (100 / $photoPerRow)) ?>%;"></td>
                        <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <div class="Panel">Belum ada dokumentasi foto pada laporan ini.</div>
        <?php endif; ?>
    </div>

    <div class="Section">
        <p class="SectionTitle">Update Pekerja Hari Ini</p>
        <table class="DataTable">
            <thead>
                <tr>
                    <th style="width: 74%;">Keterangan</th>
                    <th style="width: 26%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($workerRows !== []) : ?>
                    <?php foreach ($workerRows as $item) : ?>
                        <tr>
                            <td><?= esc($formatValue($item['category_label'] ?? '')) ?></td>
                            <td class="TextCenter"><?= esc((string) ($item['quantity'] ?? 0)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="2" class="MutedCell">Belum ada data update pekerja.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="Section">
        <p class="SectionTitle">Resume Realisasi Material &amp; Bahan Pekerjaan</p>
        <div class="Panel">
            <?php if ($materialLines !== []) : ?>
                <ul class="ListBlock">
                    <?php foreach ($materialLines as $line) : ?>
                        <li><?= esc($line) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                -
            <?php endif; ?>
        </div>
    </div>

    <div class="Section">
        <p class="SectionTitle">Resume Realisasi Alat Berat Yang Digunakan</p>
        <table class="DataTable">
            <thead>
                <tr>
                    <th style="width: 62%;">Alat Berat</th>
                    <th style="width: 19%;">Volume</th>
                    <th style="width: 19%;">Satuan</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($heavyRows !== []) : ?>
                    <?php foreach ($heavyRows as $item) : ?>
                        <tr>
                            <td><?= esc($formatValue($item['equipment_label'] ?? '')) ?></td>
                            <td class="TextCenter"><?= esc((string) ($item['volume'] ?? $item['quantity'] ?? 0)) ?></td>
                            <td class="TextCenter"><?= esc($formatValue($item['unit'] ?? 'unit')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="MutedCell">Belum ada data alat berat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="Section">
        <p class="SectionTitle">Resume Realisasi Alat Kerja Yang Digunakan</p>
        <table class="DataTable">
            <thead>
                <tr>
                    <th style="width: 62%;">Alat Kerja Ringan</th>
                    <th style="width: 19%;">Volume</th>
                    <th style="width: 19%;">Satuan</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($lightToolRows !== []) : ?>
                    <?php foreach ($lightToolRows as $item) : ?>
                        <tr>
                            <td><?= esc($formatValue($item['tool_label'] ?? '')) ?></td>
                            <td class="TextCenter"><?= esc($formatValue($item['volume'] ?? '')) ?></td>
                            <td class="TextCenter"><?= esc($formatValue($item['unit'] ?? '')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php elseif ($toolLines !== []) : ?>
                    <?php foreach ($toolLines as $line) : ?>
                        <tr>
                            <td colspan="3"><?= esc($line) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="MutedCell">Belum ada data alat kerja ringan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="Section">
        <p class="SectionTitle">Kendala Pekerjaan</p>
        <table class="DataTable">
            <thead>
                <tr>
                    <th style="width: 50%;">Kendala</th>
                    <th style="width: 50%;">Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= nl2br(esc($buildObstacleText($bundle['obstacle'] ?? []))) ?></td>
                    <td><?= nl2br(esc($formatValue($bundle['obstacle']['additional_note'] ?? ''))) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="Section">
        <p class="SectionTitle">Rencana Pekerjaan Hari Esok</p>
        <table class="DataTable">
            <thead>
                <tr>
                    <th style="width: 78%;">Rencana</th>
                    <th style="width: 22%;">Target</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php if ($tomorrowLines !== []) : ?>
                            <ul class="ListBlock">
                                <?php foreach ($tomorrowLines as $line) : ?>
                                    <li><?= esc($line) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="TextCenter"><?= esc($targetDateValue) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php if (trim((string) ($bundle['overtime']['summary_text'] ?? '')) !== '') : ?>
        <div class="Section">
            <p class="SectionTitle">Ringkasan Lembur</p>
            <div class="Panel"><?= nl2br(esc($bundle['overtime']['summary_text'])) ?></div>
        </div>
    <?php endif; ?>

    <div class="FooterNote">
        Dokumen ini digenerate otomatis oleh <?= esc(trace_app_brand()) ?> pada <?= esc(date('d F Y H:i')) ?>.
    </div>
</body>
</html>