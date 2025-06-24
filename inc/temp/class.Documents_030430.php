<?php

class Documents{

    public function __construct(){
        // do nothing
    }

    public function getOrganizationalDocumentationManageHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('organizationalDocumentationManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $user = new User();
        $rate = new Rates();

        $users = $user->getUsers();
        $cntu = count($users);

        $units = $rate->getUnits();
        $cntun = count($units);

        $hiddenContentId[] = "hiddenOrganizationalDocumentationBody";
        $pagename = array();
        $pageIcon = array();
        $contentId = array();
        $menuItems = array();
        $bottons = array();
        $headerSearch = array();
        $access = array();

        $x = 0;
        $y = 0;
        $z = 0;
        $manifold = 0;
        /*
        if($acm->hasAccess('regulationsManage')) {
            $pagename[$x] = "آئین نامه ها و دستورالعمل ها";
            $pageIcon[$x] = "fa-file";
            $contentId[$x] = "regulationsManageBody";
            $menuItems[$x] = 'regulationsManageTabID';

            $bottons1 = array();
            $headerSearch1 = array();

            $b = 0;
            if($acm->hasAccess('createNewRegulations')) {
                $bottons1[$b]['title'] = "ثبت فایل جدید";
                $bottons1[$b]['jsf'] = "createRegulations";
                $bottons1[$b]['icon'] = "fa-plus-square";
                $b++;

                $bottons1[$b]['title'] = "ویرایش فایل";
                $bottons1[$b]['jsf'] = "editRegulations";
                $bottons1[$b]['icon'] = "fa-edit";
                $b++;

                $bottons1[$b]['title'] = "حذف فایل";
                $bottons1[$b]['jsf'] = "deleteRegulations";
                $bottons1[$b]['icon'] = "fa-minus-square";
                $b++;
            }

            if($acm->hasAccess('attachFileToRegulations')) {
                $bottons1[$b]['title'] = "پیوست فایل";
                $bottons1[$b]['jsf'] = "attachFileToRegulations";
                $bottons1[$b]['icon'] = "fa-link";
            }

            $a = 0;
            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "90px";
            $headerSearch1[$a]['id'] = "regulationsManageSDateSearch";
            $headerSearch1[$a]['title'] = "تاریخ شروع";
            $headerSearch1[$a]['placeholder'] = "تاریخ شروع";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "90px";
            $headerSearch1[$a]['id'] = "regulationsManageEDateSearch";
            $headerSearch1[$a]['title'] = "تاریخ پایان";
            $headerSearch1[$a]['placeholder'] = "تاریخ پایان";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "200px";
            $headerSearch1[$a]['id'] = "regulationsManageFNameSearch";
            $headerSearch1[$a]['title'] = "قسمتی از نام فایل";
            $headerSearch1[$a]['placeholder'] = "قسمتی از نام فایل";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "120px";
            $headerSearch1[$a]['id'] = "regulationsManageFCodeSearch";
            $headerSearch1[$a]['title'] = "کد فایل";
            $headerSearch1[$a]['placeholder'] = "کد فایل";
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['width'] = "100px";
            $headerSearch1[$a]['id'] = "regulationsManageStatusSearch";
            $headerSearch1[$a]['title'] = "وضعیت فایل";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = "فعال";
            $headerSearch1[$a]['options'][0]["value"] = "1";
            $headerSearch1[$a]['options'][1]["title"] = "غیرفعال";
            $headerSearch1[$a]['options'][1]["value"] = "0";
            $a++;

            $headerSearch1[$a]['type'] = "btn";
            $headerSearch1[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch1[$a]['jsf'] = "showRegulationsManageList";

            $bottons[$y] = $bottons1;
            $headerSearch[$z] = $headerSearch1;

            $manifold++;
            $access[] = 1;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('circularsManage')) {
            $pagename[$x] = "بخشنامه ها، مصوبات و ابلاغیه ها";
            $pageIcon[$x] = "fa-file";
            $contentId[$x] = "circularsManageBody";
            $menuItems[$x] = 'circularsManageTabID';

            $bottons2 = array();
            $headerSearch2 = array();

            $b = 0;
            if($acm->hasAccess('editCreateNewCirculars')) {
                $bottons2[$b]['title'] = "ثبت فایل جدید";
                $bottons2[$b]['jsf'] = "createCirculars";
                $bottons2[$b]['icon'] = "fa-plus-square";
                $b++;

                $bottons2[$b]['title'] = "ویرایش فایل";
                $bottons2[$b]['jsf'] = "editCirculars";
                $bottons2[$b]['icon'] = "fa-edit";
                $b++;

                $bottons2[$b]['title'] = "حذف فایل";
                $bottons2[$b]['jsf'] = "deleteCirculars";
                $bottons2[$b]['icon'] = "fa-minus-square";
                $b++;
            }

            if($acm->hasAccess('attachFileToCirculars')) {
                $bottons2[$b]['title'] = "پیوست فایل";
                $bottons2[$b]['jsf'] = "attachFileToCirculars";
                $bottons2[$b]['icon'] = "fa-link";
            }

            $a = 0;
            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "90px";
            $headerSearch2[$a]['id'] = "circularsManageSDateSearch";
            $headerSearch2[$a]['title'] = "تاریخ شروع";
            $headerSearch2[$a]['placeholder'] = "تاریخ شروع";
            $a++;

            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "90px";
            $headerSearch2[$a]['id'] = "circularsManageEDateSearch";
            $headerSearch2[$a]['title'] = "تاریخ پایان";
            $headerSearch2[$a]['placeholder'] = "تاریخ پایان";
            $a++;

            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "200px";
            $headerSearch2[$a]['id'] = "circularsManageFNameSearch";
            $headerSearch2[$a]['title'] = "قسمتی از نام فایل";
            $headerSearch2[$a]['placeholder'] = "قسمتی از نام فایل";
            $a++;

            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "120px";
            $headerSearch2[$a]['id'] = "circularsManageFCodeSearch";
            $headerSearch2[$a]['title'] = "کد فایل";
            $headerSearch2[$a]['placeholder'] = "کد فایل";
            $a++;

            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['width'] = "100px";
            $headerSearch2[$a]['id'] = "circularsManageTypeSearch";
            $headerSearch2[$a]['title'] = "نوع فایل";
            $headerSearch2[$a]['options'] = array();
            $headerSearch2[$a]['options'][0]["title"] = "نوع فایل";
            $headerSearch2[$a]['options'][0]["value"] = 0;
            $headerSearch2[$a]['options'][1]["title"] = "بخشنامه ها";
            $headerSearch2[$a]['options'][1]["value"] = 1;
            $headerSearch2[$a]['options'][2]["title"] = "مصوبات";
            $headerSearch2[$a]['options'][2]["value"] = 2;
            $headerSearch2[$a]['options'][3]["title"] = "ابلاغیه ها";
            $headerSearch2[$a]['options'][3]["value"] = 3;
            $a++;

            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['width'] = "100px";
            $headerSearch2[$a]['id'] = "circularsManageStatusSearch";
            $headerSearch2[$a]['title'] = "وضعیت فایل";
            $headerSearch2[$a]['options'] = array();
            $headerSearch2[$a]['options'][0]["title"] = "فعال";
            $headerSearch2[$a]['options'][0]["value"] = "1";
            $headerSearch2[$a]['options'][1]["title"] = "غیرفعال";
            $headerSearch2[$a]['options'][1]["value"] = "0";
            $a++;

            $headerSearch2[$a]['type'] = "btn";
            $headerSearch2[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch2[$a]['jsf'] = "showCircularsManageList";

            $bottons[$y] = $bottons2;
            $headerSearch[$z] = $headerSearch2;

            $manifold++;
            $access[] = 2;
            $x++;
            $y++;
            $z++;
        }*/
        if($acm->hasAccess('legalContractsManage')) {
            $pagename[$x] = "قراردادهای حقوقی";
            $pageIcon[$x] = "fa-address-card";
            $contentId[$x] = "legalContractsManageBody";
            $menuItems[$x] = 'legalContractsManageTabID';

            $bottons3 = array();
            $headerSearch3 = array();

            $b = 0;
            if($acm->hasAccess('editCreateLegalContract')) {
                $bottons3[$b]['title'] = "ثبت قرارداد جدید";
                $bottons3[$b]['jsf'] = "createLegalContract";
                $bottons3[$b]['icon'] = "fa-plus-square";
                $b++;

                $bottons3[$b]['title'] = "ویرایش قرارداد";
                $bottons3[$b]['jsf'] = "editLegalContract";
                $bottons3[$b]['icon'] = "fa-edit";
                $b++;

                $bottons3[$b]['title'] = "حذف قرارداد";
                $bottons3[$b]['jsf'] = "deleteLegalContract";
                $bottons3[$b]['icon'] = "fa-minus-square";
                $b++;
            }

            if($acm->hasAccess('attachFileToLegalContract')) {
                $bottons3[$b]['title'] = "پیوست فایل";
                $bottons3[$b]['jsf'] = "attachFileToLegalContract";
                $bottons3[$b]['icon'] = "fa-link";
            }

            $a = 0;
            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "90px";
            $headerSearch3[$a]['id'] = "legalContractManageSDateSearch";
            $headerSearch3[$a]['title'] = "تاریخ شروع قرارداد";
            $headerSearch3[$a]['placeholder'] = "تاریخ شروع";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "90px";
            $headerSearch3[$a]['id'] = "legalContractManageEDateSearch";
            $headerSearch3[$a]['title'] = "تاریخ پایان قرارداد";
            $headerSearch3[$a]['placeholder'] = "تاریخ پایان";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "200px";
            $headerSearch3[$a]['id'] = "legalContractManageSubjectSearch";
            $headerSearch3[$a]['title'] = "قسمتی از موضوع قرارداد";
            $headerSearch3[$a]['placeholder'] = "قسمتی از موضوع قرارداد";
            $a++;

            $headerSearch3[$a]['type'] = "text";
            $headerSearch3[$a]['width'] = "120px";
            $headerSearch3[$a]['id'] = "legalContractManageCIDSearch";
            $headerSearch3[$a]['title'] = "شماره قرارداد";
            $headerSearch3[$a]['placeholder'] = "شماره قرارداد";
            $a++;

            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['width'] = "100px";
            $headerSearch3[$a]['id'] = "legalContractManageTypeSearch";
            $headerSearch3[$a]['title'] = "نوع قرارداد";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = "نوع قرارداد";
            $headerSearch3[$a]['options'][0]["value"] = -1;
            $headerSearch3[$a]['options'][1]["title"] = "فورج";
            $headerSearch3[$a]['options'][1]["value"] = 0;
            $headerSearch3[$a]['options'][2]["title"] = "سهامی";
            $headerSearch3[$a]['options'][2]["value"] = 1;
            $headerSearch3[$a]['options'][3]["title"] = "حقیقی";
            $headerSearch3[$a]['options'][3]["value"] = 2;
            $a++;

            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['width'] = "100px";
            $headerSearch3[$a]['id'] = "legalContractManageStatusSearch";
            $headerSearch3[$a]['title'] = "وضعیت قرارداد";
            $headerSearch3[$a]['options'] = array();
            $headerSearch3[$a]['options'][0]["title"] = "فعال";
            $headerSearch3[$a]['options'][0]["value"] = "1";
            $headerSearch3[$a]['options'][1]["title"] = "غیرفعال";
            $headerSearch3[$a]['options'][1]["value"] = "0";
            $a++;

            $headerSearch3[$a]['type'] = "btn";
            $headerSearch3[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch3[$a]['jsf'] = "showLegalContractsManageList";

            $bottons[$y] = $bottons3;
            $headerSearch[$z] = $headerSearch3;

            $manifold++;
            $access[] = 3;
            /*            $x++;
                        $y++;
                        $z++;*/
        }
        $htm = $ut->getHtmlOfDefaultManagementMultiPage($pagename,$pageIcon,$contentId,$menuItems,$bottons,$manifold,$headerSearch,$hiddenContentId);
        //++++++++++++++++++++++++++++++++++ Edit CREATE MODAL ++++++++++++++++++++++++++++++++
        $modalID = "regulationsManageModal";
        $modalTitle = "فرم ثبت/ویرایش فایل";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "regulationsManageFName";
        $items[$c]['title'] = "نام فایل";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "regulationsManageFCode";
        $items[$c]['title'] = "کد فایل";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "regulationsManageAccessID";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "افراد مجاز جهت دانلود فایل";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cntu;$i++){
            $items[$c]['options'][$i+1]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
            $items[$c]['options'][$i+1]["value"] = $users[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "regulationsManageSDate";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "تاریخ شروع";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "regulationsManageEDate";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "تاریخ پایان";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "regulationsManageDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "regulationsManageHiddenRid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateRegulations";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++ END OF Edit CREATE MODAL ++++++++++++++++++
        //++++++++++++++++++ Regulations Add Attachment File Modal ++++++++++++++++++++++
        $modalID = "regulationsAttachmentFileModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'regulationsAttachmentFile-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "regulationsManageAttachmentName";
        $items[$c]['title'] = "نام فایل";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "regulationsManageAttachment";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF, JPG , PNG , XLSX , DOCX باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "regulationsManageAttachmentID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachFileToRegulations";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $regulationsAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Regulations Add Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Regulations Attachment File Modal ++++++++++++++++++++++
        $modalID = "showRegulationsAttachmentFileModal";
        $modalTitle = "دانلود فایل های پیوست";
        $ShowDescription = 'showRegulationsAttachmentFile-body';
        $style = 'style="max-width: 655px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showRegulationsAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Regulations Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ DELETE MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "manageDeleteRegulationsModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به حذف این فایل مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "regulationsManage_deleteIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "dodeleteRegulations";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $delModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF DELETE MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Edit CREATE Circulars MODAL ++++++++++++++++++++++++++++++++
        $modalID = "circularsManageModal";
        $modalTitle = "فرم ثبت/ویرایش فایل";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "circularsManageFType";
        $items[$c]['title'] = "نوع فایل";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = "بخشنامه ها";
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = "مصوبات";
        $items[$c]['options'][2]["value"] = 2;
        $items[$c]['options'][3]["title"] = "ابلاغیه ها";
        $items[$c]['options'][3]["value"] = 3;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "circularsManageFName";
        $items[$c]['title'] = "نام فایل";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "circularsManageFCode";
        $items[$c]['title'] = "کد فایل";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "circularsManageAccessID";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "افراد مجاز جهت دانلود فایل";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cntu;$i++){
            $items[$c]['options'][$i+1]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
            $items[$c]['options'][$i+1]["value"] = $users[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "circularsManageSDate";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "تاریخ شروع";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "circularsManageEDate";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "تاریخ پایان";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "circularsManageDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "circularsManageHiddenCid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateCirculars";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateCircularsModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++ END OF Edit CREATE Circulars MODAL ++++++++++++++++++
        //++++++++++++++++++ Circulars Add Attachment File Modal ++++++++++++++++++++++
        $modalID = "circularsAttachmentFileModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'circularsAttachmentFile-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "circularsManageAttachmentName";
        $items[$c]['title'] = "نام فایل";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "circularsManageAttachment";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF, JPG , PNG , XLSX , DOCX باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "circularsManageAttachmentID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachFileToCirculars";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $circularsAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Circulars Add Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Circulars Attachment File Modal ++++++++++++++++++++++
        $modalID = "showCircularsAttachmentFileModal";
        $modalTitle = "دانلود فایل های پیوست";
        $ShowDescription = 'showCircularsAttachmentFile-body';
        $style = 'style="max-width: 655px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showCircularsAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Circulars Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ DELETE Circulars MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "manageDeleteCircularsModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به حذف این فایل مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "circularsManage_deleteIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "dodeleteCirculars";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $delCircularsModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF DELETE Circulars MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++EDIT CREATE Legal Contract MODAL++++++++++++++++++++++++++++++++
        $modalID = "legalContractsManageModal";
        $modalTitle = "فرم ایجاد/ویرایش قرارداد";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "legalContractsManageType";
        $items[$c]['title'] = "نوع قرارداد";
        $items[$c]['onchange'] = "onchange=changeLegalContractsFields()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "فورج";
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = "سهامی";
        $items[$c]['options'][2]["value"] = 1;
        $items[$c]['options'][3]["title"] = "حقیقی";
        $items[$c]['options'][3]["value"] = 2;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "legalContractsManageUnit";
        $items[$c]['title'] = "واحد";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cntun;$i++){
            $items[$c]['options'][$i+1]["title"] = $units[$i]['Uname'];
            $items[$c]['options'][$i+1]["value"] = $units[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "legalContractsManageCID";
        $items[$c]['title'] = "شماره قرارداد";
        $items[$c]['placeholder'] = "شماره قرارداد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "legalContractsManageSubject";
        $items[$c]['title'] = "موضوع قرارداد";
        $items[$c]['placeholder'] = "موضوع";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "legalContractsManageSideOne";
        $items[$c]['title'] = "طرف اول قرارداد";
        $items[$c]['placeholder'] = "طرف اول";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "legalContractsManageSideTwo";
        $items[$c]['title'] = "طرف دوم قرارداد";
        $items[$c]['placeholder'] = "طرف دوم";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "legalContractsManageSideTwoFS";
        $items[$c]['onchange'] = "onchange=getSideTwoCodeTafzili()";
        $items[$c]['title'] = "طرف دوم قرارداد";
        $items[$c]['placeholder'] = "طرف دوم قرارداد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "legalContractsManageCodeTafzili";
        $items[$c]['title'] = "کد تفضیلی";
        $items[$c]['placeholder'] = "کد تفضیلی";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "legalContractsManageSdate";
        $items[$c]['style'] = "style='width: 150px;float: right;'";
        $items[$c]['title'] = "تاریخ شروع قرارداد";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "legalContractsManageEdate";
        $items[$c]['style'] = "style='width: 150px;float: right;'";
        $items[$c]['title'] = "تاریخ پایان قرارداد";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "legalContractsManagePhone";
        $items[$c]['title'] = "تلفن ثابت";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "legalContractsManageMobile";
        $items[$c]['title'] = "تلفن همراه";
        $items[$c]['placeholder'] = "شماره";
        $c++;

/*        $items[$c]['type'] = "text";
        $items[$c]['id'] = "legalContractsManageTermContract";
        $items[$c]['title'] = "مدت قرارداد";
        $items[$c]['placeholder'] = "ماه / روز";
        $c++;*/

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "legalContractsManageAmount";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['title'] = "مبلغ قرارداد";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "legalContractsManageDesc";
        $items[$c]['title'] = "تضامین";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "legalContractsManageHiddenLid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateLegalContract";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateLegalModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE Legal Contract MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ DELETE MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "manageDeleteLegalContractModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به حذف این قرارداد مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "legalContract_deleteIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "dodeleteLegalContract";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $delLegalContractModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF DELETE MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++ Legal Contract Add Attachment File Modal ++++++++++++++++++++++
        $modalID = "legalContractAttachmentFileModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'legalContractAttachmentFile-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "legalContractManageAttachmentName";
        $items[$c]['title'] = "نام فایل";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "legalContractManageAttachment";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF, JPG , PNG , XLSX , DOCX باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "legalContractManageAttachmentID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachFileToLegalContract";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $legalContractAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Legal Contract Add Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Legal Contract Attachment File Modal ++++++++++++++++++++++
        $modalID = "showLegalContractAttachFileModal";
        $modalTitle = "دانلود فایل های پیوست";
        $ShowDescription = 'showLegalContractAttachFile-body';
        $style = 'style="max-width: 655px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showLegalContractAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Legal Contract Attachment File Modal ++++++++++++++++++++++++
        $htm .= $editCreateModal;
        $htm .= $regulationsAttachmentFile;
        $htm .= $showRegulationsAttachmentFile;
        $htm .= $delModal;
        $htm .= $editCreateCircularsModal;
        $htm .= $circularsAttachmentFile;
        $htm .= $showCircularsAttachmentFile;
        $htm .= $delCircularsModal;
        $htm .= $editCreateLegalModal;
        $htm .= $delLegalContractModal;
        $htm .= $legalContractAttachmentFile;
        $htm .= $showLegalContractAttachmentFile;
        $send = array($htm,$access);
        return $send;
    }

    //++++++++++++++++++++++ آئین نامه ها و دستورالعمل ها +++++++++++++++++++++++

    public function getRegulationsManageList($Name,$Code,$SDate,$EDate,$status,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('regulationsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $flag = ($acm->hasAccess('createNewRegulations') || $acm->hasAccess('attachFileToRegulations') ? true : false);
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($Name)) > 0){
            $w[] = '`Name` LIKE "%'.$Name.'%" ';
        }
        if(strlen(trim($Code)) > 0){
            $w[] = '`Code`="'.$Code.'" ';
        }
        if(strlen(trim($SDate)) > 0){
            $SDate = $ut->jal_to_greg($SDate);
            $w[] = '`startDate` >="'.$SDate.'" ';
        }
        if(strlen(trim($EDate)) > 0){
            $EDate = $ut->jal_to_greg($EDate);
            $w[] = '`endDate` <="'.$EDate.'" ';
        }
        $w[] = '`isEnable`='.$status.' ';

        $sql = "SELECT * FROM `regulations`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $users = array();
            $access = explode(',',$res[$y]['accessID']);
            if (!$flag) {
                if (!in_array($_SESSION['userid'], $access)) {
                    continue;
                }
            }
            $cnt = count($access);
            for ($i=0;$i<$cnt;$i++){
                $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$access[$i]}";
                $rst = $db->ArrayQuery($query);
                $users[] = $rst[0]['fname'].' '.$rst[0]['lname'];
            }
            $finalRes[$y]['users'] = implode(' , ',$users);
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['Name'] = $res[$y]['Name'];
            $finalRes[$y]['Code'] = $res[$y]['Code'];
            $finalRes[$y]['startDate'] = $ut->greg_to_jal($res[$y]['startDate']);
            $finalRes[$y]['endDate'] = $ut->greg_to_jal($res[$y]['endDate']);
            $finalRes[$y]['description'] = $res[$y]['description'];
        }
        $finalRes = array_values($finalRes);
        return $finalRes;
    }

    public function getRegulationsManageListCountRows($Name,$Code,$SDate,$EDate,$status){
        $db = new DBi();
        $acm = new acm();
        $ut = new Utility();
        $flag = ($acm->hasAccess('createNewRegulations') || $acm->hasAccess('attachFileToRegulations') ? true : false);
        $w = array();
        if(strlen(trim($Name)) > 0){
            $w[] = '`Name` LIKE "%'.$Name.'%" ';
        }
        if(strlen(trim($Code)) > 0){
            $w[] = '`Code`="'.$Code.'" ';
        }
        if(strlen(trim($SDate)) > 0){
            $SDate = $ut->jal_to_greg($SDate);
            $w[] = '`startDate` >="'.$SDate.'" ';
        }
        if(strlen(trim($EDate)) > 0){
            $EDate = $ut->jal_to_greg($EDate);
            $w[] = '`endDate` <="'.$EDate.'" ';
        }
        $w[] = '`isEnable`='.$status.' ';

        $sql = "SELECT `RowID`,`accessID` FROM `regulations`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $access = explode(',',$res[$y]['accessID']);
            if (!$flag) {
                if (!in_array($_SESSION['userid'], $access)) {
                    continue;
                }
            }
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
        }
        $finalRes = array_values($finalRes);
        return count($finalRes);
    }

    public function regulationsInfo($rid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `regulations` WHERE `RowID`=".$rid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("rid"=>$rid,"Name"=>$res[0]['Name'],"Code"=>$res[0]['Code'],"accessID"=>$res[0]['accessID'],"startDate"=>$ut->greg_to_jal($res[0]['startDate']),"endDate"=>$ut->greg_to_jal($res[0]['endDate']),"description"=>$res[0]['description']);
            return $res;
        }else{
            return false;
        }
    }

    public function createRegulations($fname,$fcode,$SDate,$EDate,$desc,$accID){
        $acm = new acm();
        if(!$acm->hasAccess('regulationsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $SDate = $ut->jal_to_greg($SDate);
        $EDate = $ut->jal_to_greg($EDate);
        $sql = "INSERT INTO `regulations` (`Name`,`Code`,`accessID`,`startDate`,`endDate`,`description`) VALUES ('{$fname}','{$fcode}','{$accID}','{$SDate}','{$EDate}','{$desc}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editRegulations($rid,$fname,$fcode,$SDate,$EDate,$desc,$accID){
        $acm = new acm();
        if(!$acm->hasAccess('regulationsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $SDate = $ut->jal_to_greg($SDate);
        $EDate = $ut->jal_to_greg($EDate);
        $sql = "UPDATE `regulations` SET `Name`='{$fname}',`Code`='{$fcode}',`accessID`='{$accID}',`startDate`='{$SDate}',`endDate`='{$EDate}',`description`='{$desc}' WHERE `RowID`={$rid}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if (intval($res)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteRegulations($rid){
        $acm = new acm();
        if(!$acm->hasAccess('regulationsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `regulations` SET `isEnable`=0 WHERE `RowID`=".$rid;
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public function attachFileToRegulations($rid,$info,$files){
        $db = new DBi();
        $SFile = array();
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
                $SFile[] = "Regulations" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $cnt = count($SFile);
        for ($i=0;$i<$cnt;$i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i],'../documents/'.$SFile[$i]);
            $sql4 = "INSERT INTO `regulations_attachment` (`rid`,`fileName`,`fileInfo`) VALUES ({$rid},'{$SFile[$i]}','{$info}')";
            $db->Query($sql4);
        }
        return true;
    }

    public function deleteAttachRegulationsFile($fid){
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `regulations_attachment` WHERE `RowID`={$fid}";
        $res = $db->ArrayQuery($sql);
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/documents/' . $res[0]['fileName'];
        $result = unlink($file_to_delete);
        if ($result) {
            $query = "DELETE FROM `regulations_attachment` WHERE `RowID`={$fid}";
            $db->Query($query);
            return true;
        }else{
            return false;
        }
    }

    public function attachedRegulationsFileHtm($rid){
        $db = new DBi();
        $sql = "SELECT `RowID`,`fileName`,`fileInfo` FROM `regulations_attachment` WHERE `rid`={$rid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileRegulations-tableID">';
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
            $link = ADDR.'documents/'.$res[$i]['fileName'];

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachRegulationsFile('.$res[$i]['RowID'].')" ><i class="fas fa-trash"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function attachmentFileRegulationsHtm($rid){
        $db = new DBi();
        $sql = "SELECT `fileName`,`fileInfo` FROM `regulations_attachment` WHERE `rid`={$rid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileRegulations1-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 70%;">نام فایل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $link = ADDR.'documents/'.$res[$i]['fileName'];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    //++++++++++++++++++++++ بخشنامه ها +++++++++++++++++++++++

    public function getCircularsManageList($Name,$Code,$SDate,$EDate,$status,$type,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('circularsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $flag = ($acm->hasAccess('editCreateNewCirculars') || $acm->hasAccess('attachFileToCirculars') ? true : false);
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($Name)) > 0){
            $w[] = '`Name` LIKE "%'.$Name.'%" ';
        }
        if(strlen(trim($Code)) > 0){
            $w[] = '`Code`="'.$Code.'" ';
        }
        if(strlen(trim($SDate)) > 0){
            $SDate = $ut->jal_to_greg($SDate);
            $w[] = '`startDate` >="'.$SDate.'" ';
        }
        if(strlen(trim($EDate)) > 0){
            $EDate = $ut->jal_to_greg($EDate);
            $w[] = '`endDate` <="'.$EDate.'" ';
        }
        if(intval($type) > 0){
            $w[] = '`type`='.$type.' ';
        }
        $w[] = '`isEnable`='.$status.' ';

        $sql = "SELECT * FROM `circulars`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $users = array();
            $access = explode(',',$res[$y]['accessID']);
            if (!$flag) {
                if (!in_array($_SESSION['userid'], $access)) {
                    continue;
                }
            }
            $cnt = count($access);
            for ($i=0;$i<$cnt;$i++){
                $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$access[$i]}";
                $rst = $db->ArrayQuery($query);
                $users[] = $rst[0]['fname'].' '.$rst[0]['lname'];
            }
            $finalRes[$y]['users'] = implode(' , ',$users);
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            switch ($res[$y]['type']){
                case 1:
                    $finalRes[$y]['type'] = 'بخشنامه ها';
                    break;
                case 2:
                    $finalRes[$y]['type'] = 'مصوبات';
                    break;
                case 3:
                    $finalRes[$y]['type'] = 'ابلاغیه ها';
                    break;
            }
            $finalRes[$y]['Name'] = $res[$y]['Name'];
            $finalRes[$y]['Code'] = $res[$y]['Code'];
            $finalRes[$y]['startDate'] = $ut->greg_to_jal($res[$y]['startDate']);
            $finalRes[$y]['endDate'] = $ut->greg_to_jal($res[$y]['endDate']);
            $finalRes[$y]['description'] = $res[$y]['description'];
        }
        $finalRes = array_values($finalRes);
        return $finalRes;
    }

    public function getCircularsManageListCountRows($Name,$Code,$SDate,$EDate,$status,$type){
        $db = new DBi();
        $acm = new acm();
        $ut = new Utility();
        $flag = ($acm->hasAccess('editCreateNewCirculars') || $acm->hasAccess('attachFileToCirculars') ? true : false);
        $w = array();
        if(strlen(trim($Name)) > 0){
            $w[] = '`Name` LIKE "%'.$Name.'%" ';
        }
        if(strlen(trim($Code)) > 0){
            $w[] = '`Code`="'.$Code.'" ';
        }
        if(strlen(trim($SDate)) > 0){
            $SDate = $ut->jal_to_greg($SDate);
            $w[] = '`startDate` >="'.$SDate.'" ';
        }
        if(strlen(trim($EDate)) > 0){
            $EDate = $ut->jal_to_greg($EDate);
            $w[] = '`endDate` <="'.$EDate.'" ';
        }
        if(intval($type) > 0){
            $w[] = '`type`='.$type.' ';
        }
        $w[] = '`isEnable`='.$status.' ';

        $sql = "SELECT `RowID`,`accessID` FROM `circulars`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $access = explode(',',$res[$y]['accessID']);
            if (!$flag) {
                if (!in_array($_SESSION['userid'], $access)) {
                    continue;
                }
            }
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
        }
        $finalRes = array_values($finalRes);
        return count($finalRes);
    }

    public function circularsInfo($cid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `circulars` WHERE `RowID`=".$cid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("cid"=>$cid,"Name"=>$res[0]['Name'],"Code"=>$res[0]['Code'],"accessID"=>$res[0]['accessID'],"startDate"=>$ut->greg_to_jal($res[0]['startDate']),"endDate"=>$ut->greg_to_jal($res[0]['endDate']),"description"=>$res[0]['description'],"type"=>$res[0]['type']);
            return $res;
        }else{
            return false;
        }
    }

    public function createCirculars($fname,$fcode,$SDate,$EDate,$desc,$accID,$type){
        $acm = new acm();
        if(!$acm->hasAccess('circularsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $SDate = $ut->jal_to_greg($SDate);
        $EDate = $ut->jal_to_greg($EDate);
        $sql = "INSERT INTO `circulars` (`type`,`Name`,`Code`,`accessID`,`startDate`,`endDate`,`description`) VALUES ({$type},'{$fname}','{$fcode}','{$accID}','{$SDate}','{$EDate}','{$desc}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editCirculars($cid,$fname,$fcode,$SDate,$EDate,$desc,$accID,$type){
        $acm = new acm();
        if(!$acm->hasAccess('circularsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $SDate = $ut->jal_to_greg($SDate);
        $EDate = $ut->jal_to_greg($EDate);
        $sql = "UPDATE `circulars` SET `Name`='{$fname}',`Code`='{$fcode}',`accessID`='{$accID}',`startDate`='{$SDate}',`endDate`='{$EDate}',`description`='{$desc}',`type`={$type} WHERE `RowID`={$cid}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if (intval($res)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteCirculars($cid){
        $acm = new acm();
        if(!$acm->hasAccess('circularsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `circulars` SET `isEnable`=0 WHERE `RowID`=".$cid;
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public function attachedCircularsFileHtm($cid){
        $db = new DBi();
        $sql = "SELECT `RowID`,`fileName`,`fileInfo` FROM `circulars_attachment` WHERE `cid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileCirculars-tableID">';
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
            $link = ADDR.'documents/'.$res[$i]['fileName'];

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachCircularsFile('.$res[$i]['RowID'].')" ><i class="fas fa-trash"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function attachFileToCirculars($cid,$info,$files){
        $db = new DBi();
        $SFile = array();
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
                $SFile[] = "Circulars" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $cnt = count($SFile);
        for ($i=0;$i<$cnt;$i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i],'../documents/'.$SFile[$i]);
            $sql4 = "INSERT INTO `circulars_attachment` (`cid`,`fileName`,`fileInfo`) VALUES ({$cid},'{$SFile[$i]}','{$info}')";
            $db->Query($sql4);
        }
        return true;
    }

    public function deleteAttachCircularsFile($fid){
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `circulars_attachment` WHERE `RowID`={$fid}";
        $res = $db->ArrayQuery($sql);
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/documents/' . $res[0]['fileName'];
        $result = unlink($file_to_delete);
        if ($result) {
            $query = "DELETE FROM `circulars_attachment` WHERE `RowID`={$fid}";
            $db->Query($query);
            return true;
        }else{
            return false;
        }
    }

    public function attachmentFileCircularsHtm($cid){
        $db = new DBi();
        $sql = "SELECT `fileName`,`fileInfo` FROM `circulars_attachment` WHERE `cid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileCirculars1-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 70%;">نام فایل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $link = ADDR.'documents/'.$res[$i]['fileName'];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    //++++++++++++++++++++++ قراردادهای حقوقی +++++++++++++++++++++++

    public function getLegalContractsManageList($Subject,$CID,$SDate,$EDate,$type,$status,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('legalContractsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($Subject)) > 0){
            $w[] = '`subjectContract` LIKE "%'.$Subject.'%" ';
        }
        if(strlen(trim($CID)) > 0){
            $w[] = '`ContractID`="'.$CID.'" ';
        }
        if(strlen(trim($SDate)) > 0){
            $SDate = $ut->jal_to_greg($SDate);
            $w[] = '`BeginDateContract` >="'.$SDate.'" ';
        }
        if(strlen(trim($EDate)) > 0){
            $EDate = $ut->jal_to_greg($EDate);
            $w[] = '`EndDateContract` <="'.$EDate.'" ';
        }
        if(intval($type) >= 0){
            $w[] = '`type`='.$type.' ';
        }
        $w[] = '`isEnable`='.$status.' ';

        $query = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=31";
        $rsty = $db->ArrayQuery($query);
        $uids = str_replace('1','0',$rsty[0]['uids']);
        $uids = explode(',',$uids);
        if (in_array($_SESSION['userid'],$uids)){
            $w[] = '`unitID`=22 ';
        }

        $sql = "SELECT * FROM `legal_contracts`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $sqq = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$res[$y]['unitID']}";
            $rst = $db->ArrayQuery($sqq);
            $finalRes[$y]['Uname'] = $rst[0]['Uname'];
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['ContractID'] = $res[$y]['ContractID'];
            $finalRes[$y]['subjectContract'] = $res[$y]['subjectContract'];
            $finalRes[$y]['sideOne'] = $res[$y]['sideOne'];
            $finalRes[$y]['sideTwo'] = $res[$y]['sideTwo'];
            $finalRes[$y]['BeginDateContract'] = $ut->greg_to_jal($res[$y]['BeginDateContract']);
            $finalRes[$y]['EndDateContract'] = $ut->greg_to_jal($res[$y]['EndDateContract']);
            $finalRes[$y]['phone'] = $res[$y]['phone'];
            $finalRes[$y]['mobile'] = $res[$y]['mobile'];
            $finalRes[$y]['Term_contract'] = $res[$y]['Term_contract'].' روز / ماه';
            $finalRes[$y]['Amount'] = number_format($res[$y]['Amount']).' ريال';
            $finalRes[$y]['description'] = $res[$y]['description'];
            switch ($res[$y]['type']){
                case 0:
                    $finalRes[$y]['bgColor'] = 'table-warning';
                    break;
                case 1:
                    $finalRes[$y]['bgColor'] = 'table-primary';
                    break;
                case 2:
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
            }
        }
        return $finalRes;
    }

    public function getLegalContractsManageListCountRows($Subject,$CID,$SDate,$EDate,$type,$status){
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(strlen(trim($Subject)) > 0){
            $w[] = '`subjectContract` LIKE "%'.$Subject.'%" ';
        }
        if(strlen(trim($CID)) > 0){
            $w[] = '`ContractID`="'.$CID.'" ';
        }
        if(strlen(trim($SDate)) > 0){
            $SDate = $ut->jal_to_greg($SDate);
            $w[] = '`BeginDateContract` >="'.$SDate.'" ';
        }
        if(strlen(trim($EDate)) > 0){
            $EDate = $ut->jal_to_greg($EDate);
            $w[] = '`EndDateContract` <="'.$EDate.'" ';
        }
        if(intval($type) >= 0){
            $w[] = '`type`='.$type.' ';
        }
        $w[] = '`isEnable`='.$status.' ';

        $query = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=31";
        $rsty = $db->ArrayQuery($query);
        $uids = str_replace('1','0',$rsty[0]['uids']);
        $uids = explode(',',$uids);
        if (in_array($_SESSION['userid'],$uids)){
            $w[] = '`unitID`=22 ';
        }

        $sql = "SELECT `RowID` FROM `legal_contracts`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function legalContractInfo($lcid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `legal_contracts` WHERE `RowID`=".$lcid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("lcid"=>$lcid,"ContractID"=>$res[0]['ContractID'],"subjectContract"=>$res[0]['subjectContract'],
                         "sideOne"=>$res[0]['sideOne'],"sideTwo"=>$res[0]['sideTwo'],"startDate"=>$ut->greg_to_jal($res[0]['BeginDateContract']),
                         "endDate"=>$ut->greg_to_jal($res[0]['EndDateContract']),"phone"=>$res[0]['phone'],"mobile"=>$res[0]['mobile'],
                         "Term_contract"=>$res[0]['Term_contract'],"Amount"=>number_format($res[0]['Amount']),"description"=>$res[0]['description'],
                         "type"=>$res[0]['type'],"unitID"=>$res[0]['unitID'],"codeTafzili"=>$res[0]['codeTafzili']);
            return $res;
        }else{
            return false;
        }
    }

    public function createLegalContract($cid,$subject,$sideOne,$sideTwo,$codeTafzili,$Sdate,$Edate,$Phone,$Mobile,$amount,$desc,$type,$unit){
        $acm = new acm();
        if(!$acm->hasAccess('legalContractsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $Sdate = $ut->jal_to_greg($Sdate);
        $Edate = $ut->jal_to_greg($Edate);
        $sql = "INSERT INTO `legal_contracts` (`ContractID`,`subjectContract`,`sideOne`,`sideTwo`,`BeginDateContract`,`EndDateContract`,`phone`,`mobile`,`Amount`,`description`,`type`,`unitID`,`codeTafzili`) VALUES ('{$cid}','{$subject}','{$sideOne}','{$sideTwo}','{$Sdate}','{$Edate}','{$Phone}','{$Mobile}',{$amount},'{$desc}',{$type},{$unit},'{$codeTafzili}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editLegalContract($lcid,$cid,$subject,$sideOne,$sideTwo,$codeTafzili,$Sdate,$Edate,$Phone,$Mobile,$amount,$desc,$type,$unit){
        $acm = new acm();
        if(!$acm->hasAccess('regulationsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $Sdate = $ut->jal_to_greg($Sdate);
        $Edate = $ut->jal_to_greg($Edate);
        $sql = "UPDATE `legal_contracts` SET `ContractID`='{$cid}',`subjectContract`='{$subject}',`sideOne`='{$sideOne}',`sideTwo`='{$sideTwo}',`BeginDateContract`='{$Sdate}',`EndDateContract`='{$Edate}',`phone`='{$Phone}',`mobile`='{$Mobile}',`Amount`={$amount},`description`='{$desc}',`type`={$type},`unitID`={$unit},`codeTafzili`='{$codeTafzili}' WHERE `RowID`={$lcid}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if (intval($res)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteLegalContract($lcid){
        $acm = new acm();
        if(!$acm->hasAccess('legalContractsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `legal_contracts` SET `isEnable`=0 WHERE `RowID`=".$lcid;
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public function attachedLegalContractFileHtm($lcid){
        $db = new DBi();
        $sql = "SELECT `RowID`,`fileName`,`fileInfo` FROM `legal_contracts_attachment` WHERE `lcid`={$lcid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileLegalContract-tableID">';
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
            $link = ADDR.'documents/'.$res[$i]['fileName'];

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachLegalContractFile('.$res[$i]['RowID'].')" ><i class="fas fa-trash"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function attachFileToLegalContract($lcid,$info,$files){
        $db = new DBi();
        $SFile = array();
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
                $SFile[] = "LegalCnt" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $cnt = count($SFile);
        for ($i=0;$i<$cnt;$i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i],'../documents/'.$SFile[$i]);
            $sql4 = "INSERT INTO `legal_contracts_attachment` (`lcid`,`fileName`,`fileInfo`) VALUES ({$lcid},'{$SFile[$i]}','{$info}')";
            $db->Query($sql4);
        }
        return true;
    }

    public function deleteAttachLegalContractFile($fid){
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `legal_contracts_attachment` WHERE `RowID`={$fid}";
        $res = $db->ArrayQuery($sql);
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/documents/' . $res[0]['fileName'];
        $result = unlink($file_to_delete);
        if ($result) {
            $query = "DELETE FROM `legal_contracts_attachment` WHERE `RowID`={$fid}";
            $db->Query($query);
            return true;
        }else{
            return false;
        }
    }

    public function attachmentFileLegalContractsHtm($lcid){
        $db = new DBi();
        $sql = "SELECT `fileName`,`fileInfo` FROM `legal_contracts_attachment` WHERE `lcid`={$lcid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileLegalContract1-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 70%;">نام فایل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $link = ADDR.'documents/'.$res[$i]['fileName'];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getSideTwoCodeTafzili($cfor){
        $db = new DBi();
        $sql = "SELECT `code` FROM `account` WHERE `Name`='{$cfor}'";
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            return $res;
        }else{
            return false;
        }
    }

}