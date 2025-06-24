<?php
/**
 * Created by PhpStorm.
 * User: Majid
 * Date: 12/10/2018
 * Time: 7:54 AM
 */

class Good{

    public function __construct(){
        // do nothing
    }

    public function getManageGoodHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $pagename = "مدیریت محصولات";
        $pageIcon = "fas fa-archive";
        $contentId = "goodManageBody";

        $c = 0;
        $bottons= array();
        if($acm->hasAccess('engineeringAccess') && !$acm->hasAccess('procurementAccess')) {  // فقط مهندسی
            $bottons[$c]['title'] = "آپلود محصولات";
            $bottons[$c]['jsf'] = "uploadGoodMasterList";
            $bottons[$c]['icon'] = "fa-upload";
            $bottons[$c]['id'] = 'id="uploadGoodMasterListID"';
            $c++;

            $bottons[$c]['title'] = "بارگذاری مجدد محصولات";
            $bottons[$c]['jsf'] = "uploadAgainGoodMasterList";
            $bottons[$c]['icon'] = "fa-plus-square";
            $bottons[$c]['id'] = 'id="uploadAgainGoodMasterListID"';
            $c++;

            $bottons[$c]['title'] = "آپلود BOM";
            $bottons[$c]['jsf'] = "uploadBOMList";
            $bottons[$c]['icon'] = "fa-upload";
            $bottons[$c]['id'] = 'id="uploadBOMListID"';
            $c++;

            $bottons[$c]['title'] = "بارگذاری مجدد BOM";
            $bottons[$c]['jsf'] = "uploadAgainBOMList";
            $bottons[$c]['icon'] = "fa-plus-square";
            $bottons[$c]['id'] = 'id="uploadAgainBOMListID"';
            $c++;

            $bottons[$c]['title'] = "ثبت محصول جدید";
            $bottons[$c]['jsf'] = "createGood";
            $bottons[$c]['icon'] = "fa-plus-square";
            $c++;

            $bottons[$c]['title'] = "ویرایش محصول";
            $bottons[$c]['jsf'] = "editGood";
            $bottons[$c]['icon'] = "fa-edit";
            $c++;

            $bottons[$c]['title'] = "افزودن/ویرایش قطعه در محصولات";
            $bottons[$c]['jsf'] = "addEditPieceToGoods";
            $bottons[$c]['icon'] = "fa-plus-square";
            $c++;

            $bottons[$c]['title'] = "افزودن قطعات";
            $bottons[$c]['jsf'] = "addPiecesToGood";
            $bottons[$c]['icon'] = "fa-plus-square";

            if ($acm->hasAccess("excelexportBOM")) {
                $c++;
                $bottons[$c]['title'] = "خروجی اکسل BOM";
                $bottons[$c]['jsf'] = "generalExcelBOM";
                $bottons[$c]['icon'] = "fa-file-excel";
            }
           
        }
    
        if($acm->hasAccess('engineeringAccess') && $acm->hasAccess('procurementAccess')) {  // دسترسی تدارکات و مهندسی
            $bottons[$c]['title'] = "ثبت محصول جدید";
            $bottons[$c]['jsf'] = "createGood";
            $bottons[$c]['icon'] = "fa-plus-square";
            $c++;

            $bottons[$c]['title'] = "ویرایش محصول";
            $bottons[$c]['jsf'] = "editGood";
            $bottons[$c]['icon'] = "fa-edit";
            $c++;

            $bottons[$c]['title'] = "افزودن/ویرایش قطعه در محصولات";
            $bottons[$c]['jsf'] = "addEditPieceToGoods";
            $bottons[$c]['icon'] = "fa-plus-square";
            $c++;

            $bottons[$c]['title'] = "افزودن قطعات";
            $bottons[$c]['jsf'] = "addPiecesToGood";
            $bottons[$c]['icon'] = "fa-plus-square";

      
            
            

            if ($acm->hasAccess("excelexportBOM")) {
                $c++;
                $bottons[$c]['title'] = "خروجی اکسل BOM";
                $bottons[$c]['jsf'] = "generalExcelBOM";
                $bottons[$c]['icon'] = "fa-file-excel";
            }
        }
        if($acm->hasAccess('financialAccess') || $acm->hasAccess('industrialManagement')) {  // دسترسی مالی یا صنایع
            if ($acm->hasAccess("excelexport")) {
                $bottons[$c]['title'] = "خروجی اکسل";
                $bottons[$c]['jsf'] = "createMasterListGoodExcel";
                $bottons[$c]['icon'] = "fa-file-excel";
                if ($acm->hasAccess("excelexportBOM")) {
                    $c++;
                    $bottons[$c]['title'] = "گزارش  BOM محصولات خروجی اکسل";
                    $bottons[$c]['jsf'] = "generalExcelBOMReport";
                    $bottons[$c]['icon'] = "fa-file-excel";
    
                }
                if ($acm->hasAccess("excelexportBOM")) {
                    $c++;
                    $bottons[$c]['title'] = "گزارش  BOM قطعات خروجی اکسل";
                    $bottons[$c]['jsf'] = "pieceExcelBOMReport";
                    $bottons[$c]['icon'] = "fa-file-excel";
                }
            }
        }
        //----- add update_history_goods----
        $bottons[$c]['title'] = " مشاهده سوابق بروزرسانی محصول";
        $bottons[$c]['jsf'] = "pieces_product_bom_update_history";
        $bottons[$c]['icon'] = "fa-eye";
        $bottons[$c]['params'] = "2";
        $c++;
        //--------------------------
        //----- add update_history_bom----
        $bottons[$c]['title'] = "مشاهده سوابق بروزرسانی  BOM";
        $bottons[$c]['jsf'] = "pieces_product_bom_update_history";
        $bottons[$c]['icon'] = "fa-eye";
        $bottons[$c]['params'] = "3";
        $c++;
        //--------------------------
		
        $headerSearch = array();
        $headerSearch[0]['type'] = "text";
        $headerSearch[0]['width'] = "150px";
        $headerSearch[0]['id'] = "goodManageGnameSearch";
        $headerSearch[0]['title'] = "قسمتی از نام محصول";
        $headerSearch[0]['placeholder'] = "قسمتی از نام محصول";

        $headerSearch[1]['type'] = "btn";
        $headerSearch[1]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
        $headerSearch[1]['jsf'] = "showMGoodManageList";

        $headerSearch[2]['type'] = "btn";
        $headerSearch[2]['title'] = "مرحله قبل&nbsp;&nbsp;<i class='fa fa-redo-alt'></i>";
        $headerSearch[2]['jsf'] = "showBMGoodManageList";

		$headerSearch[3]['type'] = "text";
        $headerSearch[3]['width'] = "120px";
        $headerSearch[3]['id'] = "goodManageGcodeSearch";
        $headerSearch[3]['title'] = "کد مهندسی محصول";
        $headerSearch[3]['placeholder'] = "کد  مهندسی محصول";

        $headerSearch[4]['type'] = "text";
        $headerSearch[4]['width'] = "120px";
        $headerSearch[4]['id'] = "goodManageHcodeSearch";
        $headerSearch[4]['title'] = "کد محصول";
        $headerSearch[4]['placeholder'] = "کد محصول";

        $headerSearch[5]['type'] = "select";
        $headerSearch[5]['id'] = "goodManageParvanehSearch";
        $headerSearch[5]['title'] = "پروانه بهره برداری";
        $headerSearch[5]['width'] = "150px";
        $headerSearch[5]['options'] = array();
        $headerSearch[5]['options'][0]["title"] = 'پروانه بهره برداری';
        $headerSearch[5]['options'][0]["value"] = -1;
        $headerSearch[5]['options'][1]["title"] = 'شیرآلات بهداشتی اهرمی';
        $headerSearch[5]['options'][1]["value"] = 0;
        $headerSearch[5]['options'][2]["title"] = 'شیرآلات بهداشتی معمولی';
        $headerSearch[5]['options'][2]["value"] = 1;
        $headerSearch[5]['options'][3]["title"] = 'شیرآلات توپی';
        $headerSearch[5]['options'][3]["value"] = 2;
        $headerSearch[5]['options'][4]["title"] = 'شیلنگ و لوله لاستیکی ولکانیزه شده تقویت شده با فلز با لوازم و ملحقات';
        $headerSearch[5]['options'][4]["value"] = 3;
        $headerSearch[5]['options'][5]["title"] = 'قطعات و اتصالات لوله از جنس مس و آلیاژهای آن';
        $headerSearch[5]['options'][5]["value"] = 4;
        $headerSearch[5]['options'][6]["title"] = 'لوازم بهداشتی پلاستیکی ساختمان';
        $headerSearch[5]['options'][6]["value"] = 5;
        $headerSearch[5]['options'][7]["title"] = 'لوله از پلی اتیلن پنج لایه با فلز';
        $headerSearch[5]['options'][7]["value"] = 6;

        $headerSearch[6]['type'] = "btn";
        $headerSearch[6]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
        $headerSearch[6]['jsf'] = "showGoodManageList";

        $headerSearch[7]['type'] = "btn";
        $headerSearch[7]['title'] = "پاک سازی فیلترها&nbsp;&nbsp;<i class='fa fa-redo-alt'></i>";
        $headerSearch[7]['jsf'] = "emptyGoodManageSearchFilters";

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        //++++++++++++++++++++++++++++++++++ EDIT CREATE MODAL ++++++++++++++++++++++++++++++++
        $modalID = "goodManagmentModal";
        $modalTitle = "فرم ایجاد/ویرایش محصولات";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "goodManagmentGname";
        $items[$c]['title'] = "نام محصول";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "goodManagmentGcode";
        $items[$c]['title'] = "کد محصول";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "goodManagmentSimilarGood";
        $items[$c]['title'] = "محصول مشابه";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageGoodHiddenGid";
		$c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageGoodHiddenPage";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateGood";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++ END OF EDIT CREATE MODAL ++++++++++++++++++
        //++++++++++++++++++ Add Piece To Goods MODAL ++++++++++++++++++
        $modalID = "addEditPieceToGoodsManagmentModal";
        $modalTitle = "فرم افزودن/ویرایش قطعه در محصولات";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "addPieceToGoodsManagePName";
        $items[$c]['title'] = "نام قطعه";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "addPieceToGoodsManagePCoefficient";
        $items[$c]['title'] = "ضریب قطعه";
        $items[$c]['placeholder'] = "ضریب";
        $c++;

        $items[$c]['type'] = "inputGroup";
        $items[$c]['id'] = "addPieceToGoodsManageGName";
        $items[$c]['icon'] = "fa-plus fa-lg";
        $items[$c]['onclick'] = "onclick='addGoods()'";
        $items[$c]['title'] = "انتخاب محصول";
        $items[$c]['placeholder'] = "تایپ کنید";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "addPieceToGoodsManageGNames";
        $items[$c]['style'] = "style='resize: none;'";
        $items[$c]['title'] = "محصولات";
        $items[$c]['placeholder'] = "محصول / محصولات";

        $footerBottons = array();
        $footerBottons[0]['title'] = "افزودن";
        $footerBottons[0]['jsf'] = "doAddPieceToGoods";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "ویرایش";
        $footerBottons[1]['jsf'] = "doEditPieceToGoods";
        $footerBottons[1]['type'] = "btn-success";
        $footerBottons[2]['title'] = "انصراف";
        $footerBottons[2]['type'] = "dismis";
        $addPieceToGoodsModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++ END OF Add Piece To Goods MODAL ++++++++++++++++++
        //++++++++++++++++++ Add Pieces To Good MODAL ++++++++++++++++++
        $modalID = "addPiecesToGoodManagmentModal";
        $modalTitle = "فرم افزودن قطعات به محصول";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "inputGroup";
        $items[$c]['id'] = "addPiecesToGoodManagePName";
        $items[$c]['icon'] = "fa-plus fa-lg";
        $items[$c]['onclick'] = "onclick='addPieces()'";
        $items[$c]['title'] = "انتخاب قطعه";
        $items[$c]['placeholder'] = "تایپ کنید";
        $c++;

        $items[$c]['type'] = "inputGroup";
        $items[$c]['id'] = "addPiecesToGoodManagePCoefficient";
        $items[$c]['icon'] = "fa-plus fa-lg";
        $items[$c]['onclick'] = "onclick='addPCoefficient()'";
        $items[$c]['title'] = "ضریب قطعه";
        $items[$c]['placeholder'] = "تایپ کنید";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "addPiecesToGoodManagePiecesCoefficient";
        $items[$c]['style'] = "style='resize: none;'";
        $items[$c]['title'] = "قطعات و ضریب ها";
        $items[$c]['placeholder'] = "قطعات و ضریب ها";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "addPiecesToGoodManageHiddenGid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doAddPiecesToGood";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $addPiecesToGoodModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++ END OF Add Pieces To Good MODAL ++++++++++++++++++
		//++++++++++++++++++ Start Good Pieces Modal ++++++++++++++++++
        $modalID = "goodManagePiecesModal";
        $modalTitle = "اجزای محصول";
        $style = 'style="max-width: 1000px;"';
        $ShowDescription = 'good-manage-Pieces-body';
        $items = array();
        $footerBottons = array();
        $a=0;
        if($acm->hasAccess('engineeringAccess') && !$acm->hasAccess('procurementAccess')) {  // فقط مهندسی
            $footerBottons[$a]['title'] = "ثبت";
            $footerBottons[$a]['jsf'] = "doEditCreateCoefficientPiece";
            $footerBottons[$a]['type'] = "btn";
            $footerBottons[$a]['data-dismiss'] = "NO";
            $a++;
        }
        $footerBottons[$a]['title'] = "انصراف";
        $footerBottons[$a]['type'] = "dismis";
        $showGoodPieces = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++ End Good Pieces Modal ++++++++++++++++++
        //++++++++++++++++++ Start Good Info Modal ++++++++++++++++++
        $modalID = "goodManageInfoModal";
        $modalTitle = "اطلاعات محصول";
        $style = 'style="max-width: 500px;"';
        $ShowDescription = 'good-manage-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showGoodInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++ End Good Info Modal ++++++++++++++++++
        //++++++++++++++++++++++++++++++ DELETE MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "goodManageDeletePieceModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به حذف این قطعه مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "usermanage_deleteIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doDeletePieceOfGood";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $delModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF DELETE MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ UPLOAD P MASTERLIST File MODAL++++++++++++++++++++++++++++++++
        $modalID = "uploadGMasterListModal";
        $modalTitle = "آپلود فایل محصولات";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "file";
        $items[$c]['id'] = "goodManagmentPML_File";
        $items[$c]['helpText'] = "نوع فایل باید XLSX باشد.";
        $items[$c]['title'] = "فایل محصولات";
        $items[$c]['accept'] = "accept='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doUploadGMListFile";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $uploadGMasterListModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END UPLOAD P MASTERLIST File MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ UPLOAD BOM LIST MODAL++++++++++++++++++++++++++++++++
        $modalID = "uploadBOMListModal";
        $modalTitle = "آپلود فایل BOM";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "file";
        $items[$c]['id'] = "goodManagmentBOM_File";
        $items[$c]['helpText'] = "نوع فایل باید CSV باشد.";
        $items[$c]['title'] = "فایل BOM";
        $items[$c]['accept'] = "accept='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.csv'";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doUploadBOMListFile";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $uploadBOMListModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END UPLOAD BOM LIST MODAL +++++++++++++++++++++++++++++++++++++
        $htm .= $editCreateModal;
        $htm .= $addPieceToGoodsModal;
        $htm .= $addPiecesToGoodModal;
		$htm .= $showGoodPieces;
		$htm .= $showGoodInfo;
		$htm .= $delModal;
		$htm .= $uploadGMasterListModal;
		$htm .= $uploadBOMListModal;
        return $htm;
    }

    public function getGoodList($gName,$gCode,$parvaneh,$hCode,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $usl = "UPDATE `good` SET `uid`=0 WHERE `uid`={$_SESSION['userid']}";
        $db->Query($usl);

        $dsl = "DELETE FROM `multiple_search` WHERE `uid`={$_SESSION['userid']}";
        $db->Query($dsl);

        $query = "SELECT `changeDate` FROM `backup_good` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate = $ut->greg_to_jal($rst[0]['changeDate']);

        $query = "SELECT `changeDate` FROM `backup_interface` ORDER BY `RowID` DESC LIMIT 1";
        $rst = $db->ArrayQuery($query);
        $changeDate1 = $ut->greg_to_jal($rst[0]['changeDate']);

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
        if (intval($parvaneh) >= 0) {
            switch ($parvaneh) {
                case 0:
                    $x = 'شیرآلات بهداشتی اهرمی';
                    break;
                case 1:
                    $x = 'شیرآلات بهداشتی معمولی';
                    break;
                case 2:
                    $x = 'شیرآلات توپی';
                    break;
                case 3:
                    $x = 'شیلنگ و لوله لاستیکی ولکانیزه شده تقویت شده با فلز با لوازم و ملحقات';
                    break;
                case 4:
                    $x = 'قطعات و اتصالات لوله از جنس مس و آلیاژهای آن';
                    break;
                case 5:
                    $x = 'لوازم بهداشتی پلاستیکی ساختمان';
                    break;
                case 6:
                    $x = 'لوله از پلی اتیلن پنج لایه با فلز';
                    break;
            }
            $w[] = '`parvaneh`="'.$x.'" ';
        }
        $sql = "SELECT `RowID`,`gName`,`gCode`,`isEnable`,`HCode` FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
            $finalRes[$y]['HCode'] = $res[$y]['HCode'];
        }
        $sendParam = array($finalRes,$changeDate,$changeDate1);
        return $sendParam;
    }

    public function getMGoodList($gName,$gCode,$parvaneh,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood')){
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
        if (intval($parvaneh) >= 0) {
            switch ($parvaneh) {
                case 0:
                    $x = 'شیرآلات بهداشتی اهرمی';
                    break;
                case 1:
                    $x = 'شیرآلات بهداشتی معمولی';
                    break;
                case 2:
                    $x = 'شیرآلات توپی';
                    break;
                case 3:
                    $x = 'شیلنگ و لوله لاستیکی ولکانیزه شده تقویت شده با فلز با لوازم و ملحقات';
                    break;
                case 4:
                    $x = 'قطعات و اتصالات لوله از جنس مس و آلیاژهای آن';
                    break;
                case 5:
                    $x = 'لوازم بهداشتی پلاستیکی ساختمان';
                    break;
                case 6:
                    $x = 'لوله از پلی اتیلن پنج لایه با فلز';
                    break;
            }
            $w[] = '`parvaneh`="'.$x.'" ';
        }

        $sql = "SELECT `RowID` FROM `good` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql1 = "SELECT `RowID`,`gName`,`gCode`,`isEnable` FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql1 .= " WHERE ".$where;
            $query = $sql1;
        }
        $sql1 .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql1);

        $arr = array();
        $rst = $db->ArrayQuery($query);
        $cnt = count($rst);
        for ($i=0;$i<$cnt;$i++){
            $arr[] = $rst[$i]['RowID'];
        }
        $arr = implode(',',$arr);
        $q = "UPDATE `good` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
        $db->Query($q);

        $q = "UPDATE `good` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
        $db->Query($q);

        $q = "INSERT INTO `multiple_search` (`word`,`uid`) VALUES ('{$gName}',{$_SESSION['userid']})";
        $db->Query($q);

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
        }
        return $finalRes;
    }

    public function getMPageGoodList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();

        $sql = "SELECT `RowID` FROM `good` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql1 = "SELECT `RowID`,`gName`,`gCode`,`isEnable` FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql1 .= " WHERE ".$where;
            $query = $sql1;
        }
        $sql1 .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql1);

        $arr = array();
        $rst = $db->ArrayQuery($query);
        $cnt = count($rst);
        for ($i=0;$i<$cnt;$i++){
            $arr[] = $rst[$i]['RowID'];
        }
        $arr = implode(',',$arr);
        $q = "UPDATE `good` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
        $db->Query($q);

        $q = "UPDATE `good` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
        $db->Query($q);

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
        }
        return $finalRes;
    }

    public function getBMGoodList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sql = "DELETE FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` DESC LIMIT 1";
        $db->Query($sql);

        $sql1 = "SELECT `word` FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` ASC";
        $rst = $db->ArrayQuery($sql1);
        $cntrst = count($rst);

        $sql2 = "UPDATE `good` SET `uid`=0 WHERE `uid`={$_SESSION['userid']}";
        $db->Query($sql2);

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        for ($i=0;$i<$cntrst;$i++){
            $w = array();
            $w[] = '`gName` LIKE "%'.$rst[$i]['word'].'%" ';

            $sql3 = "SELECT `RowID` FROM `good` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
            $res = $db->ArrayQuery($sql3);
            if (count($res) > 0){
                $w[] = '`uid`='.$_SESSION['userid'].' ';
            }

            $sql4 = "SELECT `RowID`,`gName`,`gCode`,`isEnable` FROM `good`";
            if(count($w)){
                $where = implode(" AND ",$w);
                $sql4 .= " WHERE ".$where;
                $query = $sql4;
            }
            $sql4 .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
            $res = $db->ArrayQuery($sql4);

            $arr = array();
            $rarr = $db->ArrayQuery($query);
            $cnt = count($rarr);
            for ($j=0;$j<$cnt;$j++){
                $arr[] = $rarr[$j]['RowID'];
            }
            $arr = implode(',',$arr);
            $q = "UPDATE `good` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
            $db->Query($q);

            $q = "UPDATE `good` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
            $db->Query($q);
        }

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
        }
        return $finalRes;
    }

    public function getBMPageGoodList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sql1 = "SELECT `word` FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` ASC";
        $rst = $db->ArrayQuery($sql1);
        $cntrst = count($rst);

        $sql2 = "UPDATE `good` SET `uid`=0 WHERE `uid`={$_SESSION['userid']}";
        $db->Query($sql2);

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        for ($i=0;$i<$cntrst;$i++){
            $w = array();
            $w[] = '`gName` LIKE "%'.$rst[$i]['word'].'%" ';

            $sql3 = "SELECT `RowID` FROM `good` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
            $res = $db->ArrayQuery($sql3);
            if (count($res) > 0){
                $w[] = '`uid`='.$_SESSION['userid'].' ';
            }

            $sql4 = "SELECT `RowID`,`gName`,`gCode`,`isEnable` FROM `good`";
            if(count($w)){
                $where = implode(" AND ",$w);
                $sql4 .= " WHERE ".$where;
                $query = $sql4;
            }
            $sql4 .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
            $res = $db->ArrayQuery($sql4);

            $arr = array();
            $rarr = $db->ArrayQuery($query);
            $cnt = count($rarr);
            for ($j=0;$j<$cnt;$j++){
                $arr[] = $rarr[$j]['RowID'];
            }
            $arr = implode(',',$arr);
            $q = "UPDATE `good` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
            $db->Query($q);

            $q = "UPDATE `good` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
            $db->Query($q);
        }

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['gCode'] = $res[$y]['gCode'];
            $finalRes[$y]['gName'] = $res[$y]['gName'];
        }
        return $finalRes;
    }

    public function getBMGoodListCountRows(){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sql1 = "SELECT `word` FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` ASC";
        $rst = $db->ArrayQuery($sql1);
        $cntrst = count($rst);

        for ($i=0;$i<$cntrst;$i++){
            $w = array();
            $w[] = '`gName` LIKE "%'.$rst[$i]['word'].'%" ';

            $sql3 = "SELECT `RowID` FROM `good` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
            $res = $db->ArrayQuery($sql3);
            if (count($res) > 0){
                $w[] = '`uid`='.$_SESSION['userid'].' ';
            }

            $sql4 = "SELECT `RowID`,`gName`,`gCode`,`isEnable` FROM `good`";
            if(count($w)){
                $where = implode(" AND ",$w);
                $sql4 .= " WHERE ".$where;
            }
            $res = $db->ArrayQuery($sql4);
        }
        return count($res);
    }

    public function getMGoodListCountRows($gName,$gCode,$parvaneh){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood')){
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
        if (intval($parvaneh) >= 0) {
            switch ($parvaneh) {
                case 0:
                    $x = 'شیرآلات بهداشتی اهرمی';
                    break;
                case 1:
                    $x = 'شیرآلات بهداشتی معمولی';
                    break;
                case 2:
                    $x = 'شیرآلات توپی';
                    break;
                case 3:
                    $x = 'شیلنگ و لوله لاستیکی ولکانیزه شده تقویت شده با فلز با لوازم و ملحقات';
                    break;
                case 4:
                    $x = 'قطعات و اتصالات لوله از جنس مس و آلیاژهای آن';
                    break;
                case 5:
                    $x = 'لوازم بهداشتی پلاستیکی ساختمان';
                    break;
                case 6:
                    $x = 'لوله از پلی اتیلن پنج لایه با فلز';
                    break;
            }
            $w[] = '`parvaneh`="'.$x.'" ';
        }

        $sql = "SELECT `RowID` FROM `good` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql1 = "SELECT `RowID`,`gName`,`gCode`,`isEnable` FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql1 .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql1);
        return count($res);
    }

    public function getGoodListCountRows($gName,$gCode,$parvaneh){
        $db = new DBi();
        $w = array();
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`gCode` LIKE "%'.$gCode.'%" ';
        }
        if (intval($parvaneh) >= 0) {
            switch (intval($parvaneh)) {
                case 0:
                    $x = 'شیرآلات بهداشتی اهرمی';
                    break;
                case 1:
                    $x = 'شیرآلات بهداشتی معمولی';
                    break;
                case 2:
                    $x = 'شیرآلات توپی';
                    break;
                case 3:
                    $x = 'شیلنگ و لوله لاستیکی ولکانیزه شده تقویت شده با فلز با لوازم و ملحقات';
                    break;
                case 4:
                    $x = 'قطعات و اتصالات لوله از جنس مس و آلیاژهای آن';
                    break;
                case 5:
                    $x = 'لوازم بهداشتی پلاستیکی ساختمان';
                    break;
                case 6:
                    $x = 'لوله از پلی اتیلن پنج لایه با فلز';
                    break;
            }
            $w[] = '`parvaneh`="'.$x.'" ';
        }
        $sql = "SELECT `RowID` FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function goodInfo($gid){
        $db = new DBi();
        $sql = "SELECT `gCode`,`gName` FROM `good` WHERE `RowID`=".$gid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("gid"=>$gid,
                "gCode"=>$res[0]['gCode'],
                "gName"=>$res[0]['gName']
            );
            return $res;
        }else{
            return false;
        }
    }

    public function createGood($Gname,$Gcode,$similar){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        mysqli_autocommit($db->Getcon(),FALSE);

        $sql = "SELECT MAX(Col) AS `Mcol` FROM `good`";
        $result = $db->ArrayQuery($sql);
        $Col = intval($result[0]['Mcol'])+1;

        if (strlen(trim($similar)) > 0){
            $query = "SELECT `gCode` FROM `good` WHERE `gName`='{$similar}'";
            $rst = $db->ArrayQuery($query);
            if (count($rst) > 0){
                $query1 = "SELECT `PieceCode`,`amount`,`col_row` FROM `interface` WHERE `ProductCode`='{$rst[0]['gCode']}'";
                $rst1 = $db->ArrayQuery($query1);
                if (count($rst1) > 0){
                    $cnt = count($rst1);
                    for ($i=0;$i<$cnt;$i++){
                        $row = array();
                        $row = explode(',',$rst1[$i]['col_row']);
                        $ColRow = $Col.','.$row[1];
                        $insertValue[] = '("'.$Gcode.'","'.$rst1[$i]['PieceCode'].'",'.$rst1[$i]['amount'].',"'.$ColRow.'")';
                    }
                    $insertValue = implode(',',$insertValue);
                    $sqlBP = "INSERT INTO `interface` (`ProductCode`,`PieceCode`,`amount`,`col_row`) VALUES ".$insertValue." ";
                    $resBP = $db->Query($sqlBP);
                    if (intval($resBP) <= 0){
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        $sql1 = "INSERT INTO `good` (`gCode`,`gName`,`Col`) VALUES ('{$Gcode}','{$Gname}',{$Col})";
        $res = $db->Query($sql1);
        if (intval($res) > 0) {
            mysqli_commit($db->Getcon());
            return true;
        }else{
            mysqli_rollback($db->Getcon());
            return false;
        }
    }

    public function editGood($gid,$Gname,$Gcode){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `good` SET `gCode`='{$Gcode}',`gName`='{$Gname}' WHERE `RowID`=".$gid;
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = ((intval($res) == -1 || $res == 0) ? 0 : 1);
        if (intval($res)){
            return true;
        }else{
            return false;
        }
    }

    public function getMasterListGoodExcel($gName,$gCode,$parvaneh){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood')){
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
        $sq = "SELECT `RowID` FROM `good` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
        $res = $db->ArrayQuery($sq);
        if (count($res) > 0){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }
        if (intval($parvaneh) >= 0) {
            switch ($parvaneh) {
                case 0:
                    $x = 'شیرآلات بهداشتی اهرمی';
                    break;
                case 1:
                    $x = 'شیرآلات بهداشتی معمولی';
                    break;
                case 2:
                    $x = 'شیرآلات توپی';
                    break;
                case 3:
                    $x = 'شیلنگ و لوله لاستیکی ولکانیزه شده تقویت شده با فلز با لوازم و ملحقات';
                    break;
                case 4:
                    $x = 'قطعات و اتصالات لوله از جنس مس و آلیاژهای آن';
                    break;
                case 5:
                    $x = 'لوازم بهداشتی پلاستیکی ساختمان';
                    break;
                case 6:
                    $x = 'لوله از پلی اتیلن پنج لایه با فلز';
                    break;
            }
            $w[] = '`parvaneh`="'.$x.'" ';
        }
        $sql = "SELECT * FROM `good`";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function getGoodOtherInfo($gid){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `HCode`,`LatinTitle`,`brand`,`ggroup`,`gsgroup`,`Series`,`category`,`color`,`MCartoon`,
                       `BCartoon`,`ABCartoon`,`AMCartoon`,`parvaneh`,`gWeight`,`gmWeight`,`gdescription`
                        FROM `good` WHERE `RowID`=".$gid;
        $res = $db->ArrayQuery($sql);
        $infoNames = array('کد همکاران','عنوان لاتین','برند محصول','گروه محصول','زیرگروه محصول','سری محصول','نوع محصول','پوشش محصول','کارتن مادر',
                           'کارتن بچه','تعداد محصول در کارتن بچه','تعداد کارتن بچه در کارتن مادر','پروانه بهره برداری','وزن کارتن مادر انبار (کیلوگرم)','وزن کارتن مادر BOM (کیلوگرم)','توضیحات'
        );
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoPiece-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">سایر اطلاعات</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مقدار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<16;$i++){
            $iterator++;
            $keyName = key($res[0]);
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]["$keyName"].'</td>';
            $htm .= '</tr>';
            next($res[0]);
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

	public function showGoodPieces($gid){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood') && !$acm->hasAccess('salesPriceGoodsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `gCode` FROM `good` WHERE `RowID`=".$gid;
        $res = $db->ArrayQuery($sql);
        $sql1 = "SELECT `pCode`,`pName`,`pUnit`,`amount`,`isEnable` FROM `piece`
                 INNER JOIN `interface` ON (`piece`.`pCode`=`interface`.`PieceCode` AND `interface`.`ProductCode`='{$res[0]['gCode']}')";
        $res1 = $db->ArrayQuery($sql1);
        $CountRes = count($res1);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-responsive table-sm" style="display: inline-table;" id="goodPieces-tableID">';
        $htm .= '<thead>';
        if($acm->hasAccess('engineeringAccess') || $acm->hasAccess('procurementAccess')) {
            $htm .= '<tr class="bg-info">';
            $htm .= "<td style='display: none;'>&nbsp;</td>";
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">کد قطعه</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 50%;">نام قطعه</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;"> واحد</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;"> مقدار</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;"> فعال/غیرفعال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;"> حذف</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';
            for ($i = 0; $i < $CountRes; $i++) {
                $iterator++;
                $pCN = "'" . $res1[$i]['pCode'] . "','" . $res[0]['gCode'] . "','" . $res1[$i]['isEnable'] . "','" . $gid . "'";
                $pCN1 = "'" . $res1[$i]['pCode'] . "','" . $res[0]['gCode'] . "','" . $gid . "'";
                $htm .= '<tr class="table-secondary">';
                $htm .= "<td style='display: none;' ><input type='checkbox' rid='" . $iterator . "' checked disabled>&nbsp;</td>";
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;">' . $res1[$i]['pCode'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;">' . $res1[$i]['pName'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;">' . $res1[$i]['pUnit'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;"><input type="text" style="text-align: center;" class="form-control" id="pieceCoefficient-' . $iterator . '" value="' . $res1[$i]['amount'] . '" /><input type="hidden" id="pCode-' . $iterator . '-Hidden" value="' . $res1[$i]['pCode'] . '" /></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;"><button class="btn btn-' . (intval($res1[$i]['isEnable']) == 1 ? 'success' : 'danger') . '" onclick="activeInactivePieceOfGood(' . $pCN . ')"><i class="fas ' . (intval($res1[$i]['isEnable']) == 1 ? 'fa-unlock' : 'fa-lock') . '"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;"><button class="btn btn-danger" onclick="deletePieceOfGood(' . $pCN1 . ')"><i class="fas fa-trash-alt"></i></button></td>';
                $htm .= '</tr>';
            }
            $htm .= '<tr class="table-primary">';
            $htm .= '<td style="text-align: center;font-family: dubai-bold;" colspan="6">تعداد قطعات : ' . $CountRes . '</td>';
            $htm .= '</tr>';
            $htm .= '<input type="hidden" id="goodManage-HiddenID" value="' . $res[0]['gCode'] . '" />';
            $htm .= '<input type="hidden" id="goodManage-HiddenGCode" />';
            $htm .= '<input type="hidden" id="goodManage-HiddenPCode" />';
            $htm .= '<input type="hidden" id="goodManage-HiddenGoodID" />';
            $htm .= '</tbody>';
        }else{  // دسترسی به جز مهندسی و تدارکات
            $htm .= '<tr class="bg-info">';
            $htm .= "<td style='display: none;'>&nbsp;</td>";
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">کد قطعه</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 70%;">نام قطعه</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;"> واحد</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;"> مقدار</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';
            for ($i = 0; $i < $CountRes; $i++) {
                $iterator++;
                $htm .= '<tr class="table-secondary">';
                $htm .= "<td style='display: none;' ><input type='checkbox' rid='" . $iterator . "' checked disabled>&nbsp;</td>";
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;">' . $res1[$i]['pCode'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;">' . $res1[$i]['pName'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;">' . $res1[$i]['pUnit'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;"><input type="text" style="text-align: center;" class="form-control" id="pieceCoefficient-' . $iterator . '" value="' . $res1[$i]['amount'] . '" /><input type="hidden" id="pCode-' . $iterator . '-Hidden" value="' . $res1[$i]['pCode'] . '" /></td>';
                $htm .= '</tr>';
            }
            $htm .= '<tr class="table-primary">';
            $htm .= '<td style="text-align: center;font-family: dubai-bold;" colspan="6">تعداد قطعات : ' . $CountRes . '</td>';
            $htm .= '</tr>';
            $htm .= '</tbody>';
        }
        $htm .= '</table>';
        return $htm;
    }

/*    public function activeInactiveGoodBom($gid){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $query = "SELECT `isEnable` FROM `good` WHERE `RowID`={$gid}";
        $res = $db->ArrayQuery($query);
        $isEnable = (intval($res[0]['isEnable']) == 1 ? 0 : 1);
        $sql = "UPDATE `good` SET `isEnable`={$isEnable} WHERE `RowID`={$gid}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = ((intval($res) == -1 || $res == 0) ? 0 : 1);
        if (intval($res)){
            return true;
        }else{
            return false;
        }
    }*/

	public function activeInactivePieceOfGood($pCode,$gCode,$isEnable){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $isEnable = (intval($isEnable) == 1 ? 0 : 1);
        $db = new DBi();
        $sql = "UPDATE `interface` SET `isEnable`={$isEnable} WHERE `ProductCode`='{$gCode}' AND `PieceCode`='{$pCode}'";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = ((intval($res) == -1 || $res == 0) ? 0 : 1);
        if (intval($res)){
            return true;
        }else{
            return false;
        }
    }

    public function deletePieceOfGood($pCode,$gCode){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "DELETE FROM `interface` WHERE `ProductCode`='{$gCode}' AND `PieceCode`='{$pCode}'";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if (intval($res)){
            return true;
        }else{
            return false;
        }
    }

    public function getGoods(){
        $db = new DBi();
        $sql = "SELECT `gName`,`RowID` FROM `good` ";
        $res = $db->ArrayQuery($sql);
        if(count($res)>0){
            return $res;
        }else{
            return false;
        }
    }

    public function addPieceToGoods($pName,$gNames,$Coefficient){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `pCode`,`Row` FROM `piece` WHERE `pName`='{$pName}'";
        $res = $db->ArrayQuery($sql);
        if (count($res) == 1){
            $flag = 1;
            $gNames = explode(',',$gNames);
            $countGNames = count($gNames);
            for ($i=0;$i<$countGNames;$i++){
                $query = "SELECT `gCode`,`Col` FROM `good` WHERE `gName`='{$gNames[$i]}'";
                $result = $db->ArrayQuery($query);
                if (count($result) == 1){
                    $query1 = "SELECT `RowID` FROM `interface` WHERE `ProductCode`='{$result[0]['gCode']}' AND `PieceCode`='{$res[0]['pCode']}'";
                    $rst = $db->ArrayQuery($query1);
                    if (count($rst) <= 0){
                        $ColRow = $result[0]['Col'].','.$res[0]['Row'];
                        $insertValue[] = '("'.$result[0]['gCode'].'","'.$res[0]['pCode'].'",'.$Coefficient.',"'.$ColRow.'")';
                    }else{
                        $flag = -1;
                    }
                }else{
                    $flag = -2;
                }
            } // End for();

            if (intval($flag) == 1){
                $insertValue = array_unique($insertValue);
                $insertValue = implode(',',$insertValue);
                $sqlBP = "INSERT INTO `interface` (`ProductCode`,`PieceCode`,`amount`,`col_row`) VALUES ".$insertValue." ";
                $resBP = $db->Query($sqlBP);
                if (intval($resBP) > 0){
                    return true;
                }else{
                    return false;
                }
            }else{
                return $flag;
            }
        }else{
            return false;
        }
    }

    public function editPieceToGoods($pName,$gNames,$Coefficient){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `pCode` FROM `piece` WHERE `pName`='{$pName}'";
        $res = $db->ArrayQuery($sql);
        if (count($res) == 1){
            $flag = 1;
            $gNames = explode(',',$gNames);
            $countGNames = count($gNames);
            for ($i=0;$i<$countGNames;$i++){
                $query = "SELECT `gCode` FROM `good` WHERE `gName`='{$gNames[$i]}'";
                $result = $db->ArrayQuery($query);
                if (count($result) == 1){
                    $query1 = "SELECT `RowID` FROM `interface` WHERE `ProductCode`='{$result[0]['gCode']}' AND `PieceCode`='{$res[0]['pCode']}'";
                    $rst = $db->ArrayQuery($query1);
                    if (count($rst) == 1){
                        $Rids[] = $rst[0]['RowID'];
                    }else{
                        $flag = -1;  // همچین قطعه و محصولی وجود ندارد!!!
                    }
                }else{
                    $flag = -2;
                }
            } // End for();

            if (intval($flag) == 1){
                $Rids = array_unique($Rids);
                $Rids = implode(',',$Rids);
                $sqlBP = "UPDATE `interface` SET `amount`={$Coefficient} WHERE `RowID` IN ({$Rids})";
                $db->Query($sqlBP);
                $res1 = $db->AffectedRows();
                $res1 = ((intval($res1) == -1) ? 0 : 1);
                if (intval($res1)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return $flag;
            }
        }else{
            return false;
        }
    }

    public function addPiecesToGood($gid,$PiecesCoefficient){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `gCode`,`Col` FROM `good` WHERE `RowID`={$gid}";
        $res = $db->ArrayQuery($sql);
        if (count($res) == 1){
            $PiecesCoefficient = explode(',',$PiecesCoefficient);
            $countPC = count($PiecesCoefficient)/2;
            if((count($PiecesCoefficient)) % 2){
                return -3;
            }else{
                $flag = 1;
                $x = 0;
                $z = 1;
                for ($i=0;$i<$countPC;$i++){
                    $query = "SELECT `pCode`,`Row` FROM `piece` WHERE `pName`='{$PiecesCoefficient[$x]}'";
                    $result = $db->ArrayQuery($query);
                    if (count($result) == 1){
                        $query1 = "SELECT `RowID` FROM `interface` WHERE `ProductCode`='{$res[0]['gCode']}' AND `PieceCode`='{$result[0]['pCode']}'";
                        $rst = $db->ArrayQuery($query1);
                        if (count($rst) <= 0){
                            $ColRow = $res[0]['Col'].','.$result[0]['Row'];
                            $insertValue[] = '("'.$res[0]['gCode'].'","'.$result[0]['pCode'].'",'.$PiecesCoefficient[$z].',"'.$ColRow.'")';
                        }else{
                            $flag = -1;
                        }
                    }else{
                        $flag = -2;
                    }
                    $x += 2;
                    $z += 2;
                }  // End for

                if (intval($flag) == 1){
                    $insertValue = array_unique($insertValue);
                    $insertValue = implode(',',$insertValue);
                    $sqlBP = "INSERT INTO `interface` (`ProductCode`,`PieceCode`,`amount`,`col_row`) VALUES ".$insertValue." ";
                    $resBP = $db->Query($sqlBP);
                    if (intval($resBP) > 0){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return $flag;
                }
            }
        }else{
            return false;
        }
    }

    public function editCreateCoefficientPiece($myJsonString,$gCode){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood') || !$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $countJS = count($myJsonString);
        $flag = true;
        $db = new DBi();
        mysqli_autocommit($db->Getcon(),FALSE);

        for($i=0;$i<$countJS;$i++){
            $Coefficient = $myJsonString[$i][0];  // ضریب قطعه
            $pCode = $myJsonString[$i][1];  // کد قطعه
            $sql = "UPDATE `interface` SET `amount`={$Coefficient} WHERE `PieceCode`='{$pCode}' AND `ProductCode`='{$gCode}'";
            $db->Query($sql);
            $res1 = $db->AffectedRows();
            $res1 = (intval($res1) == -1 ? 0 : 1);
            if(!intval($res1)){
                $flag = false;
            }
        }
        if($flag){
            mysqli_commit($db->Getcon());
            return true;
        }else{
            mysqli_rollback($db->Getcon());
            return false;
        }
    }

    public function uploadGMListFile($gmlist){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)).'/pmlexcel/excGML.xlsx';
        unlink($file_to_delete);

        if (isset($gmlist) && intval($gmlist['size']) > 0) {
            $sql = "DROP TABLE IF EXISTS `gmasterlist`";
            $db->Query($sql);
            $gmlFile = "excGML.xlsx";
            $gmlist['name'] = $gmlFile;
            $upload = move_uploaded_file($gmlist["tmp_name"],'../pmlexcel/'.$gmlist["name"]);
            if(!$upload){
                return false;
            }else{
                $result = $this->createGoodMasterListDB();
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

    private function createGoodMasterListDB(){
        $inputFileName = '../pmlexcel/excGML.xlsx';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($inputFileName);

        $db = new DBi();
        $sql = "DROP TABLE IF EXISTS `gmasterlist`";
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
                $x = str_replace('"', '', $cell->getCalculatedValue());
                $y = str_replace("'", "", $x);
                if ($cell->getColumn() == 'C'){
                    if (strlen(trim($y)) == 0){
                        $y = '###';
                    }
                }
                $excell_array_data[$rowIndex][$cell->getColumn()] = $y;
            }
        }

        //Create Database table with one Field
        $sql = "CREATE TABLE `gmasterlist` (`RowID` INT NOT NULL PRIMARY KEY AUTO_INCREMENT)";
        $aff = $db->Query($sql);
        if (intval($aff) == -1 || intval($aff) == 0){
            $flag = false;
        }

        //Create Others Field (A, B, C & ...)
        $columns_name = $excell_array_data[$skip_rows+1];
        foreach (array_keys($columns_name) as $fieldname ){
            $sql1 = "ALTER TABLE `gmasterlist` ADD $fieldname VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin";
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
                //$insertValue[] = "('".join(array_values($chunk[$i][$j]), "','")."')";
                $insertValue[] = "('".implode("','",array_values($chunk[$i][$j]))."')";
            }
            $insertValue = implode(',',$insertValue);
            $sql2 = "INSERT INTO `gmasterlist` ($keys) VALUES ".$insertValue." ";
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

    public function uploadAgainGoodMasterList(){
        $acm = new acm();
        if(!$acm->hasAccess('manageGood')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        mysqli_autocommit($db->Getcon(),FALSE);

        $sqlbgt = "SELECT `RowID` FROM `budget` ORDER BY `RowID` DESC LIMIT 1";
        $rstbgt = $db->ArrayQuery($sqlbgt);

        $sqlbgt1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$rstbgt[0]['RowID']} AND `finalTick`=1";
        // $//ut->fileRecorder('sqlbgt1:');
        // $//ut->fileRecorder($sqlbgt1);
        $rstbgt1 = $db->ArrayQuery($sqlbgt1);

        if (count($rstbgt1) <= 0){
            $res = "ابتدا باید اجزای بودجه برای آخرین بودجه ثبت گردد !";
            $out = "false";
            response($res,$out);
            exit;
        }


        $sql = "SELECT * FROM `gmasterlist` WHERE `G`>0 ORDER BY `RowID` ASC ";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $flag = true;

        $qext = "SELECT `RowID` FROM `good`";
        $rqext = $db->ArrayQuery($qext);
        if (count($rqext) > 0){      // قبلا محصولات ثبت شده است
		//$//ut->fileRecorder('if1');
            $keys = array('gName','Series','ggroup','gsgroup','brand','color','HCode','MCartoon','BCartoon','ABCartoon','AMCartoon','gWeight','gmWeight','category');
            for ($i=0;$i<$cnt;$i++){
                $q = "SELECT `RowID`,`gName`,`Series`,`ggroup`,`gsgroup`,`brand`,`color`,`HCode`,`MCartoon`,`BCartoon`,`ABCartoon`,`AMCartoon`,`gWeight`,`gmWeight`,`category` FROM `good` WHERE `gCode`='{$res[$i]['A']}'";
                $rstq = $db->ArrayQuery($q);

                if (count($rstq) > 0) { // محصول قبلا وجود دارد
                    $fieldsString = array($res[$i]['C'], $res[$i]['K'], $res[$i]['I'], $res[$i]['J'],$res[$i]['H'], $res[$i]['M'], $res[$i]['B'], $res[$i]['P'], $res[$i]['Q'], $res[$i]['R'], $res[$i]['S'], $res[$i]['U'], $res[$i]['V'], $res[$i]['L']);
                    $cntFS = count($fieldsString);

                    $backupKeys = array();
                    $PreviousValue = array();
                    $currentValue = array();

                    for ($k = 0; $k < $cntFS; $k++) {  // بررسی فیلد های رشته ای
                        if (trim($rstq[0]["$keys[$k]"]) != trim($fieldsString[$k])) {
                            $backupKeys[] = $keys[$k];
                            $PreviousValue[] = $rstq[0]["$keys[$k]"];
                            $currentValue[] = $fieldsString[$k];
                        }
                    }

                    if (count($backupKeys) > 0) {  // اگر تغییری کرده بود
                        $sqlPU = "UPDATE `good` SET `gName`='{$res[$i]['C']}',`Series`='{$res[$i]['K']}',`ggroup`='{$res[$i]['I']}',`gsgroup`='{$res[$i]['J']}',`brand`='{$res[$i]['H']}',`color`='{$res[$i]['M']}',`HCode`='{$res[$i]['B']}',`MCartoon`='{$res[$i]['P']}',`BCartoon`='{$res[$i]['Q']}',`ABCartoon`='{$res[$i]['R']}',`AMCartoon`='{$res[$i]['S']}',`parvaneh`='{$res[$i]['T']}',`gWeight`='{$res[$i]['U']}',`gmWeight`='{$res[$i]['V']}',`category`='{$res[$i]['L']}',`proccessWay`='{$res[$i]['N']}',`cartridgeSize`='{$res[$i]['O']}' WHERE `RowID`={$rstq[0]['RowID']}";
                        $db->Query($sqlPU);
                        //$//ut->fileRecorder("sqlPU:".$sqlPU);
                        $resPU = $db->AffectedRows();
                        $resPU = ((intval($resPU) == -1) ? 0 : 1);
                        if (intval($resPU)) {
                            $countBackupKeys = count($backupKeys);
                            $insertValue = array();
                            for ($a=0;$a<$countBackupKeys;$a++){
                                $sqlSFA = "SELECT `FaName` FROM `en_to_fa` WHERE `EnName`='{$backupKeys[$a]}'";
                                $rst = $db->ArrayQuery($sqlSFA);
                                $insertValue[] = '('.$rstq[0]['RowID'].',"'.$backupKeys[$a].'","'.$rst[0]['FaName'].'","'.$currentValue[$a].'","'.$PreviousValue[$a].'","'.date('Y/m/d').'",'.$_SESSION['userid'].')';
                            }
                            $insertValue = implode(',',$insertValue);
                            $sqlBP = "INSERT INTO `backup_good` (`gid`,`fieldName`,`fieldName_Fa`,`currentValue`,`previousValue`,`changeDate`,`uid`) VALUES ".$insertValue." ";
								
							$resBP = $db->Query($sqlBP);

                            if (intval($resBP) < 0){
                               // $//ut->fileRecorder('sqlBP:'.$sqlBP);
                                $flag = false;
                            }
                        }else{
                           // $//ut->fileRecorder("sqlPU:".$sqlPU);
                            $flag = false;
                        }
                    }else{
                        continue;
                    }
                }else{  //++++++++++++++++++++ محصول قبلا وجود ندارد ++++++++++++++++++++
					////$//ut->fileRecorder('else1');
                    $qcol = "SELECT `Col` FROM `good` ORDER BY `RowID` DESC LIMIT 1";
                    $rcol = $db->ArrayQuery($qcol);
                    $col = $rcol[0]['Col']+1;

                    $query = "INSERT INTO `good` (`gCode`,`gName`,`Series`,`ggroup`,`gsgroup`,`brand`,`color`,`mapNumber`,`HCode`,`LatinTitle`,`MCartoon`,
                                                  `BCartoon`,`ABCartoon`,`AMCartoon`,`gdescription`,`parvaneh`,`gWeight`,`gmWeight`,`category`,`proccessWay`,`cartridgeSize`,`Col`)
                                                   VALUES ('{$res[$i]['A']}','{$res[$i]['C']}','{$res[$i]['K']}','{$res[$i]['I']}','{$res[$i]['J']}','{$res[$i]['H']}',
                                                           '{$res[$i]['M']}','{$res[$i]['D']}','{$res[$i]['B']}','{$res[$i]['E']}','{$res[$i]['P']}','{$res[$i]['Q']}',
                                                           '{$res[$i]['R']}','{$res[$i]['S']}','{$res[$i]['F']}','{$res[$i]['T']}','{$res[$i]['U']}','{$res[$i]['V']}',
                                                           '{$res[$i]['L']}','{$res[$i]['N']}','{$res[$i]['O']}',{$col})";
                    ////$//ut->fileRecorder('query22:'.$query);
					$result = $db->Query($query);
                    $id = intval($db->InsertrdID());
                    if (intval($result) <= 0) {
                       // $//ut->fileRecorder('query22:'.$query);
                        $flag = false;
                    }
					$check_exist="SELECT RowID from `budget_components_details` where `gCode`='{$res[$i]['A']}' AND `bcid`={$rstbgt1[0]['RowID']} ";
					$chk_res = $db->ArrayQuery($check_exist);
					
					if(count($chk_res)==0){
						
						$sqlbgt2 = "INSERT INTO `budget_components_details` (`bcid`,`goodID`,`gCode`,`gName`,`brand`,`ggroup`,`gsgroup`,`series`,`farvardin`,`ordibehesht`,`khordad`,
																			 `tir`,`mordad`,`shahrivar`,`mehr`,`aban`,`azar`,`dey`,`bahman`,`esfand`,`productionTick`,`planningTick`,
																			 `productionDescription`,`planningDescription`,`finalTick`,`farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,
																			 `tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,
																			 `esfandTotal`,`totalEntryNumber`,`HCode`,`parentID`)
																			  VALUES ({$rstbgt1[0]['RowID']},{$id},'{$res[$i]['A']}','{$res[$i]['C']}','{$res[$i]['H']}','{$res[$i]['I']}','{$res[$i]['J']}','{$res[$i]['K']}',0,0,0,
																			  0,0,0,0,0,0,0,0,0,1,1,'','',1,0,0,0,0,0,0,0,0,0,0,0,0,0,'{$res[$i]['B']}',0)";
					   //$//ut->fileRecorder('sqlbgt2:'.$sqlbgt2);
						$rstbgt2 = $db->Query($sqlbgt2);
						if (intval($rstbgt2) <= 0) {
                            $//ut->fileRecorder('sqlbgt2:'.$sqlbgt2);
							$flag = false;
						}
					}
                }
            } //FOR
        }else{       // هیچ محصولی ثبت نشده است
            for ($i=0;$i<$cnt;$i++){
                $col = $i+23;
                $query = "INSERT INTO `good` (`gCode`,`gName`,`Series`,`ggroup`,`gsgroup`,`brand`,`color`,`mapNumber`,`HCode`,`LatinTitle`,`MCartoon`,
                                                  `BCartoon`,`ABCartoon`,`AMCartoon`,`gdescription`,`parvaneh`,`gWeight`,`gmWeight`,`category`,`proccessWay`,`cartridgeSize`,`Col`)
                                                   VALUES ('{$res[$i]['A']}','{$res[$i]['C']}','{$res[$i]['K']}','{$res[$i]['I']}','{$res[$i]['J']}','{$res[$i]['H']}',
                                                           '{$res[$i]['M']}','{$res[$i]['D']}','{$res[$i]['B']}','{$res[$i]['E']}','{$res[$i]['P']}','{$res[$i]['Q']}',
                                                           '{$res[$i]['R']}','{$res[$i]['S']}','{$res[$i]['F']}','{$res[$i]['T']}','{$res[$i]['U']}','{$res[$i]['V']}',
                                                           '{$res[$i]['L']}','{$res[$i]['N']}','{$res[$i]['O']}',{$col})";
                // $//ut->fileRecorder('query2:'.$query);
				$result = $db->Query($query);
                if (intval($result) <= 0){
                    //$//ut->fileRecorder($query);
                    $flag = false;
                }
            }
        }

        if ($flag){
            $sql = "SELECT `RowID`,`brand`,`Series`,`color`,`category` FROM `good` WHERE `ggroup`='شیرآلات' AND `color`!='کروم'";
            $res = $db->ArrayQuery($sql);
            $cnt = count($res);

            for ($i=0;$i<$cnt;$i++){
                $query = "SELECT `RowID` FROM `good` WHERE `brand`='{$res[$i]['brand']}' AND `Series`='{$res[$i]['Series']}' AND `category`='{$res[$i]['category']}' AND `color`='کروم'";
                $rst = $db->ArrayQuery($query);
                $q = "UPDATE `good` SET `chCode`='{$rst[0]['RowID']}' WHERE `RowID`={$res[$i]['RowID']}";
                $db->Query($q);
            }
            mysqli_commit($db->Getcon());
            return true;
        }else{
            mysqli_rollback($db->Getcon());
			//$//ut->fileRecorder('EEEERRRRROOOOORRRRR:');
            return false;
        }
    }

    public function uploadBOMListFile($bomList){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)).'/pmlexcel/excBOM.csv';
        unlink($file_to_delete);

        if (isset($bomList) && intval($bomList['size']) > 0) {
            $sql = "DROP TABLE IF EXISTS `bomlist`";
            $db->Query($sql);
            $bomFile = "excBOM.csv";
            $bomList['name'] = $bomFile;
            $upload = move_uploaded_file($bomList["tmp_name"],'../pmlexcel/'.$bomList["name"]);
            if(!$upload){
                return false;
            }else{
                $result = $this->createBOMListDB();
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

    private function createBOMListDB(){
        $db = new DBi();
        $sql = "DROP TABLE IF EXISTS `bomlist`";
        $db->Query($sql);

        $flag = true;
        $sql = "CREATE TABLE `bomlist` (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT)";
        $aff = $db->Query($sql);
        if (intval($aff) <= 0){
            $flag = false;
        }

        //Create Others Field (A, B, C & ...)
        $columns_name = array(A,B,C);
        $cnt = count($columns_name);
        for ($i=0;$i<$cnt;$i++){
            $sql = "ALTER TABLE `bomlist` ADD $columns_name[$i] VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_bin";
            $aff1 = $db->Query($sql);
            if (intval($aff1) <= 0){
                $flag = false;
            }
        }

        $sql = "ALTER TABLE `bomlist` ADD UNIQUE(`A`,`B`)";
        $aff3 = $db->Query($sql);
        if (intval($aff3) <= 0){
            $flag = false;
        }

        $sql = "LOAD DATA LOCAL INFILE '../pmlexcel/excBOM.csv' INTO TABLE `bomlist`
            FIELDS TERMINATED BY ','
            LINES TERMINATED BY '\n'
            (A,B,C)";
        $aff2 = $db->Query($sql);
        if (intval($aff2) <= 0){
            $flag = false;
        }

        if ($flag){
            return true;
        }else{
            return false;
        }
    }

    public function uploadAgainBOMList(){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        mysqli_autocommit($db->Getcon(),FALSE);
        $flag = true;

        $sql = "SELECT * FROM `bomlist` ORDER BY `id` ASC ";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $sql1 = "SELECT `RowID`,`ProductCode`,`PieceCode`,`amount` FROM `interface` ORDER BY `RowID` ASC ";
        $rst = $db->ArrayQuery($sql1);
        $chunk = array_chunk($rst,100);

        $insertValue = array();
        if (count($rst) > 0){   // قبلا BOM ثبت شده است
            $rids = array();
            $ccnt = count($chunk);  //189
            for ($i=0;$i<$ccnt;$i++) {
                $cchnt = count($chunk[$i]);
                for ($j=0;$j<$cchnt;$j++){
                    $sql2 = "SELECT `id` FROM `bomlist` WHERE `A`='{$chunk[$i][$j]['ProductCode']}' AND `B`='{$chunk[$i][$j]['PieceCode']}'";
                    $result = $db->ArrayQuery($sql2);
                    if (count($result) == 0){
                        $insertValue[] = '("'.$chunk[$i][$j]['PieceCode'].'","'.$chunk[$i][$j]['ProductCode'].'","'.$chunk[$i][$j]['amount'].'","0","'.date('Y/m/d').'")';
                        $rids[] = $chunk[$i][$j]['RowID'];
                    }
                }
            }  // FOR

            $rids = implode(',',$rids);
            $qdel = "DELETE FROM `interface` WHERE `RowID` IN (".$rids.")";
            $afd = $db->Query($qdel);
            if (intval($afd) < 0) {
                $flag = false;
            }

            for ($i=0;$i<$cnt;$i++) {
                $sql3 = "SELECT `amount` FROM `interface` WHERE `ProductCode`='{$res[$i]['A']}' AND `PieceCode`='{$res[$i]['B']}'";
                $rqst = $db->ArrayQuery($sql3);
                if (count($rqst) > 0){  // قبلا در bom ثبت شده
                    if ( trim($rqst[0]['amount']) != trim($res[$i]['C']) ){
                        $qq = "UPDATE `interface` SET `amount`='{$res[$i]['C']}' WHERE `ProductCode`='{$res[$i]['A']}' AND `PieceCode`='{$res[$i]['B']}' ";
                        $aff = $db->Query($qq);
                        if (intval($aff) <= 0){
                            $flag = false;
                        }
                        $insertValue[] = '("'.$res[$i]['B'].'","'.$res[$i]['A'].'","'.$rqst[0]['amount'].'","'.$res[$i]['C'].'","'.date('Y/m/d').'")';
                    }
                }else{  // قبلا در bom ثبت نشده است.
                    $sql4 = "SELECT `Col` FROM `good` WHERE `gCode`='{$res[$i]['A']}'";
                    $rq = $db->ArrayQuery($sql4);
                    $sql5 = "SELECT `Row` FROM `piece` WHERE `pCode`='{$res[$i]['B']}'";
                    $rq1 = $db->ArrayQuery($sql5);
                    $colrow = $rq[0]['Col'].','.$rq1[0]['Row'];
                    $insertValue[] = '("'.$res[$i]['B'].'","'.$res[$i]['A'].'","0","'.$res[$i]['C'].'","'.date('Y/m/d').'")';
                    $sql6 = "INSERT INTO `interface` (`ProductCode`,`PieceCode`,`amount`,`col_row`) VALUES ('{$res[$i]['A']}','{$res[$i]['B']}',{$res[$i]['C']},'{$colrow}')";
                    $rqin = $db->Query($sql6);
                    if (intval($rqin) <= 0){
                        $flag = false;
                    }
                }
            }  // FOR

            if (count($insertValue) > 0) {
                $insertValue = implode(',', $insertValue);
                $sql7 = "INSERT INTO `backup_interface` (`pCode`,`gCode`,`pValue`,`cValue`,`changeDate`) VALUES " . $insertValue . " ";
                $rqqq = $db->Query($sql7);
                if (intval($rqqq) <= 0) {
                    $flag = false;
                }
            }

        }
        else{       // قبلا BOM ثبت نشده است
            for ($i=0;$i<$cnt;$i++) {
                $query = "SELECT `Col` FROM `good` WHERE `gCode`='{$res[$i]['A']}'";
                $rq = $db->ArrayQuery($query);
                $query1 = "SELECT `Row` FROM `piece` WHERE `pCode`='{$res[$i]['B']}'";
                $rq1 = $db->ArrayQuery($query1);
                $colrow = $rq[0]['Col'].','.$rq1[0]['Row'];
                $q = "INSERT INTO `interface` (`ProductCode`,`PieceCode`,`amount`,`col_row`) VALUES ('{$res[$i]['A']}','{$res[$i]['B']}',{$res[$i]['C']},'{$colrow}')";
                $result = $db->Query($q);
                if (intval($result) <= 0){
                    //$//ut->fileRecorder($q);
                    $flag = false;
                }
            }  // FOR
        }

        if ($flag){
            mysqli_commit($db->Getcon());
            return true;
        }else{
            mysqli_rollback($db->Getcon());
            return false;
        }
    }

    public function getGoodsForGeneralExcelBOM(){
        $db=new DBi();
        $ut=new Utility();
        $get_goods_bom="SELECT g.HCode as g_code,g.gName,p.pName,pm.HPCode as p_code,pm.Weight_Final,pm.RawMaterialCode,pm.material,pm.first_stage_construction ,
                            g.ggroup,p.pUnit,i.amount ,m.M,m.N,m.K,m.P,m.F,m.D,pm.Custom_dimensions,m.A,m.U,m.V,m.W,m.S,pm.Weight_materials,pm.System_weight,pm.Weight_Machining
                        FROM interface AS i LEFT JOIN good AS g ON i.ProductCode=g.gCode 
                            LEFT JOIN piece AS p ON i.PieceCode=p.pCode
                            LEFT JOIN piece_masterlist AS pm ON p.RowID=pm.RowID 
                            LEFT JOIN masterlist AS m ON m.A=p.pCode
                            LEFT JOIN `gmasterlist` as gm ON gm.A=g.gCode
                        WHERE i.isEnable=1 AND gm.G=2 AND g.HCode <>'' AND m.M NOT IN (13,0) AND pm.HPCode <>'' ";
        $res=$db->ArrayQuery($get_goods_bom); 
       
        // foreach($res as $key=>$value){
        //     if($value['N']==1 || $value['N'==2]){}
        //     else{
        //         $res[$key]['p_code']=$value['RawMaterialCode'];
        //         if(!empty($value['Weight_Final']))
        //             $res[$key]['amount']=$value['Weight_Final'];
        //     }
        // }
 
        // $get_goods="SELECT ProductCode,PieceCode,amount FROM interface WHERE isEnable =1";
        // $res=$db->ArrayQuery($get_goods);
        // foreach($res as $key=>$value){
        //    $sql2="SELECT HCode as g_code,gName ,ggroup from good where gCode '{$value['ProductCode']}'";
        //    $res2=$db->ArrayQuery($sql2);
        //    $value['g_code']=$res2[0]['g_code'];
        //    $value['gName']=$res2[0]['gName'];
        //    $value['ggroup']=$res2[0]['ggroup'];
        //    $res[$k]=$value;
        
        //    //---------------------------------------------------^^^^^--1--------
        //    $sql3="SELECT pName,RowID  from piece where gCode= '{$value['ProductCode']}'";
        //    $res3=$db->ArrayQuery($sql2);
        //    $value['pName']=$res2[0]['pName'];
        //    $value['p_RowID']=$res2[0]['RowID'];
        //    $res[$k]=$value;
        //     //---------------------------------------------------^^^^^--2------
        //     $sql4="SELECT HPCode,Weight_Final,RawMaterialCode,material,first_stage_construction   from piece_masterlist where pid= '{$value['p_RowID']}'";
        //     $res3=$db->ArrayQuery($sql2);
        //     $value['pName']=$res2[0]['pName'];
        //     $res[$k]=$value;
        //      //---------------------------------------------------^^^^^--2------
        // }
        return $res;


    }
}