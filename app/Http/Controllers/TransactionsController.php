<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Transaction;

class TransactionsController extends Controller
{
    public function newTransaction(){
        Auth::id();
        
    }
}
