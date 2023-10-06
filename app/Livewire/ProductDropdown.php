<?php

namespace App\Livewire;

use App\Models\ProductVariation;
use Livewire\Component;

class ProductDropdown extends Component
{
    public $variations;
    public $selectedVariation;

    // dynamic getter property for product variation
    public function getSelectedVariationModelProperty()
    {
        if(! $this->selectedVariation) {
            return;
        }

        return ProductVariation::find($this->selectedVariation);
    }

    public function updatedSelectedVariation()
    {
        $this->dispatch('skuVariationSelected', null); // reset skuVariation if reselected

        // emitting event when final product variation is selected
        if($this->selectedVariationModel?->sku) {
            $this->dispatch('skuVariationSelected', $this->selectedVariation);
        }
    }

    public function render()
    {
        return view('livewire.product-dropdown');
    }
}
