<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生編集画面</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/students_edit.js') }}"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin: 20px 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .alert {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }

        input[readonly] {
            background-color: #e9ecef;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-button {
            background-color: #6c757d;
        }

        .back-button:hover {
            background-color: #5a6268;
        }
    </style>
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
            @method('PUT')

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
        <button class="back-button" onclick="window.location.href='{{ route('students.index') }}'">戻る</button>
    </div>
</body>
</html>