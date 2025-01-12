<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生表示画面</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <script>
        $(document).ready(function() {
            // 検索ボタンをクリックしたときの処理
            $('#searchButton').on('click', function() {
                var name = $('#name').val();
                var grade = $('#grade').val();
                console.log("検索ボタンが押されました:", name, grade); // デバッグ用コンソールログ
                $.ajax({
                    url: "{{ route('students.search') }}",
                    method: 'GET',
                    data: { name: name, grade: grade },
                    success: function(data) {
                        console.log("AJAXリクエスト成功:", data); // デバッグ用コンソールログ
                        $('#studentsTable tbody').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAXリクエスト失敗:", status, error); // エラーログ
                    }
                });
            });

            // ソートオプションを変更したときの処理
            $('#sortOrder').on('change', function() {
                var sortOrder = $(this).val();
                console.log("ソートオプションが変更されました:", sortOrder); // デバッグ用コンソールログ
                $.ajax({
                    url: "{{ route('students.sort') }}",
                    method: 'GET',
                    data: { order: sortOrder },
                    success: function(data) {
                        console.log("ソートリクエスト成功:", data); // デバッグ用コンソールログ
                        $('#studentsTable tbody').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("ソートリクエスト失敗:", status, error); // エラーログ
                    }
                });
            });
        });
    </script>
</body>
</html>
