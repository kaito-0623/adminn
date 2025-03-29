// public/js/students.js
// 成績フィルタリングの処理
function filterGrades() {
    var grade = $('#gradeFilter').val();
    var term = $('#termFilter').val();

    $.ajax({
        url: filterStudentGradesUrl, // フィルタリングリクエスト用のURL
        method: 'GET',
        data: { grade: grade, term: term },
        success: function(data) {
            $('#gradesTable tbody').html(data); // 成績テーブルを更新
        },
        error: function(xhr, status, error) {
            console.error("成績フィルタリングリクエスト失敗:", status, error); // エラーログを出力
        }
    });
}

// 成績並べ替えの処理
function sortGrades() {
    var sortOrder = $('#sortOrder').val();

    $.ajax({
        url: sortStudentGradesUrl, // 並べ替えリクエスト用のURL
        method: 'GET',
        data: { order: sortOrder },
        success: function(data) {
            $('#gradesTable tbody').html(data); // 成績テーブルを更新
        },
        error: function(xhr, status, error) {
            console.error("成績ソートリクエスト失敗:", status, error); // エラーログを出力
        }
    });
}

// ドキュメント読み込み完了後の処理
$(document).ready(function() {
    // 検索ボタンのクリックイベント設定
    $('#searchButton').on('click', filterGrades);

    // セレクタの変更イベント設定
    $('#gradeFilter, #termFilter').on('change', filterGrades);

    // ソートセレクタの変更イベント設定
    $('#sortOrder').on('change', sortGrades);
});