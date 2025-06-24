<?php
class Inquires{

    public function __construct(){
	
    }
    public function __call($method, $arguments)
    {
        try {
            return call_user_func_array([$this->obj, $method], $arguments);
        } catch (Exception $e) {
            if ($this->handler) {
                throw call_user_func($this->handler, $e);
            } else {
                throw $e;
            }
        }
    }

    public function get_inquray_status_info($group_info){
        $db = new DBi();
		$ut=new Utility();
        $acm = new acm();
        $sql="SELECT `code`,`description` FROM `inquiry_status` WHERE `group` ='{$group_info}'";
        $res=$db->ArrayQuery($sql);
        $ut->fileRecorder($sql);
        $ut->fileRecorder($res);
        return $res;
    }

	public function get_inquires($status,$page=1,$other_params=[]){
		$db = new DBi();
		$ut=new Utility();
        $acm = new acm();
        $flag=0;
        $listcount=LISTCNT;
        $w="i.`status` <> -1 AND s.`group`='inq_status' AND ";
        if($page==1){
            $start=0;
            $end=LISTCNT;
        }
        else{
            $start=($page-1)*LISTCNT ;
            $end=$page*LISTCNT;
        }

       
        switch($status){
            case"0":
            case 0:
                $w.="  i.`status`={$status} AND i.`creator_id`={$_SESSION['userid']}";
                $status_desc="در حال ثبت";
                break;
            case"1":
            case 1:
                $w.="   i.`creator_id`={$_SESSION['userid']} AND  i.status={$status}";
                $status_desc="استعلام آماده ارسال  ";
                break;
            case"2":
            case 2:
                $w.="    i.`last_receiver`={$_SESSION['userid']}";
                $status_desc="استعلام دریافت  شده  ";
                break;
            case"3":
            case 3:
                $w.="  i.`creator_id`={$_SESSION['userid'] } AND i.`status` ={$status} ";
                $status_desc="استعلام ارسال شده  ";
                break;
            case"10":
            case 10:
                $w.="  i.`creator_id`={$_SESSION['userid']} AND  i.`status`={$status} ";
                $status_desc="استعلام  بایگانی شده  ";
                break;
        }
       // $ut->fileRecorder('status:'.$w);
        if(count($other_params)>0){
            $persian_from_date= $other_params['inq_from'];
            $persian_to_date= $other_params['inq_to'];
            $other_params['inq_from']=$ut->jal_to_greg($other_params['inq_from']);
            $other_params['inq_to']=$ut->jal_to_greg($other_params['inq_to']);
            if(!empty($other_params['inq_name'])){
                $w.=" AND i.`title` LIKE '%{$other_params['inq_name']}%'";
            }
            if(!empty($other_params['inq_code'])){
                $w.=" AND i.`inquiry_code` ='{$other_params['inq_code']}'";
            }
            if(!empty($other_params['inq_from']) && !empty($other_params['inq_to'])){
                $w.=" AND i.`inquiry_date` BETWEEN '{$other_params['inq_from']}' AND '{$other_params['inq_to']}'";
            }

        }

        $rowCount="SELECT i.RowID FROM inquiries as i 
			LEFT JOIN inquiry_status as s on i.status=s.code  WHERE {$w} ";
        $res_row=$db->ArrayQuery($rowCount);
        $sql="SELECT i.*,s.description as status_name FROM inquiries as i 
			LEFT JOIN inquiry_status as s on i.status=s.code  WHERE {$w} ORDER BY RowID DESC LIMIT {$start},{$listcount} ";
        $ut->fileRecorder($sql);
        $result=$db->ArrayQuery($sql);

        $last_page=ceil(count($res_row)/LISTCNT);
        $pageinate=
            '<nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item">
                        <a style="color:red" class="page-link" onclick="go_first(this,1)">
                            <i class="fa fa-fast-forward"></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a style="color:green" class="page-link" onclick="go_previous(this,1)" >
                            <i class="fa fa-step-forward"></i>
                        </a>
                    </li>
                    <span>&nbsp;&nbsp; صفحه &nbsp;&nbsp; </span> <span> <input type="number" min="1" max="'.$last_page.'" onchange="go_to_page(this,'.$status.','.$last_page.')" class="paginate_input" value="'.$page.'" type="text" ></span><span> از </span> &nbsp;&nbsp;'.$last_page .'&nbsp;&nbsp;</span>
                    <li class="page-item">
                        <a style="color:green" class="page-link" onclick="go_next(this,1,'.$last_page.')">
                            <i class="fa fa-step-backward "></i>
                        </a>
                    </li>
                    <li class="page-item">
                        <a style="color:red" class="page-link" onclick="go_last(this,1,'.$last_page.')">
                            <i class="fa fa-fast-backward "></i>
                        </a>
                    </li> 
                </ul>
            </nav>';
        $htm="";
        if($status!=-1)
        {
            $htm .= '<div class="inq_table">';
            $htm .= '<input type="hidden"id="inq_create_date_input" value="' . $ut->greg_to_jal(date("Y-m-d")) . '">';
            $htm .= '<div class="inq_list_header"></div>';
            $htm .= '<div class="inq_list_header_search" style="width: 100%">';
            $htm .= '<div><input type="text" class="inq_name_search"  id="inq_name_search_'.$status.'" value="' . $other_params['inq_name'] . '"  class="form-control mr-2" placeholder="نام استعلام "></div>';
            $htm .= '<div><input type="text" class="inq_code_search" id="inq_code_search_'.$status.'" value="' . $other_params['inq_code'] . '" class="form-control mr-2" placeholder="  کد استعلام   "></div>';
            $htm .= '<div style="display: flex"><input type="text"  class="inq_from_date_search" id="inq_from_date_search_'.$status.'" value="' . $persian_from_date . '"   class="form-control mr-2" placeholder="از تاریخ"></div>';
            $htm .= '<div  style="display: flex"><input type="text" class="inq_to_date_search" id="inq_to_date_search_'.$status.'"  value="' . $persian_to_date . '" o class="form-control mr-2"  placeholder="تا تاریخ"></div>';
            $htm .= '<div><input type="text" class="inq_reciever_search" id="inq_reciever_search_'.$status.'" onchange="search_inquiry(this,'.$status.')"  class="form-control mr-2"  placeholder=" دریافت کننده"></div>';
            $htm .= '<div><button  class="btn btn-info"  id="inq_search_btn_'.$status.'" onclick="search_inquiry(this,'.$status.')"  class="form-control mr-2" ><i  class="fa fa-search"> </i><span style="font-family: IranSans">  جستجو  </span> </button> </div>';
            $htm .= '</div>';
            if (count($result) > 0) 
            {
                if ($status == 0) {
                    $table_header = ['ردیف', 'گروه استعلام ', 'تاریخ استعلام', 'کد استعلام', 'آخرین دریافت کننده', 'وضعیت استعلام', 'مدیریت'];
                } elseif ($status == 1) {
                    $table_header = ['ردیف', 'گروه استعلام ', 'تاریخ استعلام', 'کد استعلام', 'آخرین دریافت کننده', 'وضعیت استعلام', 'مدیریت'];
                } elseif ($status == 2) {
                    $table_header = ['ردیف', 'گروه استعلام ', 'تاریخ استعلام', 'کد استعلام', 'آخرین دریافت کننده', 'وضعیت استعلام', 'مدیریت'];
                } elseif ($status == 3) {
                    $table_header = ['ردیف', 'گروه استعلام ', 'تاریخ استعلام', 'کد استعلام', 'آخرین دریافت کننده', 'وضعیت استعلام', 'مدیریت'];
                } elseif ($status == 10) {
                    $table_header = ['ردیف', 'گروه استعلام ', 'تاریخ استعلام', 'کد استعلام', 'آخرین دریافت کننده', 'وضعیت استعلام', 'مدیریت'];
                }

                $htm .= '<table class="table table-borderd">';
                $htm .= '<thead class="thead-dark">';
                $htm .= '<tr>';
                foreach ($table_header as $header) {

                    $htm .= "<th>" . $header . "</th>";
                }
                $htm .= '</tr>';
                $htm .= '</thead>';
                $htm .= '</tbody>';
                $counter = $start + 1;
                foreach ($result as $row) {

                    $htm .= "<tr>";
                    $htm .= "<td>" . $counter . "</td>";
                    $htm .= "<td>" . $this->get_inq_status('inq_group',$row['title']) . "</td>";
                    $htm .= "<td>" . $ut->greg_to_jal($row['inquiry_date']) . "</td>";
                    $htm .= "<td>" . $row['inquiry_code'] . "</td>";
                    $htm .= "<td>" . $ut->get_user_fullname($row['last_receiver']) . "</td>";
                    $htm .= "<td>" . $status_desc . "</td>";
                    if ($status != 0) {
                        $htm .= '<td><button class="btn btn-primary-outline" onclick="show_inq_detailes(' . $row['RowID'] . ',this,' . $status . ')"><i class="fa fa-angle-left manage_inq"</i></button</td>';
                    } else {
                        $htm .= '<td><button class="btn btn-primary-outline" onclick="open_inq_manage(this,' . $row['RowID'] . ',' . $status . ')"><i class="fa fa-angle-left manage_inq"</i></button</td>';
                    }

                    $htm .= "<tr>";
                    $counter++;
                }
                $htm .= "</tbody>";
                $htm .= "</table>";
                $htm .= "<div></div>";
                 $flag=1;
                
            } 
            else 
            {
                $htm .= '<p class="inq_not_found">موردی با کاربری شما یافت نشد</p>';
            }
            if($flag==1){
                $htm .= "<div>".$pageinate."</div>";
            }
        }
        else{
            $seller_content="SELECT * FROM `account`  where account_role like '%2%'";
            $s_res=$db->ArrayQuery($seller_content);
            $temp_html="";
            $counter=1;
            foreach($s_res as $key=>$value){
                $temp_html.="<tr><td>".$counter."</td><td>".$value['code']."</td><td>".$value['Name']."</td><td>".$value['address']."</td><td>".$value['phone']."</td></tr>";
                $counter++;
            }
            $htm.='<div class="setting" style="width: 100%">
                        <ul class="nav nav-tabs pb-3">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#seller_manangment" onclick="go_to_setting_page(this)">مدیریت فروشندگان</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" onclick="go_to_setting_page(this)" href="#rate_manangment">مدیریت  نرخ سود</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" onclick="go_to_setting_page(this)" href="#vat_manangment">مدیریت  درصد ارزش افزوده</a>
                            </li>
                        </ul>

                        <div class="setting_page" style="width: 100%;height:auto;display: block" id="seller_manangment">
                            <div class="header-search"></div>
                            <div class="seller_content">
                            ';
                            // if($acm->hasAccess('inq_add_new_Supplier')){
                            //   // $htm.='<div style="padding:2rem;margin:2rem"><button id="add_new seller_btn" onclick="inq_add_new_seller(this)" class="btn btn-info">ایجاد مشتری جدید</button></div>';
                            // } 
                            
                            $htm.='<table id="seller_manage_tbl" style="width:100%" class="table table-borderd" border="1"><thead>
                            <tr> <td>ردیف</td><td>کد تفضیلی</td><td>نام مشتری</td><td>نشانی</td><td>تلفن</td></tr></thead><tbody>'.
                                $temp_html

                           .'</tbody></table></div>
                        
                        </div>
                        <div class="setting_page" style="width: 100%;height: auto;display: none" id="rate_manangment">نرخ سود</div>
                        <div class="setting_page" style="width: 100%;height: auto;display: none" id="vat_manangment">نرخ ارزش افزوده</div>
    
            </div>';
        }
		return $htm;
	}

    public function get_inquiry_final_confirm($inq_id){
        $ut = new Utility();
        $db = new  DBi();
        $sql = "SELECT * FROM inquiries as i LEFT JOIN inquiry_workflow as iw on i.`RowID`=iw.`inquiry_id`
        where (iw.`sender`=4 OR iw.`sender`=20) and iw.`status`=1 AND i.`RowID`={$inq_id}";
        $res = $db->ArrayQuery($sql);
       if(count($res)>0){
        return 1;

       }
       return 0;
    }

    public function create_pay_comment_modal($g_id){

        $ut = new Utility();
        $db = new DBi();
        $cmt = new Comment();
        $projects_detailes=$cmt->get_comment_projects();
        $sql = "SELECT * FROM `inquiries_meta` where `group_code`='{$g_id}'";
        $final_array = [];
        $array_handler = [];
        $res = $db->ArrayQuery($sql);
        foreach($res as $key=>$value){
            $array_handler[$value['key']]=$value['value']; 
        }
        $total_pay_amount =(intval($array_handler['export_good_quantity'])*intval($array_handler['export_unit_price']))+intval($array_handler['vat_amount']);
      
        $seller_account_data = $this->get_seller_name($array_handler['export_inq_seller_name'],1);
        
        $toward = ' بابت خرید '.$array_handler['inq_good_quantity'].$this->get_unit($array_handler['inq_good_quantity_unit'])." ".$this->get_good_name($array_handler['inq_good_id']);
        $final_array['account_name']=$seller_account_data[0];
        $final_array['account_tafzili_code']=$seller_account_data[1];
        $final_array['toward']=$toward;
        $final_array['accountNumber']=$seller_account_data[2];
        $final_array['bankName']=$seller_account_data[3];
        $final_array['acc_RowID']=$seller_account_data[4];
        $final_array['total_amount_pay']=$total_pay_amount;

      // $ut->fileRecorder($array_handler);
        $modalID = "commentManagmentModal";
        $modalTitle = "ثبت  اظهارنظر";
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

        //********************************************************* */
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
        //********************************************************* */
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
        $items[$c]['title'] = "انتخاب زیرگروه فرعی";
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
//-----------------------------
        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "base_inq";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "group_code_comment";
        $c++;
//--------------------------------------------------
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
        $items[$c]['disabled'] = "disabled";
       // $items[$c]['onchange'] = "onchange=getAccountNumber()";
        $items[$c]['title'] = "نام طرف حساب";
        $items[$c]['placeholder'] = "تایپ کنید";
        $c++;

        $items[$c]['type'] = "text";
        $items[$c]['id'] = "commentManagmentCode";
        $items[$c]['disabled'] = "disabled";
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
        return [$editCreateCommentModal,$final_array];
    }

    public function getLayer1(){
        $ut=new Utility();
        $db=new  DBi();
        $sql="SELECT `RowID`,`layerName` FROM `layers` WHERE `parentID`=-1";
        $res=$db->ArrayQuery($sql);
        return $res;
    }

    public function create_inquiry_pay_comment($inq_id){
        $ut=new Utility();
        $db=new  DBi();
        $sql="SELECT `group_code` FROM `inquiries_meta` WHERE `inquiry_id`={$inq_id} AND  `key`='selected_seller' AND `value`=1 ";
        $res=$db->ArrayQuery($sql);
        $group_array=[];
        foreach($res as $key=>$value){
            $group_array[] = $value['group_code'];
        }
        $array_info=[];
        for($i=0;$i<count($group_array);$i++){
            $inq_sql = "SELECT * FROM `inquiries_meta` WHERE  `inquiry_id`='{$inq_id}' AND  `group_code`='{$group_array[$i]}'";
            $res = $db->ArrayQuery($inq_sql);
            $pay_method=[];
            $pay_date=[];
            $pay_amount=[];

            for($k=0;$k<count($res);$k++){

                if($res[$k]['key']=="pay_method"){
                   $pay_method[]=$res[$k]['value'];
                }

                if($res[$k]['key']=="pay_cheque_date"){
                    $pay_date[]=$res[$k]['value'];
                }

                if($res[$k]['key']=="pay_cash_section"){
                    $pay_amount[]=$res[$k]['value'];
                }
                if($res[$k]['key']=='inq_good_id'){
                    $array_info[$group_array[$i]]['inq_good_id']=$res[$k]['value'];
                }
                if($res[$k]['key']=='export_inq_seller_name'){
                    $array_info[$group_array[$i]]['export_inq_seller_name']=$res[$k]['value'];
                }
                if($res[$k]['key']=='temp_seller_name'){
                    $array_info[$group_array[$i]]['temp_seller_name']=$res[$k]['value'];
                }
            }
            $array_info[$group_array[$i]]['pay_method']=$pay_method;
            // $array_info[$group_array[$i]]['pay_date']=$pay_date;
            // $array_info[$group_array[$i]]['pay_amount']=$pay_amount;
           
        }
        $html="";
       foreach($array_info as $arr_key=>$arr_value){
       
        $ut->fileRecorder($array_info);
            $html.="<fieldset  style='background:rgba(0,255,0,0.1);border:2px solid gray;border-radius:10px;padding:10px'>
                    <legend style='width:auto;font-size:1rem;padding:10px'>".$this->get_good_name($arr_value['inq_good_id'])."</legend>";
            $html.="<h6 class='text-info'>  نام مشتری  : ".(!empty($arr_value['export_inq_seller_name'])?$this->get_seller_name($arr_value['export_inq_seller_name']):$arr_value['temp_seller_name'])."</h6>";
            $html.="<table class='table table-border'>";
            $html.=$this->get_inquiry_pay_deliver_method($arr_key,'p','ریال',['get_all_payComments',$arr_key,'ثبت اظهارنظر']);        
            $html.="</table>";
            $html.="</fieldset>";
        }
        return $html;
    }

    public function display_inq_counts(){
        $db=new DBi();
        $ut=new Utility();
        $count_array=[];
        $get_temp_inqs="SELECT RowID from `inquiries` where `status`=0 AND  `creator_id`={$_SESSION['userid']}";//get temp inqs
        $res_temp=$db->ArrayQuery($get_temp_inqs);
        $array_handler1=array('status_type'=>'temp','status_count'=>count($res_temp));
        $count_array[]=$array_handler1;
        //--------------------------------------------------------------------------------------------------------
        $get_temp_inqs="SELECT RowID from `inquiries` where `status`=1 AND  `creator_id`={$_SESSION['userid']}";//get ready inqs
        $res_temp=$db->ArrayQuery($get_temp_inqs);
        $array_handler5=array('status_type'=>'ready','status_count'=>count($res_temp));
        $count_array[]=$array_handler5;
        //------------------------------------------------------------------------------------------------------
        $get_sended_inqs="SELECT RowID from `inquiries` where `status`=3 AND  `creator_id`={$_SESSION['userid']}";//get sended inqs
        $res_sended=$db->ArrayQuery($get_sended_inqs);
        $array_handler2=array('status_type'=>'sended','status_count'=>count($res_sended));
        $count_array[]=$array_handler2;
        //-----------------------------------------------------------------------------------------------------------------
        $get_received_inqs="SELECT RowID from `inquiries` where `status`=3 AND  `last_receiver`={$_SESSION['userid']}";//get received inqs
        $res_received=$db->ArrayQuery($get_received_inqs);
        $array_handler3=array('status_type'=>'received','status_count'=>count($res_received));
        $count_array[]=$array_handler3;
        //-----------------------------------------------------------------------------------------------------------------
        $get_archived_inqs="SELECT RowID from `inquiries` where `status`=10 AND  `creator_id`={$_SESSION['userid']}";//get archived inqs
        $res_archived=$db->ArrayQuery($get_archived_inqs);
        $array_handler4=array('status_type'=>'archived','status_count'=>count($res_archived));
        $count_array[]=$array_handler4;

        // $ut->fileRecorder('result');
        // $ut->fileRecorder($count_array);
        return $count_array;
    }

    public function delete_inquiry($rowID){
        $db=new DBi();
        $ut=new Utility();
        $sql="UPDATE `inquiries` SET `status`='-1' WHERE RowID={$rowID}";
       $ut->fileRecorder($sql);
       // $ut->fileRecorder($sql);
        $res=$db->Query($sql);
        if($res){
            return true;

        }
        return false;
    }

    public function get_inq_comments($inq_id,$sender,$RowID){
        $db=new DBi();
        $ut=new Utility();
        $comment_sql="SELECT `good_id`,`message` from `inquiry_comments` where `inq_id`={$inq_id} AND sender={$sender}  AND `workflow_id`={$RowID}";
       
        $res_comment=$db->ArrayQuery($comment_sql);
       
        $htm="";
        foreach($res_comment as $k=>$v){
            $htm.="<fieldset style='border:2px solid gray;border-radius:10px;padding:10px'><legend style='width:auto;font-size:1rem;padding:10px'>".$this->get_good_name($v['good_id'])."</legend><p>".$v['message']."</p></fieldset>";
        }
       // $ut->fileRecorder($htm);
        return $htm;

    }
    public function get_pay_deliver_info_htm($group_id,$info_type,$unit=""){
        $ut=new Utility();
        $db=new DBi();
        $html="";
       // get_inq_status($group_name,$value)
        if($info_type=="p"){
            $sql = "SELECT * FROM inq_pay_method where group_id='{$group_id}'";
            $p_res = $db->ArrayQuery($sql);
            $html='<table border="1" class="table  table-borderd"><thead><tr><th>ردیف</th><th>روش پرداخت</th><th>نوع پرداخت</th><th>مبلغ ('.$unit.')</th><th>تاریخ پرداخت</th></tr></thead><tbody>';
            $counter=1;
            foreach($p_res as $key=>$value){
                $html.="<tr><td>".$counter."</td><td>".$this->get_inq_status('pay_method',$value['pay_method'])."</td><td>".$this->get_inq_status('pay_type',$value['pay_type'])."</td><td>".number_format($value['pay_amount'])."</td><td>".$ut->greg_to_jal($value['pay_date'])."</td></tr>";
                $counter++;
            }
            
        }
        if($info_type=="d"){
            $sql = "SELECT * FROM inq_deliver_method where group_id='{$group_id}'";
            $p_res = $db->ArrayQuery($sql);
            $html='<table border="1" class="table table-borderd"><thead><tr><th>ردیف</th><th>روش تحویل</th><th>مقدار تحویلی ('.$unit.')</th><th>تاریخ تحویل</th></tr></thead><tbody>';
            $counter=1;
            foreach($p_res as $key=>$value){
                $html.="<tr><td>".$counter."</td><td>".$this->get_inq_status('deliver_method',$value['deliver_method'])."</td><td>".number_format($value['deliver_amount'])."</td><td>".$ut->greg_to_jal($value['deliver_date'])."</td></tr>";
                $counter++;
            }
            
        }
        $html.="</tbody></table>";
        return $html;
        
    }
    public function get_all_inq_sends($row_id){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT * FROM `inquiry_workflow` where `inquiry_id`={$row_id}";
        $res=$db->ArrayQuery($sql);
        $html="";
       if(count($res)>0){
            $html.="<table id='send_".$row_id."' class='table table-bordered table-striped'>";
            $html.="<thead><tr><td>ردیف</td><td>فرستنده</td><td>گیرنده</td><td>وضعیت تایید</td><td>تاریخ و زمان ارسال</td><td>شرح</td> </tr></thead><tbody>";
            $counter=1;
            foreach ($res as $key=>$value){
                $status=$value['status']==1?"<span style='color:green'>تایید</span>":"<span style='color:red'>عدم تایید</span>";
                $comment=$this->get_inq_comments($row_id,$value['sender']);
              //  $ut->fileRecorder('comment');
               // $ut->fileRecorder($comment);
              $html.="<tr>
                        <td>".$counter."</td>
                        <td>".$ut->get_user_fullname($value['sender'])."</td>
                        <td>".$ut->get_user_fullname($value['reciever'])."</td>
                        <td>".$status."</td>
                        <td>".$ut->greg_to_jal($value['create_date'])." ".$value['create_time']."</td>
                        <td>";
                       
                            $html.="<fieldset style='border:2px solid gray;border-radius:10px;padding:10px;margin-bottom:10px'><legend style='width:auto'>".$value['good_id']."</legend><p>".$value['message']."</p></fieldset>";
                      
                        $html.="</td>
                    </tr>";
              $counter++;
           }
            $html.="</tbody></table>";
       }
       else{
           $html.="<p style='text-align: center;color: red'>فاقد گردش</p>";
       }
        $final_res=$html;
        return $final_res;
    }

    public function get_inq_status($group_name,$value){
        $db = new DBi();
        $ut=new Utility();
        $sql = "SELECT `description` FROM  `inquiry_status` WHERE `code`={$value} AND `group`='{$group_name}'";
        $res = $db->ArrayQuery($sql);
        return $res[0]['description'];
    }
       
    public function inq_get_all_base_purchase(){
        $db=new DBi();
		$ut=new Utility();

        $sql="SELECT `code`,`description` FROM `inquiry_status` where `description` <>'' AND `group` ='base_of_purchase' ";
       
		$res=$db->ArrayQuery($sql);
		return $res;
    }

	public function inq_get_all_goods($good_name){
		$db=new DBi();
		$ut=new Utility();

        $sql="SELECT code,title FROM inquiry_good where title <>'' AND title like '%{$good_name}%'";
       
		$res=$db->ArrayQuery($sql);
		return $res;
	}

    public function inq_create_new_goods($good_name){
        $db=new DBi();
        $ut=new Utility();
        $uniq_id = abs( crc32( uniqid() ) );
        $sql="INSERT INTO inquiry_good (`code`,`title`)VALUES('{$uniq_id}','{$good_name}')";
       // $ut->fileRecorder($sql);
        $res=$db->Query($sql);
        if($res){
            $final_sql="SELECT code,title from inquiry_good where title <>''";
            $res=$db->ArrayQuery($final_sql);
            return $res;
        }
        else{
            return [];
        }

    }

    public function get_all_users_access_inq($row_id){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT u.`RowID`,u.`fname`,u.`lname` 
        from `users` as u left join access_table as a on a.`user_id`=u.`RowID` 
                        LEFT JOIN accessitem as at on at.RowID=a.item_id
         where u.`IsEnable`=1 AND at.`accessNameEn`='PurchaseInquiry' AND u.RowID<>{$_SESSION['userid']} GROUP BY u.`RowID` ";
        $res=$db->ArrayQuery($sql);
        $final_array['users']=$res;
        $get_goods_inq="SELECT good_id from inquiries_meta where inquiry_id={$row_id} group by good_id";
        $res2=$db->ArrayQuery($get_goods_inq);
        $handler_array2=[];
        foreach($res2 as $k=>$value){
            /*$get_inq_comments="SELECT `message` from inquiry_comments where inq_id={$row_id} AND good_id={$value['good_id']} ORDER BY RowID DESC LIMIT 1";
            $ut->fileRecorder($get_inq_comments);
            $res3=$db->ArrayQuery($get_inq_comments);*/

            $handler_array2[]=array('good_name'=>$this->get_good_name($value['good_id']),'good_id'=>$value['good_id']);
        }
        $final_array['goods']=$handler_array2;
        $get_sended_sql="SELECT * FROM inquiry_workflow where `inquiry_id` ={$row_id}";
      //  $ut->fileRecorder($get_sended_sql);
        $res4=$db->ArrayQuery($get_sended_sql);
         $html="";
        // // foreach($res4 as $key=>$value){
        // //     $sql="SELECT * FROM `inquiry_workflow` where `inquiry_id`={$row_id}";
        // //     $res=$db->ArrayQuery($sql);
        // //     $html="";
           if(count($res4)>0){
                $html.="<table id='send_".$row_id."' class='table table-bordered table-striped'>";
                $html.="<thead><tr><td>ردیف</td><td>فرستنده</td><td>گیرنده</td><td>وضعیت تایید</td><td>تاریخ و زمان ارسال</td><td>شرح</td> </tr></thead><tbody>";
                $counter=1;
                foreach ($res4 as $key=>$value){
                    $status=$value['status']==1?"<span style='color:green'>تایید</span>":"<span style='color:red'>عدم تایید</span>";
                    $comment=$this->get_inq_comments($row_id,$value['sender'],$value['RowId']);
                  
                    $html.="<tr>
                            <td>".$counter."</td>
                            <td>".$ut->get_user_fullname($value['sender'])."</td>
                            <td>".$ut->get_user_fullname($value['reciever'])."</td>
                            <td>".$status."</td>
                            <td>".$ut->greg_to_jal($value['create_date'])." ".$value['create_time']."</td>
                            <td>".$comment."</td>
                        </tr>";
                  $counter++;
               }
                $html.="</tbody></table>";
           }
           else{
               $html.="<p style='text-align: center;color: red'>فاقد گردش</p>";
           }

        
       // 
       $final_array['grid_html']=$html;
      // $ut->fileRecorder($final_array);
        return $final_array;

    }

	public function inq_get_all_buyer_sellers(){
		$db=new DBi();
		$ut=new Utility();
		$sql="SELECT `RowID`,`Name` FROM account  where account_role like '%2%'";
		$res=$db->ArrayQuery($sql);
		return $res;
	}
	public function inq_get_all_units(){
		$db = new DBi();
		$ut = new Utility();
		$sql = "SELECT `RowID`,`description` FROM inquiry_good_units ";
		$res = $db->ArrayQuery($sql);
		return $res;
	}

    public function get_temp_seller_id(){
        $db=new DBi();
        $sql="SELECT `value` from `inquiry_options` WHERE `key`='temp_seller_id'";
        $res=$db->ArrayQuery($sql);
        return $res[0]['value'];
    }

	public function save_inquiry($inq_title,$inq_date,$inq_buy_code,$inq_id,$inq_created_date){
		$db = new DBi();
		$ut = new Utility();
		$userid=$_SESSION['userid'];
		$inq_date=$ut->jal_to_greg($inq_date);
        $inq_created_date=date('Y-m-d');
        $j_date=$ut->greg_to_jal($inq_created_date);
        $j_array=explode("/",$j_date);
        $j_arr_y=str_ireplace("14","",$j_array[0]);
        if($inq_id==0) {//    insert new inquiry
            $inquiry_code = $j_arr_y.(strtotime(date('Y-m-d H:i:s'))) . rand(10000, 99999);

            $sql = "INSERT INTO `inquiries` (title,inquiry_code,creator_id,inquiry_date,`purchase_code`,`inq_created_date`) 
                VALUES ('{$inq_title}','{$inquiry_code}','{$userid}','{$inq_date}','{$inq_buy_code}','{$inq_created_date}')";
            $res = $db->Query($sql);
            if ($res) {
                
                $sql2 = "SELECT RowID,inquiry_code from inquiries ORDER BY RowID DESC LIMIT 1";

                $last_inq_code_res = $db->ArrayQuery($sql2);
                $last_inq_code = array('inquiry_code' => $last_inq_code_res[0]['inquiry_code'], 'inquiry_id' => $last_inq_code_res[0]['RowID'],'seller_temp_id'=>$this->get_temp_seller_id());

                unset($_SESSION['inquiry_id']);
                $_SESSION['inquiry_id'] = $last_inq_code_res[0]['RowID'];

            } else {
                return -1;
            }

            return $last_inq_code;
        }
        else{//update inquiry
            $sql_select="SELECT * FROM inquiries WHERE RowID={$inq_id}";
            $select_res=$db->ArrayQuery($sql_select);
            $last_history=$select_res[0]['history'];
            $history_array=[];
            if(!empty($last_history)){
                $history_array=json_decode($last_history);
            }

            unset($select_res[0]['history']);
            $history_array[]=$select_res[0];
            $history_json=json_encode($history_array,JSON_UNESCAPED_UNICODE);
            $sql="update inquiries set `title` ='{$inq_title}',`inquiry_date`='{$inq_date}',`purchase_code`='{$inq_buy_code}' WHERE RowID={$inq_id}";
            $res_updated=$db->Query($sql);
            $affected=$db->AffectedRows();
            $res_update=true;
            if($affected>0){
                $sql_update_history="update inquiries set `history` ='{$history_json}' WHERE RowID={$inq_id}";
                $res_update=$db->Query($sql_update_history);
            }
            if($res_updated &&  $res_update ){

               // $last_inq_code_update = array('inquiry_code' => $select_res[0]['inquiry_code'], 'inquiry_id' => $select_res[0]['RowID']);
                $last_inq_code_update = array('inquiry_code' => $select_res[0]['inquiry_code'], 'inquiry_id' => $select_res[0]['RowID'],'seller_temp_id'=>$this->get_temp_seller_id());
                return $last_inq_code_update;
            }

        }
	}

    public function send_inquiry($reciever,$status,$description,$inq_id){
        //$_POST['reciever'],$_POST['status'],$_POST['inq_send_comment'],$_POST['inquiry_id']
       $db=new DBi();
       $ut=new Utility();
       $current_date=date('Y-m-d');
       $current_time=date('H:i:s');
       $get_sql="SELECT * from inquiries  where RowID={$inq_id}";
       $res_creator=$db->ArrayQuery($get_sql);
       $creator=$res_creator[0]['creator_id'];
       if($creator==$_SESSION['userid']){
            if($status==0){
                return -1;
            }
       }
       
       $sql="INSERT INTO `inquiry_workflow` (`sender`,`reciever`,`inquiry_id`,`status`,`create_date`,`create_time`) VALUES('{$_SESSION['userid']}',{$reciever},'{$inq_id}','{$status}','{$current_date}','{$current_time}')";
       $res=$db->Query($sql);
       if($res){
        $get_last_id="SELECT RowID from `inquiry_workflow` order by RowID DESC LIMIT 1";
        $res_w=$db->ArrayQuery($get_last_id);
        $workflow_id=$res_w[0]['RowID'];
           $update_inq="UPDATE inquiries set last_receiver='{$reciever}',`status`=3 where RowID ='{$inq_id}'";
           $res_u=$db->Query($update_inq);
       }
      // $ut->fileRecorder($description);
       if(!empty($description)){
            $desc=json_decode($description,true);
           // $ut->fileRecorder('desc');
           $ut->fileRecorder($desc);
            foreach($desc as $k=>$v){
                $ut->fileRecorder($v);
                $sql2="SELECT group_code FROM `inquiries_meta` where inquiry_id={$inq_id} AND `key`='selected_seller' ANd `value`=1 AND good_id={$v['good_id']}";
               // $ut->fileRecorder($sql2);
                $gc=$db->ArrayQuery($sql2);
                $group_code=$gc[0]['group_code'];
                $sql3="SELECT `value` from `inquiries_meta` where `key`='export_inq_seller_name' AND group_code='{$group_code}' AND `inquiry_id`={$inq_id}";
              //  $ut->fileRecorder('sssqql3:'.$sql3);
                $res3=$db->ArrayQuery($sql3);
                $seller_code=$res3[0]['value'];
                $curr_date=date('Y-m-d H:i:s');
          
                if(empty($v['good_comment'])){
             
                    $get_last_confirm_comment="SELECT * FROM `inquiry_comments` where `good_id` = {$v['good_id']} AND sender={$_SESSION['userid']} AND `message` IS NOT NULL order by RowID ASC";
                    $comment_res=$db->ArrayQuery($get_last_confirm_comment);
                    $comment=$comment_res[0]['message'];
                    
                }
                else{
                   
                   $comment=$v['good_comment'];
               }
                $insert_sql="INSERT INTO `inquiry_comments` (`inq_id`,`good_id`,`group_id`,`message`,`sender`,`reg_date`,`reason`,`selected_seller`,`workflow_id`)VALUES(
                    '{$inq_id}','{$v['good_id']}','{$group_code}','{$comment}',{$_SESSION['userid']},'{$curr_date}',1,{$seller_code},{$workflow_id}
                    )";
                    $res=$db->Query($insert_sql);
            }

       }

    }
    public function set_inq_archive($inq_id){
        $db=new DBi();
        $ut=new Utility();
        $sql="UPDATE `inquiries` SET `status`=10 WHERE RowID={$inq_id}";
        $res=$db->Query($sql);
        if($res){
            return true;
        }
        else{
            return false;
        }
    }
    public function save_inquiry_detailes($inq_good_name, $inq_good_quantity_unit, $inq_good_quantity,$inq_good_buy_request_num,$inq_good_desc,$buy_base_desc,
                                            $export_inq_seller_name, $export_good_quantity, $export_unit_price,
                                            $export_pay_method, $export_rent_inside,$export_deliver_method,
                                            $export_rent_outside, $seller_group_code,$inq_id,$export_description,
                                            $is_vat,$temp_seller_name){
        

        $db=new DBi();
        $ut=new Utility();
        $inq_vat_percent = $this->get_inq_seller_data('vat_percent');
        $inq_rate_percent = $this->get_inq_seller_data('market_rate');
        $vat_amount=$is_vat==1?($export_good_quantity*$export_unit_price*$inq_vat_percent)/100:0;
        $ut->fileRecorder("seller_group_code:".$seller_group_code);
        if(!empty($seller_group_code)){// update ------
            $group_code=$seller_group_code;
            $delete_old_paymethods="DELETE FROM `inq_pay_method` where `group_id`='{$group_code}'";
            $res=$db->Query($delete_old_paymethods);

            $delete_old_deliver_methods="DELETE FROM `inq_deliver_method` where `group_id`='{$group_code}'";
            $res=$db->Query($delete_old_deliver_methods);

        }
        else
        {//insert -------------------------
            $group_code = uniqid();
        }

        // if(!empty($seller_group_code)){// update ------
        //     $group_code=$seller_group_code;
        //     $delete_old_paymethods="DELETE FROM `inq_pay_methode` where `group_id`={$group_code}";
        //     $res=$db->Query($delete_old_paymethods);

        // }
        // else
        // {//insert -------------------------
        //     $group_code = uniqid();
        // }

            $pay_insert_value = "";
           
            foreach($export_pay_method as $key=>$value)
            {
                $pay_date = $ut->jal_to_greg($value['pay_cheque_date']);
                $pay_insert_value.="('{$value['pay_method']}','{$value['pay_cash_section']}','{$value['pay_type']}','{$pay_date}',{$inq_id},'{$group_code}'),";
                
            }
            $pay_insert_value=rtrim($pay_insert_value,',');
            foreach($export_deliver_method as $key_d=>$value_d)
            {
                $deliver_date = $ut->jal_to_greg($value_d['deliver_date']);
                $deliver_insert_value.="('{$value_d['deliver_method']}','{$value_d['deliver_part_amount']}','{$deliver_date}',{$inq_id},'{$group_code}'),";
            }
            $deliver_insert_value=rtrim($deliver_insert_value,',');
            
        $insert_pay_method="INSERT INTO `inq_pay_method` (`pay_method`,`pay_amount`,`pay_type`,`pay_date`,`inq_id`,`group_id`) VALUES {$pay_insert_value}";
        $res_p=$db->Query($insert_pay_method);
            $ut->fileRecorder($insert_pay_method);
        $insert_deliver_method="INSERT INTO `inq_deliver_method` (
        `deliver_method`,
        `deliver_amount`,
        `deliver_date`,
        `inq_id`,
        `group_id`
        ) VALUES {$deliver_insert_value}";
        $res_d=$db->Query($insert_deliver_method);
       
       if(!empty($seller_group_code))
       {
            $flag=true;
           if(!empty($inq_good_name)){
               $sql_update1="UPDATE `inquiries_meta` set `value` = '{$inq_good_name}' WHERE `key`='inq_good_id' AND group_code='{$seller_group_code}';";
                $res1=$db->Query($sql_update1);
                if(!$res1){
                    $flag=false;
                }
           }

           if(!empty($inq_good_quantity_unit)){
               $sql_update2="UPDATE `inquiries_meta` set `value` = '{$inq_good_quantity_unit}' WHERE `key`='inq_good_quantity_unit' AND group_code='{$seller_group_code}';";
               $res2=$db->Query($sql_update2);
               if(!$res2){
                   $flag=false;
               }
           }

           if(!empty($inq_good_quantity)){
               $sql_update3="UPDATE `inquiries_meta` set `value` = '{$inq_good_quantity}' WHERE `key`='inq_good_quantity' AND group_code='{$seller_group_code}';";
               $res3=$db->Query($sql_update3);
               if(!$res3){
                   $flag=false;
               }
           }

           //--------------------------------------------------------------------
            if(!empty($inq_good_buy_request_num)){
               $sql_update3_1="UPDATE `inquiries_meta` set `value` = '{$inq_good_buy_request_num}' WHERE `key`='inq_good_buy_request_num' AND group_code='{$seller_group_code}';";
               $res3_1=$db->Query($sql_update3_1);
               if(!$res3_1){
                   $flag=false;
               }
           }

            if(!empty($inq_good_desc)){
               $sql_update3_2="UPDATE `inquiries_meta` set `value` = '{$inq_good_desc}' WHERE `key`='inq_good_desc' AND group_code='{$seller_group_code}';";
               $res3_2=$db->Query($sql_update3_2);
               if(!$res3_2){
                   $flag=false;
               }
           }

            if(!empty($buy_base_desc)){
               $sql_update3_3="UPDATE `inquiries_meta` set `value` = '{$buy_base_desc}' WHERE `key`='buy_base_desc' AND group_code='{$seller_group_code}';";
               $res3_3=$db->Query($sql_update3_3);
               if(!$res3_3){
                   $flag=false;
               }
           }

           //--------------------------------------------------------------------
           if(!empty($export_inq_seller_name)){
               $sql_update4="UPDATE `inquiries_meta` set `value` = '{$export_inq_seller_name}' WHERE `key`='export_inq_seller_name' AND group_code='{$seller_group_code}';";
               $res4=$db->Query($sql_update4);
               if(!$res4){
                   $flag=false;
               }
           }

           if(!empty($export_good_quantity)){
               $sql_update5="UPDATE `inquiries_meta` set `value` = '{$export_good_quantity}' WHERE `key`='export_good_quantity' AND group_code='{$seller_group_code}';";
               $res5=$db->Query($sql_update5);
               if(!$res5){
                   $flag=false;
               }
           }

           if(!empty($export_unit_price)){
               $sql_update6 = "UPDATE `inquiries_meta` set `value` = '{$export_unit_price}',`sub_key` = 'ریال' WHERE `key` = 'export_unit_price' AND group_code = '{$seller_group_code}'";
               $res6 = $db->Query($sql_update6);
               if(!$res6){
                   $flag=false;
               }
           }

           if(count($export_pay_method)>0)
           {
               $sql_update7="UPDATE `inquiries_meta` set `value` = '{$export_pay_method['pay_method']}',description='{}' WHERE `key`='export_pay_method' AND group_code = '{$seller_group_code}'";
               $res7=$db->Query($sql_update7);
               if(!$res7){
                   $flag=false;
               }
           }

           if(!empty($export_pay_detailes)){
               $sql_update8="UPDATE `inquiries_meta` set `value` = '{$export_pay_detailes}' WHERE `key`='export_pay_detailes' AND group_code='{$seller_group_code}';";
               $res8=$db->Query($sql_update8);
               if(!$res8){
                   $flag=false;
               }
           }
           if(!empty($export_rent_inside)){
               $sql_update9="UPDATE `inquiries_meta` set `value` = '{$export_rent_inside}',`sub_key`='ریال'  WHERE `key`='export_rent_inside' AND group_code='{$seller_group_code}';";
               $res9=$db->Query($sql_update9);
               if(!$res9){
                   $flag=false;
               }
           }
           if(!empty($export_rent_outside)){
               $sql_update10="UPDATE `inquiries_meta` set `value` = '{$export_rent_outside}' ,`sub_key`='ریال'  WHERE `key`='export_rent_outside' AND group_code='{$seller_group_code}';";
               $res10=$db->Query($sql_update10);
               if(!$res10){
                   $flag=false;
               }
           }

           if(!empty($export_rent_outside)){
               $sql_update11="UPDATE `inquiries_meta` set `value` = '{$export_term_payment}' ,`sub_key`='ریال'  WHERE `key`='export_term_payment' AND group_code='{$seller_group_code}';";
               $res11=$db->Query($sql_update11);
               if(!$res11){
                   $flag=false;
               }
           }
           if(!empty($export_rent_outside)){
               $sql_update13="UPDATE `inquiries_meta` set `value` = '{$export_description}' ,`sub_key`='ریال'  WHERE `key`='export_description' AND group_code='{$seller_group_code}';";
               $res13=$db->Query($sql_update13);
               if(!$res13){
                   $flag=false;
               }
           }
           if(!empty($temp_seller)){
            $sql_update14="UPDATE `inquiries_meta` set `value` = '{$temp_seller}'   WHERE `key`='temp_seller_name' AND group_code='{$seller_group_code}';";
            $res14=$db->Query($sql_update14);
            if(!$res14){
                $flag=false;
            }
        }
               $sql_update10_1="UPDATE `inquiries_meta` set `value` = '{$is_vat}'   WHERE `key`='is_vat' AND group_code='{$seller_group_code}';";
               $res10_1=$db->Query($sql_update10_1);
               if(!$res10_1){
                   $flag=false;
               }
               $sql_update10_2="UPDATE `inquiries_meta` set `value` = '{$vat_amount}' ,`sub_key`='ریال'  WHERE `key`='vat_amount' AND group_code='{$seller_group_code}';";
               $res10_2=$db->Query($sql_update10_2);
               if(!$res10_2){
                   $flag=false;
               }

           if($flag==true){
               return 1;
           }
           else{
               return -2;
           }
       }
       else {
           $values = "('{$inq_id}','{$inq_good_name}','inq_good_id','','{$inq_good_name}','کد کالا','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','inq_good_quantity_unit','','{$inq_good_quantity_unit}','واحد کالا','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','inq_good_quantity','','{$inq_good_quantity}','تعداد ','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','inq_good_buy_request_num','','{$inq_good_buy_request_num}','شماره درخواست خرید ','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','inq_good_desc','','{$inq_good_desc}','توضیحات کالا ','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','buy_base_desc','','{$buy_base_desc}',' توضیحات مبنای خرید کالا ','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','is_vat','','{$is_vat}','محاسبه ارزش افزوده','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','vat_amount','','{$vat_amount}','مقدار ارزش افزوده','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','inq_vat_percent','','{$inq_vat_percent}','درصد ارزش افزوده ','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','inq_rate_percent','','{$inq_rate_percent}','درصد سود ','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','export_inq_seller_name','','{$export_inq_seller_name}','نام فروشنده','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','temp_seller_name','','{$temp_seller}',' فروشنده ثبت نشده','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','export_good_quantity','','{$export_good_quantity}','تعداد قابل درخواست','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','export_unit_price','ریال','{$export_unit_price}','قیمت واحد','{$group_code}'),";
                     
                   // $values.=$insert_value;
                     
                     $values.="('{$inq_id}','{$inq_good_name}','export_rent_inside','ریال','{$export_rent_inside}','کرایه حمل داخلی','{$group_code}'),
                     ('{$inq_id}','{$inq_good_name}','export_rent_outside','ریال','{$export_rent_outside}','کرایه حمل خارجی','{$group_code}'),
                 
                     ('{$inq_id}','{$inq_good_name}','export_description','','{$export_description}','توضیحات','{$group_code}')";
                     //('{$inq_id}','{$inq_good_name}','export_pay_detailes','','{$export_pay_detailes}','جزییات روش پرداخت','{$group_code}'),

           $insert_sql = "INSERT INTO `inquiries_meta` (`inquiry_id`,`good_id`,`key`,`sub_key`,`value`,`description`,`group_code`) VALUES {$values}";
           $ut->fileRecorder($insert_sql);
           $res = $db->Query($insert_sql);
           if ($res) {
               return $group_code;
               unset($_SESSION['inquiry_id']);
           } else {
               return -1;
           }
       }
    }

    public function get_inq_seller_data($key){
        $db=new DBi();
        $sql="SELECT `value` FROM `inquiry_options`  where `key`='{$key}'";
        $res=$db->ArrayQuery($sql);
        return $res[0]['value'];
    }
    public function get_fixed_inq_data(){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT `key`,`value` FROM `inquiry_options` ";
        $res=$db->ArrayQuery($sql);
        $final_array=[];
        foreach($res as $array_value){
           $final_array[$array_value['key']]= $array_value['value'];
        }
        return $final_array;
    }
    public function  print_var_name($var)
    {
        foreach($GLOBALS as $var_name => $value)
        {
            if ($value === $var) {
                return $var_name;
            }
        }
        return false;
    }
    public function inq_get_good_detailes($inq_id){
        $db = new DBi();
        $ut = new Utility();
        $sql_g="SELECT good_id FROM inquiries_meta  where inquiry_id={$inq_id}  AND status=1 GROUP BY good_id";

        $g_res=$db->ArrayQuery($sql_g);
        $good_array=[];
        $final_array_good=[];
        foreach($g_res as $res_key=>$res_value){
            $good_array[]=$res_value['good_id'];
        }
        for($i=0;$i<count($good_array);$i++){
            //$get_good_sql="SELECT * FROM inquiries_meta where good_id={$good_array[$i]} AND inquiry_id={$inq_id} AND status=1";
            $get_good_sql="SELECT m.* ,g.title FROM inquiries_meta as m LEFT JOIN inquiry_good  as g on g.code=m.good_id where m.good_id={$good_array[$i]} AND m.inquiry_id={$inq_id} AND m.status=1";
            $res=$db->ArrayQuery($get_good_sql);
           // $ut->fileRecorder($get_good_sql);
           // $ut->fileRecorder($res);
            $array_handler=[];
            foreach($res as $row){
                switch($row['key']){
                    case "inq_good_id":
                    case "inq_good_quantity_unit":
                    case "inq_good_quantity":
                    case "inq_good_buy_request_num":
                    case "inq_good_desc":
                    case "buy_base_desc":
                    $array_handler[$row['key']]=$row['value'];
                    break;
                }
                $array_handler['title']=$row['title'];
            }
            $final_array_good[]=$array_handler;
        }
        $f_array=[];
        foreach ($final_array_good as $k=>$value){
            if(count($value)>0){
                $f_array[]=$value;
            }
        }
        return $f_array;
    }

    public function delete_all_form_inq($inq_id,$good_id){
        $db = new DBi();
        $ut = new Utility();
        $sql="UPDATE inquiries_meta set `status`=0 where `inquiry_id` ={$inq_id} AND `good_id`='{$good_id}'";
       $ut->fileRecorder($sql);
        $res=$db->Query($sql);
        if($res){
            return 1;
        }
        else{
            return 0;
        }
    }

    public function  create_description_seller($inq_id){
        $db = new DBi();
        $ut = new Utility();
        $sql_g="SELECT group_code,good_id FROM inquiries_meta  where inquiry_id={$inq_id} AND status=1 GROUP BY good_id ";
        $g_res=$db->ArrayQuery($sql_g);
        if(count($g_res)==0){
            return -1;
        }
        $group_array=[];
        $handler_array=[];
        foreach($g_res as $res_key=>$res_value){
            $group_array[]=$res_value['good_id'];
        }
        $group_ids="SELECT group_code from inquiries_meta where inquiry_id={$inq_id} group by group_code";
        $id_res=$db->ArrayQuery($group_ids);
        $in_id_statement=[];
        foreach($id_res as $res_k=>$res_value){
            $in_id_statement[]=$res_value['group_code'];
        }

        $temp_array=$group_array;
        foreach($group_array as $key=>$value){
            foreach($in_id_statement as $g_i=>$g_v) {
                $sql2 = "select * from inquiries_meta where group_code ='{$g_v}' AND good_id={$value}";

                $res2 = $db->ArrayQuery($sql2);
                if(count($res2)>0){
                    $handler_array[$value][] = $res2;
                }
            }
        }
        foreach($handler_array as $handler_array_index=>$handler_array_value)
        {
            $ut->fileRecorder('handler_array_index');
            $ut->fileRecorder($handler_array_index);
            $ut->fileRecorder($handler_array_value);
            $hand2=[];
            foreach($handler_array_value as $k1=>$v1)
            {
                    $hand3=[];
                    $hand4=[];
                    $hand5=[];
                    foreach($v1 as $k2=>$v2){
                        
                        // if($v2['key']=='pay_method'){
                        //     $hand5['pay_method'][]=$v2['value'];
                        // }
                        // elseif($v2['key']=='pay_cheque_date'){
                        //     $hand5['pay_date'][]=$v2['value'];
                        // }
                        // elseif($v2['key']=='pay_cash_section'){
                        //     $hand5['pay_amount'][]=$v2['value'];
                        // }

                        // elseif($v2['key']=='deliver_method'){
                        //     $hand4['deliver_method'][]=$v2['value'];
                        // }
                        // elseif($v2['key']=='deliver_date'){
                        //     $hand4['deliver_date'][]=$v2['value'];
                        // }
                        // elseif($v2['key']=='deliver_part_amount'){
                        //     $hand4['deliver_amount'][]=$v2['value'];
                        // }
                        // else{
                            $hand3[$v2['key']]=$v2['value'];
                        // }
                        // $hand3['pay_info']=$hand5;
                        // $hand3['deliver_info']=$hand4;
                    }
                $hand3['group_code']=$v2['group_code'];
                $hand3['inquiry_id']=$v2['inquiry_id'];
                $hand3['good_id']=$v2['good_id'];
               
                $hand2[]=$hand3;
            }
            if(count($hand2)){$final_array[$handler_array_index]=$hand2;}
        }
        $sql_pay="SELECT * FROM inq_pay_method where ";
        $html="";
        $html="<div class='seller_description'>
                <div>
                    <p style='font-family: IranSans;color:mediumblue'> &#10004; کلیه قیمت ها و هزینه حمل ها به ریال می باشد</p>
                    <p style='font-family: IranSans;color:mediumorchid'>&#10004;  فرمول محاسبه نرم افزار=(<span>مبلغ چک</span>*<span>نرخ سود</span>*<span> مدت باز پرداخت</span>)+<span>مبلغ چک</span>+<span>مبلغ نقدی</span>+<span>مبلغ ارزش افزوده </span>+<span>کرایه حمل داخل شهری</span>+<span>کرایه حمل بین شهری</span></p>
                    <p style='display: flex; gap: 10px;color:rgba(185, 225, 185, 0.7);'>&#10004;<span style='display:block;width:30px;height:30px;border-radius:100%;background: rgba(185, 225, 185, 0.7)'></span> <span>انتخاب شده توسط سیستم</span> </p>
                </div>";

        $inner_html="";
        $inner_htmlheader="<tr>
                            <td>انتخاب</td>
                            <td>شرح کالا</td>
                            <td>مقدار مورد درخواست</td>
                            <td>نام فروشنده</td>
                            <td>قیمت واحد</td>
                            <td>قیمت  تمام شده(با احتساب کرایه حمل)</td>
                           <!-- <td>مدت باز پرداخت به روز</td>
                            <td>مبلغ پرداخت نقدی</td>
                            <td>مبلغ پرداخت  چک</td>-->
                            <td>کرایه حمل </td>
                            <td>توضیحات</td>
                            
                        </tr>";
        $k=0;
        $chart_array=[];
        $final_chart_array=[];
        $final_formula_array=[];
        $temp_seller_query="SELECT `value` FROM inquiry_options where `key`='temp_seller_id'";
        $temp_seller_res=$db->ArrayQuery($temp_seller_query);
        $temp_seller_id=$temp_seller_res[0]['value'];
        foreach($final_array as $group_code=>$arr_value){
            $ut->fileRecorder('group_code:'.$group_code);
            $inner_html="";
            $k++;
            $chart_array=[];
            $k=0;
            $pay_method_array=[];
          
            foreach($arr_value as $arr_index=>$arr_value1){

                if(key($arr_value1)=='pay_method'){
                   
                    $pay_method_array['pay_method'][]=$arr_value1['pay_method'];
                }
                if(key($arr_value1)=='pay_cheque_date'){
                    $pay_method_array['pay_date'][]=$arr_value1['pay_cheque_date'];
                }
                if(key($arr_value1)=='pay_cash_section'){
                    $pay_method_array['pay_amount'][]=$arr_value1['pay_cash_section'];
                }
                $seller_id=$arr_value1['export_inq_seller_name'];
                $seller_name="";
                if($seller_id==$temp_seller_id){
                    $seller_name=$this->get_seller_name($arr_value1['export_inq_seller_name'])."(".$arr_value1['temp_seller_name'].")";
                }
                else{
                    $seller_name=$this->get_seller_name($arr_value1['export_inq_seller_name']);
                }

                $checked=$arr_value1['selected_seller'];
                if(intval($checked)==1){
                    $checked="checked";
                }
                else{
                    $checked="";
                }
                $total=($arr_value1['export_term_payment']/30)*($arr_value1['inq_rate_percent']/100)+$arr_value1['export_term_payment']+$arr_value1['export_cash_section']+$arr_value1['vat_amount']+$arr_value1['export_rent_inside']+$arr_value1['export_rent_outside'];
               // $pay_method_array[]=$arr_value1['pay_method'];
                $final_formula_array[$group_code][]=array("export_term_payment" => $arr_value1['export_term_payment'],
                                                        "inq_rate_percent" => $arr_value1['inq_rate_percent']/100,
                                                        "export_cash_section" => $arr_value1['export_cash_section'],
                                                        "vat_amount" => $arr_value1['vat_amount'],
                                                        "seller_name" => $seller_name,//(intval($arr_value1['export_inq_seller_name'])==intval($temp_seller_id)?$this->get_seller_name($arr_value1['export_inq_seller_name'])."(".$arr_value1['temp_seller_name'].")":$this->get_seller_name($arr_value1['export_inq_seller_name'])),
                                                        "rent_inside" => $arr_value1['export_rent_inside'],
                                                        "rent_outside" => $arr_value1['export_rent_outside'],
                                                        "pay_mount" => $arr_value1['export_pay_method'],
                                                        "vat_percent" => $arr_value1['inq_vat_percent'],
                                                        'pay_info'=>$arr_value1['pay_info'],
                                                        'deliver_info'=>$arr_value1['deliver_info'],
                                                        );

                $chart_array[]=array("label"=>$seller_name,"value"=>$total,'color'=>["rgba(".rand(0,255).",".rand(0,255).",".rand(0,255).",0.6)","#fff"]);
                $m=$this->get_inq_comment($arr_value1['good_id'],$arr_value1['inquiry_id'],$arr_value1['group_code']);
                if($m==true){
                    $class_name="btn btn-success";
                    //$disabled="";
                }
                else{
                    $class_name="btn btn-danger";
                    //$disabled="disabled";
                }
                $value=$total;
                $export_pay_method="";
                $export_cash_section="";
                $export_term_payment="";
                foreach($arr_value1['deliver_info']['deliver_method'] as $key_d=> $value_d){
                    $d_html="";
                    $deliver_method = $arr_value1['deliver_info']['deliver_method'];
                    $deliver_date = $arr_value1['deliver_info']['deliver_date'];
                    $deliver_amount = $arr_value1['deliver_info']['deliver_amount'];
                    $d_html.="<thead><tr><th>تاریخ تحویل</th><th>نحوه تحویل</th><th>مقدار تحویلی</th></tr></thead><tbody>";
                    for($i=0;$i<count($deliver_method);$i++){
                        if($deliver_method[$i]==1){
                            $deliver_method_title='تحویل کالا به صورت یکجا';
                        }
                        if($deliver_method[$i]==2){
                            $deliver_method_title='تحویل بخشی از کالا';
                        }
                        
                        $d_html.="<tr>";
                        $d_html.="<td>".$ut->greg_to_jal($deliver_date[$i])."</td>";
                        $d_html.="<td>".$deliver_method_title."</td>";
                        $d_html.="<td>".$deliver_amount[$i]." ".$this->get_unit($arr_value1['inq_good_quantity_unit'])."</td>";
                        $d_html.="</tr>";
                    }
                    $d_html.="</tbody>";
                }
                $p_html=$this->get_pay_deliver_info_htm($arr_value1['group_code'],"p",'ریال');
                $d_html=$this->get_pay_deliver_info_htm($arr_value1['group_code'],"d",$this->get_unit($arr_value1['inq_good_quantity_unit']));
                //-----------------------------------------------
                // foreach($arr_value1['pay_info']['pay_method'] as $key_p=> $value_p){
                //     $p_html="";
                //     $pay_method = $arr_value1['pay_info']['pay_method'];
                //     $pay_date = $arr_value1['pay_info']['pay_date'];
                //     $pay_amount = $arr_value1['pay_info']['pay_amount'];
                //     $p_html.="<thead><tr><th>تاریخ پرداخت</th><th>نحوه پرداخت</th><th>مبلغ پرداخت</th></tr></thead><tbody>";
                //     for($i=0;$i<count($pay_method);$i++){
                //         if($pay_method[$i]==1){
                //             $pay_method_title='پیش پرداخت';
                //         }
                //         if($pay_method[$i]==2){
                //             $pay_method_title='نقدی';
                //         }
                //         if($pay_method[$i]==2){
                //             $pay_method_title='چک';
                //         }
                //         if($pay_method[$i]==2){
                //             $pay_method_title='نقدی موقع تحویل';
                //         }
                        
                //         $p_html.="<tr>";
                //         $p_html.="<td>".$ut->greg_to_jal($pay_date[$i])."</td>";
                //         $p_html.="<td>".$pay_method_title."</td>";
                //         $p_html.="<td>".$pay_amount[$i]." ریال</td>";
                //         $p_html.="</tr>";
                //     }
                //     $p_html.="</tbody>";
                // }
                
                $deliver_html="";
                $deliver_html.="<div class='row'><fieldset class='col-md-4 col-md-offset-1 fs'><legend>اطلاعات تحویل</legend><div ><table  border='1' class='thead-dark table table-borderd table-striped'>".$d_html."</table></div></fieldset>";
                $deliver_html.="<fieldset class='col-md-4 fs col-md-offset-2'><legend>اطلاعات پرداخت</legend><div ><table  border='1' class='thead-dark table table-borderd table-striped'>".$p_html."</table></div></fieldset></div>";
                $inner_html.="<div class='m-4 card p-4 bg-light'><table border='1' class='table'>".$inner_htmlheader;
                $inner_html.="<tr>
                    <td><input ".$checked." id='".$arr_value1['group_code']."_".$k."' good_id='".$arr_value1['good_id']."' inquiry_id='".$arr_value1['inquiry_id']."' group_code='".$arr_value1['group_code']."' type='radio'  name='select_".$arr_value1['good_id']."' value='".$value."' onmousedown='check_selected_radio(this)'></td>
                    <td>".$this->get_good_name($arr_value1['inq_good_id'])."</td>
                    <td>".$arr_value1['inq_good_quantity']." ".$this->get_unit($arr_value1['inq_good_quantity_unit'])."</td>
                    <td>".$seller_name."</td>
                    <td>".$arr_value1['export_unit_price']." ریال "."</td>
                    <!--<td>".$export_pay_method."</td>
                    <td>".$export_cash_section."</td>
                    <td>".$export_term_payment."</td>-->
                    <td>".(($arr_value1['inq_good_quantity']*$arr_value1['export_unit_price'])+$arr_value1['export_rent_inside']+$arr_value1['export_rent_outside'])."ریال </td>
                    <td>".($arr_value1['export_rent_inside']+$arr_value1['export_rent_outside'])." ریال"."</td>
                    <td>".$arr_value1['export_description']."</td>
                </tr></table>
                <div>".$deliver_html."</div></div>";
                $k++;
            }
            $good_name=$this->get_good_name($group_code);
            $html.="<fieldset id='box_".$group_code."' style='background:#80808029;border: 2px solid gray;border-radius: 10px;padding: 10px;width:100%;border:2px solid rgb(".rand(0,255).",".rand(0,255).",".rand(0,255).")'><legend style='color:green;font-size:1rem;width: auto;padding: 5px'>$good_name</legend>";
            $html.="<div>
                       <!-- <button title='مشاهده نمودار' style='color:blue' class='btn bg-light' type='button' onclick='display_chart(this)'>نمایش نمودار مقایسه ای </button>-->
                        <!--<button title='نمایش جزییات محاسبه  ' style='color:blue' class='btn bg-light' type='button' onclick='display_calc_detailes(this)'> نمایش جزییات محاسبه</button>
                        <button title='سوابق انتخاب فروشنده  '  style='color:blue' class='btn bg-light' type='button' onclick='display_selected_seller_history(this,".$arr_value1['group_code'].",".$arr_value1['good_id'].",".$arr_value1['inquiry_id'].")'>نمایش  سوابق انتخاب فروشنده</button>-->
                        <button title='مدیریت پیوست ها' style='color:blue' class='btn bg-light' type='button' onclick='display_inq_attachments(".$arr_value1['good_id'].",".$arr_value1['inquiry_id'].")'>مدیریت پیوست ها </button>
                    </div>";
            $html.=$inner_html;
            
            $html.="</fieldset> ";
            $final_chart_array[$group_code]=$chart_array;
        }
        return json_encode(array('html'=>$html,'chart_json'=>$final_chart_array,'formula_detailes'=>$final_formula_array));
    }

    public function get_inq_groups(){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT `code`,`description` FROM `inquiry_status` where `group`='inq_group'";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function get_user_sign($userid){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT * FROM `users` where RowID={$userid}";
        $res=$db->ArrayQuery($sql);
        $user_array=array('sign'=>$res[0]['signature'],'fullname'=>$res[0]['fname']." ".$res[0]['lname']);
       // $ut->fileRecorder($user_array);
        return $user_array;
    }

    public function get_inquires_options($key){
        $db=new DBi();
        $sql="SELECT `value` from `inquiry_options` where `key`='{$key}'";
        $res=$db->ArrayQuery($sql);
        return $res[0]['value'];
    }

    public function get_inquiry_pay_deliver_method($group_id,$info_title,$unit,$event_array=[]){
        $db = new DBi();
        $ut = new Utility();
        if($info_title=="p"){
            $sql = "SELECT `key`,`value`,`description` FROM  `inquiries_meta` where `group_code` ='{$group_id}' AND `key` in ('pay_method','pay_cheque_date','pay_cash_section')";
            $res = $db->ArrayQuery($sql);
            $btn_html = "";
            if(count($event_array)>0){
                $btn_html = "<td>عملیات</td>";
            }
            $html='<table border="1"  class="table table-bordered" style="border-collapse:collapse;width:100%"><thead class="table-dark"><tr><td>روش پرداخت</td><td>سررسید پرداخت</td><td>مبلغ پرداخت</td>'.$btn_html.'</tr></thead>';
            $pay_date=[];
            $pay_method=[];
            $pay_amount=[];
           
            foreach($res as $key=>$value){
                if($value['key']=='pay_method'){
                    $pay_method[]=$value['value'];
                }
                if($value['key']=='pay_cheque_date'){
                    $pay_date[]=$value['value'];
                }
                if($value['key']=='pay_cash_section'){
                    $pay_amount[]=$value['value'];
                }
            }
            $btn_html_op="";
            for($p=0;$p<count($pay_method) ;$p++){
                $arg_array=array('group_code'=>$event_array[1],"pay_method"=>$pay_method[$p],"pay_date"=>str_replace("/","-",$ut->greg_to_jal($pay_date[$p])),'pay_amount'=>$pay_amount[$p]);
                if(count($event_array)>0){
                    $btn_html_op = "<td><button class='btn btn-success' onclick='".$event_array[0]."(".json_encode($arg_array).")'>".$event_array[2]."</button></td>";
                }
                
                $html.="<tr><td>".$this->deliver_pay_description($pay_method[$p],'p')."</td><td>".$ut->greg_to_jal($pay_date[$p])."</td><td>".number_format($pay_amount[$p])." ".$unit." </td>".$btn_html_op."</tr>";
            }
          // $html_final="<tbody>".$html."</tbody>";
        }
        if($info_title=='d'){
            $array_handler0=[];
            $sql="SELECT `RowID`,`key`,`value`,`description` FROM  `inquiries_meta` where `group_code` ='{$group_id}' AND `key` in ('deliver_method','deliver_date','deliver_part_amount')";
            $res2=$db->ArrayQuery($sql);
            $array_handler0=[];
            $html='<table border="1"  class="table table-bordered" style="border-collapse:collapse;width:100%"><tr><td>نحوه تحویل</td><td>تاریخ تحویل </td><td> مقدار تحویلی</td></tr>';
            $deliver_date=[];
            $deliver_method=[];
            $deliver_amount=[];
           
            foreach($res2 as $key=>$value){
                if($value['key']=='deliver_method'){
                    $deliver_method[]=$value['value'];
                }
                if($value['key']=='deliver_date'){
                    $deliver_date[]=$value['value'];
                }
                if($value['key']=='deliver_part_amount'){
                    $deliver_amount[]=$value['value'];
                }
            }
  
            for($d=0;$d<count($deliver_method);$d++){
                $html.="<tr><td>".$this->deliver_pay_description($deliver_method[$d],'d')."</td><td>".$ut->greg_to_jal($deliver_date[$d])."</td><td>".$deliver_amount[$d]." ".$unit."</td></tr>";
            }
        }
        
        $html.="</table>";
        return $html;
    }

    public function deliver_pay_description($value,$type){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT `code`,`description` FROM `inquiry_status` where `code`={$value} ";
        if($type=="p"){
            $sql.='AND  `group`="pay_method"';
        }
        if($type=="d"){
            $sql.='AND  `group`="deliver_method"';
        }
        $res=$db->ArrayQuery($sql);
        $ut->fileRecorder($type);
        return $res[0]['description'];
    }

    public function print_inq($inq_id,$print_paraf){
        $db = new DBi();
        $ut = new Utility();
        $get_inq_header_sql = "SELECT * FROM `inquiries` where RowID={$inq_id}";
        $res_header = $db->ArrayQuery($get_inq_header_sql);
        $inq_date = $ut->greg_to_jal($res_header[0]['inquiry_date']);
        $inq_number = $res_header[0]['inquiry_code'];
        $inq_creator=$res_header[0]['creator_id'];
        $temp_seller_id=$this->get_inquires_options('temp_seller_id');

         $get_good_ids_sql = "SELECT good_id FROM `inquiries_meta` where inquiry_id={$inq_id}  GROUP BY good_id";
        $res_good_ids = $db->ArrayQuery($get_good_ids_sql);
        $good_ids_array = [];
        foreach ($res_good_ids as $key => $value) {
            $good_ids_array[] = $value['good_id'];
        }
        $print_array=[];
        foreach($good_ids_array as $k=>$v){
            $group_array=$this->get_group_array($v,$inq_id);
           
            $print_array[$v]=$group_array;
        }
       // $ut->fileRecorder($print_array);

       $get_group_code="SELECT `group_code`  FROM `inquiries_meta` where  inquiry_id={$inq_id}  Group By `group_code`";
        $ut->fileRecorder($get_group_code);
        $gc_res=$db->ArrayQuery($get_group_code);
        $html="";
        foreach($print_array as $seller_key=>$seller_value)
        {
            //-------------------------------------------------------------------------------
           // $ut->fileRecorder('kkkkkkkkkkkkkkkkk:'.$seller_key);
            $comment_sql = "SELECT c.`message`,c.`sender` FROM `inquiry_comments` as c LEFT JOIN `inquiry_workflow` as w on c.`workflow_id`=w.`RowID`  where c.`inq_id`={$inq_id} AND c.`good_id`={$seller_key} AND w.`status`=1 group By c.`sender` order by c.`RowID` DESC";
            $comment_res = $db->ArrayQuery($comment_sql);
            $buy_agent_array = $ut->get_full_access_users(10);
            $high_managers = $ut->get_full_access_users(14);
            $vice_president_commerce_array = $ut->get_full_access_users(13);
            $manager_commerce_array =$ut->get_full_access_users(12);
            $superviser_inquiry_users = $ut->get_full_access_users(11);
            $agent_info = [];
            $agent_comment = "";
            $hm_comment = "";
            $hm_info = "";
            $vpc_comment = "";
            $vpc_info = "";
            $mc_comment = "";
            $mc_info = "";
            $si_ifo="";
            $si_comment="";
           
            foreach($comment_res as $c_key=>$c_value){
                if(in_array($c_value['sender'],$buy_agent_array)){//اطلاعات و امضای کارشناس خرید
                    $agent_comment = $c_value['message'];
                    $agent_info=$this->get_user_sign($c_value['sender']);
                }
                if(in_array($c_value['sender'],$superviser_inquiry_users)){//اطلاعت و امضای سرپرست خرید
                    $si_comment = $c_value['message'];
                    $si_info=$this->get_user_sign($c_value['sender']);
                }
                if(in_array($c_value['sender'],$manager_commerce_array)){//اطلاعات و امضا  مدیر بازرگانی خرید
                    $mc_comment = $c_value['message'];
                    $mc_info=$this->get_user_sign($c_value['sender']);
                }
                if(in_array($c_value['sender'],$vice_president_commerce_array)){//اطلاعات و امضا  معاونت بازرگانی 
                    $vpc_comment = $c_value['message'];
                    $vpc_info=$this->get_user_sign($c_value['sender']);
                }
                if(in_array($c_value['sender'],$high_managers)){//اطلاعات و امضا  مدیر عامل  
                    $hm_comment = $c_value['message'];
                    $hm_info=$this->get_user_sign($c_value['sender']);
                }
            }
            $logo_img=file_exists("../images/inq_logo.png")?"../images/inq_logo.png":'../images/inq_logo.png';
        
            //-------------------------------------------------------------------------------
              $html .= '<style type="text/css" media="print">@page { size:legal landscape;margin:0;} .print_inq:{page-break-after:always}</style>
            <div class="print_inq" style="font-family:IranSans;font-size:11px;width: 100% ;direction:rtl;padding: 0;margin: 0;border:2px solid #000;page-break-after:always">
                <header style=" border:2px solid ;display: flex ;justify-content: center;">
                    <div style="width: 20%;height:;border-left:2px solid #000;text-align: center"><img src="'.$logo_img.'"></div>
                    <div style="width: 60%;border-left:2px solid #000;text-align: center;display: flex;align-items: center;justify-content: center">استعلام بها</div>
                    <div style="width: 20%;padding: 10px" >
                        <table >
                            <tr><td>کد فرم :</td><td >F111012</td></tr>
                            <tr><td>سطح تغییرات  :</td><td>1</td></tr>
                            <tr><td> تاریخ تغییرات :</td><td>1401/05/20</td></tr>
                        </table>                    
                    </div>
                </header>
                <div style="display: flex;align-items: center;gap:4rem;border-bottom:1px solid #000;height:1cm">
                    <div><span>تاریخ استعلام  :</span><span>' . $inq_date . '</span></div> 
                    <div><span>شماره  استعلام  :</span><span>' . $inq_number . '</span></div>
                </div>
                <main>';
                $html .= '<fieldset style="border:2px solid gray;border-radius: 10px ;margin: 10px;"><legend style="width: auto;padding-block:20px">' . $this->get_good_name($seller_key) . ' </legend>';
                
                $html.='<table border="1"  class="table table-bordered" style="border-collapse:collapse">';
                $table_header='
                            <thead>
                                <tr>
                                    <th style="width: 10%;height:0.3cm">نام فروشنده</th>
                                    <th style="width:5%;height:0.3cm">تعداد کالا </th>
                                    <th style="width: 10%"> ارائه فاکتور با ارزش افزوده</th>
                                    <th style="width: 10%">(با احتساب کرایه حمل)قیمت تمام شده </th>
                                    
                                    <th style="width: 10%">کرایه حمل</th>
                                    <th style="width: 10%">توضیحات</th>
                                </tr>
                            </thead>
                        <tbody>';
                $counter=0;
                foreach($seller_value as $seller_info_key=>$seller_info){
                   
                    $good_unit=$this->get_unit($seller_info['inq_good_quantity_unit']);
                    $pay_method=$this->get_pay_deliver_info_htm($gc_res[$counter]['group_code'],'p','ریال');
                   // get_pay_deliver_info_htm($group_id,$info_type,$unit="")
                    $ut->fileRecorder($pay_method);
                    $deliver_method=$this->get_pay_deliver_info_htm($gc_res[$counter]['group_code'],'d',$good_unit);
                    $seller_name="";
                   
                    $seller_id = $seller_info['export_inq_seller_name'];
                    if($seller_id == $temp_seller_id){
                       $seller_name = $this->get_seller_name($seller_info['export_inq_seller_name'])." (".$seller_info['temp_seller_name'].")";
                    }
                    else{
                        $seller_name = $this->get_seller_name($seller_info['export_inq_seller_name']);
                    }
                    $sign=$seller_info['is_vat']==0?"&#10004;":"&#10007;";
                    $html.=$table_header;
                    $html.="<tr>
                                <td style='height:0.9cm'>".$seller_name."</td>
                                <td>".$seller_info['export_good_quantity']." ".$good_unit."</td>
                                <td>".$sign."</td>
                               <!-- <td>".number_format($seller_info['export_cash_section']+$seller_info['export_term_payment']+$seller_info['export_rent_inside']+$seller_info['export_rent_outside'])."ریال</td>
                                <td>".number_format($seller_info['export_cash_section'])." ریال</td>
                                <td>".number_format($seller_info['export_term_payment'])."ریال</td>-->
                                <td>".number_format($seller_info['export_good_quantity']*$seller_info['export_unit_price']+$seller_info['export_rent_inside']+$seller_info['export_rent_outside'])." ریال</td>
                                <td>".number_format($seller_info['export_rent_inside']+$seller_info['export_rent_outside'])."ریال"." </td>
                                <td>".$seller_info['export_description']."</td>
                            </tr>";
                    $html.="<tr>
                                <td colspan='3'>
                                    <fieldset><legend>اطلاعات پرداخت </legend>".$pay_method."</fieldset>
                                </td>
                                <td colspan='3'>
                                    <fieldset><legend>اطلاعات تحویل کالا </legend>".$deliver_method."</fieldset>
                                </td>
                            </tr>
                            <tr style='background-color:gray;padding:10px;height:20px;border:none;border-bottom:1px solid'>
                                <td style='background-color:gray;padding:10px'colspan='10'></td>
                            </tr>";
                            $counter++;
                }
                $html.='</tbody></table></fieldset>';
                //---------------------------------------------------
                if(!empty($si_info['sign'])&& file_exists('../Signature/' . $si_info['sign'] )){
                   // $si_sign_img='../Signature/' . $si_info['sign'] ;
                    $si_sign_img='<img width="70" height="70" src="'.'../Signature/' . $si_info['sign'].'">';
                    
                }
                else{
                    $si_sign_img="";
                   // $ut->fileRecorder('si_sign_img2:');
                  //  $ut->fileRecorder($si_sign_img);
                }
                //-----------------------------------------------------------
                if(!empty($mc_info['sign'])&&file_exists('../Signature/' . $mc_info['sign'] )){
                    $mc_sign_img='<img width="70" height="70" src="'.'../Signature/' . $mc_info['sign'].'">';
                }
                else{
                    $mc_sign_img="";
                }
                //--------------------------------------------------
                if(!empty($vpc_info['sign'])&&file_exists('../Signature/' . $vpc_info['sign'] )){
                    $vpc_sign_img='<img width="70" height="70" src="'.'../Signature/' . $vpc_info['sign'].'">';
                }
                else{
                    $vpc_sign_img="";
                }
                //--------------------------------------------------
                if(!empty($hm_info['sign'])&&file_exists('../Signature/' . $hm_info['sign'] )){
                    $hm_sign_img='<img width="70" height="70" src="'.'../Signature/' . $hm_info['sign'].'">';
                    
                }
                else{
                    $hm_sign_img="";
                }
                //-------------------------------------------------------
                $html .= '
                <footer style="bottom: 0;width: 100% ;position:absolute;bottom:0">
                    <table border="1" style="width: 100%">
                    <thead>
                        <tr>
                            <td>این قسمت توسط کارشناس بازرگانی خرید تکمیل می گردد.</td>
                            <td>این قسمت توسط سرپرست بازرگانی خرید تکمیل می گردد.</td>
                            <td>این قسمت توسط مدیر بازرگانی خرید تکمیل می گردد.</td>
                            <td>این قسمت توسط معاونت بازرگانی تکمیل می گردد.</td>
                            <td>این قسمت توسط مدیر عامل تکمیل می گردد.</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                             <td>کارشناس بازرگانی خرید:<br>' . $agent_comment . '<br>' . $agent_info['fullname'] . '<br><img width="70" height="70" src="../Signature/' . $agent_info['sign'] . '"></td>
                             <td>نظر سرپرست بازرگانی خرید :<br>' . $si_comment . '<br>' . $si_info['fullname'] . '<br>'.$si_sign_img.'</td>
                            <td>نظر مدیر بازرگانی خرید :<br>' . $mc_comment . '<br>' . $mc_info['fullname'] . '<br>'.$mc_sign_img.'</td> 
                            <td>نظر معاونت بازرگانی :<br>' . $vpc_comment . '<br>' . $vpc_info['fullname'] . '<br>'.$vpc_sign_img.'</td> 
                            <td>نظر مدیر عامل :<br>' . $hm_comment . '<br>' . $hm_info['fullname'] . '<br>'.$hm_sign_img.'</td>                  
                        </tr>
                    </tbody></table>
                </footer>
           </div>
';
if($print_paraf==1){
   $comment_sql = "SELECT c.*,w.`status`,w.`create_date`,w.`create_time` FROM `inquiry_comments` as c 
                                    LEFT JOIN `inquiry_workflow` as w on c.`workflow_id`=w.`RowID`  
                                    where c.`inq_id`={$inq_id} AND c.`good_id`={$seller_key}  ";
    $paraf_res=$db->ArrayQuery($comment_sql);
    $html .= '<br>
            <div class="print_inq_paraf" style="font-family:IranSans;font-size:11px;width: 100% ;direction:rtl;padding: 0;margin: 0;border:#000;page-break-after:always ">
                <header style=" border:2px solid ;display: flex ;justify-content: center;">
                    <div style="width: 20%;height:;border-left:2px solid #000;text-align: center"><img src="'.$logo_img.'"></div>
                    <div style="width: 60%;border-left:2px solid #000;text-align: center;display: flex;align-items: center;justify-content: center"> پاراف های ثبت شده استعلام بها</div>
                    <div style="width: 20%;padding: 10px" >
                        <table >
                            <tr><td>کد فرم :</td><td >F111012</td></tr>
                            <tr><td>سطح تغییرات  :</td><td>1</td></tr>
                            <tr><td> تاریخ تغییرات :</td><td>1401/05/20</td></tr>
                        </table>                    
                    </div>
                </header>
                <div style="display: flex;align-items: center;gap:4rem;border-bottom:1px solid #000;height:1cm">
                    <div><span>تاریخ استعلام  :</span><span>' . $inq_date . '</span></div> 
                    <div><span>شماره  استعلام  :</span><span>' . $inq_number . '</span></div>
                </div>
                <main>
                <fieldset style="border:2px solid gray;border-radius: 10px ;margin: 10px;"><legend style="width: auto;padding-block:20px">' . $this->get_good_name($seller_key) . ' </legend>
                <table class="table">
                <tr><th>ردیف</th><th>کاربر ثبت کننده</th><th>زمان ثبت </th><th>فروشنده مورد تایید</th><th>توضیحات</th><th>وضعیت</th></tr>';
                $paraf_counter=1;
                foreach($paraf_res as $paraf_key=>$paraf_value){
                    if($paraf_value['status']=='1'){
                        $html_sign="&#10004;";
                    }
                    elseif($paraf_value['status']=='0'){
                        $html_sign="&#10007;";
                    }
                    else{
                        $html_sign=" انتخاب فروشنده توسط ".$ut->get_user_fullname($paraf_value['sender']);
                    }
                    $html.="<tr>
                                <td>".$paraf_counter."</td>
                                <td>".$ut->get_user_fullname($paraf_value['sender'])."</td>
                                <td>".$ut->greg_to_jal($paraf_value['create_date'])." ".$paraf_value['create_time']."</td>
                                <td>".$this->get_seller_name($paraf_value['selected_seller'])."</td>
                                <td>".$paraf_value['message']."</td>
                                <td>".$html_sign."</td>
                                
                            </tr>";
                            $paraf_counter++;
                }   
        
                $html.='</table></fieldset></div></main>';
}

        }
        return $html;
        
    }

    public function delete_inq_file($RowId){
        $db=new DBi();
        $ut=new Utility();
        $this_file="SELECT * FROM inquiry_attachment where RowID={$RowId}";
        $res_file=$db->ArrayQuery($this_file);
        $file_name=$res_file[0]['fileName'];
        $sql="DELETE FROM inquiry_attachment WHERE RowID={$RowId}";
        $res=$db->Query($sql);
        if($res){
            $ut->fileRecorder('inquiry_attachment/'.$file_name);
            unlink(ROOT.'inquiry_attachment/'.$file_name);
            return 1;

        }
        return 0;

    }

    public function get_inq_attachment_list($group_code,$inq_id){
        $db=new DBi();
        $ut=new Utility();
        $status_sql="SELECT * FROM `inquiries` where RowID={$inq_id}";
        $status_res=$db->ArrayQuery($status_sql);
        $creator=$status_res[0]['creator_id'];
        $status=$status_res[0]['status'];
        $last_receiver=$status_res[0]['last_receiver'];
        if($status==1 || $status==0){
            $allow_upload=1;
        }

        $sql="SELECT * FROM inquiry_attachment where `status`=1 AND `inq_id`={$inq_id} AND group_code='{$group_code}'";
        $ut->fileRecorder('attachment:'.$sql);
        $res=$db->ArrayQuery($sql);
        $html="";
        $counter=1;
        if(count($res)>0){
            $html.="<table border='1' class='table table-borderd'><tr><td>ردیف</td><td>عنوان فایل</td><td>مدیریت مستندات</td></tr>";
            foreach($res as $key=>$value){
                $html.=
                    "<tr>
                        <td>".$counter."</td>
                        <td>".$value['fileTitle']."</td>
                        <td style='display: flex;justify-content: space-around;'><a href='inquiry_attachment/".$value['fileName']."' download class='btn btn-success' ><i class='fa fa-download'></i></a>";
                        if($allow_upload==1)
                        {
                            $html.="<button class='btn btn-danger' onclick='delete_inq_file(".$value['RowID'].",".$value['group_code'].",".$value['inq_id'].")'><i class='fa fa-trash'></i></button>";
                        }
                        $html.="</td>

                    </tr>";
                $counter++;

            }
            $html.="</table>";

        }
        else{
            $html.="<p style='color:red'>مستنداتی پیوست نشده است </p>";
        }
        return array('html'=>$html,'allow_upload'=>$allow_upload);
    }

public function attach_inq_file($file,$row_id,$file_title,$group_code,$inq_id){
    $db=new DBi();
    $ut=new Utility();
    $current_date=date('Y-m-d H:i:s');
    if (isset($file) && !empty($file)) {
        $no_files = count($file['name']);
        // for ($i = 0; $i < $no_files; $i++) {
        //     $filepath = $file["tmp_name"][$i];
        //     if ($file["error"][$i] > 0) {  // اگر یک فایل ارور داشت
        //         return -1;
        //     }
        //     if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
        //         return -2;
        //     }
        //    // $format = substr($file['name'][$i], strpos($file['name'][$i], ".") + 1);
        //    $format = pathinfo($file['name'][$i], PATHINFO_EXTENSION);
        //     $ut->fileRecorder('format:');
        //     $ut->fileRecorder($file['name'][$i]);
        //     // if(!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
        //     //     return -3;
        //     // }
        //     $SFile[] = "attach_inq" . rand(0, time()).'.'.$format;
        // } // for()
        foreach($file as $key=>$value){
            if($key=='name'){
                $format = pathinfo($value, PATHINFO_EXTENSION);
                $SFile[] = "attach_inq" . rand(0, time()).'.'.$format;
            }
           
           
        }
    } //  if (isset($files) && !empty($files))
   // $ut=>fileRecorder('2');
    $cnt = count($SFile);
    if (!file_exists('../inquiry')) {
        mkdir('../inquiry_attachment', 0777, true);
    }
    $ut->fileRecorder($group_code);
    for ($i=0;$i<$cnt;$i++) {
        $upload = move_uploaded_file($file["tmp_name"],'../inquiry_attachment/'.$SFile[$i]);
        
        $sql4 = "INSERT INTO `inquiry_attachment` (`inq_id`,`fileName`,`fileTitle`,`createDate`,`uid`,`group_code`) VALUES ({$inq_id},'{$SFile[$i]}','{$file_title}','{$current_date}','{$_SESSION['userid']}','{$group_code}')";
        $ut->fileRecorder($sql4);
        $db->Query($sql4);
    }
    return true;

}

    public function get_group_array($good_id,$inq_id){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT group_code FROM `inquiries_meta` where inquiry_id={$inq_id}  AND good_id={$good_id}   GROUP BY group_code";
        $res=$db->ArrayQuery($sql);
        $array_handler=[];
        foreach($res as $k=>$v){
            $sql2="select `key`,`value` from inquiries_meta where group_code='{$v['group_code']}'";
            $res2=$db->ArrayQuery($sql2);
            $array_handler2=[];
            foreach($res2 as $k2=>$v2){
                $array_handler2[$v2['key']]=$v2['value'];
            }
            $array_handler[]=$array_handler2;
        }

        return $array_handler;
    }
    public function print_inq1($inq_id)
    {
        $db = new DBi();
        $ut = new Utility();
        $get_inq_header_sql = "SELECT * FROM `inquiries` where RowID={$inq_id}";
        $res_header = $db->ArrayQuery($get_inq_header_sql);
        $inq_date = $ut->greg_to_jal($res_header[0]['inquiry_date']);
        $inq_number = $res_header[0]['inquiry_code'];
        $get_good_ids_sql = "SELECT good_id FROM `inquiries_meta` where inquiry_id={$inq_id}  GROUP BY good_id";
        $res_good_ids = $db->ArrayQuery($get_good_ids_sql);
        $good_ids_array = [];
        foreach ($res_good_ids as $key => $value) {
            $good_ids_array[] = $value['good_id'];
        }

        $get_group_code="SELECT `group_code`  FROM `inquiries_meta` where  inquiry_id={$inq_id}  Group By `group_code`";
        $ut->fileRecorder($get_group_code);
        $gc_res=$db->ArrayQuery($get_group_code);

       


        foreach ($good_ids_array as $k => $v)
        {
            $html = "";
            $comment_sql = "SELECT * FROM inquiry_comments where inq_id={$inq_id} AND good_id={$v} group By sender";
            $comment_res = $db->ArrayQuery($comment_sql);
            $inq_worlflow_sql_sign = "SELECT * FROM inquiry_workflow where inquiry_id={$inq_id} AND status=1  group By sender";

            $inq_worlflow_res = $db->ArrayQuery($inq_worlflow_sql_sign);

            $buy_agent_array = [1];
            $high_managers = [4];
            $vice_president_commerce_array = [20];
            $manager_commerce_array = [14];
            $agent_info = [];
            $agent_comment = "";
            $hm_comment = "";
            $hm_info = "";
            $vpc_comment = "";
            $vpc_info = "";
            $mc_comment = "";
            $mc_info = "";


            foreach ($comment_res as $k1 => $v1) {
                if (in_array($v1['sender'], $buy_agent_array)) {
                    $agent_comment = $v1['message'];

                   // $agent_info = $this->get_user_sign($v1['sender']);
                }
                elseif (in_array($v1['sender'], $high_managers)) {
                    $hm_comment = $v1['message'];

                   // $hm_info = $this->get_user_sign($v1['sender']);
                }
                elseif (in_array($v1['sender'], $vice_president_commerce_array)) {
                    $vpc_comment = $v1['message'];

                   // $vpc_info = $this->get_user_sign($v1['sender']);
                }
                elseif (in_array($v1['sender'], $manager_commerce_array)) {
                    $mc_comment = $v1['message'];

                   // $mc_info = $this->get_user_sign($v1['sender']);
                }
            }

            foreach ($inq_worlflow_res as $w_k => $w_v) {
                if (in_array($w_v['sender'], $buy_agent_array)) {
                    $agent_info = $this->get_user_sign($w_v['sender']);
                }
                elseif (in_array($w_v['sender'], $high_managers)) {
                    $hm_info = $this->get_user_sign($w_v['sender']);
                }
                elseif (in_array($w_v['sender'], $vice_president_commerce_array)) {
                    $vpc_info = $this->get_user_sign($w_v['sender']);
                }
                elseif (in_array($w_v['sender'], $manager_commerce_array)) {
                    $mc_info = $this->get_user_sign($w_v['sender']);
                }
            }
        }

        $seller_array = [];
        $group_code_array = [];

        foreach ($good_ids_array as $k => $v) {
            $select_group_code_sql = "SELECT group_code FROM inquiries_meta where good_id={$v} AND inquiry_id={$inq_id} group by group_code";
            $select_group_res = $db->ArrayQuery($select_group_code_sql);
            foreach ($select_group_res as $k3 => $v3) {
                $group_code_array[$v][] = $v3['group_code'];
            }
        }

        foreach ($good_ids_array as $good_array){
            foreach ($group_code_array as $k4 => $v4) {
                $handler_array1 = [];
                foreach ($v4 as $k5 => $v5) {
                    $select_seller_sql = "SELECT * FROM inquiries_meta where  inquiry_id={$inq_id} and group_code='{$v5}'";
                    $select_seller_res = $db->ArrayQuery($select_seller_sql);
                    $handler_array2 = [];
                    foreach ($select_seller_res as $k6 => $v6) {
                        $handler_array3[$v6['key']] = $v6['value'];
                        $handler_array2 = $handler_array3;
                    }
                    $handler_array1[] = $handler_array2;
                }
                $seller_array[$good_array] = $handler_array1;
            }
        }
      
        foreach ($seller_array as $seller_key => $seller_value)
        {
            $html .= '<style type="text/css" media="print">@page { size:legal landscape;margin: 1px;  }</style>
            <div id="print_inq" style="font-family:IranSans;font-size:11px;width: 100% ;direction:rtl;padding: 0;margin: 0;border:2px solid #000;height: 100%;position: fixed">
                <header style=" border:2px solid ;display: flex ;justify-content: center;">
                    <div style="width: 20%;height:;border-left:2px solid #000;text-align: center"><img src="../images/inq_logo.png"></div>
                    <div style="width: 60%;border-left:2px solid #000;text-align: center;display: flex;align-items: center;justify-content: center">استعلام بها</div>
                    <div style="width: 20%;padding: 10px" >
                        <table >
                            <tr><td>کد فرم :</td><td >F111012</td></tr>
                            <tr><td>سطح تغییرات  :</td><td >1</td></tr>
                            <tr><td> تاریخ تغییرات :</td><td>1401/05/20</td></tr>
                        </table>                    
                    </div>
                </header>
                <div style="display: flex;align-items: center;gap:4rem;border-bottom:1px solid #000">
                    <div><span>تاریخ استعلام  :</span><span>' . $inq_date . '</span></div> 
                    <div><span>شماره  استعلام  :</span><span>' . $inq_number . '</span></div>
                </div>
                <main>';
                $html .= '<fieldset style="border:2px solid gray;border-radius: 10px ;margin: 10px;"><legend style="width: auto;padding:10px">' . $this->get_good_name($seller_key).' </legend>';
                $html.='<table class="table table-bordered"><tr><th style="width: 20%">نام فروشنده</th><th style="width: 10%"> محاسبه قیمت با ارزش افزوده </th><th style="width: 10%">قیمت تمام شده </th><th style="width: 10%">مبلغ نقدی</th><th style="width: 10%">مبلغ چک </th><th style="width: 10%">مدت بازپرداخت</th><th style="width: 10%">کرایه حمل</th><th style="width: 20%">توضیحات</th></tr>';
                foreach($seller_value as $seller_info_key=>$seller_info)
                {
                    $sign=$seller_info['is_vat']==0?"&#10004;":"&#10007;";
                    $html.="<tr>
                                <td>".$this->get_seller_name($seller_info['export_inq_seller_name'])."</td>
                                <td>".$sign."</td>
                                <td>".number_format($seller_info['export_cash_section']+$seller_info['export_term_payment']+$seller_info['export_rent_inside']+$seller_info['export_rent_outside'])."ریال</td>
                                <td>".number_format($seller_info['export_cash_section'])." ریال</td>
                                <td>".number_format($seller_info['export_term_payment'])."ریال</td>
                                <td>".$seller_info['export_pay_method']." روز</td>
                                <td>".number_format($seller_info['export_rent_inside']+$seller_info['export_rent_outside'])."ریال"." </td>
                                <td>".$this->$seller_info['export_description']."</td>
                            </tr>";
                }
                $html.='</table></fieldset>';
                $html .= '
                <footer style="position: fixed;bottom: 0;width: 100%">
                    <table border="1" style="width: 100%">
                        <tr>
                            <td>این قسمت توسط کارشناس بازرگانی خرید تکمیل می گردد.</td>
                            <td>این قسمت توسط سرپرست بازرگانی خرید تکمیل می گردد.</td>
                            <td>این قسمت توسط مدیر بازرگانی خرید تکمیل می گردد.</td>
                            <td>این قسمت توسط معاونت بازرگانی تکمیل می گردد.</td>
                            <td>این قسمت توسط مدیر عامل تکمیل می گردد.</td>
                        </tr>
                        <tr>
                            <td>نظر کارشناس بازرگانی خرید خرید :<br>' . $agent_comment . '<br>' . $agent_info['fullname'] . '<br><img width="70" height="70" src="../Signature/' . $agent_info['sign'] . '"></td>
                            <td>نظر سرپرست بازرگانی خرید:<br>' . $agent_comment . '<br>' . $agent_info['fullname'] . '<br><img width="70" height="70" src="../Signature/' . $agent_info['sign'].'"></td>
                             <td>نظر مدیر بازرگانی خرید :<br>' . $mc_comment . '<br>' . $mc_info['fullname'] . '<br><img width="70" height="70" src="../Signature/' . $mc_info['sign'] . '"></td> 
                            <td>نظر معاونت بازرگانی :<br>' . $vpc_comment . '<br>' . $vpc_info['fullname'] . '<br><img width="70" height="70" src="../Signature/' . $vpc_info['sign'] . '"></td> 
                            <td>نظر مدیر عامل :<br>' . $hm_comment . '<br>' . $hm_info['fullname'] . '<br><img width="70" height="70" src="../Signature/' . $hm_info['sign'] . '"></td>                  
                        </tr>
                    </table>
                
                </footer>
        
            </div>';
            }

        return $html;
    }

    public function get_selected_seller_history($good_id,$inquiry_id){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT * FROM inquiry_comments where inq_id='{$inquiry_id}' AND good_id='{$good_id}'";
      //  $ut->fileRecorder($sql);
        $res=$db->ArrayQuery($sql);
        $htm="<div></div><table class='table table-bordered'>";
        $htm.="<tr><td>ردیف</td><td>ارسال کننده</td><td>شرح پیام </td><td>فروشنده</td></tr>";
        $counter=1;
        foreach($res as $key=>$value){
            $htm.="<tr><td>".$counter."</td><td>".$ut->get_user_fullname($value['sender'])."</td><td>".$value['message']."</td><td>".$this->get_seller_name($value['selected_seller'])."</td></tr>";
            $counter++;
        }
        $htm.="</table></div>";
        return $htm;
    }
    public function check_inq_comment_reason_status($good_id,$group_code,$inquiry_id){
      $db=new DBi();
      $ut=new Utility();
      $sql="SELECT reason from inquiry_comments where 
            `inq_id`='{$inquiry_id}' AND `good_id`='{$good_id}' AND `group_id`='{$group_code}'";
      $res=$db->ArrayQuery($sql);
      if(count($res)==0){
          return 0;
      }
      else{
          $reason=0;
          foreach($res as $k=>$value){
              $reason+=$value['reason'];
          }
          if($reason>0){
              return 1;
          }
          else{
              return 0;
          }
      }
    }

    public function get_inquiry_detailes($RowID){
        $db=new DBi();
        $ut=new Utility();
        $sql_inq="SELECT * FROM inquiries where RowID={$RowID}";
        $res_inq=$db->ArrayQuery($sql_inq);
        $html="<div><table class='table '>";
        foreach($res_inq as $key=>$value){
            $html.=
                "<tr>
                    <td>عنوان استعلام</td>
                    <td>کد یکتا</td>
                    <td>تاریخ استعلام</td>
                   
                </tr>";
            $html.=
                "<tr>
                    <td> ".$value['title']."</td>
                    <td> ".$value['inquiry_code']."</td>
                    <td> ".$ut->greg_to_jal($value['inquiry_date'])."</td>
                   
                </tr>";

        }
        $html.='</table>';
        $sql_inq_meta="SELECT group_code FROM `inquiries_meta` where inquiry_id={$RowID} AND status=1 GROUP BY group_code";
        $res_inq_meta=$db->ArrayQuery($sql_inq_meta);
        $group_array=[];
        $display_headers=['inq_good_id'/*,'inq_good_quantity_unit'*/, 'inq_good_quantity', 'export_inq_seller_name', 'export_good_quantity', 'export_unit_price', 'export_pay_method', 'export_pay_detailes', 'export_rent_inside', 'export_rent_outside'];
        foreach($res_inq_meta as $key=>$value){
            $group_array[]=$value['group_code'];
        }
        for($i=0;$i<count($group_array);$i++) {
            $html.='<div class="inq_detailes_parent">';
            $sql_inq_meta_detailes = "SELECT * FROM `inquiries_meta` where inquiry_id={$RowID} AND group_code='{$group_array[$i]}' AND status=1";
            $res = $db->ArrayQuery($sql_inq_meta_detailes);

            foreach ($res as $key2 => $value2) {
                $value=$value2['value'];
                switch($value2['key']){
                   case "inq_good_id":
                       $value=$this->get_good_name($value2['value']);
                       break;
                   case "inq_good_quantity":
                    $value=$this->merg_fileds($group_array[$i],$value);

                        break;
                    case "export_good_quantity":
                        $value=$this->merg_fileds($group_array[$i],$value);

                        break;
                    case "export_inq_seller_name":
                        $value=$this->get_seller_name($value);

                        break;
                    case "export_unit_price":
                    case "export_rent_inside":
                    case "export_rent_outside":
                        $value .=" "."ریال";
                        break;
                    default :
                        $value = $value2['value'];
                        break;
                }
                if(in_array($value2['key'],$display_headers)){
                    $html .= '<div class="inq_detailes_box"><span>' . $value2['description'] . ' : </span><span>' . $value . '</span></div>';
                }
            }
            $html .= "</div>";
        }
        return $html;
    }

    public function inq_final_confirm($inq_id){
       $db=new DBi();
       $ut=new Utility();
      // $sql_get_goods="SELECT good_id FROM `inquiries_meta` WHERE `inquiry_id` ={$inq_id} group by group_";
       $sql_select_seller_sql="SELECT good_id,group_code FROM `inquiries_meta` WHERE `inquiry_id` ={$inq_id} group by group_code";
     //  $ut->fileRecorder('sql1:'.$sql_select_seller_sql);
       $select_seller_res=$db->ArrayQuery($sql_select_seller_sql);
       $group_code_array=[];
       $good_id_array=[];
       foreach($select_seller_res as $key=>$value){
            $group_code_array[]=array('group_code'=>$value['group_code'],'good_id'=>$value['good_id']);
            $good_id_array[]=$value['good_id'];
       }
       $good_id_array=array_unique($good_id_array);
       $selected_good_array=[];
       foreach($group_code_array as $key2=>$value)
       {
            $select_seller="SELECT RowID, good_id FROM `inquiries_meta` where `inquiry_id` ='{$inq_id}' AND group_code='{$value['group_code']}' AND `key`='selected_seller' AND `value`=1";
          //  $ut->fileRecorder('sql2:'.$select_seller);
            $select_res=$db->ArrayQuery($select_seller);
             //$ut->fileRecorder($select_res);
            if(count($select_res)>0){
                $selected_good_array[]=$this->get_good_name($value['good_id']);
            }
       }
       if(count($selected_good_array)==0){
            return 0;
       }

       $sql="update inquiries set status=1 where RowID={$inq_id}";
       $res=$db->Query($sql);
       if($res){
           return true;
       }
       else{
           return false;
       }

    }
    public function get_inq_comment($good_id,$inquiry_id,$group_code){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT `value` from `inquiries_meta` where `good_id`={$good_id} AND `inquiry_id`={$inquiry_id} AND `group_code`='{$group_code}'  AND `key`='inq_comment'";
        $res=$db->ArrayQuery($sql);
        if(count($res[0])>0){
            return true;
        }
        else{
            return false;
        }
    }

    public function display_inq_comment($good_id,$inquiry_id,$group_code){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT `message`,`sender`,`reg_date`,`reason` FROM `inquiry_comments` where `good_id`={$good_id} AND `inq_id`={$inquiry_id} AND `group_id`='{$group_code}' ";

        $res=$db->ArrayQuery($sql);
        $final_array=[];
        foreach($res as $key=>$value){

            $value['reg_date']=$ut->greg_to_jal($value['reg_date']);
            $value['sender']=$ut->get_user_fullname($value['sender']);
            $final_array[]=$value;
        }
        return $final_array;
    }

    public function inq_select_seller($good_id,$group_code,$inquiry_id,$mode=0){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT `value` from inquiries_meta where `inquiry_id` = {$inquiry_id} AND `good_id` = {$good_id} AND `group_code` = '{$group_code}' AND `key`='selected_seller'";
        $res=$db->ArrayQuery($sql);
           if($mode==1){
                $u_sql="update inquiries_meta SET `value`=1 where `key`='selected_seller' AND inquiry_id='{$inquiry_id}' AND good_id={$good_id} ";
                $res=$db->Query($u_sql);
           }

    }

    public function save_inq_comment($good_id,$inquiry_id,$group_code,$comment,$reason=0){

        $db=new DBi();
        $ut=new Utility();
        //$ut->fileRecorder($good_id);
        $current_date=date('Y-m-d H:i:s');
        $seller_sql="SELECT `value` from `inquiries_meta` where `key`='export_inq_seller_name' AND `inquiry_id`='{$inquiry_id}' AND `good_id`='{$good_id}' AND `group_code`='{$group_code}'";
        $seller_res=$db->ArrayQuery($seller_sql);
        $seller_id=$seller_res[0]['value'];
        //---------------------------------
        $seller_select="SELECT * from `inquiries_meta` where `key`='selected_seller' AND `inquiry_id`='{$inquiry_id}' AND `good_id`='{$good_id}' ";
        $res=$db->ArrayQuery($seller_select);
        if(count($res)>0){
           $sql_update="UPDATE `inquiries_meta` SET `group_code`='{$group_code}' where `key`='selected_seller' AND `inquiry_id`='{$inquiry_id}' AND `good_id`='{$good_id}'  ";
           $res=$db->Query($sql_update);

        }
        else{
            $insert_sql="INSERT INTO inquiries_meta(`inquiry_id`,`key`,`value`,`description`,`group_code`,`good_id`)VALUES('{$inquiry_id}','selected_seller','1','انتخاب فروشنده','{$group_code}','{$good_id}')";
            $res=$db->Query($insert_sql);

        }
        //---------------------------------
        $sql="INSERT INTO inquiry_comments (`inq_id`,`good_id`,`group_id`,`message`,`sender`,`reg_date`,`reason`,`selected_seller`) VALUES('{$inquiry_id}','{$good_id}','{$group_code}','{$comment}','{$_SESSION['userid']}','{$current_date}','{$reason}','{$seller_id}')";
        $res=$db->Query($sql);
        if($res){
            return true;
        }
        return false;
    }

    public function  do_edit_inq_message($rowid,$message){
        $db=new DBi();
        $ut=new Utility();
        if(!empty($rowid) && !empty($message)){
            $u_sql="UPDATE `inquiries_meta` set `value`='{$message}' where `key`='inq_comment' AND `RowID` ='{$rowid}'";
           // $ut->fileRecorder($u_sql);
            $res=$db->Query($u_sql);
            if($res){
                return 1;
            }
            else{
                return 0;
            }
        }
        return -1;
    }

    public function get_good_name($good_id){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT title FROM inquiry_good where code={$good_id}";

        $res=$db->ArrayQuery($sql);
        return $res[0]['title'];

    }
    public function get_unit($unit_id){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT description FROM inquiry_good_units where RowID ='{$unit_id}'";

        $res=$db->ArrayQuery($sql);
        return $res[0]['description'];

    }
    public function get_seller_name($seller_id,$more_info=0){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT `RowID`,`Name`,`code`,`accountNumber`,`bankName` FROM `account` where `RowID` ='{$seller_id}'";
       
        $res=$db->ArrayQuery($sql);
        if($more_info==1){
           return array($res[0]['Name'],$res[0]['code'],$res[0]['accountNumber'],$res[0]['bankName'],$res[0]['RowID']); 
           //return array($res[0]['Name'],$res[0]['code']); 
        }
        return $res[0]['Name'];

    }
    public function merg_fileds($group_code,$value1){
        $db=new DBi();
        $ut=new Utility();

        $sql="SELECT * FROM inquiries_meta where `group_code`='{$group_code}'  AND `key`='inq_good_quantity_unit' AND status=1";
        $res=$db->ArrayQuery($sql);
        $value=$this->get_unit($res[0]['value']);
        return $value1." ".$value;
    }

    public function inq_save_comment($inq_id,$inq_group,$msg,$good_id){
        $ut=new Utility();
        $db=new DBi();
        $sql="INSERT INTO `inquiry_comments` (`inq_id`,`good_id`,`group_id`,`message`,`sender`)VALUES('{$inq_id}','{$inq_group}','{$msg}','{$good_id}','{$_SESSION['userid']}')";
        $res=$db->Query($sql);
        if($res){
        return true;}
        else{
            return false;
        }
    }

    public function edit_inquiry($inq_id){
        $db=new DBi();
        $ut=new Utility();
        $select_inq="SELECT * FROM inquiries where RowID={$inq_id}";
        $res_inq=$db->ArrayQuery($select_inq);
        $final_array=[];
        foreach($res_inq as $k=>$value){
            $value['inquiry_date']=$ut->greg_to_jal($value['inquiry_date']);
            $value['inq_created_date']=$ut->greg_to_jal($value['inq_created_date']);
            $final_array[]=$value;
        }
        return $final_array[0];
    }

    public function get_seller_data($inq_id,$good_id){
        $db=new DBi();
        $ut=new Utility();
        $final_array=[];
       // $select_inq_1="SELECT good_id FROM inquiries_meta where inquiry_id={$inq_id} AND good_id= AND `status`=1 GROUP BY good_id";
        //$res_good=$db->ArrayQuery($select_inq_1);
      //  $good_array=[];
       // foreach($res_good as $key=>$value){
        //    $good_array[]=$value['good_id'];
       // }
        $select_inq="SELECT group_code FROM inquiries_meta where inquiry_id={$inq_id} AND good_id={$good_id} AND `status`=1 GROUP BY group_code";
        $res_inq=$db->ArrayQuery($select_inq);
        $array_handler=[];
        $ut->fileRecorder('good_array');
        $ut->fileRecorder($good_array);
        foreach($res_inq as $value){
           // $sql="SELECT * FROM inquiries_meta where group_code={$value['group_code']} AND `status`=1";
            $sql="SELECT m.* ,g.title FROM inquiries_meta as m LEFT JOIN inquiry_good  as g on g.code=m.good_id where m.group_code='{$value['group_code']}' AND m.status=1";
            $res=$db->ArrayQuery($sql);
           
            $array_handler1=[];
            $array_handler2=[];
     
            foreach($res as $seller_info) {
              //  $array_handler[$seller_info['group_code']] = $seller_info['group_code'];
              
                switch($seller_info['key'])
                {
                    case"export_inq_seller_name":
                           $array_handler[$seller_info['group_code']] ['export_inq_seller_name'] = $seller_info['value'];
                        break;
                    case'export_good_quantity':
                           $array_handler[$seller_info['group_code']]['export_good_quantity']= $seller_info['value'];
                        break;
                    case "export_unit_price";
                           $array_handler[$seller_info['group_code']]['export_unit_price'] = $seller_info['value'];
                        break;
                    case "export_pay_method":
                           $array_handler[$seller_info['group_code']]['export_pay_method'] = $seller_info['value'];
                        break;
                    case "export_pay_detailes":
                           $array_handler[$seller_info['group_code']]['export_pay_detailes'] = $seller_info['value'];
                        break;
                    case "export_rent_inside":
                           $array_handler[$seller_info['group_code']]['export_rent_inside'] = $seller_info['value'];
                        break;
                    case "export_rent_outside":
                           $array_handler[$seller_info['group_code']]['export_rent_outside'] = $seller_info['value'];
                        break;
                    case "export_cash_section":
                           $array_handler[$seller_info['group_code']]['export_cash_section'] = $seller_info['value'];
                        break;
                    case "export_term_payment":
                           $array_handler[$seller_info['group_code']]['export_term_payment'] = $seller_info['value'];
                        break;
                    case "export_description":
                           $array_handler[$seller_info['group_code']]['export_description'] = $seller_info['value'];
                        break;
                    case "is_vat":
                           $array_handler[$seller_info['group_code']]['is_vat'] = $seller_info['value'];
                        break;
                    case "vat_amount":
                           $array_handler[$seller_info['group_code']]['vat_amount'] = $seller_info['value'];
                        break;
               
                }
                $get_pay_method="SELECT * FROM `inq_pay_method` where group_id='{$seller_info['group_code']}'";
                $array_handler1=$db->ArrayQuery($get_pay_method);
                foreach($array_handler1 as $p_key=>$p_value){
                    $array_handler1[$p_key]['pay_date']=$ut->greg_to_jal($array_handler1[$p_key]['pay_date']);
                }
                $get_pay_method="SELECT * FROM `inq_deliver_method` where group_id='{$seller_info['group_code']}'";
                $array_handler2=$db->ArrayQuery($get_pay_method);
                    foreach($array_handler2 as $d_key=>$d_value){
                    $array_handler2[$d_key]['deliver_date']=$ut->greg_to_jal($array_handler2[$d_key]['deliver_date']);
                }
                $array_handler[$seller_info['group_code']]['pay']=$array_handler1;
                $array_handler[$seller_info['group_code']]['deliver']=$array_handler2;

            }
        }
            // if(!empty($inq_id)){
            //     if(count($array_handler1)>0){
            //         $array_handler[$seller_info['group_code']]['pay']=$array_handler1;
            //     }
            //     if(count($array_handler2)>0){
            //         $array_handler[$seller_info['group_code']]['deliver']=$array_handler2;
            //     }
            // }
            if(count($array_handler)>0){
                $final_array=$array_handler;
            }
        $ut->fileRecorder('[final_array');
        $ut->fileRecorder($final_array);
        return $final_array;
    }
    public function delete_seller_buyer_row($good_id,$inq_id){
       $db=new DBi();
       $del_sql="UPDATE inquiries_meta  set status=0 where good_id={$good_id} AND inquiry_id={$inq_id}";
       $res=$db->ArrayQuery($del_sql);
       if($res){
           return true;
       }
       else{
           return false;
       }

    }
}