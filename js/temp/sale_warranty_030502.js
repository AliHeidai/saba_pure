
function showWarrantyManageList (page) {
    if(typeof page == "undefined"){
        page = 1;
    }
    var Name = $("#warrantyManageFNameSearch").val();
    var Code = $("#warrantyManageFCodeSearch").val();
    var SDate = $("#warrantyManageSDateSearch").val();
    var EDate = $("#warrantyManageEDateSearch").val();
    var status = $("#warrantyManageStatusSearch").val();
    var type = $("#warrantyManageTypeSearch").val();
    var action = "showWarrantyManageList";
    var param = {action:action,Name:Name,Code:Code,SDate:SDate,EDate:EDate,status:status,type:type,page:page};
    var res = manageAjaxRequest(param);
    if(res != false){
        $("#warrantyManageBody").html('');
        $("#warrantyManageBody").html(res);
    }
}

function show_attachments(wid){
    var action = "downloadWarrantyFile";
    var param = {action:action,cid:wid};
    var res = manageAjaxRequest(param);
    if(!$("#warranty_alarm_files_modal").length){
        var modal=`
            <div id="warranty_alarm_files_modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">فایل های پیوست شده </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="text-danger" aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body">
                    ${res}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
            </div>
            </div>
        </div>
        </div>`
        $('body').append(modal);
    }
    $("#warranty_alarm_files_modal .modal-body").html('');
    $("#warranty_alarm_files_modal .modal-body").html(res);
    $("#warranty_alarm_files_modal").modal({'backdrop':'static','keyboard':false},'show')

}

async function custom_confirm(title,msg){
    var flag=false;
    await Swal.fire({
        title: title,
        text: msg,
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "بله تایید شود",
        denyButtonText: `انصراف `
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            flag= true
        } else if (result.isDenied) {
            flag= false
        }
      });
      return flag;
}
async function final_confirm_sale_warranty(wid){
    var conf=await custom_confirm('تایید','در صورت تایید نهایی سند قادر به ویرایش آن نخواهید بود  \n\n ادامه می دهید ؟')
    if(conf){

    
    var action="final_confirm_sale_warranty";
    var params={action:action,wid:wid};
    var res=manageAjaxRequest(params);
    if(res){
        if(parseInt(res[0])>0){
            $("#pending_sale_warranty_modal .modal-body").html('');
            $("#pending_sale_warranty_modal .modal-body").html(res);
        }
        else{
            $("#pending_sale_warranty_modal .modal-body").html('');
            $("#pending_sale_warranty_modal .modal-body").html(res);
           $("#pending_sale_warranty_modal").modal('hide');
        }
    }
}
else{
    return false;
}
}
function createWarranty() {
    // $("#warrantyManageHiddenCid").val('');
    // $("#WarrantyManageFType").val(0);
    // $("#WarrantyManageFName").val('');
    // $("#WarrantyManageFCode").val('');
    // $("#WarrantyManageSDate").val('');
    // $("#WarrantyManageEDate").val('');
    // $("#WarrantyManageDescription").val('');
   // $("#WarrantyManageAccessID").selectpicker('deselectAll');
   $("#warrantyManageModal").find('form.modal-form-m').find('input,textarea,select').each(function(){
        $(this).val('');
   })

    $('#warrantyManageModal').modal('show');
    select_data_picker("#warrantyManageAccount")
    select_data_picker("#warrantyManageAccessID")
    $('#warrantyManageSDate').MdPersianDateTimePicker({
        targetTextSelector: '#warrantyManageSDate',
       
       })
       $('#warrantyManageEDate').MdPersianDateTimePicker({
        targetTextSelector: '#warrantyManageEDate',
        
       })

    //$WarrantyManageAccount
}

function editWarranty () {
    var ch = $('#WarrantyManageBody-table').find('input');
    var cid = new Array();
    for(var c=0;c<ch.length;c++){
        if(ch[c].checked){
            cid[cid.length] = ch[c].attributes.rid.value;
        }
    }
    if(cid.length > 1){
        notice1Sec("فقط یک فایل باید انتخاب شده باشد !","red");
        return false;
    }
    if(cid.length==0){
        notice1Sec("هیچ فایلی انتخاب نشده است !","red");
        return false;
    }
    cid = cid[0];
    var res =manageAjaxRequest({action:'get_warranty_info',cid:cid});
    if(res != false){
        $('#warrantyManageModal').modal('show');
        $("#warrantyManageHiddenCid").val(res['cid']);
        $("#warrantyManageTitle").val(res['warranty_title']);
        $("#warrantyManageType").val(res['warranty_type']);
        $("#warrantyManageAccType").val(res['warranty_type']);
        $("#warrantyManageAccount").val(res['account_id']);
        $("#warrantyDocumentOwnerOtherInfo").val(res['doc_other_info']);
        $("#warrantyDocumentOwner").val(res['doc_owner_name']);
        $("#warrantyDocumentOwnerNatID").val(res['doc_owner_nat_id']);
        $("#warrantyDocumentOwnerCost").val(res['warranty_cost']);
        $("#warrantyManageSDate").val(res['start_date']);
        $("#warrantyManageEDate").val(res['end_date']);
        $("#warrantyManageDescription").val(res['desc']);
        $("#warrantyManageAccessID").val(res['access_id'].split(','));
        select_data_picker("#warrantyManageAccount")
        select_data_picker("#warrantyManageAccessID")
        $('#warrantyManageSDate').MdPersianDateTimePicker({
            targetTextSelector: '#warrantyManageSDate',
           
        })
        $('#warrantyManageEDate').MdPersianDateTimePicker({
            targetTextSelector: '#warrantyManageEDate',
            
        })
        
    }
}

function display_sale_warranty(w_id){
    var action="display_sale_warranty";
    var params={action:action,w_id:w_id}
    var res=manageAjaxRequest(params);
    if(res){
        if(!$("#display_sale_warranty_modal").length){
            var modal=`
            <div class="modal fade" id="display_sale_warranty_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-danger"aria-hidden="true">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    ${res}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
                </div>
            </div>
            </div>`
            $("body").append(modal);
        }
        else
        {
            $("#display_sale_warranty_modal").find('.modal-body').html('');
            $("#display_sale_warranty_modal").find('.modal-body').html(res);
        }
        $("#display_sale_warranty_modal").modal({"keyboard":false,'backdrop':'static'},'show')
    }
}
function doEditCreateWarranty() {
    var w_type= $("#warrantyManageType").val();
    var w_title= $("#warrantyManageTitle").val();
    var w_id = $("#warrantyManageHiddenCid").val();
    var w_acc_type = $("#warrantyManageAccType").val();
    var w_doc_owner=$("#warrantyDocumentOwner").val();
    var w_doc_owner_nat_id=$("#warrantyDocumentOwnerNatID").val();
    var w_account_id=$("#warrantyManageAccount").val();
    var w_doc_info=$("#warrantyDocumentOwnerOtherInfo").val()
    var w_access_id=$("#warrantyManageAccessID").val();
    var w_start_date = $("#warrantyManageSDate").val();
    var w_end_date = $("#warrantyManageEDate").val();
    var desc = $("#warrantyManageDescription").val();
    var warranty_cost=numberformat2($("#warrantyDocumentOwnerCost").val())
    var accID = w_access_id.join(",");

    var var_array=[{v_name:w_title,v_msg:"عنوان مدرک ",'elm_id':"warrantyManageTitle"},{v_name:w_type,v_msg:"نوع تضمین",'elm_id':"warrantyManageType"},{v_name:w_acc_type,v_msg:"نوع طرف حساب",'elm_id':'warrantyManageAccType'},{v_name:w_doc_owner,v_msg:" صاحب سند ",'elm_id':'warrantyDocumentOwner'},{v_name:w_doc_owner_nat_id,v_msg:"شناسه/کد ملی ",'elm_id':'warrantyDocumentOwnerNatID'},{v_name:w_account_id,v_msg:"طرف حساب  ",'elm_id':'warrantyManageAccount'},
    {v_name:w_doc_info,v_msg:"اطلاعات سند  ",'elm_id':"warrantyDocumentOwnerOtherInfo"},{v_name:w_access_id,v_msg:"کاربران مجاز به دانلود",'elm_id':"warrantyManageAccessID"},{v_name:w_start_date,v_msg:"تاریخ شروع",'elm_id':"warrantyManageSDate"},{v_name:w_end_date,v_msg:" تاریخ پایان",'elm_id':"warrantyManageEDate"},{v_name:warranty_cost,v_msg:" مبلغ تضمین ",'elm_id':"warrantyDocumentOwnerCost"}];
 
    for(k in var_array){
       
        if(var_array[k]['v_name']==0 || var_array[k]['v_name']==null ){
            Swal.fire({
                icon: "warning",
                title: "خطا",
                text: var_array[k]['v_msg'] +  ' مشخص نشده است  '
                
            }).then((result)=>{
                $("#"+var_array[k]['elm_id']).focus();
            
            });
            return false;
        }
    }
    w_id?w_id:0;
    var action = "doEditCreateWarranty";
   
    var param = {action:action,w_title:w_title,w_id:w_id,w_acc_type:w_acc_type,w_doc_owner:w_doc_owner,w_doc_owner_nat_id:w_doc_owner_nat_id,w_doc_info:w_doc_info,w_access_id:accID,w_start_date:w_start_date,
                    w_end_date:w_end_date,desc:desc,w_account_id:w_account_id[0],warranty_cost:warranty_cost};
    var res = manageAjaxRequest(param);
    if (res != false) {
        notice1Sec(res, "green");
        $('#warrantyManageModal').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        showWarrantyManageList();
    }
}

function deleteWarranty() {
    var ch = $('#WarrantyManageBody-table').find('input');
    var cid = new Array();
    $("#WarrantyManage_deleteComment").val('');
    for(var c=0;c<ch.length;c++){
        if(ch[c].checked){
            cid[cid.length] = ch[c].attributes.rid.value;
        }
    }
    if(cid.length > 1){
        notice1Sec("فقط یک مورد باید انتخاب شده باشد !","red");
        return false;
    }
    if(cid.length==0){
        notice1Sec("هیچ موردی انتخاب نشده است !","red");
        return false;
    }
    cid = cid[0];
    $("#WarrantyManage_deleteIdHidden").val(cid);
    $('#manageDeleteWarrantyModal').modal('show');
}

function endWarranty() {
    var ch = $('#WarrantyManageBody-table').find('input');
    var cid = new Array();
    $("#WarrantyManage_endComment").val('');
    $("#WarrantyManage_endDate").val('');
    $("#upload_all_needed_files").prop('checked',false)
    for(var c=0;c<ch.length;c++){
        if(ch[c].checked){
            cid[cid.length] = ch[c].attributes.rid.value;
        }
    }
    if(cid.length > 1){
        notice1Sec("فقط یک مورد باید انتخاب شده باشد !","red");
        return false;
    }
    if(cid.length==0){
        notice1Sec("هیچ موردی انتخاب نشده است !","red");
        return false;
    }
    cid = cid[0];
    $("#WarrantyManage_endIdHidden").val(cid);
    $('#manageEndWarrantyModal').modal('show');
    get_end_date_warranty();
    $("#upload_all_needed_files").parent('.col-sm-6').addClass('col-sm-2').removeClass('col-sm-6')
    $("#upload_all_needed_files").parent().siblings('label').removeClass('col-sm-6').addClass('col-sm-10').css('color','red')
    
    // $('#WarrantyManage_endDate').MdPersianDateTimePicker({
    //     targetTextSelector: '#WarrantyManage_endDate',
       
    // })
}

function doEndWarranty(){
    var cid = $("#WarrantyManage_endIdHidden").val();
    var comment = $("#WarrantyManage_endComment").val();
    var action = "doEndWarranty";
    var param = {action:action,cid:cid,comment:comment};
    if(comment==null || comment==""){
        Swal.fire({
            icon: "warning",
            title: "خطا",
            text:'توضیحات را وارد نمایید' 
            
        })
        $('#manageEndWarrantyModal').modal('show');
        return false;
    }

    if(!$("#upload_all_needed_files").is(":checked")){
        Swal.fire({
            icon: "warning",
            title: "خطا",
            text:'درصورتی که مستندات را دریافت و آپلود کرده تیک گزینه مورد نظر را بزنید در غیر اینصورت خارج شوید' 
            
        })
        return false;
    }

    Swal.fire({
        title: "؟ آیا از بابت عودت تضمین و اتمام تعهد مطمئن می باشید",
        showDenyButton: true,
        showCancelButton:false,
        confirmButtonText: "بله انجام شود ",
        denyButtonText: `انصراف`
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var res = manageAjaxRequest(param);
            if(res != false){
                notice1Sec(res,"green");
                $('#manageEndWarrantyModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                //showCircularsManageList();
                showWarrantyManageList();
                //$('#manageDeleteWarrantyModal').modal('hide');
            }
            else{
                Swal.fire({
                    icon: "warning",
                    title: "خطا",
                    text:'تغییری اعمال نشد  ' 
                    
                })
                $('#manageEndWarrantyModal').modal('hide');
                return false;
            }
          
        } else if (result.isDenied) {
            $('#manageEndWarrantyModal').modal('hide');
        }
      });
    
}

function get_end_date_warranty (){
    var action ="get_end_date_warranty"
    var cid = $("#WarrantyManage_endIdHidden").val();
    var param={action:action,cid:cid}
    var res = manageAjaxRequest(param);
    if(res){
       $("#WarrantyManage_endDate").val(res);
    }
    $("#WarrantyManage_endDate").attr('disabled',true)
}

function dodeleteWarranty() {
    var cid = $("#WarrantyManage_deleteIdHidden").val();
    var comment = $("#WarrantyManage_deleteComment").val();
    var action = "dodeleteWarranty";
    var param = {action:action,cid:cid,comment:comment};
    if(comment==null){
        Swal.fire({
            icon: "warning",
            title: "خطا",
            text:'علت  ابطال سند را وارد نمایید' 
            
        })
        return false;
    }
    Swal.fire({
        title: "تضمین حذف شود ؟",
        showDenyButton: true,
        showCancelButton:false,
        confirmButtonText: "حذف شود",
        denyButtonText: `انصراف`
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var res = manageAjaxRequest(param);
            if(res != false){
                notice1Sec(res,"green");
                $('#manageDeleteWarrantyModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                //showCircularsManageList();
                showWarrantyManageList();
                //$('#manageDeleteWarrantyModal').modal('hide');
            }
            else{
                Swal.fire({
                    icon: "warning",
                    title: "خطا",
                    text:'تغییری اعمال نشد  ' 
                    
                })
                $('#manageDeleteWarrantyModal').modal('hide');
                return false;
            }
          
        } else if (result.isDenied) {
            $('#manageDeleteWarrantyModal').modal('hide');
        }
      });
    
}

function attachFileWarranty() {
    var ch = $('#WarrantyManageBody-table').find('input');
    var cid = new Array();
    for(var c=0;c<ch.length;c++){
        if(ch[c].checked){
            cid[cid.length] = ch[c].attributes.rid.value;
        }
    }
    if(cid.length > 1){
        notice1Sec("فقط یک فایل باید انتخاب شده باشد !","red");
        return false;
    }
    if(cid.length==0){
        notice1Sec("هیچ فایلی انتخاب نشده است !","red");
        return false;
    }
    cid = cid[0];
    
    $("#warrantyManageAttachmentID").val(cid);
    $("#warrantyManageAttachmentName").val('');
    $("#warrantyManageAttachment").val('');
    var res = getAttachedWarrantyFile(cid);
    $("#warrantyAttachmentFile-body").html('');
    $("#warrantyAttachmentFile-body").html(res);
    $('#warrantyAttachmentFileModal').modal('show');
}

function getAttachedWarrantyFile(cid){
    var action="getAttachedWarrantyFile";
    var params={action:action,cid:cid}
    var res=manageAjaxRequest(params);
    if(res){
        return res;
    }
    notice1Sec("  خطایی رخ داده است!","yellow");
    return false;
}

function doAttachFileToWarranty() {
    var cid = $('#warrantyManageAttachmentID').val();
    var info = $('#warrantyManageAttachmentName').val();
    var formData = new FormData();
    if($('#warrantyManageAttachment').val() != '' || $('#warrantyManageAttachment')[0].files.length != 0){
        var fileSelect = document.getElementById('warrantyManageAttachment');
        var files = fileSelect.files;
        if(!window.File && window.FileReader && window.FileList && window.Blob){ //if browser doesn't supports File API
            notice1Sec("Your browser does not support new File API! Please upgrade.","yellow");
            return false;
        }else {
            var total_selected_files = files.length;
            for (var x = 0; x < total_selected_files; x++) {
                formData.append("files[]",files[x]);
            }
        }
    }else {
        notice1Sec("هیچ فایلی انتخاب نشده است !","yellow");
        return false;
    }
    if(!parseInt((info.trim()).length)){
        notice1Sec("نام فایل مشخص نشده است !","yellow");
        return false;
    }
    formData.append("action","doAttachFileToWarranty");
    formData.append("cid",cid);
    formData.append("info",info);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/managemantproccess.php',true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var res = JSON.parse(xhr.responseText);
            if(res['res'] == "false"){
                notice1Sec(res['data'],'yellow');
            }else{
                notice1Sec('بارگذاری انجام شد.', "green");
                var rst = getAttachedWarrantyFile(cid);
                $("#warrantyAttachmentFile-body").html('');
                $("#warrantyAttachmentFile-body").html(rst);
                $("#warrantyManageAttachmentName").val('');
                $("#warrantyManageAttachment").val('');
                //showCircularsManageList();

                showWarrantyManageList();
            }
        }else{
            notice1Sec("خطایی رخ داده است !",'yellow');
        }
    };
    xhr.send(formData);
}

function deleteAttachWarrantyFile(fid){
    var cid = $('#warrantyManageAttachmentID').val();
    var action = "deleteAttachWarrantyFile";
    var param = {action:action,fid:fid};
    var res = manageAjaxRequest(param);
    if(res != false){
        notice1Sec(res, "green");
        var rst = getAttachedWarrantyFile(cid);
        $("#warrantyAttachmentFile-body").html('');
        $("#warrantyAttachmentFile-body").html(rst);
        showWarrantyManageList();
    }
}

function downloadWarrantyFile(cid) {
    var action = "downloadWarrantyFile";
    var param = {action:action,cid:cid};
    var res = manageAjaxRequest(param);
    if(res != false){
        $("#showWarrantyAttachmentFile-body").html('');
        $("#showWarrantyAttachmentFile-body").html(res);
        $('#showWarrantyAttachmentFileModal').modal('show');
    }
}

     