<?php

namespace App\Service\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class DashboardUpdateServices
{
    public function update(array $data)
    {
        if ($data['email'] != auth()->user()->email) {
            $emailUniqueCheck = User::query()->where('email', $data['email'])->first();

            if ($emailUniqueCheck) {
                throw ValidationException::withMessages([
                    'email' => trans('validation.unique'),
                ]);
            }
        }

        $data['password'] = Hash::make($data['password']);

        User::query()->where('id', auth()->user()->id)->update($data);

        return redirect()->route('dashboard.index')->with('status', trans('dashboard.update.success'));
    }
}
