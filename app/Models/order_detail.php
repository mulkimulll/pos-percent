<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_detail extends Model
{
    use HasFactory;
    protected $table = 'order_detail';
    protected $guarded;
    

    public function getProduk()
    {
        return $this->hasOne(produk::class, 'id', 'produk_id');
    }
}
