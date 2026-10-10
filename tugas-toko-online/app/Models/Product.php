<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_barang',
        'nama_barang',
        'deskripsi',
        'harga',
        'stok',
        'gambar',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'id_barang', 'id_barang');
    }
}
