<?php

function fetch_account_from_paya(){
    $sql=new ConnectSql();
    $result=$sql->sql_ArrayQuery('SELECT * from jobs');
    $res = array();
    $res["data"] = 0;
    $res["res"]  = "true";
    $result = json_encode($res,true);
    die($result);
    exit;
}

function compare_saba_paya(){

}