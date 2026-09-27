<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'jam_masuk'  => Setting::get('jam_masuk', '07:00'),
            'jam_pulang' => Setting::get('jam_pulang', '15:00'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'jam_masuk'  => 'required',
            'jam_pulang' => 'required',
        ]);

        $jamMasuk  = \Carbon\Carbon::parse($request->jam_masuk)->format('H:i');
        $jamPulang = \Carbon\Carbon::parse($request->jam_pulang)->format('H:i');

        Setting::set('jam_masuk', $jamMasuk);
        Setting::set('jam_pulang', $jamPulang);

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
