<div>
    <h1 class="text-3xl font-bold mb-6">Dashboard Admin</h1>

    {{-- Grid untuk "Stat Card" --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card 1: Peminjaman Pending -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
            <h2 class="text-sm font-medium text-gray-500 truncate">Peminjaman Pending</h2>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $jumlahPeminjamanPending }}</p>
        </div>

        <!-- Card 2: Total Judul Buku -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <h2 class="text-sm font-medium text-gray-500 truncate">Total Judul Buku</h2>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $jumlahBuku }}</p>
        </div>

        <!-- Card 3: User Aktif -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-500">
            <h2 class="text-sm font-medium text-gray-500 truncate">User Aktif</h2>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $jumlahUserAktif }}</p>
        </div>

        <!-- Card 4: Peminjaman Overdue -->
        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-500">
            <h2 class="text-sm font-medium text-gray-500 truncate">Peminjaman Overdue</h2>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $jumlahOverdue }}</p>
        </div>

    </div>

    {{-- Area untuk chart atau tabel ringkasan nanti --}}
    <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Aktivitas Terbaru</h2>
        <p class="text-gray-600">Area ini bisa diisi dengan daftar peminjaman pending terbaru atau aktivitas admin lainnya nanti.</p>
    </div>
</div>
