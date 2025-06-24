<?php


class Industry{

    public function __construct(){
        // do nothing
    }

/*    public function saveTiming(){
        $acm = new acm();
        if(!$acm->hasAccess('industrialManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `timing`";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0) {
            $cnt = count($res);
            for ($i=0;$i<$cnt;$i++) {
                $qq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$res[$i]['A']}'";
                $rst = $db->ArrayQuery($qq);

                $res[$i]['C'] = (strlen(trim($res[$i]['C'])) == 0 || floatval($res[$i]['C']) == 0 ? 'NULL' : $res[$i]['C']);
                $res[$i]['D'] = (strlen(trim($res[$i]['D'])) == 0 || floatval($res[$i]['D']) == 0 ? 'NULL' : $res[$i]['D']);
                $res[$i]['E'] = (strlen(trim($res[$i]['E'])) == 0 || floatval($res[$i]['E']) == 0 ? 'NULL' : $res[$i]['E']);
                $res[$i]['F'] = (strlen(trim($res[$i]['F'])) == 0 || floatval($res[$i]['F']) == 0 ? 'NULL' : $res[$i]['F']);
                $res[$i]['G'] = (strlen(trim($res[$i]['G'])) == 0 || floatval($res[$i]['G']) == 0 ? 'NULL' : $res[$i]['G']);
                $res[$i]['H'] = (strlen(trim($res[$i]['H'])) == 0 || floatval($res[$i]['H']) == 0 ? 'NULL' : $res[$i]['H']);
                $res[$i]['I'] = (strlen(trim($res[$i]['I'])) == 0 || floatval($res[$i]['I']) == 0 ? 'NULL' : $res[$i]['I']);
                $res[$i]['J'] = (strlen(trim($res[$i]['J'])) == 0 || floatval($res[$i]['J']) == 0 ? 'NULL' : $res[$i]['J']);
                $res[$i]['K'] = (strlen(trim($res[$i]['K'])) == 0 || floatval($res[$i]['K']) == 0 ? 'NULL' : $res[$i]['K']);
                $query = "INSERT INTO `piece_timing` (`pid`,`Forging_timing`,`Machining_timing`,`Polishing_timing`,
                                                      `Plating_timing`,`Paint_timing`,`PVD_timing`,`Hose_timing`,
                                                      `Pipe_timing`,`PlasticInjection_timing`) 
                                         VALUES ({$rst[0]['RowID']},{$res[$i]['C']},{$res[$i]['D']},{$res[$i]['E']},
                                                 {$res[$i]['F']},{$res[$i]['G']},{$res[$i]['H']},{$res[$i]['I']},
                                                 {$res[$i]['J']},{$res[$i]['K']}
                                                 )";
                ////$//ut->fileRecorder($query);
                $result = $db->Query($query);
                if (intval($result) < 0){
                    //$//ut->fileRecorder($query);
                }
            }
        }
        return 'موفق بود';
    }*/

    public function saveTiming(){
        $db = new DBi();
/*        $sql = "SELECT `RowID`,`ProductCode`,`PieceCode` FROM `interface` ORDER BY `RowID` ASC";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            $qq = "SELECT `Row` FROM `piece` WHERE `pCode`='{$res[$i]['PieceCode']}'";
            $rst = $db->ArrayQuery($qq);

            $qqq = "SELECT `Col` FROM `good` WHERE `gCode`='{$res[$i]['ProductCode']}'";
            $rstt = $db->ArrayQuery($qqq);

            $colrow = $rstt[0]['Col'].','.$rst[0]['Row'];

            $q = "UPDATE `interface` SET `col_row`='{$colrow}' WHERE `RowID`={$res[$i]['RowID']}";
            $db->Query($q);
        }*/
        $sql = "SELECT `RowID` FROM `good` ORDER BY `RowID` ASC";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $x = 23;
        for ($i=0;$i<$cnt;$i++){
            $q = "UPDATE `good` SET `Col`={$x} WHERE `RowID`={$res[$i]['RowID']}";
            $db->Query($q);
            $x++;
        }
        return 'موفق بود';
    }

    public function getIndustryList($pName,$pCode,$supply,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('industrialManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        if(strlen(trim($pCode)) > 0){
            $w[] = '`pCode`= "'.$pCode.'" ';
        }
		if(intval($supply) > 0){
            $w[] = '`ChangingHow_supply`= '.$supply.' ';
        }
        $sql = "SELECT `piece`.`RowID`,`pCode`,`pName`,`ChangingHow_supply` FROM `piece`
                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `piece`.`RowID` LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            switch ($res[$y]['ChangingHow_supply']){
                case 0:
                    $finalRes[$y]['ChangingHow_supply'] = 'راکد';
                    break;
                case 1:
                    $finalRes[$y]['ChangingHow_supply'] = 'وارداتی';
                    break;
                case 2:
                    $finalRes[$y]['ChangingHow_supply'] = 'خرید داخلی';
                    break;
                case 3:
                    $finalRes[$y]['ChangingHow_supply'] = 'خرید قطعه ماشینکاری';
                    break;
                case 4:
                    $finalRes[$y]['ChangingHow_supply'] = 'خرید قطعه ریخته گری';
                    break;
                case 5:
                    $finalRes[$y]['ChangingHow_supply'] = 'تولید ریخته گری';
                    break;
                case 6:
                    $finalRes[$y]['ChangingHow_supply'] = 'تولید ماشینکاری';
                    break;
                case 7:
                    $finalRes[$y]['ChangingHow_supply'] = 'فورج';
                    break;
                case 8:
                    $finalRes[$y]['ChangingHow_supply'] = 'تزریق پلاستیک';
                    break;
                case 9:
                    $finalRes[$y]['ChangingHow_supply'] = 'لوله';
                    break;
                case 10:
                    $finalRes[$y]['ChangingHow_supply'] = 'شیلنگ';
                    break;
                case 11:
                    $finalRes[$y]['ChangingHow_supply'] = 'برش لیزر';
                    break;
                case 12:
                    $finalRes[$y]['ChangingHow_supply'] = 'کلکتور';
                    break;
                case 13:
                    $finalRes[$y]['ChangingHow_supply'] = 'منسوخ';
                    break;
                case 14:
                    $finalRes[$y]['ChangingHow_supply'] = 'قطعه مونتاژی';
                    break;
            }
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
        }
        return $finalRes;
    }

    public function getIndustryListCountRows($pName,$pCode,$supply){
        $acm = new acm();
        if(!$acm->hasAccess('industrialManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $w = array();
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        if(strlen(trim($pCode)) > 0){
            $w[] = '`pCode`= "'.$pCode.'" ';
        }
        if(intval($supply) > 0){
            $w[] = '`ChangingHow_supply`= '.$supply.' ';
        }
        $sql = "SELECT `piece`.`RowID`,`ChangingHow_supply` FROM `piece`
                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function editCreateUnitTimingPiece($pid,$myJsonString){
        $acm = new acm();
        if(!$acm->hasAccess('industrialManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        mysqli_autocommit($db->Getcon(),FALSE);
        $Casting = (strlen(trim($myJsonString[0][0])) == 0 ? 'NULL' : $myJsonString[0][0]);
        $Forging = (strlen(trim($myJsonString[1][0])) == 0 ? 'NULL' : $myJsonString[1][0]);
        $Machining = (strlen(trim($myJsonString[2][0])) == 0 ? 'NULL' : $myJsonString[2][0]);
        $Polishing = (strlen(trim($myJsonString[3][0])) == 0 ? 'NULL' : $myJsonString[3][0]);
        $Plating = (strlen(trim($myJsonString[4][0])) == 0 ? 'NULL' : $myJsonString[4][0]);
        $Scotching = (strlen(trim($myJsonString[5][0])) == 0 ? 'NULL' : $myJsonString[5][0]);
        $Paint = (strlen(trim($myJsonString[6][0])) == 0 ? 'NULL' : $myJsonString[6][0]);
        $PVD = (strlen(trim($myJsonString[7][0])) == 0 ? 'NULL' : $myJsonString[7][0]);
        $Hose = (strlen(trim($myJsonString[8][0])) == 0 ? 'NULL' : $myJsonString[8][0]);
        $Pipe = (strlen(trim($myJsonString[9][0])) == 0 ? 'NULL' : $myJsonString[9][0]);
        $PlasticInjection = (strlen(trim($myJsonString[10][0])) == 0 ? 'NULL' : $myJsonString[10][0]);
        $Assembly = (strlen(trim($myJsonString[11][0])) == 0 ? 'NULL' : $myJsonString[11][0]);

        $sql = "SELECT `Casting_timing`,`Forging_timing`,`Machining_timing`,`Polishing_timing`,`Plating_timing`,`Scotching_timing`,`Paint_timing`,
        `PVD_timing`,`Hose_timing`,`Pipe_timing`,`PlasticInjection_timing`,`Assembly_timing` FROM `piece_timing` WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);

        if (count($res) > 0) {  // قبلا برای این قطعه زمان سنجی ثبت شده است
            $keys = array_keys($res[0]);
            $fields = array($Forging,$Machining,$Polishing,$Plating,$Paint,$PVD,$Hose,$Pipe,$PlasticInjection);
            $cnt = count($fields);
            for ($i=0;$i<$cnt;$i++) {
                if (floatval($res[0]["$keys[$i]"]) != floatval($fields[$i])) {
                    $backupKeys[] = $keys[$i];
                    $PreviousValue[] = $res[0]["$keys[$i]"];
                    $currentValue[] = (floatval($fields[$i]) > 0 ? $fields[$i] : '');
                }
            }
            $sqlPCU = "UPDATE `piece_timing` SET `Casting_timing`={$Casting},`Forging_timing`={$Forging},`Machining_timing`={$Machining},
                                                 `Polishing_timing`={$Polishing},`Plating_timing`={$Plating},`Scotching_timing`={$Scotching},
                                                 `Paint_timing`={$Paint},`PVD_timing`={$PVD},`Hose_timing`={$Hose},`Pipe_timing`={$Pipe},
                                                 `PlasticInjection_timing`={$PlasticInjection},`Assembly_timing`={$Assembly} WHERE `pid`={$pid}";
            $db->Query($sqlPCU);
            $resPCU = $db->AffectedRows();
            $resPCU = ((intval($resPCU) == -1 || $resPCU == 0) ? 0 : 1);
            if (intval($resPCU)){
                $countBackupKeys = count($backupKeys);
                for ($i=0;$i<$countBackupKeys;$i++){
                    $sqlSFA = "SELECT `FaName` FROM `en_to_fa` WHERE `EnName`='{$backupKeys[$i]}'";
                    $rst = $db->ArrayQuery($sqlSFA);
                    $insertValue[] = '('.$pid.',"'.$backupKeys[$i].'","'.$rst[0]['FaName'].'","'.$currentValue[$i].'","'.$PreviousValue[$i].'","'.date('Y/m/d').'",'.$_SESSION['userid'].')';
                }
                $insertValue = implode(',',$insertValue);
                $sqlBP = "INSERT INTO `backup_piece` (`pid`,`fieldName`,`fieldName_Fa`,`currentValue`,`previousValue`,`changeDate`,`uid`) VALUES ".$insertValue." ";
                $resBP = $db->Query($sqlBP);
                if (intval($resBP) > 0){
                    mysqli_commit($db->Getcon());
                    return true;
                }else{
                    mysqli_rollback($db->Getcon());
                    return false;
                }
            }else{
                mysqli_rollback($db->Getcon());
                return false;
            }
        }else{  // قبلا برای این قطعه زمان سنجی ثبت نشده است
            $sqlPCI = "INSERT INTO `piece_timing` (`pid`,`Casting_timing`,`Forging_timing`,`Machining_timing`,`Polishing_timing`,`Plating_timing`,`Scotching_timing`,`Paint_timing`,`PVD_timing`,`Hose_timing`,`Pipe_timing`,`PlasticInjection_timing`,`Assembly_timing`) 
                                              VALUES ({$pid},{$Casting},{$Forging},{$Machining},{$Polishing},{$Plating},{$Scotching},{$Paint},{$PVD},{$Hose},{$Pipe},{$PlasticInjection},{$Assembly}) ";
            $resPCI = $db->Query($sqlPCI);
            if (intval($resPCI) > 0){
                mysqli_commit($db->Getcon());
                return true;
            }else{
                mysqli_rollback($db->Getcon());
                return false;
            }
        }
    }

    public function editCreateOtherPieceCode($pid,$myJsonString){
        $acm = new acm();
        if(!$acm->hasAccess('industrialManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `piece_masterlist` 
                SET `rawCode`='{$myJsonString[0][0]}',`forgingCode`='{$myJsonString[1][0]}',`machiningCode`='{$myJsonString[2][0]}',
                    `polishingCode`='{$myJsonString[3][0]}',`nickelCode`='{$myJsonString[4][0]}',`platingCode`='{$myJsonString[5][0]}',`scotchingCode`='{$myJsonString[6][0]}',
                    `pushplatingCode`='{$myJsonString[7][0]}',`goldenCode`='{$myJsonString[8][0]}',`mattgoldenCode`='{$myJsonString[9][0]}',
                    `lightgoldenCode`='{$myJsonString[10][0]}',`darkgoldenCode`='{$myJsonString[11][0]}',`paintCode`='{$myJsonString[12][0]}',
                    `decoralCode`='{$myJsonString[13][0]}',`steelCode`='{$myJsonString[14][0]}',`rawppCode`='{$myJsonString[15][0]}',
                    `finalppCode`='{$myJsonString[16][0]}',`finalCode`='{$myJsonString[17][0]}' WHERE `pid`={$pid}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = ((intval($res) == -1 || $res == 0) ? 0 : 1);
        if (intval($res)){
            return true;
        }else{
            return false;
        }
    }

    public function PieceTimingInfoHTM($pid){
        $acm = new acm();
        if(!$acm->hasAccess('industrialManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `Casting_timing`,`Forging_timing`,`Machining_timing`,`Polishing_timing`,`Plating_timing`,`Scotching_timing`,`Paint_timing`,`PVD_timing`,`Hose_timing`,`Pipe_timing`,`PlasticInjection_timing`,`Assembly_timing` FROM `piece_timing` WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);

        $infoNames = array('زمان سنجی ریخته گری','زمان سنجی فورج','زمان سنجی ماشین کاری','زمان سنجی پرداخت',
                            'زمان سنجی آب کاری', 'زمان سنجی اسکاچ ','زمان سنجی رنگ','زمان سنجی PVD','زمان سنجی خط شیلنگ',
                            'زمان سنجی خط لوله','زمان سنجی تزریق پلاستیک','زمان سنجی مونتاژ');
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoIndustry-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 60%;">نام واحد</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 30%;">زمان سنجی (ثانیه)</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<count($infoNames);$i++){
            $iterator++;
            $keyName = key($res[0]);
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><input type="text" class="form-control" style="text-align: center;" id="pieceTiming-'.$iterator.'" value="'.$res[0]["$keyName"].'" /></td>';
            $htm .= '</tr>';
            next($res[0]);
        }
        $htm .= '<input type="hidden" id="manageIndustryHiddenECTimingPid" value="'.$pid.'" />';
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function PieceOtherCodesInfoHTM($pid){
        $acm = new acm();
        if(!$acm->hasAccess('industrialManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `rawCode`,`forgingCode`,`machiningCode`,`polishingCode`,`nickelCode`,
                       `platingCode`,`pushplatingCode`,`goldenCode`,`mattgoldenCode`,`lightgoldenCode`,
                       `darkgoldenCode`,`paintCode`,`decoralCode`,`steelCode`,`rawppCode`,`finalppCode`,`finalCode`
                        FROM `piece_masterlist` WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);

        $infoNames = array('کد سیاهتاب','کد فورج','کد ماشین کاری','کد پرداخت','کد نیکل خورده',
                           'کد آب کاری شده','کد اسکاچ','کد آب برداری شده','کد طلایی','کد طلایی مات','کد طلایی روشن',
                           'کد طلایی تیره','کد رنگی','کد دکورال','کد طرح استیل','کد قطعه خام تزریق',
                           'کد قطعه نهایی تزریق','کد نهایی');
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoIndustry-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">نام کد</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مقدار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<17;$i++){
            $iterator++;
            $keyName = key($res[0]);
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><input type="text" class="form-control" style="text-align: center;" id="pieceOtherCodes-'.$iterator.'" value="'.$res[0]["$keyName"].'" /></td>';
            $htm .= '</tr>';
            next($res[0]);
        }
        $htm .= '<input type="hidden" id="manageIndustryHiddenRecordOPCPid" value="'.$pid.'" />';
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function unitEfficiency($UEid){
        $db = new DBi();
        $sql = "SELECT `efficiency` FROM `official_productive_units` WHERE `RowID`=".$UEid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $result = array("efficiency"=>$res[0]['efficiency']);
            return $result;
        }else{
            return false;
        }
    }

    public function editUnitEfficiency($UEid,$efficiency){
        $acm = new acm();
        if(!$acm->hasAccess('industrialManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        mysqli_autocommit($db->Getcon(),FALSE);

        $uDate = $ut->greg_to_jal(date('Y-m-d'));
        $register = explode('/',$uDate);
        switch ($register[1]){
            case 1:
                $month = 'فروردین';
                break;
            case 2:
                $month = 'اردیبهشت';
                break;
            case 3:
                $month = 'خرداد';
                break;
            case 4:
                $month = 'تیر';
                break;
            case 5:
                $month = 'مرداد';
                break;
            case 6:
                $month = 'شهریور';
                break;
            case 7:
                $month = 'مهر';
                break;
            case 8:
                $month = 'آبان';
                break;
            case 9:
                $month = 'آذر';
                break;
            case 10:
                $month = 'دی';
                break;
            case 11:
                $month = 'بهمن';
                break;
            case 12:
                $month = 'اسفند';
                break;
        }
        $uDate = $ut->jal_to_greg($uDate);

        $sqlPCU = "UPDATE `official_productive_units` SET `efficiency`={$efficiency},`cDate`='{$uDate}' WHERE `RowID`={$UEid}";
        $db->Query($sqlPCU);

        $res = $db->AffectedRows();
        $res = ((intval($res) == -1 || $res == 0) ? 0 : 1);
        if (intval($res)){
            $sql = "INSERT INTO `unit_efficiency` (`unit_ID`,`efficiency`,`createDate`,`registrationYear`,`registrationMonth`) VALUES ({$UEid},{$efficiency},'{$uDate}','{$register[0]}','{$month}')";
            $rst = $db->Query($sql);
            if (intval($rst) > 0){
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

    //++++++++++++++++ زمان سنجی محصولات ++++++++++++++++

    public function getGIndustryList($gName,$gCode,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('industrialManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`gCode`= "'.$gCode.'" ';
        }
        $sql = "SELECT `RowID`,`gName`,`gCode`,`Montage_timing` FROM `good` ";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['Montage_timing'] = (floatval($res[$y]['Montage_timing']) > 0 ? $res[$y]['Montage_timing'].' ثانیه' : '');
        }
        return $finalRes;
    }

    public function getGIndustryListCountRows($gName,$gCode){
        $acm = new acm();
        if(!$acm->hasAccess('industrialManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $w = array();
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`gCode`= "'.$gCode.'" ';
        }
        $sql = "SELECT `RowID` FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function goodTimingInfoHTM($gid){
        $acm = new acm();
        if(!$acm->hasAccess('industrialManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `Montage_timing` FROM `good` WHERE `RowID`={$gid}";
        $res = $db->ArrayQuery($sql);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoIndustry-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 60%;">نام واحد</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 30%;">زمان سنجی (ثانیه)</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $htm .= '<tr class="table-secondary">';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">1</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">زمان سنجی مونتاژ</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><input type="text" class="form-control" style="text-align: center;" id="goodMontageTiming" value="'.$res[0]['Montage_timing'].'" /></td>';
        $htm .= '</tr>';

        $htm .= '<input type="hidden" id="manageIndustryHiddenTimingGid" value="'.$gid.'" />';
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function editCreateUnitTimingGood($gid,$gTiming){
        $acm = new acm();
        if(!$acm->hasAccess('industrialManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $gTiming = (strlen(trim($gTiming)) == 0 ? 'NULL' : $gTiming);

        $sqlPCU = "UPDATE `good` SET `Montage_timing`={$gTiming} WHERE `RowID`={$gid}";
        $db->Query($sqlPCU);
        $resPCU = $db->AffectedRows();
        $resPCU = ((intval($resPCU) == -1 || $resPCU == 0) ? 0 : 1);
        if (intval($resPCU)){
            return true;
        }else{
            return false;
        }
    }

	    //+++++++++++++++++ خروجی اکسل BOM ++++++++++++++++++++

    public function getGoodsForGeneralExcelBOM(){
        $acm = new acm();
        if(!$acm->hasAccess('excelexportBOM')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `gCode`,`HCode`,`gName`,`Col`,`isEnable` FROM `good` ORDER BY `RowID` ASC";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            return $res;
        }else{
            return false;
        }
    }

    public function getPiecesForGeneralExcelBOM(){
        $acm = new acm();
        if(!$acm->hasAccess('excelexportBOM')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `pCode`,`pName`,`pUnit`,`rawCode`,`forgingCode`,`machiningCode`,`polishingCode`,`nickelCode`,
                       `platingCode`,`pushplatingCode`,`goldenCode`,`mattgoldenCode`,`lightgoldenCode`,`darkgoldenCode`,
                       `HPCode`,`paintCode`,`decoralCode`,`steelCode`,`rawppCode`,`finalppCode`,`finalCode`,`Row`,
                       `Weight_materials`,`Custom_dimensions`,`Forging_timing`,`Machining_timing`,`Polishing_timing`,
                       `Plating_timing`,`Paint_timing`,`PVD_timing`,`Hose_timing`,`Pipe_timing`,`PlasticInjection_timing`
                        FROM `piece` 
                        INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                        LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`) 
                        ORDER BY `piece`.`RowID` ASC";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            return $res;
        }else{
            return false;
        }
    }

    public function getColRowForGeneralExcelBOM(){
        $acm = new acm();
        if(!$acm->hasAccess('excelexportBOM')){
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
	
	public function getExcelZamanSanji($pName,$pCode,$supply){
        $acm = new acm();
        if(!$acm->hasAccess('industrialManagement') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $w = array();
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        if(strlen(trim($pCode)) > 0){
            $w[] = '`pCode`= "'.$pCode.'" ';
        }
        if(intval($supply) > 0){
            $w[] = '`ChangingHow_supply`='.$supply.' ';
        }
        $sql = "SELECT `pName`,`pCode`,`Casting_timing`,`Forging_timing`,`Machining_timing`,`Polishing_timing`,`Plating_timing`,`Scotching_timing`,`Paint_timing`,
                       `PVD_timing`,`Hose_timing`,`Pipe_timing`,`PlasticInjection_timing`,`Assembly_timing`,`rawCode`,`forgingCode`,`machiningCode`,
                       `polishingCode`,`nickelCode`,`platingCode`,`pushplatingCode`,`goldenCode`,`mattgoldenCode`,`lightgoldenCode`,
                       `darkgoldenCode`,`paintCode`,`decoralCode`,`steelCode`,`rawppCode`,`finalppCode`,`finalCode`
                FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)
                       ";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            return $res;
        }else{
            $res = "امکان گرفتن خروجی وجود ندارد";
            $out = "false";
            response($res,$out);
            exit;
        }
    }

}