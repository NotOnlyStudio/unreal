<?php

namespace App\Http\Controllers\Api\v1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\DashboardUploadFileRequest;
use App\Service\Upload\UploadUserServices;
use Illuminate\Http\JsonResponse;

class DashboardImageController extends Controller
{
    public function upload(DashboardUploadFileRequest $request, UploadUserServices $services): JsonResponse
    {
        return response()->json([
            'url' => $services->upload($request->validated())
        ]);
    }
}
