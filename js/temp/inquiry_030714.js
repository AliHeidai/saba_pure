var parent_inquiry_id=0;
var inq_fix_data=get_fixed_inq_data();
var selected_radio_inq;
var seller_temp_id;
var pay_method_array=[];
var deliver_method_array=[];
var sum_good_amount;
var sum_inq_cost;
var timeInterval=0;

// function set_label(input,enter){
//     var input_value=$(input).val();
//     var parent=$(input).parent();
//     if(enter==1) {
//         if (!$(parent).find(".input_label").length) {
//             $(parent).css('position', 'relative');
//             $(parent).append(`<label class="input_label">${$(input).attr('placeholder')}</label>`)
//             $(input).attr('placeholder', '');
//             $(parent).css('border','none');
//         }
//     }
//     if(enter==0) {
//         if ($(input).val()) {
//             $(input).css('border', '1px solid #b4d1c6')
//         }
//         else
//         {
//             $(parent).css('position', 'relative')
//             $(input).attr('placeholder', $(parent).find(".input_label").text());
//             $(parent).find(".input_label").remove()
//         }
//     }
//     $(input).css({'background':'transparent',"text-align":"center"})
// }

function set_persian_date(element,status=0){
    var element_id="#"+$(element).attr('id')
    var date_json={targetTextSelector: element_id};
    if(status!=0)
    {
        if(status==-1){
        
            date_json.disableAfterDate=new Date()
        }
        if(status==1){
            date_json.disableBeforDate= new Date()
        }
    }
    $(element).MdPersianDateTimePicker(date_json);
}
    
 async function swal_custom(title,text,type){
    var msg_type="";
    switch(type)
    {
        case "e":
            msg_type="error";
            Swal.fire({
                title: title,
                text: text,
                icon: msg_type
              });
            break;
        case "w":
            msg_type="warning";
            Swal.fire({
                title: title,
                text: text,
                icon: msg_type
              });
            break;
        case "i":
            msg_type="info";
            Swal.fire({
                title: title,
                text: text,
                icon: msg_type
              });
            break;
        case "s":
            msg_type="success";
            Swal.fire({
                title: title,
                text: text,
                icon: msg_type
              });
            break;
        case "c":
            msg_type="confirm";
            var conf =await Swal.fire({
                title: title,
                text:text,
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "حذف شود",
                denyButtonText: `انصراف`
              }).then((result) => {
                if (result.isConfirmed) {
                   return 1;
                } else if (result.isDenied) {
                    return 0;
                }
              });
            break;
    }
    return conf;
}

function get_inq_groups(group_id=0){
    var action="get_inq_groups";
    var params={action:action}
    var res=ajaxHandler(params)
    var options="";
    if(group_id==0){
        options=`<option value="0"> گروه استعلام را انتخاب نمایید</option>`;
        for(k in res){
            options+=`<option value="${res[k]['code']}">${res[k]['description']}</option>`
        }
    }
    else{
        for(k in res){
            if(res[k]['code']==group_id){
                options=res[k]['description'];
                continue;
            }
        }
    }
    
    return options;

}

async  function manageAjaxRequestCustom(formData,loading=0,atype,url){
    var res=0;
    if(loading==1){
        add_loading(formData.action);
    }
    if(typeof atype == "undefined"){
        var atype="POST";
    }
    if(typeof url == "undefined"){
        var url = "php/managemantproccess.php";
    }
    var prms = new URLSearchParams(formData);
   
    const rawResponse = await fetch(url, {
        method: atype,
        
        credentials: "same-origin",
        headers: new Headers({
            'Content-Type': 'application/x-www-form-urlencoded', // <-- Specifying the Content-Type
            
        }),
        body: prms
    });
    const content = await rawResponse.json().then((data)=>{

      res = data;
       // //console.log(res);

    }).catch((e)=>{
        res={msg_type: 'error', message: 'خطای غیر منتظره '};

    });
    if(loading==1){
        remove_loading(formData.action);
    }
    return res;
}

async function goToPurchaseInquiry(){
    var tab=
    `<ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link inq_active inq_tab" id="temp_inqs-tab" data-bs-toggle="tab" data-bs-target="#temp_inqs" type="button" role="tab" onclick="get_inquires(this,0,'temp_inq_detailes')" aria-selected="true">استعلام های موقت</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link inq_tab" id="ready_inqs-tab" data-bs-toggle="tab" data-bs-target="#ready_inqs" type="button" role="tab" aria-controls="ready_inqs" onclick="get_inquires(this,1,'temp_inq_detailes')" aria-selected="false"> استعلام های آماده ارسال</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link inq_tab" id="varede_inqs-tab" data-bs-toggle="tab" data-bs-target="#varede_inqs" type="button" role="tab" aria-controls="varede_inqs" onclick="get_inquires(this,2,'temp_inq_detailes')" aria-selected="false">استعلام های وارده</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link inq_tab" id="saderh_inqs-tab" data-bs-toggle="tab" data-bs-target="#saderh_inqs" type="button" role="tab" aria-controls="saderh_inqs"  onclick="get_inquires(this,3,'temp_inq_detailes')""aria-selected="false">استعلام های صادره</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="inq-section" id="temp_inqs" role="tabpanel" aria-labelledby="temp_inqs-tab"> 
           
            <div id="temp_inq_detailes">
                
            </div>
        </div> 
    </div>`;

  $("#PurchaseInquiry").html(tab);
  get_inquires($("#temp_inqs-tab"),0,'temp_inq_detailes')
   
}
       
function create_new_inquiry(){
    var inq_group_options = get_inq_groups();
    if($("#create_inq").length){
        $("#create_inq").remove();
    }
    var modal=`<div class="modal fade" id="create_inq" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary text-light">
          <h5 class="modal-title" id="exampleModalLongTitle">ایجاد استعلام بها جدید </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="h3 text-danger">X</span>
          </button>
        </div>
        <div class="modal-body">
        <form class="w-100">
            <div class="form-row border-bottom py-2">
                <div class="form-group col-md-6">
                    <label for="inq_date">تاریخ استعلام </label>
                    <input type="text" class="form-control" id="inq_date" placeholder="تاریخ استعلام ">
                </div>
                <div class="form-group col-md-6">
                    <label for="inq_group">گروه استعلام </label>
                    <select  class="form-control" id="inq_group">${inq_group_options}</select>
                   
                </div>
            </div>
          
      </form>
          
        </div>
        <div class="modal-footer">
         
          <button type="button" class="btn btn-primary" onclick="do_create_inquiry()">ایجاد استعلام </button>
        </div>
      </div>
    </div>
  </div>`;
    $("#temp_inqs").append(modal)
    set_persian_date($("#inq_date"))
    $("#create_inq").modal({backdrop:'static',keyboard:false},'show')

}

 function do_create_inquiry(){
    var inq_date=$("#inq_date").val();
    var inq_group=$("#inq_group").val();
    var inq_description=$("#inq_description").val()?$("#inq_description").val():"";
    if(!inq_date.trim()){
        swal_custom('خطا','تاریخ استعلام را وارد نمایید','w');
        return false;
    }
    if(!inq_group.trim() || inq_group==0){
        
        swal_custom('خطا','گروه استعلام را وارد نمایید','w');
        return false;
    }
    var action = "do_create_inquiry";
    var params = {action:action,inq_date:inq_date,inq_group:inq_group,inq_description:inq_description}

    var res = manageAjaxRequestCustom(params);
    
    get_inquires($("#temp_inqs-tab"),0,"temp_inq_detailes");
    $("#create_inq").modal('hide');
    $(".modal-backdrop").hide();
 }
// function add_inquiry_detailes()
// {
//    if($("#edit_create_inq").length){
//         $("#edit_create_inq").remove();
//    }
//     var modal=`
//     <div class="modal fade" id="edit_create_inq" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
//     <div class="modal-dialog modal-dialog-centered" role="document">
//       <div class="modal-content">
//         <div class="modal-header bg-primary text-light">
//           <h5 class="modal-title" id="exampleModalLongTitle">ایجاد استعلام بها جدید </h5>
//           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
//             <span aria-hidden="true" class="h3 text-danger">X</span>
//           </button>
//         </div>
//         <div class="modal-body">
//         <form class="w-100">
//             <div class="form-row border-bottom py-2  ">
//                 <div class="form-group col-md-6">
//                     <label for="inq_date">تاریخ استعلام </label>
//                     <input type="text" class="form-control" id="inq_date" placeholder="تاریخ استعلام ">
//                 </div>
//                 <div class="form-group col-md-6">
//                     <label for="inq_group">گروه استعلام </label>

//                     <input type="password" class="form-control" id="inq_group" placeholder="گروه استعلام ">
//                 </div>
//             </div>
//             <div class="form-row border-bottom py-2 ">
//             <div class="form-group col-md-12">
//                 <label for="good_name">شرح کالا</label>
//                 <input type="text" class="form-control" id="good_name" placeholder="شرح کالا">
//             </div>
//             </div>

//             <div class="form-row border-bottom py-2 ">
//                 <div class="form-group col-md-6">
//                     <label for="good_quantity"> تعداد کالا </label>
//                     <input type="text" class="form-control" id="good_quantity" placeholder="تعداد کالا ">
//                 </div>
//                 <div class="form-group col-md-6">
//                     <label for="good_unit"> واحد کالا </label>
//                     <input type="text" class="form-control" id="good_unit" placeholder="واحد کالا">
//                 </div>
//             </div>
//             <div class="form-row border-bottom py-2 ">
//                 <div class="form-group col-md-6">
//                     <label for="inq_buy_num"> شماره درخواست خرید </label>
//                     <input type="text" class="form-control" id="inq_buy_num" placeholder="شماره درخواست خرید ">
//                 </div>
//                 <div class="form-group col-md-6">
//                     <label for="basis_purchase">مبنای درخواست</label>
//                     <input type="password" class="form-control" id="basis_purchase" placeholder="مبنای درخواست">
//                 </div>
//             </div>
//             <div class="form-group  py-2 ">
//                 <label for="good_description">توضیحات</label>
//                 <textarea class="form-control" id="good_description" placeholder="توضیحات"></textarea>
//             </div>
//         </form>
          
//         </div>
//         <div class="modal-footer">
//           <button type="button" class="btn btn-primary">Save changes</button>
//         </div>
//       </div>
//     </div>
//   </div>`
//     $("#temp_inqs").append(modal);
//    $("#edit_create_inq").modal({backdrop:'static',keyboard:false},'show')
    
// }

async function display_inq_counts(){
    var action = "display_inq_counts";
    var res = await manageAjaxRequestCustom({action:action})
    var status = res.data;
 
    for(k in status){
        var elm = "";
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

function get_sellers(seller_id=0){
    var action = "inq_get_all_buyer_sellers";
    var params={action:action,seller_id:seller_id}
    var res=ajaxHandler(params)
    return res
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


function go_to_setting_page(input){
    var target_id=$(input).attr('href');
    $("#setting_inqs-section .setting .setting_page").hide();
    $(target_id).show();
    if(target_id=="#seller_manangment"){
        create_data_table("seller_manage_tbl")
    }

}


function get_inquires(input,status,selector,page=1,other_params={}){
    var action="get_inquires";
    if(typeof selector=="string"){
        selector="#"+selector;
    }
    var param={action:action,status:status,page:page,other_params:other_params}
    var paragraph_array="";
    var res=ajaxHandler(param);
    
    $(selector).html('');
    $(selector).html(res);
    var inq_from_date_search = new AMIB.persianCalendar('inq_from_date_search_'+status);
    var inq_to_date_search = new AMIB.persianCalendar('inq_to_date_search_'+status);
    if(status!=0){
        $("#add_inq").remove();
    }
    else{
        $(selector).prepend(`<button  id="add_inq" onclick="create_new_inquiry()" class="btn btn-primary">ایجاد استعلام جدید </button>`);
      //  $(".inq_temp_headeing").html(`<button id="add_inq" onclick="create_new_inquiry()" class="btn btn-primary">ایجاد استعلام جدید </button>`);
        
                
    }
    $(".inq_tab").removeClass('inq_active');
    $(input).addClass('inq_active');
}

//************************************************** */
function search_inquiry(input,status)
{
    var parent=$(input).parents(".inq_list_header_search")
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
function get_inq_added_goods(id){
    var action="get_inq_added_goods";
    var params={action:action,inq_id:inq_id}
    var res=ajaxHandler(params);
    return res;
}


function add_row_inq_table(added_goods,edit=0){

    var good_id=added_goods['good_id']
    var td_content=`<div >
              
                <fieldset class="form-group-custom">
                    <legend>شرح کالا  </legend>
                   <p class="text-center">${added_goods['good_name']}</p>
                </fieldset>
                <fieldset class="form-group-custom">
                    <legend>مقدار مورد درخواست</legend>
                        <p class="text-center">${added_goods['quantity']} ${added_goods['unit']}</p>
                </fieldset>
                <fieldset class="form-group-custom">
                    <legend> مبنای درخواست</legend>
                        <p class="text-center">${added_goods['base']}</p>
                    </fieldset>
                <fieldset class="form-group-custom">
                    <legend>شماره درخواست خرید</legend>
                    <p class="text-center">${added_goods['inq_num']}</p>
                </fieldset>
            </div>`
   
   if(edit==0){
    var seller_td="";
        $("#inq_table").find("th.inq_detailes_th").each(function(){
            var seller_id=$(this).attr('seller_id')

            var seller_name_array=get_sellers(seller_id);
            var seller_name=seller_name_array[0]['Name'];
            seller_td+=`<td class="inq_detaile_td" style="min-width:300px;min-height:300px">
                <div style="min-height:300px;height:auto">
                    <button id="more_seller_data_${seller_id}" onclick="register_inq_detailes(this,${seller_id},'${seller_name}')" class="btn btn-success">ثبت/ویرایش جزییات</button>
                </div>
            </td>`
        });
        return `<tr style="border-bottom:4px solid gray" good_id="${added_goods['RowID']}" good_name="${added_goods['good_name']}" class="good_detailes" id="good_detailes_${good_id}">
        <td >
            ${td_content}
            
        </td>
        <td id="last_inq_result_${good_id}">
        </td>
        
    </tr>`
   }
   return td_content;
}

async function  open_inq_manage(id,status){
    
    if(parseInt(id)>0){
        var action = "get_inquiry_detailes";
        var params = {action:action,row_id:id};
        var res = await manageAjaxRequestCustom(params);
    }
   //-----------------------------------------------
    add_loading(action);
   //-----------------------------------------------
    if(res.data[0]){
        var data_info=res.data[0]
        var inq_code=data_info['inquiry_code'];
        var inq_group=data_info['title'];
        var description=data_info['description']?data_info['description']:'';
    }
    var added_goods=get_goods_row(id,0,1);
    var disable="disabled";
    var show_flag=false;
    if(added_goods && added_goods.length>0){
       disable="";
        show_flag=true;
        var good_td="";
        for(k in added_goods){
            
            good_td+=add_row_inq_table(added_goods[k]);
           
        }
        var selected_sellers=await add_row_inq_seller_table(added_goods[k]['good_id'],added_goods[k]['inq_id']);
    }

    switch(status){
        case 0:
        case "0":
            title="استعلام موقت"
            break;
        case 1:
        case "1":
             title="استعلام آماده ارسال"
            break;
        case 2:
        case "2":
             title="استعلام وارده"
            break;
        case 3:
        case "3":
            title="استعلام صادره"
            break;
        case 10:
        case "10":
            title="استعلام آرشیو شده"
            break;
            
    }
    var add_good_btn=`<button id="add_good_row" onclick="add_good_inq(this)" class="btn btn-success mx-2 col-md-1">مدیریت  کالا</button>`
    var add_seller_btn=` <button ${disable} onclick="add_seller()" id="add_seller_btn"  title="مدیریت فروشندگان" class="btn btn-info col-md-1 mx-2">مدیریت فروشندگان</button>`
    var inq_print_btn=` <button ${disable} onclick="inqiury_print(${id})" id="print_btn"  title="پرینت استعلام " class="btn btn-dark col-md-1 mx-2"> پرینت استعلام</button>`
    var send_inq_btn=`<button ${disable} onclick="send_inq(${id},${status})" id="send_inq_btn"  title="ارسال" class="btn btn-primary col-md-1 mx-2">ارسال</button>`
    var ready_to_send_btn=`<button ${disable} onclick="send_to_ready()" id="send_to_ready_btn"  title="تایید و ادامه" class="btn btn-secondary col-md-1 mx-2">تایید و ادامه</button>`
    var inq_archive_btn=`<button ${disable} onclick="archive_inq(${id})" id="archive_inq_btn"  title="بایگانی و توقف" class="btn btn-warning col-md-1 mx-2">  بایگانی و توقف  استعلام </button>`
 
    var modal=`
    <div id="inq_detailes" >
        <div class="return_box"><i class="text-danger h5 fa fa-undo return_btn" aria-hidden="true" titile="بازگشت به لیست استعلامات" onclick="menu_item_display(goToPurchaseInquiry,'PurchaseInquiry')"></i></div>
        <input type="hidden" value="${id}" id="inq_id_hidden" >
        <ul class="inq_breadcrumb">
                    <li><a href="#" onclick="menu_item_display(goToPurchaseInquiry,'PurchaseInquiry')">استعلام بها</a></li>
                    <li><a href="#">${title}</a></li>
                    
        </ul>
          <div id="inq_header" class="row ">
                
                <div  class="col-md-4 text-center" >
                    <label class="p-4" >شماره استعلام  :</label><label>${inq_code}</label>
                </div>
                <div  class="col-md-4  text-center" >
                    <label class="p-4"> گروه استعلام   :</label><label>${get_inq_groups(inq_group)}</label>
                </div>
                <div class="col-md-4  text-center" > 
                    <label class="p-4">${description}</label>
                </div>
            </div>
            <div class="row justify-content-center" id="add_good_row">`
    if(status==0){
        modal+=`${add_good_btn} ${add_seller_btn} ${ready_to_send_btn}`        
    }
    else
    {
       
        if(status==1){
            modal+=`${send_inq_btn} ${inq_archive_btn}`
           
        }
        if(status==2){
             modal+=`${send_inq_btn}`
        }
         modal+=`${inq_print_btn}`
                 
    } 
    
      modal+=`</div>
        <div class="row">
            <table id="inq_tbl_main" style="overflow:scroll;margin:1rem auto;display:none" border="1" >
                <tr>
                    <td colspan="20">
                        <table id="inq_table" class="table-bordered table-striped" >
                            <thead>
                                <tr>
                                    <th style="min-width:215px;width:215px">مشخصات  کالا</td>
                                    <th style="min-width:200px;width:200px">اطلاعات آخرین خرید</td>
                                </tr>
                            </thead>
                            <tbody>
                            ${good_td}
                            </tbody>
                        </table>
                    </td>
                   
                </tr>
            </table>
        </div>
    </div>
`;

$("#PurchaseInquiry").html(modal)

//------------------------------------------------
if(!selected_sellers){
    remove_loading(action);
    return;
}
for(var i=0;i<selected_sellers.length;i++){
       
    var index=selected_sellers[i]['seller_id']

    var td_text=`
    <div class="container">
        <div class="custom_row py-2">
            <h6 style="min-height:100%;height:100%" class="text-right ">${selected_sellers[i]['Name']} </h6>
            
            
        </div>
        <input type="hidden" id="seller_hidden_id_${selected_sellers[i]['seller_id']}" value="${selected_sellers[i]['seller_id']}">
        <input type="hidden" id="seller_hidden_rowid_${selected_sellers[i]['seller_id']}" value="${selected_sellers[i]['RowID']}">
        <input type="hidden" id="seller_hidden_name_${selected_sellers[i]['seller_id']}" value="${selected_sellers[i]['Name']}">
    </div>`
    appended_td=`<th class="inq_detailes_th" style='width:300px' seller_id="${selected_sellers[i]['seller_id']}" id="seller_th_${selected_sellers[i]['seller_id']}">${td_text}</th>`
    appended_tbody_td=`<td class="inq_detaile_td" style='min-width:300px;min-height:300px'>
                            <div style="min-height:300px;height:auto">
                                <button class="btn btn-success more_seller_data" id="more_seller_data_${selected_sellers[i]['seller_id']}" onclick="register_inq_detailes(this,'${selected_sellers[i]['Name']}',${selected_sellers[i]['seller_id']},${selected_sellers[i]['RowID']})" class='btn btn-success'>ثبت/ویرایش جزییات</button>
                                <div class="seller_summary">
                                   
                                </div>
                            </div>
                        </td>`

  //  close_modal('select_sellers_modal')
    if(appended_td && appended_tbody_td){
        $("#inq_table thead tr").append(appended_td);
        $("#inq_table tbody tr.good_detailes").append(appended_tbody_td);
    }
    if(!$("#origin_data_"+selected_sellers[i]['seller_id']).length)
        {
            $("#seller_th_"+selected_sellers[i]['seller_id']).append(`<div id="origin_data_${selected_sellers[i]['seller_id']}">
                    <div><span>مبدا ارسال :</span><span>${await get_ostan_city(selected_sellers[i]['ostan_id'])??"----"} - ${await get_ostan_city(selected_sellers[i]['city_id'])??"----"}</span></div>
                    <div><span> تلفن :</span><span> ${selected_sellers[i]['phone']??"----"} </span></div>
                </div>`)
        }
}
//------------------------------------------------
    if(show_flag){
        $("#inq_tbl_main").show();
    }
    else{
        $("#inq_tbl_main").hide();
    }
    $("#inq_table").find("div.seller_summary").each(function(){
        var rand=create_random_code();
       
        $(this).attr('id','seller_summary_'+rand)
    });

    $("#inq_table").find("button.more_seller_data").each(function(){
        var rand= create_random_code();
        $(this).attr('id','more_seller_data_'+rand)
        var first=sessionStorage.setItem('first',1);
        $(this).click();
    })
    var first=sessionStorage.setItem('first',0);
    remove_loading(action);
    if(status !=0){
        $(".more_seller_data").remove();
    }
} 
async function send_to_ready(){
    var check_select_inq_detailes=[]
    var good_rows_count=0;
    var inq_id=$("#inq_id_hidden").val();
    $("#inq_table tbody tr.good_detailes").each(function(){
                //var td_count=$(this).find('td.inq_detaile_td').length;
               // var radio_count=$(this).find('td.inq_detaile_td input[type="radio"]').length;
    var checked=$(this).find('input[type="radio"]').val();
    if(parseInt(checked)){
                
        check_select_inq_detailes.push(checked);
                    
        $(this).removeClass('inq_null_row');
        }
    else{
            $(this).addClass('inq_null_row');
        }
                
        good_rows_count++;
        
    })
    if(good_rows_count>0 && check_select_inq_detailes.length==good_rows_count){
        var action="send_to_ready"
        var params={action:action,inq_id:inq_id}
       var res=await manageAjaxRequestCustom(params);
       
        if(res.res=='true'){
            await menu_item_display(goToPurchaseInquiry,'PurchaseInquiry')
            
        }
       // create_comment_modal(check_select_inq_detailes)
    }
    else{
        swal_custom('خطا','برای یک یا چند ردیف کالا  استعلامی ثبت نشده است . ','w');
        return false;
    }
          
    var inq_id=$("#inq_id_hidden").val();
    var action="send_to_ready"
    var params={action:action,inq_id:inq_id}
    var res=await manageAjaxRequestCustom(params);
    if(res.res=="true"){

    }
    
}

async function create_comment_modal(inq_info_array){
    var action="create_comment_modal"
    var params={action:action,RowID_array:inq_info_array}
    var res=await manageAjaxRequestCustom(params)
    if(res.res=='true'){
        var result=res.data;
        var tr_html="";
        console.log(result);
        for(k in result){
            console.log(result[k].good_name);
            tr_html+=`<tr>
                <td>
                    ${parseInt(k)+1}
                </td>
                 <td>
                    ${result[k].good_name}
                </td>
                 <td>
                    ${result[k].seller_name}
                </td>
               
                <td>
                    <textarea class="form-control" id="comment_"></textarea>
                </td>
            
            </tr>`
        }
        if($("#selected_inq_comments").length){
            $("#selected_inq_comments").remove();
        }
        var comment_modal=`<div id="selected_inq_comments" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style="max-width:600px">
                    <div class="modal-content">
                    <div class="modal-header bg-primary text-light">
                        <h5 class="modal-title">ثبت علت انتخاب </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-danger h3">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>مورد/موارد ذیل توسط شما تایید و انتخاب شده است  توضیحات و علت انتخاب خود را وارد نمایید</p>
                        <table border="1" class="table table-borderd">
                        <tr>
                            <td>ردیف </td>
                            <td>شرح کالا  </td>
                            <td>طرف حساب تایید شده</td>
                            <td>علت انتخاب </td>
                        </tr>
                        ${tr_html}
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="save_inq_comment()" class="btn btn-primary">تایید </button>
                       
                    </div>
                    </div>
                </div>
                </div>`
        $("#PurchaseInquiry").append(comment_modal)
        $("#selected_inq_comments").modal({backdrop:'static',keyboard:false},'show');
    }
}

function save_inq_comment(){
    var action="save_inq_comment"
    var comment_
}
async function show_summary_suggests(good_id,seller_id,RowID){

    var action="show_summary_suggests";
    var params={action:action,seller_id:seller_id,RowID:RowID,good_id:good_id}
    var res=await manageAjaxRequestCustom(params);
    var htm="";
    var rand= create_random_code();
    if(res.data.length){
        htm+=`<table  border="1" class="bg-light table table-borderd"><tr><td>انتخاب</td><td>اعتبار قیمت</td><td>قیمت نهایی</td><td>توضیحات</td></tr>`
        for(k in res.data){
            htm+=`<tr>
                    <td>
                        <input type="radio" name="select_${good_id}" value="${res.data[k]['RowID']}"></td>
                        <td><span>${res.data[k]['price_valid']}</span></td>
                        <td><span>${res.data[k]['ultimate_price']}</span> </td>
                        <td><p style="width: 150px;overflow-wrap: break-word">${res.data[k]['description']}</p></td>`
        }
        htm+="</table>"
    }
    else{
        htm=`<p class="text-center text-danger">استعلامی صورت نگرفته است</p>` 
    }
    return htm
}

function create_random_code(){
    var rand= Math.floor(Math.random()*100000000)
    return rand;
}

async function add_row_inq_seller_table(good_id,inq_id){
    var action="add_row_inq_seller_table";
    var params={action:action,good_id:good_id,inq_id:inq_id}
    var res= await manageAjaxRequestCustom(params)
   return res['data'];
   
}

function display_inq_section(target_section){
    $("#PurchaseInquiry").children().find('.inq-section').each(function(){
        $(this).hide();
    })
    $(target_section).show();
}

function add_seller(){
    var inq_id=$("#inq_id_hidden").val();
    
    var seller_array = get_sellers();
    var p_objects = get_all_citeis();
    var option_privonce=`<option value="0"> استان را انتخاب نمایید</option>`;
    var province = p_objects['data'];
    for(p in province){
        option_privonce+=`<option value="${province[p].RowID}">${province[p].title}</option>`
    }
   
    var seller_options=`<option value="-1"> انتخاب نمایید</option>`;
    var counter=1;
    if($("#select_sellers_modal").length){
        $("#select_sellers_modal").remove()
    }
    for(m in seller_array){
        var seller_info=seller_array[m]
        seller_options+=`<option tafzili="${seller_info['code']}" value="${seller_info['RowID']}" seller_addres="${seller_info['address']?seller_info['address']:''}" >${seller_info['Name']}</option>`
        
    }
   
    var modal=`<div id="select_sellers_modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width:900px">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">انتخاب مشتری </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="h3 text-danger" aria-hidden="true" onclick="close_modal('select_sellers_modal')">X</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="staticEmail" class="col-sm-4 col-form-label">انتخاب طرف حساب </label>
                        <div class="col-sm-8">
                            <select class="form-control"  id="inq_seller_selcect">${seller_options}</select>
                        </div>
                    </div>
                </div>
            </div> 
             
             
             
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="staticEmail" class="col-sm-4 col-form-label">استان</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="pri_origin_of_send" onchange="get_citeis(this)" id="inq_seller_selcect">${option_privonce}</select>
                        </div>
                    </div>
                </div>
           
            
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="staticEmail" class="col-sm-4 col-form-label">شهرستان </label>
                        <div class="col-sm-8">
                            <select class="form-control" onchange="" id="city_origin_of_send"></select>
                        </div>
                    </div>
                </div>
           
            
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label for="staticEmail" class="col-sm-4 col-form-label">تلفن </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="seller_phon">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <button class="w-100 btn btn-success" onclick="saveSelectedSeller()"> تایید</button>
                    </div>
                </div>
             
                <div id="selected_sellers"></div>
            </div>
        </div>
        
        </div>
    </div>
</div>`;
$("#PurchaseInquiry").append(modal)
$("#select_sellers_modal").modal({backdrop:'static',keyboard:false},'show')
select_data_picker($("#inq_seller_selcect"))
get_all_inq_sellers(inq_id);


}
  
async function add_good_inq(btn_input){

    var inq_id=$("#inq_id_hidden").val();
    var added_goods=get_goods_row(inq_id,0,1);
    //--------------------------------------------------------
      var modal=
      `<div id="select_goods_modal" class="modal" tabindex="-1" role="dialog">
           <div class="modal-dialog" role="document" style="min-width:90vw">
               <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title">مدیریت کالا </h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span class="h3 text-danger" aria-hidden="true">X</span>
                   </button>
               </div>
               <div class="modal-body">
               <div class="row px-2">
                   <button onclick="edit_create_good_row()" id="add_good_btn" class="btn w-100 btn-secondary text-center" title="افزودن کالا"><i style="font-size:2rem" class="fa fa-plus"></i></button>
               </div>
               <table class="table table-borderd table-striped" id="inq_seleted_goods_tbl">
                   <thead>   
                   </thead>
                   <tbody>
                   </tbody>  
               </table>
               
               </div>
           </div>
       </div>`;
       if($("#select_goods_modal").length){
            $("#select_goods_modal").remove()
        }
       $(btn_input).parents('#inq_detailes').append(modal); 
      //---------------------------------------------------------------------------
    if(added_goods && added_goods.length){
       // disable='disabled'
        $("#inq_seleted_goods_tbl thead").html(
        `<tr>
            <th style="width:5%">#</th>
            <th style="width:17%">شرح کالا </th>
            <th style="width:7%"> تعداد</th>
           
            <th style="width:13%">مبنای خرید</th>
            <th style="width:10%">شماره درخواست خرید </th>
            <th style="width:10%">توضیحات</th>
            <th style="width:8%">عملیات</th>
        </tr>`);
        var counter=1;
        for(k in added_goods){
          // //console.log(added_goods[k]);
            $("#inq_seleted_goods_tbl tbody").append(
            `<tr id="add_good_row_${added_goods[k]['good_id']}">
                <td class="row_number">${counter}</td>
                <td class="form-group" "> <p>${added_goods[k]['good_name']}</p></td>
                <td class="form-group"> <p>${added_goods[k]['quantity']}  ${added_goods[k]['unit']} <p></td>
               
                <td class="form-group"> <p>${added_goods[k]['base']}<p></td>
                <td class="form-group"> <p>${added_goods[k]['inq_num']}<p></td>
                <td class="form-group"> <p>${added_goods[k]['desc']}<p></td>
                <td class="row justify-content-around">
                   
                    <button class="good_edit btn btn-info" onclick="edit_create_good_row(this,${added_goods[k]['good_id']},${added_goods[k]['inq_id']})"><i class="fa fa-edit"></i></button>
                    <button class="good_delete btn btn-danger" onclick="delete_inq_good(this,${added_goods[k]['good_id']},${added_goods[k]['inq_id']})"><i class="fa fa-trash"></i></button>
                </td>
            </tr>` )
            counter++;
        }
    }
    
    
    $("#select_goods_modal").modal({backdrop:'static',keyboard:false},'show')
   
}

function check_inserted_duplicate_good(input){

}


 function edit_create_good_row(input,good_id,inq_id){
    // var target_thead=$("#inq_seleted_goods_tbl thead");
    // var target_tbody=$("#inq_seleted_goods_tbl tbody");
    // var th_count=$("#inq_seleted_goods_tbl").children().find('th').length;
    //-----------------------------------------------------------------------
    var RowID=0;
    var good_rows=get_goods_row(inq_id,good_id);

    var goods = get_all_goods();
    if(good_rows.length){
        RowID=good_rows[0]['RowID'];
    }
  
    var options=`<option value="0"> کالا را انتخاب نماید</option>`
    for(k in goods){
        options+=`<option value="${goods[k]['RowID']}">${goods[k]['title']}</option>` 
    }
    //-------------------------------------------------------------------------------
     //-----------------------------------------------------------------------
     var unit_obj=get_units();
    var unit_options=`<option value="0">واحد کالا را انتخاب نمایید</option>`;
    for(m in unit_obj){
        unit_options+=`<option value="${unit_obj[m]['RowID']}">${unit_obj[m]['description']}</option>`
    }
     //-------------------------------------------------------------------------------
     //-----------------------------------------------------------------------
     var base_inq = get_base_of_inq();
     var base_options=`<option value="0">مبنای خرید را وارد نمایید </option>`
     for(b in base_inq){
        base_options+=`<option value="${base_inq[b]['code']}">${base_inq[b]['description']}</option>` 
     }
     //-------------------------------------------------------------------------------
 
    // var tbl_head=
    // `<tr>
    //     <th style="width:5%">#</th>
    //     <th style="width:17%">شرح کالا </th>
    //     <th style="width:7%"> تعداد</th>
    //     <th style="width:10%">واحد </th>
    //     <th style="width:13%">مبنای خرید</th>
    //     <th style="width:10%">شماره درخواست خرید </th>
    //     <th style="width:30%">توضیحات</th>
    //     <th style="width:8%">عملیات</th>
    // </tr>`
    // if(!th_count){
    //     $(target_thead).append(tbl_head);
    // }
    // var disable="disabled";
    // var tbl_body=
    // `<tr>
    //     <td class="row_number"></td>
    //     <td class="form-group"> <select onchange="set_table_childs_id(this)" class="inq_good form-control">${options}</select></td>
    //     <td class="form-group"> <input class="good_quantity form-control" type="text" ></td>
    //     <td class="form-group"> <select class="inq_good_unit form-control">${unit_options}</select></td>
    //     <td class="form-group"> <select class="inq_base_inquiry form-control">${base_options}</select></td>
    //     <td class="form-group"> <input class="inq_number form-control" type="text" id=""></td>
    //     <td class="form-group"> <textarea class="form-control inq_good_description" style="height:35px"></textarea></td>
    //     <td class="row justify-content-around">
    //         <button  onclick="save_good_row(this)" class="good_save_btn btn btn-success"><i  class=" fa fa-save "></i></button>
    //         <button ${disable}  class="good_edit btn btn-info" onclick="update_good_row(this,0,0)"><i class="fa fa-edit"></i></button>
    //         <button class="good_delete btn btn-danger" onclick="delete_inq_good(this,0,0)"><i class="fa fa-trash"></i></button>
    //     </td>
    // </tr>`
    // $(target_tbody).append(tbl_body);
    // set_table_row_number($("#inq_seleted_goods_tbl"));
    // $(btn_input).prop('disabled',true);
    // // var select_array=[$(".inq_good_unit"),$(".inq_good"),$(".inq_base_inquiry")]
    // select_array.forEach((item)=>{
    //     select_data_picker(item,0,0,'100%')
    // })














    if($("#edit_create_good_row_modal").length){
        $("#edit_create_good_row_modal").remove();
    }
    var modal=`
        <div class="modal fade" id="edit_create_good_row_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h5 class="modal-title" id="staticBackdropLabel">ایجاد /ویرایش  ردیف کالا</h5>
                <button type="button" class="btn btn-default text-danger" data-bs-dismiss="modal" aria-label="Close" onclick="close_modal('edit_create_good_row_modal')"> X</button>
            </div>
            <div class="modal-body">
                 <form>
                    <input type="hidden" id="old_good_name' value="0">
                    <div class="form-group row">
                        <label for="" class="col-sm-4 col-form-label">شرح کالا</label>
                        <div class="col-sm-8">
                        <select onchange="check_inserted_duplicate_good(this)"  id="inq_good" class="form-control">${options}</select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">تعداد</label>
                        <div class="col-sm-8">
                        <input id="good_quantity" class="form-control" type="text" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">واحد</label>
                        <div class="col-sm-8">
                        <select id="inq_good_unit" class="form-control">${unit_options}</select></td>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">مبنای خرید</label>
                        <div class="col-sm-8">
                        <select id="inq_base_inquiry" class="form-control">${base_options}</select>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">شماره درخواست </label>
                        <div class="col-sm-8">
                        <input id="inq_number" class="form-control" type="text" id="">
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="inputPassword" class="col-sm-4 col-form-label">توضیحات</label>
                        <div class="col-sm-8">
                        <textarea class="form-control" id="inq_good_description" style="height:35px"></textarea>
                        </div>
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="inq_old_good_id_hidden"/>
                <button type="button" class="btn btn-primary" onclick="save_good_row(${RowID})"><i class="fa fa-save"></i></button>
            </div>
            </div>
        </div>
        </div>`

    $("#inq_detailes").append(modal);
    $("#edit_create_good_row_modal").modal({backdrop:'static',keyboard:false},'show')
    if(good_rows.length){
        $("#inq_good").val(good_rows[0]['good_id'])
        $("#good_quantity").val(good_rows[0]['quantity'])
        $("#inq_good_unit").val(good_rows[0]['unit'])
        $("#inq_number").val(good_rows[0]['inq_num'])
        $("#inq_base_inquiry").val(good_rows[0]['inq_base'])
        $("#inq_good_description").val(good_rows[0]['description'])
        if(RowID>0){
            $("#inq_old_good_id_hidden").val(good_rows[0]['good_id']);
        }
    }
 }


async function delete_inq_good(input,good_id,inq_id){
    var conf= await swal_custom('حذف', 'آیا از حذف ردیف کالا مطمئن می باشید؟', "c")
    if(conf==1){
        if(good_id==0 && inq_id==0){
            $("#inq_table").find('#good_detailes_'+good_id).remove();
            swal_custom('','ردیف کالا با موفقیت حذف شد','s');
        }
        var action="delete_inq_good";
        var params={action:action,inq_id:inq_id,good_id:good_id}
        var res=await manageAjaxRequestCustom(params)
        if(res.res=="true"){
            if(parseInt(res.data)==1)
            {
                $("#inq_table").find('#good_detailes_'+good_id).remove();
                $(input).parents('tr').remove();
            }
            else{
                $("#inq_table").remove();
            }
           
            swal_custom('','ردیف کالا با موفقیت حذف شد','s');
        }
        else{
            swal_custom('خطا',res.data,'w');
        }
        add_good_inq($("#add_good_row"));
    }
    else{
        return false;
    }
    $(".modal-backdrop").hide();
}
       
function get_goods_row(inq_id,good_id=0,detailes=0){
    var action="get_goods_row"
    var res=ajaxHandler({action:action,inq_id:inq_id,good_id:good_id,detailes:detailes})
    return res;
}

// function add_inq_good_row(btn_input){


















//     // var target_thead=$("#inq_seleted_goods_tbl thead");
//     // var target_tbody=$("#inq_seleted_goods_tbl tbody");
//     // var th_count=$("#inq_seleted_goods_tbl").children().find('th').length;
//     // //-----------------------------------------------------------------------
//     // var goods = get_all_goods();
//     // var options=`<option value="0"> کالا را انتخاب نماید</option>`
//     // for(k in goods){
//     //     options+=`<option value="${goods[k]['RowID']}">${goods[k]['title']}</option>` 
//     // }
//     // //-------------------------------------------------------------------------------
//     //  //-----------------------------------------------------------------------
//     //  var unit_obj=get_units();
//     // var unit_options=`<option value="0">واحد کالا را انتخاب نمایید</option>`;
//     // for(m in unit_obj){
//     //     unit_options+=`<option value="${unit_obj[m]['RowID']}">${unit_obj[m]['description']}</option>`
//     // }
//     //  //-------------------------------------------------------------------------------
//     //  //-----------------------------------------------------------------------
//     //  var base_inq = get_base_of_inq();
//     //  var base_options=`<option value="0">مبنای خرید را وارد نمایید </option>`
//     //  for(b in base_inq){
//     //     base_options+=`<option value="${base_inq[b]['code']}">${base_inq[b]['description']}</option>` 
//     //  }
//     //  //-------------------------------------------------------------------------------
 
//     // var tbl_head=
//     // `<tr>
//     //     <th style="width:5%">#</th>
//     //     <th style="width:17%">شرح کالا </th>
//     //     <th style="width:7%"> تعداد</th>
//     //     <th style="width:10%">واحد </th>
//     //     <th style="width:13%">مبنای خرید</th>
//     //     <th style="width:10%">شماره درخواست خرید </th>
//     //     <th style="width:30%">توضیحات</th>
//     //     <th style="width:8%">عملیات</th>
//     // </tr>`
//     // if(!th_count){
//     //     $(target_thead).append(tbl_head);
//     // }
//     // var disable="disabled";
//     // var tbl_body=
//     // `<tr>
//     //     <td class="row_number"></td>
//     //     <td class="form-group"> <select onchange="set_table_childs_id(this)" class="inq_good form-control">${options}</select></td>
//     //     <td class="form-group"> <input class="good_quantity form-control" type="text" ></td>
//     //     <td class="form-group"> <select class="inq_good_unit form-control">${unit_options}</select></td>
//     //     <td class="form-group"> <select class="inq_base_inquiry form-control">${base_options}</select></td>
//     //     <td class="form-group"> <input class="inq_number form-control" type="text" id=""></td>
//     //     <td class="form-group"> <textarea class="form-control inq_good_description" style="height:35px"></textarea></td>
//     //     <td class="row justify-content-around">
//     //         <button  onclick="save_good_row(this)" class="good_save_btn btn btn-success"><i  class=" fa fa-save "></i></button>
//     //         <button ${disable}  class="good_edit btn btn-info" onclick="update_good_row(this,0,0)"><i class="fa fa-edit"></i></button>
//     //         <button class="good_delete btn btn-danger" onclick="delete_inq_good(this,0,0)"><i class="fa fa-trash"></i></button>
//     //     </td>
//     // </tr>`
//     // $(target_tbody).append(tbl_body);
//     // set_table_row_number($("#inq_seleted_goods_tbl"));
//     // $(btn_input).prop('disabled',true);
//     // // var select_array=[$(".inq_good_unit"),$(".inq_good"),$(".inq_base_inquiry")]
//     // // select_array.forEach((item)=>{
//     // //     select_data_picker(item,0,0,'100%')
//     // // })
// }

function set_table_childs_id(input){
    var parent_tr=$(input).parents('tr')
    if($(input).val() && $(input).val()>0){
        var custom_id='add_good_row_'+$(input).val()
        if($("#inq_seleted_goods_tbl").find("tr#"+custom_id).length){
            swal_custom('خطا','ردیف کالا تکراری می باشد','w');
            $(input).val(0)
            return false;
        }
        $(parent_tr).attr('id','add_good_row_'+$(input).val())
        $(parent_tr).find('textarea,input,select').each(function(){
            $(this).attr('id',$(this).attr('class').split(' ')[0]+"_"+$(input).val())
        })
       
    }
}

async function save_good_row(RowID=0){
    var inq_id=$("#inq_id_hidden").val();
    var action="save_good_row"
    var good_name=$("#inq_good ").val()
    var good_quantity=$("#good_quantity ").val()
    var good_unit=$("#inq_good_unit").val()
    var inq_base=$("#inq_base_inquiry").val()
    var description=$("#inq_good_description").val()
    var inq_number=$("#inq_number").val();
    var fillabel=[inq_id,good_name,good_quantity,good_unit,inq_base];
    var fa_alias=['شناسه استعلام ',' مشخصات کالا '," تعداد/ مقدار  کالا "," واحد کالا "," مبنای خرید "];
    for(k in fillabel){
        var item=fillabel[k];
        if(item.trim=="" || item==0 || item==-1 || item==""){
            swal_custom('خطا', fa_alias[k] +' الزامی می باشد ' , "w")
            return false;
        }
    }

    if(RowID==0){
        if($("#add_good_row_"+good_name).length){
            swal_custom('خطا','کالا تکراری می باشد','w');
            return false;
        }
    }
    if(RowID>0){

        if($("#add_good_row_"+good_name).length && $("#inq_old_good_id_hidden").val() !=good_name){
            swal_custom('خطا','کالا تکراری می باشد','w');
            return false;
        }
    }

    var params={
        action:action,
        inq_id:inq_id,
        good_name:good_name,
        good_quantity:good_quantity,
        good_unit:good_unit,
        inq_base:inq_base,
        description:description,
        inq_number:inq_number,
        RowID:RowID
    }
    var res= await manageAjaxRequestCustom(params);
    if(res.res=="true"){
        close_modal('edit_create_good_row_modal');
        swal_custom('',res.data[1],'s'); 
        add_good_inq($("#add_good_row"));
        if(!$("inq_tbl thead th.inq_detailes_th").length){
            if(RowID==0){
                var td = add_row_inq_table(res.data[0][0]);
                $("#inq_table tbody").append(td)
            }
            else{
                var td = add_row_inq_table(res.data[0][0],1);
                var old_good_id=$("#inq_old_good_id_hidden").val();
                $("#good_detailes_"+old_good_id).find('td').first().html(td);
                $("#good_detailes_"+old_good_id).attr('id','good_detailes_'+good_name);
            }
        }

        // $(".modal-backdrop").hide();
        // if($("#inq_table tbody tr").length>0){
        //     $("#inq_tbl_main").hide();
        // }
        // return;
    }
    else{
        swal_custom('خطا',res.data,'w'); 
        return;
    }
    
}

function saveSelectedSeller()
{
    var chk=$("#inq_seller_selcect");
    var inq_id=$("#inq_id_hidden").val()
    if($(chk).val()!==-1){
        var selected_seller_rows='';
        var seller_id=$(chk).val()
        var seller_name=$(chk).find('option:selected').text()
        var ostan_id=$("#pri_origin_of_send").val()
        var city_id=$("#city_origin_of_send").val()
        var phone=$("#seller_phon").val()
        if(phone.trim()=="")
        {
            swal_custom('خطا','شماره  تلفن مشتری الزامی می باشد','w');
            return false;
        }
        if(ostan_id==0)
        {
            swal_custom('خطا','استان مبدا استعلام انتخاب نشده است','w');
            return false;  
        }
        if(city_id==0)
        {
            swal_custom('خطا','شهر ستان مبدا استعلام  انتخاب نشده است','w');
            return false;      
        }
        
        // selected_seller_rows=`<tr seller_id="${seller_id}" seller_tafzili="${seller_tafzili}" >
        //                         <td style="width:10%" class="row_number">1</td>
        //                         <td style="width:20%">${seller_tafzili}</td>
        //                         <td style="width:50%" >${seller_name}</td>
        //                         <td style="width:20%">
        //                             <i class="btn btn-info fa fa-edit"></i>
        //                             <i class="btn btn-primary fa fa-eye"></i>
        //                             <i class="btn btn-danger fa fa-trash" onclick="delete_selected_seller_row(${seller_id})"></i>
        //                         </td>
        //                     </tr>`
        
        // if(!$("#selected_seller_table").length){
        //     var selected_table=`
        //     <table border="1" id="selected_seller_table" class="table table-borderd table-striped">
        //     <thead>
        //         <th style="width:10%">ردیف</th>
        //         <th style="width:20%">کد تفضیلی</th>
        //         <th style="width:50%"> نام مشتری </th>
        //         <th style="width:20%">عملیات </th>
        //     </thead>
        //     <tbody>
               
        //     </tbody>`
        //     $("#selected_sellers").append(selected_table)
       // }
        var chk_duplicate_row=$("#selected_seller_table tr[seller_id='"+seller_id+"']").length
        var chk_duplicate_inserted=0;
        if($("#inq_table").length){
            var chk_duplicate_inserted=0;
            chk_duplicate_inserted=$("#inq_table").find('thead th#seller_th_'+seller_id).length
        }
        if(chk_duplicate_row ||chk_duplicate_inserted>0 ){
            swal_custom("",'طرف حساب   تکراری می باشد','w')
            return false;
        }
        $("#selected_seller_table tbody").append(selected_seller_rows);
        
        set_table_row_number($("#selected_seller_table"));
        insert_select_sellers(seller_id,seller_name,ostan_id,city_id,phone);
        // $("#inq_table").remove();
        // refresh_inq_table(inq_id)
    }
    else{
        swal_custom('خطا','نام طرف حساب  انتخاب نشده است','e');
    }
}

function refresh_inq_table(inq_id){
    open_inq_manage('',inq_id,'')
    $(".modal-backdrop").hide();
}
async function get_all_inq_sellers(inq_id)
{
    var action="get_all_inq_sellers"
    var params={inq_id:inq_id,action:action}
    var result= await manageAjaxRequestCustom(params);
    var selected_seller_rows="";
    if(typeof result.data=="array" || typeof result.data=="object")
    {
        var res=result.data;
        if($("#selected_seller_table").length){
            $("#selected_seller_table").remove();
        }
            var selected_table=`
            <table border="1" id="selected_seller_table" class="table table-borderd table-striped">
            <thead>
            <th style="width:10%">ردیف</th>
            <th style="width:20%">کد تفضیلی</th>
            <th style="width:50%"> نام مشتری </th>
            <th style="width:20%">عملیات </th>
            </thead>
            <tbody>

            </tbody>`
            $("#selected_sellers").append(selected_table)
        
        for(k in res)
        {
            selected_seller_rows+=`<tr seller_id="${res[k]['seller_id']}" seller_tafzili="${res[k]['code']}" >
                <td style="width:10%" class="row_number">${parseInt(k)+1}</td>
                <td style="width:20%">${res[k]['code']}</td>
                <td style="width:50%" >${res[k]['Name']}</td>
                <td style="width:20%">
                    <i class="btn btn-info fa fa-edit"></i>
                    <i class="btn btn-primary fa fa-eye"></i>
                    <i class="btn btn-danger fa fa-trash" onclick="delete_seller_column(${res[k]['RowID']},${res[k]['seller_id']},${res[k]['inq_id']})"></i>
                </td>
            </tr>`

        }
        $("#selected_seller_table tbody").html(selected_seller_rows)
    }
}

// function toggleSelectGoods(chk){
//    if($(chk).val()!==-1){
//         var selected_good_rows='';
//         var good_id=$(chk).val()
//         var good_code=$(chk).find('option:selected').attr('good_code')
//         var good_name=$(chk).find('option:selected').text()
//         var inserted_row=0;
        
//         selected_good_rows=`<tr good_id="${good_id}" good_name="${good_name}" ><td class="row_number">1</td><td>${good_code}</td><td>${good_name}</td><td><i class="btn btn-danger fa fa-trash" onclik="delete_selected_good_row(${good_id})"></i></td></tr>`
//         if(!$("#selected_good_table").length){
//             var selected_table=`
//             <table border="1" id="selected_good_table" class="table table-borderd table-striped">
//             <thead>
//                 <th style="width:10%">ردیف</th>
//                 <th style="width:20%">کد کالا</th>
//                 <th style="width:60%">مشخصات  کالا </th>
//                 <th style="width:10%">حذف  </th>
//             </thead>
//             <tbody>
               
//             </tbody>`
//             $("#selected_goods").append(selected_table)
//         }
//         var chk_duplicate_row=$("#selected_good_table tr[good_id='"+good_id+"']").length
//         if($("#inq_table").length){
//            inserted_row= $("#inq_table").find('tr#row_'+good_id).length
//         }
//         if(chk_duplicate_row || inserted_row>0 ){
//             swal_custom("",'ردیف کالا  تکراری می باشد','w')
//             return false;
//         }
//         $("#selected_good_table tbody").append(selected_good_rows);
        
//         set_table_row_number($("#selected_good_table"));
//         $("#inq_seller_selcect").val('-1')

//     }
// }

function get_units(){
    var action = "inq_get_units";
    var params={action:action}
    var res=ajaxHandler(params)
   
    return res 
}
// function get_units(){
//     var action = "inq_get_units";
//     var params={action:action}
//     var res=ajaxHandler(params)
    
//     return res 
// }

function get_all_citeis(privince=0){
    var action = "get_all_citeis";
    var params={action:action,privince:privince}
    var res=ajaxHandler(params)
    return res 
}

function get_base_of_inq(){
    var action = "inq_base_of_inq";
    var params={action:action}
    var res=ajaxHandler(params)
    return res 
}
function insert_select_goods(){
    var selected_goods=[]
    var obj={};
    $("#selected_good_table tbody tr").each(function(){
        obj.good_id=$(this).attr('good_id');
        obj.good_desc=$(this).attr('good_name')
        selected_goods.push(obj);
        obj={}
    })
    var tr_count=$("#inq_table tbody").find('tr').length;
    var seller_array = get_sellers();
    var seller_options=`<option value="0"> انتخاب نمایید </option>`
    for(k in seller_array){
        seller_options+=`<option value="${seller_array[k]['RowID']}">${seller_array[k]['Name']}</option>`
    }
    var unit_obj=get_units();
    var unit_options=`<option value="0">واحد کالا را انتخاب نمایید</option>`;
    for(m in unit_obj){
        unit_options+=`<option value="${unit_obj[m]['RowID']}">${unit_obj[m]['description']}</option>`
    }

    var base_of_inq_obj=get_base_of_inq();

    var base_of_inq_option=`<option value="0">مبنای خرید را انتخاب نمایید</option>`;
    for(m in base_of_inq_obj){
        base_of_inq_option+=`<option value="${base_of_inq_obj[m]['RowID']}">${base_of_inq_obj[m]['description']}</option>`
    }
    var count_th=0;
    if($("#inq_table")){
        count_th=$("#inq_table").find('thead th').length
    }
    var td_add="";
    if(count_th>2){
        $("#inq_table thead tr th").each(function(){
            if($(this).index()>1){
                var seller_id=$(this).attr('seller_id');
                var seller_name = $("#seller_hidden_name_"+seller_id).val()
                td_add+=`<td class="inq_detaile_td" style='min-width:300px;min-height:300px'>
                <div style="min-height:300px;height:auto">
                     <button id="more_seller_data_${seller_id}" onclick="register_inq_detailes(this,'${seller_name}')" class='btn btn-success'>ثبت/ویرایش جزییات</button>
                </div>
            </td>`
            }
        })
    }
    
    for(m in selected_goods){
        var index= parseInt(tr_count)+parseInt(m)
       
        $("#inq_table").append(`<tr id="row_${selected_goods[m]['good_id']}" good_name="${selected_goods[m]['good_desc']}">
                                    <td style="min-width:300px;padding:5px">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <h4  class="text-center">
                                                    ${selected_goods[m]['good_desc']}
                                                </h4>
                                            </div>
                                        </div>
                                        <fieldset class="form-group-custom">
                                            <legend>تعداد </legend>
                                            <input type="text" value="" id="good_quantity_${index}" >
                                        </fieldset>

                                        <fieldset class="form-group-custom">
                                            <legend>واحد</legend>
                                            <select id="good_unit_${index}" > ${unit_options}</select>
                                         </fieldset>
                                        
                                        <fieldset class="form-group-custom">
                                            <legend> مبنای درخواست</legend>
                                            <select id="get_base_of_inq_${index}" > ${base_of_inq_option}</select>
                                         </fieldset>
                                        <fieldset class="form-group-custom">
                                            <legend>شماره درخواست خرید</legend>
                                            <input  autocomplete="off" type="text" id="request_buy_number_${index}" value="">
                                        </fieldset>
                                        
                                        <div class="form-group row">
                                            <div class="col-md-12"><button id="delete_good_row_${selected_goods[m]['good_id']}" onclick="delete_good_inq(this,${$("#inq_id_hidden").val()},${selected_goods[m]['good_id']})" class="btn btn-danger">حذف  کالا </button></div>
                                        </div>
                                    </td>
                                    <td style='min-width:200px'>
                                         <fieldset class="form-group-custom">
                                            <legend>فروشنده</legend>
                                            <select id="last_seller_name_${index}" >${seller_options} </select>
                                         </fieldset>
                                          <fieldset class="form-group-custom">
                                            <legend>(ریال)قیمت واحد</legend>
                                            <input   type="text" id="unit_price_${index}" >
                                         </fieldset>
                                        <fieldset class="form-group-custom">
                                            <legend >ارائه فاکتور با ارزش افزوده</legend>
                                            <div class="text-center">
                                                <label> <input type="radio" name="vat_status_${index}"> بله</label>
                                                <label> <input type="radio" name="vat_status_${index}"> خیر</label>
                                            </div>
                                        </fieldset>
                                        <fieldset class="form-group-custom">
                                            <legend>(ریال)قیمت با / بدون ارزش افزوده</legend>
                                            <input   type="text" id="total_unit_price_${index}" >
                                         </fieldset>
                                        <fieldset class="form-group-custom">
                                            <legend>   (بر اساس روز)مدت باز پرداخت</legend>
                                            <input   type="text" id="term_of_pay_${index}">
                                         </fieldset>
                                        <fieldset class="form-group-custom">
                                            <legend> تاریخ پرداخت </legend>
                                             <input id="pay_date_${index}"  type="text" >
                                         </fieldset>
                                    </td>
                                    ${td_add}
                                </tr>`);
                                select_data_picker("#last_seller_name_"+index)
                                select_data_picker("#good_unit_"+index)
                                select_data_picker("#get_base_of_inq_"+index)
                                set_persian_date($("#pay_date_"+index),-1)
    }
   // $("#inq_table").append(`<tr>${appended_td}</tr>`)
    $("#select_goods_modal").modal('hide')
    $("#add_seller_btn").removeAttr('disabled')
    $("#inq_tbl_main").show();
}

// function delete_good_inq(input,inq_id,good_id){
//    var btn_id= $(input).attr('id')
//    Swal.fire({
//     title: "حذف",
//     text:'ردیف کالا حذف شود ؟',
//     showDenyButton: true,
//     showCancelButton: false,
//     confirmButtonText: "حذف شود",
//     denyButtonText: `حذف نشود`
//   }).then(async (result) => {
//     /* Read more about isConfirmed, isDenied below */
//     if (result.isConfirmed) {
//         var res= await do_delete_inq_good_row(inq_id,good_id);
//         if(res=="true"){
//             $("#"+btn_id).parent().closest('tr').remove();
//         }
        
//     } else if (result.isDenied) {
//      // Swal.fire("Changes are not saved", "", "info");
//     }
//   });
  
// }

async function do_delete_inq_good_row(inq_id,good_id){
    var action="do_delete_inq_good_row";
    var params={action:action,inq_id:inq_id,good_id:good_id}
    var res=manageAjaxRequestCustom(params);
    return res.res

}

function get_citeis(input,index=""){
    var privince = $(input).val()
    var city_array = get_all_citeis(privince);
    var city_option =`<option value="0"> انتخاب شهر</option>`;
    for(k in city_array['data'] ){
       // //console.log(city_array['data'][k]['title'])
        city_option+=`<option value="${city_array['data'][k]['RowID']}">${city_array['data'][k]['title']}</option>`
    }
    $("#city_origin_of_send").html(city_option);
   // select_data_picker($("#city_origin_of_send_"+index),0,1)
}

async function insert_select_sellers(seller_id,seller_name,ostan_id,city_id,phone){
    var params={action:"insert_select_sellers",seller:seller_id,inq_id:$("#inq_id_hidden").val(),ostan_id:ostan_id,city_id:city_id,phone:phone}
    var res= await manageAjaxRequestCustom(params)
    var seller_inq_RowID=res['data']['RowID'];
    var th_count=$("#inq_table thead").find('th').length;
    var appended_td="";
    var appended_tbody_td="";
    var p_objects = get_all_citeis();
    var option_privonce="";
    var province = p_objects['data'];

    for(p in province){
        option_privonce+=`<option value="${province[p].RowID}">${province[p].title}</option>`
    }
    //console.log(selected_sellers.length)
    //for(var i=0;i<selected_sellers.length;i++){
       
       // var index=selected_sellers[i]['seller_id']
      // var index=seller_id;
        //console.log('index:'+index)
        var td_text=`
        <div class="container">
            <div class="custom_row py-2">
                <h6 style="min-height:100%;height:100%" class="text-right ">${seller_name} </h6>
                <button onclick="delete_seller_column(this,${seller_inq_RowID},${seller_id},${$("#inq_id_hidden").val()})" class="col-md-2 btn btn-danger"><i  class="fa fa-trash"></i> </button>
                
            </div>
            <div class="row form-group">
               
            </div>

           
            <input type="hidden" id="seller_hidden_id_${seller_id}" value="${seller_id}">
            <input type="hidden" id="seller_hidden_name_${seller_id}" value="${seller_name}">
            <input type="hidden" id="seller_tbl_row_id_${seller_id}" value="${seller_inq_RowID}">
            
        </div>`
        appended_td=`<th class="inq_detailes_th" style='width:300px' seller_id="${seller_id}" id="seller_th_${seller_id}">${td_text}</th>`
        appended_tbody_td=`<td class="inq_detaile_td" style='min-width:300px;min-height:300px'>
                                <div style="min-height:300px;height:auto">
                                    <button class="btn btn-success more_seller_data" onclick="register_inq_detailes(this,'${seller_name}',${seller_id},${seller_inq_RowID})" class='btn btn-success'>ثبت/ویرایش جزییات</button>
                                    <div class="seller_summary" id="seller_summary_${create_random_code()}"><p class="text-danger">استعلامی صورت نگرفته است</p></div>
                                </div>
                            </td>`
 
        //close_modal('select_sellers_modal')
        if(appended_td && appended_tbody_td){
            $("#inq_table thead tr").append(appended_td);
            $("#inq_table tbody tr.good_detailes").append(appended_tbody_td);
            // select_data_picker($("#pri_origin_of_send_"+index),0,0,'100%')
            // select_data_picker($("#city_origin_of_send_"+index),0,0,'100%')
        }
   // }

    $("#inq_table tbody button.more_seller_data").each(function(){
        var rand= create_random_code();
        $(this).attr('id','more_seller_data_'+rand)
    })
    
   
}
function get_all_goods(){
    var goods_params = {action:'inq_get_all_goods'}
    var goods = ajaxHandler(goods_params);
    return goods;
}

async function delete_seller_column(RowID,seller_id,inq_id){
    var conf=await swal_custom("حذف","ستون مشتری حذف گردد ؟",'c');
    if(conf==1)
    {
        var td_index=$("#seller_th_"+seller_id).index();
    
        var del_result= await do_delete_seller_column(RowID,seller_id,inq_id);
        if(del_result.res=="true"){
            if(del_result.data==1){
                $("#inq_table tr").each(function() {
                  
                    $(this).children("td:eq("+td_index+")").remove();
                    $(this).children("th:eq("+td_index+")").remove();
                    
                });
            }
            if(del_result.data==0){
               $("#inq_table").remove();
            }
           
            swal_custom('حذف','عملیات با موفقیت انجام شد',"s")
           get_all_inq_sellers(inq_id);
        }
    }
    // Swal.fire({
    //     title: "حذف",
    //     text: "ستون مشتری حذف گردد ؟",
    //     showDenyButton: true,
    //     showCancelButton: false,
    //     confirmButtonText: " !!حذف شود",
    //     denyButtonText: `انصراف`
    //   }).then((result) => {
    //     /* Read more about isConfirmed, isDenied below */
    //     if (result.isConfirmed) {
    //         console.log(seller_id);
    //         console.log(inq_id);
    //         var td_index=$(input).parent().closest('th').index();
    //         $("#inq_table tr").each(function() {

    //             $(this).children("td:eq("+td_index+")").remove();
    //             $(this).children("th:eq("+td_index+")").remove();
    //         });
    //     } 
    //   });
}

async function do_delete_seller_column(RowID,seller_id,inq_id){
    var action ="do_delete_seller_column";
    var params={action:action,RowID:RowID,seller_id:seller_id,inq_id:inq_id};
    var result= await manageAjaxRequestCustom(params);
    return result

}

 function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
  }
  
  async function toggle_form(detailes_id=0){
    var good_id=$("#good_id").val()
    var RowID=$("#seller_tbl_id").val();
    var seller_id=$("#seller_id").val();
    var summary_id=$("#summary_id").val()
    var form_html=`<form id="inq_detailes_form" style="display:none">
                        
                        <div class="row form-group">
                            <div class="col-sm-6">
                                <label class="col-sm-6">ارائه فاکتور با ارزش افزوده</label>
                                <div class="col-sm-6">
                                        <label> دارد<input name="is_vat" type="radio" value="1"></label>
                                        <label> ندارد<input name="is_vat" type="radio" value="-1"></label>
                                </div>
                            </div>
                            <div class="col-sm-6" >
                                <label class="col-sm-6">محاسبه کرایه حمل</label>
                                <div class="col-sm-6">
                                        <label> درصدی<input name="rent_percent" type="radio" value="1"></label>
                                        <label> عادی<input name="rent_percent" type="radio" value="-1"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">    
                            <div class="col-sm-6">
                                <label class="col-sm-6"> قیمت واحد </label>
                                <div class="col-sm-6">
                                    <input class="form-control" onkeyup="set_septaror(this)" type="text" id="unit_price">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="col-sm-6">مدت بازپرداخت (روز)</label>
                                <div class="col-sm-6">
                                    <input class="form-control"  type="text" id="pay_term">
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-6">
                                <label class="col-sm-6">اعتبار قیمت</label>
                                <div class="col-sm-6">
                                    <input class="form-control"  type="text" id="price_valid">
                                </div>
                            </div>

                            <div class="col-sm-6" id="rent_div">
                                <label id="rent_lbl" class="col-sm-6">کرایه حمل (درصدی )</label>
                                <div class="col-sm-6">
                                    <input class="form-control"  type="text" id="rent">
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            
                            <div class="col-sm-6">
                                <label class="col-sm-6">قیمت تمام شده نهایی(ریال)</label>
                                <div class="col-sm-6">
                                    <input onkeyup="set_septaror(this)" class="form-control"  type="text" id="ultimate_price">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="col-sm-6"> توضیحات</label>
                                <div class="col-sm-6">
                                    <textarea id="description" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" onclick="save_seller_info(this,'${good_id}','${RowID}','${seller_id}','${summary_id}',${detailes_id})" class="btn btn-primary" >تایید</button>
                            <button type="button" onclick="close_inq_form(this)" class="btn btn-danger" >انصراف</button>
                        </div>
                          
                    </form>`
                $("#inq_detailes_box").html(form_html);
    if(detailes_id>0){
        var res=await get_inq_detailes_info(detailes_id)
        console.log(res);
//         RowID
// inq_good_tbl_id
// inquiry_sellers_tbl_id
// is_vat
// rent_percent
// unit_price
// pay_term
// price_valid
// rent
// ultimate_price
// ultimate_rent
// description
//status

      $("input[name='is_vat'][value='"+res.is_vat+"']").prop('checked',true)
      $("input[name='rent_percent'][value='"+res.is_vat+"']").prop('checked',true)
      
      $("#unit_price").val(res.unit_price)
      $("#pay_term").val(res.pay_term)
      $("#price_valid").val(res.price_valid)
      $("#rent").val(res.ultimate_rent)
      $("#ultimate_price").val(res.ultimate_price)
      $("#description").val(res.description)

    }
    $("#inq_detailes_form").show(1000);
    $("#toggle_form_btn").hide(1000);
  }

  async function get_inq_detailes_info(RowID){
    var action="get_inq_detailes_info";
    var params={action:action,RowID:RowID}
    var res=await manageAjaxRequestCustom(params);
    if(res.res=="true"){
        return res.data;
    }
    else{
        swal_custom('خطا','خطایی رخ داده است ',"w");
        return false;
    }
  }

  function close_inq_form(input){
    $(input).parents("form").remove();
    $("#toggle_form_btn").show(1000);
  }
async function register_inq_detailes(input,seller_name,seller_id,RowID=0){
    
    
    var input_id=$(input).attr('id');
    var first_parent=$(input).parent();
    var parent_id= $("#"+input_id).parents('tr').attr('id');
    var good_name= $("#"+parent_id).attr('good_name');
    var good_id= $("#"+parent_id).attr('good_id');
    var summary_id=$(input).siblings('.seller_summary').attr('id');
    var seller_id=seller_id
    //--------------------------------------------------------
    var p_objects = get_all_citeis();
    var option_privonce="";
    
    var province = p_objects['data'];
 
 
    for(p in province){
        option_privonce+=`<option value="${province[p].RowID}">${province[p].title}</option>`
    }
  //  var data_info = JSON.stringify({seller_id:seller_id,RowID:RowID,good_id:good_id,summary_id:summary_id})
  //-------------------------------------------------------------
    var rc=getRandomColor();
    if(sessionStorage.getItem('first')==1){
       
        var html=await show_summary_suggests(good_id,seller_id,RowID)
        $(first_parent).find("div.seller_summary").html(html)
    }
    else
    {
        if($("#seller_inq_detailes").length){
            $("#seller_inq_detailes").remove();
        }
       
        var bgColorStyle=`style="background:${rc} !important"`
        var modal=`
        <div id="seller_inq_detailes" class="modal custom_modal"  tabindex="-1" role="dialog">

            <div class="modal-dialog" style="max-width:900px" role="document">
                <div class="modal-content">
                <div class=" bg-primary text-light modal-header" ${bgColorStyle}>
                    <h5 class="modal-title">ثبت/ویرایش جزییات  </h5>
                    <button type="button" class="close" onclick="close_modal('seller_inq_detailes')" id="close_modal_reg">
                    <span class="h4 text-danger" aria-hidden="true">X</span>
                    </button>
                </div>
                <h5 class="modal-title row justify-content-between px-6"><span> نام طرف حساب :${seller_name}</span> <span>شرح کالا : ${good_name}</span></h5>
                <div class="modal-body form_custom_bg">
                        <input type="hidden" id="seller_id" value="${seller_id}">
                        <input type="hidden" id="good_id" value="${good_id}">
                        <input type="hidden" id="seller_tbl_id" value="${RowID}">
                        <input type="hidden" id="summary_id" value="${summary_id}">
                    <div class="row">
                        <button class="btn btn-success w-100" id="toggle_form_btn" onclick="toggle_form()"> + </button>
                    </div>
                    <div id="inq_detailes_box">
                    </div>
                </div>
                
                <table border="1" id="inq_seller_detailes" class="table table-borderd table-striped">
            <thead></thead>
            <tbody></tbody>
                
            </table>
                </div>
            </div>
            
        </div>`;
        $("#inq_detailes").append(modal)
        $("#seller_inq_detailes").modal({backdrop:'static',keyboard:false},'show')
        $("input[name='rent_percent'][type='radio']").on('click',function(){
        if($(this).val()==1){
            
                $("#rent_lbl").text('درصد کرایه حمل');
        }
        else{
            $("#rent").prop('disabled',false)
            $("#rent_lbl").text('کرایه حمل (عادی)');
        }
        })
        var rows=await get_inq_seller_detailes(good_id,seller_id,RowID);
    
        await create_inq_seller_detailes_rows(rows);
    }
   
}

function number_spaparate(input){
    var num=$(input).val().replace(',','')
    if(!isNaN(num)){
        var new_val=Number(num).toLocaleString();
        $(input).val(new_val);
    }
}

async function save_seller_info (input,good_id="",RowID="",seller_id="",summary_id="",inq_detailes_id=0){
    var action ="save_seller_info"
    var ostan=$("#pri_origin_of_send").val();
   
    var city=$("#city_origin_of_send").val();
   
    var phone=$("#seller_phone").val();
    
    var is_vat=$("input[name='is_vat'][type='radio']:checked").val();
    var rent_percent=$("input[name='rent_percent'][type='radio']:checked").val();
    var unit_price=$("#unit_price").val();
    var pay_term=$("#pay_term").val();
    var price_valid=$("#price_valid").val();
    var ultimate_rent=0;
    var rent=$("#rent").val();
    var good_id=$("#good_id").val();
    var seller_tbl_id=$("#seller_tbl_id").val();
    var ultimate_price=$("#ultimate_price").val();
    var description=$("#description").val()?$("#description").val():'';
    if(rent_percent==1){
        ultimate_rent=parseInt(rent)*parseInt(unit_price)/100;
    }
    else{
        ultimate_rent=rent
    }
    var fillable=[is_vat,rent_percent,unit_price,pay_term,price_valid,rent,ultimate_price,ultimate_rent]
    var fa_alias=[' وضعیت ارزش افزوده ',' روش محاسبه کرایه حمل ',' قیمت واحد ','  مدت بازپرداخت',' اعتبار قیمت ','  کرایه حمل ',' قیمت نهایی ','مبلغ کرایه حمل ']
    for(k in fillable){
        var elm_data=fillable[k]
        if(typeof(elm_data)=='undefined' || elm_data=="" || elm_data==0 || String(elm_data).trim()==""){
            swal_custom('خطا',fa_alias[k] + " مشخص نشده است ",'w' )
            return false;
        }
    }

    var params={
        action:action,
        is_vat:is_vat,
        rent_percent:rent_percent,
        unit_price:unit_price,
        pay_term:pay_term,
        price_valid:price_valid,
        rent:rent,
        ultimate_price:ultimate_price,
        description:description,
        ultimate_rent:ultimate_rent,
        seller_tbl_id:seller_tbl_id,
        good_id:good_id,
      
        inq_detailes_id:inq_detailes_id
    }
    var res=await manageAjaxRequestCustom(params);
    if(res['res']=='true'){
        swal_custom('ذخیره',res['data'],'s');
        var rows= await get_inq_seller_detailes( good_id,seller_id,RowID);
        await create_inq_seller_detailes_rows(rows);
        if(summary_id){
            var res=await show_summary_suggests(good_id,seller_id,RowID)
            $("#"+summary_id).html(res);
        }
        $("#inq_detailes_form").hide(1000)
        $("#toggle_form_btn").show(1000)
    }
    
    
}

async function get_ostan_city(row_id){
    var action ="get_ostan_city"
    var params={action:action,row_id:row_id}
    var res= await manageAjaxRequestCustom(params)
    return res.data;
}
async function get_inq_seller_detailes(good_id,seller_id,RowID){
    var action="get_inq_seller_detailes";
    var params={action:action,good_id:good_id,seller_id:seller_id,RowID:RowID}
    var res=await manageAjaxRequestCustom(params);
    
    return res;
}

function create_inq_seller_detailes_rows(res){
 
    var obj_length=Object.keys(res.data).length;
   
    if(parseInt(obj_length)==0){
        $("#inq_seller_detailes thead").html('')
        $("#inq_seller_detailes tbody").html('')
        return false;
    }
    var tbody_content="";
    var th_content=`<tr>
        <th> ردیف</th>
        <th>قیمت واحد</th>
        <th>مدت بازپرداخت</th>
        <th>اعتبار قیمت</th>
        <th> قیمت تمام شده نهایی</th>
        <th>کرایه حمل</th>
        <th> توضیحات</th>
        <th> عملیات</th>
    </tr>`;
    
    var res_array=res.data;
    for(k in res_array){
        tbody_content+=`
            <tr>
                <td>${parseInt(k)+1}</td>
                <td>${res_array[k]['unit_price']}</td>
                <td>${res_array[k]['pay_term']}</td>
                <td>${res_array[k]['price_valid']}</td>
                <td>${res_array[k]['ultimate_price']}</td>
                <td>${res_array[k]['ultimate_rent']}</td>
                <td>${res_array[k]['description']}</td>
                <td>
                    <button class="btn btn-info" onclick="toggle_form(${res_array[k]['RowID']})">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-danger" onclick="delete_inq_detailes(${res_array[k]['RowID']})">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }
    $("#inq_seller_detailes thead").html(th_content);
    $("#inq_seller_detailes tbody").html(tbody_content);
}
 
async function delete_inq_detailes(id){
    var conf = await swal_custom("حذف",'ردیف استعلام حذف  گردد ؟','c')
    if(conf==1){
        var good_id = $("#good_id").val();
        var seller_id = $("#seller_id").val();
        var RowID = $("#seller_tbl_id").val();
        var summary_id = $("#summary_id").val();
        var action = "delete_inq_detailes"
        var params = {action:action,id:id}
        var res = await manageAjaxRequestCustom(params);
        if(res.res=="true"){
            swal_custom('','عملیات با موفقیت انجام شد','s');
            var rows= await get_inq_seller_detailes( good_id,seller_id,RowID);
           
            await create_inq_seller_detailes_rows(rows);
            if(summary_id){
                var res=await show_summary_suggests(good_id,seller_id,RowID)
                $("#"+summary_id).html(res);
            }
        }
    }
    else{
        return false;
    }
}

function close_modal(modal_id){
        $("#"+modal_id).modal('hide')
        $(".modal-backdrop").hide();
}
//----------------------------------------------------------------  End of new part --
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



// async function  create_inquiry(input,id=0){
//     var form_title="";
//     var today= $("#inq_create_date_input").val();
//     var inq_group_options=get_inq_groups();
//     var parent=$(input).parents('section#temp_inqs-section');
//     $(parent).html('');
//     var footer= {'type':'div',"class":"footer"}
//     var content= {'type':'div',"class":"content",'id':"content_step_one"}
//     var content_two= {'type':'div',"class":"content",'id':"content_step_two"}
//     var content_three= {'type':'div',"class":"content",'id':"content_step_three"}
//     //var content_four= {'type':'div',"class":"content",'id':"content_step_four"}
//     var return_button={'type':'btn',"class":"btn ", 'id':"inq_btn_return" ,'title': "<i class='fa fa-arrow-turn-right' style='color:red' title='برگشت به صفحه اصلی'></i>","style":"margin-inline:2reme;margin-inline: 2rem;position: absolute;left: -2rem;top: 0;",'onclick':'goto_export_inq_list(this)'}
//     var form_element = {'type':'form',"class":"form", 'id':"form_create_export_inq"}
//     var section_one = {'type':'section',"class":"form-section", 'id':"form_section_inq_one",'style':'max-width:600px;margin:0 auto'}
//     var section_two = {'type':'section',"class":"form-section", 'id':"form_section_inq_two",'style':'width:100%'}
//     var section_three = {'type':'section',"class":"form-section", 'id':"form_section_inq_three",'style':'width:100%'}
//     var section_four = {'type':'section',"class":"form-section", 'id':"form_section_inq_four",'style':'width:100%'}
//     if(parseInt(id)>0){
//         form_title="ویرایش استعلام بها";
//     }
//     else{
//         form_title="ثبت استعلام بها جدید";
//     }
//     var paragraph_array = {'type':'txt_info',"class":"", "text":form_title, 'id':"export_inq_header"}

//     //-------------------------------------input for section 1--------------------------------------------------------------
//     var inq_create_date = {'type':'text',"class":"form-control", "title":"تاریخ ثبت استعلام", 'id':"export_inq_create_date",'style':'width:50%','value':today,'disabled':true}
//     var inq_date = {'type':'text',"class":"form-control", "title":"تاریخ استعلام", 'id':"export_inq_date",'style':'width:50%',"onchange":"set_inq_data(this,'#inq_date_disable_td')"}
//     //var inq_buy_code = {'type':'text',"class":"form-control", "title":" کد خرید", 'id':"export_inq_buy_code",'style':'width:50%',"onchange":"set_inq_data(this,'#inq_buy_code_disable_td')"}
//     var inq_title = {'type':'select',"class":"form-control", "title":"گروه استعلام", 'id':"export_inq_title",'style':'width:50%','options':inq_group_options}
//     if(parseInt(id)>0){
//         inq_date['value']=res.data.inquiry_date;
//        // inq_buy_code['value']=res.data.purchase_code;
//         inq_title['value']=res.data.title;
//     }
//     // var inq_button_1 = {'type':'button',"class":"btn btn-primary", "title":"تایید و ادامه", 'id':"confirm_continue_one",'style':'float:left','onclick':'confirm_continue(this,\'#form_section_inq_two\')'}
//     //-------------------------------------input for section 1-------------------------------------------------------------
//     //-------------------------------------input for section 2------------------------------------------------------------
//     var add_goods = {'type':'button',"class":"btn btn-primary inqury_btn", "title":" افزودن کالا", 'id':"add_good",'style':'width:100%;margin:auto','onclick':'add_inq_row_good(this,0)'}
//     // var confirm_next_step = {'type':'button',"class":"btn btn-success", "title":" تایید و ادامه", 'id':"confirm_next_step",'style':'display:none;float:left;margin-inline:3rem','onclick':'add_inq_row_good(this)'}
//     var inq_table_header_step2=[[" کد استعلام","<span id='inq_uncode_disable'></span>","تاریخ استعلام ","<span id=\"inq_date_disable_td\">"]] ;
//     //-------------------------------------input for section 2-----------------------------------------------------------


//     create_form_element(parent,form_element);
//     create_form_element("#form_create_export_inq",paragraph_array);
//     create_form_element("#form_create_export_inq",return_button);
//     $("#form_create_export_inq").css('position','relative');
//     //-----------------------------------section 1-------------------------------------------------------------------------
//     create_form_element("#form_create_export_inq",section_one);
//     create_form_element("#form_section_inq_one",content);
//     create_form_element("#content_step_one",inq_create_date);
//     create_form_element("#content_step_one",inq_date);
//     create_form_element("#content_step_one",inq_title);
//    // create_form_element("#content_step_one",inq_buy_code);
//     create_form_element("#form_section_inq_one",footer);
//     //-----------------------------------section 1------------------------------------------------------------------------
//     //-----------------------------------section 2------------------------------------------------------------------------
//     create_form_element("#form_create_export_inq",section_two);
//     create_table("#form_section_inq_two",inq_table_header_step2,'width:50%;margin:0 auto',0,0)
//     //var add_goods = {'type':'button',"class":"btn btn-primary", "title":" افزودن کالا", 'id':"add_good",'style':'float:left','onclick':'add_inq_row_good(this)'}

//     create_form_element("#form_section_inq_two",content_two);
//     create_form_element("#content_step_two",add_goods);

//     // create_form_element("#form_section_inq_two",);
//     create_form_element("#form_section_inq_two",footer);

//     //-----------------------------------section 2-------------------------------------------------------------------------

//     //-----------------------------------section 3-------------------------------------------------------------------------
//     create_form_element("#form_create_export_inq",section_three);
//     create_form_element("#form_section_inq_three",content_three)
//     create_form_element("#form_section_inq_three",footer)
//     //-----------------------------------section 4-------------------------------------------------------------------------
//     // create_form_element("#form_create_export_inq",section_four);
//     // create_form_element("#form_section_inq_four",content_four)
//     // create_form_element("#form_section_inq_four",footer)

//     // create_form_element("#form_create_export_inq",section_four);
//     set_steps_form("#form_create_export_inq",3,0,[validate_form_step_one,callback2,callback3],['form_section_inq_one','form_section_inq_two','form_section_inq_three'],active_step=0);
//     $('#export_inq_date').MdPersianDateTimePicker({
//         targetTextSelector: '#export_inq_date',
//         // disableBeforeDate: new Date(),
//         disableAfterDate: new Date(),
//     })
// }
// async  function edit_inquiry(input,id){
//     await get_fixed_inq_data()
//     var action="edit_inquiry";
//     var inq_group_options=get_inq_groups();
//     var res= await manageAjaxRequestCustom({action:action,row_id:id})
//     if(res.res=="false"){
//         custom_alerts(res.data,'e',0,'خطا')
//         return false;
//     }
//     parent_inquiry_id=id;
//     var today=res.data.inq_created_date
//     var parent=$(input).parents('section#temp_inqs-section');
//     $(parent).html('');
//     var footer= {'type':'div',"class":"footer"}
//     var content= {'type':'div',"class":"content",'id':"content_step_one"}
//     var content_two= {'type':'div',"class":"content",'id':"content_step_two"}
//     var content_three= {'type':'div',"class":"content",'id':"content_step_three"}
//     var content_four= {'type':'div',"class":"content",'id':"content_step_four"}
//     var return_button={'type':'btn',"class":"btn ", 'id':"inq_btn_return" ,'title': "<i class='fa fa-arrow-turn-right' style='color:red' title='برگشت به صفحه اصلی'></i>","style":"margin-inline:2reme;margin-inline: 2rem;position: absolute;left: -2rem;top: 0;",'onclick':'goto_export_inq_list(this)'}
//     var form_element = {'type':'form',"class":"form", 'id':"form_create_export_inq"}
//     var section_one = {'type':'section',"class":"form-section", 'id':"form_section_inq_one",'style':'max-width:600px;margin:0 auto'}
//     var section_two = {'type':'section',"class":"form-section", 'id':"form_section_inq_two",'style':'width:100%'}
//     var section_three = {'type':'section',"class":"form-section", 'id':"form_section_inq_three",'style':'width:100%'}
//    // var section_four = {'type':'section',"class":"form-section", 'id':"form_section_inq_four",'style':'width:100%'}
//     if(parseInt(id)>0){
//         form_title="ویرایش استعلام بها";
//     }
//     else{
//         form_title="ثبت استعلام بها جدید";
//     }
//     var paragraph_array = {'type':'txt_info',"class":"", "text":form_title, 'id':"export_inq_header"}
//     //-------------------------------------input for section 1--------------------------------------------------------------
//     var inq_create_date = {'type':'text',"class":"form-control", "title":"تاریخ ثبت استعلام", 'id':"export_inq_create_date",'style':'width:50%','value':today,'disabled':true}
//     var inq_date = {'type':'text',"class":"form-control", "title":"تاریخ استعلام", 'id':"export_inq_date",'style':'width:50%',"onchange":"set_inq_data(this,'#inq_date_disable_td')"}
//   //  var inq_buy_code = {'type':'text',"class":"form-control", "title":" کد خرید", 'id':"export_inq_buy_code",'style':'width:50%',"onchange":"set_inq_data(this,'#inq_buy_code_disable_td')"}
//   var inq_title = {'type':'select',"class":"form-control", "title":"گروه استعلام", 'id':"export_inq_title",'style':'width:50%','options':inq_group_options}
//     var inq_id_hidden = {'type':'hidden', 'id':"inq_id_hidden", 'value':id}
//     if(parseInt(id)>0){
//         inq_date['value']=res.data.inquiry_date;
//     }
//     // var inq_button_1 = {'type':'button',"class":"btn btn-primary", "title":"تایید و ادامه", 'id':"confirm_continue_one",'style':'float:left','onclick':'confirm_continue(this,\'#form_section_inq_two\')'}
//     //-------------------------------------input for section 1-------------------------------------------------------------
//     //-------------------------------------input for section 2------------------------------------------------------------
//     var add_goods = {'type':'button',"class":"btn btn-primary inqury_btn", "title":" افزودن کالا", 'id':"add_good",'style':'width:100%;margin:auto','onclick':'add_inq_row_good(this,'+id+')'}
//     // var confirm_next_step = {'type':'button',"class":"btn btn-success", "title":" تایید و ادامه", 'id':"confirm_next_step",'style':'display:none;float:left;margin-inline:3rem','onclick':'add_inq_row_good(this)'}
//     var inq_table_header_step2=[[" کد استعلام","<span id='inq_uncode_disable'></span>","تاریخ استعلام "]] ;
//     //-------------------------------------input for section 2-----------------------------------------------------------


//     create_form_element(parent,form_element);

//     create_form_element("#form_create_export_inq",paragraph_array);
//     create_form_element("#form_create_export_inq",return_button);
//     $("#form_create_export_inq").css('position','relative');
//     //-----------------------------------section 1-------------------------------------------------------------------------
//     create_form_element("#form_create_export_inq",section_one);
//     create_form_element("#form_create_export_inq",inq_id_hidden);
//     create_form_element("#form_section_inq_one",content);
//     create_form_element("#content_step_one",inq_create_date);
//     create_form_element("#content_step_one",inq_date);
//     create_form_element("#content_step_one",inq_title);
//     $("#export_inq_title").val( res.data.title)
//     //create_form_element("#content_step_one",inq_buy_code);
//     create_form_element("#form_section_inq_one",footer);
//     //-----------------------------------section 2------------------------------------------------------------------------
//     create_form_element("#form_create_export_inq",section_two);
//     create_table("#form_section_inq_two",inq_table_header_step2,'width:50%;margin:0 auto',0,0)
//     //var add_goods = {'type':'button',"class":"btn btn-primary", "title":" افزودن کالا", 'id':"add_good",'style':'float:left','onclick':'add_inq_row_good(this)'}

//     create_form_element("#form_section_inq_two",content_two);
//     create_form_element("#content_step_two",add_goods);

//     // create_form_element("#form_section_inq_two",);
//     create_form_element("#form_section_inq_two",footer);

//     //-----------------------------------section 2-------------------------------------------------------------------------

//     //-----------------------------------section 3-------------------------------------------------------------------------
//     create_form_element("#form_create_export_inq",section_three);
//     create_form_element("#form_section_inq_three",content_three)
//     create_form_element("#form_section_inq_three",footer)

//     set_steps_form("#form_create_export_inq",3,0,[validate_form_step_one,callback2,callback3],['form_section_inq_one','form_section_inq_two','form_section_inq_three'],active_step=0);
//     $('#export_inq_date').MdPersianDateTimePicker({
//         targetTextSelector: '#export_inq_date',
//         disableAfterDate: new Date(),
//     })
   
//     sessionStorage.setItem("inq_id",id)
//     await add_inq_row_good($("#add_good"),id)
//     $("#form_section_inq_two").children('div.footer').show();

// }
// function set_inq_data(input,target,form_index){
//     var data_inq= $(input).val()
//     $(target).text(data_inq);
//     $(target).val(data_inq);
//     if(data_inq==seller_temp_id){
//         $("#temp_export_inq_seller_name_"+form_index).parent().show();
//     }
//     else{
//         $("#temp_export_inq_seller_name_"+form_index).parent().hide();
//     }
    
// }

// async function add_inq_row_good(input,inq_id=0){

//     var goods_params = {action:'inq_get_all_goods'}
//     var units_params = {action:'inq_get_all_units'}
//     var purchase_params = {action:'inq_get_all_base_purchase'}
//     var options_good = '<option value="0">انتخاب کالا</option>';
//     var options_unit = '<option value="0"> انتخاب واحد کالا</option>';
//     var options_purchase = '<option value="0">انتخاب مبنای خرید</option>';

//     var option_array_goods = ajaxHandler(goods_params);
//     for(k in option_array_goods){
//         options_good += `<option value=${option_array_goods[k]['code']}>${option_array_goods[k]['title']}</option>`
//     }

//     var option_array_units = ajaxHandler(units_params);
//     for (k in option_array_units) {
//         options_unit += `<option value=${option_array_units[k]['RowID']}>${option_array_units[k]['description']}</option>`
//     }

//     var option_array_purchase = ajaxHandler(purchase_params);
//     for (k in option_array_purchase) {
//         options_purchase += `<option value=${option_array_purchase[k]['code']}>${option_array_purchase[k]['description']}</option>`
//     }
//     //**********************
//     var count_res=0;
//     var index=$("#form_section_inq_two").find(".form_create_inq_box").length;
//     var res_goods="";
//     if(inq_id>0) {
//         var action = 'inquiry_get_good_detailes';
//         var params = {action: action, inq_id: inq_id}
//         var res_info =await manageAjaxRequestCustom(params);
//         if(res_info['res']=='true'){
//             res_goods=res_info['data'];
//             count_res=res_goods.length
//         }
//     }
//     index_array=[];
//     if(count_res>0)
//     {
//         for (var m = 0; m < count_res; m++) {
//             index=$("#form_section_inq_two").find(".form_create_good_box").length;
//             var box_element = {
//                 'type': 'div',
//                 "class": "row form_create_inq_box",
//                 "style": "width:100%;border:2px solid gray;margin:10px;border-radius:5px;position:relative;min-height:50px",
//                 'id': "form_create_inq_box_" + index
//             };
//             var good_element = {
//                 'type': 'div',
//                 "class": "col-md-3 form_create_good_box",
//                 'id': "form_create_good_box_" + index,
//                 "style":"position:static"};
//             var seller_element = {
//                 'type': 'div',
//                 "class": "col-md-8 form_create_seller_box",
//                 'id': "form_create_seller_box_" + index
//             };
//             var form_element = {
//                 'type': 'form',
//                 "class": "form  form_create_good_row",
//                 "style": "width:80%;",
//                 'id': "form_create_good_row_" + index
//             }
//             var form_element_button = {
//                 'type': 'div',
//                 "class": "col-md-1 add_seller_btn_box",
//                 "style": "width:100%;",
//                 'id': "add_seller_btn_box_" + index,
//                 'style':"border-right:2px solid #dee8e9; margin:1rem 0px"
//             }
//             //var inq_good_code = {'type':'text',"class":"form-control", "title":"کدکالا", 'id':"inq_good_code_"+index,'style':'width:100%','value':"",'disabled':true}
//             var inq_good_name = {
//                 'type': 'text',
//                 "class": "form-control",
//                 "title": " شرح کالا",
//                 'id': "inq_good_name_" + index,
//                 'style': 'width:100%',
//                 'row_class':"col-md-7",
//                 'disable':'disabled',
//                 'readonly':'readonly'
//             }
//             var inq_good_hidden= {
//                 'type': 'hidden',
//                 'id': "inq_good_name_" + index+"_hidden",
//             }
//             var inq_good_unit = {
//                 'type': 'select',
//                 "class": "form-control",
//                 "title": "واحد کالا",
//                 'id': "inq_good_quantity_unit_" + index,
//                 'style': 'width:100%',
//                 'options': options_unit,
//                 'onchange':"update_seller_good_quantity(this,'form_create_good_row_"+index+"')"

//             }
//             var inq_good_quantity = {
//                 'type': 'text',
//                 "class": "form-control",
//                 "min": "1",
//                 "title": "تعداد",
//                 'id': "inq_good_quantity_" + index,
//                 'style': 'width:50%',
//                 'onkeyup': "numberformat(this,1)",
//                 'onchange':"check_seller_quantity(this," + index + ")",

//             }
//             //-------------------------------------------------
//               var inq_good_desc = {
//                 'type': 'textarea',
//                 "class": "form-control",
//                 "title": "توضیحات کالا",
//                 'id': "inq_good_desc_" + index,
//                 'style': 'width:100%',
//             }

//              var inq_good_buy_request_num = {
//                 'type': 'text',
//                 "class": "form-control",
//                 "title": "شماره درخواست",
//                 'id': "inq_good_buy_request_num_" + index,
//                 'style': 'width:50%',

//             }
//              var buy_base_desc = {
//                 'type': 'select',
//                 "class": "form-control",
//                 "title": " مبنای خرید",
//                 'id': "buy_base_desc_" + index,
//                 'style': 'width:100%',
//                 'options':options_purchase

//             }
           
//             //-------------------------------------------------
//             // var inq_btn = {'type':'button',"class":"btn btn-success", "title": "مدیریت فروشندگان/خریدار", 'id':"export_inq_btn",'style':'','parent_box_index':index, "onclick":"add_seller_buyer_inq(this,'#form_create_seller_box_"+index+"')"}
//             var inq_btn_del =
//             {
//                 "type": "button",
//                 "parent":0,
//                 "class": "btn btn-danger",
//                 "title": "حذف",
//                 'id': "export_inq_btn_delete_inq_" + index,
//                 "onclick": 'delete_all_form_inq(this,\'#form_create_inq_box_' + index + '\','+index+')',
//                 "style":"position:absolute;top:3rem;left:1rem"
//             }
//             var add_seller_btn=
//             {
//                 'type': 'button',
//                 "class": "btn ",
//                 "title": "+",
//                 'id': "add_seller_" + index,
//                 'style': 'width: 100%;height: 100%;color: #fff;border: 2px solid #f1eaea;background-color: #dee7ed;',
//                 'parent_box_index': index,
//                 'parent':0,
//                 "onclick": "add_seller_buyer_inq(this,'#form_create_seller_box_" + index + "'," + index + "," + inq_id + ")"
//             };
//             var show_hide_btn =
//             {
//                 'type': 'btn',
//                 "class": "btn",
//                 "title": '<i class="fa fa-angle-double-up"></i>',
//                 'id': "show_hide_" + index,
//                 'style': 'position:absolute;z-index:3;left:0',
//                 'parent': 0,
//                 "onclick": "toggle_display(this,'#form_create_inq_box_" + index + "')"
//             }
//             create_form_element("#content_step_two", box_element);
//             create_form_element("#form_create_inq_box_" + index, show_hide_btn);
//             create_form_element("#form_create_inq_box_" + index, good_element);
//             create_form_element("#form_create_inq_box_"  + index,form_element_button);
//             create_form_element("#form_create_inq_box_" + index, seller_element);
//             create_form_element("#form_create_good_box_" + index, form_element);
//             create_form_element("#form_create_good_row_" + index, inq_good_name);
//             create_form_element("#form_create_good_row_" + index, inq_good_unit);
//             create_form_element("#form_create_good_row_" + index, inq_good_hidden);
//             create_form_element("#form_create_good_row_" + index, inq_good_quantity);
//             //-----------------------------
//             create_form_element("#form_create_good_row_" + index, inq_good_buy_request_num);
//             create_form_element("#form_create_good_row_" + index, buy_base_desc);
//             create_form_element("#form_create_good_row_" + index, inq_good_desc);
//             //-----------------------------
//             create_form_element("#add_seller_btn_box_"+ index, add_seller_btn);
//             create_form_element("#form_create_good_row_" + index, inq_btn_del);
//             select_data_picker("#inq_good_name_" + index)

//             $('<button  type="button" onclick="add_datalist(this)"  style="height: 100%" class="btn btn-info col-md-1"> ...</button>').insertAfter("#inq_good_name_" + index)
//             $(input).attr('disabled', true);
//             if(inq_id>0) {
//                 $("#inq_good_name_" + index).val(res_goods[m]['title']);
//                 $("#inq_good_quantity_unit_" + index).val(res_goods[m]['inq_good_quantity_unit']);
//                 $("#inq_good_quantity_" + index).val(res_goods[m]['inq_good_quantity']);
//                 $("#inq_good_name_" + index+"_hidden").val(res_goods[m]['inq_good_id'])
//                 $("#inq_good_desc_" + index).val(res_goods[m]['inq_good_desc'])
//                 $("#inq_good_buy_request_num_" + index).val(res_goods[m]['inq_good_buy_request_num'])
//                 $("#buy_base_desc_" + index).val(res_goods[m]['buy_base_desc'])
                
//             }

//             if($("#add_seller_" + index) && index>0){
//                 $("#add_seller_" + index).click()
//             }
//         }
//         $("#add_seller_0").click();
//             for(var t=0;t<=index;t++) {
//                 var abb_btn1 = '<div style="width: 100%;margin: auto;padding-block: 10px"> <button id="add_seller_btn_' + t + '"  type="button" class="btn"  style="width: 100%;background: #e9ecef;color:#fff;font-size: 2rem"> + </button> </div>'
//                 $("#form_create_seller_box_" + t).append(abb_btn1)
//                 $("#add_seller_btn_" + t).click(function () {
                   
//                     var parent = $(this).parents(".form_create_inq_box").find(".form_create_good_box").attr("id")
//                     var index_array = parent.split("_")
//                     var parent_box_index = index_array[parseInt(index_array.length) - 1]
//                     $(this).attr('parent_box_index', parent_box_index)
//                     $("#add_seller_" + parent_box_index).click();
//                    // $("#add_seller_btn_" + t).remove();
//                     $(this).remove();

//                 })
//                 $("#content_step_two").find(".footer").show();
//             }

//     }
//     else
//     {
//         var box_element = {
//             'type': 'div',
//             "class": "row form_create_inq_box",
//             "style": "width:100%;border:2px solid gray;margin:10px;border-radius:5px;position:relative;min-height:50px",
//             'id': "form_create_inq_box_" + index
//         };
//         var good_element = {
//             'type': 'div',
//             "class": "col-md-3 form_create_good_box",
//             'id': "form_create_good_box_" + index,
//             'style':'position:static'
//         };
//         var seller_element = {
//             'type': 'div',
//             "class": "col-md-8 form_create_seller_box",
//             'id': "form_create_seller_box_" + index
//         };
//         var form_element = {
//             'type': 'form',
//             "class": "form  form_create_good_row",
//             "style": "width:80%;",
//             'id': "form_create_good_row_" + index
//         }
//         var form_element_button = {
//             'type': 'div',
//             "class": "col-md-1 add_seller_btn_box",
//             "style": "width:100%;",
//             'id': "add_seller_btn_box_" + index,
//             'style':"border-right:2px solid #dee8e9; margin:1rem 0px"
//         }
//         var inq_btn_del =
//             {
//                 "type": "button",
//                 "parent":0,
//                 "class": "btn btn-danger",
//                 "title": "حذف",
//                 'id': "export_inq_btn_delete_inq_" + index,
//                 "onclick": 'delete_all_form_inq(this,\'#form_create_inq_box_' + index + '\','+index+')',
//                 "style":"position:absolute;top:3rem;left:1rem;display:none"
//             }
//         var inq_good_name = {
//             'type': 'text',
//             "class": "form-control",
//             'row_class':'col-md-7',
//             "title": " شرح کالا",
//             'id': "inq_good_name_" + index,
//             'style': 'width:100%',

//             'disable':'disabled',
//             'readonly':'readonly'


//         }
//         var inq_good_hidden= {
//         'type': 'hidden',
//         'id': "inq_good_name_" + index+"_hidden",
//         }

//         var inq_good_unit = {
//             'type': 'select',
//             "class": "form-control",
//             "title": "واحد کالا",
//             'id': "inq_good_quantity_unit_" + index,
//             'style': 'width:100%',
//             'options': options_unit,
//             'onchange':"update_seller_good_quantity(this,'form_create_good_row_"+index+"')",
           
               
//         }
//         var inq_good_quantity = {
//             'type': 'text',
//             "class": "form-control",
//             "min": "1",
//             "title": "تعداد",
//             'id': "inq_good_quantity_" + index,
//             'style': 'width:50%',
//            // 'onchange': "numberformat(this,1);check_seller_quantity(this," + index + ")",
//             'onkeyup': "numberformat(this,1)",
//             'onchange':"check_seller_quantity(this," + index + ")",

//         }
//            //-------------------------------------------------
//             var inq_good_buy_request_num = {
//                 'type': 'text',
//                 "class": "form-control",
//                 "min": "1",
//                 "title": "شماره درخواست",
//                 'id': "inq_good_buy_request_num_" + index,
//                 'style': 'width:50%',

//             }
//               var inq_good_desc = {
//                 'type': 'textarea',
//                 "class": "form-control",
//                 "min": "1",
//                 "title": "توضیحات کالا",
//                 'id': "inq_good_desc_" + index,
//                 'style': 'width:100%',
                

//             }

//              var buy_base_desc = {
//                 'type': 'select',
//                 "class": "form-control",
//                 "title": " مبنای خرید",
//                 'id': "buy_base_desc_" + index,
//                 'style': 'width:100%',
//                 'options':options_purchase
//             }
           
//             //-------------------------------------------------
//         var inq_btn = {
//             'type': 'button_group',
//             "elements": [
//                 {
//                     "type": "button",
//                     "class": "btn btn-danger",
//                     "title": "حذف",
//                     'id': "export_inq_btn_delete_inq_" + index,
//                     "onclick": 'delete_all_form_inq(this,\'#form_create_inq_box_' + index + '\','+index+')',
//                     "style":"position:absolute;top:3rem;left:1rem"
//                 }]
//         }
//         var add_seller_btn= {
//             'type': 'button',
//             "class": "btn ",
//             "title": "+",
//             'id': "add_seller_" + index,
//             'style': 'width: 100%;height: 100%;color: #fff;border: 2px solid #f1eaea;background-color: #dee7ed;',
//             'parent_box_index': index,
//             'parent':0,
//             "onclick": "add_seller_buyer_inq(this,'#form_create_seller_box_" + index + "'," + index + "," + inq_id + ")"
//         };
//         var show_hide_btn = {
//             'type': 'btn',
//             "class": "btn",
//             "title": '<i class="fa fa-angle-double-up"></i>',
//             'id': "show_hide_" + index,
//             'style': 'position:absolute;z-index:3;left:0',
//             'parent': 0,
//             "onclick": "toggle_display(this,'#form_create_inq_box_" + index + "')"
//         }


//         create_form_element("#content_step_two", box_element);
//         create_form_element("#form_create_inq_box_" + index, show_hide_btn);

//         create_form_element("#form_create_inq_box_" + index, good_element);
//         //create_form_element("#form_create_inq_box_" + index, good_element);
//         create_form_element("#form_create_inq_box_"  + index,form_element_button);
//         create_form_element("#form_create_inq_box_" + index, seller_element);
//         create_form_element("#form_create_good_box_" + index, form_element);
//         create_form_element("#form_create_good_row_"+index,inq_good_hidden);
//         create_form_element("#form_create_good_row_" + index, inq_good_name);
//         create_form_element("#form_create_good_row_" + index, inq_good_unit);
//         create_form_element("#form_create_good_row_" + index, inq_good_quantity);
//           //-----------------------------
//             create_form_element("#form_create_good_row_" + index, inq_good_buy_request_num);
//             create_form_element("#form_create_good_row_" + index, buy_base_desc);
//             create_form_element("#form_create_good_row_" + index, inq_good_desc);
//             //-----------------------------
//         create_form_element("#add_seller_btn_box_"+ index, add_seller_btn);
//         create_form_element("#form_create_good_row_" + index, inq_btn_del);
//        // select_data_picker("#inq_good_name_" + index)
//         $('<button  type="button" onclick="add_datalist(this)"  style="height: 100%" class="btn btn-info col-md-1"> ...</button>').insertAfter("#inq_good_name_" + index)
//         $("#add_good").hide();
//     }
//     if(res_goods.res=="false"){
//           $("#form_create_export_inq").find("button[step='2']").hide();
//     }
//     else{
//         $("#form_create_export_inq").find("button[step='2']").show();
//     }
//     sessionStorage.removeItem('inq_id')

// }

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
// async function add_datalist(input){

//     $(input).parent().css('position','relative');
//     var id=$(input).parent().children('input').attr('id');
//     var list_length=$(input).parent().children("#"+id+"_list").length;
//     var good_name=$(input).val();
//     if(list_length==0)
//     {
//         $(input).parent().append(`<div style="position: absolute;background: #fff;box-shadow: 2px 3px 4px #000;padding:10px border:1px solid gray;left:0;top: 2rem;width: 80%;z-index: 10;display: none" id="${id}_list"> 
//         </div>`);
//     }
//     var final_html =
//         `
//         <div class="form-group p-2"><input style="background: #FFFFE0"  target="${id}_list" id="search_bar" onkeyup="search_goods(this,'${id}')" class="form-control" placeholder="search.."></div>
//         <hr>
//         <div class="final_good_box" style="max-height: 300px;overflow-y: scroll;padding: 10px">
            
       
//             <table id="table_${id}" class="table table-bordered ">`;
//     var action="inq_get_all_goods"
//     var params={action:action,good_name:good_name}
//     var res=await manageAjaxRequestCustom(params)
//     if(res.data.length>0) {
//         var html_td = "";
//         for (k in res.data) {
//             html_td += "<tr><td target='" + id + "' good_id='" + res.data[k]['code'] + "' onclick='set_good_name(this)' style='cursor: pointer'>" + res.data[k]['title'] + "</td></td></tr>"
//         }
//         $("#" + id + "_list").html('');
//         final_html+= `<tbody>${html_td}</tbody>`
//     }
//     var insert_box=
//         `</table>
//             </div >
//              <hr>
//             <div class="input-group mb-3" style='padding:10px'>
           
//               <input type="text" style="background: #FFFFE0" id="${id}_add" class="form-control" placeholder="نام محصول را برای  افزودن وارد نمایید" aria-label="Recipient's username" aria-describedby="basic-addon2">
//               <div style="" class="input-group-append ">
             
//                 <button parent_id="${id}" class="btn btn-success" onclick="inq_create_new_goods(this,'${id+'_add'}','${id}')" type="button">+</button>
//               </div>
//           </div>`
//     $("#"+id+"_list").html(final_html+insert_box);
//     if( $("#"+id+"_list").is(":visible")){
//         $("#"+id+"_list").hide();
//     }
//     else{
//         $("#"+id+"_list").show();
//     }
// }

// async function inq_create_new_goods(input,text_input_id,id){
//     var action="inq_create_new_goods"
//     var good_name=$("#"+text_input_id).val();
//     var params={action:action,good_name:good_name};
//     var parent=$(input).attr('parent_id');
//     var res=await manageAjaxRequestCustom(params)
//     var html_td="";
//     if(res.res=='true'){
//         for(k in res.data){
//             html_td += "<tr><td target='" + id + "' good_id='" + res.data[k]['code'] + "' onclick='set_good_name(this)' style='cursor: pointer'>" + res.data[k]['title'] + "</td></td></tr>";
//         }
//         $("#table_"+parent).html(html_td);
//         $("#"+parent+"_add").val('');
//     }
//     else{
//         custom_alerts(res.data,"e",0,'خطا')
//     }
// }

// async function search_goods(input,id){
//     var target=$(input).attr('target');
//     var id=$("#"+id).attr('id');
//     var action="inq_get_all_goods"
//     var good_name=$(input).val()
//     var params={action:action,good_name:good_name}
//     var res=await manageAjaxRequestCustom(params)
//     var all_rows="";
//     for(k in res.data){
//         all_rows+=`<tr> <td target='${id}'  good_id="${res.data[k]['code']}" onclick='set_good_name(this)' style='cursor: pointer'>${res.data[k]['title']}</td></tr>`
//     }
//     $("#"+target).children().find('table').html(all_rows);
// }

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

// async function create_inquiry_pay_comment(inq_id){
//     var action="create_inquiry_pay_comment";
//     var params={action:action,inq_id:inq_id};
//     var res=await manageAjaxRequestCustom(params);
//     if(!$("#inq_pay_comment_modal").length){
//         $('body').append(`<div class="modal" id="inq_pay_comment_modal" tabindex="-1" role="dialog" style="background:rgba(128,128,128,0.7)">
//         <div class="modal-dialog" role="document" style="max-width:700px">
//         <div class="modal-content">
//             <div class="modal-header bg-primary">
//             <h5 class="modal-title"></h5>
//             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
//                 <span class="text-danger" style="font-size:2rem" aria-hidden="true">X</span>
//             </button>
//             </div>
//             <div class="modal-body">
//             ${res['data']}
//             </div>
//             <div class="modal-footer">
//                 <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
//             </div>
//         </div>
//         </div>
//         </div>`);
//     }
//     else{
//         $("#inq_pay_comment_modal").find('.modal-body').html(res['data']);

//     }
//     $("#inq_pay_comment_modal").modal({'keyboard':false,'backdrop':'static'},'show');

// }
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
////console.log(checked)
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
    //console.log(chartArgs[0])
    //console.log(typeof chartArgs)
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
            //console.log(currentIndex.label);
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
/*
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
*/
async function add_pay_method_elements(input,form_index,array_info=[]){
    var parent=$(input).parents('fieldset');  
    var btn_id=$(input).attr('id');
    //console.log('array_info');
    //console.log(array_info);
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
    //console.log(res);
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

/*
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
    //console.log('sum_pay_methods:'+sum_pay_methods);
    //console.log('قیمت تمام شده:'+(parseInt(inq_good_buy_request_num*export_unit_price)+parseInt(vat_value)));
    //console.log('تعداد تمام شده:'+(inq_good_buy_request_num));//*export_unit_price)+parseInt(vat_value));
    //console.log('vahed تمام شده:'+(export_unit_price))
    //console.log('vat تمام شده:'+parseInt(vat_value));
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
*/
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
                    //console.log(form_length)
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

async function send_inq(row_id,tab_status) {
    var action = "get_all_users_access_inq";//گرفتن  لیست کاربران  دارای دسترسی ه
    var res = await manageAjaxRequestCustom({action: action,row_id:row_id})
    var comment_html="";
   
    var users = res.data.users;
    var goods=res.data.goods;
    var grid_html=res.data.grid_html;
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
                  <div class="modal-header bg-info">
                    <h6 class="modal-title" id="exampleModalLongTitle">ارجاع استعلام</h6>
                    <button type="button" class="close"  data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true" style="color:red;font-size: 2rem">&times;</span>
                    </button>
                  </div>
                  <div id="send_form_body" >
                        <div class="modal-body send_body">
                            <form>
                                 <!--<div class="form-group row">
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
                                </div>-->
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
    $("#close_inquiry_modal").on('click',function(){
        $("#send_modal_box").modal('hide')
        $("#import_inqs_detailes").modal('hide')
        $(".modal-backdrop").hide();
        $(".modal-backdrop").css('display','none !important');
       
    })
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
    //console.log('res');
    //console.log(res);
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
    //console.log(res)
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
