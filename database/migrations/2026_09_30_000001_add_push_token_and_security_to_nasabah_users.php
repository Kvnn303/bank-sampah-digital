<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tambah kolom-kolom penting ke tabel nasabah dan users
 *
 * 1. nasabah:
 *    - expo_push_token      → Token device untuk Expo Push Notifications (per nasabah)
 *    - latitude             → Koordinat GPS saat registrasi (keamanan & verifikasi domisili)
 *    - longitude            → Koordinat GPS saat registrasi
 *    - lokasi_registrasi    → Alamat dari GPS (reverse geocoding), teks lengkap
 *    - ktp_verified_at      → Timestamp ketika admin memverifikasi KTP valid
 *    - ktp_verification_status → Status verifikasi KTP: pending/valid/invalid
 *    - ktp_rejection_reason → Alasan penolakan jika KTP tidak valid
 *
 * 2. users:
 *    - expo_push_token      → Token per device (user level, untuk admin juga)
 *    - last_login_at        → Timestamp login terakhir
 *    - last_login_ip        → IP login terakhir (deteksi login mencurigakan)
 *    - failed_login_count   → Hitung percobaan login gagal (lockout protection)
 *    - locked_until         → Waktu terkunci akun akibat brute force
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Kolom tambahan di tabel nasabah ──
        Schema::table('nasabah', function (Blueprint $table) {
            // Push notification token
            $table->string('expo_push_token')->nullable()->after('catatan_admin')
                ->comment('Token Expo Push Notification untuk perangkat nasabah');

            // Lokasi saat registrasi
            $table->decimal('latitude', 10, 8)->nullable()->after('expo_push_token')
                ->comment('Koordinat lintang GPS saat registrasi');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude')
                ->comment('Koordinat bujur GPS saat registrasi');
            $table->text('lokasi_registrasi')->nullable()->after('longitude')
                ->comment('Alamat lengkap dari reverse geocoding GPS registrasi');

            // Verifikasi KTP
            $table->enum('ktp_verification_status', ['pending', 'valid', 'invalid'])
                ->default('pending')->after('foto_ktp')
                ->comment('Status verifikasi KTP oleh admin');
            $table->timestamp('ktp_verified_at')->nullable()->after('ktp_verification_status')
                ->comment('Waktu admin memverifikasi KTP');
            $table->text('ktp_rejection_reason')->nullable()->after('ktp_verified_at')
                ->comment('Alasan penolakan KTP oleh admin');
        });

        // ── Kolom tambahan di tabel users ──
        Schema::table('users', function (Blueprint $table) {
            // Push notification token
            if (!Schema::hasColumn('users', 'expo_push_token')) {
                $table->string('expo_push_token')->nullable()->after('remember_token')
                    ->comment('Token Expo Push Notification untuk perangkat user');
            }

            // Security tracking
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('expo_push_token')
                    ->comment('Waktu login terakhir berhasil');
            }
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at')
                    ->comment('IP address login terakhir (IPv4/IPv6)');
            }
            if (!Schema::hasColumn('users', 'failed_login_count')) {
                $table->unsignedSmallInteger('failed_login_count')->default(0)->after('last_login_ip')
                    ->comment('Jumlah percobaan login gagal berturut-turut');
            }
            if (!Schema::hasColumn('users', 'locked_until')) {
                $table->timestamp('locked_until')->nullable()->after('failed_login_count')
                    ->comment('Akun terkunci hingga waktu ini akibat brute force');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nasabah', function (Blueprint $table) {
            $table->dropColumn([
                'expo_push_token',
                'latitude',
                'longitude',
                'lokasi_registrasi',
                'ktp_verification_status',
                'ktp_verified_at',
                'ktp_rejection_reason',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $columns = ['expo_push_token', 'last_login_at', 'last_login_ip', 'failed_login_count', 'locked_until'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
