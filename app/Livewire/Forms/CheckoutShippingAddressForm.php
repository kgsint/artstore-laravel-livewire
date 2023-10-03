<?php

namespace App\Livewire\Forms;

use App\Models\ShippingAddress;
use Livewire\Attributes\Rule;
use Livewire\Form;

class CheckoutShippingAddressForm extends Form
{
    #[Rule('required|max:255', as: 'address')]
    public $address = '';

    #[Rule('required|max:255', as: 'city')]
    public $city = '';

    #[Rule('required|max:255', as: 'post code')]
    public $postcode = '';

    // validation messages
    public function messages()
    {
        return [
            '*.required' => 'The :attribute cannot be empty',
        ];
    }

    public function populate(ShippingAddress $shippingAddress)
    {
        $this->address = $shippingAddress->address;
        $this->city = $shippingAddress->city;
        $this->postcode = $shippingAddress->postcode;
    }
}
