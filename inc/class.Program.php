<?php
/**
 * Created by PhpStorm.
 * User: Majid
 * Date: 10/23/2018
 * Time: 12:54 PM
 */

class Program{

    public function __construct(){
        // do nothing
    }

    public function getProgramManageHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('programManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $pagename = "مدیریت برنامه ها";
        $pageIcon = "fas fa-project-diagram";
        $contentId = "programManageBody";

        $c = 0;
        $bottons= array();
        $bottons[$c]['title'] = "ایجاد برنامه جدید";
        $bottons[$c]['jsf'] = "createProgram";
        $bottons[$c]['icon'] = "fa-plus-square";
        $c++;

        $bottons[$c]['title'] = "ویرایش برنامه";
        $bottons[$c]['jsf'] = "editProgram";
        $bottons[$c]['icon'] = "fa-edit";
        $c++;

        $bottons[$c]['title'] = "حذف برنامه";
        $bottons[$c]['jsf'] = "deleteProgram";
        $bottons[$c]['icon'] = "fa-minus-square";

        $headerSearch = array();
        $headerSearch[0]['type'] = "text";
        $headerSearch[0]['width'] = "120px";
        $headerSearch[0]['id'] = "programManagePCodeSearch";
        $headerSearch[0]['title'] = "شماره برنامه";
        $headerSearch[0]['placeholder'] = "شماره برنامه";

        $headerSearch[1]['type'] = "text";
        $headerSearch[1]['width'] = "220px";
        $headerSearch[1]['id'] = "programManageGNameSearch";
        $headerSearch[1]['title'] = "قسمتی از نام محصول";
        $headerSearch[1]['placeholder'] = "قسمتی از نام محصول";

        $headerSearch[2]['type'] = "text";
        $headerSearch[2]['width'] = "120px";
        $headerSearch[2]['id'] = "programManageCNameSearch";
        $headerSearch[2]['title'] = "نام مشتری";
        $headerSearch[2]['placeholder'] = "نام مشتری";

        $headerSearch[3]['type'] = "text";
        $headerSearch[3]['width'] = "100px";
        $headerSearch[3]['id'] = "programManageSdateSearch";
        $headerSearch[3]['title'] = "از تاریخ";
        $headerSearch[3]['placeholder'] = "از تاریخ";

        $headerSearch[4]['type'] = "text";
        $headerSearch[4]['width'] = "100px";
        $headerSearch[4]['id'] = "programManageEdateSearch";
        $headerSearch[4]['title'] = "تا تاریخ";
        $headerSearch[4]['placeholder'] = "تا تاریخ";

        $headerSearch[5]['type'] = "btn";
        $headerSearch[5]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
        $headerSearch[5]['jsf'] = "showProgramManageList";

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        //++++++++++++++++++++++++++++++++++EDIT CREATE MODAL++++++++++++++++++++++++++++++++
        $modalID = "programManagmentModal";
        $modalTitle = "فرم ایجاد/ویرایش برنامه";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "programManagmentPcode";
        $items[$c]['title'] = "شماره برنامه";
        $items[$c]['placeholder'] = "شماره برنامه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "programManagmentGname";
        $items[$c]['title'] = "نام محصول";
        $items[$c]['data-provide'] = "data-provide='typeahead'";
        $items[$c]['placeholder'] = "نام محصول";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "programManagmentNumber";
        $items[$c]['title'] = "تعداد";
        $items[$c]['placeholder'] = "تعداد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "programManagmentDate";
        $items[$c]['style'] = "style='width:70%;float:right;'";
        $items[$c]['title'] = "تاریخ";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "programManagmentCname";
        $items[$c]['title'] = "نام مشتری";
        $items[$c]['placeholder'] = "نام مشتری";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "programManagmentDesc";
        $items[$c]['title'] = "شرح فعالیت";
        $items[$c]['placeholder'] = "شرح فعالیت";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageProgramHiddenPid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateProgram";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ DELETE MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "manageDeleteProgramModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به حذف این برنامه مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "programManage_deleteIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "dodeleteProgram";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $delModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF DELETE MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++Start Program Info Modal ++++++++++++++++++++++
        $modalID = "programManageInfoModal";
        $modalTitle = "اجزای محصول";
        $style = 'style="max-width: 1000px;"';
        $ShowDescription = 'program-manage-Info-body';
        $x = 0;
        $items = array();
        $footerBottons = array();
        if($acm->hasAccess('excelexport')){
            $footerBottons[$x]['title'] = "خروجی اکسل";
            $footerBottons[$x]['jsf'] = "getExcelProgramInfo";
            $footerBottons[$x]['type'] = "btn-success";
            $x++;
        }
        $footerBottons[$x]['title'] = "ثبت";
        $footerBottons[$x]['jsf'] = "addDescForPiece";
        $footerBottons[$x]['type'] = "btn";
        $x++;
        $footerBottons[$x]['title'] = "بستن";
        $footerBottons[$x]['type'] = "dismis";
        $showProgramInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++End Good Info Modal ++++++++++++++++++++++++
        $htm .= $editCreateModal;
        $htm .= $delModal;
        $htm .= $showProgramInfo;
        return $htm;
    }

    public function getProgramList($pCode,$gName,$cName,$sDate,$eDate,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('programManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($pCode)) > 0){
            $w[] = '`programCode`="'.$pCode.'"';
        }
        if(strlen(trim($gName)) > 0){
            $w[] = '`good`.`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($cName)) > 0){
            $w[] = '`customerName` LIKE "%'.$cName.'%" ';
        }
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`programDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`programDate` <="'.$eDate.'" ';
        }

        $sql = "SELECT `program`.`RowID` AS `pid`,
                       `Assembled`,
                       `fname`,
                       `lname`,
                       `programCode`,
                       `gName`,
                       `number`,
                       `programDate`,
                       `customerName`,
                       `activityDescription`
                       FROM `program` 
                       INNER JOIN `good` ON (`good`.`gCode`=`program`.`goodCode`)
                       INNER JOIN `users` ON (`users`.`RowID`=`program`.`uid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `programDate` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['pid'];
            $finalRes[$y]['name'] = $res[$y]['fname'].' '.$res[$y]['lname'];
            $finalRes[$y]['programCode'] = $res[$y]['programCode'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
            $finalRes[$y]['number'] = $res[$y]['number'];
            $finalRes[$y]['Assembled'] = $res[$y]['Assembled'];
            $finalRes[$y]['customerName'] = $res[$y]['customerName'];
            $finalRes[$y]['activityDescription'] = $res[$y]['activityDescription'];
            $finalRes[$y]['programDate'] = $ut->greg_to_jal($res[$y]['programDate']);
        }
        return $finalRes;
    }

    public function getProgramListCountRows($pCode,$gName,$cName,$sDate,$eDate){
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(strlen(trim($pCode)) > 0){
            $w[] = '`programCode`="'.$pCode.'"';
        }
        if(strlen(trim($gName)) > 0){
            $w[] = '`good`.`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($cName)) > 0){
            $w[] = '`customerName` LIKE "%'.$cName.'%" ';
        }
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`programDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`programDate` <="'.$eDate.'" ';
        }
        $sql = "SELECT `program`.`RowID` AS `pid`,
                       `fname`,
                       `gName`
                       FROM `program`
                       INNER JOIN `good` ON (`good`.`gCode`=`program`.`goodCode`)
                       INNER JOIN `users` ON (`users`.`RowID`=`program`.`uid`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function programInfo($pid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `programCode`,`gName`,`number`,`programDate`,`customerName`,`activityDescription`
                FROM `program` INNER JOIN `good` ON (`program`.`goodCode`=`good`.`gCode`)
                WHERE `program`.`RowID` = ".$pid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("pid"=>$pid,
                "programCode"=>$res[0]['programCode'],
                "gName"=>$res[0]['gName'],
                "number"=>$res[0]['number'],
                "programDate"=>$ut->greg_to_jal($res[0]['programDate']),
                "customerName"=>$res[0]['customerName'],
                "activityDescription"=>$res[0]['activityDescription']
            );
            return $res;
        }else{
            return false;
        }
    }

    public function createProgram($Pcode,$Gname,$Number,$pDate,$Cname,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('programManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `gCode` FROM `good` WHERE `gName`='{$Gname}'";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            $sql2 = "SELECT `piece`.`RowID` AS `prid`,`interface`.`amount` AS `pamounts` FROM `interface`
                     INNER JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                     WHERE `interface`.`ProductCode`='{$res[0]['gCode']}'";
            $res2 = $db->ArrayQuery($sql2);
            $Countres2 = count($res2);
            if($Countres2 > 0){
                for($i=0;$i<$Countres2;$i++){
                    $Rids[] = $res2[$i]['prid'];
                    $Amounts[] = $res2[$i]['pamounts'];
                }
                $PieceRids = implode(',',$Rids);
                $PieceAmounts = implode(',',$Amounts);
                $pDate = $ut->jal_to_greg($pDate);
                $sql1 = "INSERT INTO `program` (`uid`,`programCode`,`goodCode`,`number`,`programDate`,`customerName`,`activityDescription`,`PieceRids`,`PieceAmounts`) 
                         VALUES({$_SESSION['userid']},'{$Pcode}','{$res[0]['gCode']}',{$Number},'{$pDate}','{$Cname}','{$desc}','{$PieceRids}','{$PieceAmounts}')";
                $db->Query($sql1);
                $id = $db->InsertrdID();
                if(intval($id) > 0){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function editProgram($pid,$Pcode,$Number,$pDate,$Cname,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('programManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $pDate = $ut->jal_to_greg($pDate);
        $sql1 = "UPDATE `program` SET `programCode`='{$Pcode}',`number`={$Number},`programDate`='{$pDate}',`customerName`='{$Cname}',`activityDescription`='{$desc}' WHERE `RowID`=".$pid;
        $db->Query($sql1);
        $res1 = $db->AffectedRows();
        $res1 = (($res1 == -1 || $res1 == 0) ? 0 : 1);
        if (intval($res1)){
            return true;
        }else{
            return false;
        }
    }

    public function deleteProgram($pid){
        $acm = new acm();
        if(!$acm->hasAccess('programManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "DELETE FROM `program` WHERE `RowID`=".$pid;
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
		
        $sql1 = "DELETE FROM `other_piece` WHERE `program_id`=".$pid;
        $db->Query($sql1);
        $res1 = $db->AffectedRows();
        $res1 = (($res1 == -1 || $res1 == 0) ? 0 : 1);

        if(intval($res) > 0 && intval($res1) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function showPieceOfGood($pid){
        $acm = new acm();
        if(!$acm->hasAccess('programManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `goodCode`,`number`,`Assembled`,`PieceRids`,`PieceAmounts` FROM `program` WHERE `RowID`=".$pid;
        $res = $db->ArrayQuery($sql);
        $gCode = $res[0]['goodCode'];
        $Num = $res[0]['number'];
        $Assembled = $res[0]['Assembled'];
        $Amounts = explode(',',$res[0]['PieceAmounts']);
        $sql1 = "SELECT `pCode`,`pName`,`pUnit` FROM `piece`
                 INNER JOIN `interface` ON (`piece`.`pCode`=`interface`.`PieceCode` AND `interface`.`ProductCode`='{$gCode}')
                 WHERE `piece`.`RowID` IN ({$res[0]['PieceRids']})";
        $res1 = $db->ArrayQuery($sql1);
        $CountRes = count($res1);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-responsive table-sm" style="display: inline-table;" id="addDescForPiece-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="display: none;">#</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 36%;">نام قطعه</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 8%;">مقدار نیاز</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 8%;"> مازاد</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 8%;"> مقدار محصول</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 8%;"> ضایعات</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 8%;"> سالم</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 8%;"> مغایرت</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 16%;"> توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$CountRes;$i++){
            $total = $Amounts[$i] * $Num;  // تعداد کل مورد نیاز
            $totalAssembled = $Amounts[$i] * $Assembled;  //مونتاژ شده
            $totalAssembled1 = ($totalAssembled == 0 ? '----' : $totalAssembled.' '.$res1[$i]['pUnit']);  //مونتاژ شده
            $sql2 = "SELECT `waste`,`extra`,`desc`,`healthy` FROM `other_piece` WHERE `codePiece`='{$res1[$i]['pCode']}' AND `program_id`=".$pid;
            $result = $db->ArrayQuery($sql2);
            if(count($result) > 0){
                $waste = (($result[0]['waste']) == 0 ? '----' : $result[0]['waste'].'  '.$res1[$i]['pUnit']);  //ضایعات
                $extra = (($result[0]['extra']) == 0 ? '----' : $result[0]['extra'].'  '.$res1[$i]['pUnit']);  //مازاد
                $healthy = (($result[0]['healthy']) == 0 ? '----' : $result[0]['healthy'].'  '.$res1[$i]['pUnit']);  //سالم
                $desc = (strlen($result[0]['desc']) == 0 ? '' : $result[0]['desc']);  //توضیحات
                $Contradiction = ($totalAssembled == 0 ? '----' : ($total + ($result[0]['extra']))-($totalAssembled + ($result[0]['waste']) + ($result[0]['healthy']))); //مغایرت
            }else{
                $waste = '----';
                $extra = '----';
                $healthy = '----';
                $desc = '';
                $Contradiction = ($totalAssembled == 0 ? '----' : ($total + ($result[0]['extra']))-($totalAssembled + ($result[0]['waste']) + ($result[0]['healthy']))); //مغایرت
            }
            $dir = (($Contradiction) < 0 ? 'direction: ltr;' : '');
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="display: none;"><input type="checkbox" rid="'.$iterator.'" checked disabled /></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;">'.$res1[$i]['pName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;">'.$total.'  '.$res1[$i]['pUnit'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;">'.$extra.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;">'.$totalAssembled1.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;">'.$waste.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;">'.$healthy.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;'.$dir.'">'.$Contradiction.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;"><input type="text" id="descValue-'.$iterator.'" class="form-control" style="text-align: center;" value="'.$desc.'" /><input type="hidden" id="pieceCode-'.$iterator.'-Hidden" value="'.$res1[$i]['pCode'].'" /></td>';
            $htm .= '</tr>';
        }
        $htm .= '<input type="hidden" id="programManage-HiddenID" value="'.$pid.'" />';
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function addDescForPiece($myJsonString,$programID){
        $acm = new acm();
        if(!$acm->hasAccess('programManage')){
            die("access denied");
            exit;
        }
        $countJS = count($myJsonString);
        $flag = true;
        $db = new DBi();
        mysqli_autocommit($db->Getcon(),FALSE);
        $sql = "SELECT `RowID` FROM `other_piece` WHERE `program_id`=".$programID;
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){  // قبلا برای این برنامه توضیحات ثبت شده است
            for($i=0;$i<$countJS;$i++){
                $desc = $myJsonString[$i][0];  // desc
                $lengthDesc = strlen($desc);  // desc Length
                $intJS1 = intval($myJsonString[$i][1]);  // pcode
                $sqlnew = "SELECT `RowID` FROM `other_piece` WHERE `codePiece`='{$intJS1}' AND `program_id`={$programID}";
                $resnew = $db->ArrayQuery($sqlnew);
                if(count($resnew) > 0){  // برای این قطعه قبلا توضیحات ثبت شده است
                    $sql1 = "UPDATE `other_piece` SET `desc`='{$desc}' WHERE `program_id`={$programID} AND `codePiece`='{$intJS1}'";
                    $db->Query($sql1);
                    $res1 = $db->AffectedRows();
                    $res1 = ($res1 == -1 ? 0 : 1);
                    if(!intval($res1)){
                        $flag = false;
                    }
                }else{   // برای این قطعه قبلا توضیحات ثبت نشده است
                    if ($lengthDesc <= 0){
                        continue;
                    }
                    $sqlnew1 = "INSERT INTO `other_piece` (`program_id`,`codePiece`,`desc`)
                                VALUES ({$programID},'{$intJS1}','{$desc}')";
                    $resnew1 = $db->Query($sqlnew1);
                    if (intval($resnew1) <= 0) {
                        $flag = false;
                    }
                }
            }
        }else{  // قبلا برای این برنامه توضیحات ثبت نشده است
            for($i=0;$i<$countJS;$i++) {
                $desc = $myJsonString[$i][0];  // desc
                $lengthDesc = strlen($desc);  // desc Length
                if ($lengthDesc <= 0){
                    continue;
                }
                $sql2 = "INSERT INTO `other_piece` (`program_id`,`codePiece`,`desc`)
                         VALUES ({$programID},'{$myJsonString[$i][1]}','{$desc}')";
                $result = $db->Query($sql2);
                if (intval($result) <= 0) {
                    $flag = false;
                }
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

    public function reportProgramExcel($programID){
        $acm = new acm();
        if(!$acm->hasAccess('programManage') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `goodCode`,`number`,`Assembled`,`PieceRids`,`PieceAmounts` FROM `program` WHERE `RowID`=".$programID;
        $res = $db->ArrayQuery($sql);
        $gCode = $res[0]['goodCode'];
        $Num = $res[0]['number'];
        $Assembled = $res[0]['Assembled'];
        $Amounts = explode(',',$res[0]['PieceAmounts']);
        $sql1 = "SELECT `pCode`,`pName`,`pUnit` FROM `piece`
                 INNER JOIN `interface` ON (`piece`.`pCode`=`interface`.`PieceCode` AND `interface`.`ProductCode`='{$gCode}')
                 WHERE `piece`.`RowID` IN ({$res[0]['PieceRids']})";
/*        $sql1 = "SELECT `pCode`,`pName`,`pUnit`,`amount` FROM `interface`
                 INNER JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                 WHERE `interface`.`ProductCode`='{$gCode}'";*/
        $res1 = $db->ArrayQuery($sql1);
        $CountRes = count($res1);
        $finalResult = array();
        for ($i=0;$i<$CountRes;$i++){
            $total = $Amounts[$i] * $Num;  // تعداد کل مورد نیاز
            $totalAssembled = $Amounts[$i] * $Assembled;  //مونتاة شده
            $totalAssembled1 = ($totalAssembled == 0 ? '' : $totalAssembled.' '.$res1[$i]['pUnit']);  //مونتاة شده
            $sql2 = "SELECT `waste`,`extra`,`desc`,`healthy` FROM `other_piece` WHERE `codePiece`='{$res1[$i]['pCode']}' AND `program_id`=".$programID;
            $result = $db->ArrayQuery($sql2);
            if(count($result) > 0){
                $waste = (($result[0]['waste']) == 0 ? '' : $result[0]['waste'].' '.$res1[$i]['pUnit']);  //ضایعات
                $extra = (($result[0]['extra']) == 0 ? '' : $result[0]['extra'].' '.$res1[$i]['pUnit']);  //مازاد
                $healthy = (($result[0]['healthy']) == 0 ? '' : $result[0]['healthy'].' '.$res1[$i]['pUnit']);  //مازاد
                $desc = (strlen($result[0]['desc']) == 0 ? '' : $result[0]['desc']);  //توضیحات
                $Contradiction = ($totalAssembled == 0 ? '' : ($total + ($result[0]['extra']))-($totalAssembled + ($result[0]['waste']) + ($result[0]['healthy']))); //مغایرت
            }else{
                $waste = '';
                $extra = '';
                $healthy = '';
                $desc = '';
                $Contradiction = ($totalAssembled == 0 ? '' : ($total + ($result[0]['extra']))-($totalAssembled + ($result[0]['waste']) + ($result[0]['healthy']))); //مغایرت
            }
            $finalResult[$i]['pName'] = $res1[$i]['pName'];
            $finalResult[$i]['total'] = $total.' '.$res1[$i]['pUnit'];
            $finalResult[$i]['totalAssembled'] = $totalAssembled1;
            $finalResult[$i]['waste'] = $waste;
            $finalResult[$i]['extra'] = $extra;
			$finalResult[$i]['healthy'] = $healthy;
            $finalResult[$i]['Contradiction'] = $Contradiction;
            $finalResult[$i]['desc'] = $desc;
        }
        return $finalResult;
    }

    public function getGoods(){
        $db = new DBi();
        $sql = "SELECT `gName`,`RowID` FROM `good` ";
        $res = $db->ArrayQuery($sql);
        if(count($res)>0){
            return $res;
        }else{
            return false;
        }
    }
	
	    //+++++++++++++++++ خروجی اکسل BOM ++++++++++++++++++++

    public function getGoodsForGeneralExcelBOM(){
        $acm = new acm();
        if(!$acm->hasAccess('programManage') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `gCode`,`gName`,`Col` FROM `good` ORDER BY `RowID` ASC";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            return $res;
        }else{
            return false;
        }
    }

    public function getPiecesForGeneralExcelBOM(){
        $acm = new acm();
        if(!$acm->hasAccess('programManage') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `pCode`,`pName`,`pUnit`,`Row` FROM `piece` ORDER BY `RowID` ASC";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            return $res;
        }else{
            return false;
        }
    }

    public function getColRowForGeneralExcelBOM(){
        $acm = new acm();
        if(!$acm->hasAccess('programManage') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `col_row`,`amount` FROM `interface` WHERE `isEnable`=1 ORDER BY `RowID` ASC";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            return $res;
        }else{
            return false;
        }
    }
	
	    //++++++++++++++++++++++ گزارش مغایرت ها +++++++++++++++++++++++

    public function getContradictionReportHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('contradictionReport')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $pagename = "گزارش مغایرت ها";
        $pageIcon = "fas fa-chart-bar";
        $contentId = "contradictionReportBody";
        $bottons= array();

        $x = 0;
        $headerSearch = array();
        $headerSearch[$x]['type'] = "text";
        $headerSearch[$x]['width'] = "100px";
        $headerSearch[$x]['id'] = "contradictionReportSdateSearch";
        $headerSearch[$x]['title'] = "از تاریخ";
        $headerSearch[$x]['placeholder'] = "از تاریخ";
        $x++;

        $headerSearch[$x]['type'] = "text";
        $headerSearch[$x]['width'] = "100px";
        $headerSearch[$x]['id'] = "contradictionReportEdateSearch";
        $headerSearch[$x]['title'] = "تا تاریخ";
        $headerSearch[$x]['placeholder'] = "تا تاریخ";
        $x++;
		
		$headerSearch[$x]['type'] = "text";
        $headerSearch[$x]['width'] = "200px";
        $headerSearch[$x]['id'] = "contradictionReportPnameSearch";
        $headerSearch[$x]['title'] = "نام قطعه";
        $headerSearch[$x]['placeholder'] = "نام قطعه";
        $x++;

        if($acm->hasAccess('excelexport')){
            $headerSearch[$x]['type'] = "btn";
            $headerSearch[$x]['title'] = "خروجی اکسل&nbsp;&nbsp;<i class='fas fa-file-excel'></i>";
            $headerSearch[$x]['jsf'] = "getExcelContradictionInfo";
            $x++;
        }

        $headerSearch[$x]['type'] = "btn";
        $headerSearch[$x]['title'] = "جستجو&nbsp;&nbsp;<i class='fas fa-search'></i>";
        $headerSearch[$x]['jsf'] = "showContradictionReportList";

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        return $htm;
    }

    /*public function getContradictionReport($cSDate,$cEDate,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('contradictionReport')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($cSDate)) > 0){
            $cSDate = $ut->jal_to_greg($cSDate);
            $w[] = '`programDate` >="'.$cSDate.'" ';
        }
        if(strlen(trim($cEDate)) > 0){
            $cEDate = $ut->jal_to_greg($cEDate);
            $w[] = '`programDate` <="'.$cEDate.'" ';
        }
        $sql = "SELECT SUM(`waste`) AS `sw`,
                       SUM(`extra`) AS `se`,
                      `codePiece`,
                      `pName`,
                      `pUnit` FROM `program`
                INNER JOIN `other_piece` ON (`other_piece`.`program_id`=`program`.`RowID`)
                INNER JOIN `piece` ON (`piece`.`pCode`=`other_piece`.`codePiece`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE `Assembled` > 0 AND ".$where;
        }else{
            $sql .= " WHERE `Assembled` > 0";
        }
        $sql .= " GROUP BY `other_piece`.`codePiece`";
        $sql .= " LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);

        $sql1 = "SELECT `number`,`Assembled`,`PieceRids`,`PieceAmounts` FROM `program` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql1 .= " WHERE `Assembled` > 0 AND ".$where;
        }else{
            $sql1 .= " WHERE `Assembled` > 0";
        }
        $result = $db->ArrayQuery($sql1);
        $resultCount = count($result);
        $sum = array();
        $assemble = array();
        $x = 0;

        for ($i=0;$i<$resultCount;$i++){
            for ($j=0;$j<$listCount;$j++){
                $sql2 = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$res[$j]['codePiece']}'";
                $res2 = $db->ArrayQuery($sql2);
                for ($c=0;$c<$resultCount;$c++){
                    $PieceRids = explode(',', $result[$c]['PieceRids']);
                    $PieceAmounts = explode(',', $result[$c]['PieceAmounts']);
                    if (in_array($res2[0]['RowID'], $PieceRids)) {
                        $key = array_search($res2[0]['RowID'], $PieceRids);
                        $sum[$x] += intval($PieceAmounts[$key]) * intval($result[$c]['number']);
                        $assemble[$x] += intval($PieceAmounts[$key]) * intval($result[$c]['Assembled']);
                    }
                    if ($c == $resultCount - 1) {
                        $x++;
                    }
                }
            }
        }
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['sw'] = $res[$y]['sw'].' '.$res[$y]['pUnit'];
            $finalRes[$y]['se'] = $res[$y]['se'].' '.$res[$y]['pUnit'];
            $finalRes[$y]['codePiece'] = $res[$y]['codePiece'];
            $finalRes[$y]['Total'] = $sum[$y];
            $finalRes[$y]['Assemble'] = $assemble[$y];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
            $finalRes[$y]['contradiction'] = ($res[$y]['se'] + $sum[$y])-($res[$y]['sw'] + $assemble[$y]);
        }
        return $finalRes;
    }*/
	
	public function getContradictionReport($cSDate,$cEDate,$cPName,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('contradictionReport')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        $z = array();
        if(strlen(trim($cSDate)) > 0){
            $cSDate = $ut->jal_to_greg($cSDate);
            $w[] = '`programDate` >="'.$cSDate.'" ';
            $z[] = '`programDate` >="'.$cSDate.'" ';
        }
        if(strlen(trim($cEDate)) > 0){
            $cEDate = $ut->jal_to_greg($cEDate);
            $w[] = '`programDate` <="'.$cEDate.'" ';
            $z[] = '`programDate` <="'.$cEDate.'" ';
        }
        if(strlen(trim($cPName)) > 0){
            $w[] = '`pName`="'.$cPName.'" ';
        }
		
        $qu = "SELECT `PieceCode`,`pName`,`pUnit` FROM `program`
               INNER JOIN `interface` ON (`interface`.`ProductCode`=`program`.`goodCode`)
               INNER JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $qu .= " WHERE `Assembled`<`number` AND `Assembled` > 0 AND ".$where;
        }else {
            $qu .= " WHERE `Assembled`<`number` AND `Assembled` > 0";
        }
        $qu .= " GROUP BY `PieceCode`";
        $rslt = $db->ArrayQuery($qu);
        $cnt = count($rslt);


        $sql = "SELECT SUM(`waste`) AS `sw`,
                       SUM(`extra`) AS `se`,
					   SUM(`healthy`) AS `sh`,
                      `codePiece`,
                      `pName`,
                      `pUnit` FROM `program`
                INNER JOIN `other_piece` ON (`other_piece`.`program_id`=`program`.`RowID`)
                INNER JOIN `piece` ON (`piece`.`pCode`=`other_piece`.`codePiece`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE `Assembled` > 0 AND ".$where;
        }else{
            $sql .= " WHERE `Assembled` > 0";
        }
        $sql .= " GROUP BY `other_piece`.`codePiece`";
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $m = $listCount;
        for ($i=0;$i<$listCount;$i++){
            $arr[] = $res[$i]['codePiece'];
        }

        for ($a=0;$a<$cnt;$a++){
            if (in_array($rslt[$a]['PieceCode'], $arr)){
                continue;
            }else{
                $res[$m]['sw'] = 0;
                $res[$m]['se'] = 0;
				$res[$m]['sh'] = 0;
                $res[$m]['codePiece'] = $rslt[$a]['PieceCode'];
                $res[$m]['pName'] = $rslt[$a]['pName'];
                $res[$m]['pUnit'] = $rslt[$a]['pUnit'];
                $m++;
            }
        }
        $listCount = count($res);

        $sql1 = "SELECT `number`,`Assembled`,`PieceRids`,`PieceAmounts` FROM `program` ";
        if(count($z) > 0){
            $where = implode(" AND ",$z);
            $sql1 .= " WHERE `Assembled` > 0 AND ".$where;
        }else{
            $sql1 .= " WHERE `Assembled` > 0";
        }
        $result = $db->ArrayQuery($sql1);
        $resultCount = count($result);
        $sum = array();
        $assemble = array();
        $x = 0;

        for ($j=0;$j<$listCount;$j++){
            $sql2 = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$res[$j]['codePiece']}'";
            $res2 = $db->ArrayQuery($sql2);
            $pcs[] = $res2[0]['RowID'];
        }

        //for ($i=0;$i<$resultCount;$i++){
            for ($j=0;$j<$listCount;$j++){
                for ($c=0;$c<$resultCount;$c++){
                    $PieceRids = explode(',', $result[$c]['PieceRids']);
                    $PieceAmounts = explode(',', $result[$c]['PieceAmounts']);
                    if (in_array($pcs[$j], $PieceRids)) {
                        $key = array_search($pcs[$j], $PieceRids);
                        $sum[$x] += ($PieceAmounts[$key]) * intval($result[$c]['number']);
                        $assemble[$x] += ($PieceAmounts[$key]) * intval($result[$c]['Assembled']);
                    }
                    if ($c == $resultCount - 1) {
                        $x++;
                    }
                }
            }
        //}

        $pageAmount = $page * 30;
        $finalRes = array();
        if ($pageAmount < $listCount) {
            for ($y = 0; $y < 30; $y++) {
                $finalRes[$y]['sw'] = $res[$start]['sw'] . ' ' . $res[$start]['pUnit'];
                $finalRes[$y]['se'] = $res[$start]['se'] . ' ' . $res[$start]['pUnit'];
				$finalRes[$y]['sh'] = $res[$start]['sh'] . ' ' . $res[$start]['pUnit'];
                $finalRes[$y]['codePiece'] = $res[$start]['codePiece'];
                $finalRes[$y]['Total'] = $sum[$start];
                $finalRes[$y]['Assemble'] = $assemble[$start];
                $finalRes[$y]['pName'] = $res[$start]['pName'];
                $finalRes[$y]['contradiction'] = ($res[$start]['se'] + $sum[$start]) - ($res[$start]['sw'] + $res[$start]['sh'] + $assemble[$start]);
                $start++;
            }
        }else{
            $pageAmount = ($listCount) - (($page-1) * 30);
            for ($y = 0; $y < $pageAmount; $y++) {
                $finalRes[$y]['sw'] = $res[$start]['sw'] . ' ' . $res[$start]['pUnit'];
                $finalRes[$y]['se'] = $res[$start]['se'] . ' ' . $res[$start]['pUnit'];
				$finalRes[$y]['sh'] = $res[$start]['sh'] . ' ' . $res[$start]['pUnit'];
                $finalRes[$y]['codePiece'] = $res[$start]['codePiece'];
                $finalRes[$y]['Total'] = $sum[$start];
                $finalRes[$y]['Assemble'] = $assemble[$start];
                $finalRes[$y]['pName'] = $res[$start]['pName'];
                $finalRes[$y]['contradiction'] = ($res[$start]['se'] + $sum[$start]) - ($res[$start]['sw'] + $res[$start]['sh'] + $assemble[$start]);
                $start++;
            }
        }
        return $finalRes;
    }
	
	public function getContradictionReportCountRows($cSDate,$cEDate,$cPName){
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(strlen(trim($cSDate)) > 0){
            $cSDate = $ut->jal_to_greg($cSDate);
            $w[] = '`programDate` >="'.$cSDate.'" ';
        }
        if(strlen(trim($cEDate)) > 0){
            $cEDate = $ut->jal_to_greg($cEDate);
            $w[] = '`programDate` <="'.$cEDate.'" ';
        }
		if(strlen(trim($cPName)) > 0){
            $w[] = '`pName`="'.$cPName.'" ';
        }

        $qu = "SELECT `PieceCode`,`pName`,`pUnit` FROM `program`
               INNER JOIN `interface` ON (`interface`.`ProductCode`=`program`.`goodCode`)
               INNER JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $qu .= " WHERE `Assembled`<`number` AND `Assembled` > 0 AND ".$where;
        }else {
            $qu .= " WHERE `Assembled`<`number` AND `Assembled` > 0";
        }
        $qu .= " GROUP BY `PieceCode`";
        $rslt = $db->ArrayQuery($qu);
        $cnt = count($rslt);

        $sql = "SELECT SUM(`waste`) AS `sw`,
                      `pUnit`,
                      `codePiece` FROM `program`
                INNER JOIN `other_piece` ON (`other_piece`.`program_id`=`program`.`RowID`)
                INNER JOIN `piece` ON (`piece`.`pCode`=`other_piece`.`codePiece`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE `Assembled` > 0 AND ".$where;
        }else{
            $sql .= " WHERE `Assembled` > 0";
        }
        $sql .= " GROUP BY `other_piece`.`codePiece`";
        $res = $db->ArrayQuery($sql);

        $listCount = count($res);
        $m = $listCount;
        for ($i=0;$i<$listCount;$i++){
            $arr[] = $res[$i]['codePiece'];
        }

        for ($a=0;$a<$cnt;$a++){
            if (in_array($rslt[$a]['PieceCode'], $arr)){
                continue;
            }else{
                $res[$m]['sw'] = 0;
                $res[$m]['se'] = 0;
                $res[$m]['codePiece'] = $rslt[$a]['PieceCode'];
                $res[$m]['pName'] = $rslt[$a]['pName'];
                $res[$m]['pUnit'] = $rslt[$a]['pUnit'];
                $m++;
            }
        }
        return count($res);
    }
	/*public function getContradictionReportCountRows($cSDate,$cEDate){
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(strlen(trim($cSDate)) > 0){
            $cSDate = $ut->jal_to_greg($cSDate);
            $w[] = '`programDate` >="'.$cSDate.'" ';
        }
        if(strlen(trim($cEDate)) > 0){
            $cEDate = $ut->jal_to_greg($cEDate);
            $w[] = '`programDate` <="'.$cEDate.'" ';
        }
        $sql = "SELECT SUM(`waste`) AS `sw`,
                      `pUnit` FROM `program`
                INNER JOIN `other_piece` ON (`other_piece`.`program_id`=`program`.`RowID`)
                INNER JOIN `piece` ON (`piece`.`pCode`=`other_piece`.`codePiece`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE `Assembled` > 0 AND ".$where;
        }else{
            $sql .= " WHERE `Assembled` > 0";
        }
        $sql .= " GROUP BY `other_piece`.`codePiece`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }*/
	
	public function contradictionReportExcel($cSDate,$cEDate,$cPName){
        $acm = new acm();
        if(!$acm->hasAccess('contradictionReport') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        $z = array();
        if(strlen(trim($cSDate)) > 0){
            $cSDate = $ut->jal_to_greg($cSDate);
            $w[] = '`programDate` >="'.$cSDate.'" ';
            $z[] = '`programDate` >="'.$cSDate.'" ';
        }
        if(strlen(trim($cEDate)) > 0){
            $cEDate = $ut->jal_to_greg($cEDate);
            $w[] = '`programDate` <="'.$cEDate.'" ';
            $z[] = '`programDate` <="'.$cEDate.'" ';
        }
        if(strlen(trim($cPName)) > 0){
            $w[] = '`pName`="'.$cPName.'" ';
        }

        $qu = "SELECT `PieceCode`,`pName`,`pUnit` FROM `program`
               INNER JOIN `interface` ON (`interface`.`ProductCode`=`program`.`goodCode`)
               INNER JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $qu .= " WHERE `Assembled`<`number` AND `Assembled` > 0 AND ".$where;
        }else {
            $qu .= " WHERE `Assembled`<`number` AND `Assembled` > 0";
        }
        $qu .= " GROUP BY `PieceCode`";
        $rslt = $db->ArrayQuery($qu);
        $cnt = count($rslt);

        $sql = "SELECT SUM(`waste`) AS `sw`,
                       SUM(`extra`) AS `se`,
					   SUM(`healthy`) AS `sh`,
                      `codePiece`,
                      `pName`,
                      `pUnit` FROM `program`
                INNER JOIN `other_piece` ON (`other_piece`.`program_id`=`program`.`RowID`)
                INNER JOIN `piece` ON (`piece`.`pCode`=`other_piece`.`codePiece`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE `Assembled` > 0 AND ".$where;
        }else{
            $sql .= " WHERE `Assembled` > 0";
        }
        $sql .= " GROUP BY `other_piece`.`codePiece`";
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);

        if(intval($cnt) > 0) {
            $m = $listCount;
            for ($i=0;$i<$listCount;$i++){
                $arr[] = $res[$i]['codePiece'];
            }
            for ($a = 0; $a < $cnt; $a++) {
                if (in_array($rslt[$a]['PieceCode'], $arr)) {
                    continue;
                } else {
                    $res[$m]['sw'] = 0;
                    $res[$m]['se'] = 0;
                    $res[$m]['sh'] = 0;
                    $res[$m]['codePiece'] = $rslt[$a]['PieceCode'];
                    $res[$m]['pName'] = $rslt[$a]['pName'];
                    $res[$m]['pUnit'] = $rslt[$a]['pUnit'];
                    $m++;
                }
            }
            $listCount = count($res);
        }

        $sql1 = "SELECT `number`,`Assembled`,`PieceRids`,`PieceAmounts` FROM `program` ";
        if(count($z) > 0){
            $where = implode(" AND ",$z);
            $sql1 .= " WHERE `Assembled` > 0 AND ".$where;
        }else{
            $sql1 .= " WHERE `Assembled` > 0";
        }
        $result = $db->ArrayQuery($sql1);
        $resultCount = count($result);
        $sum = array();
        $assemble = array();
        $x = 0;

        for ($j=0;$j<$listCount;$j++){
            $sql2 = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$res[$j]['codePiece']}'";
            $res2 = $db->ArrayQuery($sql2);
            $pcs[] = $res2[0]['RowID'];
        }

        for ($j=0;$j<$listCount;$j++){
            for ($c=0;$c<$resultCount;$c++){
                $PieceRids = explode(',', $result[$c]['PieceRids']);
                $PieceAmounts = explode(',', $result[$c]['PieceAmounts']);
                if (in_array($pcs[$j], $PieceRids)) {
                    $key = array_search($pcs[$j], $PieceRids);
                    $sum[$x] += ($PieceAmounts[$key]) * intval($result[$c]['number']);
                    $assemble[$x] += ($PieceAmounts[$key]) * intval($result[$c]['Assembled']);
                }
                if ($c == $resultCount - 1) {
                    $x++;
                }
            }
        }

        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['sw'] = $res[$y]['sw'].' '.$res[$y]['pUnit'];
            $finalRes[$y]['se'] = $res[$y]['se'].' '.$res[$y]['pUnit'];
            $finalRes[$y]['sh'] = $res[$y]['sh'] . ' ' . $res[$y]['pUnit'];
            $finalRes[$y]['codePiece'] = $res[$y]['codePiece'];
            $finalRes[$y]['Total'] = $sum[$y];
            $finalRes[$y]['Assemble'] = $assemble[$y];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
            $finalRes[$y]['contradiction'] = ($res[$y]['se'] + $sum[$y])-($res[$y]['sw'] + $res[$y]['sh'] + $assemble[$y]);
        }
        return $finalRes;
    }
}