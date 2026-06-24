<?php
$toggleId = $toggleId ?? 'AutoSendWaToggle';
$hint = $hint ?? 'Saat submit final, laporan otomatis dikirim ke grup WhatsApp TraceApp.';
?>
<section class="InfoCard ReportToggleCard">
    <div class="ReportToggleField">
        <div>
            <strong>Auto Send WA</strong>
            <p class="InfoText"><?= esc($hint) ?></p>
        </div>
        <label class="ReportSwitch" for="<?= esc($toggleId) ?>">
            <input type="checkbox" id="<?= esc($toggleId) ?>" class="ReportSwitchInput" data-auto-wa-toggle>
            <span class="ReportSwitchTrack" aria-hidden="true"></span>
        </label>
    </div>
</section>
