<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Kendaraan;
use App\Models\Perbaikan;

class KendaraanController extends Controller
{
    public function index()
    {
        $totalKendaraan = Kendaraan::count();

        // Ambil jumlah kendaraan ready
        $kendaraanReady = Kendaraan::where('status', 'Ready')->count();

        // OPSI 1: Jika hitungan berdasarkan tabel perbaikan yang sedang berjalan
        $listPerbaikan = Perbaikan::with('kendaraan')
            ->where('status', 'Proses') // pastikan 'Proses' sesuai dengan value di DB
            ->latest()
            ->get();

        // Hitung total perbaikan mengikuti jumlah data di $listPerbaikan agar sinkron
        $kendaraanPerbaikan = $listPerbaikan->count();

        // Ambil semua data kendaraan ready
        $listReady = Kendaraan::where('status', 'Ready')
            ->latest()
            ->get();

        return view('dashboard.index', compact(
            'totalKendaraan',
            'kendaraanReady',
            'kendaraanPerbaikan',
            'listReady',
            'listPerbaikan'
        ));
    }

    public function create()
    {
        return view('kendaraan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_polisi'      => 'required|unique:kendaraans,no_polisi',
            'merk'           => 'required|string',
            'tipe'           => 'required|string',
            'tahun'          => 'required|numeric',
            'warna'          => 'required|string',
            'kilometer'      => 'required|numeric',
            'status'         => 'required|string',
            'tanggal_servis' => 'nullable|date',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'keterangan'     => 'nullable|string'
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('kendaraan', 'public');
        }

        Kendaraan::create($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Data kendaraan berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $validated = $request->validate([
            'no_polisi' => 'required|unique:kendaraans,no_polisi,' . $id,
            'merk'      => 'required|string',
            'tipe'      => 'required|string',
            'tahun'     => 'required|numeric',
            'status'    => 'required|string',
            'kilometer' => 'required|numeric',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Jika ada unggahan foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika tersimpan di disk
            if ($kendaraan->foto && Storage::disk('public')->exists($kendaraan->foto)) {
                Storage::disk('public')->delete($kendaraan->foto);
            }
            
            // Simpan foto baru ke array $validated
            $validated['foto'] = $request->file('foto')->store('kendaraan', 'public');
        }

        // Update menggunakan data yang aman ($validated)
        $kendaraan->update($validated);

        return redirect()->back()->with('success', 'Data kendaraan berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        // Hapus file foto dari storage sebelum hapus database
        if ($kendaraan->foto && Storage::disk('public')->exists($kendaraan->foto)) {
            Storage::disk('public')->delete($kendaraan->foto);
        }

        $kendaraan->delete();

        return redirect()->back()->with('success', 'Kendaraan berhasil dihapus!');
    }

public function list()
{
    $kendaraan = Kendaraan::latest()->get();

    return view('kendaraan.list', compact('kendaraan'));
}
}