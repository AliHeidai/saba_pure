var parent_inquiry_id=0;
var inq_fix_data=get_fixed_inq_data();
var selected_radio_inq;
var seller_temp_id;
var pay_method_array=[];
var deliver_method_array=[];
var sum_good_amount;
var sum_inq_cost;
var timeInterval=0;
//var inqury_url="php/managemantproccess";
// $(document).bind("contextmenu",function(e) {
//     e.preventDefault();
// });

// $(document).keydown(function (event) {
//     if (event.keyCode == 123) { // Prevent F12
//         return false;
//     } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
//         return false;
//     }
// });





 async function goToPurchaseInquiry(){
     await openPurchaseInquiry();
     await display_inq_counts();
   
}
       
// var m=setInterval(async function(){
//     if($("#PurchaseInquiry").is(':visible')){
//         await display_inq_counts()
//     }

// },3000)
    


async function display_inq_counts(){
    var action="display_inq_counts";
    var res= await manageAjaxRequestCustom({action:action})
    var status=res.data;
 
    for(k in status){
        var elm="";
        if(parseInt(status[k]['status_count'])>0){
            switch(status[k]['status_type']){
                case 'temp':
                    elm= "#temp_inqs_count";
                break;
                case 'sended':
                    elm= "#sended_inqs_count";
                break;
                case 'received':
                    elm= "#import_inqs_count";
                break;
                case 'archived':
                    elm= "#archive_inqs_count";
                break;
                case 'ready':
                    elm= "#export_inqs_count";
                break;
            }
            $(elm).show();
            $(elm).text(status[k]['status_count']);
        }
        else{
            $(elm).hide();
        }   
    }
}

async function openPurchaseInquiry(){
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

    await create_tab("#"+id,tab_array);
    await get_inquires(0,'temp_inqs-section');
    await get_fixed_inq_data();
    open_inq_flag=1;

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
       // get_fixed_inq_data();
        return res.data
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
    //timeInterval=clearInterval(timeInterval)
   // timeInterval=setInterval(()=>{ display_inq_counts();},3000)
    
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
      showCancelButton: false,
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

function get_inq_groups(){
    var action="get_inq_groups";
    var params={action:action}
    var res=ajaxHandler(params)
    var options=`<option value="0"> گروه استعلام را انتخاب نمایید</option>`;
    for(k in res){
        options+=`<option value="${res[k]['code']}">${res[k]['description']}</option>`
    }
    return options;

}
async function  create_inquiry(input,id=0){
    var form_title="";
    var today= $("#inq_create_date_input").val();
    var inq_group_options=get_inq_groups();
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
    var inq_title = {'type':'select',"class":"form-control", "title":"گروه استعلام", 'id':"export_inq_title",'style':'width:50%','options':inq_group_options}
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
    var inq_table_header_step2=[[" کد استعلام","<span id='inq_uncode_disable'></span>","تاریخ استعلام ","<span id=\"inq_date_disable_td\">"]] ;
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
    await get_fixed_inq_data()
    var action="edit_inquiry";
    var inq_group_options=get_inq_groups();
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
  var inq_title = {'type':'select',"class":"form-control", "title":"گروه استعلام", 'id':"export_inq_title",'style':'width:50%','options':inq_group_options}
    var inq_id_hidden = {'type':'hidden', 'id':"inq_id_hidden", 'value':id}
    if(parseInt(id)>0){
        inq_date['value']=res.data.inquiry_date;
    }
    // var inq_button_1 = {'type':'button',"class":"btn btn-primary", "title":"تایید و ادامه", 'id':"confirm_continue_one",'style':'float:left','onclick':'confirm_continue(this,\'#form_section_inq_two\')'}
    //-------------------------------------input for section 1-------------------------------------------------------------
    //-------------------------------------input for section 2------------------------------------------------------------
    var add_goods = {'type':'button',"class":"btn btn-primary inqury_btn", "title":" افزودن کالا", 'id':"add_good",'style':'width:100%;margin:auto','onclick':'add_inq_row_good(this,'+id+')'}
    // var confirm_next_step = {'type':'button',"class":"btn btn-success", "title":" تایید و ادامه", 'id':"confirm_next_step",'style':'display:none;float:left;margin-inline:3rem','onclick':'add_inq_row_good(this)'}
    var inq_table_header_step2=[[" کد استعلام","<span id='inq_uncode_disable'></span>","تاریخ استعلام "]] ;
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
    $("#export_inq_title").val( res.data.title)
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

    set_steps_form("#form_create_export_inq",3,0,[validate_form_step_one,callback2,callback3],['form_section_inq_one','form_section_inq_two','form_section_inq_three'],active_step=0);
    $('#export_inq_date').MdPersianDateTimePicker({
        targetTextSelector: '#export_inq_date',
        disableAfterDate: new Date(),
    })
   
    sessionStorage.setItem("inq_id",id)
    await add_inq_row_good($("#add_good"),id)
    $("#form_section_inq_two").children('div.footer').show();

}
function set_inq_data(input,target,form_index){
    var data_inq= $(input).val()
    $(target).text(data_inq);
    $(target).val(data_inq);
    if(data_inq==seller_temp_id){
        $("#temp_export_inq_seller_name_"+form_index).parent().show();
    }
    else{
        $("#temp_export_inq_seller_name_"+form_index).parent().hide();
    }
    
}

async function add_inq_row_good(input,inq_id=0){

    var goods_params = {action:'inq_get_all_goods'}
    var units_params = {action:'inq_get_all_units'}
    var purchase_params = {action:'inq_get_all_base_purchase'}
    var options_good = '<option value="0">انتخاب کالا</option>';
    var options_unit = '<option value="0"> انتخاب واحد کالا</option>';
    var options_purchase = '<option value="0">انتخاب مبنای خرید</option>';

    var option_array_goods = ajaxHandler(goods_params);
    for(k in option_array_goods){
        options_good += `<option value=${option_array_goods[k]['code']}>${option_array_goods[k]['title']}</option>`
    }

    var option_array_units = ajaxHandler(units_params);
    for (k in option_array_units) {
        options_unit += `<option value=${option_array_units[k]['RowID']}>${option_array_units[k]['description']}</option>`
    }

    var option_array_purchase = ajaxHandler(purchase_params);
    for (k in option_array_purchase) {
        options_purchase += `<option value=${option_array_purchase[k]['code']}>${option_array_purchase[k]['description']}</option>`
    }
    //**********************
    var count_res=0;
    var index=$("#form_section_inq_two").find(".form_create_inq_box").length;
    var res_goods="";
    if(inq_id>0) {
        var action = 'inquiry_get_good_detailes';
        var params = {action: action, inq_id: inq_id}
        var res_info =await manageAjaxRequestCustom(params);
        if(res_info['res']=='true'){
            res_goods=res_info['data'];
            count_res=res_goods.length
        }
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
                'onchange':"update_seller_good_quantity(this,'form_create_good_row_"+index+"')"

            }
            var inq_good_quantity = {
                'type': 'text',
                "class": "form-control",
                "min": "1",
                "title": "تعداد",
                'id': "inq_good_quantity_" + index,
                'style': 'width:50%',
                'onkeyup': "numberformat(this,1)",
                'onchange':"check_seller_quantity(this," + index + ")",

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
                "title": "شماره درخواست",
                'id': "inq_good_buy_request_num_" + index,
                'style': 'width:50%',

            }
             var buy_base_desc = {
                'type': 'select',
                "class": "form-control",
                "title": " مبنای خرید",
                'id': "buy_base_desc_" + index,
                'style': 'width:100%',
                'options':options_purchase

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
                "onclick": 'delete_all_form_inq(this,\'#form_create_inq_box_' + index + '\','+index+')',
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
            create_form_element("#form_create_good_row_" + index, buy_base_desc);
            create_form_element("#form_create_good_row_" + index, inq_good_desc);
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
                   // $("#add_seller_btn_" + t).remove();
                    $(this).remove();

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
                "onclick": 'delete_all_form_inq(this,\'#form_create_inq_box_' + index + '\','+index+')',
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
            'onchange':"update_seller_good_quantity(this,'form_create_good_row_"+index+"')",
           
               
        }
        var inq_good_quantity = {
            'type': 'text',
            "class": "form-control",
            "min": "1",
            "title": "تعداد",
            'id': "inq_good_quantity_" + index,
            'style': 'width:50%',
           // 'onchange': "numberformat(this,1);check_seller_quantity(this," + index + ")",
            'onkeyup': "numberformat(this,1)",
            'onchange':"check_seller_quantity(this," + index + ")",

        }
           //-------------------------------------------------
            var inq_good_buy_request_num = {
                'type': 'text',
                "class": "form-control",
                "min": "1",
                "title": "شماره درخواست",
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
                'type': 'select',
                "class": "form-control",
                "title": " مبنای خرید",
                'id': "buy_base_desc_" + index,
                'style': 'width:100%',
                'options':options_purchase
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
                    "onclick": 'delete_all_form_inq(this,\'#form_create_inq_box_' + index + '\','+index+')',
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
            create_form_element("#form_create_good_row_" + index, buy_base_desc);
            create_form_element("#form_create_good_row_" + index, inq_good_desc);
            //-----------------------------
        create_form_element("#add_seller_btn_box_"+ index, add_seller_btn);
        create_form_element("#form_create_good_row_" + index, inq_btn_del);
       // select_data_picker("#inq_good_name_" + index)
        $('<button  type="button" onclick="add_datalist(this)"  style="height: 100%" class="btn btn-info col-md-1"> ...</button>').insertAfter("#inq_good_name_" + index)
        $("#add_good").hide();
    }
    if(res_goods.res=="false"){
          $("#form_create_export_inq").find("button[step='2']").hide();
    }
    else{
        $("#form_create_export_inq").find("button[step='2']").show();
    }
    sessionStorage.removeItem('inq_id')

}

function update_seller_good_quantity(input,related_form_id){
    if($(input).val() !=0){
        $("#content_step_two").find('form[related_good_form="'+related_form_id+'"]').each(function(){
            var input_box=$(this).find('input[id^="export_good_quantity_"]')
           $(input_box).attr('placeholder',"مقدار کالا ("+$(input).find('option:selected').text()+")")
           if($(input_box).val()){
                $(input_box).siblings(".input_label").html($(input_box).attr('placeholder'));
           }
           $(this).find("table[id^='deliver_method_box_']").each(function(){
            $(this).find('td.deliver_amount').html('مقدار تحویلی ('+$(input).find('option:selected').text()+')');
           })
        })
    }
    
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
            custom_alerts('شما مجاز به تغییر مقدار کالا   نمی باشید','e','خطا')
            $(this).val(good_quntity)
            numberformat($(this),1)
        }
    })
}

function toggle_display(input,selector,e=event){
    e.preventDefault()
    if($(selector).children().find('form').is(":visible")){
        $(selector).children().find('form').hide()
        $(selector).find('button[id^="add_seller_"]').hide();
        $(input).html("<i class='fa fa-angle-double-down'></i>");
    }
    else
    {
        $(selector).children().find('form').show();
        $(selector).find('button[id^="add_seller_"]').show();
        $(input).html("<i class='fa fa-angle-double-up'></i>");
    }
}

async function validate_form_step_one(){

    var error_array=
        [
            {'selector':"#export_inq_date",'message':"تاریخ استعلام وارد نشده است"},
            {'selector':"#export_inq_title",'message':"گروه استعلام وارد نشده است"},
           
        ];
    var inq_id_hidden=parent_inquiry_id;
    parent_inquiry_id=0;
    var flag=1;
    for(k=0;k<error_array.length; k++ ){
        
        if($(error_array[k]['selector']).val().trim()=="" || $(error_array[k]['selector']).val().trim()==null ||$(error_array[k]['selector']).val()==0){
            custom_alerts(error_array[k]['message'],'e',0,'خطا');
            $(error_array[k]['selector']).css('border','2px solid red');
            flag=0;
            if(flag==0){
                return false;
            }
        }
    }
    
    var inq_title=$("#export_inq_title") .val();
    var inq_created_date=$("#export_inq_create_date") .val();
    var inq_date=$("#export_inq_date") .val();
    var action="save_inquiry";
    var param={inq_title:inq_title,inq_date:inq_date,action:action,inq_id_hidden:inq_id_hidden,inq_created_date:inq_created_date};
    var result = await manageAjaxRequestCustom(param);

    if(result.res=='false'){
        custom_alerts(result.data,'e',0,'خطا');
        return false;
    }
    else{
    $("#export_inq_header").text("ثبت استعلام بها > اطلاعات پایه")
    $("#inq_uncode_disable").text(result.data.inquiry_code);
    $("#inq_buy_code_disable_td").text(result.data.purchase_code);
    sessionStorage.setItem('inq_uncode',result.data.inquiry_id);
    seller_temp_id=result.data.seller_temp_id;
    if($("#inq_id_hidden") && $("#inq_id_hidden").val()>0 ){
        $("#export_inq_header").text("ویرایش استعلام بها > اطلاعات پایه")
        $("#form_section_inq_two").find('.footer').show();
    }
    else{
   
        $("#form_section_inq_two").find('.footer').hide();
    }
    return true;
}
}

async function callback2(){
    
    var inq_id =  sessionStorage.getItem('inq_uncode');
    $("#form_section_inq_three").find('#content_step_three').text(inq_id);
    var params = {action:'create_description_seller',inq_id:inq_id}
    var res = await manageAjaxRequestCustom(params);
  
    if(res.res=="false"){

        custom_alerts(JSON.parse(res.data),'e',0,'خطا');
        return false;
    }
    var data=JSON.parse(res.data);
    $("#form_section_inq_three").find("#content_step_three").html(data.html+"<br>")
    var json_chart_data=data.chart_json;
    var formula_detailes=data.formula_detailes;

    for(k in json_chart_data){

        $("#box_"+k).append(
            `
        <!--<div class="modal fade chart_box_modal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">-->
                <div id='chart_box_${k}'  class="chart_box" style='width: 500px;margin: 0 auto;border:2px solid transparent;display: none'>
                    <canvas style='display: block;width: 500px;height: 250px;background: #fff;box-shadow: 3px 3px 5px;border-radius: 10px;' id='chrt_${k}'></canvas>
                </div>
       <!--     </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
        </div>-->
            
            `)
        createReportChartInq(json_chart_data[k],"0","#chrt_"+k);
    }

    for(h in formula_detailes) {
        var htm="";
          htm+=   `<div id='formula_box_${h}'  class="formula_box" style='width: 100%;margin: 0 auto;border:2px solid transparent;display: none;gap: 1rem;direction:ltr;align-items: center;justify-content: center'>
              `;
    htm+=`<div>`
        $("#box_" + h).append(htm);
        $("#formula_box_"+h).draggable();
        
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
    return true;
}

async function get_inquiry_final_confirm(inq_id){
    var action = "get_inquiry_final_confirm";
    var params = {action:action,inq_id:inq_id}
    var res = await manageAjaxRequestCustom(params);
  
    return res.data;

}

//---------------------------------------------------

function manage_pay_comment_input(){
    $(document).ready(function(){
        $("input[type='radio']").click(function(){
            var exception_array=['is_related_project','calculation_method','contractPaymentType','method_create_contract_payment_type_fields','contract_payment_method','is_related_VAT']
            var is_exception = exception_array.indexOf($(this).attr('name'));
            if(is_exception==-1){
                var CheckCarcass = $("input[name='commentManagmentCheckCarcass']:checked").val();
                if (parseInt(CheckCarcass) > 0){
                    if (parseInt(CheckCarcass) == 1){  // تحویل داده شده است
                        $("#commentManagmentDeliveryDate").val('');
                        $("#commentManagmentCheckCarcassFile-div").css('display','');
                        $("#commentManagmentDeliveryDate-div").css('display','none');
                    }else if (parseInt(CheckCarcass) == 2){  // تحویل داده نشده است
                        $("#commentManagmentCheckCarcassFile").val('');
                        $("#commentManagmentCheckCarcassFile-div").css('display','none');
                        $("#commentManagmentDeliveryDate-div").css('display','');
                    }
                }else {
                    var radioValue = $("input[name='commentManagmentPayType']:checked").val();
                    var is_related_project=$("input[name='is_related_project']:checked").val()
                    $("#commentManagmentClearingFund").val('');
                    $("#commentManagmentClearingGoodLoan").val('');
                    if(is_related_project==1){
                        $("#comment_project-div").show();
                    }
                    else{
                        $("#comment_project-div").hide();
                    }
                    if(parseInt(radioValue) == 0){  // سهامی
                        $("input[name='commentManagmentCashCheck']").prop('checked',false);
                        $("#is_related_project-div").show();
                        $("#is_related_VAT-div").show();
                    // $("input[name='is_related_project']").prop('checked',false);
                       // $("#commentManagmentFor").val('');
                      //  $('#commentManagmentAccNum').children('option:not(:first)').remove();
                        $("#commentManagmentAccNum").val(-1);
                      //  $("#commentManagmentCode").val('');
                        $("#commentManagmentOneLayer").val(-1);
                        $('#commentManagmentTwoLayer').empty();
                        $('#commentManagmentThreeLayer').empty();
                        $('#commentManagmentUnit').empty();
                        $('#commentManagmentConsumerUnit').empty();
                        $('#commentManagmentType').empty();

                        $("#commentManagmentCashCheck-div").css('display','none');
                        
                        $("#commentManagmentOneLayer-div").css('display','');
                        $("#commentManagmentTwoLayer-div").css('display','none');
                        $("#commentManagmentThreeLayer-div").css('display','none');
                        $("#commentManagmentUnit-div").css('display','');
                        $("#commentManagmentConsumerUnit-div").css('display','');
                        $("#commentManagmentType-div").css('display','');
                        $("#commentManagmentCheckNumber-div").css('display','none');
                        $("#commentManagmentCheckDate-div").css('display','none');
                        $("#commentManagmentCheckCarcass-div").css('display','none');
                        $("#commentManagmentCheckCarcassFile-div").css('display','none');
                        $("#commentManagmentDeliveryDate-div").css('display','none');
                        $("#commentManagmentClearingFund-div").css('display','none');
                        $("#commentManagmentClearingGoodLoan-div").css('display','none');
                        $("#commentManagmentContractYN-div").css('display','');
                        $("#commentManagmentContractNum-div").css('display','');
                        $("#commentManagmentToward-div").css('display','');
                        $("#commentManagmentTotalAmount-div").css('display','');
                        $("#commentManagmentAmount-div").css('display','');
                        $("#commentManagmentFor-div").css('display','');
                        $("#commentManagmentCode-div").css('display','');
                        $("#commentManagmentCashSection-div").css('display','');
                        $("#commentManagmentPaymentMaturityCash-div").css('display','');
                        $("#commentManagmentNonCashSection-div").css('display','');
                        $("#commentManagmentPaymentMaturityCheck-div").css('display','');
                        $("#commentManagmentRequestSource-div").css('display','');
                        $("#commentManagmentRequestNumbers-div").css('display','');
                        $("#commentManagmentDesc-div").css('display','');
                        $("#commentManagmentCardNumber-div").show();
                        $("#commentManagmentAccNum-div").show();
                    }else 
                    {  // فورج
                        $("#is_related_project-div").hide();
                        $("#is_related_VAT-div").hide();
                        //$("input[name='is_related_project']").prop('checked',false);
                        $("#commentManagmentCashCheck-div").css('display','');
                        $("#commentManagmentOneLayer-div").css('display','none');
                        $("#commentManagmentTwoLayer-div").css('display','none');
                        $("#commentManagmentThreeLayer-div").css('display','none');
                        $("#commentManagmentUnit-div").css('display','none');
                        $("#commentManagmentConsumerUnit-div").css('display','none');
                        $("#commentManagmentType-div").css('display','none');
                        $("#commentManagmentCheckNumber-div").css('display','none');
                        $("#commentManagmentCheckDate-div").css('display','none');
                        $("#commentManagmentCheckCarcass-div").css('display','none');
                        $("#commentManagmentCheckCarcassFile-div").css('display','none');
                        $("#commentManagmentDeliveryDate-div").css('display','none');
                        $("#commentManagmentContractYN-div").css('display','none');
                        $("#commentManagmentContractNum-div").css('display','none');
                        $("#commentManagmentToward-div").css('display','none');
                        $("#commentManagmentTotalAmount-div").css('display','none');
                        $("#commentManagmentAmount-div").css('display','none');
                        $("#commentManagmentFor-div").css('display','none');
                        $("#commentManagmentAccNum-div").css('display','none');
                        $("#commentManagmentCardNumber-div").css('display','none');
                        $("#commentManagmentBillingID-div").css('display','none');
                        $("#commentManagmentPaymentID-div").css('display','none');
                        $("#commentManagmentCode-div").css('display','none');
                        $("#commentManagmentCashSection-div").css('display','none');
                        $("#commentManagmentPaymentMaturityCash-div").css('display','none');
                        $("#commentManagmentNonCashSection-div").css('display','none');
                        $("#commentManagmentPaymentMaturityCheck-div").css('display','none');
                        $("#commentManagmentRequestSource-div").css('display','none');
                        $("#commentManagmentRequestNumbers-div").css('display','none');
                        $("#commentManagmentDesc-div").css('display','none');
                        $("#commentManagmentClearingFund-div").css('display','none');
                        $("#commentManagmentClearingGoodLoan-div").css('display','none');
                        var typeValue = $("input[name='commentManagmentCashCheck']:checked").val();
                        if(parseInt(typeValue) == 0) {  // نقدی
                            $("#is_related_project-div").show();
                            $("#is_related_VAT-div").show();
                        //$("input[name='is_related_project']").prop('checked',false);
                           // $("#commentManagmentFor").val('');
                            // $('#commentManagmentAccNum').children('option:not(:first)').remove();
                            // $("#commentManagmentAccNum").val(-1);
                          //  $("#commentManagmentCode").val('');
                          //  $("#commentManagmentType").val('');
                          //  $("#commentManagmentCashSection").val('');
                           // $("#commentManagmentNonCashSection").val('');
                           // $("#commentManagmentPaymentMaturityCheck").val('');
                            $("#commentManagmentOneLayer").val(-1);
                            $('#commentManagmentTwoLayer').empty();
                            $('#commentManagmentThreeLayer').empty();
                            $('#commentManagmentUnit').empty();
                            $('#commentManagmentConsumerUnit').empty();
                            $('#commentManagmentType').empty();

                            $("#commentManagmentOneLayer-div").css('display','');
                            $("#commentManagmentTwoLayer-div").css('display','none');
                            $("#commentManagmentThreeLayer-div").css('display','none');
                            $("#commentManagmentUnit-div").css('display','');
                            $("#commentManagmentConsumerUnit-div").css('display','');
                            $("#commentManagmentType-div").css('display','');
                            $("#commentManagmentContractYN-div").css('display','');
                            $("#commentManagmentContractNum-div").css('display','');
                            $("#commentManagmentToward-div").css('display','');
                            $("#commentManagmentTotalAmount-div").css('display','');
                            $("#commentManagmentAmount-div").css('display','');
                            $("#commentManagmentFor-div").css('display','');
                            $("#commentManagmentCode-div").css('display','');
                            $("#commentManagmentCashSection-div").css('display','none');
                            $("#commentManagmentPaymentMaturityCash-div").css('display','');
                            $("#commentManagmentNonCashSection-div").css('display','none');
                            $("#commentManagmentPaymentMaturityCheck-div").css('display','none');
                            $("#commentManagmentRequestSource-div").css('display','');
                            $("#commentManagmentRequestNumbers-div").css('display','');
                            $("#commentManagmentDesc-div").css('display','');
                            $("#commentManagmentCardNumber-div").show();
    
                            $("#commentManagmentAccNum-div").show();
                        }else if (parseInt(typeValue) == 1)
                        {  // چک
                            $("#is_related_project-div").show();
                            $("#is_related_VAT-div").show();
                        // $("input[name='is_related_project']").prop('checked',false);
                           // $("#commentManagmentFor").val('');
                            $('#commentManagmentAccNum').children('option:not(:first)').remove();
                            $("#commentManagmentAccNum").val(-1);
                          //  $("#commentManagmentCode").val('');
                          //  $("#commentManagmentType").val('');
                          //  $("#commentManagmentCashSection").val('');
                          //  $("#commentManagmentPaymentMaturityCash").val('');
                            $("#commentManagmentOneLayer").val(-1);
                            $('#commentManagmentTwoLayer').empty();
                            $('#commentManagmentThreeLayer').empty();
                            $('#commentManagmentUnit').empty();
                            $('#commentManagmentConsumerUnit').empty();
                            $('#commentManagmentType').empty();

                            $("#commentManagmentOneLayer-div").css('display','');
                            $("#commentManagmentTwoLayer-div").css('display','none');
                            $("#commentManagmentThreeLayer-div").css('display','none');
                            $("#commentManagmentUnit-div").css('display','');
                            $("#commentManagmentConsumerUnit-div").css('display','');
                            $("#commentManagmentType-div").css('display','');
                            $("#commentManagmentContractYN-div").css('display','');
                            $("#commentManagmentContractNum-div").css('display','');
                            $("#commentManagmentToward-div").css('display','');
                            $("#commentManagmentTotalAmount-div").css('display','');
                            $("#commentManagmentAmount-div").css('display','');
                            $("#commentManagmentFor-div").css('display','');
                            $("#commentManagmentCode-div").css('display','none');
                            $("#commentManagmentCashSection-div").css('display','none');
                            $("#commentManagmentPaymentMaturityCash-div").css('display','none');
                            $("#commentManagmentNonCashSection-div").css('display','none');
                            $("#commentManagmentPaymentMaturityCheck-div").css('display','');
                            $("#commentManagmentRequestSource-div").css('display','');
                            $("#commentManagmentRequestNumbers-div").css('display','');
                            $("#commentManagmentDesc-div").css('display','');
                        }
                    }
                }
            }
        });
    });
 
    $('input[name="is_related_project"]').on('click',function(){
        if($(this).val()==1){
            $("#comment_project-div").show();
        }
        else{
            $("#comment_project-div").hide();
        }
       
    })
}


//-------------------------------------------------------

async function get_all_payComments(inq_array){
    var action = 'create_pay_comment_modal';
    var account_options=`<option value="-1">--------</option>`;
    var res = ajaxHandler({action:action,g_id:inq_array['group_code']});
    if($("#commentManagmentModal").length)
    {
       $("#commentManagmentModal").remove();
    }
    var modal=res[0];
    $('body').append(modal);
    $("#commentManagmentModal").find('.modal-header').removeClass('bg-secondary').addClass('bg-primary text-light');
    $("#commentManagmentModal").find('.modal-content').css('background','#c4d9c4');
    $('#commentManagmentPaymentMaturityCheck').MdPersianDateTimePicker({
        targetTextSelector: '#commentManagmentPaymentMaturityCheck',
       
    })

    $('#commentManagmentPaymentMaturityCash').MdPersianDateTimePicker({
        targetTextSelector: '#commentManagmentPaymentMaturityCash',
       
    })
    
    await createPayComment();
    await get_layer_one_options();
    await manage_pay_comment_input();
   
    var account_num_array=res[1]['accountNumber'].split(',')
    var bank_name_array=res[1]['bankName'].split(',');
    $("#commentManagmentFor").val(res[1]['account_name']);
    $("#commentManagmentCode").val(res[1]['account_tafzili_code']);
    $("#commentManagmentToward").val(res[1]['toward']);
    var pay_date=inq_array['pay_date'].replaceAll("-", "/")
    if(inq_array['pay_method']==1){//پیش پرداخت
        $("#commentManagmentPaymentMaturityCash").val(pay_date);
        $("#commentManagmentCashSection").val(inq_array['pay_amount']);
    }
    if(inq_array['pay_method']==2){// نقدی
        $("#commentManagmentPaymentMaturityCash").val(pay_date);
        $("#commentManagmentCashSection").val(inq_array['pay_amount']);
    }
    if(inq_array['pay_method']==3){// چک
        $("#commentManagmentPaymentMaturityCheck").val(pay_date);
        $("#commentManagmentNonCashSection").val(inq_array['pay_amount']);
    }
    if(inq_array['pay_method']==4){//نقدی موقع تحویل 
        $("#commentManagmentPaymentMaturityCash").val(pay_date);
        $("#commentManagmentCashSection").val(inq_array['pay_amount']);
    }
    $("#commentManagmentAmount").val(inq_array['pay_amount']);
    $("#commentManagmentToward").val(res[1]['toward']);
    for(var a =0;a< account_num_array.length;a++){
        account_options+=`<option value="${res[1]['acc_RowID']},${a}"> ${account_num_array[a]} ** ${bank_name_array[a]}</option>`
    }
    $("#commentManagmentAccNum").html(account_options);
    $("#commentManagmentTotalAmount").val(res[1]['total_amount_pay']);
    $("#group_code_comment").val(inq_array['group_code']);
    $("#base_inq").val(1);
}

async function get_layer_one_options(){
    var action="get_layer_one_options"
    var params={action:action};
    var res= await ajaxHandler(params);
    var options="<option value='0'>--------</option>";
    for(k in res){
        options+=`<option value="${res[k]['RowID']}">${res[k]['layerName']}</option>`;
    }
    $("#commentManagmentOneLayer").html(options);
}

async function show_inq_detailes(inq_id,input,status)
{
    var parent_elm="";
    var parent_section="";
    var id="";
    var inquiry_final_confirm = await get_inquiry_final_confirm(inq_id);
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
        var modal=`<div id="${id}" class="modal" tabindex="-1" role="dialog">
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
              <button type="button" id="print_inq_btn" class="btn btn-info" ><i  class="fa fa-print"> <span style="font-family: IranSans">چاپ خروجی </span>   </i></button>`;  
            if((status==2 || status==3) && inquiry_final_confirm==1){
                modal+=`<button onclick="create_inquiry_pay_comment(${inq_id})" type="button" id="create_pay_comment_btn" class="btn btn-primary" ><i  class="fa fa-edit"> <span style="font-family: IranSans">ثبت اظهارنظر  </span>   </i></button>`
            }
            modal+=`
                
                <button type="button" style="font-family: IranSans" class="btn btn-danger" data-dismiss="modal"> بستن</button>
                </div>
            </div>
            </div>
        </div>`                   
        $(parent_section).append(modal);
    }

    var params = {action:'create_description_seller',inq_id:inq_id}
    var res =  await manageAjaxRequestCustom(params);
    var data=JSON.parse(res.data);
    if(status!=1){
        $("#archive_inq_btn").remove();
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
                            <span> :مبلغ چک</span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['export_term_payment']} </span>
                         </td>
                         </tr><tr>
                         <td>
                            <span> مدت  باز پرداخت</span>
                         </td>
                         <td>
                            <span>${formula_detailes[h][m]['pay_mount']} روز   </span>
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
                     </tr>`
                     htm+=`</table></div>`
                    // <tr><td>قیمت تمام شده بر اساس محاسبه نرم افزار</td><td>${(formula_detailes[h][m]['export_term_payment']*formula_detailes[h][m]['inq_rate_percent']*formula_detailes[h][m]['pay_mount'])+parseInt(formula_detailes[h][m]['export_term_payment'])+parseInt(numberformat2(formula_detailes[h][m]['export_cash_section']))+parseInt(formula_detailes[h][m]['vat_amount'])+parseInt(formula_detailes[h][m]['rent_inside'])+parseInt(formula_detailes[h][m]['rent_outside'])}</td></tr>
                    

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
            showCancelButtonText:'انصراف',
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
            // $("body").append(res.data);
            // $(".print_inq").find()
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

async function create_inquiry_pay_comment(inq_id){
    var action="create_inquiry_pay_comment";
    var params={action:action,inq_id:inq_id};
    var res=await manageAjaxRequestCustom(params);
    if(!$("#inq_pay_comment_modal").length){
        $('body').append(`<div class="modal" id="inq_pay_comment_modal" tabindex="-1" role="dialog" style="background:rgba(128,128,128,0.7)">
        <div class="modal-dialog" role="document" style="max-width:700px">
        <div class="modal-content">
            <div class="modal-header bg-primary">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="text-danger" style="font-size:2rem" aria-hidden="true">X</span>
            </button>
            </div>
            <div class="modal-body">
            ${res['data']}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
            </div>
        </div>
        </div>
        </div>`);
    }
    else{
        $("#inq_pay_comment_modal").find('.modal-body').html(res['data']);

    }
    $("#inq_pay_comment_modal").modal({'keyboard':false,'backdrop':'static'},'show');

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
         $(chart_box).show()
     }
     else{

         $(input).html("نمایش نمودار مقایسه ای")
         $(input).css('color','blue')
         $(input).attr('title','مشاهده نمودار')
         $(chart_box).hide()
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
function get_inquray_status_info(info_group){
    var action = "get_inquray_status_info";
    var params = {action:action,info_group:info_group} 
    var result= ajaxHandler(params)
    var options="<option value='-1'> انتخاب نمایید</option>";
    for(k in result){
        options+=`<option value="${result[k]['code']}">${result[k]['description']}</option>`;
    }
    return options;
}

async function add_seller_buyer_inq(input,parent,form_good_index,mode=0){
    var good_name=$("#inq_good_name_"+form_good_index).val();
    var good_id=$("#inq_good_name_"+form_good_index+"_hidden").val();
    var good_unit=$("#inq_good_quantity_unit_"+form_good_index).val();
    var good_quantity=$("#inq_good_quantity_"+form_good_index).val();
    var mode_handler=form_good_index+"_"+mode
    var vat_percent=0;
    if(vat_percent){
        await get_fixed_inq_data();
        var vat_percent=inq_fix_data.vat_percent;
    }
    var vat_atatus_options=get_inquray_status_info('factor_group');

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
        custom_alerts('مقدار کالا  را وارد نمایید','e','خطا');
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

    
    var result = handler_seller_data(mode, good_id);
    var res_length=Object.keys(result).length
    if(res_length>0 && !$(input).attr('fetch')){//------------------------------------------------------- ویرایش   استعلام بها -----------------------------------------------------------------------
        for (obj_key in result)
        {
            var inq_object=result[obj_key];
            var parent_box_index = $(input).attr('parent_box_index');
            var form_index1 = $(parent).find('form.form-seller').length;
            form_index = form_index1 + parent_box_index;

            var form_element = {
                'type': 'form',
                "class": " from form-seller ",
                'id': "form_create_seller_row_" + form_index,
                'related_good_form':"form_create_good_row_"+form_good_index
            }
           // $("#form_create_seller_row_"+form_index).remove();
            var seller_name_hidden_value = inq_object.export_inq_seller_name ? inq_object.export_inq_seller_name : "";
            var seller_code = {
                'type': 'hidden', 'id': "export_inq_seller_id_hidden_" + form_index
            }
            var seller_name = {
            'type': 'select',
            'options': options,
            "class": "form-control",
            "placeholder": "نام فروشنده/خریدار ",
            'id': "export_inq_seller_name_" + form_index,
            'style': 'width:50%',
            'data-display': "static",
            'onchange': " set_inq_data(this,'#export_inq_seller_id_hidden_" + form_index + "','"+form_index+"')",
            'value': seller_name_hidden_value

            }
            var temp_seller_name={
                'type': 'text',
                "class": "form-control",
                "placeholder": "نام فروشنده/خریدار ",
                'id': "temp_export_inq_seller_name_" + form_index,
                'style': 'width:50%',
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
                'set_focus':"1"
                
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
                "placeholder": "مقدار کالا " + "("+$("#inq_good_quantity_unit_"+form_good_index+" option:selected" ).text()+")",
                'id': "export_good_quantity_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',
                'onblur': "check_inq_allowed_good_quantity(this," + parent_box_index + ")",
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
                'onkeyup': "numberformat(this,1)",
                'value':inq_object['export_good_quantity'],
                'set_focus':"1"

            }
            var unit_price = {
                'type': 'text',
                "class": "form-control",
                'min': '0',
                "placeholder": "قیمت براساس واحد ( ریال)",
                'id': "export_unit_price_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',
                'value': "",
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
                'onkeyup': "numberformat(this,1)",
                'value':inq_object['export_unit_price'],
                'set_focus':"1"

            }
        
            //-----------------------------------------
            var vat = {
                    'type': 'select',
                    "class": "form-control",
                    'placeholder':"مقدار ارزش افزوده( ریال )",
                    'id': "vat_status_" + form_index,
                    'style': 'width:100%;border-bottom:1px solid gray',

                    "onfocusin": "set_label(this,1)",
                    "options":vat_atatus_options,
                    'onfocus': "toggle_vat_amount(this,'"+form_index+"')",
                    

                }
                var vat_amount = {
                'type': 'text',
                "class": "form-control",
                "placeholder": "مقدار ارزش افزوده( ریال )  ",
                'id': "export_vat_amount_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray display:none',
                'value': inq_object['vat_amount'],
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
                'oninput': "numberformat(this,1)",
                'disabled':true,
                'set_focus':"1"
            }
            var group_id={
                'type': 'hidden',
                'id':"group_id_"+form_index,
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
                'set_focus':"1"

            }

            var rent_inside = {
                'type': 'text',
                "class": "form-control",
                'min': '0',
                "placeholder": " هزینه حمل و نقل داخلی(ریال)",
                'id': "export_rent_inside_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',
                "onfocusin": "set_label(this,1)",
                'onfocusout': 'set_label(this,0)',
                'onkeyup': "numberformat(this,1)",
                'value':inq_object['export_rent_inside'],
                'set_focus':"1"

            }
            var rent_outside = {
            'type': 'text',
            "class": "form-control",
            'min': '0',
            "placeholder": " هزینه حمل و نقل خارجی( ریال )",
            'id': "export_rent_outside_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onkeyup': "numberformat(this,1)",
            'value':inq_object['export_rent_outside'],
            'set_focus':"1"

            }
            var pay_method_group = {
                'type': 'fieldset',
                "class": "form-control",
                "title": " نحوه پرداخت",
                'id': "pay_method_group_" + form_index,
                'style':"background:#f4f6f9;border:2px dashed gray;border-radius:10px;height:auto;padding:0"
            }
            var deliver_method_group = {
                'type': 'fieldset',
                "class": "form-control",
                "title": " نحوه تامین ",
                'id': "deliver_method_group_" + form_index,
                'style':"background:#f4f6f9;border:2px dashed gray;border-radius:10px;height:auto;padding:0"
            }
            var inq_btn_save = {
                "type": "btn",
                "class": "btn btn-success",
                "title": "ذخیره",
                
                'id': "export_inq_btn_save_" + form_index,
                'style': "width:100% !important;margin-block:1rem",
                "onclick": 'add_seller_buyer_save(this,\'' + form_index + '\',\'' + form_good_index + '\','+ sessionStorage.getItem('inq_uncode')+')'
            };
        // var inq_btn_delete = {
        //     "type": "btn",
        //     "class": "btn btn-danger",
        //     "title": "حذف",
        //     'id': "del_seller_buyer_" + form_index,
            
        //     'style':"width:50%;display:none ",
        //     "onclick": 'delete_seller_buyer_form(this,\'#form_create_seller_row_' + form_index + '\')'
        // };
            var group_code={
                'type': 'hidden',
                'id': "group_code_hidden_" + form_index,
            }
            var line={
                "type":"line",
                'style':'border:3px solid red'
            }
            var first_section={
                "type": "div",
                'id': "first_section_" + form_index,
                'style':"width:100%;",
                'class':"form-inline grid-form_first"
            }
            var second_section={
                "type": "div",
                'id': "second_section_" + form_index,
                'style':"width:100%;",
               
            }
            var pay_method_add_btn={
            "type": "btn",
            "class": "btn",
            "style": "width:100%;background:#dee7ed;font-size:1.5rem;color:#138f50",
            "title": "+",
            'id': "pay_method_add_btn_" + form_index,
            "onclick": 'add_pay_method_elements(this,\''+form_index+'\')' 
            };
            var deliver_method_add_btn={
                "type": "btn",
                "class": "btn",
                "style": "width:100%;background:#dee7ed;font-size:1.5rem;color:#138f50",
                "title": "+",
                'id': "deliver_method_add_btn_" + form_index,
                "onclick": 'add_deliver_method_elements(this,\''+form_index+'\')' 
            };
        
            create_form_element(parent, form_element);
            create_form_element("#form_create_seller_row_" + form_index, first_section);
            create_form_element("#first_section_" + form_index, seller_code);
            create_form_element("#first_section_" + form_index, seller_name);
            create_form_element("#first_section_" + form_index, temp_seller_name);
            create_form_element("#first_section_" + form_index, good_code);
            create_form_element("#first_section_" + form_index, good_quantity);
            create_form_element("#first_section_" + form_index, unit_price);
            create_form_element("#first_section_" + form_index, vat);
            create_form_element("#first_section_" + form_index, vat_amount);
            create_form_element("#first_section_" + form_index, rent_inside);
            create_form_element("#first_section_" + form_index, rent_outside);
            create_form_element("#first_section_" + form_index, group_code);
            create_form_element("#form_create_seller_row_" + form_index, second_section);
            create_form_element("#first_section_" + form_index, inq_description);
            create_form_element("#second_section_" + form_index, pay_method_group);
            create_form_element("#pay_method_group_" + form_index,pay_method_add_btn);
            create_form_element("#second_section_" + form_index, deliver_method_group);
            create_form_element("#deliver_method_group_" + form_index,deliver_method_add_btn);
            select_data_picker("#export_inq_seller_name_" + form_index);
            
            $("#export_description_" + form_index).val(inq_object['export_description']);
            $("#export_inq_seller_name_" + form_index).val(seller_name_hidden_value)
            $("#vat_status_"+form_index).val(inq_object['is_vat']);
            $("#export_inq_seller_name_" + form_index).selectpicker('refresh')
            $(input).attr('disabled', true)
            
        
            $("#form_create_seller_row_" + form_index).append(`<div id="btn_box_${form_index}" style="100%;display:flex;justify-content:space-between;align-items:center"></div>`);
            create_form_element("#btn_box_" + form_index, inq_btn_save);
       // create_form_element("#btn_box_" + form_index, inq_btn_delete);
        //----------------------------------
            var vat=$("#vat_status_"+form_index).val();
           if(vat==0){
                $("#vat_status_"+form_index).css('color','red')
                $("#export_vat_amount_"+form_index).parent().hide();
           }
           else
           {
                $("#vat_status_"+form_index).css('color','green')
                $("#export_vat_amount_"+form_index).parent().show();
           }
           $("#temp_export_inq_seller_name_"+form_index).parent().hide();

        //----------------------------------------------------------------------------------------------------------------
            var pay_method = inq_object['pay'];
            await  add_pay_method_elements($("#pay_method_add_btn_"+form_index),form_index,pay_method);
            $("#pay_method_add_btn_"+form_index).show();
        //-------------------------------------------------------------------------------------------------------------------
            var deliver_method=inq_object['deliver'];
                add_deliver_method_elements($("#deliver_method_add_btn_"+form_index),form_index,deliver_method);
            $("#deliver_method_add_btn_"+form_index).show();
            $("#form_create_seller_row_"+form_index).children().find('input[type="text"],textarea').each(function(){
                var attr =$(this).attr('set_focus');
                if(attr){
                    set_label($(this),1);
                }
            }) 
            $("#export_inq_btn_save_"+form_index).attr('disabled',false)
            $(input).parents(".add_seller_btn_box").hide();
            if(parseInt(result.length)>0){
                $("#form_section_inq_two").find('.footer').css('display','block')
            }
            else{
                $("#del_seller_buyer_" + form_index).hide();
                $("#form_section_inq_two .footer").hide();
            }
            
            $("#del_seller_buyer_" + form_index).show();
            $("#form_create_seller_row_"+form_index).prepend(`<div style="margin-bottom:4rem"><button  type="button" class="delete_seller_btn float-left btn btn-danger" onclick="delete_seller(this,'${form_index}','${form_good_index}')">حذف فروشنده</button></div>`)
            $("#group_id_"+form_index).val(inq_object['group_code']);
        }
        $(input).attr('fetch','1')
        $(input).parents(".add_seller_btn_box").hide();
        $("#group_code_hidden_" + form_index).val(obj_key);
  

    //----------------------------------------------------------------------------- مقدار دهی  نحوه پرداخت  --------------------------------------------------

    //----------------------------------------------------------------------------- مقدار دهی  نحوه پرداخت  --------------------------------------------------
        // $("#form_create_seller_row_"+form_index).children().find('input[type="text"],textarea').each(function(){
        //     var attr =$(this).attr('set_focus');
        //     if(attr){
        //         set_label($(this),1);
        //     }
        // }) 
        // $("#export_inq_btn_save_"+form_index).attr('disabled',true)
        // $(input).parents(".add_seller_btn_box").hide();
        // if(parseInt(result.length)>0){
        //     $("#form_section_inq_two").find('.footer').css('display','block')
        // }
        // else{
        //     $("#del_seller_buyer_" + form_index).hide();
        //     $("#form_section_inq_two .footer").hide();
        // }
        
        // $("#del_seller_buyer_" + form_index).show();
    }//--------------------------------------------------------------------------------------------------ویرایش   استعلام بها ---------------------------------------------------------------------------
    else
    {
        var parent_box_index = $(input).attr('parent_box_index');
        var form_index1 = $(parent).find('form.form-seller').length;
        form_index = form_index1 + parent_box_index;
        var form_element = {
            'type': 'form',
            "class": " from form-seller ",
            'id': "form_create_seller_row_" + form_index,
            'related_good_form':"form_create_good_row_"+form_good_index
        }
        // var seller_name_hidden_value = all_records > 1 ? inq_object['export_inq_seller_name'] : ""
        var seller_code = {
            'type': 'hidden', 'id': "export_inq_seller_id_hidden_" + form_index
        }
        var group_id={
            'type': 'hidden',
            'id':"group_id_"+form_index,
        }
        var seller_name = {
            'type': 'select',
            'options': options,
            "class": "form-control",
            "placeholder": "نام فروشنده/خریدار ",
            'id': "export_inq_seller_name_" + form_index,
            'style': 'width:50%',
            'data-display': "static",
            'onchange': " set_inq_data(this,'#export_inq_seller_id_hidden_" + form_index + "','"+form_index+"')",
        }
        var temp_seller_name={
            'type': 'text',
            "class": "form-control",
            "placeholder": "نام فروشنده/خریدار ",
            'id': "temp_export_inq_seller_name_" + form_index,
            'style': 'width:100%',
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
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
            "placeholder": "مقدار کالا " +"("+ $("#inq_good_quantity_unit_"+form_good_index+" option:selected" ).text()+")",
            'id': "export_good_quantity_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            'onblur': "check_inq_allowed_good_quantity(this," + parent_box_index + ")",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onkeyup': "numberformat(this,1)"

        }
        var unit_price = {
            'type': 'text',
            "class": "form-control",
            'min': '0',
            "placeholder": "قیمت براساس واحد ( ریال)",
            'id': "export_unit_price_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            'value': "",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onkeyup': "numberformat(this,1)"

        }
        //**-------------
        var prepayment={
            'type': 'text',
            "class": "form-control",
            'min': '0',
            "placeholder": "مقدار پیش پرداخت",
            'id': "export_prepayment" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            //'value': inq_object['export_prepayment'],
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onchange': "numberformat(this,1)"
        }
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
                'placeholder':"مقدار ارزش افزوده( ریال )",
                'id': "vat_status_" + form_index,
                'style': 'width:100%;border-bottom:1px solid gray',

                "onfocusin": "set_label(this,1)",
                "options":vat_atatus_options,
                'onfocus': "toggle_vat_amount(this,'"+form_index+"')"

            }
            var vat_amount = {
            'type': 'text',
            "class": "form-control",
            "placeholder": "مبلغ  ارزش افزوده( ریال )  ",
            'id': "export_vat_amount_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray display:none',
            'value': "",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'oninput': "numberformat(this,1)",
            'disabled':true
        }
        var inq_good_deliver_time = {
            'type': 'text',
            "class": "form-control",
            'min': '0',
            "placeholder": " تاریخ تقریبی تحویل کالا",
            'id': "inq_good_deliver_time_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            'value': "",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',

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
            'set_focus':"1"

        }
        //*******----------------------
        var pay_method = {
            'type': 'number',
            "class": "form-control",
            "placeholder": "مدت باز پرداخت براساس روز ",
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
            "placeholder": " هزینه حمل و نقل داخلی(ریال)",
            'id': "export_rent_inside_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            'value': "",
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onkeyup': "numberformat(this,1)"

        }
        var rent_outside = {
            'type': 'text',
            "class": "form-control",
            'min': '0',
            "placeholder": " هزینه حمل و نقل خارجی( ریال )",
            'id': "export_rent_outside_" + form_index,
            'style': 'width:100%;border-bottom:1px solid gray',
            "onfocusin": "set_label(this,1)",
            'onfocusout': 'set_label(this,0)',
            'onkeyup': "numberformat(this,1)"

        }
        var pay_method_group = {
            'type': 'fieldset',
            "class": "form-control",
            "title": " نحوه پرداخت",
            'id': "pay_method_group_" + form_index,
            'style':"background:#f4f6f9;border:2px dashed gray;border-radius:10px;height:auto"
        }
        var deliver_method_group = {
            'type': 'fieldset',
            "class": "form-control",
            "title": " نحوه تامین ",
            'id': "deliver_method_group_" + form_index,
            'style':"background:#f4f6f9;border:2px dashed gray;border-radius:10px;height:auto"
        }
        var inq_btn_save = {
            "type": "btn",
            "class": "btn btn-success",
            "title": "ذخیره",
            'id': "export_inq_btn_save_" + form_index,
            'style': "width:100% !important;margin-block:1rem",
            "onclick": 'add_seller_buyer_save(this,\'' + form_index + '\',\'' + form_good_index + '\','+ sessionStorage.getItem('inq_uncode')+')'
        };
        // var inq_btn_delete = {
        //     "type": "btn",
        //     "class": "btn btn-danger",
        //     "title": "حذف",
        //     'id': "del_seller_buyer_" + form_index,
        //     'style':"width:100%;display:none ",
        //     "onclick": 'delete_seller_buyer_form(this,\'#form_create_seller_row_' + form_index + '\')'
        // };
        var group_code={
            'type': 'hidden',
            'id': "group_code_hidden_" + form_index,
        }
        var line={
            "type":"line",
            'style':'border:3px solid red'
        }
        var first_section={
            "type": "div",
            'id': "first_section_" + form_index,
            'style':"width:100%;",
            'class':"form-inline grid-form_first"
        }
        var second_section={
            "type": "div",
            'id': "second_section_" + form_index,
            'style':"width:100%;",
        }
        var pay_method_add_btn={
            "type": "btn",
            "class": "btn",
            "style": "width:100%;background:#dee7ed;font-size:1.5rem;color:#138f50",
            "title": "+",
            'id': "pay_method_add_btn_" + form_index,
            "onclick": 'add_pay_method_elements(this,\''+form_index+'\')' 
        };
        var deliver_method_add_btn={
            "type": "btn",
            "class": "btn",
            "style": "width:100%;background:#dee7ed;font-size:1.5rem;color:#138f50",
            "title": "+",
            'id': "deliver_method_add_btn_" + form_index,
            "onclick": 'add_deliver_method_elements(this,\''+form_index+'\')' 
        };
       
        create_form_element(parent, form_element);
        create_form_element("#form_create_seller_row_" + form_index, first_section);
        create_form_element("#first_section_" + form_index, seller_code);
        create_form_element("#first_section_" + form_index, seller_name);
        create_form_element("#first_section_" + form_index, temp_seller_name);
        create_form_element("#first_section_" + form_index, good_code);
        create_form_element("#first_section_" + form_index, good_quantity);
        create_form_element("#first_section_" + form_index, unit_price);
        create_form_element("#first_section_" + form_index, vat);
        create_form_element("#first_section_" + form_index, vat_amount);
        create_form_element("#first_section_" + form_index, rent_inside);
        create_form_element("#first_section_" + form_index, rent_outside);
      //  create_form_element("#first_section_" + form_index,  group_code);
        create_form_element("#form_create_seller_row_" + form_index, second_section);
        create_form_element("#first_section_" + form_index, inq_description);
        create_form_element("#second_section_" + form_index, pay_method_group);
        create_form_element("#pay_method_group_" + form_index,pay_method_add_btn);
        create_form_element("#second_section_" + form_index, deliver_method_group);
        create_form_element("#deliver_method_group_" + form_index,deliver_method_add_btn);
        select_data_picker("#export_inq_seller_name_" + form_index)
        $(input).attr('disabled', true)
        set_label($("#export_vat_amount_"+form_index.toString()),1)
       
        $("#form_create_seller_row_" + form_index).append(`<div id="btn_box_${form_index}" style="100%;display:flex;justify-content:center;align-items:center"></div>`);
        create_form_element("#btn_box_" + form_index, inq_btn_save);
        //----------------------------------
        var vat=$("#vat_status_"+form_index).val();
        if(vat==1){
            
            $("#vat_status_"+form_index).css('color','green')
            $("#export_vat_amount_"+form_index).parent().show();
        }
        else
        {
            $("#vat_status_"+form_index).css('color','red')
            $("#export_vat_amount_"+form_index).parent().hide();
            $("#export_vat_amount_"+form_index).val(0);
        }
        
        $("#temp_export_inq_seller_name_"+form_index).parent().hide();
        $("#form_create_seller_row_"+form_index).prepend(`<div style="margin-bottom:4rem"><button type="button" class="delete_seller_btn float-left btn btn-danger" onclick="delete_seller(this,'${form_index}','${form_good_index}')">حذف فروشنده</button></div>`)

    }
    $(input).attr('fetch','1')
    $(input).parents(".add_seller_btn_box").hide();
    if(!result.length){
        $("#del_seller_buyer_" + form_index).parent().hide();
    }
    $("#del_seller_buyer_" + form_index).parent().show();
}

async function add_pay_method_elements(input,form_index,array_info=[]){
    var parent=$(input).parents('fieldset');  
    var btn_id=$(input).attr('id');
    console.log('array_info');
    console.log(array_info);
   var inner_form_length =$(parent).find('.pay_method_box').length;
   // var inner_form_length = $(parent).children('table tr').find(".pay_method_box").length
    var export_good_quantity=numberformat2($("#export_good_quantity_"+form_index).val());
    var export_unit_price=numberformat2($("#export_unit_price_"+form_index).val());
    var vat_status=$("#vat_status_"+form_index).val();
    var export_vat_amount=$("#export_vat_amount_"+form_index).val()?numberformat2($("#export_vat_amount_"+form_index).val()):0;
    var remind_payment=0;
    var sum_registred_pays=0;
    var  pay_cash=0;
    inner_form_index = form_index+inner_form_length;
    
    if(inner_form_length>0){
       
        $(parent).children('table').find("tr.pay_method_box").each(function(){
            pay_cash=numberformat2($(this).children().find('input[id^="pay_cash_section_"]').val());
            sum_registred_pays=parseFloat(sum_registred_pays)+parseFloat(pay_cash)
        })

    }
    if(export_good_quantity.trim()=="undefined" ||export_good_quantity.trim()=="" ||export_good_quantity.trim()==0){
        custom_alerts('مقدار کالا را وارد نمایید','e',0,"خطا");
        return false;
    }
    if(export_unit_price.trim()=="undefined" ||export_unit_price.trim()=="" ||export_unit_price.trim()==0){
        custom_alerts('قیمت بر اساس واحد  را وارد نمایید','e',0,"خطا");
        return false;
    }
     remind_payment= await parseFloat(parseFloat(numberformat2(export_good_quantity))*parseFloat(numberformat2(export_unit_price)))+parseFloat(numberformat2(export_vat_amount))-parseFloat(numberformat2(sum_registred_pays))
    if(remind_payment<=0){
        custom_alerts('کل مبلغ وارد شده و شما مجاز به ایجاد ردیف پرداخت جدید نمی باشید','e',0,"خطا");
        return false;
    }
    if(!$("#pay_method_box_"+form_index).length){
        $(parent).append(`
                    <table border="1" id="pay_method_box_${form_index}" class="table table-striped table-borderd mt-1">
                        <thead>
                            <tr>
                                <td class="tbl_header">ردیف</td>
                                <td class="tbl_header">روش پرداخت</td>
                                <td class="tbl_header">نوع پرداخت</td>
                                <td class="tbl_header">مبلغ(ریال)</td>
                                <td class="tbl_header">سررسید پرداخت</td>
                                <td class="tbl_header">توضیحات</td>
                                <td class="tbl_header">مدیریت</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>`);
    }
    
    var table=$("#pay_method_box_"+form_index + " tbody")
   
    if(array_info.length>0){
        for(var k=0; k< array_info.length;k++){
            inner_form_index = form_index+k;
           var pay_row=`<tr id="pay_method_box_${inner_form_index}" class="pay_method_box">
                        <td style="padding-block-end:5px;text-align:center" class="row_number">${parseInt(k)+1}</td>
                        <td style="padding:0">
                            <select class="form-control" style="width:100%" id="pay_method_select_${inner_form_index}" >
                                <option value="0">انتخاب کنید</option>
                                <option value="1">نقدی</option>
                                <option value="2">چک</option>
                            </select>
                        </td>
                        <td style="padding:0">
                            <select class="form-control" style="width:100%" id="pay_type_select_${inner_form_index}" >
                                <option value="0">انتخاب کنید</option>
                                <option value="1">پیش پرداخت</option>
                                <option value="2">تسویه حساب </option>
                               
                            </select>
                        </td>
                        <td style="padding:0">
                            <input  id="pay_cash_section_${inner_form_index}" value="${remind_payment}" placeholder="مبلغ" type="text" class="form-control pay_cash_section" onkeyup="numberformat(this,1)" style="max-width:100%;background:#fff">
                        </td>
                        <td style="padding:0" >
                            <input  id="pay_cheque_date_${inner_form_index}" placeholder="تاریخ پرداخت" type="text" class="pay_method_cheque form-control" style="max-width:100%;background:#fff;">
                        </td>
                        <td style="padding:0" >
                            <textarea id="pay_method_description_${inner_form_index}" placeholder="توضیحات"  class="pay_method_description form-control" style="height:34px;max-width:;background:#fff;"></textarea>
                        </td>
                        <td style="display:flex;justify-content:space-between;align-items:center;padding:0">
                            <button id="pay_save_btn_${inner_form_index}"  style="width:30px;text-align:center"class="btn btn-success" onclick="confirm_add_pay_method(this,'${btn_id}','${inner_form_index}','${form_index}')" type="button"><i class="fa fa-check"></i></button>
                            <button id="pay_delete_btn_${inner_form_index}"  style="width:30px;text-align:center"class="btn btn-danger" onclick="delete_pay_method_row(this,'${btn_id}','${inner_form_index}',${array_info[k]['RowID']})" type="button" ><i class="fa fa-trash"></i></button>
                        </td>

                </tr>`;
               $(table).append(pay_row)
                $("#pay_cheque_date_"+inner_form_index).MdPersianDateTimePicker({
                    targetTextSelector: "#pay_cheque_date_"+inner_form_index
                })
                
                var pay_method = parseInt(array_info[k].pay_method);
                var pay_type = parseInt(array_info[k].pay_type);
                var pay_date = array_info[k].pay_date;
                var pay_amount = array_info[k].pay_amount;
                var pay_description = array_info[k].description;
                $('#pay_method_select_'+inner_form_index).val(pay_method).attr('disabled',true);
                $('#pay_type_select_'+inner_form_index).val(pay_type).attr('disabled',true);
                $('#pay_cash_section_'+inner_form_index).val(pay_amount).attr('disabled',true);
                $('#pay_cheque_date_'+inner_form_index).val(pay_date).attr('disabled',true);
                $('#pay_method_description_'+inner_form_index).val(pay_description).attr('disabled',true);
                $("#pay_save_btn_"+inner_form_index).attr('disabled',true);
                numberformat2($("#pay_cash_section_"+inner_form_index),1); 
                // var array_handler_index = "row_"+inner_form_index
                // var handler_array = {pay_method:pay_method,pay_type:pay_type,pay_cheque_date:pay_date,pay_cash_section:pay_amount,description:''};
                // pay_method_array[array_handler_index] = handler_array;
                //-------------------------------------------------------------
        }
        
    }
    else{


    $(table).append(`<tr id="pay_method_box_${inner_form_index}" class="pay_method_box">
                        <td style="padding-block-end:5px;text-align:center" class="row_number">1</td>
                        <td style="padding:0">
                            <select class="form-control" style="width:100%" id="pay_method_select_${inner_form_index}" >
                                <option value="0">انتخاب کنید</option>
                                <option value="1">نقدی</option>
                                <option value="2">چک</option>
                            </select>
                        </td>
                        <td style="padding:0">
                            <select class="form-control" style="width:100%" id="pay_type_select_${inner_form_index}" >
                                <option value="0">انتخاب کنید</option>
                                <option value="1">پیش پرداخت</option>
                                <option value="2">تسویه حساب </option>
                                <!--<option value="3">علی الحساب</option>-->
                            </select>
                        </td>
                        <td style="padding:0">
                            <input  id="pay_cash_section_${inner_form_index}" value="${remind_payment}" placeholder="مبلغ" type="text" class="form-control pay_cash_section" onkeyup="numberformat(this,1)" style="max-width:100%;background:#fff">
                        </td>
                        <td style="padding:0" >
                            <input  id="pay_cheque_date_${inner_form_index}" placeholder="تاریخ پرداخت" type="text" class="pay_method_cheque form-control" style="max-width:100%;background:#fff;">
                        </td>
                        <td style="padding:0" >
                            <textarea id="pay_method_description_${inner_form_index}" placeholder="توضیحات" class="pay_method_description form-control" style="max-width:100%;background:#fff;height:34px"></textarea>
                        </td>
                        <td style="display:flex;justify-content:space-between;align-items:center;padding:0">
                            <button id="pay_save_btn_${inner_form_index}"  style="width:30px;text-align:center"class="btn btn-success" onclick="confirm_add_pay_method(this,'${btn_id}','${inner_form_index}','${form_index}')" type="button"><i class="fa fa-check"></i></button>
                            <button id="pay_delete_btn_${inner_form_index}"  style="width:30px;text-align:center"class="btn btn-danger" onclick="delete_pay_method_row(this,'${btn_id}','${inner_form_index}')" type="button" ><i class="fa fa-trash"></i></button>
                        </td>

                </tr>`);
                $(input).hide();
                $("#pay_cheque_date_"+inner_form_index).MdPersianDateTimePicker({
                    targetTextSelector: "#pay_cheque_date_"+inner_form_index
                })
            }
}

function set_table_row_number(table){
    var count_tr=0;
    $(table).children().find('tr').each(function(){
    $(this).find('.row_number').text(count_tr)
        count_tr ++
    })
    
}
function delete_pay_method_row(input,btn_id,inner_form_index,row_id=0){
    var table_obj=$(input).parents('table');
    var tr_length=$(table_obj).children().find('tr').length
    var delete_flag=0;
    if(row_id==0){
        delete_flag=1;
    }
    if(delete_flag==1){
        Swal.fire({
            title: "حذف ردیف",
            text: "آیا از حذف ردیف مطمئن می باشید؟",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "بله حذف شود !!",
            cancelButtonText: "انصراف"
          }).then((result) => {
            if (result.isConfirmed) {
                if(tr_length==2){
                    $(table_obj).remove()
                }
                if(tr_length>2){
                    $("#pay_method_box_"+inner_form_index.toString()) .remove();
                }
                delete pay_method_array['row_'+inner_form_index.toString()];
                if($("#"+btn_id).is(":hidden")){
                    $("#"+btn_id).show();
                }
                set_table_row_number(table_obj)
            }
          });
    }

}

function add_deliver_method_elements(input,form_index,deliver_array=[]){
    
    var parent = $(input).parents('fieldset');  
    var btn_id = $(input).attr('id');
    var inner_form_index = 0;
    var total_registred_deliver = 0;
    var remin_deliver = 0;
    var inner_form_length = $(parent).children('table').find(".deliver_method_box").length?$(parent).children('table').find(".deliver_method_box").length:0;
    var export_good_quantity = numberformat2($("#export_good_quantity_"+form_index).val());
    inner_form_index = form_index + inner_form_length;
    if(export_good_quantity == 0){
        custom_alerts(' مقدار کالای مورد درخواست وارد نشده است','e',0,'');
        return false;
    }
    $(parent).children('table').find("input.deliver_part_amount").each(function(){
        total_registred_deliver = parseFloat(total_registred_deliver)+parseFloat(numberformat2($(this).val()))
    })
    
    remin_deliver = parseFloat(export_good_quantity)-parseFloat(total_registred_deliver);
    if(remin_deliver==0){
        custom_alerts(' ثبت ردیف های نحوه تحویل کالا برای این کالا تکمیل شده است','e',0,'هشدار');
        return false;
    }
    remin_deliver=numberformat2(remin_deliver,1)
    var good_form_id=$(input).parents("#form_create_seller_row_"+form_index).attr('related_good_form')
    var unit_text=$("#"+good_form_id).find("select[id^='inq_good_quantity_unit_']").find('option:selected').text();
    if(!$("#deliver_method_box_"+form_index).length){
        $(parent).append(`
                            <table border="1" id="deliver_method_box_${form_index}" class="table table-borderd table-striped table-sm mt-1">
                                <thead>
                                    <tr>
                                        <td class="tbl_header">ردیف</td>
                                        <td class="tbl_header"> نحوه تحویل</td>
                                        <td class="deliver_amount tbl_header">مقدار تحویلی(${unit_text})</td>
                                        <td class="tbl_header">تاریخ تحویل</td>
                                        <td class="tbl_header">توضیحات</td>
                                        <td class="tbl_header">مدیریت</td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                               
                            </table>`
                        );
    }
    if(deliver_array.length>0){
       // $('#deliver_method_box_'+form_index).append(
     //  alert(deliver_array.length);
       for(var k=0;k<deliver_array.length;k++){
        var inner_form_index=form_index+k;
            var deliver_row=`<tr  id="deliver_method_box_${inner_form_index}" class="deliver_method_box" >
                    <td class="row_number" style="padding-block-end:0">${parseInt(k)+1}</td>
                    <td style="padding:0">
                        <select class="form-control" style="width:100%" id="deliver_method_select_${inner_form_index}">
                            <option value="0">انتخاب کنید</option>
                            <option value="1">تحویل کالا به صورت یکجا</option>
                            <option value="2">تحویل بخشی از کالا </option>
                        </select>
                    </td>
                    <td style="padding:0">
                        <input   id="deliver_part_amount_${inner_form_index}" value="${remin_deliver}"  onkeyup= "numberformat(this,1)" placeholder="مقدار تحویلی" type="text" class="form-control deliver_part_amount" style="max-width:100%;background:#fff">
                    </td>
                    <td style="padding:0">
                        <input id="deliver_date_${inner_form_index}" placeholder="تاریخ تحویل" type="text" class="deliver_date form-control" style="max-width:100%">
                    </td>
                    <td style="padding:0" >
                        <textarea id="deliver_method_description_${inner_form_index}" placeholder="توضیحات" class="deliver_method_description form-control" style="max-width:100%;background:#fff;height:34px"></textarea>
                    </td>
                      <td style="display:flex;justify-content:space-between;align-items:center;padding:0">
                        <button id="deliver_save_btn_${inner_form_index}"  style="width:30px;text-align:center"class="btn btn-success" onclick="confirm_add_deliver_method(this,'${btn_id}','${inner_form_index}','${form_index}')" type="button"><i class="fa fa-check"></i></button>
                        <button id="delete_btn_${inner_form_index}" style="width:30px;text-align:center"class="btn btn-danger" onclick="delete_deliver_method(this,'${btn_id}','${inner_form_index}',${deliver_array[k]['RowID']})" type="button" ><i class="fa fa-trash"></i></button>
                    </td>

                </tr>`;
                $('#deliver_method_box_'+form_index +" tbody").append(deliver_row);
                  $("#deliver_method_select_"+inner_form_index).val(deliver_array[k].deliver_method).attr('disabled',true);
                  $("#deliver_part_amount_"+inner_form_index).val(deliver_array[k].deliver_amount).attr('disabled',true);
                  $("#deliver_date_"+inner_form_index).val(deliver_array[k].deliver_date).attr('disabled',true);
                  $("#deliver_method_description_"+inner_form_index).val(deliver_array[k].description).attr('disabled',true);
                  $("#deliver_save_btn_"+inner_form_index).attr('disabled',true);
                
                    set_table_row_number($("#deliver_method_box_"+form_index))
                    numberformat($("#deliver_part_amount_"+inner_form_index),1)
                    var handler_array = {deliver_method:deliver_array[k].deliver_method,deliver_date:deliver_array[k].deliver_date,deliver_part_amount:deliver_array[k].deliver_amount,description:''};
                    deliver_method_array['row_'-inner_form_index] = handler_array;
                }
            }
            else{
    $('#deliver_method_box_'+form_index +" tbody").append(`<tr  id="deliver_method_box_${inner_form_index}" class="deliver_method_box" >
                    <td class="row_number" style="padding-block-end:0">1</td>
                    <td style="padding:0">
                        <select class="form-control" style="width:100%" id="deliver_method_select_${inner_form_index}">
                            <option value="0">انتخاب کنید</option>
                            <option value="1">تحویل کالا به صورت یکجا</option>
                            <option value="2">تحویل بخشی از کالا </option>
                        </select>
                    </td>
                    <td style="padding:0">
                        <input   id="deliver_part_amount_${inner_form_index}" value="${remin_deliver}"  onkeyup= "numberformat(this,1)" placeholder="مقدار تحویلی" type="text" class="form-control deliver_part_amount" style="max-width:100%;background:#fff">
                    </td>
                    <td style="padding:0">
                        <input id="deliver_date_${inner_form_index}" placeholder="تاریخ تحویل" type="text" class="deliver_date form-control" style="max-width:100%">
                    </td>
                    <td style="padding:0" >
                        <textarea id="deliver_method_description_${inner_form_index}" placeholder="توضیحات" class="deliver_method_description form-control" style="max-width:100%;background:#fff;"></textarea>
                    </td>
                      <td style="display:flex;justify-content:space-between;align-items:center;padding:0">
                        <button id="deliver_save_btn_${inner_form_index}"  style="width:30px;text-align:center"class="btn btn-success" onclick="confirm_add_deliver_method(this,'${btn_id}','${inner_form_index}','${form_index}')" type="button"><i class="fa fa-check"></i></button>
                        <button id="delete_btn_${inner_form_index}" style="width:30px;text-align:center"class="btn btn-danger" onclick="delete_deliver_method(this,'${btn_id}','${inner_form_index}')" type="button" ><i class="fa fa-trash"></i></button>
                    </td>

                </tr>`);
                $(input).hide();
                $("#deliver_date_"+inner_form_index).MdPersianDateTimePicker({
                    targetTextSelector: "#deliver_date_"+inner_form_index
                   
                })
            }
                
}

function confirm_add_deliver_method(input,btn_id,inner_form_index,form_index){
    var deliver_method = $("#deliver_method_select_"+inner_form_index).val();
    var description = $("#deliver_method_select_"+inner_form_index+" option:selected").text();
    var deliver_date =  $("#deliver_date_"+inner_form_index).val();
    var deliver_part_amount = parseFloat(numberformat2($("#deliver_part_amount_"+inner_form_index).val()));
    var export_good_quantity = parseFloat(numberformat2($("#export_good_quantity_"+form_index).val()))
    var row_number = $(input).parents('table').find('tr').length;
    var total_registered=0;
    $(input).parents().first('table').children('tr').find('input.deliver_part_amount').each(function(){
        total_registered=parseFloat(total_registered)+parseFloat(numberformat2($(this).val()));
    });
    var remin=export_good_quantity-total_registered;
   
    if(deliver_part_amount>remin){
        custom_alerts('مجموع مقادیر تحویلی از مقدار درخواست کالا بیشتر است','e',0,'');
        return false; 
    }
    if(deliver_method == 0)
    {
        custom_alerts(' نحوه تامین  را انتخاب نمایید','e',0,'');
        return false;
    }

    if(deliver_part_amount==0 || typeof(deliver_part_amount)=='undefined')
    {
        custom_alerts(' مقدار تحویل کالا را وارد نمایید','e',0,'');
        return false;
    }

   if(deliver_date==0 || typeof(deliver_date)=='undefined')
   {
        custom_alerts(' تاریخ تحویل را وارد نمایید','e',0,'');
        return false;
    }

    if(deliver_method==1)
    {
       
        if(deliver_part_amount!=export_good_quantity){
            custom_alerts('در صورت انتخاب گزینه تحویل به صورت یکجا مقدار وارد شده در ردیف نحوه تحویل حتما باید با مقدار کل کالای درخواستی برابر باشد','e',0,'خطا');
            return false;
       }
    }
    else{
        if(deliver_part_amount==export_good_quantity){
            custom_alerts('مقدار کالای درخواستی با مقدار کل کالا برابر است  در صورت  تایید مقدار  تحویلی گزینه تحویل یکباره را انتخاب نمایید در غیر این صورت  مقدار درخواستی باید از مقدار کل کالا کمتر باشد','e',0,'هشدار');
            return false;
        }
    }
    if(row_number==2)
    {
        flag= Swal.fire(
        {
            title: "ادامه می دهید؟",
            html: "<p><p>در صورت ایجاد ردیف تحویل قادر به  تغییر  فیلد </p> <span style='color:red'>  مقدار کالا </span>  نخواهید بود</p>",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "تایید وادامه",
            cancelButtonText: "انصراف "
        }).then(async(result) => 
        {
            if (result.isConfirmed) {
                do_confirm_add_deliver_method(input,btn_id,inner_form_index,form_index,deliver_method,deliver_date,deliver_part_amount,description);
               
            }
        });
    }
    else
    {
        do_confirm_add_deliver_method(input,btn_id,inner_form_index,form_index,deliver_method,deliver_date,deliver_part_amount,description);
    }
}

function do_confirm_add_deliver_method(input,btn_id,inner_form_index,form_index,deliver_method,deliver_date,deliver_part_amount,description){
    $("#"+btn_id).show();
    $(input).attr('disabled','true');
    var inner_index_string=inner_form_index.toString();
    var array_handler_index = "row_"+inner_index_string
    var export_good_quantity_flag= $("#export_good_quantity_"+form_index).is(":disabled")?1:0;
    if(export_good_quantity_flag==0){
        $("#export_good_quantity_"+form_index).attr('disabled',true);
    }

    $(input).parents('fieldset').children('table').find('input,select').attr('disabled',true);
    var handler_array = {deliver_method:deliver_method,deliver_date:deliver_date,deliver_part_amount:deliver_part_amount,description:description};
    deliver_method_array[array_handler_index] = handler_array;
    
    
}

async function confirm_add_pay_method(input,btn_element,inner_form_index,form_index){
    var tr_length = $(input).parents('table').children().find('tr').length;
    var pay_method = $("#pay_method_select_"+inner_form_index).val();
    var pay_type = $("#pay_type_select_"+inner_form_index).val();
    var pay_cheque_date = $("#pay_cheque_date_"+inner_form_index).val();
    var pay_cash_section = numberformat2($("#pay_cash_section_"+inner_form_index).val());
    var description= $("#pay_method_select_"+inner_form_index+" option:selected").text()
    var sum_registerd_pay_row = 0;
    var vat_status=$("#vat_status_"+form_index).val();
    var good_price_whitout_vat=parseFloat(numberformat2($("#export_good_quantity_"+form_index).val()))*parseFloat(numberformat2($("#export_unit_price_"+form_index).val()));
    var inq_total_cost=good_price_whitout_vat
    if(vat_status==1){
        inq_fix_data.length?inq_fix_data:get_fixed_inq_data();
        var vat_value=inq_fix_data.vat_percent*good_price_whitout_vat/100;
        inq_total_cost=good_price_whitout_vat+vat_value;
    }
    $(input).parents('table').children().find('input.pay_cash_section').each(function(){
        sum_registerd_pay_row=parseFloat(sum_registerd_pay_row)+parseFloat($(this).val())
    })
   
    if(parseFloat(sum_registerd_pay_row)>parseFloat(inq_total_cost)){
        custom_alerts('مبلغ وارد شده از کل مبلغ خالص استعلام  بیشتر است','e',0,'');
        return false;
    }
    if(pay_method == 0){
        custom_alerts('روش پرداخت را انتخاب نمایید','e',0,'');
        return false;
    }

    if(pay_type== 0){
        custom_alerts('نوع پرداخت را انتخاب نمایید','e',0,'');
        return false;
    }

    if(pay_cash_section == 0 || typeof(pay_cash_section)=='undefined')
    {
        custom_alerts(' مبلغ پرداخت را وارد نمایید','e',0,'');
        return false;
    }

    if(pay_cheque_date == "" || typeof(pay_cheque_date)=="undefined"){
        custom_alerts('تاریخ/ سررسید پرداخت را وارد نمایید','e',0,'');
        return false;
    }

    if(tr_length==2)
    {
        flag= await Swal.fire(
        {
            title: "ادامه می دهید؟",
            html: "<p><p>در صورت ایجاد ردیف پرداخت قادر به  تغییر  فیلد های </p> <span style='color:red'>  مقدار کالا </span> و <span style='color:red'>  قیمت کالا</span> و <span style='color:red'> وضعیت ارزش افزوده  </span>  نخواهید بود</p>",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "تایید وادامه",
            cancelButtonText: "انصراف "
            }).then(async(result) => {
            if (result.isConfirmed) {
                do_confirm_add_pay_method(pay_method,pay_type,pay_cheque_date,pay_cash_section,description,form_index,inner_form_index,btn_element,input)
            }
            
        });
    }
    else{
        do_confirm_add_pay_method(pay_method,pay_type,pay_cheque_date,pay_cash_section,description,form_index,inner_form_index,btn_element,input)
    }
}

function do_confirm_add_pay_method(pay_method,pay_type,pay_cheque_date,pay_cash_section,description,form_index,inner_form_index,btn_element,input)
{
    var inner_index_string = inner_form_index.toString()
    var array_handler_index = "row_"+inner_index_string
    var handler_array = {pay_method:pay_method,pay_type:pay_type,pay_cheque_date:pay_cheque_date,pay_cash_section:pay_cash_section,description:description};
    pay_method_array[array_handler_index] = handler_array;
    $("#"+btn_element).show();
    $(input).attr('disabled','true');
    $("#export_good_quantity_"+form_index).attr('disabled',true)
    $("#export_unit_price_"+form_index).attr('disabled',true)
    $("#vat_status_"+form_index).attr('disabled',true);
    $(input).parents('tr').find('input,select').attr('disabled',true);
}

function delete_deliver_method(input,btn_id,inner_form_index,row_id=0){
    var table_obj=$(input).parents('table');
    var tr_length=$(table_obj).children().find('tr').length
    var delete_flag=0;
    if(row_id==0){
        delete_flag=1;
    }
    if(delete_flag==1){
        Swal.fire({
                title: "حذف ردیف",
                text: "آیا از حذف ردیف تحویل کالا مطمئن می باشید؟",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "بله حذف شود !!",
                cancelButtonText: "انصراف"
          }).then((result) => {
            if (result.isConfirmed) {
                if(tr_length==2){
                    $(table_obj).remove()
                }
                if(tr_length>2){
                    $("#deliver_method_box_"+inner_form_index.toString()) .remove();
                }
                delete deliver_method_array['row_'+inner_form_index.toString()];
                if($("#"+btn_id).is(":hidden")){
                    $("#"+btn_id).show();
                }
                set_table_row_number(table_obj)
            }
          });
    }
}
function set_pay_method_elements(input,parent_id){
    // if($(input).val()==3){
    //    $(parent_id).find(".pay_method_cheque").parent().show();
    // }
    // else{
    //     $(parent_id).find(".pay_method_cheque").parent().hide();
    // }
}

function set_deliver_method_elements(input,parent_id){
    if($(input).val()==1){
       $(parent_id).find(".deliver_part_amount").parent().hide();
    }
    else{
        $(parent_id).find(".deliver_part_amount").parent().show();
    }
}

async function toggle_vat_amount(input,form_index){
    var fix_data=await get_fixed_inq_data();
    var previous_val=$(input).val();
    $(input).on("change",function(){
       
        if($(input).val()==1){
        {
            var vat_percent=fix_data['vat_percent'];
            if(!$("#export_good_quantity_"+form_index).val()||!$("#export_unit_price_"+form_index).val()){
                custom_alerts('قیمت واحد کالا یا مقدار کالا  را تکمیل نمایید','e',0,'خطا');
                if(previous_val==0){
                    $(input).css('color','red');
                }
                if(previous_val==1){
                    $(input).css('color','green');
                }
                $(input).val(previous_val);
                return false;
            }
            var a=parseFloat(numberformat2($("#export_good_quantity_"+form_index).val()))
            var b=parseFloat(numberformat2($("#export_unit_price_"+form_index).val()))
            var c=parseFloat(numberformat2(vat_percent))
            var total_vat_amount=a*b*c/100
            $("#vat_status_"+form_index).css('color','green')
            $("#export_vat_amount_"+form_index).parent().show();
            $("#export_vat_amount_"+form_index).val(total_vat_amount);
            numberformat($("#export_vat_amount_"+form_index),1);
        }
    }
    else
    {

            if($(input).val()==-1 || $(input).val()==0){
                $("#vat_status_"+form_index).css('color','red')
                $("#export_vat_amount_"+form_index).parent().hide();
                $("#export_vat_amount_"+form_index).val(0);
            }
            else{
            
                $("#vat_status_"+form_index).css('color','orange')
                $("#export_vat_amount_"+form_index).parent().hide();
                $("#export_vat_amount_"+form_index).val(0);
            }
        }
    })
}

function handler_seller_data(inq_id,good_id){
    var action='get_seller_data';
    var params={action:action,inq_id:inq_id,good_id:good_id}
    var res=ajaxHandler(params)
    console.log(res);
    return res;
}

async function get_inq_attachment_list(group_code,inq_id){
    var action='get_inq_attachment_list';
    var params={action:action,group_code:group_code,inq_id:inq_id}
    var res= await manageAjaxRequestCustom(params);
    var html=res.data.html
    if(res.res=="false"){
        custom_alerts(res.data,'e',0,'خطا')
    }
    else{
       // return res.data;
       $("#inq_modal_grid").html('');
       $("#inq_modal_grid").html(html);
       if(res.data.allow_upload!=1){
            $("#inq_attachment_modal").find('.modal-body').remove();
       }
    }
}
function display_inq_attachments(group_code,inq_id){
    if($("#inq_attachment_modal").length){
        $("#inq_attachment_modal").remove();
    }
    $('body').append(`
<div class="modal fade" id="inq_attachment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background:rgba(128,128,128,0.5)">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> مدیریت مستندات پیوست شده  </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span style="color:red;font-size:2.5rem" aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">عنوان فایل :</label>
                <input type="text" class="form-control" id="inq_file_title">
            </div>
          
            <div class="custom-file mb-3">
            <label class="col-form-label" for="customFile">انتخاب فایل:</label>
                <input type="file" class="form-control" id="inq_custom_file" name="filename">
            </div>
        </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="attach_inq_file(${group_code},${inq_id})">تایید</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>
        </div>
      </div>
      <div id="inq_modal_grid"></div>
    </div>
  </div>
</div>
    
    `);
    $("#inq_attachment_modal").modal({backdrop: 'static', keyboard: false}) 
    get_inq_attachment_list(group_code,inq_id);

}
 function attach_inq_file(group_code,inq_id){
   
    var file_title=$("#inq_file_title").val();
    var file_input=document.getElementById('inq_custom_file');
    var file_data = file_input.files;   
    var form_data = new FormData(); 
    form_data.append('file',file_data[0]) ;
    form_data.append('action','attach_inq_file') ;
    form_data.append('file_title',file_title) ;
    form_data.append('group_code',group_code) ;
    form_data.append('inq_id',inq_id) ;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/managemantproccess.php',true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if(res['res'] == "false"){
                custom_alerts(res.data,'e',0,'پیوست فایل',);
            }else{
                custom_alerts(res.data,'i',0,'پیوست فایل',);
                get_inq_attachment_list(group_code,inq_id);
                $("#inq_file_title").val('');
                $("#inq_custom_file").val('');
            }
        }else{
            custom_alerts('خطایی رخ داده است','e',0,'پیوست فایل',);
        }
    };
    xhr.send(form_data);

}

async function delete_inq_file(RowId,group_code,inq_id){
    var action="delete_inq_file";
    var params={action:action,RowId:RowId}
    Swal.fire({
        title: "حذف فایل ",
        text: "آیا از حذف فایل مطمئن می باشید ؟",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "بله حذف شود",
        cancelButtonText:'انصراف'
      }).then(async(result) => {
        if (result.isConfirmed) {
            var res= await manageAjaxRequestCustom(params);
            if(res.res=="false"){
                custom_alerts(res.data,'e',0,'خطا')
                return false;
            }
            get_inq_attachment_list(group_code,inq_id);
        }
      });
}

function set_label(input,enter){
    var input_value=$(input).val();
    var parent=$(input).parent();
    if(enter==1) {
        if (!$(parent).find(".input_label").length) {
            $(parent).css('position', 'relative');
            $(parent).append(`<label class="input_label">${$(input).attr('placeholder')}</label>`)
            $(input).attr('placeholder', '');
            $(parent).css('border','none');
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
    var fix_data=await get_fixed_inq_data();
    var inq_good_name=$("#inq_good_name_"+good_form_index+"_hidden").val();
    var inq_good_quantity_unit=$("#inq_good_quantity_unit_"+good_form_index).val();
    var inq_good_quantity=numberformat2($("#inq_good_quantity_"+good_form_index).val());
    var inq_good_buy_request_num=numberformat2($("#inq_good_buy_request_num_"+good_form_index).val());
    var inq_good_desc=$("#inq_good_desc_"+good_form_index).val();
    var buy_base_desc=$("#buy_base_desc_"+good_form_index).val();

    //-------------------------seller form elements
    var export_inq_seller_name=$("#export_inq_seller_name_"+seller_form_index).val()
    var temp_seller_name=$("#temp_export_inq_seller_name_"+seller_form_index).val()
   // var inq_good_deliver_time=$("#inq_good_deliver_time_"+seller_form_index).val()
    var export_good_quantity=numberformat2($("#export_good_quantity_"+seller_form_index).val())
    var is_vat=$("#vat_status_"+seller_form_index).val();

    var export_unit_price = numberformat2($("#export_unit_price_"+seller_form_index).val())
   // var export_pay_method=$("#export_pay_method_"+seller_form_index).val()
    var export_rent_inside = numberformat2($("#export_rent_inside_"+seller_form_index).val())
    var export_rent_outside = numberformat2($("#export_rent_outside_"+seller_form_index).val());
    var seller_group_code = $("#group_code_hidden_"+seller_form_index).val()?$("#group_code_hidden_"+seller_form_index).val():"";
    var export_description = $("#export_description_"+seller_form_index).val();
    //-----------------------------------------------------------------------------
    //var inq_good_buy_request_num = $("#inq_good_buy_request_num_"+good_form_index).val();
    var inq_good_buy_request_num = numberformat2($("#export_good_quantity_"+seller_form_index).val());
   // alert(good_form_index);
    var inq_good_desc = $("#inq_good_desc_"+good_form_index).val();
    var vat_rate = 0;
    var vat_value = 0;
     var export_pay_method = [];
    var export_deliver_method=[];
    // for(k in pay_method_array){
    //     export_pay_method.push(pay_method_array[k])
    // }
    // for(k in deliver_method_array){
    //     export_deliver_method.push(deliver_method_array[k])
    // }
    
    //-----------------------------------------------------------------------------
    if(is_vat==1){
       
        vat_rate=fix_data['vat_percent'];
        vat_value=parseFloat(numberformat2(inq_good_buy_request_num))*parseFloat(numberformat2(export_unit_price))*vat_rate/100;
    }
    if(export_inq_seller_name.trim()=="" || export_inq_seller_name.trim()==0){
        custom_alerts('نام فروشنده وارد نشده است','e','خطا')
        return false;
    }
    if(export_good_quantity.trim()=="" || export_good_quantity.trim()==0){
        custom_alerts('مقدار کالا  وارد نشده است','e','خطا')
        return false;
    }
    if(export_unit_price.trim()=="" || export_unit_price.trim()==0){
        custom_alerts('قیمت واحد کالا وارد نشده است','e','خطا')
        return false;
    }
    if(export_rent_inside.trim()=="" || export_rent_inside.trim()==0){
        // custom_alerts('کرایه حمل داخلی وارد نشده است','e','خطا')
        // return false;
        export_rent_inside=0;
    }
    if(export_rent_outside.trim()=="" || export_rent_outside.trim()==0){
        // custom_alerts('کرایه حمل خارجی وارد نشده است','e','خطا')
        // return false;
        export_rent_outside=0;
    }
    var sum_pay_methods = 0;
    var sum_deliver_methods = 0;
    
    $("#pay_method_box_"+seller_form_index).find('input.pay_cash_section').each(function(){
        sum_pay_methods=parseFloat(sum_pay_methods)+parseFloat(numberformat2($(this).val()));
    })

    $("#pay_method_box_"+seller_form_index + " tbody").find('tr').each(function(){
        var p_obj_row={pay_method:$(this).find('td:eq(1)').children().first().val(),
                        pay_type:$(this).find('td:eq(2)').children().first().val(),
                        pay_amount:$(this).find('td:eq(3)').children().first().val(),
                        pay_date:$(this).find('td:eq(4)').children().first().val(),
                        pay_desc:$(this).find('td:eq(5)').children().first().val()};
                export_pay_method.push(p_obj_row);
            })
    
    $("#deliver_method_box_"+seller_form_index+" tbody").find('tr').each(function(){
        var d_obj_row={deliver_method:$(this).find('td:eq(1)').children().first().val(),
                        deliver_amount:$(this).find('td:eq(2)').children().first().val(),
                        deliver_date:$(this).find('td:eq(3)').children().first().val(),
                        deliver_desc:$(this).find('td:eq(4)').children().first().val()}
        export_deliver_method.push(d_obj_row);
       })
     
    if(sum_pay_methods == 0){
        custom_alerts(' هیچ گونه ردیف پرداخت برای این کالا وارد نشده است ','e','خطا')
        return false;
    }
    console.log('sum_pay_methods:'+sum_pay_methods);
    console.log('قیمت تمام شده:'+(parseInt(inq_good_buy_request_num*export_unit_price)+parseInt(vat_value)));
    console.log('تعداد تمام شده:'+(inq_good_buy_request_num));//*export_unit_price)+parseInt(vat_value));
    console.log('vahed تمام شده:'+(export_unit_price))
    console.log('vat تمام شده:'+parseInt(vat_value));
    if(sum_pay_methods!=(parseFloat(numberformat2(inq_good_buy_request_num))*numberformat2(export_unit_price)+parseFloat(numberformat2(vat_value)))){
        custom_alerts('مجموع قیمت تمام شده کالا با  مجموع ردیف پرداخت برابر نمی باشد','e','خطا')
        return false;
    }
   
    $("#deliver_method_box_"+seller_form_index).find('input.deliver_part_amount').each(function(){
        sum_deliver_methods=parseFloat(numberformat2(sum_deliver_methods))+parseFloat(numberformat2($(this).val()));
    })
       
    if(sum_deliver_methods==0){
        custom_alerts(' هیچ گونه ردیف تحویل برای این کالا وارد نشده است ','e','خطا')
        return false;
    }

    if(numberformat2(sum_deliver_methods)!=numberformat2(inq_good_buy_request_num)){
            custom_alerts('مقدار کالای درخواستی با مجموع ردیف تحویل کالا  برابر نمی باشد','e','خطا')
            return false;
    }

    var action="add_seller_buyer_save";
    var params=
        {
            action:action,is_vat:is_vat,inq_good_name:inq_good_name,inq_good_quantity_unit:inq_good_quantity_unit,inq_id:inq_id,
            inq_good_buy_request_num:inq_good_buy_request_num,inq_good_desc:inq_good_desc,buy_base_desc:buy_base_desc,
            inq_good_quantity:inq_good_quantity,export_inq_seller_name:export_inq_seller_name,temp_seller_name:temp_seller_name,
            export_good_quantity:export_good_quantity,export_unit_price:export_unit_price,
            export_rent_inside:export_rent_inside,export_rent_outside:export_rent_outside,seller_group_code:seller_group_code,
            export_description:export_description,inq_good_buy_request_num:inq_good_buy_request_num,inq_good_desc:inq_good_desc,
            buy_base_desc:buy_base_desc,export_pay_method:JSON.stringify(export_pay_method),export_deliver_method:JSON.stringify(export_deliver_method)
        }

    var res = await manageAjaxRequestCustom(params);
    
    if(res.data.length >0){
        $("#add_good").attr('disabled',false);
        $("#add_seller_"+good_form_index).attr('disabled',false);
        if($("#add_seller_btn_"+good_form_index).length){
            $("#add_seller_btn_"+good_form_index).remove();
        }
        var abb_btn='<div style="width: 82%;margin: auto;padding-block: 10px"> <button id="add_seller_btn_'+good_form_index+'" parent_box_index="'+good_form_index+'" type="button" class="btn"  style="width: 100%;background: #e9ecef;color:#fff;font-size: 2rem"> + </button> </div>'
        $(input).parents('#form_create_inq_box_'+good_form_index).append(abb_btn);
        $("#add_seller_btn_"+good_form_index).click(function(){
           // alert('45');
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
        $("#form_create_good_row_"+good_form_index).find('input,select,textarea,button').each(function(){
            $(this).attr('disabled');
        })
        pay_method_array=[];
        deliver_method_array=[];
    }
    $(input).parents()
    $(".delete_seller_btn").attr('disabled',false);
    $("#form_section_inq_two").find('.footer').show(100);
    $("#group_code_hidden_"+seller_form_index).val(res.data);
    
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

  function delete_all_form_inq(input,selector,good_form_index){ // حذف ردیف کالا به همراه فروشنده ها
 //  alert( sessionStorage.getItem('inq_uncode'));
    var id=$(input).attr('id');
    var inq_id=sessionStorage.getItem('inq_uncode');
    Swal.fire({
        title: "حذف استعلام ردیف کالا",
        text:'تمامی فروشنده ها به همراه ردیف کالا حذف خواهد شد \n ادامه می دهید؟',
        showDenyButton: false,
        showCancelButton: true,
        cancelButtonText: 'انصراف',
        confirmButtonText: "حذف",
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            if (inq_id) 
            {
                var action = "delete_all_seller_buyer_row";
                var good_id=$("#inq_good_name_"+good_form_index+"_hidden").val();
                var params = {action: action, inq_id: inq_id,good_id:good_id}
                var res =await  manageAjaxRequestCustom(params)

                if(res.res=='true'){
                    custom_alerts(res.data,'i',0,'حذف');
                    $(selector).hide(2000);
                    var input=$("#"+id)
                    $(input).parents('.form_create_seller_box').siblings('.add_seller_btn_box').show()
                    setTimeout(() => {
                    $(selector).remove();
                    }, 1000);
                    // if (siblings >= 2) {
                    //     $("#add_good").removeAttr('disabled')
                    // }
                
                    var form_length=await $("#temp_inqs-section").children().find('.form-seller').length
                    console.log(form_length)
                    if(form_length<2){
                        $('#step_btn_1').hide();
                    
                    }
                    $("#add_good").attr('disabled',false)
                    
                }
                else{
                    custom_alerts(res.data,'e',0,'خطا');
                }
            }
            else
            {
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

        } else if (result.isDenied) {
          return false;
        }
      });
    
//     $('body').append(` 
//  <div id="delete_confirm2" class="modal" tabindex="-1" role="dialog">
//   <div class="modal-dialog" role="document">
//     <div class="modal-content">
//       <div class="modal-header">
//         <h5 class="modal-title">حذف رکورد</h5>

//       </div>
//       <div class="modal-body">
//         <p>رکورد به همراه فروشنده ها و کالا حذف شود ؟ ؟</p>
//       </div>
//       <div class="modal-footer">
//         <button type="button"  id="close_del_modal" class="btn btn-danger" data-dismiss="modal">انصراف</button>
//         <button type="button"  onclick="do_delete_all_form_inq ('${id}', '${selector}',  '${inq_id}')" id="btn_delete_confirm" class="btn btn-primary">تایید</button>
//       </div>
//     </div>
//   </div>
// </div>`);
//     $("#delete_confirm2").modal('show');
//     $("#close_del_modal").click(function () {
//         $("#delete_confirm").modal('hide');
//     })

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

 async function delete_seller(input,form_index,form_good_index){
    var seller_group_code = $("#group_id_"+form_index).val()?$("#group_id_"+form_index).val():0;
    event.preventDefault();
    Swal.fire({
        title: "حذف",
        text: 'آیا از حذف فروشنده مطمئن هستید ؟',
        showDenyButton:true,
        showCancelButton: false,
        confirmButtonText: "حذف",
        denyButtonText: `انصراف`
      }).then(async(result) => {
        if (result.isConfirmed) {
            if(seller_group_code!=0){
                
            }
            $(input).parents("#form_create_seller_row_"+form_index).hide(3000);
            await $(input).parents("#form_create_seller_box_"+form_index).remove();
            var seller_form_length= await $("form[related_good_form='form_create_good_row_"+form_good_index+"']").length
           alert(seller_form_length);
          
           if(parseFloat(seller_form_length)==1){
               $("#add_seller_btn_"+form_good_index).remove();
               $("button[step='2']").hide();
               $("#export_inq_btn_delete_inq_"+form_good_index).hide();
               $("#add_seller_"+form_good_index).parent().show();
           }
        } 
      });
 }

async function do_delete_all_form_inq (id, selector, inq_id){

    if (inq_id) 
    {
        var action = "delete_all_seller_buyer_row";
        var params = {action: action, inq_id: inq_id}
        var good_id=$("#inq_good_name_0_hidden").val();
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
            if(!form_length){
                $('#step_btn_1').hide();
            }
            
        }
        else{
            custom_alerts(res.data,'e',0,'خطا');
        }
    }
    else
    {
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
