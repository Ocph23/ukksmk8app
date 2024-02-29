<?php

namespace App\Http\Controllers;

use App\Models\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{


    public function show()
    {
        $count = User::count();
        if ($count <= 0) {
            return Redirect::to('/auth/register');
        } else {
            return view('/auths/login');
        }
    }


    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        if (!Auth::validate($credentials)) :
            return redirect()->to('/auth/login')
                ->withErrors(trans('auth.failed'));
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        Auth::login($user);
        return redirect('/admin');
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect('/auth/login');
    }

    /**
     * Handle response after user authenticated
     * 
     * @param Request $request
     * @param Auth $user
     * 
     * @return \Illuminate\Http\Response
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended();
    }
}
