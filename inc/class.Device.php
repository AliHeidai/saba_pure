<?php
class Device{

    public function __construct(){
        // do nothing
    }

    public function getDevicesProgramManageHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('devicesProgramManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();

        $device = $this->getDevices();
        $cnt = count($device);

        $pagename = array();
        $pageIcon = array();
        $contentId = array();
        $menuItems = array();
        $bottons = array();
        $headerSearch = array();
        $access = array();

        $x = 0;
        $y = 0;
        $z = 0;
        $manifold = 0;
        if($acm->hasAccess('devicesProgramManage')) {
            $pagename[$x] = "ثبت اطلاعات برنامه";
            $pageIcon[$x] = "fa-list";
            $contentId[$x] = "devicesProgramManageBody";
            $menuItems[$x] = 'devicesProgramManageTabID';

            $bottons1 = array();
            $headerSearch1 = array();

            $b = 0;
            $bottons1[$b]['title'] = "ثبت اطلاعات برنامه";
            $bottons1[$b]['jsf'] = "createDevicesProgram";
            $bottons1[$b]['icon'] = "fa-plus-square";

            $a = 0;
            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "90px";
            $headerSearch1[$a]['id'] = "devicesProgramManageSDateSearch";
            $headerSearch1[$a]['title'] = "از تاریخ";
            $headerSearch1[$a]['placeholder'] = "از تاریخ";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "90px";
            $headerSearch1[$a]['id'] = "devicesProgramManageEDateSearch";
            $headerSearch1[$a]['title'] = "تا تاریخ";
            $headerSearch1[$a]['placeholder'] = "تا تاریخ";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "devicesProgramManagePNameSearch";
            $headerSearch1[$a]['title'] = "قسمتی از نام قطعه";
            $headerSearch1[$a]['placeholder'] = "قسمتی از نام قطعه";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "devicesProgramManageGNameSearch";
            $headerSearch1[$a]['title'] = "قسمتی از نام محصول";
            $headerSearch1[$a]['placeholder'] = "قسمتی از نام محصول";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "devicesProgramManageFamilySearch";
            $headerSearch1[$a]['title'] = "نام خانوادگی ثبت کننده";
            $headerSearch1[$a]['placeholder'] = "نام خانوادگی ثبت کننده";
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['width'] = "220px";
            $headerSearch1[$a]['id'] = "devicesProgramManageDeviceSearch";
            $headerSearch1[$a]['title'] = "نام دستگاه";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = "نام دستگاه";
            $headerSearch1[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cnt;$i++){
                $headerSearch1[$a]['options'][$i+1]["title"] = $device[$i]['deviceName'];
                $headerSearch1[$a]['options'][$i+1]["value"] = $device[$i]['RowID'];
            }
            $a++;

            $headerSearch1[$a]['type'] = "btn";
            $headerSearch1[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch1[$a]['jsf'] = "showDevicesProgramManageList";

            $bottons[$y] = $bottons1;
            $headerSearch[$z] = $headerSearch1;

            $manifold++;
            $access[] = 1;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('editCreateDevices')) {
            $pagename[$x] = "نام دستگاه ها";
            $pageIcon[$x] = "fa-computer";
            $contentId[$x] = "editCreateDevicesManageBody";
            $menuItems[$x] = 'editCreateDevicesManageTabID';

            $bottons2 = array();
            $headerSearch2 = array();

            $b = 0;
            $bottons2[$b]['title'] = "ثبت دستگاه";
            $bottons2[$b]['jsf'] = "createDevices";
            $bottons2[$b]['icon'] = "fa-plus-square";
            $b++;

            $bottons2[$b]['title'] = "ویرایش دستگاه";
            $bottons2[$b]['jsf'] = "editDevices";
            $bottons2[$b]['icon'] = "fa-edit";

            $bottons[$y] = $bottons2;
            $headerSearch[$z] = $headerSearch2;

            $manifold++;
            $access[] = 2;
        }
        $htm = $ut->getHtmlOfDefaultManagementMultiPage($pagename,$pageIcon,$contentId,$menuItems,$bottons,$manifold,$headerSearch);
        //++++++++++++++++++++++++++++++++++ edit Create Devices MODAL ++++++++++++++++++++++++++++++++
        $modalID = "editCreateDevicesModal";
        $modalTitle = "فرم ثبت / ویرایش دستگاه";
        $style = 'style="max-width: 651px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "editCreateDevicesName";
        $items[$c]['title'] = "نام دستگاه";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "editCreateDevicesHiddenDid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateDevices";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateDevices = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF edit Create Devices MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ devices Program Manage MODAL ++++++++++++++++++++++++++++++++
        $modalID = "devicesProgramManageModal";
        $modalTitle = "ثبت اطلاعات برنامه";
        $style = 'style="max-width: 651px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "devicesProgramManageFName";
        $items[$c]['title'] = "نام";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "devicesProgramManageLName";
        $items[$c]['title'] = "نام خانوادگی";
        $items[$c]['placeholder'] = "نام خانوادگی";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "devicesProgramManageDevicesName";
        $items[$c]['title'] = "نام دستگاه";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cnt;$i++){
            $items[$c]['options'][$i+1]["title"] = $device[$i]['deviceName'];
            $items[$c]['options'][$i+1]["value"] = $device[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "devicesProgramManagePName";
        $items[$c]['title'] = "نام قطعه تولیدی";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "devicesProgramManageGName";
        $items[$c]['title'] = "نام محصول";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "devicesProgramManageDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCreateDevicesProgram";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $devicesProgramManage = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF devices Program Manage MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++ attach File To Device Program Modal ++++++++++++++++++++++
        $modalID = "attachFileToDeviceProgramModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'attachFileToDeviceProgram-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "attachFileToDeviceProgramFileName";
        $items[$c]['title'] = "نام فایل";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "attachFileToDeviceProgramFile";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "attachFileToDeviceProgramID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachFileToDeviceProgram";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $attachFileToDeviceProgram = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End attach File To Device Program Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show attach File To Device Program Modal ++++++++++++++++++++++
        $modalID = "showAttachFileToDeviceProgramModal";
        $modalTitle = "ضمیمه ها";
        $ShowDescription = 'showAttachFileToDeviceProgram-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showAttachFileToDeviceProgram = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End show attach File To Device Program Modal ++++++++++++++++++++++++
        $htm .= $editCreateDevices;
        $htm .= $devicesProgramManage;
        $htm .= $attachFileToDeviceProgram;
        $htm .= $showAttachFileToDeviceProgram;
        return array($htm,$access);
    }

    public function getDevicesProgramManageList($sDate,$eDate,$pName,$gName,$family,$deviceID,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('devicesProgramManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`createDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`createDate` <="'.$eDate.'" ';
        }
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($family)) > 0){
            $w[] = '`lname` LIKE "%'.$family.'%" ';
        }
        if(intval($deviceID) > 0){
            $w[] = '`deviceID`='.$deviceID.' ';
        }

        $sql = "SELECT `devices_program`.*,`deviceName` FROM `devices_program` INNER JOIN `devices` ON (`devices_program`.`deviceID`=`devices`.`RowID`) ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['Name'] = $res[$y]['fname'].' '.$res[$y]['lname'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
            $finalRes[$y]['createDate'] = $ut->greg_to_jal($res[$y]['createDate']);
            $finalRes[$y]['createTime'] = $res[$y]['createTime'];
            $finalRes[$y]['description'] = $res[$y]['description'];
            $finalRes[$y]['deviceName'] = $res[$y]['deviceName'];
        }
        return $finalRes;
    }

    public function getDevicesProgramManageListCountRows($sDate,$eDate,$pName,$gName,$family,$deviceID){
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`createDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`createDate` <="'.$eDate.'" ';
        }
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($family)) > 0){
            $w[] = '`lname` LIKE "%'.$family.'%" ';
        }
        if(intval($deviceID) > 0){
            $w[] = '`deviceID`='.$deviceID.' ';
        }

        $sql = "SELECT `RowID` FROM `devices_program` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getDevicesManageList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('editCreateDevices')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;

        $sql = "SELECT * FROM `devices` WHERE `isEnable`=1";
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['deviceName'] = $res[$y]['deviceName'];
        }
        return $finalRes;
    }

    public function getDevicesManageListCountRows(){
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `devices` WHERE `isEnable`=1";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function createDevices($name){
        $acm = new acm();
        if(!$acm->hasAccess('editCreateDevices')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "INSERT INTO `devices` (`deviceName`) VALUES ('{$name}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editDevices($did,$name){
        $acm = new acm();
        if(!$acm->hasAccess('editCreateDevices')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `devices` SET `deviceName`='{$name}' WHERE `RowID`={$did}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if (intval($res)) {
            return true;
        } else {
            return false;
        }
    }

    public function createDevicesProgram($fname,$lname,$dName,$pName,$gName,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('devicesProgramManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');
        $sql = "INSERT INTO `devices_program` (`fname`,`lname`,`deviceID`,`pName`,`gName`,`createDate`,`createTime`,`description`) VALUES ('{$fname}','{$lname}',{$dName},'{$pName}','{$gName}','{$nowDate}','{$nowTime}','{$desc}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function attachedDeviceProgramFileHtm($dpID){
        $db = new DBi();
        $sql = "SELECT `RowID`,`info`,`fileName` FROM `devices_program_attachment` WHERE `dpID`={$dpID}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        if (strlen(trim($res[0]['fileName'])) > 0) {
            $htm .= '<table class="table table-bordered table-hover table-sm" id="attachedDeviceProgramFileHtm-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">ردیف</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 73%;">نام فایل</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">لینک دانلود</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $iterator = 0;
            for ($i=0;$i<$cnt;$i++) {
                $iterator++;
                $link = ADDR . 'attachment/' . $res[$i]['fileName'];
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['info'].'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
                $htm .= '</tr>';
            }

            $htm .= '</tbody>';
            $htm .= '</table>';
        }
        return $htm;
    }

    public function attachFileToDeviceProgram($dpID,$info,$files){
        $db = new DBi();
        $SFile = array();
        $SFormat = array();
        //$allowedTypes = ['tif','jpg','jpeg','png','pdf','cdr','ai','TIF','JPG','JPEG','PNG','PDF','CDR','AI'];
        if (isset($files) && !empty($files)) {
            $no_files = count($files['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files["tmp_name"][$i];
                if ($files["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files['name'][$i], strpos($files['name'][$i], ".") + 1);
/*                if(!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }*/
                $SFile[] = "devicePrg" . rand(0, time()).'.'.$format;
                $SFormat[] = $format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');
        $cnt = count($SFile);
        for ($i=0;$i<$cnt;$i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i],'../attachment/'.$SFile[$i]);
            $sql4 = "INSERT INTO `devices_program_attachment` (`dpID`,`info`,`fileName`,`createDate`,`createTime`,`uid`) VALUES ({$dpID},'{$info}','{$SFile[$i]}','{$nowDate}','{$nowTime}',{$_SESSION['userid']})";
            $db->Query($sql4);
        }
        return true;
    }

    public function deviceInfo($did){
        $db = new DBi();
        $sql = "SELECT `deviceName` FROM `devices` WHERE `RowID`=".$did;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("did"=>$did,"deviceName"=>$res[0]['deviceName']);
            return $res;
        }else{
            return false;
        }
    }

    private function getDevices(){
        $db = new DBi();
        $sql = "SELECT * FROM `devices` WHERE `isEnable`=1";
        return $db->ArrayQuery($sql);
    }

}