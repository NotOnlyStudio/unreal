<?php

namespace App\Service\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductDestroyServices extends  ProductServices
{
    public function destroy(int $productID)
    {
        $findProduct = Product::query()->where('id', $productID)->first();

        if($findProduct) {
            if ($findProduct->user_id == Auth::user()->id) {
                $findProduct->delete();
            }
        }

        return redirect(route('dashboard.product'));
    }
}
