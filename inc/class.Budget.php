<?php

class Budget{

    public function __construct(){
        // do nothing
    }

    public function getBudgetManagementHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('budgetManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $piece = new Piece();

        $brands = $piece->getBrands();
        $cntb = count($brands);

        $ggroups = $piece->getJustGGroup();
        $cntgg = count($ggroups);

        $gsgroups = $piece->getJustGSGroup();
        $cntgsg = count($gsgroups);

        $gseries = $piece->getJustGSeries();
        $cntgs = count($gseries);

        $budgetYear = $this->getAllBudgetYear();
        $cntbu = count($budgetYear);

        $budgetYearComponents = $this->getAllBudgetYearComponents();
        $cntBuDet = count($budgetYearComponents);

        $pagename = array();
        $pageIcon = array();
        $contentId = array();
        $menuItems = array();
        $bottons = array();
        $headerSearch = array();
        $access = array();
        $hiddenContentId[] = "hiddenBudgetPrintBody";

        $x = 0;
        $y = 0;
        $z = 0;
        $manifold = 0;
        if($acm->hasAccess('budgetManagement')) {
            $pagename[$x] = "بودجه فروش اولیه";
            $pageIcon[$x] = "fa-money-bill";
            $contentId[$x] = "budgetManagementBody";
            $menuItems[$x] = 'budgetManagementTabID';

            $bottons1 = array();
            $headerSearch1 = array();

            if($acm->hasAccess('editCreateBudget')) {
                $b = 0;
                $bottons1[$b]['title'] = "افزودن بودجه فروش جدید";
                $bottons1[$b]['jsf'] = "createBudget";
                $bottons1[$b]['icon'] = "fa-plus-square";
                $b++;

                $bottons1[$b]['title'] = "ویرایش بودجه فروش";
                $bottons1[$b]['jsf'] = "editBudget";
                $bottons1[$b]['icon'] = "fa-edit";
            }

            $bottons[$y] = $bottons1;
            $headerSearch[$z] = $headerSearch1;

            $manifold++;
            $access[] = 1;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('budgetComponentsManage')) {
            $pagename[$x] = "اجزای بودجه فروش";
            $pageIcon[$x] = "fa-puzzle-piece";
            $contentId[$x] = "budgetComponentsManageBody";
            $menuItems[$x] = 'budgetComponentsManageTabID';

            $bottons2 = array();
            $headerSearch2 = array();

            $b = 0;
            if($acm->hasAccess('editCreateBudgetComponents')) {
                $bottons2[$b]['title'] = "ثبت اجزای بودجه فروش";
                $bottons2[$b]['jsf'] = "createBudgetComponents";
                $bottons2[$b]['icon'] = "fa-plus-square";
                $b++;

                $bottons2[$b]['title'] = "ویرایش اجزای بودجه فروش";
                $bottons2[$b]['jsf'] = "editBudgetComponents";
                $bottons2[$b]['icon'] = "fa-edit";
                $b++;
            }

            $bottons2[$b]['title'] = "ارجاع اجزای بودجه فروش";
            $bottons2[$b]['jsf'] = "sendBudget";
            $bottons2[$b]['icon'] = "fa-paper-plane";
/*            $b++;

            $bottons2[$b]['title'] = "حرکت کن آقا";
            $bottons2[$b]['jsf'] = "replaceNewBudget";
            $bottons2[$b]['icon'] = "fa-paper-plane";*/

            if ($acm->hasAccess("excelexport")) {
                $b++;
                $bottons2[$b]['title'] = "خروجی اکسل بودجه اولیه";
                $bottons2[$b]['jsf'] = "createBudgetComponentsDetailsExcel";
                $bottons2[$b]['icon'] = "fa-file-excel";
            }

            $bottons[$y] = $bottons2;
            $headerSearch[$z] = $headerSearch2;

            $manifold++;
            $access[] = 2;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('budgetPriceManage')) {
            $pagename[$x] = "قیمت بودجه فروش";
            $pageIcon[$x] = "fa-dollar-sign";
            $contentId[$x] = "budgetPriceManageBody";
            $menuItems[$x] = 'budgetPriceManageTabID';

            $bottons3 = array();
            $headerSearch3 = array();

            $b = 0;
            if ($acm->hasAccess("excelexport")) {
                $bottons3[$b]['title'] = "خروجی اکسل قیمت بودجه";
                $bottons3[$b]['jsf'] = "getBudgetPriceManageExcel";
                $bottons3[$b]['icon'] = "fa-file-excel";
            }

            $a = 0;
            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['width'] = "160px";
            $headerSearch3[$a]['id'] = "budgetPriceManageYearSearch";
            $headerSearch3[$a]['onchange'] = "onchange=getYearBudgetComponents10()";
            $headerSearch3[$a]['title'] = "سال بودجه فروش";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = "سال بودجه فروش";
            $headerSearch3[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cntbu;$i++){
                $headerSearch3[$a]['options'][$i+1]["title"] = $budgetYear[$i]['Name'];
                $headerSearch3[$a]['options'][$i+1]["value"] = $budgetYear[$i]['RowID'];
            }
            $a++;

            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['width'] = "400px";
            $headerSearch3[$a]['id'] = "budgetPriceManageComponentSearch";
            $headerSearch3[$a]['multiple'] = "multiple";
            $headerSearch3[$a]['LimitNumSelections'] = 1;
            $headerSearch3[$a]['title'] = "انتخاب محصول";
            $headerSearch3[$a]['options'] = array();
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "100px";
            $headerSearch3[$a]['id'] = "budgetPriceManageECodeSearch";
            $headerSearch3[$a]['title'] = "کد مهندسی";
            $headerSearch3[$a]['placeholder'] = "کد مهندسی";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "150px";
            $headerSearch3[$a]['id'] = "budgetPriceManageGCodeSearch";
            $headerSearch3[$a]['title'] = "کد محصول";
            $headerSearch3[$a]['placeholder'] = "کد محصول";
            $a++;

            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['width'] = "160px";
            $headerSearch3[$a]['id'] = "budgetPriceManageBrandSearch";
            $headerSearch3[$a]['title'] = "برند محصول";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = "برند محصول";
            $headerSearch3[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cntb;$i++){
                $headerSearch3[$a]['options'][$i+1]["title"] = $brands[$i]['title'];
                $headerSearch3[$a]['options'][$i+1]["value"] = $brands[$i]['RowID'];
            }
            $a++;

            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['width'] = "160px";
            $headerSearch3[$a]['id'] = "budgetPriceManageGroupSearch";
            $headerSearch3[$a]['title'] = "گروه محصول";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = "گروه محصول";
            $headerSearch3[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cntgg;$i++){
                $headerSearch3[$a]['options'][$i+1]["title"] = $ggroups[$i]['title'];
                $headerSearch3[$a]['options'][$i+1]["value"] = $ggroups[$i]['RowID'];
            }
            $a++;

            $headerSearch3[$a]['type'] = "btn";
            $headerSearch3[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch3[$a]['jsf'] = "showBudgetPriceManageList";

            $bottons[$y] = $bottons3;
            $headerSearch[$z] = $headerSearch3;

            $manifold++;
            $access[] = 3;
        }
        $htm = $ut->getHtmlOfDefaultManagementMultiPage($pagename,$pageIcon,$contentId,$menuItems,$bottons,$manifold,$headerSearch,$hiddenContentId);
        //++++++++++++++++++++++++++++++++++ EDIT CREATE MODAL ++++++++++++++++++++++++++++++++
        $modalID = "budgetManagementModal";
        $modalTitle = "فرم ایجاد/ویرایش بودجه فروش";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "budgetManagementYear";
        $items[$c]['style'] = "style='width: 50%;'";
        $items[$c]['title'] = "بودجه فروش سال";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        $x = 1401;
        for ($i=0;$i<600;$i++){
            $items[$c]['options'][$i+1]["title"] = $x;
            $items[$c]['options'][$i+1]["value"] = $x;
            $x++;
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "budgetManagementValidDate";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ اعتبار";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "budgetManagementDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetManagementHiddenBid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ show Goods Without Budget MODAL ++++++++++++++++++++++++++++++++
        $modalID = "showGoodsWithoutBudgetModal";
        $modalTitle = "محصولات فاقد بودجه فروش";
        $style = 'style="max-width: 1190px;"';
        $ShowDescription = 'showGoodsWithoutBudget-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showGoodsWithoutBudget = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF show Goods Without Budget MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++ Budget Add Attachment File Modal ++++++++++++++++++++++
        $modalID = "attachmentFileToBudgetModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'attachmentFileToBudget-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "attachmentFileToBudgetName";
        $items[$c]['title'] = "نام فایل";
        $items[$c]['placeholder'] = "نام";
        $c++;
       
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "budegtDetailesSelect";
        $items[$c]['title'] = "انتخاب جزییات بودجه فروش";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"]  = "انتخاب جزییات بودجه فروش";
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntBuDet;$i++){//-------------------------------   جزییات بودجه فروش   -----------------------------------
            $items[$c]['options'][$i+1]["title"] = $budgetYearComponents[$i]['Name'];
            $items[$c]['options'][$i+1]["value"] = $budgetYearComponents[$i]['RowID'];
        }
        $items[$c]['option'][0] = "انتخاب جزییات بودجه فروش";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "attachmentFileToBudgetFile";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF, JPG , PNG , XLSX , DOCX باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "attachmentFileToBudgetID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachFileToBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $attachmentFileToBudgetModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Budget Add Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show Budget Components Details Final Modal ++++++++++++++++++++++
        $modalID = "showBudgetComponentsDetailsFinalModal";
        $modalTitle = " جزئیات نهایی اجزای بودجه فروش";
        $ShowDescription = 'showBudgetComponentsDetailsFinal-body';
        $style = 'style="max-width: 1366px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showBudgetComponentsDetailsFinal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show Budget Components Details Final Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show Budget Components Final Modal ++++++++++++++++++++++
        $modalID = "showBudgetComponentsFinalModal";
        $modalTitle = "اجزای بودجه فروش نهایی";
        $ShowDescription = 'showBudgetComponentsFinal-body';
        $style = 'style="max-width: 1000px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showBudgetComponentsFinal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show Budget Components Final Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ CREATE MODAL ++++++++++++++++++++++++++++++++
        $modalID = "budgetComponentsManageModal";
        $modalTitle = "فرم ایجاد اجزای بودجه فروش";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "budgetComponentsManageYear";
        $items[$c]['title'] = "بودجه فروش سال";
        $items[$c]['style'] = "style='width: 70%;'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntbu;$i++){
            $items[$c]['options'][$i+1]["title"] = $budgetYear[$i]['Name'];
            $items[$c]['options'][$i+1]["value"] = $budgetYear[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "budgetComponentsManageName";
        $items[$c]['title'] = "نام";
        $items[$c]['placeholder'] = "مثال : لوله های 5 لایه، اتصالات، شیرآلات ابرش و ...";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "budgetComponentsManageDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetComponentsManageHiddenID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateBudgetComponents";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createBudgetComponents = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF CREATE MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ CREATE MODAL ++++++++++++++++++++++++++++++++
        $modalID = "budgetComponentsDetailsModal";
        $modalTitle = "فرم ایجاد جزئیات اجزا بودجه فروش";
        $style = 'style="max-width: 1700px;"';
        $ShowDescription = 'budgetComponentsDetails-body';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "budgetComponentsDetailsBrand";
        $items[$c]['title'] = "برند محصول";
        $items[$c]['style'] = "style='width: 50%;'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntb;$i++){
            $items[$c]['options'][$i+1]["title"] = $brands[$i]['title'];
            $items[$c]['options'][$i+1]["value"] = $brands[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "budgetComponentsDetailsGGroup";
        $items[$c]['title'] = "گروه محصول";
        $items[$c]['style'] = "style='width: 50%;'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntgg;$i++){
            $items[$c]['options'][$i+1]["title"] = $ggroups[$i]['title'];
            $items[$c]['options'][$i+1]["value"] = $ggroups[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "budgetComponentsDetailsSGroup";
        $items[$c]['title'] = "زیرگروه محصول";
        $items[$c]['style'] = "style='width: 50%;'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntgsg;$i++){
            $items[$c]['options'][$i+1]["title"] = $gsgroups[$i]['title'];
            $items[$c]['options'][$i+1]["value"] = $gsgroups[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "budgetComponentsDetailsSeries";
        $items[$c]['title'] = "سری محصول";
        $items[$c]['style'] = "style='width: 50%;'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntgs;$i++){
            $items[$c]['options'][$i+1]["title"] = $gseries[$i]['title'];
            $items[$c]['options'][$i+1]["value"] = $gseries[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetComponentsDetailsHiddenBid";

        $topperBottons = array();
        $topperBottons[0]['title'] = "ایجاد جدول";
        $topperBottons[0]['jsf'] = "getGoodsInThisGroup";
        $topperBottons[0]['type'] = "btn-success";
        $topperBottons[0]['data-dismiss'] = "No";
        $topperBottons[1]['title'] = "تایید";
        $topperBottons[1]['jsf'] = "doCreateBudgetComponentsDetails";
        $topperBottons[1]['type'] = "btn";
        $topperBottons[1]['data-dismiss'] = "No";
        $topperBottons[2]['title'] = "انصراف";
        $topperBottons[2]['type'] = "dismis";
        $createBudgetComponentsDetails = $ut->getHtmlModal($modalID,$modalTitle,$items,array(),'','',$style,$ShowDescription,'','',$topperBottons);
        //++++++++++++++++++++++++++++++ END OF CREATE MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Final approval MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "budgetComponentsFinalTickModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "budgetComponentsFinalTickIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doFinalTickBudgetComponents";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $finalApprovalModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF Final approval MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++ show Budget Components Details ++++++++++++++++++++++
        $modalID = "showBudgetComponentsDetailsModal";
        $modalTitle = " جزئیات اجزای بودجه فروش";
        $ShowDescription = 'showBudgetComponentsDetails-body';
        $style = 'style="max-width: 1366px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetManageBcidHiddenSearch";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetManageGCodeHiddenSearch";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetManageGNameHiddenSearch";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetManageBrandHiddenSearch";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetManageGGroupHiddenSearch";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetManageSGroupHiddenSearch";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetManageSeriesHiddenSearch";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetComponentsManageHiddenBCID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید کلی";
        $footerBottons[0]['jsf'] = "allTickBudgetComponentDetails";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $showBudgetComponentsDetailsModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show Budget Components Details ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ all Tick Budget Component Details MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "allTickBudgetComponentDetailsModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doAllTickBudgetComponentDetails";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $allTickBudgetComponentDetails = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF all Tick Budget Component Details MODAL ++++++++++++++++++++++++++++
        //++++++++++++++++++ show Details Of Budget Components Modal ++++++++++++++++++++++
        $modalID = "showDetailsOfBudgetComponentsModal";
        $modalTitle = " جزئیات اجزای بودجه فروش";
        $ShowDescription = 'showDetailsOfBudgetComponents-body';
        $style = 'style="max-width: 1366px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showDetailsOfBudgetComponentsModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show Details Of Budget Components Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Edit MODAL ++++++++++++++++++++++++++++++++
        $modalID = "budgetComponentDetailsEditModal";
        $modalTitle = "فرم ویرایش جزئیات اجزا بودجه فروش";
        $style = 'style="max-width: 1400px;"';
        $ShowDescription = 'budgetComponentDetailsEdit-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetComponentDetailsEditHiddenBcdId";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditBudgetComponentDetails";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editBudgetComponents = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF Edit MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ budget Components Production Comment Modal ++++++++++++++++++++++++++++++++
        $modalID = "budgetComponentsProductionCommentModal";
        $modalTitle = "فرم تایید / عدم تایید و ثبت نظر ( تولید )";
        $style = 'style="max-width: 651px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "budgetComponentsProductionCommentType";
        $items[$c]['title'] = "انتخاب نمایید";
        $items[$c]['options'][0]['title'] = "تایید";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "عدم تایید";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "budgetComponentsProductionCommentWarning";
        $items[$c]['title'] = "هشدار";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['placeholder'] = "در صورت تایید، نظر شما ثبت خواهد شد و امکان تغییر نخواهید داشت.";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "budgetComponentsProductionCommentDesc";
        $items[$c]['title'] = "ثبت نظرات";
        $items[$c]['placeholder'] = "نظرات خود را بنویسید";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetComponentsProductionCommentHiddenBcdid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doRecordProductionComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $budgetComponentsProductionComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF budget Components Production Comment Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ budget Components Planning Comment Modal ++++++++++++++++++++++++++++++++
        $modalID = "budgetComponentsPlanningCommentModal";
        $modalTitle = "فرم تایید / عدم تایید و ثبت نظر ( برنامه ریزی )";
        $style = 'style="max-width: 651px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "budgetComponentsPlanningCommentType";
        $items[$c]['title'] = "انتخاب نمایید";
        $items[$c]['options'][0]['title'] = "تایید";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "عدم تایید";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "budgetComponentsPlanningCommentWarning";
        $items[$c]['title'] = "هشدار";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['placeholder'] = "در صورت تایید، نظر شما ثبت خواهد شد و امکان تغییر نخواهید داشت.";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "budgetComponentsPlanningCommentDesc";
        $items[$c]['title'] = "ثبت نظرات";
        $items[$c]['placeholder'] = "نظرات خود را بنویسید";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetComponentsPlanningCommentHiddenBcdid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doRecordPlanningComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $budgetComponentsPlanningComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF budget Components Planning Comment Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ send budget Modal ++++++++++++++++++++++++++++++++
        $modalID = "sendBudgetModal";
        $modalTitle = "ارجاع بودجه فروش";
        $style = 'style="max-width: 800px;"';
        $ShowDescription = 'sendBudgetModal-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "sendBudgetDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "sendBudgetHiddenBcid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doSendBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $sensBudget = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF send budget Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ show Budget Workflow Modal ++++++++++++++++++++++++++++++++
        $modalID = "showBudgetWorkflowModal";
        $modalTitle = "گردش کار";
        $style = 'style="max-width: 800px;"';
        $ShowDescription = 'showBudgetWorkflow-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showBudgetWorkflowModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF show Budget Workflow Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ return Budget Components Modal ++++++++++++++++++++++++++++++++++++++++
        $modalID = "returnBudgetComponentsModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "returnBudgetComponentsIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doReturnBudgetComponents";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $returnBudgetComponents = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++ END OF return Budget Components Modal ++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ budget Components Details Excel Modal ++++++++++++++++++++++++++++++++
        $modalID = "budgetComponentsDetailsExcelModal";
        $modalTitle = "خروجی اکسل جزئیات اجزای بودجه";
        $style = 'style="max-width: 570px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "budgetComponentsDetailsExcelYear";
        $items[$c]['style'] = "style='width: 50%;'";
        $items[$c]['title'] = "بودجه فروش سال";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        $x = 1401;
        for ($i=0;$i<600;$i++){
            $items[$c]['options'][$i+1]["title"] = $x;
            $items[$c]['options'][$i+1]["value"] = $x;
            $x++;
        }

        $footerBottons = array();
        $footerBottons[0]['title'] = "دریافت";
        $footerBottons[0]['jsf'] = "doCreateBudgetComponentsDetailsExcel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $budgetComponentsDetailsExcel = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF budget Components Details Excel Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ show Budget Price Details Modal ++++++++++++++++++++++++++++++++
        $modalID = "showBudgetPriceDetailsModal";
        $modalTitle = "جزئیات قیمت تمام شده بودجه";
        $style = 'style="max-width: 1190px;"';
        $ShowDescription = 'showBudgetPriceDetails-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showBudgetPriceDetails = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF show Budget Price Details Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Budget Price Manage Excel Modal ++++++++++++++++++++++++++++++++
        $modalID = "budgetPriceManageExcelModal";
        $modalTitle = "خروجی اکسل قیمت بودجه";
        $style = 'style="max-width: 570px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "budgetPriceManageExcelYear";
        $items[$c]['style'] = "style='width: 50%;'";
        $items[$c]['title'] = "بودجه فروش سال";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        $x = 1401;
        for ($i=0;$i<600;$i++){
            $items[$c]['options'][$i+1]["title"] = $x;
            $items[$c]['options'][$i+1]["value"] = $x;
            $x++;
        }

        $footerBottons = array();
        $footerBottons[0]['title'] = "دریافت";
        $footerBottons[0]['jsf'] = "doGetBudgetPriceManageExcel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $budgetPriceManageExcel = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF Budget Price Manage Excel Modal +++++++++++++++++++++++++++++++++++++
        $htm .= $editCreateModal;
        $htm .= $createBudgetComponents;
        $htm .= $createBudgetComponentsDetails;
        $htm .= $finalApprovalModal;
        $htm .= $showBudgetComponentsDetailsModal;
        $htm .= $allTickBudgetComponentDetails;
        $htm .= $editBudgetComponents;
        $htm .= $budgetComponentsPlanningComment;
        $htm .= $budgetComponentsProductionComment;
        $htm .= $showGoodsWithoutBudget;
        $htm .= $attachmentFileToBudgetModal;
        $htm .= $showBudgetComponentsDetailsFinal;
        $htm .= $showBudgetComponentsFinal;
        $htm .= $showDetailsOfBudgetComponentsModal;
        $htm .= $sensBudget;
        $htm .= $showBudgetWorkflowModal;
        $htm .= $returnBudgetComponents;
        $htm .= $budgetComponentsDetailsExcel;
        $htm .= $showBudgetPriceDetails;
        $htm .= $budgetPriceManageExcel;
        $send = array($htm,$access);
        return $send;
    }

    //******************** بودجه فروش سال ********************

    public function getBudgetManagementList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('budgetManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;

        $sql = "SELECT * FROM `budget`";
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['Name'] = $res[$y]['Name'];
            $finalRes[$y]['validDate'] = $ut->greg_to_jal($res[$y]['validDate']);
            $finalRes[$y]['description'] = $res[$y]['description'];
        }
        return $finalRes;
    }

    public function getBudgetManagementListCountRows(){
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `budget`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function budgetInfo($bid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `budget` WHERE `RowID`=".$bid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("bid"=>$bid,"year"=>$res[0]['year'],"validDate"=>$ut->greg_to_jal($res[0]['validDate']),"description"=>$res[0]['description']);
            return $res;
        }else{
            return false;
        }
    }

    public function createBudget($year,$validDate,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('budgetManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $query = "SELECT `year` FROM `budget`";
        $rst = $db->ArrayQuery($query);
        $cnt = count($rst);
        for ($i=0;$i<$cnt;$i++){
            if (intval($rst[$i]['year']) == $year){
                $res = "قبلا برای این سال بودجه فروش ثبت نموده اید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }
        $validDate = $ut->jal_to_greg($validDate);
        $name = 'بودجه فروش سال '.$year;
        $sql = "INSERT INTO `budget` (`Name`,`year`,`validDate`,`description`) VALUES ('{$name}',{$year},'{$validDate}','{$desc}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editBudget($bid,$validDate,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('budgetManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $validDate = $ut->jal_to_greg($validDate);
        $sql = "UPDATE `budget` SET `validDate`='{$validDate}',`description`='{$desc}' WHERE `RowID`={$bid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getGoodsWithoutBudget($bid){
        $db = new DBi();

        $query = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$bid}";
        $rst = $db->ArrayQuery($query);
        $cntr = count($rst);

        $rids = array();
        for ($i=0;$i<$cntr;$i++){
            $query1 = "SELECT `goodID` FROM `budget_components_details` WHERE `bcid`={$rst[$i]['RowID']}";
            $rst1 = $db->ArrayQuery($query1);
            $cnt1 = count($rst1);
            for ($y=0;$y<$cnt1;$y++){
                $rids[] = $rst1[$y]['goodID'];
            }
        }
        $rids = (count($rids) > 0 ? implode(',', $rids) : 0);

        $sql1 = "SELECT `gCode`,`gName`,`brand`,`ggroup`,`gsgroup`,`Series` FROM `good` WHERE `RowID` NOT IN ({$rids})";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="getGoodsWithoutBudget-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 4%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">کد محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 50%;">نام محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">برند محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">گروه محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زیرگروه محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">سری محصول</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i=0;$i<$cnt1;$i++){
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['gCode'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['gName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['brand'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['ggroup'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['gsgroup'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['Series'].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getBudgetComponentsDetailsFinal($bid,$gCode,$gName,$brand,$ggroup,$sgroup,$series){
        $db = new DBi();
        $piece = new Piece();
        $ut = new Utility();

        $brands = $piece->getBrands();
        $cntb = count($brands);

        $ggroups = $piece->getJustGGroup();
        $cntgg = count($ggroups);

        $gsgroups = $piece->getJustGSGroup();
        $cntgsg = count($gsgroups);

        $gseries = $piece->getJustGSeries();
        $cntgs = count($gseries);

        $sql = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$bid} AND `finalTick`=1";
        $res = $db->ArrayQuery($sql);
        $ccnt = count($res);
        $rids = array();
        for ($j=0;$j<$ccnt;$j++){
            $rids[] = $res[$j]['RowID'];
        }
        $rids = (count($rids) > 0 ? implode(',',$rids) : 0);

        $w = array();
        $w[] = '`bcid` IN ('.$rids.') ';
        if(strlen(trim($gCode)) > 0){
            $w[] = '`HCode` LIKE "%'.$gCode.'%" ';
        }
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(intval($brand) > 0){
            $query = "SELECT `title` FROM `categories` WHERE `RowID`={$brand}";
            $rst = $db->ArrayQuery($query);
            $w[] = '`brand`="'.$rst[0]['title'].'" ';
        }
        if(intval($ggroup) > 0){
            $query1 = "SELECT `title` FROM `categories` WHERE `RowID`={$ggroup}";
            $rst1 = $db->ArrayQuery($query1);
            $w[] = '`ggroup`="'.$rst1[0]['title'].'" ';
        }
        if(intval($sgroup) > 0){
            $query2 = "SELECT `title` FROM `categories` WHERE `RowID`={$sgroup}";
            $rst2 = $db->ArrayQuery($query2);
            $w[] = '`gsgroup`="'.$rst2[0]['title'].'" ';
        }
        if(intval($series) > 0){
            $query3 = "SELECT `title` FROM `categories` WHERE `RowID`={$series}";
            $rst3 = $db->ArrayQuery($query3);
            $w[] = '`series`="'.$rst3[0]['title'].'" ';
        }
        $sql1 = "SELECT `budget_components_details`.*,`cDate` FROM `budget_components_details` INNER JOIN `budget_components` ON (`budget_components`.`RowID`=`budget_components_details`.`bcid`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql1 .= " WHERE ".$where;
        }
        $res1 = $db->ArrayQuery($sql1);
        $cnt = count($res1);
        $iterator = 0;

        $htm = '';
        $htm .= '<form class="form-inline" style="margin: 20px 0;">';

        $htm .= '<div id="budgetFinalGCodeSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetFinalGCodeSearch">کد محصول</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="budgetFinalGCodeSearch" autocomplete="off" style="width: 150px;" placeholder="کد محصول" >';
        $htm .= '</div>';

        $htm .= '<div id="budgetFinalGNameSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetFinalGNameSearch">نام محصول</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="budgetFinalGNameSearch" autocomplete="off" style="width: 200px;" placeholder="نام محصول" >';
        $htm .= '</div>';

        $htm .= '<div id="budgetFinalBrandSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetFinalBrandSearch">برند محصول</label>';
        $htm .= '<select class="form-control form-control-sm mb-2 mr-sm-2 headsearch shadow-color" id="budgetFinalBrandSearch" style="width: 150px;">';
        $htm .= '<option value="-1">برند محصول</option>';
        for ($i=0;$i<$cntb;$i++){
            $htm .= '<option value="'.$brands[$i]['RowID'].'">'.$brands[$i]['title'].'</option>';
        }
        $htm .= '</select>';
        $htm .= '</div>';

        $htm .= '<div id="budgetFinalGGroupSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetFinalGGroupSearch">گروه محصول</label>';
        $htm .= '<select class="form-control form-control-sm mb-2 mr-sm-2 headsearch shadow-color" id="budgetFinalGGroupSearch" style="width: 150px;" >';
        $htm .= '<option value="-1">گروه محصول</option>';
        for ($i=0;$i<$cntgg;$i++){
            $htm .= '<option value="'.$ggroups[$i]['RowID'].'">'.$ggroups[$i]['title'].'</option>';
        }
        $htm .= '</select>';
        $htm .= '</div>';

        $htm .= '<div id="budgetFinalSGroupSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetFinalSGroupSearch">زیرگروه محصول</label>';
        $htm .= '<select class="form-control form-control-sm mb-2 mr-sm-2 headsearch shadow-color" id="budgetFinalSGroupSearch" style="width: 150px;">';
        $htm .= '<option value="-1">زیرگروه محصول</option>';
        for ($i=0;$i<$cntgsg;$i++){
            $htm .= '<option value="'.$gsgroups[$i]['RowID'].'">'.$gsgroups[$i]['title'].'</option>';
        }
        $htm .= '</select>';
        $htm .= '</div>';

        $htm .= '<div id="budgetFinalSeriesSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetFinalSeriesSearch">سری محصول</label>';
        $htm .= '<select class="form-control form-control-sm mb-2 mr-sm-2 headsearch shadow-color" id="budgetFinalSeriesSearch" style="width: 150px;" >';
        $htm .= '<option value="-1">سری محصول</option>';
        for ($i=0;$i<$cntgs;$i++){
            $htm .= '<option value="'.$gseries[$i]['RowID'].'">'.$gseries[$i]['title'].'</option>';
        }
        $htm .= '</select>';
        $htm .= '</div>';

        $htm .= '<button type="button" class="btn btn-sm btn-secondary mb-2 mr-2" style="padding: 0.25rem 0.5rem;" onclick="showBudgetComponentsDetailsFinal('.$bid.')">جستجو&nbsp;&nbsp;<i class="fa fa-search"></i></button>';

        $htm .= '</form>';

        $htm .= '<table class="table table-bordered table-hover table-sm" id="getBudgetComponentsDetailsFinal-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 4%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">کد محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">نام محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">فروردین</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">اردیبهشت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">خرداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">تیر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">مرداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">شهریور</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">مهر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">آبان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">آذر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">دی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">بهمن</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">اسفند</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['HCode'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['gName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res1[$i]['cDate']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['farvardin'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['ordibehesht'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['khordad'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['tir'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['mordad'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['shahrivar'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['mehr'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['aban'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['azar'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['dey'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['bahman'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['esfand'].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getBudgetComponentsFinal($bid,$bcDate,$bcName,$bcCode){
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if(strlen(trim($bcName)) > 0){
            $w[] = '`Name` LIKE "%'.$bcName.'%" ';
        }
        if(strlen(trim($bcCode)) > 0){
            $w[] = '`unCode`="'.$bcCode.'" ';
        }
        if(strlen(trim($bcDate)) > 0){
            $bcDate = $ut->jal_to_greg($bcDate);
            $w[] = '`cDate`="'.$bcDate.'" ';
        }
        $w[] = '`budgetID`='.$bid;
        $w[] = '`finalTick`=1';

        $sql = "SELECT * FROM `budget_components`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;

        $htm = '';
        $htm .= '<form class="form-inline" style="margin: 20px 0;">';

        $htm .= '<div id="budgetComponentsFinalCdateSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetComponentsFinalCdateSearch">تاریخ</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="budgetComponentsFinalCdateSearch" autocomplete="off" style="width: 150px;" placeholder="تاریخ" >';
        $htm .= '</div>';

        $htm .= '<div id="budgetComponentsFinalBcNameSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetComponentsFinalBcNameSearch">نام اجزا</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="budgetComponentsFinalBcNameSearch" autocomplete="off" style="width: 200px;" placeholder="نام اجزا" >';
        $htm .= '</div>';

        $htm .= '<div id="budgetComponentsFinalBcCodeSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetComponentsFinalBcCodeSearch">کد اجزا</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="budgetComponentsFinalBcCodeSearch" autocomplete="off" style="width: 200px;" placeholder="کد اجزا" >';
        $htm .= '</div>';

        $htm .= '<button type="button" class="btn btn-sm btn-secondary mb-2 mr-2" style="padding: 0.25rem 0.5rem;" onclick="showBudgetComponentsFinal('.$bid.')">جستجو&nbsp;&nbsp;<i class="fa fa-search"></i></button>';

        $htm .= '</form>';

        $htm .= '<table class="table table-bordered table-hover table-sm" id="getBudgetComponentsFinal-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 4%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 28%;">نام اجزای بودجه فروش</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">کد اجزای بودجه فروش</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ساعت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 24%;">توضیحات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">جزئیات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">برگشت</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['Name'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['unCode'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res[$i]['cDate']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['cTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showDetailsOfBudgetComponents('.$res[$i]['RowID'].')" ><i class="fas fa-puzzle-piece"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="returnBudgetComponents('.$res[$i]['RowID'].')" ><i class="fas fa-rotate-left"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getBudgetComponentsDetails($bcid){
        $db = new DBi();

        $sql1 = "SELECT * FROM `budget_components_details` WHERE `bcid`={$bcid}";
        $res1 = $db->ArrayQuery($sql1);
        $cnt = count($res1);
        $iterator = 0;

        $htm = '';

        $htm .= '<table class="table table-bordered table-hover table-sm" id="getBudgetComponentsDetails-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 4%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">کد محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 31%;">نام محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">فروردین</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">اردیبهشت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">خرداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">تیر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">مرداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">شهریور</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">مهر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">آبان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">آذر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">دی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">بهمن</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">اسفند</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['gCode'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['gName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['farvardin'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['ordibehesht'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['khordad'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['tir'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['mordad'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['shahrivar'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['mehr'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['aban'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['azar'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['dey'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['bahman'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['esfand'].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function returnBudgetComponents($bcid){
        $db= new DBi();
        $sql = "UPDATE `budget_components` SET `finalTick`=0 WHERE `RowID`={$bcid}";
        $db->Query($sql);

        $sql1 = "UPDATE `budget_components_details` SET `finalTick`=0 WHERE `bcid`={$bcid}";
        $db->Query($sql1);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }

    }

    public function attachedBudgetFileHtm($bid){
        $db = new DBi();
        $sql = "SELECT `RowID`,`fileName`,`fileInfo` FROM `budget_attachment` WHERE `bid`={$bid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachedBudgetFileHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 55%;">نام فایل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">لینک دانلود</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">حذف فایل</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $link = ADDR.'attachment/'.$res[$i]['fileName'];

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachBudgetFile('.$res[$i]['RowID'].')" ><i class="fas fa-trash"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function attachFileToBudget($bid,$info,$files,$bcid){

        $db = new DBi();
        $SFile = array();
        $ut=new Utility();
       // $ut->fileRecorder('bcid:'.$bcid);
       // die();
        $allowedTypes = ['png','jpg','jpeg','pdf','xlsx','docx','PNG','JPG','JPEG','PDF','XLSX','DOCX'];
        if (isset($files) && !empty($files)) {
            $no_files = count($files['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files["tmp_name"][$i];
                if ($files["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files['name'][$i], strpos($files['name'][$i], ".") + 1);
                if(!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile[] = "Budget" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $cnt = count($SFile);
        for ($i=0;$i<$cnt;$i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i],'../attachment/'.$SFile[$i]);
            $sql4 = "INSERT INTO `budget_attachment` (`bid`,`fileName`,`fileInfo`) VALUES ({$bid},'{$SFile[$i]}','{$info}')";
            $db->Query($sql4);
        }
        if($format=="xlsx" || $format=="XLSX" ){
            if($upload){
              //  //error_log('testtttttt:'.print_r($SFile,true));
                $filePath='../attachment/'.$SFile[0];
                $this->createBudgetListDB($filePath,$bid,$bcid);

            }
           //$reader=new SpreadsheetReader$upload);
        //    foreach($reader as $k=>$v){
        //     $ut->fileRcorder('excel');
        //    }
        //error_log('upload:'.$upload);
        }
        return true;
    }

    public function deleteAttachBudgetFile($fid){
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `budget_attachment` WHERE `RowID`={$fid}";
        $res = $db->ArrayQuery($sql);
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/attachment/' . $res[0]['fileName'];
        $result = unlink($file_to_delete);
        if ($result) {
            $query = "DELETE FROM `budget_attachment` WHERE `RowID`={$fid}";
            $db->Query($query);
            return true;
        }else{
            return false;
        }
    }

    //******************** اجزای بودجه فروش ********************

    public function getBudgetComponentsList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('budgetComponentsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        $w[] = '`finalTick`=0 ';
        if (intval($_SESSION['userid']) !== 1) {
            if ($acm->hasAccess('editCreateBudgetComponents')){  // سید جواد رضوی یا حاتمی
                $w[] = '(`lastReceiver`=' . 20 . ' OR `lastReceiver`=' . 24 .')';
            }else{
                $w[] = '`lastReceiver`=' . $_SESSION['userid'] . ' ';
            }
        }

        $sql = "SELECT `budget_components`.*,`year` FROM `budget_components` INNER JOIN `budget` ON (`budget_components`.`budgetID`=`budget`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['year'] = $res[$y]['year'];
            $finalRes[$y]['Name'] = $res[$y]['Name'];
            $finalRes[$y]['unCode'] = $res[$y]['unCode'];
            $finalRes[$y]['cDate'] = $ut->greg_to_jal($res[$y]['cDate']);
            $finalRes[$y]['cTime'] = $res[$y]['cTime'];
            $finalRes[$y]['description'] = $res[$y]['description'];
        }
        return $finalRes;
    }

    public function getBudgetComponentsListCountRows(){
        $acm = new acm();
        $db = new DBi();
        $w = array();
        $w[] = '`finalTick`=0 ';
        if (intval($_SESSION['userid']) !== 1) {
            if ($acm->hasAccess('editCreateBudgetComponents')){  // سید جواد رضوی یا حاتمی
                $w[] = '(`lastReceiver`=' . 20 . ' OR `lastReceiver`=' . 24 .')';
            }else{
                $w[] = '`lastReceiver`=' . $_SESSION['userid'] . ' ';
            }
        }
        $sql = "SELECT `RowID` FROM `budget_components`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function budgetComponentsInfo($bcid){
        $db = new DBi();
        $sql = "SELECT * FROM `budget_components` WHERE `RowID`=".$bcid;
        $res = $db->ArrayQuery($sql);

        $sql1 = "SELECT `Name` FROM `budget` WHERE `RowID`={$res[0]['budgetID']}";
        $res1 = $db->ArrayQuery($sql1);

        $res[0]['Name'] = str_replace($res1[0]['Name'].' (',"",$res[0]['Name']);
        $res[0]['Name'] = str_replace(')',"",$res[0]['Name']);

        if(count($res) == 1){
            $res = array("bcid"=>$bcid,"budgetID"=>$res[0]['budgetID'],"Name"=>$res[0]['Name'],"description"=>$res[0]['description']);
            return $res;
        }else{
            return false;
        }
    }

    public function createBudgetComponents($desc,$year,$name){
        $acm = new acm();
        if(!$acm->hasAccess('budgetComponentsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $nowDate = date('Y-m-d');
        $sql1 = "SELECT `Name`,`validDate` FROM `budget` WHERE `RowID`={$year}";
        $res1 = $db->ArrayQuery($sql1);
        if (strtotime($nowDate) > strtotime($res1[0]['validDate'])){
            $res = "تاریخ اعتبار بودجه فروش منقضی شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $name = $res1[0]['Name'].' ('.$name.')';
        $datetostring = substr($ut->greg_to_jal(str_replace('/','-',date('Y/m/d'))),2,2);
        $unCode = $datetostring.rand(10000,99999).substr(time(), -4);
        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        $sql2 = "INSERT INTO `budget_components` (`budgetID`,`Name`,`unCode`,`cDate`,`cTime`,`lastReceiver`,`description`) VALUES ({$year},'{$name}','{$unCode}','{$nowDate}','{$nowTime}',{$_SESSION['userid']},'{$desc}')";
        $res2 = $db->Query($sql2);

        if (intval($res2) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editBudgetComponents($bcid,$desc,$year,$name){
        $acm = new acm();
        if(!$acm->hasAccess('budgetComponentsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sql1 = "SELECT `Name` FROM `budget` WHERE `RowID`={$year}";
        $res1 = $db->ArrayQuery($sql1);

        $name = $res1[0]['Name'].' ('.$name.')';

        $sql2 = "UPDATE `budget_components` SET `Name`='{$name}',`description`='{$desc}' WHERE `RowID`={$bcid}";
        $res2 = $db->Query($sql2);

        if (intval($res2) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function budgetComponentsDetailsHtm($bcid,$brand,$ggroup,$sgroup,$Series){
        $db = new DBi();
        $ut = new Utility();
        $nowDate = $ut->greg_to_jal(date('Y-m-d'));
        $month = (intval(strlen($nowDate)) == 8 ? intval(substr($nowDate,5,1)) : intval(substr($nowDate,5,2)) );
        $cyear = intval(substr($nowDate,0,4));

        $sqq = "SELECT `budgetID` FROM `budget_components` WHERE `RowID`={$bcid}";
        $rsq = $db->ArrayQuery($sqq);

        $rids = array();
        $sql1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$rsq[0]['budgetID']}";
        $res1 = $db->ArrayQuery($sql1);
        $ccnt = count($res1);
        for ($j=0;$j<$ccnt;$j++){
            $rids[] = $res1[$j]['RowID'];
        }
        $rids = implode(',',$rids);

        $rowIds = array();
        $sql2 = "SELECT `goodID` FROM `budget_components_details` WHERE `bcid` IN ({$rids})";
        $res2 = $db->ArrayQuery($sql2);
        $cccnt = count($res2);
        for ($j=0;$j<$cccnt;$j++){
            $rowIds[] = $res2[$j]['goodID'];
        }
        $rowIds = (count($rowIds) > 0 ? implode(',',$rowIds) : 0);

        $sqqq = "SELECT `year` FROM `budget` WHERE `RowID`={$rsq[0]['budgetID']}";
        $rstq = $db->ArrayQuery($sqqq);

        $query = "SELECT `title` FROM `categories` WHERE `RowID`={$brand}";
        $rst = $db->ArrayQuery($query);

        $query1 = "SELECT `title` FROM `categories` WHERE `RowID`={$ggroup}";
        $rst1 = $db->ArrayQuery($query1);

        $query2 = "SELECT `title` FROM `categories` WHERE `RowID`={$sgroup}";
        $rst2 = $db->ArrayQuery($query2);

        $query3 = "SELECT `title` FROM `categories` WHERE `RowID`={$Series}";
        $rst3 = $db->ArrayQuery($query3);

        $w = array();
        if(count($rst) > 0){
            $w[] = '`brand`="'.$rst[0]['title'].'" ';
        }
        if(count($rst1) > 0){
            $w[] = '`ggroup`="'.$rst1[0]['title'].'" ';
        }
        if(count($rst2) > 0){
            $w[] = '`gsgroup`="'.$rst2[0]['title'].'" ';
        }
        if(count($rst3) > 0){
            $w[] = '`Series`="'.$rst3[0]['title'].'" ';
        }
        $w[] = '`RowID` NOT IN ('.$rowIds.') ';

        $sql = "SELECT `RowID`,`gName`,`gCode`,`brand`,`ggroup`,`HCode` FROM `good`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }

        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="budgetComponentsDetailsHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= "<td style='display: none;'>&nbsp;</td>";
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 3%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 21%;">نام محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">کد مهندسی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">کد محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">فروردین</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">اردیبهشت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">خرداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">تیر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">مرداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">شهریور</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">مهر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">آبان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">آذر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">دی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">بهمن</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">اسفند</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        if (intval($cyear) == intval($rstq[0]['year'])) {
            for ($i = 0; $i < $cnt; $i++) {
                $iterator++;
                $htm .= '<tr class="table-secondary">';
                $htm .= "<td style='display: none;' ><input type='checkbox' rid='" . $iterator . "' checked disabled>&nbsp;</td>";
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['gName'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['gCode'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['HCode'] . '</td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12)) ? 'disabled value="0"' : '') . ' id="budgetComponentsOne-' . $iterator . '" /><input type="hidden" id="bcid-' . $iterator . '-Hidden" value="' . $res[$i]['RowID'] . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(3, 4, 5, 6, 7, 8, 9, 10, 11, 12)) ? 'disabled value="0"' : '') . ' id="budgetComponentsTwo-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(4, 5, 6, 7, 8, 9, 10, 11, 12)) ? 'disabled value="0"' : '') . ' id="budgetComponentsThree-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(5, 6, 7, 8, 9, 10, 11, 12)) ? 'disabled value="0"' : '') . ' id="budgetComponentsFour-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(6, 7, 8, 9, 10, 11, 12)) ? 'disabled value="0"' : '') . ' id="budgetComponentsFive-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(7, 8, 9, 10, 11, 12)) ? 'disabled value="0"' : '') . ' id="budgetComponentsSix-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(8, 9, 10, 11, 12)) ? 'disabled value="0"' : '') . ' id="budgetComponentsSeven-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(9, 10, 11, 12)) ? 'disabled value="0"' : '') . ' id="budgetComponentsEight-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(10, 11, 12)) ? 'disabled value="0"' : '') . ' id="budgetComponentsNine-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(11, 12)) ? 'disabled value="0"' : '') . ' id="budgetComponentsTen-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(12)) ? 'disabled value="0"' : '') . ' id="budgetComponentsEleven-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="budgetComponentsTwelve-' . $iterator . '" /></td>';

                $htm .= '</tr>';
            }
        }else{
            for ($i = 0; $i < $cnt; $i++) {
                $iterator++;
                $htm .= '<tr class="table-secondary">';
                $htm .= "<td style='display: none;' ><input type='checkbox' rid='" . $iterator . "' checked disabled>&nbsp;</td>";
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['gName'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['gCode'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['HCode'] . '</td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="budgetComponentsOne-' . $iterator . '" /><input type="hidden" id="bcid-' . $iterator . '-Hidden" value="' . $res[$i]['RowID'] . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="budgetComponentsTwo-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="budgetComponentsThree-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="budgetComponentsFour-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="budgetComponentsFive-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="budgetComponentsSix-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="budgetComponentsSeven-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="budgetComponentsEight-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="budgetComponentsNine-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="budgetComponentsTen-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="budgetComponentsEleven-' . $iterator . '" /></td>';
                $htm .= '<td style="text-align: center;"><input type="text" class="form-control" id="budgetComponentsTwelve-' . $iterator . '" /></td>';

                $htm .= '</tr>';
            }
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function createBudgetComponentsDetails($myJsonString,$bccid){
        $acm = new acm();
        if(!$acm->hasAccess('budgetComponentsManage')){
            die("access denied");
            exit;
        }
        $countJS = count($myJsonString);
        $flag = true;
        $db = new DBi();
        mysqli_autocommit($db->Getcon(),FALSE);

        $sqq = "SELECT `budgetID` FROM `budget_components` WHERE `RowID`={$bccid}";
        $rstq = $db->ArrayQuery($sqq);

        $nowDate = date('Y-m-d');
        $sqld = "SELECT `validDate` FROM `budget` WHERE `RowID`={$rstq[0]['budgetID']}";
        $resd = $db->ArrayQuery($sqld);
        if (strtotime($nowDate) > strtotime($resd[0]['validDate'])){
            $res = "تاریخ اعتبار بودجه فروش منقضی شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $goodIDs = array();
        $query = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$rstq[0]['budgetID']}";
        $rst = $db->ArrayQuery($query);
        $cnt = count($rst);
        for ($x=0;$x<$cnt;$x++){
            $query1 = "SELECT `goodID` FROM `budget_components_details` WHERE `bcid`={$rst[$x]['RowID']}";
            $rst1 = $db->ArrayQuery($query1);
            $cnt1 = count($rst1);
            for ($y=0;$y<$cnt1;$y++){
                $goodIDs[] = $rst1[$y]['goodID'];
            }
        }

        for ($j=0;$j<$countJS;$j++){
            if (in_array($myJsonString[$j][12],$goodIDs)){
                $res = "برای یک یا چند محصول در این سال انتخابی قبلا بودجه فروش تعیین نموده اید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }

        for($i=0;$i<$countJS;$i++){
            $goodID = intval($myJsonString[$i][12]);  // goodID
            $sqg = "SELECT `brand`,`ggroup`,`gsgroup`,`Series`,`gCode`,`gName`,`HCode` FROM `good` WHERE `RowID`={$goodID}";
            $rstg = $db->ArrayQuery($sqg);

            $rolevije = 0;
            switch ($goodID){
                case 976:
                    $sqlv = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid`={$bccid} AND `goodID`=340";
                    $rsv = $db->ArrayQuery($sqlv);
                    $rolevije = $rsv[0]['RowID'];
                    break;
                case 977:
                    $sqlv = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid`={$bccid} AND `goodID`=341";
                    $rsv = $db->ArrayQuery($sqlv);
                    $rolevije = $rsv[0]['RowID'];
                    break;
                case 978:
                    $sqlv = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid`={$bccid} AND `goodID`=342";
                    $rsv = $db->ArrayQuery($sqlv);
                    $rolevije = $rsv[0]['RowID'];
                    break;
                case 979:
                    $sqlv = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid`={$bccid} AND `goodID`=343";
                    $rsv = $db->ArrayQuery($sqlv);
                    $rolevije = $rsv[0]['RowID'];
                    break;
                case 980:
                    $sqlv = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid`={$bccid} AND `goodID`=751";
                    $rsv = $db->ArrayQuery($sqlv);
                    $rolevije = $rsv[0]['RowID'];
                    break;
                case 981:
                    $sqlv = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid`={$bccid} AND `goodID`=752";
                    $rsv = $db->ArrayQuery($sqlv);
                    $rolevije = $rsv[0]['RowID'];
                    break;
                case 982:
                    $sqlv = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid`={$bccid} AND `goodID`=753";
                    $rsv = $db->ArrayQuery($sqlv);
                    $rolevije = $rsv[0]['RowID'];
                    break;
                case 983:
                    $sqlv = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid`={$bccid} AND `goodID`=754";
                    $rsv = $db->ArrayQuery($sqlv);
                    $rolevije = $rsv[0]['RowID'];
                    break;
            }

            $sql = "INSERT INTO `budget_components_details` (`bcid`,`goodID`,`gCode`,`gName`,`brand`,`ggroup`,`gsgroup`,`series`,`farvardin`,`ordibehesht`,`khordad`,`tir`,`mordad`,`shahrivar`,`mehr`,`aban`,`azar`,`dey`,`bahman`,`esfand`,`HCode`,`parentID`) VALUES ({$bccid},{$goodID},'{$rstg[0]['gCode']}','{$rstg[0]['gName']}','{$rstg[0]['brand']}','{$rstg[0]['ggroup']}','{$rstg[0]['gsgroup']}','{$rstg[0]['Series']}',{$myJsonString[$i][0]},{$myJsonString[$i][1]},{$myJsonString[$i][2]},{$myJsonString[$i][3]},{$myJsonString[$i][4]},{$myJsonString[$i][5]},{$myJsonString[$i][6]},{$myJsonString[$i][7]},{$myJsonString[$i][8]},{$myJsonString[$i][9]},{$myJsonString[$i][10]},{$myJsonString[$i][11]},'{$rstg[0]['HCode']}',{$rolevije})";
            $res1 = $db->Query($sql);
            if(intval($res1) <= 0){
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

    public function getBudgetComponents($bcid,$gCode,$gName,$brand,$ggroup,$sgroup,$series){
        $db = new DBi();
        $piece = new Piece();

        $brands = $piece->getBrands();
        $cntb = count($brands);

        $ggroups = $piece->getJustGGroup();
        $cntgg = count($ggroups);

        $gsgroups = $piece->getJustGSGroup();
        $cntgsg = count($gsgroups);

        $gseries = $piece->getJustGSeries();
        $cntgs = count($gseries);

        $w = array();
        $w[] = '`bcid`='.$bcid.' ';
        if(strlen(trim($gCode)) > 0){
            $w[] = '`HCode` LIKE "%'.$gCode.'%" ';
        }
        if(strlen(trim($gName)) > 0){
            $w[] = '`gName` LIKE "%'.$gName.'%" ';
        }
        if(intval($brand) > 0){
            $query = "SELECT `title` FROM `categories` WHERE `RowID`={$brand}";
            $rst = $db->ArrayQuery($query);
            $w[] = '`brand`="'.$rst[0]['title'].'" ';
        }
        if(intval($ggroup) > 0){
            $query1 = "SELECT `title` FROM `categories` WHERE `RowID`={$ggroup}";
            $rst1 = $db->ArrayQuery($query1);
            $w[] = '`ggroup`="'.$rst1[0]['title'].'" ';
        }
        if(intval($sgroup) > 0){
            $query2 = "SELECT `title` FROM `categories` WHERE `RowID`={$sgroup}";
            $rst2 = $db->ArrayQuery($query2);
            $w[] = '`gsgroup`="'.$rst2[0]['title'].'" ';
        }
        if(intval($series) > 0){
            $query3 = "SELECT `title` FROM `categories` WHERE `RowID`={$series}";
            $rst3 = $db->ArrayQuery($query3);
            $w[] = '`series`="'.$rst3[0]['title'].'" ';
        }
        $sql1 = "SELECT * FROM `budget_components_details`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql1 .= " WHERE ".$where;
        }
        $res1 = $db->ArrayQuery($sql1);
        $cnt = count($res1);
        $iterator = 0;

        $htm = '';
        $htm .= '<form class="form-inline" style="margin: 20px 0;">';

        $htm .= '<div id="budgetManageGCodeSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetManageGCodeSearch">کد محصول</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="budgetManageGCodeSearch" autocomplete="off" style="width: 150px;" placeholder="کد محصول" >';
        $htm .= '</div>';

        $htm .= '<div id="budgetManageGNameSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetManageGNameSearch">نام محصول</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="budgetManageGNameSearch" autocomplete="off" style="width: 200px;" placeholder="نام محصول" >';
        $htm .= '</div>';

        $htm .= '<div id="budgetManageBrandSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetManageBrandSearch">برند محصول</label>';
        $htm .= '<select class="form-control form-control-sm mb-2 mr-sm-2 headsearch shadow-color" id="budgetManageBrandSearch" style="width: 150px;">';
        $htm .= '<option value="-1">برند محصول</option>';
        for ($i=0;$i<$cntb;$i++){
            $htm .= '<option value="'.$brands[$i]['RowID'].'">'.$brands[$i]['title'].'</option>';
        }
        $htm .= '</select>';
        $htm .= '</div>';

        $htm .= '<div id="budgetManageGGroupSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetManageGGroupSearch">گروه محصول</label>';
        $htm .= '<select class="form-control form-control-sm mb-2 mr-sm-2 headsearch shadow-color" id="budgetManageGGroupSearch" style="width: 150px;">';
        $htm .= '<option value="-1">گروه محصول</option>';
        for ($i=0;$i<$cntgg;$i++){
            $htm .= '<option value="'.$ggroups[$i]['RowID'].'">'.$ggroups[$i]['title'].'</option>';
        }
        $htm .= '</select>';
        $htm .= '</div>';

        $htm .= '<div id="budgetManageSGroupSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetManageSGroupSearch">زیرگروه محصول</label>';
        $htm .= '<select class="form-control form-control-sm mb-2 mr-sm-2 headsearch shadow-color" id="budgetManageSGroupSearch" style="width: 150px;">';
        $htm .= '<option value="-1">زیرگروه محصول</option>';
        for ($i=0;$i<$cntgsg;$i++){
            $htm .= '<option value="'.$gsgroups[$i]['RowID'].'">'.$gsgroups[$i]['title'].'</option>';
        }
        $htm .= '</select>';
        $htm .= '</div>';

        $htm .= '<div id="budgetManageSeriesSearch-div" >';
        $htm .= '<label class="sr-only" for="budgetManageSeriesSearch">سری محصول</label>';
        $htm .= '<select class="form-control form-control-sm mb-2 mr-sm-2 headsearch shadow-color" id="budgetManageSeriesSearch" style="width: 150px;" >';
        $htm .= '<option value="-1">سری محصول</option>';
        for ($i=0;$i<$cntgs;$i++){
            $htm .= '<option value="'.$gseries[$i]['RowID'].'">'.$gseries[$i]['title'].'</option>';
        }
        $htm .= '</select>';
        $htm .= '</div>';

        $htm .= '<button type="button" class="btn btn-sm btn-secondary mb-2 mr-2" style="padding: 0.25rem 0.5rem;" onclick="showBudgetComponentsDetails('.$bcid.')">جستجو&nbsp;&nbsp;<i class="fa fa-search"></i></button>';

        $htm .= '</form>';

        $htm .= '<table class="table table-bordered table-hover table-sm" id="getBudgetComponents-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 4%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">کد محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 16%;">نام محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">فروردین</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">اردیبهشت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">خرداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">تیر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">مرداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">شهریور</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">مهر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">آبان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">آذر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">دی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">بهمن</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">اسفند</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">نظر برنامه ریزی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">نظر تولید</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ویرایش</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i=0;$i<$cnt;$i++){
            switch (intval($res1[$i]['planningTick'])){
                case 0:
                    $btnType1 = 'btn-danger';
                    break;
                case 1:
                    $btnType1 = 'btn-success';
                    break;
                case 2:
                    $btnType1 = 'btn-warning';
                    break;
            }
            switch (intval($res1[$i]['productionTick'])){
                case 0:
                    $btnType2 = 'btn-danger';
                    break;
                case 1:
                    $btnType2 = 'btn-success';
                    break;
                case 2:
                    $btnType2 = 'btn-warning';
                    break;
            }
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['HCode'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['gName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['farvardin'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['ordibehesht'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['khordad'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['tir'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['mordad'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['shahrivar'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['mehr'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['aban'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['azar'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['dey'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['bahman'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['esfand'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn '.$btnType1.'" onclick="recordPlanningComment('.$res1[$i]['RowID'].')" ><i class="fas fa-comment-alt"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn '.$btnType2.'" onclick="recordProductionComment('.$res1[$i]['RowID'].')" ><i class="fas fa-comment-alt"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="editBudgetComponentDetails('.$res1[$i]['RowID'].')" ><i class="fas fa-edit"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function planningComment($bcdid){
        $db = new DBi();
        $sql = "SELECT `planningTick`,`planningDescription` FROM `budget_components_details` WHERE `RowID`=".$bcdid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("planningTick"=>$res[0]['planningTick'],"planningDescription"=>$res[0]['planningDescription']);
            return $res;
        }else{
            return false;
        }
    }

    public function productionComment($bcdid){
        $db = new DBi();
        $sql = "SELECT `productionTick`,`productionDescription` FROM `budget_components_details` WHERE `RowID`=".$bcdid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("productionTick"=>$res[0]['productionTick'],"productionDescription"=>$res[0]['productionDescription']);
            return $res;
        }else{
            return false;
        }
    }

    public function recordPlanningComment($bcdid,$desc,$radioValue){
        $acm = new acm();
        if(!$acm->hasAccess('planningTickBudget')){
            $res = "شما مجاز به ثبت نظر در واحد برنامه ریزی نمی باشید !";
            $out = "false";
            response($res,$out);
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `budget_components_details` SET `planningTick`={$radioValue},`planningDescription`='{$desc}' WHERE `RowID`={$bcdid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function recordProductionComment($bcdid,$desc,$radioValue){
        $acm = new acm();
        if(!$acm->hasAccess('productionTickBudget')){
            $res = "شما مجاز به ثبت نظر در واحد تولید نمی باشید !";
            $out = "false";
            response($res,$out);
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `budget_components_details` SET `productionTick`={$radioValue},`productionDescription`='{$desc}' WHERE `RowID`={$bcdid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function allTickBudgetComponentDetails($bcid){
        $acm = new acm();
        $db = new DBi();

        if ($acm->hasAccess('planningTickBudget')){
            $sql3 = "UPDATE `budget_components_details` SET `planningTick`=1,`planningDescription`='' WHERE `bcid`={$bcid}";
            $db->Query($sql3);
            return true;
        }elseif ($acm->hasAccess('productionTickBudget')){
            $sql3 = "UPDATE `budget_components_details` SET `productionTick`=1,`productionDescription`='' WHERE `bcid`={$bcid}";
            $db->Query($sql3);
            return true;
        }else{
            $res = "شما مجاز به ثبت نظر در واحد برنامه ریزی یا تولید نمی باشید !";
            $out = "false";
            response($res,$out);
            exit;
        }
    }

    public function budgetWorkflowHtm($bcid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `budget_workflow`.*,`fname`,`lname` FROM `budget_workflow` INNER JOIN `users` ON (`budget_workflow`.`sender`=`users`.`RowID`) WHERE `bcid`={$bcid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="budgetWorkflowHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">فرستنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">گیرنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زمان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$i]['receiver']}";
            $rst = $db->ArrayQuery($query);
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$rst[0]['fname'].' '.$rst[0]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createDate'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function sendBudget($bcid,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('budgetComponentsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        $cartable = 'اجزای بودجه فروش';

        $sql1 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=102";  // واحد برنامه ریزی
        $res1 = $db->ArrayQuery($sql1);

        $sqlp = "SELECT `phone` FROM `users` WHERE `RowID`={$res1[0]['user_id']}";
        $resp = $db->ArrayQuery($sqlp);

        $sql2 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=101";  // واحد تولید
        $res2 = $db->ArrayQuery($sql2);

        $sqlp1 = "SELECT `phone` FROM `users` WHERE `RowID`={$res2[0]['user_id']}";
        $resp1 = $db->ArrayQuery($sqlp1);

        if ($acm->hasAccess('editCreateBudgetComponents')){   // معاونت بازرگانی یا حاتمی بود
            $sql3 = "INSERT INTO `budget_workflow` (`sender`,`receiver`,`bcid`,`createDate`,`createTime`,`description`) VALUES ({$_SESSION['userid']},{$res1[0]['user_id']},{$bcid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

            $sql4 = "UPDATE `budget_components` SET `lastReceiver`={$res1[0]['user_id']} WHERE `RowID`={$bcid}";
            $db->Query($sql4);

            $phone = $resp[0]['phone'];
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }elseif ($acm->hasAccess('planningTickBudget')){
            $sql3 = "INSERT INTO `budget_workflow` (`sender`,`receiver`,`bcid`,`createDate`,`createTime`,`description`) VALUES ({$res1[0]['user_id']},{$res2[0]['user_id']},{$bcid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

            $sql4 = "UPDATE `budget_components` SET `lastReceiver`={$res2[0]['user_id']} WHERE `RowID`={$bcid}";
            $db->Query($sql4);

            $phone = $resp1[0]['phone'];
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }elseif ($acm->hasAccess('productionTickBudget')){
            $sql3 = "INSERT INTO `budget_workflow` (`sender`,`receiver`,`bcid`,`createDate`,`createTime`,`description`) VALUES ({$res2[0]['user_id']},20,{$bcid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

            $sql4 = "UPDATE `budget_components` SET `lastReceiver`=20 WHERE `RowID`={$bcid}";
            $db->Query($sql4);

            $phone = '9153131176';
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }else{
            return false;
        }
    }

    public function finalTickBudgetComponents($bcid){
        $db = new DBi();

        $sqq = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid`={$bcid}";
        $rsq = $db->ArrayQuery($sqq);
        if (count($rsq) <= 0){
            $res = "هیچ جزئیاتی برای این مورد ثبت نشده است !";
            $out = "false";
            response($res,$out);
            exit;
        }
        $query = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid`={$bcid} AND (`productionTick`!=1 OR `planningTick`!=1)";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0){
            $res = "بعضی از جزئیات اجزای بودجه فروش تاییدیه برنامه ریزی یا تولید را ندارند !";
            $out = "false";
            response($res,$out);
            exit;
        }else{
            $sql = "UPDATE `budget_components` SET `finalTick`=1 WHERE `RowID`={$bcid}";
            $db->Query($sql);
            $aff = $db->AffectedRows();
            $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
            if (intval($aff) > 0){
                $sqlu = "UPDATE `budget_components_details` SET `finalTick`=1 WHERE `bcid`={$bcid}";
                $db->Query($sqlu);

                $query1 = "SELECT `RowID`,`farvardin`,`ordibehesht`,`khordad`,`tir`,`mordad`,`shahrivar`,`mehr`,`aban`,`azar`,`dey`,`bahman`,`esfand` FROM `budget_components_details` WHERE `bcid`={$bcid}";
                $rst1 = $db->ArrayQuery($query1);
                $cnt = count($rst1);
                for ($i=0;$i<$cnt;$i++){
                    $query2 = "UPDATE `budget_components_details` SET `farvardinTotal`={$rst1[$i]['farvardin']},`ordibeheshtTotal`={$rst1[$i]['ordibehesht']},`khordadTotal`={$rst1[$i]['khordad']},`tirTotal`={$rst1[$i]['tir']},`mordadTotal`={$rst1[$i]['mordad']},`shahrivarTotal`={$rst1[$i]['shahrivar']},`mehrTotal`={$rst1[$i]['mehr']},`abanTotal`={$rst1[$i]['aban']},`azarTotal`={$rst1[$i]['azar']},`deyTotal`={$rst1[$i]['dey']},`bahmanTotal`={$rst1[$i]['bahman']},`esfandTotal`={$rst1[$i]['esfand']} WHERE `RowID`={$rst1[$i]['RowID']}";
                    $db->Query($query2);
                }
                return true;
            }else{
                return false;
            }
        }
    }

    public function editBudgetComponentDetailsHtm($bcdid){
        $db = new DBi();
        $ut = new Utility();
        $nowDate = $ut->greg_to_jal(date('Y-m-d'));
        $month = (intval(strlen($nowDate)) == 8 ? intval(substr($nowDate,5,1)) : intval(substr($nowDate,5,2)) );
        $cyear = intval(substr($nowDate,0,4));

        $query = "SELECT * FROM `budget_components_details` WHERE `RowID`={$bcdid}";
        $rst = $db->ArrayQuery($query);

        $sqq = "SELECT `budgetID` FROM `budget_components` WHERE `RowID`={$rst[0]['bcid']}";
        $rstq = $db->ArrayQuery($sqq);

        $sqqq = "SELECT `year` FROM `budget` WHERE `RowID`={$rstq[0]['budgetID']}";
        $rstqq = $db->ArrayQuery($sqqq);

        $query1 = "SELECT `validDate` FROM `budget` WHERE `RowID`={$rstq[0]['budgetID']}";
        $rst1 = $db->ArrayQuery($query1);

        $nowDate = date('Y-m-d');
        if (strtotime($nowDate) > strtotime($rst1[0]['validDate'])){
            $res = "تاریخ اعتبار بودجه فروش منقضی شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="editBudgetComponentDetailsHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 23%;">نام محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">کد محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">برند</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">گروه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">فروردین</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">اردیبهشت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">خرداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">تیر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">مرداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">شهریور</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">مهر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">آبان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">آذر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">دی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">بهمن</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">اسفند</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        if (intval($cyear) == intval($rstqq[0]['year'])) {
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst[0]['gName'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst[0]['gCode'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst[0]['brand'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst[0]['ggroup'] . '</td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12)) ? 'disabled' : '') . ' value="' . $rst[0]['farvardin'] . '" id="editBudgetComponentsOne" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(3, 4, 5, 6, 7, 8, 9, 10, 11, 12)) ? 'disabled' : '') . ' value="' . $rst[0]['ordibehesht'] . '" id="editBudgetComponentsTwo" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(4, 5, 6, 7, 8, 9, 10, 11, 12)) ? 'disabled' : '') . ' value="' . $rst[0]['khordad'] . '" id="editBudgetComponentsThree" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(5, 6, 7, 8, 9, 10, 11, 12)) ? 'disabled' : '') . ' value="' . $rst[0]['tir'] . '" id="editBudgetComponentsFour" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(6, 7, 8, 9, 10, 11, 12)) ? 'disabled' : '') . ' value="' . $rst[0]['mordad'] . '" id="editBudgetComponentsFive" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(7, 8, 9, 10, 11, 12)) ? 'disabled' : '') . ' value="' . $rst[0]['shahrivar'] . '" id="editBudgetComponentsSix" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(8, 9, 10, 11, 12)) ? 'disabled' : '') . ' value="' . $rst[0]['mehr'] . '" id="editBudgetComponentsSeven" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(9, 10, 11, 12)) ? 'disabled' : '') . ' value="' . $rst[0]['aban'] . '" id="editBudgetComponentsEight" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(10, 11, 12)) ? 'disabled' : '') . ' value="' . $rst[0]['azar'] . '" id="editBudgetComponentsNine" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(11, 12)) ? 'disabled' : '') . ' value="' . $rst[0]['dey'] . '" id="editBudgetComponentsTen" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" ' . (in_array(intval($month), array(12)) ? 'disabled' : '') . ' value="' . $rst[0]['bahman'] . '" id="editBudgetComponentsEleven" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" value="' . $rst[0]['esfand'] . '" id="editBudgetComponentsTwelve" /></td>';
            $htm .= '</tr>';
        }else{
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst[0]['gName'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst[0]['gCode'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst[0]['brand'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst[0]['ggroup'] . '</td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" value="' . $rst[0]['farvardin'] . '" id="editBudgetComponentsOne" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" value="' . $rst[0]['ordibehesht'] . '" id="editBudgetComponentsTwo" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" value="' . $rst[0]['khordad'] . '" id="editBudgetComponentsThree" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" value="' . $rst[0]['tir'] . '" id="editBudgetComponentsFour" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" value="' . $rst[0]['mordad'] . '" id="editBudgetComponentsFive" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" value="' . $rst[0]['shahrivar'] . '" id="editBudgetComponentsSix" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" value="' . $rst[0]['mehr'] . '" id="editBudgetComponentsSeven" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" value="' . $rst[0]['aban'] . '" id="editBudgetComponentsEight" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" value="' . $rst[0]['azar'] . '" id="editBudgetComponentsNine" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" value="' . $rst[0]['dey'] . '" id="editBudgetComponentsTen" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" value="' . $rst[0]['bahman'] . '" id="editBudgetComponentsEleven" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control" value="' . $rst[0]['esfand'] . '" id="editBudgetComponentsTwelve" /></td>';
            $htm .= '</tr>';
        }

        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function editBudgetComponentDetails($bcdid,$farvardin,$ordibehesht,$khordad,$tir,$mordad,$shahrivar,$mehr,$aban,$azar,$dey,$bahman,$esfand){
        $db = new DBi();
        $sql = "UPDATE `budget_components_details` SET `productionTick`=2,`planningTick`=2,`productionDescription`='',`planningDescription`='',`farvardin`={$farvardin},`ordibehesht`={$ordibehesht},`khordad`={$khordad},`tir`={$tir},`mordad`={$mordad},`shahrivar`={$shahrivar},`mehr`={$mehr},`aban`={$aban},`azar`={$azar},`dey`={$dey},`bahman`={$bahman},`esfand`={$esfand} WHERE `RowID`={$bcdid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getBudgetComponentsDetailsExcel($bid){
        $db = new DBi();

        $sql = "SELECT `RowID` FROM `budget` WHERE `year`={$bid}";
        $res = $db->ArrayQuery($sql);

        $sql1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$res[0]['RowID']}";
        $res1 = $db->ArrayQuery($sql1);

        $rids = array();
        if (count($res1) > 0){
            $cnt = count($res1);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $res1[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $query = "SELECT * FROM `budget_components_details` WHERE `bcid` IN ({$rids}) ORDER BY `brand` ASC,`ggroup` ASC";
            $rst = $db->ArrayQuery($query);
            if (count($rst) > 0){
                $ccnt = count($rst);
                for ($j=0;$j<$ccnt;$j++){
                    $sqq = "SELECT `HCode` FROM `good` WHERE `gCode`='{$rst[$j]['gCode']}'";
                    $rsq = $db->ArrayQuery($sqq);
                    $rst[$j]['goodID'] = $rsq[0]['HCode'];
                }
                return $rst;
            }else{
                return array();
            }
        }else{
            return array();
        }
    }

    public function replaceNewBudget(){
        $db = new DBi();
        $ut = new Utility();

        $sqq = "SELECT * FROM `good`";
        $resq = $db->ArrayQuery($sqq);
        $cntq = count($resq);

        for ($i=0;$i<$cntq;$i++){
            $sql = "SELECT * FROM `eslahi` WHERE `A`='{$resq[$i]['gCode']}'";
            $res = $db->ArrayQuery($sql);
            if (count($res) > 0){
                $sql1 = "INSERT INTO `budget_components_details` (`bcid`,`goodID`,`gCode`,`gName`,`brand`,`ggroup`,`gsgroup`,`series`,`farvardin`,`ordibehesht`,`khordad`,`tir`,`mordad`,`shahrivar`,`mehr`,`aban`,`azar`,`dey`,`bahman`,`esfand`,`HCode`) VALUES (1,{$resq[$i]['RowID']},'{$resq[$i]['gCode']}','{$resq[$i]['gName']}','{$resq[$i]['brand']}','{$resq[$i]['ggroup']}','{$resq[$i]['gsgroup']}','{$resq[$i]['Series']}',{$res[0]['B']},{$res[0]['C']},{$res[0]['D']},{$res[0]['E']},{$res[0]['F']},{$res[0]['G']},{$res[0]['H']},{$res[0]['I']},{$res[0]['J']},{$res[0]['K']},{$res[0]['L']},{$res[0]['M']},'{$resq[$i]['HCode']}')";
                $rst = $db->Query($sql1);
                if (intval($rst) <= 0){
                    //$//ut->fileRecorder($sql1);
                }
            }else{
                $sql2 = "INSERT INTO `budget_components_details` (`bcid`,`goodID`,`gCode`,`gName`,`brand`,`ggroup`,`gsgroup`,`series`,`farvardin`,`ordibehesht`,`khordad`,`tir`,`mordad`,`shahrivar`,`mehr`,`aban`,`azar`,`dey`,`bahman`,`esfand`,`HCode`) VALUES (1,{$resq[$i]['RowID']},'{$resq[$i]['gCode']}','{$resq[$i]['gName']}','{$resq[$i]['brand']}','{$resq[$i]['ggroup']}','{$resq[$i]['gsgroup']}','{$resq[$i]['Series']}',0,0,0,0,0,0,0,0,0,0,0,0,'{$resq[$i]['HCode']}')";
                $rst1 = $db->Query($sql2);
                if (intval($rst1) <= 0){
                    //$//ut->fileRecorder($sql2);
                }
            }
        }

        return true;
    }

    //+++++++++++++++++++++++ قیمت بودجه +++++++++++++++++++++++++

    public function getBudgetPriceManageList($year,$component,$eCode,$gCode,$brand,$group,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('budgetPriceManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sql1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$year}";
        $res1 = $db->ArrayQuery($sql1);
        $cnt = count($res1);
        $rids = array();
        for ($i=0;$i<$cnt;$i++){
            $rids[] = $res1[$i]['RowID'];
        }
        $rids = implode(',',$rids);

        $w = array();
        if(intval($component) > 0){
            $w[] = '`RowID`='.$component.' ';
        }
        if(strlen(trim($eCode)) > 0){
            $w[] = '`gCode` LIKE "%'.$eCode.'%" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`HCode`="'.$gCode.'" ';
        }
        if(intval($brand) > 0){
            $query = "SELECT `title` FROM `categories` WHERE `RowID`={$brand}";
            $rst = $db->ArrayQuery($query);
            $w[] = '`brand`="'.$rst[0]['title'].'" ';
        }
        if(intval($group) > 0){
            $query1 = "SELECT `title` FROM `categories` WHERE `RowID`={$group}";
            $rst1 = $db->ArrayQuery($query1);
            $w[] = '`ggroup`="'.$rst1[0]['title'].'" ';
        }
        $w[] = '`bcid` IN ('.$rids.') ';

        $sql2 = "SELECT * FROM `budget_components_details`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql2 .= " WHERE ".$where;
        }
        //$sql2 .= " ORDER BY `budget_components_details`.`RowID` DESC LIMIT $start,".$numRows;
        $res2 = $db->ArrayQuery($sql2);
        $listCount = count($res2);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            $totalBudget = intval($res2[$y]['farvardin']) + intval($res2[$y]['ordibehesht']) + intval($res2[$y]['khordad']) + intval($res2[$y]['tir']) + intval($res2[$y]['mordad']) + intval($res2[$y]['shahrivar']) + intval($res2[$y]['mehr']) + intval($res2[$y]['aban']) + intval($res2[$y]['azar']) + intval($res2[$y]['dey']) + intval($res2[$y]['bahman']) + intval($res2[$y]['esfand']) + intval($res2[$y]['farvardinTotal']) + intval($res2[$y]['ordibeheshtTotal']) + intval($res2[$y]['khordadTotal']) + intval($res2[$y]['tirTotal']) + intval($res2[$y]['mordadTotal']) + intval($res2[$y]['shahrivarTotal']) + intval($res2[$y]['mehrTotal']) + intval($res2[$y]['abanTotal']) + intval($res2[$y]['azarTotal']) + intval($res2[$y]['deyTotal']) + intval($res2[$y]['bahmanTotal']) + intval($res2[$y]['esfandTotal']);
            if (intval($totalBudget) == 0){
                continue;
            }

            $sqq = "SELECT `salesListPrice` FROM `good` WHERE `RowID`={$res2[$y]['goodID']}";
            $rsq = $db->ArrayQuery($sqq);

            $sqq1 = "SELECT `RowID` FROM `discounts` WHERE `brand`='{$res2[$y]['brand']}' AND `subGroup`='{$res2[$y]['ggroup']}'";
            $rsq1 = $db->ArrayQuery($sqq1);

            $finalRes[$y]['RowID'] = $res2[$y]['RowID'];
            $finalRes[$y]['gName'] = $res2[$y]['gName'];
            $finalRes[$y]['gCode'] = $res2[$y]['gCode'];
            $finalRes[$y]['HCode'] = $res2[$y]['HCode'];

            $finalRes[$y]['farvardin'] = 1;
            if ((intval($res2[$y]['farvardin']) + intval($res2[$y]['farvardinTotal'])) == 0){  // اگر مقدار صفر بود
                $finalRes[$y][0]['btnType'] = 'btn-danger';
            }elseif ((intval($res2[$y]['farvardin']) + intval($res2[$y]['farvardinTotal'])) > 0 && intval($rsq[0]['salesListPrice']) == 0){  // اگر مقدار داشت و مبلغ صفر بود
                $finalRes[$y][0]['btnType'] = 'btn-warning';
            }elseif (count($rsq1) <= 0){  // درصد تخفیفات مشخص نشده است
                $finalRes[$y][0]['btnType'] = 'btn-success';
            }else{
                $finalRes[$y][0]['btnType'] = 'btn-info';
            }

            $finalRes[$y]['ordibehesht'] = 2;
            if ((intval($res2[$y]['ordibehesht']) + intval($res2[$y]['ordibeheshtTotal'])) == 0){  // اگر مقدار صفر بود
                $finalRes[$y][1]['btnType'] = 'btn-danger';
            }elseif ((intval($res2[$y]['ordibehesht']) + intval($res2[$y]['ordibeheshtTotal'])) > 0 && intval($rsq[0]['salesListPrice']) == 0){  // اگر مقدار داشت و مبلغ صفر بود
                $finalRes[$y][1]['btnType'] = 'btn-warning';
            }elseif (count($rsq1) <= 0){  // درصد تخفیفات مشخص نشده است
                $finalRes[$y][1]['btnType'] = 'btn-success';
            }else{
                $finalRes[$y][1]['btnType'] = 'btn-info';
            }

            $finalRes[$y]['khordad'] = 3;
            if ((intval($res2[$y]['khordad']) + intval($res2[$y]['khordadTotal'])) == 0){  // اگر مقدار صفر بود
                $finalRes[$y][2]['btnType'] = 'btn-danger';
            }elseif ((intval($res2[$y]['khordad']) + intval($res2[$y]['khordadTotal'])) > 0 && intval($rsq[0]['salesListPrice']) == 0){  // اگر مقدار داشت و مبلغ صفر بود
                $finalRes[$y][2]['btnType'] = 'btn-warning';
            }elseif (count($rsq1) <= 0){  // درصد تخفیفات مشخص نشده است
                $finalRes[$y][2]['btnType'] = 'btn-success';
            }else{
                $finalRes[$y][2]['btnType'] = 'btn-info';
            }

            $finalRes[$y]['tir'] = 4;
            if ((intval($res2[$y]['tir']) + intval($res2[$y]['tirTotal'])) == 0){  // اگر مقدار صفر بود
                $finalRes[$y][3]['btnType'] = 'btn-danger';
            }elseif ((intval($res2[$y]['tir']) + intval($res2[$y]['tirTotal'])) > 0 && intval($rsq[0]['salesListPrice']) == 0){  // اگر مقدار داشت و مبلغ صفر بود
                $finalRes[$y][3]['btnType'] = 'btn-warning';
            }elseif (count($rsq1) <= 0){  // درصد تخفیفات مشخص نشده است
                $finalRes[$y][3]['btnType'] = 'btn-success';
            }else{
                $finalRes[$y][3]['btnType'] = 'btn-info';
            }

            $finalRes[$y]['mordad'] = 5;
            if ((intval($res2[$y]['mordad']) + intval($res2[$y]['mordadTotal'])) == 0){  // اگر مقدار صفر بود
                $finalRes[$y][4]['btnType'] = 'btn-danger';
            }elseif ((intval($res2[$y]['mordad']) + intval($res2[$y]['mordadTotal'])) > 0 && intval($rsq[0]['salesListPrice']) == 0){  // اگر مقدار داشت و مبلغ صفر بود
                $finalRes[$y][4]['btnType'] = 'btn-warning';
            }elseif (count($rsq1) <= 0){  // درصد تخفیفات مشخص نشده است
                $finalRes[$y][4]['btnType'] = 'btn-success';
            }else{
                $finalRes[$y][4]['btnType'] = 'btn-info';
            }

            $finalRes[$y]['shahrivar'] = 6;
            if ((intval($res2[$y]['shahrivar']) + intval($res2[$y]['shahrivarTotal'])) == 0){  // اگر مقدار صفر بود
                $finalRes[$y][5]['btnType'] = 'btn-danger';
            }elseif ((intval($res2[$y]['shahrivar']) + intval($res2[$y]['shahrivarTotal'])) > 0 && intval($rsq[0]['salesListPrice']) == 0){  // اگر مقدار داشت و مبلغ صفر بود
                $finalRes[$y][5]['btnType'] = 'btn-warning';
            }elseif (count($rsq1) <= 0){  // درصد تخفیفات مشخص نشده است
                $finalRes[$y][5]['btnType'] = 'btn-success';
            }else{
                $finalRes[$y][5]['btnType'] = 'btn-info';
            }

            $finalRes[$y]['mehr'] = 7;
            if ((intval($res2[$y]['mehr']) + intval($res2[$y]['mehrTotal'])) == 0){  // اگر مقدار صفر بود
                $finalRes[$y][6]['btnType'] = 'btn-danger';
            }elseif ((intval($res2[$y]['mehr']) + intval($res2[$y]['mehrTotal'])) > 0 && intval($rsq[0]['salesListPrice']) == 0){  // اگر مقدار داشت و مبلغ صفر بود
                $finalRes[$y][6]['btnType'] = 'btn-warning';
            }elseif (count($rsq1) <= 0){  // درصد تخفیفات مشخص نشده است
                $finalRes[$y][6]['btnType'] = 'btn-success';
            }else{
                $finalRes[$y][6]['btnType'] = 'btn-info';
            }

            $finalRes[$y]['aban'] = 8;
            if ((intval($res2[$y]['aban']) + intval($res2[$y]['abanTotal'])) == 0){  // اگر مقدار صفر بود
                $finalRes[$y][7]['btnType'] = 'btn-danger';
            }elseif ((intval($res2[$y]['aban']) + intval($res2[$y]['abanTotal'])) > 0 && intval($rsq[0]['salesListPrice']) == 0){  // اگر مقدار داشت و مبلغ صفر بود
                $finalRes[$y][7]['btnType'] = 'btn-warning';
            }elseif (count($rsq1) <= 0){  // درصد تخفیفات مشخص نشده است
                $finalRes[$y][7]['btnType'] = 'btn-success';
            }else{
                $finalRes[$y][7]['btnType'] = 'btn-info';
            }

            $finalRes[$y]['azar'] = 9;
            if ((intval($res2[$y]['azar']) + intval($res2[$y]['azarTotal'])) == 0){  // اگر مقدار صفر بود
                $finalRes[$y][8]['btnType'] = 'btn-danger';
            }elseif ((intval($res2[$y]['azar']) + intval($res2[$y]['azarTotal'])) > 0 && intval($rsq[0]['salesListPrice']) == 0){  // اگر مقدار داشت و مبلغ صفر بود
                $finalRes[$y][8]['btnType'] = 'btn-warning';
            }elseif (count($rsq1) <= 0){  // درصد تخفیفات مشخص نشده است
                $finalRes[$y][8]['btnType'] = 'btn-success';
            }else{
                $finalRes[$y][8]['btnType'] = 'btn-info';
            }

            $finalRes[$y]['dey'] = 10;
            if ((intval($res2[$y]['dey']) + intval($res2[$y]['deyTotal'])) == 0){  // اگر مقدار صفر بود
                $finalRes[$y][9]['btnType'] = 'btn-danger';
            }elseif ((intval($res2[$y]['dey']) + intval($res2[$y]['deyTotal'])) > 0 && intval($rsq[0]['salesListPrice']) == 0){  // اگر مقدار داشت و مبلغ صفر بود
                $finalRes[$y][9]['btnType'] = 'btn-warning';
            }elseif (count($rsq1) <= 0){  // درصد تخفیفات مشخص نشده است
                $finalRes[$y][9]['btnType'] = 'btn-success';
            }else{
                $finalRes[$y][9]['btnType'] = 'btn-info';
            }

            $finalRes[$y]['bahman'] = 11;
            if ((intval($res2[$y]['bahman']) + intval($res2[$y]['bahmanTotal'])) == 0){  // اگر مقدار صفر بود
                $finalRes[$y][10]['btnType'] = 'btn-danger';
            }elseif ((intval($res2[$y]['bahman']) + intval($res2[$y]['bahmanTotal'])) > 0 && intval($rsq[0]['salesListPrice']) == 0){  // اگر مقدار داشت و مبلغ صفر بود
                $finalRes[$y][10]['btnType'] = 'btn-warning';
            }elseif (count($rsq1) <= 0){  // درصد تخفیفات مشخص نشده است
                $finalRes[$y][10]['btnType'] = 'btn-success';
            }else{
                $finalRes[$y][10]['btnType'] = 'btn-info';
            }

            $finalRes[$y]['esfand'] = 12;
            if ((intval($res2[$y]['esfand']) + intval($res2[$y]['esfandTotal'])) == 0){  // اگر مقدار صفر بود
                $finalRes[$y][11]['btnType'] = 'btn-danger';
            }elseif ((intval($res2[$y]['esfand']) + intval($res2[$y]['esfandTotal'])) > 0 && intval($rsq[0]['salesListPrice']) == 0){  // اگر مقدار داشت و مبلغ صفر بود
                $finalRes[$y][11]['btnType'] = 'btn-warning';
            }elseif (count($rsq1) <= 0){  // درصد تخفیفات مشخص نشده است
                $finalRes[$y][11]['btnType'] = 'btn-success';
            }else{
                $finalRes[$y][11]['btnType'] = 'btn-info';
            }
        }

        return array_values($finalRes);
    }

    public function getBudgetPriceManageListCountRows($year,$component,$eCode,$gCode,$brand,$group){
        $db = new DBi();

        $sql1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$year}";
        $res1 = $db->ArrayQuery($sql1);
        $cnt = count($res1);
        $rids = array();
        for ($i=0;$i<$cnt;$i++){
            $rids[] = $res1[$i]['RowID'];
        }
        $rids = implode(',',$rids);

        $w = array();
        if(intval($component) > 0){
            $w[] = '`RowID`='.$component.' ';
        }
        if(strlen(trim($eCode)) > 0){
            $w[] = '`gCode`="'.$eCode.'" ';
        }
        if(strlen(trim($gCode)) > 0){
            $w[] = '`HCode`="'.$gCode.'" ';
        }
        if(intval($brand) > 0){
            $query = "SELECT `title` FROM `categories` WHERE `RowID`={$brand}";
            $rst = $db->ArrayQuery($query);
            $w[] = '`brand`="'.$rst[0]['title'].'" ';
        }
        if(intval($group) > 0){
            $query1 = "SELECT `title` FROM `categories` WHERE `RowID`={$group}";
            $rst1 = $db->ArrayQuery($query1);
            $w[] = '`ggroup`="'.$rst1[0]['title'].'" ';
        }
        $w[] = '`bcid` IN ('.$rids.') ';

        $sql2 = "SELECT * FROM `budget_components_details`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql2 .= " WHERE ".$where;
        }
        $res2 = $db->ArrayQuery($sql2);
        $listCount = count($res2);
        $x = 0;
        for($y=0;$y<$listCount;$y++){
            $totalBudget = intval($res2[$y]['farvardin']) + intval($res2[$y]['ordibehesht']) + intval($res2[$y]['khordad']) + intval($res2[$y]['tir']) + intval($res2[$y]['mordad']) + intval($res2[$y]['shahrivar']) + intval($res2[$y]['mehr']) + intval($res2[$y]['aban']) + intval($res2[$y]['azar']) + intval($res2[$y]['dey']) + intval($res2[$y]['bahman']) + intval($res2[$y]['esfand']) + intval($res2[$y]['farvardinTotal']) + intval($res2[$y]['ordibeheshtTotal']) + intval($res2[$y]['khordadTotal']) + intval($res2[$y]['tirTotal']) + intval($res2[$y]['mordadTotal']) + intval($res2[$y]['shahrivarTotal']) + intval($res2[$y]['mehrTotal']) + intval($res2[$y]['abanTotal']) + intval($res2[$y]['azarTotal']) + intval($res2[$y]['deyTotal']) + intval($res2[$y]['bahmanTotal']) + intval($res2[$y]['esfandTotal']);
            if (intval($totalBudget) == 0){
                continue;
            }else{
                $x++;
            }
        }

        return intval($x);
    }

    public function getTotalBudgetPrice($year,$component,$eCode,$gCode,$brand,$group){
        $db = new DBi();
        $ut = new Utility();

        $sql1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$year} AND `finalTick`=1";
        $res1 = $db->ArrayQuery($sql1);
        $cnt = count($res1);
        $rids = array();
        for ($i = 0; $i < $cnt; $i++) {
            $rids[] = $res1[$i]['RowID'];
        }
        $rids = implode(',', $rids);

        $sql = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$year} AND `finalTick`=0";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $rowids = array();
        for ($i = 0; $i < $cnt; $i++) {
            $rowids[] = $res[$i]['RowID'];
        }
        $rowids = implode(',', $rowids);

        $w = array();
        $z = array();
        if (intval($component) > 0) {
            $w[] = '`budget_components_details`.`RowID`=' . $component . ' ';
            $z[] = '`budget_components_details`.`RowID`=' . $component . ' ';
        }
        if (strlen(trim($eCode)) > 0) {
            $w[] = '`budget_components_details`.`gCode` LIKE "%' . $eCode . '%" ';
            $z[] = '`budget_components_details`.`gCode` LIKE "%' . $eCode . '%" ';
        }
        if (strlen(trim($gCode)) > 0) {
            $w[] = '`budget_components_details`.`HCode`="' . $gCode . '" ';
            $z[] = '`budget_components_details`.`HCode`="' . $gCode . '" ';
        }
        if (intval($brand) > 0) {
            $query = "SELECT `title` FROM `categories` WHERE `RowID`={$brand}";
            $rst = $db->ArrayQuery($query);
            $w[] = '`budget_components_details`.`brand`="' . $rst[0]['title'] . '" ';
            $z[] = '`budget_components_details`.`brand`="' . $rst[0]['title'] . '" ';
        }
        if (intval($group) > 0) {
            $query1 = "SELECT `title` FROM `categories` WHERE `RowID`={$group}";
            $rst1 = $db->ArrayQuery($query1);
            $w[] = '`budget_components_details`.`ggroup`="' . $rst1[0]['title'] . '" ';
            $z[] = '`budget_components_details`.`ggroup`="' . $rst1[0]['title'] . '" ';
        }
        $w[] = '`budget_components_details`.`bcid` IN (' . $rids . ') ';
        $z[] = '`budget_components_details`.`bcid` IN (' . $rowids . ') ';

        $sql2 = "SELECT `budget_components_details`.`RowID` AS `bcdid`,`farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal`,`totalEntryNumber`,`salesListPrice`,`updatePriceDate` FROM `budget_components_details` INNER JOIN `good` ON (`budget_components_details`.`goodID`=`good`.`RowID`)";
        if (count($w) > 0) {
            $where = implode(" AND ", $w);
            $sql2 .= " WHERE " . $where;
        }
        $res2 = $db->ArrayQuery($sql2);
        $listCount = count($res2);

        $totalPrice = 0;
        $farvardinPrice = 0;
        $ordibeheshtPrice = 0;
        $khordadPrice = 0;
        $tirPrice = 0;
        $mordadPrice = 0;
        $shahrivarPrice = 0;
        $mehrPrice = 0;
        $abanPrice = 0;
        $azarPrice = 0;
        $deyPrice = 0;
        $bahmanPrice = 0;
        $esfandPrice = 0;
        for ($y = 0; $y < $listCount; $y++) {
            $totalBudget = intval($res2[$y]['farvardinTotal']) + intval($res2[$y]['ordibeheshtTotal']) + intval($res2[$y]['khordadTotal']) + intval($res2[$y]['tirTotal']) + intval($res2[$y]['mordadTotal']) + intval($res2[$y]['shahrivarTotal']) + intval($res2[$y]['mehrTotal']) + intval($res2[$y]['abanTotal']) + intval($res2[$y]['azarTotal']) + intval($res2[$y]['deyTotal']) + intval($res2[$y]['bahmanTotal']) + intval($res2[$y]['esfandTotal'] + intval($res2[$y]['totalEntryNumber']));

            $tNumber = array();
            for ($j=1;$j<13;$j++) {
                $query = "SELECT SUM(`number`) AS `bNum` FROM `budget_product_entry` WHERE `bid`={$year} AND `bcdid`={$res2[$y]['bcdid']} AND `month`={$j}";
                $rst = $db->ArrayQuery($query);
                $tNumber[] = $rst[0]['bNum'];
            }

            $totalPrice += $res2[$y]['salesListPrice'] * $totalBudget;
            $farvardinPrice += $res2[$y]['salesListPrice'] * (intval($res2[$y]['farvardinTotal']) + $tNumber[0]);
            $ordibeheshtPrice += $res2[$y]['salesListPrice'] * (intval($res2[$y]['ordibeheshtTotal']) + $tNumber[1]);
            $khordadPrice += $res2[$y]['salesListPrice'] * (intval($res2[$y]['khordadTotal']) + $tNumber[2]);
            $tirPrice += $res2[$y]['salesListPrice'] * (intval($res2[$y]['tirTotal']) + $tNumber[3]);
            $mordadPrice += $res2[$y]['salesListPrice'] * (intval($res2[$y]['mordadTotal']) + $tNumber[4]);
            $shahrivarPrice += $res2[$y]['salesListPrice'] * (intval($res2[$y]['shahrivarTotal']) + $tNumber[5]);
            $mehrPrice += $res2[$y]['salesListPrice'] * (intval($res2[$y]['mehrTotal']) + $tNumber[6]);
            $abanPrice += $res2[$y]['salesListPrice'] * (intval($res2[$y]['abanTotal']) + $tNumber[7]);
            $azarPrice += $res2[$y]['salesListPrice'] * (intval($res2[$y]['azarTotal']) + $tNumber[8]);
            $deyPrice += $res2[$y]['salesListPrice'] * (intval($res2[$y]['deyTotal']) + $tNumber[9]);
            $bahmanPrice += $res2[$y]['salesListPrice'] * (intval($res2[$y]['bahmanTotal']) + $tNumber[10]);
            $esfandPrice += $res2[$y]['salesListPrice'] * (intval($res2[$y]['esfandTotal']) + $tNumber[11]);
        }

        $sql3 = "SELECT `budget_components_details`.`RowID` AS `bcdid`,`farvardin`,`ordibehesht`,`khordad`,`tir`,`mordad`,`shahrivar`,`mehr`,`aban`,`azar`,`dey`,`bahman`,`esfand`,`totalEntryNumber`,`salesListPrice`,`updatePriceDate` FROM `budget_components_details` INNER JOIN `good` ON (`budget_components_details`.`goodID`=`good`.`RowID`)";
        if (count($z) > 0) {
            $where = implode(" AND ", $z);
            $sql3 .= " WHERE " . $where;
        }
        $res3 = $db->ArrayQuery($sql3);
        $listCount = count($res3);

        $updatePriceDate = (strlen(trim($res2[0]['updatePriceDate'])) > 0 ? $ut->greg_to_jal($res2[0]['updatePriceDate']) : $ut->greg_to_jal($res3[0]['updatePriceDate']));

        for ($y = 0; $y < $listCount; $y++) {
            $totalBudget = intval($res3[$y]['farvardin']) + intval($res3[$y]['ordibehesht']) + intval($res3[$y]['khordad']) + intval($res3[$y]['tir']) + intval($res3[$y]['mordad']) + intval($res3[$y]['shahrivar']) + intval($res3[$y]['mehr']) + intval($res3[$y]['aban']) + intval($res3[$y]['azar']) + intval($res3[$y]['dey']) + intval($res3[$y]['bahman']) + intval($res3[$y]['esfand'] + intval($res3[$y]['totalEntryNumber']));

            $tNumber = array();
            for ($j=1;$j<13;$j++) {
                $query = "SELECT SUM(`number`) AS `bNum` FROM `budget_product_entry` WHERE `bid`={$year} AND `bcdid`={$res3[$y]['bcdid']} AND `month`={$j}";
                $rst = $db->ArrayQuery($query);
                $tNumber[] = $rst[0]['bNum'];
            }

            $totalPrice += $res3[$y]['salesListPrice'] * $totalBudget;
            $farvardinPrice += $res3[$y]['salesListPrice'] * (intval($res3[$y]['farvardin']) + $tNumber[0]);
            $ordibeheshtPrice += $res3[$y]['salesListPrice'] * (intval($res3[$y]['ordibehesht']) + $tNumber[1]);
            $khordadPrice += $res3[$y]['salesListPrice'] * (intval($res3[$y]['khordad']) + $tNumber[2]);
            $tirPrice += $res3[$y]['salesListPrice'] * (intval($res3[$y]['tir']) + $tNumber[3]);
            $mordadPrice += $res3[$y]['salesListPrice'] * (intval($res3[$y]['mordad']) + $tNumber[4]);
            $shahrivarPrice += $res3[$y]['salesListPrice'] * (intval($res3[$y]['shahrivar']) + $tNumber[5]);
            $mehrPrice += $res3[$y]['salesListPrice'] * (intval($res3[$y]['mehr']) + $tNumber[6]);
            $abanPrice += $res3[$y]['salesListPrice'] * (intval($res3[$y]['aban']) + $tNumber[7]);
            $azarPrice += $res3[$y]['salesListPrice'] * (intval($res3[$y]['azar']) + $tNumber[8]);
            $deyPrice += $res3[$y]['salesListPrice'] * (intval($res3[$y]['dey']) + $tNumber[9]);
            $bahmanPrice += $res3[$y]['salesListPrice'] * (intval($res3[$y]['bahman']) + $tNumber[10]);
            $esfandPrice += $res3[$y]['salesListPrice'] * (intval($res3[$y]['esfand']) + $tNumber[11]);
        }

        $headerTxt = '<table class="table-light">';
        $headerTxt .= '<tr>';
        $headerTxt .= '<td class="bg-light"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> فروردین : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.number_format($farvardinPrice).' ریال</span></td>';
        $headerTxt .= '<td class="bg-light pr-5"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> اردیبهشت : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.number_format($ordibeheshtPrice).' ریال</span></td>';
        $headerTxt .= '<td class="bg-light pr-5"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> خرداد : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.number_format($khordadPrice).' ریال</span></td>';
        $headerTxt .= '</tr>';
        $headerTxt .= '<tr>';
        $headerTxt .= '<td class="bg-light"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> تیر : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.number_format($tirPrice).' ریال</span></td>';
        $headerTxt .= '<td class="bg-light pr-5"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> مرداد : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.number_format($mordadPrice).' ریال</span></td>';
        $headerTxt .= '<td class="bg-light pr-5"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> شهریور : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.number_format($shahrivarPrice).' ریال</span></td>';
        $headerTxt .= '</tr>';
        $headerTxt .= '<tr>';
        $headerTxt .= '<td class="bg-light"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> مهر : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.number_format($mehrPrice).' ریال</span></td>';
        $headerTxt .= '<td class="bg-light pr-5"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> آبان : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.number_format($abanPrice).' ریال</span></td>';
        $headerTxt .= '<td class="bg-light pr-5"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> آذر : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.number_format($azarPrice).' ریال</span></td>';
        $headerTxt .= '</tr>';
        $headerTxt .= '<tr>';
        $headerTxt .= '<td class="bg-light"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> دی : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.number_format($deyPrice).' ریال</span></td>';
        $headerTxt .= '<td class="bg-light pr-5"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> بهمن : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.number_format($bahmanPrice).' ریال</span></td>';
        $headerTxt .= '<td class="bg-light pr-5"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> اسفند : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.number_format($esfandPrice).' ریال</span></td>';
        $headerTxt .= '</tr>';
        $headerTxt .= '<tr>';
        $headerTxt .= '<td class="bg-light" colspan="3"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> مبلغ کل بودجه در این سال : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.number_format($totalPrice).' ریال</span></td>';
        $headerTxt .= '</tr>';
        $headerTxt .= '<tr>';
        $headerTxt .= '<td class="bg-light" colspan="3"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> تاریخ آخرین بروزرسانی قیمت ها : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">'.$updatePriceDate.'</span></td>';
        $headerTxt .= '</tr>';
        $headerTxt .= '<tr>';
        $headerTxt .= '<td class="bg-light" colspan="3"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> اگر بودجه مقداری در آن ماه نداشت : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;">رنگ قرمز</span></td>';
        $headerTxt .= '</tr>';
        $headerTxt .= '<tr>';
        $headerTxt .= '<td class="bg-light" colspan="3"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> اگر بودجه در آن ماه مقدار داشت، ولی آن محصول مبلغی نداشت : </span><span style="font-family: dubai-Regular;font-weight: bold;color: #ffc107;font-size: 20px;">رنگ زرد</span></td>';
        $headerTxt .= '</tr>';
        $headerTxt .= '<tr>';
        $headerTxt .= '<td class="bg-light" colspan="3"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> اگر درصد تخفیفات برای آن محصول ثبت نشده بود : </span><span style="font-family: dubai-Regular;font-weight: bold;color: green;font-size: 20px;">رنگ سبز</span></td>';
        $headerTxt .= '</tr>';
        $headerTxt .= '<tr>';
        $headerTxt .= '<td class="bg-light" colspan="3"><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> اگر محصول در آن ماه، هم مقدار و هم مبلغ داشت : </span><span style="font-family: dubai-Regular;font-weight: bold;color: #138496;font-size: 20px;">رنگ آبی</span></td>';
        $headerTxt .= '</tr>';
        $headerTxt .= '</table>';
        return $headerTxt;
    }

    public function budgetPriceDetailsHtm($bcdid,$month){
        $db = new DBi();

        $sql = "SELECT `budget_components_details`.`brand`,`budget_components_details`.`ggroup`,`farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal`,`farvardin`,`ordibehesht`,`khordad`,`tir`,`mordad`,`shahrivar`,`mehr`,`aban`,`azar`,`dey`,`bahman`,`esfand`,`finalTick`,`salesListPrice` FROM `budget_components_details` INNER JOIN `good` ON (`budget_components_details`.`goodID`=`good`.`RowID`) WHERE `budget_components_details`.`RowID`={$bcdid}";
        $res = $db->ArrayQuery($sql);

        $sqq = "SELECT `perDiscount1`,`perDiscount2`,`perDiscount3`,`perDiscount4`,`perDiscount5`,`Priority1`,`Priority2`,`Priority3`,`Priority4`,`Priority5` FROM `discounts` WHERE `brand`='{$res[0]['brand']}' AND `subGroup`='{$res[0]['ggroup']}'";
        $rsq = $db->ArrayQuery($sqq);

        if (count($rsq) > 0) {
            $Priority = array($rsq[0]['Priority1'], $rsq[0]['perDiscount1'], $rsq[0]['Priority2'], $rsq[0]['perDiscount2'], $rsq[0]['Priority3'], $rsq[0]['perDiscount3'], $rsq[0]['Priority4'], $rsq[0]['perDiscount4'], $rsq[0]['Priority5'], $rsq[0]['perDiscount5']);
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

            $pt1 = $res[0]['salesListPrice'] * (1 - $t1);
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

        switch ($month){
            case 1:
                $baceNumber = (intval($res[0]['finalTick']) == 1 ? intval($res[0]['farvardinTotal']) : intval($res[0]['farvardin']));
                break;
            case 2:
                $baceNumber = (intval($res[0]['finalTick']) == 1 ? intval($res[0]['ordibeheshtTotal']) : intval($res[0]['ordibehesht']));
                break;
            case 3:
                $baceNumber = (intval($res[0]['finalTick']) == 1 ? intval($res[0]['khordadTotal']) : intval($res[0]['khordad']));
                break;
            case 4:
                $baceNumber = (intval($res[0]['finalTick']) == 1 ? intval($res[0]['tirTotal']) : intval($res[0]['tir']));
                break;
            case 5:
                $baceNumber = (intval($res[0]['finalTick']) == 1 ? intval($res[0]['mordadTotal']) : intval($res[0]['mordad']));
                break;
            case 6:
                $baceNumber = (intval($res[0]['finalTick']) == 1 ? intval($res[0]['shahrivarTotal']) : intval($res[0]['shahrivar']));
                break;
            case 7:
                $baceNumber = (intval($res[0]['finalTick']) == 1 ? intval($res[0]['mehrTotal']) : intval($res[0]['mehr']));
                break;
            case 8:
                $baceNumber = (intval($res[0]['finalTick']) == 1 ? intval($res[0]['abanTotal']) : intval($res[0]['aban']));
                break;
            case 9:
                $baceNumber = (intval($res[0]['finalTick']) == 1 ? intval($res[0]['azarTotal']) : intval($res[0]['azar']));
                break;
            case 10:
                $baceNumber = (intval($res[0]['finalTick']) == 1 ? intval($res[0]['deyTotal']) : intval($res[0]['dey']));
                break;
            case 11:
                $baceNumber = (intval($res[0]['finalTick']) == 1 ? intval($res[0]['bahmanTotal']) : intval($res[0]['bahman']));
                break;
            case 12:
                $baceNumber = (intval($res[0]['finalTick']) == 1 ? intval($res[0]['esfandTotal']) : intval($res[0]['esfand']));
                break;
        }


        $priceTotal1 = number_format($res[0]['salesListPrice'] * $baceNumber);
        $priceTotal2 = number_format($Priority[1] * $baceNumber);
        $priceTotal3 = number_format($Priority[3] * $baceNumber);
        $priceTotal4 = number_format($Priority[5] * $baceNumber);
        $priceTotal5 = number_format($Priority[7] * $baceNumber);
        $priceTotal6 = number_format($Priority[9] * $baceNumber);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="budgetPriceDetailsHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">مقدار</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">قیمت لیست فروش</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">قیمت با تخفیف خرید مدت دار</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">قیمت با تخفیف نمایندگی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">قیمت با تخفیف پرداخت نقدی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">قیمت با تخفیف خرید لوله و اتصالات با هم</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">قیمت با تخفیف پایان دوره</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $htm .= '<tr class="table-secondary">';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$baceNumber.'</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$priceTotal1.'</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$priceTotal2.'</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$priceTotal3.'</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$priceTotal4.'</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$priceTotal5.'</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$priceTotal6.'</td>';
        $htm .= '</tr>';

        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    //*************************************************************************************************************************************************************************************
    //*************************************************************************************************************************************************************************************

    //+++++++++++++++++++++++ بودجه فروش نهایی +++++++++++++++++++++++++

    public function getFinalBudgetManagementHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('finalBudgetManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();

        $budgetYear = $this->getAllBudgetYear();
        $cntbu = count($budgetYear);

        $cDate = $ut->greg_to_jal(date('Y-m-d'));
        $cmonth = intval((intval(strlen($cDate)) == 8 ? substr($cDate,5,1) : substr($cDate,5,2) ));
        $months = array('فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند');

        $pagename = array();
        $pageIcon = array();
        $contentId = array();
        $menuItems = array();
        $bottons = array();
        $headerSearch = array();
        $access = array();
        $hiddenContentId[] = "hiddenFinalBudgetPrintBody";

        $x = 0;
        $y = 0;
        $z = 0;
        $manifold = 0;
        if($acm->hasAccess('finalBudgetManagement')) {
            $pagename[$x] = "بودجه فروش نهایی پس از اعمال تغییرات";
            $pageIcon[$x] = "fa-money-bill";
            $contentId[$x] = "finalBudgetManagementBody";
            $menuItems[$x] = 'finalBudgetManagementTabID';

            $bottons1 = array();
            $headerSearch1 = array();

            $b = 0;
            if ($acm->hasAccess("excelexport")) {
                $bottons1[$b]['title'] = "خروجی اکسل تغییرات بودجه";
                $bottons1[$b]['jsf'] = "getFinalBudgetManageExcel";
                $bottons1[$b]['icon'] = "fa-file-excel";
                $b++;

                $bottons1[$b]['title'] = "خروجی اکسل مقادیر نهایی";
                $bottons1[$b]['jsf'] = "getFinalBudgetNumberExcel";
                $bottons1[$b]['icon'] = "fa-file-excel";
            }

            $a = 0;
            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['width'] = "160px";
            $headerSearch1[$a]['id'] = "finalBudgetManageYearSearch";
            $headerSearch1[$a]['onchange'] = "onchange=getYearBudgetComponents8()";
            $headerSearch1[$a]['title'] = "سال بودجه فروش";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = "سال بودجه فروش";
            $headerSearch1[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cntbu;$i++){
                $headerSearch1[$a]['options'][$i+1]["title"] = $budgetYear[$i]['Name'];
                $headerSearch1[$a]['options'][$i+1]["value"] = $budgetYear[$i]['RowID'];
            }
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['width'] = "400px";
            $headerSearch1[$a]['id'] = "finalBudgetManageComponentSearch";
            $headerSearch1[$a]['multiple'] = "multiple";
            $headerSearch1[$a]['LimitNumSelections'] = 1;
            $headerSearch1[$a]['title'] = "انتخاب محصول";
            $headerSearch1[$a]['options'] = array();
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "finalBudgetManageGCodeSearch";
            $headerSearch1[$a]['title'] = "کد محصول";
            $headerSearch1[$a]['placeholder'] = "کد محصول";
            $a++;

            $headerSearch1[$a]['type'] = "btn";
            $headerSearch1[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch1[$a]['jsf'] = "showFinalBudgetManagementList";

            $bottons[$y] = $bottons1;
            $headerSearch[$z] = $headerSearch1;

            $manifold++;
            $access[] = 1;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('outProgramBudgetManage')) {
            $pagename[$x] = "بودجه فروش خارج از برنامه";
            $pageIcon[$x] = "fa-file-pen";
            $contentId[$x] = "outProgramBudgetManageBody";
            $menuItems[$x] = 'outProgramBudgetManageTabID';

            $bottons2 = array();
            $headerSearch2 = array();

            $b = 0;
            if($acm->hasAccess('editCreateOutProgramBudget')) {
                $bottons2[$b]['title'] = "درخواست بودجه فروش خارج از برنامه";
                $bottons2[$b]['jsf'] = "createOutProgramBudget";
                $bottons2[$b]['icon'] = "fa-plus-square";
                $b++;
            }

            $bottons2[$b]['title'] = "ارجاع درخواست";
            $bottons2[$b]['jsf'] = "sendOutProgramBudget";
            $bottons2[$b]['icon'] = "fa-paper-plane";

            $a = 0;
            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['width'] = "160px";
            $headerSearch2[$a]['id'] = "outProgramBudgetYearSearch";
            $headerSearch2[$a]['onchange'] = "onchange=getYearBudgetComponents1()";
            $headerSearch2[$a]['title'] = "سال بودجه فروش";
            $headerSearch2[$a]['options'] = array();
            $headerSearch2[$a]['options'][0]["title"] = "سال بودجه فروش";
            $headerSearch2[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cntbu;$i++){
                $headerSearch2[$a]['options'][$i+1]["title"] = $budgetYear[$i]['Name'];
                $headerSearch2[$a]['options'][$i+1]["value"] = $budgetYear[$i]['RowID'];
            }
            $a++;

            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['width'] = "400px";
            $headerSearch2[$a]['id'] = "outProgramBudgetComponentSearch";
            $headerSearch2[$a]['onchange'] = "onchange=emptyOutProgramBudgetHCodeSearch()";
            $headerSearch2[$a]['multiple'] = "multiple";
            $headerSearch2[$a]['LimitNumSelections'] = 1;
            $headerSearch2[$a]['title'] = "انتخاب محصول";
            $headerSearch2[$a]['options'] = array();
            $a++;

            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['width'] = "120px";
            $headerSearch2[$a]['id'] = "outProgramBudgetMonthSearch";
            $headerSearch2[$a]['title'] = "ماه";
            $headerSearch2[$a]['options'] = array();
            $headerSearch2[$a]['options'][0]["title"] = "انتخاب ماه";
            $headerSearch2[$a]['options'][0]["value"] = -1;
            $headerSearch2[$a]['options'][1]["title"] = "فروردین";
            $headerSearch2[$a]['options'][1]["value"] = 1;
            $headerSearch2[$a]['options'][2]["title"] = "اردیبهشت";
            $headerSearch2[$a]['options'][2]["value"] = 2;
            $headerSearch2[$a]['options'][3]["title"] = "خرداد";
            $headerSearch2[$a]['options'][3]["value"] = 3;
            $headerSearch2[$a]['options'][4]["title"] = "تیر";
            $headerSearch2[$a]['options'][4]["value"] = 4;
            $headerSearch2[$a]['options'][5]["title"] = "مرداد";
            $headerSearch2[$a]['options'][5]["value"] = 5;
            $headerSearch2[$a]['options'][6]["title"] = "شهریور";
            $headerSearch2[$a]['options'][6]["value"] = 6;
            $headerSearch2[$a]['options'][7]["title"] = "مهر";
            $headerSearch2[$a]['options'][7]["value"] = 7;
            $headerSearch2[$a]['options'][8]["title"] = "آبان";
            $headerSearch2[$a]['options'][8]["value"] = 8;
            $headerSearch2[$a]['options'][9]["title"] = "آذر";
            $headerSearch2[$a]['options'][9]["value"] = 9;
            $headerSearch2[$a]['options'][10]["title"] = "دی";
            $headerSearch2[$a]['options'][10]["value"] = 10;
            $headerSearch2[$a]['options'][11]["title"] = "بهمن";
            $headerSearch2[$a]['options'][11]["value"] = 11;
            $headerSearch2[$a]['options'][12]["title"] = "اسفند";
            $headerSearch2[$a]['options'][12]["value"] = 12;
            $a++;

            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "150px";
            $headerSearch2[$a]['id'] = "outProgramBudgetHCodeSearch";
            $headerSearch2[$a]['onchange'] = "onchange=emptyOutProgramBudgetComponentSearch()";
            $headerSearch2[$a]['title'] = "کد محصول";
            $headerSearch2[$a]['placeholder'] = "کد محصول";
            $a++;

            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['width'] = "120px";
            $headerSearch2[$a]['id'] = "outProgramBudgetFinalTickSearch";
            $headerSearch2[$a]['title'] = "وضعیت";
            $headerSearch2[$a]['options'] = array();
            $headerSearch2[$a]['options'][0]["title"] = "نهایی نشده";
            $headerSearch2[$a]['options'][0]["value"] = 0;
            $headerSearch2[$a]['options'][1]["title"] = "نهایی شده";
            $headerSearch2[$a]['options'][1]["value"] = 1;
            $a++;

            $headerSearch2[$a]['type'] = "btn";
            $headerSearch2[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch2[$a]['jsf'] = "showOutProgramBudgetList";

            $bottons[$y] = $bottons2;
            $headerSearch[$z] = $headerSearch2;

            $manifold++;
            $access[] = 2;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('displacementBudgetManage')) {
            $pagename[$x] = "درخواست جابجایی بودجه فروش";
            $pageIcon[$x] = "fa-rotate-right";
            $contentId[$x] = "displacementBudgetManageBody";
            $menuItems[$x] = 'displacementBudgetManageTabID';

            $bottons3 = array();
            $headerSearch3 = array();

            $b = 0;
            if($acm->hasAccess('editCreateDisplacementBudget')) {
                $bottons3[$b]['title'] = "درخواست جابجایی بودجه فروش";
                $bottons3[$b]['jsf'] = "createDisplacementBudget";
                $bottons3[$b]['icon'] = "fa-plus-square";
                $b++;
            }

            $bottons3[$b]['title'] = "ارجاع درخواست";
            $bottons3[$b]['jsf'] = "sendDisplacementBudget";
            $bottons3[$b]['icon'] = "fa-paper-plane";

            $a = 0;
            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['width'] = "170px";
            $headerSearch3[$a]['id'] = "displacementBudgetYearSearch";
            $headerSearch3[$a]['onchange'] = "onchange=getYearBudgetComponents2()";
            $headerSearch3[$a]['title'] = "از سال";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = "از سال";
            $headerSearch3[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cntbu;$i++){
                $headerSearch3[$a]['options'][$i+1]["title"] = $budgetYear[$i]['Name'];
                $headerSearch3[$a]['options'][$i+1]["value"] = $budgetYear[$i]['RowID'];
            }
            $a++;

            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['width'] = "120px";
            $headerSearch3[$a]['id'] = "displacementBudgetMonthSearch";
            $headerSearch3[$a]['title'] = "ماه";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = "از ماه";
            $headerSearch3[$a]['options'][0]["value"] = -1;
            $headerSearch3[$a]['options'][1]["title"] = "فروردین";
            $headerSearch3[$a]['options'][1]["value"] = 1;
            $headerSearch3[$a]['options'][2]["title"] = "اردیبهشت";
            $headerSearch3[$a]['options'][2]["value"] = 2;
            $headerSearch3[$a]['options'][3]["title"] = "خرداد";
            $headerSearch3[$a]['options'][3]["value"] = 3;
            $headerSearch3[$a]['options'][4]["title"] = "تیر";
            $headerSearch3[$a]['options'][4]["value"] = 4;
            $headerSearch3[$a]['options'][5]["title"] = "مرداد";
            $headerSearch3[$a]['options'][5]["value"] = 5;
            $headerSearch3[$a]['options'][6]["title"] = "شهریور";
            $headerSearch3[$a]['options'][6]["value"] = 6;
            $headerSearch3[$a]['options'][7]["title"] = "مهر";
            $headerSearch3[$a]['options'][7]["value"] = 7;
            $headerSearch3[$a]['options'][8]["title"] = "آبان";
            $headerSearch3[$a]['options'][8]["value"] = 8;
            $headerSearch3[$a]['options'][9]["title"] = "آذر";
            $headerSearch3[$a]['options'][9]["value"] = 9;
            $headerSearch3[$a]['options'][10]["title"] = "دی";
            $headerSearch3[$a]['options'][10]["value"] = 10;
            $headerSearch3[$a]['options'][11]["title"] = "بهمن";
            $headerSearch3[$a]['options'][11]["value"] = 11;
            $headerSearch3[$a]['options'][12]["title"] = "اسفند";
            $headerSearch3[$a]['options'][12]["value"] = 12;
            $a++;

            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['width'] = "400px";
            $headerSearch3[$a]['id'] = "displacementBudgetComponentSearch";
            $headerSearch3[$a]['onchange'] = "onchange=emptyDisplacementBudgetHCodeSearch()";
            $headerSearch3[$a]['multiple'] = "multiple";
            $headerSearch3[$a]['LimitNumSelections'] = 1;
            $headerSearch3[$a]['title'] = "انتخاب محصول";
            $headerSearch3[$a]['options'] = array();
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "150px";
            $headerSearch3[$a]['id'] = "displacementBudgetHCodeSearch";
            $headerSearch3[$a]['onchange'] = "onchange=emptyDisplacementBudgetComponentSearch()";
            $headerSearch3[$a]['title'] = "کد محصول";
            $headerSearch3[$a]['placeholder'] = "کد محصول";
            $a++;

            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['width'] = "120px";
            $headerSearch3[$a]['id'] = "displacementBudgetFinalTickSearch";
            $headerSearch3[$a]['title'] = "وضعیت";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = "نهایی نشده";
            $headerSearch3[$a]['options'][0]["value"] = 0;
            $headerSearch3[$a]['options'][1]["title"] = "نهایی شده";
            $headerSearch3[$a]['options'][1]["value"] = 1;
            $a++;

            $headerSearch3[$a]['type'] = "btn";
            $headerSearch3[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch3[$a]['jsf'] = "showDisplacementBudgetList";

            $bottons[$y] = $bottons3;
            $headerSearch[$z] = $headerSearch3;

            $manifold++;
            $access[] = 3;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('delayBudgetManage')) {
            $pagename[$x] = "تاخیر در تحویل بودجه فروش";
            $pageIcon[$x] = "fa-clock-rotate-left";
            $contentId[$x] = "delayBudgetManageBody";
            $menuItems[$x] = 'delayBudgetManageTabID';

            $bottons4 = array();
            $headerSearch4 = array();

            $b = 0;
            if($acm->hasAccess('editCreateDelayBudget')) {
                $bottons4[$b]['title'] = "مجوز تاخیر در تحویل";
                $bottons4[$b]['jsf'] = "createDelayBudget";
                $bottons4[$b]['icon'] = "fa-plus-square";
                $b++;
            }

            $bottons4[$b]['title'] = "ارجاع درخواست";
            $bottons4[$b]['jsf'] = "sendDelayBudget";
            $bottons4[$b]['icon'] = "fa-paper-plane";

            $a = 0;
            $headerSearch4[$a]['type'] = "select";
            $headerSearch4[$a]['width'] = "120px";
            $headerSearch4[$a]['id'] = "delayBudgetYearSearch";
            $headerSearch4[$a]['onchange'] = "onchange=getYearBudgetComponents4()";
            $headerSearch4[$a]['title'] = "سال بودجه فروش";
            $headerSearch4[$a]['options'] = array();
            $headerSearch4[$a]['options'][0]["title"] = "سال بودجه فروش";
            $headerSearch4[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cntbu;$i++){
                $headerSearch4[$a]['options'][$i+1]["title"] = $budgetYear[$i]['Name'];
                $headerSearch4[$a]['options'][$i+1]["value"] = $budgetYear[$i]['RowID'];
            }
            $a++;

            $headerSearch4[$a]['type'] = "select";
            $headerSearch4[$a]['width'] = "400px";
            $headerSearch4[$a]['id'] = "delayBudgetComponentSearch";
            $headerSearch4[$a]['onchange'] = "onchange=emptyDelayBudgetHCodeSearch()";
            $headerSearch4[$a]['multiple'] = "multiple";
            $headerSearch4[$a]['LimitNumSelections'] = 1;
            $headerSearch4[$a]['title'] = "انتخاب محصول";
            $headerSearch4[$a]['options'] = array();
            $a++;

            $headerSearch4[$a]['type'] = "select";
            $headerSearch4[$a]['width'] = "120px";
            $headerSearch4[$a]['id'] = "delayBudgetMonthSearch";
            $headerSearch4[$a]['title'] = "ماه";
            $headerSearch4[$a]['options'] = array();
            $headerSearch4[$a]['options'][0]["title"] = "انتخاب ماه";
            $headerSearch4[$a]['options'][0]["value"] = -1;
            $headerSearch4[$a]['options'][1]["title"] = "فروردین";
            $headerSearch4[$a]['options'][1]["value"] = 1;
            $headerSearch4[$a]['options'][2]["title"] = "اردیبهشت";
            $headerSearch4[$a]['options'][2]["value"] = 2;
            $headerSearch4[$a]['options'][3]["title"] = "خرداد";
            $headerSearch4[$a]['options'][3]["value"] = 3;
            $headerSearch4[$a]['options'][4]["title"] = "تیر";
            $headerSearch4[$a]['options'][4]["value"] = 4;
            $headerSearch4[$a]['options'][5]["title"] = "مرداد";
            $headerSearch4[$a]['options'][5]["value"] = 5;
            $headerSearch4[$a]['options'][6]["title"] = "شهریور";
            $headerSearch4[$a]['options'][6]["value"] = 6;
            $headerSearch4[$a]['options'][7]["title"] = "مهر";
            $headerSearch4[$a]['options'][7]["value"] = 7;
            $headerSearch4[$a]['options'][8]["title"] = "آبان";
            $headerSearch4[$a]['options'][8]["value"] = 8;
            $headerSearch4[$a]['options'][9]["title"] = "آذر";
            $headerSearch4[$a]['options'][9]["value"] = 9;
            $headerSearch4[$a]['options'][10]["title"] = "دی";
            $headerSearch4[$a]['options'][10]["value"] = 10;
            $headerSearch4[$a]['options'][11]["title"] = "بهمن";
            $headerSearch4[$a]['options'][11]["value"] = 11;
            $headerSearch4[$a]['options'][12]["title"] = "اسفند";
            $headerSearch4[$a]['options'][12]["value"] = 12;
            $a++;

            $headerSearch4[$a]['type'] = "text";
            $headerSearch4[$a]['width'] = "150px";
            $headerSearch4[$a]['id'] = "delayBudgetHCodeSearch";
            $headerSearch4[$a]['onchange'] = "onchange=emptyDelayBudgetComponentSearch()";
            $headerSearch4[$a]['title'] = "کد محصول";
            $headerSearch4[$a]['placeholder'] = "کد محصول";
            $a++;

            $headerSearch4[$a]['type'] = "select";
            $headerSearch4[$a]['width'] = "120px";
            $headerSearch4[$a]['id'] = "delayBudgetFinalTickSearch";
            $headerSearch4[$a]['title'] = "وضعیت";
            $headerSearch4[$a]['options'] = array();
            $headerSearch4[$a]['options'][0]["title"] = "نهایی نشده";
            $headerSearch4[$a]['options'][0]["value"] = 0;
            $headerSearch4[$a]['options'][1]["title"] = "نهایی شده";
            $headerSearch4[$a]['options'][1]["value"] = 1;
            $a++;

            $headerSearch4[$a]['type'] = "btn";
            $headerSearch4[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch4[$a]['jsf'] = "showDelayBudgetList";

            $bottons[$y] = $bottons4;
            $headerSearch[$z] = $headerSearch4;

            $manifold++;
            $access[] = 4;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('amendmentBudgetManage')) {
            $pagename[$x] = "اصلاحیه بودجه فروش";
            $pageIcon[$x] = "fa-edit";
            $contentId[$x] = "amendmentBudgetManageBody";
            $menuItems[$x] = 'amendmentBudgetManageTabID';

            $bottons5 = array();
            $headerSearch5 = array();

            

            $b = 0;
            if($acm->hasAccess('editCreateAmendmentBudget')) {
                $bottons5[$b]['title'] = "سوابق تغییر بودجه ";
                $bottons5[$b]['jsf'] = "createHistoryAmendmentBudget";
                $bottons5[$b]['icon'] = "fa-history";
                $b++;
				
				$bottons5[$b]['title'] = "مشاهده جدول اصلاحیه بودجه";
                $bottons5[$b]['jsf'] = "displayAmendmentBudget";
                $bottons5[$b]['icon'] = "fa-eye";
                $b++;

                $bottons5[$b]['title'] = "ثبت اصلاحیه";
                $bottons5[$b]['jsf'] = "createAmendmentBudget";
                $bottons5[$b]['icon'] = "fa-plus-square";
                $b++;
		
				
            }

            $bottons5[$b]['title'] = "ارجاع اصلاحیه";
            $bottons5[$b]['jsf'] = "sendAmendmentBudget";
            $bottons5[$b]['icon'] = "fa-paper-plane";

            $a = 0;
            $headerSearch5[$a]['type'] = "select";
            $headerSearch5[$a]['width'] = "120px";
            $headerSearch5[$a]['id'] = "amendmentBudgetYearSearch";
            $headerSearch5[$a]['onchange'] = "onchange=getYearBudgetComponents9()";
            $headerSearch5[$a]['title'] = "سال بودجه فروش";
            $headerSearch5[$a]['options'] = array();
            $headerSearch5[$a]['options'][0]["title"] = "سال بودجه فروش";
            $headerSearch5[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cntbu;$i++){
                $headerSearch5[$a]['options'][$i+1]["title"] = $budgetYear[$i]['Name'];
                $headerSearch5[$a]['options'][$i+1]["value"] = $budgetYear[$i]['RowID'];
            }
            $a++;

            $headerSearch5[$a]['type'] = "select";
            $headerSearch5[$a]['width'] = "400px";
            $headerSearch5[$a]['id'] = "amendmentBudgetComponentSearch";
            $headerSearch5[$a]['multiple'] = "multiple";
            $headerSearch5[$a]['LimitNumSelections'] = 1;
            $headerSearch5[$a]['title'] = "انتخاب محصول";
            $headerSearch5[$a]['options'] = array();
            $a++;

            $headerSearch5[$a]['type'] = "btn";
            $headerSearch5[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch5[$a]['jsf'] = "showAmendmentBudgetList";

            $bottons[$y] = $bottons5;
            $headerSearch[$z] = $headerSearch5;

            $manifold++;
            $access[] = 5;
        }
        $htm = $ut->getHtmlOfDefaultManagementMultiPage($pagename,$pageIcon,$contentId,$menuItems,$bottons,$manifold,$headerSearch,$hiddenContentId);

        //++++++++++++++++++++++++++++++++++ show Details Of Final Budget Modal ++++++++++++++++++++++++++++++++
     
        $modalID = "createHistoryAmendmentBudgetModal";
        $modalTitle = "  سوابق تغییرات بودجه";

        $ShowDescription = 'createHistoryAmendmentBudgetModal-body';
        $style = 'style="max-width: 1460px;"';

        $c = 0;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createHistoryAmendmentBudgetComponents";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['actionsBox'] = "true";
        $items[$c]['title'] = "انتخاب محصول";
        $items[$c]['style'] = "style='width: 50%;height:40%'";
        $items[$c]['options'] = array();

        $topperBottons = array();
        $topperBottons[0]['title'] = "ایجاد جدول";
        $topperBottons[0]['jsf'] = "createTableHistoryAmendmentBudget";
        $topperBottons[0]['type'] = "btn-success";
        $topperBottons[0]['data-dismiss'] = "No";

        $topperBottons[1]['title'] = "انصراف";
        $topperBottons[1]['type'] = "dismis";
        $createHistoryAmendmentBudget = $ut->getHtmlModal($modalID,$modalTitle,$items,array(),'','',$style,$ShowDescription,'','',$topperBottons);
        //++++++++++++++++++++++++++++++ END OF show Details Of Final Budget Modal ++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ show Details Of Final Budget Modal ++++++++++++++++++++++++++++++++
        $modalID = "showDetailsOfFinalBudgetModal";
        $modalTitle = "تغییرات بودجه فروش";
        $style = 'style="max-width: 650px;"';
        $ShowDescription = 'showDetailsOfFinalBudget-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showDetailsOfFinalBudget = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF show Details Of Final Budget Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ create out Program Budget MODAL ++++++++++++++++++++++++++++++++
        $modalID = "createOutProgramBudgetModal";
        $modalTitle = "فرم ثبت/ویرایش بودجه فروش خارج از برنامه";
        $style = 'style="max-width: 651px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createOutProgramBudgetYear";
        $items[$c]['title'] = "بودجه فروش سال";
        $items[$c]['style'] = "style='width: 85%;'";
        $items[$c]['onchange'] = "onchange=getYearBudgetComponents()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntbu;$i++){
            $items[$c]['options'][$i+1]["title"] = $budgetYear[$i]['Name'];
            $items[$c]['options'][$i+1]["value"] = $budgetYear[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createOutProgramBudgetMonth";
        $items[$c]['title'] = "انتخاب ماه";
        $items[$c]['style'] = "style='width: 60%;'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        $mNum = 13 - $cmonth;
        $keey = $cmonth;
        for ($i=0;$i<$mNum;$i++){
            $items[$c]['options'][$i+1]["title"] = $months[$keey-1];
            $items[$c]['options'][$i+1]["value"] = $keey;
            $keey++;
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createOutProgramBudgetComponents";
        $items[$c]['onchange'] = "onchange=getOutProgramHCodeWithName()";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['LimitNumSelections'] = 1;
        $items[$c]['title'] = "انتخاب محصول";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createOutProgramBudgetHCode";
        $items[$c]['onchange'] = "onchange=getOutProgramProductNameWithHcode()";
        $items[$c]['title'] = "کد محصول";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createOutProgramBudgetNumber";
        $items[$c]['title'] = "مقدار درخواست خارج از برنامه";
        $items[$c]['placeholder'] = "مقدار";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createOutProgramBudgetCName";
        $items[$c]['title'] = "نام مشتری";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createOutProgramBudgetNDate";
        $items[$c]['title'] = "تاریخ نیاز";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createOutProgramBudgetSDate";
        $items[$c]['title'] = "تاریخ تامین";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "createOutProgramBudgetDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "outProgramBudgetHiddenOpbid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateOutProgramBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createOutProgramBudget = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF Create Out Program Budget MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ out Program Budget Comment Modal ++++++++++++++++++++++++++++++++
        $modalID = "outProgramBudgetCommentModal";
        $modalTitle = "فرم تایید / عدم تایید و ثبت نظر";
        $style = 'style="max-width: 651px;"';
        $ShowDescription = 'outProgramBudgetComment-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "outProgramBudgetCommentType";
        $items[$c]['title'] = "انتخاب نمایید";
        $items[$c]['options'][0]['title'] = "تایید";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "عدم تایید";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "outProgramBudgetCommentWarning";
        $items[$c]['title'] = "هشدار";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['placeholder'] = "در صورت تایید، نظر شما ثبت خواهد شد و امکان تغییر نخواهید داشت.";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "outProgramBudgetCommentDesc";
        $items[$c]['title'] = "ثبت نظرات";
        $items[$c]['placeholder'] = "نظرات خود را بنویسید";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "outProgramBudgetCommentHiddenOpbid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doRecordOutProgramBudgetComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $outProgramBudgetComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF out Program Budget Comment Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ send out Program Budget Modal ++++++++++++++++++++++++++++++++
        $modalID = "sendOutProgramBudgetModal";
        $modalTitle = "ارجاع درخواست";
        $style = 'style="max-width: 800px;"';
        $ShowDescription = 'sendOutProgramBudgetModal-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "sendOutProgramBudgetDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "sendOutProgramBudgetHiddenOpbid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doSendOutProgramBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $sensOutProgramBudget = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF send out Program Budget Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ show Out Program Budget Info Modal ++++++++++++++++++++++++++++++++
        $modalID = "showOutProgramBudgetInfoModal";
        $modalTitle = "سایر اطلاعات";
        $style = 'style="max-width: 500px;"';
        $ShowDescription = 'showOutProgramBudgetInfo-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showOutProgramBudgetInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF show Out Program Budget Info Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ show Out Program Budget Workflow Modal ++++++++++++++++++++++++++++++++
        $modalID = "showOutProgramBudgetWorkflowModal";
        $modalTitle = "گردش کار";
        $style = 'style="max-width: 800px;"';
        $ShowDescription = 'showOutProgramBudgetWorkflow-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showOutProgramBudgetWorkflow = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF show Out Program Budget Workflow Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Final approval MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "outProgramBudgetFinalTickModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "outProgramBudgetFinalTickIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doFinalTickOutProgramBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $outProgramBudgetFinalTick = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF Final approval MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ create displacement Budget MODAL ++++++++++++++++++++++++++++++++
        $modalID = "createDisplacementBudgetModal";
        $modalTitle = "فرم درخواست جابجایی بودجه فروش";
        $style = 'style="max-width: 651px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createDisplacementBudgetYear";
        $items[$c]['title'] = "از سال";
        $items[$c]['style'] = "style='width: 70%;'";
        $items[$c]['onchange'] = "onchange=getYearBudgetComponents3()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntbu;$i++){
            $items[$c]['options'][$i+1]["title"] = $budgetYear[$i]['Name'];
            $items[$c]['options'][$i+1]["value"] = $budgetYear[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createDisplacementBudgetMonth";
        $items[$c]['title'] = "از ماه";
        $items[$c]['style'] = "style='width: 70%;'";
        $items[$c]['onchange'] = "onchange=refreshDisplacementBudgetComponents()";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createDisplacementBudgetToYear";
        $items[$c]['title'] = "به سال";
        $items[$c]['style'] = "style='width: 70%;'";
        $items[$c]['onchange'] = "onchange=getMonthOfThisYear()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntbu;$i++){
            $items[$c]['options'][$i+1]["title"] = $budgetYear[$i]['Name'];
            $items[$c]['options'][$i+1]["value"] = $budgetYear[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createDisplacementBudgetToMonth";
        $items[$c]['title'] = "به ماه";
        $items[$c]['style'] = "style='width: 70%;'";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createDisplacementBudgetComponents";
        $items[$c]['onchange'] = "onchange=getTotalNumberInMonth()";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['LimitNumSelections'] = 1;
        $items[$c]['title'] = "انتخاب محصول";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createDisplacementBudgetHCode";
        $items[$c]['onchange'] = "onchange=getDisplacementProductNameWithHcode()";
        $items[$c]['title'] = "کد کالا";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createDisplacementBudgetNumber";
        $items[$c]['title'] = "مقدار درخواست جابجایی";
        $items[$c]['placeholder'] = "مقدار";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "createDisplacementBudgetDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "displacementBudgetTotalInMonth";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['title'] = "مقدار کل در ماه انتخابی";
        $items[$c]['placeholder'] = "مقدار";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "displacementBudgetHiddenDbid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateDisplacementBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createDisplacementBudget = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF create displacement Budget MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ displacement Budget Comment Modal ++++++++++++++++++++++++++++++++
        $modalID = "displacementBudgetCommentModal";
        $modalTitle = "فرم تایید / عدم تایید و ثبت نظر";
        $style = 'style="max-width: 651px;"';
        $ShowDescription = 'displacementBudgetComment-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "displacementBudgetCommentType";
        $items[$c]['title'] = "انتخاب نمایید";
        $items[$c]['options'][0]['title'] = "تایید";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "عدم تایید";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "displacementBudgetCommentWarning";
        $items[$c]['title'] = "هشدار";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['placeholder'] = "در صورت تایید، نظر شما ثبت خواهد شد و امکان تغییر نخواهید داشت.";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "displacementBudgetCommentDesc";
        $items[$c]['title'] = "ثبت نظرات";
        $items[$c]['placeholder'] = "نظرات خود را بنویسید";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "displacementBudgetCommentHiddenDbid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doRecordDisplacementBudgetComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $displacementBudgetComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF displacement Budget Comment Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ send Displacement Budget Modal ++++++++++++++++++++++++++++++++
        $modalID = "sendDisplacementBudgetModal";
        $modalTitle = "ارجاع درخواست";
        $style = 'style="max-width: 800px;"';
        $ShowDescription = 'sendDisplacementBudgetModal-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "sendDisplacementBudgetDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "sendDisplacementBudgetHiddenDbid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doSendDisplacementBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $sendDisplacementBudget = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF send Displacement Budget Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ show Displacement Budget Workflow Modal ++++++++++++++++++++++++++++++++
        $modalID = "showDisplacementBudgetWorkflowModal";
        $modalTitle = "گردش کار";
        $style = 'style="max-width: 800px;"';
        $ShowDescription = 'showDisplacementBudgetWorkflow-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showDisplacementBudgetWorkflow = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF show Displacement Budget Workflow Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Final approval MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "displacementBudgetFinalTickModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "displacementBudgetFinalTickIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doFinalTickDisplacementBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $displacementBudgetFinalTick = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF Final approval MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ create delay Budget MODAL ++++++++++++++++++++++++++++++++
        $modalID = "createDelayBudgetModal";
        $modalTitle = "فرم مجوز تاخیر در تحویل بودجه فروش";
        $style = 'style="max-width: 651px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createDelayBudgetYear";
        $items[$c]['title'] = "بودجه فروش سال";
        $items[$c]['style'] = "style='width: 60%;'";
        $items[$c]['onchange'] = "onchange=getYearBudgetComponents5()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntbu;$i++){
            $items[$c]['options'][$i+1]["title"] = $budgetYear[$i]['Name'];
            $items[$c]['options'][$i+1]["value"] = $budgetYear[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createDelayBudgetMonth";
        $items[$c]['onchange'] = "onchange=refreshDelayBudgetComponents()";
        $items[$c]['title'] = "از ماه";
        $items[$c]['style'] = "style='width: 60%;'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        $mNum = $cmonth - 1;
        $keey = $cmonth;
        for ($i=0;$i<$mNum;$i++){
            $items[$c]['options'][$i+1]["title"] = $months[$keey-2];
            $items[$c]['options'][$i+1]["value"] = $keey-1;
            $keey--;
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createDelayBudgetToMonth";
        $items[$c]['title'] = "به ماه";
        $items[$c]['style'] = "style='width: 60%;'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        $mNum = 13 - $cmonth;
        $keey = $cmonth;
        for ($i=0;$i<$mNum;$i++){
            $items[$c]['options'][$i+1]["title"] = $months[$keey-1];
            $items[$c]['options'][$i+1]["value"] = $keey;
            $keey++;
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createDelayBudgetComponents";
        $items[$c]['onchange'] = "onchange=getTotalNumberInMonth1()";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['LimitNumSelections'] = 1;
        $items[$c]['title'] = "انتخاب محصول";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createDelayBudgetHCode";
        $items[$c]['onchange'] = "onchange=getDelayProductNameWithHcode()";
        $items[$c]['title'] = "کد محصول";
        $items[$c]['placeholder'] = "کد";
        $c++;

/*        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createOutProgramBudgetComponents";
        $items[$c]['onchange'] = "onchange=getOutProgramHCodeWithName()";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['LimitNumSelections'] = 1;
        $items[$c]['title'] = "انتخاب محصول";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createOutProgramBudgetHCode";
        $items[$c]['onchange'] = "onchange=getOutProgramProductNameWithHcode()";
        $items[$c]['title'] = "کد محصول";
        $items[$c]['placeholder'] = "کد";
        $c++;*/

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createDelayBudgetNumber";
        $items[$c]['title'] = "مقدار درخواستی";
        $items[$c]['placeholder'] = "مقدار";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "createDelayBudgetDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "delayBudgetTotalInMonth";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['title'] = "مقدار کل در ماه انتخابی";
        $items[$c]['placeholder'] = "مقدار";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "delayBudgetHiddenDbid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateDelayBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createDelayBudget = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF create delay Budget MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ delay Budget Comment Modal ++++++++++++++++++++++++++++++++
        $modalID = "delayBudgetCommentModal";
        $modalTitle = "فرم تایید / عدم تایید و ثبت نظر";
        $style = 'style="max-width: 500px;"';
        $ShowDescription = 'delayBudgetComment-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "delayBudgetCommentType";
        $items[$c]['title'] = "انتخاب نمایید";
        $items[$c]['options'][0]['title'] = "تایید";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "عدم تایید";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "delayBudgetCommentWarning";
        $items[$c]['title'] = "هشدار";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['placeholder'] = "در صورت تایید، نظر شما ثبت خواهد شد و امکان تغییر نخواهید داشت.";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "delayBudgetCommentDesc";
        $items[$c]['title'] = "ثبت نظرات";
        $items[$c]['placeholder'] = "نظرات خود را بنویسید";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "delayBudgetCommentHiddenDbid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doRecordDelayBudgetComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $delayBudgetComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF delay Budget Comment Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ send Delay Budget Modal ++++++++++++++++++++++++++++++++
        $modalID = "sendDelayBudgetModal";
        $modalTitle = "ارجاع درخواست";
        $style = 'style="max-width: 800px;"';
        $ShowDescription = 'sendDelayBudgetModal-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "sendDelayBudgetDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "sendDelayBudgetHiddenDbid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doSendDelayBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $sendDelayBudget = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF send Delay Budget Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ show Delay Budget Workflow Modal ++++++++++++++++++++++++++++++++
        $modalID = "showDelayBudgetWorkflowModal";
        $modalTitle = "گردش کار";
        $style = 'style="max-width: 800px;"';
        $ShowDescription = 'showDelayBudgetWorkflow-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showDelayBudgetWorkflow = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF show Delay Budget Workflow Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Final approval MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "delayBudgetFinalTickModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "delayBudgetFinalTickIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doFinalTickDelayBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $delayBudgetFinalTick = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF Final approval MODAL ++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ create budget Product Entry MODAL ++++++++++++++++++++++++++++++++
        $modalID = "createAmendmentBudgetModal";
        $modalTitle = "فرم اصلاحیه بودجه فروش";
        $ShowDescription = 'createAmendmentBudgetModal-body';
        $style = 'style="max-width: 1460px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createAmendmentBudgetADate";
        $items[$c]['onchange'] = "onchange=getDateBudgetComponents2()";
        $items[$c]['style'] = "style='width: 33%;float: right;'";
        $items[$c]['title'] = "تاریخ";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;
/*
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createAmendmentBudgetMonth";
        $items[$c]['title'] = "ماه";
        $items[$c]['style'] = "style='width: 33%;'";
        $items[$c]['options'] = array();
        $c++;
*/
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createAmendmentBudgetComponents";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['actionsBox'] = "true";
        $items[$c]['title'] = "انتخاب محصول";
        $items[$c]['options'] = array();

        $topperBottons = array();
        $topperBottons[0]['title'] = "ایجاد جدول";
        $topperBottons[0]['jsf'] = "createTableAmendmentBudget";
        $topperBottons[0]['type'] = "btn-success";
        $topperBottons[0]['data-dismiss'] = "No";
        /* $topperBottons[1]['title'] = "تایید";
        $topperBottons[1]['jsf'] = "doCreateAmendmentBudget";
        $topperBottons[1]['type'] = "btn";
        $topperBottons[1]['data-dismiss'] = "No"; */
        $topperBottons[1]['title'] = "انصراف";
        $topperBottons[1]['type'] = "dismis";
        $createAmendmentBudgetModal = $ut->getHtmlModal($modalID,$modalTitle,$items,array(),'','',$style,$ShowDescription,'','',$topperBottons);
        //++++++++++++++++++++++++++++++ END OF create budget Product Entry MODAL +++++++++++++++++++++++++++++++++++++
		//++++++++++++++++++++++++++++++++++ show budget amendment MODAL  ++++++++++++++++++++++++++++++++
        $modalID = "displayAmendmentBudgetModal";
        $modalTitle = " مشاهده جدول اصلاحیه بودجه ";
        $ShowDescription = 'displayTableAmendmentBudgetModal-body';
        $style = 'style="max-width: 1460px;"';
		
		$items=array();
       $z = 0;
        $items[$z]['type'] = "select";
        $items[$z]['id'] = "showAmendmentBudgetComponents";
        $items[$z]['multiple'] = "multiple";
        $items[$z]['actionsBox'] = "true";
        $items[$z]['title'] = "انتخاب محصول";
        $items[$z]['options'] = array();
		
        $topperBottons = array();
        $topperBottons[0]['title'] = "مشاهده جدول";
        $topperBottons[0]['jsf'] = "displayTableAmendmentBudget";
        $topperBottons[0]['type'] = "btn-success";
        $topperBottons[0]['data-dismiss'] = "No";
        $topperBottons[1]['title'] = "انصراف";
        $topperBottons[1]['type'] = "dismis";
        $displayAmendmentBudgetModal = $ut->getHtmlModal($modalID,$modalTitle,$items,array(),'','',$style,$ShowDescription,'','',$topperBottons);
        //++++++++++++++++++++++++++++++ END OF show budget amendment MODAL +++++++++++++++++++++++++++++++++++++
  
		
		
		
		
		
		
		
		
		
        //++++++++++++++++++++++++++++++++++ send Amendment Budget Modal ++++++++++++++++++++++++++++++++
        $modalID = "sendAmendmentBudgetModal";
        $modalTitle = "ارجاع اصلاحیه";
        $style = 'style="max-width: 800px;"';
        $ShowDescription = 'sendAmendmentBudgetModal-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "sendAmendmentBudgetDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "sendAmendmentBudgetHiddenAbid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doSendAmendmentBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $sendAmendmentBudget = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF send Amendment Budget Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ show Amendment Budget Workflow Modal ++++++++++++++++++++++++++++++++
        $modalID = "showAmendmentBudgetWorkflowModal";
        $modalTitle = "گردش کار";
        $style = 'style="max-width: 800px;"';
        $ShowDescription = 'showAmendmentBudgetWorkflow-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showAmendmentBudgetWorkflow = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF show Amendment Budget Workflow Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ amendment Budget Comment Modal ++++++++++++++++++++++++++++++++
        $modalID = "amendmentBudgetCommentModal";
        $modalTitle = "فرم تایید / عدم تایید و ثبت نظر";
        $style = 'style="max-width: 651px;"';
        $ShowDescription = 'amendmentBudgetComment-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "amendmentBudgetCommentType";
        $items[$c]['title'] = "انتخاب نمایید";
        $items[$c]['options'][0]['title'] = "تایید";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "عدم تایید";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "amendmentBudgetCommentWarning";
        $items[$c]['title'] = "هشدار";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['placeholder'] = "در صورت تایید، نظر شما ثبت خواهد شد و امکان تغییر نخواهید داشت.";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "amendmentBudgetCommentDesc";
        $items[$c]['title'] = "ثبت نظرات";
        $items[$c]['placeholder'] = "نظرات خود را بنویسید";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "amendmentBudgetCommentHiddenAbid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doRecordAmendmentBudgetComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $amendmentBudgetComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF amendment Budget Comment Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Edit MODAL ++++++++++++++++++++++++++++++++
        $modalID = "budgetAmendmentEditModal";
        $modalTitle = "فرم ویرایش اصلاحیه بودجه فروش";
        $style = 'style="max-width: 800px;"';
        $ShowDescription = 'budgetAmendmentEditModal-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "budgetAmendmentEditModalHiddenAbId";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditAmendmentBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editBudgetAmendment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF Edit MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Final approval MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "amendmentBudgetFinalTickModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "amendmentBudgetFinalTickIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doFinalTickAmendmentBudget";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $amendmentBudgetFinalTick = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF Final approval MODAL ++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Final Budget Manage Excel Modal ++++++++++++++++++++++++++++++++
        $modalID = "finalBudgetManageExcelModal";
        $modalTitle = "خروجی اکسل بودجه نهایی";
        $style = 'style="max-width: 570px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "finalBudgetManageExcelYear";
        $items[$c]['style'] = "style='width: 50%;'";
        $items[$c]['title'] = "بودجه فروش سال";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        $x = 1401;
        for ($i=0;$i<600;$i++){
            $items[$c]['options'][$i+1]["title"] = $x;
            $items[$c]['options'][$i+1]["value"] = $x;
            $x++;
        }

        $footerBottons = array();
        $footerBottons[0]['title'] = "دریافت";
        $footerBottons[0]['jsf'] = "doGetFinalBudgetManageExcel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $finalBudgetManageExcel = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF Final Budget Manage Excel Modal +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Final Budget Number Excel Modal ++++++++++++++++++++++++++++++++
        $modalID = "finalBudgetNumberExcelModal";
        $modalTitle = "خروجی اکسل مقادیر نهایی بودجه";
        $style = 'style="max-width: 570px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "finalBudgetNumberExcelYear";
        $items[$c]['style'] = "style='width: 50%;'";
        $items[$c]['title'] = "بودجه فروش سال";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        $x = 1401;
        for ($i=0;$i<600;$i++){
            $items[$c]['options'][$i+1]["title"] = $x;
            $items[$c]['options'][$i+1]["value"] = $x;
            $x++;
        }

        $footerBottons = array();
        $footerBottons[0]['title'] = "دریافت";
        $footerBottons[0]['jsf'] = "doGetFinalBudgetNumberExcel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $finalBudgetNumberExcel = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF Final Budget Number Excel Modal +++++++++++++++++++++++++++++++++++++
        $htm .= $showDetailsOfFinalBudget;
        $htm .= $createOutProgramBudget;
        $htm .= $outProgramBudgetComment;
        $htm .= $sensOutProgramBudget;
        $htm .= $showOutProgramBudgetInfo;
        $htm .= $showOutProgramBudgetWorkflow;
        $htm .= $outProgramBudgetFinalTick;
        $htm .= $createDisplacementBudget;
        $htm .= $displacementBudgetComment;
        $htm .= $sendDisplacementBudget;
        $htm .= $showDisplacementBudgetWorkflow;
        $htm .= $displacementBudgetFinalTick;
        $htm .= $createDelayBudget;
        $htm .= $delayBudgetComment;
        $htm .= $sendDelayBudget;
        $htm .= $showDelayBudgetWorkflow;
        $htm .= $delayBudgetFinalTick;
        $htm .= $createAmendmentBudgetModal;
        $htm .= $sendAmendmentBudget;
        $htm .= $showAmendmentBudgetWorkflow;
        $htm .= $amendmentBudgetComment;
        $htm .= $editBudgetAmendment;
        $htm .= $amendmentBudgetFinalTick;
        $htm .= $finalBudgetManageExcel;
        $htm .= $finalBudgetNumberExcel;
        $htm .= $createHistoryAmendmentBudget;
        $htm .= $displayAmendmentBudgetModal;
        $send = array($htm,$access);
        return $send;
    }

    //******************** بودجه فروش نهایی پس از اعمال تغییرات ********************

    public function getFinalBudgetManagementList($year,$component,$gCode,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('finalBudgetManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;

        $sql = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$year} AND `finalTick`=1";
        $res = $db->ArrayQuery($sql);
        $rids = array();
        if (count($res) > 0){
            $cnt = count($res);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $res[$i]['RowID'];
            }
            $rids = implode(',',$rids);
        }else{
            $rids = 0;
        }

        $w = array();
        if(intval($component) > 0) {
            $w[] = '`RowID`='.$component.' ';
        }
        $w[] = '`bcid` IN ('.$rids.') ';
        if(strlen(trim($gCode)) > 0) {
            $w[] = '`HCode`="'.$gCode.'" ';
        }

        $sql1 = "SELECT * FROM `budget_components_details`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql1 .= " WHERE ".$where;
        }
        $sql1 .= " ORDER BY `goodID` ASC LIMIT $start,".$numRows;
        $res1 = $db->ArrayQuery($sql1);

        $listCount = count($res1);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            $totalNum = 0;
            $totalNum1 = 0;

            $query = "SELECT `number` FROM `budget_product_entry` WHERE `bcdid`={$res1[$y]['RowID']}";
            $rst = $db->ArrayQuery($query);
            if (count($rst) > 0){
                $ccnt = count($rst);
                for ($j=0;$j<$ccnt;$j++){
                    $totalNum += $rst[$j]['number'];
                }
            }

            $query1 = "SELECT `number` FROM `budget_product_exit` WHERE `bcdid`={$res1[$y]['RowID']}";
            $rst1 = $db->ArrayQuery($query1);
            if (count($rst1) > 0){
                $ccnt1 = count($rst1);
                for ($j=0;$j<$ccnt1;$j++){
                    $totalNum1 += $rst1[$j]['number'];
                }
            }

            $finalRes[$y]['RowID'] = $res1[$y]['RowID'];
            $finalRes[$y]['gCode'] = $res1[$y]['gCode'];
            $finalRes[$y]['gName'] = $res1[$y]['gName'];
            $finalRes[$y]['farvardin'] = 1;
            $finalRes[$y]['ordibehesht'] = 2;
            $finalRes[$y]['khordad'] = 3;
            $finalRes[$y]['tir'] = 4;
            $finalRes[$y]['mordad'] = 5;
            $finalRes[$y]['shahrivar'] = 6;
            $finalRes[$y]['mehr'] = 7;
            $finalRes[$y]['aban'] = 8;
            $finalRes[$y]['azar'] = 9;
            $finalRes[$y]['dey'] = 10;
            $finalRes[$y]['bahman'] = 11;
            $finalRes[$y]['esfand'] = 12;

            $finalRes[$y]['farvardinTotal'] = $res1[$y]['farvardinTotal'];
            $finalRes[$y]['ordibeheshtTotal'] = $res1[$y]['ordibeheshtTotal'];
            $finalRes[$y]['khordadTotal'] = $res1[$y]['khordadTotal'];
            $finalRes[$y]['tirTotal'] = $res1[$y]['tirTotal'];
            $finalRes[$y]['mordadTotal'] = $res1[$y]['mordadTotal'];
            $finalRes[$y]['shahrivarTotal'] = $res1[$y]['shahrivarTotal'];
            $finalRes[$y]['mehrTotal'] = $res1[$y]['mehrTotal'];
            $finalRes[$y]['abanTotal'] = $res1[$y]['abanTotal'];
            $finalRes[$y]['azarTotal'] = $res1[$y]['azarTotal'];
            $finalRes[$y]['deyTotal'] = $res1[$y]['deyTotal'];
            $finalRes[$y]['bahmanTotal'] = $res1[$y]['bahmanTotal'];
            $finalRes[$y]['esfandTotal'] = $res1[$y]['esfandTotal'];

            $finalRes[$y]['totalEntryNumber'] = $res1[$y]['totalEntryNumber'];
            $finalRes[$y]['totalNum'] = $totalNum;
            $finalRes[$y]['totalNum1'] = $totalNum1;
        }
        return $finalRes;
    }

    public function getFinalBudgetManagementListCountRows($year,$component,$gCode){
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$year} AND `finalTick`=1";
        $res = $db->ArrayQuery($sql);
        $rids = array();
        if (count($res) > 0){
            $cnt = count($res);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $res[$i]['RowID'];
            }
            $rids = implode(',',$rids);
        }else{
            $rids = 0;
        }

        $w = array();
        if(intval($component) > 0){
            $w[] = '`RowID`='.$component.' ';
        }
        $w[] = '`bcid` IN ('.$rids.') ';
        if(strlen(trim($gCode)) > 0) {
            $w[] = '`HCode`="'.$gCode.'" ';
        }

        $sql1 = "SELECT `RowID` FROM `budget_components_details`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql1 .= " WHERE ".$where;
        }
        $res1 = $db->ArrayQuery($sql1);
        return count($res1);
    }

    public function detailsOfFinalBudgetHtm($bcdid,$month){
        $db = new DBi();

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoBudget-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;color: #fec107;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 60%;color: #fec107;">تشکیل شده از</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 30%;color: #fec107;">مقادیر</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $sql = "SELECT `bcid`,`goodID`,`gName`,`farvardin`,`ordibehesht`,`khordad`,`tir`,`mordad`,`shahrivar`,`mehr`,`aban`,`azar`,`dey`,`bahman`,`esfand`,`farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `RowID`={$bcdid}";
        $res = $db->ArrayQuery($sql);

        $sqq = "SELECT `budgetID` FROM `budget_components` WHERE `RowID`={$res[0]['bcid']}";
        $rstq = $db->ArrayQuery($sqq);

        switch ($month){
            case 1:
                $val = $res[0]['farvardin'];
                $val1 = $res[0]['farvardinTotal'];
                break;
            case 2:
                $val = $res[0]['ordibehesht'];
                $val1 = $res[0]['ordibeheshtTotal'];
                break;
            case 3:
                $val = $res[0]['khordad'];
                $val1 = $res[0]['khordadTotal'];
                break;
            case 4:
                $val = $res[0]['tir'];
                $val1 = $res[0]['tirTotal'];
                break;
            case 5:
                $val = $res[0]['mordad'];
                $val1 = $res[0]['mordadTotal'];
                break;
            case 6:
                $val = $res[0]['shahrivar'];
                $val1 = $res[0]['shahrivarTotal'];
                break;
            case 7:
                $val = $res[0]['mehr'];
                $val1 = $res[0]['mehrTotal'];
                break;
            case 8:
                $val = $res[0]['aban'];
                $val1 = $res[0]['abanTotal'];
                break;
            case 9:
                $val = $res[0]['azar'];
                $val1 = $res[0]['azarTotal'];
                break;
            case 10:
                $val = $res[0]['dey'];
                $val1 = $res[0]['deyTotal'];
                break;
            case 11:
                $val = $res[0]['bahman'];
                $val1 = $res[0]['bahmanTotal'];
                break;
            case 12:
                $val = $res[0]['esfand'];
                $val1 = $res[0]['esfandTotal'];
                break;
        }

        $sql1 = "SELECT `number` FROM `budget_out_program` WHERE `bcdid`={$bcdid} AND `month`={$month} AND `finalTick`=1";
        $res1 = $db->ArrayQuery($sql1);
        if (count($res1) <= 0){
            $res1[0]['number'] = '';
        }

        $sql2 = "SELECT `number` FROM `budget_displacement` WHERE `bcdid`={$bcdid} AND `fromMonth`={$month} AND `finalTick`=1";
        $res2 = $db->ArrayQuery($sql2);
        if (count($res2) > 0){
            $cnt2 = count($res2);
            $number2 = 0;
            for ($i=0;$i<$cnt2;$i++){
                $number2 += $res2[$i]['number'];
            }
        }else{
            $number2 = '';
        }

        $number3 = 0;
        $query = "SELECT `number`,`bcdid` FROM `budget_displacement` WHERE `fromYear`!={$rstq[0]['budgetID']} AND `toYear`={$rstq[0]['budgetID']} AND `toMonth`={$month} AND `finalTick`=1";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0){
            $ccnt = count($rst);
            for ($i=0;$i<$ccnt;$i++){
                $query1 = "SELECT `goodID` FROM `budget_components_details` WHERE `RowID`={$rst[$i]['bcdid']}";
                $rst1 = $db->ArrayQuery($query1);
                if (intval($rst1[0]['goodID']) == intval($res[0]['goodID'])){
                    $number3 += $rst[$i]['number'];
                }
            }
        }

        $sql3 = "SELECT `number` FROM `budget_displacement` WHERE `bcdid`={$bcdid} AND `toMonth`={$month} AND `finalTick`=1";
        $res3 = $db->ArrayQuery($sql3);

        if (count($res3) > 0){
            $cnt3 = count($res3);
            for ($i=0;$i<$cnt3;$i++){
                $number3 += $res3[$i]['number'];
            }
        }

        if (count($res3) <= 0 && count($rst) <= 0){
            $number3 = '';
        }

        $sql4 = "SELECT `number` FROM `budget_delay` WHERE `bcdid`={$bcdid} AND `fromMonth`={$month} AND `finalTick`=1";
        $res4 = $db->ArrayQuery($sql4);
        if (count($res4) > 0){
            $cnt4 = count($res4);
            $number4 = 0;
            for ($i=0;$i<$cnt4;$i++){
                $number4 +=  $res4[$i]['number'];
            }
        }else{
            $number4 = '';
        }

        $sql5 = "SELECT `number` FROM `budget_delay` WHERE `bcdid`={$bcdid} AND `toMonth`={$month} AND `finalTick`=1";
        $res5 = $db->ArrayQuery($sql5);
        if (count($res5) > 0){
            $cnt5 = count($res5);
            $number5 = 0;
            for ($i=0;$i<$cnt5;$i++){
                $number5 +=  $res5[$i]['number'];
            }
        }else{
            $number5 = '';
        }

        $sql6 = "SELECT `number` FROM `budget_product_entry` WHERE `bcdid`={$bcdid} AND `month`={$month}";
        $res6 = $db->ArrayQuery($sql6);
        if (count($res6) > 0){
            $cnt6 = count($res6);
            $number6 = 0;
            for ($i=0;$i<$cnt6;$i++){
                $number6 +=  $res6[$i]['number'];
            }
        }else{
            $number6 = '';
        }

        $sql7 = "SELECT `number` FROM `budget_product_exit` WHERE `bcdid`={$bcdid} AND `month`={$month}";
        $res7 = $db->ArrayQuery($sql7);
        if (count($res7) > 0){
            $cnt7 = count($res7);
            $number7 = 0;
            for ($i=0;$i<$cnt7;$i++){
                $number7 +=  $res7[$i]['number'];
            }
        }else{
            $number7 = '';
        }

        $sql8 = "SELECT `number`,`currentNumber`,`DifferenceNumber` FROM `budget_amendment` WHERE `bcdid`={$bcdid} AND `month`={$month} AND `finalTick`=1";
        $res8 = $db->ArrayQuery($sql8);
        if (count($res8) <= 0){
            $res8[0]['number'] = '';
            $res8[0]['currentNumber'] = '';
            $res8[0]['DifferenceNumber'] = '';
        }

        $values = array($val,$res1[0]['number'],$number2,$number3,$number4,$number5,$res8[0]['currentNumber'],$res8[0]['number'],$res8[0]['DifferenceNumber'],$number6,$val1);
        $infoNames = array('بودجه فروش اولیه','خارج از برنامه','جابجایی (کاهش)','جابجایی (افزایش)','تاخیر در تحویل (کاهش)','تاخیر در تحویل (افزایش)','مقدار در زمان اصلاحیه','مقدار اصلاح شده به','مابه التفاوت','مقدار تحویل به انبار','مقدار پس از اعمال کلیه تغییرات');
        for ($i=0;$i<11;$i++){
            $iterator++;
            switch ($i){
                case 2:
                case 4:
                    $color = 'red';
                    break;
                case 8:
                    $color = (intval($res8[0]['DifferenceNumber']) > 0 ? 'red' : 'blue');
                    if (intval($res8[0]['DifferenceNumber']) == 0){
                        $values[$i] = '';
                    }else{
                        $values[$i] = (intval($res8[0]['DifferenceNumber']) > 0 ? $res8[0]['DifferenceNumber'].' کاهش' : abs($res8[0]['DifferenceNumber']).' افزایش');
                    }
                    break;
                default:
                    $color = 'blue';
                    break;
            }
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;color: '.$color.'">'.$values[$i].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        $res[0]['gName'] = 'تغییرات بودجه فروش ( '.$res[0]['gName'].' ) ';
        $send = array($htm,$res[0]['gName']);
        return $send;
    }

    //******************** بودجه فروش خارج از برنامه ********************

    public function getOutProgramBudgetList($year,$component,$month,$hcode,$finalTick,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('outProgramBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();

        if(intval($year) > 0){
            $w[] = '`bid`='.$year.' ';
        }
        if(intval($component) > 0){
            $w[] = '`bcdid`='.$component.' ';
        }
        if(intval($month) > 0){
            $w[] = '`month`='.$month.' ';
        }
        if(intval($_SESSION['userid']) !== 1){
            $w[] = '`lastReceiver`='.$_SESSION['userid'].' ';
        }
        if(strlen(trim($hcode)) > 0 && intval($year) > 0) {
            $rids = array();
            $sqq = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$year}";
            $rsq = $db->ArrayQuery($sqq);
            $cnt = count($rsq);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rsq[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $sqq1 = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `HCode`='{$hcode}'";
            $rsq1 = $db->ArrayQuery($sqq1);
            $w[] = '`bcdid`='.$rsq1[0]['RowID'].' ';
        }
        if(strlen(trim($hcode)) > 0 && intval($year) <= 0) {
            $rids = array();
            $sqq1 = "SELECT `RowID` FROM `budget_components_details` WHERE `HCode`='{$hcode}'";
            $rsq1 = $db->ArrayQuery($sqq1);
            $cnt = count($rsq1);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rsq1[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $w[] = '`bcdid` IN ('.$rids.') ';
        }
        $w[] = '`finalTick`='.$finalTick.' ';

        $sql = "SELECT `budget_out_program`.*,`year` FROM `budget_out_program` INNER JOIN `budget` ON (`budget_out_program`.`bid`=`budget`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            $query = "SELECT * FROM `budget_components_details` WHERE `RowID`={$res[$y]['bcdid']}";
            $rst = $db->ArrayQuery($query);
            switch ($res[$y]['month']){
                case 1:
                    $pNumber = $rst[0]['farvardin'];
                    $tNumber = $rst[0]['farvardinTotal'];
                    $monthtxt = 'فروردین';
                    break;
                case 2:
                    $pNumber = $rst[0]['ordibehesht'];
                    $tNumber = $rst[0]['ordibeheshtTotal'];
                    $monthtxt = 'اردیبهشت';
                    break;
                case 3:
                    $pNumber = $rst[0]['khordad'];
                    $tNumber = $rst[0]['khordadTotal'];
                    $monthtxt = 'خرداد';
                    break;
                case 4:
                    $pNumber = $rst[0]['tir'];
                    $tNumber = $rst[0]['tirTotal'];
                    $monthtxt = 'تیر';
                    break;
                case 5:
                    $pNumber = $rst[0]['mordad'];
                    $tNumber = $rst[0]['mordadTotal'];
                    $monthtxt = 'مرداد';
                    break;
                case 6:
                    $pNumber = $rst[0]['shahrivar'];
                    $tNumber = $rst[0]['shahrivarTotal'];
                    $monthtxt = 'شهریور';
                    break;
                case 7:
                    $pNumber = $rst[0]['mehr'];
                    $tNumber = $rst[0]['mehrTotal'];
                    $monthtxt = 'مهر';
                    break;
                case 8:
                    $pNumber = $rst[0]['aban'];
                    $tNumber = $rst[0]['abanTotal'];
                    $monthtxt = 'آبان';
                    break;
                case 9:
                    $pNumber = $rst[0]['azar'];
                    $tNumber = $rst[0]['azarTotal'];
                    $monthtxt = 'آذر';
                    break;
                case 10:
                    $pNumber = $rst[0]['dey'];
                    $tNumber = $rst[0]['deyTotal'];
                    $monthtxt = 'دی';
                    break;
                case 11:
                    $pNumber = $rst[0]['bahman'];
                    $tNumber = $rst[0]['bahmanTotal'];
                    $monthtxt = 'بهمن';
                    break;
                case 12:
                    $pNumber = $rst[0]['esfand'];
                    $tNumber = $rst[0]['esfandTotal'];
                    $monthtxt = 'اسفند';
                    break;
            }

            $finalRes[$y]['bgColor'] = ($res[$y]['finalTick'] == 0 ? 'table-danger' : 'table-success');
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['year'] = $res[$y]['year'];
            $finalRes[$y]['HCode'] = $rst[0]['HCode'];
            $finalRes[$y]['gName'] = $rst[0]['gName'];
            $finalRes[$y]['monthtxt'] = $monthtxt;
            $finalRes[$y]['pNumber'] = $pNumber;
            $finalRes[$y]['number'] = $res[$y]['number'];
            $finalRes[$y]['tNumber'] = $tNumber;
            $finalRes[$y]['description'] = $res[$y]['description'];
        }
        return $finalRes;
    }

    public function getOutProgramBudgetListCountRows($year,$component,$month,$finalTick,$hcode){
        $db = new DBi();
        $w = array();
        if(intval($year) > 0){
            $w[] = '`bid`='.$year.' ';
        }
        if(intval($component) > 0){
            $w[] = '`bcdid`='.$component.' ';
        }
        if(intval($month) > 0){
            $w[] = '`month`='.$month.' ';
        }
        if (intval($_SESSION['userid']) !== 1){
            $w[] = '`lastReceiver`='.$_SESSION['userid'].' ';
        }
        if(strlen(trim($hcode)) > 0 && intval($year) > 0) {
            $rids = array();
            $sqq = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$year}";
            $rsq = $db->ArrayQuery($sqq);
            $cnt = count($rsq);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rsq[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $sqq1 = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `HCode`='{$hcode}'";
            $rsq1 = $db->ArrayQuery($sqq1);
            $w[] = '`bcdid`='.$rsq1[0]['RowID'].' ';
        }
        if(strlen(trim($hcode)) > 0 && intval($year) <= 0) {
            $rids = array();
            $sqq1 = "SELECT `RowID` FROM `budget_components_details` WHERE `HCode`='{$hcode}'";
            $rsq1 = $db->ArrayQuery($sqq1);
            $cnt = count($rsq1);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rsq1[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $w[] = '`bcdid` IN ('.$rids.') ';
        }
        $w[] = '`finalTick`='.$finalTick.' ';

        $sql = "SELECT `RowID` FROM `budget_out_program`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function yearBudgetComponents($bid){
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$bid} AND `finalTick`=1";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $rids = array();

        for ($i=0;$i<$cnt;$i++){
            $rids[] = $res[$i]['RowID'];
        }
        $rids = (count($rids) > 0 ? implode(',',$rids) : 0);

        $sql1 = "SELECT `RowID`,`gName` FROM `budget_components_details` WHERE `bcid` IN ({$rids})";
        $res1 = $db->ArrayQuery($sql1);

        return $res1;
    }

    public function outProgramBudgetInfo($opbID){
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT * FROM `budget_out_program` WHERE `RowID`=".$opbID;
        $res = $db->ArrayQuery($sql);

        $query = "SELECT `HCode` FROM `budget_components_details` WHERE `RowID`={$res[0]['bcdid']}";
        $rst = $db->ArrayQuery($query);

        if(count($res) == 1){
            $nDate = (strtotime($res[0]['needDate']) > 0 ? $ut->greg_to_jal($res[0]['needDate']) : '');
            $sDate = (strtotime($res[0]['supplyDate']) > 0 ? $ut->greg_to_jal($res[0]['supplyDate']) : '');
            $res = array("opbID"=>$opbID,"bid"=>$res[0]['bid'],"month"=>$res[0]['month'],"bcdid"=>$res[0]['bcdid'],"number"=>$res[0]['number'],"description"=>$res[0]['description'],"customerName"=>$res[0]['customerName'],"nDate"=>$nDate,"sDate"=>$sDate,"HCode"=>$rst[0]['HCode']);
            return $res;
        }else{
            return false;
        }
    }

    public function createOutProgramBudget($year,$month,$components,$num,$cName,$nDate,$sDate,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('outProgramBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $nDate = (strlen(trim($nDate)) > 0 ? $ut->jal_to_greg($nDate) : '');
        $sDate = (strlen(trim($sDate)) > 0 ? $ut->jal_to_greg($sDate) : '');

        $query = "SELECT `month` FROM `budget_out_program` WHERE `bid`={$year} AND `bcdid`={$components}";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0){
            $cnt = count($rst);
            for ($i=0;$i<$cnt;$i++){
                if (intval($month) == $rst[$i]['month']){
                    $res = "برای این محصول، در ماه و سال انتخاب شده قبلا درخواست مازاد داده شده است !";
                    $out = "false";
                    response($res,$out);
                    exit;
                }
            }
        }

        $sql = "INSERT INTO `budget_out_program` (`bid`,`bcdid`,`month`,`number`,`description`,`lastReceiver`,`customerName`,`needDate`,`supplyDate`) VALUES ({$year},{$components},{$month},{$num},'{$desc}',{$_SESSION['userid']},'{$cName}','{$nDate}','{$sDate}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editOutProgramBudget($opbid,$year,$month,$components,$num,$cName,$nDate,$sDate,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('outProgramBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $nDate = (strlen(trim($nDate)) > 0 ? $ut->jal_to_greg($nDate) : '');
        $sDate = (strlen(trim($sDate)) > 0 ? $ut->jal_to_greg($sDate) : '');

        $sqq = "SELECT `bid`,`month`,`bcdid`,`finalTick`,`number`,`description`,`customerName`,`needDate`,`supplyDate` FROM `budget_out_program` WHERE `RowID`={$opbid}";
        $result = $db->ArrayQuery($sqq);

        if (intval($result[0]['finalTick']) == 1){
            $res = "این مورد تایید نهایی شده است و امکان ویرایش ندارد !";
            $out = "false";
            response($res, $out);
            exit;
        }
        if ( (intval($year) !== intval($result[0]['bid'])) || (intval($month) !== intval($result[0]['month'])) || (intval($components) !== intval($result[0]['bcdid'])) ) {
            $query = "SELECT `month` FROM `budget_out_program` WHERE `bid`={$year} AND `bcdid`={$components}";
            $rst = $db->ArrayQuery($query);
            if (count($rst) > 0) {
                $cnt = count($rst);
                for ($i = 0; $i < $cnt; $i++) {
                    if (intval($month) == intval($rst[$i]['month'])) {
                        $res = "برای این محصول، در ماه و سال انتخاب شده قبلا درخواست مازاد داده شده است !";
                        $out = "false";
                        response($res, $out);
                        exit;
                    }
                }
            }
        }elseif ((intval($num) == intval($result[0]['number'])) && (strlen(trim($desc)) == strlen(trim($result[0]['description']))) && ($cName == $result[0]['customerName']) && strtotime($nDate) == strtotime($result[0]['needDate']) && strtotime($sDate) == strtotime($result[0]['supplyDate'])){
            $res = "شما هیچ تغییری اعمال ننموده اید !";
            $out = "false";
            response($res, $out);
            exit;
        }

        $sql = "UPDATE `budget_out_program` SET `bid`={$year},`bcdid`={$components},`month`={$month},`number`={$num},`description`='{$desc}',`productionTick`=2,`planningTick`=2,`productionDescription`='',`planningDescription`='',`customerName`='{$cName}',`needDate`='{$nDate}',`supplyDate`='{$sDate}' WHERE `RowID`={$opbid}";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function outProgramBudgetComment($opbid){
        $db = new DBi();
        $sql = "SELECT `productionTick`,`planningTick`,`productionDescription`,`planningDescription` FROM `budget_out_program` WHERE `RowID`=".$opbid;
        $res = $db->ArrayQuery($sql);
        switch ($res[0]['productionTick']){
            case 0:
                $productionTick = 'عدم تایید';
                $bg = 'bg-danger-light';
                break;
            case 1:
                $productionTick = 'تایید شده';
                $res[0]['productionDescription'] = 'مورد تایید می باشد';
                $bg = 'bg-success-light';
                break;
            case 2:
                $productionTick = 'در حال بررسی';
                $res[0]['productionDescription'] = 'در حال بررسی می باشد';
                $bg = 'bg-warning-light';
                break;
        }
        switch ($res[0]['planningTick']){
            case 0:
                $planningTick = 'عدم تایید';
                $bg1 = 'bg-danger-light';
                break;
            case 1:
                $planningTick = 'تایید شده';
                $res[0]['planningDescription'] = 'مورد تایید می باشد';
                $bg1 = 'bg-success-light';
                break;
            case 2:
                $planningTick = 'در حال بررسی';
                $res[0]['planningDescription'] = 'در حال بررسی می باشد';
                $bg1 = 'bg-warning-light';
                break;
        }
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="outProgramBudgetCommentHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">نظر واحد برنامه ریزی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">وضعیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">نظر واحد تولید</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">وضعیت</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $htm .= '<tr class="table-secondary">';
        $htm .= '<td class="'.$bg1.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]['planningDescription'].'</td>';
        $htm .= '<td class="'.$bg1.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$planningTick.'</td>';
        $htm .= '<td class="'.$bg.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]['productionDescription'].'</td>';
        $htm .= '<td class="'.$bg.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$productionTick.'</td>';
        $htm .= '</tr>';
        $htm .= '</tbody>';
        $htm .= '</table>';

        return $htm;
    }

    public function recordOutProgramBudgetComment($opbid,$desc,$radioValue){
        $acm = new acm();
        $db = new DBi();

        $sqq = "SELECT `productionTick`,`planningTick` FROM `budget_out_program` WHERE `RowID`={$opbid}";
        $rsq = $db->ArrayQuery($sqq);
        if ($acm->hasAccess('productionTickBudget') && intval($rsq[0]['productionTick']) == 1){
            $res = "شما قبلا این مورد را تایید نموده اید !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif ($acm->hasAccess('productionTickBudget')){
            $m = '`productionTick`='.$radioValue;
            $n = ',`productionDescription`="'.$desc.'"';
        }

        if ($acm->hasAccess('planningTickBudget') && intval($rsq[0]['planningTick']) == 1){
            $res = "شما قبلا این مورد را تایید نموده اید !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif ($acm->hasAccess('planningTickBudget')){
            $m = '`planningTick`='.$radioValue;
            $n = ',`planningDescription`="'.$desc.'"';
        }

        $sql = "UPDATE `budget_out_program` SET ".$m.$n." WHERE `RowID`={$opbid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function outProgramBudgetInfoHtm($opbid){
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT `customerName`,`needDate`,`supplyDate` FROM `budget_out_program` WHERE `RowID`={$opbid}";
        $res = $db->ArrayQuery($sql);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="outProgramBudgetInfoHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 80%;">نام مشتری</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ نیاز</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ تامین</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $htm .= '<tr class="table-secondary">';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]['customerName'].'</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(strtotime($res[0]['needDate']) > 0 ? $ut->greg_to_jal($res[0]['needDate']) : '').'</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(strtotime($res[0]['supplyDate']) > 0 ? $ut->greg_to_jal($res[0]['supplyDate']) : '').'</td>';
        $htm .= '</tr>';
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function outProgramBudgetWorkflowHtm($opbid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `budget_out_program_workflow`.*,`fname`,`lname` FROM `budget_out_program_workflow` INNER JOIN `users` ON (`budget_out_program_workflow`.`sender`=`users`.`RowID`) WHERE `opbid`={$opbid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="outProgramBudgetWorkflowHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">فرستنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">گیرنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زمان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$i]['receiver']}";
            $rst = $db->ArrayQuery($query);
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$rst[0]['fname'].' '.$rst[0]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createDate'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function sendOutProgramBudget($opbid,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('outProgramBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $sqlu = "SELECT `finalTick` FROM `budget_out_program` WHERE `RowID`={$opbid}";
        $resu = $db->ArrayQuery($sqlu);
        if (intval($resu[0]['finalTick']) == 1){
            $res = "این مورد تایید نهایی شده است و امکان ارجاع ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        $cartable = 'بودجه خارج از برنامه';

        $sql1 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=102";  // واحد برنامه ریزی
        $res1 = $db->ArrayQuery($sql1);

        $sqlp = "SELECT `phone` FROM `users` WHERE `RowID`={$res1[0]['user_id']}";
        $resp = $db->ArrayQuery($sqlp);

        $sql2 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=101";  // واحد تولید
        $res2 = $db->ArrayQuery($sql2);

        $sqlp1 = "SELECT `phone` FROM `users` WHERE `RowID`={$res2[0]['user_id']}";
        $resp1 = $db->ArrayQuery($sqlp1);

        if ($acm->hasAccess('editCreateOutProgramBudget')){   // معاونت بازرگانی بود
            $sql3 = "INSERT INTO `budget_out_program_workflow` (`sender`,`receiver`,`opbid`,`createDate`,`createTime`,`description`) VALUES ({$_SESSION['userid']},{$res1[0]['user_id']},{$opbid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

            $sql4 = "UPDATE `budget_out_program` SET `lastReceiver`={$res1[0]['user_id']} WHERE `RowID`={$opbid}";
            $db->Query($sql4);

            $phone = $resp[0]['phone'];
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }elseif ($acm->hasAccess('planningTickBudget')){
            $sql3 = "INSERT INTO `budget_out_program_workflow` (`sender`,`receiver`,`opbid`,`createDate`,`createTime`,`description`) VALUES ({$res1[0]['user_id']},20,{$opbid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

/*            $sql4 = "UPDATE `budget_out_program` SET `lastReceiver`={$res2[0]['user_id']} WHERE `RowID`={$opbid}";
            $db->Query($sql4);*/
            $sql4 = "UPDATE `budget_out_program` SET `lastReceiver`=20,`productionTick`=1 WHERE `RowID`={$opbid}";
            $db->Query($sql4);

            $phone = $resp1[0]['phone'];
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }elseif ($acm->hasAccess('productionTickBudget')){
            $sql3 = "INSERT INTO `budget_out_program_workflow` (`sender`,`receiver`,`opbid`,`createDate`,`createTime`,`description`) VALUES ({$res2[0]['user_id']},20,{$opbid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

            $sql4 = "UPDATE `budget_out_program` SET `lastReceiver`=20 WHERE `RowID`={$opbid}";
            $db->Query($sql4);

            $phone = '9153131176';
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }else{
            return false;
        }
    }

    public function finalTickOutProgramBudget($opbid){
        $db = new DBi();
        $query = "SELECT `RowID` FROM `budget_out_program` WHERE `RowID`={$opbid} AND (`productionTick`!=1 OR `planningTick`!=1)";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0){
            $res = "این مورد تاییدیه برنامه ریزی یا تولید را ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }else{
            $sql = "SELECT `month`,`number`,`bcdid`,`finalTick` FROM `budget_out_program` WHERE `RowID`={$opbid}";
            $res = $db->ArrayQuery($sql);
            if (intval($res[0]['finalTick']) == 1){
                $res = "شما قبلا این مورد را تایید نهایی نموده اید !";
                $out = "false";
                response($res,$out);
                exit;
            }

            $sql1 = "SELECT `farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `RowID`={$res[0]['bcdid']}";
            $res1 = $db->ArrayQuery($sql1);

            switch ($res[0]['month']){
                case 1:
                    $number = intval($res1[0]['farvardinTotal']);
                    $m = ' `farvardinTotal`';
                    break;
                case 2:
                    $number = intval($res1[0]['ordibeheshtTotal']);
                    $m = ' `ordibeheshtTotal`';
                    break;
                case 3:
                    $number = intval($res1[0]['khordadTotal']);
                    $m = ' `khordadTotal`';
                    break;
                case 4:
                    $number = intval($res1[0]['tirTotal']);
                    $m = ' `tirTotal`';
                    break;
                case 5:
                    $number = intval($res1[0]['mordadTotal']);
                    $m = ' `mordadTotal`';
                    break;
                case 6:
                    $number = intval($res1[0]['shahrivarTotal']);
                    $m = ' `shahrivarTotal`';
                    break;
                case 7:
                    $number = intval($res1[0]['mehrTotal']);
                    $m = ' `mehrTotal`';
                    break;
                case 8:
                    $number = intval($res1[0]['abanTotal']);
                    $m = ' `abanTotal`';
                    break;
                case 9:
                    $number = intval($res1[0]['azarTotal']);
                    $m = ' `azarTotal`';
                    break;
                case 10:
                    $number = intval($res1[0]['deyTotal']);
                    $m = ' `deyTotal`';
                    break;
                case 11:
                    $number = intval($res1[0]['bahmanTotal']);
                    $m = ' `bahmanTotal`';
                    break;
                case 12:
                    $number = intval($res1[0]['esfandTotal']);
                    $m = ' `esfandTotal`';
                    break;
            }

            $totalNum = intval($number) + intval($res[0]['number']);

            $sqlu = "UPDATE `budget_components_details` SET".$m."=".$totalNum." WHERE `RowID`={$res[0]['bcdid']} ";
            $db->Query($sqlu);

            $sql2 = "UPDATE `budget_out_program` SET `finalTick`=1 WHERE `RowID`={$opbid}";
            $db->Query($sql2);
            $aff = $db->AffectedRows();
            $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
            if (intval($aff) > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function getOutProgramHCodeWithName($components){
        $db = new DBi();
        $sql = "SELECT `HCode` FROM `budget_components_details` WHERE `RowID`={$components}";
        $res = $db->ArrayQuery($sql);
        $HCode = (strlen(trim($components)) == 0 ? '' : $res[0]['HCode']);
        return $HCode;
    }

    public function getOutProgramProductNameWithHcode($bid,$hcode){
        $db = new DBi();

        $query1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$bid} AND `finalTick`=1";
        $rst1 = $db->ArrayQuery($query1);
        $rids = array();
        if (count($rst1) > 0){
            $cnt = count($rst1);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rst1[$i]['RowID'];
                $rids = implode(',',$rids);
            }
        }else{
            $rids = 0;
        }

        $sql = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `HCode`='{$hcode}'";
        $res = $db->ArrayQuery($sql);
        $idd = (strlen(trim($hcode)) == 0 ? 0 : $res[0]['RowID']);
        return $idd;
    }

    //******************** مجوز جابجایی بودجه فروش ********************

    public function getDisplacementBudgetList($year,$component,$month,$hcode,$finalTick,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('displacementBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(intval($year) > 0){
            $w[] = '`fromYear`='.$year.' ';
        }
        if(intval($component) > 0){
            $w[] = '`bcdid`='.$component.' ';
        }
        if(intval($month) > 0){
            $w[] = '`fromMonth`='.$month.' ';
        }
        if(intval($_SESSION['userid']) !== 1){
            $w[] = '`lastReceiver`='.$_SESSION['userid'].' ';
        }
        if(strlen(trim($hcode)) > 0 && intval($year) > 0) {
            $rids = array();
            $sqq = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$year}";
            $rsq = $db->ArrayQuery($sqq);
            $cnt = count($rsq);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rsq[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $sqq1 = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `HCode`='{$hcode}'";
            $rsq1 = $db->ArrayQuery($sqq1);
            $w[] = '`bcdid`='.$rsq1[0]['RowID'].' ';
        }
        if(strlen(trim($hcode)) > 0 && intval($year) <= 0) {
            $rids = array();
            $sqq1 = "SELECT `RowID` FROM `budget_components_details` WHERE `HCode`='{$hcode}'";
            $rsq1 = $db->ArrayQuery($sqq1);
            $cnt = count($rsq1);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rsq1[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $w[] = '`bcdid` IN ('.$rids.') ';
        }
        $w[] = '`finalTick`='.$finalTick.' ';

        $sql = "SELECT `budget_displacement`.*,`year` FROM `budget_displacement` INNER JOIN `budget` ON (`budget_displacement`.`fromYear`=`budget`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            $query = "SELECT * FROM `budget_components_details` WHERE `RowID`={$res[$y]['bcdid']}";
            $rst = $db->ArrayQuery($query);
            switch ($res[$y]['fromMonth']){
                case 1:
                    $tNumber = $rst[0]['farvardinTotal'];
                    $monthtxt = 'فروردین';
                    break;
                case 2:
                    $tNumber = $rst[0]['ordibeheshtTotal'];
                    $monthtxt = 'اردیبهشت';
                    break;
                case 3:
                    $tNumber = $rst[0]['khordadTotal'];
                    $monthtxt = 'خرداد';
                    break;
                case 4:
                    $tNumber = $rst[0]['tirTotal'];
                    $monthtxt = 'تیر';
                    break;
                case 5:
                    $tNumber = $rst[0]['mordadTotal'];
                    $monthtxt = 'مرداد';
                    break;
                case 6:
                    $tNumber = $rst[0]['shahrivarTotal'];
                    $monthtxt = 'شهریور';
                    break;
                case 7:
                    $tNumber = $rst[0]['mehrTotal'];
                    $monthtxt = 'مهر';
                    break;
                case 8:
                    $tNumber = $rst[0]['abanTotal'];
                    $monthtxt = 'آبان';
                    break;
                case 9:
                    $tNumber = $rst[0]['azarTotal'];
                    $monthtxt = 'آذر';
                    break;
                case 10:
                    $tNumber = $rst[0]['deyTotal'];
                    $monthtxt = 'دی';
                    break;
                case 11:
                    $tNumber = $rst[0]['bahmanTotal'];
                    $monthtxt = 'بهمن';
                    break;
                case 12:
                    $tNumber = $rst[0]['esfandTotal'];
                    $monthtxt = 'اسفند';
                    break;
            }
            if (intval($res[$y]['fromYear']) == intval($res[$y]['toYear'])) {
                switch ($res[$y]['toMonth']) {
                    case 1:
                        $tNumber1 = $rst[0]['farvardinTotal'];
                        $monthtxt1 = 'فروردین';
                        break;
                    case 2:
                        $tNumber1 = $rst[0]['ordibeheshtTotal'];
                        $monthtxt1 = 'اردیبهشت';
                        break;
                    case 3:
                        $tNumber1 = $rst[0]['khordadTotal'];
                        $monthtxt1 = 'خرداد';
                        break;
                    case 4:
                        $tNumber1 = $rst[0]['tirTotal'];
                        $monthtxt1 = 'تیر';
                        break;
                    case 5:
                        $tNumber1 = $rst[0]['mordadTotal'];
                        $monthtxt1 = 'مرداد';
                        break;
                    case 6:
                        $tNumber1 = $rst[0]['shahrivarTotal'];
                        $monthtxt1 = 'شهریور';
                        break;
                    case 7:
                        $tNumber1 = $rst[0]['mehrTotal'];
                        $monthtxt1 = 'مهر';
                        break;
                    case 8:
                        $tNumber1 = $rst[0]['abanTotal'];
                        $monthtxt1 = 'آبان';
                        break;
                    case 9:
                        $tNumber1 = $rst[0]['azarTotal'];
                        $monthtxt1 = 'آذر';
                        break;
                    case 10:
                        $tNumber1 = $rst[0]['deyTotal'];
                        $monthtxt1 = 'دی';
                        break;
                    case 11:
                        $tNumber1 = $rst[0]['bahmanTotal'];
                        $monthtxt1 = 'بهمن';
                        break;
                    case 12:
                        $tNumber1 = $rst[0]['esfandTotal'];
                        $monthtxt1 = 'اسفند';
                        break;
                }
                $finalRes[$y]['toYear'] = $res[$y]['year'];
            }else{
                $query1 = "SELECT `year` FROM `budget` WHERE `RowID`={$res[$y]['toYear']}";
                $rst1 = $db->ArrayQuery($query1);
                $finalRes[$y]['toYear'] = $rst1[0]['year'];

                $query2 = "SELECT `budget_components_details`.* FROM `budget_components_details` INNER JOIN `budget_components` ON (`budget_components_details`.`bcid`=`budget_components`.`RowID`) WHERE `budgetID`={$res[$y]['toYear']} AND `goodID`={$rst[0]['goodID']}";
                $rst2 = $db->ArrayQuery($query2);

                switch ($res[$y]['toMonth']) {
                    case 1:
                        $tNumber1 = $rst2[0]['farvardinTotal'];
                        $monthtxt = 'فروردین';
                        break;
                    case 2:
                        $tNumber1 = $rst2[0]['ordibeheshtTotal'];
                        $monthtxt1 = 'اردیبهشت';
                        break;
                    case 3:
                        $tNumber1 = $rst2[0]['khordadTotal'];
                        $monthtxt1 = 'خرداد';
                        break;
                    case 4:
                        $tNumber1 = $rst2[0]['tirTotal'];
                        $monthtxt1 = 'تیر';
                        break;
                    case 5:
                        $tNumber1 = $rst[0]['mordadTotal'];
                        $monthtxt1 = 'مرداد';
                        break;
                    case 6:
                        $tNumber1 = $rst2[0]['shahrivarTotal'];
                        $monthtxt1 = 'شهریور';
                        break;
                    case 7:
                        $tNumber1 = $rst2[0]['mehrTotal'];
                        $monthtxt1 = 'مهر';
                        break;
                    case 8:
                        $tNumber1 = $rst2[0]['abanTotal'];
                        $monthtxt1 = 'آبان';
                        break;
                    case 9:
                        $tNumber1 = $rst2[0]['azarTotal'];
                        $monthtxt1 = 'آذر';
                        break;
                    case 10:
                        $tNumber1 = $rst2[0]['deyTotal'];
                        $monthtxt1 = 'دی';
                        break;
                    case 11:
                        $tNumber1 = $rst2[0]['bahmanTotal'];
                        $monthtxt1 = 'بهمن';
                        break;
                    case 12:
                        $tNumber1 = $rst2[0]['esfandTotal'];
                        $monthtxt1 = 'اسفند';
                        break;
                }
            }

            $finalRes[$y]['bgColor'] = ($res[$y]['finalTick'] == 0 ? 'table-danger' : 'table-success');
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['year'] = $res[$y]['year'];
            $finalRes[$y]['HCode'] = $rst[0]['HCode'];
            $finalRes[$y]['gName'] = $rst[0]['gName'];
            $finalRes[$y]['monthtxt'] = $monthtxt;
            $finalRes[$y]['number'] = $res[$y]['number'];
            $finalRes[$y]['tNumber'] = $tNumber;
            $finalRes[$y]['monthtxt1'] = $monthtxt1;
            $finalRes[$y]['tNumber1'] = $tNumber1;
            $finalRes[$y]['description'] = $res[$y]['description'];
        }
        return $finalRes;
    }

    public function getDisplacementBudgetListCountRows($year,$component,$month,$finalTick,$hcode){
        $db = new DBi();
        $w = array();
        if(intval($year) > 0){
            $w[] = '`fromYear`='.$year.' ';
        }
        if(intval($component) > 0){
            $w[] = '`bcdid`='.$component.' ';
        }
        if(intval($month) > 0){
            $w[] = '`fromMonth`='.$month.' ';
        }
        if(intval($_SESSION['userid']) !== 1){
            $w[] = '`lastReceiver`='.$_SESSION['userid'].' ';
        }
        if(strlen(trim($hcode)) > 0 && intval($year) > 0) {
            $rids = array();
            $sqq = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$year}";
            $rsq = $db->ArrayQuery($sqq);
            $cnt = count($rsq);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rsq[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $sqq1 = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `HCode`='{$hcode}'";
            $rsq1 = $db->ArrayQuery($sqq1);
            $w[] = '`bcdid`='.$rsq1[0]['RowID'].' ';
        }
        if(strlen(trim($hcode)) > 0 && intval($year) <= 0) {
            $rids = array();
            $sqq1 = "SELECT `RowID` FROM `budget_components_details` WHERE `HCode`='{$hcode}'";
            $rsq1 = $db->ArrayQuery($sqq1);
            $cnt = count($rsq1);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rsq1[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $w[] = '`bcdid` IN ('.$rids.') ';
        }
        $w[] = '`finalTick`='.$finalTick.' ';

        $sql = "SELECT `RowID` FROM `budget_displacement`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function yearBudgetDisplacementComponents($bid){
        $db = new DBi();
        $ut = new Utility();

        $months = array('فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند');
        $nowDate = $ut->greg_to_jal(date('Y-m-d'));
        $cmonth = (intval(strlen($nowDate)) == 8 ? intval(substr($nowDate,5,1)) : intval(substr($nowDate,5,2)) );
        $cyear = intval(substr($nowDate,0,4));

        $sqq = "SELECT `year` FROM `budget` WHERE `RowID`={$bid}";
        $rsq = $db->ArrayQuery($sqq);

        $result = array();
        if (intval($cyear) == intval($rsq[0]['year'])){
            $mNum = 13 - $cmonth;
            for ($i=0;$i<$mNum;$i++){
                $result[$i]['title'] = $months[$cmonth-1];
                $result[$i]['value'] = $cmonth;
                $cmonth++;
            }
        }else{
            for ($i=0;$i<12;$i++){
                $result[$i]['title'] = $months[$i];
                $result[$i]['value'] = $i+1;
                $cmonth++;
            }
        }

        $sql = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$bid} AND `finalTick`=1";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $rids = array();

        for ($i=0;$i<$cnt;$i++){
            $rids[] = $res[$i]['RowID'];
        }
        $rids = (count($rids) > 0 ? implode(',',$rids) : 0);

        $sql1 = "SELECT `RowID`,`gName` FROM `budget_components_details` WHERE `bcid` IN ({$rids})";
        $res1 = $db->ArrayQuery($sql1);

        $send = array($res1,$result);
        return $send;
    }

    public function getMonthOfThisYear($bid){
        $db = new DBi();
        $ut = new Utility();

        $months = array('فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند');
        $nowDate = $ut->greg_to_jal(date('Y-m-d'));
        $cmonth = (intval(strlen($nowDate)) == 8 ? intval(substr($nowDate,5,1)) : intval(substr($nowDate,5,2)) );
        $cyear = intval(substr($nowDate,0,4));

        $sqq = "SELECT `year` FROM `budget` WHERE `RowID`={$bid}";
        $rsq = $db->ArrayQuery($sqq);

        $result = array();
        if (intval($cyear) == intval($rsq[0]['year'])){
            $mNum = 13 - $cmonth;
            for ($i=0;$i<$mNum;$i++){
                $result[$i]['title'] = $months[$cmonth-1];
                $result[$i]['value'] = $cmonth;
                $cmonth++;
            }
        }else{
            for ($i=0;$i<12;$i++){
                $result[$i]['title'] = $months[$i];
                $result[$i]['value'] = $i+1;
                $cmonth++;
            }
        }
        return $result;
    }

    public function totalNumberInMonth($month,$components){
        $db = new DBi();
        $sql1 = "SELECT `farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `RowID`={$components}";
        $res1 = $db->ArrayQuery($sql1);
        if (count($res1) > 0) {
            switch ($month) {
                case 1:
                    $number = intval($res1[0]['farvardinTotal']);
                    break;
                case 2:
                    $number = intval($res1[0]['ordibeheshtTotal']);
                    break;
                case 3:
                    $number = intval($res1[0]['khordadTotal']);
                    break;
                case 4:
                    $number = intval($res1[0]['tirTotal']);
                    break;
                case 5:
                    $number = intval($res1[0]['mordadTotal']);
                    break;
                case 6:
                    $number = intval($res1[0]['shahrivarTotal']);
                    break;
                case 7:
                    $number = intval($res1[0]['mehrTotal']);
                    break;
                case 8:
                    $number = intval($res1[0]['abanTotal']);
                    break;
                case 9:
                    $number = intval($res1[0]['azarTotal']);
                    break;
                case 10:
                    $number = intval($res1[0]['deyTotal']);
                    break;
                case 11:
                    $number = intval($res1[0]['bahmanTotal']);
                    break;
                case 12:
                    $number = intval($res1[0]['esfandTotal']);
                    break;
                default:
                    $number = 'فاقد مقدار';
                    break;
            }
        }else{
            $number = 'فاقد مقدار';
        }
        if ($number == 0){
            $number = 'فاقد مقدار';
        }
        return $number;
    }

    public function displacementBudgetInfo($dbID){
        $db = new DBi();
        $sql = "SELECT * FROM `budget_displacement` WHERE `RowID`=".$dbID;
        $res = $db->ArrayQuery($sql);

        $query = "SELECT `HCode` FROM `budget_components_details` WHERE `RowID`={$res[0]['bcdid']}";
        $rst = $db->ArrayQuery($query);

        if(count($res) == 1){
            $res = array("dbID"=>$dbID,"fromYear"=>$res[0]['fromYear'],"fromMonth"=>$res[0]['fromMonth'],"toYear"=>$res[0]['toYear'],"toMonth"=>$res[0]['toMonth'],"bcdid"=>$res[0]['bcdid'],"number"=>$res[0]['number'],"description"=>$res[0]['description'],"HCode"=>$rst[0]['HCode']);
            return $res;
        }else{
            return false;
        }
    }

    public function createDisplacementBudget($year,$toyear,$month,$tomonth,$components,$num,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('displacementBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        if (intval($year) !== intval($toyear)) {
            $sqq = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$toyear}";
            $rsq = $db->ArrayQuery($sqq);
            $cnt = count($rsq);
            $rids = array();
            $gids = array();
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rsq[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $sqq1 = "SELECT `goodID` FROM `budget_components_details` WHERE `bcid` IN ({$rids})";
            $rsq1 = $db->ArrayQuery($sqq1);
            $ccnt = count($rsq1);
            for ($i=0;$i<$ccnt;$i++){
                $gids[] = $rsq1[$i]['goodID'];
            }

            $sqq2 = "SELECT `goodID` FROM `budget_components_details` WHERE `RowID`={$components}";
            $rsq2 = $db->ArrayQuery($sqq2);
            if (!in_array($rsq2[0]['goodID'],$gids)){
                $res = "برای محصول انتخاب شده در سال مقصد جابجایی، بودجه تعریف نشده است !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }

        $query = "SELECT `RowID` FROM `budget_displacement` WHERE `fromYear`={$year} AND `fromMonth`={$month} AND `toYear`={$toyear} AND `toMonth`={$tomonth} AND `bcdid`={$components}";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0){
            $res = "این درخواست جابجایی، برای سال و ماه های انتخابی تکراری می باشد !";
            $out = "false";
            response($res,$out);
            exit;
        }
        $sqls = "SELECT `farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `RowID`={$components}";
        $rsts = $db->ArrayQuery($sqls);
        switch ($month){
            case 1:
                $tNumber = $rsts[0]['farvardinTotal'];
                break;
            case 2:
                $tNumber = $rsts[0]['ordibeheshtTotal'];
                break;
            case 3:
                $tNumber = $rsts[0]['khordadTotal'];
                break;
            case 4:
                $tNumber = $rsts[0]['tirTotal'];
                break;
            case 5:
                $tNumber = $rsts[0]['mordadTotal'];
                break;
            case 6:
                $tNumber = $rsts[0]['shahrivarTotal'];
                break;
            case 7:
                $tNumber = $rsts[0]['mehrTotal'];
                break;
            case 8:
                $tNumber = $rsts[0]['abanTotal'];
                break;
            case 9:
                $tNumber = $rsts[0]['azarTotal'];
                break;
            case 10:
                $tNumber = $rsts[0]['deyTotal'];
                break;
            case 11:
                $tNumber = $rsts[0]['bahmanTotal'];
                break;
            case 12:
                $tNumber = $rsts[0]['esfandTotal'];
                break;
        }
        if (intval($num) > intval($tNumber)){
            $res = "مقدار درخواست جابجایی از مقدار کل محصول در ماه انتخابی بیشتر است !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "INSERT INTO `budget_displacement` (`bcdid`,`fromYear`,`fromMonth`,`toYear`,`toMonth`,`number`,`description`,`lastReceiver`) VALUES ({$components},{$year},{$month},{$toyear},{$tomonth},{$num},'{$desc}',{$_SESSION['userid']})";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editDisplacementBudget($dbid,$year,$toyear,$month,$tomonth,$components,$num,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('displacementBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sqq = "SELECT `fromMonth`,toMonth,`fromYear`,`toYear`,`bcdid`,`finalTick`,`number`,`description` FROM `budget_displacement` WHERE `RowID`={$dbid}";
        $result = $db->ArrayQuery($sqq);

        if (intval($result[0]['finalTick']) == 1){
            $res = "این مورد تایید نهایی شده است و امکان ویرایش ندارد !";
            $out = "false";
            response($res, $out);
            exit;
        }
        if ( (intval($month) !== intval($result[0]['fromMonth'])) || (intval($tomonth) !== intval($result[0]['toMonth'])) || (intval($year) !== intval($result[0]['fromYear'])) || (intval($toyear) !== intval($result[0]['toYear'])) || (intval($components) !== intval($result[0]['bcdid'])) ) {
            $query = "SELECT `RowID` FROM `budget_displacement` WHERE `fromYear`={$year} AND `fromMonth`={$month} AND `toYear`={$toyear} AND `toMonth`={$tomonth} AND `bcdid`={$components}";
            $rst = $db->ArrayQuery($query);
            if (count($rst) > 0){
                $res = "این درخواست جابجایی، برای سال و ماه های انتخابی تکراری می باشد !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }elseif ((intval($num) == intval($result[0]['number'])) && (strlen(trim($desc)) == strlen(trim($result[0]['description'])))){
            $res = "شما هیچ تغییری اعمال ننموده اید !";
            $out = "false";
            response($res, $out);
            exit;
        }

        $sqls = "SELECT `farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `RowID`={$components}";
        $rsts = $db->ArrayQuery($sqls);
        switch ($month){
            case 1:
                $tNumber = $rsts[0]['farvardinTotal'];
                break;
            case 2:
                $tNumber = $rsts[0]['ordibeheshtTotal'];
                break;
            case 3:
                $tNumber = $rsts[0]['khordadTotal'];
                break;
            case 4:
                $tNumber = $rsts[0]['tirTotal'];
                break;
            case 5:
                $tNumber = $rsts[0]['mordadTotal'];
                break;
            case 6:
                $tNumber = $rsts[0]['shahrivarTotal'];
                break;
            case 7:
                $tNumber = $rsts[0]['mehrTotal'];
                break;
            case 8:
                $tNumber = $rsts[0]['abanTotal'];
                break;
            case 9:
                $tNumber = $rsts[0]['azarTotal'];
                break;
            case 10:
                $tNumber = $rsts[0]['deyTotal'];
                break;
            case 11:
                $tNumber = $rsts[0]['bahmanTotal'];
                break;
            case 12:
                $tNumber = $rsts[0]['esfandTotal'];
                break;
        }
        if (intval($num) > intval($tNumber)){
            $res = "مقدار درخواست جابجایی از مقدار کل محصول در ماه انتخابی بیشتر است !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "UPDATE `budget_displacement` SET `bcdid`={$components},`fromYear`={$year},`fromMonth`={$month},`toYear`={$toyear},`toMonth`={$tomonth},`number`={$num},`description`='{$desc}',`productionTick`=2,`planningTick`=2,`productionDescription`='',`planningDescription`='' WHERE `RowID`={$dbid}";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function displacementBudgetComment($dbid){
        $db = new DBi();
        $sql = "SELECT `productionTick`,`planningTick`,`productionDescription`,`planningDescription` FROM `budget_displacement` WHERE `RowID`=".$dbid;
        $res = $db->ArrayQuery($sql);
        switch ($res[0]['productionTick']){
            case 0:
                $productionTick = 'عدم تایید';
                $bg = 'bg-danger-light';
                break;
            case 1:
                $productionTick = 'تایید شده';
                $res[0]['productionDescription'] = 'مورد تایید می باشد';
                $bg = 'bg-success-light';
                break;
            case 2:
                $productionTick = 'در حال بررسی';
                $res[0]['productionDescription'] = 'در حال بررسی می باشد';
                $bg = 'bg-warning-light';
                break;
        }
        switch ($res[0]['planningTick']){
            case 0:
                $planningTick = 'عدم تایید';
                $bg1 = 'bg-danger-light';
                break;
            case 1:
                $planningTick = 'تایید شده';
                $res[0]['planningDescription'] = 'مورد تایید می باشد';
                $bg1 = 'bg-success-light';
                break;
            case 2:
                $planningTick = 'در حال بررسی';
                $res[0]['planningDescription'] = 'در حال بررسی می باشد';
                $bg1 = 'bg-warning-light';
                break;
        }
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="displacementBudgetCommentHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">نظر واحد برنامه ریزی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">وضعیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">نظر واحد تولید</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">وضعیت</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $htm .= '<tr class="table-secondary">';
        $htm .= '<td class="'.$bg1.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]['planningDescription'].'</td>';
        $htm .= '<td class="'.$bg1.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$planningTick.'</td>';
        $htm .= '<td class="'.$bg.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]['productionDescription'].'</td>';
        $htm .= '<td class="'.$bg.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$productionTick.'</td>';
        $htm .= '</tr>';
        $htm .= '</tbody>';
        $htm .= '</table>';

        return $htm;
    }

    public function recordDisplacementBudgetComment($dbid,$desc,$radioValue){
        $acm = new acm();
        $db = new DBi();

        $sqq = "SELECT `productionTick`,`planningTick` FROM `budget_displacement` WHERE `RowID`={$dbid}";
        $rsq = $db->ArrayQuery($sqq);
        if ($acm->hasAccess('productionTickBudget') && intval($rsq[0]['productionTick']) == 1){
            $res = "شما قبلا این مورد را تایید نموده اید !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif ($acm->hasAccess('productionTickBudget')){
            $m = '`productionTick`='.$radioValue;
            $n = ',`productionDescription`="'.$desc.'"';
        }

        if ($acm->hasAccess('planningTickBudget') && intval($rsq[0]['planningTick']) == 1){
            $res = "شما قبلا این مورد را تایید نموده اید !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif ($acm->hasAccess('planningTickBudget')){
            $m = '`planningTick`='.$radioValue;
            $n = ',`planningDescription`="'.$desc.'"';
        }

        $sql = "UPDATE `budget_displacement` SET ".$m.$n." WHERE `RowID`={$dbid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function displacementBudgetWorkflowHtm($dbid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `budget_displacement_workflow`.*,`fname`,`lname` FROM `budget_displacement_workflow` INNER JOIN `users` ON (`budget_displacement_workflow`.`sender`=`users`.`RowID`) WHERE `dbid`={$dbid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="displacementBudgetWorkflowHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">فرستنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">گیرنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زمان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$i]['receiver']}";
            $rst = $db->ArrayQuery($query);
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$rst[0]['fname'].' '.$rst[0]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createDate'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function SendDisplacementBudget($dbid,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('displacementBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $sqlu = "SELECT `finalTick` FROM `budget_displacement` WHERE `RowID`={$dbid}";
        $resu = $db->ArrayQuery($sqlu);
        if (intval($resu[0]['finalTick']) == 1){
            $res = "این مورد تایید نهایی شده است و امکان ارجاع ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        $cartable = 'جابجایی بودجه';

        $sql1 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=102";  // واحد برنامه ریزی
        $res1 = $db->ArrayQuery($sql1);

        $sqlp = "SELECT `phone` FROM `users` WHERE `RowID`={$res1[0]['user_id']}";
        $resp = $db->ArrayQuery($sqlp);

        $sql2 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=101";  // واحد تولید
        $res2 = $db->ArrayQuery($sql2);

        $sqlp1 = "SELECT `phone` FROM `users` WHERE `RowID`={$res2[0]['user_id']}";
        $resp1 = $db->ArrayQuery($sqlp1);

        if ($acm->hasAccess('editCreateDisplacementBudget')){   // معاونت بازرگانی بود
            $sql3 = "INSERT INTO `budget_displacement_workflow` (`sender`,`receiver`,`dbid`,`createDate`,`createTime`,`description`) VALUES ({$_SESSION['userid']},{$res1[0]['user_id']},{$dbid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

            $sql4 = "UPDATE `budget_displacement` SET `lastReceiver`={$res1[0]['user_id']} WHERE `RowID`={$dbid}";
            $db->Query($sql4);

            $phone = $resp[0]['phone'];
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }elseif ($acm->hasAccess('planningTickBudget')){
            $sql3 = "INSERT INTO `budget_displacement_workflow` (`sender`,`receiver`,`dbid`,`createDate`,`createTime`,`description`) VALUES ({$res1[0]['user_id']},20,{$dbid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

/*            $sql4 = "UPDATE `budget_displacement` SET `lastReceiver`={$res2[0]['user_id']} WHERE `RowID`={$dbid}";
            $db->Query($sql4);*/
            $sql4 = "UPDATE `budget_displacement` SET `lastReceiver`=20,`productionTick`=1 WHERE `RowID`={$dbid}";
            $db->Query($sql4);

            $phone = $resp1[0]['phone'];
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }elseif ($acm->hasAccess('productionTickBudget')){
            $sql3 = "INSERT INTO `budget_displacement_workflow` (`sender`,`receiver`,`dbid`,`createDate`,`createTime`,`description`) VALUES ({$res2[0]['user_id']},20,{$dbid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

            $sql4 = "UPDATE `budget_displacement` SET `lastReceiver`=20 WHERE `RowID`={$dbid}";
            $db->Query($sql4);

            $phone = '9153131176';
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }else{
            return false;
        }
    }

    public function finalTickDisplacementBudget($dbid){
        $db = new DBi();

        $query = "SELECT `RowID` FROM `budget_displacement` WHERE `RowID`={$dbid} AND (`productionTick`!=1 OR `planningTick`!=1)";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0){
            $res = "این مورد تاییدیه برنامه ریزی یا تولید را ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }else{
            $sql = "SELECT `fromYear`,`fromMonth`,`toYear`,`toMonth`,`number`,`bcdid`,`finalTick` FROM `budget_displacement` WHERE `RowID`={$dbid}";
            $res = $db->ArrayQuery($sql);
            if (intval($res[0]['finalTick']) == 1){
                $res = "شما قبلا این مورد را تایید نهایی نموده اید !";
                $out = "false";
                response($res,$out);
                exit;
            }

            $sql1 = "SELECT `goodID`,`farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `RowID`={$res[0]['bcdid']}";
            $res1 = $db->ArrayQuery($sql1);

            switch ($res[0]['fromMonth']){
                case 1:
                    $number = intval($res1[0]['farvardinTotal']);
                    $m = ' `farvardinTotal`';
                    break;
                case 2:
                    $number = intval($res1[0]['ordibeheshtTotal']);
                    $m = ' `ordibeheshtTotal`';
                    break;
                case 3:
                    $number = intval($res1[0]['khordadTotal']);
                    $m = ' `khordadTotal`';
                    break;
                case 4:
                    $number = intval($res1[0]['tirTotal']);
                    $m = ' `tirTotal`';
                    break;
                case 5:
                    $number = intval($res1[0]['mordadTotal']);
                    $m = ' `mordadTotal`';
                    break;
                case 6:
                    $number = intval($res1[0]['shahrivarTotal']);
                    $m = ' `shahrivarTotal`';
                    break;
                case 7:
                    $number = intval($res1[0]['mehrTotal']);
                    $m = ' `mehrTotal`';
                    break;
                case 8:
                    $number = intval($res1[0]['abanTotal']);
                    $m = ' `abanTotal`';
                    break;
                case 9:
                    $number = intval($res1[0]['azarTotal']);
                    $m = ' `azarTotal`';
                    break;
                case 10:
                    $number = intval($res1[0]['deyTotal']);
                    $m = ' `deyTotal`';
                    break;
                case 11:
                    $number = intval($res1[0]['bahmanTotal']);
                    $m = ' `bahmanTotal`';
                    break;
                case 12:
                    $number = intval($res1[0]['esfandTotal']);
                    $m = ' `esfandTotal`';
                    break;
            }

            if (intval($res[0]['fromMonth']) == intval($res[0]['toYear'])) {
                switch ($res[0]['toMonth']) {
                    case 1:
                        $number1 = intval($res1[0]['farvardinTotal']);
                        $m1 = ' `farvardinTotal`';
                        break;
                    case 2:
                        $number1 = intval($res1[0]['ordibeheshtTotal']);
                        $m1 = ' `ordibeheshtTotal`';
                        break;
                    case 3:
                        $number1 = intval($res1[0]['khordadTotal']);
                        $m1 = ' `khordadTotal`';
                        break;
                    case 4:
                        $number1 = intval($res1[0]['tirTotal']);
                        $m1 = ' `tirTotal`';
                        break;
                    case 5:
                        $number1 = intval($res1[0]['mordadTotal']);
                        $m1 = ' `mordadTotal`';
                        break;
                    case 6:
                        $number1 = intval($res1[0]['shahrivarTotal']);
                        $m1 = ' `shahrivarTotal`';
                        break;
                    case 7:
                        $number1 = intval($res1[0]['mehrTotal']);
                        $m1 = ' `mehrTotal`';
                        break;
                    case 8:
                        $number1 = intval($res1[0]['abanTotal']);
                        $m1 = ' `abanTotal`';
                        break;
                    case 9:
                        $number1 = intval($res1[0]['azarTotal']);
                        $m1 = ' `azarTotal`';
                        break;
                    case 10:
                        $number1 = intval($res1[0]['deyTotal']);
                        $m1 = ' `deyTotal`';
                        break;
                    case 11:
                        $number1 = intval($res1[0]['bahmanTotal']);
                        $m1 = ' `bahmanTotal`';
                        break;
                    case 12:
                        $number1 = intval($res1[0]['esfandTotal']);
                        $m1 = ' `esfandTotal`';
                        break;
                }

                $number = intval($number) - intval($res[0]['number']);
                $number1 = intval($number1) + intval($res[0]['number']);

                $sqlu = "UPDATE `budget_components_details` SET".$m."=".$number.",".$m1."=".$number1." WHERE `RowID`={$res[0]['bcdid']}";
                $db->Query($sqlu);
            }else{
                $sqll = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$res[0]['toYear']}";
                $rstl = $db->ArrayQuery($sqll);
                $ccnt = count($rstl);
                $rids = array();
                for ($i=0;$i<$ccnt;$i++){
                    $rids[] = $rstl[$i]['RowID'];
                }
                $rids = implode(',',$rids);
                $sqle = "SELECT `RowID`,`farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `goodID`={$res1[0]['goodID']}";
                $rste = $db->ArrayQuery($sqle);

                switch ($res[0]['toMonth']) {
                    case 1:
                        $number1 = intval($rste[0]['farvardinTotal']);
                        $m1 = ' `farvardinTotal`';
                        break;
                    case 2:
                        $number1 = intval($rste[0]['ordibeheshtTotal']);
                        $m1 = ' `ordibeheshtTotal`';
                        break;
                    case 3:
                        $number1 = intval($rste[0]['khordadTotal']);
                        $m1 = ' `khordadTotal`';
                        break;
                    case 4:
                        $number1 = intval($rste[0]['tirTotal']);
                        $m1 = ' `tirTotal`';
                        break;
                    case 5:
                        $number1 = intval($rste[0]['mordadTotal']);
                        $m1 = ' `mordadTotal`';
                        break;
                    case 6:
                        $number1 = intval($rste[0]['shahrivarTotal']);
                        $m1 = ' `shahrivarTotal`';
                        break;
                    case 7:
                        $number1 = intval($rste[0]['mehrTotal']);
                        $m1 = ' `mehrTotal`';
                        break;
                    case 8:
                        $number1 = intval($rste[0]['abanTotal']);
                        $m1 = ' `abanTotal`';
                        break;
                    case 9:
                        $number1 = intval($rste[0]['azarTotal']);
                        $m1 = ' `azarTotal`';
                        break;
                    case 10:
                        $number1 = intval($rste[0]['deyTotal']);
                        $m1 = ' `deyTotal`';
                        break;
                    case 11:
                        $number1 = intval($rste[0]['bahmanTotal']);
                        $m1 = ' `bahmanTotal`';
                        break;
                    case 12:
                        $number1 = intval($rste[0]['esfandTotal']);
                        $m1 = ' `esfandTotal`';
                        break;
                }

                $number = intval($number) - intval($res[0]['number']);
                $number1 = intval($number1) + intval($res[0]['number']);

                $sqlu = "UPDATE `budget_components_details` SET".$m."=".$number." WHERE `RowID`={$res[0]['bcdid']}";
                $db->Query($sqlu);

                $sqlu1 = "UPDATE `budget_components_details` SET".$m1."=".$number1." WHERE `RowID`={$rste[0]['RowID']}";
                $db->Query($sqlu1);
            }

            $sql2 = "UPDATE `budget_displacement` SET `finalTick`=1 WHERE `RowID`={$dbid}";
            $db->Query($sql2);
            $aff = $db->AffectedRows();
            $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
            if (intval($aff) > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function getDisplacementProductNameWithHcode($bid,$month,$hcode){
        $db = new DBi();

        $query1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$bid} AND `finalTick`=1";
        $rst1 = $db->ArrayQuery($query1);
        $rids = array();
        if (count($rst1) > 0){
            $cnt = count($rst1);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rst1[$i]['RowID'];
                $rids = implode(',',$rids);
            }
        }else{
            $rids = 0;
        }

        $sql = "SELECT `RowID`,`farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `HCode`='{$hcode}'";
        $res = $db->ArrayQuery($sql);

        switch ($month){
            case 1:
                $tNumber = $res[0]['farvardinTotal'];
                break;
            case 2:
                $tNumber = $res[0]['ordibeheshtTotal'];
                break;
            case 3:
                $tNumber = $res[0]['khordadTotal'];
                break;
            case 4:
                $tNumber = $res[0]['tirTotal'];
                break;
            case 5:
                $tNumber = $res[0]['mordadTotal'];
                break;
            case 6:
                $tNumber = $res[0]['shahrivarTotal'];
                break;
            case 7:
                $tNumber = $res[0]['mehrTotal'];
                break;
            case 8:
                $tNumber = $res[0]['abanTotal'];
                break;
            case 9:
                $tNumber = $res[0]['azarTotal'];
                break;
            case 10:
                $tNumber = $res[0]['deyTotal'];
                break;
            case 11:
                $tNumber = $res[0]['bahmanTotal'];
                break;
            case 12:
                $tNumber = $res[0]['esfandTotal'];
                break;
        }

        $idd = (strlen(trim($hcode)) == 0 ? 0 : $res[0]['RowID']);
        $tNumber = (intval($idd) > 0 ? $tNumber : 0);
        return array($idd,$tNumber);
    }

    //******************** مجوز تاخیر در تحویل بودجه فروش ********************

    public function getDelayBudgetList($year,$component,$month,$hcode,$finalTick,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('delayBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(intval($year) > 0){
            $w[] = '`bid`='.$year.' ';
        }
        if(intval($component) > 0){
            $w[] = '`bcdid`='.$component.' ';
        }
        if(intval($month) > 0){
            $w[] = '`fromMonth`='.$month.' ';
        }
        if (intval($_SESSION['userid']) !== 1){
            $w[] = '`lastReceiver`='.$_SESSION['userid'].' ';
        }
        if(strlen(trim($hcode)) > 0 && intval($year) > 0) {
            $rids = array();
            $sqq = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$year}";
            $rsq = $db->ArrayQuery($sqq);
            $cnt = count($rsq);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rsq[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $sqq1 = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `HCode`='{$hcode}'";
            $rsq1 = $db->ArrayQuery($sqq1);
            $w[] = '`bcdid`='.$rsq1[0]['RowID'].' ';
        }
        if(strlen(trim($hcode)) > 0 && intval($year) <= 0) {
            $rids = array();
            $sqq1 = "SELECT `RowID` FROM `budget_components_details` WHERE `HCode`='{$hcode}'";
            $rsq1 = $db->ArrayQuery($sqq1);
            $cnt = count($rsq1);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rsq1[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $w[] = '`bcdid` IN ('.$rids.') ';
        }
        $w[] = '`finalTick`='.$finalTick.' ';

        $sql = "SELECT `budget_delay`.*,`year` FROM `budget_delay` INNER JOIN `budget` ON (`budget_delay`.`bid`=`budget`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            $query = "SELECT * FROM `budget_components_details` WHERE `RowID`={$res[$y]['bcdid']}";
            $rst = $db->ArrayQuery($query);
            switch ($res[$y]['fromMonth']){
                case 1:
                    $tNumber = $rst[0]['farvardinTotal'];
                    $monthtxt = 'فروردین';
                    break;
                case 2:
                    $tNumber = $rst[0]['ordibeheshtTotal'];
                    $monthtxt = 'اردیبهشت';
                    break;
                case 3:
                    $tNumber = $rst[0]['khordadTotal'];
                    $monthtxt = 'خرداد';
                    break;
                case 4:
                    $tNumber = $rst[0]['tirTotal'];
                    $monthtxt = 'تیر';
                    break;
                case 5:
                    $tNumber = $rst[0]['mordadTotal'];
                    $monthtxt = 'مرداد';
                    break;
                case 6:
                    $tNumber = $rst[0]['shahrivarTotal'];
                    $monthtxt = 'شهریور';
                    break;
                case 7:
                    $tNumber = $rst[0]['mehrTotal'];
                    $monthtxt = 'مهر';
                    break;
                case 8:
                    $tNumber = $rst[0]['abanTotal'];
                    $monthtxt = 'آبان';
                    break;
                case 9:
                    $tNumber = $rst[0]['azarTotal'];
                    $monthtxt = 'آذر';
                    break;
                case 10:
                    $tNumber = $rst[0]['deyTotal'];
                    $monthtxt = 'دی';
                    break;
                case 11:
                    $tNumber = $rst[0]['bahmanTotal'];
                    $monthtxt = 'بهمن';
                    break;
                case 12:
                    $tNumber = $rst[0]['esfandTotal'];
                    $monthtxt = 'اسفند';
                    break;
            }
            switch ($res[$y]['toMonth']){
                case 1:
                    $tNumber1 = $rst[0]['farvardinTotal'];
                    $monthtxt = 'فروردین';
                    break;
                case 2:
                    $tNumber1 = $rst[0]['ordibeheshtTotal'];
                    $monthtxt1 = 'اردیبهشت';
                    break;
                case 3:
                    $tNumber1 = $rst[0]['khordadTotal'];
                    $monthtxt1 = 'خرداد';
                    break;
                case 4:
                    $tNumber1 = $rst[0]['tirTotal'];
                    $monthtxt1 = 'تیر';
                    break;
                case 5:
                    $tNumber1 = $rst[0]['mordadTotal'];
                    $monthtxt1 = 'مرداد';
                    break;
                case 6:
                    $tNumber1 = $rst[0]['shahrivarTotal'];
                    $monthtxt1 = 'شهریور';
                    break;
                case 7:
                    $tNumber1 = $rst[0]['mehrTotal'];
                    $monthtxt1 = 'مهر';
                    break;
                case 8:
                    $tNumber1 = $rst[0]['abanTotal'];
                    $monthtxt1 = 'آبان';
                    break;
                case 9:
                    $tNumber1 = $rst[0]['azarTotal'];
                    $monthtxt1 = 'آذر';
                    break;
                case 10:
                    $tNumber1 = $rst[0]['deyTotal'];
                    $monthtxt1 = 'دی';
                    break;
                case 11:
                    $tNumber1 = $rst[0]['bahmanTotal'];
                    $monthtxt1 = 'بهمن';
                    break;
                case 12:
                    $tNumber1 = $rst[0]['esfandTotal'];
                    $monthtxt1 = 'اسفند';
                    break;
            }

            $finalRes[$y]['bgColor'] = ($res[$y]['finalTick'] == 0 ? 'table-danger' : 'table-success');
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['year'] = $res[$y]['year'];
            $finalRes[$y]['HCode'] = $rst[0]['HCode'];
            $finalRes[$y]['gName'] = $rst[0]['gName'];
            $finalRes[$y]['monthtxt'] = $monthtxt;
            $finalRes[$y]['number'] = $res[$y]['number'];
            $finalRes[$y]['tNumber'] = $tNumber;
            $finalRes[$y]['monthtxt1'] = $monthtxt1;
            $finalRes[$y]['tNumber1'] = $tNumber1;
            $finalRes[$y]['description'] = $res[$y]['description'];
        }
        return $finalRes;
    }

    public function getDelayBudgetListCountRows($year,$component,$month,$finalTick,$hcode){
        $db = new DBi();
        $w = array();
        if(intval($year) > 0){
            $w[] = '`bid`='.$year.' ';
        }
        if(intval($component) > 0){
            $w[] = '`bcdid`='.$component.' ';
        }
        if(intval($month) > 0){
            $w[] = '`fromMonth`='.$month.' ';
        }
        if (intval($_SESSION['userid']) !== 1){
            $w[] = '`lastReceiver`='.$_SESSION['userid'].' ';
        }
        if(strlen(trim($hcode)) > 0 && intval($year) > 0) {
            $rids = array();
            $sqq = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$year}";
            $rsq = $db->ArrayQuery($sqq);
            $cnt = count($rsq);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rsq[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $sqq1 = "SELECT `RowID` FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `HCode`='{$hcode}'";
            $rsq1 = $db->ArrayQuery($sqq1);
            $w[] = '`bcdid`='.$rsq1[0]['RowID'].' ';
        }
        if(strlen(trim($hcode)) > 0 && intval($year) <= 0) {
            $rids = array();
            $sqq1 = "SELECT `RowID` FROM `budget_components_details` WHERE `HCode`='{$hcode}'";
            $rsq1 = $db->ArrayQuery($sqq1);
            $cnt = count($rsq1);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rsq1[$i]['RowID'];
            }
            $rids = implode(',',$rids);
            $w[] = '`bcdid` IN ('.$rids.') ';
        }
        $w[] = '`finalTick`='.$finalTick.' ';

        $sql = "SELECT `RowID` FROM `budget_delay`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function delayBudgetInfo($dbID){
        $db = new DBi();
        $sql = "SELECT * FROM `budget_delay` WHERE `RowID`=".$dbID;
        $res = $db->ArrayQuery($sql);

        $query = "SELECT `HCode` FROM `budget_components_details` WHERE `RowID`={$res[0]['bcdid']}";
        $rst = $db->ArrayQuery($query);

        if(count($res) == 1){
            $res = array("dbID"=>$dbID,"bid"=>$res[0]['bid'],"fromMonth"=>$res[0]['fromMonth'],"toMonth"=>$res[0]['toMonth'],"bcdid"=>$res[0]['bcdid'],"number"=>$res[0]['number'],"description"=>$res[0]['description'],"HCode"=>$rst[0]['HCode']);
            return $res;
        }else{
            return false;
        }
    }

    public function createDelayBudget($year,$month,$tomonth,$components,$num,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('delayBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $query = "SELECT `fromMonth`,`toMonth` FROM `budget_delay` WHERE `bid`={$year} AND `bcdid`={$components}";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0){
            $cnt = count($rst);
            for ($i=0;$i<$cnt;$i++){
                if ( (intval($month) == intval($rst[$i]['fromMonth'])) && (intval($tomonth) == intval($rst[$i]['toMonth'])) ){
                    $res = "برای این محصول از ماه، به ماه انتخابی قبلا درخواست داده شده است !";
                    $out = "false";
                    response($res,$out);
                    exit;
                }
            }
        }
        $sqls = "SELECT `farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `RowID`={$components}";
        $rsts = $db->ArrayQuery($sqls);
        switch ($month){
            case 1:
                $tNumber = $rsts[0]['farvardinTotal'];
                break;
            case 2:
                $tNumber = $rsts[0]['ordibeheshtTotal'];
                break;
            case 3:
                $tNumber = $rsts[0]['khordadTotal'];
                break;
            case 4:
                $tNumber = $rsts[0]['tirTotal'];
                break;
            case 5:
                $tNumber = $rsts[0]['mordadTotal'];
                break;
            case 6:
                $tNumber = $rsts[0]['shahrivarTotal'];
                break;
            case 7:
                $tNumber = $rsts[0]['mehrTotal'];
                break;
            case 8:
                $tNumber = $rsts[0]['abanTotal'];
                break;
            case 9:
                $tNumber = $rsts[0]['azarTotal'];
                break;
            case 10:
                $tNumber = $rsts[0]['deyTotal'];
                break;
            case 11:
                $tNumber = $rsts[0]['bahmanTotal'];
                break;
            case 12:
                $tNumber = $rsts[0]['esfandTotal'];
                break;
        }
        if (intval($num) > intval($tNumber)){
            $res = "مقدار درخواستی از مقدار کل محصول در ماه انتخابی بیشتر است !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "INSERT INTO `budget_delay` (`bid`,`bcdid`,`fromMonth`,`toMonth`,`number`,`description`,`lastReceiver`) VALUES ({$year},{$components},{$month},{$tomonth},{$num},'{$desc}',{$_SESSION['userid']})";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editDelayBudget($dbid,$year,$month,$tomonth,$components,$num,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('delayBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sqq = "SELECT `fromMonth`,`toMonth`,`bcdid`,`finalTick`,`number`,`description` FROM `budget_delay` WHERE `RowID`={$dbid}";
        $result = $db->ArrayQuery($sqq);

        if (intval($result[0]['finalTick']) == 1){
            $res = "این مورد تایید نهایی شده است و امکان ویرایش ندارد !";
            $out = "false";
            response($res, $out);
            exit;
        }
        if ( (intval($month) !== intval($result[0]['fromMonth'])) || (intval($tomonth) !== intval($result[0]['toMonth']))|| (intval($components) !== intval($result[0]['bcdid'])) ) {
            $query = "SELECT `fromMonth`,`toMonth` FROM `budget_delay` WHERE `bid`={$year} AND `bcdid`={$components}";
            $rst = $db->ArrayQuery($query);
            if (count($rst) > 0) {
                $cnt = count($rst);
                for ($i = 0; $i < $cnt; $i++) {
                    if ( (intval($month) == intval($rst[$i]['fromMonth'])) && (intval($tomonth) == intval($rst[$i]['toMonth'])) ) {
                        $res = "برای این محصول درخواستی از ماه، به ماه انتخابی قبلا داده شده است !";
                        $out = "false";
                        response($res, $out);
                        exit;
                    }
                }
            }
        }elseif ((intval($num) == intval($result[0]['number'])) && (strlen(trim($desc)) == strlen(trim($result[0]['description'])))){
            $res = "شما هیچ تغییری اعمال ننموده اید !";
            $out = "false";
            response($res, $out);
            exit;
        }

        $sqls = "SELECT `farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `RowID`={$components}";
        $rsts = $db->ArrayQuery($sqls);
        switch ($month){
            case 1:
                $tNumber = $rsts[0]['farvardinTotal'];
                break;
            case 2:
                $tNumber = $rsts[0]['ordibeheshtTotal'];
                break;
            case 3:
                $tNumber = $rsts[0]['khordadTotal'];
                break;
            case 4:
                $tNumber = $rsts[0]['tirTotal'];
                break;
            case 5:
                $tNumber = $rsts[0]['mordadTotal'];
                break;
            case 6:
                $tNumber = $rsts[0]['shahrivarTotal'];
                break;
            case 7:
                $tNumber = $rsts[0]['mehrTotal'];
                break;
            case 8:
                $tNumber = $rsts[0]['abanTotal'];
                break;
            case 9:
                $tNumber = $rsts[0]['azarTotal'];
                break;
            case 10:
                $tNumber = $rsts[0]['deyTotal'];
                break;
            case 11:
                $tNumber = $rsts[0]['bahmanTotal'];
                break;
            case 12:
                $tNumber = $rsts[0]['esfandTotal'];
                break;
        }
        if (intval($num) > intval($tNumber)){
            $res = "مقدار درخواستی از مقدار کل محصول در ماه انتخابی بیشتر است !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "UPDATE `budget_delay` SET `bid`={$year},`bcdid`={$components},`fromMonth`={$month},`toMonth`={$tomonth},`number`={$num},`description`='{$desc}',`assistantTick`=2,`assistantDescription`='' WHERE `RowID`={$dbid}";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function delayBudgetComment($dbid){
        $db = new DBi();
        $sql = "SELECT `assistantTick`,`assistantDescription` FROM `budget_delay` WHERE `RowID`=".$dbid;
        $res = $db->ArrayQuery($sql);
        switch ($res[0]['assistantTick']){
            case 0:
                $assistantTick = 'عدم تایید';
                $bg = 'bg-danger-light';
                break;
            case 1:
                $assistantTick = 'تایید شده';
                $res[0]['assistantDescription'] = 'مورد تایید می باشد';
                $bg = 'bg-success-light';
                break;
            case 2:
                $assistantTick = 'در حال بررسی';
                $res[0]['assistantDescription'] = 'در حال بررسی می باشد';
                $bg = 'bg-warning-light';
                break;
        }
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="delayBudgetCommentHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 70%;">نظر معاونت بازرگانی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 30%;">وضعیت</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $htm .= '<tr class="table-secondary">';
        $htm .= '<td class="'.$bg.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]['assistantDescription'].'</td>';
        $htm .= '<td class="'.$bg.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$assistantTick.'</td>';
        $htm .= '</tr>';
        $htm .= '</tbody>';
        $htm .= '</table>';

        return $htm;
    }

    public function recordDelayBudgetComment($dbid,$desc,$radioValue){
        $db = new DBi();

        $sqq = "SELECT `assistantTick` FROM `budget_delay` WHERE `RowID`={$dbid}";
        $rsq = $db->ArrayQuery($sqq);
        if (intval($rsq[0]['assistantTick']) == 1){
            $res = "شما قبلا این مورد را تایید نموده اید !";
            $out = "false";
            response($res,$out);
            exit;
        }else{
            $m = '`assistantTick`='.$radioValue;
            $n = ',`assistantDescription`="'.$desc.'"';
        }

        $sql = "UPDATE `budget_delay` SET ".$m.$n." WHERE `RowID`={$dbid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function delayBudgetWorkflowHtm($dbid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `budget_delay_workflow`.*,`fname`,`lname` FROM `budget_delay_workflow` INNER JOIN `users` ON (`budget_delay_workflow`.`sender`=`users`.`RowID`) WHERE `dbid`={$dbid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="delayBudgetWorkflowHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">فرستنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">گیرنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زمان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$i]['receiver']}";
            $rst = $db->ArrayQuery($query);
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$rst[0]['fname'].' '.$rst[0]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createDate'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function SendDelayBudget($dbid,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('delayBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $sqlu = "SELECT `finalTick` FROM `budget_delay` WHERE `RowID`={$dbid}";
        $resu = $db->ArrayQuery($sqlu);
        if (intval($resu[0]['finalTick']) == 1){
            $res = "این مورد تایید نهایی شده است و امکان ارجاع ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        $cartable = 'تاخیر در تحویل بودجه';

        $sql1 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=102";  // واحد برنامه ریزی
        $res1 = $db->ArrayQuery($sql1);

        $sqlp = "SELECT `phone` FROM `users` WHERE `RowID`={$res1[0]['user_id']}";
        $resp = $db->ArrayQuery($sqlp);

        if ($acm->hasAccess('planningTickBudget') || $acm->hasAccess('editCreateDelayBudget')){  // واحد برنامه ریزی
            $sql3 = "INSERT INTO `budget_delay_workflow` (`sender`,`receiver`,`dbid`,`createDate`,`createTime`,`description`) VALUES ({$res1[0]['user_id']},20,{$dbid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

            $sql4 = "UPDATE `budget_delay` SET `lastReceiver`=20 WHERE `RowID`={$dbid}";
            $db->Query($sql4);

            $phone = '9153131176';
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }elseif ($acm->hasAccess('editCreateBudgetComponents')){
            $sql3 = "INSERT INTO `budget_delay_workflow` (`sender`,`receiver`,`dbid`,`createDate`,`createTime`,`description`) VALUES ({$_SESSION['userid']},{$res1[0]['user_id']},{$dbid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

            $sql4 = "UPDATE `budget_delay` SET `lastReceiver`={$res1[0]['user_id']} WHERE `RowID`={$dbid}";
            $db->Query($sql4);

            $phone = $resp[0]['phone'];
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }else{
            return false;
        }
    }

    public function finalTickDelayBudget($dbid){
        $db = new DBi();
        $query = "SELECT `RowID` FROM `budget_delay` WHERE `RowID`={$dbid} AND `assistantTick`=0";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0){
            $res = "این مورد توسط شما رد شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }else{
            $sql = "SELECT `fromMonth`,`toMonth`,`number`,`bcdid`,`finalTick` FROM `budget_delay` WHERE `RowID`={$dbid}";
            $res = $db->ArrayQuery($sql);
            if (intval($res[0]['finalTick']) == 1){
                $res = "شما قبلا این مورد را تایید نهایی نموده اید !";
                $out = "false";
                response($res,$out);
                exit;
            }

            $sql1 = "SELECT `farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `RowID`={$res[0]['bcdid']}";
            $res1 = $db->ArrayQuery($sql1);

            switch ($res[0]['fromMonth']){
                case 1:
                    $number = intval($res1[0]['farvardinTotal']);
                    $m = ' `farvardinTotal`';
                    break;
                case 2:
                    $number = intval($res1[0]['ordibeheshtTotal']);
                    $m = ' `ordibeheshtTotal`';
                    break;
                case 3:
                    $number = intval($res1[0]['khordadTotal']);
                    $m = ' `khordadTotal`';
                    break;
                case 4:
                    $number = intval($res1[0]['tirTotal']);
                    $m = ' `tirTotal`';
                    break;
                case 5:
                    $number = intval($res1[0]['mordadTotal']);
                    $m = ' `mordadTotal`';
                    break;
                case 6:
                    $number = intval($res1[0]['shahrivarTotal']);
                    $m = ' `shahrivarTotal`';
                    break;
                case 7:
                    $number = intval($res1[0]['mehrTotal']);
                    $m = ' `mehrTotal`';
                    break;
                case 8:
                    $number = intval($res1[0]['abanTotal']);
                    $m = ' `abanTotal`';
                    break;
                case 9:
                    $number = intval($res1[0]['azarTotal']);
                    $m = ' `azarTotal`';
                    break;
                case 10:
                    $number = intval($res1[0]['deyTotal']);
                    $m = ' `deyTotal`';
                    break;
                case 11:
                    $number = intval($res1[0]['bahmanTotal']);
                    $m = ' `bahmanTotal`';
                    break;
                case 12:
                    $number = intval($res1[0]['esfandTotal']);
                    $m = ' `esfandTotal`';
                    break;
            }
            switch ($res[0]['toMonth']){
                case 1:
                    $number1 = intval($res1[0]['farvardinTotal']);
                    $m1 = ' `farvardinTotal`';
                    break;
                case 2:
                    $number1 = intval($res1[0]['ordibeheshtTotal']);
                    $m1 = ' `ordibeheshtTotal`';
                    break;
                case 3:
                    $number1 = intval($res1[0]['khordadTotal']);
                    $m1 = ' `khordadTotal`';
                    break;
                case 4:
                    $number1 = intval($res1[0]['tirTotal']);
                    $m1 = ' `tirTotal`';
                    break;
                case 5:
                    $number1 = intval($res1[0]['mordadTotal']);
                    $m1 = ' `mordadTotal`';
                    break;
                case 6:
                    $number1 = intval($res1[0]['shahrivarTotal']);
                    $m1 = ' `shahrivarTotal`';
                    break;
                case 7:
                    $number1 = intval($res1[0]['mehrTotal']);
                    $m1 = ' `mehrTotal`';
                    break;
                case 8:
                    $number1 = intval($res1[0]['abanTotal']);
                    $m1 = ' `abanTotal`';
                    break;
                case 9:
                    $number1 = intval($res1[0]['azarTotal']);
                    $m1 = ' `azarTotal`';
                    break;
                case 10:
                    $number1 = intval($res1[0]['deyTotal']);
                    $m1 = ' `deyTotal`';
                    break;
                case 11:
                    $number1 = intval($res1[0]['bahmanTotal']);
                    $m1 = ' `bahmanTotal`';
                    break;
                case 12:
                    $number1 = intval($res1[0]['esfandTotal']);
                    $m1 = ' `esfandTotal`';
                    break;
            }

            $number = intval($number) - intval($res[0]['number']);
            $number1 = intval($number1) + intval($res[0]['number']);

            $sqlu = "UPDATE `budget_components_details` SET".$m."=".$number.",".$m1."=".$number1." WHERE `RowID`={$res[0]['bcdid']}";
            $db->Query($sqlu);

            $sql2 = "UPDATE `budget_delay` SET `finalTick`=1,`assistantTick`=1 WHERE `RowID`={$dbid}";
            $db->Query($sql2);
            $aff = $db->AffectedRows();
            $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
            if (intval($aff) > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function getDelayProductNameWithHcode($bid,$month,$hcode){
        $db = new DBi();

        $query1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$bid} AND `finalTick`=1";
        $rst1 = $db->ArrayQuery($query1);
        $rids = array();
        if (count($rst1) > 0){
            $cnt = count($rst1);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rst1[$i]['RowID'];
                $rids = implode(',',$rids);
            }
        }else{
            $rids = 0;
        }

        $sql = "SELECT `RowID`,`farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `HCode`='{$hcode}'";
        $res = $db->ArrayQuery($sql);

        switch ($month){
            case 1:
                $tNumber = $res[0]['farvardinTotal'];
                break;
            case 2:
                $tNumber = $res[0]['ordibeheshtTotal'];
                break;
            case 3:
                $tNumber = $res[0]['khordadTotal'];
                break;
            case 4:
                $tNumber = $res[0]['tirTotal'];
                break;
            case 5:
                $tNumber = $res[0]['mordadTotal'];
                break;
            case 6:
                $tNumber = $res[0]['shahrivarTotal'];
                break;
            case 7:
                $tNumber = $res[0]['mehrTotal'];
                break;
            case 8:
                $tNumber = $res[0]['abanTotal'];
                break;
            case 9:
                $tNumber = $res[0]['azarTotal'];
                break;
            case 10:
                $tNumber = $res[0]['deyTotal'];
                break;
            case 11:
                $tNumber = $res[0]['bahmanTotal'];
                break;
            case 12:
                $tNumber = $res[0]['esfandTotal'];
                break;
        }

        $idd = (strlen(trim($hcode)) == 0 ? 0 : $res[0]['RowID']);
        $tNumber = (intval($idd) > 0 ? $tNumber : 0);
        return array($idd,$tNumber);
    }

    //******************** اصلاحیه بودجه ********************

    public function getAmendmentBudgetList($year,$component,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('amendmentBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(intval($year) > 0){
            $w[] = '`bid`='.$year.' ';
        }
        if(intval($component) > 0){
            $w[] = '`bcdid`='.$component.' ';
        }
        if (intval($_SESSION['userid']) !== 1){
            $w[] = '`lastReceiver`='.$_SESSION['userid'].' ';
        }
		//$w[]='budget_amendment.number <> `budget_amendment`.currentNumber';
        $sql = "SELECT `budget_amendment`.*,`year` FROM `budget_amendment` INNER JOIN `budget` ON (`budget_amendment`.`bid`=`budget`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
		//$//ut->fileRecorder($sql);
        $listCount = count($res);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            $query = "SELECT * FROM `budget_components_details` WHERE `RowID`={$res[$y]['bcdid']}";
            $rst = $db->ArrayQuery($query);
            switch ($res[$y]['month']){
                case 1:
                    $tNumber = $rst[0]['farvardinTotal'];
                    $monthtxt = 'فروردین';
                    break;
                case 2:
                    $tNumber = $rst[0]['ordibeheshtTotal'];
                    $monthtxt = 'اردیبهشت';
                    break;
                case 3:
                    $tNumber = $rst[0]['khordadTotal'];
                    $monthtxt = 'خرداد';
                    break;
                case 4:
                    $tNumber = $rst[0]['tirTotal'];
                    $monthtxt = 'تیر';
                    break;
                case 5:
                    $tNumber = $rst[0]['mordadTotal'];
                    $monthtxt = 'مرداد';
                    break;
                case 6:
                    $tNumber = $rst[0]['shahrivarTotal'];
                    $monthtxt = 'شهریور';
                    break;
                case 7:
                    $tNumber = $rst[0]['mehrTotal'];
                    $monthtxt = 'مهر';
                    break;
                case 8:
                    $tNumber = $rst[0]['abanTotal'];
                    $monthtxt = 'آبان';
                    break;
                case 9:
                    $tNumber = $rst[0]['azarTotal'];
                    $monthtxt = 'آذر';
                    break;
                case 10:
                    $tNumber = $rst[0]['deyTotal'];
                    $monthtxt = 'دی';
                    break;
                case 11:
                    $tNumber = $rst[0]['bahmanTotal'];
                    $monthtxt = 'بهمن';
                    break;
                case 12:
                    $tNumber = $rst[0]['esfandTotal'];
                    $monthtxt = 'اسفند';
                    break;
            }
            $bgcolor="table-danger"; 
            if($res[$y]['planningTick']==1){
                $bgcolor="table-primary";
            }
            if($res[$y]['finalTick']==1){
               $bgcolor="table-success"; 
            }
                
            $finalRes[$y]['bgColor'] = $bgcolor;//($res[$y]['finalTick'] == 0 ? 'table-danger' : 'table-success');
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['year'] = $res[$y]['year'];
            $finalRes[$y]['gCode'] = $rst[0]['gCode'];
            $finalRes[$y]['gName'] = $rst[0]['gName'];
            $finalRes[$y]['createDate'] = $ut->greg_to_jal($res[$y]['createDate']);
            $finalRes[$y]['monthtxt'] = $monthtxt;
            $finalRes[$y]['number'] = $res[$y]['number'];
            $finalRes[$y]['currentNumber'] = $res[$y]['currentNumber'];
            $finalRes[$y]['DifferenceNumber'] = (intval($res[$y]['DifferenceNumber']) > 0 ? $res[$y]['DifferenceNumber'].' کاهش' : abs($res[$y]['DifferenceNumber']).' افزایش');
            $finalRes[$y]['tNumber'] = $tNumber;
            $finalRes[$y]['description'] = $res[$y]['description'];
        }
        return $finalRes;
    }

    public function getAmendmentBudgetListCountRows($year,$component){
        $db = new DBi();
        $w = array();
        if(intval($year) > 0){
            $w[] = '`bid`='.$year.' ';
        }
        if(intval($component) > 0){
            $w[] = '`bcdid`='.$component.' ';
        }
        if (intval($_SESSION['userid']) !== 1){
            $w[] = '`lastReceiver`='.$_SESSION['userid'].' ';
        }

        $sql = "SELECT `RowID` FROM `budget_amendment`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function amendmentBudgetInfo($abid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `budget_amendment` WHERE `RowID`=".$abid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("abid"=>$abid,"bcdid"=>$res[0]['bcdid'],"createDate"=>$ut->greg_to_jal($res[0]['createDate']),"month"=>$res[0]['month'],"number"=>$res[0]['number'],"description"=>$res[0]['description'],"ascORdesc"=>$res[0]['ascORdesc']);
            return $res;
        }else{
            return false;
        }
    }

    public function checkValidationDate(){
        $ut = new Utility();
        $currentDate = date('Y-m-d');
        $currentDate = $ut->greg_to_jal($currentDate);
        $currentDay = (date('d', strtotime($currentDate)));
        $currentMonth = (date('m', strtotime($currentDate)));

        if (intval($currentMonth) > 10){
            $res = "در این ماه امکان ثبت اصلاحیه وجود ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }
        if (intval($currentMonth) == 1 && intval($currentDay) < 25){
            $res = "بازه زمانی برای ثبت اصلاحیه مجاز نیست !";
            $out = "false";
            response($res,$out);
            exit;
        }
        if (intval($currentDay) > 5 && intval($currentDay) < 22){
            $res = "بازه زمانی برای ثبت اصلاحیه مجاز نیست !";
            $out = "false";
            response($res,$out);
            exit;
        }else{
            return true;
        }
    }

    public function getValidationMonth($bDate){
        $ut = new Utility();
        $bDate = $ut->jal_to_greg($bDate);
        $bDate = $ut->greg_to_jal($bDate);

        $months = array('فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند');
        $currentDay = (date('d', strtotime($bDate)));
        $currentMonth = (date('m', strtotime($bDate)));

        $res = array();
        $m = array();
        if (intval($currentDay) <= 5 && intval($currentDay) >= 1){
            $currentMonth += 2;
            while (intval($currentMonth) <= 12){
                $m[] = $currentMonth;
                $currentMonth++;
            }
        }else{
            $currentMonth += 3;
            while (intval($currentMonth) <= 12){
                $m[] = $currentMonth;
                $currentMonth++;
            }
        }
        $cnt = count($m);
        for ($i=0;$i<$cnt;$i++){
            $res[$i]['RowID'] = $m[$i];
            $res[$i]['Name'] = $months[$m[$i]-1];
        }
        return $res;
    }

public function historyBudgetAmendmentDetailsHtm($components){
    $db = new DBi();
    $ut=new Utility();
    $b_amendment_qry="select * from budget_amendment where bcdid IN ({$components})";
    $res_b_amendment = $db->ArrayQuery($b_amendment_qry);
   // //$//ut->fileRecorder('historyBudgetAmendmentDetailsHtm'.print_r($res_b_amendment,true));

    $sql= "SELECT 
					bcd.`RowID`,
						bcd.`gName`,
							bcd.`HCode`,
								bcd.`farvardinTotal`,
									bcd.`ordibeheshtTotal`,
										bcd.`khordadTotal`,
											bcd.`tirTotal`,
												bcd.`mordadTotal`,
													bcd.`shahrivarTotal`,
														bcd.`mehrTotal`,
															bcd.`abanTotal`,
																bcd.`azarTotal`,
																	bcd.`deyTotal`,
																		bcd.`bahmanTotal`,
																			bcd.`esfandTotal`,
																				bcd.`amendmentHistoryJson`

				FROM `budget_components_details` as bcd 
					WHERE bcd.`RowID` 
						IN ({$components}) ";
		$res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="budgetAmendmentDetailsHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info" scop="row">';
        $htm .= "<td style='display: none;'>&nbsp;</td>";
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 3%;">ردیف</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 19%;">نام محصول</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">کد محصول</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">فروردین</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">اردیبهشت</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">خرداد</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">تیر</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">مرداد</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">شهریور</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">مهر</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">آبان</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">آذر</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">دی</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">بهمن</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">اسفند</td></tr>';
        $counter=1;
		
        foreach($res as $res_index=>$componentsArray){
			 $amendArray=[];
            if(!empty($componentsArray['amendmentHistoryJson'])){
               
                $amendmentarray=json_decode($componentsArray['amendmentHistoryJson'],true);
                foreach($amendmentarray as $amendmentkey=>$amendmentValue){
					$amendArray=[];
                    $amendmentHtml="";
                    $littleHtml="";
                    $current_number=[];
                    
                    foreach($amendmentValue as $amend_key=>$amendArray){
						
                        
                        foreach($amendArray as $k=>$value){
                            switch($k){
								case "currentNumber":
									$custom_key="مقدار قبلی";
								break;
								case "amendmentNumber":
								$custom_key="مقدار تغییر یافته";
								break;
								case "amendmentDate":
								$custom_key="تاریخ تغییر";
								break;
								case "user":
								$custom_key="کاربر تغییر دهنده";
								break;
							}
							if($k=='amendmentDate'){
                                $amendDate=$ut->greg_to_jal($value);
                                $value=$amendDate;
                            }
							if($k=='user'){
                               
                                $value=$this->getUserInfo($value);
                            }
							
							if($k=='currentNumber'){
                                $current_number[]=$value;
                                
                            }
                            if($k=='amendmentNumber'){
                                $littleHtml.='<span style="margin-bottom:2px;text-align: center;background-color: skyblue;width: 100%;display: inline-block;border-radius: 5px;">'.$value.'</span>';
                            }
                            $amendmentHtml.='<tr>
												<td style="width:30%">
													<input type="text" disabled value="'.$custom_key.'"/>
												</td>
												<td>
													<input style="width:auto" disabled type="text" value="'.$value.'"/>
												</td>
											</tr>';
                        }
                        $amendArray=[];
                        $amendArray[$amendmentkey]['amendmentHtml']=$amendmentHtml;
                        $amendArray[$amendmentkey]['littleHtml']='<span style="margin-bottom:2px;text-align: center;background-color: skyblue;width: 100%;display: inline-block;border-radius: 5px;">'.$current_number[0].'</span>'.$littleHtml;
                    } 
                } 
            }
			
			$amendArray['farvardinTotal']['number']=$componentsArray['farvardinTotal'];
			$amendArray['ordibeheshtTotal']['number']=$componentsArray['ordibeheshtTotal'];
			$amendArray['khordadTotal']['number']=$componentsArray['khordadTotal'];
			$amendArray['tirTotal']['number']=$componentsArray['tirTotal'];
			$amendArray['mordadTotal']['number']=$componentsArray['mordadTotal'];
			$amendArray['shahrivarTotal']['number']=$componentsArray['shahrivarTotal'];
			$amendArray['mehrTotal']['number']=$componentsArray['mehrTotal'];
			$amendArray['abanTotal']['number']=$componentsArray['abanTotal'];
			$amendArray['azarTotal']['number']=$componentsArray['azarTotal'];
			$amendArray['deyTotal']['number']=$componentsArray['deyTotal'];
			$amendArray['bahmanTotal']['number']=$componentsArray['bahmanTotal'];
			$amendArray['esfandTotal']['number']=$componentsArray['esfandTotal'];
            $htm.="<tr>
                <td>".$counter."</td>
                <td>".$componentsArray['gName']."</td>
                <td>".$componentsArray['HCode']."</td>
                <td>";
                    $amendArray['farvardinTotal']['littleHtml']? $htm.=$amendArray['farvardinTotal']['littleHtml'].
					'<button id="far_amend_det_'.$counter.'" class="btn btn-success" style="width:100%;padding:0" onclick="showDetailes(\'box_detailes_'.$counter.'\')">جزییات</button>'.
					"<div id='box_detailes_".$counter."' style='position: absolute;background: gray;width:auto;display:none;z-index:".($counter+100)."'>
						<table style='background:gray'>".$amendArray['farvardinTotal']['amendmentHtml']."</table></div>":$htm.=$amendArray['farvardinTotal']['number'];
                    $htm.="</td>
                <td>";
                    $amendArray['ordibeheshtTotal']['littleHtml']? $htm.=$amendArray['ordibeheshtTotal']['littleHtml'].
					'<button id="ordi_amend_det_'.$counter.'" class="btn btn-success" style="width:100%;padding:0" onclick="showDetailes(\'box_detailes_'.$counter.'\')">جزییات</button>'.
					"<div id='box_detailes_".$counter."' style='position: absolute;background: gray;width:auto;display:none;z-index:".($counter+100)."'>
						<table style='background:gray'>".$amendArray['ordibeheshtTotal']['amendmentHtml']."</table></div>":$htm.=$amendArray['ordibeheshtTotal']['number'];
                    $htm.="</td>
                <td>";
                    $amendArray['khordadTotal']['littleHtml']? $htm.=$amendArray['khordadTotal']['littleHtml'].
					'<button id="khor_amend_det_'.$counter.'" class="btn btn-success" style="width:100%;padding:0" onclick="showDetailes(\'box_detailes_'.$counter.'\')">جزییات</button>'.
					"<div id='box_detailes_".$counter."' style='position: absolute;background: gray;width:auto;display:none;z-index:".($counter+100)."'>
						<table style='background:gray'>".$amendArray['khordadTotal']['amendmentHtml']."</table></div>":$htm.=$amendArray['khordadTotal']['number'];
                    $htm.="</td>
                <td style='position:relative'>";
                    $amendArray['tirTotal']['littleHtml']? $htm.=$amendArray['tirTotal']['littleHtml'].
					'<button id="tir_amend_det_'.$counter.'" class="btn btn-success" style="width:100%;padding:0" onclick="showDetailes(\'box_detailes_'.$counter.'\')">جزییات</button>'.
					"<div id='box_detailes_".$counter."' style='position: absolute;background: gray;width:auto;display:none;z-index:".($counter+100)."'>
						<table style='background:gray'>".$amendArray['tirTotal']['amendmentHtml']."</table></div>":$htm.=$amendArray['tirTotal']['number'];
                    $htm.="</td>
                <td>";
                    $amendArray['mordadTotal']['littleHtml']? $htm.=$amendArray['mordadTotal']['littleHtml'].'<button id="mor_amend_det_'.$counter.'" class="btn btn-success" style="width:100%;padding:0" onclick="showDetailes(\'box_detailes_'.$counter.'\')">جزییات</button>'.
					"<div id='box_detailes_".$counter."' style='position: absolute;background: gray;width:auto;display:none;z-index:".($counter+100)."'>
						<table style='background:gray'>".$amendArray['mordadTotal']['amendmentHtml']."</table></div>":$htm.$amendArray['mordadTotal']['number'];
                    $htm.="</td>
                <td>";
                    $amendArray['shahrivarTotal']['littleHtml']? $htm.=$amendArray['shahrivarTotal']['littleHtml'].'<button id="shah_amend_det_'.$counter.'" class="btn btn-success" style="width:100%;padding:0" onclick="showDetailes(\'box_detailes_'.$counter.'\')">جزییات</button>'.
					"<div id='box_detailes_".$counter."' style='position: absolute;background: gray;width:auto;display:none;z-index:".($counter+100)."'>
						<table style='background:gray'>".$amendArray['shahrivarTotal']['amendmentHtml']."</table></div>":$htm.=$amendArray['shahrivarTotal']['number'];
                    $htm.="</td>
                <td>";
                    $amendArray['mehrTotal']['littleHtml']? $htm.=$amendArray['mehrTotal']['littleHtml'].'<button id="mehr_amend_det_'.$counter.'" class="btn btn-success" style="width:100%;padding:0" onclick="showDetailes(\'box_detailes_'.$counter.'\')">جزییات</button>'.
					"<div id='box_detailes_".$counter."' style='position: absolute;background: gray;width:auto;display:none;z-index:".($counter+100)."'>
						<table style='background:gray'>".$amendArray['mehrTotal']['amendmentHtml']."</table></div>":$htm.=$amendArray['mehrTotal']['number'];
                    $htm.="</td>
                <td>";
                    $amendArray['abanTotal']['littleHtml']? $htm.=$amendArray['abanTotal']['littleHtml'].
					'<button id="aban_amend_det_'.$counter.'" class="btn btn-success" style="width:100%;padding:0" onclick="showDetailes(\'box_detailes_'.$counter.'\')">جزییات</button>'.
					"<div id='box_detailes_".$counter."' style='position: absolute;background: gray;width:auto;display:none;z-index:".($counter+100)."'>
						<table style='background:gray'>".$amendArray['abanTotal']['amendmentHtml']."</table></div>":$htm.=$amendArray['abanTotal']['number'];
                    $htm.="</td>
                <td>";
                    $amendArray['azarTotal']['littleHtml']? $htm.=$amendArray['azarTotal']['littleHtml'].
					'<button id="azar_amend_det_'.$counter.'" class="btn btn-success" style="width:100%;padding:0" onclick="showDetailes(\'box_detailes_'.$counter.'\')">جزییات</button>'.
					"<div id='box_detailes_".$counter."' style='position: absolute;background: gray;width:auto;display:none;z-index:".($counter+100)."'>
						<table style='background:gray'>".$amendArray['azarTotal']['amendmentHtml']."</table></div>":$htm.=$amendArray['azarTotal']['number'];
                    $htm.="</td>
                <td>";
                    $amendArray['deyTotal']['littleHtml']? $htm.=$amendArray['deyTotal']['littleHtml'].
					'<button id="dey_amend_det_'.$counter.'" class="btn btn-success" style="width:100%;padding:0" onclick="showDetailes(\'box_detailes_'.$counter.'\')">جزییات</button>'.
					"<div id='box_detailes_".$counter."' style='position: absolute;background: gray;width:auto;display:none;z-index:".($counter+100)."'>
						<table style='background:gray'>".$amendArray['deyTotal']['amendmentHtml']."</table></div>":$htm.=$amendArray['deyTotal']['number'];
                    $htm.="</td>
                <td>";
                    $amendArray['bahmanTotal']['littleHtml']? $htm.=$amendArray['bahmanTotal']['littleHtml'].
					'<button id="bah_amend_det_'.$counter.'" class="btn btn-success" style="width:100%;padding:0" onclick="showDetailes(\'box_detailes_'.$counter.'\')">جزییات</button>'.
					"<div id='box_detailes_".$counter."' style='position: absolute;background: gray;width:auto;display:none;z-index:".($counter+100)."'>
						<table style='background:gray'>".$amendArray['bahmanTotal']['amendmentHtml']."</table></div>":$htm.=$amendArray['bahmanTotal']['number'];
                    $htm.="</td>
                <td>";
                    $amendArray['esfandTotal']['littleHtml']? $htm.=$amendArray['esfandTotal']['littleHtml'].'<button id="es_amend_det_'.$counter.'" class="btn btn-success" style="width:100%;padding:0" onclick="showDetailes(\'box_detailes_'.$counter.'\')">جزییات</button>'.
					"<div id='box_detailes_".$counter."' style='position: absolute;background: gray;width:auto;display:none;z-index:".($counter+100)."'>
						<table style='background:gray'>".$amendArray['esfandTotal']['amendmentHtml']."</table></div>":$htm.=$amendArray['esfandTotal']['number'];
                    $htm.="</td></tr>";
     
            $counter++;
        }
        //$//ut->fileRecorder('testtttttamendment:'.print_r($amendmentsArraydeailes,true));
        $htm .= '<tbody>';
        return $htm;
}

public function getUserInfo($userID){
	$db=new DBi();
	$sql="SELECT * FROM users WHERE RowID={$userID}";
	$res = $db->ArrayQuery($sql);
	return $res[0]['fname']." ".$res[0]['lname'];
}
//*******************************************************
public function displayBudgetAmendmentDetailsHtm($month,$components){
    $db = new DBi();
    $ut=new Utility();
    $b_amendment_qry="select * from budget_amendment where bcdid IN ({$components})";
    $res_b_amendment = $db->ArrayQuery($b_amendment_qry);
    $sql= "SELECT 
					bcd.`RowID`,
						bcd.`gName`,
							bcd.`HCode`,
								bcd.`farvardinTotal`,
									bcd.`ordibeheshtTotal`,
										bcd.`khordadTotal`,
											bcd.`tirTotal`,
												bcd.`mordadTotal`,
													bcd.`shahrivarTotal`,
														bcd.`mehrTotal`,
															bcd.`abanTotal`,
																bcd.`azarTotal`,
																	bcd.`deyTotal`,
																		bcd.`bahmanTotal`,
																			bcd.`esfandTotal` 

				FROM `budget_components_details` as bcd 
					WHERE bcd.`RowID` 
						IN ({$components}) ";
        
		$res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="budgetAmendmentDetailsHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info" scop="row">';
        $htm .= "<td style='display: none;'>&nbsp;</td>";
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 3%;">ردیف</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">نام محصول</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">کد محصول</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">فروردین</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">اردیبهشت</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">خرداد</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">تیر</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">مرداد</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">شهریور</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">مهر</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">آبان</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">آذر</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">دی</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">بهمن</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">اسفند</td>';
        /*
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">مقدار اصلاح شود به</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';*/
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $month_number_array = [];
            foreach($res_b_amendment as $key=>$value){
			    switch(intval($value['month']))
                {
				    case 1:
					    $month='farvardin';
					    break;
				    case 2:
					    $month='ordibehesht';
					    break;
				    case 3:
					    $month='khordad';
					    break;
				    case 4:
					    $month='tir';
					    break;
				    case 5:
					    $month='mordad';
					    break;
				    case 6:
					    $month='shahrivar';
					    break;
				    case 7:
					    $month='mehr';
					    break;
				    case 8:
					    $month='aban';
					    break;
				    case 9:
					    $month='azar';
					    break;
				    case 10:
					    $month='dey';
					    break;
				    case 11:
					    $month='bahman';
					    break;
				    case 12:
					    $month='esfand';
					    break;
			    }
                if($res[$i]['RowID']==$value['bcdid'])
                {
                    $month_number_array[$month]['number']=$value['number'];
                    $month_number_array[$month]['currentNumber']=$value['currentNumber'];
                    $month_number_array[$month]['RowID']=$value['RowID'];
                }

            }
            $htm .= '<tr class="table-secondary" style="border:2px solid gray;height:auto">';
            $htm .= "<td style='display: none;' ><input type='checkbox' rid='".$iterator."' checked disabled>&nbsp;</td>";
            $htm .= '<td style="text-align:center;font-family: dubai-Regular;padding: 10px;"><span>'.$iterator.'</span></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['gName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['HCode'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input id="f_current_total_'.$iterator.'" style="width:50px" type="text"  value="'.intval($month_number_array['farvardin']['currentNumber']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="f_edited_total_'.$iterator.'" 
							style="width:50px" 
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['farvardin']['number'].'"
											amendment_rowId="'.$month_number_array['farvardin']['RowID'].'"
												month="1"
													old_amendment_number="'.$month_number_array['farvardin']['number'].'"
														onblur="selectEditedAmendment(this)" >
                    </td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input id="o_current_total_'.$iterator.'" style="width:50px" type="text"  value="'.intval($month_number_array['ordibehesht']['currentNumber']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="o_edited_total_'.$iterator.'" 
							style="width:50px" type="text" bcdid="'.intval($res[$i]['RowID']).'" 
								value="'.$month_number_array['ordibehesht']['number'].'"
									amendment_rowId="'.$month_number_array['ordibehesht']['RowID'].'"
										month="2"
											old_amendment_number="'.$month_number_array['ordibehesht']['number'].'"
												onblur="selectEditedAmendment(this)">
                    </td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input id="kh_current_total_'.$iterator.'" style="width:50px" type="text" value="'.intval($month_number_array['khordad']['currentNumber']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="kh_edited_total_'.$iterator.'" 
							style="width:50px" 
								bcdid="'.intval($res[$i]['RowID']).'" 
									type="text" 
										value="'.$month_number_array['khordad']['number'].'"
											amendment_rowId="'.$month_number_array['khordad']['RowID'].'"
												month="3"
													old_amendment_number="'.$month_number_array['khordad']['number'].'"
														onblur="selectEditedAmendment(this)">
                    </td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="t_current_total_'.$iterator.'" type="text" value="'.intval($month_number_array['tir']['currentNumber']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="t_edited_total_'.$iterator.'" 
							style="width:50px" 
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['tir']['number'].'"
											amendment_rowId="'.$month_number_array['tir']['RowID'].'"
												month="4"
													old_amendment_number="'.$month_number_array['tir']['number'].'"
														onblur="selectEditedAmendment(this)">
                    </td>';
            $htm .= '<td style="text-align: center;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="mo_current_total_'.$iterator.'" type="text" value="'.intval($month_number_array['mordad']['currentNumber']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="mo_edited_total_'.$iterator.'" 
							style="width:50px"
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['mordad']['number'].'" 
											amendment_rowId="'.$month_number_array['mordad']['RowID'].'"
												month="5"
													old_amendment_number="'.$month_number_array['mordad']['number'].'"
														onblur="selectEditedAmendment(this)">
                    </td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="sh_current_total_'.$iterator.'" type="text" value="'.intval($month_number_array['shahrivar']['currentNumber']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="sh_edited_total_'.$iterator.'" 
							style="width:50px" 
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['shahrivar']['number'].'"
											amendment_rowId="'.$month_number_array['shahrivar']['RowID'].'"
												month="6"
													old_amendment_number="'.$month_number_array['shahrivar']['number'].'"
														onblur="selectEditedAmendment(this)">
                    </td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="me_current_total_'.$iterator.'" type="text" value="'.intval($month_number_array['mehr']['currentNumber']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="me_edited_total_'.$iterator.'" 
							style="width:50px" 
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['mehr']['number'].'"
											amendment_rowId="'.$month_number_array['mehr']['RowID'].'"
												month="7"
													old_amendment_number="'.$month_number_array['mehr']['number'].'"
														onblur="selectEditedAmendment(this)">	
                    </td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="ab_current_total_'.$iterator.'" type="text" value="'.intval($month_number_array['aban']['currentNumber']).'"><hr>
                        <span>تغییر یافته</span>
							<input id="ab_edited_total_'.$iterator.'" 
								style="width:50px" 
									type="text"bcdid="'.intval($res[$i]['RowID']).'"  
										value="'.$month_number_array['aban']['number'].'"
											amendment_rowId="'.$month_number_array['aban']['RowID'].'"
												month="8"
													old_amendment_number="'.$month_number_array['aban']['number'].'"
														onblur="selectEditedAmendment(this)">
                    </td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="az_current_total_'.$iterator.'" type="text" value="'.intval($month_number_array['azar']['currentNumber']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="az_edited_total_'.$iterator.'" 
							style="width:50px" type="text" 
								bcdid="'.intval($res[$i]['RowID']).'" 
									value="'.$month_number_array['azar']['number'].'" 
										amendment_rowId="'.$month_number_array['azar']['RowID'].'"
											month="9"
												old_amendment_number="'.$month_number_array['azar']['number'].'"
													onblur="selectEditedAmendment(this)">
                    </td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="d_current_total_'.$iterator.'" type="text" value="'.intval($month_number_array['dey']['currentNumber']).'"><hr>
                        <span>تغییر یافته</span>
							<input id="d_edited_total_'.$iterator.'" 
								style="width:50px" 
									type="text" 
										bcdid="'.intval($res[$i]['RowID']).'"
											value="'.$month_number_array['dey']['number'].'" 
												amendment_rowId="'.$month_number_array['dey']['RowID'].'"
													month="10"
														old_amendment_number="'.$month_number_array['dey']['number'].'"
															onblur="selectEditedAmendment(this)">
                    </td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="b_current_total_'.$iterator.'" type="text" value="'.intval($month_number_array['bahman']['currentNumber']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="b_edited_total_'.$iterator.'"
							style="width:50px" 
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['bahman']['number'].'" 
											amendment_rowId="'.$month_number_array['bahman']['RowID'].'"
												month="11"
													old_amendment_number="'.$month_number_array['bahman']['number'].'"
														onblur="selectEditedAmendment(this)">
                    </td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input  style="width:50px" id="es_current_total_'.$iterator.'" type="text" value="'.intval($month_number_array['esfand']['currentNumber']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="es_edited_total_'.$iterator.'" 
							style="width:50px" 
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['esfand']['number'].'"
											amendment_rowId="'.$month_number_array['esfand']['RowID'].'"
												month="12"
													old_amendment_number="'.$month_number_array['esfand']['number'].'"
														onblur="selectEditedAmendment(this)">
                    </td>';
                /*$htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" type="text" value="'.intval($res[$i]['farvardinTotal']).'"><hr>
                        <span>تغییر یافته</span><input style="width:50px" type="text" value="'.intval($res[$i]['farvardinTotal']).'">
                    </td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" type="text" value="'.intval($res[$i]['farvardinTotal']).'"><hr>
                        <span>تغییر یافته</span><input style="width:50px" type="text" value="'.intval($res[$i]['farvardinTotal']).'">
                    </td>';
                
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['ordibeheshtTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['khordadTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['tirTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['mordadTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['shahrivarTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['mehrTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['abanTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['azarTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['deyTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['bahmanTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['esfandTotal']).'</td>';
           /* $htm .= '<td style="text-align: center;"><input type="text" class="form-control"  id="budgetAmendmentNumber-'.$iterator.'" /><input type="hidden" id="bcdid-'.$iterator.'-Hidden" value="'.$res[$i]['RowID'].'" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control"  id="budgetAmendmentDesc-'.$iterator.'" /></td>';*/

            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

//*******************************************************
public function budgetAmendmentDetailsHtm($month,$components){
    $db = new DBi();
    $ut=new Utility();
    $b_amendment_qry="select * from budget_amendment where bcdid IN ({$components})";
    $res_b_amendment = $db->ArrayQuery($b_amendment_qry);
    $sql= "SELECT 
					bcd.`RowID`,
						bcd.`gName`,
							bcd.`HCode`,
								bcd.`farvardinTotal`,
									bcd.`ordibeheshtTotal`,
										bcd.`khordadTotal`,
											bcd.`tirTotal`,
												bcd.`mordadTotal`,
													bcd.`shahrivarTotal`,
														bcd.`mehrTotal`,
															bcd.`abanTotal`,
																bcd.`azarTotal`,
																	bcd.`deyTotal`,
																		bcd.`bahmanTotal`,
																			bcd.`esfandTotal` 

				FROM `budget_components_details` as bcd 
					WHERE bcd.`RowID` 
						IN ({$components}) ";
        
		$res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="budgetAmendmentDetailsHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info" scop="row">';
        $htm .= "<td style='display: none;'>&nbsp;</td>";
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 3%;">ردیف</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">نام محصول</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">کد محصول</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">فروردین</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">اردیبهشت</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">خرداد</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">تیر</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">مرداد</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">شهریور</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">مهر</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">آبان</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">آذر</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">دی</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">بهمن</td>';
        $htm .= '<td scop="col" style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">اسفند</td>';
        /*
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">مقدار اصلاح شود به</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';*/
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $month_number_array = [];
            foreach($res_b_amendment as $key=>$value){
			    switch(intval($value['month']))
                {
				    case 1:
					    $month='farvardin';
					    break;
				    case 2:
					    $month='ordibehesht';
					    break;
				    case 3:
					    $month='khordad';
					    break;
				    case 4:
					    $month='tir';
					    break;
				    case 5:
					    $month='mordad';
					    break;
				    case 6:
					    $month='shahrivar';
					    break;
				    case 7:
					    $month='mehr';
					    break;
				    case 8:
					    $month='aban';
					    break;
				    case 9:
					    $month='azar';
					    break;
				    case 10:
					    $month='dey';
					    break;
				    case 11:
					    $month='bahman';
					    break;
				    case 12:
					    $month='esfand';
					    break;
			    }
                if($res[$i]['RowID']==$value['bcdid'])
                {
                    $month_number_array[$month]['number']=$value['number'];
                    $month_number_array[$month]['RowID']=$value['RowID'];
                }

            }
            $htm .= '<tr class="table-secondary" style="border:2px solid gray;height:auto">';
            $htm .= "<td style='display: none;' ><input type='checkbox' rid='".$iterator."' checked disabled>&nbsp;</td>";
            $htm .= '<td style="text-align:center;font-family: dubai-Regular;padding: 10px;"><span>'.$iterator.'</span></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['gName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['HCode'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input id="f_current_total_'.$iterator.'" style="width:50px" type="text"  value="'.intval($res[$i]['farvardinTotal']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="f_edited_total_'.$iterator.'" 
							style="width:50px" 
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['farvardin']['number'].'"
											amendment_rowId="'.$month_number_array['farvardin']['RowID'].'"
												month="1"
													old_amendment_number="'.$month_number_array['farvardin']['number'].'"
														onblur="selectEditedAmendment(this)" onchange="saveAmendmentBudget(this)">
                    </td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input id="o_current_total_'.$iterator.'" style="width:50px" type="text"  value="'.intval($res[$i]['ordibeheshtTotal']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="o_edited_total_'.$iterator.'" 
							style="width:50px" type="text" bcdid="'.intval($res[$i]['RowID']).'" 
								value="'.$month_number_array['ordibehesht']['number'].'"
									amendment_rowId="'.$month_number_array['ordibehesht']['RowID'].'"
										month="2"
											old_amendment_number="'.$month_number_array['ordibehesht']['number'].'"
												onblur="selectEditedAmendment(this)" onchange="saveAmendmentBudget(this)">
                    </td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input id="kh_current_total_'.$iterator.'" style="width:50px" type="text" value="'.intval($res[$i]['khordadTotal']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="kh_edited_total_'.$iterator.'" 
							style="width:50px" 
								bcdid="'.intval($res[$i]['RowID']).'" 
									type="text" 
										value="'.$month_number_array['khordad']['number'].'"
											amendment_rowId="'.$month_number_array['khordad']['RowID'].'"
												month="3"
													old_amendment_number="'.$month_number_array['khordad']['number'].'"
														onblur="selectEditedAmendment(this)" onchange="saveAmendmentBudget(this)">
                    </td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="t_current_total_'.$iterator.'" type="text" value="'.intval($res[$i]['tirTotal']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="t_edited_total_'.$iterator.'" 
							style="width:50px" 
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['tir']['number'].'"
											amendment_rowId="'.$month_number_array['tir']['RowID'].'"
												month="4"
													old_amendment_number="'.$month_number_array['tir']['number'].'"
														onblur="selectEditedAmendment(this)" onchange="saveAmendmentBudget(this)">
                    </td>';
            $htm .= '<td style="text-align: center;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="mo_current_total_'.$iterator.'" type="text" value="'.intval($res[$i]['mordadTotal']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="mo_edited_total_'.$iterator.'" 
							style="width:50px"
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['mordad']['number'].'" 
											amendment_rowId="'.$month_number_array['mordad']['RowID'].'"
												month="5"
													old_amendment_number="'.$month_number_array['mordad']['number'].'"
														onblur="selectEditedAmendment(this)" onchange="saveAmendmentBudget(this)">
                    </td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="sh_current_total_'.$iterator.'" type="text" value="'.intval($res[$i]['shahrivarTotal']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="sh_edited_total_'.$iterator.'" 
							style="width:50px" 
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['shahrivar']['number'].'"
											amendment_rowId="'.$month_number_array['shahrivar']['RowID'].'"
												month="6"
													old_amendment_number="'.$month_number_array['shahrivar']['number'].'"
														onblur="selectEditedAmendment(this)" onchange="saveAmendmentBudget(this)">
                    </td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="me_current_total_'.$iterator.'" type="text" value="'.intval($res[$i]['mehrTotal']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="me_edited_total_'.$iterator.'" 
							style="width:50px" 
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['mehr']['number'].'"
											amendment_rowId="'.$month_number_array['mehr']['RowID'].'"
												month="7"
													old_amendment_number="'.$month_number_array['mehr']['number'].'"
														onblur="selectEditedAmendment(this)" onchange="saveAmendmentBudget(this)">	
                    </td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="ab_current_total_'.$iterator.'" type="text" value="'.intval($res[$i]['abanTotal']).'"><hr>
                        <span>تغییر یافته</span>
							<input id="ab_edited_total_'.$iterator.'" 
								style="width:50px" 
									type="text"bcdid="'.intval($res[$i]['RowID']).'"  
										value="'.$month_number_array['aban']['number'].'"
											amendment_rowId="'.$month_number_array['aban']['RowID'].'"
												month="8"
													old_amendment_number="'.$month_number_array['aban']['number'].'"
														onblur="selectEditedAmendment(this)" onchange="saveAmendmentBudget(this)">
                    </td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="az_current_total_'.$iterator.'" type="text" value="'.intval($res[$i]['azarTotal']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="az_edited_total_'.$iterator.'" 
							style="width:50px" type="text" 
								bcdid="'.intval($res[$i]['RowID']).'" 
									value="'.$month_number_array['azar']['number'].'" 
										amendment_rowId="'.$month_number_array['azar']['RowID'].'"
											month="9"
												old_amendment_number="'.$month_number_array['azar']['number'].'"
													onblur="selectEditedAmendment(this)" onchange="saveAmendmentBudget(this)">
                    </td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="d_current_total_'.$iterator.'" type="text" value="'.intval($res[$i]['deyTotal']).'"><hr>
                        <span>تغییر یافته</span>
							<input id="d_edited_total_'.$iterator.'" 
								style="width:50px" 
									type="text" 
										bcdid="'.intval($res[$i]['RowID']).'"
											value="'.$month_number_array['dey']['number'].'" 
												amendment_rowId="'.$month_number_array['dey']['RowID'].'"
													month="10"
														old_amendment_number="'.$month_number_array['dey']['number'].'"
															onblur="selectEditedAmendment(this)" onchange="saveAmendmentBudget(this)">
                    </td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" id="b_current_total_'.$iterator.'" type="text" value="'.intval($res[$i]['bahmanTotal']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="b_edited_total_'.$iterator.'"
							style="width:50px" 
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['bahman']['number'].'" 
											amendment_rowId="'.$month_number_array['bahman']['RowID'].'"
												month="11"
													old_amendment_number="'.$month_number_array['bahman']['number'].'"
														onblur="selectEditedAmendment(this)" onchange="saveAmendmentBudget(this)">
                    </td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input  style="width:50px" id="es_current_total_'.$iterator.'" type="text" value="'.intval($res[$i]['esfandTotal']).'"><hr>
                        <span>تغییر یافته</span>
						<input id="es_edited_total_'.$iterator.'" 
							style="width:50px" 
								type="text" 
									bcdid="'.intval($res[$i]['RowID']).'" 
										value="'.$month_number_array['esfand']['number'].'"
											amendment_rowId="'.$month_number_array['esfand']['RowID'].'"
												month="12"
													old_amendment_number="'.$month_number_array['esfand']['number'].'"
														onblur="selectEditedAmendment(this)" onchange="saveAmendmentBudget(this)">
                    </td>';
                /*$htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" type="text" value="'.intval($res[$i]['farvardinTotal']).'"><hr>
                        <span>تغییر یافته</span><input style="width:50px" type="text" value="'.intval($res[$i]['farvardinTotal']).'">
                    </td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;width:40px">
                        <span>مقدار قبلی</span><input style="width:50px" type="text" value="'.intval($res[$i]['farvardinTotal']).'"><hr>
                        <span>تغییر یافته</span><input style="width:50px" type="text" value="'.intval($res[$i]['farvardinTotal']).'">
                    </td>';
                
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['ordibeheshtTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['khordadTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['tirTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['mordadTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['shahrivarTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['mehrTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['abanTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['azarTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['deyTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['bahmanTotal']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.intval($res[$i]['esfandTotal']).'</td>';
           /* $htm .= '<td style="text-align: center;"><input type="text" class="form-control"  id="budgetAmendmentNumber-'.$iterator.'" /><input type="hidden" id="bcdid-'.$iterator.'-Hidden" value="'.$res[$i]['RowID'].'" /></td>';
            $htm .= '<td style="text-align: center;"><input type="text" class="form-control"  id="budgetAmendmentDesc-'.$iterator.'" /></td>';*/

            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

/*    public function createAmendmentBudget($myJsonString,$aDate,$month){
        $acm = new acm();
        if(!$acm->hasAccess('amendmentBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        mysqli_autocommit($db->Getcon(),FALSE);
        $countJS = count($myJsonString);
        $flag = true;
        $jDate = $ut->jal_to_greg($aDate);
        $cDate = $ut->greg_to_jal($jDate);
        $cyear = intval(substr($cDate,0,4));

        $sqq = "SELECT `RowID` FROM `budget` WHERE `year`={$cyear}";
        $rst1 = $db->ArrayQuery($sqq);
        for($j=0;$j<$countJS;$j++) {
            if (intval($myJsonString[$j][0]) <= 0){
                continue;
            }
            $query = "SELECT `month` FROM `budget_amendment` WHERE `bid`={$rst1[0]['RowID']} AND `bcdid`={$myJsonString[$j][2]}";
            $rst = $db->ArrayQuery($query);
            if (count($rst) > 0) {
                $cnt = count($rst);
                for ($i=0;$i<$cnt;$i++) {
                    if ((intval($month) == intval($rst[$i]['month']))) {
                        $res = "برای یک یا چند محصول، در ماه انتخابی قبلا اصلاحیه زده شده است !";
                        $out = "false";
                        response($res, $out);
                        exit;
                    }
                }
            }
        }
        for($i=0;$i<$countJS;$i++){
            if (intval($myJsonString[$i][0]) <= 0){
                continue;
            }
            $sql = "INSERT INTO `budget_amendment` (`bid`,`bcdid`,`createDate`,`month`,`number`,`description`,`lastReceiver`) VALUES ({$rst1[0]['RowID']},{$myJsonString[$i][2]},'{$jDate}',{$month},{$myJsonString[$i][0]},'{$myJsonString[$i][1]}',{$_SESSION['userid']})";
            $res = $db->Query($sql);
            if(intval($res) <= 0){
                //$//ut->fileRecorder($sql);
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
*/
//**************************************************

	public function saveChangeAmendmentBudget($month,$number,$bcdid,$amendmentDate,$amendment_rowid)
	{
		$userid=$_SESSION['userid'];
		$db=new DBi();
		$ut=new Utility();
		$jDate = $ut->jal_to_greg($amendmentDate);
		$cDate = $ut->greg_to_jal($jDate);
		$cyear = intval(substr($cDate,0,4));
		$sqq = "SELECT `RowID` FROM `budget` WHERE `year`={$cyear}";
		$rst1 = $db->ArrayQuery($sqq);
		$bid=$rst1[0]['RowID'];
		$getAmendmentQry="SELECT bcdid,month  FROM budget_amendment where bid={$bid} AND bcdid={$bcdid} AND month={$month}";
		//error_log($getAmendmentQry);
		$getAmendment=$db->ArrayQuery($getAmendmentQry);
		if(count($getAmendment)>0){
			$amendQueryUpdate="UPDATE budget_amendment SET number={$number} WHERE RowID={$amendment_rowid}";
			$reult=$db->Query($amendQueryUpdate);
			$affectedRow=$db->AffectedRows();
			if($affectedRow>0){
				return "true";
			}
			else{
				return "false";
			}
		}
		else
		{
			$amendQueryInsert="INSERT INTO budget_amendment (`bid`,`bcdid`,`createDate`,`month`,`number`,`lastReceiver`)
								VALUES({$bid},{$bcdid},'{$jDate}',{$month},{$number},{$userid})";
			$insertReult=$db->Query($amendQueryInsert);
			$lastInsertId=$db->InsertrdID();
			if($lastInsertId>0){
				return $lastInsertId;
			}
			else{
				return -1;
			}
		} 
	}
  public function createAmendmentBudget($myJsonString,$aDate,$month){
    
        $acm = new acm();
        $db = new DBi();
        $ut = new Utility();
        if(!$acm->hasAccess('amendmentBudgetManage')){
            die("access denied");
            exit;
        }
        //$//ut->fileRecorder(print_r($myJsonString,true));
       // mysqli_autocommit($db->Getcon(),FALSE);
       
        $jDate = $ut->jal_to_greg($aDate);
        $cDate = $ut->greg_to_jal($jDate);
        $cyear = intval(substr($cDate,0,4));
        $sqq = "SELECT `RowID` FROM `budget` WHERE `year`={$cyear}";
        $rst1 = $db->ArrayQuery($sqq);
        $values="";
		$update_sql="";
        foreach($myJsonString as $k1=>$v1)
        {
			$query = "SELECT `month` FROM `budget_amendment` WHERE `bid`={$rst1[0]['RowID']} AND `bcdid`={$v1['bcdid']}";
            $rst = $db->ArrayQuery($query);
			//error_log(print_r($v1,true));
			if($v1['amendment_type']=='insert'){
				$values.="({$rst1[0]['RowID']},{$v1['bcdid']},'{$jDate}',{$v1['month']},{$v1['number']},'{$v1['desc']}',{$_SESSION['userid']}),";
			}
			if($v1['amendment_type']=='update'){
				$amendmentRowID=intval($v1['amendment_row_id']);
				$amendmentEditesNumber=intval($v1['number']);
				$update_sql="UPDATE `budget_amendment` SET `number`={$amendmentEditesNumber} where `RowID`={$amendmentRowID};";
				//$//ut->fileRecorder($update_sql);
				$updateResult=$db->Query($update_sql);
				if($updateResult==0){
					//$//ut->fileRecorder("error");
					return false;
				}
				else{
					
					$Flag=true;
				}
			}
	   }
        $values=rtrim($values,',');
		if($values){
		
			$insert_sql = "INSERT INTO `budget_amendment` (`bid`,`bcdid`,`createDate`,`month`,`number`,`description`,`lastReceiver`) VALUES" .$values;
			$res_insert = $db->Query($insert_sql);
		
        if(!$res_insert){ 
			//$//ut->fileRecorder("insert error");
            
           $Flag=false;
        }
		else{
			 $Flag=true;
			 //$//ut->fileRecorder("insert ok");
		}
		}
		return $Flag;
    }
    
//**************************************************
    public function editBudgetAmendmentHtm($abid){
        $db = new DBi();

        $query = "SELECT `number`,`description`,`gCode`,`gName` FROM `budget_amendment` INNER JOIN `budget_components_details` ON (`budget_amendment`.`bcdid`=`budget_components_details`.`RowID`) WHERE `budget_amendment`.`RowID`={$abid}";
        $rst = $db->ArrayQuery($query);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="editBudgetAmendmentHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">نام محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">کد محصول</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">مقدار اصلاحیه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 30%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $htm .= '<tr class="table-secondary">';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$rst[0]['gName'].'</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$rst[0]['gCode'].'</td>';
        $htm .= '<td style="text-align: center;"><input type="text" class="form-control"  value="'.$rst[0]['number'].'" id="editBudgetAmendmentNumber" /></td>';
        $htm .= '<td style="text-align: center;"><input type="text" class="form-control"  value="'.$rst[0]['description'].'" id="editBudgetAmendmentDesc" /></td>';
        $htm .= '</tr>';

        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function editAmendmentBudget($abid,$number,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('editCreateAmendmentBudget')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sqq = "SELECT `finalTick`,`number`,`description` FROM `budget_amendment` WHERE `RowID`={$abid}";
        $result = $db->ArrayQuery($sqq);

        if (intval($result[0]['finalTick']) == 1){
            $res = "این مورد تایید نهایی شده است و امکان ویرایش ندارد !";
            $out = "false";
            response($res, $out);
            exit;
        }
        if ((intval($number) == intval($result[0]['number'])) && (strlen(trim($desc)) == strlen(trim($result[0]['description'])))){
            $res = "شما هیچ تغییری اعمال ننموده اید !";
            $out = "false";
            response($res, $out);
            exit;
        }

        $sql = "UPDATE `budget_amendment` SET `number`={$number},`description`='{$desc}',`productionTick`=2,`planningTick`=2,`productionDescription`='',`planningDescription`='' WHERE `RowID`={$abid}";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function amendmentBudgetWorkflowHtm($abid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `budget_amendment_workflow`.*,`fname`,`lname` FROM `budget_amendment_workflow` INNER JOIN `users` ON (`budget_amendment_workflow`.`sender`=`users`.`RowID`) WHERE `abid`={$abid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="amendmentBudgetWorkflowHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">فرستنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">گیرنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زمان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$i]['receiver']}";
            $rst = $db->ArrayQuery($query);
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$rst[0]['fname'].' '.$rst[0]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createDate'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function SendAmendmentBudget($abid,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('amendmentBudgetManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $sqlu = "SELECT `finalTick` FROM `budget_amendment` WHERE `RowID`={$abid}";
        $resu = $db->ArrayQuery($sqlu);
        if (intval($resu[0]['finalTick']) == 1){
            $res = "این مورد تایید نهایی شده است و امکان ارجاع ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        $cartable = 'اصلاحیه بودجه فروش';

        $sql1 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=102";  // واحد برنامه ریزی
        $res1 = $db->ArrayQuery($sql1);

        $sqlp = "SELECT `phone` FROM `users` WHERE `RowID`={$res1[0]['user_id']}";
        $resp = $db->ArrayQuery($sqlp);

        $sql2 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=101";  // واحد تولید
        $res2 = $db->ArrayQuery($sql2);

        $sqlp1 = "SELECT `phone` FROM `users` WHERE `RowID`={$res2[0]['user_id']}";
        $resp1 = $db->ArrayQuery($sqlp1);

        if ($acm->hasAccess('editCreateAmendmentBudget')){   // معاونت بازرگانی بود
            $sql3 = "INSERT INTO `budget_amendment_workflow` (`sender`,`receiver`,`abid`,`createDate`,`createTime`,`description`) VALUES ({$_SESSION['userid']},{$res1[0]['user_id']},{$abid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

            $sql4 = "UPDATE `budget_amendment` SET `lastReceiver`={$res1[0]['user_id']} WHERE `RowID`={$abid}";
            $db->Query($sql4);

            $phone = $resp[0]['phone'];
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }elseif ($acm->hasAccess('planningTickBudget')){
            $sql3 = "INSERT INTO `budget_amendment_workflow` (`sender`,`receiver`,`abid`,`createDate`,`createTime`,`description`) VALUES ({$res1[0]['user_id']},20,{$abid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

/*            $sql4 = "UPDATE `budget_amendment` SET `lastReceiver`={$res2[0]['user_id']} WHERE `RowID`={$abid}";
            $db->Query($sql4);*/
            $sql4 = "UPDATE `budget_amendment` SET `lastReceiver`=20,`productionTick`=1 WHERE `RowID`={$abid}";
            $db->Query($sql4);

            $phone = $resp1[0]['phone'];
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }elseif ($acm->hasAccess('productionTickBudget')){
            $sql3 = "INSERT INTO `budget_amendment_workflow` (`sender`,`receiver`,`abid`,`createDate`,`createTime`,`description`) VALUES ({$res2[0]['user_id']},20,{$abid},'{$nowDate}','{$nowTime}','{$desc}')";
            $db->Query($sql3);

            $sql4 = "UPDATE `budget_amendment` SET `lastReceiver`=20 WHERE `RowID`={$abid}";
            $db->Query($sql4);

            $phone = '9153131176';
            $ut->sendAllBudgetElements($phone,$cartable);
            return true;
        }else{
            return false;
        }
    }

    public function amendmentBudgetComment($abid){
        $db = new DBi();
        $sql = "SELECT `productionTick`,`planningTick`,`productionDescription`,`planningDescription` FROM `budget_amendment` WHERE `RowID`=".$abid;
        $res = $db->ArrayQuery($sql);
        switch ($res[0]['productionTick']){
            case 0:
                $productionTick = 'عدم تایید';
                $bg = 'bg-danger-light';
                break;
            case 1:
                $productionTick = 'تایید شده';
                $res[0]['productionDescription'] = 'مورد تایید می باشد';
                $bg = 'bg-success-light';
                break;
            case 2:
                $productionTick = 'در حال بررسی';
                $res[0]['productionDescription'] = 'در حال بررسی می باشد';
                $bg = 'bg-warning-light';
                break;
        }
        switch ($res[0]['planningTick']){
            case 0:
                $planningTick = 'عدم تایید';
                $bg1 = 'bg-danger-light';
                break;
            case 1:
                $planningTick = 'تایید شده';
                $res[0]['planningDescription'] = 'مورد تایید می باشد';
                $bg1 = 'bg-success-light';
                break;
            case 2:
                $planningTick = 'در حال بررسی';
                $res[0]['planningDescription'] = 'در حال بررسی می باشد';
                $bg1 = 'bg-warning-light';
                break;
        }
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="amendmentBudgetCommentHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">نظر واحد برنامه ریزی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">وضعیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">نظر واحد تولید</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">وضعیت</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $htm .= '<tr class="table-secondary">';
        $htm .= '<td class="'.$bg1.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]['planningDescription'].'</td>';
        $htm .= '<td class="'.$bg1.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$planningTick.'</td>';
        $htm .= '<td class="'.$bg.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]['productionDescription'].'</td>';
        $htm .= '<td class="'.$bg.'" style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$productionTick.'</td>';
        $htm .= '</tr>';
        $htm .= '</tbody>';
        $htm .= '</table>';

        return $htm;
    }

    public function recordAmendmentBudgetComment($abid,$desc,$radioValue){
        $acm = new acm();
        $db = new DBi();

        $sqq = "SELECT `productionTick`,`planningTick` FROM `budget_amendment` WHERE `RowID`={$abid}";
        $rsq = $db->ArrayQuery($sqq);
        if ($acm->hasAccess('productionTickBudget') && intval($rsq[0]['productionTick']) == 1){
            $res = "شما قبلا این مورد را تایید نموده اید !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif ($acm->hasAccess('productionTickBudget')){
            $m = '`productionTick`='.$radioValue;
            $n = ',`productionDescription`="'.$desc.'"';
        }

        if ($acm->hasAccess('planningTickBudget') && intval($rsq[0]['planningTick']) == 1){
            $res = "شما قبلا این مورد را تایید نموده اید !";
            $out = "false";
            response($res,$out);
            exit;
        }elseif ($acm->hasAccess('planningTickBudget')){
            $m = '`planningTick`='.$radioValue;
            $n = ',`planningDescription`="'.$desc.'"';
        }

        $sql = "UPDATE `budget_amendment` SET ".$m.$n." WHERE `RowID`={$abid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function finalTickAmendmentBudget($abid){
        $db = new DBi();
        $ut = new Utility();
                $query = "SELECT `RowID` FROM `budget_amendment` WHERE `RowID`={$abid} AND (`productionTick`!=1 OR `planningTick`!=1)";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0){
            $res = "این مورد تاییدیه برنامه ریزی یا تولید را ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }else{
            $sql = "SELECT `month`,`number`,`bcdid`,`finalTick` FROM `budget_amendment` WHERE `RowID`={$abid}";
            $res = $db->ArrayQuery($sql);
            if (intval($res[0]['finalTick']) == 1){
                $res = "شما قبلا این مورد را تایید نهایی نموده اید !";
                $out = "false";
                response($res,$out);
                exit;
            }
            switch ($res[0]['month']){
                case 1:
                    $m = ' `farvardinTotal`';
                    $key = 'farvardinTotal';
                    break;
                case 2:
                    $m = ' `ordibeheshtTotal`';
                    $key = 'ordibeheshtTotal';
                    break;
                case 3:
                    $m = ' `khordadTotal`';
                    $key = 'khordadTotal';
                    break;
                case 4:
                    $m = ' `tirTotal`';
                    $key = 'tirTotal';
                    break;
                case 5:
                    $m = ' `mordadTotal`';
                    $key = 'mordadTotal';
                    break;
                case 6:
                    $m = ' `shahrivarTotal`';
                    $key = 'shahrivarTotal';
                    break;
                case 7:
                    $m = ' `mehrTotal`';
                    $key = 'mehrTotal';
                    break;
                case 8:
                    $m = ' `abanTotal`';
                    $key = 'abanTotal';
                    break;
                case 9:
                    $m = ' `azarTotal`';
                    $key = 'azarTotal';
                    break;
                case 10:
                    $m = ' `deyTotal`';
                    $key = 'deyTotal';
                    break;
                case 11:
                    $m = ' `bahmanTotal`';
                    $key = 'bahmanTotal';
                    break;
                case 12:
                    $m = ' `esfandTotal`';
                    $key = 'esfandTotal';
                    break;
            }
            $sql1 = "SELECT ".$m.",`amendmentHistoryJson` FROM `budget_components_details` WHERE `RowID`={$res[0]['bcdid']}";
            $res1 = $db->ArrayQuery($sql1);
            $currentNumber = $res1[0]["$key"];
            $differenceNumber = intval($currentNumber) - intval($res[0]['number']);
            //----------------------------------------------------------------
                    
            $amendmentHistoryJson = $res1[0]['amendmentHistoryJson'];
            //$//ut->fileRecorder('primaryJSon:'.$amendmentHistoryJson);
            $amendmentHistoryArray=json_decode($amendmentHistoryJson,true);
            //$//ut->fileRecorder('primary:'.print_r($amendmentHistoryArray,true));
            if(!empty($amendmentHistoryJson)){
                $amendmentHistoryArray=json_decode($amendmentHistoryJson,true);
                $amendmentCount="amendment_0";
                if(count($amendmentHistoryArray[$key])>0){
                    $amendmentCount='amendment_'.count($amendmentHistoryArray[$key]);
                }
            }
            else{
                $amendmentCount="amendment_0";
            }
            $amendmentHistoryArray[$key][$amendmentCount]['currentNumber']=intval($currentNumber);
            $amendmentHistoryArray[$key][$amendmentCount]['amendmentNumber']=intval($res[0]['number']);
            $amendmentHistoryArray[$key][$amendmentCount]['amendmentDate']=date('Y-m-d h:i:s');
            $amendmentHistoryArray[$key][$amendmentCount]['user']=$_SESSION['userid'];
            //$//ut->fileRecorder('final:'.print_r($amendmentHistoryArray,true));
            $amendmentHistoryJson=json_encode($amendmentHistoryArray);
            
            $sqlu = "UPDATE `budget_components_details` SET".$m."=".$res[0]['number'].",
                        amendmentHistoryJson='{$amendmentHistoryJson}' WHERE `RowID`={$res[0]['bcdid']}";
            $db->Query($sqlu);

            $sql2 = "UPDATE `budget_amendment` SET `currentNumber`={$currentNumber},`DifferenceNumber`={$differenceNumber},`finalTick`=1 WHERE `RowID`={$abid}";
            $db->Query($sql2);
            $aff = $db->AffectedRows();
            $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
            if (intval($aff) > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    //*************************************************************************************************************************************************************************************
    //*************************************************************************************************************************************************************************************

    //+++++++++++++++++++++++ اسناد ورود و خروج محصول +++++++++++++++++++++++++

    public function getProductEntryExitDocumentsHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('productEntryExitDocuments')){
            die("access denied");
            exit;
        }
        $ut = new Utility();

        $budgetYear = $this->getAllBudgetYear();
        $cntbu = count($budgetYear);

        $cDate = $ut->greg_to_jal(date('Y-m-d'));
        $cmonth = intval((intval(strlen($cDate)) == 8 ? substr($cDate,5,1) : substr($cDate,5,2) ));
        $cday = intval((intval(strlen($cDate)) == 8 || intval(strlen($cDate)) == 9 ? substr($cDate,7,2) : substr($cDate,8,2) ));
        switch ($cmonth){
            case 1:
                $months = array(1=>'فروردین',2=>'اردیبهشت');
                break;
            case 2:
                $months = ($cday <= 5 ? array(1=>'فروردین',2=>'اردیبهشت',3=>'خرداد') : array(2=>'اردیبهشت',3=>'خرداد'));
                break;
            case 3:
                $months = ($cday <= 5 ? array(2=>'اردیبهشت',3=>'خرداد',4=>'تیر') : array(3=>'خرداد',4=>'تیر'));
                break;
            case 4:
                $months = ($cday <= 5 ? array(3=>'خرداد',4=>'تیر',5=>'مرداد') : array(4=>'نیر',5=>'مرداد'));
                break;
            case 5:
                $months = ($cday <= 5 ? array(4=>'تیر',5=>'مرداد',6=>'شهریور') : array(5=>'مرداد',6=>'شهریور'));
                break;
            case 6:
                $months = ($cday <= 5 ? array(5=>'مرداد',6=>'شهریور',7=>'مهر') : array(6=>'شهریور',7=>'مهر'));
                break;
            case 7:
                $months = ($cday <= 5 ? array(6=>'شهریور',7=>'مهر',8=>'آبان') : array(7=>'مهر',8=>'آبان'));
                break;
            case 8:
                $months = ($cday <= 5 ? array(7=>'مهر',8=>'آبان',9=>'آذر') : array(8=>'آبان',9=>'آذر'));
                break;
            case 9:
                $months = ($cday <= 5 ? array(8=>'آبان',9=>'آذر',10=>'دی') : array(9=>'آذر',10=>'دی'));
                break;
            case 10:
                $months = ($cday <= 5 ? array(9=>'آذر',10=>'دی',11=>'بهمن') : array(10=>'دی',11=>'بهمن'));
                break;
            case 11:
                $months = ($cday <= 5 ? array(10=>'دی',11=>'بهمن',12=>'اسفند') : array(11=>'بهمن',12=>'اسفند'));
                break;
            case 12:
                $months = ($cday <= 5 ? array(11=>'بهمن',12=>'اسفند') : array(11=>'اسفند'));
                break;
        }

        $pagename = array();
        $pageIcon = array();
        $contentId = array();
        $menuItems = array();
        $bottons = array();
        $headerSearch = array();
        $access = array();
        $hiddenContentId[] = "hiddenFinalBudgetPrintBody";

        $x = 0;
        $y = 0;
        $z = 0;
        $manifold = 0;
        if($acm->hasAccess('budgetProductEntry')) {
            $pagename[$x] = "سند ورود محصول";
            $pageIcon[$x] = "fa-arrow-right-to-bracket";
            $contentId[$x] = "budgetProductEntryManageBody";
            $menuItems[$x] = 'budgetProductEntryManageTabID';

            $bottons1 = array();
            $headerSearch1 = array();

            $b = 0;
            $bottons1[$b]['title'] = "ثبت سند";
            $bottons1[$b]['jsf'] = "createBudgetProductEntry";
            $bottons1[$b]['icon'] = "fa-plus-square";

            $a = 0;
            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "90px";
            $headerSearch1[$a]['id'] = "budgetProductEntrySDateSearch";
            $headerSearch1[$a]['title'] = "از تاریخ";
            $headerSearch1[$a]['placeholder'] = "از تاریخ";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "90px";
            $headerSearch1[$a]['id'] = "budgetProductEntryEDateSearch";
            $headerSearch1[$a]['title'] = "تا تاریخ";
            $headerSearch1[$a]['placeholder'] = "تا تاریخ";
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['width'] = "120px";
            $headerSearch1[$a]['id'] = "budgetProductEntryYearSearch";
            $headerSearch1[$a]['onchange'] = "onchange=getYearBudgetComponents6()";
            $headerSearch1[$a]['title'] = "سال بودجه فروش";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = "سال بودجه فروش";
            $headerSearch1[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cntbu;$i++){
                $headerSearch1[$a]['options'][$i+1]["title"] = $budgetYear[$i]['Name'];
                $headerSearch1[$a]['options'][$i+1]["value"] = $budgetYear[$i]['RowID'];
            }
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['width'] = "400px";
            $headerSearch1[$a]['id'] = "budgetProductEntryComponentSearch";
            $headerSearch1[$a]['multiple'] = "multiple";
            $headerSearch1[$a]['LimitNumSelections'] = 1;
            $headerSearch1[$a]['title'] = "انتخاب محصول";
            $headerSearch1[$a]['options'] = array();
            $a++;

            $headerSearch1[$a]['type'] = "btn";
            $headerSearch1[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch1[$a]['jsf'] = "showBudgetProductEntryList";

            $bottons[$y] = $bottons1;
            $headerSearch[$z] = $headerSearch1;

            $manifold++;
            $access[] = 1;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('budgetProductExit')) {
            $pagename[$x] = "سند خروج محصول";
            $pageIcon[$x] = "fa-right-from-bracket";
            $contentId[$x] = "budgetProductExitManageBody";
            $menuItems[$x] = 'budgetProductExitManageTabID';

            $bottons2 = array();
            $headerSearch2 = array();

            $b = 0;
            $bottons2[$b]['title'] = "ثبت سند";
            $bottons2[$b]['jsf'] = "createBudgetProductExit";
            $bottons2[$b]['icon'] = "fa-plus-square";

            $a = 0;
            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "90px";
            $headerSearch2[$a]['id'] = "budgetProductExitSDateSearch";
            $headerSearch2[$a]['title'] = "از تاریخ";
            $headerSearch2[$a]['placeholder'] = "از تاریخ";
            $a++;

            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "90px";
            $headerSearch2[$a]['id'] = "budgetProductExitEDateSearch";
            $headerSearch2[$a]['title'] = "تا تاریخ";
            $headerSearch2[$a]['placeholder'] = "تا تاریخ";
            $a++;

            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['width'] = "120px";
            $headerSearch2[$a]['id'] = "budgetProductExitYearSearch";
            $headerSearch2[$a]['onchange'] = "onchange=getYearBudgetComponents7()";
            $headerSearch2[$a]['title'] = "سال بودجه فروش";
            $headerSearch2[$a]['options'] = array();
            $headerSearch2[$a]['options'][0]["title"] = "سال بودجه فروش";
            $headerSearch2[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cntbu;$i++){
                $headerSearch2[$a]['options'][$i+1]["title"] = $budgetYear[$i]['Name'];
                $headerSearch2[$a]['options'][$i+1]["value"] = $budgetYear[$i]['RowID'];
            }
            $a++;

            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['width'] = "400px";
            $headerSearch2[$a]['id'] = "budgetProductExitComponentSearch";
            $headerSearch2[$a]['multiple'] = "multiple";
            $headerSearch2[$a]['LimitNumSelections'] = 1;
            $headerSearch2[$a]['title'] = "انتخاب محصول";
            $headerSearch2[$a]['options'] = array();
            $a++;

            $headerSearch2[$a]['type'] = "btn";
            $headerSearch2[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch2[$a]['jsf'] = "showBudgetProductExitList";

            $bottons[$y] = $bottons2;
            $headerSearch[$z] = $headerSearch2;

            $manifold++;
            $access[] = 2;
        }
        $htm = $ut->getHtmlOfDefaultManagementMultiPage($pagename,$pageIcon,$contentId,$menuItems,$bottons,$manifold,$headerSearch,$hiddenContentId);
        //++++++++++++++++++++++++++++++++++ create budget Product Entry MODAL ++++++++++++++++++++++++++++++++
        $modalID = "createBudgetProductEntryModal";
        $modalTitle = "فرم سند ورود محصول";
        $style = 'style="max-width: 651px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createBudgetProductEntryPEdate";
        $items[$c]['onchange'] = "onchange=getDateBudgetComponents()";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createBudgetProductEntryMonth";
        $items[$c]['title'] = "انتخاب ماه";
        $items[$c]['style'] = "style='width: 60%;'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        $i=0;
        foreach ($months as $key => $value) {
            $items[$c]['options'][$i+1]["title"] = $value;
            $items[$c]['options'][$i+1]["value"] = $key;
            $i++;
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createBudgetProductEntryComponents";
        $items[$c]['onchange'] = "onchange=getTotalNumberProductInThisMonth()";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['LimitNumSelections'] = 1;
        $items[$c]['title'] = "انتخاب محصول";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createBudgetProductEntryHCode";
        $items[$c]['onchange'] = "onchange=getProductNameWithHcode()";
        $items[$c]['title'] = "کد کالا";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createBudgetProductEntryNumber";
        $items[$c]['title'] = "مقدار ورودی";
        $items[$c]['placeholder'] = "مقدار";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "createBudgetProductEntryDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "totalNumberProductInThisMonth";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['title'] = "مقدار بودجه فروش در این ماه";
        $items[$c]['placeholder'] = "مقدار";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCreateBudgetProductEntry";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createBudgetProductEntry = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF create budget Product Entry MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Delete Budget Product Entry MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "deleteBudgetProductEntryModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "deleteBudgetProductEntryIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doDeleteBudgetProductEntry";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $deleteBudgetProductEntry = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++ END OF Delete Budget Product Entry MODAL ++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ create budget Product Exit MODAL ++++++++++++++++++++++++++++++++
        $modalID = "createBudgetProductExitModal";
        $modalTitle = "فرم سند خروج محصول";
        $style = 'style="max-width: 651px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createBudgetProductExitPEdate";
        $items[$c]['onchange'] = "onchange=getDateBudgetComponents1()";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createBudgetProductExitComponents";
        $items[$c]['onchange'] = "onchange=getTotalEntryNumberProduct()";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['LimitNumSelections'] = 1;
        $items[$c]['title'] = "انتخاب محصول";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createBudgetProductExitNumber";
        $items[$c]['title'] = "مقدار خروجی";
        $items[$c]['placeholder'] = "مقدار";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "createBudgetProductExitDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "totalEntryNumberProduct";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['title'] = "مقدار کل موجودی";
        $items[$c]['placeholder'] = "مقدار";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCreateBudgetProductExit";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createBudgetProductExit = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF create budget Product Exit MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Delete Budget Product Exit MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "deleteBudgetProductExitModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "deleteBudgetProductExitIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doDeleteBudgetProductExit";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $deleteBudgetProductExit = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++ END OF Delete Budget Product Exit MODAL ++++++++++++++++++++++++++++
        $htm .= $createBudgetProductEntry;
        $htm .= $deleteBudgetProductEntry;
        $htm .= $createBudgetProductExit;
        $htm .= $deleteBudgetProductExit;
        $send = array($htm,$access);
        return $send;
    }

    //******************** سند ورود محصول ********************

    public function getBudgetProductEntryList($sDate,$eDate,$year,$component,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('budgetProductEntry')){
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
            $w[] = '`entryDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`entryDate` <="'.$eDate.'" ';
        }
        if(intval($year) > 0){
            $w[] = '`bid`='.$year.' ';
        }
        if(intval($component) > 0){
            $w[] = '`bcdid`='.$component.' ';
        }

        $sql = "SELECT `budget_product_entry`.*,`year` FROM `budget_product_entry` INNER JOIN `budget` ON (`budget_product_entry`.`bid`=`budget`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            $query = "SELECT * FROM `budget_components_details` WHERE `RowID`={$res[$y]['bcdid']}";
            $rst = $db->ArrayQuery($query);
            switch ($res[$y]['month']){
                case 1:
                    $monthtxt = 'فروردین';
                    break;
                case 2:
                    $monthtxt = 'اردیبهشت';
                    break;
                case 3:
                    $monthtxt = 'خرداد';
                    break;
                case 4:
                    $monthtxt = 'تیر';
                    break;
                case 5:
                    $monthtxt = 'مرداد';
                    break;
                case 6:
                    $monthtxt = 'شهریور';
                    break;
                case 7:
                    $monthtxt = 'مهر';
                    break;
                case 8:
                    $monthtxt = 'آبان';
                    break;
                case 9:
                    $monthtxt = 'آذر';
                    break;
                case 10:
                    $monthtxt = 'دی';
                    break;
                case 11:
                    $monthtxt = 'بهمن';
                    break;
                case 12:
                    $monthtxt = 'اسفند';
                    break;
            }

            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['year'] = $res[$y]['year'];
            $finalRes[$y]['gCode'] = $rst[0]['gCode'];
            $finalRes[$y]['gName'] = $rst[0]['gName'];
            $finalRes[$y]['month'] = $monthtxt;
            $finalRes[$y]['number'] = $res[$y]['number'];
            $finalRes[$y]['stock'] = $res[$y]['stock'];
            $finalRes[$y]['remaining'] = $res[$y]['remaining'];
            $finalRes[$y]['entryDate'] = $ut->greg_to_jal($res[$y]['entryDate']);
            $finalRes[$y]['entryTime'] = $res[$y]['entryTime'];
            $finalRes[$y]['description'] = $res[$y]['description'];
            $finalRes[$y]['btnType'] = 'btn-danger';
            $finalRes[$y]['icon'] = 'fa-trash';
        }
        return $finalRes;
    }

    public function getBudgetProductEntryListCountRows($sDate,$eDate,$year,$component){
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`entryDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`entryDate` <="'.$eDate.'" ';
        }
        if(intval($year) > 0){
            $w[] = '`bid`='.$year.' ';
        }
        if(intval($component) > 0){
            $w[] = '`bcdid`='.$component.' ';
        }

        $sql = "SELECT `RowID` FROM `budget_product_entry`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function createBudgetProductEntry($peDate,$month,$components,$num,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('budgetProductEntry')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $nowTime = date('H:i:s');
        $jDate = $ut->jal_to_greg($peDate);
        $cDate = $ut->greg_to_jal($jDate);
        $cyear = intval(substr($cDate,0,4));

        $query = "SELECT `RowID` FROM `budget` WHERE `year`={$cyear}";
        $rst = $db->ArrayQuery($query);

        $query1 = "SELECT `parentID`,`totalEntryNumber` FROM `budget_components_details` WHERE `RowID`={$components}";
        $rst1 = $db->ArrayQuery($query1);

        if (intval($rst1[0]['parentID']) > 0){
            $sqls = "SELECT `farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal`,`totalEntryNumber` FROM `budget_components_details` WHERE `RowID`={$rst1[0]['parentID']}";
        }else{
            $sqls = "SELECT `farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal`,`totalEntryNumber` FROM `budget_components_details` WHERE `RowID`={$components}";
        }
        $rsts = $db->ArrayQuery($sqls);

        switch ($month){
            case 1:
                $tNumber = $rsts[0]['farvardinTotal'];
                $m = '`farvardinTotal` ';
                break;
            case 2:
                $tNumber = $rsts[0]['ordibeheshtTotal'];
                $m = '`ordibeheshtTotal` ';
                break;
            case 3:
                $tNumber = $rsts[0]['khordadTotal'];
                $m = '`khordadTotal` ';
                break;
            case 4:
                $tNumber = $rsts[0]['tirTotal'];
                $m = '`tirTotal` ';
                break;
            case 5:
                $tNumber = $rsts[0]['mordadTotal'];
                $m = '`mordadTotal` ';
                break;
            case 6:
                $tNumber = $rsts[0]['shahrivarTotal'];
                $m = '`shahrivarTotal` ';
                break;
            case 7:
                $tNumber = $rsts[0]['mehrTotal'];
                $m = '`mehrTotal` ';
                break;
            case 8:
                $tNumber = $rsts[0]['abanTotal'];
                $m = '`abanTotal` ';
                break;
            case 9:
                $tNumber = $rsts[0]['azarTotal'];
                $m = '`azarTotal` ';
                break;
            case 10:
                $tNumber = $rsts[0]['deyTotal'];
                $m = '`deyTotal` ';
                break;
            case 11:
                $tNumber = $rsts[0]['bahmanTotal'];
                $m = '`bahmanTotal` ';
                break;
            case 12:
                $tNumber = $rsts[0]['esfandTotal'];
                $m = '`esfandTotal` ';
                break;
        }
        if (intval($num) > intval($tNumber)){
            $res = "مقدار وارد شده از مقدار موجودی محصول در این ماه بیشتر است !";
            $out = "false";
            response($res,$out);
            exit;
        }else {
            $totalNum = intval($tNumber) - intval($num);
            $totalNum1 = intval($rsts[0]['totalEntryNumber']) + intval($num);

            if (intval($rst1[0]['parentID']) > 0){
                $totalNum2 = intval($rst1[0]['totalEntryNumber']) + intval($num);

                $sqlu = "UPDATE `budget_components_details` SET `totalEntryNumber`={$totalNum2} WHERE `RowID`={$components}";
                $db->Query($sqlu);

                $sqlu1 = "UPDATE `budget_components_details` SET ".$m."={$totalNum},`totalEntryNumber`={$totalNum1} WHERE `RowID`={$rst1[0]['parentID']}";
                $db->Query($sqlu1);

                $sql = "INSERT INTO `budget_product_entry` (`bid`,`bcdid`,`month`,`entryDate`,`entryTime`,`number`,`description`,`stock`,`remaining`) VALUES ({$rst[0]['RowID']},{$rst1[0]['parentID']},{$month},'{$jDate}','{$nowTime}',{$num},'{$desc}',{$totalNum1},{$totalNum})";
                $res = $db->Query($sql);

            }else{
                $sqlu = "UPDATE `budget_components_details` SET ".$m."={$totalNum},`totalEntryNumber`={$totalNum1} WHERE `RowID`={$components}";
                $db->Query($sqlu);

                $sql = "INSERT INTO `budget_product_entry` (`bid`,`bcdid`,`month`,`entryDate`,`entryTime`,`number`,`description`,`stock`,`remaining`) VALUES ({$rst[0]['RowID']},{$components},{$month},'{$jDate}','{$nowTime}',{$num},'{$desc}',{$totalNum1},{$totalNum})";
                $res = $db->Query($sql);
            }

            if (intval($res) > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function dateBudgetComponents($bDate=""){
        $db = new DBi();
        $ut = new Utility();
        if(!empty($bDate))
        {
            $jDate = $ut->jal_to_greg($bDate);
            $gDate = $ut->greg_to_jal($jDate);
        }
        else{
            $currentDate= Date('Y-m-d');
           // $jDate = $ut->jal_to_greg($bDate);
            $gDate = $ut->greg_to_jal($currentDate);
        }
        $cyear = intval(substr($gDate,0,4));

        $query = "SELECT `RowID` FROM `budget` WHERE `year`={$cyear}";
        $rst = $db->ArrayQuery($query);

        $query1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$rst[0]['RowID']} AND `finalTick`=1";
        $rst1 = $db->ArrayQuery($query1);
        $rids = array();

        if (count($rst1) > 0){
            $cnt = count($rst1);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rst1[$i]['RowID'];
                $rids = implode(',',$rids);
            }
        }else{
            $rids = 0;
        }

        $sql = "SELECT `RowID`,`gName` FROM `budget_components_details` WHERE `bcid` IN ({$rids})";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function deleteBudgetProductEntry($bpeid){
        $db = new DBi();

        $sql = "SELECT `bid`,`bcdid`,`number`,`month` FROM `budget_product_entry` WHERE `RowID`={$bpeid}";
        $res = $db->ArrayQuery($sql);

        $sql1 = "SELECT * FROM `budget_components_details` WHERE `RowID`={$res[0]['bcdid']}";
        $res1 = $db->ArrayQuery($sql1);

        switch ($res[0]['month']){
            case 1:
                $tnumber = $res1[0]['farvardinTotal'] + $res[0]['number'];
                $m = '`farvardinTotal`';
                break;
            case 2:
                $tnumber = $res1[0]['ordibeheshtTotal'] + $res[0]['number'];
                $m = '`ordibeheshtTotal`';
                break;
            case 3:
                $tnumber = $res1[0]['khordadTotal'] + $res[0]['number'];
                $m = '`khordadTotal`';
                break;
            case 4:
                $tnumber = $res1[0]['tirTotal'] + $res[0]['number'];
                $m = '`tirTotal`';
                break;
            case 5:
                $tnumber = $res1[0]['mordadTotal'] + $res[0]['number'];
                $m = '`mordadTotal`';
                break;
            case 6:
                $tnumber = $res1[0]['shahrivarTotal'] + $res[0]['number'];
                $m = '`shahrivarTotal`';
                break;
            case 7:
                $tnumber = $res1[0]['mehrTotal'] + $res[0]['number'];
                $m = '`mehrTotal`';
                break;
            case 8:
                $tnumber = $res1[0]['abanTotal'] + $res[0]['number'];
                $m = '`abanTotal`';
                break;
            case 9:
                $tnumber = $res1[0]['azarTotal'] + $res[0]['number'];
                $m = '`azarTotal`';
                break;
            case 10:
                $tnumber = $res1[0]['deyTotal'] + $res[0]['number'];
                $m = '`deyTotal`';
                break;
            case 11:
                $tnumber = $res1[0]['bahmanTotal'] + $res[0]['number'];
                $m = '`bahmanTotal`';
                break;
            case 12:
                $tnumber = $res1[0]['esfandTotal'] + $res[0]['number'];
                $m = '`esfandTotal`';
                break;
        }
        $tnumber1 = $res1[0]['totalEntryNumber'] - $res[0]['number'];

        $sql2 = "UPDATE `budget_components_details` SET ".$m."={$tnumber},`totalEntryNumber`={$tnumber1} WHERE `RowID`={$res[0]['bcdid']}";
        $db->Query($sql2);

        $sql3 = "DELETE FROM `budget_product_entry` WHERE `RowID`={$bpeid}";
        $db->Query($sql3);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getTotalNumberProductInThisMonth($month,$components){
        $db = new DBi();

        $query = "SELECT `parentID`,`HCode` FROM `budget_components_details` WHERE `RowID`={$components}";
        $rst = $db->ArrayQuery($query);

        if (intval($rst[0]['parentID']) > 0){
            $sqls = "SELECT `farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal`,`HCode` FROM `budget_components_details` WHERE `RowID`={$rst[0]['parentID']}";
            $rsts = $db->ArrayQuery($sqls);
            $rsts[0]['HCode'] = $rst[0]['HCode'];
        }else{
            $sqls = "SELECT `farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal`,`HCode` FROM `budget_components_details` WHERE `RowID`={$components}";
            $rsts = $db->ArrayQuery($sqls);
        }

        switch ($month){
            case 1:
                $tNumber = $rsts[0]['farvardinTotal'];
                break;
            case 2:
                $tNumber = $rsts[0]['ordibeheshtTotal'];
                break;
            case 3:
                $tNumber = $rsts[0]['khordadTotal'];
                break;
            case 4:
                $tNumber = $rsts[0]['tirTotal'];
                break;
            case 5:
                $tNumber = $rsts[0]['mordadTotal'];
                break;
            case 6:
                $tNumber = $rsts[0]['shahrivarTotal'];
                break;
            case 7:
                $tNumber = $rsts[0]['mehrTotal'];
                break;
            case 8:
                $tNumber = $rsts[0]['abanTotal'];
                break;
            case 9:
                $tNumber = $rsts[0]['azarTotal'];
                break;
            case 10:
                $tNumber = $rsts[0]['deyTotal'];
                break;
            case 11:
                $tNumber = $rsts[0]['bahmanTotal'];
                break;
            case 12:
                $tNumber = $rsts[0]['esfandTotal'];
                break;
        }
        return array($tNumber,$rsts[0]['HCode']);
    }

    public function getProductNameWithHcode($peDate,$month,$hcode){
        $db = new DBi();
        $ut = new Utility();
        $jDate = $ut->jal_to_greg($peDate);
        $gDate = $ut->greg_to_jal($jDate);
        $cyear = intval(substr($gDate,0,4));

        $query = "SELECT `RowID` FROM `budget` WHERE `year`={$cyear}";
        $rst = $db->ArrayQuery($query);

        $query1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$rst[0]['RowID']} AND `finalTick`=1";
        $rst1 = $db->ArrayQuery($query1);
        $rids = array();

        if (count($rst1) > 0){
            $cnt = count($rst1);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rst1[$i]['RowID'];
                $rids = implode(',',$rids);
            }
        }else{
            $rids = 0;
        }

        $query = "SELECT `RowID`,`parentID` FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `HCode`='{$hcode}'";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['parentID']) > 0){
            $sql = "SELECT `RowID`,`farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `RowID`={$rst[0]['parentID']}";
            $res = $db->ArrayQuery($sql);
            $res[0]['RowID'] = $rst[0]['RowID'];
        }else{
            $sql = "SELECT `RowID`,`farvardinTotal`,`ordibeheshtTotal`,`khordadTotal`,`tirTotal`,`mordadTotal`,`shahrivarTotal`,`mehrTotal`,`abanTotal`,`azarTotal`,`deyTotal`,`bahmanTotal`,`esfandTotal` FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `HCode`='{$hcode}'";
            $res = $db->ArrayQuery($sql);
        }

        switch ($month){
            case 1:
                $tNumber = $res[0]['farvardinTotal'];
                break;
            case 2:
                $tNumber = $res[0]['ordibeheshtTotal'];
                break;
            case 3:
                $tNumber = $res[0]['khordadTotal'];
                break;
            case 4:
                $tNumber = $res[0]['tirTotal'];
                break;
            case 5:
                $tNumber = $res[0]['mordadTotal'];
                break;
            case 6:
                $tNumber = $res[0]['shahrivarTotal'];
                break;
            case 7:
                $tNumber = $res[0]['mehrTotal'];
                break;
            case 8:
                $tNumber = $res[0]['abanTotal'];
                break;
            case 9:
                $tNumber = $res[0]['azarTotal'];
                break;
            case 10:
                $tNumber = $res[0]['deyTotal'];
                break;
            case 11:
                $tNumber = $res[0]['bahmanTotal'];
                break;
            case 12:
                $tNumber = $res[0]['esfandTotal'];
                break;
        }

        $idd = (strlen(trim($hcode)) == 0 ? 0 : $res[0]['RowID']);
        $tNumber = (intval($idd) > 0 ? $tNumber : 0);
        return array($idd,$tNumber);
    }

    //******************** سند خروج محصول ********************

    public function getBudgetProductExitList($sDate,$eDate,$year,$component,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('budgetProductExit')){
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
            $w[] = '`exitDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`exitDate` <="'.$eDate.'" ';
        }
        if(intval($year) > 0){
            $w[] = '`bid`='.$year.' ';
        }
        if(intval($component) > 0){
            $w[] = '`bcdid`='.$component.' ';
        }

        $sql = "SELECT `budget_product_exit`.*,`year` FROM `budget_product_exit` INNER JOIN `budget` ON (`budget_product_exit`.`bid`=`budget`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `exitDate` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            $query = "SELECT * FROM `budget_components_details` WHERE `RowID`={$res[$y]['bcdid']}";
            $rst = $db->ArrayQuery($query);

            switch ($res[$y]['month']){
                case 1:
                    $monthtxt = 'فروردین';
                    break;
                case 2:
                    $monthtxt = 'اردیبهشت';
                    break;
                case 3:
                    $monthtxt = 'خرداد';
                    break;
                case 4:
                    $monthtxt = 'تیر';
                    break;
                case 5:
                    $monthtxt = 'مرداد';
                    break;
                case 6:
                    $monthtxt = 'شهریور';
                    break;
                case 7:
                    $monthtxt = 'مهر';
                    break;
                case 8:
                    $monthtxt = 'آبان';
                    break;
                case 9:
                    $monthtxt = 'آذر';
                    break;
                case 10:
                    $monthtxt = 'دی';
                    break;
                case 11:
                    $monthtxt = 'بهمن';
                    break;
                case 12:
                    $monthtxt = 'اسفند';
                    break;
            }

            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['year'] = $res[$y]['year'];
            $finalRes[$y]['gCode'] = $rst[0]['gCode'];
            $finalRes[$y]['gName'] = $rst[0]['gName'];
            $finalRes[$y]['totalEntryNumber'] = $rst[0]['totalEntryNumber'];
            $finalRes[$y]['month'] = $monthtxt;
            $finalRes[$y]['number'] = $res[$y]['number'];
            $finalRes[$y]['exitDate'] = $ut->greg_to_jal($res[$y]['exitDate']);
            $finalRes[$y]['exitTime'] = $res[$y]['exitTime'];
            $finalRes[$y]['description'] = $res[$y]['description'];
            $finalRes[$y]['btnType'] = 'btn-danger';
            $finalRes[$y]['icon'] = 'fa-trash';
        }
        return $finalRes;
    }

    public function getBudgetProductExitListCountRows($sDate,$eDate,$year,$component){
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`exitDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`exitDate` <="'.$eDate.'" ';
        }
        if(intval($year) > 0){
            $w[] = '`bid`='.$year.' ';
        }
        if(intval($component) > 0){
            $w[] = '`bcdid`='.$component.' ';
        }

        $sql = "SELECT `RowID` FROM `budget_product_exit`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function createBudgetProductExit($peDate,$components,$num,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('budgetProductExit')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $nowTime = date('H:i:s');
        $jDate = $ut->jal_to_greg($peDate);
        $cDate = $ut->greg_to_jal($jDate);
        $cmonth = intval(substr($cDate,5,2));
        $cyear = intval(substr($cDate,0,4));

        $query = "SELECT `RowID` FROM `budget` WHERE `year`={$cyear}";
        $rst = $db->ArrayQuery($query);

        $sqls = "SELECT `totalEntryNumber` FROM `budget_components_details` WHERE `RowID`={$components}";
        $rsts = $db->ArrayQuery($sqls);

        if (intval($num) > intval($rsts[0]['totalEntryNumber'])){
            $res = "مقدار وارد شده از مقدار کل موجودی محصول بیشتر است !";
            $out = "false";
            response($res,$out);
            exit;
        }else {
            $totalNum = intval($rsts[0]['totalEntryNumber']) - intval($num);

            $sqlu = "UPDATE `budget_components_details` SET `totalEntryNumber`={$totalNum} WHERE `RowID`={$components}";
            $db->Query($sqlu);

            $sql = "INSERT INTO `budget_product_exit` (`bid`,`bcdid`,`month`,`exitDate`,`exitTime`,`number`,`description`) VALUES ({$rst[0]['RowID']},{$components},{$cmonth},'{$jDate}','{$nowTime}',{$num},'{$desc}')";
            $res = $db->Query($sql);
            if (intval($res) > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function deleteBudgetProductExit($bpeid){
        $db = new DBi();

        $sql = "SELECT `bid`,`bcdid`,`number` FROM `budget_product_exit` WHERE `RowID`={$bpeid}";
        $res = $db->ArrayQuery($sql);

        $sql1 = "SELECT `totalEntryNumber` FROM `budget_components_details` WHERE `RowID`={$res[0]['bcdid']}";
        $res1 = $db->ArrayQuery($sql1);

        $tnumber = $res1[0]['totalEntryNumber'] + $res[0]['number'];

        $sql2 = "UPDATE `budget_components_details` SET `totalEntryNumber`={$tnumber} WHERE `RowID`={$res[0]['bcdid']}";
        $db->Query($sql2);

        $sql3 = "DELETE FROM `budget_product_exit` WHERE `RowID`={$bpeid}";
        $db->Query($sql3);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getTotalEntryNumberProduct($components){
        $db = new DBi();
        $sqls = "SELECT `totalEntryNumber` FROM `budget_components_details` WHERE `RowID`={$components}";
        $rsts = $db->ArrayQuery($sqls);
        if (intval($rsts[0]['totalEntryNumber']) == 0){
            $num = 'فاقد مقدار';
        }else{
            $num = intval($rsts[0]['totalEntryNumber']);
        }
        return $num;
    }

    //*************************************************************************************************************************************************************************************

    private function getAllBudgetYear(){
        $db = new DBi();
        $sql = "SELECT `RowID`,`Name` FROM `budget` ORDER BY `RowID` DESC";
        $res = $db->ArrayQuery($sql);
        return $res;
    }
    private function  getAllBudgetYearComponents(){
        $db = new DBi();
        $sql = "SELECT `RowID`,`Name` FROM `budget_components` ORDER BY `RowID` DESC";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

   /*  private function createBudgetListDB($filePath,$bid){
       // $inputFileName = '../pmlexcel/excGSPL.xlsx';
       //error_log($filePath);
	   $ut=new Utility();
	   
        $inputFileName = $filePath;
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($inputFileName);

        $db = new DBi();
        $sql = "DROP TABLE IF EXISTS `budget_components_list_temp`";
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
        //error_log('before create');
        //Create Database table with one Field
        $sql = "CREATE TABLE `budget_components_list_temp` (`RowID` INT NOT NULL PRIMARY KEY AUTO_INCREMENT)";
        $aff = $db->Query($sql);
        //error_log('after create');
        //error_log($sql);
        if (intval($aff) == -1 || intval($aff) == 0){
            $flag = false;
        }

        //Create Others Field (A, B, C & ...)
        $columns_name = $excell_array_data[$skip_rows+1];
        //error_log(print_r($columns_name,true));
        foreach (array_keys($columns_name) as $fieldname ){
           
            $sql1 = "ALTER TABLE `budget_components_list_temp` ADD $fieldname VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin";
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
                $insertValue[] = "('".join(array_values($chunk[$i][$j]), "','")."')";
            }
            $insertValue = implode(',',$insertValue);
            $sql2 = "INSERT INTO `budget_components_list_temp` ($keys) VALUES ".$insertValue." ";
            $aff2 = $db->Query($sql2);
            if (intval($aff2) <= 0){
                $flag = false;
            }
        }
        if ($flag){
            //---------------------------------------------------------rename table fields from A B C  ,... to  Excel column name
            $rename_table_fields="SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='abrash_bom' AND `TABLE_NAME`='budget_components_list_temp'";
            $res = $db->ArrayQuery($rename_table_fields);
            $get_firstRow="SELECT * From budget_components_list_temp where RowID=1";
            $first_res=$db->ArrayQuery($get_firstRow);
            foreach($res as $key=>$value){
				$oldF=trim(str_replace(",","",$value['COLUMN_NAME']));
				if($oldF!="RowID")
                {
					$sql="ALTER TABLE `budget_components_list_temp` CHANGE `{$oldF}`  `{$first_res[0][$oldF]}` VARCHAR(255) NOT NULL";
					$db->Query($sql);
                }
            }
             //-------------------------------------------------------delete    Excel column name that insertyed in table as  first row
		    $del_sql="DELETE FROM budget_components_list_temp Where RowID=1";
		    $db->Query($del_sql);
             //--------------------------------------------------------re create  RowID Autoincriment strat from 1
		    $del_id_sql="ALTER TABLE budget_components_list_temp
						DROP COLUMN RowID;ALTER TABLE `budget_components_list_temp` ADD COLUMN `RowID` INT NOT NULL PRIMARY KEY AUTO_INCREMENT";
		    $db->Query($del_id_sql);
            //---------------------------------------------------------create backup table from budget_components_details
            $timeStamp=strtotime("now");
            $backup_table_name="budget_components_details_backup".$timeStamp;
            $backup_create_table_sql="CREATE TABLE $backup_table_name LIKE budget_components_details";
            $db->Query($backup_create_table_sql);
            $backup_insert_table_sql="INSERT INTO $backup_table_name SELECT * FROM  budget_components_details";
            $db->Query($backup_insert_table_sql);
            //----------------------------------------------------------update   budget_components_details base of imported excel
			
		    $update_sql="UPDATE budget_components_details as bcd join budget_components_list_temp bcl on bcd.gCode=bcl.eng_code
						SET bcd.mehr=bcl.mehr,bcd.aban=bcl.aban,bcd.azar=bcl.azar,bcd.dey=bcl.dey,bcd.bahman=bcl.bahman,bcd.esfand=bcl.esfand
                        WHERE bcid={$bid}";
						//$//ut->fileRecorder('update_sql:'.$update_sql);
			$db->Query($update_sql);
            //-------------------------------------------------------- if excel record is new  and not in  budget_components_details  insert it in budget_components_details
            $set_deffrence_sql="SELECT * from budget_components_list_temp as bcl 
            WHERE not exists (select gCode from budget_components_details as bcd where bcl.eng_code=bcd.gCode) ";
            $res_insert_array=$db->ArrayQuery($set_deffrence_sql);
            if(count($res_insert_array)>0){
                foreach($res_insert_array as $key=>$insert_value){
                    //$insert_budgect_component_det="insert into budget_components_details()";
                }
            }
            return true;
        }else{
            return false;
        }
    
    } */
	 private function createBudgetListDB($filePath,$bid,$bcid)
	{
       // $inputFileName = '../pmlexcel/excGSPL.xlsx';
       $ut=new Utility();
       //error_log($filePath);
    //    $ut->fileRecorder('bcid2:'.$bcid);
    //    die();
        $inputFileName = $filePath;
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($inputFileName);

        $db = new DBi();
        $sql = "DROP TABLE IF EXISTS `budget_components_list_temp`";
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
        //error_log('before create');
        //Create Database table with one Field
        $sql = "CREATE TABLE `budget_components_list_temp` (`RowID` INT NOT NULL PRIMARY KEY AUTO_INCREMENT)";
        $aff = $db->Query($sql);
        //error_log('after create');
        //error_log($sql);
        if (intval($aff) == -1 || intval($aff) == 0){
            $flag = false;
        }

        //Create Others Field (A, B, C & ...)
        $columns_name = $excell_array_data[$skip_rows+1];
        //error_log(print_r($columns_name,true));
        foreach (array_keys($columns_name) as $fieldname ){
           
            $sql1 = "ALTER TABLE `budget_components_list_temp` ADD $fieldname VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin";
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
                $insertValue[] = "('".implode( "','",array_values(str_replace("'",'',$chunk[$i][$j])))."')";
            }
            $insertValue = implode(',',$insertValue);
            $sql2 = "INSERT INTO `budget_components_list_temp` ($keys) VALUES ".$insertValue." ";
            $aff2 = $db->Query($sql2);
            if (intval($aff2) <= 0){
                $flag = false;
            }
        }
       
  
        if ($flag)
        {
            //---------------------------------------------------------rename table fields from A B C  ,... to  Excel column name
            $rename_table_fields="SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='abrash_bom' AND `TABLE_NAME`='budget_components_list_temp'";
            $res = $db->ArrayQuery($rename_table_fields);
            $get_firstRow="SELECT * From budget_components_list_temp where RowID=1";
            $first_res=$db->ArrayQuery($get_firstRow);
            foreach($res as $key=>$value){
				$oldF=trim(str_replace(",","",$value['COLUMN_NAME']));
				if($oldF!="RowID")
                {
					$sql="ALTER TABLE `budget_components_list_temp` CHANGE `{$oldF}`  `{$first_res[0][$oldF]}` VARCHAR(255) NOT NULL";
					$db->Query($sql);
                }
            }
             //-------------------------------------------------------delete    Excel column name that insertyed in table as  first row
		    $del_sql="DELETE FROM budget_components_list_temp Where RowID=1";
		    $db->Query($del_sql);
             //--------------------------------------------------------re create  RowID Autoincriment strat from 1
		    $del_id_sql="ALTER TABLE budget_components_list_temp
						DROP COLUMN RowID;ALTER TABLE `budget_components_list_temp` ADD COLUMN `RowID` INT NOT NULL PRIMARY KEY AUTO_INCREMENT";
		    $db->Query($del_id_sql);
            //---------------------------------------------------------create backup table from budget_components_details
            //------------------------------------------------------------ check if good not in budget component detailes ---------------------
            $get_all_budget_upload="SELECT * from budget_components_list_temp";
            $upload_res=$db->ArrayQuery($get_all_budget_upload);
            $upload_res_array=[];
            foreach($upload_res as $key=>$value){
                $upload_res_array[$value['g_code']]=$value;
            }
             //------------------------------------------------------------ check if good not in budget component detailes  ^^^^^^^---------------------
            $timeStamp=strtotime("now");
            $backup_table_name="budget_components_details_backup".$timeStamp;
            $backup_create_table_sql="CREATE TABLE $backup_table_name LIKE budget_components_details";
            $db->Query($backup_create_table_sql);
            $backup_insert_table_sql="INSERT INTO $backup_table_name SELECT * FROM  budget_components_details";
            $db->Query($backup_insert_table_sql);
            //------------------------------------------------------ اگر بودجه  ثبت نشده بود
            
            $history_array=[];
            //اگر جزییات بودجه جدید در  جدول ثبت نشده بود
            $check_new_bodget_coponent="SELECT gCode from budget_components_details where bcid={$bid}";
            $chk_comp= $db->ArrayQuery($check_new_bodget_coponent);
            $is_exist_budget_component=[];
            foreach($chk_comp as $arr){
                $is_exist_budget_component[]=$arr['gCode'];
            }
           
            foreach($is_exist_budget_component as $gCode){
                unset($upload_res_array[$gCode]);

            }

           if(count($upload_res_array)>0){
                $value_row="";
                
                foreach($upload_res_array as $up_array){
                    $get_good_rowId = "SELECT RowID FROM good where gCode='{$up_array['eng_code']}'";
                    $far=!empty($up_array['farvardin'])?$up_array['farvardin']:0;
                    $or=!empty($up_array['ordibehesht'])?$up_array['ordibehesht']:0;
                    $khor=!empty($up_array['khordad'])?$up_array['khordad']:0;
                    $tir=!empty($up_array['tir'])?$up_array['tir']:0;
                    $mor=!empty($up_array['mordad'])?$up_array['mordad']:0;
                    $shah=!empty($up_array['shahrivar'])?$up_array['shahrivar']:0;
                    $mehr=!empty($up_array['mehr'])?$up_array['mehr']:0;
                    $aban=!empty($up_array['aban'])?$up_array['aban']:0;
                    $azar=!empty($up_array['azar'])?$up_array['azar']:0;
                    $dey=!empty($up_array['dey'])?$up_array['dey']:0;
                    $bah=!empty($up_array['bahman'])?$up_array['bahman']:0;
                    $es=!empty($up_array['esfand'])?$up_array['esfand']:0;

                    $good_res=$db->ArrayQuery($get_good_rowId);
                  
                    $value_row.="(".$bcid.",".$good_res[0]['RowID'].",'".$up_array['g_group']."','".$up_array['eng_code']."','".$up_array['g_code']."','".str_replace("'",'',$up_array['p_name'])."',1,1,
                    {$far},{$or},{$khor},{$tir},{$mor},{$shah},{$mehr},{$aban},{$azar},{$dey},{$bah},{$es}),"	;	
                  
                }
                $value_row=rtrim($value_row,',');
                // $ut->fileRecorder($value_row);
                // die();
                $insert_new_goods="INSERT into budget_components_details (bcid,goodID,ggroup,gCode,HCode,gName,productionTick,planningTick,
                    farvardin,ordibehesht,khordad,tir,mordad,shahrivar,mehr,aban,azar,dey,bahman,esfand) VALUES ".$value_row;
               $ut->fileRecorder($value_row);
                $res=$db->Query($insert_new_goods);
          
           }

            //---------------------------------------------------------------
            $select_edited_sql="SELECT bcd.gCode,bcd.mehr as curr_mehr,bcd.aban as curr_aban,bcd.azar as curr_azar,bcd.dey as curr_dey,
            bcd.bahman as curr_bahman,bcd.esfand as curr_esfand,bcd.amendmentHistoryJson,bcl.* FROM budget_components_details as bcd 
                join budget_components_list_temp as  bcl on bcd.gCode=bcl.eng_code	
                    WHERE bcd.bcid={$bid} AND bcd.gCode<>''";
                $result=$db->ArrayQuery($select_edited_sql);
                foreach($result as $key=>$value){
                    $history=[];
                    $history_array[$value['eng_code']]=[];
                    if(!empty($value['amendmentHistoryJson'])){
                        $history=json_decode(empty($value['amendmentHistoryJson']));
                    }
                    if($value['mehr']>0 && $value['curr_mehr']!=$value['mehr']){
                        //'{"azarTotal":{"currentNumber":0,"amendmentNumber":"500","amendmentDate":"2023-09-26 10:40:51","user":"1"}}')
                        $history[]=array('mehrTotal'=>array('currentNumber'=>$value['curr_mehr'],
                                                            "amendmentNumber"=>$value['mehr'],
                                                            "amendmentDate"=>date("Y-m-d H:i:s"),
                                                            "user"=>$_SESSION['userid'],
                                                            )
                                        );

                        $history_array[$value['eng_code']]=$history;
                    }
                    if($value['aban']>0 && $value['curr_aban']!=$value['aban']){
                        //'{"azarTotal":{"currentNumber":0,"amendmentNumber":"500","amendmentDate":"2023-09-26 10:40:51","user":"1"}}')
                        $history[]=array('abanTotal'=>array('currentNumber'=>$value['curr_aban'],
                                                            "amendmentNumber"=>$value['aban'],
                                                            "amendmentDate"=>date("Y-m-d H:i:s"),
                                                            "user"=>$_SESSION['userid'],
                                                            )
                                        );

                        $history_array[$value['eng_code']]=$history;
                    }
                    if($value['azar']>0 && $value['curr_azar']!=$value['azar']){
                        //'{"azarTotal":{"currentNumber":0,"amendmentNumber":"500","amendmentDate":"2023-09-26 10:40:51","user":"1"}}')
                        $history[]=array('azarTotal'=>array('currentNumber'=>$value['curr_azar'],
                                                            "amendmentNumber"=>$value['azar'],
                                                            "amendmentDate"=>date("Y-m-d H:i:s"),
                                                            "user"=>$_SESSION['userid'],
                                                            )
                                        );

                        $history_array[$value['eng_code']]=$history;
                    }
                    if($value['dey']>0 && $value['curr_dey']!=$value['dey']){
                        //'{"azarTotal":{"currentNumber":0,"amendmentNumber":"500","amendmentDate":"2023-09-26 10:40:51","user":"1"}}')
                        $history[]=array('deyTotal'=>array('currentNumber'=>$value['curr_dey'],
                                                            "amendmentNumber"=>$value['dey'],
                                                            "amendmentDate"=>date("Y-m-d H:i:s"),
                                                            "user"=>$_SESSION['userid'],
                                                            )
                                        );

                        $history_array[$value['eng_code']]=$history;
                    }
                    if($value['bahman']>0 && $value['curr_bahman']!=$value['bahman']){
                        //'{"azarTotal":{"currentNumber":0,"amendmentNumber":"500","amendmentDate":"2023-09-26 10:40:51","user":"1"}}')
                        $history[]=array('bahmanTotal'=>array('currentNumber'=>$value['curr_bahman'],
                                                            "amendmentNumber"=>$value['bahman'],
                                                            "amendmentDate"=>date("Y-m-d H:i:s"),
                                                            "user"=>$_SESSION['userid'],
                                                            )
                                        );

                        $history_array[$value['eng_code']]=$history;
                    }
                    if($value['esfand']>0 && $value['curr_esfand']!=$value['esfand'] ){
                        //'{"azarTotal":{"currentNumber":0,"amendmentNumber":"500","amendmentDate":"2023-09-26 10:40:51","user":"1"}}')
                        $history[]=array('esfandTotal'=>array('currentNumber'=>$value['curr_esfand'],
                                                            "amendmentNumber"=>$value['esfand'],
                                                            "amendmentDate"=>date("Y-m-d H:i:s"),
                                                            "user"=>$_SESSION['userid'],
                                                            )
                                        );

                        $history_array[$value['eng_code']]=$history;
                    }
                }
                //$//ut->fileRecorder(print_r($history_array,true));

            //----------------------------------------------------------update   budget_components_details base of imported excel
		    $update_sql="UPDATE budget_components_details as bcd join budget_components_list_temp bcl on bcd.gCode=bcl.eng_code
						SET bcd.mehr=bcl.mehr,bcd.aban=bcl.aban,bcd.azar=bcl.azar,bcd.dey=bcl.dey,bcd.bahman=bcl.bahman,bcd.esfand=bcl.esfand
                        WHERE bcid={$bid} and bcd.gCode<>''";
			$db->Query($update_sql);
            foreach($history_array as $eng_code=>$history){
                $json_history=json_encode($history);
                $update_history="UPDATE budget_components_details SET amendmentHistoryJson='{$json_history}' Where g_Code='{$eng_code}'";
				$res=$db->Query($update_history);
            }
            //-------------------------------------------------------- if excel record is new  and not in  budget_components_details  insert it in budget_components_details
            $set_deffrence_sql="SELECT * from budget_components_list_temp as bcl 
            WHERE not exists (select gCode from budget_components_details as bcd where bcl.eng_code=bcd.gCode) ";
            //$//ut->fileRecorder("set_deffrence_sql:".$set_deffrence_sql);
            $res_insert_array=$db->ArrayQuery($set_deffrence_sql);
            if(count($res_insert_array)>0){
                foreach($res_insert_array as $key=>$insert_value){
                    $amendum_array=[];
                    if($insert_value['farvardin']>0){
                        $amendum_array['farvardinTotal']=array("currentNumber"=>0,"amendmentNumber"=>$insert_value['farvardin'],"amendmentDate"=>date("Y-m-d H:i:s"),"user"=>$_SESSION['userid']);
                    }
                    if($insert_value['ordibehesht']>0){
                        $amendum_array['ordibeheshtTotal']=array("currentNumber"=>0,"amendmentNumber"=>$insert_value['ordibehesht'],"amendmentDate"=>date("Y-m-d H:i:s"),"user"=>$_SESSION['userid']);
                    }
                    if($insert_value['khordad']>0){
                        $amendum_array['khordadTotal']=array("currentNumber"=>0,"amendmentNumber"=>$insert_value['khordad'],"amendmentDate"=>date("Y-m-d H:i:s"),"user"=>$_SESSION['userid']);
                    }
                    if($insert_value['tir']>0){
                        $amendum_array['tirTotal']=array("currentNumber"=>0,"amendmentNumber"=>$insert_value['tir'],"amendmentDate"=>date("Y-m-d H:i:s"),"user"=>$_SESSION['userid']);
                    }
                    if($insert_value['mordad']>0){
                        $amendum_array['mordadTotal']=array("currentNumber"=>0,"amendmentNumber"=>$insert_value['mordad'],"amendmentDate"=>date("Y-m-d H:i:s"),"user"=>$_SESSION['userid']);
                    }
                    if($insert_value['shahrivar']>0){
                        $amendum_array['shahrivarTotal']=array("currentNumber"=>0,"amendmentNumber"=>$insert_value['shahrivar'],"amendmentDate"=>date("Y-m-d H:i:s"),"user"=>$_SESSION['userid']);
                    }
                    if($insert_value['mehr']>0){
                        $amendum_array['mehrTotal']=array("currentNumber"=>0,"amendmentNumber"=>$insert_value['mehr'],"amendmentDate"=>date("Y-m-d H:i:s"),"user"=>$_SESSION['userid']);
                    }
                    if($insert_value['aban']>0){
                        $amendum_array['abanTotal']=array("currentNumber"=>0,"amendmentNumber"=>$insert_value['aban'],"amendmentDate"=>date("Y-m-d H:i:s"),"user"=>$_SESSION['userid']);
                    }
                    if($insert_value['azar']>0){
                        $amendum_array['azarTotal']=array("currentNumber"=>0,"amendmentNumber"=>$insert_value['azar'],"amendmentDate"=>date("Y-m-d H:i:s"),"user"=>$_SESSION['userid']);
                    }
                    if($insert_value['dey']>0){
                        $amendum_array['deyTotal']=array("currentNumber"=>0,"amendmentNumber"=>$insert_value['dey'],"amendmentDate"=>date("Y-m-d H:i:s"),"user"=>$_SESSION['userid']);
                    }
                    if($insert_value['bahman']>0){
                        $amendum_array['bahmanTotal']=array("currentNumber"=>0,"amendmentNumber"=>$insert_value['bahman'],"amendmentDate"=>date("Y-m-d H:i:s"),"user"=>$_SESSION['userid']);
                    }
                    if($insert_value['esfand']>0){
                        $amendum_array['esfandTotal']=array("currentNumber"=>0,"amendmentNumber"=>$insert_value['esfand'],"amendmentDate"=>date("Y-m-d H:i:s"),"user"=>$_SESSION['userid']);
                    }
                    $month_value=[];
                    //{"shahrivarTotal":{"amendment_0":{"currentNumber":3000,"amendmentNumber":0,"amendmentDate":"2023-08-19 02:09:10","user":"1"}}}
                    $month_array=['farvardin','ordibehesht','khordad','tir','mordad','shahrivar','mehr','aban','azar','dey','bahman','esfand'];
                    for($i=0;$i<count($month_array);$i++){
                        $month_value[]=!empty($insert_value[$month_array[$i]])?$insert_value[$month_array[$i]]:0;
                    }
                    $amendmentHistoryJson=json_encode($amendum_array);
                    $insert_budgect_component_det="insert into budget_components_details( 
                    bcid,gCode,gName,ggroup,
                    farvardin,ordibehesht,khordad,tir,mordad,shahrivar,mehr,aban,azar,dey,bahman,esfand,
                    productionTick,planningTick,productionDescription,planningDescription,finalTick,
                    farvardinTotal,ordibeheshtTotal,khordadTotal,tirTotal,mordadTotal,shahrivarTotal,mehrTotal,abanTotal,azarTotal,deyTotal,bahmanTotal,esfandTotal,
                    totalEntryNumber,
                    HCode,
                    amendmentHistoryJson)
                    VALUES(
                        {$bid},'{$insert_value['eng_code']}','{$insert_value['p_name']}','{$insert_value['g_group']}',
                        {$month_value[0]},{$month_value[1]},{$month_value[2]},{$month_value[3]},{$month_value[4]},{$month_value[5]},{$month_value[6]},{$month_value[7]},{$month_value[8]},{$month_value[9]},{$month_value[10]},{$month_value[11]},
                        1,1,'اصلاحیه بودجه شش ماهه دوم 1402','اصلاحیه بودجه شش ماهه دوم 1402',1,
                        {$month_value[0]},{$month_value[1]},{$month_value[2]},{$month_value[3]},{$month_value[4]},{$month_value[5]},{$month_value[6]},{$month_value[7]},{$month_value[8]},{$month_value[9]},{$month_value[10]},{$month_value[11]},
                        0,'{$insert_value['g_code']}','{$amendmentHistoryJson }')";
                    //$//ut->fileRecorder($insert_budgect_component_det);
                    $db->Query($insert_budgect_component_det);    
                }
            }
            return true;
        }else{
            return false;
        }
    }

}