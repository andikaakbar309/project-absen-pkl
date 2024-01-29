<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }

  public function auth(Request $request)
  {
      $request->validate([
          'username' => 'required',
          'password' => 'required',
      ]);

      if (Auth::attempt($request->only('username', 'password'))) {

          return redirect()->intended('/');
      }

      return back()->withInput()->withErrors(['username' => 'Invalid login credentials']);
  }

  public function logout()
  {
    Auth::logout();

    return redirect('auth/login');
  }

}
