<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Kendaraan;

class KendaraanController extends Controller
{
    public function index()
    {
        $totalKendaraan = Kendaraan::count();

        $kendaraanReady = Kendaraan::where('status', 'Ready')->count();

        $kendaraanPerbaikan = Kendaraan::where('status', 'Perbaikan')->count();

        $listReady = Kendaraan::where('status', 'Ready')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact(
            'totalKendaraan',
            'kendaraanReady',
            'kendaraanPerbaikan',
            'listReady'
        ));
    }

    public function create()
    {
        return view('kendaraan.create');
    }

    public function store(Request $request)
    {

        $request->validate([

            'no_polisi' => 'required|unique:kendaraans',

            'merk' => 'required',

            'tipe' => 'required',

            'tahun' => 'required',

            'warna' => 'required',

            'kilometer' => 'required|numeric',

            'status' => 'required',

            'tanggal_servis' => 'nullable|date',

            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'keterangan' => 'nullable'

        ]);

        $foto = null;

        if ($request->hasFile('foto')) {

            $foto = $request
                ->file('foto')
                ->store('kendaraan', 'public');
        }

        Kendaraan::create([

            'no_polisi' => $request->no_polisi,

            'merk' => $request->merk,

            'tipe' => $request->tipe,

            'tahun' => $request->tahun,

            'warna' => $request->warna,

            'kilometer' => $request->kilometer,

            'status' => $request->status,

            'tanggal_servis' => $request->tanggal_servis,

            'foto' => $foto,

            'keterangan' => $request->keterangan

        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Data kendaraan berhasil ditambahkan.');
    }
    public function update(Request $request, string $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $request->validate([
            'no_polisi' => 'required|unique:kendaraans,no_polisi,' . $id,
            'merk' => 'required',
            'tipe' => 'required',
            'tahun' => 'required|numeric',
            'status' => 'required',
            'kilometer' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        // Jika user mengunggah foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($kendaraan->foto) {
                Storage::disk('public')->delete($kendaraan->foto);
            }
            // Simpan foto baru
            $data['foto'] = $request->file('foto')->store('kendaraan', 'public');
        }

        $kendaraan->update($data);

        return redirect()->back()->with('success', 'Data kendaraan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        // Hapus file foto dari storage sebelum record di database dihapus
        if ($kendaraan->foto) {
            Storage::disk('public')->delete($kendaraan->foto);
        }

        $kendaraan->delete();

        return redirect()->back()->with('success', 'Kendaraan berhasil dihapus!');
    }

    public function list()
    {
        $kendaraan = Kendaraan::latest()->paginate(10);

        return view('kendaraan.list', compact('kendaraan'));
    }
}
