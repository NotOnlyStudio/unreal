<?php

namespace App\Http\Controllers\Api\v1\Conclusion;

use App\Http\Controllers\Controller;
use App\Service\Conclusion\ConclusionServices;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ConclusionController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function conclusion(Request $request, ConclusionServices $services)
    {
        $request->validate([
            'accountName' => 'required',
            'amount' => 'required|integer',
            'freekassa' => ''
        ]);

        return $services->conclusion($request->all());
    }
}
