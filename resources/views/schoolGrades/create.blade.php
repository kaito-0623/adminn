<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成績登録画面</title>
    <style>
        /* 現在のスタイルを維持 */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .alert {
            color: #d8000c;
            background-color: #ffbaba;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        form div {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }
        select, input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-button {
            margin-top: 15px;
            background-color: #6c757d;
        }
        .back-button:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <h1>成績登録画面</h1>
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

        <form method="POST" action="{{ route('schoolGrades.store') }}">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">

            <div>
                <label for="student">学生:</label>
                <span>{{ $student->name }}</span>
            </div>

            <div>
                <label for="grade">学年:</label>
                <select id="grade" name="grade" required>
                    @foreach([1 => '1年生', 2 => '2年生', 3 => '3年生', 4 => '4年生'] as $value => $label)
                        <option value="{{ $value }}" {{ $student->grade == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="term">学期:</label>
                <select id="term" name="term" required>
                    <option value="1学期">1学期</option>
                    <option value="2学期">2学期</option>
                    <option value="3学期">3学期</option>
                </select>
            </div>

            <!-- 科目ごとの入力フィールド -->
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

        <button class="back-button" onclick="window.location.href='{{ route('students.show', $student->id) }}'">戻る</button>
    </div>
</body>
</html>