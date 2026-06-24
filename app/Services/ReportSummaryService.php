<?php

namespace App\Services;

class ReportSummaryService
{
    public function build(array $bundle): string
    {
        $report   = $bundle['report'] ?? [];
        $location = $bundle['location'] ?? [];
        $material = $bundle['material'] ?? [];
        $tool     = $bundle['tool'] ?? [];
        $obstacle = $bundle['obstacle'] ?? [];
        $tomorrow = $bundle['tomorrow'] ?? [];
        $overtime = $bundle['overtime'] ?? [];
        $workerName = $bundle['worker']['full_name'] ?? ($report['worker_name'] ?? '-');

        $lines = [
            '*DETAIL LAPORAN HARIAN*',
            '',
            'Halo, ' . $workerName . ',',
            'Berikut detail laporan yang sudah Anda input:',
        ];

        $this->appendSection($lines, 'IDENTITAS', [
            'Kode Laporan: ' . $this->value($report['report_code'] ?? ''),
            'Tanggal Laporan: ' . $this->formatDate($report['report_date'] ?? ''),
            'Status: ' . $this->value($report['status'] ?? ''),
            'Pelaksana / Supervisor: ' . $this->value($workerName),
            'Dibuat Oleh: ' . $this->value($report['creator_name'] ?? ''),
            'Cuaca: ' . $this->value($report['weather_code'] ?? ''),
        ]);

        $this->appendSection($lines, 'LOKASI PEKERJAAN', [
            'Area: ' . $this->value($location['area_label'] ?? ''),
            'Lokasi Terkini: ' . $this->value($location['current_location'] ?? ''),
            'Keterangan Lokasi: ' . $this->value($location['reason'] ?? ''),
        ]);

        $realizationLines = [];
        foreach (($bundle['realizationItems'] ?? []) as $index => $item) {
            $realizationLines[] = ((int) $index + 1) . '. ' . $this->value($item['work_item'] ?? '');
            $realizationLines[] = '   Satuan: ' . $this->value($item['unit'] ?? '');
            $realizationLines[] = '   Rencana: ' . $this->value($item['plan_text'] ?? '');
            $realizationLines[] = '   Realisasi: ' . $this->value($item['realization_text'] ?? '');
            $realizationLines[] = '   Deviasi: ' . $this->value($item['deviation_text'] ?? '');
            $realizationLines[] = '   Rekanan: ' . $this->value($item['partner'] ?? '');
        }
        if ($realizationLines === []) {
            $realizationLines[] = $this->value($report['realization_summary'] ?? '');
        }
        $this->appendSection($lines, 'REALISASI PEKERJAAN', $realizationLines);

        $workerLines = [];
        foreach (($bundle['workerUpdates'] ?? []) as $index => $item) {
            $workerLines[] = ((int) $index + 1) . '. ' . $this->value($item['category_label'] ?? '') . ': ' . $this->value($item['quantity'] ?? '') . ' orang';
        }
        $this->appendSection($lines, 'PEKERJA DAN POSISI', $workerLines === [] ? ['-'] : $workerLines);

        $heavyLines = [];
        foreach (($bundle['heavyEquipment'] ?? []) as $index => $item) {
            $heavyLines[] = ((int) $index + 1) . '. ' . $this->value($item['equipment_label'] ?? '') . ': ' . $this->value($item['quantity'] ?? '') . ' unit';
            $heavyLines[] = '   Volume: ' . $this->value($item['volume'] ?? $item['quantity'] ?? '') . ' ' . $this->value($item['unit'] ?? 'unit');
        }
        $this->appendSection($lines, 'ALAT BERAT', $heavyLines === [] ? ['-'] : $heavyLines);

        $lightToolLines = [];
        foreach (($bundle['lightTools'] ?? []) as $index => $item) {
            $lightToolLines[] = ((int) $index + 1) . '. ' . $this->value($item['tool_label'] ?? '');
            $lightToolLines[] = '   Volume: ' . $this->value($item['volume'] ?? '') . ' ' . $this->value($item['unit'] ?? '');
        }
        if ($lightToolLines === []) {
            $lightToolLines[] = $this->value($tool['summary_text'] ?? '');
        }
        $this->appendSection($lines, 'ALAT KERJA RINGAN', $lightToolLines);

        $this->appendSection($lines, 'MATERIAL & BAHAN KERJA', [
            $this->value($material['summary_text'] ?? ''),
        ]);

        $hasObstacle = trim((string) ($obstacle['obstacle_shape'] ?? '')) !== ''
            || trim((string) ($obstacle['obstacle_cause'] ?? '')) !== ''
            || trim((string) ($obstacle['obstacle_impact'] ?? '')) !== ''
            || trim((string) ($obstacle['additional_note'] ?? '')) !== '';

        $this->appendSection($lines, 'KENDALA LAPANGAN', $hasObstacle ? [
            'Bentuk Kendala: ' . $this->value($obstacle['obstacle_shape'] ?? ''),
            'Penyebab Kendala: ' . $this->value($obstacle['obstacle_cause'] ?? ''),
            'Dampak Pekerjaan: ' . $this->value($obstacle['obstacle_impact'] ?? ''),
            'Penjelasan Tambahan: ' . $this->value($obstacle['additional_note'] ?? ''),
        ] : ['Tidak ada kendala yang diinput.']);

        $this->appendSection($lines, 'RENCANA PEKERJAAN ESOK', [
            $this->value($tomorrow['summary_text'] ?? ''),
        ]);

        $hasOvertime = (int) ($overtime['is_enabled'] ?? 0) === 1;
        $overtimeLines = [
            'Status: ' . ($hasOvertime ? 'Ada lembur' : 'Tidak ada lembur'),
        ];
        if ($hasOvertime) {
            $overtimeLines[] = 'Jam Mulai: ' . $this->value($overtime['start_time'] ?? '');
            $overtimeLines[] = 'Jam Selesai: ' . $this->value($overtime['end_time'] ?? '');
        }
        if (trim((string) ($overtime['summary_text'] ?? '')) !== '') {
            $overtimeLines[] = 'Ringkasan Lembur: ' . $this->value($overtime['summary_text'] ?? '');
        }
        $this->appendSection($lines, 'LEMBUR', $overtimeLines);

        if (!empty($report['edited_at'])) {
            $lines[] = '';
            $lines[] = '*(Catatan: Laporan ini telah diedit/diperbarui pada ' . date('d M Y H:i', strtotime($report['edited_at'])) . ')*';
        }

        return implode(PHP_EOL, $lines);
    }

    public function buildObstacleSummary(array $bundle): string
    {
        return trim(implode(' ', array_filter([
            'Bentuk: ' . $bundle['obstacle']['obstacle_shape'],
            'Penyebab: ' . $bundle['obstacle']['obstacle_cause'],
            'Dampak: ' . $bundle['obstacle']['obstacle_impact'],
            $bundle['obstacle']['additional_note'] !== '' ? 'Catatan: ' . $bundle['obstacle']['additional_note'] : null,
        ])));
    }

    private function appendSection(array &$lines, string $title, array $content): void
    {
        $lines[] = '';
        $lines[] = '*' . $title . '*';

        foreach ($content as $line) {
            $lines[] = (string) $line;
        }
    }

    private function formatDate(mixed $value): string
    {
        $date = trim((string) $value);
        if ($date === '') {
            return '-';
        }

        $timestamp = strtotime($date);

        return $timestamp === false ? $date : date('d M Y', $timestamp);
    }

    private function value(mixed $value): string
    {
        $value = trim((string) $value);

        return $value === '' ? '-' : $value;
    }
}
