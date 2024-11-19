<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績一覧</title>
</head>
<body>
    <h1>成績一覧</h1>
    <a href="{{ route('grades.create') }}">成績追加</a>
    <table>
        <thead>
            <tr>
                <th>学生ID</th>
                <th>メールアドレス</th>
                <th>科目</th>
                <th>成績</th>
                <th>アクション</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($grades as $grade)
            <tr>
                <td>{{ $grade->student_id }}</td>
                <td>{{ $grade->student->email }}</td> <!-- ここを追加 -->
                <td>{{ $grade->subject }}</td>
                <td>{{ $grade->score }}</td>
                <td>
                    <a href="{{ route('grades.show', $grade->id) }}">詳細</a>
                    <a href="{{ route('grades.edit', $grade->id) }}">編集</a>
                    <form action="{{ route('grades.destroy', $grade->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('本当に削除しますか？');">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

