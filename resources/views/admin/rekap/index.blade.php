@extends('layouts.adminNav')

@section('title', 'Rekap Absensi')
@section('page-title', 'Rekap Absensi')

@section('content')

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Rekap Absensi</h1>
            <p class="text-xs text-gray-400 mt-0.5">Data kehadiran seluruh siswa</p>
        </div>
        <a href="{{ route('admin.rekap.export', request()->all()) }}"
            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition w-full sm:w-auto justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Export Excel
        </a>
    </div>

    {{-- Filter Card --}}
    <form method="GET" class="bg-white border border-gray-200 rounded-2xl p-4 mb-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Filter Data</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3">

            {{-- Search --}}
            <div class="relative xl:col-span-2">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari Nama / NIS..."
                    class="w-full border border-gray-200 rounded-xl pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
            </div>

            {{-- Kelas --}}
            <select name="kelas" onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                <option value="">Semua Kelas</option>
                <option value="X"   {{ request('kelas') == 'X'   ? 'selected' : '' }}>X</option>
                <option value="XI"  {{ request('kelas') == 'XI'  ? 'selected' : '' }}>XI</option>
                <option value="XII" {{ request('kelas') == 'XII' ? 'selected' : '' }}>XII</option>
            </select>

            {{-- Jurusan --}}
            <select name="jurusan" onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                <option value="">Semua Jurusan</option>
                <option value="RPL" {{ request('jurusan') == 'RPL' ? 'selected' : '' }}>RPL</option>
                <option value="TKJ" {{ request('jurusan') == 'TKJ' ? 'selected' : '' }}>TKJ</option>
            </select>

            {{-- Status --}}
            <select name="keterangan" onchange="this.form.submit()"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="hadir" {{ request('keterangan') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="izin"  {{ request('keterangan') == 'izin'  ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ request('keterangan') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="alpha" {{ request('keterangan') == 'alpha' ? 'selected' : '' }}>Alpha</option>
            </select>

            {{-- Tanggal --}}
            <div class="sm:col-span-2 lg:col-span-1 xl:col-span-1 grid grid-cols-2 gap-2">
                <input type="date" name="start" value="{{ request('start') }}"
                    onchange="this.form.submit()"
                    class="border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                <input type="date" name="end" value="{{ request('end') }}"
                    onchange="this.form.submit()"
                    class="border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
            </div>

        </div>

        {{-- Hanya tombol reset & search fallback --}}

    </form>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">NIS</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Kelas</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Jurusan</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Waktu</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $absen)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $absen->user->name }}</td>
                        <td class="px-5 py-3 num text-gray-500">{{ $absen->user->nis }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $absen->user->kelas }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $absen->user->jurusan }}</td>
                        <td class="px-5 py-3">
                            @php
                                $statusClass = match($absen->keterangan) {
                                    'hadir' => 'bg-emerald-50 text-emerald-700',
                                    'izin'  => 'bg-yellow-50 text-yellow-700',
                                    'sakit' => 'bg-blue-50 text-blue-700',
                                    default => 'bg-red-50 text-red-700',
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst($absen->keterangan) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 num text-gray-500">{{ $absen->waktu ?? '—' }}</td>
                        <td class="px-5 py-3 num text-gray-500">{{ $absen->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center">
                            <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-sm text-gray-400">Tidak ada data absensi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $data->withQueryString()->links() }}
        </div>
    </div>

    {{-- Mobile Card List --}}
    <div class="md:hidden space-y-3">
        @forelse($data as $absen)
        @php
            $dot = match($absen->keterangan) {
                'hadir' => 'bg-emerald-400',
                'izin'  => 'bg-yellow-400',
                'sakit' => 'bg-blue-400',
                default => 'bg-red-400',
            };
            $badge = match($absen->keterangan) {
                'hadir' => 'bg-emerald-50 text-emerald-700',
                'izin'  => 'bg-yellow-50 text-yellow-700',
                'sakit' => 'bg-blue-50 text-blue-700',
                default => 'bg-red-50 text-red-700',
            };
        @endphp
        <div class="bg-white border border-gray-200 rounded-2xl p-4">
            <div class="flex items-start justify-between gap-2 mb-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center text-blue-700 font-bold text-sm shrink-0">
                        {{ strtoupper(substr($absen->user->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-800 truncate text-sm">{{ $absen->user->name }}</p>
                        <p class="text-xs text-gray-400 num">{{ $absen->user->nis }}</p>
                    </div>
                </div>
                <span class="shrink-0 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $badge }}">
                    {{ ucfirst($absen->keterangan) }}
                </span>
            </div>
            <div class="grid grid-cols-3 gap-2 text-xs">
                <div class="bg-gray-50 rounded-lg px-2.5 py-1.5">
                    <p class="text-gray-400 mb-0.5">Kelas</p>
                    <p class="font-medium text-gray-700">{{ $absen->user->kelas }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg px-2.5 py-1.5">
                    <p class="text-gray-400 mb-0.5">Jurusan</p>
                    <p class="font-medium text-gray-700">{{ $absen->user->jurusan }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg px-2.5 py-1.5">
                    <p class="text-gray-400 mb-0.5">Waktu</p>
                    <p class="font-medium text-gray-700 num">{{ $absen->waktu ?? '—' }}</p>
                </div>
            </div>
            <p class="text-[11px] text-gray-400 mt-2.5 num">{{ $absen->created_at->format('d M Y') }}</p>
        </div>
        @empty
        <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center">
            <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm text-gray-400">Tidak ada data absensi</p>
        </div>
        @endforelse

        {{-- Pagination mobile --}}
        <div class="pt-1">
            {{ $data->withQueryString()->links() }}
        </div>
    </div>

@push('scripts')
<script>
    // Search: auto-submit dengan debounce 500ms
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let timer;
        searchInput.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => searchInput.closest('form').submit(), 500);
        });
    }
</script>
@endpush

@endsection