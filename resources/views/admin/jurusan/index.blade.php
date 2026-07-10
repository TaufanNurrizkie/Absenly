@extends('layouts.adminNav')

@section('title', 'Kelola Jurusan')
@section('page-title', 'Kelola Jurusan')

@section('content')

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Data Jurusan</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola data jurusan yang tersedia di sekolah.</p>
            </div>
            <button onclick="openCreateModal()"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-sm hover:shadow-md transition-all duration-200 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Jurusan
            </button>
        </div>
    </div>

    {{-- Success Alert --}}
    @if (session('success'))
        <div
            class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-700 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center border border-blue-100">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0121 15.803M12 14L5.84 10.578A12.083 12.083 0 003 15.803M12 14v7m-6-3.803A11.955 11.955 0 0112 21c2.176 0 4.208-.576 5.965-1.585A11.955 11.955 0 0018 17.197" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ $jurusanList->count() }}</p>
                <p class="text-xs text-slate-500 font-medium">Total Jurusan</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center border border-emerald-100">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ $jurusanList->sum('users_count') }}</p>
                <p class="text-xs text-slate-500 font-medium">Total Siswa</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    @if ($jurusanList->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                No</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Nama Jurusan</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Jumlah Siswa</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Dibuat</th>
                            <th
                                class="text-right px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($jurusanList as $index => $jurusan)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-500 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-slate-800">{{ $jurusan->nama }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                        </svg>
                                        {{ $jurusan->users_count }} siswa
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $jurusan->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button
                                            onclick="openEditModal({{ $jurusan->id }}, '{{ addslashes($jurusan->nama) }}')"
                                            class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('admin.jurusan.destroy', $jurusan) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus jurusan {{ $jurusan->nama }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
            <div
                class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0121 15.803M12 14L5.84 10.578A12.083 12.083 0 003 15.803M12 14v7m-6-3.803A11.955 11.955 0 0112 21c2.176 0 4.208-.576 5.965-1.585A11.955 11.955 0 0018 17.197" />
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-700 mb-1">Belum Ada Data Jurusan</h3>
            <p class="text-sm text-slate-400 mb-4">Mulai tambahkan jurusan pertama untuk sekolah Anda.</p>
            <button onclick="openCreateModal()"
                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Jurusan Baru
            </button>
        </div>
    @endif

    {{-- Modal Create/Edit --}}
    <div id="jurusanModal"
        class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl transform scale-95 opacity-0 transition-all duration-300"
            id="modalContent">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h3 id="modalTitle" class="text-lg font-bold text-slate-800">Tambah Jurusan Baru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Masukkan nama jurusan</p>
                </div>
                <button onclick="closeModal()"
                    class="w-9 h-9 bg-white hover:bg-slate-50 rounded-full flex items-center justify-center transition-colors border border-slate-200 text-slate-500 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <form id="jurusanForm" method="POST">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="POST">

                <div class="p-6">
                    <label for="nama" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Nama Jurusan <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="nama" id="nama"
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm bg-slate-50 focus:bg-white"
                        placeholder="Contoh: RPL, TKJ, Multimedia" required>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()"
                        class="px-5 py-2.5 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-white transition-colors text-sm bg-white shadow-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-colors text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span id="submitButtonText">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Jurusan Baru';
            document.getElementById('submitButtonText').textContent = 'Simpan';
            document.getElementById('jurusanForm').action = "{{ route('admin.jurusan.store') }}";
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('nama').value = '';

            showModal();
        }

        function openEditModal(id, nama) {
            document.getElementById('modalTitle').textContent = 'Edit Jurusan';
            document.getElementById('submitButtonText').textContent = 'Update';
            document.getElementById('jurusanForm').action = "/admin/jurusan/" + id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('nama').value = nama;

            showModal();
        }

        function showModal() {
            const modal = document.getElementById('jurusanModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('jurusanModal');
            const content = document.getElementById('modalContent');

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        // Close on backdrop click
        document.getElementById('jurusanModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('jurusanModal');
                if (!modal.classList.contains('hidden')) closeModal();
            }
        });
    </script>
@endpush
