@extends('layouts.app')

@section('content')
<div class="app-content-header py-4">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1 text-dark">Tambah Perbaikan Kendaraan</h2>
                <p class="text-muted small mb-0">Input data kendaraan perusahaan yang sedang mengalami kerusakan atau servis rutin</p>
            </div>
            <a href="{{ route('perbaikan.index') }}" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        
        @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card dashboard-card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <form action="{{ route('perbaikan.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        {{-- Kendaraan --}}
                        <div class="col-md-4 mb-2">
                            <label for="kendaraan_id" class="form-label text-secondary small fw-bold text-uppercase">Kendaraan</label>
                            <select id="kendaraan_id" name="kendaraan_id" class="form-select @error('kendaraan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kendaraan --</option>
                                @foreach ($kendaraans as $kendaraan)
                                    <option value="{{ $kendaraan->id }}" {{ old('kendaraan_id') == $kendaraan->id ? 'selected' : '' }}>
                                        {{ $kendaraan->no_polisi }} — {{ $kendaraan->merk }} {{ $kendaraan->tipe }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kendaraan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal Perbaikan --}}
                        <div class="col-md-4 mb-2">
                            <label for="tanggal_perbaikan" class="form-label text-secondary small fw-bold text-uppercase">Tanggal Perbaikan</label>
                            <input type="date" id="tanggal_perbaikan" name="tanggal_perbaikan" value="{{ old('tanggal_perbaikan', date('Y-m-d')) }}" class="form-control @error('tanggal_perbaikan') is-invalid @enderror" required>
                            @error('tanggal_perbaikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Estimasi Selesai (Diubah menjadi datetime-local) --}}
                        <div class="col-md-4 mb-2">
                            <label for="estimasi_selesai" class="form-label text-secondary small fw-bold text-uppercase">Estimasi Selesai (Tanggal & Jam)</label>
                            <input type="datetime-local" id="estimasi_selesai" name="estimasi_selesai" value="{{ old('estimasi_selesai') }}" class="form-control @error('estimasi_selesai') is-invalid @enderror">
                            @error('estimasi_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kendala --}}
                        <div class="col-md-12 mb-2">
                            <label for="kendala" class="form-label text-secondary small fw-bold text-uppercase">Kendala / Kerusakan</label>
                            <textarea id="kendala" name="kendala" rows="3" class="form-control @error('kendala') is-invalid @enderror" placeholder="Deskripsikan gejala kerusakan pada kendaraan..." required>{{ old('kendala') }}</textarea>
                            @error('kendala')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tindakan --}}
                        <div class="col-md-12 mb-2">
                            <label for="tindakan" class="form-label text-secondary small fw-bold text-uppercase">Tindakan Sementara <span class="text-lowercase text-muted">(opsional)</span></label>
                            <textarea id="tindakan" name="tindakan" rows="3" class="form-control @error('tindakan') is-invalid @enderror" placeholder="Tindakan awal yang sudah dilakukan...">{{ old('tindakan') }}</textarea>
                            @error('tindakan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Catatan --}}
                        <div class="col-md-12 mb-3">
                            <label for="catatan" class="form-label text-secondary small fw-bold text-uppercase">Catatan Tambahan</label>
                            <textarea id="catatan" name="catatan" rows="2" class="form-control @error('catatan') is-invalid @enderror" placeholder="Catatan opsional lainnya...">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="text-end border-top pt-3">
                        <a href="{{ route('perbaikan.index') }}" class="btn btn-light rounded-pill px-4 me-2">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-save me-1"></i> Simpan Data Perbaikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-card { border-radius: 16px; background-color: #ffffff; }
.form-label { font-weight: 600; color: #495057; }
.form-control, .form-select { border-radius: 8px; padding: 0.6rem 0.75rem; border-color: #dee2e6; }
.form-control:focus, .form-select:focus { box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15); border-color: #0d6efd; }
textarea { resize: none; }
</style>
@endsection