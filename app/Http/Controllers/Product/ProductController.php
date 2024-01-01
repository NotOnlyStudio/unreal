<?php

namespace App\Http\Controllers\Product;

use App\Facade\Video;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        return Inertia::render('Welcome', [
            'products' => $this->products(
                $request->input('category')
                    ? Product::query()->with(['user'])->orderBy('id', 'desc')->where('category_id', $request->input('category'))->get()
                    : Product::query()->with(['user'])->orderBy('id', 'desc')->get()
            ),
//            'categories' => ($request->input('category')) ? Category::query()->where('id', $request->input('category'))->get() : null
        ]);
    }

    public function products($products)
    {
        $orders = [];
        if (auth()->user())
            $orders = Payment::query()->where([['user_id', auth()->user()->id], ['status', 3]])->get() ?? null;

        $matchedProducts = [];

        foreach ($orders as $order) {
            $productId = $order->product_id;

            $products->first(function ($product) use ($productId) {
                if ($product->id === $productId) {
                    $product->ordered = true;
                } else {
                    $product->ordered = false;
                }
            });
        }

        return $products;
    }

    public function product(Request $request, $alias)
    {
        $data = Product::query()->where('name_translit', 'LIKE', "%$alias%")->with('user')->first();

        if ($data) {
            return Inertia::render('Product', [
                'data' => $data,
                'alias' => $alias,
                'comments' => Comment::query()->where('product_id', $data->id)->orderBy('id', 'desc')->with(['user'])->get(),
                'video' => 'https://www.youtube.com/embed/' . Video::videoID($data->video),
                'order' => (auth()->user()) ? Payment::query()->where([['user_id', auth()->user()->id], ['product_id', $data->id]])->first() : []
            ]);
        }

        abort(404);
    }

    public function filter(Request $request)
    {
        return $this->products(Product::query()->with(['user'])->orderBy('id', 'desc')->where('category_id', $request->integer('id'))->get());
    }
}
