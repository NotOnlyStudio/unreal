<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Discord;
use Illuminate\Http\Request;

class DiscordController extends Controller
{
    public function discord()
    {
        return Discord::query()->first();
    }
}
