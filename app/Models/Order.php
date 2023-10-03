<?php

namespace App\Models;

use App\Models\Presenters\OrderPresenter;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = [
        'placed_at',
        'packaged_at',
        'shipped_at',
    ];

    public array $statuses = [
        'placed_at',
        'packaged_at',
        'shipped_at',
    ];

    // when the order is creating, set uuid and placed_at
    public static function booted()
    {
        static::creating(function(Order $order) {
            $order->uuid = (string) Str::uuid();
            $order->placed_at = now();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shippingType(): BelongsTo
    {
        return $this->belongsTo(ShippingType::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    public function variations(): BelongsToMany
    {
        return $this->belongsToMany(ProductVariation::class)
                                                            ->withPivot(['quantity'])
                                                            ->withTimestamps();
    }

    public function formattedSubtotal()
    {
        return money($this->subtotal);
    }

    // status of the order
    public function status()
    {
        return collect($this->statuses)->last(fn($status) => $this->{$status});
    }

    public function presenter(): OrderPresenter
    {
        return new OrderPresenter($this);
    }
}
