<?php

namespace App\Service\Product\Comment;

use App\Models\Comment;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Validation\ValidationException;

class ProductCommentCreateServices
{
    /**
     * @throws ValidationException
     */
    public function create(array $data)
    {
        $product = Product::query()->where('name_translit', $data['product_name'])->first();
        $order = Payment::query()->where([['user_id', auth()->user()->id], ['product_id', $product->id], ['status', 3]])->first();
        if ($order) {
            if ($product) {
                Comment::query()->create([
                    'product_id' => $product->id,
                    'user_id' => auth()->user()->id,
                    'description' => $data['description']
                ]);
            }
        }else {
            throw ValidationException::withMessages([
                'description' => trans('post.comment.nopay'),
            ]);
        }
    }
}
