<?php

namespace App\Service\Product;

use App\Models\Product;

class ProductStoreServices extends  ProductServices
{
    public function create(array $data)
    {
        $create = $this->store($data);
        return redirect(route('product', [$create->name_translit]));
    }

    protected function store(array $data)
    {
        $data['user_id'] = auth()->user()->id;
        $data['price'] = $data['amount'];
        $data['category_id'] = $data['category'];
        $data['name_translit'] = $this->translit($data['name']);

        return Product::query()->create($data);
    }

}
