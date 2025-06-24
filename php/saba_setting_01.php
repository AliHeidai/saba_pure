<?php
// header('Content-type: application/json');
// if(!isset($_SESSION)){
//     session_start();
//     session_regenerate_id(true);
// }


//     $action = $_POST['action'];

//     if(!strlen(trim($action))){
//         die("access denied1");
//         exit;
//     }else{
//         call_user_func($action);

//     }

function get_saba_settings(){
   $db=new DBi();
   $get_sql="SELECT * FROM menu_crud_setting";
   $res=$db->ArrayQuery($get_sql);
   $htm='<table class="table" style=" width: 300px;background: #fff;margin: 2rem auto;">
     <tr>
       <th scope="row">1</th>
       <td>قابلیت ویرایش رکورد</td>
       <td><input type="checkbox"'.($res[0]['update']==1?'checked' :'').' onclick="set_crud_enable(this,\'update\')"></td>
     </tr>
      <tr>
       <th scope="row">2</th>
       <td>قابلیت ایجاد رکورد</td>
       <td><input type="checkbox"' .($res[0]['create']==1?'checked' :'').' onclick="set_crud_enable(this,\'create\')"></td>
     </tr>
      <tr>
       <th scope="row">3</th>
       <td>قابلیت حذف رکورد</td>
       <td><input type="checkbox" '.($res[0]['delete']==1?'checked' :'').' onclick="set_crud_enable(this,\'delete\')"></td>
     </tr>
     
 </table>';
 $res = array();
    $res["data"] = $htm;
    $res["res"]  = "true";
    $result = json_encode($res,true);
    die($result);
    exit;
}

function set_crud_enable(){
    extract($_POST);
    $db=new DBi();
    $sql="UPDATE `menu_crud_setting` set `{$crud_type}`=$crud_status ";
    $res=$db->Query($sql,1);
    if($res){
        get_saba_settings(); 
    }
    
}