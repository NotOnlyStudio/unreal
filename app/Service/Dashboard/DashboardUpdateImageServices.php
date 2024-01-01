<?php

namespace App\Service\Dashboard;

use App\Models\User;

class DashboardUpdateImageServices
{
    public function upload(array $data)
    {
        User::query()->where('id', auth()->id())->update([
            $data['type'] => $data['image']
        ]);

        return response()->json([
            'message' => trans('dashboard.upload.success')
        ]);
    }
}
