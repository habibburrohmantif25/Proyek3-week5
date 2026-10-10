<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table = 'order_details';

    protected $fillable = [
        'id_order',
        'id_barang',
        'harga_satuan',
        'jumlah_beli',
    ];

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
            'jumlah_beli' => 'integer',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'id_order');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_barang', 'id_barang');
    }

    public function getSubtotalAttribute(): float
    {
        return (float) ($this->harga_satuan * $this->jumlah_beli);
    }
}
