<?php

namespace App\Http\Controllers\Api\v1\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function select(Request $request)
    {

    }

    public function index()
    {
        return Category::query()->get();
    }
}
