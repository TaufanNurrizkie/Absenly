@extends('layouts.adminNav')

@section('title', 'Kelola Berita')
@section('page-title', 'Kelola Berita')

@section('content')

{{-- Header --}}
<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Berita</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola informasi dan pengumuman sekolah di sini.</p>
        </div>
        <button onclick="openCreateModal()" 
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-sm hover:shadow-md transition-all duration-200 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Berita
        </button>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center border border-blue-100">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ $beritas->total() }}</p>
            <p class="text-xs text-slate-500 font-medium">Total Berita</p>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center border border-emerald-100">
            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m16 4V3m-1 16H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ $beritas->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
            <p class="text-xs text-slate-500 font-medium">Bulan Ini</p>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center border border-indigo-100">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800 tabular-nums">{{ $beritas->whereNotNull('gambar')->count() }}</p>
            <p class="text-xs text-slate-500 font-medium">Dengan Gambar</p>
        </div>
    </div>
</div>

{{-- News Grid --}}
@if($beritas->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    @foreach($beritas as $berita)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md hover:border-slate-200 transition-all duration-300 flex flex-col group">
        
        {{-- Image Section --}}
        <div class="relative aspect-video bg-slate-100 overflow-hidden">
            @if($berita->gambar)
                <img src="{{ asset('img/' . $berita->gambar) }}" 
                     alt="{{ $berita->judul }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-50">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
            
            {{-- Date Badge --}}
            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-lg shadow-sm border border-slate-100">
                <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">{{ $berita->created_at->format('d M') }}</p>
            </div>
        </div>

        {{-- Content Section --}}
        <div class="p-5 flex-1 flex flex-col">
            <h3 class="text-base font-bold text-slate-800 mb-2 line-clamp-2 leading-snug">
                {{ Str::limit($berita->judul, 60) }}
            </h3>
            <p class="text-xs text-slate-500 mb-4 line-clamp-2 flex-1">
                {{ Str::limit(strip_tags($berita->konten), 80) }}
            </p>
            
            <div class="flex items-center gap-2 pt-4 border-t border-slate-100 border-dashed">
                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-xs font-bold text-blue-600 border border-blue-100">
                    {{ substr($berita->penulis, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-slate-700 truncate">{{ $berita->penulis }}</p>
                </div>
                
                {{-- Actions --}}
                <div class="flex items-center gap-1">
                    <button onclick="openEditModal({{ $berita->id }})" 
                       class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button onclick="deleteBerita({{ $berita->id }})" 
                            class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
{{-- Empty State --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
    </div>
    <h3 class="text-base font-semibold text-slate-700 mb-1">Belum Ada Berita</h3>
    <p class="text-sm text-slate-400 mb-4">Mulai buat berita pertama untuk sekolah Anda.</p>
    <button onclick="openCreateModal()" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Berita Baru
    </button>
</div>
@endif

{{-- Pagination --}}
@if($beritas->hasPages())
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
    {{ $beritas->links() }}
</div>
@endif


{{-- Modal Create/Edit --}}
<div id="beritaModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-hidden shadow-2xl transform scale-95 opacity-0 transition-all duration-300" id="modalContent">
        
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <h3 id="modalTitle" class="text-lg font-bold text-slate-800">Tambah Berita Baru</h3>
                <p class="text-xs text-slate-500 mt-0.5">Isi detail berita di bawah ini</p>
            </div>
            <button onclick="closeModal()" 
                class="w-9 h-9 bg-white hover:bg-slate-50 rounded-full flex items-center justify-center transition-colors border border-slate-200 text-slate-500 hover:text-slate-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="beritaForm" enctype="multipart/form-data" class="overflow-y-auto" style="max-height: calc(90vh - 140px);">
            @csrf
            <input type="hidden" id="beritaId" name="berita_id">
            <input type="hidden" id="formMethod" name="_method" value="POST">

            <div class="p-6 space-y-5">
                {{-- Judul --}}
                <div>
                    <label for="judul" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Judul Berita <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="judul" 
                           id="judul" 
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm bg-slate-50 focus:bg-white"
                           placeholder="Tulis judul berita..."
                           required>
                </div>

                {{-- Penulis --}}
                <div>
                    <label for="penulis" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Nama Penulis <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="penulis" 
                           id="penulis" 
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm bg-slate-50 focus:bg-white"
                           placeholder="Nama penulis..."
                           required>
                </div>

                {{-- Konten --}}
                <div>
                    <label for="konten" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Isi Konten <span class="text-red-400">*</span>
                    </label>
                    <textarea name="konten" 
                              id="konten" 
                              rows="6"
                              class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none text-sm bg-slate-50 focus:bg-white"
                              placeholder="Tulis isi berita lengkap..."
                              required></textarea>
                </div>

                {{-- Gambar --}}
                <div>
                    <label for="gambar" class="block text-xs font-semibold text-slate-600 mb-1.5">
                        Gambar Illustrasi
                    </label>
                    
                    <div id="currentImageContainer" class="hidden mb-3">
                        <p class="text-xs text-slate-400 mb-1">Gambar saat ini:</p>
                        <img id="currentImage" src="" alt="Current" class="h-24 w-auto rounded-lg border border-slate-100 shadow-sm">
                    </div>

                    <div class="relative border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-blue-400 transition-colors bg-slate-50/50">
                        <input type="file" 
                               name="gambar" 
                               id="gambar" 
                               accept="image/*"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                               onchange="previewImage(event)">
                        <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-xs text-slate-500 font-medium">Klik atau seret gambar ke sini</p>
                        <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG (Max: 2MB)</p>
                    </div>
                    
                    <div id="imagePreview" class="mt-3 hidden">
                        <img id="preview" src="" alt="Preview" class="w-full h-32 object-cover rounded-lg shadow-sm">
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button type="button" 
                        onclick="closeModal()"
                        class="px-5 py-2.5 border border-slate-200 text-slate-600 font-semibold rounded-xl hover:bg-white transition-colors text-sm bg-white shadow-sm">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-colors text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="submitButtonText">Simpan Berita</span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// CSRF Token
 $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Open Create Modal
function openCreateModal() {
    $('#modalTitle').text('Tambah Berita Baru');
    $('#submitButtonText').text('Simpan Berita');
    $('#beritaForm')[0].reset();
    $('#beritaId').val('');
    $('#formMethod').val('POST');
    $('#currentImageContainer').addClass('hidden');
    $('#imagePreview').addClass('hidden');
    
    const modal = document.getElementById('beritaModal');
    const content = document.getElementById('modalContent');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

// Open Edit Modal
function openEditModal(id) {
    $.ajax({
        url: `/admin/berita/${id}/edit`,
        method: 'GET',
        success: function(data) {
            $('#modalTitle').text('Edit Berita');
            $('#submitButtonText').text('Update Berita');
            $('#beritaId').val(data.id);
            $('#formMethod').val('PUT');
            $('#judul').val(data.judul);
            $('#penulis').val(data.penulis);
            $('#konten').val(data.konten);
            
            if (data.gambar) {
                $('#currentImage').attr('src', `/img/${data.gambar}`);
                $('#currentImageContainer').removeClass('hidden');
            } else {
                $('#currentImageContainer').addClass('hidden');
            }
            
            $('#imagePreview').addClass('hidden');
            
            const modal = document.getElementById('beritaModal');
            const content = document.getElementById('modalContent');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Gagal memuat data berita!',
                confirmButtonColor: '#2563eb'
            });
        }
    });
}

// Close Modal
function closeModal() {
    const modal = document.getElementById('beritaModal');
    const content = document.getElementById('modalContent');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

// Preview Image
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2048 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran gambar maksimal 2MB!',
                confirmButtonColor: '#2563eb'
            });
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            $('#preview').attr('src', e.target.result);
            $('#imagePreview').removeClass('hidden');
        }
        reader.readAsDataURL(file);
    }
}

// Submit Form
 $('#beritaForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const beritaId = $('#beritaId').val();
    const method = $('#formMethod').val();
    
    let url = '/admin/berita';
    if (method === 'PUT') {
        url = `/admin/berita/${beritaId}`;
        formData.append('_method', 'PUT');
    }

    Swal.fire({
        title: 'Menyimpan...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            closeModal();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: response.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => { location.reload(); });
        },
        error: function(xhr) {
            let errorMessage = 'Terjadi kesalahan!';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                errorMessage = Object.values(errors).flat().join('\n');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            Swal.fire({ icon: 'error', title: 'Gagal!', text: errorMessage, confirmButtonColor: '#2563eb' });
        }
    });
});

// Delete Berita
function deleteBerita(id) {
    Swal.fire({
        title: 'Hapus Berita?',
        text: "Berita yang dihapus tidak dapat dikembalikan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            $.ajax({
                url: `/admin/berita/${id}`,
                method: 'DELETE',
                success: function(response) {
                    Swal.fire({ icon: 'success', title: 'Terhapus!', text: response.message, timer: 1500, showConfirmButton: false })
                     .then(() => { location.reload(); });
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Tidak dapat menghapus berita.', confirmButtonColor: '#2563eb' });
                }
            });
        }
    });
}

// Close modal on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('beritaModal');
        if (!modal.classList.contains('hidden')) { closeModal(); }
    }
});
</script>
@endpush