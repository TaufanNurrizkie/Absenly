<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set maximum idle time in seconds (60 minutes = 3600 seconds)
        $timeout = config('session.lifetime') * 60;
        
        if (Auth::check()) {
            $lastActivity = session('last_activity');
            
            if ($lastActivity && (time() - $lastActivity > $timeout)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')->withErrors([
                    'nis' => 'Sesi Anda telah berakhir karena tidak ada aktivitas. Silakan login kembali.'
                ]);
            }
            
            // Update last activity
            session(['last_activity' => time()]);
        }
        
        return $next($request);
    }
}
