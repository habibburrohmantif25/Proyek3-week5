<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_order';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_order',
        'id_user',
        'tanggal_order',
        'total_harga',
        'alamat_pengiriman',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_order' => 'datetime',
            'total_harga' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'id_order', 'id_order');
    }
}
