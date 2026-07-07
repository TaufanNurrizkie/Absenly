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
            'jam_masuk'  => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i',
        ]);

        Setting::updateOrCreate(['key' => 'jam_masuk'], ['value' => $request->jam_masuk]);
        Setting::updateOrCreate(['key' => 'jam_pulang'], ['value' => $request->jam_pulang]);

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
