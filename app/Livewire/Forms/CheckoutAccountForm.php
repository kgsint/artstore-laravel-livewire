<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

class CheckoutAccountForm extends Form
{
    public $email = '';

    // attribute name as:
    protected $validationAttributes =[
        'email' => 'Email address',
    ];

    // validation rules
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email'. (auth()->user() ? ',' . auth()->id() : '') ,
        ];
    }

    // validation messages
    public function messages()
    {
        return [
            // 'email.required' => 'The email address cannot be empty',
            'email.unique' => 'Seems you already have an account. Please sign in to place an order',
        ];
    }

}
