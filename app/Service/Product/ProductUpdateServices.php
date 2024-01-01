<?php

namespace App\Service\Product;

use App\Models\Product;

class ProductUpdateServices extends  ProductServices
{
    public function update(array $data)
    {
        $findProduct = Product::query()->where('id', $data['id'])->first();

        if ($findProduct) {
            if ($findProduct->user_id ==  auth()->user()->getAuthIdentifier()) {
                $findProduct['name_translit'] = $this->translit($data['name']);
                $findProduct['category_id'] = $data['category'];
                $findProduct['price'] = $data['amount'];
                $findProduct->update($data);
            }
        }

        return redirect(route('product', [$findProduct->name_translit]));
    }
}
