<?php
/**
 * Created by PhpStorm.
 * User: Majid
 * Date: 10/12/2019
 * Time: 7:54 AM
 */

class Salary{

    public function __construct(){
        //do nothing
    }

    public function addddPersonnel(){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `bbbbbb`";
        $res = $db->ArrayQuery($sql);

        $sql1 = "SELECT * FROM `personnel`";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);

        $cnt = count($res);
        for ($i = 0; $i < $cnt; $i++) {
            $status = 0;
            for ($j = 0; $j < $cnt1; $j++) {
                if (intval($res[$i]['PersonnelCode']) == intval($res1[$j]['PersonnelCode'])) {
                    $status = 1;
                }
            }
            if ($status == 1) {
                $res[$i]['EvaluationScore'] = intval($res[$i]['EvaluationScore']);
                $sqlup = "UPDATE `personnel` SET `father_Name`='{$res[$i]['father_Name']}',`Birth_Certificate_Num`='{$res[$i]['Birth_Certificate_Num']}',`Issued`='{$res[$i]['Issued']}',`National_Code`='{$res[$i]['National_Code']}',`Marital_Status`={$res[$i]['Marital_Status']},`numberChildren`={$res[$i]['Number_Children']},`Address`='{$res[$i]['Address']}',`degree_Education`='{$res[$i]['degree_Education']}',`Grocery`={$res[$i]['Grocery']},`RightHousing`={$res[$i]['Right_Housing']},`insurance_Number`='{$res[$i]['insurance_Number']}',`account_Number`='{$res[$i]['account_Number']}',`BeginDateContract`='{$res[$i]['BeginDateContract']}',`EndDateContract`='{$res[$i]['EndDateContract']}',`Term_contract`={$res[$i]['Term_contract']},`month_Trial`={$res[$i]['month_Trial']},`phone`='{$res[$i]['phone']}',`mobile`='{$res[$i]['mobile']}',`description`='{$res[$i]['description']}',`RecruitmentDate`='{$res[$i]['RecruitmentDate']}',`EvaluationScore`={$res[$i]['EvaluationScore']} WHERE `PersonnelCode`='{$res[$i]['PersonnelCode']}'";
                $db->Query($sqlup);
            } else {
                $res[$i]['EvaluationScore'] = intval($res[$i]['EvaluationScore']);
                $sqlin = "INSERT INTO `personnel` (`Fname`,`PersonnelCode`,`RightHousing`,`numberChildren`,`Grocery`,`RecruitmentDate`,`BeginDateContract`,`EndDateContract`,`phone`,`mobile`,`Term_contract`,`month_Trial`,`Birth_Certificate_Num`,`Issued`,`National_Code`,`Marital_Status`,`Address`,`degree_Education`,`insurance_Number`,`account_Number`,`EvaluationScore`,`description`,`father_Name`)
                              VALUES ('{$res[$i]['Name']}','{$res[$i]['PersonnelCode']}',{$res[$i]['Right_Housing']},{$res[$i]['Number_Children']},{$res[$i]['Grocery']},'{$res[$i]['RecruitmentDate']}','{$res[$i]['BeginDateContract']}','{$res[$i]['EndDateContract']}','{$res[$i]['phone']}','{$res[$i]['mobile']}',{$res[$i]['Term_contract']},{$res[$i]['month_Trial']},'{$res[$i]['Birth_Certificate_Num']}','{$res[$i]['Issued']}','{$res[$i]['National_Code']}',{$res[$i]['Marital_Status']},'{$res[$i]['Address']}','{$res[$i]['degree_Education']}','{$res[$i]['insurance_Number']}','{$res[$i]['account_Number']}',{$res[$i]['EvaluationScore']},'{$res[$i]['description']}','{$res[$i]['father_Name']}')";
                $db->Query($sqlin);
            }
        }
        return 'موفقی بودا کودی بولا نبولا';
    }

    public function getUnitList($page = 1){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page - 1) * $numRows;
        $sql = "SELECT * FROM `official_productive_units`";
        $sql .= " ORDER BY `RowID` DESC LIMIT $start," . $numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for ($y = 0; $y < $listCount; $y++) {
            $name = array();
            $query = "SELECT `Fname`,`Lname` FROM `personnel` WHERE `Unit_id`={$res[$y]['RowID']} AND `isEnable`=1";
            $rst = $db->ArrayQuery($query);
            $cnt = count($rst);
            for ($i = 0; $i < $cnt; $i++) {
                $name[] = $rst[$i]['Fname'] . ' ' . $rst[$i]['Lname'];
            }
            $finalRes[$y]['Udesc'] = implode(' - ', $name);
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['Uname'] = $res[$y]['Uname'];
            $finalRes[$y]['cDate'] = (strtotime($res[$y]['cDate']) > 0 ? $ut->greg_to_jal($res[$y]['cDate']) : '');
            $finalRes[$y]['efficiency'] = $res[$y]['efficiency'] . ' درصد';
            $finalRes[$y]['Utype'] = (intval($res[$y]['Utype']) == 0 ? 'تولیدی' : 'سربار');
        }
        return $finalRes;
    }

    public function getUnitListCountRows(){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `official_productive_units`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function unitInfo($uid){
        $db = new DBi();
        $sql = "SELECT `Uname`,`Udesc`,`Utype` FROM `official_productive_units` WHERE `RowID`=" . $uid;
        $res = $db->ArrayQuery($sql);
        if (count($res) == 1) {
            $res = array("uid" => $uid, "Uname" => $res[0]['Uname'], "Udesc" => $res[0]['Udesc'], "Utype" => $res[0]['Utype']);
            return $res;
        } else {
            return false;
        }
    }

    public function createUnit($Uname, $Udesc, $Utype){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "INSERT INTO `official_productive_units` (`Uname`,`Udesc`,`Utype`) VALUES ('{$Uname}','{$Udesc}',{$Utype})";
        $res = $db->Query($sql);
        if (intval($res) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function editUnit($uid, $Uname, $Udesc, $Utype){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `official_productive_units` SET `Uname`='{$Uname}',`Udesc`='{$Udesc}',`Utype`={$Utype} WHERE `RowID`=" . $uid;
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = ((intval($res) == -1 || $res == 0) ? 0 : 1);
        if (intval($res)) {
            return true;
        } else {
            return false;
        }
    }

    public function getPersonnelList($Pname, $Pfamily, $Pcode, $Punit, $RsDate, $ReDate, $TsAmount, $TeAmount, $Pname1, $Pfamily1, $Pcode1, $Punit1, $RsDate1, $ReDate1, $TsAmount1, $TeAmount1, $ability, $status,$endContractDate, $page = 1){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page - 1) * $numRows;
        $w = array();
        if (strlen(trim($Pname)) > 0) {
            $w[] = '`Fname`="' . $Pname . '" ';
        }
        if (strlen(trim($Pfamily)) > 0) {
            $w[] = '`Lname`="' . $Pfamily . '" ';
        }
        if (strlen(trim($Pcode)) > 0) {
            $w[] = '`PersonnelCode`=' . $Pcode . ' ';
        }
        if (intval($Punit) > 0) {
            $w[] = '`Unit_id`=' . $Punit . ' ';
        }
        if (strlen(trim($RsDate)) > 0) {
            $RsDate = $ut->jal_to_greg($RsDate);
            $w[] = '`RecruitmentDate`>="' . $RsDate . '" ';
        }
        if (strlen(trim($endContractDate)) > 0) {
            $endContractDate = $ut->jal_to_greg($endContractDate);
            $w[] = '`EndDateContract`="' . $endContractDate . '" ';
        }
        if (strlen(trim($ReDate)) > 0) {
            $ReDate = $ut->jal_to_greg($ReDate);
            $w[] = '`RecruitmentDate`<="' . $ReDate . '" ';
        }
        if (strlen(trim($TsAmount)) > 0) {
            $w[] = '`TotalCosts`>=' . $TsAmount . ' ';
        }
        if (strlen(trim($TeAmount)) > 0) {
            $w[] = '`TotalCosts`<=' . $TeAmount . ' ';
        }

        if (strlen(trim($Pname1)) > 0) {
            $w[] = '`Fname`="' . $Pname1 . '" ';
        }
        if (strlen(trim($Pfamily1)) > 0) {
            $w[] = '`Lname`="' . $Pfamily1 . '" ';
        }
        if (strlen(trim($Pcode1)) > 0) {
            $w[] = '`PersonnelCode`=' . $Pcode1 . ' ';
        }
        if (intval($Punit1) > 0) {
            $w[] = '`Unit_id`=' . $Punit1 . ' ';
        }
        if (strlen(trim($RsDate1)) > 0) {
            $RsDate1 = $ut->jal_to_greg($RsDate1);
            $w[] = '`RecruitmentDate`>="' . $RsDate1 . '" ';
        }
        if (strlen(trim($ReDate1)) > 0) {
            $ReDate1 = $ut->jal_to_greg($ReDate1);
            $w[] = '`RecruitmentDate`<="' . $ReDate1 . '" ';
        }
        if (strlen(trim($TsAmount1)) > 0) {
            $w[] = '`TotalCosts`>=' . $TsAmount1 . ' ';
        }
        if (strlen(trim($TeAmount1)) > 0) {
            $w[] = '`TotalCosts`<=' . $TeAmount1 . ' ';
        }
        if(intval($status) >= 0){
            $w[] = '`isEnable` ='.$status.' ';
        }


        $sql = "SELECT `personnel`.`RowID`,`Fname`,`Lname`,`PersonnelCode`,`RecruitmentDate`,`BeginDateContract`,`EndDateContract`,`Uname`
                FROM `personnel` INNER JOIN `official_productive_units` ON (`official_productive_units`.`RowID`=`personnel`.`Unit_id`) ";
        if (intval($ability) > 0) {
            $sql .= "LEFT JOIN `personnel_ability` ON (`personnel`.`RowID`=`personnel_ability`.`pid`)";
            $w[] = '`aid`=' . $ability . ' ';
        }
        if (count($w)) {
            $where = implode(" AND ", $w);
            $sql .= " WHERE " . $where;
        }
        $sql .= " LIMIT $start," . $numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $ut->fileRecorder('sql'.$sql);
        $finalRes = array();
        for ($y = 0; $y < $listCount; $y++) {
            $sqq = "SELECT COUNT(`RowID`) AS `cnt` FROM `personnel_doc` WHERE `uid`={$res[$y]['RowID']} AND `status`=1";
            $rst = $db->ArrayQuery($sqq);
            $finalRes[$y]['btnType'] = ($rst[0]['cnt'] == 15 ? 'btn-info' : 'btn-danger');
            $finalRes[$y]['icon'] = 'fa-file';
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['Name'] = $res[$y]['Fname'] . ' ' . $res[$y]['Lname'];
            $finalRes[$y]['PersonnelCode'] = $res[$y]['PersonnelCode'];
            $finalRes[$y]['Uname'] = $res[$y]['Uname'];
            $finalRes[$y]['RecruitmentDate'] = (strtotime($res[$y]['RecruitmentDate']) > 0 ? $ut->greg_to_jal($res[$y]['RecruitmentDate']) : '--------');
            $finalRes[$y]['BeginDateContract'] = (strtotime($res[$y]['BeginDateContract']) > 0 ? $ut->greg_to_jal($res[$y]['BeginDateContract']) : '--------');
            $finalRes[$y]['EndDateContract'] = (strtotime($res[$y]['EndDateContract']) > 0 ? $ut->greg_to_jal($res[$y]['EndDateContract']) : '--------');
        }
        return $finalRes;
    }

    public function getPersonnelListCountRows($Pname, $Pfamily, $Pcode, $Punit, $RsDate, $ReDate, $TsAmount, $TeAmount, $Pname1, $Pfamily1, $Pcode1, $Punit1, $RsDate1, $ReDate1, $TsAmount1, $TeAmount1, $ability, $status,$endContractDate){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $w = array();
        if (strlen(trim($Pname)) > 0) {
            $w[] = '`Fname`="' . $Pname . '" ';
        }
        if (strlen(trim($Pfamily)) > 0) {
            $w[] = '`Lname`="' . $Pfamily . '" ';
        }
        if (strlen(trim($Pcode)) > 0) {
            $w[] = '`PersonnelCode`=' . $Pcode . ' ';
        }
        if (intval($Punit) > 0) {
            $w[] = '`Unit_id`=' . $Punit . ' ';
        }
        if (strlen(trim($RsDate)) > 0) {
            $RsDate = $ut->jal_to_greg($RsDate);
            $w[] = '`RecruitmentDate`>="' . $RsDate . '" ';
        }
        if (strlen(trim($endContractDate)) > 0) {
            $endContractDate = $ut->jal_to_greg($endContractDate);
            $w[] = '`EndDateContract`="' . $endContractDate . '" ';
        }

        
        if (strlen(trim($ReDate)) > 0) {
            $ReDate = $ut->jal_to_greg($ReDate);
            $w[] = '`RecruitmentDate`<="' . $ReDate . '" ';
        }
        if (strlen(trim($TsAmount)) > 0) {
            $w[] = '`TotalCosts`>=' . $TsAmount . ' ';
        }
        if (strlen(trim($TeAmount)) > 0) {
            $w[] = '`TotalCosts`<=' . $TeAmount . ' ';
        }

        if (strlen(trim($Pname1)) > 0) {
            $w[] = '`Fname`="' . $Pname1 . '" ';
        }
        if (strlen(trim($Pfamily1)) > 0) {
            $w[] = '`Lname`="' . $Pfamily1 . '" ';
        }
        if (strlen(trim($Pcode1)) > 0) {
            $w[] = '`PersonnelCode`=' . $Pcode1 . ' ';
        }
        if (intval($Punit1) > 0) {
            $w[] = '`Unit_id`=' . $Punit1 . ' ';
        }
        if (strlen(trim($RsDate1)) > 0) {
            $RsDate1 = $ut->jal_to_greg($RsDate1);
            $w[] = '`RecruitmentDate`>="' . $RsDate1 . '" ';
        }
        if (strlen(trim($ReDate1)) > 0) {
            $ReDate1 = $ut->jal_to_greg($ReDate1);
            $w[] = '`RecruitmentDate`<="' . $ReDate1 . '" ';
        }
        if (strlen(trim($TsAmount1)) > 0) {
            $w[] = '`TotalCosts`>=' . $TsAmount1 . ' ';
        }
        if (strlen(trim($TeAmount1)) > 0) {
            $w[] = '`TotalCosts`<=' . $TeAmount1 . ' ';
        }
        if(intval($status) >= 0){
            $w[] = '`isEnable` ='.$status.' ';
        }

        $sql = "SELECT `personnel`.`RowID` FROM `personnel` ";
        if (intval($ability) > 0) {
            $sql .= "LEFT JOIN `personnel_ability` ON (`personnel`.`RowID`=`personnel_ability`.`pid`)";
            $w[] = '`aid`=' . $ability . ' ';
        }
        if (count($w)) {
            $where = implode(" AND ", $w);
            $sql .= " WHERE " . $where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getTotalAmountPersonnel($Pname, $Pfamily, $Pcode, $Punit, $RsDate, $ReDate, $TsAmount, $TeAmount, $Pname1, $Pfamily1, $Pcode1, $Punit1, $RsDate1, $ReDate1, $TsAmount1, $TeAmount1, $ability,$status){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $w = array();
        if (strlen(trim($Pname)) > 0) {
            $w[] = '`Fname`="' . $Pname . '" ';
        }
        if (strlen(trim($Pfamily)) > 0) {
            $w[] = '`Lname`="' . $Pfamily . '" ';
        }
        if (strlen(trim($Pcode)) > 0) {
            $w[] = '`PersonnelCode`=' . $Pcode . ' ';
        }
        if (intval($Punit) > 0) {
            $w[] = '`Unit_id`=' . $Punit . ' ';
        }
        if (strlen(trim($RsDate)) > 0) {
            $RsDate = $ut->jal_to_greg($RsDate);
            $w[] = '`RecruitmentDate`>="' . $RsDate . '" ';
        }
        if (strlen(trim($ReDate)) > 0) {
            $ReDate = $ut->jal_to_greg($ReDate);
            $w[] = '`RecruitmentDate`<="' . $ReDate . '" ';
        }
        if (strlen(trim($TsAmount)) > 0) {
            $w[] = '`TotalCosts`>=' . $TsAmount . ' ';
        }
        if (strlen(trim($TeAmount)) > 0) {
            $w[] = '`TotalCosts`<=' . $TeAmount . ' ';
        }

        if (strlen(trim($Pname1)) > 0) {
            $w[] = '`Fname`="' . $Pname1 . '" ';
        }
        if (strlen(trim($Pfamily1)) > 0) {
            $w[] = '`Lname`="' . $Pfamily1 . '" ';
        }
        if (strlen(trim($Pcode1)) > 0) {
            $w[] = '`PersonnelCode`=' . $Pcode1 . ' ';
        }
        if (intval($Punit1) > 0) {
            $w[] = '`Unit_id`=' . $Punit1 . ' ';
        }
        if (strlen(trim($RsDate1)) > 0) {
            $RsDate1 = $ut->jal_to_greg($RsDate1);
            $w[] = '`RecruitmentDate`>="' . $RsDate1 . '" ';
        }
        if (strlen(trim($ReDate1)) > 0) {
            $ReDate1 = $ut->jal_to_greg($ReDate1);
            $w[] = '`RecruitmentDate`<="' . $ReDate1 . '" ';
        }
        if (strlen(trim($TsAmount1)) > 0) {
            $w[] = '`TotalCosts`>=' . $TsAmount1 . ' ';
        }
        if (strlen(trim($TeAmount1)) > 0) {
            $w[] = '`TotalCosts`<=' . $TeAmount1 . ' ';
        }
        if(intval($status) >= 0){
            $w[] = '`isEnable` ='.$status.' ';
        }
        $sql = "SELECT `TotalCosts` FROM `personnel`";
        if (intval($ability) > 0) {
            $sql .= "LEFT JOIN `personnel_ability` ON (`personnel`.`RowID`=`personnel_ability`.`pid`)";
            $w[] = '`aid`=' . $ability . ' ';
        }
        if (count($w)) {
            $where = implode(" AND ", $w);
            $sql .= " WHERE " . $where;
        }
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $query = "SELECT `AvailableDays` FROM `available_days` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $fixedPrice = ($rst[0]['AvailableDays'] * 7.33);

        $totalCost = 0;
        for ($i = 0; $i < $cnt; $i++) {
            $totalCost += $res[$i]['TotalCosts'];
        }
        $hourCost = $totalCost / $fixedPrice;
        $dayCost = $totalCost / $rst[0]['AvailableDays'];
        $monthCost = $totalCost / 12;
        $Costs = array($totalCost, $hourCost, $dayCost, $monthCost);
        return $Costs;
    }

    public function personnelInfo($pid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `personnel` WHERE `RowID`=" . $pid;
        $res = $db->ArrayQuery($sql);

        $sqq = "SELECT `Ability`,`proficiency`,`passedCourse` FROM `personnel_ability` INNER JOIN `abilities` ON (`personnel_ability`.`aid`=`abilities`.`RowID`) WHERE `pid`={$pid}";
        $resq = $db->ArrayQuery($sqq);
        $cnt = count($resq);
        $arr = array();
        for ($i = 0; $i < $cnt; $i++) {
            $arr[] = $resq[$i]['Ability'];
            $arr[] = $resq[$i]['proficiency'];
            $arr[] = $resq[$i]['passedCourse'];
        }
        if (count($res) == 1) {
            $query = "SELECT `minimumSalary`,`maximumSalary`,`achievementConditions` FROM `salary_group` WHERE `RowID`={$res[0]['sgid']}";
            $rst = $db->ArrayQuery($query);

            $res = array("pid" => $pid,
                "type" => $res[0]['type'],
                "Fname" => $res[0]['Fname'],
                "Lname" => $res[0]['Lname'],
                "PersonnelCode" => $res[0]['PersonnelCode'],
                "birthDate" => (strtotime($res[0]['birthDate']) > 0 ? $ut->greg_to_jal($res[0]['birthDate']) : ''),
                "RecruitmentDate" => (strtotime($res[0]['RecruitmentDate']) > 0 ? $ut->greg_to_jal($res[0]['RecruitmentDate']) : ''),
                "BeginDateContract" => (strtotime($res[0]['BeginDateContract']) > 0 ? $ut->greg_to_jal($res[0]['BeginDateContract']) : ''),
                "EndDateContract" => (strtotime($res[0]['EndDateContract']) > 0 ? $ut->greg_to_jal($res[0]['EndDateContract']) : ''),
                "LeaveDate" => (strtotime($res[0]['leaveDate']) > 0 ? $ut->greg_to_jal($res[0]['leaveDate']) : ''),
                "Unit_id" => $res[0]['Unit_id'],
                "wage" => number_format($res[0]['wage']),
                "yearsCost" => number_format($res[0]['yearsCost']),
                "dailyWages" => number_format($res[0]['dailyWages']),
                "RightHousing" => number_format($res[0]['RightHousing']),
                "numberChildren" => $res[0]['numberChildren'],
                "Child_Allowance" => ((is_null($res[0]['Child_Allowance']) || $res[0]['Child_Allowance'] == 0) ? 0 : number_format($res[0]['Child_Allowance'] / $res[0]['numberChildren'])),
                "Grocery" => number_format($res[0]['Grocery']),
                "Shift" => $res[0]['shiftPercent'],
                "SalaryOutofList" => number_format($res[0]['SalaryOutofList']),
                "Service" => ((is_null($res[0]['Service']) || $res[0]['Service'] == 0) ? '' : number_format($res[0]['Service'] / 12)),
                "OvertimeLunch" => ((is_null($res[0]['OvertimeLunch']) || $res[0]['OvertimeLunch'] == 0) ? '' : number_format($res[0]['OvertimeLunch'])),
                "OvertimeService" => ((is_null($res[0]['OvertimeService']) || $res[0]['OvertimeService'] == 0) ? '' : number_format($res[0]['OvertimeService'])),
                "NoBenefits" => $res[0]['NoBenefits'],
                "insuranceNo" => $res[0]['insuranceNo'],
                "responsibility_right" => ((is_null($res[0]['responsibility_right']) || $res[0]['responsibility_right'] == 0) ? '' : number_format($res[0]['responsibility_right'])),
                "hardWork" => ((is_null($res[0]['hardWork']) || $res[0]['hardWork'] == 0) ? '' : number_format($res[0]['hardWork'])),
                "job_right" => ((is_null($res[0]['job_right']) || $res[0]['job_right'] == 0) ? '' : number_format($res[0]['job_right'])),
                "financial_allowance" => ((is_null($res[0]['financial_allowance']) || $res[0]['financial_allowance'] == 0) ? '' : number_format($res[0]['financial_allowance'])),
                "phone" => $res[0]['phone'],
                "mobile" => $res[0]['mobile'],
                "Term_contract" => $res[0]['Term_contract'],
                "month_Trial" => $res[0]['month_Trial'],
                "Birth_Certificate_Num" => $res[0]['Birth_Certificate_Num'],
                "Issued" => $res[0]['Issued'],
                "National_Code" => $res[0]['National_Code'],
                "Marital_Status" => $res[0]['Marital_Status'],
                "personnelRightMarry" => number_format($res[0]['personnelRightMarry']),
                "Address" => $res[0]['Address'],
                "degree_field_study" => $res[0]['degree_field_study'],
                "insurance_Number" => $res[0]['insurance_Number'],
                "account_Number" => $res[0]['account_Number'],
                "EvaluationScore" => $res[0]['EvaluationScore'],
                "description" => $res[0]['description'],
                "Abilities" => implode(',', $arr),
                "father_Name" => $res[0]['father_Name'],
                "leaveStatus" => $res[0]['leaveStatus'],
                "overtimeStatus" => $res[0]['overtimeStatus'],
                "AboutOTHoursMonth" => $res[0]['AboutOTHoursMonth'],
                "sgid" => $res[0]['sgid'],
                "gender" => $res[0]['gender'],
                "status" => $res[0]['isEnable'],
                "minimumSalary" => $rst[0]['minimumSalary'],
                "maximumSalary" => $rst[0]['maximumSalary'],
                "achievementConditions" => $rst[0]['achievementConditions']
            );
            return $res;
        } else {
            return false;
        }
    }

    public function personnelDocInfo($pid){
        $db = new DBi();
        $query = "SELECT `Fname`,`Lname`,`gender` FROM `personnel` WHERE `RowID`={$pid}";
        $rst = $db->ArrayQuery($query);
        $name = ($rst[0]['gender'] == 1 ? 'آقای '.$rst[0]['Fname'].' '.$rst[0]['Lname'] : 'خانم '.$rst[0]['Fname'].' '.$rst[0]['Lname']);

        $sql = "SELECT `did`,`status`,`description` FROM `personnel_doc` WHERE `uid`=" . $pid;
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $finalRes = array();
        $finalRes['Questionnaire'] = 0;
        $finalRes['QuestionnaireDesc'] = '';
        $finalRes['Recognizance'] = 0;
        $finalRes['RecognizanceDesc'] = '';
        $finalRes['NationalCard'] = 0;
        $finalRes['NationalCardDesc'] = '';
        $finalRes['NationalCardDependants'] = 0;
        $finalRes['NationalCardDependantsDesc'] = '';
        $finalRes['InsuranceBooklet'] = 0;
        $finalRes['InsuranceBookletDesc'] = '';
        $finalRes['CardMilitary'] = 0;
        $finalRes['CardMilitaryDesc'] = '';
        $finalRes['InsuranceRecords'] = 0;
        $finalRes['InsuranceRecordsDesc'] = '';
        $finalRes['DegreeEducation'] = 0;
        $finalRes['DegreeEducationDesc'] = '';
        $finalRes['Photo'] = 0;
        $finalRes['PhotoDesc'] = '';
        $finalRes['Certificate'] = 0;
        $finalRes['CertificateDesc'] = '';
        $finalRes['VerificationServiceRecords'] = 0;
        $finalRes['VerificationServiceRecordsDesc'] = '';
        $finalRes['LackBackground'] = 0;
        $finalRes['LackBackgroundDesc'] = '';
        $finalRes['AccountNumber'] = 0;
        $finalRes['AccountNumberDesc'] = '';
        $finalRes['CheckPromissoryNote'] = 0;
        $finalRes['CheckPromissoryNoteDesc'] = '';
        $finalRes['Experiments'] = 0;
        $finalRes['ExperimentsDesc'] = '';
        $finalRes['name'] = $name;

        for ($i = 0; $i < $cnt; $i++) {
            switch ($res[$i]['did']) {
                case 1:
                    $finalRes['Questionnaire'] = $res[$i]['status'];
                    $finalRes['QuestionnaireDesc'] = $res[$i]['description'];
                    break;
                case 2:
                    $finalRes['Recognizance'] = $res[$i]['status'];
                    $finalRes['RecognizanceDesc'] = $res[$i]['description'];
                    break;
                case 3:
                    $finalRes['NationalCard'] = $res[$i]['status'];
                    $finalRes['NationalCardDesc'] = $res[$i]['description'];
                    break;
                case 4:
                    $finalRes['NationalCardDependants'] = $res[$i]['status'];
                    $finalRes['NationalCardDependantsDesc'] = $res[$i]['description'];
                    break;
                case 5:
                    $finalRes['InsuranceBooklet'] = $res[$i]['status'];
                    $finalRes['InsuranceBookletDesc'] = $res[$i]['description'];
                    break;
                case 6:
                    $finalRes['CardMilitary'] = $res[$i]['status'];
                    $finalRes['CardMilitaryDesc'] = $res[$i]['description'];
                    break;
                case 7:
                    $finalRes['InsuranceRecords'] = $res[$i]['status'];
                    $finalRes['InsuranceRecordsDesc'] = $res[$i]['description'];
                    break;
                case 8:
                    $finalRes['DegreeEducation'] = $res[$i]['status'];
                    $finalRes['DegreeEducationDesc'] = $res[$i]['description'];
                    break;
                case 9:
                    $finalRes['Photo'] = $res[$i]['status'];
                    $finalRes['PhotoDesc'] = $res[$i]['description'];
                    break;
                case 10:
                    $finalRes['Certificate'] = $res[$i]['status'];
                    $finalRes['CertificateDesc'] = $res[$i]['description'];
                    break;
                case 11:
                    $finalRes['VerificationServiceRecords'] = $res[$i]['status'];
                    $finalRes['VerificationServiceRecordsDesc'] = $res[$i]['description'];
                    break;
                case 12:
                    $finalRes['LackBackground'] = $res[$i]['status'];
                    $finalRes['LackBackgroundDesc'] = $res[$i]['description'];
                    break;
                case 13:
                    $finalRes['AccountNumber'] = $res[$i]['status'];
                    $finalRes['AccountNumberDesc'] = $res[$i]['description'];
                    break;
                case 14:
                    $finalRes['CheckPromissoryNote'] = $res[$i]['status'];
                    $finalRes['CheckPromissoryNoteDesc'] = $res[$i]['description'];
                    break;
                case 15:
                    $finalRes['Experiments'] = $res[$i]['status'];
                    $finalRes['ExperimentsDesc'] = $res[$i]['description'];
                    break;
            }
        }
        return $finalRes;
    }

    public function createPersonnelDocument($pid, $Questionnaire, $Recognizance, $NationalCard, $NationalCardDependants, $InsuranceBooklet, $CardMilitary, $InsuranceRecords,
                                            $DegreeEducation, $Photo, $Certificate, $VerificationServiceRecords, $LackBackground, $AccountNumber,
                                            $CheckPromissoryNote, $Experiments, $QuestionnaireDesc, $RecognizanceDesc, $NationalCardDesc, $NationalCardDependantsDesc,
                                            $InsuranceBookletDesc, $CardMilitaryDesc, $InsuranceRecordsDesc, $DegreeEducationDesc, $PhotoDesc, $CertificateDesc,
                                            $VerificationServiceRecordsDesc, $LackBackgroundDesc, $AccountNumberDesc, $CheckPromissoryNoteDesc, $ExperimentsDesc){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "DELETE FROM `personnel_doc` WHERE `uid`={$pid}";
        $db->Query($sql);
        $arr = array(1, $Questionnaire, $QuestionnaireDesc, 2, $Recognizance, $RecognizanceDesc, 3, $NationalCard, $NationalCardDesc, 4, $NationalCardDependants, $NationalCardDependantsDesc, 5, $InsuranceBooklet, $InsuranceBookletDesc, 6, $CardMilitary, $CardMilitaryDesc,
            7, $InsuranceRecords, $InsuranceRecordsDesc, 8, $DegreeEducation, $DegreeEducationDesc, 9, $Photo, $PhotoDesc, 10, $Certificate, $CertificateDesc, 11, $VerificationServiceRecords, $VerificationServiceRecordsDesc, 12, $LackBackground, $LackBackgroundDesc,
            13, $AccountNumber, $AccountNumberDesc, 14, $CheckPromissoryNote, $CheckPromissoryNoteDesc, 15, $Experiments, $ExperimentsDesc);
        $j = 0;
        for ($i = 0; $i < 15; $i++) {
            $query = "INSERT INTO `personnel_doc` (`uid`,`did`,`status`,`description`) VALUES ({$pid},{$arr[$j]},{$arr[$j+1]},'{$arr[$j+2]}')";
            $db->Query($query);
            $j += 3;
        }
        return true;
    }

    public function createPersonnel($Fname, $Lname, $fatherName, $Pcode,$birthDate,$RecruitmentDate, $Sdate, $Edate, $phone, $mobile, $TermContract, $monthTrial,
                                    $BCertificate, $Issued, $NationalCode, $MaritalStatus, $personnelRightMarry,$Address, $insuranceNumber, $AccountNum, $EvaluationScore,
                                    $Unit, $Wage, $YearsCost, $RightHousing, $NumberChildren, $Child, $Grocery, $Shift, $OutOfList, $Service, $OTLunch,
                                    $OTService, $responsibilityRight, $hardWork, $jobRight, $financialAllowance, $description, $mazaya, $AbilityProficiency,
                                    $DegreeFieldStudy, $AboutOTHoursMonth, $LeaveStatus, $OvertimeStatus, $sgid,$gender,$status,$NoInsurance,$type,
                                    $hourlyWages,$avgDayInMonth,$dailyDutyWorkingHours){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();

        $DegreeFieldStudy = (strlen(trim($DegreeFieldStudy)) > 0 ? explode(',', $DegreeFieldStudy) : array());
        if (((count($DegreeFieldStudy)) % 2) >= 1) {
            return -1;
        }
        $DegreeFieldStudy = implode(',', $DegreeFieldStudy);
        $AbilityProficiency = (strlen(trim($AbilityProficiency)) > 0 ? explode(',', $AbilityProficiency) : array());
        $cntAbility = count($AbilityProficiency) / 3;
        if (((count($AbilityProficiency)) % 3) >= 1) {
            return -2;
        }else{
            if (strlen(trim($TermContract)) == 0) {
                $start = $ut->jal_to_greg_to_ts($Sdate);
                $end = $ut->jal_to_greg_to_ts_EndDay($Edate);
                $datediff = $end - $start;
                $TermContract = round($datediff / (60 * 60 * 24));
            }
            $birthDate = $ut->jal_to_greg($birthDate);
            $RecruitmentDate = $ut->jal_to_greg($RecruitmentDate);
            $Sdate = $ut->jal_to_greg($Sdate);
            $Edate = $ut->jal_to_greg($Edate);


            if (intval($type) == 1){  // پرسنل عادی
                $OutOfList1 = ($OutOfList / 30) * 30.5;
                $responsibilityRight1 = ($responsibilityRight / 30) * 30.5;
                $jobRight1 = ($jobRight / 30) * 30.5;

                if ($mazaya == 1) {
                    $YFR = 0;
                    $Sanavat = 0;
                    $eps = 0;  // عیدی پاداش سنوات
                }else{
                    // عیدی - پاداش
                    $Sanavat = (intval($Wage) + intval($YearsCost)) * 30;
                    $eydipadash = $Sanavat * 2;
                    // if ($eydipadash > 159248520) {
                    //     $YFR = 79624260;
                    //     $eps = (159248520 + $Sanavat);
                    // } else {
                    //     $YFR = $Sanavat;
                    //     $eps = $Sanavat * 3;
                    // }

                    if ($eydipadash > 175903810) {
                        $YFR = 87951905;
                        $eps = (175903810 + $Sanavat);
                    } else {
                        $YFR = $Sanavat;
                        $eps = $Sanavat * 3;
                    }
                }

                $dailyWages = intval($Wage) + intval($YearsCost);  // حقوق پایه روزانه
                $monthlyWages = intval($dailyWages) * 30.5;  // حقوق پایه ماهانه
                $ChildAllowance = intval($NumberChildren) * intval($Child);  // حق اولاد ماهانه
                $shiftPercent = $Shift;
                $Shift = ($dailyWages * 30) * ($shiftPercent/100);

                // حقوق کل داخل لیست ماهانه
                $SalaryInofList = intval($monthlyWages) + intval($RightHousing) + intval($ChildAllowance) + intval($Grocery) + intval($Shift)+intval($personnelRightMarry);

                if (intval($NoInsurance) == 1){
                    $Worker = 0;
                    $EmployerSalane = 0;
                    $tax = 0;
                }else{
                    //حق اولاد معاف از  بیمه می باشد 
                    // حق بیمه سهم کارگر ماهانه
                    $Worker = ((intval($monthlyWages) + intval($Grocery) + intval($RightHousing) + intval($Shift)+intval($personnelRightMarry)) * 7) / 100;

                    // حق بیمه سهم کارفرما ماهانه
                    $EmployerSalane = (((intval($monthlyWages) + intval($Grocery) + intval($RightHousing) + intval($Shift)+intval($personnelRightMarry)) * 23) / 100);

                    //$SalaryTax = $SalaryInofList - (intval($RightHousing) + intval($Grocery) + $Worker);
                    $SalaryTax = $SalaryInofList;

                    // مالیات
                    // if (intval($SalaryTax) > 340000000){  // بیشتر از 340,000,000 ریال
                    //     $temp = ($SalaryTax - 340000000) * 0.3;
                    //     $tax = 4000000 + 13500000 + 22000000 + $temp;
                    // }elseif (intval($SalaryTax) > 230000000){  // تا سقف 230,000,000 ریال
                    //     $temp = ($SalaryTax - 230000000) * 0.2;
                    //     $tax = 4000000 + 13500000 + $temp;
                    // }elseif (intval($SalaryTax) > 140000000){  // تا سقف 140,000,000 ریال
                    //     $temp = ($SalaryTax - 140000000) * 0.15;
                    //     $tax = 4000000 + $temp;
                    // }elseif (intval($SalaryTax) > 100000000){  // تا سقف 150,000,000 ریال
                    //     $tax = ($SalaryTax - 100000000) * 0.1;
                    // }else{
                    //     $tax = 0;
                    // }

                    if (intval($SalaryTax) > 400000000){  // بیشتر از 340,000,000 ریال
                        $temp = ($SalaryTax - 400000000) * 0.4;
                        $tax =  $temp;
                    }elseif (intval($SalaryTax) > 270000000){  // تا سقف 230,000,000 ریال
                        $temp = ($SalaryTax ) * 0.2;
                        $tax = $temp;
                    }elseif (intval($SalaryTax) > 165000000){  // تا سقف 140,000,000 ریال
                        $temp = ($SalaryTax) * 0.15;
                        $tax = $temp;
                    }elseif (intval($SalaryTax) > 120000000){  // تا سقف 100,000,000 ریال
                        $tax = ($SalaryTax - 120000000) * 0.1;
                    }else{
                        $tax = 0;
                    }
                }

                // حقوق ناخالص ماهانه
                $grossMonthlySalary = intval($SalaryInofList) + intval($OutOfList1) + intval($responsibilityRight1) + intval($hardWork) + intval($jobRight1) + intval($financialAllowance);

                // حقوق ناخالص سالانه
                $AnnualSalaries = ($grossMonthlySalary * 12) + $eps;

                // مرخصی
               // $leaveCost = ($dailyWages / 7.33) * 16;
               $leaveCost = ($dailyWages * 26) /12;

                // هزینه سرویس ایاب و ذهاب سالانه
                $ServiceSalane = intval($Service) * 12;

                // هزینه کل تمام شده سالانه
                $totalCosts = intval($AnnualSalaries) + intval($EmployerSalane) + intval($ServiceSalane);

                // حقوق هر ساعت اضافه کار
                $OTWageHour = ((intval($dailyWages) + ((intval($OutOfList1) + intval($responsibilityRight) + intval($jobRight)) / 30)) / 7.33) * 1.4;

                // هزینه تمام شده اضافه کار - ساعتی
                $totalOTWageHour = ((intval($OTLunch) + intval($OTService)) / 7.33) + intval($OTWageHour);

                $sql = "INSERT INTO `personnel` (`Fname`,`Lname`,`PersonnelCode`,`Unit_id`,`wage`,`yearsCost`,`dailyWages`,`monthlyWages`,
                                                 `RightHousing`,`numberChildren`,`Child_Allowance`,`Grocery`,`Shift`,`SalaryInofList`,
                                                 `SalaryOutofList`,`WorkerPremium`,`AnnualSalaries`,`EmployerPremium`,`Years`,`Festival`,
                                                 `Reward`,`Service`,`TotalCosts`,`RecruitmentDate`,`OvertimeLunch`,`OvertimeService`,
                                                 `OvertimeWage`,`totalOvertimeCost`,`NoBenefits`,`responsibility_right`,`hardWork`,`job_right`,
                                                 `financial_allowance`,`BeginDateContract`,`EndDateContract`,`phone`,`mobile`,`Term_contract`,
                                                 `month_Trial`,`Birth_Certificate_Num`,`Issued`,`National_Code`,`Marital_Status`,`Address`,
                                                 `insurance_Number`,`account_Number`,`EvaluationScore`,`description`,`father_Name`,`degree_field_study`,
                                                 `Tax`,`LeaveCost`,`grossMonthlySalary`,`AboutOTHoursMonth`,`leaveStatus`,`overtimeStatus`,
                                                 `sgid`,`gender`,`isEnable`,`insuranceNo`,`type`,`shiftPercent`,`birthDate`,`personnelRightMarry`) 
                                                 VALUES ('{$Fname}','{$Lname}','{$Pcode}',{$Unit},{$Wage},{$YearsCost},{$dailyWages},{$monthlyWages},
                                                          {$RightHousing},{$NumberChildren},{$ChildAllowance},{$Grocery},{$Shift},{$SalaryInofList},
                                                          {$OutOfList},{$Worker},{$AnnualSalaries},{$EmployerSalane},{$Sanavat},{$YFR},
                                                          {$YFR},{$ServiceSalane},{$totalCosts},'{$RecruitmentDate}',{$OTLunch},{$OTService},
                                                          {$OTWageHour},{$totalOTWageHour},{$mazaya},{$responsibilityRight},{$hardWork},{$jobRight},
                                                          {$financialAllowance},'{$Sdate}','{$Edate}','{$phone}','{$mobile}',{$TermContract},
                                                          {$monthTrial},'{$BCertificate}','{$Issued}','{$NationalCode}',{$MaritalStatus},'{$Address}',
                                                          '{$insuranceNumber}','{$AccountNum}','{$EvaluationScore}','{$description}','{$fatherName}','{$DegreeFieldStudy}',
                                                          '{$tax}','{$leaveCost}','{$grossMonthlySalary}','{$AboutOTHoursMonth}','{$LeaveStatus}','{$OvertimeStatus}',
                                                          '{$sgid}','{$gender}','{$status}','{$NoInsurance}','{$type}','{$shiftPercent}','{$birthDate}','{$personnelRightMarry}')";
            }else{   // پرسنل ساعتی
                $grossMonthlySalary = (($avgDayInMonth * $dailyDutyWorkingHours) + $AboutOTHoursMonth) * $hourlyWages;
                $tax = $grossMonthlySalary * 0.1;
                $monthlyWages = $grossMonthlySalary - $tax;
                $AnnualSalaries = $monthlyWages * 12;
                $sql = "INSERT INTO `personnel` (`Fname`,`Lname`,`PersonnelCode`,`Unit_id`,`grossMonthlySalary`,`monthlyWages`,`AnnualSalaries`,`RecruitmentDate`,`BeginDateContract`,
                                                 `EndDateContract`,`phone`,`mobile`,`Term_contract`,`month_Trial`,`Birth_Certificate_Num`,`Issued`,`National_Code`,`wage`,
                                                 `Marital_Status`,`Address`,`insurance_Number`,`account_Number`,`EvaluationScore`,`description`,`father_Name`,`Tax`,`yearsCost`,
                                                 `dailyWages`,`degree_field_study`,`AboutOTHoursMonth`,`overtimeStatus`,`gender`,`isEnable`,`type`,`TotalCosts`,`birthDate`) 
                                                 VALUES ('{$Fname}','{$Lname}','{$Pcode}',{$Unit},{$grossMonthlySalary},{$monthlyWages},{$AnnualSalaries},'{$RecruitmentDate}','{$Sdate}',
                                                         '{$Edate}','{$phone}','{$mobile}',{$TermContract},{$monthTrial},'{$BCertificate}','{$Issued}','{$NationalCode}',{$avgDayInMonth},
                                                          '{$MaritalStatus}','{$Address}','{$insuranceNumber}','{$AccountNum}',{$EvaluationScore},'{$description}','{$fatherName}',{$tax},{$dailyDutyWorkingHours},
                                                          '{$hourlyWages}','{$DegreeFieldStudy}','{$AboutOTHoursMonth}','{$OvertimeStatus}','{$gender}','{$status}','{$type}','{$AnnualSalaries}','{$birthDate}')";
            }

            $res = $db->Query($sql);
            $ut->fileRecorder("1:".$sql);
            if (intval($res) > 0) {
                $id = $db->InsertrdID();
                $j = 0;
                for ($i = 0; $i < $cntAbility; $i++) {
                    $sqq = "SELECT `RowID` FROM `abilities` WHERE `Ability`='{$AbilityProficiency[$j]}' ";
                    $rst = $db->ArrayQuery($sqq);
                    $sglAb = "INSERT INTO `personnel_ability` (`pid`,`aid`,`proficiency`,`passedCourse`) VALUES ({$id},{$rst[0]['RowID']},'{$AbilityProficiency[$j+1]}','{$AbilityProficiency[$j+2]}')";
                    $db->Query($sglAb);
                    $ut->fileRecorder("2:".$sglAb);
                    $j += 3;
                }
                return true;
            } else {
                return false;
            }
        }
    }

    public function editPersonnel($pid, $Fname, $Lname, $fatherName, $Pcode,$birthDate, $RecruitmentDate, $Sdate, $Edate, $phone, $mobile, $TermContract, $monthTrial,
                                  $BCertificate, $Issued, $NationalCode, $MaritalStatus,$personnelRightMarry, $Address, $insuranceNumber, $AccountNum, $EvaluationScore,
                                  $Unit, $Wage, $YearsCost, $RightHousing, $NumberChildren, $Child, $Grocery, $Shift, $OutOfList, $Service, $OTLunch,
                                  $OTService, $responsibilityRight, $hardWork, $jobRight, $financialAllowance, $description, $mazaya, $AbilityProficiency,
                                  $DegreeFieldStudy, $AboutOTHoursMonth, $LeaveStatus, $OvertimeStatus, $sgid,$gender,$status,$NoInsurance,$type,
                                  $hourlyWages,$avgDayInMonth,$dailyDutyWorkingHours,$leaveDate){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
		//$//ut->fileRecorder("leaveDate:".$leaveDate);
        $DegreeFieldStudy = (strlen(trim($DegreeFieldStudy)) > 0 ? explode(',', $DegreeFieldStudy) : array());
        if (((count($DegreeFieldStudy)) % 2) >= 1) {
            return -1;
        }
        $DegreeFieldStudy = implode(',', $DegreeFieldStudy);
        $AbilityProficiency = (strlen(trim($AbilityProficiency)) > 0 ? explode(',', $AbilityProficiency) : array());
        $cntAbility = count($AbilityProficiency) / 3;
        if (((count($AbilityProficiency)) % 3) >= 1) {
            return -2;
        } else {
            if (strlen(trim($TermContract)) == 0) {
                $start = $ut->jal_to_greg_to_ts($Sdate);
                $end = $ut->jal_to_greg_to_ts_EndDay($Edate);
                $datediff = $end - $start;
                $TermContract = round($datediff / (60 * 60 * 24));
            }
            $birthDate = $ut->jal_to_greg($birthDate);
            $RecruitmentDate = $ut->jal_to_greg($RecruitmentDate);
            $Sdate = $ut->jal_to_greg($Sdate);
            $Edate = $ut->jal_to_greg($Edate);

            if (intval($type) == 1) {  // پرسنل عادی
                $OutOfList1 = ($OutOfList / 30) * 30.5;
                $responsibilityRight1 = ($responsibilityRight / 30) * 30.5;
                $jobRight1 = ($jobRight / 30) * 30.5;

                if ($mazaya == 1) {
                    $YFR = 0;
                    $Sanavat = 0;
                    $eps = 0;  // عیدی پاداش سنوات
                }else{
                    // عیدی - پاداش
                    $Sanavat = (intval($Wage) + intval($YearsCost)) * 30;
                    $eydipadash = $Sanavat * 2;

                    // if ($eydipadash > 159248520) {
                    //     $YFR = 79624260;
                    //     $eps = (159248520 + $Sanavat);
                    // } else {
                    //     $YFR = $Sanavat;
                    //     $eps = $Sanavat * 3;
                    // }

                    if ($eydipadash > 175903810) {
                        $YFR = 87951905;
                        $eps = (175903810 + $Sanavat);
                    } else {
                        $YFR = $Sanavat;
                        $eps = $Sanavat * 3;
                    }
                }

                $dailyWages = intval($Wage) + intval($YearsCost);  // حقوق پایه روزانه
                $monthlyWages = intval($dailyWages) * 30.5;  // حقوق پایه ماهانه
                $ChildAllowance = intval($NumberChildren) * intval($Child);  // حق اولاد ماهانه
                $shiftPercent = intval($Shift);
                $Shift = ($dailyWages * 30) * ($shiftPercent/100);

                // حقوق کل داخل لیست ماهانه
                $SalaryInofList = intval($monthlyWages) + intval($RightHousing) + intval($ChildAllowance) + intval($Grocery) + intval($Shift)+intval($personnelRightMarry);

                if (intval($NoInsurance) == 1){
                    $Worker = 0;
                    $EmployerSalane = 0;
                    $tax = 0;
                }else{
                    // حق بیمه سهم کارگر ماهانه
                    $Worker = ((intval($monthlyWages) + intval($Grocery) + intval($RightHousing) + intval($Shift)+intval($personnelRightMarry)) * 7) / 100;

                    // حق بیمه سهم کارفرما ماهانه
                    $EmployerSalane = (((intval($monthlyWages) + intval($Grocery) + intval($RightHousing) + intval($Shift)+intval($personnelRightMarry)) * 23) / 100);

                    //$SalaryTax = $SalaryInofList - (intval($RightHousing) + intval($Grocery) + $Worker);
                    $SalaryTax = $SalaryInofList;

                    // مالیات
                    // if (intval($SalaryTax) > 340000000){  // بیشتر از 340,000,000 ریال
                    //     $temp = ($SalaryTax - 340000000) * 0.3;
                    //     $tax = 4000000 + 13500000 + 22000000 + $temp;
                    // }elseif (intval($SalaryTax) > 230000000){  // تا سقف 230,000,000 ریال
                    //     $temp = ($SalaryTax - 230000000) * 0.2;
                    //     $tax = 4000000 + 13500000 + $temp;
                    // }elseif (intval($SalaryTax) > 140000000){  // تا سقف 140,000,000 ریال
                    //     $temp = ($SalaryTax - 140000000) * 0.15;
                    //     $tax = 4000000 + $temp;
                    // }elseif (intval($SalaryTax) > 100000000){  // تا سقف 150,000,000 ریال
                    //     $tax = ($SalaryTax - 100000000) * 0.1;
                    // }else{
                    //     $tax = 0;
                    // }

                    if (intval($SalaryTax) > 400000000){  // بیشتر از 340,000,000 ریال
                        $temp = ($SalaryTax - 400000000) * 0.4;
                        $tax =  $temp;
                    }elseif (intval($SalaryTax) > 270000000){  // تا سقف 230,000,000 ریال
                        $temp = ($SalaryTax ) * 0.2;
                        $tax = $temp;
                    }elseif (intval($SalaryTax) > 165000000){  // تا سقف 140,000,000 ریال
                        $temp = ($SalaryTax) * 0.15;
                        $tax = $temp;
                    }elseif (intval($SalaryTax) > 120000000){  // تا سقف 100,000,000 ریال
                        $tax = ($SalaryTax - 120000000) * 0.1;
                    }else{
                        $tax = 0;
                    }
                }

                // حقوق ناخالص ماهانه
                $grossMonthlySalary = intval($SalaryInofList) + intval($OutOfList1) + intval($responsibilityRight1) + intval($hardWork) + intval($jobRight1) + intval($financialAllowance);

                // حقوق ناخالص سالانه
                $AnnualSalaries = ($grossMonthlySalary * 12) + $eps;

                // مرخصی
              //  $leaveCost = ($dailyWages / 7.33) * 16;
                $leaveCost = ($dailyWages * 26) /12;

                // هزینه سرویس ایاب و ذهاب سالانه
                $ServiceSalane = intval($Service) * 12;

                // هزینه کل تمام شده سالانه
                $totalCosts = intval($AnnualSalaries) + intval($EmployerSalane) + intval($ServiceSalane);

                // حقوق هر ساعت اضافه کار
                $OTWageHour = ((intval($dailyWages) + ((intval($OutOfList1) + intval($responsibilityRight) + intval($jobRight)) / 30)) / 7.33) * 1.4;

                // هزینه تمام شده اضافه کار - ساعتی
                $totalOTWageHour = ((intval($OTLunch) + intval($OTService)) / 7.33) + intval($OTWageHour);

                if($acm->hasAccess('administrativeManagement')) {
			
                    $sql = "UPDATE `personnel` SET `Fname`='{$Fname}',`Lname`='{$Lname}',`PersonnelCode`='{$Pcode}',`Unit_id`={$Unit},`wage`={$Wage},`yearsCost`={$YearsCost},`dailyWages`={$dailyWages},`monthlyWages`={$monthlyWages},
                    `RightHousing`={$RightHousing},`numberChildren`={$NumberChildren},`Child_Allowance`={$ChildAllowance},`Grocery`={$Grocery},`Shift`={$Shift},`SalaryInofList`={$SalaryInofList},`SalaryOutofList`={$OutOfList},
                    `WorkerPremium`={$Worker},`AnnualSalaries`={$AnnualSalaries},`EmployerPremium`={$EmployerSalane},`Years`={$Sanavat},`Festival`={$YFR},`Reward`={$YFR},`Service`={$ServiceSalane},`TotalCosts`={$totalCosts},
                    `RecruitmentDate`='{$RecruitmentDate}',`OvertimeLunch`={$OTLunch},`OvertimeService`={$OTService},`OvertimeWage`={$OTWageHour},`totalOvertimeCost`={$totalOTWageHour},`NoBenefits`={$mazaya},`responsibility_right`={$responsibilityRight},
                    `hardWork`={$hardWork},`job_right`={$jobRight},`financial_allowance`={$financialAllowance},`BeginDateContract`='{$Sdate}',`EndDateContract`='{$Edate}',`phone`='{$phone}',`mobile`='{$mobile}',`Term_contract`={$TermContract},
                    `month_Trial`={$monthTrial},`Birth_Certificate_Num`='{$BCertificate}',`Issued`='{$Issued}',`National_Code`='{$NationalCode}',`Marital_Status`={$MaritalStatus},`Address`='{$Address}',`insurance_Number`='{$insuranceNumber}',
                    `account_Number`='{$AccountNum}',`EvaluationScore`={$EvaluationScore},`description`='{$description}',`father_Name`='{$fatherName}',`degree_field_study`='{$DegreeFieldStudy}',`Tax`={$tax},`LeaveCost`={$leaveCost},
                    `grossMonthlySalary`={$grossMonthlySalary},`shiftPercent`={$shiftPercent},`AboutOTHoursMonth`={$AboutOTHoursMonth},`leaveStatus`={$LeaveStatus},`overtimeStatus`={$OvertimeStatus},`sgid`={$sgid},`gender`={$gender},
                    `isEnable`={$status},`insuranceNo`={$NoInsurance},`type`={$type},`birthDate`='{$birthDate}',`personnelRightMarry`='{$personnelRightMarry}'";// WHERE `RowID`=" . $pid;
					if(strlen($leaveDate)>0){
                       $leaveDate=$ut->jal_to_greg($leaveDate);
                        $sql.=",`leaveDate`='{$leaveDate}'";
                    }
                    $sql.=" WHERE `RowID`=" . $pid;
				}else{
                    $sql = "UPDATE `personnel` SET `Fname`='{$Fname}',`Lname`='{$Lname}',`PersonnelCode`='{$Pcode}',`Unit_id`={$Unit},`wage`={$Wage},`yearsCost`={$YearsCost},`dailyWages`={$dailyWages},`monthlyWages`={$monthlyWages},
                    `RightHousing`={$RightHousing},`numberChildren`={$NumberChildren},`Child_Allowance`={$ChildAllowance},`Grocery`={$Grocery},`Shift`={$Shift},`SalaryInofList`={$SalaryInofList},`WorkerPremium`={$Worker},
                    `AnnualSalaries`={$AnnualSalaries},`EmployerPremium`={$EmployerSalane},`Years`={$Sanavat},`Festival`={$YFR},`Reward`={$YFR},`Service`={$ServiceSalane},`TotalCosts`={$totalCosts},`RecruitmentDate`='{$RecruitmentDate}',
                    `OvertimeLunch`={$OTLunch},`OvertimeService`={$OTService},`OvertimeWage`={$OTWageHour},`totalOvertimeCost`={$totalOTWageHour},`NoBenefits`={$mazaya},`BeginDateContract`='{$Sdate}',`EndDateContract`='{$Edate}',
                    `phone`='{$phone}',`mobile`='{$mobile}',`Term_contract`={$TermContract},`month_Trial`={$monthTrial},`Birth_Certificate_Num`='{$BCertificate}',`Issued`='{$Issued}',`National_Code`='{$NationalCode}',
                    `Marital_Status`={$MaritalStatus},`Address`='{$Address}',`insurance_Number`='{$insuranceNumber}',`account_Number`='{$AccountNum}',`EvaluationScore`={$EvaluationScore},`description`='{$description}',`shiftPercent`={$shiftPercent},
                    `father_Name`='{$fatherName}',`degree_field_study`='{$DegreeFieldStudy}',`Tax`={$tax},`LeaveCost`={$leaveCost},`grossMonthlySalary`={$grossMonthlySalary},`AboutOTHoursMonth`={$AboutOTHoursMonth},`leaveStatus`={$LeaveStatus},
                    `overtimeStatus`={$OvertimeStatus},`sgid`={$sgid},`gender`={$gender},`isEnable`={$status},`insuranceNo`={$NoInsurance},`type`={$type},`birthDate`='{$birthDate}',`personnelRightMarry`='{$personnelRightMarry}'";// WHERE `RowID`=" . $pid;
					if(strlen($leaveDate)>0){
                       $leaveDate=$ut->jal_to_greg($leaveDate);
                        $sql.=",`leaveDate`='{$leaveDate}'";
                    }
                    $sql.=" WHERE `RowID`=" . $pid;
				}
            }else{  // پرسنل ساعتی
                $grossMonthlySalary = (($avgDayInMonth * $dailyDutyWorkingHours) + $AboutOTHoursMonth) * $hourlyWages;
                $tax = $grossMonthlySalary * 0.1;
                $monthlyWages = $grossMonthlySalary - $tax;
                $AnnualSalaries = $monthlyWages * 12;

                $sql = "UPDATE `personnel` SET `Fname`='{$Fname}',`Lname`='{$Lname}',`PersonnelCode`='{$Pcode}',`Unit_id`={$Unit},`wage`={$avgDayInMonth},`yearsCost`={$dailyDutyWorkingHours},`dailyWages`={$hourlyWages},`monthlyWages`={$monthlyWages},`AnnualSalaries`={$AnnualSalaries},
                `RightHousing`=0,`numberChildren`=0,`Child_Allowance`=0,`Grocery`=0,`Shift`=0,`SalaryInofList`=0,`WorkerPremium`=0,`EmployerPremium`=0,`Years`=0,`Festival`=0,`Reward`=0,`Service`=0,`OvertimeLunch`=0,`OvertimeService`=0,`OvertimeWage`=0,
                `totalOvertimeCost`=0,`NoBenefits`=0,`LeaveCost`=0,`leaveStatus`=0,`sgid`=0,`insuranceNo`=0,`TotalCosts`={$AnnualSalaries},`RecruitmentDate`='{$RecruitmentDate}',`BeginDateContract`='{$Sdate}',`EndDateContract`='{$Edate}',`phone`='{$phone}',
                `mobile`='{$mobile}',`Term_contract`={$TermContract},`month_Trial`={$monthTrial},`Birth_Certificate_Num`='{$BCertificate}',`Issued`='{$Issued}',`National_Code`='{$NationalCode}',`Marital_Status`={$MaritalStatus},`Address`='{$Address}',
                `insurance_Number`='{$insuranceNumber}',`account_Number`='{$AccountNum}',`EvaluationScore`={$EvaluationScore},`description`='{$description}',`father_Name`='{$fatherName}',`degree_field_study`='{$DegreeFieldStudy}',`Tax`={$tax},
                `grossMonthlySalary`={$grossMonthlySalary},`AboutOTHoursMonth`={$AboutOTHoursMonth},`overtimeStatus`={$OvertimeStatus},`gender`={$gender},`isEnable`={$status},`SalaryOutofList`=0,`responsibility_right`=0,`hardWork`=0,`job_right`=0,`financial_allowance`=0,`type`={$type},`birthDate`='{$birthDate}'";//WHERE `RowID`=" . $pid;
				if(strlen($leaveDate)>0){
                    $leaveDate=$ut->jal_to_greg($leaveDate);
                    $sql.=",`leaveDate`='{$leaveDate}'";
                }
                $sql.=" WHERE `RowID`=" . $pid;
            }

            $db->Query($sql);
            $res = $db->AffectedRows();
            $res = ((intval($res) == -1) ? 0 : 1);
            if (intval($res)) {
                $sqdel = "DELETE FROM `personnel_ability` WHERE `pid`={$pid}";
                $db->Query($sqdel);
                $j = 0;
                for ($i = 0; $i < $cntAbility; $i++) {
                    $sqq = "SELECT `RowID` FROM `abilities` WHERE `Ability`='{$AbilityProficiency[$j]}' ";
                    $rst = $db->ArrayQuery($sqq);
                    $sglAb = "INSERT INTO `personnel_ability` (`pid`,`aid`,`proficiency`,`passedCourse`) VALUES ({$pid},{$rst[0]['RowID']},'{$AbilityProficiency[$j+1]}','{$AbilityProficiency[$j+2]}')";
                    $db->Query($sglAb);
                    $j += 3;
                }
                return true;
            } else {
                return false;
            }
        }
    }

    public function editAllPersonnel(){
        $acm = new acm();
        $ut=new Utility();
        $work_dayes=30;
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sqls = "SELECT * FROM `personnel`";
        $rts = $db->ArrayQuery($sqls);
        $cnts = count($rts);

        for ($i=0;$i<$cnts;$i++) {
            if (intval($rts[$i]['type']) == 1) {  // پرسنل عادی
                $OutOfList1 = ($rts[$i]['SalaryOutofList'] / 30) * $work_dayes;
                $responsibilityRight1 = ($rts[$i]['responsibility_right'] / 30) * $work_dayes;
                $jobRight1 = ($rts[$i]['job_right'] / 30) * $work_dayes;

                if ($rts[$i]['NoBenefits'] == 1) {
                    $YFR = 0;
                    $Sanavat = 0;
                    $eps = 0;  // عیدی پاداش سنوات
                } else {
                    // عیدی - پاداش
                    $Sanavat = (intval($rts[$i]['wage']) + intval($rts[$i]['yearsCost'])) * 30;
                    $eydipadash = $Sanavat * 2;
                    // if ($eydipadash > 159248520) {
                    //     $YFR = 79624260;
                    //     $eps = (159248520 + $Sanavat);
                    // } else {
                    //     $YFR = $Sanavat;
                    //     $eps = $Sanavat * 3;
                    // }
                    $o=($rts[$i]['SalaryOutofList'] / 30)+intval($rts[$i]['wage']);
                    if($o*60<intval($rts[$i]['wage']*90)){
                        $YFR = $o;
                        $eps = ($o*2 + $Sanavat);
                    }
                    else{
                        $YFR=intval($rts[$i]['wage']);
                        $eps=intval($rts[$i]['wage']*90);
                    }
                    // if ($eydipadash > 175903810) {
                    //     $YFR = 87951905;
                    //     $eps = (175903810 + $Sanavat);
                    // } else {
                    //     $YFR = $Sanavat;
                    //     $eps = $Sanavat * 3;
                    // }
                }

                $dailyWages = intval($rts[$i]['wage']) + intval($rts[$i]['yearsCost']);  // حقوق پایه روزانه
                $monthlyWages = intval($dailyWages) * $work_dayes;  // حقوق پایه ماهانه
               // $ut->fileRecorder($rts[$i]['Fname']." ".$rts[$i]['Lname'].":".$dailyWages."   ".intval($monthlyWages) );
                $ChildAllowance = $rts[$i]['Child_Allowance'];  // حق اولاد ماهانه
                $shiftPercent = $rts[$i]['shiftPercent'];
                $personnelRightMarry=$rts[$i]['personnelRightMarry'];
                $Shift = ($dailyWages * 30) * ($shiftPercent / 100);

                // حقوق کل داخل لیست ماهانه
                $SalaryInofList = intval($monthlyWages) + intval($rts[$i]['RightHousing']) + intval($ChildAllowance) + intval($rts[$i]['Grocery']) + intval($Shift)+intval($personnelRightMarry);
                $ut->fileRecorder($rts[$i]['Fname']." ".$rts[$i]['Lname']."==".'monthlyWages:'. intval($monthlyWages) ." *RightHousing: ". intval($rts[$i]['RightHousing']) ." * ChildAllowance:". intval($ChildAllowance) ." *Grocery: ". intval($rts[$i]['Grocery']) ." *Shift: ". intval($Shift)." *personnelRightMarry: ".intval($personnelRightMarry));
                if (intval($rts[$i]['insuranceNo']) == 1) {
                    $Worker = 0;
                    $EmployerSalane = 0;
                    $tax = 0;
                } else {
                    // حق بیمه سهم کارگر ماهانه
                    $Worker = ((intval($monthlyWages) + intval($rts[$i]['Grocery']) + intval($rts[$i]['RightHousing']) + intval($Shift) + intval($personnelRightMarry)) * 7) / 100;

                    // حق بیمه سهم کارفرما ماهانه
                    $EmployerSalane = (((intval($monthlyWages) + intval($rts[$i]['Grocery']) + intval($rts[$i]['RightHousing']) + intval($Shift)+intval($personnelRightMarry)) * 23) / 100);

                    //$SalaryTax = $SalaryInofList - (intval($RightHousing) + intval($Grocery) + $Worker);
                    $SalaryTax = $SalaryInofList - $Worker;
                   // $SalaryTax = $SalaryInofList;
                   // $ut->fileRecorder($rts[$i]['Fname']." ".$rts[$i]['Lname'].":".$SalaryTax);
                  // $ut->fileRecorder($rts[$i]['Fname']." ".$rts[$i]['Lname'].":".$SalaryInofList." --".$Worker);

                    // مالیات
                    // if (intval($SalaryTax) > 340000000){  // بیشتر از 340,000,000 ریال
                    //     $temp = ($SalaryTax - 340000000) * 0.3;
                    //     $tax = 4000000 + 13500000 + 22000000 + $temp;
                    // }elseif (intval($SalaryTax) > 230000000){  // تا سقف 230,000,000 ریال
                    //     $temp = ($SalaryTax - 230000000) * 0.2;
                    //     $tax = 4000000 + 13500000 + $temp;
                    // }elseif (intval($SalaryTax) > 140000000){  // تا سقف 140,000,000 ریال
                    //     $temp = ($SalaryTax - 140000000) * 0.15;
                    //     $tax = 4000000 + $temp;
                    // }elseif (intval($SalaryTax) > 100000000){  // تا سقف 100,000,000 ریال
                    //     $tax = ($SalaryTax - 100000000) * 0.1;
                    // }else{
                    //     $tax = 0;
                    // }


                    if (intval($SalaryTax) > 400000000){  // بیشتر از 340,000,000 ریال
                        $temp = ($SalaryTax - 400000000) * 0.4;
                        $tax =  $temp;
                    }elseif (intval($SalaryTax) > 270000000){  // تا سقف 230,000,000 ریال
                        $temp = ($SalaryTax ) * 0.2;
                        $tax = $temp;
                    }elseif (intval($SalaryTax) > 165000000){  // تا سقف 140,000,000 ریال
                        $temp = ($SalaryTax) * 0.15;
                        $tax = $temp;
                    }elseif (intval($SalaryTax) > 120000000){  // تا سقف 100,000,000 ریال
                        $tax = ($SalaryTax - 120000000) * 0.1;
                    }else{
                        $tax = 0;
                    }
                }

                // حقوق ناخالص ماهانه
                $grossMonthlySalary = intval($SalaryInofList) + intval($OutOfList1) + intval($responsibilityRight1) + intval($rts[$i]['hardWork']) + intval($jobRight1) + intval($rts[$i]['financial_allowance']);

                // حقوق ناخالص سالانه
                $AnnualSalaries = ($grossMonthlySalary * 12) + $eps;

                // هزینه سرویس ایاب و ذهاب سالانه
                $ServiceSalane = $rts[$i]['Service'];

                // هزینه کل تمام شده سالانه
                $totalCosts = intval($AnnualSalaries) + intval($EmployerSalane) + intval($ServiceSalane);

                $sql = "UPDATE `personnel` SET `SalaryInofList`={$SalaryInofList},`WorkerPremium`={$Worker},`AnnualSalaries`={$AnnualSalaries},`EmployerPremium`={$EmployerSalane},`TotalCosts`={$totalCosts},`Tax`={$tax},`grossMonthlySalary`={$grossMonthlySalary} WHERE `RowID`=" . $rts[$i]['RowID'];
                $db->Query($sql);
            }
        }
        return true;
    }

    public function deletePersonnel($pid){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "DELETE FROM `personnel` WHERE `RowID`={$pid}";
        $db->Query($sql);
        $ar = $db->AffectedRows();
        $ar = (($ar == -1 || $ar == 0) ? 0 : 1);
        if (intval($ar) > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function array_insert(&$array, $position, $insert){
        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {
            $pos = array_search($position, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
        }
    }

    public function OtherInfoPersonnelHTM($pid){
        $acm = new acm();
        $ut=new Utility();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sqlt = "SELECT `type` FROM `personnel` WHERE `RowID`=" . $pid;
        $rst = $db->ArrayQuery($sqlt);

        if (intval($rst[0]['type']) == 1) {  // پرسنل عادی
            $sql = "SELECT `dailyWages`,`monthlyWages`,`RightHousing`,`Grocery`,`numberChildren`,`Child_Allowance`,`personnelRightMarry`,`Shift`,
                    `SalaryInofList`,`SalaryOutofList`,`responsibility_right`,`job_right`,`hardWork`,`financial_allowance`,
                    `grossMonthlySalary`,`WorkerPremium`,`Tax`,`EmployerPremium`,`LeaveCost`,`Festival`,`Reward`,`Years`,`Service`,
                    `AboutOTHoursMonth`,`OvertimeWage`,`Fname`,`Lname`,`leaveStatus`,`overtimeStatus`,`degree_field_study`
                    FROM `personnel` WHERE `RowID`=" . $pid;

            $res = $db->ArrayQuery($sql);
            $Name = $res[0]['Fname'] . ' ' . $res[0]['Lname'];

            $infoNames = array('روز کارکرد', 'حقوق روزانه', 'حقوق کارکرد', 'حق مسکن', 'کمک هزینه اقلام مصرفی خانوار', 'تعداد فرزند', 'عائله مندی','حق تاهل', 'نوبت کاری',
                               'جمع کل حقوق داخل لیست', 'حقوق خارج لیست', 'حق مسئولیت', 'حق شغل', 'کمک هزینه سرویس', 'کمک هزینه اجاره',
                               'جمع کل حقوق ناخالص', 'کسر بیمه', 'کسر مالیات', 'جمع کل حقوق خالص', 'حق بیمه سهم کارفرما', 'مرخصی',
                               'عیدی', 'پاداش', 'سنوات', 'هزینه سرویس', 'حدود ساعت اضافه کار در ماه', 'فوق العاده اضافه کار'
            );
            $cnt = count($res[0]) - 5;
            $view = array();
            for ($i = 0; $i < $cnt; $i++) {
                $keyName = key($res[0]);
                $view[] = $res[0]["$keyName"];
                next($res[0]);
            }

           // $monthSalaries = ($view[13] - $view[14]) - $view[15];  // جمع کل حقوق خالص
            $monthSalaries = ($view[14] - $view[15]) - $view[16];  // جمع کل حقوق خالص

            $this->array_insert($view, 0, 30.5);
           // $this->array_insert($view, 17, intval($monthSalaries));
            $this->array_insert($view, 18, intval($monthSalaries));
            $ut->fileRecorder($view);

            $htm = '';
            $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoSalary-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info text-white">';
            $htm .= '<td colspan="2" style="text-align: center;vertical-align: middle;font-family: dubai-Bold;font-size: 20px;width: 40%;">آقای / خانم ' . $Name . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">دریافتی ماهیانه ایشان بدون احتساب عیدی-پاداش-سنوات ( با/بدون اضافه کار-مرخصی )</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">دریافتی ماهیانه ایشان با احتساب عیدی-پاداش-سنوات ( با/بدون اضافه کار-مرخصی )</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">قیمت تمام شده ماهیانه ایشان برای سازمان ( با/بدون اضافه کار-مرخصی )</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $SalariesWithoutEPS = $monthSalaries;
            $ut->fileRecorder('1:'.$SalariesWithoutEPS);

          //  $SalariesWithEPS = $monthSalaries + (round($view[20] / 12)) + (round($view[21] / 12)) + (round($view[22] / 12));
            $SalariesWithEPS = $monthSalaries + (round($view[21] / 12)) + (round($view[22] / 12)) + (round($view[23] / 12));
         
           // $totalSalaries = $view[14] + $view[18] + (round($view[20] / 12)) + (round($view[21] / 12)) + (round($view[22] / 12));
            $totalSalaries = $view[15] + $view[19] + (round($view[21] / 12)) + (round($view[22] / 12)) + (round($view[23] / 12));

            if ($res[0]['leaveStatus'] == 1) {
               // $SalariesWithoutEPS += $view[19];
                $SalariesWithoutEPS += $view[20];
                //$SalariesWithEPS += $view[19];
                $SalariesWithEPS += $view[20];
               // $totalSalaries += $view[19];
                $totalSalaries += $view[20];
                $ut->fileRecorder('3:'.$SalariesWithEPS);
                
            }
            if ($res[0]['overtimeStatus'] == 1) {
                //$SalariesWithoutEPS += ($view[24] * $view[25]);
                $SalariesWithoutEPS += ($view[25] * $view[26]);
               // $SalariesWithEPS += ($view[24] * $view[25]);
                $SalariesWithEPS += ($view[25] * $view[26]);
               // $totalSalaries += ($view[24] * $view[25]);
                $totalSalaries += ($view[25] * $view[26]);
            }
            $annualTotalSalaries = $totalSalaries * 12;

            $cnt1 = count($view);
            for ($i=0;$i<$cnt1;$i++) {
                // if ($i == 20) {  // عیدی
                //     $e = number_format($view[20] / 12);
                // } else {
                //     unset($e);
                // }
                // if ($i == 21) {  // پاداش
                //     $p = number_format($view[21] / 12);
                // } else {
                //     unset($p);
                // }
                // if ($i == 22) {  // سنوات
                //     $s = number_format($view[22] / 12);
                // } else {
                //     unset($s);
                // }
                // if ($i == 14) {  // حقوق ناخالص
                //     $gslist = number_format($view[14]);
                // } else {
                //     unset($gslist);
                // }
                // if ($i == 17) {  // حقوق خالص
                //     $sinoflist = number_format($view[17]);
                // } else {
                //     unset($sinoflist);
                // }
                // if ($i == 18) {  // سهم کارفرما
                //     $EmployerPremium = number_format($view[18]);
                // } else {
                //     unset($EmployerPremium);
                // }
                // if ($i == 25 && $res[0]['overtimeStatus'] == 1) {
                //     $otCost = number_format($ot * $view[25]);
                //     $otCheck = '<input type="checkbox" checked id="overtimeCalclute" onchange="overtimeCalcluteStatus()" class="mr-2" />';
                // } elseif ($i == 25 && $res[0]['overtimeStatus'] == 0) {
                //     $otCheck = '<input type="checkbox" id="overtimeCalclute" onchange="overtimeCalcluteStatus()" class="mr-2" />';
                // }
                // if ($i == 19 && $res[0]['leaveStatus'] == 1) {
                //     $leaveCost = number_format($view[19]);
                //     $leaveCheck = '<input type="checkbox" checked id="leaveCalclute" onchange="leaveCalcluteStatus()" class="mr-2" />';
                // } elseif ($i == 19 && $res[0]['leaveStatus'] == 0) {
                //     unset($leaveCost);
                //     $leaveCheck = '<input type="checkbox" id="leaveCalclute" onchange="leaveCalcluteStatus()" class="mr-2" />';
                // } else {
                //     $leaveCheck = '';
                //     unset($leaveCost);
                // }
                // if ($i == 5) {
                //     $view[$i] = $view[$i] . ' نفر';
                // } elseif ($i != 0 && $i != 24) {
                //     if ($i == 20 || $i == 21 || $i == 22) {
                //         $view[$i] = (intval($view[$i]) == 0 ? 0 : number_format($view[$i] / 12));
                //     } else {
                //         $view[$i] = (intval($view[$i]) == 0 ? 0 : number_format($view[$i]));
                //     }
                // } elseif ($i == 24) {
                //     $ot = $view[$i];
                //     $view[$i] = "<input class='form-control' type='text' style='text-align: center;' value='" . $view[$i] . "' id='AboutOTHoursMonthTxt' onchange='aboutOTHoursMonthChange()' />";
                // }
                //----------------------------------------------------
                if ($i == 21) {  // عیدی
                    $e = number_format($view[21] / 12);
                } else {
                    unset($e);
                }
                if ($i == 22) {  // پاداش
                    $p = number_format($view[22] / 12);
                } else {
                    unset($p);
                }
                if ($i == 23) {  // سنوات
                    $s = number_format($view[23] / 12);
                } else {
                    unset($s);
                }
                if ($i == 15) {  // حقوق ناخالص
                    $gslist = number_format($view[15]);
                } else {
                    unset($gslist);
                }
                if ($i == 18) {  // حقوق خالص
                    $sinoflist = number_format($view[18]);
                } else {
                    unset($sinoflist);
                }
                if ($i == 19) {  // سهم کارفرما
                    $EmployerPremium = number_format($view[19]);
                } else {
                    unset($EmployerPremium);
                }
                if ($i == 26 && $res[0]['overtimeStatus'] == 1) {
                    $otCost = number_format($ot * $view[26]);
                    $otCheck = '<input type="checkbox" checked id="overtimeCalclute" onchange="overtimeCalcluteStatus()" class="mr-2" />';
                } elseif ($i == 26 && $res[0]['overtimeStatus'] == 0) {
                    $otCheck = '<input type="checkbox" id="overtimeCalclute" onchange="overtimeCalcluteStatus()" class="mr-2" />';
                }
                if ($i == 20 && $res[0]['leaveStatus'] == 1) {
                    $leaveCost = number_format($view[20]);
                    $leaveCheck = '<input type="checkbox" checked id="leaveCalclute" onchange="leaveCalcluteStatus()" class="mr-2" />';
                } elseif ($i == 20 && $res[0]['leaveStatus'] == 0) {
                    unset($leaveCost);
                    $leaveCheck = '<input type="checkbox" id="leaveCalclute" onchange="leaveCalcluteStatus()" class="mr-2" />';
                } else {
                    $leaveCheck = '';
                    unset($leaveCost);
                }
                if ($i == 5) {
                    $view[$i] = $view[$i] . ' نفر';
                } elseif ($i != 0 && $i != 25) {
                    if ($i == 21 || $i == 22 || $i == 23) {
                        $view[$i] = (intval($view[$i]) == 0 ? 0 : number_format($view[$i] / 12));
                    } else {
                        $view[$i] = (intval($view[$i]) == 0 ? 0 : number_format($view[$i]));
                    }
                } elseif ($i == 25) {
                    $ot = $view[$i];
                    $view[$i] = "<input class='form-control' type='text' style='text-align: center;' value='" . $view[$i] . "' id='AboutOTHoursMonthTxt' onchange='aboutOTHoursMonthChange()' />";
                }
                //----------------------------------------------------
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $infoNames[$i] . $leaveCheck . $otCheck . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $view[$i] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . (isset($otCost) ? $otCost : '') . ' ' . (isset($leaveCost) ? $leaveCost : '') . ' ' . (isset($sinoflist) ? $sinoflist : '') . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . (isset($otCost) ? $otCost : '') . ' ' . (isset($leaveCost) ? $leaveCost : '') . ' ' . (isset($sinoflist) ? $sinoflist : '') . ' ' . (isset($e) ? $e : '') . ' ' . (isset($p) ? $p : '') . ' ' . (isset($s) ? $s : '') . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . (isset($otCost) ? $otCost : '') . ' ' . (isset($leaveCost) ? $leaveCost : '') . ' ' . (isset($EmployerPremium) ? $EmployerPremium : '') . ' ' . (isset($gslist) ? $gslist : '') . ' ' . (isset($e) ? $e : '') . ' ' . (isset($p) ? $p : '') . ' ' . (isset($s) ? $s : '') . '</td>';
                $htm .= '</tr>';
            }

            $htm .= '<tr class="table-warning">';
            $htm .= '<td colspan="2" style="text-align: center;font-family: dubai-Regular;padding: 10px;">جمع کل</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($SalariesWithoutEPS) . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($SalariesWithEPS) . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($totalSalaries) . '</td>';
            $htm .= '</tr>';

            $htm .= '<tr class="table-danger">';
            $htm .= '<td colspan="4" style="text-align: center;font-family: dubai-Regular;padding: 10px;">جمع کل قیمت تمام شده یک ساله</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($annualTotalSalaries) . '<input type="hidden" id="personnelIDHidden" value="" /></td>';
            $htm .= '</tr>';

            $htm .= '</tbody>';
            $htm .= '</table>';
        }else{
            $sql = "SELECT `wage`,`yearsCost`,`dailyWages`,`AboutOTHoursMonth`,`grossMonthlySalary`,`Tax`,`monthlyWages`,`AnnualSalaries`,`Fname`,`Lname`,`overtimeStatus` FROM `personnel` WHERE `RowID`=" . $pid;
            $res = $db->ArrayQuery($sql);
            $Name = $res[0]['Fname'] . ' ' . $res[0]['Lname'];

            $infoNames = array('روز کارکرد', 'ساعت کارکرد موظفی روزانه', 'دستمزد ساعتی', 'حدود ساعت اضافه کار در ماه','جمع کل حقوق ناخالص ماهیانه', 'کسر مالیات', 'جمع کل حقوق خالص ماهیانه', 'جمع کل حقوق خالص سالانه');

            $cnt = count($res[0]) - 3;
            $view = array();
            for ($i = 0; $i < $cnt; $i++) {
                $keyName = key($res[0]);
                $view[] = $res[0]["$keyName"];
                next($res[0]);
            }

            $htm = '';
            $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoSalary-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info text-white">';
            $htm .= '<td colspan="2" style="text-align: center;vertical-align: middle;font-family: dubai-Bold;font-size: 20px;width: 40%;">آقای / خانم ' . $Name . '</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            for ($i = 0; $i < $cnt; $i++) {
                if ($i == 1) {
                    $view[$i] = $view[$i] . ' ساعت';
                } elseif ($i != 0 && $i != 3) {
                    $view[$i] = (intval($view[$i]) == 0 ? 0 : number_format($view[$i]));
                }

                if ($i == 3 && $res[0]['overtimeStatus'] == 1) {
                    $view[$i] = "<input class='form-control' type='text' style='text-align: center;' value='" . $view[$i] . "' id='AboutOTHoursMonthTxt' onchange='aboutOTHoursMonthChange()' />";
                    $otCheck = '<input type="checkbox" checked id="overtimeCalclute" onchange="overtimeCalcluteStatus()" class="mr-2" />';
                }elseif ($i == 3 && $res[0]['overtimeStatus'] == 0) {
                    $view[$i] = "<input class='form-control' type='text' style='text-align: center;' value='" . $view[$i] . "' id='AboutOTHoursMonthTxt' onchange='aboutOTHoursMonthChange()' />";
                    $otCheck = '<input type="checkbox" id="overtimeCalclute" onchange="overtimeCalcluteStatus()" class="mr-2" />';
                }else{
                    $otCheck = '';
                }

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $infoNames[$i] . $otCheck . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $view[$i] . '<input type="hidden" id="personnelIDHidden" value="" /></td>';
                $htm .= '</tr>';
            }
            $htm .= '</tbody>';
            $htm .= '</table>';
        }
        return $htm;
    }

    public function OtherInfoPersonnelMHTM($pid){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT `father_Name`,`BeginDateContract`,`EndDateContract`,`phone`,`mobile`,
                       `Term_contract`,`month_Trial`,`Birth_Certificate_Num`,`Issued`,`National_Code`,`Marital_Status`,
                       `Address`,`insurance_Number`,`account_Number`,`EvaluationScore`,`description`,`degree_field_study`
                       FROM `personnel` WHERE `RowID`=" . $pid;
        $res = $db->ArrayQuery($sql);
        $infoNames = array('نام پدر', 'تاریخ شروع قرارداد', 'تاریخ اتمام قرارداد', 'شماره تلفن ثابت', 'شماره تلفن همراه', 'مدت قرارداد', 'مدت آزمایشی', 'شماره شناسنامه', 'صادره از', 'کد ملی', 'وضعیت تاهل', 'آدرس', 'شماره بیمه', 'شماره حساب', 'امتیاز ارزشیابی', 'توضیحات');
        $cnt = count($res[0]) - 1;

        $res[0]['BeginDateContract'] = (strtotime($res[0]['BeginDateContract']) > 0 ? $ut->greg_to_jal($res[0]['BeginDateContract']) : '');
        $res[0]['EndDateContract'] = (strtotime($res[0]['EndDateContract']) > 0 ? $ut->greg_to_jal($res[0]['EndDateContract']) : '');
        $res[0]['Marital_Status'] = (intval($res[0]['Marital_Status']) == 0 ? 'مجرد' : 'متاهل');
        for ($i = 0; $i < $cnt; $i++) {
            $keyName = key($res[0]);
            $view[] = $res[0]["$keyName"];
            next($res[0]);
        }

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoPInfo-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">سایر اطلاعات</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مقدار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $infoNames[$i] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $view[$i] . '</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';

        if (strlen(trim($res[0]['degree_field_study'])) > 0) {
            $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoDegreeField-tableID1">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مدرک تحصیلی</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">رشته تحصیلی</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $iterator = 0;
            $degreeField = explode(',', $res[0]['degree_field_study']);
            $cnt = count($degreeField) / 2;
            $x = 0;
            for ($i = 0; $i < $cnt; $i++) {
                $iterator++;
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $degreeField[$x] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $degreeField[$x + 1] . '</td>';
                $htm .= '</tr>';
                $x += 2;
            }
            $htm .= '</tbody>';
            $htm .= '</table>';
        }

        $query = "SELECT `proficiency`,`passedCourse`,`Ability` FROM `abilities` INNER JOIN `personnel_ability` ON (`abilities`.`RowID`=`personnel_ability`.`aid`) WHERE `pid`={$pid}";
        $rst1 = $db->ArrayQuery($query);
        if (count($rst1) > 0) {
            $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoSalary-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 40%;">توانایی</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">میزان تسلط</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 30%;">نام دوره</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $iterator = 0;
            $cnt = count($rst1);
            for ($i = 0; $i < $cnt; $i++) {
                $iterator++;
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst1[$i]['Ability'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst1[$i]['proficiency'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst1[$i]['passedCourse'] . '</td>';
                $htm .= '</tr>';
            }
            $htm .= '</tbody>';
            $htm .= '</table>';
        }
        return $htm;
    }

    public function getPersonnelAgreementHtm($pid){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `personnel` WHERE `RowID`={$pid}";
        $res = $db->ArrayQuery($sql);
        // $marital_status='';
        // if(intval($res[0]['Marital_Status']) == 1) 
        // { $marital_status= 'متاهل &#9632; مجرد &#9633; 9633#& معیل';}
        // elseif(intval($res[0]['Marital_Status']==2))
        // {$marital_status='متاهل &#9632; مجرد &#9633; 9633#& معیل';}
        // else
        // {$marital_status='متاهل &#9633; مجرد &#9632;';}

        $TMSalary = ($res[0]['dailyWages'] * 30) + $res[0]['Grocery'] + $res[0]['RightHousing'] + $res[0]['Child_Allowance']+$res[0]['personnelRightMarry'];
        $addco = 'کیلومتر 12 جاده سنتو-خیابان ابرش-تقاطع اول سمت چپ-شرکت ابرش';
        $htm = '';
        $htm .= '<div class="demo" style="margin-top: -80px;">';
        // page 1
        $htm .= '<p style="padding:2px;margin:0px;text-align: center;direction: rtl;font-size: 17px;font-family: BTitr;">به نام خدا</p>';
        $htm .= '<p style="padding:2px;margin:0px;text-align: center;direction: rtl;font-size: 17px;font-family: BTitr;">شرکت فورج فلزات رنگین پارسیان (سهامی خاص)</p>';
        $htm .= '<p style="padding:2px;margin:0px;text-align: center;direction: rtl;font-size: 17px;font-family: BTitr;">((قرارداد کار))</p>';
        $htm .= '<p style="padding:2px;margin:0px;text-align: left;direction: rtl;font-size: 17px;font-family: BTitr;line-height: 0px;">' . $res[0]['PersonnelCode'] . '</p>';
        $htm .= '<hr>';

        $htm .= '<p style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">ماده 1 - هدف :</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;">این قرارداد به موجب ماده (10) قانون کار جمهوری اسلامی ایران و تبصره (3) الحاقی به ماده (7) قانون کار موضوع بند (الف ) ماده (8) قانون کار رفع برخی موانع تولید و سرمایه گزاری صنعتی – مصوب 1387/08/25 تشخیص مصلحت نظام، به منظور تامین نیروی انسانی مورد نیاز برای انجام امور محوله به شرح مواد زیر منعقد می گردد. </p>';

        $htm .= '<p style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">ماده 2 - مشخصات طرفین :</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">2-1-کارفرما : </span>شرکت <span style="font-size:15px;font-weight:bold;"> فورج فلزات رنگین پارسیان </span> به شماره ثبت 34801 واقع در '.$addco.' به نمایندگی <span style="font-size:15px;font-weight:bold;"> آقای سید جمال رضوی </span> با سمت مدیر عامل که منبعد در این قرارداد <span style="font-size:15px;font-weight:bold;"> طرف اول یا کارفرما </span> نامیده می شود.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">2-2-همکار : </span>نام و نام خانوادگی : <span style="font-size:20px;font-weight:bold;">'. $res[0]['Fname'] . ' ' . $res[0]['Lname'] .'</span>  نام پدر : '.$res[0]['father_Name'].' شماره شناسنامه : '.$res[0]['Birth_Certificate_Num'].' کد ملی : '.$res[0]['National_Code'].' وضعیت تاهل : '. (intval($res[0]['Marital_Status']) == 1 ? 'متاهل &#9632; مجرد &#9633;' : 'متاهل &#9633; مجرد &#9632;') .' وضعیت نظام وظیفه :  دارای کارت معافیت □ پایان خدمت □ دارای '.intval($res[0]['numberChildren']).' فرزند  با مدرک تحصیلی : '.($res[0]['degree_field_study']).' به نشانی '.(strlen(trim($res[0]['Address'])) > 0 ? $res[0]['Address'] : '...................................') . ' و شماره تلفن ثابت : '.(strlen(trim($res[0]['phone'])) > 0 ? $res[0]['phone'] : '...................................').' و همراه '.(strlen(trim($res[0]['mobile'])) > 0 ? $res[0]['mobile'] : '...................................').' که منبعد در این قرارداد <span style="font-size:15px;font-weight:bold;"> طرف دوم یا همکار </span> نامیده می شود.</p>';

        $htm .= '<p style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">ماده 3 - نوع قرارداد :</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;">قرارداد موقت</p>';

        $htm .= '<p style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">ماده 4 - شرح وظایف :</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;">وظایف محوله به طرف دوم، مطابق شرح وظایف سازمانی که به همکار ابلاغ می گردد، می باشد. لاکن این وظایف بصورت سیال بوده و طرف دوم متعهد می گردد کلیه امور ارجاعی را با حداکثر توان و صداقت کاری به سرانجام برساند.</p>';

        $htm .= '<p style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">ماده 5 - محل انجام کار :</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;">محل انجام کار، شرکت فورج فلزات رنگین پارسیان در کیلومتر 12 جاده سنتو - خیابان ابرش - تقاطع اول سمت چپ - شرکت ابرش  یا هر مکان دیگری که انجام امور اقتضا نماید، تعیین می گردد و طرف دوم با شناخت کافی از این امر قبول مسئولیت می نماید.</p>';

        $htm .= '<p style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">ماده 6 - مدت قرارداد :</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;">مدت اين قرارداد ' . $res[0]['Term_contract'] . ' ماه/ روز است كه شروع آن از تاريخ ' . $ut->greg_to_jal($res[0]['BeginDateContract']) . ' و پايان آن تاريخ ' . $ut->greg_to_jal($res[0]['EndDateContract']) . ' مي باشد.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">تبصره 1 : </span>مدت قرارداد برای دوره های متوالی نهایتا تا پایان همان سال <span style="font-size:15px;font-weight:bold;">بصورت یکطرفه از طرف کارفرما</span>، قابل تمدید می باشد.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">تبصره 2 : </span>تمدید قرارداد با شرایط و مفاد قرارداد حاضر، انجام خواهد شد و همکار حق هرگونه اعتراضی را در این خصوص از خود، سلب و ساقط خواهد نمود.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">تبصره 3 : </span>صرفا در صورت تصمیم طرف اول به قطع همکاری، به همکار اطلاع رسانی خواهد شد.</p>';
        $current_date=$ut->greg_to_jal($res[0]['EndDateContract']);
        $current_date=explode('/',$current_date);
        $htm .= '<p style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">ماده 7 - حق السعی (دستمزد)   :</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;">حقوق و مزایای قانونی بر اساس آخرین مصوبات شورای عالی کار و در سال '.$current_date[0].' مطابق با جدول زیر خواهد بود.</p>';
        $htm .= '<table class="table-bordered" align="center">
                        <tr>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">دستمزد روزانه</td>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">' . number_format($res[0]['dailyWages']) . '</td>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">حق جذب</td>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width:200px;">کمک هزینه اقلام مصرفی خانوار</td>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">' . number_format($res[0]['Grocery']) . '</td>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">حق سرپرستی</td>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">حق مسکن</td>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">' . number_format($res[0]['RightHousing']) . '</td>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">رفت و آمد</td>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">حق اولاد</td>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">' . number_format($res[0]['Child_Allowance']) . '</td>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">حق تاهل </td>
                            <td style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">' . number_format(intval($res[0]['personnelRightMarry'])) . '</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">جمع حقوق ماهیانه</td>
                            <td colspan="2" style="font-size: 19px;font-family: BLotus;text-align: center;width: 200px;">' . number_format(intval($TMSalary)) . '</td>
                        </tr>
                     </table>';
        $htm .= '<br>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">تبصره 1 : </span>سایر موضوعات مندرج در قانون کار و مقررات تبعی از جمله مزایای پایان سال، مرخصی استحقاقی، مرخصی استعلاجی، ماموریت، اضافه کار و غیره، نسبت به این قرارداد اعمال خواهد شد.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">تبصره 2 : </span>به موجب ماده 148 قانون کار، بیمه طرف دوم، نزد سازمان تامین اجتماعی با رعایت ضوابط مصوب، از تعهدات طرف اول است.</p>';
        $htm .= '<p style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">ماده 8 - تعهدات طرف دوم :</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">8-1- </span>طرف دوم صحت و اصالت مشخصات و اطلاعات فردی ارائه شده به طرف اول را شخصا تایید می نماید. در صورت احراز نادرستی تمام یا بخشی از اطلاعات یا عدم اصالت مدارک تسلیمی، کارفرما حق دارد یکطرفه قرارداد را فسخ کند و همکار حق هرگونه اعتراض را از خود سلب می نماید.</p>';
        $htm .= '<hr>';
      
        $htm .= '<div>';
        $htm .= '<p class="col-4 " style="text-align: right;font-size: 19px;font-family: BLotus;float: right;padding:0">محل امضا و اثر انگشت همکار</p>';

        $htm .= '<p class="col-4" style="text-align: center;font-size: 19px;font-family: BLotus;float: right;padding:0">امضا مدیر منابع انسانی</p>';

        $htm .= '<p class="col-4 " style="text-align: left;font-size: 19px;font-family: BLotus;float: right;padding:0">امضا قائم مقام مدیر عامل / امضا مدیر عامل</p>';
        $htm .= '</div>';

        // page 2
        // $htm .= '<br>';
        // $htm .= '<br>';
        // $htm .= '<br>';
        // $htm .= '<br>';
        $htm .= '<div class="contract_page2">';
        // $htm .= '<p style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">ماده 8 - تعهدات طرف دوم :</p>';
        // $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">8-1- </span>طرف دوم صحت و اصالت مشخصات و اطلاعات فردی ارائه شده به طرف اول را شخصا تایید می نماید. در صورت احراز نادرستی تمام یا بخشی از اطلاعات یا عدم اصالت مدارک تسلیمی، کارفرما حق دارد یکطرفه قرارداد را فسخ کند و همکار حق هرگونه اعتراض را از خود سلب می نماید.</p>';
        $htm .="<br>";
        $htm .="<br>";
        $htm .= '<p stsyle="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">8-2- </span>طرف دوم متعهد به رعایت حسن اخلاق، رفتار و شئونات انسانی در محل کار و همچنین رعایت تمامی قوانین، مقررات، آیین نامه ها و دستورالعمل های مدون که به اطلاع ایشان رسیده است، می باشد.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">8-3- </span>طرف دوم متعهد به انجام کار روزانه با توجه به ساعات شروع و خاتمه کار تعیین شده از سوی کارفرما می باشد.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">8-4- </span>طرف دوم ملزم به انجام اضافه کار و ماموریت های خارج از محل کار، طبق نظر کارفرما می باشد.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">8-5- </span>طرف دوم متعهد است خدمات موضوع قرارداد را که منطبق بر مصالح و قوانین داخلی شرکت می باشد، تحت نظارت مسئول مستقیم خود و در نهایت دقت، کوشش و امانت داری مطابق تشخیص کارفرما، انجام داده و تمامی اسناد کارفرما را محرمانه تلقی و از افشا آن خودداری نماید.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">8-6- </span>طرف دوم متعهد است از لوازم، ابزارآلات و اموالی که در اختیار ایشان می باشد، در راستای وظایف و منافع شرکت، استفاده نموده و چنانچه در اثر بی احتیاطی و استفاده نادرست از وسایل، ابزارآلات و اموال شرکت، خساراتی متوجه کارفرما گردد، طرف دوم ملزم به جبران خسارات وارده بر اساس تشخیص کارفرما، خواهد بود.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">8-7- </span>طراحی، کپی برداری، عکسبرداری و شبیه سازی از سیستم ها، نرم افزارها و تمامی امکانات و متعلقات کارفرما جهت ارائه به غیر و برای منافع شخصی، توسط طرف دوم ممنوع و قابل تعقیب قانونی است.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">8-8- </span>ابزارآلات، وسایل کار، اموال و سایر تجهیزات، بصورت امانی در اختیار همکار قرار دارد و خروج هرگونه کالا و تجهیزات، بدون مجوز کافرما، خیانت در امانت محسوب می گردد و کارفرما می تواند با اسناد و مدارک مثبته اقدام قانونی به عمل آورده و مراتب را از طریق مراجع ذی صلاح تعقیب نماید.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">8-9- </span>طرف دوم نمی تواند از هیچ منبعی خارج از شرکت، هیچگونه مزایای مستقیم یا غیر مستقیم که با استخدام و موقعیت وی در شرکت مربوط است، دریافت نماید. همچنین می بایست از قبول هرگونه هدایا جهت استفاده شخصی، خودداری نموده و مراتب را فورا به مقام مافوق خود گزارش دهد.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">8-10- </span>طرف دوم متعهد به پرداخت کلیه دیون احتمالی خود به شرکت می باشد و شرکت می تواند مطالبات خود را از محل حقوق و مزایای وی کسر نماید و طرف دوم حق هرگونه اعتراضی را از خویش سلب می نماید.</p>';

        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">8-11- </span>طرف دوم متعهد می گردد پس از خاتمه این قرارداد به هر دلیل و ختم اشتغال وی در شرکت طرف اول، حداقل برای مدت سه سال تمام شمسی از اشتغال  نزد هر شخص حقیقی یا حقوقی که فعالیت مشابه با شرکت طرف اول دارد خودداری نماید ولو متعهد است خسارت وارده را بنا به تشخیص کارفرما در حق وی بپردازد.</p>';

        $htm .= '<p style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">ماده 9 - شرایط فسخ قرارداد :</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">9-1- </span>مطابق مفاد ماده 21 قانون کار : الف) فوت کارگر، ب) بازنشستگی کارگر، ج) ازکارافتادگی کلی کارگر، د) انقضای مدت در قراردادهای کار با مدت موقت و عدم تجدید صریح یا ضمنی آن، ه) پایان کار در قراردادهایی که مربوط به کار معین است، و) استعفای کارگر، موجب فسخ قرارداد می گردد.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">تبصره 1 : </span>استعفای طرف دوم، منوط به پذیرش طرف اول بوده و همکار متعهد به حضور و انجام وظایف تا استخدام فرد جایگزین می باشد.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">9-2- </span>عدم تعهد نسبت به مفاد ماده 8 این قرارداد نیز حق فسخ یکجانبه را برای طرف اول ایجاد می نماید.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">9-3- </span>تعدیل نیروی انسانی به موجب بند (ح) الحاقی به ماده 21 قانون کار با توجه به کاهش تولید و تغییرات ساختاری در شرایط اقتصادی، اجتماعی و سیاسی مطابق با ماده 9 قانون تنظیم بخشی از مقررات تسهیل و نوسازی کشور</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">9-4- </span>حذف مشاغل و پست های سازمانی اضافی که منجر به ضرورت فسخ این قرارداد گردد.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">9-5- </span>سو استفاده از امکانات شرکت و موقعیت های شغلی توسط همکار</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">9-6- </span>اخلال در امور جاری شرکت، کارکنان و مشتریان و هرگونه برخورد نامناسب که موجب نارضایتی، تشویش و یا اختلال در امور کار گردد.</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;"><span style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">9-7- </span>اعتیاد طرف دوم، حمل، خرید و فروش مواد مخدر و هر نوع مواد روانگردان دیگر و خرید، فروش یا نگهداری ابزار و یا اشیا ممنوعه توسط ایشان، حق فسخ یکجانبه قرارداد را برای طرف اول ایجاد می کند.</p>';

        $htm .= '<p style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">ماده 10 - حل اختلاف :</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;">در صورت بروز هرگونه اختلاف در اجرای مفاد این قرارداد، در مرحله اول، طرفین از طریق سازش، اقدام به رفع اختلاف خواهند کرد و در صورت عدم سازش، مطابق قانون کار عمل خواهد شد.</p>';

        $htm .= '<p style="text-align: right;direction: rtl;font-size: 15px;font-family: BTitr;">ماده 11 - نسخ قرارداد :</p>';
        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;">این قرارداد در 11 ماده و 19 بند و 6 تبصره تنظیم و در محل شرکت فورج فلزات رنگین پارسیان، امضا گردید.</p>';

        $htm .= '<p style="text-align: justify;direction: rtl;font-size: 19px;font-family: BLotus;">اينجـــــــــــانب ' . $res[0]['Fname'] . ' ' . $res[0]['Lname'] . ' به طور كامل كليه مطالب و جزئيات اين قرارداد را مطالعه و از مفاد آن آگاهي حاصل نموده ام و هيچگونه نکته مبهمي وجود نداشته كه بعدها مستند به جهل گردد.</p>';

        $htm .= '<hr>';
      
        $htm .= '<div>';
        $htm .= '<p class="col-4" style="text-align: right;font-size: 19px;font-family: BLotus;float: right;">محل امضا و اثر انگشت همکار</p>';

        $htm .= '<p class="col-4" style="text-align: center;font-size: 19px;font-family: BLotus;float: right;">امضا مدیر منابع انسانی</p>';

        $htm .= '<p class="col-4" style="text-align: left;font-size: 19px;font-family: BLotus;float: right;">امضا قائم مقام مدیر عامل / امضا مدیر عامل</p>';
        $htm .= '</div>';

        $htm .= '</div></div>';

        return $htm;
    }

    public function getPersonnelCostsHTM($pid){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sqlt = "SELECT `type` FROM `personnel` WHERE `RowID`=" . $pid;
        $rst = $db->ArrayQuery($sqlt);

        $htm = '';
        $htm .= '<div class="demoPC" style="margin-top: -60px;">';
        if (intval($rst[0]['type']) == 1) {  // پرسنل عادی
            $sql = "SELECT `dailyWages`,`monthlyWages`,`RightHousing`,`Grocery`,`numberChildren`,`Child_Allowance`,`Shift`,
                    `SalaryInofList`,`SalaryOutofList`,`responsibility_right`,`job_right`,`hardWork`,`financial_allowance`,
                    `grossMonthlySalary`,`WorkerPremium`,`Tax`,`EmployerPremium`,`LeaveCost`,`Festival`,`Reward`,`Years`,`Service`,
                    `AboutOTHoursMonth`,`OvertimeWage`,`Fname`,`Lname`,`leaveStatus`,`overtimeStatus`,`degree_field_study`
                    FROM `personnel` WHERE `RowID`=" . $pid;

            $res = $db->ArrayQuery($sql);
            $Name = $res[0]['Fname'] . ' ' . $res[0]['Lname'];

            $infoNames = array('روز کارکرد', 'حقوق روزانه', 'حقوق کارکرد', 'حق مسکن', 'کمک هزینه اقلام مصرفی خانوار', 'تعداد فرزند', 'عائله مندی', 'نوبت کاری',
                'جمع کل حقوق داخل لیست', 'حقوق خارج لیست', 'حق مسئولیت', 'حق شغل', 'کمک هزینه سرویس', 'کمک هزینه اجاره',
                'جمع کل حقوق ناخالص', 'کسر بیمه', 'کسر مالیات', 'جمع کل حقوق خالص', 'حق بیمه سهم کارفرما', 'مرخصی',
                'عیدی', 'پاداش', 'سنوات', 'هزینه سرویس', 'حدود ساعت اضافه کار در ماه', 'فوق العاده اضافه کار'
            );
            $cnt = count($res[0]) - 5;
            $view = array();
            for ($i = 0; $i < $cnt; $i++) {
                $keyName = key($res[0]);
                $view[] = $res[0]["$keyName"];
                next($res[0]);
            }

            $monthSalaries = ($view[13] - $view[14]) - $view[15];  // جمع کل حقوق خالص

            $this->array_insert($view, 0, 30.5);
            $this->array_insert($view, 17, intval($monthSalaries));

            $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoSalary-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info text-white">';
            $htm .= '<td colspan="2" style="text-align: center;vertical-align: middle;font-family: dubai-Bold;font-size: 20px;width: 40%;">آقای / خانم ' . $Name . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">دریافتی ماهیانه ایشان بدون احتساب عیدی-پاداش-سنوات ( با/بدون اضافه کار-مرخصی )</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">دریافتی ماهیانه ایشان با احتساب عیدی-پاداش-سنوات ( با/بدون اضافه کار-مرخصی )</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">قیمت تمام شده ماهیانه ایشان برای سازمان ( با/بدون اضافه کار-مرخصی )</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $SalariesWithoutEPS = $monthSalaries;
            $SalariesWithEPS = $monthSalaries + (round($view[20] / 12)) + (round($view[21] / 12)) + (round($view[22] / 12));
            $totalSalaries = $view[14] + $view[18] + (round($view[20] / 12)) + (round($view[21] / 12)) + (round($view[22] / 12));

            if ($res[0]['leaveStatus'] == 1) {
                $SalariesWithoutEPS += $view[19];
                $SalariesWithEPS += $view[19];
                $totalSalaries += $view[19];
            }
            if ($res[0]['overtimeStatus'] == 1) {
                $SalariesWithoutEPS += ($view[24] * $view[25]);
                $SalariesWithEPS += ($view[24] * $view[25]);
                $totalSalaries += ($view[24] * $view[25]);
            }
            $annualTotalSalaries = $totalSalaries * 12;

            $cnt1 = count($view);
            for ($i=0;$i<$cnt1;$i++) {
                if ($i == 20) {  // عیدی
                    $e = number_format($view[20] / 12);
                } else {
                    unset($e);
                }
                if ($i == 21) {  // پاداش
                    $p = number_format($view[21] / 12);
                } else {
                    unset($p);
                }
                if ($i == 22) {  // سنوات
                    $s = number_format($view[22] / 12);
                } else {
                    unset($s);
                }
                if ($i == 14) {  // حقوق ناخالص
                    $gslist = number_format($view[14]);
                } else {
                    unset($gslist);
                }
                if ($i == 17) {  // حقوق خالص
                    $sinoflist = number_format($view[17]);
                } else {
                    unset($sinoflist);
                }
                if ($i == 18) {  // سهم کارفرما
                    $EmployerPremium = number_format($view[18]);
                } else {
                    unset($EmployerPremium);
                }
                if ($i == 25 && $res[0]['overtimeStatus'] == 1) {
                    $otCost = number_format($ot * $view[25]);
                    $otCheck = '<input type="checkbox" checked id="overtimeCalclute" onchange="overtimeCalcluteStatus()" class="mr-2" />';
                } elseif ($i == 25 && $res[0]['overtimeStatus'] == 0) {
                    $otCheck = '<input type="checkbox" id="overtimeCalclute" onchange="overtimeCalcluteStatus()" class="mr-2" />';
                }
                if ($i == 19 && $res[0]['leaveStatus'] == 1) {
                    $leaveCost = number_format($view[19]);
                    $leaveCheck = '<input type="checkbox" checked id="leaveCalclute" onchange="leaveCalcluteStatus()" class="mr-2" />';
                } elseif ($i == 19 && $res[0]['leaveStatus'] == 0) {
                    unset($leaveCost);
                    $leaveCheck = '<input type="checkbox" id="leaveCalclute" onchange="leaveCalcluteStatus()" class="mr-2" />';
                } else {
                    $leaveCheck = '';
                    unset($leaveCost);
                }
                if ($i == 5) {
                    $view[$i] = $view[$i] . ' نفر';
                } elseif ($i != 0 && $i != 24) {
                    if ($i == 20 || $i == 21 || $i == 22) {
                        $view[$i] = (intval($view[$i]) == 0 ? 0 : number_format($view[$i] / 12));
                    } else {
                        $view[$i] = (intval($view[$i]) == 0 ? 0 : number_format($view[$i]));
                    }
                } elseif ($i == 24) {
                    $ot = $view[$i];
                    $view[$i] = "<input class='form-control' type='text' style='text-align: center;' value='" . $view[$i] . "' id='AboutOTHoursMonthTxt' onchange='aboutOTHoursMonthChange()' />";
                }
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $infoNames[$i] . $leaveCheck . $otCheck . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $view[$i] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . (isset($otCost) ? $otCost : '') . ' ' . (isset($leaveCost) ? $leaveCost : '') . ' ' . (isset($sinoflist) ? $sinoflist : '') . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . (isset($otCost) ? $otCost : '') . ' ' . (isset($leaveCost) ? $leaveCost : '') . ' ' . (isset($sinoflist) ? $sinoflist : '') . ' ' . (isset($e) ? $e : '') . ' ' . (isset($p) ? $p : '') . ' ' . (isset($s) ? $s : '') . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . (isset($otCost) ? $otCost : '') . ' ' . (isset($leaveCost) ? $leaveCost : '') . ' ' . (isset($EmployerPremium) ? $EmployerPremium : '') . ' ' . (isset($gslist) ? $gslist : '') . ' ' . (isset($e) ? $e : '') . ' ' . (isset($p) ? $p : '') . ' ' . (isset($s) ? $s : '') . '</td>';
                $htm .= '</tr>';
            }

            $htm .= '<tr class="table-warning">';
            $htm .= '<td colspan="2" style="text-align: center;font-family: dubai-Regular;padding: 10px;">جمع کل</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($SalariesWithoutEPS) . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($SalariesWithEPS) . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($totalSalaries) . '</td>';
            $htm .= '</tr>';

            $htm .= '<tr class="table-danger">';
            $htm .= '<td colspan="4" style="text-align: center;font-family: dubai-Regular;padding: 10px;">جمع کل قیمت تمام شده یک ساله</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($annualTotalSalaries) . '<input type="hidden" id="personnelIDHidden" value="" /></td>';
            $htm .= '</tr>';

            $htm .= '</tbody>';
            $htm .= '</table>';
        }else{
            $sql = "SELECT `wage`,`yearsCost`,`dailyWages`,`AboutOTHoursMonth`,`grossMonthlySalary`,`Tax`,`monthlyWages`,`AnnualSalaries`,`Fname`,`Lname`,`overtimeStatus` FROM `personnel` WHERE `RowID`=" . $pid;
            $res = $db->ArrayQuery($sql);
            $Name = $res[0]['Fname'] . ' ' . $res[0]['Lname'];

            $infoNames = array('روز کارکرد', 'ساعت کارکرد موظفی روزانه', 'دستمزد ساعتی', 'حدود ساعت اضافه کار در ماه','جمع کل حقوق ناخالص ماهیانه', 'کسر مالیات', 'جمع کل حقوق خالص ماهیانه', 'جمع کل حقوق خالص سالانه');

            $cnt = count($res[0]) - 3;
            $view = array();
            for ($i = 0; $i < $cnt; $i++) {
                $keyName = key($res[0]);
                $view[] = $res[0]["$keyName"];
                next($res[0]);
            }

            $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoSalary-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info text-white">';
            $htm .= '<td colspan="2" style="text-align: center;vertical-align: middle;font-family: dubai-Bold;font-size: 20px;width: 40%;">آقای / خانم ' . $Name . '</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            for ($i = 0; $i < $cnt; $i++) {
                if ($i == 1) {
                    $view[$i] = $view[$i] . ' ساعت';
                } elseif ($i != 0 && $i != 3) {
                    $view[$i] = (intval($view[$i]) == 0 ? 0 : number_format($view[$i]));
                }

                if ($i == 3 && $res[0]['overtimeStatus'] == 1) {
                    $view[$i] = "<input class='form-control' type='text' style='text-align: center;' value='" . $view[$i] . "' id='AboutOTHoursMonthTxt' onchange='aboutOTHoursMonthChange()' />";
                    $otCheck = '<input type="checkbox" checked id="overtimeCalclute" onchange="overtimeCalcluteStatus()" class="mr-2" />';
                }elseif ($i == 3 && $res[0]['overtimeStatus'] == 0) {
                    $view[$i] = "<input class='form-control' type='text' style='text-align: center;' value='" . $view[$i] . "' id='AboutOTHoursMonthTxt' onchange='aboutOTHoursMonthChange()' />";
                    $otCheck = '<input type="checkbox" id="overtimeCalclute" onchange="overtimeCalcluteStatus()" class="mr-2" />';
                }else{
                    $otCheck = '';
                }

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $infoNames[$i] . $otCheck . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $view[$i] . '<input type="hidden" id="personnelIDHidden" value="" /></td>';
                $htm .= '</tr>';
            }
            $htm .= '</tbody>';
            $htm .= '</table>';
        }
        $htm .= '</div>';
        return $htm;
    }

    public function createAbility($ability, $group, $sgroup){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $parentID = (intval($group) == -1 || (intval($group) > 0 && intval($sgroup) == 0) ? $group : $sgroup);
        $db = new DBi();
        $sql = "INSERT INTO `abilities` (`Ability`,`parentID`) VALUES ('{$ability}',{$parentID})";
        $res = $db->Query($sql);
        $id = $db->InsertrdID();
        if (intval($res) > 0) {
            return $id;
        } else {
            return false;
        }
    }

    public function getHeadGroup(){
        $db = new DBi();
        $sql = "SELECT `RowID`,`Ability` FROM `abilities` WHERE `parentID`=-1";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function getAbilities($gid){
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `abilities` WHERE `parentID`={$gid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $rids = array();
        for ($i = 0; $i < $cnt; $i++) {
            $query = "SELECT `RowID` FROM `abilities` WHERE `parentID`={$res[$i]['RowID']}";
            $rst = $db->ArrayQuery($query);
            if (count($rst) > 0) {
                $cnt1 = count($rst);
                for ($j = 0; $j < $cnt1; $j++) {
                    $rids[] = $rst[$j]['RowID'];
                }
            } else {
                $rids[] = $res[$i]['RowID'];
            }
        }
        $rids = implode(',', $rids);
        $sql = "SELECT `RowID`,`Ability` FROM `abilities` WHERE `RowID` IN ({$rids})";
        $result = $db->ArrayQuery($sql);

        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getAbilities1($gid){
        $db = new DBi();
        $sql = "SELECT `RowID`,`Ability` FROM `abilities` WHERE `parentID`={$gid}";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0) {
            return $res;
        } else {
            return false;
        }
    }

    public function overtimeCalcluteStatusChange($OvertimeStatus, $pid){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $query = "SELECT `type`,`wage`,`yearsCost`,`dailyWages`,`AboutOTHoursMonth` FROM `personnel` WHERE `RowID`={$pid}";
        $rst = $db->ArrayQuery($query);

        if ($rst[0]['type'] == 0){
            if ($OvertimeStatus == 0){
                $grossMonthlySalary = (($rst[0]['wage'] * $rst[0]['yearsCost'])) * $rst[0]['dailyWages'];
            }else{
                $grossMonthlySalary = (($rst[0]['wage'] * $rst[0]['yearsCost']) + $rst[0]['AboutOTHoursMonth']) * $rst[0]['dailyWages'];
            }
            $tax = $grossMonthlySalary * 0.1;
            $monthlyWages = $grossMonthlySalary - $tax;
            $AnnualSalaries = $monthlyWages * 12;
            $sql1 = "UPDATE `personnel` SET `grossMonthlySalary`={$grossMonthlySalary},`monthlyWages`={$monthlyWages},`AnnualSalaries`={$AnnualSalaries},`TotalCosts`={$AnnualSalaries},`Tax`={$tax} WHERE `RowID`={$pid}";
            $db->Query($sql1);
        }

        $sql = "UPDATE `personnel` SET `overtimeStatus`={$OvertimeStatus} WHERE `RowID`={$pid}";
        $db->Query($sql);
        $af = $db->AffectedRows();
        $af = (intval($af) == -1 ? 0 : 1);
        if (intval($af) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function leaveCalcluteStatusChange($LeaveStatus, $pid){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `personnel` SET `leaveStatus`={$LeaveStatus} WHERE `RowID`={$pid}";
        $db->Query($sql);
        $af = $db->AffectedRows();
        $af = (intval($af) == -1 ? 0 : 1);
        if (intval($af) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function aboutOTHoursMonthChange($otHour, $pid){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $query = "SELECT `type`,`wage`,`yearsCost`,`dailyWages`,`overtimeStatus` FROM `personnel` WHERE `RowID`={$pid}";
        $rst = $db->ArrayQuery($query);

        if ($rst[0]['type'] == 0){
            if ($rst[0]['overtimeStatus'] == 0){
                $grossMonthlySalary = (($rst[0]['wage'] * $rst[0]['yearsCost'])) * $rst[0]['dailyWages'];
            }else{
                $grossMonthlySalary = (($rst[0]['wage'] * $rst[0]['yearsCost']) + $otHour) * $rst[0]['dailyWages'];
            }
            $tax = $grossMonthlySalary * 0.1;
            $monthlyWages = $grossMonthlySalary - $tax;
            $AnnualSalaries = $monthlyWages * 12;
            $sql1 = "UPDATE `personnel` SET `grossMonthlySalary`={$grossMonthlySalary},`monthlyWages`={$monthlyWages},`AnnualSalaries`={$AnnualSalaries},`TotalCosts`={$AnnualSalaries},`Tax`={$tax} WHERE `RowID`={$pid}";
            $db->Query($sql1);
        }

        $sql = "UPDATE `personnel` SET `AboutOTHoursMonth`={$otHour} WHERE `RowID`={$pid}";
        $db->Query($sql);
        $af = $db->AffectedRows();
        $af = (intval($af) == -1 ? 0 : 1);
        if (intval($af) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function salaryGroupInfo(){
        $db = new DBi();
        $sql = "SELECT * FROM `salary_group`";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0) {
            $cnt = count($res);
            for ($i = 0; $i < $cnt; $i++) {
                $res[$i]['minimumSalary'] = number_format($res[$i]['minimumSalary']);
                $res[$i]['maximumSalary'] = number_format($res[$i]['maximumSalary']);
            }
            return $res;
        } else {
            return false;
        }
    }

    public function editPersonnelSalaryGroup($g1, $min1, $max1, $ac1, $g2, $min2, $max2, $ac2, $g3, $min3, $max3, $ac3, $g4, $min4, $max4, $ac4, $g5, $min5, $max5, $ac5, $g6, $min6, $max6, $ac6, $g7, $min7, $max7, $ac7, $g8, $min8, $max8, $ac8, $g9, $min9, $max9, $ac9, $g10, $min10, $max10, $ac10){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $flag = true;
        $values = array($g1, $min1, $max1, $ac1, $g2, $min2, $max2, $ac2, $g3, $min3, $max3, $ac3, $g4, $min4, $max4, $ac4, $g5, $min5, $max5, $ac5, $g6, $min6, $max6, $ac6, $g7, $min7, $max7, $ac7, $g8, $min8, $max8, $ac8, $g9, $min9, $max9, $ac9, $g10, $min10, $max10, $ac10);
        $x = 0;
        for ($i = 0; $i < 10; $i++) {
            $j = $i + 1;
            $sql = "UPDATE `salary_group` SET `groupName`='{$values[$x]}',`minimumSalary`={$values[$x+1]},`maximumSalary`={$values[$x+2]},`achievementConditions`='{$values[$x+3]}' WHERE `RowID`={$j}";
            $db->Query($sql);
            $af = $db->AffectedRows();
            if (intval($af) == -1) {
                $flag = false;
            }
            $x += 4;
        }
        if ($flag) {
            return true;
        } else {
            return false;
        }
    }

    public function getGroupSalaryInfo($group){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `minimumSalary`,`maximumSalary`,`achievementConditions` FROM `salary_group` WHERE `RowID`={$group}";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0) {
            return $res;
        } else {
            return false;
        }
    }

    public function getPersonnelSalaryGroupList($sgid){
        $db = new DBi();
        $sql = "SELECT `RowID`,`Fname`,`Lname` FROM `personnel` WHERE `sgid`={$sgid} AND `isEnable`=1";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0) {
            $cnt = count($res);
            for ($i = 0; $i < $cnt; $i++) {
                $res[$i]['Fname'] = $res[$i]['Fname'] . ' ' . $res[$i]['Lname'];
            }
            return $res;
        } else {
            return false;
        }
    }

    public function comparePersonnelSalaryGroupHTM($sGroup, $personnel, $method){
        $acm = new acm();
        if (!$acm->hasAccess('salaryBenefitsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $method = explode(',',$method);
        $w = array();
        $w[] = '`sgid`=' . $sGroup . ' ';
        if (strlen(trim($personnel)) > 0) {
            $w[] = '`RowID` IN (' . $personnel . ') ';
        }
        $sql = "SELECT `RowID`,`grossMonthlySalary`,`WorkerPremium`,`Tax`,`LeaveCost`,`AboutOTHoursMonth`,`OvertimeWage`,`EmployerPremium`,`Years`,`Festival`,`Reward`,`SalaryOutofList`,`Fname`,`Lname` FROM `personnel` ";
        if (count($w) > 0) {
            $where = implode(" AND ", $w);
            $sql .= " WHERE " . $where;
        }
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0) {
            $cnt = count($res);
            $htm = '';
            $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoSalary-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info text-white">';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">نام و نام خانوادگی</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 18%;">دریافتی ماهیانه بدون احتساب عیدی-پاداش-سنوات</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 18%;">دریافتی ماهیانه با احتساب عیدی-پاداش-سنوات</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 18%;">بهای تمام شده ماهیانه برای سازمان</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 18%;">خارج لیست</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 8%;">سایر اطلاعات</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';
            $costs = array();
            for ($i = 0; $i < $cnt; $i++) {
                $SalariesWithoutEPS = (($res[$i]['grossMonthlySalary'] - $res[$i]['WorkerPremium']) - $res[$i]['Tax']) + $res[$i]['LeaveCost'] + ($res[$i]['AboutOTHoursMonth'] * $res[$i]['OvertimeWage']);
                $SalariesWithEPS = $SalariesWithoutEPS + ($res[$i]['Years'] / 12) + ($res[$i]['Festival'] / 12) + ($res[$i]['Reward'] / 12);
                $totalSalaries = $res[$i]['grossMonthlySalary'] + $res[$i]['EmployerPremium'] + $res[$i]['LeaveCost'] + ($res[$i]['AboutOTHoursMonth'] * $res[$i]['OvertimeWage']) + ($res[$i]['Years'] / 12) + ($res[$i]['Festival'] / 12) + ($res[$i]['Reward'] / 12);
                $costs[$i]['RowID'] = $res[$i]['RowID'];
                $costs[$i]['name'] = $res[$i]['Fname'] . ' ' . $res[$i]['Lname'];
                $costs[$i]['swoeps'] = (in_array(1,$method) ? $SalariesWithoutEPS : '');
                $costs[$i]['sweps'] = (in_array(2,$method) ? $SalariesWithEPS : '');
                $costs[$i]['ts'] = (in_array(3,$method) ? $totalSalaries : '');
                $costs[$i]['out'] = (in_array(4,$method) ? $res[$i]['SalaryOutofList'] : '');
            }
            if (in_array(1,$method)){
                $costs = $this->array_sort_by_column($costs,'swoeps');
            }elseif (!in_array(1,$method) && in_array(2,$method)){
                $costs = $this->array_sort_by_column($costs,'sweps');
            }elseif (!in_array(1,$method) && !in_array(2,$method) && in_array(3,$method)){
                $costs = $this->array_sort_by_column($costs,'ts');
            }else{
                $costs = $this->array_sort_by_column($costs,'out');
            }
            for ($j=0;$j<$cnt;$j++){
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $costs[$j]['name'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($costs[$j]['swoeps']) . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($costs[$j]['sweps']) . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($costs[$j]['ts']) . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($costs[$j]['out']) . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info btn-sm" onclick="ShowOtherInfoPersonnelCost('.$costs[$j]['RowID'].')"><i class="fa fa-tv"></i></button></td>';
                $htm .= '</tr>';
            }
            $htm .= '</tbody>';
            $htm .= '</table>';
        }
        return $htm;
    }

    public function getExcelDeficitDocuments(){
        $acm = new acm();
        if(!$acm->hasAccess('salaryBenefitsManage') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `RowID`,`Fname`,`Lname` FROM `personnel` WHERE `isEnable`=1";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            $cnt = count($res);
            for ($i=0;$i<$cnt;$i++){
                $sql1 = "SELECT `status` FROM `personnel_doc` WHERE `uid`={$res[$i]['RowID']}";
                $rst = $db->ArrayQuery($sql1);
                if (count($rst) > 0){
                    $finalRes[$i]['name'] = $res[$i]['Fname'].' '.$res[$i]['Lname'];
                    $finalRes[$i]['porsesh'] = ($rst[0]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['taahod'] = ($rst[1]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['shcardm'] = ($rst[2]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['shcardt'] = ($rst[3]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['daftar'] = ($rst[4]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['cardpkh'] = ($rst[5]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['sbime'] = ($rst[6]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['amadrakt'] = ($rst[7]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['aks'] = ($rst[8]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['govmaharat'] = ($rst[9]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['tsavabeghkh'] = ($rst[10]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['soopishine'] = ($rst[11]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['naccount'] = ($rst[12]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['check'] = ($rst[13]['status'] == 0 ? '*' : '');
                    $finalRes[$i]['azmayesh'] = ($rst[14]['status'] == 0 ? '*' : '');
                }else{
                    $finalRes[$i]['name'] = $res[$i]['Fname'].' '.$res[$i]['Lname'];
                    $finalRes[$i]['porsesh'] = '*';
                    $finalRes[$i]['taahod'] = '*';
                    $finalRes[$i]['shcardm'] = '*';
                    $finalRes[$i]['shcardt'] = '*';
                    $finalRes[$i]['daftar'] = '*';
                    $finalRes[$i]['cardpkh'] = '*';
                    $finalRes[$i]['sbime'] = '*';
                    $finalRes[$i]['amadrakt'] = '*';
                    $finalRes[$i]['aks'] = '*';
                    $finalRes[$i]['govmaharat'] = '*';
                    $finalRes[$i]['tsavabeghkh'] = '*';
                    $finalRes[$i]['soopishine'] = '*';
                    $finalRes[$i]['naccount'] = '*';
                    $finalRes[$i]['check'] = '*';
                    $finalRes[$i]['azmayesh'] = '*';
                }
            }
            return $finalRes;
        }else{
            $res = "امکان گرفتن خروجی وجود ندارد";
            $out = "false";
            response($res,$out);
            exit;
        }
    }

    public function getPersonnelExcel($Pname,$Pfamily,$Pcode,$Punit,$RsDate,$ReDate,$TsAmount,$TeAmount,$Pname1,$Pfamily1,$Pcode1,$Punit1,$RsDate1,$ReDate1,$TsAmount1,$TeAmount1,$ability,$status){
        $acm = new acm();
        if(!$acm->hasAccess('salaryBenefitsManage') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if (strlen(trim($Pname)) > 0) {
            $w[] = '`Fname`="' . $Pname . '" ';
        }
        if (strlen(trim($Pfamily)) > 0) {
            $w[] = '`Lname`="' . $Pfamily . '" ';
        }
        if (strlen(trim($Pcode)) > 0) {
            $w[] = '`PersonnelCode`=' . $Pcode . ' ';
        }
        if (intval($Punit) > 0) {
            $w[] = '`Unit_id`=' . $Punit . ' ';
        }
        if (strlen(trim($RsDate)) > 0) {
            $RsDate = $ut->jal_to_greg($RsDate);
            $w[] = '`RecruitmentDate`>="' . $RsDate . '" ';
        }
        if (strlen(trim($ReDate)) > 0) {
            $ReDate = $ut->jal_to_greg($ReDate);
            $w[] = '`RecruitmentDate`<="' . $ReDate . '" ';
        }
        if (strlen(trim($TsAmount)) > 0) {
            $w[] = '`TotalCosts`>=' . $TsAmount . ' ';
        }
        if (strlen(trim($TeAmount)) > 0) {
            $w[] = '`TotalCosts`<=' . $TeAmount . ' ';
        }

        if (strlen(trim($Pname1)) > 0) {
            $w[] = '`Fname`="' . $Pname1 . '" ';
        }
        if (strlen(trim($Pfamily1)) > 0) {
            $w[] = '`Lname`="' . $Pfamily1 . '" ';
        }
        if (strlen(trim($Pcode1)) > 0) {
            $w[] = '`PersonnelCode`=' . $Pcode1 . ' ';
        }
        if (intval($Punit1) > 0) {
            $w[] = '`Unit_id`=' . $Punit1 . ' ';
        }
        if (strlen(trim($RsDate1)) > 0) {
            $RsDate1 = $ut->jal_to_greg($RsDate1);
            $w[] = '`RecruitmentDate`>="' . $RsDate1 . '" ';
        }
        if (strlen(trim($ReDate1)) > 0) {
            $ReDate1 = $ut->jal_to_greg($ReDate1);
            $w[] = '`RecruitmentDate`<="' . $ReDate1 . '" ';
        }
        if (strlen(trim($TsAmount1)) > 0) {
            $w[] = '`TotalCosts`>=' . $TsAmount1 . ' ';
        }
        if (strlen(trim($TeAmount1)) > 0) {
            $w[] = '`TotalCosts`<=' . $TeAmount1 . ' ';
        }
        if(intval($status) >= 0){
            $w[] = '`isEnable` ='.$status.' ';
        }

        $sql = "SELECT `personnel`.*,`Uname` FROM `personnel` INNER JOIN `official_productive_units` ON (`official_productive_units`.`RowID`=`personnel`.`Unit_id`) ";
        if (intval($ability) > 0) {
            $sql .= "LEFT JOIN `personnel_ability` ON (`personnel`.`RowID`=`personnel_ability`.`pid`)";
            $w[] = '`aid`=' . $ability . ' ';
        }
        if (count($w)) {
            $where = implode(" AND ", $w);
            $sql .= " WHERE " . $where;
        }
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        if(count($res) > 0){
            for($i=0;$i<$listCount;$i++){
                $res[$i]['BeginDateContract'] = (strtotime($res[$i]['BeginDateContract']) > 0 ? $ut->greg_to_jal($res[$i]['BeginDateContract']) : '');
                $res[$i]['EndDateContract'] = (strtotime($res[$i]['EndDateContract']) > 0 ? $ut->greg_to_jal($res[$i]['EndDateContract']) : '');
                $res[$i]['RecruitmentDate'] = (strtotime($res[$i]['RecruitmentDate']) > 0 ? $ut->greg_to_jal($res[$i]['RecruitmentDate']) : '');
                $res[$i]['Marital_Status'] = (intval($res[$i]['Marital_Status']) == 0 ? 'مجرد' : 'متاهل');
                $res[$i]['Fname'] = $res[$i]['Fname'].' '.$res[$i]['Lname'];
            }
            return $res;
        }else{
            $res = "امکان گرفتن خروجی وجود ندارد";
            $out = "false";
            response($res,$out);
            exit;
        }
    }

    public function getPersonnelFinalPriceExcel(){
        $acm = new acm();
        if(!$acm->hasAccess('salaryBenefitsManage') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT * FROM `personnel` WHERE `isEnable`=1";
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        if(count($res) > 0){
            for($i=0;$i<$listCount;$i++){
                $res[$i]['Fname'] = $res[$i]['Fname'].' '.$res[$i]['Lname'];
                $res[$i]['Unit_id'] = '30.5';
                $res[$i]['wage'] = $res[$i]['grossMonthlySalary']-($res[$i]['WorkerPremium'] + $res[$i]['Tax']);
                $res[$i]['Festival'] = round($res[$i]['Festival']/12);
                $res[$i]['Reward'] = round($res[$i]['Reward']/12);
                $res[$i]['Years'] = round($res[$i]['Years']/12);
                $res[$i]['Service'] = $res[$i]['Service']/12;
                $res[$i]['yearsCost'] = $res[$i]['grossMonthlySalary'] + $res[$i]['Festival'] + $res[$i]['Reward'] + $res[$i]['Years'] + $res[$i]['EmployerPremium'];
                if (intval($res[$i]['leaveStatus']) == 1){
                    $res[$i]['yearsCost'] += $res[$i]['LeaveCost'];
                }
                if (intval($res[$i]['overtimeStatus']) == 1){
                    $res[$i]['yearsCost'] += ($res[$i]['OvertimeWage'] * $res[$i]['AboutOTHoursMonth']);
                }
                $res[$i]['AnnualSalaries'] = $res[$i]['yearsCost'] * 12;
            }
            return $res;
        }else{
            $res = "امکان گرفتن خروجی وجود ندارد";
            $out = "false";
            response($res,$out);
            exit;
        }
    }

    public function getPersonnelAbilityExcel($Pname,$Pfamily,$Pcode,$Punit,$RsDate,$ReDate,$TsAmount,$TeAmount,$Pname1,$Pfamily1,$Pcode1,$Punit1,$RsDate1,$ReDate1,$TsAmount1,$TeAmount1,$ability,$status){
        $acm = new acm();
        if(!$acm->hasAccess('salaryBenefitsManage') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if (strlen(trim($Pname)) > 0) {
            $w[] = '`Fname`="' . $Pname . '" ';
        }
        if (strlen(trim($Pfamily)) > 0) {
            $w[] = '`Lname`="' . $Pfamily . '" ';
        }
        if (strlen(trim($Pcode)) > 0) {
            $w[] = '`PersonnelCode`=' . $Pcode . ' ';
        }
        if (intval($Punit) > 0) {
            $w[] = '`Unit_id`=' . $Punit . ' ';
        }
        if (strlen(trim($RsDate)) > 0) {
            $RsDate = $ut->jal_to_greg($RsDate);
            $w[] = '`RecruitmentDate`>="' . $RsDate . '" ';
        }
        if (strlen(trim($ReDate)) > 0) {
            $ReDate = $ut->jal_to_greg($ReDate);
            $w[] = '`RecruitmentDate`<="' . $ReDate . '" ';
        }
        if (strlen(trim($TsAmount)) > 0) {
            $w[] = '`TotalCosts`>=' . $TsAmount . ' ';
        }
        if (strlen(trim($TeAmount)) > 0) {
            $w[] = '`TotalCosts`<=' . $TeAmount . ' ';
        }

        if (strlen(trim($Pname1)) > 0) {
            $w[] = '`Fname`="' . $Pname1 . '" ';
        }
        if (strlen(trim($Pfamily1)) > 0) {
            $w[] = '`Lname`="' . $Pfamily1 . '" ';
        }
        if (strlen(trim($Pcode1)) > 0) {
            $w[] = '`PersonnelCode`=' . $Pcode1 . ' ';
        }
        if (intval($Punit1) > 0) {
            $w[] = '`Unit_id`=' . $Punit1 . ' ';
        }
        if (strlen(trim($RsDate1)) > 0) {
            $RsDate1 = $ut->jal_to_greg($RsDate1);
            $w[] = '`RecruitmentDate`>="' . $RsDate1 . '" ';
        }
        if (strlen(trim($ReDate1)) > 0) {
            $ReDate1 = $ut->jal_to_greg($ReDate1);
            $w[] = '`RecruitmentDate`<="' . $ReDate1 . '" ';
        }
        if (strlen(trim($TsAmount1)) > 0) {
            $w[] = '`TotalCosts`>=' . $TsAmount1 . ' ';
        }
        if (strlen(trim($TeAmount1)) > 0) {
            $w[] = '`TotalCosts`<=' . $TeAmount1 . ' ';
        }
        if(intval($status) >= 0){
            $w[] = '`isEnable` ='.$status.' ';
        }
        if (intval($ability) > 0) {
            $w[] = '`aid`=' . $ability . ' ';
        }

        $sql = "SELECT `personnel`.`RowID`,`Fname`,`PersonnelCode`,`Lname`,`Ability`,`proficiency`,`passedCourse` FROM `personnel` 
                LEFT JOIN `personnel_ability` ON (`personnel`.`RowID`=`personnel_ability`.`pid`)
                INNER JOIN `abilities` ON (`personnel_ability`.`aid`=`abilities`.`RowID`)";
        if (count($w)) {
            $where = implode(" AND ", $w);
            $sql .= " WHERE " . $where;
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

    private function array_sort_by_column(&$arr, $col, $dir=SORT_DESC) {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
        return $arr;
    }

    public function getAllPersonnel(){
        $db = new DBi();
        $sql = "SELECT `RowID`,`Fname`,`Lname` FROM `personnel` where isEnable =1  ORDER BY `Fname` ASC";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

}