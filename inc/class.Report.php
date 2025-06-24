<?php
/**
 * Created by PhpStorm.
 * User: Majid
 * Date: 11/09/2019
 * Time: 8:33 AM
 */

class Report{

    private $errorPriceDakheli = 'قیمت خرید داخلی وارد نشده است !';
    private $errorPriceKhareji = 'قیمت خرید خارجی وارد نشده است !';
    private $errorPriceKGM = 'قیمت خرید قطعه ماشین کاری وارد نشده است !';
    private $errorPriceKGR = 'قیمت خرید قطعه ریخته گری وارد نشده است !';
    private $errorPriceDate = 'تاریخ قیمت قطعه منقضی شده است !';

    public function __construct(){
        // do nothing
    }

    //+++++++++++++++++ گزارشات نرخ ارز +++++++++++++++++++

    public function getCurrencyReportManageHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('currencyReportManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $cur = new Currency();
        $res = $cur->getAllCurrency();
        $countRes = count($res);
        $pagename = "گزارشات نرخ ارز";
        $pageIcon = "fas fa-chart-area";
        $contentId = "currencyReportManageBody";

        $c = 0;
        $bottons= array();

        $headerSearch = array();
        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "currencyReportManageSdateSearch";
        $headerSearch[$c]['title'] = "از تاریخ";
        $headerSearch[$c]['placeholder'] = "از تاریخ";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "currencyReportManageEdateSearch";
        $headerSearch[$c]['title'] = "تا تاریخ";
        $headerSearch[$c]['placeholder'] = "تا تاریخ";
        $c++;

        $headerSearch[$c]['type'] = "select";
        $headerSearch[$c]['id'] = "currencyReportManageCunameSearch";
        $headerSearch[$c]['title'] = "نوع ارز";
        $headerSearch[$c]['width'] = "100px";
        $headerSearch[$c]['options'] = array();
        $headerSearch[$c]['options'][0]["title"] = '--------';
        $headerSearch[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$countRes;$i++){
            $headerSearch[$c]['options'][$i+1]["title"] = $res[$i]['currencyName'];
            $headerSearch[$c]['options'][$i+1]["value"] = $res[$i]['RowID'];
        }
        $c++;

        $headerSearch[$c]['type'] = "btn";
        $headerSearch[$c]['title'] = "جستجو&nbsp;&nbsp;<i class='fas fa-search'></i>";
        $headerSearch[$c]['jsf'] = "showCurrencyReportManageList";

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        return $htm;
    }

    public function getCurrencyReportList($cid,$sDate,$eDate,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('currencyReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(intval($cid) > 0){
            $w[] = '`currency_id`= '.$cid.' ';
        }
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`changeDate`>="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`changeDate`<="'.$eDate.'" ';
        }
        $sql = "SELECT `currencyName`,`fname`,`lname`,`changeDate`,`currency_Rate`,`exchange_Rate` 
                FROM `backup_currency` INNER JOIN `currency` ON (`currency`.`RowID`=`backup_currency`.`currency_id`)
                                       INNER JOIN `users` ON (`backup_currency`.`uid`=`users`.`RowID`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `backup_currency`.`RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['currencyName'] = $res[$y]['currencyName'];
            $finalRes[$y]['changeDate'] = $ut->greg_to_jal($res[$y]['changeDate']);
            $finalRes[$y]['currency_Rate'] = number_format($res[$y]['currency_Rate']).' ریال';
            $finalRes[$y]['exchange_Rate'] = (floatval($res[$y]['exchange_Rate']) == 0 ? '--------' : $res[$y]['exchange_Rate'].' دلار');
            $finalRes[$y]['name'] = $res[$y]['fname'].' '.$res[$y]['lname'];
        }
        return $finalRes;
    }

    public function getCurrencyReportListCountRows($cid,$sDate,$eDate){
        $acm = new acm();
        if(!$acm->hasAccess('currencyReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(intval($cid) > 0){
            $w[] = '`currency_id`= '.$cid.' ';
        }
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`changeDate`>="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`changeDate`<="'.$eDate.'" ';
        }
        $sql = "SELECT `currencyName`,`changeDate`,`lname` 
                FROM `backup_currency` 
                INNER JOIN `currency` ON (`currency`.`RowID`=`backup_currency`.`currency_id`)
                INNER JOIN `users` ON (`backup_currency`.`uid`=`users`.`RowID`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    //+++++++++++++++++ گزارش تغییرات قطعه +++++++++++++++++++

    public function getPieceChangeReportManageHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('pieceChangeReportManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $pagename = "گزارش تغییرات قطعه";
        $pageIcon = "fas fa-chart-bar";
        $contentId = "pieceChangeReportManageBody";

        $c = 0;
        $bottons= array();

        $headerSearch = array();
        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "pieceChangeReportManageSdateSearch";
        $headerSearch[$c]['title'] = "از تاریخ";
        $headerSearch[$c]['placeholder'] = "از تاریخ";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "pieceChangeReportManageEdateSearch";
        $headerSearch[$c]['title'] = "تا تاریخ";
        $headerSearch[$c]['placeholder'] = "تا تاریخ";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "250px";
        $headerSearch[$c]['id'] = "pieceChangeReportManagePNameSearch";
        $headerSearch[$c]['title'] = "نام قطعه";
        $headerSearch[$c]['placeholder'] = "نام قطعه";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "pieceChangeReportManagePCodeSearch";
        $headerSearch[$c]['title'] = "کد قطعه";
        $headerSearch[$c]['placeholder'] = "کد قطعه";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "pieceChangeReportManageFNameSearch";
        $headerSearch[$c]['title'] = "نام فیلد مورد نظر";
        $headerSearch[$c]['placeholder'] = "نام فیلد مورد نظر";
        $c++;

        $headerSearch[$c]['type'] = "btn";
        $headerSearch[$c]['title'] = "جستجو&nbsp;&nbsp;<i class='fas fa-search'></i>";
        $headerSearch[$c]['jsf'] = "showPieceChangeReportManageList";

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        return $htm;
    }

    public function getPieceChangeReportList($pName,$fName,$pCode,$sDate,$eDate,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('pieceChangeReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        if(strlen(trim($pCode)) > 0){
            $w[] = '`pCode` LIKE "%'.$pCode.'%" ';
        }
        if(strlen(trim($fName)) > 0){
            $w[] = '`fieldName_Fa`="'.$fName.'" ';
        }
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`changeDate`>="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`changeDate`<="'.$eDate.'" ';
        }
        $sql = "SELECT `pName`,`pCode`,`fieldName_Fa`,`fieldName`,`currentValue`,`previousValue`,`changeDate` FROM `piece` INNER JOIN `backup_piece` ON (`piece`.`RowID`=`backup_piece`.`pid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `backup_piece`.`RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            switch ($res[$y]['fieldName']){
                case 'FixHow_supply':
                    switch ($res[$y]['currentValue']){
                        case 0:
                            $finalRes[$y]['currentValue'] = 'راکد';
                            break;
                        case 1:
                            $finalRes[$y]['currentValue'] = 'وارداتی';
                            break;
                        case 2:
                            $finalRes[$y]['currentValue'] = 'خرید داخلی';
                            break;
                        case 3:
                            $finalRes[$y]['currentValue'] = 'خرید قطعه ماشینکاری';
                            break;
                        case 4:
                            $finalRes[$y]['currentValue'] = 'خرید قطعه ریخته گری';
                            break;
                        case 5:
                            $finalRes[$y]['currentValue'] = 'تولید ریخته گری';
                            break;
                        case 6:
                            $finalRes[$y]['currentValue'] = 'تولید ماشینکاری';
                            break;
                        case 7:
                            $finalRes[$y]['currentValue'] = 'فورج';
                            break;
                        case 8:
                            $finalRes[$y]['currentValue'] = 'تزریق پلاستیک';
                            break;
                        case 9:
                            $finalRes[$y]['currentValue'] = 'لوله';
                            break;
                        case 10:
                            $finalRes[$y]['currentValue'] = 'شیلنگ';
                            break;
                        case 11:
                            $finalRes[$y]['currentValue'] = 'برش لیزر';
                            break;
                        case 12:
                            $finalRes[$y]['currentValue'] = 'کلکتور';
                            break;
                        case 13:
                            $finalRes[$y]['currentValue'] = 'منسوخ';
                            break;
                        case 14:
                            $finalRes[$y]['currentValue'] = 'قطعه مونتاژی';
                            break;
                    }
                    switch ($res[$y]['previousValue']){
                        case 0:
                            $finalRes[$y]['previousValue'] = 'راکد';
                            break;
                        case 1:
                            $finalRes[$y]['previousValue'] = 'وارداتی';
                            break;
                        case 2:
                            $finalRes[$y]['previousValue'] = 'خرید داخلی';
                            break;
                        case 3:
                            $finalRes[$y]['previousValue'] = 'خرید قطعه ماشینکاری';
                            break;
                        case 4:
                            $finalRes[$y]['previousValue'] = 'خرید قطعه ریخته گری';
                            break;
                        case 5:
                            $finalRes[$y]['previousValue'] = 'تولید ریخته گری';
                            break;
                        case 6:
                            $finalRes[$y]['previousValue'] = 'تولید ماشینکاری';
                            break;
                        case 7:
                            $finalRes[$y]['previousValue'] = 'فورج';
                            break;
                        case 8:
                            $finalRes[$y]['previousValue'] = 'تزریق پلاستیک';
                            break;
                        case 9:
                            $finalRes[$y]['previousValue'] = 'لوله';
                            break;
                        case 10:
                            $finalRes[$y]['previousValue'] = 'شیلنگ';
                            break;
                        case 11:
                            $finalRes[$y]['previousValue'] = 'برش لیزر';
                            break;
                        case 12:
                            $finalRes[$y]['previousValue'] = 'کلکتور';
                            break;
                        case 13:
                            $finalRes[$y]['previousValue'] = 'منسوخ';
                            break;
                        case 14:
                            $finalRes[$y]['previousValue'] = 'قطعه مونتاژی';
                            break;
                    }
                    break;
                case 'TypeBullion':
                    switch ($res[$y]['currentValue']){
                        case 0:
                            $finalRes[$y]['currentValue'] = 'قطر زیر 14 mm';
                            break;
                        case 1:
                            $finalRes[$y]['currentValue'] = 'قطر بالای 14 mm';
                            break;
                        case 2:
                            $finalRes[$y]['currentValue'] = 'کلکتور';
                            break;
                    }
                    switch ($res[$y]['previousValue']){
                        case 0:
                            $finalRes[$y]['previousValue'] = 'قطر زیر 14 mm';
                            break;
                        case 1:
                            $finalRes[$y]['previousValue'] = 'قطر بالای 14 mm';
                            break;
                        case 2:
                            $finalRes[$y]['previousValue'] = 'کلکتور';
                            break;
                    }
                    break;
                default:
                    $finalRes[$y]['currentValue'] = $res[$y]['currentValue'];
                    $finalRes[$y]['previousValue'] = $res[$y]['previousValue'];
            }
            $finalRes[$y]['pName'] = $res[$y]['pName'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['fieldName_Fa'] = $res[$y]['fieldName_Fa'];
            $finalRes[$y]['changeDate'] = $ut->greg_to_jal($res[$y]['changeDate']);
        }
        return $finalRes;
    }

    public function getPieceChangeReportListCountRows($pName,$fName,$pCode,$sDate,$eDate){
        $acm = new acm();
        if(!$acm->hasAccess('pieceChangeReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName`="'.$pName.'" ';
        }
        if(strlen(trim($pCode)) > 0){
            $w[] = '`pCode`="'.$pCode.'" ';
        }
        if(strlen(trim($fName)) > 0){
            $w[] = '`fieldName_Fa`="'.$fName.'" ';
        }
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`changeDate`>="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`changeDate`<="'.$eDate.'" ';
        }
        $sql = "SELECT `pName`,`fieldName_Fa` FROM `piece` INNER JOIN `backup_piece` ON (`piece`.`RowID`=`backup_piece`.`pid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getFieldName(){
        $db = new DBi();
        $sql = "SELECT `FaName`,`RowID` FROM `en_to_fa` WHERE `type`=0";
        $res = $db->ArrayQuery($sql);
        if(count($res)>0){
            return $res;
        }else{
            return false;
        }
    }

    //+++++++++++++++++ گزارش تغییرات محصول +++++++++++++++++++

    public function getGoodChangeReportManageHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('goodChangeReportManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $pagename = "گزارش تغییرات محصول";
        $pageIcon = "fas fa-chart-bar";
        $contentId = "goodChangeReportManageBody";

        $c = 0;
        $bottons= array();

        $headerSearch = array();
        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "goodChangeReportManageSdateSearch";
        $headerSearch[$c]['title'] = "از تاریخ";
        $headerSearch[$c]['placeholder'] = "از تاریخ";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "goodChangeReportManageEdateSearch";
        $headerSearch[$c]['title'] = "تا تاریخ";
        $headerSearch[$c]['placeholder'] = "تا تاریخ";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "250px";
        $headerSearch[$c]['id'] = "goodChangeReportManageGNameSearch";
        $headerSearch[$c]['title'] = "نام محصول";
        $headerSearch[$c]['placeholder'] = "نام محصول";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "goodChangeReportManageGCodeSearch";
        $headerSearch[$c]['title'] = "کد محصول";
        $headerSearch[$c]['placeholder'] = "کد محصول";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "goodChangeReportManageFNameSearch";
        $headerSearch[$c]['title'] = "نام فیلد مورد نظر";
        $headerSearch[$c]['placeholder'] = "نام فیلد مورد نظر";
        $c++;

        $headerSearch[$c]['type'] = "btn";
        $headerSearch[$c]['title'] = "جستجو&nbsp;&nbsp;<i class='fas fa-search'></i>";
        $headerSearch[$c]['jsf'] = "showGoodChangeReportManageList";

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        return $htm;
    }

    public function getGoodChangeReportList($gName,$fName,$gCode,$sDate,$eDate,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('goodChangeReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`gCode` LIKE "%'.$gCode.'%" ';
        }
        if(strlen(trim($fName)) > 0){
            $w[] = '`fieldName_Fa`="'.$fName.'" ';
        }
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`changeDate`>="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`changeDate`<="'.$eDate.'" ';
        }
        $sql = "SELECT `gName`,`gCode`,`fieldName_Fa`,`currentValue`,`previousValue`,`changeDate` FROM `good` INNER JOIN `backup_good` ON (`good`.`RowID`=`backup_good`.`gid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `backup_good`.`RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['gName'] = $res[$y]['gName'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['fieldName_Fa'] = $res[$y]['fieldName_Fa'];
            $finalRes[$y]['previousValue'] = $res[$y]['previousValue'];
            $finalRes[$y]['currentValue'] = $res[$y]['currentValue'];
            $finalRes[$y]['changeDate'] = $ut->greg_to_jal($res[$y]['changeDate']);
        }
        return $finalRes;
    }

    public function getGoodChangeReportListCountRows($gName,$fName,$gCode,$sDate,$eDate){
        $acm = new acm();
        if(!$acm->hasAccess('goodChangeReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`gCode` LIKE "%'.$gCode.'%" ';
        }
        if(strlen(trim($fName)) > 0){
            $w[] = '`fieldName_Fa`="'.$fName.'" ';
        }
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`changeDate`>="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`changeDate`<="'.$eDate.'" ';
        }
        $sql = "SELECT `gName` FROM `good` INNER JOIN `backup_good` ON (`good`.`RowID`=`backup_good`.`gid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getFieldNames(){
        $db = new DBi();
        $sql = "SELECT `FaName`,`RowID` FROM `en_to_fa` WHERE `type`=1";
        $res = $db->ArrayQuery($sql);
        if(count($res)>0){
            return $res;
        }else{
            return false;
        }
    }

    //+++++++++++++++++ گزارش قیمت ها +++++++++++++++++++

    public function getPriceReportHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('priceReportManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();

        $manifold = 0;
        $z = 0;
        $x = 0;
        $y = 0;
        $pagename = array();
        $pageIcon = array();
        $contentId = array();
        $menuItems = array();
        $inputs = array();
        $bottons = array();
        $headerSearch = array();
        $Access = array();

        if($acm->hasAccess('piecePriceReportManage')){
            $pagename[$z] = "گزارش قیمت قطعات";
            $pageIcon[$z] = "fa-dollar-sign";
            $contentId[$z] = "piecePriceReportManageBody";
            $menuItems[$z] = 'piecePriceReportManageTabID';
            $inputs[$z] = 'piecePriceReportManageHiddenPage';

            $c = 0;
            $bottons1 = array();
            $bottons1[$c]['title'] = "بازنشانی روش محاسبات";
            $bottons1[$c]['jsf'] = "resetPieceHowSupply";
            $bottons1[$c]['icon'] = "fa-redo-alt";
            $c++;

            $bottons1[$c]['title'] = "محاسبه سریع قیمت";
            $bottons1[$c]['jsf'] = "quickPiecePriceCalculation";
            $bottons1[$c]['icon'] = "fa-dollar-sign";

            $bottons[$x] = $bottons1;


            $a = 0;
            $headerSearch1 = array();
            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "120px";
            $headerSearch1[$a]['id'] = "piecePriceReportManagePNameSearch";
            $headerSearch1[$a]['title'] = "قسمتی از نام قطعه";
            $headerSearch1[$a]['placeholder'] = "قسمتی از نام قطعه";
            $a++;

            $headerSearch1[$a]['type'] = "btn";
            $headerSearch1[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch1[$a]['jsf'] = "showMPiecePriceReportManageList";
            $a++;

            $headerSearch1[$a]['type'] = "btn";
            $headerSearch1[$a]['title'] = "مرحله قبل&nbsp;&nbsp;<i class='fa fa-redo-alt'></i>";
            $headerSearch1[$a]['jsf'] = "showBMPiecePriceReportManageList";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "120px";
            $headerSearch1[$a]['id'] = "piecePriceReportManagePCodeSearch";
            $headerSearch1[$a]['title'] = "کد قطعه";
            $headerSearch1[$a]['placeholder'] = "کد قطعه";
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['id'] = "piecePriceReportManageSupplySearch";
            $headerSearch1[$a]['title'] = "روش انجام محاسبات";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = 'روش انجام محاسبات';
            $headerSearch1[$a]['options'][0]["value"] = -1;
            $headerSearch1[$a]['options'][1]["title"] = 'وارداتی';
            $headerSearch1[$a]['options'][1]["value"] = 1;
            $headerSearch1[$a]['options'][2]["title"] = 'خرید داخلی';
            $headerSearch1[$a]['options'][2]["value"] = 2;
            $headerSearch1[$a]['options'][3]["title"] = 'خرید قطعه ماشینکاری';
            $headerSearch1[$a]['options'][3]["value"] = 3;
            $headerSearch1[$a]['options'][4]["title"] = 'خرید قطعه ریخته گری';
            $headerSearch1[$a]['options'][4]["value"] = 4;
            $headerSearch1[$a]['options'][5]["title"] = 'تولید ریخته گری';
            $headerSearch1[$a]['options'][5]["value"] = 5;
            $headerSearch1[$a]['options'][6]["title"] = 'تولید ماشینکاری';
            $headerSearch1[$a]['options'][6]["value"] = 6;
            $headerSearch1[$a]['options'][7]["title"] = 'فورج';
            $headerSearch1[$a]['options'][7]["value"] = 7;
            $headerSearch1[$a]['options'][8]["title"] = 'تزریق پلاستیک';
            $headerSearch1[$a]['options'][8]["value"] = 8;
            $headerSearch1[$a]['options'][9]["title"] = 'لوله';
            $headerSearch1[$a]['options'][9]["value"] = 9;
            $headerSearch1[$a]['options'][10]["title"] = 'شیلنگ';
            $headerSearch1[$a]['options'][10]["value"] = 10;
            $headerSearch1[$a]['options'][11]["title"] = 'برش لیزر';
            $headerSearch1[$a]['options'][11]["value"] = 11;
            $headerSearch1[$a]['options'][12]["title"] = 'کلکتور';
            $headerSearch1[$a]['options'][12]["value"] = 12;
            $headerSearch1[$a]['options'][13]["title"] = 'قطعه مونتاژی';
            $headerSearch1[$a]['options'][13]["value"] = 14;
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['id'] = "piecePriceReportManageErrorSearch";
            $headerSearch1[$a]['title'] = "همه";
            $headerSearch1[$a]['width'] = "100px";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = 'همه';
            $headerSearch1[$a]['options'][0]["value"] = -1;
            $headerSearch1[$a]['options'][1]["title"] = 'بدون خطا';
            $headerSearch1[$a]['options'][1]["value"] = 0;
            $headerSearch1[$a]['options'][2]["title"] = 'خطادار ها';
            $headerSearch1[$a]['options'][2]["value"] = 1;
            $a++;

            $headerSearch1[$a]['type'] = "btn";
            $headerSearch1[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fas fa-search'></i>";
            $headerSearch1[$a]['jsf'] = "showPiecePriceReportManageList";
            $a++;

            $headerSearch1[$a]['type'] = "btn";
            $headerSearch1[$a]['title'] = "پاک سازی فیلترها&nbsp;&nbsp;<i class='fas fa-redo-alt'></i>";
            $headerSearch1[$a]['jsf'] = "emptyPiecePriceReportSearchFilters";

            $headerSearch[$y] = $headerSearch1;

            $z++;
            $x++;
            $y++;
            $manifold++;
            $Access[] = 1;
        }
        if($acm->hasAccess('goodPriceReportManage')){
            $pagename[$z] = "گزارش قیمت محصولات";
            $pageIcon[$z] = "fa-dollar-sign";
            $contentId[$z] = "goodPriceReportManageBody";
            $menuItems[$z] = 'goodPriceReportManageTabID';
            $inputs[$z] = 'goodPriceReportManageHiddenPage';

            $bottons2 = array();
            $bottons[$x] = $bottons2;

            $a = 0;
            $headerSearch2 = array();
            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "120px";
            $headerSearch2[$a]['id'] = "goodPriceReportManageGNameSearch";
            $headerSearch2[$a]['title'] = "قسمتی از نام محصول";
            $headerSearch2[$a]['placeholder'] = "قسمتی از نام محصول";
            $a++;

            $headerSearch2[$a]['type'] = "btn";
            $headerSearch2[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch2[$a]['jsf'] = "showMGoodPriceReportManageList";
            $a++;

            $headerSearch2[$a]['type'] = "btn";
            $headerSearch2[$a]['title'] = "مرحله قبل&nbsp;&nbsp;<i class='fa fa-redo-alt'></i>";
            $headerSearch2[$a]['jsf'] = "showBMGoodPriceReportManageList";
            $a++;

            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "120px";
            $headerSearch2[$a]['id'] = "goodPriceReportManagePCodeSearch";
            $headerSearch2[$a]['title'] = "کد محصول";
            $headerSearch2[$a]['placeholder'] = "کد محصول";
            $a++;

            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "120px";
            $headerSearch2[$a]['id'] = "goodPriceReportManageHCodeSearch";
            $headerSearch2[$a]['title'] = "کد همکاران محصول";
            $headerSearch2[$a]['placeholder'] = "کد همکاران محصول";
            $a++;

            $headerSearch2[$a]['type'] = "btn";
            $headerSearch2[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fas fa-search'></i>";
            $headerSearch2[$a]['jsf'] = "showGoodPriceReportManageList";
            $a++;

            $headerSearch2[$a]['type'] = "btn";
            $headerSearch2[$a]['title'] = "پاک سازی فیلترها&nbsp;&nbsp;<i class='fas fa-redo-alt'></i>";
            $headerSearch2[$a]['jsf'] = "emptyGoodPriceReportSearchFilters";

            $headerSearch[$y] = $headerSearch2;

            $z++;
            $x++;
            $y++;
            $manifold++;
            $Access[] = 2;
        }
        if($acm->hasAccess('goodProccessPriceReportManage')){
            $pagename[$z] = "گزارش قیمت کالای در جریان ساخت";
            $pageIcon[$z] = "fa-dollar-sign";
            $contentId[$z] = "goodProccessPriceReportManageBody";
            $menuItems[$z] = 'goodProccessPriceReportManageTabID';
            $inputs[$z] = 'goodProccessPriceReportManageHiddenPage';

            $bottons3 = array();
            $bottons[$x] = $bottons3;

            $a = 0;
            $headerSearch3 = array();
            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "120px";
            $headerSearch3[$a]['id'] = "goodProccessPriceReportManageCodeSearch";
            $headerSearch3[$a]['title'] = "کد در جریان";
            $headerSearch3[$a]['placeholder'] = "کد در جریان";
            $a++;

            $headerSearch3[$a]['type'] = "btn";
            $headerSearch3[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch3[$a]['jsf'] = "showGoodProccessPriceReportManageList";

            $headerSearch[$y] = $headerSearch3;

            $manifold++;
            $Access[] = 3;
        }

        $htm = $ut->getHtmlOfDefaultManagementMultiPage($pagename,$pageIcon,$contentId,$menuItems,$bottons,$manifold,$headerSearch,'',$inputs);
        //++++++++++++++++++ Start Piece Costs Info Modal ++++++++++++++++++++++
        $modalID = "pieceCostsInfoModal";
        $modalTitle = "ریز هزینه ها";
        $ShowDescription = 'Piece-costs-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showPieceCostsInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Piece Costs Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Piece Price Info Modal ++++++++++++++++++++++
        $modalID = "piecePriceInfoModal";
        $modalTitle = "ریز قیمت ها";
        $ShowDescription = 'Piece-price-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showPiecePriceInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Piece Price Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Piece Error Info Modal ++++++++++++++++++++++
        $modalID = "pieceErrorInfoModal";
        $modalTitle = "خطاها";
        $ShowDescription = 'Piece-error-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showPieceErrorInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Piece Error Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Good Error Info Modal ++++++++++++++++++++++
        $modalID = "goodErrorInfoModal";
        $modalTitle = "خطاها";
        $ShowDescription = 'Good-error-Info-body';
        $style = 'style="max-width: 800px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showGoodErrorInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Good Error Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Good FINE PRICE Modal ++++++++++++++++++++++
        $modalID = "goodFinePriceModal";
        $modalTitle = "اجزا محصول";
        $ShowDescription = 'Good-fine-price-body';
        $style = 'style="max-width: 1024px;"';
        $items = array();
        $footerBottons = array();
        $x = 0;
        if($acm->hasAccess('excelexport')){
            $footerBottons[$x]['title'] = "خروجی اکسل";
            $footerBottons[$x]['jsf'] = "getExcelGoodFinePrice";
            $footerBottons[$x]['type'] = "btn-success";
            $x++;
        }
        $footerBottons[$x]['title'] = "بستن";
        $footerBottons[$x]['type'] = "dismis";
        $showGoodFinePriceInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Good FINE PRICE Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ QUICK PIECE PRICE MODAL++++++++++++++++++++++++++++++++
        $modalID = "quickPiecePriceCalcModal";
        $modalTitle = "محاسبه سریع قیمت";
        $ShowDescription = 'quick-piece-price-body';
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "quickPiecePriceCalcHow_supply";
        $items[$c]['title'] = "روش محاسبات";
        $items[$c]['onchange'] = "onchange=quickPiecePriceCalcChangeField()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'روش انجام محاسبات';
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = 'ریخته گری';
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = 'ماشین کاری';
        $items[$c]['options'][2]["value"] = 1;
        $items[$c]['options'][3]["title"] = 'تزریق پلاستیک';
        $items[$c]['options'][3]["value"] = 2;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "quickPiecePriceCalcBrassType";
        $items[$c]['title'] = "نوع شمش";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'قطر زیر 14';
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = 'قطر بالای 14';
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = 'کلکتور';
        $items[$c]['options'][2]["value"] = 2;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "quickPiecePriceCalcWeight_FM";
        $items[$c]['title'] = "وزن مواد اولیه";
        $items[$c]['placeholder'] = "گرم";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "quickPiecePriceCalcWeight_Mach";
        $items[$c]['title'] = "وزن ماشین کاری";
        $items[$c]['placeholder'] = "گرم";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "quickPiecePriceCalcWeight_Final";
        $items[$c]['title'] = "وزن نهایی";
        $items[$c]['placeholder'] = "گرم";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "quickPiecePriceCalcPrice_FM";
        $items[$c]['title'] = "قیمت ماده اولیه (کیلوگرم)";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "quickPiecePriceCalcWeight_FMT";
        $items[$c]['title'] = "وزن ماده اولیه";
        $items[$c]['placeholder'] = "گرم";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "quickPiecePriceCalcWeight_FinalT";
        $items[$c]['title'] = "وزن نهایی";
        $items[$c]['placeholder'] = "گرم";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "quickPiecePriceCalcPer_MB";
        $items[$c]['title'] = "درصد مواد بازیافتی";
        $items[$c]['placeholder'] = "درصد";

        $footerBottons = array();
        $footerBottons[0]['title'] = "محاسبه";
        $footerBottons[0]['jsf'] = "doQuickPiecePriceCalc";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $quickPiecePriceCalc = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF QUICK PIECE PRICE MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ GOOD PROCCESS PRICE MODAL++++++++++++++++++++++++++++++++
        $modalID = "goodProccessPriceCalcModal";
        $modalTitle = "محاسبه قیمت کالای در جریان ساخت";
        $ShowDescription = 'good-proccess-price-body';
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "goodProccessPriceCalcRawMaterialCode";
        $items[$c]['title'] = "کد ماده اولیه";
        $items[$c]['placeholder'] = "کد";
        $items[$c]['disabled'] = "disabled";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "goodProccessPriceCalcAVGP";
        $items[$c]['title'] = "قیمت میانگین ماده اولیه (طبق واحد همکاران)";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "goodProccessPriceCalcPCode";
        $items[$c]['title'] = "کد قطعه";
        $items[$c]['placeholder'] = "کد";
        $items[$c]['disabled'] = "disabled";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "goodProccessPriceCalcPName";
        $items[$c]['title'] = "نام قطعه";
        $items[$c]['placeholder'] = "نام";
        $items[$c]['disabled'] = "disabled";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "goodProccessPriceCalcHiddenPid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "محاسبه";
        $footerBottons[0]['jsf'] = "doGoodProccessPriceCalc";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $goodProccessPriceCalc = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF GOOD PROCCESS PRICE MODAL +++++++++++++++++++++++++++++++++++++
        $htm .= $showPieceCostsInfo;
        $htm .= $showPiecePriceInfo;
        $htm .= $showPieceErrorInfo;
        $htm .= $showGoodErrorInfo;
        $htm .= $showGoodFinePriceInfo;
        $htm .= $quickPiecePriceCalc;
        $htm .= $goodProccessPriceCalc;

        $send[] = $Access;
        $send[] = $htm;
		//$ut->fileRecorder('send:**********************************');
		//$ut->fileRecorder($send);
        return $send;
    }

    //+++++++++++++++++ گزارش قیمت قطعات +++++++++++++++++++

    public function getPiecePriceReportList($pName,$pCode,$supply,$error,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('piecePriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $usl = "UPDATE `piece` SET `uid`=0 WHERE `uid`={$_SESSION['userid']}";
        $db->Query($usl);

        $dsl = "DELETE FROM `multiple_search` WHERE `uid`={$_SESSION['userid']}";
        $db->Query($dsl);

        $qq = "UPDATE `piece` SET `showf`=0";
        $db->Query($qq);

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        if(strlen(trim($pCode)) > 0){
            $w[] = '`pCode` LIKE "%'.$pCode.'%" ';
        }
        if(intval($error) >= 0){
            $w[] = '`error`='.$error.' ';
        }
        if (intval($supply) > 0){
            $w[] = '`ChangingHow_supply`='.$supply.' ';
        }else{
            $w[] = '`ChangingHow_supply` IN (1,2,3,4,5,6,7,8,10,11,12,14) ';
        }
        $sql = "SELECT `piece`.`RowID`,`pName`,`pCode`,`FixHow_supply`,`ChangingHow_supply`,`priceFinalRawMaterial`,`priceFinalRawMaterialCash`,`TotalCosts`,`error`
                FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                             LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
            $qqq = $sql;
        }
        $sql .= " ORDER BY `piece`.`RowID` ASC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        $arr = array();
        $rqq = $db->ArrayQuery($qqq);
        $cnt = count($rqq);
        for ($i=0;$i<$cnt;$i++){
            $arr[] = $rqq[$i]['RowID'];
        }
        $arr = implode(',',$arr);
        $q = "UPDATE `piece` SET `showf`=1 WHERE `RowID` IN ({$arr})";
        $db->Query($q);

        $query = "SELECT `changeDate` FROM `backup_piece` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate = $ut->greg_to_jal($rst[0]['changeDate']);

        for($y=0;$y<$listCount;$y++){
            switch ($res[$y]['FixHow_supply']){
                case 0:
                    $txt = 'راکد';
                    break;
                case 1:
                    $txt = 'وارداتی';
                    break;
                case 2:
                    $txt = 'خرید داخلی';
                    break;
                case 3:
                    $txt = 'خرید قطعه ماشینکاری';
                    break;
                case 4:
                    $txt = 'خرید قطعه ریخته گری';
                    break;
                case 5:
                    $txt = 'تولید ریخته گری';
                    break;
                case 6:
                    $txt = 'تولید ماشینکاری';
                    break;
                case 7:
                    $txt = 'فورج';
                    break;
                case 8:
                    $txt = 'تزریق پلاستیک';
                    break;
                case 9:
                    $txt = 'لوله';
                    break;
                case 10:
                    $txt = 'شیلنگ';
                    break;
                case 11:
                    $txt = 'برش لیزر';
                    break;
                case 12:
                    $txt = 'کلکتور';
                    break;
                case 13:
                    $txt = 'منسوخ';
                    break;
                case 14:
                    $txt = 'قطعه مونتاژی';
                    break;
            }

            switch ($res[$y]['ChangingHow_supply']){
                case 0:
                    $hs = [0,'راکد'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 1:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [1,'وارداتی',$res[$y]['FixHow_supply'],$txt,2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 2:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [2,'خرید داخلی',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [2,'خرید داخلی',1,'وارداتی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 3:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [3,'خرید قطعه ماشینکاری',$res[$y]['FixHow_supply'],$txt,2,'خرید داخلی',1,'وارداتی',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 4:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [4,'خرید قطعه ریخته گری',$res[$y]['FixHow_supply'],$txt,3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی'];
                    }else{
                        $hs = [4,'خرید قطعه ریخته گری',3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 5:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [5,'تولید ریخته گری',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [5,'تولید ریخته گری',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 6:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [6,'تولید ماشینکاری',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [6,'تولید ماشینکاری',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 7:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [7,'فورج',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [7,'فورج',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 8:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [8,'تزریق پلاستیک',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [8,'تزریق پلاستیک',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 9:
                    $hs = [9,'لوله'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 10:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [10,'شیلنگ',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [10,'شیلنگ',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 11:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [11,'برش لیزر',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [11,'برش لیزر',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 12:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [12,'کلکتور',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [12,'کلکتور',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 13:
                    $hs = [13,'منسوخ'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 14:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [14,'قطعه مونتاژی',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [14,'قطعه مونتاژی',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
            }
            $finalRes[$y]['btnType'] = ($res[$y]['error'] == 1 ? 'btn-danger' : 'btn-success');
            $finalRes[$y]['disabled'] = ($res[$y]['error'] == 1 ? '' : 'disabled');
            $finalRes[$y]['icon'] = 'fa-exclamation-circle';
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['ChangingHow_supply'] = $txt;
            $finalRes[$y]['priceFinalRawMaterial'] = (intval($res[$y]['priceFinalRawMaterial']) > 0 ? number_format($res[$y]['priceFinalRawMaterial']).' ریال' : '');
            $finalRes[$y]['priceFinalRawMaterialCash'] = (intval($res[$y]['priceFinalRawMaterialCash']) > 0 ? number_format($res[$y]['priceFinalRawMaterialCash']).' ریال' : '');
            $finalRes[$y]['totalPC'] = (intval($res[$y]['priceFinalRawMaterial']) > 0 ? number_format($res[$y]['priceFinalRawMaterial']+intval($res[$y]['TotalCosts'])).' ریال' : '');
            $finalRes[$y]['totalPCC'] = (intval($res[$y]['priceFinalRawMaterialCash']) > 0 ? number_format($res[$y]['priceFinalRawMaterialCash']+intval($res[$y]['TotalCosts'])).' ریال' : '');
        }
        $sendParam = array($finalRes,$changeDate);
        return $sendParam;
    }

    public function getMPiecePriceReportList($pName,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('piecePriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();

        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        $w[] = '`ChangingHow_supply` IN (1,2,3,4,5,6,7,8,10,11,12,14) ';

        $sql = "SELECT `RowID` FROM `piece` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql = "SELECT `piece`.`RowID`,`pName`,`pCode`,`FixHow_supply`,`ChangingHow_supply`,`priceFinalRawMaterial`,`priceFinalRawMaterialCash`,`TotalCosts`,`error`
                FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                             LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
            $qqq = $sql;
        }
        $sql .= " ORDER BY `piece`.`RowID` ASC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        $arr = array();
        $rqq = $db->ArrayQuery($qqq);
        $cnt = count($rqq);
        for ($i=0;$i<$cnt;$i++){
            $arr[] = $rqq[$i]['RowID'];
        }
        $arr = implode(',',$arr);
        $q = "UPDATE `piece` SET `showf`=1 WHERE `RowID` IN ({$arr})";
        $db->Query($q);

        $q = "UPDATE `piece` SET `showf`=0 WHERE `RowID` NOT IN ({$arr})";
        $db->Query($q);

        $q = "UPDATE `piece` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
        $db->Query($q);

        $q = "UPDATE `piece` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
        $db->Query($q);

        $q = "INSERT INTO `multiple_search` (`word`,`uid`) VALUES ('{$pName}',{$_SESSION['userid']})";
        $db->Query($q);

        $query = "SELECT `changeDate` FROM `backup_piece` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate = $ut->greg_to_jal($rst[0]['changeDate']);

        for($y=0;$y<$listCount;$y++){
            switch ($res[$y]['FixHow_supply']){
                case 0:
                    $txt = 'راکد';
                    break;
                case 1:
                    $txt = 'وارداتی';
                    break;
                case 2:
                    $txt = 'خرید داخلی';
                    break;
                case 3:
                    $txt = 'خرید قطعه ماشینکاری';
                    break;
                case 4:
                    $txt = 'خرید قطعه ریخته گری';
                    break;
                case 5:
                    $txt = 'تولید ریخته گری';
                    break;
                case 6:
                    $txt = 'تولید ماشینکاری';
                    break;
                case 7:
                    $txt = 'فورج';
                    break;
                case 8:
                    $txt = 'تزریق پلاستیک';
                    break;
                case 9:
                    $txt = 'لوله';
                    break;
                case 10:
                    $txt = 'شیلنگ';
                    break;
                case 11:
                    $txt = 'برش لیزر';
                    break;
                case 12:
                    $txt = 'کلکتور';
                    break;
                case 13:
                    $txt = 'منسوخ';
                    break;
                case 14:
                    $txt = 'قطعه مونتاژی';
                    break;
            }

            switch ($res[$y]['ChangingHow_supply']){
                case 0:
                    $hs = [0,'راکد'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 1:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [1,'وارداتی',$res[$y]['FixHow_supply'],$txt,2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 2:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [2,'خرید داخلی',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [2,'خرید داخلی',1,'وارداتی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 3:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [3,'خرید قطعه ماشینکاری',$res[$y]['FixHow_supply'],$txt,2,'خرید داخلی',1,'وارداتی',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 4:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [4,'خرید قطعه ریخته گری',$res[$y]['FixHow_supply'],$txt,3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی'];
                    }else{
                        $hs = [4,'خرید قطعه ریخته گری',3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 5:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [5,'تولید ریخته گری',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [5,'تولید ریخته گری',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 6:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [6,'تولید ماشینکاری',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [6,'تولید ماشینکاری',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 7:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [7,'فورج',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [7,'فورج',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 8:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [8,'تزریق پلاستیک',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [8,'تزریق پلاستیک',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 9:
                    $hs = [9,'لوله'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 10:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [10,'شیلنگ',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [10,'شیلنگ',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 11:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [11,'برش لیزر',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [11,'برش لیزر',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 12:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [12,'کلکتور',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [12,'کلکتور',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 13:
                    $hs = [13,'منسوخ'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 14:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [14,'قطعه مونتاژی',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [14,'قطعه مونتاژی',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
            }
            $finalRes[$y]['btnType'] = ($res[$y]['error'] == 1 ? 'btn-danger' : 'btn-success');
            $finalRes[$y]['disabled'] = ($res[$y]['error'] == 1 ? '' : 'disabled');
            $finalRes[$y]['icon'] = 'fa-exclamation-circle';
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['ChangingHow_supply'] = $txt;
            $finalRes[$y]['priceFinalRawMaterial'] = (intval($res[$y]['priceFinalRawMaterial']) > 0 ? number_format($res[$y]['priceFinalRawMaterial']).' ریال' : '');
            $finalRes[$y]['priceFinalRawMaterialCash'] = (intval($res[$y]['priceFinalRawMaterialCash']) > 0 ? number_format($res[$y]['priceFinalRawMaterialCash']).' ریال' : '');
            $finalRes[$y]['totalPC'] = (intval($res[$y]['priceFinalRawMaterial']) > 0 ? number_format($res[$y]['priceFinalRawMaterial']+intval($res[$y]['TotalCosts'])).' ریال' : '');
            $finalRes[$y]['totalPCC'] = (intval($res[$y]['priceFinalRawMaterialCash']) > 0 ? number_format($res[$y]['priceFinalRawMaterialCash']+intval($res[$y]['TotalCosts'])).' ریال' : '');
        }
        $sendParam = array($finalRes,$changeDate);
        return $sendParam;
    }

    public function getMPagePiecePriceReportList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('piecePriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();

        $w[] = '`ChangingHow_supply` IN (1,2,3,4,5,6,7,8,10,11,12,14) ';

        $sql = "SELECT `RowID` FROM `piece` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql = "SELECT `piece`.`RowID`,`pName`,`pCode`,`FixHow_supply`,`ChangingHow_supply`,`priceFinalRawMaterial`,`priceFinalRawMaterialCash`,`TotalCosts`,`error`
                FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                             LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
            $qqq = $sql;
        }
        $sql .= " ORDER BY `piece`.`RowID` ASC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        $arr = array();
        $rqq = $db->ArrayQuery($qqq);
        $cnt = count($rqq);
        for ($i=0;$i<$cnt;$i++){
            $arr[] = $rqq[$i]['RowID'];
        }
        $arr = implode(',',$arr);
        $q = "UPDATE `piece` SET `showf`=1 WHERE `RowID` IN ({$arr})";
        $db->Query($q);

        $q = "UPDATE `piece` SET `showf`=0 WHERE `RowID` NOT IN ({$arr})";
        $db->Query($q);

        $q = "UPDATE `piece` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
        $db->Query($q);

        $q = "UPDATE `piece` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
        $db->Query($q);

        $query = "SELECT `changeDate` FROM `backup_piece` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate = $ut->greg_to_jal($rst[0]['changeDate']);

        for($y=0;$y<$listCount;$y++){
            switch ($res[$y]['FixHow_supply']){
                case 0:
                    $txt = 'راکد';
                    break;
                case 1:
                    $txt = 'وارداتی';
                    break;
                case 2:
                    $txt = 'خرید داخلی';
                    break;
                case 3:
                    $txt = 'خرید قطعه ماشینکاری';
                    break;
                case 4:
                    $txt = 'خرید قطعه ریخته گری';
                    break;
                case 5:
                    $txt = 'تولید ریخته گری';
                    break;
                case 6:
                    $txt = 'تولید ماشینکاری';
                    break;
                case 7:
                    $txt = 'فورج';
                    break;
                case 8:
                    $txt = 'تزریق پلاستیک';
                    break;
                case 9:
                    $txt = 'لوله';
                    break;
                case 10:
                    $txt = 'شیلنگ';
                    break;
                case 11:
                    $txt = 'برش لیزر';
                    break;
                case 12:
                    $txt = 'کلکتور';
                    break;
                case 13:
                    $txt = 'منسوخ';
                    break;
                case 14:
                    $txt = 'قطعه مونتاژی';
                    break;
            }

            switch ($res[$y]['ChangingHow_supply']){
                case 0:
                    $hs = [0,'راکد'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 1:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [1,'وارداتی',$res[$y]['FixHow_supply'],$txt,2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 2:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [2,'خرید داخلی',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [2,'خرید داخلی',1,'وارداتی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 3:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [3,'خرید قطعه ماشینکاری',$res[$y]['FixHow_supply'],$txt,2,'خرید داخلی',1,'وارداتی',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 4:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [4,'خرید قطعه ریخته گری',$res[$y]['FixHow_supply'],$txt,3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی'];
                    }else{
                        $hs = [4,'خرید قطعه ریخته گری',3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 5:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [5,'تولید ریخته گری',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [5,'تولید ریخته گری',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 6:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [6,'تولید ماشینکاری',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [6,'تولید ماشینکاری',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 7:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [7,'فورج',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [7,'فورج',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 8:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [8,'تزریق پلاستیک',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [8,'تزریق پلاستیک',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 9:
                    $hs = [9,'لوله'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 10:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [10,'شیلنگ',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [10,'شیلنگ',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 11:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [11,'برش لیزر',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [11,'برش لیزر',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 12:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [12,'کلکتور',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [12,'کلکتور',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 13:
                    $hs = [13,'منسوخ'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 14:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [14,'قطعه مونتاژی',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [14,'قطعه مونتاژی',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
            }
            $finalRes[$y]['btnType'] = ($res[$y]['error'] == 1 ? 'btn-danger' : 'btn-success');
            $finalRes[$y]['disabled'] = ($res[$y]['error'] == 1 ? '' : 'disabled');
            $finalRes[$y]['icon'] = 'fa-exclamation-circle';
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['ChangingHow_supply'] = $txt;
            $finalRes[$y]['priceFinalRawMaterial'] = (intval($res[$y]['priceFinalRawMaterial']) > 0 ? number_format($res[$y]['priceFinalRawMaterial']).' ریال' : '');
            $finalRes[$y]['priceFinalRawMaterialCash'] = (intval($res[$y]['priceFinalRawMaterialCash']) > 0 ? number_format($res[$y]['priceFinalRawMaterialCash']).' ریال' : '');
            $finalRes[$y]['totalPC'] = (intval($res[$y]['priceFinalRawMaterial']) > 0 ? number_format($res[$y]['priceFinalRawMaterial']+intval($res[$y]['TotalCosts'])).' ریال' : '');
            $finalRes[$y]['totalPCC'] = (intval($res[$y]['priceFinalRawMaterialCash']) > 0 ? number_format($res[$y]['priceFinalRawMaterialCash']+intval($res[$y]['TotalCosts'])).' ریال' : '');
        }
        $sendParam = array($finalRes,$changeDate);
        return $sendParam;
    }

    public function getBMPiecePriceReportList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('piecePriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $sql = "DELETE FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` DESC LIMIT 1";
        $db->Query($sql);

        $sql1 = "SELECT `word` FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` ASC";
        $rst = $db->ArrayQuery($sql1);
        $cntrst = count($rst);

        $sql2 = "UPDATE `piece` SET `uid`=0 WHERE `uid`={$_SESSION['userid']}";
        $db->Query($sql2);

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        if ($cntrst == 0){
            $qq = "UPDATE `piece` SET `showf`=0";
            $db->Query($qq);
            $w = array();
            $w[] = '`ChangingHow_supply` IN (1,2,3,4,5,6,7,8,10,11,12,14) ';

            $sql = "SELECT `piece`.`RowID`,`pName`,`pCode`,`FixHow_supply`,`ChangingHow_supply`,`priceFinalRawMaterial`,`priceFinalRawMaterialCash`,`TotalCosts`,`error`
                    FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                 LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)";
            if(count($w)){
                $where = implode(" AND ",$w);
                $sql .= " WHERE ".$where;
            }
            $sql .= " ORDER BY `piece`.`RowID` ASC LIMIT $start,".$numRows;
            $res = $db->ArrayQuery($sql);
        }else{
            for ($i=0;$i<$cntrst;$i++){
                $w = array();
                $w[] = '`pName` LIKE "%'.$rst[$i]['word'].'%" ';
                $w[] = '`ChangingHow_supply` IN (1,2,3,4,5,6,7,8,10,11,12,14) ';

                $sql3 = "SELECT `RowID` FROM `piece` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
                $res = $db->ArrayQuery($sql3);
                if (count($res) > 0){
                    $w[] = '`uid`='.$_SESSION['userid'].' ';
                }

                $sql = "SELECT `piece`.`RowID`,`pName`,`pCode`,`FixHow_supply`,`ChangingHow_supply`,`priceFinalRawMaterial`,`priceFinalRawMaterialCash`,`TotalCosts`,`error`
                        FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                     LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)";
                if(count($w)){
                    $where = implode(" AND ",$w);
                    $sql .= " WHERE ".$where;
                    $qqq = $sql;
                }
                $sql .= " ORDER BY `piece`.`RowID` ASC LIMIT $start,".$numRows;
                $res = $db->ArrayQuery($sql);

                $arr = array();
                $rqq = $db->ArrayQuery($qqq);
                $cnt = count($rqq);
                for ($j=0;$j<$cnt;$j++){
                    $arr[] = $rqq[$j]['RowID'];
                }
                $arr = implode(',',$arr);
                $q = "UPDATE `piece` SET `showf`=1 WHERE `RowID` IN ({$arr})";
                $db->Query($q);

                $q = "UPDATE `piece` SET `showf`=0 WHERE `RowID` NOT IN ({$arr})";
                $db->Query($q);

                $q = "UPDATE `piece` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
                $db->Query($q);

                $q = "UPDATE `piece` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
                $db->Query($q);
            }
        }
        $query = "SELECT `changeDate` FROM `backup_piece` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate = $ut->greg_to_jal($rst[0]['changeDate']);

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            switch ($res[$y]['FixHow_supply']){
                case 0:
                    $txt = 'راکد';
                    break;
                case 1:
                    $txt = 'وارداتی';
                    break;
                case 2:
                    $txt = 'خرید داخلی';
                    break;
                case 3:
                    $txt = 'خرید قطعه ماشینکاری';
                    break;
                case 4:
                    $txt = 'خرید قطعه ریخته گری';
                    break;
                case 5:
                    $txt = 'تولید ریخته گری';
                    break;
                case 6:
                    $txt = 'تولید ماشینکاری';
                    break;
                case 7:
                    $txt = 'فورج';
                    break;
                case 8:
                    $txt = 'تزریق پلاستیک';
                    break;
                case 9:
                    $txt = 'لوله';
                    break;
                case 10:
                    $txt = 'شیلنگ';
                    break;
                case 11:
                    $txt = 'برش لیزر';
                    break;
                case 12:
                    $txt = 'کلکتور';
                    break;
                case 13:
                    $txt = 'منسوخ';
                    break;
                case 14:
                    $txt = 'قطعه مونتاژی';
                    break;
            }

            switch ($res[$y]['ChangingHow_supply']){
                case 0:
                    $hs = [0,'راکد'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 1:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [1,'وارداتی',$res[$y]['FixHow_supply'],$txt,2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 2:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [2,'خرید داخلی',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [2,'خرید داخلی',1,'وارداتی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 3:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [3,'خرید قطعه ماشینکاری',$res[$y]['FixHow_supply'],$txt,2,'خرید داخلی',1,'وارداتی',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 4:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [4,'خرید قطعه ریخته گری',$res[$y]['FixHow_supply'],$txt,3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی'];
                    }else{
                        $hs = [4,'خرید قطعه ریخته گری',3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 5:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [5,'تولید ریخته گری',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [5,'تولید ریخته گری',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 6:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [6,'تولید ماشینکاری',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [6,'تولید ماشینکاری',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 7:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [7,'فورج',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [7,'فورج',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 8:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [8,'تزریق پلاستیک',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [8,'تزریق پلاستیک',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 9:
                    $hs = [9,'لوله'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 10:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [10,'شیلنگ',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [10,'شیلنگ',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 11:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [11,'برش لیزر',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [11,'برش لیزر',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 12:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [12,'کلکتور',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [12,'کلکتور',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 13:
                    $hs = [13,'منسوخ'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 14:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [14,'قطعه مونتاژی',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [14,'قطعه مونتاژی',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
            }
            $finalRes[$y]['btnType'] = ($res[$y]['error'] == 1 ? 'btn-danger' : 'btn-success');
            $finalRes[$y]['disabled'] = ($res[$y]['error'] == 1 ? '' : 'disabled');
            $finalRes[$y]['icon'] = 'fa-exclamation-circle';
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['ChangingHow_supply'] = $txt;
            $finalRes[$y]['priceFinalRawMaterial'] = (intval($res[$y]['priceFinalRawMaterial']) > 0 ? number_format($res[$y]['priceFinalRawMaterial']).' ریال' : '');
            $finalRes[$y]['priceFinalRawMaterialCash'] = (intval($res[$y]['priceFinalRawMaterialCash']) > 0 ? number_format($res[$y]['priceFinalRawMaterialCash']).' ریال' : '');
            $finalRes[$y]['totalPC'] = (intval($res[$y]['priceFinalRawMaterial']) > 0 ? number_format($res[$y]['priceFinalRawMaterial']+intval($res[$y]['TotalCosts'])).' ریال' : '');
            $finalRes[$y]['totalPCC'] = (intval($res[$y]['priceFinalRawMaterialCash']) > 0 ? number_format($res[$y]['priceFinalRawMaterialCash']+intval($res[$y]['TotalCosts'])).' ریال' : '');
        }
        $sendParam = array($finalRes,$changeDate);
        return $sendParam;
    }

    public function getBMPagePiecePriceReportList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('piecePriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $sql1 = "SELECT `word` FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` ASC";
        $rst = $db->ArrayQuery($sql1);
        $cntrst = count($rst);

        $sql2 = "UPDATE `piece` SET `uid`=0 WHERE `uid`={$_SESSION['userid']}";
        $db->Query($sql2);

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        if ($cntrst == 0){
            $qq = "UPDATE `piece` SET `showf`=0";
            $db->Query($qq);
            $w = array();
            $w[] = '`ChangingHow_supply` IN (1,2,3,4,5,6,7,8,10,11,12,14) ';

            $sql = "SELECT `piece`.`RowID`,`pName`,`pCode`,`FixHow_supply`,`ChangingHow_supply`,`priceFinalRawMaterial`,`priceFinalRawMaterialCash`,`TotalCosts`,`error`
                    FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                 LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)";
            if(count($w)){
                $where = implode(" AND ",$w);
                $sql .= " WHERE ".$where;
            }
            $sql .= " ORDER BY `piece`.`RowID` ASC LIMIT $start,".$numRows;
            $res = $db->ArrayQuery($sql);
        }else{
            for ($i=0;$i<$cntrst;$i++){
                $w = array();
                $w[] = '`pName` LIKE "%'.$rst[$i]['word'].'%" ';
                $w[] = '`ChangingHow_supply` IN (1,2,3,4,5,6,7,8,10,11,12,14) ';

                $sql3 = "SELECT `RowID` FROM `piece` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
                $res = $db->ArrayQuery($sql3);
                if (count($res) > 0){
                    $w[] = '`uid`='.$_SESSION['userid'].' ';
                }

                $sql = "SELECT `piece`.`RowID`,`pName`,`pCode`,`FixHow_supply`,`ChangingHow_supply`,`priceFinalRawMaterial`,`priceFinalRawMaterialCash`,`TotalCosts`,`error`
                        FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                     LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)";
                if(count($w)){
                    $where = implode(" AND ",$w);
                    $sql .= " WHERE ".$where;
                    $qqq = $sql;
                }
                $sql .= " ORDER BY `piece`.`RowID` ASC LIMIT $start,".$numRows;
                $res = $db->ArrayQuery($sql);

                $arr = array();
                $rqq = $db->ArrayQuery($qqq);
                $cnt = count($rqq);
                for ($j=0;$j<$cnt;$j++){
                    $arr[] = $rqq[$j]['RowID'];
                }
                $arr = implode(',',$arr);
                $q = "UPDATE `piece` SET `showf`=1 WHERE `RowID` IN ({$arr})";
                $db->Query($q);

                $q = "UPDATE `piece` SET `showf`=0 WHERE `RowID` NOT IN ({$arr})";
                $db->Query($q);

                $q = "UPDATE `piece` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
                $db->Query($q);

                $q = "UPDATE `piece` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
                $db->Query($q);
            }
        }
        $query = "SELECT `changeDate` FROM `backup_piece` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate = $ut->greg_to_jal($rst[0]['changeDate']);

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            switch ($res[$y]['FixHow_supply']){
                case 0:
                    $txt = 'راکد';
                    break;
                case 1:
                    $txt = 'وارداتی';
                    break;
                case 2:
                    $txt = 'خرید داخلی';
                    break;
                case 3:
                    $txt = 'خرید قطعه ماشینکاری';
                    break;
                case 4:
                    $txt = 'خرید قطعه ریخته گری';
                    break;
                case 5:
                    $txt = 'تولید ریخته گری';
                    break;
                case 6:
                    $txt = 'تولید ماشینکاری';
                    break;
                case 7:
                    $txt = 'فورج';
                    break;
                case 8:
                    $txt = 'تزریق پلاستیک';
                    break;
                case 9:
                    $txt = 'لوله';
                    break;
                case 10:
                    $txt = 'شیلنگ';
                    break;
                case 11:
                    $txt = 'برش لیزر';
                    break;
                case 12:
                    $txt = 'کلکتور';
                    break;
                case 13:
                    $txt = 'منسوخ';
                    break;
                case 14:
                    $txt = 'قطعه مونتاژی';
                    break;
            }

            switch ($res[$y]['ChangingHow_supply']){
                case 0:
                    $hs = [0,'راکد'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 1:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [1,'وارداتی',$res[$y]['FixHow_supply'],$txt,2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 2:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [2,'خرید داخلی',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [2,'خرید داخلی',1,'وارداتی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 3:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [3,'خرید قطعه ماشینکاری',$res[$y]['FixHow_supply'],$txt,2,'خرید داخلی',1,'وارداتی',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 4:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [4,'خرید قطعه ریخته گری',$res[$y]['FixHow_supply'],$txt,3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی'];
                    }else{
                        $hs = [4,'خرید قطعه ریخته گری',3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 5:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [5,'تولید ریخته گری',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [5,'تولید ریخته گری',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 6:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [6,'تولید ماشینکاری',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [6,'تولید ماشینکاری',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 7:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [7,'فورج',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [7,'فورج',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 8:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [8,'تزریق پلاستیک',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [8,'تزریق پلاستیک',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 9:
                    $hs = [9,'لوله'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 10:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [10,'شیلنگ',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [10,'شیلنگ',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 11:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [11,'برش لیزر',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [11,'برش لیزر',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 12:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [12,'کلکتور',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [12,'کلکتور',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 13:
                    $hs = [13,'منسوخ'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 14:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [14,'قطعه مونتاژی',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [14,'قطعه مونتاژی',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
            }
            $finalRes[$y]['btnType'] = ($res[$y]['error'] == 1 ? 'btn-danger' : 'btn-success');
            $finalRes[$y]['disabled'] = ($res[$y]['error'] == 1 ? '' : 'disabled');
            $finalRes[$y]['icon'] = 'fa-exclamation-circle';
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['ChangingHow_supply'] = $txt;
            $finalRes[$y]['priceFinalRawMaterial'] = (intval($res[$y]['priceFinalRawMaterial']) > 0 ? number_format($res[$y]['priceFinalRawMaterial']).' ریال' : '');
            $finalRes[$y]['priceFinalRawMaterialCash'] = (intval($res[$y]['priceFinalRawMaterialCash']) > 0 ? number_format($res[$y]['priceFinalRawMaterialCash']).' ریال' : '');
            $finalRes[$y]['totalPC'] = (intval($res[$y]['priceFinalRawMaterial']) > 0 ? number_format($res[$y]['priceFinalRawMaterial']+intval($res[$y]['TotalCosts'])).' ریال' : '');
            $finalRes[$y]['totalPCC'] = (intval($res[$y]['priceFinalRawMaterialCash']) > 0 ? number_format($res[$y]['priceFinalRawMaterialCash']+intval($res[$y]['TotalCosts'])).' ریال' : '');
        }
        $sendParam = array($finalRes,$changeDate);
        return $sendParam;
    }

    public function getPiecePriceReportListCountRows($pName,$pCode,$supply,$error){
        $db = new DBi();
        $w = array();
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        if(strlen(trim($pCode)) > 0){
            $w[] = '`pCode`="'.$pCode.'" ';
        }
        if(intval($error) >= 0){
            $w[] = '`error`='.$error.' ';
        }
        if (intval($supply) > 0){
            $w[] = '`ChangingHow_supply`='.$supply.' ';
        }else{
            $w[] = '`ChangingHow_supply` IN (1,2,3,4,5,6,7,8,10,11,12,14) ';
        }
        $sql = "SELECT `pName` FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getMPiecePriceReportListCountRows($pName){
        $db = new DBi();
        $w = array();

        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        $w[] = '`ChangingHow_supply` IN (1,2,3,4,5,6,7,8,10,11,12,14) ';

        $sql = "SELECT `RowID` FROM `piece` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql = "SELECT `piece`.`RowID`,`pName`,`pCode`,`FixHow_supply`,`ChangingHow_supply`,`priceFinalRawMaterial`,`priceFinalRawMaterialCash`,`TotalCosts`,`error`
                FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                             LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getBMPiecePriceReportListCountRows(){
        $db = new DBi();

        $sql1 = "SELECT `word` FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` ASC";
        $rst = $db->ArrayQuery($sql1);
        $cntrst = count($rst);
        if ($cntrst == 0){
            $w = array();
            $w[] = '`ChangingHow_supply` IN (1,2,3,4,5,6,7,8,10,11,12,14) ';
            $sql = "SELECT `piece`.`RowID`,`pName`,`pCode`,`FixHow_supply`,`ChangingHow_supply`,`priceFinalRawMaterial`,`priceFinalRawMaterialCash`,`TotalCosts`,`error`
                    FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                 LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)";
            if(count($w)){
                $where = implode(" AND ",$w);
                $sql .= " WHERE ".$where;
            }
            $res = $db->ArrayQuery($sql);
        }else{
            for ($i=0;$i<$cntrst;$i++){
                $w = array();
                $w[] = '`pName` LIKE "%'.$rst[$i]['word'].'%" ';
                $w[] = '`ChangingHow_supply` IN (1,2,3,4,5,6,7,8,10,11,12,14) ';

                $sql3 = "SELECT `RowID` FROM `piece` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
                $res = $db->ArrayQuery($sql3);
                if (count($res) > 0){
                    $w[] = '`uid`='.$_SESSION['userid'].' ';
                }

                $sql = "SELECT `piece`.`RowID`,`pName`,`pCode`,`FixHow_supply`,`ChangingHow_supply`,`priceFinalRawMaterial`,`priceFinalRawMaterialCash`,`TotalCosts`,`error`
                        FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                 LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)";
                if(count($w)){
                    $where = implode(" AND ",$w);
                    $sql .= " WHERE ".$where;
                }
                $res = $db->ArrayQuery($sql);
            }
        }
        return count($res);
    }

/*    public function getPiecePriceReportRowList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('piecePriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;

        $sql = "SELECT `piece`.`RowID`,`pName`,`pCode`,`FixHow_supply`,`ChangingHow_supply`,`priceFinalRawMaterial`,`priceFinalRawMaterialCash`,`TotalCosts`,`error`
                FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                             LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`) WHERE `ChangingHow_supply` IN (1,2,3,4,5,6,7,8,10,11,12,14) AND `showf`=1";

        $sql .= " ORDER BY `piece`.`RowID` ASC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);

        $query = "SELECT `changeDate` FROM `backup_piece` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate = $ut->greg_to_jal($rst[0]['changeDate']);

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            switch ($res[$y]['FixHow_supply']){
                case 0:
                    $txt = 'راکد';
                    break;
                case 1:
                    $txt = 'وارداتی';
                    break;
                case 2:
                    $txt = 'خرید داخلی';
                    break;
                case 3:
                    $txt = 'خرید قطعه ماشینکاری';
                    break;
                case 4:
                    $txt = 'خرید قطعه ریخته گری';
                    break;
                case 5:
                    $txt = 'تولید ریخته گری';
                    break;
                case 6:
                    $txt = 'تولید ماشینکاری';
                    break;
                case 7:
                    $txt = 'فورج';
                    break;
                case 8:
                    $txt = 'تزریق پلاستیک';
                    break;
                case 9:
                    $txt = 'لوله';
                    break;
                case 10:
                    $txt = 'شیلنگ';
                    break;
                case 11:
                    $txt = 'برش لیزر';
                    break;
                case 12:
                    $txt = 'کلکتور';
                    break;
                case 13:
                    $txt = 'منسوخ';
                    break;
                case 14:
                    $txt = 'قطعه مونتاژی';
                    break;
            }

            switch ($res[$y]['ChangingHow_supply']){
                case 0:
                    $hs = [0,'راکد'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 1:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [1,'وارداتی',$res[$y]['FixHow_supply'],$txt,2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 2:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [2,'خرید داخلی',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [2,'خرید داخلی',1,'وارداتی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 3:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [3,'خرید قطعه ماشینکاری',$res[$y]['FixHow_supply'],$txt,2,'خرید داخلی',1,'وارداتی',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 4:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [4,'خرید قطعه ریخته گری',$res[$y]['FixHow_supply'],$txt,3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی'];
                    }else{
                        $hs = [4,'خرید قطعه ریخته گری',3,'خرید قطعه ماشینکاری',2,'خرید داخلی',1,'وارداتی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 5:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [5,'تولید ریخته گری',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }else{
                        $hs = [5,'تولید ریخته گری',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری',4,'خرید قطعه ریخته گری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 6:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [6,'تولید ماشینکاری',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [6,'تولید ماشینکاری',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 7:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [7,'فورج',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [7,'فورج',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 8:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [8,'تزریق پلاستیک',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [8,'تزریق پلاستیک',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 9:
                    $hs = [9,'لوله'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 10:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [10,'شیلنگ',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [10,'شیلنگ',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 11:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [11,'برش لیزر',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [11,'برش لیزر',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 12:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [12,'کلکتور',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }else{
                        $hs = [12,'کلکتور',1,'وارداتی',2,'خرید داخلی',3,'خرید قطعه ماشینکاری'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 13:
                    $hs = [13,'منسوخ'];
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
                case 14:
                    if ($res[$y]['ChangingHow_supply'] != $res[$y]['FixHow_supply']){
                        $hs = [14,'قطعه مونتاژی',$res[$y]['FixHow_supply'],$txt,1,'وارداتی',2,'خرید داخلی'];
                    }else{
                        $hs = [14,'قطعه مونتاژی',1,'وارداتی',2,'خرید داخلی'];
                    }
                    $res[$y]['ChangingHow_supply'] = $hs;
                    break;
            }
            $finalRes[$y]['btnType'] = ($res[$y]['error'] == 1 ? 'btn-danger' : 'btn-success');
            $finalRes[$y]['disabled'] = ($res[$y]['error'] == 1 ? '' : 'disabled');
            $finalRes[$y]['icon'] = 'fa-exclamation-circle';
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['ChangingHow_supply'] = $txt;
            $finalRes[$y]['priceFinalRawMaterial'] = (intval($res[$y]['priceFinalRawMaterial']) > 0 ? number_format($res[$y]['priceFinalRawMaterial']).' ریال' : '');
            $finalRes[$y]['priceFinalRawMaterialCash'] = (intval($res[$y]['priceFinalRawMaterialCash']) > 0 ? number_format($res[$y]['priceFinalRawMaterialCash']).' ریال' : '');
            $finalRes[$y]['totalPC'] = (intval($res[$y]['priceFinalRawMaterial']) > 0 ? number_format($res[$y]['priceFinalRawMaterial']+intval($res[$y]['TotalCosts'])).' ریال' : '');
            $finalRes[$y]['totalPCC'] = (intval($res[$y]['priceFinalRawMaterialCash']) > 0 ? number_format($res[$y]['priceFinalRawMaterialCash']+intval($res[$y]['TotalCosts'])).' ریال' : '');
        }
        $sendParam = array($finalRes,$changeDate);
        return $sendParam;
    }

    public function getPiecePriceReportRowListCountRows(){
        $db = new DBi();
        $sql = "SELECT `pName` FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`) WHERE `ChangingHow_supply` IN (1,2,3,4,5,6,7,8,10,11,12,14) AND `showf`=1";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }*/

    public function updatePiecePrice($outdb=NULL){
        $acm = new acm();
        if(!$acm->hasAccess('procurementAccess')) {
            if (!$acm->hasAccess('piecePriceReportManage')) {
                die("access denied");
                exit;
            }
        }
        if($outdb == NULL){
            $db = new DBi();
            mysqli_autocommit($db->Getcon(),FALSE);
        }else{
            $db = $outdb;
        }
        $ut = new Utility();

        $qd = "SELECT `changeDate` FROM `backup_brass_weight` ORDER BY `RowID` DESC LIMIT 1";
        $rqd = $db->ArrayQuery($qd);
        $effectiveDate = date('Y-m-d', strtotime($rqd[0]['changeDate'] . "+3 months"));
        //$ut->fileRecorder($effectiveDate);
       // $ut->fileRecorder(date('Y-m-d'));
        // if (date('Y-m-d') > $effectiveDate){
        //     return -1;
        // }
        $sqq = "SELECT `RowID` FROM `brass_weight`";
        $rsqq = $db->ArrayQuery($sqq);
        if (count($rsqq) > 0){
            $flag = true;

            $qq = "SELECT `brassSwarfPrice`,`BullionPriceUp14`,`BullionPriceUnder14`,`BullionPriceColector`,`CastingPrice`,`PercentFuelWeight`,`PolishingSoilPrice` FROM `brass_weight`";  // اطلاعات بار برنج
            $rsqq = $db->ArrayQuery($qq);

            $bsp = $rsqq[0]['brassSwarfPrice'] / 1000;  // قیمت براده برنج
            $BPUp14 = $rsqq[0]['BullionPriceUp14'] / 1000;  // اجرت شمش بالای 14
            $BPUn14 = $rsqq[0]['BullionPriceUnder14'] / 1000;  // اجرت شمش زیر 14
            $BPC = $rsqq[0]['BullionPriceColector'] / 1000;  // اجرت شمش کلکتور
            $CP = $rsqq[0]['CastingPrice'] / 1000;  // اجرت ریخته گری
            $PSP = $rsqq[0]['PolishingSoilPrice'] / 1000;  // قیمت خاک پرداخت
            $PFW = $rsqq[0]['PercentFuelWeight'] * 0.01;  // درصد سوخت بار
            $PFW = ($PFW/(1-$PFW));  // درصد سوخت بار

            $qqq = "SELECT * FROM `wastage`";
            $rqqq = $db->ArrayQuery($qqq);
            $rqqq[0]['wCasting']  = $rqqq[0]['wCasting']/100;
            $rqqq[0]['wMachining']  = $rqqq[0]['wMachining']/100;
            $rqqq[0]['wPolishing']  = $rqqq[0]['wPolishing']/100;
            $rqqq[0]['wMachiningChips']  = $rqqq[0]['wMachiningChips']/100;
            $rqqq[0]['wPolishingSoil']  = $rqqq[0]['wPolishingSoil']/100;

            $sql = "SELECT `piece`.`RowID` AS `pieceID`,`ChangingHow_supply`,`Weight_materials`,`Weight_Machining`,`Weight_Final`,
                           `TypeBullion`,`material`,`CastingPriceBC`,`PercentFuelWeightBC`,`CastMachPriceBM`,`PercentFuelWeightBM`,
                           `currencyID`,`currency_amount`,`Percentage_additional_costs`,`priceDakheli`,`PercentageACD`,pCode,
                           `Forging_timing`,`Machining_timing`,`Polishing_timing`,`Plating_timing`,`Paint_timing`,`PVD_timing`,
                           `Hose_timing`,`Pipe_timing`,`PlasticInjection_timing`,`monthkickback`,`priceCatchDate`,`plasticPlate`,`PercentagePP`,`referenceECode`
                        FROM `piece_masterlist` 
                        INNER JOIN `piece` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                        LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)
                        WHERE `ChangingHow_supply` IN (0,1,2,3,4,5,6,7,8,10,11,12,13)";
            $res = $db->ArrayQuery($sql);
            $cnt = count($res);
            //$ut->fileRecorder(print_r($res,true));

            $qaday = "SELECT `AvailableDays` FROM `available_days` ORDER BY `RowID` DESC LIMIT 1";
            $rstd = $db->ArrayQuery($qaday);
            $fixedPrice = ($rstd[0]['AvailableDays'] * 7.33);
            $fixedPrice = $fixedPrice * 3600;

            $usql = "SELECT `RowID`,`efficiency` FROM `official_productive_units` WHERE `RowID` IN (11,12,13,14,15,16,17,18,19) ORDER BY `RowID` ASC";
            $rstu = $db->ArrayQuery($usql);

            $pers = "SELECT `TotalCosts`,`Unit_id` FROM `personnel` WHERE `Unit_id` IN (11,12,13,14,15,16,17,18,19)";
            $rpers = $db->ArrayQuery($pers);
            $cpers = count($rpers);
            $forjCost = 0;
            $forjNum = 0;
            $machCost = 0;
            $machNum = 0;
            $polishCost = 0;
            $polishNum = 0;
            $plateCost = 0;
            $plateNum = 0;
            $pvdCost = 0;
            $pvdNum = 0;
            $hoseCost = 0;
            $hoseNum = 0;
            $pipeCost = 0;
            $pipeNum = 0;
            $plasticCost = 0;
            $plasticNum = 0;
            $montageCost = 0;
            $montageNum = 0;
            for ($o=0;$o<$cpers;$o++){
                switch ($rpers[$o]['Unit_id']){
                    case 19:
                        $forjNum++;
                        $forjCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 18:
                        $machNum++;
                        $machCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 15:
                        $polishNum++;
                        $polishCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 14:
                        $plateNum++;
                        $plateCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 16:
                        $pvdNum++;
                        $pvdCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 13:
                        $hoseNum++;
                        $hoseCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 11:
                        $pipeNum++;
                        $pipeCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 17:
                        $plasticNum++;
                        $plasticCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 12:
                        $montageNum++;
                        $montageCost += $rpers[$o]['TotalCosts'];
                        break;
                }
            } //for
            $forjCost = ($forjCost/$fixedPrice)/$forjNum;  // هزینه هر نفر ثانیه واحد فورج
            $machCost = ($machCost/$fixedPrice)/$machNum;  // هزینه هر نفر ثانیه واحد ماشینکاری
            $polishCost = ($polishCost/$fixedPrice)/$polishNum;  // هزینه هر نفر ثانیه واحد پرداخت
            $paintCost = $polishCost;  // هزینه هر نفر ثانیه واحد رنگ
            $plateCost = ($plateCost / $fixedPrice) / $plateNum;  // هزینه هر نفر ثانیه واحد آبکاری
            $pvdCost = ($pvdCost/$fixedPrice)/$pvdNum;  // هزینه هر نفر ثانیه واحد پی وی دی
            $hoseCost = ($hoseCost/$fixedPrice)/$hoseNum;  // هزینه هر نفر ثانیه واحد شیلنگ
            $pipeCost = ($pipeCost/$fixedPrice)/$pipeNum;  // هزینه هر نفر ثانیه واحد لوله
            $plasticCost = ($plasticCost/$fixedPrice)/$plasticNum;  // هزینه هر نفر ثانیه واحد پلاستیک
            $montageCost = ($montageCost/$fixedPrice)/$montageNum;  // هزینه هر نفر ثانیه واحد مونتاژ

            for ($i=0;$i<$cnt;$i++){
                $bt = $res[$i]['TypeBullion']; // نوع شمش
                $fsc = $res[$i]['ChangingHow_supply']; // روش محاسبات
                $wm = $res[$i]['Weight_materials'];  // وزن اولیه
                $wmch = $res[$i]['Weight_Machining'];  // وزن ماشینکاری
                $wf = $res[$i]['Weight_Final'];  // وزن نهایی

                $pkh = 'NULL';
                $pm = 'NULL';
                $pp = 'NULL';
                $pe = 'NULL';
                $pen = 'NULL';
                $error = 0;
                $errorTxt = '';
                $errorArr = array();

                if ($fsc == 0){
                    $error = 1;
                    $errorTxt = 'قطعه راکد شده است !';
                }

                if ($fsc == 13){
                    $error = 1;
                    $errorTxt = 'قطعه منسوخ شده است !';
                }

                if ($fsc == 1){  // خرید خارجی
                    if (strlen(trim($res[$i]['referenceECode']))  > 0){
                        $sqlr = "SELECT `pName`,`currency_amount`,`Percentage_additional_costs`,`priceCatchDate`,`currencyID`
                                 FROM `piece_masterlist`
                                 INNER JOIN `piece` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                 WHERE `pCode`='{$res[$i]['referenceECode']}'";
                        $res11 = $db->ArrayQuery($sqlr);
                        if (intval($res11[0]['currencyID'])  == 0){
                            $pkh = 'NULL';
                            $error = 1;
                            $errorTxt = 'قیمت خرید خارجی '.$res11[0]['pName'].' مشخص نشده است !';
                        }else{
                            $qv = "SELECT `dayRate` FROM `currency` WHERE `RowID`={$res11[0]['currencyID']}";
                            $resv = $db->ArrayQuery($qv);
                            if (intval(strtotime($res11[0]['priceCatchDate'])) > 0){
                                $effectiveDate = date('Y-m-d', strtotime($res11[0]['priceCatchDate'] . "+3 months"));
                                if (date('Y-m-d') > $effectiveDate){
                                    $error = 1;
                                    $errorTxt = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                                }
                            }else{
                                $error = 1;
                                $errorTxt = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                            }
                            $pkh = (($res11[0]['currency_amount'] * $resv[0]['dayRate']) * (100 + floatval($res11[0]['Percentage_additional_costs']))) / 100;
                        }
                    }else {
                        if (intval($res[$i]['currencyID']) == 0) {
                            $pkh = 'NULL';
                            $error = 1;
                            $errorTxt = $this->errorPriceKhareji;
                        } else {
                            $qv = "SELECT `dayRate` FROM `currency` WHERE `RowID`={$res[$i]['currencyID']}";
                            $resv = $db->ArrayQuery($qv);
                            if (intval(strtotime($res[$i]['priceCatchDate'])) > 0) {
                                $effectiveDate = date('Y-m-d', strtotime($res[$i]['priceCatchDate'] . "+3 months"));
                                if (date('Y-m-d') > $effectiveDate) {
                                    $error = 1;
                                    $errorTxt = $this->errorPriceDate;
                                }
                            } else {
                                $error = 1;
                                $errorTxt = $this->errorPriceDate;
                            }
                            $pkh = (($res[$i]['currency_amount'] * $resv[0]['dayRate']) * (100 + floatval($res[$i]['Percentage_additional_costs']))) / 100;
                        }
                    }
                    $pe = $pkh;
                }

                if ($fsc == 2){  // خرید داخلی
                    if (strlen(trim($res[$i]['referenceECode']))  > 0){
                        $sqlr = "SELECT `pName`,`priceDakheli`,`PercentageACD`,`priceCatchDate`,`monthkickback` FROM `piece` WHERE `pCode`='{$res[$i]['referenceECode']}'";
                        $res11 = $db->ArrayQuery($sqlr);
                        if (intval($res11[0]['priceDakheli'])  == 0){
                            $pkh = 'NULL';
                            $error = 1;
                            $errorTxt = 'قیمت خرید داخلی '.$res11[0]['pName'].' مشخص نشده است !';
                        }else{
                            if (intval(strtotime($res11[0]['priceCatchDate'])) > 0){
                                $effectiveDate = date('Y-m-d', strtotime($res11[0]['priceCatchDate'] . "+3 months"));
                                if (date('Y-m-d') > $effectiveDate){
                                    $error = 1;
                                    $errorTxt = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                                }
                            }else{
                                $error = 1;
                                $errorTxt = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                            }
                            $pkh = (($res11[0]['priceDakheli']) * (100 + floatval($res11[0]['PercentageACD']))) / 100;
                            $pen = ($pkh == 0 ? 'NULL' : (($pkh * (100 - ($res11[0]['monthkickback'] * $rqqq[0]['Scount'])))/100));
                        }
                    }else {
                        
                        if (intval($res[$i]['pieceID']) == 2944){
                            //$ut->fileRecorder(intval($res[$i]['priceDakheli']));
                        }
                        if (intval($res[$i]['priceDakheli']) == 0) {
                            $pkh = 'NULL';
                            $error = 1;
                            $errorTxt = $this->errorPriceDakheli;
                        } else {
                            if (intval(strtotime($res[$i]['priceCatchDate'])) > 0) {
                                $effectiveDate = date('Y-m-d', strtotime($res[$i]['priceCatchDate'] . "+3 months"));
                                if (date('Y-m-d') > $effectiveDate) {
                                    $error = 1;
                                    $errorTxt = $this->errorPriceDate;
                                }
                            } else {
                                $error = 1;
                                $errorTxt = $this->errorPriceDate;
                            }
                            $pkh = (($res[$i]['priceDakheli']) * (100 + floatval($res[$i]['PercentageACD']))) / 100;
                            $pen = ($pkh == 0 ? 'NULL' : (($pkh * (100 - ($res[$i]['monthkickback'] * $rqqq[0]['Scount'])))/100));
                        }
                    }
                    $pe = $pkh;
                }

                if ($fsc == 3){  // خرید قطعه ماشینکاری
                    if (strlen(trim($res[$i]['referenceECode']))  > 0){
                        $sqlr = "SELECT `pName`,`CastMachPriceBM`,`PercentFuelWeightBM`,`priceCatchDate` FROM `piece` WHERE `pCode`='{$res[$i]['referenceECode']}'";
                        $res11 = $db->ArrayQuery($sqlr);
                        if (intval($res11[0]['CastMachPriceBM'])  == 0){
                            $error = 1;
                            $errorArr[] = 'قیمت خرید قطعه ماشینکاری '.$res11[0]['pName'].' مشخص نشده است !';
                        }else{
                            if (intval(strtotime($res11[0]['priceCatchDate'])) > 0){
                                $effectiveDate = date('Y-m-d', strtotime($res11[0]['priceCatchDate'] . "+3 months"));
                                if (date('Y-m-d') > $effectiveDate){
                                    $error = 1;
                                    $errorArr[] = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                                }
                            }else{
                                $error = 1;
                                $errorArr[] = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                            }
                            //$res11[0]['CastMachPriceBM'] = $res11[0]['CastMachPriceBM'] / 1000;
                            $PFWP = floatval($res11[0]['PercentFuelWeightBM']) * 0.01;  // درصد سوخت بار
                            $PFWP = ($PFWP/(1-$PFWP));  // درصد سوخت بار
                            $pm = ($wmch * $bsp) + ($bsp * $PFWP) + $res11[0]['CastMachPriceBM']; // قیمت ماشینکاری
                            if ($pm < $res11[0]['CastMachPriceBM']){
                                $error = 1;
                                $errorArr[] = 'قیمت به دست آمده معتبر نمی باشد.';
                            }
                            $pp = ($wmch == $wf ? 'NULL' : (($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing'])));
                            $pe = ($pp == 'NULL' ? $pm : $pp);
                        }
                    }else {
                        if (intval($res[$i]['CastMachPriceBM']) == 0) {
                            $error = 1;
                            $errorArr[] = $this->errorPriceKGM;
                        } else {
                            if (intval(strtotime($res[$i]['priceCatchDate'])) > 0) {
                                $effectiveDate = date('Y-m-d', strtotime($res[$i]['priceCatchDate'] . "+3 months"));
                                if (date('Y-m-d') > $effectiveDate) {
                                    $error = 1;
                                    $errorArr[] = $this->errorPriceDate;
                                }
                            } else {
                                $error = 1;
                                $errorArr[] = $this->errorPriceDate;
                            }
                            //$res[$i]['CastMachPriceBM'] = $res[$i]['CastMachPriceBM'] / 1000;
                            $PFWP = floatval($res[$i]['PercentFuelWeightBM']) * 0.01;  // درصد سوخت بار
                            $PFWP = ($PFWP / (1 - $PFWP));  // درصد سوخت بار
                            $pm = ($wmch * $bsp) + ($bsp * $PFWP) + $res[$i]['CastMachPriceBM']; // قیمت ماشینکاری
                            if ($pm < $res[$i]['CastMachPriceBM']){
                                $error = 1;
                                $errorArr[] = 'قیمت به دست آمده معتبر نمی باشد.';
                            }
                            $pp = ($wmch == $wf ? 'NULL' : (($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing'])));
                            $pe = ($pp == 'NULL' ? $pm : $pp);
                        }
                    }
                    if ($wmch == 0 || strlen(trim($wmch)) == 0){
                        $error = 1;
                        $errorArr[] = 'وزن ماشینکاری قطعه نامشخص است !';
                    }
                    if ($wf == 0 || strlen(trim($wf)) == 0){
                        $error = 1;
                        $errorArr[] = 'وزن نهایی قطعه نامشخص است !';
                    }
                    $errorTxt = implode(' - ',$errorArr);
                }

                if ($fsc == 4){  // خرید قطعه ریخته گری
                    if (strlen(trim($res[$i]['referenceECode']))  > 0) {
                        $sqlr = "SELECT `pName`,`CastingPriceBC`,`PercentFuelWeightBC`,`priceCatchDate` FROM `piece` WHERE `pCode`='{$res[$i]['referenceECode']}'";
                        $res11 = $db->ArrayQuery($sqlr);
                        if (intval($res11[0]['CastingPriceBC']) == 0) {
                            $error = 1;
                            $errorArr[] = 'قیمت خرید قطعه ریخته گری '.$res11[0]['pName'].' مشخص نشده است !';
                        } else {
                            if (intval(strtotime($res11[0]['priceCatchDate'])) > 0) {
                                $effectiveDate = date('Y-m-d', strtotime($res11[0]['priceCatchDate'] . "+3 months"));
                                if (date('Y-m-d') > $effectiveDate) {
                                    $error = 1;
                                    $errorArr[] = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                                }
                            } else {
                                $error = 1;
                                $errorArr[] = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                            }

                            $res11[0]['CastingPriceBC'] = $res11[0]['CastingPriceBC'] / 1000;
                            $PFWC = floatval($res11[0]['PercentFuelWeightBC']) * 0.01;  // درصد سوخت بار
                            $PFWC = ($PFWC / (1 - $PFWC));  // درصد سوخت بار
                            $pkh = $wm * ($bsp + $res11[0]['CastingPriceBC'] + ($bsp * $PFWC));  // قیمت قطعه خام
                            $pm = ($pkh - (($wm - $wmch) * $bsp) + ($rqqq[0]['wMachiningChips'] * ($wm - $wmch) * $bsp)) * (1 + $rqqq[0]['wMachining']); // قیمت ماشینکاری
                            $pp = ($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing']);  // قیمت پرداخت
                            $pe = $pp;
                        }
                    }else {
                        if (intval($res[$i]['CastingPriceBC']) == 0) {
                            $error = 1;
                            $errorArr[] = $this->errorPriceKGR;
                        } else {
                            if (intval(strtotime($res[$i]['priceCatchDate'])) > 0) {
                                $effectiveDate = date('Y-m-d', strtotime($res[$i]['priceCatchDate'] . "+3 months"));
                                if (date('Y-m-d') > $effectiveDate) {
                                    $error = 1;
                                    $errorArr[] = $this->errorPriceDate;
                                }
                            } else {
                                $error = 1;
                                $errorArr[] = $this->errorPriceDate;
                            }
                            $res[$i]['CastingPriceBC'] = $res[$i]['CastingPriceBC'] / 1000;
                            $PFWC = floatval($res[$i]['PercentFuelWeightBC']) * 0.01;  // درصد سوخت بار
                            $PFWC = ($PFWC / (1 - $PFWC));  // درصد سوخت بار
                            $pkh = $wm * ($bsp + $res[$i]['CastingPriceBC'] + ($bsp * $PFWC));  // قیمت قطعه خام
                            $pm = ($pkh - (($wm - $wmch) * $bsp) + ($rqqq[0]['wMachiningChips'] * ($wm - $wmch) * $bsp)) * (1 + $rqqq[0]['wMachining']); // قیمت ماشینکاری
                            $pp = ($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing']);  // قیمت پرداخت
                            $pe = $pp;
                        }
                    }
                    if ($wm == 0 || strlen(trim($wm)) == 0){
                        $error = 1;
                        $errorArr[] = 'وزن اولیه قطعه نامشخص است !';
                    }
                    if ($wmch == 0 || strlen(trim($wmch)) == 0){
                        $error = 1;
                        $errorArr[] = 'وزن ماشینکاری قطعه نامشخص است !';
                    }
                    if ($wf == 0 || strlen(trim($wf)) == 0){
                        $error = 1;
                        $errorArr[] = 'وزن نهایی قطعه نامشخص است !';
                    }
                    $errorTxt = implode(' - ',$errorArr);
                }

                if ($fsc == 5){  // تولید ریخته گری
                    $pkh = ($wm * ($bsp + $CP + ($bsp * $PFW))) * (1 + $rqqq[0]['wCasting']); // قیمت قطعه خام
                    $pm = ($pkh - (($wm - $wmch) * $bsp) + ($rqqq[0]['wMachiningChips'] * ($wm - $wmch) * $bsp)) * (1 + $rqqq[0]['wMachining']);  // قیمت ماشینکاری
                    $pp = ($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing']);  // قیمت پرداخت
                    $pe = $pp;
                    if ($wm == 0 || strlen(trim($wm)) == 0){
                        $error = 1;
                        $errorArr[] = 'وزن اولیه قطعه نامشخص است !';
                    }
                    if ($wmch == 0 || strlen(trim($wmch)) == 0){
                        $error = 1;
                        $errorArr[] = 'وزن ماشینکاری قطعه نامشخص است !';
                    }
                    if ($wf == 0 || strlen(trim($wf)) == 0){
                        $error = 1;
                        $errorArr[] = 'وزن نهایی قطعه نامشخص است !';
                    }
                    $errorTxt = implode(' - ',$errorArr);
                }

                if ($fsc == 6 || $fsc == 7 || $fsc == 12){  // تولید ماشینکاری و فورج و کلکتور
                    switch ($bt){
                        case 0: // قطر زیر 14
                            $but = $BPUn14;
                            break;
                        case 1:  // قطر بالای 14
                            $but = $BPUp14;
                            break;
                        case 2:  // کلکتور
                            $but = $BPC;
                            break;
                        default:
                            $but = 0;
                            $wm = 0;
                            $wmch = 0;
                            $wf = 0;
                    }
                    $pkh = ($wm * ($bsp + $but));  // قیمت قطعه خام
                    $pm = ($pkh - (($wm - $wmch) * $bsp) + ($rqqq[0]['wMachiningChips'] * ($wm - $wmch) * $bsp)) * (1 + $rqqq[0]['wMachining']);  // قیمت ماشینکاری
                    $pp = ($wmch == $wf ? 'NULL' : (($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing'])));  // قیمت پرداخت
                    $pe = ($pp == 'NULL' ? $pm : $pp);
                    if ($wm == 0 || strlen(trim($wm)) == 0){
                        $error = 1;
                        $errorArr[] = 'وزن اولیه قطعه نامشخص است !';
                    }
                    if ($wmch == 0 || strlen(trim($wmch)) == 0){
                        $error = 1;
                        $errorArr[] = 'وزن ماشینکاری قطعه نامشخص است !';
                    }
                    if ($fsc == 6){
                        if ($wf == 0 || strlen(trim($wf)) == 0){
                            $error = 1;
                            $errorArr[] = 'وزن نهایی قطعه نامشخص است !';
                        }
                    }
                    $errorTxt = implode(' - ',$errorArr);
                }

                if ($fsc == 8 || $fsc == 10 || $fsc == 11){   // تزریق پلاستیک و شیلنگ و برش لیزر
                    $query = "SELECT `ChangingHow_supply`,`currencyID`,`currency_amount`,`Percentage_additional_costs`,
                                     `priceDakheli`,`pName`,`monthkickback`,`PercentageACD`,`priceCatchDate`
                              FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`) WHERE `pCode`='{$res[$i]['material']}'";
                    $result = $db->ArrayQuery($query);

                    if ($result[0]['ChangingHow_supply']  == 1){  // قطعه مرجع خرید خارجی بود
                        $qv = "SELECT `dayRate` FROM `currency` WHERE `RowID`={$result[0]['currencyID']}";
                        $resv = $db->ArrayQuery($qv);
                        if (intval($result[0]['currencyID']) == 0){
                            $peMaterial = 0;
                        }else{
                            $peMaterial = (($result[0]['currency_amount'] * $resv[0]['dayRate']) * (100 + floatval($result[0]['Percentage_additional_costs']))) / 100;
                        }
                        $penMaterial = $peMaterial;
                    }elseif ($result[0]['ChangingHow_supply']  == 2){  // قطعه مرجع خرید داخلی بود
                        $peMaterial = ((floatval($result[0]['priceDakheli'])) * (100 + floatval($result[0]['PercentageACD']))) / 100;
                        $penMaterial = (intval($peMaterial) == 0 ? 0 : (($peMaterial * (100 - ($result[0]['monthkickback'] * $rqqq[0]['Scount'])))/100));
                    }

                    if (intval($peMaterial)  == 0){
                        $pkh = 'NULL';
                        $pe = 'NULL';
                        $pen = 'NULL';
                        $error = 1;
                        $errorArr[] = 'قیمت '.$result[0]['pName'].' مشخص نشده است !';   // یعنی اینکه قیمت قطعه مرجع مشخص نشده است
                    }else{
                        if (intval(strtotime($result[0]['priceCatchDate'])) > 0){  // چک کردن انقضای ماده خام تزریق پلاستیک
                            $effectiveDate = date('Y-m-d', strtotime($result[0]['priceCatchDate'] . "+3 months"));
                            if (date('Y-m-d') > $effectiveDate){
                                $error = 1;
                                $errorArr[] = 'تاریخ قیمت '.$result[0]['pName'].' منقضی شده است !';
                            }
                        }else{
                            $error = 1;
                            $errorArr[] = 'تاریخ قیمت '.$result[0]['pName'].' منقضی شده است !';
                        }
                        if ($fsc == 8) {  // تزریق پلاستیک
                            $sqlab = "SELECT `status` FROM `abkari` WHERE `pCode`='{$res[$i]['pCode']}'";
                            $rstab = $db->ArrayQuery($sqlab);
                            if (intval($rstab[0]['status']) == 1 && (intval($res[$i]['plasticPlate']) == 0 || strlen(trim($res[$i]['plasticPlate'])) == 0)){
                                $error = 1;
                                $errorArr[] = 'هزینه آب کاری مشخص نشده است !';
                            }

                            $pkh = $wm * (1 - ($wmch/200)) * ($peMaterial/1000);
                            $pkhn = $wm * (1 - ($wmch/200)) * ($penMaterial/1000);
                            if (strlen(trim($res[$i]['referenceECode'])) > 0) {
                                $sqlr = "SELECT `pName`,`plasticPlate`,`PercentagePP`,`priceCatchDate` FROM `piece` WHERE `pCode`='{$res[$i]['referenceECode']}'";
                                $res11 = $db->ArrayQuery($sqlr);
                                if (intval(strtotime($res11[0]['priceCatchDate'])) > 0) {  // چک کردن انقضای تاریخ قطعه مرجع
                                    $effectiveDate = date('Y-m-d', strtotime($res11[0]['priceCatchDate'] . "+3 months"));
                                    if (date('Y-m-d') > $effectiveDate) {
                                        $error = 1;
                                        $errorArr[] = 'تاریخ قیمت ' . $res11[0]['pName'] . ' منقضی شده است !';
                                    }
                                }
                                $pe = ($pkh - ($wm - $wf) * (($peMaterial / 1000) / 2)) + ($res11[0]['plasticPlate'] * (1 + ($res11[0]['PercentagePP'] / 100)));
                                $pen = ($pkhn - ($wm - $wf) * (($penMaterial / 1000) / 2)) + ($res11[0]['plasticPlate'] * (1 + ($res11[0]['PercentagePP'] / 100)));
                            } else {
                                if (intval(strtotime($res[$i]['priceCatchDate'])) > 0) {  // چک کردن انقضای تاریخ خود قطعه
                                    $effectiveDate = date('Y-m-d', strtotime($res[$i]['priceCatchDate'] . "+3 months"));
                                    if (date('Y-m-d') > $effectiveDate) {
                                        $error = 1;
                                        $errorArr[] = $this->errorPriceDate;
                                    }
                                }
                                $pe = ($pkh - ($wm - $wf) * (($peMaterial / 1000) / 2)) + ($res[$i]['plasticPlate'] * (1 + ($res[$i]['PercentagePP'] / 100)));
                                $pen = ($pkhn - ($wm - $wf) * (($penMaterial / 1000) / 2)) + ($res[$i]['plasticPlate'] * (1 + ($res[$i]['PercentagePP'] / 100)));
                            }
                        }else{
                            $pkh = ($wf * $peMaterial) / 1000;
                            $pkhn = ($wf * $penMaterial) / 1000;
                            $pe = $pkh;
                            $pen = $pkhn;
                        }
                    }
                    if (($pe == 0 || $pe == 'NULL')){
                        $error = 1;
                        $errorArr[] = 'وزن قطعه نامشخص است !';
                    }
                    $errorTxt = implode(' - ',$errorArr);
                }

                $pen = ($pen == 'NULL' ? $pe : $pen);
                $Usql = "UPDATE `piece` SET `priceRawPiece`={$pkh},`priceMachining`={$pm},`pricePolishing`={$pp},`priceFinalRawMaterial`={$pe},`priceFinalRawMaterialCash`={$pen},`error`={$error},`errorText`='{$errorTxt}' WHERE `RowID`={$res[$i]['pieceID']}";
                $db->Query($Usql);
                $aff = $db->AffectedRows();
                $aff = ((intval($aff) == -1) ? 0 : 1);

                $fcost = (floatval($res[$i]['Forging_timing']) > 0 ? ((($res[$i]['Forging_timing'] * $forjCost)*(100+(100-$rstu[8]['efficiency'])))/100) : 'NULL');
                $mcost = (floatval($res[$i]['Machining_timing']) > 0 ? ((($res[$i]['Machining_timing'] * $machCost)*(100+(100-$rstu[7]['efficiency'])))/100) : 'NULL');
                $pcost = (floatval($res[$i]['Polishing_timing']) > 0 ? ((($res[$i]['Polishing_timing'] * $polishCost)*(100+(100-$rstu[4]['efficiency'])))/100) : 'NULL');
                $plcost = (floatval($res[$i]['Plating_timing']) > 0 ? ((($res[$i]['Plating_timing'] * $plateCost)*(100+(100-$rstu[3]['efficiency'])))/100) : 'NULL');
                $pacost = (floatval($res[$i]['Paint_timing']) > 0 ? ((($res[$i]['Paint_timing'] * $paintCost)*(100+(100-$rstu[4]['efficiency'])))/100) : 'NULL');
                $pvcost = (floatval($res[$i]['PVD_timing']) > 0 ? ((($res[$i]['PVD_timing'] * $pvdCost)*(100+(100-$rstu[5]['efficiency'])))/100) : 'NULL');
                $hcost = (floatval($res[$i]['Hose_timing']) > 0 ? ((($res[$i]['Hose_timing'] * $hoseCost)*(100+(100-$rstu[2]['efficiency'])))/100) : 'NULL');
                $picost = (floatval($res[$i]['Pipe_timing']) > 0 ? ((($res[$i]['Pipe_timing'] * $pipeCost)*(100+(100-$rstu[0]['efficiency'])))/100) : 'NULL');
                $placost = (floatval($res[$i]['PlasticInjection_timing']) > 0 ? ((($res[$i]['PlasticInjection_timing'] * $plasticCost)*(100+(100-$rstu[6]['efficiency'])))/100) : 'NULL');
                $totalMCost = (($fcost + $mcost) == 0 ? 'NULL' : ($fcost + $mcost));
                $totalCosts = (($fcost + $mcost + $pcost + $plcost + $pacost + $pvcost + $hcost + $picost + $placost) == 0 ? 'NULL' : ($fcost + $mcost + $pcost + $plcost + $pacost + $pvcost + $hcost + $picost + $placost));  // مجموع هزینه ها

                $upCost = "UPDATE `piece_timing` SET `costMachining`={$totalMCost},`costPolishing`={$pcost},`costPlating`={$plcost},
                           `costPainting`={$pacost},`costPVD`={$pvcost},`costPlastic`={$placost},`costHose`={$hcost},`costPipe`={$picost},`TotalCosts`={$totalCosts} 
                           WHERE `pid`={$res[$i]['pieceID']}";
                $db->Query($upCost);
                $aff1 = $db->AffectedRows();
                $aff1 = ((intval($aff1) == -1) ? 0 : 1);
                if ($aff == 0 || $aff1 == 0){
                    $flag = false;
                }
            }  // For

            //************** قطعات مونتاژی **************

            $sqlm = "SELECT `pid`,`montageCode`,`Montage_timing` 
                     FROM `piece_masterlist` INNER JOIN `good` ON (`piece_masterlist`.`montageCode`=`good`.`gCode`)
                     WHERE `ChangingHow_supply`=14";
            $resm = $db->ArrayQuery($sqlm);
            $cntm = count($resm);

            $pkh = 'NULL';
            $pm = 'NULL';
            $pp = 'NULL';
            for ($j=0;$j<$cntm;$j++){
                $error = 0;
                $errorTxt = '';
                $mocost = (floatval($resm[$j]['Montage_timing']) > 0 ? ((($resm[$j]['Montage_timing'] * $montageCost)*(100+(100-$rstu[1]['efficiency'])))/100) : 'NULL');
                $querym = "SELECT SUM(`priceFinalRawMaterial` * `amount`) AS `pfrm`,SUM(`priceFinalRawMaterialCash` * `amount`) AS `pfrmc`,
                                  SUM(`TotalCosts`) AS `tc`,SUM(`piece`.`error`) AS `perr`
                           FROM `interface` 
                           LEFT JOIN `piece` ON (`interface`.`PieceCode`=`piece`.`pCode`)
                           LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`) 
                           WHERE `ProductCode`='{$resm[$j]['montageCode']}'";
                $resultm = $db->ArrayQuery($querym);

                if (intval($resultm[0]['perr']) > 0){
                    $errors = array();
                    $qerror = "SELECT `error` FROM `good` WHERE `gCode`='{$resm[$j]['montageCode']}'";
                    $rerror = $db->ArrayQuery($qerror);
                    $rids = $rerror[0]['error'];
                    $qperror = "SELECT `pName`,`errorText` FROM `piece` WHERE `RowID` IN (".$rids.")";
                    $rstqr = $db->ArrayQuery($qperror);
                    $cntr = count($rstqr);
                    for ($e=0;$e<$cntr;$e++){
                        $errors[] = $rstqr[$e]['pName'].' : '.$rstqr[$e]['errorText'];
                    }
                    $rstqr = implode(' \n\n ',$errors);

                    $error = 1;
                    $errorTxt = $rstqr;
                }

                $TC = $mocost + $resultm[0]['tc'];
                $pe = (intval($resultm[0]['pfrm']) > 0 ? $resultm[0]['pfrm'] : 'NULL');
                $pen = (intval($resultm[0]['pfrmc']) > 0 ? $resultm[0]['pfrmc'] : 'NULL');

                $Usqlm = "UPDATE `piece` SET `priceRawPiece`={$pkh},`priceMachining`={$pm},`pricePolishing`={$pp},`priceFinalRawMaterial`={$pe},`priceFinalRawMaterialCash`={$pen},`error`={$error},`errorText`='{$errorTxt}' WHERE `RowID`={$resm[$j]['pid']}";
                $db->Query($Usqlm);
                $aff = $db->AffectedRows();
                $aff = ((intval($aff) == -1) ? 0 : 1);

                $upCostm = "UPDATE `piece_timing` SET `costMontage`={$mocost},`TotalCosts`={$TC} WHERE `pid`={$resm[$j]['pid']}";
                $db->Query($upCostm);
                $affm = $db->AffectedRows();
                $affm = ((intval($affm) == -1) ? 0 : 1);
                if ($aff == 0 || $affm == 0){
                    $flag = false;
                }
            }

            if ($flag){
                if($outdb == NULL){
                    mysqli_commit($db->Getcon());
                    return true;
                }else{
                    return true;
                }
            }else{
                if($outdb == NULL){
                    mysqli_rollback($db->Getcon());
                    return false;
                }else{
                    return false;
                }
            }
        }else{
            return -2;
        }
    }

    public function updateRowPiecePrice($pid,$howsupply){
        $acm = new acm();
        if(!$acm->hasAccess('piecePriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        //$ut = new Utility();
        mysqli_autocommit($db->Getcon(),FALSE);

        $qd = "SELECT `changeDate` FROM `backup_brass_weight` ORDER BY `RowID` DESC LIMIT 1";
        $rqd = $db->ArrayQuery($qd);
        $effectiveDate = date('Y-m-d', strtotime($rqd[0]['changeDate'] . "+3 months"));
        if (date('Y-m-d') > $effectiveDate){
            return -1;
        }
        $sqq = "SELECT `RowID` FROM `brass_weight`";
        $rsqq = $db->ArrayQuery($sqq);
        if (count($rsqq) > 0){
            $flag = true;

            $qq = "SELECT `brassSwarfPrice`,`BullionPriceUp14`,`BullionPriceUnder14`,`BullionPriceColector`,`CastingPrice`,`PercentFuelWeight`,`PolishingSoilPrice` FROM `brass_weight`";  // اطلاعات بار برنج
            $rsqq = $db->ArrayQuery($qq);

            $bsp = $rsqq[0]['brassSwarfPrice'] / 1000;  // قیمت براده برنج
            $BPUp14 = $rsqq[0]['BullionPriceUp14'] / 1000;  // اجرت شمش بالای 14
            $BPUn14 = $rsqq[0]['BullionPriceUnder14'] / 1000;  // اجرت شمش زیر 14
            $BPC = $rsqq[0]['BullionPriceColector'] / 1000;  // اجرت شمش کلکتور
            $CP = $rsqq[0]['CastingPrice'] / 1000;  // اجرت ریخته گری
            $PSP = $rsqq[0]['PolishingSoilPrice'] / 1000;  // قیمت خاک پرداخت
            $PFW = $rsqq[0]['PercentFuelWeight'] * 0.01;  // درصد سوخت بار
            $PFW = ($PFW/(1-$PFW));  // درصد سوخت بار

            $qqq = "SELECT * FROM `wastage`";
            $rqqq = $db->ArrayQuery($qqq);
            $rqqq[0]['wCasting']  = $rqqq[0]['wCasting']/100;
            $rqqq[0]['wMachining']  = $rqqq[0]['wMachining']/100;
            $rqqq[0]['wPolishing']  = $rqqq[0]['wPolishing']/100;
            $rqqq[0]['wMachiningChips']  = $rqqq[0]['wMachiningChips']/100;
            $rqqq[0]['wPolishingSoil']  = $rqqq[0]['wPolishingSoil']/100;

            $sql = "SELECT `piece`.`RowID` AS `pieceID`,`ChangingHow_supply`,`Weight_materials`,`Weight_Machining`,`Weight_Final`,
                           `TypeBullion`,`material`,`CastingPriceBC`,`PercentFuelWeightBC`,`CastMachPriceBM`,`PercentFuelWeightBM`,
                           `currencyID`,`currency_amount`,`Percentage_additional_costs`,`priceDakheli`,`PercentageACD`,
                           `Forging_timing`,`Machining_timing`,`Polishing_timing`,`Plating_timing`,`Paint_timing`,`PVD_timing`,
                           `Hose_timing`,`Pipe_timing`,`PlasticInjection_timing`,`monthkickback`,`priceCatchDate`,`plasticPlate`,`PercentagePP`,`referenceECode`
                        FROM `piece_masterlist` 
                        INNER JOIN `piece` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                        LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)
                        WHERE `piece`.`RowID`={$pid}";
            $res = $db->ArrayQuery($sql);

            $qaday = "SELECT `AvailableDays` FROM `available_days` ORDER BY `RowID` DESC LIMIT 1";
            $rstd = $db->ArrayQuery($qaday);
            $fixedPrice = ($rstd[0]['AvailableDays'] * 7.33);
            $fixedPrice = $fixedPrice * 3600;

            $usql = "SELECT `RowID`,`efficiency` FROM `official_productive_units` WHERE `RowID` IN (11,12,13,14,15,16,17,18,19) ORDER BY `RowID` ASC";
            $rstu = $db->ArrayQuery($usql);

            $pers = "SELECT `TotalCosts`,`Unit_id` FROM `personnel` WHERE `Unit_id` IN (11,12,13,14,15,16,17,18,19)";
            $rpers = $db->ArrayQuery($pers);
            $cpers = count($rpers);
            $forjCost = 0;
            $forjNum = 0;
            $machCost = 0;
            $machNum = 0;
            $polishCost = 0;
            $polishNum = 0;
            $plateCost = 0;
            $plateNum = 0;
            $pvdCost = 0;
            $pvdNum = 0;
            $hoseCost = 0;
            $hoseNum = 0;
            $pipeCost = 0;
            $pipeNum = 0;
            $plasticCost = 0;
            $plasticNum = 0;
            $montageCost = 0;
            $montageNum = 0;
            for ($o=0;$o<$cpers;$o++){
                switch ($rpers[$o]['Unit_id']){
                    case 19:
                        $forjNum++;
                        $forjCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 18:
                        $machNum++;
                        $machCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 15:
                        $polishNum++;
                        $polishCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 14:
                        $plateNum++;
                        $plateCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 16:
                        $pvdNum++;
                        $pvdCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 13:
                        $hoseNum++;
                        $hoseCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 11:
                        $pipeNum++;
                        $pipeCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 17:
                        $plasticNum++;
                        $plasticCost += $rpers[$o]['TotalCosts'];
                        break;
                    case 12:
                        $montageNum++;
                        $montageCost += $rpers[$o]['TotalCosts'];
                        break;
                }
            } //for
            $forjCost = ($forjCost/$fixedPrice)/$forjNum;  // هزینه هر نفر ثانیه واحد فورج
            $machCost = ($machCost/$fixedPrice)/$machNum;  // هزینه هر نفر ثانیه واحد ماشینکاری
            $polishCost = ($polishCost/$fixedPrice)/$polishNum;  // هزینه هر نفر ثانیه واحد پرداخت
            $paintCost = $polishCost;  // هزینه هر نفر ثانیه واحد رنگ
            $plateCost = ($plateCost / $fixedPrice) / $plateNum;  // هزینه هر نفر ثانیه واحد آبکاری
            $pvdCost = ($pvdCost/$fixedPrice)/$pvdNum;  // هزینه هر نفر ثانیه واحد پی وی دی
            $hoseCost = ($hoseCost/$fixedPrice)/$hoseNum;  // هزینه هر نفر ثانیه واحد شیلنگ
            $pipeCost = ($pipeCost/$fixedPrice)/$pipeNum;  // هزینه هر نفر ثانیه واحد لوله
            $plasticCost = ($plasticCost/$fixedPrice)/$plasticNum;  // هزینه هر نفر ثانیه واحد پلاستیک
            $montageCost = ($montageCost/$fixedPrice)/$montageNum;  // هزینه هر نفر ثانیه واحد مونتاژ

            $bt = $res[0]['TypeBullion']; // نوع شمش
            $fsc = $howsupply; // روش محاسبات
            $wm = $res[0]['Weight_materials'];  // وزن اولیه
            $wmch = $res[0]['Weight_Machining'];  // وزن ماشینکاری
            $wf = $res[0]['Weight_Final'];  // وزن نهایی

            $pkh = 'NULL';
            $pm = 'NULL';
            $pp = 'NULL';
            $pe = 'NULL';
            $pen = 'NULL';
            $error = 0;
            $errorTxt = '';

            if ($fsc == 0){
                $error = 1;
                $errorTxt = 'قطعه راکد شده است !';
            }

            if ($fsc == 13){
                $error = 1;
                $errorTxt = 'قطعه منسوخ شده است !';
            }

            if ($fsc == 1){  // خرید خارجی
                if (strlen(trim($res[0]['referenceECode']))  > 0){
                    $sqlr = "SELECT `pName`,`currency_amount`,`Percentage_additional_costs`,`priceCatchDate`,`currencyID`
                             FROM `piece_masterlist`
                             INNER JOIN `piece` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                             WHERE `pCode`='{$res[0]['referenceECode']}'";
                    $res11 = $db->ArrayQuery($sqlr);
                    if (intval($res11[0]['currencyID'])  == 0){
                        $pkh = 'NULL';
                        $error = 1;
                        $errorTxt = 'قیمت خرید خارجی '.$res11[0]['pName'].' مشخص نشده است !';
                    }else{
                        $qv = "SELECT `dayRate` FROM `currency` WHERE `RowID`={$res11[0]['currencyID']}";
                        $resv = $db->ArrayQuery($qv);
                        if (intval(strtotime($res11[0]['priceCatchDate'])) > 0){
                            $effectiveDate = date('Y-m-d', strtotime($res11[0]['priceCatchDate'] . "+3 months"));
                            if (date('Y-m-d') > $effectiveDate){
                                $error = 1;
                                $errorTxt = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                            }
                        }else{
                            $error = 1;
                            $errorTxt = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                        }
                        $pkh = (($res11[0]['currency_amount'] * $resv[0]['dayRate']) * (100 + floatval($res11[0]['Percentage_additional_costs']))) / 100;
                    }
                }else {
                    if (intval($res[0]['currencyID']) == 0) {
                        $pkh = 'NULL';
                        $error = 1;
                        $errorTxt = $this->errorPriceKhareji;
                    } else {
                        $qv = "SELECT `dayRate` FROM `currency` WHERE `RowID`={$res[0]['currencyID']}";
                        $resv = $db->ArrayQuery($qv);
                        if (intval(strtotime($res[0]['priceCatchDate'])) > 0) {
                            $effectiveDate = date('Y-m-d', strtotime($res[0]['priceCatchDate'] . "+3 months"));
                            if (date('Y-m-d') > $effectiveDate) {
                                $error = 1;
                                $errorTxt = $this->errorPriceDate;
                            }
                        } else {
                            $error = 1;
                            $errorTxt = $this->errorPriceDate;
                        }
                        $pkh = (($res[0]['currency_amount'] * $resv[0]['dayRate']) * (100 + floatval($res[0]['Percentage_additional_costs']))) / 100;
                    }
                }
                $pe = $pkh;
            }

            if ($fsc == 2){  // خرید داخلی
                if (strlen(trim($res[0]['referenceECode']))  > 0){
                    $sqlr = "SELECT `pName`,`priceDakheli`,`PercentageACD`,`priceCatchDate`,`monthkickback` FROM `piece` WHERE `pCode`='{$res[0]['referenceECode']}'";
                    $res11 = $db->ArrayQuery($sqlr);
                    if (intval($res11[0]['priceDakheli'])  == 0){
                        $pkh = 'NULL';
                        $error = 1;
                        $errorTxt = 'قیمت خرید داخلی '.$res11[0]['pName'].' مشخص نشده است !';
                    }else{
                        if (intval(strtotime($res11[0]['priceCatchDate'])) > 0){
                            $effectiveDate = date('Y-m-d', strtotime($res11[0]['priceCatchDate'] . "+3 months"));
                            if (date('Y-m-d') > $effectiveDate){
                                $error = 1;
                                $errorTxt = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                            }
                        }else{
                            $error = 1;
                            $errorTxt = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                        }
                        $pkh = (($res11[0]['priceDakheli']) * (100 + floatval($res11[0]['PercentageACD']))) / 100;
                        $pen = ($pkh == 0 ? 'NULL' : (($pkh * (100 - ($res11[0]['monthkickback'] * $rqqq[0]['Scount'])))/100));
                    }
                }else {
                    if (intval($res[0]['priceDakheli']) == 0) {
                        $pkh = 'NULL';
                        $error = 1;
                        $errorTxt = $this->errorPriceDakheli;
                    } else {
                        if (intval(strtotime($res[0]['priceCatchDate'])) > 0) {
                            $effectiveDate = date('Y-m-d', strtotime($res[0]['priceCatchDate'] . "+3 months"));
                            if (date('Y-m-d') > $effectiveDate) {
                                $error = 1;
                                $errorTxt = $this->errorPriceDate;
                            }
                        } else {
                            $error = 1;
                            $errorTxt = $this->errorPriceDate;
                        }
                        $pkh = (($res[0]['priceDakheli']) * (100 + floatval($res[0]['PercentageACD']))) / 100;
                        $pen = ($pkh == 'NULL' ? 'NULL' : (($pkh * (100 - ($res[0]['monthkickback'] * $rqqq[0]['Scount'])))/100));
                    }
                }
                $pe = $pkh;
            }

            if ($fsc == 3){  // خرید قطعه ماشینکاری
                if (strlen(trim($res[0]['referenceECode']))  > 0){
                    $sqlr = "SELECT `pName`,`CastMachPriceBM`,`PercentFuelWeightBM`,`priceCatchDate` FROM `piece` WHERE `pCode`='{$res[0]['referenceECode']}'";
                    $res11 = $db->ArrayQuery($sqlr);
                    if (intval($res11[0]['CastMachPriceBM'])  == 0){
                        $error = 1;
                        $errorTxt = 'قیمت خرید قطعه ماشینکاری '.$res11[0]['pName'].' مشخص نشده است !';
                    }else{
                        if (intval(strtotime($res11[0]['priceCatchDate'])) > 0){
                            $effectiveDate = date('Y-m-d', strtotime($res11[0]['priceCatchDate'] . "+3 months"));
                            if (date('Y-m-d') > $effectiveDate){
                                $error = 1;
                                $errorTxt = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                            }
                        }else{
                            $error = 1;
                            $errorTxt = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                        }
                        $res11[0]['CastMachPriceBM'] = $res11[0]['CastMachPriceBM'] / 1000;
                        $PFWP = floatval($res11[0]['PercentFuelWeightBM']) * 0.01;  // درصد سوخت بار
                        $PFWP = ($PFWP/(1-$PFWP));  // درصد سوخت بار
                        $pm = $wmch * ($bsp + $res11[0]['CastMachPriceBM'] + ($bsp * $PFWP)); // قیمت ماشینکاری
                        $pp = ($wmch == $wf ? 'NULL' : (($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing'])));
                        $pe = ($pp == 'NULL' ? $pm : $pp);
                    }
                }else {
                    if (intval($res[0]['CastMachPriceBM']) == 0) {
                        $error = 1;
                        $errorTxt = $this->errorPriceKGM;
                    } else {
                        if (intval(strtotime($res[0]['priceCatchDate'])) > 0) {
                            $effectiveDate = date('Y-m-d', strtotime($res[0]['priceCatchDate'] . "+3 months"));
                            if (date('Y-m-d') > $effectiveDate) {
                                $error = 1;
                                $errorTxt = $this->errorPriceDate;
                            }
                        } else {
                            $error = 1;
                            $errorTxt = $this->errorPriceDate;
                        }
                        $res[0]['CastMachPriceBM'] = $res[0]['CastMachPriceBM'] / 1000;
                        $PFWP = floatval($res[0]['PercentFuelWeightBM']) * 0.01;  // درصد سوخت بار
                        $PFWP = ($PFWP / (1 - $PFWP));  // درصد سوخت بار
                        $pm = $wmch * ($bsp + $res[0]['CastMachPriceBM'] + ($bsp * $PFWP)); // قیمت ماشینکاری
                        $pp = ($wmch == $wf ? 'NULL' : (($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing'])));
                        $pe = ($pp == 'NULL' ? $pm : $pp);
                    }
                }
            }

            if ($fsc == 4){  // خرید قطعه ریخته گری
                if (strlen(trim($res[0]['referenceECode']))  > 0) {
                    $sqlr = "SELECT `pName`,`CastingPriceBC`,`PercentFuelWeightBC`,`priceCatchDate` FROM `piece` WHERE `pCode`='{$res[0]['referenceECode']}'";
                    $res11 = $db->ArrayQuery($sqlr);
                    if (intval($res11[0]['CastingPriceBC']) == 0) {
                        $error = 1;
                        $errorTxt = 'قیمت خرید قطعه ریخته گری '.$res11[0]['pName'].' مشخص نشده است !';
                    } else {
                        if (intval(strtotime($res11[0]['priceCatchDate'])) > 0) {
                            $effectiveDate = date('Y-m-d', strtotime($res11[0]['priceCatchDate'] . "+3 months"));
                            if (date('Y-m-d') > $effectiveDate) {
                                $error = 1;
                                $errorTxt = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                            }
                        } else {
                            $error = 1;
                            $errorTxt = 'تاریخ قیمت '.$res11[0]['pName'].' منقضی شده است !';
                        }

                        $res11[0]['CastingPriceBC'] = $res11[0]['CastingPriceBC'] / 1000;
                        $PFWC = floatval($res11[0]['PercentFuelWeightBC']) * 0.01;  // درصد سوخت بار
                        $PFWC = ($PFWC / (1 - $PFWC));  // درصد سوخت بار
                        $pkh = $wm * ($bsp + $res11[0]['CastingPriceBC'] + ($bsp * $PFWC));  // قیمت قطعه خام
                        $pm = ($pkh - (($wm - $wmch) * $bsp) + ($rqqq[0]['wMachiningChips'] * ($wm - $wmch) * $bsp)) * (1 + $rqqq[0]['wMachining']); // قیمت ماشینکاری
                        $pp = ($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing']);  // قیمت پرداخت
                        $pe = $pp;
                    }
                }else {
                    if (intval($res[0]['CastingPriceBC']) == 0) {
                        $error = 1;
                        $errorTxt = $this->errorPriceKGR;
                    } else {
                        if (intval(strtotime($res[0]['priceCatchDate'])) > 0) {
                            $effectiveDate = date('Y-m-d', strtotime($res[0]['priceCatchDate'] . "+3 months"));
                            if (date('Y-m-d') > $effectiveDate) {
                                $error = 1;
                                $errorTxt = $this->errorPriceDate;
                            }
                        } else {
                            $error = 1;
                            $errorTxt = $this->errorPriceDate;
                        }
                        $res[0]['CastingPriceBC'] = $res[0]['CastingPriceBC'] / 1000;
                        $PFWC = floatval($res[0]['PercentFuelWeightBC']) * 0.01;  // درصد سوخت بار
                        $PFWC = ($PFWC / (1 - $PFWC));  // درصد سوخت بار
                        $pkh = $wm * ($bsp + $res[0]['CastingPriceBC'] + ($bsp * $PFWC));  // قیمت قطعه خام
                        $pm = ($pkh - (($wm - $wmch) * $bsp) + ($rqqq[0]['wMachiningChips'] * ($wm - $wmch) * $bsp)) * (1 + $rqqq[0]['wMachining']); // قیمت ماشینکاری
                        $pp = ($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing']);  // قیمت پرداخت
                        $pe = $pp;
                    }
                }
            }

            if ($fsc == 5){  // تولید ریخته گری
                $pkh = ($wm * ($bsp + $CP + ($bsp * $PFW))) * (1 + $rqqq[0]['wCasting']); // قیمت قطعه خام
                $pm = ($pkh - (($wm - $wmch) * $bsp) + ($rqqq[0]['wMachiningChips'] * ($wm - $wmch) * $bsp)) * (1 + $rqqq[0]['wMachining']);  // قیمت ماشینکاری
                $pp = ($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing']);  // قیمت پرداخت
                $pe = $pp;
            }

            if ($fsc == 6 || $fsc == 7 || $fsc == 12){  // تولید ماشینکاری و فورج و کلکتور
                switch ($bt){
                    case 0: // قطر زیر 14
                        $but = $BPUn14;
                        break;
                    case 1:  // قطر بالای 14
                        $but = $BPUp14;
                        break;
                    case 2:  // کلکتور
                        $but = $BPC;
                        break;
                    default:
                        $but = 0;
                        $wm = 0;
                        $wmch = 0;
                        $wf = 0;
                }
                $pkh = ($wm * ($bsp + $but));  // قیمت قطعه خام
                $pm = ($pkh - (($wm - $wmch) * $bsp) + ($rqqq[0]['wMachiningChips'] * ($wm - $wmch) * $bsp)) * (1 + $rqqq[0]['wMachining']);  // قیمت ماشینکاری
                $pp = ($wmch == $wf ? 'NULL' : (($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing'])));  // قیمت پرداخت
                $pe = ($pp == 'NULL' ? $pm : $pp);
            }

            if ($fsc == 8 || $fsc == 10 || $fsc == 11){   // تزریق پلاستیک و شیلنگ و برش لیزر
                $query = "SELECT `ChangingHow_supply`,`currencyID`,`currency_amount`,`Percentage_additional_costs`,
                                     `priceDakheli`,`pName`,`monthkickback`,`PercentageACD`,`priceCatchDate`
                              FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`) WHERE `pCode`='{$res[0]['material']}'";
                $result = $db->ArrayQuery($query);

                if ($result[0]['ChangingHow_supply']  == 1){
                    $qv = "SELECT `dayRate` FROM `currency` WHERE `RowID`={$result[0]['currencyID']}";
                    $resv = $db->ArrayQuery($qv);
                    if (intval($result[0]['currencyID']) == 0){
                        $peMaterial = 0;
                    }else{
                        $peMaterial = (($result[0]['currency_amount'] * $resv[0]['dayRate']) * (100 + floatval($result[0]['Percentage_additional_costs']))) / 100;
                    }
                    $penMaterial = $peMaterial;
                }elseif ($result[0]['ChangingHow_supply']  == 2){
                    $peMaterial = ((floatval($result[0]['priceDakheli'])) * (100 + floatval($result[0]['PercentageACD']))) / 100;
                    $penMaterial = (intval($peMaterial) == 0 ? 0 : (($peMaterial * (100 - ($result[0]['monthkickback'] * $rqqq[0]['Scount'])))/100));
                }

                if (intval($peMaterial)  == 0){
                    $pkh = 'NULL';
                    $pe = 'NULL';
                    $pen = 'NULL';
                    $error = 1;
                    $errorTxt = 'قیمت '.$result[0]['pName'].' مشخص نشده است !';
                }else{
                    if (intval(strtotime($result[0]['priceCatchDate'])) > 0){  // چک کردن انقضای ماده خام تزریق پلاستیک
                        $effectiveDate = date('Y-m-d', strtotime($result[0]['priceCatchDate'] . "+3 months"));
                        if (date('Y-m-d') > $effectiveDate){
                            $error = 1;
                            $errorTxt = 'تاریخ قیمت '.$result[0]['pName'].' منقضی شده است !';
                        }
                    }else{
                        $error = 1;
                        $errorTxt = 'تاریخ قیمت '.$result[0]['pName'].' منقضی شده است !';
                    }
                    if ($fsc == 8) {
                        $pkh = $wm * (1 - ($wmch/200)) * ($peMaterial/1000);
                        $pkhn = $wm * (1 - ($wmch/200)) * ($penMaterial/1000);
                        if (strlen(trim($res[0]['referenceECode'])) > 0) {
                            $sqlr = "SELECT `pName`,`plasticPlate`,`PercentagePP`,`priceCatchDate` FROM `piece` WHERE `pCode`='{$res[0]['referenceECode']}'";
                            $res11 = $db->ArrayQuery($sqlr);
                            if (strlen(trim($errorTxt)) == 0) {
                                if (intval(strtotime($res11[0]['priceCatchDate'])) > 0) {  // چک کردن انقضای تاریخ قطعه مرجع
                                    $effectiveDate = date('Y-m-d', strtotime($res11[0]['priceCatchDate'] . "+3 months"));
                                    if (date('Y-m-d') > $effectiveDate) {
                                        $error = 1;
                                        $errorTxt = 'تاریخ قیمت ' . $res11[0]['pName'] . ' منقضی شده است !';
                                    }
                                }
                            }
                            $pe = ($pkh - ($wm - $wf) * (($peMaterial / 1000) / 2)) + ($res11[0]['plasticPlate'] * (1 + ($res11[0]['PercentagePP'] / 100)));
                            $pen = ($pkhn - ($wm - $wf) * (($penMaterial / 1000) / 2)) + ($res11[0]['plasticPlate'] * (1 + ($res11[0]['PercentagePP'] / 100)));
                        } else {
                            if (strlen(trim($errorTxt)) == 0) {
                                if (intval(strtotime($res[0]['priceCatchDate'])) > 0) {  // چک کردن انقضای تاریخ خود قطعه
                                    $effectiveDate = date('Y-m-d', strtotime($res[0]['priceCatchDate'] . "+3 months"));
                                    if (date('Y-m-d') > $effectiveDate) {
                                        $error = 1;
                                        $errorTxt = $this->errorPriceDate;
                                    }
                                }
                            }
                            $pe = ($pkh - ($wm - $wf) * (($peMaterial / 1000) / 2)) + ($res[0]['plasticPlate'] * (1 + ($res[0]['PercentagePP'] / 100)));
                            $pen = ($pkhn - ($wm - $wf) * (($penMaterial / 1000) / 2)) + ($res[0]['plasticPlate'] * (1 + ($res[0]['PercentagePP'] / 100)));
                        }
                    }else{
                        $pkh = ($wf * $peMaterial) / 1000;
                        $pkhn = ($wf * $penMaterial) / 1000;
                        $pe = $pkh;
                        $pen = $pkhn;
                    }
                }
            }

            $TC = 0;
            $mocost = 0;
            if ($fsc == 14){  // قطعه مونتاژی
                $sqlm = "SELECT `montageCode`,`Montage_timing` 
                         FROM `piece_masterlist` 
                         INNER JOIN `good` ON (`piece_masterlist`.`montageCode`=`good`.`gCode`)
                         WHERE `pid`={$pid}";
                $resm = $db->ArrayQuery($sqlm);

                $mocost = (floatval($resm[0]['Montage_timing']) > 0 ? ((($resm[0]['Montage_timing'] * $montageCost)*(100+(100-$rstu[1]['efficiency'])))/100) : 'NULL');
                $querym = "SELECT SUM(`priceFinalRawMaterial` * `amount`) AS `pfrm`,SUM(`priceFinalRawMaterialCash` * `amount`) AS `pfrmc`,
                                  SUM(`TotalCosts`) AS `tc`,SUM(`piece`.`error`) AS `perr`
                           FROM `interface` 
                           LEFT JOIN `piece` ON (`interface`.`PieceCode`=`piece`.`pCode`)
                           LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`) 
                           WHERE `ProductCode`='{$resm[0]['montageCode']}'";
                $resultm = $db->ArrayQuery($querym);

                if (intval($resultm[0]['perr']) > 0){
                    $errors = array();
                    $qerror = "SELECT `error` FROM `good` WHERE `gCode`='{$resm[0]['montageCode']}'";
                    $rerror = $db->ArrayQuery($qerror);
                    $rids = $rerror[0]['error'];
                    $qperror = "SELECT `pName`,`errorText` FROM `piece` WHERE `RowID` IN (".$rids.")";
                    $rstqr = $db->ArrayQuery($qperror);
                    $cntr = count($rstqr);
                    for ($e=0;$e<$cntr;$e++){
                        $errors[] = $rstqr[$e]['pName'].' : '.$rstqr[$e]['errorText'];
                    }
                    $rstqr = implode(' \n\n ',$errors);

                    $error = 1;
                    $errorTxt = $rstqr;
                }

                $TC = $mocost + $resultm[0]['tc'];
                $pe = (intval($resultm[0]['pfrm']) > 0 ? $resultm[0]['pfrm'] : 'NULL');
                $pen = (intval($resultm[0]['pfrmc']) > 0 ? $resultm[0]['pfrmc'] : 'NULL');
            }

            $fcost = (floatval($res[0]['Forging_timing']) > 0 ? ((($res[0]['Forging_timing'] * $forjCost)*(100+(100-$rstu[7]['efficiency'])))/100) : 'NULL');
            $mcost = (floatval($res[0]['Machining_timing']) > 0 ? ((($res[0]['Machining_timing'] * $machCost)*(100+(100-$rstu[6]['efficiency'])))/100) : 'NULL');
            $pcost = (floatval($res[0]['Polishing_timing']) > 0 ? ((($res[0]['Polishing_timing'] * $polishCost)*(100+(100-$rstu[3]['efficiency'])))/100) : 'NULL');
            $plcost = (floatval($res[0]['Plating_timing']) > 0 ? ((($res[0]['Plating_timing'] * $plateCost)*(100+(100-$rstu[2]['efficiency'])))/100) : 'NULL');
            $pacost = (floatval($res[0]['Paint_timing']) > 0 ? ((($res[0]['Paint_timing'] * $paintCost)*(100+(100-$rstu[3]['efficiency'])))/100) : 'NULL');
            $pvcost = (floatval($res[0]['PVD_timing']) > 0 ? ((($res[0]['PVD_timing'] * $pvdCost)*(100+(100-$rstu[4]['efficiency'])))/100) : 'NULL');
            $hcost = (floatval($res[0]['Hose_timing']) > 0 ? ((($res[0]['Hose_timing'] * $hoseCost)*(100+(100-$rstu[1]['efficiency'])))/100) : 'NULL');
            $picost = (floatval($res[0]['Pipe_timing']) > 0 ? ((($res[0]['Pipe_timing'] * $pipeCost)*(100+(100-$rstu[0]['efficiency'])))/100) : 'NULL');
            $placost = (floatval($res[0]['PlasticInjection_timing']) > 0 ? ((($res[0]['PlasticInjection_timing'] * $plasticCost)*(100+(100-$rstu[5]['efficiency'])))/100) : 'NULL');
            $totalMCost = (($fcost + $mcost) == 0 ? 'NULL' : ($fcost + $mcost));
            $totalCosts = (($fcost + $mcost + $pcost + $plcost + $pacost + $pvcost + $hcost + $picost + $placost + $TC) == 0 ? 'NULL' : ($fcost + $mcost + $pcost + $plcost + $pacost + $pvcost + $hcost + $picost + $placost + $TC));  // مجموع هزینه ها

            $pen = ($pen == 'NULL' ? $pe : $pen);
            $Usql = "UPDATE `piece` SET `priceRawPiece`={$pkh},`priceMachining`={$pm},`pricePolishing`={$pp},`priceFinalRawMaterial`={$pe},`priceFinalRawMaterialCash`={$pen},`error`={$error},`errorText`='{$errorTxt}' WHERE `RowID`={$pid}";
            $db->Query($Usql);
            $aff = $db->AffectedRows();
            $aff = ((intval($aff) == -1) ? 0 : 1);

            $Usqlm = "UPDATE `piece_masterlist` SET `ChangingHow_supply`={$howsupply} WHERE `RowID`={$pid}";
            $db->Query($Usqlm);
            $affm = $db->AffectedRows();
            $affm = ((intval($affm) == -1) ? 0 : 1);

            $upCost = "UPDATE `piece_timing` SET `costMachining`={$totalMCost},`costPolishing`={$pcost},`costPlating`={$plcost},`costPainting`={$pacost},
                              `costPVD`={$pvcost},`costPlastic`={$placost},`costHose`={$hcost},`costPipe`={$picost},`costMontage`={$mocost},`TotalCosts`={$totalCosts} 
                       WHERE `pid`={$pid}";
            $db->Query($upCost);
            $aff1 = $db->AffectedRows();
            $aff1 = ((intval($aff1) == -1) ? 0 : 1);
            if ($aff == 0 || $affm == 0 || $aff1 == 0){
                $flag = false;
            }

            if ($flag){
                mysqli_commit($db->Getcon());
                return true;
            }else{
                mysqli_rollback($db->Getcon());
                return false;
            }
        }else{
            return -2;
        }
    }

    public function resetPieceHowSupply(){
        $acm = new acm();
        if(!$acm->hasAccess('piecePriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        mysqli_autocommit($db->Getcon(),FALSE);

        $sql = "SELECT `RowID`,`FixHow_supply`,`ChangingHow_supply` FROM `piece_masterlist` ORDER BY `RowID` ASC";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $flag = true;
            $cnt = count($res);
            for ($i=0;$i<$cnt;$i++){
                if (intval($res[$i]['ChangingHow_supply']) !== intval($res[$i]['FixHow_supply'])) {
                    $qq = "UPDATE `piece_masterlist` SET `ChangingHow_supply`={$res[$i]['FixHow_supply']} WHERE `RowID`={$res[$i]['RowID']}";
                    $db->Query($qq);
                    $aff = $db->AffectedRows();
                    $aff = ((intval($aff) == -1) ? 0 : 1);
                    if ($aff == 0) {
                        $flag = false;
                    }
                }else{
                    continue;
                }
            }
            if ($flag){
                $result = $this->updatePiecePrice($db);
                if ($result == false || intval($result) == -1 || intval($result) == -2){
                    mysqli_rollback($db->Getcon());
                    return false;
                }else{
                    mysqli_commit($db->Getcon());
                    return true;
                }
            }else{
                mysqli_rollback($db->Getcon());
                return false;
            }
        }else{
            return false;
        }
    }

    public function pieceCostsInfoHTM($pid){
        $acm = new acm();
        if(!$acm->hasAccess('piecePriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `ChangingHow_supply` FROM `piece_masterlist` WHERE `pid`=".$pid;
        $res = $db->ArrayQuery($sql);

        if ($res[0]['ChangingHow_supply'] == 14){
            $sql = "SELECT `costMontage`,`TotalCosts` FROM `piece_timing` WHERE `pid`=".$pid;
            $res = $db->ArrayQuery($sql);
            $infoNames = array('هزینه مونتاژ','مجموع هزینه ها');
            $iterator = 0;
            $htm = '';
            $htm .= '<table class="table table-bordered table-hover table-sm" id="pieceCostsInfo-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">ریز هزینه ها</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مقدار</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';
            for ($i=0;$i<2;$i++){
                $iterator++;
                $keyName = key($res[0]);
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(intval($res[0]["$keyName"]) == 0 ? '' : number_format($res[0]["$keyName"]).' ریال').'</td>';
                $htm .= '</tr>';
                next($res[0]);
            }
        }else {
            $sql = "SELECT `costMachining`,`costPolishing`,`costPlating`,`costPainting`,`costPVD`,`costPlastic`,`costHose`,`costPipe`,`TotalCosts` FROM `piece_timing` WHERE `pid`=".$pid;
            $res = $db->ArrayQuery($sql);
            $infoNames = array('هزینه ماشین کاری', 'هزینه پرداخت', 'هزینه آب کاری', 'هزینه رنگ کاری', 'هزینه پی وی دی', 'هزینه تزریق پلاستیک', 'هزینه شیلنگ', 'هزینه لوله', 'مجموع هزینه ها');
            $iterator = 0;
            $htm = '';
            $htm .= '<table class="table table-bordered table-hover table-sm" id="pieceCostsInfo-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">ریز هزینه ها</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مقدار</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';
            for ($i = 0; $i < 9; $i++) {
                $iterator++;
                $keyName = key($res[0]);
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $infoNames[$i] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . (intval($res[0]["$keyName"]) == 0 ? '' : number_format($res[0]["$keyName"]) . ' ریال') . '</td>';
                $htm .= '</tr>';
                next($res[0]);
            }
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function piecePriceInfoHTM($pid){
        $acm = new acm();
        if(!$acm->hasAccess('piecePriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `priceRawPiece`,`priceMachining`,`pricePolishing` FROM `piece` WHERE `RowID`=".$pid;
        $res = $db->ArrayQuery($sql);
        $infoNames = array('قیمت قطعه خام', 'قیمت ماشین کاری', 'قیمت پرداخت');
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="piecePriceInfo-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">ریز هزینه ها</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مقدار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i = 0; $i < 3; $i++) {
            $iterator++;
            $keyName = key($res[0]);
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $infoNames[$i] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . (intval($res[0]["$keyName"]) == 0 ? '' : number_format($res[0]["$keyName"]) . ' ریال') . '</td>';
            $htm .= '</tr>';
            next($res[0]);
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function pieceErrorInfoHTM($pid){
        $acm = new acm();
        if(!$acm->hasAccess('piecePriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `errorText` FROM `piece` WHERE `RowID`=".$pid;
        $res = $db->ArrayQuery($sql);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="pieceErrorInfo-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">خطا</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $htm .= '<tr class="table-secondary">';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">1</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[0]['errorText'] . '</td>';
        $htm .= '</tr>';

        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function quickPiecePriceCalc($HS,$BT,$wfm,$wmch,$wf,$pfm,$wfmt,$pmb,$wft){
        $acm = new acm();
        if(!$acm->hasAccess('piecePriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $qq = "SELECT `brassSwarfPrice`,`BullionPriceUp14`,`BullionPriceUnder14`,`BullionPriceColector`,`CastingPrice`,`PercentFuelWeight`,`PolishingSoilPrice` FROM `brass_weight`";  // اطلاعات بار برنج
        $rsqq = $db->ArrayQuery($qq);

        $bsp = $rsqq[0]['brassSwarfPrice'] / 1000;  // قیمت براده برنج
        $BPUp14 = $rsqq[0]['BullionPriceUp14'] / 1000;  // اجرت شمش بالای 14
        $BPUn14 = $rsqq[0]['BullionPriceUnder14'] / 1000;  // اجرت شمش زیر 14
        $BPC = $rsqq[0]['BullionPriceColector'] / 1000;  // اجرت شمش کلکتور
        $CP = $rsqq[0]['CastingPrice'] / 1000;  // اجرت ریخته گری
        $PSP = $rsqq[0]['PolishingSoilPrice'] / 1000;  // قیمت خاک پرداخت
        $PFW = $rsqq[0]['PercentFuelWeight'] * 0.01;  // درصد سوخت بار
        $PFW = ($PFW/(1-$PFW));  // درصد سوخت بار

        $qqq = "SELECT * FROM `wastage`";
        $rqqq = $db->ArrayQuery($qqq);
        $rqqq[0]['wCasting']  = $rqqq[0]['wCasting']/100;
        $rqqq[0]['wMachining']  = $rqqq[0]['wMachining']/100;
        $rqqq[0]['wPolishing']  = $rqqq[0]['wPolishing']/100;
        $rqqq[0]['wMachiningChips']  = $rqqq[0]['wMachiningChips']/100;
        $rqqq[0]['wPolishingSoil']  = $rqqq[0]['wPolishingSoil']/100;

        if ($HS == 0){  // تولید ریخته گری
            $pkh = ($wfm * ($bsp + $CP + ($bsp * $PFW))) * (1 + $rqqq[0]['wCasting']); // قیمت قطعه خام
            $pm = ($pkh - (($wfm - $wmch) * $bsp) + ($rqqq[0]['wMachiningChips'] * ($wfm - $wmch) * $bsp)) * (1 + $rqqq[0]['wMachining']);  // قیمت ماشینکاری
            $pp = ($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing']);  // قیمت پرداخت
            $pe = $pp;
        }elseif ($HS == 1){  // تولید ماشینکاری و فورج و کلکتور
            switch ($BT){
                case 0: // قطر زیر 14
                    $but = $BPUn14;
                    break;
                case 1:  // قطر بالای 14
                    $but = $BPUp14;
                    break;
                case 2:  // کلکتور
                    $but = $BPC;
                    break;
            }
            $pkh = ($wfm * ($bsp + $but));  // قیمت قطعه خام
            $pm = ($pkh - (($wfm - $wmch) * $bsp) + ($rqqq[0]['wMachiningChips'] * ($wfm - $wmch) * $bsp)) * (1 + $rqqq[0]['wMachining']);  // قیمت ماشینکاری
            $pp = ($wmch == $wf ? 'NULL' : (($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing'])));  // قیمت پرداخت
            $pe = ($pp == 'NULL' ? $pm : $pp);
        }else{
            $pkh = $wfmt * (1 - ($pmb/200)) * ($pfm/1000);
            $pe = ($pkh - ($wfmt - $wft) * (($pfm / 1000) / 2));
        }
        $htm = '<p>قیمت به دست آمده : '.number_format($pe).' ریال</p>';
        return $htm;
    }

    //+++++++++++++++++ گزارش قیمت محصولات +++++++++++++++++++

    public function getGoodPriceReportList($gName,$gCode,$hCode,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('goodPriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $usl = "UPDATE `good` SET `uid`=0 WHERE `uid`={$_SESSION['userid']}";
        $db->Query($usl);

        $dsl = "DELETE FROM `multiple_search` WHERE `uid`={$_SESSION['userid']}";
        $db->Query($dsl);

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`gCode` LIKE "%'.$gCode.'%" ';
        }
        if(strlen(trim($hCode)) > 0){
            $w[] = '`HCode` LIKE "%'.$hCode.'%" ';
        }
        $sql = "SELECT * FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE `isEnable`=1 AND ".$where;
        }else{
            $sql .= " WHERE `isEnable`=1";
        }
        $sql .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);

        $query = "SELECT `changeDate` FROM `backup_good` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate = $ut->greg_to_jal($rst[0]['changeDate']);

        $query = "SELECT `changeDate` FROM `backup_interface` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate1 = $ut->greg_to_jal($rst[0]['changeDate']);

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['btnType'] = (strlen(trim($res[$y]['error'])) > 0 ? 'btn-danger' : 'btn-success');
            $finalRes[$y]['disabled'] = (strlen(trim($res[$y]['error'])) > 0 ? '' : 'disabled');
            $finalRes[$y]['icon'] = 'fa-exclamation-circle';

            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['finalPriceMaterials'] = (intval($res[$y]['finalPriceMaterials']) > 0 ? number_format($res[$y]['finalPriceMaterials']).' ریال' : '');
            $finalRes[$y]['finalPriceMaterialsCash'] = (intval($res[$y]['finalPriceMaterialsCash']) > 0 ? number_format($res[$y]['finalPriceMaterialsCash']).' ریال' : '');
            $finalRes[$y]['totalProductionCosts'] = (intval($res[$y]['totalProductionCosts']) > 0 ? number_format($res[$y]['totalProductionCosts']).' ریال' : '');
            $finalRes[$y]['fppPCosts'] = (intval($res[$y]['fppProductionCosts']) > 0 ? number_format($res[$y]['fppProductionCosts']).' ریال' : '');
            $finalRes[$y]['fppPCostsCash'] = (intval($res[$y]['fppProductionCostsCash']) > 0 ? number_format($res[$y]['fppProductionCostsCash']).' ریال' : '');
        }
        $sendParam = array($finalRes,$changeDate,$changeDate1);
        return $sendParam;
    }

    public function getMGoodPriceReportList($gName,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('goodPriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        $sql = "SELECT `RowID` FROM `good` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql = "SELECT * FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
            $qqq = $sql;
        }
        $sql .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        $arr = array();
        $rqq = $db->ArrayQuery($qqq);
        $cnt = count($rqq);
        for ($i=0;$i<$cnt;$i++){
            $arr[] = $rqq[$i]['RowID'];
        }
        $arr = implode(',',$arr);

        $q = "UPDATE `good` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
        $db->Query($q);

        $q = "UPDATE `good` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
        $db->Query($q);

        $q = "INSERT INTO `multiple_search` (`word`,`uid`) VALUES ('{$gName}',{$_SESSION['userid']})";
        $db->Query($q);

        $query = "SELECT `changeDate` FROM `backup_good` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate = $ut->greg_to_jal($rst[0]['changeDate']);

        $query = "SELECT `changeDate` FROM `backup_interface` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate1 = $ut->greg_to_jal($rst[0]['changeDate']);

        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['btnType'] = (strlen(trim($res[$y]['error'])) > 0 ? 'btn-danger' : 'btn-success');
            $finalRes[$y]['disabled'] = (strlen(trim($res[$y]['error'])) > 0 ? '' : 'disabled');
            $finalRes[$y]['icon'] = 'fa-exclamation-circle';

            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['finalPriceMaterials'] = (intval($res[$y]['finalPriceMaterials']) > 0 ? number_format($res[$y]['finalPriceMaterials']).' ریال' : '');
            $finalRes[$y]['finalPriceMaterialsCash'] = (intval($res[$y]['finalPriceMaterialsCash']) > 0 ? number_format($res[$y]['finalPriceMaterialsCash']).' ریال' : '');
            $finalRes[$y]['totalProductionCosts'] = (intval($res[$y]['totalProductionCosts']) > 0 ? number_format($res[$y]['totalProductionCosts']).' ریال' : '');
            $finalRes[$y]['fppPCosts'] = (intval($res[$y]['fppProductionCosts']) > 0 ? number_format($res[$y]['fppProductionCosts']).' ریال' : '');
            $finalRes[$y]['fppPCostsCash'] = (intval($res[$y]['fppProductionCostsCash']) > 0 ? number_format($res[$y]['fppProductionCostsCash']).' ریال' : '');
        }
        $sendParam = array($finalRes,$changeDate,$changeDate1);
        return $sendParam;
    }

    public function getMPageGoodPriceReportList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('goodPriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        $sql = "SELECT `RowID` FROM `good` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql = "SELECT * FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
            $qqq = $sql;
        }
        $sql .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        $arr = array();
        $rqq = $db->ArrayQuery($qqq);
        $cnt = count($rqq);
        for ($i=0;$i<$cnt;$i++){
            $arr[] = $rqq[$i]['RowID'];
        }
        $arr = implode(',',$arr);

        $q = "UPDATE `good` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
        $db->Query($q);

        $q = "UPDATE `good` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
        $db->Query($q);

        $query = "SELECT `changeDate` FROM `backup_good` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate = $ut->greg_to_jal($rst[0]['changeDate']);

        $query = "SELECT `changeDate` FROM `backup_interface` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate1 = $ut->greg_to_jal($rst[0]['changeDate']);

        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['btnType'] = (strlen(trim($res[$y]['error'])) > 0 ? 'btn-danger' : 'btn-success');
            $finalRes[$y]['disabled'] = (strlen(trim($res[$y]['error'])) > 0 ? '' : 'disabled');
            $finalRes[$y]['icon'] = 'fa-exclamation-circle';

            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['finalPriceMaterials'] = (intval($res[$y]['finalPriceMaterials']) > 0 ? number_format($res[$y]['finalPriceMaterials']).' ریال' : '');
            $finalRes[$y]['finalPriceMaterialsCash'] = (intval($res[$y]['finalPriceMaterialsCash']) > 0 ? number_format($res[$y]['finalPriceMaterialsCash']).' ریال' : '');
            $finalRes[$y]['totalProductionCosts'] = (intval($res[$y]['totalProductionCosts']) > 0 ? number_format($res[$y]['totalProductionCosts']).' ریال' : '');
            $finalRes[$y]['fppPCosts'] = (intval($res[$y]['fppProductionCosts']) > 0 ? number_format($res[$y]['fppProductionCosts']).' ریال' : '');
            $finalRes[$y]['fppPCostsCash'] = (intval($res[$y]['fppProductionCostsCash']) > 0 ? number_format($res[$y]['fppProductionCostsCash']).' ریال' : '');
        }
        $sendParam = array($finalRes,$changeDate,$changeDate1);
        return $sendParam;
    }

    public function getBMGoodPriceReportList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('goodPriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $sql = "DELETE FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` DESC LIMIT 1";
        $db->Query($sql);

        $sql1 = "SELECT `word` FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` ASC";
        $rst = $db->ArrayQuery($sql1);
        $cntrst = count($rst);

        $sql2 = "UPDATE `good` SET `uid`=0 WHERE `uid`={$_SESSION['userid']}";
        $db->Query($sql2);

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        if (intval($cntrst) == 0){
            $sql = "SELECT * FROM `good` ORDER BY `RowID` ASC LIMIT $start,".$numRows;
            $res = $db->ArrayQuery($sql);
        }else{
            for ($i=0;$i<$cntrst;$i++){
                $w = array();
                $w[] = '`gName` LIKE "%'.$rst[$i]['word'].'%" ';

                $sql3 = "SELECT `RowID` FROM `good` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
                $res = $db->ArrayQuery($sql3);
                if (count($res) > 0){
                    $w[] = '`uid`='.$_SESSION['userid'].' ';
                }

                $sql = "SELECT * FROM `good`";
                if(count($w)){
                    $where = implode(" AND ",$w);
                    $sql .= " WHERE ".$where;
                    $qqq = $sql;
                }
                $sql .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
                $res = $db->ArrayQuery($sql);

                $arr = array();
                $rqq = $db->ArrayQuery($qqq);
                $cnt = count($rqq);
                for ($j=0;$j<$cnt;$j++){
                    $arr[] = $rqq[$j]['RowID'];
                }
                $arr = implode(',',$arr);

                $q = "UPDATE `good` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
                $db->Query($q);

                $q = "UPDATE `good` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
                $db->Query($q);
            }
        }

        $query = "SELECT `changeDate` FROM `backup_good` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate = $ut->greg_to_jal($rst[0]['changeDate']);

        $query = "SELECT `changeDate` FROM `backup_interface` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate1 = $ut->greg_to_jal($rst[0]['changeDate']);

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['btnType'] = (strlen(trim($res[$y]['error'])) > 0 ? 'btn-danger' : 'btn-success');
            $finalRes[$y]['disabled'] = (strlen(trim($res[$y]['error'])) > 0 ? '' : 'disabled');
            $finalRes[$y]['icon'] = 'fa-exclamation-circle';

            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['finalPriceMaterials'] = (intval($res[$y]['finalPriceMaterials']) > 0 ? number_format($res[$y]['finalPriceMaterials']).' ریال' : '');
            $finalRes[$y]['finalPriceMaterialsCash'] = (intval($res[$y]['finalPriceMaterialsCash']) > 0 ? number_format($res[$y]['finalPriceMaterialsCash']).' ریال' : '');
            $finalRes[$y]['totalProductionCosts'] = (intval($res[$y]['totalProductionCosts']) > 0 ? number_format($res[$y]['totalProductionCosts']).' ریال' : '');
            $finalRes[$y]['fppPCosts'] = (intval($res[$y]['fppProductionCosts']) > 0 ? number_format($res[$y]['fppProductionCosts']).' ریال' : '');
            $finalRes[$y]['fppPCostsCash'] = (intval($res[$y]['fppProductionCostsCash']) > 0 ? number_format($res[$y]['fppProductionCostsCash']).' ریال' : '');
        }
        $sendParam = array($finalRes,$changeDate,$changeDate1);
        return $sendParam;
    }

    public function getBMPageGoodPriceReportList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('goodPriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $sql1 = "SELECT `word` FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` ASC";
        $rst = $db->ArrayQuery($sql1);
        $cntrst = count($rst);

        $sql2 = "UPDATE `good` SET `uid`=0 WHERE `uid`={$_SESSION['userid']}";
        $db->Query($sql2);

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        if (intval($cntrst) == 0){
            $sql = "SELECT * FROM `good` ORDER BY `RowID` ASC LIMIT $start,".$numRows;
            $res = $db->ArrayQuery($sql);
        }else{
            for ($i=0;$i<$cntrst;$i++){
                $w = array();
                $w[] = '`gName` LIKE "%'.$rst[$i]['word'].'%" ';

                $sql3 = "SELECT `RowID` FROM `good` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
                $res = $db->ArrayQuery($sql3);
                if (count($res) > 0){
                    $w[] = '`uid`='.$_SESSION['userid'].' ';
                }

                $sql = "SELECT * FROM `good`";
                if(count($w)){
                    $where = implode(" AND ",$w);
                    $sql .= " WHERE ".$where;
                    $qqq = $sql;
                }
                $sql .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
                $res = $db->ArrayQuery($sql);

                $arr = array();
                $rqq = $db->ArrayQuery($qqq);
                $cnt = count($rqq);
                for ($j=0;$j<$cnt;$j++){
                    $arr[] = $rqq[$j]['RowID'];
                }
                $arr = implode(',',$arr);

                $q = "UPDATE `good` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
                $db->Query($q);

                $q = "UPDATE `good` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
                $db->Query($q);
            }
        }

        $query = "SELECT `changeDate` FROM `backup_good` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate = $ut->greg_to_jal($rst[0]['changeDate']);

        $query = "SELECT `changeDate` FROM `backup_interface` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate1 = $ut->greg_to_jal($rst[0]['changeDate']);

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['btnType'] = (strlen(trim($res[$y]['error'])) > 0 ? 'btn-danger' : 'btn-success');
            $finalRes[$y]['disabled'] = (strlen(trim($res[$y]['error'])) > 0 ? '' : 'disabled');
            $finalRes[$y]['icon'] = 'fa-exclamation-circle';

            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['finalPriceMaterials'] = (intval($res[$y]['finalPriceMaterials']) > 0 ? number_format($res[$y]['finalPriceMaterials']).' ریال' : '');
            $finalRes[$y]['finalPriceMaterialsCash'] = (intval($res[$y]['finalPriceMaterialsCash']) > 0 ? number_format($res[$y]['finalPriceMaterialsCash']).' ریال' : '');
            $finalRes[$y]['totalProductionCosts'] = (intval($res[$y]['totalProductionCosts']) > 0 ? number_format($res[$y]['totalProductionCosts']).' ریال' : '');
            $finalRes[$y]['fppPCosts'] = (intval($res[$y]['fppProductionCosts']) > 0 ? number_format($res[$y]['fppProductionCosts']).' ریال' : '');
            $finalRes[$y]['fppPCostsCash'] = (intval($res[$y]['fppProductionCostsCash']) > 0 ? number_format($res[$y]['fppProductionCostsCash']).' ریال' : '');
        }
        $sendParam = array($finalRes,$changeDate,$changeDate1);
        return $sendParam;
    }

    public function getGoodPriceReportListCountRows($gName,$gCode,$hCode){
        $acm = new acm();
        if(!$acm->hasAccess('goodPriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $w = array();
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`gCode` LIKE "%'.$gCode.'%" ';
        }
        if(strlen(trim($hCode)) > 0){
            $w[] = '`HCode` LIKE "%'.$hCode.'%" ';
        }
        $sql = "SELECT `RowID` FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE `isEnable`=1 AND ".$where;
        }else{
            $sql .= " WHERE `isEnable`=1";
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getMGoodPriceReportListCountRows($gName){
        $db = new DBi();
        $w = array();
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        $sql = "SELECT `RowID` FROM `good` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql = "SELECT `RowID` FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getBMGoodPriceReportListCountRows(){
        $db = new DBi();
        $sql1 = "SELECT `word` FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` ASC";
        $rst = $db->ArrayQuery($sql1);
        $cntrst = count($rst);

        if (intval($cntrst) == 0){
            $sql = "SELECT `RowID` FROM `good`";
            $res = $db->ArrayQuery($sql);
        }else{
            for ($i=0;$i<$cntrst;$i++){
                $w = array();
                $w[] = '`gName` LIKE "%'.$rst[$i]['word'].'%" ';

                $sql3 = "SELECT `RowID` FROM `good` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
                $res = $db->ArrayQuery($sql3);
                if (count($res) > 0){
                    $w[] = '`uid`='.$_SESSION['userid'].' ';
                }

                $sql = "SELECT `RowID` FROM `good`";
                if(count($w)){
                    $where = implode(" AND ",$w);
                    $sql .= " WHERE ".$where;
                }
                $res = $db->ArrayQuery($sql);
            }
        }
        return count($res);
    }

    public function updateGoodPrice($outdb=NULL){
        $acm = new acm();
        if(!$acm->hasAccess('procurementAccess')) {
            if (!$acm->hasAccess('goodPriceReportManage')) {
                die("access denied");
                exit;
            }
        }
        if($outdb == NULL){
            $db = new DBi();
            mysqli_autocommit($db->Getcon(),FALSE);
        }else{
            $db = $outdb;
        }
        $flag = true;

        $qaday = "SELECT `AvailableDays` FROM `available_days` ORDER BY `RowID` DESC LIMIT 1";
        $rstd = $db->ArrayQuery($qaday);
        $fixedPrice = ($rstd[0]['AvailableDays'] * 7.33);
        $fixedPrice = $fixedPrice * 3600;

        $usql = "SELECT `RowID`,`efficiency` FROM `official_productive_units` WHERE `RowID`=12";
        $rstu = $db->ArrayQuery($usql);

        $pers = "SELECT `TotalCosts`,`Unit_id` FROM `personnel` WHERE `Unit_id`=12";
        $rpers = $db->ArrayQuery($pers);
        $cpers = count($rpers);
        $montageCost = 0;
        $montageNum = 0;
        for ($o=0;$o<$cpers;$o++){
            $montageNum++;
            $montageCost += $rpers[$o]['TotalCosts'];
        } //for
        $montageCost = ($montageCost/$fixedPrice)/$montageNum;  // هزینه هر نفر ثانیه واحد مونتاژ

        $sql = "SELECT `gCode`,`Montage_timing` FROM `good` WHERE `isEnable`=1";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++) {
            $query = "SELECT `piece`.`RowID` FROM `interface`
                      LEFT JOIN `piece` ON (`interface`.`PieceCode`=`piece`.`pCode`)
                      WHERE `ProductCode`='{$res[$i]['gCode']}' AND `piece`.`error`=1";
            $result = $db->ArrayQuery($query);
            $cc = count($result);
			$arr = array();
            for ($j=0;$j<$cc;$j++){
                $arr[] = $result[$j]['RowID'];
            }
            $result = implode(',',$arr);

            $mocost = (floatval($res[$i]['Montage_timing']) > 0 ? ((($res[$i]['Montage_timing'] * $montageCost)*(100+(100-$rstu[0]['efficiency'])))/100) : 'NULL');
            $sql1 = "SELECT SUM(`priceFinalRawMaterial` * `amount`) AS `pfrm`,SUM(`priceFinalRawMaterialCash` * `amount`) AS `pfrmc`,SUM(`TotalCosts`) AS `tc`
                     FROM `interface` 
                     LEFT JOIN `piece` ON (`interface`.`PieceCode`=`piece`.`pCode`)
                     LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)
                     WHERE `ProductCode`='{$res[$i]['gCode']}'";
            $res1 = $db->ArrayQuery($sql1);
            $TC = $mocost + $res1[0]['tc'];
            $pe = (intval($res1[0]['pfrm']) > 0 ? $res1[0]['pfrm'] : 'NULL');
            $pen = (intval($res1[0]['pfrmc']) > 0 ? $res1[0]['pfrmc'] : 'NULL');
            $fppPC = $pe + $TC;
            $fppPCC = $pen + $TC;
            $qq = "UPDATE `good` SET `finalPriceMaterials`={$pe},`finalPriceMaterialsCash`={$pen},`totalProductionCosts`={$TC},`fppProductionCosts`={$fppPC},`fppProductionCostsCash`={$fppPCC},`error`='{$result}' WHERE `gCode`='{$res[$i]['gCode']}'";
            $db->Query($qq);
            $aff = $db->AffectedRows();
            $aff = ((intval($aff) == -1) ? 0 : 1);
            if ($aff == 0){
                $flag = false;
            }
        }

        if ($flag){
            if($outdb == NULL){
                mysqli_commit($db->Getcon());
                return true;
            }else{
                return true;
            }
        }else{
            if($outdb == NULL){
                mysqli_rollback($db->Getcon());
                return false;
            }else{
                return false;
            }
        }
    }

    public function goodErrorInfoHTM($gid){
        $acm = new acm();
        if(!$acm->hasAccess('goodPriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `error` FROM `good` WHERE `RowID`=".$gid;
        $res = $db->ArrayQuery($sql);

        $sql1 = "SELECT `pCode`,`pName`,`errorText` FROM `piece` WHERE `RowID` IN ({$res[0]['error']})";
        $res1 = $db->ArrayQuery($sql1);
        $cnt = count($res1);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="pieceErrorInfo-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 4%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 46%;">نام قطعه</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">کد قطعه</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 40%;">خطا</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $iterator = 0;
        for ($i=0;$i<$cnt;$i++) {
            $iterator++;
            $res1[$i]['errorText'] = nl2br($res1[$i]['errorText']);
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res1[$i]['pName'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res1[$i]['pCode'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res1[$i]['errorText'] . '</td>';
            $htm .= '</tr>';
        }

        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function goodFinePriceHTM($gid){
        $acm = new acm();
        if(!$acm->hasAccess('goodPriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `gCode` FROM `good` WHERE `RowID`=".$gid;
        $res = $db->ArrayQuery($sql);
        $sql1 = "SELECT `pCode`,`pName`,`pUnit`,`amount`,`priceFinalRawMaterial`,`priceFinalRawMaterialCash`,`TotalCosts`,`FixHow_supply` FROM `interface`
                 INNER JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode` AND `interface`.`ProductCode`='{$res[0]['gCode']}' AND `interface`.`isEnable`=1)
                 INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                 LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)";
        $res1 = $db->ArrayQuery($sql1);
        $CountRes = count($res1);
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-responsive table-sm" style="display: inline-table;" id="goodFinePrice-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= "<td style='display: none;'>&nbsp;</td>";
        $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Bold;width: 9%;">کد قطعه</td>';
        $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Bold;width: 19%;">نام قطعه</td>';
        $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Bold;width: 9%;">واحد</td>';
        $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Bold;width: 9%;">مقدار</td>';
        $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Bold;width: 9%;">نحوه تامین</td>';
        $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Bold;width: 9%;">قیمت نهایی مواد</td>';
        $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Bold;width: 9%;">قیمت نهایی مواد - نقدی</td>';
        $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Bold;width: 9%;">مجموع هزینه های تولید</td>';
        $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Bold;width: 9%;">قیمت نهایی محصول با هزینه تولید</td>';
        $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Bold;width: 9%;">قیمت نهایی محصول با هزینه تولید - نقدی</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $TpFRM = 0;
        $TpFRMC = 0;
        $TTcosts = 0;
        $TpFRMTotal = 0;
        $TpFRMCTotal = 0;
        for ($i=0;$i<$CountRes;$i++){
            $pFRM = (floatval($res1[$i]['priceFinalRawMaterial']) > 0 ? $res1[$i]['priceFinalRawMaterial'] * $res1[$i]['amount'] : 0);
            $pFRMTotal = $pFRM + floatval($res1[$i]['TotalCosts']);
            $TpFRM += $pFRM;
            $TpFRMTotal += $pFRMTotal;
            $pFRM = (floatval($pFRM) > 0 ? number_format($pFRM).' ریال' : '');
            $pFRMTotal = (floatval($pFRMTotal) > 0 ? number_format($pFRMTotal).' ریال' : '');

            $pFRMC = (floatval($res1[$i]['priceFinalRawMaterialCash']) > 0 ? $res1[$i]['priceFinalRawMaterialCash'] * $res1[$i]['amount'] : 0);
            $pFRMCTotal = $pFRMC + floatval($res1[$i]['TotalCosts']);
            $TpFRMC += $pFRMC;
            $TpFRMCTotal += $pFRMCTotal;
            $pFRMC = (floatval($pFRMC) > 0 ? number_format($pFRMC).' ریال' : '');
            $pFRMCTotal = (floatval($pFRMCTotal) > 0 ? number_format($pFRMCTotal).' ریال' : '');

            $TTcosts += floatval($res1[$i]['TotalCosts']);
            $res1[$i]['TotalCosts'] = (floatval($res1[$i]['TotalCosts']) > 0 ? number_format($res1[$i]['TotalCosts']).' ریال' : '');

            switch ($res1[$i]['FixHow_supply']){
                case 0:
                    $txt = 'راکد';
                    break;
                case 1:
                    $txt = 'وارداتی';
                    break;
                case 2:
                    $txt = 'خرید داخلی';
                    break;
                case 3:
                    $txt = 'خرید قطعه ماشینکاری';
                    break;
                case 4:
                    $txt = 'خرید قطعه ریخته گری';
                    break;
                case 5:
                    $txt = 'تولید ریخته گری';
                    break;
                case 6:
                    $txt = 'تولید ماشینکاری';
                    break;
                case 7:
                    $txt = 'فورج';
                    break;
                case 8:
                    $txt = 'تزریق پلاستیک';
                    break;
                case 9:
                    $txt = 'لوله';
                    break;
                case 10:
                    $txt = 'شیلنگ';
                    break;
                case 11:
                    $txt = 'برش لیزر';
                    break;
                case 12:
                    $txt = 'کلکتور';
                    break;
                case 13:
                    $txt = 'منسوخ';
                    break;
                case 14:
                    $txt = 'قطعه مونتاژی';
                    break;
            }

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;">'.$res1[$i]['pCode'].'</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;">'.$res1[$i]['pName'].'</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;">'.$res1[$i]['pUnit'].'</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;">'.$res1[$i]['amount'].'</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;">'.$txt.'</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;">'.$pFRM.'</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;">'.$pFRMC.'</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;">'.$res1[$i]['TotalCosts'].'</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;">'.$pFRMTotal.'</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;">'.$pFRMCTotal.'</td>';
            $htm .= '</tr>';
        }
        $TpFRM = (floatval($TpFRM) > 0 ? number_format($TpFRM).' ریال' : '');
        $TpFRMC = (floatval($TpFRMC) > 0 ? number_format($TpFRMC).' ریال' : '');
        $TTcosts = (floatval($TTcosts) > 0 ? number_format($TTcosts).' ریال' : '');
        $TpFRMTotal = (floatval($TpFRMTotal) > 0 ? number_format($TpFRMTotal).' ریال' : '');
        $TpFRMCTotal = (floatval($TpFRMCTotal) > 0 ? number_format($TpFRMCTotal).' ریال' : '');

        $htm .= '<tr class="table-warning">';
        $htm .= '<td colspan="5" style="text-align: center;font-family: dubai-bold;color: red;">جمع کل ریز قیمت قطعات محصول : </td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;">'.$TpFRM.'</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;">'.$TpFRMC.'</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;">'.$TTcosts.'</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;">'.$TpFRMTotal.'</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;">'.$TpFRMCTotal.'</td>';
        $htm .= '</tr>';
        $htm .= '<input type="hidden" id="goodFinePrice-HiddenID" value="'.$gid.'" />';
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getExcelGoodFinePrice($gid){
        $acm = new acm();
        if(!$acm->hasAccess('goodPriceReportManage') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `gCode` FROM `good` WHERE `RowID`=".$gid;
        $res = $db->ArrayQuery($sql);
        $sql1 = "SELECT `pCode`,`pName`,`pUnit`,`amount`,`priceFinalRawMaterial`,`priceFinalRawMaterialCash`,`TotalCosts` FROM `interface`
                 INNER JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode` AND `interface`.`ProductCode`='{$res[0]['gCode']}' AND `interface`.`isEnable`=1)
                 LEFT JOIN `piece_timing` ON (`piece`.`RowID`=`piece_timing`.`pid`)";
        $res1 = $db->ArrayQuery($sql1);
        $cnt = count($res1);
        $result = array();
        $TpFRM = 0;
        $TpFRMC = 0;
        $TTcosts = 0;
        $TpFRMTotal = 0;
        $TpFRMCTotal = 0;
        for ($i=0;$i<$cnt;$i++){
            $result[$i]['pCode'] = $res1[$i]['pCode'];
            $result[$i]['pName'] = $res1[$i]['pName'];
            $result[$i]['pUnit'] = $res1[$i]['pUnit'];
            $result[$i]['amount'] = $res1[$i]['amount'];
            $pFRM = (floatval($res1[$i]['priceFinalRawMaterial']) > 0 ? $res1[$i]['priceFinalRawMaterial'] * $res1[$i]['amount'] : 0);
            $pFRMTotal = $pFRM + floatval($res1[$i]['TotalCosts']);
            $TpFRM += $pFRM;
            $TpFRMTotal += $pFRMTotal;
            $result[$i]['pFRM'] = (floatval($pFRM) > 0 ? number_format($pFRM).' ریال' : '');
            $result[$i]['pFRMTotal'] = (floatval($pFRMTotal) > 0 ? number_format($pFRMTotal).' ریال' : '');

            $pFRMC = (floatval($res1[$i]['priceFinalRawMaterialCash']) > 0 ? $res1[$i]['priceFinalRawMaterialCash'] * $res1[$i]['amount'] : 0);
            $pFRMCTotal = $pFRMC + floatval($res1[$i]['TotalCosts']);
            $TpFRMC += $pFRMC;
            $TpFRMCTotal += $pFRMCTotal;
            $result[$i]['pFRMC'] = (floatval($pFRMC) > 0 ? number_format($pFRMC).' ریال' : '');
            $result[$i]['pFRMCTotal'] = (floatval($pFRMCTotal) > 0 ? number_format($pFRMCTotal).' ریال' : '');

            $TTcosts += floatval($res1[$i]['TotalCosts']);
            $result[$i]['TotalCosts'] = (floatval($res1[$i]['TotalCosts']) > 0 ? number_format($res1[$i]['TotalCosts']).' ریال' : '');
        }
        $TpFRM = (floatval($TpFRM) > 0 ? number_format($TpFRM).' ریال' : '');
        $TpFRMC = (floatval($TpFRMC) > 0 ? number_format($TpFRMC).' ریال' : '');
        $TTcosts = (floatval($TTcosts) > 0 ? number_format($TTcosts).' ریال' : '');
        $TpFRMTotal = (floatval($TpFRMTotal) > 0 ? number_format($TpFRMTotal).' ریال' : '');
        $TpFRMCTotal = (floatval($TpFRMCTotal) > 0 ? number_format($TpFRMCTotal).' ریال' : '');
        $footer = array(0=>'جمع کل ریز قیمت قطعات محصول :	',1=>'',2=>'',3=>'',4=>$TpFRM,5=>$TpFRMC,6=>$TTcosts,7=>$TpFRMTotal,8=>$TpFRMCTotal);
        $rst = array();
        $rst[0] = $result;
        $rst[1] = $footer;
        if(count($rst) > 0){
            return $rst;
        }else{
            $res = "امکان گرفتن خروجی وجود ندارد";
            $out = "false";
            response($res,$out);
            exit;
        }
    }

    //+++++++++++++++++ گزارش قیمت کالای در جریان ساخت +++++++++++++++++++

    public function getGoodProccessPriceReportList($Code,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('goodProccessPriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;

        $sql = "SELECT `RowID`,`mCode` FROM `part_codes` WHERE `h1`='{$Code}' OR `h2`='{$Code}' OR `h3`='{$Code}'OR `h4`='{$Code}'OR `h5`='{$Code}'OR `h6`='{$Code}'OR `h7`='{$Code}'OR `h8`='{$Code}'OR `h9`='{$Code}'OR `h10`='{$Code}'OR `h11`='{$Code}'OR `h12`='{$Code}'OR `h13`='{$Code}'OR `h14`='{$Code}'OR `h15`='{$Code}'OR `h16`='{$Code}'OR `h17`='{$Code}'OR `h18`='{$Code}'";

        $sql .= " ORDER BY `RowID` ASC  LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            $query = "SELECT `piece`.`RowID`,`pName`,`FixHow_supply` FROM `piece`
                      INNER JOIN `piece_masterlist` ON (`piece_masterlist`.`pid`=`piece`.`RowID`)
                      WHERE  `pCode`='{$res[$y]['mCode']}'";
            $rst = $db->ArrayQuery($query);
            switch ($rst[0]['FixHow_supply']){
                case 0:
                    $txt = 'راکد';
                    break;
                case 1:
                    $txt = 'وارداتی';
                    break;
                case 2:
                    $txt = 'خرید داخلی';
                    break;
                case 3:
                    $txt = 'خرید قطعه ماشینکاری';
                    break;
                case 4:
                    $txt = 'خرید قطعه ریخته گری';
                    break;
                case 5:
                    $txt = 'تولید ریخته گری';
                    break;
                case 6:
                    $txt = 'تولید ماشینکاری';
                    break;
                case 7:
                    $txt = 'فورج';
                    break;
                case 8:
                    $txt = 'تزریق پلاستیک';
                    break;
                case 9:
                    $txt = 'لوله';
                    break;
                case 10:
                    $txt = 'شیلنگ';
                    break;
                case 11:
                    $txt = 'برش لیزر';
                    break;
                case 12:
                    $txt = 'کلکتور';
                    break;
                case 13:
                    $txt = 'منسوخ';
                    break;
                case 14:
                    $txt = 'قطعه مونتاژی';
                    break;
            }

            $finalRes[$y]['RowID'] = $rst[0]['RowID'];
            $finalRes[$y]['FixHow_supply'] = $txt;
            $finalRes[$y]['pName'] = $rst[0]['pName'];
            $finalRes[$y]['mCode'] = $res[$y]['mCode'];
            $finalRes[$y]['hCode'] = $Code;
        }
        return $finalRes;
    }

    public function getGoodProccessPriceReportListCountRows($Code){
        $acm = new acm();
        if(!$acm->hasAccess('goodProccessPriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `RowID`,`mCode` FROM `part_codes` WHERE `h1`='{$Code}' OR `h2`='{$Code}' OR `h3`='{$Code}'OR `h4`='{$Code}'OR `h5`='{$Code}'OR `h6`='{$Code}'OR `h7`='{$Code}'OR `h8`='{$Code}'OR `h9`='{$Code}'OR `h10`='{$Code}'OR `h11`='{$Code}'OR `h12`='{$Code}'OR `h13`='{$Code}'OR `h14`='{$Code}'OR `h15`='{$Code}'OR `h16`='{$Code}'OR `h17`='{$Code}'OR `h18`='{$Code}'";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getRawMaterialCode($pid){
        $acm = new acm();
        if(!$acm->hasAccess('goodProccessPriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        //$ut = new Utility();
        $sql = "SELECT `pCode`,`RawMaterialCode`,`FixHow_supply`,`referenceECode` FROM `piece_masterlist` INNER JOIN `piece` ON (`piece_masterlist`.`pid`= `piece`.`RowID` AND `pid`={$pid})";
        $res = $db->ArrayQuery($sql);
        if (intval($res[0]['FixHow_supply']) == 1 || intval($res[0]['FixHow_supply']) == 2){   // خرید داخلی یا وارداتی
            if (strlen(trim($res[0]['referenceECode'])) !== 0){
                $query = "SELECT `pName`,`h18` FROM `piece` INNER JOIN `part_codes` ON (`piece`.`pCode`=`part_codes`.`mCode` AND `pCode`='{$res[0]['referenceECode']}')";
                $rst = $db->ArrayQuery($query);
            }else{
                $query = "SELECT `pName`,`h18` FROM `piece` INNER JOIN `part_codes` ON (`piece`.`pCode`=`part_codes`.`mCode` AND `pCode`='{$res[0]['pCode']}')";
                $rst = $db->ArrayQuery($query);
            }
        }

        $res = array( "RawMaterialCode"=>($res[0]['RawMaterialCode']),"FixHow_supply"=>($res[0]['FixHow_supply']),"pName"=>($rst[0]['pName']),"h18"=>($rst[0]['h18']) );
        if (count($res) > 0){
            return $res;
        }else{
            return false;
        }
    }

    public function goodProccessPriceCalc($avg,$pid){
        $acm = new acm();
        if(!$acm->hasAccess('goodProccessPriceReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `FixHow_supply`,`Weight_materials`,`Weight_Machining`,`Weight_Final` FROM `piece_masterlist` WHERE `pid`={$pid}";  // اطلاعات قطعه
        $res = $db->ArrayQuery($sql);

        $wfm = $res[0]['Weight_materials'];
        $wmch = $res[0]['Weight_Machining'];
        $wf = $res[0]['Weight_Final'];

        $qq = "SELECT `CastingPrice`,`PercentFuelWeight`,`brassSwarfPrice` FROM `brass_weight`";  // اطلاعات بار برنج
        $rsqq = $db->ArrayQuery($qq);

        $bsp = $avg/1000;
        $PSP = $bsp * 0.86;   // خاک پرداخت
        $CP = $rsqq[0]['CastingPrice'] / 1000;  // اجرت ریخته گری
        $PFW = $rsqq[0]['PercentFuelWeight'] * 0.01;  // درصد سوخت بار
        $PFW = ($PFW/(1-$PFW));  // درصد سوخت بار


        $qqq = "SELECT * FROM `wastage`";
        $rqqq = $db->ArrayQuery($qqq);
        $rqqq[0]['wCasting']  = $rqqq[0]['wCasting']/100;
        $rqqq[0]['wMachining']  = $rqqq[0]['wMachining']/100;
        $rqqq[0]['wPolishing']  = $rqqq[0]['wPolishing']/100;
        $rqqq[0]['wMachiningChips']  = $rqqq[0]['wMachiningChips']/100;
        $rqqq[0]['wPolishingSoil']  = $rqqq[0]['wPolishingSoil']/100;

        if ($res[0]['FixHow_supply'] == 5){  // تولید ریخته گری
            $pkh = ($wfm * ($bsp + $CP + ($bsp * $PFW))) * (1 + $rqqq[0]['wCasting']); // قیمت قطعه خام
            $pm = ($pkh - (($wfm - $wmch) * $bsp) + ($rqqq[0]['wMachiningChips'] * ($wfm - $wmch) * $bsp)) * (1 + $rqqq[0]['wMachining']);  // قیمت ماشینکاری
            $pp = ($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing']);  // قیمت پرداخت
            $pe = $pp;
        }elseif ($res[0]['FixHow_supply'] == 6 || $res[0]['FixHow_supply'] == 7 || $res[0]['FixHow_supply'] == 12){  // تولید ماشینکاری و فورج و کلکتور
            $pkh = ($wfm * $bsp);  // قیمت قطعه خام
            $bsp = ($bsp * 0.88);
            $pm = ($pkh - (($wfm - $wmch) * $bsp) + ($rqqq[0]['wMachiningChips'] * ($wfm - $wmch) * $bsp)) * (1 + $rqqq[0]['wMachining']);  // قیمت ماشینکاری
            $PSP = $bsp * 0.86;
            $pp = ($wmch == $wf ? 'NULL' : (($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing'])));  // قیمت پرداخت
            $pe = ($pp == 'NULL' ? $pm : $pp);
        }elseif ($res[0]['FixHow_supply'] == 8 || $res[0]['FixHow_supply'] == 11 || $res[0]['FixHow_supply'] == 10){   // تزریق پلاستیک، لیزر، شیلنگ
            $pkh = $wfm * (1 - ($wmch/200)) * ($bsp);
            $pe = ($pkh - ($wfm - $wf) * (($bsp) / 2));
        }elseif ($res[0]['FixHow_supply'] == 3) {  // خرید قطعه ماشین کاری
            $bsp = $rsqq[0]['brassSwarfPrice'] / 1000;
            $PSP = $bsp * 0.86;   // خاک پرداخت
            $pkh = $avg;  // قیمت قطعه خام
            $pm = $avg;  // قیمت ماشینکاری
            $pp = ($wmch == $wf ? 'NULL' : (($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing'])));  // قیمت پرداخت
            $pe = ($pp == 'NULL' ? $pm : $pp);
        }elseif ($res[0]['FixHow_supply'] == 4) {  // خرید قطعه ریخته گری
            $bsp = $rsqq[0]['brassSwarfPrice'] / 1000;
            $PSP = $bsp * 0.86;   // خاک پرداخت
            $pkh = $avg; // قیمت قطعه خام
            $pm = ($pkh - (($wfm - $wmch) * $bsp) + ($rqqq[0]['wMachiningChips'] * ($wfm - $wmch) * $bsp)) * (1 + $rqqq[0]['wMachining']);  // قیمت ماشینکاری
            $pp = ($pm - (($wmch - $wf) * $PSP) + ($rqqq[0]['wPolishingSoil'] * ($wmch - $wf) * $PSP)) * (1 + $rqqq[0]['wPolishing']);  // قیمت پرداخت
            $pe = $pp;
        }else{
            $pe = 0;
        }
        $htm = '<p>قیمت ماده اولیه : '.number_format($pkh).' ریال</p>';
        $htm .= '<p>قیمت ماشین کاری : '.number_format($pm).' ریال</p>';
        $htm .= '<p>قیمت نهایی (پرداخت شده - آب کاری شده - PVD) : '.number_format($pe).' ریال</p>';
        return $htm;
    }

    //+++++++++++++++++ قیمت فروش محصولات +++++++++++++++++++

/*    public function salesPriceGoodSalePrice(){
        $acm = new acm();
        if(!$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        //$ut = new Utility();
        $sql = "SELECT `RowID`,`brand`,`Series`,`color`,`category` FROM `good` WHERE `ggroup`='شیرآلات' AND `color`!='کروم'";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        for ($i=0;$i<$cnt;$i++){
            $query = "SELECT `RowID` FROM `good` WHERE `brand`='{$res[$i]['brand']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}' AND `color`='کروم'";
            $rst = $db->ArrayQuery($query);
            $q = "UPDATE `good` SET `chCode`='{$rst[0]['RowID']}' WHERE `RowID`={$res[$i]['RowID']}";
            $db->Query($q);
        }
        return true;
    }*/

    /*public function salesPriceGoodSalePrice(){
        $acm = new acm();
        if(!$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT * FROM `part_codes`";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        for ($i=0;$i<$cnt;$i++){
            $sql1 = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$res[$i]['mCode']}'";
            $rst = $db->ArrayQuery($sql1);

            $q = "UPDATE `piece_masterlist` SET `rawCode`='{$res[$i]['h1']}',`forgingCode`='{$res[$i]['h2']}',`machiningCode`='{$res[$i]['h3']}',`polishingCode`='{$res[$i]['h4']}',`nickelCode`='{$res[$i]['h5']}',`platingCode`='{$res[$i]['h6']}',`pushplatingCode`='{$res[$i]['h7']}',`goldenCode`='{$res[$i]['h8']}',`mattgoldenCode`='{$res[$i]['h9']}',`lightgoldenCode`='{$res[$i]['h10']}',`darkgoldenCode`='{$res[$i]['h11']}',`paintCode`='{$res[$i]['h12']}',`decoralCode`='{$res[$i]['h13']}',`steelCode`='{$res[$i]['h14']}',`rawppCode`='{$res[$i]['h15']}',`finalppCode`='{$res[$i]['h16']}',`finalCode`='{$res[$i]['h17']}' WHERE `pid`={$rst[0]['RowID']}";
            $db->Query($q);
            $aff = $db->AffectedRows();
            $aff = ((intval($aff) == -1) ? 0 : 1);
            if ($aff == 0){
                //$ut->fileRecorder($q);
            }
        }
        return true;

/*        $sql = "SELECT * FROM `ppp`";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        for ($i=0;$i<$cnt;$i++){
            $q = "INSERT INTO `part_codes` (`mCode`,`h1`,`h2`,`h3`,`h4`,`h5`,`h6`,`h7`,`h8`,`h9`,`h10`,`h11`,`h12`,`h13`,`h14`,`h15`,`h16`,`h17`,`h18`) VALUES ('{$res[$i]['A']}','{$res[$i]['B']}','{$res[$i]['C']}','{$res[$i]['D']}','{$res[$i]['E']}','{$res[$i]['F']}','{$res[$i]['G']}','{$res[$i]['H']}','{$res[$i]['I']}','{$res[$i]['J']}','{$res[$i]['K']}','{$res[$i]['L']}','{$res[$i]['M']}','{$res[$i]['N']}','{$res[$i]['O']}','{$res[$i]['P']}','{$res[$i]['Q']}','{$res[$i]['R']}','{$res[$i]['S']}')";
            $db->Query($q);
        }
        return true;*/
/*        $flag = true;
        $ut = new Utility();

        $sql = "SELECT `A`,`B` FROM `saleprice`";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        for ($i=0;$i<$cnt;$i++){
            if (strlen(trim($res[$i]['B'])) == 0){
                $res[$i]['B'] = 0;
            }
            $qq = "UPDATE `good` SET `salesListPrice`={$res[$i]['B']} WHERE `gCode`='{$res[$i]['A']}'";
            $db->Query($qq);
            $aff = $db->AffectedRows();
            $aff = ((intval($aff) == -1) ? 0 : 1);
            if ($aff == 0){
                //$ut->fileRecorder($qq);
                $flag = false;
            }
        }

        if ($flag){
            return true;
        }else{
            return false;
        }
    }*/

    public function getPrintSaleListPriceHtm($cid){
        $acm = new acm();
        if(!$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $htm = '';
        $srcc = ADDR.'images/abrash.png';
        $srcc1 = ADDR.'images/standard.png';
        $srcc2 = ADDR.'images/ce.png';
        $srcc3 = ADDR.'images/pipeHeader.jpg';
        $srcc4 = ADDR.'images/pipeFooter.jpg';
        switch (intval($cid)){
            case 0:  // شیرآلات ابرش
                $htm .= '<div style="border: 1px solid #000;margin: -70px 0 0 0;width: 100%" class="demo">';
                    $htm .= '<div style="margin: 10px;">';
                        $htm .= '<div style="width: 30%;float: left;text-align: center;"><img  src="'.$srcc.'"></div>';
                        $htm .= '<div style="width: 40%;float: left;text-align: center;"><h1>شیرآلات بهداشتی ابرش</h1></div>';
                        $htm .= '<div style="width: 30%;float: left;text-align: center;"><img src="'.$srcc1.'"></div>';
                    $htm .= '</div>';

                    $htm .= '<div style="padding: 20px 0 100px 0;border-bottom: 1px solid #000;">';
                        $htm .= '<div style="width: 30%;float: left;text-align: center;"><h5>لیست قیمت جهت مصرف کنندگان محترم</h5></div>';
                        $htm .= '<div style="width: 40%;float: left;text-align: center;"><img src="'.$srcc2.'"></div>';
                        $htm .= '<div style="width: 30%;float: left;text-align: center;"><h4>تاریخ اجرا : 1399/07/19</h4></div>';
                    $htm .= '</div>';

                    $htm .= '<div>';
                        $htm .= '<table style="width: 100%;" class="table-bordered border-dark">';
                            $htm .= '<thead>';
                                $htm .= '<tr>';
                                    $htm .= '<th style="text-align: center;">نام مدل</th>';
                                    $htm .= '<th style="text-align: center;">نام محصول</th>';
                                    $htm .= '<th style="text-align: center;">تعداد در کارتن</th>';
                                    $htm .= '<th colspan="2" style="text-align: center;">کروم</th>';
                                    $htm .= '<th colspan="2" style="text-align: center;">رنگی(سفید،مشکی و...)</th>';
                                    $htm .= '<th colspan="2" style="text-align: center;">رنگی-طلا(سفید-طلا، مشکی-طلا و ...)</th>';
                                    $htm .= '<th colspan="2" style="text-align: center;">طلایی PVD</th>';
                                    $htm .= '<th colspan="2" style="text-align: center;">طلایی مات PVD--طرح استیل</th>';
                                $htm .= '</tr>';
                                $htm .= '<tr>';
                                    $htm .= '<th colspan="3" style="text-align: center;"></th>';
                                    $htm .= '<th style="text-align: center;">قیمت واحد به ریال</th>';
                                    $htm .= '<th style="text-align: center;">جمع 4 عددی</th>';
                                    $htm .= '<th style="text-align: center;">قیمت واحد به ریال</th>';
                                    $htm .= '<th style="text-align: center;">جمع 4 عددی</th>';
                                    $htm .= '<th style="text-align: center;">قیمت واحد به ریال</th>';
                                    $htm .= '<th style="text-align: center;">جمع 4 عددی</th>';
                                    $htm .= '<th style="text-align: center;">قیمت واحد به ریال</th>';
                                    $htm .= '<th style="text-align: center;">جمع 4 عددی</th>';
                                    $htm .= '<th style="text-align: center;">قیمت واحد به ریال</th>';
                                    $htm .= '<th style="text-align: center;">جمع 4 عددی</th>';
                                $htm .= '</tr>';
                            $htm .= '</thead>';
                            $htm .= '<tbody>';
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاس%' AND `category`='دوش'";
                                $rst = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاس%' AND `category`='توالت'";
                                $rst1 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاس%' AND `category`='روشویی ثابت'";
                                $rst2 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی با علم ریخته گری'";
                                $rst3 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                                $rst4 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاس%' AND `category`='روشویی متحرک با علم عصایی'";
                                $rst5 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی دنباله دار با علم ریخته گری'";
                                $rst6 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاس%' AND `category`='روشویی متحرک دنباله دار با علم ریخته گری'";
                                $rst7 = $db->ArrayQuery($sql);
                                $nc4yas = $rst[0]['salesListPrice'] + $rst1[0]['salesListPrice'] + $rst2[0]['salesListPrice'] +$rst3[0]['salesListPrice'];

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاس%' AND `category`='دوش'";
                                $rst8 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاس%' AND `category`='توالت'";
                                $rst9 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاس%' AND `category`='روشویی ثابت'";
                                $rst10 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی با علم ریخته گری'";
                                $rst11 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                                $rst12 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاس%' AND `category`='روشویی متحرک با علم عصایی'";
                                $rst13 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی دنباله دار با علم ریخته گری'";
                                $rst14 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاس%' AND `category`='روشویی متحرک دنباله دار با علم ریخته گری'";
                                $rst15 = $db->ArrayQuery($sql);
                                $nco4yas = $rst8[0]['salesListPrice'] + $rst9[0]['salesListPrice'] + $rst10[0]['salesListPrice'] +$rst11[0]['salesListPrice'];

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاس%' AND `category`='دوش'";
                                $rst16 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاس%' AND `category`='توالت'";
                                $rst17 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاس%' AND `category`='روشویی ثابت'";
                                $rst18 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی با علم ریخته گری'";
                                $rst19 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                                $rst20 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاس%' AND `category`='روشویی متحرک با علم عصایی'";
                                $rst21 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی دنباله دار با علم ریخته گری'";
                                $rst22 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاس%' AND `category`='روشویی متحرک دنباله دار با علم ریخته گری'";
                                $rst23 = $db->ArrayQuery($sql);
                                $ncogo4yas = $rst16[0]['salesListPrice'] + $rst17[0]['salesListPrice'] + $rst18[0]['salesListPrice'] +$rst19[0]['salesListPrice'];

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاس%' AND `category`='دوش'";
                                $rst24 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاس%' AND `category`='توالت'";
                                $rst25 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاس%' AND `category`='روشویی ثابت'";
                                $rst26 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی با علم ریخته گری'";
                                $rst27 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                                $rst28 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاس%' AND `category`='روشویی متحرک با علم عصایی'";
                                $rst29 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی دنباله دار با علم ریخته گری'";
                                $rst30 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاس%' AND `category`='روشویی متحرک دنباله دار با علم ریخته گری'";
                                $rst31 = $db->ArrayQuery($sql);
                                $ngo4yas = $rst24[0]['salesListPrice'] + $rst25[0]['salesListPrice'] + $rst26[0]['salesListPrice'] +$rst27[0]['salesListPrice'];

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاس%' AND `category`='دوش'";
                                $rst32 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاس%' AND `category`='توالت'";
                                $rst33 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاس%' AND `category`='روشویی ثابت'";
                                $rst34 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی با علم ریخته گری'";
                                $rst35 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                                $rst36 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاس%' AND `category`='روشویی متحرک با علم عصایی'";
                                $rst37 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاس%' AND `category`='ظرفشویی دنباله دار با علم ریخته گری'";
                                $rst38 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاس%' AND `category`='روشویی متحرک دنباله دار با علم ریخته گری'";
                                $rst39 = $db->ArrayQuery($sql);
                                $ngom4yas = $rst32[0]['salesListPrice'] + $rst33[0]['salesListPrice'] + $rst34[0]['salesListPrice'] +$rst35[0]['salesListPrice'];

                                $htm .= '<tr>';
                                    $htm .= '<td rowspan="8" style="text-align: center;">یاس 1و2</td>';
                                    $htm .= '<td style="text-align: center;">دوش</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$nc4yas.'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst8[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$nco4yas.'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst16[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$ncogo4yas.'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst24[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$ngo4yas.'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst32[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$ngom4yas.'</td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">توالت</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst1[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst9[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst17[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst25[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst33[0]['salesListPrice'].'</td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">روشویی ثابت</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst2[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst10[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst18[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst26[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst34[0]['salesListPrice'].'</td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">ظرفشویی با علم ریخته گری</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst3[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst11[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst19[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst27[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst35[0]['salesListPrice'].'</td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">ظرفشویی دیواری با علم ریخته گری</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst4[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst12[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst20[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst28[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst36[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">روشویی متحرک با علم عصایی</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst5[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst13[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst21[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst29[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst37[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">ظرفشوئی دنباله دار با علم ریخته گری</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst6[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst14[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst22[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst30[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst38[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">روشوئی متحرک دنباله دار با علم ریخته گری</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst7[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst15[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst23[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst31[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst39[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                $htm .= '</tr>';

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاسمن%' AND `category`='دوش'";
                                $rst = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاسمن%' AND `category`='توالت'";
                                $rst1 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاسمن%' AND `category`='روشویی ثابت'";
                                $rst2 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاسمن%' AND `category`='ظرفشویی با علم ریخته گری'";
                                $rst3 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاسمن%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                                $rst4 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%یاسمن%' AND `category`='روشویی متحرک با علم ریخته گری'";
                                $rst5 = $db->ArrayQuery($sql);
                                $nc4yas = $rst[0]['salesListPrice'] + $rst1[0]['salesListPrice'] + $rst2[0]['salesListPrice'] +$rst3[0]['salesListPrice'];

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاسمن%' AND `category`='دوش'";
                                $rst8 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاسمن%' AND `category`='توالت'";
                                $rst9 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاسمن%' AND `category`='روشویی ثابت'";
                                $rst10 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاسمن%' AND `category`='ظرفشویی با علم ریخته گری'";
                                $rst11 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاسمن%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                                $rst12 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%یاسمن%' AND `category`='روشویی متحرک با علم ریخته گری'";
                                $rst13 = $db->ArrayQuery($sql);
                                $nco4yas = $rst8[0]['salesListPrice'] + $rst9[0]['salesListPrice'] + $rst10[0]['salesListPrice'] +$rst11[0]['salesListPrice'];

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاسمن%' AND `category`='دوش'";
                                $rst16 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاسمن%' AND `category`='توالت'";
                                $rst17 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاسمن%' AND `category`='روشویی ثابت'";
                                $rst18 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاسمن%' AND `category`='ظرفشویی با علم ریخته گری'";
                                $rst19 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاسمن%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                                $rst20 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%یاسمن%' AND `category`='روشویی متحرک با علم ریخته گری'";
                                $rst21 = $db->ArrayQuery($sql);
                                $ncogo4yas = $rst16[0]['salesListPrice'] + $rst17[0]['salesListPrice'] + $rst18[0]['salesListPrice'] +$rst19[0]['salesListPrice'];

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاسمن%' AND `category`='دوش'";
                                $rst24 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاسمن%' AND `category`='توالت'";
                                $rst25 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاسمن%' AND `category`='روشویی ثابت'";
                                $rst26 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاسمن%' AND `category`='ظرفشویی با علم ریخته گری'";
                                $rst27 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاسمن%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                                $rst28 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%یاسمن%' AND `category`='روشویی متحرک با علم ریخته گری'";
                                $rst29 = $db->ArrayQuery($sql);
                                $ngo4yas = $rst24[0]['salesListPrice'] + $rst25[0]['salesListPrice'] + $rst26[0]['salesListPrice'] +$rst27[0]['salesListPrice'];

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاسمن%' AND `category`='دوش'";
                                $rst32 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاسمن%' AND `category`='توالت'";
                                $rst33 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاسمن%' AND `category`='روشویی ثابت'";
                                $rst34 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاسمن%' AND `category`='ظرفشویی با علم ریخته گری'";
                                $rst35 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاسمن%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                                $rst36 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%یاسمن%' AND `category`='روشویی متحرک با علم ریخته گری'";
                                $rst37 = $db->ArrayQuery($sql);
                                $ngom4yas = $rst32[0]['salesListPrice'] + $rst33[0]['salesListPrice'] + $rst34[0]['salesListPrice'] +$rst35[0]['salesListPrice'];

                                $htm .= '<tr style="border-top: 2px solid #000 !important;">';
                                    $htm .= '<td rowspan="6" style="text-align: center;">یاسمن 1و2</td>';
                                    $htm .= '<td style="text-align: center;">دوش</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$nc4yas.'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst8[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$nco4yas.'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst16[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$ncogo4yas.'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst24[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$ngo4yas.'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst32[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$ngom4yas.'</td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">توالت</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst1[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst9[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst17[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst25[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst33[0]['salesListPrice'].'</td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">روشویی ثابت</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst2[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst10[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst18[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst26[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst34[0]['salesListPrice'].'</td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">ظرفشویی با علم ریخته گری</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst3[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst11[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst19[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst27[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst35[0]['salesListPrice'].'</td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">ظرفشویی دیواری با علم ریخته گری</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst4[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst12[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst20[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst28[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst36[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">روشویی متحرک با علم ریخته گری</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst5[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst13[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst21[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst29[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst37[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                $htm .= '</tr>';

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%حنا%' AND `category`='دوش'";
                                $rst = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%حنا%' AND `category`='توالت'";
                                $rst1 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%حنا%' AND `category`='روشویی ثابت'";
                                $rst2 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%حنا%' AND `category`='ظرفشویی با علم تتراس'";
                                $rst3 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%حنا%' AND `category`='ظرفشویی دیواری با علم تتراس'";
                                $rst4 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%حنا%' AND `category`='روشویی متحرک با علم عصایی'";
                                $rst5 = $db->ArrayQuery($sql);
                                $nc4yas = $rst[0]['salesListPrice'] + $rst1[0]['salesListPrice'] + $rst2[0]['salesListPrice'] +$rst3[0]['salesListPrice'];

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%حنا%' AND `category`='دوش'";
                                $rst8 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%حنا%' AND `category`='توالت'";
                                $rst9 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%حنا%' AND `category`='روشویی ثابت'";
                                $rst10 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%حنا%' AND `category`='ظرفشویی با علم تتراس'";
                                $rst11 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%حنا%' AND `category`='ظرفشویی دیواری با علم تتراس'";
                                $rst12 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%حنا%' AND `category`='روشویی متحرک با علم عصایی'";
                                $rst13 = $db->ArrayQuery($sql);
                                $nco4yas = $rst8[0]['salesListPrice'] + $rst9[0]['salesListPrice'] + $rst10[0]['salesListPrice'] +$rst11[0]['salesListPrice'];

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%حنا%' AND `category`='دوش'";
                                $rst16 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%حنا%' AND `category`='توالت'";
                                $rst17 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%حنا%' AND `category`='روشویی ثابت'";
                                $rst18 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%حنا%' AND `category`='ظرفشویی با علم تتراس'";
                                $rst19 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%حنا%' AND `category`='ظرفشویی دیواری با علم تتراس'";
                                $rst20 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%حنا%' AND `category`='روشویی متحرک با علم عصایی'";
                                $rst21 = $db->ArrayQuery($sql);
                                $ncogo4yas = $rst16[0]['salesListPrice'] + $rst17[0]['salesListPrice'] + $rst18[0]['salesListPrice'] +$rst19[0]['salesListPrice'];

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%حنا%' AND `category`='دوش'";
                                $rst24 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%حنا%' AND `category`='توالت'";
                                $rst25 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%حنا%' AND `category`='روشویی ثابت'";
                                $rst26 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%حنا%' AND `category`='ظرفشویی با علم تتراس'";
                                $rst27 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%حنا%' AND `category`='ظرفشویی دیواری با علم تتراس'";
                                $rst28 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%حنا%' AND `category`='روشویی متحرک با علم عصایی'";
                                $rst29 = $db->ArrayQuery($sql);
                                $ngo4yas = $rst24[0]['salesListPrice'] + $rst25[0]['salesListPrice'] + $rst26[0]['salesListPrice'] +$rst27[0]['salesListPrice'];

                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%حنا%' AND `category`='دوش'";
                                $rst32 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%حنا%' AND `category`='توالت'";
                                $rst33 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%حنا%' AND `category`='روشویی ثابت'";
                                $rst34 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%حنا%' AND `category`='ظرفشویی با علم تتراس'";
                                $rst35 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%حنا%' AND `category`='ظرفشویی دیواری با علم تتراس'";
                                $rst36 = $db->ArrayQuery($sql);
                                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%حنا%' AND `category`='روشویی متحرک با علم عصایی'";
                                $rst37 = $db->ArrayQuery($sql);
                                $ngom4yas = $rst32[0]['salesListPrice'] + $rst33[0]['salesListPrice'] + $rst34[0]['salesListPrice'] +$rst35[0]['salesListPrice'];

                                $htm .= '<tr style="border-top: 2px solid #000 !important;">';
                                    $htm .= '<td rowspan="6" style="text-align: center;">حنا 1و2</td>';
                                    $htm .= '<td style="text-align: center;">دوش</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$nc4yas.'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst8[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$nco4yas.'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst16[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$ncogo4yas.'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst24[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$ngo4yas.'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst32[0]['salesListPrice'].'</td>';
                                    $htm .= '<td rowspan="4" style="text-align: center;">'.$ngom4yas.'</td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">توالت</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst1[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst9[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst17[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst25[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst33[0]['salesListPrice'].'</td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">روشویی ثابت</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst2[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst10[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst18[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst26[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst34[0]['salesListPrice'].'</td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">ظرفشویی با علم تتراس</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst3[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst11[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst19[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst27[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst35[0]['salesListPrice'].'</td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">ظرفشویی دیواری با علم تتراس</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst4[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst12[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst20[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst28[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst36[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                $htm .= '</tr>';

                                $htm .= '<tr>';
                                    $htm .= '<td style="text-align: center;">روشویی متحرک با علم عصایی</td>';
                                    $htm .= '<td style="text-align: center;">8</td>';
                                    $htm .= '<td style="text-align: center;">'.$rst5[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst13[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst21[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst29[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                    $htm .= '<td style="text-align: center;">'.$rst37[0]['salesListPrice'].'</td>';
                                    $htm .= '<td style="text-align: center;"></td>';
                                $htm .= '</tr>';

                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%رز%' AND `category`='دوش'";
                $rst = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%رز%' AND `category`='توالت'";
                $rst1 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%رز%' AND `category`='روشویی ثابت'";
                $rst2 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%رز%' AND `category`='ظرفشویی با علم ریخته گری'";
                $rst3 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%رز%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                $rst4 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%رز%' AND `category`='روشویی متحرک با علم ریخته گری'";
                $rst5 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%رز%' AND `category`='ظرفشویی دنباله دار با علم ریخته گری'";
                $rst6 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='کروم' AND `Series` LIKE '%رز%' AND `category`='روشویی متحرک دنباله دار با علم ریخته گری'";
                $rst7 = $db->ArrayQuery($sql);
                $nc4yas = $rst[0]['salesListPrice'] + $rst1[0]['salesListPrice'] + $rst2[0]['salesListPrice'] +$rst3[0]['salesListPrice'];

                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%رز%' AND `category`='دوش'";
                $rst8 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%رز%' AND `category`='توالت'";
                $rst9 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%رز%' AND `category`='روشویی ثابت'";
                $rst10 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%رز%' AND `category`='ظرفشویی با علم ریخته گری'";
                $rst11 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%رز%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                $rst12 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%رز%' AND `category`='روشویی متحرک با علم عصایی'";
                $rst13 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%رز%' AND `category`='ظرفشویی دنباله دار با علم ریخته گری'";
                $rst14 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید' OR `color`='مشکی' OR `color`='زیتونی') AND `Series` LIKE '%رز%' AND `category`='روشویی متحرک دنباله دار با علم ریخته گری'";
                $rst15 = $db->ArrayQuery($sql);
                $nco4yas = $rst8[0]['salesListPrice'] + $rst9[0]['salesListPrice'] + $rst10[0]['salesListPrice'] +$rst11[0]['salesListPrice'];

                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%رز%' AND `category`='دوش'";
                $rst16 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%رز%' AND `category`='توالت'";
                $rst17 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%رز%' AND `category`='روشویی ثابت'";
                $rst18 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%رز%' AND `category`='ظرفشویی با علم ریخته گری'";
                $rst19 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%رز%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                $rst20 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%رز%' AND `category`='روشویی متحرک با علم عصایی'";
                $rst21 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%رز%' AND `category`='ظرفشویی دنباله دار با علم ریخته گری'";
                $rst22 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='سفید طلایی' OR `color`='مشکی طلایی') AND `Series` LIKE '%رز%' AND `category`='روشویی متحرک دنباله دار با علم ریخته گری'";
                $rst23 = $db->ArrayQuery($sql);
                $ncogo4yas = $rst16[0]['salesListPrice'] + $rst17[0]['salesListPrice'] + $rst18[0]['salesListPrice'] +$rst19[0]['salesListPrice'];

                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%رز%' AND `category`='دوش'";
                $rst24 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%رز%' AND `category`='توالت'";
                $rst25 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%رز%' AND `category`='روشویی ثابت'";
                $rst26 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%رز%' AND `category`='ظرفشویی با علم ریخته گری'";
                $rst27 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%رز%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                $rst28 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%رز%' AND `category`='روشویی متحرک با علم عصایی'";
                $rst29 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%رز%' AND `category`='ظرفشویی دنباله دار با علم ریخته گری'";
                $rst30 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND `color`='طلایی' AND `Series` LIKE '%رز%' AND `category`='روشویی متحرک دنباله دار با علم ریخته گری'";
                $rst31 = $db->ArrayQuery($sql);
                $ngo4yas = $rst24[0]['salesListPrice'] + $rst25[0]['salesListPrice'] + $rst26[0]['salesListPrice'] +$rst27[0]['salesListPrice'];

                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%رز%' AND `category`='دوش'";
                $rst32 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%رز%' AND `category`='توالت'";
                $rst33 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%رز%' AND `category`='روشویی ثابت'";
                $rst34 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%رز%' AND `category`='ظرفشویی با علم ریخته گری'";
                $rst35 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%رز%' AND `category`='ظرفشویی دیواری با علم ریخته گری'";
                $rst36 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%رز%' AND `category`='روشویی متحرک با علم عصایی'";
                $rst37 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%رز%' AND `category`='ظرفشویی دنباله دار با علم ریخته گری'";
                $rst38 = $db->ArrayQuery($sql);
                $sql = "SELECT `salesListPrice` FROM `good` WHERE `brand`='ابرش' AND (`color`='طلایی مات' OR `color`='طرح استیل') AND `Series` LIKE '%رز%' AND `category`='روشویی متحرک دنباله دار با علم ریخته گری'";
                $rst39 = $db->ArrayQuery($sql);
                $ngom4yas = $rst32[0]['salesListPrice'] + $rst33[0]['salesListPrice'] + $rst34[0]['salesListPrice'] +$rst35[0]['salesListPrice'];

                $htm .= '<tr style="border-top: 2px solid #000 !important;">';
                $htm .= '<td rowspan="8" style="text-align: center;">رز 1و2</td>';
                $htm .= '<td style="text-align: center;">دوش</td>';
                $htm .= '<td style="text-align: center;">8</td>';
                $htm .= '<td style="text-align: center;">'.$rst[0]['salesListPrice'].'</td>';
                $htm .= '<td rowspan="4" style="text-align: center;">'.$nc4yas.'</td>';
                $htm .= '<td style="text-align: center;">'.$rst8[0]['salesListPrice'].'</td>';
                $htm .= '<td rowspan="4" style="text-align: center;">'.$nco4yas.'</td>';
                $htm .= '<td style="text-align: center;">'.$rst16[0]['salesListPrice'].'</td>';
                $htm .= '<td rowspan="4" style="text-align: center;">'.$ncogo4yas.'</td>';
                $htm .= '<td style="text-align: center;">'.$rst24[0]['salesListPrice'].'</td>';
                $htm .= '<td rowspan="4" style="text-align: center;">'.$ngo4yas.'</td>';
                $htm .= '<td style="text-align: center;">'.$rst32[0]['salesListPrice'].'</td>';
                $htm .= '<td rowspan="4" style="text-align: center;">'.$ngom4yas.'</td>';
                $htm .= '</tr>';

                $htm .= '<tr>';
                $htm .= '<td style="text-align: center;">توالت</td>';
                $htm .= '<td style="text-align: center;">8</td>';
                $htm .= '<td style="text-align: center;">'.$rst1[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;">'.$rst9[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;">'.$rst17[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;">'.$rst25[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;">'.$rst33[0]['salesListPrice'].'</td>';
                $htm .= '</tr>';

                $htm .= '<tr>';
                $htm .= '<td style="text-align: center;">روشویی ثابت</td>';
                $htm .= '<td style="text-align: center;">8</td>';
                $htm .= '<td style="text-align: center;">'.$rst2[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;">'.$rst10[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;">'.$rst18[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;">'.$rst26[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;">'.$rst34[0]['salesListPrice'].'</td>';
                $htm .= '</tr>';

                $htm .= '<tr>';
                $htm .= '<td style="text-align: center;">ظرفشویی با علم ریخته گری</td>';
                $htm .= '<td style="text-align: center;">8</td>';
                $htm .= '<td style="text-align: center;">'.$rst3[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;">'.$rst11[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;">'.$rst19[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;">'.$rst27[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;">'.$rst35[0]['salesListPrice'].'</td>';
                $htm .= '</tr>';

                $htm .= '<tr>';
                $htm .= '<td style="text-align: center;">ظرفشویی دیواری با علم ریخته گری</td>';
                $htm .= '<td style="text-align: center;">8</td>';
                $htm .= '<td style="text-align: center;">'.$rst4[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst12[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst20[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst28[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst36[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '</tr>';

                $htm .= '<tr>';
                $htm .= '<td style="text-align: center;">روشویی متحرک با علم ریخته گری</td>';
                $htm .= '<td style="text-align: center;">8</td>';
                $htm .= '<td style="text-align: center;">'.$rst5[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst13[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst21[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst29[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst37[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '</tr>';

                $htm .= '<tr>';
                $htm .= '<td style="text-align: center;">ظرفشوئی دنباله دار با علم ریخته گری</td>';
                $htm .= '<td style="text-align: center;">8</td>';
                $htm .= '<td style="text-align: center;">'.$rst6[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst14[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst22[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst30[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst38[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '</tr>';

                $htm .= '<tr>';
                $htm .= '<td style="text-align: center;">روشوئی متحرک دنباله دار با علم ریخته گری</td>';
                $htm .= '<td style="text-align: center;">8</td>';
                $htm .= '<td style="text-align: center;">'.$rst7[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst15[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst23[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst31[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '<td style="text-align: center;">'.$rst39[0]['salesListPrice'].'</td>';
                $htm .= '<td style="text-align: center;"></td>';
                $htm .= '</tr>';

                            $htm .= '</tbody>';
                        $htm .= '</table>';
                    $htm .= '</div>';
                $htm .= '</div>';
                break;
            case 5:
                $sql = "SELECT `salesListPrice`,`PerformanceDate` FROM `good` WHERE `ggroup`='لوله 5 لایه' AND `Series`='لوله های PERT' ORDER BY `cartridgeSize` ASC";
                $res = $db->ArrayQuery($sql);
                $sql1 = "SELECT `salesListPrice` FROM `good` WHERE `ggroup`='لوله 5 لایه' AND `Series`='لوله های PEX' ORDER BY `cartridgeSize` ASC";
                $res1 = $db->ArrayQuery($sql1);
                $htm .= '<div style="margin: -90px 0 0 0;width: 100%;background-color: #e9e8e6;" class="demo">';
                    $htm .= '<div style="width: 100%;background: url('.$srcc3.') no-repeat;background-size: cover;height: 600px;"></div>';

                    $htm .= '<div style="width: 100%;">';
                        $htm .= '<table style="margin: 50px auto;width: 90%;">';
                            $htm .= '<thead>';
                                $htm .= '<tr>';
                                    $htm .= '<th style="text-align: left;width: 80%"></th>';
                                    $htm .= '<th style="text-align: center;width: 20%;background-color: #fff;height: 30px;border-radius: 5px;border-color: #000 !important;border-width: 2px !important;">تاریخ اجرا : '.$ut->greg_to_jal($res[0]['PerformanceDate']).'</th>';
                                $htm .= '</tr>';
                            $htm .= '</thead>';
                        $htm .= '</table>';

                        $htm .= '<table style="margin: 0px auto 10px auto;width: 90%;border: 1px solid #000;">';
                            $htm .= '<thead>';
                                $htm .= '<tr style="height: 30px;">';
                                    $htm .= '<th style="text-align: center;width: 70%;background-color: #c9c9c9;">لیست قیمت لوله های تلفیقی پنج لایه</th>';
                                    $htm .= '<th style="text-align: center;background-color: red;color: #ffffff;width: 30%;">جهت مصرف کنندگان محترم</th>';
                                $htm .= '</tr>';
                            $htm .= '</thead>';
                        $htm .= '</table>';

                        $htm .= '<table style="margin: 0px auto 50px auto;width: 90%;" class="table-bordark">';
                            $htm .= '<thead>';
                                $htm .= '<tr style="height: 30px;background-color: #888888;color: #ffffff;">';
                                    $htm .= '<th style="text-align: center;">کد محصول</th>';
                                    $htm .= '<th style="text-align: center;">نام و سایز</th>';
                                    $htm .= '<th style="text-align: center;">متراژ در بسته</th>';
                                    $htm .= '<th style="text-align: center;">قیمت متر (ریال)</th>';
                                    $htm .= '<th style="text-align: center;">قیمت هر بسته (ریال)</th>';
                                $htm .= '</tr>';
                            $htm .= '</thead>';
                            $htm .= '<tbody>';
                                $htm .= '<tr style="height: 30px;background-color: #fff;">';
                                    $htm .= '<td style="text-align: center;">AP55-16</td>';
                                    $htm .= '<td style="text-align: center;direction: ltr;">(PERT-AL-PERT) 16mm لوله</td>';
                                    $htm .= '<td style="text-align: center;">200</td>';
                                    $pack16PERT = $res[0]['salesListPrice'] * 200;
                                    $htm .= '<td style="text-align: center;">'.number_format($res[0]['salesListPrice']).'</td>';
                                    $htm .= '<td style="text-align: center;">'.number_format($pack16PERT).'</td>';
                                $htm .= '</tr>';
                                    $htm .= '<tr style="height: 30px;background-color: #fff;">';
                                    $htm .= '<td style="text-align: center;">AP55-20</td>';
                                    $htm .= '<td style="text-align: center;direction: ltr;">(PERT-AL-PERT) 20mm لوله</td>';
                                    $htm .= '<td style="text-align: center;">150</td>';
                                    $pack20PERT = $res[1]['salesListPrice'] * 150;
                                    $htm .= '<td style="text-align: center;">'.number_format($res[1]['salesListPrice']).'</td>';
                                    $htm .= '<td style="text-align: center;">'.number_format($pack20PERT).'</td>';
                                $htm .= '</tr>';
                                $htm .= '<tr style="height: 30px;background-color: #fff;">';
                                    $htm .= '<td style="text-align: center;">AP55-25</td>';
                                    $htm .= '<td style="text-align: center;direction: ltr;">(PERT-AL-PERT) 25mm لوله</td>';
                                    $htm .= '<td style="text-align: center;">100</td>';
                                    $pack25PERT = $res[2]['salesListPrice'] * 100;
                                    $htm .= '<td style="text-align: center;">'.number_format($res[2]['salesListPrice']).'</td>';
                                    $htm .= '<td style="text-align: center;">'.number_format($pack25PERT).'</td>';
                                $htm .= '</tr>';
                                $htm .= '<tr style="height: 30px;background-color: #fff;">';
                                    $htm .= '<td style="text-align: center;">AP55-32</td>';
                                    $htm .= '<td style="text-align: center;direction: ltr;">(PERT-AL-PERT) 32mm لوله</td>';
                                    $htm .= '<td style="text-align: center;">50</td>';
                                    $pack32PERT = $res[3]['salesListPrice'] * 50;
                                    $htm .= '<td style="text-align: center;">'.number_format($res[3]['salesListPrice']).'</td>';
                                    $htm .= '<td style="text-align: center;">'.number_format($pack32PERT).'</td>';
                                $htm .= '</tr>';
                                $htm .= '<tr style="height: 30px;background-color: #fff;">';
                                    $htm .= '<td style="text-align: center;">AP56-16</td>';
                                    $htm .= '<td style="text-align: center;direction: ltr;">(PEX-AL-PEX) 16mm لوله</td>';
                                    $htm .= '<td style="text-align: center;">200</td>';
                                    $pack16PEX = $res1[0]['salesListPrice'] * 200;
                                    $htm .= '<td style="text-align: center;">'.number_format($res1[0]['salesListPrice']).'</td>';
                                    $htm .= '<td style="text-align: center;">'.number_format($pack16PEX).'</td>';
                                $htm .= '</tr>';
                                $htm .= '<tr style="height: 30px;background-color: #fff;">';
                                    $htm .= '<td style="text-align: center;">AP56-20</td>';
                                    $htm .= '<td style="text-align: center;direction: ltr;">(PEX-AL-PEX) 20mm لوله</td>';
                                    $htm .= '<td style="text-align: center;">150</td>';
                                    $pack20PEX = $res1[1]['salesListPrice'] * 150;
                                    $htm .= '<td style="text-align: center;">'.number_format($res1[1]['salesListPrice']).'</td>';
                                    $htm .= '<td style="text-align: center;">'.number_format($pack20PEX).'</td>';
                                $htm .= '</tr>';
                                $htm .= '<tr style="height: 30px;background-color: #fff;">';
                                    $htm .= '<td style="text-align: center;">AP56-25</td>';
                                    $htm .= '<td style="text-align: center;direction: ltr;">(PEX-AL-PEX) 25mm لوله</td>';
                                    $htm .= '<td style="text-align: center;">100</td>';
                                    $pack25PEX = $res1[2]['salesListPrice'] * 100;
                                    $htm .= '<td style="text-align: center;">'.number_format($res1[2]['salesListPrice']).'</td>';
                                    $htm .= '<td style="text-align: center;">'.number_format($pack25PEX).'</td>';
                                $htm .= '</tr>';
                                $htm .= '<tr style="height: 30px;background-color: #fff;">';
                                    $htm .= '<td style="text-align: center;">AP56-32</td>';
                                    $htm .= '<td style="text-align: center;direction: ltr;">(PEX-AL-PEX) 32mm لوله</td>';
                                    $htm .= '<td style="text-align: center;">50</td>';
                                    $pack32PEX = $res1[3]['salesListPrice'] * 50;
                                    $htm .= '<td style="text-align: center;">'.number_format($res1[3]['salesListPrice']).'</td>';
                                    $htm .= '<td style="text-align: center;">'.number_format($pack32PEX).'</td>';
                                $htm .= '</tr>';
                            $htm .= '</tbody>';
                        $htm .= '</table>';
                        $htm .= '<p class="mt-5 mr-5">1- قیمت های فوق بر اساس تحویل درب کارخانه و فاقد تخفیف می باشد.</p>';
                        $htm .= '<p class="mt-1 mr-5">2- به قیمت های فوق مالیات بر ارزش افزوده اضافه می گردد.</p>';
                    $htm .= '</div>';

                    $htm .= '<div style="width: 100%;background: url('.$srcc4.') no-repeat;background-size: cover;background-position: center;height: 240px;"></div>';
                $htm .= '</div>';
                break;
        }
        return $htm;
    }

    public function getSalesPriceGoodsHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $piece = new Piece();

        $brands = $piece->getBrands();
        $cntb = count($brands);
        $access = 0;
        if ($acm->hasAccess('salePriceConfirmation')) {
            $access = 1;
            $manifold = 2;
            $hiddenContentId = array();
            $hiddenContentId[0] = "hiddenSaleListPriceBody";
            $hiddenContentId[1] = "hiddenCheckPricesManageBody";

            $pagename = array();
            $pagename[0] = "قیمت فروش محصولات";
            $pagename[1] = "چک کردن قیمت ها";

            $pageIcon = array();
            $pageIcon[0] = "fa-box-open";
            $pageIcon[1] = "fa-tick";

            $contentId = array();
            $contentId[0] = "salesPriceGoodsManageBody";
            $contentId[1] = "checkPricesManageBody";

            $menuItems = array();
            $menuItems[0] = 'salesPriceGoodsManageTabID';
            $menuItems[1] = 'checkPricesManageTabID';

            $bottons = array();
            $bottons1 = array();
            $bottons2 = array();

            $c = 0;
/*            $bottons1[$c]['title'] = "خروجی PDF";
            $bottons1[$c]['jsf'] = "createSalesPriceGoodsPDF";
            $bottons1[$c]['icon'] = "fa-file-pdf";
            $c++;*/

            $bottons1[$c]['title'] = "مقایسه BOM";
            $bottons1[$c]['jsf'] = "salesPriceGoodsCompareBOM";
            $bottons1[$c]['icon'] = "fa-american-sign-language-interpreting";

            if ($acm->hasAccess('createNewSalesPriceGoodsList')) {
                $c++;
                $bottons1[$c]['title'] = "آپلود قیمت ها";
                $bottons1[$c]['jsf'] = "uploadSalesPriceGoods";
                $bottons1[$c]['icon'] = "fa-upload";
                $bottons1[$c]['id'] = 'id="uploadSalesPriceGoodsID"';
                $c++;

                $bottons1[$c]['title'] = "بارگذاری مجدد قیمت ها";
                $bottons1[$c]['jsf'] = "uploadAgainSalesPriceGoods";
                $bottons1[$c]['icon'] = "fa-plus-square";
                $bottons1[$c]['id'] = 'id="uploadAgainSalesPriceGoodsID"';
            }

            if ($acm->hasAccess('perDiscountAccess')) {
                $c++;
                $bottons1[$c]['title'] = "درصد تخفیفات فروش";
                $bottons1[$c]['jsf'] = "editCreatePerDiscount";
                $bottons1[$c]['icon'] = "fa-percentage";
            }
/*            $c++;

            $bottons1[$c]['title'] = "برو";
            $bottons1[$c]['jsf'] = "salesPriceGoodSalePrice";
            $bottons1[$c]['icon'] = "fa-american-sign-language-interpreting";*/

            $bottons[0] = $bottons1;
            $bottons[1] = $bottons2;

            $headerSearch = array();
            $headerSearch1 = array();
            $headerSearch2 = array();

            $c = 0;
            $headerSearch1[$c]['type'] = "text";
            $headerSearch1[$c]['width'] = "200px";
            $headerSearch1[$c]['id'] = "salesPriceGoodsManageGNameSearch";
            $headerSearch1[$c]['title'] = "نام محصول";
            $headerSearch1[$c]['placeholder'] = "نام محصول";
            $c++;

            $headerSearch1[$c]['type'] = "text";
            $headerSearch1[$c]['width'] = "120px";
            $headerSearch1[$c]['id'] = "salesPriceGoodsManagePCodeSearch";
            $headerSearch1[$c]['title'] = "کد محصول";
            $headerSearch1[$c]['placeholder'] = "کد محصول";
            $c++;

            $headerSearch1[$c]['type'] = "btn";
            $headerSearch1[$c]['title'] = "جستجو&nbsp;&nbsp;<i class='fas fa-search'></i>";
            $headerSearch1[$c]['jsf'] = "showSalesPriceGoodsManageList";

            $list = $this->getNewSalesList();
            $cnt = count($list) / 2;
            $c = 0;
            $headerSearch2[$c]['type'] = "select";
            $headerSearch2[$c]['id'] = "checkPricesManageCategorySearch";
            $headerSearch2[$c]['title'] = "انتخاب لیست";
            $headerSearch2[$c]['width'] = "200px";
            $headerSearch2[$c]['options'] = array();
            $headerSearch2[$c]['options'][0]["title"] = 'لیست مورد نظر را انتخاب کنید';
            $headerSearch2[$c]['options'][0]["value"] = -1;
            $x = 0;
            for ($i = 0; $i < $cnt; $i++) {
                $headerSearch2[$c]['options'][$i + 1]["title"] = $list[$x + 1];
                $headerSearch2[$c]['options'][$i + 1]["value"] = $list[$x];
                $x += 2;
            }
            $c++;

            $headerSearch2[$c]['type'] = "text";
            $headerSearch2[$c]['width'] = "80px";
            $headerSearch2[$c]['id'] = "checkPricesManageSPercentSearch";
            $headerSearch2[$c]['title'] = "از درصد مجاز";
            $headerSearch2[$c]['placeholder'] = "از درصد مجاز";
            $c++;

            $headerSearch2[$c]['type'] = "text";
            $headerSearch2[$c]['width'] = "80px";
            $headerSearch2[$c]['id'] = "checkPricesManageEPercentSearch";
            $headerSearch2[$c]['title'] = "تا درصد مجاز";
            $headerSearch2[$c]['placeholder'] = "تا درصد مجاز";
            $c++;

            $headerSearch2[$c]['type'] = "btn";
            $headerSearch2[$c]['title'] = "بررسی&nbsp;&nbsp;<i class='fas fa-search'></i>";
            $headerSearch2[$c]['jsf'] = "showCheckPricesManageList";
            $c++;

            $headerSearch2[$c]['type'] = "text";
            $headerSearch2[$c]['width'] = "100px";
            $headerSearch2[$c]['id'] = "checkPricesManagePDateSearch";
            $headerSearch2[$c]['title'] = "تاریخ اجرا";
            $headerSearch2[$c]['placeholder'] = "تاریخ اجرا";
            $c++;

            $headerSearch2[$c]['type'] = "btn";
            $headerSearch2[$c]['title'] = "ثبت نهایی&nbsp;&nbsp;<i class='fas fa-check'></i>";
            $headerSearch2[$c]['jsf'] = "questionCreateNewSalesPriceGoodsList";

            $headerSearch[0] = $headerSearch1;
            $headerSearch[1] = $headerSearch2;

            $htm = $ut->getHtmlOfDefaultManagementMultiPage($pagename, $pageIcon, $contentId, $menuItems, $bottons, $manifold, $headerSearch,$hiddenContentId);
        }else{
            $hiddenContentId = "hiddenSaleListPriceBody";
            $pagename = "قیمت فروش محصولات";
            $pageIcon = "fas fa-box-open";
            $contentId = "salesPriceGoodsManageBody";

            $c = 0;
            $bottons= array();
/*            $bottons[$c]['title'] = "خروجی اکسل";
            $bottons[$c]['jsf'] = "createSalesPriceGoodsExcel";
            $bottons[$c]['icon'] = "fa-file-excel";
            $c++;*/

            $bottons[$c]['title'] = "مقایسه BOM";
            $bottons[$c]['jsf'] = "salesPriceGoodsCompareBOM";
            $bottons[$c]['icon'] = "fa-american-sign-language-interpreting";

            if ($acm->hasAccess('createNewSalesPriceGoodsList')) {
                $c++;
                $bottons[$c]['title'] = "آپلود قیمت ها";
                $bottons[$c]['jsf'] = "uploadSalesPriceGoods";
                $bottons[$c]['icon'] = "fa-upload";
                $bottons[$c]['id'] = 'id="uploadSalesPriceGoodsID"';
                $c++;

                $bottons[$c]['title'] = "بارگذاری مجدد قیمت ها";
                $bottons[$c]['jsf'] = "uploadAgainSalesPriceGoods";
                $bottons[$c]['icon'] = "fa-plus-square";
                $bottons[$c]['id'] = 'id="uploadAgainSalesPriceGoodsID"';
            }

            if ($acm->hasAccess('perDiscountAccess')) {
                $c++;
                $bottons[$c]['title'] = "درصد تخفیفات فروش";
                $bottons[$c]['jsf'] = "editCreatePerDiscount";
                $bottons[$c]['icon'] = "fa-percentage";
            }

            $c = 0;
            $headerSearch = array();
            $headerSearch[$c]['type'] = "text";
            $headerSearch[$c]['width'] = "200px";
            $headerSearch[$c]['id'] = "salesPriceGoodsManageGNameSearch";
            $headerSearch[$c]['title'] = "نام محصول";
            $headerSearch[$c]['placeholder'] = "نام محصول";
            $c++;

            $headerSearch[$c]['type'] = "text";
            $headerSearch[$c]['width'] = "120px";
            $headerSearch[$c]['id'] = "salesPriceGoodsManagePCodeSearch";
            $headerSearch[$c]['title'] = "کد محصول";
            $headerSearch[$c]['placeholder'] = "کد محصول";
            $c++;

            $headerSearch[$c]['type'] = "btn";
            $headerSearch[$c]['title'] = "جستجو&nbsp;&nbsp;<i class='fas fa-search'></i>";
            $headerSearch[$c]['jsf'] = "showSalesPriceGoodsManageList";

            $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch,'',array(),$hiddenContentId);
        }

        //++++++++++++++++++ Start Good Pieces Modal ++++++++++++++++++
        $modalID = "goodManagePiecesModal";
        $modalTitle = "اجزای محصول";
        $style = 'style="max-width: 1000px;"';
        $ShowDescription = 'good-manage-Pieces-body';
        $items = array();
        $footerBottons = array();
        $showGoodPieces = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++ End Good Pieces Modal ++++++++++++++++++
        //++++++++++++++++++ Start Good Compare BOM Modal ++++++++++++++++++
        $modalID = "goodCompareBOMModal";
        $modalTitle = "مقایسه BOM";
        $style = 'style="max-width: 1000px;"';
        $ShowDescription = 'good-Compare-BOM-body';
        $items = array();
        $c = 0;
        $items[$c]['type'] = "inputGroup";
        $items[$c]['id'] = "goodCompareBOMGname";
        $items[$c]['icon'] = "fa-plus fa-lg";
        $items[$c]['onclick'] = "onclick='addGoods()'";
        $items[$c]['title'] = "انتخاب محصول";
        $items[$c]['placeholder'] = "تایپ کنید";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "goodCompareBOMGnames";
        $items[$c]['style'] = "style='resize: none;'";
        $items[$c]['title'] = "محصولات";
        $items[$c]['placeholder'] = "فقط دو محصول باید انتخاب شود";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "showGoodsCompareBomList";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";

        $showGoodCompareBom = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++ End Good Compare BOM Modal ++++++++++++++++++
        //++++++++++++++++++++++++++++++ Change MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "questionCreateNewSalesPriceGoodsModal";
        $modalTitle = "هشدار";
        $style = 'style="max-width: 600px;"';

        $modalTxt = "<p style='font-size: 17px;'>آیا نسبت به ایجاد لیست جدید مطمئن هستید؟ </p><br><p>توجه : ایجاد لیست جدید قیمت فروش، عملی غیر قابل بازگشت می باشد. لطفا دقت فرمایید !!!</p>";
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCreateFinalSalesPriceGoodsList";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createFinalSalesListPrice = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt,'',$style);
        //++++++++++++++++++++++++++++++END OF Change MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++ EDIT CREATE PERDISCOUNT MODAL ++++++++++++++++++
        $modalID = "perDiscountManageModal";
        $modalTitle = "فرم ثبت/ویرایش درصد تخفیفات فروش";
        $style = "style = 'max-width: 650px;'";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "perDiscountManageBrand";
        $items[$c]['title'] = "برند محصول";
        $items[$c]['onchange'] = "onchange=findDiscountGoodGroup()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntb;$i++){
            $items[$c]['options'][$i+1]["title"] = $brands[$i]['title'];
            $items[$c]['options'][$i+1]["value"] = $brands[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "perDiscountManageGroup";
        $items[$c]['title'] = "گروه محصول";
        $items[$c]['onchange'] = "onchange=getPerDiscounts()";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 30%;'";
        $items[$c]['id'] = "perDiscountManagePriorityDis1";
        $items[$c]['title'] = "تخفیف خرید مدت دار - اولویت";
        $items[$c]['id1'] = "perDiscountManageDis1";
        $items[$c]['id2'] = "perDiscountManagePriority1";
        $items[$c]['placeholder1'] = "درصد";
        $items[$c]['placeholder2'] = "اولویت";
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 30%;'";
        $items[$c]['id'] = "perDiscountManagePriorityDis2";
        $items[$c]['title'] = "تخفیف نمایندگی - اولویت";
        $items[$c]['id1'] = "perDiscountManageDis2";
        $items[$c]['id2'] = "perDiscountManagePriority2";
        $items[$c]['placeholder1'] = "درصد";
        $items[$c]['placeholder2'] = "اولویت";
        $c++;

        $items[$c]['type'] = 'twoText';
        $items[$c]['style'] = "style='width: 30%;'";
        $items[$c]['id'] = 'perDiscountManagePriorityDis3';
        $items[$c]['title'] = 'تخفیف پرداخت نقدی - اولویت';
        $items[$c]['id1'] = 'perDiscountManageDis3';
        $items[$c]['id2'] = 'perDiscountManagePriority3';
        $items[$c]['placeholder1'] = "درصد";
        $items[$c]['placeholder2'] = "اولویت";
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 30%;'";
        $items[$c]['id'] = "perDiscountManagePriorityDis4";
        $items[$c]['title'] = "تخفیف خرید لوله و اتصالات باهم - اولویت";
        $items[$c]['id1'] = "perDiscountManageDis4";
        $items[$c]['id2'] = "perDiscountManagePriority4";
        $items[$c]['placeholder1'] = "درصد";
        $items[$c]['placeholder2'] = "اولویت";
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 30%;'";
        $items[$c]['id'] = "perDiscountManagePriorityDis4";
        $items[$c]['title'] = "تخفیف پایان دوره - اولویت";
        $items[$c]['id1'] = "perDiscountManageDis5";
        $items[$c]['id2'] = "perDiscountManagePriority5";
        $items[$c]['placeholder1'] = "درصد";
        $items[$c]['placeholder2'] = "اولویت";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "perDiscountManageHiddenWid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doeEditCreatePerDiscount";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $perDiscountManageModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++ END OF EDIT CREATE PERDISCOUNT MODAL ++++++++++++++++++++++++
        //++++++++++++++++++ create One New Sales Price Goods List Modal ++++++++++++++++++
        $modalID = "createOneNewSalesPriceGoodsModal";
        $modalTitle = "فرم ویرایش قیمت فروش";
        $style = "style = 'max-width: 550px;'";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "oneNewSalesPriceGoodsCalcType";
        $items[$c]['title'] = "روش محاسبه";
        $items[$c]['onchange'] = "onchange=showFieldOfCalcType()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'عادی';
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = 'فرمولی';
        $items[$c]['options'][1]["value"] = 1;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "oneNewSalesPriceGoodsGName";
        $items[$c]['title'] = "نام محصول";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "oneNewSalesPriceGoodsDivisibility";
        $items[$c]['title'] = "بخش پذیری";
        $items[$c]['placeholder'] = "عدد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "oneNewSalesPriceGoodsCoefficient";
        $items[$c]['title'] = "ضریب";
        $items[$c]['placeholder'] = "عدد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "oneNewSalesPriceGoodsDTNBLGHGH";
        $items[$c]['title'] = "درصد تغییر نسبت به لیست قیمت قبلی";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "oneNewSalesPriceGoodsMSTNBLGHGH";
        $items[$c]['title'] = "مقدار ثابت تغییر نسبت به لیست قیمت قبلی";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "oneNewSalesPriceGoodsHiddenGid";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "oneNewSalesPriceGoodsHiddenChromeIsNo";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "oneNewSalesPriceGoodsHiddenGGroup";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "oneNewSalesPriceGoodsHiddenPage";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCreateOneNewSalesPriceGoodsModal";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createOneNewSalesPriceGoodsList = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++ END OF create One New Sales Price Goods List Modal ++++++++++++++++++++++++
        //++++++++++++++++++ print sale List price Modal ++++++++++++++++++
        $modalID = "createSalesPriceGoodsPDFModal";
        $modalTitle = "فرم انتخاب لیست قیمت";
        $style = 'style="max-width: 700px;"';

        $items = array();
        $c = 0;
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "saleListPriceCategory";
        $items[$c]['title'] = "انتخاب لیست";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = '----------';
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = 'شیرآلات ابرش';
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = 'شیرآلات بارشینا';
        $items[$c]['options'][2]["value"] = 1;
        $items[$c]['options'][3]["title"] = 'شیلنگ ابرش';
        $items[$c]['options'][3]["value"] = 2;
        $items[$c]['options'][4]["title"] = 'شیلنگ بارشینا';
        $items[$c]['options'][4]["value"] = 3;
        $items[$c]['options'][5]["title"] = 'اتصالات 5 لایه';
        $items[$c]['options'][5]["value"] = 4;
        $items[$c]['options'][6]["title"] = 'لوله 5 لایه';
        $items[$c]['options'][6]["value"] = 5;

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "printSaleListPrice";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createSaleListPricePDF = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++ END OF print sale List price Modal ++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ UPLOAD Good Sale Price List File MODAL++++++++++++++++++++++++++++++++
        $modalID = "goodSalePriceListModal";
        $modalTitle = "آپلود فایل قیمت فروش محصولات";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "file";
        $items[$c]['id'] = "goodSalePriceList_File";
        $items[$c]['helpText'] = "نوع فایل باید XLSX باشد.";
        $items[$c]['title'] = "فایل قیمت فروش محصولات";
        $items[$c]['accept'] = "accept='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doUploadGoodSalePriceFile";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $uploadGoodSalePriceListModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END UPLOAD Good Sale Price List File MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++ edit One Sales Price Goods List Modal ++++++++++++++++++
        $modalID = "editOneSalesPriceGoodsListModal";
        $modalTitle = "فرم ویرایش قیمت فروش";
        $style = "style = 'max-width: 550px;'";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "editOneSalesPriceGoodsAmount";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['title'] = "قیمت";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "editOneSalesPriceGoodsHiddenGid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditOneSalesPriceGoodsList";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editOneSalesPriceGoodsList = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++ END OF edit One Sales Price Goods List Modal ++++++++++++++++++++++++

        $htm .= $showGoodPieces;
        $htm .= $showGoodCompareBom;
        $htm .= $perDiscountManageModal;
        $htm .= $createFinalSalesListPrice;
        $htm .= $createOneNewSalesPriceGoodsList;
        $htm .= $createSaleListPricePDF;
        $htm .= $uploadGoodSalePriceListModal;
        $htm .= $editOneSalesPriceGoodsList;
        $send = array($access,$htm);
        return $send;
    }

    private function getNewSalesList(){
        $db = new DBi();
        $list = array();
        $sql = "SELECT `RowID` FROM `good` WHERE `newSalesListPrice` IS NOT NULL AND `ggroup`='شیرآلات' AND `brand`='ابرش'";
        $rst = $db->ArrayQuery($sql);
        if (count($rst) > 0){
            $list[] = 0;
            $list[] = 'شیرآلات ابرش';
        }
        $sql = "SELECT `RowID` FROM `good` WHERE `newSalesListPrice` IS NOT NULL AND `ggroup`='شیرآلات' AND `brand`='بارشینا'";
        $rst = $db->ArrayQuery($sql);
        if (count($rst) > 0){
            $list[] = 1;
            $list[] = 'شیرآلات بارشینا';
        }
        $sql = "SELECT `RowID` FROM `good` WHERE `newSalesListPrice` IS NOT NULL AND `ggroup`='شیلنگ' AND `brand`='ابرش'";
        $rst = $db->ArrayQuery($sql);
        if (count($rst) > 0){
            $list[] = 2;
            $list[] = 'شیلنگ ابرش';
        }
        $sql = "SELECT `RowID` FROM `good` WHERE `newSalesListPrice` IS NOT NULL AND `ggroup`='شیلنگ' AND `brand`='بارشینا'";
        $rst = $db->ArrayQuery($sql);
        if (count($rst) > 0){
            $list[] = 3;
            $list[] = 'شیلنگ بارشینا';
        }
        $sql = "SELECT `RowID` FROM `good` WHERE `newSalesListPrice` IS NOT NULL AND `ggroup`='اتصالات 5 لایه'";
        $rst = $db->ArrayQuery($sql);
        if (count($rst) > 0){
            $list[] = 4;
            $list[] = 'اتصالات 5 لایه';
        }
        $sql = "SELECT `RowID` FROM `good` WHERE `newSalesListPrice` IS NOT NULL AND `ggroup`='لوله 5 لایه'";
        $rst = $db->ArrayQuery($sql);
        if (count($rst) > 0){
            $list[] = 5;
            $list[] = 'لوله 5 لایه';
        }
        return $list;
    }

    public function getSalesPriceGoodsList($gName,$gCode,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`gCode` LIKE "%'.$gCode.'%" ';
        }
        $sql = "SELECT `RowID`,`gName`,`gCode`,`salesListPrice`,`Discount1`,`Discount2`,`Discount3`,`Discount4`,`Discount5`,`updatePriceDate` FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE `isEnable`=1 AND ".$where;
        }else{
            $sql .= " WHERE `isEnable`=1";
        }
        $sql .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['salesListPrice'] = (intval($res[$y]['salesListPrice']) > 0 ? number_format($res[$y]['salesListPrice']).' ریال' : '');
            $finalRes[$y]['Discount1'] = (intval($res[$y]['Discount1']) > 0 ? number_format($res[$y]['Discount1']).' ریال' : '');
            $finalRes[$y]['Discount2'] = (intval($res[$y]['Discount2']) > 0 ? number_format($res[$y]['Discount2']).' ریال' : '');
            $finalRes[$y]['Discount3'] = (intval($res[$y]['Discount3']) > 0 ? number_format($res[$y]['Discount3']).' ریال' : '');
            $finalRes[$y]['Discount4'] = (intval($res[$y]['Discount4']) > 0 ? number_format($res[$y]['Discount4']).' ریال' : '');
            $finalRes[$y]['Discount5'] = (intval($res[$y]['Discount5']) > 0 ? number_format($res[$y]['Discount5']).' ریال' : '');
        }
        $updatePriceDate = $ut->greg_to_jal($res[0]['updatePriceDate']);
        $send = array($finalRes,$updatePriceDate);
        return $send;
    }

    public function getSalesPriceGoodsListCountRows($gName,$gCode){
        $acm = new acm();
        if(!$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $w = array();
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`gCode` LIKE "%'.$gCode.'%" ';
        }
        $sql = "SELECT `RowID` FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE `isEnable`=1 AND ".$where;
        }else{
            $sql .= " WHERE `isEnable`=1";
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function updateSalePriceList(){
        $acm = new acm();
        if(!$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        mysqli_autocommit($db->Getcon(),FALSE);
        $flag = true;

        $sql = "SELECT `RowID`,`brand`,`ggroup`,`salesListPrice` FROM `good`";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        for ($i=0;$i<$cnt;$i++){
            $query = "SELECT `perDiscount1`,`perDiscount2`,`perDiscount3`,`perDiscount4`,`perDiscount5`,`Priority1`,`Priority2`,`Priority3`,`Priority4`,`Priority5` FROM `discounts` WHERE `brand`='{$res[$i]['brand']}' AND `subGroup`='{$res[$i]['ggroup']}'";
            $rst = $db->ArrayQuery($query);

            if (count($rst) > 0) {
                $Priority = array($rst[0]['Priority1'], $rst[0]['perDiscount1'], $rst[0]['Priority2'], $rst[0]['perDiscount2'], $rst[0]['Priority3'], $rst[0]['perDiscount3'], $rst[0]['Priority4'], $rst[0]['perDiscount4'], $rst[0]['Priority5'], $rst[0]['perDiscount5']);
                $x = 0;
                for ($j = 0; $j < 5; $j++) {
                    switch ($Priority[$x]) {
                        case 1:
                            $t1 = $Priority[$x + 1] / 100;
                            break;
                        case 2:
                            $t2 = $Priority[$x + 1] / 100;
                            break;
                        case 3:
                            $t3 = $Priority[$x + 1] / 100;
                            break;
                        case 4:
                            $t4 = $Priority[$x + 1] / 100;
                            break;
                        case 5:
                            $t5 = $Priority[$x + 1] / 100;
                            break;
                    }
                    $x += 2;
                }

                $pt1 = $res[$i]['salesListPrice'] * (1 - $t1);
                $pt2 = $pt1 * (1 - $t2);
                $pt3 = $pt2 * (1 - $t3);
                $pt4 = $pt3 * (1 - $t4);
                $pt5 = $pt4 * (1 - $t5);

                $x = 0;
                for ($j = 0; $j < 5; $j++) {
                    switch ($Priority[$x]) {
                        case 1:
                            $Priority[$x + 1] = $pt1;
                            break;
                        case 2:
                            $Priority[$x + 1] = $pt2;
                            break;
                        case 3:
                            $Priority[$x + 1] = $pt3;
                            break;
                        case 4:
                            $Priority[$x + 1] = $pt4;
                            break;
                        case 5:
                            $Priority[$x + 1] = $pt5;
                            break;
                    }
                    $x += 2;
                }
            }else{
                $Priority[1] = 0;
                $Priority[3] = 0;
                $Priority[5] = 0;
                $Priority[7] = 0;
                $Priority[9] = 0;
            }

            $qq = "UPDATE `good` SET `Discount1`={$Priority[1]},`Discount2`={$Priority[3]},`Discount3`={$Priority[5]},`Discount4`={$Priority[7]},`Discount5`={$Priority[9]} WHERE `RowID`={$res[$i]['RowID']}";
            $db->Query($qq);
            $aff = $db->AffectedRows();
            $aff = ((intval($aff) == -1) ? 0 : 1);
            if ($aff == 0){
                $flag = false;
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

    public function showGoodCompareBom($CBG){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood') && !$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }

        $gnames = explode(',',$CBG);
        if (count($gnames) !== 2){
            return false;
        }else{
            $db = new DBi();
            $sql = "SELECT `gCode` FROM `good` WHERE `gName`='{$gnames[0]}'";
            $res = $db->ArrayQuery($sql);

            $sql1 = "SELECT `gCode` FROM `good` WHERE `gName`='{$gnames[1]}'";
            $res1 = $db->ArrayQuery($sql1);

            $sql2 = "SELECT `pCode` FROM `piece`
                 INNER JOIN `interface` ON (`piece`.`pCode`=`interface`.`PieceCode` AND `interface`.`ProductCode`='{$res[0]['gCode']}')";
            $res2 = $db->ArrayQuery($sql2);

            $sql3 = "SELECT `pCode` FROM `piece` INNER JOIN `interface` ON (`piece`.`pCode`=`interface`.`PieceCode` AND `interface`.`ProductCode`='{$res1[0]['gCode']}')";
            $res3 = $db->ArrayQuery($sql3);

            $CountRes = count($res2);
            $CountRess = count($res3);
            $pcodes = array();

            for ($j=0;$j<$CountRes;$j++){
                $pcodes[] = $res2[$j]['pCode'];
            }
            for ($j=0;$j<$CountRess;$j++){
                $pcodes[] = $res3[$j]['pCode'];
            }
            $pcodes = array_values(array_unique($pcodes));
            $cnt = count($pcodes);

            $htm = '<table class="table table-bordered table-hover table-responsive table-sm" style="display: inline-table;" id="goodPieces1-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 7%;">کد قطعه</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 32%;">نام قطعه</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 7%;">واحد قطعه</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 27%;">'.$gnames[0].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 27%;">'.$gnames[1].'</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';
            for ($i = 0; $i < $cnt; $i++) {
                $sql4 = "SELECT `pUnit`,`pName`,`amount` FROM `piece`
                     INNER JOIN `interface` ON (`piece`.`pCode`=`interface`.`PieceCode` AND `interface`.`ProductCode`='{$res[0]['gCode']}' AND `interface`.`PieceCode`='{$pcodes[$i]}')";
                $res4 = $db->ArrayQuery($sql4);

                $sql5 = "SELECT `pUnit`,`pName`,`amount` FROM `piece`
                     INNER JOIN `interface` ON (`piece`.`pCode`=`interface`.`PieceCode` AND `interface`.`ProductCode`='{$res1[0]['gCode']}' AND `interface`.`PieceCode`='{$pcodes[$i]}')";
                $res5 = $db->ArrayQuery($sql5);

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;">' . $pcodes[$i] . '</td>';
                if (count($res4) > 0) {
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;">' . $res4[0]['pName'] . '</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;">' . $res4[0]['pUnit'] . '</td>';
                }else{
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;">' . $res5[0]['pName'] . '</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;">' . $res5[0]['pUnit'] . '</td>';
                }
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;">' . $res4[0]['amount'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;">' . $res5[0]['amount'] . '</td>';
                $htm .= '</tr>';
            }
            $htm .= '</tbody>';
            $htm .= '</table>';

            return $htm;
        }
    }

    public function getIncreaseChrome(){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood') && !$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT * FROM `increase_compared_chrome`";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            $res = array("ChromeCOCHRI"=>(number_format($res[0]['ChromeCOCHRI'])),
                         "ChromeCOGOCHRI"=>(number_format($res[0]['ChromeCOGOCHRI'])),
                         "ChromeGOCHRI"=>(number_format($res[0]['ChromeGOCHRI'])),
                         "ChromeGOMCHRI"=>(number_format($res[0]['ChromeGOMCHRI'])),
                         "ChromeCOCHMA"=>(number_format($res[0]['ChromeCOCHMA'])),
                         "ChromeCOGOCHMA"=>(number_format($res[0]['ChromeCOGOCHMA'])),
                         "ChromeGOCHMA"=>(number_format($res[0]['ChromeGOCHMA'])),
                         "ChromeGOMCHMA"=>(number_format($res[0]['ChromeGOMCHMA'])),
                         "ChromeDPer"=>($res[0]['ChromeDPer']),
                         "ChromeDTPer"=>($res[0]['ChromeDTPer']),
                         "ChromeDSPer"=>($res[0]['ChromeDSPer']),
                         "ChromeDKAPer"=>($res[0]['ChromeDKAPer']),
                         "ChromeTPer"=>($res[0]['ChromeTPer']),
                         "ChromeRSPer"=>($res[0]['ChromeRSPer']),
                         "ChromeRMPer"=>($res[0]['ChromeRMPer']),
                         "ChromeRPBPer"=>($res[0]['ChromeRPBPer']),
                         "ChromeRMARPer"=>($res[0]['ChromeRMARPer']),
                         "ChromeRMAAPer"=>($res[0]['ChromeRMAAPer']),
                         "ChromeRMDARPer"=>($res[0]['ChromeRMDARPer']),
                         "ChromeZPer"=>($res[0]['ChromeZPer']),
                         "ChromeZRPer"=>($res[0]['ChromeZRPer']),
                         "ChromeZALRPer"=>($res[0]['ChromeZALRPer']),
                         "ChromeZAUPer"=>($res[0]['ChromeZAUPer']),
                         "ChromeZATPer"=>($res[0]['ChromeZATPer']),
                         "ChromeZARPer"=>($res[0]['ChromeZARPer']),
                         "ChromeZASPer"=>($res[0]['ChromeZASPer']),
                         "ChromeZAF30Per"=>($res[0]['ChromeZAF30Per']),
                         "ChromeZAF25Per"=>($res[0]['ChromeZAF25Per']),
                         "ChromeZAFPer"=>($res[0]['ChromeZAFPer']),
                         "ChromeZANPer"=>($res[0]['ChromeZANPer']),
                         "ChromeZG360Per"=>($res[0]['ChromeZG360Per']),
                         "ChromeZBAAPer"=>($res[0]['ChromeZBAAPer']),
                         "ChromeZTPer"=>($res[0]['ChromeZTPer']),
                         "ChromeZDARPer"=>($res[0]['ChromeZDARPer']),
                         "ChromeZDPer"=>($res[0]['ChromeZDPer']),
                         "ChromeZDATPer"=>($res[0]['ChromeZDATPer']),
                         "ChromeZDiARPer"=>($res[0]['ChromeZDiARPer']),
                         "ChromeZSHPer"=>($res[0]['ChromeZSHPer']),
                         "ChromeZMANPer"=>($res[0]['ChromeZMANPer']),
                         "ChromeMTPer"=>($res[0]['ChromeMTPer']),
                         "ChromeMDT1Per"=>($res[0]['ChromeMDT1Per']),
                         "ChromeMDT2Per"=>($res[0]['ChromeMDT2Per']),
                         "ChromeMDT3Per"=>($res[0]['ChromeMDT3Per']),
                         "ChromeMRPer"=>($res[0]['ChromeMRPer']),

                         "ChromeBarshinCOCHRI"=>(number_format($res[0]['ChromeBarshinCOCHRI'])),
                         "ChromeBarshinCOGOCHRI"=>(number_format($res[0]['ChromeBarshinCOGOCHRI'])),
                         "ChromeBarshinGOCHRI"=>(number_format($res[0]['ChromeBarshinGOCHRI'])),
                         "ChromeBarshinGOMCHRI"=>(number_format($res[0]['ChromeBarshinGOMCHRI'])),
                         "ChromeBarshinCOCHMA"=>(number_format($res[0]['ChromeBarshinCOCHMA'])),
                         "ChromeBarshinCOGOCHMA"=>(number_format($res[0]['ChromeBarshinCOGOCHMA'])),
                         "ChromeBarshinGOCHMA"=>(number_format($res[0]['ChromeBarshinGOCHMA'])),
                         "ChromeBarshinGOMCHMA"=>(number_format($res[0]['ChromeBarshinGOMCHMA'])),
                         "ChromeBarshinTPer"=>($res[0]['ChromeBarshinTPer']),
                         "ChromeBarshinDPer"=>($res[0]['ChromeBarshinDPer']),
                         "ChromeBarshinDKPer"=>($res[0]['ChromeBarshinDKPer']),
                         "ChromeBarshinRZASPer"=>($res[0]['ChromeBarshinRZASPer']),
                         "ChromeBarshinRSPer"=>($res[0]['ChromeBarshinRSPer']),
                         "ChromeBarshinRMPer"=>($res[0]['ChromeBarshinRMPer']),
                         "ChromeBarshinRMAAPer"=>($res[0]['ChromeBarshinRMAAPer']),
                         "ChromeBarshinZPer"=>($res[0]['ChromeBarshinZPer']),
                         "ChromeBarshinZAPPer"=>($res[0]['ChromeBarshinZAPPer']),
                         "ChromeBarshinZDPer"=>($res[0]['ChromeBarshinZDPer']),
                         "ChromeBarshinZDiPer"=>($res[0]['ChromeBarshinZDiPer']),
                         "ChromeBarshinZDAPPer"=>($res[0]['ChromeBarshinZDAPPer']),
                         "ChromeBarshinZDASPer"=>($res[0]['ChromeBarshinZDASPer']),
                         "ChromeBarshinZAASPer"=>($res[0]['ChromeBarshinZAASPer']),

                         "TNBLGHGH"=>($res[0]['TNBLGHGH']),
                         "TNBLGHGHBarshina"=>($res[0]['TNBLGHGHBarshina']),
                         "TNBLGHGHFitting"=>($res[0]['TNBLGHGHFitting']),
                         "TNBLGHGHPipePex"=>($res[0]['TNBLGHGHPipePex']),
                         "TNBLGHGHPipePert"=>($res[0]['TNBLGHGHPipePert']),
                         "TNBLGHGHAHose"=>($res[0]['TNBLGHGHAHose']),
                         "TNBLGHGHBHose"=>($res[0]['TNBLGHGHBHose'])
            );
            return $res;
        }else{
            $res = array("ChromeCOCHRI"=>'',"ChromeCOGOCHRI"=>'',"ChromeGOCHRI"=>'',"ChromeGOMCHRI"=>'',"ChromeCOCHMA"=>'',"ChromeCOGOCHMA"=>'',"ChromeGOCHMA"=>'',"ChromeGOMCHMA"=>'',"ChromeBarshinCOCHRI"=>'',"ChromeBarshinCOGOCHRI"=>'',"ChromeBarshinGOCHRI"=>'',"ChromeBarshinGOMCHRI"=>'',"ChromeBarshinCOCHMA"=>'',"ChromeBarshinCOGOCHMA"=>'',"ChromeBarshinGOCHMA"=>'',"ChromeBarshinGOMCHMA"=>''
            );
            return $res;
        }
    }

    public function getGoodCalcInfo($gid){
        $db = new DBi();
        $sql = "SELECT `color`,`priceChangePercent`,`priceChangeConstant`,`ggroup`,`Series`,`calcType`,`gid`,`coefficient`,`divisibility` FROM `good` WHERE `RowID`={$gid}";
        $res = $db->ArrayQuery($sql);
        $query = "SELECT `gName` FROM `good` WHERE `RowID`={$res[0]['gid']}";
        $rst = $db->ArrayQuery($query);
        if (count($res) > 0) {
            $res = array('color' => $res[0]['color'], 'priceChangePercent' => $res[0]['priceChangePercent'],'priceChangeConstant' => $res[0]['priceChangeConstant'],'ggroup' => $res[0]['ggroup'],'Series' => $res[0]['Series'],'calcType' => $res[0]['calcType'],'gName' => $rst[0]['gName'],'coefficient' => $res[0]['coefficient'],'divisibility' => $res[0]['divisibility']);
            return $res;
        }else{
            return false;
        }
    }

    public function createNewSalesPriceGoods($type,$ChromeCOCHRI,$ChromeCOGOCHRI,$ChromeGOCHRI,$ChromeGOMCHRI,$ChromeCOCHMA,$ChromeCOGOCHMA,$ChromeGOCHMA,$ChromeGOMCHMA,$ChromeDP,$ChromeDTP,$ChromeDSP,$ChromeDKAP,$ChromeTP,$ChromeRSP,$ChromeRMP,$ChromeRPBP,$ChromeRMARP,$ChromeRMAAP,$ChromeRMDARP,$ChromeZP,$ChromeZRP,$ChromeZALRP,$ChromeZAUP,$ChromeZATP,$ChromeZARP,$ChromeZASP,$ChromeZAF30P,$ChromeZAF25P,$ChromeZAFP,$ChromeZANP,$ChromeZG360P,$ChromeZBAAP,$ChromeZTP,$ChromeZDARP,$ChromeZDP,$ChromeZDATP,$ChromeZDiARP,$ChromeZSHP,$ChromeZMANP,$ChromeMTP,$ChromeMDT1P,$ChromeMDT2P,$ChromeMDT3P,$ChromeMRP,$BChromeCOCHRI,$BChromeCOGOCHRI,$BChromeGOCHRI,$BChromeGOMCHRI,$BChromeCOCHMA,$BChromeCOGOCHMA,$BChromeGOCHMA,$BChromeGOMCHMA,$BChromeTP,$BChromeDP,$BChromeDKP,$BChromeRSP,$BChromeRMP,$BChromeRMAAP,$BChromeRZASP,$BChromeZP,$BChromeZAPP,$BChromeZDP,$BChromeZDiP,$BChromeZDAPP,$BChromeZDASP,$BChromeZAASP,$BTNBLGHGH,$TNBLGHGH,$ETNBLGHGH,$PXTNBLGHGH,$PETNBLGHGH,$AHTNBLGHGH,$BHTNBLGHGH){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood') && !$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql111 = "SELECT `gCode`,`price` FROM `salepricelist`";
        $res11 = $db->ArrayQuery($sql111);

        for ($i=0;$i<count($res11);$i++){
            $q11 = "UPDATE `good` SET `salesListPrice`={$res11[$i]['price']} WHERE `gCode`='{$res11[$i]['gCode']}'";
            $db->Query($q11);
        }

        /*$sql = "SELECT `RowID`,`salesListPrice`,`gCode`,`Series`,`category` FROM `good` WHERE `ggroup`='شیرآلات'";
        $result = $db->ArrayQuery($sql);
        $cntres = count($result);
        $nowDate = date('Y/m/d');

        for ($j=0;$j<$cntres;$j++){
            $qq = "INSERT INTO `saleprice_history` (`gid`,`price`,`cdate`) VALUES ({$result[$j]['RowID']},{$result[$j]['salesListPrice']},'{$nowDate}')";
            $db->Query($qq);
        }*/

        if (intval($type) == 0){  // شیرآلات ابرش
            $query = "UPDATE `good` SET `priceChangePercent`={$TNBLGHGH} WHERE `ggroup`='شیرآلات' AND `brand`='ابرش'";
            $db->Query($query);

            $sql = "UPDATE `increase_compared_chrome` SET `ChromeCOCHRI`={$ChromeCOCHRI},`ChromeCOGOCHRI`={$ChromeCOGOCHRI},`ChromeGOCHRI`={$ChromeGOCHRI},`ChromeGOMCHRI`={$ChromeGOMCHRI},
                                                          `ChromeCOCHMA`={$ChromeCOCHMA},`ChromeCOGOCHMA`={$ChromeCOGOCHMA},`ChromeGOCHMA`={$ChromeGOCHMA},`ChromeGOMCHMA`={$ChromeGOMCHMA},
                                                          `ChromeDPer`={$ChromeDP},`ChromeDTPer`={$ChromeDTP},`ChromeDSPer`={$ChromeDSP},`ChromeDKAPer`={$ChromeDKAP},`ChromeTPer`={$ChromeTP},
                                                          `ChromeRSPer`={$ChromeRSP},`ChromeRMPer`={$ChromeRMP},`ChromeRPBPer`={$ChromeRPBP},`ChromeRMARPer`={$ChromeRMARP},`ChromeRMAAPer`={$ChromeRMAAP},
                                                          `ChromeRMDARPer`={$ChromeRMDARP},`ChromeZPer`={$ChromeZP},`ChromeZRPer`={$ChromeZRP},`ChromeZALRPer`={$ChromeZALRP},`ChromeZAUPer`={$ChromeZAUP},
                                                          `ChromeZATPer`={$ChromeZATP},`ChromeZARPer`={$ChromeZARP},`ChromeZASPer`={$ChromeZASP},`ChromeZAF30Per`={$ChromeZAF30P},`ChromeZAF25Per`={$ChromeZAF25P},
                                                          `ChromeZAFPer`={$ChromeZAFP},`ChromeZANPer`={$ChromeZANP},`ChromeZG360Per`={$ChromeZG360P},`ChromeZBAAPer`={$ChromeZBAAP},`ChromeZTPer`={$ChromeZTP},
                                                          `ChromeZDARPer`={$ChromeZDARP},`ChromeZDPer`={$ChromeZDP},`ChromeZDATPer`={$ChromeZDATP},`ChromeZDiARPer`={$ChromeZDiARP},`ChromeZSHPer`={$ChromeZSHP},
                                                          `ChromeZMANPer`={$ChromeZMANP},`ChromeMTPer`={$ChromeMTP},`ChromeMDT1Per`={$ChromeMDT1P},`ChromeMDT2Per`={$ChromeMDT2P},`ChromeMDT3Per`={$ChromeMDT3P},
                                                          `ChromeMRPer`={$ChromeMRP},`TNBLGHGH`={$TNBLGHGH}";

            $db->Query($sql);
            $rslt = $db->AffectedRows();
            $rslt = (($rslt == -1) ? 0 : 1);
            if (intval($rslt)){

                $sql = "SELECT `RowID`,`salesListPrice`,`newSalesListPrice`,`gCode`,`Series`,`category`,`proccessWay`,`priceChangePercent`,`priceChangeConstant` FROM `good` WHERE `color`='کروم' AND `ggroup`='شیرآلات' AND `brand`='ابرش'";
                $res = $db->ArrayQuery($sql);
                $cnt = count($res);

                $query = "SELECT `ChromeCOCHRI`,`ChromeCOGOCHRI`,`ChromeGOCHRI`,`ChromeGOMCHRI`,`ChromeCOCHMA`,`ChromeCOGOCHMA`,`ChromeGOCHMA`,`ChromeGOMCHMA`,`ChromeDPer`,`ChromeDTPer`,`ChromeDSPer`,`ChromeDKAPer`,`ChromeTPer`,`ChromeRSPer`,`ChromeRMPer`,`ChromeRPBPer`,`ChromeRMARPer`,`ChromeRMAAPer`,`ChromeRMDARPer`,`ChromeZPer`,`ChromeZRPer`,`ChromeZALRPer`,`ChromeZAUPer`,`ChromeZATPer`,`ChromeZARPer`,`ChromeZASPer`,`ChromeZAF30Per`,`ChromeZAF25Per`,`ChromeZAFPer`,`ChromeZANPer`,`ChromeZG360Per`,`ChromeZBAAPer`,`ChromeZTPer`,`ChromeZDARPer`,`ChromeZDPer`,`ChromeZDATPer`,`ChromeZDiARPer`,`ChromeZSHPer`,`ChromeZMANPer`,`ChromeMTPer`,`ChromeMDT1Per`,`ChromeMDT2Per`,`ChromeMDT3Per`,`ChromeMRPer` FROM `increase_compared_chrome`";
                $res1 = $db->ArrayQuery($query);

                // کروم به رنگی
                $ChromeToColorRI = $res1[0]['ChromeCOCHRI'];
                $ChromeToColorMA = $res1[0]['ChromeCOCHMA'];

                // کروم به رنگی طلا
                $ChromeToGoldenColorRI = $res1[0]['ChromeCOGOCHRI'];
                $ChromeToGoldenColorMA = $res1[0]['ChromeCOGOCHMA'];

                // کروم به طلایی
                $ChromeToGoldenRI = $res1[0]['ChromeGOCHRI'];
                $ChromeToGoldenMA = $res1[0]['ChromeGOCHMA'];

                // کروم به طلایی مات
                $ChromeToGoldenMattRI = $res1[0]['ChromeGOMCHRI'];
                $ChromeToGoldenMattMA = $res1[0]['ChromeGOMCHMA'];

                for ($i=0;$i<$cnt;$i++){
                    $increaseChromePercent = $res[$i]['priceChangePercent']/100;
                    $newSalesListPrice = $this->abrashRound((($res[$i]['salesListPrice'] * $increaseChromePercent)+$res[$i]['priceChangeConstant']));
                    $qq = "UPDATE `good` SET `newSalesListPrice`={$newSalesListPrice} WHERE `RowID`={$res[$i]['RowID']}";
                    $db->Query($qq);
                    $res[$i]['newSalesListPrice'] = $newSalesListPrice;
                }

                $percent = 0;
                for ($i=0;$i<$cnt;$i++){
                    switch ($res[$i]['category']){
                        case 'دوش':
                            $percent = $res1[0]['ChromeDPer']/100;
                            break;
                        case 'دوش تخت':
                            $percent = $res1[0]['ChromeDTPer']/100;
                            break;
                        case 'دوش سوئیچی':
                            $percent = $res1[0]['ChromeDSPer']/100;
                            break;
                        case 'دوش کج اهرمی':
                            $percent = $res1[0]['ChromeDKAPer']/100;
                            break;
                        case 'توالت':
                            $percent = $res1[0]['ChromeTPer']/100;
                            break;
                        case 'روشویی پایه بلند':
                            $percent = $res1[0]['ChromeRPBPer']/100;
                            break;
                        case 'روشویی ثابت':
                            $percent = $res1[0]['ChromeRSPer']/100;
                            break;
                        case 'روشویی متحرک':
                            $percent = $res1[0]['ChromeRMPer']/100;
                            break;
                        case 'روشویی متحرک با علم ریخته گری':
                            $percent = $res1[0]['ChromeRMARPer']/100;
                            break;
                        case 'روشویی متحرک با علم عصایی':
                            $percent = $res1[0]['ChromeRMAAPer']/100;
                            break;
                        case 'روشویی متحرک دنباله دار با علم ریخته گری':
                            $percent = $res1[0]['ChromeRMDARPer']/100;
                            break;
                        case 'ظرفشویی':
                            $percent = $res1[0]['ChromeZPer']/100;
                            break;
                        case 'ظرفشویی - روشویی':
                            $percent = $res1[0]['ChromeZRPer']/100;
                            break;
                        case 'ظرفشویی با علم L ریخته گری':
                            $percent = $res1[0]['ChromeZALRPer']/100;
                            break;
                        case 'ظرفشویی با علم U شکل':
                            $percent = $res1[0]['ChromeZAUPer']/100;
                            break;
                        case 'ظرفشویی با علم تتراس':
                            $percent = $res1[0]['ChromeZATPer']/100;
                            break;
                        case 'ظرفشویی با علم ریخته گری':
                            $percent = $res1[0]['ChromeZARPer']/100;
                            break;
                        case 'ظرفشویی با علم سماوری':
                            $percent = $res1[0]['ChromeZASPer']/100;
                            break;
                        case 'ظرفشویی با علم فلت 10*30':
                            $percent = $res1[0]['ChromeZAF30Per']/100;
                            break;
                        case 'ظرفشویی با علم فلت 25*25':
                            $percent = $res1[0]['ChromeZAF25Per']/100;
                            break;
                        case 'ظرفشویی با علم فنری':
                            $percent = $res1[0]['ChromeZAFPer']/100;
                            break;
                        case 'ظرفشویی با علم ناخنی':
                            $percent = $res1[0]['ChromeZANPer']/100;
                            break;
                        case 'ظرفشویی با گوشی چرخشی 360 درجه':
                            $percent = $res1[0]['ChromeZG360Per']/100;
                            break;
                        case 'ظرفشویی بغل با علم عصایی':
                            $percent = $res1[0]['ChromeZBAAPer']/100;
                            break;
                        case 'ظرفشویی تصفیه':
                            $percent = $res1[0]['ChromeZTPer']/100;
                            break;
                        case 'ظرفشویی دنباله دار با علم ریخته گری':
                            $percent = $res1[0]['ChromeZDARPer']/100;
                            break;
                        case 'ظرفشویی دیواری':
                            $percent = $res1[0]['ChromeZDPer']/100;
                            break;
                        case 'ظرفشویی دیواری با علم تتراس':
                            $percent = $res1[0]['ChromeZDATPer']/100;
                            break;
                        case 'ظرفشویی دیواری با علم ریخته گری':
                            $percent = $res1[0]['ChromeZDiARPer']/100;
                            break;
                        case 'ظرفشویی شاوری':
                            $percent = $res1[0]['ChromeZSHPer']/100;
                            break;
                        case 'ظرفشویی مخروطی با علم ناخنی':
                            $percent = $res1[0]['ChromeZMANPer']/100;
                            break;
                        case 'متعلقات توالت':
                            $percent = $res1[0]['ChromeMTPer']/100;
                            break;
                        case 'متعلقات دوش تیپ 1':
                            $percent = $res1[0]['ChromeMDT1Per']/100;
                            break;
                        case 'متعلقات دوش تیپ 2':
                            $percent = $res1[0]['ChromeMDT2Per']/100;
                            break;
                        case 'متعلقات دوش تیپ 3':
                            $percent = $res1[0]['ChromeMDT3Per']/100;
                            break;
                        case 'متعلقات روشویی':
                            $percent = $res1[0]['ChromeMRPer']/100;
                            break;
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='زیتونی' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToColorRI : $ChromeToColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='سفید' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToColorRI : $ChromeToColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='سفید طلایی' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenColorRI : $ChromeToGoldenColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='سفید کروم' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToColorRI : $ChromeToColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='طرح استیل' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenMattRI : $ChromeToGoldenMattMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='طرح چوب' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToColorRI : $ChromeToColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='طلایی' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenRI : $ChromeToGoldenMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='طلایی مات' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenMattRI : $ChromeToGoldenMattMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='مشکی' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToColorRI : $ChromeToColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='مشکی طلایی' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenColorRI : $ChromeToGoldenColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }
                }
                return true;
            }
        }

        if (intval($type) == 1){  // شیرآلات بارشینا
            $query = "UPDATE `good` SET `priceChangePercent`={$BTNBLGHGH} WHERE `ggroup`='شیرآلات' AND `brand`='بارشینا'";
            $db->Query($query);

            $sql = "UPDATE `increase_compared_chrome` SET `ChromeBarshinCOCHRI`={$BChromeCOCHRI},`ChromeBarshinCOGOCHRI`={$BChromeCOGOCHRI},`ChromeBarshinGOCHRI`={$BChromeGOCHRI},`ChromeBarshinGOMCHRI`={$BChromeGOMCHRI},
                                                          `ChromeBarshinCOCHMA`={$BChromeCOCHMA},`ChromeBarshinCOGOCHMA`={$BChromeCOGOCHMA},`ChromeBarshinGOCHMA`={$BChromeGOCHMA},`ChromeBarshinGOMCHMA`={$BChromeGOMCHMA},
                                                          `ChromeBarshinTPer`={$BChromeTP},`ChromeBarshinDPer`={$BChromeDP},`ChromeBarshinDKPer`={$BChromeDKP},`ChromeBarshinRZASPer`={$BChromeRZASP},`ChromeBarshinRSPer`={$BChromeRSP},
                                                          `ChromeBarshinRMPer`={$BChromeRMP},`ChromeBarshinRMAAPer`={$BChromeRMAAP},`ChromeBarshinZPer`={$BChromeZP},`ChromeBarshinZAPPer`={$BChromeZAPP},`ChromeBarshinZDPer`={$BChromeZDP},
                                                          `ChromeBarshinZDiPer`={$BChromeZDiP},`ChromeBarshinZDAPPer`={$BChromeZDAPP},`ChromeBarshinZDASPer`={$BChromeZDASP},`ChromeBarshinZAASPer`={$BChromeZAASP},`TNBLGHGHBarshina`={$BTNBLGHGH}";

            $db->Query($sql);
            $rslt = $db->AffectedRows();
            $rslt = (($rslt == -1) ? 0 : 1);
            if (intval($rslt)){

                $sql = "SELECT `RowID`,`salesListPrice`,`gCode`,`Series`,`category`,`proccessWay`,`priceChangePercent`,`priceChangeConstant` FROM `good` WHERE `color`='کروم' AND `ggroup`='شیرآلات' AND `brand`='بارشینا'";
                $res = $db->ArrayQuery($sql);
                $cnt = count($res);

                $query = "SELECT `ChromeBarshinCOCHRI`,`ChromeBarshinCOGOCHRI`,`ChromeBarshinGOCHRI`,`ChromeBarshinGOMCHRI`,`ChromeBarshinCOCHMA`,`ChromeBarshinCOGOCHMA`,`ChromeBarshinGOCHMA`,`ChromeBarshinGOMCHMA`,`ChromeBarshinTPer`,`ChromeBarshinDPer`,`ChromeBarshinDKPer`,`ChromeBarshinRZASPer`,`ChromeBarshinRSPer`,`ChromeBarshinRMPer`,`ChromeBarshinRMAAPer`,`ChromeBarshinZPer`,`ChromeBarshinZAPPer`,`ChromeBarshinZDPer`,`ChromeBarshinZDiPer`,`ChromeBarshinZDAPPer`,`ChromeBarshinZDASPer`,`ChromeBarshinZAASPer`,`TNBLGHGHBarshina` FROM `increase_compared_chrome`";
                $res1 = $db->ArrayQuery($query);

                // کروم به رنگی
                $ChromeToColorRI = $res1[0]['ChromeBarshinCOCHRI'];
                $ChromeToColorMA = $res1[0]['ChromeBarshinCOCHMA'];

                // کروم به رنگی طلا
                $ChromeToGoldenColorRI = $res1[0]['ChromeBarshinCOGOCHRI'];
                $ChromeToGoldenColorMA = $res1[0]['ChromeBarshinCOGOCHMA'];

                // کروم به طلایی
                $ChromeToGoldenRI = $res1[0]['ChromeBarshinGOCHRI'];
                $ChromeToGoldenMA = $res1[0]['ChromeBarshinGOCHMA'];

                // کروم به طلایی مات
                $ChromeToGoldenMattRI = $res1[0]['ChromeBarshinGOMCHRI'];
                $ChromeToGoldenMattMA = $res1[0]['ChromeBarshinGOMCHMA'];

                $percent = 0;
                $Btp = $res1[0]['ChromeBarshinTPer']/100;
                $Bdp= $res1[0]['ChromeBarshinDPer']/100;
                $Bdkp = $res1[0]['ChromeBarshinDKPer']/100;
                $Brzasp = $res1[0]['ChromeBarshinRZASPer']/100;
                $Brsp = $res1[0]['ChromeBarshinRSPer']/100;
                $Brmp = $res1[0]['ChromeBarshinRMPer']/100;
                $Brmaap = $res1[0]['ChromeBarshinRMAAPer']/100;
                $Bzp = $res1[0]['ChromeBarshinZPer']/100;
                $Bzapp = $res1[0]['ChromeBarshinZAPPer']/100;
                $Bzdp = $res1[0]['ChromeBarshinZDPer']/100;
                $Bzdip = $res1[0]['ChromeBarshinZDiPer']/100;
                $Bzdapp = $res1[0]['ChromeBarshinZDAPPer']/100;
                $Bzdasp = $res1[0]['ChromeBarshinZDASPer']/100;
                $Bzaasp = $res1[0]['ChromeBarshinZAASPer']/100;


                for ($i=0;$i<$cnt;$i++){
                    $increaseChromePercent = $res[$i]['priceChangePercent']/100;
                    $newSalesListPrice = $this->abrashRound((($res[$i]['salesListPrice'] * $increaseChromePercent)+$res[$i]['priceChangeConstant']));
                    $qq = "UPDATE `good` SET `newSalesListPrice`={$newSalesListPrice} WHERE `RowID`={$res[$i]['RowID']}";
                    $db->Query($qq);
                    $res[$i]['newSalesListPrice'] = $newSalesListPrice;
                }

                for ($i=0;$i<$cnt;$i++){
                    switch ($res[$i]['category']){
                        case 'توالت':
                            $percent = $Btp;
                            break;
                        case 'دوش':
                            $percent = $Bdp;
                            break;
                        case 'دوش کج':
                            $percent = $Bdkp;
                            break;
                        case 'روشویی - ظرفشویی با علم صدفی':
                            $percent = $Brzasp;
                            break;
                        case 'روشویی ثابت':
                            $percent = $Brsp;
                            break;
                        case 'روشویی متحرک':
                            $percent = $Brmp;
                            break;
                        case 'روشویی متحرک با علم عصایی':
                            $percent = $Brmaap;
                            break;
                        case 'ظرفشویی':
                            $percent = $Bzp;
                            break;
                        case 'ظرفشویی با علم پاپیونی':
                            $percent = $Bzapp;
                            break;
                        case 'ظرفشویی دنباله دار':
                            $percent = $Bzdp;
                            break;
                        case 'ظرفشویی دیواری':
                            $percent = $Bzdip;
                            break;
                        case 'ظرفشویی دیواری با علم پاپیونی':
                            $percent = $Bzdapp;
                            break;
                        case 'ظرفشویی دیواری با علم صدفی':
                            $percent = $Bzdasp;
                            break;
                        case 'ظرفشویی سه تکه عربی با علم صدفی':
                            $percent = $Bzaasp;
                            break;
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='زیتونی' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToColorRI : $ChromeToColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='سفید' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToColorRI : $ChromeToColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='سفید طلایی' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenColorRI : $ChromeToGoldenColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='سفید کروم' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToColorRI : $ChromeToColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='طرح استیل' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenMattRI : $ChromeToGoldenMattMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='طرح چوب' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToColorRI : $ChromeToColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRoLund($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='طلایی' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenRI : $ChromeToGoldenMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='طلایی مات' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenMattRI : $ChromeToGoldenMattMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='مشکی' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToColorRI : $ChromeToColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }

                    $sql = "SELECT `gCode`,`priceChangeConstant` FROM `good` WHERE `color`='مشکی طلایی' AND `chCode`='{$res[$i]['RowID']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}'";
                    $rst = $db->ArrayQuery($sql);
                    if (count($rst) > 0) {
                        $newSaleListPrice = $res[$i]['newSalesListPrice'] + (($res[$i]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenColorRI : $ChromeToGoldenColorMA) * $percent) + $rst[0]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `newSalesListPrice`={$newSaleListPrice} WHERE `gCode`='{$rst[0]['gCode']}'";
                        $db->Query($q);
                    }
                }
                return true;
            }
        }

        if (intval($type) == 2) {  // شیلنگ ابرش
            $query = "UPDATE `good` SET `priceChangePercent`={$AHTNBLGHGH} WHERE `ggroup`='شیلنگ' AND `brand`='ابرش'";
            $db->Query($query);

            $sql = "UPDATE `increase_compared_chrome` SET `TNBLGHGHAHose`={$AHTNBLGHGH}";
            $db->Query($sql);

            $rslt = $db->AffectedRows();
            $rslt = (($rslt == -1) ? 0 : 1);
            if (intval($rslt)) {
                $sql = "SELECT `RowID`,`salesListPrice`,`priceChangePercent`,`priceChangeConstant` FROM `good` WHERE `ggroup`='شیلنگ' AND `brand`='ابرش' AND `calcType`=0";
                $res = $db->ArrayQuery($sql);
                $cnt = count($res);
                for ($i=0;$i<$cnt;$i++){
                    $increaseChromePercent = $res[$i]['priceChangePercent'] / 100;
                    $newSalesListPrice = $this->abrashRound((($res[$i]['salesListPrice'] * $increaseChromePercent) + $res[$i]['priceChangeConstant']));
                    $qq = "UPDATE `good` SET `newSalesListPrice`={$newSalesListPrice} WHERE `RowID`={$res[$i]['RowID']}";
                    $db->Query($qq);
                }
                $sql1 = "SELECT `RowID`,`gid`,`coefficient`,`priceChangeConstant` FROM `good` WHERE `ggroup`='شیلنگ' AND `brand`='ابرش' AND `calcType`=1";
                $res1 = $db->ArrayQuery($sql1);
                $cntres = count($res1);
                for ($j=0;$j<$cntres;$j++){
                    $sqle = "SELECT `newSalesListPrice` FROM `good` WHERE `RowID`={$res1[$j]['gid']}";
                    $rste = $db->ArrayQuery($sqle);
                    $newSalesListPrice = $this->abrashRound($rste[0]['newSalesListPrice'] + ($res1[$j]['priceChangeConstant'] * $res1[$j]['coefficient']));
                    $qe = "UPDATE `good` SET `newSalesListPrice`={$newSalesListPrice} WHERE `RowID`={$res1[$j]['RowID']}";
                    $db->Query($qe);
                }
                return true;
            }
        }

        if (intval($type) == 3) {  // شیلنگ بارشینا
            $query = "UPDATE `good` SET `priceChangePercent`={$BHTNBLGHGH} WHERE `ggroup`='شیلنگ' AND `brand`='بارشینا'";
            $db->Query($query);

            $sql = "UPDATE `increase_compared_chrome` SET `TNBLGHGHBHose`={$BHTNBLGHGH}";
            $db->Query($sql);

            $rslt = $db->AffectedRows();
            $rslt = (($rslt == -1) ? 0 : 1);
            if (intval($rslt)) {
                $sql = "SELECT `RowID`,`salesListPrice`,`priceChangePercent`,`priceChangeConstant` FROM `good` WHERE `ggroup`='شیلنگ' AND `brand`='بارشینا' AND `calcType`=0";
                $res = $db->ArrayQuery($sql);
                $cnt = count($res);
                for ($i=0;$i<$cnt;$i++){
                    $increaseChromePercent = $res[$i]['priceChangePercent'] / 100;
                    $newSalesListPrice = $this->abrashRound((($res[$i]['salesListPrice'] * $increaseChromePercent) + $res[$i]['priceChangeConstant']));
                    $qq = "UPDATE `good` SET `newSalesListPrice`={$newSalesListPrice} WHERE `RowID`={$res[$i]['RowID']}";
                    $db->Query($qq);
                }
                $sql1 = "SELECT `RowID`,`gid`,`coefficient`,`priceChangeConstant` FROM `good` WHERE `ggroup`='شیلنگ' AND `brand`='بارشینا' AND `calcType`=1";
                $res1 = $db->ArrayQuery($sql1);
                $cntres = count($res1);
                for ($j=0;$j<$cntres;$j++){
                    $sqle = "SELECT `newSalesListPrice` FROM `good` WHERE `RowID`={$res1[$j]['gid']}";
                    $rste = $db->ArrayQuery($sqle);
                    $newSalesListPrice = $this->abrashRound($rste[0]['newSalesListPrice'] + ($res1[$j]['priceChangeConstant'] * $res1[$j]['coefficient']));
                    $qe = "UPDATE `good` SET `newSalesListPrice`={$newSalesListPrice} WHERE `RowID`={$res1[$j]['RowID']}";
                    $db->Query($qe);
                }
                return true;
            }
        }

        if (intval($type) == 4){  // اتصالات
            $query = "UPDATE `good` SET `priceChangePercent`={$ETNBLGHGH} WHERE `ggroup`='اتصالات 5 لایه' AND (`brand`='ابرش' OR `brand`='راتکو')";
            $db->Query($query);

            $sql = "UPDATE `increase_compared_chrome` SET `TNBLGHGHFitting`={$ETNBLGHGH}";
            $db->Query($sql);

            $rslt = $db->AffectedRows();
            $rslt = (($rslt == -1) ? 0 : 1);
            if (intval($rslt)) {
                $sql = "SELECT `RowID`,`salesListPrice`,`priceChangePercent`,`priceChangeConstant` FROM `good` WHERE `ggroup`='اتصالات 5 لایه' AND (`brand`='ابرش' OR `brand`='راتکو') AND `calcType`=0";
                $res = $db->ArrayQuery($sql);
                $cnt = count($res);
                for ($i=0;$i<$cnt;$i++){   // روش عادی
                    $increaseChromePercent = ($res[$i]['priceChangePercent'] + 100) / 100;
                    $newSalesListPrice = $this->abrashRound((($res[$i]['salesListPrice'] * $increaseChromePercent) + $res[$i]['priceChangeConstant']));
                    $qq = "UPDATE `good` SET `newSalesListPrice`={$newSalesListPrice} WHERE `RowID`={$res[$i]['RowID']}";
                    $db->Query($qq);
                }

                $sql1 = "SELECT `RowID`,`gid`,`coefficient`,`divisibility` FROM `good` WHERE `ggroup`='اتصالات 5 لایه' AND (`brand`='ابرش' OR `brand`='راتکو') AND `calcType`=1";
                $res1 = $db->ArrayQuery($sql1);
                $cntres = count($res1);
                for ($j=0;$j<$cntres;$j++){
                    $sqle = "SELECT `newSalesListPrice` FROM `good` WHERE `RowID`={$res1[$j]['gid']}";
                    $rste = $db->ArrayQuery($sqle);
                    $newSalesListPrice = $this->abrashRound(($rste[0]['newSalesListPrice'] / $res1[$j]['divisibility']) * $res1[$j]['coefficient']);
                    $qe = "UPDATE `good` SET `newSalesListPrice`={$newSalesListPrice} WHERE `RowID`={$res1[$j]['RowID']}";
                    $db->Query($qe);
                }
                return true;
            }
        }

        if (intval($type) == 5) {  // لوله
            $query = "UPDATE `good` SET `priceChangePercent`={$PXTNBLGHGH} WHERE `ggroup`='لوله 5 لایه' AND `Series`='لوله های PEX' AND `brand`='ابرش'";
            $db->Query($query);

            $query = "UPDATE `good` SET `priceChangePercent`={$PETNBLGHGH} WHERE `ggroup`='لوله 5 لایه' AND `Series`='لوله های PERT' AND `brand`='ابرش'";
            $db->Query($query);

            $sql = "UPDATE `increase_compared_chrome` SET `TNBLGHGHPipePex`={$PXTNBLGHGH},`TNBLGHGHPipePert`={$PETNBLGHGH}";
            $db->Query($sql);

            $rslt = $db->AffectedRows();
            $rslt = (($rslt == -1) ? 0 : 1);
            if (intval($rslt)) {
                $sql = "SELECT `RowID`,`salesListPrice`,`priceChangePercent`,`priceChangeConstant` FROM `good` WHERE `ggroup`='لوله 5 لایه' AND `Series`='لوله های PEX' AND `brand`='ابرش' ORDER BY `cartridgeSize` ASC";
                $res = $db->ArrayQuery($sql);
                $cnt = count($res);

                for ($i=0;$i<$cnt;$i++){
                    $increaseChromePercent = ($res[$i]['priceChangePercent'] + 100) / 100;
                    $newSalesListPrice = (($res[$i]['salesListPrice'] * $increaseChromePercent)+$res[$i]['priceChangeConstant']);
                    $newSalesListPrice = ceil($newSalesListPrice/100) * 100;
                    $qq = "UPDATE `good` SET `newSalesListPrice`={$newSalesListPrice} WHERE `RowID`={$res[$i]['RowID']}";
                    $db->Query($qq);
                    $res[$i]['salesListPrice'] = $newSalesListPrice;
                }

                $sqlp = "SELECT `RowID`,`salesListPrice`,`priceChangePercent`,`priceChangeConstant` FROM `good` WHERE `ggroup`='لوله 5 لایه' AND `Series`='لوله های PERT' AND `brand`='ابرش' ORDER BY `cartridgeSize` ASC";
                $resp = $db->ArrayQuery($sqlp);
                $cnt = count($resp);

                for ($i=0;$i<$cnt;$i++){
                    $increaseChromePercent = ($resp[$i]['priceChangePercent'] + 100)/100;
                    $newSalesListPrice = (($res[$i]['salesListPrice'] * $increaseChromePercent)+$resp[$i]['priceChangeConstant']);
                    $newSalesListPrice = ceil($newSalesListPrice/100) * 100;
                    $qq = "UPDATE `good` SET `newSalesListPrice`={$newSalesListPrice} WHERE `RowID`={$resp[$i]['RowID']}";
                    $db->Query($qq);
                }
                return true;
            }
        }

    }

    public function createFinalSalesPriceGoods($pDate,$Cat){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood') && !$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        mysqli_autocommit($db->Getcon(),FALSE);

        $pDate = $ut->jal_to_greg($pDate);
        $flag = true;
        switch (intval($Cat)){
            case 0 :
                $sql = "SELECT `RowID`,`newSalesListPrice` FROM `good` WHERE `isEnable`=1 AND `ggroup`='شیرآلات' AND `brand`='ابرش'";
                $res = $db->ArrayQuery($sql);
                $cnt = count($res);
                break;
            case 1 :
                $sql = "SELECT `RowID`,`newSalesListPrice` FROM `good` WHERE `isEnable`=1 AND `ggroup`='شیرآلات' AND `brand`='بارشینا'";
                $res = $db->ArrayQuery($sql);
                $cnt = count($res);
                break;
            case 2 :
                $sql = "SELECT `RowID`,`newSalesListPrice` FROM `good` WHERE `isEnable`=1 AND `ggroup`='شیلنگ' AND `brand`='ابرش'";
                $res = $db->ArrayQuery($sql);
                $cnt = count($res);
                break;
            case 3 :
                $sql = "SELECT `RowID`,`newSalesListPrice` FROM `good` WHERE `isEnable`=1 AND `ggroup`='شیلنگ' AND `brand`='بارشینا'";
                $res = $db->ArrayQuery($sql);
                $cnt = count($res);
                break;
            case 4 :
                $sql = "SELECT `RowID`,`newSalesListPrice` FROM `good` WHERE `isEnable`=1 AND `ggroup`='اتصالات 5 لایه'";
                $res = $db->ArrayQuery($sql);
                $cnt = count($res);
                break;
            case 5 :
                $sql = "SELECT `RowID`,`newSalesListPrice` FROM `good` WHERE `isEnable`=1 AND `ggroup`='لوله 5 لایه'";
                $res = $db->ArrayQuery($sql);
                $cnt = count($res);
                break;
        }
        $newSalesListPrice = 'NULL';
        for ($i=0;$i<$cnt;$i++){
            $query = "UPDATE `good` SET `newSalesListPrice`={$newSalesListPrice},`salesListPrice`={$res[$i]['newSalesListPrice']},`PerformanceDate`='{$pDate}' WHERE `RowID`={$res[$i]['RowID']}";
            $db->Query($query);
            $rst = $db->AffectedRows();
            $rst = ((intval($rst) == -1 || $rst == 0) ? 0 : 1);
            if (intval($rst) == 0) {
                $flag = false;
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

    public function createOneNewSalesPriceGoods($gid,$DTNBLGHGH,$MSTNBLGHGH,$calcType,$gName,$coefficient,$divisibility,$ggroup){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood') && !$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        if (intval($calcType) > 0) {  // فرمولی
            $gsql = "SELECT `RowID` FROM `good` WHERE `gName`='{$gName}'";
            $gRowID = $db->ArrayQuery($gsql);
            $gRowID = $gRowID[0]['RowID'];
            if ($ggroup == 'شیلنگ'){
                $divisibility = 'NULL';
            }
        }else{
            $gRowID = 'NULL';
            $coefficient = 'NULL';
            $divisibility = 'NULL';
        }

        $query = "UPDATE `good` SET `priceChangePercent`={$DTNBLGHGH},`priceChangeConstant`={$MSTNBLGHGH},`calcType`={$calcType},`gid`={$gRowID},`coefficient`={$coefficient},`divisibility`={$divisibility} WHERE `RowID`={$gid}";
        $db->Query($query);

        $sql = "SELECT `color`,`brand`,`ggroup`,`Series`,`category`,`cartridgeSize`,`proccessWay`,`priceChangePercent`,`priceChangeConstant`,`salesListPrice`,`chCode` FROM `good` WHERE `RowID`={$gid}";
        $res = $db->ArrayQuery($sql);

        if ($res[0]['ggroup'] !== 'لوله 5 لایه') {
            $sql111 = "SELECT `gCode`,`price` FROM `salepricelist`";
            $res11 = $db->ArrayQuery($sql111);

            for ($i = 0; $i < count($res11); $i++) {
                $q11 = "UPDATE `good` SET `salesListPrice`={$res11[$i]['price']} WHERE `gCode`='{$res11[$i]['gCode']}'";
                $db->Query($q11);
            }
        }

        /*$sql = "SELECT `RowID`,`salesListPrice`,`gCode`,`Series`,`category` FROM `good` WHERE `ggroup`='شیرآلات'";
        $result = $db->ArrayQuery($sql);
        $cntres = count($result);
        $nowDate = date('Y/m/d');

        for ($j=0;$j<$cntres;$j++){
            $qq = "INSERT INTO `saleprice_history` (`gid`,`price`,`cdate`) VALUES ({$result[$j]['RowID']},{$result[$j]['salesListPrice']},'{$nowDate}')";
            $db->Query($qq);
        }*/



        $percent = 0;
        if ($res[0]['ggroup'] == 'شیرآلات' && $res[0]['brand'] == 'ابرش'){

            $query = "SELECT `ChromeCOCHRI`,`ChromeCOGOCHRI`,`ChromeGOCHRI`,`ChromeGOMCHRI`,`ChromeCOCHMA`,`ChromeCOGOCHMA`,`ChromeGOCHMA`,`ChromeGOMCHMA`,`ChromeDPer`,`ChromeDTPer`,`ChromeDSPer`,`ChromeDKAPer`,`ChromeTPer`,`ChromeRSPer`,`ChromeRMPer`,`ChromeRPBPer`,`ChromeRMARPer`,`ChromeRMAAPer`,`ChromeRMDARPer`,`ChromeZPer`,`ChromeZRPer`,`ChromeZALRPer`,`ChromeZAUPer`,`ChromeZATPer`,`ChromeZARPer`,`ChromeZASPer`,`ChromeZAF30Per`,`ChromeZAF25Per`,`ChromeZAFPer`,`ChromeZANPer`,`ChromeZG360Per`,`ChromeZBAAPer`,`ChromeZTPer`,`ChromeZDARPer`,`ChromeZDPer`,`ChromeZDATPer`,`ChromeZDiARPer`,`ChromeZSHPer`,`ChromeZMANPer`,`ChromeMTPer`,`ChromeMDT1Per`,`ChromeMDT2Per`,`ChromeMDT3Per`,`ChromeMRPer` FROM `increase_compared_chrome`";
            $res1 = $db->ArrayQuery($query);

            // کروم به رنگی
            $ChromeToColorRI = $res1[0]['ChromeCOCHRI'];
            $ChromeToColorMA = $res1[0]['ChromeCOCHMA'];

            // کروم به رنگی طلا
            $ChromeToGoldenColorRI = $res1[0]['ChromeCOGOCHRI'];
            $ChromeToGoldenColorMA = $res1[0]['ChromeCOGOCHMA'];

            // کروم به طلایی
            $ChromeToGoldenRI = $res1[0]['ChromeGOCHRI'];
            $ChromeToGoldenMA = $res1[0]['ChromeGOCHMA'];

            // کروم به طلایی مات
            $ChromeToGoldenMattRI = $res1[0]['ChromeGOMCHRI'];
            $ChromeToGoldenMattMA = $res1[0]['ChromeGOMCHMA'];

            switch ($res[0]['category']) {
                case 'دوش':
                    $percent = $res1[0]['ChromeDPer']/100;
                    break;
                case 'دوش تخت':
                    $percent = $res1[0]['ChromeDTPer']/100;
                    break;
                case 'دوش سوئیچی':
                    $percent = $res1[0]['ChromeDSPer']/100;
                    break;
                case 'دوش کج اهرمی':
                    $percent = $res1[0]['ChromeDKAPer']/100;
                    break;
                case 'توالت':
                    $percent = $res1[0]['ChromeTPer']/100;
                    break;
                case 'روشویی پایه بلند':
                    $percent = $res1[0]['ChromeRPBPer']/100;
                    break;
                case 'روشویی ثابت':
                    $percent = $res1[0]['ChromeRSPer']/100;
                    break;
                case 'روشویی متحرک':
                    $percent = $res1[0]['ChromeRMPer']/100;
                    break;
                case 'روشویی متحرک با علم ریخته گری':
                    $percent = $res1[0]['ChromeRMARPer']/100;
                    break;
                case 'روشویی متحرک با علم عصایی':
                    $percent = $res1[0]['ChromeRMAAPer']/100;
                    break;
                case 'روشویی متحرک دنباله دار با علم ریخته گری':
                    $percent = $res1[0]['ChromeRMDARPer']/100;
                    break;
                case 'ظرفشویی':
                    $percent = $res1[0]['ChromeZPer']/100;
                    break;
                case 'ظرفشویی - روشویی':
                    $percent = $res1[0]['ChromeZRPer']/100;
                    break;
                case 'ظرفشویی با علم L ریخته گری':
                    $percent = $res1[0]['ChromeZALRPer']/100;
                    break;
                case 'ظرفشویی با علم U شکل':
                    $percent = $res1[0]['ChromeZAUPer']/100;
                    break;
                case 'ظرفشویی با علم تتراس':
                    $percent = $res1[0]['ChromeZATPer']/100;
                    break;
                case 'ظرفشویی با علم ریخته گری':
                    $percent = $res1[0]['ChromeZARPer']/100;
                    break;
                case 'ظرفشویی با علم سماوری':
                    $percent = $res1[0]['ChromeZASPer']/100;
                    break;
                case 'ظرفشویی با علم فلت 10*30':
                    $percent = $res1[0]['ChromeZAF30Per']/100;
                    break;
                case 'ظرفشویی با علم فلت 25*25':
                    $percent = $res1[0]['ChromeZAF25Per']/100;
                    break;
                case 'ظرفشویی با علم فنری':
                    $percent = $res1[0]['ChromeZAFPer']/100;
                    break;
                case 'ظرفشویی با علم ناخنی':
                    $percent = $res1[0]['ChromeZANPer']/100;
                    break;
                case 'ظرفشویی با گوشی چرخشی 360 درجه':
                    $percent = $res1[0]['ChromeZG360Per']/100;
                    break;
                case 'ظرفشویی بغل با علم عصایی':
                    $percent = $res1[0]['ChromeZBAAPer']/100;
                    break;
                case 'ظرفشویی تصفیه':
                    $percent = $res1[0]['ChromeZTPer']/100;
                    break;
                case 'ظرفشویی دنباله دار با علم ریخته گری':
                    $percent = $res1[0]['ChromeZDARPer']/100;
                    break;
                case 'ظرفشویی دیواری':
                    $percent = $res1[0]['ChromeZDPer']/100;
                    break;
                case 'ظرفشویی دیواری با علم تتراس':
                    $percent = $res1[0]['ChromeZDATPer']/100;
                    break;
                case 'ظرفشویی دیواری با علم ریخته گری':
                    $percent = $res1[0]['ChromeZDiARPer']/100;
                    break;
                case 'ظرفشویی شاوری':
                    $percent = $res1[0]['ChromeZSHPer']/100;
                    break;
                case 'ظرفشویی مخروطی با علم ناخنی':
                    $percent = $res1[0]['ChromeZMANPer']/100;
                    break;
                case 'متعلقات توالت':
                    $percent = $res1[0]['ChromeMTPer']/100;
                    break;
                case 'متعلقات دوش تیپ 1':
                    $percent = $res1[0]['ChromeMDT1Per']/100;
                    break;
                case 'متعلقات دوش تیپ 2':
                    $percent = $res1[0]['ChromeMDT2Per']/100;
                    break;
                case 'متعلقات دوش تیپ 3':
                    $percent = $res1[0]['ChromeMDT3Per']/100;
                    break;
                case 'متعلقات روشویی':
                    $percent = $res1[0]['ChromeMRPer']/100;
                    break;
            }
        } // شیرآلات ابرش

        if ($res[0]['ggroup'] == 'شیرآلات' && $res[0]['brand'] == 'بارشینا'){

            $query = "SELECT `ChromeBarshinCOCHRI`,`ChromeBarshinCOGOCHRI`,`ChromeBarshinGOCHRI`,`ChromeBarshinGOMCHRI`,`ChromeBarshinCOCHMA`,`ChromeBarshinCOGOCHMA`,`ChromeBarshinGOCHMA`,`ChromeBarshinGOMCHMA`,`ChromeBarshinTPer`,`ChromeBarshinDPer`,`ChromeBarshinDKPer`,`ChromeBarshinRZASPer`,`ChromeBarshinRSPer`,`ChromeBarshinRMPer`,`ChromeBarshinRMAAPer`,`ChromeBarshinZPer`,`ChromeBarshinZAPPer`,`ChromeBarshinZDPer`,`ChromeBarshinZDiPer`,`ChromeBarshinZDAPPer`,`ChromeBarshinZDASPer`,`ChromeBarshinZAASPer`,`TNBLGHGHBarshina` FROM `increase_compared_chrome`";
            $res1 = $db->ArrayQuery($query);

            // کروم به رنگی
            $ChromeToColorRI = $res1[0]['ChromeBarshinCOCHRI'];
            $ChromeToColorMA = $res1[0]['ChromeBarshinCOCHMA'];

            // کروم به رنگی طلا
            $ChromeToGoldenColorRI = $res1[0]['ChromeBarshinCOGOCHRI'];
            $ChromeToGoldenColorMA = $res1[0]['ChromeBarshinCOGOCHMA'];

            // کروم به طلایی
            $ChromeToGoldenRI = $res1[0]['ChromeBarshinGOCHRI'];
            $ChromeToGoldenMA = $res1[0]['ChromeBarshinGOCHMA'];

            // کروم به طلایی مات
            $ChromeToGoldenMattRI = $res1[0]['ChromeBarshinGOMCHRI'];
            $ChromeToGoldenMattMA = $res1[0]['ChromeBarshinGOMCHMA'];

            switch ($res[0]['category']){
                case 'توالت':
                    $percent = $res1[0]['ChromeBarshinTPer']/100;
                    break;
                case 'دوش':
                    $percent = $res1[0]['ChromeBarshinDPer']/100;
                    break;
                case 'دوش کج':
                    $percent = $res1[0]['ChromeBarshinDKPer']/100;
                    break;
                case 'روشویی - ظرفشویی با علم صدفی':
                    $percent = $res1[0]['ChromeBarshinRZASPer']/100;
                    break;
                case 'روشویی ثابت':
                    $percent = $res1[0]['ChromeBarshinRSPer']/100;
                    break;
                case 'روشویی متحرک':
                    $percent = $res1[0]['ChromeBarshinRMPer']/100;
                    break;
                case 'روشویی متحرک با علم عصایی':
                    $percent = $res1[0]['ChromeBarshinRMAAPer']/100;
                    break;
                case 'ظرفشویی':
                    $percent = $res1[0]['ChromeBarshinZPer']/100;
                    break;
                case 'ظرفشویی با علم پاپیونی':
                    $percent = $res1[0]['ChromeBarshinZAPPer']/100;
                    break;
                case 'ظرفشویی دنباله دار':
                    $percent = $res1[0]['ChromeBarshinZDPer']/100;
                    break;
                case 'ظرفشویی دیواری':
                    $percent = $res1[0]['ChromeBarshinZDiPer']/100;
                    break;
                case 'ظرفشویی دیواری با علم پاپیونی':
                    $percent = $res1[0]['ChromeBarshinZDAPPer']/100;
                    break;
                case 'ظرفشویی دیواری با علم صدفی':
                    $percent = $res1[0]['ChromeBarshinZDASPer']/100;
                    break;
                case 'ظرفشویی سه تکه عربی با علم صدفی':
                    $percent = $res1[0]['ChromeBarshinZAASPer']/100;
                    break;
            }
        } // شیرآلات بارشینا

        if ($res[0]['color'] == 'کروم' && $res[0]['ggroup'] == 'شیرآلات') {
            $increaseChromePercent = $res[0]['priceChangePercent'] / 100;
            $salesListPrice = $this->abrashRound((($res[0]['salesListPrice'] * $increaseChromePercent) + $res[0]['priceChangeConstant']));
            $qq = "UPDATE `good` SET `salesListPrice`={$salesListPrice} WHERE `RowID`={$gid}";
            $db->Query($qq);
            $res[0]['salesListPrice'] = $salesListPrice;

            $query = "SELECT `RowID`,`color`,`priceChangeConstant` FROM `good` WHERE `chCode`='{$gid}'";
            $result = $db->ArrayQuery($query);
            $ccnt = count($result);

            for ($i=0;$i<$ccnt;$i++){
                switch ($result[$i]['color']){
                    case 'سفید کروم':
                    case 'سفید':
                    case 'طرح چوب':
                    case 'مشکی':
                    case 'زیتونی':
                        $newSaleListPrice = $res[0]['salesListPrice'] + (($res[0]['proccessWay'] == 'ریخته گری' ? $ChromeToColorRI : $ChromeToColorMA) * $percent) + $result[$i]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `salesListPrice`={$newSaleListPrice} WHERE `RowID`={$result[$i]['RowID']}";
                        $db->Query($q);
                        break;
                    case 'مشکی طلایی':
                    case 'سفید طلایی':
                        $newSaleListPrice = $res[0]['salesListPrice'] + (($res[0]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenColorRI : $ChromeToGoldenColorMA) * $percent) + $result[$i]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `salesListPrice`={$newSaleListPrice} WHERE `RowID`={$result[$i]['RowID']}";
                        $db->Query($q);
                        break;
                    case 'طلایی مات':
                    case 'طرح استیل':
                        $newSaleListPrice = $res[0]['salesListPrice'] + (($res[0]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenMattRI : $ChromeToGoldenMattMA) * $percent) + $result[$i]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `salesListPrice`={$newSaleListPrice} WHERE `RowID`={$result[$i]['RowID']}";
                        $db->Query($q);
                        break;
                    case 'طلایی':
                        $newSaleListPrice = $res[0]['salesListPrice'] + (($res[0]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenRI : $ChromeToGoldenMA) * $percent) + $result[$i]['priceChangeConstant'];
                        $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                        $q = "UPDATE `good` SET `salesListPrice`={$newSaleListPrice} WHERE `RowID`={$result[$i]['RowID']}";
                        $db->Query($q);
                        break;
                }
            }
        }else{
            switch ($res[0]['color']){
                case 'سفید کروم':
                case 'سفید':
                case 'طرح چوب':
                case 'مشکی':
                case 'زیتونی':
                    $sqlq = "SELECT `salesListPrice` FROM `good` WHERE `RowID`={$res[0]['chCode']}";
                    $rst = $db->ArrayQuery($sqlq);
                    $newSaleListPrice = $rst[0]['salesListPrice'] + (($res[0]['proccessWay'] == 'ریخته گری' ? $ChromeToColorRI : $ChromeToColorMA) * $percent) + $res[0]['priceChangeConstant'];
                    $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                    $q = "UPDATE `good` SET `salesListPrice`={$newSaleListPrice} WHERE `RowID`={$gid}";
                    $db->Query($q);
                    break;
                case 'مشکی طلایی':
                case 'سفید طلایی':
                    $sqlq = "SELECT `salesListPrice` FROM `good` WHERE `RowID`={$res[0]['chCode']}";
                    $rst = $db->ArrayQuery($sqlq);
                    $newSaleListPrice = $rst[0]['salesListPrice'] + (($res[0]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenColorRI : $ChromeToGoldenColorMA) * $percent) + $res[0]['priceChangeConstant'];
                    $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                    $q = "UPDATE `good` SET `salesListPrice`={$newSaleListPrice} WHERE `RowID`={$gid}";
                    $db->Query($q);
                    break;
                case 'طلایی مات':
                case 'طرح استیل':
                    $sqlq = "SELECT `salesListPrice` FROM `good` WHERE `RowID`={$res[0]['chCode']}";
                    $rst = $db->ArrayQuery($sqlq);
                    $newSaleListPrice = $rst[0]['salesListPrice'] + (($res[0]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenMattRI : $ChromeToGoldenMattMA) * $percent) + $res[0]['priceChangeConstant'];
                    $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                    $q = "UPDATE `good` SET `salesListPrice`={$newSaleListPrice} WHERE `RowID`={$gid}";
                    $db->Query($q);
                    break;
                case 'طلایی':
                    $sqlq = "SELECT `salesListPrice` FROM `good` WHERE `RowID`={$res[0]['chCode']}";
                    $rst = $db->ArrayQuery($sqlq);
                    $newSaleListPrice = $rst[0]['salesListPrice'] + (($res[0]['proccessWay'] == 'ریخته گری' ? $ChromeToGoldenRI : $ChromeToGoldenMA) * $percent) + $res[0]['priceChangeConstant'];
                    $newSaleListPrice = $this->abrashRound($newSaleListPrice);
                    $q = "UPDATE `good` SET `salesListPrice`={$newSaleListPrice} WHERE `RowID`={$gid}";
                    $db->Query($q);
                    break;
            }
        }

        if ($res[0]['ggroup'] == 'شیلنگ' && $res[0]['brand'] == 'ابرش'){
            if (intval($calcType) == 0) {
                $increaseChromePercent = $res[0]['priceChangePercent'] / 100;
                $newSalesListPrice = $this->abrashRound((($res[0]['salesListPrice'] * $increaseChromePercent) + $res[0]['priceChangeConstant']));
            }else{
                $sqle = "SELECT `salesListPrice` FROM `good` WHERE `RowID`={$gRowID}";
                $rste = $db->ArrayQuery($sqle);
                $newSalesListPrice = $rste[0]['salesListPrice'] + ($res[0]['priceChangeConstant'] * $coefficient);
            }
            $qq = "UPDATE `good` SET `salesListPrice`={$newSalesListPrice} WHERE `RowID`={$gid}";
            $db->Query($qq);
        }

        if ($res[0]['ggroup'] == 'شیلنگ' && $res[0]['brand'] == 'بارشینا'){
            if (intval($calcType) == 0) {
                $increaseChromePercent = $res[0]['priceChangePercent'] / 100;
                $newSalesListPrice = $this->abrashRound((($res[0]['salesListPrice'] * $increaseChromePercent) + $res[0]['priceChangeConstant']));
            }else{
                $sqle = "SELECT `salesListPrice` FROM `good` WHERE `RowID`={$gRowID}";
                $rste = $db->ArrayQuery($sqle);
                $newSalesListPrice = $rste[0]['salesListPrice'] + ($res[0]['priceChangeConstant'] * $coefficient);
            }
            $qq = "UPDATE `good` SET `salesListPrice`={$newSalesListPrice} WHERE `RowID`={$gid}";
            $db->Query($qq);
        }

        if ($res[0]['ggroup'] == 'اتصالات 5 لایه'){
            if (intval($calcType) == 0) {
                $increaseChromePercent = ($res[0]['priceChangePercent'] + 100) / 100;
                $newSalesListPrice = $this->abrashRound((($res[0]['salesListPrice'] * $increaseChromePercent) + $res[0]['priceChangeConstant']));
            }else{
                $sqle = "SELECT `salesListPrice` FROM `good` WHERE `RowID`={$gRowID}";
                $rste = $db->ArrayQuery($sqle);
                $newSalesListPrice = ($rste[0]['salesListPrice'] / $divisibility) * $coefficient;
            }
            $qq = "UPDATE `good` SET `salesListPrice`={$newSalesListPrice} WHERE `RowID`={$gid}";
            $db->Query($qq);
        }

        if ($res[0]['ggroup'] == 'لوله 5 لایه'){
            $increaseChromePercent = ($res[0]['priceChangePercent'] + 100) / 100;
            if ($res[0]['Series'] == 'لوله های PEX'){
                $newSalesListPrice = (($res[0]['salesListPrice'] * $increaseChromePercent) + $res[0]['priceChangeConstant']);
                $newSalesListPrice = ceil($newSalesListPrice/100) * 100;
                $qPert = "SELECT `RowID`,`priceChangePercent`,`priceChangeConstant` FROM `good` WHERE `ggroup`='لوله 5 لایه' AND `Series`='لوله های PERT' AND `cartridgeSize`='{$res[0]['cartridgeSize']}'";
                $rPert = $db->ArrayQuery($qPert);
                $newSalesListPricePERT = (($newSalesListPrice * (($rPert[0]['priceChangePercent'] + 100) / 100)) + $rPert[0]['priceChangeConstant']);
                $newSalesListPricePERT = ceil($newSalesListPricePERT/100) * 100;
                $qq = "UPDATE `good` SET `salesListPrice`={$newSalesListPricePERT} WHERE `RowID`={$rPert[0]['RowID']}";
                $db->Query($qq);
            }else{
                $sqlp = "SELECT `salesListPrice` FROM `good` WHERE `ggroup`='لوله 5 لایه' AND `Series`='لوله های PEX' AND `cartridgeSize`='{$res[0]['cartridgeSize']}' ";
                $resp = $db->ArrayQuery($sqlp);
                $newSalesListPrice = (($resp[0]['salesListPrice'] * $increaseChromePercent) + $res[0]['priceChangeConstant']);
                $newSalesListPrice = ceil($newSalesListPrice/100) * 100;
            }
            $qq = "UPDATE `good` SET `salesListPrice`={$newSalesListPrice} WHERE `RowID`={$gid}";
            $db->Query($qq);
        }

        return true;
    }

    private function abrashRound($price){
        if ($price >= 0 && $price < 10000){
            $price = ceil($price/100) * 100;
        }elseif ($price >= 10000 && $price < 100000000){
            $price = ceil($price/1000) * 1000;
        }elseif ($price >= 100000000){
            $price = ceil($price/10000) * 10000;
        }
        return $price;
    }

    public function perDiscountInfo($brand,$group){
        $db = new DBi();

        $sq1 = "SELECT `title` FROM `categories` WHERE `RowID`={$brand}";
        $res = $db->ArrayQuery($sq1);

        $sq11 = "SELECT `title` FROM `categories` WHERE `RowID`={$group}";
        $res1 = $db->ArrayQuery($sq11);

        $sql2 = "SELECT * FROM `discounts` WHERE `brand`='{$res[0]['title']}' AND `subGroup`='{$res1[0]['title']}'";
        $res2 = $db->ArrayQuery($sql2);
        if(count($res2) > 0){
            $res = array("Wid"=>($res2[0]['RowID']),"perDiscount1"=>($res2[0]['perDiscount1']),"perDiscount2"=>($res2[0]['perDiscount2']),"perDiscount3"=>($res2[0]['perDiscount3']),"perDiscount4"=>($res2[0]['perDiscount4']),"perDiscount5"=>($res2[0]['perDiscount5']),"Priority1"=>($res2[0]['Priority1']),"Priority2"=>($res2[0]['Priority2']),"Priority3"=>($res2[0]['Priority3']),"Priority4"=>($res2[0]['Priority4']),"Priority5"=>($res2[0]['Priority5']));
            return $res;
        }else{
            $res = array("Wid"=>'',"perDiscount1"=>'',"perDiscount2"=>'',"perDiscount3"=>'',"perDiscount4"=>'',"perDiscount5"=>'',"Priority1"=>'',"Priority2"=>'',"Priority3"=>'',"Priority4"=>'',"Priority5"=>'');
            return $res;
        }
    }

    public function editCreatePerDiscount($Wid,$brand,$group,$Dis1,$Dis2,$Dis3,$Dis4,$Dis5,$Priority1,$Priority2,$Priority3,$Priority4,$Priority5){
        $acm = new acm();
        if(!$acm->hasAccess('perDiscountAccess')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sq1b = "SELECT `title` FROM `categories` WHERE `RowID`={$brand}";
        $resb = $db->ArrayQuery($sq1b);

        $sq1g = "SELECT `title` FROM `categories` WHERE `RowID`={$group}";
        $resg = $db->ArrayQuery($sq1g);

        if (intval($Wid) > 0) {
            $sql = "UPDATE `discounts` SET `perDiscount1`={$Dis1},`perDiscount2`={$Dis2},`perDiscount3`={$Dis3},`perDiscount4`={$Dis4},`perDiscount5`={$Dis5},`Priority1`={$Priority1},`Priority2`={$Priority2},`Priority3`={$Priority3},`Priority4`={$Priority4},`Priority5`={$Priority5} WHERE `RowID`=".$Wid;
            $db->Query($sql);
            $res = $db->AffectedRows();
            $res = ((intval($res) == -1 || $res == 0) ? 0 : 1);
            if (intval($res)) {
                return true;
            } else {
                return false;
            }
        }else{
            $sql = "INSERT INTO `discounts` (`brand`,`subGroup`,`perDiscount1`,`perDiscount2`,`perDiscount3`,`perDiscount4`,`perDiscount5`,`Priority1`,`Priority2`,`Priority3`,`Priority4`,`Priority5`) VALUES ('{$resb[0]['title']}','{$resg[0]['title']}',{$Dis1},{$Dis2},{$Dis3},{$Dis4},{$Dis5},{$Priority1},{$Priority2},{$Priority3},{$Priority4},{$Priority5})";
            $res = $db->Query($sql);
            if (intval($res) > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getCheckPricesList($SPercent,$EPercent,$checkList){
        $acm = new acm();
        if(!$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        switch (intval($checkList)){
            case '0':
                $sql = "SELECT `RowID`,`gCode`,`gName`,`salesListPrice`,`newSalesListPrice` FROM `good` WHERE `isEnable`=1 AND `ggroup`='شیرآلات' AND `brand`='ابرش'";
                $res = $db->ArrayQuery($sql);
                break;
            case '1':
                $sql = "SELECT `RowID`,`gCode`,`gName`,`salesListPrice`,`newSalesListPrice` FROM `good` WHERE `isEnable`=1 AND `ggroup`='شیرآلات' AND `brand`='بارشینا'";
                $res = $db->ArrayQuery($sql);
                break;
            case '2':
                $sql = "SELECT `RowID`,`gCode`,`gName`,`salesListPrice`,`newSalesListPrice` FROM `good` WHERE `isEnable`=1 AND `ggroup`='شیلنگ' AND `brand`='ابرش'";
                $res = $db->ArrayQuery($sql);
                break;
            case '3':
                $sql = "SELECT `RowID`,`gCode`,`gName`,`salesListPrice`,`newSalesListPrice` FROM `good` WHERE `isEnable`=1 AND `ggroup`='شیلنگ' AND `brand`='بارشینا'";
                $res = $db->ArrayQuery($sql);
                break;
            case '4':
                $sql = "SELECT `RowID`,`gCode`,`gName`,`salesListPrice`,`newSalesListPrice` FROM `good` WHERE `isEnable`=1 AND `ggroup`='اتصالات 5 لایه'";
                $res = $db->ArrayQuery($sql);
                break;
            case '5':
                $sql = "SELECT `RowID`,`gCode`,`gName`,`salesListPrice`,`newSalesListPrice` FROM `good` WHERE `isEnable`=1 AND `ggroup`='لوله 5 لایه'";
                $res = $db->ArrayQuery($sql);
                break;
        }
        $cnt = count($res);

        $x = 0;
        $finalRes = array();
        for ($i=0;$i<$cnt;$i++){
            if (intval($res[$i]['newSalesListPrice']) > 0 && intval($res[$i]['salesListPrice'] > 0)) {
                $percent = (((intval($res[$i]['newSalesListPrice']) - intval($res[$i]['salesListPrice'])) / intval($res[$i]['salesListPrice'])) * 100) + 100;
                if ((intval($percent) > intval($EPercent)) || (intval($percent) < intval($SPercent))) {
                    $finalRes[$x]['RowID'] = $res[$i]['RowID'];
                    $finalRes[$x]['iterator'] = $x+1;
                    $finalRes[$x]['gName'] = $res[$i]['gName'];
                    $finalRes[$x]['gCode'] = $res[$i]['gCode'];
                    $finalRes[$x]['salesListPrice'] = number_format($res[$i]['salesListPrice']) . ' ریال';
                    $finalRes[$x]['newSalesListPrice'] = number_format($res[$i]['newSalesListPrice']) . ' ریال';
                    $finalRes[$x]['percent'] = number_format($percent, 1);
                    $x++;
                }
            }
        }
        return $finalRes;
    }

    public function uploadGSPListFile($gsplist){
        $acm = new acm();
        if(!$acm->hasAccess('createNewSalesPriceGoodsList')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)).'/pmlexcel/excGSPL.xlsx';
        unlink($file_to_delete);

        if (isset($gsplist) && intval($gsplist['size']) > 0) {
            $sql = "DROP TABLE IF EXISTS `goodsalepricelist`";
            $db->Query($sql);
            $gsplFile = "excGSPL.xlsx";
            $gsplist['name'] = $gsplFile;
            $upload = move_uploaded_file($gsplist["tmp_name"],'../pmlexcel/'.$gsplist["name"]);
            if(!$upload){
                return false;
            }else{
                $result = $this->createGoodSalePriceListDB();
                if ($result){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    private function createGoodSalePriceListDB(){
        $inputFileName = '../pmlexcel/excGSPL.xlsx';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($inputFileName);

        $db = new DBi();
        $sql = "DROP TABLE IF EXISTS `goodsalepricelist`";
        $db->Query($sql);

        $flag = true;
        $rowIterator = $spreadsheet->getActiveSheet()->getRowIterator();
        $skip_rows = 0;
        $excell_array_data = array();
        foreach($rowIterator as $row){
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            if($skip_rows >= $row->getRowIndex ()) continue;
            $rowIndex = $row->getRowIndex ();
            $excell_array_data[$rowIndex] = array();

            foreach ($cellIterator as $cell) {
                $excell_array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
            }
        }

        //Create Database table with one Field
        $sql = "CREATE TABLE `goodsalepricelist` (`RowID` INT NOT NULL PRIMARY KEY AUTO_INCREMENT)";
        $aff = $db->Query($sql);
        if (intval($aff) == -1 || intval($aff) == 0){
            $flag = false;
        }

        //Create Others Field (A, B, C & ...)
        $columns_name = $excell_array_data[$skip_rows+1];
        foreach (array_keys($columns_name) as $fieldname ){
            $sql1 = "ALTER TABLE `goodsalepricelist` ADD $fieldname VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin";
            $aff1 = $db->Query($sql1);
            if (intval($aff1) == -1 || intval($aff1) == 0){
                $flag = false;
            }
        }

        //Insert Excel data to MySQL
        $keys = array_keys($excell_array_data[1]);
        $keys = implode(',',$keys);

        $chunk = array_chunk($excell_array_data,100);
        $cnt = count($chunk);

        for ($i=0;$i<$cnt;$i++){
            $ccnt = count($chunk[$i]);
            $insertValue = array();
            for ($j=0;$j<$ccnt;$j++){
               $insertValue[] = "('".implode( "','",array_values($chunk[$i][$j]))."')";
            }
            $insertValue = implode(',',$insertValue);
            $sql2 = "INSERT INTO `goodsalepricelist` ($keys) VALUES ".$insertValue." ";
            $aff2 = $db->Query($sql2);
            if (intval($aff2) <= 0){
                $flag = false;
            }
        }

        if ($flag){
            return true;
        }else{
            return false;
        }
    }

    public function uploadAgainSalesPriceGoodsList(){
        $acm = new acm();
        if(!$acm->hasAccess('createNewSalesPriceGoodsList')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        mysqli_autocommit($db->Getcon(),FALSE);

        $sql = "SELECT * FROM `goodsalepricelist` ORDER BY `RowID` ASC ";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $flag = true;
        $nowDate = date('Y-m-d');

        for ($i=0;$i<$cnt;$i++){
            if (intval($res[$i]['B']) == 0 || strlen(trim($res[$i]['B'])) == 0){
                continue;
            }
            $query = "UPDATE `good` SET `salesListPrice`={$res[$i]['B']} WHERE `gCode`='{$res[$i]['A']}'";
            $db->Query($query);
            $aff = $db->AffectedRows();
            if (intval($aff) == -1){
                //$ut->fileRecorder($query);
                $flag = false;
            }
        }
        $query1 = "UPDATE `good` SET `updatePriceDate`='{$nowDate}'";
        $db->Query($query1);

        if ($flag){
            mysqli_commit($db->Getcon());
            return true;
        }else{
            mysqli_rollback($db->Getcon());
            return false;
        }
    }

    public function getGoodSalePrice($gid){
        $db = new DBi();
        $sql = "SELECT `salesListPrice` FROM `good` WHERE `RowID`={$gid}";
        $res = $db->ArrayQuery($sql);
        $res = array("salesListPrice"=>number_format($res[0]['salesListPrice']));
        return $res;
    }

    public function editOneSalesPriceGoods($gid,$amount){
        $acm = new acm();
        if(!$acm->hasAccess('createNewSalesPriceGoodsList')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $sql = "UPDATE `good` SET `salesListPrice`={$amount} WHERE `RowID`={$gid}";
        //$ut->fileRecorder($sql);
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    //+++++++++++++++++ مقایسه قیمت تمام شده/ فروش +++++++++++++++++++

    public function getComparePricesHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('comparePricesManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();

        $pagename = "مقایسه قیمت تمام شده/ فروش";
        $pageIcon = "fas fa-not-equal";
        $contentId = "comparePricesManageBody";

        $c = 0;
        $bottons= array();

        $headerSearch = array();
        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "200px";
        $headerSearch[$c]['id'] = "comparePricesManageGNameSearch";
        $headerSearch[$c]['title'] = "نام محصول";
        $headerSearch[$c]['placeholder'] = "نام محصول";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "comparePricesManagePCodeSearch";
        $headerSearch[$c]['title'] = "کد محصول";
        $headerSearch[$c]['placeholder'] = "کد محصول";
        $c++;

        $headerSearch[$c]['type'] = "btn";
        $headerSearch[$c]['title'] = "جستجو&nbsp;&nbsp;<i class='fas fa-search'></i>";
        $headerSearch[$c]['jsf'] = "showComparePricesManageList";

        $changeableFields = array();
        $c = 0;

        $changeableFields[$c]['type'] = "select";
        $changeableFields[$c]['id'] = "comparePricesManagePriceType";
        $changeableFields[$c]['title'] = "نوع قیمت تمام شده";
        $changeableFields[$c]['width'] = "200px";
        $changeableFields[$c]['onchange'] = "onchange='updatePriceTypeList()'";
        $changeableFields[$c]['options'] = array();
        $changeableFields[$c]['options'][0]["title"] = 'قیمت نهایی مواد';
        $changeableFields[$c]['options'][0]["value"] = 0;
        $changeableFields[$c]['options'][1]["title"] = 'قیمت نهایی مواد - نقدی';
        $changeableFields[$c]['options'][1]["value"] = 1;
        $changeableFields[$c]['options'][2]["title"] = 'قیمت نهایی محصول با هزینه تولید';
        $changeableFields[$c]['options'][2]["value"] = 2;
        $changeableFields[$c]['options'][3]["title"] = 'قیمت نهایی محصول با هزینه تولید - نقدی';
        $changeableFields[$c]['options'][3]["value"] = 3;
        $c++;

        $changeableFields[$c]['type'] = "select";
        $changeableFields[$c]['id'] = "comparePricesManageSalePriceType";
        $changeableFields[$c]['title'] = "نوع قیمت فروش";
        $changeableFields[$c]['width'] = "200px";
        $changeableFields[$c]['onchange'] = "onchange='updateSaleTypeList()'";
        $changeableFields[$c]['multiple'] = "multiple";
        $changeableFields[$c]['options'] = array();
        $changeableFields[$c]['options'][0]["title"] = 'لیست فروش';
        $changeableFields[$c]['options'][0]["value"] = 0;
        $changeableFields[$c]['options'][1]["title"] = 'تخفیف خرید مدت دار';
        $changeableFields[$c]['options'][1]["value"] = 1;
        $changeableFields[$c]['options'][2]["title"] = 'تخفیف نمایندگی';
        $changeableFields[$c]['options'][2]["value"] = 2;
        $changeableFields[$c]['options'][3]["title"] = 'تخفیف پرداخت نقدی';
        $changeableFields[$c]['options'][3]["value"] = 3;
        $changeableFields[$c]['options'][4]["title"] = 'تخفیف خرید لوله و اتصالات باهم';
        $changeableFields[$c]['options'][4]["value"] = 4;
        $changeableFields[$c]['options'][5]["title"] = 'تخفیف پایان دوره';
        $changeableFields[$c]['options'][5]["value"] = 5;

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch,'',$changeableFields);
        //++++++++++++++++++ Start Good Error Info Modal ++++++++++++++++++++++
        $modalID = "goodErrorInfoModalC";
        $modalTitle = "خطاها";
        $ShowDescription = 'Good-error-Info-bodyC';
        $style = 'style="max-width: 800px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showGoodErrorInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Good Error Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Good FINE PRICE Modal ++++++++++++++++++++++
        $modalID = "goodFinePriceCPRModal";
        $modalTitle = "اجزا محصول";
        $ShowDescription = 'Good-fine-priceCPR-body';
        $style = 'style="max-width: 1024px;"';
        $items = array();
        $footerBottons = array();
        $x = 0;
        if($acm->hasAccess('excelexport')){
            $footerBottons[$x]['title'] = "خروجی اکسل";
            $footerBottons[$x]['jsf'] = "getExcelGoodFinePrice";
            $footerBottons[$x]['type'] = "btn-success";
            $x++;
        }
        $footerBottons[$x]['title'] = "بستن";
        $footerBottons[$x]['type'] = "dismis";
        $showGoodFinePriceInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Good FINE PRICE Modal ++++++++++++++++++++++++
        $htm .= $showGoodErrorInfo;
        $htm .= $showGoodFinePriceInfo;
        return $htm;
    }

    public function getComparePricesList($gName,$gCode,$pType,$sType,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('comparePricesManage')){
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
            $w[] = '`gCode` LIKE "%'.$gCode.'%" ';
        }
        switch ($pType){
            case 0:
                $sql = "SELECT `RowID`,`brand`,`ggroup`,`gName`,`gCode`,`error`,`finalPriceMaterials`,`salesListPrice` FROM `good`";
                $priceType = 'finalPriceMaterials';
                break;
            case 1:
                $sql = "SELECT `RowID`,`brand`,`ggroup`,`gName`,`gCode`,`error`,`finalPriceMaterialsCash`,`salesListPrice` FROM `good`";
                $priceType = 'finalPriceMaterialsCash';
                break;
            case 2:
                $sql = "SELECT `RowID`,`brand`,`ggroup`,`gName`,`gCode`,`error`,`fppProductionCosts`,`salesListPrice` FROM `good`";
                $priceType = 'fppProductionCosts';
                break;
            case 3:
                $sql = "SELECT `RowID`,`brand`,`ggroup`,`gName`,`gCode`,`error`,`fppProductionCostsCash`,`salesListPrice` FROM `good`";
                $priceType = 'fppProductionCostsCash';
                break;
        }

        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE `isEnable`=1 AND ".$where;
        }else{
            $sql .= " WHERE `isEnable`=1";
        }
        $sql .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);

        $sType = explode(',', $sType);
        $cnt = count($sType);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            $query = "SELECT `perDiscount1`,`perDiscount2`,`perDiscount3`,`perDiscount4`,`perDiscount5`,`Priority1`,`Priority2`,`Priority3`,`Priority4`,`Priority5` FROM `discounts` WHERE `brand`='{$res[$y]['brand']}' AND `subGroup`='{$res[$y]['ggroup']}'";
            $rst = $db->ArrayQuery($query);

            if (count($rst) > 0) {
                $percent = array();
                $pt0 = (intval($res[$y]['salesListPrice']) > 0 ? $res[$y]['salesListPrice'] : 0);
                $pt = 0;
                for ($i = 0; $i < $cnt; $i++) {
                    switch ($sType[$i]) {
                        case 0:
                            $pt = $pt0;
                            break;
                        case 1:
                            $percent[$rst[0]['Priority1']] = $rst[0]['perDiscount1'];
                            break;
                        case 2:
                            $percent[$rst[0]['Priority2']] = $rst[0]['perDiscount2'];
                            break;
                        case 3:
                            $percent[$rst[0]['Priority3']] = $rst[0]['perDiscount3'];
                            break;
                        case 4:
                            $percent[$rst[0]['Priority4']] = $rst[0]['perDiscount4'];
                            break;
                        case 5:
                            $percent[$rst[0]['Priority5']] = $rst[0]['perDiscount5'];
                            break;
                    }
                }
                ksort($percent);
                $percent = array_values($percent);
                $cnt1 = count($percent);
                for ($a=0;$a<$cnt1;$a++){
                    $pt = $pt0 * (1 - ($percent[$a]/100));
                    $pt0 = $pt;
                }

            }else{
                $pt = (intval($res[$y]['salesListPrice']) > 0 ? $res[$y]['salesListPrice'] : 0);
            }

            $finalRes[$y]['btnType'] = (strlen(trim($res[$y]['error'])) > 0 ? 'btn-danger' : 'btn-success');
            $finalRes[$y]['disabled'] = (strlen(trim($res[$y]['error'])) > 0 ? '' : 'disabled');
            $finalRes[$y]['icon'] = 'fa-exclamation-circle';

            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['pType'] = (intval($res[$y]["$priceType"]) > 0 ? number_format($res[$y]["$priceType"]).' ریال' : '');
            $finalRes[$y]['salesListPrice'] = (intval($pt) > 0 ? number_format($pt).' ریال' : '');
            $PerDifference = ((intval($pt) - intval($res[$y]["$priceType"]))/intval($res[$y]["$priceType"]))*100;
            $finalRes[$y]['PerDifference'] = round($PerDifference,2);
        }
        return $finalRes;
    }

    public function getComparePricesListCountRows($gName,$gCode){
        $acm = new acm();
        if(!$acm->hasAccess('comparePricesManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $w = array();
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`gCode` LIKE "%'.$gCode.'%" ';
        }
        $sql = "SELECT `RowID` FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE `isEnable`=1 AND ".$where;
        }else{
            $sql .= " WHERE `isEnable`=1";
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    //+++++++++++++++++ گزارش تغییرات BOM +++++++++++++++++++

    public function getBomChangeReportManageHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('bomChangeReportManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $pagename = "گزارش تغییرات BOM";
        $pageIcon = "fas fa-exchange-alt";
        $contentId = "bomChangeReportManageBody";

        $c = 0;
        $bottons= array();

        $headerSearch = array();
        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "bomChangeReportManageSdateSearch";
        $headerSearch[$c]['title'] = "از تاریخ";
        $headerSearch[$c]['placeholder'] = "از تاریخ";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "bomChangeReportManageEdateSearch";
        $headerSearch[$c]['title'] = "تا تاریخ";
        $headerSearch[$c]['placeholder'] = "تا تاریخ";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "250px";
        $headerSearch[$c]['id'] = "bomChangeReportManageGNameSearch";
        $headerSearch[$c]['title'] = "نام محصول";
        $headerSearch[$c]['placeholder'] = "نام محصول";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "100px";
        $headerSearch[$c]['id'] = "bomChangeReportManageGCodeSearch";
        $headerSearch[$c]['title'] = "کد محصول";
        $headerSearch[$c]['placeholder'] = "کد محصول";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "250px";
        $headerSearch[$c]['id'] = "bomChangeReportManagePNameSearch";
        $headerSearch[$c]['title'] = "نام قطعه";
        $headerSearch[$c]['placeholder'] = "نام قطعه";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "100px";
        $headerSearch[$c]['id'] = "bomChangeReportManagePCodeSearch";
        $headerSearch[$c]['title'] = "کد قطعه";
        $headerSearch[$c]['placeholder'] = "کد قطعه";
        $c++;

        $headerSearch[$c]['type'] = "btn";
        $headerSearch[$c]['title'] = "جستجو&nbsp;&nbsp;<i class='fas fa-search'></i>";
        $headerSearch[$c]['jsf'] = "showBomChangeReportManageList";

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        return $htm;
    }

    public function getBomChangeReportList($pName,$gName,$pCode,$gCode,$sDate,$eDate,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('bomChangeReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        if(strlen(trim($pCode)) > 0){
            $w[] = '`piece`.`pCode` LIKE "%'.$pCode.'%" ';
        }
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`good`.`gCode` LIKE "%'.$gCode.'%" ';
        }
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`changeDate`>="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`changeDate`<="'.$eDate.'" ';
        }
        $sql = "SELECT `pName`,`piece`.`pCode`,`gName`,`good`.`gCode`,`pValue`,`cValue`,`changeDate` FROM `piece` 
                INNER JOIN `backup_interface` ON (`piece`.`pCode`=`backup_interface`.`pCode`)
                INNER JOIN `good` ON (`good`.`gCode`=`backup_interface`.`gCode`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `backup_interface`.`RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['pName'] = $res[$y]['pName'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['pValue'] = $res[$y]['pValue'];
            $finalRes[$y]['cValue'] = $res[$y]['cValue'];
            $finalRes[$y]['changeDate'] = $ut->greg_to_jal($res[$y]['changeDate']);
        }
        return $finalRes;
    }

    public function getBomChangeReportListCountRows($pName,$gName,$pCode,$gCode,$sDate,$eDate){
        $acm = new acm();
        if(!$acm->hasAccess('bomChangeReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        if(strlen(trim($pCode)) > 0){
            $w[] = '`piece`.`pCode` LIKE "%'.$pCode.'%" ';
        }
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`good`.`gCode` LIKE "%'.$gCode.'%" ';
        }
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`changeDate`>="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`changeDate`<="'.$eDate.'" ';
        }
        $sql = "SELECT `pName` FROM `piece` 
                INNER JOIN `backup_interface` ON (`piece`.`pCode`=`backup_interface`.`pCode`)
                INNER JOIN `good` ON (`good`.`gCode`=`backup_interface`.`gCode`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    //+++++++++++++++++ گزارش تغییرات بار برنج +++++++++++++++++++

    public function getBrassWeightReportManageHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('brassWeightReportManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $pagename = "گزارش تغییرات بار برنج";
        $pageIcon = "fas fa-weight";
        $contentId = "BWReportManageBody";

        $c = 0;
        $bottons= array();

        $headerSearch = array();
        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "BWReportManageSdateSearch";
        $headerSearch[$c]['title'] = "از تاریخ";
        $headerSearch[$c]['placeholder'] = "از تاریخ";
        $c++;

        $headerSearch[$c]['type'] = "text";
        $headerSearch[$c]['width'] = "120px";
        $headerSearch[$c]['id'] = "BWReportManageEdateSearch";
        $headerSearch[$c]['title'] = "تا تاریخ";
        $headerSearch[$c]['placeholder'] = "تا تاریخ";
        $c++;

        $headerSearch[$c]['type'] = "btn";
        $headerSearch[$c]['title'] = "جستجو&nbsp;&nbsp;<i class='fas fa-search'></i>";
        $headerSearch[$c]['jsf'] = "showBWReportManageList";

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        return $htm;
    }

    public function getBWReportManageList($sDate,$eDate,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('brassWeightReportManage')){
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
            $w[] = '`changeDate`>="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`changeDate`<="'.$eDate.'" ';
        }
        $sql = "SELECT * FROM `backup_brass_weight`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['brassSwarfPrice'] = (intval($res[$y]['brassSwarfPrice']) == 0 ? '--------' : number_format($res[$y]['brassSwarfPrice']).' ریال');
            $finalRes[$y]['BullionPriceUp14'] = (intval($res[$y]['BullionPriceUp14']) == 0 ? '--------' : number_format($res[$y]['BullionPriceUp14']).' ریال');
            $finalRes[$y]['BullionPriceUnder14'] = (intval($res[$y]['BullionPriceUnder14']) == 0 ? '--------' : number_format($res[$y]['BullionPriceUnder14']).' ریال');
            $finalRes[$y]['BullionPriceColector'] = (intval($res[$y]['BullionPriceColector']) == 0 ? '--------' : number_format($res[$y]['BullionPriceColector']).' ریال');
            $finalRes[$y]['CastingPrice'] = (intval($res[$y]['CastingPrice']) == 0 ? '--------' : number_format($res[$y]['CastingPrice']).' ریال');
            $finalRes[$y]['PolishingSoilPrice'] = (intval($res[$y]['PolishingSoilPrice']) == 0 ? '--------' : number_format($res[$y]['PolishingSoilPrice']).' ریال');
            $finalRes[$y]['PercentFuelWeight'] = (floatval($res[$y]['PercentFuelWeight']) == 0 ? '--------' : $res[$y]['PercentFuelWeight'].' درصد');
            $finalRes[$y]['changeDate'] = $ut->greg_to_jal($res[$y]['changeDate']);
        }
        return $finalRes;
    }

    public function getBWReportManageListCountRows($sDate,$eDate){
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`changeDate`>="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`changeDate`<="'.$eDate.'" ';
        }
        $sql = "SELECT `RowID` FROM `backup_brass_weight`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }
}