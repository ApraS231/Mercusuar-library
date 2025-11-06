<div>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

    {{-- Grid untuk "Stat Card" --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card 1: Peminjaman Pending -->
        <div class="bg-white p-6 rounded-xl shadow-lg transform hover:-translate-y-1 transition-transform duration-300 flex items-center space-x-4">
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h2 class="text-sm font-medium text-gray-500">Peminjaman Pending</h2>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $pendingLoans }}</p>
            </div>
        </div>

        <!-- Card 2: Total Judul Buku -->
        <div class="bg-white p-6 rounded-xl shadow-lg transform hover:-translate-y-1 transition-transform duration-300 flex items-center space-x-4">
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <h2 class="text-sm font-medium text-gray-500">Total Judul Buku</h2>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $jumlahBuku }}</p>
            </div>
        </div>

        <!-- Card 3: User Aktif -->
        <div class="bg-white p-6 rounded-xl shadow-lg transform hover:-translate-y-1 transition-transform duration-300 flex items-center space-x-4">
            <div class="bg-purple-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-purple-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.965 5.965 0 0112 13a5.965 5.965 0 013 1.803"></path></svg>
            </div>
            <div>
                <h2 class="text-sm font-medium text-gray-500">User Aktif</h2>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $jumlahUserAktif }}</p>
            </div>
        </div>

        <!-- Card 4: Peminjaman Overdue -->
        <div class="bg-white p-6 rounded-xl shadow-lg transform hover:-translate-y-1 transition-transform duration-300 flex items-center space-x-4">
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h2 class="text-sm font-medium text-gray-500">Peminjaman Overdue</h2>
                <p class="mt-1 text-3xl font-bold text-gray-900">{{ $jumlahOverdue }}</p>
            </div>
        </div>

    </div>

    {{-- Area untuk chart atau tabel ringkasan nanti --}}
    <div class="mt-8 bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Aktivitas Terbaru</h2>
        <p class="text-gray-600">Area ini dapat diisi dengan daftar peminjaman yang menunggu persetujuan atau pendaftaran pengguna baru untuk tindakan cepat.</p>
    </div>
</div>
