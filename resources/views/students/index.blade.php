<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生表示画面</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // ルートURLをJavaScriptで利用するための変数を設定
        var searchUrl = "{{ route('students.search') }}";
        var sortUrl = "{{ route('students.sort') }}";
    </script>
    <script src="{{ asset('js/students_index.js') }}"></script>
</head>
<body>
    <h1>学生表示画面</h1>

    <!-- 検索フォーム -->
    <div>
        <label for="name">学生名:</label>
        <input type="text" id="name" name="name" value="{{ request('name') }}">
        <button id="searchButton">検索</button>
    </div>
    <div>
        <label for="grade">学年:</label>
        <select id="grade" name="grade">
            <option value="">選択してください</option>
            @foreach(['1年生', '2年生', '3年生', '4年生'] as $grade)
                <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>{{ $grade }}</option>
            @endforeach
        </select>
    </div>

    <!-- ソート機能 -->
    <div>
        <label for="sortOrder">学年のソート:</label>
        <select id="sortOrder">
            <option value="asc">昇順</option>
            <option value="desc">降順</option>
        </select>
    </div>

    <!-- 学生リスト -->
    <table border="1" id="studentsTable">
        <thead>
            <tr>
                <th>学年</th>
                <th>名前</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @include('students.partials.students_table', ['students' => $students])
        </tbody>
    </table>

    <!-- メニュー画面に戻るボタン -->
    <button onclick="window.location.href='{{ url('/menu') }}'">戻る</button>
</body>
</html>
