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
            {{-- Total Kendaraan --}}
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

            {{-- Kendaraan Ready --}}
            <div class="col-lg-4">
                <div class="stat-card">
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

            {{-- Perbaikan --}}
            <div class="col-lg-4">
                <div class="stat-card">
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

        {{-- Tabel Preview Kendaraan Ready --}}
        <div class="card dashboard-card mt-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">
                            <i class="bi bi-patch-check-fill text-success me-2"></i>Kendaraan Ready
                        </h5>
                    </div>
                    {{-- Tombol Trigger Modal --}}
                    <button type="button" class="btn btn-outline-success btn-sm px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalListReady">
                        <i class="bi bi-eye-fill me-1"></i>Lihat Semua
                    </button>
                </div>
            </div>

            <div class="card-body px-4 pb-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="py-3 pl-4" style="width: 80px;">No</th>
                                <th class="py-3">No Polisi</th>
                                <th class="py-3">Merk</th>
                                <th class="py-3">Tipe</th>
                                <th class="py-3">Tahun</th>
                                <th class="py-3 text-center" style="width: 150px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listReady as $item)
                            <tr>
                                <td class="py-3 text-muted">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    <span class="font-monospace fw-bold text-dark bg-light px-2 py-1 rounded">
                                        {{ $item->no_polisi }}
                                    </span>
                                </td>
                                <td class="py-3 fw-medium text-secondary">{{ $item->merk }}</td>
                                <td class="py-3 text-secondary">{{ $item->tipe }}</td>
                                <td class="py-3 text-secondary">{{ $item->tahun }}</td>
                                <td class="py-3 text-center">
                                    <span class="badge-ready d-inline-block">
                                        <i class="bi bi-check2-circle me-1"></i>Ready
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted py-3">
                                        <i class="bi bi-inbox fs-1 d-block mb-3 text-black-50"></i>
                                        <span>Tidak ada kendaraan ready yang tersedia saat ini.</span>
                                    </div>
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

{{-- MODAL SEMUA KENDARAAN READY --}}
<div class="modal fade" id="modalListReady" tabindex="-1" aria-labelledby="modalListReadyLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white px-4 py-3">
                <h5 class="modal-title fw-bold" id="modalListReadyLabel">
                    <i class="bi bi-car-front-fill me-2"></i>Daftar Seluruh Kendaraan Ready
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                {{-- Input Cari Cepat di Modal --}}
                <div class="row mb-3">
                    <div class="col-md-6 ms-auto">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchReadyModal" class="form-control" placeholder="Cari nopol, merk, atau tipe...">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle border rounded-3" id="tableModalReady">
                        <thead class="table-light">
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
                                <td class="py-3 text-center text-muted">{{ $loop->iteration }}</td>
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
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Tidak ada data kendaraan ready.
                                </td>
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

{{-- SCRIPT SEARCH FILTER DALAM MODAL --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchReadyModal');
    const tableBody = document.querySelector('#tableModalReady tbody');

    if (searchInput && tableBody) {
        searchInput.addEventListener('keyup', function () {
            const filter = this.value.toLowerCase();
            const rows = tableBody.getElementsByTagName('tr');

            Array.from(rows).forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
</script>

<style>
.stat-card {
    background: #fff;
    border: 1px solid #f1f3f5;
    border-radius: 16px;
    padding: 24px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
}

.stat-card h2 {
    font-size: 36px;
    font-weight: 700;
    color: #1f2937;
    margin: 8px 0 4px 0;
}

.card-title {
    font-size: 13px;
    color: #9ca3af;
    letter-spacing: 0.05em;
}

.icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content:center;
    font-size: 22px;
}

.dashboard-card {
    border-radius: 16px;
}

.table thead {
    background: #f8fafc;
}

.table thead th {
    border-bottom: 1px solid #edf2f7;
    color: #64748b;
    font-weight: 600;
    font-size: 14px;
}

.table tbody tr {
    transition: background-color 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8fafc;
}

.badge-ready {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
}

.tracking-wider {
    letter-spacing: 0.05em;
}
</style>
@endsection