<?php
# ����������� ����� ��� ������ � ��������� �������
Class Input{
    # ������� POST ��. ������
    public static function getPost($item){
       $res = null;
       # �������� �� ������������� $_POST[$item]
       if (isset($_POST[$item])){
            $res = $_POST[$item];
       }
       return $res;
    }
}
?>