<?php


class Days{

    public function __construct(){
        // do nothing
    }

    public function getAvailableDayList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('manageAvailableDay')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $sql = "SELECT `RowID`,`Year`,`AllDaysOfYear`,`OfficialHolidays`,`Vacations`,`AvailableDays` FROM `available_days`";
        $sql .= " LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['Year'] = $res[$y]['Year'];
            $finalRes[$y]['AllDaysOfYear'] = $res[$y]['AllDaysOfYear'].' روز';
            $finalRes[$y]['OfficialHolidays'] = $res[$y]['OfficialHolidays'].' روز';
            $finalRes[$y]['Vacations'] = $res[$y]['Vacations'].' روز';
            $finalRes[$y]['AvailableDays'] = $res[$y]['AvailableDays'].' روز';
        }
        return $finalRes;
    }

    public function getAvailableDayListCountRows(){
        $acm = new acm();
        if(!$acm->hasAccess('manageAvailableDay')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `available_days`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function availableDayInfo($ADid){
        $db = new DBi();
        $sql = "SELECT `Year`,`AllDaysOfYear`,`OfficialHolidays`,`Vacations` FROM `available_days` WHERE `RowID`=".$ADid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("ADid"=>$ADid,"Year"=>$res[0]['Year'],"AllDaysOfYear"=>$res[0]['AllDaysOfYear'],"OfficialHolidays"=>$res[0]['OfficialHolidays'],"Vacations"=>$res[0]['Vacations']);
            return $res;
        }else{
            return false;
        }
    }

    public function createAvailableDay($yy,$totalDay,$OHolidays,$Vacations){
        $acm = new acm();
        if(!$acm->hasAccess('manageAvailableDay')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $AvailableDay = intval($totalDay) - (intval($OHolidays) + intval($Vacations));
        $sql = "INSERT INTO `available_days` (`Year`,`AllDaysOfYear`,`OfficialHolidays`,`Vacations`,`AvailableDays`) VALUES ({$yy},{$totalDay},{$OHolidays},{$Vacations},{$AvailableDay})";
        $res = $db->Query($sql);
        if (intval($res) > 0) {
            return true;
        }else{
            return false;
        }
    }

    public function editAvailableDay($ADid,$yy,$totalDay,$OHolidays,$Vacations){
        $acm = new acm();
        if(!$acm->hasAccess('manageAvailableDay')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $AvailableDay = intval($totalDay) - (intval($OHolidays) + intval($Vacations));
        $sql = "UPDATE `available_days` SET `Year`={$yy},`AllDaysOfYear`={$totalDay},`OfficialHolidays`={$OHolidays},`Vacations`={$Vacations},`AvailableDays`={$AvailableDay} WHERE `RowID`=".$ADid;
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = ((intval($res) == -1 || $res == 0) ? 0 : 1);
        if (intval($res)){
            return true;
        }else{
            return false;
        }
    }
}