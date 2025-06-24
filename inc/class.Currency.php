<?php
/**
 * Created by PhpStorm.
 * User: Majid
 * Date: 5/11/2019
 * Time: 8:14 AM
 */

class Currency{

    public function __construct(){
        // do nothing
    }

    public function getCurrencyList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('currencyManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;

        $sql = "SELECT `currency`.*,`fname`,`lname` FROM `currency`
                INNER JOIN `users` ON (`currency`.`uid`=`users`.`RowID`)";
        $sql .= " LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['currencyName'] = $res[$y]['currencyName'];
            $finalRes[$y]['createDate'] = $ut->greg_to_jal($res[$y]['createDate']);
            $finalRes[$y]['dayRate'] = number_format($res[$y]['dayRate']).' ریال';
            $finalRes[$y]['previousDate'] = (strlen($res[$y]['previousDate']) == 0 ? '------' : $ut->greg_to_jal($res[$y]['previousDate']));
            $finalRes[$y]['previousRate'] = (intval($res[$y]['previousRate']) == 0 ? '------' : number_format($res[$y]['previousRate']).' ریال');
            $finalRes[$y]['ExchangeRate'] = (floatval($res[$y]['ExchangeRate']) == 0 ? '------' : $res[$y]['ExchangeRate'].' دلار');
            $finalRes[$y]['previousExchangeRate'] = (floatval($res[$y]['previousExchangeRate']) == 0 ? '------' : $res[$y]['previousExchangeRate'].' دلار');
            $finalRes[$y]['name'] = $res[$y]['fname'].' '.$res[$y]['lname'];
        }
        return $finalRes;
    }

    public function getCurrencyListCountRows(){
        $db = new DBi();
        $sql = "SELECT `currency`.`RowID`,`fname` FROM `currency`
                INNER JOIN `users` ON (`currency`.`uid`=`users`.`RowID`)";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function createCurrency($Cuname,$CRate,$ERate){
        $acm = new acm();
        if(!$acm->hasAccess('currencyManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        mysqli_autocommit($db->Getcon(),FALSE);
        $ERate = (strlen(trim($ERate)) == 0 ? 'NULL' : $ERate);
        $sql = "INSERT INTO `currency` (`currencyName`,`createDate`,`dayRate`,`previousDate`,`previousRate`,`ExchangeRate`,`previousExchangeRate`,`uid`)
                                VALUES ('{$Cuname}',NOW(),{$CRate},NULL,NULL,{$ERate},NULL,{$_SESSION['userid']})";
        $res = $db->Query($sql);
        if(intval($res) > 0){
            $id = intval($db->InsertrdID());
            $sql1 = "INSERT INTO `backup_currency` (`currency_id`,`uid`,`changeDate`,`currency_Rate`,`exchange_Rate`)
                                VALUES ({$id},{$_SESSION['userid']},NOW(),{$CRate},{$ERate})";
            $res1 = $db->Query($sql1);
            if(intval($res1) > 0){
                mysqli_commit($db->Getcon());
                return true;
            }else{
                mysqli_rollback($db->Getcon());
                return false;
            }
        }else{
            return false;
        }
    }

    public function editCurrency($cid,$CRate,$ERate){
        $acm = new acm();
        if(!$acm->hasAccess('currencyManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        mysqli_autocommit($db->Getcon(),FALSE);
        $flag = true;

        if ($cid == 1){  // یعنی دلار انتخاب شده است
            $sql = "SELECT `RowID`,`createDate`,`dayRate`,`previousDate`,`previousRate`,`ExchangeRate` FROM `currency`";
            $res = $db->ArrayQuery($sql);
            if (count($res) > 0){
                $cnt = count($res);
                for ($i=0;$i<$cnt;$i++){
                    $previousDate = $res[$i]['createDate'];
                    $previousRate = $res[$i]['dayRate'];
                    if ($i == 0){
                        $dayRate = $CRate;
                        $sql1 = "UPDATE `currency` SET `createDate`=NOW(),
                                                   `dayRate`={$CRate},
                                                   `previousDate`='{$previousDate}',
                                                   `previousRate`={$previousRate}
                                                   WHERE `RowID`={$cid}";
                        $db->Query($sql1);
                        $res1 = $db->AffectedRows();
                        $res1 = ((intval($res1) == -1 || $res1 == 0) ? 0 : 1);
                        if ($res1 == 0){
                            $flag = false;
                        }
                    }else{
                        $dayRate = $CRate * $res[$i]['ExchangeRate'];
                        $cid = $res[$i]['RowID'];
                        $sql2 = "UPDATE `currency` SET `createDate`=NOW(),
                                                   `dayRate`={$dayRate},
                                                   `previousDate`='{$previousDate}',
                                                   `previousRate`={$previousRate}
                                                   WHERE `RowID`={$res[$i]['RowID']}";
                        $db->Query($sql2);
                        $res2 = $db->AffectedRows();
                        $res2 = ((intval($res2) == -1 || $res2 == 0) ? 0 : 1);
                        if ($res2 == 0){
                            $flag = false;
                        }
                    }
                    $res[$i]['ExchangeRate'] = (floatval($res[$i]['ExchangeRate']) == 0 ? 'NULL' : $res[$i]['ExchangeRate']);
                    $sql3 = "INSERT INTO `backup_currency` (`currency_id`,`uid`,`changeDate`,`currency_Rate`,`exchange_Rate`) VALUES ({$cid},{$_SESSION['userid']},NOW(),{$dayRate},{$res[$i]['ExchangeRate']})";
                    $res3 = $db->Query($sql3);
                    if (intval($res3) <= 0){
                        $flag = false;
                    }
                }  // End For
            }else{
                return false;
            }
        }else{  // هر ارزی غیر از دلار
            $sql = "SELECT `createDate`,`dayRate`,`previousDate`,`previousRate`,`ExchangeRate`,`previousExchangeRate` FROM `currency` WHERE `RowID`={$cid}";
            $res = $db->ArrayQuery($sql);
            if (count($res) > 0) {
                $previousDate = $res[0]['createDate'];
                $previousRate = $res[0]['dayRate'];
                $previousExchangeRate = (floatval($res[0]['ExchangeRate']) == 0 ? 'NULL' : $res[0]['ExchangeRate']);
                $sql1 = "UPDATE `currency` SET `createDate`=NOW(),
                                               `dayRate`={$CRate},
                                               `previousDate`='{$previousDate}',
                                               `previousRate`={$previousRate},
                                               `ExchangeRate`={$ERate},
                                               `previousExchangeRate`={$previousExchangeRate}
                                               WHERE `RowID`={$cid}";
                $db->Query($sql1);
                $res1 = $db->AffectedRows();
                $res1 = ((intval($res1) == -1 || $res1 == 0) ? 0 : 1);
                if (intval($res1) > 0) {
                    $sql2 = "INSERT INTO `backup_currency` (`currency_id`,`uid`,`changeDate`,`currency_Rate`,`exchange_Rate`) VALUES ({$cid},{$_SESSION['userid']},NOW(),{$CRate},{$ERate})";
                    $res2 = $db->Query($sql2);
                    if (intval($res2) <= 0){
                        $flag = false;
                    }
                }else{
                    $flag = false;
                }
            }else{
                return false;
            }
        }
        if ($flag){
            mysqli_commit($db->Getcon());
            return true;
        }else{
            mysqli_rollback($db->Getcon());
            return false;
        }
    }

    public function currencyInfo($cid){
        $db = new DBi();
        $sql = "SELECT `currencyName`,`dayRate`,`ExchangeRate` FROM `currency` WHERE `RowID`=".$cid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("cid"=>$cid,
                         "currencyName"=>$res[0]['currencyName'],
                         "dayRate"=>number_format($res[0]['dayRate']),
                         "ExchangeRate"=>$res[0]['ExchangeRate']
                        );
            return $res;
        }else{
            return false;
        }
    }

    public function checkExistDollar(){
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `currency` WHERE `currencyName`='دلار'";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getDollarPrice(){
        $db = new DBi();
        $sql = "SELECT `dayRate` FROM `currency` WHERE `currencyName`='دلار'";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            return $res;
        }else{
            return false;
        }
    }

    public function getAllCurrency(){
        $db = new DBi();
        $sql = "SELECT `RowID`,`currencyName` FROM `currency`";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            return $res;
        }else{
            return array();
        }
    }
}