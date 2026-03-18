@extends('layouts.guruNav')

@section('content')

<div class="min-h-screen bg-gray-50 p-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Daftar Siswa
            </h1>

            <p class="text-gray-500 text-sm">
                Kelas {{ $guru->kelas }} {{ $guru->jurusan }}
            </p>
        </div>

        <!-- Search -->
        <div class="w-full md:w-72">
            <input
                type="text"
                placeholder="Cari nama / NIS..."
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                onkeyup="filterTable(this.value)"
            >
        </div>

    </div>


    <!-- Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- Table -->
        <div class="overflow-x-auto">

            <table id="tableSiswa" class="min-w-full text-sm text-left">

                <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">

                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">NIS</th>
                        <th class="px-6 py-4">Kelas</th>
                        <th class="px-6 py-4">Jurusan</th>
                        <th class="px-6 py-4">Streak Absen</th>
                    </tr>

                </thead>

                <tbody class="divide-y">

                    @forelse($siswa as $i => $item)

                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-6 py-4 font-medium text-gray-700">
                                {{ $i + 1 }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ $item->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->nis }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->kelas }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->jurusan }}
                            </td>

                            <td class="px-6 py-4">

                                @if($item->absen_streak >= 7)

                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                        🔥 {{ $item->absen_streak }} Hari
                                    </span>

                                @elseif($item->absen_streak >= 3)

                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                        {{ $item->absen_streak }} Hari
                                    </span>

                                @else

                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                        {{ $item->absen_streak }} Hari
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-16">

                                <div class="flex flex-col items-center justify-center gap-3 text-gray-400">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M9 17v-2a4 4 0 014-4h4"/>
                                    </svg>

                                    <p class="text-sm">
                                        Belum ada siswa di kelas ini
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- Search Script -->
<script>

function filterTable(value)
{
    let filter = value.toLowerCase()
    let rows = document.querySelectorAll("#tableSiswa tbody tr")

    rows.forEach(row => {

        let text = row.innerText.toLowerCase()

        if(text.includes(filter))
        {
            row.style.display = ""
        }
        else
        {
            row.style.display = "none"
        }

    })
}

</script>

@endsection