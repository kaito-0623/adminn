<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生表示画面</title>
</head>
<body>
    <h1>学生表示画面</h1>

    <!-- 検索フォーム -->
    <form method="GET" action="{{ route('students.index') }}">
        <div>
            <label for="name">学生名:</label>
            <input type="text" id="name" name="name" value="{{ request('name') }}">
        </div>
        <div>
            <label for="grade">学年:</label>
            <select id="grade" name="grade">
                <option value="">選択してください</option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}" {{ request('grade') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit">検索</button>
    </form>

    <!-- 学生リスト -->
    <table border="1">
        <tr>
            <th>学年</th>
            <th>名前</th>
            <th>詳細</th>
        </tr>
        @foreach ($students as $student)
        <tr>
            <td>{{ $student->grade->name }}</td>
            <td>{{ $student->name }}</td>
            <td><a href="{{ route('students.show', $student->id) }}">詳細表示</a></td>
        </tr>
        @endforeach
    </table>
</body>
</html>
