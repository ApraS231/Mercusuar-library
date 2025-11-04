<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Mendaftarkan command scheduler (Tugas Otomatis)
Schedule::command('app:check-overdue-loans') // <-- Memanggil command yang kita buat
    ->daily(); // <-- Menjalankannya setiap hari (pukul 00:00)
    // ->dailyAt('01:00'); // <-- Anda juga bisa tentukan jamnya, misal jam 1 pagi
