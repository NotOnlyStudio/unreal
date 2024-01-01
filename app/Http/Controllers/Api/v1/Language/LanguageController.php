<?php

namespace App\Http\Controllers\Api\v1\Language;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function language(Request $request)
    {
        $lang = $request->input('language');

        if ($lang) {
            App::setLocale($lang);
            Session::put('language', $lang);
        }

        return App::getLocale();
    }
}
