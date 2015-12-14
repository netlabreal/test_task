<?php

Class Foto{
    //------СОЗДАНИЕ ПРЕВЬЮ-------------------------------------------//
    public static function makePreview($f,$out){
        $res = false;
        $height=200; $width=200;
        $rgb=0xffffff;
        $size = getimagesize($f);
        $x_ratio = $width / $size[0];
        $y_ratio = $height / $size[1];
        $ratio       = min($x_ratio, $y_ratio);
        $use_x_ratio = ($x_ratio == $ratio);
        $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
        $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
        $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
        $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

        $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
        $icfunc = "imagecreatefrom" . $format;
        if (!function_exists($icfunc)) return false;

        $img = imagecreatetruecolor($width,$height);
        imagefill($img, 0, 0, $rgb);
        $photo = $icfunc($f);
        imagecopyresampled($img, $photo, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
        $res = imagejpeg($img, $out);

        imagedestroy($img);
        imagedestroy($photo);
        return $res;
    }

    //---------------ВОЗВРАТ ФОТО-------------------------------------//
    public static function GetFoto($id){
        $f_arr=array();
        //Путь к файлу
        $path = $GLOBALS['config']['path'].$id;
        //Открытие
        $h = @opendir($path);
        if($h!==false){
            while (false !== ($file = readdir($h))) {
                if($file!=='.' and $file!=='..'){
                    // Проверка существования файла
                    if(is_file($path."/".$file)===true){
                        // возврат массива файлов
                        array_push($f_arr, $path . "/pr/" . $file);
                    }
                }
            }
            closedir($h);
        }
        return $f_arr;
    }

//------------ДОБАВИТЬ ФОТО---------------------------------------//
    public static function AddFotos(){
        // Путь к папке пользователя
        $path = $GLOBALS['config']['path'].Session::get('user');
        $fotos = array();
        $res = null; $res_pev = null;
        $rdt = null;

        // Количество загруженных фото
        $f_c = count($_FILES['rfile']['name']);
        for ($i=0; $i<$f_c; $i++) {
            if($_FILES['rfile']['error'][$i] != 1){
                $rdt = date("YmdHms");
                // Если папки нет -- создаем папку
                if(!file_exists($path)){mkdir($path,0777);}
                $output = $path.'/'.$rdt.$_FILES['rfile']['name'][$i];

                // Если папки превью нет -- создаем папку превью
                if(!file_exists($path.'/pr')){mkdir($path.'/pr',0777);}
                $outpr = $path.'/pr/'.$rdt.$_FILES['rfile']['name'][$i];

                // Переноси файлы на сервер
                $res_up = move_uploaded_file($_FILES['rfile']['tmp_name'][$i], $output);
                if($res_up) {
                    //Делаем превью
                    $res_pev = Foto::makePreview($output, $outpr);
                    $res = $res_pev ? 'Файл успешно загружен: '.$_FILES['rfile']['name'][$i] : 'Ошибка загрузки файла: ' . $_FILES['rfile']['name'][$i];
                    if ($res){array_push($fotos, $res);}
                }
            }else{
                array_push($fotos, 'Ошибка загрузки файла: '.$_FILES['rfile']['name'][$i]);
            }
        }
        return $fotos;
    }

//-------------УДАЛЕНИЕ ФОТО--------------------------------------//
    public static function delFoto($f){
        $resf = false; $ires = 0;

        //Путь к файлу из пути к превью
        $rf = str_replace("pr/","",$f);
        // Если файлы существуют
        if (file_exists($f) && file_exists($rf)){
            // Удаление файлов
            $resf = @unlink($f);
            $resf = @unlink($rf);
        }
        $ires = $resf ? 1 : 0;

        return $ires;
    }

}
?>