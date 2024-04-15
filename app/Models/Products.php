<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\CurrencyService;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'id',
        'name',
        'price'
    ];

    public function getPriceEurAttribute()
    {
        return (new CurrencyService())->convert($this->price, 'usd', 'eur');
    }
}
