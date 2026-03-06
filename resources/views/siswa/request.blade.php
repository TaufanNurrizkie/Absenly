@extends('layouts.siswaNav')

@section('content')

<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<!-- Header Section -->
<div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-b-[2.5rem] p-6 pt-8 pb-12 relative overflow-hidden shadow-lg">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 blur-2xl"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-500/20 rounded-full -ml-24 -mb-24 blur-xl"></div>

    <div class="relative z-10 max-w-lg mx-auto text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl mb-4 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight">Riwayat Permintaan</h1>
        <p class="text-sm text-blue-100 mt-1 opacity-90">Pantau status izin dan cuti kamu di sini.</p>
    </div>
</div>

<!-- Main Content -->
<div class="px-4 -mt-6 relative z-20 space-y-4 pb-8">

    <!-- Quick Stats & Action Button -->
    <div class="max-w-lg mx-auto w-full">
        <div class="grid grid-cols-3 gap-3 mb-4">
            <!-- Pending Stat -->
            <div class="bg-white rounded-xl p-3 shadow-sm border border-slate-100 text-center">
                <p class="text-xl font-bold text-amber-500">{{ $requests->where('status', 'pending')->count() }}</p>
                <p class="text-[10px] text-slate-500 font-medium uppercase">Pending</p>
            </div>
            <!-- Approved Stat -->
            <div class="bg-white rounded-xl p-3 shadow-sm border border-slate-100 text-center">
                <p class="text-xl font-bold text-emerald-500">{{ $requests->where('status', 'approved')->count() }}</p>
                <p class="text-[10px] text-slate-500 font-medium uppercase">Disetujui</p>
            </div>
            <!-- Rejected Stat -->
            <div class="bg-white rounded-xl p-3 shadow-sm border border-slate-100 text-center">
                <p class="text-xl font-bold text-red-500">{{ $requests->where('status', 'rejected')->count() }}</p>
                <p class="text-[10px] text-slate-500 font-medium uppercase">Ditolak</p>
            </div>
        </div>

        <!-- Add New Request Button -->
        <a href="{{ route('siswa.dashboard') }}" 
           class="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow-md transition-colors mb-6 active:scale-[0.98]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Ajukan Permintaan Baru
        </a>
    </div>

    <!-- Filter & List Container -->
    <div class="max-w-lg mx-auto w-full">
        
        <!-- Filter Section -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-bold text-slate-700">Daftar Permintaan</h2>
            <form method="GET" action="{{ route('siswa.request') }}">
                <select name="status" onchange="this.form.submit()"
                    class="border border-slate-200 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-slate-600 cursor-pointer">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </form>
        </div>

        <!-- Request List -->
        <div class="space-y-3">
            @forelse($requests as $req)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-all group">

                <!-- Card Header -->
                <div class="p-4 pb-0 flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <!-- Icon based on type -->
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $req->keterangan === 'izin' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600' }}">
                            @if($req->keterangan === 'izin')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800 text-sm capitalize">
                                {{ $req->keterangan === 'izin' ? 'Izin' : 'Sakit' }}
                            </h3>
                            <p class="text-xs text-slate-400">{{ $req->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full uppercase tracking-wide
                        @if($req->status === 'Approved')
                            bg-emerald-50 text-emerald-600 ring-1 ring-inset ring-emerald-200
                        @elseif($req->status === 'Rejected')
                            bg-red-50 text-red-600 ring-1 ring-inset ring-red-200
                        @else
                            bg-amber-50 text-amber-600 ring-1 ring-inset ring-amber-200
                        @endif">
                        {{ $req->status }}
                    </span>
                </div>

                <!-- Card Body -->
                <div class="px-4 pb-4 mt-3">
                    <p class="text-sm text-slate-600 line-clamp-3 leading-relaxed whitespace-pre-wrap">
                        {!! nl2br(e($req->alasan)) !!}
                    </p>
                </div>

                <!-- Card Footer (If image exists) -->
                @if($req->keterangan === 'sakit' && $req->foto)
                <div class="px-4 pb-4 pt-2 border-t border-slate-50 mt-2 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Lampiran tersedia</span>
                    </div>
                    <button onclick="openImageModal('{{ asset('storage/' . $req->foto) }}')" 
                            class="text-xs font-semibold text-blue-600 hover:underline">
                        Lihat Foto
                    </button>
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-slate-500 font-medium text-sm">Belum ada riwayat permintaan.</p>
                <p class="text-slate-400 text-xs mt-1">Permintaan izin atau sakit yang kamu ajukan akan muncul di sini.</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="imageModalContent" onclick="event.stopPropagation()">
        <div class="p-2">
            <img id="modalImageSrc" src="" class="w-full rounded-xl object-cover">
        </div>
        <button onclick="closeImageModal()" class="absolute top-4 right-4 bg-white/80 hover:bg-white p-1.5 rounded-full shadow-md transition-colors">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<script>
    function openImageModal(src) {
        const modal = document.getElementById('imageModal');
        const content = document.getElementById('imageModalContent');
        const img = document.getElementById('modalImageSrc');
        
        img.src = src;
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        const content = document.getElementById('imageModalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>

@endsection