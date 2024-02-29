<?php

namespace App\Http\Controllers;

use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Validator;
class RegisterController extends Controller
{
    public function show()
    {
        return view('/auths/register');
    }

    /**
     * Handle account registration request
     * 
     * @param RegisterRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email:rfc,dns|unique:users,email',
                'name' => 'required',
                'password' => 'required|min:6',
                'password_confirmation' => 'required|same:password'
            ]);

            if ($validator->fails()) {
                throw new Error("Periksa Kembali Data Anda");
            }
            $user = User::create($request->all());

            auth()->login($user);

            return redirect('/')->with('success', "Account successfully registered.");
            //code...
        } catch (\Throwable $th) {
           $err = $th->getMessage();
        }
    }
}
