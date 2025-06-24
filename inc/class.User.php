<?php
/**
 * Created by PhpStorm.
 * User: MajidEbrahimi
 * Date: 4/17/2018
 * Time: 13:13
 */

class User{

    public function __construct (){
        // do nothing
    }

    public function getUserManagementHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('userManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $rates = new Rates();
        $units = $rates->getUnits();
        $cnt = count($units);

        $pagename = "مدیریت کاربران";
        $pageIcon = "fas fa-users";
        $contentId = "userManageBody";

        $c = 0;
        $bottons= array();
        $bottons[$c]['title'] = "ایجاد کاربر جدید";
        $bottons[$c]['jsf'] = "createUser";
        $bottons[$c]['icon'] = "fa-plus-square";
        $c++;

        $bottons[$c]['title'] = "ویرایش کاربر";
        $bottons[$c]['jsf'] = "editUser";
        $bottons[$c]['icon'] = "fa-edit";
        $c++;

        $bottons[$c]['title'] = "حذف کاربر";
        $bottons[$c]['jsf'] = "deleteUser";
        $bottons[$c]['icon'] = "fa-minus-square";

        if($acm->hasAccess("changeUsersAccess")) {
            $c++;
            $bottons[$c]['title'] = "ویرایش دسترسی های کاربر";
            $bottons[$c]['jsf'] = "editUserAccess";
            $bottons[$c]['icon'] = "fa-edit";
        }
		
		if($acm->hasAccess('manageUserIP'))	{
			$c++;
			$bottons[$c]['title'] = "مدیریت IP های اختصاص داده شده به کاربر";
			$bottons[$c]['jsf'] = "activeUsersManage";
			$bottons[$c]['icon'] = "fa-user";
		}

        $headerSearch = array();
        $headerSearch[0]['type'] = "text";
        $headerSearch[0]['width'] = "220px";
        $headerSearch[0]['id'] = "usermanagementUsernameSearch";
        $headerSearch[0]['title'] = "نام کاربر";
        $headerSearch[0]['placeholder'] = "نام کاربر";

        $headerSearch[1]['type'] = "select";
        $headerSearch[1]['width'] = "100px";
        $headerSearch[1]['id'] = "usermanagementUserStatusSearch";
        $headerSearch[1]['title'] = "وضعیت کاربر";
        $headerSearch[1]['options'] = array();
        $headerSearch[1]['options'][0]["title"] = "همه";
        $headerSearch[1]['options'][0]["value"] = "-1";
        $headerSearch[1]['options'][1]["title"] = "فعال";
        $headerSearch[1]['options'][1]["value"] = "1";
        $headerSearch[1]['options'][2]["title"] = "غیرفعال";
        $headerSearch[1]['options'][2]["value"] = "0";

        $headerSearch[2]['type'] = "btn";
        $headerSearch[2]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search fa-sm'></i>";
        $headerSearch[2]['jsf'] = "showUserList";

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        //++++++++++++++++++++++++++++++++++EDIT CREATE MODAL++++++++++++++++++++++++++++++++
        $modalID = "userManagmentModal";
        $modalTitle = "فرم ایجاد/ویرایش کاربر";
        $txt = 'نکته : تمامی فیلدها باید تکمیل شوند !!!';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "userManagmentFname";
        $items[$c]['title'] = "نام";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "userManagmentLname";
        $items[$c]['title'] = "نام خانوادگی";
        $items[$c]['placeholder'] = "نام خانوادگی";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "userManagmentUsername";
        $items[$c]['title'] = "نام کاربری";
        $items[$c]['placeholder'] = "نام کاربری";
        $c++;

        $items[$c]['type'] = "password";
        $items[$c]['id'] = "userManagmentps";
        $items[$c]['title'] = "کلمه عبور";
        $items[$c]['placeholder'] = "کلمه عبور";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "userManagmentUserType";
        $items[$c]['title'] = "جنسیت";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "مرد";
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = "زن";
        $items[$c]['options'][1]["value"] = 1;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "userManagmentUnitID";
        $items[$c]['title'] = "واحد";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cnt;$i++) {
            $items[$c]['options'][$i+1]["title"] = $units[$i]['Uname'];
            $items[$c]['options'][$i+1]["value"] = $units[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "userManagmentFatherName";
        $items[$c]['title'] = "نام پدر";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "userManagmentCodeMelli";
        $items[$c]['title'] = "کد ملی";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "userManagmentBirthYear";
        $items[$c]['title'] = "سال تولد";
        $items[$c]['placeholder'] = "سال";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "userManagmentPhone";
        $items[$c]['title'] = "شماره همراه";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "userManagmentPostJob";
        $items[$c]['title'] = "سمت";
        $items[$c]['placeholder'] = "سمت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "userManagmentSignature";
        $items[$c]['title'] = "نام فایل امضا";
        $items[$c]['placeholder'] = "نام فایل امضا";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "userManagmentUserStatus";
        $items[$c]['title'] = "وضعیت کاربر";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "فعال";
        $items[$c]['options'][0]["value"] = "1";
        $items[$c]['options'][1]["title"] = "غیرفعال";
        $items[$c]['options'][1]["value"] = "0";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "userManagmentUserIP";
        $items[$c]['title'] = "IP اختصاص داده شده به کاربر ";
       
        $c++;


        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageUserHiddenUid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateUser";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$txt);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ START OF active users status MODAL +++++++++++++++++++++++++++++++++++++
		$modalID = "activeUsersManageModal";
        $modalTitle = "مدیریت IP های اختصاص داده شده به کاربر";
		
		$ShowDescription = 'activeUsersManageModalBody';
        $c = 0;

        $items = array();
		
		$items[$c]['type'] = "select";
        $items[$c]['id'] = "ListenIPSelect";
        $items[$c]['title'] = "IP کاربر رهگیری شود؟";
		$items[$c]['options'] = array();
        //$items[$c]['onchange'] = "onchange=getReportSubLayerTwo()";
        $items[$c]['options'][0]["title"] = "رهگیری شود";
        $items[$c]['options'][0]["value"] = 1;
		$items[$c]['options'][1]["title"] = "رهگیری نشود";
        $items[$c]['options'][1]["value"] = 0;
        
      
		$c++;
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "userManagmentIP";
        $items[$c]['title'] = "Ip کاربر";
        $items[$c]['placeholder'] = "Ip کاربر";

        $c++;
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "selectedUserIP";

        $topperBottons = array();
        $topperBottons[0]['title'] = "تایید";
        $topperBottons[0]['jsf'] = "addUserIP";
        $topperBottons[0]['type'] = "btn";
        $topperBottons[0]['data-dismiss'] = "NO";
        $topperBottons[1]['title'] = "انصراف";
        $topperBottons[1]['type'] = "dismis";
		
        $activeUsersModal = $ut->getHtmlModal($modalID,$modalTitle,$items,'',$modalTxt,'','',$ShowDescription,'','',$topperBottons);

        //++++++++++++++++++++++++++++++ END OF active users status MODA +++++++++++++++++++++++++++++++++++++
        

        //++++++++++++++++++++++++++++++ DELETE MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "manageDeleteUserModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به حذف این کاربر مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "usermanage_deleteIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "dodeleteUser";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $delModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF DELETE MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++START ACCESS MODAL ++++++++++++++++++++++++++++
        $modalID = "userManagmentAccessModal";
        $modalTitle = "فرم ویرایش دسترسی های کاربر";
        $ShowDescription = 'user-management-allaccess';
        $style = 'style="max-width: 90vw;"';
        $items = array();
        $k=0;
       
        $items[$k]['type']="select";
        $items[$k]['title']="کپی دسترسی ها از ";
        $items[$k]['options'][0]['title']="کابر مرجع را انتخاب نمایید";
        $items[$k]['options'][0]['value']="0";
        $items[$k]['id']='select_origin_user';
        // $items[1]['type']="select";
        // $items[1]['title']=" انتخاب کاربر مرجع";
        // $items[1]['options'][0]="کابر مرجع را انتخاب نمایید";
       
        // $k++;
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditAccessUser";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $accessModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++END ACCESS MODAL +++++++++++++++
        $htm .= $editCreateModal;
        $htm .= $delModal;
        $htm .= $accessModal;
        $htm .= $activeUsersModal;
        return $htm;
    }

    public function getUserList($name,$userStatus,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('userManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($name)) > 0){
            $w[] = '`users`.`fname` LIKE "%'.$name.'%" OR `users`.`lname` LIKE "%'.$name.'%" ';
        }
        if(intval($userStatus)>=0){
            $w[] = '`users`.`IsEnable` ='.$userStatus.' ';
        }

        $sql = "SELECT `RowID` AS `uid`,
                       `username`,
                       `fname`,
                       `lname`,
                       `IsEnable`,
                       `IsAdmin`
                       FROM `users`
                       ";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
        $finalRes[$y]['RowID'] = $res[$y]['uid'];
        $finalRes[$y]['username'] = $res[$y]['username'];
        $finalRes[$y]['name'] = $res[$y]['fname'].' '.$res[$y]['lname'];
        if(intval($res[$y]['IsEnable']) == 1){
            $finalRes[$y]['isEnableTxt'] = 'فعال';
            $finalRes[$y]['txtco'] = 'green';
        }else{
            $finalRes[$y]['isEnableTxt'] = 'غیر فعال';
            $finalRes[$y]['txtco'] = 'red';
        }
        $finalRes[$y]['IsAdmin'] = (intval($res[$y]['IsAdmin']) == 1 ? 'کاربر عادی' : 'کاربر معمولی');
        }
        return $finalRes;
    }

    public function getUserListCountRows($name,$userStatus){
        $db = new DBi();
        $w = array();
        if(strlen(trim($name)) > 0){
            $w[] = '`users`.`fname` LIKE "%'.$name.'%" OR `users`.`lname` LIKE "%'.$name.'%" ';
        }
        if(intval($userStatus)>=0){
            $w[] = '`users`.`IsEnable` ='.$userStatus.' ';
        }

        $sql = "SELECT `RowID` FROM `users`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function UserInfo($uid){
        $db = new DBi();
        $sql = "SELECT * FROM `users` WHERE `RowID` = ".$uid;
        $res = $db->ArrayQuery($sql);
        $ipSql="SELECT * FROM user_pc_info WHERE userID=".$uid;
        $res_ip = $db->ArrayQuery($ipSql);
        $ip_array=[];
        foreach($res_ip as $res_ip_key=>$res_ip_value){
            $ip_array[]=$res_ip_value['pc_ip'];
        }
        //error_log('testIP:'.print_r($ip_array,true));
        $user_ips=implode(",",$ip_array);
        //error_log('testIPImplod:'.$user_ips);
        if(count($res) == 1){
            $res = array("uid"=>$uid,
                         "fname"=>$res[0]['fname'],
                         "lname"=>$res[0]['lname'],
                         "username"=>$res[0]['username'],
                         "ut"=>$res[0]['gender'],
                         "us"=>$res[0]['IsEnable'],
                         "phone"=>$res[0]['phone'],
                         "postJob"=>$res[0]['postJob'],
                         "signature"=>$res[0]['signature'],
                         "unitID"=>$res[0]['unitID'],
                         "fatherName"=>$res[0]['fatherName'],
                         "codeMelli"=>$res[0]['codeMelli'],
                         "birthYear"=>$res[0]['birthYear'],
                         "user_ip"=>$user_ips
                        );
            return $res;
        }else{
            return false;
        }
    }

    public function createUser($fname,$lname,$username,$ut,$us,$ps,$unitID,$fatherName,$codeMelli,$birthYear,$phone,$postJob,$signature){
        $acm = new acm();
        if(!$acm->hasAccess('userManagement')){
            die("access denied");
            exit;
        }
        $utility = new Utility();
        $db = new DBi();
        $pass = $utility->MackHash(trim($ps));
        $sql = "INSERT INTO `users` (`fname`,`lname`,`username`,`pass`,`IsEnable`,`gender`,`unitID`,`fatherName`,`codeMelli`,`birthYear`,`phone`,`postJob`,`signature`) 
                VALUES('{$fname}','{$lname}','{$username}','{$pass}',{$us},{$ut},{$unitID},'{$fatherName}','{$codeMelli}',{$birthYear},'{$phone}','{$postJob}','{$signature}')";
        $db->Query($sql);
        $id = $db->InsertrdID();
        if(intval($id) > 0){
/*            $log = new Log();
            $log->doLog(1,$_SESSION['userid'],$id);*/
            return true;
        }else{
            return false;
        }
    }

    public function editUser($uid,$fname,$lname,$username,$ps,$ut,$us,$unitID,$fatherName,$codeMelli,$birthYear,$phone,$postJob,$signature){
        $acm = new acm();
        if(!$acm->hasAccess('userManagement')){
            die("access denied");
            exit;
        }
        $utility = new Utility();
        $db = new DBi();
        $pstxt = '';
        if(strlen(trim($ps)) > 0){
            $ps = $utility->MackHash(trim($ps));
            $pstxt = ",`pass`='{$ps}'";
        }
        $sql = "UPDATE `users` SET `fname`='{$fname}',`lname`='{$lname}',`username`='{$username}',`IsEnable`={$us},`gender`={$ut},`unitID`={$unitID},`fatherName`='{$fatherName}',`codeMelli`='{$codeMelli}',`birthYear`={$birthYear},`phone`='{$phone}',`postJob`='{$postJob}',`signature`='{$signature}' ".$pstxt." WHERE `RowID`={$uid} ";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = ((intval($res) == -1 || $res == 0) ? 0 : 1);
        if(intval($res)){
/*            $log = new Log();
            $log->doLog(2,$_SESSION['userid'],$uid);*/
            return true;
        }else{
            return false;
        }
    }

    public function deleteUser($uid){
        $acm = new acm();
        if(!$acm->hasAccess('userManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
/*        $sql = "SELECT `RowID` FROM `message` WHERE `senderid`={$uid} OR `receiverid`={$uid}";
        $mes = $db->ArrayQuery($sql);
        if(count($mes)>0){
            return 0;
        }*/
/*        $sql = "SELECT `RowID` FROM `log` WHERE `userid`={$uid}";
        $lg = $db->ArrayQuery($sql);
        if(count($lg) > 0){
            return 0;
        }*/
        $sql = "DELETE FROM `users` WHERE `RowID`={$uid}";
        $db->Query($sql);
        $ar = $db->AffectedRows();
        $ar = (($ar == -1 || $ar == 0) ? 0 : 1);
        if(intval($ar) > 0){
/*            $log = new Log();
            $log->doLog(3,$_SESSION['userid'],$uid);*/
            return true;
        }else{
            return false;
        }
    }
	
	public function getUsernameIP($userid){
		$db=new DBi();
        $ut=new Utility();
		$sql="select upi.*, u.fname,u.lname from `user_pc_info` as upi LEFT JOIN  `users` as u on upi.`userID`=u.`RowID`  Where  u.RowID={$userid}";
		$res=$db->ArrayQuery($sql);
        $full_access_array = $ut->get_full_access_users(3);
		$htm="";
		if(count($res)>0){
			$htm.='<div style="padding:10px">نام کاربر : '.$res[0]['fname']." ".$res[0]['lname'].'</div><table class="table table-striped table-bordered">
						<thead>
							<tr>
								
								<td> IP اختصاص داده شده به کاربر</td>
								<td> mac address</td>
								<td>حذف</td>
							</tr>
						</thead>
						<tbody>';
			foreach($res as $db=>$value){
				$htm.='<tr>
							
							<td>'.$value['pc_ip'].'</td>
							<td>'.$value['pc_mac'].'</td>
							<td><button onclick="deleteUserIP('.$value['RowID'].')"class="btn btn-danger"><i class="fa fa-trash"></i></button></td>
						</tr>';
			}			
					$htm.='</tbody></table>';
                    return $htm;
		}
        else{
            return 0;
        }
        
	}

    public function setUsernameIP($userid,$userIP){
		$db=new DBi();
		$sql="select * from `user_pc_info`   Where  `userID`={$userid} AND `pc_ip`='{$userIP}'";
        //error_log($sql);
		$res=$db->ArrayQuery($sql);
		if(count($res)>0){
            return 0;
        }
        else{
            $query="INSERT INTO `user_pc_info` (`pc_ip`,`userID`)VALUES('{$userIP}',{$userid})";
            $result=$db->Query($query);
            if($result){
                return $db->InsertrdID();
            }
            else{
                return -1;
            }
        }
	}

    public function deleteUserNameIP($RowID){
        $db=new DBi();
        $sql="DELETE FROM `user_pc_info` WHERE RowID={$RowID}";
        //error_log($sql);
        $res=$db->Query($sql);
        return $res;

    }
    public function getAllAccessHtm($uid){
        $db = new DBi();
        $sql = 'SELECT `IsAdmin` FROM `users` WHERE `RowID`='.$uid;
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0) {
            $userType = $res[0]['IsAdmin'];
            $userType++;
            $sql1 = "SELECT * FROM `accessitem` WHERE `accessType`=".$userType;
            $res1 = $db->ArrayQuery($sql1);
            $Countres1 = count($res1);
            if($Countres1 > 0) {
                $htm = '';
                $htm .= "<table style='width:100%' id='user-access-table'>";
                for ($t = 0; $t < $Countres1; $t += 5) {
                    $b = $t;
                    $htm .= "<tr>";
                    for ($e = 0; $e < 5; $e++) {
                        $htm .= "<td style='width:20%'>";
                        if (intval($res1[$b]['RowID'])) {
                            $htm .= "<div style='float: right;' class='defyek'>";
                            $htm .= "<input type='checkbox' aid='" . $res1[$b]['RowID'] . "' id='AccessCHBX" . $res1[$b]['RowID'] . "' />&nbsp;";
                            $htm .= $res1[$b]['accessNameFa'];
                            $b++;
                            $htm .= "</div>";
                        }
                        $htm .= "</td>";
                    }
                    $htm .= "</tr>";
                }
                $htm .= "</table>";
                return $htm;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function getUserAccess($uid){
        $acm = new acm();
        if(!$acm->hasAccess('changeUsersAccess')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT * FROM `access_table` WHERE `user_id`={$uid}";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            return $res;
        }else{
            return false;
        }
    }

    public function doEditAccessUser($aids,$uid){
        $acm = new acm();
        if (!$acm->hasAccess("changeUsersAccess")) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $aids = trim($aids);
        if($aids != '') {
            $aidArr = explode(",", $aids);
        }else{
            $aidArr = array();
        }
        $sql = "DELETE FROM `access_table` WHERE `user_id` = ".$uid;
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (intval($res) == -1 ? 0 : 1);
        if($res){
            if(count($aidArr) > 0){
                $V = array();
                for ($a = 0; $a < count($aidArr); $a++) {
                    $V[] = " ({$uid},{$aidArr[$a]},1)";
                }
                $insertV = implode(",", $V);
                $insertSql = "INSERT INTO `access_table` (`user_id`,`item_id`,`access_type`) VALUES ".$insertV;
                $db->Query($insertSql);
                $insertedId = $db->InsertrdID();
                if(intval($insertedId) > 0) {
/*                    $log = new Log();
                    $log->doLog(4, $_SESSION['userid'], $uid);*/
                    return true;
                }else{
                    return false;
                }
            }else{
/*                $log = new Log();
                $log->doLog(4, $_SESSION['userid'], $uid);*/
                return true;
            }
        }else{
            return false;
        }
    }

    public function checkPassword($oldPass){
        $db = new DBi();
        $query = "SELECT `pass` FROM `users` WHERE `RowID`={$_SESSION['userid']} AND `IsEnable`=1 ";
        $userInfo = $db->ArrayQuery($query);
        if(count($userInfo) == 1) {
            $hash = $userInfo[0]['pass'];
            if (password_verify($oldPass, $hash)) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function changePassword($newPass){
        $db = new DBi();
        $ut = new Utility();
        $pass = $ut->MackHash(trim($newPass));
        $sql = "UPDATE `users` SET `pass`='{$pass}' WHERE `RowID`={$_SESSION['userid']}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = ((intval($res) == -1 || $res == 0) ? 0 : 1);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public function getUsers(){
        $db = new DBi();
        $sql = "SELECT `RowID`,`fname`,`lname` FROM `users`";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function copy_user_access($from_user,$to_user){
        $ut=new Utility();
        $db=new DBi();
        $get_all_access="SELECT RowID FROM access_table where `user_id`={$to_user}";
        $res_access=$db->ArrayQuery($get_all_access);
        $old_access_array=[];
        foreach($res_access as $key=>$value){
            $old_access_array[]=$value['RowID'];
        }
        $old_access_items=implode(",",$old_access_array);
        $sql="INSERT INTO access_table( `user_id`,`item_id`,`access_type`)
                SELECT {$to_user},`item_id`,`access_type`
                    FROM access_table WHERE user_id = {$from_user}";
        $res=$db->Query($sql);
        if($res){
            $del_sql="DELETE FROM access_table where RowID IN ({$old_access_items})";
            $res_d=$db->Query($del_sql);
            return true;
        }
        return false;
    }

    public function getCompanyManagement(){
        $db = new DBi();
        $sql = "SELECT `user_id` FROM `access_table` WHERE `item_id`=155";
        $res = $db->ArrayQuery($sql);
        return $res[0]['user_id'];
    }

}