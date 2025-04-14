<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生登録画面</title>
    <link rel="stylesheet" href="{{ asset('css/students_create.css') }}">
</head>
<body>
    <h1>学生登録画面</h1>
    <div class="container">
        <!-- エラーメッセージの表示 -->
        @if ($errors->any())
            <div class="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="name">名前(必須):</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div>
                <label for="address">住所(必須):</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" required>
            </div>
            <div>
                <label for="email">メールアドレス(必須):</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div>
                <label for="grade">学年:</label>
                <select id="grade" name="grade" required>
                    @for($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}" {{ old('grade') == $i ? 'selected' : '' }}>{{ $i }}年生</option>
                    @endfor
                </select>
            </div>
            <!-- コメント欄 -->
            <div>
                <label for="comment">コメント(任意):</label>
                <textarea id="comment" name="comment" rows="4">{{ old('comment') }}</textarea>
            </div>
            <div>
                <label for="photo">顔写真 (必須):</label>
                <input type="file" id="photo" name="photo" accept="image/*" required> <!-- 必須項目に設定 -->
            </div>
            <button type="submit">登録</button>
        </form>

        <button class="back-button" onclick="window.location.href='{{ url('/menu') }}'">メニューに戻る</button>
    </div>
</body>
</html>