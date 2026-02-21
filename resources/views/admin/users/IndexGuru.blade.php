@extends('layouts.adminNav')

@section('title', 'User Management')

@section('content')
    <div class="p-4 md:p-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
            <h1 class="text-2xl font-bold">Manajemen User</h1>
            <button onclick="openCreateModal()"
                class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 text-center">
                + Tambah User
            </button>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto bg-white rounded-2xl shadow">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">NIS</th>
                        <th class="p-3 text-left">Role</th>
                        <th class="p-3 text-left">Point</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="p-3 font-medium">{{ $user->name }}</td>
                            <td class="p-3 text-gray-600">{{ $user->email }}</td>
                            <td class="p-3">{{ $user->nis }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs rounded-lg bg-blue-100 text-blue-700">
                                    {{ $user->usertype }}
                                </span>
                            </td>
                            <td class="p-3">{{ $user->Point ?? 0 }}</td>
                            <td class="p-3 space-x-2">
                                <button onclick='openEditModal(@json($user))'
                                    class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 text-xs">
                                    Edit
                                </button>
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700 text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List -->
        <div class="md:hidden space-y-3">
            @foreach ($users as $user)
                <div class="bg-white rounded-2xl shadow p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-lg bg-blue-100 text-blue-700 shrink-0 ml-2">
                            {{ $user->usertype }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-1 text-sm text-gray-600 mb-3">
                        <div><span class="text-gray-400 text-xs">NIS:</span> {{ $user->nis ?? '-' }}</div>
                        <div><span class="text-gray-400 text-xs">Point:</span> {{ $user->Point ?? 0 }}</div>
                    </div>

                    <div class="flex gap-2">
                        <button onclick='openEditModal(@json($user))'
                            class="flex-1 bg-yellow-500 text-white py-1.5 rounded-lg text-sm hover:bg-yellow-600">
                            Edit
                        </button>
                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button class="w-full bg-red-600 text-white py-1.5 rounded-lg text-sm hover:bg-red-700">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal Overlay -->
        <div id="userModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
            <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl relative animate-fadeIn
                        max-h-[90vh] overflow-y-auto">

                <!-- Modal Header -->
                <div class="sticky top-0 bg-white px-6 pt-6 pb-3 border-b z-10 rounded-t-2xl">
                    <h2 id="modalTitle" class="text-xl font-bold">Tambah User</h2>
                    <button onclick="closeModal()"
                        class="absolute top-4 right-4 text-gray-400 hover:text-black text-xl leading-none">
                        ✕
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="px-6 pb-6 pt-4">
                    <form id="userForm" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="id" id="user_id">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                <input type="text" name="name" id="name"
                                    class="w-full border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="email"
                                    class="w-full border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIS</label>
                                <input type="text" name="nis" id="nis"
                                    class="w-full border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                                <input type="text" name="nohp" id="nohp"
                                    class="w-full border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir"
                                    class="w-full border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                    class="w-full border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin"
                                    class="w-full border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                                <input type="text" name="kelas" id="kelas"
                                    class="w-full border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                                <input type="text" name="jurusan" id="jurusan"
                                    class="w-full border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <select name="usertype" id="usertype"
                                    class="w-full border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <option value="siswa">Siswa</option>
                                    <option value="guru">Guru</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Point</label>
                                <input type="number" name="point" id="point"
                                    class="w-full border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3"
                                    class="w-full border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                                <input type="file" name="foto"
                                    class="w-full border rounded-xl p-2 text-sm file:mr-2 file:py-1 file:px-2 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" name="password" id="password"
                                    class="w-full border rounded-xl p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak diubah</p>
                            </div>

                        </div>

                        <!-- Modal Footer -->
                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 mt-6">
                            <button type="button" onclick="closeModal()"
                                class="w-full sm:w-auto px-4 py-2 bg-gray-200 rounded-xl text-sm hover:bg-gray-300">
                                Batal
                            </button>
                            <button type="submit"
                                class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-xl text-sm hover:bg-blue-700">
                                Simpan
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>

    </div>

    <script>
        const modal = document.getElementById('userModal');
        const form = document.getElementById('userForm');

        function openCreateModal() {
            document.getElementById('modalTitle').innerText = "Tambah User";
            form.action = "{{ route('admin.users.store') }}";
            form.reset();
            document.getElementById('user_id').value = '';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function openEditModal(user) {
            document.getElementById('modalTitle').innerText = "Edit User";
            form.action = "/admin/users/update/" + user.id;

            document.getElementById('user_id').value = user.id;
            document.getElementById('name').value = user.name ?? '';
            document.getElementById('email').value = user.email ?? '';
            document.getElementById('nis').value = user.nis ?? '';
            document.getElementById('nohp').value = user.nohp ?? '';
            document.getElementById('tempat_lahir').value = user.tempat_lahir ?? '';
            document.getElementById('tanggal_lahir').value = user.tanggal_lahir ?? '';
            document.getElementById('jenis_kelamin').value = user.jenis_kelamin ?? 'L';
            document.getElementById('kelas').value = user.kelas ?? '';
            document.getElementById('jurusan').value = user.jurusan ?? '';
            document.getElementById('usertype').value = user.usertype ?? 'siswa';
            document.getElementById('point').value = user.point ?? 0;
            document.getElementById('alamat').value = user.alamat ?? '';
            document.getElementById('password').value = '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal on backdrop click
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });
    </script>

@endsection