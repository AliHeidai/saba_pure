<?php

class acm{

    public function __construct(){

    }

    public function hasAccess($access,$userID=0){
        if(!intval($userID)){
            $userID = $_SESSION['userid'];
        }
        if(!strlen(trim($access))){
            return false;
        }
        $db = new DBi();
        $access = $db->Escape($access);
        $sql = "SELECT `access_type` FROM `access_table` 
                INNER JOIN accessitem ON (`accessitem`.`RowID`=`access_table`.`item_id`)
                WHERE `access_table`.`user_id`={$userID} AND `accessitem`.`accessNameEn` = '{$access}' ";
        $res = $db->ArrayQuery($sql);
        $accessType = $res[0]['access_type'];
        if(intval($accessType) === 1){
            return true;
        }else{
            return false;
        }
    }

    public function hasAccessThisMessage($refId){
        $uid = $_SESSION['userid'];
        if(!intval($uid)){
            return false;
        }
        $db = new DBi();
        $isAdmin = $_SESSION['IsAdmin'];
        /*$w = array();
        $w[] = $uid;
        $w[] = -1;
        if($isAdmin){
            $w[] = -3;
        }else{
            $w[] = -2;
        }
        $w = join(",",$w);*/
        $sql = " SELECT `RowID` FROM `messagerefer` WHERE `messagerefer`.`IsEnable`=1 AND `receiverid`={$uid} AND `RowID` = {$refId} ";
        $res = $db->ArrayQuery($sql);
        if(intval($res[0]['RowID'])>0){
            return true;
        }else{
            return false;
        }
    }
    
    public function get_user_access($userID){
        $db=new DBi();
        $sql="select `ai`.accessNameEn from (select user_id,item_id from access_table where user_id=$userID) as `at` INNER JOIN accessitem  as `ai` on `at`.item_id=`ai`.RowID";
        $rows = $db->Query($sql);
       // $res=$db->ArrayQuery($sql);
       if($rows && mysqli_num_rows($rows) > 0) {
            while($row = mysqli_fetch_assoc($rows)) {
                $result[] = $row['accessNameEn'];
            }
        }
        return $result;
    }
}
