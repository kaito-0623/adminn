// public/js/students.js

function filterGrades() {
    var grade = $('#gradeFilter').val();
    var term = $('#termFilter').val();

    $.ajax({
        url: filterStudentGradesUrl, // 変数としてURLを設定
        method: 'GET',
        data: { grade: grade, term: term },
        success: function(data) {
            $('#gradesTable tbody').html(data);
        },
        error: function(xhr, status, error) {
            console.error("成績フィルタリングリクエスト失敗:", status, error); // エラーログ
        }
    });
}

function sortGrades() {
    var sortOrder = $('#sortOrder').val();

    $.ajax({
        url: sortStudentGradesUrl, // 変数としてURLを設定
        method: 'GET',
        data: { order: sortOrder },
        success: function(data) {
            $('#gradesTable tbody').html(data);
        },
        error: function(xhr, status, error) {
            console.error("成績ソートリクエスト失敗:", status, error); // エラーログ
        }
    });
}

$(document).ready(function() {
    // ドキュメント読み込み完了後の処理
});
