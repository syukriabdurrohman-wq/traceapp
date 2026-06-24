<?= $this->extend('Layouts/MobileLayout') ?>

<?= $this->section('content') ?>
<?php
$workerUpdates = $bundle['workerUpdates'] ?? [];
$heavyEquipment = $bundle['heavyEquipment'] ?? [];
$realizationItems = $bundle['realizationItems'] ?? [];
$lightTools = $bundle['lightTools'] ?? [];
$hasOvertime = (int) ($bundle['overtime']['is_enabled'] ?? 0) === 1;
$overtimeText = $hasOvertime
    ? trim((string) ($bundle['overtime']['start_time'] ?? '-')) . ' - ' . trim((string) ($bundle['overtime']['end_time'] ?? '-'))
    : 'Tidak ada lembur';
$displayWaSummary = trim((string) ($waSummary ?? ''));
?>
<?= view('Components/PageHeader', [
    'eyebrow' => 'Detail Laporan',
    'title' => $bundle['worker']['full_name'],
    'subtitle' => 'Laporan tanggal ' . date('d F Y', strtotime($bundle['report']['report_date'])),
    'actionHref' => base_url('reports/pdf/' . $bundle['report']['id']),
    'actionLabel' => 'Buka PDF',
    'actionIcon' => 'pdf',
]) ?>

<?php if ($bundle['report']['status'] !== 'Submitted') : ?>
    <?= view('Components/AutoSendWAToggle', [
        'toggleId' => 'DetailAutoSendWaToggle',
    ]) ?>
<?php endif; ?>

<section class="SuccessCard">
    <div class="SuccessIcon">✓</div>
    <strong><?= esc($bundle['report']['status']) === 'Submitted' ? 'Laporan berhasil dikirim' : 'Draft tersimpan' ?></strong>
    <p><?= esc($bundle['report']['report_code']) ?></p>
    <?php if (!empty($bundle['report']['edited_at'])) : ?>
        <p style="margin-top: 8px; font-size: 0.8rem; color: #e67e22; font-weight: 600;">Diedit pada: <?= date('d M Y H:i', strtotime($bundle['report']['edited_at'])) ?></p>
    <?php endif; ?>
</section>

<details class="InfoCard InfoAccordion">
    <summary class="AccordionSummary">
        <div class="AccordionSummaryText">
            <h2>Identitas</h2>
            <span><?= esc($bundle['report']['weather_code']) ?></span>
        </div>
        <span class="AccordionSummaryIcon" aria-hidden="true"><?= trace_icon('next') ?></span>
    </summary>
    <div class="AccordionBody">
        <div class="StructuredRows">
            <div class="StructuredRow">
                <span class="StructuredLabel">Pelaksana</span>
                <strong class="StructuredValue"><?= esc($bundle['worker']['full_name']) ?></strong>
            </div>
            <div class="StructuredRow">
                <span class="StructuredLabel">Area</span>
                <strong class="StructuredValue"><?= esc($bundle['location']['area_label']) ?></strong>
            </div>
            <div class="StructuredRow">
                <span class="StructuredLabel">Lokasi</span>
                <strong class="StructuredValue"><?= esc($bundle['location']['current_location']) ?></strong>
            </div>
            <div class="StructuredRow">
                <span class="StructuredLabel">Created By</span>
                <strong class="StructuredValue"><?= esc($bundle['report']['creator_name']) ?></strong>
            </div>
        </div>
        <?php if ($bundle['location']['reason'] !== '') : ?>
            <div class="AccordionGroup">
                <p class="AccordionGroupTitle">Keterangan Lokasi</p>
                <div class="StructuredNote"><?= nl2br(esc($bundle['location']['reason'])) ?></div>
            </div>
        <?php endif; ?>
    </div>
</details>

<details class="InfoCard InfoAccordion">
    <summary class="AccordionSummary">
        <div class="AccordionSummaryText">
            <h2>Update Pekerja</h2>
            <span><?= esc((string) count($workerUpdates)) ?> item</span>
        </div>
        <span class="AccordionSummaryIcon" aria-hidden="true"><?= trace_icon('next') ?></span>
    </summary>
    <div class="AccordionBody">
        <?php if ($workerUpdates !== []) : ?>
            <div class="StructuredRows">
                <?php foreach ($workerUpdates as $item) : ?>
                    <div class="StructuredRow">
                        <span class="StructuredLabel"><?= esc($item['category_label']) ?></span>
                        <strong class="StructuredValue"><?= esc((string) $item['quantity']) ?></strong>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="StructuredEmpty">Belum ada data update pekerja.</p>
        <?php endif; ?>
    </div>
</details>

<details class="InfoCard InfoAccordion">
    <summary class="AccordionSummary">
        <div class="AccordionSummaryText">
            <h2>Realisasi Pekerjaan</h2>
            <span>Resume</span>
        </div>
        <span class="AccordionSummaryIcon" aria-hidden="true"><?= trace_icon('next') ?></span>
    </summary>
    <div class="AccordionBody">
        <?php if ($realizationItems !== []) : ?>
            <div class="StructuredRows">
                <?php foreach ($realizationItems as $item) : ?>
                    <div class="StructuredRow">
                        <span class="StructuredLabel"><?= esc($item['work_item']) ?></span>
                        <strong class="StructuredValue">
                            Sat: <?= esc($item['unit'] ?: '-') ?> |
                            Rencana: <?= esc($item['plan_text'] ?: '-') ?> |
                            Realisasi: <?= esc($item['realization_text'] ?: '-') ?> |
                            Deviasi: <?= esc($item['deviation_text'] ?: '-') ?> |
                            Rekanan: <?= esc($item['partner'] ?: '-') ?>
                        </strong>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="StructuredNote"><?= nl2br(esc($bundle['report']['realization_summary'])) ?></div>
        <?php endif; ?>
    </div>
</details>

<details class="InfoCard InfoAccordion">
    <summary class="AccordionSummary">
        <div class="AccordionSummaryText">
            <h2>Alat & Material</h2>
            <span><?= esc((string) count($heavyEquipment)) ?> alat berat</span>
        </div>
        <span class="AccordionSummaryIcon" aria-hidden="true"><?= trace_icon('next') ?></span>
    </summary>
    <div class="AccordionBody">
        <div class="AccordionGroup">
            <p class="AccordionGroupTitle">Alat Berat</p>
            <?php if ($heavyEquipment !== []) : ?>
                <div class="StructuredRows">
                    <?php foreach ($heavyEquipment as $item) : ?>
                        <div class="StructuredRow">
                            <span class="StructuredLabel"><?= esc($item['equipment_label']) ?></span>
                            <strong class="StructuredValue">
                                Jumlah <?= esc((string) $item['quantity']) ?> |
                                Volume <?= esc((string) ($item['volume'] ?? $item['quantity'])) ?> <?= esc((string) ($item['unit'] ?? 'unit')) ?>
                            </strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="StructuredEmpty">Belum ada data alat berat.</p>
            <?php endif; ?>
        </div>

        <div class="AccordionGroup">
            <p class="AccordionGroupTitle">Alat Kerja Ringan</p>
            <?php if ($lightTools !== []) : ?>
                <div class="StructuredRows">
                    <?php foreach ($lightTools as $item) : ?>
                        <div class="StructuredRow">
                            <span class="StructuredLabel"><?= esc($item['tool_label']) ?></span>
                            <strong class="StructuredValue"><?= esc((string) ($item['volume'] ?? '-')) ?> <?= esc((string) ($item['unit'] ?? '')) ?></strong>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="StructuredNote"><?= nl2br(esc($bundle['tool']['summary_text'])) ?></div>
            <?php endif; ?>
        </div>

        <div class="AccordionGroup">
            <p class="AccordionGroupTitle">Material & Bahan Kerja</p>
            <div class="StructuredNote"><?= nl2br(esc($bundle['material']['summary_text'])) ?></div>
        </div>
    </div>
</details>

<details class="InfoCard InfoAccordion">
    <summary class="AccordionSummary">
        <div class="AccordionSummaryText">
            <h2>Kendala & Rencana</h2>
            <span><?= $hasOvertime ? 'Dengan lembur' : 'Tanpa lembur' ?></span>
        </div>
        <span class="AccordionSummaryIcon" aria-hidden="true"><?= trace_icon('next') ?></span>
    </summary>
    <div class="AccordionBody">
        <div class="AccordionGroup">
            <p class="AccordionGroupTitle">Kendala Lapangan</p>
            <div class="StructuredRows">
                <div class="StructuredRow">
                    <span class="StructuredLabel">Bentuk Kendala</span>
                    <strong class="StructuredValue"><?= esc($bundle['obstacle']['obstacle_shape']) ?></strong>
                </div>
                <div class="StructuredRow">
                    <span class="StructuredLabel">Penyebab Kendala</span>
                    <strong class="StructuredValue"><?= esc($bundle['obstacle']['obstacle_cause']) ?></strong>
                </div>
                <div class="StructuredRow">
                    <span class="StructuredLabel">Dampak Pekerjaan</span>
                    <strong class="StructuredValue"><?= esc($bundle['obstacle']['obstacle_impact']) ?></strong>
                </div>
            </div>
        </div>

        <?php if ($bundle['obstacle']['additional_note'] !== '') : ?>
            <div class="AccordionGroup">
                <p class="AccordionGroupTitle">Catatan Tambahan</p>
                <div class="StructuredNote"><?= nl2br(esc($bundle['obstacle']['additional_note'])) ?></div>
            </div>
        <?php endif; ?>

        <div class="AccordionGroup">
            <p class="AccordionGroupTitle">Rencana Pekerjaan Esok</p>
            <div class="StructuredNote"><?= nl2br(esc($bundle['tomorrow']['summary_text'])) ?></div>
        </div>

        <div class="AccordionGroup">
            <p class="AccordionGroupTitle">Lembur</p>
            <div class="StructuredRows">
                <div class="StructuredRow">
                    <span class="StructuredLabel">Status</span>
                    <strong class="StructuredValue"><?= $hasOvertime ? 'Ada lembur' : 'Tidak ada lembur' ?></strong>
                </div>
                <div class="StructuredRow">
                    <span class="StructuredLabel">Jam Kerja</span>
                    <strong class="StructuredValue"><?= esc($overtimeText) ?></strong>
                </div>
                <?php if (trim((string) ($bundle['overtime']['summary_text'] ?? '')) !== '') : ?>
                    <div class="StructuredRow">
                        <span class="StructuredLabel">Ringkasan Lembur</span>
                        <strong class="StructuredValue"><?= esc($bundle['overtime']['summary_text']) ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</details>

<details class="InfoCard InfoAccordion">
    <summary class="AccordionSummary">
        <div class="AccordionSummaryText">
            <h2>Dokumentasi</h2>
            <span><?= esc((string) count($bundle['photos'])) ?> foto</span>
        </div>
        <span class="AccordionSummaryIcon" aria-hidden="true"><?= trace_icon('next') ?></span>
    </summary>
    <div class="AccordionBody">
        <?php if ($bundle['photos'] !== []) : ?>
            <div class="PhotoPreviewGrid">
                <?php foreach ($bundle['photos'] as $photo) : ?>
                    <img src="<?= base_url($photo['file_path']) ?>" alt="Foto laporan">
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="StructuredEmpty">Belum ada dokumentasi foto pada laporan ini.</p>
        <?php endif; ?>
    </div>
</details>

<details class="InfoCard InfoAccordion">
    <summary class="AccordionSummary">
        <div class="AccordionSummaryText">
            <h2>Ringkasan WhatsApp</h2>
            <span><?= $displayWaSummary !== '' ? 'Siap disalin' : 'Belum terbentuk' ?></span>
        </div>
        <span class="AccordionSummaryIcon" aria-hidden="true"><?= trace_icon('next') ?></span>
    </summary>
    <div class="AccordionBody">
        <div class="AccordionActionRow">
            <button type="button" class="InlineAction isIconOnly" data-copy-target="WhatsAppSummary" aria-label="Salin ringkasan WhatsApp" title="Salin ringkasan WhatsApp" data-copy-default-label="Salin ringkasan WhatsApp" data-copy-success-label="Ringkasan tersalin">
                <?= trace_icon('copy') ?>
            </button>
        </div>
        <pre id="WhatsAppSummary" class="SummaryBox" style="white-space: pre-wrap; font-size: 13px; line-height: 1.6; background: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid #e2e8f0; font-family: inherit; margin: 0; color: #1e293b;"><?= esc($displayWaSummary !== '' ? $displayWaSummary : 'Ringkasan WhatsApp akan terbentuk setelah submit final.') ?></pre>
    </div>
</details>

<div class="StickyActionBar" style="display:flex; gap: 8px;">
    <?php if ($bundle['report']['status'] !== 'Submitted') : ?>
        <a href="<?= base_url('reports/edit/' . $bundle['report']['id']) ?>" class="GhostButton isIconOnly" aria-label="Edit draft laporan" title="Edit draft laporan"><?= trace_icon('edit') ?></a>
        <form method="post" action="<?= base_url('reports/submit/' . $bundle['report']['id']) ?>" style="flex: 1; margin: 0;">
            <?= csrf_field() ?>
            <input type="hidden" name="autoSendWa" value="0" data-auto-wa-input>
            <button type="submit" class="PrimaryButton" style="width: 100%;">Submit Final</button>
        </form>
    <?php else : ?>
       <a href="<?= base_url('reports/edit/' . $bundle['report']['id']) ?>" 
   class="PrimaryButton"
   style="flex:1; display:flex; align-items:center; justify-content:center; gap:6px;">

    <span style="display:flex; align-items:center; width:16px; height:16px;">
        <?= trace_icon('edit') ?>
    </span>

    <span>Edit Laporan</span>
</a>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
