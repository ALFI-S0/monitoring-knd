@extends('layouts.app')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">Tambah Kendaraan</h3>
                    <small class="text-muted">Tambahkan data kendaraan baru</small>
                </div>

                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="card shadow-sm">

                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-car-front-fill me-2"></i>
                        Form Data Kendaraan
                    </h5>
                </div>

                <form action="{{ route('kendaraan.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="card-body">

                        <div class="row">

                            {{-- Nomor Polisi --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Nomor Polisi
                                </label>

                                <input type="text" name="no_polisi"
                                    class="form-control @error('no_polisi') is-invalid @enderror"
                                    placeholder="Contoh : B 1234 ABC" value="{{ old('no_polisi') }}">

                                @error('no_polisi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Merk --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Merk Kendaraan
                                </label>

                                <input type="text" name="merk"
                                    class="form-control @error('merk') is-invalid @enderror"
                                    placeholder="Toyota, Honda, Mitsubishi..." value="{{ old('merk') }}">

                                @error('merk')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Tipe --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Tipe Kendaraan
                                </label>

                                <input type="text" name="tipe" class="form-control" placeholder="Avanza, Pajero, dll"
                                    value="{{ old('tipe') }}">
                            </div>

                            {{-- Tahun --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Tahun
                                </label>

                                <input type="number" name="tahun" class="form-control" min="2000"
                                    max="{{ date('Y') }}" value="{{ old('tahun') }}">
                            </div>

                            {{-- Warna --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Warna
                                </label>

                                <input type="text" name="warna" class="form-control" placeholder="Putih"
                                    value="{{ old('warna') }}">
                            </div>

                            {{-- Kilometer --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Kilometer
                                </label>

                                <div class="input-group">
                                    <input type="number" name="kilometer" class="form-control" placeholder="0"
                                        value="{{ old('kilometer') }}">

                                    <span class="input-group-text">
                                        KM
                                    </span>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Status
                                </label>

                                <select name="status" class="form-select">

                                    <option value="">-- Pilih Status --</option>

                                    <option value="Ready">Ready</option>

                                    <option value="Dipakai">Dipakai</option>

                                    <option value="Perbaikan">Perbaikan</option>

                                    <option value="Servis">Servis</option>

                                </select>
                            </div>

                            {{-- Servis --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Tanggal Servis Terakhir
                                </label>

                                <input type="date" name="tanggal_servis" class="form-control"
                                    value="{{ old('tanggal_servis') }}">
                            </div>

                            {{-- Foto --}}
                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Foto Kendaraan
                                </label>

                                <input type="file" class="form-control" name="foto">

                            </div>

                            {{-- Keterangan --}}
                            <div class="col-md-12">

                                <label class="form-label">
                                    Keterangan
                                </label>

                                <textarea name="keterangan" rows="4" class="form-control" placeholder="Masukkan keterangan kendaraan...">{{ old('keterangan') }}</textarea>

                            </div>

                        </div>

                    </div>

                    <div class="card-footer d-flex justify-content-end">

                        <button type="reset" class="btn btn-light me-2">

                            <i class="bi bi-arrow-clockwise"></i>

                            Reset

                        </button>

                        <button type="submit" class="btn btn-primary">

                            <i class="bi bi-save"></i>

                            Simpan Kendaraan

                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
@endsection
