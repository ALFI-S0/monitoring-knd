<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Kendaraan;
use App\Models\Perbaikan;
use Illuminate\Http\Request;

class PerbaikanController extends Controller
{
    /**
     * Menampilkan daftar perbaikan (Dapat diakses oleh semua departemen)
     */
    public function index()
    {
        $totalKendaraan     = Kendaraan::count();
        $kendaraanReady     = Kendaraan::where('status', 'Ready')->count();
        $kendaraanPerbaikan = Kendaraan::where('status', 'Perbaikan')->count();

        // Ambil seluruh data perbaikan beserta relasi kendaraannya
        $perbaikans = Perbaikan::with('kendaraan')
            ->latest()
            ->get();

        return view('perbaikan.index', compact(
            'perbaikans', 
            'totalKendaraan', 
            'kendaraanReady', 
            'kendaraanPerbaikan'
        ));
    }

    /**
     * Form tambah perbaikan (Hanya Departemen ID 1 & 3)
     */
    public function create()
    {
        if (!in_array(Auth::user()->departemen_id, [1, 3])) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk menambah data perbaikan.');
        }

        // Hanya mengambil kendaraan yang berstatus 'Ready'
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
        if (!in_array(Auth::user()->departemen_id, [1, 3])) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk menambah data perbaikan.');
        }

        $validated = $request->validate([
            'kendaraan_id'      => 'required|exists:kendaraans,id',
            'tanggal_perbaikan' => 'required|date',
            'kendala'           => 'required|string',
            'tindakan'          => 'nullable|string',
            'estimasi_selesai'  => 'nullable|date',
            'catatan'           => 'nullable|string',
        ]);

        // Tambahkan status default 'Proses'
        $validated['status'] = 'Proses';

        $perbaikan = Perbaikan::create($validated);

        // Update status kendaraan terkait menjadi 'Perbaikan'
        $perbaikan->kendaraan()->update([
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
        if (!in_array(Auth::user()->departemen_id, [1, 3])) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk mengubah data perbaikan.');
        }

        $perbaikan = Perbaikan::findOrFail($id);

        // Proteksi Backend: Jika status perbaikan sudah Selesai, cegah update
        if ($perbaikan->status === 'Selesai') {
            return back()->with('error', 'Data perbaikan yang sudah Selesai tidak dapat diubah lagi.');
        }

        $validated = $request->validate([
            'tanggal_perbaikan' => 'required|date',
            'estimasi_selesai'  => 'nullable|date',
            'tanggal_selesai'   => 'nullable|date',
            'kendala'           => 'required|string',
            'tindakan'          => 'nullable|string',
            'catatan'           => 'nullable|string',
            'status'            => 'required|in:Proses,Selesai',
        ]);

        // Atur tanggal selesai berdasarkan status
        $validated['tanggal_selesai'] = ($request->status === 'Selesai') ? $request->tanggal_selesai : null;

        $perbaikan->update($validated);

        // Update status armada kendaraan
        $statusKendaraan = ($request->status === 'Selesai') ? 'Ready' : 'Perbaikan';
        $perbaikan->kendaraan()->update([
            'status' => $statusKendaraan
        ]);

        return redirect()
            ->route('perbaikan.index')
            ->with('success', 'Data perbaikan berhasil diperbarui.');
    }

    /**
     * Hapus data perbaikan (Hanya Departemen ID 1 & 3)
     */
    public function destroy(string $id)
    {
        if (!in_array(Auth::user()->departemen_id, [1, 3])) {
            abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk menghapus data perbaikan.');
        }

        $perbaikan = Perbaikan::findOrFail($id);

        // Jika data perbaikan masih berstatus 'Proses' lalu dihapus, kembalikan kendaraan ke 'Ready'
        if ($perbaikan->status === 'Proses') {
            $perbaikan->kendaraan()->update([
                'status' => 'Ready'
            ]);
        }

        $perbaikan->delete();

        return back()->with('success', 'Data perbaikan berhasil dihapus.');
    }
}