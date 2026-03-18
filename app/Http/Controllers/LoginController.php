<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLogin()
    {


        // jika sudah login lewat guard Auth
        if (auth()->check()) {
            $type = strtolower(auth()->user()->usertype ?? '');
        }
        // atau jika info user disimpan di session manual
        elseif (session()->has('user') || session()->has('usertype')) {
            $type = '';
            if (session()->has('user')) {
            $type = strtolower(session('user.usertype') ?? '');
            }
            if (!$type && session()->has('usertype')) {
            $type = strtolower(session('usertype'));
            }
        } else {
            $type = '';
        }

        switch ($type) {
            case 'admin':
            return redirect('/admin/dashboard');
            case 'siswa':
            return redirect('/siswa/dashboard');
            case 'guru':
            return redirect('/guru/dashboard');
            default:
            return view('auth.login');
        }

    }
}
