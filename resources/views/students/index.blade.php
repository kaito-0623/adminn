<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生表示画面</title>
    <link rel="stylesheet" href="{{ asset('css/students_index.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>学生表示画面</h1>
    <div class="container">

        <!-- 検索フォーム -->
        <form method="GET" action="{{ route('students.index') }}">
            <div class="search-form">
                <label for="name">学生名:</label>
                <input type="text" id="name" name="name" value="{{ request('name') }}" placeholder="例: 山田 太郎">
                <button type="submit">検索</button>
            </div>

            <div class="search-form">
                <label for="grade">学年:</label>
                <select id="grade" name="grade">
                    <option value="">選択してください</option>
                    @foreach([1, 2, 3, 4, '卒業生'] as $grade)
                        <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>
                            @if($grade === '卒業生')
                                卒業生
                            @else
                                {{ $grade }}年生
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- ソート機能 -->
            <div class="sort-form">
                <label for="sortOrder">学年のソート:</label>
                <select id="sortOrder" name="sortOrder">
                    <option value="asc" {{ request('sortOrder') == 'asc' ? 'selected' : '' }}>昇順</option>
                    <option value="desc" {{ request('sortOrder') == 'desc' ? 'selected' : '' }}>降順</option>
                </select>
            </div>
        </form>

        <!-- 学生リスト -->
        <table id="studentsTable">
            <thead>
                <tr>
                    <th>学年</th>
                    <th>名前</th>
                    <th>写真</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td>
                        @if ($student->grade === '卒業生')
                            {{ $student->grade }}
                        @else
                            {{ $student->grade }}年生
                        @endif
                    </td>
                    <td>{{ $student->name }}</td>
                    <td>
                        @if ($student->img_path)
                            <img src="{{ asset('storage/' . $student->img_path) }}" alt="学生の写真">
                        @else
                            写真なし
                        @endif
                    </td>
                    <td>
                        <!-- ボタン形式の詳細リンク -->
                        <form method="GET" action="{{ route('students.show', $student) }}">
                            <button type="submit" class="details-button">詳細</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">該当する学生が見つかりませんでした。</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- メニュー画面に戻るボタン -->
        <div class="button-container">
            <button onclick="window.location.href='{{ url('/menu') }}'">メニューに戻る</button>
        </div>
    </div>
</body>
</html>