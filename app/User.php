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

    /** ユーザーがメール認証済みか確認 */
    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    /** メール認証を完了する */
    public function markEmailAsVerified()
    {
        $this->email_verified_at = now();
        $this->save();
    }

    /** ユーザー作成（パスワードのハッシュ化） */
    public static function createWithHashedPassword(array $data)
    {
        try {
            $data['password'] = Hash::make($data['password']);
            $user = self::create($data);
            
            return $user;
        } catch (\Exception $e) {
            Log::error('Error creating user.', ['data' => $data, 'error_message' => $e->getMessage()]);
            throw new \Exception('Failed to create user: ' . $e->getMessage());
        }
    }
    
    //データ取得系（一覧取得・個別検索)
    public static function getAllUsers()
    {
        return self::all();
    }

    public static function findUserById($id)
    {
        return self::findOrFail($id);
    }


    /** ログイン試行 */
    public static function attemptLogin(array $credentials)
    {
        try {
            $user = self::where('email', $credentials['email'])->first();
            if ($user && Hash::check($credentials['password'], $user->password)) {
                Auth::login($user);
                return true;
            }
            Log::warning('Login failed.', ['email' => $credentials['email']]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error during login attempt.', ['email' => $credentials['email'], 'error_message' => $e->getMessage()]);
            throw new \Exception('Failed to attempt login: ' . $e->getMessage());
        }
    }


    /** ユーザー情報更新 */
    public static function updateUserInfo($id, array $data)
    {
        try {
            //レコードを簡潔に更新カプセル化
            return self::where('id', $id)->update($data);
        } catch (\Exception $e) {
            Log::error('Error updating user.', ['user_id' => $id, 'error_message' => $e->getMessage()]);
            throw new \Exception('Failed to update user: ' . $e->getMessage());
        }
    }

    /** ユーザー削除 */
    public static function removeUser($id)
    {
        try {
            //ユーザーを検索（カプセル化）
            $user = self::find($id);

            if (!$user) {
                Log::warning('User not found for deletion.', ['user_id' => $id]);
                return false;
            }

            $user->delete();
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting user.', ['user_id' => $id, 'error_message' => $e->getMessage()]);
            throw new \Exception('Failed to delete user: ' . $e->getMessage());
        }
    }
}