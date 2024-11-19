<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/menu'; // ここを変更

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        // リクエストのバリデーション
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 入力されたクレデンシャルを取得
        $credentials = $request->only('email', 'password');

        // ユーザーを取得
        $user = \App\User::where('email', $credentials['email'])->first();

        // ユーザーが存在し、パスワードが一致するか確認
        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            return redirect()->intended($this->redirectPath());
        } else {
            // パスワードが一致しない場合
            return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
        }
    }
}
