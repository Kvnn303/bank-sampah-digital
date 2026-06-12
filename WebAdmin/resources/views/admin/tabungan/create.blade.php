@extends('layouts.admin')

@section('title', 'Input Tabungan Sampah')
@section('page-title', 'Input Tabungan Sampah')

@push('styles')
<style>
    /* Modern Card Styles */
    .card-modern {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
        margin-bottom: 1.5rem;
    }
    .card-header-modern {
        background: transparent;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
    }

    /* Modern Inputs */
    .form-control-modern, .form-select-modern {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    .form-control-modern:focus, .form-select-modern:focus {
        background-color: #ffffff;
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    .input-icon-addon {
        color: #94a3b8;
    }

    /* Table Styles */
    .table-input-modern th {
        background-color: #f8fafc !important;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem;
    }
    .table-input-modern td {
        vertical-align: middle;
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .row-total {
        background: linear-gradient(to right, #f8fafc, #ecfdf5);
        border-radius: 12px;
        padding: 1.25rem;
        margin-top: 1rem;
        border: 1px solid #d1fae5;
    }

    /* Price List Hover */
    .price-list-item {
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }
    .price-list-item:hover {
        background-color: #f8fafc;
        border-left-color: #10b981;
        transform: translateX(4px);
    }

    /* Custom Timeline Steps */
    .step-modern {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 1.5rem;
    }
    .step-modern::before {
        content: '';
        position: absolute;
        left: 0;
        top: 4px;
        bottom: -1.5rem;
        width: 2px;
        background-color: #e2e8f0;
    }
    .step-modern:last-child::before {
        display: none;
    }
    .step-modern::after {
        content: '';
        position: absolute;
        left: -4px;
        top: 6px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
    }

    .btn-add-row {
        border: 2px dashed #cbd5e1;
        color: #64748b;
        background: transparent;
        transition: all 0.3s ease;
    }
    .btn-add-row:hover {
        border-color: #10b981;
        color: #10b981;
        background: #ecfdf5;
    }
</style>
@endpush

@section('content')

<div class="row g-4">
    <!-- Form Input Utama -->
    <div class="col-lg-8">
        <div class="card card-modern">
            <div class="card-header card-header-modern d-flex align-items-center">
                <span class="bg-primary-lt text-primary p-2 rounded-3 me-3 d-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                </span>
                <div>
                    <h3 class="card-title fw-bold text-dark m-0">Form Transaksi Setoran</h3>
                    <div class="text-muted small mt-1">Masukkan detail sampah yang disetor nasabah.</div>
                </div>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.tabungan.store') }}" id="formTabungan">
                    @csrf

                    <div class="row g-4 mb-4">
                        <!-- Pilih Nasabah -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark required">Pilih Nasabah</label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/></svg>
                                </span>
                                <select name="nasabah_id" id="nasabah_id" class="form-select form-select-modern @error('nasabah_id') is-invalid @enderror" onchange="loadSaldoNasabah(this.value)" required>
                                    <option value="">-- Cari atau Pilih Nasabah --</option>
                                    @foreach($nasabahList as $n)
                                        <option value="{{ $n->id }}"
                                                data-saldo="{{ $n->saldo_aktif }}"
                                                data-nama="{{ $n->nama_lengkap }}"
                                                {{ old('nasabah_id') == $n->id ? 'selected' : '' }}>
                                            {{ $n->nama_lengkap }} ({{ $n->no_telepon ?? $n->no_ktp ?? '-' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('nasabah_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                            <!-- Info Saldo -->
                            <div id="infoNasabah" class="mt-3 p-3 bg-indigo-lt border-0 rounded-3 d-none">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-indigo fw-medium d-flex align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 19l18 0"/><path d="M5 6l14 0"/><path d="M5 9l14 0"/><path d="M5 12l14 0"/><path d="M5 15l14 0"/></svg>
                                        Saldo Saat Ini:
                                    </span>
                                    <span class="fw-bold text-indigo fs-3" id="saldoNasabah">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Setor -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark required">Tanggal Setor</label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2"/><path d="M16 3l0 4"/><path d="M8 3l0 4"/><path d="M4 11l16 0"/></svg>
                                </span>
                                <input type="date" name="tanggal_setor" class="form-control form-control-modern @error('tanggal_setor') is-invalid @enderror" value="{{ old('tanggal_setor', now()->toDateString()) }}" min="{{ now()->toDateString() }}" required>
                            </div>
                            @error('tanggal_setor') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <hr class="my-4 text-slate-200">

                    <!-- Tabel Input Sampah -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark required mb-3">Detail Setoran Sampah</label>

                        <div class="table-responsive border border-slate-200 rounded-3 mb-3">
                            <table class="table table-input-modern mb-0" id="tabelSampah">
                                <thead>
                                    <tr>
                                        <th>Kategori / Jenis Sampah</th>
                                        <th class="text-center" style="width: 120px;">Berat (kg)</th>
                                        <th class="text-end" style="width: 140px;">Harga/kg</th>
                                        <th class="text-end" style="width: 160px;">Total Nilai</th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="bodySampah">
                                    <!-- Baris Default -->
                                    <tr id="row-0">
                                        <td>
                                            <select name="items[0][jenis_sampah_id]" class="form-select form-select-modern jenis-sampah" onchange="hitungNilai(0)" required>
                                                <option value="">-- Pilih Jenis Sampah --</option>
                                                @foreach($jenisSampahList as $j)
                                                    <option value="{{ $j->id }}" data-harga="{{ $j->harga_per_kg }}">
                                                        {{ $j->nama }} {{ $j->kategori ? '('.$j->kategori.')' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][berat_kg]" class="form-control form-control-modern berat-kg text-center fw-bold text-primary" placeholder="0.0" step="0.1" min="0.1" oninput="hitungNilai(0)" required>
                                        </td>
                                        <td>
                                            <input type="text" id="harga-0" class="form-control-plaintext text-end text-muted" readonly value="Rp 0">
                                        </td>
                                        <td>
                                            <input type="text" id="nilai-0" class="form-control-plaintext text-end fw-bold text-dark fs-4" readonly value="Rp 0">
                                            <input type="hidden" name="items[0][nilai]" id="nilai-input-0">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-icon btn-light text-muted" onclick="hapusBaris(0)" disabled title="Hapus Baris">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tombol Tambah Baris -->
                        <button type="button" class="btn w-100 btn-add-row py-2 rounded-3 fw-bold mb-4" onclick="tambahBaris()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                            Tambah Baris Sampah Baru
                        </button>

                        <!-- Box Total (Modern Receipt Style) -->
                        <div class="row-total d-flex justify-content-between align-items-center shadow-sm">
                            <div>
                                <div class="text-emerald fw-bold mb-1">TOTAL KESELURUHAN</div>
                                <div class="text-muted small">Total Berat: <strong id="totalBerat" class="text-dark">0 kg</strong></div>
                            </div>
                            <div class="text-end">
                                <div class="text-muted small mb-1">Total Nilai Tabungan</div>
                                <h2 class="text-emerald fw-black mb-0 m-0" id="totalNilai" style="font-size: 2rem; letter-spacing: -1px;">Rp 0</h2>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-4 mt-4">
                        <label class="form-label fw-bold text-dark">Catatan Tambahan <span class="text-muted fw-normal">(Opsional)</span></label>
                        <textarea name="catatan" class="form-control form-control-modern" rows="3" placeholder="Tuliskan catatan khusus jika diperlukan...">{{ old('catatan') }}</textarea>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                        <a href="{{ route('admin.tabungan.index') }}" class="btn btn-light px-4 rounded-pill fw-bold">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h16l-2 13l-6 2l-6 -2z"/><path d="M6 4l-2 1.5"/><path d="M6 4v1.5"/><path d="M6 4h-2v2.5"/><path d="M12 16v4"/><path d="M12 16l4 -2"/></svg>
                            Simpan Transaksi
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">

        <!-- Panduan Cepat (Timeline) -->
        <div class="card card-modern mb-4">
            <div class="card-header card-header-modern">
                <h3 class="card-title fw-bold text-dark m-0 d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M12 9v2m0 4h.01"/></svg>
                    Alur Input Cepat
                </h3>
            </div>
            <div class="card-body p-4">
                <div class="step-modern">
                    <div class="fw-bold text-dark mb-1">Pilih Nasabah</div>
                    <div class="text-muted small">Cari nama atau NIK dari daftar dropdown.</div>
                </div>
                <div class="step-modern">
                    <div class="fw-bold text-dark mb-1">Pilih Jenis & Timbang</div>
                    <div class="text-muted small">Pilih jenis sampah dan masukkan beratnya (dalam Kg).</div>
                </div>
                <div class="step-modern">
                    <div class="fw-bold text-dark mb-1">Sistem Hitung Otomatis</div>
                    <div class="text-muted small">Nilai rupiah akan otomatis dihitung oleh sistem.</div>
                </div>
                <div class="step-modern">
                    <div class="fw-bold text-dark mb-1">Simpan Data</div>
                    <div class="text-muted small">Klik simpan dan saldo nasabah akan bertambah seketika.</div>
                </div>
            </div>
        </div>

        <!-- Harga Sampah Terkini -->
        <div class="card card-modern border-top border-primary border-3">
            <div class="card-header card-header-modern bg-white">
                <h3 class="card-title fw-bold text-dark m-0">Daftar Harga Beli Terkini</h3>
            </div>
            <div class="list-group list-group-flush">
                @forelse($jenisSampahList as $j)
                <div class="list-group-item price-list-item py-3 px-4 border-bottom-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold text-dark">{{ $j->nama }}</div>
                            <div class="text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">{{ $j->kategori ?? 'Umum' }}</div>
                        </div>
                        <div class="text-primary fw-black fs-4">
                            Rp {{ number_format($j->harga_per_kg, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="list-group-item text-center text-muted py-4 border-0">
                    Belum ada data harga sampah.
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let rowCount = 1;
    const jenisSampahData = @json($jenisSampahList->keyBy('id'));

    // Load info saldo nasabah
    function loadSaldoNasabah(nasabahId) {
        const select = document.getElementById('nasabah_id');
        const option = select.options[select.selectedIndex];
        const infoBox = document.getElementById('infoNasabah');

        if (nasabahId && option.dataset.saldo !== undefined) {
            const saldo = parseFloat(option.dataset.saldo) || 0;
            document.getElementById('saldoNasabah').textContent = 'Rp ' + saldo.toLocaleString('id-ID');
            infoBox.classList.remove('d-none');
            infoBox.classList.add('d-block');
        } else {
            infoBox.classList.remove('d-block');
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

        document.querySelectorAll('.berat-kg').forEach(input => {
            totalBerat += parseFloat(input.value) || 0;
        });

        document.querySelectorAll('[id^="nilai-input-"]').forEach(input => {
            totalNilai += parseFloat(input.value) || 0;
        });

        document.getElementById('totalNilai').textContent = 'Rp ' + totalNilai.toLocaleString('id-ID');
        document.getElementById('totalBerat').textContent = totalBerat.toFixed(1) + ' kg';
    }

    // Tambah baris baru
    function tambahBaris() {
        const idx = rowCount;
        const tbody = document.getElementById('bodySampah');

        let options = '<option value="">-- Pilih Jenis Sampah --</option>';
        Object.values(jenisSampahData).forEach(j => {
            const ket = j.kategori ? ` (${j.kategori})` : '';
            options += `<option value="${j.id}" data-harga="${j.harga_per_kg}">${j.nama}${ket}</option>`;
        });

        const rowHtml = `
        <tr id="row-${idx}">
            <td>
                <select name="items[${idx}][jenis_sampah_id]" class="form-select form-select-modern jenis-sampah" onchange="hitungNilai(${idx})" required>
                    ${options}
                </select>
            </td>
            <td>
                <input type="number" name="items[${idx}][berat_kg]" class="form-control form-control-modern berat-kg text-center fw-bold text-primary" placeholder="0.0" step="0.1" min="0.1" oninput="hitungNilai(${idx})" required>
            </td>
            <td>
                <input type="text" id="harga-${idx}" class="form-control-plaintext text-end text-muted" readonly value="Rp 0">
            </td>
            <td>
                <input type="text" id="nilai-${idx}" class="form-control-plaintext text-end fw-bold text-dark fs-4" readonly value="Rp 0">
                <input type="hidden" name="items[${idx}][nilai]" id="nilai-input-${idx}">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-icon btn-outline-danger border-0" onclick="hapusBaris(${idx})" title="Hapus Baris">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0"/><path d="M10 11l0 6"/><path d="M14 11l0 6"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/></svg>
                </button>
            </td>
        </tr>`;

        tbody.insertAdjacentHTML('beforeend', rowHtml);

        const newRow = document.getElementById('row-' + idx);
        newRow.style.opacity = 0;
        newRow.style.transform = 'translateY(10px)';
        newRow.style.transition = 'all 0.3s ease';
        setTimeout(() => {
            newRow.style.opacity = 1;
            newRow.style.transform = 'translateY(0)';
        }, 10);

        rowCount++;
    }

    // Hapus baris
    function hapusBaris(index) {
        const row = document.getElementById('row-' + index);
        if (row) {
            row.style.opacity = 0;
            row.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                row.remove();
                hitungTotal();
            }, 300);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const nasabahSelect = document.getElementById('nasabah_id');
        if(nasabahSelect.value) {
            loadSaldoNasabah(nasabahSelect.value);
        }

        // Handle form submit dengan AJAX SweetAlert2
        const form = document.getElementById('formTabungan');
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnHTML = submitBtn.innerHTML;

            // Validasi basic sebelum submit
            const items = form.querySelectorAll('[name^="items"]');
            if (items.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Minimal harus ada 1 jenis sampah',
                    confirmButtonColor: '#f43f5e',
                    confirmButtonText: 'OK'
                });
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                // Check if response is OK
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Terjadi kesalahan pada server');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#10b981',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'rounded-pill fw-bold px-4',
                            popup: 'rounded-4'
                        }
                    }).then(() => {
                        window.location.href = "{{ route('admin.tabungan.index') }}";
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                let errorMsg = error.message || 'Terjadi kesalahan saat menyimpan data';

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMsg,
                    confirmButtonColor: '#f43f5e',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'rounded-pill fw-bold px-4',
                        popup: 'rounded-4'
                    }
                });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHTML;
            });
        });
    });
</script>
@endpush
