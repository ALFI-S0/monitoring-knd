@extends('layouts.app')

@section('content')
<div class="app-content-header py-4">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1 text-dark">Data Perbaikan Kendaraan</h2>
                <p class="text-muted small mb-0">Daftar seluruh kendaraan yang sedang maupun pernah diperbaiki</p>
            </div>
            
            {{-- Tombol Tambah Perbaikan HANYA untuk Departemen ID 1 dan 3 --}}
            @if(auth()->check() && in_array(auth()->user()->departemen_id, [1, 3]))
                <a href="{{ route('perbaikan.create') }}" class="btn btn-primary px-3 rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Perbaikan
                </a>
            @endif
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card dashboard-card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="bi bi-wrench-adjustable-circle text-primary me-2"></i>List Perbaikan
                </h5>
            </div>

            <div class="card-body px-4 pb-4">
                <div class="table-responsive">
                    <table id="tablePerbaikan" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="py-3" style="width: 60px;">No</th>
                                <th class="py-3">No Polisi</th>
                                <th class="py-3">Kendaraan</th>
                                <th class="py-3">Tanggal Masuk</th>
                                <th class="py-3">Estimasi</th>
                                <th class="py-3 text-center" style="width: 130px;">Status</th>
                                <th class="py-3 text-center" style="width: 140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($perbaikans as $item)
                            <tr>
                                <td class="py-3 text-secondary fw-medium">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    <span class="font-monospace fw-bold text-dark bg-light px-2 py-1 rounded border border-secondary-subtle">
                                        {{ $item->kendaraan->no_polisi }}
                                    </span>
                                </td>
                                <td class="py-3 fw-bold text-dark">
                                    {{ $item->kendaraan->merk }} <span class="fw-medium text-secondary">{{ $item->kendaraan->tipe }}</span>
                                </td>
                                <td class="py-3 text-dark fw-medium">
                                    {{ \Carbon\Carbon::parse($item->tanggal_perbaikan)->format('d M Y') }}
                                </td>
                                <td class="py-3 text-dark fw-medium">
                                    @if($item->estimasi_selesai)
                                        {{ \Carbon\Carbon::parse($item->estimasi_selesai)->format('d M Y H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    @if($item->status == 'Proses')
                                        <span class="badge-status bg-warning-subtle text-warning">
                                            <i class="bi bi-hourglass-split me-1"></i>Proses
                                        </span>
                                    @else
                                        <span class="badge-status bg-success-subtle text-success">
                                            <i class="bi bi-check2-circle me-1"></i>Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <div class="btn-group gap-1">
                                        {{-- TOMBOL DETAIL (BISA DIAKSES SEMUA USER/DEPARTEMEN) --}}
                                        <button type="button" 
                                                class="btn btn-action btn-outline-info btn-detail" 
                                                title="Detail"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDetailPerbaikan"
                                                data-nopolisi="{{ $item->kendaraan->no_polisi }}"
                                                data-kendaraan="{{ $item->kendaraan->merk }} {{ $item->kendaraan->tipe }}"
                                                data-tanggalperbaikan="{{ \Carbon\Carbon::parse($item->tanggal_perbaikan)->format('d M Y') }}"
                                                data-estimasiselesai="{{ $item->estimasi_selesai ? \Carbon\Carbon::parse($item->estimasi_selesai)->format('d M Y H:i') : '-' }}"
                                                data-tanggalselesai="{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y H:i') : '-' }}"
                                                data-kendala="{{ $item->kendala }}"
                                                data-tindakan="{{ $item->tindakan }}"
                                                data-catatan="{{ $item->catatan }}"
                                                data-status="{{ $item->status }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        {{-- TOMBOL EDIT & HAPUS HANYA UNTUK DEPARTEMEN ID 1 DAN ID 3 --}}
                                        @if(auth()->check() && in_array(auth()->user()->departemen_id, [1, 3]))
                                            {{-- TOMBOL EDIT: Hanya tampil aktif jika status BUKAN 'Selesai' --}}
                                            @if($item->status != 'Selesai')
                                                <button type="button" 
                                                        class="btn btn-action btn-outline-warning btn-edit" 
                                                        title="Ubah"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEditPerbaikan"
                                                        data-id="{{ $item->id }}"
                                                        data-nopolisi="{{ $item->kendaraan->no_polisi }}"
                                                        data-kendaraan="{{ $item->kendaraan->merk }} {{ $item->kendaraan->tipe }}"
                                                        data-tanggalperbaikan="{{ $item->tanggal_perbaikan }}"
                                                        data-estimasiselesai="{{ $item->estimasi_selesai ? \Carbon\Carbon::parse($item->estimasi_selesai)->format('Y-m-d\TH:i') : '' }}"
                                                        data-tanggalselesai="{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('Y-m-d\TH:i') : '' }}"
                                                        data-kendala="{{ $item->kendala }}"
                                                        data-tindakan="{{ $item->tindakan }}"
                                                        data-catatan="{{ $item->catatan }}"
                                                        data-status="{{ $item->status }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            @else
                                                {{-- Jika Selesai, tampilkan tombol ter-disable dengan tooltip --}}
                                                <button type="button" 
                                                        class="btn btn-action btn-outline-secondary" 
                                                        title="Perbaikan sudah selesai, tidak dapat diubah" 
                                                        disabled>
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            @endif

                                            {{-- TOMBOL HAPUS --}}
                                            <form action="{{ route('perbaikan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data perbaikan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-action btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODAL DETAIL PERBAIKAN KENDARAAN --}}
<div class="modal fade" id="modalDetailPerbaikan" tabindex="-1" aria-labelledby="modalDetailPerbaikanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalDetailPerbaikanLabel">
                    <i class="bi bi-info-circle text-info me-2"></i>Detail Riwayat Perbaikan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

{{-- MODAL EDIT PERBAIKAN KENDARAAN (HANYA DIGUNAKAN DEPARTEMEN ID 1 & 3) --}}
@if(auth()->check() && in_array(auth()->user()->departemen_id, [1, 3]))
<div class="modal fade" id="modalEditPerbaikan" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalEditPerbaikanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalEditPerbaikanLabel">
                    <i class="bi bi-pencil-square text-warning me-2"></i>Update Data Perbaikan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditPerbaikan" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body px-4">
                    
                    {{-- Informasi Singkat Kendaraan --}}
                    <div class="p-3 bg-light rounded-3 mb-3 border border-secondary-subtle">
                        <div class="row">
                            <div class="col-6">
                                <span class="d-block small text-muted text-uppercase fw-bold">Unit Armada</span>
                                <span id="info_kendaraan" class="fw-bold text-dark">-</span>
                            </div>
                            <div class="col-6 text-end">
                                <span class="d-block small text-muted text-uppercase fw-bold">Plat Nomor</span>
                                <span id="info_nopol" class="font-monospace fw-bold text-dark bg-white px-2 py-0.5 border rounded">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="edit_tanggal_perbaikan" class="form-label text-secondary small fw-bold text-uppercase">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="edit_tanggal_perbaikan" name="tanggal_perbaikan" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_estimasi_selesai" class="form-label text-secondary small fw-bold text-uppercase">Estimasi Selesai</label>
                            <input type="datetime-local" class="form-control" id="edit_estimasi_selesai" name="estimasi_selesai">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_status" class="form-label text-secondary small fw-bold text-uppercase">Status Perbaikan</label>
                            <select class="form-select fw-bold" id="edit_status" name="status" required>
                                <option value="Proses">Proses</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                    </div>

                    {{-- Section Tanggal Selesai Aktual --}}
                    <div class="mb-3 p-3 bg-success-subtle rounded-3 border border-success-subtle" id="wrapper_tanggal_selesai">
                        <label for="edit_tanggal_selesai" class="form-label text-success small fw-bold text-uppercase mb-1">
                            <i class="bi bi-calendar-check-fill me-1"></i> Tanggal & Jam Selesai Aktual
                        </label>
                        <input type="datetime-local" class="form-control border-success" id="edit_tanggal_selesai" name="tanggal_selesai">
                        <small class="text-success d-block mt-1">Mengubah status menjadi 'Selesai' otomatis mengembalikan armada ke status 'Ready'.</small>
                    </div>

                    <div class="mb-3">
                        <label for="edit_kendala" class="form-label text-secondary small fw-bold text-uppercase">Kendala / Kerusakan</label>
                        <textarea class="form-control" id="edit_kendala" name="kendala" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_tindakan" class="form-label text-secondary small fw-bold text-uppercase">Tindakan Pemeliharaan</label>
                        <textarea class="form-control" id="edit_tindakan" name="tindakan" rows="2" placeholder="Tulis tindakan perbaikan yang dilakukan..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_catatan" class="form-label text-secondary small fw-bold text-uppercase">Catatan Tambahan</label>
                        <textarea class="form-control" id="edit_catatan" name="catatan" rows="2"></textarea>
                    </div>

                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update Perbaikan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
.dashboard-card { border-radius: 16px; background-color: #ffffff; }
.table thead th { border-bottom: 2px solid #f1f3f5; color: #495057; font-weight: 600; font-size: 14px; background: transparent !important; }
.table tbody tr { transition: background-color 0.2s ease; border-bottom: 1px solid #efefef; }
.table tbody tr:hover { background-color: #f8fafc !important; }
.badge-status { display: inline-block; padding: 6px 14px; border-radius: 30px; font-size: 13px; font-weight: 600; }
.btn-action { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px !important; font-size: 14px; }
.modal-content { border-radius: 16px; }
.form-control, .form-select { border-radius: 8px; padding: 0.6rem 0.75rem; border-color: #dee2e6; }
.form-control:focus, .form-select:focus { box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15); border-color: #0d6efd; }
textarea { resize: none; }
</style>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inisialisasi DataTable
    if ($.fn.DataTable) {
        $('#tablePerbaikan').DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            language: {
                search: "Cari :",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                paginate: {
                    previous: "<i class='bi bi-chevron-left'></i>",
                    next: "<i class='bi bi-chevron-right'></i>"
                }
            }
        });
    }

    // Event Klik Tombol Detail
    $('#tablePerbaikan tbody').on('click', '.btn-detail', function() {
        const nopol = $(this).attr('data-nopolisi');
        const kendaraan = $(this).attr('data-kendaraan');
        const tglPerbaikan = $(this).attr('data-tanggalperbaikan');
        const estSelesai = $(this).attr('data-estimasiselesai');
        const tglSelesai = $(this).attr('data-tanggalselesai');
        const kendala = $(this).attr('data-kendala');
        const tindakan = $(this).attr('data-tindakan');
        const catatan = $(this).attr('data-catatan');
        const status = $(this).attr('data-status');

        $('#detail_kendaraan').text(kendaraan || '-');
        $('#detail_nopol').text(nopol || '-');
        $('#detail_tanggal_perbaikan').text(tglPerbaikan || '-');
        $('#detail_estimasi_selesai').text(estSelesai || '-');
        $('#detail_tanggal_selesai').text(tglSelesai || '-');
        $('#detail_kendala').text(kendala || '-');
        $('#detail_tindakan').text(tindakan || '-');
        $('#detail_catatan').text(catatan || '-');

        // Render badge status di modal detail
        if (status === 'Proses') {
            $('#detail_status_badge').html('<span class="badge-status bg-warning-subtle text-warning"><i class="bi bi-hourglass-split me-1"></i>Proses</span>');
        } else {
            $('#detail_status_badge').html('<span class="badge-status bg-success-subtle text-success"><i class="bi bi-check2-circle me-1"></i>Selesai</span>');
        }
    });

    // Event Klik Tombol Edit
    $('#tablePerbaikan tbody').on('click', '.btn-edit', function() {
        const id = $(this).attr('data-id');
        const nopol = $(this).attr('data-nopolisi');
        const kendaraan = $(this).attr('data-kendaraan');
        const tglPerbaikan = $(this).attr('data-tanggalperbaikan');
        const estSelesai = $(this).attr('data-estimasiselesai');
        const tglSelesai = $(this).attr('data-tanggalselesai');
        const kendala = $(this).attr('data-kendala');
        const tindakan = $(this).attr('data-tindakan');
        const catatan = $(this).attr('data-catatan');
        const status = $(this).attr('data-status');

        $('#formEditPerbaikan').attr('action', `/perbaikan/${id}`);

        $('#info_kendaraan').text(kendaraan || '-');
        $('#info_nopol').text(nopol || '-');

        $('#edit_tanggal_perbaikan').val(tglPerbaikan);
        $('#edit_estimasi_selesai').val(estSelesai);
        $('#edit_tanggal_selesai').val(tglSelesai);
        $('#edit_kendala').val(kendala);
        $('#edit_tindakan').val(tindakan);
        $('#edit_catatan').val(catatan);
        
        $('#edit_status').val(status).trigger('change');
    });

    // Interaktivitas: Sembunyikan/Tampilkan Tanggal Selesai di Modal Edit
    $('#edit_status').on('change', function() {
        if ($(this).val() === 'Selesai') {
            $('#wrapper_tanggal_selesai').slideDown(200);
            $('#edit_tanggal_selesai').attr('required', true);
            
            if(!$('#edit_tanggal_selesai').val()) {
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                $('#edit_tanggal_selesai').val(now.toISOString().slice(0, 16));
            }
        } else {
            $('#wrapper_tanggal_selesai').slideUp(200);
            $('#edit_tanggal_selesai').removeAttr('required').val('');
        }
    });
});
</script>
@endpush