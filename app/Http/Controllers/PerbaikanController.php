<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Kendaraan;
use App\Models\Perbaikan;
use Illuminate\Http\Request;

class PerbaikanController extends Controller
{
    /**
     * Menampilkan daftar perbaikan (Bisa diakses oleh semua departemen, termasuk Ekspedisi/ID 2)
     */
    public function index()
    {
        $totalKendaraan = Kendaraan::count();
        $kendaraanPerbaikan = Kendaraan::where('status', 'Perbaikan')->count();

        $kendaraanReady = Kendaraan::where('status', 'Ready')->count();
        $perbaikans = Perbaikan::with('kendaraan')
            ->latest()
            ->get();
                $listReady = Kendaraan::where('status', 'Ready')
        ->latest()
        ->get();

    $listPerbaikan = Perbaikan::with('kendaraan')
                            ->where('status', 'Proses') // atau 'Perbaikan' sesuai database kamu
                            ->get();

        return view('perbaikan.index', compact('perbaikans', 'totalKendaraan', 'kendaraanReady', 'kendaraanPerbaikan', 'listReady', 'listPerbaikan'));
    }

    /**
     * Form tambah perbaikan (Hanya Departemen ID 1 & 3)
     */
    public function create()
    {
        // Pengecekan Hak Akses: Hanya Departemen ID 1 dan 3
        if (!in_array(Auth::user()->departemen_id, [1, 3])) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk menambah perbaikan.');
        }

        // Hanya mengambil kendaraan yang sedang 'Ready' untuk diperbaiki
        $kendaraans = Kendaraan::where('status', 'Ready')
            ->orderBy('no_polisi')
            ->get();

        return view('perbaikan.create', compact('kendaraans'));
    }

    /**
     * Simpan perbaikan (Hanya Departemen ID 1 & 3)
     */
    public function store(Request $request)
    {
        // Pengecekan Hak Akses
        if (!in_array(Auth::user()->departemen_id, [1, 3])) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk mengedit data perbaikan.');
        }

        $request->validate([
            'kendaraan_id'      => 'required|exists:kendaraans,id',
            'tanggal_perbaikan' => 'required|date',
            'kendala'           => 'required',
            'tindakan'          => 'nullable',
            'estimasi_selesai'  => 'nullable|date',
            'catatan'           => 'nullable',
        ]);

        Perbaikan::create([
            'kendaraan_id'      => $request->kendaraan_id,
            'tanggal_perbaikan' => $request->tanggal_perbaikan,
            'kendala'           => $request->kendala,
            'tindakan'          => $request->tindakan,
            'estimasi_selesai'  => $request->estimasi_selesai,
            'catatan'           => $request->catatan,
            'status'            => 'Proses', // Default dari migration
        ]);

        // Update status kendaraan menjadi Perbaikan
        $kendaraan = Kendaraan::findOrFail($request->kendaraan_id);
        $kendaraan->update([
            'status' => 'Perbaikan'
        ]);

        return redirect()
            ->route('perbaikan.index')
            ->with('success', 'Data perbaikan berhasil ditambahkan.');
    }

    /**
     * Detail perbaikan
     */
    public function show(string $id)
    {
        $perbaikan = Perbaikan::with('kendaraan')->findOrFail($id);

        return view('perbaikan.show', compact('perbaikan'));
    }

    /**
     * Update data perbaikan via Modal (Hanya Departemen ID 1 & 3)
     */
    public function update(Request $request, string $id)
    {
        // Pengecekan Hak Akses: Hanya Departemen ID 1 dan 3
        if (!in_array(Auth::user()->departemen_id, [1, 3])) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk menyimpan data perbaikan.');
        }

        $perbaikan = Perbaikan::findOrFail($id);

        // Proteksi Backend: Jika status perbaikan sudah Selesai, cegah update
        if ($perbaikan->status == 'Selesai') {
            return back()->with('error', 'Data perbaikan yang sudah Selesai tidak dapat diubah lagi.');
        }
        
        $request->validate([
            'tanggal_perbaikan' => 'required|date',
            'estimasi_selesai'  => 'nullable|date',
            'tanggal_selesai'   => 'nullable|date',
            'kendala'           => 'required',
            'tindakan'          => 'nullable',
            'catatan'           => 'nullable',
            'status'            => 'required|in:Proses,Selesai',
        ]);

        $perbaikan->update([
            'tanggal_perbaikan' => $request->tanggal_perbaikan,
            'estimasi_selesai'  => $request->estimasi_selesai,
            'tanggal_selesai'   => $request->status == 'Selesai' ? $request->tanggal_selesai : null,
            'kendala'           => $request->kendala,
            'tindakan'          => $request->tindakan,
            'catatan'           => $request->catatan,
            'status'            => $request->status,
        ]);

        if ($request->status == 'Selesai') {
            $perbaikan->kendaraan()->update([
                'status' => 'Ready'
            ]);
        } else {
            $perbaikan->kendaraan()->update([
                'status' => 'Perbaikan'
            ]);
        }

        return redirect()
            ->route('perbaikan.index')
            ->with('success', 'Data perbaikan berhasil diperbarui.');
    }

    /**
     * Hapus data perbaikan (Hanya Departemen ID 1 & 3)
     */
    public function destroy(string $id)
    {
        // Pengecekan Hak Akses: Hanya Departemen ID 1 dan 3
        // Pengecekan Hak Akses
        if (!in_array(Auth::user()->departemen_id, [1, 3])) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk mengedit data perbaikan.');
        }

        $perbaikan = Perbaikan::findOrFail($id);

        if ($perbaikan->status == 'Proses') {
            $perbaikan->kendaraan()->update([
                'status' => 'Ready'
            ]);
        }

        $perbaikan->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }
}
