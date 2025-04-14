<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'user_name', 'email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // パスワードをハッシュ化してユーザー作成
    public static function createWithHashedPassword(array $data)
    {
        try {
            $data['password'] = Hash::make($data['password']);
            $user = self::create($data);
            Log::info('User created successfully.', ['id' => $user->id, 'data' => $data]);
            return $user;
        } catch (\Exception $e) {
            Log::error('Error creating user.', ['data' => $data, 'error_message' => $e->getMessage()]);
            throw new \Exception('Failed to create user: ' . $e->getMessage());
        }
    }

    // ログイン試行
    public static function attemptLogin(array $credentials)
    {
        try {
            $user = self::where('email', $credentials['email'])->first();
            if ($user && Hash::check($credentials['password'], $user->password)) {
                Auth::login($user);
                Log::info('Login successful.', ['user_id' => $user->id]);
                return true;
            }
            Log::warning('Login failed.', ['email' => $credentials['email']]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error during login attempt.', ['email' => $credentials['email'], 'error_message' => $e->getMessage()]);
            throw new \Exception('Failed to attempt login: ' . $e->getMessage());
        }
    }
}