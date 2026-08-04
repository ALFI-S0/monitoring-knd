@extends('layouts.app')

@section('content')
<div class="app-content py-4">
    <div class="container-fluid">

        {{-- Header Dashboard --}}
        <div class="dashboard-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Dashboard</h2>
                <p class="text-muted mb-0">Monitoring kendaraan operasional perusahaan</p>
            </div>
            <div>
                <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill">
                    <i class="bi bi-calendar3 me-2"></i>
                    {{ now()->format('d F Y') }}
                </span>
            </div>
        </div>

        {{-- Kartu Statistik --}}
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="card-title text-uppercase fw-semibold tracking-wider">Total Kendaraan</span>
                            <h2>{{ $totalKendaraan }}</h2>
                        </div>
                        <div class="icon-box bg-primary-subtle text-primary">
                            <i class="bi bi-car-front-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="stat-card cursor-pointer" data-bs-toggle="modal" data-bs-target="#modalListReady" role="button">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="card-title text-uppercase fw-semibold tracking-wider">Kendaraan Ready</span>
                            <h2>{{ $kendaraanReady }}</h2>
                        </div>
                        <div class="icon-box bg-success-subtle text-success">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="stat-card cursor-pointer" data-bs-toggle="modal" data-bs-target="#modalListPerbaikan" role="button">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="card-title text-uppercase fw-semibold tracking-wider">Dalam Perbaikan</span>
                            <h2>{{ $kendaraanPerbaikan }}</h2>
                        </div>
                        <div class="icon-box bg-warning-subtle text-warning">
                            <i class="bi bi-tools"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Tabel Dashboard dengan Nav Tabs --}}
        <div class="card dashboard-card mt-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <ul class="nav nav-pills custom-tabs" id="statusVehicleTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill fw-semibold me-2" id="ready-tab" data-bs-toggle="tab" data-bs-target="#ready-pane" type="button" role="tab">
                                <i class="bi bi-patch-check-fill text-success me-2"></i>Ready ({{ $kendaraanReady }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-semibold" id="repair-tab" data-bs-toggle="tab" data-bs-target="#repair-pane" type="button" role="tab">
                                <i class="bi bi-tools text-warning me-2"></i>Dalam Perbaikan ({{ $kendaraanPerbaikan }})
                            </button>
                        </li>
                    </ul>

                    <div id="tabActionButtons">
                        <button type="button" class="btn btn-outline-success btn-sm px-3 rounded-pill tab-btn-ready" data-bs-toggle="modal" data-bs-target="#modalListReady">
                            <i class="bi bi-eye-fill me-1"></i>Lihat Semua Ready
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm px-3 rounded-pill tab-btn-repair d-none" data-bs-toggle="modal" data-bs-target="#modalListPerbaikan">
                            <i class="bi bi-eye-fill me-1"></i>Lihat Semua Perbaikan
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body px-4 pb-4 pt-3">
                <div class="tab-content" id="statusVehicleTabsContent">
                    
                    {{-- TAB 1: KENDARAAN READY --}}
                    <div class="tab-pane fade show active" id="ready-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="py-3" style="width: 70px;">No</th>
                                        <th class="py-3">No Polisi</th>
                                        <th class="py-3">Merk</th>
                                        <th class="py-3">Tipe</th>
                                        <th class="py-3">Tahun</th>
                                        <th class="py-3 text-center" style="width: 140px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($listReady->take(5) as $item)
                                    <tr>
                                        <td class="py-3 text-muted">{{ $loop->iteration }}</td>
                                        <td class="py-3">
                                            <span class="font-monospace fw-bold text-dark bg-light px-2 py-1 rounded border">
                                                {{ $item->no_polisi }}
                                            </span>
                                        </td>
                                        <td class="py-3 fw-medium text-secondary">{{ $item->merk }}</td>
                                        <td class="py-3 text-secondary">{{ $item->tipe }}</td>
                                        <td class="py-3 text-secondary">{{ $item->tahun }}</td>
                                        <td class="py-3 text-center">
                                            <span class="badge-status bg-success-subtle text-success">
                                                <i class="bi bi-check2-circle me-1"></i>Ready
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            Tidak ada kendaraan ready yang tersedia saat ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- TAB 2: KENDARAAN PERBAIKAN (SUDAH ADA TOMBOL DETAIL) --}}
                    <div class="tab-pane fade" id="repair-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="py-3" style="width: 60px;">No</th>
                                        <th class="py-3">No Polisi</th>
                                        <th class="py-3">Merk</th>
                                        <th class="py-3">Tipe</th>
                                        <th class="py-3">Tahun</th>
                                        <th class="py-3 text-center" style="width: 130px;">Status</th>
                                        <th class="py-3 text-center" style="width: 80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($listPerbaikan->take(5) as $item)
                                    @php
                                        // Jaga-jaga jika $item adalah Model Perbaikan atau Model Kendaraan
                                        $kendaraan = $item->kendaraan ?? $item;
                                    @endphp
                                    <tr>
                                        <td class="py-3 text-muted">{{ $loop->iteration }}</td>
                                        <td class="py-3">
                                            <span class="font-monospace fw-bold text-dark bg-light px-2 py-1 rounded border">
                                                {{ $kendaraan->no_polisi }}
                                            </span>
                                        </td>
                                        <td class="py-3 fw-medium text-secondary">{{ $kendaraan->merk }}</td>
                                        <td class="py-3 text-secondary">{{ $kendaraan->tipe }}</td>
                                        <td class="py-3 text-secondary">{{ $kendaraan->tahun }}</td>
                                        <td class="py-3 text-center">
                                            <span class="badge-status bg-warning-subtle text-warning">
                                                <i class="bi bi-tools me-1"></i>Perbaikan
                                            </span>
                                        </td>
                                        <td class="py-3 text-center">
                                            {{-- TOMBOL MATA UNTUK DETAIL --}}
                                            <button type="button" 
                                                    class="btn btn-action btn-outline-info btn-detail-perbaikan" 
                                                    title="Lihat Detail Perbaikan"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDetailPerbaikan"
                                                    data-nopolisi="{{ $kendaraan->no_polisi }}"
                                                    data-kendaraan="{{ $kendaraan->merk }} {{ $kendaraan->tipe }}"
                                                    data-tanggalperbaikan="{{ isset($item->tanggal_perbaikan) ? \Carbon\Carbon::parse($item->tanggal_perbaikan)->format('d M Y') : '-' }}"
                                                    data-estimasiselesai="{{ !empty($item->estimasi_selesai) ? \Carbon\Carbon::parse($item->estimasi_selesai)->format('d M Y H:i') : '-' }}"
                                                    data-tanggalselesai="{{ !empty($item->tanggal_selesai) ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y H:i') : '-' }}"
                                                    data-kendala="{{ $item->kendala ?? '-' }}"
                                                    data-tindakan="{{ $item->tindakan ?? '-' }}"
                                                    data-catatan="{{ $item->catatan ?? '-' }}"
                                                    data-status="{{ $item->status ?? 'Proses' }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            Tidak ada kendaraan yang sedang dalam perbaikan.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODAL 1: LIHAT SEMUA READY --}}
<div class="modal fade" id="modalListReady" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white px-4 py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-car-front-fill me-2"></i>Daftar Seluruh Kendaraan Ready
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row align-items-center mb-3 g-2">
                    <div class="col-md-6 text-muted small">
                        Total kendaraan siap pakai: <strong class="text-success">{{ count($listReady) }}</strong> armada
                    </div>
                    <div class="col-md-6 ms-auto">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchReadyModal" class="form-control border-start-0" placeholder="Cari nopol, merk, atau tipe...">
                        </div>
                    </div>
                </div>

                <div class="modal-table-scroll border rounded-3">
                    <table class="table table-hover align-middle mb-0" id="tableModalReady">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="py-3 text-center" style="width: 60px;">No</th>
                                <th class="py-3">No Polisi</th>
                                <th class="py-3">Merk</th>
                                <th class="py-3">Tipe</th>
                                <th class="py-3">Tahun</th>
                                <th class="py-3 text-center" style="width: 130px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listReady as $item)
                            <tr>
                                <td class="py-3 text-center text-muted fs-7">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    <span class="font-monospace fw-bold text-dark bg-light px-2 py-1 rounded border">
                                        {{ $item->no_polisi }}
                                    </span>
                                </td>
                                <td class="py-3 fw-semibold">{{ $item->merk }}</td>
                                <td class="py-3 text-secondary">{{ $item->tipe }}</td>
                                <td class="py-3 text-secondary">{{ $item->tahun }}</td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i>Ready
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Tidak ada data kendaraan ready.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 2: LIHAT SEMUA PERBAIKAN --}}
<div class="modal fade" id="modalListPerbaikan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-warning text-dark px-4 py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-tools me-2"></i>Daftar Seluruh Kendaraan Dalam Perbaikan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row align-items-center mb-3 g-2">
                    <div class="col-md-6 text-muted small">
                        Total kendaraan perbaikan: <strong class="text-warning-emphasis">{{ count($listPerbaikan) }}</strong> armada
                    </div>
                    <div class="col-md-6 ms-auto">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchRepairModal" class="form-control border-start-0" placeholder="Cari nopol, merk, atau tipe...">
                        </div>
                    </div>
                </div>

                <div class="modal-table-scroll border rounded-3">
                    <table class="table table-hover align-middle mb-0" id="tableModalRepair">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="py-3 text-center" style="width: 50px;">No</th>
                                <th class="py-3">No Polisi</th>
                                <th class="py-3">Merk</th>
                                <th class="py-3">Tipe</th>
                                <th class="py-3">Tahun</th>
                                <th class="py-3 text-center" style="width: 120px;">Status</th>
                                <th class="py-3 text-center" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listPerbaikan as $item)
                            @php
                                $kendaraan = $item->kendaraan ?? $item;
                            @endphp
                            <tr>
                                <td class="py-3 text-center text-muted fs-7">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    <span class="font-monospace fw-bold text-dark bg-light px-2 py-1 rounded border">
                                        {{ $kendaraan->no_polisi }}
                                    </span>
                                </td>
                                <td class="py-3 fw-semibold">{{ $kendaraan->merk }}</td>
                                <td class="py-3 text-secondary">{{ $kendaraan->tipe }}</td>
                                <td class="py-3 text-secondary">{{ $kendaraan->tahun }}</td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-warning-subtle text-warning-emphasis fw-bold px-3 py-2 rounded-pill">
                                        <i class="bi bi-tools me-1"></i>Perbaikan
                                    </span>
                                </td>
                                <td class="py-3 text-center">
                                    <button type="button" 
                                            class="btn btn-action btn-outline-info btn-detail-perbaikan" 
                                            title="Lihat Detail Perbaikan"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalDetailPerbaikan"
                                            data-nopolisi="{{ $kendaraan->no_polisi }}"
                                            data-kendaraan="{{ $kendaraan->merk }} {{ $kendaraan->tipe }}"
                                            data-tanggalperbaikan="{{ isset($item->tanggal_perbaikan) ? \Carbon\Carbon::parse($item->tanggal_perbaikan)->format('d M Y') : '-' }}"
                                            data-estimasiselesai="{{ !empty($item->estimasi_selesai) ? \Carbon\Carbon::parse($item->estimasi_selesai)->format('d M Y H:i') : '-' }}"
                                            data-tanggalselesai="{{ !empty($item->tanggal_selesai) ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y H:i') : '-' }}"
                                            data-kendala="{{ $item->kendala ?? '-' }}"
                                            data-tindakan="{{ $item->tindakan ?? '-' }}"
                                            data-catatan="{{ $item->catatan ?? '-' }}"
                                            data-status="{{ $item->status ?? 'Proses' }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Tidak ada data kendaraan perbaikan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 3: POPUP DETAIL INFORMASI PERBAIKAN --}}
<div class="modal fade" id="modalDetailPerbaikan" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-info-circle text-info me-2"></i>Detail Riwayat Perbaikan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                
                {{-- Banner Informasi Utama Kendaraan --}}
                <div class="p-3 bg-light rounded-3 mb-4 border border-secondary-subtle">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <span class="d-block small text-muted text-uppercase fw-bold">Unit Armada</span>
                            <span id="detail_kendaraan" class="fw-bold fs-5 text-dark">-</span>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="d-block small text-muted text-uppercase fw-bold mb-1">Plat Nomor & Status</span>
                            <span id="detail_nopol" class="font-monospace fw-bold text-dark bg-white px-2 py-1 border rounded me-2">-</span>
                            <span id="detail_status_badge"></span>
                        </div>
                    </div>
                </div>

                {{-- Grid Informasi Waktu --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 bg-white">
                            <span class="d-block small text-muted text-uppercase fw-bold"><i class="bi bi-calendar-event me-1"></i>Tanggal Masuk</span>
                            <span id="detail_tanggal_perbaikan" class="fw-bold text-dark d-block mt-1">-</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 bg-white">
                            <span class="d-block small text-muted text-uppercase fw-bold"><i class="bi bi-clock me-1"></i>Estimasi Selesai</span>
                            <span id="detail_estimasi_selesai" class="fw-bold text-dark d-block mt-1">-</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 bg-white">
                            <span class="d-block small text-muted text-uppercase fw-bold"><i class="bi bi-calendar-check me-1"></i>Selesai Aktual</span>
                            <span id="detail_tanggal_selesai" class="fw-bold text-dark d-block mt-1">-</span>
                        </div>
                    </div>
                </div>

                {{-- Detail Kerusakan, Tindakan, Catatan --}}
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold text-uppercase mb-1">Kendala / Kerusakan</label>
                    <div id="detail_kendala" class="p-3 bg-light rounded-3 text-dark fw-medium border">-</div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold text-uppercase mb-1">Tindakan Pemeliharaan</label>
                    <div id="detail_tindakan" class="p-3 bg-light rounded-3 text-dark fw-medium border">-</div>
                </div>

                <div class="mb-2">
                    <label class="form-label text-secondary small fw-bold text-uppercase mb-1">Catatan Tambahan</label>
                    <div id="detail_catatan" class="p-3 bg-light rounded-3 text-dark fw-medium border">-</div>
                </div>

            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Switcher Tombol
    const readyTab = document.getElementById('ready-tab');
    const repairTab = document.getElementById('repair-tab');
    const btnReady = document.querySelector('.tab-btn-ready');
    const btnRepair = document.querySelector('.tab-btn-repair');

    if (readyTab && repairTab) {
        readyTab.addEventListener('shown.bs.tab', function () {
            btnReady.classList.remove('d-none');
            btnRepair.classList.add('d-none');
        });

        repairTab.addEventListener('shown.bs.tab', function () {
            btnRepair.classList.remove('d-none');
            btnReady.classList.add('d-none');
        });
    }

    // 2. Isikan Data ke Modal Detail
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-detail-perbaikan');
        if (btn) {
            document.getElementById('detail_kendaraan').textContent = btn.getAttribute('data-kendaraan') || '-';
            document.getElementById('detail_nopol').textContent = btn.getAttribute('data-nopolisi') || '-';
            document.getElementById('detail_tanggal_perbaikan').textContent = btn.getAttribute('data-tanggalperbaikan') || '-';
            document.getElementById('detail_estimasi_selesai').textContent = btn.getAttribute('data-estimasiselesai') || '-';
            document.getElementById('detail_tanggal_selesai').textContent = btn.getAttribute('data-tanggalselesai') || '-';
            document.getElementById('detail_kendala').textContent = btn.getAttribute('data-kendala') || '-';
            document.getElementById('detail_tindakan').textContent = btn.getAttribute('data-tindakan') || '-';
            document.getElementById('detail_catatan').textContent = btn.getAttribute('data-catatan') || '-';

            const status = btn.getAttribute('data-status') || 'Proses';
            const badgeWrapper = document.getElementById('detail_status_badge');
            if (status === 'Proses' || status === 'Perbaikan') {
                badgeWrapper.innerHTML = '<span class="badge-status bg-warning-subtle text-warning"><i class="bi bi-hourglass-split me-1"></i>Proses</span>';
            } else {
                badgeWrapper.innerHTML = '<span class="badge-status bg-success-subtle text-success"><i class="bi bi-check2-circle me-1"></i>Selesai</span>';
            }
        }
    });

    // 3. Search Filter
    function setupTableSearch(inputId, tableId) {
        const searchInput = document.getElementById(inputId);
        const tableBody = document.querySelector(`#${tableId} tbody`);

        if (searchInput && tableBody) {
            searchInput.addEventListener('keyup', function () {
                const filter = this.value.toLowerCase();
                const rows = tableBody.getElementsByTagName('tr');

                Array.from(rows).forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        }
    }

    setupTableSearch('searchReadyModal', 'tableModalReady');
    setupTableSearch('searchRepairModal', 'tableModalRepair');
});
</script>

<style>
.cursor-pointer { cursor: pointer; }
.stat-card {
    background: #fff;
    border: 1px solid #f1f3f5;
    border-radius: 16px;
    padding: 24px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}
.stat-card:hover { transform: translateY(-4px); }
.stat-card h2 { font-size: 36px; font-weight: 700; color: #1f2937; margin: 8px 0 4px 0; }
.card-title { font-size: 13px; color: #9ca3af; letter-spacing: 0.05em; }
.icon-box { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; }

.custom-tabs .nav-link { color: #64748b; background-color: #f8fafc; border: 1px solid #edf2f7; padding: 8px 20px; }
.custom-tabs .nav-link.active#ready-tab { background-color: #e8f5e9; color: #2e7d32; border-color: #a5d6a7; }
.custom-tabs .nav-link.active#repair-tab { background-color: #fff8e1; color: #b45309; border-color: #fde68a; }

.dashboard-card { border-radius: 16px; }
.table thead th { border-bottom: 1px solid #edf2f7; color: #64748b; font-weight: 600; font-size: 14px; }

.badge-status { display: inline-block; padding: 6px 14px; border-radius: 30px; font-size: 13px; font-weight: 600; }
.btn-action { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px !important; font-size: 14px; }

.modal-table-scroll { max-height: 450px; overflow-y: auto; }
.modal-table-scroll thead.sticky-top { position: sticky; top: 0; z-index: 2; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
</style>
@endsection