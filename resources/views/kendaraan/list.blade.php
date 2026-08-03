@extends('layouts.app')

@section('content')
<div class="app-content-header py-4">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1 text-dark">Data Kendaraan</h2>
                <p class="text-muted small mb-0">Daftar manajemen seluruh armada kendaraan perusahaan</p>
            </div>
            <a href="{{ route('kendaraan.create') }}" class="btn btn-primary px-3 rounded-pill">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kendaraan
            </a>
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
                    <i class="bi bi-car-front-fill text-primary me-2"></i>List Kendaraan
                </h5>
            </div>

            <div class="card-body px-4 pb-4">
                <div class="table-responsive">
                    <table id="tableKendaraan" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="py-3" style="width: 60px;">No</th>
                                <th class="py-3" style="width: 100px;">Foto</th>
                                <th class="py-3">No Polisi</th>
                                <th class="py-3">Merk</th>
                                <th class="py-3">Tipe</th>
                                <th class="py-3">Tahun</th>
                                <th class="py-3 text-center" style="width: 130px;">Status</th>
                                <th class="py-3 text-end">KM</th>
                                <th class="py-3 text-center" style="width: 140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kendaraan as $item)
                            <tr>
                                <td class="py-3 text-secondary fw-medium">{{ $loop->iteration }}</td>
                                <td class="py-3">
                                    @if($item->foto)
                                        <img src="{{ asset('storage/'.$item->foto) }}" class="img-vehicle rounded">
                                    @else
                                        <div class="no-img-placeholder rounded d-flex align-items-center justify-content-center">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="font-monospace fw-bold text-dark bg-light px-2 py-1 rounded border border-secondary-subtle">
                                        {{ $item->no_polisi }}
                                    </span>
                                </td>
                                <td class="py-3 fw-bold text-dark">{{ $item->merk }}</td>
                                <td class="py-3 text-dark fw-medium">{{ $item->tipe }}</td>
                                <td class="py-3 text-dark fw-medium">{{ $item->tahun }}</td>
                                <td class="py-3 text-center">
                                    @switch($item->status)
                                        @case('Ready')
                                            <span class="badge-status bg-success-subtle text-success">
                                                <i class="bi bi-check2-circle me-1"></i>Ready
                                            </span>
                                            @break
                                        @case('Dipakai')
                                            <span class="badge-status bg-primary-subtle text-primary">
                                                <i class="bi bi-person-badge me-1"></i>Dipakai
                                            </span>
                                            @break
                                        @case('Perbaikan')
                                            <span class="badge-status bg-danger-subtle text-danger">
                                                <i class="bi bi-tools me-1"></i>Perbaikan
                                            </span>
                                            @break
                                        @default
                                            <span class="badge-status bg-warning-subtle text-warning">
                                                <i class="bi bi-hourglass-split me-1"></i>Servis
                                            </span>
                                    @endswitch
                                </td>
                                <td class="py-3 text-end fw-bold text-dark">
                                    {{ number_format($item->kilometer, 0, ',', '.') }} KM
                                </td>
                                <td class="py-3 text-center">
                                    <div class="btn-group gap-1">
                                        {{-- TOMBOL DETAIL MODAL --}}
                                        <button type="button" 
                                                class="btn btn-action btn-outline-info btn-detail" 
                                                title="Detail"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDetailKendaraan"
                                                data-nopolisi="{{ $item->no_polisi }}"
                                                data-merk="{{ $item->merk }}"
                                                data-tipe="{{ $item->tipe }}"
                                                data-tahun="{{ $item->tahun }}"
                                                data-status="{{ $item->status }}"
                                                data-kilometer="{{ number_format($item->kilometer, 0, ',', '.') }}"
                                                data-foto="{{ $item->foto ? asset('storage/'.$item->foto) : '' }}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        
                                        {{-- BUTTON EDIT MODAL --}}
                                        <button type="button" 
                                                class="btn btn-action btn-outline-warning btn-edit" 
                                                title="Ubah"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditKendaraan"
                                                data-id="{{ $item->id }}"
                                                data-nopolisi="{{ $item->no_polisi }}"
                                                data-merk="{{ $item->merk }}"
                                                data-tipe="{{ $item->tipe }}"
                                                data-tahun="{{ $item->tahun }}"
                                                data-status="{{ $item->status }}"
                                                data-kilometer="{{ $item->kilometer }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        {{-- FORM HAPUS BERFUNGSI --}}
                                        <form action="{{ route('kendaraan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kendaraan {{ $item->no_polisi }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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

{{-- MODAL DETAIL KENDARAAN --}}
<div class="modal fade" id="modalDetailKendaraan" tabindex="-1" aria-labelledby="modalDetailKendaraanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalDetailKendaraanLabel">
                    <i class="bi bi-info-circle text-info me-2"></i>Detail Informasi Kendaraan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div class="row align-items-center g-4">
                    
                    {{-- Preview Foto --}}
                    <div class="col-md-5 text-center">
                        <div id="detail_foto_wrapper" class="p-2 border rounded-3 bg-light shadow-sm">
                            <img id="detail_foto_img" src="" class="img-fluid rounded" style="max-height: 220px; width: 100%; object-fit: cover; display: none;">
                            <div id="detail_no_foto" class="py-5 text-muted d-flex flex-column align-items-center justify-content-center">
                                <i class="bi bi-image fs-1 mb-2"></i>
                                <span class="small fw-semibold">Tidak ada foto kendaraan</span>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Spesifikasi Armada --}}
                    <div class="col-md-7">
                        <div class="p-3 bg-light rounded-3 mb-3 border border-secondary-subtle">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="d-block small text-muted text-uppercase fw-bold">Nomor Polisi</span>
                                    <span id="detail_nopol" class="font-monospace fw-bold fs-4 text-dark bg-white px-2 py-0.5 border rounded d-inline-block mt-1">-</span>
                                </div>
                                <div id="detail_status_badge"></div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-2 border rounded-3 bg-white">
                                    <span class="d-block small text-muted text-uppercase fw-bold"><i class="bi bi-tag me-1"></i>Merk</span>
                                    <span id="detail_merk" class="fw-bold text-dark fs-6 d-block mt-1">-</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 border rounded-3 bg-white">
                                    <span class="d-block small text-muted text-uppercase fw-bold"><i class="bi bi-car-front me-1"></i>Tipe</span>
                                    <span id="detail_tipe" class="fw-bold text-dark fs-6 d-block mt-1">-</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 border rounded-3 bg-white">
                                    <span class="d-block small text-muted text-uppercase fw-bold"><i class="bi bi-calendar-event me-1"></i>Tahun</span>
                                    <span id="detail_tahun" class="fw-bold text-dark fs-6 d-block mt-1">-</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 border rounded-3 bg-white">
                                    <span class="d-block small text-muted text-uppercase fw-bold"><i class="bi bi-speedometer2 me-1"></i>Kilometer</span>
                                    <span id="detail_kilometer" class="fw-bold text-dark fs-6 d-block mt-1">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT KENDARAAN --}}
<div class="modal fade" id="modalEditKendaraan" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalEditKendaraanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalEditKendaraanLabel">
                    <i class="bi bi-pencil-square text-warning me-2"></i>Edit Data Kendaraan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditKendaraan" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label for="edit_no_polisi" class="form-label text-secondary small fw-bold text-uppercase">No Polisi</label>
                        <input type="text" class="form-control" id="edit_no_polisi" name="no_polisi" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label for="edit_merk" class="form-label text-secondary small fw-bold text-uppercase">Merk</label>
                            <input type="text" class="form-control" id="edit_merk" name="merk" required>
                        </div>
                        <div class="col-6">
                            <label for="edit_tipe" class="form-label text-secondary small fw-bold text-uppercase">Tipe</label>
                            <input type="text" class="form-control" id="edit_tipe" name="tipe" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label for="edit_tahun" class="form-label text-secondary small fw-bold text-uppercase">Tahun</label>
                            <input type="number" class="form-control" id="edit_tahun" name="tahun" required>
                        </div>
                        <div class="col-6">
                            <label for="edit_kilometer" class="form-label text-secondary small fw-bold text-uppercase">Kilometer (KM)</label>
                            <input type="number" class="form-control" id="edit_kilometer" name="kilometer" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label text-secondary small fw-bold text-uppercase">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="Ready">Ready</option>
                            <option value="Dipakai">Dipakai</option>
                            <option value="Perbaikan">Perbaikan</option>
                            <option value="Servis">Servis</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_foto" class="form-label text-secondary small fw-bold text-uppercase">Ganti Foto <span class="text-muted text-lowercase">(opsional)</span></label>
                        <input type="file" class="form-control" id="edit_foto" name="foto" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.dashboard-card { border-radius: 16px; background-color: #ffffff; }
.table thead th { border-bottom: 2px solid #f1f3f5; color: #495057; font-weight: 600; font-size: 14px; background: transparent !important; }
.table tbody tr { transition: background-color 0.2s ease; border-bottom: 1px solid #efefef; }
.table tbody tr:hover { background-color: #f8fafc !important; }
.badge-status { display: inline-block; padding: 6px 14px; border-radius: 30px; font-size: 13px; font-weight: 600; }
.img-vehicle { width: 65px; height: 45px; object-fit: cover; border: 1px solid #dee2e6; }
.no-img-placeholder { width: 65px; height: 45px; background-color: #f1f3f5; border: 1px solid #dee2e6; }
.btn-action { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px !important; font-size: 14px; }
.modal-content { border-radius: 16px; }
.form-control, .form-select { border-radius: 8px; padding: 0.6rem 0.75rem; border-color: #dee2e6; }
.form-control:focus, .form-select:focus { box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15); }
</style>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Inisialisasi DataTable
    if ($.fn.DataTable) {
        $('#tableKendaraan').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: "Cari :",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Belum ada data",
                paginate: { previous: "<i class='bi bi-chevron-left'></i>", next: "<i class='bi bi-chevron-right'></i>" }
            }
        });
    }

    // Handle data passing ke Modal Detail
    $('#tableKendaraan tbody').on('click', '.btn-detail', function() {
        const nopol = $(this).attr('data-nopolisi');
        const merk = $(this).attr('data-merk');
        const tipe = $(this).attr('data-tipe');
        const tahun = $(this).attr('data-tahun');
        const status = $(this).attr('data-status');
        const km = $(this).attr('data-kilometer');
        const foto = $(this).attr('data-foto');

        $('#detail_nopol').text(nopol || '-');
        $('#detail_merk').text(merk || '-');
        $('#detail_tipe').text(tipe || '-');
        $('#detail_tahun').text(tahun || '-');
        $('#detail_kilometer').text((km || '0') + ' KM');

        // Render foto jika tersedia
        if (foto) {
            $('#detail_foto_img').attr('src', foto).show();
            $('#detail_no_foto').addClass('d-none').removeClass('d-flex');
        } else {
            $('#detail_foto_img').hide();
            $('#detail_no_foto').removeClass('d-none').addClass('d-flex');
        }

        // Render badge status di modal detail
        let badgeHtml = '';
        switch(status) {
            case 'Ready':
                badgeHtml = '<span class="badge-status bg-success-subtle text-success"><i class="bi bi-check2-circle me-1"></i>Ready</span>';
                break;
            case 'Dipakai':
                badgeHtml = '<span class="badge-status bg-primary-subtle text-primary"><i class="bi bi-person-badge me-1"></i>Dipakai</span>';
                break;
            case 'Perbaikan':
                badgeHtml = '<span class="badge-status bg-danger-subtle text-danger"><i class="bi bi-tools me-1"></i>Perbaikan</span>';
                break;
            default:
                badgeHtml = '<span class="badge-status bg-warning-subtle text-warning"><i class="bi bi-hourglass-split me-1"></i>Servis</span>';
        }
        $('#detail_status_badge').html(badgeHtml);
    });

    // Handle data passing ke Modal Edit ketika tombol diklik
    $('#tableKendaraan tbody').on('click', '.btn-edit', function() {
        const id = $(this).attr('data-id');
        const nopol = $(this).attr('data-nopolisi');
        const merk = $(this).attr('data-merk');
        const tipe = $(this).attr('data-tipe');
        const tahun = $(this).attr('data-tahun');
        const status = $(this).attr('data-status');
        const km = $(this).attr('data-kilometer');

        // Set action form URL secara dinamis sesuai id
        $('#formEditKendaraan').attr('action', `/kendaraan/${id}`);

        // Isi field input di dalam modal
        $('#edit_no_polisi').val(nopol);
        $('#edit_merk').val(merk);
        $('#edit_tipe').val(tipe);
        $('#edit_tahun').val(tahun);
        $('#edit_kilometer').val(km);
        $('#edit_status').val(status);
    });
});
</script>
@endpush