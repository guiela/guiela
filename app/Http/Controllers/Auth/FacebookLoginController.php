<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Services\FacebookAccountService;

class FacebookLoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        // save anything you will need later, for example a url to come back to
        Session::put('url.intended', URL::previous());

        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @param FacebookAccountService $service
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(FacebookAccountService $service)
    {
        $user = $service->createOrGetUser(Socialite::driver('facebook')->user());
        
        auth()->login($user);

        $url = Session::get('url.intended', url('/'));

        Session::forget('url.intended');

        return \redirect($url);
    }
}
