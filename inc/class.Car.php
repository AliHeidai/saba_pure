<?php

class Car
{

    public function __construct()
    {
        // do nothing
    }

    public function getCarInformationManageHtm()
    {
        $acm = new acm();
        if (!$acm->hasAccess('carInformationManage')) {
            die("access denied");
            exit;
        }
        $ut = new Utility();

        $cars = $this->getAllCars();
        $cnt = count($cars);

        $driver = $this->getAllDriver();
        $cntd = count($driver);

        $hiddenContentId[] = "hiddenCarCommentBody";
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

        if ($acm->hasAccess('carInformationManage')) {
            $pagename[$x] = "شناسنامه خودرو ها";
            $pageIcon[$x] = "fa-car";
            $contentId[$x] = "carInformationManageBody";
            $menuItems[$x] = 'carInformationManageTabID';

            $bottons1 = array();
            $headerSearch1 = array();

            $b = 0;
            if ($acm->hasAccess('editCreateCar')) {
                $bottons1[$b]['title'] = "ثبت";
                $bottons1[$b]['jsf'] = "createCar";
                $bottons1[$b]['icon'] = "fa-plus-square";
                $b++;

                $bottons1[$b]['title'] = "ویرایش";
                $bottons1[$b]['jsf'] = "editCar";
                $bottons1[$b]['icon'] = "fa-edit";
                $b++;

                $bottons1[$b]['title'] = "تجهیزات مازاد";
                $bottons1[$b]['jsf'] = "createExtraEquipment";
                $bottons1[$b]['icon'] = "fa-plus-square";
                $b++;
            }

            if ($acm->hasAccess('recordEnterExitCar')) {
                $bottons1[$b]['title'] = "ثبت ورود و خروج";
                $bottons1[$b]['jsf'] = "enterExitCar";
                $bottons1[$b]['icon'] = "fa-plus-square";
                $b++;

                $bottons1[$b]['title'] = "ثبت مواد مصرفی خودرو";
                $bottons1[$b]['jsf'] = "createConsumingMaterials";
                $bottons1[$b]['icon'] = "fa-plus-square";
            }

            $bottons[$y] = $bottons1;
            $headerSearch[$z] = $headerSearch1;

            $manifold++;
            $access[] = 1;
            $x++;
            $y++;
            $z++;
        }
        if ($acm->hasAccess('consumingMaterialsManage')) {
            $pagename[$x] = "مدیریت مواد مصرفی خودرو ها";
            $pageIcon[$x] = "fa-oil-can";
            $contentId[$x] = "consumingMaterialsManageBody";
            $menuItems[$x] = 'consumingMaterialsManageTabID';

            $bottons2 = array();
            $headerSearch2 = array();

            $b = 0;
            $bottons2[$b]['title'] = "ثبت";
            $bottons2[$b]['jsf'] = "consumingMaterialsCreate";
            $bottons2[$b]['icon'] = "fa-plus-square";
            $b++;

            $bottons2[$b]['title'] = "ویرایش";
            $bottons2[$b]['jsf'] = "consumingMaterialsEdit";
            $bottons2[$b]['icon'] = "fa-edit";

            $bottons[$y] = $bottons2;
            $headerSearch[$z] = $headerSearch2;

            $manifold++;
            $access[] = 2;
        }

        $htm = $ut->getHtmlOfDefaultManagementMultiPage($pagename, $pageIcon, $contentId, $menuItems, $bottons, $manifold, $headerSearch, $hiddenContentId);
        //++++++++++++++++++++++++++++++++++ EDIT CREATE MODAL ++++++++++++++++++++++++++++++++
        $modalID = "carInformationManageModal";
        $modalTitle = "فرم ایجاد/ویرایش خودرو";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carInformationManageName";
        $items[$c]['title'] = "نام ماشین";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carInformationManagePlaque";
        $items[$c]['title'] = "شماره پلاک";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carInformationManageChassis";
        $items[$c]['title'] = "شماره شاسی";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carInformationManageSerial";
        $items[$c]['title'] = "شماره سریال";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "carInformationManageFuelType";
        $items[$c]['title'] = "نوع سوخت";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = "بنزین";
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = "گازوئیل";
        $items[$c]['options'][2]["value"] = 2;
        $items[$c]['options'][3]["title"] = "بنزین و گاز";
        $items[$c]['options'][3]["value"] = 3;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "carInformationManageCarType";
        $items[$c]['title'] = "نوع کاربری";
        $items[$c]['onchange'] = "onchange=getCarType()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "اداری";
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = "شخصی";
        $items[$c]['options'][2]["value"] = 1;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carInformationManageLastKilometer";
        $items[$c]['title'] = "آخرین کیلومتر";
        $items[$c]['placeholder'] = "کیلومتر";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carInformationManageTDDate";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ اتمام معاینه فنی";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carInformationManageTIDate";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ اتمام بیمه شخص ثالث";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carInformationManageBIDate";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ اتمام بیمه بدنه";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;
        //----------------------------------------------------------------
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carHuggingStartDate";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ شروع  مجوز بغل نویسی";
        $items[$c]['placeholder'] = "تاریخ";

        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carHuggingEndDate";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ اتمام مجوز بغل نویسی ";
        $items[$c]['placeholder'] = "تاریخ";

        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "carHuggindFile";
        $items[$c]['title'] = "بارگذاری  مجوز بغل نویسی";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF , JPG , PNG باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;
        //----------------------------------------------------------------
        $items[$c]['type'] = "file";
        $items[$c]['id'] = "carInformationManageTDFile";
        $items[$c]['title'] = "بارگذاری معاینه فنی";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF , JPG , PNG باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "carInformationManageTIFile";
        $items[$c]['title'] = "بارگذاری بیمه شخص ثالث";
        $items[$c]['name'] = 'name="files1[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF , JPG , PNG باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "carInformationManageBIFile";
        $items[$c]['title'] = "بارگذاری بیمه بدنه";
        $items[$c]['name'] = 'name="files2[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF , JPG , PNG باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "carInformationManageDocument";
        $items[$c]['title'] = "بارگذاری سند";
        $items[$c]['name'] = 'name="files3[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF , JPG , PNG باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "carInformationManageGreenPage";
        $items[$c]['title'] = "بارگذاری برگ سبز";
        $items[$c]['name'] = 'name="files4[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF , JPG , PNG باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "carInformationManageLastStatus";
        $items[$c]['title'] = "بارگذاری آخرین وضعیت خودرو";
        $items[$c]['name'] = 'name="files5[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF , JPG , PNG باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageCarHiddenCid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateCar";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateModal = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style);
        //++++++++++++++++++ END OF EDIT CREATE MODAL ++++++++++++++++++
        //++++++++++++++++++ show Attached File To Car Modal ++++++++++++++++++++++
        $modalID = "showAttachedFileToCarModal";
        $modalTitle = "فایل های ضمیمه";
        $ShowDescription = 'showAttachedFileToCar-body';
        $style = 'style="max-width: 500px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showAttachedFileToCar = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style, $ShowDescription);
        //+++++++++++++++++ End show Attached File To Car Modal ++++++++++++++++++++++++
        //++++++++++++++++++ download Technical Diag File Modal ++++++++++++++++++++++
        $modalID = "downloadTechnicalDiagFileModal";
        $modalTitle = "دانلود فایل های معاینه فنی";
        $ShowDescription = 'downloadTechnicalDiagFile-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $downloadTechnicalDiagFile = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', '', $ShowDescription);
        //+++++++++++++++++ End download Technical Diag File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ download Third Insurance File Modal ++++++++++++++++++++++
        $modalID = "downloadThirdInsuranceFileModal";
        $modalTitle = "دانلود فایل های بیمه شخص ثالث";
        $ShowDescription = 'downloadThirdInsuranceFile-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $downloadThirdInsuranceFile = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', '', $ShowDescription);
        //+++++++++++++++++ End download Third Insurance File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ download Body Insurance File Modal ++++++++++++++++++++++
        $modalID = "downloadBodyInsuranceFileModal";
        $modalTitle = "دانلود فایل های بیمه بدنه";
        $ShowDescription = 'downloadBodyInsuranceFile-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $downloadBodyInsuranceFile = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', '', $ShowDescription);
        //+++++++++++++++++ End download Body Insurance File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ download Car Document File Modal ++++++++++++++++++++++
        $modalID = "downloadCarDocumentFileModal";
        $modalTitle = "دانلود فایل های سند ماشین";
        $ShowDescription = 'downloadCarDocumentFile-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $downloadCarDocumentFile = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', '', $ShowDescription);
        //+++++++++++++++++ End download Car Document File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ download Green Page File Modal ++++++++++++++++++++++
        $modalID = "downloadGreenPageFileModal";
        $modalTitle = "دانلود فایل های برگ سبز ماشین";
        $ShowDescription = 'downloadGreenPageFileFile-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $downloadGreenPageFile = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', '', $ShowDescription);
        //+++++++++++++++++ End download Green Page File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ download Last Status File Modal ++++++++++++++++++++++
        $modalID = "downloadLastStatusFileModal";
        $modalTitle = "دانلود فایل های آخرین وضعیت خودرو";
        $ShowDescription = 'downloadLastStatusFile-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $downloadLastStatusFile = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', '', $ShowDescription);
        //+++++++++++++++++ End download Last Status File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show PayComment For THis Car Modal ++++++++++++++++++++++
        $modalID = "showPayCommentForTHisCarModal";
        $modalTitle = "اظهارنظر های خودرو";
        $ShowDescription = 'showPayCommentForTHisCar-body';
        $style = 'style="max-width: 1200px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showPayCommentForTHisCar = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style, $ShowDescription);
        //+++++++++++++++++ End show PayComment For THis Car Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Deposits List Info Modal ++++++++++++++++++++++
        $modalID = "depositsInCarListModal";
        $modalTitle = "واریزی های انجام شده";
        $style = 'style="max-width: 900px;"';
        $ShowDescription = 'deposits-InCar-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showDepositsInfo = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style, $ShowDescription);
        //+++++++++++++++++ End Deposits List Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Comment Checks Car Modal ++++++++++++++++++++++
        $modalID = "commentCheckInCarModal";
        $modalTitle = "چک/چک ها";
        $ShowDescription = 'comment-InCar-Checks-body';
        $style = 'style="max-width: 700px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showCommentChecks = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style, $ShowDescription);
        //+++++++++++++++++ End Comment Checks Car Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Comment Info Modal ++++++++++++++++++++++
        $modalID = "carCommentManageInfoModal";
        $modalTitle = "سایر اطلاعات";
        $ShowDescription = 'comment-car-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showCommentInfo = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', '', $ShowDescription);
        //+++++++++++++++++ End Comment Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ download CheckCarcass File Modal ++++++++++++++++++++++
        $modalID = "downloadCheckCarcassCarFileModal";
        $modalTitle = "دانلود رسید لاشه چک";
        $ShowDescription = 'downloadCheckCarcassCarFile-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $downloadCheckCarcassFile = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', '', $ShowDescription);
        //+++++++++++++++++ End download CheckCarcass File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ comment Attachment File Modal ++++++++++++++++++++++
        $modalID = "commentAttachmentCarFileModal";
        $modalTitle = "دانلود فایل های پیوست و رسیدهای پرداخت";
        $ShowDescription = 'commentAttachmentCarFile-body';
        $style = 'style="max-width: 655px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $commentAttachmentFile = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style, $ShowDescription);
        //+++++++++++++++++ End comment Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ comment Workflow Modal ++++++++++++++++++++++
        $modalID = "carCommentWorkflowModal";
        $modalTitle = "گردش کار اظهارنظر";
        $style = 'style="max-width: 1000px;"';
        $ShowDescription = 'carCommentWorkflow-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $commentWorkflow = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style, $ShowDescription);
        //+++++++++++++++++ End comment Workflow Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show Attached Fund To Car Comment Modal ++++++++++++++++++++++
        $modalID = "showAttachedFundToCarCommentModal";
        $modalTitle = "لیست تنخواه ها";
        $ShowDescription = 'showAttachedFundToCarComment-body';
        $style = 'style="max-width: 1200px;"';

        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showAttachedFundToCarComment = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style, $ShowDescription);
        //+++++++++++++++++ End show Attached Fund To Car Comment Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ show Fund List Details MODAL ++++++++++++++++++++++++++++++++
        $modalID = "showCarFundListDetailsModal";
        $modalTitle = "جزئیات تنخواه";
        $style = 'style="max-width: 700px;"';
        $ShowDescription = 'fundListDetails-Car-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showFundListDetails = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style, $ShowDescription);
        //++++++++++++++++++++++++++++++++++ End show Fund List Details MODAL ++++++++++++++++++++++++++++++++
        //++++++++++++++++++ fundList Show Attachment File Modal ++++++++++++++++++++++
        $modalID = "showCarFundListAttachmentFileModal";
        $modalTitle = "فایل های پیوست";
        $ShowDescription = 'showCarFundListAttachmentFile-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showFundListAddAttachmentFile = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', '', $ShowDescription);
        //+++++++++++++++++ End fundList Show Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Enter Exit Car MODAL ++++++++++++++++++++++++++++++++
        $modalID = "carEnterExitManageModal";
        $modalTitle = "فرم ثبت ورود و خروج";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "carEnterExitManageCarType";
        $items[$c]['title'] = "انتخاب خودرو";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        for ($i = 0; $i < $cnt; $i++) {
            $items[$c]['options'][$i + 1]["title"] = $cars[$i]['carName'];
            $items[$c]['options'][$i + 1]["value"] = $cars[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "carEnterExitManageEorE";
        $items[$c]['title'] = "نوع رفت و آمد";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "ورود";
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = "خروج";
        $items[$c]['options'][2]["value"] = 1;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carEnterExitManageTime";
        $items[$c]['style'] = "style='width:50%;float:right;'";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['data-format'] = "data-format='hh:mm'";
        $items[$c]['add-on'] = "yes";
        $items[$c]['title'] = "ساعت";
        $items[$c]['placeholder'] = "ساعت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carEnterExitManageKM";
        $items[$c]['title'] = "کیلومتر ماشین";
        $items[$c]['placeholder'] = "کیلومتر";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "carEnterExitManageDName";
        $items[$c]['title'] = "نام راننده";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        for ($i = 0; $i < $cntd; $i++) {
            $items[$c]['options'][$i + 1]["title"] = $driver[$i]['driverName'];
            $items[$c]['options'][$i + 1]["value"] = $driver[$i]['RowID'];
        }

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEnterExitCar";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $enterExitCarModal = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style);
        //++++++++++++++++++++++++++++++++++ Enter Exit Car MODAL ++++++++++++++++++++++++++++++++
        //++++++++++++++++++ Enter Exit Car Modal ++++++++++++++++++++++
        $modalID = "enterExitCarListModal";
        $modalTitle = "ورود و خروج ماشین ها";
        $style = 'style="max-width: 900px;"';
        $ShowDescription = 'enterExitCarListBody';
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "enterExitCarListHiddenCaid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showEnterExitCarList = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style, $ShowDescription);
        //+++++++++++++++++ End Enter Exit Car Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Edit Enter Exit Car MODAL ++++++++++++++++++++++++++++++++
        $modalID = "carEditEnterExitManageModal";
        $modalTitle = "فرم ویرایش ورود و خروج";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "carEditEnterExitManageEorE";
        $items[$c]['title'] = "نوع رفت و آمد";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "ورود";
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = "خروج";
        $items[$c]['options'][2]["value"] = 1;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carEditEnterExitManageTime";
        $items[$c]['style'] = "style='width:50%;float:right;'";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['data-format'] = "data-format='hh:mm'";
        $items[$c]['add-on'] = "yes";
        $items[$c]['title'] = "ساعت";
        $items[$c]['placeholder'] = "ساعت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "carEditEnterExitManageKM";
        $items[$c]['title'] = "کیلومتر ماشین";
        $items[$c]['placeholder'] = "کیلومتر";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "carEditEnterExitManageDName";
        $items[$c]['title'] = "نام راننده";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        for ($i = 0; $i < $cntd; $i++) {
            $items[$c]['options'][$i + 1]["title"] = $driver[$i]['driverName'];
            $items[$c]['options'][$i + 1]["value"] = $driver[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "carEditEnterExitHiddenEEid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditEnterExitCar";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editEnterExitCarModal = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style);
        //++++++++++++++++++++++++++++++++++ Edit Enter Exit Car MODAL ++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ create Consuming Materials MODAL ++++++++++++++++++++++++++++++++
        $modalID = "createConsumingMaterialsModal";
        $modalTitle = "فرم ثبت مواد مصرفی خودرو";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createConsumingMaterialsType";
        $items[$c]['title'] = "ماده مصرفی";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "روغن";
        $items[$c]['options'][0]["value"] = 1;
        $items[$c]['options'][1]["title"] = "فیلتر روغن";
        $items[$c]['options'][1]["value"] = 2;
        $items[$c]['options'][2]["title"] = "فیلتر سوخت";
        $items[$c]['options'][2]["value"] = 3;
        $items[$c]['options'][3]["title"] = "فیلتر هوا";
        $items[$c]['options'][3]["value"] = 4;
        $items[$c]['options'][4]["title"] = "روغن گیربکس";
        $items[$c]['options'][4]["value"] = 5;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createConsumingMaterialsCurKM";
        $items[$c]['title'] = "کیلومتر فعلی";
        $items[$c]['placeholder'] = "کیلومتر";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createConsumingMaterialsChangeDate";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ تعویض";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "consumingMaterialsHiddenCaid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCreateConsumingMaterials";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createConsumingMaterialsModal = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style);
        //++++++++++++++++++ END OF create Consuming Materials MODAL ++++++++++++++++++
        //++++++++++++++++++ Consuming Materials Modal ++++++++++++++++++++++
        $modalID = "showConsumingMaterialsModal";
        $modalTitle = "موارد تعویض شده";
        $style = 'style="max-width: 900px;"';
        $ShowDescription = 'consumingMaterialsBody';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showConsumingMaterialsList = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style, $ShowDescription);
        //+++++++++++++++++ End Consuming Materials Modal ++++++++++++++++++++++++
        //++++++++++++++++++ create Extra Equipment Modal ++++++++++++++++++++++
        $modalID = "extraEquipmentModal";
        $modalTitle = "تجهیزات مازاد";
        $style = 'style="max-width: 700px;"';
        $ShowDescription = 'extraEquipment-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "extraEquipmentName";
        $items[$c]['title'] = "نام تجهیزات";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "extraEquipmentDescription";
        $items[$c]['title'] = "مشخصات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "extraEquipmentAttachment";
        $items[$c]['title'] = "بارگذاری عکس";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PNG , JPG , JPEG , JFIF , PDF باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "extraEquipmentAttachmentID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doCreateExtraEquipment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $createExtraEquipment = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style, $ShowDescription);
        //+++++++++++++++++ End create Extra Equipment Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show Extra Equipment Modal ++++++++++++++++++++++
        $modalID = "showExtraEquipmentModal";
        $modalTitle = "تجهیزات مازاد";
        $ShowDescription = 'showExtraEquipment-body';
        $style = 'style="max-width: 500px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showExtraEquipment = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style, $ShowDescription);
        //+++++++++++++++++ End show Extra Equipment Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ create Consuming Materials MODAL ++++++++++++++++++++++++++++++++
        $modalID = "consumingMaterialsCreateModal";
        $modalTitle = "فرم ثبت/ویرایش مواد مصرفی خودرو";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "consumingMaterialsCreateCarName";
        $items[$c]['title'] = "انتخاب خودرو";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        for ($i = 0; $i < $cnt; $i++) {
            $items[$c]['options'][$i + 1]["title"] = $cars[$i]['carName'];
            $items[$c]['options'][$i + 1]["value"] = $cars[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "consumingMaterialsCreateType";
        $items[$c]['title'] = "ماده مصرفی";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "روغن";
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = "فیلتر روغن";
        $items[$c]['options'][2]["value"] = 2;
        $items[$c]['options'][3]["title"] = "فیلتر سوخت";
        $items[$c]['options'][3]["value"] = 3;
        $items[$c]['options'][4]["title"] = "فیلتر هوا";
        $items[$c]['options'][4]["value"] = 4;
        $items[$c]['options'][5]["title"] = "روغن گیربکس";
        $items[$c]['options'][5]["value"] = 5;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "consumingMaterialsCreateBrand";
        $items[$c]['title'] = "برند محصول";
        $items[$c]['placeholder'] = "برند محصول";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "consumingMaterialsCreateChangeKM";
        $items[$c]['title'] = "کیلومتر تا تعویض بعدی";
        $items[$c]['placeholder'] = "کیلومتر";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "consumingMaterialsCreateHiddenCMid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doConsumingMaterialsCreate";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $consumingMaterialsCreate = $ut->getHtmlModal($modalID, $modalTitle, $items, $footerBottons, '', '', $style);
        //++++++++++++++++++ END OF create Consuming Materials MODAL ++++++++++++++++++
        $htm .= $editCreateModal;
        $htm .= $showAttachedFileToCar;
        $htm .= $downloadTechnicalDiagFile;
        $htm .= $downloadThirdInsuranceFile;
        $htm .= $downloadBodyInsuranceFile;
        $htm .= $downloadCarDocumentFile;
        $htm .= $downloadGreenPageFile;
        $htm .= $downloadLastStatusFile;
        $htm .= $showPayCommentForTHisCar;
        $htm .= $showDepositsInfo;
        $htm .= $showCommentChecks;
        $htm .= $showCommentInfo;
        $htm .= $downloadCheckCarcassFile;
        $htm .= $commentAttachmentFile;
        $htm .= $commentWorkflow;
        $htm .= $showAttachedFundToCarComment;
        $htm .= $showFundListDetails;
        $htm .= $showFundListAddAttachmentFile;
        $htm .= $enterExitCarModal;
        $htm .= $showEnterExitCarList;
        $htm .= $editEnterExitCarModal;
        $htm .= $createConsumingMaterialsModal;
        $htm .= $showConsumingMaterialsList;
        $htm .= $createExtraEquipment;
        $htm .= $showExtraEquipment;
        $htm .= $consumingMaterialsCreate;
        $send = array($htm, $access);
        return $send;
    }

    public function getCarInformationManageList($page = 1)
    {
        $acm = new acm();
        if (!$acm->hasAccess('carInformationManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $numRows = LISTCNT;
        $start = ($page - 1) * $numRows;

        $sql = "SELECT * FROM `car_info`";
        $sql .= " ORDER BY `RowID` DESC LIMIT $start," . $numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for ($y = 0; $y < $listCount; $y++) {
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['carName'] = $res[$y]['carName'];
            $finalRes[$y]['plaque'] = $res[$y]['plaque'];
            $finalRes[$y]['chassis'] = $res[$y]['chassis'];
            $finalRes[$y]['serial'] = $res[$y]['serial'];
            $finalRes[$y]['carLayer'] = $res[$y]['carLayer'];
            $finalRes[$y]['carType'] = ($res[$y]['carType'] == 0 ? 'اداری' : 'شخصی');
            switch ($res[$y]['fuelType']) {
                case 1:
                    $finalRes[$y]['fuelType'] = 'بنزین';
                    break;
                case 2:
                    $finalRes[$y]['fuelType'] = 'گازوئیل';
                    break;
                case 3:
                    $finalRes[$y]['fuelType'] = 'بنزین و گاز';
                    break;
            }
            $finalRes[$y]['technicalDiagDate'] = (strtotime($res[$y]['technicalDiagDate']) > 0 ? $ut->greg_to_jal($res[$y]['technicalDiagDate']) : '');
            $finalRes[$y]['insuranceDate'] = (strtotime($res[$y]['insuranceDate']) > 0 ? $ut->greg_to_jal($res[$y]['insuranceDate']) : '');
            $finalRes[$y]['insuranceBodyDate'] = (strtotime($res[$y]['insuranceBodyDate']) > 0 ? $ut->greg_to_jal($res[$y]['insuranceBodyDate']) : '');
        }
        return $finalRes;
    }

    public function getCarInformationManageListCountRows()
    {
        $acm = new acm();
        if (!$acm->hasAccess('carInformationManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `car_info`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function carInfo($caid)
    {
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `car_info` WHERE `RowID`=" . $caid;
        $res = $db->ArrayQuery($sql);

        if (count($res) == 1) {
            $sql1 = "SELECT `value` from `car_info_meta` where `key`='hugging_start_date' AND `status`=1 AND `car_id`={$caid}";
            $res1 = $db->ArrayQuery($sql1);
            $res1 = $db->ArrayQuery($sql1);
            $carHuggingStartDate = $ut->greg_to_jal($res1[0]['value']);
            $sql2 = "SELECT `value` from `car_info_meta` where `key`='hugging_end_date' AND `status`=1 AND `car_id`={$caid}";
            $res1 = $db->ArrayQuery($sql2);
            $res2 = $db->ArrayQuery($sql2);
            $carHuggingEndDate = $ut->greg_to_jal($res2[0]['value']);
            $technicalDiagDate = (strtotime($res[0]['technicalDiagDate']) > 0 ? $ut->greg_to_jal($res[0]['technicalDiagDate']) : '');
            $insuranceDate = (strtotime($res[0]['insuranceDate']) > 0 ? $ut->greg_to_jal($res[0]['insuranceDate']) : '');
            $insuranceBodyDate = (strtotime($res[0]['insuranceBodyDate']) > 0 ? $ut->greg_to_jal($res[0]['insuranceBodyDate']) : '');
            $res = array(
                "caid" => $caid,
                "carName" => $res[0]['carName'],
                "plaque" => $res[0]['plaque'],
                "chassis" => $res[0]['chassis'],
                "serial" => $res[0]['serial'],
                "fuelType" => $res[0]['fuelType'],
                "technicalDiagDate" => $technicalDiagDate,
                "insuranceDate" => $insuranceDate,
                "insuranceBodyDate" => $insuranceBodyDate,
                "carType" => $res[0]['carType'],
                "lastKilometer" => $res[0]['lastKilometer'],
                'carHuggingStartDate' => $carHuggingStartDate,
                'carHuggingEndDate' => $carHuggingEndDate
            );
            return $res;
        } else {
            return false;
        }
    }

    public function createCar($name, $plaque, $chassis, $serial, $fuelType, $TDDate, $TIDate, $BIDate, $carType, $lastKilometer, $files, $files1, $files2, $files3, $files4, $files5, $files6, $hugging_start, $hugging_end, $hugging_flag)
    {
        $acm = new acm();
        if (!$acm->hasAccess('carInformationManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $nowDate = date('Y-m-d');
        $TDDate = $ut->jal_to_greg($TDDate);
        $TIDate = $ut->jal_to_greg($TIDate);
        $BIDate = $ut->jal_to_greg($BIDate);
        $hugging_start = $ut->jal_to_greg($hugging_start);
        $hugging_end = $ut->jal_to_greg($hugging_end);

        $SFile = array();
        $SFile1 = array();
        $SFile2 = array();
        $SFile3 = array();
        $SFile4 = array();
        $SFile5 = array();
        $SFile6 = array();
        $allowedTypes = ['png', 'jpg', 'jpeg', 'pdf', 'PNG', 'JPG', 'JPEG', 'PDF'];

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
                if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile[] = "td" . rand(0, time()) . '.' . $format;
            } // for()
        }   //  if (isset($files) && !empty($files))
        if (isset($files1) && !empty($files1)) {
            $no_files = count($files1['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files1["tmp_name"][$i];
                if ($files["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files1['name'][$i], strpos($files1['name'][$i], ".") + 1);
                if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile1[] = "ti" . rand(0, time()) . '.' . $format;
            } // for()
        } //  if (isset($files) && !empty($files))
        if (isset($files2) && !empty($files2)) {
            $no_files = count($files2['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files2["tmp_name"][$i];
                if ($files2["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files2['name'][$i], strpos($files2['name'][$i], ".") + 1);
                if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile2[] = "bi" . rand(0, time()) . '.' . $format;
            } // for()
        } //  if (isset($files) && !empty($files))
        if (isset($files3) && !empty($files3)) {
            $no_files = count($files3['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files3["tmp_name"][$i];
                if ($files3["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files3['name'][$i], strpos($files3['name'][$i], ".") + 1);
                if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile3[] = "cd" . rand(0, time()) . '.' . $format;
            } // for()
        } //  if (isset($files) && !empty($files))
        if (isset($files4) && !empty($files4)) {
            $no_files = count($files4['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files4["tmp_name"][$i];
                if ($files4["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files4['name'][$i], strpos($files4['name'][$i], ".") + 1);
                if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile4[] = "gp" . rand(0, time()) . '.' . $format;
            } // for()
        } //  if (isset($files) && !empty($files))
        if (isset($files5) && !empty($files5)) {
            $no_files = count($files5['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files5["tmp_name"][$i];
                if ($files5["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files5['name'][$i], strpos($files5['name'][$i], ".") + 1);
                if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile5[] = "ls" . rand(0, time()) . '.' . $format;
            } // for()
        } //  if (isset($files) && !empty($files))

        //----------------------------------------------------
        if ($hugging_flag == 1) { //مجوز بغل نویسی 
            if (isset($files6) && !empty($files6)) {
                $no_files = count($files6['name']);
                for ($i = 0; $i < $no_files; $i++) {
                    $filepath = $files6["tmp_name"][$i];
                    if ($files6["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                        return -1;
                    }
                    if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                        return -2;
                    }
                    $format = substr($files6['name'][$i], strpos($files6['name'][$i], ".") + 1);
                    if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                        return -3;
                    }
                    $SFile6[] = "hp" . rand(0, time()) . '.' . $format;
                } // for()
            } //  if (isset($files) && !empty($files))
        }
        //----------------------------------------------------

        $sql = "INSERT INTO `car_info` (`carName`,`plaque`,`chassis`,`serial`,`fuelType`,`technicalDiagDate`,`insuranceDate`,`insuranceBodyDate`,`carType`,`lastKilometer`,`lastKilometerDate`,`has_meta`) VALUES ('{$name}','{$plaque}','{$chassis}','{$serial}',{$fuelType},'{$TDDate}','{$TIDate}','{$BIDate}',{$carType},'{$lastKilometer}','{$nowDate}','{$hugging_flag}')";
        $result = $db->Query($sql);
        $car_id = $db->InsertrdID();

        if (intval($result) > 0) {
            $id = $db->InsertrdID();
            $cnt = count($SFile);
            $cnt1 = count($SFile1);
            $cnt2 = count($SFile2);
            $cnt3 = count($SFile3);
            $cnt4 = count($SFile4);
            $cnt5 = count($SFile5);
            $cnt6 = 0;
            if ($hugging_flag == 1) {
                $cnt6 = count($SFile6);
            }
            for ($i = 0; $i < $cnt; $i++) {
                $upload = move_uploaded_file($files["tmp_name"][$i], '../carInfo/' . $SFile[$i]);
            }
            for ($i = 0; $i < $cnt1; $i++) {
                $upload = move_uploaded_file($files1["tmp_name"][$i], '../carInfo/' . $SFile1[$i]);
            }
            for ($i = 0; $i < $cnt2; $i++) {
                $upload = move_uploaded_file($files2["tmp_name"][$i], '../carInfo/' . $SFile2[$i]);
            }
            for ($i = 0; $i < $cnt3; $i++) {
                $upload = move_uploaded_file($files3["tmp_name"][$i], '../carInfo/' . $SFile3[$i]);
            }
            for ($i = 0; $i < $cnt4; $i++) {
                $upload = move_uploaded_file($files4["tmp_name"][$i], '../carInfo/' . $SFile4[$i]);
            }
            for ($i = 0; $i < $cnt5; $i++) {
                $upload = move_uploaded_file($files5["tmp_name"][$i], '../carInfo/' . $SFile5[$i]);
            }

            if ($cnt6 > 0) {
                for ($i = 0; $i < $cnt5; $i++) {
                    $upload = move_uploaded_file($files5["tmp_name"][$i], '../carInfo/' . $SFile5[$i]);
                }
            }

            $SFile = implode(',', $SFile);
            $SFile1 = implode(',', $SFile1);
            $SFile2 = implode(',', $SFile2);
            $SFile3 = implode(',', $SFile3);
            $SFile4 = implode(',', $SFile4);
            $SFile5 = implode(',', $SFile5);
            if ($cnt6 > 0) {
                $SFile6 = implode(',', $SFile6);
                $sql_meta = "INSERT INTO `car_info_meta` (`car_id`,`key`,`value`,`group`,`type`) VALUES({$car_id},'hugging_start_date','{$hugging_start}','hugging','str'),({$car_id},'hugging_end_date','{$hugging_end}','hugging','str'),({$car_id},'hugging_files','{$SFile6}','hugging','file')";
                $res_meta = $db->Query($sql_meta);
            }
            $sql4 = "UPDATE `car_info` SET `technicalDiagFile`='{$SFile}',`insuranceFile`='{$SFile1}',`insuranceBodyFile`='{$SFile2}',`document`='{$SFile3}',`greenPage`='{$SFile4}',`lastStatus`='{$SFile5}' WHERE `RowID`={$id}";
            $db->Query($sql4);
            return true;
        } else {
            return false;
        }
    }

    public function editCar($caid, $name, $plaque, $chassis, $serial, $fuelType, $TDDate, $TIDate, $BIDate, $carType, $files, $files1, $files2, $files3, $files4, $files5, $files6, $hugging_start, $hugging_end, $hugging_flag)
    {
        $acm = new acm();
        if (!$acm->hasAccess('carInformationManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $TDDate = $ut->jal_to_greg($TDDate);
        $TIDate = $ut->jal_to_greg($TIDate);
        $BIDate = $ut->jal_to_greg($BIDate);
        $hugging_start = $ut->jal_to_greg($hugging_start);
        $hugging_end = $ut->jal_to_greg($hugging_end);

        $sql = "SELECT `technicalDiagFile`,`insuranceFile`,`insuranceBodyFile`,`document`,`greenPage`,`lastStatus` FROM `car_info` WHERE `RowID`={$caid}";
        $rst = $db->ArrayQuery($sql);

        $technicalDiagFile = explode(',', $rst[0]['technicalDiagFile']);
        $insuranceFile = explode(',', $rst[0]['insuranceFile']);
        $insuranceBodyFile = explode(',', $rst[0]['insuranceBodyFile']);
        $document = explode(',', $rst[0]['document']);
        $greenPage = explode(',', $rst[0]['greenPage']);
        $lastStatus = explode(',', $rst[0]['lastStatus']);
        $ccnt = count($technicalDiagFile);
        $ccnt1 = count($insuranceFile);
        $ccnt2 = count($insuranceBodyFile);
        $ccnt3 = count($document);
        $ccnt4 = count($greenPage);
        $ccnt5 = count($lastStatus);

        for ($i = 0; $i < $ccnt; $i++) {
            $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/carInfo/' . $technicalDiagFile[$i];
            unlink($file_to_delete);
        }
        for ($i = 0; $i < $ccnt1; $i++) {
            $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/carInfo/' . $insuranceFile[$i];
            unlink($file_to_delete);
        }
        for ($i = 0; $i < $ccnt2; $i++) {
            $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/carInfo/' . $insuranceBodyFile[$i];
            unlink($file_to_delete);
        }
        for ($i = 0; $i < $ccnt3; $i++) {
            $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/carInfo/' . $document[$i];
            unlink($file_to_delete);
        }
        for ($i = 0; $i < $ccnt4; $i++) {
            $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/carInfo/' . $greenPage[$i];
            unlink($file_to_delete);
        }
        for ($i = 0; $i < $ccnt5; $i++) {
            $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/carInfo/' . $lastStatus[$i];
            unlink($file_to_delete);
        }

        $SFile = array();
        $SFile1 = array();
        $SFile2 = array();
        $SFile3 = array();
        $SFile4 = array();
        $SFile5 = array();
        $SFile6 = array();
        $allowedTypes = ['png', 'jpg', 'jpeg', 'pdf', 'PNG', 'JPG', 'JPEG', 'PDF'];

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
                if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile[] = "td" . rand(0, time()) . '.' . $format;
            } // for()
        } //  if (isset($files) && !empty($files))
        if (isset($files1) && !empty($files1)) {
            $no_files = count($files1['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files1["tmp_name"][$i];
                if ($files["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files1['name'][$i], strpos($files1['name'][$i], ".") + 1);
                if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile1[] = "ti" . rand(0, time()) . '.' . $format;
            } // for()
        } //  if (isset($files) && !empty($files))
        if (isset($files2) && !empty($files2)) {
            $no_files = count($files2['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files2["tmp_name"][$i];
                if ($files2["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files2['name'][$i], strpos($files2['name'][$i], ".") + 1);
                if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile2[] = "bi" . rand(0, time()) . '.' . $format;
            } // for()
        } //  if (isset($files) && !empty($files))
        if (isset($files3) && !empty($files3)) {
            $no_files = count($files3['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files3["tmp_name"][$i];
                if ($files3["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files3['name'][$i], strpos($files3['name'][$i], ".") + 1);
                if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile3[] = "cd" . rand(0, time()) . '.' . $format;
            } // for()
        } //  if (isset($files) && !empty($files))
        if (isset($files4) && !empty($files4)) {
            $no_files = count($files4['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files4["tmp_name"][$i];
                if ($files4["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files4['name'][$i], strpos($files4['name'][$i], ".") + 1);
                if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile4[] = "gp" . rand(0, time()) . '.' . $format;
            } // for()
        } //  if (isset($files) && !empty($files))
        if (isset($files5) && !empty($files5)) {
            $no_files = count($files5['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files5["tmp_name"][$i];
                if ($files5["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files5['name'][$i], strpos($files5['name'][$i], ".") + 1);
                if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile5[] = "ls" . rand(0, time()) . '.' . $format;
            } // for()
        } //  if (isset($files) && !empty($files))

        //-----------------------------------------------------------------
        if ($hugging_flag == 1) {
            if (isset($files6) && !empty($files6)) {
                $no_files = count($files6['name']);
                for ($i = 0; $i < $no_files; $i++) {
                    $filepath = $files6["tmp_name"][$i];
                    if ($files6["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                        return -1;
                    }
                    if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                        return -2;
                    }
                    $format = substr($files6['name'][$i], strpos($files6['name'][$i], ".") + 1);
                    if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                        return -3;
                    }
                    $SFile6[] = "hp" . rand(0, time()) . '.' . $format;
                } // for()
            } //  

        }
        //-----------------------------------------------------------------
        $cnt = count($SFile);
        $cnt1 = count($SFile1);
        $cnt2 = count($SFile2);
        $cnt3 = count($SFile3);
        $cnt4 = count($SFile4);
        $cnt5 = count($SFile5);
        $cnt6 = count($SFile6);
        if ($hugging_flag == 1) {
            $cnt6 = count($SFile6);
        }
        for ($i = 0; $i < $cnt; $i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i], '../carInfo/' . $SFile[$i]);
        }
        for ($i = 0; $i < $cnt1; $i++) {
            $upload = move_uploaded_file($files1["tmp_name"][$i], '../carInfo/' . $SFile1[$i]);
        }
        for ($i = 0; $i < $cnt2; $i++) {
            $upload = move_uploaded_file($files2["tmp_name"][$i], '../carInfo/' . $SFile2[$i]);
        }
        for ($i = 0; $i < $cnt3; $i++) {
            $upload = move_uploaded_file($files3["tmp_name"][$i], '../carInfo/' . $SFile3[$i]);
        }
        for ($i = 0; $i < $cnt4; $i++) {
            $upload = move_uploaded_file($files4["tmp_name"][$i], '../carInfo/' . $SFile4[$i]);
        }
        for ($i = 0; $i < $cnt5; $i++) {
            $upload = move_uploaded_file($files5["tmp_name"][$i], '../carInfo/' . $SFile5[$i]);
        }

        if ($cnt6 > 0) {
            for ($i = 0; $i < $cnt6; $i++) {
                $upload = move_uploaded_file($files6["tmp_name"][$i], '../carInfo/' . $SFile6[$i]);
            }
        }

        $SFile = implode(',', $SFile);
        $SFile1 = implode(',', $SFile1);
        $SFile2 = implode(',', $SFile2);
        $SFile3 = implode(',', $SFile3);
        $SFile4 = implode(',', $SFile4);
        $SFile5 = implode(',', $SFile5);
        if ($cnt6 > 0) {
            $SFile6 = implode(',', $SFile6);
            $get_meta_sql = "SELECT * FROM  `car_info_meta` where car_id={$caid} AND `group`='hugging'";
            $res_get = $db->ArrayQuery($get_meta_sql);
            $deleted_rows = [];
            foreach ($res_get as $car_meta_index => $car_meta_value) {
                $deleted_rows[] = $car_meta_value['RowID'];
            }
            $import_deleted_ids = implode(',', $deleted_rows);
            $sql_meta = "INSERT INTO `car_info_meta` (`car_id`,`key`,`value`,`group`,`type`) VALUES({$caid},'hugging_start_date','{$hugging_start}','hugging','str'),({$caid},'hugging_end_date','{$hugging_end}','hugging','str'),({$caid},'hugging_files','{$SFile6}','hugging','file')";
            $res_meta = $db->Query($sql_meta);
            if ($res_meta && count($deleted_rows) > 0) {
                $delete_sql = "UPDATE `car_info_meta` set `status`=0 where RowID in($import_deleted_ids)";
                $u_res = $db->Query($delete_sql);
            }
        }



        $sql4 = "UPDATE `car_info` SET `carName`='{$name}',`plaque`='{$plaque}',`chassis`='{$chassis}',`serial`='{$serial}',`fuelType`={$fuelType},`technicalDiagDate`='{$TDDate}',`insuranceDate`='{$TIDate}',`insuranceBodyDate`='{$BIDate}',`technicalDiagFile`='{$SFile}',`insuranceFile`='{$SFile1}',`insuranceBodyFile`='{$SFile2}',`document`='{$SFile3}',`greenPage`='{$SFile4}',`lastStatus`='{$SFile5}',`carType`={$carType},`has_meta`='{$hugging_flag}' WHERE `RowID`={$caid}";
        $db->Query($sql4);
        $res = $db->AffectedRows();
        $res = (($res == -1) ? 0 : 1);
        if (intval($res)) {
            return true;
        } else {
            return false;
        }
    }

    public function getAttachedFileToCar($caid)
    {
        $db = new DBi();
        $ut = new Utility();
        $ut->fileRecorder('rrrrr');
        $query = "SELECT `carName` FROM `car_info` WHERE `RowID`={$caid}";
        $rst = $db->ArrayQuery($query);

        $meta_query = "SELECT * From `car_info_meta` where car_id={$caid} AND type='file'";
        $res2 = $db->ArrayQuery($meta_query);

        $ut->fileRecorder($meta_query);
        $carName = 'فایل های ضمیمه (' . $rst[0]['carName'] . ')';

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="carAttachedFile-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 60%;">شرح</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 30%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $functions = array('downloadTechnicalDiagFile', 'downloadThirdInsuranceFile', 'downloadBodyInsuranceFile', 'downloadCarDocumentFile', 'downloadGreenPageFile', 'downloadLastStatusFile');
        $fNames = array('معاینه فنی', 'بیمه شخص ثالث', 'بیمه بدنه', 'برگ سند', 'برگ سبز', 'آخرین وضعیت خودرو');
        if (count($res2) > 0) {
            $ut->fileRecorder($res2[0]['key']);

            if ($res2[0]['key'] == 'hugging_files') {
                $fNames[] = "مجوز بغل نویسی";
                $functions[] = "downloadhuggingFile";
            }
        }
        for ($i = 0; $i < count($fNames); $i++) {
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $fNames[$i] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="' . $functions[$i] . '(' . $caid . ')" ><i class="fas fa-file"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        $send = array($htm, $carName);
        return $send;
    }

    public function downloadTechnicalDiagHtm($caid)
    {
        $db = new DBi();
        $sql = "SELECT `technicalDiagFile` FROM `car_info` WHERE `RowID`={$caid}";
        $res = $db->ArrayQuery($sql);
        $files = array();
        $cnt = 0;
        if (strlen(trim($res[0]['technicalDiagFile'])) > 0) {
            $files = explode(',', $res[0]['technicalDiagFile']);
            $cnt = count($files);
        }

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="downloadTechnicalDiagHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 90%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $link = ADDR . 'carInfo/' . $files[$i];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function downloadThirdInsuranceHtm($caid)
    {
        $db = new DBi();
        $sql = "SELECT `insuranceFile` FROM `car_info` WHERE `RowID`={$caid}";
        $res = $db->ArrayQuery($sql);
        $files = array();
        $cnt = 0;
        if (strlen(trim($res[0]['insuranceFile'])) > 0) {
            $files = explode(',', $res[0]['insuranceFile']);
            $cnt = count($files);
        }

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="downloadThirdInsuranceHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 90%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $link = ADDR . 'carInfo/' . $files[$i];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function downloadBodyInsuranceHtm($caid)
    {
        $db = new DBi();
        $sql = "SELECT `insuranceBodyFile` FROM `car_info` WHERE `RowID`={$caid}";
        $res = $db->ArrayQuery($sql);
        $files = array();
        $cnt = 0;
        if (strlen(trim($res[0]['insuranceBodyFile'])) > 0) {
            $files = explode(',', $res[0]['insuranceBodyFile']);
            $cnt = count($files);
        }

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="downloadBodyInsuranceHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 90%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $link = ADDR . 'carInfo/' . $files[$i];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function downloadCarDocumentHtm($caid)
    {
        $db = new DBi();
        $sql = "SELECT `document` FROM `car_info` WHERE `RowID`={$caid}";
        $res = $db->ArrayQuery($sql);
        $files = array();
        $cnt = 0;
        if (strlen(trim($res[0]['document'])) > 0) {
            $files = explode(',', $res[0]['document']);
            $cnt = count($files);
        }

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="downloadCarDocumentHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 90%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $link = ADDR . 'carInfo/' . $files[$i];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function downloadGreenPageHtm($caid)
    {
        $db = new DBi();
        $sql = "SELECT `greenPage` FROM `car_info` WHERE `RowID`={$caid}";
        $res = $db->ArrayQuery($sql);
        $files = array();
        $cnt = 0;
        if (strlen(trim($res[0]['greenPage'])) > 0) {
            $files = explode(',', $res[0]['greenPage']);
            $cnt = count($files);
        }

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="downloadGreenPageHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 90%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $link = ADDR . 'carInfo/' . $files[$i];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function downloadLastStatusHtm($caid)
    {
        $db = new DBi();
        $sql = "SELECT `lastStatus` FROM `car_info` WHERE `RowID`={$caid}";
        $res = $db->ArrayQuery($sql);
        $files = array();
        $cnt = 0;
        if (strlen(trim($res[0]['lastStatus'])) > 0) {
            $files = explode(',', $res[0]['lastStatus']);
            $cnt = count($files);
        }

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="downloadLastStatusHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 90%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $link = ADDR . 'carInfo/' . $files[$i];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function downloadhuggingFile($caid)
    {
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `car_info_meta` WHERE `car_id`={$caid} AND `key`='hugging_files' AND `status`=1 AND `group`='hugging' AND `type`='file'";
        $ut->fileRecorder($sql);
        $res = $db->ArrayQuery($sql);
        $files = array();
        $cnt = 0;
        if (strlen(trim($res[0]['value'])) > 0) {
            $files = explode(',', $res[0]['value']);
            $cnt = count($files);
        }

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="downloadLastStatusHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 90%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $link = ADDR . 'carInfo/' . $files[$i];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getCarPayComments($carLayer, $layer)
    {
        $db = new DBi();
        $ut = new Utility();

        $query = "SELECT `carName` FROM `car_info` WHERE `carLayer`={$carLayer}";
        $rst = $db->ArrayQuery($query);
        $carName = 'اظهارنظرهای ثبت شده (' . $rst[0]['carName'] . ')';

        $htm = '';
        if (intval($carLayer) > 0) {
            $w = array();
            $w[] = '`layer2`=' . $carLayer . ' ';
            if (intval($layer) > 0) {
                $w[] = '`layer3`=' . $layer . ' ';
            }

            $sql = "SELECT `pid`,SUM(`finalAmount`) AS `fa` FROM `fund_list`";
            if (count($w) > 0) {
                $where = implode(" AND ", $w);
                $sql .= " WHERE " . $where;
            }
            $sql .= " GROUP BY `pid` ORDER BY `pid` ASC";
            $rst = $db->ArrayQuery($sql);

            $totalAmount = 0;
            $finalAmount = array();
            if (count($rst) > 0) {
                $ccnt = count($rst);
                $rids = array();
                for ($i = 0; $i < $ccnt; $i++) {
                    $sqq = "SELECT `RowID` FROM `pay_comment` WHERE `RowID`={$rst[$i]['pid']} AND `isEnable`=1";
                    $rsq = $db->ArrayQuery($sqq);
                    if (count($rsq) > 0) {
                        $rids[] = $rst[$i]['pid'];
                        $finalAmount[] = $rst[$i]['fa'];
                        $totalAmount += $rst[$i]['fa'];
                    }
                }
                $rids = implode(',', $rids);
            } else {
                $rids = '0';
            }

            $sql1 = "SELECT * FROM `pay_comment` WHERE";
            if (intval($layer) > 0) {
                $sql1 .= ' `layer2`=' . $carLayer . ' AND `layer3`=' . $layer . ' AND `isEnable`=1';
            } else {
                $sql1 .= ' `layer2`=' . $carLayer . ' AND `isEnable`=1';
            }
            $res1 = $db->ArrayQuery($sql1);
            $cnt = count($res1);

            $sql2 = "SELECT * FROM `pay_comment` WHERE `RowID` IN ({$rids}) AND `isEnable`=1";
            $res2 = $db->ArrayQuery($sql2);
            $cnt2 = count($res2);

            $iterator = 0;
            $htm .= '<form class="form-inline" style="margin: 20px 0;">';

            $htm .= '<div id="carPayCommentsLayerSearch-div" >';
            $htm .= '<label class="sr-only" for="carPayCommentsLayerSearch">به تفکیک</label>';
            $htm .= '<select class="form-control form-control-sm mb-2 mr-sm-2 headsearch shadow-color" id="carPayCommentsLayerSearch" style="width: 150px;" ></select>';
            $htm .= '</div>';

            $htm .= '<button type="button" class="btn btn-sm btn-secondary mb-2 mr-2" style="padding: 0.25rem 0.5rem;" onclick="showPayCommentForThisCar(' . $carLayer . ')">جستجو&nbsp;&nbsp;<i class="fa fa-search"></i></button>';

            $htm .= '</form>';


            $htm .= '<table class="table table-bordered table-hover table-sm" id="carPayComments-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 4%;">ردیف</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">تاریخ ثبت</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">در وجه</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">بابت</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">مبلغ</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">وضعیت</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">تنخواه</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">واریزی</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">چک</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">سایر اطلاعات</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">فایل پیوست</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">گردش کار</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">نمایش</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            for ($i = 0; $i < $cnt; $i++) {
                $iterator++;
                switch ($res1[$i]['transfer']) {
                    case 0:
                        $status = 'صادر شده';
                        break;
                    case 1:
                        $status = 'کشو پرداخت';
                        break;
                    case 2:
                        $status = 'تاییدیه مالی';
                        break;
                    case 3:
                        $status = 'پرداخت شده';
                        break;
                }
                $totalAmount += $res1[$i]['Amount'];
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $ut->greg_to_jal($res1[$i]['cDate']) . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res1[$i]['accName'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res1[$i]['Toward'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($res1[$i]['Amount']) . ' ریال</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $status . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showAttachedFundToCarComment(' . $res1[$i]['RowID'] . ',' . $carLayer . ')" ><i class="fas fa-list"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showDepositsInCarList(' . $res1[$i]['RowID'] . ')" ><i class="fas fa-dollar-sign"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowCommentCheckInCar(' . $res1[$i]['RowID'] . ')" ><i class="fas fa-money-check-alt"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowOtherInfoCarComment(' . $res1[$i]['RowID'] . ')" ><i class="fas fa-tv"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowAttachmentFileCarComment(' . $res1[$i]['RowID'] . ')" ><i class="fas fa-link"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowWorkflowCarComment(' . $res1[$i]['RowID'] . ')" ><i class="fas fa-sitemap"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="printCarPayComment(' . $res1[$i]['RowID'] . ')" ><i class="fas fa-search"></i></button></td>';
                $htm .= '</tr>';
            }
            for ($i = 0; $i < $cnt2; $i++) {
                $iterator++;
                switch ($res2[$i]['transfer']) {
                    case 0:
                        $status = 'صادر شده';
                        break;
                    case 1:
                        $status = 'کشو پرداخت';
                        break;
                    case 2:
                        $status = 'تاییدیه مالی';
                        break;
                    case 3:
                        $status = 'پرداخت شده';
                        break;
                }
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $ut->greg_to_jal($res2[$i]['cDate']) . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['accName'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['Toward'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($finalAmount[$i]) . ' ریال</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $status . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showAttachedFundToCarComment(' . $res2[$i]['RowID'] . ',' . $carLayer . ')" ><i class="fas fa-list"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showDepositsInCarList(' . $res2[$i]['RowID'] . ')" ><i class="fas fa-dollar-sign"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowCommentCheckInCar(' . $res2[$i]['RowID'] . ')" ><i class="fas fa-money-check-alt"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowOtherInfoCarComment(' . $res2[$i]['RowID'] . ')" ><i class="fas fa-tv"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowAttachmentFileCarComment(' . $res2[$i]['RowID'] . ')" ><i class="fas fa-link"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowWorkflowCarComment(' . $res2[$i]['RowID'] . ')" ><i class="fas fa-sitemap"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="printCarPayComment(' . $res2[$i]['RowID'] . ')" ><i class="fas fa-search"></i></button></td>';
                $htm .= '</tr>';
            }
            $htm .= '<tr class="bg-warning">';
            $htm .= '<td colspan="13" style="text-align: center;color: #000;font-family: dubai-Bold;font-size: 20px;">جمع کل مبالغ : ' . number_format($totalAmount) . ' ریال</td>';
            $htm .= '</tr>';

            $htm .= '</tbody>';
            $htm .= '</table>';
        }
        $send = array($htm, $carName);
        return $send;
    }

    public function getCarThreeLayers($carLayer)
    {
        $db = new DBi();
        $query = "SELECT `RowID`,`layerName` FROM `layers` WHERE `parentID`={$carLayer}";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0) {
            return $rst;
        } else {
            return array();
        }
    }

    public function OtherInfoCommentHTM($cid)
    {
        $acm = new acm();
        if (!$acm->hasAccess('commentManagement')) {
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `unCode`,`layer1`,`layer2`,`layer3`,`type`,`CashSection`,`paymentMaturityCash`,`BillingID`,`PaymentID`,`Transactions`,`RequestSource`,`RequestNumbers`,`desc` FROM `pay_comment` WHERE `RowID`={$cid}";
        $res = $db->ArrayQuery($sql);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoComment-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">سایر اطلاعات</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مقدار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        if ($res[0]['type'] == 'پرداخت قبض' || $res[0]['type'] == 'پرداخت جریمه') {
            $infoNames = array('شماره یکتا', 'سر گروه', 'زیر گروه', 'زیرگروه فرعی', 'نوع', 'بخش نقدی', 'سررسید پرداخت', 'شناسه قبض', 'شناسه پرداخت', 'طبقه معاملات', 'منبع درخواست', 'شماره درخواست', 'توضیحات');
            for ($i = 0; $i < 13; $i++) {
                $iterator++;
                $keyName = key($res[0]);
                if ($iterator == 2 || $iterator == 3 || $iterator == 4) {
                    $sql1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]["$keyName"]}";
                    $rst = $db->ArrayQuery($sql1);
                    $res[0]["$keyName"] = $rst[0]['layerName'];
                }
                if ($iterator == 6) {
                    $res[0]["$keyName"] = number_format($res[0]["$keyName"]) . ' ریال';
                }
                if ($iterator == 7) {
                    $res[0]["$keyName"] = $ut->greg_to_jal($res[0]["$keyName"]);
                }
                if ($iterator == 10) {
                    switch ($res[0]["$keyName"]) {
                        case 1:
                            $res[0]["$keyName"] = 'جزئی';
                            break;
                        case 2:
                            $res[0]["$keyName"] = 'متوسط';
                            break;
                        case 3:
                            $res[0]["$keyName"] = 'عمده';
                            break;
                        case 4:
                            $res[0]["$keyName"] = 'کلان';
                            break;
                    }
                }
                if ($iterator == 11) {
                    switch ($res[0]["$keyName"]) {
                        case -1:
                            $res[0]["$keyName"] = '-------------';
                            break;
                        case 1:
                            $res[0]["$keyName"] = 'اعلام شفاهی مدیریت محترم عامل';
                            break;
                        case 2:
                            $res[0]["$keyName"] = 'اعلام شفاهی معاونت محترم بازرگانی';
                            break;
                        case 3:
                            $res[0]["$keyName"] = 'اعلام شفاهی قائم مقام محترم';
                            break;
                        case 4:
                            $res[0]["$keyName"] = 'قرارداد';
                            break;
                    }
                }
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $infoNames[$i] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[0]["$keyName"] . '</td>';
                $htm .= '</tr>';
                next($res[0]);
            }
        } else {
            $sql = "SELECT `unCode`,`layer1`,`layer2`,`layer3`,`clearingFundDate`,`type`,`CashSection`,`paymentMaturityCash`,`NonCashSection`,`paymentMaturityCheck`,`Transactions`,`accName`,`accNumber`,`accBank`,`codeTafzili`,`nationalCode`,`RequestSource`,`RequestNumbers`,`contractNumber`,`tick`,`checkNumber`,`checkDate`,`checkCarcass`,`checkDeliveryDate`,`RowID`,`desc`,`cardNumber` FROM `pay_comment` WHERE `pay_comment`.`RowID`={$cid}";
            $res = $db->ArrayQuery($sql);
            $infoNames = array('شماره یکتا', 'سر گروه', 'زیر گروه', 'زیرگروه فرعی', 'تاریخ تسویه تنخواه', 'نوع', 'بخش نقدی', 'سررسید پرداخت نقدی', 'بخش چک', 'سررسید پرداخت چک', 'طبقه معاملات', 'نام طرف حساب', 'شماره حساب', 'نام بانک و شعبه', 'کد تفضیلی', 'کد ملی', 'منبع درخواست', 'شماره درخواست', 'شماره قرارداد', 'پرینت گرفته و مستندات پیوست شده است', 'شماره چک', 'تاریخ چک', 'لاشه چک تحویل واحد مالی', 'تعهد تاریخ تحویل لاشه چک به واحد مالی', 'رسید تحویل چک', 'توضیحات', 'شماره کارت');
            $direction = '';
            for ($i = 0; $i < 27; $i++) {
                $iterator++;
                $keyName = key($res[0]);
                if ($iterator == 2 || $iterator == 3 || $iterator == 4) {
                    $sql1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]["$keyName"]}";
                    $rst = $db->ArrayQuery($sql1);
                    $res[0]["$keyName"] = $rst[0]['layerName'];
                }
                if ($iterator == 5) {
                    if (strtotime($res[0]["$keyName"]) > 0) {
                        $res[0]["$keyName"] = $ut->greg_to_jal($res[0]["$keyName"]);
                    } else {
                        next($res[0]);
                        continue;
                    }
                }
                if ($iterator == 7 || $iterator == 9) {
                    $res[0]["$keyName"] = number_format($res[0]["$keyName"]) . ' ریال';
                }
                if ($iterator == 8 || $iterator == 10) {
                    $res[0]["$keyName"] = (strtotime($res[0]["$keyName"]) > 0 ? $ut->greg_to_jal($res[0]["$keyName"]) : '');
                }
                if ($iterator == 11) {
                    switch ($res[0]["$keyName"]) {
                        case 1:
                            $res[0]["$keyName"] = 'جزئی';
                            break;
                        case 2:
                            $res[0]["$keyName"] = 'متوسط';
                            break;
                        case 3:
                            $res[0]["$keyName"] = 'عمده';
                            break;
                        case 4:
                            $res[0]["$keyName"] = 'کلان';
                            break;
                    }
                }
                if ($iterator == 17) {
                    switch ($res[0]["$keyName"]) {
                        case -1:
                            $res[0]["$keyName"] = '-------------';
                            break;
                        case 1:
                            $res[0]["$keyName"] = 'اعلام شفاهی مدیریت محترم عامل';
                            break;
                        case 2:
                            $res[0]["$keyName"] = 'اعلام شفاهی معاونت محترم بازرگانی';
                            break;
                        case 3:
                            $res[0]["$keyName"] = 'اعلام شفاهی قائم مقام محترم';
                            break;
                        case 4:
                            $res[0]["$keyName"] = 'قرارداد';
                            break;
                    }
                }

                if ($iterator == 20) {
                    switch ($res[0]["$keyName"]) {
                        case 0:
                            $res[0]["$keyName"] = 'خیر';
                            break;
                        case 1:
                            $res[0]["$keyName"] = 'بلی';
                            break;
                    }
                }

                if ($iterator == 23) {
                    switch (intval($res[0]["$keyName"])) {
                        case 0:
                            $res[0]["$keyName"] = '-------------';
                            break;
                        case 1:
                            $res[0]["$keyName"] = 'داده شده است';
                            break;
                        case 2:
                            $res[0]["$keyName"] = 'داده نشده است';
                            break;
                    }
                }

                if ($iterator == 22 || $iterator == 24) {
                    $res[0]["$keyName"] = (strtotime($res[0]["$keyName"]) > 0 ? $ut->greg_to_jal($res[0]["$keyName"]) : '');
                }
                if ($iterator == 25) {
                    $res[0]["$keyName"] = '<button class="btn btn-info" onclick="downloadCheckCarcassCarFile(' . $res[0]["$keyName"] . ')"><i class="fas fa-download"></i></button>';
                }
                if ($iterator == 27) {
                    $direction = 'dir="ltr"';
                }

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $infoNames[$i] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;" ' . $direction . '>' . $res[0]["$keyName"] . '</td>';
                $htm .= '</tr>';
                next($res[0]);
            }
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getPrintPayCommentHtm($cid)
    {
        $acm = new acm();
        if (!$acm->hasAccess('carInformationManage')) {
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `pay_comment`.*,`unitName`,`fname`,`lname`,`layerName`,`postJob`,`signature` FROM `pay_comment`
                INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)
                INNER JOIN `users` ON (`pay_comment`.`uid`=`users`.`RowID`)
                INNER JOIN `layers` ON (`pay_comment`.`layer1`=`layers`.`RowID`)
                WHERE `pay_comment`.`RowID`={$cid}";
        $res = $db->ArrayQuery($sql);

        $query = "SELECT `unitName` FROM `relatedunits` WHERE `RowID`={$res[0]['consumerUnit']}";
        $rst = $db->ArrayQuery($query);

        if (intval($res[0]['layer2']) > 0) {
            $query1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]['layer2']}";
            $rst1 = $db->ArrayQuery($query1);
            $twoLayer = $rst1[0]['layerName'];
        } else {
            $twoLayer = '&emsp;&emsp;&emsp;&emsp;';
        }
        if (intval($res[0]['layer3']) > 0) {
            $query2 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]['layer3']}";
            $rst2 = $db->ArrayQuery($query2);
            $threeLayer = $rst2[0]['layerName'];
        } else {
            $threeLayer = '&emsp;&emsp;&emsp;&emsp;';
        }

        $sqlsig = "SELECT `sender`,`createDate`,`fname`,`lname`,`postJob`,`signature` FROM `payment_workflow` INNER JOIN `users` ON  (`payment_workflow`.`sender`=`users`.`RowID`) WHERE `status`=1 AND `pid`={$cid}";
        $rsig = $db->ArrayQuery($sqlsig);
        $cnt = count($rsig);

        $beginner = '<img width="120px;" height="120px;" src="' . ADDR . 'Signature/' . $res[0]['signature'] . '">';
        $fbeginner = $res[0]['postJob'] . ' ' . $ut->greg_to_jal($res[0]['cDate']) . ' ' . $res[0]['fname'] . ' ' . $res[0]['lname'];
        $Sarparast = '';
        $fSarparast = '';
        $Moavenat = '';
        $fMoavenat = '';
        $Hesabdar = '';
        $fHesabdar = '';
        $RHesabdar = '';
        $fRHesabdar = '';
        $MHesabdar = '';
        $fMHesabdar = '';
        $Modiriat = '';
        $fModiriat = '';

        for ($i = 0; $i < $cnt; $i++) {
            if ($rsig[$i]['sender'] == 3 || $rsig[$i]['sender'] == 14 || $rsig[$i]['sender'] == 46) {  // حقیقت و مصطفوی عفت پناه
                $Sarparast = '<img width="120px;" height="120px;" src="' . ADDR . 'Signature/' . $rsig[$i]['signature'] . '">';
                $fSarparast = $rsig[$i]['postJob'] . ' ' . $ut->greg_to_jal($rsig[$i]['createDate']) . ' ' . $rsig[$i]['fname'] . ' ' . $rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 20) {  // معاونت بازرگانی
                $Moavenat = '<img width="120px;" height="120px;" src="' . ADDR . 'Signature/' . $rsig[$i]['signature'] . '">';
                $fMoavenat = $rsig[$i]['postJob'] . ' ' . $ut->greg_to_jal($rsig[$i]['createDate']) . ' ' . $rsig[$i]['fname'] . ' ' . $rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 44 || $rsig[$i]['sender'] == 42) {  // کارشناس حسابداری
                $Hesabdar = '<img width="120px;" height="120px;" src="' . ADDR . 'Signature/' . $rsig[$i]['signature'] . '">';
                $fHesabdar = $rsig[$i]['postJob'] . ' ' . $ut->greg_to_jal($rsig[$i]['createDate']) . ' ' . $rsig[$i]['fname'] . ' ' . $rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 39) {  // رئیس حسابداری
                $RHesabdar = '<img width="120px;" height="120px;" src="' . ADDR . 'Signature/' . $rsig[$i]['signature'] . '">';
                $fRHesabdar = $rsig[$i]['postJob'] . ' ' . $ut->greg_to_jal($rsig[$i]['createDate']) . ' ' . $rsig[$i]['fname'] . ' ' . $rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 27) {  // مدیر مالی
                $MHesabdar = '<img width="120px;" height="120px;" src="' . ADDR . 'Signature/' . $rsig[$i]['signature'] . '">';
                $fMHesabdar = $rsig[$i]['postJob'] . ' ' . $ut->greg_to_jal($rsig[$i]['createDate']) . ' ' . $rsig[$i]['fname'] . ' ' . $rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 4) {  // مدیر عامل
                $Modiriat = '<img width="120px;" height="120px;" src="' . ADDR . 'Signature/' . $rsig[$i]['signature'] . '">';
                $fModiriat = $rsig[$i]['postJob'] . ' ' . $ut->greg_to_jal($rsig[$i]['createDate']) . ' ' . $rsig[$i]['fname'] . ' ' . $rsig[$i]['lname'];
            }
        }


        if (!($res[0]['accNumber'] === '0') || !($res[0]['accNumber'] === '')) {
            $accountNumber = explode('-', $res[0]['accNumber']);
            $accountNumber = array_reverse($accountNumber);
            $accountNumber = implode('-', $accountNumber);
            $banks = $res[0]['accBank'];
        }

        $datetostring = substr($ut->greg_to_jal(str_replace('/', '-', $res[0]['cDate'])), 2);
        $datetostring = explode('/', $datetostring);
        $m = (intval($datetostring[1]) <= 9 ? '0' . $datetostring[1] : $datetostring[1]);
        $d = (intval($datetostring[2]) <= 9 ? '0' . $datetostring[2] : $datetostring[2]);
        $datetostring = [0 => $datetostring[0], 1 => $m, 2 => $d];
        $datetostring = implode('/', $datetostring);
        $personalCode = [0 => $datetostring, 1 => $res[0]['uid']];
        $personalCode = implode('-', $personalCode);

        switch ($res[0]['Transactions']) {
            case 1:
                $Transactions = 'جزئی';
                break;
            case 2:
                $Transactions = 'متوسط';
                break;
            case 3:
                $Transactions = 'عمده';
                break;
            case 4:
                $Transactions = 'کلان';
                break;
        }

        if (intval($res[0]['RequestSource']) > 0) {
            switch ($res[0]['RequestSource']) {
                case 1:
                    $SN = 'منبع درخواست : ';
                    $RequestSN = 'اعلام شفاهی مدیریت محترم عامل';
                    break;
                case 2:
                    $SN = 'منبع درخواست : ';
                    $RequestSN = 'اعلام شفاهی معاونت محترم بازرگانی';
                    break;
                case 3:
                    $SN = 'منبع درخواست : ';
                    $RequestSN = 'اعلام شفاهی قائم مقام محترم';
                    break;
                case 4:
                    $SN = 'منبع درخواست : ';
                    $RequestSN = 'قرارداد';
                    break;
            }
        } else {
            $SN = 'شماره درخواست : ';
            $RequestSN = $res[0]['RequestNumbers'];
        }

        if (intval($res[0]['sendType']) == 2) {  // سهامی بود
            if (intval($res[0]['CashSection']) > 0 && intval($res[0]['NonCashSection']) > 0) {
                $naghd = 'مبلغ نقد : <span style="font-size: 25px;font-family: BTitr;">' . number_format($res[0]['CashSection']) . '</span> ریال به تاریخ : <span style="font-size: 25px;font-family: BTitr;">' . $ut->greg_to_jal($res[0]['paymentMaturityCash']) . '</span>';
                $checki = 'مبلغ چک : <span style="font-size: 25px;font-family: BTitr;">' . number_format($res[0]['NonCashSection']) . '</span> ریال به تاریخ (میانگین) : <span style="font-size: 25px;font-family: BTitr;">' . $ut->greg_to_jal($res[0]['paymentMaturityCheck']) . '</span>';
                $mablagh = $naghd . ' ' . $checki;
                $shaba = 'شماره شبا : <span style="font-size: 25px;font-family: BTitr;">' . $accountNumber . '</span>';
                $AccAndBank = 'نام بانک و صاحب حساب : <span style="font-size: 25px;font-family: BTitr;">' . $banks . '</span>';
            } elseif (intval($res[0]['CashSection']) > 0) {
                $mablagh = 'مبلغ نقد : <span style="font-size: 25px;font-family: BTitr;">' . number_format($res[0]['CashSection']) . '</span> ریال به تاریخ : <span style="font-size: 25px;font-family: BTitr;">' . $ut->greg_to_jal($res[0]['paymentMaturityCash']) . '</span>';
                if ($res[0]['type'] != 'پرداخت قبض' && $res[0]['type'] != 'پرداخت جریمه') {
                    $shaba = 'شماره شبا : <span style="font-size: 25px;font-family: BTitr;">' . $accountNumber . '</span>';
                    $AccAndBank = 'نام بانک و صاحب حساب : <span style="font-size: 25px;font-family: BTitr;">' . $banks . '</span>';
                } else {
                    $shaba = 'شناسه قبض : <span style="font-size: 25px;font-family: BTitr;">' . $res[0]['BillingID'] . '</span>';
                    $AccAndBank = 'شناسه پرداخت : <span style="font-size: 25px;font-family: BTitr;">' . $res[0]['PaymentID'] . '</span>';
                }
            } else {
                $mablagh = 'مبلغ چک : <span style="font-size: 25px;font-family: BTitr;">' . number_format($res[0]['NonCashSection']) . '</span> ریال به تاریخ (میانگین) : <span style="font-size: 25px;font-family: BTitr;">' . $ut->greg_to_jal($res[0]['paymentMaturityCheck']) . '</span>';
                $shaba = 'فاقد بخش نقدی می باشد !!!';
                $AccAndBank = '';
            }
        } elseif (intval($res[0]['sendType']) == 0) {  // فورج نقدی
            $mablagh = 'مبلغ نقد : <span style="font-size: 25px;font-family: BTitr;">' . number_format($res[0]['CashSection']) . '</span> ریال به تاریخ : <span style="font-size: 25px;font-family: BTitr;">' . $ut->greg_to_jal($res[0]['paymentMaturityCash']) . '</span>';
            if ($res[0]['type'] != 'پرداخت قبض' && $res[0]['type'] != 'پرداخت جریمه') {
                $shaba = 'شماره شبا : <span style="font-size: 25px;font-family: BTitr;">' . $accountNumber . '</span>';
                $AccAndBank = 'نام بانک و صاحب حساب : <span style="font-size: 25px;font-family: BTitr;">' . $banks . '</span>';
            } else {
                $shaba = 'شناسه قبض : <span style="font-size: 25px;font-family: BTitr;">' . $res[0]['BillingID'] . '</span>';
                $AccAndBank = 'شناسه پرداخت : <span style="font-size: 25px;font-family: BTitr;">' . $res[0]['PaymentID'] . '</span>';
            }
        } elseif (intval($res[0]['sendType']) == 1) {  // فورج چکی
            $mablagh = 'مبلغ چک : <span style="font-size: 25px;font-family: BTitr;">' . number_format($res[0]['NonCashSection']) . '</span> ریال به تاریخ (میانگین) : <span style="font-size: 25px;font-family: BTitr;">' . $ut->greg_to_jal($res[0]['paymentMaturityCheck']) . '</span>';
            $shaba = 'فاقد بخش نقدی می باشد !!!';
            $AccAndBank = '';
        } else {
            $mablagh = 'مبلغ : <span style="font-size: 25px;font-family: BTitr;">' . number_format($res[0]['Amount']) . '</span> ریال ';
            $shaba = 'فاقد بخش نقدی و غیر نقدی می باشد !!!';
            $AccAndBank = '';
        }

        $srcc = ADDR . 'images/abrash.png';
        $htm = '';
        $htm .= '<div class="cardemo" style="width: 100%;margin: -85px auto;">';
        // page 1
        $htm .= '<table style="width: 100%;border: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr style="border: 2px solid #000;">';
        $htm .= '<th style="width: 25%;padding-left: 10px;text-align: center;background-color: #fff;"><img src="' . $srcc . '"></th>';
        $htm .= '<th style="width: 50%;font-size: 40px;font-family: BTitr;background-color: #ddd;text-align: center;padding: 0 20px;">اظهار نظر و درخواست<br> پرداخت وجه</th>';
        $htm .= '<th style="width: 25%;font-size: 20px;font-family: BNazanin;text-align: right;padding-right: 30px;background-color: #fff;">کد فرم : F121009<br>کد ثبت : ' . $personalCode . '<br>سطح تغييرات:  2</th>';
        $htm .= '</tr>';
        $htm .= '<tr style="border-right: 2px solid #000;border-left: 2px solid #000;height: 5px;">';
        $htm .= '<th colspan="3"></th>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        //************************ شماره یکتا - نوع - تاریخ *************************

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: right;font-weight: bold;" class="pr-3">شماره یکتا : ' . $res[0]['unCode'] . '</td>';
        $htm .= '<td style="width: 30%;background-color: #fff;font-size: 30px;font-family: BTitr;text-align: center;">' . $res[0]['type'] . '</td>';
        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: left;font-weight: bold;" class="pl-3">تاریخ : ' . $ut->greg_to_jal($res[0]['cDate']) . '</td>';
        $htm .= '</tr>';
        $htm .= '<tr>';
        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: right;font-weight: bold;" class="pr-3">سرگروه : <span style="font-size: 25px;font-family: BTitr;">' . $res[0]['layerName'] . '</span></td>';
        $htm .= '<td style="width: 30%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: center;font-weight: bold;">زیرگروه : <span style="font-size: 25px;font-family: BTitr;">' . $twoLayer . '</span></td>';
        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: left;font-weight: bold;" class="pl-3">زیرگروه فرعی : <span style="font-size: 25px;font-family: BTitr;">' . $threeLayer . '</span></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        //************************ اطلاعات اظهارنظر *************************

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding-top: 3px;width: 30%;background-color: #fff;"></td>';
        $htm .= '<td style="padding-top: 3px;width: 35%;background-color: #fff;font-size: 25px;font-family: BTitr;text-align: center;">قسمت اطلاعات اظهارنظر</td>';
        $htm .= '<td style="padding-top: 3px;width: 35%;background-color: #fff;"></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding-top: 5px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">فرد صادرکننده : <span style="font-size: 25px;font-family: BTitr;">' . $res[0]['fname'] . ' ' . $res[0]['lname'] . '</span></td>';
        $htm .= '<td style="padding-top: 5px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;">طبقه معاملات : <span style="font-size: 25px;font-family: BTitr;">' . $Transactions . '</span></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding-top: 3px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">واحد درخواست کننده : <span style="font-size: 25px;font-family: BTitr;">' . $res[0]['unitName'] . '</span></td>';
        $htm .= '<td style="padding-top: 3px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;">' . $SN . '<span style="font-size: 25px;font-family: BTitr;">' . $RequestSN . '</span></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 3px 0;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">واحد مصرف کننده : <span style="font-size: 25px;font-family: BTitr;">' . $rst[0]['unitName'] . '</span></td>';
        $htm .= '<td style="padding: 3px 0;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;"></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        //************************ اطلاعات خرید کالا / خدمات *************************

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding-top: 3px;width: 30%;background-color: #fff;"></td>';
        $htm .= '<td style="padding-top: 3px;width: 35%;background-color: #fff;font-size: 25px;font-family: BTitr;text-align: center;">قسمت اطلاعات خرید کالا / خدمات</td>';
        $htm .= '<td style="padding-top: 3px;width: 35%;background-color: #fff;"></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 5px 0;width: 60%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">طرف مقابل : <span style="font-size: 25px;font-family: BTitr;">' . $res[0]['accName'] . ' - ' . $res[0]['codeTafzili'] . '</span></td>';
        $htm .= '<td style="padding: 5px 0;width: 40%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;">شماره قرارداد : <span style="font-size: 25px;font-family: BTitr;">' . $res[0]['contractNumber'] . '</span></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding-top: 3px;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">بابت : <span style="font-size: 25px;font-family: BTitr;">' . $res[0]['Toward'] . '</span></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        if ($res[0]['type'] !== 'ثبت در حساب بستانکاری طرف مقابل') {
            $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
            $htm .= '<thead>';
            $htm .= '<tr>';
            $htm .= '<td style="padding-top: 3px;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">مبلغ کل اظهارنظر : <span style="font-size: 25px;font-family: BTitr;">' . number_format($res[0]['Amount']) . ' ریال</span></td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '</table>';
        }

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding-top: 3px;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">' . $mablagh . '</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 3px 0;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">توضیحات : <span style="font-size: 25px;font-family: BTitr;" dir="rtl">' . $res[0]['desc'] . '</span></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        //************************ اطلاعات حساب *************************

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding-top: 3px;width: 30%;background-color: #fff;"></td>';
        $htm .= '<td style="padding-top: 3px;width: 35%;background-color: #fff;font-size: 25px;font-family: BTitr;text-align: center;" class="pr-3">قسمت اطلاعات حساب</td>';
        $htm .= '<td style="padding-top: 3px;width: 35%;background-color: #fff;"></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 7px 0;width: 60%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">' . $shaba . '</td>';
        $htm .= '<td style="padding: 7px 0;width: 40%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: right;">' . $AccAndBank . '</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        //************************ امضا کارشناس و سرپرست و مدیر واحد *************************

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">' . $beginner . $fbeginner . '</td>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 34%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">' . $Sarparast . $fSarparast . '</td>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">' . $Moavenat . $fMoavenat . '</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        //************************ امضا حسابداری *************************

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">' . $Hesabdar . $fHesabdar . '</td>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 34%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">' . $RHesabdar . $fRHesabdar . '</td>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">' . $MHesabdar . $fMHesabdar . '</td>';
        $htm .= '</tr>';
        if (strtotime($res[0]['clearingFundDate']) > 0) {
            $htm .= '<tr>';
            $htm .= '<td colspan="3" class="pr-3" style="padding: 10px 0 20px 0;width: 100%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: right;">قابل توجه واحد مالی : تاریخ تسویه تنخواه ' . $ut->greg_to_jal($res[0]['clearingFundDate']) . ' می باشد</td>';
            $htm .= '</tr>';
        }
        $htm .= '</thead>';
        $htm .= '</table>';

        /*            $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
                    $htm .= '<thead>';
                    $htm .= '<tr>';
                    $htm .= '<td class="pr-3" style="padding-top: 20px;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;">مدیر محترم امور مالی، پرداخت به شرح فوق مورد تایید است، لطفا اقدام فرمایید.</td>';
                    $htm .= '</tr>';
                    $htm .= '</thead>';
                    $htm .= '</table>';*/

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td class="pl-3" style="padding: 50px 0 20px 0;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: left;">' . $Modiriat . $fModiriat . '</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';
        $htm .= '</div>';

        return $htm;
    }

    public function getAttachFundToCommentList($cid, $carLayer)
    {
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT `fund_list`.*,`fname`,`lname` FROM `fund_list` INNER JOIN `users` ON (`fund_list`.`uid`=`users`.`RowID`) WHERE `pid`={$cid} AND `layer2`={$carLayer}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="getAttachFundToCarCommentList-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 14%;">ثبت کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 11%;">نوع تنخواه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 11%;">کد تنخواه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 11%;">سرگروه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 11%;">زیرگروه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 11%;">زیرگروه فرعی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">مبلغ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">جزئیات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $sqq = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer1']}";
            $sqq1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer2']}";
            $sqq2 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer3']}";
            $rst = $db->ArrayQuery($sqq);
            $rst1 = $db->ArrayQuery($sqq1);
            $rst2 = $db->ArrayQuery($sqq2);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $ut->greg_to_jal($res[$i]['cDate']) . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['fname'] . ' ' . $res[$i]['lname'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . (intval($res[$i]['fundName']) == 0 ? 'تنخواه هزینه ای' : 'تنخواه مصرفی') . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['unCode'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . (count($rst) > 0 ? $rst[0]['layerName'] : '') . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . (count($rst1) > 0 ? $rst1[0]['layerName'] : '') . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . (count($rst2) > 0 ? $rst2[0]['layerName'] : '') . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($res[$i]['finalAmount']) . ' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="attachedCarFundListDetails(' . $res[$i]['RowID'] . ')"><i class="fas fa-puzzle-piece"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function showFundListDetailsHTM($fid)
    {
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `fund_list_details` WHERE `fid`={$fid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="showCarFundListDetailsHTM-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 23%;">شرح</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 16%;">شماره درخواست</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 17%;">محل استفاده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">مبلغ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">ضمیمه</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i = 0; $i < $cnt; $i++) {
            $cDate = (strtotime($res[$i]['createDate']) > 0 ? $ut->greg_to_jal($res[$i]['createDate']) : '');
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $cDate . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['description'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['reqNumber'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['placeUse'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . number_format($res[$i]['fundAmount']) . ' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showAttachCarFundListDetails(' . $res[$i]['RowID'] . ')"><i class="fas fa-link"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function enterExitCar($CarType, $EorE, $cTime, $km, $dName)
    {
        $acm = new acm();
        if (!$acm->hasAccess('recordEnterExitCar')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $nowDate = date('Y-m-d');
        $sql = "INSERT INTO `car_enter_exit` (`carID`,`eeType`,`eeDate`,`eeTime`,`kilometer`,`driverName`,`uid`) VALUES ({$CarType},{$EorE},'{$nowDate}','{$cTime}','{$km}',{$dName},{$_SESSION['userid']})";
        $res = $db->Query($sql);
        if (intval($res) > 0) {
            $query = "UPDATE `car_info` SET `lastKilometer`='{$km}' WHERE `RowID`={$CarType}";
            $db->Query($query);
            return true;
        } else {
            return false;
        }
    }

    public function deleteEnterExit($eeID)
    {
        $acm = new acm();
        if (!$acm->hasAccess('recordEnterExitCar')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT * FROM `car_enter_exit` WHERE `RowID`={$eeID}";
        $res = $db->ArrayQuery($sql);

        $sql1 = "INSERT INTO `car_enter_exit_backup` (`eeID`,`carID`,`eeType`,`eeDate`,`eeTime`,`kilometer`,`driverName`,`uid`,`deleteORedit`) VALUES ({$eeID},{$res[0]['carID']},{$res[0]['eeType']},'{$res[0]['eeDate']}','{$res[0]['eeTime']}','{$res[0]['kilometer']}',{$res[0]['driverName']},{$_SESSION['userid']},0)";
        $db->Query($sql1);

        $sql = "DELETE FROM `car_enter_exit` WHERE `RowID`={$eeID}";
        $db->Query($sql);

        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function enterExitCarInfo($eeID)
    {
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `car_enter_exit` WHERE `RowID`=" . $eeID;
        $res = $db->ArrayQuery($sql);
        if (count($res) == 1) {
            $res = array("eeID" => $eeID, "eeType" => $res[0]['eeType'], "eeTime" => $res[0]['eeTime'], "kilometer" => $res[0]['kilometer'], "driverName" => $res[0]['driverName']);
            return $res;
        } else {
            return false;
        }
    }

    public function editEnterExitCar($eeID, $EorE, $cTime, $km, $dName)
    {
        $acm = new acm();
        if (!$acm->hasAccess('recordEnterExitCar')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT * FROM `car_enter_exit` WHERE `RowID`={$eeID}";
        $res = $db->ArrayQuery($sql);

        $sql1 = "INSERT INTO `car_enter_exit_backup` (`eeID`,`carID`,`eeType`,`eeDate`,`eeTime`,`kilometer`,`driverName`,`uid`,`deleteORedit`) VALUES ({$eeID},{$res[0]['carID']},{$res[0]['eeType']},'{$res[0]['eeDate']}','{$res[0]['eeTime']}','{$res[0]['kilometer']}',{$res[0]['driverName']},{$res[0]['uid']},1)";
        $db->Query($sql1);

        $sql2 = "UPDATE `car_enter_exit` SET `eeType`={$EorE},`eeTime`='{$cTime}',`kilometer`='{$km}',`driverName`={$dName},`uid`={$_SESSION['userid']}  WHERE `RowID`={$eeID}";
        $db->Query($sql2);

        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getEnterExitCarHTM($caid, $sDate, $eDate, $dName, $eeType)
    {
        $ut = new Utility();
        $db = new DBi();

        $query = "SELECT `carName` FROM `car_info` WHERE `RowID`={$caid}";
        $rst = $db->ArrayQuery($query);
        $carName = 'ورود و خروج (' . $rst[0]['carName'] . ')';

        $driver = $this->getAllDriver();
        $cntd = count($driver);

        $w = array();
        if (strlen(trim($sDate)) > 0) {
            $sDate = $ut->jal_to_greg($sDate);
            $w[] = '`eeDate` >="' . $sDate . '" ';
        }
        if (strlen(trim($eDate)) > 0) {
            $eDate = $ut->jal_to_greg($eDate);
            $w[] = '`eeDate` <="' . $eDate . '" ';
        }
        if (intval($dName) > 0) {
            $w[] = '`driverName`=' . $dName . ' ';
        }
        if (intval($eeType) >= 0) {
            $w[] = '`eeType`=' . $eeType . ' ';
        }
        $w[] = '`carID`=' . $caid . ' ';

        $sql = "SELECT `car_enter_exit`.*,`fname`,`lname` FROM `car_enter_exit` INNER JOIN `users` ON (`users`.`RowID`=`car_enter_exit`.`uid`) ";
        if (count($w) > 0) {
            $where = implode(" AND ", $w);
            $sql .= " WHERE " . $where;
        }
        $sql .= " ORDER BY `eeDate` Desc,`eeTime` ASC";
        $ut->fileRecorder('sqqll'.$sql);
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        $htm .= '<form class="form-inline" style="margin: 20px 0;">';

        $htm .= '<div id="carManageSDateSearch-div" >';
        $htm .= '<label class="sr-only" for="carManageSDateSearch">از تاریخ</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="carManageSDateSearch" autocomplete="off" style="width: 150px;" placeholder="از تاریخ" >';
        $htm .= '</div>';

        $htm .= '<div id="carManageEDateSearch-div" >';
        $htm .= '<label class="sr-only" for="carManageEDateSearch">تا تاریخ</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="carManageEDateSearch" autocomplete="off" style="width: 150px;" placeholder="تا تاریخ" >';
        $htm .= '</div>';

        $htm .= '<div id="carManageEETypeSearch-div" >';
        $htm .= '<label class="sr-only" for="carManageEETypeSearch">نوع رفت و آمد</label>';
        $htm .= '<select class="form-control form-control-sm mb-2 mr-sm-2 headsearch shadow-color" id="carManageEETypeSearch" style="width: 150px;" >';
        $htm .= '<option value="-1">نوع رفت و آمد</option>';
        $htm .= '<option value="0">ورود</option>';
        $htm .= '<option value="1">خروج</option>';
        $htm .= '</select>';
        $htm .= '</div>';

        $htm .= '<div id="carManageDNameSearch-div" >';
        $htm .= '<label class="sr-only" for="carManageDNameSearch">راننده</label>';
        $htm .= '<select class="form-control form-control-sm mb-2 mr-sm-2 headsearch shadow-color" id="carManageDNameSearch" style="width: 150px;" >';
        $htm .= '<option value="-1">راننده</option>';
        for ($i = 0; $i < $cntd; $i++) {
            $htm .= '<option value="' . $driver[$i]['RowID'] . '">' . $driver[$i]['driverName'] . '</option>';
        }
        $htm .= '</select>';
        $htm .= '</div>';

        $htm .= '<button type="button" class="btn btn-sm btn-secondary mb-2 mr-2" style="padding: 0.25rem 0.5rem;" onclick="showEnterExitThisCarSearch(' . $caid . ')">جستجو&nbsp;&nbsp;<i class="fa fa-search"></i></button>';
        $htm .= '</form>';

        $htm .= '<table class="table table-bordered table-hover table-sm" id="enterExitCar-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 16%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 16%;">کاربر ثبت کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">نوع رفت و آمد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">ساعت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">کیلومتر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 16%;">راننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">حذف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ویرایش</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i = 0; $i < $cnt; $i++) {
            $sqq = "SELECT `driverName` FROM `driver` WHERE `RowID`={$res[$i]['driverName']}";
           // $sqq = "SELECT concat(`fname`,' ',`lname`) as `driverName`  FROM `personnel` WHERE `RowID`={$res[$i]['driverName']}";
            $rst = $db->ArrayQuery($sqq);
            $eeType = ($res[$i]['eeType'] == 0 ? 'ورود' : 'خروج');
            $eeDate = (strtotime($res[$i]['eeDate']) > 0 ? $ut->greg_to_jal($res[$i]['eeDate']) : '');
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . ($i+1) . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['fname'] . ' ' . $res[$i]['lname'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $eeType . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $eeDate . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['eeTime'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['kilometer'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst[0]['driverName'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteEnterExitCar(' . $res[$i]['RowID'] . ')"><i class="fa fa-trash"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="editEnterExitCar(' . $res[$i]['RowID'] . ')"><i class="fa fa-edit"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        $send = array($htm, $carName);
        return $send;
    }

    public function doUpdateKilometer($myJsonString)
    {
        $acm = new acm();
        if (!$acm->hasAccess('carInformationManage')) {
            die("access denied");
            exit;
        }
        $countJS = count($myJsonString);
        $flag = true;
        $db = new DBi();

        $nowDate = date('Y-m-d');
        mysqli_autocommit($db->Getcon(), FALSE);
        for ($i = 0; $i < $countJS; $i++) {
            $newKM = $myJsonString[$i][0];  // cDate
            $caid = intval($myJsonString[$i][1]);  // pid
            $sql = "UPDATE `car_info` SET `lastKilometer`='{$newKM}',`lastKilometerDate`='{$nowDate}' WHERE `RowID`={$caid}";
            $db->Query($sql);
            $res1 = $db->AffectedRows();
            $res1 = ($res1 == -1 ? 0 : 1);
            if (!intval($res1)) {
                $flag = false;
            }
        }
        if ($flag) {
            mysqli_commit($db->Getcon());
            return true;
        } else {
            mysqli_rollback($db->Getcon());
            return false;
        }
    }

    public function createConsumingMaterials($caid, $mType, $curKM, $changeDate)
    {
        $acm = new acm();
        if (!$acm->hasAccess('carInformationManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        mysqli_autocommit($db->Getcon(), FALSE);

        $mType = explode(',', $mType);
        $cnt = count($mType);
        $changeDate = $ut->jal_to_greg($changeDate);
        $flag = true;

        for ($i = 0; $i < $cnt; $i++) {
            $query = "SELECT `km` FROM `consuming_materials_km` WHERE `carID`={$caid} AND `materialID`={$mType[$i]}";
            $rst = $db->ArrayQuery($query);

            if (count($rst) > 0) {
                $nextKM = intval($rst[0]['km']) + intval($curKM);

                $sql = "INSERT INTO `consuming_materials` (`carID`,`materialID`,`previousKm`,`nextKm`,`changeDate`) VALUES ({$caid},{$mType[$i]},'{$curKM}','{$nextKM}','{$changeDate}')";;
                $res = $db->Query($sql);
                if (intval($res) <= 0) {
                    $flag = false;
                }
            } else {
                $flag = false;
            }
        }

        if ($flag) {
            mysqli_commit($db->Getcon());
            return true;
        } else {
            mysqli_rollback($db->Getcon());
            $res = "ماده/مواد مصرفی تعریف نشده است !";
            $out = "false";
            response($res, $out);
            exit;
        }
    }

    public function getConsumingMaterialsHTM($caid)
    {
        $ut = new Utility();
        $db = new DBi();

        $query = "SELECT `carName` FROM `car_info` WHERE `RowID`={$caid}";
        $rst = $db->ArrayQuery($query);
        $carName = 'موارد تعویض شده (' . $rst[0]['carName'] . ')';

        $sql = "SELECT `consuming_materials`.*,`carName`,`plaque` FROM `consuming_materials` INNER JOIN `car_info` ON (`car_info`.`RowID`=`consuming_materials`.`carID`) WHERE `carID`={$caid} ORDER BY `consuming_materials`.`RowID` DESC";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="consumingMaterials-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">نام ماشین</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">شماره پلاک</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">ماده مصرفی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">کیلومتر فعلی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">کیلومتر بعدی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">تاریخ تعویض</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i = 0; $i < $cnt; $i++) {
            switch ($res[$i]['materialID']) {
                case 1:
                    $mType = 'روغن';
                    break;
                case 2:
                    $mType = 'فیلتر روغن';
                    break;
                case 3:
                    $mType = 'فیلتر سوخت';
                    break;
                case 4:
                    $mType = 'فیلتر هوا';
                    break;
                case 5:
                    $mType = 'روغن گیربکس';
                    break;
            }
            $changeDate = (strtotime($res[$i]['changeDate']) > 0 ? $ut->greg_to_jal($res[$i]['changeDate']) : '');
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['carName'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['plaque'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $mType . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['previousKm'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['nextKm'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $changeDate . '</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        $send = array($htm, $carName);
        return $send;
    }

    public function extraEquipmentHtm($cid)
    {
        $db = new DBi();
        $sql = "SELECT * FROM `extra_equipment` WHERE `carID`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="extraEquipmentHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">نام تجهیزات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 40%;">مشخصات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">لینک دانلود</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">حذف فایل</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $link = ADDR . 'carInfo/' . $res[$i]['fileName'];

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['name'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['description'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteExtraEquipment(' . $res[$i]['RowID'] . ')" ><i class="fas fa-trash"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function createExtraEquipment($cid, $name, $desc, $files)
    {
        $db = new DBi();
        $SFile = array();
        $allowedTypes = ['png', 'jpg', 'jpeg', 'jfif', 'pdf', 'PNG', 'JPG', 'JPEG', 'JFIF', 'PDF'];

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
                if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile[] = "equipment" . rand(0, time()) . '.' . $format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $cnt = count($SFile);
        for ($i = 0; $i < $cnt; $i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i], '../carInfo/' . $SFile[$i]);
            $sql4 = "INSERT INTO `extra_equipment` (`carID`,`name`,`fileName`,`description`) VALUES ({$cid},'{$name}','{$SFile[$i]}','{$desc}')";
            $db->Query($sql4);
        }
        return true;
    }

    public function deleteExtraEquipment($eid)
    {
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `extra_equipment` WHERE `RowID`={$eid}";
        $res = $db->ArrayQuery($sql);
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/carInfo/' . $res[0]['fileName'];
        $result = unlink($file_to_delete);
        if ($result) {
            $query = "DELETE FROM `extra_equipment` WHERE `RowID`={$eid}";
            $db->Query($query);
            return true;
        } else {
            return false;
        }
    }

    public function getExtraEquipment($caid)
    {
        $db = new DBi();

        $query = "SELECT `carName` FROM `car_info` WHERE `RowID`={$caid}";
        $rst = $db->ArrayQuery($query);
        $carName = 'تجهیزات مازاد (' . $rst[0]['carName'] . ')';

        $sql = "SELECT `name`,`fileName`,`description` FROM `extra_equipment` WHERE `carID`={$caid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="getExtraEquipment-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">نام تجهیزات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">توضیحات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $link = ADDR . 'carInfo/' . $res[$i]['fileName'];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['name'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['description'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        $send = array($htm, $carName);
        return $send;
    }

    public function carConsumingMaterials($cmid)
    {
        $db = new DBi();
        $sql = "SELECT * FROM `consuming_materials_km` WHERE `RowID`=" . $cmid;
        $res = $db->ArrayQuery($sql);
        if (count($res) == 1) {
            $res = array("cmid" => $cmid, "carID" => $res[0]['carID'], "materialID" => $res[0]['materialID'], "brand" => $res[0]['brand'], "km" => $res[0]['km']);
            return $res;
        } else {
            return false;
        }
    }

    public function getConsumingMaterialsManageList($page = 1)
    {
        $acm = new acm();
        if (!$acm->hasAccess('consumingMaterialsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page - 1) * $numRows;

        $sql = "SELECT * FROM `consuming_materials_km`";
        $sql .= " ORDER BY `RowID` DESC LIMIT $start," . $numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for ($y = 0; $y < $listCount; $y++) {
            $sql1 = "SELECT `carName`,`plaque` FROM `car_info` WHERE `RowID`={$res[$y]['carID']}";
            $res1 = $db->ArrayQuery($sql1);
            switch ($res[$y]['materialID']) {
                case 1:
                    $mType = 'روغن';
                    break;
                case 2:
                    $mType = 'فیلتر روغن';
                    break;
                case 3:
                    $mType = 'فیلتر سوخت';
                    break;
                case 4:
                    $mType = 'فیلتر هوا';
                    break;
                case 5:
                    $mType = 'روغن گیربکس';
                    break;
            }
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['carName'] = $res1[0]['carName'];
            $finalRes[$y]['plaque'] = $res1[0]['plaque'];
            $finalRes[$y]['materialID'] = $mType;
            $finalRes[$y]['brand'] = $res[$y]['brand'];
            $finalRes[$y]['km'] = $res[$y]['km'];
        }
        return $finalRes;
    }

    public function getConsumingMaterialsManageListCountRows()
    {
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `consuming_materials_km`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function consumingMaterialsCreate($carName, $type, $brand, $changeKM)
    {
        $acm = new acm();
        if (!$acm->hasAccess('consumingMaterialsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();

        $query = "SELECT `RowID` FROM `consuming_materials_km` WHERE `carID`={$carName} AND `materialID`={$type}";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0) {
            $res = "برای این خودرو، این ماده مصرفی قبلا ثبت شده است !";
            $out = "false";
            response($res, $out);
            exit;
        }

        $sql = "INSERT INTO `consuming_materials_km` (`carID`,`materialID`,`brand`,`km`) VALUES ({$carName},{$type},'{$brand}','{$changeKM}')";
        $res = $db->Query($sql);
        if (intval($res) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function consumingMaterialsEdit($cmid, $brand, $changeKM)
    {
        $acm = new acm();
        if (!$acm->hasAccess('consumingMaterialsManage')) {
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sql = "UPDATE `consuming_materials_km` SET `brand`='{$brand}',`km`='{$changeKM}' WHERE `RowID`={$cmid}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1) ? 0 : 1);
        if (intval($res)) {
            return true;
        } else {
            return false;
        }
    }

    private function getAllCars()
    {
        $db = new DBi();
        $sql = "SELECT `carName`,`RowID` FROM `car_info`";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0) {
            return $res;
        } else {
            return false;
        }
    }

    private function getAllDriver()
    {
        $db = new DBi();
        $sql = "SELECT `driverName`,`RowID` FROM `driver` where `isEnable`=1";
       // $sql = "SELECT concat(`fname`,' ',`lname`) as `driverName`,`RowID`  FROM `personnel` where `isEnable`=1";
//         $sql = "SELECT concat(p.`fname`,' ',p.`lname`) as `driverName`,p.`RowID`  FROM `personnel` as p left join
// personnel_ability as pa on (p.RowID=pa.pid) where p.`isEnable`=1 and aid in(15,16);";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0) {
            return $res;
        } else {
            return false;
        }
    }
}
