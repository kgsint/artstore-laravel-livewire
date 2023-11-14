<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_displays_a_list_of_products()
    {
        // set up
        $product = Product::factory()->create();

        $response = $this->get(route('products.index'));

        // assertions
        $response->assertSuccessful();
        $response->assertSee($product->title);
    }

    public function test_it_displays_a_product_and_its_variations_in_show_page()
    {
        // set up
        $product = Product::factory()->create();
        $variationOne = ProductVariation::factory()->create([
            'title' => 'gray',
            'product_id' => $product->id,
            'price' => $product->price,
            'type' => 'color',
            'sku' => 'abc'
        ]);
        $variationTwo = ProductVariation::factory()->create([
            'title' => 'red',
            'product_id' => $product->id,
            'price' => $product->price + 10,
            'type' => 'color',
            'sku' => 'abc'
        ]);

        $response = $this->get(route('products.show', $product->slug));
        // assertions
        $response->assertSuccessful();

        $response->assertSee($product->title);
        $response->assertSee($variationOne->title);
        $response->assertSee($variationTwo->title);

        $response->assertSee($product->description);
        $response->assertSee($variationOne->type);
        $response->assertSee($variationTwo->type);

    }
}
