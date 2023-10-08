<?php

namespace App\Models;

use App\Models\Scopes\ProductLiveScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [];

    // add global scope to product to not show the product which are not live
    public static function booted()
    {
        static::addGlobalScope(new ProductLiveScope);
    }

    // formatted price for product
    public function formattedPrice()
    {
        return money($this->price);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function isLive(): Attribute
    {
        return Attribute::make(get: fn() => !is_null($this->live_at));
    }

    // spatie media
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')->fit(Manipulations::FIT_CROP, 200, 200);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')
                                            ->useFallbackUrl("/storage/default-product-image.png");
    }

}
