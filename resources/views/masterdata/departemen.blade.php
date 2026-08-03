@extends('layouts.app') {{-- Sesuaikan nama main layout kamu --}}

@section('title', 'Master Data Departemen')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Data Departemen</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Departemen</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        
        {{-- Flash Message Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Alert Jika Ada Error Validasi Saat Submit Modal --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Gagal Menyimpan!</strong> Silakan periksa kembali isian form Anda.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary shadow-sm">
                    {{-- Header Card --}}
                    <div class="card-header d-flex justify-content-between align-items-center w-100">
                        <h3 class="card-title text-primary font-weight-bold mb-0">
                            <i class="bi bi-building me-1"></i> Daftar Departemen
                        </h3>
                        <div class="card-tools ms-auto">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Departemen
                            </button>
                        </div>
                    </div>

                    {{-- Body Card --}}
                    <div class="card-body">
                        {{-- Search Bar --}}
                        <form method="GET" action="{{ route('masterdata.departemen') }}" class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" class="form-control" placeholder="Cari kode atau nama departemen..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-secondary">Cari</button>
                                </div>
                            </div>
                        </form>

                        {{-- Data Table --}}
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle border">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px" class="text-center">#</th>
                                        <th style="width: 200px">Kode Departemen</th>
                                        <th>Nama Departemen</th>
                                        <th style="width: 150px" class="text-center">Jumlah User</th>
                                        <th style="width: 150px" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($departemens as $index => $dept)
                                        <tr>
                                            <td class="text-center">{{ method_exists($departemens, 'firstItem') ? $departemens->firstItem() + $index : $index + 1 }}</td>
                                            <td><span class="badge bg-secondary font-monospace fs-6">{{ $dept->kode_departemen ?? '-' }}</span></td>
                                            <td class="fw-semibold">{{ $dept->nama_departemen }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-info text-dark">
                                                    <i class="bi bi-people-fill me-1"></i>{{ $dept->users_count ?? 0 }} User
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    {{-- Tombol Edit --}}
                                                    <button type="button" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#editModal{{ $dept->id }}" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    {{-- Tombol Hapus --}}
                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $dept->id }}" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>

                                                {{-- MODAL EDIT DEPARTEMEN --}}
                                                <div class="modal fade" id="editModal{{ $dept->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content text-start">
                                                            <div class="modal-header bg-warning text-white">
                                                                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Departemen</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('masterdata.departemen.update', $dept->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label font-weight-bold">Kode Departemen</label>
                                                                        <input type="text" name="kode_departemen" class="form-control" value="{{ old('kode_departemen', $dept->kode_departemen) }}" placeholder="Contoh: EDP, HRD, LOG">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label font-weight-bold">Nama Departemen <span class="text-danger">*</span></label>
                                                                        <input type="text" name="nama_departemen" class="form-control" value="{{ old('nama_departemen', $dept->nama_departemen) }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-warning text-white"><i class="bi bi-save me-1"></i> Update</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- MODAL HAPUS DEPARTEMEN --}}
                                                <div class="modal fade" id="deleteModal{{ $dept->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content text-start">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus departemen <strong>{{ $dept->nama_departemen }}</strong>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <form action="{{ route('masterdata.departemen.destroy', $dept->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                Data departemen belum tersedia atau tidak ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Footer Card (Pagination) --}}
                    @if(method_exists($departemens, 'hasPages') && $departemens->hasPages())
                        <div class="card-footer clearfix">
                            <div class="float-end">
                                {{ $departemens->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH DEPARTEMEN --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createModalLabel">
                    <i class="bi bi-building-add me-2"></i>Tambah Departemen Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('masterdata.departemen.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode_departemen" class="form-label font-weight-bold">Kode Departemen</label>
                        <input type="text" name="kode_departemen" id="kode_departemen" class="form-control @error('kode_departemen') is-invalid @enderror" value="{{ old('kode_departemen') }}" placeholder="Contoh: EDP, HRD, LOG">
                        @error('kode_departemen')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nama_departemen" class="form-label font-weight-bold">Nama Departemen <span class="text-danger">*</span></label>
                        <input type="text" name="nama_departemen" id="nama_departemen" class="form-control @error('nama_departemen') is-invalid @enderror" value="{{ old('nama_departemen') }}" placeholder="Contoh: Information Technology" required>
                        @error('nama_departemen')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save-fill me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script Otomatis Buka Modal Create jika Ada Validation Error --}}
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var createModal = new bootstrap.Modal(document.getElementById('createModal'));
        createModal.show();
    });
</script>
@endif

@endsection