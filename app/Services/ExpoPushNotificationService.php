<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * ExpoPushNotificationService
 * Layanan untuk mengirim push notification langsung ke HP pengguna
 * melalui Expo Push Notification API.
 *
 * Cara kerja:
 * 1. Pengguna login/daftar di aplikasi mobile → token push tersimpan di backend
 * 2. Saat ada event (setoran, penarikan, dll) → kirim push ke Expo API
 * 3. Expo meneruskan ke FCM (Android) / APNs (iOS) → notifikasi muncul di HP
 */
class ExpoPushNotificationService
{
    // Expo Push API endpoint
    private const EXPO_PUSH_URL = 'https://exp.host/--/api/v2/push/send';

    // Batas ukuran pesan per request
    private const MAX_TITLE_LENGTH = 100;
    private const MAX_BODY_LENGTH  = 300;

    /**
     * Kirim push notification ke satu atau banyak token
     *
     * @param string|array $expoPushTokens Token Expo dari perangkat target
     * @param string $title               Judul notifikasi
     * @param string $body                Isi pesan notifikasi
     * @param array  $data                Data tambahan (deep link, id transaksi, dll)
     * @param string $sound               'default' | null (diam)
     * @param int    $badge               Angka badge (unread count)
     * @param string $channelId           Android notification channel
     */
    public static function send(
        string|array $expoPushTokens,
        string $title,
        string $body,
        array $data = [],
        string $sound = 'default',
        int $badge = 0,
        string $channelId = 'default'
    ): bool {
        // Normalisasi ke array
        $tokens = is_array($expoPushTokens) ? $expoPushTokens : [$expoPushTokens];

        // Filter token yang valid (format ExponentPushToken[xxx])
        $validTokens = array_filter($tokens, fn($t) => self::isValidToken($t));

        if (empty($validTokens)) {
            Log::debug('[PushNotif] Tidak ada token valid, skip.');
            return false;
        }

        // Potong panjang pesan agar tidak error
        $title = mb_substr($title, 0, self::MAX_TITLE_LENGTH);
        $body  = mb_substr($body,  0, self::MAX_BODY_LENGTH);

        // Buat payload per token
        $messages = array_values(array_map(fn($token) => [
            'to'         => $token,
            'title'      => $title,
            'body'       => $body,
            'data'       => $data,
            'sound'      => $sound,
            'badge'      => $badge,
            'channelId'  => $channelId,
            'priority'   => 'high',                  // Agar langsung muncul seperti WA
            'ttl'        => 86400,                   // Time-to-live 24 jam
            'expiration' => time() + 86400,
        ], $validTokens));

        try {
            // Kirim dalam batch (max 100 per request sesuai limit Expo)
            $chunks = array_chunk($messages, 100);

            foreach ($chunks as $chunk) {
                $response = Http::withHeaders([
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                    'Accept-Encoding' => 'gzip, deflate',
                ])->timeout(10)->post(self::EXPO_PUSH_URL, $chunk);

                if (!$response->successful()) {
                    Log::error('[PushNotif] Expo API error', [
                        'status'   => $response->status(),
                        'response' => $response->body(),
                    ]);
                    return false;
                }

                // Log hasil per token untuk debug
                $responseData = $response->json();
                if (isset($responseData['data'])) {
                    foreach ($responseData['data'] as $idx => $result) {
                        if ($result['status'] === 'error') {
                            Log::warning('[PushNotif] Token gagal', [
                                'token'   => $chunk[$idx]['to'] ?? 'unknown',
                                'error'   => $result['message'] ?? 'unknown error',
                                'details' => $result['details'] ?? [],
                            ]);
                        }
                    }
                }
            }

            Log::info('[PushNotif] Berhasil kirim ke ' . count($validTokens) . ' device', [
                'title' => $title,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('[PushNotif] Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Validasi format token Expo
     * Format valid: ExponentPushToken[xxxxxx] atau ExpoPushToken[xxxxxx]
     */
    public static function isValidToken(string $token): bool
    {
        return (bool) preg_match('/^Expo(nent)?PushToken\[[a-zA-Z0-9_\-]+\]$/', trim($token));
    }

    // ─── SHORTCUT METHODS ─────────────────────────────────────

    /**
     * Notifikasi setoran sampah ke nasabah
     */
    public static function notifySetoran(string $token, string $nominal, string $jenisSampah): bool
    {
        return self::send(
            $token,
            '💰 Setoran Berhasil!',
            "Sampah {$jenisSampah} senilai {$nominal} berhasil dicatat.",
            ['type' => 'tabungan', 'screen' => 'Riwayat'],
            'default',
            0,
            'transaksi'
        );
    }

    /**
     * Notifikasi penarikan disetujui
     */
    public static function notifyPenarikanDisetujui(string $token, string $nominal): bool
    {
        return self::send(
            $token,
            '✅ Penarikan Disetujui!',
            "Pengajuan penarikan {$nominal} telah disetujui. Siap dicairkan!",
            ['type' => 'penarikan', 'screen' => 'Penarikan'],
            'default',
            0,
            'transaksi'
        );
    }

    /**
     * Notifikasi penarikan selesai/cair
     */
    public static function notifyPenarikanSelesai(string $token, string $nominal): bool
    {
        return self::send(
            $token,
            '🎉 Dana Sudah Cair!',
            "Penarikan saldo {$nominal} telah selesai diproses. Cek riwayat Anda.",
            ['type' => 'penarikan', 'screen' => 'Penarikan'],
            'default',
            0,
            'transaksi'
        );
    }

    /**
     * Notifikasi penarikan ditolak
     */
    public static function notifyPenarikanDitolak(string $token, string $nominal, string $alasan): bool
    {
        return self::send(
            $token,
            '❌ Penarikan Ditolak',
            "Maaf, penarikan {$nominal} ditolak. Alasan: {$alasan}",
            ['type' => 'penarikan', 'screen' => 'Penarikan'],
            'default',
            0,
            'transaksi'
        );
    }

    /**
     * Notifikasi akun diverifikasi
     */
    public static function notifyAkunVerifikasi(string $token, string $nama): bool
    {
        return self::send(
            $token,
            '🎊 Akun Verified!',
            "Selamat {$nama}! Akun Anda telah diverifikasi. Mulai setor sampah sekarang!",
            ['type' => 'akun', 'screen' => 'Dashboard'],
            'default',
            0,
            'akun'
        );
    }

    /**
     * Notifikasi harga sampah berubah (broadcast ke semua)
     */
    public static function notifyHargaBerubah(array $tokens, string $namaSampah, string $hargaBaru): bool
    {
        return self::send(
            $tokens,
            '📈 Update Harga Sampah',
            "Harga {$namaSampah} kini {$hargaBaru}/kg. Yuk semangat pilah sampah!",
            ['type' => 'info', 'screen' => 'HargaSampah'],
            'default',
            0,
            'info'
        );
    }

    /**
     * Notifikasi keamanan - login dari perangkat baru
     */
    public static function notifyLoginBaru(string $token, string $nama, string $ip): bool
    {
        return self::send(
            $token,
            '🔐 Login Baru Terdeteksi',
            "Halo {$nama}! Ada login baru ke akun Anda dari {$ip}. Bukan kamu? Segera ganti password!",
            ['type' => 'auth', 'screen' => 'ChangePassword'],
            'default',
            1,
            'keamanan'
        );
    }
}
