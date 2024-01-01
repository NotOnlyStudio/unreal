<?php

namespace App\Service\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DashboardUpdatePasswordServices
{
    public function update(array $data)
    {
        User::query()->where('id', auth()->user()->id)->update([
            'password' => Hash::make($data['password'])
        ]);

        return redirect()->route('dashboard.index')->with('status', trans('dashboard.update.success'));
    }
}
