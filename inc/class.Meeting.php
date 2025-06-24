<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
class Meeting{
	public $meeting_info=[];


	public function __construct(){
		
      $ut=new Utility();
      $db = new DBi();
      $username_id=$_SESSION['userid'];
      $query= "select * from meeting where observers like'%{$username_id}%'";
      $meeting_array=$db->ArrayQuery($query);
	  foreach($meeting_array as $meering_array_key=>$meeting_array){
		  $temp_array=[];
		  foreach($meeting_array as $key=>$array_value){
			$temp_array[$key] =$array_value;
		  }
		  unset($temp_array['RowID']);
		$this->meeting_info[$meeting_array['RowID']]=$temp_array;
	  }
      ////$//ut->fileRecorder(print_r($this->meeting_info,true));
    }

    public function getMeetingManageHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $user = new User();
        $db = new DBi();

        $sql = "SELECT `user_id` FROM `access_table` WHERE `item_id`=155";  // مدیریت کارخانه
        $res = $db->ArrayQuery($sql);

        $grouping = $this->getMeetingGroups();
        $CountGroups = count($grouping);

        $users = $user->getUsers();
        $cntu = count($users);

        $modirID = $user->getCompanyManagement();

        $pagename = array();
        $pageIcon = array();
        $contentId = array();
        $menuItems = array();
        $bottons = array();
        $headerSearch = array();
        $access = array();
        $hiddenContentId[] = "hiddenMeetingPrintBody";

        $x = 0;
        $y = 0;
        $z = 0;
        $manifold = 0;
        if($acm->hasAccess('meetingManage')) {
            $pagename[$x] = "جلسات";
            $pageIcon[$x] = "fa-handshake";
            $contentId[$x] = "firstMeetingManageBody";
            $menuItems[$x] = 'firstMeetingManageTabID';

            $bottons1 = array();
            $headerSearch1 = array();

            $a = 0;
            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "200px";
            $headerSearch1[$a]['id'] = "meetingManageUncodeSearch";
            $headerSearch1[$a]['title'] = "کد یکتا";
            $headerSearch1[$a]['placeholder'] = "کد یکتا";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "200px";
            $headerSearch1[$a]['id'] = "meetingManageSubjectSearch";
            $headerSearch1[$a]['title'] = "موضوع جلسه";
            $headerSearch1[$a]['placeholder'] = "موضوع جلسه";
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "meetingManageGroupingSearch";
            $headerSearch1[$a]['title'] = "دسته بندی جلسه";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = "دسته بندی جلسه";
            $headerSearch1[$a]['options'][0]["value"] = 0;
            for ($i=0;$i<$CountGroups;$i++){
                $headerSearch1[$a]['options'][$i+1]["title"] = $grouping[$i]['groupName'];
                $headerSearch1[$a]['options'][$i+1]["value"] = $grouping[$i]['RowID'];
            }
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "meetingManageSenderSearch";
            $headerSearch1[$a]['title'] = "ایجاد کننده جلسه";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = "ایجاد کننده جلسه";
            $headerSearch1[$a]['options'][0]["value"] = 0;
            $headerSearch1[$a]['options'][1]["title"] = "خودم";
            $headerSearch1[$a]['options'][1]["value"] = 1;
            $headerSearch1[$a]['options'][2]["title"] = "دیگران";
            $headerSearch1[$a]['options'][2]["value"] = 2;
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "meetingManageCloseOrOpenSearch";
            $headerSearch1[$a]['title'] = "وضعیت جلسه";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = "جلسات باز";
            $headerSearch1[$a]['options'][0]["value"] = 0;
            $headerSearch1[$a]['options'][1]["title"] = "جلسات بسته شده";
            $headerSearch1[$a]['options'][1]["value"] = 1;
            $a++;

            $headerSearch1[$a]['type'] = "btn";
            $headerSearch1[$a]['title'] = "جستجو &nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch1[$a]['jsf'] = "showFirstMeetingList";

            $b = 0;
            $bottons1[$b]['title'] = "شروع مقدمات جلسه                                                                                                                                                                                                                                                                                                      ";
            $bottons1[$b]['jsf'] = "createFirstMeeting";
            $bottons1[$b]['icon'] = "fa-plus-square";
            $b++;

/*            $bottons1[$b]['title'] = "ویرایش جلسه";
            $bottons1[$b]['jsf'] = "editFirstMeeting";
            $bottons1[$b]['icon'] = "fa-edit";
            $b++;

            $bottons1[$b]['title'] = "وضعیت جانشین";
            $bottons1[$b]['jsf'] = "createSubstituteStatus";
            $bottons1[$b]['icon'] = "fa-user";
            $b++;

            $bottons1[$b]['title'] = "شروع جلسه";
            $bottons1[$b]['jsf'] = "startFirstMeeting";
            $bottons1[$b]['icon'] = "fa-play";
            $b++;*/

            $bottons1[$b]['title'] = "تعیین مسئولیت ها";
            $bottons1[$b]['jsf'] = "createMeetingJobs";
            $bottons1[$b]['icon'] = "fa-list";
            $b++;

            $bottons1[$b]['title'] = "خاتمه جلسه";
            $bottons1[$b]['jsf'] = "closeFirstMeeting";
            $bottons1[$b]['icon'] = "fa-ban";

            $bottons[$y] = $bottons1;
            $headerSearch[$z] = $headerSearch1;

            $manifold++;
            $access[] = 1;
            $x++;
            $y++;
            $z++;
        }
        if($acm->hasAccess('meetingGroupManage')) {
            $pagename[$x] = "دسته بندی جلسات";
            $pageIcon[$x] = "fa-list";
            $contentId[$x] = "meetingGroupManageBody";
            $menuItems[$x] = 'meetingGroupManageTabID';

            $bottons2 = array();
            $headerSearch2 = array();

            $b = 0;
            $bottons2[$b]['title'] = "ثبت";
            $bottons2[$b]['jsf'] = "createMeetingGroup";
            $bottons2[$b]['icon'] = "fa-plus-square";
            $b++;

            $bottons2[$b]['title'] = "ویرایش";
            $bottons2[$b]['jsf'] = "editMeetingGroup";
            $bottons2[$b]['icon'] = "fa-edit";

            $bottons[$y] = $bottons2;
            $headerSearch[$z] = $headerSearch2;

            $manifold++;
            $access[] = 2;
            $x++;
            $y++;
            $z++;
        }
/*        if($acm->hasAccess('budgetPriceManage')) {
            $pagename[$x] = "قیمت بودجه فروش";
            $pageIcon[$x] = "fa-dollar-sign";
            $contentId[$x] = "budgetPriceManageBody";
            $menuItems[$x] = 'budgetPriceManageTabID';

            $bottons3 = array();
            $headerSearch3 = array();

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
        }*/
        $htm = $ut->getHtmlOfDefaultManagementMultiPage($pagename,$pageIcon,$contentId,$menuItems,$bottons,$manifold,$headerSearch,$hiddenContentId);
        //++++++++++++++++++++++++++++++++++ CREATE Meeting MODAL ++++++++++++++++++++++++++++++++
        $modalID = "meetingManageModal";
        $modalTitle = "فرم ایجاد/ویرایش جلسات";
        $style = 'style="max-width: 915px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "meetingManageType";
        $items[$c]['title'] = "نوع جلسه";
        $items[$c]['options'][0]['title'] = "حالت Fast";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][0]['headline'] = 'جلسات سریع و بدون تشریفات';
        $items[$c]['options'][1]['title'] = "حالت Normal";
        $items[$c]['options'][1]['value'] = 2;
        $items[$c]['options'][1]['headline'] = 'جلسات با مقدمات و تشریفات';
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "meetingManageInterBoss";
        $items[$c]['title'] = "نیاز به ورود مدیریت";
        $items[$c]['style'] = "style='width: 57%;'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "نمی باشد";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]['title'] = "مدیریت عامل";
        $items[$c]['options'][1]['value'] = 4;
        $items[$c]['options'][2]['title'] = "معاونت بازرگانی";
        $items[$c]['options'][2]['value'] = 20;
        $items[$c]['options'][3]['title'] = "مدیر کارخانه";
        $items[$c]['options'][3]['value'] = $modirID;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "meetingManageSubject";
        $items[$c]['title'] = "موضوع جلسه";
        $items[$c]['placeholder'] = "موضوع";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "meetingManageReason";
        $items[$c]['title'] = "علت تشکیل جلسه";
        $items[$c]['placeholder'] = "علت";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "meetingManageHeadline";
        $items[$c]['title'] = "سرتیتر عمده موضوعات جلسه";
        $items[$c]['placeholder'] = "سرتیتر ها";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "meetingManageMeetingGroup";
        $items[$c]['title'] = "دسته بندی جلسه";
        $items[$c]['style'] = "style='width: 57%;'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$CountGroups;$i++){
            $items[$c]['options'][$i+1]["title"] = $grouping[$i]['groupName'];
            $items[$c]['options'][$i+1]["value"] = $grouping[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "meetingManageRelationship";
        $items[$c]['title'] = "ارتباط کاربران با یکدیگر";
        $items[$c]['options'][0]['title'] = "محرمانه بودن فرایندها";
        $items[$c]['options'][0]['value'] = 0;
        $items[$c]['options'][1]['title'] = "تا هماهنگی های قبل گردهمایی";
        $items[$c]['options'][1]['value'] = 1;
        $items[$c]['options'][2]['title'] = "عادی";
        $items[$c]['options'][2]['value'] = 2;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "meetingManageMembers";
        $items[$c]['onchange'] = "onchange=resetSubstituteMembers()";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "اعضا جلسه";
        $items[$c]['options'] = array();
        for ($i=0;$i<$cntu;$i++){
            if (intval($users[$i]['RowID']) == intval($_SESSION['userid'])){// || intval($users[$i]['RowID']) == 4 || intval($users[$i]['RowID']) == 20 || intval($users[$i]['RowID']) == intval($res[0]['user_id'])){
                continue;
            }
            $items[$c]['options'][$i]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
            $items[$c]['options'][$i]["value"] = $users[$i]['RowID'];
        }
        $items[$c]['options'] = array_values($items[$c]['options']);
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "meetingManageEqualManager";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "مدیر/مدیران موازی";
        $items[$c]['options'] = array();
        for ($i=0;$i<$cntu;$i++){
            if (intval($users[$i]['RowID']) == intval($_SESSION['userid'])){
                continue;
            }
            $items[$c]['options'][$i]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
            $items[$c]['options'][$i]["value"] = $users[$i]['RowID'];
        }
        $items[$c]['options'] = array_values($items[$c]['options']);
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "meetingManageGatheringDate";
        $items[$c]['style'] = "style='width: 57%;'";
        $items[$c]['title'] = "تاریخ پیشنهادی گردهمایی برای جلسه";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "meetingManageGatheringTime";
        $items[$c]['style'] = "style='width:57%;float:right;'";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['data-format'] = "data-format='hh:mm'";
        $items[$c]['add-on'] = "yes";
        $items[$c]['title'] = "ساعت پیشنهادی گردهمایی برای جلسه";
        $items[$c]['placeholder'] = "ساعت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "meetingManageGatheringPlace";
        $items[$c]['title'] = "مکان پیشنهادی گردهمایی برای جلسه";
        $items[$c]['placeholder'] = "مکان";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "meetingManageSubstitute";
        $items[$c]['title'] = "امکان معرفی جانشین برای اعضا";
        $items[$c]['options'][0]['title'] = "وجود دارد";
        $items[$c]['options'][0]['value'] = 0;
        $items[$c]['options'][1]['title'] = "وجود ندارد";
        $items[$c]['options'][1]['value'] = 1;
        $items[$c]['options'][2]['title'] = "انتخاب اعضا مجاز";
        $items[$c]['options'][2]['value'] = 2;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "meetingManageSubstituteMembers";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "اعضا مجاز به انتخاب جانشین";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "meetingManageRequirements";
        $items[$c]['title'] = "نیازمندی های گردهمایی و توضیحات تکمیلی";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "meetingManageDescription";
        $items[$c]['title'] = "شرح کل جلسه";
        $items[$c]['placeholder'] = "شرح";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "meetingManageHiddenMid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateFirstMeeting";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createMeetingModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF CREATE Meeting MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ EDIT CREATE Meeting Group MODAL ++++++++++++++++++++++++++++++++
        $modalID = "meetingGroupManageModal";
        $modalTitle = "فرم ایجاد/ویرایش دسته بندی جلسات";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "meetingGroupManageName";
        $items[$c]['title'] = "نام دسته بندی";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "meetingGroupManageHiddenMid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateMeetingGroup";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateMeetingGroupModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE Meeting Group MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++ meeting comment Manage Info Modal ++++++++++++++++++++++
        $modalID = "meetingCommentManageInfoModal";
         $modalTitle = '<span>نظرات</span><span id="deadlineToStartMeeting"></span>';
        $style = 'style="max-width: 1240px;"';
        $ShowDescription = 'meeting-manage-comment-Info-body';
        $txt = 'اینجا وضعیت انتخاب جانشین مشخص می شود !!!';

        $c = 0;
        $items = array();
/*        $items[$c]['type'] = "text";
        $items[$c]['id'] = "meetingCommentManageSubject";
        $items[$c]['title'] = "موضوع جلسه";
        $items[$c]['placeholder'] = "موضوع";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "meetingCommentManageReason";
        $items[$c]['title'] = "علت تشکیل جلسه";
        $items[$c]['placeholder'] = "علت";
        $c++;*/

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "meetingCommentManageHeadline";
        $items[$c]['title'] = "سرتیتر عمده موضوعات جلسه";
        $items[$c]['placeholder'] = "سرتیتر ها";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "meetingCommentManageGatheringDate";
        $items[$c]['style'] = "style='width: 40%;'";
        $items[$c]['title'] = "تاریخ پیشنهادی گردهمایی برای جلسه";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "meetingCommentManageGatheringTime";
        $items[$c]['style'] = "style='width:40%;float:right;'";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['data-format'] = "data-format='hh:mm'";
        $items[$c]['add-on'] = "yes";
        $items[$c]['title'] = "ساعت پیشنهادی گردهمایی برای جلسه";
        $items[$c]['placeholder'] = "ساعت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "meetingCommentManageGatheringPlace";
        $items[$c]['title'] = "مکان پیشنهادی گردهمایی برای جلسه";
        $items[$c]['placeholder'] = "مکان";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "meetingCommentManageRequirements";
        $items[$c]['title'] = "نیازمندی های گردهمایی و توضیحات تکمیلی";
        $items[$c]['placeholder'] = "نیازمندی ها";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "meetingCommentManageDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "meetingCommentManageHiddenMid";

        $footerBottons = array();
        /*$footerBottons[0]['title'] = "ثبت نظر";
        $footerBottons[0]['jsf'] = "doCreateFirstMeetingComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "اصلاحیه";
        $footerBottons[1]['jsf'] = "editFirstMeeting";
        $footerBottons[1]['type'] = "btn-warning";
        $footerBottons[1]['data-dismiss'] = "No";
        $footerBottons[2]['title'] = "وضعیت جانشین";
        $footerBottons[2]['jsf'] = "createSubstituteStatus";
        $footerBottons[2]['type'] = "btn-success";
        $footerBottons[2]['data-dismiss'] = "No";
        $footerBottons[3]['title'] = "شروع جلسه";
        $footerBottons[3]['jsf'] = "startFirstMeeting";
        $footerBottons[3]['type'] = "btn-danger";
        $footerBottons[3]['data-dismiss'] = "No";
        $footerBottons[4]['title'] = "حذف/اضافه عضو";
        $footerBottons[4]['jsf'] = "addRemoveMember";
        $footerBottons[4]['type'] = "btn-info";
        $footerBottons[4]['data-dismiss'] = "No";
        $footerBottons[5]['title'] = "میهمان";
        $footerBottons[5]['jsf'] = "addGuestMember";
        $footerBottons[5]['type'] = "btn-secondary";
        $footerBottons[5]['data-dismiss'] = "No";
        $footerBottons[6]['title'] = "بستن";
        $footerBottons[6]['type'] = "dismis";*/
		//***************************************************************************
		//***************************************************************************

        //if($this->is_meeting_admin){
            $footerBottons = array();
            $footerBottons[0]['title'] = "ثبت نظر";
            $footerBottons[0]['jsf'] = "doCreateFirstMeetingComment";
            $footerBottons[0]['type'] = "btn";
            $footerBottons[0]['data-dismiss'] = "No";
            $footerBottons[1]['title'] = "اصلاحیه";
            $footerBottons[1]['jsf'] = "editFirstMeeting";
            $footerBottons[1]['type'] = "btn-warning";
            $footerBottons[1]['data-dismiss'] = "No";
            $footerBottons[2]['title'] = "وضعیت جانشین";
            $footerBottons[2]['jsf'] = "createSubstituteStatus";
            $footerBottons[2]['type'] = "btn-success";
            $footerBottons[2]['data-dismiss'] = "No";
            $footerBottons[3]['title'] = "صدور دستور گردهمایی ";
            $footerBottons[3]['jsf'] = "startFirstMeeting";
            $footerBottons[3]['type'] = "btn-danger";
            $footerBottons[3]['data-dismiss'] = "No";
            $footerBottons[4]['title'] = "حذف/اضافه عضو";
            $footerBottons[4]['jsf'] = "addRemoveMember";
            $footerBottons[4]['type'] = "btn-info";
            $footerBottons[4]['data-dismiss'] = "No";
            $footerBottons[5]['title'] = "میهمان";
            $footerBottons[5]['jsf'] = "addGuestMember";
            $footerBottons[5]['type'] = "btn-secondary";
            $footerBottons[5]['data-dismiss'] = "No";
            $footerBottons[6]['title'] = "بستن";
            $footerBottons[6]['type'] = "dismis";
      //  }
        /*else{
            $footerBottons = array();
            $footerBottons[0]['title'] = "ثبت نظر";
            $footerBottons[0]['jsf'] = "doCreateFirstMeetingComment";
            $footerBottons[0]['type'] = "btn";
            $footerBottons[0]['data-dismiss'] = "No";
            $footerBottons[1]['title'] = "بستن";
            $footerBottons[1]['type'] = "dismis"; 
        }*/
        $meetingCommentManageInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$txt,'',$style,$ShowDescription);
        //+++++++++++++++++ End meeting comment Manage Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ create Substitute Status MODAL ++++++++++++++++++++++++++++++++
        $modalID = "createSubstituteStatusModal";
        $modalTitle = "فرم انتخاب جانشین";
        $style = 'style="max-width: 870px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "createSubstituteStatusType";
        $items[$c]['title'] = "وضعیت انتخاب جانشین";
        $items[$c]['options'][0]['title'] = "جانشین انتخاب نمی کنم";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "خارج می شوم";
        $items[$c]['options'][1]['value'] = 2;
        $items[$c]['options'][1]['headline'] = 'در صورت انتخاب این گزینه و زدن کلید ارسال فرم در حالت ارسال به جانشین بطور کل از فرایند حذف خواهد شد و فرم در حالتی که به ایشان ارسال شده بود برای جانشین ایشان ارسال می شود';
        $items[$c]['options'][2]['title'] = "رصد خواهم کرد";
        $items[$c]['options'][2]['value'] = 3;
        $items[$c]['options'][2]['headline'] = 'تمامی اطلاعات جانشین را بصورت فقط دیداری می بیند';
/*        $items[$c]['options'][3]['title'] = "پیوستن بعد از تایید مدیر";
        $items[$c]['options'][3]['value'] = 4;
        $items[$c]['options'][3]['headline'] = 'هر زمان مدیر تایید برگشت به جلسه را بزند همزمان کلیه فرایندی که تا به الان توسط جانشین انجام می شده است به عضو منتقل می شود';*/
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createSubstituteStatusUser";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "جانشین";
        $items[$c]['LimitNumSelections'] = 1;
/*        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "createSubstituteStatusHiddenFMid";*/

        $footerBottons = array();
        $footerBottons[0]['title'] = "ثبت";
        $footerBottons[0]['jsf'] = "doCreateSubstituteStatus";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createSubstituteStatus = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF create Substitute Status MODAL +++++++++++++++++++++++++++++++++++++
		//***************************************************************************************************************

        $modalID = "ProceedingsModal";
        $modalTitle = "فرم درج شرح جلسه";
        $style = 'style="max-width: 870px;"';
        $c = 0;
        $items = array();
        $items[$c]['type'] = "textarea";
        $items[$c]['name'] = "ProceedingsComment";
        $items[$c]['placeholder'] = "شرح جلسه";
        $items[$c]['id'] = "ProceedingsComment";
        $items[$c]['title'] = "دستور جلسه";
        $footerBottons = array();
        $footerBottons[0]['title'] = "ثبت";
        $footerBottons[0]['jsf'] = "doCreateProceedingsComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createProceedingsComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);

		//***************************************************************************************************************
        //++++++++++++++++++++++++++++++ start First Meeting MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "startFirstMeetingModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به شروع جلسه مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "startFirstMeetingIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doStartFirstMeeting";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $startFirstMeeting = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++ END OF start First Meeting MODAL ++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ create Meeting Jobs MODAL ++++++++++++++++++++++++++++++++
        $modalID = "createMeetingJobsModal";
        $modalTitle = "فرم تعیین مسئولیت";
        $style = 'style="max-width: 1240px;"';
        $ShowDescription = 'createMeetingJobsBody';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "createMeetingJobsMembers";
        $items[$c]['title'] = "عضو جلسه";
        $items[$c]['style'] = "style='width: 57%;'";
        $items[$c]['options'] = array();
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "createMeetingJobsValidDate";
        $items[$c]['style'] = "style='width: 57%;'";
        $items[$c]['title'] = "مهلت انجام کار";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "createMeetingJobsDescription";
        $items[$c]['title'] = "شرح مسئولیت";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "createMeetingJobsHiddenMid";

        $topperBottons = array();
        $topperBottons[0]['title'] = "تایید";
        $topperBottons[0]['jsf'] = "doCreateMeetingJobs";
        $topperBottons[0]['type'] = "btn";
        $topperBottons[0]['data-dismiss'] = "No";
        $topperBottons[1]['title'] = "انصراف";
        $topperBottons[1]['type'] = "dismis";
        $createMeetingJobsModal = $ut->getHtmlModal($modalID,$modalTitle,$items,array(),'','',$style,$ShowDescription,'','',$topperBottons);
        //++++++++++++++++++++++++++++++ END OF create Meeting Jobs MODAL +++++++++++++++++++++++++++++++++++++
        //------------------------------------------------------
        //++++++++++++++++++++++++++++++++++ create edit Meeting Jobs MODAL ++++++++++++++++++++++++++++++++
        $items=[];
        $modalID = "editMeetingJobsModal";
        $modalTitle = "فرم ویرایش مسئولیت";
        $style = 'style="max-width: 600px;"';
        $ShowDescription = 'createEditMeetingJobsBody';

        $m = 0;
        $items[$m]['type'] = "text";
        $items[$m]['id'] = "editMeetingJobsValidDate";
        $items[$m]['title'] = "مهلت انجام کار";
        $items[$m]['placeholder'] = "تاریخ";
        $m++;

        $items[$m]['type'] = "textarea";
        $items[$m]['id'] = "reasonChangeMeetingJobsDescription";
        $items[$m]['title'] = " علت تغییر مهلت انجام ";
        $items[$m]['style'] = "style='width: 100%;'";
        $items[$m]['placeholder'] = "متن";
        $m++;

        $items[$m]['type'] = "hidden";
        $items[$m]['id'] = "editMeetingJobsHiddenMid";

        $topperBottons = array();
        $topperBottons[0]['title'] = "تایید";
        $topperBottons[0]['jsf'] = "do_edit_meeting_jobs_item";
        $topperBottons[0]['type'] = "btn";
        $topperBottons[0]['data-dismiss'] = "No";
        $topperBottons[1]['title'] = "انصراف";
        $topperBottons[1]['type'] = "dismis";
        $editMeetingJobsModal = $ut->getHtmlModal($modalID,$modalTitle,$items,array(),'','',$style,$ShowDescription,'','',$topperBottons);
        //++++++++++++++++++++++++++++++ END OF create edit Meeting Jobs MODAL +++++++++++++++++++++++++++++++++++++
        //------------------------------------------------------
        //++++++++++++++++++++++++++++++++++ create Meeting Prerequisite MODAL ++++++++++++++++++++++++++++++++
        $modalID = "createMeetingPrerequisiteModal";
        $modalTitle = "تعیین پیش نیاز های این کار";
        $style = 'style="max-width: 1050px;"';
        $ShowDescription = 'createMeetingPrerequisiteBody';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "createMeetingPrerequisiteHiddenJobID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ثبت";
        $footerBottons[0]['jsf'] = "doCreateMeetingPrerequisite";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createMeetingPrerequisite = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF create Meeting Prerequisite MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ delete Meeting Work Report Modal ++++++++++++++++++++++++++++++++++++++++
        $modalID = "deleteMeetingJobsModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به حذف این مسئولیت مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "deleteMeetingJobsIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doDeleteMeetingJobs";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $deleteMeetingJobs = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++ END OF delete Meeting Work Report Modal ++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ meeting Jobs List MODAL ++++++++++++++++++++++++++++++++
        $modalID = "meetingJobsListModal";
        $modalTitle = "لیست مسئولیت ها";
        $style = 'style="max-width: 97vw;"';
        $ShowDescription = 'meetingJobsListBody';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "meetingJobsListHiddenMID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $meetingJobsList = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF meeting Jobs List MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ create Meeting WorkReport MODAL ++++++++++++++++++++++++++++++++
        $modalID = "createMeetingWorkReportModal";
        $modalTitle = "گزارش کار";
        $style = 'style="max-width: 1024px;"';
        $ShowDescription = 'createMeetingWorkReportBody';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "createMeetingWorkReportDescription";
        $items[$c]['title'] = "شرح کار";
        $items[$c]['placeholder'] = "متن";
        $c++;

       $items[$c]['type'] = "number";
        $items[$c]['id'] = "createMeetingWorkReportPercent";
        $items[$c]['title'] = "درصد پیشرفت کار";
        $items[$c]['placeholder'] = "درصد";
		$items[$c]['onchange'] = "changeProgressValue(event)";
		$items[$c]['value'] = $_SESSION['percent'];
		
        $c++;
		
		$items[$c]['type'] = "progress";
        $items[$c]['value'] = "";
        $items[$c]['progress_id'] = "createMeetingWorkReportPercent_g";
        $items[$c]['id'] = "createMeetingWorkReportPercent_progress";
		$items[$c]['placeholder'] = "درصد";
        $c++;
		
        $items[$c]['type'] = "checkbox";
        $items[$c]['id'] = "createMeetingWorkReportFinaltick";
        $items[$c]['title'] = "اتمام کار";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "createMeetingWorkReportHiddenJobID";

        $topperBottons = array();
        $topperBottons[0]['title'] = "ثبت گزارش";
        $topperBottons[0]['jsf'] = "doCreateMeetingWorkReport";
        $topperBottons[0]['type'] = "btn";
        $topperBottons[0]['data-dismiss'] = "No";
        $topperBottons[1]['title'] = "تایید نهایی";
        $topperBottons[1]['jsf'] = "confirmMeetingJob";
        $topperBottons[1]['type'] = "btn-success";
        $topperBottons[1]['data-dismiss'] = "No";
        $topperBottons[2]['title'] = "عدم تایید";
        $topperBottons[2]['jsf'] = "noConfirmMeetingJob";
        $topperBottons[2]['type'] = "btn-danger";
        $topperBottons[2]['data-dismiss'] = "No";
        $topperBottons[3]['title'] = "انصراف";
        $topperBottons[3]['type'] = "dismis";
        $createMeetingWorkReport = $ut->getHtmlModal($modalID,$modalTitle,$items,array(),'','',$style,$ShowDescription,'','',$topperBottons);
        //++++++++++++++++++++++++++++++ END OF create Meeting WorkReport MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ delete Meeting Work Report Modal ++++++++++++++++++++++++++++++++++++++++
        $modalID = "deleteMeetingWorkReportModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به حذف این گزارش مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "deleteMeetingWorkReportIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doDeleteMeetingWorkReport";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $deleteMeetingWorkReport = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++ END OF delete Meeting Work Report Modal ++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ confirm Meeting Job Modal ++++++++++++++++++++++++++++++++++++++++
        $modalID = "confirmMeetingJobModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به تایید نهایی این مسئولیت مطمئن هستید؟ ";
        $items = array();

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doConfirmMeetingJob";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $confirmMeetingJob = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++ END OF confirm Meeting Work Report Modal ++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ no confirm Meeting Job Modal ++++++++++++++++++++++++++++++++++++++++
        $modalID = "noConfirmMeetingJobModal";
        $modalTitle = "رد کردن اتمام کار";
        $style = 'style="max-width: 651px;"';

        $items = array();
        $c = 0;
        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "noConfirmMeetingJobDescription";
        $items[$c]['title'] = "علت رد اتمام کار";
        $items[$c]['placeholder'] = "متن";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ثبت";
        $footerBottons[0]['jsf'] = "doNoConfirmMeetingJob";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $noConfirmMeetingJob = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF no confirm Meeting Work Report Modal ++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ comment Meeting Work Report MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "commentMeetingWorkReportModal";
        $modalTitle = "ثبت نظر";
        $style = 'style="max-width: 715px;"';
        $ShowDescription = 'commentMeetingWorkReportBody';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "commentMeetingWorkReportDescription";
        $items[$c]['title'] = "نظر خود را بنویسید";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "commentMeetingWorkReportIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "ثبت";
        $footerBottons[0]['jsf'] = "doCommentMeetingWorkReport";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $commentMeetingWorkReport = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++END OF comment Meeting Work Report ++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ close First Meeting MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "closeFirstMeetingModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به بستن این جلسه مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "closeFirstMeetingIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCloseFirstMeeting";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $closeFirstMeeting = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++ END OF close First Meeting MODAL ++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ add Remove Member MODAL ++++++++++++++++++++++++++++++++
        $modalID = "addRemoveMemberModal";
        $modalTitle = "حذف و اضافه عضو";

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "addRemoveMembersSelect";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "حذف و اضافه عضو";
        $items[$c]['options'] = array();
        for ($i=0;$i<$cntu;$i++){
            if (intval($users[$i]['RowID']) == intval($_SESSION['userid']) || intval($users[$i]['RowID']) == 4 || intval($users[$i]['RowID']) == 20 || intval($users[$i]['RowID']) == intval($res[0]['user_id'])){
                continue;
            }
            $items[$c]['options'][$i]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
            $items[$c]['options'][$i]["value"] = $users[$i]['RowID'];
        }
        $items[$c]['options'] = array_values($items[$c]['options']);

        $footerBottons = array();
        $footerBottons[0]['title'] = "ثبت";
        $footerBottons[0]['jsf'] = "doAddRemoveMember";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $addRemoveMember = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF add Remove Member MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ add Guest Member MODAL ++++++++++++++++++++++++++++++++
        $modalID = "addGuestMemberModal";
        $modalTitle = "افزودن کاربر میهمان";

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "addGuestMembersRole";
        $items[$c]['style'] = "style='width: 77%;'";
        $items[$c]['title'] = "نقش کاربر";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = 'ناظر';
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = 'مدیر موازی';
        $items[$c]['options'][2]["value"] = 2;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "addGuestMembersSelect";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "کاربر میهمان";
        $items[$c]['options'] = array();

        $footerBottons = array();
        $footerBottons[0]['title'] = "ثبت";
        $footerBottons[0]['jsf'] = "doAddGuestMember";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $addGuestMember = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF add Guest Member MODAL +++++++++++++++++++++++++++++++++++++
         //++++++++++++++++++++++++++++++++++ meeting Jobs history List MODAL ++++++++++++++++++++++++++++++++
        $modalID = "meetingJobsHistoryListModal";
        $modalTitle = "سوابق تغییر تاریخ انجام کار";
        $style = 'style="max-width: 700px;"';
        $ShowDescription = 'meetingJobsHistoryListBody';

        $c = 0;
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $meetingHistoryJobsList = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END meeting Jobs history List MODAL +++++++++++++++++++++++++++++++++++++
        $htm .= $editCreateMeetingGroupModal;
        $htm .= $meetingCommentManageInfo;
        $htm .= $createSubstituteStatus;
        $htm .= $createProceedingsComment;
        $htm .= $startFirstMeeting;
        $htm .= $createMeetingModal;
        $htm .= $createMeetingJobsModal;
        $htm .= $createMeetingPrerequisite;
        $htm .= $deleteMeetingJobs;
        $htm .= $meetingJobsList;
        $htm .= $createMeetingWorkReport;
        $htm .= $deleteMeetingWorkReport;
        $htm .= $confirmMeetingJob;
        $htm .= $noConfirmMeetingJob;
        $htm .= $commentMeetingWorkReport;
        $htm .= $closeFirstMeeting;
        $htm .= $addRemoveMember;
        $htm .= $addGuestMember;
        $htm .= $editMeetingJobsModal;
        $htm .= $meetingHistoryJobsList;
        $send = array($htm,$access);
        return $send;
    }

    //++++++++++++++++++++++ جلسات +++++++++++++++++++++++

    public function getFirstMeetingList($unCode,$subject,$grouping,$sender,$closed,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if(strlen(trim($unCode)) > 0){
            $w[] = '`unCode` LIKE "%'.$unCode.'%" ';
        }
        if(strlen(trim($subject)) > 0){
            $w[] = '`subject` LIKE "%'.$subject.'%" ';
        }
        if(intval($grouping) > 0){
            $w[] = '`grouping`='.$grouping.' ';
        }
        if(intval($sender) == 1){
            $w[] = '`manager1`='.$_SESSION['userid'].' ';
        }elseif (intval($sender) == 2){
            $w[] = '`manager1`!='.$_SESSION['userid'].' ';
        }
        $w[] = '`closed`='.$closed;

        $sql = "SELECT * FROM `meeting`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC ";
        $res = $db->ArrayQuery($sql);

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $observers = explode(',',$res[$y]['observers']);
            if (!in_array($_SESSION['userid'],$observers)){
                continue;
            }
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['enterBoss'] = (intval($res[$y]['enterBoss']) == 0 ? 'دارد' : 'ندارد');
            $finalRes[$y]['subject'] = $res[$y]['subject'];
            $finalRes[$y]['reason'] = $res[$y]['reason'];
            $finalRes[$y]['headline'] = $res[$y]['headline'];

            $qry = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$y]['manager1']}";
            $rsq = $db->ArrayQuery($qry);
            $finalRes[$y]['manager'] = $rsq[0]['fname'].' '.$rsq[0]['lname'];
			$this->meeting_mananger=[$res[$y]['manager1']];
			//error_log('mananger:');
			//error_log(print_r($this->meeting_mananger,true));
            $query = "SELECT `groupName` FROM `meeting_grouping` WHERE `RowID`={$res[$y]['grouping']}";
            $rst = $db->ArrayQuery($query);
            $finalRes[$y]['grouping'] = $rst[0]['groupName'];

            switch (intval($res[$y]['relationship'])){
                case 0;
                    $finalRes[$y]['relationship'] = 'محرمانه بودن فرایندها';
                    break;
                case 1;
                    $finalRes[$y]['relationship'] = 'تا هماهنگی های قبل گردهمایی';
                    break;
                case 2;
                    $finalRes[$y]['relationship'] = 'عادی';
                    break;
            }

            $users = array();
            $query1 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID` IN ({$res[$y]['members']})";
            $rst1 = $db->ArrayQuery($query1);
            $cnt = count($rst1);
            for ($i=0;$i<$cnt;$i++){
                $users[] = $rst1[$i]['fname'].' '.$rst1[$i]['lname'];
            }
            $users = implode(' - ',$users);
            $finalRes[$y]['users'] = $users;
            if(!empty($res[$y]['manager2'])){
                $query2 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$y]['manager2']}";
                $rst2 = $db->ArrayQuery($query2);
                $finalRes[$y]['manager2'] = $rst2[0]['fname'].' '.$rst2[0]['lname'];
                }
            else
            {
                $finalRes[$y]['manager2'] = '';
            }
            
            $finalRes[$y]['gatheringDate'] = $ut->greg_to_jal($res[$y]['gatheringDate']);
            $finalRes[$y]['gatheringTime'] = $res[$y]['gatheringTime'];
            $finalRes[$y]['gatheringPlace'] = $res[$y]['gatheringPlace'];

            switch (intval($res[$y]['substitute'])){
                case 0;
                    $finalRes[$y]['substitute'] = 'وجود دارد';
                    break;
                case 1;
                    $finalRes[$y]['substitute'] = 'وجود ندارد';
                    break;
                case 2;
                    $finalRes[$y]['substitute'] = 'انتخاب اعضا مجاز';
                    break;
            }

            $finalRes[$y]['bgColor'] = (intval($res[$y]['start']) == 0 ? 'table-danger' : 'table-success');

            $substituteMembers = array();
            $query3 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID` IN ({$res[$y]['substituteMembers']})";
            $rst3 = $db->ArrayQuery($query3);
            $ccnt = count($rst3);
            for ($i=0;$i<$ccnt;$i++){
                $substituteMembers[] = $rst3[$i]['fname'].' '.$rst3[$i]['lname'];
            }
            $substituteMembers = implode(' - ',$substituteMembers);
            $finalRes[$y]['substituteMembers'] = $substituteMembers;
            $finalRes[$y]['requirements'] = $res[$y]['requirements'];
            $finalRes[$y]['unCode'] = $res[$y]['unCode'];
            $finalRes[$y]['disabled'] = (intval($res[$y]['mType']) == 1 ? 'disabled' : '');
            $finalRes[$y]['mType'] = (intval($res[$y]['mType']) == 1 ? 'Fast' : 'Normal');
        }
        return array_values($finalRes);
    }

    public function getFirstMeetingListCountRows($unCode,$subject,$grouping,$sender,$closed){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $w = array();
        if(strlen(trim($unCode)) > 0){
            $w[] = '`unCode` LIKE "%'.$unCode.'%" ';
        }
        if(strlen(trim($subject)) > 0){
            $w[] = '`subject` LIKE "%'.$subject.'%" ';
        }
        if(intval($grouping) > 0){
            $w[] = '`grouping`='.$grouping.' ';
        }
        if(intval($sender) == 1){
            $w[] = '`manager1`='.$_SESSION['userid'].' ';
        }elseif (intval($sender) == 2){
            $w[] = '`manager1`!='.$_SESSION['userid'].' ';
        }
        $w[] = '`closed`='.$closed;

        $sql = "SELECT `RowID`,`observers` FROM `meeting`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $iterator = 0;
        for($y=0;$y<$listCount;$y++){
            $observers = explode(',',$res[$y]['observers']);
            if (!in_array($_SESSION['userid'],$observers)){
                continue;
            }
            $iterator++;
        }
        return $iterator;
    }

    public function substituteMembers($members){
        $db = new DBi();
        $sql = "SELECT `RowID`,`fname`,`lname` FROM `users` WHERE `RowID` IN ({$members})";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function firstMeetingInfo($fmID){
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT * FROM `meeting` WHERE `RowID`=".$fmID;
        $res = $db->ArrayQuery($sql);

        $sql1 = "SELECT `uid` FROM `meeting_members` WHERE `meetingID`={$fmID}";
        $res1 = $db->ArrayQuery($sql1);
        $cnt = count($res1);
        $uids = array();
        for ($i=0;$i<$cnt;$i++){
            $uids[] = $res1[$i]['uid'];
        }
        $uids = implode(',',$uids);

        if(count($res) == 1){
            $res = array("fmID"=>$fmID,"modirID"=>$res[0]['modirID'],"mType"=>$res[0]['mType'],
                         "subject"=>$res[0]['subject'],"reason"=>$res[0]['reason'],
                         "headline"=>$res[0]['headline'],"grouping"=>$res[0]['grouping'],
                         "relationship"=>$res[0]['relationship'],"manager2"=>$res[0]['manager2'],
                         "gatheringDate"=>$ut->greg_to_jal($res[0]['gatheringDate']),
                         "gatheringTime"=>$res[0]['gatheringTime'],"gatheringPlace"=>$res[0]['gatheringPlace'],
                         "substitute"=>$res[0]['substitute'],"substituteMembers"=>$res[0]['substituteMembers'],
                         "requirements"=>$res[0]['requirements'],"members"=>$uids,"description"=>$res[0]['description']
            );
            return $res;
        }else{
            return false;
        }
    }

    public function createFirstMeeting($mType,$interBoss,$subject,$reason,$headline,$meetingGroup,$relationship,$members,$equalManager,$gDate,$gTime,$gPlace,$substitute,$substituteMembers,$requirements,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $gatheringDate = $gDate;
        $gDate = $ut->jal_to_greg($gDate);
        $datetostring = substr($ut->greg_to_jal(str_replace('/','-',date('Y/m/d'))),2,2);
        $unCode = $datetostring.rand(10000,99999).substr(time(), -4);

        $query = "SELECT `user_id` FROM `access_table` WHERE `item_id`=152";   // مدیریت فناوری اطلاعات
        $rst = $db->ArrayQuery($query);

        $members1 = $members;
        $members = explode(',',$members);
        $ccnt = count($members);
        if (intval($mType) == 1){  // حالت سریع
            $observers = $members1.','.$_SESSION['userid'].',1';
           // //$//ut->fileRecorder('observers:'.$observers);
            $enterBoss = 0;
            $relationship = 2;
            $equalManager = '';
            $substitute = 1;
            $substituteMembers = '';

            $sql = "INSERT INTO `meeting` (`enterBoss`,`subject`,`reason`,`headline`,`grouping`,`relationship`,`manager1`,`manager2`,`gatheringDate`,`gatheringTime`,`gatheringPlace`,`substitute`,`substituteMembers`,`requirements`,`unCode`,`observers`,`modirID`,`mType`,`description`,`start`) VALUES ({$enterBoss},'{$subject}','{$reason}','{$headline}',{$meetingGroup},{$relationship},{$_SESSION['userid']},'{$equalManager}','{$gDate}','{$gTime}','{$gPlace}',{$substitute},'{$substituteMembers}','{$requirements}','{$unCode}','{$observers}',{$interBoss},{$mType},'{$desc}',1)";
            $db->Query($sql);
            $id = $db->InsertrdID();
            if(intval($id) > 0){
                $sql1 = "INSERT INTO `meeting_comment` (`meetingID`,`uid`,`subject`,`reason`,`headline`,`gatheringDate`,`gatheringTime`,`gatheringPlace`,`requirements`,`generalDescription`) VALUES ({$id},{$_SESSION['userid']},'{$subject}','{$reason}','{$headline}','{$gDate}','{$gTime}','{$gPlace}','{$requirements}','{$desc}')";
                $db->Query($sql1);
                for ($j=0;$j<$ccnt;$j++){
                    $sql2 = "INSERT INTO `meeting_members` (`meetingID`,`uid`) VALUES ({$id},{$members[$j]})";
                    $db->Query($sql2);

                    $sqls1 = "SELECT `phone` FROM `users` WHERE `RowID`={$members[$j]}";
                    $rsts1 = $db->ArrayQuery($sqls1);
                   // //$//ut->fileRecorder('Send fast');
                   //$ut->sendToMeetingMembers($rsts1[0]['phone'],$subject,$gatheringDate,$gTime,$gPlace);
                }
                return true;
            }else{
                return false;
            }
        }else{  // حالت نرمال
           // //$//ut->fileRecorder('senduuuuuuuuu');
            if (strlen(trim($equalManager)) > 0){
               // //$//ut->fileRecorder('senduuuuuuuuu:'.$equalManager);
                $equalManager = explode(',',$equalManager);
                $cnt = count($equalManager);
                for ($i=0;$i<$cnt;$i++){
                    if (in_array($equalManager[$i],$members)){
                        $res = "مدیر موازی نمی تواند از بین اعضا جلسه باشد !";
                        $out = "false";
                        response($res,$out);
                        exit;
                    }
                    if (intval($equalManager[$i]) == intval($interBoss)){
                        $res = "مدیر موازی نمی تواند با مدیر ارشد انتخابی یکسان باشد !";
                        $out = "false";
                        response($res,$out);
                        exit;
                    }
                }
                $equalManager = implode(',',$equalManager);
            }       
            $observers = $members1.','.$equalManager.','.$rst[0]['user_id'].','.$_SESSION['userid'].',1';
           ////$//ut->fileRecorder('observersNormal:'.$observers);
            if (intval($interBoss) > 0){
                $observers = $observers.','.$interBoss;
                $enterBoss = 1;
            }else{
                $enterBoss = 0;
            }
            $observers = explode(',',$observers);
            $observers = array_unique($observers);
            $observers = implode(',',$observers);
            $observers=str_ireplace(',,',',',$observers);
            ////$//ut->fileRecorder('send normal');
            $sqp = "SELECT `phone` FROM `users` WHERE `RowID` IN ({$observers})";
           // //$//ut->fileRecorder('send normal:');
            $rsp = $db->ArrayQuery($sqp);
          //  //$//ut->fileRecorder('send normal:'.$sqp);
            $cntp = count($rsp);
           // //$//ut->fileRecorder('send cntp:'.$cntp);
            for ($i=0;$i<$cntp;$i++){
                ////$//ut->fileRecorder('Send normal---------');
               // $ut->sendAllBudgetElements($rsp[$i]['phone'],'جلسات');
            }

            $sql = "INSERT INTO `meeting` (`enterBoss`,`subject`,`reason`,`headline`,`grouping`,`relationship`,`manager1`,`manager2`,`gatheringDate`,`gatheringTime`,`gatheringPlace`,`substitute`,`substituteMembers`,`requirements`,`unCode`,`observers`,`modirID`,`mType`,`description`) VALUES ({$enterBoss},'{$subject}','{$reason}','{$headline}',{$meetingGroup},{$relationship},{$_SESSION['userid']},'{$equalManager}','{$gDate}','{$gTime}','{$gPlace}',{$substitute},'{$substituteMembers}','{$requirements}','{$unCode}','{$observers}',{$interBoss},{$mType},'{$desc}')";
            $db->Query($sql);
            $id = $db->InsertrdID();
            if(intval($id) > 0){
                $sql1 = "INSERT INTO `meeting_comment` (`meetingID`,`uid`,`subject`,`reason`,`headline`,`gatheringDate`,`gatheringTime`,`gatheringPlace`,`requirements`,`generalDescription`) VALUES ({$id},{$_SESSION['userid']},'{$subject}','{$reason}','{$headline}','{$gDate}','{$gTime}','{$gPlace}','{$requirements}','{$desc}')";
                $db->Query($sql1);
                for ($j=0;$j<$ccnt;$j++){
                    $sql2 = "INSERT INTO `meeting_members` (`meetingID`,`uid`) VALUES ({$id},{$members[$j]})";
                    $db->Query($sql2);
                }
                return true;
            }else{
                return false;
            }
        }
    }

    public function editFirstMeeting($mType,$mid,$interBoss,$subject,$reason,$headline,$meetingGroup,$relationship,$members,$equalManager,$gDate,$gTime,$gPlace,$substitute,$substituteMembers,$requirements,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $gDate = $ut->jal_to_greg($gDate);

        $sqq = "SELECT `manager1`,`manager2`,`enterBoss`,`mType`,`modirID`,`start` FROM `meeting` WHERE `RowID`={$mid}";
        $rsq = $db->ArrayQuery($sqq);

        $sqql = "SELECT `user_id` FROM `access_table` WHERE `item_id`=155";
        $rstq = $db->ArrayQuery($sqql);

        if (intval($rsq[0]['start']) == 1){
            if(intval($rsq[0]['mType'])==2){
            $res = "دستور شروع این جلسه داده شده است و امکان اصلاح وجود ندارد !";
            $out = "false";
            response($res,$out);
            exit;
            }
            
        }

        switch (intval($rsq[0]['modirID'])){
            case 4:
                $modirTxt = 'مدیریت عامل';
                break;
            case 20:
                $modirTxt = 'معاونت بازرگانی';
                break;
            case intval($rstq[0]['user_id']):
                $modirTxt = 'مدیریت کارخانه';
                break;
        }

        $meetingModir = $rsq[0]['manager1'].','.$rsq[0]['manager2'];
        if (intval($rsq[0]['enterBoss']) == 1){
            if (intval($_SESSION['userid']) !== intval($rsq[0]['modirID'])){
                $res = "فقط ".$modirTxt." می تواند ویرایش نماید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }else{
            $meetingModir = explode(',',$meetingModir);
            if (!in_array($_SESSION['userid'],$meetingModir)){
                $res = "شما جز مدیران این جلسه نمی باشید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }

        $sqls = "SELECT `uid`,`sid` FROM `meeting_members` WHERE `meetingID`={$mid}";
        $rsts = $db->ArrayQuery($sqls);
        $cnts = count($rsts);
        $uids = array();
        for ($i=0;$i<$cnts;$i++){
            $uids[] = $rsts[$i]['uid'];
            if (intval($rsts[$i]['sid']) > 0)
            {
                $sqls1 = "SELECT `RowID` FROM `meeting_comment` WHERE `uid`={$rsts[$i]['sid']} AND `meetingID`={$mid}";
                $rsts1 = $db->ArrayQuery($sqls1);
                if(intval($rsq[0]['mType'])==2){
                    if (count($rsts1) <= 0){
                        $res = "ابتدا باید تمامی نظرات ثبت گردد !";
                        $out = "false";
                        response($res,$out);
                        exit;
                    }
                }
            }else{
                $sqls1 = "SELECT `RowID` FROM `meeting_comment` WHERE `uid`={$rsts[$i]['uid']} AND `meetingID`={$mid}";
                $rsts1 = $db->ArrayQuery($sqls1);
                if(intval($rsq[0]['mType'])==2){
                    if (count($rsts1) <= 0){
                        $res = "ابتدا باید تمامی نظرات ثبت گردد !";
                        $out = "false";
                        response($res,$out);
                        exit;
                    }
                }
            }
        }

        $sids = array();
        $sqm = "SELECT `sid` FROM `meeting_members` WHERE `uid` IN ({$members}) AND `meetingID`={$mid} AND `sid`>0";
        $rsm = $db->ArrayQuery($sqm);
        $cntm = count($rsm);
        for ($j=0;$j<$cntm;$j++){
            $sids[] = $rsm[$j]['sid'];
        }
        $sids1 = implode(',',$sids);

        $query = "SELECT `user_id` FROM `access_table` WHERE `item_id`=152";  // مدیریت فناوری اطلاعات
        $rst = $db->ArrayQuery($query);

        $members = explode(',',$members);
        $ccnt = count($members);
        if (strlen(trim($equalManager)) > 0){
            $equalManager = explode(',',$equalManager);
            $cnt = count($equalManager);
            for ($i=0;$i<$cnt;$i++){
                if (in_array($equalManager[$i],$members) || in_array($equalManager[$i],$sids) ){
                    $res = "مدیر موازی نمی تواند از بین اعضا یا جانشینان باشد !";
                    $out = "false";
                    response($res,$out);
                    exit;
                }
            }
            $equalManager = implode(',',$equalManager);
        }

        for ($i=0;$i<$ccnt;$i++){
            if (in_array(intval($members[$i]),$sids)){
                $res = "اعضا جلسه نمی تواند از بین جانشینان باشد !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }

        $members1 = implode(',',$members);
        $observers = $members1.','.$equalManager.','.$sids1.','.$rst[0]['user_id'].','.$rsq[0]['manager1'].',1';
        if (intval($interBoss) > 0){
            $observers = $observers.','.$rsq[0]['modirID'];
        }
        $observers = explode(',',$observers);
        $observers = array_unique($observers);
        $observers = implode(',',$observers);

        $sql = "UPDATE `meeting` SET `subject`='{$subject}',`reason`='{$reason}',`headline`='{$headline}',`manager1`={$_SESSION['userid']},`manager2`='{$equalManager}',`gatheringDate`='{$gDate}',`gatheringTime`='{$gTime}',`gatheringPlace`='{$gPlace}',`requirements`='{$requirements}',`observers`='{$observers}',`description`='{$desc}',`edited`=1 WHERE `RowID`={$mid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if(intval($aff) > 0){
            $cntu = count($uids);
            for ($j=0;$j<$ccnt;$j++){
                if (in_array(intval($members[$j]),$uids)){
                    continue;
                }
                $sqp = "SELECT `phone` FROM `users` WHERE `RowID`={$members[$j]}";
                $rsp = $db->ArrayQuery($sqp);
                $ut->sendAllBudgetElements($rsp[0]['phone'],'جلسات');

                $sql2 = "INSERT INTO `meeting_members` (`meetingID`,`uid`) VALUES ({$mid},{$members[$j]})";
                $db->Query($sql2);
            }
            for ($j=0;$j<$cntu;$j++){
                if (in_array(intval($uids[$j]),$members)){
                    continue;
                }
                $sql2 = "DELETE FROM `meeting_members` WHERE `uid`={$uids[$j]} AND `meetingID`={$mid}";
                $db->Query($sql2);
            }
            $sql1 = "INSERT INTO `meeting_comment` (`meetingID`,`uid`,`subject`,`reason`,`headline`,`gatheringDate`,`gatheringTime`,`gatheringPlace`,`requirements`,`generalDescription`) VALUES ({$mid},{$_SESSION['userid']},'{$subject}','{$reason}','{$headline}','{$gDate}','{$gTime}','{$gPlace}','{$requirements}','{$desc}')";
            $db->Query($sql1);
            return true;
        }else{
            return false;
        }
    }

    public function getFirstMeetingCommentsHTM($fmID){
        $db = new DBi();
        $ut = new Utility();
		
        $sqq = "SELECT `relationship`,`substitute`,`substituteMembers`,`manager1`,`manager2`,`start`,`modirID`,`supervisor`,`gatheringDate`,`gatheringTime`,`MeetingStartComment`,`mType`,`meeting_close_reason`,closed FROM `meeting` WHERE `RowID`={$fmID}";
        $rsq = $db->ArrayQuery($sqq);
		////$//ut->fileRecorder('rsq::'.$sqq);
        $meetingType = intval($rsq[0]['mType']);
        $manager2 = explode(',',$rsq[0]['manager2']);
		$gatheringDate = $ut->greg_to_jal($rsq[0]['gatheringDate']);
		$gatheringTime = $rsq[0]['gatheringTime'];
		//*****************************************************************
		
		$manager1 = explode(',',$rsq[0]['manager1']);
		$username_id=$_SESSION['userid'];
		////$//ut->fileRecorder('m2:'.print_r($rsq,true));
		$is_meeting_admin=0;
		
		if(count($manager1)>0){
			$is_meeting_admin = in_array($username_id,$manager1)?1:0;
		}
        if($is_meeting_admin==0){
            if(count($manager2)>0)
            {
                $is_meeting_admin = in_array($username_id,$manager2)?1:0;	
            }
        }
		//*****************************************************************
        $supervisor = explode(',',$rsq[0]['supervisor']);
        $substituteMembers = (strlen(trim($rsq[0]['substituteMembers'])) > 0 ? explode(',',$rsq[0]['substituteMembers']) : array());
        $substitute = 0;

        if ( (intval($_SESSION['userid']) == intval($rsq[0]['manager1'])) || (in_array(intval($_SESSION['userid']),$manager2)) || (intval($_SESSION['userid']) == intval($rsq[0]['modirID'])) ){
            $substitute = 2;
        }elseif ( (intval($rsq[0]['substitute']) == 2 && in_array(intval($_SESSION['userid']),$substituteMembers)) ||  intval($rsq[0]['substitute']) == 0 ){
            $sqs = "SELECT `status` FROM `meeting_members` WHERE (`uid`={$_SESSION['userid']} OR `sid`={$_SESSION['userid']}) AND `meetingID`={$fmID}";
            $rsts = $db->ArrayQuery($sqs);
            $substitute = (intval($rsts[0]['status']) > 0 ? 2 : 1);
        }

        $query = "SELECT `sid` FROM `meeting_members` WHERE `uid`={$_SESSION['userid']} AND `meetingID`={$fmID} AND `status`=3";
        $rst = $db->ArrayQuery($query);
        $sid = (count($rst) > 0 ? $rst[0]['sid'] : 0);

        if ( (intval($rsq[0]['manager1']) == intval($_SESSION['userid'])) || intval($_SESSION['userid']) == 1 || intval($_SESSION['userid']) == intval($rsq[0]['modirID']) || in_array(intval($_SESSION['userid']),$manager2) || in_array(intval($_SESSION['userid']),$supervisor) ){  // اگر مدیر جلسه، مدیران موازی یا مدیر عامل یا معاونت بازرگانی یا مدیر کارخانه یا مدیر سیستم یا ناظر بود
            $sql = "SELECT `subject`,`reason`,`headline`,`gatheringDate`,`gatheringTime`,`gatheringPlace`,`requirements`,`description`,`fname`,`lname`,`generalDescription` FROM `meeting_comment` INNER JOIN `users` ON (`meeting_comment`.`uid`=`users`.`RowID`) WHERE `meetingID`={$fmID}";
        }elseif (intval($rsq[0]['relationship']) == 0 ) {  // محرمانه بودن
            $sql = "SELECT `subject`,`reason`,`headline`,`gatheringDate`,`gatheringTime`,`gatheringPlace`,`requirements`,`description`,`fname`,`lname`,`generalDescription` FROM `meeting_comment` INNER JOIN `users` ON (`meeting_comment`.`uid`=`users`.`RowID`) WHERE `meetingID`={$fmID} AND (`uid`={$_SESSION['userid']} OR `uid`={$sid} OR `uid`={$rsq[0]['manager1']})";
        }elseif (intval($rsq[0]['relationship']) == 1 && intval($rsq[0]['start']) == 1 ){    // تا قبل گردهمایی و جلسه شروع شده بود
            $sql = "SELECT `subject`,`reason`,`headline`,`gatheringDate`,`gatheringTime`,`gatheringPlace`,`requirements`,`description`,`fname`,`lname`,`generalDescription` FROM `meeting_comment` INNER JOIN `users` ON (`meeting_comment`.`uid`=`users`.`RowID`) WHERE `meetingID`={$fmID} AND (`uid`={$_SESSION['userid']} OR `uid`={$sid} OR `uid`={$rsq[0]['manager1']})";
        }else{
            $sql = "SELECT `subject`,`reason`,`headline`,`gatheringDate`,`gatheringTime`,`gatheringPlace`,`requirements`,`description`,`fname`,`lname`,`generalDescription` FROM `meeting_comment` INNER JOIN `users` ON (`meeting_comment`.`uid`=`users`.`RowID`) WHERE `meetingID`={$fmID}";
        }
        $sql .= " ORDER BY `meeting_comment`.`RowID` ASC";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
		$htm .= '<div id="MeetingStartComment"><p> <h4 style="width:100%;text-align:center">شرح جلسه : </h4>'.$rsq[0]['MeetingStartComment'].'</p></div>';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="getFirstMeetingCommentsHTM-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ثبت کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">موضوع جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">علت تشکیل جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">سرتیتر عمده موضوعات جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ پیشنهادی گردهمایی برای جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ساعت پیشنهادی گردهمایی برای جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">مکان پیشنهادی گردهمایی برای جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">نیازمندی های گردهمایی و توضیحات تکمیلی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $iterator = 0;
        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $res[$i]['gatheringDate'] = (strtotime($res[$i]['gatheringDate']) > 0 ? $ut->greg_to_jal($res[$i]['gatheringDate']) : '');
            $description = (strlen(trim($res[$i]['generalDescription'])) > 0 ? $res[$i]['generalDescription'] : $res[$i]['description']);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['fname'] . ' ' . $res[$i]['lname'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['subject'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['reason'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['headline'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['gatheringDate'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['gatheringTime'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['gatheringPlace'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['requirements'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $description . '</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';

        $meetingModir = $rsq[0]['manager1'].','.$rsq[0]['manager2'];
        $meetingModir = explode(',',$meetingModir);

        if ( $_SESSION['userid'] == 1 || $_SESSION['userid'] == intval($rsq[0]['modirID']) || in_array($_SESSION['userid'],$meetingModir) || in_array($_SESSION['userid'],$supervisor) ){

            $sql2 = "SELECT * FROM `meeting_members` WHERE `meetingID`={$fmID}";
            $res2 = $db->ArrayQuery($sql2);
            $ccnt = count($res2);

            $htm .= '<table class="table table-bordered table-hover table-sm" id="otherInfoMeetingHTM1-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">اعضا جلسه</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">مجاز به انتخاب جانشین</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">جانشین</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 30%;">وضعیت</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $rids = explode(',',$rsq[0]['substituteMembers']);
            $iterator = 0;
            for ($j=0;$j<$ccnt;$j++){
                $iterator++;

                $query1 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res2[$j]['uid']}";
                $rst1 = $db->ArrayQuery($query1);

                $query2 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res2[$j]['sid']}";
                $rst2 = $db->ArrayQuery($query2);

                switch ($res2[$j]['status']){
                    case 0:
                        $status = 'مشخص نشده';
                        break;
                    case 1:
                        $status = 'جانشین انتخاب نمی کنم';
                        break;
                    case 2:
                        $status = 'خارج می شوم';
                        break;
                    case 3:
                        $status = 'رصد خواهم کرد';
                        break;
                }

                switch ($rsq[0]['substitute']){
                    case 0:
                        $substitute1 = 'می باشد';
                        $color = 'green';
                        break;
                    case 1:
                        $substitute1 = 'نمی باشد';
                        $color = 'red';
                        break;
                    case 2:
                        $substitute1 = (in_array($res2[$j]['uid'],$rids) ? 'می باشد' : 'نمی باشد');
                        $color = (in_array($res2[$j]['uid'],$rids) ? 'green' : 'red');
                        break;
                }

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$rst1[0]['fname'].' '.$rst1[0]['lname'].'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;color: '.$color.'">'.$substitute1.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$rst2[0]['fname'].' '.$rst2[0]['lname'].'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$status.'</td>';
                $htm .= '</tr>';
            }

            $htm .= '</tbody>';
            $htm .= '</table>';

            $sql3 = "SELECT `unCode`,`enterBoss`,`relationship`,`manager1`,`manager2`,`substitute`,`supervisor`,`meeting_close_reason` FROM `meeting` WHERE `RowID`={$fmID}";
            $res3 = $db->ArrayQuery($sql3);
            $iterator = 0;

            $htm .= '<table class="table table-bordered table-hover table-sm" id="otherInfoMeetingHTM2-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 30%;">سایر اطلاعات</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 60%;">مقدار</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $infoNames = array('کد یکتا','نیاز به ورود مدیریت','ارتباط کاربران با یکدیگر','اعضا جلسه','مدیر/مدیران موازی','امکان معرفی جانشین برای اعضا','ناظر/ناظران');
            for ($i=0;$i<7;$i++){
                $iterator++;
                $keyName = key($res3[0]);
                switch ($iterator){
                    case 2:
                        $res3[0]["$keyName"] = (intval($res3[0]["$keyName"]) == 1 ? 'دارد' : 'ندارد');
                        break;
                    case 3:
                        if (intval($res3[0]["$keyName"]) == 0){
                            $res3[0]["$keyName"] = 'محرمانه بودن فرایندها';
                        }elseif (intval($res3[0]["$keyName"]) == 1){
                            $res3[0]["$keyName"] = 'تا هماهنگی های قبل گردهمایی';
                        }else{
                            $res3[0]["$keyName"] = 'عادی';
                        }
                        break;
                    case 4:
                        $sql1 = "SELECT `fname`,`lname` FROM `meeting_members` INNER JOIN `users` ON (`meeting_members`.`uid`=`users`.`RowID`) WHERE `meetingID`={$fmID}";
                        $res4 = $db->ArrayQuery($sql1);
                        $cnt = count($res4);
                        $members = array();
                        for ($j=0;$j<$cnt;$j++){
                            $members[] = $res4[$j]['fname'].' '.$res4[$j]['lname'];
                        }
                        $res3[0]["$keyName"] = implode(' - ',$members);
                        break;
                    case 5:
                    case 7:
                        $rids = $res3[0]["$keyName"];
                        $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID` IN ({$rids})";
                        $rst = $db->ArrayQuery($query);
                        $ccnt = count($rst);
                        $members = array();
                        for ($j=0;$j<$ccnt;$j++){
                            $members[] = $rst[$j]['fname'].' '.$rst[$j]['lname'];
                        }
                        $res3[0]["$keyName"] = implode(' - ',$members);
                        break;
                    case 6:
                        if (intval($res3[0]["$keyName"]) == 0){
                            $res3[0]["$keyName"] = 'وجود دارد';
                        }elseif (intval($res3[0]["$keyName"]) == 1){
                            $res3[0]["$keyName"] = 'وجود ندارد';
                        }else{
                            $res3[0]["$keyName"] = 'انتخاب اعضا مجاز';
                        }
                        break;
                }

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res3[0]["$keyName"].'</td>';
                $htm .= '</tr>';
                next($res3[0]);
            }

            $htm .= '</tbody>';
            $htm .= '</table>';
        }
		$meeting_comment=$rsq[0]['MeetingStartComment'];
		
		$meetingInfo=array("is_admin"=>$is_meeting_admin,"meetingDate"=>$gatheringDate,"meetingTime"=>$gatheringTime,'meetingGregoryDate'=>$rsq[0]['gatheringDate'],'is_meeting_start'=>$rsq[0]['start'],'MeetingStartComment'=>$rsq[0]['MeetingStartComment'],'meeting_close_reason'=>$rsq[0]['meeting_close_reason'],'meeting_closed'=>$rsq[0]['closed']);
		$meetingInfoJSon = json_encode($meetingInfo);
		////$//ut->fileRecorder($meetingInfoJSon);
		$htm .= '<script>setMeetingDetailes('.$meetingInfoJSon.')</script>';
        return array($htm,$substitute,$meetingType,$is_meeting_admin);
    }

    public function createFirstMeetingComment($fmID,$headline,$gDate,$gTime,$gPlace,$requirements,$description){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $gDate = $ut->jal_to_greg($gDate);

        $sqlo = "SELECT `edited`,`start`,`enterBoss`,`modirID` FROM `meeting` WHERE `RowID`={$fmID}";
        $reso = $db->ArrayQuery($sqlo);

        if (intval($reso[0]['start']) == 1){
            $res = "دستور شروع این جلسه داده شده است و امکان ثبت نظر نمی باشد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sqlu = "SELECT `RowID` FROM `meeting_comment` WHERE `uid`={$_SESSION['userid']} AND `meetingID`={$fmID}";
      //  //$//ut->fileRecorder('sqlusqlusqlusqlu:'.$sqlu);
        $rstu = $db->ArrayQuery($sqlu);
        if (count($rstu) > 0){
            $res = "شما قبلا نظر خود را ثبت نموده اید !";
            $out = "false";
            response($res, $out);
            exit;
        }

        if ( (intval($_SESSION['userid']) == intval($reso[0]['modirID']) && intval($reso[0]['enterBoss']) == 1) ) {
            $sql = "INSERT INTO `meeting_comment` (`meetingID`,`uid`,`headline`,`gatheringDate`,`gatheringTime`,`gatheringPlace`,`requirements`,`description`) VALUES ({$fmID},{$_SESSION['userid']},'{$headline}','{$gDate}','{$gTime}','{$gPlace}','{$requirements}','{$description}')";
            $db->Query($sql);
        }else{
            $sqls = "SELECT `RowID` FROM `meeting_members` WHERE `sid`={$_SESSION['userid']} AND `meetingID`={$fmID}";
            $rsql = $db->ArrayQuery($sqls);
            if (intval(count($rsql)) <= 0) {
                $query = "SELECT `substitute`,`substituteMembers`,`manager1`,`manager2` FROM `meeting` WHERE `RowID`={$fmID}";
                $rst = $db->ArrayQuery($query);

                if (strlen(trim($rst[0]['manager2'])) > 0) {
                    $manager2 = explode(',', $rst[0]['manager2']);
                    if (in_array(intval($_SESSION['userid']), $manager2)) {
                        $res = "مدیر/مدیران موازی مجاز به ثبت نظر نمی باشند !";
                        $out = "false";
                        response($res, $out);
                        exit;
                    }
                }
                if (intval($_SESSION['userid']) == intval($rst[0]['manager1'])) {
                    $res = "شما مدیر جلسه هستید و مجاز به ثبت نظر نمی باشید !";
                    $out = "false";
                    response($res, $out);
                    exit;
                }

                $sqq = "SELECT `sid`,`status` FROM `meeting_members` WHERE `uid`={$_SESSION['userid']}";  // تشخیص می دهد که شخص عضو این جلسه باشد
                $rsq = $db->ArrayQuery($sqq);
                if (count($rsq) > 0) {
                    switch (intval($rst[0]['substitute'])) {
                        case 0:  // اگر امکان معرفی جانشین برای اعضا وجود دارد
                            if (intval($rsq[0]['sid']) == 0 && intval($rsq[0]['status']) == 0) {
                                $res = "ابتدا وضعیت جانشین خود را مشخص نمایید !";
                                $out = "false";
                                response($res, $out);
                                exit;
                            } elseif (intval($rsq[0]['sid']) > 0) {
                                $res = "جانشین شما باید ثبت نظر نماید0 !";
                                $out = "false";
                                response($res, $out);
                                exit;
                            }
						
                            break;
                        case 2:  // اگر امکان معرفی جانشین برای اعضا انتخاب اعضا مجاز بود
                            $substituteMembers = explode(',', $rst[0]['substituteMembers']);
                            if (in_array($_SESSION['userid'], $substituteMembers) && intval($rsq[0]['sid']) == 0 && intval($rsq[0]['status']) == 0) {
                                $res = "ابتدا وضعیت جانشین خود را مشخص نمایید !";
                                $out = "false";
                                response($res, $out);
                                exit;
                            } elseif (in_array($_SESSION['userid'], $substituteMembers) && intval($rsq[0]['sid']) > 0) {
                                $res = "1جانشین شما باید ثبت نظر نماید !";
                                $out = "false";
								////$//ut->fileRecorder($_SESSION['userid']);
								////$//ut->fileRecorder(print_r($substituteMembers,true));
								////$//ut->fileRecorder($rsq[0]['sid']);
                                response($res, $out);
                                exit;
                            }
						
                            break;
                    }
                } else {
                    $res = "شما جز اعضا جلسه نمی باشید !";
                    $out = "false";
                    response($res, $out);
                    exit;
                }
            }
            $sql = "INSERT INTO `meeting_comment` (`meetingID`,`uid`,`headline`,`gatheringDate`,`gatheringTime`,`gatheringPlace`,`requirements`,`description`) VALUES ({$fmID},{$_SESSION['userid']},'{$headline}','{$gDate}','{$gTime}','{$gPlace}','{$requirements}','{$description}')";
            $db->Query($sql);
        }

        $id = $db->InsertrdID();
        if(intval($id) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getSubstituteUsers($fmID){
        $db = new DBi();

        $rids = array();
        $sql = "SELECT `uid`,`sid` FROM `meeting_members` WHERE `meetingID`={$fmID}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            $rids[] = $res[$i]['uid'];
            $rids[] = $res[$i]['sid'];
        }
        $rids = implode(',',$rids);

        $sql1 = "SELECT `manager1`,`manager2`,`modirID` FROM `meeting` WHERE `RowID`={$fmID}";
        $res1 = $db->ArrayQuery($sql1);

        if (intval($_SESSION['modirID']) > 0){
            $substitute = $rids.','.$res1[0]['manager1'].','.$res1[0]['manager2'].','.$_SESSION['userid'].','.$_SESSION['modirID'].',1';
        }else{
            $substitute = $rids.','.$res1[0]['manager1'].','.$res1[0]['manager2'].','.$_SESSION['userid'].',1';

        }
        $substitute = explode(',',$substitute);
        $substitute = array_values(array_diff(array_unique($substitute),[0]));
        $substitute = implode(',',$substitute);

        $query = "SELECT `RowID`,`fname`,`lname` FROM `users` WHERE `RowID` NOT IN ({$substitute}) AND `IsEnable`=1";
        $rst = $db->ArrayQuery($query);

        return $rst;
    }

    public function createSubstituteStatus($fmID,$type,$user){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $query = "SELECT `RowID` FROM `meeting_members` WHERE `uid`={$_SESSION['userid']}";
        $rst = $db->ArrayQuery($query);
        if (count($rst) <= 0){
            $res = "شما جز اعضا جلسه نمی باشید !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "SELECT `substitute`,`substituteMembers`,`observers`,`start` FROM `meeting` WHERE `RowID`={$fmID}";
        $res = $db->ArrayQuery($sql);

        if (intval($res[0]['start']) == 1){
            $res = "دستور شروع جلسه داده شده است و امکان تعیین جانشین وجود ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        switch (intval($res[0]['substitute'])){
            case 1:
                $res = "امکان معرفی جانشین برای این جلسه وجود ندارد !";
                $out = "false";
                response($res,$out);
                exit;
            case 2:
                $substituteMembers = explode(',', $res[0]['substituteMembers']);
                if (!in_array($_SESSION['userid'], $substituteMembers)){
                    $res = "شما مجاز به انتخاب جانشین نمی باشید !";
                    $out = "false";
                    response($res,$out);
                    exit;
                }
                break;
        }

        $sql1 = "SELECT `status` FROM `meeting_members` WHERE `meetingID`={$fmID} AND `uid`={$_SESSION['userid']}";
        $res1 = $db->ArrayQuery($sql1);
        if (intval($res1[0]['status']) > 0){
            $res = "شما قبلا وضعیت جانشین خود را مشخص نموده اید !";
            $out = "false";
            response($res,$out);
            exit;
        }

        if (intval($type) !== 1) {
            $sql2 = "SELECT `sid` FROM `meeting_members` WHERE `meetingID`={$fmID}";
            $res2 = $db->ArrayQuery($sql2);
            $cnt = count($res2);
            for ($i = 0; $i < $cnt; $i++) {
                if (intval($user) == intval($res2[$i]['sid'])) {
                    $res = "این شخص بعنوان جانشین قبلا توسط فردی دیگر انتخاب شده است !";
                    $out = "false";
                    response($res, $out);
                    exit;
                }
            }
        }

        if (intval($type) == 1){  // جانشین انتخاب نمی کنم
            $sql3 = "UPDATE `meeting_members` SET `status`=1 WHERE `meetingID`={$fmID} AND `uid`={$_SESSION['userid']}";
            $db->Query($sql3);
            $observers = $res[0]['observers'];
        }elseif (intval($type) == 3){  // رصد خواهم کرد
            $sql3 = "UPDATE `meeting_members` SET `sid`={$user},`status`={$type} WHERE `meetingID`={$fmID} AND `uid`={$_SESSION['userid']}";
            $db->Query($sql3);
            $observers = $res[0]['observers'].','.$user;
        }else{
            $sql3 = "UPDATE `meeting_members` SET `sid`={$user},`status`={$type} WHERE `meetingID`={$fmID} AND `uid`={$_SESSION['userid']}";
            $db->Query($sql3);

            $observers = explode(',',$res[0]['observers']);
            $key = array_search($_SESSION['userid'], $observers);
            unset($observers[$key]);
            $observers = implode(',',$observers);
            $observers = $observers.','.$user;
        }

        $sql4 = "UPDATE `meeting` SET `observers`='{$observers}' WHERE `RowID`={$fmID}";
        $db->Query($sql4);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1) ? 0 : 1);
        if(intval($aff)){
            return true;
        }else{
            return false;
        }
    }

/*    public function checkMeetingCommentStatus($fmID){
        $db = new DBi();

        $sqls = "SELECT `uid`,`sid` FROM `meeting_members` WHERE `meetingID`={$fmID}";
        $rsts = $db->ArrayQuery($sqls);
        $cnts = count($rsts);
        for ($i=0;$i<$cnts;$i++){
            if (intval($rsts[$i]['sid']) > 0){
                $sqls1 = "SELECT `RowID` FROM `meeting_comment` WHERE `uid`={$rsts[$i]['sid']} AND `meetingID`={$fmID}";
                $rsts1 = $db->ArrayQuery($sqls1);
                if (count($rsts1) <= 0){
                    return false;
                }
            }else{
                $sqls1 = "SELECT `RowID` FROM `meeting_comment` WHERE `uid`={$rsts[$i]['uid']} AND `meetingID`={$fmID}";
                $rsts1 = $db->ArrayQuery($sqls1);
                if (count($rsts1) <= 0){
                    return false;
                }
            }
        }
        return true;
    }*/

    public function startFirstMeeting($fmID){
        $db = new DBi();
        $ut = new Utility();

        $sqq = "SELECT `subject`,`gatheringDate`,`gatheringTime`,`gatheringPlace`,`manager1`,`manager2`,`enterBoss`,`modirID`,`start` FROM `meeting` WHERE `RowID`={$fmID}";
        $rsq = $db->ArrayQuery($sqq);
        $gatheringDate = $ut->greg_to_jal($rsq[0]['gatheringDate']);
        if (intval($rsq[0]['start']) == 1){
            $res = "دستور شروع این جلسه قبلا داده شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sqql = "SELECT `user_id` FROM `access_table` WHERE `item_id`=155";
        $rstq = $db->ArrayQuery($sqql);

        switch (intval($rsq[0]['modirID'])){
            case 4:
                $modirTxt = 'مدیریت عامل';
                break;
            case 20:
                $modirTxt = 'معاونت بازرگانی';
                break;
            case intval($rstq[0]['user_id']):
                $modirTxt = 'مدیریت کارخانه';
                break;
        }
        $meetingModir = $rsq[0]['manager1'].','.$rsq[0]['manager2'];
        if (intval($rsq[0]['enterBoss']) == 1){
            if (intval($_SESSION['userid']) !== intval($rsq[0]['modirID'])){
                $res = "فقط ".$modirTxt." می تواند دستور صدور جلسه دهد !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }else{
            $meetingModir = explode(',',$meetingModir);
            if (!in_array($_SESSION['userid'],$meetingModir)){
                $res = "شما جز مدیران این جلسه نمی باشید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }

        $sqls = "SELECT `uid`,`sid` FROM `meeting_members` WHERE `meetingID`={$fmID}";
        $rsts = $db->ArrayQuery($sqls);
        $cnts = count($rsts);
        for ($i=0;$i<$cnts;$i++){
            if (intval($rsts[$i]['sid']) > 0){
                $sqls1 = "SELECT `RowID` FROM `meeting_comment` WHERE `uid`={$rsts[$i]['sid']} AND `meetingID`={$fmID}";
                $rsts1 = $db->ArrayQuery($sqls1);
                if (count($rsts1) <= 0){
                    $res = "ابتدا باید تمامی نظرات ثبت گردد !";
                    $out = "false";
                    response($res,$out);
                    exit;
                }
            }else{
                $sqls1 = "SELECT `RowID` FROM `meeting_comment` WHERE `uid`={$rsts[$i]['uid']} AND `meetingID`={$fmID}";
                $rsts1 = $db->ArrayQuery($sqls1);
                if (count($rsts1) <= 0){
                    $res = "ابتدا باید تمامی نظرات ثبت گردد !";
                    $out = "false";
                    response($res,$out);
                    exit;
                }
            }
        }
        for ($i=0;$i<$cnts;$i++){
            if (intval($rsts[$i]['sid']) > 0){
                $sqls1 = "SELECT `phone` FROM `users` WHERE `RowID`={$rsts[$i]['sid']} ";
                $rsts1 = $db->ArrayQuery($sqls1);
               $ut->sendToMeetingMembers($rsts1[0]['phone'],$rsq[0]['subject'],$gatheringDate,$rsq[0]['gatheringTime'],$rsq[0]['gatheringPlace']);
            }else{
                $sqls1 = "SELECT `phone` FROM `users` WHERE `RowID`={$rsts[$i]['uid']} ";
                $rsts1 = $db->ArrayQuery($sqls1);
              $ut->sendToMeetingMembers($rsts1[0]['phone'],$rsq[0]['subject'],$gatheringDate,$rsq[0]['gatheringTime'],$rsq[0]['gatheringPlace']);
            }
        }

        $query = "UPDATE `meeting` SET `start`=1 WHERE `RowID`={$fmID}";
        $db->Query($query);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if(intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getMeetingMembersAndJobs($fmID){
        $db = new DBi();
        $ut = new Utility();

        $sqq = "SELECT `relationship`,`manager1`,`manager2`,`start`,`supervisor` FROM `meeting` WHERE `RowID`={$fmID}";
        $rsq = $db->ArrayQuery($sqq);
        $manager2 = explode(',',$rsq[0]['manager2']);
        $supervisor = explode(',',$rsq[0]['supervisor']);

        $query = "SELECT `sid` FROM `meeting_members` WHERE `uid`={$_SESSION['userid']} AND `meetingID`={$fmID} AND `status`=3";
        $rst = $db->ArrayQuery($query);
        $sid = (count($rst) > 0 ? $rst[0]['sid'] : 0);

        if ( (intval($rsq[0]['manager1']) == intval($_SESSION['userid'])) || intval($_SESSION['userid']) == 1 || intval($_SESSION['userid']) == 4 || intval($_SESSION['userid']) == 20 || in_array(intval($_SESSION['userid']),$manager2) || in_array(intval($_SESSION['userid']),$supervisor) ){  // اگر مدیر جلسه، مدیران موازی یا مدیر عامل یا معاونت بازرگانی یا مدیر سیستم یا ناظر بود
            $sql = "SELECT * FROM `meeting_jobs` WHERE `meetingID`={$fmID}";
        }elseif (intval($rsq[0]['relationship']) == 0 ) {  // محرمانه بودن
            $sql = "SELECT * FROM `meeting_jobs` WHERE `meetingID`={$fmID} AND (`uid`={$_SESSION['userid']} OR `uid`={$sid})";
        }elseif (intval($rsq[0]['relationship']) == 1 && intval($rsq[0]['start']) == 1 ){    // تا قبل گردهمایی و جلسه شروع شده بود
            $sql = "SELECT * FROM `meeting_jobs` WHERE `meetingID`={$fmID} AND (`uid`={$_SESSION['userid']} OR `uid`={$sid})";
        }else{
            $sql = "SELECT * FROM `meeting_jobs` WHERE `meetingID`={$fmID}";
        }
        $rst = $db->ArrayQuery($sql);
        $cnt = count($rst);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="getMeetingMembersAndJobs-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">عضو جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">تاریخ ثبت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">ساعت ثبت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">مهلت انجام</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">شرح مسئولیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">وضعیت کار</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">وضعیت نهایی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">درصد پیشرفت کار</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">پیش نیاز ها</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">حذف</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $iterator = 0;
        for ($i = 0; $i < $cnt; $i++) {
            $prerequest_count=0;
            $prerequest=trim(str_replace(",,",",",$rst[$i]['prerequisiteIDS']));
            if(!empty($prerequest)){
                $prerequest_count=count(explode(",",$prerequest)); //بدست آوردن تعدادکل پیش نیاز های این مسئولیت
               unset($prerequest);
            }
            $iterator++;
            $rst[$i]['createDate'] = $ut->greg_to_jal($rst[$i]['createDate']);
            $rst[$i]['validDate'] = $ut->greg_to_jal($rst[$i]['validDate']);
            $rst[$i]['finishDate'] = (strtotime($rst[$i]['finishDate']) > 0 ? $ut->greg_to_jal($rst[$i]['finishDate']) : '');
            $rst[$i]['finishTime'] = ($rst[$i]['finishTime'] == '00:00:00' ? '' : $rst[$i]['finishTime']);
            $color = (intval($rst[$i]['status']) == 1 ? 'color: green;' : 'color: red;');
            $color1 = (intval($rst[$i]['confirm']) == 1 ? 'color: green;' : 'color: red;');
            $rst[$i]['status'] = (intval($rst[$i]['status']) == 1 ? 'اتمام کار' : 'ناتمام');
            $rst[$i]['confirm'] = (intval($rst[$i]['confirm']) == 1 ? 'تایید شده' : 'در حال بررسی');

            $sql1 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$rst[$i]['uid']}";
            $rst1 = $db->ArrayQuery($sql1);
			$_SESSION['percent']=$rst[$i]['percent'];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst1[0]['fname'] . ' ' . $rst1[0]['lname'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['createDate'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['createTime'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['validDate'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['description'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;'.$color.'">' . $rst[$i]['status'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;'.$color1.'">' . $rst[$i]['confirm'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['percent'] . ' درصد</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button style="position:relative" id="MeetingPrerequisite_btn_'.$rst[$i]['RowID'].'" class="btn btn-info" onclick="createMeetingPrerequisite(' . $rst[$i]['RowID'] . ')" ><i class="fas fa-list"></i><span title="تعداد کل پیش نیاز های تعریف شده برای این مسئولیت" style="display:'.($prerequest_count>0?"flex":"none").'" class="Prerequisite_count">'.$prerequest_count.'</span></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteMeetingJobs(' . $rst[$i]['RowID'] . ')" ><i class="fas fa-trash"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';

        $sqls = "SELECT `uid`,`sid` FROM `meeting_members` WHERE `meetingID`={$fmID}";
        $rsts = $db->ArrayQuery($sqls);
        $cnts = count($rsts);
        $uids = array();
        for ($i=0;$i<$cnts;$i++){
            if (intval($rsts[$i]['sid']) > 0){
                $uids[] = $rsts[$i]['sid'];
            }else{
                $uids[] = $rsts[$i]['uid'];
            }
        }

        $uids = implode(',',$uids);
        $sql = "SELECT `RowID`,`fname`,`lname` FROM `users` WHERE `RowID` IN ({$uids})";
        $res = $db->ArrayQuery($sql);
        return array($res,$htm);
    }

    public function createMeetingJobs($mid,$member,$vDate,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');
        $vDate = $ut->jal_to_greg($vDate);

        $sqq = "SELECT `manager1`,`manager2`,`start`,`closed`,`MeetingStartComment`,`mType` FROM `meeting` WHERE `RowID`={$mid}";
        $rsq = $db->ArrayQuery($sqq);
		if(intval($rsq[0]['MeetingStartComment']==2)){
			 if (empty(trim($rsq[0]['MeetingStartComment']))){
				$res = "شرح  دستور جلسه ثبت نشده است";
				$out = "false";
				response($res,$out);
				exit;
			}
		}
        if (intval($rsq[0]['start']) == 0){
            $res = "دستور شروع این جلسه داده نشده است !";
            $out = "false";
            response($res,$out);
            exit;
        }
        if (intval($rsq[0]['closed']) == 1){
            $res = "این جلسه بسته شده شده است و امکان افزودن مسئولیت جدید وجود ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $meetingModir = $rsq[0]['manager1'].','.$rsq[0]['manager2'];
        $meetingModir = explode(',',$meetingModir);
        if (!in_array($_SESSION['userid'],$meetingModir)){
            $res = "شما جز مدیران این جلسه نمی باشید !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "INSERT INTO `meeting_jobs` (`meetingID`,`uid`,`createDate`,`createTime`,`validDate`,`description`) VALUES ({$mid},{$member},'{$nowDate}','{$nowTime}','{$vDate}','{$desc}')";
        $db->Query($sql);
        $id = $db->InsertrdID();
        if(intval($id) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function deleteMeetingJobs($mid,$jobID){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sqq = "SELECT `manager1`,`manager2`,`closed` FROM `meeting` WHERE `RowID`={$mid}";
        $rsq = $db->ArrayQuery($sqq);

        if (intval($rsq[0]['closed']) == 1){
            $res = "این جلسه بسته شده است و امکان حذف مسئولیت وجود ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $meetingModir = $rsq[0]['manager1'].','.$rsq[0]['manager2'];
        $meetingModir = explode(',',$meetingModir);
        if (!in_array($_SESSION['userid'],$meetingModir)){
            $res = "شما جز مدیران این جلسه نمی باشید !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql1 = "SELECT `RowID` FROM `meeting_workreport` WHERE `jobID`={$jobID}";
        $rst1 = $db->ArrayQuery($sql1);
        if (count($rst1) > 0){
            $res = "برای این مورد گزارش کار ثبت شده است و امکان حذف وجود ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $query = "SELECT `RowID`,`prerequisiteIDS` FROM `meeting_jobs` WHERE `meetingID`={$mid}";
        $rst = $db->ArrayQuery($query);
        $ccnt = count($rst);
        for ($i=0;$i<$ccnt;$i++){
            $arr = explode(',',$rst[$i]['prerequisiteIDS']);
            if (in_array(intval($jobID),$arr)){
                $res = "این مورد بعنوان پیش نیاز مسئولیتی انتخاب شده است  !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }

        $sql = "DELETE FROM `meeting_jobs` WHERE `RowID`={$jobID}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if(intval($res)){
            return true;
        }else{
            return false;
        }
    }

    public function createMeetingJobsHtm($mid,$jobID){
        $db = new DBi();
        $ut = new Utility();

        $sqq = "SELECT `prerequisiteIDS` FROM `meeting_jobs` WHERE `RowID`={$jobID}";
        $rsq = $db->ArrayQuery($sqq);
        $prerequisiteIDS = explode(',',$rsq[0]['prerequisiteIDS']);

        $rids = array();
        $query = "SELECT `RowID`,`prerequisiteIDS` FROM `meeting_jobs` WHERE `meetingID`={$mid}";
        $rst = $db->ArrayQuery($query);
        $ccnt = count($rst);
        for ($i=0;$i<$ccnt;$i++){
            $arr = explode(',',$rst[$i]['prerequisiteIDS']);
            if (in_array(intval($jobID),$arr)){
                $rids[] = $rst[$i]['RowID'];
            }
        }
        $rids[] = $jobID;
        $rids = implode(',',$rids);

        $sqls = "SELECT `relationship`,`manager1`,`manager2`,`start`,`modirID` FROM `meeting` WHERE `RowID`={$mid}";
        $rsqs = $db->ArrayQuery($sqls);
        $manager2 = explode(',',$rsqs[0]['manager2']);

        if ( (intval($rsqs[0]['manager1']) == intval($_SESSION['userid'])) || intval($_SESSION['userid']) == 1 || intval($_SESSION['userid']) == intval($rsqs[0]['modirID']) || in_array(intval($_SESSION['userid']),$manager2) ){  // اگر مدیر جلسه، مدیران موازی یا مدیر عامل یا معاونت بازرگانی یا مدیر سیستم بود
            $sql = "SELECT * FROM `meeting_jobs` WHERE `meetingID`={$mid} AND `RowID` NOT IN ($rids)";
        }elseif (intval($rsq[0]['relationship']) == 0 ) {  // محرمانه بودن
            $sql = "SELECT * FROM `meeting_jobs` WHERE `RowID`=0";
        }elseif (intval($rsq[0]['relationship']) == 1 && intval($rsq[0]['start']) == 1 ){    // تا قبل گردهمایی و جلسه شروع شده بود
            $sql = "SELECT * FROM `meeting_jobs` WHERE `RowID`=0";
        }else{
            $sql = "SELECT * FROM `meeting_jobs` WHERE `meetingID`={$mid} AND `RowID` NOT IN ($rids)";
        }
        $rst = $db->ArrayQuery($sql);
        $cnt = count($rst);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="createMeetingJobsHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">عضو جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">تاریخ ثبت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">ساعت ثبت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">مهلت انجام</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 24%;">شرح مسئولیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">وضعیت کار</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">وضعیت نهایی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 11%;">درصد پیشرفت کار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i = 0; $i < $cnt; $i++) {
            $rst[$i]['createDate'] = $ut->greg_to_jal($rst[$i]['createDate']);
            $rst[$i]['validDate'] = $ut->greg_to_jal($rst[$i]['validDate']);
            $rst[$i]['finishDate'] = (strtotime($rst[$i]['finishDate']) > 0 ? $ut->greg_to_jal($rst[$i]['finishDate']) : '');
            $color = (intval($rst[$i]['status']) == 1 ? 'color: green;' : 'color: red;');
			$color1 = (intval($rst[$i]['confirm']) == 1 ? 'color: green;' : 'color: red;');
            $rst[$i]['status'] = (intval($rst[$i]['status']) == 1 ? 'اتمام کار' : 'ناتمام');
			$rst[$i]['confirm'] = (intval($rst[$i]['confirm']) == 1 ? 'تایید شده' : 'در حال بررسی');

            $sql1 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$rst[$i]['uid']}";
            $rst1 = $db->ArrayQuery($sql1);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;padding: 10px;"><input type="checkbox" rid="'.$rst[$i]['RowID'].'" '.(in_array($rst[$i]['RowID'],$prerequisiteIDS) ? 'checked' : '').'></td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst1[0]['fname'] . ' ' . $rst1[0]['lname'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['createDate'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['createTime'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['validDate'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['description'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;'.$color.'">' . $rst[$i]['status'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;'.$color1.'">' . $rst[$i]['confirm'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['percent'] . ' درصد</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';

        return $htm;
    }

    public function createMeetingPrerequisite($jid,$mid,$jobID){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sqq = "SELECT `manager1`,`manager2`,`closed` FROM `meeting` WHERE `RowID`={$mid}";
        $rsq = $db->ArrayQuery($sqq);

        if (intval($rsq[0]['closed']) == 1){
            $res = "این جلسه بسته شده است و امکان تعیین پیش نیاز وجود ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $meetingModir = $rsq[0]['manager1'].','.$rsq[0]['manager2'];
        $meetingModir = explode(',',$meetingModir);
        if (!in_array($_SESSION['userid'],$meetingModir)){
            $res = "شما جز مدیران این جلسه نمی باشید !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $query = "SELECT `status`,`confirm` FROM `meeting_jobs` WHERE `RowID`={$jid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['status']) == 1 || intval($rst[0]['confirm']) == 1){
            $res = "این مسئولیت تایید نهایی شده یا اتمام کار آن ثبت شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "UPDATE `meeting_jobs` SET `prerequisiteIDS`='{$jobID}' WHERE `RowID`={$jid}";
        $db->Query($sql);

        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if(intval($res)){
            return true;
        }else{
            return false;
        }
    }

    public function showMeetingWorkReportList($mid){
        $db = new DBi();
        $ut = new Utility();
        $is_meeting_admin=0;
        $sqq = "SELECT `relationship`,`manager1`,`manager2`,`start`,`modirID`,`supervisor` FROM `meeting` WHERE `RowID`={$mid}";
        $rsq = $db->ArrayQuery($sqq);
        //--------------------------------------------------   getting meeting admins -----------------------------------------
        if(!empty($rsq[0]['manager2']))
            $managers=$rsq[0]['manager2'].",".$rsq[0]['manager1'];
        else
            $managers=$rsq[0]['manager1'];

        $managers_array=explode(",",str_replace(",,",',',$managers));
        $is_meeting_admin=0;

        if(in_array($_SESSION['userid'],$managers_array)){
           // //$//ut->fileRecorder('is_admin');
            $is_meeting_admin=1; 
        }
        //--------------------------------------------------   getting meeting admins -----------------------------------------
        $manager2 = str_replace(",,",',',$rsq[0]['manager2']);
        $manager2 = explode(',',$manager2);

        $supervisor = explode(',',$rsq[0]['supervisor']);
        
       
        $query = "SELECT `sid` FROM `meeting_members` WHERE `uid`={$_SESSION['userid']} AND `meetingID`={$mid} AND `status`=3";
        $rst3 = $db->ArrayQuery($query);
        $sid = (count($rst3) > 0 ? $rst3[0]['sid'] : 0);

        if ( (intval($rsq[0]['manager1']) == intval($_SESSION['userid'])) || intval($_SESSION['userid']) == 1 || intval($_SESSION['userid']) == intval($rsq[0]['modirID']) || in_array(intval($_SESSION['userid']),$manager2) || in_array(intval($_SESSION['userid']),$supervisor) ){  // اگر مدیر جلسه، مدیران موازی یا مدیر ارشد یا مدیر سیستم بود
            $sql = "SELECT * FROM `meeting_jobs` WHERE `meetingID`={$mid} ORDER BY `uid` ASC,`validDate` ASC";
        }elseif (intval($rsq[0]['relationship']) == 0 ) {  // محرمانه بودن
            $sql = "SELECT * FROM `meeting_jobs` WHERE `meetingID`={$mid} AND (`uid`={$_SESSION['userid']} OR `uid`={$sid}) ORDER BY `uid` ASC,`validDate` ASC";
        }elseif (intval($rsq[0]['relationship']) == 1 && intval($rsq[0]['start']) == 1 ){    // تا قبل گردهمایی و جلسه شروع شده بود
            $sql = "SELECT * FROM `meeting_jobs` WHERE `meetingID`={$mid} AND (`uid`={$_SESSION['userid']} OR `uid`={$sid}) ORDER BY `uid` ASC,`validDate` ASC";
        }else{
            $sql = "SELECT * FROM `meeting_jobs` WHERE `meetingID`={$mid} ORDER BY `uid` ASC,`validDate` ASC";
        }
        $rst = $db->ArrayQuery($sql);
        $cnt = count($rst);
        $htm = '';
        
        $htm .= '<table class="table table-bordered table-hover table-sm" id="showMeetingWorkReportList-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
       // $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 3%;">#</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 3%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">عضو جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 4%;">تاریخ ثبت</td>';
       // $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ساعت ثبت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 4%;">مهلت انجام</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">شرح مسئولیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 4%;">وضعیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 4%;">تاریخ اتمام</td>';
       // $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ساعت اتمام</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">درصد پیشرفت کار</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">گزارشات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">وضعیت نهایی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">تایید کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">تاریخ تایید</td>';
        if($is_meeting_admin==1){
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">تعییر مهلت انجام</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;"> سوابق تعییر مهلت انجام </td>';
        }
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $iterator = 0;
        for ($i=0;$i<$cnt;$i++) {
            $iterator++;
            $pre_count=0;
            $pre_req=str_replace(",,",",",$rst[$i]['prerequisiteIDS']);
            if(!empty($pre_req)){
                $get_pre_req_percent_sql="SELECT RowID FROM meeting_jobs where RowID in({$pre_req}) and status=0 and confirm=0 and percent<100 ";
                $pre_arr=$db->ArrayQuery($get_pre_req_percent_sql);
                $pre_count=count($pre_arr);// بدست آوردن  پیش نیاز های تایید نشده  این مسئولیت 
            }
            
            $rst[$i]['createDate'] = $ut->greg_to_jal($rst[$i]['createDate']);
            $rst[$i]['validDate'] = $ut->greg_to_jal($rst[$i]['validDate']);
            $rst[$i]['finishDate'] = (strtotime($rst[$i]['finishDate']) > 0 ? $ut->greg_to_jal($rst[$i]['finishDate']) : '');
            $rst[$i]['finishTime'] = ($rst[$i]['finishTime'] == '00:00:00' ? '' : $rst[$i]['finishTime']);
            $rst[$i]['confirmDate'] = (strtotime($rst[$i]['confirmDate']) > 0 ? $ut->greg_to_jal($rst[$i]['confirmDate']) : '');
            $color = (intval($rst[$i]['status']) == 1 ? 'color:green;' : 'color:red;');
            $color1 = (intval($rst[$i]['confirm']) == 1 ? 'color:green;' : 'color:red;');
            $rst[$i]['status'] = (intval($rst[$i]['status']) == 1 ? 'اتمام کار' : 'ناتمام');
            $rst[$i]['confirm'] = (intval($rst[$i]['confirm']) == 1 ? 'تایید شده' : 'در حال بررسی');

            $sql1 = "SELECT `fname`,`lname`,`gender` FROM `users` WHERE `RowID`={$rst[$i]['uid']}";
            $rst1 = $db->ArrayQuery($sql1);

            $sql2 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$rst[$i]['confirmUid']}";
            $rst2 = $db->ArrayQuery($sql2);
            $fullname= ($rst1[0]['gender']==0?"آقای ":"خانم ").$rst1[0]['fname']." ". $rst1[0]['lname'];

            $htm .= '<tr class="table-secondary">';
            //$htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
           // $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">
                     //   <input class="meeting_jobs_row" onclick="select_one_row(this)" type="checkbox" id="job_'.$rst[$i]['RowID'].'" value="'.$rst[$i]['RowID'].'"/>
                   // </td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst1[0]['fname'] . ' ' . $rst1[0]['lname'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['createDate'] . '</td>';
           // $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['createTime'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['validDate'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['description'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;'.$color.'">' . $rst[$i]['status'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['finishDate'] . '</td>';
           // $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['finishTime'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['percent'] . ' درصد</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button style="position:relative" class="btn btn-info" onclick="createMeetingWorkReport(' . $rst[$i]['RowID'] . ','.$pre_count.')" ><i class="fas fa-edit"></i><span title="پیش نیاز های تایید نشده" style="display:'.($pre_count>0?"flex;":"none;").'" class="Prerequisite_count">'.$pre_count.'</span></button></td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;'.$color1.'">' . $rst[$i]['confirm'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst2[0]['fname'] . ' ' . $rst2[0]['lname'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['confirmDate'] . '</td>';
            if($is_meeting_admin==1){
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;"><button onclick="edit_meeting_jobs_item('.$rst[$i]['RowID'].')" class="btn btn-success"><i class="fa fa-edit"></i></button> </td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;"><button onclick="get_meeting_jobs_history('.$rst[$i]['RowID'].')" class="btn btn-info"><i class="fa fa-history"></i></button> </td>';
            }
            $htm .= '<input type="hidden" value="'.$fullname.'" id="user_full_name_'. $rst[$i]['RowID'].'"></tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';

        return $htm;
    }

    public function getMeetingWorkReportHtm($jobID){
        $db = new DBi();
        $ut = new Utility();
        //$check_user_query="select `uid`,`confirmUid`  from meeting_jobs where `RowID`={$jobID}";
        $check_user_query="select mj.`uid`,m.`manager1`,m.`manager2`  from  `meeting_jobs` as mj
                            INNER JOIN `meeting` as m on m.`RowID`=mj.`meetingID` where mj.`RowID`={$jobID}";
        $res_check_user = $db->ArrayQuery($check_user_query);
         ////$//ut->fileRecorder('meeting_workreport:'.$check_user_query);
         $is_admin=0;
         $manager1=explode(',',$res_check_user[0]['manager1']);
         $manager2=explode(',',str_ireplace(",,",",",$res_check_user[0]['manager2']));
         $is_admin=in_array($_SESSION['userid'],$manager1)?1:0;
         if($is_admin==0){
            $is_admin=in_array($_SESSION['userid'],$manager2)?1:0;
         }
         //if(in_array($_SESSION['userid'],$_SESSION['userid']))
         ////$//ut->fileRecorder('is_admin:'.$is_admin);
         if($is_admin==0){
            if($res_check_user[0]['uid']!=$_SESSION['userid']){
           ////$//ut->fileRecorder('meeting_workreport:'.print_r($res_check_user,true));
            $result=array("res"=>"false","data"=>'این مسئولیت مربوط به شما نمی باشد');
            $result_json=json_encode($result);
            exit($result_json);
        }
       }
       // startجدول مربوط به پیش نیاز های مسیئولیت تعریف شد

       $sql1 = "SELECT `prerequisiteIDS` FROM `meeting_jobs` WHERE `RowID`={$jobID}";
       $res1 = $db->ArrayQuery($sql1);

       if (strlen(trim($res1[0]['prerequisiteIDS'])) > 0){
           $htm .= '';
           $htm .= '<fieldset style="border:2px solid red;border-radius:1rem;paddong:10px;margin:0">
                        <legend style="width:auto;padding:0 10px;color:blue;font-size:1rem">پیش نیاز های  مسئولیت</legend>';

           $sql2 = "SELECT `mj`.*,`fname`,`lname` FROM `meeting_jobs` as mj INNER JOIN `users` ON (`mj`.`uid`=`users`.`RowID`) WHERE `mj`.`RowID` IN ({$res1[0]['prerequisiteIDS']}) 
                    AND mj.status=0 AND mj.confirm=0 AND mj.percent<100";
           $res2 = $db->ArrayQuery($sql2);
           $ccnt = count($res2);

           $htm .= '<table class="table table-bordered table-striped  table-sm" id="getMeetingWorkReportHtm1-tableID">';
          // $htm .= '<thead>';
           //$htm .= '<tr class="bg-info">';
           $htm .= '<tr>';
           $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 5%;">ردیف</td>';
           $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 15%;">عضو جلسه</td>';
           $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 8%;">تاریخ ثبت</td>';
           $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 8%;">ساعت ثبت</td>';
           $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 8%;">مهلت انجام</td>';
           $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 26%;">شرح مسئولیت</td>';
           $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 9%;">وضعیت کار</td>';
           $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 9%;">وضعیت نهایی</td>';
           $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 12%;">درصد پیشرفت کار</td>';
           $htm .= '</tr>';
          // $htm .= '</thead>';
           //$htm .= '<tbody>';

           $iterator = 0;
           for ($i=0;$i<$ccnt;$i++) {
               $iterator++;
               $res2[$i]['createDate'] = $ut->greg_to_jal($res2[$i]['createDate']);
               $res2[$i]['validDate'] = $ut->greg_to_jal($res2[$i]['validDate']);
               $status = (intval($res2[$i]['status']) == 1 ? 'اتمام کار' : 'ناتمام');
               $confirm = (intval($res2[$i]['confirm']) == 1 ? 'تایید شده' : 'در حال بررسی');

               $color = (intval($res2[$i]['status']) == 1 ? 'color: green;' : 'color: red;');
               $color1 = (intval($res2[$i]['confirm']) == 1 ? 'color: green;' : 'color: red;');

               $htm .= '<tr>';
               $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
               $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['fname'] . ' '.$res2[$i]['lname'].'</td>';
               $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['createDate'] . '</td>';
               $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['createTime'] . '</td>';
               $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['validDate'] . '</td>';
               $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['description'] . '</td>';
               $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;'.$color.'">' . $status . '</td>';
               $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;'.$color1.'">' . $confirm . '</td>';
               $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['percent'] . ' درصد</td>';
               $htm .= '</tr>';
           }
          // $htm .= '</tbody>';
           $htm .= '</table></fieldset>';
       }

       // end جدول مربوط به پیش نیاز های مسیئولیت تعریف شد
        $sql = "SELECT * FROM `meeting_workreport` WHERE `jobID`={$jobID} AND `isEnable`=1 ";
        $res = $db->ArrayQuery($sql);
        
        $cnt = count($res);

        $htm .= '<fieldset style="border:2px solid blue;border-radius:1rem;paddong:10px;margin:0">
        <legend style="width:auto;padding:0 10px;color:blue;font-size:1rem">  گزارشات انجام مسئولیت  </legend>';
        if($cnt>0)
        {
            $htm .= '<table class="table table-bordered table-striped  table-sm" id="getMeetingWorkReportHtm-tableID">';
        // $htm .= '<thead>';
            $htm .= '<tr >';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 5%;">ردیف</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">تاریخ ثبت</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ساعت ثبت</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 61%;">شرح کار</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 7%;">حذف</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 7%;">نظرات</td>';
            $htm .= '</tr>';
        // $htm .= '</thead>';
        // $htm .= '<tbody>';

            $iterator = 0;
            for ($i=0;$i<$cnt;$i++) {
                $iterator++;
                $res[$i]['cDate'] = $ut->greg_to_jal($res[$i]['cDate']);

                $htm .= '<tr >';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['cDate'] . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['cTime'] . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['description'] . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteMeetingWorkReport('.$res[$i]['RowID'].')" ><i class="fas fa-trash"></i></button></td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="commentMeetingWorkReport('.$res[$i]['RowID'].')" ><i class="fas fa-comment"></i></button></td>';
                $htm .= '</tr>';
            }
       // $htm .= '</tbody>';
            $htm .= '</table>';
    }
    else{
        if($ccnt>0){
            $htm.='<p style="color:red;padding:10px">   مسئولیت دارای پیش نیاز می باشد  و تا زمان انجام و تایید کامل پیش نیاز ها از طرف مدیریت جلسه شما قادر به ثبت گزارش کار نمی باشد</p>';
        }
        else{
            $htm.='<p style="color:green;padding:10px">  مسئولیت های پیش نیاز به طور کامل انجام و توسط مدیر تایید شده است  و شما قادر به ثبت گزارش کار می باشید </p>';
        }
      
    }
    $htm.='</fieldset>';

       
        $sql3 = "SELECT `meeting_jobs_notconfirm`.*,`fname`,`lname` FROM `meeting_jobs_notconfirm` INNER JOIN `users` ON (`meeting_jobs_notconfirm`.`uid`=`users`.`RowID`) WHERE `jobID`={$jobID}";
        $res3 = $db->ArrayQuery($sql3);

        if (count($res3) > 0){
            $cntc = count($res3);
            $htm .= '<br>';
            $htm .= '<p>جدول علت های رد اتمام کار این مسئولیت :</p>';

            $htm .= '<table class="table table-bordered table-hover table-sm" id="getMeetingWorkReportHtm2-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">ثبت کننده</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ عدم تایید</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ساعت عدم تایید</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 55%;">علت رد اتمام کار</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $iterator = 0;
            for ($i=0;$i<$cntc;$i++) {
                $iterator++;
                $res3[$i]['cDate'] = $ut->greg_to_jal($res3[$i]['cDate']);

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res3[$i]['fname'] . ' '.$res3[$i]['lname'].'</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res3[$i]['cDate'] . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res3[$i]['cTime'] . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res3[$i]['description'] . '</td>';
                $htm .= '</tr>';
            }
            $htm .= '</tbody>';
            $htm .= '</table>';
        }

        return $htm;
    }

    public function getMeetingJobPercent($jobID){
        $db = new DBi();
        $ut = new Utility();
        $userId = $_SESSION['userid'];
        //$sql = "SELECT `percent` FROM `meeting_jobs` WHERE `RowID`={$jobID}";
        $sql = "SELECT m.`manager1`,`manager2`,m.`observers`,mj.`uid`,mj.`percent` FROM `meeting`  AS m
                INNER JOIN `meeting_jobs` AS mj ON mj.`meetingID`=m.`RowID` WHERE mj.`RowID`={$jobID}";
        $res = $db->ArrayQuery($sql);
       // //$//ut->fileRecorder($jobID.":".print_r($res,true));
        $manager1=explode(",",$res[0]['manager1']);
        $manager2=explode(",",$res[0]['manager2']);
        $observers = explode(",",$res[0]['observers']);
        $is_meeting_admin = 0;
        if(in_array($userId,$observers)){
            if(in_array($userId,$manager1)){
                $is_meeting_admin=1;
            }
            else{
                if(in_array($userId,$manager2)){
                    $is_meeting_admin=1;
                }
            }
        }
        $result = array('percent'=>intval($res[0]['percent']),'is_meeting_admin'=>$is_meeting_admin);

        //return intval($res[0]['percent']);
        return json_encode($result);
    }

    public function createMeetingWorkReport($jobID,$desc,$status,$percent){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        if (intval($percent) < 100 && intval($status) == 1){
            $res = "در صورتی می توان اتمام کار را اعلام کرد که درصد پیشرفت کار برابر 100 باشد !";
            $out = "false";
            response($res,$out);
            exit;
        }
        if (intval($percent) == 100){
            $status = 1;
        }

        $sql = "SELECT `uid`,`status`,`prerequisiteIDS`,`confirm`,`percent` FROM `meeting_jobs` WHERE `RowID`={$jobID}";
        $res = $db->ArrayQuery($sql);

        if (intval($res[0]['status']) == 1){
            $res = "اتمام کار این مورد قبلا اعلام شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }
        if (intval($res[0]['confirm']) == 1){
            $res = "این مسئولیت تایید نهایی شده است و امکان ثبت گزارش کار وجود ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }
        if (intval($_SESSION['userid']) !== intval($res[0]['uid'])){
            $res = "این مسئولیت مربوط به شما نمی باشد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql2 = "INSERT INTO `meeting_workreport` (`jobID`,`cDate`,`cTime`,`description`) VALUES ({$jobID},'{$nowDate}','{$nowTime}','{$desc}')";
        $db->Query($sql2);
        $id = $db->InsertrdID();
        if(intval($id) > 0){
            if (intval($status) == 1){
                $sql3 = "UPDATE `meeting_jobs` SET `status`=1,`finishDate`='{$nowDate}',`finishTime`='{$nowTime}',`percent`={$percent} WHERE `RowID`={$jobID}";
                $db->Query($sql3);
                $res3 = $db->AffectedRows();
                $res3 = (($res3 == -1 || $res3 == 0) ? 0 : 1);
                if(intval($res3)){
                    return true;
                }else{
                    return false;
                }
            }else{
                $sql3 = "UPDATE `meeting_jobs` SET `percent`={$percent} WHERE `RowID`={$jobID}";
                $db->Query($sql3);
                $res3 = $db->AffectedRows();
                $res3 = (($res3 == -1 || $res3 == 0) ? 0 : 1);
                if(intval($res3)){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    public function doDeleteMeetingWorkReport($mwrID,$jobID){
        $db = new DBi();

        $sql = "SELECT `uid`,`confirm` FROM `meeting_jobs` WHERE `RowID`={$jobID}";
        $res = $db->ArrayQuery($sql);
        if (intval($_SESSION['userid']) !== intval($res[0]['uid'])){
            $res = "شما ثبت کننده این گزارش کار نمی باشید !";
            $out = "false";
            response($res,$out);
            exit;
        }
        if ( intval($res[0]['confirm']) == 1 ){
            $res = "این مسئولیت تایید نهایی شده است و امکان حذف وجود ندارد !";
            $out = "false";
            response($res,$out);
            exit;
        }
        $query = "UPDATE `meeting_workreport` SET `isEnable`=0 WHERE `RowID`={$mwrID}";
        $db->Query($query);
        $ar = $db->AffectedRows();
        $ar = (($ar == -1 || $ar == 0) ? 0 : 1);
        if (intval($ar) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function confirmMeetingJob($mid,$jobID){
        $db = new DBi();

        $sqq = "SELECT `manager1`,`manager2`,`enterBoss`,`modirID`,`start` FROM `meeting` WHERE `RowID`={$mid}";
        $rsq = $db->ArrayQuery($sqq);

        $sqql = "SELECT `user_id` FROM `access_table` WHERE `item_id`=155";
        $rstq = $db->ArrayQuery($sqql);

        switch (intval($rsq[0]['modirID'])){
            case 4:
                $modirTxt = 'مدیریت عامل';
                break;
            case 20:
                $modirTxt = 'معاونت بازرگانی';
                break;
            case intval($rstq[0]['user_id']):
                $modirTxt = 'مدیریت کارخانه';
                break;
        }

        $meetingModir = $rsq[0]['manager1'].','.$rsq[0]['manager2'];
        if (intval($rsq[0]['enterBoss']) == 1){
            if (intval($_SESSION['userid']) !== intval($rsq[0]['modirID'])){
                $res = "فقط ".$modirTxt." می تواند تایید نهایی نماید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }else{
            $meetingModir = explode(',',$meetingModir);
            if (!in_array($_SESSION['userid'],$meetingModir)){
                $res = "شما جز مدیران این جلسه نمی باشید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }

        $sqp = "SELECT `status` FROM `meeting_jobs` WHERE `RowID`={$jobID}";
        $rsp = $db->ArrayQuery($sqp);

        if (intval($rsp[0]['status']) == 0){
            $res = "ابتدا باید اتمام کار این مسئولیت اعلام گردد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        $sql1 = "UPDATE `meeting_jobs` SET `confirm`=1,`confirmDate`='{$nowDate}',`confirmTime`='{$nowTime}',`confirmUid`={$_SESSION['userid']} WHERE `RowID`={$jobID}";
        $db->Query($sql1);
        $arr = $db->AffectedRows();
        $arr = (($arr == -1) ? 0 : 1);
        if(intval($arr) > 0) {
            return true;
        }else{
            return false;
        }
    }

    public function noConfirmMeetingJob($mid,$jobID,$desc){
        $db = new DBi();

        $sqq = "SELECT `manager1`,`manager2`,`enterBoss`,`modirID`,`start` FROM `meeting` WHERE `RowID`={$mid}";
        $rsq = $db->ArrayQuery($sqq);

        $sqql = "SELECT `user_id` FROM `access_table` WHERE `item_id`=155";
        $rstq = $db->ArrayQuery($sqql);

        switch (intval($rsq[0]['modirID'])){
            case 4:
                $modirTxt = 'مدیریت عامل';
                break;
            case 20:
                $modirTxt = 'معاونت بازرگانی';
                break;
            case intval($rstq[0]['user_id']):
                $modirTxt = 'مدیریت کارخانه';
                break;
        }

        $meetingModir = $rsq[0]['manager1'].','.$rsq[0]['manager2'];
        if (intval($rsq[0]['enterBoss']) == 1){
            if (intval($_SESSION['userid']) !== intval($rsq[0]['modirID'])){
                $res = "فقط ".$modirTxt." می تواند تایید نهایی نماید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }else{
            $meetingModir = explode(',',$meetingModir);
            if (!in_array($_SESSION['userid'],$meetingModir)){
                $res = "شما جز مدیران این جلسه نمی باشید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }

        $sqp = "SELECT `status`,`confirm` FROM `meeting_jobs` WHERE `RowID`={$jobID}";
        $rsp = $db->ArrayQuery($sqp);

        if (intval($rsp[0]['confirm']) == 1){
            $res = "گزارش کارهای این مسئولیت قبلا تایید نهایی شده است !";
            $out = "false";
            response($res,$out);
            exit;
        }
        if (intval($rsp[0]['status']) == 0){
            $res = "ابتدا باید اتمام کار این مسئولیت اعلام گردد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        $sql = "INSERT INTO `meeting_jobs_notconfirm` (`jobID`,`uid`,`cDate`,`cTime`,`description`) VALUES ({$jobID},{$_SESSION['userid']},'{$nowDate}','{$nowTime}','{$desc}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            $sql1 = "UPDATE `meeting_jobs` SET `status`=0,`finishDate`='',`finishTime`='',`percent`=0 WHERE `RowID`={$jobID}";
            $db->Query($sql1);
            $arr = $db->AffectedRows();
            $arr = (($arr == -1) ? 0 : 1);
            if(intval($arr) > 0) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function addRemoveMember($fmID,$members){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $query = "SELECT `start`,`observers`,`manager1`,`manager2`,`modirID`,`enterBoss` FROM `meeting` WHERE `RowID`={$fmID}";
        $rst = $db->ArrayQuery($query);

        $sqql = "SELECT `user_id` FROM `access_table` WHERE `item_id`=155";
        $rstq = $db->ArrayQuery($sqql);

        switch (intval($rst[0]['modirID'])){
            case 4:
                $modirTxt = 'مدیریت عامل';
                break;
            case 20:
                $modirTxt = 'معاونت بازرگانی';
                break;
            case intval($rstq[0]['user_id']):
                $modirTxt = 'مدیریت کارخانه';
                break;
        }

        $meetingModir = $rst[0]['manager1'].','.$rst[0]['manager2'];
        if (intval($rst[0]['enterBoss']) == 1){
            if (intval($_SESSION['userid']) !== intval($rst[0]['modirID'])){
                $res = "فقط ".$modirTxt." می تواند تایید نهایی نماید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }else{
            $meetingModir = explode(',',$meetingModir);
            if (!in_array($_SESSION['userid'],$meetingModir)){
                $res = "شما جز مدیران این جلسه نمی باشید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }

        if (strlen(trim($rst[0]['manager2'])) > 0){
            $rst[0]['manager1'] .= ','.$rst[0]['manager2'];
        }
        $modiran = explode(',',$rst[0]['manager1']);
        if (intval($rst[0]['start']) == 0){
            $res = "دستور شروع این جلسه داده نشده است و شما می توانید حذف و اضافه نمایید !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $members = explode(',',$members);
        $ccnt = count($members);
        $sql = "SELECT `uid`,`sid` FROM `meeting_members` WHERE `meetingID`={$fmID}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $uids = array();
        for ($i=0;$i<$cnt;$i++){
            $uids[] = $res[$i]['uid'];
            $sql1 = "SELECT `RowID` FROM `meeting_jobs` WHERE `uid`={$res[$i]['uid']} AND `meetingID`={$fmID}";
            $res1 = $db->ArrayQuery($sql1);

            $sql1p = "SELECT `RowID` FROM `meeting_jobs` WHERE `uid`={$res[$i]['sid']} AND `meetingID`={$fmID}";
            $res1p = $db->ArrayQuery($sql1p);

            if (!in_array($res[$i]['uid'],$members) && (count($res1) > 0 || count($res1p) > 0)){
                $res = "از اعضای حذف شده ( خود شخص یا جانشین وی ) دارای مسئولیت می باشد !";
                $out = "false";
                response($res,$out);
                exit;
            }
            if (!in_array($res[$i]['uid'],$members)){
                $sql2 = "DELETE FROM `meeting_members` WHERE `uid`={$res[$i]['uid']} AND `meetingID`={$fmID}";
                $db->Query($sql2);
            }
        }
        $rids = array();
        for ($i=0;$i<$ccnt;$i++){
            if (in_array($members[$i],$modiran)){
                $res = "اعضا جلسه نمی تواند از بین مدیران باشد !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }
        for ($i=0;$i<$ccnt;$i++){
            if (!in_array($members[$i],$uids)){
                $sql3 = "INSERT INTO `meeting_members` (`meetingID`,`uid`) VALUES ({$fmID},{$members[$i]})";
                $db->Query($sql3);
                $rids[] = $members[$i];
            }
        }
        if (count($rids) > 0){
            $rids = implode(',',$rids);
            $observers = $rst[0]['observers'].','.$rids;
            $sql3 = "UPDATE `meeting` SET `observers`='{$observers}' WHERE `RowID`={$fmID}";
            $db->Query($sql3);
        }
        return true;
    }

    public function addGuestMember($fmID,$members,$role){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT `observers`,`manager1`,`manager2`,`start`,`enterBoss` FROM `meeting` WHERE `RowID`={$fmID}";
        $res = $db->ArrayQuery($sql);

        $sqql = "SELECT `user_id` FROM `access_table` WHERE `item_id`=155";
        $rstq = $db->ArrayQuery($sqql);

        switch (intval($res[0]['modirID'])){
            case 4:
                $modirTxt = 'مدیریت عامل';
                break;
            case 20:
                $modirTxt = 'معاونت بازرگانی';
                break;
            case intval($rstq[0]['user_id']):
                $modirTxt = 'مدیریت کارخانه';
                break;
        }

        $meetingModir = $res[0]['manager1'].','.$res[0]['manager2'];
        if (intval($res[0]['enterBoss']) == 1){
            if (intval($_SESSION['userid']) !== intval($res[0]['modirID'])){
                $res = "فقط ".$modirTxt." می تواند تایید نهایی نماید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }else{
            $meetingModir = explode(',',$meetingModir);
            if (!in_array($_SESSION['userid'],$meetingModir)){
                $res = "شما جز مدیران این جلسه نمی باشید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }

        if (intval($res[0]['start']) == 0){
            $res = "دستور شروع این جلسه داده نشده است و شما می توانید کاربر میهمان نمایید !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $observers = $res[0]['observers'].','.$members;
        $manager2 = $members;
        if (strlen(trim($res[0]['manager2'])) > 0){
            $manager2 .= ','.$res[0]['manager2'];
        }
        if (intval($role) == 1){  // ناظر
            $sql1 = "UPDATE `meeting` SET `observers`='{$observers}',`supervisor`='{$members}' WHERE `RowID`={$fmID}";
            $db->Query($sql1);
        }else{  // مدیر موازی
            $sql1 = "UPDATE `meeting` SET `observers`='{$observers}',`manager2`='{$manager2}' WHERE `RowID`={$fmID}";
            $db->Query($sql1);
        }
        $res1 = $db->AffectedRows();
        $res1 = (($res1 == -1 || $res1 == 0) ? 0 : 1);
        if(intval($res1)){
            $sqp = "SELECT `phone` FROM `users` WHERE `RowID` IN ({$members})";
            $rsp = $db->ArrayQuery($sqp);
            $cnp = count($rsp);
            for ($i=0;$i<$cnp;$i++){
                $ut->sendAllBudgetElements($rsp[$i]['phone'],'جلسات');
            }
            return true;
        }else{
            return false;
        }
    }

    public function getMeetingWorkReportCommentHtm($mwrID){
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT `meeting_workreport_comment`.*,`fname`,`lname` FROM `meeting_workreport_comment` INNER JOIN `users` ON (`meeting_workreport_comment`.`uid`=`users`.`RowID`) WHERE `mwrID`={$mwrID}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="getMeetingWorkReportCommentHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 21%;">ثبت کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">تاریخ ثبت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">ساعت ثبت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 50%;">نظر</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $iterator = 0;
        for ($i=0;$i<$cnt;$i++) {
            $iterator++;
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['fname'] . ' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['createDate'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['createTime'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['description'] . '</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';

        return $htm;
    }

    public function closeFirstMeeting($fmID,$befor_deadline,$meeting_close_reason){
		$ut=new Utility();
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sqq = "SELECT `manager1`,`manager2`,`modirID` FROM `meeting` WHERE `RowID`={$fmID}";
        $rsq = $db->ArrayQuery($sqq);

        $sqql = "SELECT `user_id` FROM `access_table` WHERE `item_id`=155";
        $rstq = $db->ArrayQuery($sqql);

        switch (intval($rsq[0]['modirID'])){
            case 4:
                $modirTxt = 'مدیریت عامل';
                break;
            case 20:
                $modirTxt = 'معاونت بازرگانی';
                break;
            case intval($rstq[0]['user_id']):
                $modirTxt = 'مدیریت کارخانه';
                break;
        }
        $meetingModir = $rsq[0]['manager1'].','.$rsq[0]['manager2'];
        if (intval($rsq[0]['enterBoss']) == 1){
            if (intval($_SESSION['userid']) !== intval($rsq[0]['modirID'])){
                $res = "فقط ".$modirTxt." می تواند دستور صدور جلسه دهد !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }else{
            $meetingModir = explode(',',$meetingModir);
            if (!in_array($_SESSION['userid'],$meetingModir)){
                $res = "شما جز مدیران این جلسه نمی باشید !";
                $out = "false";
                response($res,$out);
                exit;
            }
        }
        //

        if($befor_deadline==0){ 
		
            $sql1 = "SELECT `RowID` FROM `meeting_jobs` WHERE `meetingID`={$fmID} AND `confirm`=0";
			
            $res1 = $db->ArrayQuery($sql1);
            if (count($res1) > 0){
                $res = "ابتدا باید تمامی مسئولیت ها تایید نهایی شده باشند !";
                $out = "false";
                //response($res,$out);
				
                return -9;
                exit;
            }
        }
      
        $sql = "UPDATE `meeting` SET `closed`=1,`meeting_close_reason`='{$meeting_close_reason}' WHERE `RowID`={$fmID}";
		////$//ut->fileRecorder('UPDATE:'.$sql);
        ////error_log($sql);
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if(intval($res)){
            
            return 1;
        }else{
           
            return 0;
        }
    }

    public function get_meeting_jobs_detailes($job_id){
       $db=new DBi();
       $ut=new Utility();
       $get_meeting_job="SELECT `description`,`validDate` from meeting_jobs  where RowID={$job_id}";
       $res=$db->ArrayQuery($get_meeting_job);
       $final_arrauy=[];
       foreach($res as $res_key=>$res_value){
            $helper_array=[];
            $helper_array['valid_date']=$ut->greg_to_jal($res_value['validDate']);
            $helper_array['description']=$res_value['description'];
            $final_array[]=$helper_array;
       }
       return $final_array;
    
    }

    public function get_meeting_jobs_history($meeting_job_id){
        $db=new DBi();
        $ut=new Utility();
        $get_job_header="Select * from meeting_jobs where RowID={$meeting_job_id}";
        $res=$db->ArrayQuery($get_job_header);
        $job_description=$res[0]['description'];
        $user_sql="select * from users where RowID={$res[0]['uid']}";
        $res_user=$db->ArrayQuery($user_sql);
        $full_name=($res_user[0]['gender']==1?" خانم ":"  آقای ").$res_user[0]['fname']." ".$res_user[0]['lname'];

        $get_job_history="SELECT *  FROM meeting_job_history where meeting_job_id={$meeting_job_id}";
        $history_info=$db->ArrayQuery($get_job_history);
        $html="";
        $html.='<div  style="border: 2px solid gray;padding: 10px; border-radius: 10px 10px  0 0;">';
        $html.="عضو جلسه :". " ".$full_name."<br>";
        $html.=" شرح مسئولیت :". " ".$job_description;
        $html.='</div>';
        $html.='<table class="table table-borderd table-striped table-primary">';
        $html.="<tr>
        <th>ردیف</th>
        <th>مهلت انجام کار</th>
        <th>علت تغییر تاریخ انجام کار</th>
        <th> تاریخ ثبت تغییرات</th>
        </tr>";
        $counter=0;
        foreach($history_info as $row){
            $html.=
            '<tr>
                <td>'.($counter+1).'</td>
                <td>'.$ut->greg_to_jal($row['valid_date']).'</td>
                <td>'.$row['reason_change'].'</td>
                <td>'.$ut->greg_to_jal($row['change_date']).'</td>
            </tr>';
            $counter++;
        }
        $html."</table>";
        return array($html,$counter);


    }
    public function do_edit_meeting_jobs_item($edit_job_id,$valid_date,$job_description){
        $db=new DBi();
        $ut=new Utility();
        //------------------------------------------- get job info detailes for last change data befor update ----------------
        $get_meeting_job="SELECT validDate,createDate,uid from meeting_jobs where RowId={$edit_job_id}";
        $res_job_info=$db->ArrayQuery($get_meeting_job);

        $sql_history="SELECT RowID,valid_date FROM meeting_job_history WHERE meeting_job_id={$edit_job_id} ";
        ////$//ut->fileRecorder('his:'.$sql_history);
        $res_history=$db->ArrayQuery($sql_history);

        
        //-------------------------------------------------------------------------------------
        $valid_date=$ut->jal_to_greg($valid_date);
        $sql="update meeting_jobs set validDate='{$valid_date}' where RowID={$edit_job_id} ";
        $res=$db->Query($sql);
        $current_date=date("Y-m-d H:i:s");
        if($res){
            $affected_row=$db->AffectedRows();
        }
        if($affected_row> 0){

            if(count($res_history)== 0){
                $insert_query_last="insert into meeting_job_history (meeting_job_id,valid_date,reason_change,user_id,change_date)VALUES('{$edit_job_id}','{$res_job_info[0]['validDate']}','مهلت انجام کار ثبت شده اولیه توسط مدیر جلسه','{$res_job_info[0]['uid']}','{$res_job_info[0]['createDate']}')";
               // //$//ut->fileRecorder("last:".$insert_query_last);
                $lase_res=$db->Query($insert_query_last);
                if(!$lase_res){
                    return -3;
                }
            }

            $insert_query_new="insert into meeting_job_history (meeting_job_id,valid_date,reason_change,user_id,change_date)VALUES('{$edit_job_id}','{$valid_date}','{$job_description}','{$_SESSION['userid']}','{$current_date}')";
           // //$//ut->fileRecorder('new'.$insert_query_new);
            $new_res= $db->Query($insert_query_new);
            if(!$new_res){
                return -4;
            }
            return $affected_row;
        }
        return -1;
    }

    public function commentMeetingWorkReport($mwrID,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('meetingManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        $sql = "INSERT INTO `meeting_workreport_comment` (`mwrID`,`uid`,`createDate`,`createTime`,`description`) VALUES ({$mwrID},{$_SESSION['userid']},'{$nowDate}','{$nowTime}','{$desc}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getMeetingMembers($fmID){
        $db = new DBi();
        $sql = "SELECT `uid` FROM `meeting_members` WHERE `meetingID`=".$fmID;
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $uids = array();
        for ($i=0;$i<$cnt;$i++){
            $uids[] = $res[$i]['uid'];
        }
        $uids = implode(',',$uids);
        if(count($res) > 0){
            return $uids;
        }else{
            return false;
        }
    }

    public function getAllowedMembers($fmID){
        $db = new DBi();
		$ut=new Utility();
        $sql = "SELECT `uid`,`sid` FROM `meeting_members` WHERE `meetingID`=".$fmID;
		////$//ut->fileRecorder('query:'.$sql);
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $uids = array();
        for ($i=0;$i<$cnt;$i++){
            $uids[] = $res[$i]['uid'];
            if (intval($res[$i]['sid']) > 0){
                $uids[] = $res[$i]['sid'];
            }
        }
        $uids = implode(',',$uids);
	
        $sql1 = "SELECT `observers`,`manager1` FROM `meeting` WHERE `RowID`={$fmID}";
        $res1 = $db->ArrayQuery($sql1);
			////$//ut->fileRecorder('test:'.$uids);	
		////$//ut->fileRecorder('observers:'.$res1[0]['observers']);	
		////$//ut->fileRecorder('manager1:'.$$res1[0]['manager1']);	
		////$//ut->fileRecorder('test22:'.$members);	
		////$//ut->fileRecorder('test22:'.$members);	
        $members = $uids.','.$res1[0]['observers'].','.$res1[0]['manager1'].',4,20';
		$members  = str_ireplace(',,',',',$members);
        $sql2 = "SELECT `RowID`,`fname`,`lname` FROM `users` WHERE `RowID` NOT IN ({$members}) AND `isEnable`=1";
		////$//ut->fileRecorder('sql2:'.$sql2);
        $res2 = $db->ArrayQuery($sql2);
        if(count($res2) > 0){
            return $res2;
        }else{
            return false;
        }
    }

    //++++++++++++++++++++++ گزارش جلسات +++++++++++++++++++++++

    public function getMeetingReportManageHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('meetingReportManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();

        $grouping = $this->getMeetingGroups();
        $CountGroups = count($grouping);

        $pagename = "گزارش جلسات";
        $pageIcon = "fa-chart-bar";
        $contentId = "meetingReportManageBody";

        $bottons = array();
        $headerSearch = array();

        $a = 0;
        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "200px";
        $headerSearch[$a]['id'] = "meetingReportUncodeSearch";
        $headerSearch[$a]['title'] = "کد یکتا";
        $headerSearch[$a]['placeholder'] = "کد یکتا";
        $a++;

        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "200px";
        $headerSearch[$a]['id'] = "meetingReportSubjectSearch";
        $headerSearch[$a]['title'] = "موضوع جلسه";
        $headerSearch[$a]['placeholder'] = "موضوع جلسه";
        $a++;

        $headerSearch[$a]['type'] = "select";
        $headerSearch[$a]['width'] = "150px";
        $headerSearch[$a]['id'] = "meetingReportGroupingSearch";
        $headerSearch[$a]['title'] = "دسته بندی جلسه";
        $headerSearch[$a]['options'] = array();
        $headerSearch[$a]['options'][0]["title"] = "دسته بندی جلسه";
        $headerSearch[$a]['options'][0]["value"] = 0;
        for ($i=0;$i<$CountGroups;$i++){
            $headerSearch[$a]['options'][$i+1]["title"] = $grouping[$i]['groupName'];
            $headerSearch[$a]['options'][$i+1]["value"] = $grouping[$i]['RowID'];
        }
        $a++;

        $headerSearch[$a]['type'] = "select";
        $headerSearch[$a]['width'] = "150px";
        $headerSearch[$a]['id'] = "meetingReportCloseOrOpenSearch";
        $headerSearch[$a]['title'] = "وضعیت جلسه";
        $headerSearch[$a]['options'] = array();
        $headerSearch[$a]['options'][0]["title"] = "وضعیت جلسه";
        $headerSearch[$a]['options'][0]["value"] = 0;
        $headerSearch[$a]['options'][1]["title"] = "جلسات باز (شروع شده)";
        $headerSearch[$a]['options'][1]["value"] = 1;
        $headerSearch[$a]['options'][2]["title"] = "جلسات باز (شروع نشده)";
        $headerSearch[$a]['options'][2]["value"] = 2;
        $headerSearch[$a]['options'][3]["title"] = "جلسات بسته شده";
        $headerSearch[$a]['options'][3]["value"] = 3;
        $headerSearch[$a]['options'][4]["title"] = "جلسات سریع";
        $headerSearch[$a]['options'][4]["value"] = 4;
        $headerSearch[$a]['options'][5]["title"] = "جلسات نرمال";
        $headerSearch[$a]['options'][5]["value"] = 5;
        $a++;

        $headerSearch[$a]['type'] = "btn";
        $headerSearch[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
        $headerSearch[$a]['jsf'] = "showMeetingReportList";
        $a++;

        $headerSearch[$a]['type'] = "select";
        $headerSearch[$a]['width'] = "150px";
        $headerSearch[$a]['id'] = "meetingReportChartTypeSearch";
        $headerSearch[$a]['onchange'] = "onchange= showMeetingReportList()";
        $headerSearch[$a]['title'] = "نوع نمودار";
        $headerSearch[$a]['options'] = array();
        $headerSearch[$a]['options'][0]["title"] = "میله ای";
        $headerSearch[$a]['options'][0]["value"] = 0;
        $headerSearch[$a]['options'][1]["title"] = "دایره ای";
        $headerSearch[$a]['options'][1]["value"] = 1;

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        //++++++++++++++++++++++++++++++++++EDIT CREATE MODAL++++++++++++++++++++++++++++++++
        //++++++++++++++++++ meeting Report Info Modal ++++++++++++++++++++++
        $modalID = "meetingReportInfoModal";
        $modalTitle = "سایر اطلاعات";
        $style = 'style="max-width: 915px;"';

        $ShowDescription = 'meeting-Report-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showMeetingReportInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End meeting Manage Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ meeting Jobs List MODAL ++++++++++++++++++++++++++++++++
        $modalID = "meetingReportJobsListModal";
        $modalTitle = "لیست مسئولیت ها";
        $style = 'style="max-width:100vw;"';
        $ShowDescription = 'meetingReportJobsListBody';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "meetingReportJobsListHiddenMID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $meetingReportJobsList = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF meeting Jobs List MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ Meeting WorkReport MODAL ++++++++++++++++++++++++++++++++
        $modalID = "reportMeetingWorkReportModal";
        $modalTitle = "گزارش کار";
        $style = 'style="max-width: 1024px;"';
        $ShowDescription = 'reportMeetingWorkReportBody';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $meetingReportWorkReportModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF Meeting WorkReport MODAL +++++++++++++++++++++++++++++++++++++
        $htm .= $showMeetingReportInfo;
        $htm .= $meetingReportJobsList;
        $htm .= $meetingReportWorkReportModal;
        return $htm;
    }

    public function getMeetingReportList($unCode,$subject,$grouping,$status,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('meetingReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($unCode)) > 0){
            $w[] = '`unCode` LIKE "%'.$unCode.'%" ';
        }
        if(strlen(trim($subject)) > 0){
            $w[] = '`subject` LIKE "%'.$subject.'%" ';
        }
        if(intval($grouping) > 0){
            $w[] = '`grouping`='.$grouping.' ';
        }
        if (intval($status) > 0){
            switch (intval($status)){
                case 1:
                    $w[] = '`start`=1 ';
                    $w[] = '`closed`=0 ';
                    break;
                case 2:
                    $w[] = '`start`=0 ';
                    $w[] = '`closed`=0 ';
                    break;
                case 3:
                    $w[] = '`closed`=1 ';
                    break;
                case 4:
                    $w[] = '`mType`=1 ';
                    break;
                case 5:
                    $w[] = '`mType`=2 ';
                    break;
            }
        }

        $sql = "SELECT * FROM `meeting`";
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
            $finalRes[$y]['enterBoss'] = (intval($res[$y]['enterBoss']) == 0 ? 'دارد' : 'ندارد');
            $finalRes[$y]['subject'] = $res[$y]['subject'];
            $finalRes[$y]['reason'] = $res[$y]['reason'];
            $finalRes[$y]['headline'] = $res[$y]['headline'];

            $qry = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$y]['manager1']}";
            $rsq = $db->ArrayQuery($qry);
            $finalRes[$y]['manager'] = $rsq[0]['fname'].' '.$rsq[0]['lname'];

            $query = "SELECT `groupName` FROM `meeting_grouping` WHERE `RowID`={$res[$y]['grouping']}";
            $rst = $db->ArrayQuery($query);
            $finalRes[$y]['grouping'] = $rst[0]['groupName'];

            switch (intval($res[$y]['relationship'])){
                case 0;
                    $finalRes[$y]['relationship'] = 'محرمانه بودن فرایندها';
                    break;
                case 1;
                    $finalRes[$y]['relationship'] = 'تا هماهنگی های قبل گردهمایی';
                    break;
                case 2;
                    $finalRes[$y]['relationship'] = 'عادی';
                    break;
            }

            $users = array();
            $query1 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID` IN ({$res[$y]['members']})";
            $rst1 = $db->ArrayQuery($query1);
            $cnt = count($rst1);
            for ($i=0;$i<$cnt;$i++){
                $users[] = $rst1[$i]['fname'].' '.$rst1[$i]['lname'];
            }
            $users = implode(' - ',$users);
            $finalRes[$y]['users'] = $users;

            $query2 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$y]['manager2']}";
            $rst2 = $db->ArrayQuery($query2);
            $finalRes[$y]['manager2'] = $rst2[0]['fname'].' '.$rst2[0]['lname'];

            $finalRes[$y]['gatheringDate'] = $ut->greg_to_jal($res[$y]['gatheringDate']);
            $finalRes[$y]['gatheringTime'] = $res[$y]['gatheringTime'];
            $finalRes[$y]['gatheringPlace'] = $res[$y]['gatheringPlace'];

            switch (intval($res[$y]['substitute'])){
                case 0;
                    $finalRes[$y]['substitute'] = 'وجود دارد';
                    break;
                case 1;
                    $finalRes[$y]['substitute'] = 'وجود ندارد';
                    break;
                case 2;
                    $finalRes[$y]['substitute'] = 'انتخاب اعضا مجاز';
                    break;
            }

            $finalRes[$y]['bgColor'] = (intval($res[$y]['start']) == 0 ? 'table-danger' : 'table-success');

            $substituteMembers = array();
            $query3 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID` IN ({$res[$y]['substituteMembers']})";
            $rst3 = $db->ArrayQuery($query3);
            $ccnt = count($rst3);
            for ($i=0;$i<$ccnt;$i++){
                $substituteMembers[] = $rst3[$i]['fname'].' '.$rst3[$i]['lname'];
            }
            $substituteMembers = implode(' - ',$substituteMembers);
            $finalRes[$y]['substituteMembers'] = $substituteMembers;
            $finalRes[$y]['requirements'] = $res[$y]['requirements'];
            $finalRes[$y]['unCode'] = $res[$y]['unCode'];
            $finalRes[$y]['disabled'] = (intval($res[$y]['mType']) == 1 ? 'disabled' : '');
        }
        return $finalRes;
    }

    public function getMeetingReportListCountRows($unCode,$subject,$grouping,$status){
        $acm = new acm();
        if(!$acm->hasAccess('meetingReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $w = array();
        if(strlen(trim($unCode)) > 0){
            $w[] = '`unCode` LIKE "%'.$unCode.'%" ';
        }
        if(strlen(trim($subject)) > 0){
            $w[] = '`subject` LIKE "%'.$subject.'%" ';
        }
        if(intval($grouping) > 0){
            $w[] = '`grouping`='.$grouping.' ';
        }
        if (intval($status) > 0){
            switch (intval($status)){
                case 1:
                    $w[] = '`start`=1 ';
                    $w[] = '`closed`=0 ';
                    break;
                case 2:
                    $w[] = '`start`=0 ';
                    $w[] = '`closed`=0 ';
                    break;
                case 3:
                    $w[] = '`closed`=1 ';
                    break;
                case 4:
                    $w[] = '`mType`=1 ';
                    break;
                case 5:
                    $w[] = '`mType`=2 ';
                    break;
            }
        }

        $sql = "SELECT `RowID` FROM `meeting`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function reportMeetingInfoHtm(){
        $db = new DBi();
        $ut=new Utility();
        $sql = "SELECT `RowID` FROM `meeting` WHERE `start`=1 AND `closed`=0";  // باز شروع شده
        $res = $db->ArrayQuery($sql);

        $sql1 = "SELECT `RowID` FROM `meeting` WHERE `start`=0 AND `closed`=0";  // باز شروع نشده
        $res1 = $db->ArrayQuery($sql1);

        $sql2 = "SELECT `RowID` FROM `meeting` WHERE `closed`=1";  // بسته شده
        $res2 = $db->ArrayQuery($sql2);

        $sql3 = "SELECT `RowID` FROM `meeting` WHERE `mType`=2";  // نرمال
        $res3 = $db->ArrayQuery($sql3);

        $sql4 = "SELECT `RowID` FROM `meeting` WHERE `mType`=1";  // سریع
        $res4 = $db->ArrayQuery($sql4);

        $sql5 = "SELECT `RowID` FROM `meeting`";  // تعداد کل جلسات
        $res5 = $db->ArrayQuery($sql5);

        $sql6 = "SELECT `RowID` FROM `meeting_jobs` WHERE `status`=0 AND `confirm`=0";  // تعداد مسئولیت های ناتمام
        $res6 = $db->ArrayQuery($sql6);

        $sql7 = "SELECT `RowID` FROM `meeting_jobs` WHERE `status`=1";  // تعداد مسئولیت های تمام شده با تایید کاربر
        $res7 = $db->ArrayQuery($sql7);

        $sql8 = "SELECT `RowID` FROM `meeting_jobs` WHERE `confirm`=1";  // تعداد مسئولیت های تمام شده با تایید مدیر
        $res8 = $db->ArrayQuery($sql8);

        $data = [];
        $limit = 6;
        $labels = array('تعداد کل جلسات','جلسات باز (شروع شده)','جلسات باز (شروع نشده)','جلسات بسته شده','جلسات سریع','جلسات نرمال','وظایف ناتمام','وظایف تمام شده (تایید کاربر)','وظایف تمام شده (تایید مدیر)');
        $values = array(count($res5),count($res),count($res1),count($res2),count($res4),count($res3),count($res6),count($res7),count($res8));

        for($i=0;$i<$limit;$i++){
            $current_list = [];

            $current_list['label'] = $labels[$i];
            $current_list['value'] = $values[$i];

            $color = rand(0 , 255) . "," . rand(0 , 255) . "," . rand(0 , 255);
            $color = "rgba(" . $color . ", X)";

            $bg_color = str_replace("X", "0.2", $color);
            $border_color = str_replace("X", "1", $color);

            $current_list['color'] = [$bg_color,$border_color];

            $data[$i] = $current_list;
        }
        // $ut->fileRecorder('data*****');
        // $ut->fileRecorder($data);
        // $ut->fileRecorder('data*******');
        $json_data = json_encode($data);
        return $json_data;
    }

    public function otherInfoMeetingHTM($fmID){
        $acm = new acm();
        if(!$acm->hasAccess('meetingReportManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT `subject`,`reason`,`headline`,`gatheringDate`,`gatheringTime`,`gatheringPlace`,`requirements`,`description`,`fname`,`lname`,`generalDescription` FROM `meeting_comment` INNER JOIN `users` ON (`meeting_comment`.`uid`=`users`.`RowID`) WHERE `meetingID`={$fmID} ORDER BY `meeting_comment`.`RowID` ASC";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="otherInfoMeetingReportHTM-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ثبت کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">موضوع جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">علت تشکیل جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">سرتیتر عمده موضوعات جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ پیشنهادی گردهمایی برای جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ساعت پیشنهادی گردهمایی برای جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">مکان پیشنهادی گردهمایی برای جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">نیازمندی های گردهمایی و توضیحات تکمیلی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $iterator = 0;
        for ($i = 0; $i < $cnt; $i++) {
            $iterator++;
            $res[$i]['gatheringDate'] = (strtotime($res[$i]['gatheringDate']) > 0 ? $ut->greg_to_jal($res[$i]['gatheringDate']) : '');
            $description = (strlen(trim($res[$i]['generalDescription'])) > 0 ? $res[$i]['generalDescription'] : $res[$i]['description']);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['fname'] . ' ' . $res[$i]['lname'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['subject'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['reason'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['headline'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['gatheringDate'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['gatheringTime'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['gatheringPlace'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['requirements'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $description . '</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';


        $sql = "SELECT `unCode`,`enterBoss`,`relationship`,`manager1`,`manager2`,`substitute`,`substituteMembers`,`requirements`,`supervisor` FROM `meeting` WHERE `RowID`={$fmID}";
        $res = $db->ArrayQuery($sql);
        $iterator = 0;

        $htm .= '<table class="table table-bordered table-hover table-sm" id="otherInfoMeetingReportHTM1-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 30%;">سایر اطلاعات</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 60%;">مقدار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $infoNames = array('کد یکتا','نیاز به ورود مدیریت','ارتباط کاربران با یکدیگر','اعضا جلسه','مدیر/مدیران موازی','امکان معرفی جانشین برای اعضا','اعضا مجاز به انتخاب جانشین','نیازمندی های گردهمایی و توضیحات تکمیلی','ناظر / ناظران');
        for ($i=0;$i<9;$i++){
            $iterator++;
            $keyName = key($res[0]);
            switch ($iterator){
                case 2:
                    $res[0]["$keyName"] = (intval($res[0]["$keyName"]) == 1 ? 'دارد' : 'ندارد');
                    break;
                case 3:
                    if (intval($res[0]["$keyName"]) == 0){
                        $res[0]["$keyName"] = 'محرمانه بودن فرایندها';
                    }elseif (intval($res[0]["$keyName"]) == 1){
                        $res[0]["$keyName"] = 'تا هماهنگی های قبل گردهمایی';
                    }else{
                        $res[0]["$keyName"] = 'عادی';
                    }
                    break;
                case 4:
                    $sql1 = "SELECT `fname`,`lname` FROM `meeting_members` INNER JOIN `users` ON (`meeting_members`.`uid`=`users`.`RowID`) WHERE `meetingID`={$fmID}";
                    $res1 = $db->ArrayQuery($sql1);
                    $cnt = count($res1);
                    $members = array();
                    for ($j=0;$j<$cnt;$j++){
                        $members[] = $res1[$j]['fname'].' '.$res1[$j]['lname'];
                    }
                    $res[0]["$keyName"] = implode(' - ',$members);
                    break;
                case 5:
                case 7:
                case 9:
                    $rids = $res[0]["$keyName"];
                    $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID` IN ({$rids})";
                    $rst = $db->ArrayQuery($query);
                    $ccnt = count($rst);
                    $members = array();
                    for ($j=0;$j<$ccnt;$j++){
                        $members[] = $rst[$j]['fname'].' '.$rst[$j]['lname'];
                    }
                    $res[0]["$keyName"] = implode(' - ',$members);
                    break;
                case 6:
                    if (intval($res[0]["$keyName"]) == 0){
                        $res[0]["$keyName"] = 'وجود دارد';
                    }elseif (intval($res[0]["$keyName"]) == 1){
                        $res[0]["$keyName"] = 'وجود ندارد';
                    }else{
                        $res[0]["$keyName"] = 'انتخاب اعضا مجاز';
                    }
                    break;
            }

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]["$keyName"].'</td>';
            $htm .= '</tr>';
            next($res[0]);
        }

        $htm .= '</tbody>';
        $htm .= '</table>';

        $htm .= '<br>';

        $sql1 = "SELECT `manager1`,`substitute`,`substituteMembers` FROM `meeting` WHERE `RowID`={$fmID}";
        $res1 = $db->ArrayQuery($sql1);

        $sql2 = "SELECT * FROM `meeting_members` WHERE `meetingID`={$fmID}";
        $res2 = $db->ArrayQuery($sql2);
        $ccnt = count($res2);

        $htm .= '<table class="table table-bordered table-hover table-sm" id="otherInfoMeetingReportHTM2-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">اعضا جلسه</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">مجاز به انتخاب جانشین</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 20%;">جانشین</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 30%;">وضعیت</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $rids = explode(',',$res1[0]['substituteMembers']);
        $iterator = 0;
        for ($j=0;$j<$ccnt;$j++){
            $iterator++;

            $query1 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res2[$j]['uid']}";
            $rst1 = $db->ArrayQuery($query1);

            $query2 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res2[$j]['sid']}";
            $rst2 = $db->ArrayQuery($query2);

            switch ($res2[$j]['status']){
                case 0:
                    $status = 'مشخص نشده';
                    break;
                case 1:
                    $status = 'جانشین انتخاب نمی کنم';
                    break;
                case 2:
                    $status = 'خارج می شوم';
                    break;
                case 3:
                    $status = 'رصد خواهم کرد';
                    break;
                case 4:
                    $status = 'پیوستن بعد از تایید مدیر';
                    break;
            }

            switch ($res1[0]['substitute']){
                case 0:
                    $substitute = 'می باشد';
                    $color = 'green';
                    break;
                case 1:
                    $substitute = 'نمی باشد';
                    $color = 'red';
                    break;
                case 2:
                    $substitute = (in_array($res2[$j]['uid'],$rids) ? 'می باشد' : 'نمی باشد');
                    $color = (in_array($res2[$j]['uid'],$rids) ? 'green' : 'red');
                    break;
            }

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$rst1[0]['fname'].' '.$rst1[0]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;color: '.$color.'">'.$substitute.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$rst2[0]['fname'].' '.$rst2[0]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$status.'</td>';
            $htm .= '</tr>';
        }

        $htm .= '</tbody>';
        $htm .= '</table>';

        return $htm;
    }

    public function showReportMeetingWorkReportList($mid){
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT * FROM `meeting_jobs` WHERE `meetingID`={$mid} ORDER BY `uid` ASC,`validDate` ASC";
        $rst = $db->ArrayQuery($sql);
        $cnt = count($rst);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="showReportMeetingWorkReportList-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">عضو جلسه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">تاریخ ثبت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">ساعت ثبت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">مهلت انجام</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">شرح مسئولیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">وضعیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">تاریخ اتمام</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">ساعت اتمام</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">درصد پیشرفت کار</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">گزارشات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">وضعیت نهایی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">تایید کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">تاریخ تایید</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">ساعت تایید</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $iterator = 0;
        for ($i=0;$i<$cnt;$i++) {
            $iterator++;
            $rst[$i]['createDate'] = $ut->greg_to_jal($rst[$i]['createDate']);
            $rst[$i]['validDate'] = $ut->greg_to_jal($rst[$i]['validDate']);
            $rst[$i]['finishDate'] = (strtotime($rst[$i]['finishDate']) > 0 ? $ut->greg_to_jal($rst[$i]['finishDate']) : '');
            $rst[$i]['finishTime'] = ($rst[$i]['finishTime'] == '00:00:00' ? '' : $rst[$i]['finishTime']);
            $rst[$i]['confirmDate'] = (strtotime($rst[$i]['confirmDate']) > 0 ? $ut->greg_to_jal($rst[$i]['confirmDate']) : '');
            $color = (intval($rst[$i]['status']) == 1 ? 'color:green;' : 'color:red;');
            $color1 = (intval($rst[$i]['confirm']) == 1 ? 'color:green;' : 'color:red;');
            $rst[$i]['status'] = (intval($rst[$i]['status']) == 1 ? 'اتمام کار' : 'ناتمام');
            $rst[$i]['confirm'] = (intval($rst[$i]['confirm']) == 1 ? 'تایید شده' : 'در حال بررسی');

            $sql1 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$rst[$i]['uid']}";
            $rst1 = $db->ArrayQuery($sql1);

            $sql2 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$rst[$i]['confirmUid']}";
            $rst2 = $db->ArrayQuery($sql2);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst1[0]['fname'] . ' ' . $rst1[0]['lname'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['createDate'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['createTime'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['validDate'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['description'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;'.$color.'">' . $rst[$i]['status'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['finishDate'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['finishTime'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['percent'] . ' درصد</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="reportMeetingWorkReport(' . $rst[$i]['RowID'] . ')" ><i class="fas fa-edit"></i></button></td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;'.$color1.'">' . $rst[$i]['confirm'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst2[0]['fname'] . ' ' . $rst2[0]['lname'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['confirmDate'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $rst[$i]['confirmTime'] . '</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';

        return $htm;
    }

    public function getMeetingWorkReportHtm1($jobID){
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT * FROM `meeting_workreport` WHERE `jobID`={$jobID} AND `isEnable`=1";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="getMeetingWorkReportHtm-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ ثبت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ساعت ثبت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 75%;">شرح کار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $iterator = 0;
        for ($i=0;$i<$cnt;$i++) {
            $iterator++;
            $res[$i]['cDate'] = $ut->greg_to_jal($res[$i]['cDate']);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['cDate'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['cTime'] . '</td>';
            $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res[$i]['description'] . '</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';

        $sql1 = "SELECT `prerequisiteIDS` FROM `meeting_jobs` WHERE `RowID`={$jobID}";
        $res1 = $db->ArrayQuery($sql1);

        if (strlen(trim($res1[0]['prerequisiteIDS'])) > 0){
            $htm .= '<br>';
            $htm .= '<p>جدول پیش نیاز های این مسئولیت :</p>';

            $sql2 = "SELECT `meeting_jobs`.*,`fname`,`lname` FROM `meeting_jobs` INNER JOIN `users` ON (`meeting_jobs`.`uid`=`users`.`RowID`) WHERE `meeting_jobs`.`RowID` IN ({$res1[0]['prerequisiteIDS']})";
            $res2 = $db->ArrayQuery($sql2);
            $ccnt = count($res2);

            $htm .= '<table class="table table-bordered table-hover table-sm" id="getMeetingWorkReportHtm1-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">عضو جلسه</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">تاریخ ثبت</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">ساعت ثبت</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">مهلت انجام</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 26%;">شرح مسئولیت</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">وضعیت کار</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 9%;">وضعیت نهایی</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">درصد پیشرفت کار</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $iterator = 0;
            for ($i=0;$i<$ccnt;$i++) {
                $iterator++;
                $res2[$i]['createDate'] = $ut->greg_to_jal($res2[$i]['createDate']);
                $res2[$i]['validDate'] = $ut->greg_to_jal($res2[$i]['validDate']);
                $status = (intval($res2[$i]['status']) == 1 ? 'اتمام کار' : 'ناتمام');
                $confirm = (intval($res2[$i]['confirm']) == 1 ? 'تایید شده' : 'در حال بررسی');

                $color = (intval($res2[$i]['status']) == 1 ? 'color: green;' : 'color: red;');
                $color1 = (intval($res2[$i]['confirm']) == 1 ? 'color: green;' : 'color: red;');

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['fname'] . ' '.$res2[$i]['lname'].'</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['createDate'] . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['createTime'] . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['validDate'] . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['description'] . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;'.$color.'">' . $status . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;'.$color1.'">' . $confirm . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res2[$i]['percent'] . ' درصد</td>';
                $htm .= '</tr>';
            }
            $htm .= '</tbody>';
            $htm .= '</table>';
        }

        $sql3 = "SELECT `meeting_jobs_notconfirm`.*,`fname`,`lname` FROM `meeting_jobs_notconfirm` INNER JOIN `users` ON (`meeting_jobs_notconfirm`.`uid`=`users`.`RowID`) WHERE `jobID`={$jobID}";
        $res3 = $db->ArrayQuery($sql3);

        if (count($res3) > 0){
            $cntc = count($res3);
            $htm .= '<br>';
            $htm .= '<p>جدول علت های رد اتمام کار این مسئولیت :</p>';

            $htm .= '<table class="table table-bordered table-hover table-sm" id="getMeetingWorkReportHtm2-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">ثبت کننده</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ عدم تایید</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ساعت عدم تایید</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 55%;">علت رد اتمام کار</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';

            $iterator = 0;
            for ($i=0;$i<$cntc;$i++) {
                $iterator++;
                $res3[$i]['cDate'] = $ut->greg_to_jal($res3[$i]['cDate']);

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res3[$i]['fname'] . ' '.$res3[$i]['lname'].'</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res3[$i]['cDate'] . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res3[$i]['cTime'] . '</td>';
                $htm .= '<td style="text-align: center;vertical-align: middle;font-family: dubai-Regular;padding: 10px;">' . $res3[$i]['description'] . '</td>';
                $htm .= '</tr>';
            }
            $htm .= '</tbody>';
            $htm .= '</table>';
        }

        return $htm;
    }

    //++++++++++++++++++++++ دسته بندی جلسات +++++++++++++++++++++++

    public function getMeetingGroupList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('meetingGroupManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;

        $sql = "SELECT `RowID`,`groupName` FROM `meeting_grouping`";
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);

        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['groupName'] = $res[$y]['groupName'];
        }
        return $finalRes;
    }

    public function getMeetingGroupListCountRows(){
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `meeting_grouping`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function createMeetingGroup($name){
        $acm = new acm();
        if(!$acm->hasAccess('meetingGroupManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "INSERT INTO `meeting_grouping` (`groupName`) VALUES ('{$name}')";
        $db->Query($sql);
        $id = $db->InsertrdID();
        if(intval($id) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editMeetingGroup($mid,$name){
        $acm = new acm();
        if(!$acm->hasAccess('meetingGroupManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `meeting_grouping` SET `groupName`='{$name}' WHERE `RowID`={$mid} ";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if(intval($res)){
            return true;
        }else{
            return false;
        }
    }

    public function meetingGroupInfo($mid){
        $db = new DBi();
        $sql = "SELECT `groupName` FROM `meeting_grouping` WHERE `RowID` = ".$mid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("mid"=>$mid,"groupName"=>$res[0]['groupName']);
            return $res;
        }else{
            return false;
        }
    }

    private function getMeetingGroups(){
        $db = new DBi();
        $sql = "SELECT * FROM `meeting_grouping`";
        $res = $db->ArrayQuery($sql);
        return $res;
    }
	public function showProceedingsModal(){
		$db = new DBi();
        $sql = "SELECT * FROM `meeting_grouping`";
        $res = $db->ArrayQuery($sql);
        return $res;
	}
public function doCreateProceedingsComment($fmID,$data){
		$db = new DBi();
		$ut = new Utility();
		$check_job_sql="SELECT `RowID`,`meetingID` FROM `meeting_jobs` WHERE `meetingID` = {$fmID}";
		$job_result = $db->ArrayQuery($check_job_sql);
		if(count($job_result)>0){
			$result= array('msgType'=>"warning","message"=>"برای این جلسه مسئولیت  ثبت شده است و شرح جلسه قابل ویرایش نمی باشد");
			 
		}
		else{
			$sql = "UPDATE `meeting` SET `MeetingStartComment`='{$data}' WHERE `RowID`={$fmID}";
			$res = $db->query($sql);
			if($res){
				$result= array('msgType'=>"success","message"=>"شرح جلسه با موفقیت  ثبت شد");
				//$result="شرح جلسه با موفقیت  ثبت شد";
			}
		}
        return json_encode($result);
	}
}