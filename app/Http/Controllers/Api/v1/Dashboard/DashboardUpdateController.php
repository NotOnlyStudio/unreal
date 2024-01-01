<?php

namespace App\Http\Controllers\Api\v1\Dashboard;

use App\Http\Controllers\Controller;
use App\Service\Dashboard\DashboardUpdateImageServices;
use App\Service\Dashboard\DashboardUpdatePasswordServices;
use App\Service\Dashboard\DashboardUpdateServices;
use Illuminate\Http\Request;

class DashboardUpdateController extends Controller
{
    public function update(Request $request, DashboardUpdateServices $updateServices)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|',
            'name' => 'required|string|max:255',
        ]);

        return $updateServices->update($request->all());
    }

    public function password(Request $request, DashboardUpdatePasswordServices $services)
    {
        $request->validate([
            'password' => 'required',
        ]);

        return $services->update($request->all());
    }

    public function image(Request $request, DashboardUpdateImageServices $services)
    {
        return $services->upload($request->all());
    }
}
