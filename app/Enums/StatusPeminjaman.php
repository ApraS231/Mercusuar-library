<?php

namespace App\Enums;

enum StatusPeminjaman: string
{
    case Pending = 'Pending';
    case Disetujui = 'Disetujui';
    case Ditolak = 'Ditolak';
    case Diproses = 'Diproses';
    case Diantar = 'Diantar';
    case Diterima = 'Diterima';
    case Dikembalikan = 'Dikembalikan';
    case Overdue = 'Overdue';
}