<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description' => ['required']
        ];
    }
}
