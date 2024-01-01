<?php

namespace App\Http\Controllers\Api\v1\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Models\Product;
use App\Service\Product\ProductDestroyServices;
use App\Service\Product\ProductStoreServices;
use App\Service\Product\ProductUpdateServices;
use Illuminate\Http\Request;

class ProductCreateController extends Controller
{
    public function store(ProductStoreRequest $request, ProductStoreServices $services)
    {
        return $services->create($request->all());
    }

    public function update(ProductUpdateRequest $request, ProductUpdateServices $services)
    {
        return $services->update($request->all());
    }

    public function delete($product_id, ProductDestroyServices $services)
    {
        return $services->destroy($product_id);
    }
}
