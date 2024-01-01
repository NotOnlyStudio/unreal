<?php

namespace App\Http\Requests\Product;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['string'],
            'amount' => ['integer'],
            'description' => ['string'],
            'img1' => ['string'],
            'img2' => ['string'],
            'img3' => ['string'],
            'video' => ['string'],
            'preview_video' => ['string'],
            'id' => ['required'],
            'category' => ['required'],
        ];
    }
}
