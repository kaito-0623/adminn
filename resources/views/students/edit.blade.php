<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生編集画面</title>
</head>
<body>
    <h1>学生編集画面</h1>
    <form method="POST" action="{{ route('students.update', $student->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div>
            <label for="id">学生ID:</label>
            <input type="text" id="id" name="id" value="{{ $student->id }}" readonly>
        </div>

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
                @foreach(['1年生', '2年生', '3年生', '4年生'] as $grade)
                    <option value="{{ $grade }}" @if(old('grade', $student->grade) == $grade) selected @endif>{{ $grade }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label for="comment">コメント:</label>
            <textarea id="comment" name="comment">{{ old('comment', $student->comment) }}</textarea>
        </div>
        
        <div>
            <label for="photo">顔写真:</label>
            <input type="file" id="photo" name="photo">
            @if ($student->img_path)
                <div>
                    <img src="{{ asset('storage/' . $student->img_path) }}" alt="学生の顔写真">
                </div>
            @endif
        </div>
        
        <button type="submit">更新</button>
    </form>

    <!-- 学生詳細表示画面に戻るボタン -->
    <button onclick="window.location.href='{{ route('students.show', $student->id) }}'">戻る</button>

    <script>
    function goBack() {
        window.history.back();
    }
    </script>
</body>
</html>
