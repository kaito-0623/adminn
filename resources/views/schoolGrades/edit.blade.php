<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績編集画面</title>
    <link rel="stylesheet" href="{{ asset('css/schoolGrades_edit.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/school_grades_edit.js') }}"></script>
</head>
<body>
    <h1>成績編集画面</h1>
    <div class="container">
        <!-- エラーメッセージの表示 -->
        @if ($errors->any())
            <div class="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 成績編集フォーム -->
        <form method="POST" action="{{ route('schoolGrades.update', $schoolGrade->id) }}">
            @csrf
            
            
            <div>
                <label for="student_id">学生:</label>
                <select id="student_id" name="student_id" required readonly>
                    <option value="{{ $schoolGrade->student_id }}">{{ $schoolGrade->student->name }}</option>
                </select>
            </div>

            <div>
                <label for="grade">学年:</label>
                <select id="grade" name="grade" required>
                    @foreach([1 => '1年生', 2 => '2年生', 3 => '3年生', 4 => '4年生'] as $value => $label)
                        <option value="{{ $value }}" @if(old('grade', $schoolGrade->grade) == $value) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="term">学期:</label>
                <select id="term" name="term" required>
                    @foreach(['1学期', '2学期', '3学期'] as $term)
                        <option value="{{ $term }}" @if(old('term', $schoolGrade->term) == $term) selected @endif>{{ $term }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 科目ごとの入力フィールド -->
            <div>
                <label for="japanese">国語:</label>
                <input type="number" id="japanese" name="japanese" min="0" max="100" value="{{ old('japanese', $schoolGrade->japanese) }}" required>
            </div>
            <div>
                <label for="math">数学:</label>
                <input type="number" id="math" name="math" min="0" max="100" value="{{ old('math', $schoolGrade->math) }}" required>
            </div>
            <div>
                <label for="science">科学:</label>
                <input type="number" id="science" name="science" min="0" max="100" value="{{ old('science', $schoolGrade->science) }}" required>
            </div>
            <div>
                <label for="social_studies">社会:</label>
                <input type="number" id="social_studies" name="social_studies" min="0" max="100" value="{{ old('social_studies', $schoolGrade->social_studies) }}" required>
            </div>
            <div>
                <label for="music">音楽:</label>
                <input type="number" id="music" name="music" min="0" max="100" value="{{ old('music', $schoolGrade->music) }}" required>
            </div>
            <div>
                <label for="home_economics">家庭科:</label>
                <input type="number" id="home_economics" name="home_economics" min="0" max="100" value="{{ old('home_economics', $schoolGrade->home_economics) }}" required>
            </div>
            <div>
                <label for="english">英語:</label>
                <input type="number" id="english" name="english" min="0" max="100" value="{{ old('english', $schoolGrade->english) }}" required>
            </div>
            <div>
                <label for="art">美術:</label>
                <input type="number" id="art" name="art" min="0" max="100" value="{{ old('art', $schoolGrade->art) }}" required>
            </div>
            <div>
                <label for="health_and_physical_education">体育:</label>
                <input type="number" id="health_and_physical_education" name="health_and_physical_education" min="0" max="100" value="{{ old('health_and_physical_education', $schoolGrade->health_and_physical_education) }}" required>
            </div>

            <button type="submit">更新</button>
        </form>

        <!-- 成績削除ボタン -->
        <form method="POST" action="{{ route('schoolGrades.destroy', $schoolGrade->id) }}" onsubmit="return confirm('本当にこの成績を削除しますか？');">
            @csrf
            <button type="submit" class="delete-button">成績削除</button>
        </form>

        <!-- 戻るボタン -->
        <button class="back-button" onclick="window.location.href='{{ route('students.show', $schoolGrade->student_id) }}'">戻る</button>
    </div>
</body>
</html>