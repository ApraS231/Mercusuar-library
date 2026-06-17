<?php

namespace App\Enums;

enum StatusPeminjaman: string
{
    case Pinjam = 'Pinjam';
    case Disetujui = 'Disetujui';
    case Ditolak = 'Ditolak';
    case Selesai = 'Selesai';
    case Overdue = 'Overdue';
}