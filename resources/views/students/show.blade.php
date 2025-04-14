<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学生詳細表示画面</title>
    <link rel="stylesheet" href="{{ asset('css/students_show.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/students.js') }}"></script>
</head>
<body>
    <h1>学生詳細表示画面</h1>
    <div class="container">
        @if (isset($student))
        <!-- 学生情報 -->
        <div class="info">
            <div>
                <label>学年:</label>
                <span>{{ $student->grade_label ?? ($student->grade . '年生') }}</span>
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
            <div>
                <label>顔写真:</label><br>
                <img src="{{ asset('storage/' . $student->img_path) }}" alt="学生の顔写真">
            </div>
            <div>
                <label>コメント:</label>
                <span>{{ $student->comment ?? 'なし' }}</span>
            </div>
        </div>

        <!-- アクションボタン -->
        <div class="actions">
            <button onclick="window.location.href='{{ route('students.edit', $student->id) }}'">学生編集</button>
            <button onclick="window.location.href='{{ route('schoolGrades.create', ['student_id' => $student->id]) }}'">成績追加</button>
            <form method="POST" action="{{ route('students.destroy', $student->id) }}" style="display:inline-block;" onsubmit="return confirm('本当にこの学生を削除しますか？');">
                 @csrf
                    <button type="submit" class="delete-button">削除</button>
            </form>
        </div>

        <!-- 成績検索機能 -->
        <form method="GET" action="{{ route('students.filterGrades', $student->id) }}" class="grades">
            <h2>成績検索</h2>
            <label for="gradeFilter">学年:</label>
            <select id="gradeFilter" name="grade">
                <option value="">全て</option>
                @foreach(['1年生', '2年生', '3年生', '4年生'] as $grade)
                    <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                @endforeach
            </select>
            <label for="termFilter">学期:</label>
            <select id="termFilter" name="term">
                <option value="">全て</option>
                @foreach(['1学期', '2学期', '3学期'] as $term)
                    <option value="{{ $term }}" {{ request('term') == $term ? 'selected' : '' }}>{{ $term }}</option>
                @endforeach
            </select>
            <label for="sortOrder">学年のソート:</label>
            <select id="sortOrder" name="order">
                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>昇順</option>
                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>降順</option>
            </select>
            <button type="submit">検索</button>
        </form>

        <!-- 成績一覧 -->
        <div>
            <h2>成績一覧</h2>
            <table>
                <thead>
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
                </thead>
                <tbody>
                    @if(isset($grades) && $grades->isNotEmpty())
                        @foreach($grades as $grade)
                        <tr>
                           <td>{{ $grade->term }}</td>
                           <td>{{ $grade->grade_label }}</td>
                           <td>{{ $grade->japanese }}</td>
                           <td>{{ $grade->math }}</td>
                           <td>{{ $grade->science }}</td>
                           <td>{{ $grade->social_studies }}</td>
                           <td>{{ $grade->music }}</td>
                           <td>{{ $grade->home_economics }}</td>
                           <td>{{ $grade->english }}</td>
                           <td>{{ $grade->art }}</td>
                           <td>{{ $grade->health_and_physical_education }}</td>
                           <td><a href="{{ route('schoolGrades.edit', $grade->id) }}" class="btn-edit">編集</a></td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                           <td colspan="12">成績情報がありません。</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- 学生表示画面に戻るボタン -->
        <div style="text-align: center; margin-top: 20px;"> <!-- ボタンの位置調整 -->
            <button class="back-button" onclick="window.location.href='{{ route('students.index') }}'">学生表示画面に戻る</button>
        </div>
        @endif
    </div>
</body>