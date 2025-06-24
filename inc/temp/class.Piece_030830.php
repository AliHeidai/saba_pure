<?php
/**
 * Created by PhpStorm.
 * User: Majid
 * Date: 12/6/2018
 * Time: 8:18 AM
 */

class Piece{

    public function __construct(){
        // do nothing
    }

    public function getManagePieceHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $currency = new Currency();
        $AllCurrency = $currency->getAllCurrency();
        $cnt = count($AllCurrency);

        $pagename = "مدیریت قطعات";
        $pageIcon = "fa-puzzle-piece";
        $contentId = "pieceManageBody";

        $bottons = array();
        $c = 0;

        if($acm->hasAccess('engineeringAccess') && !$acm->hasAccess('procurementAccess')) {  // فقط دسترسی مهندسی
            $bottons[$c]['title'] = "افزودن قطعه جدید";
            $bottons[$c]['jsf'] = "createPiece";
            $bottons[$c]['icon'] = "fa-plus-square";
            $c++;

            if($acm->hasAccess('excelexport')) {
                $bottons[$c]['title'] = "خروجی اکسل";
                $bottons[$c]['jsf'] = "createMasterListPieceExcel";
                $bottons[$c]['icon'] = "fa-file-excel";
                $c++;
            }

            $bottons[$c]['title'] = "آپلود قطعات";
            $bottons[$c]['jsf'] = "uploadPieceMasterList";
            $bottons[$c]['icon'] = "fa-upload";
            $bottons[$c]['id'] = 'id="uploadPieceMasterListID"';
            $c++;

            $bottons[$c]['title'] = "بارگذاری مجدد قطعات";
            $bottons[$c]['jsf'] = "uploadAgainPieceMasterList";
            $bottons[$c]['icon'] = "fa-plus-square";
            $bottons[$c]['id'] = 'id="uploadAgainPieceMasterListID"';
            $c++;
        }

        $bottons[$c]['title'] = "جستجو بر اساس محصول";
        $bottons[$c]['jsf'] = "pieceManageGoodSearch";
        $bottons[$c]['icon'] = "fa-search";
        $c++;

        $bottons[$c]['title'] = "جستجو بر اساس قطعه";
        $bottons[$c]['jsf'] = "pieceManagePieceSearch";
        $bottons[$c]['icon'] = "fa-search";

        $headerSearch = array();
        $headerSearch[0]['type'] = "text";
        $headerSearch[0]['width'] = "150px";
        $headerSearch[0]['id'] = "pieceManageMPnameSearch";
        $headerSearch[0]['title'] = "قسمتی از نام قطعه";
        $headerSearch[0]['placeholder'] = "قسمتی از نام قطعه";

        $headerSearch[1]['type'] = "btn";
        $headerSearch[1]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
        $headerSearch[1]['jsf'] = "showMPieceManageList";

        $headerSearch[2]['type'] = "btn";
        $headerSearch[2]['title'] = "مرحله قبل&nbsp;&nbsp;<i class='fa fa-redo-alt'></i>";
        $headerSearch[2]['jsf'] = "showBMPieceManageList";

        $headerSearch[3]['type'] = "btn";
        $headerSearch[3]['title'] = "پاک سازی فیلترها&nbsp;&nbsp;<i class='fa fa-redo-alt'></i>";
        $headerSearch[3]['jsf'] = "emptyPieceManageSearchFilters";

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        //++++++++++++++++++++++++++++++++++EDIT CREATE MODAL++++++++++++++++++++++++++++++++
        $modalID = "pieceManagmentModal";
        $modalTitle = "فرم ایجاد/ویرایش قطعات";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPname";
        $items[$c]['asterisk'] = "required";
        $items[$c]['title'] = "نام قطعه";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPcode";
        $items[$c]['asterisk'] = "required";
        $items[$c]['title'] = "کد قطعه";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPunit";
        $items[$c]['asterisk'] = "required";
        $items[$c]['title'] = "واحد قطعه";
        $items[$c]['placeholder'] = "واحد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentCollection_name";
        $items[$c]['title'] = "نام مجموعه";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentCollection_Subname";
        $items[$c]['title'] = "نام زیر مجموعه";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentMaterial";
        $items[$c]['title'] = "جنس";
        $items[$c]['placeholder'] = "جنس";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentLatin_title";
        $items[$c]['title'] = "نام لاتین";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "pieceManagmentHow_supply";
        $items[$c]['title'] = "روش محاسبات";
        $items[$c]['asterisk'] = "required";
        $items[$c]['onchange'] = "onchange=changeViewField()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'روش انجام محاسبات';
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = 'راکد';
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = 'وارداتی';
        $items[$c]['options'][2]["value"] = 1;
        $items[$c]['options'][3]["title"] = 'خرید داخلی';
        $items[$c]['options'][3]["value"] = 2;
        $items[$c]['options'][4]["title"] = 'خرید قطعه ماشینکاری';
        $items[$c]['options'][4]["value"] = 3;
        $items[$c]['options'][5]["title"] = 'خرید قطعه ریخته گری';
        $items[$c]['options'][5]["value"] = 4;
        $items[$c]['options'][6]["title"] = 'تولید ریخته گری';
        $items[$c]['options'][6]["value"] = 5;
        $items[$c]['options'][7]["title"] = 'تولید ماشینکاری';
        $items[$c]['options'][7]["value"] = 6;
        $items[$c]['options'][8]["title"] = 'فورج';
        $items[$c]['options'][8]["value"] = 7;
        $items[$c]['options'][9]["title"] = 'تزریق پلاستیک';
        $items[$c]['options'][9]["value"] = 8;
        $items[$c]['options'][10]["title"] = 'لوله';
        $items[$c]['options'][10]["value"] = 9;
        $items[$c]['options'][11]["title"] = 'شیلنگ';
        $items[$c]['options'][11]["value"] = 10;
        $items[$c]['options'][12]["title"] = 'برش لیزر';
        $items[$c]['options'][12]["value"] = 11;
        $items[$c]['options'][13]["title"] = 'کلکتور';
        $items[$c]['options'][13]["value"] = 12;
        $items[$c]['options'][14]["title"] = 'منسوخ';
        $items[$c]['options'][14]["value"] = 13;
        $items[$c]['options'][15]["title"] = 'قطعه مونتاژی';
        $items[$c]['options'][15]["value"] = 14;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentRelatedPCode";
        $items[$c]['title'] = "کد محصول مرتبط";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPriceDakheli";
        $items[$c]['title'] = "قیمت خرید داخلی (بدون ارزش افزوده)";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['onchange'] = "onchange=calcPriceWithTax()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPriceDakheliTax";
        $items[$c]['title'] = "مالیات بر ارزش افزوده";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPriceDakheliWithTax";
        $items[$c]['title'] = "قیمت خرید داخلی (با ارزش افزوده)";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPACD";
        $items[$c]['title'] = "درصد هزینه های حمل و نقل (خرید داخلی)";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentKickback";
        $items[$c]['title'] = "مدت زمان باز پرداخت";
        $items[$c]['placeholder'] = "ماه";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "pieceManagmentCurrencyType";
        $items[$c]['style'] = "style='width:50%;'";
        $items[$c]['title'] = "نوع ارز";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = '--------';
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cnt;$i++){
            $items[$c]['options'][$i+1]["title"] = $AllCurrency[$i]['currencyName'];
            $items[$c]['options'][$i+1]["value"] = $AllCurrency[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentCurrency_amount";
        $items[$c]['title'] = "قیمت بر اساس ارز";
        $items[$c]['placeholder'] = "قیمت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPercentage_additional_costs";
        $items[$c]['onchange'] = "onchange=priceToRial()";
        $items[$c]['title'] = "درصد هزینه های ترخیص و غیره";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPriceToRial";
        $items[$c]['title'] = "قیمت به ریال";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPercentFuelWeight";
        $items[$c]['title'] = "درصد سوخت بار(ریخته گری)";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentCastingPrice";
        $items[$c]['title'] = "اجرت ریخته گری";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPFWBM";
        $items[$c]['title'] = "درصد سوخت بار(ماشینکاری)";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentCastMachPrice";
        $items[$c]['title'] = "مجموع اجرت ها(ریخته گری-ماشینکاری)";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPlasticPlate";
        $items[$c]['title'] = "هزینه آب کاری";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPercentageP";
        $items[$c]['title'] = "درصد هزینه حمل و نقل";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPriceBasis";
        $items[$c]['title'] = "مبنای قیمت";
        $items[$c]['placeholder'] = "مبنای قیمت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentSupplier";
        $items[$c]['title'] = "تامین کننده";
        $items[$c]['placeholder'] = "تامین کننده";
        $c++;

        $items[$c]['type'] = "inputGroup";
        $items[$c]['id'] = "pieceManagmentPriceCatchDate";
        $items[$c]['icon'] = "fa-calendar-alt";
        $items[$c]['style'] = "style='width:50%;float:right;'";
        $items[$c]['title'] = "تاریخ قیمت";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentTechnical_Specifications";
        $items[$c]['title'] = "مشخصات فنی";
        $items[$c]['placeholder'] = "مشخصات";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentReferenceECode";
        $items[$c]['title'] = "کد مهندسی مرجع";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentFSC";
        $items[$c]['title'] = "اولین مرحله ساخت";
        $items[$c]['placeholder'] = "اولین مرحله ساخت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentCustom_dimensions";
        $items[$c]['title'] = "ابعاد سفارشی";
        $items[$c]['placeholder'] = "ابعاد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentRawMaterialCode";
        $items[$c]['title'] = "کد مواد اولیه";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentExternal_size_bullion";
        $items[$c]['title'] = "اندازه خارجی شمش";
        $items[$c]['placeholder'] = "میلیمتر";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentWeight_materials";
        $items[$c]['title'] = "وزن مواد اولیه";
        $items[$c]['placeholder'] = "گرم";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentWeight_Machining";
        $items[$c]['title'] = "وزن ماشینکاری";
        $items[$c]['placeholder'] = "گرم";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentWeight_Final";
        $items[$c]['title'] = "وزن نهایی";
        $items[$c]['placeholder'] = "گرم";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentPrimaryBaseWeight";
        $items[$c]['title'] = "وزن مبنای اولیه";
        $items[$c]['placeholder'] = "گرم";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentMachiningBaseWeight";
        $items[$c]['title'] = "وزن مبنای ماشینکاری";
        $items[$c]['placeholder'] = "گرم";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentFinalBaseWeight";
        $items[$c]['title'] = "وزن مبنای نهایی";
        $items[$c]['placeholder'] = "گرم";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentLoadPolish";
        $items[$c]['title'] = "بارریزی پرداخت";
        $items[$c]['placeholder'] = "گرم";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "pieceManagmentSystem_weight";
        $items[$c]['title'] = "وزن سیستمی";
        $items[$c]['placeholder'] = "گرم";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "pieceManagmentDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "managePieceHiddenPid";
        $c++;

        if ($acm->hasAccess('engineeringAccess') && $acm->hasAccess('procurementAccess')){
            $items[$c]['type'] = "hidden";
            $items[$c]['id'] = "managePieceHiddenAccessType";
            $items[$c]['value'] = 2;
            $c++;
        }elseif($acm->hasAccess('engineeringAccess')){
            $items[$c]['type'] = "hidden";
            $items[$c]['id'] = "managePieceHiddenAccessType";
            $items[$c]['value'] = 1;
            $c++;
        }elseif ($acm->hasAccess('procurementAccess')){
            $items[$c]['type'] = "hidden";
            $items[$c]['id'] = "managePieceHiddenAccessType";
            $items[$c]['value'] = 0;
            $c++;
        }

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "managePieceHiddenPage";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreatePiece";
        $footerBottons[0]['type'] = "btn";
		$footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Add File MODAL++++++++++++++++++++++++++++++++
        $modalID = "pieceManagmentAddFileModal";
        $modalTitle = "فرم دانلود فایل قطعات";
        $style = 'style="max-width: 575px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "managePieceHiddenFilePid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "دانلود فایل نقشه";
        $footerBottons[0]['jsf'] = "downloadFilePiece";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[0]['type'] = "btn-success";

        $footerBottons[1]['title'] = "دانلود فایل OPC";
        $footerBottons[1]['jsf'] = "downloadFileOPC";
        $footerBottons[1]['data-dismiss'] = "NO";
        $footerBottons[1]['type'] = "btn-success";

        $footerBottons[2]['title'] = "دانلود برگه فرآیند";
        $footerBottons[2]['jsf'] = "downloadIPPiece";
        $footerBottons[2]['data-dismiss'] = "NO";
        $footerBottons[2]['type'] = "btn-success";

        $footerBottons[3]['title'] = "دانلود عکس قطعه";
        $footerBottons[3]['jsf'] = "downloadImagePiece";
        $footerBottons[3]['data-dismiss'] = "NO";
        $footerBottons[3]['type'] = "btn-success";

        $footerBottons[4]['title'] = "انصراف";
        $footerBottons[4]['type'] = "dismis";
        $addFileModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END Add File MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++ Start Piece Info Modal ++++++++++++++++++++++
        $modalID = "pieceManageInfoModal";
        $modalTitle = "سایر اطلاعات";
        $ShowDescription = 'Piece-manage-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showPieceInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Piece Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Add PM LIST MODAL++++++++++++++++++++++++++++++++
        $modalID = "uploadPMasterListModal";
        $modalTitle = "آپلود فایل قطعات";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "file";
        $items[$c]['id'] = "pieceManagmentPML_File";
        $items[$c]['helpText'] = "نوع فایل باید Excel باشد.";
        $items[$c]['title'] = "فایل قطعات";
        $items[$c]['accept'] = "accept='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doUploadPMListFile";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $uploadPMasterListModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END PM LIST MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++ SEARCH GOOD BASE MODAL ++++++++++++++++++
        $modalID = "pieceManageGoodSearchModal";
        $modalTitle = "جستجو بر اساس محصول";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $brands = $this->getBrands();
        $cntb = count($brands);
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "pieceManageGoodSearchBrand";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "برند محصول";
        $items[$c]['onchange'] = "onchange=findGoodGroup()";
        $items[$c]['options'] = array();
        for ($i=0;$i<$cntb;$i++){
            $items[$c]['options'][$i]["title"] = $brands[$i]['title'];
            $items[$c]['options'][$i]["value"] = $brands[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "pieceManageGoodSearchGroup";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "گروه محصول";
        $items[$c]['onchange'] = "onchange=findGoodSGroup()";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "pieceManageGoodSearchSGroup";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "زیرگروه محصول";
        $items[$c]['onchange'] = "onchange=findGoodSeries()";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "pieceManageGoodSearchSeries";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "سری محصول";
        $items[$c]['onchange'] = "onchange=findGoodColor()";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "pieceManageGoodSearchColor";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "پوشش محصول";
        $items[$c]['options'] = array();
        $c++;

        if($acm->hasAccess('engineeringAccess')){
            $items[$c]['type'] = "select";
            $items[$c]['id'] = "pieceManagmentHow_supplySearch";
            $items[$c]['title'] = "روش انجام محاسبات";
            $items[$c]['multiple'] = "multiple";
            $items[$c]['options'] = array();
            $items[$c]['options'][0]["title"] = 'راکد';
            $items[$c]['options'][0]["value"] = 0;
            $items[$c]['options'][1]["title"] = 'وارداتی';
            $items[$c]['options'][1]["value"] = 1;
            $items[$c]['options'][2]["title"] = 'خرید داخلی';
            $items[$c]['options'][2]["value"] = 2;
            $items[$c]['options'][3]["title"] = 'خرید قطعه ماشینکاری';
            $items[$c]['options'][3]["value"] = 3;
            $items[$c]['options'][4]["title"] = 'خرید قطعه ریخته گری';
            $items[$c]['options'][4]["value"] = 4;
            $items[$c]['options'][5]["title"] = 'تولید ریخته گری';
            $items[$c]['options'][5]["value"] = 5;
            $items[$c]['options'][6]["title"] = 'تولید ماشینکاری';
            $items[$c]['options'][6]["value"] = 6;
            $items[$c]['options'][7]["title"] = 'فورج';
            $items[$c]['options'][7]["value"] = 7;
            $items[$c]['options'][8]["title"] = 'تزریق پلاستیک';
            $items[$c]['options'][8]["value"] = 8;
            $items[$c]['options'][9]["title"] = 'لوله';
            $items[$c]['options'][9]["value"] = 9;
            $items[$c]['options'][10]["title"] = 'شیلنگ';
            $items[$c]['options'][10]["value"] = 10;
            $items[$c]['options'][11]["title"] = 'برش لیزر';
            $items[$c]['options'][11]["value"] = 11;
            $items[$c]['options'][12]["title"] = 'کلکتور';
            $items[$c]['options'][12]["value"] = 12;
            $items[$c]['options'][13]["title"] = 'منسوخ';
            $items[$c]['options'][13]["value"] = 13;
            $items[$c]['options'][14]["title"] = 'قطعه مونتاژی';
            $items[$c]['options'][14]["value"] = 14;
            $c++;
        }else{
            $items[$c]['type'] = "select";
            $items[$c]['id'] = "pieceManagmentHow_supplySearch";
            $items[$c]['title'] = "روش انجام محاسبات";
            $items[$c]['width'] = "150px";
            $items[$c]['multiple'] = "multiple";
            $items[$c]['options'] = array();
            $items[$c]['options'][0]["title"] = 'وارداتی';
            $items[$c]['options'][0]["value"] = 1;
            $items[$c]['options'][1]["title"] = 'خرید داخلی';
            $items[$c]['options'][1]["value"] = 2;
            $items[$c]['options'][2]["title"] = 'خرید قطعه ماشینکاری';
            $items[$c]['options'][2]["value"] = 3;
            $items[$c]['options'][3]["title"] = 'خرید قطعه ریخته گری';
            $items[$c]['options'][3]["value"] = 4;
            $items[$c]['options'][4]["title"] = 'تزریق پلاستیک';
            $items[$c]['options'][4]["value"] = 8;
            $c++;
        }

        $items[$c]['type'] = "inputGroup";
        $items[$c]['id'] = "pieceManageGoodSearchGName";
        $items[$c]['icon'] = "fa-plus fa-lg";
        $items[$c]['onclick'] = "onclick='addGoods()'";
        $items[$c]['title'] = "انتخاب محصول";
        $items[$c]['placeholder'] = "تایپ کنید";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "pieceManageGoodSearchGNames";
        $items[$c]['style'] = "style='resize: none;'";
        $items[$c]['title'] = "محصولات";
        $items[$c]['placeholder'] = "محصول / محصولات";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "showPieceManageList";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $pieceManageGoodSearch = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++ END SEARCH GOOD BASE MODAL ++++++++++++++++++
        //++++++++++++++++++ SEARCH PIECE BASE MODAL ++++++++++++++++++
        $modalID = "pieceManagePieceSearchModal";
        $modalTitle = "جستجو بر اساس قطعه";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['width'] = "150px";
        $items[$c]['id'] = "pieceManagePnameSearch";
        $items[$c]['title'] = "قسمتی از نام قطعه";
        $items[$c]['placeholder'] = "قسمتی از نام قطعه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['width'] = "120px";
        $items[$c]['id'] = "pieceManagePcodeSearch";
        $items[$c]['title'] = "کد قطعه";
        $items[$c]['placeholder'] = "کد قطعه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['width'] = "120px";
        $items[$c]['id'] = "pieceManageCollectionNameSearch";
        $items[$c]['title'] = "نام مجموعه";
        $items[$c]['placeholder'] = "نام مجموعه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['width'] = "120px";
        $items[$c]['id'] = "pieceManageMaterialSearch";
        $items[$c]['title'] = "جنس";
        $items[$c]['placeholder'] = "جنس";
        $c++;

        if($acm->hasAccess('engineeringAccess')){
            $items[$c]['type'] = "select";
            $items[$c]['id'] = "pieceManagmentHow_supplySearch1";
            $items[$c]['title'] = "روش انجام محاسبات";
            $items[$c]['multiple'] = "multiple";
            $items[$c]['options'] = array();
            $items[$c]['options'][0]["title"] = 'راکد';
            $items[$c]['options'][0]["value"] = 0;
            $items[$c]['options'][1]["title"] = 'وارداتی';
            $items[$c]['options'][1]["value"] = 1;
            $items[$c]['options'][2]["title"] = 'خرید داخلی';
            $items[$c]['options'][2]["value"] = 2;
            $items[$c]['options'][3]["title"] = 'خرید قطعه ماشینکاری';
            $items[$c]['options'][3]["value"] = 3;
            $items[$c]['options'][4]["title"] = 'خرید قطعه ریخته گری';
            $items[$c]['options'][4]["value"] = 4;
            $items[$c]['options'][5]["title"] = 'تولید ریخته گری';
            $items[$c]['options'][5]["value"] = 5;
            $items[$c]['options'][6]["title"] = 'تولید ماشینکاری';
            $items[$c]['options'][6]["value"] = 6;
            $items[$c]['options'][7]["title"] = 'فورج';
            $items[$c]['options'][7]["value"] = 7;
            $items[$c]['options'][8]["title"] = 'تزریق پلاستیک';
            $items[$c]['options'][8]["value"] = 8;
            $items[$c]['options'][9]["title"] = 'لوله';
            $items[$c]['options'][9]["value"] = 9;
            $items[$c]['options'][10]["title"] = 'شیلنگ';
            $items[$c]['options'][10]["value"] = 10;
            $items[$c]['options'][11]["title"] = 'برش لیزر';
            $items[$c]['options'][11]["value"] = 11;
            $items[$c]['options'][12]["title"] = 'کلکتور';
            $items[$c]['options'][12]["value"] = 12;
            $items[$c]['options'][13]["title"] = 'منسوخ';
            $items[$c]['options'][13]["value"] = 13;
            $items[$c]['options'][14]["title"] = 'قطعه مونتاژی';
            $items[$c]['options'][14]["value"] = 14;
        }else{
            $items[$c]['type'] = "select";
            $items[$c]['id'] = "pieceManagmentHow_supplySearch1";
            $items[$c]['title'] = "روش انجام محاسبات";
            $items[$c]['width'] = "150px";
            $items[$c]['multiple'] = "multiple";
            $items[$c]['options'] = array();
            $items[$c]['options'][0]["title"] = 'وارداتی';
            $items[$c]['options'][0]["value"] = 1;
            $items[$c]['options'][1]["title"] = 'خرید داخلی';
            $items[$c]['options'][1]["value"] = 2;
            $items[$c]['options'][2]["title"] = 'خرید قطعه ماشینکاری';
            $items[$c]['options'][2]["value"] = 3;
            $items[$c]['options'][3]["title"] = 'خرید قطعه ریخته گری';
            $items[$c]['options'][3]["value"] = 4;
            $items[$c]['options'][4]["title"] = 'تزریق پلاستیک';
            $items[$c]['options'][4]["value"] = 8;
        }

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "showPieceManageList";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $pieceManagePieceSearch = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++ END SEARCH PIECE BASE MODAL ++++++++++++++++++
        //++++++++++++++++++ Start GOODS OF PIECE Modal ++++++++++++++++++++++
        $modalID = "pieceManageGoodsModal";
        $modalTitle = "محصولات مرتبط";
        $ShowDescription = 'Piece-manage-Goods-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showGoodsOfPieceInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End GOODS OF PIECE Modal ++++++++++++++++++++++++
        $htm .= $editCreateModal;
        $htm .= $addFileModal;
        $htm .= $showPieceInfo;
        $htm .= $uploadPMasterListModal;
        $htm .= $pieceManageGoodSearch;
        $htm .= $pieceManagePieceSearch;
        $htm .= $showGoodsOfPieceInfo;
        return $htm;
    }

    public function getPieceList($pName,$pCode,$CollectionName,$Material,$supply1,$supply,$gname,$brand,$group,$sgroup,$series,$color,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $qDate = "SELECT `changeDate` FROM `backup_piece` ORDER BY `RowID` DESC LIMIT 1";
        $rqst = $db->ArrayQuery($qDate);
        $changeDate = $ut->greg_to_jal($rqst[0]['changeDate']);

        $usl = "UPDATE `piece` SET `uid`=0 WHERE `uid`={$_SESSION['userid']}";
        $db->Query($usl);

        $dsl = "DELETE FROM `multiple_search` WHERE `uid`={$_SESSION['userid']}";
        $db->Query($dsl);

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        $rids = array();
        $A = array();
        $B = array();
        $C = array();
        $D = array();
        $E = array();
        //********************************** بر اساس برند محصول **********************************
        if (strlen(trim($brand)) > 0){
            $brand = explode(',',$brand);
            $cb = count($brand);
            $gcode = array();
            for ($i=0;$i<$cb;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$brand[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `brand`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {  // تزریق پلاستیک و لیزر و شیلنگ
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $A[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){  // قطعه مونتاژی
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $A[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $A[] = $rqqq[0]['RowID'];
                            }else {
                                $A[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $A[] = $rqqq[0]['RowID'];
                        }else {
                            $A[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }
        //********************************** بر اساس گروه محصول **********************************
        if (strlen(trim($group)) > 0){
            $group = explode(',',$group);
            $cg = count($group);
            $gcode = array();
            for ($i=0;$i<$cg;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$group[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `ggroup`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $B[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $B[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $B[] = $rqqq[0]['RowID'];
                            }else {
                                $B[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $B[] = $rqqq[0]['RowID'];
                        }else {
                            $B[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }
        //********************************** بر اساس زیرگروه محصول **********************************
        if (strlen(trim($sgroup)) > 0){
            $sgroup = explode(',',$sgroup);
            $csg = count($sgroup);
            $gcode = array();
            for ($i=0;$i<$csg;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$sgroup[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `gsgroup`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $C[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $C[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $C[] = $rqqq[0]['RowID'];
                            }else {
                                $C[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $C[] = $rqqq[0]['RowID'];
                        }else {
                            $C[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }
        //********************************** بر اساس سری محصول **********************************
        if (strlen(trim($series)) > 0){
            $series = explode(',',$series);
            $cs = count($series);
            $gcode = array();
            for ($i=0;$i<$cs;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$series[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `Series`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $D[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $D[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $D[] = $rqqq[0]['RowID'];
                            }else {
                                $D[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $D[] = $rqqq[0]['RowID'];
                        }else {
                            $D[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }
        //********************************** بر اساس پوشش محصول **********************************
        if (strlen(trim($color)) > 0){
            $color = explode(',',$color);
            $cc = count($color);
            $gcode = array();
            for ($i=0;$i<$cc;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$color[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `color`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $E[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $E[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $E[] = $rqqq[0]['RowID'];
                            }else {
                                $E[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $E[] = $rqqq[0]['RowID'];
                        }else {
                            $E[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }

        if (count($A) > 0 && count($B) > 0){
            $rids = array_intersect($A,$B);
        }elseif (count($A) > 0){
            $rids = $A;
        }
        if (count($C) > 0){
            $rids = array_intersect($rids,$C);
        }
        if (count($D) > 0){
            $rids = array_intersect($rids,$D);
        }
        if (count($E) > 0){
            $rids = array_intersect($rids,$E);
        }
        //********************************** بر اساس نام محصول **********************************
        if(strlen(trim($gname)) > 0){
            $gname = explode(',',$gname);
            $gnt = count($gname);
            $gcode = array();
            for ($i=0;$i<$gnt;$i++){
                $query = "SELECT `gCode` FROM `good` WHERE `gName`='{$gname[$i]}'";
                $rst = $db->ArrayQuery($query);
                $gcode[] = $rst[0]['gCode'];
            }
            $gcode = array_values(array_unique($gcode));
            $gntc = count($gcode);
            for ($j=0;$j<$gntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $rids[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }else {
                                $rids[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $rids[] = $rqqq[0]['RowID'];
                        }else {
                            $rids[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }

        if (count($rids) > 0){
            $rids = array_values(array_unique(array_filter($rids)));
            $rids = implode(',',$rids);
            $w[] = '`piece`.`RowID` IN ('.$rids.') ';
        }
        //********************************** بر اساس قطعه **********************************
        if($acm->hasAccess('engineeringAccess') || $acm->hasAccess('industrialManagement')){
            if(strlen(trim($supply)) > 0){
                $w[] = '`FixHow_supply` ='.$supply.' ';
            }elseif(strlen(trim($supply1)) > 0){
                $w[] = '`FixHow_supply` ='.$supply1.' ';
            }
        }else{
            if(strlen(trim($supply)) > 0){
                $w[] = '`FixHow_supply` ='.$supply.' ';
            }elseif(strlen(trim($supply1)) > 0){
                $w[] = '`FixHow_supply` ='.$supply1.' ';
            }else{
                $w[] = '`FixHow_supply` IN (1,2,3,4,8) ';
            }
        }
        if(strlen(trim($pCode)) > 0){
            $w[] = '`pCode` LIKE "%'.$pCode.'%" ';
        }
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        if(strlen(trim($CollectionName)) > 0){
            $w[] = '`Collection_name` LIKE "%'.$CollectionName.'%" ';
        }
        if(strlen(trim($Material)) > 0){
            $w[] = '`material` LIKE "%'.$Material.'%" ';
        }
        $sql = "SELECT `piece`.`RowID`,`pCode`,`pName` FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
        ////$//ut->fileRecorder($sql);
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
        }
        $sendParam = array($finalRes,$changeDate);
        return $sendParam;
    }

    public function getMPieceList($pName,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();

        if($acm->hasAccess('procurementAccess')){
            $w[] = '`ChangingHow_supply` IN (1,2,3,4,8) ';
        }
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        $sql = "SELECT `RowID` FROM `piece` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql1 = "SELECT `piece`.`RowID`,`pCode`,`pName` FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
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
        $q = "UPDATE `piece` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
        $db->Query($q);

        $q = "UPDATE `piece` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
        $db->Query($q);

        $q = "INSERT INTO `multiple_search` (`word`,`uid`) VALUES ('{$pName}',{$_SESSION['userid']})";
        $db->Query($q);

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
        }
        return $finalRes;
    }

    public function getMPagePieceList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();

        $sql = "SELECT `RowID` FROM `piece` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql1 = "SELECT `piece`.`RowID`,`pCode`,`pName` FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
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
        $q = "UPDATE `piece` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
        $db->Query($q);

        $q = "UPDATE `piece` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
        $db->Query($q);

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
        }
        return $finalRes;
    }

    public function getBMPieceList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();

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
            $w = array();
            if($acm->hasAccess('procurementAccess') && !$acm->hasAccess('engineeringAccess')){
                $w[] = '`ChangingHow_supply` IN (1,2,3,4,8) ';
            }
            $sql4 = "SELECT `piece`.`RowID`,`pCode`,`pName` FROM `piece`
                     INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
            if(count($w)){
                $where = implode(" AND ",$w);
                $sql4 .= " WHERE ".$where;
            }
            $sql4 .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
            $res = $db->ArrayQuery($sql4);
        }else{
            for ($i=0;$i<$cntrst;$i++){
                $w = array();
                if($acm->hasAccess('procurementAccess') && !$acm->hasAccess('engineeringAccess')){
                    $w[] = '`ChangingHow_supply` IN (1,2,3,4,8) ';
                }
                $w[] = '`pName` LIKE "%'.$rst[$i]['word'].'%" ';

                $sql3 = "SELECT `RowID` FROM `piece` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
                $res = $db->ArrayQuery($sql3);
                if (count($res) > 0){
                    $w[] = '`uid`='.$_SESSION['userid'].' ';
                }

                $sql4 = "SELECT `piece`.`RowID`,`pCode`,`pName` FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
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
                $q = "UPDATE `piece` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
                $db->Query($q);

                $q = "UPDATE `piece` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
                $db->Query($q);
            }
        }
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
        }
        return $finalRes;
    }

    public function getBMPagePieceList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sql1 = "SELECT `word` FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` ASC";
        $rst = $db->ArrayQuery($sql1);
        $cntrst = count($rst);

        $sql2 = "UPDATE `piece` SET `uid`=0 WHERE `uid`={$_SESSION['userid']}";
        $db->Query($sql2);

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        if ($cntrst == 0){
            $w = array();
            if($acm->hasAccess('procurementAccess') && !$acm->hasAccess('engineeringAccess')){
                $w[] = '`ChangingHow_supply` IN (1,2,3,4,8) ';
            }
            $sql4 = "SELECT `piece`.`RowID`,`pCode`,`pName` FROM `piece`
                     INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
            if(count($w)){
                $where = implode(" AND ",$w);
                $sql4 .= " WHERE ".$where;
            }
            $sql4 .= " ORDER BY `RowID` ASC LIMIT $start,".$numRows;
            $res = $db->ArrayQuery($sql4);
        }else{
            for ($i=0;$i<$cntrst;$i++){
                $w = array();
                if($acm->hasAccess('procurementAccess') && !$acm->hasAccess('engineeringAccess')){
                    $w[] = '`ChangingHow_supply` IN (1,2,3,4,8) ';
                }
                $w[] = '`pName` LIKE "%'.$rst[$i]['word'].'%" ';

                $sql3 = "SELECT `RowID` FROM `piece` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
                $res = $db->ArrayQuery($sql3);
                if (count($res) > 0){
                    $w[] = '`uid`='.$_SESSION['userid'].' ';
                }

                $sql4 = "SELECT `piece`.`RowID`,`pCode`,`pName` FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
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
                $q = "UPDATE `piece` SET `uid`={$_SESSION['userid']} WHERE `RowID` IN ({$arr})";
                $db->Query($q);

                $q = "UPDATE `piece` SET `uid`=0 WHERE `RowID` NOT IN ({$arr})";
                $db->Query($q);
            }
        }
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
        }
        return $finalRes;
    }

    public function getBMPieceListCountRows(){
        $acm = new acm();
        $db = new DBi();
        $sql1 = "SELECT `word` FROM `multiple_search` WHERE `uid`={$_SESSION['userid']} ORDER BY `RowID` ASC";
        $rst = $db->ArrayQuery($sql1);
        $cntrst = count($rst);
        if ($cntrst == 0){
            $w = array();
            if($acm->hasAccess('procurementAccess') && !$acm->hasAccess('engineeringAccess')){
                $w[] = '`ChangingHow_supply` IN (1,2,3,4,8) ';
            }
            $sql4 = "SELECT `piece`.`RowID` FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
            if(count($w)){
                $where = implode(" AND ",$w);
                $sql4 .= " WHERE ".$where;
            }
            $res = $db->ArrayQuery($sql4);
        }else{
            for ($i=0;$i<$cntrst;$i++){
                $w = array();
                if($acm->hasAccess('procurementAccess') && !$acm->hasAccess('engineeringAccess')){
                    $w[] = '`ChangingHow_supply` IN (1,2,3,4,8) ';
                }
                $w[] = '`pName` LIKE "%'.$rst[$i]['word'].'%" ';

                $sql3 = "SELECT `RowID` FROM `piece` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
                $res = $db->ArrayQuery($sql3);
                if (count($res) > 0){
                    $w[] = '`uid`='.$_SESSION['userid'].' ';
                }

                $sql4 = "SELECT `piece`.`RowID` FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
                if(count($w)){
                    $where = implode(" AND ",$w);
                    $sql4 .= " WHERE ".$where;
                }
                $res = $db->ArrayQuery($sql4);
            }
        }
        return count($res);
    }

    public function getMPieceListCountRows($pName){
        $acm = new acm();
        $db = new DBi();
        $w = array();
        if($acm->hasAccess('procurementAccess')){
            $w[] = '`ChangingHow_supply` IN (1,2,3,4,8) ';
        }
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        $sql = "SELECT `RowID` FROM `piece` WHERE `uid`={$_SESSION['userid']} LIMIT 1";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql1 = "SELECT `piece`.`RowID`,`pCode`,`pName` FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql1 .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql1);
        return count($res);
    }

    public function getPieceListCountRows($pName,$pCode,$CollectionName,$Material,$supply1,$supply,$gname,$brand,$group,$sgroup,$series,$color){
        $acm = new acm();
        $db = new DBi();
        $w = array();
        $rids = array();
        $A = array();
        $B = array();
        $C = array();
        $D = array();
        $E = array();
        //********************************** بر اساس برند محصول **********************************
        if (strlen(trim($brand)) > 0){
            $brand = explode(',',$brand);
            $cb = count($brand);
            $gcode = array();
            for ($i=0;$i<$cb;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$brand[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `brand`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {  // تزریق پلاستیک و لیزر و شیلنگ
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $A[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){  // قطعه مونتاژی
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $A[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $A[] = $rqqq[0]['RowID'];
                            }else {
                                $A[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $A[] = $rqqq[0]['RowID'];
                        }else {
                            $A[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }
        //********************************** بر اساس گروه محصول **********************************
        if (strlen(trim($group)) > 0){
            $group = explode(',',$group);
            $cg = count($group);
            $gcode = array();
            for ($i=0;$i<$cg;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$group[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `ggroup`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $B[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $B[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $B[] = $rqqq[0]['RowID'];
                            }else {
                                $B[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $B[] = $rqqq[0]['RowID'];
                        }else {
                            $B[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }
        //********************************** بر اساس زیرگروه محصول **********************************
        if (strlen(trim($sgroup)) > 0){
            $sgroup = explode(',',$sgroup);
            $csg = count($sgroup);
            $gcode = array();
            for ($i=0;$i<$csg;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$sgroup[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `gsgroup`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $C[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $C[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $C[] = $rqqq[0]['RowID'];
                            }else {
                                $C[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $C[] = $rqqq[0]['RowID'];
                        }else {
                            $C[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }
        //********************************** بر اساس سری محصول **********************************
        if (strlen(trim($series)) > 0){
            $series = explode(',',$series);
            $cs = count($series);
            $gcode = array();
            for ($i=0;$i<$cs;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$series[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `Series`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $D[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $D[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $D[] = $rqqq[0]['RowID'];
                            }else {
                                $D[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $D[] = $rqqq[0]['RowID'];
                        }else {
                            $D[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }
        //********************************** بر اساس پوشش محصول **********************************
        if (strlen(trim($color)) > 0){
            $color = explode(',',$color);
            $cc = count($color);
            $gcode = array();
            for ($i=0;$i<$cc;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$color[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `color`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $E[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $E[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $E[] = $rqqq[0]['RowID'];
                            }else {
                                $E[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $E[] = $rqqq[0]['RowID'];
                        }else {
                            $E[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }

        if (count($A) > 0 && count($B) > 0){
            $rids = array_intersect($A,$B);
        }elseif (count($A) > 0){
            $rids = $A;
        }
        if (count($C) > 0){
            $rids = array_intersect($rids,$C);
        }
        if (count($D) > 0){
            $rids = array_intersect($rids,$D);
        }
        if (count($E) > 0){
            $rids = array_intersect($rids,$E);
        }
        //********************************** بر اساس نام محصول **********************************
        if(strlen(trim($gname)) > 0){
            $gname = explode(',',$gname);
            $gnt = count($gname);
            $gcode = array();
            for ($i=0;$i<$gnt;$i++){
                $query = "SELECT `gCode` FROM `good` WHERE `gName`='{$gname[$i]}'";
                $rst = $db->ArrayQuery($query);
                $gcode[] = $rst[0]['gCode'];
            }
            $gcode = array_values(array_unique($gcode));
            $gntc = count($gcode);
            for ($j=0;$j<$gntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $rids[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }else {
                                $rids[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $rids[] = $rqqq[0]['RowID'];
                        }else {
                            $rids[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }

        if (count($rids) > 0){
            $rids = array_values(array_unique(array_filter($rids)));
            $rids = implode(',',$rids);
            $w[] = '`piece`.`RowID` IN ('.$rids.') ';
        }
        //********************************** بر اساس قطعه **********************************
        if($acm->hasAccess('engineeringAccess') || $acm->hasAccess('industrialManagement')){
            if(strlen(trim($supply)) > 0){
                $w[] = '`FixHow_supply` ='.$supply.' ';
            }elseif(strlen(trim($supply1)) > 0){
                $w[] = '`FixHow_supply` ='.$supply1.' ';
            }
        }else{
            if(strlen(trim($supply)) > 0){
                $w[] = '`FixHow_supply` ='.$supply.' ';
            }elseif(strlen(trim($supply1)) > 0){
                $w[] = '`FixHow_supply` ='.$supply1.' ';
            }else{
                $w[] = '`FixHow_supply` IN (1,2,3,4,8) ';
            }
        }
        if(strlen(trim($pCode)) > 0){
            $w[] = '`pCode` LIKE "%'.$pCode.'%" ';
        }
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        if(strlen(trim($CollectionName)) > 0){
            $w[] = '`Collection_name` LIKE "%'.$CollectionName.'%" ';
        }
        if(strlen(trim($Material)) > 0){
            $w[] = '`material` LIKE "%'.$Material.'%" ';
        }
        $sql = "SELECT `piece`.`RowID` FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)";
        if(count($w)){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getMasterListPieceExcel($pName,$pCode,$CollectionName,$Material,$supply1,$supply,$gname,$brand,$group,$sgroup,$series,$color){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $w = array();
        $rids = array();
        //********************************** بر اساس برند محصول **********************************
        if (strlen(trim($brand)) > 0){
            $brand = explode(',',$brand);
            $cb = count($brand);
            $gcode = array();
            for ($i=0;$i<$cb;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$brand[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `brand`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       INNER JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {  // تزریق پلاستیک و لیزر و شیلنگ
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $rids[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){  // قطعه مونتاژی
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                INNER JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }else {
                                $rids[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $rids[] = $rqqq[0]['RowID'];
                        }else {
                            $rids[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }
        //********************************** بر اساس گروه محصول **********************************
        if (strlen(trim($group)) > 0){
            $group = explode(',',$group);
            $cg = count($group);
            $gcode = array();
            for ($i=0;$i<$cg;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$group[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `ggroup`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $rids[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }else {
                                $rids[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $rids[] = $rqqq[0]['RowID'];
                        }else {
                            $rids[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }
        //********************************** بر اساس زیرگروه محصول **********************************
        if (strlen(trim($sgroup)) > 0){
            $sgroup = explode(',',$sgroup);
            $csg = count($sgroup);
            $gcode = array();
            for ($i=0;$i<$csg;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$sgroup[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `gsgroup`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $rids[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }else {
                                $rids[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $rids[] = $rqqq[0]['RowID'];
                        }else {
                            $rids[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }
        //********************************** بر اساس سری محصول **********************************
        if (strlen(trim($series)) > 0){
            $series = explode(',',$series);
            $cs = count($series);
            $gcode = array();
            for ($i=0;$i<$cs;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$series[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `Series`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $rids[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }else {
                                $rids[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $rids[] = $rqqq[0]['RowID'];
                        }else {
                            $rids[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }
        //********************************** بر اساس پوشش محصول **********************************
        if (strlen(trim($color)) > 0){
            $color = explode(',',$color);
            $cc = count($color);
            $gcode = array();
            for ($i=0;$i<$cc;$i++){
                $q = "SELECT `title` FROM `categories` WHERE `RowID`={$color[$i]}";
                $rq = $db->ArrayQuery($q);
                $qry = "SELECT `gCode` FROM `good` WHERE `color`='{$rq[0]['title']}'";
                $rqst = $db->ArrayQuery($qry);
                $sgnt = count($rqst);
                for ($j=0;$j<$sgnt;$j++){
                    $gcode[] = $rqst[$j]['gCode'];
                }
            }
            $sgntc = count($gcode);
            for ($j=0;$j<$sgntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $rids[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }else {
                                $rids[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $rids[] = $rqqq[0]['RowID'];
                        }else {
                            $rids[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }
        //********************************** بر اساس نام محصول **********************************
        if(strlen(trim($gname)) > 0){
            $gname = explode(',',$gname);
            $gnt = count($gname);
            $gcode = array();
            for ($i=0;$i<$gnt;$i++){
                $query = "SELECT `gCode` FROM `good` WHERE `gName`='{$gname[$i]}'";
                $rst = $db->ArrayQuery($query);
                $gcode[] = $rst[0]['gCode'];
            }
            $gcode = array_values(array_unique($gcode));
            $gntc = count($gcode);
            for ($j=0;$j<$gntc;$j++){
                $qq = "SELECT `Piece`.`RowID` AS `pieceID`,`referenceECode`,`montageCode`,`material`,`ChangingHow_supply` FROM `interface`
                       LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                       INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                       WHERE `ProductCode`='{$gcode[$j]}'";
                $rqq = $db->ArrayQuery($qq);
                $pntc = count($rqq);
                for ($r=0;$r<$pntc;$r++){
                    if (intval($rqq[$r]['ChangingHow_supply']) == 8 || intval($rqq[$r]['ChangingHow_supply']) == 10 || intval($rqq[$r]['ChangingHow_supply']) == 11) {
                        $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['material']}'";
                        $rqqq = $db->ArrayQuery($qqq);
                        $rids[] = $rqqq[0]['RowID'];
                    }
                    if (strlen(trim($rqq[$r]['montageCode'])) > 0){
                        $sqm = "SELECT `Piece`.`RowID`,`referenceECode`,`material`,`ChangingHow_supply` FROM `interface`
                                LEFT JOIN `piece` ON (`piece`.`pCode`=`interface`.`PieceCode`)
                                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                                WHERE `ProductCode`='{$rqq[$r]['montageCode']}'";
                        $rsm = $db->ArrayQuery($sqm);
                        $cntsm = count($rsm);
                        for ($a=0;$a<$cntsm;$a++){
                            if (intval($rsm[$a]['ChangingHow_supply']) == 8 || intval($rsm[$a]['ChangingHow_supply']) == 10 || intval($rsm[$a]['ChangingHow_supply']) == 11) {
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['material']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }
                            if (strlen(trim($rsm[$a]['referenceECode'])) > 0){
                                $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rsm[$a]['referenceECode']}'";
                                $rqqq = $db->ArrayQuery($qqq);
                                $rids[] = $rqqq[0]['RowID'];
                            }else {
                                $rids[] = $rsm[$a]['RowID'];
                            }
                        }
                    }else{
                        if (strlen(trim($rqq[$r]['referenceECode'])) > 0){
                            $qqq = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$rqq[$r]['referenceECode']}'";
                            $rqqq = $db->ArrayQuery($qqq);
                            $rids[] = $rqqq[0]['RowID'];
                        }else {
                            $rids[] = $rqq[$r]['pieceID'];
                        }
                    }
                }
            }
        }

        if (count($rids) > 0){
            $rids = array_values(array_unique(array_filter($rids)));
            $rids = implode(',',$rids);
            $w[] = '`piece`.`RowID` IN ('.$rids.') ';
        }
        //********************************** بر اساس قطعه **********************************
        if($acm->hasAccess('engineeringAccess')){
            if(strlen(trim($supply)) > 0){
                $w[] = '`ChangingHow_supply` IN ('.$supply.') ';
            }elseif(strlen(trim($supply1)) > 0){
                $w[] = '`ChangingHow_supply` IN ('.$supply1.') ';
            }
        }else{
            if(strlen(trim($supply)) > 0){
                $w[] = '`ChangingHow_supply` IN ('.$supply.') ';
            }elseif(strlen(trim($supply1)) > 0){
                $w[] = '`ChangingHow_supply` IN ('.$supply1.') ';
            }else{
                $w[] = '`ChangingHow_supply` IN (1,2,3,4,8) ';
            }
        }
        if(strlen(trim($pCode)) > 0){
            $w[] = '`pCode` LIKE "%'.$pCode.'%" ';
        }
        if(strlen(trim($pName)) > 0){
            $w[] = '`pName` LIKE "%'.$pName.'%" ';
        }
        if(strlen(trim($CollectionName)) > 0){
            $w[] = '`Collection_name` LIKE "%'.$CollectionName.'%" ';
        }
        if(strlen(trim($Material)) > 0){
            $w[] = '`material` LIKE "%'.$Material.'%" ';
        }
        $sql = "SELECT `pCode`,`Collection_name`,`Subset_name`,`pName`,`mapNumber`,`Latin_title`,`Technical_Specifications`,
                       `description`,`referenceECode`,`pUnit`,`material`,`ChangingHow_supply`,`first_stage_construction`,`Custom_dimensions`,
                       `RawMaterialCode`,`external_size_bullion`,`System_weight`,`Weight_materials`,`Weight_Machining`,`Weight_Final`
                       FROM `piece` INNER JOIN `piece_masterlist` ON (`piece_masterlist`.`pid`=`piece`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        if(count($res) > 0){
            for($i=0;$i<$listCount;$i++){
                switch ($res[$i]['ChangingHow_supply']){
                    case 0:
                        $res[$i]['ChangingHow_supply'] = 'راکد';
                        break;
                    case 1:
                        $res[$i]['ChangingHow_supply'] = 'وارداتی';
                        break;
                    case 2:
                        $res[$i]['ChangingHow_supply'] = 'خرید داخلی';
                        break;
                    case 3:
                        $res[$i]['ChangingHow_supply'] = 'خرید قطعه ماشینکاری';
                        break;
                    case 4:
                        $res[$i]['ChangingHow_supply'] = 'خرید قطعه ریخته گری';
                        break;
                    case 5:
                        $res[$i]['ChangingHow_supply'] = 'تولید ریخته گری';
                        break;
                    case 6:
                        $res[$i]['ChangingHow_supply'] = 'تولید ماشینکاری';
                        break;
                    case 7:
                        $res[$i]['ChangingHow_supply'] = 'فورج';
                        break;
                    case 8:
                        $res[$i]['ChangingHow_supply'] = 'تزریق پلاستیک';
                        break;
                    case 9:
                        $res[$i]['ChangingHow_supply'] = 'لوله';
                        break;
                    case 10:
                        $res[$i]['ChangingHow_supply'] = 'شیلنگ';
                        break;
                    case 11:
                        $res[$i]['ChangingHow_supply'] = 'برش لیزر';
                        break;
                    case 12:
                        $res[$i]['ChangingHow_supply'] = 'کلکتور';
                        break;
                    case 13:
                        $res[$i]['ChangingHow_supply'] = 'منسوخ';
                        break;
                    case 14:
                        $res[$i]['ChangingHow_supply'] = 'قطعه مونتاژی';
                        break;
                }
            }
            return $res;
        }else{
            $res = "امکان گرفتن خروجی وجود ندارد";
            $out = "false";
            response($res,$out);
            exit;
        }
    }

    public function getBrands(){  // برند محصولات
        $db = new DBi();
        $sql = "SELECT `RowID`,`title` FROM `categories` WHERE `level`=1";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function getGGroup($brand){  // گروه محصولات
        $db = new DBi();
        $brand = explode(',',$brand);
        $cb = count($brand);

        $q = "SELECT `RowID`,`title`,`parentID` FROM `categories` WHERE `level`=2";
        $rst = $db->ArrayQuery($q);
        $cnt = count($rst);
        $x = 0;
        $result = array();

        for ($i=0;$i<$cb;$i++) {
            for ($j = 0; $j < $cnt; $j++) {
                $parentID = explode(',', $rst[$j]['parentID']);
                if (in_array($brand[$i], $parentID)) {
                    $result[$x] = $rst[$j]['RowID'];
                    $x++;
                    $result[$x] = $rst[$j]['title'];
                    $x++;
                }
            }
        }

        $result = array_values(array_unique($result));
        return $result;
    }

    public function getGSGroup($group){  // زیرگروه محصولات
        $db = new DBi();
        $group = explode(',',$group);
        $cb = count($group);

        $q = "SELECT `RowID`,`title`,`parentID` FROM `categories` WHERE `level`=3";
        $rst = $db->ArrayQuery($q);
        $cnt = count($rst);
        $x = 0;
        $result = array();

        for ($i=0;$i<$cb;$i++) {
            for ($j = 0; $j < $cnt; $j++) {
                $parentID = explode(',', $rst[$j]['parentID']);
                if (in_array($group[$i], $parentID)) {
                    $result[$x] = $rst[$j]['RowID'];
                    $x++;
                    $result[$x] = $rst[$j]['title'];
                    $x++;
                }
            }
        }
        $result = array_values(array_unique($result));
        return $result;
    }

    public function getGSeries($sgroup){  // سری محصولات
        $db = new DBi();
        $sgroup = explode(',',$sgroup);
        $cb = count($sgroup);

        $q = "SELECT `RowID`,`title`,`parentID` FROM `categories` WHERE `level`=4";
        $rst = $db->ArrayQuery($q);
        $cnt = count($rst);
        $x = 0;
        $result = array();

        for ($i=0;$i<$cb;$i++) {
            for ($j = 0; $j < $cnt; $j++) {
                $parentID = explode(',', $rst[$j]['parentID']);
                if (in_array($sgroup[$i], $parentID)) {
                    $result[$x] = $rst[$j]['RowID'];
                    $x++;
                    $result[$x] = $rst[$j]['title'];
                    $x++;
                }
            }
        }

        $result = array_values(array_unique($result));
        return $result;
    }

    public function getGColor($series){  // پوشش محصولات
        $db = new DBi();
        $series = explode(',',$series);
        $cb = count($series);

        $q = "SELECT `RowID`,`title`,`parentID` FROM `categories` WHERE `level`=5";
        $rst = $db->ArrayQuery($q);
        $cnt = count($rst);
        $x = 0;
        $result = array();

        for ($i=0;$i<$cb;$i++) {
            for ($j = 0; $j < $cnt; $j++) {
                $parentID = explode(',', $rst[$j]['parentID']);
                if (in_array($series[$i], $parentID)) {
                    $result[$x] = $rst[$j]['RowID'];
                    $x++;
                    $result[$x] = $rst[$j]['title'];
                    $x++;
                }
            }
        }

        $result = array_values(array_unique($result));
        return $result;
    }

    public function getJustGGroup(){  // گروه محصولات
        $db = new DBi();
        $sql = "SELECT `RowID`,`title` FROM `categories` WHERE `level`=2 ORDER BY `title` ASC";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function getJustGSGroup(){  // زیرگروه محصولات
        $db = new DBi();
        $sql = "SELECT `RowID`,`title` FROM `categories` WHERE `level`=3 ORDER BY `title` ASC";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function getJustGSeries(){  // سری محصولات
        $db = new DBi();
        $sql = "SELECT `RowID`,`title` FROM `categories` WHERE `level`=4 ORDER BY `title` ASC";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function goodsOfPieceHTM($pCode){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `ProductCode`,`amount`,`gName` FROM `good`
                INNER JOIN `interface` ON (`good`.`gCode`=`interface`.`ProductCode`) WHERE `interface`.`PieceCode`='{$pCode}'";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoPiece-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">کد محصول</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 80%;">نام محصول</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">مقدار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['ProductCode'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['gName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['amount'].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function OtherInfoPieceHTM($pid){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `pUnit`,`Collection_name`,`Subset_name`,`referenceECode`,`mapNumber`,`Latin_title`,`Technical_Specifications`,
                       `description`,`material`,`ChangingHow_supply`,`montageCode`,`first_stage_construction`,`Custom_dimensions`,`RawMaterialCode`,
                       `Weight_materials`,`Weight_Machining`,`Weight_Final`,`System_weight`
                        FROM `piece`
                        INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`) WHERE /*`piece`.`RowID`*/`piece_masterlist`.`pid`=".$pid;
        $res = $db->ArrayQuery($sql);
        $infoNames = array('واحد قطعه','نام مجموعه','نام زیر مجموعه','کد مهندسی مرجع','شماره نقشه','عنوان لاتین کالا','مشخصات فنی',
                           'توضیحات','جنس','روش انجام محاسبات','کد محصول مرتبط','اولین مرحله ساخت','ابعاد سفارشی','کد مواد اولیه',
                           'وزن مواد اولیه','وزن ماشینکاری','وزن نهایی','وزن سیستمی'
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
        for ($i=0;$i<18;$i++){
            $iterator++;
            $keyName = key($res[0]);
            if ($iterator == 10){
                switch ($res[0]["$keyName"]){
                    case 0:
                        $res[0]["$keyName"] = 'راکد';
                        break;
                    case 1:
                        $res[0]["$keyName"] = 'وارداتی';
                        break;
                    case 2:
                        $res[0]["$keyName"] = 'خرید داخلی';
                        break;
                    case 3:
                        $res[0]["$keyName"] = 'خرید قطعه ماشینکاری';
                        break;
                    case 4:
                        $res[0]["$keyName"] = 'خرید قطعه ریخته گری';
                        break;
                    case 5:
                        $res[0]["$keyName"] = 'تولید ریخته گری';
                        break;
                    case 6:
                        $res[0]["$keyName"] = 'تولید ماشینکاری';
                        break;
                    case 7:
                        $res[0]["$keyName"] = 'فورج';
                        break;
                    case 8:
                        $res[0]["$keyName"] = 'تزریق پلاستیک';
                        break;
                    case 9:
                        $res[0]["$keyName"] = 'لوله';
                        break;
                    case 10:
                        $res[0]["$keyName"] = 'شیلنگ';
                        break;
                    case 11:
                        $res[0]["$keyName"] = 'برش لیزر';
                        break;
                    case 12:
                        $res[0]["$keyName"] = 'کلکتور';
                        break;
                    case 13:
                        $res[0]["$keyName"] = 'منسوخ';
                        break;
                    case 14:
                        $res[0]["$keyName"] = 'قطعه مونتاژی';
                        break;
                }
            }

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;direction: ltr;">'.$res[0]["$keyName"].'</td>';
            $htm .= '</tr>';
            next($res[0]);
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function pieceInfo($pid){
        $db = new DBi();
        $ut = new Utility();

        $sqq = "SELECT `tax` FROM `wastage`";
        $rsq = $db->ArrayQuery($sqq);


        $sql = "SELECT `piece`.`RowID`,`pUnit`,`pCode`,`pName`,`currencyID`,`currency_amount`,`Percentage_additional_costs`,`priceDakheli`,`priceDakheliWithoutTax`,
                       `PercentageACD`,`description`,`CastingPriceBC`,`PercentFuelWeightBC`,`CastMachPriceBM`,`PercentFuelWeightBM`,
                       `priceBasis`,`Supplier`,`priceCatchDate`,`Collection_name`,`Subset_name`,`Latin_title`,`Technical_Specifications`,
                       `material`,`ChangingHow_supply`,`montageCode`,`first_stage_construction`,`Custom_dimensions`,`Weight_materials`,
                       `Weight_Machining`,`Weight_Final`,`System_weight`,`external_size_bullion`,`PrimaryBaseWeight`,`FinalBaseWeight`,
                       `MachiningBaseWeight`,`LoadPolish`,`referenceECode`,`RawMaterialCode`,`monthkickback`,`plasticPlate`,`PercentagePP`
                        FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`) 
                        WHERE `piece`.`RowID`=".$pid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            if (intval($res[0]['currencyID']) > 0){
                $query = "SELECT `dayRate` FROM `currency` WHERE `RowID`={$res[0]['currencyID']}";
                $rst = $db->ArrayQuery($query);
                $pp = number_format((($res[0]['currency_amount'] * $rst[0]['dayRate']) * (100 + floatval($res[0]['Percentage_additional_costs']))) / 100);
            }else{
                $pp = '';
            }
            $priceDakheli = (strlen(trim($res[0]['priceDakheli'])) > 0 ? number_format($res[0]['priceDakheli']) : '');
            $priceDakheliWithoutTax = (strlen(trim($res[0]['priceDakheliWithoutTax'])) > 0 ? number_format($res[0]['priceDakheliWithoutTax']) : '');
            $CastingPriceBC = (strlen(trim($res[0]['CastingPriceBC'])) > 0 ? number_format($res[0]['CastingPriceBC']) : '');
            $CastMachPriceBM = (strlen(trim($res[0]['CastMachPriceBM'])) > 0 ? number_format($res[0]['CastMachPriceBM']) : '');
            $plasticPlate = (strlen(trim($res[0]['plasticPlate'])) > 0 ? number_format($res[0]['plasticPlate']) : '');

            $result = array("pid"=>$pid,"pCode"=>$res[0]['pCode'],"pName"=>$res[0]['pName'],"pUnit"=>$res[0]['pUnit'],
                            "currencyID"=>intval($res[0]['currencyID']),"currency_amount"=>$res[0]['currency_amount'],"priceDakheli"=>$priceDakheli,
                            "Percentage"=>$res[0]['Percentage_additional_costs'],"PercentageACD"=>$res[0]['PercentageACD'],"PriceRial"=>$pp,
                            "description"=>$res[0]['description'],"CastingPriceBC"=>$CastingPriceBC,"PercentFuelWeightBC"=>$res[0]['PercentFuelWeightBC'],
                            "CastMachPriceBM"=>$CastMachPriceBM,"PercentFuelWeightBM"=>$res[0]['PercentFuelWeightBM'],
                            "priceBasis"=>$res[0]['priceBasis'],"Supplier"=>$res[0]['Supplier'],"priceCatchDate"=>(strtotime($res[0]['priceCatchDate']) > 0 ? $ut->greg_to_jal($res[0]['priceCatchDate']) : ''),
                            "Collection"=>$res[0]['Collection_name'],"Subset_name"=>$res[0]['Subset_name'],"Latin"=>$res[0]['Latin_title'],
                            "Technical"=>$res[0]['Technical_Specifications'],"material"=>$res[0]['material'],"How_supply"=>$res[0]['ChangingHow_supply'],
                            "montageCode"=>$res[0]['montageCode'],"fsc"=>$res[0]['first_stage_construction'],"dimensions"=>$res[0]['Custom_dimensions'],
                            "Wmaterials"=>$res[0]['Weight_materials'],"WMachining"=>$res[0]['Weight_Machining'],"WFinal"=>$res[0]['Weight_Final'],
                            "Systemweight"=>$res[0]['System_weight'],"externalSize"=>$res[0]['external_size_bullion'],"PBW"=>$res[0]['PrimaryBaseWeight'],
                            "FBW"=>$res[0]['FinalBaseWeight'],"MBW"=>$res[0]['MachiningBaseWeight'],"LoadPolish"=>$res[0]['LoadPolish'],
                            "referenceECode"=>$res[0]['referenceECode'],"RawMaterialCode"=>$res[0]['RawMaterialCode'],"monthkickback"=>$res[0]['monthkickback'],
							"plasticPlate"=>$plasticPlate,"PercentagePP"=>$res[0]['PercentagePP'],"tax"=>$rsq[0]['tax'],"priceDakheliWithoutTax"=>$priceDakheliWithoutTax
                            );
            return $result;
        }else{
            return false;
        }
    }

    public function createPiece($Pname,$Pcode,$Punit,$Collection,$Subname,$Material,$Latin,$montageCode,$Howsupply,
                                $Technical,$RECode,$FSC,$RMCode,$dimensions,$ExternalSize,$Wmaterials,$WMachining,
                                $WFinal,$PBW,$MBW,$FBW,$Systemweight,$LoadPolish,$desc){
        $acm = new acm();
        if (!$acm->hasAccess('managePiece')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        mysqli_autocommit($db->Getcon(),FALSE);

        if (intval($Howsupply) == 12){
            $BType = 2;
        }elseif (strlen(trim($ExternalSize)) == 0){
            $BType = 'NULL';
        }elseif (intval($ExternalSize) < 14){
            $BType = 0;
        }else{
            $BType = 1;
        }
        $ExternalSize = (strlen(trim($ExternalSize)) == 0 ? 'NULL' : number_format($ExternalSize,1,'.',''));  // اندازه خارجی شمش

        $qu = "SELECT MAX(Row) AS `Mrow` FROM `piece`";
        $result = $db->ArrayQuery($qu);
        $Row = intval($result[0]['Mrow'])+1;

        $sql = "INSERT INTO `piece` (`pCode`,`pName`,`pUnit`,`description`,`Row`)
                VALUES ('{$Pcode}','{$Pname}','{$Punit}','{$desc}',{$Row})";
        $res = $db->Query($sql);
        if (intval($res) > 0) {
            $id = $db->InsertrdID();
            $sql1 = "INSERT INTO `piece_masterlist` (`pid`,`Collection_name`,`Subset_name`,`Latin_title`,`Technical_Specifications`,
                                                     `material`,`FixHow_supply`,`ChangingHow_supply`,`montageCode`,`first_stage_construction`,
                                                     `Custom_dimensions`,`Weight_materials`,`Weight_Machining`,`Weight_Final`,`System_weight`,
                                                     `external_size_bullion`,`PrimaryBaseWeight`,`FinalBaseWeight`,MachiningBaseWeight,
                                                     `LoadPolish`,`TypeBullion`,`referenceECode`,`RawMaterialCode`)
                                                     VALUES ({$id},'{$Collection}','{$Subname}','{$Latin}','{$Technical}',
                                                            '{$Material}',{$Howsupply},{$Howsupply},'{$montageCode}','{$FSC}',
                                                            '{$dimensions}','{$Wmaterials}','{$WMachining}','{$WFinal}','{$Systemweight}',
                                                             {$ExternalSize},{$PBW},{$FBW},{$MBW},{$LoadPolish},{$BType},'{$RECode}','{$RMCode}')";
            $res1 = $db->Query($sql1);
            if (intval($res1) > 0){
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

    public function editPiece($pid,$Pname,$Punit,$Collection,$Subname,$Material,$Latin,$PriceDakheli,$PriceDakheliWithTax,$PACD,$CastingPrice,
                              $PFW,$CastMachPrice,$PFWBM,$CurrencyType,$CurrencyAmount,$PACV,$montageCode,$Howsupply,$Technical,
                              $RECode,$FSC,$RMCode,$dimensions,$ExternalSize,$Wmaterials,$WMachining,$WFinal,$PBW,
                              $MBW,$FBW,$Systemweight,$LoadPolish,$PriceBasis,$Supplier,$CatchDate,$desc,$Kickback,$PlasticPlate,$PercentageP){
        $acm = new acm();
        if (!$acm->hasAccess('managePiece')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $report = new Report();
        mysqli_autocommit($db->Getcon(),FALSE);

        if (intval($Howsupply) == 12){
            $BType = 2;
        }elseif (strlen(trim($ExternalSize)) == 0){
            $BType = 'NULL';
        }elseif (intval($ExternalSize) < 14){
            $BType = 0;
        }else{
            $BType = 1;
        }
        $ExternalSize = (strlen(trim($ExternalSize)) == 0 ? 'NULL' : number_format($ExternalSize,1,'.',''));  // اندازه خارجی شمش
        if(intval($CurrencyType) > 0){  // اگر خرید خارجی بود
            $query = "SELECT `currencyName` FROM `currency` WHERE `RowID`={$CurrencyType}";
            $result = $db->ArrayQuery($query);
            $CurrencyName = $result[0]['currencyName'];
        }else{
            $CurrencyName = '';
            $CurrencyType = 'NULL';
            $CurrencyAmount = 'NULL';
            $PACV = 'NULL';
        }
		////$//ut->fileRecorder('test1:'.$PriceDakheli);
        if(intval($PriceDakheli) == 0){  // اگر خرید داخلی بود
			////$//ut->fileRecorder('خرید داخلی نبود');
            $PACD = 'NULL';
            $PriceDakheli = 'NULL';
            $PriceDakheliWithTax = 'NULL';
        }
        $CastingPrice = (strlen(trim($CastingPrice)) > 0 ? $CastingPrice : 'NULL');
        $PFW = (strlen(trim($PFW)) > 0 ? $PFW : 'NULL');
        $CastMachPrice = (strlen(trim($CastMachPrice)) > 0 ? $CastMachPrice : 'NULL');
        $PFWBM = (strlen(trim($PFWBM)) > 0 ? $PFWBM : 'NULL');
        $PlasticPlate = (strlen(trim($PlasticPlate)) > 0 ? $PlasticPlate : 'NULL');
        $PercentageP = (strlen(trim($PercentageP)) > 0 ? $PercentageP : 'NULL');
        $Kickback = (strlen(trim($Kickback)) > 0 ? $Kickback : 'NULL');
        $CatchDate = (strlen(trim($CatchDate)) > 0 ? $ut->jal_to_greg($CatchDate) : NULL);

        $sql = "SELECT `currency_amount`,`Percentage_additional_costs`,`PercentageACD`,`CastingPriceBC`,`PercentFuelWeightBC`,`CastMachPriceBM`,
                       `PercentFuelWeightBM`,`FixHow_supply`,`external_size_bullion`,`monthkickback`,`plasticPlate`,`PercentagePP`,`priceDakheli`,`TypeBullion`,
                       `Weight_materials`,`Weight_Machining`,`Weight_Final`,`System_weight`,`pName`,`pUnit`,`currencyName`,`Collection_name`,
                       `Subset_name`,`material`,`montageCode`,`Custom_dimensions`,`referenceECode`,`RawMaterialCode`,`priceBasis`,`Supplier`,`priceCatchDate`
                        FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                        WHERE `piece`.`RowID`={$pid}";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $keys = array_keys($res[0]);
            $fieldsNumeric = array($CurrencyAmount,$PACV,$PACD,$CastingPrice,$PFW,$CastMachPrice,$PFWBM,$Howsupply,$ExternalSize,$Kickback,$PlasticPlate,$PercentageP,$PriceDakheliWithTax,$BType);
            $cntFN = count($fieldsNumeric);

            $fieldsString = array($Wmaterials,$WMachining,$WFinal,$Systemweight,$Pname,$Punit,$CurrencyName,$Collection,$Subname,$Material,$montageCode,$dimensions,$RECode,$RMCode,$PriceBasis,$Supplier);
            $cntFS = count($fieldsString);

            $fieldsDate = array($CatchDate);
            $cntFD = count($fieldsDate);

            $y = 0;
            $backupKeys = array();
            $PreviousValue = array();
            $currentValue = array();
            for ($i=0;$i<$cntFN;$i++){  // بررسی فیلدهای عددی
                if (floatval($res[0]["$keys[$y]"]) != floatval($fieldsNumeric[$i])){
                    $backupKeys[] = $keys[$y];
                    $PreviousValue[] = $res[0]["$keys[$y]"];
                    $currentValue[] = (floatval($fieldsNumeric[$i]) > 0 ? $fieldsNumeric[$i] : '');
                }
                $y++;
            }
            for ($i=0;$i<$cntFS;$i++){  // بررسی فیلد های رشته ای
                if (trim($res[0]["$keys[$y]"]) != trim($fieldsString[$i])){
                    $backupKeys[] = $keys[$y];
                    $PreviousValue[] = $res[0]["$keys[$y]"];
                    $currentValue[] = $fieldsString[$i];
                }
                $y++;
            }
            for ($i=0;$i<$cntFD;$i++){  // بررسی فیلد های تاریخ
                if (strtotime($res[0]["$keys[$y]"]) < 0 || strtotime($fieldsDate[$i]) < 0){
                    continue;
                }
                if (strtotime($res[0]["$keys[$y]"]) != strtotime($fieldsDate[$i])){
                    $backupKeys[] = $keys[$y];
                    $PreviousValue[] = $res[0]["$keys[$y]"];
                    $currentValue[] = (intval(strtotime($fieldsDate[$i])) > 0 ? $fieldsDate[$i] : '0000-00-00');
                }
                $y++;
            }

            $sqlPU = "UPDATE `piece` SET `pName`='{$Pname}',`pUnit`='{$Punit}',`currencyName`='{$CurrencyName}',`currencyID`={$CurrencyType},`currency_amount`={$CurrencyAmount},
                                         `Percentage_additional_costs`={$PACV},`PercentageACD`={$PACD},`priceDakheli`={$PriceDakheliWithTax},`priceDakheliWithoutTax`={$PriceDakheli},`CastingPriceBC`={$CastingPrice},`PercentFuelWeightBC`={$PFW},
                                         `CastMachPriceBM`={$CastMachPrice},`PercentFuelWeightBM`={$PFWBM},`priceBasis`='{$PriceBasis}',`Supplier`='{$Supplier}',`priceCatchDate`='{$CatchDate}',
                                         `description`='{$desc}',`monthkickback`={$Kickback},`plasticPlate`={$PlasticPlate},`PercentagePP`={$PercentageP} WHERE `RowID`={$pid}";
		   
            $db->Query($sqlPU);
            $resPU = $db->AffectedRows();
            //$ut->fileRecorder('sqlPU:'.$resPU);
            $resPU = ((intval($resPU) == -1) ? 0 : 1);
            if (intval($resPU)){
                $sqlPMU = "UPDATE `piece_masterlist` SET 
                                  `Collection_name`='{$Collection}',`Subset_name`='{$Subname}',`Latin_title`='{$Latin}',
                                  `Technical_Specifications`='{$Technical}',`material`='{$Material}',`ChangingHow_supply`={$Howsupply},`FixHow_supply`={$Howsupply},
                                  `montageCode`='{$montageCode}',`first_stage_construction`='{$FSC}',`Custom_dimensions`='{$dimensions}',
                                  `Weight_materials`='{$Wmaterials}',`Weight_Machining`='{$WMachining}',`Weight_Final`='{$WFinal}',
                                  `System_weight`='{$Systemweight}',`external_size_bullion`={$ExternalSize},`LoadPolish`={$LoadPolish},
                                  `PrimaryBaseWeight`={$PBW},`FinalBaseWeight`={$FBW},`MachiningBaseWeight`={$MBW},`TypeBullion`={$BType},
                                  `referenceECode`='{$RECode}',`RawMaterialCode`='{$RMCode}' WHERE `pid`={$pid}";
              
             // $ut->fileRecorder('sqlPMU:'.$sqlPMU);
                $db->Query($sqlPMU);
                $resPMU = $db->AffectedRows();
               
                $resPMU = ((intval($resPMU) == -1) ? 0 : 1);
               // $ut->fileRecorder('resPMU:'.$resPMU);
                if (intval($resPMU)>0){
                    if (count($backupKeys) > 0){  // یعنی اگر تغییری اعمال شده بود
                        $countBackupKeys = count($backupKeys);
                        $insertValue = array();
                        for ($i=0;$i<$countBackupKeys;$i++){
                            $sqlSFA = "SELECT `FaName` FROM `en_to_fa` WHERE `EnName`='{$backupKeys[$i]}'";
                            $rst = $db->ArrayQuery($sqlSFA);
                            $insertValue[] = '('.$pid.',"'.$backupKeys[$i].'","'.$rst[0]['FaName'].'","'.$currentValue[$i].'","'.$PreviousValue[$i].'","'.date('Y/m/d').'",'.$_SESSION['userid'].')';
                        }
                        $insertValue = implode(',',$insertValue);
                        $sqlBP = "INSERT INTO `backup_piece` (`pid`,`fieldName`,`fieldName_Fa`,`currentValue`,`previousValue`,`changeDate`,`uid`) VALUES ".$insertValue." ";
                        $resBP = $db->Query($sqlBP);
                        if (intval($resBP) > 0){
                            $update = $report->updatePiecePrice($db);
							
                            if ($update == false || intval($update) == -1 || intval($update) == -2) {
                                mysqli_rollback($db->Getcon());
								//$ut->fileRecorder("1***".$update);
								//$//ut->fileRecorder($update);
                                return false;
                            }else{
                                $gupdate = $report->updateGoodPrice($db);
                                if ($gupdate == false){
                                    mysqli_rollback($db->Getcon());
									//$ut->fileRecorder("2");
                                return false;
                                   // return false;
                                }else{
                                    mysqli_commit($db->Getcon());
									//$ut->fileRecorder("3");
                                    return true;
                                }
                            }
                        }else{
                            mysqli_rollback($db->Getcon());
							//$ut->fileRecorder("4");
                                return false;
                           // return false;
                        }
                    }else{
						//$ut->fileRecorder("5");
                                return false;
                        //return false;
                    }
                }else{
                    mysqli_rollback($db->Getcon());
								//$//ut->fileRecorder("6");
                                return false;
                   // return false;
                }
            }else{
								//$//ut->fileRecorder("7");
                                return false;
               // return false;
            }
        }else{
			//$//ut->fileRecorder("8");
                                return false;
            //return false;
        }
    }

    public function addFileToPiece($pid,$MapNumber,$photo,$pdf,$opc){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $flag = true;
        $db = new DBi();
        mysqli_autocommit($db->Getcon(),FALSE);
        if (strlen(trim($MapNumber)) <= 0){
            $query = "SELECT `mapNumber` FROM `piece` WHERE `RowID`={$pid}";
            $rst = $db->ArrayQuery($query);
            $MapNumber = $rst[0]['mapNumber'];
        }

        if (isset($pdf) && intval($pdf['size']) > 0) {
            $pieceFile = "map".$pid.".pdf";
            $sql = "UPDATE `piece_masterlist` SET `mapNumber`='{$MapNumber}',`mapFileName`='{$pieceFile}' WHERE `RowID`={$pid}";
            $db->Query($sql);
            $res = $db->AffectedRows();
            $res = ((intval($res) == -1) ? 0 : 1);
            if (intval($res)){
                $pdf['name'] = $pieceFile;
                $upload = move_uploaded_file($pdf["tmp_name"],'../mapFile/'.$pdf["name"]);
                if(!$upload){
                    $flag = false;
                }
            }else{
                $flag = false;
            }
        }
        if (isset($photo) && intval($photo['size']) > 0) {
            $pieceImage = "piece".$pid.".jpg";
            $sql = "UPDATE `piece_masterlist` SET `mapNumber`='{$MapNumber}',`pieceImageName`='{$pieceImage}' WHERE `RowID`={$pid}";
            $db->Query($sql);
            $res = $db->AffectedRows();
            $res = ((intval($res) == -1) ? 0 : 1);
            if (intval($res)){
                $photo['name'] = $pieceImage;
                $upload1 = move_uploaded_file($photo["tmp_name"],'../pieceImage/'.$photo["name"]);
                if(!$upload1){
                    $flag = false;
                }
            }else{
                $flag = false;
            }
        }
        if (isset($opc) && intval($opc['size']) > 0) {
            $opcFile = "OPC".$pid.".pdf";
            $sql = "UPDATE `piece_masterlist` SET `mapNumber`='{$MapNumber}',`opcFileName`='{$opcFile}' WHERE `RowID`={$pid}";
            $db->Query($sql);
            $res = $db->AffectedRows();
            $res = ((intval($res) == -1) ? 0 : 1);
            if (intval($res)){
                $opc['name'] = $opcFile;
                $upload2 = move_uploaded_file($opc["tmp_name"],'../opcFile/'.$opc["name"]);
                if(!$upload2){
                    $flag = false;
                }
            }else{
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

    public function uploadPMListFile($pmlist){
        $acm = new acm();
        $ut=new Utility();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)).'/pmlexcel/excPML.xlsx';
        unlink($file_to_delete);
        

        if (isset($pmlist) && intval($pmlist['size']) > 0) 
        {
            $sql = "DROP TABLE IF EXISTS `masterlist`";
            $db->Query($sql);
            $pmlFile = "excPML.xlsx";
            $pmlist['name'] = $pmlFile;
            $upload = move_uploaded_file($pmlist["tmp_name"],'../pmlexcel/'.$pmlist["name"]);
            $ut->fileRecorder('upload');
           // $ut->fileRecorder($pmlist);
            if(!$upload){
               
                return false;
            }else{

                $result = $this->createPieceMasterListDB();
                if ($result){
                    return true;
                }else{
                    return false;
                }
            }
        }
        else
        {
            return false;
        }
    }

    private function createPieceMasterListDB(){
        $inputFileName = '../pmlexcel/excPML.xlsx';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($inputFileName);

        $db = new DBi();

        $sql = "DROP TABLE IF EXISTS `masterlist`";
        $db->Query($sql);

        $flag = true;
        $rowIterator = $spreadsheet->getActiveSheet()->getRowIterator();
        $skip_rows = 0;
        $excell_array_data = array();
        foreach($rowIterator as $row){
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            if($skip_rows >= $row->getRowIndex()) continue;
            $rowIndex = $row->getRowIndex();
            $excell_array_data[$rowIndex] = array();

            foreach ($cellIterator as $cell) {
                $x = str_replace('"', '', $cell->getCalculatedValue());
                $y = str_replace("'", "", $x);
                if ($cell->getColumn() == 'D'){
                    if (strlen(trim($y)) == 0){
                        $y = '###';
                    }
                }
                $excell_array_data[$rowIndex][$cell->getColumn()] = $y;
            }
        }

        //Create Database table with one Field
        $sql = "CREATE TABLE `masterlist` (`RowID` INT NOT NULL PRIMARY KEY AUTO_INCREMENT)";
        $aff = $db->Query($sql);
        if (intval($aff) <= 0){
            $flag = false;
        }

        //Create Others Field (A, B, C & ...)
        $columns_name = $excell_array_data[$skip_rows+1];
        foreach (array_keys($columns_name) as $fieldname ){
            $sql1 = "ALTER TABLE `masterlist` ADD $fieldname VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin";
            $aff1 = $db->Query($sql1);
            if (intval($aff1) <= 0){
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
                //$insertValue[] = "('".implode(array_values($chunk[$i][$j]), "','")."')";
                $insertValue[] = "('".implode( "','",array_values($chunk[$i][$j]))."')";
            }
            $insertValue = implode(',',$insertValue);
            $sql2 = "INSERT INTO `masterlist` ($keys) VALUES ".$insertValue." ";
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

    public function uploadAgainPieceMasterList(){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        mysqli_autocommit($db->Getcon(),FALSE);

        $sql = "SELECT * FROM `masterlist` ORDER BY `RowID` ASC ";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $flag = true;
		//$//ut->fileRecorder('ssssqqqqqllll1:'.$sql);
        $qext = "SELECT `RowID` FROM `piece`";
		//$//ut->fileRecorder('ssssqqqqqllll2222:'.$qext);
        $rqext = $db->ArrayQuery($qext);
        if (count($rqext) > 0){      // قبلا قطعات ثبت شده است
            $keys = array('FixHow_supply','external_size_bullion','TypeBullion','Weight_materials','Weight_Machining','Weight_Final','System_weight','pName','pUnit','Collection_name','Subset_name','material','montageCode','Custom_dimensions','referenceECode','RawMaterialCode','HPCode');
            for ($i=0;$i<$cnt;$i++){
                $q = "SELECT `piece`.`RowID`,`FixHow_supply`,`external_size_bullion`,`TypeBullion`,`Weight_materials`,`Weight_Machining`,`Weight_Final`,`System_weight`,`pName`,`pUnit`,
                             `Collection_name`,`Subset_name`,`material`,`montageCode`,`Custom_dimensions`,`referenceECode`,`RawMaterialCode`,`HPCode`
                             FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                             WHERE `pCode`='{$res[$i]['A']}'";
                $rstq = $db->ArrayQuery($q);

                if (strpos($res[$i]['M'], 'P') !== false || strpos($res[$i]['M'], 'p') !== false) {
                    $mc = $res[$i]['M'];  // کد مونتاژی
                    $res[$i]['M'] = 14;  // از نوع قطعه مونتاژی
                } else {
                    $mc = '';
                }

                if (intval($res[$i]['M']) == 12){  // نوع شمش را مشخص می کند
                    $tb = 2;
                }elseif (strlen(trim($res[$i]['R'])) <= 0){
                    $tb = 'NULL';
                }elseif (intval($res[$i]['R']) < 14){
                    $tb = 0;
                }else{
                    $tb = 1;
                }
                $res[$i]['R'] = (strlen(trim($res[$i]['R'])) == 0 ? 'NULL' : number_format($res[$i]['R'],1,'.',''));

                if (count($rstq) > 0) { // قطعه قبلا وجود دارد
                    $fieldsNumeric = array($res[$i]['M'],$res[$i]['R'],$tb);
                    $cntFN = count($fieldsNumeric);

                    $fieldsString = array($res[$i]['T'], $res[$i]['U'], $res[$i]['V'], $res[$i]['S'],$res[$i]['D'], $res[$i]['K'], $res[$i]['B'], $res[$i]['C'],
                                          $res[$i]['L'], $mc, $res[$i]['P'], $res[$i]['J'], $res[$i]['Q'], $res[$i]['F']);
                    $cntFS = count($fieldsString);

                    $y = 0;
                    $backupKeys = array();
                    $PreviousValue = array();
                    $currentValue = array();
                    for ($j = 0; $j < $cntFN; $j++) {  // بررسی فیلدهای عددی
                        if (floatval($rstq[0]["$keys[$y]"]) != floatval($fieldsNumeric[$j])) {
                            $backupKeys[] = $keys[$y];
                            $PreviousValue[] = $rstq[0]["$keys[$y]"];
                            $currentValue[] = (floatval($fieldsNumeric[$j]) > 0 ? $fieldsNumeric[$j] : '');
                        }
                        $y++;
                    }
                    for ($k = 0; $k < $cntFS; $k++) {  // بررسی فیلد های رشته ای
                        if (trim($rstq[0]["$keys[$y]"]) != trim($fieldsString[$k])) {
                            $backupKeys[] = $keys[$y];
                            $PreviousValue[] = $rstq[0]["$keys[$y]"];
                            $currentValue[] = $fieldsString[$k];
                        }
                        $y++;
                    }

                    if (count($backupKeys) > 0) {  // اگر تغییری کرده بود
                        $res[$i]['D'] = $db->Escape($res[$i]['D']);
                        $res[$i]['G'] = $db->Escape($res[$i]['G']);
                        $res[$i]['H'] = $db->Escape($res[$i]['H']);
                        $res[$i]['I'] = $db->Escape($res[$i]['I']);
                        $res[$i]['P'] = $db->Escape($res[$i]['P']);
                        $sqlPU = "UPDATE `piece` SET `pName`='{$res[$i]['D']}',`pUnit`='{$res[$i]['K']}',`description`='{$res[$i]['I']}' WHERE `RowID`={$rstq[0]['RowID']}";
                        $db->Query($sqlPU);
                        $resPU = $db->AffectedRows();
                        $resPU = ((intval($resPU) == -1) ? 0 : 1);
                        if (intval($resPU)) {
                            $sqlPMU = "UPDATE `piece_masterlist` SET `Collection_name`='{$res[$i]['B']}',`Subset_name`='{$res[$i]['C']}',`Latin_title`='{$res[$i]['G']}',`Technical_Specifications`='{$res[$i]['H']}',
                                                                     `material`='{$res[$i]['L']}',`ChangingHow_supply`={$res[$i]['M']},`FixHow_supply`={$res[$i]['M']},`montageCode`='{$mc}',`first_stage_construction`='{$res[$i]['O']}',
                                                                     `Custom_dimensions`='{$res[$i]['P']}',`Weight_materials`='{$res[$i]['T']}',`Weight_Machining`='{$res[$i]['U']}',`Weight_Final`='{$res[$i]['V']}',
                                                                     `System_weight`='{$res[$i]['S']}',`external_size_bullion`={$res[$i]['R']},`referenceECode`='{$res[$i]['J']}',`RawMaterialCode`='{$res[$i]['Q']}',`TypeBullion`={$tb},`HPCode`='{$res[$i]['F']}'
                                                                 WHERE `pid`={$rstq[0]['RowID']}";

                            $db->Query($sqlPMU);
                            $resPMU = $db->AffectedRows();
                            $resPMU = ((intval($resPMU) == -1) ? 0 : 1);
                            if (intval($resPMU)) {
                                $countBackupKeys = count($backupKeys);
                                $insertValue = array();
                                for ($a=0;$a<$countBackupKeys;$a++){
                                    $sqlSFA = "SELECT `FaName` FROM `en_to_fa` WHERE `EnName`='{$backupKeys[$a]}'";
                                    $rst = $db->ArrayQuery($sqlSFA);
                                    $insertValue[] = '('.$rstq[0]['RowID'].',"'.$backupKeys[$a].'","'.$rst[0]['FaName'].'","'.$currentValue[$a].'","'.$PreviousValue[$a].'","'.date('Y/m/d').'",'.$_SESSION['userid'].')';
                                }
                                $insertValue = implode(',',$insertValue);
                                $sqlBP = "INSERT INTO `backup_piece` (`pid`,`fieldName`,`fieldName_Fa`,`currentValue`,`previousValue`,`changeDate`,`uid`) VALUES ".$insertValue." ";
                                $resBP = $db->Query($sqlBP);

                                if (intval($resBP) < 0){
                                    $flag = false;
                                }
                            }else{
                                $flag = false;
                            }
                        }else{
                            $flag = false;
                        }
                    }else{
                        continue;
                    }
                }else{  //++++++++++++++++++++ قطعه قبلا وجود ندارد ++++++++++++++++++++
                    $res[$i]['D'] = $db->Escape($res[$i]['D']);
                    $res[$i]['G'] = $db->Escape($res[$i]['G']);
                    $res[$i]['H'] = $db->Escape($res[$i]['H']);
                    $res[$i]['I'] = $db->Escape($res[$i]['I']);
                    $res[$i]['P'] = $db->Escape($res[$i]['P']);

                    $qrow = "SELECT `Row` FROM `piece` ORDER BY `RowID` DESC LIMIT 1";
                    $rrow = $db->ArrayQuery($qrow);
                    $row = $rrow[0]['Row']+1;
					
                    $query = "INSERT INTO `piece` (`pCode`,`pName`,`pUnit`,`description`,`Row`) VALUES ('{$res[$i]['A']}','{$res[$i]['D']}','{$res[$i]['K']}','{$res[$i]['I']}',{$row})";
                    
					$result = $db->Query($query);
                    if (intval($result) > 0){
                        $id = $db->InsertrdID();
						//$//ut->fileRecorder('insert:'.$id);
                        $qq = "INSERT INTO `piece_masterlist` (`pid`,`Collection_name`,`Subset_name`,`Latin_title`,
                                                   `Technical_Specifications`,`material`,`FixHow_supply`,
                                                   `ChangingHow_supply`,`montageCode`,`first_stage_construction`,
                                                   `Custom_dimensions`,`Weight_materials`,`Weight_Machining`,
                                                   `Weight_Final`,`System_weight`,`external_size_bullion`,
                                                   `mapNumber`,`TypeBullion`,`referenceECode`,`RawMaterialCode`,`HPCode`
                                                   )
                                                   VALUES 
                                                   ({$id},'{$res[$i]['B']}','{$res[$i]['C']}','{$res[$i]['G']}',
                                                   '{$res[$i]['H']}','{$res[$i]['L']}','{$res[$i]['M']}',
                                                    '{$res[$i]['M']}','{$mc}','{$res[$i]['O']}',
                                                   '{$res[$i]['P']}','{$res[$i]['T']}','{$res[$i]['U']}',
                                                   '{$res[$i]['V']}','{$res[$i]['S']}',{$res[$i]['R']},
                                                   '{$res[$i]['E']}',{$tb},'{$res[$i]['J']}','{$res[$i]['Q']}','{$res[$i]['F']}'
                                                   )";
                        //$//ut->fileRecorder('insert:'.$qq);
						$result = $db->Query($qq);
                        if (intval($result) <= 0){
                            $flag = false;
                        }
                    }else{
                        $flag = false;
                    }
                }
            } //FOR
        }else{       // هیچ قطعه ای ثبت نشده است
            for ($i=0;$i<$cnt;$i++){
                $row = $i+4;
                if (strpos($res[$i]['M'], 'P') !== false || strpos($res[$i]['M'], 'p') !== false) {
                    $mc = $res[$i]['M'];  // کد مونتاژی
                    $res[$i]['M'] = 14;  // از نوع قطعه مونتاژی
                } else {
                    $mc = '';
                }

                if (intval($res[$i]['M']) == 12){  // نوع شمش را مشخص می کند
                    $tb = 2;
                }elseif (strlen(trim($res[$i]['R'])) <= 0){
                    $tb = 'NULL';
                }elseif (intval($res[$i]['R']) < 14){
                    $tb = 0;
                }else{
                    $tb = 1;
                }

                $res[$i]['D'] = $db->Escape($res[$i]['D']);
                $res[$i]['G'] = $db->Escape($res[$i]['G']);
                $res[$i]['H'] = $db->Escape($res[$i]['H']);
                $res[$i]['I'] = $db->Escape($res[$i]['I']);
                $res[$i]['P'] = $db->Escape($res[$i]['P']);
                $res[$i]['R'] = (strlen(trim($res[$i]['R'])) == 0 ? 'NULL' : $res[$i]['R']);  // اندازه خارجی شمش

                $query = "INSERT INTO `piece` (`pCode`,`pName`,`pUnit`,`description`,`Row`) VALUES ('{$res[$i]['A']}','{$res[$i]['D']}','{$res[$i]['K']}','{$res[$i]['I']}',{$row})";
                $result = $db->Query($query);
                if (intval($result) > 0){
                    $id = $db->InsertrdID();
                    $qq = "INSERT INTO `piece_masterlist` (`pid`,`Collection_name`,`Subset_name`,`Latin_title`,
                                                   `Technical_Specifications`,`material`,`FixHow_supply`,
                                                   `ChangingHow_supply`,`montageCode`,`first_stage_construction`,
                                                   `Custom_dimensions`,`Weight_materials`,`Weight_Machining`,
                                                   `Weight_Final`,`System_weight`,`external_size_bullion`,
                                                   `mapNumber`,`TypeBullion`,`referenceECode`,`RawMaterialCode`,`HPCode`
                                                   )
                                                   VALUES 
                                                   ({$id},'{$res[$i]['B']}','{$res[$i]['C']}','{$res[$i]['G']}',
                                                   '{$res[$i]['H']}','{$res[$i]['L']}',{$res[$i]['M']},
                                                    {$res[$i]['M']},'{$mc}','{$res[$i]['O']}',
                                                   '{$res[$i]['P']}','{$res[$i]['T']}','{$res[$i]['U']}',
                                                   '{$res[$i]['V']}','{$res[$i]['S']}',{$res[$i]['R']},
                                                   '{$res[$i]['E']}',{$tb},'{$res[$i]['J']}','{$res[$i]['Q']}','{$res[$i]['F']}'
                                                   )";
                    $result = $db->Query($qq);
                    if (intval($result) <= 0){
                        //$//ut->fileRecorder($query);
                        //$//ut->fileRecorder($qq);
                        $flag = false;
                    }
                }else{
                    $flag = false;
                }
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

    public function checkFileExist($pid){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `mapNumber`,`Latin_title` FROM `piece_masterlist` WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        if (strlen(trim($res[0]['mapNumber'])) > 0 && strlen(trim($res[0]['Latin_title'])) > 0){
            $drawingPath = 'Engineering Data/Documents/Part Documents/Drawing/';
            $path = $drawingPath.$res[0]['mapNumber'].'-'.$res[0]['Latin_title'].'.pdf';
            return ADDR.$path;
        }else{
            return false;
        }
    }

    public function checkOPCFileExist($pid){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `mapNumber`,`Latin_title` FROM `piece_masterlist` WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        if (strlen(trim($res[0]['mapNumber'])) > 0 && strlen(trim($res[0]['Latin_title'])) > 0){
            $opcPath = 'Engineering Data/Documents/Part Documents/OPC/';
            $path = $opcPath.$res[0]['mapNumber'].'-OPC '.$res[0]['Latin_title'].'.pdf';
            return ADDR.$path;
        }else{
            return false;
        }
    }

    public function checkPieceImageExist($pid){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `mapNumber`,`Latin_title` FROM `piece_masterlist` WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        if (strlen(trim($res[0]['mapNumber'])) > 0 && strlen(trim($res[0]['Latin_title'])) > 0){
            $ipPath = 'Engineering Data/Documents/Part Documents/Picture/';
            $path = $ipPath.$res[0]['mapNumber'].'-'.$res[0]['Latin_title'].'.jpg';
            return ADDR.$path;
        }else{
            return false;
        }
    }

    public function checkPieceIPExist($pid){
        $acm = new acm();
        if(!$acm->hasAccess('managePiece')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `mapNumber`,`Latin_title` FROM `piece_masterlist` WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        if (strlen(trim($res[0]['mapNumber'])) > 0 && strlen(trim($res[0]['Latin_title'])) > 0){
            $ipPath = 'Engineering Data/Documents/Part Documents/IP/';
            $path = $ipPath.$res[0]['mapNumber'].'-IP '.$res[0]['Latin_title'].'.pdf';
            return ADDR.$path;
        }else{
            return false;
        }
    }

    public function getPieces(){
        $db = new DBi();
        $sql = "SELECT `RowID`,`pCode`,`pName` FROM `piece` ";
        $res = $db->ArrayQuery($sql);
        if(count($res)>0){
            return $res;
        }else{
            return false;
        }
    }

    public function priceToRial($CurrencyType,$CurrencyAmount,$PACV){
        if (intval($CurrencyType) > 0 && floatval($CurrencyAmount) > 0){
            $db = new DBi();
            $query = "SELECT `dayRate` FROM `currency` WHERE `RowID`={$CurrencyType}";
            $rst = $db->ArrayQuery($query);
            $pp = number_format((($CurrencyAmount * $rst[0]['dayRate']) * (100 + floatval($PACV))) / 100);
        }else{
            $pp = '';
        }
        return $pp;
    }

    // public function getPieceForGeneralExcelBOM()
    // {
    //     $db = new DBi();
    //     $ut = new Utility();
    //     // $query = "SELECT pm.*,p.pName,p.errorText FROM piece_masterlist as pm LEFT JOIN piece as p ON (pm.pid= p.RowID) where p.showf=1";
    //     // $rst = $db->ArrayQuery($query);
    //     // return $rst;
    //     $get_goods_bom="SELECT M FROM masterlist where M LIKE '%P%' GROUP BY M";
    //     $montaj_codes=$db->ArrayQuery($get_goods_bom);
    //     $montaj_codes_array=[];
    //     foreach($montaj_codes as $k=>$value){
    //         $montaj_codes_array[]= "'".$value['M']."'";
    //     }
       
    //     $montaj_info=implode(",",$montaj_codes_array);
    //     //$ut->fileRecorder($momtaj_info);
    //    // WHERE i.isEnable=1 AND gm.G=2 AND m.M LIKE '%P%' ";
    //    $final_res=[];
    //    for($m=0;$m<count($montaj_codes_array);$m++){
    //         $get_goods_bom="SELECT g.HCode as g_code,g.gName,p.pName,pm.HPCode as p_code,pm.Weight_Final,pm.RawMaterialCode,pm.material,pm.first_stage_construction ,
    //         g.ggroup,p.pUnit,i.amount ,m.M,m.N,m.K,m.Q
    //         FROM interface AS i LEFT JOIN good AS g ON i.ProductCode=g.gCode 
    //         LEFT JOIN piece AS p ON i.PieceCode=p.pCode
    //         LEFT JOIN piece_masterlist AS pm ON p.RowID=pm.pid 
    //         LEFT JOIN masterlist AS m ON m.A=p.pCode
    //         LEFT JOIN `gmasterlist` as gm ON(gm.A=g.gCode)
    //         WHERE i.isEnable=1 AND gm.G=2 AND i.ProductCode IN ({$montaj_info}) AND m.Q IS NOT NULL  order by g.HCode asc";
    //         $res= $res=$db->ArrayQuery($get_goods_bom); 
    //         $final_res[]=$res;
    //    }
    //     foreach($final_res as $key=>$value){
    //         if($value['N']==1 || $value['N'==2]){}
    //         else
    //         {
    //             $res[$key]['p_code']=$value['RawMaterialCode'];
    //             $res[$key]['amount']=$value['Weight_Final'];
    //         }
    //         //$res[$key]['g_code']=$value['p_code'];
    //     }
       
    //     return $res;
    // }

    public function getPieceForGeneralExcelBOM()
    {
        $db = new DBi();
        $ut = new Utility();
        $final_array=[];
        $fileds_array=['forgingCode','machiningCode','polishingCode','nickelCode','platingCode','pushplatingCode','goldenCode','mattgoldenCode','lightgoldenCode','darkgoldenCode','paintCode','decoralCode','steelCode'];
        $fileds_array = array_reverse($fileds_array);
        $fileds_array_fa=['فورج','ماشینکاری','پرداخت','نیکل','آبکاری','آب برداری','طلایی','طلایی مات','طلایی روشن','طلایی تیره','رنگ','دکورال','استیل'];
        $fileds_array_fa = array_reverse($fileds_array_fa);
        for($i=0;$i<count($fileds_array);$i++){
            $row_matrialCode="SELECT RowID, `{$fileds_array[$i]}` FROM `piece_masterlist` WHERE `{$fileds_array[$i]}` <>'' AND  `{$fileds_array[$i]}` <>0  AND FixHow_supply NOT IN(0,13)";
            $res=$db->ArrayQuery($row_matrialCode);
            //$res=array_unique($res);
           
            foreach($res as $k=>$v){
                $handler_array_value=array_values($v);
                $handler_array['RowID']=$handler_array_value[0];
                $handler_array['pCode']=$handler_array_value[1];
                $handler_array['step_en']=$fileds_array[$i];
                $handler_array['step_fa']=$fileds_array_fa[$i];
                $final_array[]=$handler_array;
            }
        }
        $piece_sql="SELECT pm.*,m.D,m.K,m.C From piece_masterlist as pm left join masterlist as m on m.RowID=pm.pid";
        $piece_res=$db->ArrayQuery($piece_sql);
        $piece_array=[];
        foreach($piece_res as $p_k=>$p_value){
            $piece_array[$p_value['RowID']]=$p_value;
        }
        
        foreach($final_array as $k=>$value){
            $final_waight="";
            switch($final_array[$k]['step_en']){
               
                case "machiningCode":
                    $final_waight=$Weight_Machining;
                    break;

            }
            if(empty($final_waight)){
                $final_waight=$piece_array[$value['RowID']]['Weight_Final'];
            }
            if(empty($final_waight)){
                $final_waight=$piece_array[$value['RowID']]['System_weight'];
            }
            //if($final_array[$k]['step']==""){}
            //$value['Weight_Final']=$piece_array[$value['RowID']]['Weight_Final'];
            $value['Weight_Final']=$final_waight;
            $value['material']=$piece_array[$value['RowID']]['material'];
            $value['Weight_materials']=$piece_array[$value['RowID']]['Weight_materials'];
           // $value['Weight_Machining']=$piece_array[$value['RowID']]['Weight_Machining'];
           // $value['System_weight']=$piece_array[$value['RowID']]['System_weight'];
            $value['Subset_name']=$piece_array[$value['RowID']]['D'] ." - " .$final_array[$k]['step_fa'];
            $value['Custom_dimensions']=$piece_array[$value['RowID']]['Custom_dimensions'];
            $value['RawMaterialCode']=$piece_array[$value['RowID']]['RawMaterialCode'];
            $value['first_stage_construction']=$piece_array[$value['RowID']]['first_stage_construction'];
            $value['p_unit']=$piece_array[$value['RowID']]['K'];
            $value['group']=$piece_array[$value['RowID']]['C'];
            $final_array[$k]=$value;
        }
        
       return $final_array;
        
    }
}