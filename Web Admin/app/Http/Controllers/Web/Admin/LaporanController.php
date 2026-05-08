<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\Tabungan;
use App\Models\Penarikan;
use App\Models\JenisSampah;
use App\Models\AuditLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    // Halaman utama laporan
    public function index(Request $request)
    {
        $bulan     = $request->bulan ?? now()->month;
        $tahun     = $request->tahun ?? now()->year;
        $dariTgl   = $request->dari_tanggal ?? now()->startOfMonth()->toDateString();
        $sampaiTgl = $request->sampai_tanggal ?? now()->toDateString();

        $ringkasan = [
            'total_nasabah'      => Nasabah::count(),
            'nasabah_aktif'      => Nasabah::where('status_akun', 'active')->count(),
            'nasabah_pending'    => Nasabah::where('status_akun', 'pending')->count(),
            'total_sampah_kg'    => Tabungan::sum('berat_kg'),
            'total_nilai'        => Tabungan::sum('nilai_rupiah'),
            'total_dicairkan'    => Penarikan::where('status', 'selesai')->sum('nominal'),
            'saldo_tersisa'      => Tabungan::sum('nilai_rupiah') - Penarikan::whereIn('status', ['selesai', 'diproses'])->sum('nominal'),
            'penarikan_pending'  => Penarikan::where('status', 'pending')->count(),
        ];

        $statistikPeriode = [
            'sampah_kg'    => Tabungan::whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])->sum('berat_kg'),
            'nilai'        => Tabungan::whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])->sum('nilai_rupiah'),
            'dicairkan'    => Penarikan::where('status', 'selesai')->whereBetween('updated_at', [$dariTgl, $sampaiTgl])->sum('nominal'),
            'transaksi'    => Tabungan::whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])->count(),
            'nasabah_baru' => Nasabah::whereBetween('created_at', [$dariTgl, $sampaiTgl])->count(),
        ];

        $laporanTabungan = Tabungan::with(['nasabah', 'jenisSampah', 'admin'])
                            ->whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])
                            ->orderByDesc('tanggal_setor')
                            ->paginate(15);

        $laporanPenarikan = Penarikan::with(['nasabah', 'diprosesoleh'])
                            ->whereBetween('created_at', [$dariTgl, $sampaiTgl])
                            ->orderByDesc('created_at')
                            ->paginate(15);

        $rekapJenis = Tabungan::with('jenisSampah')
                        ->whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])
                        ->selectRaw('jenis_sampah_id, SUM(berat_kg) as total_kg, SUM(nilai_rupiah) as total_nilai, COUNT(*) as total_transaksi')
                        ->groupBy('jenis_sampah_id')
                        ->orderByDesc('total_kg')
                        ->get();

        $rekapNasabah = Tabungan::with('nasabah')
                        ->whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])
                        ->selectRaw('nasabah_id, SUM(berat_kg) as total_kg, SUM(nilai_rupiah) as total_nilai, COUNT(*) as total_transaksi')
                        ->groupBy('nasabah_id')
                        ->orderByDesc('total_nilai')
                        ->get();

        $grafikBulanan = [];
        for ($i = 1; $i <= 12; $i++) {
            $grafikBulanan[] = [
                'bulan'   => Carbon::create()->month($i)->format('M'),
                'sampah'  => Tabungan::whereMonth('tanggal_setor', $i)->whereYear('tanggal_setor', $tahun)->sum('berat_kg'),
                'nilai'   => Tabungan::whereMonth('tanggal_setor', $i)->whereYear('tanggal_setor', $tahun)->sum('nilai_rupiah'),
                'cairkan' => Penarikan::where('status', 'selesai')->whereMonth('updated_at', $i)->whereYear('updated_at', $tahun)->sum('nominal'),
            ];
        }

        $auditLog  = AuditLog::with('user')->orderByDesc('created_at')->paginate(10);
        $tahunList = range(now()->year, now()->year - 3);

        return view('admin.laporan.index', compact(
            'ringkasan', 'statistikPeriode', 'laporanTabungan',
            'laporanPenarikan', 'rekapJenis', 'rekapNasabah',
            'grafikBulanan', 'auditLog', 'tahunList',
            'dariTgl', 'sampaiTgl', 'bulan', 'tahun',
        ));
    }


    // EXPORT PDF

    public function exportPdfTabungan(Request $request)
    {
        $dariTgl   = $request->dari_tanggal ?? now()->startOfMonth()->toDateString();
        $sampaiTgl = $request->sampai_tanggal ?? now()->toDateString();

        $tabungan = Tabungan::with(['nasabah', 'jenisSampah', 'admin'])
                    ->whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])
                    ->orderByDesc('tanggal_setor')->get();

        $rekapJenis = Tabungan::with('jenisSampah')
                        ->whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])
                        ->selectRaw('jenis_sampah_id, SUM(berat_kg) as total_kg, SUM(nilai_rupiah) as total_nilai, COUNT(*) as total_transaksi')
                        ->groupBy('jenis_sampah_id')->orderByDesc('total_kg')->get();

        $totalKg    = $tabungan->sum('berat_kg');
        $totalNilai = $tabungan->sum('nilai_rupiah');

        $pdf = Pdf::loadView('admin.laporan.pdf.tabungan', compact(
            'tabungan', 'rekapJenis', 'totalKg', 'totalNilai', 'dariTgl', 'sampaiTgl'
        ))->setPaper('a4', 'landscape');

        return $pdf->download("laporan-tabungan-{$dariTgl}-sd-{$sampaiTgl}.pdf");
    }

    public function exportPdfPenarikan(Request $request)
    {
        $dariTgl   = $request->dari_tanggal ?? now()->startOfMonth()->toDateString();
        $sampaiTgl = $request->sampai_tanggal ?? now()->toDateString();

        $penarikan    = Penarikan::with(['nasabah', 'diprosesoleh'])
                        ->whereBetween('created_at', [$dariTgl, $sampaiTgl])
                        ->orderByDesc('created_at')->get();
        $totalNominal = $penarikan->sum('nominal');
        $totalSelesai = $penarikan->where('status', 'selesai')->sum('nominal');

        $pdf = Pdf::loadView('admin.laporan.pdf.penarikan', compact(
            'penarikan', 'totalNominal', 'totalSelesai', 'dariTgl', 'sampaiTgl'
        ))->setPaper('a4', 'landscape');

        return $pdf->download("laporan-penarikan-{$dariTgl}-sd-{$sampaiTgl}.pdf");
    }

    public function exportPdfNasabah()
    {
        $nasabah = Nasabah::with(['tabungan', 'penarikan'])->orderBy('nama_lengkap')->get();
        $pdf     = Pdf::loadView('admin.laporan.pdf.nasabah', compact('nasabah'))->setPaper('a4', 'landscape');
        return $pdf->download("rekap-nasabah-" . now()->format('Y-m-d') . ".pdf");
    }

    public function laporanBulanan(Request $request)
    {
        $bulan     = $request->bulan ?? now()->month;
        $tahun     = $request->tahun ?? now()->year;
        $dariTgl   = Carbon::create($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $sampaiTgl = Carbon::create($tahun, $bulan, 1)->endOfMonth()->toDateString();

        $tabungan  = Tabungan::with(['nasabah', 'jenisSampah', 'admin'])
                    ->whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])
                    ->orderByDesc('tanggal_setor')->get();

        $penarikan = Penarikan::with(['nasabah', 'diprosesoleh'])
                    ->whereBetween('created_at', [$dariTgl, $sampaiTgl])
                    ->orderByDesc('created_at')->get();

        $rekapJenis = Tabungan::with('jenisSampah')
                        ->whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])
                        ->selectRaw('jenis_sampah_id, SUM(berat_kg) as total_kg, SUM(nilai_rupiah) as total_nilai, COUNT(*) as total_transaksi')
                        ->groupBy('jenis_sampah_id')->orderByDesc('total_kg')->get();

        $rekapNasabah = Tabungan::with('nasabah')
                        ->whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])
                        ->selectRaw('nasabah_id, SUM(berat_kg) as total_kg, SUM(nilai_rupiah) as total_nilai, COUNT(*) as total_transaksi')
                        ->groupBy('nasabah_id')->orderByDesc('total_nilai')->get();

        $totalNasabahAktif = Nasabah::where('status_akun', 'active')->count();
        $totalNasabahBaru  = Nasabah::whereBetween('created_at', [$dariTgl, $sampaiTgl])->count();
        $totalSampahKg     = $tabungan->sum('berat_kg');
        $totalNilai        = $tabungan->sum('nilai_rupiah');
        $totalDicairkan    = $penarikan->where('status', 'selesai')->sum('nominal');
        $namaBulan         = Carbon::create($tahun, $bulan, 1)->translatedFormat('F');

        $pdf = Pdf::loadView('admin.laporan.pdf.bulanan', compact(
            'tabungan', 'penarikan', 'rekapJenis', 'rekapNasabah',
            'totalNasabahAktif', 'totalNasabahBaru', 'totalSampahKg',
            'totalNilai', 'totalDicairkan', 'namaBulan', 'bulan', 'tahun',
            'dariTgl', 'sampaiTgl'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("laporan-bulanan-{$namaBulan}-{$tahun}.pdf");
    }

    public function kartuTabungan($nasabahId)
    {
        $nasabah = Nasabah::with(['user', 'tabungan.jenisSampah', 'penarikan'])->findOrFail($nasabahId);
        $pdf     = Pdf::loadView('admin.laporan.pdf.kartu-tabungan', compact('nasabah'))->setPaper('a4', 'portrait');
        return $pdf->download("kartu-tabungan-{$nasabah->nama_lengkap}.pdf");
    }

    public function rekapTahunan(Request $request)
    {
        $tahun         = $request->tahun ?? now()->year;
        $rekapPerBulan = [];

        for ($i = 1; $i <= 12; $i++) {
            $dariTgl   = Carbon::create($tahun, $i, 1)->startOfMonth()->toDateString();
            $sampaiTgl = Carbon::create($tahun, $i, 1)->endOfMonth()->toDateString();
            $rekapPerBulan[] = [
                'bulan'           => Carbon::create($tahun, $i, 1)->translatedFormat('F'),
                'total_kg'        => Tabungan::whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])->sum('berat_kg'),
                'total_nilai'     => Tabungan::whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])->sum('nilai_rupiah'),
                'total_transaksi' => Tabungan::whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])->count(),
                'total_dicairkan' => Penarikan::where('status', 'selesai')->whereBetween('updated_at', [$dariTgl, $sampaiTgl])->sum('nominal'),
                'nasabah_baru'    => Nasabah::whereBetween('created_at', [$dariTgl, $sampaiTgl])->count(),
            ];
        }

        $rekapJenis = Tabungan::with('jenisSampah')
                        ->whereYear('tanggal_setor', $tahun)
                        ->selectRaw('jenis_sampah_id, SUM(berat_kg) as total_kg, SUM(nilai_rupiah) as total_nilai, COUNT(*) as total_transaksi')
                        ->groupBy('jenis_sampah_id')->orderByDesc('total_kg')->get();

        $pdf = Pdf::loadView('admin.laporan.pdf.tahunan', compact('rekapPerBulan', 'rekapJenis', 'tahun'))
                ->setPaper('a4', 'portrait');

        return $pdf->download("rekap-tahunan-{$tahun}.pdf");
    }

    public function laporanHarian(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();

        $tabungan = Tabungan::with(['nasabah', 'jenisSampah', 'admin'])
                    ->whereDate('tanggal_setor', $tanggal)
                    ->orderBy('created_at')->get();

        $penarikan = Penarikan::with(['nasabah', 'diprosesoleh'])
                    ->whereDate('created_at', $tanggal)
                    ->orderBy('created_at')->get();

        $rekapJenis = Tabungan::with('jenisSampah')
                        ->whereDate('tanggal_setor', $tanggal)
                        ->selectRaw('jenis_sampah_id, SUM(berat_kg) as total_kg, SUM(nilai_rupiah) as total_nilai, COUNT(*) as total_transaksi')
                        ->groupBy('jenis_sampah_id')->orderByDesc('total_kg')->get();

        $totalKg        = $tabungan->sum('berat_kg');
        $totalNilai     = $tabungan->sum('nilai_rupiah');
        $totalDicairkan = $penarikan->where('status', 'selesai')->sum('nominal');
        $tanggalFormat  = Carbon::parse($tanggal)->translatedFormat('d F Y');

        $pdf = Pdf::loadView('admin.laporan.pdf.harian', compact(
            'tabungan', 'penarikan', 'rekapJenis',
            'totalKg', 'totalNilai', 'totalDicairkan', 'tanggal', 'tanggalFormat'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("laporan-harian-{$tanggal}.pdf");
    }

    public function laporanMingguan(Request $request)
    {
        $tanggal   = $request->tanggal ?? now()->toDateString();
        $dariTgl   = Carbon::parse($tanggal)->startOfWeek()->toDateString();
        $sampaiTgl = Carbon::parse($tanggal)->endOfWeek()->toDateString();

        $tabungan = Tabungan::with(['nasabah', 'jenisSampah', 'admin'])
                    ->whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])
                    ->orderBy('tanggal_setor')->get();

        $penarikan = Penarikan::with(['nasabah', 'diprosesoleh'])
                    ->whereBetween('created_at', [$dariTgl, $sampaiTgl])
                    ->orderBy('created_at')->get();

        $rekapJenis = Tabungan::with('jenisSampah')
                        ->whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])
                        ->selectRaw('jenis_sampah_id, SUM(berat_kg) as total_kg, SUM(nilai_rupiah) as total_nilai, COUNT(*) as total_transaksi')
                        ->groupBy('jenis_sampah_id')->orderByDesc('total_kg')->get();

        $rekapHarian = [];
        $current     = Carbon::parse($dariTgl);
        while ($current->lte(Carbon::parse($sampaiTgl))) {
            $tgl           = $current->toDateString();
            $rekapHarian[] = [
                'tanggal'     => $current->translatedFormat('l, d F Y'),
                'total_kg'    => Tabungan::whereDate('tanggal_setor', $tgl)->sum('berat_kg'),
                'total_nilai' => Tabungan::whereDate('tanggal_setor', $tgl)->sum('nilai_rupiah'),
                'transaksi'   => Tabungan::whereDate('tanggal_setor', $tgl)->count(),
                'dicairkan'   => Penarikan::where('status', 'selesai')->whereDate('updated_at', $tgl)->sum('nominal'),
            ];
            $current->addDay();
        }

        $totalKg        = $tabungan->sum('berat_kg');
        $totalNilai     = $tabungan->sum('nilai_rupiah');
        $totalDicairkan = $penarikan->where('status', 'selesai')->sum('nominal');
        $mingguKe       = Carbon::parse($dariTgl)->weekOfYear();

        $pdf = Pdf::loadView('admin.laporan.pdf.mingguan', compact(
            'tabungan', 'penarikan', 'rekapJenis', 'rekapHarian',
            'totalKg', 'totalNilai', 'totalDicairkan', 'dariTgl', 'sampaiTgl', 'mingguKe'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("laporan-mingguan-minggu-{$mingguKe}.pdf");
    }


    public function exportExcelTabungan(Request $request)
    {
        $dariTgl   = $request->dari_tanggal ?? now()->startOfMonth()->toDateString();
        $sampaiTgl = $request->sampai_tanggal ?? now()->toDateString();

        $data = Tabungan::with(['nasabah', 'jenisSampah', 'admin'])
                ->whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])
                ->orderByDesc('tanggal_setor')
                ->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Tidak ada data tabungan pada periode ini!');
        }

        $rows = $data->map(function ($t, $i) {
            return [
                'No'                => $i + 1,
                'Tanggal Setor'     => $t->tanggal_setor ? $t->tanggal_setor->format('d/m/Y') : '-',
                'Nama Nasabah'      => $t->nasabah->nama_lengkap ?? '-',
                'No KTP'            => $t->nasabah->no_ktp ?? '-',
                'No Telepon'        => $t->nasabah->no_telepon ?? '-',
                'Jenis Sampah'      => $t->jenisSampah->nama ?? '-',
                'Kategori'          => $t->jenisSampah->kategori ?? '-',
                'Berat (kg)'        => (float) $t->berat_kg,
                'Harga per kg (Rp)' => (float) $t->harga_per_kg_saat_itu,
                'Nilai (Rp)'        => (float) $t->nilai_rupiah,
                'Diinput Oleh'      => $t->admin->name ?? '-',
                'Catatan'           => $t->catatan ?? '-',
            ];
        });

        return (new FastExcel($rows))->download("laporan-tabungan-{$dariTgl}-sd-{$sampaiTgl}.xlsx");
    }

    public function exportExcelPenarikan(Request $request)
    {
        $dariTgl   = $request->dari_tanggal ?? now()->startOfMonth()->toDateString();
        $sampaiTgl = $request->sampai_tanggal ?? now()->toDateString();

        $data = Penarikan::with(['nasabah', 'diprosesoleh'])
                ->whereBetween('created_at', [$dariTgl, $sampaiTgl])
                ->orderByDesc('created_at')
                ->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Tidak ada data penarikan pada periode ini!');
        }

        $rows = $data->map(function ($p, $i) {
            return [
                'No'                => $i + 1,
                'Tanggal Ajuan'     => $p->created_at ? $p->created_at->format('d/m/Y H:i') : '-',
                'Nama Nasabah'      => $p->nasabah->nama_lengkap ?? '-',
                'No KTP'            => $p->nasabah->no_ktp ?? '-',
                'No Telepon'        => $p->nasabah->no_telepon ?? '-',
                'Nominal (Rp)'      => (float) $p->nominal,
                'Tanggal Ambil'     => $p->tanggal_ambil ? $p->tanggal_ambil->format('d/m/Y') : '-',
                'Status'            => ucfirst($p->status),
                'Alasan Penolakan'  => $p->alasan_penolakan ?? '-',
                'Catatan Admin'     => $p->catatan_admin ?? '-',
                'Diproses Oleh'     => $p->diprosesoleh->name ?? '-',
                'Tanggal Proses'    => $p->tanggal_proses ? $p->tanggal_proses->format('d/m/Y H:i') : '-',
            ];
        });

        return (new FastExcel($rows))->download("laporan-penarikan-{$dariTgl}-sd-{$sampaiTgl}.xlsx");
    }

    public function exportExcelNasabah()
    {
        $data = Nasabah::with(['tabungan', 'penarikan', 'user'])
                ->orderBy('nama_lengkap')
                ->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Tidak ada data nasabah!');
        }

        $rows = $data->map(function ($n, $i) {
            return [
                'No'                    => $i + 1,
                'Nama Lengkap'          => $n->nama_lengkap,
                'Email'                 => $n->user->email ?? '-',
                'No KTP'                => $n->no_ktp ?? '-',
                'No Telepon'            => $n->no_telepon ?? '-',
                'Alamat'                => $n->alamat ?? '-',
                'Tanggal Bergabung'     => $n->tanggal_bergabung ? $n->tanggal_bergabung->format('d/m/Y') : '-',
                'Status Akun'           => ucfirst($n->status_akun),
                'Sumber Daftar'         => $n->sumber_daftar == 'admin' ? 'Input Admin' : 'Daftar Sendiri',
                'Total Sampah (kg)'     => (float) $n->total_sampah,
                'Total Tabungan (Rp)'   => (float) $n->tabungan->sum('nilai_rupiah'),
                'Total Penarikan (Rp)'  => (float) $n->penarikan->whereIn('status', ['selesai', 'diproses'])->sum('nominal'),
                'Saldo Aktif (Rp)'      => (float) $n->saldo,
            ];
        });

        return (new FastExcel($rows))->download("rekap-nasabah-" . now()->format('Y-m-d') . ".xlsx");
    }

    public function exportExcelHarian(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();

        $data = Tabungan::with(['nasabah', 'jenisSampah', 'admin'])
                ->whereDate('tanggal_setor', $tanggal)
                ->orderBy('created_at')
                ->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Tidak ada data tabungan pada tanggal ' . Carbon::parse($tanggal)->format('d/m/Y') . '!');
        }

        $rows = $data->map(function ($t, $i) {
            return [
                'No'                => $i + 1,
                'Waktu Input'       => $t->created_at ? $t->created_at->format('H:i:s') : '-',
                'Tanggal Setor'     => $t->tanggal_setor ? $t->tanggal_setor->format('d/m/Y') : '-',
                'Nama Nasabah'      => $t->nasabah->nama_lengkap ?? '-',
                'No KTP'            => $t->nasabah->no_ktp ?? '-',
                'No Telepon'        => $t->nasabah->no_telepon ?? '-',
                'Jenis Sampah'      => $t->jenisSampah->nama ?? '-',
                'Kategori'          => $t->jenisSampah->kategori ?? '-',
                'Berat (kg)'        => (float) $t->berat_kg,
                'Harga per kg (Rp)' => (float) $t->harga_per_kg_saat_itu,
                'Nilai (Rp)'        => (float) $t->nilai_rupiah,
                'Diinput Oleh'      => $t->admin->name ?? '-',
                'Catatan'           => $t->catatan ?? '-',
            ];
        });

        return (new FastExcel($rows))->download("laporan-harian-{$tanggal}.xlsx");
    }

    public function exportExcelMingguan(Request $request)
    {
        $tanggal   = $request->tanggal ?? now()->toDateString();
        $dariTgl   = Carbon::parse($tanggal)->startOfWeek()->toDateString();
        $sampaiTgl = Carbon::parse($tanggal)->endOfWeek()->toDateString();

        $data = Tabungan::with(['nasabah', 'jenisSampah', 'admin'])
                ->whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])
                ->orderBy('tanggal_setor')
                ->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Tidak ada data tabungan pada minggu ini!');
        }

        $rows = $data->map(function ($t, $i) {
            return [
                'No'                => $i + 1,
                'Tanggal Setor'     => $t->tanggal_setor ? $t->tanggal_setor->format('d/m/Y') : '-',
                'Hari'              => $t->tanggal_setor ? $t->tanggal_setor->translatedFormat('l') : '-',
                'Nama Nasabah'      => $t->nasabah->nama_lengkap ?? '-',
                'No KTP'            => $t->nasabah->no_ktp ?? '-',
                'No Telepon'        => $t->nasabah->no_telepon ?? '-',
                'Jenis Sampah'      => $t->jenisSampah->nama ?? '-',
                'Kategori'          => $t->jenisSampah->kategori ?? '-',
                'Berat (kg)'        => (float) $t->berat_kg,
                'Harga per kg (Rp)' => (float) $t->harga_per_kg_saat_itu,
                'Nilai (Rp)'        => (float) $t->nilai_rupiah,
                'Diinput Oleh'      => $t->admin->name ?? '-',
                'Catatan'           => $t->catatan ?? '-',
            ];
        });

        return (new FastExcel($rows))->download("laporan-mingguan-{$dariTgl}-sd-{$sampaiTgl}.xlsx");
    }

    public function exportExcelBulanan(Request $request)
    {
        $bulan     = $request->bulan ?? now()->month;
        $tahun     = $request->tahun ?? now()->year;
        $dariTgl   = Carbon::create($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $sampaiTgl = Carbon::create($tahun, $bulan, 1)->endOfMonth()->toDateString();
        $namaBulan = Carbon::create($tahun, $bulan, 1)->translatedFormat('F');

        $data = Tabungan::with(['nasabah', 'jenisSampah', 'admin'])
                ->whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])
                ->orderBy('tanggal_setor')
                ->get();

        if ($data->isEmpty()) {
            return back()->with('error', "Tidak ada data tabungan bulan {$namaBulan} {$tahun}!");
        }

        $rows = $data->map(function ($t, $i) {
            return [
                'No'                => $i + 1,
                'Tanggal Setor'     => $t->tanggal_setor ? $t->tanggal_setor->format('d/m/Y') : '-',
                'Nama Nasabah'      => $t->nasabah->nama_lengkap ?? '-',
                'No KTP'            => $t->nasabah->no_ktp ?? '-',
                'No Telepon'        => $t->nasabah->no_telepon ?? '-',
                'Jenis Sampah'      => $t->jenisSampah->nama ?? '-',
                'Kategori'          => $t->jenisSampah->kategori ?? '-',
                'Berat (kg)'        => (float) $t->berat_kg,
                'Harga per kg (Rp)' => (float) $t->harga_per_kg_saat_itu,
                'Nilai (Rp)'        => (float) $t->nilai_rupiah,
                'Diinput Oleh'      => $t->admin->name ?? '-',
                'Catatan'           => $t->catatan ?? '-',
            ];
        });

        return (new FastExcel($rows))->download("laporan-bulanan-{$namaBulan}-{$tahun}.xlsx");
    }

    public function exportExcelTahunan(Request $request)
    {
        $tahun = $request->tahun ?? now()->year;
        $rows  = collect();

        for ($i = 1; $i <= 12; $i++) {
            $dariTgl   = Carbon::create($tahun, $i, 1)->startOfMonth()->toDateString();
            $sampaiTgl = Carbon::create($tahun, $i, 1)->endOfMonth()->toDateString();
            $namaBulan = Carbon::create($tahun, $i, 1)->translatedFormat('F');

            $rows->push([
                'No'                   => $i,
                'Bulan'                => $namaBulan,
                'Tahun'                => $tahun,
                'Total Sampah (kg)'    => (float) Tabungan::whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])->sum('berat_kg'),
                'Total Nilai (Rp)'     => (float) Tabungan::whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])->sum('nilai_rupiah'),
                'Total Transaksi'      => (int) Tabungan::whereBetween('tanggal_setor', [$dariTgl, $sampaiTgl])->count(),
                'Total Dicairkan (Rp)' => (float) Penarikan::where('status', 'selesai')->whereBetween('updated_at', [$dariTgl, $sampaiTgl])->sum('nominal'),
                'Nasabah Baru'         => (int) Nasabah::whereBetween('created_at', [$dariTgl, $sampaiTgl])->count(),
            ]);
        }

        return (new FastExcel($rows))->download("rekap-tahunan-{$tahun}.xlsx");
    }

    public function exportExcelAuditLog(Request $request)
    {
        $dariTgl   = $request->dari_tanggal ?? now()->startOfMonth()->toDateString();
        $sampaiTgl = $request->sampai_tanggal ?? now()->toDateString();

        $data = AuditLog::with('user')
                ->whereBetween('created_at', [$dariTgl . ' 00:00:00', $sampaiTgl . ' 23:59:59'])
                ->orderByDesc('created_at')
                ->get();

        if ($data->isEmpty()) {
            return back()->with('error', 'Tidak ada log aktivitas pada periode ini!');
        }

        $rows = $data->map(function ($log, $i) {
            return [
                'No'          => $i + 1,
                'Waktu'       => $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-',
                'User'        => $log->user_name ?? '-',
                'Role'        => ucfirst($log->role ?? '-'),
                'Aksi'        => $log->action ?? '-',
                'Modul'       => $log->module ?? '-',
                'Deskripsi'   => $log->description ?? '-',
                'IP Address'  => $log->ip_address ?? '-',
                'Status'      => ucfirst($log->status ?? '-'),
            ];
        });

        return (new FastExcel($rows))->download("audit-log-{$dariTgl}-sd-{$sampaiTgl}.xlsx");
    }
}
