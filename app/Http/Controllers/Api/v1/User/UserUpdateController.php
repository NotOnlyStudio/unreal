<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserUpdateController extends Controller
{
    public function update_status()
    {
        User::query()->where('id', auth()->id())->update([
            'last_seen' => now()
        ]);

        return response()->json(['message' => 'User status updated']);
    }
}
