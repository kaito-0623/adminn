<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績登録画面</title>
</head>
<body>
    <h1>成績登録画面</h1>

    <!-- エラーメッセージの表示 -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('schoolGrades.store') }}">
        @csrf
        <input type="hidden" name="student_id" value="{{ $student->id }}">
        <div>
            <label for="student">学生:</label>
            <span>{{ $student->name }}</span>
        </div>
        <div>
            <label for="grade">学年:</label>
            <input type="text" id="grade" name="grade" value="{{ $student->grade }}" readonly>
        </div>
        <div>
            <label for="term">学期:</label>
            <select id="term" name="term" required>
                <option value="1学期">1学期</option>
                <option value="2学期">2学期</option>
                <option value="3学期">3学期</option>
            </select>
        </div>
        <div>
            <label for="japanese">国語:</label>
            <input type="number" id="japanese" name="japanese" min="0" max="100" required>
        </div>
        <div>
            <label for="math">数学:</label>
            <input type="number" id="math" name="math" min="0" max="100" required>
        </div>
        <div>
            <label for="science">科学:</label>
            <input type="number" id="science" name="science" min="0" max="100" required>
        </div>
        <div>
            <label for="social_studies">社会:</label>
            <input type="number" id="social_studies" name="social_studies" min="0" max="100" required>
        </div>
        <div>
            <label for="music">音楽:</label>
            <input type="number" id="music" name="music" min="0" max="100" required>
        </div>
        <div>
            <label for="home_economics">家庭科:</label>
            <input type="number" id="home_economics" name="home_economics" min="0" max="100" required>
        </div>
        <div>
            <label for="english">英語:</label>
            <input type="number" id="english" name="english" min="0" max="100" required>
        </div>
        <div>
            <label for="art">美術:</label>
            <input type="number" id="art" name="art" min="0" max="100" required>
        </div>
        <div>
            <label for="health_and_physical_education">体育:</label>
            <input type="number" id="health_and_physical_education" name="health_and_physical_education" min="0" max="100" required>
        </div>
        <button type="submit">登録</button>
    </form>

    <!-- 学生詳細表示画面に戻るボタン -->
    <button onclick="window.location.href='{{ route('students.show', $student->id) }}'">戻る</button>
</body>
</html>
