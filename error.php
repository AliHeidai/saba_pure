<?php
    $res = array();
    $res["data"] = 'شما قادر به ایجاد تغییر در پایگاه داده نمی باشید';
    $res["res"]  = "false";
    $result = json_encode($res,true);
    die($result);
    exit;


?>