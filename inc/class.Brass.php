<?php
/**
 * Created by PhpStorm.
 * User: Majid
 * Date: 11/19/2019
 * Time: 13:35 AM
 */

class Brass{

    public function __construct(){
        // do nothing
    }

    public function createBrassWeight($BSPrice,$U14,$Un14,$BPriceC,$CPrice,$pfw,$psp){
        $acm = new acm();
        if(!$acm->hasAccess('manageBrassWeight')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "INSERT INTO `brass_weight` (`brassSwarfPrice`,`BullionPriceUp14`,`BullionPriceUnder14`,`BullionPriceColector`,
                                            `CastingPrice`,`PercentFuelWeight`,`PolishingSoilPrice`,`uid`)
                                    VALUES ({$BSPrice},{$U14},{$Un14},{$BPriceC},{$CPrice},{$pfw},{$psp},{$_SESSION['userid']})";
        $res = $db->Query($sql);
        if(intval($res) > 0) {
            $sql1 = "INSERT INTO `backup_brass_weight` (`brassSwarfPrice`,`BullionPriceUp14`,`BullionPriceUnder14`,`BullionPriceColector`,
                                                        `CastingPrice`,`PercentFuelWeight`,`PolishingSoilPrice`,`uid`,`changeDate`)
                                                VALUES ({$BSPrice},{$U14},{$Un14},{$BPriceC},{$CPrice},{$pfw},{$psp},{$_SESSION['userid']},NOW())";
            $result = $db->Query($sql1);
            if (intval($result) > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function editBrassWeight($BWid,$BSPrice,$U14,$Un14,$BPriceC,$CPrice,$pfw,$psp){
        $acm = new acm();
        if(!$acm->hasAccess('manageBrassWeight')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sql = "UPDATE `brass_weight` SET `brassSwarfPrice`={$BSPrice},`BullionPriceUp14`={$U14},`BullionPriceUnder14`={$Un14},
                `BullionPriceColector`={$BPriceC},`CastingPrice`={$CPrice},`PercentFuelWeight`={$pfw},`PolishingSoilPrice`={$psp}
                WHERE `RowID`={$BWid}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = ((intval($res) == -1 || $res == 0) ? 0 : 1);
        if (intval($res) > 0) {
            $sql1 = "INSERT INTO `backup_brass_weight` (`brassSwarfPrice`,`BullionPriceUp14`,`BullionPriceUnder14`,`BullionPriceColector`,
                                                        `CastingPrice`,`PercentFuelWeight`,`PolishingSoilPrice`,`uid`,`changeDate`)
                                                VALUES ({$BSPrice},{$U14},{$Un14},{$BPriceC},{$CPrice},{$pfw},{$psp},{$_SESSION['userid']},NOW())";
            $result = $db->Query($sql1);
            if (intval($result) > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function getBrassWeightList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('manageBrassWeight')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $sql = "SELECT * FROM `brass_weight`";
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['brassSwarfPrice'] = (intval($res[$y]['brassSwarfPrice']) == 0 ? '--------' : number_format($res[$y]['brassSwarfPrice']).' ریال');
            $finalRes[$y]['BullionPriceUp14'] = (intval($res[$y]['BullionPriceUp14']) == 0 ? '--------' : number_format($res[$y]['BullionPriceUp14']).' ریال');
            $finalRes[$y]['BullionPriceUnder14'] = (intval($res[$y]['BullionPriceUnder14']) == 0 ? '--------' : number_format($res[$y]['BullionPriceUnder14']).' ریال');
            $finalRes[$y]['BullionPriceColector'] = (intval($res[$y]['BullionPriceColector']) == 0 ? '--------' : number_format($res[$y]['BullionPriceColector']).' ریال');
            $finalRes[$y]['CastingPrice'] = (intval($res[$y]['CastingPrice']) == 0 ? '--------' : number_format($res[$y]['CastingPrice']).' ریال');
            $finalRes[$y]['PolishingSoilPrice'] = (intval($res[$y]['PolishingSoilPrice']) == 0 ? '--------' : number_format($res[$y]['PolishingSoilPrice']).' ریال');
            $finalRes[$y]['PercentFuelWeight'] = (floatval($res[$y]['PercentFuelWeight']) == 0 ? '--------' : $res[$y]['PercentFuelWeight'].' درصد');
        }
        return $finalRes;
    }

    public function getBrassWeightListCountRows(){
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `brass_weight`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function brassWeight(){
        $db = new DBi();
        $sql = "SELECT `RowID`,`brassSwarfPrice`,`BullionPriceUp14`,`BullionPriceUnder14`,`BullionPriceColector`,`CastingPrice`,`PercentFuelWeight`,`PolishingSoilPrice` FROM `brass_weight`";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            $percentage = ($res[0]['PolishingSoilPrice']/$res[0]['brassSwarfPrice'])*100;
            $result = array("BWid"=>$res[0]['RowID'],
                            "brassSwarfPrice"=>number_format($res[0]['brassSwarfPrice']),
                            "BullionPriceUp14"=>number_format($res[0]['BullionPriceUp14']),
                            "BullionPriceUnder14"=>number_format($res[0]['BullionPriceUnder14']),
                            "BullionPriceColector"=>number_format($res[0]['BullionPriceColector']),
                            "CastingPrice"=>number_format($res[0]['CastingPrice']),
                            "percentage"=>intval($percentage),
                            "PolishingSoilPrice"=>number_format($res[0]['PolishingSoilPrice']),
                            "PercentFuelWeight"=>$res[0]['PercentFuelWeight']
            );
            return $result;
        }else{
            $result = array("BWid"=>'',"brassSwarfPrice"=>'',"BullionPriceUp14"=>'',
                            "BullionPriceUnder14"=>'',"BullionPriceColector"=>'',"CastingPrice"=>'',
                            "percentage"=>'',"PolishingSoilPrice"=>'',"PercentFuelWeight"=>''
            );
            return $result;
        }
    }

    //++++++++++++++++++++++++++ درصد ضایعات و بهره ++++++++++++++++++++++++++

    public function getPercentagesList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('percentagesAccess')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $sql = "SELECT * FROM `wastage`";
        $sql .= " LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['wCasting'] = ($res[$y]['wCasting']).' درصد';
            $finalRes[$y]['wMachining'] = ($res[$y]['wMachining']).' درصد';
            $finalRes[$y]['wPolishing'] = ($res[$y]['wPolishing']).' درصد';
            $finalRes[$y]['wMachiningChips'] = ($res[$y]['wMachiningChips']).' درصد';
            $finalRes[$y]['wPolishingSoil'] = ($res[$y]['wPolishingSoil']).' درصد';
            $finalRes[$y]['Scount'] = ($res[$y]['Scount']).' درصد';
            $finalRes[$y]['tax'] = ($res[$y]['tax']).' درصد';
        }
        return $finalRes;
    }

    public function getPercentagesListCountRows(){
        $acm = new acm();
        if(!$acm->hasAccess('percentagesAccess')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `wastage`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function percentagesInfo(){
        $db = new DBi();
        $sql = "SELECT * FROM `wastage`";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            $res = array("Wid"=>($res[0]['RowID']),"wCasting"=>($res[0]['wCasting']),"wMachining"=>($res[0]['wMachining']),
                         "wPolishing"=>($res[0]['wPolishing']),"wMachiningChips"=>($res[0]['wMachiningChips']),
                         "wPolishingSoil"=>($res[0]['wPolishingSoil']),"Scount"=>($res[0]['Scount']),"tax"=>($res[0]['tax'])
            );
            return $res;
        }else{
            $res = array("Wid"=>'',"wCasting"=>'',"wMachining"=>'',"wPolishing"=>'',"wMachiningChips"=>'',"wPolishingSoil"=>'',"Scount"=>'',"tax"=>'');
            return $res;
        }
    }

    public function editCreatePercentages($Wid,$WCasting,$WMachining,$WPolishing,$WMachiningChips,$WPolishingSoil,$Scount,$tax){
        $acm = new acm();
        if(!$acm->hasAccess('percentagesAccess')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        if (intval($Wid) > 0) {
            $sql = "UPDATE `wastage` SET `wCasting`={$WCasting},`wMachining`={$WMachining},`wPolishing`={$WPolishing},`wMachiningChips`={$WMachiningChips},`wPolishingSoil`={$WPolishingSoil},`Scount`={$Scount},`tax`={$tax} WHERE `RowID`=" . $Wid;
            $db->Query($sql);
            $res = $db->AffectedRows();
            $res = ((intval($res) == -1 || $res == 0) ? 0 : 1);
            if (intval($res)) {
                return true;
            } else {
                return false;
            }
        }else{
            $sql = "INSERT INTO `wastage` (`wCasting`,`wMachining`,`wPolishing`,`wMachiningChips`,`wPolishingSoil`,`Scount`,`tax`) 
                    VALUES ({$WCasting},{$WMachining},{$WPolishing},{$WMachiningChips},{$WPolishingSoil},{$Scount},{$tax})";
            $res = $db->Query($sql);
            if (intval($res) > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

}