<?php
require_once 'core/init.php';

//**************************************//
$param = Input::getPost('param');
switch ($param) {
    case "reguser" : if(Session::exists('user')){$us = new User(); $us->Registration(); echo json_encode($us->error); unset($us);} break;
    case "allfoto" : if(Session::exists('user')){ echo json_encode(Foto::GetFoto(Session::get('user')));} break;
    case "insfoto" : if(Session::exists('user')){ echo json_encode(Foto::AddFotos());} break;
    case "delfoto" : if(Session::exists('user')){ echo json_encode(Foto::delFoto(Input::getPost('foto')));} break;
    case "" : break;
//**************************************//

}
