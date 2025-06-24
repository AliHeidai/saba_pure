<?php

function do_create_inquiry(){
    $ut=new Utility();
    $inq_date = $_POST['inq_date'];
    $inq_group = $_POST['inq_group'];
    $inq_description = $_POST['inq_description'];
    $inq = new Inquires();
    $result=$inq->do_create_inquiry($inq_date,$inq_group,$inq_description);
    if($result && count($result)>0){
        $out="true";
        response($result,$out);
    }
    else{
        $out="false";
        response('خطا در ذخیره استعلام ',$out);
    }

}

function get_summury_suggests(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $res=$inq->get_summury_suggests($_POST);
    if($res){
        response($res, 'true');
    }
    else{
        return false;
    }
    
   
    exit; 
}

function save_inq_paraf(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $res=$inq->save_inq_paraf($_POST);
    if($res)
    {
        response('عملیات با موفقیت انجام', 'true');
    }   
    else{
        response(" خطا در انجام عملیات", 'false'); 
    }
    exit;
}

function edit_paraf(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $result=$inq->edit_paraf($_POST);
    if($result && !empty($result)){
        $out="true";
        $res=$result;
       
    }
    if($result==0){
        $out="false";
        $res="خطا در انجام عملیات";
        
    }
    if($result==-1){
        $out="false";
        $res="شما مجاز به ویرایش پاراف نمی باشید";
       
    }
    response($res, $out);
}

function delete_paraf(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $result=$inq->delete_paraf($_POST);
   
    if(intval($result)==1){
       
        $out="true";
        $res="عملیات با موفقیت انجام شد";
    }
    if(intval($result)==0){
      
        $out="false";
        $res="خطا در انجام عملیات";
    }
    if(intval($result)==-1){
        $out="false";
        $res="شما مجاز به حذف پاراف نمی باشید";
    }
    response($res, $out);
}

function get_inq_parafs(){
  
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $res=$inq->get_inq_parafs($_POST);
    if($res)
    {
        response($res, 'true');
    }   
    
}

function get_inq_good_detailes(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
  
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
   
    if(!empty($_POST['inq_id'])){
        $res=$inq->get_inq_good_detailes($_POST);
        if($res)
        {
            response($res, 'true');
        } 
        response("خطا در انجام عملیات", 'false');
    }
    else{
        response("شناسه استعلام وارد نشده است", 'false'); 
    }
   
    exit;
}
function archive_inq(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    if(!empty($_POST['inq_id'])){
        $res=$inq->archive_inq($_POST['inq_id']);
        if($res)
        {
            response('استعلام  با موفقیت بایگانی  شد', 'true');
        } 
        response("خطا در انجام عملیات", 'false');
    }
    else{
        response("شناسه استعلام وارد نشده است", 'false'); 
    }
   
    

    exit;
}
function do_send_inquiry(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $ut->fileRecorder($_POST);
   $res=$inq->do_send_inquiry($_POST['reciever'],$_POST['inq_detailes'],$_POST['inq_id']);
   if($res)
   {
        response('کالا با موفقیت ذخیره شد', 'true');
    } 
    response("خطا در انجام عملیات", 'false');

    exit;
}

function get_selected_inqs(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $ut->fileRecorder($_REQUEST);
}

function save_good_row(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
   
    $inq_id         =$_POST['inq_id'];
    $good_name      =$_POST['good_name'];
    $good_quantity  =$_POST['good_quantity'];
    $good_unit      =$_POST['good_unit'];  
    $inq_base       =$_POST['inq_base'];
    $description    =$_POST['description'];
    $inq_number     =$_POST['inq_number'];
    $row_id     =$_POST['RowID'];
    $result=$inq->save_good_row($inq_id,$good_name,$good_quantity,$good_unit,$inq_base,$description,$inq_number,$row_id);
   // $ut->fileRecorder($result);
    if(is_array($result) && count($result)>0){
        response([$result,'کالا با موفقیت ذخیره شد'], 'true');
    } 
    response("خطا در انجام عملیات", 'false');
   
    exit;
    
}

function get_inq_detailes_info(){
    $ut = new Utility();
    $inq = new Inquires();
   
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    
   // $seller_array=explode(",",$_POST['sellers']);
    $res=$inq->get_inq_detailes_info($_POST['RowID']);
    $out = "true";
    response($res, $out);
    exit; 
}

function edit_inq_detailes(){
    $ut = new Utility();
    $inq = new Inquires();
   
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    
   // $seller_array=explode(",",$_POST['sellers']);
    $res=$inq->edit_inq_detailes($_POST['id']);
    $out = "true";
    response($res, $out);
    exit; 
}
function send_to_ready(){
    $ut = new Utility();
    $inq = new Inquires();
   
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $res=$inq->send_to_ready($_POST['inq_id']);
    $out =  $res? "true":"false";
    response($res, $out);
    exit; 

}
function create_comment_modal(){
    $ut = new Utility();
    $inq = new Inquires();
   
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }

    $res=$inq->create_comment_modal($_POST['RowID_array']);
    $out =  $res? "true":"false";
    response($res, $out);
    exit; 
}
function delete_inq_detailes(){
    $ut = new Utility();
    $inq = new Inquires();
   
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    
   // $seller_array=explode(",",$_POST['sellers']);
    $res=$inq->delete_inq_detailes($_POST['id']);
    $out =  $res? "true":"false";
    response($res, $out);
    exit; 
}

function get_seller_detailes(){
    $ut = new Utility();
    $inq = new Inquires();
   
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }

    $res=$inq->get_seller_detailes($_POST['seller_id']);
    $out = "true";
    response($res, $out);
    exit;
}

function insert_select_sellers(){
    $ut = new Utility();
    $inq = new Inquires();
   
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $ut->fileRecorder($_REQUEST);
   // $seller_array=explode(",",$_POST['sellers']);
    $res=$inq->insert_select_sellers($_POST['seller'],$_POST['inq_id'],$_POST['ostan_id'],$_POST['city_id'],$_POST['phone']);
    $out = "true";
    response($res, $out);
    exit;
}

function inq_get_units(){
    $ut = new Utility();
    $inq = new Inquires();

    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $result=$inq->get_inquray_unit();
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function do_delete_inq_good_row(){
    $ut = new Utility();
    $inq = new Inquires();

    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $result=$inq->do_delete_inq_good_row($_POST['good_id'],$_POST['inq_id']);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function inq_base_of_inq(){
    $ut = new Utility();
    $inq = new Inquires();

    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $result=$inq->get_base_of_inq();
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function get_inq_added_goods(){
    $ut = new Utility();
    $inq = new Inquires();

    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $result=$inq->get_inq_added_goods($_POST['inq_id']);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function do_delete_seller_column(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $result = $inq->do_delete_seller_column($_POST['RowID'],$_POST['inq_id'],$_POST['seller_id']);
    $res = $result;
    if($res){
        $out = "true";
        response($res, $out);
    }else{
        $out = "false";
        response('هیچ تغییری ایجاد نشد', $out);
    }
   
    exit; 

}

function get_all_inq_sellers(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $result = $inq->get_all_inq_sellers($_POST['inq_id']);
    $res = $result;
    if(is_array($res)){
        $out = "true";
        response($res, $out);
    }else{
        $out = "false";
        response('خطایی رخ داده است', $out);
    }
    
    exit; 

}

function delete_inq_good(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
 
    $result = $inq->delete_inq_good($_POST['inq_id'],$_POST['good_id']);
    $res = $result;
    if($res==1 || $res==2){
        $out = "true";
        response($res, $out);
    }else{
        $out = "false";
        response('هیچ تغییری ایجاد نشد', $out);
    }
   
    exit; 
}

function get_goods_row(){
    
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $result = $inq->get_goods_row($_POST['inq_id'],$_POST['good_id'],$_POST['detailes']);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function get_all_citeis(){
    $ut = new Utility();
    $inq = new Inquires();

    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    
    $result=$inq->get_all_citeis($_POST['privince']);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}



function get_layer_one_options(){
    $acm = new acm();
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
     $inq = new Inquires();
    $res=$inq->getLayer1();
    if(count($res>0)){
        $out="true";
        response($res,$out);
    }
}


function get_inquray_status_info(){
    $ut = new Utility();
    $inq = new Inquires();

    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $result=$inq->get_inquray_status_info($_POST['info_group']);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;

}
function create_pay_comment_modal(){
    $ut = new Utility();
    $inq = new Inquires();

    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $result=$inq->create_pay_comment_modal($_POST['g_id']);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function create_inquiry_pay_comment(){
    $ut = new Utility();
    $inq = new Inquires();

    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    if(!$acm->hasAccess('commentManagement')){
        die("access denied");
        exit;
    }
    $result=$inq->create_inquiry_pay_comment($_POST['inq_id']);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function get_inquiry_final_confirm(){
    $ut = new Utility();
    $inq = new Inquires();

    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    
    $result=$inq->get_inquiry_final_confirm($_POST['inq_id']);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function get_inq_groups(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $result=$inq->get_inq_groups();
 
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function display_inq_counts(){
    
    $ut = new Utility();
    $inq = new Inquires();

    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $result=$inq->display_inq_counts();
 
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function get_inquires()
{

    $ut = new Utility();
    $inq = new Inquires();

    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    } 
    $ut->fileRecorder($_POST['other_params']);

    $result = $inq->get_inquires($_POST['status'], $_POST['page'], $_POST['other_params']);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;

}

function attach_inq_file(){
    $inq = new Inquires();
    $acm = new acm();
    $ut=new Utility();
   
    if(!$acm->hasAccess('PurchaseInquiry') ){
       
        $out = "false";
        response( 'عدم دسترسی ',$out);
        die();
       
     } 

    $file = $_FILES['file'];

   $result = $inq->attach_inq_file($file,$_POST['file_title'],$_POST['good_id'],$_POST['inq_id']);
    $res = $result;
    if(!$res){
        $out = "false";
        response('خطا در انجام عملیات',$out);
    }
    $out = "true";
    response('عملیات با موفقیت انجام شد ', $out);
    exit; 
}

function get_inq_attachment_list(){
    $inq = new Inquires();
    $acm = new acm();
    $ut=new Utility();
    if(!$acm->hasAccess('PurchaseInquiry') ){
       
        $out = "false";
        response( 'عدم دسترسی ',$out);
        die();
       
     } 

   $result = $inq->get_inq_attachment_list($_POST['good_id'],$_POST['inq_id']);
    $res = $result;
    if(!$res){
        $out = "false";
        response('خطا در انجام عملیات',$out);
    }
    $out = "true";
    response($result, $out);
    exit; 

}

function delete_inq_file(){
    $inq = new Inquires();
    $acm = new acm();
    $ut=new Utility();
    if(!$acm->hasAccess('PurchaseInquiry') ){
       
        $out = "false";
        response( 'عدم دسترسی ',$out);
        die();
       
     } 

   $result = $inq->delete_inq_file($_POST['RowId']);
    $res = $result;
    if($res==0){
        $out = "false";
        response('خطا در انجام عملیات',$out);
    }
    $out = "true";
    response('عملیات حذف با موفقیت انجام شد', $out);
    exit; 
}

function delete_inquiry(){
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('DeletePurchaseInquiry') ){
       
        $out = "false";
        response( 'عدم دسترسی ',$out);
        die();
       
    } 
    $result = $inq->delete_inquiry($_POST['row_id']);
    $res = $result;
    if(!$res){
        $out = "false";
        response('خطا در انجام عملیات',$out);
    }
    $out = "true";
    response('عملیات با موفقیت انجام شد ', $out);
    exit;
}


function get_all_users_access_inq(){
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry') ){
        die("access denied");
        exit;
    } 
    $result = $inq->get_all_users_access_inq($_POST['row_id']);
    $res = $result;
    if($res==0){
        $out = "false";
        response($res, 'استعلام از کارتابل شما قابلیت ارجاع ندارد');
    }
    $out = "true";
    response($res, $out);
    exit;
}

function get_last_inq_data(){
    $ut=new Utility();
    $inq = new Inquires();
    $acm = new acm();
    //  if(!$acm->hasAccess('PurchaseInquiry')){
    //     die("access denied");
    //     exit;
    // } 
    //$good_name=isset($_POST['good_name'])?$_POST['good_name']:'';
    $result = $inq->get_last_inq_data($_REQUEST);
    if(is_array(($result)) && count($result)>0){
        $res = $result;
        $out = "true";
    }
    if($result==0){
        $res=[];
        $out = "true";
    }
    
    response($res, $out);
    exit;
}

function inq_get_all_goods()
{
    $inq = new Inquires();
    $acm = new acm();
    //  if(!$acm->hasAccess('PurchaseInquiry')){
    //     die("access denied");
    //     exit;
    // } 
    //$good_name=isset($_POST['good_name'])?$_POST['good_name']:'';
    $result = $inq->inq_get_all_goods($_REQUEST);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function inq_get_all_base_purchase()
{
    $inq = new Inquires();
    $acm = new acm();
    //  if(!$acm->hasAccess('PurchaseInquiry')){
    //     die("access denied");
    //     exit;
    // } 
    
    $result = $inq->inq_get_all_base_purchase();
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function create_description_seller()
{
    $ut=new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    } 

    $result = $inq->create_description_seller($_POST['inq_id']);
    if($result==-1){
        $out = "false";
        $res = json_encode(array('هیچ ردیف فروشنده ای برای این استعلام ثبت نشده است'),JSON_UNESCAPED_UNICODE);
        response($res, $out);
        exit;
    }
    else{
        $res = $result;
        $out = "true";
        response($res, $out);
        exit;
    }
   
}

function delete_all_seller_buyer_row()
{
    $ut=new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    } 

    $result = $inq->delete_all_form_inq($_POST['inq_id'],$_POST['good_id']);
    
    if($result==1){
        $res = 'ردیف کالا با موفقیت حذف شد';
        $out = "true";
        response($res, $out);
    }
    else{
        $res = 'خطا در انجام عملیات';
        $out = "false";
        response($res, $out);
    }
    
    exit;
}
function inq_get_all_units()
{
    $inq = new Inquires();
    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    } 
    $result = $inq->inq_get_all_units();
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function inq_get_all_buyer_sellers()
{
    $inq = new Inquires();
    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    } 
    $result = $inq->inq_get_all_buyer_sellers($_POST['seller_id']);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}



function  save_last_inq(){
    $inq = new Inquires();
    $acm = new acm();
    $ut=new Utility();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    } 
    $result = $inq->save_last_inq($_REQUEST);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function get_ostan_city(){
    $ut=new Utility();
    $inq = new Inquires();
    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
    $result = $inq->get_ostan_city($_POST["row_id"]);
    $res = $result;
    $out = "true";
    response($res, $out);
}
function save_seller_info(){
    $ut=new Utility();
    $inq = new Inquires();
    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }

    $result = $inq->save_seller_info(
    $_POST['is_vat'],
    $_POST['rent_percent'],
    $_POST['unit_price'],
    $_POST['pay_term'],
    $_POST['price_valid'],
    $_POST['rent'],
    $_POST['ultimate_price'],
    $_POST['description'],
    $_POST['ultimate_rent'],
    $_POST['good_id'],
    $_POST['seller_tbl_id'],
    $_POST['inq_detailes_id']
);
    if($result==1){
        $res='اطلاعات با موفقیت ذخیره شد';
        $out = "true";
    }
    else{
        $res=' خطا در ذخیره اطلاعات';
        $out = "false";
    }
    response($res, $out);
    exit;
}


function get_inq_seller_detailes(){
    $ut=new Utility();
    $inq = new Inquires();
    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
  
    $result=$inq->get_inq_seller_detailes($_POST['good_id'],$_POST['seller_id'],$_POST['RowID']);
    $res=$result;
    $out = "true";
    response($res, $out);
    exit;
    
}

function show_summary_suggests(){
    $ut=new Utility();
    $inq = new Inquires();
    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
 
    $result=$inq->show_summary_suggests($_POST['good_id'],$_POST['seller_id'],$_POST['RowID']);
    $res=$result;
    $out = "true";
    response($res, $out);
    exit;
}

function add_row_inq_seller_table(){
    $ut=new Utility();
    $inq = new Inquires();
    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    }
   
    $result=$inq->add_row_inq_seller_table($_POST['inq_id']);
    $res=$result;
    $out = "true";
    response($res, $out);
    exit;
}

function save_inquiry()
{
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */
    $inq_title = $_POST['inq_title'];
    $inq_date = $_POST['inq_date'];
    $inq_buy_code = $_POST['inq_buy_code'];
    $inq_id = $_POST['inq_id_hidden'];
    $inq_created_date = $_POST['inq_created_date'];


    $result = $inq->save_inquiry($inq_title, $inq_date, $inq_buy_code, $inq_id, $inq_created_date);

    if (!empty($result) && $result != -1) {
        $out = "true";
        response($result, $out);
    }
    $out = "false";
    response('خطا در ذخیره اطلاعات', $out);
}

function inquiry_get_good_detailes()
{
    $ut = new Utility();
    $inq = new Inquires();
    // $acm=new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */

    $inq_id = $_POST['inq_id'];


    $result = $inq->inq_get_good_detailes($inq_id);

    if (count($result) > 0) {
        $out = "true";
        response($result, $out);
    } else {
        $out = "false";
        response('اطلاعاتی یافت نشد', $out);
    }
}

function get_fixed_inq_data(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    } 
    $result=$inq->get_fixed_inq_data();
    if (count($result)) {
        $out = "true";
        response($result, $out);
    }
    $out = "false";
    response('خطا در فراخوانی اطلاعات', $out);
}

function add_seller_buyer_save()
{
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
     if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    } 
   // $ut->fileRecorder($_POST);
   
    $inq_good_name = $_POST['inq_good_name'];
    $inq_good_quantity_unit = $_POST['inq_good_quantity_unit'];
    $inq_good_quantity = $_POST['inq_good_quantity'];
    $inq_good_buy_request_num = $_POST['inq_good_buy_request_num'];
    $inq_good_desc = $_POST['inq_good_desc'];
    $buy_base_desc = $_POST['buy_base_desc'];
    $export_inq_seller_name = $_POST['export_inq_seller_name'];
    $export_good_quantity = $_POST['export_good_quantity'];
    $export_unit_price = $_POST['export_unit_price'];
    $export_pay_method = json_decode($_POST['export_pay_method'],true);
    $export_deliver_method = json_decode($_POST['export_deliver_method'],true);
    $export_rent_inside = $_POST['export_rent_inside'];
    $export_rent_outside = $_POST['export_rent_outside'];
    $seller_group_code = $_POST['seller_group_code'];
    $inq_id = $_POST['inq_id'];
   // $export_cash_section=$_POST['export_cash_section'];
   // $export_term_payment=$_POST['export_term_payment'];
    $export_description=$_POST['export_description'];
    $is_vat=$_POST['is_vat'];
    $temp_seller_name=$_POST['temp_seller_name'];
   // $ut->fileRecorder('temp_seller_name');
  //  $ut->fileRecorder($temp_seller_name);
   // $ut->fileRecorder($export_deliver_method);
    $result = $inq->save_inquiry_detailes($inq_good_name, $inq_good_quantity_unit, $inq_good_quantity,$inq_good_buy_request_num,$inq_good_desc,$buy_base_desc,
        $export_inq_seller_name, $export_good_quantity, $export_unit_price,
        $export_pay_method, $export_rent_inside,$export_deliver_method,
        $export_rent_outside, $seller_group_code,$inq_id,$export_description,$is_vat,$temp_seller_name
    );

    if (!empty($result) && $result != -1) {
        $out = "true";
        response($result, $out);
    }
    $out = "false";
    response('خطا در ذخیره اطلاعات', $out);

}

function inq_final_confirm(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */
    //group_code:group_code,inq_comment_detailes:inq_comment_detailes
    $result = $inq->inq_final_confirm($_POST['inq_id']);
    if($result==0){
        $out = "false";

        response(" برای هر گروه کالا باید  یک فروشنده انتخاب شود", $out);
    }
    if ($result) {
        $out = "true";
        response('استعلام با موفقیت ذخیره شد', $out);
    } else{
        $out = "false";
        response('خطا در ذخیره اطلاعات', $out);
    }
}

function inq_save_comment()
{
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */
    //group_code:group_code,inq_comment_detailes:inq_comment_detailes
    $result = $inq->inq_save_comment($_POST['inq_id'], $_POST['inq_group_code'], $_POST['inq_comment_detailes'],$_POST['good_id']);
    if ($result == 1) {
        $out = "true";
        response($result, $out);
    } elseif ($result == 0) {
        $out = "false";
        response('خطا در ذخیره اطلاعات', $out);
    } elseif ($result == -1) {
        $out = "false";
        response('برای این استعلام قبلا نظر ثبت شده است', $out);
    }
}

function display_inq_comment()
{
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */
    $result = $inq->display_inq_comment($_POST['good_id'],$_POST['inquiry_id'],$_POST['group_code']);

    if (count($result)>0) {
        $out = "true";
        // $ut->fileRecorder('result2522');
        // $ut->fileRecorder($result);
        response($result, $out);
    }
    else
    {
        $out = "false";
        response('یاداداشتی در خصوص این فروشنده ثبت نشده است',$out);
    }
}
function save_inq_comment(){
    $ut = new Utility();
    $db = new DBi();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */

    $result = $inq->save_inq_comment($_POST['good_id'],$_POST['inquiry_id'],$_POST['group_code'],$_POST['comment']);
    if ($result == true) {
        $out = "true";
        $res = "اطلاعات با موفقیت ذخیره شد";
    } else {
        $out = "false";
        $res = "خطا در ذخیره اطلاعات";
    }
    response($res, $out);
    exit;
}

function display_selected_seller_history(){

     $ut = new Utility();
    $db = new DBi();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */

    $result = $inq->get_selected_seller_history($_POST['good_id'],$_POST['inquiry_id'],$_POST['group_code']);
    if ($result) {
        $out = "true";
        $res = $result;
    } else {
        $out = "false";
        $res = "خطا در ذخیره اطلاعات";
    }
    response($res, $out);
    exit;
}
function save_inq_comment_reason(){
    $ut = new Utility();
    $db = new DBi();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */

    $result = $inq->save_inq_comment($_POST['good_id'],$_POST['inquiry_id'],$_POST['group_code'],$_POST['inq_comment_detailes'],1);
    if ($result == true) {
        $out = "true";
        $res = "اطلاعات با موفقیت ذخیره شد";
    } else {
        $out = "false";
        $res = "خطا در ذخیره اطلاعات";
    }
    response($res, $out);
    exit;
}

function add_new_seller(){
     $ut = new Utility();
    $db = new DBi();
    $inq = new Inquires();
    $acm = new acm();
     if(!$acm->hasAccess('inq_add_new_Supplier')){
        die("access denied");
        exit;
    } 

    $result = $inq->add_new_seller($_POST['seller_name'],$_POST['seller_phon'],$_POST['seller_address'],);
    // $res = $result;
    if ($result) {
        $out = "true";
        $res = $result;
    } else {
        $out = "false";
        $res = "خطا در عملیات";
    }
    response($res, $out);
    exit;

}

function inqiury_print(){
    $ut = new Utility();
    $db = new DBi();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */

    $result = $inq->print_inq($_POST['inq_id'],$_POST['print_paraf']);
    // $res = $result;
    if ($result) {
        $out = "true";
        $res = $result;
    } else {
        $out = "false";
        $res = "خطا در عملیات";
    }
    response($res, $out);
    exit;

}
function check_inq_comment_reason_status(){
    $ut = new Utility();
    $db = new DBi();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */

    $result = $inq->check_inq_comment_reason_status($_POST['good_id'],$_POST['group_code'],$_POST['inquiry_id']);
    // $res = $result;
    if ($result == 1) {
        $out = "true";
        $res = "1";
    } else {
        $out = "false";
        $res = "0";
    }
    response($res, $out);
    exit;

}

function do_edit_inq_message()
{
    $ut = new Utility();
    $db = new DBi();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */

    $result = $inq->do_edit_inq_message($_POST['rowid'], $_POST['message']);
    // $res = $result;
    if ($result == 1) {
        $out = "true";
        $res = "اطلاعات با موفقیت ذخیره شد";
    } elseif ($result == 0) {
        $out = "false";
        $res = "خطا در ذخیره اطلاعات";
    } else {
        $out = "false";
        $res = "خطا در دریافت اطلاعات اولیه";
    }
    response($res, $out);
    exit;

}

function edit_inquiry()
{
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
     if(!$acm->hasAccess('inq_edit_inquiry')){
        $res = 'عدم دسترسی';
        $out = "false";
        response($res, $out);
    } 

    $result = $inq->edit_inquiry($_POST['row_id']);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

// function delete_seller_buyer_row(){
//     $ut = new Utility();
//     $inq = new Inquires();
//     $acm = new acm();

//     /* if(!$acm->hasAccess('attach_unit_procedure_file')){
//         die("access denied");
//         exit;
//     } */

//     $result = $inq->delete_seller_buyer_row($_POST['good_id'] ,$_POST['inq_id']);
//     $res = $result;
//     $out = "true";
//     response($res, $out);
//     exit;
// }
// function inq_display_affordable_asc()
// {
//     $ut = new Utility();
//     $inq = new Inquires();
//     $acm = new acm();
//     /* if(!$acm->hasAccess('attach_unit_procedure_file')){
//         die("access denied");
//         exit;
//     } */
//     $ut->fileRecorder($_REQUEST);
//     $result = $inq->inq_display_affordable_asc($_POST['inq_id'], $_POST['percent']);
//     $res = $result;
//     $out = "true";
//     response($res, $out);
//     exit;
// }

function get_risks(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    $result=$inq->get_risks($_POST['risk_type']);
    if(!is_array($result)){
        $res = 'خطا در دریافت اطلاعات';
        $out = "false";
        response($res, $out);
    }
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function send_inquiry()
{
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */
  //  $ut->fileRecorder($_REQUEST);
    $result = $inq->send_inquiry($_POST['reciever'],$_POST['status'],$_POST['inq_send_comment'],$_POST['inquiry_id']);
    if($result==-1){
        $res = 'ایجاد کننده استعلام حتما باید استعلام را تایید نماید';
        $out = "false";
        response($res, $out);
    }
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function get_all_inq_sends(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */

    $result = $inq->get_all_inq_sends($_POST['row_id']);
    $res = $result;

    $out = "true";
    response($res, $out);
    exit;
}
function get_inquiry_detailes()
{
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    } 

    $result = $inq->get_inquiry_detailes($_POST['row_id']);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function get_inq_footer(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    if(!$acm->hasAccess('PurchaseInquiry')){
        die("access denied");
        exit;
    } 

    $result = $inq->get_inq_footer($_POST['inq_id']);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function get_seller_data()
{
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */
    $result = $inq->get_seller_data($_POST['inq_id'], $_POST['good_id']);
    // $ut->fileRecorder('result');
    // $ut->fileRecorder($result);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function set_inq_archive(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */
    $result = $inq->set_inq_archive($_POST['inq_id']);
    if($result){
        $res = $result;
        $out = "true";
    }
    else{
        $res = 'خطا در عملیات';
        $out = "false";

    }
    response($res, $out);
    exit;

}

function inq_select_seller(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */
    $result = $inq->inq_select_seller($_POST['good_id'],$_POST['group_code'],$_POST['inquiry_id'],$_POST['mode']);
    // $ut->fileRecorder('result');
    // $ut->fileRecorder($result);
    $res = $result;
    $out = "true";
    response($res, $out);
    exit;
}

function inq_create_new_goods(){
    $ut = new Utility();
    $inq = new Inquires();
    $acm = new acm();
    /* if(!$acm->hasAccess('attach_unit_procedure_file')){
        die("access denied");
        exit;
    } */
    $result = $inq->inq_create_new_goods($_POST['good_name']);
    if(count($result)>0) {
        $res = $result;
        $out = "true";
        response($res, $out);
        exit;
    }
    else{
        $res = "خطا در ذخیره اطلاعات";
        $out = "false";
        response($res, $out);
        exit;
    }

}

// //********************************************** inquires ^^^^^^********************************************