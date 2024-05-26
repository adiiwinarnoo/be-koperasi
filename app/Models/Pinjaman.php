<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = [
        'id',
        'id_product',
        'nik_karyawan',
        'nama_karyawan',
        'jumlah_pinjam',
        'harga',
    ];
}
