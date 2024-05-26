<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $fillable = [
        'id',
        'code_barang',
        'nama_product',
        'foto_product',
        'harga_jual',
        'stock_masuk'
    ];
}
