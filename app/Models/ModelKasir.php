<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelKasir extends Model
{
    use HasFactory;

    protected $table = 'admin';

    protected $fillable = [
        'nomor_induk',
        'nama_admin',
        'jabatan',
        'password',
    ];
}
