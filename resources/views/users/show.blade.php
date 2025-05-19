@extends('layouts.app')

@section('content')
<div class="container">
    <h2>ユーザー情報</h2>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>名前:</strong> {{ $user->user_name }}</p>
            <p><strong>メールアドレス:</strong> {{ $user->email }}</p>

            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">編集</a>
            
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？');">削除</button>
            </form>
        </div>
    </div>

    <!-- ⭐ ユーザー一覧へ戻るボタンを追加 -->
    <div class="mt-3">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">ユーザー一覧へ戻る</a>
    </div>
</div>
@endsection