@extends('layouts.adminNav')

@section('title', 'Rekap Absensi')
@section('page-title', 'Rekap Absensi')

@section('content')

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Rekap Absensi</h1>
            <p class="text-xs text-gray-400 mt-0.5">
                {{ $start->translatedFormat('d M Y') }} — {{ $end->translatedFormat('d M Y') }}
            </p>
        </div>
        <a href="{{ route('admin.rekap.export', request()->all()) }}"
            class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition w-full sm:w-auto shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Export Excel
        </a>
    </div>

    {{-- Filter Card --}}
    <form method="GET" id="filterForm" class="bg-white border border-gray-200 rounded-2xl p-4 mb-4">

        {{-- Mode toggle --}}
        <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
            <div class="flex gap-1 bg-gray-100 rounded-xl p-1">
                <button type="button" onclick="setMode('mingguan')"
                    class="px-4 py-1.5 rounded-lg text-sm font-medium transition
                           {{ $mode === 'mingguan' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    Mingguan
                </button>
                <button type="button" onclick="setMode('bulanan')"
                    class="px-4 py-1.5 rounded-lg text-sm font-medium transition
                           {{ $mode === 'bulanan' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    Bulanan
                </button>
            </div>

            {{-- Navigasi periode --}}
            <div class="flex items-center gap-2">
                <a href="{{ request()->fullUrlWithQuery(['mode' => $mode, $mode === 'bulanan' ? 'bulan' : 'minggu' => $prevNav]) }}"
                    class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition text-gray-500 text-base font-medium">‹</a>

                <span class="text-sm font-semibold text-gray-700 min-w-max">
                    @if ($mode === 'bulanan')
                        {{ $start->translatedFormat('F Y') }}
                    @else
                        {{ $start->translatedFormat('d M') }} – {{ $end->translatedFormat('d M Y') }}
                    @endif
                </span>

                <a href="{{ request()->fullUrlWithQuery(['mode' => $mode, $mode === 'bulanan' ? 'bulan' : 'minggu' => $nextNav]) }}"
                    class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition text-gray-500 text-base font-medium">›</a>

                {{-- Picker --}}
                @if ($mode === 'bulanan')
                    <input type="month" name="bulan" value="{{ $bulan }}" onchange="this.form.submit()"
                        class="border border-gray-200 rounded-lg px-2 py-1.5 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                @else
                    <input type="week" id="weekPicker"
                        class="border border-gray-200 rounded-lg px-2 py-1.5 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <input type="hidden" name="minggu" id="mingguInput"
                        value="{{ $minggu ?? now()->startOfWeek()->format('Y-m-d') }}">
                @endif
            </div>
        </div>

        <input type="hidden" name="mode" id="modeInput" value="{{ $mode }}">

        {{-- Filter row --}}
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari Nama / NIS..."
                    class="w-full border border-gray-200 rounded-xl pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <select name="kelas" onchange="this.form.submit()"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                    <option value="">-- Semua Kelas --</option>
                    @foreach ($kelasList as $k)
                        <option value="{{ $k->id }}" {{ (string) $kelas == (string) $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="jurusan" onchange="this.form.submit()"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                    <option value="">-- Semua Jurusan --</option>
                    @foreach ($jurusanList as $j)
                        <option value="{{ $j->id }}" {{ $jurusan == $j->id ? 'selected' : '' }}>
                            {{ $j->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit"
                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-xl transition-all shadow-sm">
                    Cari
                </button>
            </div>
        </div>

        {{-- Reset --}}
        @if (request()->hasAny(['search', 'kelas', 'jurusan', 'minggu', 'bulan']))
            <div class="mt-3">
                <a href="{{ route('admin.rekap') }}"
                    class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reset filter
                </a>
            </div>
        @endif
    </form>

    {{-- Legenda --}}
    <div class="flex flex-wrap gap-2 mb-4">
        @foreach ([['bg-emerald-50 text-emerald-600', '✓', 'Hadir'], ['bg-orange-50 text-orange-600', 'T', 'Telat'], ['bg-purple-50 text-purple-600', 'B', 'Bolos'], ['bg-red-50 text-red-400', '✗', 'Alfa'], ['bg-yellow-50 text-yellow-600', 'I', 'Izin'], ['bg-blue-50 text-blue-600', 'S', 'Sakit'], ['bg-gray-100 text-gray-400', '—', 'Libur']] as [$cls, $icon, $label])
            <span
                class="flex items-center gap-1.5 text-xs text-gray-500 bg-white border border-gray-200 px-2.5 py-1 rounded-lg">
                <span
                    class="inline-flex items-center justify-center w-5 h-5 rounded-md {{ $cls }} font-bold text-[11px]">{{ $icon }}</span>
                {{ $label }}
            </span>
        @endforeach
    </div>

    {{-- Matrix Table --}}
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">

        @if ($matrix->isEmpty())
            <div class="py-16 text-center">
                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-500">Tidak ada data siswa</p>
                <p class="text-xs text-gray-400 mt-1">Coba ubah filter atau periode yang dipilih</p>
            </div>
        @else
            {{-- Scroll hint (mobile) --}}
            <div class="sm:hidden flex items-center gap-1.5 px-4 py-2 bg-blue-50 border-b border-blue-100">
                <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                </svg>
                <p class="text-xs text-blue-500 font-medium">Geser ke kanan untuk melihat semua tanggal</p>
            </div>

            <div class="overflow-x-auto" id="tableScroll">
                <table class="text-xs border-collapse w-full" style="min-width: max-content;">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">

                            {{-- Nama sticky --}}
                            <th class="sticky left-0 z-20 bg-gray-50 px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide border-r border-gray-200"
                                style="min-width:170px">
                                Nama
                            </th>

                            {{-- Kelas --}}
                            <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide border-r border-gray-100 whitespace-nowrap"
                                style="min-width:90px">
                                Kelas
                            </th>

                            {{-- Kolom tanggal --}}
                            @foreach ($tanggals as $tgl)
                                <th class="py-2 text-center font-semibold border-r border-gray-100 select-none"
                                    style="width:36px; min-width:36px;" title="{{ $tgl->translatedFormat('l, d M Y') }}">
                                    <div
                                        class="{{ $tgl->isWeekend() ? 'text-gray-400' : 'text-gray-600' }} font-bold text-xs leading-tight">
                                        {{ $tgl->format('d') }}
                                    </div>
                                    <div
                                        class="text-[9px] font-normal {{ $tgl->isWeekend() ? 'text-gray-300' : 'text-gray-400' }} uppercase">
                                        {{ $tgl->translatedFormat('D') }}
                                    </div>
                                    @if ($tgl->isWeekend())
                                        <div class="w-full h-0.5 bg-gray-200 mt-1 rounded"></div>
                                    @else
                                        <div class="w-full h-0.5 bg-blue-200 mt-1 rounded"></div>
                                    @endif
                                </th>
                            @endforeach

                            {{-- Summary cols --}}
                            <th class="px-2 py-3 text-center text-xs font-bold text-emerald-600 uppercase border-l-2 border-gray-200 bg-emerald-50/50 whitespace-nowrap"
                                style="min-width:34px">H</th>
                            <th class="px-2 py-3 text-center text-xs font-bold text-yellow-500 uppercase bg-yellow-50/50 whitespace-nowrap"
                                style="min-width:34px">I</th>
                            <th class="px-2 py-3 text-center text-xs font-bold text-blue-500 uppercase bg-blue-50/50 whitespace-nowrap"
                                style="min-width:34px">S</th>
                            <th class="px-2 py-3 text-center text-xs font-bold text-red-500 uppercase bg-red-50/50 whitespace-nowrap"
                                style="min-width:34px">A</th>
                            <th class="px-2 py-3 text-center text-xs font-bold text-orange-600 uppercase bg-orange-50/50 whitespace-nowrap"
                                style="min-width:34px">T</th>
                            <th class="px-2 py-3 text-center text-xs font-bold text-purple-600 uppercase bg-purple-50/50 whitespace-nowrap"
                                style="min-width:34px">B</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($matrix->items() as $i => $row)
                            <tr
                                class="hover:bg-blue-50/20 transition-colors group {{ $i % 2 === 0 ? '' : 'bg-gray-50/40' }}">

                                {{-- Nama sticky --}}
                                <td class="sticky left-0 z-10 bg-inherit px-4 py-2.5 border-r border-gray-200"
                                    style="min-width:170px">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($row['name'], 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-800 truncate text-xs"
                                                style="max-width:120px">{{ $row['name'] }}</p>
                                            <p class="text-[10px] text-gray-400 num">{{ $row['nis'] }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kelas --}}
                                <td class="px-3 py-2.5 border-r border-gray-100 whitespace-nowrap">
                                    <span class="text-xs font-medium text-gray-600">{{ $row['kelas'] }}</span>
                                    <span class="text-[10px] text-gray-400 ml-1">{{ $row['jurusan'] }}</span>
                                </td>

                                {{-- Kolom per tanggal --}}
                                @foreach ($tanggals as $tgl)
                                    @php
                                        $dayData = $row['days'][$tgl->toDateString()] ?? null;
                                        $status = is_array($dayData) ? $dayData['keterangan'] : $dayData;
                                        $isTelat = is_array($dayData) && ($dayData['isTelat'] ?? false);
                                        $isBolos = is_array($dayData) && ($dayData['isBolos'] ?? false);
                                        $weekend = $tgl->isWeekend();

                                        // Prioritas: Bolos > Telat > Status normal
                                        if ($isBolos) {
                                            $icon = 'B';
                                            $cellCls = 'bg-purple-100 text-purple-600';
                                        } elseif ($isTelat) {
                                            $icon = 'T';
                                            $cellCls = 'bg-orange-100 text-orange-600';
                                        } elseif ($weekend && !$status) {
                                            $icon = '—';
                                            $cellCls = 'bg-gray-100 text-gray-300';
                                        } elseif (
                                            !$status &&
                                            $tgl->toDateString() > \Carbon\Carbon::today()->toDateString()
                                        ) {
                                            $icon = '';
                                            $cellCls = 'bg-transparent';
                                        } else {
                                            [$icon, $cellCls] = match ($status) {
                                                'hadir' => ['✓', 'bg-emerald-100 text-emerald-600'],
                                                'izin' => ['I', 'bg-yellow-100 text-yellow-600'],
                                                'sakit' => ['S', 'bg-blue-100 text-blue-600'],
                                                default => ['✗', 'bg-red-100 text-red-400'],
                                            };
                                        }
                                    @endphp
                                    <td class="py-2 text-center border-r border-gray-100 {{ $weekend && !$status ? 'bg-gray-50' : '' }}"
                                        style="width:36px; min-width:36px;">
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-md font-bold text-[11px] {{ $cellCls }}">
                                            {{ $icon }}
                                        </span>
                                    </td>
                                @endforeach

                                {{-- Summary --}}
                                <td
                                    class="px-2 py-2.5 text-center font-bold text-emerald-600 border-l-2 border-gray-200 bg-emerald-50/30 text-xs">
                                    {{ $row['hadir'] }}</td>
                                <td class="px-2 py-2.5 text-center font-semibold text-yellow-500 bg-yellow-50/30 text-xs">
                                    {{ $row['izin'] }}</td>
                                <td class="px-2 py-2.5 text-center font-semibold text-blue-500 bg-blue-50/30 text-xs">
                                    {{ $row['sakit'] }}</td>
                                <td class="px-2 py-2.5 text-center font-semibold text-red-400 bg-red-50/30 text-xs">
                                    {{ $row['alfa'] }}</td>
                                <td class="px-2 py-2.5 text-center font-semibold text-orange-600 bg-orange-50/30 text-xs">
                                    {{ $row['telat'] ?? 0 }}</td>
                                <td class="px-2 py-2.5 text-center font-semibold text-purple-600 bg-purple-50/30 text-xs">
                                    {{ $row['bolos'] ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $matrix->withQueryString()->links('pagination.rekap') }}
            </div>

        @endif
    </div>

@endsection

@push('scripts')
    <script>
        function setMode(mode) {
            const url = new URL(window.location.href);
            url.searchParams.set('mode', mode);
            url.searchParams.delete('minggu');
            url.searchParams.delete('bulan');
            window.location.href = url.toString();
        }

        // Week picker
        const weekPicker = document.getElementById('weekPicker');
        if (weekPicker) {
            weekPicker.addEventListener('change', function() {
                const [year, week] = this.value.split('-W');
                const jan4 = new Date(year, 0, 4);
                const dow = jan4.getDay() || 7;
                const mon1 = new Date(jan4);
                mon1.setDate(jan4.getDate() - dow + 1);
                const monday = new Date(mon1);
                monday.setDate(mon1.getDate() + (parseInt(week) - 1) * 7);
                const pad = n => String(n).padStart(2, '0');
                document.getElementById('mingguInput').value =
                    `${monday.getFullYear()}-${pad(monday.getMonth()+1)}-${pad(monday.getDate())}`;
                document.getElementById('filterForm').submit();
            });
        }

        // Search debounce
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            let timer;
            searchInput.addEventListener('input', () => {
                clearTimeout(timer);
                timer = setTimeout(() => searchInput.closest('form').submit(), 500);
            });
        }

        // Scroll shadow indicator
        const scroll = document.getElementById('tableScroll');
        if (scroll) {
            scroll.addEventListener('scroll', () => {
                const hint = document.querySelector('.sm\\:hidden.flex');
                if (hint) hint.style.opacity = scroll.scrollLeft > 10 ? '0' : '1';
            });
        }
    </script>
@endpush
