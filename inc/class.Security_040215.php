<?php

class Security{

    public function __construct(){
        // do nothing
    }

    public function getSecurityAccessManageHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('securityAccessManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $salary = new Salary();
        $rate = new Rates();

        $personnel = $salary->getAllPersonnel();
        $cntp = count($personnel);

        $units = $rate->getUnits();
        $cntu = count($units);

        $restaurant = $this->getRestaurant();
        $cntr = count($restaurant);

        $meal=$this->getOverTimeMeal();
        $cntm=count($meal);

        $pagename = array();
        $pageIcon = array();
        $contentId = array();
        $menuItems = array();
        $bottons = array();
        $headerSearch = array();
        $access = array();
        $hiddenContentId[] = "hiddenAgencyPrintBody";

        $x = 0;
        $y = 0;
        $z = 0;
        $manifold = 0;
        if($acm->hasAccess('recordEventManage')) {
            $current_date=$ut->greg_to_jal(date('Y-m-d'));
            $current_time=date('H:i:s');
            $pagename[$x] = "ثبت وقایع انتظامات";
            $pageIcon[$x] = "fa-file";
            $contentId[$x] = "recordEventManageBody";
            $menuItems[$x] = 'recordEventManageTabID';

            $bottons1 = array();
            $headerSearch1 = array();

            $b = 0;
            $bottons1[$b]['title'] = "ثبت";
            $bottons1[$b]['jsf'] = "createEvents";
            $bottons1[$b]['icon'] = "fa-plus-square";

            $a = 0;
            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "90px";
            $headerSearch1[$a]['id'] = "RecordEventsManageSDateSearch";
            $headerSearch1[$a]['title'] = "از تاریخ";
            $headerSearch1[$a]['placeholder'] = "از تاریخ";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "90px";
            $headerSearch1[$a]['id'] = "RecordEventsManageEDateSearch";
            $headerSearch1[$a]['title'] = "تا تاریخ";
            $headerSearch1[$a]['placeholder'] = "تا تاریخ";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "220px";
            $headerSearch1[$a]['id'] = "RecordEventsManageDescSearch";
            $headerSearch1[$a]['title'] = "قسمتی از متن شرح واقعه";
            $headerSearch1[$a]['placeholder'] = "قسمتی از متن شرح واقعه";
            $a++;

            $headerSearch1[$a]['type'] = "btn";
            $headerSearch1[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch1[$a]['jsf'] = "showRecordEventsList";

            $bottons[$y] = $bottons1;
            $headerSearch[$z] = $headerSearch1;

            $manifold++;
            $access[] = 1;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('agencyManage')) {
            $pagename[$x] = "مدیریت آژانس";
            $pageIcon[$x] = "fa-car";
            $contentId[$x] = "agencyManageBody";
            $menuItems[$x] = 'agencyManageTabID';

            $bottons2 = array();
            $headerSearch2 = array();

            $b = 0;
            $bottons2[$b]['title'] = "ثبت";
            $bottons2[$b]['jsf'] = "createAgency";
            $bottons2[$b]['icon'] = "fa-plus-square";
            $b++;

            $bottons2[$b]['title'] = "ویرایش";
            $bottons2[$b]['jsf'] = "editAgency";
            $bottons2[$b]['icon'] = "fa-edit";
            $b++;

            $bottons2[$b]['title'] = "پرینت";
            $bottons2[$b]['jsf'] = "printAgency";
            $bottons2[$b]['icon'] = "fa-print";
            $b++;

            $bottons2[$b]['title'] = "خروجی اکسل(تجمیعی)";
            $bottons2[$b]['jsf'] = "getAgencyExcel";
            $bottons2[$b]['icon'] = "fa-file-excel";
            $b++;

            $bottons2[$b]['title'] = "خروجی اکسل(تفکیکی)";
            $bottons2[$b]['jsf'] = "getAgencyExcel2";
            $bottons2[$b]['icon'] = "fa-file-excel";
            $b++;

            $bottons2[$b]['title'] = "هزینه آژانس به تفکیک واحد";
            $bottons2[$b]['jsf'] = "getUnitAgencyAmountExcel";
            $bottons2[$b]['icon'] = "fa-file-excel";

            if($acm->hasAccess('finalTickAgency')) {
                $b++;
                $bottons2[$b]['title'] = "تایید نهایی";
                $bottons2[$b]['jsf'] = "finalTickAgency";
                $bottons2[$b]['icon'] = "fa-check";
            }

            $a = 0;
            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "90px";
            $headerSearch2[$a]['id'] = "agencyManageSDateSearch";
            $headerSearch2[$a]['title'] = "از تاریخ";
            $headerSearch2[$a]['placeholder'] = "از تاریخ";
            $a++;

            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "90px";
            $headerSearch2[$a]['id'] = "agencyManageEDateSearch";
            $headerSearch2[$a]['title'] = "تا تاریخ";
            $headerSearch2[$a]['placeholder'] = "تا تاریخ";
            $a++;

            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "120px";
            $headerSearch2[$a]['id'] = "agencyManageBillNumSearch";
            $headerSearch2[$a]['title'] = "شماره قبض";
            $headerSearch2[$a]['placeholder'] = "شماره قبض";
            $a++;

            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['width'] = "120px";
            $headerSearch2[$a]['id'] = "agencyManageAgencyTypeSearch";
            $headerSearch2[$a]['title'] = "آژانس";
            $headerSearch2[$a]['options'] = array();
            $headerSearch2[$a]['options'][0]["title"] = "نام آژانس";
            $headerSearch2[$a]['options'][0]["value"] = -1;
            $headerSearch2[$a]['options'][1]["title"] = "آژانس پارک";
            $headerSearch2[$a]['options'][1]["value"] = 0;
            $headerSearch2[$a]['options'][2]["title"] = "آژانس پرستو";
            $headerSearch2[$a]['options'][2]["value"] = 1;
            $headerSearch2[$a]['options'][3]["title"] = "اسنپ";
            $headerSearch2[$a]['options'][3]["value"] = 2;
            $headerSearch2[$a]['options'][4]["title"] = "آژانس مسافر (آزادگان)";
            $headerSearch2[$a]['options'][4]["value"] = 3;
            $headerSearch2[$a]['options'][5]["title"] = "آژانس متفرقه";
            $headerSearch2[$a]['options'][5]["value"] = 4;
            $headerSearch2[$a]['options'][6]["title"] = "خودرو خدماتی";
            $headerSearch2[$a]['options'][6]["value"] = 5;
            $a++;

            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['width'] = "120px";
            $headerSearch2[$a]['id'] = "agencyManageServiceTypeSearch";
            $headerSearch2[$a]['title'] = "نوع خدمت";
            $headerSearch2[$a]['options'] = array();
            $headerSearch2[$a]['options'][0]["title"] = "نوع سرویس";
            $headerSearch2[$a]['options'][0]["value"] = -1;
            $headerSearch2[$a]['options'][1]["title"] = "ماموریت";
            $headerSearch2[$a]['options'][1]["value"] = 0;
            $headerSearch2[$a]['options'][2]["title"] = "اضافه کار";
            $headerSearch2[$a]['options'][2]["value"] = 1;
            $headerSearch2[$a]['options'][3]["title"] = "میهمان";
            $headerSearch2[$a]['options'][3]["value"] = 2;
            $headerSearch2[$a]['options'][4]["title"] = "شیفت کاری";
            $headerSearch2[$a]['options'][4]["value"] = 3;
            $headerSearch2[$a]['options'][5]["title"] = "ارسال کالا";
            $headerSearch2[$a]['options'][5]["value"] = 4;
            $headerSearch2[$a]['options'][6]["title"] = "کنسل شده";
            $headerSearch2[$a]['options'][6]["value"] = 5;
            $a++;

            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['width'] = "120px";
            $headerSearch2[$a]['id'] = "agencyManagePersonnelSearch";
            $headerSearch2[$a]['title'] = "پرسنل";
            $headerSearch2[$a]['options'] = array();
            $headerSearch2[$a]['options'][0]["title"] = "پرسنل";
            $headerSearch2[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cntp;$i++){
                $headerSearch2[$a]['options'][$i+1]["title"] = $personnel[$i]['Fname'].' '.$personnel[$i]['Lname'];
                $headerSearch2[$a]['options'][$i+1]["value"] = $personnel[$i]['RowID'];
            }
            $a++;

            $headerSearch2[$a]['type'] = "btn";
            $headerSearch2[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch2[$a]['jsf'] = "showAgencyManageList";

            $bottons[$y] = $bottons2;
            $headerSearch[$z] = $headerSearch2;

            $manifold++;
            $access[] = 2;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('foodItemsManage')) {
            $pagename[$x] = "رستوران ها";
            $pageIcon[$x] = "fa-utensils";
            $contentId[$x] = "restaurantManageBody";
            $menuItems[$x] = 'restaurantManageTabID';

            $bottons3 = array();
            $headerSearch3 = array();

            $b = 0;
            $bottons3[$b]['title'] = "ثبت";
            $bottons3[$b]['jsf'] = "createRestaurant";
            $bottons3[$b]['icon'] = "fa-plus-square";
            $b++;

            $bottons3[$b]['title'] = "ویرایش";
            $bottons3[$b]['jsf'] = "editRestaurant";
            $bottons3[$b]['icon'] = "fa-edit";

            $bottons[$y] = $bottons3;
            $headerSearch[$z] = $headerSearch3;

            $manifold++;
            $access[] = 3;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('foodItemsManage')) {
            $pagename[$x] = "غذاها";
            $pageIcon[$x] = "fa-pizza-slice";
            $contentId[$x] = "foodManageBody";
            $menuItems[$x] = 'foodManageTabID';

            $bottons4 = array();
            $headerSearch4 = array();

            $b = 0;
            $bottons4[$b]['title'] = "ثبت";
            $bottons4[$b]['jsf'] = "createFood";
            $bottons4[$b]['icon'] = "fa-plus-square";
            $b++;

            $bottons4[$b]['title'] = "ویرایش";
            $bottons4[$b]['jsf'] = "editFood";
            $bottons4[$b]['icon'] = "fa-edit";

            $bottons[$y] = $bottons4;
            $headerSearch[$z] = $headerSearch4;

            $manifold++;
            $access[] = 4;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('foodItemsManage')) {
            $pagename[$x] = "نوشیدنی";
            $pageIcon[$x] = "fa-wine-bottle";
            $contentId[$x] = "drinkManageBody";
            $menuItems[$x] = 'drinkManageTabID';

            $bottons5 = array();
            $headerSearch5 = array();

            $b = 0;
            $bottons5[$b]['title'] = "ثبت";
            $bottons5[$b]['jsf'] = "createDrink";
            $bottons5[$b]['icon'] = "fa-plus-square";
            $b++;

            $bottons5[$b]['title'] = "ویرایش";
            $bottons5[$b]['jsf'] = "editDrink";
            $bottons5[$b]['icon'] = "fa-edit";

            $bottons[$y] = $bottons5;
            $headerSearch[$z] = $headerSearch5;

            $manifold++;
            $access[] = 5;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('foodItemsManage')) {
            $pagename[$x] = "مسیرهای سرویس دهی";
            $pageIcon[$x] = "fa-bus";
            $contentId[$x] = "serviceRouteManageBody";
            $menuItems[$x] = 'serviceRouteManageTabID';

            $bottons6 = array();
            $headerSearch6 = array();

            $b = 0;
            $bottons6[$b]['title'] = "ثبت";
            $bottons6[$b]['jsf'] = "createServiceRoute";
            $bottons6[$b]['icon'] = "fa-plus-square";
            $b++;

            $bottons6[$b]['title'] = "ویرایش";
            $bottons6[$b]['jsf'] = "editServiceRoute";
            $bottons6[$b]['icon'] = "fa-edit";
            $b++;

            $bottons6[$b]['title'] = "لیست پرسنل";
            $bottons6[$b]['jsf'] = "personnelServiceRoute";
            $bottons6[$b]['icon'] = "fa-plus-square";

            $bottons[$y] = $bottons6;
            $headerSearch[$z] = $headerSearch6;

            $manifold++;
            $access[] = 6;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('overtimeLunchManage')) {
            $pagename[$x] = "ثبت ناهار اضافه کار";
            $pageIcon[$x] = "fa-burger-soda";
            $contentId[$x] = "overtimeLunchManageBody";
            $menuItems[$x] = 'overtimeLunchManageTabID';

            $bottons7 = array();
            $headerSearch7 = array();

            $b = 0;
            $bottons7[$b]['title'] = "ثبت ناهار پرسنل";
            $bottons7[$b]['jsf'] = "createOvertimeLunchPersonnel";
            $bottons7[$b]['icon'] = "fa-plus-square";
            $b++;

            $bottons7[$b]['title'] = "ثبت ناهار میهمان";
            $bottons7[$b]['jsf'] = "createOvertimeLunchGuest";
            $bottons7[$b]['icon'] = "fa-plus-square";
            $b++;

            $bottons7[$b]['title'] = "پرینت";
            $bottons7[$b]['jsf'] = "printOvertimeLunch";
            $bottons7[$b]['icon'] = "fa-print";
            $b++;

            $bottons7[$b]['title'] = "خروجی اکسل";
            $bottons7[$b]['jsf'] = "getOvertimeLunchExcel";
            $bottons7[$b]['icon'] = "fa-file-excel";
            $b++;

            $bottons7[$b]['title'] = "هزینه ناهار به تفکیک واحد";
            $bottons7[$b]['jsf'] = "getUnitOvertimeLunchExcel";
            $bottons7[$b]['icon'] = "fa-file-excel";

            if($acm->hasAccess('overtimeLunchDetailsManage')) {
                $b++;
                $bottons7[$b]['title'] = "ثبت جزئیات ناهار";
                $bottons7[$b]['jsf'] = "createOvertimeLunchDetails";
                $bottons7[$b]['icon'] = "fa-plus-square";
            }

            if($acm->hasAccess('finalTickLunch')) {
                $b++;
                $bottons7[$b]['title'] = "تایید نهایی";
                $bottons7[$b]['jsf'] = "finalTickLunch";
                $bottons7[$b]['icon'] = "fa-check";
            }

            $a = 0;
            $headerSearch7[$a]['type'] = "text";
            $headerSearch7[$a]['width'] = "90px";
            $headerSearch7[$a]['id'] = "overtimeLunchManageSDateSearch";
            $headerSearch7[$a]['title'] = "از تاریخ";
            $headerSearch7[$a]['placeholder'] = "از تاریخ";
            $a++;

            $headerSearch7[$a]['type'] = "text";
            $headerSearch7[$a]['width'] = "90px";
            $headerSearch7[$a]['id'] = "overtimeLunchManageEDateSearch";
            $headerSearch7[$a]['title'] = "تا تاریخ";
            $headerSearch7[$a]['placeholder'] = "تا تاریخ";
            $a++;

            $headerSearch7[$a]['type'] = "select";
            $headerSearch7[$a]['width'] = "120px";
            $headerSearch7[$a]['id'] = "overtimeLunchManagePersonnelSearch";
            $headerSearch7[$a]['title'] = "پرسنل";
            $headerSearch7[$a]['options'] = array();
            $headerSearch7[$a]['options'][0]["title"] = "پرسنل";
            $headerSearch7[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cntp;$i++){
                $headerSearch7[$a]['options'][$i+1]["title"] = $personnel[$i]['Fname'].' '.$personnel[$i]['Lname'];
                $headerSearch7[$a]['options'][$i+1]["value"] = $personnel[$i]['RowID'];
            }
            $a++;

            $headerSearch7[$a]['type'] = "select";
            $headerSearch7[$a]['width'] = "120px";
            $headerSearch7[$a]['id'] = "overtimeLunchManageRestaurantSearch";
            $headerSearch7[$a]['title'] = "رستوران";
            $headerSearch7[$a]['options'] = array();
            $headerSearch7[$a]['options'][0]["title"] = "رستوران";
            $headerSearch7[$a]['options'][0]["value"] = -1;
            for ($i=0;$i<$cntr;$i++){
                $headerSearch7[$a]['options'][$i+1]["title"] = $restaurant[$i]['restaurant_Name'];
                $headerSearch7[$a]['options'][$i+1]["value"] = $restaurant[$i]['RowID'];
            }
            $a++;

            $headerSearch7[$a]['type'] = "select";
            $headerSearch7[$a]['width'] = "120px";
             $headerSearch7[$a]['width'] = "120px";
            $headerSearch7[$a]['id'] = "overtimeLunchManageMealSearch";
            $headerSearch7[$a]['title'] = "وعده غذایی";
            $headerSearch7[$a]['options'] = array();
            $headerSearch7[$a]['options'][0]["title"] = "وعده غذایی";
            $headerSearch7[$a]['options'][0]["value"] = -1;
            for($k=0;$k<$cntm;$k++){
                //$meal=$this->getOverTimeMeal();
                $headerSearch7[$a]['options'][$k+1]["title"] = $meal[$k]['meal'];
                $headerSearch7[$a]['options'][$k+1]["value"] = $meal[$k]['meal_id'];
            }
            // $headerSearch7[$a]['options'][1]["title"] = "ناهار";
            // $headerSearch7[$a]['options'][1]["value"] = 0;
            // $headerSearch7[$a]['options'][2]["title"] = "شام";
            // $headerSearch7[$a]['options'][2]["value"] = 1;
            // $headerSearch7[$a]['options'][3]["title"] = 'بدون ناهار';

            //$headerSearch7[$a]['options'][3]["value"] = 2;
            $a++;

            $headerSearch7[$a]['type'] = "btn";
            $headerSearch7[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch7[$a]['jsf'] = "showOvertimeLunchManageList";

            $bottons[$y] = $bottons7;
            $headerSearch[$z] = $headerSearch7;

            $manifold++;
            $access[] = 7;
        }
        $htm = $ut->getHtmlOfDefaultManagementMultiPage($pagename,$pageIcon,$contentId,$menuItems,$bottons,$manifold,$headerSearch,$hiddenContentId);
        //++++++++++++++++++++++++++++++++++ CREATE Record Events MODAL++++++++++++++++++++++++++++++++
        $modalID = "recordEventsManageModal";
        $modalTitle = "فرم ثبت وقایع انتظامات";
        $style = 'style="max-width: 600px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "recordEventsManageTime";
        $items[$c]['style'] = "style='width:50%;float:right;'";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['data-format'] = "data-format='hh:mm'";
        $items[$c]['add-on'] = "yes";
        $items[$c]['title'] = "ساعت";
        $items[$c]['placeholder'] = "ساعت";
        $items[$c]['value'] = "value='{$current_time}'";
        
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "recordEventsManageDate";
        $items[$c]['title'] = "تاریخ واقعه";
        $items[$c]['placeholder'] = "";
        $items[$c]['value'] = "value='{$current_date}'";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "recordEventsManageDesc";
        $items[$c]['title'] = "شرح واقعه";
        $items[$c]['placeholder'] = "متن را اینجا بنویسید.";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "recordEventsManageHiddenEid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateEvents";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createRecordEventsModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF CREATE Record Events MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Edit CREATE MODAL ++++++++++++++++++++++++++++++++
        $modalID = "agencyManageModal";
        $modalTitle = "فرم ثبت/ویرایش آژانس ها";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "agencyManageCreateDate";
        $items[$c]['style'] = "style='width: 60%;float: right;'";
        $items[$c]['title'] = "تاریخ";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "agencyManageBillNumber";
        $items[$c]['title'] = "شماره قبض";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "agencyManageAgencyID";
        $items[$c]['title'] = "نام آژانس";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "آژانس پارک";
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = "آژانس پرستو";
        $items[$c]['options'][2]["value"] = 1;
        $items[$c]['options'][3]["title"] = "اسنپ";
        $items[$c]['options'][3]["value"] = 2;
        $items[$c]['options'][4]["title"] = "آژانس مسافر (آزادگان)";
        $items[$c]['options'][4]["value"] = 3;
        $items[$c]['options'][5]["title"] = "آژانس متفرقه";
        $items[$c]['options'][5]["value"] = 4;
        $items[$c]['options'][6]["title"] = "خودرو خدماتی";
        $items[$c]['options'][6]["value"] = 5;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "agencyManageServiceID";
        $items[$c]['title'] = "نوع سرویس";
        $items[$c]['onchange'] = "onchange=getPassengerOrGuest()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "ماموریت";
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = "اضافه کار";
        $items[$c]['options'][2]["value"] = 1;
        $items[$c]['options'][3]["title"] = "میهمان";
        $items[$c]['options'][3]["value"] = 2;
        $items[$c]['options'][4]["title"] = "شیفت کاری";
        $items[$c]['options'][4]["value"] = 3;
        $items[$c]['options'][5]["title"] = "ارسال کالا";
        $items[$c]['options'][5]["value"] = 4;
        $items[$c]['options'][6]["title"] = "کنسل شده";
        $items[$c]['options'][6]["value"] = 5;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "agencyManageStopMinute";
        $items[$c]['title'] = "توقف به دقیقه";
        $items[$c]['placeholder'] = "دقیقه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "agencyManageAmount";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['title'] = "مبلغ";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "agencyManagePassenger";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "نام مسافر/مسافرین";
        $items[$c]['options'] = array();
        for ($i=0;$i<$cntp;$i++){
            $items[$c]['options'][$i]["title"] = $personnel[$i]['Fname'].' '.$personnel[$i]['Lname'];
            $items[$c]['options'][$i]["value"] = $personnel[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "agencyManageGuest";
        $items[$c]['title'] = "نام میهمان/میهمانان";
        $items[$c]['placeholder'] = "نام میهمان/میهمانان";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "agencyManageDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "agencyManageHiddenAid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateAgency";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++ END OF Edit CREATE MODAL ++++++++++++++++++
        //++++++++++++++++++ agency Attachment File Modal ++++++++++++++++++++++
        $modalID = "agencyAttachmentFileModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'agencyAttachmentFile-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "file";
        $items[$c]['id'] = "agencyAttachmentFileAttached";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PNG , JPG , JPEG , JFIF , PDF باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "agencyAttachmentFileAttachedID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachedFileToAgency";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $agencyAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End agency Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ final tick agency MODAL ++++++++++++++++++++++++++++++++
        $modalID = "finalTickAgencyModal";
        $modalTitle = "فرم تایید نهایی آژانس ها";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "finalTickAgencySDate";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "از تاریخ";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "finalTickAgencyEDate";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "تا تاریخ";
        $items[$c]['placeholder'] = "تاریخ";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید نهایی";
        $footerBottons[0]['jsf'] = "doFinalTickAgency";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $finalTickAgency = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++ END OF final tick agency MODAL ++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Edit CREATE Restaurant MODAL ++++++++++++++++++++++++++++++++
        $modalID = "restaurantManageModal";
        $modalTitle = "فرم ثبت/ویرایش رستوران ها";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "restaurantManageRName";
        $items[$c]['title'] = "نام رستوران";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "restaurantManageHiddenRid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateRestaurant";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateRestaurantModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++ END OF Edit CREATE Restaurant MODAL ++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Edit CREATE Food MODAL ++++++++++++++++++++++++++++++++
        $modalID = "foodManageModal";
        $modalTitle = "فرم ثبت/ویرایش غذاها";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "foodManageFName";
        $items[$c]['title'] = "نام غذا";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['id'] = "foodManageAmount";
        $items[$c]['title'] = "قیمت";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "foodManageHiddenFid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateFood";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateFoodModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++ END OF Edit CREATE Food MODAL ++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Edit CREATE Drink MODAL ++++++++++++++++++++++++++++++++
        $modalID = "drinkManageModal";
        $modalTitle = "فرم ثبت/ویرایش نوشیدنی ها";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "drinkManageDName";
        $items[$c]['title'] = "نام نوشیدنی";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['id'] = "drinkManageAmount";
        $items[$c]['title'] = "قیمت";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "drinkManageHiddenDid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateDrink";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateDrinkModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++ END OF Edit CREATE Drink MODAL ++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Edit CREATE Service Route MODAL ++++++++++++++++++++++++++++++++
        $modalID = "serviceRouteManageModal";
        $modalTitle = "فرم ثبت/ویرایش مسیر ها";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "serviceRouteManageRName";
        $items[$c]['title'] = "نام مسیر";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "serviceRouteManageHiddenSRid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateServiceRoute";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateServiceRouteModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++ END OF Edit CREATE Service Route MODAL ++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Personnel Service Route MODAL ++++++++++++++++++++++++++++++++
        $modalID = "personnelServiceRouteModal";
        $modalTitle = "فرم ثبت مسیر پرسنل";
        $style = 'style="max-width: 1000px;"';
        $ShowDescription = 'personnelServiceRouteBody';

        $items = array();

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doPersonnelServiceRoute";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $personnelServiceRoute = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++ END OF Personnel Service Route MODAL MODAL ++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ create Overtime Lunch Personnel MODAL ++++++++++++++++++++++++++++++++
        $modalID = "overtimeLunchPersonnelModal";
        $modalTitle = "فرم ثبت ناهار پرسنل";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "overtimeLunchManageDate";
        $items[$c]['disabled'] = ($acm->hasAccess('overtimeLunchDetailsManage') ? '' : 'disabled');
        $items[$c]['title'] = "تاریخ";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        // $items[$c]['type'] = "select";
        // $items[$c]['id'] = "overtimeLunchManageUnit";
        // $items[$c]['title'] = "واحد";
        // $items[$c]['onchange'] = "onchange=getPersonnelOfUnit()";
        // $items[$c]['options'] = array();
        // $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        // $items[$c]['options'][0]["value"] = 0;
        // for ($i=0;$i<$cntu;$i++){
        //     $items[$c]['options'][$i+1]["title"] = $units[$i]['Uname'];
        //     $items[$c]['options'][$i+1]["value"] = $units[$i]['RowID'];
        // }
        // $c++;

        // $items[$c]['type'] = "select";
        // $items[$c]['id'] = "overtimeLunchManagerestaurant";
        // $items[$c]['title'] = "نام رستوران";
        // //$items[$c]['onchange'] = "onchange=getPersonnelOfUnit()";
        // $items[$c]['options'] = array();
        // $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        // $items[$c]['options'][0]["value"] = 0;
        // $restaurant=$this->getRestaurant();
        // for ($i=0;$i<count($restaurant);$i++){
        //     $items[$c]['options'][$i+1]["title"] = $restaurant[$i]['restaurant_Name'];
        //     $items[$c]['options'][$i+1]["value"] = $restaurant[$i]['RowID'];
        // }
        // $c++;


        $items[$c]['type'] = "select";
        $items[$c]['id'] = "overtimeLunchManagePersonnel";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "پرسنل";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "overtimeLunchManageMeal";
        $items[$c]['title'] = "وعده";
        $items[$c]['onchange'] = "onchange='toggle_restaurant_div(this)'";

        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        for($k=0;$k<$cntm;$k++){
            //$meal=$this->getOverTimeMeal();
            $items[$c]['options'][$k+1]["title"] = $meal[$k]['meal'];
            $items[$c]['options'][$k+1]["value"] = $meal[$k]['meal_id'];
        }
        // $items[$c]['options'][1]["title"] = 'ناهار';
        // $items[$c]['options'][1]["value"] = 0;
        // $items[$c]['options'][2]["title"] = 'شام';
        // $items[$c]['options'][2]["value"] = 1;
		// $items[$c]['options'][3]["title"] = 'بدون ناهار';
        // $items[$c]['options'][3]["value"] = 2;
            $c++;
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "overtimeLunchManagerestaurant";
        $items[$c]['title'] = "نام رستوران";
        //$items[$c]['onchange'] = "onchange=getPersonnelOfUnit()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        $restaurant=$this->getRestaurant();
        for ($i=0;$i<count($restaurant);$i++){
            $items[$c]['options'][$i+1]["title"] = $restaurant[$i]['restaurant_Name'];
            $items[$c]['options'][$i+1]["value"] = $restaurant[$i]['RowID'];
        }
        $c++;

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCreateOvertimeLunchPersonnel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $overtimeLunchPersonnelModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++ END OF create Overtime Lunch Personnel MODAL ++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ create Overtime Lunch Guest MODAL ++++++++++++++++++++++++++++++++
        $modalID = "overtimeLunchGuestModal";
        $modalTitle = "فرم ثبت ناهار میهمان";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "overtimeLunchGuestDate";
        $items[$c]['disabled'] = ($acm->hasAccess('overtimeLunchDetailsManage') ? '' : 'disabled');
        $items[$c]['title'] = "تاریخ";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "overtimeLunchGuestUnit";
        $items[$c]['title'] = "واحد";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cntu;$i++){
            $items[$c]['options'][$i+1]["title"] = $units[$i]['Uname'];
            $items[$c]['options'][$i+1]["value"] = $units[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "overtimeLunchGuestFName";
        $items[$c]['title'] = "نام میهمان";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "overtimeLunchGuestLName";
        $items[$c]['title'] = "نام خانوادگی میهمان";
        $items[$c]['placeholder'] = "نام خانوادگی";
        $c++;
		//***********************************
		/* $items[$c]['type'] = "text";
        $items[$c]['id'] = "overtimeLunchGuestLName";
        $items[$c]['title'] = "نام رستوران";
        $items[$c]['placeholder'] = "نام خانوادگی";
        $c++; */
		
		$items[$c]['type'] = "select";
        $items[$c]['id'] = "overtimeLunchManagerestaurantGest";
        $items[$c]['title'] = "نام رستوران";
        //$items[$c]['onchange'] = "onchange=getPersonnelOfUnit()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        $restaurant=$this->getRestaurant();
        for ($i=0;$i<count($restaurant);$i++){
            $items[$c]['options'][$i+1]["title"] = $restaurant[$i]['restaurant_Name'];
            $items[$c]['options'][$i+1]["value"] = $restaurant[$i]['RowID'];
        }
        $c++;
		//***********************************

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "overtimeLunchGuestDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCreateOvertimeLunchGuest";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $overtimeLunchGuestModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++ END OF create Overtime Lunch Guest MODAL ++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ overtime Lunch Details MODAL ++++++++++++++++++++++++++++++++
        $modalID = "overtimeLunchDetailsModal";
        $modalTitle = "فرم ثبت جزئیات ناهار اضافه کار";
        $style = 'style="max-width: 1000px;"';
        $ShowDescription = 'overtimeLunchDetailsBody';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "overtimeLunchDetailsDate";
        $items[$c]['onchange'] = "onchange=getLunchOfPersonnel()";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ ثبت ناهار اضافه کار";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "overtimeLunchDetailsBreadNum";
        $items[$c]['style'] = "style='width: 50%;'";
        $items[$c]['title'] = "تعداد نان";
        $items[$c]['placeholder'] = "تعداد";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCreateOvertimeLunchDetails";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $overtimeLunchDetails = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++ END OF overtime Lunch Details MODAL ++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ final tick lunch MODAL ++++++++++++++++++++++++++++++++
        $modalID = "finalTickLunchModal";
        $modalTitle = "فرم تایید نهایی ناهار ها";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "finalTickLunchSDate";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "از تاریخ";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "finalTickLunchEDate";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "تا تاریخ";
        $items[$c]['placeholder'] = "تاریخ";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید نهایی";
        $footerBottons[0]['jsf'] = "doFinalTickLunch";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $finalTickLunch = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++ END OF final tick lunch MODAL ++++++++++++++++++
        $htm .= $createRecordEventsModal;
        $htm .= $editCreateModal;
        $htm .= $agencyAttachmentFile;
        $htm .= $finalTickAgency;
        $htm .= $editCreateRestaurantModal;
        $htm .= $editCreateFoodModal;
        $htm .= $editCreateDrinkModal;
        $htm .= $editCreateServiceRouteModal;
        $htm .= $personnelServiceRoute;
        $htm .= $overtimeLunchPersonnelModal;
        $htm .= $overtimeLunchGuestModal;
        $htm .= $overtimeLunchDetails;
        $htm .= $finalTickLunch;
        $send = array($htm,$access);
        return $send;
    }

    private function getRestaurant(){
        $db = new DBi();
        $sql = "SELECT * FROM `restaurant` where `status`=1";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    private function getOverTimeMeal(){
        $db = new DBi();
        $sql = "SELECT `meal_id`,`meal` FROM `over_time_meal` where `status`=1";
        $res = $db->ArrayQuery($sql);
        return $res;
    }
    
    //******************** ثبت وقایع انتظامات ********************

    public function getRecordEventsList($sDate,$eDate,$desc,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('recordEventManage')){
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
            $w[] = '`cDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`cDate` <="'.$eDate.'" ';
        }
        if(strlen(trim($desc)) > 0){
            $w[] = '`event` LIKE "%'.$desc.'%" ';
        }

        $sql = "SELECT * FROM `info`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `cDate` DESC,`cTime` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$y]['uid']}";
            $rst = $db->ArrayQuery($query);
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['cDate'] = $ut->greg_to_jal($res[$y]['cDate']);
            $finalRes[$y]['cTime'] = $res[$y]['cTime'];
            $finalRes[$y]['event'] = $res[$y]['event'];
            $finalRes[$y]['name'] = $rst[0]['fname'].' '.$rst[0]['lname'];
        }
        return array_values($finalRes);
    }

    public function getRecordEventsListCountRows($sDate,$eDate,$desc){
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`cDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`cDate` <="'.$eDate.'" ';
        }
        if(strlen(trim($desc)) > 0){
            $w[] = '`event` LIKE "%'.$desc.'%" ';
        }

        $sql = "SELECT `RowID` FROM `info`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function eventsInfo($infoID){
        $db = new DBi();
        $ut=new Utility();
        $sql = "SELECT * FROM `info` WHERE `RowID`=".$infoID;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("infoID"=>$infoID,"cTime"=>$res[0]['cTime'],"event"=>$res[0]['event'],"event_date"=>$ut->greg_to_jal($res[0]['cDate']));
            return $res;
        }else{
            return false;
        }
    }

    public function createEvents($cTime,$desc,$rDate){
        $acm = new acm();
        $ut=new Utility();
        if(!$acm->hasAccess('recordEventManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $h = date(H);
        $cDate = date('Y-m-d');
        if ($h == '00'){
            $cDate = date('Y-m-d',strtotime("yesterday"));
        }
        $rDate=$ut->jal_to_greg($rDate);
        $sql = "INSERT INTO `info` (`uid`,`cTime`,`cDate`,`event`,`rDate`) VALUES ({$_SESSION['userid']},'{$cTime}','{$rDate}','{$desc}','{$cDate}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editEvents($eid,$cTime,$desc,$cDate){
        $acm = new acm();
        $ut=new Utility();
        if(!$acm->hasAccess('recordEventManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sql = "SELECT * FROM `info` WHERE `RowID`={$eid}";
        $res = $db->ArrayQuery($sql);

        $sql1 = "INSERT INTO `info_backup` (`infoID`,`uid`,`cTime`,`cDate`,`event`) VALUES ({$eid},{$res[0]['uid']},'{$res[0]['cTime']}','{$res[0]['cDate']}','{$res[0]['event']}')";
        $db->Query($sql1);

        $sql2 = "UPDATE `info` SET `uid`={$_SESSION['userid']},`cTime`='{$cTime}',`event`='{$desc}',`cDate`='{$ut->jal_to_greg($cDate)}' WHERE `RowID`={$eid}";
        $ut->fileRecorder($sql2);
        $db->Query($sql2);

        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    //******************** مدیریت آژانس ها ********************

    public function getAgencyManageList($sDate,$eDate,$billNum,$agencyType,$serviceType,$personnel,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('agencyManage')){
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
            $w[] = '`createDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`createDate` <="'.$eDate.'" ';
        }
        if(strlen(trim($billNum)) > 0){
            $w[] = '`billNumber`="'.$billNum.'" ';
        }
        if(intval($agencyType) >= 0){
            $w[] = '`agencyID`='.$agencyType.' ';
        }
        if(intval($serviceType) >= 0){
            $w[] = '`serviceID`='.$serviceType.' ';
        }

        $sql = "SELECT * FROM `agency`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        if(intval($personnel) > 0){
            $sql .= " ORDER BY `createDate` DESC ";
        }else{
            $sql .= " ORDER BY `createDate` DESC LIMIT $start,".$numRows;
        }

        $ut->fileRecorder($sql);
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $passenger = array();
            $afrad = explode(',',$res[$y]['passenger']);
            if(intval($personnel) > 0){
                if (!in_array($personnel,$afrad)){
                    continue;
                }
            }
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['createDate'] = (strtotime($res[$y]['createDate']) > 0 ? $ut->greg_to_jal($res[$y]['createDate']) : '');
            $finalRes[$y]['billNumber'] = $res[$y]['billNumber'];
            $query = "SELECT `Fname`,`Lname` FROM `personnel` WHERE `RowID` IN ({$res[$y]['passenger']})";
            $rst = $db->ArrayQuery($query);
            $cnt = count($rst);
            for ($i=0;$i<$cnt;$i++){
                $passenger[] = $rst[$i]['Fname'].' '.$rst[$i]['Lname'];
            }
            $passenger = implode(' - ',$passenger);
            $finalRes[$y]['passenger'] = $passenger;
            $finalRes[$y]['guest'] = $res[$y]['guest'];
            $finalRes[$y]['stopMinute'] = $res[$y]['stopMinute'];
            $finalRes[$y]['amount'] = (intval($res[$y]['amount']) > 0 ? number_format($res[$y]['amount']).' ریال' : '');
            $finalRes[$y]['description'] = $res[$y]['description'];
            $finalRes[$y]['status'] = ($res[$y]['finalTick'] == 0 ? 'عدم تایید' : 'تایید شده');
            $finalRes[$y]['txtco'] = ($res[$y]['finalTick'] == 0 ? 'red' : 'green');
            switch ($res[$y]['agencyID']){
                case 0:
                    $finalRes[$y]['agencyID'] = 'آژانس پارک';
                    break;
                case 1:
                    $finalRes[$y]['agencyID'] = 'آژانس پرستو';
                    break;
                case 2:
                    $finalRes[$y]['agencyID'] = 'اسنپ';
                    break;
                case 3:
                    $finalRes[$y]['agencyID'] = 'آژانس مسافر (آزادگان)';
                    break;
                case 4:
                    $finalRes[$y]['agencyID'] = 'آژانس متفرقه';
                    break;
                case 5:
                    $finalRes[$y]['agencyID'] = 'خودرو خدماتی';
                    break;
            }
            switch ($res[$y]['serviceID']){
                case 0:
                    $finalRes[$y]['serviceID'] = 'ماموریت';
                    break;
                case 1:
                    $finalRes[$y]['serviceID'] = 'اضافه کار';
                    break;
                case 2:
                    $finalRes[$y]['serviceID'] = 'میهمان';
                    break;
                case 3:
                    $finalRes[$y]['serviceID'] = 'شیفت کاری';
                    break;
                case 4:
                    $finalRes[$y]['serviceID'] = 'ارسال کالا';
                    break;
                case 5:
                    $finalRes[$y]['serviceID'] = 'کنسل شده';
                    break;
            }
        }
        return array_values($finalRes);
    }

    public function getAgencyManageListCountRows($sDate,$eDate,$billNum,$agencyType,$serviceType,$personnel){
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`createDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`createDate` <="'.$eDate.'" ';
        }
        if(strlen(trim($billNum)) > 0){
            $w[] = '`billNumber`="'.$billNum.'" ';
        }
        if(intval($agencyType) >= 0){
            $w[] = '`agencyID`='.$agencyType.' ';
        }
        if(intval($serviceType) >= 0){
            $w[] = '`serviceID`='.$serviceType.' ';
        }

        $sql = "SELECT `RowID`,`passenger` FROM `agency`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++) {
            $afrad = explode(',', $res[$y]['passenger']);
            if (intval($personnel) > 0) {
                if (!in_array($personnel, $afrad)) {
                    continue;
                }
            }
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
        }
        $finalRes = array_values($finalRes);
        return count($finalRes);
    }

    public function agencyInfo($aid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `agency` WHERE `RowID`=".$aid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $createDate = (strtotime($res[0]['createDate']) > 0 ? $ut->greg_to_jal($res[0]['createDate']) : '');
            $res = array("aid"=>$aid,"bNumber"=>$res[0]['billNumber'],"passenger"=>$res[0]['passenger'],"agencyID"=>$res[0]['agencyID'],"serviceID"=>$res[0]['serviceID'],"stopMinute"=>$res[0]['stopMinute'],"createDate"=>$createDate,"amount"=>number_format($res[0]['amount']),"description"=>$res[0]['description'],"guest"=>$res[0]['guest']);
            return $res;
        }else{
            return false;
        }
    }

    public function createAgency($cDate,$billNum,$agencyID,$serviceID,$stopMinute,$amount,$passenger,$guest,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('agencyManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $cDate = $ut->jal_to_greg($cDate);

        $sql = "INSERT INTO `agency` (`createDate`,`billNumber`,`passenger`,`agencyID`,`serviceID`,`stopMinute`,`amount`,`description`,`uid`,`guest`) VALUES ('{$cDate}','{$billNum}','{$passenger}',{$agencyID},{$serviceID},{$stopMinute},{$amount},'{$desc}',{$_SESSION['userid']},'{$guest}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editAgency($aid,$cDate,$billNum,$agencyID,$serviceID,$stopMinute,$amount,$passenger,$guest,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('agencyManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $query = "SELECT `finalTick` FROM `agency` WHERE `RowID`={$aid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['finalTick']) == 1){
            $res = "این آژانس تایید نهایی شده است و امکان ویرایش ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $cDate = $ut->jal_to_greg($cDate);
        $sql = "UPDATE `agency` SET `createDate`='{$cDate}',`billNumber`='{$billNum}',`passenger`='{$passenger}',`agencyID`={$agencyID},`serviceID`={$serviceID},`stopMinute`={$stopMinute},`amount`={$amount},`description`='{$desc}',`uid`={$_SESSION['userid']},`guest`='{$guest}' WHERE `RowID`={$aid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function attachedAgencyFileHtm($aid){
        $db = new DBi();
        $sql = "SELECT `RowID`,`fileName` FROM `agency` WHERE `RowID`={$aid}";
        $res = $db->ArrayQuery($sql);
        $htm = '';
        if (strlen(trim($res[0]['fileName'])) > 0) {
            $cnt = count($res);
            $iterator = 0;
            $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileAgency-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">لینک دانلود</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">حذف فایل</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';
            for ($i = 0; $i < $cnt; $i++) {
                $iterator++;
                $link = ADDR . 'attachment/' . $res[$i]['fileName'];
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachAgencyFile(' . $res[$i]['RowID'] . ')" ><i class="fas fa-trash"></i></button></td>';
                $htm .= '</tr>';
            }
            $htm .= '</tbody>';
            $htm .= '</table>';
        }
        return $htm;
    }

    public function attachFileToAgency($aid,$files){
        $db = new DBi();
        $SFile = array();
        $allowedTypes = ['png','jpg','jpeg','jfif','pdf','PNG','JPG','JPEG','JFIF','PDF'];

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
                $SFile[] = "agency" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $cnt = count($SFile);
        for ($i=0;$i<$cnt;$i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i],'../attachment/'.$SFile[$i]);
            $sql4 = "UPDATE `agency` SET `fileName`='{$SFile[$i]}' WHERE `RowID`={$aid}";
            $db->Query($sql4);
        }
        return true;
    }

    public function deleteAttachAgencyFile($aid){
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `agency` WHERE `RowID`={$aid}";
        $res = $db->ArrayQuery($sql);
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/attachment/' . $res[0]['fileName'];
        $result = unlink($file_to_delete);

        if ($result) {
            $sql = "UPDATE `agency` SET `fileName`='' WHERE `RowID`={$aid}";
            $db->Query($sql);
            $aff = $db->AffectedRows();
            $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
            if (intval($aff) > 0) {
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    public function getPrintAgencyHTM($sDate,$eDate,$billNum,$agencyType,$serviceType,$personnel){
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`createDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`createDate` <="'.$eDate.'" ';
        }
        if(strlen(trim($billNum)) > 0){
            $w[] = '`billNumber`="'.$billNum.'" ';
        }
        if(intval($agencyType) >= 0){
            $w[] = '`agencyID`='.$agencyType.' ';
        }
        if(intval($serviceType) >= 0){
            $w[] = '`serviceID`='.$serviceType.' ';
        }

        $sql = "SELECT * FROM `agency`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `createDate` ASC ";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        $htm .= '<div class="demoAgency" style="margin-top: -60px;">';
        $htm .= '<table class="table table-sm" id="AgencyCover-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="text-dark">';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 9%;">تاریخ</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 9%;">شماره قبض</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 9%;">نام آژانس</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 9%;">نوع سرویس</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 9%;">توقف به دقیقه</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 12%;">نام مسافر/مسافران</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 12%;">نام میهمان/میهمانان</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 12%;">توضیحات</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 14%;">مبلغ</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $iterator = 0;
        $totalAmount = 0;
        $personnelAmount = 0;
        for ($i = 0; $i < $cnt; $i++) {
            $passenger = array();
            $afrad = explode(',',$res[$i]['passenger']);
            if(intval($personnel) > 0){
                if (!in_array($personnel,$afrad)){
                    continue;
                }
                $cntp = count($afrad);
                $personnelAmount += ($res[$i]['amount']/$cntp);
            }
            $totalAmount += $res[$i]['amount'];
            $iterator ++;
            switch ($res[$i]['agencyID']){
                case 0:
                    $agencyID = 'آژانس پارک';
                    break;
                case 1:
                    $agencyID = 'آژانس پرستو';
                    break;
                case 2:
                    $agencyID = 'اسنپ';
                    break;
                case 3:
                    $agencyID = 'آژانس مسافر (آزادگان)';
                    break;
                case 4:
                    $agencyID = 'آژانس متفرقه';
                    break;
                case 5:
                    $agencyID = 'خودرو خدماتی';
                    break;
            }
            switch ($res[$i]['serviceID']){
                case 0:
                    $serviceID = 'ماموریت';
                    break;
                case 1:
                    $serviceID = 'اضافه کار';
                    break;
                case 2:
                    $serviceID = 'میهمان';
                    break;
                case 3:
                    $serviceID = 'شیفت کاری';
                    break;
                case 4:
                    $serviceID = 'ارسال کالا';
                    break;
                case 5:
                    $serviceID = 'کنسل شده';
                    break;
            }
            $query = "SELECT `Fname`,`Lname` FROM `personnel` WHERE `RowID` IN ({$res[$i]['passenger']})";
            $rst = $db->ArrayQuery($query);
            $cnt1 = count($rst);
            for ($j=0;$j<$cnt1;$j++){
                $passenger[] = $rst[$j]['Fname'].' '.$rst[$j]['Lname'];
            }
            $passenger = implode(' - ',$passenger);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $iterator. '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $ut->greg_to_jal($res[$i]['createDate']) . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $res[$i]['billNumber'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $agencyID . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $serviceID . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $res[$i]['stopMinute'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $passenger . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $res[$i]['guest'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $res[$i]['description'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . number_format($res[$i]['amount']) . ' ریال</td>';
            $htm .= '</tr>';
        }

        $htm .= '<tr class="table-secondary">';
        $htm .= '<td colspan="5" style="border: 2px solid #000;text-align: center;font-family: dubai-Regular;padding: 10px;">جمع کل مبلغ</td>';
        $htm .= '<td colspan="5" style="border: 2px solid #000;text-align: right;font-family: dubai-Regular;padding: 10px;">'.number_format($totalAmount).' ریال</td>';
        $htm .= '</tr>';

        if (intval($personnelAmount) > 0) {
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td colspan="5" style="border: 2px solid #000;text-align: center;font-family: dubai-Regular;padding: 10px;">جمع کل مبلغ برای پرسنل انتخاب شده</td>';
            $htm .= '<td colspan="5" style="border: 2px solid #000;text-align: right;font-family: dubai-Regular;padding: 10px;">' . number_format($personnelAmount) . ' ریال</td>';
            $htm .= '</tr>';
        }

        $htm .= '</tbody>';
        $htm .= '</table>';
        $htm .= '</div>';
        return $htm;
    }

    public function getAgencyExcel($sDate,$eDate,$billNum,$agencyType,$serviceType,$personnel,$group_method=0){
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`createDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`createDate` <="'.$eDate.'" ';
        }
        if(strlen(trim($billNum)) > 0){
            $w[] = '`billNumber`="'.$billNum.'" ';
        }
        if(intval($agencyType) >= 0){
            $w[] = '`agencyID`='.$agencyType.' ';
        }
        if(intval($serviceType) >= 0){
            $w[] = '`serviceID`='.$serviceType.' ';
        }

        $sql = "SELECT * FROM `agency`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `createDate` ASC ";
        $res = $db->ArrayQuery($sql);
        //**************************************************** */
        if($group_method==1)
        {
            $new_index=$k;
            foreach($res as $key=>$value){
                $value['new_index']=$new_index;
                $handler_array=  $value;
                $passenger= explode(',',$value['passenger']);
                if(count($passenger)>0){
                    for($passenger_index=0; $passenger_index<count($passenger);$passenger_index++){
                        $new_index++;
                        $handler_array['passenger']=$passenger[$passenger_index];
                        $handler_array['amount']=$value['amount']/count($passenger);
                        $handler_array['new_index']=$new_index;
                        $res[]=$handler_array;
                    }
                    unset($res[$key]);
                   
                }
            }
            $new_index++;
            
            $new_res_array=[];
            foreach($res as $res_k=>$res_value){
                $new_index=$res_value['new_index'];
                unset($res_value['new_index']);
                $new_res_array[$new_index]=$res_value;
            }
            sort($new_res_array);
            //$res=array_reverse($new_res_array);
            $res=$new_res_array;
        }
		else{
			
			
		}
    
        $cnt = count($res);
        $finalRes = array();
        for ($i=0;$i<$cnt;$i++){
            $passenger = array();
            $units = array();
            $afrad = explode(',',$res[$i]['passenger']);
            if(intval($personnel) > 0){
                if (!in_array($personnel,$afrad)){
                    continue;
                }
            }
            $query = "SELECT `Fname`,`Lname`,`Unit_id` FROM `personnel` WHERE `RowID` IN ({$res[$i]['passenger']})";
            $rst = $db->ArrayQuery($query);
            $cntf = count($rst);
            for ($j=0;$j<$cntf;$j++){
                $passenger[] = $rst[$j]['Fname'].' '.$rst[$j]['Lname'];
                $sqlo = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$rst[$j]['Unit_id']}";
                $rsto = $db->ArrayQuery($sqlo);
                $units[] = $rsto[0]['Uname'];
            }
            $finalRes[$i]['passenger'] = implode(' - ',$passenger);
            $finalRes[$i]['units'] = implode(' - ',$units);
            $finalRes[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);
            $finalRes[$i]['amount'] = (intval($res[$i]['amount']) > 0 ? $res[$i]['amount'] : '');
            $finalRes[$i]['billNumber'] = $res[$i]['billNumber'];
            $finalRes[$i]['guest'] = $res[$i]['guest'];
            $finalRes[$i]['stopMinute'] = $res[$i]['stopMinute'];
            $finalRes[$i]['description'] = $res[$i]['description'];
            switch ($res[$i]['agencyID']){
                case 0:
                    $finalRes[$i]['agencyID'] = 'آژانس پارک';
                    break;
                case 1:
                    $finalRes[$i]['agencyID'] = 'آژانس پرستو';
                    break;
                case 2:
                    $finalRes[$i]['agencyID'] = 'اسنپ';
                    break;
                case 3:
                    $finalRes[$i]['agencyID'] = 'آژانس مسافر (آزادگان)';
                    break;
                case 4:
                    $finalRes[$i]['agencyID'] = 'آژانس متفرقه';
                    break;
                case 5:
                    $finalRes[$i]['agencyID'] = 'خودرو خدماتی';
                    break;
            }
            switch ($res[$i]['serviceID']){
                case 0:
                    $finalRes[$i]['serviceID'] = 'ماموریت';
                    break;
                case 1:
                    $finalRes[$i]['serviceID'] = 'اضافه کار';
                    break;
                case 2:
                    $finalRes[$i]['serviceID'] = 'میهمان';
                    break;
                case 3:
                    $finalRes[$i]['serviceID'] = 'شیفت کاری';
                    break;
                case 4:
                    $finalRes[$i]['serviceID'] = 'ارسال کالا';
                    break;
                case 5:
                    $finalRes[$i]['serviceID'] = 'کنسل شده';
                    break;
            }
        }
        return array_values($finalRes);
    }

    public function getUnitAgencyAmountExcel($sDate,$eDate,$agencyType,$serviceType){
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`createDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`createDate` <="'.$eDate.'" ';
        }
        if(intval($agencyType) >= 0){
            $w[] = '`agencyID`='.$agencyType.' ';
        }
        if(intval($serviceType) >= 0){
            $w[] = '`serviceID`='.$serviceType.' ';
        }

        $prices = array();
        $query = "SELECT `RowID`,`Uname` FROM `official_productive_units`";
        $rst = $db->ArrayQuery($query);
        $cntc = count($rst);
        for ($a=0;$a<$cntc;$a++){
            $prices[$rst[$a]['RowID']] = 0;
        }

        $sql = "SELECT `passenger`,`amount` FROM `agency`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        for ($i=0;$i<$cnt;$i++){
            if (strlen(trim($res[$i]['passenger'])) == 0){
                continue;
            }
            $passenger = explode(',',$res[$i]['passenger']);
            $ccnt = count($passenger);
            for ($j=0;$j<$ccnt;$j++){
                $sql1 = "SELECT `Unit_id` FROM `personnel` WHERE `RowID`={$passenger[$j]}";
                $rst1 = $db->ArrayQuery($sql1);
                $prices[$rst1[0]['Unit_id']] += intval($res[$i]['amount']/$ccnt);
            }
        }
        $keyPrice = array_keys($prices);
        $cntkey = count($keyPrice);
        $result = array();
        for ($i=0;$i<$cntkey;$i++){
            $sql2 = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$keyPrice[$i]}";
            $rst2 = $db->ArrayQuery($sql2);
            $result[$i]['Uname'] = $rst2[0]['Uname'];
            $result[$i]['amount'] = $prices[$keyPrice[$i]];
        }
        return $result;
    }

    public function finalTickAgency($sDate,$eDate){
        $acm = new acm();
        if(!$acm->hasAccess('finalTickAgency')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $sDate = $ut->jal_to_greg($sDate);
        $eDate = $ut->jal_to_greg($eDate);
        if (strtotime($sDate) >= strtotime($eDate)){
            $res = "بازه زمانی نامعتبر است !";
            $out = "false";
            response($res,$out);
            exit;
        }
        $query = "SELECT `amount`,`serviceID`,`billNumber` FROM `agency` WHERE `createDate`>='{$sDate}' AND `createDate`<='{$eDate}'";
        $res = $db->ArrayQuery($query);
        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            if (intval($res[$i]['serviceID']) !== 5 && intval($res[$i]['amount']) == 0){  // کنسل شده نبود و مبلغ صفر بود
                $res = "در بازه انتخابی، بعضی از قیمت ها وارد نشده است !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }

        $sql = "UPDATE `agency` SET `finalTick`=1 WHERE `createDate`>='{$sDate}' AND `createDate`<='{$eDate}'";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getTotalAgencyPrice($sDate,$eDate,$billNum,$agencyType,$serviceType,$personnel){
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`createDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`createDate` <="'.$eDate.'" ';
        }
        if(strlen(trim($billNum)) > 0){
            $w[] = '`billNumber`="'.$billNum.'" ';
        }
        if(intval($agencyType) >= 0 && intval($agencyType) !== 5){
            $w[] = '`agencyID`='.$agencyType.' ';
        }
        if(intval($serviceType) >= 0){
            $w[] = '`serviceID`='.$serviceType.' ';
        }

        $sql = "SELECT `amount`,`passenger` FROM `agency`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $totalAmount = 0;
        for($y=0;$y<$listCount;$y++){
            $afrad = explode(',',$res[$y]['passenger']);
            if(intval($personnel) > 0){
                if (!in_array($personnel,$afrad)){
                    continue;
                }
            }
            $totalAmount += $res[$y]['amount'];
        }

        $w1 = array();
        if(strlen(trim($sDate)) > 0){
            $sDate = $ut->jal_to_greg($sDate);
            $w1[] = '`createDate` >="'.$sDate.'" ';
        }
        if(strlen(trim($eDate)) > 0){
            $eDate = $ut->jal_to_greg($eDate);
            $w1[] = '`createDate` <="'.$eDate.'" ';
        }
        if(strlen(trim($billNum)) > 0){
            $w1[] = '`billNumber`="'.$billNum.'" ';
        }
        if(intval($serviceType) >= 0){
            $w1[] = '`serviceID`='.$serviceType.' ';
        }
        $w1[] = '`agencyID`=5';

        $sql1 = "SELECT `amount`,`passenger` FROM `agency`";
        if(count($w1) > 0){
            $where = implode(" AND ",$w1);
            $sql1 .= " WHERE ".$where;
        }
        $res1 = $db->ArrayQuery($sql1);
        $listCount1 = count($res1);
        $totalAmount1 = 0;
        for($y=0;$y<$listCount1;$y++){
            $afrad1 = explode(',',$res1[$y]['passenger']);
            if(intval($personnel) > 0){
                if (!in_array($personnel,$afrad1)){
                    continue;
                }
            }
            $totalAmount1 += $res1[$y]['amount'];
        }

        $headerTxt = '<span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> مبلغ کل (به جز خودرو خدماتی) : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;"> '.number_format($totalAmount).' ریال </span><br><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> مبلغ کل (خودرو خدماتی) : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;"> '.number_format($totalAmount1).' ریال</span><br>';
        return $headerTxt;
    }

    //******************** رستوران ها ********************

    public function getRestaurantManageList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $sql = "SELECT * FROM `restaurant`";
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['restaurant_Name'] = $res[$y]['restaurant_Name'];
        }
        return $finalRes;
    }

    public function getRestaurantManageListCountRows(){
        $db = new DBi();
        $sql = "SELECT * FROM `restaurant`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function restaurantInfo($rid){
        $db = new DBi();
        $sql = "SELECT * FROM `restaurant` WHERE `RowID`=".$rid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("rid"=>$rid,"restaurant_Name"=>$res[0]['restaurant_Name']);
            return $res;
        }else{
            return false;
        }
    }

    public function createRestaurant($rName){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "INSERT INTO `restaurant` (`restaurant_Name`) VALUES ('{$rName}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editRestaurant($rid,$rName){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `restaurant` SET `restaurant_Name`='{$rName}' WHERE `RowID`={$rid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    //******************** غذا ها ********************

    public function getFoodManageList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $sql = "SELECT * FROM `food`";
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['food_Name'] = $res[$y]['food_Name'];
            $finalRes[$y]['amount'] = number_format($res[$y]['amount']).' ریال';
        }
        return $finalRes;
    }

    public function getFoodManageListCountRows(){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT * FROM `food`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function foodInfo($fid){
        $db = new DBi();
        $sql = "SELECT * FROM `food` WHERE `RowID`=".$fid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("fid"=>$fid,"food_Name"=>$res[0]['food_Name'],"amount"=>$res[0]['amount']);
            return $res;
        }else{
            return false;
        }
    }

    public function createFood($fName,$amount){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "INSERT INTO `food` (`food_Name`,`amount`) VALUES ('{$fName}',{$amount})";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editFood($fid,$fName,$amount){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `food` SET `food_Name`='{$fName}',`amount`={$amount} WHERE `RowID`={$fid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    //******************** نوشیدنی ها ********************

    public function getDrinkManageList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $sql = "SELECT * FROM `drink`";
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['drink_Name'] = $res[$y]['drink_Name'];
            $finalRes[$y]['amount'] = number_format($res[$y]['amount']).' ریال';
        }
        return $finalRes;
    }

    public function getDrinkManageListCountRows(){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT * FROM `food`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function drinkInfo($did){
        $db = new DBi();
        $sql = "SELECT * FROM `drink` WHERE `RowID`=".$did;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("did"=>$did,"drink_Name"=>$res[0]['drink_Name'],"amount"=>$res[0]['amount']);
            return $res;
        }else{
            return false;
        }
    }

    public function createDrink($dName,$amount){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "INSERT INTO `drink` (`drink_Name`,`amount`) VALUES ('{$dName}',{$amount})";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editDrink($did,$dName,$amount){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `drink` SET `drink_Name`='{$dName}',`amount`={$amount} WHERE `RowID`={$did}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    //******************** مسیرهای سرویس دهی ********************

    public function getServiceRouteManageList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $sql = "SELECT * FROM `service_routes`";
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $personnel = array();
            $rids = array();
            $query = "SELECT `RowID`,`Fname`,`Lname` FROM `personnel` WHERE `route`={$res[$y]['RowID']} AND `isEnable`=1";
            $rst = $db->ArrayQuery($query);
            $cnt = count($rst);
            for ($i=0;$i<$cnt;$i++){
                $personnel[] = $rst[$i]['Fname'].' '.$rst[$i]['Lname'];
                $rids[] = $rst[$i]['RowID'];
            }
            $personnel = implode(' - ',$personnel);
            $rids = implode(' - ',$rids);

            $sql1 = "UPDATE `service_routes` SET `personnel`='{$rids}' WHERE `RowID`={$res[$y]['RowID']}";
            $db->Query($sql1);

            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['routeName'] = $res[$y]['routeName'];
            $finalRes[$y]['personnel'] = $personnel;
        }
        return $finalRes;
    }

    public function getServiceRouteManageListCountRows(){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `service_routes`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function serviceRouteInfo($srid){
        $db = new DBi();
        $sql = "SELECT * FROM `service_routes` WHERE `RowID`=".$srid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("srid"=>$srid,"routeName"=>$res[0]['routeName']);
            return $res;
        }else{
            return false;
        }
    }

    public function createServiceRoute($rName){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "INSERT INTO `service_routes` (`routeName`) VALUES ('{$rName}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editServiceRoute($srid,$rName){
        $acm = new acm();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `service_routes` SET `routeName`='{$rName}' WHERE `RowID`={$srid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function personnelServiceRouteHtm(){
        $db = new DBi();
        $sql = "SELECT `RowID`,`Fname`,`Lname`,`PersonnelCode`,`Address`,`route` FROM `personnel` WHERE `isEnable`=1";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $sql1 = "SELECT `RowID`,`routeName` FROM `service_routes`";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);

        $htm = '';
        $iterator = 0;
        $htm .= '<table class="table table-bordered table-hover table-sm" id="personnelServiceRoute-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= "<td style='display: none;'>&nbsp;</td>";
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">نام</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">نام خانوادگی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">کد تفضیلی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">آدرس</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">مسیر</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++) {
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= "<td style='display: none;' ><input type='checkbox' rid='".$iterator."' checked disabled>&nbsp;</td>";
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['Fname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['Lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['PersonnelCode'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['Address'].'</td>';

            $htm .= '<td style="text-align: center;"><select class="form-control" id="personnelServiceRoute-'.$iterator.'" >';
            $htm .= '<option value="0" >انتخاب کنید</option>';
            for ($x=0;$x<$cnt1;$x++){
                $selected = (intval($res1[$x]['RowID']) == intval($res[$i]['route']) ? 'selected' : '');
                $htm .= '<option value="'.$res1[$x]['RowID'].'" '.$selected.'>'.$res1[$x]['routeName'].'</option>';
            }
            $htm .= '</select><input type="hidden" id="psrid-'.$iterator.'-Hidden" value="'.$res[$i]['RowID'].'" /></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function createPersonnelServiceRoute($myJsonString){
        $acm = new acm();
        $ut=new Utility();
        if(!$acm->hasAccess('foodItemsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $countJS = count($myJsonString);
        $flag = true;
        mysqli_autocommit($db->Getcon(),FALSE);

        for($i=0;$i<$countJS;$i++){
            $psrid = intval($myJsonString[$i][0]);
            $routeID = $myJsonString[$i][1];

            $sql = "UPDATE `personnel` SET `route`={$routeID} WHERE `RowID`={$psrid}";
           // $ut->fileRecorder('SEcurity:'.$sql);
            $db->Query($sql);
            $res = $db->AffectedRows();
            $res = ($res == -1 ? 0 : 1);
            if(!intval($res)){
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

    //******************** ثبت ناهار اضافه کار ********************
    private function get_overtime_meal($meal_id){
        $db=new DBi();
        $sql="SELECT `meal`,`meal_id` FROM `over_time_meal` WHERE `meal_id`={$meal_id}" ;
        $res=$db->ArrayQuery($sql);
        return $res[0]['meal'];

    }
    public function getOvertimeLunchManageList($sDate,$eDate,$personnel,$restaurant,$meal,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('overtimeLunchManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if($acm->hasAccess('overtimeLunchDetailsManage')) {
            if (strlen(trim($sDate)) > 0) {
                $sDate = $ut->jal_to_greg($sDate);
                $w[] = '`createDate` >="' . $sDate . '" ';
            }
            if (strlen(trim($eDate)) > 0) {
                $eDate = $ut->jal_to_greg($eDate);
                $w[] = '`createDate` <="' . $eDate . '" ';
            }
        }else{
            $nowDate = date('Y-m-d');
            $w[] = '`createDate`="'.$nowDate.'" ';
        }
        if (intval($personnel) > 0){
            $w[] = '`pid`='.$personnel.' ';
        }
        if (intval($restaurant) > 0){
            $w[] = '`restaurantID`='.$restaurant.' ';
        }
        if (intval($meal) >= 0){
            $w[] = '`meal`='.$meal.' ';
        }

        $sql = "SELECT * FROM `overtime_lunch`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `createDate` DESC,`route` DESC LIMIT $start,".$numRows;
  
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            $query1 = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$res[$y]['unit']}";
            $rst1 = $db->ArrayQuery($query1);
            $query2 = "SELECT `restaurant_Name` FROM `restaurant` WHERE `RowID`={$res[$y]['restaurantID']}";
            $rst2 = $db->ArrayQuery($query2);
            $query3 = "SELECT `food_Name` FROM `food` WHERE `RowID`={$res[$y]['foodID']}";
            $rst3 = $db->ArrayQuery($query3);
            $query4 = "SELECT `drink_Name` FROM `drink` WHERE `RowID`={$res[$y]['drinkID']}";
            $rst4 = $db->ArrayQuery($query4);
            $query5 = "SELECT `routeName` FROM `service_routes` WHERE `RowID`={$res[$y]['route']}";
            $rst5 = $db->ArrayQuery($query5);

			$meal=$this->get_overtime_meal($res[$y]['meal']);
			// switch($res[$y]['meal']){
			// 	case "0":
			// 		$meal="ناهار";
			// 		break;
			// 	case "1":
			// 		$meal="شام";
			// 		break;
			// 	case "2":
			// 		$meal="بدون ناهار";
			// 		break;
			// }
            $routeName = (count($rst5) > 0 ? $rst5[0]['routeName'] : 'میهمان');
            $createDate = $ut->greg_to_jal($res[$y]['createDate']);
            $name = $res[$y]['fname'].' '.$res[$y]['lname'];
			
            $finalRes[$y]['counter'] = $start+1+$y;
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['createDate'] = $createDate;
            $finalRes[$y]['routeName'] = $routeName;
            $finalRes[$y]['name'] = $name;
            $finalRes[$y]['unitName'] = $rst1[0]['Uname'];
            $finalRes[$y]['restaurant_Name'] = $rst2[0]['restaurant_Name'];
            $finalRes[$y]['food_Name'] = $rst3[0]['food_Name'];
            $finalRes[$y]['drink_Name'] = $rst4[0]['drink_Name'];
            $finalRes[$y]['btnType'] = 'btn-danger';
            $finalRes[$y]['icon'] = 'fa-trash';
            $finalRes[$y]['bread'] = number_format($res[$y]['bread']).' ریال';
            $finalRes[$y]['desc'] = $res[$y]['description'];
            $finalRes[$y]['status'] = ($res[$y]['finalTick'] == 0 ? 'عدم تایید' : 'تایید شده');
            $finalRes[$y]['txtco'] = ($res[$y]['finalTick'] == 0 ? 'red' : 'green');
            $finalRes[$y]['meal'] = $meal;
            $finalRes[$y]['finalAmount'] = number_format($res[$y]['finalAmount']).' ریال';
        }
   
        return $finalRes;
    }

    public function getOvertimeLunchManageListCountRows($sDate,$eDate,$personnel,$restaurant,$meal){
        $acm = new acm();
        $ut = new Utility();
        $db = new DBi();
        $w = array();
		$w[]=' `meal` =0';
        if($acm->hasAccess('overtimeLunchDetailsManage')) {
            if (strlen(trim($sDate)) > 0) {
                $sDate = $ut->jal_to_greg($sDate);
                $w[] = '`createDate` >="' . $sDate . '" ';
            }
            if (strlen(trim($eDate)) > 0) {
                $eDate = $ut->jal_to_greg($eDate);
                $w[] = '`createDate` <="' . $eDate . '" ';
            }
        }else{
            $nowDate = date('Y-m-d');
            $w[] = '`createDate`="'.$nowDate.'" ';
        }
        if (intval($personnel) > 0){
            $w[] = '`pid`='.$personnel.' ';
        }
        if (intval($restaurant) > 0){
            $w[] = '`restaurantID`='.$restaurant.' ';
        }
        if (intval($meal) >= 0){
            $w[] = '`meal`='.$meal.' ';
        }

        $sql = "SELECT `RowID` FROM `overtime_lunch`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $personel_lunch_count=$sql." AND type=1";
        $guest_lunch_count=$sql." AND type=2";
        // $ut->fileRecorder($personel_lunch_count);
        // $ut->fileRecorder($guest_lunch_count);
        $res = $db->ArrayQuery($personel_lunch_count);
        $guest = $db->ArrayQuery($guest_lunch_count);
		//----------------------------------------- تعداد بدون ناهار -------------
		$whitout_lunch_w=$w;
		$whitout_lunch_w[0]= ' `meal`=2';
		$whitout_lunch_sql="SELECT RowID FROM `overtime_lunch` ";
		if(count($w) > 0){
            $whitout_lunch_where = implode(" AND ",$whitout_lunch_w);
            $whitout_lunch_sql .= " WHERE ".$whitout_lunch_where;
        }
		$whitout_lunch_res=$db->ArrayQuery($whitout_lunch_sql);
        //-----------------------------------------------تعداد افطاری -------------------------
        $breakfast_where=$w;
		$breakfast_where[0]= ' `meal`=4';
		$breakfast_sql="SELECT RowID FROM `overtime_lunch` ";
		if(count($w) > 0){
            $breakfast_where = implode(" AND ",$breakfast_where);
            $breakfast_sql .= " WHERE ".$breakfast_where;
        }
		$breakfast_res=$db->ArrayQuery($breakfast_sql);
		$lunch_count_detailes_array=array('has_lunch_count_personel'=>count($res),'has_lunch_count_guest'=>count($guest),'whitout_lunch_count'=>count($whitout_lunch_res),'breakfast_count'=>count($breakfast_res));
		
        return $lunch_count_detailes_array;
    }

    public function getPersonnelOfUnit($unit){
        $db = new DBi();
       // $query = "SELECT `RowID`,`Fname`,`Lname` FROM `personnel` WHERE `Unit_id`={$unit} AND `isEnable`=1";
        $query = "SELECT `RowID`,`Fname`,`Lname` FROM `personnel` WHERE `isEnable`=1";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0) {
            return $rst;
        } else {
            return array();
        }
    }

    public function createOvertimeLunchPersonnel($personnel,$olDate,$unit,$meal,$restaurant){
        $acm = new acm();
        if(!$acm->hasAccess('overtimeLunchManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $cDate = (strlen(trim($olDate)) > 0 ? $ut->jal_to_greg($olDate) : date('Y-m-d'));
        $afrad = explode(',',$personnel);
        $ccnt = count($afrad);

/*        $query = "SELECT `pid` FROM `overtime_lunch` WHERE `createDate`='{$cDate}'";
        $rst = $db->ArrayQuery($query);
        $cnt = count($rst);
        for ($i=0;$i<$cnt;$i++){
            if (in_array($rst[$i]['pid'],$afrad)){
                return -1;
            }
        }*/

        for ($i=0;$i<$ccnt;$i++){
            $sqq = "SELECT `Fname`,`Lname`,`PersonnelCode`,`route`,`Unit_id` FROM `personnel` WHERE `RowID`={$afrad[$i]}";
            $rqq = $db->ArrayQuery($sqq);
            $unit= $rqq[0]['Unit_id'];
            $sql = "INSERT INTO `overtime_lunch` (`pid`,`unit`,`createDate`,`type`,`fname`,`lname`,`pCode`,`route`,`meal`,`restaurantID`) VALUES ({$afrad[$i]},{$unit},'{$cDate}',1,'{$rqq[0]['Fname']}','{$rqq[0]['Lname']}','{$rqq[0]['PersonnelCode']}',{$rqq[0]['route']},{$meal},'{$restaurant}')";
            //$ut->fileRecorder($sql);
            $db->Query($sql);
        }
        return true;
    }

    public function createOvertimeLunchGuest($olDate,$unit,$fname,$lname,$desc,$restaurant){
        $acm = new acm();
        if(!$acm->hasAccess('overtimeLunchManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $cDate = (strlen(trim($olDate)) > 0 ? $ut->jal_to_greg($olDate) : date('Y-m-d'));
        $sql = "INSERT INTO `overtime_lunch` (`unit`,`createDate`,`type`,`fname`,`lname`,`restaurantID`,`description`) VALUES ({$unit},'{$cDate}',2,'{$fname}','{$lname}',{$restaurant},'{$desc}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getTotalServiceRoutes($sDate,$eDate,$personnel,$restaurant,$meal){
        $acm = new acm();
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if($acm->hasAccess('overtimeLunchDetailsManage')) {
            if (strlen(trim($sDate)) > 0) {
                $sDate = $ut->jal_to_greg($sDate);
                $w[] = '`createDate` >="' . $sDate . '" ';
            }
            if (strlen(trim($eDate)) > 0) {
                $eDate = $ut->jal_to_greg($eDate);
                $w[] = '`createDate` <="' . $eDate . '" ';
            }
        }else{
            $nowDate = date('Y-m-d');
            $w[] = '`createDate`="'.$nowDate.'" ';
        }
        if (intval($personnel) > 0){
            $w[] = '`pid`='.$personnel.' ';
        }
        if (intval($restaurant) > 0){
            $w[] = '`restaurantID`='.$restaurant.' ';
        }
        if (intval($meal) >= 0){
            $w[] = '`meal`='.$meal.' ';
        }
        $sql = "SELECT COUNT(`route`) AS `rt`,`routeName`,SUM(`finalAmount`) AS `fa`,SUM(`bread`) AS `ba` FROM `overtime_lunch` LEFT JOIN `service_routes` ON (`overtime_lunch`.`route`=`service_routes`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " GROUP BY `route`";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $headerTxt = '';
        $totalAmount = 0;
        $totalBreadAmount = 0;
        for ($i=0;$i<$cnt;$i++){
            // if(strlen(trim($res[$i]['routeName'])>0))
            // {
                $res[$i]['routeName'] = (strlen(trim($res[$i]['routeName'])) == 0 ? 'میهمان' : $res[$i]['routeName']);
                $totalAmount += $res[$i]['fa'];
                $totalBreadAmount += $res[$i]['ba'];
                if($res[$i]['routeName']!="میهمان")
                    $headerTxt .= '<span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 16px;"> '.$res[$i]['routeName'].' : </span><span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 16px;"> '.$res[$i]['rt'].' نفر &nbsp;</span>';
           // }
        }

        $sql1 = "SELECT SUM(`amount`) AS `sa` FROM `food` INNER JOIN `overtime_lunch` ON (`food`.`RowID`=`overtime_lunch`.`foodID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql1 .= " WHERE ".$where;
        }
        $res1 = $db->ArrayQuery($sql1);

        $sql2 = "SELECT SUM(`amount`) AS `da` FROM `drink` INNER JOIN `overtime_lunch` ON (`drink`.`RowID`=`overtime_lunch`.`drinkID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql2 .= " WHERE ".$where;
        }
        $res2 = $db->ArrayQuery($sql2);

        $headerTxt .= '<br><br><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> مبلغ کل هزینه غذا : </span>';
        $headerTxt .= '<span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;"> '.number_format($res1[0]['sa']).' ریال</span>';

        $headerTxt .= '<br><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> مبلغ کل هزینه نوشیدنی : </span>';
        $headerTxt .= '<span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;"> '.number_format($res2[0]['da']).' ریال</span>';

        $headerTxt .= '<br><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> مبلغ کل هزینه نان : </span>';
        $headerTxt .= '<span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;"> '.number_format($totalBreadAmount).' ریال</span>';

        $headerTxt .= '<br><span style="font-family: dubai-Regular;font-weight: bold;color: #000;font-size: 20px;"> کل مبلغ : </span>';
        $headerTxt .= '<span style="font-family: dubai-Regular;font-weight: bold;color: red;font-size: 20px;"> '.number_format($totalAmount).' ریال</span>';

        return $headerTxt;
    }

    // public function lunchOfPersonnelHtm($ddate){
    //     $db = new DBi();
    //     $ut = new Utility();
    //     $ddate = $ut->jal_to_greg($ddate);
    //     $sql = "SELECT `RowID`,`fname`,`lname`,`pCode`,`restaurantID`,`foodID`,`drinkID`,`breadNumber`,`meal` FROM `overtime_lunch` WHERE `createDate`='{$ddate}' ";
    //     $res = $db->ArrayQuery($sql);
    //     $cnt = count($res);

    //     $sql1 = "SELECT * FROM `restaurant`";
    //     $res1 = $db->ArrayQuery($sql1);
    //     $cnt1 = count($res1);

    //     $sql2 = "SELECT * FROM `food`";
    //     $res2 = $db->ArrayQuery($sql2);
    //     $cnt2 = count($res2);

    //     $sql3 = "SELECT * FROM `drink`";
    //     $res3 = $db->ArrayQuery($sql3);
    //     $cnt3 = count($res3);

    //     $htm = '';
    //     $iterator = 0;
    //     $htm .= '<table class="table table-bordered table-hover table-sm" id="lunchOfPersonnelHtm-tableID">';
    //     $htm .= '<thead>';
    //     $htm .= '<tr class="bg-info">';
    //     $htm .= "<td style='display: none;'>&nbsp;</td>";
    //     $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
    //     $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">نام</td>';
    //     $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">نام خانوادگی</td>';
    //     $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">کد تفضیلی</td>';
    //     $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">نام رستوران</td>';
    //     $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">نام غذا</td>';
    //     $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">نام نوشیدنی</td>';
    //     $htm .= '</tr>';
    //     $htm .= '</thead>';
    //     $htm .= '<tbody>';
    //     for ($i = 0; $i < $cnt; $i++) {
    //         $iterator++;
    //         $chgRestaurant = ($i == 0 ? 'onchange=changeRestaurantType()' : '');
    //         $chgFood = ($i == 0 ? 'onchange=changeFoodType()' : '');
    //         $chgDrink = ($i == 0 ? 'onchange=changeDrinkType()' : '');
    //         $disabled="";
    //         $food_status=1;
    //         if($res[$i]['meal']==2){// ثبت اضافه کار بدون نهار
    //             $disabled="disabled";
    //             $food_status=0;
    //         }
    //         $htm .= '<tr class="table-secondary">';
    //         $htm .= "<td style='display: none;' ><input food_status='".$food_status."' type='checkbox' rid='".$iterator."' checked disabled>&nbsp;</td>";
    //         $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
    //         $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].'</td>';
    //         $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['lname'].'</td>';
    //         $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['pCode'].'</td>';
    //         $htm .= '<td style="text-align: center;"><select '.$disabled.' class="form-control" id="OvertimeLunchRestaurant-'.$iterator.'" '.$chgRestaurant.' >';
    //         $htm .= '<option value="0" >انتخاب کنید</option>';
    //         for ($x=0;$x<$cnt1;$x++){
    //             $selected = (intval($res1[$x]['RowID']) == intval($res[$i]['restaurantID']) ? 'selected' : '');
    //             $htm .= '<option value="'.$res1[$x]['RowID'].'" '.$selected.'>'.$res1[$x]['restaurant_Name'].'</option>';
    //         }
    //         $htm .= '</select></td>';

    //         $htm .= '<td style="text-align: center;"><select '.$disabled.' class="form-control" id="OvertimeLunchFood-'.$iterator.'" '.$chgFood.' >';
    //         $htm .= '<option value="0" >انتخاب کنید</option>';
    //         for ($x=0;$x<$cnt2;$x++){
    //             $selected = (intval($res2[$x]['RowID']) == intval($res[$i]['foodID']) ? 'selected' : '');
    //             $htm .= '<option value="'.$res2[$x]['RowID'].'" '.$selected.'>'.$res2[$x]['food_Name'].'</option>';
    //         }
    //         $htm .= '</select></td>';

    //         $htm .= '<td style="text-align: center;"><select '.$disabled.' class="form-control" id="OvertimeLunchDrink-'.$iterator.'" '.$chgDrink.' >';
    //         $htm .= '<option value="0" >انتخاب کنید</option>';
    //         for ($x=0;$x<$cnt3;$x++){
    //             $selected = (intval($res3[$x]['RowID']) == intval($res[$i]['drinkID']) ? 'selected' : '');
    //             $htm .= '<option value="'.$res3[$x]['RowID'].'" '.$selected.'>'.$res3[$x]['drink_Name'].'</option>';
    //         }
    //         $htm .= '</select><input type="hidden" id="olid-'.$iterator.'-Hidden" value="'.$res[$i]['RowID'].'" /></td>';
    //         $htm .= '</tr>';
    //     }
    //     $htm .= '</tbody>';
    //     $htm .= '</table>';
    //     $send = array($htm,$res[0]['breadNumber']);
    //     return $send;
    // }
    public function lunchOfPersonnelHtm($ddate){
        $db = new DBi();
        $ut = new Utility();
        $ddate = $ut->jal_to_greg($ddate);
        $sql = "SELECT `RowID`,`fname`,`lname`,`pCode`,`restaurantID`,`foodID`,`drinkID`,`breadNumber`,`meal` FROM `overtime_lunch` WHERE `createDate`='{$ddate}' ";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $sql1 = "SELECT * FROM `restaurant`";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);

        $sql2 = "SELECT * FROM `food`";
        $res2 = $db->ArrayQuery($sql2);
        $cnt2 = count($res2);

        $sql3 = "SELECT * FROM `drink`";
        $res3 = $db->ArrayQuery($sql3);
        $cnt3 = count($res3);

        $htm = '';
        $iterator = 0;
        $htm .= '<table class="table table-bordered table-hover table-sm" id="lunchOfPersonnelHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= "<td style='display: none;'>&nbsp;</td>";
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">نام</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">نام خانوادگی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">کد تفضیلی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">نام رستوران</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">نام غذا</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">نام نوشیدنی</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $chgFood = 'onchange=changeFoodType(this)';
        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $chgRestaurant = ($i == 0 ? 'onchange=changeRestaurantType()' : '');
          //  $chgFood = ($i == 0 ? 'onchange=changeFoodType()' : '');
            //$chgDrink = ($i == 0 ? 'onchange=changeDrinkType()' : '');
            $chgDrink = 'onchange=changeDrinkType(this)';
            $disabled="";
            $food_status=1;
            if($res[$i]['meal']==2){// ثبت اضافه کار بدون نهار
                $disabled="disabled";
                $food_status=0;
            }
           
            $htm .= '<tr class="table-secondary">';
            $htm .= "<td style='display: none;' ><input food_status='".$food_status."' type='checkbox' rid='".$iterator."' checked disabled>&nbsp;</td>";
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['pCode'].'</td>';
            $htm .= '<td style="text-align: center;"><select '.$disabled.' class="form-control" id="OvertimeLunchRestaurant-'.$iterator.'" '.$chgRestaurant.' >';
            $htm .= '<option value="0" >انتخاب کنید</option>';
            for ($x=0;$x<$cnt1;$x++){
                $selected = (intval($res1[$x]['RowID']) == intval($res[$i]['restaurantID']) ? 'selected' : '');
                $htm .= '<option value="'.$res1[$x]['RowID'].'" '.$selected.'>'.$res1[$x]['restaurant_Name'].'</option>';
            }
            $htm .= '</select></td>';
            if($food_status==1){
                $htm .= '<td style="text-align: center;"><select '.$disabled.' class="form-control" food-status="'.$food_status.'" id="OvertimeLunchFood-'.$iterator.'" '.$chgFood.' >';
                $chgFood="";
                $htm .= '<option value="0" >انتخاب کنید</option>';
                for ($x=0;$x<$cnt2;$x++){
                    $selected = (intval($res2[$x]['RowID']) == intval($res[$i]['foodID']) ? 'selected' : '');
                    $htm .= '<option value="'.$res2[$x]['RowID'].'" '.$selected.'>'.$res2[$x]['food_Name'].'</option>';
                }
                $htm .= '</select></td>';
            }
            else{
                $htm .= '<td style="text-align: center;"><select '.$disabled.' class="form-control" food-status="'.$food_status.'" id="OvertimeLunchFood-'.$iterator.'" '.$chgFood.' >';
                $htm .= '<option value="0" >بدون ناهار </option>';
                // for ($x=0;$x<$cnt2;$x++){
                //     $selected = (intval($res2[$x]['RowID']) == intval($res[$i]['foodID']) ? 'selected' : '');
                //     $htm .= '<option value="'.$res2[$x]['RowID'].'" '.$selected.'>'.$res2[$x]['food_Name'].'</option>';
                // }
                $htm .= '</select></td>';
            }
           
            
           
            $htm .= '<td style="text-align: center;"><select '.$disabled.' class="form-control" id="OvertimeLunchDrink-'.$iterator.'" '.$chgDrink.' >';
            $htm .= '<option value="0" >انتخاب کنید</option>';
            for ($x=0;$x<$cnt3;$x++){
                $selected = (intval($res3[$x]['RowID']) == intval($res[$i]['drinkID']) ? 'selected' : '');
                $htm .= '<option value="'.$res3[$x]['RowID'].'" '.$selected.'>'.$res3[$x]['drink_Name'].'</option>';
            }
            $htm .= '</select><input type="hidden" id="olid-'.$iterator.'-Hidden" value="'.$res[$i]['RowID'].'" /></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        $send = array($htm,$res[0]['breadNumber']);
        return $send;
    }


    public function getCountLunch($ddate){
        $db = new DBi();
        $ut = new Utility();
        $ddate = $ut->jal_to_greg($ddate);
        $sql = "SELECT `RowID` FROM `overtime_lunch` WHERE `createDate`='{$ddate}'";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function createOvertimeLunchDetails($myJsonString,$BreadNum){
        $acm = new acm();
        $ut=new Utility();
        if(!$acm->hasAccess('overtimeLunchDetailsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $countJS = count($myJsonString);
        $lunch_amount = 0;
        foreach($myJsonString as $food_array){
            if($food_array[4]==1){
                $lunch_amount ++;
            }
        }
        $flag = true;
        mysqli_autocommit($db->Getcon(),FALSE);

        if (intval($BreadNum) > 0) {
            $query = "SELECT `amount` FROM `food` WHERE `food_Name`='نان'";
            $rst = $db->ArrayQuery($query);
            $BreadNumber = intval($BreadNum) / intval($lunch_amount);
            $sahmNan = $BreadNumber * $rst[0]['amount'];
        }else{
            $sahmNan = 0;
        }

        for($i=0;$i<$countJS;$i++){
            $olid = intval($myJsonString[$i][0]);
            if($myJsonString[$i][4]==1){
                $restaurantID = $myJsonString[$i][1];
                $foodID = $myJsonString[$i][2];
                $drinkID = $myJsonString[$i][3];
            }
            else{//  ----------------------------اضافه کاری بدون ناهار 
                $restaurantID = 0;
                $foodID = 0;
                $drinkID = 0;
            }
            

            $query = "SELECT `finalTick` FROM `overtime_lunch` WHERE `RowID`={$olid}";
            $rst = $db->ArrayQuery($query);
            if (intval($rst[0]['finalTick']) == 1){
                $flag = false;
                break;
            }
            $ut->fileRecorder($myJsonString);
            if($myJsonString[$i][4]==1){
                $sql1 = "SELECT `amount` FROM `food` WHERE `RowID`={$foodID}";
                $res1 = $db->ArrayQuery($sql1);

                $sql2 = "SELECT `amount` FROM `drink` WHERE `RowID`={$drinkID}";
                $res2 = $db->ArrayQuery($sql2);
        
                $finalAmount = intval($res1[0]['amount']) + intval($res2[0]['amount']) + $sahmNan;
            }
            else{
                $finalAmount=0;
                $sahmNan=0;
                $BreadNum=0;
            }

            $sql = "UPDATE `overtime_lunch` SET `restaurantID`={$restaurantID},`foodID`={$foodID},`drinkID`={$drinkID},`bread`={$sahmNan},`breadNumber`={$BreadNum},`finalAmount`={$finalAmount} WHERE `RowID`={$olid}";
            $ut->fileRecorder('Update:'.$sql);
          
            $db->Query($sql);
            $res = $db->AffectedRows();
            $res = ($res == -1 ? 0 : 1);
            if(!intval($res)){
                error_log(2);
                $flag = false;
            }
        }
   
        if($flag){
            mysqli_commit($db->Getcon());
            return true;
        }else{
            mysqli_rollback($db->Getcon());
            error_log(3);
            return false;
        }
    }

    public function deleteOvertimeLunch($olid){
        $acm = new acm();
        if(!$acm->hasAccess('overtimeLunchDelete')){
            return false;
        }
        $db = new DBi();

        $query = "SELECT `finalTick` FROM `overtime_lunch` WHERE `RowID`={$olid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['finalTick']) == 1){
            $res = "این ناهار تایید نهایی شده است و امکان حذف ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "DELETE FROM `overtime_lunch` WHERE `RowID`={$olid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getPrintOvertimeLunchHTM($sDate,$eDate,$personnel,$restaurant,$meal){
        $acm = new acm();
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if($acm->hasAccess('overtimeLunchDetailsManage')) {
            if (strlen(trim($sDate)) > 0) {
                $sDate = $ut->jal_to_greg($sDate);
                $w[] = '`createDate` >="' . $sDate . '" ';
            }
            if (strlen(trim($eDate)) > 0) {
                $eDate = $ut->jal_to_greg($eDate);
                $w[] = '`createDate` <="' . $eDate . '" ';
            }
        }else{
            $nowDate = date('Y-m-d');
            $w[] = '`createDate`="'.$nowDate.'" ';
        }
        if (intval($personnel) > 0){
            $w[] = '`pid`='.$personnel.' ';
        }
        if (intval($restaurant) > 0){
            $w[] = '`restaurantID`='.$restaurant.' ';
        }
        if (intval($meal) >= 0){
            $w[] = '`meal`='.$meal.' ';
        }

        $sql = "SELECT * FROM `overtime_lunch`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `createDate` ASC ";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        $htm .= '<div class="demoOvertimeLunch" style="margin-top: -60px;">';
        if ($acm->hasAccess('overtimeLunchDetailsManage')) {
            $htm .= '<table class="table table-sm" id="overtimeLunch-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="text-dark">';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 5%;">ردیف</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 5%;">وعده</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 6%;">تاریخ</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 6%;">واحد</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 9%;">نام و نام خانوادگی</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 7%;">کد پرسنلی</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 5%;">نوع</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 9%;">رستوران</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 9%;">غذا</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 9%;">نوشیدنی</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 9%;">سهم نان</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 12%;">قیمت نهایی</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 9%;">توضیحات</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $iterator = 0;
            $totalAmount = 0;
            $foodAmount = 0;
            for ($i = 0; $i < $cnt; $i++) {
                $iterator++;
                $query1 = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$res[$i]['unit']}";
                $rst1 = $db->ArrayQuery($query1);
                $query2 = "SELECT `restaurant_Name` FROM `restaurant` WHERE `RowID`={$res[$i]['restaurantID']}";
                $rst2 = $db->ArrayQuery($query2);
                $query3 = "SELECT `food_Name`,`amount` FROM `food` WHERE `RowID`={$res[$i]['foodID']}";
                $rst3 = $db->ArrayQuery($query3);
                $query4 = "SELECT `drink_Name` FROM `drink` WHERE `RowID`={$res[$i]['drinkID']}";
                $rst4 = $db->ArrayQuery($query4);
                $type = (intval($res[$i]['type']) == 1 ? 'پرسنل' : 'میهمان');
                $totalAmount += $res[$i]['finalAmount'];
                $foodAmount += $rst3[0]['amount'];

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . (intval($res[$i]['meal']) == 0 ? 'ناهار' : 'شام') . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $ut->greg_to_jal($res[$i]['createDate']) . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $rst1[0]['Uname'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $res[$i]['fname'].' '.$res[$i]['lname'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $res[$i]['pCode'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $type . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $rst2[0]['restaurant_Name'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $rst3[0]['food_Name'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $rst4[0]['drink_Name'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . number_format($res[$i]['bread']) . ' ریال</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . number_format($res[$i]['finalAmount']) . ' ریال</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $res[$i]['description'] . '</td>';
                $htm .= '</tr>';
            }

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td colspan="7" style="border: 2px solid #000;text-align: center;font-family: dubai-Regular;padding: 10px;">جمع کل مبلغ ( با احتساب هزینه نوشیدنی و نان )</td>';
            $htm .= '<td colspan="6" style="border: 2px solid #000;text-align: right;font-family: dubai-Regular;padding: 10px;">' . number_format($totalAmount) . ' ریال</td>';
            $htm .= '</tr>';

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td colspan="7" style="border: 2px solid #000;text-align: center;font-family: dubai-Regular;padding: 10px;">جمع کل مبلغ ( بدون احتساب هزینه نوشیدنی و نان )</td>';
            $htm .= '<td colspan="6" style="border: 2px solid #000;text-align: right;font-family: dubai-Regular;padding: 10px;">' . number_format($foodAmount) . ' ریال</td>';
            $htm .= '</tr>';
            $htm .= '</tbody>';
            $htm .= '</table>';
        }else{
            $htm .= '<table class="table table-sm" id="overtimeLunch1-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="text-dark">';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 5%;">ردیف</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 15%;">واحد</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 50%;">نام و نام خانوادگی</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 10%;">رستوران</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 10%;">غذا</td>';
            $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 10%;">مسیر</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $iterator = 0;
            $totalAmount = 0;
            for ($i = 0; $i < $cnt; $i++) {
                $iterator++;
                $query1 = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$res[$i]['unit']}";
                $rst1 = $db->ArrayQuery($query1);
                $query2 = "SELECT `restaurant_Name` FROM `restaurant` WHERE `RowID`={$res[$i]['restaurantID']}";
                $rst2 = $db->ArrayQuery($query2);
                $query3 = "SELECT `food_Name` FROM `food` WHERE `RowID`={$res[$i]['foodID']}";
                $rst3 = $db->ArrayQuery($query3);
                $query4 = "SELECT `routeName` FROM `service_routes` WHERE `RowID`={$res[$i]['route']}";
                $rst4 = $db->ArrayQuery($query4);
                $rst4[0]['routeName'] = (count($rst4) > 0 ? $rst4[0]['routeName'] : 'میهمان');
                $totalAmount += $res[$i]['finalAmount'];

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;font-size: 18px;padding: 10px;border: 2px solid #000;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;font-size: 18px;padding: 10px;border: 2px solid #000;">' . $rst1[0]['Uname'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;font-size: 18px;padding: 10px;border: 2px solid #000;">' . $res[$i]['fname'].' '.$res[$i]['lname'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;font-size: 18px;padding: 10px;border: 2px solid #000;">' . $rst2[0]['restaurant_Name'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;font-size: 18px;padding: 10px;border: 2px solid #000;">' . $rst3[0]['food_Name'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;font-size: 18px;padding: 10px;border: 2px solid #000;">' . $rst4[0]['routeName'] . '</td>';
                $htm .= '</tr>';
            }
            $htm .= '</tbody>';
            $htm .= '</table>';
        }
        $htm .= '</div>';
        return $htm;
    }

    public function getOvertimeLunchExcel($sDate,$eDate,$personnel,$restaurant,$meal){
        $acm = new acm();
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if($acm->hasAccess('overtimeLunchDetailsManage')) {
            if (strlen(trim($sDate)) > 0) {
                $sDate = $ut->jal_to_greg($sDate);
                $w[] = '`createDate` >="' . $sDate . '" ';
            }
            if (strlen(trim($eDate)) > 0) {
                $eDate = $ut->jal_to_greg($eDate);
                $w[] = '`createDate` <="' . $eDate . '" ';
            }
        }else{
            $nowDate = date('Y-m-d');
            $w[] = '`createDate`="'.$nowDate.'" ';
        }
        if (intval($personnel) > 0){
            $w[] = '`pid`='.$personnel.' ';
        }
        if (intval($restaurant) > 0){
            $w[] = '`restaurantID`='.$restaurant.' ';
        }
        if (intval($meal) >= 0){
            $w[] = '`meal`='.$meal.' ';
        }

        $sql = "SELECT * FROM `overtime_lunch`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `createDate` ASC ";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            $meal=$this->get_overtime_meal($res[$i]['meal']);
            // switch(intval($res[$i]['meal'])){
            //     case "0":
            //         $meal="ناهار";
            //         break;
            //     case "1":
            //         $meal="شام";
            //         break;
            //     case "2":
            //         $meal="بدون ناهار";
            //         break;
            // }
            $query1 = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$res[$i]['unit']}";
            $rst1 = $db->ArrayQuery($query1);
            $res[$i]['unit'] = $rst1[0]['Uname'];

            $query2 = "SELECT `restaurant_Name` FROM `restaurant` WHERE `RowID`={$res[$i]['restaurantID']}";
            $rst2 = $db->ArrayQuery($query2);
            $res[$i]['restaurantID'] = $rst2[0]['restaurant_Name'];

            $query3 = "SELECT `food_Name`,`amount` FROM `food` WHERE `RowID`={$res[$i]['foodID']}";
            $rst3 = $db->ArrayQuery($query3);
            $res[$i]['foodID'] = $rst3[0]['food_Name'];
            $res[$i]['foodAmount'] = $rst3[0]['amount'];

            $query4 = "SELECT `drink_Name` FROM `drink` WHERE `RowID`={$res[$i]['drinkID']}";
            $rst4 = $db->ArrayQuery($query4);
            $res[$i]['drinkID'] = $rst4[0]['drink_Name'];

            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);
            $res[$i]['type'] = (intval($res[$i]['type']) == 1 ? 'پرسنل' : 'میهمان');
            $res[$i]['meal'] = $meal;
            $res[$i]['fname'] = $res[$i]['fname'].' '.$res[$i]['lname'];
        }
        return $res;
    }

    public function finalTickLunch($sDate,$eDate){
        $acm = new acm();
        if(!$acm->hasAccess('finalTickLunch')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $sDate = $ut->jal_to_greg($sDate);
        $eDate = $ut->jal_to_greg($eDate);
        if (strtotime($sDate) >= strtotime($eDate)){
            $res = "بازه زمانی نامعتبر است !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "UPDATE `overtime_lunch` SET `finalTick`=1 WHERE `createDate`>='{$sDate}' AND `createDate`<='{$eDate}'";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getUnitOvertimeLunchExcel($sDate,$eDate){
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if (strlen(trim($sDate)) > 0) {
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`createDate` >="' . $sDate . '" ';
        }
        if (strlen(trim($eDate)) > 0) {
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`createDate` <="' . $eDate . '" ';
        }

        $prices = array();
        $query = "SELECT `RowID`,`Uname` FROM `official_productive_units`";
        $rst = $db->ArrayQuery($query);
        $cntc = count($rst);
        for ($a=0;$a<$cntc;$a++){
            $prices[$rst[$a]['RowID']] = 0;
        }

        $sql = "SELECT `finalAmount`,`unit` FROM `overtime_lunch`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        for ($i=0;$i<$cnt;$i++){
            $prices[$res[$i]['unit']] += intval($res[$i]['finalAmount']);
        }

        $keyPrice = array_keys($prices);
        $cntkey = count($keyPrice);
        $result = array();
        for ($i=0;$i<$cntkey;$i++){
            $sql2 = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$keyPrice[$i]}";
            $rst2 = $db->ArrayQuery($sql2);
            $result[$i]['Uname'] = $rst2[0]['Uname'];
            $result[$i]['amount'] = $prices[$keyPrice[$i]];
        }
        return $result;
    }

}