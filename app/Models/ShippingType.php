<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function formattedPrice()
    {
        return money($this->price);
    }
}
