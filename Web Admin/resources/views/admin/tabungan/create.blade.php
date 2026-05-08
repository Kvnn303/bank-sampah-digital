@extends('layouts.admin')

@section('title', 'Input Tabungan Sampah')
@section('page-title', 'Input Tabungan Sampah')

@section('content')

<div class="row g-3">

    <!-- Form Input Utama -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Transaksi Setoran</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.tabungan.store') }}" id="formTabungan">
                    @csrf

                    <div class="row g-3 mb-4">
                        <!-- Pilih Nasabah -->
                        <div class="col-md-6">
                            <label class="form-label required">Nasabah</label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/></svg>
                                </span>
                                <select name="nasabah_id" id="nasabah_id" class="form-select @error('nasabah_id') is-invalid @enderror" onchange="loadSaldoNasabah(this.value)" required>
                                    <option value="">-- Cari/Pilih Nasabah --</option>
                                    @foreach($nasabahList as $n)
                                        <option value="{{ $n->id }}"
                                                data-saldo="{{ $n->saldo }}"
                                                data-nama="{{ $n->nama_lengkap }}"
                                                {{ old('nasabah_id') == $n->id ? 'selected' : '' }}>
                                            {{ $n->nama_lengkap }} ({{ $n->no_telepon ?? $n->no_ktp ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('nasabah_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            
                            <!-- Info Saldo -->
                            <div id="infoNasabah" class="mt-2 p-2 bg-light border rounded small d-none">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Saldo Saat Ini:</span>
                                    <span class="fw-bold text-success" id="saldoNasabah">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Setor -->
                        <div class="col-md-6">
                            <label class="form-label required">Tanggal Setor</label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2"/><path d="M16 3l0 4"/><path d="M8 3l0 4"/><path d="M4 11l16 0"/></svg>
                                </span>
                                <input type="date" name="tanggal_setor" class="form-control @error('tanggal_setor') is-invalid @enderror" value="{{ old('tanggal_setor', now()->toDateString()) }}" required>
                            </div>
                            @error('tanggal_setor') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Tabel Input Sampah -->
                    <div class="mb-3">
                        <label class="form-label required">Detail Sampah</label>
                        
                        <!-- Wrapper Responsive untuk Mobile -->
                        <div class="table-responsive border rounded">
                            <table class="table table-vcenter card-table mb-0" id="tabelSampah">
                                <thead class="table-light">
                                    <tr>
                                        <th>Jenis Sampah</th>
                                        <th class="text-center" style="width: 100px;">Berat (kg)</th>
                                        <th class="text-end" style="width: 130px;">Harga/kg</th>
                                        <th class="text-end" style="width: 140px;">Nilai</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="bodySampah">
                                    <!-- Baris Default -->
                                    <tr id="row-0">
                                        <td>
                                            <select name="items[0][jenis_sampah_id]" class="form-select form-select-sm jenis-sampah" onchange="hitungNilai(0)" required>
                                                <option value="">-- Pilih Jenis --</option>
                                                @foreach($jenisSampahList as $j)
                                                    <option value="{{ $j->id }}" data-harga="{{ $j->harga_per_kg }}">
                                                        {{ $j->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][berat_kg]" class="form-control form-control-sm berat-kg text-center" placeholder="0.0" step="0.1" min="0.1" oninput="hitungNilai(0)" required>
                                        </td>
                                        <td>
                                            <input type="text" id="harga-0" class="form-control-plaintext text-end small" readonly value="Rp 0">
                                        </td>
                                        <td>
                                            <input type="text" id="nilai-0" class="form-control-plaintext text-end fw-bold text-success" readonly value="Rp 0">
                                            <input type="hidden" name="items[0][nilai]" id="nilai-input-0">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-ghost-danger" onclick="hapusBaris(0)" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer Tabel: Total & Tombol Tambah -->
                        <div class="bg-light mt-0 p-2 border border-top-0 rounded-bottom">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="tambahBaris()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                                        Tambah Jenis
                                    </button>
                                </div>
                                <div class="col text-end">
                                    <div class="me-2">
                                        <span class="text-muted small">Total Berat: </span>
                                        <strong id="totalBerat">0 kg</strong>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="bg-white border rounded px-3 py-1">
                                        <span class="text-muted small">Total Nilai: </span>
                                        <strong class="text-success" id="totalNilai">Rp 0</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan jika ada...">{{ old('catatan') }}</textarea>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.tabungan.index') }}" class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l-4 -4l4 -4"/><path d="M5 10h11a4 4 0 1 1 0 8h-1"/></svg>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h16l-2 13l-6 2l-6 -2z"/><path d="M6 4l-2 1.5"/><path d="M6 4v1.5"/><path d="M6 4h-2v2.5"/><path d="M12 16v4"/><path d="M12 16l4 -2"/></svg>
                            Simpan Transaksi
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        
        <!-- Harga Sampah -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Harga Aktif</h3>
            </div>
            <div class="list-group list-group-flush list-group-hoverable">
                @foreach($jenisSampahList as $j)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold">{{ $j->nama }}</div>
                        <div class="text-muted small">{{ $j->kategori ?? '-' }}</div>
                    </div>
                    <div class="text-success fw-bold">
                        Rp {{ number_format($j->harga_per_kg) }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Panduan -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Alur Input Cepat</h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled steps steps-vertical mb-0">
                    <li class="step-item">
                        <div class="h6 mb-0">Pilih Nasabah</div>
                        <small class="text-muted">Cari nama atau pilih dari daftar.</small>
                    </li>
                    <li class="step-item">
                        <div class="h6 mb-0">Input Sampah</div>
                        <small class="text-muted">Pilih jenis dan timbang beratnya.</small>
                    </li>
                    <li class="step-item">
                        <div class="h6 mb-0">Sistem Hitung Otomatis</div>
                        <small class="text-muted">Total nilai dihitung real-time.</small>
                    </li>
                    <li class="step-item">
                        <div class="h6 mb-0">Simpan</div>
                        <small class="text-muted">Pastikan data sudah benar.</small>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    let rowCount = 1;
    const jenisSampahData = @json($jenisSampahList->keyBy('id'));

    // Load info saldo nasabah
    function loadSaldoNasabah(nasabahId) {
        const select = document.getElementById('nasabah_id');
        const option = select.options[select.selectedIndex];
        const infoBox = document.getElementById('infoNasabah');

        if (nasabahId && option.dataset.saldo !== undefined) {
            const saldo = parseInt(option.dataset.saldo);
            document.getElementById('saldoNasabah').textContent = 'Rp ' + saldo.toLocaleString('id-ID');
            infoBox.classList.remove('d-none');
        } else {
            infoBox.classList.add('d-none');
        }
    }

    // Hitung nilai per baris
    function hitungNilai(index) {
        const row = document.getElementById('row-' + index);
        if (!row) return;

        const jenisSelect = row.querySelector('.jenis-sampah');
        const beratInput = row.querySelector('.berat-kg');
        const hargaInput = document.getElementById('harga-' + index);
        const nilaiText = document.getElementById('nilai-' + index);
        const nilaiHidden = document.getElementById('nilai-input-' + index);

        const jenisId = jenisSelect.value;
        const berat = parseFloat(beratInput.value) || 0;

        let harga = 0;
        let nilai = 0;

        if (jenisId && jenisSampahData[jenisId]) {
            harga = parseFloat(jenisSampahData[jenisId].harga_per_kg);
            nilai = harga * berat;
        }

        hargaInput.value = 'Rp ' + harga.toLocaleString('id-ID');
        nilaiText.value = 'Rp ' + nilai.toLocaleString('id-ID');
        if(nilaiHidden) nilaiHidden.value = nilai;

        hitungTotal();
    }

    // Hitung total semua
    function hitungTotal() {
        let totalNilai = 0;
        let totalBerat = 0;

        // Loop semua input berat
        document.querySelectorAll('.berat-kg').forEach(input => {
            totalBerat += parseFloat(input.value) || 0;
        });

        // Loop semua hidden nilai (lebih akurat daripada parsing text)
        document.querySelectorAll('[id^="nilai-input-"]').forEach(input => {
            totalNilai += parseFloat(input.value) || 0;
        });
        
        // Fallback jika hidden input belum ada
        if(totalNilai === 0) {
             document.querySelectorAll('.nilai-rupiah').forEach(input => {
                // Parsing text Rp 100.000 -> 100000
                const val = input.value.replace(/[^0-9]/g, '');
                totalNilai += parseInt(val) || 0;
            });
        }

        document.getElementById('totalNilai').textContent = 'Rp ' + totalNilai.toLocaleString('id-ID');
        document.getElementById('totalBerat').textContent = totalBerat.toFixed(1) + ' kg';
    }

    // Tambah baris baru
    function tambahBaris() {
        const idx = rowCount;
        const tbody = document.getElementById('bodySampah');

        // Generate options untuk select
        let options = '<option value="">-- Pilih Jenis --</option>';
        Object.values(jenisSampahData).forEach(j => {
            options += `<option value="${j.id}" data-harga="${j.harga_per_kg}">${j.nama}</option>`;
        });

        const rowHtml = `
        <tr id="row-${idx}">
            <td>
                <select name="items[${idx}][jenis_sampah_id]" class="form-select form-select-sm jenis-sampah" onchange="hitungNilai(${idx})" required>
                    ${options}
                </select>
            </td>
            <td>
                <input type="number" name="items[${idx}][berat_kg]" class="form-control form-control-sm berat-kg text-center" placeholder="0.0" step="0.1" min="0.1" oninput="hitungNilai(${idx})" required>
            </td>
            <td>
                <input type="text" id="harga-${idx}" class="form-control-plaintext text-end small" readonly value="Rp 0">
            </td>
            <td>
                <input type="text" id="nilai-${idx}" class="form-control-plaintext text-end fw-bold text-success" readonly value="Rp 0">
                <input type="hidden" name="items[${idx}][nilai]" id="nilai-input-${idx}">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-ghost-danger" onclick="hapusBaris(${idx})">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>
                </button>
            </td>
        </tr>`;

        tbody.insertAdjacentHTML('beforeend', rowHtml);
        rowCount++;
    }

    // Hapus baris
    function hapusBaris(index) {
        const row = document.getElementById('row-' + index);
        if (row) {
            row.remove();
            hitungTotal();
        }
    }

    // Trigger initial calculation if old input exists
    document.addEventListener('DOMContentLoaded', function() {
    });
</script>
@endpush