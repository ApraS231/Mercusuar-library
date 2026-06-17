<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case User = 'user';
    case KepalaPerpus = 'kepala_perpus';
}
