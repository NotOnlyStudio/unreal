<?php

namespace App\Http\Controllers\Api\v1\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Service\Product\Comment\ProductCommentCreateServices;
use App\Service\Product\Comment\ProductCommentDestroyServices;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductCommentController extends Controller
{
    public function create(ProductCreateRequest $request, ProductCommentCreateServices $services): void
    {
        $services->create($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function delete(int $comment_id, ProductCommentDestroyServices $services)
    {
        $services->destroy($comment_id);
    }
}
