<?php

class Label{

    public function __construct(){
        // do nothing
    }

    public function getLabelManagementHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('labelManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $piece = new Piece();
        $pieces = $piece->getPieces();
        $CountPiece = count($pieces);

        $actioner = $this->getActioner();
        $CountActioner = count($actioner);

        $pagename = array();
        $pageIcon = array();
        $contentId = array();
        $menuItems = array();
        $bottons = array();
        $headerSearch = array();
        $access = array();
        $hiddenContentId[] = "hiddenLabelPrintBody";

        $x = 0;
        $y = 0;
        $z = 0;
        $manifold = 0;
        if($acm->hasAccess('labelManagement')) {
            $pagename[$x] = "مدیریت برچسب ها";
            $pageIcon[$x] = "fa-tag";
            $contentId[$x] = "labelManagementBody";
            $menuItems[$x] = 'labelManagementTabID';

            $bottons1 = array();
            $headerSearch1 = array();

            $a = 0;
            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "labelManagementLabelNumSearch";
            $headerSearch1[$a]['title'] = "کد برچسب";
            $headerSearch1[$a]['placeholder'] = "کد برچسب";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "labelManagementHPCodeSearch";
            $headerSearch1[$a]['title'] = "کد کالا";
            $headerSearch1[$a]['placeholder'] = "کد کالا";
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['width'] = "300px";
            $headerSearch1[$a]['id'] = "labelManagementPieceSearch";
            $headerSearch1[$a]['title'] = "انتخاب برچسب";
            $headerSearch1[$a]['multiple'] = "multiple";
            $headerSearch1[$a]['actionsBox'] = 1;
            $headerSearch1[$a]['options'] = array();
            for ($i=0;$i<$CountPiece;$i++){
                $headerSearch1[$a]['options'][$i]["title"] = $pieces[$i]['pName'];
                $headerSearch1[$a]['options'][$i]["value"] = $pieces[$i]['RowID'];
            }
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "labelManagementStatusSearch";
            $headerSearch1[$a]['title'] = "وضعیت";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = "همه";
            $headerSearch1[$a]['options'][0]["value"] = 0;
            $headerSearch1[$a]['options'][1]["title"] = "در حال آرشیو";
            $headerSearch1[$a]['options'][1]["value"] = 1;
            $headerSearch1[$a]['options'][2]["title"] = "تایید شده";
            $headerSearch1[$a]['options'][2]["value"] = 2;
            $headerSearch1[$a]['options'][3]["title"] = "در حال ویرایش";
            $headerSearch1[$a]['options'][3]["value"] = 3;
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['width'] = "100px";
            $headerSearch1[$a]['id'] = "labelManagementDateTypeSearch";
            $headerSearch1[$a]['onchange'] = "onchange=showLabelManagementList()";
            $headerSearch1[$a]['title'] = "نمایش تاریخ";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = "تاریخ تایید";
            $headerSearch1[$a]['options'][0]["value"] = 0;
            $headerSearch1[$a]['options'][1]["title"] = "تاریخ تغییر";
            $headerSearch1[$a]['options'][1]["value"] = 1;
            $a++;

            $headerSearch1[$a]['type'] = "btn";
            $headerSearch1[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch1[$a]['jsf'] = "showLabelManagementList";

            $b = 0;
            if($acm->hasAccess('editCreateLabel')) {
                $bottons1[$b]['title'] = "افزودن";
                $bottons1[$b]['jsf'] = "createLabel";
                $bottons1[$b]['icon'] = "fa-plus-square";
                $b++;

                $bottons1[$b]['title'] = "ویرایش";
                $bottons1[$b]['jsf'] = "editLabel";
                $bottons1[$b]['icon'] = "fa-edit";
                $b++;
            }
            if($acm->hasAccess('attachFileToLabel')) {
                $bottons1[$b]['title'] = "پیوست فایل";
                $bottons1[$b]['jsf'] = "attachFileToLabel";
                $bottons1[$b]['icon'] = "fa-link";
                $b++;
               
            }
            if($acm->hasAccess('labelConfirmationSendSMS')) {
                $bottons1[$b]['title'] = "ارسال پیامک";
                $bottons1[$b]['jsf'] = "sendSMSForLabelConfirmation";
                $bottons1[$b]['icon'] = "fa-paper-plane";
            }
            if($acm->hasAccess('labelExcelReport')) {
                $bottons1[$b]['title'] = " گزارش اکسل";
                $bottons1[$b]['jsf'] = "labelExcelReport";
                $bottons1[$b]['icon'] = "fa-file-excel";
                $b++;
            }

            $bottons[$y] = $bottons1;
            $headerSearch[$z] = $headerSearch1;

            $manifold++;
            $access[] = 1;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('labelRequestManagement')) {
            $pagename[$x] = "درخواست برچسب";
            $pageIcon[$x] = "fa-list";
            $contentId[$x] = "labelRequestManagementBody";
            $menuItems[$x] = 'labelRequestManagementTabID';

            $bottons2 = array();
            $headerSearch2 = array();

            $a = 0;
            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "150px";
            $headerSearch2[$a]['id'] = "labelRequestManagementNameSearch";
            $headerSearch2[$a]['title'] = "نام درخواست";
            $headerSearch2[$a]['placeholder'] = "نام درخواست";
            $a++;

            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "150px";
            $headerSearch2[$a]['id'] = "labelRequestManagementNDateSearch";
            $headerSearch2[$a]['title'] = "تاریخ نیاز";
            $headerSearch2[$a]['placeholder'] = "تاریخ نیاز";
            $a++;

            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "120px";
            $headerSearch2[$a]['id'] = "labelRequestManagementBNumSearch";
            $headerSearch2[$a]['title'] = "شماره درخواست خرید";
            $headerSearch2[$a]['placeholder'] = "شماره درخواست خرید";
            $a++;

            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['width'] = "100px";
            $headerSearch2[$a]['id'] = "labelRequestManagementStatusSearch";
            $headerSearch2[$a]['title'] = "وضعیت";
            $headerSearch2[$a]['options'] = array();
            $headerSearch2[$a]['options'][0]["title"] = "همه";
            $headerSearch2[$a]['options'][0]["value"] = -1;
            $headerSearch2[$a]['options'][1]["title"] = "تایید شده";
            $headerSearch2[$a]['options'][1]["value"] = 1;
            $headerSearch2[$a]['options'][2]["title"] = "تایید نشده";
            $headerSearch2[$a]['options'][2]["value"] = 0;
            $a++;

            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['width'] = "100px";
            $headerSearch2[$a]['id'] = "labelRequestManagementCloseTypeSearch";
            $headerSearch2[$a]['title'] = "وضعیت";
            $headerSearch2[$a]['options'] = array();
            $headerSearch2[$a]['options'][0]["title"] = "جاری";
            $headerSearch2[$a]['options'][0]["value"] = 1;
            $headerSearch2[$a]['options'][1]["title"] = "بسته شده";
            $headerSearch2[$a]['options'][1]["value"] = 0;
            $a++;

            $headerSearch2[$a]['type'] = "select";
            $headerSearch2[$a]['id'] = "labelRequestManagementPieceIDSearch";
            $headerSearch2[$a]['multiple'] = "multiple";
            $headerSearch2[$a]['actionsBox'] = 1;
            $headerSearch2[$a]['LimitNumSelections'] = 1;
            $headerSearch2[$a]['title'] = "نام برچسب";
            $headerSearch2[$a]['options'] = array();
            for ($i=0;$i<$CountPiece;$i++){
                $headerSearch2[$a]['options'][$i]["title"] = $pieces[$i]['pName'];
                $headerSearch2[$a]['options'][$i]["value"] = $pieces[$i]['RowID'];
            }
            $a++;

            $headerSearch2[$a]['type'] = "btn";
            $headerSearch2[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch2[$a]['jsf'] = "showLabelRequestList";

            $b = 0;
            if($acm->hasAccess('editCreateLabelRequest')) {
                $bottons2[$b]['title'] = "ثبت";
                $bottons2[$b]['jsf'] = "createLabelRequest";
                $bottons2[$b]['icon'] = "fa-plus-square";
                $b++;

                $bottons2[$b]['title'] = "ویرایش";
                $bottons2[$b]['jsf'] = "editLabelRequest";
                $bottons2[$b]['icon'] = "fa-edit";
                $b++;

                $bottons2[$b]['title'] = "حذف";
                $bottons2[$b]['jsf'] = "deleteLabelRequest";
                $bottons2[$b]['icon'] = "fa-minus-square";
                $b++;
            }
            if($acm->hasAccess('attachFileToLabelRequest')) {
                $bottons2[$b]['title'] = "پیوست فایل";
                $bottons2[$b]['jsf'] = "attachFileToLabelRequest";
                $bottons2[$b]['icon'] = "fa-link";
                $b++;
            }
            if($acm->hasAccess('closedLabelRequest')) {
                $bottons2[$b]['title'] = "بستن درخواست";
                $bottons2[$b]['jsf'] = "closedLabelRequest";
                $bottons2[$b]['icon'] = "fa-ban";
            }

            $bottons[$y] = $bottons2;
            $headerSearch[$z] = $headerSearch2;

            $manifold++;
            $access[] = 2;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('renderingRequestManage')) {
            $pagename[$x] = "درخواست داده مهندسی";
            $pageIcon[$x] = "fa-outdent";
            $contentId[$x] = "renderingRequestManageBody";
            $menuItems[$x] = 'renderingRequestManageTabID';

            $bottons3 = array();
            $headerSearch3 = array();

            $a = 0;
            $headerSearch3[$a]['type'] = "select";
            $headerSearch3[$a]['width'] = "300px";
            $headerSearch3[$a]['id'] = "renderingRequestPieceSearch";
            $headerSearch3[$a]['title'] = "انتخاب برچسب";
            $headerSearch3[$a]['multiple'] = "multiple";
            $headerSearch3[$a]['LimitNumSelections'] = 1;
            $headerSearch3[$a]['options'] = array();
            for ($i=0;$i<$CountPiece;$i++){
                $headerSearch3[$a]['options'][$i]["title"] = $pieces[$i]['pName'];
                $headerSearch3[$a]['options'][$i]["value"] = $pieces[$i]['RowID'];
            }
            $a++;

            $headerSearch3[$a]['type'] = "btn";
            $headerSearch3[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch3[$a]['jsf'] = "showRenderingRequestList";

            $b = 0;
            if($acm->hasAccess('editCreateRenderingRequest')) {
                $bottons3[$b]['title'] = "ثبت";
                $bottons3[$b]['jsf'] = "createRenderingRequest";
                $bottons3[$b]['icon'] = "fa-plus-square";
                $b++;

                $bottons3[$b]['title'] = "ویرایش";
                $bottons3[$b]['jsf'] = "editRenderingRequest";
                $bottons3[$b]['icon'] = "fa-edit";
                $b++;
            }
            if($acm->hasAccess('attachFileToRenderingRequest')) {
                $bottons3[$b]['title'] = "پیوست فایل";
                $bottons3[$b]['jsf'] = "attachFileToRenderingRequest";
                $bottons3[$b]['icon'] = "fa-link";
            }

            $bottons[$y] = $bottons3;
            $headerSearch[$z] = $headerSearch3;

            $manifold++;
            $access[] = 3;
        }
        $htm = $ut->getHtmlOfDefaultManagementMultiPage($pagename,$pageIcon,$contentId,$menuItems,$bottons,$manifold,$headerSearch,$hiddenContentId);
        //++++++++++++++++++++++++++++++++++ EDIT CREATE Label MODAL ++++++++++++++++++++++++++++++++
        $modalID = "labelManagementModal";
        $modalTitle = "فرم ایجاد/ویرایش برچسب";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "labelManagementPiece";
        $items[$c]['title'] = "انتخاب برچسب";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['LimitNumSelections'] = 1;
        $items[$c]['onchange'] = "onchange=getLabelPieceCode()";
        $items[$c]['options'] = array();
        for ($i=0;$i<$CountPiece;$i++){
            $items[$c]['options'][$i]["title"] = $pieces[$i]['pName'];
            $items[$c]['options'][$i]["value"] = $pieces[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "labelManagementPieceCode";
        $items[$c]['onchange'] = "onchange=getLabelPieceName()";
        $items[$c]['title'] = "کد برچسب";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "labelManagementPrintFormatting";
        $items[$c]['title'] = "فرمت چاپ";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = 'تک رو';
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = 'دو رو';
        $items[$c]['options'][2]["value"] = 2;
        $items[$c]['options'][3]["title"] = 'چند برگی';
        $items[$c]['options'][3]["value"] = 3;
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "labelManagementHiddenLid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateLabel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateLabelModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE Label MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++ Label Add Attachment File Modal ++++++++++++++++++++++
        $modalID = "attachmentFileToLabelModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'attachmentFileToLabel-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "file";
        $items[$c]['id'] = "attachmentFileToLabelFile";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید TIF, JPG, JPEG, PNG, CDR, Ai, PDF باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "attachmentFileToLabelID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachFileToLabel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $attachmentFileToLabel = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Label Add Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Label Attachment File Modal ++++++++++++++++++++++
        $modalID = "labelAttachmentFileModal";
        $modalTitle = "دانلود فایل های پیوست";
        $ShowDescription = 'labelAttachmentFile-body';
        $style = 'style="max-width: 655px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $labelAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Label Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Final approval MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "confirmationLabelModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "confirmationLabelIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doConfirmationLabel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $finalApprovalModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF Final approval MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ change request Label MODAL ++++++++++++++++++++++++++++++++
        $modalID = "changeRequestLabelModal";
        $modalTitle = "فرم درخواست تغییر برچسب";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "changeRequestLabelDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "changeRequestLabelHiddenLid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doChangeRequestLabel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $changeRequestLabelModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF change request Label MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ send SMS For Label Confirmation MODAL ++++++++++++++++++++++++++++++++
        $modalID = "sendSMSForLabelConfirmationModal";
        $modalTitle = "ارسال پیامک";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "sendSMSForLabelConfirmationType";
        $items[$c]['title'] = "انتخاب بخش";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = 'تاییدیه برچسب';
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = 'درخواست داده مهندسی';
        $items[$c]['options'][2]["value"] = 2;
        $items[$c]['options'][3]["title"] = 'چاپ زینک';
        $items[$c]['options'][3]["value"] = 3;

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doSendSMSForLabelConfirmation";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $sendSMSForLabelConfirmationModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF send SMS For Label Confirmation MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ EDIT CREATE Label MODAL ++++++++++++++++++++++++++++++++
        $modalID = "labelRequestManagementModal";
        $modalTitle = "فرم ثبت/ویرایش درخواست برچسب";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "labelRequestManagementNDate";
        $items[$c]['style'] = "style='width: 80%;float: right;'";
        $items[$c]['title'] = "تاریخ نیاز";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "labelRequestManagementBNumber";
        $items[$c]['style'] = "style='width: 80%;'";
        $items[$c]['title'] = "شماره درخواست خرید";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "labelRequestManagementDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "labelRequestManagementHiddenLRid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateLabelRequest";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateLabelRequestModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE Label MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ DELETE MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "deleteLabelRequestModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به حذف این درخواست مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "deleteLabelRequestModalIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "dodeleteLabelRequest";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $delModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF DELETE MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++ Label Request Add Attachment File Modal ++++++++++++++++++++++
        $modalID = "attachmentFileToLabelRequestModal";
        $modalTitle = "پیوست فایل";
        $style = 'style="max-width: 851px;"';
        $ShowDescription = 'attachmentFileToLabelRequest-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "file";
        $items[$c]['id'] = "attachmentFileToLabelRequestFile";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید zip باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "attachmentFileToLabelRequestDimension";
        $items[$c]['title'] = "ابعاد";
        $items[$c]['placeholder'] = "سانتی متر";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "attachmentFileToLabelRequestDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "attachmentFileToLabelRequestID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachFileToLabelRequest";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $attachmentFileToLabelRequest = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End LabelRequest Add Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ confirmation Attach Label Request Zinc MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "confirmationAttachLabelRequestZincModal";
        $modalTitle = "تاییدیه چاپ برچسب ها";
        $style = 'style="max-width: 651px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "confirmationAttachLabelRequestActioner";
        $items[$c]['title'] = "شخص اقدام کننده چاپ";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$CountActioner;$i++){
            $items[$c]['options'][$i+1]["title"] = $actioner[$i]['fname'].' '.$actioner[$i]['lname'];
            $items[$c]['options'][$i+1]["value"] = $actioner[$i]['uid'];
        }
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "confirmationAttachLabelRequestDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "confirmationAttachLabelRequestZincIdHidden";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doConfirmationAttachLabelRequestFile";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $confirmationAttachLabelRequestZinc = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++END OF confirmation Attach Label Request Zinc MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ finisher Attach Label Request File MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "finisherAttachLabelRequestModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "finisherAttachLabelRequestFileIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doFinisherAttachLabelRequestFile";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $finisherAttachLabelRequestModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++ END OF finisher Attach Label Request File MODAL ++++++++++++++++++++++++++++
        //++++++++++++++++++ Label Request Attachment File Modal ++++++++++++++++++++++
        $modalID = "labelRequestAttachmentFileModal";
        $modalTitle = "دانلود فایل های پیوست";
        $style = 'style="max-width: 1340px;"';
        $ShowDescription = 'labelRequestAttachmentFile-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $labelRequestAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Label Request Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Final approval MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "confirmationLabelRequestModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "confirmationLabelRequestIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doConfirmationLabelRequest";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $finalApprovalLabelRequestModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++ END OF Final approval MODAL ++++++++++++++++++++++++++++
        //++++++++++++++++++ Label Request Details Modal ++++++++++++++++++++++
        $modalID = "labelRequestDetailsModal";
        $modalTitle = "ثبت جزئیات درخواست برچسب";
        $ShowDescription = 'labelRequestDetailsModal-body';
        $style = 'style="max-width: 851px;"';
        $c = 0;

        $items = array();
        if($acm->hasAccess('createLabelRequestDetails')) {
            $items[$c]['type'] = "select";
            $items[$c]['id'] = "labelRequestDetailsLid";
            $items[$c]['title'] = "انتخاب برچسب";
            $items[$c]['multiple'] = "multiple";
            $items[$c]['onchange'] = "onchange=getLabelHPCode()";
            $items[$c]['LimitNumSelections'] = 1;
            $items[$c]['options'] = array();
            for ($i = 0; $i < $CountPiece; $i++) {
                $items[$c]['options'][$i]["title"] = $pieces[$i]['pName'];
                $items[$c]['options'][$i]["value"] = $pieces[$i]['RowID'];
            }
            $c++;

            $items[$c]['type'] = "text";
            $items[$c]['id'] = "labelRequestDetailsHPCode";
            $items[$c]['style'] = "style='width: 72%;'";
            $items[$c]['onchange'] = "onchange=getLabelName()";
            $items[$c]['title'] = "کد کالا";
            $items[$c]['placeholder'] = "کد";
            $c++;

            $items[$c]['type'] = "text";
            $items[$c]['id'] = "labelRequestDetailsNumber";
            $items[$c]['style'] = "style='width: 72%;'";
            $items[$c]['title'] = "تعداد";
            $items[$c]['placeholder'] = "تعداد";
            $c++;
        }
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "labelRequestDetailsIdHidden";

        $topperBottons = array();
        $x = 0;
        if($acm->hasAccess('createLabelRequestDetails')) {
            $topperBottons[$x]['title'] = "تایید";
            $topperBottons[$x]['jsf'] = "doCreateLabelRequestDetails";
            $topperBottons[$x]['type'] = "btn";
            $topperBottons[$x]['data-dismiss'] = "NO";
            $x++;
        }

        $topperBottons[$x]['title'] = "خروجی اکسل";
        $topperBottons[$x]['jsf'] = "doCreateLabelRequestExcel";
        $topperBottons[$x]['type'] = "btn-success";
        $x++;

        $topperBottons[$x]['title'] = "بستن";
        $topperBottons[$x]['type'] = "dismis";
        $labelRequestDetails = $ut->getHtmlModal($modalID,$modalTitle,$items,array(),'','',$style,$ShowDescription,'','',$topperBottons);
        //+++++++++++++++++ End Label Request Details Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ closed Label Request MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "closedLabelRequestModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "closedLabelRequestIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doClosedLabelRequest";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $closedLabelRequestModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++ END OF closed Label Request MODAL ++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ EDIT CREATE Render Request MODAL ++++++++++++++++++++++++++++++++
        $modalID = "renderingRequestManageModal";
        $modalTitle = "فرم ایجاد/ویرایش درخواست داده مهندسی";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "renderingRequestManageRequestType";
        $items[$c]['style'] = "style='width: 220px;' ";
        $items[$c]['title'] = "نوع داده";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = 'برچسب';
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = 'کارتن';
        $items[$c]['options'][2]["value"] = 1;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "renderingRequestManagePiece";
        $items[$c]['onchange'] = "onchange=getLabelPieceCodeWithName()";
        $items[$c]['title'] = "انتخاب قطعه";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['LimitNumSelections'] = 1;
        $items[$c]['options'] = array();
        for ($i=0;$i<$CountPiece;$i++){
            $items[$c]['options'][$i]["title"] = $pieces[$i]['pName'];
            $items[$c]['options'][$i]["value"] = $pieces[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "renderingRequestManagePCode";
        $items[$c]['onchange'] = "onchange=getLabelPieceNameWithCode()";
        $items[$c]['title'] = "کد قطعه";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "renderingRequestManageDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "renderingRequestManageHiddenRid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateRenderingRequest";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateRenderingRequest = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE Render Request MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ check Rendering Request MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "checkRenderingRequestModal";
        $modalTitle = "هشدار";
        $modalTxt = "این مورد قبلا ثبت شده است، آیا نسبت به ثبت مجدد مطمئن هستید؟ ";
        $items = array();

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateRenderingRequest1";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $checkRenderingRequestModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF check Rendering Request MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++ Rendering Request Add Attachment File Modal ++++++++++++++++++++++
        $modalID = "attachmentFileToRenderingRequestModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'attachmentFileToRenderingRequest-body';
        //$style = 'style="max-width: 651px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "file";
        $items[$c]['id'] = "attachmentFileToRenderingRequestFile";
        $items[$c]['title'] = "بارگذاری فایل render";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید png باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "attachmentFileToRenderingRequestThicness";
        $items[$c]['title'] = "min Thicness";
        $items[$c]['placeholder'] = "min Thicness";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "attachmentFileToRenderingRequestSize";
        $items[$c]['title'] = "سایز";
        $items[$c]['placeholder'] = "سایز";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "attachmentFileToRenderingRequestLatin";
        $items[$c]['title'] = "نام لاتین";
        $items[$c]['placeholder'] = "نام لاتین";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "attachmentFileToRenderingRequestMapFile";
        $items[$c]['title'] = "بارگذاری فایل های نقشه";
        $items[$c]['name'] = 'name="filesm[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "attachmentFileToRenderingRequestCartoonSize";
        $items[$c]['style'] = "style='width: 122%;'";
        $items[$c]['title'] = "ابعاد گسترده کارتن";
        $items[$c]['placeholder'] = "میلیمتر";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "renderingRequestManageLabels";
        $items[$c]['title'] = "برچسب هایی که بر روی کارتن نصب می شود";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['options'] = array();
        for ($i=0;$i<$CountPiece;$i++){
            $items[$c]['options'][$i]["title"] = $pieces[$i]['pName'];
            $items[$c]['options'][$i]["value"] = $pieces[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "attachmentFileToRenderingRequestID";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "attachmentFileToRenderingRequestType";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachFileToRenderingRequest";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $attachmentFileToRenderingRequest = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Rendering Request Add Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Rendering Request Attachment File Modal ++++++++++++++++++++++
        $modalID = "renderingRequestAttachmentFileModal";
        $modalTitle = "دانلود فایل های پیوست";
        $ShowDescription = 'renderingRequestAttachmentFile-body';
        $style = 'style="max-width: 600px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $renderingRequestAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Rendering Request Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Rendering Request Attachment File Info Modal ++++++++++++++++++++++
        $modalID = "infoRenderingRequestAttachmentFileModal";
        $modalTitle = "دانلود فایل های پیوست";
        $ShowDescription = 'infoRenderingRequestAttachmentFile-body';
        $style = 'style="max-width: 600px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $infoRenderingRequestAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Rendering Request Attachment File Info Modal ++++++++++++++++++++++++
        $htm .= $editCreateLabelModal;
        $htm .= $attachmentFileToLabel;
        $htm .= $labelAttachmentFile;
        $htm .= $finalApprovalModal;
        $htm .= $changeRequestLabelModal;
        $htm .= $sendSMSForLabelConfirmationModal;
        $htm .= $editCreateLabelRequestModal;
        $htm .= $delModal;
        $htm .= $attachmentFileToLabelRequest;
        $htm .= $labelRequestAttachmentFile;
        $htm .= $confirmationAttachLabelRequestZinc;
        $htm .= $finisherAttachLabelRequestModal;
        $htm .= $finalApprovalLabelRequestModal;
        $htm .= $labelRequestDetails;
        $htm .= $closedLabelRequestModal;
        $htm .= $editCreateRenderingRequest;
        $htm .= $checkRenderingRequestModal;
        $htm .= $attachmentFileToRenderingRequest;
        $htm .= $renderingRequestAttachmentFile;
        $htm .= $infoRenderingRequestAttachmentFile;
        $send = array($htm,$access);
        return $send;
    }

    public function getLabelManagementList($labelNum,$hpCode,$piece,$status,$page=1,$format="view"){
        $acm = new acm();
        if(!$acm->hasAccess('labelManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($labelNum)) > 0){
            $w[] = '`labelNum` LIKE "%'.$labelNum.'%" ';
        }
        if(strlen(trim($hpCode)) > 0){
            $w[] = '`HPCode` LIKE "%'.$hpCode.'%" ';
        }
        if(intval($piece) > 0){
            $w[] = '`pieceID` IN('.$piece.') ';
        }
        if(intval($status) > 0){
            $w[] = '`status`='.$status.' ';
        }

        $sql = "SELECT `label`.*,`pName` FROM `label` LEFT JOIN `piece` ON (`label`.`pieceID`=`piece`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC ";
        if($format=="view"){
            $sql .= "  LIMIT $start,".$numRows;
        }
      
        $res = $db->ArrayQuery($sql);

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['index'] = $y+1;
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['HPCode'] = $res[$y]['HPCode'];
            $finalRes[$y]['labelNum'] = $res[$y]['labelNum'];
            $finalRes[$y]['pName'] = $res[$y]['pName'];
            $finalRes[$y]['statusDate'] = (strtotime($res[$y]['statusDate']) > 0 ? $ut->greg_to_jal($res[$y]['statusDate']) : '');
            $finalRes[$y]['changeDate'] = (strtotime($res[$y]['changeDate']) > 0 ? $ut->greg_to_jal($res[$y]['changeDate']) : '');

            switch (intval($res[$y]['printFormat'])){
                case 1;
                    $finalRes[$y]['printFormat'] = 'تک رو';
                    break;
                case 2;
                    $finalRes[$y]['printFormat'] = 'دو رو';
                    break;
                case 3;
                    $finalRes[$y]['printFormat'] = 'چند برگی';
                    break;
            }

            switch (intval($res[$y]['status'])){
                case 1;
                    $finalRes[$y]['status'] = 'در حال آرشیو';
                    $finalRes[$y]['bgColor'] = 'table-warning';
                    break;
                case 2;
                    $finalRes[$y]['status'] = 'تایید شده';
                    $finalRes[$y]['bgColor'] = 'table-success';
                    break;
                case 3;
                    $finalRes[$y]['status'] = 'در حال ویرایش';
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
            }

            $query1 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$y]['uid']}";
            $rst1 = $db->ArrayQuery($query1);
            $finalRes[$y]['uid'] = $rst1[0]['fname'].' '.$rst1[0]['lname'];

            $query2 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$y]['tid']}";
            $rst2 = $db->ArrayQuery($query2);
            $finalRes[$y]['tid'] = $rst2[0]['fname'].' '.$rst2[0]['lname'];
        }
        return $finalRes;
    }

    public function getLabelManagementListCountRows($labelNum,$hpCode,$piece,$status){
        $db = new DBi();
        $w = array();
        if(strlen(trim($labelNum)) > 0){
            $w[] = '`labelNum` LIKE "%'.$labelNum.'%" ';
        }
        if(strlen(trim($hpCode)) > 0){
            $w[] = '`HPCode` LIKE "%'.$hpCode.'%" ';
        }
        if(intval($piece) > 0){
            $w[] = '`pieceID` IN('.$piece.') ';
        }
        if(intval($status) > 0){
            $w[] = '`status`='.$status.' ';
        }

        $sql = "SELECT `RowID` FROM `label`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getLabelPieceCode($piece){
        $db = new DBi();
        $sql = "SELECT `pCode` FROM `piece` WHERE `RowID`={$piece}";
        return $db->ArrayQuery($sql);
    }

    public function getLabelPieceName($pCode){
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `piece` WHERE `pCode`='{$pCode}'";
        return $db->ArrayQuery($sql);
    }

    public function createLabel($piece,$pFormat){
        $acm = new acm();
        if(!$acm->hasAccess('editCreateLabel')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $query = "SELECT `HPCode`,`pCode` FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`) WHERE `piece`.`RowID`={$piece}";
        $rst = $db->ArrayQuery($query);

        $sql = "INSERT INTO `label` (`pieceID`,`HPCode`,`labelNum`,`uid`,`printFormat`) VALUES ({$piece},'{$rst[0]['HPCode']}','{$rst[0]['pCode']}',{$_SESSION['userid']},{$pFormat})";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editLabel($lid,$piece,$pFormat){
        $acm = new acm();
        if(!$acm->hasAccess('editCreateLabel')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sqq = "SELECT `status` FROM `label` WHERE `RowID`={$lid}";
        $rsq = $db->ArrayQuery($sqq);
        if (intval($rsq[0]['status']) == 2){
            $res = "موارد تایید شده قابل ویرایش نمی باشند !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $query = "SELECT `HPCode`,`pCode` FROM `piece` INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`) WHERE `piece`.`RowID`={$piece}";
        $rst = $db->ArrayQuery($query);

        $sql1 = "UPDATE `label` SET `pieceID`={$piece},`HPCode`='{$rst[0]['HPCode']}',`labelNum`='{$rst[0]['pCode']}',`uid`={$_SESSION['userid']},`status`=1,`description`='',`tid`=0,`printFormat`={$pFormat},`statusDate`='' WHERE `RowID`={$lid}";
        $db->Query($sql1);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }


    public function attachedLabelFileHtm($lid){
        $db = new DBi();
        $acm = new acm();
       // $sql = "SELECT `RowID`,`format`,`fileName` FROM `label_attachment` WHERE `labelID`={$lid}";
        $sql = "SELECT la.`RowID`,la.`format`,la.`fileName`,l.`statusDate`,la.`createDate`,l.status,l.RowID as label_id FROM `label_attachment` as la LEFT JOIN label as l on(la.`labelID`=l.`RowID`) WHERE la.`labelID`={$lid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        if (strlen(trim($res[0]['fileName'])) > 0) {
         
            $htm .= '<table class="table table-bordered table-hover table-sm" id="attachedLabelFileHtm-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">فرمت</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">لینک دانلود</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">حذف فایل</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $iterator = 0;
            for ($i=0;$i<$cnt;$i++) {
                $statusDate = $res[$i]['statusDate'];
             
                $iterator++;
               // $link = ADDR . 'label/' . $res[$i]['fileName'];
                $confirmed=0;
                $class="";

               if(strtotime($statusDate)<=strtotime($res[$i]['createDate'])){
                    if($res[$i]['status']==2){
                        $newDate=date("Y-m-d",strtotime($res[$i]['createDate'].' + 1 days'));
                        $sql="UPDATE label SET statusDate='{$newDate}' WHERE RowID={$res[$i]['label_id']}";
                        $db->Query($sql);
                        $confirmed=1;
                        $class="table-success";
                    }
                    else{
                        $class="table-warning";
                    }
                }
                else
                {
                    $confirmed=1;
                    $class="table-success";
                }
                $htm .= '<tr class="'.$class.'">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['format'] . '</td>';
               // $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" onclick="download_label(\''.$res[$i]['fileName'].'\',this)" download target="_blank"><i class="fas fa-download"></i></a></td>';
                if($confirmed==1){
                    if(!$acm->hasAccess('delete_confirmed_label')){
                        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" disabled  ><i class="fas fa-trash"></i></button></td>';
                    }
                    else{
                        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachLabelFile(' . $res[$i]['RowID'] . ')" ><i class="fas fa-trash"></i></button></td>';
                    }
                }
                else{
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachLabelFile(' . $res[$i]['RowID'] . ')" ><i class="fas fa-trash"></i></button></td>';
                }
              
                $htm .= '</tr>';
            }

            $htm .= '</tbody>';
            $htm .= '</table>';
        }
        return $htm;
    }

    public function attachFileToLabel($lid,$files){
        $db = new DBi();
        $ut=new Utility();
        $ftp=new FTP();
       
        $sqq = "SELECT `status` FROM `label` WHERE `RowID`={$lid}";
        $rsq = $db->ArrayQuery($sqq);
        if (intval($rsq[0]['status']) == 2){
            $res = "امکان افزودن فایل به موارد تایید شده نمی باشد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql1 = "SELECT COUNT(`RowID`) AS `crid` FROM `label_attachment` WHERE `labelID`={$lid}";
        $res1 = $db->ArrayQuery($sql1);
        // if (intval($res1[0]['crid']) > 4){
        //     $res = "بیشتر از چهار فایل امکان آپلود ندارد !";
        //     $out = "false";
        //     response($res,$out);
        //     exit;
        // }

        $query = "UPDATE `label` SET `status`=1,`description`='',`tid`=0 WHERE `RowID`={$lid}";
        $db->Query($query);

        $SFile = array();
        $SFormat = array();
        $allowedTypes = ['tif','jpg','jpeg','png','pdf','cdr','ai','TIF','JPG','JPEG','PNG','PDF','CDR','AI'];
        if (isset($files) && !empty($files)) {
            $no_files = count($files['name']);
            $nowDate = date('Y-m-d');
            $nowTime = date('H:i:s');
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
                $SFile[] = "label" . rand(0, time()).'.'.$format;
                $SFormat[] = $format;
                $upload_res=$ftp->Upload($SFile[$i],$files["tmp_name"][$i],$nowDate,$format);
         
                if($upload_res){
                   
                    $sql4 = "INSERT INTO `label_attachment` (`labelID`,`fileName`,`createDate`,`createTime`,`uid`,`format`) VALUES ({$lid},'{$SFile[$i]}','{$nowDate}','{$nowTime}',{$_SESSION['userid']},'{$SFormat[$i]}')";
                    $db->Query($sql4);
                }

            } // for()
        } //  if (isset($files) && !empty($files))

        // $nowDate = date('Y-m-d');
        // $nowTime = date('H:i:s');
        // $cnt = count($SFile);
        // for ($i=0;$i<$cnt;$i++) {
        //    // $upload = move_uploaded_file($files["tmp_name"][$i],'../label/'.$SFile[$i]);
        //    // $upload = move_uploaded_file($files["tmp_name"][$i],'../temp_label_upload/'.$SFile[$i]);
           
           
        // }
      //  $ut->fileRecorder('finish');
        return true;
    }

    public function deleteAttachLabelFile($lid,$laid){
        $db = new DBi();
        $ftp=new FTP();

        $sqq = "SELECT `status` FROM `label` WHERE `RowID`={$lid}";
        $rsq = $db->ArrayQuery($sqq);
        if (intval($rsq[0]['status']) == 2){
            $res = "امکان حذف فایل از موارد تایید شده نمی باشد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "SELECT `fileName` FROM `label_attachment` WHERE `RowID`={$laid}";
        $res = $db->ArrayQuery($sql);
       // $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/label/' . $res[0]['fileName'];
        $file_to_delete = $res[0]['fileName'];
       // $result = unlink($file_to_delete);
       $ftp_del_res=$ftp->Delete($file_to_delete);
        if ($ftp_del_res) {
            $query = "DELETE FROM `label_attachment` WHERE `RowID`={$laid}";
            $db->Query($query);
            return true;
        }else{
            return false;
        }
    }

    public function attachmentFileLabelHtm($lid){
        $acm = new acm();
        $db = new DBi();
        $ut = new Utility();
        $ftp=new FTP();

        if( ($acm->hasAccess('labelConfirmation') && intval($_SESSION['userid']) !== 1) || ($acm->hasAccess('labelViewer') && intval($_SESSION['userid']) !== 1) ){
            $sql = "SELECT `fileName`,`createDate`,`createTime`,`format`,`fname`,`lname` FROM `label_attachment` LEFT JOIN `users` ON (`label_attachment`.`uid`=`users`.`RowID`) WHERE `labelID`={$lid} AND (`format`='jpg' OR `format`='jpeg' OR `format`='JPG' OR `format`='JPEG')";
        }else{
            $sql = "SELECT `fileName`,`createDate`,`createTime`,`format`,`fname`,`lname` FROM `label_attachment` LEFT JOIN `users` ON (`label_attachment`.`uid`=`users`.`RowID`) WHERE `labelID`={$lid}";
        }
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileLabelHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">ثبت کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">زمان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">فرمت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $iterator = 0;
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);
            //$link = ADDR.'label/'.$res[$i]['fileName'];
           // $link = $ftp->Download($res[$i]['fileName'],true);
            
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createDate'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['format'].'</td>';
            //$htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" onclick="download_label(\''.$res[$i]['fileName'].'\',this)" download target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';

        return $htm;
    }

    public function confirmationLabel($lid){
        $db = new DBi();

        $sqq = "SELECT `status` FROM `label` WHERE `RowID`={$lid}";
        $rsq = $db->ArrayQuery($sqq);
        if (intval($rsq[0]['status']) == 3){
            $res = "برچسب های در حال ویرایش، قابل تایید نمی باشند !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $nowDate = date('Y-m-d H:i:s');
        $sql = "UPDATE `label` SET `status`=2,`tid`={$_SESSION['userid']},`statusDate`='{$nowDate}' WHERE `RowID`={$lid}";

        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            $created_date = date('Y-m-d', strtotime($nowDate. ' - 1 days'));//  تغییر  تاریخ ایجاد فایل بدلیل تداخل زمانی در هنگامی که تاریخ  تایید و تاریخ  آپلود قایل جدید  به صورت چند باره در یک روز تکرار گردند
            $file_sql="UPDATE label_attachment SET createDate='{$created_date}' WHERE  labelID={$lid}";

            $db->Query($file_sql);
            $aff2 = $db->AffectedRows();
            $aff2 = (($aff2 == -1 || $aff2 == 0) ? 0 : 1);
            if(intval($aff2)>0){
                return true;
            }
            else{
                return false;
            }

        }else{
            return false;
        }
    }

    public function changeRequestLabelDesc($lid){
        $db = new DBi();
        $sql = "SELECT `description` FROM `label` WHERE `RowID`=".$lid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("description"=>$res[0]['description']);
            return $res;
        }else{
            return false;
        }
    }

    public function changeRequestLabel($lid,$desc){
        $db = new DBi();

        $nowDate = date('Y-m-d');
        $sql = "UPDATE `label` SET `status`=3,`description`='{$desc}',`changeDate`='{$nowDate}',`tid`=0 WHERE `RowID`={$lid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function labelInfo($lid){
        $db = new DBi();
        $sql = "SELECT `pieceID`,`printFormat`,`pCode` FROM `label` INNER JOIN `piece` ON (`label`.`pieceID`=`piece`.`RowID`) WHERE `label`.`RowID`=".$lid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("lid"=>$lid,"pieceID"=>$res[0]['pieceID'],"printFormat"=>$res[0]['printFormat'],"pCode"=>$res[0]['pCode']);
            return $res;
        }else{
            return false;
        }
    }

    public function sendSMSForLabelConfirmation($type){
        $db = new DBi();
        $ut = new Utility();

        switch (intval($type)){
            case 1:
                $cartable = 'تاییدیه برچسب های';
                $sql1 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=135 AND `access_type`=1";  // تاییدیه برچسب
                $res1 = $db->ArrayQuery($sql1);
                break;
            case 2:
                $cartable = 'درخواست فایل Render';
                $sql1 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=146 AND `access_type`=1";  // پیوست فایل به درخواست رندر
                $res1 = $db->ArrayQuery($sql1);
                break;
            case 3:
                $cartable = 'تاییدیه چاپ زینک';
                $sql1 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=150 AND `access_type`=1";  // تاییدیه چاپ زینک
                $res1 = $db->ArrayQuery($sql1);
                break;
        }

        $cnt = count($res1);
        for ($i=0;$i<$cnt;$i++) {
            $sqlp = "SELECT `phone` FROM `users` WHERE `RowID`={$res1[$i]['user_id']}";
            $resp = $db->ArrayQuery($sqlp);

            $phone = $resp[0]['phone'];
            $ut->sendAllBudgetElements($phone, $cartable);
        }
        return true;
    }

    //++++++++++++++++++++++ درخواست برچسب +++++++++++++++++++++++

    public function getLabelRequestManagementList($name,$nDate,$bNum,$status,$closeType,$pieceID,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('labelRequestManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($name)) > 0){
            $w[] = '`unName` LIKE "%'.$name.'%" ';
        }
        if(strlen(trim($nDate)) > 0){
            $nDate = $ut->jal_to_greg($nDate);
            $w[] = '`needDate`="'.$nDate.'" ';
        }
        if(strlen(trim($bNum)) > 0){
            $w[] = '`brName`="'.$bNum.'" ';
        }
        if(intval($status) >= 0){
            $w[] = '`status`='.$status.' ';
        }
        if(intval($pieceID) > 0){
            $rids = array();
            $query = "SELECT `lrid` FROM `label_request_details` WHERE `pieceID`={$pieceID}";
            $rst = $db->ArrayQuery($query);
            $cnt = count($rst);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rst[$i]['lrid'];
            }
            $rids = implode(',',$rids);
            $w[] = '`RowID` IN (' . $rids . ') ';
        }
        $w[] = '`closed`='.$closeType.' ';

        $sql = "SELECT * FROM `label_request`";
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
            $finalRes[$y]['unName'] = $res[$y]['unName'];
            $finalRes[$y]['brName'] = $res[$y]['brName'];
            $finalRes[$y]['description'] = $res[$y]['description'];
            $finalRes[$y]['needDate'] = $ut->greg_to_jal($res[$y]['needDate']);
            $finalRes[$y]['sDate'] = (strtotime($res[$y]['sDate']) > 0 ? $ut->greg_to_jal($res[$y]['sDate']) : '');

            switch (intval($res[$y]['status'])){
                case 0;
                    $finalRes[$y]['status'] = 'تایید نشده';
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
                case 1;
                    $finalRes[$y]['status'] = 'تایید شده';
                    $finalRes[$y]['bgColor'] = 'table-success';
                    break;
            }

            $query1 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$y]['uid']}";
            $rst1 = $db->ArrayQuery($query1);
            $finalRes[$y]['uid'] = $rst1[0]['fname'].' '.$rst1[0]['lname'];

            $query2 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$y]['tid']}";
            $rst2 = $db->ArrayQuery($query2);
            $finalRes[$y]['tid'] = $rst2[0]['fname'].' '.$rst2[0]['lname'];
        }
        return $finalRes;
    }

    public function getLabelRequestManagementListCountRows($name,$nDate,$bNum,$status,$closeType,$pieceID){
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if(strlen(trim($name)) > 0){
            $w[] = '`unName` LIKE "%'.$name.'%" ';
        }
        if(strlen(trim($nDate)) > 0){
            $nDate = $ut->jal_to_greg($nDate);
            $w[] = '`needDate`="'.$nDate.'" ';
        }
        if(strlen(trim($bNum)) > 0){
            $w[] = '`brName`="'.$bNum.'" ';
        }
        if(intval($status) >= 0){
            $w[] = '`status`='.$status.' ';
        }
        if(intval($pieceID) > 0){
            $rids = array();
            $query = "SELECT `lrid` FROM `label_request_details` WHERE `pieceID`={$pieceID}";
            $rst = $db->ArrayQuery($query);
            $cnt = count($rst);
            for ($i=0;$i<$cnt;$i++){
                $rids[] = $rst[$i]['lrid'];
            }
            $rids = implode(',',$rids);
            $w[] = '`RowID` IN (' . $rids . ') ';
        }
        $w[] = '`closed`='.$closeType.' ';

        $sql = "SELECT `RowID` FROM `label_request`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function labelRequestInfo($lrid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `needDate`,`brName`,`description` FROM `label_request` WHERE `RowID`=".$lrid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("lrid"=>$lrid,"needDate"=>$ut->greg_to_jal($res[0]['needDate']),"brName"=>$res[0]['brName'],"description"=>$res[0]['description']);
            return $res;
        }else{
            return false;
        }
    }

    public function createLabelRequest($ndate,$bnumber,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('editCreateLabelRequest')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $ndate = $ut->jal_to_greg($ndate);
        $datetostring = substr($ut->greg_to_jal(str_replace('/','-',date('Y/m/d'))),2,2);
        $unName = 'Group'.$datetostring.substr(time(), -4);

        $sql = "INSERT INTO `label_request` (`unName`,`needDate`,`uid`,`brName`,`description`) VALUES ('{$unName}','{$ndate}',{$_SESSION['userid']},'{$bnumber}','{$desc}')";
        $db->Query($sql);
        $id = $db->InsertrdID();
        if(intval($id) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editLabelRequest($lrid,$ndate,$bnumber,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('editCreateLabelRequest')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $query = "SELECT `status` FROM `label_request` WHERE `RowID`={$lrid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['status']) == 1){
            $res = "موارد تایید شده قابل ویرایش نمی باشند !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $ndate = $ut->jal_to_greg($ndate);
        $sql = "UPDATE `label_request` SET `needDate`='{$ndate}',`uid`={$_SESSION['userid']},`brName`='{$bnumber}',`description`='{$desc}' WHERE `RowID`={$lrid} ";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if(intval($res)){
            return true;
        }else{
            return false;
        }
    }

    public function dodeleteLabelRequest($lrid){
        $db = new DBi();
        
        $sqq = "SELECT `observed` FROM `label_request` WHERE `RowID`={$lrid}";
        $rsq = $db->ArrayQuery($sqq);
        if (intval($rsq[0]['observed']) == 1){
            $res = 'این درخواست باز شده است و امکان حذف ندارد !!!';
            $out = "false";
            response($res,$out);
            exit;
        }

        $query = "DELETE FROM `label_request_details` WHERE `lrid`={$lrid}";
        $db->Query($query);
        $ar = $db->AffectedRows();
        $ar = (($ar == -1 || $ar == 0) ? 0 : 1);
        if(intval($ar) > 0){
            $sql = "DELETE FROM `label_request` WHERE `RowID`={$lrid}";
            $db->Query($sql);
            return true;
        }else{
            return false;
        }
    }

    public function attachedLabelRequestFileHtm($lrid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `RowID`,`fileName`,`createDate`,`createTime`,`dimension`,`description`,`printStatus` FROM `label_request_attachment` WHERE `lrid`={$lrid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachedLabelRequestFileHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ساعت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">ابعاد (سانتی متر)</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 40%;">توضیحات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">لینک دانلود</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">حذف فایل</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $iterator = 0;
        for ($i=0;$i<$cnt;$i++) {
            $iterator++;
            $link = ADDR . 'label/' . $res[$i]['fileName'];
            $btn = (intval($res[$i]['printStatus']) == 0 ? 'btn-danger' : 'btn-success');
            $icon = (intval($res[$i]['printStatus']) == 0 ? 'fa-square' : 'fa-check-square');
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $ut->greg_to_jal($res[$i]['createDate']) . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['createTime'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['dimension'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['description'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" onclick="download_label(\''.$res[$i]['fileName'].'\',this)" download target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachLabelRequestFile(' . $res[$i]['RowID'] . ')" ><i class="fas fa-trash"></i></button></td>';
            $htm .= '</tr>';
        }
            $htm .= '</tbody>';
            $htm .= '</table>';

        return $htm;
    }

    public function attachFileToLabelRequest($lrid,$dimension,$desc,$files){
        $db = new DBi();
        $ftp=new FTP();
        $query = "SELECT `status` FROM `label_request` WHERE `RowID`={$lrid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['status']) == 0){
            $res = "پیوست فایل تنها به موارد تایید شده امکان پذیر است !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $SFile = array();
        $allowedTypes = ['zip','ZIP'];
        if (isset($files) && !empty($files)) {
            $no_files = count($files['name']);
            $nowDate = date('Y-m-d');
            $nowTime = date('H:i:s');
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
                $SFile[] = "labelRqst" . rand(0, time()).'.'.$format;

               // $upload = move_uploaded_file($files["tmp_name"][$i],'../temp_label_upload/'.$SFile[$i]);
                $upload=$ftp->Upload($SFile[$i],$files["tmp_name"][$i]);
                if($upload){
                    $upload=$ftp->Upload($SFile[$i],'../temp_label_upload');
                    $sql4 = "INSERT INTO `label_request_attachment` (`lrid`,`fileName`,`createDate`,`createTime`,`uid`,`dimension`,`description`) VALUES ({$lrid},'{$SFile[$i]}','{$nowDate}','{$nowTime}',{$_SESSION['userid']},'{$dimension}','{$desc}')";
                    $db->Query($sql4);
                    return true;
                }
                return false;
            } // for()
        } //  if (isset($files) && !empty($files))

        // $nowDate = date('Y-m-d');
        // $nowTime = date('H:i:s');
        // $cnt = count($SFile);
        // for ($i=0;$i<$cnt;$i++) {
        //     $upload = move_uploaded_file($files["tmp_name"][$i],'../temp_label_upload/'.$SFile[$i]);
        //     $upload=$ftp->Upload($SFile[$i],'../temp_label_upload');
        //    $sql4 = "INSERT INTO `label_request_attachment` (`lrid`,`fileName`,`createDate`,`createTime`,`uid`,`dimension`,`description`) VALUES ({$lrid},'{$SFile[$i]}','{$nowDate}','{$nowTime}',{$_SESSION['userid']},'{$dimension}','{$desc}')";
        //     $db->Query($sql4);
        // }
       
    }

    public function deleteAttachLabelRequestFile($lraid){
        $db = new DBi();
        $ftp=new FTP();
        $query = "SELECT `printStatus` FROM `label_request_attachment` WHERE `RowID`={$lraid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['printStatus']) == 1){
            $res = 'این مورد تاییدیه چاپ دارد !!!';
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "SELECT `fileName` FROM `label_request_attachment` WHERE `RowID`={$lraid}";
        $res = $db->ArrayQuery($sql);
      //  $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/label/' . $res[0]['fileName'];
        $file_to_delete = $res[0]['fileName'];
        $result = $ftp->Delete($file_to_delete);
        if ($result) {
            $query = "DELETE FROM `label_request_attachment` WHERE `RowID`={$lraid}";
            $db->Query($query);
            return true;
        }else{
            return false;
        }
    }

    public function confirmationAttachLabelRequestFile($lraid,$actioner,$desc){
        $db = new DBi();
        $nowDate = date('Y-m-d');
        $query = "SELECT `printStatus` FROM `label_request_attachment` WHERE `RowID`={$lraid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['printStatus']) == 1){
            $res = 'این مورد قبلا تایید شده است !!!';
            $out = "false";
            response($res,$out);
            exit;
        }else{
            $sql = "UPDATE `label_request_attachment` SET `printStatus`=1,`actioner`={$actioner},`actionerDesc`='{$desc}',`actionerDate`='{$nowDate}' WHERE `RowID`={$lraid}";
            $db->Query($sql);
            $res = $db->AffectedRows();
            $res = (($res == -1 || $res == 0) ? 0 : 1);
            if(intval($res)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function finisherAttachLabelRequestFile($lraid){
        $db = new DBi();
        $query = "SELECT `finished` FROM `label_request_attachment` WHERE `RowID`={$lraid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['finished']) == 1){
            $res = 'برای این مورد قبلا اتمام کار اعلام شده است !!!';
            $out = "false";
            response($res,$out);
            exit;
        }else{
            $sql = "UPDATE `label_request_attachment` SET `finished`=1 WHERE `RowID`={$lraid}";
            $db->Query($sql);
            $res = $db->AffectedRows();
            $res = (($res == -1 || $res == 0) ? 0 : 1);
            if(intval($res)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function attachmentFileLabelRequestHtm($lrid){
        $db = new DBi();
        $ut = new Utility();
        $acm = new acm();

        if($acm->hasAccess('actionerAttachLabelRequestZinc')) {
            $sql = "SELECT `label_request_attachment`.`RowID` AS `lraid`,`fileName`,`createDate`,`createTime`,`dimension`,`description`,`printStatus`,`actionerDesc`,`actioner`,`actionerDate`,`finished`,`fname`,`lname` FROM `label_request_attachment` LEFT JOIN `users` ON (`label_request_attachment`.`uid`=`users`.`RowID`) WHERE `lrid`={$lrid} AND `actioner`={$_SESSION['userid']}";
        }else{
            $sql = "SELECT `label_request_attachment`.`RowID` AS `lraid`,`fileName`,`createDate`,`createTime`,`dimension`,`description`,`printStatus`,`actionerDesc`,`actioner`,`actionerDate`,`finished`,`fname`,`lname` FROM `label_request_attachment` LEFT JOIN `users` ON (`label_request_attachment`.`uid`=`users`.`RowID`) WHERE `lrid`={$lrid}";
        }
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileLabelRequestHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ثبت کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">زمان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ابعاد (سانتی متر)</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 13%;">توضیحات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">لینک دانلود</td>';
        if ($acm->hasAccess('confirmationAttachLabelRequestZinc')) {
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">تاییدیه چاپ</td>';
        }
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">وضعیت چاپ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">تاریخ ارجاع</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">اقدام کننده چاپ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">توضیحات جهت اقدام کننده</td>';
        if (!$acm->hasAccess('confirmationAttachLabelRequestZinc')) {
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">اتمام کار</td>';
        }
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $iterator = 0;
        for ($i=0;$i<$cnt;$i++) {
            $iterator++;
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);
            $link = ADDR . 'label/' . $res[$i]['fileName'];
            $btn = (intval($res[$i]['printStatus']) == 0 ? 'btn-danger' : 'btn-success');
            $btn1 = (intval($res[$i]['finished']) == 0 ? 'btn-danger' : 'btn-success');
            $icon = (intval($res[$i]['printStatus']) == 0 ? 'fa-square' : 'fa-check-square');
            $icon1 = (intval($res[$i]['finished']) == 0 ? 'fa-square' : 'fa-check-square');
            $trColor = (intval($res[$i]['printStatus']) == 1 ? 'table-success' : 'table-danger');
            $res[$i]['printStatus'] = (intval($res[$i]['printStatus']) == 1 ? 'تایید شده' : 'انتظار تایید');
            $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$i]['actioner']}";
            $rst = $db->ArrayQuery($query);
            $actioner = $rst[0]['fname'].' '.$rst[0]['lname'];

            $res[$i]['actionerDesc'] = ($acm->hasAccess('actionerAttachLabelRequestZinc') || $acm->hasAccess('confirmationAttachLabelRequestZinc') ? $res[$i]['actionerDesc'] : '');

            $htm .= '<tr class="'.$trColor.'">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['fname'] . ' ' . $res[$i]['lname'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['createDate'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['createTime'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['dimension'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['description'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" onclick="download_label(\''.$res[$i]['fileName'].'\',this)" download target="_blank"><i class="fas fa-download"></i></a></td>';
            if ($acm->hasAccess('confirmationAttachLabelRequestZinc')) {
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn ' . $btn . '" onclick="confirmationAttachLabelRequestFile(' . $res[$i]['lraid'] . ')" ><i class="fas ' . $icon . '"></i></button></td>';
            }
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['printStatus'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $ut->greg_to_jal($res[$i]['actionerDate']) . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $actioner . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['actionerDesc'] . '</td>';
            if (!$acm->hasAccess('confirmationAttachLabelRequestZinc')) {
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn ' . $btn1 . '" onclick="finisherAttachLabelRequestFile(' . $res[$i]['lraid'] . ')" ><i class="fas ' . $icon1 . '"></i></button></td>';
            }
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function confirmationLabelRequest($lrid){
        $db = new DBi();
        $ut = new Utility();

        $nowDate = date('Y-m-d');
        $sql = "UPDATE `label_request` SET `status`=1,`tid`={$_SESSION['userid']},`sDate`='{$nowDate}' WHERE `RowID`={$lrid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            $sql1 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=134 AND `access_type`=1";  // ایجاد و ویرایش برچسب
            $res1 = $db->ArrayQuery($sql1);

            $sqlp = "SELECT `phone` FROM `users` WHERE `RowID`={$res1[0]['user_id']}";
            $resp = $db->ArrayQuery($sqlp);

            $cartable = 'درخواست برچسب';
            $phone = $resp[0]['phone'];
            $ut->sendAllBudgetElements($phone, $cartable);

            return true;
        }else{
            return false;
        }
    }

    public function getLabelHPCode($lid){
        $db = new DBi();
        $sql = "SELECT `HPCode` FROM `piece_masterlist` WHERE `pid`={$lid}";
        return $db->ArrayQuery($sql);
    }

    public function getLabelName($hpCode){
        $db = new DBi();
        $sql = "SELECT `pid` FROM `piece_masterlist` WHERE `HPCode`='{$hpCode}'";
        return $db->ArrayQuery($sql);
    }

    public function getLabelRequestDetailsHtm($lrid){
        $db = new DBi();
        $acm = new acm();
        if($acm->hasAccess('editCreateLabel')){
            $query = "SELECT `status` FROM `label_request` WHERE `RowID`={$lrid}";
            $rst = $db->ArrayQuery($query);
            if (intval($rst[0]['status']) == 0){
                $res = 'شما فقط درخواست های تایید شده را می توانید مشاهده نمایید !!!';
                $out = "false";
                response($res,$out);
                exit;
            }else{
                $sqq = "UPDATE `label_request` SET `observed`=1 WHERE `RowID`={$lrid}";
                $db->Query($sqq);
            }
        }

        $sql = "SELECT `label_request_details`.`RowID`,`status`,`pName`,`pCode`,`number`,`HPCode` FROM `label_request_details` INNER JOIN `piece` ON (`label_request_details`.`pieceID`=`piece`.`RowID`) WHERE `lrid`={$lrid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);


        $htm = '';
        if (count($res) > 0) {
            $htm .= '<table class="table table-bordered table-hover table-sm" id="getLabelRequestDetailsHtm-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">ردیف</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">موجودی</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">کد برچسب</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 27%;">نام برچسب</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">کد کالا</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تعداد</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">حذف</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">وضعیت</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $iterator = 0;
            for ($i=0;$i<$cnt;$i++) {
                $iterator++;

                $sqq = "SELECT `RowID` FROM `label` WHERE `labelNum`='{$res[$i]['pCode']}'";
                $rsq = $db->ArrayQuery($sqq);
                if (count($rsq) > 0){
                    $stock = 'وجود دارد';
                    $colour = 'green';
                }else{
                    $stock = 'وجود ندارد';
                    $colour = 'red';
                }

                $btn = (intval($res[$i]['status']) == 0 ? 'btn-danger' : 'btn-success');
                $icon = (intval($res[$i]['status']) == 0 ? 'fa-square' : 'fa-check-square');
                $icon1 = (intval($res[$i]['status']) == 0 ? 'fa-ban' : 'fa-circle-check');
                $color = (intval($res[$i]['status']) == 0 ? 'red' : 'green');

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;color: '.$colour.'">' . $stock . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['pCode'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['pName'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['HPCode'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['number'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteLabelRequestDetails(' . $res[$i]['RowID'] . ')" ><i class="fas fa-trash"></i></button></td>';
                if ($acm->hasAccess('editCreateLabel')){
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn '.$btn.'" onclick="confirmationLabelRequestDetails(' . $res[$i]['RowID'] . ')" ><i class="fas '.$icon.'"></i></button></td>';
                }else{
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;color: '.$color.'"><i class="fas '.$icon1.' fa-lg"></i></td>';
                }
                $htm .= '</tr>';
            }

            $htm .= '</tbody>';
            $htm .= '</table>';
        }
        return array(true,$htm);
    }

    public function createLabelRequestDetails($lrid,$lid,$number){
        $db = new DBi();

        $query = "SELECT `RowID` FROM `label_request_details` WHERE `lrid`={$lrid} AND `pieceID`={$lid}";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0){
            $res = 'این برچسب برای این درخواست قبلا ثبت شده است !!!';
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql1 = "SELECT `status`,`observed` FROM `label_request` WHERE `RowID`={$lrid}";
        $res1 = $db->ArrayQuery($sql1);
        if (intval($res1[0]['status']) == 1 && intval($res1[0]['observed']) == 1){
            $res = 'این درخواست تایید و باز شده است !!!';
            $out = "false";
            response($res,$out);
            exit;
        }

        $sqq = "SELECT `HPCode` FROM `piece_masterlist` WHERE `pid`={$lid}";
        $rsq = $db->ArrayQuery($sqq);

        $sql = "INSERT INTO `label_request_details` (`lrid`,`pieceID`,`HPCode`,`uid`,`number`) VALUES ({$lrid},{$lid},'{$rsq[0]['HPCode']}',{$_SESSION['userid']},{$number})";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function deleteLabelRequestDetails($lrid,$lrdid){
        $db = new DBi();

        $query = "SELECT `status`,`observed` FROM `label_request` WHERE `RowID`={$lrid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['status']) == 1 && intval($rst[0]['observed']) == 1){
            $res = 'این درخواست تایید و باز شده است و امکان تغییر ندارد !!!';
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "DELETE FROM `label_request_details` WHERE `RowID`={$lrdid}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if(intval($res)){
            return true;
        }else{
            return false;
        }
    }

    private function getActioner(){
        $db = new DBi();
        $sql = "SELECT `users`.`RowID` AS `uid`,`fname`,`lname` FROM `access_table` INNER JOIN `users` ON (`access_table`.`user_id`=`users`.`RowID`) WHERE `item_id`=153";
        return $db->ArrayQuery($sql);
    }

    public function confirmationLabelRequestDetails($lrdid){
        $db = new DBi();

        $sql = "SELECT `status` FROM `label_request_details` WHERE `RowID`={$lrdid}";
        $res = $db->ArrayQuery($sql);
        $status = (intval($res[0]['status']) == 0 ? 1 : 0);
        $sql1 = "UPDATE `label_request_details` SET `status`={$status} WHERE `RowID`={$lrdid}";
        $db->Query($sql1);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if(intval($res)){
            return true;
        }else{
            return false;
        }
    }

    public function closedLabelRequest($lrid){
        $db = new DBi();

        $query = "SELECT `RowID` FROM `label_request_attachment` WHERE `lrid`={$lrid} AND `printStatus`=0";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0){
            $res = "ابتدا تاییدیه چاپ تمامی زینک ها را صادر نمایید !";
            $out = "false";
            response($res,$out);
            exit;
        }else{
            $sql = "SELECT `RowID` FROM `label_request_details` WHERE `lrid`={$lrid} AND `status`=0";
            $res = $db->ArrayQuery($sql);
            if (count($res) > 0){
                $res = "همه جزئیات این درخواست تایید نشده است !";
                $out = "false";
                response($res,$out);
                exit;
            }else{
                $sql1 = "UPDATE `label_request` SET `closed`=0 WHERE `RowID`={$lrid}";
                $db->Query($sql1);
                $res = $db->AffectedRows();
                $res = (($res == -1 || $res == 0) ? 0 : 1);
                if(intval($res)){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }

    public function createLabelRequestExcel($lrid){
        $db = new DBi();
        $sql = "SELECT `label_request_details`.*,`pCode`,`pName` FROM `label_request_details` INNER JOIN `piece` ON (`label_request_details`.`pieceID`=`piece`.`RowID`) WHERE `lrid`={$lrid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            $sqq = "SELECT `RowID` FROM `label` WHERE `labelNum`='{$res[$i]['pCode']}'";
            $rsq = $db->ArrayQuery($sqq);
            $res[$i]['status'] = (count($rsq) > 0 ? 'وجود دارد' : 'وجود ندارد');
        }
        if(count($res) > 0){
            return $res;
        }else{
            $res = "امکان گرفتن خروجی وجود ندارد";
            $out = "false";
            response($res,$out);
            exit;
        }
    }

    //++++++++++++++++++++++ درخواست داده مهندسی +++++++++++++++++++++++

    public function getRenderingRequestManageList($piece,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('renderingRequestManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(intval($piece) > 0){
            $w[] = '`pieceID`='.$piece.' ';
        }

        $sql = "SELECT `label_rendering`.*,`pName`,`pCode`,`HPCode` FROM `label_rendering` 
                INNER JOIN `piece` ON (`label_rendering`.`pieceID`=`piece`.`RowID`)
                INNER JOIN `piece_masterlist` ON (`piece`.`RowID`=`piece_masterlist`.`pid`)
                ";
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
            $finalRes[$y]['pName'] = $res[$y]['pName'];
            $finalRes[$y]['pCode'] = $res[$y]['pCode'];
            $finalRes[$y]['HPCode'] = $res[$y]['HPCode'];
            $finalRes[$y]['type'] = (intval($res[$y]['type']) == 0 ? 'برچسب' : 'کارتن');
            $finalRes[$y]['description'] = $res[$y]['description'];
            $finalRes[$y]['cDate'] = $ut->greg_to_jal($res[$y]['cDate']);

            $finalRes[$y][0]['btnType'] = 'btn-info';

            switch ($res[$y]['used']){
                case 0:
                    $finalRes[$y][1]['btnType'] = 'btn-danger';
                    $finalRes[$y][1]['icon'] = 'fa-eye-slash';
                    break;
                case 1:
                    $finalRes[$y][1]['btnType'] = 'btn-success';
                    $finalRes[$y][1]['icon'] = 'fa-eye';
                    break;
            }
            switch ($res[$y]['attached']){
                case 0:
                    $finalRes[$y][2]['btnType'] = 'btn-danger';
                    break;
                case 1:
                    $finalRes[$y][2]['btnType'] = 'btn-success';
                    break;
            }

            $query1 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$y]['uid']}";
            $rst1 = $db->ArrayQuery($query1);
            $finalRes[$y]['uid'] = $rst1[0]['fname'].' '.$rst1[0]['lname'];
        }
        return $finalRes;
    }

    public function getRenderingRequestManageListCountRows($piece){
        $acm = new acm();
        $db = new DBi();
        $w = array();
        if(intval($piece) > 0){
            $w[] = '`pieceID`='.$piece.' ';
        }

        $sql = "SELECT `RowID` FROM `label_rendering`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function renderingRequestInfo($rid){
        $db = new DBi();
        $sql = "SELECT `pieceID`,`label_rendering`.`description`,`pCode`,`type` FROM `label_rendering` INNER JOIN `piece` ON (`label_rendering`.`pieceID`=`piece`.`RowID`) WHERE `label_rendering`.`RowID`=".$rid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("rid"=>$rid,"pieceID"=>$res[0]['pieceID'],"description"=>$res[0]['description'],"pCode"=>$res[0]['pCode'],"type"=>$res[0]['type']);
            return $res;
        }else{
            return false;
        }
    }

    public function checkRenderingRequest($piece){
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `label_rendering` WHERE `pieceID`={$piece}";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function createRenderingRequest($piece,$desc,$type){
        $acm = new acm();
        if(!$acm->hasAccess('editCreateRenderingRequest')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $cDate = date('Y-m-d');
        $sql = "INSERT INTO `label_rendering` (`pieceID`,`uid`,`cDate`,`description`,`type`) VALUES ({$piece},{$_SESSION['userid']},'{$cDate}','{$desc}',{$type})";
        $db->Query($sql);
        $id = $db->InsertrdID();
        if(intval($id) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editRenderingRequest($rid,$piece,$desc,$type){
        $acm = new acm();
        if(!$acm->hasAccess('editCreateRenderingRequest')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $cDate = date('Y-m-d');
        $sql = "UPDATE `label_rendering` SET `pieceID`={$piece},`uid`={$_SESSION['userid']},`cDate`='{$cDate}',`description`='{$desc}',`type`={$type} WHERE `RowID`={$rid}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if(intval($res)){
            return true;
        }else{
            return false;
        }
    }

    public function attachedRenderingRequestFileHtm($rid){
        $db = new DBi();

        $sql = "SELECT `fileName`,`type` FROM `label_rendering` WHERE `RowID`={$rid}";
        $res = $db->ArrayQuery($sql);

        $htm = '';
        if (intval($res[0]['type']) == 1){  // کارتن
            $sql1 = "SELECT `RowID`,`fileName` FROM `label_rendering_attachment` WHERE `lrid`={$rid}";
            $res1 = $db->ArrayQuery($sql1);
            if (count($res1) > 0){
                $cnt = count($res1);
                $htm .= '<table class="table table-bordered table-hover table-sm" id="attachedRenderingRequestFileHtm-tableID">';
                $htm .= '<thead>';
                $htm .= '<tr class="bg-info">';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">لینک دانلود</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">حذف فایل</td>';
                $htm .= '</tr>';
                $htm .= '</thead>';
                $htm .= '<tbody>';

                $iterator = 0;
                for ($i=0;$i<$cnt;$i++) {
                    $iterator++;
                   // $link = ADDR . 'label/' . $res1[$i]['fileName'];
                    $htm .= '<tr class="table-secondary">';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" onclick="download_label(\''. $res[0]['fileName'].'\',this)" download target="_blank"><i class="fas fa-download"></i></a></td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachRenderingRequestFile(' . $res1[$i]['RowID'] . ',' . $res[0]['type'] . ')" ><i class="fas fa-trash"></i></button></td>';
                    $htm .= '</tr>';
                }

                $htm .= '</tbody>';
                $htm .= '</table>';
            }
        }else{
            if (strlen(trim($res[0]['fileName'])) > 0) {
                $htm .= '<table class="table table-bordered table-hover table-sm" id="attachedRenderingRequestFileHtm-tableID">';
                $htm .= '<thead>';
                $htm .= '<tr class="bg-info">';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">لینک دانلود</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">حذف فایل</td>';
                $htm .= '</tr>';
                $htm .= '</thead>';
                $htm .= '<tbody>';

                $link = ADDR . 'label/' . $res[0]['fileName'];

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">1</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" onclick="download_label(\''. $res[0]['fileName'].'\',this)"  download target="_blank"><i class="fas fa-download"></i></a></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachRenderingRequestFile(' . $rid .','.$res[0]['type']. ')" ><i class="fas fa-trash"></i></button></td>';
                $htm .= '</tr>';

                $htm .= '</tbody>';
                $htm .= '</table>';
            }
        }
        return array($htm,$res[0]['type']);
    }

    public function attachFileToRenderingRequest($rid,$thicness,$size,$latin,$files,$cartoonSize,$labels,$type,$filesm){
        $db = new DBi();
        $ftp=new FTP();
        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');
        if (intval($type) == 0) { // برچسب
            $query = "SELECT `fileName` FROM `label_rendering` WHERE `RowID`={$rid}";
            $rst = $db->ArrayQuery($query);
            if (strlen(trim($rst[0]['fileName'])) > 0) {
                $res = "فقط یک فایل می توانید آپلود نمایید !";
                $out = "false";
                response($res, $out);
                exit;
            }

            $SFile = array();
            $allowedTypes = ['png', 'PNG'];
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
                    $SFile[] = "renderRqst" . rand(0, time()) . '.' . $format;
                    
                    //$upload = move_uploaded_file($files["tmp_name"][0], '../label/' . $SFile[0]);
                    $upload = $ftp->Upload($SFile[0],$files["tmp_name"][0]);
                    if($upload){
                        $sql4 = "UPDATE `label_rendering` SET `fileName`='{$SFile[0]}',`createDate`='{$nowDate}',`createTime`='{$nowTime}',`Creator`={$_SESSION['userid']},`minThickness`='{$thicness}',`size`='{$size}',`latinName`='{$latin}',`attached`=1 WHERE `RowID`={$rid}";
                        $db->Query($sql4);
                    }
                    else{
                        return false;
                    }
                   
                } // for()
            } //  if (isset($files) && !empty($files))

        //    // $upload = move_uploaded_file($files["tmp_name"][0], '../label/' . $SFile[0]);
        //     $sql4 = "UPDATE `label_rendering` SET `fileName`='{$SFile[0]}',`createDate`='{$nowDate}',`createTime`='{$nowTime}',`Creator`={$_SESSION['userid']},`minThickness`='{$thicness}',`size`='{$size}',`latinName`='{$latin}',`attached`=1 WHERE `RowID`={$rid}";
        //     $db->Query($sql4);
        }else{          // کارتن
            $SFile = array();
            $allowedTypes = ['pdf', 'PDF'];
            if (isset($filesm) && !empty($filesm)) {
                $no_files = count($filesm['name']);
                for ($i = 0; $i < $no_files; $i++) {
                    $filepath = $filesm["tmp_name"][$i];
                    if ($filesm["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                        return -1;
                    }
                    if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                        return -2;
                    }
                    $format = substr($filesm['name'][$i], strpos($filesm['name'][$i], ".") + 1);
                    if (!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                        return -3;
                    }
                    $SFile[] = "renderRqst" . rand(0, time()) . '.' . $format;
                  //  $upload = move_uploaded_file($filesm["tmp_name"][$i], '../label/' . $SFile[$i]);
                    $upload = $ftp->Upload($SFile[$i],$filesm["tmp_name"][$i]);
                    if($upload){
                        $sql4 = "INSERT INTO `label_rendering_attachment` (`lrid`,`fileName`,`createDate`,`createTime`,`uid`) VALUES ({$rid},'{$SFile[$i]}','{$nowDate}','{$nowTime}',{$_SESSION['userid']})";
                        $db->Query($sql4);
                    }
                    else{
                        return false;
                    }
                    
                } // for()
            } //  if (isset($files) && !empty($files))
            $cnt = count($SFile);
          //  for ($i=0;$i<$cnt;$i++) {
                // $upload = move_uploaded_file($filesm["tmp_name"][$i], '../label/' . $SFile[$i]);
                // $sql4 = "INSERT INTO `label_rendering_attachment` (`lrid`,`fileName`,`createDate`,`createTime`,`uid`) VALUES ({$rid},'{$SFile[$i]}','{$nowDate}','{$nowTime}',{$_SESSION['userid']})";
                // $db->Query($sql4);
           // }
            $sql5 = "UPDATE `label_rendering` SET `labels`='{$labels}',`cartoonSize`='{$cartoonSize}',`attached`=1 WHERE `RowID`={$rid}";
            $db->Query($sql5);
        }
        return true;
    }

    public function deleteAttachRenderingRequestFile($rid,$type){
        $db = new DBi();
        $ftp=new FTP();
        if (intval($type) == 0) {   // برچسب
            $sql = "SELECT `fileName` FROM `label_rendering` WHERE `RowID`={$rid}";
            $res = $db->ArrayQuery($sql);
            //$file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/label/' . $res[0]['fileName'];
            $file_to_delete = $res[0]['fileName'];
           // $result = unlink($file_to_delete);
            $result = $ftp->Delete($file_to_delete);
            if ($result) {
                $query = "UPDATE `label_rendering` SET `fileName`='',`createDate`='',`createTime`='',`Creator`=0,`minThickness`='',`size`='',`latinName`='',`attached`=0  WHERE `RowID`={$rid}";
                $db->Query($query);
                return true;
            } else {
                return false;
            }
        }else{   // کارتن
            $sql = "SELECT `fileName`,`lrid` FROM `label_rendering_attachment` WHERE `RowID`={$rid}";
            $res = $db->ArrayQuery($sql);
           // $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/label/' . $res[0]['fileName'];
            $file_to_delete = $res[0]['fileName'];
           // $result = unlink($file_to_delete);
            $result = $ftp->Delete($file_to_delete);
            if ($result) {
                $sql1 = "DELETE FROM `label_rendering_attachment` WHERE `RowID`={$rid}";
                $db->Query($sql1);

                $sql2 = "SELECT `RowID` FROM `label_rendering_attachment` WHERE `lrid`={$res[0]['lrid']}";
                $res2 = $db->ArrayQuery($sql2);
                $attached = ( count($res2) > 0 ? 1 : 0 );

                $query = "UPDATE `label_rendering` SET `attached`={$attached}  WHERE `RowID`={$res[0]['lrid']}";
                $db->Query($query);
                return true;
            } else {
                return false;
            }
        }
    }

    public function attachmentFileRenderingRequestHtm($rid){
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT `fileName`,`createDate`,`createTime`,`minThickness`,`size`,`latinName`,`fname`,`lname`,`type`,`labels`,`cartoonSize` FROM `label_rendering` LEFT JOIN `users` ON (`label_rendering`.`Creator`=`users`.`RowID`) WHERE `label_rendering`.`RowID`={$rid}";
        $res = $db->ArrayQuery($sql);

        $htm = '';
        if (intval($res[0]['type']) == 0) {   // برچسب
            if (strlen(trim($res[0]['fileName'])) > 0) {
                $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileRenderingRequestHtm-tableID">';
                $htm .= '<thead>';
                $htm .= '<tr class="bg-info">';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 50%;">ثبت کننده</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">تاریخ</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">زمان</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">لینک دانلود</td>';
                $htm .= '</tr>';
                $htm .= '</thead>';
                $htm .= '<tbody>';

                $res[0]['createDate'] = $ut->greg_to_jal($res[0]['createDate']);
                $link = ADDR . 'label/' . $res[0]['fileName'];
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">1</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[0]['fname'] . ' ' . $res[0]['lname'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[0]['createDate'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[0]['createTime'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" onclick="download_label(\''.$res[0]['fileName'].'\',this)" download target="_blank"><i class="fas fa-download"></i></a></td>';
                $htm .= '</tr>';

                $htm .= '</tbody>';
                $htm .= '</table>';
            }
        }else{
            $query = "SELECT `label_rendering_attachment`.*,`fname`,`lname` FROM `label_rendering_attachment` INNER JOIN `users` ON (`label_rendering_attachment`.`uid`=`users`.`RowID`) WHERE `lrid`={$rid}";
            $rst = $db->ArrayQuery($query);
            if (count($rst) > 0){
                $cnt = count($rst);
                $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileRenderingRequestHtm-tableID">';
                $htm .= '<thead>';
                $htm .= '<tr class="bg-info">';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 50%;">ثبت کننده</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">تاریخ</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">زمان</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">لینک دانلود</td>';
                $htm .= '</tr>';
                $htm .= '</thead>';
                $htm .= '<tbody>';

                $iterator = 0;
                for ($i=0;$i<$cnt;$i++) {
                    $iterator++;
                    $rst[$i]['createDate'] = $ut->greg_to_jal($rst[$i]['createDate']);
                    $link = ADDR . 'label/' . $rst[$i]['fileName'];
                    $htm .= '<tr class="table-secondary">';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['fname'] . ' ' . $rst[$i]['lname'] . '</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['createDate'] . '</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['createTime'] . '</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
                    $htm .= '</tr>';
                }
                $htm .= '</tbody>';
                $htm .= '</table>';
            }
        }
        return $htm;
    }

    public function infoRenderingRequestHtm($rid){
        $db = new DBi();

        $sql = "SELECT `minThickness`,`size`,`latinName`,`type`,`labels`,`cartoonSize` FROM `label_rendering` WHERE `label_rendering`.`RowID`={$rid}";
        $res = $db->ArrayQuery($sql);

        $htm = '';
        if (intval($res[0]['type']) == 0) {   // برچسب
            $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileRenderingRequestHtm-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 30%;">minThickness</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 30%;">سایز</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">نام لاتین</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">1</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[0]['minThickness'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[0]['size'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[0]['latinName'] . '</td>';
            $htm .= '</tr>';
            $htm .= '</tbody>';
            $htm .= '</table>';
        }else{
            $sql1 = "SELECT `pName` FROM `piece` WHERE `RowID` IN ({$res[0]['labels']})";
            $res1 = $db->ArrayQuery($sql1);
            $labels = array();
            if (count($res1) > 0){
                $ccnt = count($res1);
                for ($i=0;$i<$ccnt;$i++){
                    $labels[] = $res1[$i]['pName'];
                }
            }
            $labels = implode(' - ',$labels);

            $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileRenderingRequestHtm-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 25%;">ابعاد گسترده کارتن</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 70%;">برچسب نصبی کارتن</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">1</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $res[0]['cartoonSize'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $labels . '</td>';
            $htm .= '</tr>';
            $htm .= '</tbody>';
            $htm .= '</table>';
        }
        return $htm;
    }

    public function usedRenderingRequestAttachment($rid){
        $db = new DBi();

        $sql = "SELECT `used` FROM `label_rendering` WHERE `RowID`={$rid}";
        $res = $db->ArrayQuery($sql);
        $used = (intval($res[0]['used']) == 0 ? 1 : 0);

        $sql1 = "UPDATE `label_rendering` SET `used`={$used} WHERE `RowID`={$rid}";
        $db->Query($sql1);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

}