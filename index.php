<?php
# Подключение инициализационного файла
require_once 'inc/core/init.php';
# Вывод данных о пользователе
$mail = Session::get('email');$uid  = Session::get('user');
# Если пользователь не авторизован - переход к авторизации
if (!Session::exists('user')){
    header('Location: /login.php');
    exit();
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head lang="en">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/man.css">
    <title></title>
</head>
<body>

<div class="panel panel-default" id="user_panel" >
    <div class="panel-body" id="mypb">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <button id="exit" class="btn btn-default" data-toggle="tooltip" data-placement="left" title="Выход из системы"><i class="fa fa-sign-out"></i></button>
                    <div id="uid"><?php echo $mail;?></div>
                    <div id="menuToggle"><i class="fa fa-th-large fa-3x">  Фотогаллерея</i></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid" id="main_content">
    <div class="row">

        <div class="col-md-3 col-xs-12 ">
            <div class="panel panel-default" id="fotoadd_panel" >
                <div class="panel-heading">
                    Добавить фотографии
                </div>
                <div class="panel-body">

                    <form action="" method="POST" enctype="multipart/form-data" id="rf">
                        <input type="file" id="files" name="rfile[]" multiple="multiple" accept="image/png,image/jpeg, image/gif" />
                        <output id="list"></output>
                    </form>

                    <button id="add_foto" class="btn btn-default">Добавить фотографии
                </div>

            </div>
            <div id="show_status"></div>
        </div>

        <div class="col-md-9 col-xs-12 ">
            <div class="panel panel-default" id="foto_panel" >
                <div class="panel-heading">
                    Фотографии пользователя
                </div>
                <div class="panel-body">
                    <div id="all_foto">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

    <div class="navbar navbar-inverse navbar-fixed-bottom" id="footer">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-4  col-xs-12 ">
                <div class="page-header" id="f_header" >
                </div>
                <div id ="textfooter_txt">
                </div>
            </div>

            <div class="col-md-4  col-xs-12 col-sm-12 hidden-sm hidden-xs">
                <div class="page-header" id="f_header">
                </div>
                <br>
            </div>


        </div>
    </div>
</div>


<script type="text/javascript">
//Кол-во файлов введенных в форму пользователем
var colf = 0;
//Загрузка файлов пользователя
loadFiles();


//Привязка событий к элементам
document.getElementById('files').addEventListener('change', FileSelect, false);
document.getElementById('add_foto').addEventListener('click', uploadFiles, false);
document.getElementById('exit').addEventListener('click', logout, false);


    //****ЗАГРУЗКА ФАЙЛОВ НА СЕРВЕР************************************//
    function uploadFiles() {
        if(colf!=0) {
            // Форма для отправки файлов на сервер
            var formData = new FormData(document.getElementById('rf'));
            formData.append('param', 'insfoto');
            // Создание объекта
            var xhr = new XMLHttpRequest();

            // Привязка события на загрузку
            xhr.upload.onprogress = function (event) {
                document.getElementById('show_status').innerHTML = 'Загружено на сервер ' + event.loaded + ' байт из ' + event.total;
            };
            //Открытие
            xhr.open('POST', '/inc/ra.php', true);
            // Отправка данных
            xhr.send(formData);
            // Привязка события на конец загрузки
            xhr.upload.onloadend = function(){
                // Вывод статуса
                document.getElementById('show_status').innerHTML = 'Подождите! Идет обработка файлов ';
            };

            xhr.onreadystatechange = function () {
                if (this.readyState == 4) {
                    if (this.status != 200) {
                        // Ошибка
                        alert('ошибка: ' + this.status + ' ' + this.statusText);
                    }
                    else {
                        try {
                            // Разбор ответа и вывод его в статус
                            document.getElementById('list').innerHTML = '<ul>';
                            ff = JSON.parse(this.responseText);
                            for(var i = 0;i<ff.length;i++){
                                    document.getElementById('list').innerHTML = document.getElementById('list').innerHTML+'<li><strong>'+ff[i]+'</strong></li>';
                            }
                            document.getElementById('list').innerHTML = document.getElementById('list').innerHTML+'</ul>';
                        }
                        catch(e){alert('Ошибка сервера!');}
                    }
                    document.getElementById('show_status').innerHTML = '';
                    document.getElementById('files').value = '';colf = 0;
                    // Вывод всех файлов
                    loadFiles();
                    setTimeout(function(){document.getElementById('list').innerHTML = '';}, 2000);
                }
                else{}
            };
        }else{alert('Файлы для загрузки не выбраны!');}
    }
    //*****************************************************************//

    //******ПОЛУЧЕНИЕ ФОТО С СЕРВЕРА***********************************//
    function loadFiles(){
        document.getElementById('all_foto').innerHTML ='';

        // Создание объекта
        var xhr = new XMLHttpRequest();
        //Открытие
        xhr.open('POST', '/inc/ra.php', true);
        // Установка RequestHeader
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        // Отправка данных
        xhr.send('param=allfoto');

        xhr.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status != 200) {
                    // Ошибка
                    alert('ошибка: ' + this.status + ' ' + this.statusText);
                }
                else {
                    try {
                        // Разбор ответа и вывод фоток
                        ff = JSON.parse(this.responseText);
                        for(var i = 0;i<ff.length;i++){
                            document.getElementById('all_foto').innerHTML = document.getElementById('all_foto').innerHTML+'<div id="pre_foto"><a href="'+ff[i].replace('/pr','')+'" target="_blank"><img id="rm" src="'+
                            ff[i]+'" alt="'+ff[i]+'"></a><div id="del">Удалить фото</div></div>';
                        }
                        var dels = document.querySelectorAll('#del');
                        for(var i = 0; i < dels.length; i++) {
                            dels[i].addEventListener('click', delFile, false);
                        }

                    }
                    catch(e){alert('Ошибка сервера!');}
                }
            }

        };

    }
    //*****************************************************************//

    //******ДЕЙСТВИЯ ПРИ ВЫБОРЕ ФАЙЛОВ*********************************//
    function FileSelect(evt) {
        //Список файлов
        var files = evt.target.files;
        var output = [];
        // Количесиво одновременно загружаемых файлов не больше 6
        if(files.length<6){
            // Количесиво загруженых файлов
            colf = files.length;
            for (var i = 0, f; f = files[i]; i++) {
                // Проверка соответствия файла изображению
              //  if(f.type == "image/jpeg" || f.type == "image/gif" || f.type == "image/png"){
                    //Размер загружаемого файла
                    $fsizekb = f.size / 1024;
                    // Вывод информации о файле
                    output.push('<li><strong>', f.name, ' (', $fsizekb.toFixed(2),' КБ)','</strong></li>');
               // }
            }
            document.getElementById('list').innerHTML = '<ul>' + output.join('') + '</ul>';
        }else{
            document.getElementById('list').innerHTML = '<ul>Одновременно можно загрузить не больше 5 файлов!</ul>';
            colf = 0;
        }
    }
    //*****************************************************************//

    //******ВЫХОД *****************************************************//
    function logout(){
        document.location.replace('/logout.php');
    };
    //*****************************************************************//

    //******УДАЛЕНИЕ ФАЙЛА*********************************************//
    function delFile(){
        // Фото
        var did = this.parentNode;
        var hr = this.parentNode.firstChild;
        // Путь к фото
        var ee = hr.firstChild.alt;
        // Создание объекта
        var xhr = new XMLHttpRequest();
        //Открытие
        xhr.open('POST', '/inc/ra.php', true);
        // Установка RequestHeader
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        // Отправка данных
        xhr.send('param=delfoto&foto='+ee);

        xhr.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status != 200) {
                    // Ошибка
                    alert('ошибка: ' + this.status + ' ' + this.statusText);
                }
                else {
                    if (this.responseText=="1"){
                        // Если на сервере файлы удалены, то убираем фото из DOM
                        did.parentNode.removeChild(did);
                    }
                    else{alert("Произошла ошибка! Невозможно удалить файл :"+ee);}
                }
            }

        };
    };
    //*****************************************************************//


</script>


</body>
</html>


