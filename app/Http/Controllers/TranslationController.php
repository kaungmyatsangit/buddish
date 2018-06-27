<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeLocale(Request $request)
    {
        $this->validate($request, ['locale' => 'required|in:fr,en']);

        Session::put('locale', $request->get('locale'));

        return redirect()->back();
    }
}
