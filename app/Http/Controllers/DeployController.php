<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeployController extends Controller
{
    /**
     * Run Artisan commands remotely via secure HTTP token.
     * Useful for cPanel hosting without terminal/SSH access.
     */
    public function handle(Request $request)
    {
        $secret = env('DEPLOY_SECRET_KEY');

        // Proteksi: wajib disetel di .env dan token request harus cocok
        if (empty($secret) || $request->query('token') !== $secret) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized: Token rahasia tidak valid atau DEPLOY_SECRET_KEY belum disetel di .env.'
            ], 403);
        }

        $action = $request->query('action', 'info');

        try {
            switch ($action) {
                case 'migrate':
                    Artisan::call('migrate', ['--force' => true]);
                    $output = Artisan::output();
                    return response()->json([
                        'status'  => 'success',
                        'action'  => 'migrate',
                        'message' => 'Database migration berhasil dijalankan.',
                        'output'  => $output
                    ]);

                case 'optimize':
                    Artisan::call('optimize:clear');
                    $clearOutput = Artisan::output();
                    Artisan::call('config:cache');
                    Artisan::call('route:cache');
                    Artisan::call('view:cache');
                    $cacheOutput = Artisan::output();
                    return response()->json([
                        'status'  => 'success',
                        'action'  => 'optimize',
                        'message' => 'Cache berhasil dibersihkan dan dioptimasi.',
                        'output'  => $clearOutput . "\n" . $cacheOutput
                    ]);

                case 'clear':
                    Artisan::call('optimize:clear');
                    return response()->json([
                        'status'  => 'success',
                        'action'  => 'clear',
                        'message' => 'Cache berhasil dibersihkan.',
                        'output'  => Artisan::output()
                    ]);

                case 'storage-link':
                    Artisan::call('storage:link');
                    return response()->json([
                        'status'  => 'success',
                        'action'  => 'storage-link',
                        'message' => 'Storage link berhasil dibuat.',
                        'output'  => Artisan::output()
                    ]);

                case 'info':
                default:
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'Deploy helper aktif. Gunakan parameter action=migrate | optimize | clear | storage-link',
                        'available_actions' => ['migrate', 'optimize', 'clear', 'storage-link']
                    ]);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
