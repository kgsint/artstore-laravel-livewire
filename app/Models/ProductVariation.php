<?php

namespace App\Models;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class ProductVariation extends Model implements HasMedia
{
    use HasFactory, HasRecursiveRelationships, InteractsWithMedia;

    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class, 'cart_product_variation')
                                        ->withPivot('quantity');
    }

    public function isLowStock()
    {
        return $this->isInStock() && $this->stockCount() <= 5;
    }

    public function isInStock(): bool
    {
        return $this->stockCount() > 0;
    }

    public function isOutOfStock(): bool
    {
        return !$this->isInStock();
    }

    public function stockCount(): int
    {
        return $this->descendantsAndSelf->sum(fn($variation) => $variation->stocks->sum('amount'));
    }

    public function formattedPrice()
    {
        return money($this->price);
    }

    // store as media conversion
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')->fit(Manipulations::FIT_CROP, 200, 200);
    }
}
