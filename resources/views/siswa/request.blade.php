@extends('layouts.siswaNav')

@section('content')
<div class="p-6 bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-4xl mx-auto">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-indigo-700 mb-4 sm:mb-0">Permintaan Izin / Sakit</h2>
            
            <!-- Filter Status -->
            <form method="GET" action="{{ route('siswa.request') }}">
                <select name="status" onchange="this.form.submit()"
                    class="border border-indigo-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>pending</option>
                    <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </form>
        </div>

        @forelse($requests as $req)
        <div class="overflow-hidden leading-relaxed whitespace-normal break-words border border-gray-200 rounded-lg p-4 mb-4 bg-white shadow-sm hover:shadow-md transition ">
            <div class="flex justify-between items-center ">
                <div class="">
                    <h3 class="font-semibold text-lg text-gray-800 capitalize">{{ $req->keterangan === 'izin' ? 'Izin Tidak Masuk' : 'Izin Sakit' }}</h3>
                    @if($req->keterangan === 'sakit' && $req->foto)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $req->foto) }}" alt="Foto Bukti Sakit" class="w-32 h-32 object-cover rounded-lg shadow-md">
                        </div>
                    @endif

                    <p class="text-sm text-gray-600">{{ $req->nama }}</p>
                    <p class="text-sm text-gray-600 break-words whitespace-pre-wrap">pesan: {!! nl2br(e($req->alasan)) !!}</p>
                    <p class="text-sm text-gray-500">Tanggal: {{ \Carbon\Carbon::parse($req->tanggal)->translatedFormat('d M Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($req->created_at)->translatedFormat('d M') }}</p>
                    <span class="inline-block mt-1 px-3 py-1 text-sm font-semibold rounded-full
                        @if($req->status === 'Approved')
                            bg-green-100 text-green-700
                        @elseif($req->status === 'Rejected')
                            bg-red-100 text-red-600
                        @else
                            bg-yellow-100 text-yellow-600
                        @endif">
                        {{ $req->status }}
                    </span>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center text-gray-500 py-10">
            <p>Belum ada permintaan izin atau sakit.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
