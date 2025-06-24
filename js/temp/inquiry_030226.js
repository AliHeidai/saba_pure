//var inqury_url="php/managemantproccess";
var parent_inquiry_id=0;
var inq_fix_data=[];
var selected_radio_inq;

function goToPurchaseInquiry(){
    openPurchaseInquiry();

}
function openPurchaseInquiry(){
    var id = 'PurchaseInquiry';
    $("#"+id).html('');
    var tab_array = [
        {"title":"استعلامات موقت",'attrs':{"class":"nav-link active-tab tab-item","id":"temp_inqs",'onclick':'go_to_section(this,get_inquires,0,\'temp_inqs-section\')'}},
        {"title":"استعلامات آماده ارسال",'attrs':{"class":"nav-link  tab-item","id":"export_inqs",'onclick':'go_to_section(this,get_inquires,1,\'export_inqs-section\')'}},
        {"title":"استعلامات وارده",'attrs':{'class':"nav-link tab-item","id":"import_inqs",'onclick':'go_to_section(this,get_inquires,2,\'import_inqs-section\')'}},
        {"title":"استعلامات صادره",'attrs':{'class':"nav-link tab-item","id":"sended_inqs",'onclick':'go_to_section(this,get_inquires,3,\'sended_inqs-section\')'}},
        {"title":"استعلامات آرشیو شده",'attrs':{'class':"nav-link tab-item","id":"archive_inqs",'onclick':'go_to_section(this,get_inquires,10,\'archive_inqs-section\')'}},
        {"title":"مدیریت اطلاعات اولیه  ",'attrs':{'class':"nav-link tab-item","id":"setting_inqs",'onclick':'go_to_section(this,get_inquires,-1,\'setting_inqs-section\')'}}
    ];

    create_tab("#"+id,tab_array);
    get_inquires(0,'temp_inqs-section');
    get_fixed_inq_data();

}
function go_to_setting_page(input){
    var target_id=$(input).attr('href');
    $("#setting_inqs-section .setting .setting_page").hide();
    $(target_id).show();
    if(target_id=="#seller_manangment"){
        create_data_table("seller_manage_tbl")
    }

}
async function get_fixed_inq_data(){
    var action='get_fixed_inq_data'
    var params={action:action}
    var res=await manageAjaxRequestCustom(params)
    if(res.res=true){
        inq_fix_data=res.data
    }
    else{
        custom_alerts(res.data,'e',0,'خطا')
    }
}
function programClick(linkid){
    switch (linkid){
        case "linkid-PurchaseInquiry":
            var id = linkid.split('-')[1];

            var tab_array = [
                {"title":"استعلامات موقت",'attrs':{"class":"nav-link active-tab tab-item","id":"temp_inqs",'onclick':'go_to_section(this,get_inquires,0,\'temp_inqs-section\')'}},
                {"title":"استعلامات آماده ارسال",'attrs':{"class":"nav-link  tab-item","id":"export_inqs",'onclick':'go_to_section(this,get_inquires,1,\'export_inqs-section\')'}},
                {"title":"استعلامات وارده",'attrs':{'class':"nav-link tab-item","id":"import_inqs",'onclick':'go_to_section(this,get_inquires,2,\'import_inqs-section\')'}},
                {"title":"استعلامات صادره ",'attrs':{"class":"nav-link  tab-item","id":"sended_inqs",'onclick':'go_to_section(this,get_inquires,3,\'sended_inqs-section\')'}},
                {"title":"استعلامات آرشیو شده",'attrs':{'class':"nav-link tab-item","id":"archive_inqs",'onclick':'go_to_section(this,get_inquires,10,\'archive_inqs-section\')'}}
            ];

            create_tab("#"+id,tab_array);
            get_inquires(0,'temp_inqs-section');
            break;
    }
    $('#'+linkid).click();
}

function go_to_section(input,callback_function,status,selector,refresh=0){
    if(refresh==0){
        if(!$('#PurchaseInquiry section#'+$(input).attr('id')+"-section").is(':visible')){
            $('#PurchaseInquiry .custom_navbar li a.active-tab').removeClass('active-tab')
            $('#PurchaseInquiry section.tab-section').hide(500)
            $(input).addClass('active-tab');
            $('#PurchaseInquiry section#'+$(input).attr('id')+"-section").show(500)
        }
    }
    if(typeof callback_function ==='function'){
        callback_function(status,selector);
    }
    if(status==-1){
        if($("#seller_manage_tbl").length){
            create_data_table('seller_manage_tbl')
        }

    }
}

function get_inquires(status,selector,page=1,other_params={}){
    var action="get_inquires";
    if(typeof selector=="string"){
        selector="#"+selector;
    }

    var param={action:action,status:status,page:page,other_params:other_params}
    var paragraph_array="";
    var res=ajaxHandler(param);
  //  var res=manageAjaxRequest(param);

    $(selector).html('');
    $(selector).html(res);
    switch(status){
        case 0:
            btn_create_array = {'type':'button',"class":"btn btn-primary inqury_btn",'parent':"1", "title":"استعلام جدید","onclick":"create_inquiry(this)", 'id':"create_new_inq_btn",'style':'float:left;position:relative;right:90%'}
            paragraph_array = {'type':'txt_info',"class":"", "text":"لیست استعلام های  درحال ثبت ", 'id':"export_inq_header",'style':'float:right'}
            create_form_element(".inq_list_header",btn_create_array);
            break;
        case 1:
            paragraph_array = {'type':'txt_info',"class":"", "text":"لیست استعلام های آماده ارسال ", 'id':"export_inq_header",'style':'float:right'}
            break;
        case 2:
            paragraph_array = {'type':'txt_info',"class":"", "text":"لیست استعلام های وارده  ", 'id':"export_inq_header",'style':'float:right'}
            break;
        case 3:
            paragraph_array = {'type':'txt_info',"class":"", "text":"لیست استعلام های صادره ", 'id':"export_inq_header",'style':'float:right'}
            break;
        case 10:
            paragraph_array = {'type':'txt_info',"class":"", "text":"لیست استعلام های آماده بایگانی شده ", 'id':"export_inq_header",'style':'float:right'}
            break;
       
    }
  
   
    create_form_element(".inq_list_header",paragraph_array);
    

    var inq_from_date_search = new AMIB.persianCalendar('inq_from_date_search_'+status);
    var inq_to_date_search = new AMIB.persianCalendar('inq_to_date_search_'+status);
}

//************************************************** */
function search_inquiry(input,status)
{
    var parent=$(input).parents(".inq_list_header_search")
    // var inq_name=$("#inq_name_search").val();
    // var inq_code=$("#inq_code_search").val();
    // var inq_from=$("#inq_from_date_search").val();
    // var inq_to=$("#inq_to_date_search").val();
    // var inq_reciever=$("#inq_reciever_search").val();
    var inq_name=$(parent).children().find('.inq_name_search').val();
    var inq_code=$(parent).children().find('.inq_code_search').val();
    var inq_from=$(parent).children().find('.inq_from_date_search').val();
    var inq_to=$(parent).children().find('.inq_to_date_search').val();
    var inq_reciever=$(parent).children().find('.inq_reciever_search').val();
    var selector=$(input).parents('section');
    var other_params={}
    if(inq_name){
        other_params.inq_name=inq_name
    }
    if(inq_code){
        other_params.inq_code=inq_code
    }
    if(inq_from){
        other_params.inq_from=inq_from
    }
    if(inq_to){
        other_params.inq_to=inq_to
    }
    if(inq_reciever){
        other_params.inq_reciever=inq_reciever
    }
    if((inq_to=="" && inq_from=="") ||(inq_to && inq_from)){
        get_inquires(status,selector,1,other_params)

    }
    // $(input).on('change',search_inquiry(this,status))
}
//******************************************************* */
async function  open_inq_manage(input,id,status){
    var form_title="";
    if(parseInt(id)>0){
        var action="get_inquiry_detailes";
        var  params={action:action,row_id:id};
        var res= await manageAjaxRequestCustom(params);
    }
    var selector="";
    switch  (status){
        case  0:
            selector="section#temp_inqs-section";
            break;
        case  1:
            selector="section#export_inqs-section";
            break;
        case  2:
            break;
        case 10:
            break;
    }
    var parent=$(input).parents(selector);
    $(parent).html('');
    var footer= {'type':'div',"class":"footer"}
    var content= {'type':'div',"class":"content",'id':"view_content",'style':'width:100%;height:auto'}
    var form_element = {'type':'form',"class":"form", 'id':"view_form_create_export_inq"}
    var section_one = {'type':'section',"class":"form-section", 'id':"view_form_section_inq_one",'style':'max-width:600px;margin:0 auto'}
    if(parseInt(id)>0){
        form_title="مدیریت استعلام بها";
    }
    var paragraph_array = {'type':'txt_info',"class":"", "text":form_title, 'id':"export_inq_header"}
    var header={'type':'div',"class":"header", 'id':"inq_manage_header",'style':'padding:3rem;display:flex;justify-content:space-between'}
    var return_button={'type':'btn',"class":"btn", 'id':"inq_btn_return" ,'title': "<i class='fa fa-arrow-turn-right' title='برگشت به صفحه اصلی'></i>","style":"margin-inline:2rem;color:red",'onclick':'goto_export_inq_list(this)'}
    var edit_button={'type':'button',"class":"btn btn-primary", 'parent':1,'id':"inq_btn_edit" ,'title':"ویرایش","style":"margin-inline:2rem;", 'onclick':"edit_inquiry(this,"+id+")"}
    var delete_button={'type':'button',"class":"btn btn-danger", 'parent':1,'id':"inq_btn_delete" ,'title':"حذف","style":"margin-inline:2rem;", 'onclick':"foo("+id+")"}

    // create_form_element(parent,form_element);
    create_form_element(parent,header);
    create_form_element(parent,content);
    create_form_element("#view_form_create_export_inq",paragraph_array);
    create_form_element("#inq_manage_header",paragraph_array);
    create_form_element("#inq_manage_header",edit_button);
    create_form_element("#inq_manage_header",delete_button);
    create_form_element("#inq_manage_header",return_button);

    $("#view_content").html(res.data);
}

async function foo(id) {
    swal.fire({
      title: "آیا مطمئن هستید?",
      text: "استعلام حذف شده به هیچ وجه قابل بازیابی نمی باشد ",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "بله , حذف شود",
      cancelButtonText: "انصراف",
      closeOnConfirm: false,
      customClass: {
        actions: 'my-actions',
        cancelButton: 'order-1 right-gap',
        confirmButton: 'order-2',
        denyButton: 'order-3',
      },
    });
    $(".order-2").on('click',function(){
        delete_inquiry(id);
    })
    
} 
  
 async function delete_inquiry(id){
    var action="delete_inquiry";
    var params={action:action,row_id:id}
    var res= await manageAjaxRequestCustom(params)
    if(res.res=="false"){
        custom_alerts(res.data,'e',0,'خطا')
        return false;
    }
    else{
        custom_alerts( res.data,'i',0,'حذف');
        goto_export_inq_list($("#inq_btn_return"));
    }

 }
function goto_export_inq_list(input){
    $(input).parents('section#temp_inqs-section').hide()
    go_to_section($("#temp_inqs"),get_inquires,0,'temp_inqs-section')

}
async function  create_inquiry(input,id=0){
    var form_title="";
    var today= $("#inq_create_date_input").val();

    var parent=$(input).parents('section#temp_inqs-section');
    $(parent).html('');
    var footer= {'type':'div',"class":"footer"}
    var content= {'type':'div',"class":"content",'id':"content_step_one"}
    var content_two= {'type':'div',"class":"content",'id':"content_step_two"}
    var content_three= {'type':'div',"class":"content",'id':"content_step_three"}
    //var content_four= {'type':'div',"class":"content",'id':"content_step_four"}
    var return_button={'type':'btn',"class":"btn ", 'id':"inq_btn_return" ,'title': "<i class='fa fa-arrow-turn-right' style='color:red' title='برگشت به صفحه اصلی'></i>","style":"margin-inline:2reme;margin-inline: 2rem;position: absolute;left: -2rem;top: 0;",'onclick':'goto_export_inq_list(this)'}
    var form_element = {'type':'form',"class":"form", 'id':"form_create_export_inq"}
    var section_one = {'type':'section',"class":"form-section", 'id':"form_section_inq_one",'style':'max-width:600px;margin:0 auto'}
    var section_two = {'type':'section',"class":"form-section", 'id':"form_section_inq_two",'style':'width:100%'}
    var section_three = {'type':'section',"class":"form-section", 'id':"form_section_inq_three",'style':'width:100%'}
    var section_four = {'type':'section',"class":"form-section", 'id':"form_section_inq_four",'style':'width:100%'}
    if(parseInt(id)>0){
        form_title="ویرایش استعلام بها";
    }
    else{
        form_title="ثبت استعلام بها جدید";
    }
    var paragraph_array = {'type':'txt_info',"class":"", "text":form_title, 'id':"export_inq_header"}

    //-------------------------------------input for section 1--------------------------------------------------------------
    var inq_create_date = {'type':'text',"class":"form-control", "title":"تاریخ ثبت استعلام", 'id':"export_inq_create_date",'style':'width:50%','value':today,'disabled':true}
    var inq_date = {'type':'text',"class":"form-control", "title":"تاریخ استعلام", 'id':"export_inq_date",'style':'width:50%',"onchange":"set_inq_data(this,'#inq_date_disable_td')"}
    //var inq_buy_code = {'type':'text',"class":"form-control", "title":" کد خرید", 'id':"export_inq_buy_code",'style':'width:50%',"onchange":"set_inq_data(this,'#inq_buy_code_disable_td')"}
    var inq_title = {'type':'text',"class":"form-control", "title":"گروه استعلام", 'id':"export_inq_title",'style':'width:50%'}
    if(parseInt(id)>0){
        inq_date['value']=res.data.inquiry_date;
       // inq_buy_code['value']=res.data.purchase_code;
        inq_title['value']=res.data.title;
    }
    // var inq_button_1 = {'type':'button',"class":"btn btn-primary", "title":"تایید و ادامه", 'id':"confirm_continue_one",'style':'float:left','onclick':'confirm_continue(this,\'#form_section_inq_two\')'}
    //-------------------------------------input for section 1-------------------------------------------------------------
    //-------------------------------------input for section 2------------------------------------------------------------
    var add_goods = {'type':'button',"class":"btn btn-primary inqury_btn", "title":" افزودن کالا", 'id':"add_good",'style':'width:100%;margin:auto','onclick':'add_inq_row_good(this,0)'}
    // var confirm_next_step = {'type':'button',"class":"btn btn-success", "title":" تایید و ادامه", 'id':"confirm_next_step",'style':'display:none;float:left;margin-inline:3rem','onclick':'add_inq_row_good(this)'}
    var inq_table_header_step2=[[" کد استعلام","<span id='inq_uncode_disable'></span>","تاریخ استعلام ","<span id=\"inq_date_disable_td\"></span>","شماره درخواست خرید","<span id=\"inq_buy_code_disable_td\"></span>"]] ;
    //-------------------------------------input for section 2-----------------------------------------------------------


    create_form_element(parent,form_element);
    create_form_element("#form_create_export_inq",paragraph_array);
    create_form_element("#form_create_export_inq",return_button);
    $("#form_create_export_inq").css('position','relative');
    //-----------------------------------section 1-------------------------------------------------------------------------
    create_form_element("#form_create_export_inq",section_one);
    create_form_element("#form_section_inq_one",content);
    create_form_element("#content_step_one",inq_create_date);
    create_form_element("#content_step_one",inq_date);
    create_form_element("#content_step_one",inq_title);
   // create_form_element("#content_step_one",inq_buy_code);
    create_form_element("#form_section_inq_one",footer);
    //-----------------------------------section 1------------------------------------------------------------------------
    //-----------------------------------section 2------------------------------------------------------------------------
    create_form_element("#form_create_export_inq",section_two);
    create_table("#form_section_inq_two",inq_table_header_step2,'width:50%;margin:0 auto',0,0)
    //var add_goods = {'type':'button',"class":"btn btn-primary", "title":" افزودن کالا", 'id':"add_good",'style':'float:left','onclick':'add_inq_row_good(this)'}

    create_form_element("#form_section_inq_two",content_two);
    create_form_element("#content_step_two",add_goods);

    // create_form_element("#form_section_inq_two",);
    create_form_element("#form_section_inq_two",footer);

    //-----------------------------------section 2-------------------------------------------------------------------------

    //-----------------------------------section 3-------------------------------------------------------------------------
    create_form_element("#form_create_export_inq",section_three);
    create_form_element("#form_section_inq_three",content_three)
    create_form_element("#form_section_inq_three",footer)
    //-----------------------------------section 4-------------------------------------------------------------------------
    // create_form_element("#form_create_export_inq",section_four);
    // create_form_element("#form_section_inq_four",content_four)
    // create_form_element("#form_section_inq_four",footer)

    // create_form_element("#form_create_export_inq",section_four);
    set_steps_form("#form_create_export_inq",3,0,[validate_form_step_one,callback2,callback3],['form_section_inq_one','form_section_inq_two','form_section_inq_three'],active_step=0);
    $('#export_inq_date').MdPersianDateTimePicker({
        targetTextSelector: '#export_inq_date',
        // disableBeforeDate: new Date(),
        disableAfterDate: new Date(),
    })
}
async  function edit_inquiry(input,id){
    var action="edit_inquiry";
    var res= await manageAjaxRequestCustom({action:action,row_id:id})
    if(res.res=="false"){
        custom_alerts(res.data,'e',0,'خطا')
        return false;
    }
    parent_inquiry_id=id;
    var today=res.data.inq_created_date
    var parent=$(input).parents('section#temp_inqs-section');
    $(parent).html('');
    var footer= {'type':'div',"class":"footer"}
    var content= {'type':'div',"class":"content",'id':"content_step_one"}
    var content_two= {'type':'div',"class":"content",'id':"content_step_two"}
    var content_three= {'type':'div',"class":"content",'id':"content_step_three"}
    var content_four= {'type':'div',"class":"content",'id':"content_step_four"}
    var return_button={'type':'btn',"class":"btn ", 'id':"inq_btn_return" ,'title': "<i class='fa fa-arrow-turn-right' style='color:red' title='برگشت به صفحه اصلی'></i>","style":"margin-inline:2reme;margin-inline: 2rem;position: absolute;left: -2rem;top: 0;",'onclick':'goto_export_inq_list(this)'}
    var form_element = {'type':'form',"class":"form", 'id':"form_create_export_inq"}
    var section_one = {'type':'section',"class":"form-section", 'id':"form_section_inq_one",'style':'max-width:600px;margin:0 auto'}
    var section_two = {'type':'section',"class":"form-section", 'id':"form_section_inq_two",'style':'width:100%'}
    var section_three = {'type':'section',"class":"form-section", 'id':"form_section_inq_three",'style':'width:100%'}
   // var section_four = {'type':'section',"class":"form-section", 'id':"form_section_inq_four",'style':'width:100%'}
    if(parseInt(id)>0){
        form_title="ویرایش استعلام بها";
    }
    else{
        form_title="ثبت استعلام بها جدید";
    }
    var paragraph_array = {'type':'txt_info',"class":"", "text":form_title, 'id':"export_inq_header"}
    //-------------------------------------input for section 1--------------------------------------------------------------
    var inq_create_date = {'type':'text',"class":"form-control", "title":"تاریخ ثبت استعلام", 'id':"export_inq_create_date",'style':'width:50%','value':today,'disabled':true}
    var inq_date = {'type':'text',"class":"form-control", "title":"تاریخ استعلام", 'id':"export_inq_date",'style':'width:50%',"onchange":"set_inq_data(this,'#inq_date_disable_td')"}
  //  var inq_buy_code = {'type':'text',"class":"form-control", "title":" کد خرید", 'id':"export_inq_buy_code",'style':'width:50%',"onchange":"set_inq_data(this,'#inq_buy_code_disable_td')"}
    var inq_title = {'type':'text',"class":"form-control", "title":"گروه استعلام", 'id':"export_inq_title",'style':'width:50%'}
    var inq_id_hidden = {'type':'hidden', 'id':"inq_id_hidden", 'value':id}
    if(parseInt(id)>0){
        inq_date['value']=res.data.inquiry_date;
       // inq_buy_code['value']=res.data.purchase_code;
        inq_title['value']=res.data.title;
    }
    // var inq_button_1 = {'type':'button',"class":"btn btn-primary", "title":"تایید و ادامه", 'id':"confirm_continue_one",'style':'float:left','onclick':'confirm_continue(this,\'#form_section_inq_two\')'}
    //-------------------------------------input for section 1-------------------------------------------------------------
    //-------------------------------------input for section 2------------------------------------------------------------
    var add_goods = {'type':'button',"class":"btn btn-primary inqury_btn", "title":" افزودن کالا", 'id':"add_good",'style':'width:100%;margin:auto','onclick':'add_inq_row_good(this,'+id+')'}
    // var confirm_next_step = {'type':'button',"class":"btn btn-success", "title":" تایید و ادامه", 'id':"confirm_next_step",'style':'display:none;float:left;margin-inline:3rem','onclick':'add_inq_row_good(this)'}
    var inq_table_header_step2=[[" کد استعلام","<span id='inq_uncode_disable'></span>","تاریخ استعلام ","<span id=\"inq_date_disable_td\"></span>","شماره درخواست خرید","<span id=\"inq_buy_code_disable_td\"></span>"]] ;
    //-------------------------------------input for section 2-----------------------------------------------------------


    create_form_element(parent,form_element);

    create_form_element("#form_create_export_inq",paragraph_array);
    create_form_element("#form_create_export_inq",return_button);
    $("#form_create_export_inq").css('position','relative');
    //-----------------------------------section 1-------------------------------------------------------------------------
    create_form_element("#form_create_export_inq",section_one);
    create_form_element("#form_create_export_inq",inq_id_hidden);
    create_form_element("#form_section_inq_one",content);
    create_form_element("#content_step_one",inq_create_date);
    create_form_element("#content_step_one",inq_date);
    create_form_element("#content_step_one",inq_title);
    //create_form_element("#content_step_one",inq_buy_code);
    create_form_element("#form_section_inq_one",footer);
    //-----------------------------------section 2------------------------------------------------------------------------
    create_form_element("#form_create_export_inq",section_two);
    create_table("#form_section_inq_two",inq_table_header_step2,'width:50%;margin:0 auto',0,0)
    //var add_goods = {'type':'button',"class":"btn btn-primary", "title":" افزودن کالا", 'id':"add_good",'style':'float:left','onclick':'add_inq_row_good(this)'}

    create_form_element("#form_section_inq_two",content_two);
    create_form_element("#content_step_two",add_goods);

    // create_form_element("#form_section_inq_two",);
    create_form_element("#form_section_inq_two",footer);

    //-----------------------------------section 2-------------------------------------------------------------------------

    //-----------------------------------section 3-------------------------------------------------------------------------
    create_form_element("#form_create_export_inq",section_three);
    create_form_element("#form_section_inq_three",content_three)
    create_form_element("#form_section_inq_three",footer)
    //-----------------------------------section 4-------------------------------------------------------------------------
    // create_form_element("#form_create_export_inq",section_four);
    // create_form_element("#form_section_inq_four",content_four)
    // create_form_element("#form_section_inq_four",footer)

    // create_form_element("#form_create_export_inq",section_four);
    set_steps_form("#form_create_export_inq",3,0,[validate_form_step_one,callback2,callback3],['form_section_inq_one','form_section_inq_two','form_section_inq_three'],active_step=0);
    $('#export_inq_date').MdPersianDateTimePicker({
        targetTextSelector: '#export_inq_date',
        // disableBeforeDate: new Date(),
        disableAfterDate: new Date(),
    })
    sessionStorage.setItem("inq_id",id)
    $("#add_good").click();
    $("#form_section_inq_two").children('.footer').show();

}
function set_inq_data(input,target){
    var data_inq= $(input).val()
    $(target).text(data_inq);
    $(target).val(data_inq);
}

function add_inq_row_good(input,inq_id=0){

    var goods_params = {action:'inq_get_all_goods'}
    var units_params = {action:'inq_get_all_units'}
    var options_good = '<option value="0">انتخاب کالا</option>';
    var options_unit = '<option value="0"> انتخاب واحد کالا</option>';

    var option_array_goods = ajaxHandler(goods_params);
    //var option_array_goods = manageAjaxRequest(goods_params);
    for(k in option_array_goods){
        options_good += `<option value=${option_array_goods[k]['code']}>${option_array_goods[k]['title']}</option>`
    }
    var option_array_units = ajaxHandler(units_params);
    for (k in option_array_units) {
        options_unit += `<option value=${option_array_units[k]['RowID']}>${option_array_units[k]['description']}</option>`
    }
    //**********************
    //if(inq_id>0) {
    if(sessionStorage.getItem('inq_id')>0) {
        var action = 'inquiry_get_good_detailes';
        var params = {action: action, inq_id: inq_id}
        var res_goods = ajaxHandler(params);
        console.log(res_goods)

    }
    var count_res=0;
    var index=$("#form_section_inq_two").find(".form_create_inq_box").length;

    if(sessionStorage.getItem('inq_id')>0 ) {
        if(res_goods)
            count_res=res_goods.length

    }
    index_array=[];
    if(count_res>0)
    {
        for (var m = 0; m < count_res; m++) {
            index=$("#form_section_inq_two").find(".form_create_good_box").length;
            var box_element = {
                'type': 'div',
                "class": "row form_create_inq_box",
                "style": "width:100%;border:2px solid gray;margin:10px;border-radius:5px;position:relative;min-height:50px",
                'id': "form_create_inq_box_" + index
            };
            var good_element = {
                'type': 'div',
                "class": "col-md-3 form_create_good_box",
                'id': "form_create_good_box_" + index,
                "style":"position:static"};
            var seller_element = {
                'type': 'div',
                "class": "col-md-8 form_create_seller_box",
                'id': "form_create_seller_box_" + index
            };
            var form_element = {
                'type': 'form',
                "class": "form  form_create_good_row",
                "style": "width:80%;",
                'id': "form_create_good_row_" + index
            }
            var form_element_button = {
                'type': 'div',
                "class": "col-md-1 add_seller_btn_box",
                "style": "width:100%;",
                'id': "add_seller_btn_box_" + index,
                'style':"border-right:2px solid #dee8e9; margin:1rem 0px"
            }
            //var inq_good_code = {'type':'text',"class":"form-control", "title":"کدکالا", 'id':"inq_good_code_"+index,'style':'width:100%','value':"",'disabled':true}
            var inq_good_name = {
                'type': 'text',
                "class": "form-control",
                "title": " شرح کالا",
                'id': "inq_good_name_" + index,
                'style': 'width:100%',
                'row_class':"col-md-7",
                'disable':'disabled',
                'readonly':'readonly'
            }
            var inq_good_hidden= {
                'type': 'hidden',
                'id': "inq_good_name_" + index+"_hidden",
            }
            var inq_good_unit = {
                'type': 'select',
                "class": "form-control",
                "title": "واحد کالا",
                'id': "inq_good_quantity_unit_" + index,
                'style': 'width:100%',
                'options': options_unit,

            }
            var inq_good_quantity = {
                'type': 'text',
                "class": "form-control",
                "min": "1",
                "title": "تعداد",
                'id': "inq_good_quantity_" + index,
                'style': 'width:50%',
                'onchange': "numberformat(this,1);check_seller_quantity(this," + index + ")",

            }
            //-------------------------------------------------
              var inq_good_desc = {
                'type': 'textarea',
                "class": "form-control",
                "title": "توضیحات کالا",
                'id': "inq_good_desc_" + index,
                'style': 'width:100%',

            }

             var inq_good_buy_request_num = {
                'type': 'text',
                "class": "form-control",
                "title": " شماره درخواست  خرید",
                'id': "inq_good_buy_request_num_" + index,
                'style': 'width:50%',

            }
             var buy_base_desc = {
                'type': 'textarea',
                "class": "form-control",
                "title": "توضیحات مبنای خرید",
                'id': "buy_base_desc_" + index,
                'style': 'width:100%',

            }
           
            //-------------------------------------------------
            // var inq_btn = {'type':'button',"class":"btn btn-success", "title": "افزودن فروشنده/خریدار", 'id':"export_inq_btn",'style':'','parent_box_index':index, "onclick":"add_seller_buyer_inq(this,'#form_create_seller_box_"+index+"')"}
            var inq_btn_del =
            {
                "type": "button",
                "parent":0,
                "class": "btn btn-danger",
                "title": "حذف",
                'id': "export_inq_btn_delete_inq_" + index,
                "onclick": 'delete_all_form_inq(this,\'#form_create_inq_box_' + index + '\')',
                "style":"position:absolute;top:3rem;left:1rem"
            }
            var add_seller_btn=
            {
                'type': 'button',
                "class": "btn ",
                "title": "+",
                'id': "add_seller_" + index,
                'style': 'width: 100%;height: 100%;color: #fff;border: 2px solid #f1eaea;background-color: #dee7ed;',
                'parent_box_index': index,
                'parent':0,
                "onclick": "add_seller_buyer_inq(this,'#form_create_seller_box_" + index + "'," + index + "," + inq_id + ")"
            };
            var show_hide_btn =
            {
                'type': 'btn',
                "class": "btn",
                "title": '<i class="fa fa-angle-double-up"></i>',
                'id': "show_hide_" + index,
                'style': 'position:absolute;z-index:3;left:0',
                'parent': 0,
                "onclick": "toggle_display(this,'#form_create_inq_box_" + index + "')"
            }
            create_form_element("#content_step_two", box_element);
            create_form_element("#form_create_inq_box_" + index, show_hide_btn);
            create_form_element("#form_create_inq_box_" + index, good_element);
            create_form_element("#form_create_inq_box_"  + index,form_element_button);
            create_form_element("#form_create_inq_box_" + index, seller_element);
            create_form_element("#form_create_good_box_" + index, form_element);
            create_form_element("#form_create_good_row_" + index, inq_good_name);
            create_form_element("#form_create_good_row_" + index, inq_good_unit);
            create_form_element("#form_create_good_row_" + index, inq_good_hidden);
            create_form_element("#form_create_good_row_" + index, inq_good_quantity);
            //-----------------------------
            create_form_element("#form_create_good_row_" + index, inq_good_buy_request_num);
            create_form_element("#form_create_good_row_" + index, inq_good_desc);
            create_form_element("#form_create_good_row_" + index, buy_base_desc);
            //-----------------------------
            create_form_element("#add_seller_btn_box_"+ index, add_seller_btn);
            create_form_element("#form_create_good_row_" + index, inq_btn_del);
            select_data_picker("#inq_good_name_" + index)

            $('<button  type="button" onclick="add_datalist(this)"  style="height: 100%" class="btn btn-info col-md-1"> ...</button>').insertAfter("#inq_good_name_" + index)
            $(input).attr('disabled', true);
            if(inq_id>0) {
                $("#inq_good_name_" + index).val(res_goods[m]['title']);
                $("#inq_good_quantity_unit_" + index).val(res_goods[m]['inq_good_quantity_unit']);
                $("#inq_good_quantity_" + index).val(res_goods[m]['inq_good_quantity']);
                $("#inq_good_name_" + index+"_hidden").val(res_goods[m]['inq_good_id'])
                $("#inq_good_desc_" + index).val(res_goods[m]['inq_good_desc'])
                $("#inq_good_buy_request_num_" + index).val(res_goods[m]['inq_good_buy_request_num'])
                $("#buy_base_desc_" + index).val(res_goods[m]['buy_base_desc'])
                
            }

            if($("#add_seller_" + index) && index>0){
                $("#add_seller_" + index).click()
            }
        }
        $("#add_seller_0").click();
            for(var t=0;t<=index;t++) {
                var abb_btn1 = '<div style="width: 100%;margin: auto;padding-block: 10px"> <button id="add_seller_btn_' + t + '"  type="button" class="btn"  style="width: 100%;background: #e9ecef;color:#fff;font-size: 2rem"> + </button> </div>'
                $("#form_create_seller_box_" + t).append(abb_btn1)
                $("#add_seller_btn_" + t).click(function () {
                    var parent = $(this).parents(".form_create_inq_box").find(".form_create_good_box").attr("id")
                    var index_array = parent.split("_")
                    var parent_box_index = index_array[parseInt(index_array.length) - 1]
                    $(this).attr('parent_box_index', parent_box_index)
                    $("#add_seller_" + parent_box_index).click();
                    $("#add_seller_btn_" + t).remove();

                })
                $("#content_step_two").find(".footer").show();
            }

    }
    else
    {
        var box_element = {
            'type': 'div',
            "class": "row form_create_inq_box",
            "style": "width:100%;border:2px solid gray;margin:10px;border-radius:5px;position:relative;min-height:50px",
            'id': "form_create_inq_box_" + index
        };
        var good_element = {
            'type': 'div',
            "class": "col-md-3 form_create_good_box",
            'id': "form_create_good_box_" + index,
            'style':'position:static'
        };
        var seller_element = {
            'type': 'div',
            "class": "col-md-8 form_create_seller_box",
            'id': "form_create_seller_box_" + index
        };
        var form_element = {
            'type': 'form',
            "class": "form  form_create_good_row",
            "style": "width:80%;",
            'id': "form_create_good_row_" + index
        }
        var form_element_button = {
            'type': 'div',
            "class": "col-md-1 add_seller_btn_box",
            "style": "width:100%;",
            'id': "add_seller_btn_box_" + index,
            'style':"border-right:2px solid #dee8e9; margin:1rem 0px"
        }
        var inq_btn_del =
            {
                "type": "button",
                "parent":0,
                "class": "btn btn-danger",
                "title": "حذف",
                'id': "export_inq_btn_delete_inq_" + index,
                "onclick": 'delete_all_form_inq(this,\'#form_create_inq_box_' + index + '\')',
                "style":"position:absolute;top:3rem;left:1rem;display:none"
            }
        var inq_good_name = {
            'type': 'text',
            "class": "form-control",
            'row_class':'col-md-7',
            "title": " شرح کالا",
            'id': "inq_good_name_" + index,
            'style': 'width:100%',

            'disable':'disabled',
            'readonly':'readonly'


        }
        var inq_good_hidden= {
        'type': 'hidden',
        'id': "inq_good_name_" + index+"_hidden",
        }

        var inq_good_unit = {
            'type': 'select',
            "class": "form-control",
            "title": "واحد کالا",
            'id': "inq_good_quantity_unit_" + index,
            'style': 'width:100%',
            'options': options_unit,
        }
        var inq_good_quantity = {
            'type': 'text',
            "class": "form-control",
            "min": "1",
            "title": "تعداد",
            'id': "inq_good_quantity_" + index,
            'style': 'width:50%',
            'onchange': "numberformat(this,1);check_seller_quantity(this," + index + ")",

        }
           //-------------------------------------------------
            var inq_good_buy_request_num = {
                'type': 'text',
                "class": "form-control",
                "min": "1",
                "title": " شماره درخواست  خرید",
                'id': "inq_good_buy_request_num_" + index,
                'style': 'width:50%',

            }
              var inq_good_desc = {
                'type': 'textarea',
                "class": "form-control",
                "min": "1",
                "title": "توضیحات کالا",
                'id': "inq_good_desc_" + index,
                'style': 'width:100%',

            }

            
             var buy_base_desc = {
                'type': 'textarea',
                "class": "form-control",
                "title": "توضیحات مبنای خرید",
                'id': "buy_base_desc_" + index,
                'style': 'width:100%',

            }
           
            //-------------------------------------------------
        var inq_btn = {
            'type': 'button_group',
            "elements": [
                {
                    "type": "button",
                    "class": "btn btn-danger",
                    "title": "حذف",
                    'id': "export_inq_btn_delete_inq_" + index,
                    "onclick": 'delete_all_form_inq(this,\'#form_create_inq_box_' + index + '\')',
                    "style":"position:absolute;top:3rem;left:1rem"
                }]
        }
        var add_seller_btn= {
            'type': 'button',
            "class": "btn ",
            "title": "+",
            'id': "add_seller_" + index,
            'style': 'width: 100%;height: 100%;color: #fff;border: 2px solid #f1eaea;background-color: #dee7ed;',
            'parent_box_index': index,
            'parent':0,
            "onclick": "add_seller_buyer_inq(this,'#form_create_seller_box_" + index + "'," + index + "," + inq_id + ")"
        };
        var show_hide_btn = {
            'type': 'btn',
            "class": "btn",
            "title": '<i class="fa fa-angle-double-up"></i>',
            'id': "show_hide_" + index,
            'style': 'position:absolute;z-index:3;left:0',
            'parent': 0,
            "onclick": "toggle_display(this,'#form_create_inq_box_" + index + "')"
        }


        create_form_element("#content_step_two", box_element);
        create_form_element("#form_create_inq_box_" + index, show_hide_btn);

        create_form_element("#form_create_inq_box_" + index, good_element);
        //create_form_element("#form_create_inq_box_" + index, good_element);
        create_form_element("#form_create_inq_box_"  + index,form_element_button);
        create_form_element("#form_create_inq_box_" + index, seller_element);
        create_form_element("#form_create_good_box_" + index, form_element);
        create_form_element("#form_create_good_row_"+index,inq_good_hidden);
        create_form_element("#form_create_good_row_" + index, inq_good_name);
        create_form_element("#form_create_good_row_" + index, inq_good_unit);
        create_form_element("#form_create_good_row_" + index, inq_good_quantity);
          //-----------------------------
            create_form_element("#form_create_good_row_" + index, inq_good_buy_request_num);
            create_form_element("#form_create_good_row_" + index, inq_good_desc);
          
            create_form_element("#form_create_good_row_" + index, buy_base_desc);
            //-----------------------------
        create_form_element("#add_seller_btn_box_"+ index, add_seller_btn);
        create_form_element("#form_create_good_row_" + index, inq_btn_del);
       // select_data_picker("#inq_good_name_" + index)
        $('<button  type="button" onclick="add_datalist(this)"  style="height: 100%" class="btn btn-info col-md-1"> ...</button>').insertAfter("#inq_good_name_" + index)
        $("#add_good").hide();
    }
    
    sessionStorage.removeItem('inq_id')

}

async function add_datalist(input){

    $(input).parent().css('position','relative');
    var id=$(input).parent().children('input').attr('id');
    var list_length=$(input).parent().children("#"+id+"_list").length;
    var good_name=$(input).val();
    if(list_length==0)
    {
        $(input).parent().append(`<div style="position: absolute;background: #fff;box-shadow: 2px 3px 4px #000;padding:10px border:1px solid gray;left:0;top: 2rem;width: 80%;z-index: 10;display: none" id="${id}_list"> 
        </div>`);
    }
    var final_html =
        `
        <div class="form-group p-2"><input style="background: #FFFFE0"  target="${id}_list" id="search_bar" onkeyup="search_goods(this,'${id}')" class="form-control" placeholder="search.."></div>
        <hr>
        <div class="final_good_box" style="max-height: 300px;overflow-y: scroll;padding: 10px">
            
       
            <table id="table_${id}" class="table table-bordered ">`;
    var action="inq_get_all_goods"
    var params={action:action,good_name:good_name}
    var res=await manageAjaxRequestCustom(params)
    if(res.data.length>0) {
        var html_td = "";
        for (k in res.data) {
            html_td += "<tr><td target='" + id + "' good_id='" + res.data[k]['code'] + "' onclick='set_good_name(this)' style='cursor: pointer'>" + res.data[k]['title'] + "</td></td></tr>"
        }
        $("#" + id + "_list").html('');
        final_html+= `<tbody>${html_td}</tbody>`
    }
    var insert_box=
        `</table>
            </div >
             <hr>
            <div class="input-group mb-3" style='padding:10px'>
           
              <input type="text" style="background: #FFFFE0" id="${id}_add" class="form-control" placeholder="نام محصول را برای  افزودن وارد نمایید" aria-label="Recipient's username" aria-describedby="basic-addon2">
              <div style="" class="input-group-append ">
             
                <button parent_id="${id}" class="btn btn-success" onclick="inq_create_new_goods(this,'${id+'_add'}','${id}')" type="button">+</button>
              </div>
          </div>`
    $("#"+id+"_list").html(final_html+insert_box);
    if( $("#"+id+"_list").is(":visible")){
        $("#"+id+"_list").hide();
    }
    else{
        $("#"+id+"_list").show();
    }
}

async function inq_create_new_goods(input,text_input_id,id){
    var action="inq_create_new_goods"
    var good_name=$("#"+text_input_id).val();
    var params={action:action,good_name:good_name};
    var parent=$(input).attr('parent_id');
    var res=await manageAjaxRequestCustom(params)
    var html_td="";
    if(res.res=='true'){
        for(k in res.data){
            html_td += "<tr><td target='" + id + "' good_id='" + res.data[k]['code'] + "' onclick='set_good_name(this)' style='cursor: pointer'>" + res.data[k]['title'] + "</td></td></tr>";
        }
        $("#table_"+parent).html(html_td);
        $("#"+parent+"_add").val('');
    }
    else{
        custom_alerts(res.data,"e",0,'خطا')
    }
}

async function search_goods(input,id){
    var target=$(input).attr('target');
    var id=$("#"+id).attr('id');
    var action="inq_get_all_goods"
    var good_name=$(input).val()
    var params={action:action,good_name:good_name}
    var res=await manageAjaxRequestCustom(params)
    var all_rows="";
    for(k in res.data){
        all_rows+=`<tr> <td target='${id}'  good_id="${res.data[k]['code']}" onclick='set_good_name(this)' style='cursor: pointer'>${res.data[k]['title']}</td></tr>`
    }
    $("#"+target).children().find('table').html(all_rows);
}

 function set_good_name(input){
   var target=$(input).attr('target');
   var good_id=$(input).attr('good_id');
   var good_title=$(input).text();
   $("#"+target).val(good_title);
   $(input).parents("#"+target+"_list").remove();
   $("#"+target+"_hidden").val(good_id);
 }

function check_seller_quantity(input,index){
    $("input[id^='export_good_quantity_"+index+"']").each(function(){
        var good_quntity=numberformat2($(input).val());
        if(parseInt(good_quntity)<parseInt(numberformat2($(this).val()))){
            custom_alerts('شما مجاز به تغییر تعداد کالا  نمی باشید','e','خطا')
            $(this).val(good_quntity)
            numberformat($(this),1)
        }
    })
}

function toggle_display(input,selector,e=event){
    e.preventDefault()
    if($(selector).children().find('form').is(":visible")){
        $(selector).children().find('form').hide()
        $(input).html("<i class='fa fa-angle-double-down'></i>");
    }
    else
    {
        $(selector).children().find('form').show();
        $(input).html("<i class='fa fa-angle-double-up'></i>");
    }
}

async function validate_form_step_one(){

    var error_array=
        [
            {'selector':"#export_inq_title",'message':"گروه استعلام وارد نشده است"},
            {'selector':"#export_inq_date",'message':"تاریخ استعلام وارد نشده است"},
        ];
    var inq_id_hidden=parent_inquiry_id;
    parent_inquiry_id=0;
    for(k=0;k<error_array.length; k++ ){
        var flag=true;
        if($(error_array[k]['selector']).val().trim()=="" || $(error_array[k]['selector']).val().trim()==null){
            Swal.fire({
                icon: "error",
                title: "خطا..",
                text: error_array[k]['message'],
                footer: ''
            });
            $(error_array[k]['selector']).css('border','2px solid red');
            flag=false;
        }
        if(flag==false){
            return false;
        }
    }

    var inq_title=$("#export_inq_title") .val();
    var inq_created_date=$("#export_inq_create_date") .val();

    var inq_date=$("#export_inq_date") .val();
    //var inq_buy_code=$("#export_inq_buy_code") .val();

    var action="save_inquiry";
    var param={inq_title:inq_title,inq_date:inq_date,action:action,inq_id_hidden:inq_id_hidden,inq_created_date:inq_created_date};
    var result = await manageAjaxRequestCustom(param);

    if(result.res=='false'){
        custom_alerts(result.data,'e',0,'خطا');
        return false;
    }
    $("#export_inq_header").text("ثبت استعلام بها > اطلاعات پایه")

    $("#inq_uncode_disable").text(result.data.inquiry_code);
    $("#inq_buy_code_disable_td").text(result.data.purchase_code);
    sessionStorage.setItem('inq_uncode',result.data.inquiry_id);
    if($("#inq_id_hidden") && $("#inq_id_hidden").val()>0 ){
        $("#export_inq_header").text("ویرایش استعلام بها > اطلاعات پایه")

        if($("#group_code_hidden_00").val()){// && parseInt($("#group_code_hidden_00").val()>0)){
            $("#form_section_inq_two").find('.footer').show();
        }
          else{
            $("#form_section_inq_two").find('.footer').hide();
        }
    }
    else{
        $("#form_section_inq_two").find('.footer').hide();
    }
    return true;
}

async function callback2(){
    var inq_id =  sessionStorage.getItem('inq_uncode');
    $("#form_section_inq_three").find('#content_step_three').text(inq_id);
    var params = {action:'create_description_seller',inq_id:inq_id}
    var res = await manageAjaxRequestCustom(params);
    var data=JSON.parse(res.data);
    $("#form_section_inq_three").find("#content_step_three").html(data.html+"<br>")
    var json_chart_data=data.chart_json;
    var formula_detailes=data.formula_detailes;
    for(k in json_chart_data){
        $("#box_"+k).append(
            `<div id='chart_box_${k}'  class="chart_box" style='width: 500px;margin: 0 auto;border:2px solid transparent;display: none'>
                <canvas style='display: block;width: 500px;height: 250px;background: #fff;box-shadow: 3px 3px 5px;border-radius: 10px;' id='chrt_${k}'></canvas>
            </div>`)
        createReportChartInq(json_chart_data[k],"0","#chrt_"+k);
    }

    for(h in formula_detailes) {
        var htm="";
          htm+=   `<div id='formula_box_${h}'  class="formula_box" style='width: 100%;margin: 0 auto;border:2px solid transparent;display: flex;gap: 1rem;direction:ltr;align-items: center;justify-content: center'>
              `;

        for (m in formula_detailes[h]) {

            htm += ` <div style="padding: 2rem;box-shadow: 3px 3px 3px #000;background: #e7eef3;overflow: hidden;border-radius: 10px;direction:rtl;display:none">
                        <h5>${formula_detailes[h][m]['seller_name']}</h5>
                        <table class="table table-borderd m-4"> <tr>
                        <td>
                            <span> :مبلغ پرداخت  چک</span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['export_term_payment']} </span>
                         </td>
                         </tr><tr>
                         <td>
                            <span> مدت  باز پرداخت</span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['pay_mount']} ماه   </span>
                         </td>
                     </tr>
                     <tr>
                          <td>
                            <span>درصد سود  </span>
                         </td>
                         <td>
                            <span>${(formula_detailes[h][m]['inq_rate_percent']*100)+"%"} </span>
                         </td>
                     </tr>
                      <tr>
                          <td>
                            <span>مبلغ پرداخت نقدی  </span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['export_cash_section']} </span>
                         </td>
                     </tr>
                     <tr>
                          <td>
                            <span>  درصد ارزش افزوده  </span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['vat_percent']}% </span>
                         </td>
                     </tr>
                      <tr>
                          <td>
                            <span>مبلغ ارزش افزوده  </span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['vat_amount']} </span>
                         </td>
                     </tr>
                     <tr>
                          <td>
                            <span>هزینه حمل درون شهری  </span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['rent_inside']} </span>
                         </td>
                     </tr>
                     <tr>
                          <td>
                            <span>هزینه حمل برون شهری  </span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['rent_outside']} </span>
                         </td>
                     </tr>
                     <tr><td>قیمت تمام شده بر اساس محاسبه نرم افزار</td><td>${(formula_detailes[h][m]['export_term_payment']*formula_detailes[h][m]['inq_rate_percent']*formula_detailes[h][m]['pay_mount'])+parseInt(formula_detailes[h][m]['export_term_payment'])+parseInt(numberformat2(formula_detailes[h][m]['export_cash_section']))+parseInt(formula_detailes[h][m]['vat_amount'])+parseInt(formula_detailes[h][m]['rent_inside'])+parseInt(formula_detailes[h][m]['rent_outside'])}</td></tr>
                    </table></div>`

        }
    htm+=`<div>`
        $("#box_" + h).append(htm);
    }

    $("#form_section_inq_three").find("#content_step_three").find('fieldset').each(function(){
        var array_handler=[];
        $(this).find('table').find('input[type="radio"]').each(function(){
            array_handler.push($(this).val());
        })
        var min=Math.min.apply(Math,array_handler)
        $(this).find('table').find('input[type="radio"]').each(function(){
            if($(this).val()==min){

                $(this).parents('tr').css('background',"#b9e1b9b3")

            }
        })
    })

}

async function show_inq_detailes(inq_id,input,status)
{
    var parent_elm="";
    var parent_section="";
    var id="";
    switch (status)
    {
        case 1:
            parent_elm=$("#export_inqs-section").find("#export_inqs_detailes");
            parent_section=$("#export_inqs-section")
            id="export_inqs_detailes"
            break;

        case 2:
            parent_elm=$("#import_inqs-section").find("#import_inqs_detailes")
            parent_section=$("#import_inqs-section")
            id="import_inqs_detailes"
            break;
        case 3:
            parent_elm=$("#sended_inqs-section").find("#sended_inqs_detailes")
            parent_section=$("#sended_inqs-section")
            id="sended_inqs_detailes"
            break;
        case 10:
            parent_elm=$("#archive_inqs-section").find("#archive_inqs_detailes")
            parent_section=$("#archive_inqs-section")
            id="archive_inqs_detailes"
            break;
    }
    if(!$(parent_elm).length){
        $(parent_section).append(`
                            <div id="${id}" class="modal" tabindex="-1" role="dialog">
                              <div style="max-width: 90vw" class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title">جزییات استعلام </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span style="color:red;font-size: 2rem" aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                   
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" onclick="open_send_modal(${inq_id},${status})" class="btn btn-primary"><i class="fa fa-paper-plane"></i></button>
                                    <button type="button"  id="archive_inq_btn" class="btn btn-success" > <i  class="fa fa-archive"><span>توقف و بایگانی استعلام</span style="font-family: IranSans">  </i></button>
                                    <button type="button" id="print_inq_btn" class="btn btn-info" ><i  class="fa fa-print"> <span style="font-family: IranSans">چاپ خروجی </span>   </i></button>
                                    <button type="button" style="font-family: IranSans" class="btn btn-danger" data-dismiss="modal"> بستن</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            `);
    }

    var params = {action:'create_description_seller',inq_id:inq_id}
    var res =  await manageAjaxRequestCustom(params);
    var data=JSON.parse(res.data);
    if(status!=1){
        $("#archive_inq_btn").remove();
       // $("#print_inq_btn").remove();
    }
    $(parent_section).find(".modal-body").html(data.html+"<br>")

    var json_chart_data=data.chart_json;
    var formula_detailes=data.formula_detailes;
    for(k in json_chart_data) {
        $(parent_section).find("fieldset#box_"+k)
          .append(
                `<div   id='chart_box_${k}'  class="chart_box" style='width: 500px;margin: 0 auto;border:2px solid transparent;display: none'>
            <canvas data-mdb-draggable-init class="draggable-element" style='display: block;width: 500px;height: 250px;background: #fff;box-shadow: 3px 3px 5px;border-radius: 10px;' id='chrt_${k}'></canvas>
        </div>`)
            createReportChartInq(json_chart_data[k], "0", "#chrt_" + k)
    }
    //-------------------------------------------------------------
    for(h in formula_detailes) {
        var htm="";
        htm+=   `<div id='formula_box_${h}'  class="formula_box" style='width: 100%;margin: 0 auto;border:2px solid transparent;display: flex;gap: 1rem;direction:ltr;align-items: center;justify-content: center'>
              `;

        for (m in formula_detailes[h]) {

            htm += ` <div style="padding: 2rem;box-shadow: 3px 3px 3px #000;background: #e7eef3;overflow: hidden;border-radius: 10px;direction:rtl">
                        <h5>${formula_detailes[h][m]['seller_name']}</h5>
                        <table class="table table-borderd m-4"> <tr>
                        <td>
                            <span> :مبلغ پرداخت مدت دار</span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['export_term_payment']} </span>
                         </td>
                         </tr><tr>
                         <td>
                            <span> مدت  باز پرداخت</span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['pay_mount']} ماه   </span>
                         </td>
                     </tr>
                     <tr>
                          <td>
                            <span>درصد سود  </span>
                         </td>
                         <td>
                            <span>${(formula_detailes[h][m]['inq_rate_percent']*100)+"%"} </span>
                         </td>
                     </tr>
                      <tr>
                          <td>
                            <span>مبلغ پرداخت نقدی  </span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['export_cash_section']} </span>
                         </td>
                     </tr>
                     <tr>
                          <td>
                            <span>  درصد ارزش افزوده  </span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['vat_percent']}% </span>
                         </td>
                     </tr>
                      <tr>
                          <td>
                            <span>مبلغ ارزش افزوده  </span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['vat_amount']} </span>
                         </td>
                     </tr>
                     <tr>
                          <td>
                            <span>هزینه حمل درون شهری  </span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['rent_inside']} </span>
                         </td>
                     </tr>
                     <tr>
                          <td>
                            <span>هزینه حمل برون شهری  </span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['rent_outside']} </span>
                         </td>
                     </tr>
                     <tr><td>قیمت تمام شده بر اساس محاسبه نرم افزار</td><td>${(formula_detailes[h][m]['export_term_payment']*formula_detailes[h][m]['inq_rate_percent']*formula_detailes[h][m]['pay_mount'])+parseInt(formula_detailes[h][m]['export_term_payment'])+parseInt(numberformat2(formula_detailes[h][m]['export_cash_section']))+parseInt(formula_detailes[h][m]['vat_amount'])+parseInt(formula_detailes[h][m]['rent_inside'])+parseInt(formula_detailes[h][m]['rent_outside'])}</td></tr>
                    </table></div>`

        }
        htm+=`<div>`
        $("#box_" + h).append(htm);
        $("#formula_box_"+h).hide();
    }
    //-------------------------------------------------------------

    $(input).parents('tr').addClass('inq_comment_row_select');
    $("#"+id).modal({backdrop:'static',keyboard:false},'show');
    $(parent_section).find(".modal-body .seller_description fieldset").each( function(){

        var array_handler=[];
        var min=0;
        $(this).find('table tr').find('input[type="radio"]').each( function(){
            array_handler.push($(this).val());
        })

        min=Math.min.apply(Math,array_handler)
        $(this).find('table tr').find('input[type="radio"]').each( function(){
            if(parseInt($(this).val())==parseInt(min)){

                $(this).parents('tr').css('background',"#b9e1b9b3")
                inq_select_seller($(this).attr('good_id'),$(this).attr('group_code'),$(this).attr('inquiry_id'));
            }
        })
    })
//-----------------------------------------------------------------------------------
    $("#"+id).on('hide.bs.modal', function(){
        $(input).parents('tr').removeClass('inq_comment_row_select');
        $("#"+id).remove();
    });
    //------------------------------------------------------------------------------
    //------------------------------------------------------------------------------    // بایگانی
    $("#archive_inq_btn").on('click',async function(){
        Swal.fire({
        title: "استعلام بعد از بایگانی شدن از  گردش خارج  می شود \n ادامه می دهید ؟",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "بایگانی شود",
        denyButtonText: `انصراف`
        }).then(async(result) => 
        {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var action="set_inq_archive";
                var params={action:action,inq_id:inq_id}
                var res=await manageAjaxRequestCustom(params)
        
                if(res.res=='true'){
                    $("#"+id).modal('hide');
                    custom_alerts('استعلام با موفقیت بایگانی شد','i',0,'')
                    go_to_section("#export_inqs",get_inquires,status,"export_inqs-section",refresh=1)
        
                }
                else{
                    custom_alerts(res.data,"e",0,'خطا');
                    return false;
                }
            } 
        });
       

    })
    //------------------------------------------------------------------------------//print
    $("#print_inq_btn").on('click',async function(){
        var print_paraf=0;
        var close_flag=0;
        Swal.fire({
            title: 'Print',
            text:"نسخه خروجی به همراه پاراف ها  چاپ شود ؟",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "بله",
            denyButtonText: 'خیر'
          }).then(async (result) => {
            if(result.isDismissed){
                close_flag=1;
            }
            if (result.isConfirmed) {
              print_paraf=1;
            } else if (result.isDenied) {
                print_paraf=0;
            }
        if(close_flag==1){
            return false;
        }
        var action="print_inq"
        var params={action:action,inq_id:inq_id,print_paraf:print_paraf}
        var res =await manageAjaxRequestCustom(params)
        if(res.res=="true"){
           $(res.data).printThis({

           })
        //    if($("print_p").length){
        //     $("print_p").remove();
        //    }
        //    $('body').append("<div id='print_p'>"+res.data+"</div>");
        }
        else{
            custom_alerts(res.data,'e',0,'خطا')
        }
    })
});
}

function inq_add_new_seller(input){
    if($("#add_new_seller_modal").length){
        $("#add_new_seller_modal").remove();
    }
    $('body').append(`

<div class="modal fade" id="add_new_seller_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ایجاد مشتری جدید</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span style="color:red;font-size:2rem" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="seller-name" class="col-form-label">نام مشتری:</label>
            <input type="text" class="form-control" id="seller-name">
          </div>
            <div class="form-group">
            <label for="seller-phon" class="col-form-label"> شماره همراه:</label>
            <input type="text" class="form-control" id="seller-phon">
          </div>
          <div class="form-group">
            <label for="address-text" class="col-form-label">آدرس مشتری:</label>
            <textarea class="form-control" id="address-text"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button"  class="btn btn-secondary" data-dismiss="modal">انصراف</button>
        <button type="button" id="do_save_seller_btn" class="btn btn-primary">ذخیره  </button>
      </div>
    </div>
  </div>
</div>`)
    
$("#add_new_seller_modal").modal({backdrop: 'static', keyboard: false},'show');
$("#do_save_seller_btn").on('click',async function(){
    var seller_name=$("#seller-name").val();
    var seller_phon=$("#seller-phon").val();
    var seller_address=$("#address-text").val();
    if(seller_name.trim()=="" || typeof seller_name.trim()=="undefined"){
        custom_alerts('نام فروشنده را وارد نمایید','e',0,'خطا');
        return false;
    }
     if(seller_phon.trim()=="" || typeof seller_phon.trim()=="undefined"){
        custom_alerts('شماره تلفن  فروشنده را وارد نمایید','e',0,'خطا');
        return false;
    }
     if(seller_address.trim()=="" || typeof seller_address.trim()=="undefined"){
        custom_alerts('نشانی  فروشنده را وارد نمایید','e',0,'خطا');
        return false;
    }
    var action="add_new_seller";
    var params={action:action,seller_name:seller_name,seller_phon:seller_phon,seller_address:seller_address}
    var res=await manageAjaxRequestCustom(params)

    if(res.res=="true"){
        custom_alerts(' اطلاعات  با موفقیت ذخیره شد','i',0,'ذخیره')
        $("#add_new_seller_modal").modal('hide');
    }
    else{
        custom_alerts('خطا در ذخیره اطلاعات','e',0,'خطا')
        return false;
    }
})

}
async function inq_select_seller(good_id,group_code,inquiry_id,mode=0){
    var action="inq_select_seller";
    var params={action:action,good_id:good_id,group_code:group_code,inquiry_id:inquiry_id,mode:mode}
    var res=await manageAjaxRequestCustom(params);


}
 function display_chart(input){
    var chart_box=$(input).parents('fieldset').children('.chart_box')

     var vis=$(chart_box).is(":hidden")
     if(vis){

         $(input).html("بستن نمودار مقایسه ای")
         $(input).css('color','red')
         $(input).attr('title','بستن نموار ')
         $(chart_box).css({'display':'block',"transition":"2s"});
     }
     else{

         $(input).html("نمایش نمودار مقایسه ای")
         $(input).css('color','blue')
         $(input).attr('title','مشاهده نمودار')
         $(chart_box).css({'display':'none',"transition":"2s"});
     }
}
function display_calc_detailes(input){
    var calc_detailes=$(input).parents('fieldset').children('.formula_box')
    var vis=$(calc_detailes).is(":hidden")
    if(vis){

        $(input).html("بستن جزییات محاسبه")
        $(input).css('color','red')
        $(input).attr('title','بستن جزییات محاسبه ')
        $(calc_detailes).css({'display':'flex',"transition":"2s"});
    }
    else{

        $(input).html("نمایش جزییات مقایسه")
        $(input).css('color','blue')
        $(input).attr('title','مشاهده محاسبه')
        $(calc_detailes).css({'display':'none',"transition":"2s"});
    }
}
async function check_inq_comment(good_id,group_code,inquiry_id){
    var action="check_inq_comment_reason_status";
    var params={action:action,good_id:good_id,group_code:group_code,inquiry_id:inquiry_id}
    var res=await manageAjaxRequestCustom(params)
   return res.data;
}
// function get_selected_radio(input) {
//     var checked="";
//     $(input).parents('table').find('input[type="radio"]').each(function () {
//         if ($(this).is(":checked")) {
//             checked = $(this).attr('id');
//         }
//     })
//     return checked;
// }

async function check_selected_radio(input)
    {
        selected_radio_inq=$(input).attr('id');
        var good_id = $(input).attr('good_id');
        var group_code = $(input).attr('group_code');
        var inquiry_id = $(input).attr('inquiry_id');
        var checked = "";
        $(input).parents('table').find('input[type="radio"]').each(function () {
            if ($(this).is(":checked")) {
                checked = $(this).attr('id');
            }
        })
console.log(checked)
    var current=$(input).attr('id')
        if(current==checked){
            return false;
        }
    if(!$("#inq_comment").length) {

        $('body').append(`
                <div id="inq_comment" class="modal" tabindex="-1" role="dialog" style="background: rgba(128,128,128,0.5)" >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"> ثبت  علت انتخاب  </h5>

                    </div>
                    <div class="modal-body">
                        <p style="padding:1rem">دلیل انتخاب  فروشنده را وارد نمایید</p>
                        <div style="width:100%;text-align:center">
                            <textarea id="inq_comment_detailes" class="form-control" cols="45" rows=5></textarea>
                        </div>
                    </div>
                    <div style="display: flex;justify-content: space-between; width:100%;padding: 10px;">
                     <button style="width: 80px" type="button"   id="set_inq_comment" onclick="select_seller()" class="btn btn-primary">تایید</button>
                        <button style="width: 80px"  type="button"  id="close_del_modal" class="btn btn-danger" data-dismiss="modal">انصراف</button>
                    </div>
                    </div>
                </div>
                </div>`);
    }
    else{
        $("#inq_comment").find("#inq_comment_detailes").val('');

    }


   $("#inq_comment").modal({backdrop: 'static', keyboard: false}, 'show');
    $("#close_del_modal").click(function ()
    {
        $(input).parents('table').find('tr').each(function ()
        {
            var selected = $(this).attr('selected_by_system');
            if (selected == "true") {
                $(this).find('input[type="radio"]').prop('checked', true)
            }
        })
        $("#inq_comment").modal('hide');


    })
//             //-------------------------------------------
}

 async function select_seller ()
{

    var input="#"+selected_radio_inq;
    var inq_comment_detailes = $("#inq_comment_detailes").val();
    var good_id = $(input).attr('good_id');
    var inquiry_id = $(input).attr('inquiry_id');
    var group_code = $(input).attr('group_code');
    if (!inq_comment_detailes)
    {
        custom_alerts('ثبت توضیحات اجباری می باشد', "e", 0, 'ثبت توضیحات')
        return false;
    }
    save_inq_comment_reason(good_id,inquiry_id,group_code,inq_comment_detailes,input)
    $("#inq_comment").modal('hide');
    $(input).prop('checked',true)
}


async function save_inq_comment_reason(good_id,inquiry_id,group_code,inq_comment_detailes,input) {
    var action = 'save_inq_comment_reason';

    var params = {
        action: action,
        good_id: good_id,
        group_code: group_code,
        inq_comment_detailes: inq_comment_detailes,
        inquiry_id: inquiry_id
    }
    var res = await manageAjaxRequestCustom(params)
    if (res.res == "true") {
        custom_alerts("توضیحات با موفقیت ذخیره شد", 'i', 0, 'ذخیره')
        $(input).prop('checked', true)
    }
}

async function display_selected_seller_history(input,group_code,good_id,inquiry_id){
    var history_box=$(input).parents('fieldset').find(".seller_history");
    if($(history_box).is(":visible")){
        $(history_box).remove();
        $(input).css('color','blue')
        $(input).text('نمایش  سوابق انتخاب فروشنده')
    }
    else {

        $(input).css('color','red')
        $(input).text('بستن  سوابق انتخاب فروشنده')
        var action = "display_selected_seller_history"
        var params = {action: action, group_code: group_code, good_id: good_id, inquiry_id: inquiry_id}
        var res = await manageAjaxRequestCustom(params)
        if (res.res == "true") {

            if (history_box) {
                $(history_box).remove();
            }
            $(input).parents('fieldset').append(`<fieldset class="seller_history" style="border: 2px solid gray ;border-radius: 10px"><legend style="width: auto;font-size: 1rem;padding: 10px">سوابق  انتخاب فروشندگان</legend><div class=" m-auto p-2" style="width: 50%;background: #e0e8eb;border-radius: 10px;padding: 10px;box-shadow: 3px 3px 4px;" >${res.data}</div></fieldset>`)

        }
    }
}
async function save_selected_inq_comment(input,comment){

        var inq_comment_detailes = $("#inq_comment_detailes").val();
        if (!inq_comment_detailes) {
            custom_alerts('ثبت توضیحات اجباری می باشد', "e", 0, 'ثبت توضیحات')
            return false;
        }
        var action = "" +
            "";
        var inq_id = $(input).attr('inquiry_id')
        var inq_group_code = $(input).attr('group_code')
        var good_id = $(input).attr('good_id')

        var params = {
            action: action,
            inq_id: inq_id,
            inq_group_code: inq_group_code,
            inq_comment_detailes: inq_comment_detailes,
            good_id: good_id
        }
        var res = await manageAjaxRequestCustom(params);
        if (res.res == "true") {
            custom_alerts('اطلاعات با موفقیت ذخیره شد', "i", 0, 'ثبت موفق')
        }

}
function createReportChartInq(jsonChartData,chartType,convas_id){
    const chartArgs = jsonChartData;
    console.log(chartArgs[0])
    console.log(typeof chartArgs)
    if(typeof chartArgs != "undefined" && chartArgs[0]){
        //var ctx = document.getElementById('chrt');
        var ctx = $(convas_id);
        cleanChartArgs = {
            labels : [] ,
            value : [] ,
            bgColor : [] ,
            borderColor : []
        };
        for(let i=0;i<chartArgs.length;i++){
            const currentIndex = chartArgs[i];
            cleanChartArgs.labels.push(currentIndex.label);
            console.log(currentIndex.label);
            cleanChartArgs.value.push(currentIndex.value);
            cleanChartArgs.bgColor.push(currentIndex.color[0]);
            cleanChartArgs.borderColor.push(currentIndex.color[1]);
        }
        switch (chartType){
            case "0":
                var chartType1="bar";
                break;
            case "1":
                var chartType1="pie";
                break;
        }
        if(ctx){

            var myChart = new Chart(ctx, {
                type: chartType1,
                data: {
                    labels: cleanChartArgs.labels,
                    datasets: [{
                        label: ['نمودار فروشندگان'],
                        data: cleanChartArgs.value,
                        backgroundColor: cleanChartArgs.bgColor,
                        borderColor: cleanChartArgs.borderColor,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{ticks: {beginAtZero: true}}],
                        xAxes: [{ticks: {fontSize: 14,fontStyle: "bold",fontFamily: "dubai-bold"}}]
                    }
                }
            });
        }
    }
    //*********************************
}


function open_user_comment(inq_id,inq_group_code){
    bs_prompt('ثبت نظر در باره استعلام ','','inq_save_comment',[inq_id,inq_group_code],1,'نظر خودرا وارد نمایید');
}

function callback3(){

    var inq_id =  sessionStorage.getItem('inq_uncode');
    if(!$("#final_confirm").length)
    {
        $('body').append(` 
            <div id="final_confirm" class="modal" tabindex="-1" role="dialog" >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> تایید نهایی </h5>
    
                </div>
                <div class="modal-body">
                    <p style="padding:1rem">بعد از تایید نهایی قادر به  ویرایش نخواهید بود <br>ادامه می دهید ؟</p>
                   
                </div>
                <div style="display: flex;justify-content: space-between; width:100%;padding: 10px;">
                 <button style="width: 80px" type="button"   id="final_confirm_btn" class="btn btn-primary">تایید</button>
                    <button style="width: 80px"  type="button"  id="close_del_modal" class="btn btn-danger" data-dismiss="modal">انصراف</button>
                </div>
                </div>
            </div>
            </div>`);
    }

    $("#final_confirm").modal({backdrop: 'static', keyboard: false}, 'show');
    $("#final_confirm_btn").on("click",async function(){
        var action="inq_final_confirm";
        var params={action:action,inq_id:inq_id}
        var res= await manageAjaxRequestCustom(params)
       if(res.res=="true"){

           custom_alerts(res.data,"i",0,'ذخیره')
           $("#final_confirm").modal('hide');
           $("#export_inqs").click();
       }
       else{
           custom_alerts(res.data,"e",0,'خطا')
       }


    })
}



function callback4(){

    var inq_id =  sessionStorage.getItem('inq_uncode')
    var action="send_inquiry";
    var params={action:action,inq_id:inq_id}
    var res=manageAjaxRequestCustom(params);
}


function add_seller_buyer_inq(input,parent,form_good_index,mode=0){
    var good_name=$("#inq_good_name_"+form_good_index).val();
    var good_id=$("#inq_good_name_"+form_good_index+"_hidden").val();
    var good_unit=$("#inq_good_quantity_unit_"+form_good_index).val();
    var good_quantity=$("#inq_good_quantity_"+form_good_index).val();
    var mode_handler=form_good_index+"_"+mode
    var vat_percent=0;
    if(vat_percent){
        var vat_percent=inq_fix_data.vat_percent;
    }


    sessionStorage.setItem(mode_handler,'edit')
    if(good_name.trim()=="" || good_name.trim()==0 ){
        custom_alerts('نام کالا را وارد نمایید','e','خطا');
        return false;
    }
    if(good_unit.trim()=="" || good_unit.trim()==0){
        custom_alerts('واحد کالا را وارد نمایید','e','خطا');
        return false;
    }
    if(good_quantity.trim()==""||good_quantity.trim()==0){
        custom_alerts('تعداد کالا را وارد نمایید','e','خطا');
        return false;
    }
    var form_parent=$(input).parents("#form_create_export_inq");
    var good_selects=$(form_parent).children().find('input[type="text"][id^="inq_good_name_"]')
    var counter=0;
    var flag=true;
    $(good_selects).each(function(){
        if($(this).val()==good_name){
            counter++;
            if(counter>1){ custom_alerts("این کالا قبلا  ثبت شده و تکراری می باشد",'e',0,'خطا');
                flag=false}
        }
    })
    if(!flag){
        return false
    }

    var options=`<option value="0">انتخاب فروشنده/خریدار</option>`;
    var action="inq_get_all_buyer_sellers"
    var params={action:action};
   var res=ajaxHandler(params)//--------------------get all seller/buyer-----------------------------------------
   // var res=manageAjaxRequest(params)//--------------------get all seller/buyer-----------------------------------------
    for(i in res){
        options+=`<option value="${res[i]['RowID']}">${res[i]['Name']}</option>`
    }

    var result=[];
    result = handler_seller_data(mode, good_id);
    if(result.length>0 && !$(input).attr('fetch')){
        for (var j = 0; j < result.length; j++)
        {

            var parent_box_index = $(input).attr('parent_box_index');
            var form_index1 = $(parent).find('form.form-seller').length;
            form_index = form_index1 + parent_box_index;
            var form_element = {
                'type': 'form',
                "class": " from form-seller form-inline grid-form",
                'id': "form_create_seller_row_" + form_index
            }
            var seller_name_hidden_value = result[j]['export_inq_seller_name'] ? result[j]['export_inq_seller_name'] : ""
            var seller_code = {
                'type': 'hidden', 'id': "export_inq_seller_id_hidden_" + form_index, 'value': seller_name_hidden_value
            }
            var seller_name = {
                'type': 'select',
                'options': options,
                "class": "form-control",
                "placholder": "نام فروشنده/خریدار ",
                'id': "export_inq_seller_name_" + form_index,
                'style': 'width:50%',
                'data-display': "static",
                'onchange': " set_inq_data(this,'#export_inq_seller_id_hidden_" + form_index + "')",
            }
            var good_code = {
                'type': 'hidden',
                'id': "export_inq_good_id_hidden_" + form_index,
                'value': "",
                'disabled': true
            }
            var good_quantity = {
                'type': 'text',
                "class": "form-control",
                'min': '0',
                "placeholder": "تعداد کالا ",
                'id': "export_good_quantity_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',
                'onblur': "check_inq_allowed_good_quantity(this," + parent_box_index + ")",
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
                'onchange': "numberformat(this,1)",
                'value': result[j]['export_good_quantity'] ? result[j]['export_good_quantity'] : ""
            }
            var unit_price = {
                'type': 'text',
                "class": "form-control",
                'min': '0',
                "placeholder": "قیمت براساس واحد ",
                'id': "export_unit_price_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
                'onchange': "numberformat(this,1)",
                'value': result[j]['export_unit_price'] ? result[j]['export_unit_price'] : ""
            }
            //*******----------------------
            //**-------------
            var cash_section = {
                'type': 'text',
                "class": "form-control",
                'min': '0',
                "placeholder": "پرداخت نقدی ",
                'id': "export_cash_section_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',
                'value': result[j]['export_cash_section'],
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
                'onchange': "numberformat(this,1)"

            }
            var term_payment = {
                'type': 'text',
                "class": "form-control",
                'min': '0',
                "placeholder": "پرداخت چک ",
                'id': "export_term_payment_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',
                'value': result[j]['export_term_payment'],
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
                'onchange': "numberformat(this,1)"

            }//----------------------

            var vat = {
                'type': 'select',
                "class": "form-control",
                'placeholder':"مقدار ارزش افزوده",
                'id': "vat_status_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',

                "onfocusin": "set_label(this,1)",
                "options":"<option style='color:red' value='0'>ارزش افروده ندارد</option ><option style='color:green' value='1'>ارزش افروده دارد</option>",
                'onchange': "toggle_vat_amount(this,'"+form_index+"')"

            }
            var vat_amount = {
            'type': 'text',
            "class": "form-control",
            "placeholder": "مقدار ارزش افزوده  ",
            'id': "export_vat_amount_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            'value': "",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onchange': "numberformat(this,1)",
            'disabled':true
            }

            //------------------------------------------
            var inq_description = {
                'type': 'textarea',
                "class": "form-control",
                'min': '0',
                "placeholder": "توضیحات ",
                'id': "export_description_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
            }

            //*******----------------------
            var pay_method = {
                'type': 'number',
                "class": "form-control",
                "placeholder": "مدت باز پرداخت براساس ماه",
                'id': "export_pay_method_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',
                'value': "",
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
                'value': result[j]['export_pay_method'] ? result[j]['export_pay_method'] : ""
            }
            var pay_detailes = {
                'type': 'text',
                "class": "form-control",
                "placeholder": "جزییات پرداخت",
                'id': "export_pay_detailes_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray;',
                'value': "",
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
                'value': result[j]['export_pay_detailes'] ? result[j]['export_pay_detailes'] : ""
            }
            var rent_inside = {
                'type': 'text',
                "class": "form-control",
                'min': '0',
                "placeholder": " هزینه حمل و نقل داخلی",
                'id': "export_rent_inside_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',
                'value': "",
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
                'onchange': "numberformat(this,1)",
                'value': result[j]['export_rent_inside'] ? result[j]['export_rent_inside'] : ""
            }
            var rent_outside = {
                'type': 'text',
                "class": "form-control",
                'min': '0',
                "placeholder": " هزینه حمل و نقل خارجی ",
                'id': "export_rent_outside_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
                'onchange': "numberformat(this,1)",
                'value': result[j]['export_rent_outside'] ? result[j]['export_rent_outside'] : ""
            }

            var inq_btn_save = {
                "type": "button",
                "class": "btn btn-success",
                "title": "بروزرسانی",
                'parent':'0',
                'id': "export_inq_btn_save_" + form_index,
                "onclick": 'add_seller_buyer_save(this,\'' + form_index + '\',\'' + form_good_index + '\','+mode+')'
            };
            var inq_btn_delete = {
                "type": "button",
                "class": "btn btn-danger",
                "title": "حذف",
                'id': "del_seller_buyer_" + form_index,
                "onclick": 'delete_seller_buyer_form(this,\'#form_create_seller_row_' + form_index + '\','+good_name+','+sessionStorage.getItem('inq_id')+')'
            };
            var group_code={
                'type': 'hidden',
                'id': "group_code_hidden_" + form_index,
                'value':result[j]['group_code'] ? result[j]['group_code'] : ""
            }
            var line={
                 'type': 'line',
                'style':"3px solid red"
            }

            create_form_element(parent, form_element);
            create_form_element("#form_create_seller_row_" + form_index, seller_code);
            create_form_element("#form_create_seller_row_" + form_index, seller_name);
            create_form_element("#form_create_seller_row_" + form_index, good_code);
            create_form_element("#form_create_seller_row_" + form_index, good_quantity);
            create_form_element("#form_create_seller_row_" + form_index, unit_price);
            create_form_element("#form_create_seller_row_" + form_index, vat);
            create_form_element("#form_create_seller_row_" + form_index, vat_amount);
            create_form_element("#form_create_seller_row_" + form_index, cash_section);
            create_form_element("#form_create_seller_row_" + form_index, term_payment);
            create_form_element("#form_create_seller_row_" + form_index, pay_method);
           // create_form_element("#form_create_seller_row_" + form_index, pay_detailes);
            create_form_element("#form_create_seller_row_" + form_index, rent_inside);
            create_form_element("#form_create_seller_row_" + form_index, rent_outside);
            create_form_element("#form_create_seller_row_" + form_index, inq_description);
            create_form_element("#form_create_seller_row_" + form_index, line);

            create_form_element("#form_create_seller_row_" + form_index, inq_btn_save);

            create_form_element("#form_create_seller_row_" + form_index, inq_btn_delete);
            create_form_element("#form_create_seller_row_" + form_index, group_code);
            select_data_picker("#export_inq_seller_name_" + form_index)
            $("#export_description_" + form_index).val(result[j]['export_description']);
            $("#export_inq_seller_name_" + form_index).val(result[j]['export_inq_seller_name'])
            $("#export_inq_seller_name_" + form_index).selectpicker('refresh');
            $("#vat_status_" + form_index).val(result[j]['is_vat'])
           //------------------------------------------------------------------------
            toggle_vat_amount($("#vat_status_"+form_index),form_index)
           // var vat=$("#vat_status_"+form_index).val();
           // if(vat==0){
           //      $("#vat_status_"+form_index).css('color','red')
           //      $("#export_vat_amount_"+form_index).parent().hide();
           // }
           // else{
           //       $("#vat_status_"+form_index).css('color','green')
           //        $("#export_vat_amount_"+form_index).parent().show();
           //        $("#export_vat_amount_"+form_index).val();
           //     vat_percent
           // }

        }
        $("#add_good").removeAttr('disabled')

        $(parent).find('form.form-seller').find('input[type="text"],input[type="number"],textarea').each(function(){
            set_label($(this),1);
        })

    }

    else{

        var parent_box_index = $(input).attr('parent_box_index');
        var form_index1 = $(parent).find('form.form-seller').length;
        form_index = form_index1 + parent_box_index;
        var form_element = {
            'type': 'form',
            "class": " from form-seller form-inline grid-form",
            'id': "form_create_seller_row_" + form_index
        }
        // var seller_name_hidden_value = all_records > 1 ? result[j]['export_inq_seller_name'] : ""
        var seller_code = {
            'type': 'hidden', 'id': "export_inq_seller_id_hidden_" + form_index
        }
        var seller_name = {
            'type': 'select',
            'options': options,
            "class": "form-control",
            "placholder": "نام فروشنده/خریدار ",
            'id': "export_inq_seller_name_" + form_index,
            'style': 'width:50%',
            'data-display': "static",
            'onchange': " set_inq_data(this,'#export_inq_seller_id_hidden_" + form_index + "')",
        }
        var good_code = {
            'type': 'hidden',
            'id': "export_inq_good_id_hidden_" + form_index,
            'value': "",
            'disabled': true
        }
        var good_quantity = {
            'type': 'text',
            "class": "form-control",
            'min': '0',
            "placeholder": "تعداد کالا ",
            'id': "export_good_quantity_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',

            'onblur': "check_inq_allowed_good_quantity(this," + parent_box_index + ")",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onchange': "numberformat(this,1)"

        }
        var unit_price = {
            'type': 'text',
            "class": "form-control",
            'min': '0',
            "placeholder": "قیمت براساس واحد ",
            'id': "export_unit_price_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            'value': "",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onchange': "numberformat(this,1)"

        }
        //**-------------
        var cash_section = {
            'type': 'text',
            "class": "form-control",
            'min': '0',
            "placeholder": "پرداخت نقدی ",
            'id': "export_cash_section_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            'value': "",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onchange': "numberformat(this,1)"

        }
        var term_payment = {
            'type': 'text',
            "class": "form-control",
            'min': '0',
            "placeholder": "پرداخت چک ",
            'id': "export_term_payment_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            'value': "",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onchange': "numberformat(this,1)"
        }
        //-----------------------------------------
        var vat = {
                'type': 'select',
                "class": "form-control",
                'placeholder':"مقدار ارزش افزوده",
                'id': "vat_status_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',

                "onfocusin": "set_label(this,1)",
                "options":"<option style='color:red' value='0'>ارزش افروده ندارد</option ><option style='color:green' value='1'>ارزش افروده دارد</option>",
                'onchange': "toggle_vat_amount(this,'"+form_index+"')"

            }
            var vat_amount = {
            'type': 'text',
            "class": "form-control",
            "placeholder": "مقدار ارزش افزوده  ",
            'id': "export_vat_amount_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray display:none',
            'value': "",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onchange': "numberformat(this,1)",
            'disabled':true
        }
            //------------------------------------------
        var inq_description = {
            'type': 'textarea',
            "class": "form-control",
            'min': '0',
            "placeholder": "توضیحات ",
            'id': "export_description_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            'value': "",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',

        }
        //*******----------------------
        var pay_method = {
            'type': 'number',
            "class": "form-control",
            "placeholder": " مدت باز پرداخت براساس ماه ",
            'id': "export_pay_method_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            'value': "",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)'

        }
        var pay_detailes = {
            'type': 'text',
            "class": "form-control",
            "placeholder": "جزییات پرداخت",
            'id': "export_pay_detailes_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray;',
            'value': "",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)'

        }
        var rent_inside = {
            'type': 'text',
            "class": "form-control",
            'min': '0',
            "placeholder": " هزینه حمل و نقل داخلی",
            'id': "export_rent_inside_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            'value': "",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onchange': "numberformat(this,1)"

        }
        var rent_outside = {
            'type': 'text',
            "class": "form-control",
            'min': '0',
            "placeholder": " هزینه حمل و نقل خارجی ",
            'id': "export_rent_outside_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onchange': "numberformat(this,1)"

        }
        var inq_btn_save = {
            "type": "button",
            "class": "btn btn-success",
            "title": "ذخیره",
            'parent':'0',
            'id': "export_inq_btn_save_" + form_index,
            'style': "width:100% !important",
            "onclick": 'add_seller_buyer_save(this,\'' + form_index + '\',\'' + form_good_index + '\','+ sessionStorage.getItem('inq_uncode')+')'
        };
        var inq_btn_delete = {
            "type": "button",
            "class": "btn btn-danger",
            "title": "حذف",
            'id': "del_seller_buyer_" + form_index,
            'style':"width:100%;display:none ",
            "onclick": 'delete_seller_buyer_form(this,\'#form_create_seller_row_' + form_index + '\')'
        };
        var group_code={
            'type': 'hidden',
            'id': "group_code_hidden_" + form_index,
        }
        var line={
            "type":"line",
            'style':'border:3px solid red'
        }
        create_form_element(parent, form_element);
        create_form_element("#form_create_seller_row_" + form_index, seller_code);
        create_form_element("#form_create_seller_row_" + form_index, seller_name);
        create_form_element("#form_create_seller_row_" + form_index, good_code);
        create_form_element("#form_create_seller_row_" + form_index, good_quantity);
        create_form_element("#form_create_seller_row_" + form_index, unit_price);
        create_form_element("#form_create_seller_row_" + form_index, vat);
        create_form_element("#form_create_seller_row_" + form_index, vat_amount);
        create_form_element("#form_create_seller_row_" + form_index, cash_section);
        create_form_element("#form_create_seller_row_" + form_index, term_payment);

        create_form_element("#form_create_seller_row_" + form_index, pay_method);
        //create_form_element("#form_create_seller_row_" + form_index, pay_detailes);
        create_form_element("#form_create_seller_row_" + form_index, rent_inside);
        create_form_element("#form_create_seller_row_" + form_index, rent_outside);
       // create_form_element("#form_create_seller_row_" + form_index, rent_outside);
        create_form_element("#form_create_seller_row_" + form_index, inq_description);
          create_form_element("#form_create_seller_row_" + form_index, line);
        create_form_element("#form_create_seller_row_" + form_index, inq_btn_save);
        create_form_element("#form_create_seller_row_" + form_index, inq_btn_delete);
        create_form_element("#form_create_seller_row_" + form_index, group_code);
        select_data_picker("#export_inq_seller_name_" + form_index)
        $(input).attr('disabled', true)

        //----------------------------------
         var vat=$("#vat_status_"+form_index).val();
           if(vat==0){
                $("#vat_status_"+form_index).css('color','red')
                $("#export_vat_amount_"+form_index).parent().hide();
           }
           else{
                 $("#vat_status_"+form_index).css('color','green')
                  $("#export_vat_amount_"+form_index).parent().show();
           }

    }
    $(input).attr('fetch','1')
    $(input).parents(".add_seller_btn_box").hide();
    if(!result.length){
        $("#del_seller_buyer_" + form_index).parent().hide();
    }
    $("#del_seller_buyer_" + form_index).parent().show();
}

function toggle_vat_amount(input,form_index){
    if($(input).val()==0){
        $("#vat_status_"+form_index).css('color','red')
        $("#export_vat_amount_"+form_index).parent().hide();
        $("#export_vat_amount_"+form_index).val(0);
    }
    else{
        var vat_percent=0;
        if(inq_fix_data.vat_percent){
            vat_percent=inq_fix_data.vat_percent;
        }
       // var vat_percent=inq_fix_data.vat_percent;
        var total_vat_amount=numberformat2(parseInt($("#export_good_quantity_"+form_index).val()))*parseInt(numberformat2($("#export_unit_price_"+form_index).val()))*parseInt(numberformat2(vat_percent))/100;
        $("#vat_status_"+form_index).css('color','green')
        $("#export_vat_amount_"+form_index).parent().show();
        $("#export_vat_amount_"+form_index).val(total_vat_amount);
    }
}

function handler_seller_data(inq_id,good_id){
    var action='get_seller_data';
    var params={action:action,inq_id:inq_id,good_id:good_id}
    var res=ajaxHandler(params)
    return res;
}

function set_label(input,enter){
    var input_value=$(input).val();
    var parent=$(input).parent();
    if(enter==1) {
        if (!$(parent).find(".input_label").length) {
            $(parent).css('position', 'relative')
            $(parent).append(`<label class="input_label">${$(input).attr('placeholder')}</label>`)
            $(input).attr('placeholder', '');
            $(parent).css('border','none')
        }
    }
    if(enter==0) {
        if ($(input).val()) {
            $(input).css('border', '1px solid #b4d1c6')
        }
        else
        {
            $(parent).css('position', 'relative')
            $(input).attr('placeholder', $(parent).find(".input_label").text());
            $(parent).find(".input_label").remove()
        }
    }
    $(input).css({'background':'transparent',"text-align":"center"})
}
async function add_seller_buyer_save(input,seller_form_index,good_form_index,inq_id=0){

    //-------------- good form elements
    var inq_good_name=$("#inq_good_name_"+good_form_index+"_hidden").val();
    var inq_good_quantity_unit=$("#inq_good_quantity_unit_"+good_form_index).val();
    var inq_good_quantity=$("#inq_good_quantity_"+good_form_index).val();
    var inq_good_buy_request_num=$("#inq_good_buy_request_num_"+good_form_index).val();
    var inq_good_desc=$("#inq_good_desc_"+good_form_index).val();
    var buy_base_desc=$("#buy_base_desc_"+good_form_index).val();

    //-------------------------seller form elements
    var export_inq_seller_name=$("#export_inq_seller_name_"+seller_form_index).val()
    var export_good_quantity=numberformat2($("#export_good_quantity_"+seller_form_index).val())
    var is_vat=$("#vat_status_"+seller_form_index).val();

    var export_unit_price=numberformat2($("#export_unit_price_"+seller_form_index).val())
    var export_pay_method=$("#export_pay_method_"+seller_form_index).val()
    //var export_pay_detailes=$("#export_pay_detailes_"+seller_form_index).val()
    var export_rent_inside=numberformat2($("#export_rent_inside_"+seller_form_index).val())
    var export_rent_outside=numberformat2($("#export_rent_outside_"+seller_form_index).val());
    var seller_group_code = $("#group_code_hidden_"+seller_form_index).val()
    var export_cash_section = $("#export_cash_section_"+seller_form_index).val();
    var export_term_payment = numberformat2($("#export_term_payment_"+seller_form_index).val());
    var export_description = $("#export_description_"+seller_form_index).val();
    //-----------------------------------------------------------------------------
    var inq_good_buy_request_num = $("#inq_good_buy_request_num_"+good_form_index).val();
    var inq_good_desc = $("#inq_good_desc_"+good_form_index).val();
    var buy_base_desc = $("#buy_base_desc_"+good_form_index).val();
    //-----------------------------------------------------------------------------

    if(export_inq_seller_name.trim()=="" || export_inq_seller_name.trim()==0){
        custom_alerts('نام فروشنده وارد نشده است','e','خطا')
        return false;
    }
    if(export_good_quantity.trim()=="" || export_good_quantity.trim()==0){
        custom_alerts('تعداد کالا وارد نشده است','e','خطا')
        return false;
    }
    if(export_unit_price.trim()=="" || export_unit_price.trim()==0){
        custom_alerts('قیمت واحد کالا وارد نشده است','e','خطا')
        return false;
    }

   
     if(export_cash_section.trim()==""){
        custom_alerts('مبلغ پرداخت نقدی   وارد نشده است','e','خطا')
        return false;
    }
     

    if(export_term_payment>0){
        if(export_term_payment.trim()==""){
            custom_alerts('مبلغ چک   وارد نشده است','e','خطا')
            return false;
        }
    }
    if(export_rent_inside.trim()=="" || export_inq_seller_name.trim()==0){
        custom_alerts('کرایه حمل داخلی وارد نشده است','e','خطا')
        return false;
    }
    if(export_rent_outside.trim()=="" || export_rent_outside.trim()==0){
        custom_alerts('کرایه حمل خارجی وارد نشده است','e','خطا')
        return false;
    }
    if($("#vat_status_"+seller_form_index).val()==1){
        var t1=(numberformat2(export_good_quantity)*numberformat2(export_unit_price))+parseFloat(numberformat2($("#export_vat_amount_"+seller_form_index).val()))

        var t2=parseFloat(numberformat2(export_cash_section))+parseFloat(numberformat2(export_term_payment));

        if(t1!=t2){
            
            custom_alerts('مجموع مبلغ نقدی و غیر نقدی با  قیمت تمام شده مساوی نمی باشد ','e',0,'خطا');
            return false;
        }
       

    }
    else{
        
        var t1=(numberformat2(export_good_quantity)*numberformat2(export_unit_price))
        alert(t1);
        var t2=parseFloat(numberformat2(export_cash_section))+parseFloat(numberformat2(export_term_payment));
        alert(t1);
        if(t1!=t2){
            custom_alerts('مجموع مبلغ نقدی و غیر نقدی با  قیمت تمام شده مساوی نمی باشد ','e',0,'خطا');
            return false;
        }
       
    }
    var action="add_seller_buyer_save";
    var params=
        {
            action:action,is_vat:is_vat,inq_good_name:inq_good_name,inq_good_quantity_unit:inq_good_quantity_unit,inq_id:inq_id,
            inq_good_buy_request_num:inq_good_buy_request_num,inq_good_desc:inq_good_desc,buy_base_desc:buy_base_desc,
            inq_good_quantity:inq_good_quantity,export_inq_seller_name:export_inq_seller_name,export_good_quantity:export_good_quantity,
            export_unit_price:export_unit_price,export_pay_method:export_pay_method,
            export_rent_inside:export_rent_inside,export_rent_outside:export_rent_outside,seller_group_code:seller_group_code,
            export_cash_section:export_cash_section,export_term_payment:export_term_payment,export_description:export_description,
            inq_good_buy_request_num:inq_good_buy_request_num,inq_good_desc:inq_good_desc,buy_base_desc:buy_base_desc
        }
    var res = await manageAjaxRequestCustom(params);
    if(res.data>0){
        $("#add_good").attr('disabled',false);
        $("#add_seller_"+good_form_index).attr('disabled',false);
        if($("#add_seller_btn_"+good_form_index).length){
            $("#add_seller_btn_"+good_form_index).remove();
        }
        var abb_btn='<div style="width: 82%;margin: auto;padding-block: 10px"> <button id="add_seller_btn_'+good_form_index+'" parent_box_index="'+good_form_index+'" type="button" class="btn"  style="width: 100%;background: #e9ecef;color:#fff;font-size: 2rem"> + </button> </div>'
        $(input).parents('#form_create_inq_box_'+good_form_index).append(abb_btn);
        $("#add_seller_btn_"+good_form_index).click(function(){
            $("#add_seller_"+good_form_index).click();
            $(this).remove();
        })
        $("#form_create_seller_row_"+seller_form_index).find('input,select,button').each(function(){
            $(this).attr('disabled',true)
        })
        $("#del_seller_buyer_"+seller_form_index).attr('disabled',false)
        $("#form_create_seller_row_"+seller_form_index).css("position",'relative')
        var text="رکورد با موفقیت ذخیره شد"
        $("#form_create_seller_row_"+seller_form_index).prepend(`<p style='color:green;position: absolute;top:-15px;padding: 10px;background: #dbe9e9'><i class="fa fa-check"></i>  ${text}  </p>`)
        $("#add_good").show();
    }

    $("#del_seller_buyer_"+seller_form_index).show();
    $("#export_inq_btn_delete_inq_"+good_form_index).show();
    $("#form_section_inq_two").find('.footer').show(100);
}
function check_sellers_goods(){
    return true;
}
function check_inq_allowed_good_quantity(input,index){
    var parent=$(input).parents(".form_create_inq_box");
    var quantity=numberformat2($(input).val());
    var allowed_quantity=numberformat2($("#inq_good_quantity_"+index).val());
    if(parseInt(quantity)>parseInt(allowed_quantity)){
        custom_alerts('مقدار وارد شده از مقدار درخواست شده بیشتر می باشد','e',return_value="0",title="خطا")
        $(input).val('')
        return false;
    }
}

function custom_alerts(message,mes_type,return_value="0",title="")
{
    var icon_type="";
    switch (mes_type) {
        case "e":

            Swal.fire({
                icon: 'warning',
                title: title,
                text: message,
                footer: ''
            });
            break;
        case "c":
            Swal.fire({
                title: message,
                icon: "question",
                iconHtml: "؟",
                confirmButtonText: "تایید",
                cancelButtonText: "انصراف",
                showCancelButton: true,
                showCloseButton: true
            });
            break;
        case "p":
            break;
        case "i":
            Swal.fire({
                position: "center-center",
                icon: "success",
                title: message,
                showConfirmButton: false,
                timer: 1500
            });

            break;
    }

    return return_value
}

function delete_all_form_inq(input,selector){
 //  alert( sessionStorage.getItem('inq_uncode'));
    var id=$(input).attr('id');
    var inq_id=sessionStorage.getItem('inq_uncode')
    $('body').append(` 
 <div id="delete_confirm2" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">حذف رکورد</h5>

      </div>
      <div class="modal-body">
        <p>رکورد به همراه فروشنده ها و کالا حذف شود ؟ ؟</p>
      </div>
      <div class="modal-footer">
        <button type="button"  id="close_del_modal" class="btn btn-danger" data-dismiss="modal">انصراف</button>
        <button type="button"  onclick="do_delete_all_form_inq ('${id}', '${selector}',  '${inq_id}')" id="btn_delete_confirm" class="btn btn-primary">تایید</button>
      </div>
    </div>
  </div>
</div>`);
    $("#delete_confirm2").modal('show');
    $("#close_del_modal").click(function () {
        $("#delete_confirm").modal('hide');
    })

}
 function delete_seller_buyer_form(input,selector,good_id,inq_id) {
    var id=$(input).attr('id');
     $('body').append(` 
 <div id="delete_confirm" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">حذف رکورد</h5>

      </div>
      <div class="modal-body">
        <p>رکورد حذف شود ؟</p>
      </div>
      <div class="modal-footer">
        <button type="button"  id="close_del_modal" class="btn btn-danger" data-dismiss="modal">انصراف</button>
        <button type="button"  onclick="do_delete_seller_buyer_form ('${id}', '${selector}', '${good_id}', '${inq_id}')" id="btn_delete_confirm" class="btn btn-primary">تایید</button>
      </div>
    </div>
  </div>
</div>`);
     $("#delete_confirm").modal('show');
     $("#close_del_modal").click(function () {
         $("#delete_confirm").modal('hide');
     })
 }

 function do_delete_seller_buyer_form (id, selector, good_id, inq_id) {

     $(selector).hide(2000);
     var siblings = $(selector).parents('#form_create_export_inq').find('.form_create_good_box').length;
     //form_create_seller_box_0
     if (good_id && inq_id) {
         var action = "delete_seller_buyer_row";
         var params = {action: action, good_id: good_id, inq_id: inq_id}
         var res = manageAjaxRequestCustom(params)
     }
     var input=$("#"+id)
     $(input).parents('.form_create_seller_box').siblings('.add_seller_btn_box').show()
     setTimeout(() => {
         $(selector).remove();
     }, 1000);
     if (siblings >= 2) {
         $("#add_good").removeAttr('disabled')
     }
     $("#delete_confirm").modal('hide');
 }
async function do_delete_all_form_inq (id, selector, inq_id){

if ( inq_id) {
    var action = "delete_all_seller_buyer_row";
    var params = {action: action, inq_id: inq_id}
    var res = await manageAjaxRequestCustom(params)
    $("#delete_confirm2").modal('hide');
    if(res.res=='true'){
        custom_alerts(res.data,'i',0,'حذف');
        $(selector).hide(2000);
        var input=$("#"+id)
        $(input).parents('.form_create_seller_box').siblings('.add_seller_btn_box').show()
        setTimeout(() => {
        $(selector).remove();
        }, 1000);
        if (siblings >= 2) {
            $("#add_good").removeAttr('disabled')
        }
       
        var form_length=$("#temp_inqs-section").children.find('.form-seller').length
        console.log('form_length'+form_length);
        if(!form_length){
            $('#step_btn_1').hide();
        }
        
    }
    else{
        custom_alerts(res.data,'e',0,'خطا');
    }
}
else{
    $(selector).hide(2000);
    var input=$("#"+id)
    $(input).parents('.form_create_seller_box').siblings('.add_seller_btn_box').show()
    setTimeout(() => {
    $(selector).remove();
    }, 1000);
    if (siblings >= 2) {
        $("#add_good").removeAttr('disabled')
    }
        $("#delete_confirm").modal('hide');
}
}
async function open_send_modal(row_id,tab_status) {
    var action = "get_all_users_access_inq";//گرفتن  لیست کاربران  دارای دسترسی ه
    var res = await manageAjaxRequestCustom({action: action,row_id:row_id})
    var comment_html="";
   
    var users = res.data.users;
    var goods=res.data.goods;
    var grid_html=res.data.grid_html;
    
    // $("#send_modal_box").find(".inq_send_data_grid").html("")
    // $("#send_modal_box").find(".inq_send_data_grid").html(html)
    // create_data_table("send_"+row_id)
    var options = "<option value='0'>انتخاب نمایید</option>";
    for (k in users) {
        options += `<option value="${users[k]['RowID']}">${users[k]['fname'] + " " + users[k]['lname']}</option>`
    }
    for(i in goods){
        comment_html+=`<fieldset style="border: 2px solid rgba(128,128,128,0.3);padding: 10px;border-radius: 10px;"><legend style="width: auto;font-size: 16px;padding: 10px;color: gray;"> ${goods[i]['good_name']}</legend><div class="form-group row"><label class="col-md-2">توضیحات    </label><div class="col-md-10"><textarea class="form-control" target="${goods[i]['good_id']}" ></textarea></div></div></fieldset>`
    }
    if ($('body').find("#send_modal_box").length)
    {
        $("#send_modal_box").remove();
    }
        $('body').append(`
            <div class="modal fade" id="send_modal_box" tabindex="-1" role="dialog" style="background:#80808080;" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document" style="max-width:1024px" >
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">ارجاع استعلام</h5>
                    <button type="button" class="close"  data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" style="color:red;font-size: 2rem">&times;</span>
                    </button>
                  </div>
                  <div id="send_form_body" >
                        <div class="modal-body send_body">
                            <form>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-2 col-form-label">وضعیت </label>
                                    <div class="col-sm-10">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input " type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1">
                                            <label class="form-check-label" for="inlineRadio1">تایید</label>
                                        </div>
                                        <div class="form-check form-check-inline ">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="0">
                                            <label class="form-check-label" for="inlineRadio2">عدم تایید</label>
                                        </div>
                                    
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inq_select_users" class="col-sm-2 col-form-label">گیرنده</label>
                                    <div class="col-sm-10">
                                        <select class="border rounded " data-live-search="true" data-width="100%"  name="inq_select_users" id="inq_select_users">${options}</select>
                                    </div>
                                </div>
                                <div class="inq_send_comment" >
                                    ${comment_html}
                                
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button style="" type="button"  id="send_inquiry" class="btn btn-primary">ارسال </button>
                            <button style="" type="button "  id="close_inquiry_modal" class="btn btn-secondary" >بستن</button>
                        </div>
                    </div>
                    <div class="inq_send_data_grid">
                        ${grid_html}
                    </div>
                </div>
              </div>
            `)
        
    if(tab_status==3){
      $('#send_form_body').remove();
    }
    $("#send_modal_box").modal('show');
    $("#inq_select_users").selectpicker()
    // var html=await res4(row_id)
    
    // $("#send_modal_box").find(".inq_send_data_grid").html("")
    // $("#send_modal_box").find(".inq_send_data_grid").html(html)
    // create_data_table("send_"+row_id)

    $("#send_inquiry").on('click',async function(){
        var action="send_inquiry";
        var reciever=$("#inq_select_users").val();
        var status="";
        var good_comments=[];
        // if(status==0){
        //     $(".inq_send_comment").find('textarea').each(function(){
        
        //        if($(this).val().trim()==""){
        //             custom_alerts('در صورت عدم تایید ثبت توضیحات الزامی می باشد','e',0,'')
        //             return false;
        //        }
    
        //     })
        // }
        var comment_flag=0;
        $(".inq_send_comment").find('textarea').each(function(){
            if($.trim($(this).val())==""){
                custom_alerts(' ثبت توضیحات الزامی می باشد','e',0,'')
                comment_flag++;
            }
            good_comments.push({'good_id':$(this).attr('target'),'good_comment':$(this).val()});

        })
        if(comment_flag>0){
            return false;
        }
        
        $("input[name='inlineRadioOptions']").each(function(){
            if($(this).prop('checked')){
                status=$(this).val();
            }
        })
        if(status==""){
            custom_alerts('وضعیت تایید یا عدم تایید را مشخص نمایید','e',0,'')
            return false;
        }
        if(reciever==0){
            custom_alerts('گیرنده استعلام خرید را مشخص نمایید','e',0,'')
            return false;
        }

        
       // console.log(good_comments);
        var params={action:action,reciever:reciever,status:status,inq_send_comment:JSON.stringify(good_comments),inquiry_id:row_id}
        var res=await manageAjaxRequestCustom(params)
        var input="";
        
        var selector="";
        if(res.res=='true'){
           switch (tab_status){
               case 1:
                   input = $("#export_inqs").click();
                   selector="export_inqs-section"
                   break;

               case 2:
                   input = $("#import_inqs").click();
                   selector="import_inqs-section"
                   break;

           }
            go_to_section(input,get_inquires,tab_status,selector,refresh=1)
            $("#send_modal_box").modal('hide')
            $("#import_inqs_detailes").modal('hide')
            $(".modal-backdrop").hide();
            $(".modal-backdrop").css('display','none !important');
            custom_alerts('استعلام با موفقیت ارسال شد','i',0,'');
           
        }
        else{
           
            custom_alerts(res.data,'e',0,'خطا');
            return false;
        }
    })
    $("#close_inquiry_modal").on('click',function(){
        $("#send_modal_box").modal('hide')
        $("#import_inqs_detailes").modal('hide')
        $(".modal-backdrop").hide();
        $(".modal-backdrop").css('display','none !important');
       
    })
    // $("#send_modal_box").on("hidden.bs.modal", function () {
    //
    //
    //    $(this).remove();
    // });
}

async  function refresh_inq_table(status){
    var action="refresh_inq_table"
    var params={action:action,status:status}
    var res=await manageAjaxRequestCustom(params);
}
async function get_all_inq_sends(row_id){
    var action="get_all_inq_sends";
    var params={action:action,row_id:row_id}
    var res=await manageAjaxRequestCustom(params)
    console.log('res');
    console.log(res);
    if(res.res=="true")
    {
        return res.data;
    }
}
function hide_inq_comment(input){
    var parent= $(input).parents('td').first().children('div').remove();
}

async function get_inq_comments(good_id,inquiry_id,group_code){
    var action= "display_inq_comment";
    var params={action:action,good_id:good_id,inquiry_id:inquiry_id,group_code:group_code}
    var res=await manageAjaxRequestCustom(params);
    var commands=$("#inq_comment_display_box").find(".all_comments");
    var inner_html="";
    var counter=1;
    var data=res.data;
    console.log(res)
    if(res.res=="true"){
        inner_html=`<table class="table"><tr><td>ردیف</td><td>کاربر ثبت کننده</td><td>شرح یادداشت</td><td>تاریخ ثبت</td></tr>`;
        for(k in data){
            var message="";
            if(data[k]['reason']==1){
                message=`<span style='color:red'>علت انتخاب  : </span><span> ${data[k]['message']} </span>`;
            }
            else{
                message=`<span>${data[k]['message']}</span>`;
            }
            inner_html+=`<tr><td>${counter}</td><td>${data[k]['sender']}</td><td>${message}</td><td>${data[k]['reg_date']}</td></tr>`
            counter++;
        }
        inner_html+="</table>";
    }
    else{
        inner_html=`<p style="color:red;text-align: center">${res.data}</p>`
    }

    $(commands).html("");
    $(commands).html(inner_html);
}
async function display_inq_comment(input,good_id,inquiry_id,group_code){
        if(!$('body').children("#inq_comment_display_box").length)
        {
            $('body').append(` 
            <div id="inq_comment_display_box" class="modal" tabindex="-1" role="dialog" >
            <div class="modal-dialog" style="max-width: 900px" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> پیام ثبت شده  </h5>
    
                </div>
                <div class="modal-body">
                  <form class="form-horizontal" action="" style="margin:10px;padding: 10px">
                    <div class="form-group">
                      <label class="control-label col-md-2" for="email">توضیحات:</label>
                      <div class="col-md-10">
                      <textarea id="inq_comment_box" class="form-control" cols="45" rows="5" placeholder="توضیحات"></textarea>
               
                      </div>            
                    </form>
                    <div style="border-top:2px solid #d9d5d54d;display: flex;justify-content: space-between; width:100%;padding: 10px;">
                        <button style="width: 80px" type="button"   id="save_inq_comment" class="btn btn-primary">تایید</button>
                        <button style="width: 80px" type="button"   id="confirm_to_close" class="btn btn-danger">انصراف</button>
                    </div>
                </div>
                <div class="all_comments">
                
                </div>
               
                </div>
            </div>
            </div>`);
        }
        else{
            $("#inq_comment_box").val('');
        }
        $("#confirm_to_close").on('click',function(){
            $("#inq_comment_display_box").modal('hide');
            $(input).parents('tr').removeClass('inq_comment_row_select');
        })
        $("#inq_comment_display_box").modal({backdrop: 'static', keyboard: false}, 'show');
        get_inq_comments(good_id,inquiry_id,group_code)


    $("#save_inq_comment").on('click', async function (){
        var action="save_inq_comment";
        var comment = $("#inq_comment_box").val();
        if(comment.trim()==""){
            custom_alerts('وارد کردن متن توضیحات اجباری می باشد',"e",0,'');
            return false;
        }
        var params={action:action,good_id,inquiry_id:inquiry_id,group_code:group_code,comment:comment}
        var res= await manageAjaxRequestCustom(params)
        if(res.res=="true"){
             custom_alerts(res.data,"i",0,'');
        }
        else{
                 custom_alerts(res.data,"e",0,''); 
        }
        $(input).parents('tr').removeClass('inq_comment_row_select');

    })
    $(input).parents('tr').addClass('inq_comment_row_select');
}


async function do_edit_inq_message(input,rowid){
    var action = "do_edit_inq_message";
    var message=$(input).parents(".inq_message_modal").find("textarea").val()
    var params = {action: action, rowid: rowid,message:message}
    var res = await manageAjaxRequestCustom(params)
    $(".inq_message_modal").modal('hide')
    if(res.res=='true'){
        custom_alerts(res.data,"i",0,'ذخیره موفق')
    }
    if(res.res=='false'){
        custom_alerts(res.data,"e",0,'خطا ')
    }
}
