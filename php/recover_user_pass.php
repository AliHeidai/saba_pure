<?php
header('Content-type: application/json');
if(!isset($_SESSION)){
    session_start();
    session_regenerate_id(true);
}

//+++++++++++++++++++++ include ++++++++++++++++++++++++
require_once '../config.php';
function AutoLoad($className) {
    if(file_exists(ROOT .'inc/class.' . $className . '.php')) {
        require_once ROOT .'inc/class.' . $className . '.php';
    }
}
date_default_timezone_set("Asia/Tehran");
spl_autoload_register('AutoLoad');
require_once ROOT.'inc/jdf.php';
require_once ROOT.'inc/NumToWord_Fa.php';
require_once ROOT . 'inc/PHPExcel.php';
require_once ROOT . 'vendor/autoload.php';
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++

    $action = $_POST['action'];

    if(!strlen(trim($action))){
        die("access denied1");
        exit;
    }else{
        call_user_func($action);

    }

function listenLogin(){
    
    $db=new DBi();
    $sql="SELECT `user_id`,`ip` FROM `users_login_info` where user_id={$_REQUEST['userID']}";
    $res=$db->ArrayQuery($sql);
   
    $r=json_encode($res[0]);
    die($r);
}

 function recover_user_pass(){
    $ut=new Utility();
    $db=new DBi();
    $massage_array=array();
    $new_password=rand(10000,99999);
    $new_hash_password=$ut->MackHash($new_password);
    $mobile=$_POST['mobile'];
    $mobile=$_POST['mobile'];
    // if(substr($mobile,0,1)==0){
    //     $mobile=str_replace()
    // }
    $get_username="SELECT username FROM users where phone ={$_POST['mobile']}";
    
    $res=$db->ArrayQuery($get_username);
    if(count($res)>0){
//      if(count($res)>1){
//          $massage_array['msg_type']="error";
//          $massage_array['message']="شماره همراه برای بیش از یک کاربر ثبت شده است ";
//      }
//      else{
   
            $username=$res[0]['username'];
            $sms = new SMS();
            $message = array('server'=>SERVER,'user'=>$username,"pass"=>$new_password);
            $pattern_json = json_encode($message);
            $pattern_code = "y5qiinp7p2k67cm";
            $sms_res = $sms->send_by_pattern_api($_POST['mobile'],$pattern_code,$pattern_json);

           // $sms_res=$sms->send_recovery_password([$_POST['mobile']],$message);
          if(empty($sms_res)) {
              $massage_array['msg_type'] = "error";
              $massage_array['message'] = "خطا در ارسال اطلاعات ";
          }
          elseif(intval($sms_res)>0)
          {
                $massage_array['msg_type'] = "ok";
                $massage_array['message'] = "مشخصات کاربری به شماره ثبت شده پیامک گردید ";
                $update_sql="UPDATE users SET pass='{$new_hash_password}' WHERE username='{$username}'";
              
                $res=$db->Query($update_sql);
                if($res){
                    unset($_SESSION['userid']);
                }
          }
     // }
    }
    else{
        $massage_array['msg_type']="error";
        $massage_array['message']="شماره همراه اشتباه است یا در سامانه ثبت نشده است ";
    }

    die(json_encode($massage_array));
 }