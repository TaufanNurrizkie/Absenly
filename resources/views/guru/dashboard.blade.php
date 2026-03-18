@extends('layouts.guruNav')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard')

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">

    <div class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-indigo-200 shadow-lg">
                <i class="fa-solid fa-users text-white text-lg"></i>
            </div>
            <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">Aktif</span>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-800 tracking-tight">{{ $totalSiswa }}</h3>
        <p class="text-xs text-gray-400 mt-1 font-medium">Total Siswa</p>
        <div class="mt-4 h-1.5 bg-indigo-50 rounded-full overflow-hidden">
            <div class="h-full bg-indigo-500 rounded-full w-full"></div>
        </div>
    </div>

    <div class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center shadow-green-200 shadow-lg">
                <i class="fa-solid fa-circle-check text-white text-lg"></i>
            </div>
            <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full">
                {{ $totalSiswa > 0 ? round(($sudahAbsen / $totalSiswa) * 100) : 0 }}%
            </span>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-800 tracking-tight">{{ $sudahAbsen }}</h3>
        <p class="text-xs text-gray-400 mt-1 font-medium">Hadir Hari Ini</p>
        <div class="mt-4 h-1.5 bg-green-50 rounded-full overflow-hidden">
            <div class="h-full bg-green-500 rounded-full transition-all duration-500"
                style="width:{{ $totalSiswa > 0 ? ($sudahAbsen / $totalSiswa) * 100 : 0 }}%"></div>
        </div>
    </div>

    <div class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-orange-200 shadow-lg">
                <i class="fa-solid fa-file-medical text-white text-lg"></i>
            </div>
            <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">
                {{ $totalSiswa > 0 ? round(($izinSakit / $totalSiswa) * 100) : 0 }}%
            </span>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-800 tracking-tight">{{ $izinSakit }}</h3>
        <p class="text-xs text-gray-400 mt-1 font-medium">Izin / Sakit</p>
        <div class="mt-4 h-1.5 bg-amber-50 rounded-full overflow-hidden">
            <div class="h-full bg-amber-400 rounded-full transition-all duration-500"
                style="width:{{ $totalSiswa > 0 ? ($izinSakit / $totalSiswa) * 100 : 0 }}%"></div>
        </div>
    </div>

    <div class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-400 to-rose-600 flex items-center justify-center shadow-red-200 shadow-lg">
                <i class="fa-solid fa-circle-xmark text-white text-lg"></i>
            </div>
        </div>
        <h3 class="text-3xl font-extrabold text-gray-800 tracking-tight">{{ $belumAbsen }}</h3>
        <p class="text-xs text-gray-400 mt-1 font-medium">Belum Absen</p>
        <div class="mt-4 h-1.5 bg-red-50 rounded-full overflow-hidden">
            <div class="h-full bg-red-500 rounded-full transition-all duration-500"
                style="width:{{ $totalSiswa > 0 ? ($belumAbsen / $totalSiswa) * 100 : 0 }}%"></div>
        </div>
    </div>
</div>

{{-- TABEL & LIST ABSENSI --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Header --}}
    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50/50">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-list text-indigo-500"></i>
                    Rekap Absensi
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    <i class="fa-regular fa-calendar mr-1"></i>
                    {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}
                </p>
            </div>

            {{-- Filter Tanggal --}}
            <form method="GET" action="{{ route('guru.dashboard') }}" class="flex items-center gap-2">
                <div class="relative">
                    <input type="date" name="tanggal" value="{{ $tanggal }}"
                        max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                        class="pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-400 text-gray-700 bg-white shadow-sm w-full md:w-auto">
                    <i class="fa-solid fa-calendar-days absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>
                <button type="submit"
                    class="px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-200">
                    <i class="fa-solid fa-filter mr-1 hidden sm:inline"></i> Filter
                </button>
                @if ($tanggal !== \Carbon\Carbon::today()->format('Y-m-d'))
                    <a href="{{ route('guru.dashboard') }}"
                        class="px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors font-medium">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Search & Filter Status --}}
        <div class="mt-6 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
            <div class="relative w-full sm:w-64">
                <input type="text" id="searchInput" placeholder="Cari nama siswa..."
                    class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-200 bg-white transition-shadow">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <div class="flex gap-2 overflow-x-auto pb-1 w-full sm:w-auto">
                @php
                    $filters = [
                        'semua'     => ['label' => 'Semua',    'active' => !request('filter_status')],
                        'hadir'     => ['label' => 'Hadir',    'active' => request('filter_status') === 'hadir'],
                        'izin'      => ['label' => 'Izin',     'active' => request('filter_status') === 'izin'],
                        'sakit'     => ['label' => 'Sakit',    'active' => request('filter_status') === 'sakit'],
                        'pending'   => ['label' => 'Pending',  'active' => request('filter_status') === 'pending'],
                        'ditolak'   => ['label' => 'Ditolak',  'active' => request('filter_status') === 'ditolak'],
                    ];
                @endphp
                @foreach ($filters as $value => $filter)
                    <a href="{{ route('guru.dashboard', array_merge(request()->only('tanggal'), $value !== 'semua' ? ['filter_status' => $value] : [])) }}"
                        class="px-4 py-1.5 text-xs font-semibold rounded-full border transition-colors whitespace-nowrap
                            {{ $filter['active'] ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                        {{ $filter['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-100">
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Jam</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-28">Status</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($absensiHariIni as $index => $absen)
                    @php
                        $status     = $absen->status ?? 'pending';
                        $keterangan = $absen->keterangan ?? 'hadir';

                        [$badgeClass, $dotClass, $displayLabel] = match(true) {
                            $status === 'disetujui' && $keterangan === 'hadir'  => ['bg-green-100 text-green-700',  'bg-green-500',  'Hadir'],
                            $status === 'disetujui' && $keterangan === 'izin'   => ['bg-amber-100 text-amber-700',  'bg-amber-500',  'Izin'],
                            $status === 'disetujui' && $keterangan === 'sakit'  => ['bg-orange-100 text-orange-700','bg-orange-500', 'Sakit'],
                            $status === 'pending'                               => ['bg-yellow-100 text-yellow-700','bg-yellow-500', 'Pending'],
                            $status === 'ditolak'                               => ['bg-red-100 text-red-700',      'bg-red-500',    'Ditolak'],
                            default                                             => ['bg-gray-100 text-gray-700',    'bg-gray-400',   ucfirst($status)],
                        };
                    @endphp
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-150 searchable-row"
                        data-nama="{{ strtolower($absen->user->name ?? '') }}">
                        <td class="px-6 py-4 text-gray-400 font-mono text-xs font-bold">
                            {{ ($absensiHariIni->currentPage() - 1) * $absensiHariIni->perPage() + $index + 1 }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if (!empty($absen->foto))
                                    <img src="{{ asset('storage/' . $absen->foto) }}" alt="Foto"
                                        class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-100">
                                @else
                                    <div
                                        class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold bg-gradient-to-br from-indigo-400 to-purple-500 text-white shadow-inner">
                                        {{ strtoupper(substr($absen->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $absen->user->name ?? '-' }}</p>
                                    <p class="text-[11px] text-gray-400 font-mono">{{ $absen->user->nis ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-md border border-slate-200">
                                {{ $absen->user->kelas ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 font-mono text-xs tracking-wide">
                            {{ $absen->waktu ? \Carbon\Carbon::parse($absen->waktu)->format('H:i') : '—' }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs max-w-xs truncate italic">
                            {{ ucfirst($keterangan) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                {{ $displayLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="openModal({{ $absen->id }})"
                                class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-white hover:bg-indigo-500 font-semibold px-3 py-1.5 rounded-lg border border-indigo-200 hover:border-indigo-500 transition-all">
                                <i class="fa-solid fa-eye text-[10px]"></i> Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-2">
                                    <i class="fa-solid fa-inbox text-gray-300 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 font-semibold">Tidak Ada Data</p>
                                <p class="text-gray-400 text-xs">Tidak ada rekaman absensi untuk tanggal ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARD VIEW --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse ($absensiHariIni as $absen)
            @php
                $status     = $absen->status ?? 'pending';
                $keterangan = $absen->keterangan ?? 'hadir';

                [$badgeClass, $dotClass, $displayLabel] = match(true) {
                    $status === 'disetujui' && $keterangan === 'hadir'  => ['bg-green-100 text-green-700',  'bg-green-500',  'Hadir'],
                    $status === 'disetujui' && $keterangan === 'izin'   => ['bg-amber-100 text-amber-700',  'bg-amber-500',  'Izin'],
                    $status === 'disetujui' && $keterangan === 'sakit'  => ['bg-orange-100 text-orange-700','bg-orange-500', 'Sakit'],
                    $status === 'pending'                               => ['bg-yellow-100 text-yellow-700','bg-yellow-500', 'Pending'],
                    $status === 'ditolak'                               => ['bg-red-100 text-red-700',      'bg-red-500',    'Ditolak'],
                    default                                             => ['bg-gray-100 text-gray-700',    'bg-gray-400',   ucfirst($status)],
                };
            @endphp
            <div class="searchable-row p-4 hover:bg-gray-50 transition-colors"
                 data-nama="{{ strtolower($absen->user->name ?? '') }}">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        @if (!empty($absen->foto))
                            <img src="{{ asset('storage/' . $absen->foto) }}" alt="Foto"
                                class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div
                                class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold bg-gradient-to-br from-indigo-400 to-purple-500 text-white">
                                {{ strtoupper(substr($absen->user->name ?? 'S', 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-gray-800 text-sm">{{ $absen->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $absen->user->nis ?? '-' }} &bull;
                                {{ $absen->user->kelas ?? '-' }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $badgeClass }}">
                        {{ $displayLabel }}
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs bg-gray-50 rounded-lg p-2 border border-gray-100">
                    <div class="text-gray-500">
                        <i class="fa-regular fa-clock mr-1"></i>
                        {{ $absen->waktu ? \Carbon\Carbon::parse($absen->waktu)->format('H:i') : '—' }}
                    </div>
                    <button onclick="openModal({{ $absen->id }})" class="font-semibold text-indigo-600 hover:underline text-xs">
                        Lihat Detail <i class="fa-solid fa-chevron-right text-[8px] ml-1"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-gray-500">
                <i class="fa-solid fa-folder-open text-4xl text-gray-200 mb-2"></i>
                <p class="text-sm">Tidak ada data</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if (isset($absensiHariIni) && method_exists($absensiHariIni, 'links'))
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $absensiHariIni->appends(request()->query())->links() }}
        </div>
    @endif
</div>


{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{--  DATA ABSENSI untuk modal (disimpan sebagai JSON di data attribute)   --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
@php
    $absensiData = [];
    foreach ($absensiHariIni as $absen) {
        $st  = $absen->status ?? 'pending';
        $ket = $absen->keterangan ?? 'hadir';

        [$badgeClass, $displayLabel, $leftGradient, $iconClass] = match(true) {
            $st === 'disetujui' && $ket === 'hadir' => ['bg-green-100 text-green-700 border border-green-200',  'Hadir',   'from-emerald-500 via-green-600 to-teal-700',    'fa-circle-check text-green-500'],
            $st === 'disetujui' && $ket === 'izin'  => ['bg-amber-100 text-amber-700 border border-amber-200',  'Izin',    'from-amber-400 via-amber-500 to-orange-600',    'fa-file-lines text-amber-500'],
            $st === 'disetujui' && $ket === 'sakit' => ['bg-orange-100 text-orange-700 border border-orange-200','Sakit',  'from-orange-400 via-orange-500 to-red-500',     'fa-file-medical text-orange-500'],
            $st === 'pending'                       => ['bg-yellow-100 text-yellow-700 border border-yellow-200','Pending', 'from-yellow-400 via-amber-500 to-orange-500',   'fa-hourglass-half text-yellow-500'],
            $st === 'ditolak'                       => ['bg-red-100 text-red-700 border border-red-200',         'Ditolak', 'from-red-400 via-red-500 to-rose-700',          'fa-circle-xmark text-red-500'],
            default                                 => ['bg-gray-100 text-gray-700 border border-gray-200',     ucfirst($st), 'from-gray-400 via-gray-500 to-gray-600',  'fa-circle-question text-gray-500'],
        };

        $absensiData[$absen->id] = [
            'nama'         => $absen->user->name ?? '-',
            'nis'          => $absen->user->nis ?? '-',
            'kelas'        => $absen->user->kelas ?? '-',
            'jurusan'      => $absen->user->jurusan ?? '-',
            'jam'          => $absen->waktu ? \Carbon\Carbon::parse($absen->waktu)->format('H:i') : '—',
            'tanggal'      => \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y'),
            'keterangan'   => $absen->keterangan ? ucfirst($absen->keterangan) : 'Tidak ada keterangan',
            'alasan'       => $absen->alasan ?? '',
            'lokasi_valid' => $absen->lokasi_valid ? 'Ya' : 'Tidak',
            'foto'         => !empty($absen->foto) ? asset('storage/' . $absen->foto) : '',
            'inisial'      => strtoupper(substr($absen->user->name ?? 'S', 0, 1)),
            'status'       => $st,
            'displayLabel' => $displayLabel,
            'badgeClass'   => $badgeClass,
            'leftGradient' => $leftGradient,
            'iconClass'    => $iconClass,
            'isPending'    => $st === 'pending',
            'approveUrl'   => route('guru.absensi.approve', $absen->id),
            'rejectUrl'    => route('guru.absensi.reject', $absen->id),
            'csrfToken'    => csrf_token(),
            'tanggalVal'   => $tanggal,
        ];
    }
@endphp

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{--  SATU MODAL — konten diisi via JS                                     --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div id="detailModal" class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center sm:p-4">

    {{-- Backdrop --}}
    <div onclick="closeModal()" class="absolute inset-0 bg-black/60 backdrop-blur-sm cursor-pointer"></div>

    {{-- Modal Card --}}
    <div class="relative bg-white w-full sm:max-w-2xl rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden modal-card">

        {{-- Drag handle mobile --}}
        <div class="sm:hidden flex justify-center pt-3 pb-0">
            <div class="w-10 h-1 rounded-full bg-gray-200"></div>
        </div>

        {{-- Tombol Close --}}
        <button onclick="closeModal()"
            class="absolute top-5 right-4 z-20 w-8 h-8 rounded-full bg-white/90 flex items-center justify-center text-gray-500 hover:text-gray-800 hover:bg-white shadow-md transition-all hover:scale-110">
            <span class="text-sm font-bold">X</span>
        </button>

        <div class="flex flex-col md:flex-row">

            {{-- PANEL KIRI — desktop: kolom | mobile: strip horizontal --}}
            <div id="mLeftPanel" class="relative bg-gradient-to-br overflow-hidden
                flex flex-row items-center gap-4 px-5 py-4
                md:w-2/5 md:flex-col md:items-center md:justify-center md:p-8 md:gap-5 md:min-h-[300px]">
                <div class="absolute -top-8 -left-8 w-32 h-32 rounded-full bg-white/10 pointer-events-none"></div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 rounded-full bg-white/10 pointer-events-none"></div>

                {{-- Foto / Inisial --}}
                <div class="relative z-10 flex-shrink-0
                    w-14 h-14 md:w-36 md:h-36
                    rounded-xl md:rounded-2xl overflow-hidden ring-2 md:ring-4 ring-white/30 shadow-xl cursor-zoom-in group/foto"
                    onclick="openFotoFullscreen()">
                    <img id="mFoto" src="" alt="Foto"
                        class="w-full h-full object-cover hidden transition-transform duration-300 group-hover/foto:scale-105">
                    <div id="mInisialBox"
                        class="w-full h-full flex items-center justify-center text-xl md:text-5xl font-extrabold text-white bg-white/20 select-none">
                        <span id="mInisial"></span>
                    </div>
                    <div id="mFotoOverlay"
                        class="absolute inset-0 bg-black/30 hidden items-center justify-center rounded-xl md:rounded-2xl opacity-0 group-hover/foto:opacity-100 transition-opacity duration-200">
                        <i class="fa-solid fa-magnifying-glass-plus text-white text-sm md:text-xl"></i>
                    </div>
                </div>

                {{-- Info: Nama + NIS + Badge --}}
                <div class="relative z-10 flex-1 min-w-0 md:text-center">
                    <p id="mNama" class="text-white font-extrabold text-sm md:text-lg leading-snug drop-shadow truncate"></p>
                    <p id="mNis" class="text-white/60 text-[10px] md:text-xs font-mono mt-0.5 tracking-wider"></p>
                    <span id="mStatusBadgeLeft"
                        class="mt-1.5 inline-flex px-2.5 py-1 rounded-full text-[10px] md:text-xs font-bold bg-white/20 text-white border border-white/30 tracking-wide"></span>
                </div>
            </div>

            {{-- PANEL KANAN --}}
            <div class="md:w-3/5 p-5 md:p-8 flex flex-col gap-3 md:gap-4 overflow-y-auto" style="max-height: min(72dvh, 500px);">

                <div class="border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-extrabold text-gray-800 tracking-tight">Detail Absensi</h3>
                    <p class="text-xs text-gray-400 mt-1 flex items-center gap-1.5">
                        <i class="fa-regular fa-calendar text-indigo-400"></i>
                        <span id="mTanggal"></span>
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2 flex items-center gap-1">
                            <i class="fa-solid fa-school text-slate-400"></i> Kelas
                        </p>
                        <p id="mKelas" class="text-sm font-extrabold text-gray-800"></p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2 flex items-center gap-1">
                            <i class="fa-regular fa-clock text-slate-400"></i> Jam Absen
                        </p>
                        <p id="mJam" class="text-sm font-extrabold text-gray-800 font-mono tracking-widest"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2 flex items-center gap-1">
                            <i class="fa-solid fa-graduation-cap text-slate-400"></i> Jurusan
                        </p>
                        <p id="mJurusan" class="text-sm font-extrabold text-gray-800"></p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2 flex items-center gap-1">
                            <i class="fa-solid fa-map-pin text-slate-400"></i> Lokasi Valid
                        </p>
                        <p id="mLokasi" class="text-sm font-extrabold text-gray-800"></p>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-3 flex items-center gap-1">
                        <i class="fa-solid fa-circle-dot text-slate-400"></i> Status Kehadiran
                    </p>
                    <span id="mStatusBadge" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold"></span>
                </div>

                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-3 flex items-center gap-1">
                        <i class="fa-solid fa-note-sticky text-slate-400"></i> Keterangan
                    </p>
                    <span id="mKeteranganBadge" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold"></span>
                </div>

                <div id="mAlasanBox" class="bg-slate-50 rounded-xl p-4 border border-slate-100 hidden">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2 flex items-center gap-1">
                        <i class="fa-solid fa-comment text-slate-400"></i> Alasan
                    </p>
                    <p id="mAlasan" class="text-sm text-gray-600 leading-relaxed"></p>
                </div>

                {{-- Tombol Pending --}}
                <div id="mPendingBox" class="hidden bg-yellow-50 border border-yellow-200 rounded-2xl p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-hourglass-half text-yellow-600 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-yellow-800">Menunggu Persetujuan</p>
                            <p class="text-[10px] text-yellow-600">Konfirmasi kehadiran siswa ini</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <form id="mFormSetujui" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="tanggal" id="mTanggalSetujui">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-2.5 bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-emerald-200">
                                <i class="fa-solid fa-circle-check"></i> Setujui
                            </button>
                        </form>
                        <form id="mFormTolak" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="tanggal" id="mTanggalTolak">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-2.5 bg-red-500 hover:bg-red-600 active:scale-95 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-red-200">
                                <i class="fa-solid fa-circle-xmark"></i> Tolak
                            </button>
                        </form>
                    </div>
                </div>

                <button onclick="closeModal()"
                    class="w-full py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-all font-semibold">
                    Tutup
                </button>

            </div>
        </div>
    </div>
</div>

@endsection


@push('styles')
<style>
/* Desktop: scale-in */
@media (min-width: 640px) {
    .modal-card {
        animation: modalInDesktop 0.25s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    @keyframes modalInDesktop {
        from { opacity: 0; transform: scale(0.93) translateY(16px); }
        to   { opacity: 1; transform: scale(1)   translateY(0);     }
    }
}
/* Mobile: slide-up dari bawah */
@media (max-width: 639px) {
    .modal-card {
        animation: modalInMobile 0.3s cubic-bezier(0.32, 0.72, 0, 1) forwards;
    }
    @keyframes modalInMobile {
        from { opacity: 0; transform: translateY(100%); }
        to   { opacity: 1; transform: translateY(0);    }
    }
}
/* Lightbox foto fullscreen */
#fotoLightbox {
    animation: fadeIn 0.2s ease forwards;
}
#fotoLightbox img {
    animation: zoomIn 0.25s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
@keyframes zoomIn {
    from { opacity: 0; transform: scale(0.85); }
    to   { opacity: 1; transform: scale(1); }
}
</style>
@endpush


@push('scripts')
<script>
const absensiData = @json($absensiData);

// Konfigurasi badge keterangan
const ketConfig = {
    'hadir': { cls: 'bg-green-100 text-green-700 border border-green-200',  icon: 'fa-circle-check',   label: 'Hadir'  },
    'izin':  { cls: 'bg-amber-100 text-amber-700 border border-amber-200',  icon: 'fa-file-lines',     label: 'Izin'   },
    'sakit': { cls: 'bg-orange-100 text-orange-700 border border-orange-200',icon: 'fa-file-medical',  label: 'Sakit'  },
};

function openModal(id) {
    const d = absensiData[id];
    if (!d) return;

    // Panel kiri — gradient
    const left = document.getElementById('mLeftPanel');
    left.className = left.className.replace(/from-\S+\s+via-\S+\s+to-\S+/g, '');
    left.classList.add(...d.leftGradient.split(' '));

    // Foto / inisial
    const foto = document.getElementById('mFoto');
    const inisialBox = document.getElementById('mInisialBox');
    const fotoOverlay = document.getElementById('mFotoOverlay');
    if (d.foto) {
        foto.src = d.foto;
        foto.classList.remove('hidden');
        inisialBox.classList.add('hidden');
        // Tampilkan overlay zoom
        fotoOverlay.classList.remove('hidden');
        fotoOverlay.classList.add('flex');
    } else {
        foto.classList.add('hidden');
        inisialBox.classList.remove('hidden');
        fotoOverlay.classList.add('hidden');
        fotoOverlay.classList.remove('flex');
        document.getElementById('mInisial').textContent = d.inisial;
    }

    // Teks
    document.getElementById('mNama').textContent            = d.nama;
    document.getElementById('mNis').textContent             = d.nis;
    document.getElementById('mTanggal').textContent         = d.tanggal;
    document.getElementById('mKelas').textContent           = d.kelas;
    document.getElementById('mJam').textContent             = d.jam;
    document.getElementById('mJurusan').textContent         = d.jurusan;
    document.getElementById('mLokasi').textContent          = d.lokasi_valid;
    document.getElementById('mStatusBadgeLeft').textContent = d.displayLabel;

    // Badge status kehadiran
    const badge = document.getElementById('mStatusBadge');
    badge.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold ' + d.badgeClass;
    badge.innerHTML = `<i class="fa-solid ${d.iconClass}"></i> ${d.displayLabel}`;

    // Badge keterangan
    const ketKey = (d.keterangan || '').toLowerCase().replace('tidak ada keterangan', 'hadir');
    const ket = ketConfig[ketKey] || { cls: 'bg-slate-100 text-slate-700 border border-slate-200', icon: 'fa-tag', label: d.keterangan };
    const ketBadge = document.getElementById('mKeteranganBadge');
    ketBadge.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold ' + ket.cls;
    ketBadge.innerHTML = `<i class="fa-solid ${ket.icon}"></i> ${ket.label}`;

    // Alasan
    const alasanBox = document.getElementById('mAlasanBox');
    if (d.alasan) {
        document.getElementById('mAlasan').textContent = d.alasan;
        alasanBox.classList.remove('hidden');
    } else {
        alasanBox.classList.add('hidden');
    }

    // Pending
    const pendingBox = document.getElementById('mPendingBox');
    if (d.isPending) {
        document.getElementById('mFormSetujui').action   = d.approveUrl;
        document.getElementById('mTanggalSetujui').value = d.tanggalVal;
        document.getElementById('mFormTolak').action     = d.rejectUrl;
        document.getElementById('mTanggalTolak').value   = d.tanggalVal;
        pendingBox.classList.remove('hidden');
    } else {
        pendingBox.classList.add('hidden');
    }

    // Simpan url foto untuk lightbox
    document.getElementById('detailModal').dataset.fotoUrl = d.foto || '';

    // Tampilkan modal
    document.getElementById('detailModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// ── LIGHTBOX FOTO FULLSCREEN ──────────────────────────────────────────────
function openFotoFullscreen() {
    const fotoUrl = document.getElementById('detailModal').dataset.fotoUrl;
    if (!fotoUrl) return;

    // Buat lightbox
    const lb = document.createElement('div');
    lb.id = 'fotoLightbox';
    lb.className = 'fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 cursor-zoom-out p-4';
    lb.innerHTML = `
        <button onclick="document.getElementById('fotoLightbox').remove(); document.body.style.overflow='hidden';"
            class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <img src="${fotoUrl}" alt="Foto Siswa"
            class="max-w-full max-h-full rounded-2xl shadow-2xl object-contain"
            style="max-height: 90vh;">
    `;
    lb.addEventListener('click', function(e) {
        if (e.target === lb) {
            lb.remove();
            document.body.style.overflow = 'hidden'; // modal masih terbuka
        }
    });
    document.body.appendChild(lb);
}

// Tutup dengan ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        const lb = document.getElementById('fotoLightbox');
        if (lb) {
            lb.remove();
        } else {
            closeModal();
        }
    }
});

// Pencarian nama
document.getElementById('searchInput').addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('.searchable-row').forEach(row => {
        row.style.display = (!q || row.dataset.nama.includes(q)) ? '' : 'none';
    });
});
</script>
@endpush