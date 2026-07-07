@extends('layouts.adminNav')

@section('title', 'Pengaturan Absensi')
@section('page-title', 'Pengaturan Absensi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-1">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Pengaturan Absensi</h2>
        <p class="text-sm text-slate-500">Atur jam batas keterlambatan dan jam mulai absen pulang.</p>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-green-50/50 border border-green-100 flex items-start gap-3 fade-up">
        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div>
            <h4 class="text-sm font-bold text-green-800">Berhasil!</h4>
            <p class="text-sm text-green-600 mt-0.5">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden fade-up delay-1">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Field Jam Masuk -->
                <div class="space-y-2">
                    <label for="jam_masuk" class="block text-sm font-semibold text-slate-800">
                        Jam Batas Telat (Masuk)
                    </label>
                    <p class="text-xs text-slate-500 mb-2">Jika siswa absen masuk melebihi jam ini, maka statusnya akan tercatat "Telat".</p>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <input type="time" name="jam_masuk" id="jam_masuk" required
                            class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                            value="{{ old('jam_masuk', $settings['jam_masuk']) }}">
                    </div>
                    @error('jam_masuk')
                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="h-px bg-slate-100"></div>

                <!-- Field Jam Pulang -->
                <div class="space-y-2">
                    <label for="jam_pulang" class="block text-sm font-semibold text-slate-800">
                        Jam Mulai Pulang
                    </label>
                    <p class="text-xs text-slate-500 mb-2">Siswa hanya dapat melakukan absen pulang mulai jam ini.</p>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <input type="time" name="jam_pulang" id="jam_pulang" required
                            class="block w-full pl-10 pr-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none"
                            value="{{ old('jam_pulang', $settings['jam_pulang']) }}">
                    </div>
                    @error('jam_pulang')
                    <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Footer & Submit -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md hover:shadow-blue-500/20 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
