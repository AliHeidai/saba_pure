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
   $get_sql="SELECT * FROM accessitem";
   $res=$db->ArrayQuery($get_sql);
   $htm='<table class="table table-striped  table-bordered "   style=" width:100%;background: #fff;margin: 2rem auto;" id="menu_setting_tbl">
  <thead style="color:#fff;">
    <thead>
            <tr>
                <th>ردیف</th>
                <th>نام منو</th>
                <th>فعال/غیر فعال</th>
                <th>گروه منو </th>
            </tr>
        </thead>
     
       
       <tbody>';
       $counter=1;
       foreach($res as $key=>$value){
        $htm.="
       
       <tr>
        <td>
          {$counter}
        </td>
        <td>
          {$value['accessNameFa']}
        </td>
        <td>
          <input type='checkbox'".($value['accessType']==2 ?'checked':''). "name='chk_'.$counter />
        </td>
        <td>
       {$value['group_fa']}
        </td>
       </tr>";
       $counter++;
       }
       $htm.="</tbody></table>";

       
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