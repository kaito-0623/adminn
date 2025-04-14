<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生編集画面</title>
    <link rel="stylesheet" href="{{ asset('css/students_edit.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/students_edit.js') }}"></script>
</head>
<body>
    <h1>学生編集画面</h1>
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

        <form method="POST" action="{{ route('students.update', $student->id) }}">
            @csrf

            <div>
                <label for="name">名前:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" required>
            </div>

            <div>
                <label for="address">住所:</label>
                <input type="text" id="address" name="address" value="{{ old('address', $student->address) }}" required>
            </div>

            <div>
                <label for="email">メールアドレス:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}" required>
            </div>

            <div>
                <label for="grade">学年:</label>
                <select id="grade" name="grade" required>
            <!-- 学年の選択肢をX年生の形式で表示 -->
            @foreach([1 => '1年生', 2 => '2年生', 3 => '3年生', 4 => '4年生'] as $value => $label)
                <option value="{{ $value }}" @if(old('grade', $student->grade) == $value) selected @endif>{{ $label }}</option>
            @endforeach
                </select>
            </div>

            <div>
                <label for="photo">写真:</label>
                <input type="file" id="photo" name="photo" accept="image/*">
            @if ($student->img_path)
                <img src="{{ asset('storage/' . $student->img_path) }}" alt="学生の写真" style="width: 100px; height: auto; margin-top: 10px;">
            @endif
            </div>

            <div>
                <label for="comment">コメント:</label>
                <textarea id="comment" name="comment" rows="4">{{ old('comment', $student->comment) }}</textarea>
            </div>

            <button type="submit">更新</button>
        </form>

        <!-- 戻るボタン -->
        <button class="back-button" onclick="window.location.href='{{ route('students.show', $student->id) }}'">戻る</button>
    </div>
</body>
</html>