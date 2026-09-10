<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrCreate(
            ['id' => 1],
            [
                'nama_toko'    => 'KASIR SYAHARUDDINFS',
                'no_hp'        => '081234567890',
                'alamat'       => 'Jl. Utama No. 123',
                'min_stok'     => 3,
                'catatan_nota' => 'Terima kasih telah berbelanja!',
            ]
        );

        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_toko'    => 'required|string|max:255',
            'no_hp'        => 'nullable|string|max:20',
            'alamat'       => 'nullable|string',
            'min_stok'     => 'required|integer|min:1',
            'catatan_nota' => 'nullable|string',
        ]);

        $setting = Setting::firstOrCreate(['id' => 1]);
        $setting->update($request->only(['nama_toko', 'no_hp', 'alamat', 'min_stok', 'catatan_nota']));

        return redirect()->back()->with('success', 'Pengaturan toko berhasil diperbarui!');
    }
}