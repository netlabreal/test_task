<?php
# ����������� ������������������ �����
require_once 'inc/core/init.php';
# �������� ���������� ����������
Session::delete("user");Session::delete("email");
# ������� � �����������
header('Location: /login.php');
exit;
?>