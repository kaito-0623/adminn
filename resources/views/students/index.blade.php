<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生表示画面</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .search-form, .sort-form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input, select {
            width: calc(100% - 20px);
            padding: 8px 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            display: block;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .details-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
        }

        .details-button:hover {
            background-color: #0056b3;
        }

        .button-container {
            margin-top: 20px;
        }

        .button-container button {
            background-color: #6c757d;
        }

        .button-container button:hover {
            background-color: #5a6268;
        }
    </style>
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
                    @foreach([1, 2, 3, 4] as $grade)
                        <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>{{ $grade }}年生</option>
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
                    <td>{{ $student->grade }}年生</td>
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