<?php

namespace App\Observers;

use App\Models\Tabungan;
use App\Models\Penarikan;
use App\Services\NotificationService;
use Carbon\Carbon;

class TabunganObserver
{
    /**
     * Trigger notifikasi setoran baru ke nasabah + admin.
     * Pesan menyertakan saldo akhir real-time agar nasabah tidak bingung
     * saat saldo awal mereka minus (misal akibat koreksi data).
     */
    public function created(Tabungan $tabungan): void
    {
        $nasabah = $tabungan->nasabah;
        if (!$nasabah) {
            return;
        }

        // Hitung saldo real-time SETELAH insert
        $totalTabungan = (float) $nasabah->tabungan()->sum('nilai_rupiah');
        $totalPenarikan = (float) $nasabah->penarikan()
            ->where('status', 'selesai')
            ->sum('nominal');
        $saldoAkhir = $totalTabungan - $totalPenarikan;

        $nominalFormatted = 'Rp ' . number_format((float) $tabungan->nilai_rupiah, 0, ',', '.');
        $saldoFormatted   = 'Rp ' . number_format($saldoAkhir, 0, ',', '.');
        $tanggal          = Carbon::parse($tabungan->tanggal_setor)->format('d M Y');
        $jenisSampah      = $tabungan->jenisSampah->nama ?? '';

        // Notifikasi ke admin (panel)
        NotificationService::tabunganMasuk(
            $nasabah->nama_lengkap,
            $nominalFormatted,
            $jenisSampah
        );

        // Notifikasi ke nasabah — dengan info saldo akhir
        NotificationService::tabunganMasukNasabah(
            $nasabah->user_id,
            $nominalFormatted,
            $tanggal,
            $jenisSampah,
            $saldoFormatted
        );
    }

    /**
     * Proteksi global: cegah penghapusan tabungan
     * yang akan menyebabkan saldo nasabah menjadi minus.
     */
    public function deleting(Tabungan $tabungan): void
    {
        $nasabah = $tabungan->nasabah;

        if (!$nasabah) {
            return;
        }

        // Hitung saldo SETELAH penghapusan (semua tabungan KECUALI yang sedang dihapus)
        $totalTabunganLain = (float) $nasabah->tabungan()
            ->where('id', '!=', $tabungan->id)
            ->sum('nilai_rupiah');

        $totalPenarikan = (float) Penarikan::where('nasabah_id', $nasabah->id)
            ->where('status', 'selesai')
            ->sum('nominal');

        $saldoAfterDelete = $totalTabunganLain - $totalPenarikan;

        if ($saldoAfterDelete < 0) {
            throw new \App\Exceptions\SaldoMinusException(
                "Tidak dapat menghapus setoran Rp "
                . number_format((float) $tabungan->nilai_rupiah, 0, ',', '.')
                . ". Penghapusan ini akan membuat saldo nasabah menjadi Rp "
                . number_format($saldoAfterDelete, 0, ',', '.')
                . " (minus). Saldo penarikan terkunci saat ini: Rp "
                . number_format($totalPenarikan, 0, ',', '.')
            );
        }
    }
}
