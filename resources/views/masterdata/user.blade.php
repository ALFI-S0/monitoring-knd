@extends('layouts.app') {{-- Sesuaikan nama main layout kamu --}}

@section('title', 'Master Data User')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Data User</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User</li>
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
                            <i class="bi bi-people-fill me-1"></i> Daftar Pengguna
                        </h3>
                        <div class="card-tools ms-auto">
                            {{-- Tombol Trigger Modal Create --}}
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                                <i class="bi bi-person-plus-fill me-1"></i> Tambah User Baru
                            </button>
                        </div>
                    </div>

                    {{-- Body Card --}}
                    <div class="card-body">
                        {{-- Filter & Search Bar --}}
                        <form method="GET" action="{{ route('masterdata.user') }}" class="row g-3 mb-4">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                    <input type="text" name="search" class="form-control" placeholder="Cari nama, email, atau departemen..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select name="departemen_id" class="form-select">
                                    <option value="">-- Semua Departemen --</option>
                                    @foreach($departemens as $dept)
                                        <option value="{{ $dept->id }}" {{ request('departemen_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->nama_departemen }} {{ $dept->kode_departemen ? '('.$dept->kode_departemen.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-secondary w-100">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                            </div>
                        </form>

                        {{-- Data Table --}}
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle border">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px" class="text-center">#</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>Departemen</th>
                                        <th style="width: 130px" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Looping Data User --}}
                                    @forelse ($users as $index => $user)
                                        <tr>
                                            <td class="text-center">{{ method_exists($users, 'firstItem') ? $users->firstItem() + $index : $index + 1 }}</td>
                                            <td class="fw-semibold">{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->departemen)
                                                    <span class="badge bg-info text-dark">
                                                        <i class="bi bi-building me-1"></i>{{ $user->departemen->nama_departemen }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small"><i>Tanpa Departemen</i></span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    {{-- Tombol Trigger Modal Edit --}}
                                                    <button type="button" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}" title="Edit User">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    {{-- Tombol Trigger Modal Hapus --}}
                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}" title="Hapus User">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>

                                                {{-- MODAL EDIT USER --}}
                                                <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                                        <div class="modal-content text-start">
                                                            <div class="modal-header bg-warning text-white">
                                                                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Data User</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('masterdata.user.update', $user->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    {{-- Nama Lengkap --}}
                                                                    <div class="mb-3">
                                                                        <label class="form-label font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text bg-light"><i class="bi bi-person-fill"></i></span>
                                                                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        {{-- Email --}}
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label font-weight-bold">Alamat Email <span class="text-danger">*</span></label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text bg-light"><i class="bi bi-envelope-fill"></i></span>
                                                                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                                                            </div>
                                                                        </div>

                                                                        {{-- Departemen --}}
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label font-weight-bold">Departemen</label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                                                                <select name="departemen_id" class="form-select">
                                                                                    <option value="">-- Tanpa Departemen --</option>
                                                                                    @foreach($departemens as $dept)
                                                                                        <option value="{{ $dept->id }}" {{ old('departemen_id', $user->departemen_id) == $dept->id ? 'selected' : '' }}>
                                                                                            {{ $dept->nama_departemen }} {{ $dept->kode_departemen ? '('.$dept->kode_departemen.')' : '' }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    {{-- Ganti Password Optional --}}
                                                                    <div class="mb-3">
                                                                        <label class="form-label font-weight-bold">Password Baru <span class="text-muted fw-normal">(Kosongkan jika tidak ingin diubah)</span></label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                                                                            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-warning text-white"><i class="bi bi-save me-1"></i> Update Data</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- MODAL KONFIRMASI HAPUS --}}
                                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-start">
                                                                Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <form action="{{ route('masterdata.user.destroy', $user->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- End Modal Hapus --}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                Data user belum tersedia atau tidak ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Footer Card (Pagination) --}}
                    @if(method_exists($users, 'hasPages') && $users->hasPages())
                        <div class="card-footer clearfix">
                            <div class="float-end">
                                {{ $users->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH USER BARU --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createModalLabel">
                    <i class="bi bi-person-plus-fill me-2"></i>Tambah User Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('masterdata.user.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    {{-- Nama Lengkap --}}
                    <div class="mb-3">
                        <label for="name" class="form-label font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person-fill"></i></span>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        {{-- Email --}}
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label font-weight-bold">Alamat Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope-fill"></i></span>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="contoh@domain.com" required>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Select Departemen --}}
                        <div class="col-md-6 mb-3">
                            <label for="departemen_id" class="form-label font-weight-bold">Departemen</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                <select name="departemen_id" id="departemen_id" class="form-select @error('departemen_id') is-invalid @enderror">
                                    <option value="" selected>-- Pilih Departemen --</option>
                                    @foreach($departemens as $dept)
                                        <option value="{{ $dept->id }}" {{ old('departemen_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->nama_departemen }} {{ $dept->kode_departemen ? '('.$dept->kode_departemen.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('departemen_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        {{-- Password --}}
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label font-weight-bold">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min 8 karakter" required>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label font-weight-bold">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-shield-check"></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                            </div>
                        </div>
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

{{-- Script Otomatis Buka Modal Create jika Ada Error Validation --}}
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var createModal = new bootstrap.Modal(document.getElementById('createModal'));
        createModal.show();
    });
</script>
@endif

@endsection