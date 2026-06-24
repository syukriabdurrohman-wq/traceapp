<?php

namespace App\Libraries;

class Notification
{
    private string $fonnteApiUrl = 'https://api.fonnte.com/send';

    public function sendWhatsapp(string $target, string $message): bool
    {
        $target = trim($target);
        $token = trim((string) env('fonnte.token'));

        if ($target === '' || $token === '') {
            log_message('error', 'Fonnte configuration belum lengkap.');
            return false;
        }

        $payload = [
            'target'  => $this->normalizeTarget($target),
            'message' => trim($message),
        ];

        $device = trim((string) env('fonnte.device'));
        if ($device !== '') {
            $payload['device'] = $device;
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->fonnteApiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($payload),
            CURLOPT_HTTPHEADER     => [
                'Authorization: ' . $token,
            ],
            CURLOPT_TIMEOUT        => 20,
        ]);

        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            log_message('error', 'Fonnte curl error: ' . $curlError);
            return false;
        }

        $decoded = json_decode($response, true);
        $success = $httpCode === 200 && (($decoded['status'] ?? false) === true);

        if ($success) {
            log_message('info', 'WhatsApp report sent via Fonnte to ' . $payload['target']);
            return true;
        }

        log_message('error', 'WhatsApp report failed via Fonnte. HTTP ' . $httpCode . ' Response: ' . $response);
        return false;
    }

    private function normalizeTarget(string $target): string
    {
        if (str_contains($target, '@g.us')) {
            return $target;
        }

        $target = preg_replace('/[^0-9]/', '', $target) ?? '';

        if ($target === '') {
            return '';
        }

        if (str_starts_with($target, '0')) {
            return '62' . substr($target, 1);
        }

        if (! str_starts_with($target, '62')) {
            return '62' . $target;
        }

        return $target;
    }
}
