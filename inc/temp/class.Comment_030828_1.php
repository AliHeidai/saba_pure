<?php
/**
 * Created by PhpStorm.
 * User: Majid Ebrahimi
 * Date: 10/20/2019
 * Time: 10:05 AM
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
class Comment{
    public $current_date="";
    public $current_time="";
    public function __construct(){
        // do nothing
        $ut=new  Utility();
        date_default_timezone_set('Asia/Tehran');
		$version = (float)phpversion();
		if($version>=8){
			 $this->current_time=date("H:i:s");
		}
		else
		{
			$current_date = date('Y-m-d');
			$current_shamsi_date = $ut->greg_to_jal($current_date);
			$shamsi_array=explode("/",$current_shamsi_date);
			$shamsi_day=intval($shamsi_array[2]);
			$shamsi_month=intval($shamsi_array[1]);
			if($shamsi_month<6){
				if($shamsi_month==1 && $shamsi_day>1){
					$this->current_time=date("H:i:s",strtotime('-1 hour'));
				}
				if($shamsi_month==1 && $shamsi_day==1){
					$this->current_time=date("H:i:s");
				}
				else{
					$this->current_time=date("H:i:s",strtotime('-1 hour'));
				}
			}
			elseif($shamsi_month==6 && $shamsi_day<31){
				$this->current_time=date("H:i:s",strtotime('-1 hour'));
			}
			elseif($shamsi_month>6 && $shamsi_day==31 ){
				$this->current_time=date("H:i:s");

			}
			else{
				$this->current_time=date("H:i:s");
			}
		}

      //  $this->update_code_tafzilii8();
    }

    public function get_comment_projects(){
        $db=new DBi();
        $ut=new Utility();
        $current_date=date('Y-m-d');

       // $project_sql="SELECT RowID,projectName FROM project where isEnable=1 AND DATE(validDate) > DATE('{$current_date}')";
        $project_sql="SELECT project_name,project_code FROM active_projects where isEnable=1 ";
        $res=$db->ArrayQuery($project_sql);
        //error_log(print_r($res,true));
        return $res;
    }

    public function getCommentManagementHtm(){
        $acm = new acm();
        $access_array=$acm->get_user_access($_SESSION['userid']);
       // in_array('commentManagement',$access_array)
       
        if(!in_array('commentManagement',$access_array)){
            die("access denied");
            exit;
        }
	
        $ut = new Utility();
		$full_access_user_see_partners_cartable_array=$ut->get_full_access_users(5);
		//$full_access_user_see_partners_cartable_array=[1,4,97,67];
        //**********************************نام پروژه ها  */
        $projects_detailes=$this->get_comment_projects();
        //**********************************نام پروژه ها  */
        $units = $this->getUnits();
        $CountUnits = count($units);

        $layers = $this->getOneLayers();
        $CountLayers = count($layers);

        $fundLayers = $this->getFundOneLayers();
        $CountFundLayers = count($fundLayers);

        $users = $this->getUsersAccessToComment();
        $cntu = count($users);
		
		$userInfo=$this->getUserInfo($_SESSION['userid']);
		$userUnit=$userInfo['unitID'];

        $layer1=$this->getLayer1();
        $countLayer1=count($layer1);

        $layer2=$this->getLayer2();
        $countLayer2=count($layer2);

        $layer3=$this->getLayer3();
        $countLayer3=count($layer3);

        $hiddenContentId[] = "hiddenCommentBody";
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
        
        if(in_array('commentManagement',$access_array)) {
            $pagename[$x] = "اظهارنظر و درخواست پرداخت وجه";
            $pageIcon[$x] = "fa-file-invoice-dollar";
            $contentId[$x] = "commentManageBody";
            $menuItems[$x] = 'commentManageTabID';

            $bottons1 = array();
            $headerSearch1 = array();

            $b = 0;
            $bottons1[$b]['title'] = "ثبت اظهارنظر";
            $bottons1[$b]['jsf'] = "createPayComment";
            $bottons1[$b]['icon'] = "fa-plus-square";
            $bottons1[$b]['id'] = "create_pay_comment";
            $b++;

            $bottons1[$b]['title'] = "ویرایش اظهارنظر";
            $bottons1[$b]['jsf'] = "editPayComment";
            $bottons1[$b]['icon'] = "fa-edit";
            $bottons1[$b]['id'] = "edit_pay_comment";
            $b++;

            $bottons1[$b]['title'] = "پیوست فایل";
            $bottons1[$b]['jsf'] = "attachFileToComment";
            $bottons1[$b]['icon'] = "fa-link";
            $bottons1[$b]['id'] = "attach_pay_comment_file";
            $b++;

            $bottons1[$b]['title'] = "ارجاع اظهارنظر";
           // $bottons1[$b]['jsf'] = "sendPayComment";
            $bottons1[$b]['jsf'] = "sendPayCommentPrimary";
            $bottons1[$b]['icon'] = "fa-paper-plane";
            $bottons1[$b]['id'] = "send_pay_comment";
            $b++;

            $bottons1[$b]['title'] = "لیست تنخواه";
            $bottons1[$b]['jsf'] = "showAttachedFundToComment";
            $bottons1[$b]['icon'] = "fa-list";
            $bottons1[$b]['id'] = "comment_fund_list";
           
            if(in_array('transferToPayKesho',$access_array)) {
                $b++;
                $bottons1[$b]['title'] = "انتقال به کشو پرداخت";
                $bottons1[$b]['jsf'] = "transferToPayKesho";
                $bottons1[$b]['icon'] = "fa-exchange-alt";
                $bottons1[$b]['id'] = "transfer_to_kesho";
            }

            if(in_array('cancellationPayComment',$access_array)) {
                $b++;
                $bottons1[$b]['title'] = "ابطال اظهارنظر";
                $bottons1[$b]['jsf'] = "cancellationPayComment";
                $bottons1[$b]['icon'] = "fa-minus-square";
                $bottons1[$b]['id'] = "delete_pay_comment";
            }
            if(in_array('autoSendCommentMannage',$access_array)) {
                $b++;
                $bottons1[$b]['title'] = " مدیریت ارجاع خودکار";
                $bottons1[$b]['jsf'] = "manageAutomaticSend";
                $bottons1[$b]['icon'] = "fa-solid fa-meteor";
                $bottons1[$b]['icon'] = "comment_autosend";
            }
            if(in_array('managePartnersCartable',$access_array)){
                $b++;
                $bottons1[$b]['title'] = " مدیریت کارتابل همکاران";
                $bottons1[$b]['jsf'] = "managePartnersCartable";
                $bottons1[$b]['icon'] = "fa-solid fa-meteor";
                $bottons1[$b]['icon'] = "manage_partners_cartable";
            }

            $a = 0;
            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "90px";
            $headerSearch1[$a]['id'] = "commentManageSDateSearch";
            $headerSearch1[$a]['title'] = "از تاریخ";
            $headerSearch1[$a]['placeholder'] = "از تاریخ";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "90px";
            $headerSearch1[$a]['id'] = "commentManageEDateSearch";
            $headerSearch1[$a]['title'] = "تا تاریخ";
            $headerSearch1[$a]['placeholder'] = "تا تاریخ";
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['id'] = "commentManageUnitSearch";
            $headerSearch1[$a]['title'] = "واحد درخواست کننده";
            $headerSearch1[$a]['width'] = "200px";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = "واحد درخواست کننده";
            $headerSearch1[$a]['options'][0]["value"] = 0;
            for ($i=0;$i<$CountUnits;$i++){
                $headerSearch1[$a]['options'][$i+1]["title"] = $units[$i]['unitName'];
                $headerSearch1[$a]['options'][$i+1]["value"] = $units[$i]['RowID'];
            }
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['id'] = "commentManageConsumerUnitSearch";
            $headerSearch1[$a]['title'] = "واحد مصرف کننده";
            $headerSearch1[$a]['width'] = "200px";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = "واحد مصرف کننده";
            $headerSearch1[$a]['options'][0]["value"] = 0;
            for ($i=0;$i<$CountUnits;$i++){
                $headerSearch1[$a]['options'][$i+1]["title"] = $units[$i]['unitName'];
                $headerSearch1[$a]['options'][$i+1]["value"] = $units[$i]['RowID'];
            }
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "commentManageAccNameSearch";
            $headerSearch1[$a]['title'] = "نام طرف حساب";
            $headerSearch1[$a]['placeholder'] = "نام طرف حساب";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "commentManageTowardSearch";
            $headerSearch1[$a]['title'] = "بابت";
            $headerSearch1[$a]['placeholder'] = "بابت";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "commentManageUncodeSearch";
            $headerSearch1[$a]['title'] = "کد یکتا";
            $headerSearch1[$a]['placeholder'] = "کد یکتا";
            $a++;

            $headerSearch1[$a]['type'] = "text";
            $headerSearch1[$a]['width'] = "150px";
            $headerSearch1[$a]['id'] = "commentManageAmountSearch";
            $headerSearch1[$a]['onkeyup'] = "onkeyup=addSeprator()";
            $headerSearch1[$a]['title'] = "مبلغ";
            $headerSearch1[$a]['placeholder'] = "مبلغ";
            $a++;
            //--------------------------------------------------------- اضافه کردن  search بر اساس  سرگروه زیرگروه و زیرگروه فرعی
            // $layer1=$this->getLayer1();
            // $countLayer1=count($layer1);
            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['id'] = "commentManageLayerOneSearch";
            $headerSearch1[$a]['title'] = "سرگروه  ";
            $headerSearch1[$a]['width'] = "120px";
            $headerSearch1[$a]['onchange'] = "onchange=get_layer_two_options(this)";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = " سرگروه";
            $headerSearch1[$a]['options'][0]["value"] = 0;
            for ($i=0;$i<$countLayer1;$i++){
                $headerSearch1[$a]['options'][$i+1]["title"] = $layer1[$i]['layerName'];
                $headerSearch1[$a]['options'][$i+1]["value"] = $layer1[$i]['RowID'];
            }
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['id'] = "commentManageLayerTwoSearch";
            $headerSearch1[$a]['title'] = " زیرگروه";
            $headerSearch1[$a]['width'] = "120px";
            $headerSearch1[$a]['onchange'] = "onchange=get_layer_three_options(this)";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = "  زیرگروه";
            $headerSearch1[$a]['options'][0]["value"] = 0;
            for ($i=0;$i<$countLayer2;$i++){
                $headerSearch1[$a]['options'][$i+1]["title"] = $layer2[$i]['layerName'];
                $headerSearch1[$a]['options'][$i+1]["value"] = $$layer2[$i]['RowID'];
            }
            $a++;

            $headerSearch1[$a]['type'] = "select";
            $headerSearch1[$a]['id'] = "commentManageLayerThreeSearch";
            $headerSearch1[$a]['title'] = "زیرگروه فرعی";
            $headerSearch1[$a]['width'] = "120px";
            $headerSearch1[$a]['options'] = array();
            $headerSearch1[$a]['options'][0]["title"] = "زیرگروه فرعی  ";
            $headerSearch1[$a]['options'][0]["value"] = 0;
            for ($i=0;$i<$countLayer3;$i++){
                $headerSearch1[$a]['options'][$i+1]["title"] = $layer3[$i]['layerName'];
                $headerSearch1[$a]['options'][$i+1]["value"] = $layer3[$i]['RowID'];
            }
            $a++;

            //--------------------------------------------------------- اضافه کردن  search بر اساس  سرگروه زیرگروه و زیرگروه فرعی

            $headerSearch1[$a]['type'] = "btn";
            $headerSearch1[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch1[$a]['jsf'] = "showPayCommentManageList";

            $bottons[$y] = $bottons1;
            $headerSearch[$z] = $headerSearch1;

            $manifold++;
            $access[] = 1;
            $x++;
            $y++;
            $z++;
        }
        if(in_array('accountManagement',$access_array)) {
            $pagename[$x] = "طرف حساب";
            $pageIcon[$x] = "fa-user";
            $contentId[$x] = "accountManageBody";
            $menuItems[$x] = 'accountManageTabID';

            $bottons2 = array();
            $headerSearch2 = array();

            $n = 0;
            if(in_array('createAccount',$access_array)) {
                $bottons2[$n]['title'] = "ثبت طرف حساب جدید";
                $bottons2[$n]['jsf'] = "createAccount";
                $bottons2[$n]['icon'] = "fa-plus-square";
                $n++;
            }

            $bottons2[$n]['title'] = "ویرایش طرف حساب";
            $bottons2[$n]['jsf'] = "editAccount";
            $bottons2[$n]['icon'] = "fa-edit";

            $a = 0;
            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "250px";
            $headerSearch2[$a]['id'] = "accountManageAccNameSearch";
            $headerSearch2[$a]['title'] = "نام طرف حساب";
            $headerSearch2[$a]['placeholder'] = "نام طرف حساب";
            $a++;

            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "100px";
            $headerSearch2[$a]['id'] = "accountManageCodeSearch";
            $headerSearch2[$a]['title'] = "کد تفضیلی";
            $headerSearch2[$a]['placeholder'] = "کد تفضیلی";
            $a++;

            $headerSearch2[$a]['type'] = "btn";
            $headerSearch2[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch2[$a]['jsf'] = "showAccountManageList";

            $bottons[$y] = $bottons2;
            $headerSearch[$z] = $headerSearch2;

            $manifold++;
            $access[] = 2;
            $x++;
            $y++;
            $z++;
        }
        //****************************** active projects***************************** */
        if($acm->hasAccess('activeProjectsManagement')) {
            $pagename[$x] = "پروژه ";
            $pageIcon[$x] = "fa-user";
            $contentId[$x] = "activeProjectManageBody";
            $menuItems[$x] = 'activeProjectManageTabID';

            $bottons2 = array();
            $headerSearch2 = array();

            $n = 0;
            if($acm->hasAccess('createActiveProject')) {
                $bottons2[$n]['title'] = "ثبت پروژه جدید";
                $bottons2[$n]['jsf'] = "createActiveProject";
                $bottons2[$n]['icon'] = "fa-plus-square";
                $n++;
            }

            $bottons2[$n]['title'] = "ویرایش پروژه";
            $bottons2[$n]['jsf'] = "editActiveProject";
            $bottons2[$n]['icon'] = "fa-edit";

            $a = 0;
            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "250px";
            $headerSearch2[$a]['id'] = "activeProjectManageAccNameSearch";
            $headerSearch2[$a]['title'] = "نام  پروژه";
            $headerSearch2[$a]['placeholder'] = "نام  پروژه";
            $a++;

            $headerSearch2[$a]['type'] = "text";
            $headerSearch2[$a]['width'] = "100px";
            $headerSearch2[$a]['id'] = "activeProjectManageCodeSearch";
            $headerSearch2[$a]['title'] = "  کد تفضیلی پروژه";
            $headerSearch2[$a]['placeholder'] = " کد تفضیلی پروژه";
            $a++;

            $headerSearch2[$a]['type'] = "btn";
            $headerSearch2[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch2[$a]['jsf'] = "showAccountManageList";

            $bottons[$y] = $bottons2;
            $headerSearch[$z] = $headerSearch2;

            $manifold++;
            $access[] = 2;
            $x++;
            $y++;
            $z++;
        }
        //****************************** active projects***************************** */
        if($acm->hasAccess('relatedUnits')){
            $pagename[$x] = "واحد های مربوطه";
            $pageIcon[$x] = "fa-pencil-ruler";
            $contentId[$x] = "commentUnitManageBody";
            $menuItems[$x] = 'unitManageTabID';

            $bottons3 = array();
            $headerSearch3 = array();

            $bottons3[0]['title'] = "ایجاد واحد جدید";
            $bottons3[0]['jsf'] = "createCommentUnit";
            $bottons3[0]['icon'] = "fa-plus-square";

            $bottons3[1]['title'] = "ویرایش";
            $bottons3[1]['jsf'] = "editCommentUnit";
            $bottons3[1]['icon'] = "fa-edit";

            $bottons[$y] = $bottons3;
            $headerSearch[$z] = $headerSearch3;

            $manifold++;
            $access[] = 3;
            $x++;
            $y++;
            $z++;
        }
        if(in_array('depositorsManage',$access_array)){
            $pagename[$x] = "واریزکنندگان";
            $pageIcon[$x] = "fa-credit-card";
            $contentId[$x] = "depositorsManageBody";
            $menuItems[$x] = 'depositorsManageTabID';

            $bottons4 = array();
            $headerSearch4 = array();

            $bottons4[0]['title'] = "ثبت واریز کننده جدید";
            $bottons4[0]['jsf'] = "createDepositor";
            $bottons4[0]['icon'] = "fa-plus-square";

            $a = 0;
            $headerSearch4[$a]['type'] = "text";
            $headerSearch4[$a]['width'] = "300px";
            $headerSearch4[$a]['id'] = "depositorManageNameSearch";
            $headerSearch4[$a]['title'] = "قسمتی از نام واریزکننده";
            $headerSearch4[$a]['placeholder'] = "قسمتی از نام، نام خانوادگی یا نام بانک";
            $a++;

            $headerSearch4[$a]['type'] = "text";
            $headerSearch4[$a]['width'] = "150px";
            $headerSearch4[$a]['id'] = "depositorManageCodeSearch";
            $headerSearch4[$a]['title'] = "کد تفضیلی";
            $headerSearch4[$a]['placeholder'] = "کد تفضیلی";
            $a++;

            $headerSearch4[$a]['type'] = "btn";
            $headerSearch4[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch4[$a]['jsf'] = "showDepositorManageList";

            $bottons[$y] = $bottons4;
            $headerSearch[$z] = $headerSearch4;

            $manifold++;
            $access[] = 4;
            $x++;
            $y++;
            $z++;
        }
        if(in_array('payCommentManage',$access_array)){
            $pagename[$x] = "پرداخت اظهارنظر";
            $pageIcon[$x] = "fa-money-check-alt";
            $contentId[$x] = "payCommentManageBody";
            $menuItems[$x] = 'payCommentManageTabID';

            $bottons5 = array();
            $headerSearch5 = array();

            $bottons5[0]['title'] = "خروجی اکسل از کشو پرداخت";
            $bottons5[0]['jsf'] = "createPayCommentExcel";
            $bottons5[0]['icon'] = "fa-file-excel";

            $a = 0;
            $headerSearch5[$a]['type'] = "text";
            $headerSearch5[$a]['width'] = "150px";
            $headerSearch5[$a]['id'] = "payCommentManageAccNameSearch";
            $headerSearch5[$a]['title'] = "نام طرف حساب";
            $headerSearch5[$a]['placeholder'] = "نام طرف حساب";
            $a++;

            $headerSearch5[$a]['type'] = "text";
            $headerSearch5[$a]['width'] = "150px";
            $headerSearch5[$a]['id'] = "payCommentManageTowardSearch";
            $headerSearch5[$a]['title'] = "بابت";
            $headerSearch5[$a]['placeholder'] = "بابت";
            $a++;

            $headerSearch5[$a]['type'] = "text";
            $headerSearch5[$a]['width'] = "150px";
            $headerSearch5[$a]['id'] = "payCommentManageAmountSearch";
            $headerSearch5[$a]['title'] = "مبلغ";
            $headerSearch5[$a]['placeholder'] = "مبلغ";
            $a++;

            $headerSearch5[$a]['type'] = "select";
            $headerSearch5[$a]['id'] = "payCommentManageTypeDaySearch";
            $headerSearch5[$a]['title'] = "روزهای اظهارنظر";
            $headerSearch5[$a]['width'] = "200px";
            $headerSearch5[$a]['options'] = array();
            $headerSearch5[$a]['options'][0]["title"] = "اظهارنظرهای روز جاری و ماقبل";
            $headerSearch5[$a]['options'][0]["value"] = 0;
            $headerSearch5[$a]['options'][1]["title"] = "اظهارنظرهای آتی";
            $headerSearch5[$a]['options'][1]["value"] = 1;
            $a++;

            $headerSearch5[$a]['type'] = "btn";
            $headerSearch5[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch5[$a]['jsf'] = "showFinalPayCommentManageList";
            $a++;

            $headerSearch5[$a]['type'] = "btn";
            $headerSearch5[$a]['title'] = "مرتب سازی سررسید نقدی&nbsp;&nbsp;<i class='fa fa-sort'></i>";
            $headerSearch5[$a]['jsf'] = "showSortPayCommentManageList";
            $a++;

            $headerSearch5[$a]['type'] = "btn";
            $headerSearch5[$a]['title'] = "مرتب سازی سررسید چک&nbsp;&nbsp;<i class='fa fa-sort'></i>";
            $headerSearch5[$a]['jsf'] = "showSortPayCommentCheckManageList";

            $bottons[$y] = $bottons5;
            $headerSearch[$z] = $headerSearch5;

            $manifold++;
            $access[] = 5;
            $x++;
            $y++;
            $z++;
        }
        if(in_array('financialConfirmation',$access_array)){
            $pagename[$x] = "تاییدیه مالی";
            $pageIcon[$x] = "fa-check-square";
            $contentId[$x] = "financialConfirmationBody";
            $menuItems[$x] = 'financialConfirmationTabID';

            $bottons6 = array();
            $headerSearch6 = array();

            $bottons6[0]['title'] = "خروجی اکسل چک ها";
            $bottons6[0]['jsf'] = "getBankCheckExcel";
            $bottons6[0]['icon'] = "fa-file-excel";

            $a = 0;
            $headerSearch6[$a]['type'] = "text";
            $headerSearch6[$a]['width'] = "90px";
            $headerSearch6[$a]['id'] = "financialConfirmationSDateSearch";
            $headerSearch6[$a]['title'] = "از تاریخ";
            $headerSearch6[$a]['placeholder'] = "از تاریخ";
            $a++;

            $headerSearch6[$a]['type'] = "text";
            $headerSearch6[$a]['width'] = "90px";
            $headerSearch6[$a]['id'] = "financialConfirmationEDateSearch";
            $headerSearch6[$a]['title'] = "تا تاریخ";
            $headerSearch6[$a]['placeholder'] = "تا تاریخ";
            $a++;

            $headerSearch6[$a]['type'] = "text";
            $headerSearch6[$a]['width'] = "150px";
            $headerSearch6[$a]['id'] = "financialConfirmationAccNameSearch";
            $headerSearch6[$a]['title'] = "نام طرف حساب";
            $headerSearch6[$a]['placeholder'] = "نام طرف حساب";
            $a++;

            $headerSearch6[$a]['type'] = "text";
            $headerSearch6[$a]['width'] = "150px";
            $headerSearch6[$a]['id'] = "financialConfirmationTowardSearch";
            $headerSearch6[$a]['title'] = "بابت";
            $headerSearch6[$a]['placeholder'] = "بابت";
            $a++;

            $headerSearch6[$a]['type'] = "text";
            $headerSearch6[$a]['width'] = "150px";
            $headerSearch6[$a]['id'] = "financialConfirmationAmountSearch";
            $headerSearch6[$a]['title'] = "مبلغ";
            $headerSearch6[$a]['placeholder'] = "مبلغ";
            $a++;

            $headerSearch6[$a]['type'] = "btn";
            $headerSearch6[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch6[$a]['jsf'] = "showFinancialConfirmationList";

            $bottons[$y] = $bottons6;
            $headerSearch[$z] = $headerSearch6;

            $manifold++;
            $access[] = 6;
            $x++;
            $y++;
            $z++;
        }
        if(in_array('tempFinancialKesho',$access_array)){
            $pagename[$x] = "کشو موقت مالی";
            $pageIcon[$x] = "fa-check-square";
            $contentId[$x] = "tempFinancialKeshoBody";
            $menuItems[$x] = 'tempFinancialKeshoTabID';

            $bottons7 = array();
            $headerSearch7 = array();

            $bottons[$y] = $bottons7;
            $headerSearch[$z] = $headerSearch7;

            $manifold++;
            $access[] = 7;
            $x++;
            $y++;
            $z++;
        }
        if (in_array('sendCommentManagement',$access_array)) {
            $pagename[$x] = "اظهار نظرهای ارسال شده";
            $pageIcon[$x] = "fa-paper-plane";
            $contentId[$x] = "commentSendManageBody";
            $menuItems[$x] = 'commentSendManageTabID';

            $bottons8 = array();
            $headerSearch8 = array();

            $a = 0;
            $headerSearch8[$a]['type'] = "text";
            $headerSearch8[$a]['width'] = "90px";
            $headerSearch8[$a]['id'] = "commentSendManageSDateSearch";
            $headerSearch8[$a]['title'] = "از تاریخ";
            $headerSearch8[$a]['placeholder'] = "از تاریخ";
            $a++;

            $headerSearch8[$a]['type'] = "text";
            $headerSearch8[$a]['width'] = "90px";
            $headerSearch8[$a]['id'] = "commentSendManageEDateSearch";
            $headerSearch8[$a]['title'] = "تا تاریخ";
            $headerSearch8[$a]['placeholder'] = "تا تاریخ";
            $a++;

            $headerSearch8[$a]['type'] = "select";
            $headerSearch8[$a]['id'] = "commentSendManageUnitSearch";
            $headerSearch8[$a]['title'] = "واحد درخواست کننده";
            $headerSearch8[$a]['width'] = "200px";
            $headerSearch8[$a]['options'] = array();
            $headerSearch8[$a]['options'][0]["title"] = "واحد درخواست کننده";
            $headerSearch8[$a]['options'][0]["value"] = 0;
            for ($i = 0; $i < $CountUnits; $i++) {
                $headerSearch8[$a]['options'][$i + 1]["title"] = $units[$i]['unitName'];
                $headerSearch8[$a]['options'][$i + 1]["value"] = $units[$i]['RowID'];
            }
            $a++;

            $headerSearch8[$a]['type'] = "select";
            $headerSearch8[$a]['id'] = "commentSendManageConsumerUnitSearch";
            $headerSearch8[$a]['title'] = "واحد مصرف کننده";
            $headerSearch8[$a]['width'] = "200px";
            $headerSearch8[$a]['options'] = array();
            $headerSearch8[$a]['options'][0]["title"] = "واحد مصرف کننده";
            $headerSearch8[$a]['options'][0]["value"] = 0;
            for ($i = 0; $i < $CountUnits; $i++) {
                $headerSearch8[$a]['options'][$i + 1]["title"] = $units[$i]['unitName'];
                $headerSearch8[$a]['options'][$i + 1]["value"] = $units[$i]['RowID'];
            }
            $a++;

            $headerSearch8[$a]['type'] = "text";
            $headerSearch8[$a]['width'] = "150px";
            $headerSearch8[$a]['id'] = "commentSendManageAccNameSearch";
            $headerSearch8[$a]['title'] = "نام طرف حساب";
            $headerSearch8[$a]['placeholder'] = "نام طرف حساب";
            $a++;

            $headerSearch8[$a]['type'] = "text";
            $headerSearch8[$a]['width'] = "150px";
            $headerSearch8[$a]['id'] = "commentSendManageTowardSearch";
            $headerSearch8[$a]['title'] = "بابت";
            $headerSearch8[$a]['placeholder'] = "بابت";
            $a++;

            $headerSearch8[$a]['type'] = "text";
            $headerSearch8[$a]['width'] = "150px";
            $headerSearch8[$a]['id'] = "commentSendManageUncodeSearch";
            $headerSearch8[$a]['title'] = "کد یکتا";
            $headerSearch8[$a]['placeholder'] = "کد یکتا";
            $a++;

            $headerSearch8[$a]['type'] = "btn";
            $headerSearch8[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch8[$a]['jsf'] = "showPayCommentSendManageList";

            $bottons[$y] = $bottons8;
            $headerSearch[$z] = $headerSearch8;

            $manifold++;
            $access[] = 8;
            $x++;
            $y++;
            $z++;
        }
        if (in_array('contractManagement',$access_array)) {
            $pagename[$x] = "قراردادها";
            $pageIcon[$x] = "fa-file-signature";
            $contentId[$x] = "contractManagementBody";
            $menuItems[$x] = 'contractManagementTabID';

            $bottons9 = array();
            $headerSearch9 = array();

            $b = 0;
            if (in_array('editCreateContractComment',$access_array)) {
                $bottons9[$b]['title'] = "ثبت قرارداد جدید";
                $bottons9[$b]['jsf'] = "createContract";
                $bottons9[$b]['icon'] = "fa-plus-square";
                $b++;

                $bottons9[$b]['title'] = "ویرایش قرارداد";
                $bottons9[$b]['jsf'] = "editContract";
                $bottons9[$b]['icon'] = "fa-edit";
                $b++;

                $bottons9[$b]['title'] = "پیوست فایل";
                $bottons9[$b]['jsf'] = "contractAttachFile";
                $bottons9[$b]['icon'] = "fa-link";
                $b++;
/*                $bottons9[3]['title'] = "تاریخ های صدور اظهانظر";
                $bottons9[3]['jsf'] = "contractCommentDates";
                $bottons9[3]['icon'] = "fa-plus-square";*/
            }
            if (in_array('contractAddendumManage',$access_array)) {
                $bottons9[$b]['title'] = "مدیریت الحاقیه";
                $bottons9[$b]['jsf'] = "contractAddendum";
                $bottons9[$b]['icon'] = "fa-link";
                $b++;
                
            }
            if (in_array('expirationContract',$access_array)) {
                $bottons9[$b]['title'] = "بایگانی / تمدید";
                $bottons9[$b]['jsf'] = "archiveExtensionContract";
                $bottons9[$b]['icon'] = "fa-minus-square";
            }

            $a = 0;
            $headerSearch9[$a]['type'] = "text";
            $headerSearch9[$a]['width'] = "150px";
            $headerSearch9[$a]['id'] = "contractManageSDateSearch";
            $headerSearch9[$a]['title'] = "از تاریخ";
            $headerSearch9[$a]['placeholder'] = "تاریخ شروع قرارداد (به بعد)";
            $a++;

            $headerSearch9[$a]['type'] = "text";
            $headerSearch9[$a]['width'] = "150px";
            $headerSearch9[$a]['id'] = "contractManageEDateSearch";
            $headerSearch9[$a]['title'] = "تا تاریخ";
            $headerSearch9[$a]['placeholder'] = "تاریخ اتمام قرارداد (به قبل)";
            $a++;

            $headerSearch9[$a]['type'] = "text";
            $headerSearch9[$a]['width'] = "120px";
            $headerSearch9[$a]['id'] = "contractManageCNumberSearch";
            $headerSearch9[$a]['title'] = "شماره قرارداد";
            $headerSearch9[$a]['placeholder'] = "شماره قرارداد";
            $a++;

            $headerSearch9[$a]['type'] = "text";
            $headerSearch9[$a]['width'] = "200px";
            $headerSearch9[$a]['id'] = "contractManageAccountSearch";
            $headerSearch9[$a]['title'] = "طرف مقابل";
            $headerSearch9[$a]['placeholder'] = "طرف مقابل";
            $a++;

            $headerSearch9[$a]['type'] = "text";
            $headerSearch9[$a]['width'] = "120px";
            $headerSearch9[$a]['id'] = "contractManageAmountSearch";
            $headerSearch9[$a]['title'] = "مبلغ کل قرارداد";
            $headerSearch9[$a]['placeholder'] = "مبلغ کل قرارداد";
            $a++;

            $headerSearch9[$a]['type'] = "text";
            $headerSearch9[$a]['width'] = "120px";
            $headerSearch9[$a]['id'] = "contractManageCreditSearch";
            $headerSearch9[$a]['title'] = "مدت قرارداد";
            $headerSearch9[$a]['placeholder'] = "مدت قرارداد";
            $a++;

            $headerSearch9[$a]['type'] = "select";
            $headerSearch9[$a]['width'] = "100px";
            $headerSearch9[$a]['id'] = "contractManageStatusSearch";
            $headerSearch9[$a]['title'] = "وضعیت قرارداد";
            $headerSearch9[$a]['options'] = array();
            $headerSearch9[$a]['options'][0]["title"] = "جاری";
            $headerSearch9[$a]['options'][0]["value"] = 1;
            $headerSearch9[$a]['options'][1]["title"] = "بایگانی شده";
            $headerSearch9[$a]['options'][1]["value"] = 0;
            $a++;

            $headerSearch9[$a]['type'] = "btn";
            $headerSearch9[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch9[$a]['jsf'] = "showContractManageList";

            $bottons[$y] = $bottons9;
            $headerSearch[$z] = $headerSearch9;

            $manifold++;
            $access[] = 9;
            $x++;
            $y++;
            $z++;
        }
        if (in_array('receivedCustomerManage',$access_array)) {
            $pagename[$x] = "دریافتی از مشتری";
            $pageIcon[$x] = "fa-dollar-sign";
            $contentId[$x] = "receivedCustomerManageBody";
            $menuItems[$x] = 'receivedCustomerManageTabID';

            $bottons10 = array();
            $headerSearch10 = array();

            $bottons10[0]['title'] = "ثبت دریافتی";
            $bottons10[0]['jsf'] = "createReceivedCustomer";
            $bottons10[0]['icon'] = "fa-plus-square";

            $bottons10[1]['title'] = "ویرایش دریافتی";
            $bottons10[1]['jsf'] = "editReceivedCustomer";
            $bottons10[1]['icon'] = "fa-edit";

            $bottons10[2]['title'] = "پیوست فایل";
            $bottons10[2]['jsf'] = "attachReceivedCustomerFile";
            $bottons10[2]['icon'] = "fa-link";

            $bottons10[3]['title'] = "خروجی اکسل";
            $bottons10[3]['jsf'] = "getExcelReceivedCustomer";
            $bottons10[3]['icon'] = "fa-file-excel";

            $a = 0;
            $headerSearch10[$a]['type'] = "text";
            $headerSearch10[$a]['width'] = "100px";
            $headerSearch10[$a]['id'] = "receivedCustomerSDateSearch";
            $headerSearch10[$a]['title'] = "از تاریخ";
            $headerSearch10[$a]['placeholder'] = "از تاریخ";
            $a++;

            $headerSearch10[$a]['type'] = "text";
            $headerSearch10[$a]['width'] = "100px";
            $headerSearch10[$a]['id'] = "receivedCustomerEDateSearch";
            $headerSearch10[$a]['title'] = "تا تاریخ";
            $headerSearch10[$a]['placeholder'] = "تا تاریخ";
            $a++;

            $headerSearch10[$a]['type'] = "select";
            $headerSearch10[$a]['id'] = "receivedCustomerRTypeSearch";
            $headerSearch10[$a]['title'] = "نوع دریافتی";
            $headerSearch10[$a]['width'] = "120px";
            $headerSearch10[$a]['options'] = array();
            $headerSearch10[$a]['options'][0]["title"] = 'نوع دریافتی';
            $headerSearch10[$a]['options'][0]["value"] = -1;
            $headerSearch10[$a]['options'][1]["title"] = 'بابت فروش';
            $headerSearch10[$a]['options'][1]["value"] = 0;
            $headerSearch10[$a]['options'][2]["title"] = 'بابت چک برگشتی';
            $headerSearch10[$a]['options'][2]["value"] = 1;
            $a++;

            $headerSearch10[$a]['type'] = "select";
            $headerSearch10[$a]['id'] = "receivedCustomerRMethodSearch";
            $headerSearch10[$a]['title'] = "روش دریافتی";
            $headerSearch10[$a]['width'] = "120px";
            $headerSearch10[$a]['options'] = array();
            $headerSearch10[$a]['options'][0]["title"] = 'روش دریافتی';
            $headerSearch10[$a]['options'][0]["value"] = -1;
            $headerSearch10[$a]['options'][1]["title"] = 'واریزی به بانک ها';
            $headerSearch10[$a]['options'][1]["value"] = 1;
            $headerSearch10[$a]['options'][2]["title"] = 'pos';
            $headerSearch10[$a]['options'][2]["value"] = 2;
            $headerSearch10[$a]['options'][3]["title"] = 'چک';
            $headerSearch10[$a]['options'][3]["value"] = 3;
            $headerSearch10[$a]['options'][4]["title"] = 'نقدی';
            $headerSearch10[$a]['options'][4]["value"] = 4;
            $a++;

            $headerSearch10[$a]['type'] = "text";
            $headerSearch10[$a]['width'] = "150px";
            $headerSearch10[$a]['id'] = "receivedCustomerCNameSearch";
            $headerSearch10[$a]['title'] = "نام مشتری";
            $headerSearch10[$a]['placeholder'] = "نام مشتری";
            $a++;

            $headerSearch10[$a]['type'] = "text";
            $headerSearch10[$a]['width'] = "150px";
            $headerSearch10[$a]['id'] = "receivedCustomerRAmountSearch";
            $headerSearch10[$a]['title'] = "مبلغ";
            $headerSearch10[$a]['placeholder'] = "مبلغ";
            $a++;

            $headerSearch10[$a]['type'] = "btn";
            $headerSearch10[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch10[$a]['jsf'] = "showReceivedCustomerManageList";

            $bottons[$y] = $bottons10;
            $headerSearch[$z] = $headerSearch10;

            $manifold++;
            $access[] = 10;
            $x++;
            $y++;
            $z++;
        }
        if (in_array('fundListManage',$access_array)) {
            $pagename[$x] = "اجزا تنخواه";
            $pageIcon[$x] = "fa-puzzle-piece";
            $contentId[$x] = "fundListManageBody";
            $menuItems[$x] = 'fundListManageTabID';

            $bottons11 = array();
            $headerSearch11 = array();

            $bottons11[0]['title'] = "ثبت تنخواه";
            $bottons11[0]['jsf'] = "createFundList";
            $bottons11[0]['icon'] = "fa-plus-square";

            $bottons11[1]['title'] = "ویرایش تنخواه";
            $bottons11[1]['jsf'] = "editFundList";
            $bottons11[1]['icon'] = "fa-edit";

            $bottons11[2]['title'] = "پیوست اظهارنظر";
            $bottons11[2]['jsf'] = "attachCommentToFund";
            $bottons11[2]['icon'] = "fa-link";

            $a = 0;
            $headerSearch11[$a]['type'] = "text";
            $headerSearch11[$a]['width'] = "150px";
            $headerSearch11[$a]['id'] = "fundListManageNameSearch";
            $headerSearch11[$a]['title'] = "نام تنخواه";
            $headerSearch11[$a]['placeholder'] = "نام تنخواه";
            $a++;

            $headerSearch11[$a]['type'] = "text";
            $headerSearch11[$a]['width'] = "150px";
            $headerSearch11[$a]['id'] = "fundListManageCodeSearch";
            $headerSearch11[$a]['title'] = "کد تنخواه";
            $headerSearch11[$a]['placeholder'] = "کد تنخواه";
            $a++;

            $headerSearch11[$a]['type'] = "text";
            $headerSearch11[$a]['width'] = "200px";
            $headerSearch11[$a]['id'] = "fundListManageAmountSearch";
            $headerSearch11[$a]['onkeyup'] = "onkeyup=addSeprator()";
            $headerSearch11[$a]['title'] = "مبلغ کل";
            $headerSearch11[$a]['placeholder'] = "مبلغ کل";
            $a++;
            //-------------------------------------------------------
            $headerSearch11[$a]['type'] = "select";
            $headerSearch11[$a]['width'] = "200px";
            $headerSearch11[$a]['id'] = "fundListManageLayerOneSearch";
            $headerSearch11[$a]['onchange'] = "onchange=get_layer_two_options(this)";
            $headerSearch11[$a]['options'][0]['value'] = "0";
            $headerSearch11[$a]['options'][0]['title'] = "انتخاب سرگروه";
            for ($i=0;$i<$countLayer1;$i++){
                $headerSearch11[$a]['options'][$i+1]["title"] = $layer1[$i]['layerName'];
                $headerSearch11[$a]['options'][$i+1]["value"] = $layer1[$i]['RowID'];
            }
            $a++;

            $headerSearch11[$a]['type'] = "select";
            $headerSearch11[$a]['width'] = "200px";
            $headerSearch11[$a]['id'] = "fundListManageLayerTwoSearch";
            $headerSearch11[$a]['onchange'] = "onchange=get_layer_three_options(this)()";
            $headerSearch11[$a]['options'][0]['value'] = "0";
            $headerSearch11[$a]['options'][0]['title'] = "انتخاب زیرگروه";
            for ($i=0;$i<$countLayer2;$i++){
                $headerSearch11[$a]['options'][$i+1]["title"] = $layer2[$i]['layerName'];
                $headerSearch11[$a]['options'][$i+1]["value"] = $layer2[$i]['RowID'];
            }
            $a++;
            $headerSearch11[$a]['type'] = "select";
            $headerSearch11[$a]['width'] = "200px";
            $headerSearch11[$a]['id'] = "fundListManageLayerThreeSearch";
            $headerSearch11[$a]['onchange'] = "onchange=changeLayerTwo()";
            $headerSearch11[$a]['options'][0]['value'] = "0";
            $headerSearch11[$a]['options'][0]['title'] = "انتخاب زیر گروه فرعی";
            for ($i=0;$i<$countLayer3;$i++){
                $headerSearch11[$a]['options'][$i+1]["title"] = $layer3[$i]['layerName'];
                $headerSearch11[$a]['options'][$i+1]["value"] = $layer3[$i]['RowID'];
            }
            $a++;
            //-------------------------------------------------------

            $headerSearch11[$a]['type'] = "btn";
            $headerSearch11[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch11[$a]['jsf'] = "showFundListManageList";

            $bottons[$y] = $bottons11;
            $headerSearch[$z] = $headerSearch11;

            $manifold++;
            $access[] = 11;
            $x++;
            $y++;
            $z++;
        }
        if (in_array('stagnantCommentManagement',$access_array)) {
            $pagename[$x] = "اظهار نظرهای راکد";
            $pageIcon[$x] = "fa-face-angry";
            $contentId[$x] = "stagnantCommentManageBody";
            $menuItems[$x] = 'stagnantCommentManageTabID';

            $bottons12 = array();
            $headerSearch12 = array();

            $bottons12[0]['title'] = "اظهارنظرهای راکد در کارتابل (اول)";
            $bottons12[0]['jsf'] = "showPayCommentStagnantManageList";
            $bottons12[0]['icon'] = "fa-search";
            $bottons12[0]['id'] = "id='payCommentStagnantManageList_btn'";
            $bottons12[0]['tab'] = "tab-btn";

            $bottons12[1]['title'] = "اظهارنظرهای راکد از زمان سررسید پرداخت (دوم)";
            $bottons12[1]['jsf'] = "showPayCommentStagnantManageList1";
            $bottons12[1]['icon'] = "fa-search";
            $bottons12[1]['id'] = "id='payCommentStagnantManageList1_btn'";
            $bottons12[1]['tab'] = "tab-btn";

            $a = 0;
            $headerSearch12[$a]['type'] = "text";
            $headerSearch12[$a]['width'] = "90px";
            $headerSearch12[$a]['id'] = "commentStagnantManageSDateSearch";
            $headerSearch12[$a]['title'] = "از تاریخ";
            $headerSearch12[$a]['placeholder'] = "از تاریخ";
            $a++;

            $headerSearch12[$a]['type'] = "text";
            $headerSearch12[$a]['width'] = "90px";
            $headerSearch12[$a]['id'] = "commentStagnantManageEDateSearch";
            $headerSearch12[$a]['title'] = "تا تاریخ";
            $headerSearch12[$a]['placeholder'] = "تا تاریخ";
            $a++;

            $headerSearch12[$a]['type'] = "select";
            $headerSearch12[$a]['id'] = "commentStagnantManageUnitSearch";
            $headerSearch12[$a]['title'] = "واحد درخواست کننده";
            $headerSearch12[$a]['width'] = "200px";
            $headerSearch12[$a]['options'] = array();
            $headerSearch12[$a]['options'][0]["title"] = "واحد درخواست کننده";
            $headerSearch12[$a]['options'][0]["value"] = 0;
            for ($i = 0; $i < $CountUnits; $i++) {
                $headerSearch12[$a]['options'][$i + 1]["title"] = $units[$i]['unitName'];
                $headerSearch12[$a]['options'][$i + 1]["value"] = $units[$i]['RowID'];
            }
            $a++;

            $headerSearch12[$a]['type'] = "select";
            $headerSearch12[$a]['id'] = "commentStagnantManageConsumerUnitSearch";
            $headerSearch12[$a]['title'] = "واحد مصرف کننده";
            $headerSearch12[$a]['width'] = "200px";
            $headerSearch12[$a]['options'] = array();
            $headerSearch12[$a]['options'][0]["title"] = "واحد مصرف کننده";
            $headerSearch12[$a]['options'][0]["value"] = 0;
            for ($i = 0; $i < $CountUnits; $i++) {
                $headerSearch12[$a]['options'][$i + 1]["title"] = $units[$i]['unitName'];
                $headerSearch12[$a]['options'][$i + 1]["value"] = $units[$i]['RowID'];
            }
            $a++;

            $headerSearch12[$a]['type'] = "select";
            $headerSearch12[$a]['id'] = "commentStagnantManageCardboardSearch";
            $headerSearch12[$a]['title'] = "در کارتابل (مربوط به جستجوی اول)";
            $headerSearch12[$a]['width'] = "200px";
            $headerSearch12[$a]['multiple'] = "multiple";
            $headerSearch12[$a]['LimitNumSelections'] = 1;
            $a++;

            $headerSearch12[$a]['type'] = "select";
            $headerSearch12[$a]['id'] = "commentStagnantManageCardboardSearch1";
            $headerSearch12[$a]['title'] = "در کارتابل (مربوط به جستجوی دوم)";
            $headerSearch12[$a]['width'] = "200px";
            $headerSearch12[$a]['multiple'] = "multiple";
            $headerSearch12[$a]['LimitNumSelections'] = 1;
            $headerSearch12[$a]['options'] = array();
/*            for ($i = 0; $i < $cntu; $i++) {
                $headerSearch12[$a]['options'][$i]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
                $headerSearch12[$a]['options'][$i]["value"] = $users[$i]['RowID'];
            }*/
            $a++;

            $headerSearch12[$a]['type'] = "text";
            $headerSearch12[$a]['width'] = "150px";
            $headerSearch12[$a]['id'] = "commentStagnantManageAccNameSearch";
            $headerSearch12[$a]['title'] = "نام طرف حساب";
            $headerSearch12[$a]['placeholder'] = "نام طرف حساب";
            $a++;

            $headerSearch12[$a]['type'] = "text";
            $headerSearch12[$a]['width'] = "150px";
            $headerSearch12[$a]['id'] = "commentStagnantManageTowardSearch";
            $headerSearch12[$a]['title'] = "بابت";
            $headerSearch12[$a]['placeholder'] = "بابت";
            $a++;

            $headerSearch12[$a]['type'] = "text";
            $headerSearch12[$a]['width'] = "150px";
            $headerSearch12[$a]['id'] = "commentStagnantManageUncodeSearch";
            $headerSearch12[$a]['title'] = "کد یکتا";
            $headerSearch12[$a]['placeholder'] = "کد یکتا";

            $a++;
            $headerSearch12[$a]['type'] = "btn";
            $headerSearch12[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
            $headerSearch12[$a]['jsf'] = "ShowStagnantManageTowardSearch";
          

            $bottons[$y] = $bottons12;
            $headerSearch[$z] = $headerSearch12;

            $manifold++;
            $access[] = 12;
        }

        $htm = $ut->getHtmlOfDefaultManagementMultiPage($pagename,$pageIcon,$contentId,$menuItems,$bottons,$manifold,$headerSearch,$hiddenContentId);
        //++++++++++++++++++++++++++++++++++EDIT CREATE active project MODAL++++++++++++++++++++++++++++++++
        $modalID = "activeProjectManagmentModal";
        $modalTitle = "فرم ثبت/ویرایش  پروژه";
        //$txt = '<p style="margin-bottom: -10px;">نکته : در صورت عدم وجود شماره حساب</p><br/><p style="font-size: 16px;margin-bottom: -10px;">شماره حساب (0) و نام بانک و صاحب حساب (ندارد) قرار داده شود !!!!</p><br/><p style="font-size: 16px;">در قسمت شماره حساب نباید از IR ، ir ، - , _ , * , . استفاده شود !!!!</p>';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "activeProjectManagmentName";
        $items[$c]['title'] = "نام پروژه";
        $items[$c]['placeholder'] = "نام پروژه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "activeProjectManagmentCode";
        $items[$c]['title'] = "کد تفضیلی پروژه";
        $items[$c]['placeholder'] = "کد";
        $c++;

        // $items[$c]['type'] = "text";
        // $items[$c]['id'] = "accountManagmentCodeMelli";
        // $items[$c]['title'] = "کد ملی";
        // $items[$c]['placeholder'] = "کد";
        // $c++;

        // $items[$c]['type'] = "checkbox";
        // $items[$c]['id'] = "accountManagmentBankType";
        // $items[$c]['title'] = "پاسارگاد";
        // $c++;

        // $items[$c]['type'] = "inputGroup";
        // $items[$c]['id'] = "accountManagmentAccountNumber";
        // $items[$c]['icon'] = "fa-plus fa-lg";
        // $items[$c]['onclick'] = "onclick='addAccountNumber()'";
        // $items[$c]['title'] = "شماره حساب";
        // $items[$c]['placeholder'] = "شماره";
        // $c++;

        // $items[$c]['type'] = "inputGroup";
        // $items[$c]['id'] = "accountManagmentBank";
        // $items[$c]['icon'] = "fa-plus fa-lg";
        // $items[$c]['onclick'] = "onclick='addBankName()'";
        // $items[$c]['title'] = "نام بانک و صاحب حساب";
        // $items[$c]['placeholder'] = "نام";
		// $items[$c]['SpanStyle'] = "style='cursor: pointer;height: 35px;display: none;'";
        // $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "activeProjectDesc";
        //$items[$c]['style'] = "style='resize: none;'";
        $items[$c]['title'] = "  توضیحات  پروژه";
        $items[$c]['placeholder'] =  "توضیحات  پروژه";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageActiveProjectHiddenAid";
		$c++;
		
		$items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageActiveProjectHiddenUid";
        if($acm->hasAccess('editProjects')){
            $items[$c]['value'] = "value='1'";

        }else{
            $items[$c]['value'] = "value='0'";
        }
        $c++;

        // $items[$c]['type'] = "hidden";
        // $items[$c]['id'] = "manageAccountHiddenTempAccNum";
		// $c++;

        // $items[$c]['type'] = "hidden";
        // $items[$c]['id'] = "manageAccountHiddenPage";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateActiveProject";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateActiveProjectModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$txt);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE Account MODAL +++++++++++++++++++++++++++++++++++++

        //++++++++++++++++++++++++++++++++++EDIT CREATE Account MODAL++++++++++++++++++++++++++++++++
        $modalID = "accountManagmentModal";
        $modalTitle = "فرم ثبت/ویرایش طرف حساب";
        $style = 'style="max-width: 810px;"';
        $txt = '<p style="margin-bottom: -10px;">نکته : در صورت عدم وجود شماره حساب</p><br/><p style="font-size: 16px;margin-bottom: -10px;">شماره حساب (0) و نام بانک و صاحب حساب (ندارد) قرار داده شود !!!!</p><br/><p style="font-size: 16px;">در قسمت شماره حساب نباید از IR ، ir ، - , _ , * , . استفاده شود !!!!</p>';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "accountManagmentName";
        $items[$c]['title'] = "نام";
        $items[$c]['placeholder'] = "نام";
        $c++;
       
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "accountManagmentCode";
        $items[$c]['title'] = "کد تفضیلی";
        $items[$c]['placeholder'] = "کد";
        $c++;
        //-------------------------------------
        $items[$c]['type'] = "checkbox_group";
        $items[$c]['id'] = "accountroles";
        $items[$c]['title'] = " نقش طرف حساب";
        $items[$c]['label'][0]['title'] = " مشتریان";
        $items[$c]['label'][0]['name'] = "accountroles_check";
        $items[$c]['label'][1]['name'] = "accountroles_check";
        $items[$c]['label'][2]['name'] = "accountroles_check";
        $items[$c]['label'][0]['value'] = 1;
        $items[$c]['label'][1]['value'] = 2;
        $items[$c]['label'][2]['value'] = 3;
        $items[$c]['label'][1]['title'] = " تامین کنندگان";
        $items[$c]['label'][2]['title'] = "سایر";
        
        $c++;
        //-----------------------------------

       

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "accountManagmentCodeMelli";
        $items[$c]['title'] = "کد ملی";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "checkbox";
        $items[$c]['id'] = "accountManagmentBankType";
        $items[$c]['title'] = "پاسارگاد";
        $c++;

        $items[$c]['type'] = "inputGroup";
        $items[$c]['id'] = "accountManagmentAccountNumber";
        $items[$c]['icon'] = "fa-plus fa-lg";
        $items[$c]['onclick'] = "onclick='addAccountNumber()'";
        $items[$c]['title'] = "شماره حساب";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "inputGroup";
        $items[$c]['id'] = "accountManagmentBank";
        $items[$c]['icon'] = "fa-plus fa-lg";
        $items[$c]['onclick'] = "onclick='addBankName()'";
        $items[$c]['title'] = "نام بانک و صاحب حساب";
        $items[$c]['placeholder'] = "نام";
		$items[$c]['SpanStyle'] = "style='cursor: pointer;height: 35px;display: none;'";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "accountManagmentAccountNumbers";
        $items[$c]['style'] = "style='resize: none;'";
        $items[$c]['title'] = "شماره حساب ها";
        $items[$c]['placeholder'] = "شماره حساب/حساب ها";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageAccountHiddenAid";
		$c++;
		
		$items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageAccountHiddenUid";
        if($acm->hasAccess('editAccount')){
            $items[$c]['value'] = "value='1'";

        }else{
            $items[$c]['value'] = "value='0'";
        }
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageAccountHiddenTempAccNum";
		$c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageAccountHiddenPage";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateAccount";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateAccountModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$txt,'',$style);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE Account MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ EDIT CREATE Comment MODAL ++++++++++++++++++++++++++++++++
        $modalID = "commentManagmentModal";
        $modalTitle = "فرم ثبت/ویرایش اظهارنظر";
        $style = 'style="max-width: 90vw;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "commentManagmentPayType";
        $items[$c]['title'] = "روش پرداخت";
        $items[$c]['options'][0]['title'] = "سهامی";
        $items[$c]['options'][0]['value'] = 0;
        $items[$c]['options'][1]['title'] = "فورج";
        $items[$c]['options'][1]['value'] = 1;
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "commentManagmentCashCheck";
        $items[$c]['title'] = "نوع پرداخت";
        $items[$c]['options'][0]['title'] = "نقدی";
        $items[$c]['options'][0]['value'] = 0;
        $items[$c]['options'][1]['title'] = "چک";
        $items[$c]['options'][1]['value'] = 1;
        $c++;

        //********************************************************* */ اظهارنظر های مربوط به پروژه
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "is_related_project";
        $items[$c]['title'] = "اظهارنظر مربوط به پروژه می باشد ";
        $items[$c]['options'][0]['title'] = "بله";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "خیر";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "comment_project";
        $items[$c]['onchange'] = "";
        $items[$c]['title'] = "انتخاب پروژه";
        $items[$c]['options'][0]['value']='0';
        $items[$c]['options'][0]['title']='-------------';
        for($i=0;$i<count($projects_detailes);$i++){
            $items[$c]['options'][$i+1]['value']=$projects_detailes[$i]['project_code'];
            $items[$c]['options'][$i+1]['title']=$projects_detailes[$i]['project_name'];
        }
        $c++;
        //********************************************************* */  و کرایه حمل رانندگان اظهارنظر های مربوط به بارنامه
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "drivers_fare";
        $items[$c]['title'] = "اظهارنظر مربوط به کرایه حمل می باشد ";
        $items[$c]['options'][0]['title'] = "بله";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "خیر";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "way_bill_type";
        $items[$c]['onchange'] = "";
        $items[$c]['title'] = "انتخاب نوع بارنامه";
        $items[$c]['options'][0]['value']='0';
        $items[$c]['options'][0]['title']='-------------';
        $items[$c]['options'][1]['title']="بارنامه صادره";
        $items[$c]['options'][1]['value']="1";
        $items[$c]['options'][2]['title']="بارنامه وارده";
        $items[$c]['options'][2]['value']="2";
        $c++;
        //********************************************************* */
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "is_related_VAT";
        $items[$c]['title'] = " پرداخت  مالیات بر ارزش افزوده";
        $items[$c]['options'][0]['title'] = "بله";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "خیر";
        $items[$c]['options'][1]['value'] = 0;
        $c++;
        //********************************************************* */
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "commentManagmentOneLayer";
        $items[$c]['title'] = "انتخاب سرگروه";
        $items[$c]['options'] = array();
        $items[$c]['onchange'] = "onchange=getSubLayerTwo()";
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$CountLayers;$i++){
            $items[$c]['options'][$i+1]["title"] = $layers[$i]['layerName'];
            $items[$c]['options'][$i+1]["value"] = $layers[$i]['RowID'];
        }
        $c++;


        $items[$c]['type'] = "select";
        $items[$c]['id'] = "commentManagmentTwoLayer";
        $items[$c]['onchange'] = "onchange=showHideClearingFund()";
        $items[$c]['title'] = "انتخاب زیرگروه";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "commentManagmentThreeLayer";
        $items[$c]['onchange'] = "onchange=show_property_number(this)";
        $items[$c]['title'] = "انتخاب زیرگروه فرعی";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "PropertyNumber";
       // $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "شماره اموال";
        $items[$c]['placeholder'] = "شماره اموال";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentClearingFund";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ تسویه تنخواه (قابل توجه واحد مالی)";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentClearingGoodLoan";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ تسویه وجه قرض الحسنه";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "commentManagmentUnit";
        $items[$c]['title'] = "واحد درخواست کننده اظهارنظر";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "commentManagmentConsumerUnit";
        $items[$c]['title'] = "واحد مصرف کننده مرتبط";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "commentManagmentType";
        $items[$c]['title'] = "نوع";
        $items[$c]['onchange'] = "onchange=getPayingBillInfo()";
        $c++;

        //******************************************************** */
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "commentCheckoutType";
        $items[$c]['title'] = "نوع تسویه حساب  اظهار نظر";
        $items[$c]['options'][0]['title'] = "--------";
        $items[$c]['options'][0]['value'] = 0;
        $items[$c]['options'][1]['title'] = "تسویه حساب هزینه ای";
        $items[$c]['options'][1]['value'] = 1;
        $items[$c]['options'][2]['title'] = "تسویه حساب انباری";
        $items[$c]['options'][2]['value'] = 2;
        $items[$c]['options'][3]['title'] = "سایر";
        $items[$c]['options'][3]['value'] = 3;
        $c++;
        //******************************************************** */

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentCheckNumber";
        $items[$c]['title'] = "شماره چک";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentCheckDate";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ چک";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "commentManagmentCheckCarcass";
        $items[$c]['title'] = "لاشه چک تحویل واحد مالی";
        $items[$c]['options'][0]['title'] = "داده شده";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "داده نشده";
        $items[$c]['options'][1]['value'] = 2;
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "commentManagmentCheckCarcassFile";
        $items[$c]['title'] = "بارگذاری رسید تحویل چک";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF, JPG , PNG باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['id'] = "commentManagmentDeliveryDate";
        $items[$c]['title'] = "تعهد تاریخ تحویل لاشه چک به واحد مالی";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "commentManagmentContractYN";
        $items[$c]['title'] = "قرارداد";
        $items[$c]['onchange'] = "onchange=removeContractInfo()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "---------";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "دارد";
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = "ندارد";
        $items[$c]['options'][2]["value"] = 0;
        $c++;

        $items[$c]['type'] = "inputGroup";
        $items[$c]['id'] = "commentManagmentContractNum";
        $items[$c]['icon'] = "fa-plus fa-lg";
        $items[$c]['onclick'] = "onclick='showContractChooseList()'";
        $items[$c]['title'] = "شماره قرارداد";
        $items[$c]['placeholder'] = "شماره";
        $items[$c]['SpanStyle'] = "style='cursor: pointer;height: 35px;'";
         $c++;
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "commentManagmentContractID";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "commentManagmentToward";
        $items[$c]['title'] = "بابت";
        $items[$c]['placeholder'] = "بابت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentTotalAmount";
        $items[$c]['title'] = "مبلغ مربوط به کل معامله";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentAmount";
        $items[$c]['title'] = "مبلغ قابل پرداخت در این اظهارنظر";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['onfocus'] = "onfocus=show_contract_pay_rows(this)";
        $items[$c]['onblur'] = "onblur=check_pay_comment_amount(this)";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentCashSection";
        $items[$c]['title'] = "بخش نقدی";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentPaymentMaturityCash";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "سررسید پرداخت نقدی";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentNonCashSection";
        $items[$c]['title'] = "بخش چک";
        $items[$c]['placeholder'] = "ریال";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentPaymentMaturityCheck";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "سررسید پرداخت چک";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentFor";
        $items[$c]['onchange'] = "onchange=getAccountNumber()";
        $items[$c]['title'] = "نام طرف حساب";
        $items[$c]['placeholder'] = "تایپ کنید";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentCode";
        $items[$c]['title'] = "کد تفضیلی";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "commentManagmentAccNum";
        $items[$c]['title'] = "شماره حساب";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentCardNumber";
        $items[$c]['title'] = "شماره کارت و نام صاحب کارت";
        $items[$c]['placeholder'] = "شماره و نام صاحب کارت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentBillingID";
        $items[$c]['title'] = "شناسه قبض";
        $items[$c]['placeholder'] = "شناسه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentPaymentID";
        $items[$c]['title'] = "شناسه پرداخت";
        $items[$c]['placeholder'] = "شناسه";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "commentManagmentRequestSource";
        $items[$c]['title'] = "منبع درخواست";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "اعلام شفاهی مدیریت محترم عامل";
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = "اعلام شفاهی معاونت محترم بازرگانی";
        $items[$c]['options'][2]["value"] = 2;
        $items[$c]['options'][3]["title"] = "اعلام شفاهی قائم مقام محترم";
        $items[$c]['options'][3]["value"] = 3;
        $items[$c]['options'][4]["title"] = "قرارداد";
        $items[$c]['options'][4]["value"] = 4;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "commentManagmentRequestNumbers";
        $items[$c]['title'] = "شماره/شماره های درخواست";
        $items[$c]['style'] = "style='resize: none;'";
        $items[$c]['placeholder'] = "شماره/ شماره ها";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "commentManagmentDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageCommentHiddenCid";
		$c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageCommentHiddenBillPay";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "max_increase";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "percent_row";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "amount_row";
        $c++;

        $footerBottons = array();
        $footerBottons[0]['title'] = "ذخیره";
        $footerBottons[0]['jsf'] = "doEditCreateComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateCommentModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,'','','','',2);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE Comment MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ SEND Comment MODAL ++++++++++++++++++++++++++++++++
        $modalID = "sendCommentManagmentModal";
        $modalTitle = "فرم ارجاع اظهارنظر";
        $style = 'style="max-width: 1000px;"';
        $ShowDescription = 'commentWorkflowSend-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "commentManagmentConfirmedType";
        $items[$c]['title'] = "انتخاب نمایید";
        $items[$c]['options'][0]['title'] = "تایید";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "عدم تایید";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "commentManagmentReceiver";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['LimitNumSelections'] = 1;
        $items[$c]['title'] = "گیرنده";
        //$items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cntu;$i++){
            $items[$c]['options'][$i+1]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
            $items[$c]['options'][$i+1]["value"] = $users[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "commentManagmentConfirmedDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "commentManagmentPriorityLevel";
        $items[$c]['title'] = "سطح اولویت";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "عادی";
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = "در اسرع وقت";
        $items[$c]['options'][1]["value"] = 1;
        $c++;

        $items[$c]['type'] = "checkbox";
        $items[$c]['id'] = "commentManagmentIsPaid";
        $items[$c]['title'] = "قبلا پرداخت شده است";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "commentManagmentHiddenCid";

        $topperBottons = array();
        $topperBottons[0]['title'] = "ارسال";
        $topperBottons[0]['jsf'] = "doSendPayComment";
        $topperBottons[0]['type'] = "btn";
        $topperBottons[0]['data-dismiss'] = "NO";
        $topperBottons[1]['title'] = "ذخیره";
        $topperBottons[1]['jsf'] = "saveSendPayComment";
        $topperBottons[1]['type'] = "btn-success";
        $topperBottons[1]['data-dismiss'] = "NO";
        $topperBottons[2]['title'] = "انصراف";
        $topperBottons[2]['type'] = "dismis";
        $sendCommentModal = $ut->getHtmlModal($modalID,$modalTitle,$items,array(),'','',$style,$ShowDescription,'','',$topperBottons);
        //++++++++++++++++++++++++++++++ END OF SEND Comment MODAL +++++++++++++++++++++++++++++++++++++

            //++++++++++++++++++++++++++++++++++ SEND Automatic Comment MODAL ++++++++++++++++++++++++++++++++
            $modalID = "manageAutomaticSendCommentModal";
            $items=[];
            $modalTitle = " مدیریت ارجاع خودکار اظهارنظر ";
            $style = 'style="max-width: 900px;"';
            $ShowDescription = 'manageAutomaticSendWorkflowSend-body';
    
            $c = 0;
            $items[$c]['type'] = "text";
           // $items[$c]['id'] = "commentManagmentPaymentMaturityCheck";
            $items[$c]['id'] = "automaticSendCommentManagmentDate";
            $items[$c]['style'] = "style='width: 70%;float: right;'";
            $items[$c]['title'] = "تاریخ شروع ارجاع خودکار";
            $items[$c]['placeholder'] = "تاریخ";
            $items[$c]['style'] = "style='width:220px;'";
            $c++;
          
            $items[$c]['title'] = "مدت زمان ارجاع خودکار به روز";
            $items[$c]['type'] = "text";
            $items[$c]['placeholder'] = "مدت زمان ارجاع خودکار به روز";
            $items[$c]['id'] = "َautomaticSendCommentManagmentDays";
            $items[$c]['style'] = "style='width:220px;'";
            
            $c++;
            
            $items[$c]['type'] = "select";
            $items[$c]['id'] = "َautomaticSendCommentManagmentReceiver";
            $items[$c]['multiple'] = "multiple";
            $items[$c]['LimitNumSelections'] = 1;
            $items[$c]['title'] = "کاربر غایب";
            $items[$c]['options'][0]["title"] = "--------";
            $items[$c]['options'][0]["value"] = 0;
            for ($i=0;$i<$cntu;$i++){
                $items[$c]['options'][$i+1]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
                $items[$c]['options'][$i+1]["value"] = $users[$i]['RowID'];
            }
            $c++;
            $items[$c]['title'] = "کاربر جانشین ";
            $items[$c]['id'] = "َautomaticSendCommentManagmentsubstitute";
            $items[$c]['type'] = "select";
            $items[$c]['multiple'] = "multiple";
            $items[$c]['LimitNumSelections'] = 1;
            $items[$c]['options'][0]["title"] = "--------";
            $items[$c]['options'][0]["value"] = 0;
            for ($i=0;$i<$cntu;$i++){
                $items[$c]['options'][$i+1]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
                $items[$c]['options'][$i+1]["value"] = $users[$i]['RowID'];
            }
            $c++;
            $items[$c]['title'] = " توضیحات ";
            $items[$c]['id'] = "َautomaticSendCommentManagmentDesc";
            $items[$c]['type'] = "textarea";
            $items[$c]['style'] = "style='width:220px;'";
            $c++;
            $items[$c]['id'] = "َautoSendRowID";
            $items[$c]['type'] = "hidden";

           //$items[$c]['options'] = array();
            $topperBottons = array();
            $topperBottons[0]['title'] = "ارسال";
            $topperBottons[0]['jsf'] = "doAutomaticSendPayComment";
            $topperBottons[0]['type'] = "btn";
            $topperBottons[0]['data-dismiss'] = "NO";
        
            $topperBottons[1]['title'] = "انصراف";
            $topperBottons[1]['type'] = "dismis";
            $sendAutomaticCommentModal = $ut->getHtmlModal($modalID,$modalTitle,$items,array(),'','',$style,$ShowDescription,'','',$topperBottons);
          //  ////$ut->fileRecorder($items);
            //++++++++++++++++++++++++++++++ END OF Automatic SEND Comment MODAL +++++++++++++++++++++++++++++++++++++
                //++++++++++++++++++++++++++++++++++ manage partners cartable MODAL ++++++++++++++++++++++++++++++++
              
				
				
			   $modalID = "managePartnersCartableModal";
                $items=[];
                $modalTitle = " مدیریت کارتابل همکاران  ";
                $style = 'style="max-width:90vw;"';
                $ShowDescription = 'managePartnersCartable-body';
        
                $c = 0;
                /*$items[$c]['type'] = "text";
               // $items[$c]['id'] = "commentManagmentPaymentMaturityCheck";
                $items[$c]['id'] = "managePartnersCartableDate";
                $items[$c]['style'] = "style='width: 70%;float: right;'";
                $items[$c]['title'] = "تاریخ   ";
                $items[$c]['placeholder'] = "تاریخ";
                $items[$c]['style'] = "style='width:220px;'";
                $c++;/*
              /*
                $items[$c]['title'] = "مدت زمان ارجاع خودکار به روز";
                $items[$c]['type'] = "text";
                $items[$c]['placeholder'] = "مدت زمان ارجاع خودکار به روز";
                $items[$c]['id'] = "َautomaticSendCommentManagmentDays";
                $items[$c]['style'] = "style='width:220px;'";
                
                $c++;
                */
                $items[$c]['type'] = "select";
                $items[$c]['id'] = "managePartnersCartableSender";
               
                //$items[$c]['onchange'] = "َonChange='showPartnersCartableDatailes(this.value)'";
                $items[$c]['multiple'] = "multiple";
                $items[$c]['LimitNumSelections'] = 1;
                $items[$c]['title'] = "کارتابل کاربر جاری";
                $items[$c]['options'][0]["title"] = "--------";
                $items[$c]['options'][0]["value"] = 0;
				$subUser=[];
				if(!in_array($_SESSION['userid'],$full_access_user_see_partners_cartable_array))
				{
					foreach($users as $k=>$v){
						if($v['unitID']==$userUnit){
							$subUser[]=$v;
						}
					}
					for ($i=0;$i<count($subUser);$i++){
							$items[$c]['options'][$i+1]["title"] = $subUser[$i]['fname'].' '.$subUser[$i]['lname'];
							$items[$c]['options'][$i+1]["value"] = $subUser[$i]['RowID'];
			
					}
				}
				else
				{
					for ($i=0;$i<$cntu;$i++){
							$items[$c]['options'][$i+1]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
							$items[$c]['options'][$i+1]["value"] = $users[$i]['RowID'];
			
					}
					
				}
					
					
                $c++;
                $items[$c]['title'] = "ارجاع به کاربر";
                $items[$c]['id'] = "َmanagePartnersCartableSubstitute";
                $items[$c]['type'] = "select";
                $items[$c]['multiple'] = "multiple";
                $items[$c]['LimitNumSelections'] = 1;
                $items[$c]['options'][0]["title"] = "--------";
                $items[$c]['options'][0]["value"] = 0;
                for ($i=0;$i<$cntu;$i++){
                    $items[$c]['options'][$i+1]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
                    $items[$c]['options'][$i+1]["value"] = $users[$i]['RowID'];
                }
                /*$c++;
                $items[$c]['title'] = " توضیحات ";
                $items[$c]['id'] = "َmanagePartnersCartableDesc";
                $items[$c]['type'] = "textarea";
                $items[$c]['style'] = "style='width:220px;'";*/
                
    
               //$items[$c]['options'] = array();
                $topperBottons = array();
                $topperBottons[0]['title'] = "ارسال";
                $topperBottons[0]['jsf'] = "transferPartnersCartable";
                $topperBottons[0]['type'] = "btn";
                $topperBottons[0]['data-dismiss'] = "NO";
            
                $topperBottons[1]['title'] = "انصراف";
                $topperBottons[1]['type'] = "dismis";
                $managePartnersCartableModal = $ut->getHtmlModal($modalID,$modalTitle,$items,array(),'','',$style,$ShowDescription,'','',$topperBottons);
               // $managePartnersCartableModal.="<script> $('#managePartnersCartableReceiver').parent().css('border','3px solid red')
                //;</script>";
                //////$ut->fileRecorder($items);
			   
                //++++++++++++++++++++++++++++++ END OF manage partners cartable MODAL +++++++++++++++++++++++++++++++++++++
            

        //++++++++++++++++++++++++++++++++++ SEND Temp Comment MODAL ++++++++++++++++++++++++++++++++
        $modalID = "sendTempCommentManagmentModal";
        $modalTitle = "فرم ویرایش ارجاع اظهارنظر";
        $style = 'style="max-width: 700px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "commentManagmentTempConfirmedType";
        $items[$c]['title'] = "انتخاب نمایید";
        $items[$c]['options'][0]['title'] = "تایید";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "عدم تایید";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "commentManagmentTempReceiver";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['LimitNumSelections'] = 1;
        $items[$c]['title'] = "گیرنده";
/*        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cntu;$i++){
            $items[$c]['options'][$i+1]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
            $items[$c]['options'][$i+1]["value"] = $users[$i]['RowID'];
        }*/
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "commentManagmentTempConfirmedDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "commentManagmentTempHiddenPWID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditTempSendComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $sendTempCommentModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF SEND Temp Comment MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ DELETE MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "deleteTempCommentManagmentModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به حذف مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "deleteTempComment_deleteIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doDeleteTempSendComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $doDeleteTempSendComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF DELETE MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Send MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "sendTempCommentManagmentSendModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به ارسال مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "sendTempComment_sendIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doSendTempSendComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $doSendTempSendComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++ END OF Send MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++ Start Comment Info Modal ++++++++++++++++++++++
        $modalID = "commentManageInfoModal";
        $modalTitle = "سایر اطلاعات";
        $ShowDescription = 'comment-manage-Info-body';
        $style = 'style="max-width: 800px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showCommentInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Comment Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Account Info Modal ++++++++++++++++++++++
        $modalID = "accountManageInfoModal";
        $modalTitle = "اطلاعات حساب";
        $ShowDescription = 'account-manage-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showAccountInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Account Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Add Check To Comment MODAL ++++++++++++++++++
        $modalID = "addCheckToCommentManagmentModal";
        $modalTitle = "فرم افزودن چک به اظهارنظر";
        $style = 'style="max-width: 651px;"';
        $ShowDescription = 'payCommentCheck-body';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "addCheckToCommentManageCType";
        $items[$c]['title'] = "نوع چک";
        $items[$c]['onchange'] = "onchange='getCheckType()'";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "ابرش";
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = "مشتری";
        $items[$c]['options'][1]["value"] = 1;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "addCheckToCommentManageCDate";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ چک";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "addCheckToCommentManageCAmount";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['title'] = "مبلغ چک";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "addCheckToCommentManageCNum";
        $items[$c]['title'] = "شماره چک";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "addCheckToCommentManageDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "توضیحات";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "addCheckToCommentManageHiddenCid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "checkLeftOverCheki";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $addCheckToCommentModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++ END OF Add Check To Comment MODAL ++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ EDIT CREATE Unit MODAL ++++++++++++++++++++++++++++++++
        $modalID = "unitManagmentModal";
        $modalTitle = "فرم ثبت/ویرایش واحدهای مربوطه";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "unitManagmentUName";
        $items[$c]['style'] = "style='width: 220px;'";
        $items[$c]['title'] = "نام واحد";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "unitManagmentUsers";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "کاربران مجاز";
        $items[$c]['options'] = array();
        for ($i=0;$i<$cntu;$i++){
            $items[$c]['options'][$i]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
            $items[$c]['options'][$i]["value"] = $users[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "unitManagmentUDesc";
        $items[$c]['style'] = "style='width: 220px;'";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageUnitHiddenUid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateCommentUnit";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateUnitModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE Unit MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++EDIT CREATE Depositor MODAL++++++++++++++++++++++++++++++++
        $modalID = "depositorManagmentModal";
        $modalTitle = "فرم ثبت/ویرایش واریزکننده";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "depositorManagmentName";
        $items[$c]['title'] = "نام";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "depositorManagmentCode";
        $items[$c]['title'] = "کد تفضیلی";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageDepositorHiddenDid";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageDepositorHiddenPage";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateDepositor";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateDepositorModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE Depositor MODAL +++++++++++++++++++++++++++++++++++++
        //+++++++++++++++++ CREATE Transfer Modal +++++++++++++++++
        $modalID = "transferPayCommentModal";
        $modalTitle = "انتقال به کشو پرداخت";
        $ShowDescription = 'transferPayComment-body';
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "transferPayComment_IdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doTransferPayComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $transferPayComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End CREATE Transfer Modal +++++++++++++++++
        //++++++++++++++++++++++++++++++++++ EDIT CREATE Depositor MODAL ++++++++++++++++++++++++++++++++
        $modalID = "depositManagmentModal";
        $modalTitle = "فرم ثبت واریزی";
        $style = 'style="max-width: 1000px;"';
        $ShowDescription = 'depositManagment-Info-body';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "depositManagmentDdate";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ واریز";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "depositManagmentDepositor";
        $items[$c]['title'] = "واریز کننده";
        $items[$c]['onchange'] = "onchange='getDepositorCodeTafzili()'";
        $items[$c]['placeholder'] = "تایپ کنید";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "depositManagmentDCode";
        $items[$c]['title'] = "کد تفضیلی";
        $items[$c]['onchange'] = "onchange='getDepositorNameWithCode()'";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "depositManagmentDBank";
        $items[$c]['title'] = "نام بانک واریز کننده";
        $items[$c]['placeholder'] = "تایپ کنید";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "depositManagmentDAmount";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['title'] = "مبلغ";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "depositManagmentDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "depositManagmentPaymentReceipt";
        $items[$c]['title'] = "بارگذاری رسید";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF , JPG , PNG , ZIP , RAR باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageDepositHiddenDid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "checkLeftOverCash";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "خروجی اکسل";
        $footerBottons[1]['jsf'] = "createDepositExcel";
        $footerBottons[1]['type'] = "btn-success";
        $footerBottons[1]['data-dismiss'] = "NO";
        $footerBottons[2]['title'] = "انصراف";
        $footerBottons[2]['type'] = "dismis";
        $createDepositRegistration = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE Depositor MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++ Start Pay Comment Info Modal ++++++++++++++++++++++
        $modalID = "payCommentManageInfoModal";
        $modalTitle = "سایر اطلاعات";
        $ShowDescription = 'payComment-manage-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showPayCommentInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Pay Comment Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Final approval MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "payCommentFinalApprovalModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "payCommentFinalApprovalIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doFinalApprovalComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $finalApprovalModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF Final approval MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ check Mabalegh Varizi MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "checkMabaleghVariziModal";
        $modalTitle = "هشدار";
        $modalTxt = "اظهارنظر به طور کامل پرداخت نشده است، آیا مطئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "payCommentFinalApprovalIdHidden1";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doFinalApprovalComment1";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $checkMabaleghVarizi = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END check Mabalegh Varizi MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Final approval MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "checkLeftOverCashModal";
        $modalTitle = "هشدار";
        $modalTxt = "جمع مبالغ پرداختی از مبلغ نقدی بیشتر است، آیا مطمئن هستید ثبت نمایید؟";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "checkLeftOverCashIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCreateDepositRegistration";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $checkLeftOverCash = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF Final approval MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Final approval MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "checkLeftOverChekiModal";
        $modalTitle = "هشدار";
        $modalTxt = "جمع مبلغ چک های صادر شده از مبلغ چک بیشتر است، آیا مطمئن هستید ثبت نمایید؟";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "checkLeftOverChekiIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doAddCheckToComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $checkLeftOverCheck = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF Final approval MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Final approval MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "payCommentFinancialApprovalModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "payCommentFinancialApprovalIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doFinancialApprovalComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $financialApprovalModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF Final approval MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ Final approval MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "payCommentTempFinancialApprovalModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "payCommentTempFinancialApprovalIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doTempFinancialApprovalComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $tempFinancialApprovalModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF Final approval MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++ Deposits List Info Modal ++++++++++++++++++++++
        $modalID = "depositsInFinancialListModal";
        $modalTitle = "واریزی های انجام شده";
        $style = 'style="max-width: 900px;"';
        $ShowDescription = 'deposits-InFinancial-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showDepositsInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Deposits List Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Deposits List Info Modal ++++++++++++++++++++++
        $modalID = "depositsInTempFinancialListModal";
        $modalTitle = "واریزی های انجام شده";
        $style = 'style="max-width: 1100px;"';
        $ShowDescription = 'deposits-InTempFinancial-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showTempDepositsInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Deposits List Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ payment Receipt Download Modal ++++++++++++++++++++++
        $modalID = "paymentReceiptDownloadModal";
        $modalTitle = "دانلود رسید پرداخت";
        $ShowDescription = 'paymentReceiptDownload-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $paymentReceiptDownload = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End payment Receipt Download Modal ++++++++++++++++++++++++
        //++++++++++++++++++ comment Attachment File Modal ++++++++++++++++++++++
        $modalID = "commentAttachmentFileModal";
        $modalTitle = "دانلود فایل های پیوست و رسیدهای پرداخت";
        $ShowDescription = 'commentAttachmentFile-body';
        $style = 'style="max-width: 655px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $commentAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End comment Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ comment Add Attachment File Modal ++++++++++++++++++++++
        $modalID = "commentAddAttachmentFileModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'commentAddAttachmentFile-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentAddAttachmentName";
        $items[$c]['title'] = "نام فایل";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "commentManagmentAddAttachment";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PNG , JPG , JPEG , JFIF , PDF , XLSX , DOCX , ZIP , RAR , WAV باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "commentManagmentAddAttachmentID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachFileToComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $commentAddAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End comment Add Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ comment Workflow Modal ++++++++++++++++++++++
        $modalID = "commentWorkflowModal";
        $modalTitle = "گردش کار اظهارنظر";
        $style = 'style="max-width: 1000px;"';
        $ShowDescription = 'commentWorkflow-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $commentWorkflow = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End comment Workflow Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Comment Checks Modal ++++++++++++++++++++++
        $modalID = "commentCheckInFinancialModal";
        $modalTitle = "چک/چک ها";
        $ShowDescription = 'comment-InFinancial-Checks-body';
        $style = 'style="max-width: 700px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showCommentChecks = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Comment Checks Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Pay Comment Info Modal ++++++++++++++++++++++
        $modalID = "payCommentInFinancialInfoModal";
        $modalTitle = "سایر اطلاعات";
        $ShowDescription = 'payComment-InFinancial-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showPayCommentInFinancialInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Pay Comment Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ download CheckCarcass File Modal ++++++++++++++++++++++
        $modalID = "downloadCheckCarcassFileFinancialModal";
        $modalTitle = "دانلود رسید لاشه چک";
        $ShowDescription = 'downloadCheckCarcassFileFinancial-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $downloadCheckCarcassFinancialFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End download CheckCarcass File Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ SEND DEPOSIT TO MALI MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "sendDepositToMaliModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "sendDepositToMaliIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doSendDepositToMali";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $sendDepositToMaliModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF SEND DEPOSIT TO MALI MODAL++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ EDIT CREATE Contract MODAL ++++++++++++++++++++++++++++++++
        $modalID = "contractManagmentModal";
        $modalTitle = "فرم ثبت/ویرایش قرارداد";
        $style = 'style="max-width: 80vw;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "contractManagmentCType";
        $items[$c]['title'] = "نوع قرارداد";
        $items[$c]['options'][0]['title'] = "عادی";
        $items[$c]['options'][0]['value'] = 0;
        $items[$c]['options'][1]['title'] = "ساعتی";
        $items[$c]['options'][1]['value'] = 1;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractManagmentNumber";
        $items[$c]['title'] = "شماره قرارداد";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractManagmentSubject";
        $items[$c]['title'] = "موضوع قرارداد";
        $items[$c]['placeholder'] = "موضوع";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractManagmentAccount";
        $items[$c]['onchange'] = "onchange='getContractAccountNumber()'";
        $items[$c]['title'] = "طرف مقابل";
        $items[$c]['placeholder'] = "نام و نام خانوادگی یا نام شرکت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractManagmentCode";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['title'] = "کد تفضیلی";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "contractManagmentAccNum";
        $items[$c]['title'] = "شماره حساب";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractManagmentHourAmount";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['title'] = "مبلغ هر ساعت حضور";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractManagmentMaxHour";
        $items[$c]['title'] = "ماکسیمم ساعات حضور در ماه";
        $items[$c]['placeholder'] = "تعداد ساعت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractManagmentAmount";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['onchange'] = "onchange=set_input_hidden_value(this)";
        $items[$c]['title'] = "مبلغ کل قرارداد";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractManagmentCredit";
        $items[$c]['title'] = "مدت قرارداد";
        $items[$c]['placeholder'] = "ماه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractManagmentSDate";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ شروع";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractManagmentEDate";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ اتمام";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageContractHiddenCid";
        $c++;
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "contract_manage_mode";
        $c++;
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "current_contract_number";
        $c++;
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "contractManagmentAmountHidden";
        $c++;
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "contractPayedManagmentAmountHidden";
        $c++;
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "contractPayedManagmentRegestersAmountHidden";
        $c++;

         //************************************************************** Add For peyment formula **************************************/
      
         $items[$c]['type'] = "select";
         $items[$c]['name'] = "contractManagmentPayType";
         $items[$c]['id'] = "contractManagmentPayType";
         $items[$c]['title'] = "نوع پرداخت  ";
         $items[$c]['options'][0]['title'] = " نوع پرداخت را انتخاب نمایید";
         $items[$c]['options'][0]['value'] = 0;
         $items[$c]['options'][1]['title'] = " پرداخت علی الراس";
         $items[$c]['options'][1]['value'] = 1;
         $items[$c]['options'][2]['title'] = "براساس درصد  انجام کار";
         $items[$c]['options'][2]['value'] = 2;
         $items[$c]['options'][3]['title'] = "پرداخت ماهانه";
         $items[$c]['options'][3]['value'] = 3;

         $c++;
         $items[$c]['type']= "select";
         $items[$c]['id'] = "contractManagmentPayAmount";
         $items[$c]['onchange'] = 'onchange="toggle_formula_btn()"';
         $items[$c]['title'] = "روش پرداخت  ";
         $items[$c]['options'][0]['title'] = "فاقد فرمول";
         $items[$c]['options'][0]['value'] = 0;
         $items[$c]['options'][1]['title'] = "دارای فرمول";
         $items[$c]['options'][1]['value'] = 1;
        
         //$children_index++;
        
        //  $items[$c]['icon'] = "fa-plus";
        //  $items[$c]['btn_title'] = "اعمال/مشاهده فرمول";
        //  $items[$c]['style'] = "display:none";
        //  $items[$c]['onclick'] = 'onclick="manage_contract_payment_formula_box(this)"';

         $c++;
         $items[$c]['type'] = "button";
         $items[$c]['title'] = "اعمال/مشاهده فرمول";
         $items[$c]['style'] = "display:none";
         $items[$c]['id'] = "add_edit_formula";
         $items[$c]['class'] = "btn btn-success";
         $items[$c]['onclick'] = 'onclick="manage_contract_payment_formula_box(this)"';

         $c++;
         
         $items[$c]['type'] = "box";
         $items[$c]['title'] = "ایجاد/ویرایش  روش پرداخت";
         $items[$c]['name'] = "contractPaymentType_box";
         $items[$c]['id'] = "contractPaymentType_box";
         $items[$c]['BoxStyle'] = "padding:10px;border-radius: 10px;padding: 0;border: 2px solid gray;display: grid;grid-template-columns: 50% 50%";
         $items[$c]['TitleStyle'] = "width:auto;color:blue;font-size:1rem";
         
         $children_index=0;

        //  $items[$c]['children'][$children_index]['type']= "text";
        //  $items[$c]['children'][$children_index]['id'] = "contractMonths";
        //  $items[$c]['children'][$children_index]['style'] = "style='width: 50%;float: right;'";
        //  $items[$c]['children'][$children_index]['title'] = "  مدت زمان قرارداد به ماه";
        //  $items[$c]['children'][$children_index]['placeholder'] = "مدت زمان قرارداد به ماه";
        // $items[$c]['children'][$children_index]['type'] = "radio";
        // $items[$c]['children'][$children_index]['name'] = "contractManagmentPayType";
        // $items[$c]['children'][$children_index]['title'] = "نوع پرداخت  ";
        // $items[$c]['children'][$children_index]['options'][0]['title'] = "براساس درصد  انجام کار";
        // $items[$c]['children'][$children_index]['options'][0]['value'] = "p";
        // $items[$c]['children'][$children_index]['options'][1]['title'] = "پرداخت ماهانه";
        // $items[$c]['children'][$children_index]['options'][1]['value'] = "m";
        //  $children_index++;

        $items[$c]['children'][$children_index]['type'] = "inputGroup";
        $items[$c]['children'][$children_index]['inputType'] = "number";
        $items[$c]['children'][$children_index]['id'] = "row_count_for_create";
        $items[$c]['children'][$children_index]['icon'] = "fa-plus fa-lg";
        $items[$c]['children'][$children_index]['onclick'] = "onclick='create_pay_formula_row(this)'";
        $items[$c]['children'][$children_index]['title'] = "تعداد ردیف مورد نیاز ";
        $items[$c]['children'][$children_index]['placeholder'] = "تعداد ردیف مورد نیاز";

        $children_index++;
        $items[$c]['children'][$children_index]['title'] = "مبلغ قابل پرداخت قرارداد";
        $items[$c]['children'][$children_index]['id'] = "remind_pay_amount";
        $items[$c]['children'][$children_index]['type'] = "paragraph";
        $items[$c]['children'][$children_index]['style'] = "color:green;padding:0px 10px";
        
        $children_index++;
 
 
         //************************************************************** End For peyment formula********************************************/

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateContract";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateContractModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,'','','','',1);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE Contract MODAL +++++++++++++++++++++++++++++++++++++

         //++++++++++++++++++++++++++++++++++ Addendum Contract MODAL ++++++++++++++++++++++++++++++++
        //  $modalID = "contractAddendumManagmentModal";
        //  $modalTitle = "فرم مدیریت  الحاقیه قرارداد";
        //  $style = 'style="max-width: 750px;"';
        //  $footerBottons = array();
        //  $items = array();
       
        //  $c = 0;
        //  if($acm->hasAccess('editCreateContractAddendum')) {
        //     $modalTitle = "فرم ثبت/ویرایش  الحاقیه قرارداد";
        //     $items = array();
        //     $items[$c]['type'] = "text";
        //     $items[$c]['id'] = "contractAddendumDate";
        //     $items[$c]['title'] = "تاریخ";
        //     $items[$c]['placeholder'] = "تاریخ";
        //     $c++;

        //     $items[$c]['type'] = "textarea";
        //     $items[$c]['id'] = "contractAddendumDuty";
        //     $items[$c]['title'] = "شرح مسئولیت الحاقیه";
        //     $items[$c]['placeholder'] = "شرح مسئولیت الحاقیه";
        //     $c++;
 
        //     $items[$c]['type'] = "text";
        //     $items[$c]['id'] = "contractAddendumCost";
        //     $items[$c]['title'] = "مبلغ الحاقیه";
        //     $items[$c]['placeholder'] = "مبلغ اضافه بر سقف قرارداد  ";
        //     $items[$c]['onchange'] = "onchange='numberformat(this,1)'";
        //     $c++;

        //     $items[$c]['type'] = "hidden";
        //     $items[$c]['id'] = "contract_id";
        //     $c++;
        //     $items[$c]['type'] = "hidden";
        //     $items[$c]['id'] = "addendum_id";

        //     $c++;
        //     $footerBottons[0]['title'] = "تایید";
        //     $footerBottons[0]['jsf'] = "doEditCreateContractAddendum";
        //     $footerBottons[0]['type'] = "btn";
        //     $footerBottons[0]['data-dismiss'] = "NO";
        //     $footerBottons[1]['title'] = "انصراف";
        //     $footerBottons[1]['type'] = "dismis";
        //  }
        //  $contractAddendumManagmentModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //  $contractAddendumManagmentModal.="<div id='contractAddendumManagmentModal_body'></div><script>assignPersianDate(s=true)</script>";
         //++++++++++++++++++++++++++++++ END OF Addendum Contract MODAL +++++++++++++++++++++++++++++++++++++

        //++++++++++++++++++ Contract Add Attachment File Modal ++++++++++++++++++++++
        $modalID = "contractAttachmentFileModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'contractAttachmentFile-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractManagmentAttachmentName";
        $items[$c]['title'] = "نام فایل";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "contractManagmentAttachment";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PDF, JPG , PNG , XLSX , DOCX باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "contractManagmentAttachmentID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachFileToContract";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $contractAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Contract Add Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Contract Attachment File Modal ++++++++++++++++++++++
        $modalID = "showContractAttachmentFileModal";
        $modalTitle = "دانلود فایل های پیوست";
        $ShowDescription = 'showContractAttachmentFile-body';
        $style = 'style="max-width: 655px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showContractAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Contract Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Contract Comment Dates Modal ++++++++++++++++++++++
        $modalID = "contractCommentDatesModal";
        $modalTitle = "تاریخ های صدور اظهارنظر";
        $ShowDescription = 'contractCommentDates-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractCommentDatesNew";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "تاریخ صدور اظهارنظر";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "contractCommentDatesID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "افزودن";
        $footerBottons[0]['jsf'] = "doCreateContractCommentDates";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $contractCommentDates = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Contract Comment Dates Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Show Contract Pay Comments Modal ++++++++++++++++++++++
        $modalID = "showContractPayCommentsModal";
        $modalTitle = "اظهارنظر های قرارداد";
        $ShowDescription = 'showContractPayComments-body';
        $style = 'style="max-width: 1200px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $ShowContractPayComments = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Show Contract Pay Comments Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Comment Checks Contract Modal ++++++++++++++++++++++
        $modalID = "commentCheckInContractModal";
        $modalTitle = "چک/چک ها";
        $ShowDescription = 'comment-InContract-Checks-body';
        $style = 'style="max-width: 700px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showCommentChecksContract = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Comment Checks Contract Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Contract List for choose Modal ++++++++++++++++++++++
        $modalID = "showContractChooseListModal";
        $modalTitle = "لیست قرارداد ها";
        $ShowDescription = 'showContractChooseList-body';
        $style = 'style="max-width: 1200px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "انتخاب";
        $footerBottons[0]['jsf'] = "doChooseContractNumber";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $showContractChooseList = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Contract List for choose Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ EDIT CREATE Received Customer MODAL ++++++++++++++++++++++++++++++++
        $modalID = "receivedCustomerManagmentModal";
        $modalTitle = "فرم ثبت/ویرایش دریافتی از مشتری";
        $style = 'style="max-width: 555px;"';

        $banks = $this->getCompanyBanks();
        $cntb = count($banks);
        $pos = $this->getCompanyPos();
        $cntp = count($pos);

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "receivedCustomerReceiverType";
        $items[$c]['title'] = "نوع دریافتی";
        $items[$c]['options'][0]['title'] = "بابت فروش";
        $items[$c]['options'][0]['value'] = 0;
        $items[$c]['options'][1]['title'] = "بابت چک برگشتی";
        $items[$c]['options'][1]['value'] = 1;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "receivedCustomerReceivedMethod";
        $items[$c]['onchange'] = "onchange='getCheckNumber()'";
        $items[$c]['title'] = "روش دریافتی";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "واریزی به بانک ها";
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = "pos";
        $items[$c]['options'][2]["value"] = 2;
        $items[$c]['options'][3]["title"] = "چک";
        $items[$c]['options'][3]["value"] = 3;
        $items[$c]['options'][4]["title"] = "نقدی";
        $items[$c]['options'][4]["value"] = 4;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "receivedCustomerCstName";
        $items[$c]['onchange'] = "onchange='getCustomerCodeTafzili()'";
        $items[$c]['title'] = "طرف مقابل";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "receivedCustomerCstCode";
        $items[$c]['title'] = "کد تفضیلی طرف مقابل";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "receivedCustomerCompanyBanks";
        $items[$c]['title'] = "بانک های شرکت";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntb;$i++){
            $items[$c]['options'][$i+1]["title"] = $banks[$i]['Name'];
            $items[$c]['options'][$i+1]["value"] = $banks[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "receivedCustomerCompanyPos";
        $items[$c]['title'] = "pos های شرکت";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntp;$i++){
            $items[$c]['options'][$i+1]["title"] = $pos[$i]['Name'];
            $items[$c]['options'][$i+1]["value"] = $pos[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "receivedCustomerCheckSerial";
        $items[$c]['title'] = "شماره چک";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "receivedCustomerCheckDate";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "تاریخ چک";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "receivedCustomerCheckOwner";
        $items[$c]['title'] = "نام صاحب چک";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "receivedCustomerCheckOwnerCode";
        $items[$c]['title'] = "کد ملی صاحب چک";
        $items[$c]['placeholder'] = "کد ملی";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "receivedCustomerAmount";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['title'] = "مبلغ دریافتی";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "receivedCustomerRDate";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "تاریخ دریافتی";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "receivedCustomerDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageReceivedCustomerHiddenRid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateReceivedCustomer";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateReceivedModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++++++ End EDIT CREATE Received Customer MODAL ++++++++++++++++++++++++++++++++
        //++++++++++++++++++ Received Customer Add Attachment File Modal ++++++++++++++++++++++
        $modalID = "receivedCustomerAttachmentFileModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'receivedCustomerAttachmentFile-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "receivedCustomerManageAttachmentName";
        $items[$c]['title'] = "نام فایل";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "receivedCustomerManageAttachmentFile";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PNG , JPG, JPEG , JFIF , PDF , XLSX , DOCX , ZIP , RAR , WAV باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "receivedCustomerManageAttachmentID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachReceivedCustomerFile";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $receivedCustomerAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Received Customer Add Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Received Customer Attachment File Modal ++++++++++++++++++++++
        $modalID = "showReceivedCustomerAttachFileModal";
        $modalTitle = "دانلود فایل های پیوست";
        $ShowDescription = 'showReceivedCustomerAttachFile-body';
        $style = 'style="max-width: 655px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showReceivedCustomerAttachFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Received Customer Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ received Customer CheckInfo Modal ++++++++++++++++++++++
        $modalID = "receivedCustomerCheckInfoModal";
        $modalTitle = "اطلاعات چک";
        $ShowDescription = 'receivedCustomerCheckInfoBody';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $receivedCustomerCheckInfoModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End received Customer CheckInfo Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Show received Customer Pay Comments Modal ++++++++++++++++++++++
        $modalID = "showCustomerReceiveCommentsModal";
        $modalTitle = "اظهارنظرهای وصول مطالبات";
        $ShowDescription = 'showCustomerReceiveComments-body';
        $style = 'style="max-width: 1200px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showCustomerReceiveComments = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Show received Customer Pay Comments Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Comment Checks Customer Receive Modal ++++++++++++++++++++++
        $modalID = "commentCheckInCustomerReceiveModal";
        $modalTitle = "چک/چک ها";
        $ShowDescription = 'comment-InCustomerReceive-Checks-body';
        $style = 'style="max-width: 700px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showCommentChecksCustomerReceive = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Comment Checks Customer Receive Modal ++++++++++++++++++++++++
        //++++++++++++++++++ download CheckCarcass File Modal ++++++++++++++++++++++
        $modalID = "downloadCheckCarcassFileModal";
        $modalTitle = "دانلود رسید لاشه چک";
        $ShowDescription = 'downloadCheckCarcassFile-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $downloadCheckCarcassFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End download CheckCarcass File Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ EDIT CREATE Fund List MODAL ++++++++++++++++++++++++++++++++
        $modalID = "fundListManagmentModal";
        $modalTitle = "فرم ثبت/ویرایش اجزا تنخواه";
        $style = 'style="max-width: 555px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "fundListManageName";
        $items[$c]['title'] = "نوع تنخواه";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "تنخواه هزینه ای";
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = "تنخواه مصرفی";
        $items[$c]['options'][2]["value"] = 1;
        $items[$c]['options'][3]["title"] = "تنخواه مواد اولیه";
        $items[$c]['options'][3]["value"] = 2;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "fundListManageOneLayer";
        $items[$c]['title'] = "انتخاب سرگروه";
        $items[$c]['options'] = array();
        $items[$c]['onchange'] = "onchange=getFundSubLayerTwo()";
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$CountFundLayers;$i++){
            $items[$c]['options'][$i+1]["title"] = $fundLayers[$i]['layerName'];
            $items[$c]['options'][$i+1]["value"] = $fundLayers[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "fundListManageTwoLayer";
        $items[$c]['title'] = "انتخاب زیرگروه";
        $items[$c]['onchange'] = "onchange=getFundSubLayerThree()";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "fundListManageThreeLayer";
        $items[$c]['title'] = "انتخاب زیرگروه فرعی";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "fundListManageHiddenFid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateFundList";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateFundList = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++++++ End EDIT CREATE Fund List MODAL ++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ CREATE Fund List Details MODAL ++++++++++++++++++++++++++++++++
        $modalID = "fundListDetailsManagmentModal";
        $modalTitle = "فرم ثبت جزئیات تنخواه";
        $style = 'style="max-width: 700px;"';
        $ShowDescription = 'fundListDetails-Info-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "fundListDetailsCDate";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "تاریخ";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "fundListDetailsDescription";
        $items[$c]['title'] = "شرح";
        $items[$c]['placeholder'] = "شرح";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "fundListDetailsReqNum";
        $items[$c]['title'] = "شماره درخواست";
        $items[$c]['placeholder'] = "شماره";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "fundListDetailsPlaceUse";
        $items[$c]['title'] = "محل مورد استفاده";
        $items[$c]['placeholder'] = "محل مورد استفاده";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "fundListDetailsAmount";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['title'] = "مبلغ";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "fundListDetailsHiddenFid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCreateFundListDetails";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $createFundListDetails = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++++++ End CREATE Fund List Details MODAL ++++++++++++++++++++++++++++++++
        //++++++++++++++++++ fundList Add Attachment File Modal ++++++++++++++++++++++
        $modalID = "fundListAttachmentFileModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'fundListAttachmentFile-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "file";
        $items[$c]['id'] = "fundListAddAttachment";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PNG , JPG , JPEG , JFIF , PDF , XLSX , DOCX , ZIP , RAR باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "fundListAddAttachmentID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachFileToFundList";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $fundListAddAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End fundList Add Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show Attached Fund To Comment Modal ++++++++++++++++++++++
        $modalID = "showAttachedFundToCommentModal";
        $modalTitle = "لیست تنخواه ها";
        $ShowDescription = 'showAttachedFundToComment-body';
        $style = 'style="max-width: 1200px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "coverFundListHiddenID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال به Excel";
        $footerBottons[0]['jsf'] = "getCommentFundListExcel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "روکش تنخواه";
        $footerBottons[1]['jsf'] = "printCommentFundCover";
        $footerBottons[1]['type'] = "btn";
        $footerBottons[2]['title'] = "بستن";
        $footerBottons[2]['type'] = "dismis";
        $showAttachedFundToComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show Attached Fund To Comment Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ attach Comment To Fund MODAL ++++++++++++++++++++++++++++++++
        $modalID = "attachCommentToFundListModal";
        $modalTitle = "فرم پیوست اظهارنظر";

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "attachCommentToFundUNCode";
        $items[$c]['title'] = "کد یکتا اظهارنظر";
        $items[$c]['placeholder'] = "کد یکتا";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "attachCommentToFundFid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doAttachCommentToFundList";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $attachCommentToFundListModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++++++ End attach Comment To Fund MODAL ++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ show Fund List Details MODAL ++++++++++++++++++++++++++++++++
        $modalID = "showFundListDetailsModal";
        $modalTitle = "جزئیات تنخواه";
        $style = 'style="max-width: 700px;"';
        $ShowDescription = 'fundListDetails-show-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showFundListDetails = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++++++ End show Fund List Details MODAL ++++++++++++++++++++++++++++++++
        //++++++++++++++++++ fundList Show Attachment File Modal ++++++++++++++++++++++
        $modalID = "showFundListAttachmentFileModal";
        $modalTitle = "فایل های پیوست";
        $ShowDescription = 'showFundListAttachmentFile-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showFundListAddAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End fundList Show Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show Attached Fund To Send Comment Modal ++++++++++++++++++++++
        $modalID = "showAttachedFundToSendCommentModal";
        $modalTitle = "لیست تنخواه ها";
        $ShowDescription = 'showAttachedFundToSendComment-body';
        $style = 'style="max-width: 1200px;"';

        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showAttachedFundToSendComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show Attached Fund To Send Comment Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ SEND Pay Comment MODAL ++++++++++++++++++++++++++++++++
        $modalID = "sendPayCommentManagmentModal";
        $modalTitle = "فرم ارجاع اظهارنظر";
        $style = 'style="max-width: 555px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "payCommentManagmentReceiver";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['LimitNumSelections'] = 1;
        $items[$c]['title'] = "گیرنده";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cntu;$i++){
            $items[$c]['options'][$i+1]["title"] = $users[$i]['fname'].' '.$users[$i]['lname'];
            $items[$c]['options'][$i+1]["value"] = $users[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "payCommentManagmentConfirmedDesc";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "payCommentManagmentHiddenCid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doSendPayCommentInPC";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $sendPayCommentModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF SEND Pay Comment MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ contract Archive Extension MODAL ++++++++++++++++++++++++++++++++
        $modalID = "contractArchiveExtensionModal";
        $modalTitle = "فرم بایگانی/تمدید قرارداد";
        $style = 'style="max-width: 555px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "contractArchiveExtensionCType";
        $items[$c]['title'] = "تعیین وضعیت";
        $items[$c]['options'][0]['title'] = "بایگانی";
        $items[$c]['options'][0]['value'] = 0;
        $items[$c]['options'][1]['title'] = "تمدید";
        $items[$c]['options'][1]['value'] = 1;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "contractArchiveExtensionDescription";
        $items[$c]['title'] = "شرح";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractArchiveExtensionHourAmount";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['title'] = "مبلغ هر ساعت حضور";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractArchiveExtensionMaxHour";
        $items[$c]['title'] = "ماکسیمم ساعات حضور در ماه";
        $items[$c]['placeholder'] = "تعداد ساعت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractArchiveExtensionAmount";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['title'] = "مبلغ کل قرارداد";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractArchiveExtensionCredit";
        $items[$c]['title'] = "مدت قرارداد";
        $items[$c]['placeholder'] = "ماه";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "contractArchiveExtensionEDate";
        $items[$c]['style'] = "style='width: 50%;float: right;'";
        $items[$c]['title'] = "تاریخ اتمام";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageContractArchiveExtensionHiddenCid";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "manageContractArchiveExtensionHiddenType";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doArchiveExtensionContract";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $contractArchiveExtensionModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF contract Archive Extension MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++ Contract details Modal ++++++++++++++++++++++
        $modalID = "contractDetailsModal";
        $modalTitle = "سایر اطلاعات";
        $ShowDescription = 'contractDetailsBody';
        $style = 'style="max-width: 800px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $contractDetailsModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Contract details Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ cancellation PayComment MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "cancellationPayCommentModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به ابطال این اظهارنظر مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "cancellationPayComment_deleteIdHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doCancellationPayComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $cancellationPayComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++ END OF cancellation PayComment MODAL++++++++++++++++++++++++++++

        $htm .= $ShowContractPayComments;
        $htm .= $showCommentChecksContract;
        $htm .= $showCustomerReceiveComments;
        $htm .= $showCommentChecksCustomerReceive;
        $htm .= $editCreateAccountModal;
        $htm .= $editCreateCommentModal;
        $htm .= $sendCommentModal;
        $htm .= $showCommentInfo;
        $htm .= $showAccountInfo;
        $htm .= $addCheckToCommentModal;
        $htm .= $editCreateUnitModal;
        $htm .= $editCreateDepositorModal;
        $htm .= $transferPayComment;
        $htm .= $createDepositRegistration;
        $htm .= $showPayCommentInfo;
        $htm .= $finalApprovalModal;
        $htm .= $checkMabaleghVarizi;
        $htm .= $showDepositsInfo;
        $htm .= $showTempDepositsInfo;
        $htm .= $paymentReceiptDownload;
        $htm .= $commentAttachmentFile;
        $htm .= $commentAddAttachmentFile;
        $htm .= $commentWorkflow;
        $htm .= $sendTempCommentModal;
        $htm .= $doDeleteTempSendComment;
        $htm .= $doSendTempSendComment;
        $htm .= $showCommentChecks;
        $htm .= $showPayCommentInFinancialInfo;
        $htm .= $downloadCheckCarcassFinancialFile;
        $htm .= $financialApprovalModal;
        $htm .= $tempFinancialApprovalModal;
        $htm .= $checkLeftOverCash;
        $htm .= $checkLeftOverCheck;
        $htm .= $sendDepositToMaliModal;
        $htm .= $editCreateContractModal;
        $htm .= $contractAttachmentFile;
        $htm .= $showContractAttachmentFile;
        $htm .= $contractCommentDates;
        $htm .= $showContractChooseList;
        $htm .= $editCreateReceivedModal;
        $htm .= $receivedCustomerAttachmentFile;
        $htm .= $showReceivedCustomerAttachFile;
        $htm .= $receivedCustomerCheckInfoModal;
        $htm .= $downloadCheckCarcassFile;
        $htm .= $editCreateFundList;
        $htm .= $createFundListDetails;
        $htm .= $fundListAddAttachmentFile;
        $htm .= $showAttachedFundToComment;
        $htm .= $attachCommentToFundListModal;
        $htm .= $showAttachedFundToSendComment;
        $htm .= $showFundListDetails;
        $htm .= $showFundListAddAttachmentFile;
        $htm .= $sendPayCommentModal;
        $htm .= $contractArchiveExtensionModal;
        $htm .= $contractDetailsModal;
        $htm .= $cancellationPayComment;
        $htm .= $sendAutomaticCommentModal;
        $htm .= $managePartnersCartableModal;
        //$htm .= $contractAddendumManagmentModal;
        $htm .= $contractManagmentFormulaModal;
        $send = array($htm,$access);
        return $send;
    }

    public function getUserInfo($userID){
		$db=new DBi();
		$sql="SELECT * FROM users WHERE RowID={$userID}";
		//error_log("sql:".$sql);
		$res=$db->ArrayQuery($sql);
		if(count($res)>0){
			return $res[0];
		}
		
	}
	public function getUserInfoCartable($userid){
        $acm=new acm();
        $ut=new Utility();
        $db=new DBi();
        $exception_users=$ut->get_full_access_users(4);;
        $is_exception_user=in_array($userid,$exception_users)?1:0;
        if($is_exception_user==0)
        {
            $sql="SELECT * FROM pay_comment WHERE `lastReceiver` ={$userid} AND `isEnable`=1 AND `transfer` NOT IN(1,2,3)";

            $res=$db->ArrayQuery($sql);
            $htm="";
    
            if(count($res)>0)
            {
                $htm.='<div style="display:flex;justify-content:space-between;align-items:center;width:30%">
                            <button onclick="SelectAllRows()" class="btn btn-success">انتخاب همه</button>
                            <button onclick="unSelectAllRows()" class="btn btn-warning">انتخاب هیچکدام</button>
                            ';
                
                if($acm->hasAccess('cancellationPayComment')){
                    $htm.=' <button onclick="deleteSelectedPayComment()" class="btn btn-danger"> ابطال</button>';
                    
                }       
                $htm.='</div>';
                $htm.='<table id="ManagePartnersCartable_table" class="table table-striped  table-bordered "  style="width:100%">
                <thead style="color:#fff;">
                    <tr>
                        <td style="width:3%">#</td>
                        <td style="width:3%" >انتخاب</td>
                        <td style="width:14%"> کد یکتای اظهار نظر</td>
                        <td style="width:13%"> بابت</td>
                        <td style="width:10%"> طرف حساب</td>
                        <td style="width:10%">  مبلغ اظهارنظر</td>
                        <td style="width:10%"> وضعیت </td>
                        <td style="width:15%">توضیحات ارسال </td>
                        <td style="width:15%">توضیحات اظهار</td>
                        <td style="width:7%"> گردش کار</td>
                    </tr>
                </thead>
                <tbody>';
                for($i=0;$i<count($res);$i++){
                    $htm.="<tr>
                                <td>".intval($i+1)."</td>
                                <td><input type='checkbox' value='".intval($res[$i]['RowID'])."'></td>
                                <td>".$res[$i]['unCode']."</td>
                                <td>".$res[$i]['Toward']."</td>
                                <td>".$res[$i]['accName']."</td>
                                <td>".number_format($res[$i]['Amount'])." ریال</td>
                                <td><lable class='mr-2'><span class='mr-2'>تایید</span><input type='radio' value=1 name='payment_confirm_status_".intval($res[$i]['RowID'])."'></label><lable class='mr-2'><span class='mr-2'>عدم تایید</span><input type='radio' value=0 name='payment_confirm_status_".intval($res[$i]['RowID'])."'></label></td>
                                <td><textarea style='display:block;width:100%;height:100%' id='send_cemment_desc_".intval($res[$i]['RowID'])."'placeholder='توضیحات  را وارد نمایید'> </textarea></td>
                                <td>".$res[$i]['desc'].'</td>
                                <td><button onclick="ShowWorkflowCommentAgain(\''.$res[$i]['RowID'].'\')" title=" گردش کار" class="btn btn-success">  <i class="fa fa-history"></i></button>';
                                if($acm->hasAccess('fullAccessEditPayComment')){
                                    $htm.='<button onclick="editPayComment(\''.$res[$i]['RowID'].'\')" title="  ویرایش اظهار نظر" class="btn btn-primary">  <i class="fa fa-edit"></i></button>';
                                    
                                 }
                                    
                                $html.='</td>
                            </tr>';
                }
                $htm.="<tbody></table>";
                $send=array(true,$htm);
                return $send;
            }
            else{
                $send=array(false,$htm);
                return $send;
            }
        }
        else{
            return array(false,"شما مجاز به مشاهده کارتابل کاربر  انتخاب شده نمی باشد");
        }
    }
    
    public function deleteSelectedPayComment($pid_array){
        $db=new DBi();
        $ut=new Utility();
        $ut->fileRecorder($pid_array);
        $pids=implode(',',$pid_array);
        $ut->fileRecorder($pid);
        $sql="UPDATE  `pay_comment` SET isEnable = 0 where RowID in (".$pids.")";
       
        $ut->fileRecorder($sql);
        $res=$db->Query($sql);
        if(res){
            return true;
        }
        return false;
    }

    public function getUserFullName($userId,$get_gender=0){
        $db=new DBi();
        $getUserSql="SELECT fname,lname,gender from users where RowID={$userId}";
        
        $res=$db->ArrayQuery($getUserSql);
        if(count($res)>0){
			if($get_gender==0)
			{
				return $res[0]['fname']." ".$res[0]['lname'];
			}
			elseif($get_gender==1){
				if(res[0]['gender']==0)
					return " آقای"." ".$res[0]['fname']." ".$res[0]['lname'];
					if(res[0]['gender']==1)
					return " خانم"." ".$res[0]['fname']." ".$res[0]['lname'];
			}
        }
    }
    public function sendAutoCommentWorkflowHtm($userId){
        $db=new DBi();
        $ut=new Utility();
        $htm="";
		if($userId==1){
			$getautoSendInfo="Select * FROM `auto_send_pay_comment` WHERE  `status` NOT IN(9,0)";
		}
		else{
			$getautoSendInfo="Select * FROM `auto_send_pay_comment` WHERE insertedUser={$userId} AND `status` NOT IN(9,0)";
		}
        $autoSendRes=$db->ArrayQuery($getautoSendInfo);
        if(count($autoSendRes)>0){
            $htm.='<table  class="table  table-striped  table-bordered "  style="width:100%">
                    <thead style="color:#fff;">
                        <tr>
                            <td>#</td>
                            <td>کاربر غایب</td>
                            <td>کاربر  جانشین</td>
                            <td>تاریخ شروع ارجاع خودکار</td>
                            <td>مدت زمان ارجاع خودکار</td>
                            <td>توضیحات</td>
                            <td>حذف</td>
                        </tr>
                    </thead>
                    <tbody>';
            for($i=0;$i<count($autoSendRes);$i++){
                $htm.="<tr>
                            <td>".intval($i+1)."</td>
                            <td>".$this->getUserFullName($autoSendRes[$i]['absentReceiver'])."</td>
                            <td>".$this->getUserFullName($autoSendRes[$i]['substituteReceiver'])."</td>
                            <td>".$ut->greg_to_jal($autoSendRes[$i]['autoSendStartDate'])."</td>
                            <td>".$autoSendRes[$i]['autoSendDayes']." روز</td>
                            <td>".$autoSendRes[$i]['autoSendDesc']."</td>
                            <td>
                                <div style='width:100%;display:flex;justify-content:space-between;align-items:center'>
                                    <button class='btn btn-danger' onclick=\"confirmDeleteAutoSendRecord(".$autoSendRes[$i]['RowID'].")\"><i class='fa fa-trash'></i></button>
                                    
                                </div>
                            </td>
                        </tr>";
            }
        }
        $htm.="<tbody></table>";
        $send=array($htm);
        return $send;
    }
    public function getPayCommentManageList($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount,$page=1,$layer_one,$layer_two,$layer_three){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
		//$full_access_users=[1,67];
		
        $ut = new Utility();
        $db = new DBi();
		$full_access_users=$ut->get_full_access_users(6);
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`pay_comment`.`cDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$ceDate.'" ';
        }
        if(intval($cUnit) > 0){
            $w[] = '`Unit`='.$cUnit.' ';
        }
        if(intval($coUnit) > 0){
            $w[] = '`consumerUnit`='.$coUnit.' ';
        }
        if(strlen(trim($caName)) > 0){
            $w[] = '`accName` LIKE "%'.$caName.'%" ';
        }
        if(strlen(trim($cToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$cToward.'%" ';
        }
        if(strlen(trim($Uncode)) > 0){
            $w[] = '`unCode`='.$Uncode.' ';
        }
        if(strlen(trim($amount)) > 0){
            $w[] = '`Amount`='.$amount.' ';
        }
        //---------------------------------------------
        if($layer_one  > 0){
            $w[] = '`layer1`='.$layer_one.' ';
        }
        if($layer_two > 0){
            $w[] = '`layer2`='.$layer_two.' ';
        }
        if($layer_three > 0){
            $w[] = '`layer3`='.$layer_three.' ';
        }
        if (!in_array(intval($_SESSION['userid']),$full_access_users)){
			$w[] = '`transfer`=0 ';
            $w[] = '`lastReceiver`=' . $_SESSION['userid'] . ' ';
        }
	
        $w[] = '`isEnable`=1 ';

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`consumerUnit`,`Toward`,`Amount`,`unitName`,`sendType`,`sabtDarHesabType`,`priorityLevel`,
                    (SELECT project_name FROM  active_projects WHERE project_code=`pay_comment`.related_project )AS related_project, 
                        (SELECT unitName FROM relatedunits where `relatedunits`.RowID=`pay_comment`.consumerUnit) as cosumer_unit_name
                FROM `pay_comment` INNER JOIN `relatedunits`  ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $count_sql=$sql;
        $sql .= " ORDER BY `priorityLevel` DESC,`cDate` ASC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $related_barname=$this->get_related_comment_meta($res[$y]['pcid'],'is_drivers_fare_type');
            if(is_array($related_barname) && count($related_barname)>0){
               $finalRes[$y]['barname'] =$related_barname['value'];
            }
            else{
                $finalRes[$y]['barname'] =0; 
            }
            // $sqlUName = "SELECT `unitName` FROM `relatedunits` WHERE `RowID`={$res[$y]['consumerUnit']}";
            // $result = $db->ArrayQuery($sqlUName);
            switch ($res[$y]['sendType']){
                case 0:
                    $typeComment = 'فورج نقدی';
                    $finalRes[$y]['bgColor'] = 'table-warning';
                    break;
                case 1:
                    $typeComment = 'فورج چک';
                    $finalRes[$y]['bgColor'] = 'table-orange';
                    break;
                case 2:
                    $typeComment = 'سهامی';
                    $finalRes[$y]['bgColor'] = 'table-primary';
                    break;
                case 3:
                    $typeComment = ($res[$y]['sabtDarHesabType'] == 0 ? 'ثبت در حساب بستانکاری فورج' : 'ثبت در حساب بستانکاری سهامی');
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
            }
            $finalRes[$y]['RowID'] = $res[$y]['pcid'];
            $finalRes[$y]['sendType'] = $res[$y]['sendType'];
            $finalRes[$y]['typeComment'] = $typeComment;
            $finalRes[$y]['cDate'] = $ut->greg_to_jal($res[$y]['cDate']);
            $finalRes[$y]['Unit'] = $res[$y]['unitName'];
            $finalRes[$y]['consumerUnit'] = $res[$y]['cosumer_unit_name'];
            $finalRes[$y]['accName'] = (strlen(trim($res[$y]['accName'])) == 0 ? '--------' : $res[$y]['accName']);
            $finalRes[$y]['Toward'] = $res[$y]['Toward'];
            $finalRes[$y]['blinker'] = ($res[$y]['priorityLevel'] == 1 ? 'blink' : '');
            $finalRes[$y]['Amount'] = number_format($res[$y]['Amount']).' ریال';
            $finalRes[$y]['related_project'] = $res[$y]['related_project'];
        }
        $count_res=$db->ArrayQuery($count_sql);
        $count_res=count($count_res);
        $final_array[0]=$finalRes;
        $final_array[1]=$count_res;
        return $final_array;
    }

    public function get_related_comment_meta($rowID,$meta_key){
        $db=new DBi();
        $sql="SELECT * FROM `pay_comment_meta`  WHERE `pay_comment_id`={$rowID} AND `key`='{$meta_key}'";
        $res=$db->ArrayQuery($sql);
        if(count($res)>0){
           return $res[0];
        }
        else{
            return 0;
        }
    }

    public function DeleteAutoSendRecord($RowID){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db=new DBi();
        $delAutoSendQry="UPDATE auto_send_pay_comment SET `status`=9 WHERE `RowID`={$RowID}";
        $db->Query($delAutoSendQry);

        $updateResult = $db->AffectedRows();
        $res=$updateResult==0||$updateResult==-1? false :true;
        return $res;
       
    }

    public function editAutoSendRecord($autoSend_id){
        $ut= new Utility();
        $db = new DBi();
        $getAutoSendInfo="SELECT * FROM `auto_send_pay_comment` WHERE RowID = {$autoSend_id}";
        $result = $db->ArrayQuery($getAutoSendInfo);
        $countRecord=count($result);
        if($countRecord>0){
            $result[0]['autoSendStartDate']=$ut->greg_to_jal($result[0]['autoSendStartDate']);
            return $result;
        }
        else{
            return false;
        }
    }

    public function getPayCommentManageListCountRows($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount,$layer_one,$layer_two,$layer_three){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`pay_comment`.`cDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$ceDate.'" ';
        }
        if(intval($cUnit) > 0){
            $w[] = '`Unit`='.$cUnit.' ';
        }
        if(intval($coUnit) > 0){
            $w[] = '`consumerUnit`='.$coUnit.' ';
        }
        if(strlen(trim($caName)) > 0){
            $w[] = '`accName` LIKE "%'.$caName.'%" ';
        }
        if(strlen(trim($cToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$cToward.'%" ';
        }
        if(strlen(trim($Uncode)) > 0){
            $w[] = '`unCode`='.$Uncode.' ';
        }
        if(strlen(trim($amount)) > 0){
            $w[] = '`Amount`='.$amount.' ';
        }
        //---------------------------------
        if($layer_one > 0){
            $w[] = '`layer1`='.$layer_one.' ';
        }
        if($layer_two > 0){
            $w[] = '`layer2`='.$layer_two.' ';
        }
        if($layer_three > 0){
            $w[] = '`layer3`='.$layer_three.' ';
        }
        //--------------------------------------
        if (intval($_SESSION['userid']) !== 1) {
			$w[] = '`transfer`=0 ';
            $w[] = '`lastReceiver`=' . $_SESSION['userid'] . ' ';
        }
        $w[] = '`pay_comment`.`isEnable`=1 ';

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`Toward`,`Amount`,`unitName` 
                FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        //$ut->fileRecorder($sql);
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getPayCommentSendManageList($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`pay_comment`.`cDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$ceDate.'" ';
        }
        if(intval($cUnit) > 0){
            $w[] = '`Unit`='.$cUnit.' ';
        }
        if(intval($coUnit) > 0){
            $w[] = '`consumerUnit`='.$coUnit.' ';
        }
        if(strlen(trim($caName)) > 0){
            $w[] = '`accName` LIKE "%'.$caName.'%" ';
        }
        if(strlen(trim($cToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$cToward.'%" ';
        }
        if(strlen(trim($Uncode)) > 0){
            $w[] = '`unCode`='.$Uncode.' ';
        }
        if($acm->hasAccess('SeeCommentWorkflowHistory')){
            $pids=[];
            $sql="SELECT `receiver`,`pid` from `payment_workflow` where `receiver`={$_SESSION['userid']} group by `pid`";
            $res=$db->ArrayQuery($sql);
            if(count($res)>0){
                foreach($res as $key=>$value){
                    $pids[]=$value['pid'];
                }
                $pids_imp=implode(",",$pids);
                $w[] = '(`uid`='.$_SESSION['userid'] .' OR `pay_comment`.`RowID` IN ('.$pids_imp.')) ';
            }
            else{
                $w[] = '`uid`='.$_SESSION['userid'].' ';
            }
             
        }
        else{
            $w[] = '`uid`='.$_SESSION['userid'].' ';
       }
       
        $w[] = '`isEnable`=1 ';

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`consumerUnit`,`Toward`,`Amount`,`unitName`,`sendType`,`priorityLevel`   
                FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)";


        
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `pcid` DESC LIMIT $start,".$numRows;
        $ut->fileRecorder('sql:::::::*-*****:'.$sql);
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $sqlUName = "SELECT `unitName` FROM `relatedunits` WHERE `RowID`={$res[$y]['consumerUnit']}";
            $result = $db->ArrayQuery($sqlUName);
            switch ($res[$y]['sendType']){
                case 0:
                    $finalRes[$y]['bgColor'] = 'table-warning';
                    break;
                case 1:
                    $finalRes[$y]['bgColor'] = 'table-orange';
                    break;
                case 2:
                    $finalRes[$y]['bgColor'] = 'table-primary';
                    break;
                case 3:
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
            }
            $finalRes[$y]['RowID'] = $res[$y]['pcid'];
            $finalRes[$y]['cDate'] = $ut->greg_to_jal($res[$y]['cDate']);
            $finalRes[$y]['Unit'] = $res[$y]['unitName'];
            $finalRes[$y]['consumerUnit'] = $result[0]['unitName'];
            $finalRes[$y]['accName'] = (strlen(trim($res[$y]['accName'])) == 0 ? '--------' : $res[$y]['accName']);
            $finalRes[$y]['Toward'] = $res[$y]['Toward'];
            $finalRes[$y]['blinker'] = ($res[$y]['priorityLevel'] == 1 ? 'blink' : '');
            $finalRes[$y]['Amount'] = number_format($res[$y]['Amount']).' ریال';
        }
        return $finalRes;
    }

    public function getPayCommentSendManageListCountRows($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`pay_comment`.`cDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$ceDate.'" ';
        }
        if(intval($cUnit) > 0){
            $w[] = '`Unit`='.$cUnit.' ';
        }
        if(intval($coUnit) > 0){
            $w[] = '`consumerUnit`='.$coUnit.' ';
        }
        if(strlen(trim($caName)) > 0){
            $w[] = '`accName` LIKE "%'.$caName.'%" ';
        }
        if(strlen(trim($cToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$cToward.'%" ';
        }
        if(strlen(trim($Uncode)) > 0){
            $w[] = '`unCode`='.$Uncode.' ';
        }
        if($acm->hasAccess('SeeCommentWorkflowHistory')){//
            $pids=[];
            $sql="SELECT `receiver`,`pid` from `payment_workflow` where `receiver`={$_SESSION['userid']} group by `pid`";
            $res=$db->ArrayQuery($sql);
            if(count($res)>0){
                foreach($res as $key=>$value){
                    $pids[]=$value['pid'];
                }
                $pids_imp=implode(",",$pids);
                $w[] = '(`uid`='.$_SESSION['userid'] .' OR `pay_comment`.`RowID` IN ('.$pids_imp.')) ';
            }
            else{
                $w[] = '`uid`='.$_SESSION['userid'].' ';
            }
             
        }
        else{
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }
       
        $w[] = '`isEnable`=1 ';

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`Toward`,`Amount`,`unitName` 
                FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }


    // public function getPayCommentStagnantManageList($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$cardboardID,$page=1){
    //     $acm = new acm();
    //     if(!$acm->hasAccess('stagnantCommentManagement')){
    //         die("access denied");
    //         exit;
    //     }
    //     $ut = new Utility();
    //     $db = new DBi();
    //     $query = "  
    //         SELECT  pc.RowID as RowID ,pc.Amount,pc.sendType, r.unitName,(select unitName FROM relatedunits where RowID=pc.Unit) as pc_unit,pw.`createDate`,pw.`receiver`,pw.RowID as pw_id,u.fname,u.lname,
    //         pc.`accName`,pc.`cDate`,pc.`Toward`,pc.`priorityLevel`
    //         FROM pay_comment as pc
           
    //         LEFT JOIN payment_workflow as pw
    //             on (pc.`RowID`=pw.`pid` AND pw.receiver=pc.lastReceiver)
    //         INNER JOIN `relatedunits` as r on r.RowID=pc.consumerUnit
    //         INNER JOIN `users` as u on u.RowID=pc.lastReceiver
    //     WHERE  `transfer`!=3 AND pc.isEnable=1 AND DATEDIFF(CURRENT_DATE, pw.createDate)>=5  AND pw.done=0 ";
    //     // $query = "SELECT pc.RowID,pw.`createDate`,pw.`receiver`
    //     // FROM pay_comment as pc
    //     // LEFT JOIN payment_workflow as pw
    //     //     on (pc.`RowID`=pw.`pid` AND pw.receiver=pc.lastReceiver)
    //     // WHERE  `transfer`!=3 AND pc.isEnable=1 ";

    //    if(intval($cardboardID)>0){
    //         $query.="AND pw.`receiver`={$cardboardID}" ;
    //    }

    // //    $rids=$db->ArrayQueryCustom($query,'pw_id');
    // //    $ut->fileRecorder($rids);
    //     $numRows = LISTCNT;
    //     $start = ($page-1)*$numRows;
    //     $w = array();
    //     if(strlen(trim($csDate)) > 0){
    //         $csDate = $ut->jal_to_greg($csDate);
    //         $w[] = '`pc`.`cDate` >="'.$csDate.'" ';
    //     }
    //     if(strlen(trim($ceDate)) > 0){
    //         $ceDate = $ut->jal_to_greg($ceDate);
    //         $w[] = '`pc`.`cDate` <="'.$ceDate.'" ';
    //     }
    //     if(intval($cUnit) > 0){
    //         $w[] = 'pc.`Unit`='.$cUnit.' ';
    //     }
    //     if(intval($coUnit) > 0){
    //         $w[] = 'pc.`consumerUnit`='.$coUnit.' ';
    //     }
    //     if(strlen(trim($caName)) > 0){
    //         $w[] = 'pc.`accName` LIKE "%'.$caName.'%" ';
    //     }
    //     if(strlen(trim($cToward)) > 0){
    //         $w[] = 'pc.`Toward` LIKE "%'.$cToward.'%" ';
    //     }
    //     if(strlen(trim($Uncode)) > 0){
    //         $w[] = 'pc.`unCode`='.$Uncode.' ';
    //     }
    //     if(strlen(trim($cardboardID)) > 0){
    //         $w[] = 'pc.`lastReceiver`='.$cardboardID.' ';
    //     }
    //    // cardboardID
    //     // $rids = implode(',',$rids);
    //     // $w[] = '`pw`.`RowID` IN ('.$rids.') ';
    //     //$w[] = '`isEnable`=1 ';

    //     // $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`consumerUnit`,`Toward`,`Amount`,`unitName`,`sendType`,`priorityLevel` ,relatedunits.`unitName` as unit_title 
    //     //         FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)  INNER JOIN payment_workflow as pw on pw.pid=pay_comment.`RowID` ";
    //     if(count($w) > 0){
    //         $where = implode(" AND ",$w);
    //         $query .= " AND ".$where;
    //     }
    //     $count_sql=$query;

    //     $query .= " ORDER BY pw.`RowID` DESC LIMIT $start,".$numRows;
    //    $ut->fileRecorder($query);
    //     $res = $db->ArrayQuery($query);
    //     $listCount = count($res);
    //     $finalRes = array();
    //     for($y=0;$y<$listCount;$y++){

    //     //     // $sqlUName = "SELECT `unitName` FROM `relatedunits` WHERE `RowID`={$res[$y]['consumerUnit']}";
    //     //     // $result = $db->ArrayQuery($sqlUName);

    //     //   $sqlReceiver = "SELECT `fname`,`lname`,`createDate` FROM `payment_workflow` INNER JOIN `users` ON (`payment_workflow`.`receiver`=`users`.`RowID`) WHERE done=0 AND  `pid`={$res[$y]['pcid']} ORDER BY `payment_workflow`.`RowID` DESC LIMIT 1";
    //     //    $ut->fileRecorder($sqlReceiver);
    //     //   $result1 = $db->ArrayQuery($sqlReceiver);
    //     //     if (strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($result1[0]['createDate'].'+5 days'))) || true){

            
    //     //     error_log('salam');
    //         switch ($res[$y]['sendType']){
    //             case 0:
    //                 $finalRes[$y]['bgColor'] = 'table-warning';
    //                 break;
    //             case 1:
    //                 $finalRes[$y]['bgColor'] = 'table-orange';
    //                 break;
    //             case 2:
    //                 $finalRes[$y]['bgColor'] = 'table-primary';
    //                 break;
    //             case 3:
    //                 $finalRes[$y]['bgColor'] = 'table-danger';
    //                 break;
    //         }
    //         $finalRes[$y]['RowID'] = $res[$y]['RowID'];
    //         $finalRes[$y]['Unit'] = $res[$y]['pc_unit'];
    //         $finalRes[$y]['consumerUnit'] = $res[$y]['unitName'];
    //         $finalRes[$y]['receiver'] = $res[$y]['fname'].' '.$res[$y]['lname'];
    //         $finalRes[$y]['cDate'] = $ut->greg_to_jal($res[$y]['createDate']);
    //         $finalRes[$y]['accName'] = (strlen(trim($res[$y]['accName'])) == 0 ? '--------' : $res[$y]['accName']);
    //         $finalRes[$y]['Toward'] = $res[$y]['Toward'];
    //         $finalRes[$y]['blinker'] = ($res[$y]['priorityLevel'] == 1 ? 'blink' : '');
    //         $finalRes[$y]['Amount'] = number_format($res[$y]['Amount']).' ریال';
    //     // }
    //     // else{
           
    //     // }
    // }
    //     $count_res=$db->ArrayQuery($count_sql);
    //     $count_res=count($count_res);
    //     $final_res_ultimate=[];
    //     foreach($finalRes as $k=>$value){
    //       $final_res_ultimate[]=$value;  
    //     }
    //     $final_array[0]=$final_res_ultimate;
    //     $final_array[1]=$count_res;
       
    //     return $final_array;
    // }

    public function getPayCommentStagnantManageList($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$cardboardID,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('stagnantCommentManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
    //     $query = "  SELECT pc.RowID,pw.`createDate`,pw.`receiver`
    //     FROM pay_comment as pc
    //     LEFT JOIN payment_workflow as pw
    //         on (pc.`RowID`=pw.`pid` AND pw.receiver=pc.lastReceiver)
    //    WHERE  `transfer`!=3 AND pc.isEnable=1 AND DATEDIFF(CURRENT_DATE, pw.createDate)>=5 group by pw.pid";


    $query = "  SELECT pc.RowID,pw.`createDate`,pw.`receiver`
    FROM pay_comment as pc
    LEFT JOIN payment_workflow as pw
        on (pc.`RowID`=pw.`pid` AND pw.receiver=pc.lastReceiver)
    WHERE  `transfer`!=3 AND pc.isEnable=1 AND DATEDIFF(CURRENT_DATE, pw.createDate)>=5 AND done=0";


       $result_pay=$db->ArrayQuery($query);
       $rids = array();
       foreach($result_pay as $res_key=>$res_value){
            // if (strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($res_value['createDate'].'+5 days'))))
            // {
                $rids[] = $res_value['RowID'];
          //  }
       }

       ///-----------------------------------------------------------------------
       /*
        $query = "  SELECT pc.RowID,pw.`createDate`,pw.`receiver`
        FROM pay_comment as pc
        LEFT JOIN payment_workflow as pw
            on pc.`RowID`=pw.`pid`
       WHERE  `transfer`!=3 ";

       $result_pay=$db->ArrayQuery($query);
       $rids = array();
       foreach($result_pay as $res_key=>$res_value){
            if (strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($res_value['createDate'].'+5 days'))))
            {
                if(intval($cardboardID) > 0){
                    if (intval($cardboardID) == intval($res_value['receiver'])){
                        $rids[] = $res_value['RowID'];
                    }
                }else{
                    $rids[] = $res_value['RowID'];
                }
            }
       }

       */ 
       ///-----------------------------------------------------------------------
        $rids=array_unique($rids);
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`pay_comment`.`cDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$ceDate.'" ';
        }
        if(intval($cUnit) > 0){
            $w[] = '`Unit`='.$cUnit.' ';
        }
        if(intval($coUnit) > 0){
            $w[] = '`consumerUnit`='.$coUnit.' ';
        }
        if(strlen(trim($caName)) > 0){
            $w[] = '`accName` LIKE "%'.$caName.'%" ';
        }
        if(strlen(trim($cToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$cToward.'%" ';
        }
        if(strlen(trim($Uncode)) > 0){
            $w[] = '`unCode`='.$Uncode.' ';
        }
        if(strlen(trim($cardboardID)) > 0){
            $w[] = '`lastReceiver`='.$cardboardID.' ';
        }
       // cardboardID
        $rids = implode(',',$rids);
        $w[] = '`pay_comment`.`RowID` IN ('.$rids.') ';
        //$w[] = '`isEnable`=1 ';

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`consumerUnit`,`lastReceiver`,`Toward`,`Amount`,`unitName`,`sendType`,`priorityLevel`   
                FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $count_sql=$sql;
       




        $sql .= " ORDER BY `pcid` DESC LIMIT $start,".$numRows;
      //  $ut->fileRecorder($sql);
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        $counter=1;
        for($y=0;$y<$listCount;$y++){

            $sqlUName = "SELECT `unitName` FROM `relatedunits` WHERE `RowID`={$res[$y]['consumerUnit']}";
            $result = $db->ArrayQuery($sqlUName);

            $sqlReceiver = "SELECT `fname`,`lname`,`createDate` FROM `payment_workflow` INNER JOIN `users` ON (`payment_workflow`.`receiver`=`users`.`RowID`) WHERE `pid`={$res[$y]['pcid']} ORDER BY `payment_workflow`.`RowID` DESC LIMIT 1";
           // $ut->fileRecorder($sqlReceiver);
            $result1 = $db->ArrayQuery($sqlReceiver);
          // if (strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($result1[0]['createDate'].'+5 days')))){
                switch ($res[$y]['sendType']){
                    case 0:
                        $finalRes[$y]['bgColor'] = 'table-warning';
                        break;
                    case 1:
                        $finalRes[$y]['bgColor'] = 'table-orange';
                        break;
                    case 2:
                        $finalRes[$y]['bgColor'] = 'table-primary';
                        break;
                    case 3:
                        $finalRes[$y]['bgColor'] = 'table-danger';
                        break;
                }
                $finalRes[$y]['counter'] =$start+$counter;
                $finalRes[$y]['RowID'] = $res[$y]['pcid'];
                $finalRes[$y]['Unit'] = $res[$y]['unitName'];
                $finalRes[$y]['consumerUnit'] = $result[0]['unitName'];
                $finalRes[$y]['receiver'] = $result1[0]['fname'].' '.$result1[0]['lname'];
                $finalRes[$y]['cDate'] = $ut->greg_to_jal($result1[0]['createDate']);
                $finalRes[$y]['accName'] = (strlen(trim($res[$y]['accName'])) == 0 ? '--------' : $res[$y]['accName']);
                $finalRes[$y]['Toward'] = $res[$y]['Toward'];
                $finalRes[$y]['blinker'] = ($res[$y]['priorityLevel'] == 1 ? 'blink' : '');
                $finalRes[$y]['Amount'] = number_format($res[$y]['Amount']).' ریال';
                $counter++;
               
          //  }
       // else
       // {
           
       // }
    }
        $count_res_sql=$db->ArrayQuery($count_sql);
        $count_res=count($count_res_sql);
        $final_res_ultimate=[];
        foreach($finalRes as $k=>$value){
          $final_res_ultimate[]=$value;  
        }
        $final_array[0]=$final_res_ultimate;
        $final_array[1]=$count_res;
        $receiver_array=[];
        // $recievers_query="SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`consumerUnit`,`lastReceiver`,`Toward`,`Amount`,`unitName`,`sendType`,`priorityLevel`   
        // FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)
        // left JOIN (select * from payment_workflow group by pid order by RowID DESC limit 1 ) as pw on pw.pid=`pay_comment`.`RowID` ". 'where `pay_comment`.`RowID` IN ('.$rids.') group by pcid';
        // $reciver_res = $db->ArrayQuery($recievers_query);
        
        // foreach($reciver_res as $key=>$value){
        //     $reciver_id_sql="SELECT `receiver`,createDate FROM `payment_workflow`  WHERE `pid`={$value['pcid']} and receiver={$value['lastReceiver']} AND DATEDIFF(CURRENT_DATE, createDate)>=5 ORDER BY `RowID` DESC LIMIT 1";
        //     $res_recieve=$db->ArrayQuery($reciver_id_sql);
        //     $receiver_array[]=$res_recieve[0]['receiver'];
        //     $ut->fileRecorder($res_recieve[0]['receiver']."=".$value['pcid']);
        // }
        $count_sql_receivers="SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`consumerUnit`,`lastReceiver`,`Toward`,`Amount`,`unitName`,`sendType`,`priorityLevel`   
        FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`) where  `pay_comment`.`RowID` IN (".$rids.")";
        $ut->fileRecorder($count_sql_receivers);
        $count_sql_receivers_res=$db->ArrayQuery($count_sql_receivers);
        foreach($count_sql_receivers_res as $key=>$value){
            // $reciver_id_sql="SELECT `receiver`,createDate FROM `payment_workflow`  WHERE `pid`={$value['pcid']} and receiver={$value['lastReceiver']} AND DATEDIFF(CURRENT_DATE, createDate)>=5 ORDER BY `RowID` DESC LIMIT 1";
            // $res_recieve=$db->ArrayQuery($reciver_id_sql);
            $receiver_array[]=$value['lastReceiver'];
            //$ut->fileRecorder($res_recieve[0]['receiver']."=".$value['pcid']);
        }
        $array_count_comments=array_count_values($receiver_array);

        $ultimate_cardboard=[];
        foreach($array_count_comments as $key=>$value){
            $ultimate_cardboard[$key]=array('comment_count'=>$value,'cardboard_name'=>$ut->get_user_fullname($key));
        }
        $final_array[2]=$ultimate_cardboard;
       // $ut->fileRecorder($final_array);
        return $final_array;
    }
    // public function getPayCommentStagnantManageListCountRows($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$cardboardID){
    //     $ut = new Utility();
    //     $db = new DBi();

    //     $query = "SELECT `RowID` FROM `pay_comment` WHERE `transfer`!=3";
    //     $rst = $db->ArrayQuery($query);
    //     $cnt = count($rst);
    //     $rids = array();
    //     for ($i=0;$i<$cnt;$i++) {
    //         $workFlowSQL = "SELECT `createDate`,`receiver` FROM `payment_workflow` WHERE `pid`={$rst[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
    //         $rst1 = $db->ArrayQuery($workFlowSQL);
    //         if (count($rst1) > 0) {
    //             if (strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($rst1[0]['createDate'].'+5 days')))){
    //                 if(intval($cardboardID) > 0){
    //                     if (intval($cardboardID) == intval($rst1[0]['receiver'])){
    //                         $rids[] = $rst[$i]['RowID'];
    //                     }
    //                 }else{
    //                     $rids[] = $rst[$i]['RowID'];
    //                 }
    //             }
    //         }
    //     }
    //     $w = array();
    //     if(strlen(trim($csDate)) > 0){
    //         $csDate = $ut->jal_to_greg($csDate);
    //         $w[] = '`pay_comment`.`cDate` >="'.$csDate.'" ';
    //     }
    //     if(strlen(trim($ceDate)) > 0){
    //         $ceDate = $ut->jal_to_greg($ceDate);
    //         $w[] = '`pay_comment`.`cDate` <="'.$ceDate.'" ';
    //     }
    //     if(intval($cUnit) > 0){
    //         $w[] = '`Unit`='.$cUnit.' ';
    //     }
    //     if(intval($coUnit) > 0){
    //         $w[] = '`consumerUnit`='.$coUnit.' ';
    //     }
    //     if(strlen(trim($caName)) > 0){
    //         $w[] = '`accName` LIKE "%'.$caName.'%" ';
    //     }
    //     if(strlen(trim($cToward)) > 0){
    //         $w[] = '`Toward` LIKE "%'.$cToward.'%" ';
    //     }
    //     if(strlen(trim($Uncode)) > 0){
    //         $w[] = '`unCode`='.$Uncode.' ';
    //     }
    //     $rids = implode(',',$rids);
    //     $w[] = '`pay_comment`.`RowID` IN ('.$rids.') ';
    //     $w[] = '`isEnable`=1 ';

    //     $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`consumerUnit`,`Toward`,`Amount`,`unitName`,`sendType`  
    //             FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)";
    //     if(count($w) > 0){
    //         $where = implode(" AND ",$w);
    //         $sql .= " WHERE ".$where;
    //     }
    //     $res = $db->ArrayQuery($sql);
    //     return count($res);
    // }

    
    public function getPayCommentStagnantManageListCountRows($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$cardboardID){
        $ut = new Utility();
        $db = new DBi();

        // $query = "SELECT `RowID` FROM `pay_comment` WHERE `transfer`!=3";
        // $rst = $db->ArrayQuery($query);
        // $cnt = count($rst);
        $rids = array();
        //****************************************** */

        $query = "  SELECT pc.RowID,pw.`createDate`,pw.`receiver`
        FROM pay_comment as pc
        LEFT JOIN payment_workflow as pw
            on pc.`RowID`=pw.`pid`
       WHERE  `transfer`!=3 ";

       $result_pay=$db->ArrayQuery($query);
       $rids = array();
       foreach($result_pay as $res_key=>$res_value){
            if (strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($res_value['createDate'].'+5 days'))))
            {
                if(intval($cardboardID) > 0){
                    if (intval($cardboardID) == intval($res_value['receiver'])){
                        $rids[] = $res_value['RowID'];
                    }
                }else{
                    $rids[] = $res_value['RowID'];
                }
            }
       }

        //****************************************** */
        // for ($i=0;$i<$cnt;$i++) {
        //     $workFlowSQL = "SELECT `createDate`,`receiver` FROM `payment_workflow` WHERE `pid`={$rst[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
        //     $rst1 = $db->ArrayQuery($workFlowSQL);
        //     if (count($rst1) > 0) {
        //         if (strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($rst1[0]['createDate'].'+5 days')))){
        //             if(intval($cardboardID) > 0){
        //                 if (intval($cardboardID) == intval($rst1[0]['receiver'])){
        //                     $rids[] = $rst[$i]['RowID'];
        //                 }
        //             }else{
        //                 $rids[] = $rst[$i]['RowID'];
        //             }
        //         }
        //     }
        // }
        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`pay_comment`.`cDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$ceDate.'" ';
        }
        if(intval($cUnit) > 0){
            $w[] = '`Unit`='.$cUnit.' ';
        }
        if(intval($coUnit) > 0){
            $w[] = '`consumerUnit`='.$coUnit.' ';
        }
        if(strlen(trim($caName)) > 0){
            $w[] = '`accName` LIKE "%'.$caName.'%" ';
        }
        if(strlen(trim($cToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$cToward.'%" ';
        }
        if(strlen(trim($Uncode)) > 0){
            $w[] = '`unCode`='.$Uncode.' ';
        }
        $rids = implode(',',$rids);
        $w[] = '`pay_comment`.`RowID` IN ('.$rids.') ';
        $w[] = '`isEnable`=1 ';

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`consumerUnit`,`Toward`,`Amount`,`unitName`,`sendType`  
                FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function get_related_property_number($parent_id){
        $acm = new acm();
       
        $ut = new Utility();
        $db = new DBi();
        $sql="SELECT l2.RowID as r2
             From layers as l1 LEFT JOIN layers as l2 on l1.RowID=l2.parentID where l1.parentID={$parent_id} and l2.layerName like '%خدماتی%'";
        $res=$db->ArrayQuery($sql);
        $ut->fileRecorder($sql);
        $ $row_ids=[];
        foreach($res as $v){
            $row_ids[]=$v['r2'];
        }
        return $row_ids;
        
    }

    public function getPayCommentStagnantManageList1($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$cardboardID,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('stagnantCommentManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`pay_comment`.`cDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$ceDate.'" ';
        }
        if(intval($cUnit) > 0){
            $w[] = '`Unit`='.$cUnit.' ';
        }
        if(intval($coUnit) > 0){
            $w[] = '`consumerUnit`='.$coUnit.' ';
        }
        if(strlen(trim($caName)) > 0){
            $w[] = '`accName` LIKE "%'.$caName.'%" ';
        }
        if(strlen(trim($cToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$cToward.'%" ';
        }
        if(strlen(trim($Uncode)) > 0){
            $w[] = '`unCode`='.$Uncode.' ';
        }
        if(intval($cardboardID) > 0){
            $w[] = '`lastReceiver`='.intval($cardboardID).' ';
        }
       // $w[] = '( (CURDATE() >= (`paymentMaturityCash` + INTERVAL 5 DAY)) OR (CURDATE() >= (`paymentMaturityCheck` + INTERVAL 5 DAY))) AND  `transfer`!=3 ';
        $w[] = 'if (paymentMaturityCash <> "0000-00-00", (CURDATE() >= (`paymentMaturityCash` + INTERVAL 5 DAY)) , (CURDATE() >= (`paymentMaturityCheck` + INTERVAL 5 DAY))) AND  `transfer`!=3 ';
        $w[] = '`isEnable`=1 ';

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`lastReceiver`,`accName`,`cDate`,`Unit`,`consumerUnit`,`Toward`,`Amount`,`unitName`,`sendType`,`paymentMaturityCash`,`paymentMaturityCheck`,`priorityLevel`   
                FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `pcid` DESC LIMIT $start,".$numRows;
        $ut->fileRecorder($sql);
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $sqlUName = "SELECT `unitName` FROM `relatedunits` WHERE `RowID`={$res[$y]['consumerUnit']}";
            $result = $db->ArrayQuery($sqlUName);

            $sqlReceiver = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$y]['lastReceiver']}";
            $result1 = $db->ArrayQuery($sqlReceiver);

            switch ($res[$y]['sendType']){
                case 0:
                    $finalRes[$y]['bgColor'] = 'table-warning';
                    break;
                case 1:
                    $finalRes[$y]['bgColor'] = 'table-orange';
                    break;
                case 2:
                    $finalRes[$y]['bgColor'] = 'table-primary';
                    break;
                case 3:
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
            }
            $finalRes[$y]['counter'] = $start+$y+1;
            $finalRes[$y]['RowID'] = $res[$y]['pcid'];
            $finalRes[$y]['paymentMaturityCash'] = (strtotime($res[$y]['paymentMaturityCash']) > 0 ? $ut->greg_to_jal($res[$y]['paymentMaturityCash']) : '' );
            $finalRes[$y]['paymentMaturityCheck'] = (strtotime($res[$y]['paymentMaturityCheck']) > 0 ? $ut->greg_to_jal($res[$y]['paymentMaturityCheck']) : '' );
            $finalRes[$y]['Unit'] = $res[$y]['unitName'];
            $finalRes[$y]['consumerUnit'] = $result[0]['unitName'];
            $finalRes[$y]['receiver'] = $result1[0]['fname'].' '.$result1[0]['lname'];
            $finalRes[$y]['accName'] = (strlen(trim($res[$y]['accName'])) == 0 ? '--------' : $res[$y]['accName']);
            $finalRes[$y]['Toward'] = $res[$y]['Toward'];
            $finalRes[$y]['blinker'] = ($res[$y]['priorityLevel'] == 1 ? 'blink' : '');
            $finalRes[$y]['Amount'] = number_format($res[$y]['Amount']).' ریال';
        }
        return $finalRes;
    }

    public function getPayCommentStagnantManageListCountRows1($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$cardboardID){
        $ut = new Utility();
        $db = new DBi();

        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`pay_comment`.`cDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$ceDate.'" ';
        }
        if(intval($cUnit) > 0){
            $w[] = '`Unit`='.$cUnit.' ';
        }
        if(intval($coUnit) > 0){
            $w[] = '`consumerUnit`='.$coUnit.' ';
        }
        if(strlen(trim($caName)) > 0){
            $w[] = '`accName` LIKE "%'.$caName.'%" ';
        }
        if(strlen(trim($cToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$cToward.'%" ';
        }
        if(strlen(trim($Uncode)) > 0){
            $w[] = '`unCode`='.$Uncode.' ';
        }
        if(intval($cardboardID) > 0){
            $w[] = '`lastReceiver`='.intval($cardboardID).' ';
        }
        $w[] = '( (CURDATE() >= (`paymentMaturityCash` + INTERVAL 5 DAY)) OR (CURDATE() >= (`paymentMaturityCheck` + INTERVAL 5 DAY))) AND  `transfer`!=3 ';
        $w[] = '`isEnable`=1 ';

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`lastReceiver`,`accName`,`cDate`,`Unit`,`consumerUnit`,`Toward`,`Amount`,`unitName`,`sendType`  
                FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)";
       // $count_sql=$sql;
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $count_sql="SELECT `pay_comment`.`RowID` AS `pcid`,`lastReceiver`,`accName`,`cDate`,`Unit`,`consumerUnit`,`Toward`,`Amount`,`unitName`,`sendType`  
                        FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`) 
                        WHERE ( (CURDATE() >= (`paymentMaturityCash` + INTERVAL 5 DAY)) OR (CURDATE() >= (`paymentMaturityCheck` + INTERVAL 5 DAY))) 
                            AND  `transfer`!=3 AND `isEnable`=1 ";
        $res_count=$db->ArrayQuery($count_sql);
        $receivers_id=[];
        foreach($res_count as $key=>$value){
            $receivers_id[]=$value['lastReceiver'];
        }
        $receivers_comment_count=array_count_values($receivers_id);
        $ultimate_count_array=[];
        foreach($receivers_comment_count as $comment_count_index=>$comment_count_value){
            $ultimate_count_array[$comment_count_index]=array('comment_count'=>$comment_count_value,'cardboard_name'=>$ut->get_user_fullname($comment_count_index));
        }
        $final_array[0]=count($res);
        $final_array[1]=$ultimate_count_array;
        return $final_array;
    }

    // public function getCardboard(){
    //     $db = new DBi();
    //     $query = "SELECT `RowID` FROM `pay_comment` WHERE `transfer`!=3 AND `isEnable`=1";
    //     $rst = $db->ArrayQuery($query);
    //     $cnt = count($rst);
    //     $rids = array();
    //     for ($i=0;$i<$cnt;$i++) {
    //         $workFlowSQL = "SELECT `createDate`,`receiver` FROM `payment_workflow` WHERE `pid`={$rst[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
    //         $rst1 = $db->ArrayQuery($workFlowSQL);
    //         if (count($rst1) > 0) {
    //             if (strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($rst1[0]['createDate'].'+5 days')))){
    //                 $rids[] = $rst1[0]['receiver'];
    //             }
    //         }
    //     }
    //     $rids = array_values(array_unique($rids));
    //     $rids = implode(',',$rids);
    //     $sql = "SELECT `RowID`,`fname`,`lname` FROM `users` WHERE `RowID` IN ({$rids})";
    //     $res = $db->ArrayQuery($sql);
    //     if (count($res) > 0) {
    //         return $res;
    //     } else {
    //         return array();
    //     }
    // }


    public function getCardboard(){
        $db = new DBi();
        // $query = "SELECT `RowID` FROM `pay_comment` WHERE `transfer`!=3 AND `isEnable`=1";
        // $rst = $db->ArrayQuery($query);
        // $cnt = count($rst);

        $query = "  SELECT pc.RowID,pw.`createDate`,pw.`receiver`
        FROM pay_comment as pc
        LEFT JOIN payment_workflow as pw
            on pc.`RowID`=pw.`pid`
       WHERE  `transfer`!=3 AND  `isEnable`=1";
    
       $result_pay=$db->ArrayQuery($query);
       $rids = array();
       foreach($result_pay as $res_key=>$res_value){
                if (strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($res_value['createDate'].'+5 days'))))
                {
                // if(intval($cardboardID) > 0){
                    //  if (intval($cardboardID) == intval($res_value['receiver'])){
                            $rids[] = $res_value['receiver'];
                    //  }
                // }else{
                        //$rids[] = $res_value['RowID'];
                }
            }
      // }
       // $rids = array();
        // for ($i=0;$i<$cnt;$i++) {
        //     $workFlowSQL = "SELECT `createDate`,`receiver` FROM `payment_workflow` WHERE `pid`={$rst[$i]['RowID']} ORDER BY `RowID` DESC LIMIT 1";
        //     $rst1 = $db->ArrayQuery($workFlowSQL);
        //     if (count($rst1) > 0) {
        //         if (strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($rst1[0]['createDate'].'+5 days')))){
        //             $rids[] = $rst1[0]['receiver'];
        //         }
        //     }
        // }
        $rids = array_values(array_unique($rids));
        $rids = implode(',',$rids);
        $sql = "SELECT `RowID`,`fname`,`lname` FROM `users` WHERE `RowID` IN ({$rids})";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0) {
            return $res;
        } else {
            return array();
        }
    }

    public function getCardboard1(){
        $db = new DBi();
        $sql = "SELECT `lastReceiver` FROM `pay_comment` WHERE ((CURDATE() >= (`paymentMaturityCash` + INTERVAL 5 DAY)) OR (CURDATE() >= (`paymentMaturityCheck` + INTERVAL 5 DAY))) AND `transfer`!=3 AND `isEnable`=1";
        $rst = $db->ArrayQuery($sql);
        $cnt = count($rst);
        $rids = array();
        for ($i=0;$i<$cnt;$i++) {
            $rids[] = $rst[$i]['lastReceiver'];
        }
        $rids = array_values(array_unique($rids));
        $rids = implode(',',$rids);
        $sql = "SELECT `RowID`,`fname`,`lname` FROM `users` WHERE `RowID` IN ({$rids})";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0) {
            return $res;
        } else {
            return array();
        }
    }

    public function convert_meta_data($array){
        $ut=new Utility();
        $handler=[];
        foreach($array as $key=>$value){
           
            $handler[$value['key']] =$value['value'] ;
        }
        return $handler;
        
    }

    public function commentInfo($cid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `pay_comment` WHERE `RowID`=".$cid;
        $res = $db->ArrayQuery($sql);
        $paymentMaturityCash = (strtotime($res[0]['paymentMaturityCash']) > 0 ? $ut->greg_to_jal($res[0]['paymentMaturityCash']) : '');
        $paymentMaturityCheck = (strtotime($res[0]['paymentMaturityCheck']) > 0 ? $ut->greg_to_jal($res[0]['paymentMaturityCheck']) : '');
        $clearingFundDate = (strtotime($res[0]['clearingFundDate']) > 0 ? $ut->greg_to_jal($res[0]['clearingFundDate']) : '');
        $checkDate = (strtotime($res[0]['checkDate']) > 0 ? $ut->greg_to_jal($res[0]['checkDate']) : '');
        $checkDeliveryDate = (strtotime($res[0]['checkDeliveryDate']) > 0 ? $ut->greg_to_jal($res[0]['checkDeliveryDate']) : '');
        $goodLoan = (strtotime($res[0]['goodLoan']) > 0 ? $ut->greg_to_jal($res[0]['goodLoan']) : '');
        $comment_meta_sql="SELECT * FROM `pay_comment_meta` where `pay_comment_id`={$cid}" ;
        $meta_res = $db->ArrayQuery($comment_meta_sql);
        $meta_array = $this->convert_meta_data($meta_res);
        

        if(count($res) == 1){
            if (strlen(trim($res[0]['accNumber'])) > 0){
                $res = array("cid"=>$cid,"Unit"=>$res[0]['Unit'],"type"=>$res[0]['type'],"Toward"=>$res[0]['Toward'],"consumerUnit"=>$res[0]['consumerUnit'],"Amount"=>number_format($res[0]['Amount']),"totalAmount"=>number_format($res[0]['totalAmount']),
                             "accName"=>$res[0]['accName'],"codeTafzili"=>$res[0]['codeTafzili'],"cFor"=>$res[0]['cFor'],"CashSection"=>number_format($res[0]['CashSection']),
                             "paymentMaturityCash"=>$paymentMaturityCash,"NonCashSection"=>(intval($res[0]['NonCashSection']) == 0 ? '' : number_format($res[0]['NonCashSection'])),"paymentMaturityCheck"=>$paymentMaturityCheck,
                             "RequestSource"=>$res[0]['RequestSource'],"RequestNumbers"=>$res[0]['RequestNumbers'],"desc"=>$res[0]['desc'],"sendType"=>$res[0]['sendType'],"layer1"=>$res[0]['layer1'],
                             "layer2"=>$res[0]['layer2'],"clearingFundDate"=>$clearingFundDate,"contractNumber"=>$res[0]['contractNumber'],"checkNumber"=>$res[0]['checkNumber'],
                             "checkDate"=>$checkDate,"checkCarcass"=>intval($res[0]['checkCarcass']),"checkDeliveryDate"=>$checkDeliveryDate,"sabtDarHesabType"=>$res[0]['sabtDarHesabType'],
                             "cardNumber"=>$res[0]['cardNumber'],"layer3"=>$res[0]['layer3'],"goodLoan"=>$goodLoan,
                             "checkOutType"=>$res[0]['checkOutType'],'related_project'=>$res[0]['related_project'],'related_vat'=>!empty($res[0]['related_vat'])?$res[0]['related_vat']:0,'transfer'=>!empty($res[0]['transfer'])?$res[0]['transfer']:0,'PropertyNumber'=>!empty($res[0]['PropertyNumber'])?$res[0]['PropertyNumber']:0,
                             'is_drivers_fare'=>!empty($meta_array['is_drivers_fare'])?$meta_array['is_drivers_fare']:0,'is_drivers_fare_type'=>!empty($meta_array['is_drivers_fare_type'])?$meta_array['is_drivers_fare_type']:0
                );
                return $res;
            }else{
                $res = array("cid"=>$cid,"Unit"=>$res[0]['Unit'],"type"=>$res[0]['type'],"Toward"=>$res[0]['Toward'],"consumerUnit"=>$res[0]['consumerUnit'],"totalAmount"=>number_format($res[0]['totalAmount']),
                             "Amount"=>number_format($res[0]['Amount']),"BillingID"=>$res[0]['BillingID'],"PaymentID"=>$res[0]['PaymentID'],"CashSection"=>(intval($res[0]['CashSection']) == 0 ? '' : number_format($res[0]['CashSection'])),
                             "paymentMaturityCash"=>$paymentMaturityCash,"RequestSource"=>$res[0]['RequestSource'],"RequestNumbers"=>$res[0]['RequestNumbers'],"desc"=>$res[0]['desc'],"cFor"=>$res[0]['cFor'],"sendType"=>$res[0]['sendType'],
                             "layer1"=>$res[0]['layer1'],"layer2"=>$res[0]['layer2'],"clearingFundDate"=>$clearingFundDate,"contractNumber"=>$res[0]['contractNumber'],"sabtDarHesabType"=>$res[0]['sabtDarHesabType'],"layer3"=>$res[0]['layer3'],
                             'related_project'=>$res[0]['related_project'],'related_vat'=>!empty($res[0]['related_vat'])?$res[0]['related_vat']:0,'transfer'=>!empty($res[0]['transfer'])?$res[0]['transfer']:0,'PropertyNumber'=>!empty($res[0]['PropertyNumber'])?$res[0]['PropertyNumber']:0,
                             'is_drivers_fare'=>!empty($meta_array['is_drivers_fare'])?$meta_array['is_drivers_fare']:0,'is_drivers_fare_type'=>!empty($meta_array['is_drivers_fare_type'])?$meta_array['is_drivers_fare_type']:0
                );
                return $res;
            }
        }else{
            return false;
        }
    }

    public function get_trading_levels($en_title,$return_approvers=0){
        $db=new DBi();
        $sql="SELECT `transaction_amount`,`corroborant` FROM trading_levels where `status`=1 AND en_title='{$en_title}'";
        $res=$db->ArrayQuery($sql);
        if($return_approvers==1){
            return array($res[0]['transaction_amount'], $res[0]['corroborant'] );
        }
        else{
            return array($res[0]['transaction_amount']);
        }
    }

    public function get_contract_payed_records($contract_id,$contract_number){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $get_payed_comments="SELECT `RowID`,`unCode` ,`totalAmount` FROM `pay_comment` WHERE `contractNumber` ='{$contract_number}'";
        $payed_rows = $db->ArrayQuery($get_payed_comments);
        $payed_rows = $db->ArrayQuery($get_payed_comments);
        //$ut->fileRecorder($payed_rows);
        foreach($payed_rows as $row ){
             $get_payed_contract_formula="SELECT `pid` FROM `contract_pay_formula` WHERE `pid`={$row['RowID']} AND `contract_id`='{$contract_id}' AND `status` IN (1,2)";
             $res=$db->ArrayQuery($get_payed_contract_formula);
             //$ut->fileRecorder($res);
             if(count($res)==0){
                $pay_description="اختصاص داده شده به  اظهارنظر شماره ".$row['unCode'];
                $history=array("create_date"=>date("Y-m-d H:i:s"),'user'=>$_SESSION['userid'],"transaction_type"=>"Insert");
                    $history_json=json_encode($history,JSON_UNESCAPED_UNICODE);
                $insert_sql="INSERT  INTO contract_pay_formula (contract_id,amount_pay_part,description_pay_part,percentage_increase_allowable_temperature,CEO_confirm,history,pid,`status`,`user_id`)
                VALUES('{$contract_id}','{$row['totalAmount']}','{$pay_description}','',1,'{$history_json}','{$row['RowID']}',2,'{$_SESSION['userid']}')";
                   //$ut->fileRecorder($insert_sql);
                 $db->Query($insert_sql);
             }
            
        }
        $sql_pay_row="SELECT * FROM contract_pay_formula where contract_id='{$contract_id}' AND `status` IN (1,2)";
        
        $final_res=$db->ArrayQuery($sql_pay_row);
        return $final_res;
       
        



    }
    public function createPayComment($pMethod,$pType,$Unit,$consumerUnit,$Type,$Toward,$totalTransactionAmount,$Amount,$AccNum,$CashSection,$MaturityCash,$NonCashSection,$MaturityCheck,$RequestSource,$RequestNumbers,$Desc,$billID,$payID,$layer1,$layer2,$cFund,$cForv,$code,$ContractNum,$CheckNumber,$CheckDate,$DeliveryDate,$CheckCarcass,$files,$CardNumber,$layer3,$goodLoan,$checkOutType,$related_project,$related_vat,$is_drivers_fare,$is_drivers_fare_type,$PropertyNumber){
       // //error_log('related_project:'.$related_project);
       $ut = new Utility();
       
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
           
            die("access denied");
            exit;
        }
        $db = new DBi();
       
        $sqq = "SELECT `uids` FROM `relatedunits` WHERE `RowID`={$Unit}";
        $result  = $db->ArrayQuery($sqq);

        $uids = explode(',',$result[0]['uids']);
       
        if (!in_array($_SESSION['userid'],$uids)){
            return -2;
        }

        $cDate = date('Y-m-d');
        if (strlen(trim($ContractNum)) > 0)
        {
            $fDate = date('Y-m-01');
            $lDate = date("Y-m-t");

            $aql = "SELECT `RowID`,`totalAmount`,`contractType`,`monthlyAmount`,`ceDate`,`accNum` FROM `contract` WHERE `number`='{$ContractNum}'";
            $rstaql = $db->ArrayQuery($aql);
			if($AccNum=="null" || empty($AccNum)){
				$AccNum=$rstaql[0]['accNum'];
			}
        
            $contractType = intval($rstaql[0]['contractType']);
            if (strtotime($cDate) > strtotime($rstaql[0]['ceDate'])){
                $res = "قرارداد منقضی شده است !!!";
                $out = "false";
                response($res,$out);
                exit;
            }
            //****************************************************** */
            $pay_row_formula="SELECT * FROM `contract_pay_formula`  where `contract_id`={$rstaql[0]['RowID']} AND `status`=1 AND 
				(`CEO_confirm`=1 OR percentage_increase_allowable_temperature=0 OR percentage_increase_allowable_temperature IS NULL ) ORDER BY `RowID` ASC LIMIT 1";
            $p_res=$db->ArrayQuery($pay_row_formula);
          
            $percent_amount = (!empty($p_res[0]['percentage_increase_allowable_temperature'])?intval($p_res[0]['percentage_increase_allowable_temperature']):0);
            $pey_row_amount = (!empty($p_res[0]['amount_pay_part'])?intval($p_res[0]['amount_pay_part']):0);
            $formula_row_id = $p_res[0]['RowID'];
            $addendum_amount= $pey_row_amount*$percent_amount/100;
            if(count($p_res)>0){
            //    $ut->fileRecorder('Amount:'.$Amount);
            //    $ut->fileRecorder('pey_row_amount:'.$pey_row_amount);
            //    $ut->fileRecorder('addendum_amount:'.$addendum_amount);
                if($Amount>($pey_row_amount+$addendum_amount)){

                    return -5;
                }
            }
           
            else
            {
                
            // }
            
            //****************************************************** */
            $addendum_sql="SELECT SUM(`Addendum_price`) as addendum_amount from `contract_addendum` where `contract_id`={$rstaql[0]['RowID']} AND `status`=1 AND `addendum_status`=3";
            $addendum_sql_res=$db->ArrayQuery($addendum_sql);
            //error_log($addendum_sql);
            $addendum_amount=$addendum_sql_res[0]['addendum_amount'];
           // $addendum_amount=0;
            $totalSaderShode = 0;
            $totalSaderShode1 = 0;
            if (count($rstaql) > 0) 
            {
                if ($contractType == 0) 
                {  // عادی باشد
                    $sqll = "SELECT `RowID`,`Amount` FROM `pay_comment` WHERE `contractNumber`='{$ContractNum}' AND isEnabled=1";
                    $rstl = $db->ArrayQuery($sqll);
                }
                else
                {  // ساعتی باشد
                    $sqll = "SELECT `RowID`,`Amount` FROM `pay_comment` WHERE `contractNumber`='{$ContractNum}' AND `cDate` >= '{$fDate}' AND `cDate` <= '{$lDate}'";
                    $rstl = $db->ArrayQuery($sqll);

                    $sql2 = "SELECT `RowID`,`Amount` FROM `pay_comment` WHERE `contractNumber`='{$ContractNum}'";
                    $rst2 = $db->ArrayQuery($sql2);
                    $cnt2 = count($rst2);
                    for ($b = 0; $b < $cnt2; $b++) {
                        $totalSaderShode1 += $rst2[$b]['Amount'];
                    }
                    $albaghi1 =$addendum_amount+$rstaql[0]['totalAmount'] - $totalSaderShode1;
                   //$ut->fileRecorder($albaghi);
                   // $ut->fileRecorder($Amount);
                    if (intval($albaghi1) < intval($Amount)){
                        return -5;
                    }
                }
                $cntl = count($rstl);
                for ($j=0;$j<$cntl;$j++){
                    $totalSaderShode += $rstl[$j]['Amount'];
                }

                if (intval($rstaql[0]['contractType']) == 0) 
                {  // عادی
                    $albaghi = $addendum_amount+$rstaql[0]['totalAmount'] - $totalSaderShode;
                    error_log('addendom:'.$addendum_amount);
                    error_log('total:'.$rstaql[0]['totalAmount']);
                    error_log('sader:'.$totalSaderShode);
                }
                else
                {  // ساعتی
                    $albaghi = $addendum_amount+$rstaql[0]['monthlyAmount'] - $totalSaderShode;
                    //error_log('albaghi3:'.$albaghi);
                }
                if (intval($albaghi) < intval($Amount)){
                    $ut->fileRecorder($albaghi);
                    $ut->fileRecorder($Amount);
                    return -5;
                }

                $sqqq = "SELECT `RowID` FROM `contract_dates` WHERE `commentDate` <= '{$cDate}' AND `cid`={$rstaql[0]['RowID']} ORDER BY `RowID` ASC LIMIT 1";
                $rstqq = $db->ArrayQuery($sqqq);

                if (count($rstqq) > 0) 
                {
                    $sql11 = "UPDATE `contract_dates` SET `status`=0 WHERE `RowID`={$rstqq[0]['RowID']}";
                    $db->Query($sql11);
                }/* else {
                    return -3;
                }*/
            }
            else
            {
                return -4;
            }
        }

    }
    //return array('transaction_amount'=>$res[0]['transaction_amount'], 'corroborant'=>$res[0]['corroborant'] ); 
    
    // if (intval($totalTransactionAmount) <= 100000000 ){
    //         $Transactions = 1;
    //     }elseif (intval($totalTransactionAmount) <= 1000000000){
    //         $Transactions = 2;
    //     }elseif (intval($totalTransactionAmount) <= 5000000000){
    //         $Transactions = 3;
    //     }else{
    //         $Transactions = 4;
    //     }
        if (intval($totalTransactionAmount) <= $this->get_trading_levels('small_purchases')[0] ){
            $Transactions = 1;
        }elseif (intval($totalTransactionAmount) <= $this->get_trading_levels('middel_purchases')[0]){
            $Transactions = 2;
        }elseif (intval($totalTransactionAmount) <= $this->get_trading_levels('major_purchases')[0]){
            $Transactions = 3;
        }else{
            $Transactions = 4;
        }

        $SFile = array();
        $allowedTypes = ['png','jpg','jpeg','jfif','pdf','PNG','JPG','JPEG','PDF','JFIF'];

        if (isset($files) && !empty($files)) {
            $no_files = count($files['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files["tmp_name"][$i];
                if ($files["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -6;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -7;
                }
                $format = substr($files['name'][$i], strpos($files['name'][$i], ".") + 1);
                if(!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -8;
                }
                $SFile[] = "checkCarcass" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $CashSection = (strlen(trim($CashSection)) > 0 ? $CashSection : 0);
        $NonCashSection = (strlen(trim($NonCashSection)) > 0 ? $NonCashSection : 0);
        $MaturityCash = (strlen(trim($MaturityCash)) > 0 ? $ut->jal_to_greg($MaturityCash) : $MaturityCash);
        $MaturityCheck = (strlen(trim($MaturityCheck)) > 0 ? $ut->jal_to_greg($MaturityCheck) : $MaturityCheck);
        $cFund = (strlen(trim($cFund)) > 0 ? $ut->jal_to_greg($cFund) : $cFund);
        $goodLoan = (strlen(trim($goodLoan)) > 0 ? $ut->jal_to_greg($goodLoan) : $goodLoan);
        $CheckDate = (strlen(trim($CheckDate)) > 0 ? $ut->jal_to_greg($CheckDate) : $CheckDate);
        $DeliveryDate = (strlen(trim($DeliveryDate)) > 0 ? $ut->jal_to_greg($DeliveryDate) : $DeliveryDate);
        $CheckCarcass = (intval($CheckCarcass) == -1 ? 'NULL' : $CheckCarcass);
        $totalAmount = ($CashSection) + ($NonCashSection);
        if (intval($AccNum) != -1){
            $AccID = explode(',',$AccNum);
            $accountID = $AccID[0];
            $query = "SELECT `Name`,`accountNumber`,`bankName`,`code`,`codeMelli` FROM `account` WHERE `RowID`={$AccID[0]}";
			//////$ut->fileRecorder('accc:'.$query);
            $rst = $db->ArrayQuery($query);
            $cFor = $AccNum;
            $accnum = explode(',',$rst[0]['accountNumber']);
            $position = $AccID[1];
            $accountNumber = $accnum[$position];
            $bank = explode(',',$rst[0]['bankName']);
            $accBank = $bank[$position];
            $codeTafzili = $rst[0]['code'];
            $nationalCode = $rst[0]['codeMelli'];
            $accName = $rst[0]['Name'];
        }else{
            if ($Type != 'پرداخت قبض' && $Type != 'پرداخت جریمه') {
                $sqlID = "SELECT `RowID` FROM `account` WHERE `Name`='{$cForv}'";
                $rstID = $db->ArrayQuery($sqlID);
                $accountID = $rstID[0]['RowID'];
            }else{
                $accountID = 'NULL';
            }
            $accountNumber = '';
            $accBank = '';
            $codeTafzili = '88888';
            $nationalCode = '';
            $accName = '';
            $cFor = '';
        }

        if (intval($pMethod) == 0){  // سهامی
            $sendType = 2;
        }elseif (intval($pMethod) == 1 && intval($pType) == 0){ // فورج نقدی
            $sendType = 0;
        }else{ // فورج چک
            $accountNumber = 'ندارد';
            $codeTafzili = $code;
            $accName = $cForv;
            $sendType = 1;
        }

        $flag = true;
        if ($Type != 'ثبت در حساب بستانکاری طرف مقابل'){
            if (($totalAmount) != ($Amount)){
                $flag = false;
            }
            $sabtDarHesabType = 'NULL';
        }else{
            $sabtDarHesabType = (intval($sendType) == 0 ? 0 : 1);
            $accountNumber = 'ندارد';
            $codeTafzili = $code;
            $accName = $cForv;
            $sendType = 3;
            $CashSection = 0;
            $NonCashSection = 0;
        }

        if (intval($layer1) == 5){  // قبوض شرکتی
            switch (intval($layer2)){
                case 60:  // آب
                    $accName = 'شرکت آب منطقه اي استان خراسان رضوي';
                    $codeTafzili = 400012;
                    $accountNumber = '0';
                    $accBank = 'ندارد';
                    break;
                case 61:  // برق
                    $accName = 'شرکت برق استان خراسان رضوي';
                    $codeTafzili = 400009;
                    $accountNumber = '0';
                    $accBank = 'ندارد';
                    break;
                case 62:  // گاز
                    $accName = 'شرکت گاز استان خراسان رضوي';
                    $codeTafzili = 400010;
                    $accountNumber = '0';
                    $accBank = 'ندارد';
                    break;
                case 63:  // تلفن
                    $accName = 'شرکت مخابرات خراسان رضوي';
                    $codeTafzili = 400011;
                    $accountNumber = '0';
                    $accBank = 'ندارد';
                    break;
            }
        }

        if (intval($layer1) == 50){  // وسائط نقلیه
            switch (intval($layer2)){
                case 51:  // پژو پارس خاکستری 94
                case 52:  // پژو پارس خاکستری 99
                case 53:  // سمند سفید
                case 54:  // سمند نوک مدادی
                case 55:  // کامیونت
                case 56:  // موتور سیکلت
                case 80:  // موتور سیکلت شارژی
                case 85:  // مزدا
                    if (intval($layer3) == 223 || intval($layer3) == 224 || intval($layer3) == 225 || intval($layer3) == 226 || intval($layer3) == 227 || intval($layer3) == 228 || intval($layer3) == 229 || intval($layer3) == 230 || intval($layer3) == 299 || intval($layer3) == 300 || intval($layer3) == 301 || intval($layer3) == 302 || intval($layer3) == 303 || intval($layer3) == 304 || intval($layer3) == 305){
                        $accName = 'راهنمايي و رانندگي';
                        $codeTafzili = 161281;
                        $accountNumber = '0';
                        $accBank = 'ندارد';
                    }
                    break;
            }
        }

        if (intval($layer1) == 8) {  // سهامداران
            switch (intval($layer2)){
                case 17:  // سیدرضا رضوی
                    if ($Type == 'پرداخت قبض' || $Type == 'پرداخت جریمه'){
                        $accName = 'رضوي سيد رضا';
                        $codeTafzili = 115004;
                        $accountNumber = '0';
                        $accBank = 'ندارد';
                        break;
                    }
                case 18:  // سید جواد رضوی
                    if ($Type == 'پرداخت قبض' || $Type == 'پرداخت جریمه'){
                        $accName = 'رضوي سيد جواد';
                        $codeTafzili = 115002;
                        $accountNumber = '0';
                        $accBank = 'ندارد';
                        break;
                    }
                case 19:  // سید جمال رضوی
                    if ($Type == 'پرداخت قبض' || $Type == 'پرداخت جریمه'){
                        $accName = 'رضوي سيد جمال';
                        $codeTafzili = 115003;
                        $accountNumber = '0';
                        $accBank = 'ندارد';
                        break;
                    }
                case 20:  // سیدجواد و سیدجمال رضوی باالمناصفه
                    if ($Type == 'پرداخت قبض' || $Type == 'پرداخت جریمه'){
                        $accName = 'سهامداران سيدجواد و سيدجمال رضوي';
                        $codeTafzili = 150020;
                        $accountNumber = '0';
                        $accBank = 'ندارد';
                        break;
                    }
            }
        }

        if (intval($layer1) == 9 && intval($layer2) == 84) {  // مالی و مالیاتی
            if ($Type == 'پرداخت قبض' || $Type == 'پرداخت جریمه') {
                $accName = 'اداره کل امور مالياتي خراسان رضوي';
                $codeTafzili = 160971;
                $accountNumber = '0';
                $accBank = 'ندارد';
            }
        }


        $datetostring = substr($ut->greg_to_jal(str_replace('/','-',date('Y/m/d'))),2,2);
        $unCode = $datetostring.$codeTafzili.substr(time(), -4);
		//////$ut->fileRecorder("codeTafzili:" .$codeTafzili);

        if ($flag){
            $vat_expert_id=$_SESSION['userid'];
            // if($related_vat==1){
            //     $vat_expert_id=$ut->get_full_access_users(8)[0];
            // }
            $sql = "INSERT INTO `pay_comment` 
            (`type`,`cDate`,`Toward`,`totalAmount`,`Amount`,`CashSection`,`NonCashSection`,`Transactions`,`Unit`,`consumerUnit`,`desc`,`uid`,`BillingID`,`PaymentID`,`cFor`,`accNumber`,`accName`,`accBank`,`codeTafzili`,`nationalCode`,`paymentMaturityCash`,`paymentMaturityCheck`,`RequestSource`,`RequestNumbers`,`unCode`,`sendType`,`layer1`,`layer2`,`clearingFundDate`,`lastReceiver`,`contractNumber`,`checkNumber`,`checkDate`,`checkCarcass`,`checkDeliveryDate`,`sabtDarHesabType`,`cardNumber`,`layer3`,`accountID`,`goodLoan`,`checkOutType`,`related_project`,`related_VAT`,`PropertyNumber`) 
            VALUES ('{$Type}','{$cDate}','{$Toward}',{$totalTransactionAmount},{$Amount},{$CashSection},{$NonCashSection},{$Transactions},{$Unit},{$consumerUnit},'{$Desc}',{$_SESSION['userid']},'{$billID}','{$payID}','{$cFor}','{$accountNumber}','{$accName}','{$accBank}','{$codeTafzili}','{$nationalCode}','{$MaturityCash}','{$MaturityCheck}',{$RequestSource},'{$RequestNumbers}','{$unCode}',{$sendType},{$layer1},{$layer2},'{$cFund}',{$vat_expert_id},'{$ContractNum}','{$CheckNumber}','{$CheckDate}',{$CheckCarcass},'{$DeliveryDate}',{$sabtDarHesabType},'{$CardNumber}',{$layer3},{$accountID},'{$goodLoan}','{$checkOutType}','{$related_project}','{$related_vat}','{$PropertyNumber}')";
          $res = $db->Query($sql);
            if (intval($res) > 0) 
            {
                //*************************************************ذخیره در جدول متا ****************** */
                if(intval($is_drivers_fare)>0 && intval($is_drivers_fare_type)>0){
                    $drivers_fare_type="";
                    switch($is_drivers_fare_type){
                        case "1":
                            $drivers_fare_type="بارنامه صادره";
                            break;
                        case "2":
                            $drivers_fare_type="بارنامه وارده";
                            break;
                    }
                    $last_row_id=$this->get_table_last_id('pay_comment','RowID','isEnable=1');
                    $insert_meta_pay_comment="INSERT INTO `pay_comment_meta` (`pay_comment_id`,`key`,`value`,`description`)VALUES('{$last_row_id}','is_drivers_fare',{$is_drivers_fare},'اظهار نظر مربوط به بارنامه می باشد'),('{$last_row_id}','is_drivers_fare_type',{$is_drivers_fare_type},'$drivers_fare_type')";
                    $meta_res=$db->Query($insert_meta_pay_comment);
                    if(!$meta_res){
                        return -100;
                    }

                }
                
                //*************************************************ذخیره در جدول متا ****************** */
                //********************************************** */
                if(!empty($formula_row_id))
                {
                 
                    $get_last_row="SELECT `RowID` From `pay_comment` ORDER BY RowID DESC LIMIT 1 ";
                    $result=$db->ArrayQuery($get_last_row);
                
                    $last_id=$result[0]['RowID'];
               
                    $sql_p_update="UPDATE contract_pay_formula SET `status`=2 ,`pid`={$last_id} WHERE RowID={$formula_row_id}";
            
                    $res=$db->Query($sql_p_update);
                }
                //******************************************** */
                $id = $db->InsertrdID();
                $cntFile = count($SFile);
               
                for ($i=0;$i<$cntFile;$i++) {
                    $upload = move_uploaded_file($files["tmp_name"][$i],'../checkCarcass/'.$SFile[$i]);
                    $sql4 = "INSERT INTO `check_carcass` (`pid`,`fileName`) VALUES ({$id},'{$SFile[$i]}')";
                    $db->Query($sql4);
                }
                // if($related_vat==1){
                //     $current_date=$ut->greg_to_jal(date('Y-m-d'));
                //     $current_time=date('H:i:s');
                //     $vat_description="ارسال خودکار اظهارنظرهای مالیات بر ارزش افزوده";
                //     $sql_comment_workflow="INSERT INTO `payment_workflow` (sender,receiver,pid,status,createDate,createTime,description) VALUES
                //     ('{$_SESSION['userid']}','{$vat_expert_id}','${id}',1,'{$current_date}','{$current_time}','{$vat_description}')";
                //     $auto_workflow_vat=$db->Query($sql_comment_workflow);
                //     if(!$auto_workflow_vat){
                //         return false;
                //     }
                // }
                $res_comment=$db->ArrayQuery($sql_comment_update);
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return -1;
        }
    }

    public function get_table_last_id($table_name,$table_Row_id,$where){
        $db=new DBi();
        $sql="SELECT `{$table_Row_id}` from {$table_name} WHERE {$where} ORDER BY `{$table_Row_id}` DESC LIMIT 1";
        $res=$db->ArrayQuery($sql);
        return $res[0][$table_Row_id];
    }
    
	public function editPayComment($cid,$pMethod,$pType,$Unit,$consumerUnit,$Type,$Toward,$totalTransactionAmount,$Amount,$AccNum,$CashSection,$MaturityCash,$NonCashSection,$MaturityCheck,$RequestSource,$RequestNumbers,$Desc,$billID,$payID,$layer1,$layer2,$cFund,$cForv,$code,$ContractNum,$CheckNumber,$CheckDate,$DeliveryDate,$CheckCarcass,$files,$CardNumber,$layer3,$goodLoan,$checkOutType,$related_project,$related_vat,$is_drivers_fare,$is_drivers_fare_type,$PropertyNumber){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $full_access_users_edit_pay_comment=[1,67];// (مدیریت سیستم )کاربران قادر به ویرایش اظهار نظر در هر  مرحله ای 
        $limit_access_users_edit_pay_comment=[14]; //کاربران قادر به ویرایش اظهارنظر    تازمانی که اظهار نظر از گردش خارج از واحد نداشته باشد  (مدیران واحدها)
        $this->set_comment_view_date($cid,'ویرایش اظهارنظر');
        if (!in_array($_SESSION['userid'],$full_access_users_edit_pay_comment) && !in_array($_SESSION['userid'] ,$limit_access_users_edit_pay_comment)) {
            $sqlayer = "SELECT `layer2` FROM `pay_comment` WHERE `RowID`={$cid}";
            $rstlyr = $db->ArrayQuery($sqlayer);
            if (intval($rstlyr[0]['layer2']) == 13 || intval($rstlyr[0]['layer2']) == 14) {  // اگر تنخواه هزینه ای یا مصرفی بود
                $sqFund = "SELECT `RowID` FROM `fund_list` WHERE `pid`={$cid}";
                $rstFund = $db->ArrayQuery($sqFund);
                if (count($rstFund) > 0) {
                    return -10;
                }
            }

            $sqq = "SELECT `uid` FROM `pay_comment` WHERE `RowID`={$cid}";
            $result = $db->ArrayQuery($sqq);
            if (intval($result[0]['uid']) !== intval($_SESSION['userid'])) {
                return -2;
            } else {
                $sql1 = "SELECT `RowID` FROM `payment_workflow` WHERE `pid`={$cid}";
                $rst1 = $db->ArrayQuery($sql1);
                if (count($rst1) > 0) {
                    return -3;
                }
            }
        }
        if(in_array($_SESSION['userid'],$limit_access_users_edit_pay_comment)){
            $sql_reciver="SELECT receiver,unitID FROM `payment_workflow` as p LEFT JOIN users as u on (p.receiver=u.RowID)  WHERE `pid`={$cid} GROUP BY unitID";
            $res=$db->ArrayQuery($sql_reciver);
            if(count($res)==1){
                $user_sql="SELECT unitID from users where RowID={$_SESSION['userid']} AND unitID={$res[0]['unitID']}";
                $user_unit_res=$db->ArrayQuery($user_sql);
                if(count($user_unit_res)==0){
                    return -11;//
                }
            }
            else{
                return -12;//
            }
           // $sql1 = "SELECT `reciver` FROM `payment_workflow` WHERE `pid`={$cid}" ;
        }
        $sqql = "SELECT `uids` FROM `relatedunits` WHERE `RowID`={$Unit}";
        $result1 = $db->ArrayQuery($sqql);

        $uids = explode(',', $result1[0]['uids']);
        if (!in_array($_SESSION['userid'], $uids)) {
            return -4;
        }

        if (strlen(trim($ContractNum)) > 0){
            $fDate = date('Y-m-01');
            $lDate = date("Y-m-t");

            $aql = "SELECT `RowID`,`totalAmount`,`contractType`,`monthlyAmount` FROM `contract` WHERE `number`='{$ContractNum}'";
            $rstaql = $db->ArrayQuery($aql);
            //******************************************************* */
            $pay_row_formula="SELECT * FROM `contract_pay_formula`  where contract_id={$rstaql[0]['RowID']} AND status=1 AND CEO_confirm=1 ORDER BY RowID asc LIMIT 1";
            $p_res=$db->ArrayQuery($pay_row_formula);
            $percent_amount = (!empty($p_res[0]['percentage_increase_allowable_temperature'])?intval($p_res[0]['percentage_increase_allowable_temperature']):0);
            $pey_row_amount = (!empty($p_res[0]['amount_pay_part'])?intval($p_res[0]['amount_pay_part']):0);
            $formula_row_id = $p_res[0]['RowID'];
            $addendum_amount=$pey_row_amount*$percent_amount/100;
            if(count($p_res)>0){
                if($Amount>($pey_row_amount+$addendum_amount)){
                    return -5;
                }
            }
            else
            {
                
                //--------------------اعمال الحاقیه  قرارداد------------------------------
                $addendum_contract_sql="SELECT SUM(`Addendum_price`) as addendum_amount from contract_addendum where contract_id={$rstaql[0]['RowID']} AND `status`=1 AND `addendum_status`=3";
                //error_log($addendum_contract_sql);
                $addendum_res=$db->ArrayQuery($addendum_contract_sql);
                $addendum_amount=$addendum_res[0]['addendum_amount'];
               // $addendum_amount=0;
                //--------------------اعمال الحاقیه  قرارداد------------------------------
                $contractType = intval($rstaql[0]['contractType']);

                $totalSaderShode = 0;
                $totalSaderShode1 = 0;
                if (count($rstaql) > 0) {
                    if ($contractType == 0) {  // عادی باشد
                        $sqll = "SELECT `RowID`,`Amount` FROM `pay_comment` WHERE `contractNumber`='{$ContractNum}'";
                        $rstl = $db->ArrayQuery($sqll);
                    }else
                    {  // ساعتی باشد
                        $sqll = "SELECT `RowID`,`Amount` FROM `pay_comment` WHERE `contractNumber`='{$ContractNum}' AND `cDate` >= '{$fDate}' AND `cDate` <= '{$lDate}'";
                        $rstl = $db->ArrayQuery($sqll);

                        $sql2 = "SELECT `RowID`,`Amount` FROM `pay_comment` WHERE `contractNumber`='{$ContractNum}'";
                        $rst2 = $db->ArrayQuery($sql2);
                        $cnt2 = count($rst2);
                        for ($b = 0; $b < $cnt2; $b++) {
                            if (intval($rst2[$b]['RowID']) == intval($cid)){
                                continue;
                            }
                            $totalSaderShode1 += $rst2[$b]['Amount'];
                        }
                        $albaghi1 = $addendum_amount+$rstaql[0]['totalAmount'] - $totalSaderShode1;
                        //error_log("albaghi1:".$albaghi1);
                        //error_log("totalSaderShode1:".$totalSaderShode1);
                        if (intval($albaghi1) < intval($Amount)){
                            return -5;
                        }
                    }
                    $cntl = count($rstl);
                    for ($j=0;$j<$cntl;$j++){
                        if (intval($rstl[$j]['RowID']) == intval($cid)){
                            continue;
                        }
                        $totalSaderShode += $rstl[$j]['Amount'];
                    }

                    if (intval($rstaql[0]['contractType']) == 0) {
                        $albaghi = $addendum_amount+$rstaql[0]['totalAmount'] - $totalSaderShode;
                        //error_log("albaghi2:".$albaghi);
                    }else{
                        $albaghi =$addendum_amount+ $rstaql[0]['monthlyAmount'] - $totalSaderShode;
                        //error_log("albaghi3:".$albaghi);
                        //error_log("totalSaderShode:".$totalSaderShode);
                    }
                    if (intval($albaghi) < intval($Amount)){
                        return -5;
                    }
                }else{
                    return -6;
                }
            }
        }
        // if (intval($totalTransactionAmount) <= 100000000 ){
        //     $Transactions = 1;
        // }elseif (intval($totalTransactionAmount) <= 1000000000){
        //     $Transactions = 2;
        // }elseif (intval($totalTransactionAmount) <= 5000000000){
        //     $Transactions = 3;
        // }else{
        //     $Transactions = 4;
        // }

        //--------------------------------------------------------
        $ut->fileRecorder($totalTransactionAmount);
        $ut->fileRecorder($totalTransactionAmount);
        if (intval($totalTransactionAmount) <= $this->get_trading_levels('small_purchases')[0] ){
            $Transactions = 1;
        }elseif (intval($totalTransactionAmount) <= $this->get_trading_levels('middel_purchases')[0]){
            $Transactions = 2;
        }elseif (intval($totalTransactionAmount) <= $this->get_trading_levels('major_purchases')[0]){
            $Transactions = 3;
        }else{
            $Transactions = 4;
        }
        //------------------------------------------------------------

        $SFile = array();
        $allowedTypes = ['png','jpg','jpeg','jfif','pdf','PNG','JPG','JPEG','PDF','JFIF'];

        if (isset($files) && !empty($files)) {
            $no_files = count($files['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files["tmp_name"][$i];
                if ($files["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -7;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -8;
                }
                $format = substr($files['name'][$i], strpos($files['name'][$i], ".") + 1);
                if(!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -9;
                }
                $SFile[] = "checkCarcass" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $CashSection = (strlen(trim($CashSection)) > 0 ? $CashSection : 0);
        $NonCashSection = (strlen(trim($NonCashSection)) > 0 ? $NonCashSection : 0);
        $MaturityCash = (strlen(trim($MaturityCash)) > 0 ? $ut->jal_to_greg($MaturityCash) : $MaturityCash);
        $MaturityCheck = (strlen(trim($MaturityCheck)) > 0 ? $ut->jal_to_greg($MaturityCheck) : $MaturityCheck);
        $cFund = (strlen(trim($cFund)) > 0 ? $ut->jal_to_greg($cFund) : $cFund);
        $goodLoan = (strlen(trim($goodLoan)) > 0 ? $ut->jal_to_greg($goodLoan) : $goodLoan);
        $CheckDate = (strlen(trim($CheckDate)) > 0 ? $ut->jal_to_greg($CheckDate) : $CheckDate);
        $DeliveryDate = (strlen(trim($DeliveryDate)) > 0 ? $ut->jal_to_greg($DeliveryDate) : $DeliveryDate);
        $CheckCarcass = (intval($CheckCarcass) == -1 ? 'NULL' : $CheckCarcass);
        $totalAmount = ($CashSection) + ($NonCashSection);

        if (intval($AccNum) != -1){
            $AccID = explode(',',$AccNum);
            $accountID = $AccID[0];
            $query = "SELECT `Name`,`accountNumber`,`bankName`,`code`,`codeMelli` FROM `account` WHERE `RowID`={$AccID[0]}";
            $rst = $db->ArrayQuery($query);
            $cFor = $AccNum;
            $accnum = explode(',',$rst[0]['accountNumber']);
            $position = $AccID[1];
            $accountNumber = $accnum[$position];
            $bank = explode(',',$rst[0]['bankName']);
            $accBank = $bank[$position];
            $codeTafzili = $rst[0]['code'];
            $nationalCode = $rst[0]['codeMelli'];
            $accName = $rst[0]['Name'];
        }else{
            if ($Type != 'پرداخت قبض' && $Type != 'پرداخت جریمه') {
                $sqlID = "SELECT `RowID` FROM `account` WHERE `Name`='{$cForv}'";
                $rstID = $db->ArrayQuery($sqlID);
                $accountID = $rstID[0]['RowID'];
            }else{
                $accountID = 'NULL';
            }

            $accountNumber = '';
            $accBank = '';
            $codeTafzili = '88888';
            $nationalCode = '';
            $accName = '';
            $cFor = '';
        }

        if (intval($pMethod) == 0){  // سهامی
            $sendType = 2;
        }elseif (intval($pMethod) == 1 && intval($pType) == 0){ // فورج نقدی
            $sendType = 0;
        }else{ // فورج چک
            $accountNumber = 'ندارد';
            $codeTafzili = $code;
            $accName = $cForv;
            $sendType = 1;
        }

        $flag = true;
        if ($Type != 'ثبت در حساب بستانکاری طرف مقابل'){
            if (($totalAmount) != ($Amount)){
                $flag = false;
            }
            $sabtDarHesabType = 'NULL';
        }else{
            $sabtDarHesabType = (intval($sendType) == 0 ? 0 : 1);
            $accountNumber = 'ندارد';
            $codeTafzili = $code;
            $accName = $cForv;
            $sendType = 3;
            $CashSection = 0;
            $NonCashSection = 0;
        }

        if (intval($layer1) == 5){  // قبوض شرکتی
            switch (intval($layer2)){
                case 60:  // آب
                    $accName = 'شرکت آب منطقه اي استان خراسان رضوي';
                    $codeTafzili = 400012;
                    $accountNumber = '0';
                    $accBank = 'ندارد';
                    break;
                case 61:  // برق
                    $accName = 'شرکت برق استان خراسان رضوي';
                    $codeTafzili = 400009;
                    $accountNumber = '0';
                    $accBank = 'ندارد';
                    break;
                case 62:  // گاز
                    $accName = 'شرکت گاز استان خراسان رضوي';
                    $codeTafzili = 400010;
                    $accountNumber = '0';
                    $accBank = 'ندارد';
                    break;
                case 63:  // تلفن
                    $accName = 'شرکت مخابرات خراسان رضوي';
                    $codeTafzili = 400011;
                    $accountNumber = '0';
                    $accBank = 'ندارد';
                    break;
            }
        }

        if (intval($layer1) == 50){  // وسائط نقلیه
            switch (intval($layer2)){
                case 51:  // پژو پارس خاکستری 94
                case 52:  // پژو پارس خاکستری 99
                case 53:  // سمند سفید
                case 54:  // سمند نوک مدادی
                case 55:  // کامیونت
                case 56:  // موتور سیکلت
                case 80:  // موتور سیکلت شارژی
                case 85:  // مزدا
                if (intval($layer3) == 223 || intval($layer3) == 224 || intval($layer3) == 225 || intval($layer3) == 226 || intval($layer3) == 227 || intval($layer3) == 228 || intval($layer3) == 229 || intval($layer3) == 230 || intval($layer3) == 299 || intval($layer3) == 300 || intval($layer3) == 301 || intval($layer3) == 302 || intval($layer3) == 303 || intval($layer3) == 304 || intval($layer3) == 305){
                        $accName = 'راهنمايي و رانندگي';
                        $codeTafzili = 161281;
                        $accountNumber = '0';
                        $accBank = 'ندارد';
                    }
                    break;
            }
        }

        if (intval($layer1) == 8) {  // سهامداران
            switch (intval($layer2)){
                case 17:  // سیدرضا رضوی
                    if ($Type == 'پرداخت قبض' || $Type == 'پرداخت جریمه'){
                        $accName = 'رضوي سيد رضا';
                        $codeTafzili = 115004;
                        $accountNumber = '0';
                        $accBank = 'ندارد';
                        break;
                    }
                case 18:  // سید جواد رضوی
                    if ($Type == 'پرداخت قبض' || $Type == 'پرداخت جریمه'){
                        $accName = 'رضوي سيد جواد';
                        $codeTafzili = 115002;
                        $accountNumber = '0';
                        $accBank = 'ندارد';
                        break;
                    }
                case 19:  // سید جمال رضوی
                    if ($Type == 'پرداخت قبض' || $Type == 'پرداخت جریمه'){
                        $accName = 'رضوي سيد جمال';
                        $codeTafzili = 115003;
                        $accountNumber = '0';
                        $accBank = 'ندارد';
                        break;
                    }
                case 20:  // سیدجواد و سیدجمال رضوی باالمناصفه
                    if ($Type == 'پرداخت قبض' || $Type == 'پرداخت جریمه'){
                        $accName = 'سهامداران سيدجواد و سيدجمال رضوي';
                        $codeTafzili = 150020;
                        $accountNumber = '0';
                        $accBank = 'ندارد';
                        break;
                    }
            }
        }

        if (intval($layer1) == 9 && intval($layer2) == 84) {  // مالی و مالیاتی
            if ($Type == 'پرداخت قبض' || $Type == 'پرداخت جریمه') {
                $accName = 'اداره کل امور مالياتي خراسان رضوي';
                $codeTafzili = 160971;
                $accountNumber = '0';
                $accBank = 'ندارد';
            }
        }

        if ($flag) {
            //error_log('update');
            $sql = "UPDATE `pay_comment` SET `type`='{$Type}',`Toward`='{$Toward}',`totalAmount`={$totalTransactionAmount},`Amount`={$Amount},`CashSection`={$CashSection},`NonCashSection`={$NonCashSection},
                   `Transactions`={$Transactions},`Unit`={$Unit},`consumerUnit`={$consumerUnit},`desc`='{$Desc}',`BillingID`='{$billID}',
                   `PaymentID`='{$payID}',`cFor`='{$cFor}',`accNumber`='{$accountNumber}',`accName`='{$accName}',`accBank`='{$accBank}',`codeTafzili`={$codeTafzili},
                   `nationalCode`='{$nationalCode}',`paymentMaturityCash`='{$MaturityCash}',`paymentMaturityCheck`='{$MaturityCheck}',`RequestSource`={$RequestSource},
                   `RequestNumbers`='{$RequestNumbers}',`sendType`={$sendType},`layer1`={$layer1},`layer2`={$layer2},`clearingFundDate`='{$cFund}',
                   `contractNumber`='{$ContractNum}',`checkNumber`='{$CheckNumber}',`checkDate`='{$CheckDate}',`checkCarcass`={$CheckCarcass},`checkDeliveryDate`='{$DeliveryDate}',
                   `sabtDarHesabType`={$sabtDarHesabType},`cardNumber`='{$CardNumber}',`layer3`={$layer3},`accountID`={$accountID},`goodLoan`='{$goodLoan}',`checkOutType`='{$checkOutType}',`related_project`='{$related_project}',`related_VAT`='{$related_vat}',`PropertyNumber`='{$PropertyNumber}' WHERE `RowID`={$cid}";
            $db->Query($sql);
            $res = $db->AffectedRows();
            $res = (($res == -1) ? 0 : 1);
            if (intval($res)) {
                $delete_meta_comment=" DELETE FROM `pay_comment_meta` where (`key`='is_drivers_fare' OR `key`='is_drivers_fare_type') AND `pay_comment_id`='{$cid}'";
                $res=$db->Query($delete_meta_comment);
                if(intval($is_drivers_fare)>0 && intval($is_drivers_fare_type)>0){
                    if(!$res){
                        return -300;
                    }
                    $drivers_fare_type="";
                    switch($is_drivers_fare_type){
                        case "1":
                            $drivers_fare_type="بارنامه صادره";
                            break;
                        case "2":
                            $drivers_fare_type="بارنامه وارده";
                            break;
                    }
                   // $last_row_id=$this->get_table_last_id('pay_comment','RowID','isEnable=1');
                    $insert_meta_pay_comment="INSERT INTO `pay_comment_meta` (`pay_comment_id`,`key`,`value`,`description`)VALUES('{$cid}','is_drivers_fare',{$is_drivers_fare},'اظهار نظر مربوط به بارنامه می باشد'),('{$cid}','is_drivers_fare_type',{$is_drivers_fare_type},'$drivers_fare_type')";
                    $meta_res=$db->Query($insert_meta_pay_comment);
                    if(!$meta_res){
                        return -200;
                    }

                }
                
                //************************************************************************ */
                if(!empty($formula_row_id))
                {
                 
                    $get_last_row="SELECT `RowID` From `pay_comment` ORDER BY RowID DESC LIMIT 1 ";
                    $result=$db->ArrayQuery($get_last_row);
                
                    $last_id=$result[0]['RowID'];
                    //$ut->fileRecorder($get_last_row);
                    //$ut->fileRecorder(2);
                    die();
                    $sql_p_update="UPDATE contract_pay_formula SET `status`=2 ,`pid`={$last_id} WHERE RowID={$formula_row_id}";
            
                    $res=$db->Query($sql_p_update);
                }
                //************************************************************************ */
                $sqlFile = "SELECT `fileName` FROM `check_carcass` WHERE `pid`={$cid}";
                $resf = $db->ArrayQuery($sqlFile);
                if (count($resf) > 0){
                    $sql1 = "DELETE FROM `check_carcass` WHERE `pid`={$cid}";
                    $db->Query($sql1);
                    $affile = $db->AffectedRows();
                    $affile = (($affile == -1) ? 0 : 1);
                    if (intval($affile) > 0){
                        $cntf = count($resf);
                        for ($i = 0; $i < $cntf; $i++) {
                            $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/checkCarcass/' . $resf[$i]['fileName'];
                            unlink($file_to_delete);
                        }
                    }
                }
                $cntFile = count($SFile);
                for ($i=0;$i<$cntFile;$i++) {
                    $upload = move_uploaded_file($files["tmp_name"][$i],'../checkCarcass/'.$SFile[$i]);
                    $sql4 = "INSERT INTO `check_carcass` (`pid`,`fileName`) VALUES ({$cid},'{$SFile[$i]}')";
                    $db->Query($sql4);
                }
                return true;
            } else {
                return false;
            }
        }else{
            return -1;
        }
    }

    public function attachedCommentFileHtm($cid){
        $db = new DBi();
        $sql = "SELECT `RowID`,`fileName`,`fileInfo`,`abilityDelete` FROM `payment_attachment` WHERE `pid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileComment1-tableID">';
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
            if (intval($_SESSION['userid']) !== 1 && intval($_SESSION['userid']) !== 14) {
                $disabled = ($res[$i]['abilityDelete'] == 0 ? '' : 'disabled');
            }else{
                $disabled = '';
            }

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button '.$disabled.' class="btn btn-danger" onclick="deleteAttachCommentFile('.$res[$i]['RowID'].')" ><i class="fas fa-trash"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function attachFileToComment($cid,$info,$files){
        $db = new DBi();
        $cDate = date('Y/m/d');
       // $cTime = date('H:i:s');
        $cTime = $this->current_time;

        $this->set_comment_view_date($cid,'پیوست فایل');
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
                $SFile[] = "attach" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $cnt = count($SFile);
        for ($i=0;$i<$cnt;$i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i],'../attachment/'.$SFile[$i]);
            $sql4 = "INSERT INTO `payment_attachment` (`pid`,`fileName`,`fileInfo`,`createDate`,`createTime`,`uid`) VALUES ({$cid},'{$SFile[$i]}','{$info}','{$cDate}','{$cTime}',{$_SESSION['userid']})";
            $db->Query($sql4);
        }
        return true;
    }

    public function deleteAttachCommentFile($fid){
        $db = new DBi();
        $sms=new SMS();
        $sql = "SELECT `fileName` FROM `payment_attachment` WHERE `RowID`={$fid}";
        $res = $db->ArrayQuery($sql);
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/attachment/' . $res[0]['fileName'];
        $result = unlink($file_to_delete);
        if ($result) {
            $query = "DELETE FROM `payment_attachment` WHERE `RowID`={$fid}";
            $db->Query($query);
            return true;
        }else{
            return false;
        }
    }

    public function sendPayComment($cid,$confType,$receiver,$desc,$PriorityLevel,$isPaid){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        
       //------------------------------------------------------------//Start  for send automatic when  the reciver user is on leave
        $db = new DBi();
		$ut=new Utility();
        $sms=new SMS();
        $auto_send = 0;
        $auto_sql = "SELECT * FROM `auto_send_pay_comment` where `absentReceiver` ={$receiver} AND `status`=1";
        $auto_result = $db->ArrayQuery($auto_sql);
        $RowID = $auto_result[0]['RowID'];
        $current_date = date('Y-m-d');
        $startDate = $auto_result[0]['autoSendStartDate'];
        $days = $auto_result[0]['autoSendDayes'];
        $date_add =  date("Y-m-d",strtotime($startDate. "+ {$days} days"));
        $s_date = strtotime($startDate);
        $e_date = strtotime($date_add);
        $date_diff = intval(round(($e_date-$s_date)/86400));
       
       if($date_diff>=0)
       {
            if($receiver==$auto_result[0]['absentReceiver'])
            {
                $user_query="select lname,fname,gender from users where RowID={$receiver}";
                $user_res= $db->ArrayQuery($user_query);
                if($user_res[0]['gender']==1){
                    $receiver_full_name=" خانم " ;
                }
                if($user_res[0]['gender']==0){
                    $receiver_full_name=" آقای " ;
                }
                $receiver_full_name.=" ".$user_res[0]['fname']." ".$user_res[0]['lname'];
                $desc.=" ( "."ارسال خودکار از کارتابل"." ".$receiver_full_name." ) " ;
                $auto_send = 1;
                $receiver = $auto_result[0]['substituteReceiver'];
				$absent_reciver=$auto_result[0]['absentReceiver'];
				$substitute_receiver=$auto_result[0]['substituteReceiver'];
            }
        }
        else
        {
            if(!empty($RowID)){
                $updateSql="UPDATE auto_send_pay_comment SET `status`=0 WHERE RowID={$RowID}";
                $res= $db->Query($updateSql);
                if(!$res){
                    return false;
                }
            }
        }
       //--------------------------------------------------------- //End for send automatic when  the reciver user is on leave
        

        $sqls = "SELECT `Amount`,`unCode` FROM `pay_comment` WHERE `RowID`={$cid}";
        $rsts = $db->ArrayQuery($sqls);

        $sqq1 = "SELECT SUM(`dAmount`) AS `dsum` FROM `deposit` WHERE `pid`={$cid}";
        $rsq1 = $db->ArrayQuery($sqq1);

        $sqq2 = "SELECT SUM(`check_amount`) AS `csum` FROM `bank_check` WHERE `cid`={$cid}";
        $rsq2 = $db->ArrayQuery($sqq2);

        $sqq3 = "SELECT SUM(`rAmount`) AS `rsum` FROM `return_money` WHERE `pid`={$cid}";
        $rsq3 = $db->ArrayQuery($sqq3);

        $sqq4 = "SELECT SUM(`fAmount`) AS `fsum` FROM `fraction_money` WHERE `cid`={$cid}";
        $rsq4 = $db->ArrayQuery($sqq4);

        $totalAmount = (intval($rsq1[0]['dsum']) + intval($rsq2[0]['csum']) + intval($rsq4[0]['fsum'])) - intval($rsq3[0]['rsum']);

        if (intval($totalAmount) == 0){
            $result = 0;
        }elseif (intval($totalAmount) == intval($rsts[0]['Amount'])){
            $result = 1;
        }elseif(intval($totalAmount) < intval($rsts[0]['Amount'])){
            $result = 2;
        }else{
            $result = 3;
        }

        $sql1s = "UPDATE `pay_comment` SET `paymentStatus`={$result} WHERE `RowID`={$cid}";
        $db->Query($sql1s);
        date_default_timezone_set('Asia/Tehran');
        $cDate = date('Y/m/d');

       //$cTime= date('H:i:s', strtotime('-1 hour'));
       
       $cTime=$this->current_time;
       //$cTime = time();

        $isPaid = ($acm->hasAccess('earlyPaymentAuthorization') ? $isPaid : 0);

        $query1 = "SELECT `RowID` FROM `payment_workflow` WHERE `pid`={$cid}";
        $rst = $db->ArrayQuery($query1);
		$full_access_priority_array=$ut->get_full_access_users(1);
		//$full_access_priority_array=[14,35,36,4,67];//;کاربرانی که قادر به تعیین سطح  در اسرع وقت با عادی اظهار پرداخت می باشند
        $has_access_priority = in_array(intval($_SESSION['userid']),$full_access_priority_array);
       // if (intval($_SESSION['userid']) !== 14) {
        
        if (!$has_access_priority) {
            if (count($rst) > 0 && intval($PriorityLevel) == 1) {
                $res = "فقط شخص صادرکننده می تواند در اسرع وقت را انتخاب نماید !!!";
                $out = "false";
                response($res, $out);
                exit;
            }
        }

        $query = "SELECT `RowID` FROM `payment_workflow` WHERE `sender`={$_SESSION['userid']} AND `status`=1 AND `pid`={$cid}";
        $result = $db->ArrayQuery($query);
        if (count($result) > 0 && intval($confType) == 0){ // قبلا تایید کرده و الان تایید نکرده
            return -1;
        }else{
           // //error_log('test66');
          // $get_last_pw_id_sql="SELECT `RowID` FROM `payment_workflow` WHERE `receiver`={$_SESSION['userid']}  AND `pid`={$cid} order by RowID DESC LIMIT 1";
           $get_last_pw_id_sql="SELECT `RowID` FROM `payment_workflow` WHERE `pid`={$cid} order by RowID DESC LIMIT 1";
           $get_last_pw_id=$db->ArrayQuery($get_last_pw_id_sql);
           $last_done_row_id=$get_last_pw_id[0]['RowID'];
			if($auto_send==1){// ------------------------------------در صورت ارجاع خودکار  دو رکورد در  پاراف ارجاع ها ثبت می شود
				$absent_reciver=$auto_result[0]['absentReceiver'];
				$substitute_receiver=$auto_result[0]['substituteReceiver'];
				$user_query2="select lname,fname,gender from users where RowID={$substitute_receiver}";
				//////$ut->fileRecorder($user_query2);
                $user_res2= $db->ArrayQuery($user_query2);
                if($user_res2[0]['gender']==1){
                    $substitut_full_name=" خانم " ;
                }
                if($user_res2[0]['gender']==0){
                    $substitut_full_name=" آقای " ;
                }
				$substitut_full_name.=$user_res2[0]['fname']." ".$user_res2[0]['lname'];
				
				$autoSendDesc=$auto_result[0]['autoSendDesc']." ( تفویض به " .$substitut_full_name.")";
				$conf_type2=1;
				$sql="INSERT INTO `payment_workflow` (`sender`,`receiver`,`pid`,`status`,`createDate`,`createTime`,`description`,`auto_send`) VALUES ({$_SESSION['userid']},{$absent_reciver},{$cid},{$confType},'{$cDate}','{$cTime}','{$desc}',{$auto_send}),
				({$absent_reciver},{$substitute_receiver},{$cid},{$conf_type2},'{$cDate}','{$cTime}','{$autoSendDesc}',{$auto_send})";
				
			}
			else{
				$sql = "INSERT INTO `payment_workflow` (`sender`,`receiver`,`pid`,`status`,`createDate`,`createTime`,`description`,`auto_send`) VALUES ({$_SESSION['userid']},{$receiver},{$cid},{$confType},'{$cDate}','{$cTime}','{$desc}',{$auto_send})";
            }
			$res = $db->Query($sql);
            if (intval($res) > 0){
                $sql1 = "UPDATE `payment_attachment` SET `abilityDelete`=1 WHERE `pid`={$cid}";
                $db->Query($sql1);

                $sql2 = "UPDATE `pay_comment` SET `lastReceiver`={$receiver},`isPaid`={$isPaid} WHERE `RowID`={$cid}";
                $db->Query($sql2);

                $sql3="UPDATE `payment_workflow` SET `done`=1 where `RowID`={$last_done_row_id} ";
                $res3=$db->Query($sql3);
               // if (intval($_SESSION['userid']) !== 14){
				if (!$has_access_priority) {
                    if (intval($PriorityLevel) == 1){
                        $sql3 = "UPDATE `pay_comment` SET `priorityLevel`={$PriorityLevel} WHERE `RowID`={$cid}";
                        $db->Query($sql3);
                    }
                }else{
                    $sql3 = "UPDATE `pay_comment` SET `priorityLevel`={$PriorityLevel} WHERE `RowID`={$cid}";
                    $db->Query($sql3);
                }
                //send comment sms 
                $users_send_sms=$ut->get_full_access_users(22);
                $send_sms=in_array(intval($receiver),$users_send_sms)?1:0;
                if($send_sms==1){
                    $sql="SELECT `phone` from `users` where RowID={$receiver}";
                    $res=$db->ArrayQuery($sql);
                    $phone=$res[0]['phone'];
                    $un_code = $rsts[0]['unCode'];
                    $sender_name=$ut->get_user_fullname($_SESSION['userid']);
                    $res_s=$sms-> send_by_pattern_api($phone,"l6ob1mton7j89q8",json_encode(array("cyekta"=>COMPANY_ALIAS_NAME.$un_code,"sabauser"=>$sender_name)));
                    $ut->fileRecorder("res_s:::".$res_s);
                   
                }
                

                return true;
            }else{
                return false;
            }
        }
    }

    public function AutoSendPayComment($absentReceiver,$substituteReceiver,$autoSendStartDate,$autoSendDayes,$autoSendDesc)
    {
        $db = new DBi();
        $userId = $_SESSION['userid'];
        $checkAutoSend="Select * FROM auto_send_pay_comment  WHERE  absentReceiver={$substituteReceiver} AND `status` NOT IN (0,9) ";

        $absentRes=$db->ArrayQuery($checkAutoSend);

        if(count($absentRes)>0){
        
            //error_log('absentRes');
            
            return -1;
        }
        $checkAutoSendDuplicut="Select * FROM auto_send_pay_comment  WHERE 
            absentReceiver={$absentReceiver} 
                AND substituteReceiver={$substituteReceiver}
                 AND `status` NOT IN (0,9) 
                  ";
                  //error_log($checkAutoSendDuplicut);
        $absentDuplicateRes=$db->ArrayQuery($checkAutoSendDuplicut);
        if(count($absentDuplicateRes)>0){
            return -2;
        }
        $insertQuery="INSERT INTO auto_send_pay_comment (`absentReceiver`,`substituteReceiver`,`autoSendStartDate`,`autoSendDayes`,`autoSendDesc`,`insertedUser`)VALUES({$absentReceiver},{$substituteReceiver},'{$autoSendStartDate}',{$autoSendDayes},'{$autoSendDesc}',{$userId})";
        
        $insertRes=$db->Query($insertQuery);
        $lastInsertId=$db->InsertrdID();
        if(intval($lastInsertId)){
            
            return 'true';
        }
        else{
            return 'false';
        }
    }

    public function transferPartnersPayCommentCartable($pid_array,$sender,$receiver){
        $acm = new acm();
        $ut=new Utility();
        $sms=new SMS();
         if(!$acm->hasAccess('commentManagement')){
             die("access denied");
             exit;
         } 
         $pids_array=[];
         $ut->fileRecorder('pid_array');
         $ut->fileRecorder($pid_array);
         foreach($pid_array as $key=>$value){
 
             $pids_array[]=$value['pid'];
         }
 
         $pids_array=implode(',',$pids_array);
         $db=new DBi();
         $sql="UPDATE pay_comment Set lastReceiver={$receiver} WHERE lastReceiver={$sender} AND RowID in(".$pids_array.")";
         $res=$db->Query($sql);
         //--------------------------------------------------
        // $get_last_pw_id_sql="SELECT `RowID` FROM `payment_workflow` WHERE `receiver`={$_SESSION['userid']}  AND `pid`={$cid} order by RowID DESC LIMIT 1";
        foreach($pid_array as $key=>$value){
           
         $get_last_pw_id_sql="SELECT `RowID` FROM `payment_workflow` WHERE  `pid`={$value['pid']} order by RowID DESC LIMIT 1";
         $ut->fileRecorder($get_last_pw_id_sql);
         $get_last_pw_id=$db->ArrayQuery($get_last_pw_id_sql);
         $last_done_row_id=$get_last_pw_id[0]['RowID'];
         //--------------------------------------------------

         if($res)
         {
             $result=$db->AffectedRows();
             if($result>0)
             {
                 $description="ارسال شده توسط"." ".$this->getUserFullName($_SESSION['userid'],1);

                 $values="";
                 $dateCreate=date('Y-m-d');

                 $timeCreate= $this->current_time;
                 for($k=0;$k<count($pid_array);$k++){
                     $final_desc=$pid_array[$k]['send_cemment_desc']." ** ".$description;
                     $values.="({$sender},{$receiver},{$pid_array[$k]['pid']},{$pid_array[$k]['confirm_status']},'{$dateCreate}','{$timeCreate}','{$final_desc}'),";
                 }
                 $values=rtrim($values,',');
                 $insertQry = "INSERT INTO `payment_workflow` (`sender`,`receiver`,`pid`,`status`,`createDate`,`createTime`,`description`)VALUES".$values;
                 $insertRes=$db->Query($insertQry);
                 if($insertRes){
                     $update_workflow="UPDATE `payment_workflow` set done=1 where `RowID`={$last_done_row_id}";
                     $res=$db->Query($update_workflow);
                     
                     $final_result=$result;
                 }
                 else{
                     $final_result=false;
                     return $final_res;
                 }
                 
             }

             $users_send_sms=$ut->get_full_access_users(22);
             $send_sms=in_array(intval($receiver),$users_send_sms)?1:0;
             if($send_sms==1){
                 $sql="SELECT `phone` from `users` where RowID={$receiver}";
                 $res=$db->ArrayQuery($sql);
                 $phone=$res[0]['phone'];
                 $un_code = 'ارسال گروهی ';
                 $sender_name=$ut->get_user_fullname($sender);
                 //$res_s=$sms-> send_by_pattern(array($phone),'hyg4w6794bvh1ws',array("cyekta"=>$un_code,"sabauser"=>$sender_name));
                 $res_s=$sms-> send_by_pattern_api($phone,"l6ob1mton7j89q8",json_encode(array("cyekta"=>$un_code,"sabauser"=>$sender_name)));
                 //$res_s=$sms->send_by_pattern(array($phone),'c84uoy332n30uwj',array('uncode'=>$unCode,'sabauser'=>$sender_name));
             }
            }
             return $final_result;
         }
    }

    public function saveSendPayComment($cid,$confType,$receiver,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $cDate = date('Y/m/d');
        $cTime = $this->current_time;
        //$cTime = date('Y-m-d H:i:s', strtotime('-1 hour'));
        

        $query = "SELECT `RowID` FROM `payment_workflow` WHERE `sender`={$_SESSION['userid']} AND `status`=1 AND `pid`={$cid}";
        $result = $db->ArrayQuery($query);

        $sqq = "SELECT `RowID` FROM `payment_workflow` WHERE `sender`={$_SESSION['userid']} AND `temp`=1 AND `pid`={$cid}";
        $rst = $db->ArrayQuery($sqq);

        if (count($result) > 0 && intval($confType) == 0){ // قبلا تایید کرده و الان تایید نکرده
            return -1;
        }elseif(count($rst) > 0){
            return -2;
        }else{

            $sql = "INSERT INTO `payment_workflow` (`sender`,`receiver`,`pid`,`status`,`createDate`,`createTime`,`description`,`temp`) VALUES ({$_SESSION['userid']},{$receiver},{$cid},{$confType},'{$cDate}','{$cTime}','{$desc}',1)";
            $res = $db->Query($sql);
            $lastInsertId=$db->InsertrdID();
            if ($lastInsertId > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function commentTempSendInfo($pwID){
        $db = new DBi();
        $sql = "SELECT * FROM `payment_workflow` WHERE `RowID`={$pwID}";
        $res = $db->ArrayQuery($sql);

        $sqq = "SELECT `Transactions` FROM `pay_comment` WHERE `RowID`={$res[0]['pid']}";
        $rsq = $db->ArrayQuery($sqq);

        $sqlun = "SELECT `unitID` FROM `users` WHERE `RowID`={$_SESSION['userid']}";
        $rstun = $db->ArrayQuery($sqlun);

        $rids = '';
        $rowids = array();
        if (intval($rstun[0]['unitID']) == 9){  // اگر تدارکات بود
            switch (intval($rsq[0]['Transactions'])){
                case 1:  // جزئی
                    $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (20,23)";
                    $rstu = $db->ArrayQuery($sqlu);
                    for ($i=0;$i<count($rstu);$i++){
                        $rowids[] = $rstu[$i]['uids'];
                    }
                    $rids = implode(',',$rowids);
                    $rids .= ',50';
                    break;
                case 2:  // متوسط
                    $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (20,23)";
                    $rstu = $db->ArrayQuery($sqlu);
                    for ($i=0;$i<count($rstu);$i++){
                        $rowids[] = $rstu[$i]['uids'];
                    }
                    $rids = implode(',',$rowids);
                    $rids .= ',20,50';
                    break;
                case 3:
                case 4:  // عمده و کلان
                    $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (20,23)";
                    $rstu = $db->ArrayQuery($sqlu);
                    for ($i=0;$i<count($rstu);$i++){
                        $rowids[] = $rstu[$i]['uids'];
                    }
                    $rids = implode(',',$rowids);
                    $rids .= ',4,20,50';
                    break;
            }
        }
        if (intval($rstun[0]['unitID']) == 1){  // اگر اداری بود
            switch (intval($rsq[0]['Transactions'])){
                case 1:  // جزئی
                    $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (32,22,23)";
                    $rstu = $db->ArrayQuery($sqlu);
                    for ($i=0;$i<count($rstu);$i++){
                        $rowids[] = $rstu[$i]['uids'];
                    }
                    $rids = implode(',',$rowids);
                    $rids .= ',4,50';
                    break;
                case 2:  // متوسط
                    $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (32,22,23)";
                    $rstu = $db->ArrayQuery($sqlu);
                    for ($i=0;$i<count($rstu);$i++){
                        $rowids[] = $rstu[$i]['uids'];
                    }
                    $rids = implode(',',$rowids);
                    $rids .= ',4,20,50';
                    break;
                case 3:
                case 4:  // عمده و کلان
                    $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (32,22,23)";
                    $rstu = $db->ArrayQuery($sqlu);
                    for ($i=0;$i<count($rstu);$i++){
                        $rowids[] = $rstu[$i]['uids'];
                    }
                    $rids = implode(',',$rowids);
                    $rids .= ',4,20,50';
                    break;
            }
        }
        if (intval($rstun[0]['unitID']) == 36 || intval($rstun[0]['unitID']) == 40){  // اگر پشتیبانی یا روابط عمومی بود
            switch (intval($rsq[0]['Transactions'])){
                case 1:  // جزئی
                    $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=23";
                    $rstu = $db->ArrayQuery($sqlu);
                    for ($i=0;$i<count($rstu);$i++){
                        $rowids[] = $rstu[$i]['uids'];
                    }
                    $rids = implode(',',$rowids);
                    $rids .= ',50';
                    break;
                case 2:  // متوسط
                    $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=23";
                    $rstu = $db->ArrayQuery($sqlu);
                    for ($i=0;$i<count($rstu);$i++){
                        $rowids[] = $rstu[$i]['uids'];
                    }
                    $rids = implode(',',$rowids);
                    $rids .= ',50';
                    break;
                case 3:
                case 4:  // عمده و کلان
                    $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=23";
                    $rstu = $db->ArrayQuery($sqlu);
                    for ($i=0;$i<count($rstu);$i++){
                        $rowids[] = $rstu[$i]['uids'];
                    }
                    $rids = implode(',',$rowids);
                    $rids .= ',50';
                    break;
            }
        }
        if (intval($rstun[0]['unitID']) == 22 || intval($rstun[0]['unitID']) == 23){  // اگر بازرگانی فروش یا وصول مطالبات بود
            switch (intval($rsq[0]['Transactions'])){
                case 1:  // جزئی
                    $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (23)";
                    $rstu = $db->ArrayQuery($sqlu);
                    for ($i=0;$i<count($rstu);$i++){
                        $rowids[] = $rstu[$i]['uids'];
                    }
                    $rids = implode(',',$rowids);
                    $rids .= ',20,97';
                    break;
                case 2:  // متوسط
                    $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (23)";
                    $rstu = $db->ArrayQuery($sqlu);
                    for ($i=0;$i<count($rstu);$i++){
                        $rowids[] = $rstu[$i]['uids'];
                    }
                    $rids = implode(',',$rowids);
                    $rids .= ',20,97';
                    break;
                case 3:
                case 4:  // عمده و کلان
                    $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (23)";
                    $rstu = $db->ArrayQuery($sqlu);
                    for ($i=0;$i<count($rstu);$i++){
                        $rowids[] = $rstu[$i]['uids'];
                    }
                    $rids = implode(',',$rowids);
                    $rids .= ',4,20,97';
                    break;
            }
        }
        if (intval($rstun[0]['unitID']) == 8 || intval($rstun[0]['unitID']) == 27 || intval($rstun[0]['unitID']) == 28){  // اگر مدیریت یا فناوری اطلاعات بود
            $sqlu = "SELECT `users`.`RowID` FROM `users` INNER JOIN `access_table` ON (`users`.`RowID`=`access_table`.`user_id`) WHERE `item_id`=38 AND `users`.`IsEnable`=1";
            $rstu = $db->ArrayQuery($sqlu);
            $cntu = count($rstu);
            for ($i=0;$i<$cntu;$i++){
                $rowids[] = $rstu[$i]['RowID'];
            }
            $rids = implode(',',$rowids);
        }

        $squsers = "SELECT `RowID`,`fname`,`lname` FROM `users` WHERE `RowID` IN ($rids) AND `isEnable`=1 AND `RowID`!=1 ORDER BY `lname` ASC";
        $result = $db->ArrayQuery($squsers);

        if (count($res) > 0){
            $res = array("pwID"=>$pwID,"status"=>$res[0]['status'],"receiver"=>$res[0]['receiver'],"description"=>$res[0]['description']);
            $send = array($res,$result);
            return $send;
        }else{
            return false;
        }
    }

    public function editTempSendComment($pwID,$confType,$receiver,$desc){
        $db = new DBi();
        $cDate = date('Y/m/d');
       // $cTime = date('H:i:s');
        $cTime = $this->current_time;

        $query = "SELECT `sender` FROM `payment_workflow` WHERE `RowID`={$pwID}";
        $rst = $db->ArrayQuery($query);
        if (intval($_SESSION['userid']) !== intval($rst[0]['sender'])){
            return -1;
        }
        $sql = "UPDATE `payment_workflow` SET `receiver`={$receiver},`status`={$confType},`description`='{$desc}',`createDate`='{$cDate}',`createTime`='{$cTime}' WHERE `RowID`={$pwID}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function deleteTempSendComment($pwID){
        $db = new DBi();
        $query = "SELECT `sender` FROM `payment_workflow` WHERE `RowID`={$pwID}";
        $rst = $db->ArrayQuery($query);
        if (intval($_SESSION['userid']) !== intval($rst[0]['sender'])){
            return -1;
        }
        $sql = "DELETE FROM `payment_workflow` WHERE `RowID`={$pwID}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function sendTempSendComment($pwID){
        $db = new DBi();
        $query = "SELECT `pid`,`sender`,`receiver` FROM `payment_workflow` WHERE `RowID`={$pwID}";
        $rst = $db->ArrayQuery($query);
        if (intval($_SESSION['userid']) !== intval($rst[0]['sender'])){
            return -1;
        }
        $sql = "UPDATE `payment_workflow` SET `temp`=0 WHERE `RowID`={$pwID}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            $sql1 = "UPDATE `pay_comment` SET `lastReceiver`={$rst[0]['receiver']} WHERE `RowID`={$rst[0]['pid']} ";
            $db->Query($sql1);
            return true;
        }else{
            return false;
        }
    }

    public function attachmentFileCommentHtm($pid,$show){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `fileName`,`fileInfo`,`createDate`,`createTime`,`fname`,`lname` FROM `payment_attachment` LEFT JOIN `users` ON (`payment_attachment`.`uid`=`users`.`RowID`) WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $sql1 = "SELECT `fileName` FROM `payment_receipt` INNER JOIN `deposit` ON (`payment_receipt`.`did`=`deposit`.`RowID`) WHERE `pid`={$pid}";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileComment2-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">ثبت کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زمان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 40%;">نام فایل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);
            $link = ADDR.'attachment/'.$res[$i]['fileName'];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createDate'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';

        if ($show == 1) {
            $htm .= '<br><br><br>';
            $iterator = 0;
            $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileComment3-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">شماره فایل</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">دانلود رسید پرداخت</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';
            for ($i = 0; $i < $cnt1; $i++) {
                $iterator++;
                $fName = 'رسید شماره ' . $iterator;
                $link = ADDR . 'paymentReceipt/' . $res1[$i]['fileName'];
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $fName . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
                $htm .= '</tr>';
            }
            $htm .= '</tbody>';
            $htm .= '</table>';
        }
        $this->set_comment_view_date ($pid,'مستندات');
        return $htm;
    }

    public function commentWorkflowHtm($pid,$meta_data=""){
        
        $db = new DBi();
        $ut = new Utility();
		$acm = new acm();
       
        $this->set_comment_view_date($pid,'ارجاع اظهارنظر');
       $ut->fileRecorder('55555555555555555');
        $sqq = "SELECT `Transactions`,`checkOutType`,`related_vat` FROM `pay_comment` WHERE `RowID`={$pid}";
        $rsq = $db->ArrayQuery($sqq);
        $html_seen_sign='<img  width="25" height="auto" src="data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAAQAAAACACAYAAADktbcKAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAB3RJTUUH5goNCQAl8MZ9iwAAAAZiS0dEAAAAAAAA+UO7fwAAENJJREFUeNrtnWmMnlUVx/vOdKbTMl0obYGu0wJVRBM1LmjciChLt+f+z/M+9xQxMTFuiUTxi4iAG9FoDIKighQUjBoVE437Cp9AoKUL7XRKaUtlX1oodKUzjB96nmTETpnOc++Z973vOcn7od86J+d/nnvPPed3xo0zMxsjyzKuEXEXkJ/q3Mr3A/wVgG8D+CtH/p2fSsRdWZbVzFvDG8A1gLuAlQsBvojIXw3wNQBf4px/j3N8MhF35jmbH80aw4h8l3P+TCL+KRHvJeLBo/z2EfGtztXnExWd5rVXJtB6zbmiC+A3EvHtw/hwEOBdRPxlIl7oXH18lmXmPLMx/WJNJfIfAfwGIu4fLnDl1w/41UR+OcDd5r2hX31/MuA/QeR3vIoPy99fgeKDQH2CedBsrAJ3OsCXE/FjIwza8rcN4I9mWb3TfMjjAJ5DxNcepw8HifxOIj4b4HaLRjPtwJ0J8GVE/MjxBy4PEvEdQPFm8yPPJuLrRunDQSL+rXN+bpbVrSZgpha0s0T8/6kQuC8Q+W8DfnoL+3EOEX+ngg+lLuA/BRQdFplmGkF7MsCfJ+KdVQOXiO8C/Htb1I/zRfyHq/vRryHykyw6zbTE/3AA8Q8S8WYin7egH3vk2H8ghB8Bf8A5PtUi1Cz2sT+k+AeJ+HkivrTF/LiIiL9LxC8G9OMg4M+xKDWLFbQz5M6/M2TQyu+rLeTH04j4e5L4AvvRO4tUsxhBeyLAX6hY8Bvu9xwRf7ZF/Hg6EV9PxLsj+HGQiN9h0WoWOminyDv/o5GCtpfIUwuJf1ccP/oDAJ9iEWsWMmgnAXwFET8eSfyDRHwnkPaXS479EcXPg0T+PnsFMAsZtOMBvpKIn4wo/l0yMDS5Be78uyOKfz/gVwJ+vEWuWajAvYqIn40o/sNE/rfO8eKEfbhQxP9cRD8OEvlbAD8zy9gC1yxI4H4x7heLB4l4A8BLsyzNHnaAF8hT356YfgS4F+A3Ar7NItcsROBeEfeuyoNE3AdwBvCERH04TwZ79sUVv38YKC50zgaqzMJ9+WOLfxvAywEen6gPZxPxNUR8KLL4dwLFUudogvEAzEIU/K6KfOx/mYi3A3wBkB7JRig+5UjvQEw/Av4hoFiSZa7DxG9WNXAnSbX/WQXxn5+oD2ujn+c/rt8A4PuAYkWWZTb5Z1Y5cKfInf/JyIFr4q/+6wf8RqAgE79ZiMA9UTr8HjfxN7z4DwN+A1B4E79ZiMCdIb39j0YO3K0AL0nYj1VJPiPtl1gHFCuzLLNqv1nloA1B8hlRjz/AKwBuS1j8sb/8LxH5tUDxIRO/WYigDUnyebUmHwdwR6J+nCckn4HIX/61QHGxid8spPgfjiz++wEGwF2J+rFHvvwHTfxmzXTs1xD/aoBzgCcl6seF0t67N7L41wPFRSZ+sxBBOzMiyWfo714R/wmJ+rGc6tsT+anvAaBgE79ZiKCdrlTwuwfgeqqbfgTmEXuqbwDwvUBRN/GbhQjaqfLO/4iS+CcnLP7rY89IAH4LUDh75zcLEbQnyGDPYwrHfhN/dfHvAIqlJn6zEEHbqUDyGVrwS/XYf5qS+HeK+I3mYxYkcL9ExM8oPPWlXPBbpEHykXn+pc7B7vxmQQJXi+SDhJ/6enRIPv5hoFhi8/xmoQJXg+TTKx1+qTb5zJfe/r064s+7TPxmob78uxQGe1Yk3N5bbuk9YCQfs2YJ2nYp+O1SGOldkuJgD8DlYM93iLg/Mslne3nnN/GbVQ3cLsF47TKSz6h9WM7zX6dA8tkCFMus2m8WInAny53/KRN/ZfFrkHw2AQXsnd8sROAayad5xH9YevsLE79ZiMA9SYnksw3gCxL2o4r4ZarPSD5mQYJWi+TTJ9z+WqJ+PFXhzm8kH7OgQatF8nlANvakurRjrizteFlB/AbzMAsq/tgwj7XS4Zfquq4Fcuw/ZCQfMxP///7WAEwAT0zUjwvl2L+P4tN77dhvFiRoS5JPbPHf1wKDPd8l4hciP/VtsIKfWaigPUkR41VPfKRXg+SzSZ76TPxmlYN2mjz1Gcmnmh9LmMfuyO29fbauyyxU0HbLYM+jRvIJIv7YMI9tQLHcxG8WImgnSHvvEwp3/pTFbyQfs6YL2jYZ7HlaieST6p2/JPns1iD5ZBnsy28WJHA1YB7r5akvdZLP80byMWumwNWAeWxqEZLPi0byMTPx/+/vQWnv7UzUh3MF5rHfvvxmzRK0NSXxbwd4GcDtCfqwHOy55kgXng6918RvVjVwJwi6e3dkmMc2I/kEafJ50Kr9ZqECt1sYfk8byaey+DVIPr22rsssVOBOk2P/EwowDxN/GJJP3cRvFiJwS5JP7F19OwA+L1EfapN82MRvFiJwy6m+2L39WwBenrAfNUg+5UjvRTbYYxYiaE9WmurbKE997Yn6sST5GMbLrKnEr4HxWickn1Tf+efLO/9hI/mYNZv4jeRTzY89cuzfbySfAJZl3Omcnw3wmwA+y7liWpZlbSbZoEE7S0n8Jckn1d7+kuTzogLJJ907f5ZlbYDvAfjjRPxtIv4JEf+ZiH9HxD8A/NfkKzLV5Fs5aGcYySeIH0uSz/ORm3w2AoVPVvxExSSgOB/wvzjGE9RLRNxL5L/lXHFWltXbTcqjCtoT5anvP0byqeRHLZLPZqDIk33qI/JTiPxniHjNCAsoe4j4HwBfmCoXPmLQTpZ1XUbyCSP+2DCPrUCxIlnx5zl35DmfN8q35w2yCrpm0h5R0E6Uef7HjeRT+divRfJZkqz4syyrOVefB/CdFRy1GeAPmrxfNWjbheTzlEK130g+wUg+riPhoPQdgP90gA0ofQB/wGR+zMC9koifVXjnp4S5/Qul2v+czfOHO5L+PdBUmSWB4f2sMc+/UZp8Un3nXyDv/C8oiT99kg/guwEf6qs0YEngqIGrwfDrk/beVHf1zZXBnn0m/oDmHL8u8PbT8iRwrklf7cu/XVZ0p7qltyT5HFIo+C1tKYyXJIBY02YXpDpwMoKg7ZCC324NmEeKrzCvIPm8HPmdf+uRar/raCmMFxFPluaeWHfSpakOnhwjcCdJwe8ZI/lUFn/sef4BafJZ0ZLz/BKsd0auSi9LFTF9FH9OkWP/k0byaXjx90t7b96yMA8i30nkrybi/oiOXg3wilQHUYYE7nTp8HtM4c6fqvjHKWK8Nkhvf+uSfLIsqwH1BUR+nUJPepbw+/QMpS29WwFemnASnW0kH/VTQNFJ5EmhSeXuFJOAjPRepjDY0ysnqbZExT9HYB5G8hmDq8BJRP7a2OuSiPguWTvVnUjQapF81ovfOhIVf0ny6Y/85TeSz3BXAefqiwB/c+xOqyFJ4IRExB8b5nG/tPemuquvJPkcMJLP2CaBNqB+BuBvknHf2Ekga9bCoCLJZ3WLkHz2KqC77c4/siRQvBbwP1JIAv+WO+3EJgtabZJPqoXTkuSzJ/JT3wPC7TfxH0cSOBPwNyokgXuljXVCkwTtdGWST6ojvSXM47nITT69trGn8ZPAGmkW6mjwoJ0i7/yPKInfSD7VevsftF194a4DLyhUuZc06hOXdEx+UaHJxzBeQWEeJv4QSWAx4FcpJIHeRuxwk8GeKxXae1cnTvIp7/xa4jdWZcDXgdMB/2OFPoGGGyWWqb5nIv/da1uA5PM9PZIPrOAXOgk4V+8B/G2Rn2waiiwkx/7dkcW/IXGST4889e3REX8LzfMrJ4Gac/lcwP888vqlhkgCSiSfzdIUlSrJZ54M9uzVEX/eZeKPnwRmAf7XRHxQIQmcO0aBq0HyeUj6IFJt750t7b0HI4u/9Ug+Y5wExmUZpgL+dqUkcL7W64Cgu6+MLP4S5nFhiL8ry+rjgKLduWKmc8X7ZK3bzUS8CuCPOeff5hxPA4r2LKtr+LAm4r9WOJExST7bjhT80GHi108E3XISOKDwOrAk9pdSCMlXRZ6KDEryIfJtzhUzgeKTRP4YnYl+J1B83LliBpGvRRb/HIWR3gHA9wHFcqv2N0YSOKjQJ7As1l1Z1nVdEXlpR3DxE/nFRP4mwO8bwVF5H5G/kYgXxzhRKZN8NgEF2Tt/4ySB3yicBNZI2/DEwIF7onT4xV7XFUz8ec41Ip5P5P9w/Hdm/ifAbwiZBBTFX5J8ChN/A5lzmA74X8XmtkunXDC82BCSz6MKBb8LQ9VggPokIr5s9IWzI0kgYAJQEb+M9K60wZ4GLAw6l58C+J/FfvKRKcLKZCFFks/mkCQf5+o1oDgbqNacBPC/AH59oGp/bPEbyacJkoAsGvW3NjpZSJHksyE0yQfwbYD/ZpgnNP4rwG+qIP55srRjQEH8RvJpkiSwEPC3NCpZSJHks1Y6/IIWLom4nYj/Eq6gxn8G+K2jEP8C+fIfinzsN/E3WRIoyUKrFMlCJzSY+FdLb3/w9l4i3x64YHmIiP8I8NnHIf5yS+++yOJfb8f+pk0CxWuUyEJ3jwQvBvBMRZJPHmuwh4jHR/g/HyDiPwD8zhGIX5PkYwW/Jk8Cr1WCitwjhbauYYL2JCXxRyf5yBVgR4T/+34i/j3A7xqB+GOTfDbJU5+JP4EkoEUWWi3NQp2vCNppSks7VEg+RxqA+JeR/oZ9kgTefRTxlzCP3ZHbe7cABeydP72TgMZ1YJ0sJG2XoO1OjeQDcBsRXx7xb/m/JKBI8tkOFMtM/OnWBDTIQhtlNfkEGex5QuHkoQbwlP15ZwLcp5AE3iXHfg3x7zSST2u8Dtyi0CewhYi/T8RPKyztyLVJPs5xB8AXE/n9kZPAHUT8p9hQlBLjZSSfFkgC0idwq0LHYOwks16e+ibp+5HHATxDnuJi/o2HIr/zG8mnBZNA2TH4M4XZgZgjym4s13UB3CbNON9vUh8ayae1k0B+igwQHWiywN3aKCQfmcJbQMQ/aELxG8mnxZNAOUX4GwWeQMiR3qWNtL9AksB8SQIvN4n4d5R3fhO/JQItslBVmMe2RtxbMCQRzJNq/aEG9uOAvPNbtd/sqEngYIOKf3sji39IEuiRk0Aj1lb6ZVefresyGzYJ3N6ASaApxD8kCSwi4h8q9FscL8nnAVvUafYqSQBT5SSw38RfKQmcTsQ3EPHzjSB+meqzFd1mIykM5rNk+cjeMQ7cPmEQ1prRlwCfQcQ3xl7HNcJ5/otM/GYjTQLlBqLbFJp5jtVOnAHc1IUqgBePYRIwko9ZlSRQ75GFpNp32XUxSD4tlgSM5GNWOQmUW4lXKSaBNbFIPg2QBG5QmMYcSu81ko9ZiCRQLFYaJb5vLAZ7lGsCN0S+VvULt99IPmZBk0BsstC9miO9Y5gETpcnwv2Rmnw2AoU38ZvFSAKxyEL3aME8GiQJLJIBopcCk3z6bF2XmUYSCHkduLeVxD8kCSyQtuFQvf0PAcUKE7+Z1nXgpgCFwftaUfxDksBcuQ6EmOpbYuI3U0wC9TMAf3OFgtYaKfh1t7Ivq/IESpJPljkTv5luEnCuvgjwPxlFx+C6sSL5NGACqI02CRjJx2ysk0AN8LMAvnSE230PAvw3Ij4ntXf+ikmgjYjnEPHlAD87wgRwO+Df4lxu8/xmYx7AE50r3g74vxxjFn4XEX/DufrcPGebQ3+F5TnXgGIyUJxLxH87xlf/EcBf4pyf3UhQFLOWPw3U2wA/0Tl+TZ7zeQBfTeSvB/zn8pzfR8TdRNyZZVnNvDXsiWocUG8n8hPznE8j4g8T+a8f2f7rLwH8u53jmc4VHVlWNz8mYP8FKRwer1AweCYAAAAldEVYdGRhdGU6Y3JlYXRlADIwMjItMTAtMTNUMDk6MDA6MzcrMDA6MDAleNgAAAAAJXRFWHRkYXRlOm1vZGlmeQAyMDIyLTEwLTEzVDA5OjAwOjM3KzAwOjAwVCVgvAAAAABJRU5ErkJggg==">';
		$html_thick="&#x2713";
        ////$ut->fileRecorder(intval($rstun[0]['unitID']));
        //-------------------------------------------------------------
        // if($rsq[0]['related_vat']==1){
		// 	$vat_expert_id=$ut->get_full_access_users(8)[0];
		// 	$sql_send="SELECT * from payment_workflow where pid={$pid} AND `receiver`={$vat_expert_id}";
		// 	//$ut->fileRecorder($sql_send);
		// 	$send_vat_res=$db->ArrayQuery($sql_send);
		// 	if(count($send_vat_res)==0){
        //         $current_date=date('Y-m-d');
        //         $current_time=date('H:i:s');
        //         $vat_description="ارسال خودکار اظهارنظرهای مالیات بر ارزش افزوده";
        //         $sql_comment_workflow="INSERT INTO `payment_workflow` (sender,receiver,pid,status,createDate,createTime,description) VALUES
        //         ('{$_SESSION['userid']}','{$vat_expert_id}','${pid}',1,'{$current_date}','{$current_time}','{$vat_description}')";
        //         //$ut->fileRecorder($sql_comment_workflow);
		// 		$auto_workflow_vat=$db->Query($sql_comment_workflow);
        //         if(!$auto_workflow_vat){
        //             return false;
        //         }
            
		// 		return 11;
		// 	}
		// }
        //-------------------------------------------------------------
        $sqlun = "SELECT `unitID` FROM `users` WHERE `RowID`={$_SESSION['userid']}";
        $rstun = $db->ArrayQuery($sqlun);
        $rids = '';
        $rowids = array();
        error_log(date("4:" ."H:i:s"));
        if(!empty($meta_data))
        { //
            $ut->fileRecorder('after2_check_barnameh:'.$meta_data);
            switch($meta_data){
                case "is_drivers_fare_type":
                    $get_mata_sql = "SELECT p.`RowID`,p.`sendType`,pm.`value` FROM `pay_comment` as p LEFT JOIN `pay_comment_meta` AS pm ON p.`RowID`=pm.`pay_comment_id`  WHERE pm.`key`='{$meta_data}' AND p.`RowID`={$pid}";
                    $get_meta_res = $db->ArrayQuery($get_mata_sql);
                   
                    if($get_meta_res[0]['value'] == 1){//بارنامه صادره می باشد
                       if($get_meta_res[0]['sendType']==0 || $get_meta_res[0]['sendType']==1){
                            $rowids=$ut->get_full_access_users(16);
                       }
                       elseif($get_meta_res[0]['sendType']==2){
                           
                            $rowids=$ut->get_full_access_users(17);
                       }
                    }
                    if($get_meta_res[0]['value'] == 2){//   بارنامه وارده می باشد
                        $rowids = $ut->get_full_access_users(15);
                    }
                    $related_accunting_dep_users_to_barname=$ut->get_full_access_users(19);
                    if(in_array($_SESSION['userid'],$related_accunting_dep_users_to_barname)){
                        $kesho_sql = "SELECT at.`user_id` from `access_table` AS at LEFT JOIN `users` as u ON at.`user_id`=u.`RowID`  WHERE at.`item_id`=41  AND u.`IsEnable`=1";
                        $res_kesho = $db->ArrayQuery($kesho_sql);
                        $user_kesho=[];
                        foreach($res_kesho as $key=>$value){
                            $user_kesho[]=$value['user_id'];
                        }
                        unset($res_kesho[array_search(1,$res_kesho)]);
                        foreach($user_kesho as $userid){
                            $rowids[]=$userid;
                        }
                    }
                    break;
            }
           // $rowids[]=149;
            $user_array_key = array_search($_SESSION['userid'],$rowids);
           // unset($rowids[$user_array_key]);
            $rids = implode(',',$rowids);
        }
        else
        {
           
            if (intval($rstun[0]['unitID']) == 9 || intval($rstun[0]['unitID']) == 37){  // اگر تدارکات یا انبار محصول بود
                //////$ut->fileRecorder(intval($rsq[0]['Transactions']));
              
                switch (intval($rsq[0]['Transactions'])){
                    case 1:  // جزئی
                        $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (20,23)";  // تدارکات - مالی
                        $rstu = $db->ArrayQuery($sqlu);
                        for ($i=0;$i<count($rstu);$i++){
                            $rowids[] = $rstu[$i]['uids'];
                        }
                        $rids = implode(',',$rowids);
                        $rids .= ',20,97';
                        //////$ut->fileRecorder($rids);
                        break;
                    case 2:  // متوسط
                        $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (20,23)";
                        $rstu = $db->ArrayQuery($sqlu);
                        for ($i=0;$i<count($rstu);$i++){
                            $rowids[] = $rstu[$i]['uids'];
                        }
                        $rids = implode(',',$rowids);
                        $rids .= ',20,97';
                        break;
                    case 3:
                    case 4:  // عمده و کلان
                        $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (20,23)";
                        $rstu = $db->ArrayQuery($sqlu);
                        for ($i=0;$i<count($rstu);$i++){
                            $rowids[] = $rstu[$i]['uids'];
                        }
                        $rids = implode(',',$rowids);
                        $rids .= ',4,20,97';
                        break;
                        
                }
            }
            if (intval($rstun[0]['unitID']) == 1){  // اگر اداری بود
           
                switch (intval($rsq[0]['Transactions'])){
                    case 1:  // جزئی
                        $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (32,22,23,39)";  // پشتیبانی - اداری - مالی
                        $rstu = $db->ArrayQuery($sqlu);
                        for ($i=0;$i<count($rstu);$i++){
                            $rowids[] = $rstu[$i]['uids'];
                        }
                        $rids = implode(',',$rowids);
                        $rids .= ',20,4,97';
                        break;
                    case 2:  // متوسط
                        $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (32,22,23,39)";
                        $rstu = $db->ArrayQuery($sqlu);
                        for ($i=0;$i<count($rstu);$i++){
                            $rowids[] = $rstu[$i]['uids'];
                        }
                        $rids = implode(',',$rowids);
                        $rids .= ',4,20,97';
                        break;
                    case 3:
                    case 4:  // عمده و کلان
                        $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (32,22,23,39)";
                        $rstu = $db->ArrayQuery($sqlu);
                        for ($i=0;$i<count($rstu);$i++){
                            $rowids[] = $rstu[$i]['uids'];
                        }
                        $rids = implode(',',$rowids);
                        $rids .= ',4,20,97';
                        break;
                }
            }
            if (intval($rstun[0]['unitID']) == 36 || intval($rstun[0]['unitID']) == 24 )
            {  // اگر پشتیبانی یا حقوقی بود
              
                switch (intval($rsq[0]['Transactions'])){
                    case 1:  // جزئی
                        $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=23";
                        $rstu = $db->ArrayQuery($sqlu);
                        for ($i=0;$i<count($rstu);$i++){
                            $rowids[] = $rstu[$i]['uids'];
                        }
                        $rids = implode(',',$rowids);
                        $rids .= ',97,4,20';
                        break;
                    case 2:  // متوسط
                        $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=23";
                        $rstu = $db->ArrayQuery($sqlu);
                        for ($i=0;$i<count($rstu);$i++){
                            $rowids[] = $rstu[$i]['uids'];
                        }
                        $rids = implode(',',$rowids);
                        $rids .= ',97,4,20';
                        break;
                    case 3:
                    case 4:  // عمده و کلان
                        $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=23";
                        $rstu = $db->ArrayQuery($sqlu);
                        for ($i=0;$i<count($rstu);$i++){
                            $rowids[] = $rstu[$i]['uids'];
                        }
                        $rids = implode(',',$rowids);
                        $rids .= ',97,4,20';
                        break;
                }
            }
            if (intval($rstun[0]['unitID']) == 22 || intval($rstun[0]['unitID']) == 23 || intval($rstun[0]['unitID']) == 40 || intval($rstun[0]['unitID']) == 43){  // اگر بازرگانی فروش یا وصول مطالبات یا روابط عمومی یا امور عمرانی بود
             
                switch (intval($rsq[0]['Transactions'])){
                    case 1:  // جزئی
                        //$sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (23,24,31,39,40)";
                        $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (24,31,39,40)";
                        $rstu = $db->ArrayQuery($sqlu);
                        for ($i=0;$i<count($rstu);$i++){
                            $rowids[] = $rstu[$i]['uids'];
                        }
                        $rids = implode(',',$rowids);
                        $rids .= ',20,97';
                        break;
                    case 2:  // متوسط
                    // $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (23,24,31,39,40)";
                        $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (24,31,39,40)";
                        $rstu = $db->ArrayQuery($sqlu);
                        for ($i=0;$i<count($rstu);$i++){
                            $rowids[] = $rstu[$i]['uids'];
                        }
                        $rids = implode(',',$rowids);
                        $rids .= ',20,97';
                        break;
                    case 3:
                    case 4:  // عمده و کلان
                        //$sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (23,24,31,39,40)";
                        $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (24,31,39,40)";
                        $rstu = $db->ArrayQuery($sqlu);
                        for ($i=0;$i<count($rstu);$i++){
                            $rowids[] = $rstu[$i]['uids'];
                        }
                        $rids = implode(',',$rowids);
                        $rids .= ',4,20,97';
                        break;
                }
            }
            $users_send_ceo=$ut->get_full_access_users(25);
            if(in_array($_SESSION['userid'],$users_send_ceo)){
                $rids .=',4';
            }

            if (intval($rstun[0]['unitID']) == 14 ){  // اگر آبکاری بود
              
                $sqlu = "SELECT `users`.`RowID` FROM `users` INNER JOIN `access_table` ON (`users`.`RowID`=`access_table`.`user_id`) WHERE `item_id`=38 AND `users`.`IsEnable`=1";
                $rstu = $db->ArrayQuery($sqlu);
                $cntu = count($rstu);
                for ($i=0;$i<$cntu;$i++){
                    $rowids[] = $rstu[$i]['RowID'];
                }
                $rids = implode(',',$rowids);
            }
            if (intval($rstun[0]['unitID']) == 8 || intval($rstun[0]['unitID']) == 27 || intval($rstun[0]['unitID']) == 28){  // اگر مدیریت یا فناوری اطلاعات یا مالی بود
             
                $ut->fileRecorder($_SESSION['userid']);
                $sqlu = "SELECT `users`.`RowID` FROM `users` INNER JOIN `access_table` ON (`users`.`RowID`=`access_table`.`user_id`) WHERE `item_id`=38 AND `users`.`IsEnable`=1";
           
                $rstu = $db->ArrayQuery($sqlu);
                $cntu = count($rstu);
                for ($i=0;$i<$cntu;$i++){
                    $rowids[] = $rstu[$i]['RowID'];
                }
                $rids = implode(',',$rowids);
            }
    }

        $rids_array=explode(",",$rids);

        // $squsers = "SELECT `RowID`,`fname`,`lname`,`unitID` FROM `users` WHERE `RowID` IN ($rids) AND `isEnable`=1 AND `RowID`!=1 ORDER BY `lname` ASC";
        // $result = $db->ArrayQuery($squsers);

        $sql = "SELECT `payment_workflow`.*,`fname`,`lname` FROM `payment_workflow` INNER JOIN `users` ON (`payment_workflow`.`sender`=`users`.`RowID`) WHERE `pid`={$pid} ";
        $res = $db->ArrayQuery($sql);

        $this->set_comment_view_date ($pid,'مشاهده سوابق ارجاع');
       
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileComment4-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">فرستنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">گیرنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">وضعیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ ارسال</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زمان ارسال</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">توضیحات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ویرایش</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">حذف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ارسال</td>';
        if($acm->hasAccess('ViewHistoryCommentManagement')){
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">سوابق مشاهده</td>';
        }
       
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            if($res[$i]['receiver']==$_SESSION['userid']){
                if(!in_array($res[$i]['sender'],$rids_array)){
                    $rids_array[]=$res[$i]['sender'];
                }
                
            }
            $iterator++;
            $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$i]['receiver']}";
            $rst = $db->ArrayQuery($query);
            $status = ($res[$i]['status'] == 0 ? 'عدم تایید' : 'تایید');
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);

            if ($res[$i]['temp'] == 1){
                $button = '<button type="button" class="btn btn-info" onclick="editTempSendComment('.$res[$i]['RowID'].')" ><i class="fas fa-edit"></i></button>';
                $button1 = '<button type="button" class="btn btn-danger" onclick="deleteTempSendComment('.$res[$i]['RowID'].')" ><i class="fas fa-trash"></i></button>';
                $button2 = '<button type="button" class="btn btn-success" onclick="sendTempSendComment('.$res[$i]['RowID'].')" ><i class="fas fa-paper-plane"></i></button>';
            }else{
                $button = '';
                $button1 = '';
                $button2 = '';
            }
           

            if($res[$i]['auto_send']==1){

                $htm .= '<tr class="table-secondary"  title="ارسال خودکار">';
                $style="background:green;color:#fff";
            }
            else{
                $htm .= '<tr class="table-secondary">';
                $style="";
            }

          
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$rst[0]['fname'].' '.$rst[0]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$status.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$res[$i]['createDate'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$res[$i]['description'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$button.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$button1.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$button2.'</td>';
            if($acm->hasAccess('ViewHistoryCommentManagement')){
                if(!empty($res[$i]['view_history']))
                {
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$html_seen_sign.'</td>';
                }
                else{
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$html_tick.'</td>';
                }
            }
            
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        $rids=implode(',',$rids_array);
        $squsers = "SELECT `RowID`,`fname`,`lname`,`unitID` FROM `users` WHERE `RowID` IN ($rids) AND `isEnable`=1 AND `RowID`!=1 ORDER BY `lname` ASC";
        $result = $db->ArrayQuery($squsers);
//**********************************************
		$userID=$_SESSION['userid'];
		$full_access_users_array=$ut->get_full_access_users(2);
		//$full_access_users_array=[42,4,20,72,1,39,59,44,75,43,14,3,67,97];//**کاربرانی که قادر به ارجاع اظهار نظر بدون محدودیت  نوع تسویه حساب می باشند
		$has_access_users_payComment=in_array($userID,$full_access_users_array);
		if(!$has_access_users_payComment)
        {
           
            if($rsq[0]['checkOutType']>0){
				$chk_out_type=intval($rsq[0]['checkOutType']);
				if($chk_out_type==10){//نوع اظهار پیش پرداخت
					foreach($result as $key=>$value){
						//if($value['unitID']==27){
							if($userID !=39){
								if($value['RowID']==39){
									$finalResult[]=$result[$key];
								}
							}
							else
							{
								$finalResult[]=$result[$key];
							}
						//}
						//else{
							 //$finalResult[]=$result[$key];
						//}
					}
				}
				if($chk_out_type==1){//نوع تسویه حساب هزینه ای
					foreach($result as $key=>$value){
						//if($value['unitID']==27){
							if($userID !=59){
								if($value['RowID']==59){
									$finalResult[]=$result[$key];
								}
							}
							else{
								$finalResult[]=$result[$key];
							}
						//}
						//else{
							//$finalResult[]=$result[$key];
						//}
					}
				}
				if($chk_out_type==2){//نوع تسویه حساب  انبار
					foreach($result as $key=>$value){
						//if($value['unitID']==27){
							if($userID !=44){
								if($value['RowID']== 44){
									$finalResult[]=$result[$key];
								}
							}
							else
							{
								$finalResult[]=$result[$key];
							}
						//}
						//else{
						//	$finalResult[]=$result[$key];
						//}
					}
				}
				if($chk_out_type==3){//نوع تسویه حساب  انبار
					foreach($result as $key=>$value){
						
						$finalResult[]=$result[$key];
					}
				}
                if($chk_out_type==4){//مالیات بر ارزش افزوده 
					foreach($result as $key=>$value){
						
						$finalResult[]=$result[$key];
					}
				}
			}
			else{
				$finalResult=$result;
			}
		}
		else{
			$finalResult=$result;
		}
//**********************************************
        //$ut->fileRecorder($finalResult);
        //$ut->fileRecorder('***---------------');
        //$ut->fileRecorder($result);

		$finalResult=$result;
		$send = array($htm,$finalResult);
		return $send;
    }

    public function getTypeNames(){
        $db = new DBi();
        $sql = "SELECT `typeName`,`RowID` FROM `payment_type`";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            return $res;
        }else{
            return false;
        }
    }

    private function getOneLayers(){
        $db = new DBi();
        $sql = "SELECT `layerName`,`RowID` FROM `layers` WHERE `parentID`=-1";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            return $res;
        }else{
            return false;
        }
    }

    private function getFundOneLayers(){
        $db = new DBi();
        $sql = "SELECT `layerName`,`RowID` FROM `layers` WHERE `parentID`=-1 AND `RowID`!=4";
        $res = $db->ArrayQuery($sql);
        if(count($res) > 0){
            return $res;
        }else{
            return false;
        }
    }

    public function getAccountNames(){
        $db = new DBi();
        $sql = "SELECT `Name`,`RowID` FROM `account`";
        $res = $db->ArrayQuery($sql);
        if(count($res)>0){
            return $res;
        }else{
            return false;
        }
    }

    public function getAccountNumbers($cfor){
        $db = new DBi();
        $rowID = explode(',',$cfor);
        $sql = "SELECT `RowID`,`accountNumber`,`bankName` FROM `account` WHERE `RowID`={$rowID[0]}";
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $accNumbers = explode(',',$res[0]['accountNumber']);
            $accBanks=explode(',',$res[0]['bankName']);
            $result[0] = $accNumbers;
            $result[1] = $res[0]['RowID'];
            $result[2] = $accBanks;
            return $result;
        }else{
            return false;
        }
    }

    public function getAccountNumbersWithName($cfor){
        $db = new DBi();
		
		$sql = "SELECT `accountNumber`,`RowID`,`code`,`bankName` FROM `account` WHERE `Name`= '{$cfor}'";
        $res = $db->ArrayQuery($sql);
		if(count($res)==0){
			$sql = "SELECT `accountNumber`,`RowID`,`code`,`bankName` FROM `account` WHERE `Name` LIKE '%{$cfor}%'";
			$res = $db->ArrayQuery($sql);
		}
		
		$sql1 = "SELECT `RowID` FROM `contract` WHERE `accountName`='{$cfor}'";
        $res1 = $db->ArrayQuery($sql1);
		
		if(count($res1)==0){
			$sql1 = "SELECT `RowID` FROM `contract` WHERE `accountName` LIKE '%{$cfor}%'";
			$res1 = $db->ArrayQuery($sql1);
		}

        if(count($res) == 1){
            $accNumbers = explode(',',$res[0]['accountNumber']);
            $bankName = explode(',',$res[0]['bankName']);
            $result[0] = $accNumbers;
            $result[1] = $res[0]['code'];
            $result[2] = $res[0]['RowID'];
            $result[3] = (count($res1) > 0 ? 1 : 0);
            $result[4] = $bankName;
            return $result;
        }else{
            return false;
        }
    }

    public function get_layerNames($layer_id){
        $ut=new Utility();
        $db=new DBi();
        $sql="SELECT `layerName` FROM layers WHERE RowID={$layer_id}";
        $res=$db->ArrayQuery($sql);
        return $res[0]['layerName']? $res[0]['layerName']:'-----';
    }

    public function get_transaction_names($transaction_id){
        // $ut=new Utility();
        // $db=new DBi();
        // $sql="SELECT `layerName` FROM layers WHERE RowID={$layer_id}";
        // $res=$db->ArrayQuery($sql);
        // return $res[0]['layerName']? $res[0]['layerName']:'-----';
        switch ($transaction_id){
            case 1:
                $res = 'جزئی';
                break;
            case 2:
                $res= 'متوسط';
                break;
            case 3:
                $res = 'عمده';
                break;
            case 4:
                $res= 'کلان';
                break;
        }
        return $res;

    }

    public function get_request_resource($resource_id){
        switch ($resource_id){
            case -1:
                $res = '-------------';
                break;
            case 1:
                $res = 'اعلام شفاهی مدیریت محترم عامل';
                break;
            case 2:
                $res= 'اعلام شفاهی معاونت محترم بازرگانی';
                break;
            case 3:
                $res = 'اعلام شفاهی قائم مقام محترم';
                break;
            case 4:
                $res = 'قرارداد';
                break;
        }
        return $res;
    }


    public function OtherInfoCommentHTM($cid){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $other_pay_info="";
        $sql = "SELECT `unCode`,`Amount`,`layer1`,`layer2`,`layer3`,`type`,`totalAmount`,`CashSection`,`paymentMaturityCash`,`BillingID`,`PaymentID`,`Transactions`,`RequestSource`,`RequestNumbers`,`desc`,`related_project` FROM `pay_comment` WHERE `RowID`={$cid}";
        
        $res = $db->ArrayQuery($sql);
        $contract_res = "SELECT c.* , cp.* FROM `contract` AS c  LEFT JOIN `contract_pay_formula` AS cp ON c.`RowID`=cp.`contract_id` WHERE cp.`pid`={$cid}";
        $res_contract = $db->ArrayQuery($contract_res);

        $comment_meta_info_query ="SELECT `key`,`value` FROM `pay_comment_meta` WHERE `pay_comment_id`={$cid}";
        $meta_res = $db->ArrayQuery($comment_meta_info_query);
        $comment_meta_info = $this->convert_meta_data($meta_res);


        $used_percent=0;
        if(count($res_contract)>0){
           
            if($res_contract[0]['amount_pay_part']<$res[0]['Amount']){
               
               $diff=$res[0]['Amount']-$res_contract[0]['amount_pay_part'];
               
               $used_percent = 100*$diff/$res_contract[0]['amount_pay_part'];
              
            }
            else{
                $used_percent="مبلغ اظهارنظر طبق این ردیف پرداخت قرارداد  افزایشی نداشته است";
            }
          //  $used_percent = 100*$diff/$res[0]['Amount'];
               $other_pay_info=
                '<tr>
                    <td colspan="3">
                        <span>مبلغ قابل پرداخت طبق  قرارداد  در این مرحله :&nbsp<strong>'.number_format($res_contract[0]['amount_pay_part']).'</strong> ریال</span>
                    </td>
                </tr>
                <tr>
                    <td  colspan="3">
                        <span>درصد افزایش مجاز ثبت شده   طبق قرارداد  در این مرحله :&nbsp<strong>'.$res_contract[0]['percentage_increase_allowable_temperature'].'</strong> درصد</span>
                    </td>
                </tr>
                <tr>
                    <td  colspan="3">
                        <span> استفاده از درصد افزایش مجازطبق  قرارداد  در این مرحله :&nbsp<strong>'.round($used_percent,2).'</strong> درصد</span>
                    </td>
                </tr>
                <tr>
                    <td  colspan="3">
                        <span>مجموع مبلغ  قابل پرداخت در این اظهارنظر:&nbsp<strong>'.number_format($res[0]['Amount']).'</strong> ریال</span>
                    </td>
                </tr>';
        }
        $iterator = 0;
        $htm = '';
        if($res[0]['related_project']>0){
            $get_project_name="SELECT `project_name` FROM `active_projects` where `project_code`='{$res[0]['related_project']}' ";
            $project_res=$db->ArrayQuery($get_project_name);
            $htm.='<h6 class="project_name_box"><span>پروژه مرتبط با اظهارنظر :</span><sapn>'.$project_res[0]['project_name'].'</span></h6>';
        }
        if($comment_meta_info['is_drivers_fare']>0 && $comment_meta_info['is_drivers_fare_type'] >0){
            if($comment_meta_info['is_drivers_fare_type']==1){//بارنامه صادره می باشد
                $barnemeh_type="صادره";
            }
            if($comment_meta_info['is_drivers_fare_type']==2){//بارنامه وارده می باشد
                $ $barnemeh_type="وارده";
            }

            $htm.='<h6 class="project_name_box"><span>اظهارنظر  مربوط به کرایه حمل و  بارنامه '.$barnemeh_type.' می باشد</span></h6>';
        }
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoComment-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">سایر اطلاعات</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مقدار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        if ($res[0]['type'] == 'پرداخت قبض' || $res[0]['type'] == 'پرداخت جریمه'){
            $final_array=[];
            $infoNames = array('شماره یکتا','سر گروه','زیر گروه','زیرگروه فرعی','نوع','مبلغ مربوط به کل معامله','بخش نقدی','سررسید پرداخت','شناسه قبض','شناسه پرداخت','طبقه معاملات','منبع درخواست','شماره درخواست','توضیحات');
            $final_array[0]=array('fa_title'=>$infoNames[0],'value'=>$res[0]['unCode']);
            $final_array[1]=array('fa_title'=>$infoNames[1],'value'=>$this->get_layerNames($res[0]['layer1']));
            $final_array[2]=array('fa_title'=>$infoNames[2],'value'=>$this->get_layerNames($res[0]['layer2']));
            $final_array[3]=array('fa_title'=>$infoNames[3],'value'=>$this->get_layerNames($res[0]['layer3']));
            $final_array[4]=array('fa_title'=>$infoNames[4],'value'=>$res[0]['type']);
            $final_array[5]=array('fa_title'=>$infoNames[5],'value'=>number_format($res[0]['totalAmount'])." ریال");
            $final_array[6]=array('fa_title'=>$infoNames[6],'value'=>number_format($res[0]['CashSection'])." ریال");
            $final_array[7]=array('fa_title'=>$infoNames[7],'value'=>$ut->greg_to_jal($res[0]['paymentMaturityCash']));
            $final_array[8]=array('fa_title'=>$infoNames[8],'value'=>$res[0]['BillingID']);
            $final_array[9]=array('fa_title'=>$infoNames[9],'value'=>$res[0]['PaymentID']);
            $final_array[10]=array('fa_title'=>$infoNames[10],'value'=>$res[0]['PaymentID']);
            $final_array[10]=array('fa_title'=>$infoNames[10],'value'=>$this->get_transaction_names($res[0]['Transactions']));
            $final_array[11]=array('fa_title'=>$infoNames[11],'value'=>$this->get_request_resource($res[0]['RequestSource']));
            $final_array[12]=array('fa_title'=>$infoNames[12],'value'=>$res[0]['RequestNumbers']);
            $final_array[13]=array('fa_title'=>$infoNames[13],'value'=>$res[0]['desc']);
            // $final_array[4]=array('fa_title'=>$infoNames[4],'value'=>$res[0]['type']);
            // $final_array[4]=array('fa_title'=>$infoNames[4],'value'=>$res[0]['type']);
            // $final_array[4]=array('fa_title'=>$infoNames[4],'value'=>$res[0]['type']);
            // $final_array[0]=array('fa_title'=>$infoNames[0],'value'=>$res[0]['totalAmount']);
            // $final_array[0]=array('fa_title'=>$infoNames[0],'value'=>$res[0]['CashSection']);
            // $final_array[0]=array('fa_title'=>$infoNames[0],'value'=>$res[0]['paymentMaturityCash']);
            
            // for($k=0;$k<count(infoNames);$k++){
                
            // }
            // for ($i=0;$i<14;$i++){
            //     $iterator++;
            //     $keyName = key($res[0]);
               
            //     if ($iterator == 2 || $iterator == 3 || $iterator == 4){
            //         $sql1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]["$keyName"]}";
            //         $rst = $db->ArrayQuery($sql1);
            //         $res[0]["$keyName"] = $rst[0]['layerName'];
            //     }
            //     if ($iterator == 6 || $iterator == 7){
            //         $res[0]["$keyName"] = number_format($res[0]["$keyName"]).' ریال';
            //     }
            //     if ($iterator == 8){
            //         $res[0]["$keyName"] = $ut->greg_to_jal($res[0]["$keyName"]);
            //     }
            //     if ($iterator == 11){

            //         switch ($res[0]["$keyName"]){
            //             case 1:
            //                 $res[0]["$keyName"] = 'جزئی';
            //                 break;
            //             case 2:
            //                 $res[0]["$keyName"] = 'متوسط';
            //                 break;
            //             case 3:
            //                 $res[0]["$keyName"] = 'عمده';
            //                 break;
            //             case 4:
            //                 $res[0]["$keyName"] = 'کلان';
            //                 break;
            //         }
            //     }
            //     if ($iterator == 12){
                    
            //         switch ($res[0]["$keyName"]){
            //             case -1:
            //                 $res[0]["$keyName"] = '-------------';
            //                 break;
            //             case 1:
            //                 $res[0]["$keyName"] = 'اعلام شفاهی مدیریت محترم عامل';
            //                 break;
            //             case 2:
            //                 $res[0]["$keyName"] = 'اعلام شفاهی معاونت محترم بازرگانی';
            //                 break;
            //             case 3:
            //                 $res[0]["$keyName"] = 'اعلام شفاهی قائم مقام محترم';
            //                 break;
            //             case 4:
            //                 $res[0]["$keyName"] = 'قرارداد';
            //                 break;
            //         }
            //     }
            //     $ut->fileRecorder($keyName);
                for($j=0;$j<count($final_array);$j++){
                    $iterator++;
                    $htm .= '<tr class="table-secondary">';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$final_array[$j]['fa_title'].'</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$final_array[$j]['value'].'</td>';
                    $htm .= '</tr>';

                }
                
              //  next($res[0]);
           // }
        }else{
            $sql = "SELECT `unCode`,`layer1`,`layer2`,`layer3`,`clearingFundDate`,`type`,`totalAmount`,`CashSection`,`paymentMaturityCash`,`NonCashSection`,`paymentMaturityCheck`,`Transactions`,`accName`,`accNumber`,`accBank`,`codeTafzili`,`nationalCode`,`RequestSource`,`RequestNumbers`,`contractNumber`,`tick`,`checkNumber`,`checkDate`,`checkCarcass`,`checkDeliveryDate`,`RowID`,`desc`,`cardNumber` FROM `pay_comment` WHERE `pay_comment`.`RowID`={$cid}";
            $res = $db->ArrayQuery($sql);
            $infoNames = array('شماره یکتا','سر گروه','زیر گروه','زیرگروه فرعی','تاریخ تسویه تنخواه','نوع','مبلغ مربوط به کل معامله','بخش نقدی','سررسید پرداخت نقدی','بخش چک','سررسید پرداخت چک','طبقه معاملات','نام طرف حساب','شماره حساب','نام بانک و شعبه','کد تفضیلی','کد ملی','منبع درخواست','شماره درخواست','شماره قرارداد','پرینت گرفته و مستندات پیوست شده است','شماره چک','تاریخ چک','لاشه چک تحویل واحد مالی','تعهد تاریخ تحویل لاشه چک به واحد مالی','رسید تحویل چک','توضیحات','شماره کارت');
            $direction = '';
            for ($i=0;$i<28;$i++){
                $iterator++;
                $keyName = key($res[0]);
                if ($iterator == 2 || $iterator == 3 || $iterator == 4){
                    $sql1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]["$keyName"]}";
                    $rst = $db->ArrayQuery($sql1);
                    $res[0]["$keyName"] = $rst[0]['layerName'];
                }
                if ($iterator == 5){
                    if (strtotime($res[0]["$keyName"]) > 0){
                        $res[0]["$keyName"] = $ut->greg_to_jal($res[0]["$keyName"]);
                    }else{
                        next($res[0]);
                        continue;
                    }
                }
                if ($iterator == 7 || $iterator == 8 || $iterator == 10){
                    $res[0]["$keyName"] = number_format($res[0]["$keyName"]).' ریال';
                }
                if ($iterator == 9 || $iterator == 11){
                    $res[0]["$keyName"] = (strtotime($res[0]["$keyName"]) > 0 ? $ut->greg_to_jal($res[0]["$keyName"]) : '');
                }
                if ($iterator == 12){
                    switch ($res[0]["$keyName"]){
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
                if ($iterator == 18){
                    switch ($res[0]["$keyName"]){
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

                if ($iterator == 21){
                    switch ($res[0]["$keyName"]){
                        case 0:
                            $res[0]["$keyName"] = 'خیر';
                            break;
                        case 1:
                            $res[0]["$keyName"] = 'بلی';
                            break;
                    }
                }

                if ($iterator == 24){
                    switch (intval($res[0]["$keyName"])){
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

                if ($iterator == 23 || $iterator == 25){
                    $res[0]["$keyName"] = (strtotime($res[0]["$keyName"]) > 0 ? $ut->greg_to_jal($res[0]["$keyName"]) : '');
                }
                if ($iterator == 26){
                    $res[0]["$keyName"] = '<button class="btn btn-info" onclick="downloadCheckCarcassFile('.$res[0]["$keyName"].')"><i class="fas fa-download"></i></button>';
                }
                if ($iterator == 28){
                    $direction = 'dir="ltr"';
                }

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;" '.$direction.'>'.$res[0]["$keyName"].'</td>';
                $htm .= '</tr>';
                
                next($res[0]);
            }
        }
        $htm .= $other_pay_info.'</tbody>';
        $htm .= '</table>';
        $this->set_comment_view_date ($cid,'سایر اطلاعات');
        return $htm;
    }

    public function set_comment_view_date ($comment_id,$menu_name,$comment_list=0){
        $db=new DBi();
        $ut=new Utility();
        if($comment_list==1){
            $sql_comment="SELECT p.`RowID`,p.`lastReceiver`,w.* FROM `pay_comment` as p LEFT JOIN `payment_workflow` as w on p.`lastReceiver`=w.`receiver` where w.`receiver`={$_SESSION['userid']} AND p.`isEnable`=1";
            $s_res=$db->ArrayQuery($sql_comment);
            if(count($s_res)>0){
                foreach($s_res as $key=>$value){
                    $current_date=date('Y-m-d H:i:s');
                    if(empty($value['view_history'])){
                        $history_array=[];
                    }
                    else{
                        $history_array=json_decode($value['view_history'],true);
                    }
                    $view_array=array("date"=>$current_date,'desc'=>"$menu_name");
                    $history_array[]=$view_array;
                  //  $history_json=json_encode($history_array,JSON_UNESCAPED_LINE_TERMINATORS, JSON_UNESCAPED_SLASHES,JSON_UNESCAPED_UNICODE);
                    $history_json=json_encode($history_array,JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);
                    //$ut->fileRecorder($history_json);
                    $update_sql="UPDATE `payment_workflow` SET `view_history` ='{$history_json}' WHERE RowID={$value['RowID']}";
                   // $ut->fileRecorder($update_sql);
                    $u_res=$db->Query($update_sql);
                }
            }
        }
        else{
            $seen_sql="SELECT * FROM `payment_workflow` where `receiver`={$_SESSION['userid']} and pid={$comment_id} ORDER BY RowID DESC LIMIT 1";
            $s_res=$db->ArrayQuery($seen_sql);
            if(count($s_res)>0){
                $current_date=date('Y-m-d H:i:s');
                if(empty($s_res[0]['view_history'])){
                    $history_array=[];
                }
                else{
                    $history_array=json_decode($s_res[0]['view_history'],true);
                }
                $view_array=array("date"=>$current_date,'desc'=>$menu_name);
                $history_array[]=$view_array;
                $history_json=json_encode($history_array,JSON_UNESCAPED_LINE_TERMINATORS, JSON_UNESCAPED_SLASHES);
                $update_sql="UPDATE `payment_workflow` SET `view_history` ='{$history_json}' WHERE RowID={$s_res[0]['RowID']}";
             //   $ut->fileRecorder($update_sql);
                $u_res=$db->Query($update_sql);
            }
        }
        
    }

    public function commentChecksHTM($cid)
    {
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `check_amount`,`check_date`,`check_number`,`checkType`,`description`,`fname`,`lname` FROM `bank_check` 
                INNER JOIN `users` ON (`bank_check`.`uid`=`users`.`RowID`) WHERE `cid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="commentChecksHTM-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="color: #ffc107;text-align: center;font-family: dubai-Bold;width: 10%;">نوع چک</td>';
        $htm .= '<td style="color: #ffc107;text-align: center;font-family: dubai-Bold;width: 20%;">کاربر ثبت کننده</td>';
        $htm .= '<td style="color: #ffc107;text-align: center;font-family: dubai-Bold;width: 20%;">مبلغ چک</td>';
        $htm .= '<td style="color: #ffc107;text-align: center;font-family: dubai-Bold;width: 15%;">شماره چک</td>';
        $htm .= '<td style="color: #ffc107;text-align: center;font-family: dubai-Bold;width: 15%;">تاریخ چک</td>';
        $htm .= '<td style="color: #ffc107;text-align: center;font-family: dubai-Bold;width: 20%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $amount = 0;
        for ($i=0;$i<$cnt;$i++){
            $amount += $res[$i]['check_amount'];
            $chDate = (strtotime($res[$i]['check_date']) > 0 ? $ut->greg_to_jal($res[$i]['check_date']) : '');
            if (is_null($res[$i]['checkType'])){
                $ctype = '';
            }elseif ($res[$i]['checkType'] == 0){
                $ctype = 'ابرش';
            }else{
                $ctype = 'مشتری';
            }
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ctype.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res[$i]['check_amount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['check_number'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$chDate.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '</tr>';
        }

        $sql1 = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$cid}";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);
        $deposits = 0;
        for ($j=0;$j<$cnt1;$j++){
            $deposits += $res1[$j]['dAmount'];
        }

        $sql2 = "SELECT `rAmount`,`type`,`accName`,`bankID` FROM `return_money` WHERE `pid`={$cid}";
        $rst = $db->ArrayQuery($sql2);
        $cnt2 = count($rst);
        $rAmount = 0;
        for ($x=0;$x<$cnt2;$x++){
            $rAmount += $rst[$x]['rAmount'];
        }

        $sql3 = "SELECT `fAmount`,`unCode` FROM `fraction_money` WHERE `pid`={$cid}";
        $rstf = $db->ArrayQuery($sql3);
        $cnt3 = count($rstf);
        $fAmount = 0;
        for ($x=0;$x<$cnt3;$x++){
            $fAmount += $rstf[$x]['fAmount'];
        }

        $sql4 = "SELECT `fAmount`,`pid` FROM `fraction_money` WHERE `cid`={$cid}";
        $rstx = $db->ArrayQuery($sql4);
        $cnt4 = count($rstx);
        $fxAmount = 0;
        for ($x=0;$x<$cnt4;$x++){
            $fxAmount += $rstx[$x]['fAmount'];
        }

        $query = "SELECT `CashSection`,`NonCashSection`,`Amount`,`sendType` FROM `pay_comment` WHERE `RowID`={$cid}";
        $result = $db->ArrayQuery($query);
        $remainingNaghdi = (intval($result[0]['CashSection']) > 0 ? $result[0]['CashSection'] - $deposits : 0);
        $remainingCheck = (intval($result[0]['NonCashSection']) > 0 ? $result[0]['NonCashSection'] - $amount : 0);
        $remainingFinal = ($result[0]['Amount'] + $rAmount + $fAmount) - ($amount + $deposits + $fxAmount);
        $colorNaghdi = (intval($remainingNaghdi) < 0 ? 'color: #db0000;' : '');
        $colorCheck = (intval($remainingCheck) < 0 ? 'color: #db0000;' : '');
        $colorFinal = (intval($remainingFinal) < 0 ? 'color: #db0000;' : '');
        $htm .= '<tr class="table-info">';
        $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">جمع پرداختی چک : '.number_format($amount).' ریال</td>';
        $htm .= '<td colspan="3" style="'.$colorCheck.'text-align: center;font-family: dubai-bold;padding: 10px;">مانده چک : <span  dir="ltr">'.number_format($remainingCheck).'</span> ریال</td>';
        $htm .= '</tr>';
        $htm .= '<tr class="table-info">';
        $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">جمع پرداختی نقدی : '.number_format($deposits).' ریال</td>';
        $htm .= '<td colspan="3" style="'.$colorNaghdi.'text-align: center;font-family: dubai-bold;padding: 10px;">مانده نقدی : <span dir="ltr">'.number_format($remainingNaghdi).'</span> ریال</td>';
        $htm .= '</tr>';
        for ($x=0;$x<$cnt2;$x++) {
            $sqlbank = "SELECT `Name` FROM `company_banks` WHERE `RowID`={$rst[$x]['bankID']}";
            $rstb = $db->ArrayQuery($sqlbank);
            $person = ($rst[$x]['type'] == 0 ? $rst[$x]['accName'] : $rstb[0]['Name']);
            $htm .= '<tr class="table-info">';
            $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">مبلغ عودت وجه : ' . number_format($rst[$x]['rAmount']) . ' ریال</td>';
            $htm .= '<td colspan="1"style="text-align: center;font-family: dubai-bold;padding: 10px;">' . ($rst[$x]['type'] == 0 ? 'مشتریان' : 'واریز به بانک') . '</td>';
            $htm .= '<td colspan="2"style="text-align: center;font-family: dubai-bold;padding: 10px;">' . $person . '</td>';
            $htm .= '</tr>';
        }
        for ($x=0;$x<$cnt3;$x++){
            $htm .= '<tr class="table-info">';
            $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">مبلغ کسر از اظهارنظر : ' . number_format($rstf[$x]['fAmount']) . ' ریال</td>';
            $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">کد یکتا اظهارنظر دارای کسری : ' . $rstf[$x]['unCode'] . '</td>';
            $htm .= '</tr>';
        }
        for ($x=0;$x<$cnt4;$x++){
            $sqlCode = "SELECT `unCode` FROM `pay_comment` WHERE `RowID`={$rstx[$x]['pid']}";
            $ress = $db->ArrayQuery($sqlCode);
            $htm .= '<tr class="table-info">';
            $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">مبلغ کسر شده از اظهارنظر : ' . number_format($rstx[$x]['fAmount']) . ' ریال</td>';
            $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">کد یکتا اظهارنظر دارای مازاد : ' . $ress[0]['unCode'] . '</td>';
            $htm .= '</tr>';
        }
        $htm .= '<tr class="table-info">';
        $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">مبلغ کل اظهارنظر : '.number_format($result[0]['Amount']).' ریال</td>';
        $htm .= '<td colspan="3" style="'.$colorFinal.'text-align: center;font-family: dubai-bold;padding: 10px;">مانده نهایی (چک + نقدی) : <span  dir="ltr">'.number_format($remainingFinal).'</span> ریال</td>';
        $htm .= '</tr>';
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getPrintPayCommentHtm($cid){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $this->set_comment_view_date ($pid,'print اظهارنظر');
        //------------------------------------------------------------------watermark---------------------------------------------
        $watermark="";
        $get_payed_sql="select p.Amount,p.CashSection,p.NonCashSection,p.isPaid,d.dAmount,b.check_amount from pay_comment as p 
        LEFT JOIN (select SUM(dAmount) as dAmount,pid from deposit GROUP BY pid )as d on d.pid=p.RowID
        LEFT JOIN (SELECT check_amount,cid from bank_check GROUP BY cid) as b on b.cid=p.RowID 
        where p.RowID=${cid}";
        $watermark_flag=false;
        $p_res=$db->ArrayQuery($get_payed_sql);
        if($p_res[0]['isPaid']==1){
            $watermark_flag=true;
        }
        else{
           // $ut->fileRecorder(2);
            $payed=intval($p_res[0]['dAmount'])+intval($p_res[0]['check_amount']);
            
            if(intval($p_res[0]['Amount']<=$payed)){
                $watermark_flag=true;
            }
            if($watermark_flag==true){
                $watermark='<div style="position:absolute;top:50%;right:1%;z-index:1000;width: 100%;height: auto;justify-content: center;align-items: center"  id="watermark"> <p style="text-align:center;padding:0;border-radius:10px;transform:rotate(-45deg);border:1rem solid rgba(255,0,0,0.2);color:rgba(255,0,0,0.2);font-size: 10rem;">پرداخت شد</p></div>';
            }
        }
        //------------------------------------------------------------------watermark---------------------------------------------
        $sql = "SELECT `pay_comment`.*,`unitName`,`fname`,`lname`,`layerName`,`postJob`,`signature`,account.`codeMelli` FROM `pay_comment`
                INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)
                INNER JOIN `users` ON (`pay_comment`.`uid`=`users`.`RowID`)
                INNER JOIN `layers` ON (`pay_comment`.`layer1`=`layers`.`RowID`)
                left JOIN `account` ON (`pay_comment`.`codeTafzili`=`account`.`code`)
                WHERE `pay_comment`.`RowID`={$cid}";
        $res = $db->ArrayQuery($sql);

        $query = "SELECT `unitName` FROM `relatedunits` WHERE `RowID`={$res[0]['consumerUnit']}";
        $rst = $db->ArrayQuery($query);
        $project_name="";
        if($res[0]['related_project']>0){
            $project_id=$res[0]['related_project'];
            $get_project_name="SELECT projectName From project WHERE RowID={$project_id}";
           
            $project_res=$db->ArrayQuery($get_project_name);
            $project_name=$project_res[0]['projectName'];
            
        }
        if (intval($res[0]['layer2']) > 0) {
            $query1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]['layer2']}";
            $rst1 = $db->ArrayQuery($query1);
            $twoLayer = $rst1[0]['layerName'];
        }else{
            $twoLayer = '&emsp;&emsp;&emsp;&emsp;';
        }
        if (intval($res[0]['layer3']) > 0) {
            $query2 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]['layer3']}";
            $rst2 = $db->ArrayQuery($query2);
            $threeLayer = $rst2[0]['layerName'];
        }else{
            $threeLayer = '&emsp;&emsp;&emsp;&emsp;';
        }

        $sqlsig = "SELECT `sender`,`createDate`,`fname`,`lname`,`postJob`,`signature` FROM `payment_workflow` INNER JOIN `users` ON  (`payment_workflow`.`sender`=`users`.`RowID`) WHERE `status`=1 AND `pid`={$cid} ORDER BY `payment_workflow`.`RowID` ASC";
        $rsig = $db->ArrayQuery($sqlsig);
        $cnt = count($rsig);

        $beginner = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$res[0]['signature'].'">';
        $fbeginner = $res[0]['postJob'].' '.$ut->greg_to_jal($res[0]['cDate']).' '.$res[0]['fname'].' '.$res[0]['lname'];
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

        for ($i=0;$i<$cnt;$i++) {
            if ($rsig[$i]['sender'] == 3 || $rsig[$i]['sender'] == 14 || $rsig[$i]['sender'] == 67 || $rsig[$i]['sender'] == 68){  // حقیقت و مصطفوی، عفت پناه، پور حسین
                $Sarparast = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fSarparast = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 20){  // معاونت بازرگانی
                $Moavenat = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fMoavenat = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 44 || $rsig[$i]['sender'] == 42){  // کارشناس حسابداری
                $Hesabdar = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fHesabdar = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 75 || $rsig[$i]['sender'] == 39){  // رئیس حسابداری
                $RHesabdar = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fRHesabdar = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 27 || $rsig[$i]['sender'] == 72){  // مدیر مالی
                $MHesabdar = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fMHesabdar = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 4){  // مدیر عامل
                $Modiriat = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fModiriat = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
        }


        if (!($res[0]['accNumber'] === '0') || !($res[0]['accNumber'] === '')){
            $accountNumber = explode('-',$res[0]['accNumber']);
            $accountNumber = array_reverse($accountNumber);
            $accountNumber = implode('-',$accountNumber);
            $banks = $res[0]['accBank'];
        }

        $datetostring = substr($ut->greg_to_jal(str_replace('/','-',$res[0]['cDate'])),2);
        $datetostring = explode('/',$datetostring);
        $m = (intval($datetostring[1]) <= 9 ? '0'.$datetostring[1] : $datetostring[1]);
        $d = (intval($datetostring[2]) <= 9 ? '0'.$datetostring[2] : $datetostring[2]);
        $datetostring = [0=>$datetostring[0] , 1=>$m , 2=>$d];
        $datetostring = implode('/',$datetostring);
        $personalCode = [0=>$datetostring,1=>$res[0]['uid']];
        $personalCode = implode('-',$personalCode);

        switch ($res[0]['Transactions']){
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

        if (intval($res[0]['RequestSource']) > 0){
            switch ($res[0]['RequestSource']){
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
        }else{
            $SN = 'شماره درخواست : ';
            $RequestSN = $res[0]['RequestNumbers'];
        }

        if (intval($res[0]['sendType']) == 2){  // سهامی بود
            if (intval($res[0]['CashSection']) > 0 && intval($res[0]['NonCashSection']) > 0){
                $naghd = 'مبلغ نقد : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['CashSection']).'</span> ریال به تاریخ : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCash']).'</span>';
                $checki = 'مبلغ چک : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['NonCashSection']).'</span> ریال به تاریخ (میانگین) : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCheck']).'</span>';
                $mablagh = $naghd.' '.$checki;
                $shaba = 'شماره شبا : <span style="font-size: 25px;font-family: BTitr;">'.$accountNumber.'</span>';
                $AccAndBank = 'نام بانک و صاحب حساب : <span style="font-size: 25px;font-family: BTitr;">'.$banks.'</span>';
            }elseif (intval($res[0]['CashSection']) > 0){
                $mablagh = 'مبلغ نقد : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['CashSection']).'</span> ریال به تاریخ : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCash']).'</span>';
                if ($res[0]['type'] != 'پرداخت قبض' && $res[0]['type'] != 'پرداخت جریمه') {
                    $shaba = 'شماره شبا : <span style="font-size: 25px;font-family: BTitr;">'.$accountNumber.'</span>';
                    $AccAndBank = 'نام بانک و صاحب حساب : <span style="font-size: 25px;font-family: BTitr;">'.$banks.'</span>';
                }else{
                    $shaba = 'شناسه قبض : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['BillingID'].'</span>';
                    $AccAndBank = 'شناسه پرداخت : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['PaymentID'].'</span>';
                }
            }else{
                $mablagh = 'مبلغ چک : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['NonCashSection']).'</span> ریال به تاریخ (میانگین) : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCheck']).'</span>';
                $shaba = 'فاقد بخش نقدی می باشد !!!';
                $AccAndBank = '';
            }
        }elseif (intval($res[0]['sendType']) == 0){  // فورج نقدی
            $mablagh = 'مبلغ نقد : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['CashSection']).'</span> ریال به تاریخ : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCash']).'</span>';
            if ($res[0]['type'] != 'پرداخت قبض' && $res[0]['type'] != 'پرداخت جریمه') {
                $shaba = 'شماره شبا : <span style="font-size: 25px;font-family: BTitr;">'.$accountNumber.'</span>';
                $AccAndBank = 'نام بانک و صاحب حساب : <span style="font-size: 25px;font-family: BTitr;">'.$banks.'</span>';
            }else{
                $shaba = 'شناسه قبض : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['BillingID'].'</span>';
                $AccAndBank = 'شناسه پرداخت : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['PaymentID'].'</span>';
            }
        }elseif (intval($res[0]['sendType']) == 1){  // فورج چکی
            $mablagh = 'مبلغ چک : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['NonCashSection']).'</span> ریال به تاریخ (میانگین) : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCheck']).'</span>';
            $shaba = 'فاقد بخش نقدی می باشد !!!';
            $AccAndBank = '';
        }else{
            $mablagh = 'مبلغ : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['Amount']).'</span> ریال ';
            $shaba = 'فاقد بخش نقدی و غیر نقدی می باشد !!!';
            $AccAndBank = '';
        }

        $srcc = ADDR.'images/abrash.png';
        $htm = '';
        $htm .= '<div class="demoo" style="width: 100%;margin: -85px auto;">';
        $htm.=$watermark;
            // page 1
            $htm .= '<table style="width: 100%;border: 2px solid #000;">';
                $htm .= '<thead>';
                    $htm .= '<tr style="border: 2px solid #000;">';
                    $htm .= '<th style="width: 25%;padding-left: 10px;text-align: center;background-color: #fff;"><img src="'.$srcc.'"></th>';
                    $htm .= '<th style="width: 50%;font-size: 40px;font-family: BTitr;background-color: #ddd;text-align: center;padding: 0 20px;">اظهار نظر و درخواست<br> پرداخت وجه</th>';
                    $htm .= '<th style="width: 25%;font-size: 20px;font-family: BNazanin;text-align: right;padding-right: 30px;background-color: #fff;">کد فرم : F121009<br>کد ثبت : '.$personalCode.'<br>سطح تغييرات:  2</th>';
                    $htm .= '</tr>';
                    $htm .= '<tr style="border-right: 2px solid #000;border-left: 2px solid #000;height: 5px;">';
                    $htm .= '<th colspan="3"></th>';
                    $htm .= '</tr>';
                $htm .= '</thead>';
            $htm .= '</table>';
           
            //************************ شماره یکتا - نوع - تاریخ *************************
            $projectDataHtml=!empty($project_name)?'<tr><td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BTitr;text-align: center;font-weight: bold;" class="pr-3" colspan="3"> نام پروژه : '.$project_name.'</td></tr>':'';
            //error_log('get_project_name inja:'.$projectDataHtml);
            $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
                $htm .= '<thead>';
                    $htm .=$projectDataHtml;
                    $htm .= '<tr>';
                        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: right;font-weight: bold;" class="pr-3">شماره یکتا : '.$res[0]['unCode'].'</td>';
                        $htm .= '<td style="width: 30%;background-color: #fff;font-size: 30px;font-family: BTitr;text-align: center;">'.$res[0]['type'].'</td>';
                        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: left;font-weight: bold;" class="pl-3">تاریخ : '.$ut->greg_to_jal($res[0]['cDate']).'</td>';
                    $htm .= '</tr>';
                  
                    $htm .= '<tr>';
                        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: right;font-weight: bold;" class="pr-3">سرگروه : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['layerName'].'</span></td>';
                        $htm .= '<td style="width: 30%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: center;font-weight: bold;">زیرگروه : <span style="font-size: 25px;font-family: BTitr;">'.$twoLayer.'</span></td>';
                        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: left;font-weight: bold;" class="pl-3">زیرگروه فرعی : <span style="font-size: 25px;font-family: BTitr;">'.$threeLayer.'</span></td>';
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
                    $htm .= '<td style="padding-top: 5px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">فرد صادرکننده : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['fname'].' '.$res[0]['lname'].'</span></td>';
                    $htm .= '<td style="padding-top: 5px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;">طبقه معاملات : <span style="font-size: 25px;font-family: BTitr;">'.$Transactions.'</span></td>';
                    $htm .= '</tr>';
                $htm .= '</thead>';
            $htm .= '</table>';

            $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
                $htm .= '<thead>';
                    $htm .= '<tr>';
                    $htm .= '<td style="padding-top: 3px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">واحد درخواست کننده : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['unitName'].'</span></td>';
                    $htm .= '<td style="padding-top: 3px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;">'.$SN.'<span style="font-size: 25px;font-family: BTitr;">'.$RequestSN.'</span></td>';
                    $htm .= '</tr>';
                $htm .= '</thead>';
            $htm .= '</table>';

            $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
                $htm .= '<thead>';
                    $htm .= '<tr>';
                    $htm .= '<td style="padding: 3px 0;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">واحد مصرف کننده : <span style="font-size: 25px;font-family: BTitr;">'.$rst[0]['unitName'].'</span></td>';
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
               if(!empty($res[0]['contractNumber'])){
                    $htm .= '<tr>';
                        $htm .= '<td colspan="2" style="padding: 5px 0;width: 40%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">شماره قرارداد : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['contractNumber'].'</span></td>';
                    $htm .= '</tr>';
               }
                    $htm .= '<tr>';
                        $htm .= '<td style="padding: 5px 0;width: 60%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">طرف مقابل : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['accName'].' - '.$res[0]['codeTafzili'].'</span></td>';
                    $htm.="</tr>";
                if(!empty($res[0]['codeMelli'])){
                    $htm.="<tr>"; 
                        $htm .= '<td style="padding: 5px 0;width: 40%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">شناسه/کد ملی: <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['codeMelli'].'</span></td>';
                    $htm .= '</tr>';
                }
                   
                $htm .= '</thead>';
            $htm .= '</table>';

            $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
                $htm .= '<thead>';
                    $htm .= '<tr>';
                    $htm .= '<td style="padding-top: 3px;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">بابت : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['Toward'].'</span></td>';
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
                    $htm .= '<td style="padding-top: 3px;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">'.$mablagh.'</td>';
                    $htm .= '</tr>';
                $htm .= '</thead>';
            $htm .= '</table>';

            $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
                $htm .= '<thead>';
                    $htm .= '<tr>';
                    $htm .= '<td style="padding: 3px 0;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">توضیحات : <span style="font-size: 25px;font-family: BTitr;" dir="rtl">'.$res[0]['desc'].'</span></td>';
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
                    $htm .= '<td style="padding: 7px 0;width: 60%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">'.$shaba.'</td>';
                    $htm .= '<td style="padding: 7px 0;width: 40%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: right;">'.$AccAndBank.'</td>';
                    $htm .= '</tr>';
                $htm .= '</thead>';
            $htm .= '</table>';

            //************************ امضا کارشناس و سرپرست و مدیر واحد *************************

            $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
                $htm .= '<thead>';
                    $htm .= '<tr>';
                    $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$beginner.$fbeginner.'</td>';
                    $htm .= '<td style="padding: 10px 0 10px 0;width: 34%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$Sarparast.$fSarparast.'</td>';
                    $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$Moavenat.$fMoavenat.'</td>';
                    $htm .= '</tr>';
                $htm .= '</thead>';
            $htm .= '</table>';

            //************************ امضا حسابداری *************************

            $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
                $htm .= '<thead>';
                    $htm .= '<tr>';
                    $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$Hesabdar.$fHesabdar.'</td>';
                    $htm .= '<td style="padding: 10px 0 10px 0;width: 34%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$RHesabdar.$fRHesabdar.'</td>';
                    $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$MHesabdar.$fMHesabdar.'</td>';
                    $htm .= '</tr>';
                    if (strtotime($res[0]['clearingFundDate']) > 0) {
                        $htm .= '<tr>';
                        $htm .= '<td colspan="3" class="pr-3" style="padding: 10px 0 20px 0;width: 100%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: right;">قابل توجه واحد مالی : تاریخ تسویه تنخواه '.$ut->greg_to_jal($res[0]['clearingFundDate']).' می باشد</td>';
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
            $htm .= '<td class="pl-3" style="padding: 50px 0 20px 0;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: left;">'.$Modiriat.$fModiriat.'</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '</table>';
        $htm .= '</div>';
        $send = array($htm,$res[0]['isPaid']);
        return $send;
    }

    public function commentTypeID($type){
        $acm = new acm();
        $ut=new Utility();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `payment_type` WHERE `typeName`='{$type}'";
        $ut->fileRecorder('sssssqqqqqllll:'.$sql);
        $res = $db->ArrayQuery($sql);
        if (count($res) == 1){
            return $res;
        }else{
            return false;
        }
    }

    public function transferToPayKeshoHTM(){
        $acm = new acm();
        if(!$acm->hasAccess('transferToPayKesho')){
            die("access denied");
            exit;
        }
        $htm = '';
        $htm .= '<div class="col-sm-12 form-check form-check-inline mb-5">';
        $htm .= '<label class="form-check-label" for="transferToPayKeshoForj" style="cursor: pointer;">پشتیبانی فروش</label>';
        $htm .= '<input class="form-check-input ml-5" type="radio" name="transferToPayKeshoForjSahami" id="transferToPayKeshoForj" value="0">';
        $htm .= '<label class="form-check-label" for="transferToPayKeshoSahami" style="cursor: pointer;">واحد مالی</label>';
        $htm .= '<input class="form-check-input" type="radio" name="transferToPayKeshoForjSahami" id="transferToPayKeshoSahami" value="1">';
        $htm .= '</div>';

        $htm .= '<div class="form-group row mr-2 mb-5" id="transferToPayKeshoTick-div">';
        $htm .= '<label class="col-sm-7 col-form-label text-align-form" for="transferToPayKeshoTick" style="cursor: pointer;">پرینت گرفته و مستندات پیوست شده است</label>';
        $htm .= '<div class="form-check form-check-inline col-sm-5" style="margin-right: 0;">';
        $htm .= '<input class="form-check-input" type="checkbox" id="transferToPayKeshoTick" style="margin-left: 10px;">';
        $htm .= '</div>';
        $htm .= '</div>';

        $htm .= '<div class="col-sm-12">';
        $htm .= '<textarea class="form-control" id="transferToPayKeshoDescription" rows="5" placeholder="توضیحات"></textarea>';
        $htm .= '</div>';
        return $htm;
    }

    public function createTransferPayComment($radioValue,$desc,$tick,$pid){
        $acm = new acm();
		$ut=new Utility();
        if(!$acm->hasAccess('transferToPayKesho')){
            die("access denied");
            exit;
        }
        $transfer_flag=0;
        $db = new DBi();
        $receiverDate = date('Y/m/d');
        //$receiverTime = date('H:i:s');
       // $receiverTime=date('H:i:s', strtotime('-1 hour'));
        $receiverTime=$this->current_time;

        $sqq = "SELECT `Transactions`,`Unit`,`uid`,`layer1`,`layer2`,`sendType` FROM `pay_comment` WHERE `RowID`={$pid}";
        $rsq = $db->ArrayQuery($sqq);

        $meta_comment_is_drivers_fare_sql="SELECT * FROM `pay_comment_meta` WHERE (`key`='is_drivers_fare' OR `key`='is_drivers_fare_type') AND `pay_comment_id`={$pid}";
        $meta_res_comment_is_drivers_fare=$db->ArrayQuery($meta_comment_is_drivers_fare_sql);
        $meta_comment_is_drivers_fare_count=count($meta_res_comment_is_drivers_fare);
        $ut->fileRecorder('meta_comment_is_drivers_fare_count:'.$meta_comment_is_drivers_fare_count);
		if($rsq[0]['layer2']==59)//
		{
			$promised_manager_array=[3,72,72];
			$promised_manage_role=["مدیر منابع انسانی","مدیر مالی",'سرپرست واحد حسابداری'];
			$confirm_manages=0;
			for($manage_index=0;$manage_index  < count($promised_manager_array) ;$manage_index++){
				$sqq1 = "SELECT `RowID` FROM `payment_workflow` WHERE `sender`={$promised_manager_array[$manage_index]} AND `status`=1 AND `pid`={$pid}";  
				$rsq1 = $db->ArrayQuery($sqq1);
				if (count($rsq1) <= 0){
					$res = "امضا ".$promised_manage_role[$manage_index]." الزامی می باشد !!!";
					$out = "false";
					response($res, $out);
					continue;
					exit;
				}
				else{
					$confirm_manages+=1;
				}
			}
			 if($confirm_manages==count($promised_manager_array)){
				$itemID = (intval($radioValue) == 0 ? 42 : 43);
				//$sql1 = "SELECT `user_id` FROM `access_table` WHERE `item_id`={$itemID}";  // کاربری که به پرداخت فورج یا سهامی دسترسی دارد.
				$sql1 = "SELECT `user_id` FROM `access_table` AS at LEFT JOIN `users` AS u ON at.`user_id` = u.`RowID`  WHERE `item_id` =  {$itemID}  AND u.`IsEnable` = 1";// کاربری که به پرداخت فورج یا سهامی دسترسی دارد.
                $rst1 = $db->ArrayQuery($sql1);
				$receiver = $rst1[0]['user_id'];

				$sql = "UPDATE `pay_comment` SET `transfer`=1,`receiverDate`='{$receiverDate}',`receiverTime`='{$receiverTime}',`senderUid`={$_SESSION['userid']},`descKesho`='{$desc}',`payType`={$radioValue},`lastReceiver`={$receiver},`tick`={$tick} WHERE `RowID`={$pid}";
				$db->Query($sql);
				$res = $db->AffectedRows();
				$res = (($res == -1 || $res == 0) ? 0 : 1);
				if (intval($res)) 
				{
					$sql3 = "UPDATE `payment_attachment` SET `abilityDelete`=1 WHERE `pid`={$pid}";
					$db->Query($sql3);

                    //-------------------------------------------------
                    $last_workflow="SELECT RowID FROM `payment_workflow` where `pid`={$pid}   ORDER BY RowID DESC LIMIT 1";
                    $res_last_id=$db->ArrayQuery($last_workflow);
                    $upadte_sql="UPDATE payment_workflow set done=1 where RowID={$res_last_id[0]['RowID']}";
                    $res_update=$db->Query($upadte_sql);
                    //----------------------------------------------------

					$sqq = "INSERT INTO `payment_workflow` (`sender`,`receiver`,`pid`,`status`,`createDate`,`createTime`,`description`) VALUES ({$_SESSION['userid']},{$receiver},{$pid},1,'{$receiverDate}','{$receiverTime}','{$desc}')";
					$db->Query($sqq);
					if ($acm->hasAccess('financialConfirmation')) {
						$res = 'bale';
						return $res;
					} else {
						$res = 'na';
						return $res;
					}
				} 
				else 
				{
					return false;
				}
			}
		}
        elseif($meta_comment_is_drivers_fare_count>0)
        {
            $comment_meta_info=$this->convert_meta_data($meta_res_comment_is_drivers_fare); 
            $varede_flag=0;
            $sadereh_flag=0;
            if($comment_meta_info['is_drivers_fare_type']==2)
            {//اگر بارنامه وارده بود----------------------------------
                $varede_flag=1;
                $permission_users_array=$ut->get_full_access_users(15);//کاربران مجاز به تایید بارنامه وارده
                $permission_users_role=['مدیربازرگانی خرید','رئیس حسابداری'];
                $varedeh_permission=0;
                $ut->fileRecorder('وارده');
                $ut->fileRecorder($permission_users_array);
            }
            if($comment_meta_info['is_drivers_fare_type']==1)
            {//اگر بارنامه صادره بود----------------------------------
                
                $sadereh_flag=1;
                $sadereh_permission=0;
                if($rsq[0]['sendType']==0 || $rsq[0]['sendType']==1){// اظهارنظر فورج باشد
                    $permission_users_array=$ut->get_full_access_users(16);//کاربران مجاز به تایید بارنامه صادره فورج
                    $permission_users_role=['مدیربازرگانی خرید','رئیس حسابداری'];
                    $ut->fileRecorder('صادره فورج');
                    $ut->fileRecorder($permission_users_array);
                   
                }
                if($rsq[0]['sendType']==2){// اظهارنظر سهامی  باشد
                    $permission_users_array=$ut->get_full_access_users(17);//کاربران مجاز به تایید بارنامه صادره سهامی
                    $ut->fileRecorder('صادره سهامی');
                    $ut->fileRecorder($permission_users_array);
                    $permission_users_role=['مدیربازرگانی خرید','رئیس حسابداری','کارشناس حسابداری فروش'];
                    
                }
            }
           
            if($varede_flag==1)
            {
               // $permission_users_role=['مدیربازرگانی خرید','رئیس حسابداری'];
                for($i=0;$i<count($permission_users_array);$i++){
                    $sqq1 = "SELECT `RowID` FROM `payment_workflow` WHERE `sender`={$permission_users_array[$i]} AND `status`=1 AND `pid`={$pid}";  
                  
                    $rsq1 = $db->ArrayQuery($sqq1);
                    if (count($rsq1) <= 0){
                        $res = "امضا ".$permission_users_role[$i]." الزامی می باشد !!!";
                        $out = "false";
                        response($res, $out);
                        exit;
                    }
                    else{
                        $varedeh_permission++;
                    }
                }
                if($varedeh_permission==count($permission_users_array)){
                    $transfer_flag=1;
                }
            }
            elseif($sadereh_flag==1)
            {
                for($i=0;$i<count($permission_users_array);$i++){
                    $sqq2 = "SELECT `RowID` FROM `payment_workflow` WHERE `sender`={$permission_users_array[$i]} AND `status`=1 AND `pid`={$pid}";  
                    $ut->fileRecorder($i."**".$sqq2);
                    $rsq2 = $db->ArrayQuery($sqq2);
                    if (count($rsq2) <= 0){
                        $res = "امضا ".$permission_users_role[$i]." الزامی می باشد !!!";
                        $out = "false";
                       
                        response($res, $out);
                        exit;
                    }
                    else{
                        $sadereh_permission++;
                    }
                }

                if($sadereh_permission == count($permission_users_array))
                {

                    $transfer_flag=1;
                }
            }
         }
		else
		{
			
            switch (intval($rsq[0]['Transactions'])){
				case 1 :  // جزئی
					if (intval($rsq[0]['Unit']) == 20){  // اگر تدارکات بود
						$sqq1 = "SELECT `RowID` FROM `payment_workflow` WHERE `sender`=14 AND `status`=1 AND `pid`={$pid}";  // امضا مدیر بازرگانی خرید
						$rsq1 = $db->ArrayQuery($sqq1);
						if (count($rsq1) <= 0){
							$res = "امضا مدیر بازرگانی خرید الزامی می باشد !!!";
							$out = "false";
							response($res, $out);
							exit;
						}
					}elseif (intval($rsq[0]['Unit']) == 24 || intval($rsq[0]['Unit']) == 31 || intval($rsq[0]['Unit']) == 39 || intval($rsq[0]['Unit']) == 20 || intval($rsq[0]['Unit']) == 40){  // بازرگانی فروش - وصول مطالبات و روابط عمومی و امور عمرانی
						$sqq1 = "SELECT `RowID` FROM `payment_workflow` WHERE `sender`=20 AND `status`=1 AND `pid`={$pid}";  // امضا معاونت
						$rsq1 = $db->ArrayQuery($sqq1);
						if (count($rsq1) <= 0){
							$res = "امضا معاونت بازرگانی الزامی می باشد !!!!!";
							$out = "false";
							response($res, $out);
							exit;
						}
					}else{  // سایر واحد ها
						$sqq1 = "SELECT `RowID` FROM `payment_workflow` WHERE (`sender`=4 OR `sender`=20)   AND `status`=1 AND `pid`={$pid}";  // امضا مدیریت
						$rsq1 = $db->ArrayQuery($sqq1);
						if (count($rsq1) <= 0){
							$res = "امضا مدیریت عامل الزامی می باشد !!!!";
							$out = "false";
							response($res, $out);
							exit;
						}
					}
                    $head_accounting_user=$ut->get_full_access_users(23);
                    $head_statement=implode(',',$head_accounting_user);
					$sqq2 = "SELECT `RowID` FROM `payment_workflow` WHERE `sender`in ({$head_statement}) AND `status`=1 AND `pid`={$pid}";  // امضا مدیر مالی
					$rsq2 = $db->ArrayQuery($sqq2);
					if (count($rsq2) <= 0){
						$res = "امضا رییس واحد حسابداری  الزامی می باشد !!!";
						$out = "false";
						response($res, $out);
						exit;
					}
					break;
				case 2 :
					$sqq1 = "SELECT `RowID` FROM `payment_workflow` WHERE (`sender`=4 OR `sender`=20) AND `status`=1 AND `pid`={$pid}";  // امضا معاونت یا مدیریت
					$rsq1 = $db->ArrayQuery($sqq1);
					if (count($rsq1) <= 0){
						$res = "امضا مدیریت عامل یا معاونت بازرگانی الزامی می باشد !!!";
						$out = "false";
						response($res, $out);
						exit;
					}
					$sqq2 = "SELECT `RowID` FROM `payment_workflow` WHERE `sender`=72 AND `status`=1 AND `pid`={$pid}";  // امضا مدیر مالی
					$rsq2 = $db->ArrayQuery($sqq2);
					if (count($rsq2) <= 0){
						$res = "امضا مدیر مالی الزامی می باشد !!!";
						$out = "false";
						response($res, $out);
						exit;
					}
					break;
				case 3 :
				case 4 :
					$sqq1 = "SELECT `RowID` FROM `payment_workflow` WHERE `sender`=4 AND `status`=1 AND `pid`={$pid}";  // امضا مدیریت
					$rsq1 = $db->ArrayQuery($sqq1);
					if (count($rsq1) <= 0){
						$res = "امضا مدیریت عامل الزامی می باشد !!!";
						$out = "false";
						response($res, $out);
						exit;
					}
					$sqq3 = "SELECT `RowID` FROM `payment_workflow` WHERE `sender`=72 AND `status`=1 AND `pid`={$pid}";  // امضا مدیر مالی
					$rsq3 = $db->ArrayQuery($sqq3);
					if (count($rsq3) <= 0){
						$res = "امضا مدیر مالی الزامی می باشد !!!";
						$out = "false";
						response($res, $out);
						exit;
					}
					if (intval($rsq[0]['layer1']) !== 25 && intval($rsq[0]['layer2']) !== 93 && intval($rsq[0]['layer2']) !== 149){
						$sqq2 = "SELECT `RowID` FROM `payment_workflow` WHERE `sender`=20 AND `status`=1 AND `pid`={$pid}";  // امضا معاونت
						$rsq2 = $db->ArrayQuery($sqq2);
						if (count($rsq2) <= 0){
							$res = "امضا معاونت بازرکانی الزامی می باشد !!!";
							$out = "false";
							response($res, $out);
							exit;
						}
					}
					break;
			}
            $transfer_flag=1;
        }
        if($transfer_flag==1)
        {
			$query = "SELECT `sendType` FROM `pay_comment` WHERE `RowID`={$pid}";
			$rst = $db->ArrayQuery($query);
			if ((intval($rst[0]['sendType']) == 1 || intval($rst[0]['sendType']) == 2 || intval($rst[0]['sendType']) == 3) && intval($radioValue) == 0){
				return -1;
			}
			if (intval($rst[0]['sendType']) != 3) { // ثبت در حساب بستانکاری نبود
				$itemID = (intval($radioValue) == 0 ? 42 : 43);
				//$sql1 = "SELECT `user_id` FROM `access_table` WHERE `item_id`={$itemID}";  // کاربری که به پرداخت فورج یا سهامی دسترسی دارد.
                $sql1 = "SELECT `user_id` FROM access_table AS at LEFT JOIN users AS u ON at.`user_id`= u.`RowID`  WHERE `item_id`= {$itemID}  AND u.`IsEnable`= 1";
                //$ut->fileRecorder($sql1);
				$rst1 = $db->ArrayQuery($sql1);
				$receiver = $rst1[0]['user_id'];

				$sql = "UPDATE `pay_comment` SET `transfer`=1,`receiverDate`='{$receiverDate}',`receiverTime`='{$receiverTime}',`senderUid`={$_SESSION['userid']},`descKesho`='{$desc}',`payType`={$radioValue},`lastReceiver`={$receiver},`tick`={$tick} WHERE `RowID`={$pid}";
				$db->Query($sql);
                //$ut->fileRecorder($sql);
				$res = $db->AffectedRows();
				$res = (($res == -1 || $res == 0) ? 0 : 1);
				if (intval($res)) {
					$sql3 = "UPDATE `payment_attachment` SET `abilityDelete`=1 WHERE `pid`={$pid}";
					$db->Query($sql3);

                    //-------------------------------------------------
                    $last_workflow="SELECT RowID FROM `payment_workflow` where `pid`={$pid}   ORDER BY RowID DESC LIMIT 1";
                    $res_last_id=$db->ArrayQuery($last_workflow);
                    $upadte_sql="UPDATE payment_workflow set done=1 where RowID={$res_last_id[0]['RowID']}";
                    $res_update=$db->Query($upadte_sql);
                    //----------------------------------------------------

					$sqq = "INSERT INTO `payment_workflow` (`sender`,`receiver`,`pid`,`status`,`createDate`,`createTime`,`description`) VALUES ({$_SESSION['userid']},{$receiver},{$pid},1,'{$receiverDate}','{$receiverTime}','{$desc}')";
					$db->Query($sqq);
					if ($acm->hasAccess('payCommentManage')) {
						$res = 'yes';
						return $res;
					} else {
						$res = 'no';
						return $res;
					}
				} else {
					return false;
				}
			}else{   // ثبت در حساب بستانکاری بود
				//$sql2 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=48";  // کاربری که به تاییدیه مالی دسترسی دارد
				$sql2 =  "SELECT `user_id` FROM access_table AS at LEFT JOIN users AS u ON at.`user_id`= u.`RowID`  WHERE `item_id`= 48  AND u.`IsEnable`= 1";
				$rst2 = $db->ArrayQuery($sql2);
				$receiver = $rst2[0]['user_id'];

				$sql = "UPDATE `pay_comment` SET `transfer`=2,`receiverDate`='{$receiverDate}',`receiverTime`='{$receiverTime}',`senderUid`={$_SESSION['userid']},`descKesho`='{$desc}',`payType`={$radioValue},`lastReceiver`={$receiver},`tick`={$tick} WHERE `RowID`={$pid}";
				$db->Query($sql);
				$res = $db->AffectedRows();
				$res = (($res == -1 || $res == 0) ? 0 : 1);
				if (intval($res)) {
					$sql3 = "UPDATE `payment_attachment` SET `abilityDelete`=1 WHERE `pid`={$pid}";
					$db->Query($sql3);
                    
                    //-------------------------------------------------
                    $last_workflow="SELECT RowID FROM `payment_workflow` where `pid`={$pid}   ORDER BY RowID DESC LIMIT 1";
                    $res_last_id=$db->ArrayQuery($last_workflow);
                    $upadte_sql="UPDATE payment_workflow set done=1 where RowID={$res_last_id[0]['RowID']}";
                    $res_update=$db->Query($upadte_sql);
                    //----------------------------------------------------

					$sqq = "INSERT INTO `payment_workflow` (`sender`,`receiver`,`pid`,`status`,`createDate`,`createTime`,`description`) VALUES ({$_SESSION['userid']},{$receiver},{$pid},1,'{$receiverDate}','{$receiverTime}','{$desc}')";
					$db->Query($sqq);
					if ($acm->hasAccess('financialConfirmation')) {
						$res = 'bale';
						return $res;
					} else {
						$res = 'na';
						return $res;
					}
				} else {
					return false;
				}
			}
		}
    }


    public function getSubLayers($layer1){
        $db = new DBi();
        if($layer1 == -1){
            return array();
        }else {
            $sql = "SELECT `layerName`,`RowID` FROM `layers` WHERE `parentID`={$layer1}";
            $res = $db->ArrayQuery($sql);
            if (count($res) > 0) {
                return $res;
            } else {
                return array();
            }
        }
    }

    public function get_sublayer_two($layer1,$barnameh_select){
        $db = new DBi();
        $ut = new Utility();
       
        if($layer1 == -1){
            return array();
        }
        else 
        {
           if($barnameh_select==1)
           {
                $sql = "SELECT l.`RowID`,l.`layerName` FROM  layers as l 
                LEFT JOIN `layers_meta` as lm 
                on l.`RowID`=lm.`layer_id` WHERE  l.`parentID`={$layer1} AND  lm.`key`='related_to_barnameh_sublayer'  GROUP BY l.`RowID`";
              
           }
           else
           {
                $sql="SELECT l.`RowID`,l.`layerName` FROM  layers as l 
                LEFT JOIN `layers_meta` as lm 
                on l.`RowID`=lm.`layer_id` WHERE  l.`parentID`={$layer1}  
                AND l.`RowID` NOT IN (SELECT layer_id FROM layers_meta WHERE `key`='related_to_barnameh_sublayer')";
           }
            $res = $db->ArrayQuery($sql);
            if (count($res) > 0) {
                return $res;
            } else {
                return array();
            }
        }
    }

    public function set_commentManagmentUnit($meta_data){
        $db = new DBi();
        $sql = "SELECT r.`RowID`,r.`unitName` FROM `relatedunits` as r LEFT JOIN `relatedunits_meta` rm  on r.`RowID`=rm.`unit_id`
        WHERE rm.`key`='{$meta_data}'";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function downloadCheckCarcassFileHtm($pid){
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `check_carcass` WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="downloadCheckCarcassFile-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">نام فایل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $fileName = 'رسید شماره '.$iterator;
            $link = ADDR.'checkCarcass/'.$res[$i]['fileName'];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$fileName.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getAttachFundToCommentList($cid){
        $db = new DBi();
        $ut = new Utility();
        $this->set_comment_view_date($cid,'مشاهده لیست تنخواه');
        $sql = "SELECT `fund_list`.*,`fname`,`lname` FROM `fund_list` INNER JOIN `users` ON (`fund_list`.`uid`=`users`.`RowID`) WHERE `pid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="getAttachFundToCommentList-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 13%;">ثبت کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">نوع تنخواه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">کد تنخواه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">سرگروه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زیرگروه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زیرگروه فرعی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">مبلغ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">حذف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">جزئیات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $sqq = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer1']}";
            $sqq1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer2']}";
            $sqq2 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer3']}";
            $rst = $db->ArrayQuery($sqq);
            $rst1 = $db->ArrayQuery($sqq1);
            $rst2 = $db->ArrayQuery($sqq2);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res[$i]['cDate']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(intval($res[$i]['fundName']) == 0 ? 'تنخواه هزینه ای' : 'تنخواه مصرفی').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['unCode'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(count($rst) > 0 ? $rst[0]['layerName'] : '').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(count($rst1) > 0 ? $rst1[0]['layerName'] : '').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(count($rst2) > 0 ? $rst2[0]['layerName'] : '').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res[$i]['finalAmount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachedFundList('.$res[$i]['RowID'].')"><i class="fas fa-trash-alt"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="attachedFundListDetails('.$res[$i]['RowID'].')"><i class="fas fa-puzzle-piece"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function showFundListDetailsHTM($fid){
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `fund_list_details` WHERE `fid`={$fid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="showFundListDetailsHTM-tableID">';
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
        for ($i=0;$i<$cnt;$i++){
            $cDate = (strtotime($res[$i]['createDate']) > 0 ? $ut->greg_to_jal($res[$i]['createDate']) : '');
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$cDate.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['reqNumber'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['placeUse'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res[$i]['fundAmount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showAttachFundListDetails('.$res[$i]['RowID'].')"><i class="fas fa-link"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function fundListAttachShowHTM($fdid){
        $db = new DBi();
        $sql = "SELECT * FROM `fund_details_attach` WHERE `fdid`={$fdid}";
        $ut=new Utility();
        $ut->fileRecorder($sql);
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="fundListAttachShowHTM-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $link = ADDR.'attachment/'.$res[$i]['fileName'];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.($i+1).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function deleteAttachedFundList($fid){
        $db = new DBi();
        $ut = new Utility();
        $query = "SELECT `fundName` FROM `fund_list` 
                  INNER JOIN `payment_workflow` ON (`fund_list`.`pid`=`payment_workflow`.`pid`)
                  WHERE `fund_list`.`RowID`={$fid}";
        $result = $db->ArrayQuery($query);
        if (intval($_SESSION['userid']) != 1 && intval($_SESSION['userid']) != 14){
            $cntt = count($result);
        }else{
            $cntt = 0;
        }

        if ($cntt > 0){
            return -1;
        }else{
            $sqq = "SELECT `pid`,`finalAmount` FROM `fund_list` WHERE `RowID`={$fid}";
            $rst = $db->ArrayQuery($sqq);

            $sql1 = "UPDATE `fund_list` SET `pid`=0 WHERE `RowID`={$fid}";
            $db->Query($sql1);
            $res1 = $db->AffectedRows();
            $res1 = (($res1 == -1 || $res1 == 0) ? 0 : 1);
            if (intval($res1)) {
                $sqll = "SELECT `Amount`,`totalAmount` FROM `pay_comment` WHERE `RowID`={$rst[0]['pid']}";
                $rst1 = $db->ArrayQuery($sqll);
                $amount = $rst1[0]['Amount'] - $rst[0]['finalAmount'];
                $totalAmount = $rst1[0]['totalAmount'] - $rst[0]['finalAmount'];

                // if (intval($totalAmount) <= 100000000 ){
                //     $Transactions = 1;
                // }elseif (intval($totalAmount) <= 1000000000){
                //     $Transactions = 2;
                // }elseif (intval($totalAmount) <= 5000000000){
                //     $Transactions = 3;
                // }else{
                //     $Transactions = 4;
                // }

                if (intval($totalAmount) <= $this->get_trading_levels('small_purchases')[0] ){
                    $Transactions = 1;
                }elseif (intval($totalAmount) <= $this->get_trading_levels('middel_purchases')[0]){
                    $Transactions = 2;
                }elseif (intval($totalAmount) <= $this->get_trading_levels('major_purchases')[0]){
                    $Transactions = 3;
                }else{
                    $Transactions = 4;
                }

                $sqlu = "UPDATE `pay_comment` SET `Amount`={$amount},`CashSection`={$amount},`totalAmount`={$totalAmount},`Transactions`={$Transactions} WHERE `RowID`={$rst[0]['pid']}";
                $db->Query($sqlu);

                $sql = "SELECT `fund_list`.*,`fname`,`lname` FROM `fund_list` INNER JOIN `users` ON (`fund_list`.`uid`=`users`.`RowID`) WHERE `pid`={$rst[0]['pid']}";
                $res = $db->ArrayQuery($sql);
                $cnt = count($res);

                $htm = '';
                $htm .= '<table class="table table-bordered table-hover table-sm" id="contractChooseList-tableID">';
                $htm .= '<thead>';
                $htm .= '<tr class="bg-info">';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 13%;">ثبت کننده</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">نام تنخواه</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">کد تنخواه</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">سرگروه</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زیرگروه</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زیرگروه فرعی</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">مبلغ</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">حذف</td>';
                $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">جزئیات</td>';
                $htm .= '</tr>';
                $htm .= '</thead>';
                $htm .= '<tbody>';

                for ($i=0;$i<$cnt;$i++){
                    $iterator++;
                    $sqq = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer1']}";
                    $sqq1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer2']}";
                    $sqq2 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer3']}";
                    $rst = $db->ArrayQuery($sqq);
                    $rst1 = $db->ArrayQuery($sqq1);
                    $rst2 = $db->ArrayQuery($sqq2);

                    $htm .= '<tr class="table-secondary">';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res[$i]['cDate']).'</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fundName'].'</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['unCode'].'</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(count($rst) > 0 ? $rst[0]['layerName'] : '').'</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(count($rst1) > 0 ? $rst1[0]['layerName'] : '').'</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(count($rst2) > 0 ? $rst2[0]['layerName'] : '').'</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res[$i]['finalAmount']).' ریال</td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachedFundList('.$res[$i]['RowID'].')"><i class="fas fa-trash-alt"></i></button></td>';
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="attachedFundListDetails('.$res[$i]['RowID'].')"><i class="fas fa-puzzle-piece"></i></button></td>';
                    $htm .= '</tr>';
                }
                $htm .= '</tbody>';
                $htm .= '</table>';
                $acm = new acm();
                if($acm->hasAccess('fundListManage')){
                    $res = 'yes';
                }else{
                    $res = 'no';
                }
                $send = array($htm,$res);
                return $send;
            }else{
                return false;
            }
        }
    }

    public function getPrintFundCoverHTM($cid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `RowID` FROM `fund_list` WHERE `pid`=" . $cid;
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $rids = array();
        for ($i=0;$i<$cnt;$i++){
            $rids[] = $res[$i]['RowID'];
        }
        $rids = implode(',',$rids);

        $query = "SELECT * FROM `fund_list_details` WHERE `fid` IN ({$rids})";
       
        $rst = $db->ArrayQuery($query);
        $cnt1 = count($rst);

        $htm = '';
        $htm .= '<div class="demoFund" style="margin-top: -60px;">';
        $htm .= '<table class="table table-sm" id="fundCover-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="text-dark">';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 30%;">شرح</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 15%;">شماره درخواست</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 15%;">محل استفاده</td>';
        $htm .= '<td style="border: 2px solid #000;text-align: center;font-family: dubai-Bold;width: 25%;">مبلغ</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $iterator = 0;
        $totalAmount = 0;
        for ($i = 0; $i < $cnt1; $i++) {
            $totalAmount += $rst[$i]['fundAmount'];
            $iterator ++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $iterator. '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $ut->greg_to_jal($rst[$i]['createDate']) . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $rst[$i]['description'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $rst[$i]['reqNumber'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . $rst[$i]['placeUse'] . '</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;border: 2px solid #000;">' . number_format($rst[$i]['fundAmount']) . ' ریال</td>';
            $htm .= '</tr>';
        }

        $htm .= '<tr class="table-secondary">';
        $htm .= '<td colspan="2" style="border: 2px solid #000;text-align: center;font-family: dubai-Regular;padding: 10px;">جمع کل مبلغ</td>';
        $htm .= '<td colspan="4" style="border: 2px solid #000;text-align: right;font-family: dubai-Regular;padding: 10px;">'.number_format($totalAmount).' ریال</td>';
        $htm .= '</tr>';

        $htm .= '<tr class="table-secondary">';
        $htm .= '<td colspan="2" style="border: 2px solid #000;text-align: right;font-family: dubai-Regular;padding: 10px;">نام و امضا تنخواه گردان <br><br><br><br><br><br></td>';
        $htm .= '<td colspan="1" style="border: 2px solid #000;text-align: right;font-family: dubai-Regular;padding: 10px;">امضا سرپرست / مدیر مربوطه<br><br><br><br><br><br></td>';
        $htm .= '<td colspan="2" style="border: 2px solid #000;text-align: right;font-family: dubai-Regular;padding: 10px;">امضا حسابداری<br><br><br><br><br><br></td>';
        $htm .= '<td colspan="1" style="border: 2px solid #000;text-align: right;font-family: dubai-Regular;padding: 10px;">تایید مدیریت محترم عامل<br><br><br><br><br><br></td>';
        $htm .= '</tr>';

        $htm .= '</tbody>';
        $htm .= '</table>';
        $htm .= '</div>';
       // createExcel($hd,$content,$fieldsName,$name,$additionalFields=array(),$footerFields=array())
        return $htm;
    }

    public function getFundListExcel($cid){
        
        $db = new DBi();
        $ut = new Utility();
  
        $sql = "SELECT `RowID` FROM `fund_list` WHERE `pid`=" . $cid;
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $rids = array();
        $final_array=[];
        for ($i=0;$i<$cnt;$i++){
            $rids[] = $res[$i]['RowID'];
        }
        $counter=1;
        $rids = implode(',',$rids);

        $query = "SELECT * FROM `fund_list_details` WHERE `fid` IN ({$rids})";
       
        $rst = $db->ArrayQuery($query);
  
        foreach($rst as $rows){
            $array_handler=[];
            //array('counter','createDate','createDate','uid','description','reqNumber','placeUse','fundAmount');
            $shamsi_date=$ut->greg_to_jal($rows['createDate']);
            $shamsi_array=explode('/',$shamsi_date);
           
            if(strlen($shamsi_array[1])==1){
                $shamsi_array[1]="0".$shamsi_array[1];
            }
            if(strlen($shamsi_array[2])==1){
                $shamsi_array[2]="0".$shamsi_array[2];
            }
            $shamsi_date_u=implode("/",$shamsi_array);
            $array_handler['counter']=$counter;
            $array_handler['createDate']=$shamsi_date_u;//$ut->greg_to_jal($rows['createDate']);
            $array_handler['uid']=$ut->get_user_fullname($rows['uid']);
            $array_handler['description']=$rows['description'];
            $array_handler['reqNumber']=$rows['reqNumber'];
            $array_handler['placeUse']=$rows['placeUse'];
            $array_handler['fundAmount']=$rows['fundAmount'];
            $final_array[]=$array_handler;
            $counter++;
        }
        
        return $final_array;

    }

    private function getUsersAccessToComment(){
        $db = new DBi();
        $sql = "SELECT `users`.`RowID`,`fname`,`lname`,`unitID` FROM `users` INNER JOIN `access_table` ON (`users`.`RowID`=`access_table`.`user_id`) WHERE `item_id`=38 AND `users`.`IsEnable`=1 ORDER BY `users`.`fname` ASC";
            
		$res = $db->ArrayQuery($sql);
        return $res;
    }

    public function cancellationPayComment($cid){
        $db = new DBi();
        $sql = "UPDATE `pay_comment` SET `isEnable`=0 WHERE `RowID`={$cid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    //++++++++++++++++++++++ اجزا تنخواه +++++++++++++++++++++++

    public function getFundListManageList($name,$code,$amount,$layer1,$layer2,$layer3,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('fundListManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($name)) > 0){
            $w[] = '`fundName` LIKE "%'.$name.'%" ';
        }
        if(strlen(trim($code)) > 0){
            $w[] = '`unCode`='.$code.' ';
        }
        if(strlen(trim($amount)) > 0){
            $w[] = '`finalAmount`='.$amount.' ';
        }
        if($layer1 > 0){
            $w[] = '`layer1`='.$layer1.' ';
        }
        if($layer2 > 0){
            $w[] = '`layer2`='.$layer2.' ';
        }
        if($layer3 > 0){
            $w[] = '`layer3`='.$layer3.' ';
        }
        if ($_SESSION['userid'] != 1){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql = "SELECT `fund_list`.*,`fname`,`lname` FROM `fund_list` INNER JOIN `users` ON (`fund_list`.`uid`=`users`.`RowID`)";
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
            $finalRes[$y]['bgColor'] = (intval($res[$y]['pid']) > 0 ? 'table-danger' : 'table-success');
            $finalRes[$y]['recorder'] = $res[$y]['fname'].' '.$res[$y]['lname'];
            switch (intval($res[$y]['fundName'])){
                case 0:
                    $finalRes[$y]['fundName'] = 'تنخواه هزینه ای';
                    break;
                case 1:
                    $finalRes[$y]['fundName'] = 'تنخواه مصرفی';
                    break;
                case 2:
                    $finalRes[$y]['fundName'] = 'تنخواه مواد اولیه';
                    break;
            }
            $finalRes[$y]['unCode'] = $res[$y]['unCode'];
            $finalRes[$y]['finalAmount'] = (intval($res[$y]['finalAmount']) > 0 ? number_format($res[$y]['finalAmount']).' ریال' : '');
            $sqq = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$y]['layer1']}";
            $sqq1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$y]['layer2']}";
            $sqq2 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$y]['layer3']}";
            $rst = $db->ArrayQuery($sqq);
            $rst1 = $db->ArrayQuery($sqq1);
            $rst2 = $db->ArrayQuery($sqq2);
            $finalRes[$y]['layer1'] = (count($rst) > 0 ? $rst[0]['layerName'] : '');
            $finalRes[$y]['layer2'] = (count($rst1) > 0 ? $rst1[0]['layerName'] : '');
            $finalRes[$y]['layer3'] = (count($rst2) > 0 ? $rst2[0]['layerName'] : '');
        }
        return $finalRes;
    }

    public function getFundListManageListCountRows($name,$code,$amount,$layer1,$layer2,$layer3){
        $db = new DBi();
        $ut=new Utility();
        $w = array();
        if(strlen(trim($name)) > 0){
            $w[] = '`fundName` LIKE "%'.$name.'%" ';
        }
        if(strlen(trim($code)) > 0){
            $w[] = '`unCode`='.$code.' ';
        }
        if(strlen(trim($amount)) > 0){
            $w[] = '`finalAmount`='.$amount.' ';
        }
        if(($layer1) > 0){
            $w[] = '`layer1`='.$layer1.' ';
        }
        if($layer2 > 0){
            $w[] = '`layer2`='.$layer2.' ';
        }
        if($layer3 > 0){
            $w[] = '`layer3`='.$layer3.' ';
        }
        if ($_SESSION['userid'] != 1){
            $w[] = '`uid`='.$_SESSION['userid'].' ';
        }

        $sql = "SELECT `fund_list`.*,`fname`,`lname` FROM `fund_list` INNER JOIN `users` ON (`fund_list`.`uid`=`users`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
       // $ut->fileRecorder($sql);
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function fundListInfo($fid){
        $db = new DBi();
        $sql = "SELECT * FROM `fund_list` WHERE `RowID`=".$fid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("fid"=>$fid,"fundName"=>$res[0]['fundName'],"layer1"=>$res[0]['layer1'],"layer2"=>$res[0]['layer2'],"layer3"=>$res[0]['layer3']);
            return $res;
        }else{
            return false;
        }
    }

    public function createFundList($name,$layer1,$layer2,$layer3){
        $acm = new acm();
        if(!$acm->hasAccess('fundListManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $nowDate = date('Y-m-d');
        $datetostring = substr($ut->greg_to_jal(str_replace('/','-',date('Y/m/d'))),2,2);
        $unCode = $datetostring.rand(10000,99999).substr(time(), -4);
        $sql = "INSERT INTO `fund_list` (`uid`,`fundName`,`unCode`,`layer1`,`layer2`,`layer3`,`cDate`) VALUES ({$_SESSION['userid']},{$name},'{$unCode}',{$layer1},{$layer2},{$layer3},'{$nowDate}')";
        $res = $db->Query($sql);
        if (intval($res) > 0) {
            return true;
        }else{
            return false;
        }
    }

    public function editFundList($fid,$name,$layer1,$layer2,$layer3){
        $acm = new acm();
        if(!$acm->hasAccess('fundListManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `pid` FROM `fund_list` WHERE `RowID`={$fid}";
        $res = $db->ArrayQuery($sql);
        if (intval($res[0]['pid']) > 0){
            return -1;
        }else{
            $query = "UPDATE `fund_list` SET `fundName`={$name},`layer1`={$layer1},`layer2`={$layer2},`layer3`={$layer3} WHERE `RowID`={$fid}";
            $db->Query($query);
            $res = $db->AffectedRows();
            $res = (($res == -1 || $res == 0) ? 0 : 1);
            if (intval($res)) {
                return true;
            }else{
                return false;
            }
        }
    }

    public function fundListDetailsHTM($fid){
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `fund_list_details` WHERE `fid`={$fid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="fundListDetailsHTM-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 23%;">شرح</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 16%;">شماره درخواست</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 17%;">محل استفاده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">مبلغ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">حذف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">ضمیمه</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $cDate = (strtotime($res[$i]['createDate']) > 0 ? $ut->greg_to_jal($res[$i]['createDate']) : '');
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$cDate.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['reqNumber'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['placeUse'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res[$i]['fundAmount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteFundListDetails('.$res[$i]['RowID'].')"><i class="fas fa-trash-alt"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="attachFundListDetails('.$res[$i]['RowID'].')"><i class="fas fa-link"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function createFundListDetails($fid,$CDate,$Description,$ReqNum,$PlaceUse,$Amount){
        $acm = new acm();
        if(!$acm->hasAccess('fundListManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $query = "SELECT `pid`,`finalAmount` FROM `fund_list` WHERE `RowID`={$fid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['pid']) > 0){
            return -1;
        }
        $finalAmount = $rst[0]['finalAmount'] + $Amount;
        $CDate = $ut->jal_to_greg($CDate);
        $sql = "INSERT INTO `fund_list_details` (`fid`,`uid`,`createDate`,`description`,`reqNumber`,`placeUse`,`fundAmount`) VALUES ({$fid},{$_SESSION['userid']},'{$CDate}','{$Description}','{$ReqNum}','{$PlaceUse}',{$Amount})";
        $res = $db->Query($sql);
        if (intval($res) > 0) {
            $sql1 = "UPDATE `fund_list` SET `finalAmount`={$finalAmount} WHERE `RowID`={$fid}";
            $db->Query($sql1);
            return true;
        }else{
            return false;
        }
    }

    public function deleteFundListDetails($fid,$fdid){
        $acm = new acm();
        if(!$acm->hasAccess('fundListManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $query = "SELECT `pid`,`finalAmount` FROM `fund_list` WHERE `RowID`={$fid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['pid']) > 0){
            return -1;
        }

        $sqq = "SELECT `fundAmount`,`uid` FROM `fund_list_details` WHERE `RowID`={$fdid}";
        $rsq = $db->ArrayQuery($sqq);
        $finalAmount = $rst[0]['finalAmount'] - $rsq[0]['fundAmount'];
        if (intval($_SESSION['userid']) !== 1 && intval($_SESSION['userid']) !== 14) {
            if (intval($_SESSION['userid']) !== intval($rsq[0]['uid'])) {
                return -2;
            }
        }

        $sql = "SELECT `fileName` FROM `fund_details_attach` WHERE `fdid`={$fdid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/attachment/' . $res[$i]['fileName'];
            unlink($file_to_delete);
        }

        $sqDel = "DELETE FROM `fund_details_attach` WHERE `fdid`={$fdid}";
        $db->Query($sqDel);

        $query = "DELETE FROM `fund_list_details` WHERE `RowID`={$fdid}";
        $db->Query($query);

        $sql1 = "UPDATE `fund_list` SET `finalAmount`={$finalAmount} WHERE `RowID`={$fid}";
        $db->Query($sql1);

        return true;
    }

    public function fundListAttachHTM($fdid){
        $db = new DBi();
        $sql = "SELECT * FROM `fund_details_attach` WHERE `fdid`={$fdid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="fundListAttachHTM-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">لینک دانلود</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">حذف فایل</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $link = ADDR.'attachment/'.$res[$i]['fileName'];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.($i+1).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteFundListAttach('.$res[$i]['RowID'].')"><i class="fas fa-trash-alt"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function attachFileToFundList($fid,$fdid,$files){
        $acm = new acm();
        if(!$acm->hasAccess('fundListManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $query = "SELECT `pid` FROM `fund_list` WHERE `RowID`={$fid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['pid']) > 0){
            return -4;
        }

        $SFile = array();
        $allowedTypes = ['png','jpg','jpeg','jfif','pdf','xlsx','docx','zip','rar','PNG','JPG','JPEG','JFIF','PDF','XLSX','DOCX','ZIP','RAR'];

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
                $SFile[] = "fundList" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))
        $cnt = count($SFile);

        for ($i=0;$i<$cnt;$i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i],'../attachment/'.$SFile[$i]);
            $sql4 = "INSERT INTO `fund_details_attach` (`fdid`,`fileName`,`uid`) VALUES ({$fdid},'{$SFile[$i]}',{$_SESSION['userid']})";
            $db->Query($sql4);
        }
        return true;
    }

    public function deleteFundListAttach($fid,$fileID){
        $acm = new acm();
        if(!$acm->hasAccess('fundListManage')){
            die("access denied");
            exit;
        }
        $full_access_user_delete_fund_files=[1];
        $db = new DBi();
        $query = "SELECT `pid` FROM `fund_list` WHERE `RowID`={$fid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['pid']) > 0){
            return -1;
        }

        $sql = "SELECT `fileName`,`uid` FROM `fund_details_attach` WHERE `RowID`={$fileID}";
        $res = $db->ArrayQuery($sql);
        if(!in_array($_SESSION['userid'],$full_access_user_delete_fund_files)){ //کاربران مجاز به حذف  فایل های تنخواه
            if (intval($_SESSION['userid']) !== intval($res[0]['uid'])){
                return -2;
            }
         }   
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/attachment/' . $res[0]['fileName'];
        unlink($file_to_delete);

        $sqDel = "DELETE FROM `fund_details_attach` WHERE `RowID`={$fileID}";
        $db->Query($sqDel);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if ($aff){
            return true;
        }else{
            return false;
        }
    }

    public function attachCommentToFundLis($unCode,$fid){
        $acm = new acm();
        if(!$acm->hasAccess('fundListManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $fid = explode(',',$fid);
        $cnt = count($fid);
        $finalAmount = 0;
        $fundName = array();

        for ($i=0;$i<$cnt;$i++){
            $sql = "SELECT `pid`,`finalAmount`,`fundName` FROM `fund_list` WHERE `RowID`={$fid[$i]}";
            $result = $db->ArrayQuery($sql);
            if (intval($result[0]['pid']) > 0){
                return -1;
            }
            if (intval($result[0]['finalAmount']) <= 0){
                return -2;
            }
            $finalAmount += $result[0]['finalAmount'];
            $fundName[] = $result[0]['fundName'];
        }

        $sql1 = "SELECT `RowID`,`Amount`,`totalAmount`,`layer2` FROM `pay_comment` WHERE `unCode`='{$unCode}'";
        $res = $db->ArrayQuery($sql1);
        if (count($res) > 0){
            $query = "SELECT `RowID` FROM `payment_workflow` WHERE `pid`={$res[0]['RowID']}";
            $rst = $db->ArrayQuery($query);
            if (intval($_SESSION['userid']) !== 1) {
                if (count($rst) > 0) {
                    return -3;
                }
            }
            if (intval($res[0]['layer2']) !== 13 && intval($res[0]['layer2']) !== 14 && intval($res[0]['layer2']) !== 318){
                return -4;
            }
            if ((intval($res[0]['layer2']) == 13 && (in_array(1,$fundName) || in_array(2,$fundName))) || (intval($res[0]['layer2']) == 14 && (in_array(0,$fundName) || in_array(2,$fundName))) || (intval($res[0]['layer2']) == 318 && (in_array(0,$fundName) || in_array(1,$fundName)))){
                return -5;
            }
            $amount = $finalAmount + intval($res[0]['Amount']);
            $totalAmount = $finalAmount + intval($res[0]['totalAmount']);

            // if (intval($totalAmount) <= 100000000 ){
            //     $Transactions = 1;
            // }elseif (intval($totalAmount) <= 1000000000){
            //     $Transactions = 2;
            // }elseif (intval($totalAmount) <= 5000000000){
            //     $Transactions = 3;
            // }else{
            //     $Transactions = 4;
            // }

            if (intval($totalAmount) <= $this->get_trading_levels('small_purchases')[0] ){
                $Transactions = 1;
            }elseif (intval($totalAmount) <= $this->get_trading_levels('middel_purchases')[0]){
                $Transactions = 2;
            }elseif (intval($totalAmount) <= $this->get_trading_levels('major_purchases')[0]){
                $Transactions = 3;
            }else{
                $Transactions = 4;
            }

            $fid = implode(',',$fid);
            $sql2 = "UPDATE `fund_list` SET `pid`={$res[0]['RowID']} WHERE `RowID` IN ({$fid})";
            $db->Query($sql2);

            $sql3 = "UPDATE `pay_comment` SET `totalAmount`={$totalAmount},`Amount`={$amount},`CashSection`={$amount},`Transactions`={$Transactions} WHERE `RowID`={$res[0]['RowID']}";
            $db->Query($sql3);
            return true;
        }else{
            return false;
        }
    }

    //++++++++++++++++++++++ دریافتی از مشتری +++++++++++++++++++++++

    public function  getReceivedCustomerList($rsDate,$reDate,$RType,$RMethod,$CName,$RAmount,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('receivedCustomerManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($rsDate)) > 0){
            $rsDate = $ut->jal_to_greg($rsDate);
            $w[] = '`receiveDate` >="'.$rsDate.'" ';
        }
        if(strlen(trim($reDate)) > 0){
            $reDate = $ut->jal_to_greg($reDate);
            $w[] = '`receiveDate` <="'.$reDate.'" ';
        }
        if(intval($RType) >= 0){
            $w[] = '`receiveType`='.$RType.' ';
        }
        if(intval($RMethod) > 0){
            $w[] = '`receiveMethod`='.$RMethod.' ';
        }
        if(strlen(trim($CName)) > 0){
            $w[] = '`depositor`="'.$CName.'" ';
        }
        if(strlen(trim($RAmount)) > 0){
            $w[] = '`receiveAmount`='.$RAmount.' ';
        }

        $sql = "SELECT * FROM `customer_receive`";
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
            $finalRes[$y]['receiveType'] = ($res[$y]['receiveType'] == 0 ? 'بابت فروش' : 'بابت چک برگشتی');
            switch ($res[$y]['receiveMethod']){
                case 1:
                    $finalRes[$y]['receiveMethod'] = 'واریزی به بانک ها';
                    $finalRes[$y]['disabled'] = 'disabled';
                    break;
                case 2:
                    $finalRes[$y]['receiveMethod'] = 'Pos';
                    $finalRes[$y]['disabled'] = 'disabled';
                    break;
                case 3:
                    $finalRes[$y]['receiveMethod'] = 'چک';
                    break;
                case 4:
                    $finalRes[$y]['receiveMethod'] = 'نقدی';
                    $finalRes[$y]['disabled'] = 'disabled';
                    break;
            }
            $finalRes[$y]['checkNumber'] = $res[$y]['checkNumber'];
            $finalRes[$y]['receiveAmount'] = number_format($res[$y]['receiveAmount']).' ریال';
            $finalRes[$y]['receiveDate'] = (strtotime($res[$y]['receiveDate']) ? $ut->greg_to_jal($res[$y]['receiveDate']) : '');
            $finalRes[$y]['depositor'] = $res[$y]['depositor'];
            $finalRes[$y]['codeTafzili'] = $res[$y]['codeTafzili'];
            $finalRes[$y]['description'] = $res[$y]['description'];
            $sql1 = "SELECT `Name` FROM `company_banks` WHERE `RowID`={$res[$y]['bankID']}";
            $rst1 = $db->ArrayQuery($sql1);
            $sql2 = "SELECT `Name` FROM `company_pos` WHERE `RowID`={$res[$y]['posID']}";
            $rst2 = $db->ArrayQuery($sql2);
            $finalRes[$y]['bankID'] = $rst1[0]['Name'];
            $finalRes[$y]['posID'] = $rst2[0]['Name'];
        }
        return $finalRes;
    }

    public function getExcelReceivedCustomer($rsDate,$reDate,$RType,$RMethod,$CName,$RAmount){
        $acm = new acm();
        if(!$acm->hasAccess('receivedCustomerManage') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();

        $w = array();
        if(strlen(trim($rsDate)) > 0){
            $rsDate = $ut->jal_to_greg($rsDate);
            $w[] = '`receiveDate` >="'.$rsDate.'" ';
        }
        if(strlen(trim($reDate)) > 0){
            $reDate = $ut->jal_to_greg($reDate);
            $w[] = '`receiveDate` <="'.$reDate.'" ';
        }
        if(intval($RType) >= 0){
            $w[] = '`receiveType`='.$RType.' ';
        }
        if(intval($RMethod) > 0){
            $w[] = '`receiveMethod`='.$RMethod.' ';
        }
        if(strlen(trim($CName)) > 0){
            $w[] = '`depositor`="'.$CName.'" ';
        }
        if(strlen(trim($RAmount)) > 0){
            $w[] = '`receiveAmount`='.$RAmount.' ';
        }

        $sql = "SELECT * FROM `customer_receive`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            $res[$i]['receiveType'] = (intval($res[$i]['receiveType']) == 0 ? 'بابت فروش' : 'بابت چک برگشتی');
            $res[$i]['receiveDate'] = (strtotime($res[$i]['receiveDate']) > 0 ? $ut->greg_to_jal($res[$i]['receiveDate']) : '');
            $res[$i]['checkDate'] = (strtotime($res[$i]['checkDate']) > 0 ? $ut->greg_to_jal($res[$i]['checkDate']) : '');
            switch ($res[$i]['receiveMethod']){
                case 1:
                    $res[$i]['receiveMethod'] = 'واریزی به بانک ها';
                    break;
                case 2:
                    $res[$i]['receiveMethod'] = 'pos';
                    break;
                case 3:
                    $res[$i]['receiveMethod'] = 'چک';
                    break;
                case 4:
                    $res[$i]['receiveMethod'] = 'نقدی';
                    break;
            }
            $sql1 = "SELECT `Name` FROM `company_banks` WHERE `RowID`={$res[$i]['bankID']}";
            $rst1 = $db->ArrayQuery($sql1);
            $sql2 = "SELECT `Name` FROM `company_pos` WHERE `RowID`={$res[$i]['posID']}";
            $rst2 = $db->ArrayQuery($sql2);
            $res[$i]['bankID'] = $rst1[0]['Name'];
            $res[$i]['posID'] = $rst2[0]['Name'];
            $res[$i]['receiveAmount'] = number_format($res[$i]['receiveAmount']);
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

    public function  getReceivedCustomerListCountRows($rsDate,$reDate,$RType,$RMethod,$CName,$RAmount){
        $acm = new acm();
        if(!$acm->hasAccess('receivedCustomerManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();

        $w = array();
        if(strlen(trim($rsDate)) > 0){
            $rsDate = $ut->jal_to_greg($rsDate);
            $w[] = '`receiveDate` >="'.$rsDate.'" ';
        }
        if(strlen(trim($reDate)) > 0){
            $reDate = $ut->jal_to_greg($reDate);
            $w[] = '`receiveDate` <="'.$reDate.'" ';
        }
        if(intval($RType) >= 0){
            $w[] = '`receiveType`='.$RType.' ';
        }
        if(intval($RMethod) > 0){
            $w[] = '`receiveMethod`='.$RMethod.' ';
        }
        if(strlen(trim($CName)) > 0){
            $w[] = '`depositor`="'.$CName.'" ';
        }
        if(strlen(trim($RAmount)) > 0){
            $w[] = '`receiveAmount`='.$RAmount.' ';
        }

        $sql = "SELECT `RowID` FROM `customer_receive`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getTotalAmountReceivedCustomer($rsDate,$reDate,$RType,$RMethod,$CName,$RAmount){
        $acm = new acm();
        if(!$acm->hasAccess('reportPayCommentManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $w = array();
        $w1 = array();
        $z = array();
        if(strlen(trim($rsDate)) > 0){
            $rsDate = $ut->jal_to_greg($rsDate);
            $w[] = '`receiveDate` >="'.$rsDate.'" ';
            $w1[] = '`receiveDate` >="'.$rsDate.'" ';
            $z[] = '`dDate` >="'.$rsDate.'" ';
        }
        if(strlen(trim($reDate)) > 0){
            $reDate = $ut->jal_to_greg($reDate);
            $w[] = '`receiveDate` <="'.$reDate.'" ';
            $w1[] = '`receiveDate` <="'.$reDate.'" ';
            $z[] = '`dDate` <="'.$reDate.'" ';
        }
        if(intval($RMethod) > 0){
            $w[] = '`receiveMethod`='.$RMethod.' ';
            $w1[] = '`receiveMethod`='.$RMethod.' ';
        }
        if(strlen(trim($CName)) > 0){
            $w[] = '`depositor`="'.$CName.'" ';
            $w1[] = '`depositor`="'.$CName.'" ';
        }
        if(strlen(trim($RAmount)) > 0){
            $w[] = '`receiveAmount`='.$RAmount.' ';
            $w1[] = '`receiveAmount`='.$RAmount.' ';
        }
        $w[] = '`receiveType`=0 ';  // بابت فروش
        $w1[] = '`receiveType`=1 ';  // بابت چک برگشتی
        $z[] = '`deposit`.`uid`=49 ';
        $z[] = '`isEnable`=1 ';

        $sql = "SELECT `receiveAmount` FROM `customer_receive` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $TotalAmount = 0;
        for($y=0;$y<$listCount;$y++){
            $TotalAmount += $res[$y]['receiveAmount'];
        }

        $sql1 = "SELECT `receiveAmount` FROM `customer_receive` ";
        if(count($w1) > 0){
            $where = implode(" AND ",$w1);
            $sql1 .= " WHERE ".$where;
        }
        $res1 = $db->ArrayQuery($sql1);
        $listCount = count($res1);
        $TotalAmount1 = 0;
        for($y=0;$y<$listCount;$y++){
            $TotalAmount1 += $res1[$y]['receiveAmount'];
        }

        if(intval($RType) == 0){
            $TotalAmount1 = 0;
        }elseif (intval($RType) == 1){
            $TotalAmount = 0;
        }

        $dAmount = 0;
        $sql1 = "SELECT `dAmount` FROM `deposit` INNER JOIN `pay_comment` ON (`deposit`.`pid`=`pay_comment`.`RowID`)";
        $where = implode(" AND ",$z);
        $sql1 .= " WHERE ".$where;
        $res1 = $db->ArrayQuery($sql1);
        $cnt = count($res1);
        for ($i=0;$i<$cnt;$i++){
            $dAmount += $res1[$i]['dAmount'];
        }
        $sumAmounts = $TotalAmount + $TotalAmount1 + $dAmount;
        $send = array($TotalAmount,$TotalAmount1,$dAmount,$sumAmounts);
        return $send;
    }

    public function createReceivedCustomer($RType,$RMethod,$amount,$rDate,$CstName,$CstCode,$desc,$bank,$pos,$serial,$chDate,$chOwner,$chOwnerC){
        $acm = new acm();
        if(!$acm->hasAccess('receivedCustomerManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $rDate = $ut->jal_to_greg($rDate);
        $chDate = (strlen(trim($chDate)) > 0 ? $ut->jal_to_greg($chDate) : '');
        $checkNumber = (intval($RMethod) !== 3 ? 'NULL' : 1);
        $sql = "INSERT INTO `customer_receive` (`receiveType`,`receiveMethod`,`checkNumber`,`receiveAmount`,`receiveDate`,`uid`,`depositor`,`codeTafzili`,`description`,`bankID`,`posID`,`checkDate`,`checkSerial`,`checkOwner`,`checkOwnerCode`)
                VALUES ({$RType},{$RMethod},{$checkNumber},{$amount},'{$rDate}',{$_SESSION['userid']},'{$CstName}',{$CstCode},'{$desc}',{$bank},{$pos},'{$chDate}','{$serial}','{$chOwner}','{$chOwnerC}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editReceivedCustomer($rid,$RType,$RMethod,$amount,$rDate,$CstName,$CstCode,$desc,$bank,$pos){
        $acm = new acm();
        if(!$acm->hasAccess('receivedCustomerManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $rDate = $ut->jal_to_greg($rDate);
        $sql = "UPDATE `customer_receive` SET `receiveType`={$RType},`receiveMethod`={$RMethod},
                `receiveAmount`={$amount},`receiveDate`='{$rDate}',`uid`={$_SESSION['userid']},`depositor`='{$CstName}',
                `codeTafzili`={$CstCode},`description`='{$desc}',`bankID`={$bank},`posID`={$pos} WHERE `RowID`={$rid}";
        $db->Query($sql);
        $res = $db->AffectedRows();
		
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if (intval($res)) {
            return true;
        }else {
            return false;
        }
    }

    public function receivedCustomerInfo($reid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `customer_receive` WHERE `RowID`=".$reid;
        $res = $db->ArrayQuery($sql);
        $receiveDate = (strtotime($res[0]['receiveDate']) > 0 ? $ut->greg_to_jal($res[0]['receiveDate']) : '');
        $checkDate = (strtotime($res[0]['checkDate']) > 0 ? $ut->greg_to_jal($res[0]['checkDate']) : '');
        if(count($res) == 1){
            $res = array("receiveType"=>$res[0]['receiveType'],"receiveMethod"=>$res[0]['receiveMethod'],
                         "receiveAmount"=>number_format($res[0]['receiveAmount']),"receiveDate"=>$receiveDate,
                         "depositor"=>$res[0]['depositor'],"codeTafzili"=>$res[0]['codeTafzili'],
                         "description"=>$res[0]['description'],"bankID"=>$res[0]['bankID'],"posID"=>$res[0]['posID'],
                         "checkDate"=>$checkDate,"checkSerial"=>$res[0]['checkSerial'],"checkOwner"=>$res[0]['checkOwner'],
                         "checkOwnerCode"=>$res[0]['checkOwnerCode']);
            return $res;
        }else{
            return false;
        }
    }

    private function getCompanyBanks(){
        $db = new DBi();
        $sql = "SELECT * FROM `company_banks`";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    private function getCompanyPos(){
        $db = new DBi();
        $sql = "SELECT * FROM `company_pos`";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function receivedCustomerCheckInfo($rcid){
        $acm = new acm();
        if(!$acm->hasAccess('receivedCustomerManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `checkSerial`,`checkDate`,`checkNumber`,`checkOwner`,`checkOwnerCode` FROM `customer_receive` WHERE `RowID`={$rcid}";
        $res = $db->ArrayQuery($sql);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoReceivedCustomer-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">سایر اطلاعات</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مقدار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $infoNames = array('شماره چک','تاریخ چک','تعداد چک','نام صاحب چک','کد ملی صاحب چک');
        for ($i=0;$i<5;$i++){
            $iterator++;
            $keyName = key($res[0]);
            if ($i==1){
                $res[0]["$keyName"] = (strtotime($res[0]["$keyName"]) > 0 ? $ut->greg_to_jal($res[0]["$keyName"]) : '');
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
        return $htm;
    }

    public function attachedReceivedCustomerFileHtm($reid){
        $db = new DBi();
        $sql = "SELECT `RowID`,`fileName`,`fileInfo` FROM `customer_receive_attachment` WHERE `crid`={$reid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileReceivedCustomer-tableID">';
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
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachReceivedCustomerFile('.$res[$i]['RowID'].')" ><i class="fas fa-trash"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function attachFileToReceivedCustomer($reid,$info,$files){
        $db = new DBi();
        $cDate = date('Y/m/d');
       // $cTime = date('H:i:s');
        //$cTime=date('H:i:s', strtotime('-1 hour'));
        $cTime=$this->current_time;

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
                $SFile[] = "attachRC" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $cnt = count($SFile);
        for ($i=0;$i<$cnt;$i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i],'../attachment/'.$SFile[$i]);
            $sql4 = "INSERT INTO `customer_receive_attachment` (`crid`,`fileName`,`fileInfo`,`createDate`,`createTime`,`uid`) VALUES ({$reid},'{$SFile[$i]}','{$info}','{$cDate}','{$cTime}',{$_SESSION['userid']})";
            $db->Query($sql4);
        }
        return true;
    }

    public function deleteAttachReceivedCustomerFile($fid){
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `customer_receive_attachment` WHERE `RowID`={$fid}";
        $res = $db->ArrayQuery($sql);
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/attachment/' . $res[0]['fileName'];
        $result = unlink($file_to_delete);
        if ($result) {
            $query = "DELETE FROM `customer_receive_attachment` WHERE `RowID`={$fid}";
            $db->Query($query);
            return true;
        }else{
            return false;
        }
    }

    public function attachFileReceivedCustomerHtm($crid){
        $db = new DBi();
        $sql = "SELECT `fileName`,`fileInfo` FROM `customer_receive_attachment` WHERE `crid`={$crid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileReceivedCustomer1-tableID">';
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
            $link = ADDR.'attachment/'.$res[$i]['fileName'];
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

    public function getCustomerReceiveComments($csDate,$ceDate,$toward,$cAccount,$cAmount){
        $db = new DBi();
        $ut = new Utility();
        $x = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $x[] = '`dDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $x[] = '`dDate` <="'.$ceDate.'" ';
        }
        $x[] = '`uid`=49 ';
        $sql = "SELECT `pid` FROM `deposit`";
        if(count($x) > 0){
            $where = implode(" AND ",$x);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $rids = array();
        for ($i=0;$i<$cnt;$i++){
            $rids[] = $res[$i]['pid'];
        }
        $rids = implode(',',$rids);

        $w = array();
        if(strlen(trim($toward)) > 0){
            $w[] = '`Toward` LIKE "%'.$toward.'%" ';
        }
        if(strlen(trim($cAccount)) > 0){
            $w[] = '`accName` LIKE "%'.$cAccount.'%" ';
        }
        if(strlen(trim($cAmount)) > 0){
            $w[] = '`Amount`='.$cAmount.' ';
        }
        $w[] = '`RowID` IN ('.$rids.') ';
        $w[] = '`isEnable`=1 ';

        $sql1 = "SELECT * FROM `pay_comment`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql1 .= " WHERE ".$where;
        }
        $res1 = $db->ArrayQuery($sql1);
        $cnt = count($res1);
        $iterator = 0;

        $htm = '';
        $htm .= '<form class="form-inline" style="margin: 20px 0;">';

        $htm .= '<div id="customerReceiveTowardSearch-div" >';
        $htm .= '<label class="sr-only" for="customerReceiveTowardSearch">بابت</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="customerReceiveTowardSearch" autocomplete="off" style="width: 150px;" placeholder="بابت" >';
        $htm .= '</div>';

        $htm .= '<div id="customerReceiveAccountSearch-div" >';
        $htm .= '<label class="sr-only" for="customerReceiveAccountSearch">طرف مقابل</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="customerReceiveAccountSearch" autocomplete="off" style="width: 200px;" placeholder="طرف مقابل" >';
        $htm .= '</div>';

        $htm .= '<div id="customerReceiveAmountSearch-div" >';
        $htm .= '<label class="sr-only" for="customerReceiveAmountSearch">مبلغ کل</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="customerReceiveAmountSearch" autocomplete="off" style="width: 150px;" placeholder="مبلغ کل" onkeyup=addSeprator()>';
        $htm .= '</div>';

        $htm .= '<button type="button" class="btn btn-sm btn-secondary mb-2 mr-2" style="padding: 0.25rem 0.5rem;" onclick="showCustomerReceiveComments()">جستجو&nbsp;&nbsp;<i class="fa fa-search"></i></button>';

        $htm .= '</form>';

        $htm .= '<table class="table table-bordered table-hover table-sm" id="customerReceiveComments-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 4%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">تاریخ ثبت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">در وجه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">بابت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">مبلغ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">وضعیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">واریزی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">چک</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">سایر اطلاعات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">فایل پیوست</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">گردش کار</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">نمایش</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            switch ($res1[$i]['transfer']){
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
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res1[$i]['cDate']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['accName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['Toward'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res1[$i]['Amount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$status.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showDepositsInFinancialList('.$res1[$i]['RowID'].')" ><i class="fas fa-dollar-sign"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowCommentCheckInCustomerReceive('.$res1[$i]['RowID'].')" ><i class="fas fa-money-check-alt"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowOtherInfoComment('.$res1[$i]['RowID'].')" ><i class="fas fa-tv"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowAttachmentFileComment('.$res1[$i]['RowID'].')" ><i class="fas fa-link"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowWorkflowComment('.$res1[$i]['RowID'].')" ><i class="fas fa-sitemap"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="printPayComment('.$res1[$i]['RowID'].')" ><i class="fas fa-search"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    function get_vat_pay_comment($cid){
      $db = new DBi();
      $ut = new Utility();
      $vat_expert=$ut->get_full_access_users(8)[0];
      //$sql="select related_vat from pay_comment where RowID={$cid}";
      $sql = "SELECT p.related_vat  FROM pay_comment AS p 
            WHERE   p.RowID={$cid}";
        //$ut->fileRecorder($sql);

      $res=$db->ArrayQuery($sql);
      if($res[0]['related_vat']==1){
          $workflow_sql="select receiver from payment_workflow where pid=${cid} AND receiver={$vat_expert}";
         
          $res2=$db->ArrayQuery($workflow_sql);
          if(count($res2)>0){
             return 0;
          }
          else{
               return 1;
            //  return $res_array=['related_vat']=$res[0]['related_vat'];
          }
      }
     
    }

    function get_related_to_barnameh_pay_comment($cid){
        $db = new DBi();
        $ut = new Utility();
        //$vat_expert=$ut->get_full_access_users(8)[0];
        //$sql="select related_vat from pay_comment where RowID={$cid}";
        $sql = "SELECT pm.`key`,pm.`value`  FROM `pay_comment` AS p
        LEFT JOIN `pay_comment_meta` as pm on pm.`pay_comment_id`=p.`RowID`
                    WHERE   p.`RowID`={$cid}";
        $ut->fileRecorder($sql);            
        $res=$db->ArrayQuery($sql);
        if($res[0]['key']=='is_drivers_fare' && $res[0]['value']=='1'){
            $res_array['related_barnameh']=1;
        }
        else{
            $res_array['related_barnameh']=0;
        }
        return  $res_array['related_barnameh'];
      }
    //++++++++++++++++++++ مدیریت قراردادها ++++++++++++++++++++
      public function has_addendum($contract_id){
        $db=new DBi();
        $sql="SELECT `contract_id` from  `contract_addendum` where status=1 AND `contract_id`={$contract_id}";
        $res=$db->ArrayQuery($sql);
        return (count($res));
      }

    public function getContractManageList($csDate,$ceDate,$cNum,$cAccount,$cAmount,$credit,$status,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('contractManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`csDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`ceDate` <="'.$ceDate.'" ';
        }
        if(strlen(trim($cNum)) > 0){
            $w[] = '`number` LIKE "%'.$cNum.'%" ';
        }
        if(strlen(trim($cAccount)) > 0){
            $w[] = '`accountName` LIKE "%'.$cAccount.'%" ';
        }
        if(strlen(trim($cAmount)) > 0){
            $w[] = '`totalAmount`='.$cAmount.' ';
        }
        if(strlen(trim($credit)) > 0){
            $w[] = '`creditPeriod`='.$credit.' ';
        }
        $w[] = '`isEnable`='.$status.' ';

        $sql = "SELECT * FROM `contract`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $has_addendum=$this->has_addendum($res[$y]['RowID']);
           
            $finalRes[$y]['contractType'] = ($res[$y]['contractType'] == 0 ? 'عادی' : 'ساعتی');
            $finalRes[$y]['hourAmount'] = (intval($res[$y]['hourAmount']) > 0 ? number_format($res[$y]['hourAmount']).' ریال' : '');
            $finalRes[$y]['maxHour'] = (intval($res[$y]['maxHour']) > 0 ? $res[$y]['maxHour'] : '');
            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['number'] = $res[$y]['number'];
            $finalRes[$y]['accountName'] = $res[$y]['accountName'];
            $finalRes[$y]['monthlyAmount'] = (intval($res[$y]['monthlyAmount']) > 0 ? number_format($res[$y]['monthlyAmount']).' ریال' : '');
            $finalRes[$y]['totalAmount'] = number_format($res[$y]['totalAmount']).' ریال';
            $finalRes[$y]['csDate'] = (strtotime($res[$y]['csDate']) ? $ut->greg_to_jal($res[$y]['csDate']) : '');
            $finalRes[$y]['ceDate'] = (strtotime($res[$y]['ceDate']) ? $ut->greg_to_jal($res[$y]['ceDate']) : '');
            $finalRes[$y]['creditPeriod'] = $res[$y]['creditPeriod'].' ماه';
            $finalRes[$y]['subject'] = $res[$y]['subject'];
            $finalRes[$y]['bgColor'] = $has_addendum>0?"bg-warning-light":"bg-secondary-light";
        }
        $ut->fileRecorder($finalRes);
        return $finalRes;
    }

    public function getContractManageListCountRows($csDate,$ceDate,$cNum,$cAccount,$cAmount,$credit,$status){
        $ut = new Utility();
        $db = new DBi();

        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`csDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`ceDate` <="'.$ceDate.'" ';
        }
        if(strlen(trim($cNum)) > 0){
            $w[] = '`number` LIKE "%'.$cNum.'%" ';
        }
        if(strlen(trim($cAccount)) > 0){
            $w[] = '`accountName` LIKE "%'.$cAccount.'%" ';
        }
        if(strlen(trim($cAmount)) > 0){
            $w[] = '`totalAmount`='.$cAmount.' ';
        }
        if(strlen(trim($credit)) > 0){
            $w[] = '`creditPeriod`='.$credit.' ';
        }
        $w[] = '`isEnable`='.$status.' ';

        $sql = "SELECT `RowID` FROM `contract`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function contractInfo($cid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `contract` WHERE `RowID`=".$cid;
        $res = $db->ArrayQuery($sql);
        $csDate = (strtotime($res[0]['csDate']) > 0 ? $ut->greg_to_jal($res[0]['csDate']) : '');
        $ceDate = (strtotime($res[0]['ceDate']) > 0 ? $ut->greg_to_jal($res[0]['ceDate']) : '');
        $contract_result=[];
        if(count($res) == 1){
            $contract_result[0] = array("number"=>$res[0]['number'],"accountName"=>$res[0]['accountName'],
                         "totalAmount"=>number_format($res[0]['totalAmount']),"csDate"=>$csDate,
                         "ceDate"=>$ceDate,"creditPeriod"=>$res[0]['creditPeriod'],
                         "subject"=>$res[0]['subject'],"codeTafzili"=>$res[0]['codeTafzili'],
                         "accNum"=>$res[0]['accNum'],"hourAmount"=>number_format($res[0]['hourAmount']),
                         "maxHour"=>$res[0]['maxHour'],"contractType"=>$res[0]['contractType'],'pay_method_type'=>$res[0]['pay_method_type'],'has_payment_formula'=>$res[0]['has_payment_formula']);
        }else{
            return false;
        }
       /* $sql_f="SELECT RowID,amount_pay_part,description_pay_part,percentage_increase_allowable_temperature,pid FROM contract_pay_formula WHERE contract_id={$cid} AND status IN (1,2)";
        $f_res=$db->ArrayQuery($sql_f);
        $payed_id_array=[];
        foreach($f_res as $f_row){
            $payed_id_array[]=$f_row['pid'];

        }

        $get_pay_comemts="SELECT p.`RowID`,p.`totalAmount`,p.`unCode` FROM `pay_comment` as p LEFT JOIN  `contract` as c on c.`number`=p.`contractNumber` 
                            WHERE c.`RowID`={$cid}";
        $payed_row=$db->ArrayQuery($get_pay_comemts);  
       
        foreach($payed_row as $row){
         
            if($row['RowID']>0){
                $counter=1;
                if(!in_array($row['RowID'],$payed_id_array)){
                    $query="INSERT INTO contract_pay_formula (contract_id,amount_pay_part,description_pay_part,`status`,CEO_confirm,pid)
                    VALUES('{$cid}','{$row['totalAmount']}','پرداخت ماه ',2,1,'{$row['RowID']}')";
                    $i_res=$db->Query($query);
                }
                $counter++;
            }
        }
                     
        $contract_result[1]=$f_res;*/
        
        return $contract_result;
    }

    public function contractType($cid){
        $db = new DBi();
        $sql = "SELECT `contractType` FROM `contract` WHERE `RowID`=".$cid;
        return $db->ArrayQuery($sql);
    }

    public function sendAddendum($addendum_id,$addendum_status){
        $db = new DBi();
        $ut = new Utility();
        $addendum_creator = "SELECT `user_id` FROM `contract_addendum` where `status`= 1 AND RowID = {$addendum_id}";
        $user_res = $db->ArrayQuery($addendum_creator);
        $user_creator = $user_res[0]['user_id'];

        switch(intval($addendum_status)){
            case 0:
                $receiver=$ut->get_full_access_users(20);

                break;
            case 1:
                $receiver=$ut->get_full_access_users(21);
                $receiver[]=$user_creator;

                break;
                
            case 2:
                $receiver=$ut->get_full_access_users(20);
                $receiver[]=$user_creator;

                break;

        }
        $ut->fileRecorder($receiver);
        $final_receivers=[];
        foreach($receiver as $value){
            $handler_array=[];
            $handler_array['RowID']=$value;
            $handler_array['receiver_name']=$ut->get_user_fullname($value);
            $final_receivers[]=$handler_array;
          
        }
        return $final_receivers;
    }

    public function do_send_addendum($addendum_id,$addendum_status,$confirm_status,$receiver,$addendum_message){
        $ut = new Utility();
        $db = new DBi();
        $new_addendum_status=$addendum_status;
        if($confirm_status==1){
            switch(intval($addendum_status)){
                case 0:
                    $new_addendum_status = 1;
                    break;
                case 1:
                    $new_addendum_status = 2;
                    break;
                case 2:
                    $new_addendum_status = 3;
                    break;
            }
        }
        $addendum_sql="UPDATE `contract_addendum` set last_receiver = {$receiver},addendum_status='{$new_addendum_status}' WHERE `RowID` = {$addendum_id}";
        $addendum_update_res=$db->Query($addendum_sql);
        $current_date=date('Y-m-d');
        if($addendum_update_res){
            $insert_addendum_workflow="INSERT INTO `contract_addendum_workflow` (`sender`,`receiver`,`addendum_id`,`status`,`createDate`,`createTime`,`description`) 
                                    VALUES('{$_SESSION['userid']}','{$receiver}','{$addendum_id}','{$confirm_status}','{$current_date}','{$this->current_time}','{$addendum_message}')";
            $insert_res=$db->Query($insert_addendum_workflow);
            if($insert_res){
                return $new_addendum_status;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function showAddendumSendHistory($addendum_id){
        $ut=new Utility();
        $db=new DBi();
        $sql="SELECT * FROM `contract_addendum_workflow` where `addendum_id`={$addendum_id}";
        $res=$db->ArrayQuery($sql);
        if(count($res)>0){
            $counter=1;
            $html.="<table border='1' class='table table-borderd table-striped'>
                        <tr>
                            <td>ردیف</td>
                            <td>ارسال کننده</td>
                            <td>دریافت کننده</td>
                            <td>وضعیت</td>
                            <td>تاریخ ارسال</td>
                            <td>زمان ارسال</td>
                            <td>توضیحات</td>
                        </tr>";
            foreach($res as $key=>$value){
                $html.="<tr>
                            <td>".$counter."</td>
                            <td>".$ut->get_user_fullname($value['sender'])."</td>
                            <td>".$ut->get_user_fullname($value['receiver'])."</td>
                            <td>".($value['status']==1?'تایید':'عدم تایید')."</td>
                            <td>".$ut->greg_to_jal($value['createDate'])."</td>
                            <td>".$value['createTime']."</td>
                            <td>".$value['description']."</td>
                            
                        </tr>";
            } 
            $html.="</table>";
            $counter++;
        }
        else{
            $html="<p class='p-4 text-center text-danger'>فاقد گردش</p>";
        }

        return $html;
    }

    public function get_all_contract(){
        $acm=new acm();
        $db = new DBi();
        $ut = new Utility();
        //$user_id=$_SESSION['userid'];
        if($acm->hasAccess('editCreateContractAddendum')){
            $sql="select * from contract where isEnable=1";
            $res=$db->ArrayQuery($sql);
            $final_result=[];
            foreach($res as $key=>$value){
                $handler_array=[];
                $handler_array['RowID']=$value['RowID'];
                $handler_array['contract_info']=$value['number']." - ".$value['accountName']." - ".$value['subject'];
                $final_result[]=$handler_array;

            }
        }
     else
        {
            $result=0;
            return $result;
        }
        
        return $final_result;
    }

    public function getContractAddendumDetailes($cid)
    {
        $db = new DBi();
        $ut = new Utility();
        $expert_legal_user_id=$ut->get_full_access_users(20);
        $ceo_user_id=$ut->get_full_access_users(21);
        $is_expert_legal=in_array($_SESSION['userid'],$expert_legal_user_id)?1:0;
        $is_ceo=in_array($_SESSION['userid'],$ceo_user_id)?1:0;
        if($is_expert_legal==1){
            $getAddendumDetailes = "SELECT ca.*,c.`number`,c.`accountName`,c.`subject` FROM `contract_addendum` as ca 
                left join `contract` as c on ca.`contract_id`=c.`RowID` 
                WHERE  `status`=1 AND ca.`addendum_status`=1 or (ca.`last_receiver`={$_SESSION['userid']} OR ca.`last_receiver`=0) ORDER BY ca.`RowID` DESC ";
        }
        elseif($is_ceo==1){
            $getAddendumDetailes = "SELECT ca.*,c.`number`,c.`accountName`,c.`subject` FROM `contract_addendum` as ca 
                left join `contract` as c on ca.`contract_id`=c.`RowID`
                WHERE  ca.`status`=1 AND ca.`addendum_status`=2 or (ca.`last_receiver`={$_SESSION['userid']} OR ca.`last_receiver`=0) ORDER BY ca.`RowID` DESC";
        }
        else{

            $getAddendumDetailes = "SELECT ca.*, c.`number`, c.`accountName`, c.`subject` FROM `contract_addendum` as ca 
                left join `contract` as c on ca.`contract_id`=c.`RowID`
                WHERE ca.`status`=1 or ca.`user_id`={$_SESSION['userid']} OR ca.`last_receiver`=0  ORDER BY ca.`RowID` DESC";
        }
        
        $tablehtml = "";
        $ut->fileRecorder($getAddendumDetailes);
        $res = $db->ArrayQuery($getAddendumDetailes);
        $ut->fileRecorder($getAddendumDetailes);
        
        $not_confirm_addendum0_count=0;
        $not_confirm_addendum1_count=0;
        $not_confirm_addendum2_count=0;
        $not_confirm_addendum3_count=0;
        if(count($res)>0){
            for($i=0;$i<count($res);$i++){
                $conf_status=intval($res[$i]['addendum_status']);
                switch($conf_status){
                    case 0:
                        $status_text=" در انتظار ارسال به واحد حقوقی";
                        $class="text-danger";
                        $not_confirm_addendum0_count++;
                        break;
                    case 1:
                        $status_text=" در انتظار تایید واحد حقوقی";
                        $class="text-warning";
                        $not_confirm_addendum1_count++;
                        break;
                    case 2:
                        $status_text=" در انتظار تایید  نهایی";
                        $class="text-info";
                        $not_confirm_addendum2_count++;
                        break;
                    case 3:
                        $status_text=" تایید نهایی";
                        $class="text-success";
                        $not_confirm_addendum3_count++;
                        break;
                }
              
                $addendum_status = "<span class='{$class}'>{$status_text}</span>";
                
                $tablehtml.='<tr>
                            <td class="text-center" style="width:3%">'.($i+1).'</td>
                            <td class="text-center" style="width:9%"><a href="#" onclick="open_contract_detailes('.$res[$i]['contract_id'].')">'.$res[$i]['number'].'</a></td>
                            <td class="text-center" style="width:9%">'.$res[$i]['accountName'].'</td>
                            <td class="text-center" style="width:15%">'.$res[$i]['subject'].'</td>
                            <td class="text-center" style="width:25%">'.$res[$i]['Addendum_duty'].'</td>
                            <td class="text-center" style="width:8%">'.number_format($res[$i]['Addendum_price']).' ریال </td>
                            <td class="text-center" style="width:5%">'.$ut->greg_to_jal($res[$i]['Addendum_date']).'</td>
                            <td class="text-center" style="width:10%">'.$ut->get_user_fullname($res[$i]['user_id']).'</td>
                            <td class="text-center" style="width:8%">'.$addendum_status.'</td>
                            <td class="text-center" style="width:8%"> 
                                <div style="width:8%;display:flex;justify-content:space-between;align-items:center">';
                if($res[$i]['user_id'] == $res[$i]['last_receiver']){
                    if($res[$i]['addendum_status']==0){
                        $tablehtml.='<i class="btn btn-danger fa fa-trash pointer" onclick="confirmDelete('.$res[$i]['RowID'].')"></i>
                                    <i class="btn btn-success fa fa-edit pointer" onclick="updateAddendum('.$res[$i]['RowID'].')"></i>
                                    <i class="btn btn-primary fa fa-paper-plane pointer" onclick="sendAddendum('.$res[$i]['RowID'].','.$res[$i]['addendum_status'].')"></i>';
                               
                    }
                    else{
                        $tablehtml.='
                                    <i class="btn btn-info fa fa-history pointer" onclick="sendAddendumHistory('.$res[$i]['RowID'].')"></i>';
                    }
                }
                else{
                    if($_SESSION['userid']==$res[$i]['last_receiver']){
                        $tablehtml.='
                                    <i class="btn btn-info fa fa-paper-plane pointer" onclick="sendAddendum('.$res[$i]['RowID'].','.$conf_status.')"></i>';
                    }
                    else
                    {
                        $tablehtml.='
                        <i class="btn btn-primary fa fa-history pointer" onclick="sendAddendumHistory('.$res[$i]['RowID'].','.$conf_status.')"></i>';
                    }
                }       

                           
                $tablehtml.='</div></td></tr>';
            }
            if($not_confirm_addendum0_count>0){
                $alert_html="<p class='pr-4 text-danger pr-2'> شما  تعداد <span class='p-2 blinker'>{$not_confirm_addendum0_count}</span>  الحاقیه  در انتظار ارسال به واحد حقوقی دارید  جهت تعیین تکلیف  الحاقیه ها را  ارسال نمایید</p>";
            }
            elseif($not_confirm_addendum1_count>0){
                $alert_html.="<p class='pr-4 text-warning'> شما  تعداد <span class='p-2 blinker'>{$not_confirm_addendum1_count}</span>  الحاقیه  در انتظار ارسال به مدیریت محترم عامل دارید  جهت تعیین تکلیف  الحاقیه ها را  ارسال نمایید</p>";
            }
            elseif($not_confirm_addendum2_count>0){

                $alert_html.="<p class='pr-4 text-info'> شما  تعداد <span class='p-2 blinker'>{$not_confirm_addendum2_count}</span>  الحاقیه  در انتظار تایید نهایی دارید  جهت تعیین تکلیف  الحاقیه ها را  تایید نمایید</p>";
            }
            else{
                $alert_html="";
            }

            $html="";
            $html.=
                '<div>'.$alert_html.'<p class="p-4 text-info">جهت مشاهده جزییات قرارداد بر روی شماره قرارداد کلیک نمایید</p> </div>
                <table id="addendum_detailes_tbl" class=" w-100 table table-bordered table-striped">
                    <thead>
                        <tr>
                            <td class="text-center text-light" style="width:3%">#</td>
                            <td class="text-center text-light" style="width:9%"> شماره قرارداد</td>
                            <td class="text-center text-light" style="width:9%"> طرف حساب</td>
                            <td class="text-center text-light" style="width:15%"> موضوع قرارداد</td>
                            <td class="text-center text-light" style="width:25%">شرح مسئولیت الحاقیه</td>
                            <td class="text-center text-light" style="width:8%"> ارزش مالی الحاقیه</td>
                            <td class="text-center text-light" style="width:5%">تاریخ ثبت الحاقیه</td>
                            <td class="text-center text-light" style="width:10%">کاربر ثبت کننده</td>
                            <td class="text-center text-light" style="width:8%">  وضعیت</td>
                            <td class="text-center text-light" style="width:8%">عملیات</td>
                        </tr>
                    </thead>
                    <tbody>';
            $html.=$tablehtml;       
            $html.= '</tbody>
                </table>';
                return $html;
        }
        else{
            return false;
        }
    }
    public function updateAddendumContract($addendum_id){
        $db=new DBi();
        $ut=new Utility();
        $selectAddendum="Select * from  `contract_addendum` where RowId={$addendum_id}";
        $result = $db->ArrayQuery($selectAddendum);
        if(count($result)>0){
            $result[0]['Addendum_date']=$ut->greg_to_jal($result[0]['Addendum_date']);
            return $result;
        }
        else{
            return false;
        }
    }

    public function deleteAddendumContract($addendum_id){
        $db=new DBi();
        $deleteAddendum="UPDATE `contract_addendum` SET `status`=0 where RowID={$addendum_id}";
        $db->Query($deleteAddendum);
        if($db->AffectedRows()==1){
            return true;

        }
        else{
            return false;
        }

    }
    public function editCreateContractAddendum($addendumDuty,$addendumCost,$addendumDate,$contract_id,$addendum_id){
        $acm = new acm();
        if(!$acm->hasAccess('contractManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $legal_receiver = 55;
        $addendumDate = $ut->jal_to_greg($addendumDate);
        $addendum_flag = "";
        if(empty($send_comment)){
            $send_comment = "ارسال الحاقیه  جهت تایید به واحد حقوقی";

        }
        if($addendum_id==0)
        {
            $insertSql="INSERT INTO `contract_addendum` ( `contract_id`,`Addendum_duty`,`Addendum_price`,`Addendum_date`,`user_id`,`last_receiver`)VALUES({$contract_id},'{$addendumDuty}',{$addendumCost},'{$addendumDate}',{$_SESSION['userid']},{$_SESSION['userid']})";
            $db->Query($insertSql);
            $res=$db->InsertrdID();
            if($res){
                $addendum_flag="insert";
            }
        }
        else{
            $UpdateSql="UPDATE `contract_addendum` SET `Addendum_duty`='{$addendumDuty}',`Addendum_price`={$addendumCost},`Addendum_date`='{$addendumDate}',`user_id`={$_SESSION['userid']} WHERE RowID={$addendum_id}";
            $db->Query($UpdateSql);
            $res=$db->AffectedRows();
            if($res>0){
                $addendum_flag = "update";
            }
        }
        return $addendum_flag;
    }

    // public function check_contract_pay_rows(){

    // }
    public function createContract($number,$accName,$code,$accNum,$amount,$credit,$sDate,$eDate,$subject,$cType,$HourAmount,$MaxHour,$formula_array,$pay_method_type,$has_contract_formula){
        $acm = new acm();
        if(!$acm->hasAccess('contractManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $sDate = (strlen(trim($sDate)) > 0 ? $ut->jal_to_greg($sDate) : '');
        $eDate = (strlen(trim($eDate)) > 0 ? $ut->jal_to_greg($eDate) : '');

        if (intval($cType) == 1){ // ساعتی
            $amount = intval($credit) * (intval($MaxHour) * intval($HourAmount));
            $monthlyAmount = intval($MaxHour) * intval($HourAmount);
        }else{
            $HourAmount = 0;
            $MaxHour = 0;
            $monthlyAmount = 0;
        }

        $total_pay_rows=0;
        foreach($formula_array as $row){
            $total_pay_rows+=$row['pay_amount'];
        }
        if(intval($amount)<intval($total_pay_rows)){
            
            return -1;
        }

        $sql = "INSERT INTO `contract` (`number`,`accountName`,`totalAmount`,`creditPeriod`,`csDate`,`ceDate`,`subject`,`codeTafzili`,`accNum`,`uid`,`contractType`,`hourAmount`,`maxHour`,`monthlyAmount`,`has_payment_formula`,`pay_method_type`) VALUES ('{$number}','{$accName}',{$amount},{$credit},'{$sDate}','{$eDate}','{$subject}',{$code},'{$accNum}',{$_SESSION['userid']},{$cType},{$HourAmount},{$MaxHour},{$monthlyAmount},{$has_contract_formula},{$pay_method_type})";
        $res = $db->Query($sql);
		//$ut->fileRecorder($sql);
        if (intval($res) > 0)
        {
            //return true;
            $get_last_insert_id="SELECT RowID FROM `contract` ORDER BY RowID DESC  LIMIT 1";
            $last_id_res=$db->ArrayQuery($get_last_insert_id);
            $last_id=$last_id_res[0]['RowID'];
            $values="";
            if(count($formula_array)>0)
            {
                foreach($formula_array as $row)
                {
                    $history=array("create_date"=>date("Y-m-d H:i:s"),'user'=>$_SESSION['userid'],"transaction_type"=>"Insert");
                    $history_json=json_encode($history,JSON_UNESCAPED_UNICODE);
                    $values.= "('{$last_id}','{$row['pay_amount']}','{$row['pay_desc']}','{$row['pay_percent']}','{$history_json}','{$_SESSION['userid']}'),";
                }
                $values=rtrim($values,",");
                $insert_contract_formula=
                    "INSERT INTO `contract_pay_formula`
                        (`contract_id`,`amount_pay_part`,`description_pay_part`,`percentage_increase_allowable_temperature`,`history`,`user_id`)VALUES{$values}";
				//$ut->fileRecorder($insert_contract_formula);
                $result=$db->Query($insert_contract_formula);
                if($result)
                {
                    return 1;
                }
                else{
                    return 0;
                }
            }
            else
            {
                return 1;
            }

        }else{
            return 0;
        }
    }

    public function editContract($cid,$number,$accName,$code,$accNum,$amount,$credit,$sDate,$eDate,$subject,$cType,$HourAmount,$MaxHour,$formula_array,$pay_method_type,$has_payment_formula)
    {
        $acm = new acm();
        if(!$acm->hasAccess('contractManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $sDate = (strlen(trim($sDate)) > 0 ? $ut->jal_to_greg($sDate) : '');
        $eDate = (strlen(trim($eDate)) > 0 ? $ut->jal_to_greg($eDate) : '');

        if (intval($cType) == 1){ // ساعتی
            $amount = intval($credit) * (intval($MaxHour) * intval($HourAmount));
            $monthlyAmount = intval($MaxHour) * intval($HourAmount);
        }
        else
        {
            $HourAmount = 0;
            $MaxHour = 0;
            $monthlyAmount = 0;
        }
        $total_pay_row=0;
        foreach($formula_array as $row){
            $total_pay_row+=$row['pay_amount'];
        }

        if($amount<$total_pay_row){
            return -10;
        }

        $query = "SELECT `RowID` FROM `pay_comment` WHERE `contractNumber`='{$number}' AND isEnable=1 ";
        $result = $db->ArrayQuery($query);
        $sqll = "SELECT `uid`,`has_payment_formula` FROM `contract` WHERE `RowID`={$cid}";
        $res1 = $db->ArrayQuery($sqll);
        $has_payment_formula_old=$res1[0]['has_payment_formula'];
        if (count($result) > 0)//قرارداد اظهارنظر ثبت شده دارد
        {
            if($pay_method_type==1){
                $sql="UPDATE `contract` SET  `pay_method_type`='{$pay_method_type}',`has_payment_formula`={$has_payment_formula} WHERE `RowID`={$cid}";
                $res=$db->Query($sql);
                if(!$res){
                   return -1;
                }
            }
        }
        else
        {
            $sql = "UPDATE `contract` SET `number`='{$number}',`accountName`='{$accName}',`totalAmount`={$amount},
                `creditPeriod`={$credit},`csDate`='{$sDate}',`ceDate`='{$eDate}',`subject`='{$subject}',
                `codeTafzili`={$code},`accNum`='{$accNum}',`contractType`={$cType},`hourAmount`={$HourAmount},
                `maxHour`={$MaxHour},`monthlyAmount`={$monthlyAmount},`pay_method_type`='{$pay_method_type}',`has_payment_formula`='{$has_payment_formula}' WHERE `RowID`={$cid}";
           $res= $db->Query($sql);
           
            if (!$res) {
              
                return -3;
            }
        }

        if(count($formula_array)>0)
        {
            $total_pay_rows=0;
            $get_formula_sql="SELECT * FROM `contract_pay_formula` WHERE contract_id={$cid} AND `status`=1";
            $formula_res=$db->ArrayQuery($get_formula_sql);
            $formula_array_detailes=[];
            foreach($formula_res as $f_row){
                $formula_array_detailes[$f_row['RowID']]=$f_row;
            }
            $values="";
            foreach($formula_array as $row)
            {
                if(intval($row['pay_RowID'])>0)
                {
                    $update_sql="UPDATE `contract_pay_formula` SET 
                                    `amount_pay_part`='{$row['pay_amount']}',
                                    `description_pay_part`='{$row['pay_desc']}',
                                    `percentage_increase_allowable_temperature`='{$row['pay_percent']}' WHERE RowID={$row['pay_RowID']}";            
                    $update_res=$db->Query($update_sql);
                    //$ut->fileRecorder($update_sql);
                    if(!$update_res){
                
                        return -1;
                    }
                    else
                    {
                        $res_aff = $db->AffectedRows();
                        $res_aff = (($res_aff == -1 || $res_aff == 0) ? 0 : 1);
                        if($res_aff){
                         
                            $index=$row['pay_RowID'];
                            $history=$formula_array_detailes[$index]['history'];
                            $history=json_decode($history,true);
                        
                            unset($formula_array_detailes[$row['pay_RowID']]['history']);
                            $last_data=$formula_array_detailes[$row['pay_RowID']];
                            $history[count($history)]=array("update_date"=>date("Y-m-d H:i:s"),'user'=>$_SESSION['userid'],"transaction_type"=>"Update","last_data"=>$last_data);
                      
                            $history=json_encode($history,JSON_UNESCAPED_UNICODE);
                         
                            $history_update="UPDATE `contract_pay_formula` SET `history`='{$history}' WHERE RowID={$row['pay_RowID']}";
                            $result3=$db->Query($history_update);
                            if(!$result3){
                                return -2;
                            }
                        }
                        else{
                           
                        }
                    } 
                }
                else
                {
                    $history=array("create_date"=>date("Y-m-d H:i:s"),'user'=>$_SESSION['userid'],"transaction_type"=>"Insert");
                    $history_json=json_encode($history,JSON_UNESCAPED_UNICODE);
                    $values.= "('{$cid}','{$row['pay_amount']}','{$row['pay_desc']}','{$row['pay_percent']}','{$history_json}'),";
                }
            }
            if($values){
                $values=rtrim($values,',');
                $insert_sql="INSERT INTO `contract_pay_formula`
                (`contract_id`,`amount_pay_part`,`description_pay_part`,`percentage_increase_allowable_temperature`,`history`)VALUES
                {$values}";
                
                $res_insert=$db->Query($insert_sql);
                if(!$res_insert){
                    return -3;
                }
                return 1;
            }
            return 1;
        }
        else{
            return 1;
        }
    }

    public function delete_contract_formula_row($RowID){ 
        $acm=new acm();
        if(!$acm->hasAccess('contractManagement')){
            die("access denied");
            exit;
        }
        $db=new DBi();
        $delete_row_sql="SELECT pid FROM contract_pay_formula where RowID={$RowID}"; //بررسی اینکه آیا برای  این ردیف اظهار نظر ثبت شده است ؟
        $result=$db->ArrayQuery($delete_row_sql);
        $pid=$result[0]['pid'];

        if(!empty($pid)){
            return -1;
        }
        else
        {
            $sql="UPDATE  contract_pay_formula SET `status`=0 WHERE RowID={$RowID}";
            $res=$db->Query($sql);
            if($res){
                return 1;
            }
            else{
                return 0;
            }
        }

    }
    public function archiveExtensionContract($cid,$cType,$cntType,$desc,$hourAmount,$maxHour,$amount,$eDate,$credit){
        $acm = new acm();
        if(!$acm->hasAccess('contractManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();

        $query = "SELECT `isEnable`,`ceDate` FROM `contract` WHERE `RowID`={$cid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['isEnable']) == 0 ){
            $res = "این قرارداد قبلا بایگانی شده است و امکان تغییر ندارد !!!";
            $out = "false";
            response($res,$out);
            exit;
        }
        if (strlen(trim($eDate)) > 0) {
            $eDate = $ut->jal_to_greg($eDate);
            if (strtotime($eDate) <= strtotime($rst[0]['ceDate'])) {
                $res = "تاریخ تمدید نا معتبر است !!!";
                $out = "false";
                response($res, $out);
                exit;
            }
        }

        $nowDate = date('Y-m-d');
       
       // $nowTime = date('H:i:s');
       //$nowTime = date('H:i:s',strtotime("-1 hour"));
        $nowTime = $this->current_time;

        if (intval($cType) == 0){  // بایگانی بود
            $isEnable = 0;
        }else{  // تمدید بود
            $isEnable = 1;
            $m = array();
            $m[] = "`creditPeriod`={$credit}";
            $m[] = "`ceDate`='{$eDate}'";
            if (intval($cntType) == 0){  // عادی باشد
                if (strlen(trim($amount)) > 0){
                    $m[] = "`totalAmount`={$amount}";
                }
            }else{  // ساعتی
                if (strlen(trim($hourAmount)) > 0){
                    $monthlyAmount = intval($maxHour) * intval($hourAmount);
                    $totalAmount = intval($maxHour) * intval($hourAmount) * intval($credit);
                    $m[] = "`hourAmount`={$hourAmount}";
                    $m[] = "`maxHour`={$maxHour}";
                    $m[] = "`monthlyAmount`={$monthlyAmount}";
                    $m[] = "`totalAmount`={$totalAmount}";
                }
            }
            $m = implode(',',$m).',';
        }

        $sql = "UPDATE `contract` SET ".$m." `archiveDescription`='{$desc}',`archiveDate`='{$nowDate}',`archiveTime`='{$nowTime}',`archiveUid`={$_SESSION['userid']},`isEnable`={$isEnable} WHERE `RowID`=".$cid;
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    public function OtherInfoContractHTM($cid){
        $acm = new acm();
        if(!$acm->hasAccess('contractManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
       
        $sql_addendum="SELECT * FROM `contract_addendum` WHERE `contract_id`={$cid} AND `status`=1 AND `addendum_status` = 3";
        $sql_addendum_res=$db->ArrayQuery($sql_addendum);
        if(count($sql_addendum_res)>0){
            $addendum_html="<table border='1' class='table table-borderd'>";
            $addendum_html.="<tr><td colspan='6' >
                                    <div class='row w-100'>
                                        <div class='col-md-12 text-center'> الحاقیه های ثبت شده </div>
                                    </div>
                                </td>
                            </tr>";
            $addendum_html.="<tr class='bg-info text-light'><td>ردیف</td><td>شرح الحاقیه</td><td>مبلغ الحاقیه</td><td>کاربر ثبت کننده</td><td>تاریخ ثبت</td><td>وضعیت</td></tr>";
            $addendum_counter = 1;
            foreach($sql_addendum_res as $key=>$value){
                if($value['addendum_status'] == 3){
                    $style = "background:#c0ebc9";
                    $status="تایید شده";
                }
                elseif($value['addendum_status'] == 2){
                   
                    $style = "background:#ebc0c4";
                    $status="منتظر تایید نهایی";
                }
                elseif($value['addendum_status'] == 1){
                   
                    $style = "background:#ebc0c4";
                    $status="منتظر تایید حقوقی";
                }
                elseif($value['addendum_status'] == 0){
                   
                    $style = "background:#ebc0c4";
                    $status="ثبت اولیه";
                }

            $addendum_html.="<tr style = '".$style."'>
                                <td>".$addendum_counter."</td>
                                <td>".$value['Addendum_duty']."</td>
                                <td>".number_format($value['Addendum_price'])." ریال</td>
                                <td>".$ut->get_user_fullname($value['user_id'])."</td>
                                <td>".$ut->greg_to_jal($value['Addendum_date'])."</td>
                                <td>".$status."</td>
                            </tr>";
        }
        $addendum_html.="</table>";
        $addendum_counter++;
    }
    else{
        $addendum_html="<div class='text-center'><p class='text-center text-danger' >قرارداد فاقد الحاقیه می باشد .</p></div>";
    }

    
    $sql_type="select contractType from contract where RowID={$cid}";
    $type_res=$db->ArrayQuery($sql_type);
    $iterator = 0;
    $htm = '';
    $htm .= '<table class="table table-bordered   table-light table-hover table-sm" id="OtherInfoContractHTM-tableID">';
    $htm .= '<thead>';
    $htm .= '<tr class="bg-info">';
    $htm .= '<td class="bg-info" style="text-align: center;font-family: dubai-Bold;width: 10%;color:#fff">ردیف</td>';
    $htm .= '<td class="bg-info" style="text-align: center;font-family: dubai-Bold;width: 45%;color:#fff">سایر اطلاعات</td>';
    $htm .= '<td class="bg-info" style="text-align: center;font-family: dubai-Bold;width: 45%;color:#fff">مقدار</td>';
    $htm .= '</tr>';
    $htm .= '</thead>';
    $htm .= '<tbody>';
    if($type_res[0]['contractType']==0){//قراداد عادی است 
        $infoNames = array( 'نوع قرارداد' ,' شماره قرارداد','طرف حساب','موضوع قرارداد',' مبلغ کل قراداد','تاریخ شروع','تاریخ پایان','مدت اعتبار','ثبت کننده قرارداد','توضیحات بایگانی','تاریخ بایگانی/تمدید','ساعت بایگانی/تمدید','بایگانی/تمدید کننده');
        $sql = "SELECT `contractType`,`number`,`accountName`,`subject`,`totalAmount`, `csDate`,`ceDate`,`creditPeriod`,`uid`,`archiveDescription`,`archiveDate`,`archiveTime`,`archiveUid`  FROM `contract` WHERE `RowID`={$cid}";
        $res = $db->ArrayQuery($sql);
        $total_amount=$res[0]['totalAmount'];
        $res[0]['contractType']=$res[0]['contractType']==0?"عادی":"ساعتی";
        $res[0]['uid']=$ut->get_user_fullname($res[0]['uid']);
        $res[0]['archiveUid']=$ut->get_user_fullname($res[0]['archiveUid']);
        $res[0]['archiveDate']=$ut->greg_to_jal($res[0]['archiveDate']);
        $res[0]['hourAmount']=number_format($res[0]['hourAmount']).' ریال';
        $res[0]['monthlyAmount']=number_format($res[0]['monthlyAmount']).' ریال';
        $res[0]['totalAmount']=number_format($res[0]['totalAmount']).' ریال';
        $res[0]['csDate']=$ut->greg_to_jal($res[0]['csDate']);
        $res[0]['ceDate']=$ut->greg_to_jal($res[0]['ceDate']);
        $res[0]['creditPeriod']=$res[0]['creditPeriod'] ." ماه";
        for ($i=0;$i<count($infoNames);$i++){
            $iterator++;
            $keyName = key($res[0]);
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]["$keyName"].'</td>';
            $htm .= '</tr>';
            next($res[0]);
        }
       
        $ut->fileRecorder("1-".$total_amount);
    }
    else
    {//قرارداد ساعتی می باشد
       
        $infoNames = array( 'نوع قرارداد' ,' شماره قرارداد','طرف حساب','موضوع قرارداد','مبلغ هرساعت حضور','ماکسیمم ساعات حضور در ماه','مبلغ ماهانه قرارداد','تاریخ شروع','تاریخ پایان','مدت اعتبار','ثبت کننده قرارداد','توضیحات بایگانی','تاریخ بایگانی/تمدید','ساعت بایگانی/تمدید','بایگانی/تمدید کننده');
        $sql = "SELECT `contractType`,`number`,`accountName`,`subject`,`hourAmount`,`maxHour`,`monthlyAmount`,`csDate`,`ceDate`,`creditPeriod`,`uid`,`archiveDescription`,`archiveDate`,`archiveTime`,`archiveUid` FROM `contract` WHERE `RowID`={$cid}";
        $res = $db->ArrayQuery($sql);
        $total_amount=$res[0]['monthlyAmount'];
        $res[0]['contractType']=$res[0]['contractType']==0?"عادی":"ساعتی";
        $res[0]['uid']=$ut->get_user_fullname($res[0]['uid']);
        $res[0]['archiveUid']=$ut->get_user_fullname($res[0]['archiveUid']);
        $res[0]['archiveDate']=$ut->greg_to_jal($res[0]['archiveDate']);
        $res[0]['csDate']=$ut->greg_to_jal($res[0]['csDate']);
        $res[0]['ceDate']=$ut->greg_to_jal($res[0]['ceDate']);
        $res[0]['creditPeriod'].=" ماه";
        $res[0]['hourAmount']=number_format($res[0]['hourAmount']).' ریال';
        $res[0]['monthlyAmount']=number_format($res[0]['monthlyAmount']).' ریال';
        $res[0]['maxHour']=$res[0]['maxHour'].' ساعت';
        for ($i=0;$i<count($infoNames);$i++){
            $iterator++;
            $keyName = key($res[0]);
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]["$keyName"].'</td>';
            $htm .= '</tr>';
            next($res[0]);
        }
       
    }
    //--------------------------------------------------------------------------
     //--------------------------------------------------------------
     $addendum_sql="SELECT SUM(`Addendum_price`) as addendum_amount from contract_addendum where contract_id={$cid} AND `status`=1 AND `addendum_status`=3";
     //error_log($addendum_sql);
     $addendum_res = $db->ArrayQuery($addendum_sql);
     
     $addendum_amount=$addendum_res[0]['addendum_amount']?$addendum_res[0]['addendum_amount']:0;

     $sql1 = "SELECT * FROM `pay_comment` WHERE `contractNumber`='{$res[0]['number']}' AND `isEnable`=1";
     $res1 = $db->ArrayQuery($sql1);
     $cnt = count($res1);

     $totalPardakhtShode = 0;
     $totalSaderShode = 0;

     for ($i=0;$i<$cnt;$i++){
         $pardakhtShode = 0;
         $iterator++;
         switch ($res1[$i]['transfer']){
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
         $sql2 = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$res1[$i]['RowID']}";
         $res2 = $db->ArrayQuery($sql2);
         $cnt2 = count($res2);
         for ($j=0;$j<$cnt2;$j++){
             $pardakhtShode += $res2[$j]['check_amount'];
         }

         $sql3 = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$res1[$i]['RowID']}";
         $res3 = $db->ArrayQuery($sql3);
         $cnt3 = count($res3);
         for ($j=0;$j<$cnt3;$j++){
             $pardakhtShode += $res3[$j]['dAmount'];
         }

         $totalPardakhtShode += $pardakhtShode;
         $totalSaderShode += $res1[$i]['Amount'] - $pardakhtShode;
     }
        $ut->fileRecorder($totalPardakhtShode); 
     if($totalPardakhtShode>0){
            $display_toggle_icon1 = "<i style='cursor:pointer' onclick='get_exported_payComments(".$cid.",this,1)' class='cursor-pointer fa fa-caret-left pl-2 text-success'></i>";

         }
         else{
            $display_toggle_icon1="";
         }
         if($totalSaderShode>0){
            $display_toggle_icon2 = "<i style='cursor:pointer' onclick='get_exported_payComments(".$cid.",this,0)' class='cursor-pointer fa fa-caret-left pl-2 text-success '></i>";

         }
         else{
            $display_toggle_icon2="";
         }
        $last_row_info="
             <div class='container'>
                <div class='position-relative row p-2'>
                     <div class='col-md-6'>مجموع مبلغ کل قرارداد(ثبت شده)</div>
                     <div class='col-md-6'>".number_format($total_amount)." ریال</div>
                </div>
                 <div class='position-relative row p-2'>
                     <div class='col-md-6'>مجموع کل الحاقیه ها</div>
                     <div class='col-md-6'>".number_format($addendum_amount)." ریال</div>
                </div>
                <div class='position-relative row p-2'>
                     <div class='col-md-6'>مجموع مبلغ کل قرارداد و الحاقیه های ثبت شده</div>
                     <div class='col-md-6'>".number_format($addendum_amount+$total_amount)." ریال</div>
                </div>
                <div class='position-relative row has_dropdown p-2'>
                     <div class='col-md-6'>".$display_toggle_icon2." مجموع اظهارنظر های صادرشده  مرتبط</div>
                     <div class='col-md-6'>".number_format($totalSaderShode)." ریال</div>
                </div>
                <div class='position-relative row  has_dropdown p-2'>
                     <div class='col-md-6'>".$display_toggle_icon1." مجموع اظهارنظر های پرداخت شده  مرتبط</div>
                     <div class='col-md-6'>".number_format($totalPardakhtShode)." ریال</div>
                </div>
                 
                <div class='position-relative row p-2'>
                    <div class='col-md-6'>مانده قرارداد</div>
                    <div class='col-md-6' >".number_format($addendum_amount+$total_amount-$totalPardakhtShode)." ریال</div>
                </div>
             </div>";
     //--------------------------------------------------------------
    //--------------------------------------------------------------------------
    $acm=new acm();
    if($acm->hasAccess('addendum_legal_confirm') || $acm->hasAccess('addendum_final_confirm')){
        $contract_attachment = "SELECT `fileName` FROM `contract_attachment` where `cid`={$cid} ";
        $res = $db->ArrayQuery($contract_attachment);

        $htm.='<tr class="table-secondary">
                    <td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.($iterator+1).'</td>
                    <td style="text-align: center;font-family: dubai-Regular;padding: 10px;">فایل قرارداد</td>
                    <td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a href="'.ADDR.'attachment/'.$res[0]['fileName'].'" download><i class="fa fa-download btn btn-info"></i></a></td>
                </tr>';
    }
    $htm .= '</tbody>';
    $htm .= '</table>';
    return "<fieldset style='border:1px solid gray;border-radius:10px' ><legend style='color:green;width:auto;padding:10px;margin:10px;font-size:1rem'>اطلاعات قرارداد</legend><div><i style='float:left' class='fa fa-plus  btn text-success' onclick='toggle_box(\"contract_info\",this)'></i></div><div style='display:none' id='contract_info'>".$htm.$addendum_html."</div></fieldset>
    <fieldset style='border:1px solid gray;border-radius:10px' ><legend style='color:green;width:auto;padding:10px;margin:10px;font-size:1rem'> اطلاعات پرداخت</legend><div><i style='float:left' class='fa fa-plus btn text-success' onclick='toggle_box(\"pay_info\",this)'></i></div><div style='display:none' id='pay_info'>".$last_row_info."</div></fieldset>";
}
    public function get_exported_payComments($cid,$payed=0){
        $db=new DBi();
        $ut=new Utility();
        $sql1 = "SELECT p.* FROM `pay_comment` as p left join contract as c on c.number=p.`contractNumber` WHERE c.RowID='{$cid}' AND p.`isEnable`=1 ";
        if($payed==1){
            $sql1.="AND p.`transfer` IN(2,3)";
        }
        if($payed==0){
            $sql1.="AND p.`transfer` IN(0,1)";
        }
       
        $ut->fileRecorder($sql1);
        $res1 = $db->ArrayQuery($sql1);
        $cnt = count($res1);
        $html="<div id='payed_comment_list' style='position: absolute;width: 100%;background: #d5ebeb;z-index: 1;top:30px;border:1px solid lightgray;box-shadow:10px 10px 10px gray'>
        <table class='table bg-light'>
        <tr>
            <td>#</td>
            <td>تاریخ ثبت</td>
            <td>کد یکتا</td>
            <td>مبلغ نقدی</td>
            <td>مبلغ چک</td>
            <td>وضعیت</td>
        </tr>";
        for ($i=0;$i<$cnt;$i++){
            $pardakhtShode = 0;
            $iterator++;
            switch ($res1[$i]['transfer']){
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
           $html.="<tr>
                        <td>".$iterator."</td>
                        <td>".$ut->greg_to_jal($res1[$i]['cDate'])."</td>
                        <td><a href='#' onclick='printPayComment(".$res1[$i]['RowID'].")'>".$res1[$i]['unCode']."</a></td>
                        <td>".number_format($res1[$i]['CashSection'])."</td>
                        <td>".number_format(($res1[$i]['NonCashSection']?$res1[$i]['NonCashSection']:0))."</td>
                        <td>".$status."</td>
                        
                    </tr>"; 
        }
        $html.="</table></div>";
        return $html;
    }

    public function attachedContractFileHtm($cid){
        $db = new DBi();
        $sql = "SELECT `RowID`,`fileName`,`fileInfo` FROM `contract_attachment` WHERE `cid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileContract-tableID">';
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
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteAttachContractFile('.$res[$i]['RowID'].')" ><i class="fas fa-trash"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function attachFileToContract($cid,$info,$files){
        $db = new DBi();
        $SFile = array();
        $allowedTypes = ['png','jpg','jpeg','pdf','xlsx','docx'];
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
                $SFile[] = "contract" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $cnt = count($SFile);
        for ($i=0;$i<$cnt;$i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i],'../attachment/'.$SFile[$i]);
            $sql4 = "INSERT INTO `contract_attachment` (`cid`,`fileName`,`fileInfo`) VALUES ({$cid},'{$SFile[$i]}','{$info}')";
            $db->Query($sql4);
        }
        return true;
    }

    public function deleteAttachContractFile($fid){
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `contract_attachment` WHERE `RowID`={$fid}";
        $res = $db->ArrayQuery($sql);
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/attachment/' . $res[0]['fileName'];
        $result = unlink($file_to_delete);
        if ($result) {
            $query = "DELETE FROM `contract_attachment` WHERE `RowID`={$fid}";
            $db->Query($query);
            return true;
        }else{
            return false;
        }
    }

    public function attachmentFileContractHtm($cid){
        $db = new DBi();
        $sql = "SELECT `fileName`,`fileInfo` FROM `contract_attachment` WHERE `cid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileContract1-tableID">';
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
            $link = ADDR.'attachment/'.$res[$i]['fileName'];
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

    public function contractCommentDatesHtm($cid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `RowID`,`commentDate` FROM `contract_dates` WHERE `cid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="contractCommentDates-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 50%;">تاریخ صدور اظهارنظر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">حذف تاریخ</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res[$i]['commentDate']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteContractCommentDate('.$res[$i]['RowID'].')" ><i class="fas fa-trash"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function createContractCommentDates($cid,$ccDate){
        $db = new DBi();
        $ut = new Utility();
        $ccDate = $ut->jal_to_greg($ccDate);
        $sql = "INSERT INTO `contract_dates` (`cid`,`commentDate`) VALUES ({$cid},'{$ccDate}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function deleteContractCommentDate($dateID){
        $db = new DBi();
        $query = "DELETE FROM `contract_dates` WHERE `RowID`={$dateID}";
        $db->Query($query);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if (intval($res)) {
            return true;
        } else {
            return false;
        }
    }

    public function getContractPayComments($cid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `number`,`totalAmount`,`RowID` FROM `contract` WHERE `RowID`={$cid}";
        $res = $db->ArrayQuery($sql);
        //----------------------------------------------- اعمال الحاقیه------------------------
        $addendum_sql="SELECT SUM(`Addendum_price`) as addendum_amount from contract_addendum where contract_id={$res[0]['RowID']} AND `status`=1 AND `addendum_status`=3";
        //error_log($addendum_sql);
        $addendum_res = $db->ArrayQuery($addendum_sql);
        $addendum_amount=$addendum_res[0]['addendum_amount'];
        //----------------------------------------------- اعمال الحاقیه------------------------


        $sql1 = "SELECT * FROM `pay_comment` WHERE `contractNumber`='{$res[0]['number']}' AND `isEnable`=1";
        $res1 = $db->ArrayQuery($sql1);
        $cnt = count($res1);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="contractPayComments-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 4%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">تاریخ ثبت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">در وجه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">بابت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 12%;">مبلغ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">وضعیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">واریزی</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 6%;">چک</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">سایر اطلاعات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 8%;">فایل پیوست</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">گردش کار</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">نمایش</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        $totalPardakhtShode = 0;
        $totalSaderShode = 0;

        for ($i=0;$i<$cnt;$i++){
            $pardakhtShode = 0;
            $iterator++;
            switch ($res1[$i]['transfer']){
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
            $sql2 = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$res1[$i]['RowID']}";
            $res2 = $db->ArrayQuery($sql2);
            $cnt2 = count($res2);
            for ($j=0;$j<$cnt2;$j++){
                $pardakhtShode += $res2[$j]['check_amount'];
            }

            $sql3 = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$res1[$i]['RowID']}";
            $res3 = $db->ArrayQuery($sql3);
            $cnt3 = count($res3);
            for ($j=0;$j<$cnt3;$j++){
                $pardakhtShode += $res3[$j]['dAmount'];
            }

            $totalPardakhtShode += $pardakhtShode;
            $totalSaderShode += $res1[$i]['Amount'] - $pardakhtShode;

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res1[$i]['cDate']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['accName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[$i]['Toward'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res1[$i]['Amount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$status.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showDepositsInFinancialList('.$res1[$i]['RowID'].')" ><i class="fas fa-dollar-sign"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowCommentCheckInContract('.$res1[$i]['RowID'].')" ><i class="fas fa-money-check-alt"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowOtherInfoComment('.$res1[$i]['RowID'].')" ><i class="fas fa-tv"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowAttachmentFileComment('.$res1[$i]['RowID'].')" ><i class="fas fa-link"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="ShowWorkflowComment('.$res1[$i]['RowID'].')" ><i class="fas fa-sitemap"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="printPayComment('.$res1[$i]['RowID'].')" ><i class="fas fa-search"></i></button></td>';
            $htm .= '</tr>';
        }

        $color = (intval($totalSaderShode) < 0 ? 'red' : 'black');
        $albaghi = $addendum_amount+$res[0]['totalAmount'] - ($totalPardakhtShode + $totalSaderShode);
        $htm .= '<tr class="table-warning">';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;" colspan="3"> مبلغ کل قرارداد : '.number_format($res[0]['totalAmount']).' ریال</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;" colspan="2"> مبلغ کل الحاقیه قرارداد : '.number_format($addendum_amount).' ریال</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;" colspan="2"> مبلغ اظهارنظرهای پرداخت شده : '.number_format($totalPardakhtShode).' ریال</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;" colspan="3"> مبلغ اظهارنظرهای در دست پرداخت : <span dir="ltr" style="color: '.$color.'">'.number_format($totalSaderShode).' ریال</span></td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;" colspan="2"> مبلغ باقی مانده : '.number_format($albaghi).' ریال</td>';
        $htm .= '</tr>';
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getContractChooseList($csDate,$ceDate,$cNum,$cAccount,$cAmount,$credit){
        $db = new DBi();
        $ut = new Utility();
        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`csDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`ceDate` <="'.$ceDate.'" ';
        }
        if(strlen(trim($cNum)) > 0){
            $w[] = '`number` LIKE "%'.$cNum.'%" ';
        }
        if(strlen(trim($cAccount)) > 0){
            $w[] = '`accountName` LIKE "%'.$cAccount.'%" ';
        }
        if(strlen(trim($cAmount)) > 0){
            $w[] = '`totalAmount`='.$cAmount.' ';
        }
        if(strlen(trim($credit)) > 0){
            $w[] = '`creditPeriod`='.$credit.' ';
        }
        $w[] = '`isEnable`=1 ';

        $sql = "SELECT * FROM `contract`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);

        $cnt = count($res);
        $iterator = 0;
        $htm = '';

        $htm .= '<form class="form-inline" style="margin: 20px 0;">';
        $htm .= '<div id="contractSDateSearch-div" >';
        $htm .= '<label class="sr-only" for="contractSDateSearch">تاریخ شروع قرارداد (به بعد)</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="contractSDateSearch" autocomplete="off" style="width: 150px;" placeholder="تاریخ شروع قرارداد (به بعد)" >';
        $htm .= '</div>';

        $htm .= '<div id="contractEDateSearch-div" >';
        $htm .= '<label class="sr-only" for="contractEDateSearch">تاریخ اتمام قرارداد (به قبل)</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="contractEDateSearch" autocomplete="off" style="width: 150px;" placeholder="تاریخ اتمام قرارداد (به قبل)" >';
        $htm .= '</div>';

        $htm .= '<div id="contractCNumberSearch-div" >';
        $htm .= '<label class="sr-only" for="contractCNumberSearch">شماره قرارداد</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="contractCNumberSearch" autocomplete="off" style="width: 120px;" placeholder="شماره قرارداد" >';
        $htm .= '</div>';

        $htm .= '<div id="contractAccountSearch-div" >';
        $htm .= '<label class="sr-only" for="contractAccountSearch">طرف مقابل</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="contractAccountSearch" autocomplete="off" style="width: 200px;" placeholder="طرف مقابل" >';
        $htm .= '</div>';

        $htm .= '<div id="contractAmountSearch-div" >';
        $htm .= '<label class="sr-only" for="contractAmountSearch">مبلغ کل قرارداد</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="contractAmountSearch" autocomplete="off" style="width: 120px;" placeholder="مبلغ کل قرارداد" onkeyup=addSeprator()>';
        $htm .= '</div>';

        $htm .= '<div id="contractCreditSearch-div" >';
        $htm .= '<label class="sr-only" for="contractCreditSearch">مدت قرارداد (ماه)</label>';
        $htm .= '<input type="text" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="contractCreditSearch" autocomplete="off" style="width: 100px;" placeholder="مدت قرارداد (ماه)" >';
        $htm .= '</div>';

        $htm .= '<button type="button" class="btn btn-sm btn-secondary mb-2 mr-2" style="padding: 0.25rem 0.5rem;" onclick="showContractChooseList()">جستجو&nbsp;&nbsp;<i class="fa fa-search"></i></button>';

        $htm .= '</form>';


        $htm .= '<table class="table table-bordered table-hover table-sm" id="contractChooseList-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 4%;">#</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 16%;">شماره قرارداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">موضوع قرارداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">طرف مقابل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">مبلغ کل قرارداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">مدت قرارداد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ شروع</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ اتمام</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><input type="checkbox" rid="'.$res[$i]['RowID'].'"></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['number'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['subject'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['accountName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res[$i]['totalAmount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['creditPeriod'].' ماه</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res[$i]['csDate']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res[$i]['ceDate']).'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getContractAccountInfo($cid){
        $db = new DBi();
        $sql = "SELECT `number`,`accountName`,`codeTafzili`,`accNum` FROM `contract` WHERE `RowID`={$cid}";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            return $res;
        }else{
            return false;
        }
    }

    public function get_contract_pay_rows($contract_id,$pay_type){
        $db=new DBi();
        $ut=new Utility();
        $where="contract_id={$contract_id}";
        if($pay_type==1){// سررسید شده 
            $where.= " AND `status` IN (1) ORDER BY RowID  ASC LIMIT 1";
        }
        if($pay_type==2){// ;کلیه ردیف های فعال پرداخت نشده
            $where.= " AND `status` IN (1)";
        }
        if($pay_type==3){// ;کلیه ردیف های فعال پرداخت شده
            $where.= " AND `status` IN (2)";
        }
        if($pay_type==4){// همه ردیف های پرداخت 
            $where.= " AND `status` IN (1,2)";
        }

        $sql="SELECT * FROM contract_pay_formula WHERE {$where}";
        //$ut->fileRecorder($sql);
        $result=$db->ArrayQuery($sql);
        $html='<div class="display_pay_rows">';
        $pay_type_values=[1,2,3,4];
        $pay_type_titles=["سررسید شده","پرداخت نشده","پرداخت  شده","همه ردیف های پرداخت"];
        $html.='<div style="display: grid;grid-template-columns: auto auto;grid-gap: 10px;background-color: #2196F3;padding: 10px;">
                    <div >
                        <span> فیلتر براساس نوع  ردیف پرداخت</span>
                    </div>
                    <div>
                        <select style="border-radius:5px" id="filter_pay_row_type" onchange="show_contract_pay_rows()">';
         for($i=0;$i<count($pay_type_values);$i++) {
            $selected="";
            if($pay_type_values[$i]==$pay_type){
                $selected="selected";
            }
            $html.='<option '.$selected.' value="'.$pay_type_values[$i].'">'.$pay_type_titles[$i].'</option>';
         }                  
        $html.='</select>
                </div>
            </div>';

        $html.='<table class="table bordered">';
        $html.='<tr style="background-color: #2196F3;"><th style="width:5%">#</th><th style="width:5%">انتخاب</th><th style="width:40%">شرح پرداخت</th><th style="width:25%">مبلغ  پرداخت</th><th style="width:17%">درصد مجاز</th><th style="width:7%">انتخاب</th></tr>';
        $counter=1;
        $first_row=false;
        $get_first_active_row="SELECT RowID FROM contract_pay_formula where contract_id={$contract_id} AND `status` =1 order by RowID asc limit 1 ";
        $res=$db->ArrayQuery($get_first_active_row);
        $first_RowID=$res[0]['RowID'];
        foreach($result as $row){
            if($row['status']==2){
                $html.='<tr style="background:#80808026">
                    <td style="width:5%">'.$counter.'</td>
                    <td style="width:5%"><input disabled type="checkbox" value="'.$row['RowID'].'" id="select_'.$row['RowID'].'" ></td>
                    <td style="width:40%">'.$row['description_pay_part'].'</td>
                    <td style="width:25%">
                        <span>'.$row['amount_pay_part'].'</span>
                    </td>
                    <td style="width:17%">'.$row['percentage_increase_allowable_temperature'].'</td>
                    <td style="width:8%">
                       <span style="color:green">پرداخت شده</span>
                    </td>
                </tr>';
            }
            elseif($row['status']==1){
                $html.=
                '<tr>
                    <td style="width:5%">'.$counter.'</td>
                    <td style="width:5%"><input type="checkbox" value="'.$row['RowID'].'" id="select_'.$row['RowID'].'" ></td>
                    <td style="width:40%">'.$row['description_pay_part'].'</td>
                    <td style="width:25%">
                        <input type="text" id="amount_pay_part_'.$counter.'" style="border:none;background:transparent" value="'.$row['amount_pay_part'].'" disabled readonly>
                    </td>
                    <td style="width:20%">'.$row['percentage_increase_allowable_temperature'].'
                    <input type="hidden" id="pay_percent_'.$counter.'" value="'.$row['percentage_increase_allowable_temperature'].'"></td>';
                if($row['RowID']==$first_RowID)
                {
                    $html.='<td style="width:10%">
                        <button  id="select_pay_amount_btn_'.$counter.'" onclick="select_pay_amount(this,'.$counter.')" class="btn btn-success">انتخاب</button>
                    </td>';
                }
                else
                {
                    $html.='<td style="width:10%">
                    <button disabled id="select_pay_amount_btn_'.$counter.'" onclick="" class="btn btn-success">انتخاب</button>
                    </td>';
                }
               $html.=' </tr>';
           
            }
            $counter++;
        }


        $html.='</table>';
        $html.='</div>';
        //$ut->fileRecorder($html);
        return $html;
    }
    //++++++++++++++++++++ مدیریت طرف حساب ها ++++++++++++++++++++

    public function getAccountManageList($aName,$aCode,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($aName)) > 0){
            $w[] = '`Name` LIKE "%'.$aName.'%" ';
        }
        if(strlen(trim($aCode)) > 0){
            $w[] = '`code`='.$aCode.' ';
        }

        $sql = "SELECT * FROM `account`";
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
            $finalRes[$y]['Name'] = $res[$y]['Name'];
            $finalRes[$y]['code'] = $res[$y]['code'];
            $finalRes[$y]['accountNumber'] = $res[$y]['accountNumber'];
            $finalRes[$y]['bankName'] = $res[$y]['bankName'];
        }
        return $finalRes;
    }

    public function getAccountManageListCountRows($aName,$aCode){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
/*        $ut = new Utility();
        $query = "SELECT `RowID`,`accName` FROM `pay_comment`";
        $rst = $db->ArrayQuery($query);
        $cnt = count($rst);
        for ($i=0;$i<$cnt;$i++){
            $www = $rst[$i]['accName'];
            $var2 = str_replace("ی","ي", $www);
            $sqlu = "UPDATE `pay_comment` SET `accName`='{$var2}' WHERE `RowID`={$rst[$i]['RowID']}";
            $db->Query($sqlu);
        }*/
        $w = array();
        if(strlen(trim($aName)) > 0){
            $w[] = '`Name` LIKE "%'.$aName.'%" ';
        }
        if(strlen(trim($aCode)) > 0){
            $w[] = '`code`='.$aCode.' ';
        }

        $sql = "SELECT `RowID` FROM `account`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function accountInfo($aid){
        $db = new DBi();
        $sql = "SELECT * FROM `account` WHERE `RowID`=".$aid;
        $res = $db->ArrayQuery($sql);
        $accountNumber = explode(',',$res[0]['accountNumber']);
        $bankName = explode(',',$res[0]['bankName']);
        for ($i=0;$i<count($accountNumber);$i++){
            $aNumBankName[] = $accountNumber[$i];
            $aNumBankName[] = $bankName[$i];
        }
        $aNumBankName = implode(',',$aNumBankName);
        if(count($res) == 1){
            if(empty($res[0]['account_role'])){
                $role='null';
            }
            else{
                $role=$res[0]['account_role'];
            }
            $res = array("aid"=>$aid,"Name"=>$res[0]['Name'],"code"=>$res[0]['code'],"aNumBankName"=>$aNumBankName,"codeMelli"=>$res[0]['codeMelli'],'role'=>$role);
            return $res;
        }else{
            return false;
        }
    }

    public function accountInfoHTM($aid){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `accountNumber`,`bankName` FROM `account` WHERE `RowID`={$aid}";
        $res = $db->ArrayQuery($sql);
        $accountNumber = explode(',',$res[0]['accountNumber']);
        $bankName = explode(',',$res[0]['bankName']);
        $cnt = count($accountNumber);
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoAccount-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">شماره حساب</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">نام بانک و شعبه</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.($i+1).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$accountNumber[$i].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$bankName[$i].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function createAccount($Name,$code,$an,$codeMelli,$role_array){
        $ut=new Utility();
        $acm = new acm();
        //$role_array=explode(",",$role_array);
        // $ut->fileRecorder('ule_array');
        // $ut->fileRecorder($role_array);

        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $query = "SELECT `code` FROM `account`";
        $rst = $db->ArrayQuery($query);
        $cnt = count($rst);
        for ($i=0;$i<$cnt;$i++){
            if ($rst[$i]['code'] == 8){
                continue;
            }else{
                $codes[] = $rst[$i]['code'];
            }
        }
        if(count($role_array)>0){
          
            $account_role_json=implode(",",$role_array);
        }
        else{
            $account_role_json="";
        }
        if (in_array($code,$codes)){
            return -2;
        }else{
            $an = explode(',',$an);
            $countAn = count($an)/2;
            if(((count($an)) % 2) >= 1){
                return -1;
            }else{
                $x = 0;
                for ($i=0;$i<$countAn;$i++){
                    if (strlen(trim($an[$x])) == 24){
                        $an[$x] = 'IR'.$an[$x];
                        $arr = str_split($an[$x], 4);
                        $an[$x] = implode('-',$arr);
                    }else{
                        if ($an[$x] != 0){
                            $arr = str_split($an[$x], 4);
                            $an[$x] = implode('-',$arr);
                        }
                    }
                    $aNums[] = $an[$x];
                    $banks[] = $an[$x+1];
                    $x += 2;
                }  // End for
                $aNums = implode(',',$aNums);
                $banks = implode(',',$banks);
                $sql = "INSERT INTO `account` (`Name`,`code`,`accountNumber`,`bankName`,`uid`,`codeMelli`,`account_role`) VALUES ('{$Name}',{$code},'{$aNums}','{$banks}',{$_SESSION['userid']},'{$codeMelli}','{$account_role_json}')";
                $res = $db->Query($sql);
                if (intval($res) > 0) {
                    return true;
                }else{
                    return false;
                }
            }
        }
    }

    public function editAccount($aid,$Name,$code,$an,$codeMelli,$role_array){
        $acm = new acm();
        $ut=new Utility();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $an = explode(',', $an);
        $countAn = count($an) / 2;
        if (((count($an)) % 2) >= 1) {
            return -1;
        } else {
            $x = 0;
            for ($i=0;$i<$countAn;$i++){
                if (strlen(trim($an[$x])) == 24){
                    $an[$x] = 'IR'.$an[$x];
                    $arr = str_split($an[$x], 4);
                    $an[$x] = implode('-',$arr);
                }else{
                    if ($an[$x] != 0){
                        $arr = str_split($an[$x], 4);
                        $an[$x] = implode('-',$arr);
                    }
                }
                $aNums[] = $an[$x];
                $banks[] = $an[$x+1];
                $x += 2;
            }  // End for
            $aNums = implode(',', $aNums);
            $banks = implode(',', $banks);
        }

        $sqq = "SELECT `code` FROM `account` WHERE `RowID`={$aid}";
        $resqq = $db->ArrayQuery($sqq);
        if(count($role_array)>0){
            $account_role=implode(",",$role_array);
        }
        else{
            $account_role="";
        }
        if ($resqq[0]['code'] != $code) {

            $query = "SELECT `code` FROM `account`";
            $rst = $db->ArrayQuery($query);
            $cnt = count($rst);
            for ($i = 0; $i < $cnt; $i++) {
                if ($rst[$i]['code'] == 8) {
                    continue;
                } else {
                    $codes[] = $rst[$i]['code'];
                }
            }
            if (in_array($code, $codes)) {
                return -2;
            } else {
                
                // $select_sql="SELECT `Name`, `Code` FROM `account` WHERE RowID={$aid}";
                // $old_res->ArrayQuery($select_sql);
                $sql = "UPDATE `account` SET `Name`='{$Name}',`code`={$code},`accountNumber`='{$aNums}',`bankName`='{$banks}',`codeMelli`='{$codeMelli}',`account_role`='{$account_role}' WHERE `RowID`={$aid}";
            
                $res=$db->Query($sql);
                // $res = $db->AffectedRows();
                // $res = (($res == -1 || $res == 0) ? 0 : 1);
               
                if ($res) 
                {
                    if($code!=8){
                        $update_payComment="UPDATE pay_comment SET `accName`='{$Name}', `codeTafzili`='{$code}' where `codeTafzili`='{$resqq[0]['code']}'";
                        //$ut->fileRecorder('update_payComment:'.$update_payComment);
                       $res2=$db->Query($update_payComment);
                       if($res2){
                        return true;
                       }
                       else{
                        return false;
                       }
                    }
                    else{
                        return true;
                    }
                   
                    
                } else {
                    return false;
                }
            }
        }else{
           // $ut->fileRecorder(2);
            $sql = "UPDATE `account` SET `Name`='{$Name}',`code`={$code},`accountNumber`='{$aNums}',`bankName`='{$banks}',`codeMelli`='{$codeMelli}',`account_role`='{$account_role}' WHERE `RowID`={$aid}";
           // $ut->fileRecorder($sql);
           //$ut->fileRecorder($sql);
           $res6= $db->Query($sql);
           // $res6 = $db->AffectedRows();
           // $res = (($res == -1 || $res == 0) ? 0 : 1);
            if ($res6) {
                //return true;
                if($code !=8){

                
                $update_payComment="UPDATE pay_comment SET `accName`='{$Name}', `codeTafzili`='{$code}' where `codeTafzili`='{$resqq[0]['code']}'";
                // $ut->fileRecorder('update_payComment:'.$update_payComment);
                $res2=$db->Query($update_payComment);
                if($res2){
                return true;
                }
                else{
                return false;
                }
            }
            else{
                return true;
            }
                    
            } else {
                return false;
            }
        }
    }

    //++++++++++++++++++++ مدیریت واحد های مربوطه ++++++++++++++++++++

    public function unitInfo($uid){
        $db = new DBi();
        $sql = "SELECT * FROM `relatedUnits` WHERE `RowID`=".$uid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("uid"=>$uid,"unitName"=>$res[0]['unitName'],"unitDesc"=>$res[0]['unitDesc'],"uids"=>$res[0]['uids']);
            return $res;
        }else{
            return false;
        }
    }

    public function createUnit($Uname,$Udesc,$users){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "INSERT INTO `relatedUnits` (`unitName`,`unitDesc`,`uids`) VALUES ('{$Uname}','{$Udesc}','{$users}')";
        $res = $db->Query($sql);
        if (intval($res) > 0) {
            return true;
        }else{
            return false;
        }
    }

    public function editUnit($uid,$Uname,$Udesc,$users){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `relatedUnits` SET `unitName`='{$Uname}',`unitDesc`='{$Udesc}',`uids`='{$users}' WHERE `RowID`={$uid}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if (intval($res)){
            return true;
        }else{
            return false;
        }
    }

    public function getUnitManageList($page){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $sql = "SELECT * FROM `relatedUnits`";
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            if(!empty($res[$y]['uids']))
            {
                $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID` IN ({$res[$y]['uids']})";
                $rst = $db->ArrayQuery($query);
                $cnt = count($rst);
                $uNames = array();
                for ($j=0;$j<$cnt;$j++){
                    $uNames[] = $rst[$j]['fname'].' '.$rst[$j]['lname'];
                }
                $finalRes[$y]['uNames'] = implode(' , ',$uNames);
            }
            else{
                $finalRes[$y]['uNames']='';
            }
                $finalRes[$y]['RowID'] = $res[$y]['RowID'];
                $finalRes[$y]['unitName'] = $res[$y]['unitName'];
                $finalRes[$y]['unitDesc'] = $res[$y]['unitDesc'];
        
        }
        return $finalRes;
    }

    public function getUnitManageListCountRows(){
        $acm = new acm();
        if(!$acm->hasAccess('commentManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `RowID` FROM `relatedUnits`";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getUnits(){
        $db = new DBi();
        $sql = "SELECT `RowID`,`unitName` FROM `relatedunits`";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    //++++++++++++++++++++ پرداخت اظهارنظر ++++++++++++++++++++

    public function getFinalPayCommentManageList($pcaName,$pcToward,$pcAmount,$typeDay,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('payCommentManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $toDay = date('Y/m/d');
        $w = array();
        if (intval($typeDay) == 0){
            $w[] = '((`paymentMaturityCash` != "0000-00-00" AND `paymentMaturityCash` <="'.$toDay.'") OR (`paymentMaturityCheck` != "0000-00-00" AND `paymentMaturityCheck` <="'.$toDay.'")) ';
        }else{
            $w[] = '((`paymentMaturityCash` != "0000-00-00" AND `paymentMaturityCash` >"'.$toDay.'") OR (`paymentMaturityCheck` != "0000-00-00" AND `paymentMaturityCheck` >"'.$toDay.'")) ';
        }
        if(strlen(trim($pcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$pcaName.'%" ';
        }
        if(strlen(trim($pcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$pcToward.'%" ';
        }
        if(strlen(trim($pcAmount)) > 0){
            $w[] = '`Amount`='.$pcAmount.' ';
        }
        if($acm->hasAccess('forjAccess')){
            $w[] = '`payType`=0 ';
        }elseif ($acm->hasAccess('sahamiAccess')){
            $w[] = '`payType`=1 ';
        }
        $w[] = '`transfer`=1 ';

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`Amount`,`CashSection`,`NonCashSection`,`Toward`,`accName`,`paymentMaturityCash`,`paymentMaturityCheck`,`sendType`,`priorityLevel` FROM `pay_comment` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= "AND isEnable=1 ORDER BY `pcid` DESC LIMIT $start,".$numRows;
       //$ut->fileRecorder('sql::'.$sql);
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $totalAmount = 0;
            $query = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$res[$y]['pcid']}";
            $rst = $db->ArrayQuery($query);
            if (count($rst) > 0) {
                $cnt = count($rst);
                for ($i=0;$i<$cnt;$i++) {
                    $totalAmount += $rst[$i]['dAmount'];
                }
            }

            $sqlCheck = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$res[$y]['pcid']}";
            $result = $db->ArrayQuery($sqlCheck);
            if (count($result) > 0) {
                $cnt = count($result);
                for ($i=0;$i<$cnt;$i++) {
                    $totalAmount += $result[$i]['check_amount'];
                }
            }

            $rAmount = 0;
            $sqqRTN = "SELECT `rAmount` FROM `return_money` WHERE `pid`={$res[$y]['pcid']}";
            $rstn = $db->ArrayQuery($sqqRTN);
            $cntq = count($rstn);
            for ($i=0;$i<$cntq;$i++){
                $rAmount += $rstn[$i]['rAmount'];
            }

            $fAmount = 0;
            $sqqRFN = "SELECT `fAmount` FROM `fraction_money` WHERE `cid`={$res[$y]['pcid']}";
            $rstf = $db->ArrayQuery($sqqRFN);
            $cntq = count($rstf);
            for ($i=0;$i<$cntq;$i++){
                $fAmount += $rstf[$i]['fAmount'];
            }

            $leftOver = ($res[$y]['Amount'] + $rAmount) - ($totalAmount + $fAmount);

            switch ($res[$y]['sendType']){
                case 0:
                    $finalRes[$y]['bgColor'] = 'table-warning';
                    break;
                case 1:
                    $finalRes[$y]['bgColor'] = 'table-orange';
                    break;
                case 2:
                    $finalRes[$y]['bgColor'] = 'table-primary';
                    break;
                case 3:
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
            }
            $finalRes[$y]['RowID'] = $res[$y]['pcid'];
            $finalRes[$y]['paymentMaturityCash'] = (strtotime($res[$y]['paymentMaturityCash']) > 0 ? $ut->greg_to_jal($res[$y]['paymentMaturityCash']) : '');
            $finalRes[$y]['paymentMaturityCheck'] = (strtotime($res[$y]['paymentMaturityCheck']) > 0 ? $ut->greg_to_jal($res[$y]['paymentMaturityCheck']) : '');
            $finalRes[$y]['CashSection'] = number_format($res[$y]['CashSection']).' ریال';
            $finalRes[$y]['NonCashSection'] = number_format($res[$y]['NonCashSection']).' ریال';
            $finalRes[$y]['leftOver'] = '<span dir="ltr" style="font-family: dubai-Regular;">'.number_format($leftOver).'</span> ریال';
            $finalRes[$y]['Name'] = (strlen(trim($res[$y]['accName'])) == 0 ? '--------' : $res[$y]['accName']);
            $finalRes[$y]['Toward'] = $res[$y]['Toward'];
            $finalRes[$y]['blinker'] = ($res[$y]['priorityLevel'] == 1 ? 'blink' : '');
        }
        return $finalRes;
    }

    public function getFinalPayCommentManageListCountRows($pcaName,$pcToward,$pcAmount,$typeDay){
        $acm = new acm();
        $db = new DBi();
        $ut=new Utility();
        $toDay = date('Y/m/d');
        $w = array();
        if (intval($typeDay) == 0){
            $w[] = '((`paymentMaturityCash` != "0000-00-00" AND `paymentMaturityCash` <="'.$toDay.'") OR (`paymentMaturityCheck` != "0000-00-00" AND `paymentMaturityCheck` <="'.$toDay.'")) ';
        }else{
            $w[] = '((`paymentMaturityCash` != "0000-00-00" AND `paymentMaturityCash` >"'.$toDay.'") OR (`paymentMaturityCheck` != "0000-00-00" AND `paymentMaturityCheck` >"'.$toDay.'")) ';
        }
        if(strlen(trim($pcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$pcaName.'%" ';
        }
        if(strlen(trim($pcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$pcToward.'%" ';
        }
        if(strlen(trim($pcAmount)) > 0){
            $w[] = '`Amount`='.$pcAmount.' ';
        }
        if($acm->hasAccess('forjAccess')){
            $w[] = '`payType`=0 ';
        }elseif ($acm->hasAccess('sahamiAccess')){
            $w[] = '`payType`=1 ';
        }
        $w[] = '`transfer`=1  AND isEnable=1';

        $sql = "SELECT `RowID` FROM `pay_comment` ";
       
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
       
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getSortPayCommentManageList($pcaName,$pcToward,$pcAmount,$typeDay,$sort,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('payCommentManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $toDay = date('Y/m/d');
        $w = array();
        if (intval($typeDay) == 0){
            $w[] = '((`paymentMaturityCash` != "0000-00-00" AND `paymentMaturityCash` <="'.$toDay.'") OR (`paymentMaturityCheck` != "0000-00-00" AND `paymentMaturityCheck` <="'.$toDay.'")) ';
        }else{
            $w[] = '((`paymentMaturityCash` != "0000-00-00" AND `paymentMaturityCash` >"'.$toDay.'") OR (`paymentMaturityCheck` != "0000-00-00" AND `paymentMaturityCheck` >"'.$toDay.'")) ';
        }
        if(strlen(trim($pcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$pcaName.'%" ';
        }
        if(strlen(trim($pcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$pcToward.'%" ';
        }
        if(strlen(trim($pcAmount)) > 0){
            $w[] = '`Amount`='.$pcAmount.' ';
        }
        if($acm->hasAccess('forjAccess')){
            $w[] = '`payType`=0 ';
        }elseif ($acm->hasAccess('sahamiAccess')){
            $w[] = '`payType`=1 ';
        }
        $w[] = '`transfer`=1 AND isEnable=1';
        if ($sort == 1){
            $w[] = '`paymentMaturityCash` != "0000-00-00" ';
            $order = " ORDER BY `paymentMaturityCash` ASC LIMIT $start,".$numRows;
        }else{
            $w[] = '`paymentMaturityCheck` != "0000-00-00" ';
            $order = " ORDER BY `paymentMaturityCheck` ASC LIMIT $start,".$numRows;
        }

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`Amount`,`CashSection`,`NonCashSection`,`Toward`,`accName`,`paymentMaturityCash`,`paymentMaturityCheck`,`sendType`,`priorityLevel` FROM `pay_comment` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= $order;
       // $ut->fileRecorder('sql3:'.$sql);
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $totalAmount = 0;
            $query = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$res[$y]['pcid']}";
            $rst = $db->ArrayQuery($query);
            if (count($rst) > 0) {
                $cnt = count($rst);
                for ($i=0;$i<$cnt;$i++) {
                    $totalAmount += $rst[$i]['dAmount'];
                }
            }

            $sqlCheck = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$res[$y]['pcid']}";
            $result = $db->ArrayQuery($sqlCheck);
            if (count($result) > 0) {
                $cnt = count($result);
                for ($i=0;$i<$cnt;$i++) {
                    $totalAmount += $result[$i]['check_amount'];
                }
            }

            $rAmount = 0;
            $sqqRTN = "SELECT `rAmount` FROM `return_money` WHERE `pid`={$res[$y]['pcid']}";
            $rstn = $db->ArrayQuery($sqqRTN);
            $cntq = count($rstn);
            for ($i=0;$i<$cntq;$i++){
                $rAmount += $rstn[$i]['rAmount'];
            }

            $fAmount = 0;
            $sqqRFN = "SELECT `fAmount` FROM `fraction_money` WHERE `cid`={$res[$y]['pcid']}";
            $rstf = $db->ArrayQuery($sqqRFN);
            $cntq = count($rstf);
            for ($i=0;$i<$cntq;$i++){
                $fAmount += $rstf[$i]['fAmount'];
            }

            $leftOver = ($res[$y]['Amount'] + $rAmount) - ($totalAmount + $fAmount);

            switch ($res[$y]['sendType']){
                case 0:
                    $finalRes[$y]['bgColor'] = 'table-warning';
                    break;
                case 1:
                    $finalRes[$y]['bgColor'] = 'table-orange';
                    break;
                case 2:
                    $finalRes[$y]['bgColor'] = 'table-primary';
                    break;
                case 3:
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
            }
            $finalRes[$y]['RowID'] = $res[$y]['pcid'];
            $finalRes[$y]['paymentMaturityCash'] = (strtotime($res[$y]['paymentMaturityCash']) > 0 ? $ut->greg_to_jal($res[$y]['paymentMaturityCash']) : '');
            $finalRes[$y]['paymentMaturityCheck'] = (strtotime($res[$y]['paymentMaturityCheck']) > 0 ? $ut->greg_to_jal($res[$y]['paymentMaturityCheck']) : '');
            $finalRes[$y]['CashSection'] = number_format($res[$y]['CashSection']).' ریال';
            $finalRes[$y]['NonCashSection'] = number_format($res[$y]['NonCashSection']).' ریال';
            $finalRes[$y]['leftOver'] = number_format($leftOver).' ریال';
            $finalRes[$y]['Name'] = (strlen(trim($res[$y]['accName'])) == 0 ? '--------' : $res[$y]['accName']);
            $finalRes[$y]['Toward'] = $res[$y]['Toward'];
            $finalRes[$y]['blinker'] = ($res[$y]['priorityLevel'] == 1 ? 'blink' : '');
        }
        return $finalRes;
    }

    public function getSortPayCommentManageListCountRows($pcaName,$pcToward,$pcAmount,$typeDay){
        $acm = new acm();
        $ut = new Utility();
        $db = new DBi();
        $toDay = date('Y/m/d');
        $w = array();
        if (intval($typeDay) == 0){
            $w[] = '((`paymentMaturityCash` != "0000-00-00" AND `paymentMaturityCash` <="'.$toDay.'") OR (`paymentMaturityCheck` != "0000-00-00" AND `paymentMaturityCheck` <="'.$toDay.'")) ';
        }else{
            $w[] = '((`paymentMaturityCash` != "0000-00-00" AND `paymentMaturityCash` >"'.$toDay.'") OR (`paymentMaturityCheck` != "0000-00-00" AND `paymentMaturityCheck` >"'.$toDay.'")) ';
        }
        if(strlen(trim($pcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$pcaName.'%" ';
        }
        if(strlen(trim($pcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$pcToward.'%" ';
        }
        if(strlen(trim($pcAmount)) > 0){
            $w[] = '`Amount`='.$pcAmount.' ';
        }
        if($acm->hasAccess('forjAccess')){
            $w[] = '`payType`=0 ';
        }elseif ($acm->hasAccess('sahamiAccess')){
            $w[] = '`payType`=1 ';
        }
        $w[] = '`transfer`=1  AND isEnable=1';

        $sql = "SELECT `RowID` FROM `pay_comment` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
       
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    // public function getLayer1(){
    //     $db = new DBi();
    //     $sql = "SELECT `layerName`,`RowID` FROM `layers` where parentID=-1";
    //     $res = $db->ArrayQuery($sql);
    //     if(count($res)>0){
    //         return $res;
    //     }else{
    //         return false;
    //     } 
    // }

    public function getDepositorNames(){
        $db = new DBi();
        $sql = "SELECT `Name`,`RowID` FROM `depositors`";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        if (intval($_SESSION['userid']) == 49){
            for ($i=0;$i<$cnt;$i++){
                if ($res[$i]['RowID'] == 2 || $res[$i]['RowID'] == 3 || $res[$i]['RowID'] == 4 || $res[$i]['RowID'] == 2069){
                    unset($res[$i]);
                }
            }
            $res = array_values($res);
        }
        if(count($res)>0){
            return $res;
        }else{
            return false;
        }
    }

    public function getBankNames(){
        $db = new DBi();
        $sql = "SELECT `bankName`,`RowID` FROM `banks`";
        $res = $db->ArrayQuery($sql);
        if(count($res)>0){
            return $res;
        }else{
            return false;
        }
    }

    public function getCatComment($pid){
        $db = new DBi();
        $sql = "SELECT `payType` FROM `pay_comment` WHERE `RowID`={$pid}";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function checkLeftOverCash($pid,$DAmount){
        $db = new DBi();
        $sql = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);

        $query = "SELECT `CashSection` FROM `pay_comment` WHERE `RowID`={$pid}";
        $rst = $db->ArrayQuery($query);

        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            $DAmount += $res[$i]['dAmount'];
        }
        if ($DAmount > $rst[0]['CashSection']){
            return 'yes';
        }else{
            return 'no';
        }
    }

    public function createDepositRegistration($pid,$Ddate,$Depositor,$DCode,$DBank,$DAmount,$Description,$files){
        $acm = new acm();
        if(!$acm->hasAccess('payCommentManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $Ddate = $ut->jal_to_greg($Ddate);

        $SFile = array();
        $allowedTypes = ['png','jpg','jpeg','pdf','zip','rar','PNG','JPG','JPEG','PDF','ZIP','RAR'];

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
                $SFile[] = "receipt" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $query1 = "SELECT `accountID` FROM `pay_comment` WHERE `RowID`={$pid}";
        $rst1 = $db->ArrayQuery($query1);
        if (intval($rst1[0]['accountID']) <= 0){
            $rst1[0]['accountID'] = 'NULL';
        }

        $sql = "INSERT INTO `deposit` (`pid`,`dDate`,`depositor`,`dBank`,`dAmount`,`dDesc`,`codeTafzili`,`uid`,`accID`) VALUES ({$pid},'{$Ddate}','{$Depositor}','{$DBank}',{$DAmount},'{$Description}',{$DCode},{$_SESSION['userid']},{$rst1[0]['accountID']})";
        $result = $db->Query($sql);
        if (intval($result) > 0) {
            $id = $db->InsertrdID();
            $cnt = count($SFile);
            for ($i=0;$i<$cnt;$i++) {
                $upload = move_uploaded_file($files["tmp_name"][$i],'../paymentReceipt/'.$SFile[$i]);
                $sql4 = "INSERT INTO `payment_receipt` (`did`,`fileName`) VALUES ({$id},'{$SFile[$i]}')";
                $db->Query($sql4);
            }
            return true;
        }else{
            return false;
        }
    }

    public function doDeletePaymentReceipt($did){
        $db = new DBi();
        mysqli_autocommit($db->Getcon(),FALSE);
        $flag = true;

        $sql = "SELECT `fileName` FROM `payment_receipt` WHERE `did`={$did}";
        $res = $db->ArrayQuery($sql);
        if (count($res) > 0){
            $sql1 = "DELETE FROM `payment_receipt` WHERE `did`={$did}";
            $db->Query($sql1);
            $aff = $db->AffectedRows();
            $aff = (($aff == -1) ? 0 : 1);
            if (intval($aff) == 0){
                $flag = false;
            }
        }
        $sql2 = "DELETE FROM `deposit` WHERE `RowID`={$did}";
        $db->Query($sql2);
        $aff1 = $db->AffectedRows();
        $aff1 = (($aff1 == -1) ? 0 : 1);
        if (intval($aff1) == 0){
            $flag = false;
        }
        if ($flag){
            $cnt = count($res);
            for ($i = 0; $i < $cnt; $i++) {
                $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/paymentReceipt/' . $res[$i]['fileName'];
                unlink($file_to_delete);
            }
            mysqli_commit($db->Getcon());
            return true;
        }else{
            mysqli_rollback($db->Getcon());
            return false;
        }
    }

    public function downloadPaymentReceiptHtm($did){
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `payment_receipt` WHERE `did`={$did}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="downloadPaymentReceipt-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 50%;">شماره فایل ضمیمه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 50%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $fileName = 'رسید شماره '.$iterator;
            $link = ADDR.'paymentReceipt/'.$res[$i]['fileName'];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$fileName.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getDepositorCode($depositor){
        $db = new DBi();
        $sql = "SELECT `codeTafzili` FROM `depositors` WHERE `Name`='{$depositor}'";
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            return $res;
        }else{
            return false;
        }
    }

    public function getDepositorNameWC($code){
        $db = new DBi();
        if (intval($code) !== 77777) {
            $sql = "SELECT `Name` FROM `depositors` WHERE `codeTafzili`={$code}";
            $res = $db->ArrayQuery($sql);
            if (count($res) == 1) {
                return $res;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    public function commentDepositHTM($pid,$deleteShow,$payOrReport,$sendMali,$confMali){
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `deposit`.`RowID` AS `did`,`dDate`,`depositor`,`dBank`,`dAmount`,`dDesc`,`fname`,`lname`,`status`,`maliRecord`,`accName` FROM `deposit` 
                INNER JOIN `pay_comment` ON (`pay_comment`.`RowID`=`deposit`.`pid`)
                INNER JOIN `users` ON (`deposit`.`uid`=`users`.`RowID`) WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $display = (intval($deleteShow) == 0 ? 'display: none;' : '');
        $displaySend = (intval($sendMali) == 0 ? 'display: none;' : '');
        $displayConf = (intval($confMali) == 0 ? 'display: none;' : '');
        $htm = '';
        $htm .= '<table style="width:100%" class="table table-bordered table-hover table-sm table-striped" id="OtherInfoDeposit-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<th style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;"> ردیف</th>';
        $htm .= '<th style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">تاریخ واریز</th>';
        $htm .= '<th style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">ثبت کننده</th>';
        $htm .= '<th style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 13%;">طرف مقابل</th>';
        $htm .= '<th style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 13%;">واریزکننده</th>';
        $htm .= '<th style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">نام بانک</th>';
        $htm .= '<th style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 13%;">مبلغ واریزی</th>';
        $htm .= '<th style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">توضیحات</th>';
        $htm .= '<th style="text-align: center;color: #ffc107;font-family: dubai-Bold;'.$display.'width: 5%;">حذف</th>';
        $htm .= '<th style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 7%;">دانلود رسید</th>';
        $htm .= '<th style="text-align: center;color: #ffc107;font-family: dubai-Bold;'.$displaySend.'width: 7%;">ارسال به مالی</th>';
        $htm .= '<th style="text-align: center;color: #ffc107;font-family: dubai-Bold;'.$displayConf.'width: 7%;">تایید مالی</th>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $amount = 0;
        for ($i=0;$i<$cnt;$i++){
            $disable = ($res[$i]['status'] == 0 ? '' : 'disabled');
            $disableConf = ($res[$i]['maliRecord'] == 0 ? '' : 'disabled');
            $iconConf = ($res[$i]['maliRecord'] == 0 ? 'far fa-square' : 'fas fa-check-square');
            $btnConf = ($res[$i]['maliRecord'] == 0 ? 'btn-danger' : 'btn-success');
            $amount += $res[$i]['dAmount'];
            $counter=$i+1;
            $dDate = (strtotime($res[$i]['dDate']) > 0 ? $ut->greg_to_jal($res[$i]['dDate']) : '');
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$counter.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$dDate.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['accName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['depositor'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['dBank'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res[$i]['dAmount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 0px;"><p style="width:100%;height:100%;word-break: break-all;">'.$res[$i]['dDesc'].'</p></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;'.$display.'padding: 10px;"><button '.$disable.' class="btn btn-danger" onclick="deletePaymentReceipt('.$res[$i]['did'].')"><i class="fas fa-trash-alt"></i></button></td>';
            if (intval($payOrReport) == 0) {
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="downloadPaymentReceipt(' . $res[$i]['did'] . ')"><i class="fas fa-file"></i></button></td>';
            }else{
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="downloadPaymentReceiptReport(' . $res[$i]['did'] . ')"><i class="fas fa-file"></i></button></td>';
            }
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;'.$displaySend.'padding: 10px;"><button '.$disable.' class="btn btn-info" onclick="sendDepositToMali(' . $res[$i]['did'] . ')"><i class="fas fa-paper-plane"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;'.$displayConf.'padding: 10px;"><button '.$disableConf.' class="btn '.$btnConf.'" onclick="confirmedDepositVsMali(' . $res[$i]['did'] .','.$pid.')"><i class="'.$iconConf.'"></i></button></td>';
            $htm .= '</tr>';
        }

        $sql1 = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$pid}";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);
        $checks = 0;
        for ($j=0;$j<$cnt1;$j++){
            $checks += $res1[$j]['check_amount'];
        }

        $sql2 = "SELECT `rAmount`,`type`,`accName`,`bankID` FROM `return_money` WHERE `pid`={$pid}";
        $rst = $db->ArrayQuery($sql2);
        $cnt2 = count($rst);
        $rAmount = 0;
        for ($x=0;$x<$cnt2;$x++){
            $rAmount += $rst[$x]['rAmount'];
        }

        $sql3 = "SELECT `fAmount`,`unCode` FROM `fraction_money` WHERE `pid`={$pid}";
        $rstf = $db->ArrayQuery($sql3);
        $cnt3 = count($rstf);
        $fAmount = 0;
        for ($x=0;$x<$cnt3;$x++){
            $fAmount += $rstf[$x]['fAmount'];
        }

        $sql4 = "SELECT `fAmount`,`pid` FROM `fraction_money` WHERE `cid`={$pid}";
        $rstx = $db->ArrayQuery($sql4);
        $cnt4 = count($rstx);
        $fxAmount = 0;
        for ($x=0;$x<$cnt4;$x++){
            $fxAmount += $rstx[$x]['fAmount'];
        }


        $query = "SELECT `CashSection`,`NonCashSection`,`Amount`,`sendType` FROM `pay_comment` WHERE `RowID`={$pid}";
        $result = $db->ArrayQuery($query);
        $remainingNaghdi = (intval($result[0]['CashSection']) > 0 ? $result[0]['CashSection'] - $amount : 0);
        $remainingCheck = (intval($result[0]['NonCashSection']) > 0 ? $result[0]['NonCashSection'] - $checks : 0);
        $remainingFinal = ($result[0]['Amount'] + $rAmount + $fAmount) - ($amount + $checks + $fxAmount);
        $colorNaghdi = (intval($remainingNaghdi) < 0 ? 'color: #db0000;' : '');
        $colorCheck = (intval($remainingCheck) < 0 ? 'color: #db0000;' : '');
        $colorFinal = (intval($remainingFinal) < 0 ? 'color: #db0000;' : '');
        $htm .= '</tbody>';
        $htm .= '</table>';
        $htm .= '<table class="table table-borderd table-striped" style="width:100%;"><tr class="table-info">';
        $htm .= '<td colspan="4"style="text-align: center;font-family: dubai-bold;padding: 10px;">جمع پرداختی نقدی : '.number_format($amount).' ریال</td>';
        $htm .= '<td colspan="7" style="'.$colorNaghdi.'text-align: center;font-family: dubai-bold;padding: 10px;">مانده نقدی : <span dir="ltr">'.number_format($remainingNaghdi).'</span> ریال</td>';
        $htm .= '</tr>';
        $htm .= '<tr class="table-info">';
        $htm .= '<td colspan="4"style="text-align: center;font-family: dubai-bold;padding: 10px;">جمع پرداختی چک : '.number_format($checks).' ریال</td>';
        $htm .= '<td colspan="7" style="'.$colorCheck.'text-align: center;font-family: dubai-bold;padding: 10px;">مانده چک : <span  dir="ltr">'.number_format($remainingCheck).'</span> ریال</td>';
        $htm .= '</tr>';
        for ($x=0;$x<$cnt2;$x++) {
            $sqlbank = "SELECT `Name` FROM `company_banks` WHERE `RowID`={$rst[$x]['bankID']}";
            $rstb = $db->ArrayQuery($sqlbank);
            $person = ($rst[$x]['type'] == 0 ? $rst[$x]['accName'] : $rstb[0]['Name']);
            $htm .= '<tr class="table-info">';
            $htm .= '<td colspan="4"style="text-align: center;font-family: dubai-bold;padding: 10px;">مبلغ عودت وجه : ' . number_format($rst[$x]['rAmount']) . ' ریال</td>';
            $htm .= '<td colspan="1"style="text-align: center;font-family: dubai-bold;padding: 10px;">' . ($rst[$x]['type'] == 0 ? 'مشتریان' : 'واریز به بانک') . '</td>';
            $htm .= '<td colspan="6"style="text-align: center;font-family: dubai-bold;padding: 10px;">' . $person . '</td>';
            $htm .= '</tr>';
        }
        for ($x=0;$x<$cnt3;$x++){
            $htm .= '<tr class="table-info">';
            $htm .= '<td colspan="4"style="text-align: center;font-family: dubai-bold;padding: 10px;">مبلغ کسر از اظهارنظر : ' . number_format($rstf[$x]['fAmount']) . ' ریال</td>';
            $htm .= '<td colspan="7"style="text-align: center;font-family: dubai-bold;padding: 10px;">کد یکتا اظهارنظر دارای کسری : ' . $rstf[$x]['unCode'] . '</td>';
            $htm .= '</tr>';
        }
        for ($x=0;$x<$cnt4;$x++){
            $sqlCode = "SELECT `unCode` FROM `pay_comment` WHERE `RowID`={$rstx[$x]['pid']}";
            $ress = $db->ArrayQuery($sqlCode);
            $htm .= '<tr class="table-info">';
            $htm .= '<td colspan="4"style="text-align: center;font-family: dubai-bold;padding: 10px;">مبلغ کسر شده از اظهارنظر : ' . number_format($rstx[$x]['fAmount']) . ' ریال</td>';
            $htm .= '<td colspan="7"style="text-align: center;font-family: dubai-bold;padding: 10px;">کد یکتا اظهارنظر دارای مازاد : ' . $ress[0]['unCode'] . '</td>';
            $htm .= '</tr>';
        }
        $htm .= '<tr class="table-info">';
        $htm .= '<td colspan="4"style="text-align: center;font-family: dubai-bold;padding: 10px;">مبلغ کل اظهارنظر : '.number_format($result[0]['Amount']).' ریال</td>';
        $htm .= '<td colspan="7" style="'.$colorFinal.'text-align: center;font-family: dubai-bold;padding: 10px;">مانده نهایی (چک + نقدی) : <span  dir="ltr">'.number_format($remainingFinal).'</span> ریال</td>';
        $htm .= '</tr>';
        //$htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function otherInfoPayCommentHTM($did){
        $acm = new acm();
        if(!$acm->hasAccess('payCommentManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `unCode`,`type`,`receiverDate`,`receiverTime`,`fname`,`lname`,`descKesho`,`accName`,`accNumber`,`accBank`,`codeTafzili`,`BillingID`,`PaymentID`,`contractNumber`,`tick`,`checkNumber`,`checkDate`,`checkCarcass`,`checkDeliveryDate`,`desc`,`cardNumber`
                FROM `pay_comment` INNER JOIN `users` ON (`pay_comment`.`senderUid`=`users`.`RowID`) WHERE `pay_comment`.`RowID`={$did}";
        $res = $db->ArrayQuery($sql);
        $name = $res[0]['fname'].' '.$res[0]['lname'];
        $tick = (intval($res[0]['tick']) == 0 ? 'خیر' : 'بلی');
        $receiverDate = $ut->greg_to_jal($res[0]['receiverDate']);
        $checkDate = (strtotime($res[0]['checkDate']) > 0 ? $ut->greg_to_jal($res[0]['checkDate']) : '');
        $checkDeliveryDate = (strtotime($res[0]['checkDeliveryDate']) > 0 ? $ut->greg_to_jal($res[0]['checkDeliveryDate']) : '');
        switch (intval($res[0]['checkCarcass'])){
            case 0:
                $checkCarcass = '-------------';
                break;
            case 1:
                $checkCarcass = 'داده شده است';
                break;
            case 2:
                $checkCarcass = 'داده نشده است';
                break;
        }
        $button = '<button class="btn btn-info" onclick="downloadCheckCarcassFile('.$did.')"><i class="fas fa-download"></i></button>';

        if ($res[0]['type'] == 'پرداخت قبض' || $res[0]['type'] == 'پرداخت جریمه') {
            $arr = array($res[0]['unCode'],$res[0]['type'], $receiverDate, $res[0]['receiverTime'], $name, $res[0]['descKesho'], $res[0]['BillingID'], $res[0]['PaymentID'], $res[0]['desc']);
            $infoNames = array('شماره یکتا','نوع', 'تاریخ ارسال اظهارنظر', 'ساعت ارسال اظهارنظر', 'شخص ارسال کننده', 'توضیحات کشو پرداخت', 'شناسه قبض', 'شناسه پرداخت','توضیحات');
        }else{
            $arr = array($res[0]['unCode'],$res[0]['type'], $receiverDate, $res[0]['receiverTime'], $name, $res[0]['descKesho'],$res[0]['accName'],$res[0]['accNumber'],$res[0]['accBank'],$res[0]['codeTafzili'],$res[0]['contractNumber'],$tick,$res[0]['checkNumber'],$checkDate,$checkCarcass,$checkDeliveryDate,$button,$res[0]['desc'],$res[0]['cardNumber']);
            $infoNames = array('شماره یکتا','نوع', 'تاریخ ارسال اظهارنظر', 'ساعت ارسال اظهارنظر', 'شخص ارسال کننده', 'توضیحات کشو پرداخت','طرف حساب','شماره حساب','نام بانک و صاحب حساب','کد تفضیلی','شماره قرارداد','پرینت گرفته و مستندات پیوست شده است','شماره چک','تاریخ چک','لاشه چک تحویل واحد مالی','تعهد تاریخ تحویل لاشه چک به واحد مالی','رسید تحویل چک','توضیحات','شماره کارت');
        }
        $cnt = count($arr);

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoPayComment-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">سایر اطلاعات</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مقدار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$arr[$i].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function checkLeftOverCheki($cid,$CAmount){
        $db = new DBi();
        $sql = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$cid}";
        $res = $db->ArrayQuery($sql);

        $query = "SELECT `NonCashSection` FROM `pay_comment` WHERE `RowID`={$cid}";
        $rst = $db->ArrayQuery($query);

        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            $CAmount += $res[$i]['check_amount'];
        }
        if ($CAmount > $rst[0]['NonCashSection']){
            return 'yes';
        }else{
            return 'no';
        }
    }

    public function addChecksToComment($cid,$CDate,$CAmount,$CNum,$CType,$desc){
        $acm = new acm();
        if(!$acm->hasAccess('payCommentManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $CDate = $ut->jal_to_greg($CDate);

        $query = "SELECT `accountID` FROM `pay_comment` WHERE `RowID`={$cid}";
        $rst = $db->ArrayQuery($query);
        if (intval($rst[0]['accountID']) <= 0){
            $rst[0]['accountID'] = 'NULL';
        }

        $sql = "INSERT INTO `bank_check` (`cid`,`check_amount`,`check_date`,`check_number`,`uid`,`accID`,`checkType`,`description`) VALUES ({$cid},{$CAmount},'{$CDate}','{$CNum}',{$_SESSION['userid']},{$rst[0]['accountID']},{$CType},'{$desc}')";
        $res = $db->Query($sql);
        if (intval($res) > 0) {
            return true;
        }else{
            return false;
        }
    }

    public function checkMabaleghVarizi($pid){
        $db = new DBi();
        $sql = "SELECT `Amount` FROM `pay_comment` WHERE `RowID`={$pid}";
        $res = $db->ArrayQuery($sql);

        $totalAmount = 0;
        $query = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$pid}";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0) {
            $cnt = count($rst);
            for ($i=0;$i<$cnt;$i++) {
                $totalAmount += $rst[$i]['dAmount'];
            }
        }

        $sqlCheck = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$pid}";
        $result = $db->ArrayQuery($sqlCheck);
        if (count($result) > 0) {
            $cnt = count($result);
            for ($i=0;$i<$cnt;$i++) {
                $totalAmount += $result[$i]['check_amount'];
            }
        }
        if (intval($totalAmount) < $res[0]['Amount']){
            return true;
        }else{
            return false;
        }
    }

    public function finalApprovalComment($pid){
        $acm = new acm();
        if(!$acm->hasAccess('payCommentManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "SELECT `Amount`,`sendType`,`multipleComment` FROM `pay_comment` WHERE `RowID`={$pid}";
        $res = $db->ArrayQuery($sql);

        $totalAmount = 0;
        $query = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$pid}";
        $rst = $db->ArrayQuery($query);
        if (count($rst) > 0) {
            $cnt = count($rst);
            for ($i=0;$i<$cnt;$i++) {
                $totalAmount += $rst[$i]['dAmount'];
            }
        }

        $sqlCheck = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$pid}";
        $result = $db->ArrayQuery($sqlCheck);
        if (count($result) > 0) {
            $cnt = count($result);
            for ($i=0;$i<$cnt;$i++) {
                $totalAmount += $result[$i]['check_amount'];
            }
        }

        $rAmount = 0;
        $sqlRTN = "SELECT `rAmount` FROM `return_money` WHERE `pid`={$pid}";
        $rstn = $db->ArrayQuery($sqlRTN);
        if (count($rstn) > 0) {
            $cnt = count($rstn);
            for ($i=0;$i<$cnt;$i++) {
                $rAmount += $rstn[$i]['rAmount'];
            }
        }

        $fAmount = 0;
        $sqlFRN = "SELECT `fAmount` FROM `fraction_money` WHERE `cid`={$pid}";
        $rstf = $db->ArrayQuery($sqlFRN);
        if (count($rstf) > 0) {
            $cnt = count($rstf);
            for ($i=0;$i<$cnt;$i++) {
                $fAmount += $rstf[$i]['fAmount'];
            }
        }

        $receiverDate = date('Y/m/d');
       // $receiverTime = date('H:i:s');
       // $$receiverTime=date('H:i:s',strtotime("-1 hour"));
        $receiverTime=$this->current_time;

        if (intval($totalAmount) == 0){
            $result = 0;
        }elseif ((intval($totalAmount) + $fAmount) == ($res[0]['Amount'] + $rAmount)){
            $result = 1;
        }elseif((intval($totalAmount) + $fAmount) < ($res[0]['Amount'] + $rAmount)){
            $result = 2;
        }else{
            $result = 3;
        }
        $sql1s = "UPDATE `pay_comment` SET `paymentStatus`={$result} WHERE `RowID`={$pid}";
        $db->Query($sql1s);

        if ((intval($totalAmount) + $fAmount) >= ($res[0]['Amount'] + $rAmount)){   // پرداختی مازاد یا برابر با مبلغ کل اظهارنظر بود
            if (intval($res[0]['multipleComment']) == 1){  // اظهارنظر چند مرحله ای می باشد
                return -2;
            }
           // $sql1 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=48";  // کاربری که به تاییدیه مالی دسترسی دارد
            $sql1 = "SELECT `user_id` FROM access_table AS at LEFT JOIN users AS u ON at.`user_id`= u.`RowID`  WHERE `item_id`= 48  AND u.`IsEnable`= 1";
            $rst1 = $db->ArrayQuery($sql1);
            $receiver = $rst1[0]['user_id'];

            $sqq = "UPDATE `pay_comment` SET `transfer`=2,`lastReceiver`={$receiver} WHERE `RowID`={$pid}";
            $db->Query($sqq);
            $res1 = $db->AffectedRows();
            $res1 = (($res1 == -1 || $res1 == 0) ? 0 : 1);
            if (intval($res1)){

            //--------------------------------------------------------------------
                $last_workflow="SELECT RowID FROM `payment_workflow` where `pid`={$pid}   ORDER BY RowID DESC LIMIT 1";
                $res_last_id=$db->ArrayQuery($last_workflow);
                $upadte_sql="UPDATE payment_workflow set done=1 where RowID={$res_last_id[0]['RowID']}";
                $res_update=$db->Query($upadte_sql);
            //-------------------------------------------------------------
                $sqq = "INSERT INTO `payment_workflow` (`sender`,`receiver`,`pid`,`status`,`createDate`,`createTime`) VALUES ({$_SESSION['userid']},{$receiver},{$pid},1,'{$receiverDate}','{$receiverTime}')";
                $db->Query($sqq);

                if($acm->hasAccess('financialConfirmation')) {
                    $res1 = 'yes';
                    return $res1;
                }else{
                    $res1 = 'no';
                    return $res1;
                }
            }else{
                return false;
            }
        }else{    // پرداختی کمتر از مبلغ کل اظهارنظر بود
            if ($res[0]['sendType'] == 0) 
            { // فورج نقدی بود
                //$sql2 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=43";  // کاربری که به سهامی دسترسی دارد
                $sql2 = "SELECT `user_id` FROM access_table AS at LEFT JOIN users AS u ON at.`user_id`= u.`RowID`  WHERE `item_id`= 43  AND u.`IsEnable`= 1";
                $rst2 = $db->ArrayQuery($sql2);
                $receiver = $rst2[0]['user_id'];

                $sql1 = "UPDATE `pay_comment` SET `payType`=1,`lastReceiver`={$receiver} WHERE `RowID`={$pid}";
                $db->Query($sql1);
                $result = $db->AffectedRows();
                $result = (($result == -1 || $result == 0) ? 0 : 1);
                if (intval($result)) {

                    //--------------------------------------------------------------------
                    $last_workflow="SELECT RowID FROM `payment_workflow` where `pid`={$pid}   ORDER BY RowID DESC LIMIT 1";
                    $res_last_id=$db->ArrayQuery($last_workflow);
                    $upadte_sql="UPDATE payment_workflow set done=1 where RowID={$res_last_id[0]['RowID']}";
                    $res_update=$db->Query($upadte_sql);
                    //-------------------------------------------------------------
                    $sqq = "INSERT INTO `payment_workflow` (`sender`,`receiver`,`pid`,`status`,`createDate`,`createTime`) VALUES ({$_SESSION['userid']},{$receiver},{$pid},1,'{$receiverDate}','{$receiverTime}')";
                    $db->Query($sqq);
                    if ($acm->hasAccess('financialConfirmation')) {
                        $result = 'yes';
                        return $result;
                    } else {
                        $result = 'no';
                        return $result;
                    }
                } 
                else 
                {
                    return false;
                }
            }
            else
            {  // اگر سهامی یا فورج چک بود
                return -1;
            }
        }
    }
    //----------------------------------------------------------------------------------
        
    public function select_receivers_id_related_barname($cid_arr)
    {
        $db = new DBi();
        $ut = new Utility();
		$acm = new acm();
        $pid=$cid_arr['row_id'];
        $sendtype=$cid_arr['send_type'];
        $related_barname=$cid_arr['barname'];
        $ut->fileRecorder($cid_arr);
        $this->set_comment_view_date($pid,'ارجاع اظهارنظر');
      //  $sqq = "SELECT `Transactions`,`checkOutType`,`related_vat` FROM `pay_comment` WHERE `RowID`={$pid}";
        //$rsq = $db->ArrayQuery($sqq);
        $html_seen_sign='<img  width="25" height="auto" src="data:image/png;base64, iVBORw0KGgoAAAANSUhEUgAAAQAAAACACAYAAADktbcKAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAB3RJTUUH5goNCQAl8MZ9iwAAAAZiS0dEAAAAAAAA+UO7fwAAENJJREFUeNrtnWmMnlUVx/vOdKbTMl0obYGu0wJVRBM1LmjciChLt+f+z/M+9xQxMTFuiUTxi4iAG9FoDIKighQUjBoVE437Cp9AoKUL7XRKaUtlX1oodKUzjB96nmTETpnOc++Z973vOcn7od86J+d/nnvPPed3xo0zMxsjyzKuEXEXkJ/q3Mr3A/wVgG8D+CtH/p2fSsRdWZbVzFvDG8A1gLuAlQsBvojIXw3wNQBf4px/j3N8MhF35jmbH80aw4h8l3P+TCL+KRHvJeLBo/z2EfGtztXnExWd5rVXJtB6zbmiC+A3EvHtw/hwEOBdRPxlIl7oXH18lmXmPLMx/WJNJfIfAfwGIu4fLnDl1w/41UR+OcDd5r2hX31/MuA/QeR3vIoPy99fgeKDQH2CedBsrAJ3OsCXE/FjIwza8rcN4I9mWb3TfMjjAJ5DxNcepw8HifxOIj4b4HaLRjPtwJ0J8GVE/MjxBy4PEvEdQPFm8yPPJuLrRunDQSL+rXN+bpbVrSZgpha0s0T8/6kQuC8Q+W8DfnoL+3EOEX+ngg+lLuA/BRQdFplmGkF7MsCfJ+KdVQOXiO8C/Htb1I/zRfyHq/vRryHykyw6zbTE/3AA8Q8S8WYin7egH3vk2H8ghB8Bf8A5PtUi1Cz2sT+k+AeJ+HkivrTF/LiIiL9LxC8G9OMg4M+xKDWLFbQz5M6/M2TQyu+rLeTH04j4e5L4AvvRO4tUsxhBeyLAX6hY8Bvu9xwRf7ZF/Hg6EV9PxLsj+HGQiN9h0WoWOminyDv/o5GCtpfIUwuJf1ccP/oDAJ9iEWsWMmgnAXwFET8eSfyDRHwnkPaXS479EcXPg0T+PnsFMAsZtOMBvpKIn4wo/l0yMDS5Be78uyOKfz/gVwJ+vEWuWajAvYqIn40o/sNE/rfO8eKEfbhQxP9cRD8OEvlbAD8zy9gC1yxI4H4x7heLB4l4A8BLsyzNHnaAF8hT356YfgS4F+A3Ar7NItcsROBeEfeuyoNE3AdwBvCERH04TwZ79sUVv38YKC50zgaqzMJ9+WOLfxvAywEen6gPZxPxNUR8KLL4dwLFUudogvEAzEIU/K6KfOx/mYi3A3wBkB7JRig+5UjvQEw/Av4hoFiSZa7DxG9WNXAnSbX/WQXxn5+oD2ujn+c/rt8A4PuAYkWWZTb5Z1Y5cKfInf/JyIFr4q/+6wf8RqAgE79ZiMA9UTr8HjfxN7z4DwN+A1B4E79ZiMCdIb39j0YO3K0AL0nYj1VJPiPtl1gHFCuzLLNqv1nloA1B8hlRjz/AKwBuS1j8sb/8LxH5tUDxIRO/WYigDUnyebUmHwdwR6J+nCckn4HIX/61QHGxid8spPgfjiz++wEGwF2J+rFHvvwHTfxmzXTs1xD/aoBzgCcl6seF0t67N7L41wPFRSZ+sxBBOzMiyWfo714R/wmJ+rGc6tsT+anvAaBgE79ZiKCdrlTwuwfgeqqbfgTmEXuqbwDwvUBRN/GbhQjaqfLO/4iS+CcnLP7rY89IAH4LUDh75zcLEbQnyGDPYwrHfhN/dfHvAIqlJn6zEEHbqUDyGVrwS/XYf5qS+HeK+I3mYxYkcL9ExM8oPPWlXPBbpEHykXn+pc7B7vxmQQJXi+SDhJ/6enRIPv5hoFhi8/xmoQJXg+TTKx1+qTb5zJfe/r064s+7TPxmob78uxQGe1Yk3N5bbuk9YCQfs2YJ2nYp+O1SGOldkuJgD8DlYM93iLg/Mslne3nnN/GbVQ3cLsF47TKSz6h9WM7zX6dA8tkCFMus2m8WInAny53/KRN/ZfFrkHw2AQXsnd8sROAayad5xH9YevsLE79ZiMA9SYnksw3gCxL2o4r4ZarPSD5mQYJWi+TTJ9z+WqJ+PFXhzm8kH7OgQatF8nlANvakurRjrizteFlB/AbzMAsq/tgwj7XS4Zfquq4Fcuw/ZCQfMxP///7WAEwAT0zUjwvl2L+P4tN77dhvFiRoS5JPbPHf1wKDPd8l4hciP/VtsIKfWaigPUkR41VPfKRXg+SzSZ76TPxmlYN2mjz1Gcmnmh9LmMfuyO29fbauyyxU0HbLYM+jRvIJIv7YMI9tQLHcxG8WImgnSHvvEwp3/pTFbyQfs6YL2jYZ7HlaieST6p2/JPns1iD5ZBnsy28WJHA1YB7r5akvdZLP80byMWumwNWAeWxqEZLPi0byMTPx/+/vQWnv7UzUh3MF5rHfvvxmzRK0NSXxbwd4GcDtCfqwHOy55kgXng6918RvVjVwJwi6e3dkmMc2I/kEafJ50Kr9ZqECt1sYfk8byaey+DVIPr22rsssVOBOk2P/EwowDxN/GJJP3cRvFiJwS5JP7F19OwA+L1EfapN82MRvFiJwy6m+2L39WwBenrAfNUg+5UjvRTbYYxYiaE9WmurbKE997Yn6sST5GMbLrKnEr4HxWickn1Tf+efLO/9hI/mYNZv4jeRTzY89cuzfbySfAJZl3Omcnw3wmwA+y7liWpZlbSbZoEE7S0n8Jckn1d7+kuTzogLJJ907f5ZlbYDvAfjjRPxtIv4JEf+ZiH9HxD8A/NfkKzLV5Fs5aGcYySeIH0uSz/ORm3w2AoVPVvxExSSgOB/wvzjGE9RLRNxL5L/lXHFWltXbTcqjCtoT5anvP0byqeRHLZLPZqDIk33qI/JTiPxniHjNCAsoe4j4HwBfmCoXPmLQTpZ1XUbyCSP+2DCPrUCxIlnx5zl35DmfN8q35w2yCrpm0h5R0E6Uef7HjeRT+divRfJZkqz4syyrOVefB/CdFRy1GeAPmrxfNWjbheTzlEK130g+wUg+riPhoPQdgP90gA0ofQB/wGR+zMC9koifVXjnp4S5/Qul2v+czfOHO5L+PdBUmSWB4f2sMc+/UZp8Un3nXyDv/C8oiT99kg/guwEf6qs0YEngqIGrwfDrk/beVHf1zZXBnn0m/oDmHL8u8PbT8iRwrklf7cu/XVZ0p7qltyT5HFIo+C1tKYyXJIBY02YXpDpwMoKg7ZCC324NmEeKrzCvIPm8HPmdf+uRar/raCmMFxFPluaeWHfSpakOnhwjcCdJwe8ZI/lUFn/sef4BafJZ0ZLz/BKsd0auSi9LFTF9FH9OkWP/k0byaXjx90t7b96yMA8i30nkrybi/oiOXg3wilQHUYYE7nTp8HtM4c6fqvjHKWK8Nkhvf+uSfLIsqwH1BUR+nUJPepbw+/QMpS29WwFemnASnW0kH/VTQNFJ5EmhSeXuFJOAjPRepjDY0ysnqbZExT9HYB5G8hmDq8BJRP7a2OuSiPguWTvVnUjQapF81ovfOhIVf0ny6Y/85TeSz3BXAefqiwB/c+xOqyFJ4IRExB8b5nG/tPemuquvJPkcMJLP2CaBNqB+BuBvknHf2Ekga9bCoCLJZ3WLkHz2KqC77c4/siRQvBbwP1JIAv+WO+3EJgtabZJPqoXTkuSzJ/JT3wPC7TfxH0cSOBPwNyokgXuljXVCkwTtdGWST6ojvSXM47nITT69trGn8ZPAGmkW6mjwoJ0i7/yPKInfSD7VevsftF194a4DLyhUuZc06hOXdEx+UaHJxzBeQWEeJv4QSWAx4FcpJIHeRuxwk8GeKxXae1cnTvIp7/xa4jdWZcDXgdMB/2OFPoGGGyWWqb5nIv/da1uA5PM9PZIPrOAXOgk4V+8B/G2Rn2waiiwkx/7dkcW/IXGST4889e3REX8LzfMrJ4Gac/lcwP888vqlhkgCSiSfzdIUlSrJZ54M9uzVEX/eZeKPnwRmAf7XRHxQIQmcO0aBq0HyeUj6IFJt750t7b0HI4u/9Ug+Y5wExmUZpgL+dqUkcL7W64Cgu6+MLP4S5nFhiL8ry+rjgKLduWKmc8X7ZK3bzUS8CuCPOeff5hxPA4r2LKtr+LAm4r9WOJExST7bjhT80GHi108E3XISOKDwOrAk9pdSCMlXRZ6KDEryIfJtzhUzgeKTRP4YnYl+J1B83LliBpGvRRb/HIWR3gHA9wHFcqv2N0YSOKjQJ7As1l1Z1nVdEXlpR3DxE/nFRP4mwO8bwVF5H5G/kYgXxzhRKZN8NgEF2Tt/4ySB3yicBNZI2/DEwIF7onT4xV7XFUz8ec41Ip5P5P9w/Hdm/ifAbwiZBBTFX5J8ChN/A5lzmA74X8XmtkunXDC82BCSz6MKBb8LQ9VggPokIr5s9IWzI0kgYAJQEb+M9K60wZ4GLAw6l58C+J/FfvKRKcLKZCFFks/mkCQf5+o1oDgbqNacBPC/AH59oGp/bPEbyacJkoAsGvW3NjpZSJHksyE0yQfwbYD/ZpgnNP4rwG+qIP55srRjQEH8RvJpkiSwEPC3NCpZSJHks1Y6/IIWLom4nYj/Eq6gxn8G+K2jEP8C+fIfinzsN/E3WRIoyUKrFMlCJzSY+FdLb3/w9l4i3x64YHmIiP8I8NnHIf5yS+++yOJfb8f+pk0CxWuUyEJ3jwQvBvBMRZJPHmuwh4jHR/g/HyDiPwD8zhGIX5PkYwW/Jk8Cr1WCitwjhbauYYL2JCXxRyf5yBVgR4T/+34i/j3A7xqB+GOTfDbJU5+JP4EkoEUWWi3NQp2vCNppSks7VEg+RxqA+JeR/oZ9kgTefRTxlzCP3ZHbe7cABeydP72TgMZ1YJ0sJG2XoO1OjeQDcBsRXx7xb/m/JKBI8tkOFMtM/OnWBDTIQhtlNfkEGex5QuHkoQbwlP15ZwLcp5AE3iXHfg3x7zSST2u8Dtyi0CewhYi/T8RPKyztyLVJPs5xB8AXE/n9kZPAHUT8p9hQlBLjZSSfFkgC0idwq0LHYOwks16e+ibp+5HHATxDnuJi/o2HIr/zG8mnBZNA2TH4M4XZgZgjym4s13UB3CbNON9vUh8ayae1k0B+igwQHWiywN3aKCQfmcJbQMQ/aELxG8mnxZNAOUX4GwWeQMiR3qWNtL9AksB8SQIvN4n4d5R3fhO/JQItslBVmMe2RtxbMCQRzJNq/aEG9uOAvPNbtd/sqEngYIOKf3sji39IEuiRk0Aj1lb6ZVefresyGzYJ3N6ASaApxD8kCSwi4h8q9FscL8nnAVvUafYqSQBT5SSw38RfKQmcTsQ3EPHzjSB+meqzFd1mIykM5rNk+cjeMQ7cPmEQ1prRlwCfQcQ3xl7HNcJ5/otM/GYjTQLlBqLbFJp5jtVOnAHc1IUqgBePYRIwko9ZlSRQ75GFpNp32XUxSD4tlgSM5GNWOQmUW4lXKSaBNbFIPg2QBG5QmMYcSu81ko9ZiCRQLFYaJb5vLAZ7lGsCN0S+VvULt99IPmZBk0BsstC9miO9Y5gETpcnwv2Rmnw2AoU38ZvFSAKxyEL3aME8GiQJLJIBopcCk3z6bF2XmUYSCHkduLeVxD8kCSyQtuFQvf0PAcUKE7+Z1nXgpgCFwftaUfxDksBcuQ6EmOpbYuI3U0wC9TMAf3OFgtYaKfh1t7Ivq/IESpJPljkTv5luEnCuvgjwPxlFx+C6sSL5NGACqI02CRjJx2ysk0AN8LMAvnSE230PAvw3Ij4ntXf+ikmgjYjnEPHlAD87wgRwO+Df4lxu8/xmYx7AE50r3g74vxxjFn4XEX/DufrcPGebQ3+F5TnXgGIyUJxLxH87xlf/EcBf4pyf3UhQFLOWPw3U2wA/0Tl+TZ7zeQBfTeSvB/zn8pzfR8TdRNyZZVnNvDXsiWocUG8n8hPznE8j4g8T+a8f2f7rLwH8u53jmc4VHVlWNz8mYP8FKRwer1AweCYAAAAldEVYdGRhdGU6Y3JlYXRlADIwMjItMTAtMTNUMDk6MDA6MzcrMDA6MDAleNgAAAAAJXRFWHRkYXRlOm1vZGlmeQAyMDIyLTEwLTEzVDA5OjAwOjM3KzAwOjAwVCVgvAAAAABJRU5ErkJggg==">';
		$html_thick="&#x2713";
        $sqlun = "SELECT `unitID` FROM `users` WHERE `RowID`={$_SESSION['userid']}";
        $rstun = $db->ArrayQuery($sqlun);
        $rids = '';
        $rowids = array();
        if(!empty($related_barname) AND $related_barname>0 )
        {
            $get_mata_sql = "SELECT p.`RowID`,p.`sendType` FROM `pay_comment` as p   WHERE p.`RowID`={$pid}";
            $get_meta_res = $db->ArrayQuery($get_mata_sql);
            $ut->fileRecorder('get_mata_sql:'.$get_mata_sql);
            if($related_barname == 1){//بارنامه صادره می باشد
                if($sendtype==0 || $sendtype==1){
                    $ut->fileRecorder('sendtype'.$sendtype);
                    $rowids=$ut->get_full_access_users(16);
                }
                elseif($sendtype==2){
                    $ut->fileRecorder('sendtype'.$sendtype);
                    $rowids=$ut->get_full_access_users(17);
                }
            }
            if($related_barname== 2){//   بارنامه وارده می باشد
                $ut->fileRecorder('sendtype'.$sendtype);
                $rowids = $ut->get_full_access_users(15);
            }
            $related_accunting_dep_users_to_barname=$ut->get_full_access_users(19);
            if(in_array($_SESSION['userid'],$related_accunting_dep_users_to_barname)){
                $kesho_sql = "SELECT at.`user_id` from `access_table` AS at LEFT JOIN `users` as u ON at.`user_id`=u.`RowID`  WHERE at.`item_id`=41  AND u.`IsEnable`=1";
                $res_kesho = $db->ArrayQuery($kesho_sql);
                $user_kesho=[];
                foreach($res_kesho as $key=>$value){
                    $user_kesho[]=$value['user_id'];
                }
                unset($res_kesho[array_search(1,$res_kesho)]);
                foreach($user_kesho as $userid){
                    $rowids[]=$userid;
                }
                
            }
                 //  break;
           // }
            $user_array_key = array_search($_SESSION['userid'],$rowids);
            $rids = implode(',',$rowids);
        }
        else{
            $sqlu = "SELECT `uids` FROM `relatedunits` WHERE `RowID` IN (20,23)";  // تدارکات - مالی
            $rstu = $db->ArrayQuery($sqlu);
            for ($i=0;$i<count($rstu);$i++)
            {
                $rowids[] = $rstu[$i]['uids'];
            }
            $rids = implode(',',$rowids);
            $rids .= ',97';          
        }

        $rids_array=explode(",",$rids);
        $sql = "SELECT `payment_workflow`.*,`fname`,`lname` FROM `payment_workflow` INNER JOIN `users` ON (`payment_workflow`.`sender`=`users`.`RowID`) WHERE `pid`={$pid} ";
        $res = $db->ArrayQuery($sql);
        $this->set_comment_view_date ($pid,'مشاهده سوابق ارجاع');
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileComment4-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">فرستنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">گیرنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">وضعیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ ارسال</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زمان ارسال</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">توضیحات</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ویرایش</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">حذف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ارسال</td>';

        if($acm->hasAccess('ViewHistoryCommentManagement'))
        {
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">سوابق مشاهده</td>';
        }
       
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++)
        {
            if($res[$i]['receiver']==$_SESSION['userid']){
                if(!in_array($res[$i]['sender'],$rids_array)){
                    $rids_array[]=$res[$i]['sender'];
                }
            }
            $iterator++;
            $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$i]['receiver']}";
            $rst = $db->ArrayQuery($query);
            $status = ($res[$i]['status'] == 0 ? 'عدم تایید' : 'تایید');
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);
            if ($res[$i]['temp'] == 1){
                $button = '<button type="button" class="btn btn-info" onclick="editTempSendComment('.$res[$i]['RowID'].')" ><i class="fas fa-edit"></i></button>';
                $button1 = '<button type="button" class="btn btn-danger" onclick="deleteTempSendComment('.$res[$i]['RowID'].')" ><i class="fas fa-trash"></i></button>';
                $button2 = '<button type="button" class="btn btn-success" onclick="sendTempSendComment('.$res[$i]['RowID'].')" ><i class="fas fa-paper-plane"></i></button>';
            }else{
                $button = '';
                $button1 = '';
                $button2 = '';
            }
            if($res[$i]['auto_send']==1){

                $htm .= '<tr class="table-secondary"  title="ارسال خودکار">';
                $style="background:green;color:#fff";
            }
            else{
                $htm .= '<tr class="table-secondary">';
                $style="";
            }

            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$rst[0]['fname'].' '.$rst[0]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$status.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$res[$i]['createDate'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$res[$i]['description'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$button.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$button1.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$button2.'</td>';
            if($acm->hasAccess('ViewHistoryCommentManagement'))
            {
                if(!empty($res[$i]['view_history']))
                {
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$html_seen_sign.'</td>';
                }
                else{
                    $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;'.$style.'">'.$html_tick.'</td>';
                }
            }
            $htm .= '</tr>';
        }

        $htm .= '</tbody>';
        $htm .= '</table>';
        $rids=implode(',',$rids_array);
        $squsers = "SELECT `RowID`,`fname`,`lname`,`unitID` FROM `users` WHERE `RowID` IN ($rids) AND `isEnable`=1 AND `RowID`!=1 ORDER BY `lname` ASC";
        $result = $db->ArrayQuery($squsers);
		$finalResult=$result;
		$send = array($htm,$finalResult);
		return $send;
    }

    //----------------------------------------------------------------------------------

    public function sendDepositToMali($pid,$did){
        $acm = new acm();
        if(!$acm->hasAccess('payCommentManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sqls = "SELECT `Amount` FROM `pay_comment` WHERE `RowID`={$pid}";
        $rsts = $db->ArrayQuery($sqls);

        $sqq1 = "SELECT SUM(`dAmount`) AS `dsum` FROM `deposit` WHERE `pid`={$pid}";
        $rsq1 = $db->ArrayQuery($sqq1);

        $sqq2 = "SELECT SUM(`check_amount`) AS `csum` FROM `bank_check` WHERE `cid`={$pid}";
        $rsq2 = $db->ArrayQuery($sqq2);

        $sqq3 = "SELECT SUM(`rAmount`) AS `rsum` FROM `return_money` WHERE `pid`={$pid}";
        $rsq3 = $db->ArrayQuery($sqq3);

        $sqq4 = "SELECT SUM(`fAmount`) AS `fsum` FROM `fraction_money` WHERE `cid`={$pid}";
        $rsq4 = $db->ArrayQuery($sqq4);

        $totalAmount = (intval($rsq1[0]['dsum']) + intval($rsq2[0]['csum']) + intval($rsq4[0]['fsum'])) - intval($rsq3[0]['rsum']);

        if (intval($totalAmount) == 0){
            $result = 0;
        }elseif (intval($totalAmount) == intval($rsts[0]['Amount'])){
            $result = 1;
        }elseif(intval($totalAmount) < intval($rsts[0]['Amount'])){
            $result = 2;
        }else{
            $result = 3;
        }

        $sql1s = "UPDATE `pay_comment` SET `paymentStatus`={$result} WHERE `RowID`={$pid}";
        $db->Query($sql1s);



        $receiverDate = date('Y/m/d');
        $receiverTime = $this->current_time;
        //$receiverTime=date('H:i:s',strtotime("-1 hour"));

       // $sql2 = "SELECT `user_id` FROM `access_table` WHERE `item_id`=48";  // کاربری که به تاییدیه مالی دسترسی دارد
        $sql2 = "SELECT `user_id` FROM access_table AS at LEFT JOIN users AS u ON at.`user_id`= u.`RowID`  WHERE `item_id`= 48  AND u.`IsEnable`= 1";
        $rst2 = $db->ArrayQuery($sql2);
        $receiver = $rst2[0]['user_id'];

        $sql = "UPDATE `pay_comment` SET `multipleComment`=1,`lastReceiver`={$receiver} WHERE `RowID`={$pid}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1) ? 0 : 1);
        if ($res){
            $sql1 = "UPDATE `deposit` SET `status`=1 WHERE `RowID`={$did}";
            $db->Query($sql1);
            $res1 = $db->AffectedRows();
            $res1 = (($res1 == -1) ? 0 : 1);
            if ($res1){
                $sql3 = "UPDATE `payment_attachment` SET `abilityDelete`=1 WHERE `pid`={$pid}";
                $db->Query($sql3);
                //--------------------------------------------------------------------
                $last_workflow="SELECT RowID FROM `payment_workflow` where `pid`={$pid}   ORDER BY RowID DESC LIMIT 1";
                $res_last_id=$db->ArrayQuery($last_workflow);
                $upadte_sql="UPDATE payment_workflow set done=1 where RowID={$res_last_id[0]['RowID']}";
                $res_update=$db->Query($upadte_sql);
                 //-------------------------------------------------------------
                $sqq = "INSERT INTO `payment_workflow` (`sender`,`receiver`,`pid`,`status`,`createDate`,`createTime`) VALUES ({$_SESSION['userid']},{$receiver},{$pid},1,'{$receiverDate}','{$receiverTime}')";
                $db->Query($sqq);
                if($acm->hasAccess('tempFinancialKesho')) {
                    $res1 = 'yes';
                    return $res1;
                }else{
                    $res1 = 'no';
                    return $res1;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function sendPayCommentInPC($cid,$receiver,$desc){
        $acm = new acm();
        $ut=new Utility();
       
        if(!$acm->hasAccess('payCommentManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $cDate = date('Y/m/d');
       // $cTime = date('H:i:s');
        $cTime=$this->current_time;

        $sqq = "SELECT `RowID` FROM `deposit` WHERE `pid`={$cid}";
        $rstq = $db->ArrayQuery($sqq);
        
        $sqq1 = "SELECT `RowID` FROM `bank_check` WHERE `cid`={$cid}";
        $rstq1 = $db->ArrayQuery($sqq1);

        if (count($rstq) > 0 || count($rstq1) > 0){
            return -1;
        }
        //--------------------------------------------------------------------
        $last_workflow="SELECT RowID FROM `payment_workflow` where `pid`={$pid}   ORDER BY RowID DESC LIMIT 1";
        $res_last_id=$db->ArrayQuery($last_workflow);
        $upadte_sql="UPDATE payment_workflow set done=1 where RowID={$res_last_id[0]['RowID']}";
        $res_update=$db->Query($upadte_sql);
        //-------------------------------------------------------------
        $sql = "INSERT INTO `payment_workflow` (`sender`,`receiver`,`pid`,`status`,`createDate`,`createTime`,`description`) VALUES ({$_SESSION['userid']},{$receiver},{$cid},0,'{$cDate}','{$cTime}','{$desc}')";
        $res = $db->Query($sql);
        if (count($res) > 0){
            $senderUid = 'NULL';
            $payType = 'NULL';
            $sql2 = "UPDATE `pay_comment` SET `transfer`=0,`receiverDate`='',`receiverTime`='',`senderUid`={$senderUid},`descKesho`='',`payType`={$payType},`lastReceiver`={$receiver} WHERE `RowID`={$cid}";
            $db->Query($sql2);
            return true;
        }else{
            return false;
        }
    }

    public function getPayCommentExcel(){
        $acm = new acm();
        $db = new DBi();
        $ut = new Utility();

        $w = array();
        if($acm->hasAccess('forjAccess')){
            $w[] = '`payType`=0 ';
        }elseif ($acm->hasAccess('sahamiAccess')){
            $w[] = '`payType`=1 ';
        }
        $w[] = '`transfer`=1 AND `isEnable`=1 ';

       //$sql = "SELECT `cDate`,`accName`,`codeTafzili`,`Toward`,`Amount`,`CashSection`,`NonCashSection`,`paymentMaturityCash`,`paymentMaturityCheck`,`desc`,`sendType` FROM `pay_comment ";
        $sql = "SELECT `cDate`,`accName`,`codeTafzili`,`Toward`,`Amount`,`CashSection`,`NonCashSection`,`paymentMaturityCash`,`paymentMaturityCheck`,`desc`,`sendType` FROM `pay_comment` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
		//////$ut->fileRecorder('sqlsql:'.$sql);
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            $res[$i]['cDate'] = $ut->greg_to_jal($res[$i]['cDate']);
            $res[$i]['paymentMaturityCash'] = (strtotime($res[$i]['paymentMaturityCash']) > 0 ? $ut->greg_to_jal($res[$i]['paymentMaturityCash']) : '');
            $res[$i]['paymentMaturityCheck'] = (strtotime($res[$i]['paymentMaturityCheck']) > 0 ? $ut->greg_to_jal($res[$i]['paymentMaturityCheck']) : '');
            switch ($res[$i]['sendType']){
                case 0:
                    $res[$i]['sendType'] = 'فورج نقدی';
                    break;
                case 1:
                    $res[$i]['sendType'] = 'فورج چک';
                    break;
                case 2:
                    $res[$i]['sendType'] = 'سهامی';
                    break;
                case 3:
                    $res[$i]['sendType'] = 'ثبت در حساب بستانکاری طرف مقابل';
                    break;
            }
        }
        return $res;
    }

    public function getDepositExcel($pid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `deposit`.*,`accName`,`fname`,`lname` FROM `deposit`
                INNER JOIN `pay_comment` ON (`pay_comment`.`RowID`=`deposit`.`pid`)
                INNER JOIN `users` ON (`deposit`.`uid`=`users`.`RowID`) WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            $res[$i]['fname'] = $res[$i]['fname'].' '.$res[$i]['lname'];
            $res[$i]['dDate'] = $ut->greg_to_jal($res[$i]['dDate']);
        }
        return $res;
    }

    //++++++++++++++++++++ واریزکنندگان ++++++++++++++++++++

    public function depositorInfo($did){
        $db = new DBi();
        $sql = "SELECT * FROM `depositors` WHERE `RowID`=".$did;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("did"=>$did,"Name"=>$res[0]['Name'],"codeTafzili"=>$res[0]['codeTafzili']);
            return $res;
        }else{
            return false;
        }
    }

    public function getDepositorManageList($Name,$Code,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('depositorsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($Name)) > 0){
            $w[] = '`Name` LIKE "%'.$Name.'%" ';
        }
        if(intval($Code) > 0){
            $w[] = '`codeTafzili`='.$Code.' ';
        }

        $sql = "SELECT * FROM `depositors`";
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
            $finalRes[$y]['Name'] = $res[$y]['Name'];
            $finalRes[$y]['codeTafzili'] = $res[$y]['codeTafzili'];
        }
        return $finalRes;
    }

    public function getDepositorManageListCountRows($Name,$Code){
        $db = new DBi();
        $w = array();
        if(strlen(trim($Name)) > 0){
            $w[] = '`Name` LIKE "%'.$Name.'%" ';
        }
        if(intval($Code) > 0){
            $w[] = '`codeTafzili`='.$Code.' ';
        }

        $sql = "SELECT `RowID` FROM `depositors`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function createDepositor($Name,$code){
        $acm = new acm();
        if(!$acm->hasAccess('depositorsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $query = "SELECT `codeTafzili` FROM `depositors`";
        $rst = $db->ArrayQuery($query);
        $cnt = count($rst);
        for ($i=0;$i<$cnt;$i++){
            if ($rst[$i]['codeTafzili'] == 77777){
                continue;
            }else{
                $codes[] = $rst[$i]['codeTafzili'];
            }
        }
        if (in_array($code,$codes)){
            return -2;
        }else {
            $sql = "INSERT INTO `depositors` (`Name`,`codeTafzili`) VALUES ('{$Name}',{$code})";
            $res = $db->Query($sql);
            if (intval($res) > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function editDepositor($did,$Name,$code){
        $acm = new acm();
        if(!$acm->hasAccess('depositorsManage')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sqq = "SELECT `codeTafzili` FROM `depositors` WHERE `RowID`={$did}";
        $resqq = $db->ArrayQuery($sqq);
        if ($resqq[0]['codeTafzili'] != $code) {
            $query = "SELECT `codeTafzili` FROM `depositors`";
            $rst = $db->ArrayQuery($query);
            $cnt = count($rst);
            for ($i = 0; $i < $cnt; $i++) {
                if ($rst[$i]['codeTafzili'] == 77777) {
                    continue;
                } else {
                    $codes[] = $rst[$i]['codeTafzili'];
                }
            }
            if (in_array($code, $codes)) {
                return -2;
            } else {
                $sql = "UPDATE `depositors` SET `Name`='{$Name}',`codeTafzili`={$code} WHERE `RowID`={$did}";
                $db->Query($sql);
                $res = $db->AffectedRows();
                $res = (($res == -1 || $res == 0) ? 0 : 1);
                if (intval($res)) {
                    return true;
                } else {
                    return false;
                }
            }
        }else{
            $sql = "UPDATE `depositors` SET `Name`='{$Name}',`codeTafzili`={$code} WHERE `RowID`={$did}";
            $db->Query($sql);
            $res = $db->AffectedRows();
            $res = (($res == -1 || $res == 0) ? 0 : 1);
            if (intval($res)) {
                return true;
            } else {
                return false;
            }
        }
    }

    //++++++++++++++++++++ گزارش پرداخت اظهارنظر ++++++++++++++++++++

    public function getReportPayCommentManageHtm(){
        $acm = new acm();
        $db=new Dbi();
        if(!$acm->hasAccess('reportPayCommentManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
/*        $db = new DBi();

        $sql = "SELECT `RowID`,`Amount` FROM `pay_comment`";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            $sqq1 = "SELECT SUM(`dAmount`) AS `dsum` FROM `deposit` WHERE `pid`={$res[$i]['RowID']}";
            $rsq1 = $db->ArrayQuery($sqq1);

            $sqq2 = "SELECT SUM(`check_amount`) AS `csum` FROM `bank_check` WHERE `cid`={$res[$i]['RowID']}";
            $rsq2 = $db->ArrayQuery($sqq2);

            $sqq3 = "SELECT SUM(`rAmount`) AS `rsum` FROM `return_money` WHERE `pid`={$res[$i]['RowID']}";
            $rsq3 = $db->ArrayQuery($sqq3);

            $sqq4 = "SELECT SUM(`fAmount`) AS `fsum` FROM `fraction_money` WHERE `cid`={$res[$i]['RowID']}";
            $rsq4 = $db->ArrayQuery($sqq4);

            $totalAmount = (intval($rsq1[0]['dsum']) + intval($rsq2[0]['csum']) + intval($rsq4[0]['fsum'])) - intval($rsq3[0]['rsum']);

            if (intval($totalAmount) == 0){
                $result = 0;
            }elseif (intval($totalAmount) == intval($res[$i]['Amount'])){
                $result = 1;
            }elseif(intval($totalAmount) < intval($res[$i]['Amount'])){
                $result = 2;
            }else{
                $result = 3;
            }

            $sql1 = "UPDATE `pay_comment` SET `paymentStatus`={$result} WHERE `RowID`={$res[$i]['RowID']}";
            $db->Query($sql1);
        }*/

        $pagename = "گزارش پرداخت اظهارنظر";
        $pageIcon = "fas fa-chart-area";
        $contentId = "reportPayCommentManageBody";
        $hiddenContentId = 'hiddenPayCommentBody'; //SUM(`check_amount`),SUM(`dAmount`)

        $units = $this->getUnits();
        $CountUnits = count($units);

        $layers = $this->getOneLayers();
        $CountLayers = count($layers);


        $bottons= array();
        $bottons[0]['title'] = "جستجو";
        $bottons[0]['jsf'] = "payCommentReportSearch";
        $bottons[0]['icon'] = "fa-search";

        $headerSearch = array();

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch,'',array(),$hiddenContentId);
        //++++++++++++++++++++++++++++++++++ payComment Search MODAL ++++++++++++++++++++++++++++++++
        $get_pay_type="SELECT typeName from payment_type";
        $type_res=$db->ArrayQuery($get_pay_type);
        $modalID = "payCommentSearchManageModal";
        $modalTitle = "فرم جستجوی اظهارنظرها";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "reportPayCommentManageUncodeSearch";
        $items[$c]['title'] = "کد یکتا";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "reportPayCommentManageSDateSearch";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "از تاریخ (صدور)";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "reportPayCommentManageEDateSearch";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "تا تاریخ (صدور)";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "reportPayCommentManageSDateCashSearch";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "از تاریخ (سررسید نقدی)";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "reportPayCommentManageEDateCashSearch";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "تا تاریخ (سررسید نقدی)";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "reportPayCommentManageSDateNCashSearch";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "از تاریخ (سررسید چکی)";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "reportPayCommentManageEDateNCashSearch";
        $items[$c]['style'] = "style='width: 70%;float: right;'";
        $items[$c]['title'] = "تا تاریخ (سررسید چکی)";
        $items[$c]['placeholder'] = "تاریخ";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "reportPayCommentManageOneLayer";
        $items[$c]['title'] = "انتخاب سرگروه";
        $items[$c]['options'] = array();
        $items[$c]['onchange'] = "onchange=getReportSubLayerTwo()";
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$CountLayers;$i++){
            $items[$c]['options'][$i+1]["title"] = $layers[$i]['layerName'];
            $items[$c]['options'][$i+1]["value"] = $layers[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "reportPayCommentManageTwoLayer";
        $items[$c]['onchange'] = "onchange=getReportSubLayerThree()";
        $items[$c]['title'] = "انتخاب زیرگروه";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "reportPayCommentManageThreeLayer";
        $items[$c]['title'] = "انتخاب زیرگروه فرعی";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "reportPayCommentManageUnitSearch";
        $items[$c]['title'] = "واحد درخواست کننده";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$CountUnits;$i++){
            $items[$c]['options'][$i+1]["title"] = $units[$i]['unitName'];
            $items[$c]['options'][$i+1]["value"] = $units[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "reportPayCommentManageConsumerUnitSearch";
        $items[$c]['title'] = "واحد مصرف کننده";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$CountUnits;$i++){
            $items[$c]['options'][$i+1]["title"] = $units[$i]['unitName'];
            $items[$c]['options'][$i+1]["value"] = $units[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "reportPayCommentManageAccNameSearch";
        $items[$c]['title'] = "نام طرف حساب";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "reportPayCommentManageTowardSearch";
        $items[$c]['title'] = "بابت";
        $items[$c]['placeholder'] = "بابت";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "reportPayCommentManageAmountSearch";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['title'] = "مبلغ";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "reportPayCommentManagePaySendSearch";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "مراحل اظهارنظر";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "اظهارنظرهای صادرشده";
        $items[$c]['options'][0]["value"] = 0;
        $items[$c]['options'][1]["title"] = "اظهارنظرهای بدون هیچ پرداختی";
        $items[$c]['options'][1]["value"] = 1;
        $items[$c]['options'][2]["title"] = "اظهارنظرهای کامل پرداخت شده";
        $items[$c]['options'][2]["value"] = 2;
        $items[$c]['options'][3]["title"] = "اظهارنظرهای ناقص پرداخت شده";
        $items[$c]['options'][3]["value"] = 3;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "reportPayCommentManagePaytypeSearch";
        $items[$c]['title'] = "دسته بندی";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "فورج نقدی";
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = "فورج چک";
        $items[$c]['options'][2]["value"] = 1;
        $items[$c]['options'][3]["title"] = "سهامی";
        $items[$c]['options'][3]["value"] = 2;
        $items[$c]['options'][4]["title"] = "ثبت در حساب بستانکاری";
        $items[$c]['options'][4]["value"] = 3;
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "reportPayCommentManagetypeSearch";
        $items[$c]['title'] = "نوع  پرداخت  اظهارنظر";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        
        for($tc=0;$tc<count($type_res);$tc++){
            $items[$c]['options'][$tc+1]["title"] = $type_res[$tc]['typeName'];
            $items[$c]['options'][$tc+1]["value"] = $type_res[$tc]['typeName'];
        }

        $c++;

        //type_res

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "reportPayCommentManageSortTypeSearch";
        $items[$c]['title'] = "مرتب سازی مبلغ";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "بیشترین مبلغ";
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = "کمترین مبلغ";
        $items[$c]['options'][2]["value"] = 1;

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "showReportPayCommentManageList";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "بازنشانی";
        $footerBottons[1]['jsf'] = "payCommentReportRefreshSearch";
        $footerBottons[1]['type'] = "btn-danger";
        $footerBottons[1]['data-dismiss'] = "No";
        $footerBottons[2]['title'] = "خروجی اکسل";
        $footerBottons[2]['jsf'] = "payCommentReportExcel";
        $footerBottons[2]['type'] = "btn-success";
        $footerBottons[2]['data-dismiss'] = "No";
        $footerBottons[3]['title'] = "انصراف";
        $footerBottons[3]['type'] = "dismis";
        $payCommentSearchModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END OF payComment Search MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++ Deposits List Info Modal ++++++++++++++++++++++
        $modalID = "depositsListInfoModal";
        $modalTitle = "واریزی های انجام شده";
        $style = 'style="max-width: 80vw;"';
        $ShowDescription = 'deposits-manage-Info-body';

        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "depositsListInfoHiddenPid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $footerBottons[1]['title'] = "خروجی اکسل";
        $footerBottons[1]['jsf'] = "depositsListInfoExcel";
        $footerBottons[1]['type'] = "btn";
        $footerBottons[1]['data-dismiss'] = "No";
        $showDepositsInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Deposits List Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Comment Checks Modal ++++++++++++++++++++++
        $modalID = "commentManageChecksModal";
        $modalTitle = "چک/چک ها";
        $style = 'style="max-width: 700px;"';
        $ShowDescription = 'comment-manage-Checks-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showCommentChecks = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Comment Checks Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Comment Account Info Modal ++++++++++++++++++++++
        $modalID = "commentAccountInfoModal";
        $modalTitle = "سایر اطلاعات";
        $ShowDescription = 'commentAccount-manage-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $commentAccountInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Comment Account Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ RequestSupplier File Modal ++++++++++++++++++++++
        $modalID = "paymentReceiptDownloadReportModal";
        $modalTitle = "دانلود رسید پرداخت";
        $ShowDescription = 'paymentReceiptDownloadReport-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $paymentReceiptDownloadR = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End RequestSupplier File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ comment Attachment File Modal ++++++++++++++++++++++
        $modalID = "commentAttachmentFileRptModal";
        $modalTitle = "دانلود فایل های پیوست";
        $ShowDescription = 'commentAttachmentFileRpt-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $commentAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End comment Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ comment Workflow Modal ++++++++++++++++++++++
        $modalID = "commentWorkflowRptModal";
        $modalTitle = "گردش کار اظهارنظر";
        $style = 'style="max-width: 1000px;"';
        $ShowDescription = 'commentWorkflowRpt-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $commentWorkflow = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End comment Workflow Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show Attached Fund To Report Comment Modal ++++++++++++++++++++++
        $modalID = "showAttachedFundToReportCommentModal";
        $modalTitle = "لیست تنخواه ها";
        $ShowDescription = 'showAttachedFundToReportComment-body';
        $style = 'style="max-width: 1200px;"';

        $items = array();
        $c=0;
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "coverReportFundListHiddenID";
        $footerBottons = array();
        // $footerBottons[0]['title'] = "بستن";
        // $footerBottons[0]['type'] = "dismis";
        //---------------------------------------------
        $footerBottons = array();
        
        $footerBottons[0]['title'] = "ارسال به Excel";
        $footerBottons[0]['jsf'] = "getReportFundListExcel";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "روکش تنخواه";
        $footerBottons[1]['jsf'] = "printReportFundCover";
        $footerBottons[1]['type'] = "btn";
        $footerBottons[2]['title'] = "بستن";
        $footerBottons[2]['type'] = "dismis";
        //---------------------------------------------
        $showAttachedFundToReportComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show Attached Fund To Report Comment Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ report Fund List Details MODAL ++++++++++++++++++++++++++++++++
        $modalID = "showFundListDetailsReportModal";
        $modalTitle = "جزئیات تنخواه";
        $style = 'style="max-width: 700px;"';
        $ShowDescription = 'fundListDetails-Report-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $reportFundListDetails = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++++++ End report Fund List Details MODAL ++++++++++++++++++++++++++++++++
        //++++++++++++++++++ fundList Show Attachment File Modal ++++++++++++++++++++++
        $modalID = "reportFundListAttachmentFileModal";
        $modalTitle = "فایل های پیوست";
        $ShowDescription = 'reportFundListAttachmentFile-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $reportFundListAddAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End fundList Show Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ download CheckCarcass File Modal ++++++++++++++++++++++
        $modalID = "downloadCheckCarcassFileRptModal";
        $modalTitle = "دانلود رسید لاشه چک";
        $ShowDescription = 'downloadCheckCarcassFileRpt-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $downloadCheckCarcassFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End download CheckCarcass File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show Separation Total Amount Modal ++++++++++++++++++++++
        $modalID = "showSeparationTotalAmountModal";
        $modalTitle = "جزئیات مبالغ سرگروه اظهارنظر ها";
        $ShowDescription = 'showSeparationTotalAmount-body';
        $style = 'style="max-width: 500px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showSeparationTotalAmount = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show Separation Total Amount Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show Separation Sub Total Amount Modal ++++++++++++++++++++++
        $modalID = "showSeparationSubTotalAmountModal";
        $modalTitle = "جزئیات مبالغ زیرگروه اظهارنظر ها";
        $ShowDescription = 'showSeparationSubTotalAmount-body';
        $style = 'style="max-width: 500px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showSeparationSubTotalAmount = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show Separation Sub Total Amount Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show Separation Subgroup Total Amount Modal ++++++++++++++++++++++
        $modalID = "showSeparationSubgroupTotalAmountModal";
        $modalTitle = "جزئیات مبالغ زیرگروه فرعی اظهارنظر ها";
        $ShowDescription = 'showSeparationSubgroupTotalAmount-body';
        $style = 'style="max-width: 500px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showSeparationSubgroupTotalAmount = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show Separation Subgroup Total Amount Modal ++++++++++++++++++++++++
        $htm .= $payCommentSearchModal;
        $htm .= $showDepositsInfo;
        $htm .= $showCommentChecks;
        $htm .= $commentAccountInfo;
        $htm .= $paymentReceiptDownloadR;
        $htm .= $commentAttachmentFile;
        $htm .= $commentWorkflow;
        $htm .= $showAttachedFundToReportComment;
        $htm .= $reportFundListDetails;
        $htm .= $reportFundListAddAttachmentFile;
        $htm .= $downloadCheckCarcassFile;
        $htm .= $showSeparationTotalAmount;
        $htm .= $showSeparationSubTotalAmount;
        $htm .= $showSeparationSubgroupTotalAmount;
        return $htm;
    }

/*    public function getReportPayCommentManageList($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rcSortType,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate,$dUnit,$mUnit,$layer1,$layer2,$layer3,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('reportPayCommentManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        $w[] = '`isEnable`=1 ';

        if(strlen(trim($unCode)) > 0){
            $w[] = '`unCode`="'.$unCode.'" ';
        }
        if(strlen(trim($rcsDate)) > 0){
            $rcsDate = $ut->jal_to_greg($rcsDate);
            $w[] = '`pay_comment`.`cDate` >="'.$rcsDate.'" ';
        }
        if(strlen(trim($rceDate)) > 0){
            $rceDate = $ut->jal_to_greg($rceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$rceDate.'" ';
        }
        if(strlen(trim($rpcsDate)) > 0){
            $rpcsDate = $ut->jal_to_greg($rpcsDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` >="'.$rpcsDate.'" ';
        }
        if(strlen(trim($rpceDate)) > 0){
            $rpceDate = $ut->jal_to_greg($rpceDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` <="'.$rpceDate.'" ';
        }
        if(strlen(trim($rpnsDate)) > 0){
            $rpnsDate = $ut->jal_to_greg($rpnsDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` >="'.$rpnsDate.'" ';
        }
        if(strlen(trim($rpneDate)) > 0){
            $rpneDate = $ut->jal_to_greg($rpneDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` <="'.$rpneDate.'" ';
        }
        if(intval($dUnit) > 0){
            $w[] = '`Unit`='.$dUnit.' ';
        }
        if(intval($mUnit) > 0){
            $w[] = '`consumerUnit`='.$mUnit.' ';
        }
        if(strlen(trim($rcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$rcaName.'%" ';
        }
        if(strlen(trim($rcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$rcToward.'%" ';
        }
        if(strlen(trim($rcAmount)) > 0){
            $w[] = '`Amount`='.$rcAmount.' ';
        }
        if(intval($rcPaytype) >= 0){  // فورج نقدی، فورج چک، سهامی، ثبت در حساب بستانکاری
            switch ($rcPaytype){
                case 0:
                    $w[] = '`sendType`=0';
                    break;
                case 1:
                    $w[] = '`sendType`=1';
                    break;
                case 2:
                    $w[] = '`sendType`=2';
                    break;
                case 3:
                    $w[] = '`sendType`=3';
                    break;
            }
        }

        $rcPaySend = explode(',',$rcPaySend);
        if (in_array(1,$rcPaySend) || in_array(2,$rcPaySend) || in_array(3,$rcPaySend)) {  // (بدون هیچ پرداختی)و(کامل پرداخت شده)و(ناقص پرداخت شده)
            $query = "SELECT `pid` FROM `deposit`";
            $rst = $db->ArrayQuery($query);
            $cnt = count($rst);
            $rids = array();
            for ($i = 0; $i < $cnt; $i++) {
                $rids[] = $rst[$i]['pid'];
            }

            $query1 = "SELECT `cid` FROM `bank_check`";
            $rst1 = $db->ArrayQuery($query1);
            $cnt1 = count($rst1);
            for ($i = 0; $i < $cnt1; $i++) {
                $rids[] = $rst1[$i]['cid'];
            }
            $rids = array_values(array_unique($rids));
            $rids = implode(',', $rids);
            $m = array();
            if (in_array(1,$rcPaySend)){  // اظهارنظرهای بدون هیچ پرداختی
                $m[] = '(`RowID` NOT IN ('.$rids.')) ';
            }
            if (in_array(2,$rcPaySend)){  // اظهارنظرهای کامل پرداخت شده
                $m[] = '(`transfer`=3) ';
            }
            if (in_array(3,$rcPaySend)){  // اظهارنظرهای ناقص پرداخت شده
                $m[] = '(`transfer`!=3  AND `RowID` IN ('.$rids.')) ';
            }
            $m = implode(" OR ",$m);
            $m = '('.$m.')';
            $w[] = $m;
        }

        $sqlUnit1 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=20";  // واحد تدارکات
        $sqlUnit2 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=21";  // واحد فناوری اطلاعات
        $sqlUnit3 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=22";  // واحد اداری
        $sqlUnit4 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=24";  // واحد بازرگانی فروش
        $rstUnit1 = $db->ArrayQuery($sqlUnit1);
        $rstUnit2 = $db->ArrayQuery($sqlUnit2);
        $rstUnit3 = $db->ArrayQuery($sqlUnit3);
        $rstUnit4 = $db->ArrayQuery($sqlUnit4);

        $arr1 = explode(',',$rstUnit1[0]['uids']);
        $arr2 = explode(',',$rstUnit2[0]['uids']);
        $arr3 = explode(',',$rstUnit3[0]['uids']);
        $arr4 = explode(',',$rstUnit4[0]['uids']);

        sort($arr1);
        sort($arr2);
        sort($arr3);
        sort($arr4);

        unset($arr1[0]);
        unset($arr2[0]);
        unset($arr3[0]);
        unset($arr4[0]);

        if (in_array($_SESSION['userid'],$arr1)){
            $w[] = '`uid` IN ('.$rstUnit1[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr2)){
            $w[] = '`uid` IN ('.$rstUnit2[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr3)){
            $w[] = '`uid` IN ('.$rstUnit3[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr4)){
            $w[] = '`uid` IN ('.$rstUnit4[0]['uids'].') ';
        }

        $sql1 = "SELECT `pay_comment`.`RowID` AS `pcid` FROM `pay_comment` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql1 .= " WHERE ".$where;
        }
        $res1 = $db->ArrayQuery($sql1);
        $listCount = count($res1);

        $rowIDS = array();
        for ($i=0;$i<$listCount;$i++){
            $rowIDS[] =  $res1[$i]['pcid'];
        }
        $rowIDS = implode(',',$rowIDS);

        $h = array();
        $z = array();
        $z[] = '`RowID` IN ('.$rowIDS.') ';
        if (intval($layer1) > 0){
            $z[] = '`layer1`='.$layer1.' ';
            $h[] = '`layer1`='.$layer1.' ';
        }
        if (intval($layer2) > 0){
            $z[] = '`layer2`='.$layer2.' ';
            $h[] = '`layer2`='.$layer2.' ';
        }
        if (intval($layer3) > 0){
            $z[] = '`layer3`='.$layer3.' ';
            $h[] = '`layer3`='.$layer3.' ';
        }

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`cDate`,`CashSection`,`NonCashSection`,`Amount`,`Toward`,`accName`,`sendType`,`priorityLevel` FROM `pay_comment` ";
        if(count($z) > 0){
            $where = implode(" AND ",$z);
            $sql .= " WHERE ".$where;
        }
        switch (intval($rcSortType)){
            case -1:
                $sql .= " ORDER BY `pcid` DESC LIMIT $start,".$numRows;
                break;
            case 0:
                $sql .= " ORDER BY `Amount` DESC LIMIT $start,".$numRows;
                break;
            case 1:
                $sql .= " ORDER BY `Amount` ASC LIMIT $start,".$numRows;
                break;
        }
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);




        $sqq = "SELECT `pid` FROM `fund_list`";
        if(count($h) > 0){
            $where = implode(" AND ",$h);
            $sqq .= " WHERE ".$where;
        }
        $rsq = $db->ArrayQuery($sqq);
        $ccnt = count($rsq);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            switch ($res[$y]['sendType']){
                case 0:
                    $finalRes[$y]['bgColor'] = 'table-warning';
                    break;
                case 1:
                    $finalRes[$y]['bgColor'] = 'table-orange';
                    break;
                case 2:
                    $finalRes[$y]['bgColor'] = 'table-primary';
                    break;
                case 3:
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
            }
            $finalRes[$y]['RowID'] = $res[$y]['pcid'];
            $finalRes[$y]['cDate'] = $ut->greg_to_jal($res[$y]['cDate']);
            $finalRes[$y]['CashSection'] = number_format($res[$y]['CashSection']).' ریال';
            $finalRes[$y]['NonCashSection'] = number_format($res[$y]['NonCashSection']).' ریال';
            $finalRes[$y]['Amount'] = number_format($res[$y]['Amount']).' ریال';
            $finalRes[$y]['Name'] = (strlen(trim($res[$y]['accName'])) == 0 ? '--------' : $res[$y]['accName']);
            $finalRes[$y]['Toward'] = $res[$y]['Toward'];
            $finalRes[$y]['blinker'] = ($res[$y]['priorityLevel'] == 1 ? 'blink' : '');
        }
        return $finalRes;
    }

    public function getReportPayCommentManageListCountRows($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate,$dUnit,$mUnit,$layer1,$layer2,$layer3){
        $ut = new Utility();
        $db = new DBi();
        $w = array();
        $w[] = '`isEnable`=1 ';
        if(strlen(trim($unCode)) > 0){
            $w[] = '`unCode`="'.$unCode.'" ';
        }
        if(strlen(trim($rcsDate)) > 0){
            $rcsDate = $ut->jal_to_greg($rcsDate);
            $w[] = '`pay_comment`.`cDate` >="'.$rcsDate.'" ';
        }
        if(strlen(trim($rceDate)) > 0){
            $rceDate = $ut->jal_to_greg($rceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$rceDate.'" ';
        }
        if(strlen(trim($rpcsDate)) > 0){
            $rpcsDate = $ut->jal_to_greg($rpcsDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` >="'.$rpcsDate.'" ';
        }
        if(strlen(trim($rpceDate)) > 0){
            $rpceDate = $ut->jal_to_greg($rpceDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` <="'.$rpceDate.'" ';
        }
        if(strlen(trim($rpnsDate)) > 0){
            $rpnsDate = $ut->jal_to_greg($rpnsDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` >="'.$rpnsDate.'" ';
        }
        if(strlen(trim($rpneDate)) > 0){
            $rpneDate = $ut->jal_to_greg($rpneDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` <="'.$rpneDate.'" ';
        }
        if(intval($dUnit) > 0){
            $w[] = '`Unit`='.$dUnit.' ';
        }
        if(intval($mUnit) > 0){
            $w[] = '`consumerUnit`='.$mUnit.' ';
        }
        if(strlen(trim($rcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$rcaName.'%" ';
        }
        if(strlen(trim($rcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$rcToward.'%" ';
        }
        if(strlen(trim($rcAmount)) > 0){
            $w[] = '`Amount`='.$rcAmount.' ';
        }
        if(intval($rcPaytype) >= 0){  // فورج نقدی، فورج چک، سهامی، ثبت در حساب بستانکاری
            switch ($rcPaytype){
                case 0:
                    $w[] = '`sendType`=0';
                    break;
                case 1:
                    $w[] = '`sendType`=1';
                    break;
                case 2:
                    $w[] = '`sendType`=2';
                    break;
                case 3:
                    $w[] = '`sendType`=3';
                    break;
            }
        }

        $rcPaySend = explode(',',$rcPaySend);
        if (in_array(1,$rcPaySend) || in_array(2,$rcPaySend) || in_array(3,$rcPaySend)) {  // (بدون هیچ پرداختی)و(کامل پرداخت شده)و(ناقص پرداخت شده)
            $query = "SELECT `pid` FROM `deposit`";
            $rst = $db->ArrayQuery($query);
            $cnt = count($rst);
            $rids = array();
            for ($i = 0; $i < $cnt; $i++) {
                $rids[] = $rst[$i]['pid'];
            }

            $query1 = "SELECT `cid` FROM `bank_check`";
            $rst1 = $db->ArrayQuery($query1);
            $cnt1 = count($rst1);
            for ($i = 0; $i < $cnt1; $i++) {
                $rids[] = $rst1[$i]['cid'];
            }
            $rids = array_values(array_unique($rids));
            $rids = implode(',', $rids);
            $m = array();
            if (in_array(1,$rcPaySend)){
                $m[] = '(`RowID` NOT IN ('.$rids.')) ';
            }
            if (in_array(2,$rcPaySend)){
                $m[] = '(`transfer`=3) ';
            }
            if (in_array(3,$rcPaySend)){
                $m[] = '(`transfer`!=3  AND `RowID` IN ('.$rids.')) ';
            }
            $m = implode(" OR ",$m);
            $m = '('.$m.')';
            $w[] = $m;
        }

        $sqlUnit1 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=20";  // واحد تدارکات
        $sqlUnit2 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=21";  // واحد فناوری اطلاعات
        $sqlUnit3 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=22";  // واحد اداری
        $sqlUnit4 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=24";  // واحد بازرگانی فروش
        $rstUnit1 = $db->ArrayQuery($sqlUnit1);
        $rstUnit2 = $db->ArrayQuery($sqlUnit2);
        $rstUnit3 = $db->ArrayQuery($sqlUnit3);
        $rstUnit4 = $db->ArrayQuery($sqlUnit4);

        $arr1 = explode(',',$rstUnit1[0]['uids']);
        $arr2 = explode(',',$rstUnit2[0]['uids']);
        $arr3 = explode(',',$rstUnit3[0]['uids']);
        $arr4 = explode(',',$rstUnit4[0]['uids']);

        sort($arr1);
        sort($arr2);
        sort($arr3);
        sort($arr4);

        unset($arr1[0]);
        unset($arr2[0]);
        unset($arr3[0]);
        unset($arr4[0]);

        if (in_array($_SESSION['userid'],$arr1)){
            $w[] = '`uid` IN ('.$rstUnit1[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr2)){
            $w[] = '`uid` IN ('.$rstUnit2[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr3)){
            $w[] = '`uid` IN ('.$rstUnit3[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr4)){
            $w[] = '`uid` IN ('.$rstUnit4[0]['uids'].') ';
        }

        $sql1 = "SELECT `pay_comment`.`RowID` AS `pcid` FROM `pay_comment` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql1 .= " WHERE ".$where;
        }
        $res1 = $db->ArrayQuery($sql1);
        $listCount = count($res1);

        $rowIDS = array();
        for ($i=0;$i<$listCount;$i++){
            $rowIDS[] =  $res1[$i]['pcid'];
        }

        $h = array();
        $z = array();
        if (intval($layer1) > 0){
            $z[] = '`layer1`='.$layer1.' ';
            $h[] = '`layer1`='.$layer1.' ';
        }
        if (intval($layer2) > 0){
            $z[] = '`layer2`='.$layer2.' ';
            $h[] = '`layer2`='.$layer2.' ';
        }
        if (intval($layer3) > 0){
            $z[] = '`layer3`='.$layer3.' ';
            $h[] = '`layer3`='.$layer3.' ';
        }

        $sqq = "SELECT `pid` FROM `fund_list`";
        if(count($h) > 0){
            $where = implode(" AND ",$h);
            $sqq .= " WHERE ".$where;
        }
        $rsq = $db->ArrayQuery($sqq);
        $ccnt = count($rsq);
        for ($j=0;$j<$ccnt;$j++){
            $rowIDS[] = $rsq[$j]['pid'];
        }
        $rowIDS = implode(',',$rowIDS);
        $z[] = '`RowID` IN ('.$rowIDS.') ';

        $sql = "SELECT `RowID` FROM `pay_comment` ";
        if(count($z) > 0){
            $where = implode(" AND ",$z);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }*/

    public function getReportPayCommentManageList($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rcSortType,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate,$dUnit,$mUnit,$layer1,$layer2,$layer3,$page=1,$c_type){
        $acm = new acm();
        if(!$acm->hasAccess('reportPayCommentManage')){
            die("access denied");
            exit;
        }
		
        $ut = new Utility();
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
		$full_access_array=$ut->get_full_access_users(7);
        $w = array();
        $w[] = '`isEnable`=1 ';

        if(strlen(trim($unCode)) > 0){
            $w[] = '`unCode`="'.$unCode.'" ';
        }
        if(strlen(trim($rcsDate)) > 0){
            $rcsDate = $ut->jal_to_greg($rcsDate);
            $w[] = '`pay_comment`.`cDate` >="'.$rcsDate.'" ';
        }
        if(strlen(trim($rceDate)) > 0){
            $rceDate = $ut->jal_to_greg($rceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$rceDate.'" ';
        }
        if(strlen(trim($rpcsDate)) > 0){
            $rpcsDate = $ut->jal_to_greg($rpcsDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` >="'.$rpcsDate.'" ';
        }
        if(strlen(trim($rpceDate)) > 0){
            $rpceDate = $ut->jal_to_greg($rpceDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` <="'.$rpceDate.'" ';
        }
        if(strlen(trim($rpnsDate)) > 0){
            $rpnsDate = $ut->jal_to_greg($rpnsDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` >="'.$rpnsDate.'" ';
        }
        if(strlen(trim($rpneDate)) > 0){
            $rpneDate = $ut->jal_to_greg($rpneDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` <="'.$rpneDate.'" ';
        }
        if(intval($dUnit) > 0){
            $w[] = '`Unit`='.$dUnit.' ';
        }
        if(intval($mUnit) > 0){
            $w[] = '`consumerUnit`='.$mUnit.' ';
        }
        if(strlen(trim($rcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$rcaName.'%" ';
        }
        if(strlen(trim($rcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$rcToward.'%" ';
        }
        if(strlen(trim($rcAmount)) > 0){
            $w[] = '`Amount`='.$rcAmount.' ';
        }
        if(strlen(trim($c_type)) > 0){
            $w[] = "`type`='".$c_type."'" ;
        }
        if(intval($rcPaytype) >= 0){  // فورج نقدی، فورج چک، سهامی، ثبت در حساب بستانکاری
            switch ($rcPaytype){
                case 0:
                    $w[] = '`sendType`=0';
                    break;
                case 1:
                    $w[] = '`sendType`=1';
                    break;
                case 2:
                    $w[] = '`sendType`=2';
                    break;
                case 3:
                    $w[] = '`sendType`=3';
                    break;
            }
        }
		
        $rcPaySend = explode(',',$rcPaySend);
        if (in_array(1,$rcPaySend) || in_array(2,$rcPaySend) || in_array(3,$rcPaySend)) {  // (بدون هیچ پرداختی)و(کامل پرداخت شده)و(ناقص پرداخت شده)
            $m = array();
            if (in_array(1,$rcPaySend)){  // اظهارنظرهای بدون هیچ پرداختی
                $m[] = '(`paymentStatus`=0)';
            }
            if (in_array(2,$rcPaySend)){  // اظهارنظرهای کامل پرداخت شده
                $m[] = '(`paymentStatus`=1 OR `paymentStatus`=3)';
            }
            if (in_array(3,$rcPaySend)){  // اظهارنظرهای ناقص پرداخت شده
                $m[] = '(`paymentStatus`=2)';
            }
            $m = implode(" OR ",$m);
            $m = '('.$m.')';
            $w[] = $m;
        }
		
        $sqlUnit1 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=20";  // واحد تدارکات
        $sqlUnit2 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=21";  // واحد فناوری اطلاعات
        $sqlUnit3 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=22";  // واحد اداری
        $sqlUnit4 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=24";  // واحد بازرگانی فروش
     
		$rstUnit1 = $db->ArrayQuery($sqlUnit1);
        $rstUnit2 = $db->ArrayQuery($sqlUnit2);
        $rstUnit3 = $db->ArrayQuery($sqlUnit3);
        $rstUnit4 = $db->ArrayQuery($sqlUnit4);

        $arr1 = explode(',',$rstUnit1[0]['uids']);
        $arr2 = explode(',',$rstUnit2[0]['uids']);
        $arr3 = explode(',',$rstUnit3[0]['uids']);
        $arr4 = explode(',',$rstUnit4[0]['uids']);

        sort($arr1);
        sort($arr2);
        sort($arr3);
        sort($arr4);
		if(!in_array($_SESSION['userid'],$full_access_array)){
			if (in_array($_SESSION['userid'],$arr1)){
				$w[] = '`uid` IN ('.$rstUnit1[0]['uids'].') ';
			}
			if (in_array($_SESSION['userid'],$arr2)){
				$w[] = '`uid` IN ('.$rstUnit2[0]['uids'].') ';
			}
			if (in_array($_SESSION['userid'],$arr3)){
				$w[] = '`uid` IN ('.$rstUnit3[0]['uids'].') ';
			}
			if (in_array($_SESSION['userid'],$arr4)){
				$w[] = '`uid` IN ('.$rstUnit4[0]['uids'].') ';
			}
		}
        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`type`,`unCode`,`cDate`,`CashSection`,`NonCashSection`,`Amount`,`Toward`,`accName`,`sendType`,`priorityLevel` FROM `pay_comment` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
       
        switch (intval($rcSortType)){
            case -1:
                $sql .= " ORDER BY `pcid` DESC LIMIT $start,".$numRows;
                break;
            case 0:
                $sql .= " ORDER BY `Amount` DESC LIMIT $start,".$numRows;
                break;
            case 1:
                $sql .= " ORDER BY `Amount` ASC LIMIT $start,".$numRows;
                break;
        }
		//$ut->fileRecorder("SSSSSQQQQLLL:".$sql);
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            switch ($res[$y]['sendType']){
                case 0:
                    $finalRes[$y]['bgColor'] = 'table-warning';
                    break;
                case 1:
                    $finalRes[$y]['bgColor'] = 'table-orange';
                    break;
                case 2:
                    $finalRes[$y]['bgColor'] = 'table-primary';
                    break;
                case 3:
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
            }
            $finalRes[$y]['RowID'] = $res[$y]['pcid'];
            $finalRes[$y]['type'] = $res[$y]['type'];
            $finalRes[$y]['unCode'] = $res[$y]['unCode'];
            $finalRes[$y]['cDate'] = $ut->greg_to_jal($res[$y]['cDate']);
            $finalRes[$y]['CashSection'] = number_format($res[$y]['CashSection']).' ریال';
            $finalRes[$y]['NonCashSection'] = number_format($res[$y]['NonCashSection']).' ریال';
            $finalRes[$y]['Amount'] = number_format($res[$y]['Amount']).' ریال';
            $finalRes[$y]['Name'] = (strlen(trim($res[$y]['accName'])) == 0 ? '--------' : $res[$y]['accName']);
            $finalRes[$y]['Toward'] = $res[$y]['Toward'];
            $finalRes[$y]['blinker'] = ($res[$y]['priorityLevel'] == 1 ? 'blink' : '');
        }
        return $finalRes;
    }

    public function refresh_select_options($meta_data,$sub_layer=0){
        $db=new DBi();
        $ut=new Utility();
        if($sub_layer==0){
            $where_condition_array=['`parentID`=-1'];
        }
        else{
            $where_condition_array=['`parentID`!=-1'];
        }
       
        $where_condition="";
        foreach($meta_data as $meta_index => $meta_value){
            $where_condition_array[]=" lm.`key`='{$meta_value}' ";
        }
        if(count($where_condition_array)>0){
            $where_condition=implode(" AND ",$where_condition_array);
            $where_condition='WHERE  '.$where_condition;
        }
        
        $sql="SELECT l.`RowID`,l.`layerName` FROM  layers as l 
            LEFT JOIN `layers_meta` as lm 
            on l.`RowID`=lm.`layer_id` {$where_condition} GROUP BY l.`RowID`";
        $res=$db->ArrayQuery($sql);
        return $res;
       
    }
    public function getReportPayCommentManageListCountRows($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate,$dUnit,$mUnit,$c_type){
        $ut = new Utility();
        $db = new DBi();
        $full_access_array=$ut->get_full_access_users(7);
        $w = array();
        $w[] = '`isEnable`=1 ';
        if(strlen(trim($unCode)) > 0){
            $w[] = '`unCode`="'.$unCode.'" ';
        }
        if(strlen(trim($rcsDate)) > 0){
            $rcsDate = $ut->jal_to_greg($rcsDate);
            $w[] = '`pay_comment`.`cDate` >="'.$rcsDate.'" ';
        }
        if(strlen(trim($rceDate)) > 0){
            $rceDate = $ut->jal_to_greg($rceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$rceDate.'" ';
        }
        if(strlen(trim($rpcsDate)) > 0){
            $rpcsDate = $ut->jal_to_greg($rpcsDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` >="'.$rpcsDate.'" ';
        }
        if(strlen(trim($rpceDate)) > 0){
            $rpceDate = $ut->jal_to_greg($rpceDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` <="'.$rpceDate.'" ';
        }
        if(strlen(trim($rpnsDate)) > 0){
            $rpnsDate = $ut->jal_to_greg($rpnsDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` >="'.$rpnsDate.'" ';
        }
        if(strlen(trim($rpneDate)) > 0){
            $rpneDate = $ut->jal_to_greg($rpneDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` <="'.$rpneDate.'" ';
        }
        if(intval($dUnit) > 0){
            $w[] = '`Unit`='.$dUnit.' ';
        }
        if(intval($c_type) > 0){
            $w[] = "`type`='".$c_type."'";
        }
        if(intval($mUnit) > 0){
            $w[] = '`consumerUnit`='.$mUnit.' ';
        }
        if(strlen(trim($rcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$rcaName.'%" ';
        }
        if(strlen(trim($rcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$rcToward.'%" ';
        }
        if(strlen(trim($rcAmount)) > 0){
            $w[] = '`Amount`='.$rcAmount.' ';
        }
        if(intval($rcPaytype) >= 0){  // فورج نقدی، فورج چک، سهامی، ثبت در حساب بستانکاری
            switch ($rcPaytype){
                case 0:
                    $w[] = '`sendType`=0';
                    break;
                case 1:
                    $w[] = '`sendType`=1';
                    break;
                case 2:
                    $w[] = '`sendType`=2';
                    break;
                case 3:
                    $w[] = '`sendType`=3';
                    break;
            }
        }

        $rcPaySend = explode(',',$rcPaySend);
        if (in_array(1,$rcPaySend) || in_array(2,$rcPaySend) || in_array(3,$rcPaySend)) {  // (بدون هیچ پرداختی)و(کامل پرداخت شده)و(ناقص پرداخت شده)
            $m = array();
            if (in_array(1,$rcPaySend)){  // اظهارنظرهای بدون هیچ پرداختی
                $m[] = '(`paymentStatus`=0)';
            }
            if (in_array(2,$rcPaySend)){  // اظهارنظرهای کامل پرداخت شده
                $m[] = '(`paymentStatus`=1 OR `paymentStatus`=3)';
            }
            if (in_array(3,$rcPaySend)){  // اظهارنظرهای ناقص پرداخت شده
                $m[] = '(`paymentStatus`=2)';
            }
            $m = implode(" OR ",$m);
            $m = '('.$m.')';
            $w[] = $m;
        }

        $sqlUnit1 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=20";  // واحد تدارکات
        $sqlUnit2 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=21";  // واحد فناوری اطلاعات
        $sqlUnit3 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=22";  // واحد اداری
        $sqlUnit4 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=24";  // واحد بازرگانی فروش
        $rstUnit1 = $db->ArrayQuery($sqlUnit1);
        $rstUnit2 = $db->ArrayQuery($sqlUnit2);
        $rstUnit3 = $db->ArrayQuery($sqlUnit3);
        $rstUnit4 = $db->ArrayQuery($sqlUnit4);

        $arr1 = explode(',',$rstUnit1[0]['uids']);
        $arr2 = explode(',',$rstUnit2[0]['uids']);
        $arr3 = explode(',',$rstUnit3[0]['uids']);
        $arr4 = explode(',',$rstUnit4[0]['uids']);

        sort($arr1);
        sort($arr2);
        sort($arr3);
        sort($arr4);

        unset($arr1[0]);
        unset($arr2[0]);
        unset($arr3[0]);
        unset($arr4[0]);
        if(!in_array($_SESSION['userid'],$full_access_array)){
            if (in_array($_SESSION['userid'],$arr1)){
                $w[] = '`uid` IN ('.$rstUnit1[0]['uids'].') ';
            }
            if (in_array($_SESSION['userid'],$arr2)){
                $w[] = '`uid` IN ('.$rstUnit2[0]['uids'].') ';
            }
            if (in_array($_SESSION['userid'],$arr3)){
                $w[] = '`uid` IN ('.$rstUnit3[0]['uids'].') ';
            }
            if (in_array($_SESSION['userid'],$arr4)){
                $w[] = '`uid` IN ('.$rstUnit4[0]['uids'].') ';
            }

        }
        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`cDate`,`CashSection`,`NonCashSection`,`Toward`,`accName`,`sendType` FROM `pay_comment` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function getTotalAmountPayComment($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate,$dUnit,$mUnit,$c_type){
        $acm = new acm();
        if(!$acm->hasAccess('reportPayCommentManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $w = array();
        $w[] = '`isEnable`=1 ';
        if(strlen(trim($unCode)) > 0){
            $w[] = '`unCode`="'.$unCode.'" ';
        }
        if(strlen(trim($rcsDate)) > 0){
            $rcsDate = $ut->jal_to_greg($rcsDate);
            $w[] = '`pay_comment`.`cDate` >="'.$rcsDate.'" ';
        }
        if(strlen(trim($rceDate)) > 0){
            $rceDate = $ut->jal_to_greg($rceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$rceDate.'" ';
        }
        if(strlen(trim($rpcsDate)) > 0){
            $rpcsDate = $ut->jal_to_greg($rpcsDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` >="'.$rpcsDate.'" ';
        }
        if(strlen(trim($rpceDate)) > 0){
            $rpceDate = $ut->jal_to_greg($rpceDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` <="'.$rpceDate.'" ';
        }
        if(strlen(trim($rpnsDate)) > 0){
            $rpnsDate = $ut->jal_to_greg($rpnsDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` >="'.$rpnsDate.'" ';
        }
        if(strlen(trim($rpneDate)) > 0){
            $rpneDate = $ut->jal_to_greg($rpneDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` <="'.$rpneDate.'" ';
        }
        if(intval($dUnit) > 0){
            $w[] = '`Unit`='.$dUnit.' ';
        }
        if(intval($mUnit) > 0){
            $w[] = '`consumerUnit`='.$mUnit.' ';
        }
        if(strlen(trim($rcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$rcaName.'%" ';
        }
        if(strlen(trim($rcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$rcToward.'%" ';
        }
        if(strlen(trim($rcAmount)) > 0){
            $w[] = '`Amount`='.$rcAmount.' ';
        }
        if(strlen(trim($c_type)) > 0){
            $w[] = "`type`='".$c_type."'";
        }
        if(intval($rcPaytype) >= 0){  // فورج نقدی، فورج چک، سهامی، ثبت در حساب بستانکاری
            switch ($rcPaytype){
                case 0:
                    $w[] = '`sendType`=0';
                    break;
                case 1:
                    $w[] = '`sendType`=1';
                    break;
                case 2:
                    $w[] = '`sendType`=2';
                    break;
                case 3:
                    $w[] = '`sendType`=3';
                    break;
            }
        }

        $rcPaySend = explode(',',$rcPaySend);
        if (in_array(1,$rcPaySend) || in_array(2,$rcPaySend) || in_array(3,$rcPaySend)) {  // (بدون هیچ پرداختی)و(کامل پرداخت شده)و(ناقص پرداخت شده)
            $m = array();
            if (in_array(1,$rcPaySend)){  // اظهارنظرهای بدون هیچ پرداختی
                $m[] = '(`paymentStatus`=0)';
            }
            if (in_array(2,$rcPaySend)){  // اظهارنظرهای کامل پرداخت شده
                $m[] = '(`paymentStatus`=1 OR `paymentStatus`=3)';
            }
            if (in_array(3,$rcPaySend)){  // اظهارنظرهای ناقص پرداخت شده
                $m[] = '(`paymentStatus`=2)';
            }
            $m = implode(" OR ",$m);
            $m = '('.$m.')';
            $w[] = $m;
        }

        $sqlUnit1 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=20";  // واحد تدارکات
        $sqlUnit2 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=21";  // واحد فناوری اطلاعات
        $sqlUnit3 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=22";  // واحد اداری
        $sqlUnit4 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=24";  // واحد بازرگانی فروش
        $rstUnit1 = $db->ArrayQuery($sqlUnit1);
        $rstUnit2 = $db->ArrayQuery($sqlUnit2);
        $rstUnit3 = $db->ArrayQuery($sqlUnit3);
        $rstUnit4 = $db->ArrayQuery($sqlUnit4);

        $arr1 = explode(',',$rstUnit1[0]['uids']);
        $arr2 = explode(',',$rstUnit2[0]['uids']);
        $arr3 = explode(',',$rstUnit3[0]['uids']);
        $arr4 = explode(',',$rstUnit4[0]['uids']);

        sort($arr1);
        sort($arr2);
        sort($arr3);
        sort($arr4);

        unset($arr1[0]);
        unset($arr2[0]);
        unset($arr3[0]);
        unset($arr4[0]);

        if (in_array($_SESSION['userid'],$arr1)){
            $w[] = '`uid` IN ('.$rstUnit1[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr2)){
            $w[] = '`uid` IN ('.$rstUnit2[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr3)){
            $w[] = '`uid` IN ('.$rstUnit3[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr4)){
            $w[] = '`uid` IN ('.$rstUnit4[0]['uids'].') ';
        }

        $sql = "SELECT `RowID`,`Amount` FROM `pay_comment` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $TotalAmount = 0;
        $rowIDs = array();
        for($y=0;$y<$listCount;$y++){
            $rowIDs[] = $res[$y]['RowID'];
            $TotalAmount += $res[$y]['Amount'];
        }
        $rowIDs = implode(',',$rowIDs);

        $Naghdi = 0;
        $Cheki = 0;
        $sabtDarHesab = 0;
        $sqq = "SELECT `dAmount` FROM `deposit` WHERE `pid` IN ({$rowIDs})";
        $sqq1 = "SELECT `check_amount` FROM `bank_check` WHERE `cid` IN ({$rowIDs})";
        $sqq2 = "SELECT `Amount` FROM `pay_comment` WHERE `RowID` IN ({$rowIDs}) AND `sendType`=3";
        $rsq = $db->ArrayQuery($sqq);
        $rsq1 = $db->ArrayQuery($sqq1);
        $rsq2 = $db->ArrayQuery($sqq2);
        $cntq = count($rsq);
        $cntq1 = count($rsq1);
        $cntq2 = count($rsq2);
        for ($i=0;$i<$cntq;$i++){
            $Naghdi += $rsq[$i]['dAmount'];
        }
        for ($i=0;$i<$cntq1;$i++){
            $Cheki += $rsq1[$i]['check_amount'];
        }
        for ($i=0;$i<$cntq2;$i++){
            $sabtDarHesab += $rsq2[$i]['Amount'];
        }

        $Mandeh = $TotalAmount - ($Naghdi + $Cheki + $sabtDarHesab);

        $send = array($TotalAmount,$Naghdi,$Cheki,$sabtDarHesab,$Mandeh);
        return $send;
    }

    public function getPayCommentReportExcel($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate,$dUnit,$mUnit,$c_type){
        $ut = new Utility();
        $db = new DBi();
        $full_access_array=$ut->get_full_access_users(7);
        $w = array();
        $w[] = '`isEnable`=1 ';
        if(strlen(trim($c_type)) > 0){
            $w[] = '`type`="'.$c_type.'" ';
        }
        if(strlen(trim($unCode)) > 0){
            $w[] = '`unCode`="'.$unCode.'" ';
        }
        if(strlen(trim($rcsDate)) > 0){
            $rcsDate = $ut->jal_to_greg($rcsDate);
            $w[] = '`pay_comment`.`cDate` >="'.$rcsDate.'" ';
        }
        if(strlen(trim($rceDate)) > 0){
            $rceDate = $ut->jal_to_greg($rceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$rceDate.'" ';
        }
        if(strlen(trim($rpcsDate)) > 0){
            $rpcsDate = $ut->jal_to_greg($rpcsDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` >="'.$rpcsDate.'" ';
        }
        if(strlen(trim($rpceDate)) > 0){
            $rpceDate = $ut->jal_to_greg($rpceDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` <="'.$rpceDate.'" ';
        }
        if(strlen(trim($rpnsDate)) > 0){
            $rpnsDate = $ut->jal_to_greg($rpnsDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` >="'.$rpnsDate.'" ';
        }
        if(strlen(trim($rpneDate)) > 0){
            $rpneDate = $ut->jal_to_greg($rpneDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` <="'.$rpneDate.'" ';
        }
        if(intval($dUnit) > 0){
            $w[] = '`Unit`='.$dUnit.' ';
        }
        if(intval($mUnit) > 0){
            $w[] = '`consumerUnit`='.$mUnit.' ';
        }
        if(strlen(trim($rcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$rcaName.'%" ';
        }
        if(strlen(trim($rcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$rcToward.'%" ';
        }
        if(strlen(trim($rcAmount)) > 0){
            $w[] = '`Amount`='.$rcAmount.' ';
        }
        if(intval($rcPaytype) >= 0){  // فورج نقدی، فورج چک، سهامی، ثبت در حساب بستانکاری
            switch ($rcPaytype){
                case 0:
                    $w[] = '`sendType`=0';
                    break;
                case 1:
                    $w[] = '`sendType`=1';
                    break;
                case 2:
                    $w[] = '`sendType`=2';
                    break;
                case 3:
                    $w[] = '`sendType`=3';
                    break;
            }
        }

        $rcPaySend = explode(',',$rcPaySend);
        if (in_array(1,$rcPaySend) || in_array(2,$rcPaySend) || in_array(3,$rcPaySend)) {  // (بدون هیچ پرداختی)و(کامل پرداخت شده)و(ناقص پرداخت شده)
            $query = "SELECT `pid` FROM `deposit`";
            $rst = $db->ArrayQuery($query);
            $cnt = count($rst);
            $rids = array();
            for ($i = 0; $i < $cnt; $i++) {
                $rids[] = $rst[$i]['pid'];
            }

            $query1 = "SELECT `cid` FROM `bank_check`";
            $rst1 = $db->ArrayQuery($query1);
            $cnt1 = count($rst1);
            for ($i = 0; $i < $cnt1; $i++) {
                $rids[] = $rst1[$i]['cid'];
            }
            $rids = array_values(array_unique($rids));
            $rids = implode(',', $rids);
            $m = array();
            if (in_array(1,$rcPaySend)){
                $m[] = '(`RowID` NOT IN ('.$rids.')) ';
            }
            if (in_array(2,$rcPaySend)){
                $m[] = '(`transfer`=3) ';
            }
            if (in_array(3,$rcPaySend)){
                $m[] = '(`transfer`!=3  AND `RowID` IN ('.$rids.')) ';
            }
            $m = implode(" OR ",$m);
            $m = '('.$m.')';
            $w[] = $m;
        }

        $sqlUnit1 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=20";  // واحد تدارکات
        $sqlUnit2 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=21";  // واحد فناوری اطلاعات
        $sqlUnit3 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=22";  // واحد اداری
        $sqlUnit4 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=24";  // واحد بازرگانی فروش
        $rstUnit1 = $db->ArrayQuery($sqlUnit1);
        $rstUnit2 = $db->ArrayQuery($sqlUnit2);
        $rstUnit3 = $db->ArrayQuery($sqlUnit3);
        $rstUnit4 = $db->ArrayQuery($sqlUnit4);

        $arr1 = explode(',',$rstUnit1[0]['uids']);
        $arr2 = explode(',',$rstUnit2[0]['uids']);
        $arr3 = explode(',',$rstUnit3[0]['uids']);
        $arr4 = explode(',',$rstUnit4[0]['uids']);

        sort($arr1);
        sort($arr2);
        sort($arr3);
        sort($arr4);

        unset($arr1[0]);
        unset($arr2[0]);
        unset($arr3[0]);
        unset($arr4[0]);
        if(!in_array($_SESSION['userid'],$full_access_array)){
            if (in_array($_SESSION['userid'],$arr1)){
                $w[] = '`uid` IN ('.$rstUnit1[0]['uids'].') ';
            }
            if (in_array($_SESSION['userid'],$arr2)){
                $w[] = '`uid` IN ('.$rstUnit2[0]['uids'].') ';
            }
            if (in_array($_SESSION['userid'],$arr3)){
                $w[] = '`uid` IN ('.$rstUnit3[0]['uids'].') ';
            }
            if (in_array($_SESSION['userid'],$arr4)){
                $w[] = '`uid` IN ('.$rstUnit4[0]['uids'].') ';
            }
         }
        $sql = "SELECT `type`,`unCode`,`cDate`,`accName`,`codeTafzili`,`Toward`,`Amount`,`CashSection`,`NonCashSection`,`desc`,`sendType` FROM `pay_comment` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }

        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            $res[$i]['cDate'] = $ut->greg_to_jal($res[$i]['cDate']);
            switch ($res[$i]['sendType']){
                case 0;
                    $res[$i]['sendType'] = 'فورج نقدی';
                    break;
                case 1;
                    $res[$i]['sendType'] = 'فورج چک';
                    break;
                case 2;
                    $res[$i]['sendType'] = 'سهامی';
                    break;
                case 3;
                    $res[$i]['sendType'] = 'ثبت در حساب بستانکاری طرف مقابل';
                    break;
            }
        }
       // $ut->fileRecorder('sqqlll::;'.$sql);
        return $res;
    }

    public function getSeparationTotalAmount($unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate){
        $acm = new acm();
        if(!$acm->hasAccess('reportPayCommentManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $w = array();
        $w[] = '`isEnable`=1 ';

        if(strlen(trim($unCode)) > 0){
            $w[] = '`unCode`="'.$unCode.'" ';
        }
        if(strlen(trim($rcsDate)) > 0){
            $rcsDate = $ut->jal_to_greg($rcsDate);
            $w[] = '`pay_comment`.`cDate` >="'.$rcsDate.'" ';
        }
        if(strlen(trim($rceDate)) > 0){
            $rceDate = $ut->jal_to_greg($rceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$rceDate.'" ';
        }
        if(strlen(trim($rpcsDate)) > 0){
            $rpcsDate = $ut->jal_to_greg($rpcsDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` >="'.$rpcsDate.'" ';
        }
        if(strlen(trim($rpceDate)) > 0){
            $rpceDate = $ut->jal_to_greg($rpceDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` <="'.$rpceDate.'" ';
        }
        if(strlen(trim($rpnsDate)) > 0){
            $rpnsDate = $ut->jal_to_greg($rpnsDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` >="'.$rpnsDate.'" ';
        }
        if(strlen(trim($rpneDate)) > 0){
            $rpneDate = $ut->jal_to_greg($rpneDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` <="'.$rpneDate.'" ';
        }
        if(strlen(trim($rcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$rcaName.'%" ';
        }
        if(strlen(trim($rcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$rcToward.'%" ';
        }
        if(strlen(trim($rcAmount)) > 0){
            $w[] = '`Amount`='.$rcAmount.' ';
        }
        if(intval($rcPaytype) >= 0){  // فورج نقدی، فورج چک، سهامی، ثبت در حساب بستانکاری
            switch ($rcPaytype){
                case 0:
                    $w[] = '`sendType`=0';
                    break;
                case 1:
                    $w[] = '`sendType`=1';
                    break;
                case 2:
                    $w[] = '`sendType`=2';
                    break;
                case 3:
                    $w[] = '`sendType`=3';
                    break;
            }
        }

        $rcPaySend = explode(',',$rcPaySend);
        if (in_array(1,$rcPaySend) || in_array(2,$rcPaySend) || in_array(3,$rcPaySend)) {  // (بدون هیچ پرداختی)و(کامل پرداخت شده)و(ناقص پرداخت شده)
            $query = "SELECT `pid` FROM `deposit`";
            $rst = $db->ArrayQuery($query);
            $cnt = count($rst);
            $rids = array();
            for ($i = 0; $i < $cnt; $i++) {
                $rids[] = $rst[$i]['pid'];
            }

            $query1 = "SELECT `cid` FROM `bank_check`";
            $rst1 = $db->ArrayQuery($query1);
            $cnt1 = count($rst1);
            for ($i = 0; $i < $cnt1; $i++) {
                $rids[] = $rst1[$i]['cid'];
            }
            $rids = array_values(array_unique($rids));
            $rids = implode(',', $rids);
            $m = array();
            if (in_array(1,$rcPaySend)){
                $m[] = '(`RowID` NOT IN ('.$rids.')) ';
            }
            if (in_array(2,$rcPaySend)){
                $m[] = '(`transfer`=3) ';
            }
            if (in_array(3,$rcPaySend)){
                $m[] = '(`transfer`!=3  AND `RowID` IN ('.$rids.')) ';
            }
            $m = implode(" OR ",$m);
            $m = '('.$m.')';
            $w[] = $m;
        }

        $sqlUnit1 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=20";  // واحد تدارکات
        $sqlUnit2 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=21";  // واحد فناوری اطلاعات
        $sqlUnit3 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=22";  // واحد اداری
        $sqlUnit4 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=24";  // واحد بازرگانی فروش
        $rstUnit1 = $db->ArrayQuery($sqlUnit1);
        $rstUnit2 = $db->ArrayQuery($sqlUnit2);
        $rstUnit3 = $db->ArrayQuery($sqlUnit3);
        $rstUnit4 = $db->ArrayQuery($sqlUnit4);

        $arr1 = explode(',',$rstUnit1[0]['uids']);
        $arr2 = explode(',',$rstUnit2[0]['uids']);
        $arr3 = explode(',',$rstUnit3[0]['uids']);
        $arr4 = explode(',',$rstUnit4[0]['uids']);

        sort($arr1);
        sort($arr2);
        sort($arr3);
        sort($arr4);

        unset($arr1[0]);
        unset($arr2[0]);
        unset($arr3[0]);
        unset($arr4[0]);

        if (in_array($_SESSION['userid'],$arr1)){
            $w[] = '`uid` IN ('.$rstUnit1[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr2)){
            $w[] = '`uid` IN ('.$rstUnit2[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr3)){
            $w[] = '`uid` IN ('.$rstUnit3[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr4)){
            $w[] = '`uid` IN ('.$rstUnit4[0]['uids'].') ';
        }

        $sql = "SELECT `RowID` FROM `pay_comment`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $rowIds = array();
        for ($i=0;$i<$cnt;$i++){
            $rowIds[] = $res[$i]['RowID'];
        }
        $rowIds = array_values(array_unique($rowIds));
        $rowIds = implode(',', $rowIds);

        $sql1 = "SELECT `RowID`,`layerName` FROM `layers` WHERE `parentID`=-1";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);

        $totalAmount = 0;
        $layers = array();
        for ($i=0;$i<$cnt1;$i++){
            if (intval($res1[$i]['RowID']) == 4){
                continue;
            }
            $sql2 = "SELECT `Amount` FROM `pay_comment` WHERE `layer1`={$res1[$i]['RowID']} AND `RowID` IN ({$rowIds})";
            $res2 = $db->ArrayQuery($sql2);
            $cnt3 = count($res2);
            $layers[$res1[$i]['RowID']]['layerName'] = $res1[$i]['layerName'];
            $layers[$res1[$i]['RowID']]['RowID'] = $res1[$i]['RowID'];
            for ($j=0;$j<$cnt3;$j++){
                $totalAmount += $res2[$j]['Amount'];
            }
            $layers[$res1[$i]['RowID']]['totalAmount'] = $totalAmount;
            $totalAmount = 0;
        }

        $sql3 = "SELECT `RowID` FROM `pay_comment` WHERE `layer1`=4 AND `RowID` IN ({$rowIds})";
        $res3 = $db->ArrayQuery($sql3);
        $cnt4 = count($res3);

        $justFund = array();
        for ($i=0;$i<$cnt4;$i++){
            $sql4 = "SELECT `finalAmount`,`layer1` FROM `fund_list` WHERE `pid`={$res3[$i]['RowID']}";
            $res4 = $db->ArrayQuery($sql4);
            if (count($res4) <= 0){
                $justFund[] = $res3[$i]['RowID'];
            }else{
                $cnt5 = count($res4);
                for ($j=0;$j<$cnt5;$j++){
                    $layers[$res4[$j]['layer1']]['totalAmount'] = intval($layers[$res4[$j]['layer1']]['totalAmount']) + intval($res4[$j]['finalAmount']);
                }
            }
        }

        $justFund = implode(',',$justFund);
        $sql5 = "SELECT SUM(`Amount`) AS `amt` FROM `pay_comment` WHERE `RowID` IN ({$justFund})";
        $res5 = $db->ArrayQuery($sql5);
        $layers[4]['RowID'] = 4;
        $layers[4]['layerName'] = 'تنخواه';
        $layers[4]['totalAmount'] = $res5[0]['amt'];

        $layers = array_values($layers);
        $cnt = count($layers);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="getSeparationTotalAmount-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">سرگروه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">مبلغ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">زیر گروه</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$layers[$i]['layerName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($layers[$i]['totalAmount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showSeparationSubTotalAmount('.$layers[$i]['RowID'].')" ><i class="fas fa-sitemap"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getSeparationSubTotalAmount($fid,$unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate){
        $acm = new acm();
        if(!$acm->hasAccess('reportPayCommentManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $w = array();
        $w[] = '`isEnable`=1 ';

        if(strlen(trim($unCode)) > 0){
            $w[] = '`unCode`="'.$unCode.'" ';
        }
        if(strlen(trim($rcsDate)) > 0){
            $rcsDate = $ut->jal_to_greg($rcsDate);
            $w[] = '`pay_comment`.`cDate` >="'.$rcsDate.'" ';
        }
        if(strlen(trim($rceDate)) > 0){
            $rceDate = $ut->jal_to_greg($rceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$rceDate.'" ';
        }
        if(strlen(trim($rpcsDate)) > 0){
            $rpcsDate = $ut->jal_to_greg($rpcsDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` >="'.$rpcsDate.'" ';
        }
        if(strlen(trim($rpceDate)) > 0){
            $rpceDate = $ut->jal_to_greg($rpceDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` <="'.$rpceDate.'" ';
        }
        if(strlen(trim($rpnsDate)) > 0){
            $rpnsDate = $ut->jal_to_greg($rpnsDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` >="'.$rpnsDate.'" ';
        }
        if(strlen(trim($rpneDate)) > 0){
            $rpneDate = $ut->jal_to_greg($rpneDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` <="'.$rpneDate.'" ';
        }
        if(strlen(trim($rcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$rcaName.'%" ';
        }
        if(strlen(trim($rcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$rcToward.'%" ';
        }
        if(strlen(trim($rcAmount)) > 0){
            $w[] = '`Amount`='.$rcAmount.' ';
        }
        if(intval($rcPaytype) >= 0){  // فورج نقدی، فورج چک، سهامی، ثبت در حساب بستانکاری
            switch ($rcPaytype){
                case 0:
                    $w[] = '`sendType`=0';
                    break;
                case 1:
                    $w[] = '`sendType`=1';
                    break;
                case 2:
                    $w[] = '`sendType`=2';
                    break;
                case 3:
                    $w[] = '`sendType`=3';
                    break;
            }
        }

        $rcPaySend = explode(',',$rcPaySend);
        if (in_array(1,$rcPaySend) || in_array(2,$rcPaySend) || in_array(3,$rcPaySend)) {  // (بدون هیچ پرداختی)و(کامل پرداخت شده)و(ناقص پرداخت شده)
            $query = "SELECT `pid` FROM `deposit`";
            $rst = $db->ArrayQuery($query);
            $cnt = count($rst);
            $rids = array();
            for ($i = 0; $i < $cnt; $i++) {
                $rids[] = $rst[$i]['pid'];
            }

            $query1 = "SELECT `cid` FROM `bank_check`";
            $rst1 = $db->ArrayQuery($query1);
            $cnt1 = count($rst1);
            for ($i = 0; $i < $cnt1; $i++) {
                $rids[] = $rst1[$i]['cid'];
            }
            $rids = array_values(array_unique($rids));
            $rids = implode(',', $rids);
            $m = array();
            if (in_array(1,$rcPaySend)){
                $m[] = '(`RowID` NOT IN ('.$rids.')) ';
            }
            if (in_array(2,$rcPaySend)){
                $m[] = '(`transfer`=3) ';
            }
            if (in_array(3,$rcPaySend)){
                $m[] = '(`transfer`!=3  AND `RowID` IN ('.$rids.')) ';
            }
            $m = implode(" OR ",$m);
            $m = '('.$m.')';
            $w[] = $m;
        }

        $sqlUnit1 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=20";  // واحد تدارکات
        $sqlUnit2 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=21";  // واحد فناوری اطلاعات
        $sqlUnit3 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=22";  // واحد اداری
        $sqlUnit4 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=24";  // واحد بازرگانی فروش
        $rstUnit1 = $db->ArrayQuery($sqlUnit1);
        $rstUnit2 = $db->ArrayQuery($sqlUnit2);
        $rstUnit3 = $db->ArrayQuery($sqlUnit3);
        $rstUnit4 = $db->ArrayQuery($sqlUnit4);

        $arr1 = explode(',',$rstUnit1[0]['uids']);
        $arr2 = explode(',',$rstUnit2[0]['uids']);
        $arr3 = explode(',',$rstUnit3[0]['uids']);
        $arr4 = explode(',',$rstUnit4[0]['uids']);

        sort($arr1);
        sort($arr2);
        sort($arr3);
        sort($arr4);

        unset($arr1[0]);
        unset($arr2[0]);
        unset($arr3[0]);
        unset($arr4[0]);

        if (in_array($_SESSION['userid'],$arr1)){
            $w[] = '`uid` IN ('.$rstUnit1[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr2)){
            $w[] = '`uid` IN ('.$rstUnit2[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr3)){
            $w[] = '`uid` IN ('.$rstUnit3[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr4)){
            $w[] = '`uid` IN ('.$rstUnit4[0]['uids'].') ';
        }

        $sql = "SELECT `RowID` FROM `pay_comment`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $rowIds = array();
        for ($i=0;$i<$cnt;$i++){
            $rowIds[] = $res[$i]['RowID'];
        }
        $rowIds = array_values(array_unique($rowIds));
        $rowIds = implode(',', $rowIds);

        $sql1 = "SELECT `RowID`,`layerName` FROM `layers` WHERE `parentID`={$fid}";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);
        $totalAmount = 0;
        $layers = array();

        if (intval($fid) !== 4) { // تنخواه نبود
            $layer2 = array();
            for ($i = 0; $i < $cnt1; $i++) {
                $sql2 = "SELECT `Amount` FROM `pay_comment` WHERE `layer2`={$res1[$i]['RowID']} AND `RowID` IN ({$rowIds})";
                $res2 = $db->ArrayQuery($sql2);
                $cnt3 = count($res2);
                $layers[$res1[$i]['RowID']]['layerName'] = $res1[$i]['layerName'];
                $layers[$res1[$i]['RowID']]['RowID'] = $res1[$i]['RowID'];
                $layer2[] = $res1[$i]['RowID'];
                for ($j = 0; $j < $cnt3; $j++) {
                    $totalAmount += $res2[$j]['Amount'];
                }
                $layers[$res1[$i]['RowID']]['totalAmount'] = $totalAmount;
                $totalAmount = 0;
            }

            $layer2 = implode(',', $layer2);
            $sql3 = "SELECT `RowID` FROM `pay_comment` WHERE `layer1`=4 AND `RowID` IN ({$rowIds})";
            $res3 = $db->ArrayQuery($sql3);
            $cnt4 = count($res3);

            for ($i = 0; $i < $cnt4; $i++) {
                $sql4 = "SELECT `finalAmount`,`layer2` FROM `fund_list` WHERE `pid`={$res3[$i]['RowID']} AND `layer2` IN ({$layer2})";
                $res4 = $db->ArrayQuery($sql4);
                if (count($res4) > 0) {
                    $cnt5 = count($res4);
                    for ($j = 0; $j < $cnt5; $j++) {
                        $layers[$res4[$j]['layer2']]['totalAmount'] = intval($layers[$res4[$j]['layer2']]['totalAmount']) + intval($res4[$j]['finalAmount']);
                    }
                }
            }
        }else{
            for ($i = 0; $i < $cnt1; $i++) {
                $sql2 = "SELECT `RowID`,`Amount` FROM `pay_comment` WHERE `layer2`={$res1[$i]['RowID']} AND `RowID` IN ({$rowIds})";
                $res2 = $db->ArrayQuery($sql2);
                $cnt3 = count($res2);
                $layers[$res1[$i]['RowID']]['layerName'] = $res1[$i]['layerName'];
                $layers[$res1[$i]['RowID']]['RowID'] = $res1[$i]['RowID'];
                for ($j = 0; $j < $cnt3; $j++) {
                    $sqq = "SELECT `RowID` FROM `fund_list` WHERE `pid`={$res2[$j]['RowID']}";
                    $rstq = $db->ArrayQuery($sqq);
                    if (count($rstq) <= 0) {
                        $totalAmount += $res2[$j]['Amount'];
                    }
                }
                $layers[$res1[$i]['RowID']]['totalAmount'] = $totalAmount;
                $totalAmount = 0;
            }
        }

        $layers = array_values($layers);
        $cnt = count($layers);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="reportPayCommentManage-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">سرگروه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">مبلغ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">زیر گروه فرعی</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$layers[$i]['layerName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($layers[$i]['totalAmount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showSeparationSubgroupTotalAmount('.$layers[$i]['RowID'].')" ><i class="fas fa-sitemap"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getSeparationSubgroupTotalAmount($fid,$unCode,$rcsDate,$rceDate,$rcaName,$rcToward,$rcAmount,$rcPaytype,$rcPaySend,$rpcsDate,$rpceDate,$rpnsDate,$rpneDate){
        $acm = new acm();
        if(!$acm->hasAccess('reportPayCommentManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $w = array();
        $w[] = '`isEnable`=1 ';

        if(strlen(trim($unCode)) > 0){
            $w[] = '`unCode`="'.$unCode.'" ';
        }
        if(strlen(trim($rcsDate)) > 0){
            $rcsDate = $ut->jal_to_greg($rcsDate);
            $w[] = '`pay_comment`.`cDate` >="'.$rcsDate.'" ';
        }
        if(strlen(trim($rceDate)) > 0){
            $rceDate = $ut->jal_to_greg($rceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$rceDate.'" ';
        }
        if(strlen(trim($rpcsDate)) > 0){
            $rpcsDate = $ut->jal_to_greg($rpcsDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` >="'.$rpcsDate.'" ';
        }
        if(strlen(trim($rpceDate)) > 0){
            $rpceDate = $ut->jal_to_greg($rpceDate);
            $w[] = '`pay_comment`.`paymentMaturityCash` <="'.$rpceDate.'" ';
        }
        if(strlen(trim($rpnsDate)) > 0){
            $rpnsDate = $ut->jal_to_greg($rpnsDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` >="'.$rpnsDate.'" ';
        }
        if(strlen(trim($rpneDate)) > 0){
            $rpneDate = $ut->jal_to_greg($rpneDate);
            $w[] = '`pay_comment`.`paymentMaturityCheck` <="'.$rpneDate.'" ';
        }
        if(strlen(trim($rcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$rcaName.'%" ';
        }
        if(strlen(trim($rcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$rcToward.'%" ';
        }
        if(strlen(trim($rcAmount)) > 0){
            $w[] = '`Amount`='.$rcAmount.' ';
        }
        if(intval($rcPaytype) >= 0){  // فورج نقدی، فورج چک، سهامی، ثبت در حساب بستانکاری
            switch ($rcPaytype){
                case 0:
                    $w[] = '`sendType`=0';
                    break;
                case 1:
                    $w[] = '`sendType`=1';
                    break;
                case 2:
                    $w[] = '`sendType`=2';
                    break;
                case 3:
                    $w[] = '`sendType`=3';
                    break;
            }
        }

        $rcPaySend = explode(',',$rcPaySend);
        if (in_array(1,$rcPaySend) || in_array(2,$rcPaySend) || in_array(3,$rcPaySend)) {  // (بدون هیچ پرداختی)و(کامل پرداخت شده)و(ناقص پرداخت شده)
            $query = "SELECT `pid` FROM `deposit`";
            $rst = $db->ArrayQuery($query);
            $cnt = count($rst);
            $rids = array();
            for ($i = 0; $i < $cnt; $i++) {
                $rids[] = $rst[$i]['pid'];
            }

            $query1 = "SELECT `cid` FROM `bank_check`";
            $rst1 = $db->ArrayQuery($query1);
            $cnt1 = count($rst1);
            for ($i = 0; $i < $cnt1; $i++) {
                $rids[] = $rst1[$i]['cid'];
            }
            $rids = array_values(array_unique($rids));
            $rids = implode(',', $rids);
            $m = array();
            if (in_array(1,$rcPaySend)){
                $m[] = '(`RowID` NOT IN ('.$rids.')) ';
            }
            if (in_array(2,$rcPaySend)){
                $m[] = '(`transfer`=3) ';
            }
            if (in_array(3,$rcPaySend)){
                $m[] = '(`transfer`!=3  AND `RowID` IN ('.$rids.')) ';
            }
            $m = implode(" OR ",$m);
            $m = '('.$m.')';
            $w[] = $m;
        }

        $sqlUnit1 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=20";  // واحد تدارکات
        $sqlUnit2 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=21";  // واحد فناوری اطلاعات
        $sqlUnit3 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=22";  // واحد اداری
        $sqlUnit4 = "SELECT `uids` FROM `relatedunits` WHERE `RowID`=24";  // واحد بازرگانی فروش
        $rstUnit1 = $db->ArrayQuery($sqlUnit1);
        $rstUnit2 = $db->ArrayQuery($sqlUnit2);
        $rstUnit3 = $db->ArrayQuery($sqlUnit3);
        $rstUnit4 = $db->ArrayQuery($sqlUnit4);

        $arr1 = explode(',',$rstUnit1[0]['uids']);
        $arr2 = explode(',',$rstUnit2[0]['uids']);
        $arr3 = explode(',',$rstUnit3[0]['uids']);
        $arr4 = explode(',',$rstUnit4[0]['uids']);

        sort($arr1);
        sort($arr2);
        sort($arr3);
        sort($arr4);

        unset($arr1[0]);
        unset($arr2[0]);
        unset($arr3[0]);
        unset($arr4[0]);

        if (in_array($_SESSION['userid'],$arr1)){
            $w[] = '`uid` IN ('.$rstUnit1[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr2)){
            $w[] = '`uid` IN ('.$rstUnit2[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr3)){
            $w[] = '`uid` IN ('.$rstUnit3[0]['uids'].') ';
        }
        if (in_array($_SESSION['userid'],$arr4)){
            $w[] = '`uid` IN ('.$rstUnit4[0]['uids'].') ';
        }

        $sql = "SELECT `RowID` FROM `pay_comment`";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $rowIds = array();
        for ($i=0;$i<$cnt;$i++){
            $rowIds[] = $res[$i]['RowID'];
        }
        $rowIds = array_values(array_unique($rowIds));
        $rowIds = implode(',', $rowIds);

        $sql1 = "SELECT `RowID`,`layerName` FROM `layers` WHERE `parentID`={$fid}";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);
        $totalAmount = 0;
        $layers = array();

        if (intval($fid) !== 4) { // تنخواه نبود
            $layer3 = array();
            for ($i = 0; $i < $cnt1; $i++) {
                $sql2 = "SELECT `Amount` FROM `pay_comment` WHERE `layer3`={$res1[$i]['RowID']} AND `RowID` IN ({$rowIds})";
                $res2 = $db->ArrayQuery($sql2);
                $cnt3 = count($res2);
                $layers[$res1[$i]['RowID']]['layerName'] = $res1[$i]['layerName'];
                $layers[$res1[$i]['RowID']]['RowID'] = $res1[$i]['RowID'];
                $layer3[] = $res1[$i]['RowID'];
                for ($j = 0; $j < $cnt3; $j++) {
                    $totalAmount += $res2[$j]['Amount'];
                }
                $layers[$res1[$i]['RowID']]['totalAmount'] = $totalAmount;
                $totalAmount = 0;
            }

            $layer3 = implode(',', $layer3);
            $sql3 = "SELECT `RowID` FROM `pay_comment` WHERE `layer1`=4 AND `RowID` IN ({$rowIds})";
            $res3 = $db->ArrayQuery($sql3);
            $cnt4 = count($res3);

            for ($i = 0; $i < $cnt4; $i++) {
                $sql4 = "SELECT `finalAmount`,`layer3` FROM `fund_list` WHERE `pid`={$res3[$i]['RowID']} AND `layer3` IN ({$layer3})";
                $res4 = $db->ArrayQuery($sql4);
                if (count($res4) > 0) {
                    $cnt5 = count($res4);
                    for ($j = 0; $j < $cnt5; $j++) {
                        $layers[$res4[$j]['layer3']]['totalAmount'] = intval($layers[$res4[$j]['layer3']]['totalAmount']) + intval($res4[$j]['finalAmount']);
                    }
                }
            }
        }else{
            for ($i = 0; $i < $cnt1; $i++) {
                $sql2 = "SELECT `RowID`,`Amount` FROM `pay_comment` WHERE `layer3`={$res1[$i]['RowID']} AND `RowID` IN ({$rowIds})";
                $res2 = $db->ArrayQuery($sql2);
                $cnt3 = count($res2);
                $layers[$res1[$i]['RowID']]['layerName'] = $res1[$i]['layerName'];
                $layers[$res1[$i]['RowID']]['RowID'] = $res1[$i]['RowID'];
                for ($j = 0; $j < $cnt3; $j++) {
                    $sqq = "SELECT `RowID` FROM `fund_list` WHERE `pid`={$res2[$j]['RowID']}";
                    $rstq = $db->ArrayQuery($sqq);
                    if (count($rstq) <= 0) {
                        $totalAmount += $res2[$j]['Amount'];
                    }
                }
                $layers[$res1[$i]['RowID']]['totalAmount'] = $totalAmount;
                $totalAmount = 0;
            }
        }

        $layers = array_values($layers);
        $cnt = count($layers);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="reportPayCommentManage-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">سرگروه</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">مبلغ</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';

        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$layers[$i]['layerName'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($layers[$i]['totalAmount']).' ریال</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function OtherAccountInfoCommentHTM($cid){
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `unCode`,`layer1`,`layer2`,`layer3`,`Unit`,`consumerUnit`,`type`,`totalAmount`,`paymentMaturityCash`,`BillingID`,`PaymentID`,`Transactions`,`RequestSource`,`RequestNumbers`,`desc` FROM `pay_comment` WHERE `RowID`={$cid}";
        $res = $db->ArrayQuery($sql);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoReportPayComment-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">سایر اطلاعات</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مقدار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        if ($res[0]['type'] == 'پرداخت قبض' || $res[0]['type'] == 'پرداخت جریمه'){
            $infoNames = array('شماره یکتا','سرگروه','زیرگروه','زیرگروه فرعی','واحد درخواست کننده','واحد مصرف کننده','نوع','مبلغ مربوط به کل معامله','سررسید پرداخت نقدی','شناسه قبض','شناسه پرداخت','طبقه معاملات','منبع درخواست','شماره درخواست','توضیحات');
            for ($i=0;$i<15;$i++){
                $iterator++;
                $keyName = key($res[0]);
                if ($iterator == 2 || $iterator == 3 || $iterator == 4){
                    $sql1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]["$keyName"]}";
                    $rst = $db->ArrayQuery($sql1);
                    $res[0]["$keyName"] = $rst[0]['layerName'];
                }
                if ($iterator == 5 || $iterator == 6){
                    $sql2 = "SELECT `unitName` FROM `relatedunits` WHERE `RowID`={$res[0]["$keyName"]}";
                    $rst2 = $db->ArrayQuery($sql2);
                    $res[0]["$keyName"] = $rst2[0]['unitName'];
                }
                if ($iterator == 8){
                    $res[0]["$keyName"] = number_format($res[0]["$keyName"]);
                }
                if ($iterator == 9){
                    $res[0]["$keyName"] = $ut->greg_to_jal($res[0]["$keyName"]);
                }
                if ($iterator == 12){
                    switch ($res[0]["$keyName"]){
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
                if ($iterator == 13){
                    switch ($res[0]["$keyName"]){
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
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]["$keyName"].'</td>';
                $htm .= '</tr>';
                next($res[0]);
            }
        }else{
            $sql = "SELECT `unCode`,`layer1`,`layer2`,`layer3`,`clearingFundDate`,`Unit`,`consumerUnit`,`type`,`totalAmount`,`paymentMaturityCash`,`paymentMaturityCheck`,`Transactions`,`accName`,`accNumber`,`accBank`,`codeTafzili`,`nationalCode`,`RequestSource`,`RequestNumbers`,`contractNumber`,`tick`,`checkNumber`,`checkDate`,`checkCarcass`,`checkDeliveryDate`,`RowID`,`desc` FROM `pay_comment` WHERE `pay_comment`.`RowID`={$cid}";
            $res = $db->ArrayQuery($sql);
            $infoNames = array('شماره یکتا','سر گروه','زیر گروه','زیرگروه فرعی','تاریخ تسویه تنخواه','واحد درخواست کننده','واحد مصرف کننده','نوع','مبلغ مربوط به کل معامله','سررسید پرداخت نقدی','سررسید پرداخت چک','طبقه معاملات','نام طرف حساب','شماره حساب','نام بانک و شعبه','کد تفضیلی','کد ملی','منبع درخواست','شماره درخواست','شماره قرارداد','پرینت گرفته و مستندات پیوست شده است','شماره چک','تاریخ چک','لاشه چک تحویل واحد مالی','تعهد تاریخ تحویل لاشه چک به واحد مالی','رسید تحویل چک','توضیحات');
            for ($i=0;$i<27;$i++){
                $iterator++;
                $keyName = key($res[0]);
                if ($iterator == 2 || $iterator == 3 || $iterator == 4){
                    $sql1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]["$keyName"]}";
                    $rst = $db->ArrayQuery($sql1);
                    $res[0]["$keyName"] = $rst[0]['layerName'];
                }
                if ($iterator == 5){
                    if (strtotime($res[0]["$keyName"]) > 0){
                        $res[0]["$keyName"] = $ut->greg_to_jal($res[0]["$keyName"]);
                    }else{
                        next($res[0]);
                        continue;
                    }
                }
                if ($iterator == 6 || $iterator == 7){
                    $sql2 = "SELECT `unitName`  FROM `relatedunits` WHERE `RowID`={$res[0]["$keyName"]}";
                    $rst2 = $db->ArrayQuery($sql2);
                    $res[0]["$keyName"] = $rst2[0]['unitName'];
                }
                if ($iterator == 9){
                    $res[0]["$keyName"] = number_format($res[0]["$keyName"]);
                }
                if ($iterator == 10 || $iterator == 11){
                    $res[0]["$keyName"] = (strtotime($res[0]["$keyName"]) > 0 ? $ut->greg_to_jal($res[0]["$keyName"]) : '');
                }
                if ($iterator == 12){
                    switch ($res[0]["$keyName"]){
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
                if ($iterator == 18){
                    switch ($res[0]["$keyName"]){
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

                if ($iterator == 21){
                    switch ($res[0]["$keyName"]){
                        case 0:
                            $res[0]["$keyName"] = 'خیر';
                            break;
                        case 1:
                            $res[0]["$keyName"] = 'بلی';
                            break;
                    }
                }

                if ($iterator == 24){
                    switch (intval($res[0]["$keyName"])){
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

                if ($iterator == 23 || $iterator == 25){
                    $res[0]["$keyName"] = (strtotime($res[0]["$keyName"]) > 0 ? $ut->greg_to_jal($res[0]["$keyName"]) : '');
                }
                if ($iterator == 26){
                    $res[0]["$keyName"] = '<button class="btn btn-info" onclick="downloadCheckCarcassFileRpt('.$res[0]["$keyName"].')"><i class="fas fa-download"></i></button>';
                }

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]["$keyName"].'</td>';
                $htm .= '</tr>';
                next($res[0]);
            }
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function commentWorkflowRptHtm($pid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `payment_workflow`.*,`fname`,`lname` FROM `payment_workflow` INNER JOIN `users` ON (`payment_workflow`.`sender`=`users`.`RowID`) WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileComment5-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">فرستنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">گیرنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">وضعیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ ارسال</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زمان ارسال</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$i]['receiver']}";
            $rst = $db->ArrayQuery($query);
            $status = ($res[$i]['status'] == 0 ? 'عدم تایید' : 'تایید');
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$rst[0]['fname'].' '.$rst[0]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$status.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createDate'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getAttachFundToCommentReportList($cid){
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT `fund_list`.*,`fname`,`lname` FROM `fund_list` INNER JOIN `users` ON (`fund_list`.`uid`=`users`.`RowID`) WHERE `pid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="contractChooseList-tableID">';
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

        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $sqq = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer1']}";
            $sqq1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer2']}";
            $sqq2 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer3']}";
            $rst = $db->ArrayQuery($sqq);
            $rst1 = $db->ArrayQuery($sqq1);
            $rst2 = $db->ArrayQuery($sqq2);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res[$i]['cDate']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(intval($res[$i]['fundName']) == 0 ? 'تنخواه هزینه ای' : 'تنخواه مصرفی').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['unCode'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(count($rst) > 0 ? $rst[0]['layerName'] : '').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(count($rst1) > 0 ? $rst1[0]['layerName'] : '').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(count($rst2) > 0 ? $rst2[0]['layerName'] : '').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res[$i]['finalAmount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="attachedFundListDetailsReport('.$res[$i]['RowID'].')"><i class="fas fa-puzzle-piece"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function reportFundListDetailsHTM($fid){
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `fund_list_details` WHERE `fid`={$fid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="showFundListDetailsHTM-tableID">';
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
        for ($i=0;$i<$cnt;$i++){
            $cDate = (strtotime($res[$i]['createDate']) > 0 ? $ut->greg_to_jal($res[$i]['createDate']) : '');
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$cDate.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['reqNumber'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['placeUse'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res[$i]['fundAmount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="reportAttachFundListDetails('.$res[$i]['RowID'].')"><i class="fas fa-link"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getPrintReportPayCommentHtm($cid){
        $acm = new acm();
        if(!$acm->hasAccess('reportPayCommentManage')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        // $sql = "SELECT `pay_comment`.*,`unitName`,`fname`,`lname`,`layerName`,`postJob`,`signature` FROM `pay_comment`
        //         INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)
        //         INNER JOIN `users` ON (`pay_comment`.`uid`=`users`.`RowID`)
        //         INNER JOIN `layers` ON (`pay_comment`.`layer1`=`layers`.`RowID`)
        //         WHERE `pay_comment`.`RowID`={$cid}";
        //----------------------------------------------------------------watermark----------------------------------------------------------
        $watermark="";
        $get_payed_sql="select p.Amount,p.CashSection,p.NonCashSection,p.isPaid,d.dAmount,b.check_amount from pay_comment as p 
        LEFT JOIN (select SUM(dAmount) as dAmount,pid from deposit GROUP BY pid )as d on d.pid=p.RowID
        LEFT JOIN (SELECT check_amount,cid from bank_check GROUP BY cid) as b on b.cid=p.RowID 
        where p.RowID=${cid}";
        $watermark_flag=false;
        $p_res=$db->ArrayQuery($get_payed_sql);
        if($p_res[0]['isPaid']==1){
            $watermark_flag=true;
        }
        else{
            //$ut->fileRecorder(2);
            $payed=intval($p_res[0]['dAmount'])+intval($p_res[0]['check_amount']);
            
            if(intval($p_res[0]['Amount']<=$payed)){
                $watermark_flag=true;
            }
            if($watermark_flag==true){
                $watermark='<div style="position:absolute;top:50%;right:1%;z-index:1000;width: 100%;height: auto;justify-content: center;align-items: center"  id="watermark"> <p style="text-align:center;padding:0;border-radius:10px;transform:rotate(-45deg);border:1rem solid rgba(255,0,0,0.2);color:rgba(255,0,0,0.2);font-size: 10rem;">پرداخت شد</p></div>';
            }
        }
        //----------------------------------------------------------------watermark----------------------------------------------------------
        $sql = "SELECT `pay_comment`.*,`unitName`,`fname`,`lname`,`layerName`,`postJob`,`signature`,account.`codeMelli` FROM `pay_comment`
        INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)
        INNER JOIN `users` ON (`pay_comment`.`uid`=`users`.`RowID`)
        INNER JOIN `layers` ON (`pay_comment`.`layer1`=`layers`.`RowID`)
        left JOIN `account` ON (`pay_comment`.`codeTafzili`=`account`.`code`)
        WHERE `pay_comment`.`RowID`={$cid}";
        $res = $db->ArrayQuery($sql);

        $query = "SELECT `unitName`  FROM `relatedunits` WHERE `RowID`={$res[0]['consumerUnit']}";
        $rst = $db->ArrayQuery($query);

        if (intval($res[0]['layer2']) > 0) {
            $query1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]['layer2']}";
            $rst1 = $db->ArrayQuery($query1);
            $twoLayer = $rst1[0]['layerName'];
        }else{
            $twoLayer = '&emsp;&emsp;&emsp;&emsp;';
        }
        if (intval($res[0]['layer3']) > 0) {
            $query2 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]['layer3']}";
            $rst2 = $db->ArrayQuery($query2);
            $threeLayer = $rst2[0]['layerName'];
        }else{
            $threeLayer = '&emsp;&emsp;&emsp;&emsp;';
        }

        $sqlsig = "SELECT `sender`,`createDate`,`fname`,`lname`,`postJob`,`signature` FROM `payment_workflow` INNER JOIN `users` ON  (`payment_workflow`.`sender`=`users`.`RowID`) WHERE `status`=1 AND `pid`={$cid} ORDER BY `payment_workflow`.`RowID` ASC";
        $rsig = $db->ArrayQuery($sqlsig);
        $cnt = count($rsig);

        $beginner = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$res[0]['signature'].'">';
        $fbeginner = $res[0]['postJob'].' '.$ut->greg_to_jal($res[0]['cDate']).' '.$res[0]['fname'].' '.$res[0]['lname'];
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

        for ($i=0;$i<$cnt;$i++) {
            if ($rsig[$i]['sender'] == 3 || $rsig[$i]['sender'] == 14 || $rsig[$i]['sender'] == 67 || $rsig[$i]['sender'] == 68){  // حقیقت و مصطفوی خطیبیان و پورحسین
                $Sarparast = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fSarparast = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 20){  // معاونت بازرگانی
                $Moavenat = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fMoavenat = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 44 || $rsig[$i]['sender'] == 42){  // کارشناس حسابداری
                $Hesabdar = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fHesabdar = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 75 || $rsig[$i]['sender'] == 39){  // رئیس حسابداری
                $RHesabdar = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fRHesabdar = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 27 || $rsig[$i]['sender'] == 72){  // مدیر مالی
                $MHesabdar = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fMHesabdar = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 4){  // مدیر عامل
                $Modiriat = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fModiriat = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
        }


        if (!($res[0]['accNumber'] === '0') || !($res[0]['accNumber'] === '')){
            $accountNumber = explode('-',$res[0]['accNumber']);
            $accountNumber = array_reverse($accountNumber);
            $accountNumber = implode('-',$accountNumber);
            $banks = $res[0]['accBank'];
        }

        $datetostring = substr($ut->greg_to_jal(str_replace('/','-',$res[0]['cDate'])),2);
        $datetostring = explode('/',$datetostring);
        $m = (intval($datetostring[1]) <= 9 ? '0'.$datetostring[1] : $datetostring[1]);
        $d = (intval($datetostring[2]) <= 9 ? '0'.$datetostring[2] : $datetostring[2]);
        $datetostring = [0=>$datetostring[0] , 1=>$m , 2=>$d];
        $datetostring = implode('/',$datetostring);
        $personalCode = [0=>$datetostring,1=>$res[0]['uid']];
        $personalCode = implode('-',$personalCode);

        switch ($res[0]['Transactions']){
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

        if (intval($res[0]['RequestSource']) > 0){
            switch ($res[0]['RequestSource']){
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
        }else{
            $SN = 'شماره درخواست : ';
            $RequestSN = $res[0]['RequestNumbers'];
        }

        if (intval($res[0]['sendType']) == 2){  // سهامی بود
            if (intval($res[0]['CashSection']) > 0 && intval($res[0]['NonCashSection']) > 0){
                $naghd = 'مبلغ نقد : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['CashSection']).'</span> ریال به تاریخ : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCash']).'</span>';
                $checki = 'مبلغ چک : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['NonCashSection']).'</span> ریال به تاریخ (میانگین) : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCheck']).'</span>';
                $mablagh = $naghd.' '.$checki;
                $shaba = 'شماره شبا : <span style="font-size: 25px;font-family: BTitr;">'.$accountNumber.'</span>';
                $AccAndBank = 'نام بانک و صاحب حساب : <span style="font-size: 25px;font-family: BTitr;">'.$banks.'</span>';
            }elseif (intval($res[0]['CashSection']) > 0){
                $mablagh = 'مبلغ نقد : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['CashSection']).'</span> ریال به تاریخ : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCash']).'</span>';
                if ($res[0]['type'] != 'پرداخت قبض' && $res[0]['type'] != 'پرداخت جریمه') {
                    $shaba = 'شماره شبا : <span style="font-size: 25px;font-family: BTitr;">'.$accountNumber.'</span>';
                    $AccAndBank = 'نام بانک و صاحب حساب : <span style="font-size: 25px;font-family: BTitr;">'.$banks.'</span>';
                }else{
                    $shaba = 'شناسه قبض : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['BillingID'].'</span>';
                    $AccAndBank = 'شناسه پرداخت : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['PaymentID'].'</span>';
                }
            }else{
                $mablagh = 'مبلغ چک : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['NonCashSection']).'</span> ریال به تاریخ (میانگین) : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCheck']).'</span>';
                $shaba = 'فاقد بخش نقدی می باشد !!!';
                $AccAndBank = '';
            }
        }elseif (intval($res[0]['sendType']) == 0){  // فورج نقدی
            $mablagh = 'مبلغ نقد : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['CashSection']).'</span> ریال به تاریخ : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCash']).'</span>';
            if ($res[0]['type'] != 'پرداخت قبض' && $res[0]['type'] != 'پرداخت جریمه') {
                $shaba = 'شماره شبا : <span style="font-size: 25px;font-family: BTitr;">'.$accountNumber.'</span>';
                $AccAndBank = 'نام بانک و صاحب حساب : <span style="font-size: 25px;font-family: BTitr;">'.$banks.'</span>';
            }else{
                $shaba = 'شناسه قبض : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['BillingID'].'</span>';
                $AccAndBank = 'شناسه پرداخت : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['PaymentID'].'</span>';
            }
        }elseif (intval($res[0]['sendType']) == 1){  // فورج چکی
            $mablagh = 'مبلغ چک : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['NonCashSection']).'</span> ریال به تاریخ (میانگین) : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCheck']).'</span>';
            $shaba = 'فاقد بخش نقدی می باشد !!!';
            $AccAndBank = '';
        }else{
            $mablagh = 'مبلغ : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['Amount']).'</span> ریال ';
            $shaba = 'فاقد بخش نقدی و غیر نقدی می باشد !!!';
            $AccAndBank = '';
        }

        $srcc = ADDR.'images/abrash.png';
        $htm = '';
        $htm .= '<div class="demoRPC" style="width: 100%;margin: -85px auto;">';
        $htm.=$watermark;
        // page 1
        $htm .= '<table style="width: 100%;border: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr style="border: 2px solid #000;">';
        $htm .= '<th style="width: 25%;padding-left: 10px;text-align: center;background-color: #fff;"><img src="'.$srcc.'"></th>';
        $htm .= '<th style="width: 50%;font-size: 40px;font-family: BTitr;background-color: #ddd;text-align: center;padding: 0 20px;">اظهار نظر و درخواست<br> پرداخت وجه</th>';
        $htm .= '<th style="width: 25%;font-size: 20px;font-family: BNazanin;text-align: right;padding-right: 30px;background-color: #fff;">کد فرم : F121009<br>کد ثبت : '.$personalCode.'<br>سطح تغييرات:  2</th>';
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
        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: right;font-weight: bold;" class="pr-3">شماره یکتا : '.$res[0]['unCode'].'</td>';
        $htm .= '<td style="width: 30%;background-color: #fff;font-size: 30px;font-family: BTitr;text-align: center;">'.$res[0]['type'].'</td>';
        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: left;font-weight: bold;" class="pl-3">تاریخ : '.$ut->greg_to_jal($res[0]['cDate']).'</td>';
        $htm .= '</tr>';
        $htm .= '<tr>';
        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: right;font-weight: bold;" class="pr-3">سرگروه : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['layerName'].'</span></td>';
        $htm .= '<td style="width: 30%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: center;font-weight: bold;">زیرگروه : <span style="font-size: 25px;font-family: BTitr;">'.$twoLayer.'</span></td>';
        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: left;font-weight: bold;" class="pl-3">زیرگروه فرعی : <span style="font-size: 25px;font-family: BTitr;">'.$threeLayer.'</span></td>';
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
        $htm .= '<td style="padding-top: 5px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">فرد صادرکننده : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['fname'].' '.$res[0]['lname'].'</span></td>';
        $htm .= '<td style="padding-top: 5px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;">طبقه معاملات : <span style="font-size: 25px;font-family: BTitr;">'.$Transactions.'</span></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding-top: 3px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">واحد درخواست کننده : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['unitName'].'</span></td>';
        $htm .= '<td style="padding-top: 3px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;">'.$SN.'<span style="font-size: 25px;font-family: BTitr;">'.$RequestSN.'</span></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 3px 0;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">واحد مصرف کننده : <span style="font-size: 25px;font-family: BTitr;">'.$rst[0]['unitName'].'</span></td>';
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
        $htm .= '<td style="padding: 5px 0;width: 60%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">طرف مقابل : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['accName'].' - '.$res[0]['codeTafzili'].'</span></td>';
        $htm .= '<td style="padding: 5px 0;width: 40%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;">شماره قرارداد : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['contractNumber'].'</span></td>';
        $htm .= '</tr>';
        //-------------------------------
        if(!empty($res[0]['codeMelli'])){
            $htm.="<tr>"; 
                $htm .= '<td style="padding: 5px 0;width: 40%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">کد/شناسه ملی: <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['codeMelli'].'</span></td>';
            $htm .= '</tr>';
        }
        //-------------------------------
       
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding-top: 3px;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">بابت : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['Toward'].'</span></td>';
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
        $htm .= '<td style="padding-top: 3px;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">'.$mablagh.'</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 3px 0;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">توضیحات : <span style="font-size: 25px;font-family: BTitr;" dir="rtl">'.$res[0]['desc'].'</span></td>';
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
        $htm .= '<td style="padding: 7px 0;width: 60%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">'.$shaba.'</td>';
        $htm .= '<td style="padding: 7px 0;width: 40%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: right;">'.$AccAndBank.'</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        //************************ امضا کارشناس و سرپرست و مدیر واحد *************************

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$beginner.$fbeginner.'</td>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 34%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$Sarparast.$fSarparast.'</td>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$Moavenat.$fMoavenat.'</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        //************************ امضا حسابداری *************************

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$Hesabdar.$fHesabdar.'</td>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 34%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$RHesabdar.$fRHesabdar.'</td>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$MHesabdar.$fMHesabdar.'</td>';
        $htm .= '</tr>';
        if (strtotime($res[0]['clearingFundDate']) > 0) {
            $htm .= '<tr>';
            $htm .= '<td colspan="3" class="pr-3" style="padding: 10px 0 20px 0;width: 100%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: right;">قابل توجه واحد مالی : تاریخ تسویه تنخواه '.$ut->greg_to_jal($res[0]['clearingFundDate']).' می باشد</td>';
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
        $htm .= '<td class="pl-3" style="padding: 50px 0 20px 0;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: left;">'.$Modiriat.$fModiriat.'</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';
        $htm .= '</div>';

        return $htm;
    }

    public function downloadCheckCarcassFileRPTHtm($pid){
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `check_carcass` WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="downloadCheckCarcassFileRPT-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">نام فایل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $fileName = 'رسید شماره '.$iterator;
            $link = ADDR.'checkCarcass/'.$res[$i]['fileName'];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$fileName.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getDepositsListInfoExcel($pid){
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `dDate`,`depositor`,`dBank`,`dAmount`,`dDesc`,`fname`,`lname`,`accName` FROM `deposit` 
                INNER JOIN `pay_comment` ON (`pay_comment`.`RowID`=`deposit`.`pid`)
                INNER JOIN `users` ON (`deposit`.`uid`=`users`.`RowID`) WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        for ($i=0;$i<$cnt;$i++){
            $res[$i]['dDate'] = (strtotime($res[$i]['dDate']) > 0 ? $ut->greg_to_jal($res[$i]['dDate']) : '');
            $res[$i]['fname'] = $res[$i]['fname'].' '.$res[$i]['lname'];
        }

        $sql1 = "SELECT `check_amount`,`check_date`,`check_number`,`description`,`fname`,`lname`,`accName` FROM `bank_check`
                INNER JOIN `pay_comment` ON (`pay_comment`.`RowID`=`bank_check`.`cid`)
                INNER JOIN `users` ON (`bank_check`.`uid`=`users`.`RowID`) WHERE `cid`={$pid}";
        $res1 = $db->ArrayQuery($sql1);
        $ccnt = count($res1);

        $x = 0;
        for ($i=$cnt;$x<$ccnt;$i++){
            $res[$i]['dDate'] = (strtotime($res1[$x]['check_date']) > 0 ? $ut->greg_to_jal($res1[$x]['check_date']) : '');
            $res[$i]['fname'] = $res1[$x]['fname'].' '.$res1[$x]['lname'];
            $res[$i]['accName'] = $res1[$x]['accName'];
            $res[$i]['depositor'] = $res1[$x]['check_number'];
            $res[$i]['dBank'] = '';
            $res[$i]['dDesc'] = $res1[$x]['description'];
            $res[$i]['dAmount'] = $res1[$x]['check_amount'];
            $x++;
        }

        return $res;
    }

    //++++++++++++++++++++ گزارش اظهارنظرهای حذف شده ++++++++++++++++++++

    public function getDeletedPayCommentReportHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('deletedPayCommentReport')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $pagename = "گزارش اظهارنظرهای حذف شده";
        $pageIcon = "fas fa-chart-area";
        $contentId = "deletedPayCommentReportBody";
        $hiddenContentId = 'hiddenDeletedCommentBody';

        $units = $this->getUnits();
        $CountUnits = count($units);

        $bottons= array();
        $headerSearch = array();

        $a = 0;
        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "90px";
        $headerSearch[$a]['id'] = "deletedPayCommentReportSDateSearch";
        $headerSearch[$a]['title'] = "از تاریخ";
        $headerSearch[$a]['placeholder'] = "از تاریخ";
        $a++;

        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "90px";
        $headerSearch[$a]['id'] = "deletedPayCommentReportEDateSearch";
        $headerSearch[$a]['title'] = "تا تاریخ";
        $headerSearch[$a]['placeholder'] = "تا تاریخ";
        $a++;

        $headerSearch[$a]['type'] = "select";
        $headerSearch[$a]['id'] = "deletedPayCommentReportUnitSearch";
        $headerSearch[$a]['title'] = "واحد درخواست کننده";
        $headerSearch[$a]['width'] = "200px";
        $headerSearch[$a]['options'] = array();
        $headerSearch[$a]['options'][0]["title"] = "واحد درخواست کننده";
        $headerSearch[$a]['options'][0]["value"] = 0;
        for ($i=0;$i<$CountUnits;$i++){
            $headerSearch[$a]['options'][$i+1]["title"] = $units[$i]['unitName'];
            $headerSearch[$a]['options'][$i+1]["value"] = $units[$i]['RowID'];
        }
        $a++;

        $headerSearch[$a]['type'] = "select";
        $headerSearch[$a]['id'] = "deletedPayCommentReportConsumerUnitSearch";
        $headerSearch[$a]['title'] = "واحد مصرف کننده";
        $headerSearch[$a]['width'] = "200px";
        $headerSearch[$a]['options'] = array();
        $headerSearch[$a]['options'][0]["title"] = "واحد مصرف کننده";
        $headerSearch[$a]['options'][0]["value"] = 0;
        for ($i=0;$i<$CountUnits;$i++){
            $headerSearch[$a]['options'][$i+1]["title"] = $units[$i]['unitName'];
            $headerSearch[$a]['options'][$i+1]["value"] = $units[$i]['RowID'];
        }
        $a++;

        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "150px";
        $headerSearch[$a]['id'] = "deletedPayCommentReportAccNameSearch";
        $headerSearch[$a]['title'] = "نام طرف حساب";
        $headerSearch[$a]['placeholder'] = "نام طرف حساب";
        $a++;

        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "150px";
        $headerSearch[$a]['id'] = "deletedPayCommentReportTowardSearch";
        $headerSearch[$a]['title'] = "بابت";
        $headerSearch[$a]['placeholder'] = "بابت";
        $a++;

        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "150px";
        $headerSearch[$a]['id'] = "deletedPayCommentReportUncodeSearch";
        $headerSearch[$a]['title'] = "کد یکتا";
        $headerSearch[$a]['placeholder'] = "کد یکتا";
        $a++;

        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "150px";
        $headerSearch[$a]['id'] = "deletedPayCommentReportAmountSearch";
        $headerSearch[$a]['onkeyup'] = "onkeyup=addSeprator()";
        $headerSearch[$a]['title'] = "مبلغ";
        $headerSearch[$a]['placeholder'] = "مبلغ";
        $a++;

        $headerSearch[$a]['type'] = "btn";
        $headerSearch[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
        $headerSearch[$a]['jsf'] = "showDeletedPayCommentReportList";

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch,'',array(),$hiddenContentId);
        //++++++++++++++++++++++++++++++++++ payComment Search MODAL ++++++++++++++++++++++++++++++++
        //++++++++++++++++++ Start Comment Info Modal ++++++++++++++++++++++
        $modalID = "deletedCommentManageInfoModal";
        $modalTitle = "سایر اطلاعات";
        $ShowDescription = 'comment-deleted-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showCommentInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Comment Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ comment Attachment File Modal ++++++++++++++++++++++
        $modalID = "deletedCommentAttachmentFileModal";
        $modalTitle = "دانلود فایل های پیوست و رسیدهای پرداخت";
        $ShowDescription = 'deletedCommentAttachmentFile-body';
        $style = 'style="max-width: 655px;"';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $commentAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End comment Attachment File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ comment Workflow Modal ++++++++++++++++++++++
        $modalID = "deletedCommentWorkflowModal";
        $modalTitle = "گردش کار اظهارنظر";
        $style = 'style="max-width: 1000px;"';
        $ShowDescription = 'deletedCommentWorkflow-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $commentWorkflow = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End comment Workflow Modal ++++++++++++++++++++++++
        //++++++++++++++++++ download CheckCarcass File Modal ++++++++++++++++++++++
        $modalID = "downloadCheckCarcassFileDelModal";
        $modalTitle = "دانلود رسید لاشه چک";
        $ShowDescription = 'downloadCheckCarcassFileDel-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $downloadCheckCarcassFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End download CheckCarcass File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show Attached Fund To Deleted Comment Modal ++++++++++++++++++++++
        $modalID = "showAttachedFundToDeletedCommentModal";
        $modalTitle = "لیست تنخواه ها";
        $ShowDescription = 'showAttachedFundToDeletedComment-body';
        $style = 'style="max-width: 1200px;"';

        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showAttachedFundToDeletedComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show Attached Fund To Deleted Comment Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ show Fund List Details Deleted Comment MODAL ++++++++++++++++++++++++++++++++
        $modalID = "showFundListDetailsDeletedCommentModal";
        $modalTitle = "جزئیات تنخواه";
        $style = 'style="max-width: 700px;"';
        $ShowDescription = 'fundListDetails-DeletedComment-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showFundListDetailsDeletedComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //++++++++++++++++++++++++++++++++++ End show Fund List Details Deleted Comment MODAL ++++++++++++++++++++++++++++++++
        //++++++++++++++++++ fundList Show Attachment File Modal ++++++++++++++++++++++
        $modalID = "showFundListAttachmentDeletedCommentFileModal";
        $modalTitle = "فایل های پیوست";
        $ShowDescription = 'showFundListAttachmentDeletedCommentFile-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showFundListAddAttachmentFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End fundList Show Attachment File Modal ++++++++++++++++++++++++
        $htm .= $showCommentInfo;
        $htm .= $commentAttachmentFile;
        $htm .= $commentWorkflow;
        $htm .= $downloadCheckCarcassFile;
        $htm .= $showAttachedFundToDeletedComment;
        $htm .= $showFundListDetailsDeletedComment;
        $htm .= $showFundListAddAttachmentFile;
        return $htm;
    }

    public function getDeletedPayCommentReportList($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('deletedPayCommentReport')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`pay_comment`.`cDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$ceDate.'" ';
        }
        if(intval($cUnit) > 0){
            $w[] = '`Unit`='.$cUnit.' ';
        }
        if(intval($coUnit) > 0){
            $w[] = '`consumerUnit`='.$coUnit.' ';
        }
        if(strlen(trim($caName)) > 0){
            $w[] = '`accName` LIKE "%'.$caName.'%" ';
        }
        if(strlen(trim($cToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$cToward.'%" ';
        }
        if(strlen(trim($Uncode)) > 0){
            $w[] = '`unCode`='.$Uncode.' ';
        }
        if(strlen(trim($amount)) > 0){
            $w[] = '`Amount`='.$amount.' ';
        }
        $w[] = '`isEnable`=0 ';

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`consumerUnit`,`Toward`,`Amount`,`unitName`,`sendType`,`sabtDarHesabType`,`priorityLevel`   
                FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `pcid` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $sqlUName = "SELECT `unitName` FROM `relatedunits` WHERE `RowID`={$res[$y]['consumerUnit']}";
            $result = $db->ArrayQuery($sqlUName);
            switch ($res[$y]['sendType']){
                case 0:
                    $typeComment = 'فورج نقدی';
                    $finalRes[$y]['bgColor'] = 'table-warning';
                    break;
                case 1:
                    $typeComment = 'فورج چک';
                    $finalRes[$y]['bgColor'] = 'table-orange';
                    break;
                case 2:
                    $typeComment = 'سهامی';
                    $finalRes[$y]['bgColor'] = 'table-primary';
                    break;
                case 3:
                    $typeComment = ($res[$y]['sabtDarHesabType'] == 0 ? 'ثبت در حساب بستانکاری فورج' : 'ثبت در حساب بستانکاری سهامی');
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
            }
            $finalRes[$y]['RowID'] = $res[$y]['pcid'];
            $finalRes[$y]['typeComment'] = $typeComment;
            $finalRes[$y]['cDate'] = $ut->greg_to_jal($res[$y]['cDate']);
            $finalRes[$y]['Unit'] = $res[$y]['unitName'];
            $finalRes[$y]['consumerUnit'] = $result[0]['unitName'];
            $finalRes[$y]['accName'] = (strlen(trim($res[$y]['accName'])) == 0 ? '--------' : $res[$y]['accName']);
            $finalRes[$y]['Toward'] = $res[$y]['Toward'];
            $finalRes[$y]['blinker'] = ($res[$y]['priorityLevel'] == 1 ? 'blink' : '');
            $finalRes[$y]['Amount'] = number_format($res[$y]['Amount']).' ریال';
        }
        return $finalRes;
    }

    public function getDeletedPayCommentReportListCountRows($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount){
        $ut = new Utility();
        $db = new DBi();
        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`pay_comment`.`cDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$ceDate.'" ';
        }
        if(intval($cUnit) > 0){
            $w[] = '`Unit`='.$cUnit.' ';
        }
        if(intval($coUnit) > 0){
            $w[] = '`consumerUnit`='.$coUnit.' ';
        }
        if(strlen(trim($caName)) > 0){
            $w[] = '`accName` LIKE "%'.$caName.'%" ';
        }
        if(strlen(trim($cToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$cToward.'%" ';
        }
        if(strlen(trim($Uncode)) > 0){
            $w[] = '`unCode`='.$Uncode.' ';
        }
        if(strlen(trim($amount)) > 0){
            $w[] = '`Amount`='.$amount.' ';
        }
        $w[] = '`isEnable`=0 ';

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`consumerUnit`,`Toward`,`Amount`,`unitName`,`sendType`,`sabtDarHesabType`   
                FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function otherInfoDeletedCommentHTM($cid){
        $acm = new acm();
        if(!$acm->hasAccess('deletedPayCommentReport')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `unCode`,`layer1`,`layer2`,`layer3`,`type`,`CashSection`,`paymentMaturityCash`,`BillingID`,`PaymentID`,`Transactions`,`RequestSource`,`RequestNumbers`,`desc` FROM `pay_comment` WHERE `RowID`={$cid}";
        $res = $db->ArrayQuery($sql);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoDeletedComment-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">سایر اطلاعات</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مقدار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        if ($res[0]['type'] == 'پرداخت قبض' || $res[0]['type'] == 'پرداخت جریمه'){
            $infoNames = array('شماره یکتا','سر گروه','زیر گروه','زیرگروه فرعی','نوع','بخش نقدی','سررسید پرداخت','شناسه قبض','شناسه پرداخت','طبقه معاملات','منبع درخواست','شماره درخواست','توضیحات');
            for ($i=0;$i<13;$i++){
                $iterator++;
                $keyName = key($res[0]);
                if ($iterator == 2 || $iterator == 3 || $iterator == 4){
                    $sql1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]["$keyName"]}";
                    $rst = $db->ArrayQuery($sql1);
                    $res[0]["$keyName"] = $rst[0]['layerName'];
                }
                if ($iterator == 6){
                    $res[0]["$keyName"] = number_format($res[0]["$keyName"]).' ریال';
                }
                if ($iterator == 7){
                    $res[0]["$keyName"] = $ut->greg_to_jal($res[0]["$keyName"]);
                }
                if ($iterator == 10){
                    switch ($res[0]["$keyName"]){
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
                if ($iterator == 11){
                    switch ($res[0]["$keyName"]){
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
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]["$keyName"].'</td>';
                $htm .= '</tr>';
                next($res[0]);
            }
        }else{
            $sql = "SELECT `unCode`,`layer1`,`layer2`,`layer3`,`clearingFundDate`,`type`,`CashSection`,`paymentMaturityCash`,`NonCashSection`,`paymentMaturityCheck`,`Transactions`,`accName`,`accNumber`,`accBank`,`codeTafzili`,`nationalCode`,`RequestSource`,`RequestNumbers`,`contractNumber`,`tick`,`checkNumber`,`checkDate`,`checkCarcass`,`checkDeliveryDate`,`RowID`,`desc`,`cardNumber` FROM `pay_comment` WHERE `pay_comment`.`RowID`={$cid}";
            $res = $db->ArrayQuery($sql);
            $infoNames = array('شماره یکتا','سر گروه','زیر گروه','زیرگروه فرعی','تاریخ تسویه تنخواه','نوع','بخش نقدی','سررسید پرداخت نقدی','بخش چک','سررسید پرداخت چک','طبقه معاملات','نام طرف حساب','شماره حساب','نام بانک و شعبه','کد تفضیلی','کد ملی','منبع درخواست','شماره درخواست','شماره قرارداد','پرینت گرفته و مستندات پیوست شده است','شماره چک','تاریخ چک','لاشه چک تحویل واحد مالی','تعهد تاریخ تحویل لاشه چک به واحد مالی','رسید تحویل چک','توضیحات','شماره کارت');
            $direction = '';
            for ($i=0;$i<27;$i++){
                $iterator++;
                $keyName = key($res[0]);
                if ($iterator == 2 || $iterator == 3 || $iterator == 4){
                    $sql1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]["$keyName"]}";
                    $rst = $db->ArrayQuery($sql1);
                    $res[0]["$keyName"] = $rst[0]['layerName'];
                }
                if ($iterator == 5){
                    if (strtotime($res[0]["$keyName"]) > 0){
                        $res[0]["$keyName"] = $ut->greg_to_jal($res[0]["$keyName"]);
                    }else{
                        next($res[0]);
                        continue;
                    }
                }
                if ($iterator == 7 || $iterator == 9){
                    $res[0]["$keyName"] = number_format($res[0]["$keyName"]).' ریال';
                }
                if ($iterator == 8 || $iterator == 10){
                    $res[0]["$keyName"] = (strtotime($res[0]["$keyName"]) > 0 ? $ut->greg_to_jal($res[0]["$keyName"]) : '');
                }
                if ($iterator == 11){
                    switch ($res[0]["$keyName"]){
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
                if ($iterator == 17){
                    switch ($res[0]["$keyName"]){
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

                if ($iterator == 20){
                    switch ($res[0]["$keyName"]){
                        case 0:
                            $res[0]["$keyName"] = 'خیر';
                            break;
                        case 1:
                            $res[0]["$keyName"] = 'بلی';
                            break;
                    }
                }

                if ($iterator == 23){
                    switch (intval($res[0]["$keyName"])){
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

                if ($iterator == 22 || $iterator == 24){
                    $res[0]["$keyName"] = (strtotime($res[0]["$keyName"]) > 0 ? $ut->greg_to_jal($res[0]["$keyName"]) : '');
                }
                if ($iterator == 25){
                    $res[0]["$keyName"] = '<button class="btn btn-info" onclick="downloadCheckCarcassFileDeletedComment('.$res[0]["$keyName"].')"><i class="fas fa-download"></i></button>';
                }
                if ($iterator == 27){
                    $direction = 'dir="ltr"';
                }

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;" '.$direction.'>'.$res[0]["$keyName"].'</td>';
                $htm .= '</tr>';
                next($res[0]);
            }
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function attachmentFileDeletedCommentHtm($pid,$show){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `fileName`,`fileInfo`,`createDate`,`createTime`,`fname`,`lname` FROM `payment_attachment` LEFT JOIN `users` ON (`payment_attachment`.`uid`=`users`.`RowID`) WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $sql1 = "SELECT `fileName` FROM `payment_receipt` INNER JOIN `deposit` ON (`payment_receipt`.`did`=`deposit`.`RowID`) WHERE `pid`={$pid}";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);

        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileDeletedComment-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">ثبت کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زمان</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 40%;">نام فایل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);
            $link = ADDR.'attachment/'.$res[$i]['fileName'];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createDate'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';

        if ($show == 1) {
            $htm .= '<br><br><br>';
            $iterator = 0;
            $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileDeletedComment1-tableID">';
            $htm .= '<thead>';
            $htm .= '<tr class="bg-info">';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">شماره فایل</td>';
            $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">دانلود رسید پرداخت</td>';
            $htm .= '</tr>';
            $htm .= '</thead>';
            $htm .= '<tbody>';
            for ($i = 0; $i < $cnt1; $i++) {
                $iterator++;
                $fName = 'رسید شماره ' . $iterator;
                $link = ADDR . 'paymentReceipt/' . $res1[$i]['fileName'];
                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $iterator . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">' . $fName . '</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="' . $link . '" target="_blank"><i class="fas fa-download"></i></a></td>';
                $htm .= '</tr>';
            }
            $htm .= '</tbody>';
            $htm .= '</table>';
        }
        return $htm;
    }

    public function deletedCommentWorkflowHtm($pid){
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `payment_workflow`.*,`fname`,`lname` FROM `payment_workflow` INNER JOIN `users` ON (`payment_workflow`.`sender`=`users`.`RowID`) WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="attachmentFileComment4-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">فرستنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">گیرنده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">وضعیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ ارسال</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">زمان ارسال</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $query = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$i]['receiver']}";
            $rst = $db->ArrayQuery($query);
            $status = ($res[$i]['status'] == 0 ? 'عدم تایید' : 'تایید');
            $res[$i]['createDate'] = $ut->greg_to_jal($res[$i]['createDate']);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$rst[0]['fname'].' '.$rst[0]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$status.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createDate'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getPrintDeletedCommentHtm($cid){
        $acm = new acm();
        if(!$acm->hasAccess('deletedPayCommentReport')){
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

        $query = "SELECT `unitName` FROM  `relatedunits` WHERE `RowID`={$res[0]['consumerUnit']}";
        $rst = $db->ArrayQuery($query);

        if (intval($res[0]['layer2']) > 0) {
            $query1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]['layer2']}";
            $rst1 = $db->ArrayQuery($query1);
            $twoLayer = $rst1[0]['layerName'];
        }else{
            $twoLayer = '&emsp;&emsp;&emsp;&emsp;';
        }
        if (intval($res[0]['layer3']) > 0) {
            $query2 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]['layer3']}";
            $rst2 = $db->ArrayQuery($query2);
            $threeLayer = $rst2[0]['layerName'];
        }else{
            $threeLayer = '&emsp;&emsp;&emsp;&emsp;';
        }

        $sqlsig = "SELECT `sender`,`createDate`,`fname`,`lname`,`postJob`,`signature` FROM `payment_workflow` INNER JOIN `users` ON  (`payment_workflow`.`sender`=`users`.`RowID`) WHERE `status`=1 AND `pid`={$cid} ORDER BY `payment_workflow`.`RowID` ASC";
        $rsig = $db->ArrayQuery($sqlsig);
        $cnt = count($rsig);

        $beginner = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$res[0]['signature'].'">';
        $fbeginner = $res[0]['postJob'].' '.$ut->greg_to_jal($res[0]['cDate']).' '.$res[0]['fname'].' '.$res[0]['lname'];
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

        for ($i=0;$i<$cnt;$i++) {
            if ($rsig[$i]['sender'] == 3 || $rsig[$i]['sender'] == 14 || $rsig[$i]['sender'] == 67 || $rsig[$i]['sender'] == 68){  // حقیقت و مصطفوی خطیبیان و پورحسین
                $Sarparast = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fSarparast = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 20){  // معاونت بازرگانی
                $Moavenat = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fMoavenat = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 44 || $rsig[$i]['sender'] == 42){  // کارشناس حسابداری
                $Hesabdar = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fHesabdar = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 75 || $rsig[$i]['sender'] == 39){  // رئیس حسابداری
                $RHesabdar = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fRHesabdar = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 27  || $rsig[$i]['sender'] == 72){  // مدیر مالی
                $MHesabdar = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fMHesabdar = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
            if ($rsig[$i]['sender'] == 4){  // مدیر عامل
                $Modiriat = '<img width="120px;" height="120px;" src="'.ADDR.'Signature/'.$rsig[$i]['signature'].'">';
                $fModiriat = $rsig[$i]['postJob'].' '.$ut->greg_to_jal($rsig[$i]['createDate']).' '.$rsig[$i]['fname'].' '.$rsig[$i]['lname'];
            }
        }


        if (!($res[0]['accNumber'] === '0') || !($res[0]['accNumber'] === '')){
            $accountNumber = explode('-',$res[0]['accNumber']);
            $accountNumber = array_reverse($accountNumber);
            $accountNumber = implode('-',$accountNumber);
            $banks = $res[0]['accBank'];
        }

        $datetostring = substr($ut->greg_to_jal(str_replace('/','-',$res[0]['cDate'])),2);
        $datetostring = explode('/',$datetostring);
        $m = (intval($datetostring[1]) <= 9 ? '0'.$datetostring[1] : $datetostring[1]);
        $d = (intval($datetostring[2]) <= 9 ? '0'.$datetostring[2] : $datetostring[2]);
        $datetostring = [0=>$datetostring[0] , 1=>$m , 2=>$d];
        $datetostring = implode('/',$datetostring);
        $personalCode = [0=>$datetostring,1=>$res[0]['uid']];
        $personalCode = implode('-',$personalCode);

        switch ($res[0]['Transactions']){
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

        if (intval($res[0]['RequestSource']) > 0){
            switch ($res[0]['RequestSource']){
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
        }else{
            $SN = 'شماره درخواست : ';
            $RequestSN = $res[0]['RequestNumbers'];
        }

        if (intval($res[0]['sendType']) == 2){  // سهامی بود
            if (intval($res[0]['CashSection']) > 0 && intval($res[0]['NonCashSection']) > 0){
                $naghd = 'مبلغ نقد : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['CashSection']).'</span> ریال به تاریخ : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCash']).'</span>';
                $checki = 'مبلغ چک : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['NonCashSection']).'</span> ریال به تاریخ (میانگین) : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCheck']).'</span>';
                $mablagh = $naghd.' '.$checki;
                $shaba = 'شماره شبا : <span style="font-size: 25px;font-family: BTitr;">'.$accountNumber.'</span>';
                $AccAndBank = 'نام بانک و صاحب حساب : <span style="font-size: 25px;font-family: BTitr;">'.$banks.'</span>';
            }elseif (intval($res[0]['CashSection']) > 0){
                $mablagh = 'مبلغ نقد : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['CashSection']).'</span> ریال به تاریخ : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCash']).'</span>';
                if ($res[0]['type'] != 'پرداخت قبض' && $res[0]['type'] != 'پرداخت جریمه') {
                    $shaba = 'شماره شبا : <span style="font-size: 25px;font-family: BTitr;">'.$accountNumber.'</span>';
                    $AccAndBank = 'نام بانک و صاحب حساب : <span style="font-size: 25px;font-family: BTitr;">'.$banks.'</span>';
                }else{
                    $shaba = 'شناسه قبض : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['BillingID'].'</span>';
                    $AccAndBank = 'شناسه پرداخت : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['PaymentID'].'</span>';
                }
            }else{
                $mablagh = 'مبلغ چک : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['NonCashSection']).'</span> ریال به تاریخ (میانگین) : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCheck']).'</span>';
                $shaba = 'فاقد بخش نقدی می باشد !!!';
                $AccAndBank = '';
            }
        }elseif (intval($res[0]['sendType']) == 0){  // فورج نقدی
            $mablagh = 'مبلغ نقد : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['CashSection']).'</span> ریال به تاریخ : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCash']).'</span>';
            if ($res[0]['type'] != 'پرداخت قبض' && $res[0]['type'] != 'پرداخت جریمه') {
                $shaba = 'شماره شبا : <span style="font-size: 25px;font-family: BTitr;">'.$accountNumber.'</span>';
                $AccAndBank = 'نام بانک و صاحب حساب : <span style="font-size: 25px;font-family: BTitr;">'.$banks.'</span>';
            }else{
                $shaba = 'شناسه قبض : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['BillingID'].'</span>';
                $AccAndBank = 'شناسه پرداخت : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['PaymentID'].'</span>';
            }
        }elseif (intval($res[0]['sendType']) == 1){  // فورج چکی
            $mablagh = 'مبلغ چک : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['NonCashSection']).'</span> ریال به تاریخ (میانگین) : <span style="font-size: 25px;font-family: BTitr;">'.$ut->greg_to_jal($res[0]['paymentMaturityCheck']).'</span>';
            $shaba = 'فاقد بخش نقدی می باشد !!!';
            $AccAndBank = '';
        }else{
            $mablagh = 'مبلغ : <span style="font-size: 25px;font-family: BTitr;">'.number_format($res[0]['Amount']).'</span> ریال ';
            $shaba = 'فاقد بخش نقدی و غیر نقدی می باشد !!!';
            $AccAndBank = '';
        }

        $srcc = ADDR.'images/abrash.png';
        $htm = '';
        $htm .= '<div class="demod" style="width: 100%;margin: -85px auto;">';
        // page 1
        $htm .= '<table style="width: 100%;border: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr style="border: 2px solid #000;">';
        $htm .= '<th style="width: 25%;padding-left: 10px;text-align: center;background-color: #fff;"><img src="'.$srcc.'"></th>';
        $htm .= '<th style="width: 50%;font-size: 40px;font-family: BTitr;background-color: #ddd;text-align: center;padding: 0 20px;">اظهار نظر و درخواست<br> پرداخت وجه</th>';
        $htm .= '<th style="width: 25%;font-size: 20px;font-family: BNazanin;text-align: right;padding-right: 30px;background-color: #fff;">کد فرم : F121009<br>کد ثبت : '.$personalCode.'<br>سطح تغييرات:  2</th>';
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
        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: right;font-weight: bold;" class="pr-3">شماره یکتا : '.$res[0]['unCode'].'</td>';
        $htm .= '<td style="width: 30%;background-color: #fff;font-size: 30px;font-family: BTitr;text-align: center;">'.$res[0]['type'].'</td>';
        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: left;font-weight: bold;" class="pl-3">تاریخ : '.$ut->greg_to_jal($res[0]['cDate']).'</td>';
        $htm .= '</tr>';
        $htm .= '<tr>';
        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: right;font-weight: bold;" class="pr-3">سرگروه : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['layerName'].'</span></td>';
        $htm .= '<td style="width: 30%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: center;font-weight: bold;">زیرگروه : <span style="font-size: 25px;font-family: BTitr;">'.$twoLayer.'</span></td>';
        $htm .= '<td style="width: 35%;background-color: #fff;font-size: 30px;font-family: BNazanin;text-align: left;font-weight: bold;" class="pl-3">زیرگروه فرعی : <span style="font-size: 25px;font-family: BTitr;">'.$threeLayer.'</span></td>';
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
        $htm .= '<td style="padding-top: 5px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">فرد صادرکننده : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['fname'].' '.$res[0]['lname'].'</span></td>';
        $htm .= '<td style="padding-top: 5px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;">طبقه معاملات : <span style="font-size: 25px;font-family: BTitr;">'.$Transactions.'</span></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding-top: 3px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">واحد درخواست کننده : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['unitName'].'</span></td>';
        $htm .= '<td style="padding-top: 3px;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;">'.$SN.'<span style="font-size: 25px;font-family: BTitr;">'.$RequestSN.'</span></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 3px 0;width: 50%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">واحد مصرف کننده : <span style="font-size: 25px;font-family: BTitr;">'.$rst[0]['unitName'].'</span></td>';
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
        $htm .= '<td style="padding: 5px 0;width: 60%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">طرف مقابل : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['accName'].' - '.$res[0]['codeTafzili'].'</span></td>';
        $htm .= '<td style="padding: 5px 0;width: 40%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;">کد/شناسه ملی: <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['codeMelli'].'</span></td>';
        $htm .= '<td style="padding: 5px 0;width: 40%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" " >شماره قرارداد : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['contractNumber'].'</span></td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding-top: 3px;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">بابت : <span style="font-size: 25px;font-family: BTitr;">'.$res[0]['Toward'].'</span></td>';
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
        $htm .= '<td style="padding-top: 3px;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">'.$mablagh.'</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 3px 0;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">توضیحات : <span style="font-size: 25px;font-family: BTitr;" dir="rtl">'.$res[0]['desc'].'</span></td>';
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
        $htm .= '<td style="padding: 7px 0;width: 60%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: right;" class="pr-3">'.$shaba.'</td>';
        $htm .= '<td style="padding: 7px 0;width: 40%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: right;">'.$AccAndBank.'</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        //************************ امضا کارشناس و سرپرست و مدیر واحد *************************

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$beginner.$fbeginner.'</td>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 34%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$Sarparast.$fSarparast.'</td>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$Moavenat.$fMoavenat.'</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';

        //************************ امضا حسابداری *************************

        $htm .= '<table style="width: 100%;border-right: 2px solid #000;border-left: 2px solid #000;border-bottom: 2px solid #000;">';
        $htm .= '<thead>';
        $htm .= '<tr>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$Hesabdar.$fHesabdar.'</td>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 34%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$RHesabdar.$fRHesabdar.'</td>';
        $htm .= '<td style="padding: 10px 0 10px 0;width: 33%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: center;">'.$MHesabdar.$fMHesabdar.'</td>';
        $htm .= '</tr>';
        if (strtotime($res[0]['clearingFundDate']) > 0) {
            $htm .= '<tr>';
            $htm .= '<td colspan="3" class="pr-3" style="padding: 10px 0 20px 0;width: 100%;background-color: #fff;font-size: 25px;font-family: BNazanin;font-weight: bold;text-align: right;">قابل توجه واحد مالی : تاریخ تسویه تنخواه '.$ut->greg_to_jal($res[0]['clearingFundDate']).' می باشد</td>';
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
        $htm .= '<td class="pl-3" style="padding: 50px 0 20px 0;width: 100%;background-color: #fff;font-size: 30px;font-family: BNazanin;font-weight: bold;text-align: left;">'.$Modiriat.$fModiriat.'</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '</table>';
        $htm .= '</div>';

        return $htm;
    }

    public function downloadCheckCarcassFileDelHtm($pid){
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `check_carcass` WHERE `pid`={$pid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="downloadCheckCarcassFileDel-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">نام فایل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 45%;">لینک دانلود</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $fileName = 'رسید شماره '.$iterator;
            $link = ADDR.'checkCarcass/'.$res[$i]['fileName'];
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$fileName.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function getAttachFundToDeletedCommentList($cid){
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT `fund_list`.*,`fname`,`lname` FROM `fund_list` INNER JOIN `users` ON (`fund_list`.`uid`=`users`.`RowID`) WHERE `pid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);

        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="contractChooseList-tableID">';
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

        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $sqq = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer1']}";
            $sqq1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer2']}";
            $sqq2 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[$i]['layer3']}";
            $rst = $db->ArrayQuery($sqq);
            $rst1 = $db->ArrayQuery($sqq1);
            $rst2 = $db->ArrayQuery($sqq2);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res[$i]['cDate']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(intval($res[$i]['fundName']) == 0 ? 'تنخواه هزینه ای' : 'تنخواه مصرفی').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['unCode'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(count($rst) > 0 ? $rst[0]['layerName'] : '').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(count($rst1) > 0 ? $rst1[0]['layerName'] : '').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(count($rst2) > 0 ? $rst2[0]['layerName'] : '').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res[$i]['finalAmount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="attachedFundListDetailsDeletedComment('.$res[$i]['RowID'].')"><i class="fas fa-puzzle-piece"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function showFundListDetailsDeletedCommentHTM($fid){
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT * FROM `fund_list_details` WHERE `fid`={$fid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="showFundListDetailsDeletedCommentHTM-tableID">';
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
        for ($i=0;$i<$cnt;$i++){
            $cDate = (strtotime($res[$i]['createDate']) > 0 ? $ut->greg_to_jal($res[$i]['createDate']) : '');
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$cDate.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['reqNumber'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['placeUse'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res[$i]['fundAmount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showAttachFundListDetailsDeletedComment('.$res[$i]['RowID'].')"><i class="fas fa-link"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    //++++++++++++++++++++ اظهارنظرهای دارای مازاد پرداختی ++++++++++++++++++++

    public function getOverpaymentCommentsHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('overpaymentComments')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $pagename = "اظهارنظرهای دارای مازاد پرداختی";
        $pageIcon = "fas fa-chart-area";
        $contentId = "overpaymentCommentsBody";

        $units = $this->getUnits();
        $CountUnits = count($units);

        $banks = $this->getCompanyBanks();
        $cntb = count($banks);

        $bottons = array();
        $bottons[0]['title'] = "عودت وجه";
        $bottons[0]['jsf'] = "returnMoneyComment";
        $bottons[0]['icon'] = "fa-undo";

        $bottons[1]['title'] = "کسر از اظهارنظر";
        $bottons[1]['jsf'] = "fractionMoneyComment";
        $bottons[1]['icon'] = "fa-minus-square";

        $headerSearch = array();

        $a = 0;
        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "90px";
        $headerSearch[$a]['id'] = "overpaymentCommentsSDateSearch";
        $headerSearch[$a]['title'] = "از تاریخ";
        $headerSearch[$a]['placeholder'] = "از تاریخ";
        $a++;

        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "90px";
        $headerSearch[$a]['id'] = "overpaymentCommentsEDateSearch";
        $headerSearch[$a]['title'] = "تا تاریخ";
        $headerSearch[$a]['placeholder'] = "تا تاریخ";
        $a++;

        $headerSearch[$a]['type'] = "select";
        $headerSearch[$a]['id'] = "overpaymentCommentsUnitSearch";
        $headerSearch[$a]['title'] = "واحد درخواست کننده";
        $headerSearch[$a]['width'] = "200px";
        $headerSearch[$a]['options'] = array();
        $headerSearch[$a]['options'][0]["title"] = "واحد درخواست کننده";
        $headerSearch[$a]['options'][0]["value"] = 0;
        for ($i=0;$i<$CountUnits;$i++){
            $headerSearch[$a]['options'][$i+1]["title"] = $units[$i]['unitName'];
            $headerSearch[$a]['options'][$i+1]["value"] = $units[$i]['RowID'];
        }
        $a++;

        $headerSearch[$a]['type'] = "select";
        $headerSearch[$a]['id'] = "overpaymentCommentsConsumerUnitSearch";
        $headerSearch[$a]['title'] = "واحد مصرف کننده";
        $headerSearch[$a]['width'] = "200px";
        $headerSearch[$a]['options'] = array();
        $headerSearch[$a]['options'][0]["title"] = "واحد مصرف کننده";
        $headerSearch[$a]['options'][0]["value"] = 0;
        for ($i=0;$i<$CountUnits;$i++){
            $headerSearch[$a]['options'][$i+1]["title"] = $units[$i]['unitName'];
            $headerSearch[$a]['options'][$i+1]["value"] = $units[$i]['RowID'];
        }
        $a++;

        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "150px";
        $headerSearch[$a]['id'] = "overpaymentCommentsAccNameSearch";
        $headerSearch[$a]['title'] = "نام طرف حساب";
        $headerSearch[$a]['placeholder'] = "نام طرف حساب";
        $a++;

        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "150px";
        $headerSearch[$a]['id'] = "overpaymentCommentsTowardSearch";
        $headerSearch[$a]['title'] = "بابت";
        $headerSearch[$a]['placeholder'] = "بابت";
        $a++;

        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "150px";
        $headerSearch[$a]['id'] = "overpaymentCommentsUncodeSearch";
        $headerSearch[$a]['title'] = "کد یکتا";
        $headerSearch[$a]['placeholder'] = "کد یکتا";
        $a++;

        $headerSearch[$a]['type'] = "text";
        $headerSearch[$a]['width'] = "150px";
        $headerSearch[$a]['id'] = "overpaymentCommentsAmountSearch";
        $headerSearch[$a]['onkeyup'] = "onkeyup=addSeprator()";
        $headerSearch[$a]['title'] = "مبلغ";
        $headerSearch[$a]['placeholder'] = "مبلغ";
        $a++;

        $headerSearch[$a]['type'] = "btn";
        $headerSearch[$a]['title'] = "جستجو&nbsp;&nbsp;<i class='fa fa-search'></i>";
        $headerSearch[$a]['jsf'] = "showOverpaymentCommentsList";

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        //++++++++++++++++++++++++++++++++++ payComment Search MODAL ++++++++++++++++++++++++++++++++
        //++++++++++++++++++ Start Comment Info Modal ++++++++++++++++++++++
        $modalID = "overpaymentCommentsInfoModal";
        $modalTitle = "سایر اطلاعات";
        $ShowDescription = 'overpaymentCommentsInfo-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showCommentInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End Comment Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ download CheckCarcass File Modal ++++++++++++++++++++++
        $modalID = "downloadCheckCarcassFileOverpaymentModal";
        $modalTitle = "دانلود رسید لاشه چک";
        $ShowDescription = 'downloadCheckCarcassFileOverpayment-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $downloadCheckCarcassFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End download CheckCarcass File Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Deposits List Info Modal ++++++++++++++++++++++
        $modalID = "depositsOverpaymentListInfoModal";
        $modalTitle = "واریزی های انجام شده";
        $style = 'style="max-width: 900px;"';
        $ShowDescription = 'depositsOverpayment-manage-Info-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showDepositsInfo = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Deposits List Info Modal ++++++++++++++++++++++++
        //++++++++++++++++++ Start Comment Checks Modal ++++++++++++++++++++++
        $modalID = "commentOverpaymentChecksModal";
        $modalTitle = "چک/چک ها";
        $style = 'style="max-width: 700px;"';
        $ShowDescription = 'comment-Overpayment-Checks-body';
        $items = array();
        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showCommentChecks = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End Comment Checks Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ payComment Search MODAL ++++++++++++++++++++++++++++++++
        $modalID = "returnMoneyCommentModal";
        $modalTitle = "فرم عودت وجه";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "select";
        $items[$c]['id'] = "returnMoneyCommentType";
        $items[$c]['title'] = "نحوه عودت وجه";
        $items[$c]['onchange'] = "onchange=getReturnMoneyCommentType()";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "انتخاب کنید";
        $items[$c]['options'][0]["value"] = -1;
        $items[$c]['options'][1]["title"] = "مشتریان";
        $items[$c]['options'][1]["value"] = 0;
        $items[$c]['options'][2]["title"] = "بانک های شرکت";
        $items[$c]['options'][2]["value"] = 1;
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "returnMoneyCommentAccName";
        $items[$c]['title'] = "نام مشتری";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "returnMoneyCommentBanks";
        $items[$c]['title'] = "بانک های شرکت";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = "--------";
        $items[$c]['options'][0]["value"] = -1;
        for ($i=0;$i<$cntb;$i++){
            $items[$c]['options'][$i+1]["title"] = $banks[$i]['Name'];
            $items[$c]['options'][$i+1]["value"] = $banks[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "returnMoneyCommentAmount";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['title'] = "مبلغ عودت وجه";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "returnMoneyCommentHiddenRTid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doReturnMoneyComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $returnMoneyCommentModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END OF payComment Search MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ payComment Search MODAL ++++++++++++++++++++++++++++++++
        $modalID = "fractionMoneyCommentModal";
        $modalTitle = "فرم کسر از اظهارنظر";
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "fractionMoneyCommentUnCode";
        $items[$c]['title'] = "کد یکتا";
        $items[$c]['placeholder'] = "کد";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "fractionMoneyCommentAmount";
        $items[$c]['onkeyup'] = "onkeyup=addSeprator()";
        $items[$c]['title'] = "مبلغ کسر از اظهارنظر";
        $items[$c]['placeholder'] = "ریال";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "fractionMoneyCommentHiddenFRid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doFractionMoneyComment";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $fractionMoneyCommentModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons);
        //++++++++++++++++++++++++++++++ END OF payComment Search MODAL +++++++++++++++++++++++++++++++++++++
        $htm .= $showCommentInfo;
        $htm .= $downloadCheckCarcassFile;
        $htm .= $showDepositsInfo;
        $htm .= $showCommentChecks;
        $htm .= $returnMoneyCommentModal;
        $htm .= $fractionMoneyCommentModal;
        return $htm;
    }

    public function getOverpaymentCommentsList($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('overpaymentComments')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();

        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`pay_comment`.`cDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$ceDate.'" ';
        }
        if(intval($cUnit) > 0){
            $w[] = '`Unit`='.$cUnit.' ';
        }
        if(intval($coUnit) > 0){
            $w[] = '`consumerUnit`='.$coUnit.' ';
        }
        if(strlen(trim($caName)) > 0){
            $w[] = '`accName` LIKE "%'.$caName.'%" ';
        }
        if(strlen(trim($cToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$cToward.'%" ';
        }
        if(strlen(trim($Uncode)) > 0){
            $w[] = '`unCode`='.$Uncode.' ';
        }
        if(strlen(trim($amount)) > 0){
            $w[] = '`Amount`='.$amount.' ';
        }
        $w[] = '`isEnable`=1 ';
        $w[] = '`paymentStatus`=3 ';

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`consumerUnit`,`Toward`,`Amount`,`unitName`,`sendType`,`sabtDarHesabType`,`priorityLevel`    
                FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $sql .= " ORDER BY `pcid` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);

        $ut->fileRecorder($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $sqlUName = "SELECT `unitName` , RowID FROM `relatedunits` WHERE `RowID`={$res[$y]['consumerUnit']}";
            $result = $db->ArrayQuery($sqlUName);
            switch ($res[$y]['sendType']){
                case 0:
                    $typeComment = 'فورج نقدی';
                    $finalRes[$y]['bgColor'] = 'table-warning';
                    break;
                case 1:
                    $typeComment = 'فورج چک';
                    $finalRes[$y]['bgColor'] = 'table-orange';
                    break;
                case 2:
                    $typeComment = 'سهامی';
                    $finalRes[$y]['bgColor'] = 'table-primary';
                    break;
                case 3:
                    $typeComment = ($res[$y]['sabtDarHesabType'] == 0 ? 'ثبت در حساب بستانکاری فورج' : 'ثبت در حساب بستانکاری سهامی');
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
            }
            $finalRes[$y]['RowID'] = $res[$y]['pcid'];
            $finalRes[$y]['typeComment'] = $typeComment;
            $finalRes[$y]['cDate'] = $ut->greg_to_jal($res[$y]['cDate']);
            $finalRes[$y]['Unit'] = $res[$y]['unitName'];
            $finalRes[$y]['consumerUnit'] = $result[0]['unitName'];
            $finalRes[$y]['accName'] = (strlen(trim($res[$y]['accName'])) == 0 ? '--------' : $res[$y]['accName']);
            $finalRes[$y]['Toward'] = $res[$y]['Toward'];
            $finalRes[$y]['blinker'] = ($res[$y]['priorityLevel'] == 1 ? 'blink' : '');
            $finalRes[$y]['Amount'] = number_format($res[$y]['Amount']).' ریال';
        }
        return $finalRes;
    }

    public function getOverpaymentCommentsListCountRows($csDate,$ceDate,$cUnit,$coUnit,$caName,$cToward,$Uncode,$amount){
        $ut = new Utility();
        $db = new DBi();

        $w = array();
        if(strlen(trim($csDate)) > 0){
            $csDate = $ut->jal_to_greg($csDate);
            $w[] = '`pay_comment`.`cDate` >="'.$csDate.'" ';
        }
        if(strlen(trim($ceDate)) > 0){
            $ceDate = $ut->jal_to_greg($ceDate);
            $w[] = '`pay_comment`.`cDate` <="'.$ceDate.'" ';
        }
        if(intval($cUnit) > 0){
            $w[] = '`Unit`='.$cUnit.' ';
        }
        if(intval($coUnit) > 0){
            $w[] = '`consumerUnit`='.$coUnit.' ';
        }
        if(strlen(trim($caName)) > 0){
            $w[] = '`accName` LIKE "%'.$caName.'%" ';
        }
        if(strlen(trim($cToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$cToward.'%" ';
        }
        if(strlen(trim($Uncode)) > 0){
            $w[] = '`unCode`='.$Uncode.' ';
        }
        if(strlen(trim($amount)) > 0){
            $w[] = '`Amount`='.$amount.' ';
        }
        $w[] = '`isEnable`=1 ';
        $w[] = '`paymentStatus`=3 ';

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`accName`,`cDate`,`Unit`,`consumerUnit`,`Toward`,`Amount`,`unitName`,`sendType`,`sabtDarHesabType`   
                FROM `pay_comment` INNER JOIN `relatedunits` ON (`pay_comment`.`Unit`=`relatedunits`.`RowID`)";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function otherInfoOverpaymentCommentsHTM($cid){
        $acm = new acm();
        if(!$acm->hasAccess('overpaymentComments')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `unCode`,`layer1`,`layer2`,`layer3`,`type`,`CashSection`,`paymentMaturityCash`,`BillingID`,`PaymentID`,`Transactions`,`RequestSource`,`RequestNumbers`,`desc` FROM `pay_comment` WHERE `RowID`={$cid}";
        $res = $db->ArrayQuery($sql);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="OtherInfoOverpaymentComments-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 10%;">ردیف</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">سایر اطلاعات</td>';
        $htm .= '<td style="text-align: center;font-family: dubai-Bold;width: 45%;">مقدار</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        if ($res[0]['type'] == 'پرداخت قبض' || $res[0]['type'] == 'پرداخت جریمه'){
            $infoNames = array('شماره یکتا','سر گروه','زیر گروه','زیرگروه فرعی','نوع','بخش نقدی','سررسید پرداخت','شناسه قبض','شناسه پرداخت','طبقه معاملات','منبع درخواست','شماره درخواست','توضیحات');
            for ($i=0;$i<13;$i++){
                $iterator++;
                $keyName = key($res[0]);
                if ($iterator == 2 || $iterator == 3 || $iterator == 4){
                    $sql1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]["$keyName"]}";
                    $rst = $db->ArrayQuery($sql1);
                    $res[0]["$keyName"] = $rst[0]['layerName'];
                }
                if ($iterator == 6){
                    $res[0]["$keyName"] = number_format($res[0]["$keyName"]).' ریال';
                }
                if ($iterator == 7){
                    $res[0]["$keyName"] = $ut->greg_to_jal($res[0]["$keyName"]);
                }
                if ($iterator == 10){
                    switch ($res[0]["$keyName"]){
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
                if ($iterator == 11){
                    switch ($res[0]["$keyName"]){
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
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[0]["$keyName"].'</td>';
                $htm .= '</tr>';
                next($res[0]);
            }
        }else{
            $sql = "SELECT `unCode`,`layer1`,`layer2`,`layer3`,`clearingFundDate`,`type`,`CashSection`,`paymentMaturityCash`,`NonCashSection`,`paymentMaturityCheck`,`Transactions`,`accName`,`accNumber`,`accBank`,`codeTafzili`,`nationalCode`,`RequestSource`,`RequestNumbers`,`contractNumber`,`tick`,`checkNumber`,`checkDate`,`checkCarcass`,`checkDeliveryDate`,`RowID`,`desc`,`cardNumber` FROM `pay_comment` WHERE `pay_comment`.`RowID`={$cid}";
            $res = $db->ArrayQuery($sql);
            $infoNames = array('شماره یکتا','سر گروه','زیر گروه','زیرگروه فرعی','تاریخ تسویه تنخواه','نوع','بخش نقدی','سررسید پرداخت نقدی','بخش چک','سررسید پرداخت چک','طبقه معاملات','نام طرف حساب','شماره حساب','نام بانک و شعبه','کد تفضیلی','کد ملی','منبع درخواست','شماره درخواست','شماره قرارداد','پرینت گرفته و مستندات پیوست شده است','شماره چک','تاریخ چک','لاشه چک تحویل واحد مالی','تعهد تاریخ تحویل لاشه چک به واحد مالی','رسید تحویل چک','توضیحات','شماره کارت');
            $direction = '';
            for ($i=0;$i<27;$i++){
                $iterator++;
                $keyName = key($res[0]);
                if ($iterator == 2 || $iterator == 3 || $iterator == 4){
                    $sql1 = "SELECT `layerName` FROM `layers` WHERE `RowID`={$res[0]["$keyName"]}";
                    $rst = $db->ArrayQuery($sql1);
                    $res[0]["$keyName"] = $rst[0]['layerName'];
                }
                if ($iterator == 5){
                    if (strtotime($res[0]["$keyName"]) > 0){
                        $res[0]["$keyName"] = $ut->greg_to_jal($res[0]["$keyName"]);
                    }else{
                        next($res[0]);
                        continue;
                    }
                }
                if ($iterator == 7 || $iterator == 9){
                    $res[0]["$keyName"] = number_format($res[0]["$keyName"]).' ریال';
                }
                if ($iterator == 8 || $iterator == 10){
                    $res[0]["$keyName"] = (strtotime($res[0]["$keyName"]) > 0 ? $ut->greg_to_jal($res[0]["$keyName"]) : '');
                }
                if ($iterator == 11){
                    switch ($res[0]["$keyName"]){
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
                if ($iterator == 17){
                    switch ($res[0]["$keyName"]){
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

                if ($iterator == 20){
                    switch ($res[0]["$keyName"]){
                        case 0:
                            $res[0]["$keyName"] = 'خیر';
                            break;
                        case 1:
                            $res[0]["$keyName"] = 'بلی';
                            break;
                    }
                }

                if ($iterator == 23){
                    switch (intval($res[0]["$keyName"])){
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

                if ($iterator == 22 || $iterator == 24){
                    $res[0]["$keyName"] = (strtotime($res[0]["$keyName"]) > 0 ? $ut->greg_to_jal($res[0]["$keyName"]) : '');
                }
                if ($iterator == 25){
                    $res[0]["$keyName"] = '<button class="btn btn-info" onclick="downloadCheckCarcassFileOverpayment('.$res[0]["$keyName"].')"><i class="fas fa-download"></i></button>';
                }
                if ($iterator == 27){
                    $direction = 'dir="ltr"';
                }

                $htm .= '<tr class="table-secondary">';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$infoNames[$i].'</td>';
                $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;" '.$direction.'>'.$res[0]["$keyName"].'</td>';
                $htm .= '</tr>';
                next($res[0]);
            }
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function commentOverpaymentChecksHTM($cid){
        $ut = new Utility();
        $db = new DBi();
        $sql = "SELECT `check_amount`,`check_date`,`check_number`,`checkType`,`description`,`fname`,`lname` FROM `bank_check` 
                INNER JOIN `users` ON (`bank_check`.`uid`=`users`.`RowID`) WHERE `cid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="commentOverpaymentChecksHTM-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="color: #ffc107;text-align: center;font-family: dubai-Bold;width: 10%;">نوع چک</td>';
        $htm .= '<td style="color: #ffc107;text-align: center;font-family: dubai-Bold;width: 20%;">کاربر ثبت کننده</td>';
        $htm .= '<td style="color: #ffc107;text-align: center;font-family: dubai-Bold;width: 20%;">مبلغ چک</td>';
        $htm .= '<td style="color: #ffc107;text-align: center;font-family: dubai-Bold;width: 15%;">شماره چک</td>';
        $htm .= '<td style="color: #ffc107;text-align: center;font-family: dubai-Bold;width: 15%;">تاریخ چک</td>';
        $htm .= '<td style="color: #ffc107;text-align: center;font-family: dubai-Bold;width: 20%;">توضیحات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        $amount = 0;
        for ($i=0;$i<$cnt;$i++){
            $amount += $res[$i]['check_amount'];
            $chDate = (strtotime($res[$i]['check_date']) > 0 ? $ut->greg_to_jal($res[$i]['check_date']) : '');
            if (is_null($res[$i]['checkType'])){
                $ctype = '';
            }elseif ($res[$i]['checkType'] == 0){
                $ctype = 'ابرش';
            }else{
                $ctype = 'مشتری';
            }
            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ctype.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fname'].' '.$res[$i]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.number_format($res[$i]['check_amount']).' ریال</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['check_number'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$chDate.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '</tr>';
        }

        $sql1 = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$cid}";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);
        $deposits = 0;
        for ($j=0;$j<$cnt1;$j++){
            $deposits += $res1[$j]['dAmount'];
        }

        $sql2 = "SELECT `rAmount`,`type`,`accName`,`bankID` FROM `return_money` WHERE `pid`={$cid}";
        $rst = $db->ArrayQuery($sql2);
        $cnt2 = count($rst);
        $rAmount = 0;
        for ($x=0;$x<$cnt2;$x++){
            $rAmount += $rst[$x]['rAmount'];
        }

        $sql3 = "SELECT `fAmount`,`unCode` FROM `fraction_money` WHERE `pid`={$cid}";
        $rstf = $db->ArrayQuery($sql3);
        $cnt3 = count($rstf);
        $fAmount = 0;
        for ($x=0;$x<$cnt3;$x++){
            $fAmount += $rstf[$x]['fAmount'];
        }

        $sql4 = "SELECT `fAmount`,`pid` FROM `fraction_money` WHERE `cid`={$cid}";
        $rstx = $db->ArrayQuery($sql4);
        $cnt4 = count($rstx);
        $fxAmount = 0;
        for ($x=0;$x<$cnt4;$x++){
            $fxAmount += $rstx[$x]['fAmount'];
        }

        $query = "SELECT `CashSection`,`NonCashSection`,`Amount`,`sendType` FROM `pay_comment` WHERE `RowID`={$cid}";
        $result = $db->ArrayQuery($query);
        $remainingNaghdi = (intval($result[0]['CashSection']) > 0 ? $result[0]['CashSection'] - $deposits : 0);
        $remainingCheck = (intval($result[0]['NonCashSection']) > 0 ? $result[0]['NonCashSection'] - $amount : 0);
        $remainingFinal = ($result[0]['Amount'] + $rAmount + $fAmount) - ($amount + $deposits + $fxAmount);
        $colorNaghdi = (intval($remainingNaghdi) < 0 ? 'color: #db0000;' : '');
        $colorCheck = (intval($remainingCheck) < 0 ? 'color: #db0000;' : '');
        $colorFinal = (intval($remainingFinal) < 0 ? 'color: #db0000;' : '');
        $htm .= '<tr class="table-info">';
        $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">جمع پرداختی چک : '.number_format($amount).' ریال</td>';
        $htm .= '<td colspan="3" style="'.$colorCheck.'text-align: center;font-family: dubai-bold;padding: 10px;">مانده چک : <span  dir="ltr">'.number_format($remainingCheck).'</span> ریال</td>';
        $htm .= '</tr>';
        $htm .= '<tr class="table-info">';
        $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">جمع پرداختی نقدی : '.number_format($deposits).' ریال</td>';
        $htm .= '<td colspan="3" style="'.$colorNaghdi.'text-align: center;font-family: dubai-bold;padding: 10px;">مانده نقدی : <span dir="ltr">'.number_format($remainingNaghdi).'</span> ریال</td>';
        $htm .= '</tr>';
        for ($x=0;$x<$cnt2;$x++) {
            $sqlbank = "SELECT `Name` FROM `company_banks` WHERE `RowID`={$rst[$x]['bankID']}";
            $rstb = $db->ArrayQuery($sqlbank);
            $person = ($rst[$x]['type'] == 0 ? $rst[$x]['accName'] : $rstb[0]['Name']);
            $htm .= '<tr class="table-info">';
            $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">مبلغ عودت وجه : ' . number_format($rst[$x]['rAmount']) . ' ریال</td>';
            $htm .= '<td colspan="1"style="text-align: center;font-family: dubai-bold;padding: 10px;">' . ($rst[$x]['type'] == 0 ? 'مشتریان' : 'واریز به بانک') . '</td>';
            $htm .= '<td colspan="2"style="text-align: center;font-family: dubai-bold;padding: 10px;">' . $person . '</td>';
            $htm .= '</tr>';
        }
        for ($x=0;$x<$cnt3;$x++){
            $htm .= '<tr class="table-info">';
            $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">مبلغ کسر از اظهارنظر : ' . number_format($rstf[$x]['fAmount']) . ' ریال</td>';
            $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">کد یکتا اظهارنظر دارای کسری : ' . $rstf[$x]['unCode'] . '</td>';
            $htm .= '</tr>';
        }
        for ($x=0;$x<$cnt4;$x++){
            $sqlCode = "SELECT `unCode` FROM `pay_comment` WHERE `RowID`={$rstx[$x]['pid']}";
            $ress = $db->ArrayQuery($sqlCode);
            $htm .= '<tr class="table-info">';
            $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">مبلغ کسر شده از اظهارنظر : ' . number_format($rstx[$x]['fAmount']) . ' ریال</td>';
            $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">کد یکتا اظهارنظر دارای مازاد : ' . $ress[0]['unCode'] . '</td>';
            $htm .= '</tr>';
        }
        $htm .= '<tr class="table-info">';
        $htm .= '<td colspan="3"style="text-align: center;font-family: dubai-bold;padding: 10px;">مبلغ کل اظهارنظر : '.number_format($result[0]['Amount']).' ریال</td>';
        $htm .= '<td colspan="3" style="'.$colorFinal.'text-align: center;font-family: dubai-bold;padding: 10px;">مانده نهایی (چک + نقدی) : <span  dir="ltr">'.number_format($remainingFinal).'</span> ریال</td>';
        $htm .= '</tr>';
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function createReturnMoneyComment($rtid,$type,$accName,$bank,$amount){
        $acm = new acm();
        if(!$acm->hasAccess('overpaymentComments')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $query = "SELECT `accountID`,`Amount` FROM `pay_comment` WHERE `RowID`={$rtid}";
        $rst = $db->ArrayQuery($query);

        $sql1 = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$rtid}";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);
        $checks = 0;
        for ($j=0;$j<$cnt1;$j++){
            $checks += $res1[$j]['check_amount'];
        }

        $sql2 = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$rtid}";
        $res2 = $db->ArrayQuery($sql2);
        $cnt2 = count($res2);
        $dAmount = 0;
        for ($j=0;$j<$cnt2;$j++){
            $dAmount += $res2[$j]['dAmount'];
        }

        $sql3 = "SELECT `rAmount` FROM `return_money` WHERE `pid`={$rtid}";
        $res3 = $db->ArrayQuery($sql3);
        $cnt3 = count($res3);
        $rAmount = 0;
        for ($x=0;$x<$cnt3;$x++){
            $rAmount += $res3[$x]['rAmount'];
        }

        $sql4 = "SELECT `fAmount` FROM `fraction_money` WHERE `pid`={$rtid}";
        $res4 = $db->ArrayQuery($sql4);
        $cnt4 = count($res4);
        $fAmount = 0;
        for ($x=0;$x<$cnt4;$x++){
            $fAmount += $res4[$x]['fAmount'];
        }

        if (($checks + $dAmount) < ($rst[0]['Amount'] + $rAmount + $fAmount + $amount)){
            $res = "مبلغ عودت وجه معتبر نمی باشد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "INSERT INTO `return_money` (`pid`,`type`,`accID`,`accName`,`bankID`,`rAmount`) VALUES ({$rtid},{$type},{$rst[0]['accountID']},'{$accName}',{$bank},{$amount})";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function createFractionMoneyComment($frid,$code,$amount){
        $acm = new acm();
        if(!$acm->hasAccess('overpaymentComments')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $query = "SELECT `accountID`,`Amount` FROM `pay_comment` WHERE `RowID`={$frid}";
        $rst = $db->ArrayQuery($query);

        $query1 = "SELECT `RowID`,`accountID`,`Amount` FROM `pay_comment` WHERE `unCode`='{$code}'";
        $rst1 = $db->ArrayQuery($query1);

        if ($rst[0]['accountID'] !== $rst1[0]['accountID']){
            $res = "طرف حساب اظهارنظرها، همسان نیستند !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sqlc = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$rst1[0]['RowID']}";
        $resc = $db->ArrayQuery($sqlc);
        $cntc = count($resc);
        $checks1 = 0;
        for ($j=0;$j<$cntc;$j++){
            $checks1 += $resc[$j]['check_amount'];
        }

        $sqlb = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$rst1[0]['RowID']}";
        $resb = $db->ArrayQuery($sqlb);
        $cntb = count($resb);
        $dAmount1 = 0;
        for ($j=0;$j<$cntb;$j++){
            $dAmount1 += $resb[$j]['dAmount'];
        }
        if ($rst1[0]['Amount'] < ($checks1 + $dAmount1 + $amount)){
            $res = "مبلغ کسر از اظهارنظر معتبر نمی باشد  !";
            $out = "false";
            response($res,$out);
            exit;
        }


        $sql1 = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$frid}";
        $res1 = $db->ArrayQuery($sql1);
        $cnt1 = count($res1);
        $checks = 0;
        for ($j=0;$j<$cnt1;$j++){
            $checks += $res1[$j]['check_amount'];
        }

        $sql2 = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$frid}";
        $res2 = $db->ArrayQuery($sql2);
        $cnt2 = count($res2);
        $dAmount = 0;
        for ($j=0;$j<$cnt2;$j++){
            $dAmount += $res2[$j]['dAmount'];
        }

        $sql3 = "SELECT `rAmount` FROM `return_money` WHERE `pid`={$frid}";
        $res3 = $db->ArrayQuery($sql3);
        $cnt3 = count($res3);
        $rAmount = 0;
        for ($x=0;$x<$cnt3;$x++){
            $rAmount += $res3[$x]['rAmount'];
        }

        $sql4 = "SELECT `fAmount` FROM `fraction_money` WHERE `pid`={$frid}";
        $res4 = $db->ArrayQuery($sql4);
        $cnt4 = count($res4);
        $fAmount = 0;
        for ($x=0;$x<$cnt4;$x++){
            $fAmount += $res4[$x]['fAmount'];
        }

        if (($checks + $dAmount) < ($rst[0]['Amount'] + $rAmount + $fAmount + $amount)){
            $res = "مبلغ کسر از اظهارنظر معتبر نمی باشد !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $sql = "INSERT INTO `fraction_money` (`pid`,`cid`,`unCode`,`fAmount`,`accID`) VALUES ({$frid},{$rst1[0]['RowID']},'{$code}',{$amount},{$rst[0]['accountID']})";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    //++++++++++++++++++++ تاییدیه مالی ++++++++++++++++++++

    public function getFinancialConfirmationList($fcsDate,$fceDate,$fcaName,$fcToward,$fcAmount,$page=1){
        $acm = new acm();
        if(!$acm->hasAccess('financialConfirmation')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;
        $w = array();
        if(strlen(trim($fcsDate)) > 0){
            $fcsDate = $ut->jal_to_greg($fcsDate);
            $w[] = '`pay_comment`.`receiverDate` >="'.$fcsDate.'" ';
        }
        if(strlen(trim($fceDate)) > 0){
            $fceDate = $ut->jal_to_greg($fceDate);
            $w[] = '`pay_comment`.`receiverDate` <="'.$fceDate.'" ';
        }
        if(strlen(trim($fcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$fcaName.'%" ';
        }
        if(strlen(trim($fcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$fcToward.'%" ';
        }
        if(strlen(trim($fcAmount)) > 0){
            $w[] = '`Amount`='.$fcAmount.' ';
        }
        $w[] = '`transfer`=2 ';  // تاییدیه مالی
        $w[] = '`multipleComment`=0 ';  // چند مرحله ای

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`Amount`,`CashSection`,`NonCashSection`,`Toward`,`accName`,`sendType`,`priorityLevel` FROM `pay_comment` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
     
        $sql .= " ORDER BY `accName` ASC,  `paymentMaturityCash` ASC ,`paymentMaturityCheck`  ASC LIMIT $start,".$numRows;
        $ut->fileRecorder($col_name ."**" .$sql);
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $totalAmount = 0;
            $query = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$res[$y]['pcid']}";
            $rst = $db->ArrayQuery($query);
            if (count($rst) > 0) {
                $cnt = count($rst);
                for ($i=0;$i<$cnt;$i++) {
                    $totalAmount += $rst[$i]['dAmount'];
                }
            }

            $sqlCheck = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$res[$y]['pcid']}";
            $result = $db->ArrayQuery($sqlCheck);
            if (count($result) > 0) {
                $cnt = count($result);
                for ($i=0;$i<$cnt;$i++) {
                    $totalAmount += $result[$i]['check_amount'];
                }
            }

            $rAmount = 0;
            $sqqRTN = "SELECT `rAmount` FROM `return_money` WHERE `pid`={$res[$y]['pcid']}";
            $rstn = $db->ArrayQuery($sqqRTN);
            $cntq = count($rstn);
            for ($i=0;$i<$cntq;$i++){
                $rAmount += $rstn[$i]['rAmount'];
            }

            $fAmount = 0;
            $sqqRFN = "SELECT `fAmount` FROM `fraction_money` WHERE `cid`={$res[$y]['pcid']}";
            $rstf = $db->ArrayQuery($sqqRFN);
            $cntq = count($rstf);
            for ($i=0;$i<$cntq;$i++){
                $fAmount += $rstf[$i]['fAmount'];
            }

            $leftOver = ($res[$y]['Amount'] + $rAmount) - ($totalAmount + $fAmount);
            $finalRes[$y]['txtco'] = (intval($leftOver) < 0 ? 'red' : 'green');
            $finalRes[$y]['leftOver'] = (intval($leftOver) < 0 ? number_format(abs($leftOver)).' ریال مازاد' : number_format($leftOver).' ریال');


            switch ($res[$y]['sendType']){
                case 0:
                    $finalRes[$y]['bgColor'] = 'table-warning';
                    break;
                case 1:
                    $finalRes[$y]['bgColor'] = 'table-orange';
                    break;
                case 2:
                    $finalRes[$y]['bgColor'] = 'table-primary';
                    break;
                case 3:
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
            }
            $finalRes[$y]['RowID'] = $res[$y]['pcid'];
            $finalRes[$y]['CashSection'] = number_format($res[$y]['CashSection']).' ریال';
            $finalRes[$y]['NonCashSection'] = number_format($res[$y]['NonCashSection']).' ریال';
            $finalRes[$y]['Name'] = (strlen(trim($res[$y]['accName'])) == 0 ? '--------' : $res[$y]['accName']);
            $finalRes[$y]['Toward'] = $res[$y]['Toward'];
            $finalRes[$y]['blinker'] = ($res[$y]['priorityLevel'] == 1 ? 'blink' : '');
        }
        return $finalRes;
    }

    public function getFinancialConfirmationListCountRows($fcsDate,$fceDate,$fcaName,$fcToward,$fcAmount){
        $ut = new Utility();
        $db = new DBi();
        $w = array();
        if(strlen(trim($fcsDate)) > 0){
            $fcsDate = $ut->jal_to_greg($fcsDate);
            $w[] = '`pay_comment`.`receiverDate` >="'.$fcsDate.'" ';
        }
        if(strlen(trim($fceDate)) > 0){
            $fceDate = $ut->jal_to_greg($fceDate);
            $w[] = '`pay_comment`.`receiverDate` <="'.$fceDate.'" ';
        }
        if(strlen(trim($fcaName)) > 0){
            $w[] = '`accName` LIKE "%'.$fcaName.'%" ';
        }
        if(strlen(trim($fcToward)) > 0){
            $w[] = '`Toward` LIKE "%'.$fcToward.'%" ';
        }
        if(strlen(trim($fcAmount)) > 0){
            $w[] = '`Amount`='.$fcAmount.' ';
        }
        $w[] = '`transfer`=2 ';  // تاییدیه مالی
        $w[] = '`multipleComment`=0 ';  // چند مرحله ای

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid` FROM `pay_comment` ";
        if(count($w) > 0){
            $where = implode(" AND ",$w);
            $sql .= " WHERE ".$where;
        }
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function financialApprovalComment($pid){
        $acm = new acm();
        if(!$acm->hasAccess('financialConfirmation')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $sqls = "SELECT `Amount` FROM `pay_comment` WHERE `RowID`={$pid}";
        $rsts = $db->ArrayQuery($sqls);

        $sqq1 = "SELECT SUM(`dAmount`) AS `dsum` FROM `deposit` WHERE `pid`={$pid}";
        $rsq1 = $db->ArrayQuery($sqq1);

        $sqq2 = "SELECT SUM(`check_amount`) AS `csum` FROM `bank_check` WHERE `cid`={$pid}";
        $rsq2 = $db->ArrayQuery($sqq2);

        $sqq3 = "SELECT SUM(`rAmount`) AS `rsum` FROM `return_money` WHERE `pid`={$pid}";
        $rsq3 = $db->ArrayQuery($sqq3);

        $sqq4 = "SELECT SUM(`fAmount`) AS `fsum` FROM `fraction_money` WHERE `cid`={$pid}";
        $rsq4 = $db->ArrayQuery($sqq4);

        $totalAmount = (intval($rsq1[0]['dsum']) + intval($rsq2[0]['csum']) + intval($rsq4[0]['fsum'])) - intval($rsq3[0]['rsum']);

        if (intval($totalAmount) == 0){
            $result = 0;
        }elseif (intval($totalAmount) == intval($rsts[0]['Amount'])){
            $result = 1;
        }elseif(intval($totalAmount) < intval($rsts[0]['Amount'])){
            $result = 2;
        }else{
            $result = 3;
        }

        $receiverDate = date('Y/m/d');
        //$receiverTime = date('H:i:s');
        $receiverTime=$this->current_time;

        $sqq = "UPDATE `pay_comment` SET `transfer`=3,`paymentStatus`={$result} WHERE `RowID`={$pid}";
        $db->Query($sqq);
        $res1 = $db->AffectedRows();
        $res1 = (($res1 == -1 || $res1 == 0) ? 0 : 1);
        if ($res1){
            $description = 'پرداخت شده';
            $sqq = "INSERT INTO `payment_workflow` (`sender`,`receiver`,`pid`,`status`,`createDate`,`createTime`,`description`,`done`) VALUES ({$_SESSION['userid']},0,{$pid},1,'{$receiverDate}','{$receiverTime}','{$description}',1)";
            $db->Query($sqq);
            return true;
        }else{
            return false;
        }
    }

    public function getBankCheckExcel(){
        $acm = new acm();
        if(!$acm->hasAccess('financialConfirmation') || !$acm->hasAccess('excelexport') ){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT * FROM `bank_check` ";
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        if(count($res) > 0){
            for($i=0;$i<$listCount;$i++){
                $res[$i]['check_number'] = $res[$i]['check_number'];
                $res[$i]['check_date'] = (strtotime($res[$i]['check_date']) > 0 ? $ut->greg_to_jal($res[$i]['check_date']) : '');
                $res[$i]['check_amount'] = number_format($res[$i]['check_amount']).' ریال';
                $res[$i]['checkType'] = (intval($res[$i]['checkType']) == 0 ? 'ابرش' : 'مشتری');
                $res[$i]['description'] = $res[$i]['description'];
            }
            return $res;
        }else{
            $res = "امکان گرفتن خروجی وجود ندارد";
            $out = "false";
            response($res,$out);
            exit;
        }
    }

    //++++++++++++++++++++ کشو موقت مالی ++++++++++++++++++++

    public function getTempFinancialKeshoList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('tempFinancialKesho')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;

        $sql = "SELECT `pay_comment`.`RowID` AS `pcid`,`Amount`,`cDate`,`CashSection`,`NonCashSection`,`Toward`,`accName`,`sendType`,`priorityLevel` FROM `pay_comment` WHERE `transfer`=1 AND `multipleComment`=1 ";
        $sql .= " ORDER BY `pcid` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();
        for($y=0;$y<$listCount;$y++){
            $totalAmount = 0;
            $query = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$res[$y]['pcid']}";
            $rst = $db->ArrayQuery($query);
            if (count($rst) > 0) {
                $cnt = count($rst);
                for ($i=0;$i<$cnt;$i++) {
                    $totalAmount += $rst[$i]['dAmount'];
                }
            }

            $sqlCheck = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$res[$y]['pcid']}";
            $result = $db->ArrayQuery($sqlCheck);
            if (count($result) > 0) {
                $cnt = count($result);
                for ($i=0;$i<$cnt;$i++) {
                    $totalAmount += $result[$i]['check_amount'];
                }
            }

            $rAmount = 0;
            $sqqRTN = "SELECT `rAmount` FROM `return_money` WHERE `pid`={$res[$y]['pcid']}";
            $rstn = $db->ArrayQuery($sqqRTN);
            $cntq = count($rstn);
            for ($i=0;$i<$cntq;$i++){
                $rAmount += $rstn[$i]['rAmount'];
            }

            $fAmount = 0;
            $sqqRFN = "SELECT `fAmount` FROM `fraction_money` WHERE `cid`={$res[$y]['pcid']}";
            $rstf = $db->ArrayQuery($sqqRFN);
            $cntq = count($rstf);
            for ($i=0;$i<$cntq;$i++){
                $fAmount += $rstf[$i]['fAmount'];
            }

            $leftOver = ($res[$y]['Amount'] + $rAmount) - ($totalAmount + $fAmount);
            $finalRes[$y]['txtco'] = (intval($leftOver) < 0 ? 'red' : 'green');
            $finalRes[$y]['leftOver'] = (intval($leftOver) < 0 ? number_format(abs($leftOver)).' ریال مازاد' : number_format($leftOver).' ریال');

            switch ($res[$y]['sendType']){
                case 0:
                    $finalRes[$y]['bgColor'] = 'table-warning';
                    break;
                case 1:
                    $finalRes[$y]['bgColor'] = 'table-orange';
                    break;
                case 2:
                    $finalRes[$y]['bgColor'] = 'table-primary';
                    break;
                case 3:
                    $finalRes[$y]['bgColor'] = 'table-danger';
                    break;
            }
            $finalRes[$y]['RowID'] = $res[$y]['pcid'];
            $finalRes[$y]['cDate'] = $ut->greg_to_jal($res[$y]['cDate']);
            $finalRes[$y]['CashSection'] = number_format($res[$y]['CashSection']).' ریال';
            $finalRes[$y]['NonCashSection'] = number_format($res[$y]['NonCashSection']).' ریال';
            $finalRes[$y]['Name'] = (strlen(trim($res[$y]['accName'])) == 0 ? '--------' : $res[$y]['accName']);
            $finalRes[$y]['Toward'] = $res[$y]['Toward'];
            $finalRes[$y]['blinker'] = ($res[$y]['priorityLevel'] == 1 ? 'blink' : '');
        }
        return $finalRes;
    }

    public function getTempFinancialKeshoListCountRows(){
        $db = new DBi();
        $sql = "SELECT `pay_comment`.`RowID` AS `pcid` FROM `pay_comment` WHERE `transfer`=1 AND `multipleComment`=1 ";
        $res = $db->ArrayQuery($sql);
        return count($res);
    }

    public function confirmedDepositVsMali($did){
        $acm = new acm();
        if(!$acm->hasAccess('tempFinancialKesho')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $cDate = date('Y/m/d');
        //$cTime = date('H:i:s');
        $cTime=$this->current_time;
        $sql = "UPDATE `deposit` SET `maliRecord`=1,`maliRecordDate`='{$cDate}',`maliRecordTime`='{$cTime}' WHERE `RowID`={$did}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1) ? 0 : 1);
        if ($res){
            return true;
        }else{
            return false;
        }
    }

    public function tempFinancialApprovalComment($pid){
        $acm = new acm();
        if(!$acm->hasAccess('tempFinancialKesho')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $receiverDate = date('Y/m/d');
        $receiverTime=$this->current_time;
        //$receiverTime = date('H:i:s');

        $query = "SELECT `RowID` FROM `deposit` WHERE `pid`={$pid} AND `maliRecord`=0";
        $result = $db->ArrayQuery($query);
        if (count($result) > 0){
            return -1;
        }

        $totalAmount = 0;
        $sqq = "SELECT `check_amount` FROM `bank_check` WHERE `cid`={$pid}";
        $rst = $db->ArrayQuery($sqq);
        $cntq = count($rst);
        for ($i=0;$i<$cntq;$i++){
            $totalAmount += $rst[$i]['check_amount'];
        }

        $sqq1 = "SELECT `dAmount` FROM `deposit` WHERE `pid`={$pid}";
        $rst1 = $db->ArrayQuery($sqq1);
        $cntq1 = count($rst1);
        for ($i=0;$i<$cntq1;$i++){
            $totalAmount += $rst1[$i]['dAmount'];
        }

        $rAmount = 0;
        $sqqRTN = "SELECT `rAmount` FROM `return_money` WHERE `pid`={$pid}";
        $rstn = $db->ArrayQuery($sqqRTN);
        $cntq = count($rstn);
        for ($i=0;$i<$cntq;$i++){
            $rAmount += $rstn[$i]['rAmount'];
        }

        $fAmount = 0;
        $sqqRFN = "SELECT `fAmount` FROM `fraction_money` WHERE `cid`={$pid}";
        $rstf = $db->ArrayQuery($sqqRFN);
        $cntq = count($rstf);
        for ($i=0;$i<$cntq;$i++){
            $fAmount += $rstf[$i]['fAmount'];
        }

        $sqq3 = "SELECT `Amount` FROM `pay_comment` WHERE `RowID`={$pid}";
        $rst3 = $db->ArrayQuery($sqq3);

        if (intval($totalAmount) == 0){
            $result = 0;
        }elseif ((intval($totalAmount) + $fAmount) == ($rst3[0]['Amount'] + $rAmount)){
            $result = 1;
        }elseif((intval($totalAmount) + $fAmount) < ($rst3[0]['Amount'] + $rAmount)){
            $result = 2;
        }else{
            $result = 3;
        }
        $sql1s = "UPDATE `pay_comment` SET `paymentStatus`={$result} WHERE `RowID`={$pid}";
        $db->Query($sql1s);

        if ((intval($totalAmount) + $fAmount) < (intval($rst3[0]['Amount']) + $rAmount) ){  // جمع مبالغ پرداختی کوچکتر از مبلغ کل بود
            return -2;
        }

        $sql = "UPDATE `pay_comment` SET `transfer`=3 WHERE `RowID`={$pid}";
        $db->Query($sql);
        $res = $db->AffectedRows();
        $res = (($res == -1 || $res == 0) ? 0 : 1);
        if ($res){
            $description = 'پرداخت شده';
            $sqq = "INSERT INTO `payment_workflow` (`sender`,`receiver`,`pid`,`status`,`createDate`,`createTime`,`description`) VALUES ({$_SESSION['userid']},0,{$pid},1,'{$receiverDate}','{$receiverTime}','{$description}')";
            $db->Query($sqq);
            return true;
        }else{
            return false;
        }
    }

    public function getLayer1(){
        $db = new DBi();
        $sql = "SELECT RowID,layerName FROM layers WHERE parentID='-1 '";
        $result = $db->ArrayQuery($sql);
        if(count($result)>0){
            return $result;
        }
        else{
           return false; 
        }
    }

    public function getLayer2($serach_key=""){
        $db = new DBi();
        $ut=new Utility();
        if(!empty($serach_key)){
            $layer2_sql="SELECT RowID,layerName From layers where parentID ={$serach_key} ";
            $result=$db->ArrayQuery($layer2_sql);
            return $result;
        }
    
        // $parents_id=$this->getLayer1();
        // $parents_array=[];
        // foreach($parents_id as $row){
        //     $parents_array[]=$row['RowID'];
        // }
        // $parents_implode=implode(",",$parents_array);
        // $layer2_sql="SELECT RowID,layerName From layers where parentID in({$parents_implode}) ";
        // $result=$db->ArrayQuery($layer2_sql);
        // //$ut->fileRecorder('result:');
        // //$ut->fileRecorder($result);
        // return $result;
       
    }

    public function getLayer3($serach_key=""){
        $db = new DBi();
        $ut=new Utility();
        if(!empty($serach_key)){
            $layer2_sql="SELECT RowID,layerName From layers where parentID ={$serach_key} ";
            $result=$db->ArrayQuery($layer2_sql);
            return $result;
        }
        // $parents_id=$this->getLayer2();
        // $parents_array=[];
        // foreach($parents_id as $row){
        //     $parents_array[]=$row['RowID'];
        // }
        // $parents_implode=implode(",",$parents_array);
        // $layer2_sql="SELECT RowID,layerName From layers where parentID in({$parents_implode}) ";
        // $result=$db->ArrayQuery($layer2_sql);
        // //$ut->fileRecorder('result:');
        // //$ut->fileRecorder($result);
        // return $result;
    }

    public function update_code_tafzilii8(){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT  cFor from pay_comment where codeTafzili=8 GROUP BY cFor";
       
        $res=$db->ArrayQuery($sql);
        $cFor_array=[];
        foreach($res as $key=>$value)
        {
            $cFor_array[]=$value['cFor'];
        }
       foreach($cFor_array as $key2=>$value2){
        $handler_array=[];
        $handler_array=explode(',',$value2);
        if(!empty($handler_array[0])){
            $get_acc_name="SELECT `Name` from `account` where RowID={$handler_array[0]}";
            $accunt_res=$db->ArrayQuery($get_acc_name);
            $acc_name=$accunt_res[0]['Name'];
            $update="update pay_comment set `accName`='{$acc_name}' where `codeTafzili`=8 AND cFor='{$value2}'";
            $res=$db->Query($update);
           
        }
        else{
            $update="update pay_comment set `accName`='' where `codeTafzili`=8 AND cFor=''";
            $res=$db->Query($update);
            
        }
       
        
       }
    }
}