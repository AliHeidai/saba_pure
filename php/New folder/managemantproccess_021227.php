<?php
/**
 * Created by PhpStorm.
 * User: MajidEbrahimi
 * Date: 3/6/2018
 * Time: 11:53
 */

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
if(intval($_SESSION['IsAdmin'])!= 1 || !isset($_SESSION['username']) || strlen(trim($_SESSION['username']))==0 || !intval($_SESSION['userid'])){
    die("access denied");
    exit;
}else{
    $action = $_POST['action'];
    error_log($action);
    if(!strlen(trim($action))){
        die("access denied");
        exit;
    }else{
        call_user_func($action);
/*        $acm = new acm();
        if ($acm->hasAccess('commentManagement')) {
            $db = new DBi();
            $ut = new Utility();
            $sql = "SELECT `lastOnline` FROM `users` WHERE `RowID`={$_SESSION['userid']}";
            $rst = $db->ArrayQuery($sql);
            $endTime = strtotime("+1 hour", strtotime($rst[0]['lastOnline']));
            $newTime = date('H:i:s', $endTime);
            $currentTime = date('H:i:s');
            if ($currentTime > $newTime) {
                die("access denied");
                exit;
            } else {
                $cTime = date('H:i:s');
                $sql1 = "UPDATE `users` SET `lastOnline`='{$cTime}' WHERE `RowID`={$_SESSION['userid']}";
                $db->Query($sql1);
                call_user_func($action);
            }
        }else{
            call_user_func($action);
        }*/
    }
}
//+++++++++++++++++++++++++++++ قراردادهای رو به اتمام +++++++++++++++++++++++++++++++

function calculateEndAgreement(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateEndAgreement();
    $out = "true";
    response($res,$out);
    exit;
}

function showDoneAgreements(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showDoneAgreements();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

function doContractExtension(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("access denied");
        exit;
    }
    $events = new Events();
    $myJsonString = $_POST['myJsonString'];
    $myJsonString = json_decode($myJsonString);
    $res = $events->doContractExtension($myJsonString);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++ هشدار سررسید پرداخت +++++++++++++++++++++++++++++++

function calculatePaymentMaturity(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculatePaymentMaturity();
    $out = "true";
    response($res,$out);
    exit;
}

function showPaymentMaturityAlarm(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showPaymentMaturity();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++ هشدار ثبت اظهارنظر برای قرارداد +++++++++++++++++++++++++++++++

function calculateRecordPayComment(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateRecordPayComment();
    $out = "true";
    response($res,$out);
    exit;
}

function showRecordPayCommentAlarmModal(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showRecordPayCommentAlarmModal();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++ هشدار دریافت چک برگشتی +++++++++++++++++++++++++++++++

function calculateReturnedCheck(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateReturnedCheck();
    $out = "true";
    response($res,$out);
    exit;
}

function showReturnedCheckAlarmModal(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showReturnedCheckAlarmModal();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++ بازنگری آئین نامه ها +++++++++++++++++++++++++++++++

function calculateRegulationsAlarm(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateRegulationsAlarm();
    $out = "true";
    response($res,$out);
    exit;
}

function showRegulationsAlarmModal(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showRegulationsAlarmModal();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++ بازنگری بخش نامه ها +++++++++++++++++++++++++++++++

function calculateCircularsAlarm(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateCircularsAlarm();
    $out = "true";
    response($res,$out);
    exit;
}

function showCircularsAlarmModal(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showCircularsAlarmModal();
    if($htm!=false){
       
        $out = "true";
        response($htm,$out);
        exit;
        
    }
}

//+++++++++++++++++++++++++++++ اتمام قراردادهای حقوقی +++++++++++++++++++++++++++++++

function calculateEndLegalContract(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateEndLegalContract();
    $out = "true";
    response($res,$out);
    exit;
}

function showEndLegalContractModal(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showEndLegalContractModal();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++ اتمام معاینه فنی +++++++++++++++++++++++++++++++

function calculateTechnicalDiag(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateTechnicalDiag();
    $out = "true";
    response($res,$out);
    exit;
}

function showEndOfTechnicalDiagModal(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showEndOfTechnicalDiagModal();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++ اتمام بیمه شخص ثالث +++++++++++++++++++++++++++++++

function calculateThirdInsurance(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateThirdInsurance();
    $out = "true";
    response($res,$out);
    exit;
}

function showEndOfThirdInsuranceModal(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showEndOfThirdInsuranceModal();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++ اتمام بیمه بدنه +++++++++++++++++++++++++++++++

function calculateBodyInsurance(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateBodyInsurance();
    $out = "true";
    response($res,$out);
    exit;
}

function showEndOfBodyInsuranceModal(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showEndOfBodyInsuranceModal();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++ بروزرسانی کیلومتر ماشین +++++++++++++++++++++++++++++++

function calculateUpdateKilometer(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateUpdateKilometer();
    $out = "true";
    response($res,$out);
    exit;
}

function showUpdateKilometerAlarmModal(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showUpdateKilometerAlarmModal();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++ تعویض مواد مصرفی ماشین +++++++++++++++++++++++++++++++

function calculateConsumingMaterials(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateConsumingMaterials();
    $out = "true";
    response($res,$out);
    exit;
}

function showConsumingMaterialsModal(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showConsumingMaterialsModal();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++ قرارداد منقضی شده +++++++++++++++++++++++++++++++

function calculateExpiredContract(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateExpiredContract();
    $out = "true";
    response($res,$out);
    exit;
}

function showExpiredContractModal(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showExpiredContractModal();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++ درخواست داده مهندسی جدید +++++++++++++++++++++++++++++++

function calculateRenderingRequest(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateRenderingRequest();
    $out = "true";
    response($res,$out);
    exit;
}

function showRenderingRequestModal(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showRenderingRequestModal();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++ نیازمند تاییدیه چاپ +++++++++++++++++++++++++++++++

function calculateConfirmationAttachLabelRequest(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $events = new Events();
    $res = $events->calculateConfirmationAttachLabelRequest();
    $out = "true";
    response($res,$out);
    exit;
}

function showConfirmationAttachLabelRequestModal(){
    $acm = new acm();
    if(!$acm->hasAccess('eventsManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->showConfirmationAttachLabelRequestModal();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}
//------------------------------------------------------------------------------------
function calculateMeetingJobs(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->calculateMeetingJobs();
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}

function manang_meeting_job_alarm(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("false");
    }
    $event = new Events();
   
    $meeting_id = $event->manageMeetingJobAlarm($_POST['meeting_job_id'],$_POST['is_user']);
   
    if($meeting_id!=false){
        $out = "true";
        response($meeting_id,$out);
        exit;
    }
}
//------------------------------------------------------------------------------------
//-----------------------------------------contract_pay_row_modal-----------------------------
function calculateContractPayRows(){
  
    $acm = new acm();
    if(!$acm->hasAccess('ContractPayFormulaAlarmModalAlarm')){
      
        die("false");
    }
    $event = new Events();
    $contract_pay_rows = $event->calculate_contract_pay_rows();
    if($contract_pay_rows!=false){
        $out = "true";
        response($contract_pay_rows,$out);
        exit;
    }
}

function showContractPayRows(){
  
    $acm = new acm();
    if(!$acm->hasAccess('ContractPayFormulaAlarmModalAlarm')){
      
        die("false");
    }
    $event = new Events();
   
    $contract_pay_rows = $event->showContractPayRows();
    if($contract_pay_rows!=false){
        $out = "true";
        response($contract_pay_rows,$out);
        exit;
    }
}

function final_confirm_contract_pay(){
    $acm=new acm();
    if(!$acm->hasAccess('ContractPayFormulaAlarmModalAlarm')){
        die('false');
    }
    $pay_id =$_POST['pay_id'];
    $event = new Events();
    $result=$event->final_confirm_contract_pay_rows($pay_id);
    if($result)
    {
        $out = "true";
        response($result,$out);
        exit;
    }
    else
    {
        $out = "false";
        $res="هیچ نغییری اعمال نگردید !";
        response($res,$out);
        exit;
    }
    
}
//-----------------------------------------contract_pay_row_modal-----------------------------

function showMeetingJobReportList(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("false");
    }
    $event = new Events();
    $htm = $event->getMeetingJobReportList($_POST['meeting_id'],$_POST['is_user']);
    if($htm!=false){
        $out = "true";
        response($htm,$out);
        exit;
    }
}
//+++++++++++++++++++++++++++++++ مدیریت کاربران +++++++++++++++++++++++++++++++

function userManage(){
    $acm = new acm();
    if(!$acm->hasAccess('userManagement')){
        die("access denied");
        exit;
    }
    $users = new User();
    $htm = $users->getUserManagementHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showUserList(){
    $acm = new acm();
    if(!$acm->hasAccess('userManagement')){
        die("access denied");
        exit;
    }
    $user = new User();
    $list = new Listview();
    $ut = new Utility();
    list($name,$userStatus,$page) = $ut->varCleanInput(array('un','us','page'));
    $res = $user->getUserList($name,$userStatus,$page);
    if($page == 1){
        $_SESSION['calcuser'] = $user->getUserListCountRows($name,$userStatus);
    }
    $totalRows = $_SESSION['calcuser'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام و نام خانوادگی";
    $feilds[$c]['width']="24%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="name";
    $c++;

    $feilds[$c]['title']="نام کاربری";
    $feilds[$c]['width']="24%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="username";
    $c++;

    $feilds[$c]['title']="نوع کاربر";
    $feilds[$c]['width']="24%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="IsAdmin";
    $c++;

    $feilds[$c]['title']="وضعیت کاربر";
    $feilds[$c]['width']="24%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="isEnableTxt";

    $pagerType = 2;
    $listTitle = " تعداد کاربران : ".$totalRows." عدد ";
    $tableID = "userManagementBody-table";
    $jsf = "showUserList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getUserInfo(){
    $uti = new Utility();
    list($uid) = $uti->varCleanInput(array('uid'));
    if(!intval($uid)){
        $res = "شناسه کاربر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $user = new User();
    $res = $user->UserInfo($uid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateUser(){
    $acm = new acm();
    if(!$acm->hasAccess('userManagement')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $user = new User();
    list($uid,$fname,$lname,$username,$ps,$ut,$us,$unitID,$fatherName,$codeMelli,$birthYear,$phone,$postJob,$signature) = $uti->varCleanInput(array('uid','fname','lname','username','ps','ut','us','unitID','fatherName','codeMelli','birthYear','phone','postJob','signature'));
    if(intval($uid) > 0){//edit
        $res = $user->editUser($uid,$fname,$lname,$username,$ps,$ut,$us,$unitID,$fatherName,$codeMelli,$birthYear,$phone,$postJob,$signature);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $user->createUser($fname,$lname,$username,$ut,$us,$ps,$unitID,$fatherName,$codeMelli,$birthYear,$phone,$postJob,$signature);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function dodeleteUser(){
    $acm = new acm();
    if(!$acm->hasAccess('userManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $user = new User();
    list($uid) = $ut->varCleanInput(array('uid'));
    if(!intval($uid)){
        $res = "شناسه کاربر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $user->deleteUser($uid);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getUsernameIP(){
    $acm = new acm();
    if(!$acm->hasAccess('userManagement')){
        die("access denied");
        exit;
    }

   $userId=$_POST['uid'];
   $user=new User();
   $res=$user->getUsernameIP($userId);
   if($res != false)
   {
        $out = "true";
        response($res,$out);
        exit;
    }
     else{}
    // {
    //     $res = "";
    //     $out = "false";
    //     response($res,$out);
    //     exit;
    // }  
}
// function listenLogin(){
//     $acm = new acm();
//     if(!$acm->hasAccess('userManagement')){
//         die("access denied");
//         exit;
//     }
//     $user=new User();
//     $userID=$_POST['userID'];
//     $result=new


// }
function setUserNameIP(){
    $acm = new acm();
    if(!$acm->hasAccess('userManagement')){
        die("access denied");
        exit;
    }
    $userid=$_POST['uid'];
    $userIP=$_POST['ip'];
    $user=new User();
   
	$res=$user->setUserNameIP($userid,$userIP);
    if($res>0)
   {
        $out = "true";
        response("تغییرات با موفقیت اعمال شد",$out);
        exit;
    }
    elseif($res==0)
    {
        $res = "IP مورد نظر برای این کاربر قبلا ثبت شده است";
        $out = "false";
        response($res,$out);
        exit;
    }
    elseif($res==-1)
    {
        $res = "تغییری انجام نشد";
        $out = "false";
        response($res,$out);
        exit;
    }   
 }

 function deleteUserNameIP(){
    $acm = new acm();
    if(!$acm->hasAccess('userManagement')){
        die("access denied");
        exit;
    }
    $RowID=$_POST['RowID'];

    $user=new User();
   
	$res=$user->deleteUserNameIP($RowID);
    if($res)
   {
        $out = "true";
        response("تغییرات با موفقیت اعمال شد",$out);
        exit;
    }
    
    else
    {
        $res = "تغییری انجام نشد";
        $out = "false";
        response($res,$out);
        exit;
    }   
 }

function getUserAllAccessHtm(){
    $acm = new acm();
    if(!$acm->hasAccess("changeUsersAccess")){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($uid) = $ut->varCleanInput(array('uid'));
    if(!intval($uid)){
        $res = "شناسه کاربر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $user = new User();
    $res = $user->getAllAccessHtm($uid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getUserAccess(){
    $acm = new acm();
    if(!$acm->hasAccess("changeUsersAccess")){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $user = new User();
    list($uid) = $ut->varCleanInput(array('uid'));
    if(!intval($uid)){
        $res = "شناسه کاربر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $userAccess = $user->getUserAccess($uid);
    if($userAccess != false){
        $out = "true";
        response($userAccess,$out);
        exit;
    }else{
        $res = "بدون دسترسی !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditAccessUser(){
    $acm = new acm();
    if(!$acm->hasAccess("changeUsersAccess")){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $user = new User();
    list($aids,$uid) = $ut->varCleanInput(array('aids','uid'));
    $res = $user->doEditAccessUser($aids,$uid);
    if($res==true){
        $res = "دسترسی های کاربر با موفقیت ویرایش گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "خطایی رخ داده است مجددا تلاش نمایید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function checkPassword(){
    $uti = new Utility();
    list($oldPass) = $uti->varCleanInput(array('oldPass'));
    $user = new User();
    $res = $user->checkPassword($oldPass);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doChangePassword(){
    $uti = new Utility();
    $user = new User();
    list($newPass) = $uti->varCleanInput(array('newPass'));
    $res = $user->changePassword($newPass);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ مدیریت قطعات +++++++++++++++++++++++

function managePiece(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $Piece = new Piece();
    $htm = $Piece->getManagePieceHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showPieceManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $Piece = new Piece();
    $list = new Listview();
    $ut = new Utility();
    list($page,$pName,$pCode,$CollectionName,$Material,$supply1,$supply,$gname,$brand,$group,$sgroup,$series,$color) = $ut->varCleanInput(array('page','pName','pCode','CollectionName','Material','supply1','supply','gname','brand','group','sgroup','series','color'));
    $res = $Piece->getPieceList($pName,$pCode,$CollectionName,$Material,$supply1,$supply,$gname,$brand,$group,$sgroup,$series,$color,$page);
    if($page == 1){
        $_SESSION['calcPiece'] = $Piece->getPieceListCountRows($pName,$pCode,$CollectionName,$Material,$supply1,$supply,$gname,$brand,$group,$sgroup,$series,$color);
    }
    $totalRows = $_SESSION['calcPiece'];
    $c = 0;
    $feilds = array();

    if($acm->hasAccess('engineeringAccess') || $acm->hasAccess('procurementAccess')) { // دسترسی مهندسی یا تدارکات
        $feilds[$c]['title'] = "کد قطعه";
        $feilds[$c]['width'] = "6%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pCode";
        $c++;

        $feilds[$c]['title'] = "نام قطعه";
        $feilds[$c]['width'] = "58%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pName";
        $feilds[$c]['color'] = 'yes';
        $c++;

        $feilds[$c]['title'] = "ویرایش قطعه";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "editPiece";
        $feilds[$c]['icon'] = "fa-edit";
        $c++;

        $feilds[$c]['title'] = "سایر اطلاعات";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowOtherInfoPiece";
        $feilds[$c]['icon'] = "fa-tv";
        $c++;

        $feilds[$c]['title'] = "افزودن/دانلود فایل";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowAddFilePiece";
        $feilds[$c]['icon'] = "fa-file-alt";
        $c++;

        $feilds[$c]['title'] = "محصولات مرتبط";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "pCode";
        $feilds[$c]['onclick'] = "ShowGoodsOfPiece";
        $feilds[$c]['icon'] = "fa-puzzle-piece";
    }else{
        $feilds[$c]['title'] = "کد قطعه";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pCode";
        $c++;

        $feilds[$c]['title'] = "نام قطعه";
        $feilds[$c]['width'] = "72%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pName";
        $feilds[$c]['color'] = 'yes';
        $c++;

        $feilds[$c]['title'] = "سایر اطلاعات";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowOtherInfoPiece";
        $feilds[$c]['icon'] = "fa-tv";
        $c++;

        $feilds[$c]['title'] = "محصولات مرتبط";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "pCode";
        $feilds[$c]['onclick'] = "ShowGoodsOfPiece";
        $feilds[$c]['icon'] = "fa-puzzle-piece";
    }

    $pagerType = 1;
    $listTitle = " تعداد قطعات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی قطعات : "." $res[1]";
    $tableID = "pieceManageBody-table";
    $jsf = "showPieceManageList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showMPieceManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $Piece = new Piece();
    $list = new Listview();
    $ut = new Utility();
    list($page,$pName) = $ut->varCleanInput(array('page','pName'));
    $res = $Piece->getMPieceList($pName,$page);
    if($page == 1){
        $_SESSION['calcMPiece'] = $Piece->getMPieceListCountRows($pName);
    }
    $totalRows = $_SESSION['calcMPiece'];
    $c = 0;
    $feilds = array();

    if($acm->hasAccess('engineeringAccess') || $acm->hasAccess('procurementAccess')) { // دسترسی مهندسی یا تدارکات
        $feilds[$c]['title'] = "کد قطعه";
        $feilds[$c]['width'] = "6%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pCode";
        $c++;

        $feilds[$c]['title'] = "نام قطعه";
        $feilds[$c]['width'] = "58%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pName";
        $feilds[$c]['color'] = 'yes';
        $c++;

        $feilds[$c]['title'] = "ویرایش قطعه";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "editPiece";
        $feilds[$c]['icon'] = "fa-edit";
        $c++;

        $feilds[$c]['title'] = "سایر اطلاعات";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowOtherInfoPiece";
        $feilds[$c]['icon'] = "fa-tv";
        $c++;

        $feilds[$c]['title'] = "افزودن/دانلود فایل";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowAddFilePiece";
        $feilds[$c]['icon'] = "fa-file-alt";
        $c++;

        $feilds[$c]['title'] = "محصولات مرتبط";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "pCode";
        $feilds[$c]['onclick'] = "ShowGoodsOfPiece";
        $feilds[$c]['icon'] = "fa-puzzle-piece";
    }else{
        $feilds[$c]['title'] = "کد قطعه";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pCode";
        $c++;

        $feilds[$c]['title'] = "نام قطعه";
        $feilds[$c]['width'] = "72%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pName";
        $feilds[$c]['color'] = 'yes';
        $c++;

        $feilds[$c]['title'] = "سایر اطلاعات";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowOtherInfoPiece";
        $feilds[$c]['icon'] = "fa-tv";
        $c++;

        $feilds[$c]['title'] = "محصولات مرتبط";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "pCode";
        $feilds[$c]['onclick'] = "ShowGoodsOfPiece";
        $feilds[$c]['icon'] = "fa-puzzle-piece";
    }

    $pagerType = 1;
    $listTitle = " تعداد قطعات : ".$totalRows." عدد ";
    $tableID = "pieceManageBody-table";
    $jsf = "showMPiecePageManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showMPiecePageManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $Piece = new Piece();
    $list = new Listview();
    $ut = new Utility();
    list($page,$pName) = $ut->varCleanInput(array('page','pName'));
    $res = $Piece->getMPagePieceList($page);
    if($page > 1){
        $_SESSION['calcMPPiece'] = $Piece->getMPieceListCountRows($pName);
    }
    $totalRows = $_SESSION['calcMPPiece'];
    $c = 0;
    $feilds = array();

    if($acm->hasAccess('engineeringAccess') || $acm->hasAccess('procurementAccess')) { // دسترسی مهندسی یا تدارکات
        $feilds[$c]['title'] = "کد قطعه";
        $feilds[$c]['width'] = "6%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pCode";
        $c++;

        $feilds[$c]['title'] = "نام قطعه";
        $feilds[$c]['width'] = "58%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pName";
        $feilds[$c]['color'] = 'yes';
        $c++;

        $feilds[$c]['title'] = "ویرایش قطعه";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "editPiece";
        $feilds[$c]['icon'] = "fa-edit";
        $c++;

        $feilds[$c]['title'] = "سایر اطلاعات";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowOtherInfoPiece";
        $feilds[$c]['icon'] = "fa-tv";
        $c++;

        $feilds[$c]['title'] = "افزودن/دانلود فایل";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowAddFilePiece";
        $feilds[$c]['icon'] = "fa-file-alt";
        $c++;

        $feilds[$c]['title'] = "محصولات مرتبط";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "pCode";
        $feilds[$c]['onclick'] = "ShowGoodsOfPiece";
        $feilds[$c]['icon'] = "fa-puzzle-piece";
    }else{
        $feilds[$c]['title'] = "کد قطعه";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pCode";
        $c++;

        $feilds[$c]['title'] = "نام قطعه";
        $feilds[$c]['width'] = "72%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pName";
        $feilds[$c]['color'] = 'yes';
        $c++;

        $feilds[$c]['title'] = "سایر اطلاعات";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowOtherInfoPiece";
        $feilds[$c]['icon'] = "fa-tv";
        $c++;

        $feilds[$c]['title'] = "محصولات مرتبط";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "pCode";
        $feilds[$c]['onclick'] = "ShowGoodsOfPiece";
        $feilds[$c]['icon'] = "fa-puzzle-piece";
    }

    $pagerType = 1;
    $listTitle = " تعداد قطعات : ".$totalRows." عدد ";
    $tableID = "pieceManageBody-table";
    $jsf = "showMPiecePageManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showBMPieceManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $Piece = new Piece();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $Piece->getBMPieceList($page);
    if($page == 1){
        $_SESSION['calcBMPiece'] = $Piece->getBMPieceListCountRows();
    }
    $totalRows = $_SESSION['calcBMPiece'];
    $c = 0;
    $feilds = array();

    if($acm->hasAccess('engineeringAccess') || $acm->hasAccess('procurementAccess')) { // دسترسی مهندسی یا تدارکات
        $feilds[$c]['title'] = "کد قطعه";
        $feilds[$c]['width'] = "6%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pCode";
        $c++;

        $feilds[$c]['title'] = "نام قطعه";
        $feilds[$c]['width'] = "58%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pName";
        $feilds[$c]['color'] = 'yes';
        $c++;

        $feilds[$c]['title'] = "ویرایش قطعه";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "editPiece";
        $feilds[$c]['icon'] = "fa-edit";
        $c++;

        $feilds[$c]['title'] = "سایر اطلاعات";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowOtherInfoPiece";
        $feilds[$c]['icon'] = "fa-tv";
        $c++;

        $feilds[$c]['title'] = "افزودن/دانلود فایل";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowAddFilePiece";
        $feilds[$c]['icon'] = "fa-file-alt";
        $c++;

        $feilds[$c]['title'] = "محصولات مرتبط";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "pCode";
        $feilds[$c]['onclick'] = "ShowGoodsOfPiece";
        $feilds[$c]['icon'] = "fa-puzzle-piece";
    }else{
        $feilds[$c]['title'] = "کد قطعه";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pCode";
        $c++;

        $feilds[$c]['title'] = "نام قطعه";
        $feilds[$c]['width'] = "72%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pName";
        $feilds[$c]['color'] = 'yes';
        $c++;

        $feilds[$c]['title'] = "سایر اطلاعات";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowOtherInfoPiece";
        $feilds[$c]['icon'] = "fa-tv";
        $c++;

        $feilds[$c]['title'] = "محصولات مرتبط";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "pCode";
        $feilds[$c]['onclick'] = "ShowGoodsOfPiece";
        $feilds[$c]['icon'] = "fa-puzzle-piece";
    }

    $pagerType = 1;
    $listTitle = " تعداد قطعات : ".$totalRows." عدد ";
    $tableID = "pieceManageBody-table";
    $jsf = "showBMPiecePageManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showBMPiecePageManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $Piece = new Piece();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $Piece->getBMPagePieceList($page);
    if($page > 1){
        $_SESSION['calcBMPPiece'] = $Piece->getBMPieceListCountRows();
    }
    $totalRows = $_SESSION['calcBMPPiece'];
    $c = 0;
    $feilds = array();

    if($acm->hasAccess('engineeringAccess') || $acm->hasAccess('procurementAccess')) { // دسترسی مهندسی یا تدارکات
        $feilds[$c]['title'] = "کد قطعه";
        $feilds[$c]['width'] = "6%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pCode";
        $c++;

        $feilds[$c]['title'] = "نام قطعه";
        $feilds[$c]['width'] = "58%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pName";
        $feilds[$c]['color'] = 'yes';
        $c++;

        $feilds[$c]['title'] = "ویرایش قطعه";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "editPiece";
        $feilds[$c]['icon'] = "fa-edit";
        $c++;

        $feilds[$c]['title'] = "سایر اطلاعات";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowOtherInfoPiece";
        $feilds[$c]['icon'] = "fa-tv";
        $c++;

        $feilds[$c]['title'] = "افزودن/دانلود فایل";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowAddFilePiece";
        $feilds[$c]['icon'] = "fa-file-alt";
        $c++;

        $feilds[$c]['title'] = "محصولات مرتبط";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "pCode";
        $feilds[$c]['onclick'] = "ShowGoodsOfPiece";
        $feilds[$c]['icon'] = "fa-puzzle-piece";
    }else{
        $feilds[$c]['title'] = "کد قطعه";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pCode";
        $c++;

        $feilds[$c]['title'] = "نام قطعه";
        $feilds[$c]['width'] = "72%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "pName";
        $feilds[$c]['color'] = 'yes';
        $c++;

        $feilds[$c]['title'] = "سایر اطلاعات";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowOtherInfoPiece";
        $feilds[$c]['icon'] = "fa-tv";
        $c++;

        $feilds[$c]['title'] = "محصولات مرتبط";
        $feilds[$c]['width'] = "10%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "pCode";
        $feilds[$c]['onclick'] = "ShowGoodsOfPiece";
        $feilds[$c]['icon'] = "fa-puzzle-piece";
    }

    $pagerType = 1;
    $listTitle = " تعداد قطعات : ".$totalRows." عدد ";
    $tableID = "pieceManageBody-table";
    $jsf = "showBMPiecePageManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function createMasterListPieceExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece') || !$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Piece= new Piece();
    list($pName,$pCode,$CollectionName,$Material,$supply1,$supply,$gname,$brand,$group,$sgroup,$series,$color) = $ut->varCleanInput(array('pName','pCode','CollectionName','Material','supply1','supply','gname','brand','group','sgroup','series','color'));

    $res = $Piece->getMasterListPieceExcel($pName,$pCode,$CollectionName,$Material,$supply1,$supply,$gname,$brand,$group,$sgroup,$series,$color);
    $hdarray = array('کد مهندسی','نام مجموعه','نام زیر مجموعه','نام قطعه','شماره نقشه','عنوان لاتین','مشخصات فنی',
                     'توضیحات','کد مهندسی مرجع','واحد قطعه','جنس','نحوه تامین','اولین مرحله ساخت','ابعاد سفارشی',
                     'کد مواد اولیه','اندازه خارجی شمش','وزن سیستمی','وزن مواد سیاه تاب (گرم)','وزن ماشینکاری (گرم)','وزن نهایی (گرم)');
    $fieldNames = array('pCode','Collection_name','Subset_name','pName','mapNumber','Latin_title','Technical_Specifications',
                        'description','referenceECode','pUnit','material','ChangingHow_supply','first_stage_construction','Custom_dimensions',
                        'RawMaterialCode','external_size_bullion','System_weight','Weight_materials','Weight_Machining','Weight_Final'
    );
    $name = "MasterListPiece".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getGoodGroupList(){
    $ut = new Utility();
    list($brand) = $ut->varCleanInput(array('brand'));
    $piece = new Piece();
    $res = $piece->getGGroup($brand);
    $out = "true";
    response($res,$out);
    exit;
}

function getGoodSGroupList(){
    $ut = new Utility();
    list($group) = $ut->varCleanInput(array('group'));
    $piece = new Piece();
    $res = $piece->getGSGroup($group);
    $out = "true";
    response($res,$out);
    exit;
}

function getGoodSeriesList(){
    $ut = new Utility();
    list($sgroup) = $ut->varCleanInput(array('sgroup'));
    $piece = new Piece();
    $res = $piece->getGSeries($sgroup);
    $out = "true";
    response($res,$out);
    exit;
}

function getGoodColorList(){
    $ut = new Utility();
    list($series) = $ut->varCleanInput(array('series'));
    $piece = new Piece();
    $res = $piece->getGColor($series);
    $out = "true";
    response($res,$out);
    exit;
}

function priceToRial(){
    $ut = new Utility();
    $Piece = new Piece();
    list($CurrencyType,$CurrencyAmount,$PACV) = $ut->varCleanInput(array('CurrencyType','CurrencyAmount','PACV'));
    $res = $Piece->priceToRial($CurrencyType,$CurrencyAmount,$PACV);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowGoodsOfPiece(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pCode) = $ut->varCleanInput(array('pCode'));
    $Piece = new Piece();
    $res = $Piece->goodsOfPieceHTM($pCode);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowOtherInfoPiece(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه قطعه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Piece = new Piece();
    $res = $Piece->OtherInfoPieceHTM($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function getPieceInfo(){
    $uti = new Utility();
    list($pid) = $uti->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه قطعه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Piece = new Piece();
    $res = $Piece->pieceInfo($pid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreatePiece(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Piece = new Piece();
    list($pid,$Pname,$Pcode,$Punit,$Collection,$Subname,$Material,$Latin,$PriceDakheli,$PriceDakheliWithTax,$PACD,$CastingPrice,
         $PFW,$CastMachPrice,$PFWBM,$CurrencyType,$CurrencyAmount,$PACV,$montageCode,$Howsupply,$Technical,
         $RECode,$FSC,$RMCode,$dimensions,$ExternalSize,$Wmaterials,$WMachining,$WFinal,$PBW,
         $MBW,$FBW,$Systemweight,$LoadPolish,$PriceBasis,$Supplier,$CatchDate,$desc,$Kickback,$PlasticPlate,$PercentageP
        ) = $uti->varCleanInput(array('pid','Pname','Pcode','Punit','Collection','Subname','Material','Latin',
                                      'PriceDakheli','PriceDakheliWithTax','PACD','CastingPrice','PFW','CastMachPrice','PFWBM',
                                      'CurrencyType','CurrencyAmount','PACV','montageCode','Howsupply','Technical',
                                      'RECode','FSC','RMCode','dimensions','ExternalSize','Wmaterials','WMachining',
                                      'WFinal','PBW','MBW','FBW','Systemweight','LoadPolish','PriceBasis','Supplier',
                                      'CatchDate','desc','Kickback','PlasticPlate','PercentageP'
                                ));
    $PriceDakheli = str_replace(',','',$PriceDakheli);
    $PriceDakheliWithTax = str_replace(',','',$PriceDakheliWithTax);
    $CastingPrice = str_replace(',','',$CastingPrice);
    $CastMachPrice = str_replace(',','',$CastMachPrice);
    $PlasticPlate = str_replace(',','',$PlasticPlate);

    $PBW = (strlen(trim($PBW)) == 0 ? 'NULL' : number_format($PBW,3,'.',''));  // وزن مبنای اولیه
    $MBW = (strlen(trim($MBW)) == 0 ? 'NULL' : number_format($MBW,3,'.',''));  // وزن مبنای ماشینکاری
    $FBW = (strlen(trim($FBW)) == 0 ? 'NULL' : number_format($FBW,3,'.',''));  // وزن مبنای پرداخت
    $LoadPolish = (strlen(trim($LoadPolish)) == 0 ? 'NULL' : number_format($LoadPolish,3,'.',''));  // بارریزی پرداخت
    if(intval($pid) > 0){//edit
        $res = $Piece->editPiece($pid,$Pname,$Punit,$Collection,$Subname,$Material,$Latin,$PriceDakheli,$PriceDakheliWithTax,$PACD,$CastingPrice,
                                 $PFW,$CastMachPrice,$PFWBM,$CurrencyType,$CurrencyAmount,$PACV,$montageCode,$Howsupply,$Technical,
                                 $RECode,$FSC,$RMCode,$dimensions,$ExternalSize,$Wmaterials,$WMachining,$WFinal,$PBW,
                                 $MBW,$FBW,$Systemweight,$LoadPolish,$PriceBasis,$Supplier,$CatchDate,$desc,$Kickback,$PlasticPlate,$PercentageP);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $Piece->createPiece($Pname,$Pcode,$Punit,$Collection,$Subname,$Material,$Latin,$montageCode,$Howsupply,
                                   $Technical,$RECode,$FSC,$RMCode,$dimensions,$ExternalSize,$Wmaterials,$WMachining,
                                   $WFinal,$PBW,$MBW,$FBW,$Systemweight,$LoadPolish,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function doCreatePieceFile(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $Piece = new Piece();
    $ut = new Utility();
    list($pid,$MapNumber) = $ut->varCleanInput(array('pid','MapNumber'));
    $photo = $_FILES['photo'];
    $pdf = $_FILES['pdfMap'];
    $opc = $_FILES['OPC'];
    $res = $Piece->addFileToPiece($pid,$MapNumber,$photo,$pdf,$opc);
    if($res != false){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function downloadFilePiece(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $Piece = new Piece();
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    $res = $Piece->checkFileExist($pid);
    if($res){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "فایل موجود نیست !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function downloadFileOPC(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $Piece = new Piece();
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    $res = $Piece->checkOPCFileExist($pid);
    if($res){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "فایل موجود نیست !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function downloadImagePiece(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $Piece = new Piece();
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    $res = $Piece->checkPieceImageExist($pid);
    if($res){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "فایل موجود نیست !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function downloadIPPiece(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $Piece = new Piece();
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    $res = $Piece->checkPieceIPExist($pid);
    if($res){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "فایل موجود نیست !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doUploadPMListFile(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $Piece = new Piece();
    $pmlist = $_FILES['excPML'];
    $res = $Piece->uploadPMListFile($pmlist);
    if($res != false){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "2درخواست با خطا مواجه شد، لطفا دوباره سعی کنید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function uploadAgainPieceMasterList(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $Piece = new Piece();
    $res = $Piece->uploadAgainPieceMasterList();
    if($res != false){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getPieceNameList(){
    $Piece = new Piece();
    $res = $Piece->getPieces();
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ قطعه ای تعریف نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ مدیریت محصولات +++++++++++++++++++++++

function manageGood(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood')){
        die("access denied");
        exit;
    }
    $Good = new Good();
    $htm = $Good->getManageGoodHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showGoodManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood')){
        die("access denied");
        exit;
    }
    $Good = new Good();
    $list = new Listview();
    $ut = new Utility();
    list($page,$gName,$gCode,$parvaneh) = $ut->varCleanInput(array('page','gName','gCode','parvaneh'));
    $res = $Good->getGoodList($gName,$gCode,$parvaneh,$page);
    if($page == 1){
        $_SESSION['calcGood'] = $Good->getGoodListCountRows($gName,$gCode,$parvaneh);
    }
    $totalRows = $_SESSION['calcGood'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title'] = "#";
    $feilds[$c]['width'] = "4%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "checkBox";
    $c++;

    $feilds[$c]['title'] = "کد محصول";
    $feilds[$c]['width'] = "8%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "gCode";
    $c++;

    $feilds[$c]['title'] = "نام محصول";
    $feilds[$c]['width'] = "70%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "gName";
    $c++;

    $feilds[$c]['title'] = "اطلاعات محصول";
    $feilds[$c]['width'] = "10%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "btn";
    $feilds[$c]['param'] = "RowID";
    $feilds[$c]['onclick'] = "showGoodInfo";
    $feilds[$c]['icon'] = "fa-tv";
    $c++;

    $feilds[$c]['title'] = "اجزا محصول";
    $feilds[$c]['width'] = "8%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "btn";
    $feilds[$c]['param'] = "RowID";
    $feilds[$c]['onclick'] = "showGoodPieces";
    $feilds[$c]['icon'] = "fa-puzzle-piece";

    $pagerType = 1;
    $listTitle = " تعداد محصولات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی محصولات : "." $res[1] "." <br><br> <span style='float: right;'> تاریخ آخرین بروزرسانی BOM : </span>&nbsp;"." $res[2]";
    $tableID = "goodManageBody-table";
    $jsf = "showGoodManageList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showMGoodManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood')){
        die("access denied");
        exit;
    }
    $good = new Good();
    $list = new Listview();
    $ut = new Utility();
    list($page,$gName,$gCode,$parvaneh) = $ut->varCleanInput(array('page','gName','gCode','parvaneh'));
    $res = $good->getMGoodList($gName,$gCode,$parvaneh,$page);
    if($page == 1){
        $_SESSION['calcMGood'] = $good->getMGoodListCountRows($gName,$gCode,$parvaneh);
    }
    $totalRows = $_SESSION['calcMGood'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title'] = "#";
    $feilds[$c]['width'] = "4%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "checkBox";
    $c++;

    $feilds[$c]['title'] = "کد محصول";
    $feilds[$c]['width'] = "8%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "gCode";
    $c++;

    $feilds[$c]['title'] = "نام محصول";
    $feilds[$c]['width'] = "70%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "gName";
    $c++;

    $feilds[$c]['title'] = "اطلاعات محصول";
    $feilds[$c]['width'] = "10%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "btn";
    $feilds[$c]['param'] = "RowID";
    $feilds[$c]['onclick'] = "showGoodInfo";
    $feilds[$c]['icon'] = "fa-tv";
    $c++;

    $feilds[$c]['title'] = "اجزا محصول";
    $feilds[$c]['width'] = "8%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "btn";
    $feilds[$c]['param'] = "RowID";
    $feilds[$c]['onclick'] = "showGoodPieces";
    $feilds[$c]['icon'] = "fa-puzzle-piece";

    $pagerType = 1;
    $listTitle = " تعداد محصولات : ".$totalRows." عدد ";
    $tableID = "goodManageBody-table";
    $jsf = "showMGoodPageManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showMGoodPageManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood')){
        die("access denied");
        exit;
    }
    $good = new Good();
    $list = new Listview();
    $ut = new Utility();
    list($page,$gName,$gCode,$parvaneh) = $ut->varCleanInput(array('page','gName','gCode','parvaneh'));
    $res = $good->getMPageGoodList($page);
    if($page > 1){
        $_SESSION['calcMPGood'] = $good->getMGoodListCountRows($gName,$gCode,$parvaneh);
    }
    $totalRows = $_SESSION['calcMPGood'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title'] = "#";
    $feilds[$c]['width'] = "4%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "checkBox";
    $c++;

    $feilds[$c]['title'] = "کد محصول";
    $feilds[$c]['width'] = "8%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "gCode";
    $c++;

    $feilds[$c]['title'] = "نام محصول";
    $feilds[$c]['width'] = "70%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "gName";
    $c++;

    $feilds[$c]['title'] = "اطلاعات محصول";
    $feilds[$c]['width'] = "10%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "btn";
    $feilds[$c]['param'] = "RowID";
    $feilds[$c]['onclick'] = "showGoodInfo";
    $feilds[$c]['icon'] = "fa-tv";
    $c++;

    $feilds[$c]['title'] = "اجزا محصول";
    $feilds[$c]['width'] = "8%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "btn";
    $feilds[$c]['param'] = "RowID";
    $feilds[$c]['onclick'] = "showGoodPieces";
    $feilds[$c]['icon'] = "fa-puzzle-piece";

    $pagerType = 1;
    $listTitle = " تعداد محصولات : ".$totalRows." عدد ";
    $tableID = "goodManageBody-table";
    $jsf = "showMGoodPageManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showBMGoodManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood')){
        die("access denied");
        exit;
    }
    $good = new Good();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $good->getBMGoodList($page);
    if($page == 1){
        $_SESSION['calcBMGood'] = $good->getBMGoodListCountRows();
    }
    $totalRows = $_SESSION['calcBMGood'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title'] = "#";
    $feilds[$c]['width'] = "4%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "checkBox";
    $c++;

    $feilds[$c]['title'] = "کد محصول";
    $feilds[$c]['width'] = "8%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "gCode";
    $c++;

    $feilds[$c]['title'] = "نام محصول";
    $feilds[$c]['width'] = "70%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "gName";
    $c++;

    $feilds[$c]['title'] = "اطلاعات محصول";
    $feilds[$c]['width'] = "10%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "btn";
    $feilds[$c]['param'] = "RowID";
    $feilds[$c]['onclick'] = "showGoodInfo";
    $feilds[$c]['icon'] = "fa-tv";
    $c++;

    $feilds[$c]['title'] = "اجزا محصول";
    $feilds[$c]['width'] = "8%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "btn";
    $feilds[$c]['param'] = "RowID";
    $feilds[$c]['onclick'] = "showGoodPieces";
    $feilds[$c]['icon'] = "fa-puzzle-piece";

    $pagerType = 1;
    $listTitle = " تعداد محصولات : ".$totalRows." عدد ";
    $tableID = "goodManageBody-table";
    $jsf = "showBMGoodPageManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showBMGoodPageManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood')){
        die("access denied");
        exit;
    }
    $good = new Good();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $good->getBMPageGoodList($page);
    if($page > 1){
        $_SESSION['calcBMPGood'] = $good->getBMGoodListCountRows();
    }
    $totalRows = $_SESSION['calcBMPGood'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title'] = "#";
    $feilds[$c]['width'] = "4%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "checkBox";
    $c++;

    $feilds[$c]['title'] = "کد محصول";
    $feilds[$c]['width'] = "8%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "gCode";
    $c++;

    $feilds[$c]['title'] = "نام محصول";
    $feilds[$c]['width'] = "70%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "gName";
    $c++;

    $feilds[$c]['title'] = "اطلاعات محصول";
    $feilds[$c]['width'] = "10%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "btn";
    $feilds[$c]['param'] = "RowID";
    $feilds[$c]['onclick'] = "showGoodInfo";
    $feilds[$c]['icon'] = "fa-tv";
    $c++;

    $feilds[$c]['title'] = "اجزا محصول";
    $feilds[$c]['width'] = "8%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "btn";
    $feilds[$c]['param'] = "RowID";
    $feilds[$c]['onclick'] = "showGoodPieces";
    $feilds[$c]['icon'] = "fa-puzzle-piece";

    $pagerType = 1;
    $listTitle = " تعداد محصولات : ".$totalRows." عدد ";
    $tableID = "goodManageBody-table";
    $jsf = "showBMGoodPageManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getGoodInfo(){
    $uti = new Utility();
    list($gid) = $uti->varCleanInput(array('gid'));
    if(!intval($gid)){
        $res = "شناسه محصول بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Good = new Good();
    $res = $Good->goodInfo($gid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateGood(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Good = new Good();
    list($gid,$Gname,$Gcode,$similar) = $uti->varCleanInput(array('gid','Gname','Gcode','similar'));
    if(intval($gid) > 0){//edit
        $res = $Good->editGood($gid,$Gname,$Gcode);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $Good->createGood($Gname,$Gcode,$similar);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function showGoodInfo(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($gid) = $ut->varCleanInput(array('gid'));
    if(!intval($gid)){
        $res = "شناسه محصول بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $good = new Good();
    $res = $good->getGoodOtherInfo($gid);
    $out = "true";
    response($res,$out);
    exit;
}

function showGoodPieces(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood') && !$acm->hasAccess('salesPriceGoodsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($gid) = $ut->varCleanInput(array('gid'));
    if(!intval($gid)){
        $res = "شناسه محصول بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Good = new Good();
    $res = $Good->showGoodPieces($gid);
    $out = "true";
    response($res,$out);
    exit;
}

function doEditCreateCoefficientPiece(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $good = new Good();
    $ut = new Utility();
    $myJsonString = $_POST['myJsonString'];
    $myJsonString = json_decode($myJsonString);
    list($gCode) = $ut->varCleanInput(array('gCode'));
    $res = $good->editCreateCoefficientPiece($myJsonString,$gCode);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function activeInactivePieceOfGood(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pCode,$gCode,$isEnable) = $ut->varCleanInput(array('pCode','gCode','isEnable'));
    $Good = new Good();
    $res = $Good->activeInactivePieceOfGood($pCode,$gCode,$isEnable);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doDeletePieceOfGood(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pCode,$gCode) = $ut->varCleanInput(array('pCode','gCode'));
    $Good = new Good();
    $res = $Good->deletePieceOfGood($pCode,$gCode);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doAddPieceToGoods(){  //+++++ افزودن قطعه به محصولات +++++
    $acm = new acm();
    if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pName,$gNames,$Coefficient) = $ut->varCleanInput(array('pName','gNames','Coefficient'));
    $Good = new Good();
    $res = $Good->addPieceToGoods($pName,$gNames,$Coefficient);
    if(intval($res) == -1){
        $res = "یک یا چند محصول دارای این قطعه می باشند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif(intval($res) == -2){
        $res = "یکی از محصولات موجود نمی باشد !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditPieceToGoods(){  //+++++ ویرایش قطعه در محصولات +++++
    $acm = new acm();
    if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pName,$gNames,$Coefficient) = $ut->varCleanInput(array('pName','gNames','Coefficient'));
    $Good = new Good();
    $res = $Good->editPieceToGoods($pName,$gNames,$Coefficient);
    if(intval($res) == -1){
        $res = "قطعه در یکی از محصولات موجود نمی باشد !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif(intval($res) == -2){
        $res = "یکی از محصولات موجود نمی باشد !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doAddPiecesToGood(){  //+++++ افزودن قطعات به محصول +++++
    $acm = new acm();
    if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($gid,$PiecesCoefficient) = $ut->varCleanInput(array('gid','PiecesCoefficient'));
    $Good = new Good();
    $res = $Good->addPiecesToGood($gid,$PiecesCoefficient);
    if(intval($res) == -1){
        $res = "یکی از قطعه ها قبلا در محصول موجود می باشد !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif(intval($res) == -2){
        $res = "یکی از قطعات موجود نمی باشد !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif(intval($res) == -3){
        $res = "ضریب یکی از قطعات وارد نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doUploadGMListFile(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood')){
        die("access denied");
        exit;
    }
    $good = new Good();
    $gmlist = $_FILES['excGML'];
    $res = $good->uploadGMListFile($gmlist);
    if($res != false){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "درخواست با خطا مواجه شد، لطفا دوباره سعی کنید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function uploadAgainGoodMasterList(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood')){
        die("access denied");
        exit;
    }
    $good = new Good();
    $res = $good->uploadAgainGoodMasterList();
    if($res != false){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doUploadBOMListFile(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $good = new Good();
    $bomList = $_FILES['excBOM'];
    $res = $good->uploadBOMListFile($bomList);
    if($res != false){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "درخواست با خطا مواجه شد، لطفا دوباره سعی کنید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function uploadAgainBOMList(){
    $acm = new acm();
    if(!$acm->hasAccess('managePiece')){
        die("access denied");
        exit;
    }
    $good = new Good();
    $res = $good->uploadAgainBOMList();
    if($res != false){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getGoodNameList(){
    $good = new Good();
    $res = $good->getGoods();
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ محصولی تعریف نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function createMasterListGoodExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $good= new Good();
    list($gName,$gCode,$parvaneh) = $ut->varCleanInput(array('gName','gCode','parvaneh'));

    $res = $good->getMasterListGoodExcel($gName,$gCode,$parvaneh);
    $hdarray = array('کد مهندسی','نام محصول','کد همکاران','عنوان لاتین','برند محصول','گروه محصول', 'زیر گروه محصول',
        'سری محصول','پوشش محصول','کارتن مادر','کارتن بچه','تعداد محصول در کارتن بچه',
        'تعداد کارتن بچه در کارتن مادر','پروانه بهره برداری','وزن کارتن مادر انبار (کیلوگرم)',
        'وزن کارتن مادر BOM (کیلوگرم)','توضیحات');
    $fieldNames = array('gCode','gName','HCode','LatinTitle','brand','ggroup','gsgroup','Series','color','MCartoon',
        'BCartoon','ABCartoon','AMCartoon','parvaneh','gWeight','gmWeight','gdescription'
    );
    $name = "MasterListGood".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ مدیریت نرخ ها +++++++++++++++++++++++

function manageRates(){
    $acm = new acm();
    if(!$acm->hasAccess('manageRates')){
        die("access denied");
        exit;
    }
    $rates = new Rates();
    $send = $rates->getManageRatesHtm();
    $res = $send;
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ مدیریت نرخ ارز +++++++++++++++++++++++

function showCurrencyManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('currencyManage')){
        die("access denied");
        exit;
    }
    $Currency = new Currency();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $Currency->getCurrencyList($page);
    if($page == 1){
        $_SESSION['calcCurrency'] = $Currency->getCurrencyListCountRows();
    }
    $totalRows = $_SESSION['calcCurrency'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام ارز";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="currencyName";
    $c++;

    $feilds[$c]['title']="نرخ روز";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="dayRate";
    $c++;

    $feilds[$c]['title']="نرخ قبلی";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="previousRate";
    $c++;

    $feilds[$c]['title']="نرخ تبدیل روز";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="ExchangeRate";
    $c++;

    $feilds[$c]['title']="نرخ تبدیل قبلی";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="previousExchangeRate";
    $c++;

    $feilds[$c]['title']="تاریخ قبلی";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="previousDate";
    $c++;

    $feilds[$c]['title']="تاریخ فعلی";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="createDate";
    $c++;

    $feilds[$c]['title']="شخص ثبت کننده";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="name";

    $pagerType = 1;
    $listTitle = " تعداد ارز ثبت شده : ".$totalRows." عدد ";
    $tableID = "currencyManageBody-table";
    $jsf = "showCurrencyManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function doEditCreateCurrency(){
    $acm = new acm();
    if(!$acm->hasAccess('currencyManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Currency = new Currency();
    list($cid,$Cuname,$CRate,$ERate) = $uti->varCleanInput(array('cid','Cuname','CRate','ERate'));
    $CRate = str_replace(',','',$CRate);
    if(intval($cid) > 0){//edit
        $res = $Currency->editCurrency($cid,$CRate,$ERate);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $Currency->createCurrency($Cuname,$CRate,$ERate);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getCurrencyInfo(){
    $uti = new Utility();
    list($cid) = $uti->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه ارزی بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Currency = new Currency();
    $res = $Currency->currencyInfo($cid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function checkExistDollar(){
    $Currency = new Currency();
    $res = $Currency->checkExistDollar();
    $out = "true";
    response($res,$out);
    exit;
}

function getDollarPrice(){
    $Currency = new Currency();
    $res = $Currency->getDollarPrice();
    $out = "true";
    response($res,$out);
    exit;
}

function Num_format(){
    $uti = new Utility();
    list($number) = $uti->varCleanInput(array('number'));
    $res = $uti->numberFormat($number);
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ مدیریت بار برنج +++++++++++++++++++++++

function showManageBrassWeightList(){
    $acm = new acm();
    if(!$acm->hasAccess('manageBrassWeight')){
        die("access denied");
        exit;
    }
    $brass = new Brass();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $brass->getBrassWeightList($page);
    if($page == 1){
        $_SESSION['calcBrassWeight'] = $brass->getBrassWeightListCountRows();
    }
    $totalRows = $_SESSION['calcBrassWeight'];
    $c = 0;
    $feilds = array();
/*    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;*/

    $feilds[$c]['title']="قیمت براده برنج (ریخته گری-ماشینکاری)";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="brassSwarfPrice";
    $c++;

    $feilds[$c]['title']="اجرت شمش (قطر بالای 14)";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="BullionPriceUp14";
    $c++;

    $feilds[$c]['title']="اجرت شمش (قطر زیر 14)";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="BullionPriceUnder14";
    $c++;

    $feilds[$c]['title']="اجرت شمش کلکتور";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="BullionPriceColector";
    $c++;

    $feilds[$c]['title']="اجرت ریخته گری";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="CastingPrice";
    $c++;

    $feilds[$c]['title']="قیمت خاک پرداخت";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="PolishingSoilPrice";
    $c++;

    $feilds[$c]['title']="درصد سوخت بار";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="PercentFuelWeight";

    $pagerType = 0;
    $listTitle = " تعداد ثبت شده : ".$totalRows." عدد ";
    $tableID = "brassWeightManageBody-table";
    $jsf = "showManageBrassWeightList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getBrassWeight(){
    $brass = new Brass();
    $res = $brass->brassWeight();
    $out = "true";
    response($res,$out);
    exit;
}

function doEditCreateBrassWeight(){
    $acm = new acm();
    if(!$acm->hasAccess('manageBrassWeight')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $brass = new Brass();
    list($BWid,$BSPrice,$U14,$Un14,$BPriceC,$CPrice,$pfw,$psp) = $uti->varCleanInput(array('BWid','BSPrice','U14','Un14','BPriceC','CPrice','pfw','psp'));
    $BSPrice = str_replace(',','',$BSPrice);
    $U14 = str_replace(',','',$U14);
    $Un14 = str_replace(',','',$Un14);
    $BPriceC = str_replace(',','',$BPriceC);
    $CPrice = str_replace(',','',$CPrice);
    $psp = str_replace(',','',$psp);
    if(intval($BWid) > 0){//edit
        $res = $brass->editBrassWeight($BWid,$BSPrice,$U14,$Un14,$BPriceC,$CPrice,$pfw,$psp);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $brass->createBrassWeight($BSPrice,$U14,$Un14,$BPriceC,$CPrice,$pfw,$psp);
        if($res == true){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function calKhakPardakht(){
    $ut = new Utility();
    list($BSPrice,$PPSBW) = $ut->varCleanInput(array('BSPrice','PPSBW'));
    $BSPrice = str_replace(',','',$BSPrice);
    $PPSBW = $PPSBW/100;
    $res = number_format(intval($BSPrice) * $PPSBW);
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ مدیریت هزینه های پرسنل +++++++++++++++++++++++

function addddPersonnel(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $Personnel = new Salary();
    $res = $Personnel->addddPersonnel();
    $out = "true";
    response($res,$out);
    exit;
}

function showPersonnelManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $salary = new Salary();
    $list = new Listview();
    $ut = new Utility();
    list($page,$Pname,$Pfamily,$Pcode,$Punit,$RsDate,$ReDate,$TsAmount,$TeAmount,$Pname1,$Pfamily1,$Pcode1,$Punit1,$RsDate1,$ReDate1,$TsAmount1,$TeAmount1,$ability,$status) = $ut->varCleanInput(array('page','Pname','Pfamily','Pcode','Punit','RsDate','ReDate','TsAmount','TeAmount','Pname1','Pfamily1','Pcode1','Punit1','RsDate1','ReDate1','TsAmount1','TeAmount1','ability','status'));
    $TsAmount = str_replace(',','',$TsAmount);
    $TeAmount = str_replace(',','',$TeAmount);
    $res = $salary->getPersonnelList($Pname,$Pfamily,$Pcode,$Punit,$RsDate,$ReDate,$TsAmount,$TeAmount,$Pname1,$Pfamily1,$Pcode1,$Punit1,$RsDate1,$ReDate1,$TsAmount1,$TeAmount1,$ability,$status,$page);
    if($page == 1){
        $_SESSION['calcPersonnel'] = $salary->getPersonnelListCountRows($Pname,$Pfamily,$Pcode,$Punit,$RsDate,$ReDate,$TsAmount,$TeAmount,$Pname1,$Pfamily1,$Pcode1,$Punit1,$RsDate1,$ReDate1,$TsAmount1,$TeAmount1,$ability,$status);
        $t = $salary->getTotalAmountPersonnel($Pname,$Pfamily,$Pcode,$Punit,$RsDate,$ReDate,$TsAmount,$TeAmount,$Pname1,$Pfamily1,$Pcode1,$Punit1,$RsDate1,$ReDate1,$TsAmount1,$TeAmount1,$ability,$status);
        if($acm->hasAccess('administrativeManagement')) {
            $_SESSION['calcTotalAmount'] = $t[0];
            $_SESSION['calcTotalHourAmount'] = $t[1];
            $_SESSION['calcTotalDayAmount'] = $t[2];
            $_SESSION['calcTotalMonthAmount'] = $t[3];
        }
    }
    $totalRows = $_SESSION['calcPersonnel'];
    $footerTxt = 'جمع کل هزینه ها به عدد = <span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 16px;">'.number_format($_SESSION['calcTotalAmount']).'</span>&nbsp;&nbsp;ریال <br/> جمع کل هزینه ها در ماه به عدد = <span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 16px;"> '.number_format($_SESSION['calcTotalMonthAmount']).'</span>&nbsp;ریال<br />جمع کل هزینه ها در روز به عدد = <span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 16px;">'.number_format($_SESSION['calcTotalDayAmount']).'</span>&nbsp;&nbsp;ریال <br/> جمع کل هزینه ها در ساعت به عدد = <span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 16px;"> '.number_format($_SESSION['calcTotalHourAmount']).'</span>&nbsp;ریال';
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="نام و نام خانوادگی";
    $feilds[$c]['width']="24%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="کد پرسنلی";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="PersonnelCode";
    $c++;

    $feilds[$c]['title']="واحد";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Uname";
    $c++;

    $feilds[$c]['title']="تاریخ استخدام";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="RecruitmentDate";
    $c++;

    $feilds[$c]['title']="تاریخ شروع قرارداد";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="BeginDateContract";
    $c++;

    $feilds[$c]['title']="تاریخ اتمام قرارداد";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="EndDateContract";
    $c++;

    $feilds[$c]['title'] = "ویرایش";
    $feilds[$c]['width'] = "8%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "btn";
    $feilds[$c]['param'] = "RowID";
    $feilds[$c]['onclick'] = "editPersonnel";
    $feilds[$c]['icon'] = "fa-edit";
    $c++;

    $feilds[$c]['title'] = "مدارک";
    $feilds[$c]['width'] = "5%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "btn";
    $feilds[$c]['param'] = "RowID";
    $feilds[$c]['onclick'] = "editDocumentPersonnel";
    //$feilds[$c]['icon'] = "fa-file";

    if($acm->hasAccess('administrativeManagement')) {
        $c++;
        $feilds[$c]['title'] = "سایر اطلاعات";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowOtherInfoPersonnelCost";
        $feilds[$c]['icon'] = "fa-tv";
        $c++;

        $feilds[$c]['title'] = "پرینت قرارداد";
        $feilds[$c]['width'] = "7%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "printPersonnelAgreement";
        $feilds[$c]['icon'] = "fa-print";
    }else{
        $c++;
        $feilds[$c]['title'] = "سایر اطلاعات";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "ShowOtherInfoPersonnel";
        $feilds[$c]['icon'] = "fa-tv";
        $c++;

        $feilds[$c]['title'] = "پرینت قرارداد";
        $feilds[$c]['width'] = "7%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "printPersonnelAgreement";
        $feilds[$c]['icon'] = "fa-print";
    }

    $pagerType = 1;
    $listTitle = " تعداد پرسنل : ".$totalRows." عدد ";
    $tableID = "personnelManageBody-table";
    $jsf = "showPersonnelManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf,$footerTxt);
    $out = "true";
    response($htm,$out);
    exit;
}

function createPersonnelExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage') || !$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $salary = new Salary();
    list($Pname,$Pfamily,$Pcode,$Punit,$RsDate,$ReDate,$TsAmount,$TeAmount,$Pname1,$Pfamily1,$Pcode1,$Punit1,$RsDate1,$ReDate1,$TsAmount1,$TeAmount1,$ability,$status) = $ut->varCleanInput(array('Pname','Pfamily','Pcode','Punit','RsDate','ReDate','TsAmount','TeAmount','Pname1','Pfamily1','Pcode1','Punit1','RsDate1','ReDate1','TsAmount1','TeAmount1','ability','status'));
    $res = $salary->getPersonnelExcel($Pname,$Pfamily,$Pcode,$Punit,$RsDate,$ReDate,$TsAmount,$TeAmount,$Pname1,$Pfamily1,$Pcode1,$Punit1,$RsDate1,$ReDate1,$TsAmount1,$TeAmount1,$ability,$status);
    $hdarray = array('نام و نام خانوادگی','کد پرسنلی','واحد','نام پدر','شماره شناسنامه','صادره از','کد ملی','وضعیت تاهل',
                     'تعداد فرزند','آدرس','دستمزد روزانه','خوار و بار','حق مسکن','حق اولاد',
                     'شماره بیمه','شماره حساب','تاریخ استخدام','تاریخ شروع قرارداد','تاریخ اتمام قرارداد',
                     'مدت قرارداد','مدت آزمایشی','شماره تلفن ثابت','شماره تلفن همراه','امتیاز ارزشیابی','توضیحات','مدرک و رشته تحصیلی');
    $fieldNames = array('Fname','PersonnelCode','Uname','father_Name','Birth_Certificate_Num','Issued','National_Code','Marital_Status',
                        'numberChildren','Address','dailyWages','Grocery','RightHousing','Child_Allowance',
                        'insurance_Number','account_Number','RecruitmentDate','BeginDateContract','EndDateContract',
                        'Term_contract','month_Trial','phone','mobile','EvaluationScore','description','degree_field_study');
    $name = "PersonnelList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function finalPricePersonnelExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage') || !$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $salary = new Salary();
    $res = $salary->getPersonnelFinalPriceExcel();
    $hdarray = array('نام و نام خانوادگی','کد پرسنلی','روز کارکرد','حقوق روزانه','حقوق کارکرد','حق مسکن',
                     'خواروبار','تعداد فرزند','عائله مندی','نوبت کاری','جمع کل حقوق داخل لیست','حقوق خارج لیست',
                     'حق مسئولیت','حق شغل','کمک هزینه سرویس','کمک هزینه اجاره','جمع کل حقوق ناخالص','کسر بیمه',
                     'کسر مالیات','جمع کل حقوق خالص','حق بیمه سهم کارفرما','مرخصی','عیدی','پاداش','سنوات',
                     'هزینه سرویس','حدود ساعت اضافه کار در ماه','فوق العاده اضافه کار','جمع قیمت تمام شده ماهیانه','جمع قیمت تمام شده سالیانه');
    $fieldNames = array('Fname','PersonnelCode','Unit_id','dailyWages','monthlyWages','RightHousing',
                        'Grocery','numberChildren','Child_Allowance','Shift','SalaryInofList','SalaryOutofList',
                        'responsibility_right','job_right','hardWork','financial_allowance','grossMonthlySalary','WorkerPremium',
                        'Tax','wage','EmployerPremium','LeaveCost','Festival','Reward','Years',
                        'Service','AboutOTHoursMonth','OvertimeWage','yearsCost','AnnualSalaries');
    $name = "PersonnelFinalPriceList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function createPersonnelAbilityExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage') || !$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $salary = new Salary();
    list($Pname,$Pfamily,$Pcode,$Punit,$RsDate,$ReDate,$TsAmount,$TeAmount,$Pname1,$Pfamily1,$Pcode1,$Punit1,$RsDate1,$ReDate1,$TsAmount1,$TeAmount1,$ability,$status) = $ut->varCleanInput(array('Pname','Pfamily','Pcode','Punit','RsDate','ReDate','TsAmount','TeAmount','Pname1','Pfamily1','Pcode1','Punit1','RsDate1','ReDate1','TsAmount1','TeAmount1','ability','status'));
    $res = $salary->getPersonnelAbilityExcel($Pname,$Pfamily,$Pcode,$Punit,$RsDate,$ReDate,$TsAmount,$TeAmount,$Pname1,$Pfamily1,$Pcode1,$Punit1,$RsDate1,$ReDate1,$TsAmount1,$TeAmount1,$ability,$status);
    $hdarray = array('نام','نام خانوادگی','کد پرسنلی','توانایی','میزان تسلط','گواهینامه');
    $fieldNames = array('Fname','Lname','PersonnelCode','Ability','proficiency','passedCourse');
    $name = "PersonnelAbilityList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showSalaryBenefitsManageUnitList(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $Salary = new Salary();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $Salary->getUnitList($page);
    if($page == 1){
        $_SESSION['calcUnit'] = $Salary->getUnitListCountRows();
    }
    $totalRows = $_SESSION['calcUnit'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام واحد";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Uname";
    $c++;

    $feilds[$c]['title']="نوع واحد";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Utype";
    $c++;

    if($acm->hasAccess('unitEfficiencyAccess')){
        $feilds[$c]['title']="نام پرسنل";
        $feilds[$c]['width']="42%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="Udesc";
        $c++;

        $feilds[$c]['title']="راندمان واحد";
        $feilds[$c]['width']="8%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="efficiency";
        $c++;

        $feilds[$c]['title']="تاریخ ثبت";
        $feilds[$c]['width']="8%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="cDate";
        $c++;

        $feilds[$c]['title']="راندمان واحد";
        $feilds[$c]['width']="8%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="btn";
        $feilds[$c]['param']="RowID";
        $feilds[$c]['onclick']="editCreateEfficiency";
        $feilds[$c]['icon']="fa-chart-line";
    }else{
        $feilds[$c]['title']="نام پرسنل";
        $feilds[$c]['width']="66%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="Udesc";
    }

    $pagerType = 1;
    $listTitle = " تعداد واحدهای اداری/تولیدی : ".$totalRows." عدد ";
    $tableID = "unitManageBody-table";
    $jsf = "showSalaryBenefitsManageUnitList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getUnitInfo(){
    $uti = new Utility();
    list($uid) = $uti->varCleanInput(array('uid'));
    if(!intval($uid)){
        $res = "شناسه واحد بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Salary = new Salary();
    $res = $Salary->unitInfo($uid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateUnit(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Salary = new Salary();
    list($uid,$Uname,$Udesc,$Utype) = $uti->varCleanInput(array('uid','Uname','Udesc','Utype'));
    if(intval($uid) > 0){ //edit
        $res = $Salary->editUnit($uid,$Uname,$Udesc,$Utype);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{ //create
        $res = $Salary->createUnit($Uname,$Udesc,$Utype);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getPersonnelInfo(){
    $uti = new Utility();
    list($pid) = $uti->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه پرسنل بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Salary = new Salary();
    $res = $Salary->personnelInfo($pid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getPersonnelDocInfo(){
    $uti = new Utility();
    list($pid) = $uti->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه پرسنل بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Salary = new Salary();
    $res = $Salary->personnelDocInfo($pid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreatePersonnel(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Sy = new Salary();
    list($pid,$Fname,$Lname,$fatherName,$Pcode,$birthDate,$RecruitmentDate,$Sdate,$Edate,$phone,$mobile,$TermContract,$monthTrial,
         $BCertificate,$Issued,$NationalCode,$MaritalStatus,$Address,$insuranceNumber,$AccountNum,$EvaluationScore,
         $Unit,$dailyWages,$YearsCost,$RightHousing,$NumberChildren,$Child,$Grocery,$Shift,$OutOfList,$Service,$OTLunch,
         $OTService,$responsibilityRight,$hardWork,$jobRight,$financialAllowance,$description,$YFR,$AbilityProficiency,
         $DegreeFieldStudy,$AboutOTHoursMonth,$LeaveStatus,$OvertimeStatus,$sgid,$gender,$status,$NoInsurance,$type,
         $hourlyWages,$avgDayInMonth,$dailyDutyWorkingHours) = $uti->varCleanInput(array('pid','Fname','Lname','fatherName','Pcode','birthDate','RecruitmentDate','Sdate','Edate','phone','mobile','TermContract','monthTrial','BCertificate',
                                                                                         'Issued','NationalCode','MaritalStatus','Address','insuranceNumber','AccountNum','EvaluationScore','Unit','dailyWages','YearsCost','RightHousing',
                                                                                         'NumberChildren','Child','Grocery','Shift','OutOfList','Service','OTLunch','OTService','responsibilityRight','hardWork','jobRight',
                                                                                         'financialAllowance','description','YFR','AbilityProficiency','DegreeFieldStudy','AboutOTHoursMonth','LeaveStatus','OvertimeStatus',
                                                                                         'sgid','gender','status','NoInsurance','type','hourlyWages','avgDayInMonth','dailyDutyWorkingHours'));
    $dailyWages = str_replace(',','',$dailyWages);
    $YearsCost = str_replace(',','',$YearsCost);
    $RightHousing = str_replace(',','',$RightHousing);
    $Child = str_replace(',','',$Child);
    $Grocery = str_replace(',','',$Grocery);
    $Shift = str_replace(',','',$Shift);
    $OutOfList = str_replace(',','',$OutOfList);
    $Service = str_replace(',','',$Service);
    $OTLunch = str_replace(',','',$OTLunch);
    $OTService = str_replace(',','',$OTService);
    $responsibilityRight = str_replace(',','',$responsibilityRight);
    $hardWork = str_replace(',','',$hardWork);
    $jobRight = str_replace(',','',$jobRight);
    $financialAllowance = str_replace(',','',$financialAllowance);
    $hourlyWages = str_replace(',','',$hourlyWages);
    if(intval($pid) > 0){ //edit
        $leaveDate=$_POST['leaveDate'];
        error_log('leaveDate:'.$leaveDate);
        $res = $Sy->editPersonnel($pid,$Fname,$Lname,$fatherName,$Pcode,$birthDate,$RecruitmentDate,$Sdate,$Edate,$phone,$mobile,$TermContract,$monthTrial,
                                  $BCertificate,$Issued,$NationalCode,$MaritalStatus,$Address,$insuranceNumber,$AccountNum,$EvaluationScore,
                                  $Unit,$dailyWages,$YearsCost,$RightHousing,$NumberChildren,$Child,$Grocery,$Shift,$OutOfList,$Service,$OTLunch,
                                  $OTService,$responsibilityRight,$hardWork,$jobRight,$financialAllowance,$description,$YFR,$AbilityProficiency,
                                  $DegreeFieldStudy,$AboutOTHoursMonth,$LeaveStatus,$OvertimeStatus,$sgid,$gender,$status,$NoInsurance,$type,
                                  $hourlyWages,$avgDayInMonth,$dailyDutyWorkingHours,$leaveDate);
        if(intval($res) == -1){
            $res = "تعداد مدرک و رشته تحصیلی همخوانی ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif(intval($res) == -2){
            $res = "تعداد توانایی ها و میزان تسلط آنها همخوانی ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif ($res == true){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{ //create
        $res = $Sy->createPersonnel($Fname,$Lname,$fatherName,$Pcode,$birthDate,$RecruitmentDate,$Sdate,$Edate,$phone,$mobile,$TermContract,$monthTrial,
                                    $BCertificate,$Issued,$NationalCode,$MaritalStatus,$Address,$insuranceNumber,$AccountNum,$EvaluationScore,
                                    $Unit,$dailyWages,$YearsCost,$RightHousing,$NumberChildren,$Child,$Grocery,$Shift,$OutOfList,$Service,$OTLunch,
                                    $OTService,$responsibilityRight,$hardWork,$jobRight,$financialAllowance,$description,$YFR,$AbilityProficiency,
                                    $DegreeFieldStudy,$AboutOTHoursMonth,$LeaveStatus,$OvertimeStatus,$sgid,$gender,$status,$NoInsurance,$type,
                                    $hourlyWages,$avgDayInMonth,$dailyDutyWorkingHours);
        if(intval($res) == -1){
            $res = "تعداد مدرک و رشته تحصیلی همخوانی ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif(intval($res) == -2){
            $res = "تعداد توانایی ها و میزان تسلط آنها همخوانی ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif ($res == true){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function editAllPersonnel(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $Sy = new Salary();
    $res = $Sy->editAllPersonnel();
    if ($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doPrintPersonnelAgreement(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $salary = new Salary();
    list($pid) = $ut->varCleanInput(array('pid'));
    $htm = $salary->getPersonnelAgreementHtm($pid);
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function printPersonnelCosts(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $salary = new Salary();
    list($pid) = $ut->varCleanInput(array('pid'));
    $htm = $salary->getPersonnelCostsHTM($pid);
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function dodeletePersonnel(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Salary = new Salary();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه کاربر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $Salary->deletePersonnel($pid);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function ShowOtherInfoPersonnelCost(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه پرسنل بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $salary = new Salary();
    $res = $salary->OtherInfoPersonnelHTM($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowOtherInfoPersonnel(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه پرسنل بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $salary = new Salary();
    $res = $salary->OtherInfoPersonnelMHTM($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function doEditDocumentPersonnel(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Sy = new Salary();
    list($pid,$Questionnaire,$Recognizance,$NationalCard,$NationalCardDependants,$InsuranceBooklet,$CardMilitary,$InsuranceRecords,
         $DegreeEducation,$Photo,$Certificate,$VerificationServiceRecords,$LackBackground,$AccountNumber,
         $CheckPromissoryNote,$Experiments,$QuestionnaireDesc,$RecognizanceDesc,$NationalCardDesc,$NationalCardDependantsDesc,
         $InsuranceBookletDesc,$CardMilitaryDesc,$InsuranceRecordsDesc,$DegreeEducationDesc,$PhotoDesc,$CertificateDesc,
         $VerificationServiceRecordsDesc,$LackBackgroundDesc,$AccountNumberDesc,$CheckPromissoryNoteDesc,$ExperimentsDesc) = $uti->varCleanInput(array('pid','Questionnaire','Recognizance','NationalCard','NationalCardDependants','InsuranceBooklet','CardMilitary',
                                                                                                                                                       'InsuranceRecords','DegreeEducation','Photo','Certificate','VerificationServiceRecords','LackBackground','AccountNumber',
                                                                                                                                                       'CheckPromissoryNote','Experiments','QuestionnaireDesc','RecognizanceDesc','NationalCardDesc','NationalCardDependantsDesc',
                                                                                                                                                       'InsuranceBookletDesc','CardMilitaryDesc','InsuranceRecordsDesc','DegreeEducationDesc','PhotoDesc','CertificateDesc',
                                                                                                                                                       'VerificationServiceRecordsDesc','LackBackgroundDesc','AccountNumberDesc','CheckPromissoryNoteDesc','ExperimentsDesc'));
        $res = $Sy->createPersonnelDocument($pid,$Questionnaire,$Recognizance,$NationalCard,$NationalCardDependants,$InsuranceBooklet,$CardMilitary,$InsuranceRecords,
                                    $DegreeEducation,$Photo,$Certificate,$VerificationServiceRecords,$LackBackground,$AccountNumber,
                                    $CheckPromissoryNote,$Experiments,$QuestionnaireDesc,$RecognizanceDesc,$NationalCardDesc,$NationalCardDependantsDesc,
                                    $InsuranceBookletDesc,$CardMilitaryDesc,$InsuranceRecordsDesc,$DegreeEducationDesc,$PhotoDesc,$CertificateDesc,
                                    $VerificationServiceRecordsDesc,$LackBackgroundDesc,$AccountNumberDesc,$CheckPromissoryNoteDesc,$ExperimentsDesc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
}

function getAbilityList(){
    $salary = new Salary();
    $ut = new Utility();
    list($gid) = $ut->varCleanInput(array('gid'));
    $res = $salary->getAbilities($gid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ توانایی تعریف نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getAbilityList1(){
    $salary = new Salary();
    $ut = new Utility();
    list($gid) = $ut->varCleanInput(array('gid'));
    $res = $salary->getAbilities1($gid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ توانایی تعریف نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doCreateAbility(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Salary = new Salary();
    list($ability,$group,$sgroup) = $uti->varCleanInput(array('ability','group','sgroup'));
    $res = $Salary->createAbility($ability,$group,$sgroup);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function overtimeCalcluteStatus(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Salary = new Salary();
    list($OvertimeStatus,$pid) = $uti->varCleanInput(array('OvertimeStatus','pid'));
    $res = $Salary->overtimeCalcluteStatusChange($OvertimeStatus,$pid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function leaveCalcluteStatus(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Salary = new Salary();
    list($LeaveStatus,$pid) = $uti->varCleanInput(array('LeaveStatus','pid'));
    $res = $Salary->leaveCalcluteStatusChange($LeaveStatus,$pid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function aboutOTHoursMonthChange(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Salary = new Salary();
    list($otHour,$pid) = $uti->varCleanInput(array('otHour','pid'));
    $res = $Salary->aboutOTHoursMonthChange($otHour,$pid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getSalaryGroupInfo(){
    $Salary = new Salary();
    $res = $Salary->salaryGroupInfo();
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditPersonnelSalaryGroup(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Sy = new Salary();
    list($g1,$min1,$max1,$ac1,$g2,$min2,$max2,$ac2,$g3,$min3,$max3,$ac3,$g4,$min4,$max4,$ac4,$g5,$min5,$max5,$ac5,$g6,$min6,$max6,$ac6,$g7,$min7,$max7,$ac7,$g8,$min8,$max8,$ac8,$g9,$min9,$max9,$ac9,$g10,$min10,$max10,$ac10) = $uti->varCleanInput(array('g1','min1','max1','ac1','g2','min2','max2','ac2','g3','min3','max3','ac3','g4','min4','max4','ac4','g5','min5','max5','ac5','g6','min6','max6','ac6','g7','min7','max7','ac7','g8','min8','max8','ac8','g9','min9','max9','ac9','g10','min10','max10','ac10'));
    $min1 = str_replace(',','',$min1);
    $max1 = str_replace(',','',$max1);
    $min2 = str_replace(',','',$min2);
    $max2 = str_replace(',','',$max2);
    $min3 = str_replace(',','',$min3);
    $max3 = str_replace(',','',$max3);
    $min4 = str_replace(',','',$min4);
    $max4 = str_replace(',','',$max4);
    $min5 = str_replace(',','',$min5);
    $max5 = str_replace(',','',$max5);
    $min6 = str_replace(',','',$min6);
    $max6 = str_replace(',','',$max6);
    $min7 = str_replace(',','',$min7);
    $max7 = str_replace(',','',$max7);
    $min8 = str_replace(',','',$min8);
    $max8 = str_replace(',','',$max8);
    $min9 = str_replace(',','',$min9);
    $max9 = str_replace(',','',$max9);
    $min10 = str_replace(',','',$min10);
    $max10 = str_replace(',','',$max10);
    $res = $Sy->editPersonnelSalaryGroup($g1,$min1,$max1,$ac1,$g2,$min2,$max2,$ac2,$g3,$min3,$max3,$ac3,$g4,$min4,$max4,$ac4,$g5,$min5,$max5,$ac5,$g6,$min6,$max6,$ac6,$g7,$min7,$max7,$ac7,$g8,$min8,$max8,$ac8,$g9,$min9,$max9,$ac9,$g10,$min10,$max10,$ac10);
    if ($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getSalaryGroupRange(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Salary = new Salary();
    list($group) = $uti->varCleanInput(array('group'));
    $res = $Salary->getGroupSalaryInfo($group);
    if ($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات گروه دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getPersonnelSalaryGroupList(){
    $salary = new Salary();
    $ut = new Utility();
    list($sgid) = $ut->varCleanInput(array('sgid'));
    $res = $salary->getPersonnelSalaryGroupList($sgid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ پرسنلی در این گروه یافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doComparePersonnelSalaryGroup(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($sGroup,$personnel,$method) = $ut->varCleanInput(array('sGroup','personnel','method'));
    $salary = new Salary();
    $res = $salary->comparePersonnelSalaryGroupHTM($sGroup,$personnel,$method);
    $out = "true";
    response($res,$out);
    exit;
}

function getPersonnelDeficitDocumentsExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('salaryBenefitsManage') || !$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $salary = new Salary();
    $res = $salary->getExcelDeficitDocuments();
    $hdarray = array('نام و نام خانوادگی','پرسشنامه های استخدام','تعهدنامه های بدو استخدام','شناسنامه و کارت ملی متقاضی','شناسنامه و کارت ملی افراد تحت تکفل','دفترچه بیمه تامین اجتماعی','کارت پایان خدمت یا معافی سربازی','سوابق بیمه تامین اجتماعی','آخرین مدرک تحصیلی','عکس 4*3','گواهینامه های آموزشی مهارتی','تایید سوابق خدمت و حسن انجام کار','اصل تایید عدم سو پیشینه','شماره حساب سیبا - بانک ملی','چک یا سفته ضمانت','آزمایشات بدو استخدام');
    $fieldNames = array('name','porsesh','taahod','shcardm','shcardt','daftar','cardpkh','sbime','amadrakt','aks','govmaharat','tsavabeghkh','soopishine','naccount','check','azmayesh');
    $name = "DeficitDocumentsList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ مدیریت روزهای در دسترس +++++++++++++++++++++++

function showAvailableDayManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('manageAvailableDay')){
        die("access denied");
        exit;
    }
    $days = new Days();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $days->getAvailableDayList($page);
    if($page == 1){
        $_SESSION['calcAvailableDay'] = $days->getAvailableDayListCountRows();
    }
    $totalRows = $_SESSION['calcAvailableDay'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="سال";
    $feilds[$c]['width']="19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Year";
    $c++;

    $feilds[$c]['title']="کل روزهای سال";
    $feilds[$c]['width']="19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="AllDaysOfYear";
    $c++;

    $feilds[$c]['title']="تعطیلات رسمی";
    $feilds[$c]['width']="19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="OfficialHolidays";
    $c++;

    $feilds[$c]['title']="مرخصی ها";
    $feilds[$c]['width']="19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Vacations";
    $c++;

    $feilds[$c]['title']="تعداد روزهای در دسترس";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="AvailableDays";

    $pagerType = 1;
    $listTitle = " تعداد سالهای ثبت شده : ".$totalRows." عدد ";
    $tableID = "availableDayManageBody-table";
    $jsf = "showAvailableDayManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getAvailableDayInfo(){
    $uti = new Utility();
    list($ADid) = $uti->varCleanInput(array('ADid'));
    if(!intval($ADid)){
        $res = "شناسه واحد بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $days = new Days();
    $res = $days->availableDayInfo($ADid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateAvailableDay(){
    $acm = new acm();
    if(!$acm->hasAccess('manageAvailableDay')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $days = new Days();
    list($ADid,$yy,$totalDay,$OHolidays,$Vacations) = $uti->varCleanInput(array('ADid','yy','totalDay','OHolidays','Vacations'));
    if(intval($ADid) > 0){//edit
        $res = $days->editAvailableDay($ADid,$yy,$totalDay,$OHolidays,$Vacations);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $days->createAvailableDay($yy,$totalDay,$OHolidays,$Vacations);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

//++++++++++++++++++++++ مدیریت صنایع +++++++++++++++++++++++

function saveTiming(){
    $acm = new acm();
    if(!$acm->hasAccess('industrialManagement')){
        die("access denied");
        exit;
    }
    $Industry = new Industry();
    $res = $Industry->saveTiming();
    $out = "true";
    response($res,$out);
    exit;
}

function showIndustrialManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('industrialManagement')){
        die("access denied");
        exit;
    }
    $Industry = new Industry();
    $list = new Listview();
    $ut = new Utility();
    list($page,$pName,$pCode,$supply) = $ut->varCleanInput(array('page','pName','pCode','supply'));
    $res = $Industry->getIndustryList($pName,$pCode,$supply,$page);
    if($page == 1){
        $_SESSION['calcIndustrial'] = $Industry->getIndustryListCountRows($pName,$pCode,$supply);
    }
    $totalRows = $_SESSION['calcIndustrial'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام قطعه";
    $feilds[$c]['width']="46%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pName";
    $c++;

    $feilds[$c]['title']="کد قطعه";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pCode";
    $c++;

    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="ChangingHow_supply";
    $c++;

    $feilds[$c]['title']="ثبت زمان سنجی هر واحد";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowTimingInfoPiece";
    $feilds[$c]['icon']="fa-stopwatch";
    $c++;

    $feilds[$c]['title']="ثبت کدهای مرتبط";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowOtherCodesPiece";
    $feilds[$c]['icon']="fa-copyright";

    $pagerType = 1;
    $listTitle = " تعداد قطعات تولیدی : ".$totalRows." عدد ";
    $tableID = "industrialManageBody-table";
    $jsf = "showIndustrialManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getExcelZamanSanji(){
    $acm = new acm();
    if(!$acm->hasAccess('industrialManagement') || !$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $industry = new Industry();
    list($pName,$pCode,$supply) = $ut->varCleanInput(array('pName','pCode','supply'));
    $res = $industry->getExcelZamanSanji($pName,$pCode,$supply);
    $hdarray = array('نام قطعه','کد قطعه','ریخته گری','فورج','ماشین کاری','پرداخت','آب کاری','اسکاچ','رنگ','PVD','خط شیلنگ','خط لوله','تزریق پلاستیک','مونتاژ',
                     'کد سیاهتاب','کد فورج','کد ماشین کاری','کد پرداخت','کد نیکل','کد آب کاری شده','کد آب بر داری شده','کد طلایی','کد طلایی مات',
                     'کد طلایی روشن','کد طلایی تیره','کد رنگی','کد دکورال','کد استیل','قطعه خام تزریق','قطعه نهایی تزریق','کد نهایی');
    $fieldNames = array('pName','pCode','Casting_timing','Forging_timing','Machining_timing','Polishing_timing','Plating_timing','Scotching_timing',
                        'Paint_timing','PVD_timing','Hose_timing','Pipe_timing','PlasticInjection_timing','Assembly_timing',
                        'rawCode','forgingCode','machiningCode','polishingCode','nickelCode','platingCode',
                        'pushplatingCode','goldenCode','mattgoldenCode','lightgoldenCode','darkgoldenCode',
                        'paintCode','decoralCode','steelCode','rawppCode','finalppCode','finalCode');
    $name = "PieceTimingList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function ShowOtherCodesPiece(){
    $acm = new acm();
    if(!$acm->hasAccess('industrialManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه قطعه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Industry = new Industry();
    $res = $Industry->PieceOtherCodesInfoHTM($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function doEditCreateOtherPieceCode(){
    $acm = new acm();
    if(!$acm->hasAccess('industrialManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $industry = new Industry();
    $myJsonString = $_POST['myJsonString'];
    $myJsonString = json_decode($myJsonString);
    list($pid) = $ut->varCleanInput(array('pid'));
    $res = $industry->editCreateOtherPieceCode($pid,$myJsonString);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function ShowTimingInfoPiece(){
    $acm = new acm();
    if(!$acm->hasAccess('industrialManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه قطعه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Industry = new Industry();
    $res = $Industry->PieceTimingInfoHTM($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function doEditCreateUnitTimingPiece(){
    $acm = new acm();
    if(!$acm->hasAccess('industrialManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Industry = new Industry();
    $myJsonString = $_POST['myJsonString'];
    $myJsonString = json_decode($myJsonString);
    list($pid) = $ut->varCleanInput(array('pid'));
    $res = $Industry->editCreateUnitTimingPiece($pid,$myJsonString);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showUnitEfficiencyManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('industrialManagement')){
        die("access denied");
        exit;
    }
    $Industry = new Industry();
    $list = new Listview();
    $ut = new Utility();
    list($page,$unit,$UEfficiency,$UYear,$UMonth,$UDate) = $ut->varCleanInput(array('page','unit','UEfficiency','UYear','UMonth','UDate'));
    $res = $Industry->getUnitEfficiencyList($unit,$UEfficiency,$UYear,$UMonth,$UDate,$page);
    if($page == 1){
        $_SESSION['calcEfficiency'] = $Industry->getUnitEfficiencyListCountRows($unit);
    }
    $totalRows = $_SESSION['calcEfficiency'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام واحد";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Uname";
    $c++;

    $feilds[$c]['title']="راندمان واحد";
    $feilds[$c]['width']="16%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="efficiency";
    $c++;

    $feilds[$c]['title']="تاریخ ایجاد";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="createDate";
    $c++;

    $feilds[$c]['title']="سال مربوطه";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="registrationYear";
    $c++;

    $feilds[$c]['title']="ماه مربوطه";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="registrationMonth";

    $pagerType = 1;
    $listTitle = " تعداد موارد ثبت شده : ".$totalRows." عدد ";
    $tableID = "unitEfficiencyManageBody-table";
    $jsf = "showUnitEfficiencyManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getUnitEfficiency(){
    $uti = new Utility();
    list($UEid) = $uti->varCleanInput(array('UEid'));
    if(!intval($UEid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Industry = new Industry();
    $res = $Industry->unitEfficiency($UEid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateUnitEfficiency(){
    $acm = new acm();
    if(!$acm->hasAccess('industrialManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Industry = new Industry();
    list($UEid,$efficiency) = $ut->varCleanInput(array('UEid','efficiency'));
    $res = $Industry->editUnitEfficiency($UEid,$efficiency);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ زمان سنجی محصولات +++++++++++++++++++++++

function showIndustrialGManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('industrialManagement')){
        die("access denied");
        exit;
    }
    $Industry = new Industry();
    $list = new Listview();
    $ut = new Utility();
    list($page,$gName,$gCode) = $ut->varCleanInput(array('page','gName','gCode'));
    $res = $Industry->getGIndustryList($gName,$gCode,$page);
    if($page == 1){
        $_SESSION['calcGIndustrial'] = $Industry->getGIndustryListCountRows($gName,$gCode);
    }
    $totalRows = $_SESSION['calcGIndustrial'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']="51%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="زمان سنجی مونتاژ (ثانیه)";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Montage_timing";
    $c++;

    $feilds[$c]['title']="ثبت زمان سنجی مونتاژ";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowTimingInfoGood";
    $feilds[$c]['icon']="fa-stopwatch";

    $pagerType = 1;
    $listTitle = " تعداد قطعات تولیدی : ".$totalRows." عدد ";
    $tableID = "industrialGManageBody-table";
    $jsf = "showIndustrialGManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function ShowTimingInfoGood(){
    $acm = new acm();
    if(!$acm->hasAccess('industrialManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($gid) = $ut->varCleanInput(array('gid'));
    if(!intval($gid)){
        $res = "شناسه محصول بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Industry = new Industry();
    $res = $Industry->goodTimingInfoHTM($gid);
    $out = "true";
    response($res,$out);
    exit;
}

function doEditCreateUnitTimingGood(){
    $acm = new acm();
    if(!$acm->hasAccess('industrialManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Industry = new Industry();
    list($gid,$gTiming) = $ut->varCleanInput(array('gid','gTiming'));
    $res = $Industry->editCreateUnitTimingGood($gid,$gTiming);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ درصد ضایعات و بهره +++++++++++++++++++++++

function showPercentagesManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('percentagesAccess')){
        die("access denied");
        exit;
    }
    $brass = new Brass();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $brass->getPercentagesList($page);
    if($page == 1){
        $_SESSION['calcPercentages'] = $brass->getPercentagesListCountRows();
    }
    $totalRows = $_SESSION['calcPercentages'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="درصد ضایعات ریخته گری";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="wCasting";
    $c++;

    $feilds[$c]['title']="درصد ضایعات ماشینکاری";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="wMachining";
    $c++;

    $feilds[$c]['title']="درصد ضایعات پرداخت";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="wPolishing";
    $c++;

    $feilds[$c]['title']="درصد ضایعات براده ماشینکاری";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="wMachiningChips";
    $c++;

    $feilds[$c]['title']="درصد ضایعات خاک پرداخت";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="wPolishingSoil";
    $c++;

    $feilds[$c]['title']="درصد بهره";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Scount";
    $c++;

    $feilds[$c]['title']="درصد مالیات";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="tax";

    $pagerType = 0;
    $listTitle = " تعداد ثبت شده : ".$totalRows." عدد ";
    $tableID = "percentagesManageBody-table";
    $jsf = "showPercentagesManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getPercentagesInfo(){
    $brass = new Brass();
    $res = $brass->percentagesInfo();
    $out = "true";
    response($res,$out);
    exit;
}

function doEditCreatePercentages(){
    $acm = new acm();
    if(!$acm->hasAccess('percentagesAccess')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $brass = new Brass();
    list($Wid,$WCasting,$WMachining,$WPolishing,$WMachiningChips,$WPolishingSoil,$Scount,$tax) = $uti->varCleanInput(array('Wid','WCasting','WMachining','WPolishing','WMachiningChips','WPolishingSoil','Scount','tax'));

    $res = $brass->editCreatePercentages($Wid,$WCasting,$WMachining,$WPolishing,$WMachiningChips,$WPolishingSoil,$Scount,$tax);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//+++++++++++++++++++++++++++++++ مدیریت برنامه ها +++++++++++++++++++++++++++++++

function programManage(){
    $acm = new acm();
    if(!$acm->hasAccess('programManage')){
        die("access denied");
        exit;
    }
    $Program = new Program();
    $htm = $Program->getProgramManageHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showProgramManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('programManage')){
        die("access denied");
        exit;
    }
    $Program = new Program();
    $list = new Listview();
    $ut = new Utility();
    list($page,$pCode,$gName,$cName,$sDate,$eDate) = $ut->varCleanInput(array('page','pCode','gName','cName','sDate','eDate'));
    $res = $Program->getProgramList($pCode,$gName,$cName,$sDate,$eDate,$page);
    if($page == 1){
        $_SESSION['calcProgram'] = $Program->getProgramListCountRows($pCode,$gName,$cName,$sDate,$eDate);
    }
    $totalRows = $_SESSION['calcProgram'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="شماره برنامه";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="programCode";
    $c++;

    $feilds[$c]['title']="نام ایجاد کننده";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="name";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']="22%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="تعداد";
    $feilds[$c]['width']="5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="number";
    $c++;

    $feilds[$c]['title']="مونتاژ شده";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Assembled";
    $c++;

    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="programDate";
    $c++;

    $feilds[$c]['title']="نام مشتری";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="customerName";
    $c++;

    $feilds[$c]['title']="شرح فعالیت";
    $feilds[$c]['width']="18%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="activityDescription";
    $c++;

    $feilds[$c]['title']="اجزا محصول";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showPieceOfGood";
    $feilds[$c]['icon']="fa-puzzle-piece";

    $pagerType = 1;
    $listTitle = " تعداد برنامه ها : ".$totalRows." عدد ";
    $tableID = "programManageBody-table";
    $jsf = "showProgramManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getProgramInfo(){
    $uti = new Utility();
    list($pid) = $uti->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه برنامه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Program = new Program();
    $res = $Program->programInfo($pid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateProgram(){
    $acm = new acm();
    if(!$acm->hasAccess('programManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Program = new Program();
    list($pid,$Pcode,$Gname,$Number,$pDate,$Cname,$desc) = $uti->varCleanInput(array('pid','Pcode','Gname','Number','pDate','Cname','desc'));
    if(intval($pid) > 0){//edit
        $res = $Program->editProgram($pid,$Pcode,$Number,$pDate,$Cname,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $Program->createProgram($Pcode,$Gname,$Number,$pDate,$Cname,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function showPieceOfGood(){
    $acm = new acm();
    if(!$acm->hasAccess('programManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه برنامه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $program = new Program();
    $res = $program->showPieceOfGood($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function dodeleteProgram(){
    $acm = new acm();
    if(!$acm->hasAccess('programManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $program = new Program();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه برنامه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $program->deleteProgram($pid);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function addDescForPiece(){
    $acm = new acm();
    if(!$acm->hasAccess('programManage')){
        die("access denied");
        exit;
    }
    $program = new Program();
    $ut = new Utility();
    list($programID) = $ut->varCleanInput(array('programID'));
    $myJsonString = $_POST['myJsonString'];
    $myJsonString = json_decode($myJsonString);
    $res = $program->addDescForPiece($myJsonString,$programID);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getExcelProgramInfo(){
    $acm = new acm();
    if(!$acm->hasAccess('programManage') || !$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $program = new Program();
    list($programID) = $ut->varCleanInput(array('programID'));
    $res = $program->reportProgramExcel($programID);
    $hdarray = array("نام قطعه","مقدار نیاز","مازاد","مقدار محصول","ضایعات","سالم","مغایرت","توضیحات");
    $fieldNames = array("pName","total","extra","totalAssembled","waste","healthy","Contradiction","desc");
    $name = "ProgramInfoList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++ گزارشات نرخ ارز +++++++++++++++++++

function currencyReportManage(){
    $acm = new acm();
    if(!$acm->hasAccess('currencyReportManage')){
        die("access denied");
        exit;
    }
    $Report= new Report();
    $htm = $Report->getCurrencyReportManageHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showCurrencyReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('currencyReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$cid,$sDate,$eDate) = $ut->varCleanInput(array('page','cid','sDate','eDate'));
    $res = $Report->getCurrencyReportList($cid,$sDate,$eDate,$page);
    if($page == 1){
        $_SESSION['calcCurrencyReport'] = $Report->getCurrencyReportListCountRows($cid,$sDate,$eDate);
    }
    $totalRows = $_SESSION['calcCurrencyReport'];
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="نام ارز";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="currencyName";
    $c++;

    $feilds[$c]['title']="نرخ روز";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="currency_Rate";
    $c++;

    $feilds[$c]['title']="نرخ تبدیل روز به دلار";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="exchange_Rate";
    $c++;

    $feilds[$c]['title']="تاریخ تغییر";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="changeDate";
    $c++;

    $feilds[$c]['title']="شخص ثبت کننده";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="name";

    $pagerType = 1;
    $listTitle = " تعداد ارز گزارش شده : ".$totalRows." عدد ";
    $tableID = "currencyManageBody-table";
    $jsf = "showCurrencyManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

//++++++++++++++++++++ گزارش تغییرات قطعه +++++++++++++++++++

function pieceChangeReportManage(){
    $acm = new acm();
    if(!$acm->hasAccess('pieceChangeReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $htm = $Report->getPieceChangeReportManageHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showPieceChangeReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('pieceChangeReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$pName,$fName,$pCode,$sDate,$eDate) = $ut->varCleanInput(array('page','pName','fName','pCode','sDate','eDate'));
    $res = $Report->getPieceChangeReportList($pName,$fName,$pCode,$sDate,$eDate,$page);
    if($page == 1){
        $_SESSION['calcPieceChangeReport'] = $Report->getPieceChangeReportListCountRows($pName,$fName,$pCode,$sDate,$eDate);
    }
    $totalRows = $_SESSION['calcPieceChangeReport'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="کد قطعه";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pCode";
    $c++;

    $feilds[$c]['title']="نام قطعه";
    $feilds[$c]['width']="23%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pName";
    $c++;

    $feilds[$c]['title']="نام فیلد تغییر کرده";
    $feilds[$c]['width']="19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fieldName_Fa";
    $c++;

    $feilds[$c]['title']="مقدار قبلی";
    $feilds[$c]['width']="19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="previousValue";
    $c++;

    $feilds[$c]['title']="مقدار فعلی";
    $feilds[$c]['width']="19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="currentValue";
    $c++;

    $feilds[$c]['title']="تاریخ تغییر";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="changeDate";

    $pagerType = 1;
    $listTitle = " تعداد گزارشات : ".$totalRows." عدد ";
    $tableID = "pieceChangeManageBody-table";
    $jsf = "showPieceChangeReportManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getFieldNameList(){
    $acm = new acm();
    if(!$acm->hasAccess('pieceChangeReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $res = $Report->getFieldName();
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ فیلدی تعریف نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++ گزارش تغییرات محصول +++++++++++++++++++

function goodChangeReportManage(){
    $acm = new acm();
    if(!$acm->hasAccess('goodChangeReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $htm = $Report->getGoodChangeReportManageHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showGoodChangeReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('goodChangeReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$gName,$fName,$gCode,$sDate,$eDate) = $ut->varCleanInput(array('page','gName','fName','gCode','sDate','eDate'));
    $res = $Report->getGoodChangeReportList($gName,$fName,$gCode,$sDate,$eDate,$page);
    if($page == 1){
        $_SESSION['calcGoodChangeReport'] = $Report->getGoodChangeReportListCountRows($gName,$fName,$gCode,$sDate,$eDate);
    }
    $totalRows = $_SESSION['calcGoodChangeReport'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']="23%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="نام فیلد تغییر کرده";
    $feilds[$c]['width']="19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fieldName_Fa";
    $c++;

    $feilds[$c]['title']="مقدار قبلی";
    $feilds[$c]['width']="19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="previousValue";
    $c++;

    $feilds[$c]['title']="مقدار فعلی";
    $feilds[$c]['width']="19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="currentValue";
    $c++;

    $feilds[$c]['title']="تاریخ تغییر";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="changeDate";

    $pagerType = 1;
    $listTitle = " تعداد گزارشات : ".$totalRows." عدد ";
    $tableID = "pieceChangeManageBody-table";
    $jsf = "showGoodChangeReportManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getFieldNamesList(){
    $acm = new acm();
    if(!$acm->hasAccess('goodChangeReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $res = $Report->getFieldNames();
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ فیلدی تعریف نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ گزارش قیمت قطعات / محصولات +++++++++++++++++++++++

function priceReportManage(){
    $acm = new acm();
    if(!$acm->hasAccess('priceReportManage')){
        die("access denied");
        exit;
    }
    $report = new Report();
    $send = $report->getPriceReportHtm();
    $res = $send;
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++ گزارش قیمت قطعات +++++++++++++++++++

function showPiecePriceReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('piecePriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$pName,$pCode,$supply,$error) = $ut->varCleanInput(array('page','pName','pCode','supply','error'));
    $res = $Report->getPiecePriceReportList($pName,$pCode,$supply,$error,$page);
    if($page == 1){
        $_SESSION['calcPiecePriceReport'] = $Report->getPiecePriceReportListCountRows($pName,$pCode,$supply,$error);
    }
    $totalRows = $_SESSION['calcPiecePriceReport'];
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="نام قطعه";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pName";
    $feilds[$c]['color']='yes';
    $c++;

    $feilds[$c]['title']="کد قطعه";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pCode";
    $c++;

    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="ChangingHow_supply";
    $c++;

/*    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="select";
    $feilds[$c]['onchange']="updateRowPiecePrice";
    $feilds[$c]['param']='RowID';
    $feilds[$c]['fname']="ChangingHow_supply";
    $c++;*/

    $feilds[$c]['title']="قیمت نهایی مواد";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="priceFinalRawMaterial";
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد (نقدی)";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="priceFinalRawMaterialCash";
    $c++;

    $feilds[$c]['title']="مجموع قیمت نهایی و هزینه ها";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalPC";
    $c++;

    $feilds[$c]['title']="مجموع قیمت نهایی و هزینه ها (نقدی)";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalPCC";
    $c++;

    $feilds[$c]['title']="ریز قیمت مراحل تولید";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showSmallPrice";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="ریز هزینه های تولید";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showSmallExpenses";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="خطاها";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showErrorOfPiece";
    //$feilds[$c]['disabled']="yes";
    $feilds[$c]['disabled']="";

    $pagerType = 1;
    $listTitle = " تعداد قطعات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی قطعات : "." $res[1]";
    $tableID = "piecePriceReportManageBody-table";
    $jsf = "showPiecePriceReportManageList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showMPiecePriceReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('piecePriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$pName) = $ut->varCleanInput(array('page','pName'));
    $res = $Report->getMPiecePriceReportList($pName,$page);
    if($page == 1){
        $_SESSION['calcMPiecePriceReport'] = $Report->getMPiecePriceReportListCountRows($pName);
    }
    $totalRows = $_SESSION['calcMPiecePriceReport'];
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="نام قطعه";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pName";
    $feilds[$c]['color']='yes';
    $c++;

    $feilds[$c]['title']="کد قطعه";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pCode";
    $c++;

    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="ChangingHow_supply";
    $c++;

/*    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="select";
    $feilds[$c]['onchange']="updateRowPiecePrice";
    $feilds[$c]['param']='RowID';
    $feilds[$c]['fname']="ChangingHow_supply";
    $c++;*/

    $feilds[$c]['title']="قیمت نهایی مواد";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="priceFinalRawMaterial";
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد (نقدی)";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="priceFinalRawMaterialCash";
    $c++;

    $feilds[$c]['title']="مجموع قیمت نهایی و هزینه ها";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalPC";
    $c++;

    $feilds[$c]['title']="مجموع قیمت نهایی و هزینه ها (نقدی)";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalPCC";
    $c++;

    $feilds[$c]['title']="ریز قیمت مراحل تولید";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showSmallPrice";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="ریز هزینه های تولید";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showSmallExpenses";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="خطاها";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showErrorOfPiece";
    $feilds[$c]['disabled']="yes";

    $pagerType = 1;
    $listTitle = " تعداد قطعات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی قطعات : "." $res[1]";
    $tableID = "piecePriceReportManageBody-table";
    $jsf = "showMPagePiecePriceReportManageList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showMPagePiecePriceReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('piecePriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$pName) = $ut->varCleanInput(array('page','pName'));
    $res = $Report->getMPagePiecePriceReportList($page);
    if($page > 1){
        $_SESSION['calcMPPiecePriceReport'] = $Report->getMPiecePriceReportListCountRows($pName);
    }
    $totalRows = $_SESSION['calcMPPiecePriceReport'];
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="نام قطعه";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pName";
    $feilds[$c]['color']='yes';
    $c++;

    $feilds[$c]['title']="کد قطعه";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pCode";
    $c++;

    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="ChangingHow_supply";
    $c++;

/*    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="select";
    $feilds[$c]['onchange']="updateRowPiecePrice";
    $feilds[$c]['param']='RowID';
    $feilds[$c]['fname']="ChangingHow_supply";
    $c++;*/

    $feilds[$c]['title']="قیمت نهایی مواد";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="priceFinalRawMaterial";
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد (نقدی)";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="priceFinalRawMaterialCash";
    $c++;

    $feilds[$c]['title']="مجموع قیمت نهایی و هزینه ها";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalPC";
    $c++;

    $feilds[$c]['title']="مجموع قیمت نهایی و هزینه ها (نقدی)";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalPCC";
    $c++;

    $feilds[$c]['title']="ریز قیمت مراحل تولید";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showSmallPrice";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="ریز هزینه های تولید";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showSmallExpenses";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="خطاها";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showErrorOfPiece";
    $feilds[$c]['disabled']="yes";

    $pagerType = 1;
    $listTitle = " تعداد قطعات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی قطعات : "." $res[1]";
    $tableID = "piecePriceReportManageBody-table";
    $jsf = "showMPagePiecePriceReportManageList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showBMPiecePriceReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('piecePriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $Report->getBMPiecePriceReportList($page);
    if($page == 1){
        $_SESSION['calcBMPiecePriceReport'] = $Report->getBMPiecePriceReportListCountRows();
    }
    $totalRows = $_SESSION['calcBMPiecePriceReport'];
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="نام قطعه";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pName";
    $feilds[$c]['color']='yes';
    $c++;

    $feilds[$c]['title']="کد قطعه";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pCode";
    $c++;

    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="ChangingHow_supply";
    $c++;

/*    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="select";
    $feilds[$c]['onchange']="updateRowPiecePrice";
    $feilds[$c]['param']='RowID';
    $feilds[$c]['fname']="ChangingHow_supply";
    $c++;*/

    $feilds[$c]['title']="قیمت نهایی مواد";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="priceFinalRawMaterial";
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد (نقدی)";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="priceFinalRawMaterialCash";
    $c++;

    $feilds[$c]['title']="مجموع قیمت نهایی و هزینه ها";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalPC";
    $c++;

    $feilds[$c]['title']="مجموع قیمت نهایی و هزینه ها (نقدی)";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalPCC";
    $c++;

    $feilds[$c]['title']="ریز قیمت مراحل تولید";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showSmallPrice";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="ریز هزینه های تولید";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showSmallExpenses";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="خطاها";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showErrorOfPiece";
    $feilds[$c]['disabled']="yes";

    $pagerType = 1;
    $listTitle = " تعداد قطعات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی قطعات : "." $res[1]";
    $tableID = "piecePriceReportManageBody-table";
    $jsf = "showBMPagePiecePriceReportManageList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showBMPagePiecePriceReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('piecePriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $Report->getBMPagePiecePriceReportList($page);
    if($page > 1){
        $_SESSION['calcBMPPiecePriceReport'] = $Report->getBMPiecePriceReportListCountRows();
    }
    $totalRows = $_SESSION['calcBMPPiecePriceReport'];
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="نام قطعه";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pName";
    $feilds[$c]['color']='yes';
    $c++;

    $feilds[$c]['title']="کد قطعه";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pCode";
    $c++;

    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="ChangingHow_supply";
    $c++;

/*    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="select";
    $feilds[$c]['onchange']="updateRowPiecePrice";
    $feilds[$c]['param']='RowID';
    $feilds[$c]['fname']="ChangingHow_supply";
    $c++;*/

    $feilds[$c]['title']="قیمت نهایی مواد";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="priceFinalRawMaterial";
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد (نقدی)";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="priceFinalRawMaterialCash";
    $c++;

    $feilds[$c]['title']="مجموع قیمت نهایی و هزینه ها";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalPC";
    $c++;

    $feilds[$c]['title']="مجموع قیمت نهایی و هزینه ها (نقدی)";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalPCC";
    $c++;

    $feilds[$c]['title']="ریز قیمت مراحل تولید";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showSmallPrice";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="ریز هزینه های تولید";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showSmallExpenses";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="خطاها";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showErrorOfPiece";
    $feilds[$c]['disabled']="yes";

    $pagerType = 1;
    $listTitle = " تعداد قطعات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی قطعات : "." $res[1]";
    $tableID = "piecePriceReportManageBody-table";
    $jsf = "showBMPagePiecePriceReportManageList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

/*function showPiecePriceReportManageRowList(){
    $acm = new acm();
    if(!$acm->hasAccess('piecePriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $Report->getPiecePriceReportRowList($page);
    $totalRows = $Report->getPiecePriceReportRowListCountRows();
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="نام قطعه";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pName";
    $feilds[$c]['color']='yes';
    $c++;

    $feilds[$c]['title']="کد قطعه";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pCode";
    $c++;

    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="ChangingHow_supply";
    $c++;

    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="select";
    $feilds[$c]['onchange']="updateRowPiecePrice";
    $feilds[$c]['param']='RowID';
    $feilds[$c]['fname']="ChangingHow_supply";
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="priceFinalRawMaterial";
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد (نقدی)";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="priceFinalRawMaterialCash";
    $c++;

    $feilds[$c]['title']="مجموع قیمت نهایی و هزینه ها";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalPC";
    $c++;

    $feilds[$c]['title']="مجموع قیمت نهایی و هزینه ها (نقدی)";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalPCC";
    $c++;

    $feilds[$c]['title']="ریز قیمت مراحل تولید";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showSmallPrice";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="ریز هزینه های تولید";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showSmallExpenses";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="خطاها";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showErrorOfPiece";
    $feilds[$c]['disabled']="yes";

    $pagerType = 1;
    $listTitle = " تعداد قطعات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی قطعات : "." $res[1]";
    $tableID = "piecePriceReportManageBody-table";
    $jsf = "showPiecePriceReportManageRowList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}*/

function updatePiecePrice(){
    $acm = new acm();
    if(!$acm->hasAccess('piecePriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $res = $Report->updatePiecePrice();
    if(intval($res) == -1){
        $res = "تاریخ قیمت بار برنج منقضی شده است !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif(intval($res) == -2){
        $res = "اطلاعات بار برنج ثبت نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "بروزرسانی قیمت با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function resetPieceHowSupply(){
    $acm = new acm();
    if(!$acm->hasAccess('piecePriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $res = $Report->resetPieceHowSupply();
    if(intval($res) == -1){
        $res = "اطلاعات بار برنج ثبت نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function updateRowPiecePrice(){
    $acm = new acm();
    if(!$acm->hasAccess('piecePriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $ut = new Utility();
    list($pid,$howsupply) = $ut->varCleanInput(array('pid','howsupply'));
    $res = $Report->updateRowPiecePrice($pid,$howsupply);
    if(intval($res) == -1){
        $res = "تاریخ قیمت بار برنج منقضی شده است !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif(intval($res) == -2){
        $res = "اطلاعات بار برنج ثبت نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showSmallExpenses(){
    $acm = new acm();
    if(!$acm->hasAccess('piecePriceReportManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه قطعه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $report = new Report();
    $res = $report->pieceCostsInfoHTM($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function showSmallPrice(){
    $acm = new acm();
    if(!$acm->hasAccess('piecePriceReportManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه قطعه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $report = new Report();
    $res = $report->piecePriceInfoHTM($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function showErrorOfPiece(){
    $acm = new acm();
    if(!$acm->hasAccess('piecePriceReportManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه قطعه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $report = new Report();
    $res = $report->pieceErrorInfoHTM($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function doQuickPiecePriceCalc(){
    $acm = new acm();
    if(!$acm->hasAccess('piecePriceReportManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $report = new Report();
    list($HS,$BT,$wfm,$wmch,$wf,$pfm,$wfmt,$pmb,$wft) = $uti->varCleanInput(array('HS','BT','wfm','wmch','wf','pfm','wfmt','pmb','wft'));
    $res = $report->quickPiecePriceCalc($HS,$BT,$wfm,$wmch,$wf,$pfm,$wfmt,$pmb,$wft);
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++ گزارش قیمت محصولات +++++++++++++++++++

function showGoodPriceReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('goodPriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$gName,$gCode,$hCode) = $ut->varCleanInput(array('page','gName','gCode','hCode'));
    $res = $Report->getGoodPriceReportList($gName,$gCode,$hCode,$page);
    if($page == 1){
        $_SESSION['calcGoodPriceReport'] = $Report->getGoodPriceReportListCountRows($gName,$gCode,$hCode);
    }
    $totalRows = $_SESSION['calcGoodPriceReport'];
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']="5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']="18%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $feilds[$c]['color']='yes';
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="finalPriceMaterials";
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد - نقدی";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="finalPriceMaterialsCash";
    $c++;

    $feilds[$c]['title']="مجموع هزینه های تولید";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalProductionCosts";
    $c++;

    $feilds[$c]['title']="قیمت نهایی محصول با هزینه تولید";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fppPCosts";
    $c++;

    $feilds[$c]['title']="قیمت نهایی محصول با هزینه تولید - نقدی";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fppPCostsCash";
    $c++;

    $feilds[$c]['title']="خطاها";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showErrorOfGood";
    $feilds[$c]['disabled']="yes";
    $c++;

    $feilds[$c]['title']="اجزا محصول";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['icon']="fa-puzzle-piece";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showFinePriceOfGood";

    $pagerType = 1;
    $listTitle = " تعداد محصولات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی محصولات : "." $res[1] "." <br><br> <span style='float: right;'> تاریخ آخرین بروزرسانی BOM : </span>&nbsp;"." $res[2]";
    $tableID = "goodPriceReportManageBody-table";
    $jsf = "showGoodPriceReportManageList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showMGoodPriceReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('goodPriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$gName) = $ut->varCleanInput(array('page','gName'));
    $res = $Report->getMGoodPriceReportList($gName,$page);
    if($page == 1){
        $_SESSION['calcMGoodPriceReport'] = $Report->getMGoodPriceReportListCountRows($gName);
    }
    $totalRows = $_SESSION['calcMGoodPriceReport'];
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']="5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']="18%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $feilds[$c]['color']='yes';
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="finalPriceMaterials";
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد - نقدی";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="finalPriceMaterialsCash";
    $c++;

    $feilds[$c]['title']="مجموع هزینه های تولید";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalProductionCosts";
    $c++;

    $feilds[$c]['title']="قیمت نهایی محصول با هزینه تولید";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fppPCosts";
    $c++;

    $feilds[$c]['title']="قیمت نهایی محصول با هزینه تولید - نقدی";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fppPCostsCash";
    $c++;

    $feilds[$c]['title']="خطاها";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showErrorOfGood";
    $feilds[$c]['disabled']="yes";
    $c++;

    $feilds[$c]['title']="اجزا محصول";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['icon']="fa-puzzle-piece";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showFinePriceOfGood";

    $pagerType = 1;
    $listTitle = " تعداد محصولات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی محصولات : "." $res[1] "." <br><br> <span style='float: right;'> تاریخ آخرین بروزرسانی BOM : </span>&nbsp;"." $res[2]";
    $tableID = "goodPriceReportManageBody-table";
    $jsf = "showMPageGoodPriceReportManageList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showMPageGoodPriceReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('goodPriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$gName) = $ut->varCleanInput(array('page','gName'));
    $res = $Report->getMPageGoodPriceReportList($page);
    if($page > 1){
        $_SESSION['calcMPGoodPriceReport'] = $Report->getMGoodPriceReportListCountRows($gName);
    }
    $totalRows = $_SESSION['calcMPGoodPriceReport'];
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']="5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']="18%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $feilds[$c]['color']='yes';
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="finalPriceMaterials";
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد - نقدی";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="finalPriceMaterialsCash";
    $c++;

    $feilds[$c]['title']="مجموع هزینه های تولید";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalProductionCosts";
    $c++;

    $feilds[$c]['title']="قیمت نهایی محصول با هزینه تولید";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fppPCosts";
    $c++;

    $feilds[$c]['title']="قیمت نهایی محصول با هزینه تولید - نقدی";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fppPCostsCash";
    $c++;

    $feilds[$c]['title']="خطاها";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showErrorOfGood";
    $feilds[$c]['disabled']="yes";
    $c++;

    $feilds[$c]['title']="اجزا محصول";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['icon']="fa-puzzle-piece";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showFinePriceOfGood";

    $pagerType = 1;
    $listTitle = " تعداد محصولات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی محصولات : "." $res[1] "." <br><br> <span style='float: right;'> تاریخ آخرین بروزرسانی BOM : </span>&nbsp;"." $res[2]";
    $tableID = "goodPriceReportManageBody-table";
    $jsf = "showMPageGoodPriceReportManageList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showBMGoodPriceReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('goodPriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $Report->getBMGoodPriceReportList($page);
    if($page == 1){
        $_SESSION['calcBMGoodPriceReport'] = $Report->getBMGoodPriceReportListCountRows();
    }
    $totalRows = $_SESSION['calcBMGoodPriceReport'];
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']="5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']="18%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $feilds[$c]['color']='yes';
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="finalPriceMaterials";
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد - نقدی";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="finalPriceMaterialsCash";
    $c++;

    $feilds[$c]['title']="مجموع هزینه های تولید";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalProductionCosts";
    $c++;

    $feilds[$c]['title']="قیمت نهایی محصول با هزینه تولید";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fppPCosts";
    $c++;

    $feilds[$c]['title']="قیمت نهایی محصول با هزینه تولید - نقدی";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fppPCostsCash";
    $c++;

    $feilds[$c]['title']="خطاها";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showErrorOfGood";
    $feilds[$c]['disabled']="yes";
    $c++;

    $feilds[$c]['title']="اجزا محصول";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['icon']="fa-puzzle-piece";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showFinePriceOfGood";

    $pagerType = 1;
    $listTitle = " تعداد محصولات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی محصولات : "." $res[1] "." <br><br> <span style='float: right;'> تاریخ آخرین بروزرسانی BOM : </span>&nbsp;"." $res[2]";
    $tableID = "goodPriceReportManageBody-table";
    $jsf = "showBMPageGoodPriceReportManageList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showBMPageGoodPriceReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('goodPriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $Report->getBMPageGoodPriceReportList($page);
    if($page > 1){
        $_SESSION['calcBMPGoodPriceReport'] = $Report->getBMGoodPriceReportListCountRows();
    }
    $totalRows = $_SESSION['calcBMPGoodPriceReport'];
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']="5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']="18%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $feilds[$c]['color']='yes';
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="finalPriceMaterials";
    $c++;

    $feilds[$c]['title']="قیمت نهایی مواد - نقدی";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="finalPriceMaterialsCash";
    $c++;

    $feilds[$c]['title']="مجموع هزینه های تولید";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalProductionCosts";
    $c++;

    $feilds[$c]['title']="قیمت نهایی محصول با هزینه تولید";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fppPCosts";
    $c++;

    $feilds[$c]['title']="قیمت نهایی محصول با هزینه تولید - نقدی";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fppPCostsCash";
    $c++;

    $feilds[$c]['title']="خطاها";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showErrorOfGood";
    $feilds[$c]['disabled']="yes";
    $c++;

    $feilds[$c]['title']="اجزا محصول";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['icon']="fa-puzzle-piece";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showFinePriceOfGood";

    $pagerType = 1;
    $listTitle = " تعداد محصولات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی محصولات : "." $res[1] "." <br><br> <span style='float: right;'> تاریخ آخرین بروزرسانی BOM : </span>&nbsp;"." $res[2]";
    $tableID = "goodPriceReportManageBody-table";
    $jsf = "showBMPageGoodPriceReportManageList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function updateGoodPrice(){
    $acm = new acm();
    if(!$acm->hasAccess('goodPriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $res = $Report->updateGoodPrice();
    if($res == true){
        $res = "بروزرسانی قیمت ها با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showErrorOfGood(){
    $acm = new acm();
    if(!$acm->hasAccess('goodPriceReportManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($gid) = $ut->varCleanInput(array('gid'));
    if(!intval($gid)){
        $res = "شناسه محصول بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $report = new Report();
    $res = $report->goodErrorInfoHTM($gid);
    $out = "true";
    response($res,$out);
    exit;
}

function showFinePriceOfGood(){
    $acm = new acm();
    if(!$acm->hasAccess('goodPriceReportManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($gid) = $ut->varCleanInput(array('gid'));
    if(!intval($gid)){
        $res = "شناسه محصول بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $report = new Report();
    $res = $report->goodFinePriceHTM($gid);
    $out = "true";
    response($res,$out);
    exit;
}

function getExcelGoodFinePrice(){
    $acm = new acm();
    if(!$acm->hasAccess('goodPriceReportManage') || !$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $report = new Report();
    list($gid) = $ut->varCleanInput(array('gid'));
    $res = $report->getExcelGoodFinePrice($gid);
    $hdarray = array('نام قطعه','کد قطعه','واحد','مقدار','قیمت نهایی مواد','قیمت نهایی مواد - نقدی','مجموع هزینه های تولید','قیمت نهایی محصول با هزینه تولید','مقیمت نهایی محصول با هزینه تولید - نقدی');
    $fieldNames = array('pName','pCode','pUnit','amount','pFRM','pFRMC','TotalCosts','pFRMTotal','pFRMCTotal');
    $name = "GoodFinePriceList".date("y-m-d");
    $additionalFields = array();
    $footerFields = $res[1];
    $url = $ut->createExcel($hdarray,$res[0],$fieldNames,$name,$additionalFields,$footerFields);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++ گزارش قیمت کالای در جریان ساخت +++++++++++++++++++

function showGoodProccessPriceReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('goodProccessPriceReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$Code) = $ut->varCleanInput(array('page','Code'));
    $res = $Report->getGoodProccessPriceReportList($Code,$page);
    if($page == 1){
        $_SESSION['calcGoodProccessPriceReport'] = $Report->getGoodProccessPriceReportListCountRows($Code);
    }
    $totalRows = $_SESSION['calcGoodProccessPriceReport'];
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="کد مهندسی";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="mCode";
    $c++;

    $feilds[$c]['title']="کد همکاران";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="hCode";
    $c++;

    $feilds[$c]['title']="نام قطعه";
    $feilds[$c]['width']="47%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pName";
    $c++;

    $feilds[$c]['title']="روش محاسبات";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="FixHow_supply";
    $c++;

    $feilds[$c]['title']="محاسبه قیمت در جریان";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['icon']="fa-dollar-sign";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="goodProccessPriceCalc";

    $pagerType = 1;
    $listTitle = " تعداد قطعات : ".$totalRows." ";
    $tableID = "goodProccessPriceReportManageBody-table";
    $jsf = "showGoodProccessPriceReportManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getRawMaterialCode(){
    $uti = new Utility();
    list($pid) = $uti->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه قطعه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $report = new Report();
    $res = $report->getRawMaterialCode($pid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doGoodProccessPriceCalc(){
    $acm = new acm();
    if(!$acm->hasAccess('goodProccessPriceReportManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $report = new Report();
    list($avg,$pid) = $uti->varCleanInput(array('avg','pid'));
    $avg = str_replace(',','',$avg);
    $res = $report->goodProccessPriceCalc($avg,$pid);
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ قیمت فروش محصولات +++++++++++++++++++++++

/*function salesPriceGoodSalePrice(){
    $acm = new acm();
    if(!$acm->hasAccess('salesPriceGoodsManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $res = $Report->salesPriceGoodSalePrice();
    if($res == true){
        $res = "بروزرسانی قیمت ها با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}*/

function salesPriceGoodsManage(){
    $acm = new acm();
    if(!$acm->hasAccess('salesPriceGoodsManage')){
        die("access denied");
        exit;
    }
    $report = new Report();
    $htm = $report->getSalesPriceGoodsHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showSalesPriceGoodsManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('salesPriceGoodsManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$gName,$gCode) = $ut->varCleanInput(array('page','gName','gCode'));
    $res = $Report->getSalesPriceGoodsList($gName,$gCode,$page);
    if($page == 1){
        $_SESSION['calcSalesPriceGood'] = $Report->getSalesPriceGoodsListCountRows($gName,$gCode);
    }
    $totalRows = $_SESSION['calcSalesPriceGood'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']="19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="قیمت لیست فروش";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="salesListPrice";
    $c++;

    $feilds[$c]['title']="قیمت با تخفیف خرید مدت دار";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Discount1";
    $c++;

    $feilds[$c]['title']="قیمت با تخفیف نمایندگی";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Discount2";
    $c++;

    $feilds[$c]['title']="قیمت با تخفیف پرداخت نقدی";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Discount3";
    $c++;

    $feilds[$c]['title']="قیمت با تخفیف خرید لوله و اتصالات با هم";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Discount4";
    $c++;

    $feilds[$c]['title']="قیمت با تخفیف پایان دوره";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Discount5";
/*    if($acm->hasAccess('engineeringAccess') || $acm->hasAccess('procurementAccess')) {
        $feilds[$c]['title']="#";
        $feilds[$c]['width']="4%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="checkBox";
        $c++;

        $feilds[$c]['title']="کد محصول";
        $feilds[$c]['width']="7%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="gCode";
        $c++;

        $feilds[$c]['title']="نام محصول";
        $feilds[$c]['width']="19%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="gName";
        $c++;

        $feilds[$c]['title']="قیمت لیست فروش";
        $feilds[$c]['width']="14%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="salesListPrice";
        $c++;

        $feilds[$c]['title']="قیمت با تخفیف خرید مدت دار";
        $feilds[$c]['width']="12%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="Discount1";
        $c++;

        $feilds[$c]['title']="قیمت با تخفیف نمایندگی";
        $feilds[$c]['width']="12%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="Discount2";
        $c++;

        $feilds[$c]['title']="قیمت با تخفیف پرداخت نقدی";
        $feilds[$c]['width']="12%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="Discount3";
        $c++;

        $feilds[$c]['title']="قیمت با تخفیف پایان دوره";
        $feilds[$c]['width']="12%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="Discount4";
        $c++;

        $feilds[$c]['title'] = "ویرایش قیمت";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "editOneSalesPriceGoodsList";
        $feilds[$c]['icon'] = "fa-edit";
    }else{
        $feilds[$c]['title']="#";
        $feilds[$c]['width']="4%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="checkBox";
        $c++;

        $feilds[$c]['title']="کد محصول";
        $feilds[$c]['width']="7%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="gCode";
        $c++;

        $feilds[$c]['title']="نام محصول";
        $feilds[$c]['width']="19%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="gName";
        $c++;

        $feilds[$c]['title']="قیمت لیست فروش";
        $feilds[$c]['width']="14%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="salesListPrice";
        $c++;

        $feilds[$c]['title']="قیمت با تخفیف خرید مدت دار";
        $feilds[$c]['width']="12%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="Discount1";
        $c++;

        $feilds[$c]['title']="قیمت با تخفیف نمایندگی";
        $feilds[$c]['width']="12%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="Discount2";
        $c++;

        $feilds[$c]['title']="قیمت با تخفیف پرداخت نقدی";
        $feilds[$c]['width']="12%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="Discount3";
        $c++;

        $feilds[$c]['title']="قیمت با تخفیف پایان دوره";
        $feilds[$c]['width']="12%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="Discount4";
        $c++;

        $feilds[$c]['title'] = "اجزا محصول";
        $feilds[$c]['width'] = "8%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "btn";
        $feilds[$c]['param'] = "RowID";
        $feilds[$c]['onclick'] = "showGoodPieces";
        $feilds[$c]['icon'] = "fa-tv";
    }*/

    $pagerType = 1;
    $listTitle = " تعداد محصولات : ".$totalRows." عدد <br><br> تاریخ آخرین بروزرسانی : "." $res[1] "." <br><br>";

    $tableID = "salesPriceGoodsManageBody-table";
    $jsf = "showSalesPriceGoodsManageList";
    $htm = $list->creat($listTitle,$res[0],$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function updateSalePriceList(){
    $acm = new acm();
    if(!$acm->hasAccess('salesPriceGoodsManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $res = $Report->updateSalePriceList();
    if($res == true){
        $res = "بروزرسانی قیمت ها با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showGoodsCompareBomList(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood') && !$acm->hasAccess('salesPriceGoodsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($CBG) = $ut->varCleanInput(array('CBG'));
    $report = new Report();
    $res = $report->showGoodCompareBom($CBG);
    if($res !== false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "دو محصول باید انتخاب شود !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getIncreaseChrome(){
    $report = new Report();
    $res = $report->getIncreaseChrome();
    $out = "true";
    response($res,$out);
    exit;
}

function getGoodCalcInfo(){
    $uti = new Utility();
    list($gid) = $uti->varCleanInput(array('gid'));
    if(!intval($gid)){
        $res = "شناسه محصول بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $report = new Report();
    $res = $report->getGoodCalcInfo($gid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doCreateNewSalesPriceGoodsList(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood') && !$acm->hasAccess('salesPriceGoodsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $report = new Report();
    list($type,$ChromeCOCHRI,$ChromeCOGOCHRI,$ChromeGOCHRI,$ChromeGOMCHRI,$ChromeCOCHMA,$ChromeCOGOCHMA,$ChromeGOCHMA,$ChromeGOMCHMA,$ChromeDP,$ChromeDTP,$ChromeDSP,$ChromeDKAP,$ChromeTP,$ChromeRSP,$ChromeRMP,$ChromeRPBP,$ChromeRMARP,$ChromeRMAAP,$ChromeRMDARP,$ChromeZP,$ChromeZRP,$ChromeZALRP,$ChromeZAUP,$ChromeZATP,$ChromeZARP,$ChromeZASP,$ChromeZAF30P,$ChromeZAF25P,$ChromeZAFP,$ChromeZANP,$ChromeZG360P,$ChromeZBAAP,$ChromeZTP,$ChromeZDARP,$ChromeZDP,$ChromeZDATP,$ChromeZDiARP,$ChromeZSHP,$ChromeZMANP,$ChromeMTP,$ChromeMDT1P,$ChromeMDT2P,$ChromeMDT3P,$ChromeMRP,$BChromeCOCHRI,$BChromeCOGOCHRI,$BChromeGOCHRI,$BChromeGOMCHRI,$BChromeCOCHMA,$BChromeCOGOCHMA,$BChromeGOCHMA,$BChromeGOMCHMA,$BChromeTP,$BChromeDP,$BChromeDKP,$BChromeRSP,$BChromeRMP,$BChromeRMAAP,$BChromeRZASP,$BChromeZP,$BChromeZAPP,$BChromeZDP,$BChromeZDiP,$BChromeZDAPP,$BChromeZDASP,$BChromeZAASP,$BTNBLGHGH,$TNBLGHGH,$ETNBLGHGH,$PXTNBLGHGH,$PETNBLGHGH,$AHTNBLGHGH,$BHTNBLGHGH) = $uti->varCleanInput(array('type','ChromeCOCHRI','ChromeCOGOCHRI','ChromeGOCHRI','ChromeGOMCHRI','ChromeCOCHMA','ChromeCOGOCHMA','ChromeGOCHMA','ChromeGOMCHMA','ChromeDP','ChromeDTP','ChromeDSP','ChromeDKAP','ChromeTP','ChromeRSP','ChromeRMP','ChromeRPBP','ChromeRMARP','ChromeRMAAP','ChromeRMDARP','ChromeZP','ChromeZRP','ChromeZALRP','ChromeZAUP','ChromeZATP','ChromeZARP','ChromeZASP','ChromeZAF30P','ChromeZAF25P','ChromeZAFP','ChromeZANP','ChromeZG360P','ChromeZBAAP','ChromeZTP','ChromeZDARP','ChromeZDP','ChromeZDATP','ChromeZDiARP','ChromeZSHP','ChromeZMANP','ChromeMTP','ChromeMDT1P','ChromeMDT2P','ChromeMDT3P','ChromeMRP','BChromeCOCHRI','BChromeCOGOCHRI','BChromeGOCHRI','BChromeGOMCHRI','BChromeCOCHMA','BChromeCOGOCHMA','BChromeGOCHMA','BChromeGOMCHMA','BChromeTP','BChromeDP','BChromeDKP','BChromeRSP','BChromeRMP','BChromeRMAAP','BChromeRZASP','BChromeZP','BChromeZAPP','BChromeZDP','BChromeZDiP','BChromeZDAPP','BChromeZDASP','BChromeZAASP','BTNBLGHGH','TNBLGHGH','ETNBLGHGH','PXTNBLGHGH','PETNBLGHGH','AHTNBLGHGH','BHTNBLGHGH'));
    $ChromeCOCHRI = str_replace(',','',$ChromeCOCHRI);
    $ChromeCOGOCHRI = str_replace(',','',$ChromeCOGOCHRI);
    $ChromeGOCHRI = str_replace(',','',$ChromeGOCHRI);
    $ChromeGOMCHRI = str_replace(',','',$ChromeGOMCHRI);
    $ChromeCOCHMA = str_replace(',','',$ChromeCOCHMA);
    $ChromeCOGOCHMA = str_replace(',','',$ChromeCOGOCHMA);
    $ChromeGOCHMA = str_replace(',','',$ChromeGOCHMA);
    $ChromeGOMCHMA = str_replace(',','',$ChromeGOMCHMA);

    $BChromeCOCHRI = str_replace(',','',$BChromeCOCHRI);
    $BChromeCOGOCHRI = str_replace(',','',$BChromeCOGOCHRI);
    $BChromeGOCHRI = str_replace(',','',$BChromeGOCHRI);
    $BChromeGOMCHRI = str_replace(',','',$BChromeGOMCHRI);
    $BChromeCOCHMA = str_replace(',','',$BChromeCOCHMA);
    $BChromeCOGOCHMA = str_replace(',','',$BChromeCOGOCHMA);
    $BChromeGOCHMA = str_replace(',','',$BChromeGOCHMA);
    $BChromeGOMCHMA = str_replace(',','',$BChromeGOMCHMA);
    $res = $report->createNewSalesPriceGoods($type,$ChromeCOCHRI,$ChromeCOGOCHRI,$ChromeGOCHRI,$ChromeGOMCHRI,$ChromeCOCHMA,$ChromeCOGOCHMA,$ChromeGOCHMA,$ChromeGOMCHMA,$ChromeDP,$ChromeDTP,$ChromeDSP,$ChromeDKAP,$ChromeTP,$ChromeRSP,$ChromeRMP,$ChromeRPBP,$ChromeRMARP,$ChromeRMAAP,$ChromeRMDARP,$ChromeZP,$ChromeZRP,$ChromeZALRP,$ChromeZAUP,$ChromeZATP,$ChromeZARP,$ChromeZASP,$ChromeZAF30P,$ChromeZAF25P,$ChromeZAFP,$ChromeZANP,$ChromeZG360P,$ChromeZBAAP,$ChromeZTP,$ChromeZDARP,$ChromeZDP,$ChromeZDATP,$ChromeZDiARP,$ChromeZSHP,$ChromeZMANP,$ChromeMTP,$ChromeMDT1P,$ChromeMDT2P,$ChromeMDT3P,$ChromeMRP,$BChromeCOCHRI,$BChromeCOGOCHRI,$BChromeGOCHRI,$BChromeGOMCHRI,$BChromeCOCHMA,$BChromeCOGOCHMA,$BChromeGOCHMA,$BChromeGOMCHMA,$BChromeTP,$BChromeDP,$BChromeDKP,$BChromeRSP,$BChromeRMP,$BChromeRMAAP,$BChromeRZASP,$BChromeZP,$BChromeZAPP,$BChromeZDP,$BChromeZDiP,$BChromeZDAPP,$BChromeZDASP,$BChromeZAASP,$BTNBLGHGH,$TNBLGHGH,$ETNBLGHGH,$PXTNBLGHGH,$PETNBLGHGH,$AHTNBLGHGH,$BHTNBLGHGH);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doCreateFinalSalesPriceGoodsList(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood') && !$acm->hasAccess('salesPriceGoodsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $report = new Report();
    list($pDate,$Cat) = $uti->varCleanInput(array('pDate','Cat'));
    $res = $report->createFinalSalesPriceGoods($pDate,$Cat);
    if($res){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doCreateOneNewSalesPriceGoodsModal(){
    $acm = new acm();
    if(!$acm->hasAccess('manageGood') && !$acm->hasAccess('salesPriceGoodsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $report = new Report();
    list($gid,$DTNBLGHGH,$MSTNBLGHGH,$calcType,$gName,$coefficient,$divisibility,$ggroup) = $uti->varCleanInput(array('gid','DTNBLGHGH','MSTNBLGHGH','calcType','gName','coefficient','divisibility','ggroup'));
    //$MSTNBLGHGH = str_replace(',','',$MSTNBLGHGH);
    $res = $report->createOneNewSalesPriceGoods($gid,$DTNBLGHGH,$MSTNBLGHGH,$calcType,$gName,$coefficient,$divisibility,$ggroup);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getPerDiscountInfo(){
    $report = new Report();
    $ut = new Utility();
    list($brand,$group) = $ut->varCleanInput(array('brand','group'));
    $res = $report->perDiscountInfo($brand,$group);
    $out = "true";
    response($res,$out);
    exit;
}

function doEditCreatePerDiscount(){
    $acm = new acm();
    if(!$acm->hasAccess('perDiscountAccess')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $report = new Report();
    list($Wid,$brand,$group,$Dis1,$Dis2,$Dis3,$Dis4,$Dis5,$Priority1,$Priority2,$Priority3,$Priority4,$Priority5) = $uti->varCleanInput(array('Wid','brand','group','Dis1','Dis2','Dis3','Dis4','Dis5','Priority1','Priority2','Priority3','Priority4','Priority5'));

    $res = $report->editCreatePerDiscount($Wid,$brand,$group,$Dis1,$Dis2,$Dis3,$Dis4,$Dis5,$Priority1,$Priority2,$Priority3,$Priority4,$Priority5);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showCheckPricesManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('salesPriceGoodsManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($SPercent,$EPercent,$checkList) = $ut->varCleanInput(array('SPercent','EPercent','checkList'));
    $res = $Report->getCheckPricesList($SPercent,$EPercent,$checkList);
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="ردیف";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="iterator";
    $c++;

    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']="40%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="قیمت لیست فروش";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="salesListPrice";
    $c++;

    $feilds[$c]['title']="قیمت جدید لیست فروش";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="newSalesListPrice";
    $c++;

    $feilds[$c]['title']="درصد تغییر";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="percent";
/*    $c++;

    $feilds[$c]['title'] = "ویرایش قیمت";
    $feilds[$c]['width'] = "8%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "btn";
    $feilds[$c]['param'] = "RowID";
    $feilds[$c]['onclick'] = "createOneNewSalesPriceGoodsList";
    $feilds[$c]['icon'] = "fa-sync-alt";*/

    $pagerType = 0;
    $listTitle = " تعداد گزارشات : ".count($res)." عدد ";
    $tableID = "checkPricesManageBody-table";
    $jsf = "showCheckPricesManageList";
    $scroll = 'style="height: 400px;"';
    $htm = $list->creat($listTitle,$res,$feilds,'',$pagerType,'',$tableID,$jsf,'',array(),$scroll);
    $out = "true";
    response($htm,$out);
    exit;
}

function doPrintSaleListPrice(){
    $acm = new acm();
    if(!$acm->hasAccess('salesPriceGoodsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $report = new Report();
    list($cid) = $ut->varCleanInput(array('cid'));
    $htm = $report->getPrintSaleListPriceHtm($cid);
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function doUploadGoodSalePriceFile(){
    $acm = new acm();
    if(!$acm->hasAccess('createNewSalesPriceGoodsList')){
        die("access denied");
        exit;
    }
    $report = new Report();
    $gsplist = $_FILES['excGSPL'];
    $res = $report->uploadGSPListFile($gsplist);
    if($res != false){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "درخواست با خطا مواجه شد، لطفا دوباره سعی کنید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function uploadAgainSalesPriceGoods(){
    $acm = new acm();
    if(!$acm->hasAccess('createNewSalesPriceGoodsList')){
        die("access denied");
        exit;
    }
    $report = new Report();
    $res = $report->uploadAgainSalesPriceGoodsList();
    if($res != false){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getGoodSalePrice(){
    $ut = new Utility();
    $report = new Report();
    list($gid) = $ut->varCleanInput(array('gid'));
    $res = $report->getGoodSalePrice($gid);
    $out = "true";
    response($res,$out);
    exit;
}

function doEditOneSalesPriceGoodsList(){
    $acm = new acm();
    if(!$acm->hasAccess('createNewSalesPriceGoodsList')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $report = new Report();
    list($gid,$amount) = $uti->varCleanInput(array('gid','amount'));
    $amount = str_replace(',','',$amount);
    $res = $report->editOneSalesPriceGoods($gid,$amount);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ مقایسه قیمت تمام شده/ فروش +++++++++++++++++++++++

function comparePricesManage(){
    $acm = new acm();
    if(!$acm->hasAccess('comparePricesManage')){
        die("access denied");
        exit;
    }
    $report = new Report();
    $htm = $report->getComparePricesHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showComparePricesManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('comparePricesManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$gName,$gCode,$pType,$sType) = $ut->varCleanInput(array('page','gName','gCode','pType','sType'));
    $res = $Report->getComparePricesList($gName,$gCode,$pType,$sType,$page);
    if($page == 1){
        $_SESSION['calcComparePrices'] = $Report->getComparePricesListCountRows($gName,$gCode);
    }
    $totalRows = $_SESSION['calcComparePrices'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']="32%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['id']="id='pType-td'";
    $feilds[$c]['title']="قیمت نهایی مواد";
    $feilds[$c]['width']="22%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pType";
    $c++;

    $feilds[$c]['id']="id='sType-td'";
    $feilds[$c]['title']="قیمت لیست فروش";
    $feilds[$c]['width']="18%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="salesListPrice";
    $c++;

    $feilds[$c]['title']="درصد تفاوت";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['dir']="direction: ltr;";
    $feilds[$c]['f']="PerDifference";
    $c++;

    $feilds[$c]['title']="خطاها";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showErrorOfGoodC";
    $feilds[$c]['disabled']="yes";
    $c++;

    $feilds[$c]['title']="اجزا محصول";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['icon']="fa-puzzle-piece";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showFinePriceOfCPRGood";

    $pagerType = 1;
    $listTitle = " تعداد گزارشات : ".$totalRows." عدد ";
    $tableID = "comparePricesManageBody-table";
    $jsf = "showComparePricesManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showFinePriceOfCPRGood(){
    $acm = new acm();
    if(!$acm->hasAccess('comparePricesManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($gid) = $ut->varCleanInput(array('gid'));
    if(!intval($gid)){
        $res = "شناسه محصول بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $report = new Report();
    $res = $report->goodFinePriceHTM($gid);
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++ گزارش تغییرات BOM +++++++++++++++++++

function bomChangeReportManage(){
    $acm = new acm();
    if(!$acm->hasAccess('bomChangeReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $htm = $Report->getBomChangeReportManageHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showBomChangeReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('bomChangeReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$pName,$gName,$pCode,$gCode,$sDate,$eDate) = $ut->varCleanInput(array('page','pName','gName','pCode','gCode','sDate','eDate'));
    $res = $Report->getBomChangeReportList($pName,$gName,$pCode,$gCode,$sDate,$eDate,$page);
    if($page == 1){
        $_SESSION['calcBomChangeReport'] = $Report->getBomChangeReportListCountRows($pName,$gName,$pCode,$gCode,$sDate,$eDate);
    }
    $totalRows = $_SESSION['calcBomChangeReport'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']="28%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="کد قطعه";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pCode";
    $c++;

    $feilds[$c]['title']="نام قطعه";
    $feilds[$c]['width']="29%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pName";
    $c++;

    $feilds[$c]['title']="مقدار قبلی";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pValue";
    $c++;

    $feilds[$c]['title']="مقدار فعلی";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="cValue";
    $c++;

    $feilds[$c]['title']="تاریخ تغییر";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="changeDate";

    $pagerType = 1;
    $listTitle = " تعداد گزارشات : ".$totalRows." عدد ";
    $tableID = "bomChangeReportBody-table";
    $jsf = "showBomChangeReportManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

//++++++++++++++++++++ گزارش تغییرات بار برنج +++++++++++++++++++

function brassWeightReportManage(){
    $acm = new acm();
    if(!$acm->hasAccess('brassWeightReportManage')){
        die("access denied");
        exit;
    }
    $Report= new Report();
    $htm = $Report->getBrassWeightReportManageHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showBWReportManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('brassWeightReportManage')){
        die("access denied");
        exit;
    }
    $Report = new Report();
    $list = new Listview();
    $ut = new Utility();
    list($page,$sDate,$eDate) = $ut->varCleanInput(array('page','sDate','eDate'));
    $res = $Report->getBWReportManageList($sDate,$eDate,$page);
    if($page == 1){
        $_SESSION['calcBWReport'] = $Report->getBWReportManageListCountRows($sDate,$eDate);
    }
    $totalRows = $_SESSION['calcBWReport'];
    $c = 0;
    $feilds = array();

    $feilds[$c]['title']="قیمت براده برنج (ریخته گری-ماشینکاری)";
    $feilds[$c]['width']="16%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="brassSwarfPrice";
    $c++;

    $feilds[$c]['title']="اجرت شمش (قطر بالای 14)";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="BullionPriceUp14";
    $c++;

    $feilds[$c]['title']="اجرت شمش (قطر زیر 14)";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="BullionPriceUnder14";
    $c++;

    $feilds[$c]['title']="اجرت شمش کلکتور";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="BullionPriceColector";
    $c++;

    $feilds[$c]['title']="اجرت ریخته گری";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="CastingPrice";
    $c++;

    $feilds[$c]['title']="قیمت خاک پرداخت";
    $feilds[$c]['width']="12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="PolishingSoilPrice";
    $c++;

    $feilds[$c]['title']="درصد سوخت بار";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="PercentFuelWeight";
    $c++;

    $feilds[$c]['title']="تاریخ تغییر";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="changeDate";

    $pagerType = 1;
    $listTitle = " تعداد ثبت شده : ".$totalRows." عدد ";
    $tableID = "BWReportManageBody-table";
    $jsf = "showBWReportManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

//++++++++++++++++++++++ اظهارنظر و درخواست پرداخت وجه +++++++++++++++++++++++
function getFundListExcel(){
    
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $comment = new Comment();
    list($cid) = $ut->varCleanInput(array('cid'));
   
    $res = $comment->getFundListExcel($cid);
  
    //$ut->fileRecorder($res);
    $hdarray = array('ردیف','تاریخ ','تنخواه گردان','شرح','شماره درخواست','محل استفاده ','مبلغ');
    $fieldNames = array('counter','createDate','uid','description','reqNumber','placeUse','fundAmount');
    $name = "fundList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function get_layer_two_options(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $res=$Comment->getLayer2($_POST['search_key']);
    if(count($res>0)){
        $out="true";
        response($res,$out);
    }
}

function get_layer_three_options(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $res=$Comment->getLayer3($_POST['search_key']);
    if(count($res>0)){
        $out="true";
        response($res,$out);
    }
}


function commentManagement(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $htm = $Comment->getCommentManagementHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}
function get_vat_pay_comment(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $ut = new Utility();
    $res=$Comment->get_vat_pay_comment($_POST['cid']);

    error_log('res------------------------------------');
    error_log(res);
    $out = "true";
    response($res,$out);
    exit;

}
function showPayCommentManageList(){
    
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($page,$csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount,$layer_one,$layer_two,$layer_three) = $ut->varCleanInput(array('page','csDate','ceDate','cUnit','coUnit','caName','cToward','Uncode','amount','layer_one','layer_two','layer_three'));
    $amount = str_replace(',','',$amount);
    $res = $Comment->getPayCommentManageList($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount,$page,$layer_one,$layer_two,$layer_three);
    if($page == 1){
        $_SESSION['calcCommManage'] = $Comment->getPayCommentManageListCountRows($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount,$layer_one,$layer_two,$layer_three);
    }
    $ut->fileRecorder('count:'.$_SESSION['calcCommManage']);
    $totalRows = $_SESSION['calcCommManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= "4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="روش پرداخت";
    $feilds[$c]['width']= "9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="typeComment";
    $c++;

    $feilds[$c]['title']="در وجه";
    $feilds[$c]['width']= "9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="accName";
    $c++;

    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="cDate";
    $c++;

    $feilds[$c]['title']="واحد درخواست کننده";
    $feilds[$c]['width']= "9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Unit";
    $c++;

    $feilds[$c]['title']="واحد مصرف کننده";
    $feilds[$c]['width']= "9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="consumerUnit";
    $c++;

    $feilds[$c]['title']="بابت";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Toward";
    $c++;

    $feilds[$c]['title']="به مبلغ";
    $feilds[$c]['width']= "11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Amount";
    $c++;

    $feilds[$c]['title']=" پروژه مرتبط";
    $feilds[$c]['width']= "11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="related_project";
    $c++;

    $feilds[$c]['title']="سایر اطلاعات";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowOtherInfoComment";
    $feilds[$c]['icon']="fa-tv";
    $c++;

    $feilds[$c]['title']="فایل پیوست";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowAttachmentFileComment";
    $feilds[$c]['icon']="fa-file";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowWorkflowComment";
    $feilds[$c]['icon']="fa-sitemap";
    $c++;

    $feilds[$c]['title']="پرینت";
    $feilds[$c]['width']= "4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="printPayComment";
    $feilds[$c]['icon']="fa-print";

    $pagerType = 1;
    $listTitle = " تعداد اظهار نظرها : ".$totalRows." عدد ";
    $tableID = "commentManageBody-table";
    $jsf = "showPayCommentManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showPayCommentSendManageList(){  // اظهارنظرهای ارسال شده
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($page,$csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode) = $ut->varCleanInput(array('page','csDate','ceDate','cUnit','coUnit','caName','cToward','Uncode'));
    $res = $Comment->getPayCommentSendManageList($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$page);
    if($page == 1){
        $_SESSION['calcSCommManage'] = $Comment->getPayCommentSendManageListCountRows($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode);
    }
    $totalRows = $_SESSION['calcSCommManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="در وجه";
    $feilds[$c]['width']= "14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="accName";
    $c++;

    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="cDate";
    $c++;

    $feilds[$c]['title']="واحد درخواست کننده";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Unit";
    $c++;

    $feilds[$c]['title']="واحد مصرف کننده";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="consumerUnit";
    $c++;

    $feilds[$c]['title']="بابت";
    $feilds[$c]['width']= "14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Toward";
    $c++;

    $feilds[$c]['title']="به مبلغ";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Amount";
    $c++;

    $feilds[$c]['title']="لیست تنخواه";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showAttachedFundToComment1";
    $feilds[$c]['icon']="fa-list";
    $c++;

    $feilds[$c]['title']="سایر اطلاعات";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowOtherInfoComment";
    $feilds[$c]['icon']="fa-tv";
    $c++;

    $feilds[$c]['title']="فایل پیوست";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowAttachmentFileComment";
    $feilds[$c]['icon']="fa-file";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowWorkflowComment";
    $feilds[$c]['icon']="fa-sitemap";
    $c++;

    $feilds[$c]['title']="پرینت";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="printPayComment";
    $feilds[$c]['icon']="fa-print";

    $pagerType = 1;
    $listTitle = " تعداد اظهار نظرها : ".$totalRows." عدد ";
    $tableID = "commentSendManageBody-table";
    $jsf = "showPayCommentSendManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showPayCommentStagnantManageList(){  // اظهارنظرهای راکد در کارتابل
    $acm = new acm();
    if(!$acm->hasAccess('stagnantCommentManagement')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($page,$csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$cardboardID) = $ut->varCleanInput(array('page','csDate','ceDate','cUnit','coUnit','caName','cToward','Uncode','cardboardID'));
    $res = $Comment->getPayCommentStagnantManageList($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$cardboardID,$page);
    if($page == 1){
        $_SESSION['calcStagnantCommManage'] = $Comment->getPayCommentStagnantManageListCountRows($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$cardboardID);
    }
    $totalRows = $_SESSION['calcStagnantCommManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="در وجه";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="accName";
    $c++;

    $feilds[$c]['title']="در کارتابل";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="receiver";
    $c++;

    $feilds[$c]['title']="تاریخ ورود به کارتابل";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="cDate";
    $c++;

    $feilds[$c]['title']="واحد درخواست کننده";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Unit";
    $c++;

    $feilds[$c]['title']="واحد مصرف کننده";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="consumerUnit";
    $c++;

    $feilds[$c]['title']="بابت";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Toward";
    $c++;

    $feilds[$c]['title']="به مبلغ";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Amount";
    $c++;

    $feilds[$c]['title']="لیست تنخواه";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showAttachedFundToComment3";
    $feilds[$c]['icon']="fa-list";
    $c++;

    $feilds[$c]['title']="سایر اطلاعات";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowOtherInfoComment";
    $feilds[$c]['icon']="fa-tv";
    $c++;

    $feilds[$c]['title']="فایل پیوست";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowAttachmentFileComment";
    $feilds[$c]['icon']="fa-file";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowWorkflowComment";
    $feilds[$c]['icon']="fa-sitemap";
    $c++;

    $feilds[$c]['title']="پرینت";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="printPayComment";
    $feilds[$c]['icon']="fa-print";

    $pagerType = 1;
    $listTitle = " تعداد اظهار نظرها : ".$totalRows." عدد ";
    $tableID = "commentStagnantManageBody-table";
    $jsf = "showPayCommentStagnantManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showPayCommentStagnantManageList1(){  // اظهارنظرهای راکد از زمان ایجاد
    $acm = new acm();
    if(!$acm->hasAccess('stagnantCommentManagement')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($page,$csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$cardboardID) = $ut->varCleanInput(array('page','csDate','ceDate','cUnit','coUnit','caName','cToward','Uncode','cardboardID'));
    $res = $Comment->getPayCommentStagnantManageList1($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$cardboardID,$page);
    if($page == 1){
        $_SESSION['calcStagnantCommManage'] = $Comment->getPayCommentStagnantManageListCountRows1($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$cardboardID);
    }
    $totalRows = $_SESSION['calcStagnantCommManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="در وجه";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="accName";
    $c++;

    $feilds[$c]['title']="در کارتابل";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="receiver";
    $c++;

    $feilds[$c]['title']="سررسید نقدی";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="paymentMaturityCash";
    $c++;

    $feilds[$c]['title']="سررسید چک";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="paymentMaturityCheck";
    $c++;

    $feilds[$c]['title']="واحد درخواست کننده";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Unit";
    $c++;

    $feilds[$c]['title']="واحد مصرف کننده";
    $feilds[$c]['width']= "9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="consumerUnit";
    $c++;

    $feilds[$c]['title']="بابت";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Toward";
    $c++;

    $feilds[$c]['title']="به مبلغ";
    $feilds[$c]['width']= "9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Amount";
    $c++;

    $feilds[$c]['title']="لیست تنخواه";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showAttachedFundToComment3";
    $feilds[$c]['icon']="fa-list";
    $c++;

    $feilds[$c]['title']="سایر اطلاعات";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowOtherInfoComment";
    $feilds[$c]['icon']="fa-tv";
    $c++;

    $feilds[$c]['title']="فایل پیوست";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowAttachmentFileComment";
    $feilds[$c]['icon']="fa-file";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowWorkflowComment";
    $feilds[$c]['icon']="fa-sitemap";
    $c++;

    $feilds[$c]['title']="پرینت";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="printPayComment";
    $feilds[$c]['icon']="fa-print";

    $pagerType = 1;
    $listTitle = " تعداد اظهار نظرها : ".$totalRows." عدد ";
    $tableID = "commentStagnantManageBody1-table";
    $jsf = "showPayCommentStagnantManageList1";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getCardboard(){
    $comment = new Comment();
    $res = $comment->getCardboard();
    $out = "true";
    response($res,$out);
    exit;
}

function getCardboard1(){
    $comment = new Comment();
    $res = $comment->getCardboard1();
    $out = "true";
    response($res,$out);
    exit;
}

function getCommentInfo(){
    $uti = new Utility();
    list($cid) = $uti->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->commentInfo($cid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateComment(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Comment = new Comment();
 
    list($cid,$pMethod,$pType,$Unit,$consumerUnit,$Type,$Toward,$totalAmount,$Amount,$AccNum,$CashSection,$MaturityCash,$NonCashSection,$MaturityCheck,$RequestSource,$RequestNumbers,$Desc,$billID,$payID,$layer1,$layer2,$cFund,$cForv,$code,$ContractNum,$CheckNumber,$CheckDate,$DeliveryDate,$CheckCarcass,$CardNumber,$layer3,$goodLoan,$checkOutType,$related_project,$related_vat) = $uti->varCleanInput(array('cid','pMethod','pType','Unit','consumerUnit','Type','Toward','totalAmount','Amount','AccNum','CashSection','MaturityCash','NonCashSection','MaturityCheck','RequestSource','RequestNumbers','Desc','billID','payID','layer1','layer2','cFund','cForv','code','ContractNum','CheckNumber','CheckDate','DeliveryDate','CheckCarcass','CardNumber','layer3','goodLoan','checkOutType','related_project','related_vat'));
    $files = $_FILES['files'];
    $Amount = str_replace(',','',$Amount);
    $totalAmount = str_replace(',','',$totalAmount);
    $CashSection = str_replace(',','',$CashSection);
    $NonCashSection = str_replace(',','',$NonCashSection);
    if(intval($cid) > 0){  //edit
        $res = $Comment->editPayComment($cid,$pMethod,$pType,$Unit,$consumerUnit,$Type,$Toward,$totalAmount,$Amount,$AccNum,$CashSection,$MaturityCash,$NonCashSection,$MaturityCheck,$RequestSource,$RequestNumbers,$Desc,$billID,$payID,$layer1,$layer2,$cFund,$cForv,$code,$ContractNum,$CheckNumber,$CheckDate,$DeliveryDate,$CheckCarcass,$files,$CardNumber,$layer3,$goodLoan,$checkOutType,$related_project,$related_vat);
        if(intval($res) == -1){
            $res = "جمع بخش نقدی و غیر نقدی با مبلغ کل برابر نیست !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif (intval($res) == -2){
            $res = "شما ثبت کننده این اظهارنظر نمی باشید !!!";
            $out = "false";
            response($res,$out);
            exit;
        }elseif (intval($res) == -3){
            $res = "اظهارنظر دارای گردش کار، امکان ویرایش ندارد !!!";
            $out = "false";
            response($res,$out);
            exit;
        }elseif(intval($res) == -4){
            $res = "شما مجاز به ثبت اظهارنظر در این واحد درخواست کننده نمی باشید !!!";
            $out = "false";
            response($res,$out);
            exit;
        }elseif(intval($res) == -5){
            $res = "مبلغ وارد شده از باقیمانده مبلغ قرارداد بیشتر است !!!";
            $out = "false";
            response($res,$out);
            exit;
        }elseif(intval($res) == -6){
            $res = "شماره قرارداد وارد شده موجود نمی باشد !!!";
            $out = "false";
            response($res,$out);
            exit;
        }elseif (intval($res) == -7){
            $res = "فایل ها مشکل دارند !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif (intval($res) == -8){
            $res = "سایز فایل ها مشکل دارند !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif (intval($res) == -9){
            $res = "پسوند فایل ها مشکل دارند !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif (intval($res) == -10){
            $res = "برای این اظهارنظر تنخواه ثبت شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif (intval($res) == -11){
            $res = "اظهارنظر متعلق به واحد شما نمی باشد و شما قادر به ویرایش آن نیستید";
            $out = "false";
            response($res,$out);
            exit;
        }
        elseif (intval($res) == -12){
                $res = "اظهارنظر  دارای گردش  خارج از واحد می باشد و قابل ویرایش نیست ";
                $out = "false";
                response($res,$out);
                exit;
        }elseif($res == true){
            $res = 'درخواست با موفقیت انجام شد.';
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $Comment->createPayComment($pMethod,$pType,$Unit,$consumerUnit,$Type,$Toward,$totalAmount,$Amount,$AccNum,$CashSection,$MaturityCash,$NonCashSection,$MaturityCheck,$RequestSource,$RequestNumbers,$Desc,$billID,$payID,$layer1,$layer2,$cFund,$cForv,$code,$ContractNum,$CheckNumber,$CheckDate,$DeliveryDate,$CheckCarcass,$files,$CardNumber,$layer3,$goodLoan,$checkOutType,$related_project,$related_vat);
        //die();
        if(intval($res) == -1){
            $res = "جمع بخش نقدی و غیر نقدی با مبلغ کل برابر نیست !!!";
            $out = "false";
            response($res,$out);
            exit;
        }elseif(intval($res) == -2){
            $res = "شما مجاز به ثبت اظهارنظر در این واحد درخواست کننده نمی باشید !!!";
            $out = "false";
            response($res,$out);
            exit;
        }/*elseif(intval($res) == -3){
            $res = "زمان ثبت اظهارنظر فرا نرسیده است !!!";
            $out = "false";
            response($res,$out);
            exit;
        }*/elseif(intval($res) == -4){
            $res = "شماره قرارداد وارد شده موجود نمی باشد !!!";
            $out = "false";
            response($res,$out);
            exit;
        }elseif(intval($res) == -5){
            $res = "مبلغ وارد شده از باقیمانده مبلغ قرارداد بیشتر است !!!";
            $out = "false";
            response($res,$out);
            exit;
        }elseif (intval($res) == -6){
            $res = "فایل ها مشکل دارند !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif (intval($res) == -7){
            $res = "سایز فایل ها مشکل دارند !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif (intval($res) == -8){
            $res = "پسوند فایل ها مشکل دارند !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif($res == true){
            $res = 'درخواست با موفقیت انجام شد.';
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !!!";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getAttachedCommentFile(){
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->attachedCommentFileHtm($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachFileToComment(){
    $uti = new Utility();
    $Comment = new Comment();
    list($cid,$info) = $uti->varCleanInput(array('cid','info'));
    $files = $_FILES['files'];
    $res = $Comment->attachFileToComment($cid,$info,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteAttachCommentFile(){
    $uti = new Utility();
    $Comment = new Comment();
    list($fid) = $uti->varCleanInput(array('fid'));
    $res = $Comment->deleteAttachCommentFile($fid);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}
function getUserInfoCartable(){
    $ut=new Utility();
    list($userid) = $ut->varCleanInput(array('userid'));
    $Comment=new Comment();
    $result=$Comment->getUserInfoCartable($userid);
   
    if ($result[0] == true){
        response($result[1],'true');
        return $result[0];
    }else{
        $res = "کارتابل خالی می یاشد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function DeleteAutoSendRecord(){//Delete record from  auto send comment
    $ut=new Utility();
    list($autoSendRowID)=$ut->varCleanInput(array('autoSendRowID'));
    $Comment=new Comment();
    $result = $Comment->DeleteAutoSendRecord($autoSendRowID);
    if ($result){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doAutomaticSendPayComment(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
    }
    
    $ut=new Utility();
    list($absentReceiver,$substituteReceiver,$autoSendStartDate,$autoSendDayes,$autoSendDesc) = $ut->varCleanInput(array('absentReceiver','substituteReceiver','autoSendStartDate','autoSendDayes','autoSendDesc'));
    $Comment = new Comment();
    $autoSendStartDate=$ut->jal_to_greg($autoSendStartDate);
    error_log($autoSendStartDate);
    $res = $Comment->AutoSendPayComment($absentReceiver,$substituteReceiver,$autoSendStartDate,$autoSendDayes,$autoSendDesc);

    if ($res == 'true'){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }
    elseif($res==-1){
    
        $res = "برای کاربر جانشین   ارجاع خودکار  تنظیم شده است  ";
        $out = "false";
        response($res,$out);
        exit;
    }
    elseif($res==-2){
        $res = "رکورد تکراری است ";
        $out = "false";
        response($res,$out);
        exit;
    }
    else{
        $res = "هیچ تغییری اعمال نگردید !!!";
        $out = "false";
        response($res,$out);
        exit;
    }

}
function doSendPayComment(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($cid,$confType,$receiver,$desc,$PriorityLevel,$isPaid) = $ut->varCleanInput(array('cid','confType','receiver','desc','PriorityLevel','isPaid'));
    $res = $Comment->sendPayComment($cid,$confType,$receiver,$desc,$PriorityLevel,$isPaid);
    if(intval($res) == -1) {
        $res = "قبلا این اظهارنظر را تایید نموده اید !!!";
        $out = "false";
        response($res, $out);
        exit;
    }elseif ($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !!!";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doSaveSendPayComment(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($cid,$confType,$receiver,$desc) = $ut->varCleanInput(array('cid','confType','receiver','desc'));
    $res = $Comment->saveSendPayComment($cid,$confType,$receiver,$desc);
    if(intval($res) == -1) {
        $res = "قبلا این اظهارنظر را تایید نموده اید !!!";
        $out = "false";
        response($res, $out);
        exit;
    }elseif (intval($res) == -2){
        $res = "قبلا برای این اظهارنظر توضیحات ذخیره نموده اید !!!";
        $out = "false";
        response($res, $out);
        exit;
    }elseif ($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !!!";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getTempSendCommentInfo(){
    $uti = new Utility();
    list($pwID) = $uti->varCleanInput(array('pwID'));
    if(!intval($pwID)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->commentTempSendInfo($pwID);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditTempSendComment(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($pwID,$confType,$receiver,$desc) = $ut->varCleanInput(array('pwID','confType','receiver','desc'));
    $res = $Comment->editTempSendComment($pwID,$confType,$receiver,$desc);
    if(intval($res) == -1) {
        $res = "شما این پاراف را ثبت ننموده اید !!!";
        $out = "false";
        response($res, $out);
        exit;
    }elseif ($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !!!";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doDeleteTempSendComment(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($pwID) = $ut->varCleanInput(array('pwID'));
    $res = $Comment->deleteTempSendComment($pwID);
    if(intval($res) == -1) {
        $res = "شما این پاراف را ثبت ننموده اید !!!";
        $out = "false";
        response($res, $out);
        exit;
    }elseif ($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !!!";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doSendTempSendComment(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($pwID) = $ut->varCleanInput(array('pwID'));
    $res = $Comment->sendTempSendComment($pwID);
    if(intval($res) == -1) {
        $res = "شما این پاراف را ثبت ننموده اید !!!";
        $out = "false";
        response($res, $out);
        exit;
    }elseif ($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !!!";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function ShowAttachmentFileComment(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->attachmentFileCommentHtm($pid,1);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowWorkflowComment(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->commentWorkflowHtm($pid);
    $out = "true";
    response($res,$out);
    exit;
}
 function sendAutoCommentWorkflowHtm(){
    $ut = new Utility();
    $Comment = new Comment();
    $res = $Comment->sendAutoCommentWorkflowHtm($_SESSION['userid']);
    if($res){
    $out = "true";
    response($res,$out);
    exit;
    }
 }
 function transferPartnersCartable(){
    $ut = new Utility();
    $Comment=new Comment();
    list($pid_array,$sender,$receiver) = $ut->varCleanInput(array('pid_array','sender','receiver'));
    $res = $Comment->transferPartnersPayCommentCartable($pid_array,$sender,$receiver);
    if(intval($res)>0){
        $out = "true";
        $res="ارجاع با موفقیت انجام شد";
        response($res,$out);
        exit;
    }
    else
    {
        $out = "false";
        $res="هیچ تغییری انجام  نشد";
        response($res,$out);
        exit;
    }
 }

function getTypeNameList(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->getTypeNames();
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ نوعی تعریف نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getAccountNameList(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->getAccountNames();
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ طرف حسابی تعریف نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getAccountNumList(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cfor) = $ut->varCleanInput(array('cfor'));
    $Comment = new Comment();
    $res = $Comment->getAccountNumbers($cfor);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = array();
        $out = "true";
        response($res,$out);
        exit;
    }
}

function getAccountNumListWithName(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cfor) = $ut->varCleanInput(array('cfor'));
    $Comment = new Comment();
    $res = $Comment->getAccountNumbersWithName($cfor);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = array();
        $out = "true";
        response($res,$out);
        exit;
    }
}

function ShowOtherInfoComment(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->OtherInfoCommentHTM($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function doPrintPayComment(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($cid) = $ut->varCleanInput(array('cid'));
    $htm = $Comment->getPrintPayCommentHtm($cid);
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function getCommentTypeID(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    list($type) = $uti->varCleanInput(array('type'));
    $Comment = new Comment();
    $res = $Comment->commentTypeID($type);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = array();
        $out = "true";
        response($res,$out);
        exit;
    }
}

function transferToPayKesho(){
    $acm = new acm();
    if(!$acm->hasAccess('transferToPayKesho')){
        die("access denied");
        exit;
    }
    $comment = new Comment();
    $res = $comment->transferToPayKeshoHTM();
    $out = "true";
    response($res,$out);
    exit;
}

function doTransferPayComment(){
    $acm = new acm();
    if(!$acm->hasAccess('transferToPayKesho')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Comment = new Comment();
    list($radioValue,$desc,$tick,$pid) = $uti->varCleanInput(array('radioValue','desc','tick','pid'));
    $res = $Comment->createTransferPayComment($radioValue,$desc,$tick,$pid);
    if (intval($res) == -1){
        $res = "فقط به کشو واحد مالی می شود انتقال داد !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getSubLayers(){
    $comment = new Comment();
    $ut = new Utility();
    list($layer1) = $ut->varCleanInput(array('layer1'));
    $res = $comment->getSubLayers($layer1);
    $out = "true";
    response($res,$out);
    exit;
}

function showContractChooseList(){
    $Comment = new Comment();
    $ut = new Utility();
    list($csDate,$ceDate,$cNum,$cAccount,$cAmount,$credit) = $ut->varCleanInput(array('csDate','ceDate','cNum','cAccount','cAmount','credit'));
    $cAmount = str_replace(',','',$cAmount);
    $res = $Comment->getContractChooseList($csDate,$ceDate,$cNum,$cAccount,$cAmount,$credit);
    $out = "true";
    response($res,$out);
    exit;
}

function downloadCheckCarcassFile(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->downloadCheckCarcassFileHtm($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function showAttachedFundToComment(){
    $Comment = new Comment();
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    $res = $Comment->getAttachFundToCommentList($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function showAttachedFundToComment1(){
    $Comment = new Comment();
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    $res = $Comment->getAttachFundToCommentList($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function showAttachedFundToComment3(){
    $Comment = new Comment();
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    $res = $Comment->getAttachFundToCommentList($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function getFundListDetailsShow(){
    $ut = new Utility();
    list($fid) = $ut->varCleanInput(array('fid'));
    if(!intval($fid)){
        $res = "شناسه تنخواه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->showFundListDetailsHTM($fid);
    $out = "true";
    response($res,$out);
    exit;
}

function getFundListAttachShow(){
    $ut = new Utility();
    list($fdid) = $ut->varCleanInput(array('fdid'));
    if(!intval($fdid)){
        $res = "شناسه جزئیات تنخواه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->fundListAttachShowHTM($fdid);
    $out = "true";
    response($res,$out);
    exit;
}

function deleteAttachedFundList(){
    $uti = new Utility();
    $Comment = new Comment();
    list($fid) = $uti->varCleanInput(array('fid'));
    $res = $Comment->deleteAttachedFundList($fid);
    if(intval($res) == -1){
        $res = "اظهارنظر دارای گردش کار می باشد !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function printFundCover(){
    $ut = new Utility();
    $comment = new Comment();
    list($cid) = $ut->varCleanInput(array('cid'));
    $htm = $comment->getPrintFundCoverHTM($cid);
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function doCancellationPayComment(){
    $acm = new acm();
    if(!$acm->hasAccess('cancellationPayComment')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($cid) = $ut->varCleanInput(array('cid'));
    $res = $Comment->cancellationPayComment($cid);
    if ($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !!!";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ اجزا تنخواه +++++++++++++++++++++++

function showFundListManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('fundListManage')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($name,$code,$amount,$layer1,$layer2,$layer3,$page) = $ut->varCleanInput(array('name','code','amount','layer1','layer2','layer3','page'));
    $amount = str_replace(',','',$amount);
    $res = $Comment->getFundListManageList($name,$code,$amount,$layer1,$layer2,$layer3,$page);
    $ut->fileRecorder($res);
    if($page == 1){
        $_SESSION['calcFundManage'] = $Comment->getFundListManageListCountRows($name,$code,$amount,$layer1,$layer2,$layer3);
    }
    $totalRows = $_SESSION['calcFundManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= "4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="ثبت کننده";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="recorder";
    $c++;

    $feilds[$c]['title']="نام تنخواه";
    $feilds[$c]['width']= "22%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fundName";
    $c++;

    $feilds[$c]['title']="کد تنخواه";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="unCode";
    $c++;

    $feilds[$c]['title']="سرگروه";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="layer1";
    $c++;

    $feilds[$c]['title']="زیرگروه";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="layer2";
    $c++;

    $feilds[$c]['title']="زیرگروه فرعی";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="layer3";
    $c++;

    $feilds[$c]['title']="مبلغ کل";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="finalAmount";
    $c++;

    $feilds[$c]['title']="جزئیات";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="createFundListDetails";
    $feilds[$c]['icon']="fa-puzzle-piece";

    $pagerType = 1;
    $listTitle = " تعداد تنخواه : ".$totalRows." عدد ";
    $tableID = "fundListManageBody-table";
    $jsf = "showFundListManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getFundListInfo(){
    $uti = new Utility();
    list($fid) = $uti->varCleanInput(array('fid'));
    if(!intval($fid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->fundListInfo($fid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateFundList(){
    $acm = new acm();
    if(!$acm->hasAccess('fundListManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($fid,$name,$layer1,$layer2,$layer3) = $ut->varCleanInput(array('fid','name','layer1','layer2','layer3'));
    if(intval($fid) > 0){  //edit
        $res = $Comment->editFundList($fid,$name,$layer1,$layer2,$layer3);
        if(intval($res) == -1){
            $res = "این تنخواه قبلا به اظهارنظری نسبت داده شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif($res == true){
            $res = 'درخواست با موفقیت انجام شد.';
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $Comment->createFundList($name,$layer1,$layer2,$layer3);
        if($res){
            $res = 'درخواست با موفقیت انجام شد.';
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !!!";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getFundListDetailsInfo(){
    $ut = new Utility();
    list($fid) = $ut->varCleanInput(array('fid'));
    if(!intval($fid)){
        $res = "شناسه تنخواه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->fundListDetailsHTM($fid);
    $out = "true";
    response($res,$out);
    exit;
}

function doCreateFundListDetails(){
    $acm = new acm();
    if(!$acm->hasAccess('fundListManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($fid,$CDate,$Description,$ReqNum,$PlaceUse,$Amount) = $ut->varCleanInput(array('fid','CDate','Description','ReqNum','PlaceUse','Amount'));
    $Amount = str_replace(',','',$Amount);
    $res = $Comment->createFundListDetails($fid,$CDate,$Description,$ReqNum,$PlaceUse,$Amount);
    if(intval($res) == -1){
        $res = "این تنخواه قبلا به اظهارنظری نسبت داده شده است !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = 'درخواست با موفقیت انجام شد.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getFundListAttachInfo(){
    $ut = new Utility();
    list($fdid) = $ut->varCleanInput(array('fdid'));
    if(!intval($fdid)){
        $res = "شناسه جزئیات تنخواه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->fundListAttachHTM($fdid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachFileToFundList(){
    $acm = new acm();
    if(!$acm->hasAccess('fundListManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Comment = new Comment();
    list($fid,$fdid) = $uti->varCleanInput(array('fid','fdid'));
    $files = $_FILES['files'];
    $res = $Comment->attachFileToFundList($fid,$fdid,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -4){
        $res = "این تنخواه قبلا به اظهارنظری نسبت داده شده است !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteFundListDetails(){
    $acm = new acm();
    if(!$acm->hasAccess('fundListManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Comment = new Comment();
    list($fid,$fdid) = $uti->varCleanInput(array('fid','fdid'));
    $res = $Comment->deleteFundListDetails($fid,$fdid);
    if(intval($res) == -1){
        $res = "این تنخواه قبلا به اظهارنظری نسبت داده شده است !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif(intval($res) == -2){
        $res = "این مورد را شما ثبت ننموده اید !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif ($res == true){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteFundListAttach(){
    $acm = new acm();
    if(!$acm->hasAccess('fundListManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Comment = new Comment();
    list($fid,$fileID) = $uti->varCleanInput(array('fid','fileID'));
    $res = $Comment->deleteFundListAttach($fid,$fileID);
    if(intval($res) == -1){
        $res = "این تنخواه قبلا به اظهارنظری نسبت داده شده است !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif(intval($res) == -2){
        $res = "این فایل را شما آپلود ننموده اید !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif ($res == true){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doAttachCommentToFundList(){
    $acm = new acm();
    if(!$acm->hasAccess('fundListManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($unCode,$fid) = $ut->varCleanInput(array('unCode','fid'));
    $res = $Comment->attachCommentToFundLis($unCode,$fid);
    if(intval($res) == -1){
        $res = "قبلا اظهارنظر پیوست شده است !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif(intval($res) == -2){
        $res = "ابتدا جزئیات تنخواه را ثبت نمایید !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif(intval($res) == -3){
        $res = "اظهارنظر دارای گردش کار می باشد !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif(intval($res) == -4){
        $res = "اظهارنظر تنخواه هزینه ای، مصرفی یا مواد اولیه نمی باشد !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif(intval($res) == -5){
        $res = "نوع تنخواه با زیرگروه اظهارنظر انتخابی، هماهنگ نیست !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = 'درخواست با موفقیت انجام شد.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ دریافتی از مشتری +++++++++++++++++++++++

function showReceivedCustomerManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('receivedCustomerManage')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($rsDate,$reDate,$RType,$RMethod,$CName,$RAmount,$page) = $ut->varCleanInput(array('rsDate','reDate','RType','RMethod','CName','RAmount','page'));
    $res = $Comment->getReceivedCustomerList($rsDate,$reDate,$RType,$RMethod,$CName,$RAmount,$page);
    if($page == 1){
        $_SESSION['calcRCUManage'] = $Comment->getReceivedCustomerListCountRows($rsDate,$reDate,$RType,$RMethod,$CName,$RAmount);
        $t = $Comment->getTotalAmountReceivedCustomer($rsDate,$reDate,$RType,$RMethod,$CName,$RAmount);
        $_SESSION['calcTotalAmountCR'] = $t[0];
        $_SESSION['calcTotalAmountCR1'] = $t[1];
        $_SESSION['calcTotalPayCR'] = $t[2];
        $_SESSION['calcSumAmounts'] = $t[3];
    }
    $totalRows = $_SESSION['calcRCUManage'];
    $headerTxt = 'مبلغ دریافتی از مشتری (بابت فروش) = <span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 16px;">'.number_format($_SESSION['calcTotalAmountCR']).'</span>&nbsp;&nbsp;ریال <br/>مبلغ دریافتی از مشتری (بابت چک برگشتی) = <span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 16px;">'.number_format($_SESSION['calcTotalAmountCR1']).'</span>&nbsp;&nbsp;ریال <br/> مبلغ پرداختی اظهارنظر = <span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 16px;">'.number_format($_SESSION['calcTotalPayCR']).'</span>&nbsp;&nbsp;ریال&nbsp;&nbsp;<button class="btn btn-primary" onclick="showCustomerReceiveComments()">مشاهده اظهارنظرها</button><br/> جمع کل مبالغ = <span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 16px;">'.number_format($_SESSION['calcSumAmounts']).'</span>&nbsp;&nbsp;ریال';

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= "4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نوع دریافتی";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="receiveType";
    $c++;

    $feilds[$c]['title']="روش دریافتی";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="receiveMethod";
    $c++;

    $feilds[$c]['title']="تاریخ دریافتی";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="receiveDate";
    $c++;

    $feilds[$c]['title']="طرف مقابل";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="depositor";
    $c++;

    $feilds[$c]['title']="کد تفضیلی";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="codeTafzili";
    $c++;

    $feilds[$c]['title']="نام بانک";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="bankID";
    $c++;

    $feilds[$c]['title']="نام pos";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="posID";
    $c++;

    $feilds[$c]['title']="مبلغ (دریافتی/چک)";
    $feilds[$c]['width']= "14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="receiveAmount";
    $c++;

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= "9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="اطلاعات چک";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="receivedCustomerCheckInfo";
    $feilds[$c]['disabled']="yes";
    $feilds[$c]['icon']="fa-tv";
    $c++;

    $feilds[$c]['title']="ضمیمه ها";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowAttachFileReceivedCustomer";
    $feilds[$c]['icon']="fa-link";


    $pagerType = 1;
    $listTitle = " تعداد دریافتی ها : ".$totalRows." عدد ";
    $tableID = "receivedCustomerManageBody-table";
    $jsf = "showReceivedCustomerManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf,'',array(),'',$headerTxt);
    $out = "true";
    response($htm,$out);
    exit;
}

function doEditCreateReceivedCustomer(){
    $acm = new acm();
    if(!$acm->hasAccess('receivedCustomerManage')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $ut = new Utility();
    list($rid,$RType,$RMethod,$amount,$rDate,$CstName,$CstCode,$desc,$bank,$pos,$serial,$chDate,$chOwner,$chOwnerC) = $ut->varCleanInput(array('rid','RType','RMethod','amount','rDate','CstName','CstCode','desc','bank','pos','serial','chDate','chOwner','chOwnerC'));
    $amount = str_replace(',','',$amount);
    if(intval($rid) > 0){  //edit
        $res = $Comment->editReceivedCustomer($rid,$RType,$RMethod,$amount,$rDate,$CstName,$CstCode,$desc,$bank,$pos);
        if ($res != false){
            $res = 'درخواست با موفقیت انجام شد.';
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !!!";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $Comment->createReceivedCustomer($RType,$RMethod,$amount,$rDate,$CstName,$CstCode,$desc,$bank,$pos,$serial,$chDate,$chOwner,$chOwnerC);
        if ($res != false){
            $res = 'درخواست با موفقیت انجام شد.';
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !!!";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getReceivedCustomerInfo(){
    $uti = new Utility();
    list($reid) = $uti->varCleanInput(array('reid'));
    if(!intval($reid)){
        $res = "شناسه دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->receivedCustomerInfo($reid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function receivedCustomerCheckInfo(){
    $acm = new acm();
    if(!$acm->hasAccess('receivedCustomerManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($rcid) = $ut->varCleanInput(array('rcid'));
    if(!intval($rcid)){
        $res = "شناسه دریافتی بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->receivedCustomerCheckInfo($rcid);
    $out = "true";
    response($res,$out);
    exit;
}

function getExcelReceivedCustomer(){
    $acm = new acm();
    if(!$acm->hasAccess('receivedCustomerManage') || !$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($rsDate,$reDate,$RType,$RMethod,$CName,$RAmount) = $ut->varCleanInput(array('rsDate','reDate','RType','RMethod','CName','RAmount'));
    $res = $Comment->getExcelReceivedCustomer($rsDate,$reDate,$RType,$RMethod,$CName,$RAmount);
    $hdarray = array('نوع دریافتی','روش دریافتی','تاریخ دریافتی','طرف مقابل','کد تفضیلی طرف مقابل','نام بانک','نام pos','مبلغ (دریافتی/چک)','توضیحات','شماره چک','تاریخ چک','تعداد چک','نام صاحب چک','کد ملی صاحب چک');
    $fieldNames = array('receiveType','receiveMethod','receiveDate','depositor','codeTafzili','bankID','posID','receiveAmount','description','checkSerial','checkDate','checkNumber','checkOwner','checkOwnerCode');
    $name = "PieceTimingList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getAttachedReceivedCustomerFile(){
    $ut = new Utility();
    list($reid) = $ut->varCleanInput(array('reid'));
    if(!intval($reid)){
        $res = "شناسه دریافتی بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->attachedReceivedCustomerFileHtm($reid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachReceivedCustomerFile(){
    $uti = new Utility();
    $Comment = new Comment();
    list($reid,$info) = $uti->varCleanInput(array('reid','info'));
    $files = $_FILES['files'];
    $res = $Comment->attachFileToReceivedCustomer($reid,$info,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteAttachReceivedCustomerFile(){
    $uti = new Utility();
    $Comment = new Comment();
    list($fid) = $uti->varCleanInput(array('fid'));
    $res = $Comment->deleteAttachReceivedCustomerFile($fid);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function ShowAttachFileReceivedCustomer(){
    $ut = new Utility();
    list($crid) = $ut->varCleanInput(array('crid'));
    if(!intval($crid)){
        $res = "شناسه دریافتی بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->attachFileReceivedCustomerHtm($crid);
    $out = "true";
    response($res,$out);
    exit;
}

function showCustomerReceiveComments(){
    $Comment = new Comment();
    $ut = new Utility();
    list($csDate,$ceDate,$toward,$cAccount,$cAmount) = $ut->varCleanInput(array('csDate','ceDate','toward','cAccount','cAmount'));
    $cAmount = str_replace(',','',$cAmount);
    $res = $Comment->getCustomerReceiveComments($csDate,$ceDate,$toward,$cAccount,$cAmount);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowCommentCheckInCustomerReceive(){
    $acm = new acm();
    if(!$acm->hasAccess('receivedCustomerManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->commentChecksHTM($cid);
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ مدیریت قراردادها +++++++++++++++++++++++

function showContractManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('contractManagement')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($csDate,$ceDate,$cNum,$cAccount,$cAmount,$credit,$status,$page) = $ut->varCleanInput(array('csDate','ceDate','cNum','cAccount','cAmount','credit','status','page'));
    $res = $Comment->getContractManageList($csDate,$ceDate,$cNum,$cAccount,$cAmount,$credit,$status,$page);
    if($page == 1){
        $_SESSION['calcConManage'] = $Comment->getContractManageListCountRows($csDate,$ceDate,$cNum,$cAccount,$cAmount,$credit,$status);
    }
    $totalRows = $_SESSION['calcConManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= "4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نوع قرارداد";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="contractType";
    $c++;

    $feilds[$c]['title']="شماره قرارداد";
    $feilds[$c]['width']= "10";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="number";
    $c++;

    $feilds[$c]['title']="موضوع قرارداد";
    $feilds[$c]['width']= "18%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="subject";
    $c++;

    $feilds[$c]['title']="طرف مقابل";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="accountName";
    $c++;

/*    $feilds[$c]['title']="مبلغ هرساعت حضور";
    $feilds[$c]['width']= "11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="hourAmount";
    $c++;

    $feilds[$c]['title']="ماکسیمم ساعات حضور در ماه";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="maxHour";
    $c++;

    $feilds[$c]['title']="مبلغ ماهانه قرارداد";
    $feilds[$c]['width']= "11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="monthlyAmount";
    $c++;*/

    $feilds[$c]['title']="تاریخ شروع";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="csDate";
    $c++;

    $feilds[$c]['title']="تاریخ اتمام";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="ceDate";
    $c++;

    $feilds[$c]['title']="مدت قرارداد";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="creditPeriod";
    $c++;

    $feilds[$c]['title']="مبلغ کل قرارداد";
    $feilds[$c]['width']= "11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalAmount";
    $c++;

    $feilds[$c]['title']="سایر اطلاعات";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowContractDetails";
    $feilds[$c]['icon']="fa-tv";
    $c++;

    $feilds[$c]['title']="اظهارنظرها";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowContractPayComments";
    $feilds[$c]['icon']="fa-file";
    $c++;

    $feilds[$c]['title']="فایل پیوست";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowAttachmentFileContract";
    $feilds[$c]['icon']="fa-link";

    $pagerType = 1;
    $listTitle = " تعداد قراردادها : ".$totalRows." عدد ";
    $tableID = "contractManageBody-table";
    $jsf = "showContractManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getContractInfo(){
    $uti = new Utility();
    list($cid) = $uti->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->contractInfo($cid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getContractType(){
    $uti = new Utility();
    list($cid) = $uti->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->contractType($cid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}
function delete_contract_formula_row(){
  
    $acm=new acm();
    if(!$acm->hasAccess("contractManagement")){
        die("access denied");
        exit;
    }
    $db = new DBi();
    $ut = new Utility();
    $Comment = new Comment();
    $row_id = $_POST['row_id'];
    $result = $Comment->delete_contract_formula_row($row_id);
    if($result==0){
        $res = 'تغییری اعمال نشد';
        $out = "false";
        response($res,$out);
        exit;
    }
    elseif($result==-1){
        $res = 'برای این ردیف  اظهار نظر  پرداخت صادر شده است و قابل حذف نمی باشد';
        $out = "false";
        response($res,$out);
        exit;
    }
    elseif($result==1){
        $res = 'ردیف پرداخت با موفقیت حذف شد';
        $out = "true";
        response($res,$out);
        exit;
    }
}

function get_contract_payed_records(){
    $acm = new acm();
    if(!$acm->hasAccess('contractManagement')){
        die("access denied");
        exit;
    }
    $contract_id=$_POST['contract_id'];
    $contract_number=$_POST['contract_number'];
    $Comment = new Comment();
    error_log($contract_id);
    error_log($contract_number);
    $res=$Comment->get_contract_payed_records($contract_id,$contract_number);
    if($res){
//$res = 'درخواست با موفقیت انجام شد.';
        $out = "true";
        response($res,$out);
        exit;
    }


   

}
function doEditCreateContract(){
    $acm = new acm();
    if(!$acm->hasAccess('contractManagement')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
   
    $Comment = new Comment();
    list($cid,$number,$accName,$code,$accNum,$amount,$credit,$sDate,$eDate,$subject,$cType,$HourAmount,$MaxHour,$pay_method_type,$has_contract_formula) = $uti->varCleanInput(array('cid','number','accName','code','accNum','amount','credit','sDate','eDate','subject','cType','HourAmount','MaxHour','pay_method_type','has_contract_formula'));
    $amount = str_replace(',','',$amount);
    $HourAmount = str_replace(',','',$HourAmount);
    $formula_array=$_POST['formula_array'];
    if(intval($cid) > 0){  //edit
        $res = $Comment->editContract($cid,$number,$accName,$code,$accNum,$amount,$credit,$sDate,$eDate,$subject,$cType,$HourAmount,$MaxHour,$formula_array,$pay_method_type,$has_contract_formula);
        if (intval($res) == -2){
            $res = "بدلیل ثبت اظهارنظر برای این قرارداد، امکان ویرایش وجود ندارد !!!";
            $out = "false";
            response($res,$out);
            exit;
        }elseif (intval($res) == -3){
            $res = "شما ثبت کننده این قرارداد نمی باشید !!!";
            $out = "false";
            response($res,$out);
            exit;
        }elseif($res == true){
            $res = 'درخواست با موفقیت انجام شد.';
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !!!";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else
    {//create
        $res = $Comment->createContract($number,$accName,$code,$accNum,$amount,$credit,$sDate,$eDate,$subject,$cType,$HourAmount,$MaxHour,$formula_array,$pay_method_type,$has_contract_formula);
        if($res==-1)
        {
            $res = 'مجموع ردیف های ثبت شده برای این قراردا از  مبلغ کل قرارداد بیشتر است';
            $out = "false";
            response($res,$out);
            exit;
        }
        elseif($res==1)
        {
            $res = 'درخواست با موفقیت انجام شد.';
            $out = "true";
            response($res,$out);
            exit;
        }
        else
        {
            $res = "هیچ تغییری اعمال نگردید !!!";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}
function deleteAddendumContract(){
    $acm = new acm();
    if(!$acm->hasAccess('contractManagement')){
        die("access denied");
        exit;
    } 
    $ut=new Utility(); 
    $Comment=new Comment();
    list($addendum_id)=$ut->varCleanInput(array('addendum_id'));
    $res = $Comment->deleteAddendumContract($addendum_id);
    if($res==true){
        $finalRes="تغییرات با موفقیت انجام شد";
        response($finalRes,"true");
   }
   else{
    return false;
   }
    
}

function updateAddendumContract(){
     $acm = new acm();
    if(!$acm->hasAccess('contractManagement')){
        die("access denied");
        exit;
    } 
    $ut=new Utility(); 
    $Comment=new Comment();
    list($addendum_id)=$ut->varCleanInput(array('addendum_id','editType'));
    $res = $Comment->updateAddendumContract($addendum_id);
    if(is_array($res) && count($res)>0){
        $finalRes=$res;
        response($finalRes,"true");
   }
   else{
    return false;
   }
}
function getContractAddendumDetailes(){
    $acm = new acm();
    if(!$acm->hasAccess('contractManagement')){
        die("access denied");
        exit;
    }
   $cid=$_POST['cid'] ;
   $Comment=new Comment();
   $html_res=$Comment->getContractAddendumDetailes($cid);
   if($html_res){
        response($html_res,"true");
   }
   else{
    return false;
   }
   

}
function doEditCreateContractAddendum(){
    $acm = new acm();
    if(!$acm->hasAccess('contractManagement')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Comment = new Comment();
    
    list($addendumDuty,$addendumCost,$addendumDate,$contract_id,$addendum_id) = $uti->varCleanInput(array('addendumDuty','addendumCost','addendumDate','contract_id','addendum_id'));
    $addendumCost=str_replace(",","",$addendumCost);
    if(empty($addendum_id)){
        $addendum_id=0;
    }
    $res = $Comment->editCreateContractAddendum($addendumDuty,$addendumCost,$addendumDate,$contract_id,$addendum_id);
    if ($res == true){
        $res = 'درخواست با موفقیت انجام شد.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !!!";
        $out = "false";
        response($res,$out);
        exit;
    }

};

function doArchiveExtensionContract(){
    $acm = new acm();
    if(!$acm->hasAccess('contractManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $comment = new Comment();
    list($cid,$cType,$cntType,$desc,$hourAmount,$maxHour,$amount,$eDate,$credit) = $ut->varCleanInput(array('cid','cType','cntType','desc','hourAmount','maxHour','amount','eDate','credit'));
    $hourAmount = str_replace(',','',$hourAmount);
    $amount = str_replace(',','',$amount);
    if(!intval($cid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $comment->archiveExtensionContract($cid,$cType,$cntType,$desc,$hourAmount,$maxHour,$amount,$eDate,$credit);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function ShowContractDetails(){
    $acm = new acm();
    if(!$acm->hasAccess('contractManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه قرارداد بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->OtherInfoContractHTM($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function getAttachedContractFile(){
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه قرارداد بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->attachedContractFileHtm($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachFileToContract(){
    $uti = new Utility();
    $Comment = new Comment();
    list($cid,$info) = $uti->varCleanInput(array('cid','info'));
    $files = $_FILES['files'];
    $res = $Comment->attachFileToContract($cid,$info,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteAttachContractFile(){
    $uti = new Utility();
    $Comment = new Comment();
    list($fid) = $uti->varCleanInput(array('fid'));
    $res = $Comment->deleteAttachContractFile($fid);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function ShowAttachmentFileContract(){
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه قرارداد بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->attachmentFileContractHtm($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function getContractCommentDates(){
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه قرارداد بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->contractCommentDatesHtm($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function doCreateContractCommentDates(){
    $ut = new Utility();
    $Comment = new Comment();
    list($cid,$ccDate) = $ut->varCleanInput(array('cid','ccDate'));
    $res = $Comment->createContractCommentDates($cid,$ccDate);
    if ($res){
        $res = "موفق بود.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "تغییری اعمال نگردید !!!";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteContractCommentDate(){
    $uti = new Utility();
    $Comment = new Comment();
    list($dateID) = $uti->varCleanInput(array('dateID'));
    $res = $Comment->deleteContractCommentDate($dateID);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function ShowContractPayComments(){
    $uti = new Utility();
    $Comment = new Comment();
    list($cid) = $uti->varCleanInput(array('cid'));
    $res = $Comment->getContractPayComments($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function getContractAccountInfo(){
    $uti = new Utility();
    $Comment = new Comment();
    list($cid) = $uti->varCleanInput(array('cid'));
    $res = $Comment->getContractAccountInfo($cid);
    if ($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'اطلاعات قرارداد موجود نیست !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function get_contract_pay_rows(){
    $ut = new Utility();
    $comment=new Comment();
    $contract_id=$_POST['contract_id'];
    $pay_type=$_POST['pay_type'];
    $result=$comment->get_contract_pay_rows($contract_id,$pay_type);
    if($result)
    {
        $out = "true";
        response($result,$out);
        exit;
    }   
}

function ShowCommentCheckInContract(){
    $acm = new acm();
    if(!$acm->hasAccess('contractManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->commentChecksHTM($cid);
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ مدیریت طرف حساب ها +++++++++++++++++++++++

function showAccountManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($aName,$aCode,$page) = $ut->varCleanInput(array('aName','aCode','page'));
    $res = $Comment->getAccountManageList($aName,$aCode,$page);
    if($page == 1){
        $_SESSION['calcAccManage'] = $Comment->getAccountManageListCountRows($aName,$aCode);
    }
    $totalRows = $_SESSION['calcAccManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام طرف حساب";
    $feilds[$c]['width']="54%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="کد تفضیلی";
    $feilds[$c]['width']="24%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="code";
    $c++;

    $feilds[$c]['title']="اطلاعات حساب";
    $feilds[$c]['width']="18%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowAccountInfo";
    $feilds[$c]['icon']="fa-file-invoice";

    $pagerType = 1;
    $listTitle = " تعداد طرف حساب : ".$totalRows." عدد ";
    $tableID = "accountManageBody-table";
    $jsf = "showAccountManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function doEditCreateAccount(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Comment = new Comment();
    list($aid,$Name,$code,$an,$codeMelli) = $uti->varCleanInput(array('aid','Name','code','an','codeMelli'));
    if(intval($aid) > 0){//edit
        $res = $Comment->editAccount($aid,$Name,$code,$an,$codeMelli);
        if(intval($res) == -2){
            $res = "این کد تفضیلی قبلا ثبت شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif(intval($res) == -1){
            $res = "اطلاعات حساب کامل نمی باشد !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif($res == true){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $Comment->createAccount($Name,$code,$an,$codeMelli);
        if(intval($res) == -2){
            $res = "این کد تفضیلی قبلا ثبت شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif(intval($res) == -1){
            $res = "اطلاعات حساب کامل نمی باشد !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif($res == true){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getAccountInfo(){
    $uti = new Utility();
    list($aid) = $uti->varCleanInput(array('aid'));
    if(!intval($aid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->accountInfo($aid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function ShowAccountInfo(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($aid) = $ut->varCleanInput(array('aid'));
    if(!intval($aid)){
        $res = "شناسه طرف حساب بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->accountInfoHTM($aid);
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ مدیریت واحدهای مربوطه +++++++++++++++++++++++

function showUnitManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $Comment->getUnitManageList($page);
    if($page == 1){
        $_SESSION['calcUnitManage'] = $Comment->getUnitManageListCountRows();
    }
    $totalRows = $_SESSION['calcUnitManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام واحد مربوطه";
    $feilds[$c]['width']="26%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="unitName";
    $c++;

    $feilds[$c]['title']="افراد مجاز به ثبت اظهارنظر";
    $feilds[$c]['width']="50%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="uNames";
    $c++;

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="unitDesc";

    $pagerType = 1;
    $listTitle = " تعداد واحدهای مربوطه : ".$totalRows." عدد ";
    $tableID = "commentUnitManageBody-table";
    $jsf = "showUnitManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getCommentUnitInfo(){
    $uti = new Utility();
    list($uid) = $uti->varCleanInput(array('uid'));
    if(!intval($uid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->unitInfo($uid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateCommentUnit(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Comment = new Comment();
    list($uid,$Uname,$Udesc,$users) = $uti->varCleanInput(array('uid','Uname','Udesc','users'));
    if(intval($uid) > 0){//edit
        $res = $Comment->editUnit($uid,$Uname,$Udesc,$users);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $Comment->createUnit($Uname,$Udesc,$users);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

//++++++++++++++++++++++ پرداخت اظهارنظر +++++++++++++++++++++++

function showFinalPayCommentManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('payCommentManage')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($pcaName,$pcToward,$pcAmount,$typeDay,$page) = $ut->varCleanInput(array('pcaName','pcToward','pcAmount','typeDay','page'));
    $res = $Comment->getFinalPayCommentManageList($pcaName,$pcToward,$pcAmount,$typeDay,$page);
    if($page == 1){
        $_SESSION['calcPCommManage'] = $Comment->getFinalPayCommentManageListCountRows($pcaName,$pcToward,$pcAmount,$typeDay);
       // $ut->fileRecorder( 'count:'.$_SESSION['calcPCommManage']);
    }
    $totalRows = $_SESSION['calcPCommManage'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="نقدی1";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="CashSection";
    $c++;

    $feilds[$c]['title']="چک";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="NonCashSection";
    $c++;

    $feilds[$c]['title']="مانده حساب";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="leftOver";
    $c++;

    $feilds[$c]['title']="در وجه";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="بابت";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Toward";
    $c++;

    $feilds[$c]['title']="سررسید چک";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="paymentMaturityCheck";
    $c++;

    $feilds[$c]['title']="سررسید نقدی";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="paymentMaturityCash";
    $c++;

    $feilds[$c]['title']="ارجاع";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="sendPayCommentInPC";
    $feilds[$c]['icon']="fa-paper-plane";
    $c++;

    $feilds[$c]['title']="واریز";
    $feilds[$c]['width']= "4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="createDepositRegistration";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="ثبت چک";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="createPayCommentCheck";
    $feilds[$c]['icon']="fa-money-check-alt";
    $c++;

    $feilds[$c]['title']="اطلاعات";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowOtherInfoPayComment";
    $feilds[$c]['icon']="fa-tv";
    $c++;

    $feilds[$c]['title']="پیوست";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowAttachmentFileComment";
    $feilds[$c]['icon']="fa-file";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowWorkflowComment";
    $feilds[$c]['icon']="fa-sitemap";
    $c++;

    $feilds[$c]['title']="نمایش";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="printPayComment";
    $feilds[$c]['icon']="fa-search";
    $c++;

    $feilds[$c]['title']="ارسال";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="payCommentFinalApproval";
    $feilds[$c]['icon']="fa-paper-plane";

    $pagerType = 1;
    $listTitle = " تعداد اظهار نظرها : ".$totalRows." عدد ";
    $tableID = "payCommentManageBody-table";
    $jsf = "showFinalPayCommentManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showSortPayCommentManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('payCommentManage')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($pcaName,$pcToward,$pcAmount,$typeDay,$sort,$page) = $ut->varCleanInput(array('pcaName','pcToward','pcAmount','typeDay','sort','page'));
    $res = $Comment->getSortPayCommentManageList($pcaName,$pcToward,$pcAmount,$typeDay,$sort,$page);
    if($page == 1){
        $_SESSION['calcSPCommManage'] = $Comment->getSortPayCommentManageListCountRows($pcaName,$pcToward,$pcAmount,$typeDay);
    }
    $totalRows = $_SESSION['calcSPCommManage'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="2نقدی";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="CashSection";
    $c++;

    $feilds[$c]['title']="چک";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="NonCashSection";
    $c++;

    $feilds[$c]['title']="مانده حساب";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="leftOver";
    $c++;

    $feilds[$c]['title']="در وجه";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="بابت";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Toward";
    $c++;

    $feilds[$c]['title']="سررسید چک";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="paymentMaturityCheck";
    $c++;

    $feilds[$c]['title']="سررسید نقدی";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="paymentMaturityCash";
    $c++;

    $feilds[$c]['title']="ارجاع";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="sendPayCommentInPC";
    $feilds[$c]['icon']="fa-paper-plane";
    $c++;

    $feilds[$c]['title']="واریز";
    $feilds[$c]['width']= "4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="createDepositRegistration";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="ثبت چک";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="createPayCommentCheck";
    $feilds[$c]['icon']="fa-money-check-alt";
    $c++;

    $feilds[$c]['title']="اطلاعات";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowOtherInfoPayComment";
    $feilds[$c]['icon']="fa-tv";
    $c++;

    $feilds[$c]['title']="پیوست";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowAttachmentFileComment";
    $feilds[$c]['icon']="fa-file";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowWorkflowComment";
    $feilds[$c]['icon']="fa-sitemap";
    $c++;

    $feilds[$c]['title']="نمایش";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="printPayComment";
    $feilds[$c]['icon']="fa-search";
    $c++;

    $feilds[$c]['title']="ارسال";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="payCommentFinalApproval";
    $feilds[$c]['icon']="fa-paper-plane";

    $pagerType = 1;
    $listTitle = " تعداد اظهار نظرها : ".$totalRows." عدد ";
    $tableID = "sortPayCommentManageBody-table";
    $jsf = "showSortPayCommentManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getDepositorNameList(){
    $Comment = new Comment();
    $res = $Comment->getDepositorNames();
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ واریزکننده ای تعریف نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getBankNameList(){
    $Comment = new Comment();
    $res = $Comment->getBankNames();
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ بانکی تعریف نشده است !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function checkLeftOverCash(){
    $ut = new Utility();
    $Comment = new Comment();
    list($pid,$DAmount) = $ut->varCleanInput(array('pid','DAmount'));
    $DAmount = str_replace(',','',$DAmount);
    $res = $Comment->checkLeftOverCash($pid,$DAmount);
    $out = "true";
    response($res,$out);
    exit;
}

function doCreateDepositRegistration(){
    $acm = new acm();
    if(!$acm->hasAccess('payCommentManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($pid,$Ddate,$Depositor,$DCode,$DBank,$DAmount,$Description) = $ut->varCleanInput(array('pid','Ddate','Depositor','DCode','DBank','DAmount','Description'));
    $DAmount = str_replace(',','',$DAmount);
    $files = $_FILES['files'];
    $res = $Comment->createDepositRegistration($pid,$Ddate,$Depositor,$DCode,$DBank,$DAmount,$Description,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deletePaymentReceipt(){
    $acm = new acm();
    if(!$acm->hasAccess('deletePaymentReceipt')){
        $res = "شما دسترسی لازم را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $ut = new Utility();
    list($did) = $ut->varCleanInput(array('did'));
    if(!intval($did)){
        $res = "شناسه واریزی بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->doDeletePaymentReceipt($did);
    if ($res){
        $res = 'درخواست با موفقیت انجام شد.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function downloadPaymentReceipt(){
    $ut = new Utility();
    list($did) = $ut->varCleanInput(array('did'));
    if(!intval($did)){
        $res = "شناسه واریزی بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->downloadPaymentReceiptHtm($did);
    $out = "true";
    response($res,$out);
    exit;
}

function getDepositorCode(){
    $ut = new Utility();
    list($depositor) = $ut->varCleanInput(array('depositor'));
    $Comment = new Comment();
    $res = $Comment->getDepositorCode($depositor);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = array();
        $out = "true";
        response($res,$out);
        exit;
    }
}

function getDepositorNameWC(){
    $ut = new Utility();
    list($code) = $ut->varCleanInput(array('code'));
    $Comment = new Comment();
    $res = $Comment->getDepositorNameWC($code);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = array();
        $out = "true";
        response($res,$out);
        exit;
    }
}

function getCommentDepositInfo(){
    $ut = new Utility();
    list($pid,$deleteShow,$payOrReport,$sendMali,$confMali) = $ut->varCleanInput(array('pid','deleteShow','payOrReport','sendMali','confMali'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->commentDepositHTM($pid,$deleteShow,$payOrReport,$sendMali,$confMali);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowOtherInfoPayComment(){
    $acm = new acm();
    if(!$acm->hasAccess('payCommentManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($did) = $ut->varCleanInput(array('did'));
    if(!intval($did)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->otherInfoPayCommentHTM($did);
    $out = "true";
    response($res,$out);
    exit;
}

function getCatComment(){
    $ut = new Utility();
    $Comment = new Comment();
    list($pid) = $ut->varCleanInput(array('pid'));
    $res = $Comment->getCatComment($pid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }
}

function getCommentCheckInfo(){
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->commentChecksHTM($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function checkLeftOverCheki(){
    $ut = new Utility();
    $Comment = new Comment();
    list($cid,$CAmount) = $ut->varCleanInput(array('cid','CAmount'));
    $CAmount = str_replace(',','',$CAmount);
    $res = $Comment->checkLeftOverCheki($cid,$CAmount);
    $out = "true";
    response($res,$out);
    exit;
}

function doAddCheckToComment(){  //+++++ افزودن چک به اظهارنظر +++++
    $acm = new acm();
    if(!$acm->hasAccess('payCommentManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cid,$CDate,$CAmount,$CNum,$CType,$desc) = $ut->varCleanInput(array('cid','CDate','CAmount','CNum','CType','desc'));
    $CAmount = str_replace(',','',$CAmount);
    $Comment = new Comment();
    $res = $Comment->addChecksToComment($cid,$CDate,$CAmount,$CNum,$CType,$desc);
    if($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function checkMabaleghVarizi(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    $Comment = new Comment();
    $res = $Comment->checkMabaleghVarizi($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function doFinalApprovalComment(){
    $acm = new acm();
    if(!$acm->hasAccess('payCommentManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    $Comment = new Comment();
    $res = $Comment->finalApprovalComment($pid);
    if (intval($res) == -2){
        $res = "شما مجاز به ارسال اظهارنظرهای چند مرحله ای نیستید !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -1){
        $res = "در اظهارنظرهای سهامی یا فورج چک، کل مبلغ باید پرداخت شود !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doSendDepositToMali(){
    $acm = new acm();
    if(!$acm->hasAccess('payCommentManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pid,$did) = $ut->varCleanInput(array('pid','did'));
    $Comment = new Comment();
    $res = $Comment->sendDepositToMali($pid,$did);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doSendPayCommentInPC(){
    $acm = new acm();
    if(!$acm->hasAccess('payCommentManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($cid,$receiver,$desc) = $ut->varCleanInput(array('cid','receiver','desc'));
    $res = $Comment->sendPayCommentInPC($cid,$receiver,$desc);
    if (intval($res) == -1){
        $res = "اظهارنظر دارای پرداختی می باشد !!!";
        $out = "false";
        response($res,$out);
        exit;
    }elseif ($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !!!";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function createPayCommentExcel(){
    $Comment = new Comment();
    $ut = new Utility();

    $res = $Comment->getPayCommentExcel();
    $hdarray = array('نوع','تاریخ ثبت','در وجه','کد تفضیلی','بابت','مبلغ','نقدی','چک','سررسید نقدی','سررسید چک','توضیحات');
    $fieldNames = array('sendType','cDate','accName','codeTafzili','Toward','Amount','CashSection','NonCashSection','paymentMaturityCash','paymentMaturityCheck','desc');
    $name = "payCommentList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function createDepositExcel(){
    $Comment = new Comment();
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    $res = $Comment->getDepositExcel($pid);
    $hdarray = array('تاریخ واریز','ثبت کننده','طرف مقابل','واریزکننده','نام بانک','مبلغ واریزی','توضیحات');
    $fieldNames = array('dDate','fname','accName','depositor','dBank','dAmount','dDesc');
    $name = "depositList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ واریزکنندگان +++++++++++++++++++++++

function showDepositorManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('depositorsManage')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($Name,$Code,$page) = $ut->varCleanInput(array('Name','Code','page'));
    $res = $Comment->getDepositorManageList($Name,$Code,$page);
    if($page == 1){
        $_SESSION['calcDepManage'] = $Comment->getDepositorManageListCountRows($Name,$Code);
    }
    $totalRows = $_SESSION['calcDepManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="نام واریزکننده";
    $feilds[$c]['width']="80%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="کد تفضیلی";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="codeTafzili";
    $c++;

    $feilds[$c]['title']="ویرایش";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="editDepositor";
    $feilds[$c]['icon']="fa-edit";

    $pagerType = 1;
    $listTitle = " تعداد واریزکننده : ".$totalRows." عدد ";
    $tableID = "depositorManageBody-table";
    $jsf = "showDepositorManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getDepositorInfo(){
    $uti = new Utility();
    list($did) = $uti->varCleanInput(array('did'));
    if(!intval($did)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->depositorInfo($did);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateDepositor(){
    $acm = new acm();
    if(!$acm->hasAccess('depositorsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $Comment = new Comment();
    list($did,$Name,$code) = $uti->varCleanInput(array('did','Name','code'));
    if(intval($did) > 0){//edit
        $res = $Comment->editDepositor($did,$Name,$code);
        if(intval($res) == -2){
            $res = "این کد تفضیلی قبلا ثبت شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif($res == true){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $Comment->createDepositor($Name,$code);
        if(intval($res) == -2){
            $res = "این کد تفضیلی قبلا ثبت شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif($res == true){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

//++++++++++++++++++++++ گزارش پرداخت اظهارنظر +++++++++++++++++++++++

function reportPayCommentManage(){
    $acm = new acm();
    if(!$acm->hasAccess('reportPayCommentManage')){
        die("access denied");
        exit;
    }
    $comment = new Comment();
    $htm = $comment->getReportPayCommentManageHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showReportPayCommentManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('reportPayCommentManage')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rcSortType,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate,$dUnit,$mUnit,$layer1,$layer2,$layer3,$page) = $ut->varCleanInput(array('unCode','rcsDate','rceDate','rcaName','rcToward','rcAmount','rcPaytype','rcPaySend','rcSortType','rpcsDate','rpceDate','rpnsDate','rpneDate','dUnit','mUnit','layer1','layer2','layer3','page'));
    $rcAmount = str_replace(',','',$rcAmount);
    $res = $Comment->getReportPayCommentManageList($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rcSortType,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate,$dUnit,$mUnit,$layer1,$layer2,$layer3,$page);
    if($page == 1){
        $_SESSION['calcRPCommManage'] = $Comment->getReportPayCommentManageListCountRows($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate,$dUnit,$mUnit,$layer1,$layer2,$layer3);
        $t = $Comment->getTotalAmountPayComment($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate,$dUnit,$mUnit);
        $_SESSION['calcTotalAmountt'] = $t[0];
        $_SESSION['calcTotalNaghdi'] = $t[1];
        $_SESSION['calcTotalCheki'] = $t[2];
        $_SESSION['calcTotalSDHesab'] = $t[3];
        $_SESSION['calcTotalMandeh'] = $t[4];
    }
    $totalRows = $_SESSION['calcRPCommManage'];
    $headerTxt = 'مبلغ کل = <span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 16px;">'.number_format($_SESSION['calcTotalAmountt']).'</span>&nbsp;&nbsp;ریال <button class="btn btn-primary mr-3" onclick="showSeparationTotalAmount()">جزئیات</button><br/> مبلغ واریزی نقدی = <span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 16px;">'.number_format($_SESSION['calcTotalNaghdi']).'</span>&nbsp;&nbsp;ریال <br/>  مبلغ واریزی چکی = <span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 16px;">'.number_format($_SESSION['calcTotalCheki']).'</span>&nbsp;&nbsp;ریال <br/>  مبلغ ثبت در حساب بستانکاری = <span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 16px;">'.number_format($_SESSION['calcTotalSDHesab']).'</span>&nbsp;&nbsp;ریال <br/>  '.(intval($_SESSION['calcTotalMandeh']) < 0 ? 'مبلغ مازاد پرداختی' : 'مبلغ پرداخت نشده').' = <span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 16px;" dir="ltr">'.number_format($_SESSION['calcTotalMandeh']).'</span>&nbsp;&nbsp;ریال ';

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="تاریخ ثبت";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="cDate";
    $c++;

    $feilds[$c]['title']="3نقدی";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="CashSection";
    $c++;

    $feilds[$c]['title']="چک";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="NonCashSection";
    $c++;

    $feilds[$c]['title']="مبلغ";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Amount";
    $c++;

    $feilds[$c]['title']="در وجه";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="بابت";
    $feilds[$c]['width']= "9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Toward";
    $c++;

    $feilds[$c]['title']="واریزی ها";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showDepositsList";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="چک ها";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowCommentCheck";
    $feilds[$c]['icon']="fa-money-check-alt";
    $c++;

    $feilds[$c]['title']="لیست تنخواه";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showAttachedFundToComment2";
    $feilds[$c]['icon']="fa-list";
    $c++;

    $feilds[$c]['title']="سایر اطلاعات";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showCommentAccountInfo";
    $feilds[$c]['icon']="fa-tv";
    $c++;

    $feilds[$c]['title']="پیوست";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowAttachmentFileRptComment";
    $feilds[$c]['icon']="fa-file";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowWorkflowRptComment";
    $feilds[$c]['icon']="fa-sitemap";
    $c++;

    $feilds[$c]['title']="پرینت";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="printReportPayComment";
    $feilds[$c]['icon']="fa-print";

    $pagerType = 1;
    $listTitle = " تعداد اظهار نظرها : ".$totalRows." عدد ";
    $tableID = "reportPayCommentManageBody-table";
    $jsf = "showReportPayCommentManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf,'',array(),'',$headerTxt);
    $out = "true";
    response($htm,$out);
    exit;
}

function payCommentReportExcel(){
    $Comment = new Comment();
    $ut = new Utility();
    list($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate,$dUnit,$mUnit) = $ut->varCleanInput(array('unCode','rcsDate','rceDate','rcaName','rcToward','rcAmount','rcPaytype','rcPaySend','rpcsDate','rpceDate','rpnsDate','rpneDate','dUnit','mUnit'));

    $res = $Comment->getPayCommentReportExcel($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate,$dUnit,$mUnit);
    $hdarray = array('نوع','تاریخ ثبت','در وجه','کد تفضیلی','بابت','مبلغ','نقدی','چک','توضیحات');
    $fieldNames = array('sendType','cDate','accName','codeTafzili','Toward','Amount','CashSection','NonCashSection','desc');
    $name = "payCommentReportList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showSeparationTotalAmount(){
    $acm = new acm();
    if(!$acm->hasAccess('reportPayCommentManage')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $ut = new Utility();
    list($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate) = $ut->varCleanInput(array('unCode','rcsDate','rceDate','rcaName','rcToward','rcAmount','rcPaytype','rcPaySend','rpcsDate','rpceDate','rpnsDate','rpneDate'));
    $rcAmount = str_replace(',','',$rcAmount);
    $res = $Comment->getSeparationTotalAmount($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate);
    $out = "true";
    response($res,$out);
    exit;
}

function showSeparationSubTotalAmount(){
    $acm = new acm();
    if(!$acm->hasAccess('reportPayCommentManage')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $ut = new Utility();
    list($fid,$unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate) = $ut->varCleanInput(array('fid','unCode','rcsDate','rceDate','rcaName','rcToward','rcAmount','rcPaytype','rcPaySend','rpcsDate','rpceDate','rpnsDate','rpneDate'));
    $rcAmount = str_replace(',','',$rcAmount);
    $res = $Comment->getSeparationSubTotalAmount($fid,$unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate);
    $out = "true";
    response($res,$out);
    exit;
}

function showSeparationSubgroupTotalAmount(){
    $acm = new acm();
    if(!$acm->hasAccess('reportPayCommentManage')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $ut = new Utility();
    list($fid,$unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate) = $ut->varCleanInput(array('fid','unCode','rcsDate','rceDate','rcaName','rcToward','rcAmount','rcPaytype','rcPaySend','rpcsDate','rpceDate','rpnsDate','rpneDate'));
    $rcAmount = str_replace(',','',$rcAmount);
    $res = $Comment->getSeparationSubgroupTotalAmount($fid,$unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowCommentCheck(){
    $acm = new acm();
    if(!$acm->hasAccess('reportPayCommentManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->commentChecksHTM($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function showCommentAccountInfo(){
    $acm = new acm();
    if(!$acm->hasAccess('reportPayCommentManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->OtherAccountInfoCommentHTM($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function downloadPaymentReceiptReport(){
    $ut = new Utility();
    list($did) = $ut->varCleanInput(array('did'));
    if(!intval($did)){
        $res = "شناسه واریزی بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->downloadPaymentReceiptHtm($did);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowAttachmentFileRptComment(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->attachmentFileCommentHtm($pid,0);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowWorkflowRptComment(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->commentWorkflowRptHtm($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function showAttachedFundToComment2(){
    $Comment = new Comment();
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    $res = $Comment->getAttachFundToCommentReportList($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function getFundListDetailsReport(){
    $ut = new Utility();
    list($fid) = $ut->varCleanInput(array('fid'));
    if(!intval($fid)){
        $res = "شناسه تنخواه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->reportFundListDetailsHTM($fid);
    $out = "true";
    response($res,$out);
    exit;
}

function getFundListAttachReport(){
    $ut = new Utility();
    list($fdid) = $ut->varCleanInput(array('fdid'));
    if(!intval($fdid)){
        $res = "شناسه جزئیات تنخواه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->fundListAttachShowHTM($fdid);
    $out = "true";
    response($res,$out);
    exit;
}

function doPrintReportPayComment(){
    $acm = new acm();
    if(!$acm->hasAccess('reportPayCommentManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($cid) = $ut->varCleanInput(array('cid'));
    $htm = $Comment->getPrintReportPayCommentHtm($cid);
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function downloadCheckCarcassFileRpt(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->downloadCheckCarcassFileRPTHtm($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function depositsListInfoExcel(){
    $Comment = new Comment();
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    $res = $Comment->getDepositsListInfoExcel($pid);
    $hdarray = array('تاریخ واریز/چک','ثبت کننده','طرف مقابل','واریز کننده/شماره چک','نام بانک','مبلغ واریزی/چک','توضیحات');
    $fieldNames = array('dDate','fname','accName','depositor','dBank','dAmount','dDesc');
    $name = "depositsListInfo".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ گزارش اظهارنظرهای حذف شده +++++++++++++++++++++++

function deletedPayCommentReport(){
    $acm = new acm();
    if(!$acm->hasAccess('deletedPayCommentReport')){
        die("access denied");
        exit;
    }
    $comment = new Comment();
    $htm = $comment->getDeletedPayCommentReportHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showDeletedPayCommentReportList(){
    $acm = new acm();
    if(!$acm->hasAccess('deletedPayCommentReport')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($page,$csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount) = $ut->varCleanInput(array('page','csDate','ceDate','cUnit','coUnit','caName','cToward','Uncode','amount'));
    $amount = str_replace(',','',$amount);
    $res = $Comment->getDeletedPayCommentReportList($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount,$page);
    if($page == 1){
        $_SESSION['calcDeletedCommManage'] = $Comment->getDeletedPayCommentReportListCountRows($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount);
    }
    $totalRows = $_SESSION['calcDeletedCommManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="روش پرداخت";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="typeComment";
    $c++;

    $feilds[$c]['title']="در وجه";
    $feilds[$c]['width']= "9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="accName";
    $c++;

    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="cDate";
    $c++;

    $feilds[$c]['title']="واحد درخواست کننده";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Unit";
    $c++;

    $feilds[$c]['title']="واحد مصرف کننده";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="consumerUnit";
    $c++;

    $feilds[$c]['title']="بابت";
    $feilds[$c]['width']= "9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Toward";
    $c++;

    $feilds[$c]['title']="به مبلغ";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Amount";
    $c++;

    $feilds[$c]['title']="لیست تنخواه";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showAttachedFundToDeletedComment";
    $feilds[$c]['icon']="fa-list";
    $c++;

    $feilds[$c]['title']="سایر اطلاعات";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowOtherInfoDeletedComment";
    $feilds[$c]['icon']="fa-tv";
    $c++;

    $feilds[$c]['title']="فایل پیوست";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowAttachmentFileDeletedComment";
    $feilds[$c]['icon']="fa-file";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowWorkflowDeletedComment";
    $feilds[$c]['icon']="fa-sitemap";
    $c++;

    $feilds[$c]['title']="پرینت";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="printDeletedComment";
    $feilds[$c]['icon']="fa-print";

    $pagerType = 1;
    $listTitle = " تعداد اظهار نظرها : ".$totalRows." عدد ";
    $tableID = "deletedPayCommentReportBody-table";
    $jsf = "showDeletedPayCommentReportList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function ShowOtherInfoDeletedComment(){
    $acm = new acm();
    if(!$acm->hasAccess('deletedPayCommentReport')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->otherInfoDeletedCommentHTM($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowAttachmentFileDeletedComment(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->attachmentFileDeletedCommentHtm($pid,1);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowWorkflowDeletedComment(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->deletedCommentWorkflowHtm($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function doPrintDeletedComment(){
    $acm = new acm();
    if(!$acm->hasAccess('deletedPayCommentReport')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($cid) = $ut->varCleanInput(array('cid'));
    $htm = $Comment->getPrintDeletedCommentHtm($cid);
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function downloadCheckCarcassFileDeletedComment(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->downloadCheckCarcassFileDelHtm($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function showAttachedFundToDeletedComment(){
    $Comment = new Comment();
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    $res = $Comment->getAttachFundToDeletedCommentList($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function getFundListDetailsDeletedComment(){
    $ut = new Utility();
    list($fid) = $ut->varCleanInput(array('fid'));
    if(!intval($fid)){
        $res = "شناسه تنخواه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->showFundListDetailsDeletedCommentHTM($fid);
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ اظهارنظرهای دارای مازاد پرداختی +++++++++++++++++++++++

function overpaymentComments(){
    $acm = new acm();
    if(!$acm->hasAccess('overpaymentComments')){
        die("access denied");
        exit;
    }
    $comment = new Comment();
    $htm = $comment->getOverpaymentCommentsHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showOverpaymentCommentsList(){
    $acm = new acm();
    if(!$acm->hasAccess('overpaymentComments')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($page,$csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount) = $ut->varCleanInput(array('page','csDate','ceDate','cUnit','coUnit','caName','cToward','Uncode','amount'));
    $amount = str_replace(',','',$amount);
    $res = $Comment->getOverpaymentCommentsList($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount,$page);
    if($page == 1){
        $_SESSION['calcOverCommManage'] = $Comment->getOverpaymentCommentsListCountRows($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount);
    }
    $totalRows = $_SESSION['calcOverCommManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= "4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="روش پرداخت";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="typeComment";
    $c++;

    $feilds[$c]['title']="در وجه";
    $feilds[$c]['width']= "9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="accName";
    $c++;

    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="cDate";
    $c++;

    $feilds[$c]['title']="واحد درخواست کننده";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Unit";
    $c++;

    $feilds[$c]['title']="واحد مصرف کننده";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="consumerUnit";
    $c++;

    $feilds[$c]['title']="بابت";
    $feilds[$c]['width']= "17%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Toward";
    $c++;

    $feilds[$c]['title']="به مبلغ";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Amount";
    $c++;

    $feilds[$c]['title']="سایر اطلاعات";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowOtherInfoOverpaymentComments";
    $feilds[$c]['icon']="fa-tv";
    $c++;

    $feilds[$c]['title']="واریزی ها";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showDepositsOverpaymentList";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="چک ها";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowOverpaymentCommentCheck";
    $feilds[$c]['icon']="fa-money-check-alt";

    $pagerType = 1;
    $listTitle = " تعداد اظهار نظرها : ".$totalRows." عدد ";
    $tableID = "overpaymentCommentsBody-table";
    $jsf = "showOverpaymentCommentsList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function ShowOtherInfoOverpaymentComments(){
    $acm = new acm();
    if(!$acm->hasAccess('overpaymentComments')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->otherInfoOverpaymentCommentsHTM($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function downloadCheckCarcassFileOverpayment(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->downloadCheckCarcassFileDelHtm($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowOverpaymentCommentCheck(){
    $acm = new acm();
    if(!$acm->hasAccess('overpaymentComments')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->commentOverpaymentChecksHTM($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function doReturnMoneyComment(){
    $acm = new acm();
    if(!$acm->hasAccess('overpaymentComments')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($rtid,$type,$accName,$bank,$amount) = $ut->varCleanInput(array('rtid','type','accName','bank','amount'));
    $amount = str_replace(',','',$amount);
    $res = $Comment->createReturnMoneyComment($rtid,$type,$accName,$bank,$amount);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doFractionMoneyComment(){
    $acm = new acm();
    if(!$acm->hasAccess('overpaymentComments')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    list($frid,$code,$amount) = $ut->varCleanInput(array('frid','code','amount'));
    $amount = str_replace(',','',$amount);
    $res = $Comment->createFractionMoneyComment($frid,$code,$amount);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ تاییدیه مالی +++++++++++++++++++++++

function showFinancialConfirmationList(){
    $acm = new acm();
    if(!$acm->hasAccess('financialConfirmation')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($fcsDate,$fceDate,$fcaName,$fcToward,$fcAmount,$page) = $ut->varCleanInput(array('fcsDate','fceDate','fcaName','fcToward','fcAmount','page'));
    $res = $Comment->getFinancialConfirmationList($fcsDate,$fceDate,$fcaName,$fcToward,$fcAmount,$page);
    if($page == 1){
        $_SESSION['calcFCommManage'] = $Comment->getFinancialConfirmationListCountRows($fcsDate,$fceDate,$fcaName,$fcToward,$fcAmount);
    }
    $totalRows = $_SESSION['calcFCommManage'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="نقدی4";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="CashSection";
    $c++;

    $feilds[$c]['title']="چک";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="NonCashSection";
    $c++;

    $feilds[$c]['title']="مانده حساب";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['color']="yes";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="leftOver";
    $c++;

    $feilds[$c]['title']="در وجه";
    $feilds[$c]['width']= "11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="بابت";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Toward";
    $c++;

    $feilds[$c]['title']="واریزی ها";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showDepositsInFinancialList";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="چک ها";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowCommentCheckInFinancial";
    $feilds[$c]['icon']="fa-money-check-alt";
    $c++;

    $feilds[$c]['title']="سایر اطلاعات";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowOtherInfoPayCommentInFinancial";
    $feilds[$c]['icon']="fa-tv";
    $c++;

    $feilds[$c]['title']="پیوست";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowAttachmentFileComment";
    $feilds[$c]['icon']="fa-file";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowWorkflowComment";
    $feilds[$c]['icon']="fa-sitemap";
    $c++;

    $feilds[$c]['title']="نمایش";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="printPayComment";
    $feilds[$c]['icon']="fa-search";
    $c++;

    $feilds[$c]['title']="تایید نهایی";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="payCommentFinancialApproval";
    $feilds[$c]['icon']="fa-paper-plane";

    $pagerType = 1;
    $listTitle = " تعداد اظهار نظرها : ".$totalRows." عدد ";
    $tableID = "financialConfirmationBody-table";
    $jsf = "showFinancialConfirmationList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function ShowCommentCheckInFinancial(){
    $acm = new acm();
    if(!$acm->hasAccess('financialConfirmation')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->commentChecksHTM($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowOtherInfoPayCommentInFinancial(){
    $ut = new Utility();
    list($did) = $ut->varCleanInput(array('did'));
    if(!intval($did)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->OtherAccountInfoCommentHTM($did);
    $out = "true";
    response($res,$out);
    exit;
}

function downloadCheckCarcassFileInFinancial(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->downloadCheckCarcassFileRPTHtm($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function doFinancialApprovalComment(){
    $acm = new acm();
    if(!$acm->hasAccess('financialConfirmation')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    $Comment = new Comment();
    $res = $Comment->financialApprovalComment($pid);
    if($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getBankCheckExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('financialConfirmation') || !$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $Comment = new Comment();
    $res = $Comment->getBankCheckExcel();
    $hdarray = array('شماره چک','تاریخ چک','مبلغ چک','نوع چک','توضیحات');
    $fieldNames = array('check_number','check_date','check_amount','checkType','description');
    $name = "bankCheckList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ کشو موقت مالی +++++++++++++++++++++++

function showTempFinancialKeshoList(){
    $acm = new acm();
    if(!$acm->hasAccess('tempFinancialKesho')){
        die("access denied");
        exit;
    }
    $Comment = new Comment();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $Comment->getTempFinancialKeshoList($page);
    if($page == 1){
        $_SESSION['calcTFCommManage'] = $Comment->getTempFinancialKeshoListCountRows();
    }
    $totalRows = $_SESSION['calcTFCommManage'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="5نقدی";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="CashSection";
    $c++;

    $feilds[$c]['title']="چک";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="NonCashSection";
    $c++;

    $feilds[$c]['title']="مانده حساب";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['color']="yes";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="leftOver";
    $c++;

    $feilds[$c]['title']="در وجه";
    $feilds[$c]['width']= "11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="بابت";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['blink']="yes";
    $feilds[$c]['f']="Toward";
    $c++;

    $feilds[$c]['title']="واریزی ها";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showDepositsInTempFinancialList";
    $feilds[$c]['icon']="fa-dollar-sign";
    $c++;

    $feilds[$c]['title']="چک ها";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowCommentCheckInFinancial";
    $feilds[$c]['icon']="fa-money-check-alt";
    $c++;

    $feilds[$c]['title']="سایر اطلاعات";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowOtherInfoPayCommentInFinancial";
    $feilds[$c]['icon']="fa-tv";
    $c++;

    $feilds[$c]['title']="پیوست";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowAttachmentFileComment";
    $feilds[$c]['icon']="fa-file";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="ShowWorkflowComment";
    $feilds[$c]['icon']="fa-sitemap";
    $c++;

    $feilds[$c]['title']="نمایش";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="printPayComment";
    $feilds[$c]['icon']="fa-search";
    $c++;

    $feilds[$c]['title']="تایید نهایی";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="payCommentTempFinancialApproval";
    $feilds[$c]['icon']="fa-paper-plane";

    $pagerType = 1;
    $listTitle = " تعداد اظهار نظرها : ".$totalRows." عدد ";
    $tableID = "tempFinancialKeshoBody-table";
    $jsf = "showTempFinancialKeshoList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function confirmedDepositVsMali(){
    $acm = new acm();
    if(!$acm->hasAccess('tempFinancialKesho')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($did) = $ut->varCleanInput(array('did'));
    $Comment = new Comment();
    $res = $Comment->confirmedDepositVsMali($did);
    if($res != false){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doTempFinancialApprovalComment(){
    $acm = new acm();
    if(!$acm->hasAccess('tempFinancialKesho')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    $Comment = new Comment();
    $res = $Comment->tempFinancialApprovalComment($pid);
    if (intval($res) == -1){
        $res = "ابتدا همه واریزی ها را تایید نمایید !!!";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "جمع مبالغ پرداختی از مبلغ کل اظهارنظر کمتر می باشد !!!";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ مستندات سازمانی +++++++++++++++++++++++

function organizationalDocumentationManage(){
    $acm = new acm();
    if(!$acm->hasAccess('organizationalDocumentationManage')){
        die("access denied");
        exit;
    }
    $documents = new Documents();
    $htm = $documents->getOrganizationalDocumentationManageHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ آئین نامه ها و دستورالعمل ها +++++++++++++++++++++++

function showRegulationsManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('regulationsManage')){
        die("access denied");
        exit;
    }
    $documents = new Documents();
    $list = new Listview();
    $ut = new Utility();
    list($Name,$Code,$SDate,$EDate,$status,$page) = $ut->varCleanInput(array('Name','Code','SDate','EDate','status','page'));
    $res = $documents->getRegulationsManageList($Name,$Code,$SDate,$EDate,$status,$page);
    if($page == 1){
        $_SESSION['calcRegulationsManage'] = $documents->getRegulationsManageListCountRows($Name,$Code,$SDate,$EDate,$status);
    }
    $totalRows = $_SESSION['calcRegulationsManage'];
    if($acm->hasAccess('createNewRegulations') || $acm->hasAccess('attachFileToRegulations')) {
        $pt = array('checkBox'=>4,'startDate'=>7,'endDate'=>7,'Name'=>22,'Code'=>10,'users'=>25,'description'=>20,'download'=>5);
    }else{
        $pt = array('checkBox'=>4,'startDate'=>10,'endDate'=>10,'Name'=>30,'Code'=>15,'description'=>26,'download'=>5);
    }

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= $pt['checkBox']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="تاریخ شروع";
    $feilds[$c]['width']= $pt['startDate']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="startDate";
    $c++;

    $feilds[$c]['title']="تاریخ پایان";
    $feilds[$c]['width']= $pt['endDate']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="endDate";
    $c++;

    $feilds[$c]['title']="نام فایل";
    $feilds[$c]['width']= $pt['Name']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="کد فایل";
    $feilds[$c]['width']= $pt['Code']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Code";
    $c++;

    if($acm->hasAccess('createNewRegulations') || $acm->hasAccess('attachFileToRegulations')) {
        $feilds[$c]['title'] = "افراد مجاز جهت دانلود فایل";
        $feilds[$c]['width']= $pt['users']."%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "users";
        $c++;
    }

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= $pt['description']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="ضمیمه";
    $feilds[$c]['width']= $pt['download']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="downloadRegulationsFile";
    $feilds[$c]['icon']="fa-link";

    $pagerType = 1;
    $listTitle = " تعداد آئین نامه ها و دستورالعمل ها : ".$totalRows." عدد ";
    $tableID = "regulationsManageBody-table";
    $jsf = "showRegulationsManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getRegulationsInfo(){
    $uti = new Utility();
    list($rid) = $uti->varCleanInput(array('rid'));
    if(!intval($rid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $documents = new Documents();
    $res = $documents->regulationsInfo($rid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateRegulations(){
    $acm = new acm();
    if(!$acm->hasAccess('regulationsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $documents = new Documents();
    list($rid,$fname,$fcode,$SDate,$EDate,$desc,$accID) = $uti->varCleanInput(array('rid','fname','fcode','SDate','EDate','desc','accID'));
    if(intval($rid) > 0){  //edit
        $res = $documents->editRegulations($rid,$fname,$fcode,$SDate,$EDate,$desc,$accID);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $documents->createRegulations($fname,$fcode,$SDate,$EDate,$desc,$accID);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function dodeleteRegulations(){
    $acm = new acm();
    if(!$acm->hasAccess('regulationsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $documents = new Documents();
    list($rid) = $ut->varCleanInput(array('rid'));
    if(!intval($rid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $documents->deleteRegulations($rid);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getAttachedRegulationsFile(){
    $ut = new Utility();
    list($rid) = $ut->varCleanInput(array('rid'));
    if(!intval($rid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $documents = new Documents();
    $res = $documents->attachedRegulationsFileHtm($rid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachFileToRegulations(){
    $uti = new Utility();
    $documents = new Documents();
    list($rid,$info) = $uti->varCleanInput(array('rid','info'));
    $files = $_FILES['files'];
    $res = $documents->attachFileToRegulations($rid,$info,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteAttachRegulationsFile(){
    $uti = new Utility();
    $documents = new Documents();
    list($fid) = $uti->varCleanInput(array('fid'));
    $res = $documents->deleteAttachRegulationsFile($fid);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function downloadRegulationsFile(){
    $ut = new Utility();
    list($rid) = $ut->varCleanInput(array('rid'));
    if(!intval($rid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $documents = new Documents();
    $res = $documents->attachmentFileRegulationsHtm($rid);
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ بخشنامه ها +++++++++++++++++++++++

function showCircularsManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('circularsManage')){
        die("access denied");
        exit;
    }
    $documents = new Documents();
    $list = new Listview();
    $ut = new Utility();
    list($Name,$Code,$SDate,$EDate,$status,$type,$page) = $ut->varCleanInput(array('Name','Code','SDate','EDate','status','type','page'));
    $res = $documents->getCircularsManageList($Name,$Code,$SDate,$EDate,$status,$type,$page);
    if($page == 1){
        $_SESSION['calcCircularsManage'] = $documents->getCircularsManageListCountRows($Name,$Code,$SDate,$EDate,$status,$type);
    }
    $totalRows = $_SESSION['calcCircularsManage'];
    if($acm->hasAccess('editCreateNewCirculars') || $acm->hasAccess('attachFileToCirculars')) {
        $pt = array('checkBox'=>4,'type'=>7,'startDate'=>7,'endDate'=>7,'Name'=>20,'Code'=>10,'users'=>25,'description'=>15,'download'=>5);
    }else{
        $pt = array('checkBox'=>4,'type'=>7,'startDate'=>10,'endDate'=>10,'Name'=>28,'Code'=>15,'description'=>21,'download'=>5);
    }

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= $pt['checkBox']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نوع فایل";
    $feilds[$c]['width']= $pt['type']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="type";
    $c++;

    $feilds[$c]['title']="تاریخ شروع";
    $feilds[$c]['width']= $pt['startDate']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="startDate";
    $c++;

    $feilds[$c]['title']="تاریخ پایان";
    $feilds[$c]['width']= $pt['endDate']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="endDate";
    $c++;

    $feilds[$c]['title']="نام فایل";
    $feilds[$c]['width']= $pt['Name']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="کد فایل";
    $feilds[$c]['width']= $pt['Code']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Code";
    $c++;

    if($acm->hasAccess('editCreateNewCirculars') || $acm->hasAccess('attachFileToCirculars')) {
        $feilds[$c]['title'] = "افراد مجاز جهت دانلود فایل";
        $feilds[$c]['width']= $pt['users']."%";
        $feilds[$c]['order'] = "none";
        $feilds[$c]['f'] = "users";
        $c++;
    }

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= $pt['description']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="ضمیمه";
    $feilds[$c]['width']= $pt['download']."%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="downloadCircularsFile";
    $feilds[$c]['icon']="fa-link";

    $pagerType = 1;
    $listTitle = " تعداد بخشنامه ها : ".$totalRows." عدد ";
    $tableID = "circularsManageBody-table";
    $jsf = "showCircularsManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getCircularsInfo(){
    $uti = new Utility();
    list($cid) = $uti->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $documents = new Documents();
    $res = $documents->circularsInfo($cid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateCirculars(){
    $acm = new acm();
    if(!$acm->hasAccess('circularsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $documents = new Documents();
    list($cid,$fname,$fcode,$SDate,$EDate,$desc,$accID,$type) = $uti->varCleanInput(array('cid','fname','fcode','SDate','EDate','desc','accID','type'));
    if(intval($cid) > 0){  //edit
        $res = $documents->editCirculars($cid,$fname,$fcode,$SDate,$EDate,$desc,$accID,$type);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $documents->createCirculars($fname,$fcode,$SDate,$EDate,$desc,$accID,$type);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function dodeleteCirculars(){
    $acm = new acm();
    if(!$acm->hasAccess('circularsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $documents = new Documents();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $documents->deleteCirculars($cid);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getAttachedCircularsFile(){
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $documents = new Documents();
    $res = $documents->attachedCircularsFileHtm($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachFileToCirculars(){
    $uti = new Utility();
    $documents = new Documents();
    list($cid,$info) = $uti->varCleanInput(array('cid','info'));
    $files = $_FILES['files'];
    $res = $documents->attachFileToCirculars($cid,$info,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteAttachCircularsFile(){
    $uti = new Utility();
    $documents = new Documents();
    list($fid) = $uti->varCleanInput(array('fid'));
    $res = $documents->deleteAttachCircularsFile($fid);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function downloadCircularsFile(){
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $documents = new Documents();
    $res = $documents->attachmentFileCircularsHtm($cid);
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ قراردادهای حقوقی +++++++++++++++++++++++

function showLegalContractsManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('legalContractsManage')){
        die("access denied");
        exit;
    }
    $documents = new Documents();
    $list = new Listview();
    $ut = new Utility();
    list($Subject,$CID,$SDate,$EDate,$type,$status,$page) = $ut->varCleanInput(array('Subject','CID','SDate','EDate','type','status','page'));
    $res = $documents->getLegalContractsManageList($Subject,$CID,$SDate,$EDate,$type,$status,$page);
    if($page == 1){
        $_SESSION['calcLegalManage'] = $documents->getLegalContractsManageListCountRows($Subject,$CID,$SDate,$EDate,$type,$status);
    }
    $totalRows = $_SESSION['calcLegalManage'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= "4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="واحد";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Uname";
    $c++;

    $feilds[$c]['title']="شماره قرارداد";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="ContractID";
    $c++;

    $feilds[$c]['title']="موضوع قرارداد";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="subjectContract";
    $c++;

    $feilds[$c]['title']="طرف اول";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="sideOne";
    $c++;

    $feilds[$c]['title']="طرف دوم";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="sideTwo";
    $c++;

    $feilds[$c]['title']="تاریخ شروع";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="BeginDateContract";
    $c++;

    $feilds[$c]['title']="تاریخ پایان";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="EndDateContract";
    $c++;

    $feilds[$c]['title']="تلفن ثابت";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="phone";
    $c++;

    $feilds[$c]['title']="تلفن همراه";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="mobile";
    $c++;

    $feilds[$c]['title']="مدت قرارداد";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Term_contract";
    $c++;

    $feilds[$c]['title']="مبلغ قرارداد";
    $feilds[$c]['width']= "11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Amount";
    $c++;

    $feilds[$c]['title']="تضامین";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="ضمیمه";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="downloadLegalContractsFile";
    $feilds[$c]['icon']="fa-link";

    $pagerType = 1;
    $listTitle = " تعداد قراردادهای حقوقی : ".$totalRows." عدد ";
    $tableID = "legalContractsManageBody-table";
    $jsf = "showLegalContractsManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getLegalContractInfo(){
    $uti = new Utility();
    list($lcid) = $uti->varCleanInput(array('lcid'));
    if(!intval($lcid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $documents = new Documents();
    $res = $documents->legalContractInfo($lcid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateLegalContract(){
    $acm = new acm();
    if(!$acm->hasAccess('legalContractsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $documents = new Documents();
    list($lcid,$cid,$subject,$sideOne,$sideTwo,$codeTafzili,$Sdate,$Edate,$Phone,$Mobile,$amount,$desc,$type,$unit) = $uti->varCleanInput(array('lcid','cid','subject','sideOne','sideTwo','codeTafzili','Sdate','Edate','Phone','Mobile','amount','desc','type','unit'));
    $amount = str_replace(',','',$amount);
    if(intval($lcid) > 0){  //edit
        $res = $documents->editLegalContract($lcid,$cid,$subject,$sideOne,$sideTwo,$codeTafzili,$Sdate,$Edate,$Phone,$Mobile,$amount,$desc,$type,$unit);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $documents->createLegalContract($cid,$subject,$sideOne,$sideTwo,$codeTafzili,$Sdate,$Edate,$Phone,$Mobile,$amount,$desc,$type,$unit);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function dodeleteLegalContract(){
    $acm = new acm();
    if(!$acm->hasAccess('legalContractsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $documents = new Documents();
    list($lcid) = $ut->varCleanInput(array('lcid'));
    if(!intval($lcid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $documents->deleteLegalContract($lcid);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getAttachedLegalContractFile(){
    $ut = new Utility();
    list($lcid) = $ut->varCleanInput(array('lcid'));
    if(!intval($lcid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $documents = new Documents();
    $res = $documents->attachedLegalContractFileHtm($lcid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachFileToLegalContract(){
    $uti = new Utility();
    $documents = new Documents();
    list($lcid,$info) = $uti->varCleanInput(array('lcid','info'));
    $files = $_FILES['files'];
    $res = $documents->attachFileToLegalContract($lcid,$info,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteAttachLegalContractFile(){
    $uti = new Utility();
    $documents = new Documents();
    list($fid) = $uti->varCleanInput(array('fid'));
    $res = $documents->deleteAttachLegalContractFile($fid);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function downloadLegalContractsFile(){
    $ut = new Utility();
    list($lcid) = $ut->varCleanInput(array('lcid'));
    if(!intval($lcid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $documents = new Documents();
    $res = $documents->attachmentFileLegalContractsHtm($lcid);
    $out = "true";
    response($res,$out);
    exit;
}

function getSideTwoCodeTafzili(){
    $ut = new Utility();
    list($cfor) = $ut->varCleanInput(array('cfor'));
    $documents = new Documents();
    $res = $documents->getSideTwoCodeTafzili($cfor);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = array();
        $out = "true";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ شناسنامه ماشین ها +++++++++++++++++++++++

function carInformationManage(){
    $acm = new acm();
    if(!$acm->hasAccess('carInformationManage')){
        die("access denied");
        exit;
    }
    $car = new Car();
    $htm = $car->getCarInformationManageHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showCarInformationManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('carInformationManage')){
        die("access denied");
        exit;
    }
    $car = new Car();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $car->getCarInformationManageList($page);
    if($page == 1){
        $_SESSION['calcCarInformation'] = $car->getCarInformationManageListCountRows();
    }
    $totalRows = $_SESSION['calcCarInformation'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= "4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام ماشین";
    $feilds[$c]['width']= "17%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="carName";
    $c++;

    $feilds[$c]['title']="نوع کاربری";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="carType";
    $c++;

    $feilds[$c]['title']="شماره پلاک";
    $feilds[$c]['width']= "14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="plaque";
    $c++;

    $feilds[$c]['title']="نوع سوخت";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="fuelType";
    $c++;

    $feilds[$c]['title']="تاریخ اتمام معاینه فنی";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="technicalDiagDate";
    $c++;

    $feilds[$c]['title']="تاریخ اتمام بیمه شخص ثالث";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="insuranceDate";
    $c++;

    $feilds[$c]['title']="تاریخ اتمام بیمه بدنه";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="insuranceBodyDate";
    $c++;

    $feilds[$c]['title']="تجهیزات مازاد";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showExtraEquipment";
    $feilds[$c]['icon']="fa-tools";
    $c++;

    $feilds[$c]['title']="مواد مصرفی";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showConsumingMaterials";
    $feilds[$c]['icon']="fa-oil-can";
    $c++;

    $feilds[$c]['title']="ورود خروج";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showEnterExitThisCar";
    $feilds[$c]['icon']="fa-sign-out-alt";
    $c++;

    $feilds[$c]['title']="اظهارنظرهای مربوطه";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="carLayer";
    $feilds[$c]['onclick']="showPayCommentForThisCar";
    $feilds[$c]['icon']="fa-file";
    $c++;

    $feilds[$c]['title']="ضمیمه";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showAttachedFileToCar";
    $feilds[$c]['icon']="fa-link";

    $pagerType = 1;
    $listTitle = " تعداد ماشین ها : ".$totalRows." عدد ";
    $tableID = "carInformationManageBody-table";
    $jsf = "showCarInformationManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getCarInfo(){
    $uti = new Utility();
    list($caid) = $uti->varCleanInput(array('caid'));
    if(!intval($caid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->carInfo($caid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateCar(){
    $acm = new acm();
    if(!$acm->hasAccess('carInformationManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $car = new Car();
    list($caid,$name,$plaque,$chassis,$serial,$fuelType,$TDDate,$TIDate,$BIDate,$carType,$lastKilometer) = $ut->varCleanInput(array('caid','name','plaque','chassis','serial','fuelType','TDDate','TIDate','BIDate','carType','lastKilometer'));
    $files = $_FILES['files'];  // معاینه فنی
    $files1 = $_FILES['files1'];  // بیمه شخص ثالث
    $files2 = $_FILES['files2'];  // بیمه بدنه
    $files3 = $_FILES['files3'];  // سند ماشین
    $files4 = $_FILES['files4'];  // برگ سبز
    $files5 = $_FILES['files5'];  // آخرین وضعیت
    if(intval($caid) > 0) {  //edit
        $res = $car->editCar($caid,$name,$plaque,$chassis,$serial,$fuelType,$TDDate,$TIDate,$BIDate,$carType,$files,$files1,$files2,$files3,$files4,$files5);
        if (intval($res) == -1) {
            $res = "فایل ها مشکل دارند !";
            $out = "false";
            response($res, $out);
            exit;
        } elseif (intval($res) == -2) {
            $res = "سایز فایل ها مشکل دارند !";
            $out = "false";
            response($res, $out);
            exit;
        } elseif (intval($res) == -3) {
            $res = "پسوند فایل ها مشکل دارند !";
            $out = "false";
            response($res, $out);
            exit;
        } elseif ($res == true) {
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res, $out);
            exit;
        } else {
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res, $out);
            exit;
        }
    }else{  // Create
        $res = $car->createCar($name,$plaque,$chassis,$serial,$fuelType,$TDDate,$TIDate,$BIDate,$carType,$lastKilometer,$files,$files1,$files2,$files3,$files4,$files5);
        if (intval($res) == -1) {
            $res = "فایل ها مشکل دارند !";
            $out = "false";
            response($res, $out);
            exit;
        } elseif (intval($res) == -2) {
            $res = "سایز فایل ها مشکل دارند !";
            $out = "false";
            response($res, $out);
            exit;
        } elseif (intval($res) == -3) {
            $res = "پسوند فایل ها مشکل دارند !";
            $out = "false";
            response($res, $out);
            exit;
        } elseif ($res == true) {
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res, $out);
            exit;
        } else {
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res, $out);
            exit;
        }
    }
}

function showAttachedFileToCar(){
    $uti = new Utility();
    $car = new Car();
    list($caid) = $uti->varCleanInput(array('caid'));
    $res = $car->getAttachedFileToCar($caid);
    $out = "true";
    response($res,$out);
    exit;
}

function downloadTechnicalDiagFile(){
    $ut = new Utility();
    list($caid) = $ut->varCleanInput(array('caid'));
    if(!intval($caid)){
        $res = "شناسه ماشین بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->downloadTechnicalDiagHtm($caid);
    $out = "true";
    response($res,$out);
    exit;
}

function downloadThirdInsuranceFile(){
    $ut = new Utility();
    list($caid) = $ut->varCleanInput(array('caid'));
    if(!intval($caid)){
        $res = "شناسه ماشین بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->downloadThirdInsuranceHtm($caid);
    $out = "true";
    response($res,$out);
    exit;
}

function downloadBodyInsuranceFile(){
    $ut = new Utility();
    list($caid) = $ut->varCleanInput(array('caid'));
    if(!intval($caid)){
        $res = "شناسه ماشین بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->downloadBodyInsuranceHtm($caid);
    $out = "true";
    response($res,$out);
    exit;
}

function downloadCarDocumentFile(){
    $ut = new Utility();
    list($caid) = $ut->varCleanInput(array('caid'));
    if(!intval($caid)){
        $res = "شناسه ماشین بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->downloadCarDocumentHtm($caid);
    $out = "true";
    response($res,$out);
    exit;
}

function downloadGreenPageFile(){
    $ut = new Utility();
    list($caid) = $ut->varCleanInput(array('caid'));
    if(!intval($caid)){
        $res = "شناسه ماشین بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->downloadGreenPageHtm($caid);
    $out = "true";
    response($res,$out);
    exit;
}

function downloadLastStatusFile(){
    $ut = new Utility();
    list($caid) = $ut->varCleanInput(array('caid'));
    if(!intval($caid)){
        $res = "شناسه ماشین بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->downloadLastStatusHtm($caid);
    $out = "true";
    response($res,$out);
    exit;
}

function showPayCommentForThisCar(){
    $uti = new Utility();
    $car = new Car();
    list($carLayer,$layer) = $uti->varCleanInput(array('carLayer','layer'));
    $res = $car->getCarPayComments($carLayer,$layer);
    $out = "true";
    response($res,$out);
    exit;
}

function getCarThreeLayers(){
    $car = new Car();
    $ut = new Utility();
    list($carLayer) = $ut->varCleanInput(array('carLayer'));
    $res = $car->getCarThreeLayers($carLayer);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowCommentCheckInCar(){
    $acm = new acm();
    if(!$acm->hasAccess('carInformationManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->commentChecksHTM($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowOtherInfoCarComment(){
    $acm = new acm();
    if(!$acm->hasAccess('carInformationManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->OtherInfoCommentHTM($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function downloadCheckCarcassCarFile(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->downloadCheckCarcassFileHtm($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowAttachmentFileCarComment(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->attachmentFileCommentHtm($pid,1);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowWorkflowCarComment(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه اظهارنظر بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $Comment = new Comment();
    $res = $Comment->commentWorkflowHtm($pid);
    if($res==11){
        $res="اظهارنظر به صورت مستقیم به  کارشناس مربوطه ارجاع گردید";
        $out = "true";
        response($res,$out);
    }
    $out = "true";
    response($res,$out);
    exit;
}

function doPrintCarPayComment(){
    $acm = new acm();
    if(!$acm->hasAccess('carInformationManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $car = new Car();
    list($cid) = $ut->varCleanInput(array('cid'));
    $htm = $car->getPrintPayCommentHtm($cid);
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showAttachedFundToCarComment(){
    $car = new Car();
    $ut = new Utility();
    list($cid,$carLayer) = $ut->varCleanInput(array('cid','carLayer'));
    $res = $car->getAttachFundToCommentList($cid,$carLayer);
    $out = "true";
    response($res,$out);
    exit;
}

function getCarFundListDetailsShow(){
    $ut = new Utility();
    list($fid) = $ut->varCleanInput(array('fid'));
    if(!intval($fid)){
        $res = "شناسه تنخواه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->showFundListDetailsHTM($fid);
    $out = "true";
    response($res,$out);
    exit;
}

function doEnterExitCar(){
    $acm = new acm();
    if(!$acm->hasAccess('recordEnterExitCar')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $car = new Car();
    list($CarType,$EorE,$cTime,$km,$dName) = $uti->varCleanInput(array('CarType','EorE','cTime','km','dName'));
    $res = $car->enterExitCar($CarType,$EorE,$cTime,$km,$dName);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteEnterExitCar(){
    $acm = new acm();
    if(!$acm->hasAccess('recordEnterExitCar')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $car = new Car();
    list($eeID) = $uti->varCleanInput(array('eeID'));
    $res = $car->deleteEnterExit($eeID);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getEnterExitCarInfo(){
    $uti = new Utility();
    list($eeID) = $uti->varCleanInput(array('eeID'));
    if(!intval($eeID)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->enterExitCarInfo($eeID);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditEnterExitCar(){
    $acm = new acm();
    if(!$acm->hasAccess('recordEnterExitCar')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $car = new Car();
    list($eeID,$EorE,$cTime,$km,$dName) = $uti->varCleanInput(array('eeID','EorE','cTime','km','dName'));
    $res = $car->editEnterExitCar($eeID,$EorE,$cTime,$km,$dName);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getEnterExitCarList(){
    $ut = new Utility();
    list($caid,$sDate,$eDate,$dName,$eeType) = $ut->varCleanInput(array('caid','sDate','eDate','dName','eeType'));
    if(!intval($caid)){
        $res = "شناسه ماشین بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->getEnterExitCarHTM($caid,$sDate,$eDate,$dName,$eeType);
    $out = "true";
    response($res,$out);
    exit;
}

function doUpdateKilometer(){
    $acm = new acm();
    if(!$acm->hasAccess('carInformationManage')){
        die("access denied");
        exit;
    }
    $car = new Car();
    $myJsonString = $_POST['myJsonString'];
    $myJsonString = json_decode($myJsonString);
    $res = $car->doUpdateKilometer($myJsonString);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doCreateConsumingMaterials(){
    $acm = new acm();
    if(!$acm->hasAccess('carInformationManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $car = new Car();
    list($caid,$mType,$curKM,$changeDate) = $uti->varCleanInput(array('caid','mType','curKM','changeDate'));
    $res = $car->createConsumingMaterials($caid,$mType,$curKM,$changeDate);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getConsumingMaterialsList(){
    $ut = new Utility();
    list($caid) = $ut->varCleanInput(array('caid'));
    if(!intval($caid)){
        $res = "شناسه ماشین بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->getConsumingMaterialsHTM($caid);
    $out = "true";
    response($res,$out);
    exit;
}

function getExtraEquipment(){
    $ut = new Utility();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه ماشین بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->extraEquipmentHtm($cid);
    $out = "true";
    response($res,$out);
    exit;
}

function doCreateExtraEquipment(){
    $uti = new Utility();
    $car = new Car();
    list($cid,$name,$desc) = $uti->varCleanInput(array('cid','name','desc'));
    $files = $_FILES['files'];
    $res = $car->createExtraEquipment($cid,$name,$desc,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteExtraEquipment(){
    $uti = new Utility();
    $car = new Car();
    list($eid) = $uti->varCleanInput(array('eid'));
    $res = $car->deleteExtraEquipment($eid);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showExtraEquipment(){
    $uti = new Utility();
    $car = new Car();
    list($caid) = $uti->varCleanInput(array('caid'));
    $res = $car->getExtraEquipment($caid);
    $out = "true";
    response($res,$out);
    exit;
}

function getCarConsumingMaterials(){
    $uti = new Utility();
    list($cmid) = $uti->varCleanInput(array('cmid'));
    if(!intval($cmid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $car = new Car();
    $res = $car->carConsumingMaterials($cmid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showConsumingMaterialsManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('consumingMaterialsManage')){
        die("access denied");
        exit;
    }
    $car = new Car();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $car->getConsumingMaterialsManageList($page);
    if($page == 1){
        $_SESSION['calcConsumingMaterials'] = $car->getConsumingMaterialsManageListCountRows();
    }
    $totalRows = $_SESSION['calcConsumingMaterials'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام ماشین";
    $feilds[$c]['width']= "19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="carName";
    $c++;

    $feilds[$c]['title']="شماره پلاک";
    $feilds[$c]['width']= "19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="plaque";
    $c++;

    $feilds[$c]['title']="نام ماده مصرفی";
    $feilds[$c]['width']= "19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="materialID";
    $c++;

    $feilds[$c]['title']="برند ماده مصرفی";
    $feilds[$c]['width']= "19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="brand";
    $c++;

    $feilds[$c]['title']="کیلومتر تا تعویض بعدی";
    $feilds[$c]['width']= "19%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="km";

    $pagerType = 1;
    $listTitle = " تعداد موارد ثبت شده : ".$totalRows." عدد ";
    $tableID = "consumingMaterialsManageBody-table";
    $jsf = "showConsumingMaterialsManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function doConsumingMaterialsCreate(){
    $acm = new acm();
    if(!$acm->hasAccess('consumingMaterialsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $car = new Car();
    list($cmid,$carName,$type,$brand,$changeKM) = $uti->varCleanInput(array('cmid','carName','type','brand','changeKM'));
    if(intval($cmid) > 0){  //edit
        $res = $car->consumingMaterialsEdit($cmid,$brand,$changeKM);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $car->consumingMaterialsCreate($carName,$type,$brand,$changeKM);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

//++++++++++++++++++++++ انتظامات +++++++++++++++++++++++

function securityAccessManage(){
    $acm = new acm();
    if(!$acm->hasAccess('securityAccessManage')){
        die("access denied");
        exit;
    }
    $security = new Security();
    $htm = $security->getSecurityAccessManageHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

//******************** ثبت وقایع انتظامات ********************

function showRecordEventsList(){
    $acm = new acm();
    if(!$acm->hasAccess('recordEventManage')){
        die("access denied");
        exit;
    }
    $security = new Security();
    $list = new Listview();
    $ut = new Utility();
    list($sDate,$eDate,$desc,$page) = $ut->varCleanInput(array('sDate','eDate','desc','page'));
    $res = $security->getRecordEventsList($sDate,$eDate,$desc,$page);
    if($page == 1){
        $_SESSION['calcRecordEvents'] = $security->getRecordEventsListCountRows($sDate,$eDate,$desc);
    }
    $totalRows = $_SESSION['calcRecordEvents'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="کاربر ثبت کننده";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="name";
    $c++;

    $feilds[$c]['title']="ساعت";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="cTime";
    $c++;

    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="cDate";
    $c++;

    $feilds[$c]['title']="شرح واقعه";
    $feilds[$c]['width']= "68%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="event";
    $c++;

    $feilds[$c]['title']="ویرایش";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="editEvents";
    $feilds[$c]['icon']="fa-edit";

    $pagerType = 1;
    $listTitle = " تعداد وقایع ثبت شده : ".$totalRows." عدد ";
    $tableID = "recordEventsBody-table";
    $jsf = "showRecordEventsList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getEventsInfo(){
    $uti = new Utility();
    list($infoID) = $uti->varCleanInput(array('infoID'));
    if(!intval($infoID)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $security = new Security();
    $res = $security->eventsInfo($infoID);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateEvents(){
    $acm = new acm();
    if(!$acm->hasAccess('recordEventManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $security = new Security();
    list($eid,$cTime,$desc) = $uti->varCleanInput(array('eid','cTime','desc'));
    if(intval($eid) > 0){  //edit
        $res = $security->editEvents($eid,$cTime,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $security->createEvents($cTime,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

//******************** مدیریت آژانس ها ********************

function showAgencyManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('agencyManage')){
        die("access denied");
        exit;
    }
    $security = new Security();
    $list = new Listview();
    $ut = new Utility();
    list($sDate,$eDate,$billNum,$agencyType,$serviceType,$personnel,$page) = $ut->varCleanInput(array('sDate','eDate','billNum','agencyType','serviceType','personnel','page'));
    $res = $security->getAgencyManageList($sDate,$eDate,$billNum,$agencyType,$serviceType,$personnel,$page);
    if($page == 1){
        $_SESSION['calcAgency'] = $security->getAgencyManageListCountRows($sDate,$eDate,$billNum,$agencyType,$serviceType,$personnel);
        $t = $security->getTotalAgencyPrice($sDate,$eDate,$billNum,$agencyType,$serviceType,$personnel);
        $_SESSION['totalAgencyPrice'] = $t;
    }
    $totalRows = $_SESSION['calcAgency'];
    $headerTxt = $_SESSION['totalAgencyPrice'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= "4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="createDate";
    $c++;

    $feilds[$c]['title']="شماره قبض";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="billNumber";
    $c++;

    $feilds[$c]['title']="نام آژانس";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="agencyID";
    $c++;

    $feilds[$c]['title']="نوع سرویس";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="serviceID";
    $c++;

    $feilds[$c]['title']="توقف (دقیقه)";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="stopMinute";
    $c++;

    $feilds[$c]['title']="نام مسافر/مسافران";
    $feilds[$c]['width']= "11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="passenger";
    $c++;

    $feilds[$c]['title']="نام میهمان/میهمانان";
    $feilds[$c]['width']= "11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="guest";
    $c++;

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= "11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="مبلغ";
    $feilds[$c]['width']= "13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="amount";
    $c++;

    $feilds[$c]['title']="وضعیت";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="status";
    $c++;

    $feilds[$c]['title']="پیوست فایل";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="attachedFileToAgency";
    $feilds[$c]['icon']="fa-link";

    $pagerType = (intval($personnel) > 0 ? 0 : 1);
    $listTitle = " تعداد آژانس ها : ".$totalRows." عدد ";
    $tableID = "agencyManageBody-table";
    $jsf = "showAgencyManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf,'',array(),'',$headerTxt);
    $out = "true";
    response($htm,$out);
    exit;
}

function getAgencyInfo(){
    $uti = new Utility();
    list($aid) = $uti->varCleanInput(array('aid'));
    if(!intval($aid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $security = new Security();
    $res = $security->agencyInfo($aid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateAgency(){
    $acm = new acm();
    if(!$acm->hasAccess('agencyManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $security = new Security();
    list($aid,$cDate,$billNum,$agencyID,$serviceID,$stopMinute,$amount,$passenger,$guest,$desc) = $uti->varCleanInput(array('aid','cDate','billNum','agencyID','serviceID','stopMinute','amount','passenger','guest','desc'));
    $amount = str_replace(',','',$amount);
    if(intval($aid) > 0){  //edit
        $res = $security->editAgency($aid,$cDate,$billNum,$agencyID,$serviceID,$stopMinute,$amount,$passenger,$guest,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $security->createAgency($cDate,$billNum,$agencyID,$serviceID,$stopMinute,$amount,$passenger,$guest,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getAttachedAgencyFile(){
    $ut = new Utility();
    list($aid) = $ut->varCleanInput(array('aid'));
    if(!intval($aid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $security = new Security();
    $res = $security->attachedAgencyFileHtm($aid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachedFileToAgency(){
    $uti = new Utility();
    $security = new Security();
    list($aid) = $uti->varCleanInput(array('aid'));
    $files = $_FILES['files'];
    $res = $security->attachFileToAgency($aid,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteAttachAgencyFile(){
    $uti = new Utility();
    $security = new Security();
    list($aid) = $uti->varCleanInput(array('aid'));
    $res = $security->deleteAttachAgencyFile($aid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function printAgency(){
    $ut = new Utility();
    $security = new Security();
    list($sDate,$eDate,$billNum,$agencyType,$serviceType,$personnel) = $ut->varCleanInput(array('sDate','eDate','billNum','agencyType','serviceType','personnel'));
    $htm = $security->getPrintAgencyHTM($sDate,$eDate,$billNum,$agencyType,$serviceType,$personnel);
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function getAgencyExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('agencyManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $security = new Security();
    list($sDate,$eDate,$billNum,$agencyType,$serviceType,$personnel,$group_method) = $ut->varCleanInput(array('sDate','eDate','billNum','agencyType','serviceType','personnel','group_method'));
    $res = $security->getAgencyExcel($sDate,$eDate,$billNum,$agencyType,$serviceType,$personnel,$group_method);
    $hdarray = array('تاریخ','شماره قبض','نام آژانس','نوع سرویس','توقف به دقیقه','نام مسافر/مسافران','نام میهمان/میهمانان','واحد های مربوطه','توضیحات','مبلغ');
    $fieldNames = array('createDate','billNumber','agencyID','serviceID','stopMinute','passenger','guest','units','description','amount');
    $name = "agencyList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getUnitAgencyAmountExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('agencyManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $security = new Security();
    list($sDate,$eDate,$agencyType,$serviceType) = $ut->varCleanInput(array('sDate','eDate','agencyType','serviceType'));
    $res = $security->getUnitAgencyAmountExcel($sDate,$eDate,$agencyType,$serviceType);
    $hdarray = array('نام واحد','مبلغ هزینه شده');
    $fieldNames = array('Uname','amount');
    $name = "unitAgencyAmountList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doFinalTickAgency(){
    $acm = new acm();
    if(!$acm->hasAccess('finalTickAgency')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $security = new Security();
    list($sDate,$eDate) = $ut->varCleanInput(array('sDate','eDate'));
    $res = $security->finalTickAgency($sDate,$eDate);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//******************** رستوران ********************

function showRestaurantManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('foodItemsManage')){
        die("access denied");
        exit;
    }
    $security = new Security();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $security->getRestaurantManageList($page);
    if($page == 1){
        $_SESSION['calcRestaurant'] = $security->getRestaurantManageListCountRows();
    }
    $totalRows = $_SESSION['calcRestaurant'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام رستوران";
    $feilds[$c]['width']= "95%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="restaurant_Name";

    $pagerType = 1;
    $listTitle = " تعداد رستوران ها : ".$totalRows." عدد ";
    $tableID = "restaurantManageBody-table";
    $jsf = "showRestaurantManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getRestaurantInfo(){
    $uti = new Utility();
    list($rid) = $uti->varCleanInput(array('rid'));
    if(!intval($rid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $security = new Security();
    $res = $security->restaurantInfo($rid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateRestaurant(){
    $acm = new acm();
    if(!$acm->hasAccess('foodItemsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $security = new Security();
    list($rid,$rName) = $uti->varCleanInput(array('rid','rName'));
    if(intval($rid) > 0){  //edit
        $res = $security->editRestaurant($rid,$rName);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $security->createRestaurant($rName);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

//******************** غذا ها ********************

function showFoodManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('foodItemsManage')){
        die("access denied");
        exit;
    }
    $security = new Security();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $security->getFoodManageList($page);
    if($page == 1){
        $_SESSION['calcFood'] = $security->getFoodManageListCountRows();
    }
    $totalRows = $_SESSION['calcFood'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام غذا";
    $feilds[$c]['width']= "50%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="food_Name";
    $c++;

    $feilds[$c]['title']="قیمت";
    $feilds[$c]['width']= "45%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="amount";

    $pagerType = 1;
    $listTitle = " تعداد غذا ها : ".$totalRows." عدد ";
    $tableID = "foodManageBody-table";
    $jsf = "showFoodManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getFoodInfo(){
    $uti = new Utility();
    list($fid) = $uti->varCleanInput(array('fid'));
    if(!intval($fid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $security = new Security();
    $res = $security->foodInfo($fid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateFood(){
    $acm = new acm();
    if(!$acm->hasAccess('foodItemsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $security = new Security();
    list($fid,$fName,$amount) = $uti->varCleanInput(array('fid','fName','amount'));
    $amount = str_replace(',','',$amount);
    if(intval($fid) > 0){  //edit
        $res = $security->editFood($fid,$fName,$amount);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $security->createFood($fName,$amount);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

//******************** نوشیدنی ها ********************

function showDrinkManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('foodItemsManage')){
        die("access denied");
        exit;
    }
    $security = new Security();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $security->getDrinkManageList($page);
    if($page == 1){
        $_SESSION['calcDrink'] = $security->getDrinkManageListCountRows();
    }
    $totalRows = $_SESSION['calcDrink'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام نوشیدنی";
    $feilds[$c]['width']= "50%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="drink_Name";
    $c++;

    $feilds[$c]['title']="قیمت";
    $feilds[$c]['width']= "45%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="amount";

    $pagerType = 1;
    $listTitle = " تعداد نوشیدنی ها : ".$totalRows." عدد ";
    $tableID = "drinkManageBody-table";
    $jsf = "showDrinkManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getDrinkInfo(){
    $uti = new Utility();
    list($did) = $uti->varCleanInput(array('did'));
    if(!intval($did)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $security = new Security();
    $res = $security->drinkInfo($did);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateDrink(){
    $acm = new acm();
    if(!$acm->hasAccess('foodItemsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $security = new Security();
    list($did,$dName,$amount) = $uti->varCleanInput(array('did','dName','amount'));
    $amount = str_replace(',','',$amount);
    if(intval($did) > 0){  //edit
        $res = $security->editDrink($did,$dName,$amount);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $security->createDrink($dName,$amount);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

//******************** مسیرهای سرویس دهی ********************

function showServiceRouteManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('foodItemsManage')){
        die("access denied");
        exit;
    }
    $security = new Security();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $security->getServiceRouteManageList($page);
    if($page == 1){
        $_SESSION['calcServiceRoute'] = $security->getServiceRouteManageListCountRows();
    }
    $totalRows = $_SESSION['calcServiceRoute'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام مسیر";
    $feilds[$c]['width']= "15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="routeName";
    $c++;

    $feilds[$c]['title']="پرسنل";
    $feilds[$c]['width']= "80%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="personnel";

    $pagerType = 1;
    $listTitle = " تعداد مسیر ها : ".$totalRows." عدد ";
    $tableID = "serviceRouteManageBody-table";
    $jsf = "showServiceRouteManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getServiceRouteInfo(){
    $uti = new Utility();
    list($srid) = $uti->varCleanInput(array('srid'));
    if(!intval($srid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $security = new Security();
    $res = $security->serviceRouteInfo($srid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateServiceRoute(){
    $acm = new acm();
    if(!$acm->hasAccess('foodItemsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $security = new Security();
    list($srid,$rName) = $uti->varCleanInput(array('srid','rName'));
    if(intval($srid) > 0){  //edit
        $res = $security->editServiceRoute($srid,$rName);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $security->createServiceRoute($rName);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getPersonnelServiceRoute(){
    $acm = new acm();
    if(!$acm->hasAccess('foodItemsManage')){
        die("access denied");
        exit;
    }
    $security = new Security();
    $res = $security->personnelServiceRouteHtm();
    $out = "true";
    response($res,$out);
    exit;
}

function doPersonnelServiceRoute(){
    $acm = new acm();
    if(!$acm->hasAccess('foodItemsManage')){
        die("access denied");
        exit;
    }
    $security = new Security();
    $myJsonString = $_POST['myJsonString'];
    $myJsonString = json_decode($myJsonString);
    $res = $security->createPersonnelServiceRoute($myJsonString);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//******************** ثبت ناهار اضافه کار ********************

function showOvertimeLunchManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('overtimeLunchManage')){
        die("access denied");
        exit;
    }
    $security = new Security();
    $list = new Listview();
    $ut = new Utility();
    list($sDate,$eDate,$personnel,$restaurant,$meal,$page) = $ut->varCleanInput(array('sDate','eDate','personnel','restaurant','meal','page'));
    $res = $security->getOvertimeLunchManageList($sDate,$eDate,$personnel,$restaurant,$meal,$page);
    if($page == 1){
		$lunch_count_detailes=$security->getOvertimeLunchManageListCountRows($sDate,$eDate,$personnel,$restaurant,$meal);
        $_SESSION['calcOvertimeLunch'] =$lunch_count_detailes['has_lunch_count']; //$security->getOvertimeLunchManageListCountRows($sDate,$eDate,$personnel,$restaurant,$meal);
        $_SESSION['calcOvertimeWhitoutLunch'] =$lunch_count_detailes['whitout_lunch_count']; //$security->getOvertimeLunchManageListCountRows($sDate,$eDate,$personnel,$restaurant,$meal);
        $_SESSION['breakfast_count'] =$lunch_count_detailes['breakfast_count']; // افطاری     //$security->getOvertimeLunchManageListCountRows($sDate,$eDate,$personnel,$restaurant,$meal);
        
        $t = $security->getTotalServiceRoutes($sDate,$eDate,$personnel,$restaurant,$meal);
        $_SESSION['personnelInRoute'] = $t;
    }
    $totalRows = $_SESSION['calcOvertimeLunch']+ $_SESSION['calcOvertimeWhitoutLunch']+$_SESSION['breakfast_count'];
    $headerTxt = $_SESSION['personnelInRoute'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="createDate";
    $c++;

    $feilds[$c]['title']="واحد";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="unitName";
    $c++;

    $feilds[$c]['title']="نام و نام خانوادگی";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="name";
    $c++;

    $feilds[$c]['title']="کد پرسنلی";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pCode";
    $c++;

    $feilds[$c]['title']="مسیر";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="routeName";
    $c++;

    $feilds[$c]['title']="وعده";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="meal";
    $c++;

    $feilds[$c]['title']="رستوران";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="restaurant_Name";
    $c++;

    $feilds[$c]['title']="غذا";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="food_Name";
    $c++;

    $feilds[$c]['title']="نوشیدنی";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="drink_Name";
    $c++;

    $feilds[$c]['title']="سهم نان";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="bread";
    $c++;

    $feilds[$c]['title']="قیمت نهایی";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="finalAmount";
    $c++;

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="desc";
    $c++;

    $feilds[$c]['title']="وضعیت";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="status";
    $c++;

    $feilds[$c]['title']="حذف";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="deleteOvertimeLunch";

    $pagerType = 1;
    $listTitle = '<sapn style="color:green"> تعداد ناهار ها : '.$_SESSION['calcOvertimeLunch']." عدد ".' &nbsp &nbsp &nbsp &nbsp' .
    ' <sapn style="color:blue">تعداد افطاری ها: '.$_SESSION['breakfast_count'].' عدد '.'&nbsp &nbsp &nbsp &nbsp'.'</span>' .
    ' <sapn style="color:red">تعداد بدون ناهار : '.$_SESSION['calcOvertimeWhitoutLunch'].' عدد '.'&nbsp &nbsp &nbsp &nbsp'.'</span>';
    
    $tableID = "overtimeLunchManageBody-table";
    $jsf = "showOvertimeLunchManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf,'',array(),'',$headerTxt);
    $out = "true";
    response($htm,$out);
    exit;
}

function personnelOfUnit(){
    $ut = new Utility();
    list($unit) = $ut->varCleanInput(array('unit'));
    $security = new Security();
    $res = $security->getPersonnelOfUnit($unit);
    $out = "true";
    response($res,$out);
    exit;
}

function doCreateOvertimeLunchPersonnel(){
    $acm = new acm();
    if(!$acm->hasAccess('overtimeLunchManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $security = new Security();
    list($personnel,$olDate,$unit,$meal,$restaurant) = $uti->varCleanInput(array('personnel','olDate','unit','meal','restaurant'));
    $res = $security->createOvertimeLunchPersonnel($personnel,$olDate,$unit,$meal,$restaurant);
    if(intval($res) == -1){
        $res = "شخص تکراری انتخاب شده است !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doCreateOvertimeLunchGuest(){
    $acm = new acm();
    if(!$acm->hasAccess('overtimeLunchManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $security = new Security();
    list($olDate,$unit,$fname,$lname,$desc,$restaurant) = $uti->varCleanInput(array('olDate','unit','fname','lname','desc','restaurant'));
    $res = $security->createOvertimeLunchGuest($olDate,$unit,$fname,$lname,$desc,$restaurant);
    if(intval($res) == -1){
        $res = "شخص تکراری انتخاب شده است !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getLunchOfPersonnel(){
    $acm = new acm();
    if(!$acm->hasAccess('overtimeLunchDetailsManage')){
        die("access denied");
        exit;
    }
    $security = new Security();
    $ut = new Utility();
    list($ddate) = $ut->varCleanInput(array('ddate'));
    $res = $security->lunchOfPersonnelHtm($ddate);
    $out = "true";
    response($res,$out);
    exit;
}

function changeRestaurantType(){
    $security = new Security();
    $ut = new Utility();
    list($ddate) = $ut->varCleanInput(array('ddate'));
    $res = $security->getCountLunch($ddate);
    $out = "true";
    response($res,$out);
    exit;
}

function doCreateOvertimeLunchDetails(){
    $acm = new acm();
    if(!$acm->hasAccess('overtimeLunchDetailsManage')){
        die("access denied");
        exit;
    }
    $security = new Security();
    $ut = new Utility();
    list($BreadNum) = $ut->varCleanInput(array('BreadNum'));
    $myJsonString = $_POST['myJsonString'];
    $myJsonString = json_decode($myJsonString);
    $res = $security->createOvertimeLunchDetails($myJsonString,$BreadNum);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "خطایی پیش آمده یا موارد انتخابی تایید نهایی شده است !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteOvertimeLunch(){
    $security = new Security();
    $ut = new Utility();
    list($olid) = $ut->varCleanInput(array('olid'));
    $res = $security->deleteOvertimeLunch($olid);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "شما مجاز به حذف نمی باشید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function printOvertimeLunch(){
    $ut = new Utility();
    $security = new Security();
    list($sDate,$eDate,$personnel,$restaurant,$meal) = $ut->varCleanInput(array('sDate','eDate','personnel','restaurant','meal'));
    $htm = $security->getPrintOvertimeLunchHTM($sDate,$eDate,$personnel,$restaurant,$meal);
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function getOvertimeLunchExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('overtimeLunchManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $security = new Security();
    list($sDate,$eDate,$personnel,$restaurant,$meal) = $ut->varCleanInput(array('sDate','eDate','personnel','restaurant','meal'));
    $res = $security->getOvertimeLunchExcel($sDate,$eDate,$personnel,$restaurant,$meal);
    $hdarray = array('وعده','تاریخ','واحد','نام و نام خانوادگی','کد پرسنلی','نوع','رستوران','غذا','قیمت غذا','نوشیدنی','سهم نان','قیمت نهایی','توضیحات');
    $fieldNames = array('meal','createDate','unit','fname','pCode','type','restaurantID','foodID','foodAmount','drinkID','bread','finalAmount','description');
    $name = "overtimeLunchList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doFinalTickLunch(){
    $acm = new acm();
    if(!$acm->hasAccess('finalTickLunch')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $security = new Security();
    list($sDate,$eDate) = $ut->varCleanInput(array('sDate','eDate'));
    $res = $security->finalTickLunch($sDate,$eDate);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getUnitOvertimeLunchExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('overtimeLunchManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $security = new Security();
    list($sDate,$eDate) = $ut->varCleanInput(array('sDate','eDate'));
    $res = $security->getUnitOvertimeLunchExcel($sDate,$eDate);
    $hdarray = array('نام واحد','مبلغ هزینه شده');
    $fieldNames = array('Uname','amount');
    $name = "UnitOvertimeLunchList".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ مدیریت پروژه ها +++++++++++++++++++++++

function projectManagement(){
    $acm = new acm();
    if(!$acm->hasAccess('projectManagement')){
        die("access denied");
        exit;
    }
    $project = new Project();
    $htm = $project->getProjectManagementHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showProjectManagementList(){
    $acm = new acm();
    if(!$acm->hasAccess('projectManagement')){
        die("access denied");
        exit;
    }
    $project = new Project();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $project->getProjectManagementList($page);
    if($page == 1){
        $_SESSION['calcProject'] = $project->getProjectManagementListCountRows();
    }
    $totalRows = $_SESSION['calcProject'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="موضوع پروژه";
    $feilds[$c]['width']= "16%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="projectName";
    $c++;

    $feilds[$c]['title']="مالک پروژه";
    $feilds[$c]['width']= "12%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="projectOwner";
    $c++;

    $feilds[$c]['title']="واحد های مرتبط";
    $feilds[$c]['width']= "15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="projectUnits";
    $c++;

    $feilds[$c]['title']="تاریخ ثبت";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="createDate";
    $c++;

    $feilds[$c]['title']="ساعت ثبت";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="createTime";
    $c++;

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= "21%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-sitemap";
    $feilds[$c]['onclick']="showProjectWorkflow";
    $c++;

    $feilds[$c]['title']="فیلد ها";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-list-ol";
    $feilds[$c]['onclick']="showProjectFields";

    $pagerType = 1;
    $listTitle = " تعداد پروژه ها : ".$totalRows." عدد ";
    $tableID = "projectManagementBody-table";
    $jsf = "showProjectManagementList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getProjectInfo(){
    $uti = new Utility();
    list($pid) = $uti->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $project = new Project();
    $res = $project->projectInfo($pid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateProject(){
    $acm = new acm();
    if(!$acm->hasAccess('projectManagement')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $project = new Project();
    list($pid,$name,$owner,$desc,$units) = $uti->varCleanInput(array('pid','name','owner','desc','units'));
    if(intval($pid) > 0){  //edit
        $res = $project->editProject($pid,$name,$owner,$desc,$units);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $project->createProject($name,$owner,$desc,$units);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getProjectWorkflowFile(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه پروژه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $project = new Project();
    $res = $project->projectWorkflowFileHtm($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachFileToProject(){
    $uti = new Utility();
    $project = new Project();
    list($pid,$info) = $uti->varCleanInput(array('pid','info'));
    $files = $_FILES['files'];
    $res = $project->attachFileToProject($pid,$info,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteProjectWorkflowFile(){
    $uti = new Utility();
    $project = new Project();
    list($fid) = $uti->varCleanInput(array('fid'));
    $res = $project->deleteProjectWorkflowFile($fid);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getProjectFieldsFile(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه پروژه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $project = new Project();
    $res = $project->getProjectFieldsFileHtm($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachFieldsFileToProject(){
    $uti = new Utility();
    $project = new Project();
    list($pid,$info) = $uti->varCleanInput(array('pid','info'));
    $files = $_FILES['files'];
    $res = $project->attachFieldsFileToProject($pid,$info,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteProjectFieldsFile(){
    $uti = new Utility();
    $project = new Project();
    list($fid) = $uti->varCleanInput(array('fid'));
    $res = $project->deleteProjectFieldsFile($fid);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getProjectWorkflowInfo(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه پروژه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $project = new Project();
    $res = $project->projectWorkflowInfoHtm($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function doConfirmProjectWorkflowFile(){
    $acm = new acm();
    if(!$acm->hasAccess('projectManagement')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $project = new Project();
    list($pwid,$desc,$radioValue) = $uti->varCleanInput(array('pwid','desc','radioValue'));
    $res = $project->confirmProjectWorkflowFile($pwid,$desc,$radioValue);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getProjectWorkflowComment(){
    $ut = new Utility();
    list($pwid) = $ut->varCleanInput(array('pwid'));
    if(!intval($pwid)){
        $res = "شناسه پروژه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $project = new Project();
    $res = $project->projectWorkflowCommentHtm($pwid);
    $out = "true";
    response($res,$out);
    exit;
}

function getProjectFieldsInfo(){
    $ut = new Utility();
    list($pid) = $ut->varCleanInput(array('pid'));
    if(!intval($pid)){
        $res = "شناسه پروژه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $project = new Project();
    $res = $project->projectFieldsInfoHtm($pid);
    $out = "true";
    response($res,$out);
    exit;
}

function doConfirmProjectFieldsFile(){
    $acm = new acm();
    if(!$acm->hasAccess('projectManagement')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $project = new Project();
    list($pwid,$desc,$radioValue) = $uti->varCleanInput(array('pwid','desc','radioValue'));
    $res = $project->confirmProjectFieldsFile($pwid,$desc,$radioValue);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getProjectFieldsComment(){
    $ut = new Utility();
    list($pwid) = $ut->varCleanInput(array('pwid'));
    if(!intval($pwid)){
        $res = "شناسه پروژه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $project = new Project();
    $res = $project->projectFieldsCommentHtm($pwid);
    $out = "true";
    response($res,$out);
    exit;
}

function doProjectBossTick(){
    $acm = new acm();
    if(!$acm->hasAccess('projectManagement')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $project = new Project();
    list($pid) = $uti->varCleanInput(array('pid'));
    $res = $project->projectBossTick($pid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doProjectFinalTick(){
    $acm = new acm();
    if(!$acm->hasAccess('projectManagement')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $project = new Project();
    list($pid) = $uti->varCleanInput(array('pid'));
    $res = $project->projectFinalTick($pid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ مدیریت بودجه +++++++++++++++++++++++

function budgetManagement(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetManagement')){
        die("access denied");
        exit;
    }
    $budget = new Budget();
    $htm = $budget->getBudgetManagementHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

//******************** بودجه سال ********************

function showBudgetManagementList(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetManagement')){
        die("access denied");
        exit;
    }
    $budget = new Budget();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $budget->getBudgetManagementList($page);
    if($page == 1){
        $_SESSION['calcBudget'] = $budget->getBudgetManagementListCountRows();
    }
    $totalRows = $_SESSION['calcBudget'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="عنوان";
    $feilds[$c]['width']= "15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="تاریخ اعتبار";
    $feilds[$c]['width']= "15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="validDate";
    $c++;

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= "30%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="فاقد بودجه";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-exclamation-circle";
    $feilds[$c]['onclick']="showGoodsWithoutBudget";
    $c++;

    $feilds[$c]['title']="به تفکیک اجزا بودجه";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-puzzle-piece";
    $feilds[$c]['onclick']="showBudgetComponentsFinal";
    $c++;

    $feilds[$c]['title']="به تفکیک محصول";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-puzzle-piece";
    $feilds[$c]['onclick']="showBudgetComponentsDetailsFinal";
    $c++;

    $feilds[$c]['title']="آپلود/دانلود فایل";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-link";
    $feilds[$c]['onclick']="attachFileToBudget";

    $pagerType = 1;
    $listTitle = " تعداد بودجه : ".$totalRows." عدد ";
    $tableID = "budgetManagementBody-table";
    $jsf = "showBudgetManagementList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getBudgetInfo(){
    $uti = new Utility();
    list($bid) = $uti->varCleanInput(array('bid'));
    if(!intval($bid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->budgetInfo($bid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetManagement')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($bid,$year,$validDate,$desc) = $uti->varCleanInput(array('bid','year','validDate','desc'));
    if(intval($bid) > 0){  //edit
        $res = $budget->editBudget($bid,$validDate,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $budget->createBudget($year,$validDate,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function showGoodsWithoutBudget(){
    $budget = new Budget();
    $ut = new Utility();
    list($bid) = $ut->varCleanInput(array('bid'));
    $res = $budget->getGoodsWithoutBudget($bid);
    $out = "true";
    response($res,$out);
    exit;
}

function showBudgetComponentsDetailsFinal(){
    $budget = new Budget();
    $ut = new Utility();
    list($bid,$gCode,$gName,$brand,$ggroup,$sgroup,$series) = $ut->varCleanInput(array('bid','gCode','gName','brand','ggroup','sgroup','series'));
    $res = $budget->getBudgetComponentsDetailsFinal($bid,$gCode,$gName,$brand,$ggroup,$sgroup,$series);
    $out = "true";
    response($res,$out);
    exit;
}

function showBudgetComponentsFinal(){
    $budget = new Budget();
    $ut = new Utility();
    list($bid,$bcDate,$bcName,$bcCode) = $ut->varCleanInput(array('bid','bcDate','bcName','bcCode'));
    $res = $budget->getBudgetComponentsFinal($bid,$bcDate,$bcName,$bcCode);
    $out = "true";
    response($res,$out);
    exit;
}

function showDetailsOfBudgetComponents(){
    $budget = new Budget();
    $ut = new Utility();
    list($bcid) = $ut->varCleanInput(array('bcid'));
    $res = $budget->getBudgetComponentsDetails($bcid);
    $out = "true";
    response($res,$out);
    exit;
}

function doReturnBudgetComponents(){
    $acm = new acm();
    if(!$acm->hasAccess('returnBudgetComponents')){
        $res = "شما دسترسی برگرداند اجزای بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $ut = new Utility();
    list($bcid) = $ut->varCleanInput(array('bcid'));
    $res = $budget->returnBudgetComponents($bcid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getAttachedBudgetFile(){
    $ut = new Utility();
    list($bid) = $ut->varCleanInput(array('bid'));
    if(!intval($bid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->attachedBudgetFileHtm($bid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachFileToBudget(){
    $uti = new Utility();
    $budget = new Budget();
    list($bid,$info,$bcid) = $uti->varCleanInput(array('bid','info','bcid'));
    $files = $_FILES['files'];
    // $uti->fileRecorder($_REQUEST);
    // die();
    $res = $budget->attachFileToBudget($bid,$info,$files,$bcid);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteAttachBudgetFile(){
    $uti = new Utility();
    $budget = new Budget();
    list($fid) = $uti->varCleanInput(array('fid'));
    $res = $budget->deleteAttachBudgetFile($fid);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

//******************** اجزای بودجه ********************

function showBudgetComponentsList(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetComponentsManage')){
        die("access denied");
        exit;
    }
    $budget = new Budget();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $budget->getBudgetComponentsList($page);
    if($page == 1){
        $_SESSION['calcBudgetComponents'] = $budget->getBudgetComponentsListCountRows();
    }
    $totalRows = $_SESSION['calcBudgetComponents'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="سال";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="year";
    $c++;

    $feilds[$c]['title']="نام اجزای بودجه";
    $feilds[$c]['width']= "20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="کد اجزای بودجه";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="unCode";
    $c++;

    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="cDate";
    $c++;

    $feilds[$c]['title']="ساعت";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="cTime";
    $c++;

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= "17%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="ثبت جزئیات";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-puzzle-piece";
    $feilds[$c]['onclick']="createBudgetComponentsDetails";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-sitemap";
    $feilds[$c]['onclick']="showBudgetWorkflow";
    $c++;

    $feilds[$c]['title']="مشاهده جزئیات";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-eye";
    $feilds[$c]['onclick']="showBudgetComponentsDetails";
    $c++;

    $feilds[$c]['title']="تایید نهایی";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-edit";
    $feilds[$c]['onclick']="finalTickBudgetComponents";

    $pagerType = 1;
    $listTitle = " اجزای بودجه : ".$totalRows." عدد ";
    $tableID = "budgetComponentsManage-table";
    $jsf = "showBudgetComponentsList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getGoodsInThisGroup(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetComponentsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($bcid,$brand,$ggroup,$sgroup,$Series) = $ut->varCleanInput(array('bcid','brand','ggroup','sgroup','Series'));
    $budget = new Budget();
    $res = $budget->budgetComponentsDetailsHtm($bcid,$brand,$ggroup,$sgroup,$Series);
    $out = "true";
    response($res,$out);
    exit;
}

function doEditCreateBudgetComponents(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetComponentsManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($bcid,$desc,$year,$name) = $ut->varCleanInput(array('bcid','desc','year','name'));

    $budget = new Budget();
    if(intval($bcid) > 0){  //edit
        $res = $budget->editBudgetComponents($bcid,$desc,$year,$name);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $budget->createBudgetComponents($desc,$year,$name);
        if($res){
            $res = "درخواست شما با موفقیت انجام گردید.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function doCreateBudgetComponentsDetails(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateBudgetComponentsDetails')){
        $res = "شما دسترسی ثبت جزئیات اجزای بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $ut = new Utility();
    list($bccid) = $ut->varCleanInput(array('bccid'));
    $myJsonString = $_POST['myJsonString'];
    $myJsonString = json_decode($myJsonString);

    $budget = new Budget();
    $res = $budget->createBudgetComponentsDetails($myJsonString,$bccid);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getBudgetComponentsInfo(){
    $uti = new Utility();
    list($bcid) = $uti->varCleanInput(array('bcid'));
    if(!intval($bcid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->budgetComponentsInfo($bcid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showBudgetComponentsDetails(){
    $budget = new Budget();
    $ut = new Utility();
    list($bcid,$gCode,$gName,$brand,$ggroup,$sgroup,$series) = $ut->varCleanInput(array('bcid','gCode','gName','brand','ggroup','sgroup','series'));
    $res = $budget->getBudgetComponents($bcid,$gCode,$gName,$brand,$ggroup,$sgroup,$series);
    $out = "true";
    response($res,$out);
    exit;
}

function getPlanningComment(){
    $uti = new Utility();
    list($bcdid) = $uti->varCleanInput(array('bcdid'));
    if(!intval($bcdid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->planningComment($bcdid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getProductionComment(){
    $uti = new Utility();
    list($bcdid) = $uti->varCleanInput(array('bcdid'));
    if(!intval($bcdid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->productionComment($bcdid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doRecordPlanningComment(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetComponentsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($bcdid,$desc,$radioValue) = $uti->varCleanInput(array('bcdid','desc','radioValue'));
    $res = $budget->recordPlanningComment($bcdid,$desc,$radioValue);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doRecordProductionComment(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetComponentsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($bcdid,$desc,$radioValue) = $uti->varCleanInput(array('bcdid','desc','radioValue'));
    $res = $budget->recordProductionComment($bcdid,$desc,$radioValue);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doAllTickBudgetComponentDetails(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetComponentsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($bcid) = $uti->varCleanInput(array('bcid'));
    $res = $budget->allTickBudgetComponentDetails($bcid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function ShowWorkflowBudget(){
    $ut = new Utility();
    list($bcid) = $ut->varCleanInput(array('bcid'));
    if(!intval($bcid)){
        $res = "شناسه بودجه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->budgetWorkflowHtm($bcid);
    $out = "true";
    response($res,$out);
    exit;
}

function doSendBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetComponentsManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($bcid,$desc) = $uti->varCleanInput(array('bcid','desc'));
    $res = $budget->sendBudget($bcid,$desc);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doFinalTickBudgetComponents(){
    $acm = new acm();
    if(!$acm->hasAccess('finalTickBudgetComponents')){
        $res = "شما دسترسی تایید نهایی اجزای بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($bcid) = $uti->varCleanInput(array('bcid'));
    $res = $budget->finalTickBudgetComponents($bcid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function editBudgetComponentDetails(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateBudgetComponentsDetails')){
        $res = "شما دسترسی ویرایش جزئیات اجزای بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $ut = new Utility();
    list($bcdid) = $ut->varCleanInput(array('bcdid'));
    $budget = new Budget();
    $res = $budget->editBudgetComponentDetailsHtm($bcdid);
    $out = "true";
    response($res,$out);
    exit;
}

function doEditBudgetComponentDetails(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateBudgetComponentsDetails')){
        $res = "شما دسترسی ویرایش جزئیات اجزای بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $ut = new Utility();
    list($bcdid,$farvardin,$ordibehesht,$khordad,$tir,$mordad,$shahrivar,$mehr,$aban,$azar,$dey,$bahman,$esfand) = $ut->varCleanInput(array('bcdid','farvardin','ordibehesht','khordad','tir','mordad','shahrivar','mehr','aban','azar','dey','bahman','esfand'));

    $budget = new Budget();
    $res = $budget->editBudgetComponentDetails($bcdid,$farvardin,$ordibehesht,$khordad,$tir,$mordad,$shahrivar,$mehr,$aban,$azar,$dey,$bahman,$esfand);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doCreateBudgetComponentsDetailsExcel(){
    $ut = new Utility();
    list($bid) = $ut->varCleanInput(array('bid'));
    $budget = new Budget();
    $res = $budget->getBudgetComponentsDetailsExcel($bid);
    $hdarray = array('کد مهندسی','کد محصول','نام محصول','برند','گروه','زیرگروه','سری','فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند');
    $fieldNames = array('gCode','goodID','gName','brand','ggroup','gsgroup','series','farvardin','ordibehesht','khordad','tir','mordad','shahrivar','mehr','aban','azar','dey','bahman','esfand');
    $name = "budgetComponentsDetails".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function replaceNewBudget(){
    $budget = new Budget();
    $res = $budget->replaceNewBudget();
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//******************** قیمت بودجه ********************

function showBudgetPriceManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetPriceManage')){
        die("access denied");
        exit;
    }
    $budget = new Budget();
    $list = new Listview();
    $ut = new Utility();
    list($year,$component,$eCode,$gCode,$brand,$group,$page) = $ut->varCleanInput(array('year','component','eCode','gCode','brand','group','page'));
    $res = $budget->getBudgetPriceManageList($year,$component,$eCode,$gCode,$brand,$group,$page);
    if($page == 1){
        $_SESSION['calcBudgetPrice'] = $budget->getBudgetPriceManageListCountRows($year,$component,$eCode,$gCode,$brand,$group);
        $t = $budget->getTotalBudgetPrice($year,$component,$eCode,$gCode,$brand,$group);
        $_SESSION['totalBudgetPrice'] = $t;
    }
    $totalRows = $_SESSION['calcBudgetPrice'];
    $headerTxt = $_SESSION['totalBudgetPrice'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="کد مهندسی";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="HCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']= "20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="فروردین";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID,farvardin";
    $feilds[$c]['onclick']="showBudgetPriceDetails";
    $feilds[$c]['manyColors']="yes";
    $feilds[$c]['icon']="fa fa-calendar";
    $c++;

    $feilds[$c]['title']="اردیبهشت";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID,ordibehesht";
    $feilds[$c]['onclick']="showBudgetPriceDetails";
    $feilds[$c]['manyColors']="yes";
    $feilds[$c]['icon']="fa fa-calendar-days";
    $c++;

    $feilds[$c]['title']="خرداد";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID,khordad";
    $feilds[$c]['onclick']="showBudgetPriceDetails";
    $feilds[$c]['manyColors']="yes";
    $feilds[$c]['icon']="fa fa-calendar";
    $c++;

    $feilds[$c]['title']="تیر";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID,tir";
    $feilds[$c]['onclick']="showBudgetPriceDetails";
    $feilds[$c]['manyColors']="yes";
    $feilds[$c]['icon']="fa fa-calendar-days";
    $c++;

    $feilds[$c]['title']="مرداد";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID,mordad";
    $feilds[$c]['onclick']="showBudgetPriceDetails";
    $feilds[$c]['manyColors']="yes";
    $feilds[$c]['icon']="fa fa-calendar";
    $c++;

    $feilds[$c]['title']="شهریور";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID,shahrivar";
    $feilds[$c]['onclick']="showBudgetPriceDetails";
    $feilds[$c]['manyColors']="yes";
    $feilds[$c]['icon']="fa fa-calendar-days";
    $c++;

    $feilds[$c]['title']="مهر";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID,mehr";
    $feilds[$c]['onclick']="showBudgetPriceDetails";
    $feilds[$c]['manyColors']="yes";
    $feilds[$c]['icon']="fa fa-calendar";
    $c++;

    $feilds[$c]['title']="آبان";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID,aban";
    $feilds[$c]['onclick']="showBudgetPriceDetails";
    $feilds[$c]['manyColors']="yes";
    $feilds[$c]['icon']="fa fa-calendar-days";
    $c++;

    $feilds[$c]['title']="آذر";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID,azar";
    $feilds[$c]['onclick']="showBudgetPriceDetails";
    $feilds[$c]['manyColors']="yes";
    $feilds[$c]['icon']="fa fa-calendar";
    $c++;

    $feilds[$c]['title']="دی";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID,dey";
    $feilds[$c]['onclick']="showBudgetPriceDetails";
    $feilds[$c]['manyColors']="yes";
    $feilds[$c]['icon']="fa fa-calendar-days";
    $c++;

    $feilds[$c]['title']="بهمن";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID,bahman";
    $feilds[$c]['onclick']="showBudgetPriceDetails";
    $feilds[$c]['manyColors']="yes";
    $feilds[$c]['icon']="fa fa-calendar";
    $c++;

    $feilds[$c]['title']="اسفند";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID,esfand";
    $feilds[$c]['onclick']="showBudgetPriceDetails";
    $feilds[$c]['manyColors']="yes";
    $feilds[$c]['icon']="fa fa-calendar-days";

    $pagerType = 0;
    $listTitle = " تعداد اجزا بودجه : ".$totalRows." عدد ";
    $tableID = "budgetPriceManage-table";
    $jsf = "showBudgetPriceManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf,'',array(),'',$headerTxt);
    $out = "true";
    response($htm,$out);
    exit;
}

function showBudgetPriceDetails(){
    $ut = new Utility();
    list($bcdid,$month) = $ut->varCleanInput(array('bcdid','month'));
    if(!intval($bcdid)){
        $res = "شناسه اجزای بودجه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->budgetPriceDetailsHtm($bcdid,$month);
    $out = "true";
    response($res,$out);
    exit;
}

function doGetBudgetPriceManageExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($bid) = $ut->varCleanInput(array('bid'));
    $name = "budgetPrice1".date("y-m-d");
    $url = $ut->getBudgetPriceManageExcel($name,$bid);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//*************************************************************************************************************************************************************************************
//*************************************************************************************************************************************************************************************

//++++++++++++++++++++++ مدیریت بودجه نهایی +++++++++++++++++++++++

function finalBudgetManagement(){
    $acm = new acm();
    if(!$acm->hasAccess('finalBudgetManagement')){
        die("access denied");
        exit;
    }
    $budget = new Budget();
    $htm = $budget->getFinalBudgetManagementHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

//******************** بودجه نهایی پس از اعمال تغییرات ********************

function showFinalBudgetManagementList(){
    $acm = new acm();
    if(!$acm->hasAccess('finalBudgetManagement')){
        die("access denied");
        exit;
    }
    $budget = new Budget();
    $list = new Listview();
    $ut = new Utility();
    list($year,$component,$gCode,$page) = $ut->varCleanInput(array('year','component','gCode','page'));
    $res = $budget->getFinalBudgetManagementList($year,$component,$gCode,$page);
    if($page == 1){
        $_SESSION['calcFinalBudge'] = $budget->getFinalBudgetManagementListCountRows($year,$component,$gCode);
    }
    $totalRows = $_SESSION['calcFinalBudge'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="کد";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']= "14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="فروردین";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="a";
    $feilds[$c]['param']="RowID,farvardin";
    $feilds[$c]['value']="farvardinTotal";
    $feilds[$c]['onclick']="showDetailsOfFinalBudget";
    $feilds[$c]['icon']="fa fa-calendar";
    $c++;

    $feilds[$c]['title']="اردیبهشت";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="a";
    $feilds[$c]['param']="RowID,ordibehesht";
    $feilds[$c]['value']="ordibeheshtTotal";
    $feilds[$c]['onclick']="showDetailsOfFinalBudget";
    $feilds[$c]['icon']="fa fa-calendar-days";
    $c++;

    $feilds[$c]['title']="خرداد";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="a";
    $feilds[$c]['param']="RowID,khordad";
    $feilds[$c]['value']="khordadTotal";
    $feilds[$c]['onclick']="showDetailsOfFinalBudget";
    $feilds[$c]['icon']="fa fa-calendar";
    $c++;

    $feilds[$c]['title']="تیر";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="a";
    $feilds[$c]['param']="RowID,tir";
    $feilds[$c]['value']="tirTotal";
    $feilds[$c]['onclick']="showDetailsOfFinalBudget";
    $feilds[$c]['icon']="fa fa-calendar-days";
    $c++;

    $feilds[$c]['title']="مرداد";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="a";
    $feilds[$c]['param']="RowID,mordad";
    $feilds[$c]['value']="mordadTotal";
    $feilds[$c]['onclick']="showDetailsOfFinalBudget";
    $feilds[$c]['icon']="fa fa-calendar";
    $c++;

    $feilds[$c]['title']="شهریور";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="a";
    $feilds[$c]['param']="RowID,shahrivar";
    $feilds[$c]['value']="shahrivarTotal";
    $feilds[$c]['onclick']="showDetailsOfFinalBudget";
    $feilds[$c]['icon']="fa fa-calendar-days";
    $c++;

    $feilds[$c]['title']="مهر";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="a";
    $feilds[$c]['param']="RowID,mehr";
    $feilds[$c]['value']="mehrTotal";
    $feilds[$c]['onclick']="showDetailsOfFinalBudget";
    $feilds[$c]['icon']="fa fa-calendar";
    $c++;

    $feilds[$c]['title']="آبان";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="a";
    $feilds[$c]['param']="RowID,aban";
    $feilds[$c]['value']="abanTotal";
    $feilds[$c]['onclick']="showDetailsOfFinalBudget";
    $feilds[$c]['icon']="fa fa-calendar-days";
    $c++;

    $feilds[$c]['title']="آذر";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="a";
    $feilds[$c]['param']="RowID,azar";
    $feilds[$c]['value']="azarTotal";
    $feilds[$c]['onclick']="showDetailsOfFinalBudget";
    $feilds[$c]['icon']="fa fa-calendar";
    $c++;

    $feilds[$c]['title']="دی";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="a";
    $feilds[$c]['param']="RowID,dey";
    $feilds[$c]['value']="deyTotal";
    $feilds[$c]['onclick']="showDetailsOfFinalBudget";
    $feilds[$c]['icon']="fa fa-calendar-days";
    $c++;

    $feilds[$c]['title']="بهمن";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="a";
    $feilds[$c]['param']="RowID,bahman";
    $feilds[$c]['value']="bahmanTotal";
    $feilds[$c]['onclick']="showDetailsOfFinalBudget";
    $feilds[$c]['icon']="fa fa-calendar";
    $c++;

    $feilds[$c]['title']="اسفند";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="a";
    $feilds[$c]['param']="RowID,esfand";
    $feilds[$c]['value']="esfandTotal";
    $feilds[$c]['onclick']="showDetailsOfFinalBudget";
    $feilds[$c]['icon']="fa fa-calendar-days";
    $c++;

    $feilds[$c]['title']="مقدار کل تحویل به انبار";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalNum";
    $c++;

    $feilds[$c]['title']="مقدار کل خروج از انبار";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalNum1";
    $c++;

    $feilds[$c]['title']="موجودی انبار";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalEntryNumber";

    $pagerType = 1;
    $listTitle = " تعداد درخواست ها : ".$totalRows." عدد ";
    $tableID = "outProgramBudget-table";
    $jsf = "showFinalBudgetManagementList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function showDetailsOfFinalBudget(){
    $budget = new Budget();
    $ut = new Utility();
    list($bcdid,$month) = $ut->varCleanInput(array('bcdid','month'));
    $res = $budget->detailsOfFinalBudgetHtm($bcdid,$month);
    $out = "true";
    response($res,$out);
    exit;
}

function doGetFinalBudgetManageExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($bid) = $ut->varCleanInput(array('bid'));
    $name = "finalBudget".date("y-m-d");
    $url = $ut->getFinalBudgetManageExcel($name,$bid);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doGetFinalBudgetNumberExcel(){
    $acm = new acm();
    if(!$acm->hasAccess('excelexport') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($bid) = $ut->varCleanInput(array('bid'));
    $name = "finalBudgetN".date("y-m-d");
    $url = $ut->getFinalBudgetNumberExcel($name,$bid);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//******************** بودجه خارج از برنامه ********************

function showOutProgramBudgetList(){
    $acm = new acm();
    if(!$acm->hasAccess('outProgramBudgetManage')){
        die("access denied");
        exit;
    }
    $budget = new Budget();
    $list = new Listview();
    $ut = new Utility();
    list($year,$component,$month,$hcode,$finalTick,$page) = $ut->varCleanInput(array('year','component','month','hcode','finalTick','page'));
    $res = $budget->getOutProgramBudgetList($year,$component,$month,$hcode,$finalTick,$page);
    if($page == 1){
        $_SESSION['calcOutProgramBudge'] = $budget->getOutProgramBudgetListCountRows($year,$component,$month,$finalTick,$hcode);
    }
    $totalRows = $_SESSION['calcOutProgramBudge'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="سال";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="year";
    $c++;

    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']= "9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="HCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']= "20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="ماه";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="monthtxt";
    $c++;

    $feilds[$c]['title']="اصل بودجه";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pNumber";
    $c++;

    $feilds[$c]['title']="مقدار درخواستی";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="number";
    $c++;

/*    $feilds[$c]['title']="مقدار کل";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="tNumber";
    $c++;*/

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= "15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="اطلاعات";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showOutProgramBudgetInfo";
    $feilds[$c]['icon']="fa fa-tv";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showOutProgramBudgetWorkflow";
    $feilds[$c]['icon']="fa fa-sitemap";
    $c++;

    $feilds[$c]['title']="ثبت نظر";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="recordOutProgramBudgetComment";
    $feilds[$c]['icon']="fa fa-comments";
    $c++;

    $feilds[$c]['title']="ویرایش";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="editOutProgramBudget";
    $feilds[$c]['icon']="fa fa-edit";
    $c++;

    $feilds[$c]['title']="تایید نهایی";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="finalTickOutProgramBudget";
    $feilds[$c]['icon']="fa fa-check";

    $pagerType = 1;
    $listTitle = " تعداد درخواست ها : ".$totalRows." عدد ";
    $tableID = "outProgramBudget-table";
    $jsf = "showOutProgramBudgetList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function yearBudgetComponents(){
    $ut = new Utility();
    list($bid) = $ut->varCleanInput(array('bid'));
    $budget = new Budget();
    $res = $budget->yearBudgetComponents($bid);
    $out = "true";
    response($res,$out);
    exit;
}

function getOutProgramBudgetInfo(){
    $uti = new Utility();
    list($opbID) = $uti->varCleanInput(array('opbID'));
    if(!intval($opbID)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->outProgramBudgetInfo($opbID);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateOutProgramBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateOutProgramBudget')){
        $res = "شما دسترسی ثبت/ویرایش درخواست بودجه خارج از برنامه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $ut = new Utility();
    list($opbid,$year,$month,$components,$num,$cName,$nDate,$sDate,$desc) = $ut->varCleanInput(array('opbid','year','month','components','num','cName','nDate','sDate','desc'));
    $budget = new Budget();
    if(intval($opbid) > 0){  //edit
        $res = $budget->editOutProgramBudget($opbid,$year,$month,$components,$num,$cName,$nDate,$sDate,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $budget->createOutProgramBudget($year,$month,$components,$num,$cName,$nDate,$sDate,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getOutProgramBudgetComment(){
    $uti = new Utility();
    list($opbid) = $uti->varCleanInput(array('opbid'));
    if(!intval($opbid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->outProgramBudgetComment($opbid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doRecordOutProgramBudgetComment(){
    $acm = new acm();
    if(!$acm->hasAccess('productionTickBudget') && !$acm->hasAccess('planningTickBudget')){
        $res = "شما دسترسی تاییدیه واحد برنامه ریزی یا تولید را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($opbid,$desc,$radioValue) = $uti->varCleanInput(array('opbid','desc','radioValue'));
    $res = $budget->recordOutProgramBudgetComment($opbid,$desc,$radioValue);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showOutProgramBudgetInfo(){
    $ut = new Utility();
    list($opbid) = $ut->varCleanInput(array('opbid'));
    if(!intval($opbid)){
        $res = "شناسه بودجه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->outProgramBudgetInfoHtm($opbid);
    $out = "true";
    response($res,$out);
    exit;
}

function ShowWorkflowOutProgramBudget(){
    $ut = new Utility();
    list($opbid) = $ut->varCleanInput(array('opbid'));
    if(!intval($opbid)){
        $res = "شناسه بودجه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->outProgramBudgetWorkflowHtm($opbid);
    $out = "true";
    response($res,$out);
    exit;
}

function doSendOutProgramBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('outProgramBudgetManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($opbid,$desc) = $uti->varCleanInput(array('opbid','desc'));
    $res = $budget->sendOutProgramBudget($opbid,$desc);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doFinalTickOutProgramBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('finalTickOutProgramBudget')){
        $res = "شما دسترسی تایید نهایی بودجه خارج از برنامه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($opbid) = $uti->varCleanInput(array('opbid'));
    $res = $budget->finalTickOutProgramBudget($opbid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getOutProgramHCodeWithName(){
    $budget = new Budget();
    $ut = new Utility();
    list($components) = $ut->varCleanInput(array('components'));
    $res = $budget->getOutProgramHCodeWithName($components);
    $out = "true";
    response($res,$out);
    exit;
}

function getOutProgramProductNameWithHcode(){
    $budget = new Budget();
    $ut = new Utility();
    list($bid,$hcode) = $ut->varCleanInput(array('bid','hcode'));
    $res = $budget->getOutProgramProductNameWithHcode($bid,$hcode);
    $out = "true";
    response($res,$out);
    exit;
}

//******************** مجوز جابجایی بودجه ********************

function showDisplacementBudgetList(){
    $acm = new acm();
    if(!$acm->hasAccess('displacementBudgetManage')){
        die("access denied");
        exit;
    }
    $budget = new Budget();
    $list = new Listview();
    $ut = new Utility();
    list($year,$component,$month,$hcode,$finalTick,$page) = $ut->varCleanInput(array('year','component','month','hcode','finalTick','page'));
    $res = $budget->getDisplacementBudgetList($year,$component,$month,$hcode,$finalTick,$page);
    if($page == 1){
        $_SESSION['calcDisplacementBudget'] = $budget->getDisplacementBudgetListCountRows($year,$component,$month,$finalTick,$hcode);
    }
    $totalRows = $_SESSION['calcDisplacementBudget'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="HCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']= "20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="از سال";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="year";
    $c++;

    $feilds[$c]['title']="از ماه";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="monthtxt";
    $c++;

/*    $feilds[$c]['title']="مقدار کل";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="tNumber";
    $c++;*/

    $feilds[$c]['title']="به سال";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="toYear";
    $c++;

    $feilds[$c]['title']="به ماه";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="monthtxt1";
    $c++;

    $feilds[$c]['title']="مقدار جابجایی";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="number";
    $c++;

/*    $feilds[$c]['title']="مقدار کل";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="tNumber1";
    $c++;*/

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= "17%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showDisplacementBudgetWorkflow";
    $feilds[$c]['icon']="fa fa-sitemap";
    $c++;

    $feilds[$c]['title']="ثبت نظر";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="recordDisplacementBudgetComment";
    $feilds[$c]['icon']="fa fa-comments";
    $c++;

    $feilds[$c]['title']="ویرایش";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="editDisplacementBudget";
    $feilds[$c]['icon']="fa fa-edit";
    $c++;

    $feilds[$c]['title']="تایید نهایی";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa fa-check";
    $feilds[$c]['onclick']="finalTickDisplacementBudget";

    $pagerType = 1;
    $listTitle = " تعداد درخواست ها : ".$totalRows." عدد ";
    $tableID = "displacementBudget-table";
    $jsf = "showDisplacementBudgetList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function yearBudgetDisplacementComponents(){
    $ut = new Utility();
    list($bid) = $ut->varCleanInput(array('bid'));
    $budget = new Budget();
    $res = $budget->yearBudgetDisplacementComponents($bid);
    $out = "true";
    response($res,$out);
    exit;
}

function getMonthOfThisYear(){
    $ut = new Utility();
    list($bid) = $ut->varCleanInput(array('bid'));
    $budget = new Budget();
    $res = $budget->getMonthOfThisYear($bid);
    $out = "true";
    response($res,$out);
    exit;
}

function getTotalNumberInMonth(){
    $budget = new Budget();
    $ut = new Utility();
    list($month,$components) = $ut->varCleanInput(array('month','components'));
    $res = $budget->totalNumberInMonth($month,$components);
    $out = "true";
    response($res,$out);
    exit;
}

function getDisplacementBudgetInfo(){
    $uti = new Utility();
    list($dbID) = $uti->varCleanInput(array('dbID'));
    if(!intval($dbID)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->displacementBudgetInfo($dbID);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateDisplacementBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateDisplacementBudget')){
        $res = "شما دسترسی ثبت/ویرایش درخواست جابجایی بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $ut = new Utility();
    list($dbid,$year,$toyear,$month,$tomonth,$components,$num,$desc) = $ut->varCleanInput(array('dbid','year','toyear','month','tomonth','components','num','desc'));

    $budget = new Budget();
    if(intval($dbid) > 0){  //edit
        $res = $budget->editDisplacementBudget($dbid,$year,$toyear,$month,$tomonth,$components,$num,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $budget->createDisplacementBudget($year,$toyear,$month,$tomonth,$components,$num,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getDisplacementBudgetComment(){
    $uti = new Utility();
    list($dbid) = $uti->varCleanInput(array('dbid'));
    if(!intval($dbid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->displacementBudgetComment($dbid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doRecordDisplacementBudgetComment(){
    $acm = new acm();
    if(!$acm->hasAccess('productionTickBudget') && !$acm->hasAccess('planningTickBudget')){
        $res = "شما دسترسی تاییدیه واحد برنامه ریزی یا تولید را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($dbid,$desc,$radioValue) = $uti->varCleanInput(array('dbid','desc','radioValue'));
    $res = $budget->recordDisplacementBudgetComment($dbid,$desc,$radioValue);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function ShowWorkflowDisplacementBudget(){
    $ut = new Utility();
    list($dbid) = $ut->varCleanInput(array('dbid'));
    if(!intval($dbid)){
        $res = "شناسه بودجه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->displacementBudgetWorkflowHtm($dbid);
    $out = "true";
    response($res,$out);
    exit;
}

function doSendDisplacementBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('displacementBudgetManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($dbid,$desc) = $uti->varCleanInput(array('dbid','desc'));
    $res = $budget->SendDisplacementBudget($dbid,$desc);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doFinalTickDisplacementBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('finalTickDisplacementBudget')){
        $res = "شما دسترسی تایید نهایی جابجایی بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($dbid) = $uti->varCleanInput(array('dbid'));
    $res = $budget->finalTickDisplacementBudget($dbid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getDisplacementProductNameWithHcode(){
    $budget = new Budget();
    $ut = new Utility();
    list($bid,$month,$hcode) = $ut->varCleanInput(array('bid','month','hcode'));
    $res = $budget->getDisplacementProductNameWithHcode($bid,$month,$hcode);
    $out = "true";
    response($res,$out);
    exit;
}

//******************** مجوز تاخیر در تحویل بودجه ********************

function showDelayBudgetList(){
    $acm = new acm();
    if(!$acm->hasAccess('delayBudgetManage')){
        die("access denied");
        exit;
    }
    $budget = new Budget();
    $list = new Listview();
    $ut = new Utility();
    list($year,$component,$month,$hcode,$finalTick,$page) = $ut->varCleanInput(array('year','component','month','hcode','finalTick','page'));
    $res = $budget->getDelayBudgetList($year,$component,$month,$hcode,$finalTick,$page);
    if($page == 1){
        $_SESSION['calcDelayBudget'] = $budget->getDelayBudgetListCountRows($year,$component,$month,$finalTick,$hcode);
    }
    $totalRows = $_SESSION['calcDelayBudget'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="سال";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="year";
    $c++;

    $feilds[$c]['title']="کد محصول";
    $feilds[$c]['width']= "9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="HCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']= "21%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="از ماه";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="monthtxt";
    $c++;

/*    $feilds[$c]['title']="مقدار کل";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="tNumber";
    $c++;*/

    $feilds[$c]['title']="به ماه";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="monthtxt1";
    $c++;

    $feilds[$c]['title']="مقدار درخواستی";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="number";
    $c++;

/*    $feilds[$c]['title']="مقدار کل";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="tNumber1";
    $c++;*/

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= "20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showDelayBudgetWorkflow";
    $feilds[$c]['icon']="fa fa-sitemap";
    $c++;

    $feilds[$c]['title']="ثبت نظر";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="recordDelayBudgetComment";
    $feilds[$c]['icon']="fa fa-comments";
    $c++;

    $feilds[$c]['title']="ویرایش";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="editDelayBudget";
    $feilds[$c]['icon']="fa fa-edit";
    $c++;

    $feilds[$c]['title']="تایید نهایی";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa fa-check";
    $feilds[$c]['onclick']="finalTickDelayBudget";

    $pagerType = 1;
    $listTitle = " تعداد درخواست ها : ".$totalRows." عدد ";
    $tableID = "delayBudget-table";
    $jsf = "showDelayBudgetList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getDelayBudgetInfo(){
    $uti = new Utility();
    list($dbID) = $uti->varCleanInput(array('dbID'));
    if(!intval($dbID)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->delayBudgetInfo($dbID);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateDelayBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateDelayBudget')){
        $res = "شما دسترسی ثبت/ویرایش تاخیر در تحویل بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $ut = new Utility();
    list($dbid,$year,$month,$tomonth,$components,$num,$desc) = $ut->varCleanInput(array('dbid','year','month','tomonth','components','num','desc'));

    $budget = new Budget();
    if(intval($dbid) > 0){  //edit
        $res = $budget->editDelayBudget($dbid,$year,$month,$tomonth,$components,$num,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $budget->createDelayBudget($year,$month,$tomonth,$components,$num,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getDelayBudgetComment(){
    $uti = new Utility();
    list($dbid) = $uti->varCleanInput(array('dbid'));
    if(!intval($dbid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->delayBudgetComment($dbid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doRecordDelayBudgetComment(){
    $acm = new acm();
    if($acm->hasAccess('productionTickBudget') || $acm->hasAccess('planningTickBudget')){
        $res = "شما دسترسی ثبت نظر را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($dbid,$desc,$radioValue) = $uti->varCleanInput(array('dbid','desc','radioValue'));
    $res = $budget->recordDelayBudgetComment($dbid,$desc,$radioValue);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function ShowWorkflowDelayBudget(){
    $ut = new Utility();
    list($dbid) = $ut->varCleanInput(array('dbid'));
    if(!intval($dbid)){
        $res = "شناسه بودجه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->delayBudgetWorkflowHtm($dbid);
    $out = "true";
    response($res,$out);
    exit;
}

function doSendDelayBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('delayBudgetManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($dbid,$desc) = $uti->varCleanInput(array('dbid','desc'));
    $res = $budget->SendDelayBudget($dbid,$desc);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doFinalTickDelayBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('finalTickDelayBudget')){
        $res = "شما دسترسی تایید نهایی تاخیر در تحویل بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($dbid) = $uti->varCleanInput(array('dbid'));
    $res = $budget->finalTickDelayBudget($dbid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getDelayProductNameWithHcode(){
    $budget = new Budget();
    $ut = new Utility();
    list($bid,$month,$hcode) = $ut->varCleanInput(array('bid','month','hcode'));
    $res = $budget->getDelayProductNameWithHcode($bid,$month,$hcode);
    $out = "true";
    response($res,$out);
    exit;
}

//******************** اصلاحیه بودجه ********************

function showAmendmentBudgetList(){
    $acm = new acm();
    if(!$acm->hasAccess('amendmentBudgetManage')){
        die("access denied");
        exit;
    }
    $budget = new Budget();
    $list = new Listview();
    $ut = new Utility();
    list($year,$component,$page) = $ut->varCleanInput(array('year','component','page'));
    $res = $budget->getAmendmentBudgetList($year,$component,$page);
    if($page == 1){
        $_SESSION['calcAmendmentBudget'] = $budget->getAmendmentBudgetListCountRows($year,$component);
    }
    $totalRows = $_SESSION['calcAmendmentBudget'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="سال";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="year";
    $c++;

    $feilds[$c]['title']="کد";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']= "17%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="تاریخ ثبت";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="createDate";
    $c++;

    $feilds[$c]['title']="ماه";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="monthtxt";
    $c++;

    $feilds[$c]['title']="مقدار قبل اصلاحیه";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="currentNumber";
    $c++;

    $feilds[$c]['title']="مقدار اصلاح شده به";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="number";
    $c++;

    $feilds[$c]['title']="مابه التفاوت";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="DifferenceNumber";
    $c++;

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="گردش کار";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="showAmendmentBudgetWorkflow";
    $feilds[$c]['icon']="fa fa-sitemap";
    $c++;

    $feilds[$c]['title']="ثبت نظر";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="recordAmendmentBudgetComment";
    $feilds[$c]['icon']="fa fa-comments";
    $c++;

    $feilds[$c]['title']="ویرایش";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="editAmendmentBudget";
    $feilds[$c]['icon']="fa fa-edit";
    $c++;

    $feilds[$c]['title']="تایید نهایی";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa fa-check";
    $feilds[$c]['onclick']="finalTickAmendmentBudget";

    $pagerType = 1;
    $listTitle = " تعداد اصلاحیه ها : ".$totalRows." عدد ";
    $tableID = "amendmentBudget-table";
    $jsf = "showAmendmentBudgetList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getAmendmentBudgetInfo(){
    $uti = new Utility();
    list($abid) = $uti->varCleanInput(array('abid'));
    if(!intval($abid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->amendmentBudgetInfo($abid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function checkValidationDate(){
    $budget = new Budget();
    $res = $budget->checkValidationDate();
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }
}

function getValidationMonth(){
    $uti = new Utility();
    $budget = new Budget();
    list($bDate) = $uti->varCleanInput(array('bDate'));
    $res = $budget->getValidationMonth($bDate);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }
}
function displayTableAmendmentBudget(){
	$acm = new acm();
    if(!$acm->hasAccess('amendmentBudgetManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($month,$components) = $ut->varCleanInput(array('month','components'));
    $budget = new Budget();
    $res = $budget->displayBudgetAmendmentDetailsHtm($month,$components);
    $out = "true";
    response($res,$out);
    exit;
	
}

function createTableAmendmentBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('amendmentBudgetManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($month,$components) = $ut->varCleanInput(array('month','components'));
    $budget = new Budget();
    $res = $budget->budgetAmendmentDetailsHtm($month,$components);
    $out = "true";
    response($res,$out);
    exit;
}
function historyBudgetAmendmentDetailsHtm(){
    $components=$_POST['components'];
    
   $acm = new acm();
    if(!$acm->hasAccess('amendmentBudgetManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($components) = $ut->varCleanInput(array('components'));
    $budget = new Budget();
    $res = $budget->historyBudgetAmendmentDetailsHtm($components);
    
    $out = "true";
    response($res,$out);
    exit;
}

function saveChangeAmendmentBudget(){
	$acm = new acm();
    if(!$acm->hasAccess('amendmentBudgetManage')){
        die("access denied");
        exit;
    }
	$ut = new Utility();
    list($month,$number,$bcdid,$amendmentDate,$amendment_rowid) = $ut->varCleanInput(array('month','number','bcdid','amendmentDate','amendment_rowid'));
    $budget = new Budget();
    $result = $budget->saveChangeAmendmentBudget($month,$number,$bcdid,$amendmentDate,$amendment_rowid);
    if($result=="true")
	{
		$out = "true";
		$res="update_ok";
		response($res,$out);
		exit;
	}
	elseif($result=="false"){
		$out = "false";
		$res="هیچ تغییری انجام نشد";
		response($res,$out);
		 exit;
	}
	elseif(intval($result)>0)
	{
		$out = "true";
		$res=intval($result);
		response($res,$out);
		 exit;
	}
	elseif($result==-1){
		$out = "true";
		$res="";
		response($res,$out);
		 exit;
	}
}
/*
function doCreateAmendmentBudget(){

    $acm = new acm();
    if(!$acm->hasAccess('editCreateAmendmentBudget')){
        $res = "شما دسترسی ثبت اصلاحیه بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $ut = new Utility();
    list($aDate,$month) = $ut->varCleanInput(array('aDate','month'));
    $myJsonString = $_POST['myJsonString'];
    $myJsonString = json_decode($myJsonString);

    $budget = new Budget();
    $res = $budget->createAmendmentBudget($myJsonString,$aDate,$month);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "testهیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}
*/
function doCreateAmendmentBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateAmendmentBudget')){
        $res = "شما دسترسی ثبت اصلاحیه بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $ut = new Utility();
   // list($aDate,$month) = $ut->varCleanInput(array('aDate','month'));
    $myJsonString = $_POST['myJsonString'];
    //$myJsonString = json_decode($myJsonString,true);
    $month=$_POST['month'];
    $aDate =  $_POST['aDate'];
//$ut->fileRecorder('m');
//$ut->fileRecorder(print_r($_POST['month'],true));
//$ut->fileRecorder($_POST['aDate']);
    $budget = new Budget();
    $res = $budget->createAmendmentBudget($myJsonString,$aDate,$month);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}
function editAmendmentBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateAmendmentBudget')){
        $res = "شما دسترسی ویرایش اصلاحیه بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $ut = new Utility();
    list($abid) = $ut->varCleanInput(array('abid'));
    $budget = new Budget();
    $res = $budget->editBudgetAmendmentHtm($abid);
    $out = "true";
    response($res,$out);
    exit;
}

function doEditAmendmentBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateAmendmentBudget')){
        $res = "شما دسترسی ویرایش اصلاحیه بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $ut = new Utility();
    list($abid,$number,$desc) = $ut->varCleanInput(array('abid','number','desc'));

    $budget = new Budget();
    $res = $budget->editAmendmentBudget($abid,$number,$desc);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function ShowWorkflowAmendmentBudget(){
    $ut = new Utility();
    list($abid) = $ut->varCleanInput(array('abid'));
    if(!intval($abid)){
        $res = "شناسه بودجه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->amendmentBudgetWorkflowHtm($abid);
    $out = "true";
    response($res,$out);
    exit;
}

function doSendAmendmentBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('amendmentBudgetManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($abid,$desc) = $uti->varCleanInput(array('abid','desc'));
    $res = $budget->SendAmendmentBudget($abid,$desc);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getAmendmentBudgetComment(){
    $uti = new Utility();
    list($abid) = $uti->varCleanInput(array('abid'));
    if(!intval($abid)){
        $res = "شناسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $res = $budget->amendmentBudgetComment($abid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doRecordAmendmentBudgetComment(){
    $acm = new acm();
    if(!$acm->hasAccess('productionTickBudget') && !$acm->hasAccess('planningTickBudget')){
        $res = "شما دسترسی تاییدیه واحد برنامه ریزی یا تولید را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($abid,$desc,$radioValue) = $uti->varCleanInput(array('abid','desc','radioValue'));
    $res = $budget->recordAmendmentBudgetComment($abid,$desc,$radioValue);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doFinalTickAmendmentBudget(){
    $acm = new acm();
    if(!$acm->hasAccess('finalTickAmendmentBudget')){
        $res = "شما دسترسی تایید نهایی اصلاحیه بودجه را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $budget = new Budget();
    list($abid) = $uti->varCleanInput(array('abid'));
    $res = $budget->finalTickAmendmentBudget($abid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//*************************************************************************************************************************************************************************************
//*************************************************************************************************************************************************************************************

//++++++++++++++++++++++ اسناد ورود و خروج محصول +++++++++++++++++++++++

function productEntryExitDocuments(){
    $acm = new acm();
    if(!$acm->hasAccess('productEntryExitDocuments')){
        die("access denied");
        exit;
    }
    $budget = new Budget();
    $htm = $budget->getProductEntryExitDocumentsHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

//******************** سند ورود محصول ********************

function showBudgetProductEntryList(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetProductEntry')){
        die("access denied");
        exit;
    }
    $budget = new Budget();
    $list = new Listview();
    $ut = new Utility();
    list($sDate,$eDate,$year,$component,$page) = $ut->varCleanInput(array('sDate','eDate','year','component','page'));
    $res = $budget->getBudgetProductEntryList($sDate,$eDate,$year,$component,$page);
    if($page == 1){
        $_SESSION['calcProductEntry'] = $budget->getBudgetProductEntryListCountRows($sDate,$eDate,$year,$component);
    }
    $totalRows = $_SESSION['calcProductEntry'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="سال";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="year";
    $c++;

    $feilds[$c]['title']="کد";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']= "24%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="ماه";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="month";
    $c++;

    $feilds[$c]['title']="مقدار ورودی";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="number";
    $c++;

    $feilds[$c]['title']="مقدار موجودی";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="stock";
    $c++;

    $feilds[$c]['title']="مانده در این ماه";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="remaining";
    $c++;

    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="entryDate";
    $c++;

    $feilds[$c]['title']="ساعت";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="entryTime";
    $c++;

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= "13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="حذف";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="deleteBudgetProductEntry";

    $pagerType = 1;
    $listTitle = " تعداد ورودی ها : ".$totalRows." عدد ";
    $tableID = "budgetProductEntry-table";
    $jsf = "showBudgetProductEntryList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function doCreateBudgetProductEntry(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetProductEntry')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $budget = new Budget();
    list($peDate,$month,$components,$num,$desc) = $ut->varCleanInput(array('peDate','month','components','num','desc'));
    $res = $budget->createBudgetProductEntry($peDate,$month,$components,$num,$desc);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function dateBudgetComponents(){
    $ut = new Utility();
    list($bDate) = $ut->varCleanInput(array('bDate'));
    $budget = new Budget();
    $res = $budget->dateBudgetComponents($bDate);
    $out = "true";
    response($res,$out);
    exit;
}

function doDeleteBudgetProductEntry(){
    $acm = new acm();
    if(!$acm->hasAccess('deleteBudgetProductEntry')){
        $res = "شما دسترسی حذف سند ورود محصول را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $ut = new Utility();
    list($bpeid) = $ut->varCleanInput(array('bpeid'));
    $res = $budget->deleteBudgetProductEntry($bpeid);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getTotalNumberProductInThisMonth(){
    $budget = new Budget();
    $ut = new Utility();
    list($month,$components) = $ut->varCleanInput(array('month','components'));
    $res = $budget->getTotalNumberProductInThisMonth($month,$components);
    $out = "true";
    response($res,$out);
    exit;
}

function getProductNameWithHcode(){
    $budget = new Budget();
    $ut = new Utility();
    list($peDate,$month,$hcode) = $ut->varCleanInput(array('peDate','month','hcode'));
    $res = $budget->getProductNameWithHcode($peDate,$month,$hcode);
    $out = "true";
    response($res,$out);
    exit;
}

//******************** سند خروج محصول ********************

function showBudgetProductExitList(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetProductExit')){
        die("access denied");
        exit;
    }
    $budget = new Budget();
    $list = new Listview();
    $ut = new Utility();
    list($sDate,$eDate,$year,$component,$page) = $ut->varCleanInput(array('sDate','eDate','year','component','page'));
    $res = $budget->getBudgetProductExitList($sDate,$eDate,$year,$component,$page);
    if($page == 1){
        $_SESSION['calcProductExit'] = $budget->getBudgetProductExitListCountRows($sDate,$eDate,$year,$component);
    }
    $totalRows = $_SESSION['calcProductExit'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="سال";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="year";
    $c++;

    $feilds[$c]['title']="کد";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gCode";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']= "28%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="ماه";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="month";
    $c++;

    $feilds[$c]['title']="مقدار خروجی";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="number";
    $c++;

    $feilds[$c]['title']="مقدار موجودی";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="totalEntryNumber";
    $c++;

    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="exitDate";
    $c++;

    $feilds[$c]['title']="ساعت";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="exitTime";
    $c++;

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= "18%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="حذف";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="deleteBudgetProductExit";

    $pagerType = 1;
    $listTitle = " تعداد خروجی ها : ".$totalRows." عدد ";
    $tableID = "budgetProductExit-table";
    $jsf = "showBudgetProductExitList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function doCreateBudgetProductExit(){
    $acm = new acm();
    if(!$acm->hasAccess('budgetProductExit')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $budget = new Budget();
    list($peDate,$components,$num,$desc) = $ut->varCleanInput(array('peDate','components','num','desc'));
    $res = $budget->createBudgetProductExit($peDate,$components,$num,$desc);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doDeleteBudgetProductExit(){
    $acm = new acm();
    if(!$acm->hasAccess('deleteBudgetProductExit')){
        $res = "شما دسترسی حذف سند خروج محصول را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $budget = new Budget();
    $ut = new Utility();
    list($bpeid) = $ut->varCleanInput(array('bpeid'));
    $res = $budget->deleteBudgetProductExit($bpeid);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getTotalEntryNumberProduct(){
    $budget = new Budget();
    $ut = new Utility();
    list($components) = $ut->varCleanInput(array('components'));
    $res = $budget->getTotalEntryNumberProduct($components);
    $out = "true";
    response($res,$out);
    exit;
}

//+++++++++++++++++++++++++++++++ ارتباط با پرسنل +++++++++++++++++++++++++++++++

function contactToPersonnel(){
    $acm = new acm();
    if(!$acm->hasAccess('contactToPersonnel')){
        die("access denied");
        exit;
    }
    $phone = new Phone();
    $htm = $phone->getContactToPersonnelHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showContactToPersonnelList(){
    $acm = new acm();
    if(!$acm->hasAccess('contactToPersonnel')){
        die("access denied");
        exit;
    }
    $phone = new Phone();
    $list = new Listview();
    $ut = new Utility();
    list($name,$unit,$page) = $ut->varCleanInput(array('name','unit','page'));
    $res = $phone->getContactToPersonnelList($name,$unit,$page);
    if($page == 1){
        $_SESSION['calcContactToPersonnel'] = $phone->getContactToPersonnelListCountRows($name,$unit);
    }
    $totalRows = $_SESSION['calcContactToPersonnel'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="سمت";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="post";
    $c++;

    $feilds[$c]['title']="واحد سازمانی";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Unit";
    $c++;

    $feilds[$c]['title']="شماره داخلی 1";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Number1";
    $c++;

    $feilds[$c]['title']="شماره داخلی 2";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Number2";
    $c++;

    $feilds[$c]['title']="شماره داخلی 3";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Number3";
    $c++;

    $feilds[$c]['title']="ایمیل";
    $feilds[$c]['width']="15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="email";
    $c++;

    $feilds[$c]['title']="شماره همراه";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="mobile";

    $pagerType = 0;
    $listTitle = " تعداد شماره های داخلی : ".$totalRows." عدد ";
    $tableID = "contactToPersonnelBody-table";
    $jsf = "showContactToPersonnelList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getContactToPersonnelInfo(){
    $uti = new Utility();
    list($cid) = $uti->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه داخلی بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $phone = new Phone();
    $res = $phone->contactToPersonnelInfo($cid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreatePhone(){
    $acm = new acm();
    if(!$acm->hasAccess('contactToPersonnel')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $phone = new Phone();
    list($cid,$name,$unit,$post,$phone1,$phone2,$phone3,$email,$mobile,$code) = $uti->varCleanInput(array('cid','name','unit','post','phone1','phone2','phone3','email','mobile','code'));
    if(intval($cid) > 0){//edit
        $res = $phone->editPhone($cid,$name,$unit,$post,$phone1,$phone2,$phone3,$email,$mobile,$code);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $phone->createPhone($name,$unit,$post,$phone1,$phone2,$phone3,$email,$mobile,$code);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function dodeletePhone(){
    $acm = new acm();
    if(!$acm->hasAccess('contactToPersonnel')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $phone = new Phone();
    list($cid) = $ut->varCleanInput(array('cid'));
    if(!intval($cid)){
        $res = "شناسه داخلی بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $phone->dodeletePhone($cid);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ مدیریت جلسات +++++++++++++++++++++++

function meetingManage(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $meeting = new Meeting();
    $htm = $meeting->getMeetingManageHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ جلسات +++++++++++++++++++++++

function showFirstMeetingList(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $meeting = new Meeting();
    $list = new Listview();
    $ut = new Utility();
    list($unCode,$subject,$grouping,$sender,$closed,$page) = $ut->varCleanInput(array('unCode','subject','grouping','sender','closed','page'));
    $res = $meeting->getFirstMeetingList($unCode,$subject,$grouping,$sender,$closed,$page);
   // $ut->fileRecorder('print:'.print_r($res,true));
    if($page == 1){
        $_SESSION['calcFirstMeeting'] = $meeting->getFirstMeetingListCountRows($unCode,$subject,$grouping,$sender,$closed);
    }
    $totalRows = $_SESSION['calcFirstMeeting'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="4%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="مدیر جلسه";
    $feilds[$c]['width']="9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="manager";
    $c++;

    $feilds[$c]['title']="موضوع";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="subject";
    $c++;

    $feilds[$c]['title']="علت";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="reason";
    $c++;

    $feilds[$c]['title']="سرتیتر عمده موضوعات جلسه";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="headline";
    $c++;

    $feilds[$c]['title']="دسته";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="grouping";
    $c++;

    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gatheringDate";
    $c++;

    $feilds[$c]['title']="ساعت";
    $feilds[$c]['width']="6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gatheringTime";
    $c++;

    $feilds[$c]['title']="مکان";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gatheringPlace";
    $c++;

    $feilds[$c]['title']="گزارش کار ها";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-user-pen";
    $feilds[$c]['onclick']="showMeetingWorkReportList";
    $c++;

    $feilds[$c]['title']="ثبت نظرات(فقط جلسات نرمال)";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-edit";
    $feilds[$c]['onclick']="createFirstMeetingComment";
	//$feilds[$c]['disabled']="yes";
    $c++;

    $feilds[$c]['title']="نوع جلسه";
    $feilds[$c]['width']= "8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="mType";
    //$feilds[$c]['param']="RowID";
    //$feilds[$c]['icon']="fa-edit";
   // $feilds[$c]['onclick']="createFirstMeetingComment";
	
	

    $pagerType = 0;
    $listTitle = " تعداد جلسه ها : ".$totalRows." عدد ";
    $tableID = "firstMeetingManageBody-table";
    $jsf = "showFirstMeetingList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
	//$ut->fileRecorder($htm);
    $out = "true";
    response($htm,$out);
    exit;
}

function getSubstituteMembers(){
    $uti = new Utility();
    list($members) = $uti->varCleanInput(array('members'));
    $meeting = new Meeting();
    $res = $meeting->substituteMembers($members);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getFirstMeetingInfo(){
    $uti = new Utility();
    list($fmID) = $uti->varCleanInput(array('fmID'));
    if(!intval($fmID)){
        $res = "شناسه جلسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $meeting = new Meeting();
    $res = $meeting->firstMeetingInfo($fmID);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getMeetingMembers(){
    $uti = new Utility();
    list($fmID) = $uti->varCleanInput(array('fmID'));
    if(!intval($fmID)){
        $res = "شناسه جلسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $meeting = new Meeting();
    $res = $meeting->getMeetingMembers($fmID);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getAllowedMembers(){
    $uti = new Utility();
    list($fmID) = $uti->varCleanInput(array('fmID'));
    if(!intval($fmID)){
        $res = "شناسه جلسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $meeting = new Meeting();
    $res = $meeting->getAllowedMembers($fmID);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateFirstMeeting(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($mType,$mid,$interBoss,$subject,$reason,$headline,$meetingGroup,$relationship,$members,$equalManager,$gDate,$gTime,$gPlace,$substitute,$substituteMembers,$requirements,$desc) = $uti->varCleanInput(array('mType','mid','interBoss','subject','reason','headline','meetingGroup','relationship','members','equalManager','gDate','gTime','gPlace','substitute','substituteMembers','requirements','desc'));
    if(intval($mid) > 0){  //edit
        $res = $meeting->editFirstMeeting($mType,$mid,$interBoss,$subject,$reason,$headline,$meetingGroup,$relationship,$members,$equalManager,$gDate,$gTime,$gPlace,$substitute,$substituteMembers,$requirements,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $meeting->createFirstMeeting($mType,$interBoss,$subject,$reason,$headline,$meetingGroup,$relationship,$members,$equalManager,$gDate,$gTime,$gPlace,$substitute,$substituteMembers,$requirements,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getFirstMeetingComments(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($fmID) = $ut->varCleanInput(array('fmID'));
    if(!intval($fmID)){
        $res = "شناسه جلسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $meeting = new Meeting();
    $res = $meeting->getFirstMeetingCommentsHTM($fmID);
    $out = "true";
    response($res,$out);
    exit;
}

function doCreateFirstMeetingComment(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($fmID,$headline,$gDate,$gTime,$gPlace,$requirements,$description) = $uti->varCleanInput(array('fmID','headline','gDate','gTime','gPlace','requirements','description'));
    $res = $meeting->createFirstMeetingComment($fmID,$headline,$gDate,$gTime,$gPlace,$requirements,$description);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getSubstituteUsers(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($fmID) = $ut->varCleanInput(array('fmID'));
    if(!intval($fmID)){
        $res = "شناسه جلسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $meeting = new Meeting();
    $res = $meeting->getSubstituteUsers($fmID);
    $out = "true";
    response($res,$out);
    exit;
}

function doCreateSubstituteStatus(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($fmID,$type,$user) = $uti->varCleanInput(array('fmID','type','user'));
    $res = $meeting->createSubstituteStatus($fmID,$type,$user);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

/*function checkMeetingCommentStatus(){
    $uti = new Utility();
    $meeting = new Meeting();
    list($fmID) = $uti->varCleanInput(array('fmID'));
    $res = $meeting->checkMeetingCommentStatus($fmID);
    $out = "true";
    response($res,$out);
    exit;
}*/

function doStartFirstMeeting(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($fmID) = $uti->varCleanInput(array('fmID'));
    $res = $meeting->startFirstMeeting($fmID);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getMeetingMembersAndJobs(){
    $uti = new Utility();
    $meeting = new Meeting();
    list($fmID) = $uti->varCleanInput(array('fmID'));
    $res = $meeting->getMeetingMembersAndJobs($fmID);
    $out = "true";
    response($res,$out);
    exit;
}

function doCreateMeetingJobs(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($mid,$member,$vDate,$desc) = $uti->varCleanInput(array('mid','member','vDate','desc'));
    $res = $meeting->createMeetingJobs($mid,$member,$vDate,$desc);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doDeleteMeetingJobs(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($mid,$jobID) = $uti->varCleanInput(array('mid','jobID'));
    $res = $meeting->deleteMeetingJobs($mid,$jobID);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function createMeetingPrerequisite(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($mid,$jobID) = $uti->varCleanInput(array('mid','jobID'));
    $res = $meeting->createMeetingJobsHtm($mid,$jobID);
    $out = "true";
    response($res,$out);
    exit;
}

function doCreateMeetingPrerequisite(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($jid,$mid,$jobID) = $uti->varCleanInput(array('jid','mid','jobID'));
    $res = $meeting->createMeetingPrerequisite($jid,$mid,$jobID);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showMeetingWorkReportList(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($mid) = $uti->varCleanInput(array('mid'));
    $res = $meeting->showMeetingWorkReportList($mid);
    $out = "true";
    response($res,$out);
    exit;
}

function getMeetingWorkReportHtm(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($jobID) = $uti->varCleanInput(array('jobID'));
    $res = $meeting->getMeetingWorkReportHtm($jobID);
    $out = "true";
    response($res,$out);
    exit;
}

function getMeetingJobPercent(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($jobID) = $uti->varCleanInput(array('jobID'));
    $res = $meeting->getMeetingJobPercent($jobID);
    $out = "true";
    response($res,$out);
    exit;
}

function doCreateMeetingWorkReport(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($jobID,$desc,$status,$percent) = $uti->varCleanInput(array('jobID','desc','status','percent'));
    $res = $meeting->createMeetingWorkReport($jobID,$desc,$status,$percent);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doDeleteMeetingWorkReport(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $meeting = new Meeting();
    list($mwrID,$jobID) = $ut->varCleanInput(array('mwrID','jobID'));
    if(!intval($mwrID)){
        $res = "شناسه گزارش کار بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $meeting->doDeleteMeetingWorkReport($mwrID,$jobID);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doConfirmMeetingJob(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $meeting = new Meeting();
    list($mid,$jobID) = $ut->varCleanInput(array('mid','jobID'));
    if(!intval($jobID)){
        $res = "شناسه مسئولیت بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $meeting->confirmMeetingJob($mid,$jobID);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doNoConfirmMeetingJob(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $meeting = new Meeting();
    list($mid,$jobID,$desc) = $ut->varCleanInput(array('mid','jobID','desc'));
    if(!intval($jobID)){
        $res = "شناسه مسئولیت بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $meeting->noConfirmMeetingJob($mid,$jobID,$desc);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doAddRemoveMember(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $meeting = new Meeting();
    list($fmID,$members) = $ut->varCleanInput(array('fmID','members'));
    if(!intval($fmID)){
        $res = "شناسه جلسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $meeting->addRemoveMember($fmID,$members);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doAddGuestMember(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $meeting = new Meeting();
    list($fmID,$members,$role) = $ut->varCleanInput(array('fmID','members','role'));
    if(!intval($fmID)){
        $res = "شناسه جلسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $meeting->addGuestMember($fmID,$members,$role);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getMeetingWorkReportCommentHtm(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($mwrID) = $uti->varCleanInput(array('mwrID'));
    $res = $meeting->getMeetingWorkReportCommentHtm($mwrID);
    $out = "true";
    response($res,$out);
    exit;
}

function doCommentMeetingWorkReport(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($mwrID,$desc) = $uti->varCleanInput(array('mwrID','desc'));
    $res = $meeting->commentMeetingWorkReport($mwrID,$desc);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doCloseFirstMeeting(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($fmID,$befor_deadline,$meeting_close_reason) = $uti->varCleanInput(array('fmID','befor_deadline','meeting_close_reason'));
    //$uti->fileRecorder('befor_deadline:'.$befor_deadline);
    $res = $meeting->closeFirstMeeting($fmID,$befor_deadline,$meeting_close_reason);
 
   if($res==-9){ // گزارشات کارها به صورت 100 % وارد نشده و  مدیر جلسه برای خاتمه جلسه نیاز به ساختار تصمیم گیری دارد
        $res = "-9";
        response($res,$out);
        exit;
   }
    if($res==1){ //گزارشات به صورت 100% وارد شده است
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }elseif($res==0){
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function downloadFirstMeetingVideo(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $meeting = new Meeting();
    $res = $meeting->downloadFirstMeetingVideo();
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ گزارشات جلسات +++++++++++++++++++++++

function meetingReportManage(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingReportManage')){
        die("access denied");
        exit;
    }
    $meeting = new Meeting();
    $htm = $meeting->getMeetingReportManageHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showMeetingReportList(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingReportManage')){
        die("access denied");
        exit;
    }
    $meeting = new Meeting();
    $list = new Listview();
    $ut = new Utility();
    list($unCode,$subject,$grouping,$status,$page) = $ut->varCleanInput(array('unCode','subject','grouping','status','page'));
    $res = $meeting->getMeetingReportList($unCode,$subject,$grouping,$status,$page);
    if($page == 1){
        $_SESSION['calcReportMeeting'] = $meeting->getMeetingReportListCountRows($unCode,$subject,$grouping,$status);
        $t = $meeting->reportMeetingInfoHtm();
    }
    $totalRows = $_SESSION['calcReportMeeting'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="مدیر جلسه";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="manager";
    $c++;

    $feilds[$c]['title']="موضوع";
    $feilds[$c]['width']="11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="subject";
    $c++;

    $feilds[$c]['title']="علت";
    $feilds[$c]['width']="11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="reason";
    $c++;

    $feilds[$c]['title']="سرتیتر عمده موضوعات جلسه";
    $feilds[$c]['width']="25%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="headline";
    $c++;

    $feilds[$c]['title']="دسته";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="grouping";
    $c++;

    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gatheringDate";
    $c++;

    $feilds[$c]['title']="ساعت";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gatheringTime";
    $c++;

    $feilds[$c]['title']="مکان";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gatheringPlace";
    $c++;

    $feilds[$c]['title']="گزارش کار ها";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-user-pen";
    $feilds[$c]['onclick']="showReportMeetingWorkReportList";
    $c++;

    $feilds[$c]['title']="اطلاعات";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-tv";
    $feilds[$c]['onclick']="showReportMeetingInfo";
    //$c++;

/*    $feilds[$c]['title']="اقدامات";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-edit";
    $feilds[$c]['onclick']="createFirstMeetingComment";
    $feilds[$c]['disabled']="yes";*/

    $pagerType = 0;
    $listTitle = " تعداد جلسات : ".$totalRows." عدد ";
    $tableID = "meetingReportManageBody-table";
    $jsf = "showMeetingReportList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf,'',array(),'','',$t);
    $out = "true";
    response(array($htm,$t),$out);
    exit;
}

function showReportMeetingInfo(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingReportManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    list($fmID) = $ut->varCleanInput(array('fmID'));
    if(!intval($fmID)){
        $res = "شناسه جلسه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $meeting = new Meeting();
    $res = $meeting->otherInfoMeetingHTM($fmID);
    $out = "true";
    response($res,$out);
    exit;
}

function showReportMeetingWorkReportList(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingReportManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($mid) = $uti->varCleanInput(array('mid'));
    $res = $meeting->showReportMeetingWorkReportList($mid);
    $out = "true";
    response($res,$out);
    exit;
}

function getMeetingWorkReportHtm1(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingReportManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($jobID) = $uti->varCleanInput(array('jobID'));
    $res = $meeting->getMeetingWorkReportHtm1($jobID);
    $out = "true";
    response($res,$out);
    exit;
}

function  get_meeting_jobs_detailes(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $meeting = new Meeting();
    list($job_id) = $ut->varCleanInput(array('job_id'));
    $res = $meeting->get_meeting_jobs_detailes($job_id);
   // $ut->fileRecorder($res);
    if($res){
        $out = "true";
        response($res,$out);
    }
    exit;
}

function  do_edit_meeting_jobs_item(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $meeting = new Meeting();
    
    list($edit_job_id,$valid_date,$job_description) = $ut->varCleanInput(array('edit_job_id','valid_date','job_description'));
    $res = $meeting->do_edit_meeting_jobs_item($edit_job_id,$valid_date,$job_description);
   //$ut->fileRecorder($res);
    if($res){
        $out = "true";
        response($res,$out);
    }
    exit;
}

function get_meeting_jobs_history(){
     $acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $meeting = new Meeting();
    
    list($meeting_job_id) = $ut->varCleanInput(array('meeting_job_id'));
    $res = $meeting->get_meeting_jobs_history($meeting_job_id);
  //  $ut->fileRecorder($res);
    if($res){
        $out = "true";
        response($res,$out);
    }
    exit;
}
//++++++++++++++++++++++ دسته بندی جلسات +++++++++++++++++++++++

function showMeetingGroupList(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingGroupManage')){
        die("access denied");
        exit;
    }
    $meeting = new Meeting();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $meeting->getMeetingGroupList($page);
    if($page == 1){
        $_SESSION['calcMeetingGroup'] = $meeting->getMeetingGroupListCountRows();
    }
    $totalRows = $_SESSION['calcMeetingGroup'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام دسته";
    $feilds[$c]['width']="95%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="groupName";

    $pagerType = 0;
    $listTitle = " تعداد دسته ها : ".$totalRows." عدد ";
    $tableID = "meetingGroupManageBody-table";
    $jsf = "showMeetingGroupList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getMeetingGroupInfo(){
    $uti = new Utility();
    list($mid) = $uti->varCleanInput(array('mid'));
    if(!intval($mid)){
        $res = "شناسه دسته بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $meeting = new Meeting();
    $res = $meeting->meetingGroupInfo($mid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateMeetingGroup(){
    $acm = new acm();
    if(!$acm->hasAccess('meetingGroupManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $meeting = new Meeting();
    list($mid,$name) = $uti->varCleanInput(array('mid','name'));
    if(intval($mid) > 0){  //edit
        $res = $meeting->editMeetingGroup($mid,$name);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $meeting->createMeetingGroup($name);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

//++++++++++++++++++++++ مدیریت برچسب ها +++++++++++++++++++++++

function labelManagement(){
    $acm = new acm();
    if(!$acm->hasAccess('labelManagement')){
        die("access denied");
        exit;
    }
    $label = new Label();
    $htm = $label->getLabelManagementHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showLabelManagementList(){
    $acm = new acm();
    if(!$acm->hasAccess('labelManagement')){
        die("access denied");
        exit;
    }
    $label = new Label();
    $list = new Listview();
    $ut = new Utility();
    list($labelNum,$hpCode,$piece,$status,$dateType,$page) = $ut->varCleanInput(array('labelNum','hpCode','piece','status','dateType','page'));
    $res = $label->getLabelManagementList($labelNum,$hpCode,$piece,$status,$page);
    if($page == 1){
        $_SESSION['calcLabelManage'] = $label->getLabelManagementListCountRows($labelNum,$hpCode,$piece,$status);
    }
    $totalRows = $_SESSION['calcLabelManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="کد برچسب";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="labelNum";
    $c++;

    $feilds[$c]['title']="کد کالا";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="HPCode";
    $c++;

    $feilds[$c]['title']="نام برچسب";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pName";
    $c++;

    $feilds[$c]['title']="فرمت";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="printFormat";
    $c++;

    $feilds[$c]['title']="ثبت کننده";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="uid";
    $c++;

    $feilds[$c]['title'] = "تایید کننده";
    $feilds[$c]['width'] = "10%";
    $feilds[$c]['order'] = "none";
    $feilds[$c]['f'] = "tid";
    $c++;

    if (intval($dateType) == 0) {
        $feilds[$c]['title']="تاریخ تایید";
        $feilds[$c]['width']="8%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="statusDate";
        $c++;
    }else{
        $feilds[$c]['title']="تاریخ تغییر";
        $feilds[$c]['width']="8%";
        $feilds[$c]['order']="none";
        $feilds[$c]['f']="changeDate";
        $c++;
    }

    $feilds[$c]['title']="وضعیت";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="status";
    $c++;

    $feilds[$c]['title']="تغییر";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-edit";
    $feilds[$c]['onclick']="changeRequestLabel";
    $c++;

    $feilds[$c]['title']="ضمیمه";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-link";
    $feilds[$c]['onclick']="showLabelManageAttachment";
    $c++;

    $feilds[$c]['title']="تاییدیه";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-check";
    $feilds[$c]['onclick']="confirmationLabel";

    $pagerType = 1;
    $listTitle = " تعداد برچسب ها : ".$totalRows." عدد ";
    $tableID = "labelManagementBody-table";
    $jsf = "showLabelManagementList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getLabelPieceCode(){
    $label = new Label();
    $ut = new Utility();
    list($piece) = $ut->varCleanInput(array('piece'));
    $res = $label->getLabelPieceCode($piece);
    $out = "true";
    response($res,$out);
    exit;
}

function getLabelPieceName(){
    $label = new Label();
    $ut = new Utility();
    list($pCode) = $ut->varCleanInput(array('pCode'));
    $res = $label->getLabelPieceName($pCode);
    $out = "true";
    response($res,$out);
    exit;
}

function doEditCreateLabel(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateLabel')){
        $res = "شما دسترسی ثبت/ویرایش برچسب را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lid,$piece,$pFormat) = $uti->varCleanInput(array('lid','piece','pFormat'));

    if(intval($lid) > 0){//edit
        $res = $label->editLabel($lid,$piece,$pFormat);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{//create
        $res = $label->createLabel($piece,$pFormat);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getAttachedLabelFile(){
    $ut = new Utility();
    list($lid) = $ut->varCleanInput(array('lid'));
    if(!intval($lid)){
        $res = "شناسه برچسب بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $label = new Label();
    $res = $label->attachedLabelFileHtm($lid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachFileToLabel(){
    $acm = new acm();
    if(!$acm->hasAccess('attachFileToLabel')){
        $res = "شما دسترسی پیوست فایل به برچسب را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lid) = $uti->varCleanInput(array('lid'));
    $files = $_FILES['files'];
    $res = $label->attachFileToLabel($lid,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteAttachLabelFile(){
    $uti = new Utility();
    $label = new Label();
    list($lid,$laid) = $uti->varCleanInput(array('lid','laid'));
    $res = $label->deleteAttachLabelFile($lid,$laid);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showLabelManageAttachment(){
    $ut = new Utility();
    list($lid) = $ut->varCleanInput(array('lid'));
    if(!intval($lid)){
        $res = "شناسه برچسب بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $label = new Label();
    $res = $label->attachmentFileLabelHtm($lid);
    $out = "true";
    response($res,$out);
    exit;
}

function doConfirmationLabel(){
    $acm = new acm();
    if(!$acm->hasAccess('labelConfirmation')){
        $res = "شما دسترسی تایید برچسب ها را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lid) = $uti->varCleanInput(array('lid'));
    $res = $label->confirmationLabel($lid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getChangeRequestLabelDesc(){
    $uti = new Utility();
    list($lid) = $uti->varCleanInput(array('lid'));
    if(!intval($lid)){
        $res = "شناسه برچسب بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $label = new Label();
    $res = $label->changeRequestLabelDesc($lid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doChangeRequestLabel(){
    $acm = new acm();
    if(!$acm->hasAccess('changeRequestLabel')){
        $res = "شما دسترسی درخواست تغییر برچسب ها را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lid,$desc) = $uti->varCleanInput(array('lid','desc'));
    $res = $label->changeRequestLabel($lid,$desc);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getLabelInfo(){
    $uti = new Utility();
    list($lid) = $uti->varCleanInput(array('lid'));
    if(!intval($lid)){
        $res = "شناسه برچسب بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $label = new Label();
    $res = $label->labelInfo($lid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doSendSMSForLabelConfirmation(){
    $uti = new Utility();
    $label = new Label();
    list($type) = $uti->varCleanInput(array('type'));
    $res = $label->sendSMSForLabelConfirmation($type);
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ درخواست برچسب +++++++++++++++++++++++

function showLabelRequestList(){
    $acm = new acm();
    if(!$acm->hasAccess('labelRequestManagement')){
        die("access denied");
        exit;
    }
    $label = new Label();
    $list = new Listview();
    $ut = new Utility();
    list($name,$nDate,$bNum,$status,$closeType,$pieceID,$page) = $ut->varCleanInput(array('name','nDate','bNum','status','closeType','pieceID','page'));
    $res = $label->getLabelRequestManagementList($name,$nDate,$bNum,$status,$closeType,$pieceID,$page);
    if($page == 1){
        $_SESSION['calcLabelRManage'] = $label->getLabelRequestManagementListCountRows($name,$nDate,$bNum,$status,$closeType,$pieceID);
    }
    $totalRows = $_SESSION['calcLabelRManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="unName";
    $c++;

    //$feilds[$c]['title']="تاریخ نیاز";
    $feilds[$c]['title']="تاریخ بارگذاری فایل";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="needDate";
    $c++;

    $feilds[$c]['title']="شماره درخواست خرید";
    $feilds[$c]['width']="11%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="brName";
    $c++;

    $feilds[$c]['title']="ثبت کننده";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="uid";
    $c++;

    $feilds[$c]['title']="تایید کننده";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="tid";
    $c++;

    $feilds[$c]['title']="تاریخ تایید";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="sDate";
    $c++;

    $feilds[$c]['title']="وضعیت";
    $feilds[$c]['width']="8%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="status";
    $c++;

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']="14%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="جزئیات";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-puzzle-piece";
    $feilds[$c]['onclick']="createLabelRequestDetails";
    $c++;

    $feilds[$c]['title']="ضمیمه";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-link";
    $feilds[$c]['onclick']="showLabelRequestAttachment";
    $c++;

    $feilds[$c]['title']="تاییدیه";
    $feilds[$c]['width']= "5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-check";
    $feilds[$c]['onclick']="confirmationLabelRequest";

    $pagerType = 0;
    $listTitle = " تعداد درخواست ها : ".$totalRows." عدد ";
    $tableID = "labelRequestManagementBody-table";
    $jsf = "showLabelRequestList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function doEditCreateLabelRequest(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateLabelRequest')){
        $res = "شما دسترسی ثبت/ویرایش درخواست برچسب را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lrid,$ndate,$bnumber,$desc) = $uti->varCleanInput(array('lrid','ndate','bnumber','desc'));
    if(intval($lrid) > 0){  //edit
        $res = $label->editLabelRequest($lrid,$ndate,$bnumber,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $label->createLabelRequest($ndate,$bnumber,$desc);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function dodeleteLabelRequest(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateLabelRequest')){
        $res = "شما دسترسی حذف درخواست برچسب را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $ut = new Utility();
    $label = new Label();
    list($lrid) = $ut->varCleanInput(array('lrid'));
    if(!intval($lrid)){
        $res = "شناسه درخواست بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $res = $label->dodeleteLabelRequest($lrid);
    if($res){
        $res = "درخواست شما با موفقیت انجام گردید.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getLabelRequestInfo(){
    $uti = new Utility();
    list($lrid) = $uti->varCleanInput(array('lrid'));
    if(!intval($lrid)){
        $res = "شناسه درخواست برچسب بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $label = new Label();
    $res = $label->labelRequestInfo($lrid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getAttachedLabelRequestFile(){
    $ut = new Utility();
    list($lrid) = $ut->varCleanInput(array('lrid'));
    if(!intval($lrid)){
        $res = "شناسه درخواست بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $label = new Label();
    $res = $label->attachedLabelRequestFileHtm($lrid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachFileToLabelRequest(){
    $acm = new acm();
    if(!$acm->hasAccess('attachFileToLabelRequest')){
        $res = "شما دسترسی پیوست فایل به درخواست را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lrid,$dimension,$desc) = $uti->varCleanInput(array('lrid','dimension','desc'));
    $files = $_FILES['files'];
    $res = $label->attachFileToLabelRequest($lrid,$dimension,$desc,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteAttachLabelRequestFile(){
    $acm = new acm();
    if(!$acm->hasAccess('deleteFileFromLabelRequest')){
        $res = "شما دسترسی حذف فایل از درخواست برچسب را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lraid) = $uti->varCleanInput(array('lraid'));
    $res = $label->deleteAttachLabelRequestFile($lraid);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doConfirmationAttachLabelRequestFile(){
    $acm = new acm();
    if(!$acm->hasAccess('confirmationAttachLabelRequestZinc')){
        $res = "شما دسترسی تاییدیه چاپ زینک را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lraid,$actioner,$desc) = $uti->varCleanInput(array('lraid','actioner','desc'));
    $res = $label->confirmationAttachLabelRequestFile($lraid,$actioner,$desc);
    if ($res){
        $res = 'با موفقیت تایید گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doFinisherAttachLabelRequestFile(){
    $acm = new acm();
    if(!$acm->hasAccess('actionerAttachLabelRequestZinc')){
        $res = "شما دسترسی  تایید اتمام کار چاپ زینک را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lraid) = $uti->varCleanInput(array('lraid'));
    $res = $label->finisherAttachLabelRequestFile($lraid);
    if ($res){
        $res = 'درخواست با موفقیت انجام گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showLabelRequestAttachment(){
    $ut = new Utility();
    list($lrid) = $ut->varCleanInput(array('lrid'));
    if(!intval($lrid)){
        $res = "شناسه درخواست برچسب بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $label = new Label();
    $res = $label->attachmentFileLabelRequestHtm($lrid);
    $out = "true";
    response($res,$out);
    exit;
}

function doConfirmationLabelRequest(){
    $acm = new acm();
    if(!$acm->hasAccess('labelRequestConfirmation')){
        $res = "شما دسترسی تایید درخواست برچسب ها را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lrid) = $uti->varCleanInput(array('lrid'));
    $res = $label->confirmationLabelRequest($lrid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getLabelHPCode(){
    $uti = new Utility();
    $label = new Label();
    list($lid) = $uti->varCleanInput(array('lid'));
    $res = $label->getLabelHPCode($lid);
    $out = "true";
    response($res,$out);
    exit;
}

function getLabelName(){
    $uti = new Utility();
    $label = new Label();
    list($hpCode) = $uti->varCleanInput(array('hpCode'));
    $res = $label->getLabelName($hpCode);
    $out = "true";
    response($res,$out);
    exit;
}

function getLabelRequestDetails(){
    $ut = new Utility();
    list($lrid) = $ut->varCleanInput(array('lrid'));
    if(!intval($lrid)){
        $res = "شناسه درخواست بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $label = new Label();
    $res = $label->getLabelRequestDetailsHtm($lrid);
    $out = "true";
    response($res,$out);
    exit;
}

function doCreateLabelRequestDetails(){
    $acm = new acm();
    if(!$acm->hasAccess('createLabelRequestDetails')){
        $res = "شما دسترسی ثبت جزئیات درخواست برچسب را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lrid,$lid,$number) = $uti->varCleanInput(array('lrid','lid','number'));
    $res = $label->createLabelRequestDetails($lrid,$lid,$number);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteLabelRequestDetails(){
    $acm = new acm();
    if(!$acm->hasAccess('deleteLabelRequestDetails')){
        $res = "شما دسترسی حذف جزئیات درخواست برچسب را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lrid,$lrdid) = $uti->varCleanInput(array('lrid','lrdid'));
    $res = $label->deleteLabelRequestDetails($lrid,$lrdid);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function confirmationLabelRequestDetails(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateLabel')){
        $res = "شما دسترسی تغییر وضعیت را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lrdid) = $uti->varCleanInput(array('lrdid'));
    $res = $label->confirmationLabelRequestDetails($lrdid);
    if ($res){
        $res = 'درخواست با موفقیت انجام شد.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doClosedLabelRequest(){
    $acm = new acm();
    if(!$acm->hasAccess('closedLabelRequest')){
        $res = "شما دسترسی بستن درخواست ها را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($lrid) = $uti->varCleanInput(array('lrid'));
    $res = $label->closedLabelRequest($lrid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doCreateLabelRequestExcel(){
    $ut = new Utility();
    $label = new Label();
    list($lrid) = $ut->varCleanInput(array('lrid'));
    $res = $label->createLabelRequestExcel($lrid);
    $hdarray = array('موجودی','کد برچسب','نام برچسب','کد کالا','تعداد');
    $fieldNames = array('status','pCode','pName','HPCode','number');
    $name = "labelRequestDetails".date("y-m-d");
    $url = $ut->createExcel($hdarray,$res,$fieldNames,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

//++++++++++++++++++++++ درخواست داده مهندسی +++++++++++++++++++++++

function showRenderingRequestList(){
    $acm = new acm();
    if(!$acm->hasAccess('renderingRequestManage')){
        die("access denied");
        exit;
    }
    $label = new Label();
    $list = new Listview();
    $ut = new Utility();
    list($piece,$page) = $ut->varCleanInput(array('piece','page'));
    $res = $label->getRenderingRequestManageList($piece,$page);
    if($page == 1){
        $_SESSION['calcRRManage'] = $label->getRenderingRequestManageListCountRows($piece);
    }
    $totalRows = $_SESSION['calcRRManage'];
    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نوع داده";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="type";
    $c++;

    $feilds[$c]['title']="کد قطعه";
    $feilds[$c]['width']="7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pCode";
    $c++;

    $feilds[$c]['title']="کد کالا";
    $feilds[$c]['width']="9%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="HPCode";
    $c++;

    $feilds[$c]['title']="نام قطعه";
    $feilds[$c]['width']="20%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pName";
    $c++;

    $feilds[$c]['title']="ثبت کننده";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="uid";
    $c++;

    $feilds[$c]['title']="تاریخ ایجاد";
    $feilds[$c]['width']="10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="cDate";
    $c++;

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']="13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="اطلاعات";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-tv";
    $feilds[$c]['onclick']="infoRenderingRequestAttachment";
    $feilds[$c]['manyColors']="yes";
    $c++;

    $feilds[$c]['title']="استفاده شده";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['onclick']="usedRenderingRequestAttachment";
    $feilds[$c]['manyColors']="yes";
    $c++;

    $feilds[$c]['title']="ضمیمه";
    $feilds[$c]['width']= "6%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-link";
    $feilds[$c]['onclick']="showRenderingRequestAttachment";
    $feilds[$c]['manyColors']="yes";

    $pagerType = 1;
    $listTitle = " تعداد درخواست ها : ".$totalRows." عدد ";
    $tableID = "renderingRequestManageBody-table";
    $jsf = "showRenderingRequestList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getRenderingRequestInfo(){
    $uti = new Utility();
    list($rid) = $uti->varCleanInput(array('rid'));
    if(!intval($rid)){
        $res = "شناسه درخواست بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $label = new Label();
    $res = $label->renderingRequestInfo($rid);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function checkRenderingRequest(){
    $uti = new Utility();
    $label = new Label();
    list($piece) = $uti->varCleanInput(array('piece'));
    $res = $label->checkRenderingRequest($piece);
    if($res){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateRenderingRequest(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateRenderingRequest')){
        $res = "شما دسترسی ثبت/ویرایش درخواست render را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($rid,$piece,$desc,$type) = $uti->varCleanInput(array('rid','piece','desc','type'));
    if(intval($rid) > 0){  //edit
        $res = $label->editRenderingRequest($rid,$piece,$desc,$type);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $label->createRenderingRequest($piece,$desc,$type);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

function getAttachedRenderingRequestFile(){
    $ut = new Utility();
    list($rid) = $ut->varCleanInput(array('rid'));
    if(!intval($rid)){
        $res = "شناسه درخواست بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $label = new Label();
    $res = $label->attachedRenderingRequestFileHtm($rid);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachFileToRenderingRequest(){
    $acm = new acm();
    if(!$acm->hasAccess('attachFileToRenderingRequest')){
        $res = "شما دسترسی پیوست فایل به درخواست را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $label = new Label();
    list($rid,$thicness,$size,$latin,$cartoonSize,$labels,$type) = $uti->varCleanInput(array('rid','thicness','size','latin','cartoonSize','labels','type'));
    $files = $_FILES['files'];
    $filesm = $_FILES['filesm'];
    $res = $label->attachFileToRenderingRequest($rid,$thicness,$size,$latin,$files,$cartoonSize,$labels,$type,$filesm);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function deleteAttachRenderingRequestFile(){
    $uti = new Utility();
    $label = new Label();
    list($rid,$type) = $uti->varCleanInput(array('rid','type'));
    $res = $label->deleteAttachRenderingRequestFile($rid,$type);
    if ($res){
        $res = 'با موفقیت حذف گردید.';
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = 'هیچ تغییری اعمال نگردید !!!';
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showRenderingRequestAttachment(){
    $ut = new Utility();
    list($rid) = $ut->varCleanInput(array('rid'));
    if(!intval($rid)){
        $res = "شناسه درخواست داده مهندسی بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $label = new Label();
    $res = $label->attachmentFileRenderingRequestHtm($rid);
    $out = "true";
    response($res,$out);
    exit;
}

function infoRenderingRequestAttachment(){
    $ut = new Utility();
    list($rid) = $ut->varCleanInput(array('rid'));
    if(!intval($rid)){
        $res = "شناسه درخواست داده مهندسی بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $label = new Label();
    $res = $label->infoRenderingRequestHtm($rid);
    $out = "true";
    response($res,$out);
    exit;
}

function usedRenderingRequestAttachment(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateRenderingRequest')){
        $res = "شما مجاز به این کار نیستید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $ut = new Utility();
    list($rid) = $ut->varCleanInput(array('rid'));
    if(!intval($rid)){
        $res = "شناسه درخواست داده مهندسی بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $label = new Label();
    $res = $label->usedRenderingRequestAttachment($rid);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getLabelPieceCodeWithName(){
    $label = new Label();
    $ut = new Utility();
    list($piece) = $ut->varCleanInput(array('piece'));
    $res = $label->getLabelPieceCode($piece);
    $out = "true";
    response($res,$out);
    exit;
}

function getLabelPieceNameWithCode(){
    $label = new Label();
    $ut = new Utility();
    list($pCode) = $ut->varCleanInput(array('pCode'));
    $res = $label->getLabelPieceName($pCode);
    $out = "true";
    response($res,$out);
    exit;
}

//++++++++++++++++++++++ مدیریت برنامه دستگاه ها +++++++++++++++++++++++

function devicesProgramManage(){
    $acm = new acm();
    if(!$acm->hasAccess('devicesProgramManage')){
        die("access denied");
        exit;
    }
    $device = new Device();
    $htm = $device->getDevicesProgramManageHtm();
    $res = $htm;
    $out = "true";
    response($res,$out);
    exit;
}

function showDevicesProgramManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('devicesProgramManage')){
        die("access denied");
        exit;
    }
    $device = new Device();
    $list = new Listview();
    $ut = new Utility();
    list($sDate,$eDate,$pName,$gName,$family,$deviceID,$page) = $ut->varCleanInput(array('sDate','eDate','pName','gName','family','deviceID','page'));
    $res = $device->getDevicesProgramManageList($sDate,$eDate,$pName,$gName,$family,$deviceID,$page);
    if($page == 1){
        $_SESSION['calcDevicesProgram'] = $device->getDevicesProgramManageListCountRows($sDate,$eDate,$pName,$gName,$family,$deviceID);
    }
    $totalRows = $_SESSION['calcDevicesProgram'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="نام و نام خانوادگی";
    $feilds[$c]['width']= "13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="Name";
    $c++;

    $feilds[$c]['title']="نام دستگاه";
    $feilds[$c]['width']= "13%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="deviceName";
    $c++;

    $feilds[$c]['title']="نام قطعه";
    $feilds[$c]['width']= "15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="pName";
    $c++;

    $feilds[$c]['title']="نام محصول";
    $feilds[$c]['width']= "15%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="gName";
    $c++;

    $feilds[$c]['title']="تاریخ";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="createDate";
    $c++;

    $feilds[$c]['title']="ساعت";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="createTime";
    $c++;

    $feilds[$c]['title']="توضیحات";
    $feilds[$c]['width']= "10%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="description";
    $c++;

    $feilds[$c]['title']="پیوست فایل";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-file";
    $feilds[$c]['onclick']="attachFileToDeviceProgram";
    $c++;

    $feilds[$c]['title']="ضمیمه ها";
    $feilds[$c]['width']= "7%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="btn";
    $feilds[$c]['param']="RowID";
    $feilds[$c]['icon']="fa-link";
    $feilds[$c]['onclick']="showAttachFileToDeviceProgram";

    $pagerType = 1;
    $listTitle = " تعداد برنامه ها : ".$totalRows." عدد ";
    $tableID = "devicesProgramManageBody-table";
    $jsf = "showDevicesProgramManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function doCreateDevicesProgram(){
    $acm = new acm();
    if(!$acm->hasAccess('devicesProgramManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $device = new Device();
    list($fname,$lname,$dName,$pName,$gName,$desc) = $uti->varCleanInput(array('fname','lname','dName','pName','gName','desc'));
    $res = $device->createDevicesProgram($fname,$lname,$dName,$pName,$gName,$desc);
    if($res){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function getAttachedDeviceProgramFile(){
    $ut = new Utility();
    list($dpID) = $ut->varCleanInput(array('dpID'));
    if(!intval($dpID)){
        $res = "شناسه برنامه دستگاه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $device = new Device();
    $res = $device->attachedDeviceProgramFileHtm($dpID);
    $out = "true";
    response($res,$out);
    exit;
}

function doAttachFileToDeviceProgram(){
    $acm = new acm();
    if(!$acm->hasAccess('devicesProgramManage')){
        die("access denied");
        exit;
    }
    $uti = new Utility();
    $device = new Device();
    list($dpID,$info) = $uti->varCleanInput(array('dpID','info'));
    $files = $_FILES['files'];
    $res = $device->attachFileToDeviceProgram($dpID,$info,$files);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function showDevicesManageList(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateDevices')){
        die("access denied");
        exit;
    }
    $device = new Device();
    $list = new Listview();
    $ut = new Utility();
    list($page) = $ut->varCleanInput(array('page'));
    $res = $device->getDevicesManageList($page);
    if($page == 1){
        $_SESSION['calcDevices'] = $device->getDevicesManageListCountRows();
    }
    $totalRows = $_SESSION['calcDevices'];

    $c = 0;
    $feilds = array();
    $feilds[$c]['title']="#";
    $feilds[$c]['width']="5%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="checkBox";
    $c++;

    $feilds[$c]['title']="نام دستگاه";
    $feilds[$c]['width']= "95%";
    $feilds[$c]['order']="none";
    $feilds[$c]['f']="deviceName";

    $pagerType = 1;
    $listTitle = " تعداد دستگاه ها : ".$totalRows." عدد ";
    $tableID = "devicesManageBody-table";
    $jsf = "showDevicesManageList";
    $htm = $list->creat($listTitle,$res,$feilds,$totalRows,$pagerType,$page,$tableID,$jsf);
    $out = "true";
    response($htm,$out);
    exit;
}

function getDeviceInfo(){
    $uti = new Utility();
    list($did) = $uti->varCleanInput(array('did'));
    if(!intval($did)){
        $res = "شناسه دستگاه بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $device = new Device();
    $res = $device->deviceInfo($did);
    if($res != false){
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "اطلاعات بدرستی دریافت نشد !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function doEditCreateDevices(){
    $acm = new acm();
    if(!$acm->hasAccess('editCreateDevices')){
        $res = "شما دسترسی ثبت/ویرایش دستگاه ها را ندارید !";
        $out = "false";
        response($res,$out);
        exit;
    }
    $uti = new Utility();
    $device = new Device();
    list($did,$name) = $uti->varCleanInput(array('did','name'));
    if(intval($did) > 0){  //edit
        $res = $device->editDevices($did,$name);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }else{  //create
        $res = $device->createDevices($name);
        if($res){
            $res = "درخواست با موفقیت انجام شد.";
            $out = "true";
            response($res,$out);
            exit;
        }else{
            $res = "هیچ تغییری اعمال نگردید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }
}

//++++++++++++++ خروجی اکسل BOM ++++++++++++++++++

function generalExcelBOM(){
    $acm = new acm();
    if(!$acm->hasAccess('excelexportBOM') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $industry = new Industry();
    $goods = $industry->getGoodsForGeneralExcelBOM();
    $Pieces = $industry->getPiecesForGeneralExcelBOM();
    $ColRow = $industry->getColRowForGeneralExcelBOM();
    $name = "GeneralBOM".date("y-m-d");
    $url = $ut->createBOMExcel($goods,$Pieces,$ColRow,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function generalExcelBOMFinancial(){
    $acm = new acm();
    if(!$acm->hasAccess('excelexportBOM') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $industry = new Industry();
    $goods = $industry->getGoodsForGeneralExcelBOM();
    $Pieces = $industry->getPiecesForGeneralExcelBOM();
    $ColRow = $industry->getColRowForGeneralExcelBOM();
    $name = "GeneralBOMFinancialReport".date("y-m-d");
    $url = $ut->createBOMFinancialExcel($goods,$Pieces,$ColRow,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function generalExcelBOMReport(){
    $acm = new acm();
    if(!$acm->hasAccess('excelexportBOM') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $good = new Good();
    $goods = $good->getGoodsForGeneralExcelBOM();
    $name = "GeneralBOMFinancial".date("y-m-d");
    $url = $ut->createBOMFinancialExcelReport($goods,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}

function pieceExcelBOMReport(){
    $acm = new acm();
    if(!$acm->hasAccess('excelexportBOM') ){
        die("access denied");
        exit;
    }
    $ut = new Utility();
    $piece = new Piece();
    $pieces = $piece->getPieceForGeneralExcelBOM();
    $name = "PieceBOMFinancial".date("y-m-d");
    //$url = $ut->getPieceForGeneralExcelBOM($pieces,$name);
    //$url=$ut->createBOMFinancialExcelReport($pieces,$name);
    $url=$ut->getPieceForGeneralExcelBOM($pieces,$name);
    if($url){
        //$res = "درخواست با موفقیت انجام شد";
        $out = "true";
        response($url,$out);
        exit;
    }else{
        $res = "امکان گرفتن خروجی وجود ندارد";
        $out = "false";
        response($res,$out);
        exit;
    }
}
//*************************************************************
function response($data,$output){
    $res = array();
    $res["data"] = $data;
    $res["res"]  = $output;
    $result = json_encode($res,true);
    die($result);
    exit;
}

function doCreateProceedingsComment(){
	$acm = new acm();
    if(!$acm->hasAccess('meetingManage')){
        die("access denied");
        exit;
    }
	$meeting= new Meeting();
	$res = $meeting->doCreateProceedingsComment($_POST['fmID'],$_POST['ProceedingsComment']);
	$ut = new Utility();
	//$ut->fileRecorder('*********'.print_r($res,true));
	die($res);
}
//*********************************************************************    مستندات سازمانی  ************************************ */
function save_organization_department(){
    $acm = new acm();
    $op=new OrganizationalProcedures();
    if(!$acm->hasAccess('add_organization_department')){
        die("access denied");
        exit;
    }
    $dep_name=$_POST['new_department_name'];
    $dep_desc=$_POST['new_department_desc'];
    $res=$op->save_organization_department($dep_name,$dep_desc);
    if(!empty($res)){
        if($res>0){
            $res="رویه با موفقیت  ذخیره شد";
        $out = "true";
        response($res,$out);
        }
        else{
            $out = "false";
            $res="خطا در ذخیره رویه";
                response($res,$out);
        }
    } 
}

function save_organization_procedure_type(){
    $acm = new acm();
    $op=new OrganizationalProcedures();
    $ut=new Utility();
    if(!$acm->hasAccess('add_organization_department')){
        die("access denied");
        exit;
    }
    $procedure_type=$_POST['new_procedure_type'];
    $procedure_type_desc=$_POST['new_procedure_type_desc'];
    $allow_admin_ids=$_POST['admin_ids'];
    //$ut->fileRecorder($_REQUEST);
    $res=$op->save_organization_procedure_type($procedure_type,$procedure_type_desc,$allow_admin_ids);
    if(!empty($res)){
        if($res>0){
            $res="رویه با موفقیت  ذخیره شد";
            $out = "true";
            response($res,$out);
            }
        else
        {
            $out = "false";
            $res="خطا در ذخیره رویه";
            response($res,$out);
        }
    } 
}

function save_procedure(){
    $ut=new Utility();
    $acm = new acm();
    $op=new OrganizationalProcedures();
    list($procedure_name,$unit_id,$procedure_type) = $ut->varCleanInput(array('procedure_name','unit_id','procedure_type'));
    if(!$acm->hasAccess('add_unit_prosedure')){
        die("access denied");
        exit;
    }
    error_log('procedure_type:');
    error_log($procedure_type);
    //die();
    $res=$op->save_unit_procedure($procedure_name,$unit_id,$procedure_type);
      if(!empty($res)){
        if($res>0){
        $out = "true";
        response($res,$out);
        }
        else{
             $out = "false";
             $res="خطا در ذخیره رویه";
                response($res,$out);
        }
    } 
}

function open_department_procedures(){
    $ut=new Utility();
    $op=new OrganizationalProcedures();
    list($unit_id) = $ut->varCleanInput(array('unit_id'));
    $res=$op->get_unit_procedures($unit_id);
      if(!empty($res)){
        $out = "true";
        response($res,$out);
    }

}

function organizationalProcedures(){
    $ut=new Utility();
    $op=new OrganizationalProcedures();
    $res=$op->OrganizationalProceduresHtml();
    if(!empty($res)){
        $out = "true";
        response($res,$out);
    }
}  
function SearchorganizationalProcedures(){
    $ut=new Utility();
    $op=new OrganizationalProcedures();
    $res=$op->SearchOrganizationalProceduresHtml();
    if(!empty($res)){
        $out = "true";
        response($res,$out);
    }
}  
 function CreatePublicOrganizationalProcedures(){
    $ut = new Utility();
    list($file_name,$file_code,$start_use_date,$desc) = $ut->varCleanInput(array('file_name','file_code','start_use_date','desc'));
    $op = new OrganizationalProcedures();
    $result=$op->CreatePublicOrganizationalProcedures($file_name,$file_code,$reg_date,$desc);
    if($result){
        $out = "true";
        $res="اطلاعات با موفقیت ذخیره شد";
        response($res,$out);
    }
   
 }  

 function add_users_download_procedure(){
    $ut=new Utility();
    $acm=new acm();
    if(!$acm->hasAccess('users_procedures_download_manang')){
        die("access denied");
        exit;
    }
    $pid=$_POST['pid'];
    $users_array=$_POST['users_array'];
    $op=new OrganizationalProcedures();
    $result=$op->add_users_download_procedure($pid,$users_array);
    if($result){
        $out = "true";
        $res=$result;
        response($res,$out);
    }
    else{
    $out = "false";
        $res="موردی یافت نشد";
        response($res,$out);
    }

 }

 function not_allow_download(){
    $ut=new Utility();
    $acm=new acm();
    $op=new OrganizationalProcedures();
    $p_id=$_POST['p_id'];
    $user_id=$_POST['user_id'];
    $result=$op->not_allow_download($user_id,$p_id);
    if($result){
        $out = "true";
        $res='کاربر با موفقیت غیر فعال شد';
        response($res,$out);
    }
    else
    {
        $out = "false";
        $res=$result;
        response($res,$out);
    }



 }

 function get_users_procedures_download_info(){
    $ut=new Utility();
    $acm=new acm();
    if(!$acm->hasAccess('users_procedures_download_manang')){
        die("access denied");
        exit;
    }
    $pid=$_POST['p_id'];
    $op=new OrganizationalProcedures();
    $result=$op->get_users_procedures_download_info($pid);
    if($result){
        $out = "true";
        $res=$result;
        response($res,$out);
    }

 }
   
 function manage_attach_procedure_file(){
    $ut = new Utility();
    
    list($procedure_id) = $ut->varCleanInput(array('procedure_id'));
    $op = new OrganizationalProcedures();
    $result=$op->manage_attach_procedure_file($procedure_id);
   // $ut->fileRecorder('pppppppppp:'.$result);
    if($result){
        $out = "true";
        $res=$result;
        response($res,$out);
    }
}

function save_procedure_file(){
   
    $ut = new Utility();
    $acm=new acm();
    if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    }
    $files = $_FILES['procedure_file'];
 
    list($p_id,$file_info,$file_name,$files,$start_date,$start_time,$start_now,$form_title,$file_number,$form_description) = $ut->varCleanInput(array('p_id','file_info','file_name','files','start_date','start_time','start_now','form_title','file_number','form_description'));
   // $ut->fileRecorder($files);
   // $ut->fileRecorder($p_id);
   // $ut->fileRecorder($info);
    $op = new OrganizationalProcedures();

    $res = $op->attach_procedure_file($p_id,$file_info,$file_name,$files,$start_date,$start_time,$start_now,$form_title,$file_number,$form_description);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
}

 function do_cancellation_procedure_file()
{
    $ut=new Utility();
    $acm=new acm();
    if(!$acm->hasAccess('remove_unit_prosedure_file')){
        die("access denied");
        exit;
    }
    list($remove_reason,$file_procedures_id) = $ut->varCleanInput(array('remove_reason','file_procedures_id'));
    $op=new organizationalProcedures();
   
    $result=$op->do_cancellation_procedure_file($remove_reason,$file_procedures_id);
    if($result){
        if($result>0){
            $out="true";
            $res="فایل با موفقیت ابطال گردید";
            response($res,$out);
            exit;

        }
    }
    else{
        $out="false";
            $res="تغییراتی اعمال نشد";
            response($res,$out);
            exit;
    }

}

 function open_procedure_history(){
    $ut=new Utility();
    list($procedure_id) = $ut->varCleanInput(array('procedure_id'));
    $op=new organizationalProcedures();
    $result=$op->get_procedure_history($procedure_id);
    if($result){
            $out="true";
            $res=$result;
            response($res,$out);
            exit;
    }
    else{
        $out="false";
            $res="تغییراتی اعمال نشد";
            response($res,$out);
            exit;
    }


 } 
 function get_all_active_users()
 {
    $ut=new Utility();
    $op=new organizationalProcedures();
    $result=$op->get_all_active_users();
    if($result){
            $out="true";
            $res=$result;
            response($res,$out);
            exit;
    }
    else{
        $out="false";
            $res="کاربری یافت نشد";
            response($res,$out);
            exit;
    }
 }
 function open_department_detailes(){
    $ut=new Utility();
    $op=new organizationalProcedures();
    $unit_id=$_POST['unit_id'];
    $unit_detailes=$op->get_unit_detailes($unit_id);
    //$ut->fileRecorder($unit_detailes);
    if(count($unit_detailes)>0){
        $out= 'true';
        $res=$unit_detailes;
        response($res,$out);
        exit;
    }
 }

 function get_procedures_list(){
    $ut=new Utility();
    $op=new organizationalProcedures();
    $unit_id=$_POST['unit_id'];
    $procedure_type=$_POST['procedure_type'];
    $page_number=$_POST['page_number'];

    $procedured_result=$op->get_procedures_list($unit_id,$procedure_type,$page_number);
   // $ut->fileRecorder('res:'.$procedured_result);
    if($procedured_result){
        $out= 'true';
        $res=$procedured_result[1];
        response($res,$out);
        exit;
    }
 }
 
 function search_unit_procedurs_users()
 {
    $ut=new Utility();
    $op=new organizationalProcedures();
    $procedure_name=$_POST['procedure_name'];
    $procedure_type=$_POST['procedure_type'];
    $from_date=$_POST['from_date'];
    $to_date=$_POST['to_date'];
    $form_number=$_POST['form_number'];
    $procedure_status=$_POST['procedure_status'];
    $unit_id=$_POST['unit_id'];
    $page_number=1;
    $procedure_result=$op->get_procedures_list($unit_id,$procedure_type,$page_number,$procedure_name,$from_date,$to_date,$form_number,$procedure_status);
     if($procedure_result)
     {
        $out= 'true';
        $res=$procedure_result;
        response($res,$out);
        exit;
    }
}
 function final_save_procedure(){
    $ut=new Utility();
    $files=$_FILES['procedures_file'];
    $acm=new acm();
    if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    }
    //********************************* */
   
    list($insert_mode,$p_id,$start_date,$start_time,$start_now,$form_number,$form_description,$reason_reversion,$changes_level,$current_procedure_status,$last_review_date) = $ut->varCleanInput(array('insert_mode','p_id','start_date','start_time','start_now','form_number','form_description','reason_reversion','changes_level','current_procedure_status','last_review_date'));
    $op = new OrganizationalProcedures();
    $files = $_FILES['procedures_file'];
    $file_info = $_POST['file_info'];
    $file_name = $_POST['file_name'];
    $uploaded_files_select=isset($_POST['uploaded_files'])?explode(",",$_POST['uploaded_files']):[];
  
   
    //$ut->fileRecorder($_REQUEST);
    
    $res = $op->attach_procedure_file($p_id,$file_info,$file_name,$files,$start_date,$start_time,$start_now,$form_number,$changes_level,$current_procedure_status,$last_review_date,$form_description,$insert_mode,$reason_reversion,$uploaded_files_select);
   
    //********************************* */
    //($p_id,$file_title,$info,$file_code) = $ut->varCleanInput(array('p_id','file_title','info','file_code'));
   
    // $ut->fileRecorder($files);
    // $ut->fileRecorder($p_id);
    // $ut->fileRecorder($info);
    // $op = new OrganizationalProcedures();
    // $res = $op->attach_procedure_file($p_id,$info,$file_title,$files,$file_code);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
 }

 function display_procedure_info()
 {
    $procedure_id=$_POST['procedure_id'];
    $ut=new Utility();
    $op=new organizationalProcedures();
    $procedure_result=$op->get_procedure_info($procedure_id);
    error_log($procedure_result);
    if($procedure_result){
        $out= 'true';
        $res=$procedure_result;
        response($res,$out);
        exit;
    }
}

function display_records_procedure(){
 $procedure_id=$_POST['procedure_id'];
    $ut=new Utility();
    $op=new organizationalProcedures();
    $procedure_result=$op->display_records_procedure($procedure_id);
   
    if($procedure_result){
        $out= 'true';
        $res=$procedure_result;
        response($res,$out);
        exit;
    }
    else{
        $out= 'false';
        $res="موردی یافت نشد";
        response($res,$out);
        exit;
    }


}

function do_delete_procedure()
{
    $acm = new acm();
    $ut = new Utility();
    $procedure_id = $_POST['procedure_id'];
    $desc = $_POST['reason_delete'];
    if(!$acm->hasAccess('delete_procedure_row'))
    {
        die("access denied");
        exit;
    }
    $op = new organizationalProcedures();
    $del_result = $op->delete_unit_procedure($procedure_id,$desc);
    // $ut->fileRecorder('del_result');
    // $ut->fileRecorder($del_result);
    if($del_result==0 ){
        $out= 'false';
        $res="تغییری اعمال نشد !";
        response($res,$out);
        exit;
    }
    elseif($del_result==-1 ){
        $out= 'false';
        $res=" تغییری اعمال نشد ! !";
        response($res,$out);
        exit;
    }
    elseif($del_result==-3 ){
      
        $out= 'false';
        $res=" شما ثبت کننده این رویه نمی باشید و مجوز تغییر  یا حذف آن را ندارید !!!";
        response($res,$out);
        exit;
    }
    elseif($del_result>0 ){
        $out= 'true';
        $res="رویه با موفقیت حذف شد";
        response($res,$out);
        exit;
    }
}

function get_procedures_manages(){
    $acm = new acm();
    $ut = new Utility();
    $op = new organizationalProcedures();
    $manager_results = $op->get_procedures_manages();
    
    if(count($manager_results)>0){
        $out= 'true';
        $res=$manager_results;
        response($res,$out);
        exit;
    }
   
}

function do_cancellation_procedure()
{
    $acm = new acm();
    $ut = new Utility();
    $procedure_id = $_POST['procedure_id'];
    $desc = $_POST['reason_cancellation'];
    if(!$acm->hasAccess('delete_procedure_row'))
    {
        die("access denied");
        exit;
    }
    $op = new organizationalProcedures();
    $del_result = $op->cancellation_unit_procedure($procedure_id,$desc);
   
    if($del_result==0 ){
        $out= 'false';
        $res="تغییری اعمال نشد !";
        response($res,$out);
        exit;
    }
    if($del_result==-1 ){
        $out= 'false';
        $res=" تغییری اعمال نشد ! !";
        response($res,$out);
        exit;
    }
    if($del_result==-3 ){
        $out= 'false';
        $res=" شما ثبت کننده این رویه نمی باشید و ومجوز تغییر آن را ندارید !!!";
        response($res,$out);
        exit;
    }
    if($del_result>0 ){
        $out= 'true';
        $res="رویه با موفقیت ابطال شد";
        response($res,$out);
        exit;
    }
}

function reversion_procedure(){
    $ut=new Utility();
    $procedure_id=$_POST['procedure_id'];
    $acm = new acm();
    if(!$acm->hasAccess('reversion_procedure'))
    {
        die("access denied");
        exit;
    }
    $op = new organizationalProcedures();
    $procedure_res=$op->reversion_procedure($procedure_id);
    if($procedure_res){
        if(is_array($procedure_res) && count($procedure_res)>0){
            $out= 'true';
            $res=$procedure_res ;
            response($res,$out);
            exit; 
        }
        elseif($procedure_res==-3){
            $out= 'false';
            $res=" شما ثبت کننده این رویه نمی باشید و ومجوز تغییر آن را ندارید !!!";
            response($res,$out);
            exit; 
        }
        else{
            $out= 'false';
            $res="موردی  یافت نشد  خطایی رخ داده است";
            response($res,$out);
            exit; 
        }
    }

}

function get_procedures_type(){
    $ut=new Utility();
   

    $op = new organizationalProcedures();
    $type_res=$op->get_procedures_type();
  
    if($type_res){
        if(count($type_res)>0){
            $out= 'true';
            $res=$type_res ;
            response($res,$out);
            exit; 
        }
        else{
            $out= 'false';
            $res="موردی  یافت نشد  خطایی رخ داده است";
            response($res,$out);
            exit; 
        }
    }
}

function get_procedure_history(){
    $ut=new Utility();
    $op = new organizationalProcedures();
    $get_childs=$op->get_procedure_history($_POST['procedure_id']);
    //$ut->fileRecorder($get_childs);
    if($get_childs){
        
        $out= 'true';
        $res=$get_childs ;
        response($res,$out);
        exit; 
    }
else
    {
        $out= 'false';
        $res="موردی  یافت نشد  خطایی رخ داده است";
        response($res,$out);
        exit; 
    }

}
function get_search_select_params(){
    $ut=new Utility();
    $op = new organizationalProcedures();
    $search_param_info=$op->get_search_select_params();
    //$ut->fileRecorder($get_childs);
    if(count($search_param_info)>0){
        
        $out= 'true';
        $res=$search_param_info ;
        response($res,$out);
        exit; 
    }
else
    {
        $out= 'false';
        $res="موردی  یافت نشد  خطایی رخ داده است";
        response($res,$out);
        exit; 
    }
}

function search_unit_procedurs(){
    $ut=new Utility();
    $op = new organizationalProcedures();
    list($unit,$procedure_name,$from_date,$to_date,$form_number,$procedure_status,$page_number,$procedure_type_admin)=$ut->varCleanInput(array('unit','procedure_name','from_date','to_date','form_number','procedure_status','page_number','procedure_type_admin'));
    //$ut->fileRecorder('number:'.$page_number);
    $html_res=$op->search_unit_procedurs($unit,$procedure_name,$from_date,$to_date,$form_number,$procedure_status,$page_number,$procedure_type_admin);
    if($html_res){
        $out= 'true';
        $res=$html_res ;
        response($res,$out);
        exit; 
    }
else
    {
        $out= 'false';
        $res="موردی  یافت نشد  خطایی رخ داده است";
        response($res,$out);
        exit; 
    }

}

function active_canceled_procedure(){
    //{action:action,reason_reactive:reason_reactive,reason_reactive:start_date,reason_reactive:start_time}
    $ut=new Utility();
    $op = new organizationalProcedures();
    list($reason_reactive,$start_date,$start_time,$start_now,$RowID,$current_procedure_status,$last_review_date,$level_of_changes)=$ut->varCleanInput(array('reason_reactive','start_date','start_time','start_now','RowID','current_procedure_status','last_review_date','level_of_changes'));
 
    $reaction_res=$op->reactive_procedure($reason_reactive,$start_date,$start_time,$start_now,$RowID,$current_procedure_status,$last_review_date,$level_of_changes);
    if($reaction_res){
        $out= 'true';
        $res="رویه با موفقیت فعالسازی شد";
        response($res,$out);
        exit; 
    }
else
    {
        $out= 'false';
        $res="  خطایی رخ داده است";
        response($res,$out);
        exit; 
    }

}

function manage_last_cancelled_version_procedure(){
    $acm=new acm();
    $is_admin=0;
    if($acm->hasAccess('manage_last_cancelled_version_procedure')){
        $is_admin=1;
    }
    
    $ut=new Utility();
    $op = new organizationalProcedures();
    $row_id=$_POST['row_id'];
    $old_version_res=$op->manage_last_cancelled_version_procedure($row_id);
    if($old_version_res){
        $out= 'true';
        $res=array($old_version_res,$is_admin);
        response($res,$out);
        exit; 
    }
else
    {
        $out= 'false';
        $res="  خطایی رخ داده است";
        response($res,$out);
        exit; 
    }

}

function uoload_last_cancelled_version_procedure_file(){
    $ut=new Utility();
    $op = new organizationalProcedures();
    $ut=new Utility();
    $files=$_FILES['procedures_file'];
    $acm=new acm();
    if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    }
    //********************************* */
   
   // list($insert_mode,$p_id,$start_date,$start_time,$start_now,$form_number,$form_description,$reason_reversion) = $ut->varCleanInput(array('insert_mode','p_id','start_date','start_time','start_now','form_number','form_description','reason_reversion'));
    $op = new OrganizationalProcedures();
    $files = $_FILES['procedures_file'];
    $file_info = $_POST['file_info'];
    $file_name = $_POST['file_name'];
    $display_file = $_POST['display_file'];
    $prosedure_id=$_POST['prosedure_id'];
    $display_files_array=isset($_POST['display_files_array'])?explode(",",$_POST['display_files_array']):[];
    $not_display_files_array=isset($_POST['not_display_files_array'])?explode(",",$_POST['not_display_files_array']):[];
    //$ut->fileRecorder($_POST);
    $res = $op->attach_cancelled_procedure_file($prosedure_id,$file_info,$file_name,$files,$display_files_array,$not_display_files_array,$display_file);
    if (intval($res) == -1){
        $res = "فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -2){
        $res = "سایز فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }elseif (intval($res) == -3){
        $res = "پسوند فایل ها مشکل دارند !";
        $out = "false";
        response($res,$out);
        exit;
    }
        elseif (intval($res) == -4){
            $res = "شما ثبت کننده این رویه نمی باشید و مج.وز تغییر آن را ندارید";
            $out = "false";
            response($res,$out);
            exit;
    }elseif($res == true){
        $res = "درخواست با موفقیت انجام شد.";
        $out = "true";
        response($res,$out);
        exit;
    }else{
        $res = "هیچ تغییری اعمال نگردید !";
        $out = "false";
        response($res,$out);
        exit;
    }
 
}
