<?php
/**
 * Created by PhpStorm.
 * User: Majid
 * Date: 12/31/2018
 * Time: 1:09 PM
 */

class Events{

    public function __construct(){
        // do nothing
    }

    public function calculateEndAgreement(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `personnel` WHERE CURDATE() >= (`EndDateContract` - INTERVAL 15 DAY) AND `isEnable`=1";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function showDoneAgreements(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `personnel` WHERE CURDATE() >= (`EndDateContract` - INTERVAL 15 DAY) AND `isEnable`=1";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="events-modal-table">';
        $htm .= '<thead>';
        $htm .= "<tr class='bg-secondary text-warning'>";
        $htm .= "<td style='display: none;'>&nbsp;</td>";
        $htm .= "<td style='width:5%;text-align: center;'>ردیف</td>";
		$htm .= "<td style='width:30%;text-align: center;'>نام</td>";
        $htm .= "<td style='width:12%;text-align: center;'>کد پرسنلی</td>";
        $htm .= "<td style='width:12%;text-align: center;'>تاریخ استخدام</td>";
		$htm .= "<td style='width:12%;text-align: center;'>تاریخ شروع قرارداد</td>";
		$htm .= "<td style='width:12%;text-align: center;'>تاریخ اتمام قرارداد</td>";
        $htm .= "<td style='width:17%;text-align: center;'>تاریخ تمدید قرارداد</td>";
        $htm .= "</tr>";
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for($y=0;$y<$Countevent;$y++){
            $iterator++;
			$beDate = (strtotime($res[$y]['BeginDateContract']) > 0 ? $ut->greg_to_jal($res[$y]['BeginDateContract']) : '');
			$enDate = (strtotime($res[$y]['EndDateContract']) > 0 ? $ut->greg_to_jal($res[$y]['EndDateContract']) : '');
			$RDate = (strtotime($res[$y]['RecruitmentDate']) > 0 ? $ut->greg_to_jal($res[$y]['RecruitmentDate']) : '');
            $htm .= "<tr class='table-secondary'>";
            $htm .= "<td style='display: none;' ><input type='checkbox' rid='".$iterator."' checked disabled>&nbsp;</td>";
            $htm .= "<td style='text-align: center;'>".$iterator."</td>";
			$htm .= "<td style='text-align: center;'>".$res[$y]['Fname']." ".$res[$y]['Lname']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['PersonnelCode']."</td>";
            $htm .= "<td style='text-align: center;'>".$RDate."</td>";
			$htm .= "<td style='text-align: center;'>".$beDate."</td>";
			$htm .= "<td style='text-align: center;'>".$enDate."</td>";
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="ContractExtensionDate-'.$iterator.'" /><input type="hidden" id="pid-'.$iterator.'-Hidden" value="'.$res[$y]['RowID'].'" /></td>';
            $htm .="</tr>";
        }
        $htm .= '</tbody>';
        $htm .= "</table>";
        //++++++++++++++END TABLE ++++++++++++++++
        return $htm;
    }

    public function doContractExtension($myJsonString){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("access denied");
            exit;
        }
        $countJS = count($myJsonString);
        $flag = true;
        $db = new DBi();
        $ut = new Utility();
        mysqli_autocommit($db->Getcon(),FALSE);
        for($i=0;$i<$countJS;$i++){
            $cDate = $ut->jal_to_greg($myJsonString[$i][0]);  // cDate
            $lengthCdate = strlen($cDate);  // cDate Length
            $pid = intval($myJsonString[$i][1]);  // pid
            if(intval($lengthCdate) == 0){
                continue;
            }
            $sql = "UPDATE `personnel` SET `EndDateContract`='{$cDate}' WHERE `RowID`={$pid}";
            $db->Query($sql);
            $res1 = $db->AffectedRows();
            $res1 = ($res1 == -1 ? 0 : 1);
            if(!intval($res1)){
                $flag = false;
            }
        }
        if($flag){
            mysqli_commit($db->Getcon());
            return true;
        }else{
            mysqli_rollback($db->Getcon());
            return false;
        }
    }

    //++++++++++++++++++++++ هشدار سررسید پرداخت +++++++++++++++++++++++

    public function calculatePaymentMaturity(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        if ($acm->hasAccess('forjAccess')) {
            $sql = "SELECT `RowID` FROM `pay_comment` WHERE (CURDATE() >= (`paymentMaturityCash` - INTERVAL 7 DAY) OR CURDATE() >= (`paymentMaturityCheck` - INTERVAL 7 DAY)) AND `transfer`=1 AND `payType`=0 ";
        }elseif ($acm->hasAccess('sahamiAccess')){
            $sql = "SELECT `RowID` FROM `pay_comment` WHERE (CURDATE() >= (`paymentMaturityCash` - INTERVAL 7 DAY) OR CURDATE() >= (`paymentMaturityCheck` - INTERVAL 7 DAY)) AND `transfer`=1 AND `payType`=1 ";
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function showPaymentMaturity(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        if ($acm->hasAccess('forjAccess')) {
            $sql = "SELECT `unCode`,`cDate`,`CashSection`,`NonCashSection`,`Toward`,`accName`,`paymentMaturityCash`,`paymentMaturityCheck` FROM `pay_comment` WHERE (CURDATE() >= (`paymentMaturityCash` - INTERVAL 7 DAY) OR CURDATE() >= (`paymentMaturityCheck` - INTERVAL 7 DAY)) AND `transfer`=1 AND `payType`=0  ";

        }elseif ($acm->hasAccess('sahamiAccess')){
            $sql = "SELECT `unCode`,`cDate`,`CashSection`,`NonCashSection`,`Toward`,`accName`,`paymentMaturityCash`,`paymentMaturityCheck` FROM `pay_comment` WHERE (CURDATE() >= (`paymentMaturityCash` - INTERVAL 7 DAY) OR CURDATE() >= (`paymentMaturityCheck` - INTERVAL 7 DAY)) AND `transfer`=1 AND `payType`=1 ";
        }
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="showPaymentMaturity-table">';
        $htm .= '<thead>';
        $htm .= "<tr class='bg-info'>";
        $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
        $htm .= "<td style='color: #ffc107;width:12%;text-align: center;'>شماره یکتا</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تاریخ ثبت</td>";
        $htm .= "<td style='color: #ffc107;width:12%;text-align: center;'>نقدی</td>";
        $htm .= "<td style='color: #ffc107;width:12%;text-align: center;'>غیر نقدی</td>";
        $htm .= "<td style='color: #ffc107;width:12%;text-align: center;'>در وجه</td>";
        $htm .= "<td style='color: #ffc107;width:17%;text-align: center;'>بابت</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>سررسید نقدی</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>سررسید چک</td>";
        $htm .= "</tr>";
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for($y=0;$y<$Countevent;$y++){
            $iterator++;
            $cDate = $ut->greg_to_jal($res[$y]['cDate']);
            $paymentMaturityCash = (strtotime($res[$y]['paymentMaturityCash']) > 0 ? $ut->greg_to_jal($res[$y]['paymentMaturityCash']) : '');
            $paymentMaturityCheck = (strtotime($res[$y]['paymentMaturityCheck']) > 0 ? $ut->greg_to_jal($res[$y]['paymentMaturityCheck']) : '');
            $htm .= "<tr class='table-secondary'>";
            $htm .= "<td style='text-align: center;'>".$iterator."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['unCode']."</td>";
            $htm .= "<td style='text-align: center;'>".$cDate."</td>";
            $htm .= "<td style='text-align: center;'>".number_format($res[$y]['CashSection'])." ریال</td>";
            $htm .= "<td style='text-align: center;'>".number_format($res[$y]['NonCashSection'])." ریال</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['accName']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['Toward']."</td>";
            $htm .= "<td style='text-align: center;'>".$paymentMaturityCash."</td>";
            $htm .= "<td style='text-align: center;'>".$paymentMaturityCheck."</td>";
            $htm .="</tr>";
        }
        $htm .= '</tbody>';
        $htm .= "</table>";
        //++++++++++++++END TABLE ++++++++++++++++
        return $htm;
    }

    //++++++++++++++++++++++ هشدار ثبت اظهارنظر برای قرارداد +++++++++++++++++++++++

    public function calculateRecordPayComment(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `contract_dates` WHERE `status`=1 AND CURDATE() >= `commentDate`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function showRecordPayCommentAlarmModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `number`,`accountName`,`totalAmount`,`commentDate` FROM `contract` INNER JOIN `contract_dates` ON (`contract`.`RowID`=`contract_dates`.`cid`) WHERE `status`=1 AND CURDATE() >= `commentDate` ";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="showRecordPayCommentAlarmModal-table">';
        $htm .= '<thead>';
        $htm .= "<tr class='bg-info'>";
        $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
        $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>شماره قرارداد</td>";
        $htm .= "<td style='color: #ffc107;width:12%;text-align: center;'>طرف مقابل</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>مبلغ کل قرارداد</td>";
        $htm .= "<td style='color: #ffc107;width:12%;text-align: center;'>تاریخ ثبت اظهارنظر</td>";
        $htm .= "</tr>";
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for($y=0;$y<$Countevent;$y++){
            $iterator++;
            $cDate = $ut->greg_to_jal($res[$y]['commentDate']);
            $htm .= "<tr class='table-secondary'>";
            $htm .= "<td style='text-align: center;'>".$iterator."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['number']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['accountName']."</td>";
            $htm .= "<td style='text-align: center;'>".number_format($res[$y]['totalAmount'])." ریال</td>";
            $htm .= "<td style='text-align: center;'>".$cDate."</td>";
            $htm .="</tr>";
        }
        $htm .= '</tbody>';
        $htm .= "</table>";
        //++++++++++++++END TABLE ++++++++++++++++
        return $htm;
    }

    //++++++++++++++++++++++ هشدار دریافت چک برگشتی +++++++++++++++++++++++

    public function calculateReturnedCheck(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `pay_comment` WHERE `layer1`=24 AND `checkCarcass`=0 AND CURDATE() >= `checkDeliveryDate`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function showReturnedCheckAlarmModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `unCode`,`CashSection`,`Toward`,`accName`,`checkNumber`,`checkDate`,`checkDeliveryDate` FROM `pay_comment` WHERE `layer1`=24 AND `checkCarcass`=0 AND CURDATE() >= `checkDeliveryDate`";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="showReturnedCheckAlarmModal-table">';
        $htm .= '<thead>';
        $htm .= "<tr class='bg-info'>";
        $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
        $htm .= "<td style='color: #ffc107;width:14%;text-align: center;'>شماره یکتا</td>";
        $htm .= "<td style='color: #ffc107;width:14%;text-align: center;'>نقدی</td>";
        $htm .= "<td style='color: #ffc107;width:14%;text-align: center;'>در وجه</td>";
        $htm .= "<td style='color: #ffc107;width:19%;text-align: center;'>بابت</td>";
        $htm .= "<td style='color: #ffc107;width:14%;text-align: center;'>شماره چک</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تاریخ چک</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تاریخ تحویل چک</td>";
        $htm .= "</tr>";
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for($y=0;$y<$Countevent;$y++){
            $iterator++;
            $checkDate = $ut->greg_to_jal($res[$y]['checkDate']);
            $checkDeliveryDate = $ut->greg_to_jal($res[$y]['checkDeliveryDate']);
            $htm .= "<tr class='table-secondary'>";
            $htm .= "<td style='text-align: center;'>".$iterator."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['unCode']."</td>";
            $htm .= "<td style='text-align: center;'>".number_format($res[$y]['CashSection'])." ریال</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['accName']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['Toward']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['checkNumber']."</td>";
            $htm .= "<td style='text-align: center;'>".$checkDate."</td>";
            $htm .= "<td style='text-align: center;'>".$checkDeliveryDate."</td>";
            $htm .="</tr>";
        }
        $htm .= '</tbody>';
        $htm .= "</table>";
        //++++++++++++++END TABLE ++++++++++++++++
        return $htm;
    }

    //++++++++++++++++++++++ آلارم بازنگری آئین نامه ها +++++++++++++++++++++++

    public function calculateRegulationsAlarm(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `regulations` WHERE (CURDATE() >= (`endDate` - INTERVAL 7 DAY)) AND `isEnable`=1 ";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function showRegulationsAlarmModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `regulations` WHERE (CURDATE() >= (`endDate` - INTERVAL 7 DAY)) AND `isEnable`=1 ";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="events-regulations-modal-table">';
        $htm .= '<thead>';
        $htm .= "<tr class='bg-info'>";
        $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تاریخ شروع</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تاریخ پایان</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>نام فایل</td>";
        $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>کد فایل</td>";
        $htm .= "<td style='color: #ffc107;width:40%;text-align: center;'>توضیحات</td>";
        $htm .= "</tr>";
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for($y=0;$y<$Countevent;$y++){
            $iterator++;
            $startDate = $ut->greg_to_jal($res[$y]['startDate']);
            $endDate = $ut->greg_to_jal($res[$y]['endDate']);
            $htm .= "<tr class='table-secondary'>";
            $htm .= "<td style='text-align: center;'>".$iterator."</td>";
            $htm .= "<td style='text-align: center;'>".$startDate."</td>";
            $htm .= "<td style='text-align: center;'>".$endDate."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['Name']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['Code']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['description']."</td>";
            $htm .="</tr>";
        }
        $htm .= '</tbody>';
        $htm .= "</table>";
        //++++++++++++++END TABLE ++++++++++++++++
        return $htm;
    }

    //++++++++++++++++++++++ آلارم بازنگری بخش نامه ها +++++++++++++++++++++++

    public function calculateCircularsAlarm(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `procedures` WHERE procedure_type=6 AND `status`=1 and (CURDATE() >= (`end_date` - INTERVAL 7 DAY))  ";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function calculateAddendumLegalConfirmAlaram(){
        $acm = new acm();
        $ut=new Utility();
        $ut->fileRecorder('test');
        if(!$acm->hasAccess('addendum_legal_confirm')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `contract_addendum` where `status`=1 and `addendum_status`=1 ";
        $ut->fileRecorder($sql);
        $res = $db->ArrayQuery($sql);
        return count($res); 
    }

    public function calculateAddendumLFinalConfirmAlaram(){
        $acm = new acm();
        if(!$acm->hasAccess('addendum_final_confirm')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `contract_addendum` where `status`=1 and `addendum_status`=2 ";
        $res = $db->ArrayQuery($sql);
        return count($res); 
    }

    public function showCircularsAlarmModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
       // $sql = "SELECT * FROM `circulars` WHERE (CURDATE() >= (`endDate` - INTERVAL 7 DAY)) AND `isEnable`=1 ";
        $sql = "SELECT * FROM `procedures` WHERE procedure_type=6 AND `status`=1 and (CURDATE() >= (`end_date` - INTERVAL 7 DAY)) ";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        if($Countevent>0){
            $iterator = 0;
            $htm = '';
            $htm .= '<table class="table table-bordered table-hover table-sm" id="events-circulars-modal-table">';
            $htm .= '<thead>';
            $htm .= "<tr class='bg-info'>";
            $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
            $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تاریخ شروع</td>";
            $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تاریخ پایان</td>";
            $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>نام فایل</td>";
            $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>کد فایل</td>";
            $htm .= "<td style='color: #ffc107;width:40%;text-align: center;'>توضیحات</td>";
            $htm .= "</tr>";
            $htm .= '</thead>';
            $htm .= '<tbody>';
            for($y=0;$y<$Countevent;$y++){
                $iterator++;
                $startDate = $ut->greg_to_jal($res[$y]['start_date']);
                $endDate = $ut->greg_to_jal($res[$y]['end_date']);
                $htm .= "<tr class='table-secondary'>";
                $htm .= "<td style='text-align: center;'>".$iterator."</td>";
                $htm .= "<td style='text-align: center;'>".$startDate."</td>";
                $htm .= "<td style='text-align: center;'>".$endDate."</td>";
                $htm .= "<td style='text-align: center;'>".$res[$y]['procedure_name']."</td>";
                $htm .= "<td style='text-align: center;'>".$res[$y]['form_number']."</td>";
                $htm .= "<td style='text-align: center;'>".$res[$y]['description']."</td>";
                $htm .="</tr>";
            }
            $htm .= '</tbody>';
            $htm .= "</table>";
            //++++++++++++++END TABLE ++++++++++++++++
            return $htm;
        }
        else{
            return false;
        }
    }

    public function showWarrantyAlarmModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `sale_warranty` WHERE  `status`=1 and (CURDATE() >= (`end_date` - INTERVAL 30 DAY)) ";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        if($Countevent>0){
            $iterator = 0;
            $htm = '';
            $htm .= '<table class="table table-bordered " id="events-warranty-modal-table">';
            $htm .= '<thead>';
            $htm .= "<tr>";
            $htm .= "<td style='width:5%;text-align: center;'>ردیف</td>";
            $htm .= "<td style='width:10%;text-align: center;'>نوع تضمین</td>";
            $htm .= "<td style='width:10%;text-align: center;'>تاریخ شروع</td>";
            $htm .= "<td style='width:10%;text-align: center;'>تاریخ پایان</td>";
            $htm .= "<td style='width:15%;text-align: center;'>عنوان تضمین</td>";
            $htm .= "<td style='width:20%;text-align: center;'>نام طرف حساب </td>";
            $htm .= "<td style='width:30%;text-align: center;'>توضیحات</td>";
            $htm .= "</tr>";
            $htm .= '</thead>';
            $htm .= '<tbody>';
            for($y=0;$y<$Countevent;$y++){

                switch($res[$y]['warranty_type']){
                    case "1":
                        $warranty_type="چک";
                        break;
                    case "2":
                        $warranty_type="سفته";
                        break;
                    case "3":
                        $warranty_type="سایر";
                        break;
                }
                $iterator++;
                $start_date = $ut->greg_to_jal($res[$y]['start_date']);
                $end_date = $ut->greg_to_jal($res[$y]['end_date']);
                $htm .= "<tr>";
                $htm .= "<td style='text-align: center;'>".$iterator."</td>";
                $htm .= "<td style='text-align: center;'>".$warranty_type."</td>";
                $htm .= "<td style='text-align: center;'>".$start_date."</td>";
                $htm .= "<td style='text-align: center;'>".$end_date."</td>";
                $htm .= "<td style='text-align: center;'>".$res[$y]['warranty_title']."</td>";
                $htm .= "<td style='text-align: center;'>".$this->get_account_name($res[$y]['account_id'])."</td>";
                $htm .= "<td style='text-align: center;'>".$res[$y]['desc']."</td>";
                $htm .="</tr>";
            }
            $htm .= '</tbody>';
            $htm .= "</table>";
            return array($Countevent,$htm);
        }
        else{
            return false;
        }
    }

    //++++++++++++++++++++++ اتمام قراردادهای حقوقی +++++++++++++++++++++++
    public function get_account_name ($accunt_id){
        $db=new DBi();
        $sql="SELECT `Name` FROM `account` where RowID={$accunt_id}";
        $res=$db->ArrayQuery($sql);
        return $res[0]['Name'];
    }
    public function calculateEndLegalContract(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `legal_contracts` WHERE (CURDATE() >= (`EndDateContract` - INTERVAL 7 DAY)) AND `isEnable`=1 ";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function showEndLegalContractModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `legal_contracts` WHERE (CURDATE() >= (`EndDateContract` - INTERVAL 7 DAY)) AND `isEnable`=1 ";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="events-legal_contracts-modal-table">';
        $htm .= '<thead>';
        $htm .= "<tr class='bg-info'>";
        $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تاریخ شروع</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تاریخ پایان</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>شماره قرارداد</td>";
        $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>موضوع قرارداد</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>طرف اول</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>طرف دوم</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>مدت قرارداد</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>مبلغ قرارداد</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تضامین</td>";
        $htm .= "</tr>";
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for($y=0;$y<$Countevent;$y++){
            $iterator++;
            $startDate = $ut->greg_to_jal($res[$y]['BeginDateContract']);
            $endDate = $ut->greg_to_jal($res[$y]['EndDateContract']);
            $htm .= "<tr class='table-secondary'>";
            $htm .= "<td style='text-align: center;'>".$iterator."</td>";
            $htm .= "<td style='text-align: center;'>".$startDate."</td>";
            $htm .= "<td style='text-align: center;'>".$endDate."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['ContractID']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['subjectContract']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['sideOne']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['sideTwo']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['Term_contract']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['Amount']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['description']."</td>";
            $htm .="</tr>";
        }
        $htm .= '</tbody>';
        $htm .= "</table>";
        //++++++++++++++END TABLE ++++++++++++++++
        return $htm;
    }

    //++++++++++++++++++++++ اتمام معاینه فنی +++++++++++++++++++++++

    public function calculateTechnicalDiag(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `car_info` WHERE (CURDATE() >= (`technicalDiagDate` - INTERVAL 7 DAY)) ";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function showEndOfTechnicalDiagModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `car_info` WHERE (CURDATE() >= (`technicalDiagDate` - INTERVAL 7 DAY)) ";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="events-TechnicalDiag-modal-table">';
        $htm .= '<thead>';
        $htm .= "<tr class='bg-info'>";
        $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>نام ماشین</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>شماره پلاک</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>شماره شاسی</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>شماره سریال</td>";
        $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>تاریخ پایان اعتبار</td>";
        $htm .= "</tr>";
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for($y=0;$y<$Countevent;$y++){
            $iterator++;
            $TdDate = $ut->greg_to_jal($res[$y]['technicalDiagDate']);
            $htm .= "<tr class='table-secondary'>";
            $htm .= "<td style='text-align: center;'>".$iterator."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['carName']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['plaque']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['chassis']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['serial']."</td>";
            $htm .= "<td style='text-align: center;'>".$TdDate."</td>";
            $htm .="</tr>";
        }
        $htm .= '</tbody>';
        $htm .= "</table>";
        //++++++++++++++END TABLE ++++++++++++++++
        return $htm;
    }

    //++++++++++++++++++++++ اتمام بیمه شخص ثالث +++++++++++++++++++++++

    public function calculateThirdInsurance(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `car_info` WHERE (CURDATE() >= (`insuranceDate` - INTERVAL 7 DAY)) ";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function showEndOfThirdInsuranceModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `car_info` WHERE (CURDATE() >= (`insuranceDate` - INTERVAL 7 DAY)) ";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="events-ThirdInsurance-modal-table">';
        $htm .= '<thead>';
        $htm .= "<tr class='bg-info'>";
        $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>نام ماشین</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>شماره پلاک</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>شماره شاسی</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>شماره سریال</td>";
        $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>تاریخ پایان اعتبار</td>";
        $htm .= "</tr>";
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for($y=0;$y<$Countevent;$y++){
            $iterator++;
            $TiDate = $ut->greg_to_jal($res[$y]['insuranceDate']);
            $htm .= "<tr class='table-secondary'>";
            $htm .= "<td style='text-align: center;'>".$iterator."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['carName']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['plaque']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['chassis']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['serial']."</td>";
            $htm .= "<td style='text-align: center;'>".$TiDate."</td>";
            $htm .="</tr>";
        }
        $htm .= '</tbody>';
        $htm .= "</table>";
        //++++++++++++++END TABLE ++++++++++++++++
        return $htm;
    }

    //++++++++++++++++++++++ اتمام بیمه بدنه +++++++++++++++++++++++

    public function calculateBodyInsurance(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `car_info` WHERE (CURDATE() >= (`insuranceBodyDate` - INTERVAL 7 DAY)) ";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function showEndOfBodyInsuranceModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `car_info` WHERE (CURDATE() >= (`insuranceBodyDate` - INTERVAL 7 DAY)) ";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="events-insuranceBody-modal-table">';
        $htm .= '<thead>';
        $htm .= "<tr class='bg-info'>";
        $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>نام ماشین</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>شماره پلاک</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>شماره شاسی</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>شماره سریال</td>";
        $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>تاریخ پایان اعتبار</td>";
        $htm .= "</tr>";
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for($y=0;$y<$Countevent;$y++){
            $iterator++;
            $BiDate = $ut->greg_to_jal($res[$y]['insuranceBodyDate']);
            $htm .= "<tr class='table-secondary'>";
            $htm .= "<td style='text-align: center;'>".$iterator."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['carName']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['plaque']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['chassis']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['serial']."</td>";
            $htm .= "<td style='text-align: center;'>".$BiDate."</td>";
            $htm .="</tr>";
        }
        $htm .= '</tbody>';
        $htm .= "</table>";
        //++++++++++++++END TABLE ++++++++++++++++
        return $htm;
    }

    //++++++++++++++++++++++ بروزرسانی کیلومتر ماشین +++++++++++++++++++++++

    public function calculateUpdateKilometer(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `car_info` WHERE (CURDATE() >= (`lastKilometerDate` + INTERVAL 1 MONTH )) AND `carType`=1 ";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function showUpdateKilometerAlarmModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `car_info` WHERE (CURDATE() >= (`lastKilometerDate` + INTERVAL 1 MONTH )) AND `carType`=1 ";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="showUpdateKilometerAlarmModal-table">';
        $htm .= '<thead>';
        $htm .= "<tr class='bg-info'>";
        $htm .= "<td style='display: none;'>&nbsp;</td>";
        $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
        $htm .= "<td style='color: #ffc107;width:13%;text-align: center;'>نام ماشین</td>";
        $htm .= "<td style='color: #ffc107;width:13%;text-align: center;'>شماره پلاک</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>شماره شاسی</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>شماره سریال</td>";
        $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>تاریخ اخرین کیلومتر</td>";
        $htm .= "<td style='color: #ffc107;width:14%;text-align: center;'>آخرین کیلومتر</td>";
        $htm .= "</tr>";
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for($y=0;$y<$Countevent;$y++){
            $iterator++;
            $lkDate = $ut->greg_to_jal($res[$y]['lastKilometerDate']);
            $htm .= "<tr class='table-secondary'>";
            $htm .= "<td style='display: none;' ><input type='checkbox' rid='".$iterator."' checked disabled>&nbsp;</td>";
            $htm .= "<td style='text-align: center;'>".$iterator."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['carName']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['plaque']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['chassis']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['serial']."</td>";
            $htm .= "<td style='text-align: center;'>".$lkDate."</td>";
            $htm .= "<td style='text-align: center;'><input type='text' class='form-control' id='newLastKilometer-".$iterator."' value='".$res[$y]['lastKilometer']."' /><input type='hidden' id='caid-".$iterator."-Hidden' value='".$res[$y]['RowID']."' /></td>";
            $htm .="</tr>";
        }
        $htm .= '</tbody>';
        $htm .= "</table>";
        //++++++++++++++END TABLE ++++++++++++++++
        return $htm;
    }

    //++++++++++++++++++++++ تعویض مواد مصرفی ماشین +++++++++++++++++++++++

    public function calculateConsumingMaterials(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID`,`lastKilometer`,`carName`,`plaque` FROM `car_info`";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $x = 0;
        $km_alert=300;
        if($_SESSION['userid']==4){
            $km_alert=0;
        }
        for ($i=0;$i<$cnt;$i++){
            $sql1 = "SELECT `nextKm`,`materialID` FROM `consuming_materials` WHERE `materialID`=1 AND `carID`={$res[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
            $rst = $db->ArrayQuery($sql1);
            if (count($rst) > 0) {
                if ((intval($rst[0]['nextKm']) - intval($res[$i]['lastKilometer'])) <= $km_alert) {
                    $x++;
                }
            }
            $sql2 = "SELECT `nextKm`,`materialID` FROM `consuming_materials` WHERE `materialID`=2 AND `carID`={$res[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
            $rst2 = $db->ArrayQuery($sql2);
            if (count($rst2) > 0) {
                if ((intval($rst2[0]['nextKm']) - intval($res[$i]['lastKilometer'])) <=$km_alert) {
                    $x++;
                }
            }
            $sql3 = "SELECT `nextKm`,`materialID` FROM `consuming_materials` WHERE `materialID`=3 AND `carID`={$res[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
            $rst3 = $db->ArrayQuery($sql3);
            if (count($rst3) > 0) {
                if ((intval($rst3[0]['nextKm']) - intval($res[$i]['lastKilometer'])) <= $km_alert) {
                    $x++;
                }
            }
            $sql4 = "SELECT `nextKm`,`materialID` FROM `consuming_materials` WHERE `materialID`=4 AND `carID`={$res[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
            $rst4 = $db->ArrayQuery($sql4);
            if (count($rst4) > 0) {
                if ((intval($rst4[0]['nextKm']) - intval($res[$i]['lastKilometer'])) <= $km_alert) {
                    $x++;
                }
            }
            $sql5 = "SELECT `nextKm`,`materialID` FROM `consuming_materials` WHERE `materialID`=5 AND `carID`={$res[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
            $rst5 = $db->ArrayQuery($sql5);
            if (count($rst5) > 0) {
                if ((intval($rst5[0]['nextKm']) - intval($res[$i]['lastKilometer'])) <= $km_alert) {
                    $x++;
                }
            }
        }
        return $x;
    }

    function showConsumingMaterialsModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID`,`lastKilometer`,`carName`,`plaque` FROM `car_info`";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $x = 0;
        $arr = array();
		$km_alert=300;
        $alert_color="#000";
        if($_SESSION['userid']==4){//-----------  در صورتی که زمان  تعویض گذشته باشد  فرم نمایش داده شود 
            $km_alert=0;
            $alert_color="red";
        }
        for ($i=0;$i<$cnt;$i++){
            $sql1 = "SELECT `nextKm`,`materialID` FROM `consuming_materials` WHERE `materialID`=1 AND `carID`={$res[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
            $rst = $db->ArrayQuery($sql1);
            if (count($rst) > 0) {
                if ((intval($rst[0]['nextKm']) - intval($res[$i]['lastKilometer'])) <= $km_alert) {
                    $arr[$x]['carName'] = $res[$i]['carName'];
                    $arr[$x]['plaque'] = $res[$i]['plaque'];
                    $arr[$x]['lastKilometer'] = $res[$i]['lastKilometer'];
                    $arr[$x]['nextKm'] = $rst[0]['nextKm'];
                    $arr[$x]['materialID'] = $rst[0]['materialID'];
                    $x++;
                }
            }
            $sql2 = "SELECT `nextKm`,`materialID` FROM `consuming_materials` WHERE `materialID`=2 AND `carID`={$res[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
            $rst2 = $db->ArrayQuery($sql2);
            if (count($rst2) > 0) {
                if ((intval($rst2[0]['nextKm']) - intval($res[$i]['lastKilometer'])) <= $km_alert) {
                    $arr[$x]['carName'] = $res[$i]['carName'];
                    $arr[$x]['plaque'] = $res[$i]['plaque'];
                    $arr[$x]['lastKilometer'] = $res[$i]['lastKilometer'];
                    $arr[$x]['nextKm'] = $rst2[0]['nextKm'];
                    $arr[$x]['materialID'] = $rst2[0]['materialID'];
                    $x++;
                }
            }
            $sql3 = "SELECT `nextKm`,`materialID` FROM `consuming_materials` WHERE `materialID`=3 AND `carID`={$res[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
            $rst3 = $db->ArrayQuery($sql3);
            if (count($rst3) > 0) {
                if ((intval($rst3[0]['nextKm']) - intval($res[$i]['lastKilometer'])) <= $km_alert) {
                    $arr[$x]['carName'] = $res[$i]['carName'];
                    $arr[$x]['plaque'] = $res[$i]['plaque'];
                    $arr[$x]['lastKilometer'] = $res[$i]['lastKilometer'];
                    $arr[$x]['nextKm'] = $rst3[0]['nextKm'];
                    $arr[$x]['materialID'] = $rst3[0]['materialID'];
                    $x++;
                }
            }
            $sql4 = "SELECT `nextKm`,`materialID` FROM `consuming_materials` WHERE `materialID`=4 AND `carID`={$res[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
            $rst4 = $db->ArrayQuery($sql4);
            if (count($rst4) > 0) {
                if ((intval($rst4[0]['nextKm']) - intval($res[$i]['lastKilometer'])) <= $km_alert) {
                    $arr[$x]['carName'] = $res[$i]['carName'];
                    $arr[$x]['plaque'] = $res[$i]['plaque'];
                    $arr[$x]['lastKilometer'] = $res[$i]['lastKilometer'];
                    $arr[$x]['nextKm'] = $rst4[0]['nextKm'];
                    $arr[$x]['materialID'] = $rst4[0]['materialID'];
                    $x++;
                }
            }
            $sql5 = "SELECT `nextKm`,`materialID` FROM `consuming_materials` WHERE `materialID`=5 AND `carID`={$res[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
            $rst5 = $db->ArrayQuery($sql5);
            if (count($rst5) > 0) {
                if ((intval($rst5[0]['nextKm']) - intval($res[$i]['lastKilometer'])) <= $km_alert) {
                    $arr[$x]['carName'] = $res[$i]['carName'];
                    $arr[$x]['plaque'] = $res[$i]['plaque'];
                    $arr[$x]['lastKilometer'] = $res[$i]['lastKilometer'];
                    $arr[$x]['nextKm'] = $rst5[0]['nextKm'];
                    $arr[$x]['materialID'] = $rst5[0]['materialID'];
                    $x++;
                }
            }
        }
        $Countevent = count($arr);
        $iterator = 0;

        if($Countevent>0)
        {
            $htm = '';
            $htm .= '<table  class="table table-bordered table-hover table-sm" id="showConsumingMaterialsModal-table" >';
            $htm .= '<thead>';
            $htm .= "<tr class='bg-info'>";
            $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
            $htm .= "<td style='color: #ffc107;width:25%;text-align: center;'>نام ماشین</td>";
            $htm .= "<td style='color: #ffc107;width:25%;text-align: center;'>شماره پلاک</td>";
            $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>آخرین کیلومتر</td>";
            $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>کیلومتر موعد تعویض</td>";
            $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>ماده مصرفی</td>";
            $htm .= "</tr>";
            $htm .= '</thead>';
            $htm .= '<tbody>';
            for($y=0;$y<$Countevent;$y++){
                $iterator++;
                switch ($arr[$y]['materialID']){
                    case 1:
                        $mType = 'روغن';
                        break;
                    case 2:
                        $mType = 'فیلتر روغن';
                        break;
                    case 3:
                        $mType = 'فیلتر سوخت';
                        break;
                    case 4:
                        $mType = 'فیلتر هوا';
                        break;
                    case 5:
                        $mType = 'روغن گیربکس';
                        break;
                }
                $htm .= "<tr class='table-secondary'>";
                $htm .= "<td style='text-align: center;'>".$iterator."</td>";
                $htm .= "<td style='text-align: center;'>".$arr[$y]['carName']."</td>";
                $htm .= "<td style='text-align: center;'>".$arr[$y]['plaque']."</td>";
                $htm .= "<td style='text-align: center;color:". $alert_color."'>".$arr[$y]['lastKilometer']."</td>";
                $htm .= "<td style='text-align: center;'>".$arr[$y]['nextKm']."</td>";
                $htm .= "<td style='text-align: center;'>".$mType."</td>";
                $htm .="</tr>";
            }
            $htm .= '</tbody>';
            $htm .= "</table>";
            //++++++++++++++END TABLE ++++++++++++++++
            return $htm;
        }
    }

    //++++++++++++++++++++++ قرارداد منقضی شده +++++++++++++++++++++++

    public function calculateExpiredContract(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `contract` WHERE (CURDATE() >= `ceDate`) AND `isEnable`=1 ";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function showExpiredContractModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `contract` WHERE (CURDATE() >= `ceDate`) AND `isEnable`=1 ";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="events-ExpiredContract-modal-table">';
        $htm .= '<thead>';
        $htm .= "<tr class='bg-info'>";
        $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>شماره قرارداد</td>";
        $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>موضوع قرارداد</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>نوع قرارداد</td>";
        $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>طرف مقابل</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تاریخ شروع</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تاریخ پایان</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>مدت قرارداد</td>";
        $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>مبلغ قرارداد</td>";
        $htm .= "</tr>";
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for($y=0;$y<$Countevent;$y++){
            $iterator++;
            $csDate = $ut->greg_to_jal($res[$y]['csDate']);
            $ceDate = $ut->greg_to_jal($res[$y]['ceDate']);
            $htm .= "<tr class='table-secondary'>";
            $htm .= "<td style='text-align: center;'>".$iterator."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['number']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['subject']."</td>";
            $htm .= "<td style='text-align: center;'>".(intval($res[$y]['contractType']) == 0 ? 'عادی' : 'ساعتی')."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['accountName']."</td>";
            $htm .= "<td style='text-align: center;'>".$csDate."</td>";
            $htm .= "<td style='text-align: center;'>".$ceDate."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['creditPeriod']." ماه</td>";
            $htm .= "<td style='text-align: center;'>".number_format($res[$y]['totalAmount'])." ریال</td>";
            $htm .="</tr>";
        }
        $htm .= '</tbody>';
        $htm .= "</table>";
        //++++++++++++++END TABLE ++++++++++++++++
        return $htm;
    }

    //++++++++++++++++++++++ درخواست داده مهندسی جدید +++++++++++++++++++++++

    public function calculateRenderingRequest(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `label_rendering` WHERE `attached`=0 ";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function showRenderingRequestModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `label_rendering`.*,`pName`,`pCode`,`HPCode`,`fname`,`lname` FROM `label_rendering` 
                INNER JOIN `piece` ON (`label_rendering`.`pieceID`=`piece`.`RowID`)
                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                INNER JOIN `users` ON (`label_rendering`.`uid`=`users`.`RowID`) WHERE `attached`=0";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="showRenderingRequestModal-table">';
        $htm .= '<thead>';
        $htm .= "<tr class='bg-info'>";
        $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>نوع داده</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>کد قطعه</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>کد کالا</td>";
        $htm .= "<td style='color: #ffc107;width:30%;text-align: center;'>نام قطعه</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>ثبت کننده</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تاریخ ایجاد</td>";
        $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>توضیحات</td>";
        $htm .= "</tr>";
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for($y=0;$y<$Countevent;$y++){
            $iterator++;
            $cDate = $ut->greg_to_jal($res[$y]['cDate']);
            $htm .= "<tr class='table-secondary'>";
            $htm .= "<td style='text-align: center;'>".$iterator."</td>";
            $htm .= "<td style='text-align: center;'>".(intval($res[$y]['type']) == 0 ? 'برچسب' : 'کارتن')."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['pCode']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['HPCode']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['pName']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['fname']." ".$res[$y]['lname']."</td>";
            $htm .= "<td style='text-align: center;'>".$cDate."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['description']."</td>";
            $htm .="</tr>";
        }
        $htm .= '</tbody>';
        $htm .= "</table>";
        //++++++++++++++END TABLE ++++++++++++++++
        return $htm;
    }

    //++++++++++++++++++++++ نیازمند تاییدیه چاپ +++++++++++++++++++++++

    public function calculateConfirmationAttachLabelRequest(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `label_request_attachment` WHERE `printStatus`=0 ";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function showConfirmationAttachLabelRequestModal(){
        $acm = new acm();
        if(!$acm->hasAccess('eventsManage')){
            die("false");
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `label_request_attachment`.*,`fname`,`lname` FROM `label_request_attachment` INNER JOIN `users` ON (`label_request_attachment`.`uid`=`users`.`RowID`) WHERE `printStatus`=0";
        $res = $db->ArrayQuery($sql);
        $Countevent = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="showConfirmationAttachLabelRequestModal-table">';
        $htm .= '<thead>';
        $htm .= "<tr class='bg-info'>";
        $htm .= "<td style='color: #ffc107;width:5%;text-align: center;'>ردیف</td>";
        $htm .= "<td style='color: #ffc107;width:15%;text-align: center;'>ثبت کننده</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>تاریخ</td>";
        $htm .= "<td style='color: #ffc107;width:10%;text-align: center;'>زمان</td>";
        $htm .= "<td style='color: #ffc107;width:20%;text-align: center;'>ابعاد</td>";
        $htm .= "<td style='color: #ffc107;width:40%;text-align: center;'>توضیحات</td>";
        $htm .= "</tr>";
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for($y=0;$y<$Countevent;$y++){
            $iterator++;
            $createDate = $ut->greg_to_jal($res[$y]['createDate']);
            $htm .= "<tr class='table-secondary'>";
            $htm .= "<td style='text-align: center;'>".$iterator."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['fname']." ".$res[$y]['lname']."</td>";
            $htm .= "<td style='text-align: center;'>".$createDate."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['createTime']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['dimension']."</td>";
            $htm .= "<td style='text-align: center;'>".$res[$y]['description']."</td>";
            $htm .="</tr>";
        }
        $htm .= '</tbody>';
        $htm .= "</table>";
        //++++++++++++++END TABLE ++++++++++++++++
        return $htm;
    }

    public function calculateMeetingJobs(){
        $db=new DBi();
        $ut=new Utility();
        $current_date=date('Y-m-d');
        
        $full_access_user_array=[4];
        $is_admin_CEO=0;
        $is_admin_CEO=in_array($_SESSION['userid'],$full_access_user_array)?1:0;
        
        $htm="<table>";
        if($is_admin_CEO==1) ///اگر  کاربر مدیریت محترم عامل باشد
        {
            $getMeetingsInfo="SELECT RowID,`subject`, unCode,manager1,manager2 FROM meeting WHERE closed=0 and `start`=1";
            $meeting_info=$db->ArrayQuery($getMeetingsInfo);
            foreach($meeting_info as $m_key=>$m_value){
                $meeting_array[$m_value['RowID']]=$m_value;

            }
            $meeting_keys_array=array_keys($meeting_array);
            $meeting_jobs="SELECT mj.*,mja.read_users FROM meeting_jobs as mj left join meeting_job_alarms as mja on mja.meeting_job_id=mj.RowID";
            $meeting_jobs_array=$db->ArrayQuery($meeting_jobs);
            $final_array=[];

        }
        else
        {
            $getMeetingsInfo="SELECT RowID,`subject`, unCode,manager1,manager2,observers FROM meeting WHERE closed=0 and `start`=1 
                                        AND (manager1={$_SESSION['userid']} OR  manager2={$_SESSION['userid']} OR  observers LIKE '%{$_SESSION['userid']}%')";
            $meeting_array=$db->ArrayQuery($getMeetingsInfo);
            $meeting_manage_array=[];
            //$meeting_users_array=[];
            foreach($meeting_array as $key=>$value ){
                if(!empty($value['manager1'])){
                    $meeting_manage_array[]=$value['manager1'];
                }
                if(!empty($value['manager2'])){
                    $meeting_manage_array[]=$value['manager2'];
                }
            }

            $meeting_manage_array=array_unique($meeting_manage_array);
            $is_meeting_admin=in_array($_SESSION['userid'],$meeting_manage_array)?1:0;
            //$is_user_meeting=in_array($_SESSION['userid'],$meeting_users_array);
            if($is_meeting_admin==1)
            {// اگر کاربر مدیر  جلسه باشد
                $getMeetingsInfo = "SELECT RowID,`subject`, unCode,manager1,manager2 FROM meeting WHERE closed=0 AND `start`=1 
                                    AND (manager1={$_SESSION['userid']} OR manager2={$_SESSION['userid']})";
                $meeting_info=$db->ArrayQuery($getMeetingsInfo);
                    foreach($meeting_info as $m_key=>$m_value){
                    $meeting_array[$m_value['RowID']]=$m_value;

                }
                $meeting_keys_array=array_keys($meeting_array);
                $meeting_jobs="SELECT mj.*,mja.read_users FROM meeting_jobs as mj left join meeting_job_alarms as mja on mja.meeting_job_id=mj.RowID";
                $meeting_jobs_array=$db->ArrayQuery($meeting_jobs);
                $final_array=[];
            }
        }
       if(!is_array($meeting_keys_array)){
        $final_array=[];
        $meeting_keys_array=[];
        $user_sql="SELECT * FROM meeting_jobs where uid={$_SESSION['userid']}";
        $res_j=$db->ArrayQuery($user_sql);
        //*********************************************** حذف  مسئولست هایی که پیش نیاز آنها انجام نشده است */
        
        foreach($res_j as $key=>$value){
            $meeting_keys_array[]=$value['meetingID'];
        }
       }
        foreach($meeting_keys_array as $m_key)
        {
            $helper_array=[];
            foreach($meeting_jobs_array as $job_key=>$job_value){
                
                if($job_value['meetingID']==$m_key){
                    $date_diff=$this->getDateDiff($job_value['validDate'],$current_date);
                  // //$ut->fileRecorder('date_diff'.$date_diff."-".$job_value['validDate']."-".$current_date);
                    if( $date_diff<=0){
                        $users_array=explode(",",$job_value['read_users']);
                        if(!in_array($_SESSION['userid'],$users_array)){
                            $helper_array[]=$job_value;
                            $final_array[$m_key]=$helper_array;
                        }
                    }
                }
            }
        }
        $user_htm="";
        $htm="";
        $count_jobs=0;
        foreach($final_array as $k=>$final_array_value)
        {
            $conunter=1;
            $table_id="meeting_".$k;
            $color_r=rand(0,254);
            $color_g=rand(0,254);
            $color_b=rand(0,254);
            $random_color="rgb(".$color_r.",".$color_g.",".$color_b.")";
            $htm.=
                    '<div>
                        <fieldset style="border:2px solid '.$random_color.';border-radius:10px; padding:20px">
                            <legend style="width:auto;color:blue;font-size:14px">'.$meeting_array[$k]['subject'].'</legend>
                            <div style="display:flex;justify-content:center;width:100%">
                                <h6 style="width:45%"><span>مدیر جلسه :</span>'.$this->getUserName($meeting_array[$k]['manager1']).'</h6>
                                <h6 style="width:45%"><span ">تعداد مسئولیت های تعریف  شده :</span><label id="job_count_'.$meeting_array[$k]['RowID'].'">'.count($final_array_value).'</label></h6>
                                <button style="float:left;padding-bottom:0px;" onclick="manageJobTable('.$k.',this)" class="btn btn-success"><i style="font-size:20px" class="fa fa-plus"></i></button></div>';
            $htm.=
                    '<table style="display:none" class="table table-borders table-striped" id="'.$table_id.'">
                        <tr>
                            <th>#</th>
                            <th style="text-align:center">توضیحات</th>
                            <th>عضو جلسه</th>
                            <th>درصد انجام مسئولیت</th>
							 <th> تایید مدیر جلسه</th>
                            <th>مهلت  انجام </th>
                            <th>جزییات   </th>
                        </tr>';
                      
                
            foreach($final_array_value as $f_key=>$f_value ){
                $count_pre_req=0;
                if(!empty($f_value['prerequisiteIDS'])){
                    $count_pre_req=count(explode(",",str_replace(',,',',',$f_value['prerequisiteIDS'])));
                }
                $htm.='<tr id="job_'.$f_value['RowID'].'">
                            <td class="row_counter">'.$conunter.'</td>
                            <td style="text-align:center">'.$f_value['description'].($count_pre_req>0?'<a style="margin-right:10px" href="#" onclick="open_pre_request('.$f_value['RowID'].')">دارای '  .$count_pre_req." پیش نیاز</a>":'').'</td>
                            <td>'.$this->getUserName($f_value['uid']).'</td>
                            <td><span style="color:red">'.$f_value['percent']."</span> %".'</td>
							<td>'.($f_value['confirm']==1?'<span style="color:green">تایید </span>':'<span style="color:red">درحال بررسی</span>').'</td>
                            <td>'.$ut->greg_to_jal($f_value['validDate']).'</td>
                            <td><button class="btn btn-primary" onclick="showMeetingJobReportList('.$f_value['RowID'].')"><i class="fa fa-edit"></i></button></td>
                        </tr>';
                $conunter++;
                $count_jobs++;
            }
            $htm.='</table>
                        </fieldset><input type="hidden" value="'.count($final_array).'" id="jobs_count">
                        <input type="hidden" id="is_admin_CEO" value="'.$is_admin_CEO.'"/>
                    </div>';
        }
                //*************************************************** */ آلارم  جلسات برای  اعضای جلسه 
        $htm_user="";
        $user_meeting_job="SELECT mj.*,mja.meeting_member_user FROM meeting_jobs as mj  left join meeting_job_alarms as mja on mj.RowID=mja.meeting_job_id  
            left join meeting as m on m.RowID=mj.meetingID where mj.uid={$_SESSION['userid']} AND m.closed=0 ";
        ////$ut->fileRecorder($user_meeting_job);
        $user_jobs_result=$db->ArrayQuery($user_meeting_job);
        //******************************************************************** */
        foreach($user_jobs_result as $j_k=>$j_v){
           if(!empty($j_v['prerequisiteIDS'])){
            $pre_req_arr=explode(",",str_replace(",,",',',$j_v['prerequisiteIDS']));
                if($this->check_finish_prerequest_jobs($pre_req_arr)==0){
                    unset($user_jobs_result[$j_k]);
                }
           }
        }
        //************************************************************** */
        $final_array_user=[];
        ////$ut->fileRecorder('final_array_user1');
       // //$ut->fileRecorder($final_array_user);
        foreach($user_jobs_result as $user_job_key=>$user_job_value){
            $meeting_jobs_alarm="SELECT meeting_member_user,meeting_job_id from meeting_job_alarms where meeting_member_user={$_SESSION['userid']} and meeting_job_id = {$user_job_value['RowID']}";
           // //$ut->fileRecorder($meeting_jobs_alarm);
            $temp_res=$db->ArrayQuery($meeting_jobs_alarm);
           // //$ut->fileRecorder('countttt:'.count($temp_res));
            if(count($temp_res)==0){
                $final_array_user[$user_job_value['meetingID']][]=$user_job_value;
            }
        }

        $count_job_user=0;
       // //$ut->fileRecorder('final_array_user');
       // //$ut->fileRecorder($final_array_user);
        foreach($final_array_user as $k_user=>$final_array_value_user)
        {
            $meeting_info="SELECT * FROM meeting where RowID={$k_user} AND closed=0";
            $meeting_info_result=$db->ArrayQuery($meeting_info);
            $conunter_user=1;
            $table_id="meeting_".$k;
            $pre_req_sql="SELECT * FROM meeting_jobs WHERE {$final_array_value_user['RowID']}  in ({$final_array_value_user['prerequisiteIDS']})  AND status=1 AND confirm=1 AND percent=100";
            
            $htm_user.=
            '<div>
                <fieldset style="border:3px solid green;border-radius:10px; padding:20px;background:rgba(128,128,128,0.5)">
                    <legend style="width:auto;color:blue;font-size:14px">'.$meeting_info_result[0]['subject'].'</legend>
                    <div style="display:flex;justify-content:center;width:100%">
                        <h6 style="width:45%"><span>مدیر جلسه :</span>'.$this->getUserName($meeting_info_result[0]['manager1']).'</h6>
                        <h6 style="width:45%">
                            <span ">تعداد مسئولیت های تعریف  شده :</span>
                            <label id="user_job_count_'.$meeting_info_result[0]['RowID'].'">'.count($final_array_value_user).'</label>
                        </h6>
                        <button style="float:left;padding-bottom:0px;" onclick="toggle_user_jobs('.$k_user.',this)" class="btn btn-warning"><i style="font-size:20px" class="fa fa-plus"></i></button></div>';
                    $htm_user.=
                        '<table style="display:none"  class="table table-borders table-striped" id="user_jobs_'.$k_user.'">
                            <tr>
                                <th>#</th>
                                <th style="text-align:center">توضیحات</th>
                                <th>عضو جلسه</th>
                                <th>درصد انجام مسئولیت</th>
								 <th> تایید مدیر جلسه</th>
                                <th>مهلت  انجام </th>
                                <th>جزییات   </th>
                            </tr>';
                    
                    foreach($final_array_value_user as $f_key_user=>$f_value_user ){
                        if($f_value_user['meeting_member_user']!=$_SESSION['userid']){
                            $htm_user.='<tr id="user_job_'.$f_value_user['RowID'].'">
                                        <td class="row_counter">'.$conunter.'</td>
                                        <td style="text-align:center">'.$f_value_user['description'].'</td>
                                        <td>'.$this->getUserName($f_value_user['uid']).'</td>
                                        <td><span style="color:red">'.$f_value_user['percent']."</span> %".'</td>
										<td>'.($f_value_user['confirm']==1?"<span style='color:green'>تایید </span>":"<span style='color:red'> در حال بررسی </span>").'</td>
                                        <td>'.$ut->greg_to_jal($f_value_user['validDate']).'</td>
                                        <td><button class="btn btn-primary" onclick="showMeetingJobReportList('.$f_value_user['RowID'].',1)"><i class="fa fa-edit"></i></button></td>
                                    
                                    </tr>';
                            $conunter_user++;
                            $count_job_user++;
                        }
                    }
                    $htm_user.='</table>
                </fieldset><input type="hidden" value="'.count($final_array_user).'" id="jobs_count">
            </div>';
        }
        //*************************************************** */
        //error_log($htm_user);
        if($count_job_user>0){
            return $htm.'<hr><h6>وظایف تعیین شده برای کاربر  به عنوان عضو جلسه</h6>'.$htm_user;
        }
        else{
            return $htm.$htm_user;
        }
    }
    
    public function  check_finish_prerequest_jobs($array){
        $db=new DBi();
        $count_pre_jobs=count($array);
        $finis_pre_job=0;
        for($i=0;$i<$count_pre_jobs;$i++){
            $sql="select * from meeting_jobs where RowID={$array[$i]} AND status=1 AND confirm=1 ANd percent=100";
            $res=$db->ArrayQuery($sql);
            if(count($res)>0){
                $finis_pre_job++;
            }
        }
        if($count_pre_jobs==$finis_pre_job){
            return 1;
        }
        return 0;
    }
    public function getMeetingJobReportList($meeting_id,$is_user){
        $db=new DBi();
        $ut=new Utility();
        $meeting_jobs_sql="
        Select mj.*,m.manager1,m.manager2 FROM  meeting as m  
		left join meeting_jobs as mj on mj.meetingID=m.RowID where mj.RowID={$meeting_id} AND m.closed=0";
        //error_log($meeting_jobs_sql);
        $res=$db->ArrayQuery($meeting_jobs_sql);
        //error_log(print_r($res,true));
        $report="SELECT * FROM meeting_workreport where jobID={$meeting_id} AND isEnable=1";
        $rep_res=$db->ArrayQuery($report);
        $rep_html='<table class="table table-borderd">';
        if(count($rep_res)>0){
        foreach($rep_res as $rep_kay=>$rep_value){
            $rep_html.='<tr><td>'.$ut->greg_to_jal($rep_value['cDate']).'</td><td>'.$rep_value['description'].'</td></tr>';
        }
        $rep_html.="</table>";
    }
    else{
        $rep_html='<p style="padding:0 10px;color:red">گزارشی ثبت نشده است</p>';
    }
        $htm="";
        
        $get_pre_req_sql="select * from meeting_jobs where RowID IN ({$res[0]['prerequisiteIDS']})";
        $pre_req_result=$db->ArrayQuery($get_pre_req_sql);
        $counter_req=1;
        if(count($pre_req_result)>0)
        {
            $htm.=
                '<fieldset style="border:2px solid gray;border-radius:10px">
                <legend style="width:auto;color:blue;font-size:13px">پیش نیازهای مسئولیت  </legend>';
            $htm.='<table class="table table-borderd table-striped">
                    <tr>
                        <th  style="width:5%">
                            #
                        </th>
                        <th  style="width:50%">
                            <span>شرح مسئولیت</span>
                        </th>
                        <th  style="width:12%">
                            <span> عضو جلسه</span>
                        </th>
                        <th  style="width:12%">
                        <span> مهلت انجام کار </span>
                    </th>
                        <th  style="width:12%">
                            <span> درصد انجام کار</span>
                        </th>
                        <th  style="width:12%">
                            <span> وضعیت تایید </span>
                        </th>
                    </tr>';
            foreach($pre_req_result as $req_key =>$req_value){
                $htm.="<tr>
                            <td>"
                                .$counter_req.
                            "</td>
                            <td>"
                                .$req_value['description'].
                            "</td>
                            <td>"
                                .$this->getUserName($req_value['uid']).
                            "</td>
                            <td>"
                                .$ut->greg_to_jal($req_value['validDate']).
                            "</td>
                            <td>"
                                .$req_value['percent']." %".
                            "</td>
                            <td>"
                                .($req_value['confirm']==1?'<span style="color:green"></span>':'<span style="color:red">درحال بررسی </span>').
                            "</td>
                        </tr>";
                        $counter_req++;
            }
            $htm.='</table>';

            $htm.='</fieldset>';
        }
         $htm.=
        '<fieldset style="border:2px solid gray;border-radius:10px">
            <legend style="width:auto;color:blue;font-size:13px">جزییات  مسئولیت</legend>
            <div style="display:flex;justify-content:center" >
            <h6 style="width:45%">
                <span>مدیر جلسه :</span><span>'.$this->getUserName($res[0]['manager1']).'</span>
            </h6>
            <h6 style="width:45%">
             <span> عضو جلسه :</span><span>'.$this->getUserName($res[0]['uid']).'</span>
            </h6>
            <button style="color:blue;padding:0 10px;float:left" onclick="toggle_detailes(this)" class="btn"><i class="fa fa-angle-double-down"></i></button></div>';
        $htm.='<table  class="table table-borderd ">';
        foreach($res as $key=>$value){
            
            $htm.=
            "
                <tr>
                    <th style='width:20%'>شرح مسئولیت</th><td>".$value['description']."</td>
                </tr>
                <tr class='job_detailes' style='display:none'>
                    <th>مهلت انجام</th><td>".$ut->greg_to_jal($value['validDate'])."</td>
                </tr>
                <tr class='job_detailes' style='display:none'>
                    <th>زمان اتمام</th><td>".$ut->greg_to_jal($value['finishDate'])."</td>
                </tr>
                <tr class='job_detailes' style='display:none'>
                    <th>درصد انجام کار</th><td>".$value['percent'].' %'."</td>
                </tr>
                <tr class='job_detailes' style='display:none'>
                    <th>وضعیت تایید</th><td>".($value['confirm']==1?'<span style="color:green">تایید</span>':'<span style="color:red">درحال بررسی </span>')."</td>
                </tr>";
        }
       
        $htm.='</table></fieldset><fieldset style="border:2px solid green;border-radius:10px"><legend style="color:blue;font-size:13px;width:auto">گزارش انجام کار</legend>'.$rep_html.
        '</fieldset>
        <fieldset style="border:2px solid gray;border-radius:10px">
            <legend style="width:auto;color:green;padding:5px">***</legend>
            <p style="padding:10px">
                <label style="display: flex;justify-content: space-around;width: 49%;flex-direction: row-reverse"> 
                    <span>  گزارش خوانده شده و دوباره نمایش داده نشود. </span>
                    <span>
                        <input type="checkbox" id="confirm_read_report">
                    </span>
                </label>
            </p>
            
			<input value="'.$res[0]['RowID'].'" type="hidden" id="meeting_job_id">
            <input value="'.$res[0]['meetingID'].'" type="hidden" id="meeting_id">
            <input value="'.$this->getUserName($res[0]['uid'],1).'" type="hidden" id="meeting_job_user">';
            if($is_user==1){
                $htm.='<input type="hidden" id="is_user" value="1">';
            }
           
        $htm.='</fieldset>';
        return $htm;

    }
    //------------------------------------------------------------------------ مدیریت  مراحل پرداخت قرارداد ------------------------
    public function calculate_contract_pay_rows(){
        $db=new DBi();
    
        $get_contract_has_not_confirm_rows="SELECT `contract_id` FROM contract_pay_formula WHERE (CEO_confirm IS NULL OR CEO_confirm=0)  AND `status`=1 AND percentage_increase_allowable_temperature >0";
        $res=$db->ArrayQuery($get_contract_has_not_confirm_rows);
        return count($res);
    }

    public function showContractPayRows(){
        $db=new DBi();
        $ut=new Utility();
        $acm=new acm();
       
        if(!$acm->hasAccess('ContractPayFormulaAlarmModalAlarm')){
            die("false");
        }
       
        $get_contract_has_not_confirm_rows="SELECT `contract_id` FROM contract_pay_formula WHERE (CEO_confirm IS NULL OR CEO_confirm=0) AND `status`=1 AND percentage_increase_allowable_temperature >0 ORDER BY `contract_id` ";
        $contract_ides = $db->ArrayQuery($get_contract_has_not_confirm_rows);
        $contract_ides_has_not_confirm_row=[];
        foreach($contract_ides as $row){
            $contract_ides_has_not_confirm_row[]=$row['contract_id'];// get all contract ides that has not confirm row in contract_formula_rows
        }
       
        $db->ArrayQuery($get_contract_has_not_confirm_rows);
        $get_rows="SELECT * FROM contract_pay_formula WHERE (CEO_confirm IS NULL OR CEO_confirm=0) AND `status`=1 AND percentage_increase_allowable_temperature >0";
        $p_rows=$db->ArrayQuery($get_rows);
        $count_rows=count($p_rows);
        $ut->fileRecorder('count_rows');
        $ut->fileRecorder($count_rows);
        if($count_rows>0){
            $html="";
            $parent_counter=1;
            foreach($contract_ides_has_not_confirm_row as $contract_id){
               
                $contract_info=$this->getContractInfo($contract_id);
                $pay_row_info=$this->get_contract_pay_rows_not_confirm($contract_id);

                $count_pay_row=count($pay_row_info);
                $html.='<fieldset id="box_'.$parent_counter.'" style="border: 2px solid gray;border-radius: 10px;padding: 10px;"><legend style="width:auto;padding:10px"><span> قراداد شماره </span><span style="color:green;padding:0 10px">'.$contract_info['number'].'</span><span> با موضوع </span><span style="color:green;padding:0 10px">'.$contract_info['subject'].'</span> </legend>';
                    $html.='<div style="display:grid;grid-template-columns:25% 25% 25% 25%">
                                <div>
                                    <span>  طرف حساب :</span><span> &nbsp'.$contract_info['accountName'].' </span>
                                </div>
                                <div>
                                    <span>مبلغ کل قرارداد :</span><span> &nbsp'.number_format($contract_info['totalAmount']).' ریال</span>
                                </div>
                                <div>
                                    <span>تعداد ردیف های پرداخت تایید نشده :</span> <span> &nbsp'.$count_pay_row.'</span>
                                </div>
                                <div style="text-align:end">
                                    <button class="btn btn-success" onclick="max_min_pay_contract(this,'.$parent_counter.')" ><i class="fa fa-plus"></i></button>
                               </div>
                            </div>
                            <hr>
                            <table style="display:none" class="pay_row_tbl table table-bordered">
                            <!--<tr>
                                <td colspan="10"> <span> فیلتر مراحل پرداخت </span> <span><select onchange="filetr_pay"><option value="0">موارد تایید نشده</option><option value="1">موارد تایید شده</option><option value="2">همه  موارد</option></select></span></td>
                            </tr>-->
                            <tr>
                                <td>
                                    <span>#</span>
                                </td>
                                <td>
                                    <span>شرح ردیف پرداخت </span>
                                </td>
                                <td>
                                    <span>مبلغ  پرداخت </span>
                                </td>
                                <td>
                                    <span>  درصد افزایش مجاز </span>
                                </td>
                                <td>
                                    <span>کاربر ثبت کننده </span>
                                </td>
                               <!-- <td> سوابق تغییر</td>-->
                                <td>تایید نهایی</td>
                               
                            </tr>';
                            $counter=1;
                           foreach($pay_row_info as $pay_row){
                                $html.=
                                    '<tr>
                                        <td>
                                            <span>'.$counter.'</span>
                                        </td>
                                        <td>
                                            <span>'.$pay_row['description_pay_part'].'</span>
                                        </td>
                                        <td>
                                            <span>'.number_format($pay_row['amount_pay_part']).'</span>
                                        </td>
                                        <td>
                                            <span>'.(!empty($pay_row['percentage_increase_allowable_temperature'])?$pay_row['percentage_increase_allowable_temperature']."درصد":"" ).'</span>
                                        </td>
                                        <td>
                                            <span>'.$this->getUserName($pay_row['user_id']).'</span>
                                        </td>
                                        <!--<td>
                                            <button  class="btn btn-primary" onclick="show_history('.$pay_row['RowID'].')">سوابق تغییر</button>
                                        </td>-->
                                        <td>
                                            <button  class="btn btn-success" onclick="final_confirm_contract_pay_row('.$pay_row['RowID'].',this)">تایید نهایی </button>
                                        </td>
                                    </tr>';
                                $counter++;
                           }

                $html.='</table></fieldset>';
                $parent_counter++;

            }
           
            return $html;
        }
        else{
            return 0;
        }
    }

    public function final_confirm_contract_pay_rows($row_id){
        $db=new DBi();
        $sql="UPDATE `contract_pay_formula` SET CEO_confirm=1 WHERE RowID='{$row_id}'";
        $res=$db->Query($sql);
        if($res){
            return true;
        }
        else{
            return false;
        }

    }

    public function getContractInfo($contract_id){
        $db=new DBi();
        $ut=new Utility();
        $c_sql="SELECT * FROM `contract` WHERE RowID={$contract_id}";
        $ut->fileRecorder($c_sql);
        $result=$db->ArrayQuery($c_sql);
        $ut->fileRecorder($result);
        return $result[0];
    }

    public function get_contract_pay_rows_not_confirm($contract_id){
        $db=new DBi();
        $sql_r="SELECT * FROM `contract_pay_formula` WHERE contract_id={$contract_id} AND status=1 AND (CEO_confirm IS NULL OR CEO_confirm=0)";
        $res=$db->ArrayQuery($sql_r);
        return $res;
    }

    public function manageMeetingJobAlarm($job_id,$is_user){
        $db=new DBi();
         $user_id=$_SESSION['userid'];
       
        $select_job="select * from meeting_job_alarms  where meeting_job_id={$job_id}";
        $res=$db->ArrayQuery($select_job);
        if($is_user==0){
        @$read_users=$res[0]['read_users'];
        $read_users_array=explode(",",$read_users);
        }
        else{
            @$read_users=$res[0]['meeting_member_user'];
            $read_users_array=explode(",",$read_users);
        }
        if(!in_array($user_id,$read_users_array)){
            $read_users_array[]=$user_id;
            $read_users=implode(",",$read_users_array);
            //error_log($is_user);
            if($is_user==0){
                $update_meeting_job="update meeting_job_alarms set read_users='{$read_users}' where meeting_job_id={$job_id}";
            }
            else{
                $read_users=str_ireplace(",",'',$read_users);
                $update_meeting_job="update meeting_job_alarms set meeting_member_user='{$read_users}' where meeting_job_id={$job_id}";
                
                //error_log('update_user');
            }
            //error_log($update_meeting_job);
            $update_result=$db->query($update_meeting_job);
        }
        if(count($res)==0){
           
            $users_read=$user_id;
            if($is_user==0){
                $insert_sql="insert into meeting_job_alarms (meeting_job_id,read_users)Values({$job_id},'{$users_read}')";
                $db->query($insert_sql);
            }
            else{
                $insert_sql="insert into meeting_job_alarms (meeting_job_id,meeting_member_user)Values({$job_id},'{$users_read}')";
                $db->query($insert_sql);
                //error_log('insert_user');
            }
        }
        if($db->InsertrdID()>0 || $update_result){
            $meeting_id_sql="select meetingID from meeting_jobs where RowID={$job_id}";
            $meeting_id_aray=$db->ArrayQuery($meeting_id_sql);
            return $meeting_id_aray[0]['meetingID'];
        }
    }

    public function getDateDiff($s_date,$e_date)
    { 
        $s_date = strtotime($s_date);
        $e_date = strtotime($e_date);
        $date_diff = intval(round(($s_date-$e_date)/86400));
        return  $date_diff;
    }
	
	public function getUserName($userid,$perfix=0){
		$db=new DBi();
		$user_sql="SELECT * From users where RowID={$userid} AND IsEnable=1";
		$result=$db->ArrayQuery($user_sql);
	
        if($perfix==1){
            $gender=$result[0]['gender']==0?"آقای ":"خانم ";
            $fullName=$gender." ".$result[0]['fname']." ".$result[0]['lname'];
        }
        else{
            $fullName=$result[0]['fname']." ".$result[0]['lname'];
        }
		
		return $fullName;
		
	}

}