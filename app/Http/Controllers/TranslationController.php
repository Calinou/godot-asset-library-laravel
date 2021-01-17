<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TranslationController extends Controller
{
    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
        if (isset($request->lang) && array_key_exists($request->lang, config('app.languages')))
        {
            setcookie("locale", $request->lang, strtotime('+1 year'), '/');
        }

        return Redirect::back();
    }
}
