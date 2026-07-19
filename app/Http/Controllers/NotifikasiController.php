<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Mark single notifikasi sebagai dibaca -> langsung hapus
     */
    public function read($id)
    {
        auth()->user()->notifications()->findOrFail($id)->delete();
        return response()->json(['ok' => true]);
    }

    /**
     * Mark semua notifikasi dibaca
     */
    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }

    /**
     * Delete notifikasi
     */
    public function destroy($id)
    {
        auth()->user()->notifications()->findOrFail($id)->delete();
        return response()->json(['ok' => true]);
    }
}
