<?php
if (!isset($_SESSION)) {
    session_start();
    session_regenerate_id(true);
    
}
    date_default_timezone_set("Asia/Tehran");
    require_once str_replace('\\', '/', dirname(__FILE__)) . '/config.php';
    require_once ROOT . 'inc/class.DBi.php';
    require_once ROOT . 'inc/class.Utility.php';
    require_once ROOT . 'inc/class.acm.php';
    $err_msg;
    $login=0;
    if(!empty($_POST['login_btn_f']) && $_POST['randcheck']==$_SESSION['rand']){
    $db = new DBi();
    $ut = new Utility();
    $acm = new acm();
    $username = $db->Escape($_POST['username']);
	$client_mac=$ut->get_client_mac_address();
    $super_admin_users=$ut->get_full_access_users(15);
	$full_access_users=$ut->get_full_access_users(3);
    $users_restric=$ut->get_full_access_users(26);
    $query = "SELECT `RowID`,`username`,`pass`,`fname`,`lname`,`IsEnable`,`IsAdmin`,`gender`,`phone` FROM `users` WHERE `username`='{$username}' AND `IsEnable`=1 ";
    $userInfo = $db->ArrayQuery($query);
    if(count($userInfo) == 1){
        $ut->createJsonFile(array("userID"=>$userInfo[0]['RowID'],"ip"=>$_SERVER['REMOTE_ADDR']));
		$user_current_ip = $_SERVER['REMOTE_ADDR']=="::1"?"127.0.0.1":$_SERVER['REMOTE_ADDR'];
		$is_user_ip = 0;
        $restric=in_array($userInfo[0]['RowID'],$users_restric)?1:0;
       
		$is_full_access_user = in_array($userInfo[0]['RowID'],$full_access_users)?1:0;
        if($is_full_access_user == 1){
            $is_user_ip = 1;
        }
        else{
            $get_ip_sql = "SELECT `pc_ip` FROM `user_pc_info` WHERE `userID`={$userInfo[0]['RowID']}";
            $ip_result = $db->ArrayQuery($get_ip_sql);
            foreach($ip_result as $ip_index =>$ip_value_array){
                if($user_current_ip==$ip_value_array['pc_ip']){
                    $is_user_ip=1;
                    continue;
                }
            }
        }
        //******************************************** */
       // $is_user_ip=1;
       if($restric==1){
            $err_msg="!!!!!کاربری شما محدود شده است ". PHP_EOL;
            die('<p style="
                    color: red;
                    margin: 0 auto;
                    padding: 10px;
                    text-align: center;
                    font-size: 3rem;
                    font-family:tahoma;
                    background: #80808061;
                    height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    ">'.$err_msg.'</p>'
                );
            
        }
        if($is_user_ip==1)
        {

            $user_one_step_login= $ut->get_full_access_users(9);
            $hash = $userInfo[0]['pass'];
			$_SESSION['token2']=$hash;
            if(password_verify($_POST['Pass'], $hash)){
                $_SESSION['username'] = $userInfo[0]['username'];
                $_SESSION['userid'] = $userInfo[0]['RowID'];
                $_SESSION['name'] = $userInfo[0]['fname'] . " " . $userInfo[0]['lname'];
                $_SESSION['gender'] = $userInfo[0]['gender'];
                $_SESSION['IsAdmin'] = $userInfo[0]['IsAdmin'];
                $_SESSION['phone'] = $userInfo[0]['phone'];
                $_SESSION['adminAuten'] = '';
                $_SESSION['userAuten'] = '';
				//------------------------------------------------------
				$temp=($userInfo[0]['RowID']*$userInfo[0]['RowID'])+41;
                $cookie_name = "userID";
                $cookie_value = $temp;
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
				
				//----------------------------------------------------
                if (intval($_SESSION['IsAdmin']) === 1) 
                { // زمانی که کاربر ادمین مراجعه نماید.
                    $_SESSION['adminAuten'] = "ok";
					$is_one_setp_user = in_array($_SESSION['userid'],$user_one_step_login)?1:0;
                    //if (intval($userInfo[0]['RowID']) == 96 || intval($userInfo[0]['RowID']) == 67){
					//$is_one_setp_user=1;
					
                }
                else
                {
                    $_SESSION['userAuten'] = "ok";
                    $ut->Redirect(ADDR . 'index.php');
                }
                $login=1;
                if($is_one_setp_user==1){
                    $ut->Redirect(ADDR . 'administrator.php');
                }
            }
            else
            { // پسورد اشتباه است
                $userid = $userInfo[0]['RowID'];
                if(!empty($_SERVER['HTTP_CLIENT_IP']))
                 {
                //check ip from share internet
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                }
                elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                {
                //to check ip is pass from proxy
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                 }
                 else
                 {
                    $ip = $_SERVER['REMOTE_ADDR'];
                 }
                $ts = time();
                $pswdsql = "INSERT INTO `pswderror` (`userid`,`ts`,`ip`)VALUES({$userid},{$ts},'{$ip}') ";
                $db->Query($pswdsql);
                $sdate = Date("Y-m-d") . " 00:00:00";
                $stime = strtotime($sdate);
                $delSql = "DELETE FROM `pswderror` WHERE ts<" . $stime;
                $db->Query($delSql);
                $edate = Date("Y-m-d") . " 23:59:59";
                $etime = strtotime($edate);
                $checkSql = "SELECT COUNT(`RowID`) AS cr FROM pswderror WHERE `userid`=$userid AND ts>=" . $stime . " AND ts<=" . $etime;
                $cr = $db->ArrayQuery($checkSql);
                $cr = $cr[0]['cr'];
                 $err_msg="نام کاربری یا کلمه عبور را اشتباه وارد نموده اید !!!" . PHP_EOL;
            }
        }
        else{
            $err_msg="خطای دسترسی با واحد فناوری اطلاعات تماس بگیرید" . PHP_EOL;
        }
    }
    else
    { // یا کاربر غیرفعال است و یا نام کاربری اشتباه است
        $sql = "SELECT `RowID` FROM `users` WHERE `username`='{$username}' ";
        $userid = $db->ArrayQuery($sql);
        if(count($userid) == 1){
            echo "<div class='alert alert-danger' style='margin: 20px 50px;text-align: right;font-size: 15px;font-weight: bold;font-family: vazir-bold;' >شما کاربر غیر فعال هستید !!!</div>" . PHP_EOL;
        }else{
           $err_msg="نام کاربری یا کلمه عبور را اشتباه وارد نموده اید !!!" . PHP_EOL;
        }
    }
}

if(!empty($_POST['login_btn_f_2']) && $_SESSION['rand2']==$_POST['recheck_2'])
{

    $db=new DBi();
        $ut=new Utility();
    $userPhoneNumber = $db->Escape($_POST['userPhoneNumber']);
    $userCodeMelli = $db->Escape($_POST['userCodeMelli']);
    $userFatherName = $db->Escape($_POST['userFatherName']);
    $userBirthDay = $db->Escape($_POST['userBirthDay']);
    $handler_user_info_array=[$userPhoneNumber,$userCodeMelli,$userFatherName,$userBirthDay];
    $user_info_array=[];
    foreach($handler_user_info_array as $info){
        if(!empty($info)){
            $user_info_array[]=$info;
        }
    }
    $flag=false;
    if(count($user_info_array)==2){
        $flag = true;
    }
   
    if (strlen(trim($userPhoneNumber)) > 0){
        $query = "SELECT `RowID`,`IsEnable` FROM `users` WHERE `phone`='{$userPhoneNumber}' AND `RowID`={$_SESSION['userid']} AND `IsEnable`=1 ";
        $res = $db->ArrayQuery($query);
        $IsEnable = $res[0]['IsEnable'];
        if (count($res) <= 0) {
            $flag = false;
        }
    }
    if (strlen(trim($userCodeMelli)) > 0){
        $query = "SELECT `RowID`,`IsEnable` FROM `users` WHERE `codeMelli`='{$userCodeMelli}' AND `RowID`={$_SESSION['userid']} AND `IsEnable`=1 ";
        $res1 = $db->ArrayQuery($query);
        $IsEnable = $res1[0]['IsEnable'];
        if (count($res1) <= 0) {
            $flag = false;
        }
    }
    if (strlen(trim($userFatherName)) > 0){
        $query = "SELECT `RowID`,`IsEnable` FROM `users` WHERE `fatherName`='{$userFatherName}' AND `RowID`={$_SESSION['userid']} AND `IsEnable`=1 ";
        $res2 = $db->ArrayQuery($query);
        $IsEnable = $res2[0]['IsEnable'];
        if (count($res2) <= 0) {
            $flag = false;
        }
    }
    if (strlen(trim($userBirthDay)) > 0){
        $query = "SELECT `RowID`,`IsEnable` FROM `users` WHERE `birthYear`={$userBirthDay} AND `RowID`={$_SESSION['userid']} AND `IsEnable`=1 ";
        $res3 = $db->ArrayQuery($query);
        $IsEnable = $res3[0]['IsEnable'];
        if (count($res3) <= 0) {
            $flag = false;
        }
    }

    if ($flag) {
        if (intval($_SESSION['IsAdmin']) === 1) { // زمانی که کاربر ادمین مراجعه نماید.
            $ut->Redirect(ADDR . 'administrator.php');
        } 
    }else 
    { // موارد امنیتی اشتباه وارد شده است
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
            $err_msg_2="موارد امنیتی اشتباه وارد شده است !!!" . PHP_EOL;
           $login=1;
    }
}
else{
   // $login=0;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Majid Ebrahimi">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>سامانه سبا</title>

    <link rel="icon" href="images/favicon.ico"/>

    <link href="<?php echo ADDR ?>css/bootstrap.css" rel="stylesheet" crossorigin="anonymous">
    <link href="<?php echo ADDR ?>css/bootstrap.rtl.css" rel="stylesheet" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="<?php echo ADDR ?>css/particles.css" rel="stylesheet" type="text/css" crossorigin="anonymous">
    <link href="<?php echo ADDR ?>css/solid.css" rel="stylesheet" type="text/css" crossorigin="anonymous">
    <link href="<?php echo ADDR ?>css/fontawesome.css" rel="stylesheet" type="text/css" crossorigin="anonymous">
    <link href="<?php echo ADDR ?>css/style.css" rel="stylesheet" type="text/css" crossorigin="anonymous">
    <link href="<?php echo ADDR ?>css/signin.css" rel="stylesheet" type="text/css" crossorigin="anonymous">
    <link href="<?php echo ADDR ?>css/sticky-footer-navbar.css" rel="stylesheet" type="text/css" crossorigin="anonymous">
    <script src="<?php echo ADDR ?>js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="<?php echo ADDR ?>js/bootstrap.js" crossorigin="anonymous"></script>
<style>
    .container_login{
        position:relative;
        min-height:90vh ;
        overflow:hidden;
    }
    .form-wrapping{
        min-width:350px; 
         position: absolute;
         top: 50%;
         left: 50%;
         transform: translate(-50%, -50%);
         background:#fff;
         border-radius:10px;
         box-shadow:5px 5px 5px gray;
         max-height:100%;
         min-height: auto;
    }
    .login-form{
        width:100%;
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
        padding:0;
    }
    .login_form-group{
        margin-block:10px;
        width:80%;
        position:relative;
        height:45px;
        padding: 0;
    }
    .login_form-group input{
        width:100%;
        height:100%;
        font-family:IranSans;
        font-size:1rem;
        box-sizing:border-box;
        border-radius:10px;
        border:2px solid #c3b9b9;
        outline:none;
    }
    .login_form-group input[type="text"],.login_form-group input[type="password"]{
        background: transparent;
    }
    .login_form-group input[type='submit']{
        width:100%;
        height:100%;
        font-family:IranSans;
        border-radius:10px;
        /* box-shadow:2px 2px 4px #c3b9b9; */
        margin-top:20px;
    }
    .login-btn{
        /* background: linear-gradient(to right,blue,red); */
        color:#fff;
        transition:all 1s;
    }
    .login-btn-2:hover{
        color:#fff;
        transform: scale(1.05);
    }
    .login-btn-2{
        color:#e5d9d9;
        transition:all 0.2s;
    }
    .login-btn:hover{
        /* background: linear-gradient(to left,green,red); */
        transform: scale(1.05);
    }

    .login_form-group label{
        position:absolute;
        transform: translateY(50%);
        font-family:IranSans;
        font-size:12px;
        right:10px;
        transition:0.5s;
        padding-inline:10px;
        z-index:-1;
        color:gray;
    }
     .login_form-group input[type="text"]:focus,.login_form-group input[type="Password"]:focus,
     .login_form-group input[type="text"]:valid,.login_form-group input[type="Password"]:valid{
        border-bottom:3px solid blue !important;
        background: transparent !important;
        text-align: center !important;
        transition: 1s !important;
       
     }
     .login_form-group input[type="text"]:required,.login_form-group input[type="Password"]:required{
        /* border-bottom:3px solid red; */
     }
     /* .login_form-group input[type="text"]:not(:empty),.login_form-group input[type="Password"]:valid{
        border-bottom:1px solid green;
     } */
    .login_form-group input:focus~label,
    .login_form-group input:valid~label{
        transform: translateY(-50%);
        background:#fff;
        font-size:12px;
        right:5%;
        padding-inline:5px;
        width: auto;
        text-align: center;
        color:green;
        z-index: 1;
    }
    .icon{
        width:100%;
        height:auto;
    }
    .login_error{
        font-family:IranSans;
        color:red;
        font-size:12px;
        width: 100%;
        text-align: right;
        padding-inline: 5px;
        transition: 1s;

    }
    .login-page-logo img{
        width: 180px;
        height: auto;
        /* box-shadow: 3px 5px 14px yellow;
        border-radius:10px 0; */
    }
    .login-title{
        font-family: IranSans;
        background: linear-gradient(to right, blue, red);
        -webkit-text-fill-color: transparent;
        -webkit-background-clip: text;
    }
    /** */
    .digital-clock{
    position: absolute;
    top: 20%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #17d4fe;
    font-size: 60px;
    letter-spacing: 7px;
}
.analog-clock{
    width: 100px;
    height: 100px;
    display: flex;
    justify-content: center;
    align-items: center;
    background: url('<?php echo ADDR ?>/images/clock.png');
    background-size: cover;
    border: 4px solid #091921; 
    border-radius: 50%; 
    box-shadow: 0 -15px 15px rgba(255,255,255, 0.05),
                inset 0 -15px 15px rgba(255,255,255, 0.05), 
                0 15px 15px rgba(0,0,0, 0.3),
                inset 0 15px 15px rgba(0,0,0, 0.3);
    position:absolute;
    top:4rem;
    right:1.5rem;

    
}
.analog-clock::before{
    content: '';
    position: absolute;
    width: 10px;
    height: 10px;
    background: #000;
    border-radius: 50%;
    z-index: 20;
}
.analog-clock .hour, 
.analog-clock .minute,
.analog-clock .second{
    position: absolute;
}

.analog-clock .hour,
#h-arrow{
    width: 70px;
    height: 70px;
}
.analog-clock .minute,
#m-arrow{
    width: 80px;
    height: 80px;
}
.analog-clock .second,
#s-arrow{
    width: 90px;
    height: 90px;
}
#h-arrow,
#m-arrow,
#s-arrow{
    display: flex;
    justify-content: center;
    position: absolute;
}
#h-arrow::before{
    content: '';
    position: absolute;
    width: 5px;
    height: 40px;
    background: #000;
    z-index: 10;
    border-radius: 6px;
}
#m-arrow::before{
    content: '';
    position: absolute;
    width: 4px;
    height: 40px;
    background:gray;
    z-index: 11;
    border-radius: 6px;
}
#s-arrow::before{
    content: '';
    position: absolute;
    width: 2px;
    height: 50px;
    background: brown;
    z-index: 11;
    border-radius: 6px;
}
.dropdown {
  position: relative;
  display: inline-block;
  margin:10px;

}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    padding: 12px;
    z-index: 1;
    font-size:12px;
    left:10px;
    width: 250px;

}
.phone-logo{
    width: 60px;
    height:auto;
}

.dropdown:hover .dropdown-content {
  display: block;
}
.menu-link{
    width: 100%;
    display:flex;
    justify-content:space-between;
}

.menu-link span{
    color:#000;
}
.dropdown-content li:hover{
    background:rgba(128,128,128,0.3);
   
}
#LoginBody {
    background-color: rgba(7, 7, 32, 0.87);
    height: 100vh;
    overflow-y: scroll;
}

     
</style>
</head>
<body id="LoginBody">
<!-- <div class="card" style="text-align: right;background-color: #00000017;"> -->
<!---->
    <!-- <div class="digital-clock" id="digital-clock"></div> -->

    <div class="analog-clock">
        <div class="hour">
            <div id="h-arrow"></div>
        </div>

        <div class="minute">
            <div id="m-arrow"></div>
        </div>

        <div class="second">
            <div id="s-arrow"></div>
        </div>
    </div>

    <!-- از خط 28 به بعد کدهای جاوااسکریپت قرار دارند-->
    
    <script>
        const deg = 6;
        const horArrow = document.querySelector("#h-arrow");
        const minArrow = document.querySelector("#m-arrow");
        const secArrow = document.querySelector("#s-arrow");
        const digitalClock = document.querySelector("#digital-clock");

        setInterval(() => {
            let time = new Date();
            let h = time.getHours(); // خواندن ساعت از 0 تا 23
            let m = time.getMinutes(); // خواندن دقیقه از 0 تا 59
            let s = time.getSeconds(); // خواندن ثانیه از 0 تا 59

            let hDgree = h * 30;
            let mDgree = m * deg;
            let sDgree = s * deg;

            horArrow.style.transform = `rotateZ(${hDgree + mDgree / 12
                }deg)`;
            minArrow.style.transform = `rotateZ(${mDgree}deg)`;
            secArrow.style.transform = `rotateZ(${sDgree}deg)`;
            showTime(h, m, s);
        });

        function showTime(h, m, s) {
            let midnight = "AM";
            if (h == 0) {
                h = 12;
            }
            if (h > 12) {
                h = h - 12;
                midnight = "PM";
            }

            h = h < 10 ? "0" + h : h;
            m = m < 10 ? "0" + m : m;
            s = s < 10 ? "0" + s : s;

            let timeString = h + ":" + m + ":" + s + " " + midnight;
            //digitalClock.innerText = timeString;
        }
    </script>


<!---->
    <!-- <div class="card-body" style="padding: 10px;font-family:IranSans;font-size: 20px;color: #FFFFFF;margin-right: 10px;width:100%;">
   
		<a class="mt-0 ml-3 btn" href="phone.php" target="_blank" style="float:left;font-family: vazir-bold;font-size: 20px;color: #fff;text-decoration: none;"> شماره  تلفن های داخلی <i class="fa fa-phone"></i></a>
    </div> -->
<div class="dropdown">
<span class=" btn"style="
    font-family: IranSans;
    font-size: 14px;
    color: blue;
    box-shadow: 3px 3px 3px gray;background:#d7dce3">اطلاعات تماس</span>
<ul class="dropdown-content">
    <li style="list-style:none;font-size:12px;width:100%;padding:10px">
    <a class='menu-link' style="font-size:12px" href="<?php echo  ADDR ?>frp_phone.php" target="_blank">
        <span>
            <img class='phone-logo' src="<?php echo ADDR; ?>images/logo3.jpg">
        </span>
        <span>
         فورج فلزات رنگین پارسیان
        </span>
        </a>
    </li>
    <li style="list-style:none;font-size:12px;width:100%;padding:10px">
    <a class='menu-link' href="<?php echo  ADDR ?>vira_phone.php" style="font-size:12px">
        <span>
        <img class='phone-logo' src="<?php echo ADDR; ?>images/Layout-Logo.png">
</span>
<span>
         ویرا تجارت نوین ابرش</span>
         </a>
    </li>
</ul>
</div>
</div>
<div>
    <div  class="container_login">
            <div class="form-wrapping">
                <form method="post" class="login-form" id="login_form_1" >
                <a class="login-page-logo" href="#"><img src="images/logo3.png"></a>
                    <div class="text-center icon">
                        <!-- <h5  class="login-title" >ورود به سامانه سبا</h5> -->
                    </div> 
<?php if($login==0) 
{?>
                
<?php
    $rand=rand();
    $_SESSION['rand']=$rand;
?>
    <input type="hidden" value="<?php echo $rand; ?>" name="randcheck" />

<div class="login_form-group" >
        <input autocomplete="off" required type="text" id="usernameInput" name="username" lang="en" data-index="1" required   />
        <label>نام کاربری</label>
    <!-- </div> -->
</div>
<div class="login_form-group" >
        <input autocomplete="off" required type="Password" id="PassInput" name="Pass" lang="en" data-index="2"  />
        <label >رمز عبور</label>
    <!-- </div> -->
</div>
<div class="login_form-group">
    <input type="submit" name="login_btn_f" data-index="3" value="تایید" class="btn btn-success login-btn">
</div>
<div class="login_form-group" style="text-align: center;padding-top: 2rem">
    <a class="forget_user_pass" href="#" onclick="open_recover_user_pass()">نام کاربری / رمز عبور را فراموش کرده ام</a>
    <!-- <a class="forget_user_pass" href="#" onclick="open_recover_user_pass()">*</a> -->
    <p class="recover_msg p-2"></p>
</div>
<div class="login_form-group" style="height:auto">
    <?php
        if(!empty($err_msg)){
        echo '<p class="login_error p-2">'.$err_msg.' </p>';
        }
    ?>
</div>
                
<?php 
} 
else
{
    if ($login==1) 
    {
        $rnd1 = rand(1,4);
        do {
            $rnd2 = rand(1,4);
        } while ($rnd1 == $rnd2);
        $htm = '';
        switch (intval($rnd1))
        {
            case 1:
                $htm .='<div class="login_form-group" >';
                $htm.='<input data-index="1" autocomplete="off" type="text" id="userPhoneNumber" name="userPhoneNumber"  required autofocus>';
                $htm.='<label > شماره همراه (بدون صفر اول)</label>';
                $htm.='</div>';

                break;
            case 2:
                $htm .='<div class="login_form-group" >';
                $htm.='<input data-index="2" autocomplete="off"    type="text" id="userCodeMelli" name="userCodeMelli" required autofocus>';
                $htm.='<label >کد ملی</label>';
                $htm.='</div>';

                break;
            case 3:
                $htm .='<div class="login_form-group" >';
                $htm.='<input data-index="1" autocomplete="off" type="text"  lang="fa" id="userFatherName" name="userFatherName" required autofocus>';
                $htm.='<label >نام پدر</label>';
                $htm.='</div>';

                break;
            case 4:
                $htm .='<div class="login_form-group" >';
                $htm.='<input data-index="2" autocomplete="off" type="text" id="userBirthDay" name="userBirthDay" required autofocus>';
                $htm.='<label >سال تولد</label>';
                $htm.='</div>';

                break;
        }

        switch (intval($rnd2))
        {

            case 1:
            $htm .='<div class="login_form-group" >';
            $htm.='<input  data-index="1" autocomplete="off" type="text" id="userPhoneNumber" name="userPhoneNumber" required autofocus>';
            $htm.='<label > شماره همراه (بدون صفر اول)</label>';
            $htm.='</div>';

            break;
        case 2:
            $htm .='<div class="login_form-group" >';
            $htm.='<input data-index="2" autocomplete="off"    type="text" id="userCodeMelli" name="userCodeMelli" required autofocus>';
            $htm.='<label >کد ملی</label>';
            $htm.='</div>';

            break;
        case 3:
            $htm .='<div class="login_form-group" >';
            $htm.='<input data-index="1" autocomplete="off"   type="text" id="userFatherName" name="userFatherName" required autofocus>';
            $htm.='<label >نام پدر</label>';
            $htm.='</div>';

            break;
        case 4:
            $htm .='<div class="login_form-group" >';
            $htm.='<input data-index="2" autocomplete="off"  type="text" id="userBirthDay" name="userBirthDay" required autofocus>';
            $htm.='<label >سال تولد</label>';
            $htm.='</div>';

            break;
        }

        echo  $htm;

        ?>
        <div class="login_form-group">
            <?php $rand2=rand();
                $_SESSION['rand2']=$rand2;
            ?>
                <input type="hidden" id="recheck_2" name="recheck_2" value="<?php echo $rand2;?>">
                <input type="submit"  name="login_btn_f_2" data-index="3"  value="ورود" class="login-btn-2 btn btn-success" style="width:49%">
                <input type="submit" name="abort" data-index="4" value="انصراف" class="login-btn-2 btn btn-danger" onclick="go_to_login(event)" style="width:49%">
            </div>
            <div class="login_form-group">
                <?php
                    if(!empty($err_msg_2)){
                        echo '<p class="login_error">'.$err_msg_2.' </p>';
                        $login=1;
                    }
                    else{
                        $login=0;
                    }
                }
                ?>
            </div>
<?php 
} ?>
</form>
    </div>
</div>
</div>

<script src="<?php echo ADDR ?>js/particles.js" crossorigin="anonymous"></script>
<script src="<?php echo ADDR ?>js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script src="<?php echo ADDR ?>js/stats.js" crossorigin="anonymous"></script>
<script src="<?php echo ADDR ?>js/app.js" crossorigin="anonymous"></script>
<script src="<?php echo ADDR ?>js/mjs.js" crossorigin="anonymous"></script>
<script src="<?php echo ADDR ?>js/custom_ui.js" crossorigin="anonymous"></script>
<script src="<?php echo ADDR ?>js/sweetalert2.js" crossorigin="anonymous"></script>
<script>
    $('#login_form_1').on('keydown', 'input', function (event) {
        if (event.which == 13) {
            event.preventDefault();
            var $this = $(event.target);
            var index = parseFloat($this.attr('data-index'));
            var input_type=$('[data-index="' + (index + 1).toString() + '"]').attr('type');
            if(input_type=='submit' || input_type=='button' ){
                $('[data-index="' + (index+1).toString() + '"]').click();
            }
            else{
               
                $('[data-index="' + (index + 1).toString() + '"]').focus();
            }
        }
    });
 
   function go_to_login(event){
    event.preventDefault()
    window.location.href = "<?php echo ADDR?>login.php";
   }

</script>

</body>
</html>