<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Dashboard', [
            'user' => \Illuminate\Support\Facades\Auth::user(),
            'status' => session('status')
        ]);
    }

    public function product()
    {
        return Inertia::render('Dashboard/Product', [
            'products' => Product::query()->where('user_id', auth()->user()->id)->with(['user'])->get(),
            'categories' => Category::query()->get()
        ]);
    }

    public function payment()
    {
        return Inertia::render('Dashboard/Payment', [
            'products' => Payment::query()->where([['user_id', auth()->user()->id], ['status', 3]])->with(['product', 'user'])->get()
        ]);
    }
}
