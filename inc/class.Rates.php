<?php


class Rates{

    public function __construct(){
        // do nothing
    }

    public function getManageRatesHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('manageRates')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $s = new Salary();
        $res = $this->getUnits();
        $cnt = count($res);
        $res1 = $this->getAvailableDays();
        $headGroup = $s->getHeadGroup();
        $chg = count($headGroup);

        $manifold = 0;
        $z = 0;
        $x = 0;
        $y = 0;
        $pagename = array();
        $pageIcon = array();
        $contentId = array();
        $menuItems = array();
        $bottons = array();
        $headerSearch = array();
        $Access = array();

        if($acm->hasAccess('currencyManage')){
            $pagename[$z] = "مدیریت نرخ ارز";
            $pageIcon[$z] = "fa-dollar-sign";
            $contentId[$z] = "currencyManageBody";
            $hiddenContentId[$z] = '';
            $menuItems[$z] = 'currencyManageTabID';

            $c = 0;
            $bottons1 = array();
            $bottons1[$c]['title'] = "افزودن ارز جدید";
            $bottons1[$c]['jsf'] = "createCurrency";
            $bottons1[$c]['icon'] = "fa-plus-square";
            $c++;

            $bottons1[$c]['title'] = "ویرایش نرخ ارز";
            $bottons1[$c]['jsf'] = "editCurrency";
            $bottons1[$c]['icon'] = "fa-edit";

            $bottons[$x] = $bottons1;

            $headerSearch1 = array();
            $headerSearch[$y] = $headerSearch1;

            $z++;
            $x++;
            $y++;
            $manifold++;
            $Access[] = 1;
        }
        if($acm->hasAccess('manageBrassWeight')){
            $pagename[$z] = "مدیریت بار برنج";
            $pageIcon[$z] = "fa-weight";
            $contentId[$z] = "brassWeightManageBody";
            $hiddenContentId[$z] = '';
            $menuItems[$z] = 'brassWeightManageTabID';

            $c = 0;
            $bottons2 = array();
            $bottons2[$c]['title'] = "ثبت / ویرایش";
            $bottons2[$c]['jsf'] = "editCreateBrassWeight";
            $bottons2[$c]['icon'] = "fa-edit";

            $bottons[$x] = $bottons2;

            $headerSearch2 = array();
            $headerSearch[$y] = $headerSearch2;

            $z++;
            $x++;
            $y++;
            $manifold++;
            $Access[] = 2;
        }
        if($acm->hasAccess('salaryBenefitsManage')){
            $pagename[$z] = "مدیریت پرسنل";
            $pageIcon[$z] = "fa-users";
            $contentId[$z] = "personnelManageBody";
            $hiddenContentId[$z] = 'hiddenContentBody';
            $menuItems[$z] = 'personnelManageTabID';

            $c = 0;
            // $bottons3 = array();
            // $bottons3[$c]['title'] = "افزودن پرسنل جدید";
            // $bottons3[$c]['jsf'] = "createPersonnel";
            // $bottons3[$c]['icon'] = "fa-plus-square";
            // $c++;

            // $bottons3[$c]['title'] = "ویرایش کلی";
            // $bottons3[$c]['jsf'] = "editAllPersonnel";
            // $bottons3[$c]['icon'] = "fa-edit";
            // $c++;

/*            $bottons3[$c]['title'] = "حذف پرسنل";
            $bottons3[$c]['jsf'] = "deletePersonnel";
            $bottons3[$c]['icon'] = "fa-minus-square";
            $c++;*/

            $bottons3[$c]['title'] = "افزودن مهارت و توانایی";
            $bottons3[$c]['jsf'] = "createAbility";
            $bottons3[$c]['icon'] = "fa-plus-square";

            if($acm->hasAccess('administrativeManagement')) {
                $c++;
                $bottons3[$c]['title'] = "گروه های حقوقی";
                $bottons3[$c]['jsf'] = "editSalaryGroup";
                $bottons3[$c]['icon'] = "fa-edit";
                $c++;

                $bottons3[$c]['title'] = "مقایسه افراد در گروه های حقوقی";
                $bottons3[$c]['jsf'] = "comparePersonnelSalaryGroup";
                $bottons3[$c]['icon'] = "fa-users";
                $c++;

                $bottons3[$c]['title'] = "خروجی اکسل قیمت تمام شده";
                $bottons3[$c]['jsf'] = "finalPricePersonnelExcel";
                $bottons3[$c]['icon'] = "fa-file-excel";
            }

            $c++;
            $bottons3[$c]['title'] = "خروجی اکسل کسری مدارک";
            $bottons3[$c]['jsf'] = "getPersonnelDeficitDocumentsExcel";
            $bottons3[$c]['icon'] = "fa-file-excel";
            $c++;

            $bottons3[$c]['title'] = "خروجی اکسل";
            $bottons3[$c]['jsf'] = "createPersonnelExcel";
            $bottons3[$c]['icon'] = "fa-file-excel";
            $c++;

            $bottons3[$c]['title'] = "خروجی اکسل توانایی ها";
            $bottons3[$c]['jsf'] = "createPersonnelAbilityExcel";
            $bottons3[$c]['icon'] = "fa-file-excel";

            $bottons[$x] = $bottons3;

            $a = 0;
            $headerSearch3 = array();
            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['id'] = "personnelManageSearchType";
            $headerSearch3[$a]['title'] = "جستجو بر اساس";
            $headerSearch3[$a]['width'] = "120px";
            $headerSearch3[$a]['onchange'] = "onchange=personnelSearchType()";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = 'جستجو بر اساس';
            $headerSearch3[$a]['options'][0]["value"] = 0;
            $headerSearch3[$a]['options'][1]["title"] = 'نام';
            $headerSearch3[$a]['options'][1]["value"] = 1;
            $headerSearch3[$a]['options'][2]["title"] = 'نام خانوادگی';
            $headerSearch3[$a]['options'][2]["value"] = 2;
            $headerSearch3[$a]['options'][3]["title"] = 'کد پرسنلی';
            $headerSearch3[$a]['options'][3]["value"] = 3;
            $headerSearch3[$a]['options'][4]["title"] = 'واحد';
            $headerSearch3[$a]['options'][4]["value"] = 4;
            $headerSearch3[$a]['options'][5]["title"] = 'تاریخ استخدام';
            $headerSearch3[$a]['options'][5]["value"] = 5;
            $headerSearch3[$a]['options'][6]["title"] = 'جمع کل هزینه ها';
            $headerSearch3[$a]['options'][6]["value"] = 6;
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "90px";
            $headerSearch3[$a]['id'] = "personnelManagePnameSearch";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['title'] = "نام";
            $headerSearch3[$a]['placeholder'] = "نام";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "90px";
            $headerSearch3[$a]['id'] = "personnelManagePfamilySearch";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['title'] = "نام خانوادگی";
            $headerSearch3[$a]['placeholder'] = "نام خانوادگی";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "90px";
            $headerSearch3[$a]['id'] = "personnelManagePcodeSearch";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['title'] = "کد پرسنلی";
            $headerSearch3[$a]['placeholder'] = "کد پرسنلی";
            $a++;

            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['id'] = "personnelManagePunitSearch";
            $headerSearch3[$a]['title'] = "واحد";
            $headerSearch3[$a]['width'] = "100px";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = '----------';
            $headerSearch3[$a]['options'][0]["value"] = 0;
            for ($i=0;$i<$cnt;$i++){
                $headerSearch3[$a]['options'][$i+1]["title"] = $res[$i]['Uname'];
                $headerSearch3[$a]['options'][$i+1]["value"] = $res[$i]['RowID'];
            }
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "90px";
            $headerSearch3[$a]['id'] = "personnelManageRsDateSearch";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['title'] = "از تاریخ";
            $headerSearch3[$a]['placeholder'] = "از تاریخ";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "90px";
            $headerSearch3[$a]['id'] = "personnelManageReDateSearch";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['title'] = "تا تاریخ";
            $headerSearch3[$a]['placeholder'] = "تا تاریخ";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "100px";
            $headerSearch3[$a]['id'] = "personnelManageTsAmountSearch";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['onkeyup'] = "onkeyup=addSeprator()";
            $headerSearch3[$a]['title'] = "از مبلغ (ریال)";
            $headerSearch3[$a]['placeholder'] = "از مبلغ (ریال)";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "100px";
            $headerSearch3[$a]['id'] = "personnelManageTeAmountSearch";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['onkeyup'] = "onkeyup=addSeprator()";
            $headerSearch3[$a]['title'] = "تا مبلغ (ریال)";
            $headerSearch3[$a]['placeholder'] = "تا مبلغ (ریال)";
            $a++;


            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['id'] = "personnelManageSearchType1";
            $headerSearch3[$a]['title'] = "جستجو بر اساس";
            $headerSearch3[$a]['width'] = "120px";
            $headerSearch3[$a]['onchange'] = "onchange=personnelSearchType1()";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = 'جستجو بر اساس';
            $headerSearch3[$a]['options'][0]["value"] = 0;
            $headerSearch3[$a]['options'][1]["title"] = 'نام';
            $headerSearch3[$a]['options'][1]["value"] = 1;
            $headerSearch3[$a]['options'][2]["title"] = 'نام خانوادگی';
            $headerSearch3[$a]['options'][2]["value"] = 2;
            $headerSearch3[$a]['options'][3]["title"] = 'کد پرسنلی';
            $headerSearch3[$a]['options'][3]["value"] = 3;
            $headerSearch3[$a]['options'][4]["title"] = 'واحد';
            $headerSearch3[$a]['options'][4]["value"] = 4;
            $headerSearch3[$a]['options'][5]["title"] = 'تاریخ استخدام';
            $headerSearch3[$a]['options'][5]["value"] = 5;
            $headerSearch3[$a]['options'][6]["title"] = 'جمع کل هزینه ها';
            $headerSearch3[$a]['options'][6]["value"] = 6;
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "90px";
            $headerSearch3[$a]['id'] = "personnelManagePnameSearch1";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['title'] = "نام";
            $headerSearch3[$a]['placeholder'] = "نام";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "90px";
            $headerSearch3[$a]['id'] = "personnelManagePfamilySearch1";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['title'] = "نام خانوادگی";
            $headerSearch3[$a]['placeholder'] = "نام خانوادگی";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "90px";
            $headerSearch3[$a]['id'] = "personnelManagePcodeSearch1";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['title'] = "کد پرسنلی";
            $headerSearch3[$a]['placeholder'] = "کد پرسنلی";
            $a++;

            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['id'] = "personnelManagePunitSearch1";
            $headerSearch3[$a]['title'] = "واحد";
            $headerSearch3[$a]['width'] = "100px";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = '----------';
            $headerSearch3[$a]['options'][0]["value"] = 0;
            for ($i=0;$i<$cnt;$i++){
                $headerSearch3[$a]['options'][$i+1]["title"] = $res[$i]['Uname'];
                $headerSearch3[$a]['options'][$i+1]["value"] = $res[$i]['RowID'];
            }
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "90px";
            $headerSearch3[$a]['id'] = "personnelManageRsDateSearch1";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['title'] = "از تاریخ";
            $headerSearch3[$a]['placeholder'] = "از تاریخ";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "90px";
            $headerSearch3[$a]['id'] = "personnelManageReDateSearch1";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['title'] = "تا تاریخ";
            $headerSearch3[$a]['placeholder'] = "تا تاریخ";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "100px";
            $headerSearch3[$a]['id'] = "personnelManageTsAmountSearch1";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['onkeyup'] = "onkeyup=addSeprator()";
            $headerSearch3[$a]['title'] = "از مبلغ (ریال)";
            $headerSearch3[$a]['placeholder'] = "از مبلغ (ریال)";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "100px";
            $headerSearch3[$a]['id'] = "personnelManageTeAmountSearch1";
            $headerSearch3[$a]['style'] = "style='display: none;'";
            $headerSearch3[$a]['onkeyup'] = "onkeyup=addSeprator()";
            $headerSearch3[$a]['title'] = "تا مبلغ (ریال)";
            $headerSearch3[$a]['placeholder'] = "تا مبلغ (ریال)";
            $a++;

            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['width'] = "100px";
            $headerSearch3[$a]['id'] = "personnelManageSearchStatus";
            $headerSearch3[$a]['title'] = "وضعیت پرسنل";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = "فعال";
            $headerSearch3[$a]['options'][0]["value"] = 1;
            $headerSearch3[$a]['options'][1]["title"] = "غیرفعال";
            $headerSearch3[$a]['options'][1]["value"] = 0;
            $a++;

            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['id'] = "personnelManageSearchHeadGroup";
            $headerSearch3[$a]['title'] = "انتخاب سرگروه";
            $headerSearch3[$a]['width'] = "180px";
            $headerSearch3[$a]['onchange'] = "onchange=personnelAbilitySubGroup()";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = 'انتخاب سرگروه';
            $headerSearch3[$a]['options'][0]["value"] = 0;
            for($e=0;$e<$chg;$e++){
                $headerSearch3[$a]['options'][$e+1]["title"] = $headGroup[$e]["Ability"];
                $headerSearch3[$a]['options'][$e+1]["value"] = $headGroup[$e]["RowID"];
            }
            $a++;

            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['id'] = "personnelManageSearchAbility";
            $headerSearch3[$a]['title'] = "انتخاب توانایی";
            $headerSearch3[$a]['width'] = "180px";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = 'انتخاب توانایی';
            $headerSearch3[$a]['options'][0]["value"] = 0;
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "150px";
            $headerSearch3[$a]['id'] = "personnelManagEndContractDate";
           // $headerSearch3[$a]['style'] = "style='display: none;'";
          //  $headerSearch3[$a]['onkeyup'] = "onkeyup=addSeprator()";
            $headerSearch3[$a]['title'] = "تاریخ اتمام قرارداد";
            $headerSearch3[$a]['placeholder'] = "تاریخ اتمام قرارداد";
            $a++;

            $headerSearch3[$a]['type'] = "btn";
            $headerSearch3[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch3[$a]['jsf'] = "showPersonnelManageList";

            $headerSearch[$y] = $headerSearch3;

            $z++;
            $x++;
            $y++;
            $manifold++;
            $Access[] = 3;
        }
        if($acm->hasAccess('manageUnits')){
            $pagename[$z] = "واحد های اداری/تولیدی";
            $pageIcon[$z] = "fa-pencil-ruler";
            $contentId[$z] = "unitManageBody";
            $hiddenContentId[$z] = '';
            $menuItems[$z] = 'salaryManageTabID';

            $c = 0;
            $bottons4 = array();
            $bottons4[$c]['title'] = "ایجاد واحد جدید";
            $bottons4[$c]['jsf'] = "createUnit";
            $bottons4[$c]['icon'] = "fa-plus-square";
            $c++;

            $bottons4[$c]['title'] = "ویرایش واحد";
            $bottons4[$c]['jsf'] = "editUnit";
            $bottons4[$c]['icon'] = "fa-edit";

            $bottons[$x] = $bottons4;

            $headerSearch4 = array();
            $headerSearch[$y] = $headerSearch4;

            $z++;
            $x++;
            $y++;
            $manifold++;
            $Access[] = 4;
        }
        if($acm->hasAccess('manageAvailableDay')){
            $pagename[$z] = "روزهای در دسترس";
            $pageIcon[$z] = "fa-sun";
            $contentId[$z] = "availableDayManageBody";
            $hiddenContentId[$z] = '';
            $menuItems[$z] = 'availableDayManageTabID';

            $c = 0;
            $bottons5 = array();
            $bottons5[$c]['title'] = "ثبت";
            $bottons5[$c]['jsf'] = "createAvailableDay";
            $bottons5[$c]['icon'] = "fa-plus-square";
            $c++;

            $bottons5[$c]['title'] = "ویرایش";
            $bottons5[$c]['jsf'] = "editAvailableDay";
            $bottons5[$c]['icon'] = "fa-edit";

            $bottons[$x] = $bottons5;

            $headerSearch5 = array();
            $headerSearch[$y] = $headerSearch5;

            $z++;
            $x++;
            $y++;
            $manifold++;
            $Access[] = 5;
        }
        if($acm->hasAccess('industrialManagement')){
            $pagename[$z] = "زمان سنجی قطعات";
            $pageIcon[$z] = "fa-stopwatch";
            $contentId[$z] = "industryTimingManagementBody";
            $hiddenContentId[$z] = '';
            $menuItems[$z] = 'industryTimingManageTabID';

            $c = 0;
            $bottons6 = array();
            $bottons6[$c]['title'] = "خروجی اکسل BOM";
            $bottons6[$c]['jsf'] = "generalExcelBOM";
            $bottons6[$c]['icon'] = "fa-file-excel";
            $c++;

            $bottons6[$c]['title'] = "خروجی اکسل";
            $bottons6[$c]['jsf'] = "getExcelZamanSanji";
            $bottons6[$c]['icon'] = "fa-edit";
            $c++;

            $bottons6[$c]['title'] = "خروجی اکسل زمان سنجی";
            $bottons6[$c]['jsf'] = "generalExcelBOMFinancial";
            $bottons6[$c]['icon'] = "fa-file-excel";

            $bottons[$x] = $bottons6;


            $a = 0;
            $headerSearch6 = array();
            $headerSearch6[$a]['type'] = "text";
            $headerSearch6[$a]['width'] = "200px";
            $headerSearch6[$a]['id'] = "industrialManagePnameSearch";
            $headerSearch6[$a]['title'] = "قسمتی از نام قطعه";
            $headerSearch6[$a]['placeholder'] = "قسمتی از نام قطعه";
            $a++;

            $headerSearch6[$a]['type'] = "text";
            $headerSearch6[$a]['width'] = "120px";
            $headerSearch6[$a]['id'] = "industrialManagePcodeSearch";
            $headerSearch6[$a]['title'] = "کد قطعه";
            $headerSearch6[$a]['placeholder'] = "کد قطعه";
            $a++;

            $headerSearch6[$a]['type'] = "select";
            $headerSearch6[$a]['id'] = "industrialManageHowSupplySearch";
            $headerSearch6[$a]['title'] = "روش انجام محاسبات";
            $headerSearch6[$a]['width'] = "150px";
            $headerSearch6[$a]['options'] = array();
            $headerSearch6[$a]['options'][0]["title"] = 'روش انجام محاسبات';
            $headerSearch6[$a]['options'][0]["value"] = -1;
            $headerSearch6[$a]['options'][1]["title"] = 'راکد';
            $headerSearch6[$a]['options'][1]["value"] = 0;
            $headerSearch6[$a]['options'][2]["title"] = 'وارداتی';
            $headerSearch6[$a]['options'][2]["value"] = 1;
            $headerSearch6[$a]['options'][3]["title"] = 'خرید داخلی';
            $headerSearch6[$a]['options'][3]["value"] = 2;
            $headerSearch6[$a]['options'][4]["title"] = 'خرید قطعه ماشینکاری';
            $headerSearch6[$a]['options'][4]["value"] = 3;
            $headerSearch6[$a]['options'][5]["title"] = 'خرید قطعه ریخته گری';
            $headerSearch6[$a]['options'][5]["value"] = 4;
            $headerSearch6[$a]['options'][6]["title"] = 'تولید ریخته گری';
            $headerSearch6[$a]['options'][6]["value"] = 5;
            $headerSearch6[$a]['options'][7]["title"] = 'تولید ماشینکاری';
            $headerSearch6[$a]['options'][7]["value"] = 6;
            $headerSearch6[$a]['options'][8]["title"] = 'فورج';
            $headerSearch6[$a]['options'][8]["value"] = 7;
            $headerSearch6[$a]['options'][9]["title"] = 'تزریق پلاستیک';
            $headerSearch6[$a]['options'][9]["value"] = 8;
            $headerSearch6[$a]['options'][10]["title"] = 'لوله';
            $headerSearch6[$a]['options'][10]["value"] = 9;
            $headerSearch6[$a]['options'][11]["title"] = 'شیلنگ';
            $headerSearch6[$a]['options'][11]["value"] = 10;
            $headerSearch6[$a]['options'][12]["title"] = 'برش لیزر';
            $headerSearch6[$a]['options'][12]["value"] = 11;
            $headerSearch6[$a]['options'][13]["title"] = 'کلکتور';
            $headerSearch6[$a]['options'][13]["value"] = 12;
            $headerSearch6[$a]['options'][14]["title"] = 'منسوخ';
            $headerSearch6[$a]['options'][14]["value"] = 13;
            $headerSearch6[$a]['options'][15]["title"] = 'قطعه مونتاژی';
            $headerSearch6[$a]['options'][15]["value"] = 14;
            $a++;

            $headerSearch6[$a]['type'] = "btn";
            $headerSearch6[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch6[$a]['jsf'] = "showIndustrialManageList";

            $headerSearch[$y] = $headerSearch6;

            $z++;
            $x++;
            $y++;
            $manifold++;
            $Access[] = 6;
        }
        if($acm->hasAccess('industrialManagement')){
            $pagename[$z] = "زمان سنجی محصولات";
            $pageIcon[$z] = "fa-stopwatch";
            $contentId[$z] = "industryGTimingManagementBody";
            $hiddenContentId[$z] = '';
            $menuItems[$z] = 'industryGTimingManageTabID';

            //$c = 0;
            $bottons7 = array();
/*            $bottons7[$c]['title'] = "خروجی اکسل BOM";
            $bottons7[$c]['jsf'] = "generalExcelBOM";
            $bottons7[$c]['icon'] = "fa-file-excel";
            $c++;

            $bottons7[$c]['title'] = "خروجی اکسل";
            $bottons7[$c]['jsf'] = "getExcelZamanSanji";
            $bottons7[$c]['icon'] = "fa-edit";*/

            $bottons[$x] = $bottons7;


            $a = 0;
            $headerSearch7 = array();
            $headerSearch7[$a]['type'] = "text";
            $headerSearch7[$a]['width'] = "200px";
            $headerSearch7[$a]['id'] = "industrialManageGnameSearch";
            $headerSearch7[$a]['title'] = "قسمتی از نام محصول";
            $headerSearch7[$a]['placeholder'] = "قسمتی از نام محصول";
            $a++;

            $headerSearch7[$a]['type'] = "text";
            $headerSearch7[$a]['width'] = "120px";
            $headerSearch7[$a]['id'] = "industrialManageGcodeSearch";
            $headerSearch7[$a]['title'] = "کد محصول";
            $headerSearch7[$a]['placeholder'] = "کد محصول";
            $a++;

            $headerSearch7[$a]['type'] = "btn";
            $headerSearch7[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch7[$a]['jsf'] = "showIndustrialGManageList";

            $headerSearch[$y] = $headerSearch7;

            $z++;
            $x++;
            $y++;
            $manifold++;
            $Access[] = 7;
        }
        if($acm->hasAccess('percentagesAccess')){
            $pagename[$z] = 'درصد ضایعات، بهره و مالیات';
            $pageIcon[$z] = 'fa-percentage';
            $contentId[$z] = 'percentagesManageBody';
            $hiddenContentId[$z] = '';
            $menuItems[$z] = 'percentagesManageTabID';

            $c = 0;
            $bottons8 = array();
            $bottons8[$c]['title'] = "ثبت/ویرایش";
            $bottons8[$c]['jsf'] = "editCreatePercentages";
            $bottons8[$c]['icon'] = "fa-edit";
            $bottons[$x] = $bottons8;

            $headerSearch8 = array();
            $headerSearch[$y] = $headerSearch8;

            $manifold++;
            $Access[] = 8;
			$z++;
            $x++;
            $y++;
            $manifold++;
            $Access[] = 8;
        }
		//--------------------------------------------------
		   if($acm->hasAccess('recruitment_tests')){//------------- آزمون های استخدامی
            $pagename[$z] = 'مدیریت آزمون های بدو استخدام';
            $pageIcon[$z] = 'fa-percentage';
            $contentId[$z] = 'recruitmentTestsBody';
            $hiddenContentId[$z] = '';
            $menuItems[$z] = 'recruitment_tests_tab_id';

            $c = 0;
            $bottons9 = array();
            $bottons9[$c]['title'] = "ثبت  مصاحبه جدید";
            $bottons9[$c]['jsf'] = "createRecruitment";
            $bottons9[$c]['icon'] = "fa-edit";
            $bottons[$x] = $bottons9;
			

            $headerSearch9 = array();
            $headerSearch[$y] = $headerSearch9;

            $manifold++;
            $Access[] = 9;
        }
		//--------------------------------------------------

        $htm = $ut->getHtmlOfDefaultManagementMultiPage($pagename,$pageIcon,$contentId,$menuItems,$bottons,$manifold,$headerSearch,$hiddenContentId);
        //++++++++++++++++++++++++++++++++++++++++++++++ edit create Recruitment modal++++++++++++++++++++++++++
		$modalID = "recruitment_modal";
        $modalTitle = "فرم ایجاد و ویرایش اطلاعات بدو ااستخدام";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "recruitment_fullname";
        $items[$c]['title'] = " نام و نام خانوادگی متقاضی";
        $items[$c]['placeholder'] = "نام و نام خانوادگی متقاضی";
       
        $c++;
		
		$items[$c]['type'] = "text";
        $items[$c]['id'] = "recruitment_test_date";
        $items[$c]['title'] = " تاریخ مصاحبه";
        $items[$c]['placeholder'] = "تاریخ مصاحبه";
       
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "recruitment_mobile";
        $items[$c]['title'] = "شماره همراه";
        $items[$c]['placeholder'] = "شماره همراه";
        
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "recruitment_national_id";
        $items[$c]['title'] = "کد ملی";
        $items[$c]['placeholder'] = "کد ملی";
        
        $c++;
		 $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "recruitment_description";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "توضیحات";
        
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "recruitment_id";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ثبت";
        $footerBottons[0]['jsf'] = "doEditCreateRecruitment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateRecruitment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++++++++++++++++++ end of edit create Recruitment modal+++++++++++++++++++
        //++++++++++++++++++++++++++++++++++++++++++++++ upload file +++++++++++++++++++
		 $modalID = "RecruitmentAddAttachmentFileModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'RecruitmentAddAttachmentFile-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "RecruitmentManagmentAddAttachmentName";
        $items[$c]['title'] = "نام فایل";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "RecruitmentManagmentAddAttachment";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PNG , JPG , JPEG , JFIF , PDF , XLSX , DOCX , ZIP , RAR , WAV باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "RecruitmentManagmentAddAttachmentID";
		
        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "do_attach_recruitment_files";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $RecruitmentAddAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
		//******************************************upload file ^^^^^^^^^^^^^^^^^^^
		
		
		//++++++++++++++++++++++++++++++++++EDIT CREATE Currency MODAL++++++++++++++++++++++++++++++++
        $modalID = "currencyManagmentModal";
        $modalTitle = "فرم ایجاد/ویرایش ارز";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "currencyManagmentCuname";
        $items[$c]['title'] = "نام ارز";
        $items[$c]['placeholder'] = "نام";
        $items[$c]['onchange'] = "onchange=checkCurrencyName()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "currencyManagmentExchangeRate";
        $items[$c]['title'] = "نرخ تبدیل روز به دلار";
        $items[$c]['placeholder'] = "دلار";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['onchange'] = "onchange=changeToDollar()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "currencyManagmentCRate";
        $items[$c]['title'] = "نرخ روز";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageCurrencyHiddenCid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ثبت";
        $footerBottons[0]['jsf'] = "doEditCreateCurrency";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateCurrencyModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE Currency MODAL +++++++++++++++++++++++++++++++++++++
        //-------------------------------upload excel timing ------------------------------------
        $modalID = "upload_excel_modal";
        $modalTitle = "آپلود فایل زمانسنجی";
        $style = 'style="max-width: 650px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "file";
        $items[$c]['id'] = "upload_excel_file";
        $items[$c]['title'] = "عنوان فایل ";
//        $items[$c]['placeholder'] = "نام";
//        $items[$c]['onchange'] = "onchange=checkCurrencyName()";
        $c++;

//        $items[$c]['type'] = "text";
//        $items[$c]['id'] = "currencyManagmentExchangeRate";
//        $items[$c]['title'] = "نرخ تبدیل روز به دلار";
//        $items[$c]['placeholder'] = "دلار";
//        $items[$c]['disabled'] = "disabled";
//        $items[$c]['onchange'] = "onchange=changeToDollar()";
//        $c++;
//
//        $items[$c]['type'] = "text";
//        $items[$c]['id'] = "currencyManagmentCRate";
//        $items[$c]['title'] = "نرخ روز";
//        $items[$c]['placeholder'] = "ریال";
//        $items[$c]['disabled'] = "disabled";
//        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
//        $c++;
//
//        $items[$c]['type'] = "hidden";
//        $items[$c]['id'] = "manageCurrencyHiddenCid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ثبت";
        $footerBottons[0]['jsf'] = "do_upload_excel_piece_timinig";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $upload_excel_Modal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //-------------------------------upload excel timing ------------------------------------
        //++++++++++++++++++++++++++++++++++ EDIT CREATE BRASS WEIGHT MODAL ++++++++++++++++++++++++++++++++
        $modalID = "brassWeightManageModal";
        $modalTitle = "فرم ثبت/ویرایش قیمت ها";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "brassWeightManageBSPrice";
        $items[$c]['title'] = "قیمت براده برنج (ریخته گری - ماشینکاری)";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['onchange'] = "onchange=calKhakPardakht()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "brassWeightManageBPriceU14";
        $items[$c]['title'] = "اجرت شمش (قطر بالای 14)";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "brassWeightManageBPriceUn14";
        $items[$c]['title'] = "اجرت شمش (قطر زیر 14)";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "brassWeightManageBPriceC";
        $items[$c]['title'] = "اجرت شمش (کلکتور)";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "brassWeightManageCastingPrice";
        $items[$c]['title'] = "اجرت ریخته گری (در ابرش)";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "brassWeightManagePPSBW";
        $items[$c]['title'] = "درصد ارزش خاک پرداخت به براده برنج";
        $items[$c]['placeholder'] = "درصد";
        $items[$c]['onchange'] = "onchange=calKhakPardakht()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "brassWeightManagePolishingSoilPrice";
        $items[$c]['title'] = "قیمت خاک پرداخت";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "brassWeightManagePercentFuelWeight";
        $items[$c]['title'] = "درصد سوخت بار ریخته گری (در ابرش)";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "brassWeightManageHiddenBWid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ثبت";
        $footerBottons[0]['jsf'] = "doEditCreateBrassWeight";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateBrassWeightModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE BRASS WEIGHT MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ EDIT CREATE UNIT MODAL ++++++++++++++++++++++++++++++++
        $modalID = "salaryBenefitsManageUnitModal";
        $modalTitle = "فرم ایجاد/ویرایش واحد";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "salaryBenefitsManageUname";
        $items[$c]['title'] = "نام واحد";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "salaryBenefitsManageUType";
        $items[$c]['title'] = "نوع واحد";
        $items[$c]['style'] = "style='width: 50%;'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = '----------';
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = 'تولیدی';
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = 'سربار';
        $items[$c]['options'][2]["value"] = 1;
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageSalaryBenefitsHiddenUid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateUnit";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateUnitModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++ END OF EDIT CREATE UNIT MODAL ++++++++++++++++++
        //++++++++++++++++++ EDIT CREATE PERSONNEL MODAL ++++++++++++++++++
        $modalID = "personnelManagmentModal";
        $modalTitle = "فرم ایجاد/ویرایش پرسنل";
        $style = 'style="max-width: 571px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "personnelManagmentType";
        $items[$c]['title'] = "نوع پرسنل";
        $items[$c]['onchange'] = "onchange=changePersonnelViewField()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "عادی";
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = "ساعتی";
        $items[$c]['options'][2]["value"] = 0;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentFName";
        $items[$c]['title'] = "نام";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentLName";
        $items[$c]['title'] = "نام خانوادگی";
        $items[$c]['placeholder'] = "نام خانوادگی";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentFatherName";
        $items[$c]['title'] = "نام پدر";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentPcode";
        $items[$c]['title'] = "کد پرسنلی";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "personnelManagmentGender";
        $items[$c]['title'] = "جنسیت";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "زن";
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = "مرد";
        $items[$c]['options'][1]["value"] = 1;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "personnelManagmentStatus";
        $items[$c]['title'] = "وضعیت پرسنل";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "فعال";
        $items[$c]['options'][0]["value"] = "1";
        $items[$c]['options'][1]["title"] = "غیرفعال";
        $items[$c]['options'][1]["value"] = "0";
        $c++;

        $items[$c]['type'] = "checkbox";
        $items[$c]['id'] = "personnelManagmentPYFR";
        $items[$c]['title'] = "فاقد مزایا";
        $c++;

        $items[$c]['type'] = "checkbox";
        $items[$c]['id'] = "personnelManagmentNoInsurance";
        $items[$c]['title'] = "فاقد بیمه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentBirthDate";
        $items[$c]['style'] = "style='width: 150px;float: right;'";
        $items[$c]['title'] = "تاریخ تولد";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentRecruitmentDate";
        $items[$c]['style'] = "style='width: 150px;float: right;'";
        $items[$c]['title'] = "تاریخ استخدام";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentBeginDateContract";
        $items[$c]['style'] = "style='width: 150px;float: right;'";
        $items[$c]['title'] = "تاریخ شروع قرارداد";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentEndDateContract";
        $items[$c]['style'] = "style='width: 150px;float: right;'";
        $items[$c]['title'] = "تاریخ اتمام قرارداد";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentLeaveDate";
        $items[$c]['style'] = "style='width: 150px;float: right;'";
        $items[$c]['title'] = "تاریخ پایان همکاری";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentPhone";
        $items[$c]['title'] = "تلفن ثابت";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentMobile";
        $items[$c]['title'] = "تلفن همراه";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentTermContract";
        $items[$c]['title'] = "مدت قرارداد";
        $items[$c]['placeholder'] = "ماهه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentMonthTrial";
        $items[$c]['title'] = "مدت آزمایشی";
        $items[$c]['placeholder'] = "ماه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentBirthCertificateNum";
        $items[$c]['title'] = "شماره شناسنامه";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentIssued";
        $items[$c]['title'] = "صادره از";
        $items[$c]['placeholder'] = "نام شهر";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentNationalCode";
        $items[$c]['title'] = "کد ملی";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "personnelManagmentMaritalStatus";
        $items[$c]['title'] = "وضعیت تاهل";
        $items[$c]['onchange'] = "onchange='check_marrital_status(this)'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = '----------';
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = 'مجرد';
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = 'متاهل';
        $items[$c]['options'][2]["value"] = 1;
        $items[$c]['options'][3]["title"] = 'معیل';
        $items[$c]['options'][3]["value"] = 2;
        $c++;
        //---------------------------------------------
        // $items[$c]['type'] = "text";
        // $items[$c]['id'] = "personnelRightMarry";
        // $items[$c]['title'] = "حق تاهل ";
        // $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        // $items[$c]['placeholder'] = "حق تاهل";
        // $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelRightMarry";
        $items[$c]['title'] = "حق تاهل ";
        $items[$c]['placeholder'] = "حق تاهل";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        //---------------------------------------------

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentAddress";
        $items[$c]['title'] = "آدرس";
        $items[$c]['placeholder'] = "آدرس";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentInsuranceNumber";
        $items[$c]['title'] = "شماره بیمه";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentAccountNumber";
        $items[$c]['title'] = "شماره حساب";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentEvaluationScore";
        $items[$c]['title'] = "امتیاز ارزشیابی";
        $items[$c]['placeholder'] = "امتیاز";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "personnelManagmentUnits";
        $items[$c]['title'] = "واحد";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = '----------';
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cnt;$i++){
            $items[$c]['options'][$i+1]["title"] = $res[$i]['Uname'];
            $items[$c]['options'][$i+1]["value"] = $res[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "inputSelectGroup";
        $items[$c]['id'] = "personnelManagmentDegreeEducation";
        $items[$c]['icon'] = "fa-plus fa-lg";
        $items[$c]['onclick'] = "onclick='addDegreeEducation()'";
        $items[$c]['title'] = "مدرک تحصیلی";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = '';
        $items[$c]['options'][1]["title"] = 'سیکل';
        $items[$c]['options'][1]["value"] = 'سیکل';
        $items[$c]['options'][2]["title"] = 'دیپلم';
        $items[$c]['options'][2]["value"] = 'دیپلم';
        $items[$c]['options'][3]["title"] = 'فوق دیپلم';
        $items[$c]['options'][3]["value"] = 'فوق دیپلم';
        $items[$c]['options'][4]["title"] = 'لیسانس';
        $items[$c]['options'][4]["value"] = 'لیسانس';
        $items[$c]['options'][5]["title"] = 'فوق لیسانس';
        $items[$c]['options'][5]["value"] = 'فوق لیسانس';
        $items[$c]['options'][6]["title"] = 'دکترا';
        $items[$c]['options'][6]["value"] = 'دکترا';
        $items[$c]['options'][7]["title"] = 'فوق دکترا';
        $items[$c]['options'][7]["value"] = 'فوق دکترا';
        $c++;

        $items[$c]['type'] = "inputGroup";
        $items[$c]['id'] = "personnelManagmentFieldStudy";
        $items[$c]['icon'] = "fa-plus fa-lg";
        $items[$c]['onclick'] = "onclick='addFieldStudy()'";
        $items[$c]['title'] = "رشته تحصیلی";
        $items[$c]['placeholder'] = "تایپ کنید";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentDegreeFieldStudy";
        $items[$c]['style'] = "style='resize: none;'";
        $items[$c]['title'] = "مدارک و رشته های تحصیلی";
        $items[$c]['placeholder'] = "مدرک/مدارک و رشته تحصیلی";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "personnelManagmentAbilityHeadGroup";
        $items[$c]['title'] = "انتخاب سرگروه";
        $items[$c]['onchange'] = "onchange=getAbilitySubGroup()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = "0";
        for($e=0;$e<$chg;$e++){
            $items[$c]['options'][$e+1]["title"] = $headGroup[$e]["Ability"];
            $items[$c]['options'][$e+1]["value"] = $headGroup[$e]["RowID"];
        }
        $c++;

        $items[$c]['type'] = "inputSelectGroup";
        $items[$c]['id'] = "personnelManagmentAbility";
        $items[$c]['icon'] = "fa-plus fa-lg";
        $items[$c]['onclick'] = "onclick='addAbility()'";
        $items[$c]['title'] = "انتخاب توانایی";
        $items[$c]['placeholder'] = "تایپ کنید";
        $c++;

        $items[$c]['type'] = "inputSelectGroup";
        $items[$c]['id'] = "personnelManagmentProficiency";
        $items[$c]['icon'] = "fa-plus fa-lg";
        $items[$c]['onclick'] = "onclick='addProficiency()'";
        $items[$c]['title'] = "میزان تسلط ";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = '';
        $items[$c]['options'][1]["title"] = 'بسیار ضعیف';
        $items[$c]['options'][1]["value"] = 'بسیار ضعیف';
        $items[$c]['options'][2]["title"] = 'ضعیف';
        $items[$c]['options'][2]["value"] = 'ضعیف';
        $items[$c]['options'][3]["title"] = 'متوسط';
        $items[$c]['options'][3]["value"] = 'متوسط';
        $items[$c]['options'][4]["title"] = 'خوب';
        $items[$c]['options'][4]["value"] = 'خوب';
        $items[$c]['options'][5]["title"] = 'بسیار خوب';
        $items[$c]['options'][5]["value"] = 'بسیار خوب';
        $c++;

        $items[$c]['type'] = "inputGroup";
        $items[$c]['id'] = "personnelManagmentPassedCourses";
        $items[$c]['icon'] = "fa-plus fa-lg";
        $items[$c]['onclick'] = "onclick='addPassedCourses()'";
        $items[$c]['title'] = "دوره گذرانده شده ";
        $items[$c]['placeholder'] = "نام دوره";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentAbilityProficiency";
        $items[$c]['style'] = "style='resize: none;'";
        $items[$c]['title'] = "توانایی ها، میزان تسلط و دوره ها";
        $items[$c]['placeholder'] = "توانایی ها، میزان تسلط و دوره ها";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentHourlyWages";
        $items[$c]['title'] = "دستمزد ساعتی";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentAvgDayInMonth";
        $items[$c]['title'] = "میانگین تعداد روزهای کاری در ماه";
        $items[$c]['placeholder'] = "تعداد روز در ماه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentDailyDutyWorkingHours";
        $items[$c]['title'] = "ساعت کارکرد موظفی روزانه";
        $items[$c]['placeholder'] = "تعداد ساعت در روز";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentDailyWages";
        $items[$c]['title'] = "دستمزد روزانه";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentYearsCost";
        $items[$c]['title'] = "مزد سنوات";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentRightHousing";
        $items[$c]['title'] = "حق مسکن";
        $items[$c]['placeholder'] = "ریال (ماهانه)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentNumberChildren";
        $items[$c]['title'] = "تعداد فرزندان مشمول";
        $items[$c]['placeholder'] = "تعداد";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentChild";
        $items[$c]['title'] = "حق هراولاد";
        $items[$c]['placeholder'] = "ریال (ماهانه)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentGrocery";
        $items[$c]['title'] = "کمک هزینه اقلام مصرفی خانوار";
        $items[$c]['placeholder'] = "ریال (ماهانه)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "personnelManagmentShift";
        $items[$c]['title'] = "نوبت کار";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "0";
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = "10";
        $items[$c]['options'][1]["value"] = 10;
        $items[$c]['options'][2]["title"] = "15";
        $items[$c]['options'][2]["value"] = 15;
        $items[$c]['options'][3]["title"] = "22.5";
        $items[$c]['options'][3]["value"] = 22.5;
        $c++;

        if($acm->hasAccess('administrativeManagement')) {
            $items[$c]['type'] = "text";
            $items[$c]['id'] = "personnelManagmentOutOfList";
            $items[$c]['title'] = "حقوق خارج لیست";
            $items[$c]['placeholder'] = "ریال (ماهانه)";
            $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
            $c++;

            $items[$c]['type'] = "text";
            $items[$c]['id'] = "personnelManagmentResponsibilityRight";
            $items[$c]['title'] = "حق مسئولیت";
            $items[$c]['placeholder'] = "ریال (ماهانه)";
            $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
            $c++;

            $items[$c]['type'] = "text";
            $items[$c]['id'] = "personnelManagmentJobRight";
            $items[$c]['title'] = "حق شغل";
            $items[$c]['placeholder'] = "ریال (ماهانه)";
            $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
            $c++;

            $items[$c]['type'] = "text";
            $items[$c]['id'] = "personnelManagmentHardWork";
            $items[$c]['title'] = "کمک هزینه سرویس";
            $items[$c]['placeholder'] = "ریال (ماهانه)";
            $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
            $c++;

            $items[$c]['type'] = "text";
            $items[$c]['id'] = "personnelManagmentFinancialAllowance";
            $items[$c]['title'] = "کمک هزینه اجاره";
            $items[$c]['placeholder'] = "ریال (ماهانه)";
            $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
            $c++;
        }

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentService";
        $items[$c]['title'] = "سرویس";
        $items[$c]['placeholder'] = "ریال (ماهانه)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentDaysAvailable";
        $items[$c]['title'] = "روزهای در دسترس";
        $items[$c]['placeholder'] = "تعداد روز";
        $items[$c]['value'] = 'value="'.$res1[0]['AvailableDays'].' روز"';
        $c++;

        $items[$c]['type'] = "checkbox";
        $items[$c]['id'] = "personnelManagmentLeaveStatus";
        $items[$c]['title'] = "محاسبه مرخصی";
        $c++;

        $items[$c]['type'] = "checkbox";
        $items[$c]['id'] = "personnelManagmentOvertimeStatus";
        $items[$c]['title'] = "محاسبه اضافه کار";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentAboutOTHoursMonth";
        $items[$c]['title'] = "حدود ساعت اضافه کار";
        $items[$c]['placeholder'] = "تعداد ساعت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentOvertimeLunch";
        $items[$c]['title'] = "ناهار اضافه کار";
        $items[$c]['placeholder'] = "ریال (روزانه)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentOvertimeService";
        $items[$c]['title'] = "سرویس اضافه کار";
        $items[$c]['placeholder'] = "ریال (روزانه)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "personnelManagmentSalaryGroup";
        $items[$c]['title'] = "انتخاب گروه حقوقی";
        $items[$c]['onchange'] = "onchange=getSalaryGroupRange()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = "گروه 1";
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = "گروه 2";
        $items[$c]['options'][2]["value"] = 2;
        $items[$c]['options'][3]["title"] = "گروه 3";
        $items[$c]['options'][3]["value"] = 3;
        $items[$c]['options'][4]["title"] = "گروه 4";
        $items[$c]['options'][4]["value"] = 4;
        $items[$c]['options'][5]["title"] = "گروه 5";
        $items[$c]['options'][5]["value"] = 5;
        $items[$c]['options'][6]["title"] = "گروه 6";
        $items[$c]['options'][6]["value"] = 6;
        $items[$c]['options'][7]["title"] = "گروه 7";
        $items[$c]['options'][7]["value"] = 7;
        $items[$c]['options'][8]["title"] = "گروه 8";
        $items[$c]['options'][8]["value"] = 8;
        $items[$c]['options'][9]["title"] = "گروه 9";
        $items[$c]['options'][9]["value"] = 9;
        $items[$c]['options'][10]["title"] = "گروه 10";
        $items[$c]['options'][10]["value"] = 10;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentMinimumSalary";
        $items[$c]['title'] = "حداقل حقوق";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['disabled'] = "disabled";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentMaximumSalary";
        $items[$c]['title'] = "حداکثر حقوق";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['disabled'] = "disabled";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentAchievementCnd";
        $items[$c]['title'] = "شرایط احراز";
        $items[$c]['placeholder'] = "شرایط";
        $items[$c]['disabled'] = "disabled";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "managePersonnelHiddenPid";
		$c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "managePersonnelHiddenPage";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreatePersonnel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreatePersonnelModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++ END OF EDIT CREATE PERSONNEL MODAL ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ DOCUMENT MODAL ++++++++++++++++++++++++++++++++
        $modalID = "personnelDocumentModal";
        $modalTitle = "فرم ویرایش مدارک";
        $style = 'style="max-width: 550px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentQuestionnaire";  // پرسشنامه های استخدامی
        $items[$c]['title'] = "پرسشنامه های استخدام";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentQuestionnaireDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentRecognizance";  // تعدنامه بدو استخدام
        $items[$c]['title'] = "تعهدنامه های بدو استخدام";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentRecognizanceDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentNationalCard";  // شناسنامه و کارت ملی متقاضی
        $items[$c]['title'] = "شناسنامه و کارت ملی متقاضی";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentNationalCardDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentNationalCardDependants";  // شناسنامه و کارت ملی افراد تحت تکفل
        $items[$c]['title'] = "شناسنامه و کارت ملی افراد تحت تکفل";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentNationalCardDependantsDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentInsuranceBooklet";  // دفترچه بیمه تامین اجتماعی
        $items[$c]['title'] = "دفترچه بیمه تامین اجتماعی";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentInsuranceBookletDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentCardMilitary";  // کارت پایان خدمت یا معافی سربازی
        $items[$c]['title'] = "کارت پایان خدمت یا معافی سربازی";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentCardMilitaryDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentInsuranceRecords";  // سوابق بیمه تامین اجتماعی
        $items[$c]['title'] = "سوابق بیمه تامین اجتماعی";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentInsuranceRecordsDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentDegreeEducation";  // آخرین مدرک تحصیلی
        $items[$c]['title'] = "آخرین مدرک تحصیلی";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentDegreeEducationDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentPhoto";  // عکس 4*3
        $items[$c]['title'] = "عکس 4*3";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentPhotoDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentCertificate";  // گواهینامه های آموزشی مهارتی
        $items[$c]['title'] = "گواهینامه های آموزشی مهارتی";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentCertificateDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentVerificationServiceRecords";  // تایید سوابق خدمت و حسن انجام کار
        $items[$c]['title'] = "تایید سوابق خدمت و حسن انجام کار";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentVerificationServiceRecordsDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentLackBackground";  // اصل تایید عدم سو پیشینه
        $items[$c]['title'] = "اصل تایید عدم سو پیشینه";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentLackBackgroundDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentAccountNumber";  // شماره حساب سیبا - بانک ملی
        $items[$c]['title'] = "شماره حساب سیبا - بانک ملی";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentAccountNumberDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentCheckPromissoryNote";  // چک یا سفته ضمانت
        $items[$c]['title'] = "چک یا سفته ضمانت";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentCheckPromissoryNoteDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "personnelManagmentExperiments";  // آزمایشات بدو استخدام
        $items[$c]['title'] = "آزمایشات بدو استخدام";
        $items[$c]['options'][0]['title'] = "بایگانی شد";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "بایگانی نشد";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelManagmentExperimentsDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "personnelManagmentHiddenPid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditDocumentPersonnel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editDocModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF DOCUMENT MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ DELETE MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "manageDeletePersonnelModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به حذف این پرسنل مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "personnelManage_deleteIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "dodeletePersonnel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $delModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF DELETE MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ CREATE Ability MODAL ++++++++++++++++++++++++++++++++
        $modalID = "personnelAbilityModal";
        $modalTitle = "فرم ایجاد توانایی جدید";
        $style = "style = 'max-width: 583px;'";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "personnelManagmentAbilityGroup";
        $items[$c]['title'] = "انتخاب سرگروه";
        $items[$c]['onchange'] = "onchange=getAbilitySubGroup()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = "سر گروه";
        $items[$c]['options'][1]["value"] = -1;
        for($e=0;$e<$chg;$e++){
            $items[$c]['options'][$e+2]["title"] = $headGroup[$e]["Ability"];
            $items[$c]['options'][$e+2]["value"] = $headGroup[$e]["RowID"];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "personnelManagmentAbilitySubGroup";
        $items[$c]['title'] = "انتخاب زیرگروه";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = "0";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelManagmentAbilityName";
        $items[$c]['title'] = "عنوان توانایی";
        $items[$c]['placeholder'] = "تایپ کنید";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCreateAbility";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createAbilityModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++ END OF CREATE Ability MODAL ++++++++++++++++++
        //++++++++++++++++++ EDIT SALARY GROUP MODAL ++++++++++++++++++
        $modalID = "personnelSalaryGroupModal";
        $modalTitle = "فرم ویرایش گروه های حقوقی";
        $style = 'style="max-width: 571px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelSalaryGname1";
        $items[$c]['title'] = "نام گروه 1";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 48%;'";
        $items[$c]['id'] = "personnelMinMaxSalary1";
        $items[$c]['title'] = "بهای تمام شده ماهیانه";
        $items[$c]['id1'] = "personnelMinimumSalary1";
        $items[$c]['id2'] = "personnelMaximumSalary1";
        $items[$c]['placeholder1'] = "حداقل (ریال)";
        $items[$c]['placeholder2'] = "حداکثر (ریال)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelSalaryGroupAchConditions1";
        $items[$c]['title'] = "شرایط احراز گروه 1";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelSalaryGname2";
        $items[$c]['title'] = "نام گروه 2";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 48%;'";
        $items[$c]['id'] = "personnelMinMaxSalary2";
        $items[$c]['title'] = "بهای تمام شده ماهیانه";
        $items[$c]['id1'] = "personnelMinimumSalary2";
        $items[$c]['id2'] = "personnelMaximumSalary2";
        $items[$c]['placeholder1'] = "حداقل (ریال)";
        $items[$c]['placeholder2'] = "حداکثر (ریال)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelSalaryGroupAchConditions2";
        $items[$c]['title'] = "شرایط احراز گروه 2";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelSalaryGname3";
        $items[$c]['title'] = "نام گروه 3";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 48%;'";
        $items[$c]['id'] = "personnelMinMaxSalary3";
        $items[$c]['title'] = "بهای تمام شده ماهیانه";
        $items[$c]['id1'] = "personnelMinimumSalary3";
        $items[$c]['id2'] = "personnelMaximumSalary3";
        $items[$c]['placeholder1'] = "حداقل (ریال)";
        $items[$c]['placeholder2'] = "حداکثر (ریال)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelSalaryGroupAchConditions3";
        $items[$c]['title'] = "شرایط احراز گروه 3";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelSalaryGname4";
        $items[$c]['title'] = "نام گروه 4";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 48%;'";
        $items[$c]['id'] = "personnelMinMaxSalary4";
        $items[$c]['title'] = "بهای تمام شده ماهیانه";
        $items[$c]['id1'] = "personnelMinimumSalary4";
        $items[$c]['id2'] = "personnelMaximumSalary4";
        $items[$c]['placeholder1'] = "حداقل (ریال)";
        $items[$c]['placeholder2'] = "حداکثر (ریال)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelSalaryGroupAchConditions4";
        $items[$c]['title'] = "شرایط احراز گروه 4";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelSalaryGname5";
        $items[$c]['title'] = "نام گروه 5";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 48%;'";
        $items[$c]['id'] = "personnelMinMaxSalary5";
        $items[$c]['title'] = "بهای تمام شده ماهیانه";
        $items[$c]['id1'] = "personnelMinimumSalary5";
        $items[$c]['id2'] = "personnelMaximumSalary5";
        $items[$c]['placeholder1'] = "حداقل (ریال)";
        $items[$c]['placeholder2'] = "حداکثر (ریال)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelSalaryGroupAchConditions5";
        $items[$c]['title'] = "شرایط احراز گروه 5";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelSalaryGname6";
        $items[$c]['title'] = "نام گروه 6";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 48%;'";
        $items[$c]['id'] = "personnelMinMaxSalary6";
        $items[$c]['title'] = "بهای تمام شده ماهیانه";
        $items[$c]['id1'] = "personnelMinimumSalary6";
        $items[$c]['id2'] = "personnelMaximumSalary6";
        $items[$c]['placeholder1'] = "حداقل (ریال)";
        $items[$c]['placeholder2'] = "حداکثر (ریال)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelSalaryGroupAchConditions6";
        $items[$c]['title'] = "شرایط احراز گروه 6";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelSalaryGname7";
        $items[$c]['title'] = "نام گروه 7";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 48%;'";
        $items[$c]['id'] = "personnelMinMaxSalary7";
        $items[$c]['title'] = "بهای تمام شده ماهیانه";
        $items[$c]['id1'] = "personnelMinimumSalary7";
        $items[$c]['id2'] = "personnelMaximumSalary7";
        $items[$c]['placeholder1'] = "حداقل (ریال)";
        $items[$c]['placeholder2'] = "حداکثر (ریال)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelSalaryGroupAchConditions7";
        $items[$c]['title'] = "شرایط احراز گروه 7";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelSalaryGname8";
        $items[$c]['title'] = "نام گروه 8";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 48%;'";
        $items[$c]['id'] = "personnelMinMaxSalary8";
        $items[$c]['title'] = "بهای تمام شده ماهیانه";
        $items[$c]['id1'] = "personnelMinimumSalary8";
        $items[$c]['id2'] = "personnelMaximumSalary8";
        $items[$c]['placeholder1'] = "حداقل (ریال)";
        $items[$c]['placeholder2'] = "حداکثر (ریال)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelSalaryGroupAchConditions8";
        $items[$c]['title'] = "شرایط احراز گروه 8";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelSalaryGname9";
        $items[$c]['title'] = "نام گروه 9";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 48%;'";
        $items[$c]['id'] = "personnelMinMaxSalary9";
        $items[$c]['title'] = "بهای تمام شده ماهیانه";
        $items[$c]['id1'] = "personnelMinimumSalary9";
        $items[$c]['id2'] = "personnelMaximumSalary9";
        $items[$c]['placeholder1'] = "حداقل (ریال)";
        $items[$c]['placeholder2'] = "حداکثر (ریال)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelSalaryGroupAchConditions9";
        $items[$c]['title'] = "شرایط احراز گروه 9";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "personnelSalaryGname10";
        $items[$c]['title'] = "نام گروه 10";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "twoText";
        $items[$c]['style'] = "style='width: 48%;'";
        $items[$c]['id'] = "personnelMinMaxSalary10";
        $items[$c]['title'] = "بهای تمام شده ماهیانه";
        $items[$c]['id1'] = "personnelMinimumSalary10";
        $items[$c]['id2'] = "personnelMaximumSalary10";
        $items[$c]['placeholder1'] = "حداقل (ریال)";
        $items[$c]['placeholder2'] = "حداکثر (ریال)";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "personnelSalaryGroupAchConditions10";
        $items[$c]['title'] = "شرایط احراز گروه 10";
        $items[$c]['placeholder'] = "متن";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditPersonnelSalaryGroup";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editSalaryGroupModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++ END OF EDIT SALARY GROUP MODAL ++++++++++++++++++++++++
        //++++++++++++++++++ Start Personnel Cost Info Modal ++++++++++++++++++++++
        $modalID = "personnelManageInfoModal";
        $modalTitle = "سایر اطلاعات";
        $style = "style = 'max-width: 900px;'";
        $styleModal = "style = 'z-index: 1051;'";
        $ShowDescription = 'personnel-manage-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "پرینت";
        $footerBottons[0]['jsf'] = "printPersonnelCosts";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $showPersonnelInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription,'',$styleModal);
        //+++++++++++++++++ End Personnel Cost Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Personnel Cost Info Modal ++++++++++++++++++++++
        $modalID = "personnelManageInfoMModal";
        $modalTitle = "سایر اطلاعات";
        $ShowDescription = 'personnel-manage-MInfo-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showPersonnelMInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Personnel Cost Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ COMPARE PERSONNEL MODAL MODAL ++++++++++++++++++
        $modalID = "personnelSalaryCompareModal";
        $modalTitle = "فرم مقایسه پرسنل در گروه های حقوقی";
        $style = 'style="max-width: 1040px;"';
        $ShowDescription = 'personnel-compare-salaryGroup-body';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "personnelManagmentCompareSalaryGroup";
        $items[$c]['style'] = "style='width: 50%;'";
        $items[$c]['title'] = "انتخاب گروه حقوقی";
        $items[$c]['onchange'] = "onchange=getSalaryGroupPersonnel()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = "گروه 1";
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = "گروه 2";
        $items[$c]['options'][2]["value"] = 2;
        $items[$c]['options'][3]["title"] = "گروه 3";
        $items[$c]['options'][3]["value"] = 3;
        $items[$c]['options'][4]["title"] = "گروه 4";
        $items[$c]['options'][4]["value"] = 4;
        $items[$c]['options'][5]["title"] = "گروه 5";
        $items[$c]['options'][5]["value"] = 5;
        $items[$c]['options'][6]["title"] = "گروه 6";
        $items[$c]['options'][6]["value"] = 6;
        $items[$c]['options'][7]["title"] = "گروه 7";
        $items[$c]['options'][7]["value"] = 7;
        $items[$c]['options'][8]["title"] = "گروه 8";
        $items[$c]['options'][8]["value"] = 8;
        $items[$c]['options'][9]["title"] = "گروه 9";
        $items[$c]['options'][9]["value"] = 9;
        $items[$c]['options'][10]["title"] = "گروه 10";
        $items[$c]['options'][10]["value"] = 10;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "personnelManagmentSalarySubGroup";
        $items[$c]['style'] = "style='width: 50%;'";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "انتخاب پرسنل";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "personnelManagmentCompareMethod";
        $items[$c]['title'] = "مقایسه بر اساس";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "دریافتی ماهیانه بدون احتساب عیدی-پاداش-سنوات	";
        $items[$c]['options'][0]["value"] = 1;
        $items[$c]['options'][1]["title"] = "دریافتی ماهیانه با احتساب عیدی-پاداش-سنوات	";
        $items[$c]['options'][1]["value"] = 2;
        $items[$c]['options'][2]["title"] = "بهای تمام شده ماهیانه برای سازمان";
        $items[$c]['options'][2]["value"] = 3;
        $items[$c]['options'][3]["title"] = "خارج لیست";
        $items[$c]['options'][3]["value"] = 4;

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doComparePersonnelSalaryGroup";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $comparePersonnelSalaryModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++ END OF COMPARE PERSONNEL MODAL ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ EDIT CREATE UNIT MODAL ++++++++++++++++++++++++++++++++
        $modalID = "availableDayManageModal";
        $modalTitle = "فرم ثبت/ویرایش روزهای در دسترس";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "availableDayManageYear";
        $items[$c]['title'] = "سال";
        $items[$c]['placeholder'] = "مثال : 1398";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "availableDayManageTotalDay";
        $items[$c]['title'] = "کل روزهای سال";
        $items[$c]['placeholder'] = "تعداد روز";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "availableDayManageOfficialHolidays";
        $items[$c]['title'] = "تعطیلات رسمی";
        $items[$c]['placeholder'] = "تعداد روز";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "availableDayManageVacations";
        $items[$c]['title'] = "مرخصی ها";
        $items[$c]['placeholder'] = "تعداد روز";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "availableDayManageHiddenADid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateAvailableDay";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateAvailableDayModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++ END OF EDIT CREATE UNIT MODAL ++++++++++++++++++
        //++++++++++++++++++ Start Timing Piece Info Modal ++++++++++++++++++++++
        $modalID = "pieceTimingManageInfoModal";
        $modalTitle = "مقادیر زمان سنجی هر واحد";
        $ShowDescription = 'Piece-Timing-manage-Info-body';
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "industrialManageHiddenPage";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateUnitTimingPiece";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $showPieceTimingInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Timing Piece Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Other Codes Piece Info Modal ++++++++++++++++++++++
        $modalID = "pieceOtherCodesManageInfoModal";
        $modalTitle = "کدهای مرتبط";
        $ShowDescription = 'Piece-OtherCodes-manage-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateOtherPieceCode";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $showOtherCodesPieceInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Other Codes Piece Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Timing Good Info Modal ++++++++++++++++++++++
        $modalID = "goodTimingManageInfoModal";
        $modalTitle = "زمان سنجی مونتاژ";
        $ShowDescription = 'Good-Timing-manage-Info-body';
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "industrialGManageHiddenPage";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateUnitTimingGood";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $showPieceMTimingInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Timing Good Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Create Unit Efficiency MODAL ++++++++++++++++++++++++++++++
        $modalID = "industryManageUnitEfficiencyModal";
        $modalTitle = "فرم ثبت/ویرایش راندمان هر واحد";
        $c = 0;

        $items = array();

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "industryManageUnitEfficiency";
        $items[$c]['title'] = "راندمان";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageIndustryHiddenUEUid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateUnitEfficiency";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $CreateUnitEfficiencyModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++ END Create Unit Efficiency MODAL ++++++++++++++++++++
        //++++++++++++++++++ EDIT CREATE PERCENTAGES MODAL ++++++++++++++++++
        $modalID = "percentagesManageModal";
        $modalTitle = "فرم ثبت/ویرایش درصد ضایعات، بهره و مالیات";
        $style = "style = 'max-width: 505px;'";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "percentagesManageWCasting";
        $items[$c]['title'] = "درصد ضایعات ریخته گری";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "percentagesManageWMachining";
        $items[$c]['title'] = "درصد ضایعات ماشینکاری";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = 'text';
        $items[$c]['id'] = 'percentagesManageWMachiningChips';
        $items[$c]['title'] = 'درصد هدر رفت براده ماشینکاری';
        $items[$c]['placeholder'] = 'درصد';
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "percentagesManageWPolishing";
        $items[$c]['title'] = "درصد ضایعات پرداخت";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "percentagesManageWPolishingSoil";
        $items[$c]['title'] = "درصد هدر رفت خاک پرداخت";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "percentagesManageScount";
        $items[$c]['title'] = "درصد بهره";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "percentagesManageTax";
        $items[$c]['title'] = "درصد مالیات";
        $items[$c]['placeholder'] = "درصد";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "percentagesManageHiddenWid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreatePercentages";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $percentagesManageModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++ END OF EDIT CREATE PERCENTAGES MODAL ++++++++++++++++++++++++
        $htm .= $editCreateCurrencyModal;
        $htm .= $editCreateBrassWeightModal;
        $htm .= $editCreateUnitModal;
        $htm .= $editCreatePersonnelModal;
        $htm .= $editDocModal;
        $htm .= $delModal;
        $htm .= $createAbilityModal;
        $htm .= $showPersonnelInfo;
        $htm .= $showPersonnelMInfo;
        $htm .= $editSalaryGroupModal;
        $htm .= $editCreateAvailableDayModal;
        $htm .= $showPieceTimingInfo;
        $htm .= $showPieceMTimingInfo;
        $htm .= $showOtherCodesPieceInfo;
        $htm .= $CreateUnitEfficiencyModal;
        $htm .= $percentagesManageModal;
        $htm .= $comparePersonnelSalaryModal;
        $htm .= $upload_excel_Modal;
        $htm .= $editCreateRecruitment;
        $htm .= $RecruitmentAddAttachmentFile;

        $send[] = $Access;
        $send[] = $htm;
        return $send;
    }

    public function getAvailableDays(){
        $db = new DBi();
        $sql = "SELECT `AvailableDays` FROM `available_days` ORDER BY `RowID` DESC LIMIT 1";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function getUnits(){
        $db = new DBi();
        $sql = "SELECT `RowID`,`Uname` FROM `official_productive_units`";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            return $res;
        }else{
            return false;
        }
    }
	public function getRecruitmentList($page){
		$db=new DBi();
		$ut=new Utility();
		$sql='SELECT * FROM `recruitment` where status=1';
		$res=$db->ArrayQuery($sql);
		$final_res=[];
		foreach($res as $row){
			$row['inter_view_date']=$ut->greg_to_jal($row['inter_view_date']);
			$final_res[]=$row;
		}
		return $final_res;
		
	}
	public function getRecruitmentListCountRows(){
		$db=new DBi();
		$ut=new Utility();
		$sql='SELECT * FROM `recruitment` where status=1';
		$res=$db->ArrayQuery($sql);
		return count($res);
		
	}
	public function getRecruitment($row_id){
		$db=new DBi();
		$ut=new Utility();
		$sql="SELECT * FROM `recruitment` where RowID={$row_id} AND  status=1 ";
		$res=$db->ArrayQuery($sql);
        foreach($res as $k=>$v){
            $res[$k]['inter_view_date']=$ut->greg_to_jal($res[$k]['inter_view_date']);
        }
		return $res;
		
	}
	
	public function editRecruitment($rid,$fullname,$mobile,$national_code,$description,$recruitment_test_date){
		$db=new DBi();
		$ut=new Utility();
		$sql="UPDATE `recruitment` SET `fullname`='{$fullname}',`national_code`='{$national_code}',mobile={$mobile},`inter_view_date`='{$recruitment_test_date}',`description`='{$description}' WHERE RowID={$rid}";
		$res=$db->Query($sql);
		if($res){
			return true;
		}
		else{
			return false;
		}
	}
	
public function createRecruitment($fullname,$mobile,$national_code,$description,$recruitment_test_date){
	$db=new DBi();
	$ut=new Utility();
	
	$sql="INSERT INTO `recruitment` (`fullname`,`national_code`,`mobile`,`inter_view_date`,`description`)
			VALUES('{$fullname}','{$national_code}','{$mobile}','{$recruitment_test_date}','{$description}')";
	$res=$db->Query($sql);
	if($res){
		return true;
	}
	else{
		return false;
	}
	
}
 public function attachFileToRecruitment($cid,$info,$files){
        $db = new DBi();
        $cDate = date('Y/m/d');
        $cTime = date('H:i:s');

        $SFile = array();
        $allowedTypes = ['png','jpg','jpeg','jfif','pdf','xlsx','docx','zip','rar','wav','PNG','JPG','JPEG','JFIF','PDF','XLSX','DOCX','ZIP','RAR','WAV'];

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
                $SFile[] = "Recruitment" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))
			$directory='../Recruitment/';
		if (!is_dir($directory)) {
            if (!mkdir($directory, 0777, true))
            {
                throw new Exception("Error creating the directory.");
            }
        }
        $cnt = count($SFile);
        for ($i=0;$i<$cnt;$i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i],'../Recruitment/'.$SFile[$i]);
            $sql4 = "INSERT INTO `recruitment_test_attachment` (`pid`,`fileName`,`fileInfo`,`createDate`,`createTime`,`uid`) VALUES ({$cid},'{$SFile[$i]}','{$info}','{$cDate}','{$cTime}',{$_SESSION['userid']})";
            $db->Query($sql4);
        }
        return true;
    }
	public function get_recruitment_uploded_files($cid){
		$db=new DBi();
		$sql="SELECT * FROM recruitment_test_attachment where pid={$cid} AND status=1";
		$res=$db->ArrayQuery($sql);
		$html="";
		if(count($res)>0){
			$html.='<table class="table table-borderd table-striped bg-light">
					<tr>
						<td>ردیف</td>
						<td>نام فایل</td>
						<td>لینک دانلود</td>
						<td>حذف فایل</td>
					</tr>';
					$counter=1;
			foreach($res as $row){
				$html.='<tr>
					<td>'.$counter.'</td>
					<td>'.$row['fileInfo'].'</td>
					<td>
						<a class="btn btn-info" href="Recruitment/'.$row['fileName'].'"target="_blank">
							<i class="fas fa-download"></i>
						</a>
					</td>
					<td><button onclick="delete_recruitment_files('.$row['RowID'].')" class="btn btn-danger"><i class="fa fa-trash"></i></button></td>
				</tr>';
				$counter++;
			}		
			$html.="</table>";
		}
		else{
			$html="<p style='color:red'>فایلی پیوست نشده است</p>";
		}
		return $html;
		
	}
	
	public function delete_recruitment_files($RowID){
		$db=new DBi();
		$ut=new Utility();
		$sql="SELECT filename from recruitment_test_attachment where RowID={$RowID} AND status=1";
		
		$res=$db->ArrayQuery($sql);
		$filename=$res[0]['filename'];
		$Delete_sql="UPDATE recruitment_test_attachment set `status`=0 where RowID={$RowID}";
		$res_del=$db->Query($Delete_sql);
		$directory="../Recruitment/deleted/";
		if (!is_dir($directory)) {
            if (!mkdir($directory, 0777, true))
            {
                throw new Exception("Error creating the directory.");
            }
        }
		if($res_del){
			$ut->fileRecorder($filename);
			rename('../Recruitment/'.$filename, $directory.$filename);
		}
		else{
			return false;
		}
		return true;
		
	}
	
}