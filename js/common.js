$(document).ready(function() {

    loadSettings(true);

    $('body').on('click', '#saveSettings', function(){
            $("#msg").html("Идет сохранение настроек...");
            $.ajax({
                    url: '/src/index.php',
                    type: "POST",
                    data: "action=setComplexity&complexityMin=" + $('input[name=complexityMin]').val() + "&complexityMax=" + $('input[name=complexityMax]').val(),
                    dataType: 'json',
                    success: function(res){
                            if(res){
                                $("#msg").html("Настройки сохранены");
                            } else {
                                $("#msg").html("Произошла ошибка, повторите действие!");
                            }
                            loadSettings();
                    }
            });
    });

    $('body').on('click', '#addUser', function(){
            $("#msg").html("Идет сохранение настроек...");
            $.ajax({
                    url: '/src/index.php',
                    type: "POST",
                    data: "action=setUserIq&userIQ=" + $('input[name=userIQ]').val(),
                    dataType: 'json',
                    success: function(res){
                            if(res){
                                $("#msg").html("Настройки сохранены");
                            } else {
                                $("#msg").html("Произошла ошибка, повторите действие!");
                            }
                            loadSettings();
                    }
            });
    });
    
    $('body').on('click', '#runTest', function(){
            $("#msg").html("Идет эмуляция тестрования...");
            $("#testRes table tbody").html("");
            $("#totalres").html("");
            $.ajax({
                    url: '/src/index.php',
                    type: "POST",
                    data: "action=run",
                    dataType: 'json',
                    success: function(res){
                            if(res){
                                var i=1;
                                var content;
                                $("#msg").html("Тест завершен");
                                for (var question in res.questions) {
                                    let answer = res.questions[question].answer == 1 ? "Да" : "Нет";
                                    let used = res.questions[question].used == null ? 0 : parseInt(res.questions[question].used);
                                    content += "<tr><td>" + i + "</td>" +
                                               "<td>" + question + "</td>" +
                                               "<td>" + used + "</td>" +
                                               "<td>" + parseInt(res.questions[question].complexity) + "</td>" +
                                               "<td>" + answer + "</td>" +
                                                "</tr>";
                                    i++;
                                }
                                $("#testRes table tbody").html(content);
                                $("#totalres").html("Тестируемый ответил правильно на " + res.total_true + " вопросов из 40.");
                            } else {
                                $("#msg").html("Произошла ошибка, проверьте настройки и повторите действие!");
                            }
                    }
            });
    });
});

function loadSettings(init = false)
{
    $("input").prop('disabled', true);
    if (init) {
        $("#msg").html("Загрузка настроек...");
    }
    $.ajax({
        url: '/src/index.php',
        type: "POST",
        data: "action=getSettings",
        dataType: 'json',
        success: function(res){
                if(res){
                    if (init) {
                        $("#msg").html("Настройки загружены");
                    }
                    $("#complexityMin").html(parseInt(res[0]));
                    $("#complexityMax").html(parseInt(res[1]));
                    $("#userIQ").html(parseInt(res[2]));
                } else {
                    if (init) {
                        $("#msg").html("Произошла ошибка, обновите страницу!");
                    }
                }
                $("input").prop('disabled', false);
        }
    });
}