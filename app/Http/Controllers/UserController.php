<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /** ユーザー一覧を表示 */
    public function index()
    {
        $users = User::getAllUsers(); //モデルのメソッドを利用
        return view('users.index', compact('users'));
    }

    /** 認証済みユーザー情報を取得 */
    public function show($id)
    {
        try {
            $user = User::findUserById($id); //モデルのメソッドを利用
            return view('users.show', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error finding user.', ['user_id' => $id, 'error_message' => $e->getMessage()]);
            return redirect()->route('users.index')->with('error', 'ユーザー情報の取得に失敗しました。');
        }
    }


    /** ユーザー情報を編集画面に表示 */
    public function edit($id)
    {
        try {
            $user = User::findUserById($id); //モデルのメソッドを利用
            return view('users.edit', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error finding user for edit.', ['user_id' => $id, 'error_message' => $e->getMessage()]);
            return redirect()->route('users.index')->with('error', 'ユーザー情報が取得できませんでした。');
        }
    }

    /** ユーザー情報を更新 */
    public function update(UserRequest $request, $id)
{
    try {
        User::updateUserInfo($id, $request->validated()); //Modelから呼び出す

        return redirect()->route('users.show', $id)->with('success', 'ユーザー情報が更新されました。');
    } catch (\Exception $e) {
        Log::error('Error updating user.', [
            'user_id' => $id,
            'error_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()->with('error', 'ユーザー情報の更新に失敗しました。');
    }
}

    /** ユーザーを削除 */
    public function destroy($id)
    {
        try {
            if (User::removeUser($id)) { //モデルのメソッドを利用

                return redirect()->route('users.index')->with('success', 'ユーザーが削除されました。');
            } else {
                Log::warning('User deletion failed.', ['user_id' => $id]);
                return redirect()->back()->with('error', 'ユーザーの削除に失敗しました。');
            }
        } catch (\Exception $e) {
            Log::error('Error deleting user.', ['user_id' => $id, 'error_message' => $e->getMessage()]);
            return redirect()->route('users.index')->with('error', 'ユーザーの削除に失敗しました。');
        }
    }
}