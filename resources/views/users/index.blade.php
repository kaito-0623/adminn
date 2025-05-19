@extends('layouts.app')

@section('content')
<div class="container">
    <h2>ユーザー一覧</h2>

    <!-- ⭐ メニュー画面に戻るボタンを追加 -->
    <a href="{{ route('menu.index') }}" class="btn btn-secondary mb-3">メニューへ戻る</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>ユーザー名</th>
                <th>メールアドレス</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->user_name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-info">詳細</a>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">編集</a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection