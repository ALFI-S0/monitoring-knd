<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class MasterdataController extends Controller
{
    /* =========================================================================
     | MASTER DATA USER
     | ========================================================================= */

    /**
     * Tampilkan daftar user + Search, Filter Departemen & Pagination
     */
    public function user(Request $request)
    {
        // Eager loading relasi departemen
        $query = User::with('departemen');

        // Fitur Search (Nama, Email, atau Nama/Kode Departemen)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhereHas('departemen', function ($deptQuery) use ($search) {
                      $deptQuery->where('nama_departemen', 'like', '%' . $search . '%')
                                ->orWhere('kode_departemen', 'like', '%' . $search . '%');
                  });
            });
        }

        // Fitur Filter Departemen berdasarkan ID
        if ($request->filled('departemen_id')) {
            $query->where('departemen_id', $request->departemen_id);
        }

        // Ambil data user dengan pagination
        $users = $query->latest()->paginate(10)->withQueryString();

        // Ambil data departemen untuk dropdown filter & modal form
        $departemens = collect();
        try {
            $departemens = Departemen::orderBy('nama_departemen', 'asc')->get();
        } catch (\Exception $e) {
            // Menghindari crash jika tabel belum di-migrate
        }

        return view('masterdata.user', compact('users', 'departemens'));
    }

    /**
     * Simpan user baru ke database
     */
    public function userStore(Request $request)
    {
        $deptTable = (new Departemen())->getTable();

        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'departemen_id' => ['nullable', "exists:{$deptTable},id"],
            'password'      => ['required', Rules\Password::defaults()],
        ]);

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'departemen_id' => $request->departemen_id,
            'password'      => Hash::make($request->password),
        ]);

        return redirect()->route('masterdata.user')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Update data user
     */
    public function userUpdate(Request $request, User $user)
    {
        $deptTable = (new Departemen())->getTable();

        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'departemen_id' => ['nullable', "exists:{$deptTable},id"],
        ]);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'departemen_id' => $request->departemen_id,
        ];

        // Jika password diisi, update password
        if ($request->filled('password')) {
            $request->validate([
                'password' => [Rules\Password::defaults()],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('masterdata.user')->with('success', 'Data user berhasil diperbarui!');
    }

    /**
     * Hapus user
     */
    public function userDestroy(User $user)
    {
        $user->delete();

        return redirect()->route('masterdata.user')->with('success', 'User berhasil dihapus!');
    }


    /* =========================================================================
     | MASTER DATA DEPARTEMEN
     | ========================================================================= */

    /**
     * Tampilkan daftar departemen + Search & Hitung User
     */
    public function departemen(Request $request)
    {
        $query = Departemen::withCount('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_departemen', 'like', '%' . $search . '%')
                  ->orWhere('kode_departemen', 'like', '%' . $search . '%');
            });
        }

        $departemens = $query->latest()->paginate(10)->withQueryString();

        return view('masterdata.departemen', compact('departemens'));
    }

    /**
     * Simpan departemen baru
     */
    public function departemenStore(Request $request)
    {
        $deptTable = (new Departemen())->getTable();

        $request->validate([
            'kode_departemen' => ['nullable', 'string', 'max:50', "unique:{$deptTable},kode_departemen"],
            'nama_departemen' => ['required', 'string', 'max:255'],
        ]);

        Departemen::create([
            'kode_departemen' => $request->kode_departemen,
            'nama_departemen' => $request->nama_departemen,
        ]);

        return redirect()->route('masterdata.departemen')->with('success', 'Departemen berhasil ditambahkan!');
    }

    /**
     * Update data departemen
     */
    public function departemenUpdate(Request $request, Departemen $departemen)
    {
        $deptTable = (new Departemen())->getTable();

        $request->validate([
            'kode_departemen' => ['nullable', 'string', 'max:50', "unique:{$deptTable},kode_departemen," . $departemen->id],
            'nama_departemen' => ['required', 'string', 'max:255'],
        ]);

        $departemen->update([
            'kode_departemen' => $request->kode_departemen,
            'nama_departemen' => $request->nama_departemen,
        ]);

        return redirect()->route('masterdata.departemen')->with('success', 'Departemen berhasil diperbarui!');
    }

    /**
     * Hapus departemen
     */
    public function departemenDestroy(Departemen $departemen)
    {
        $departemen->delete();

        return redirect()->route('masterdata.departemen')->with('success', 'Departemen berhasil dihapus!');
    }
}