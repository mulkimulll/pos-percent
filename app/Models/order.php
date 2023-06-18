<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\produk;
use App\Models\order_detail;

class order extends Model
{
    use HasFactory;
    protected $table = "order";
    protected $guarded;
    // protected $fillable = ['id_produk','nama_customer','jumlah_produk','jumlah_bayar','diskon'];

    public function getDetail()
    {
        return $this->hasMany(order_detail::class, 'order_id', 'id');
    }
}
