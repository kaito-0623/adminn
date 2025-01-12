<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生詳細表示画面</title>
    <script>
        function filterGrades() {
            var grade = document.getElementById('gradeFilter').value;
            var term = document.getElementById('termFilter').value;
            var rows = document.querySelectorAll('table.grade-table tr.grade-row');
            rows.forEach(function(row) {
                var rowGrade = row.dataset.grade;
                var rowTerm = row.dataset.term;
                if ((grade === '' || rowGrade === grade) && (term === '' || rowTerm === term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</head>
<body>
    <h1>学生詳細表示画面</h1>
    @if (isset($student))
        <div>
            <label>学年:</label>
            <span>{{ $student->grade }}</span>
        </div>
        <div>
            <label>名前:</label>
            <span>{{ $student->name }}</span>
        </div>
        <div>
            <label>住所:</label>
            <span>{{ $student->address }}</span>
        </div>
        <div>
            <label>メールアドレス:</label>
            <span>{{ $student->email }}</span>
        </div>
        @if($student->img_path)
        <div>
            <label>顔写真:</label>
            <img src="{{ asset('storage/' . $student->img_path) }}" alt="学生の顔写真">
        </div>
        @endif
        <div>
            <label>コメント:</label>
            <span>{{ $student->comment }}</span>
        </div>

        <!-- 学生編集ボタン -->
        <button onclick="window.location.href='{{ route('students.edit', $student->id) }}'">学生編集</button>

        <!-- 成績追加ボタン -->
        <button onclick="window.location.href='{{ route('schoolGrades.create', ['student_id' => $student->id]) }}'">成績追加</button>

        <!-- 学生削除ボタン -->
        <form method="POST" action="{{ route('students.destroy', $student->id) }}" onsubmit="return confirm('本当にこの学生を削除しますか？');">
            @csrf
            @method('DELETE')
            <button type="submit">削除</button>
        </form>

        <!-- 成績検索機能 -->
        <div>
            <h2>成績検索</h2>
            <label for="gradeFilter">学年:</label>
            <select id="gradeFilter" onchange="filterGrades()">
                <option value="">全て</option>
                @foreach(['1年生', '2年生', '3年生', '4年生'] as $grade)
                    <option value="{{ $grade }}">{{ $grade }}</option>
                @endforeach
            </select>

            <label for="termFilter">学期:</label>
            <select id="termFilter" onchange="filterGrades()">
                <option value="">全て</option>
                @foreach(['1学期', '2学期', '3学期'] as $term)
                    <option value="{{ $term }}">{{ $term }}</option>
                @endforeach
            </select>
        </div>

        <!-- 成績表示 -->
        <div>
            <h2>成績一覧</h2>
            <table border="1" class="grade-table">
                <tr>
                    <th>学期</th>
                    <th>学年</th>
                    <th>国語</th>
                    <th>数学</th>
                    <th>科学</th>
                    <th>社会</th>
                    <th>音楽</th>
                    <th>家庭科</th>
                    <th>英語</th>
                    <th>美術</th>
                    <th>体育</th>
                    <th>編集</th>
                </tr>
                @foreach($student->schoolGrades as $schoolGrade)
                <tr class="grade-row" data-grade="{{ $schoolGrade->grade }}" data-term="{{ $schoolGrade->term }}">
                    <td>{{ $schoolGrade->term }}</td>
                    <td>{{ $schoolGrade->grade }}</td>
                    <td>{{ $schoolGrade->japanese }}</td>
                    <td>{{ $schoolGrade->math }}</td>
                    <td>{{ $schoolGrade->science }}</td>
                    <td>{{ $schoolGrade->social_studies }}</td>
                    <td>{{ $schoolGrade->music }}</td>
                    <td>{{ $schoolGrade->home_economics }}</td>
                    <td>{{ $schoolGrade->english }}</td>
                    <td>{{ $schoolGrade->art }}</td>
                    <td>{{ $schoolGrade->health_and_physical_education }}</td>
                    <td>
                        <button onclick="window.location.href='{{ route('schoolGrades.edit', $schoolGrade->id) }}'">編集</button>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    @else
        <p>学生の情報が見つかりません。</p>
    @endif

    <!-- 学生表示画面に戻るボタン -->
    <button onclick="window.location.href='{{ route('students.index') }}'">学生表示画面に戻る</button>

    <script>
    function goBack() {
        window.history.back();
    }
    </script>
</body>
</html>
