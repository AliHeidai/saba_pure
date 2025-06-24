<?php

class Phone{

    public function __construct (){
        // do nothing
    }

    public function getContactToPersonnelHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('contactToPersonnel')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $rate = new Rates();
        $salary = new Salary();

        $res = $rate->getUnits();
        $cnt = count($res);

        $users = $salary->getAllPersonnel();
        $cntu = count($users);

        $pagename = "ارتباط با پرسنل";
        $pageIcon = "fas fa-phone";
        $contentId = "contactToPersonnelBody";
        $bottons= array();
        $headerSearch = array();

        $c = 0;
        if($acm->hasAccess('editCreateContactToPersonnel')) {
            $bottons[$c]['title'] = "ایجاد داخلی جدید";
            $bottons[$c]['jsf'] = "createPhone";
            $bottons[$c]['icon'] = "fa-plus-square";
            $c++;

            $bottons[$c]['title'] = "ویرایش داخلی";
            $bottons[$c]['jsf'] = "editPhone";
            $bottons[$c]['icon'] = "fa-edit";
            $c++;

            $bottons[$c]['title'] = "حذف داخلی";
            $bottons[$c]['jsf'] = "deletePhone";
            $bottons[$c]['icon'] = "fa-minus-square";
        }

        $a = 0;
        $headerSearch[$a]['type'] = "select";
        $headerSearch[$a]['id'] = "contactToPersonnelNameSearch";
        $headerSearch[$a]['multiple'] = "multiple";
        $headerSearch[$a]['LimitNumSelections'] = 1;
        $headerSearch[$a]['title'] = "نام";
        $headerSearch[$a]['options'] = array();
        for ($i=0;$i<$cntu;$i++){
            $headerSearch[$a]['options'][$i]["title"] = $users[$i]['Fname'].' '.$users[$i]['Lname'];
            $headerSearch[$a]['options'][$i]["value"] = $users[$i]['RowID'];
        }
        $a++;

        $headerSearch[$a]['type'] = "select";
        $headerSearch[$a]['id'] = "contactToPersonnelUnitSearch";
        $headerSearch[$a]['multiple'] = "multiple";
        $headerSearch[$a]['LimitNumSelections'] = 1;
        $headerSearch[$a]['title'] = "واحد سازمانی";
        $headerSearch[$a]['options'] = array();
        for ($i=0;$i<$cnt;$i++){
            $headerSearch[$a]['options'][$i]["title"] = $res[$i]['Uname'];
            $headerSearch[$a]['options'][$i]["value"] = $res[$i]['RowID'];
        }
        $a++;

        $headerSearch[$a]['type'] = "btn";
        $headerSearch[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
        $headerSearch[$a]['jsf'] = "showContactToPersonnelList";

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        //++++++++++++++++++++++++++++++++++EDIT CREATE MODAL++++++++++++++++++++++++++++++++
        $modalID = "contactToPersonnelModal";
        $modalTitle = "فرم ایجاد/ویرایش شماره های داخلی";

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "contactToPersonnelName";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['LimitNumSelections'] = 2;
        $items[$c]['title'] = "نام";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cntu;$i++){
            $items[$c]['options'][$i+1]["title"] = $users[$i]['Fname'].' '.$users[$i]['Lname'];
            $items[$c]['options'][$i+1]["value"] = $users[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "contactToPersonnelUnit";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['LimitNumSelections'] = 1;
        $items[$c]['title'] = "واحد سازمانی";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = '----------';
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cnt;$i++){
            $items[$c]['options'][$i+1]["title"] = $res[$i]['Uname'];
            $items[$c]['options'][$i+1]["value"] = $res[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contactToPersonnelPost";
        $items[$c]['title'] = "سمت";
        $items[$c]['placeholder'] = "سمت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contactToPersonnelPhone1";
        $items[$c]['title'] = "شماره داخلی 1";
        $items[$c]['placeholder'] = "شماره داخلی 1";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contactToPersonnelPhone2";
        $items[$c]['title'] = "شماره داخلی 2";
        $items[$c]['placeholder'] = "شماره داخلی 2";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contactToPersonnelPhone3";
        $items[$c]['title'] = "شماره داخلی 3";
        $items[$c]['placeholder'] = "شماره داخلی 3";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contactToPersonnelEmail";
        $items[$c]['title'] = "ایمیل";
        $items[$c]['placeholder'] = "ایمیل";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contactToPersonnelMobile";
        $items[$c]['title'] = "شماره همراه";
        $items[$c]['placeholder'] = "شماره همراه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contactToOrganizationMobile";
        $items[$c]['title'] = " شماره همراه سازمانی ";
        $items[$c]['placeholder'] = "شماره همراه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contactToPersonnelColorCode";
        $items[$c]['title'] = "کد رنگ";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "contactToPersonnelHiddenCid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreatePhone";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ DELETE MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "contactToPersonnelDeleteModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به حذف این داخلی مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "contactToPersonnel_deleteIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "dodeletePhone";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $delModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF DELETE MODAL++++++++++++++++++++++++++++
        $htm .= $editCreateModal;
        $htm .= $delModal;
        return $htm;
    }

    public function getContactToPersonnelList($name,$unit,$page=1){
        $acm = new acm();
        $ut=new Utility();
        if(!$acm->hasAccess('contactToPersonnel')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $w = array();
        if(intval($unit) > 0){
            $w[] = '`Unit`='.$unit.' ';
        }

        $sql = "SELECT `RowID` AS `cid`,`Name`,`Unit`,`Number1`,`Number2`,`Number3`,`email`,`mobile`,`color`,`post` FROM `phone`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `orderList` ASC,`Unit` DESC,`RowID` ASC ";
        $res = $db->ArrayQuery($sql);

        $listCount = count($res);
        $finalRes = array();
        $flag = true;

        for($y=0;$y<$listCount;$y++){
            if(strlen(trim($name)) > 0){
                $arr = explode(',',$res[$y]['Name']);
                if (!in_array(intval($name),$arr)){
                    continue;
                }else{
                    $res[$y]['Name'] = $name;
                    $flag = false;
                }
            }else{
                $flag = false;
            }
            $query = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$res[$y]['Unit']}";
            $rst = $db->ArrayQuery($query);

            $query1 = "SELECT `Fname`,`Lname`,`mobile`,`phone` FROM `personnel` WHERE `RowID` IN ({$res[$y]['Name']})";
            $rst1 = $db->ArrayQuery($query1);
            $ccnt = count($rst1);
            $names = array();
            $mobiles = array();
            $phones=array();
            for ($i=0;$i<$ccnt;$i++){
                $names[] = $rst1[$i]['Fname'].' '.$rst1[$i]['Lname'];
                $mobiles[] = $rst1[$i]['mobile'];
                $phones[] = $rst1[$i]['phone'];
            }
            $names = implode(' و ',$names);
            $mobiles = implode(' و ',$mobiles);
            $phones = implode(' و ',$phones);

            $finalRes[$y]['RowID'] = $res[$y]['cid'];
            $finalRes[$y]['Name'] = $names;
            $finalRes[$y]['Unit'] = $rst[0]['Uname'];
            $finalRes[$y]['Number1'] = $res[$y]['Number1'];
            $finalRes[$y]['Number2'] = (intval($res[$y]['Number2']) == 0 ? '' : $res[$y]['Number2']);
            $finalRes[$y]['Number3'] = (intval($res[$y]['Number3']) == 0 ? '' : $res[$y]['Number3']);
            $finalRes[$y]['email'] = $res[$y]['email'];
            $finalRes[$y]['mobile'] = $mobiles.",".$phones;
            $finalRes[$y]['trColor'] = $res[$y]['color'];
            $finalRes[$y]['post'] = $res[$y]['post'];
        }

        if ($flag){
            $query1 = "SELECT `Fname`,`Lname`,`mobile`,`Unit_id` FROM `personnel` WHERE `RowID` IN ({$name})";
            $ut->fileRecorder($query1);
            $rst1 = $db->ArrayQuery($query1);
            $mobiles=[];
            if(count($rst1)>1){
               foreach($rst1 as $k=>$value){
                    $mobiles[]=$value['mobile'];
               }
               $mobiles=implode(",",$mobiles);
            }
            else{
                $mobiles= $rst1[0]['mobile'];
            }

            $query = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$rst1[0]['Unit_id']}";
            $rst = $db->ArrayQuery($query);

            $finalRes[0]['RowID'] = 0;
            $finalRes[0]['Name'] = $rst1[0]['Fname'].' '.$rst1[0]['Lname'];
            $finalRes[0]['Unit'] = $rst[0]['Uname'];
            $finalRes[0]['Number1'] = '';
            $finalRes[0]['Number2'] = '';
            $finalRes[0]['Number3'] = '';
            $finalRes[0]['email'] = '';
            $finalRes[0]['mobile'] = $mobiles;
            $finalRes[0]['trColor'] = '#ddd';
            $finalRes[0]['post'] = '';
        }

        $finalRes = array_values($finalRes);
        return $finalRes;
    }

    public function getContactToPersonnelListCountRows($name,$unit){
        $db = new DBi();
        $w = array();
        if(intval($unit) > 0){
            $w[] = '`Unit`='.$unit.' ';
        }
        $sql = "SELECT `RowID`,`Name` FROM `phone`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $counter = 0;

        for ($y=0;$y<$listCount;$y++){
            if(strlen(trim($name)) > 0){
                $arr = explode(',',$res[$y]['Name']);
                if (!in_array(intval($name),$arr)){
                    continue;
                }
            }
            $counter++;
        }
        return $counter;
    }

    public function contactToPersonnelInfo($cid){
        $db = new DBi();
        $sql = "SELECT `Name`,`Unit`,`Number1`,`Number2`,`Number3`,`email`,`mobile`,`color`,`post` FROM `phone` WHERE `RowID` = ".$cid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("cid" => $cid,
                "Name" => $res[0]['Name'],
                "Unit" => $res[0]['Unit'],
                "Number1" => $res[0]['Number1'],
                "Number2" => $res[0]['Number2'],
                "Number3" => $res[0]['Number3'],
                "email" => $res[0]['email'],
                "mobile" => $res[0]['mobile'],
                "post" => $res[0]['post'],
                "color" => $res[0]['color']
            );
            return $res;
        }else{
            return false;
        }
    }

    public function createPhone($name,$unit,$post,$phone1,$phone2,$phone3,$email,$mobile,$code,$o_phone){
        $acm = new acm();
        $ut=new Utility();
        if(!$acm->hasAccess('contactToPersonnel')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "INSERT INTO `phone` (`Name`,`Unit`,`Number1`,`Number2`,`Number3`,`email`,`mobile`,`color`,`post`,`organization_mobile`) VALUES 
        ('{$name}','{$unit}','{$phone1}','{$phone2}','{$phone3}','{$email}','{$mobile}','{$code}','{$post}','{$o_phone}')";
        $ut->fileRecorder($sql);
        $db->Query($sql);
        $id = $db->InsertrdID();
        if(intval($id) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editPhone($cid,$name,$unit,$post,$phone1,$phone2,$phone3,$email,$mobile,$code,$o_phone){
        $acm = new acm();
        if(!$acm->hasAccess('contactToPersonnel')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `phone` SET `Name`='{$name}',`Unit`='{$unit}',`Number1`={$phone1},`Number2`={$phone2},`Number3`={$phone3},`email`='{$email}',`mobile`='{$mobile}',`color`='{$code}',`post`='{$post}',`organization_mobile`={$o_phone} WHERE `RowID`={$cid} ";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if(intval($res)){
            return true;
        }else{
            return false;
        }
    }

    public function dodeletePhone($cid){
        $acm = new acm();
        if(!$acm->hasAccess('contactToPersonnel')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "DELETE FROM `phone` WHERE `RowID`={$cid}";
        $db->Query($sql);
        $ar = $db->AffectedRows();
        $ar = (($ar == -1 || $ar == 0) ? 0 : 1);
        if(intval($ar) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getContactToPersonnelListHtm($name,$unit){
        $db = new DBi();
        $w = array();
        if(intval($unit) > 0){
            $w[] = '`Unit`='.$unit.' ';
        }

        $sql = "SELECT `RowID` AS `cid`,`Name`,`Unit`,`Number1`,`Number2`,`Number3`,`email`,`mobile`,`color`,`post` FROM `phone`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `orderList` ASC,`Unit` ASC,`RowID` ASC ";
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm m-3" id="contactToPersonnelBody1-table">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-secondary text-warning">';
        $htm .= '<td style="width:20%;text-align: center;font-family: dubai-Regular;font-weight: bold;">نام</td>';
        $htm .= '<td style="width:15%;text-align: center;font-family: dubai-Regular;font-weight: bold;">سمت</td>';
        $htm .= '<td style="width:15%;text-align: center;font-family: dubai-Regular;font-weight: bold;">واحد سازمانی</td>';
        $htm .= '<td style="width:10%;text-align: center;font-family: dubai-Regular;font-weight: bold;">شماره داخلی 1</td>';
        $htm .= '<td style="width:10%;text-align: center;font-family: dubai-Regular;font-weight: bold;">شماره داخلی 2</td>';
        $htm .= '<td style="width:10%;text-align: center;font-family: dubai-Regular;font-weight: bold;">شماره داخلی 3</td>';
        $htm .= '<td style="width:20%;text-align: center;font-family: dubai-Regular;font-weight: bold;">ایمیل</td>';
        //$htm .= '<td style="width:15%;text-align: center;font-family: dubai-Regular;font-weight: bold;">شماره همراه</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $flag = true;

        for($y=0;$y<$listCount;$y++) {
            if(strlen(trim($name)) > 0){
                $arr = explode(',',$res[$y]['Name']);
                if (!in_array(intval($name),$arr)){
                    continue;
                }else{
                    $res[$y]['Name'] = $name;
                    $flag = false;
                }
            }else{
                $flag = false;
            }

            $query = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$res[$y]['Unit']}";
            $rst = $db->ArrayQuery($query);

            $query1 = "SELECT `Fname`,`Lname`,`mobile` FROM `personnel` WHERE `RowID` IN ({$res[$y]['Name']})";
            $rst1 = $db->ArrayQuery($query1);
            $ccnt = count($rst1);
            $names = array();
            for ($i=0;$i<$ccnt;$i++){
                $names[] = $rst1[$i]['Fname'].' '.$rst1[$i]['Lname'];
            }
            $names = implode(' و ',$names);

            //$res[$y]['mobile'] = (strlen(trim($res[$y]['mobile'])) > 0 ? $res[$y]['mobile'] : $rst1[0]['mobile']);

            $htm .= '<tr style="background-color: '.$res[$y]['color'].'">';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;">'.$names.'</td>';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;">'.$res[$y]['post'].'</td>';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;">'.$rst[0]['Uname'].'</td>';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;">'.$res[$y]['Number1'].'</td>';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;">'.(intval($res[$y]['Number2']) == 0 ? '' : $res[$y]['Number2']).'</td>';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;">'.(intval($res[$y]['Number3']) == 0 ? '' : $res[$y]['Number3']).'</td>';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;">'.$res[$y]['email'].'</td>';
            //$htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;">'.$res[$y]['mobile'].'</td>';
            $htm .= '</tr>';
        }

        if ($flag){
            $query1 = "SELECT `Fname`,`Lname`,`mobile`,`Unit_id` FROM `personnel` WHERE `RowID` IN ({$name})";
            $rst1 = $db->ArrayQuery($query1);

            $query = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$rst1[0]['Unit_id']}";
            $rst = $db->ArrayQuery($query);

            $htm .= '<tr style="background-color: '.$res[$y]['color'].'">';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;">'.$rst1[0]['Fname'].' '.$rst1[0]['Lname'].'</td>';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;"></td>';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;">'.$rst[0]['Uname'].'</td>';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;"></td>';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;"></td>';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;"></td>';
            $htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;"></td>';
            //$htm .= '<td style="font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;">'.$rst1[0]['mobile'].'</td>';
            $htm .= '</tr>';
        }

        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

}