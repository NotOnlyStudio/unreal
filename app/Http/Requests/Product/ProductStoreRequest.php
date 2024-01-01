<?php

namespace App\Http\Requests\Product;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:' . User::class],
            'amount' => ['required', 'integer'],
            'description' => ['required', 'string'],
            'img1' => ['required', 'string'],
            'img2' => ['required', 'string'],
            'img3' => ['required', 'string'],
            'video' => ['required', 'string'],
            'preview_video' => ['required', 'string'],
            'category' => ['required', 'integer']
        ];
    }
}
