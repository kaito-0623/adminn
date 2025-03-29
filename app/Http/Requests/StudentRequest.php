<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    /**
     * リクエストが認可されているかどうかを判断します。
     */
    public function authorize()
    {
        return true; // 全てのユーザーが許可されます（必要に応じて変更）
    }

    /**
     * バリデーションルールを定義します。
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255', // 名前必須、文字列で最大255文字
            'address' => 'required|string|max:255', // 住所必須、文字列で最大255文字
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:students,email,' . $this->route('student'), // 現在の学生IDを除外した一意性チェック
            ],
            'grade' => 'required|integer|min:1|max:4', // 学年は1から4の整数
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 画像ファイル制限
            'comment' => 'nullable|string|max:1000', // コメントは任意、最大1000文字
        ];
    }
}