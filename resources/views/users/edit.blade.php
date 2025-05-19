@extends('layouts.app')

@section('content')
<div class="container">
    <h2>ユーザー情報編集</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="user_name">ユーザー名</label>
                    <input type="text" class="form-control" id="user_name" name="user_name" value="{{ $user->user_name }}" required>
                </div>

                <div class="form-group">
                    <label for="email">メールアドレス</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                </div>

                <button type="submit" class="btn btn-primary">更新</button>
                <a href="{{ route('users.show', $user->id) }}" class="btn btn-secondary">ユーザー情報に戻る</a>
            </form>
        </div>
    </div>
</div>
@endsection