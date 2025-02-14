// public/js/students_index.js

$(document).ready(function() {
    // 検索ボタンをクリックしたときの処理
    $('#searchButton').on('click', function() {
        var name = $('#name').val();
        var grade = $('#grade').val();

        $.ajax({
            url: searchUrl, // 変数としてURLを設定
            method: 'GET',
            data: { name: name, grade: grade },
            success: function(data) {
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

        $.ajax({
            url: sortUrl, // 変数としてURLを設定
            method: 'GET',
            data: { order: sortOrder },
            success: function(data) {
                $('#studentsTable tbody').html(data);
            },
            error: function(xhr, status, error) {
                console.error("ソートリクエスト失敗:", status, error); // エラーログ
            }
        });
    });
});
