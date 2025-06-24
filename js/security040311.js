function securityAccessManage() {
  var action = "securityAccessManage";
  var param = { action: action };
  var res = manageAjaxRequest(param);
  if (res != false) {
    $("#securityAccessManageID").html("");
    $("#securityAccessManageID").html(res[0]);
    console.log("res", res);
    if (jQuery.inArray(1, res[1]) !== -1) {
      // var rsDate = set_persian_date_new('#RecordEventsManageSDateSearch');
      var rsDate = set_persian_date_new("#RecordEventsManageSDateSearch");
      var rdDate = set_persian_date_new("#RecordEventsManageEDateSearch");
      // var rdDate = set_persian_date_new('#RecordEventsManageEDateSearch');

      $(function () {
        $("#recordEventsManageTime-col").datetimepicker({
          pickDate: false,
        });
      });
      $("#recordEventsManageTime-col").addClass("input-append");
      showRecordEventsList();
    }
    if (jQuery.inArray(2, res[1]) !== -1) {
      var rscDate = set_persian_date_new("#agencyManageSDateSearch");
      var rdcDate = set_persian_date_new("#agencyManageEDateSearch");
      $("#agencyManageCreateDate").MdPersianDateTimePicker({
        targetTextSelector: "#agencyManageCreateDate",
      });
      $("#finalTickAgencySDate").MdPersianDateTimePicker({
        targetTextSelector: "#finalTickAgencySDate",
      });
      $("#finalTickAgencyEDate").MdPersianDateTimePicker({
        targetTextSelector: "#finalTickAgencyEDate",
      });
      $("#agencyManagePassenger" + "-div").css("display", "none");
      $("#agencyManageGuest" + "-div").css("display", "none");
      $("#agencyManagePassenger").selectpicker();
      showAgencyManageList();
    }
    if (jQuery.inArray(3, res[1]) !== -1) {
      showRestaurantManageList();
    }
    if (jQuery.inArray(4, res[1]) !== -1) {
      showFoodManageList();
    }
    if (jQuery.inArray(5, res[1]) !== -1) {
      showDrinkManageList();
    }
    if (jQuery.inArray(6, res[1]) !== -1) {
      showServiceRouteManageList();
    }
    if (jQuery.inArray(7, res[1]) !== -1) {
      var olsDate = set_persian_date_new("#overtimeLunchManageSDateSearch");
      var oldDate = set_persian_date_new("#overtimeLunchManageEDateSearch");
      $("#overtimeLunchDetailsDate").MdPersianDateTimePicker({
        targetTextSelector: "#overtimeLunchDetailsDate",
      });
      $("#overtimeLunchManageDate").MdPersianDateTimePicker({
        targetTextSelector: "#overtimeLunchManageDate",
      });
      $("#overtimeLunchGuestDate").MdPersianDateTimePicker({
        targetTextSelector: "#overtimeLunchGuestDate",
      });
      $("#finalTickLunchSDate").MdPersianDateTimePicker({
        targetTextSelector: "#finalTickLunchSDate",
      });
      $("#finalTickLunchEDate").MdPersianDateTimePicker({
        targetTextSelector: "#finalTickLunchEDate",
      });
      $("#overtimeLunchManagePersonnel").selectpicker();
      showOvertimeLunchManageList();
      select_data_picker("#overtimeLunchManagePersonnelSearch");
    }
    if (jQuery.inArray(8, res[1]) !== -1) {
      // $("#PropertyManageTabID-alink").on('click',showPropertyManageList());
      document
        .querySelector("#PropertyManageTabID-alink")
        .addEventListener("click", function () {
          showPropertyManageList();
        });
    }
  }
}

// function showPropertyManageList(page) {

// }

async function editcreatePropertyManage(id = 0) {
  ut.showModal({
    title: "ایجاد /ویرایش انتقال اموال",
    body: await editCreatePrpertyManageFrom(),
  });
  var p_all_info = await get_property_info(id);

  if (p_all_info) {
    var p_row = p_all_info.p_info;
    if (p_row.tool_id) {
      $("#tool_name").val(p_row.tool_id);
    }
    if (p_row.tool_status) {
      $("#tool_status").val(p_row.tool_status);
    }
    if (p_row.prop_num) {
      $("#property_num").val(p_row.prop_num);
    }
    if (p_row.receiver) {
      $("#p_receiver").val(p_row.receiver);
    }
    if (p_row.deliver) {
      $("#deliver").val(p_row.deliver);
      $("#deliver").attr("disabled", true);
    }
    if (p_row.deliver_date) {
      $("#deliver_date").val(p_row.deliver_date);
    }
    if (p_row.desc) {
      $("#desc").val(p_row.desc);
    }
    if (p_row.desc) {
      $("#p_id").val(p_row.RowID);
    }
    
    $("#prop_attachments").html(p_all_info.p_file);
  }
  select_data_picker("#tool_name");
  select_data_picker("#deliver");
  select_data_picker("#p_receiver");
  set_persian_date_new("#deliver_date");
  if (id == 0) {
    $("#prop_attachments").html("");
  }
  else{
    await get_transaction_attach(id);
  }
}

async function get_property_info(id) {
  var action = "get_property_info";
  var param = { action: action, id: id };
  var res = await manageAjaxRequestCustom(param);
  return res.data;
}

async function editCreatePrpertyManageFrom(values = {}) {
  const defaults = {
    tool_name: "",
    receiver: "",
    deliver: "",
    deliver_date: "",
    return_date: "",
    tranasaction_type: "",
    property_status: "",
    p_serial_new: "",
    p_serial_old: "",
    desc: "",
  };
  const setValues = { ...defaults, ...values };

  var tool_status = await get_property_status();
  var status_op = `<option value='-1'> وضعیت ابزار /اموال را انتخاب نمایید</option>`;
  for (var k in tool_status) {
    var status = tool_status[k];
    status_op += `<option value="${status["value"]}">${status["desc"]}</option>`;
  }

  var tool_status = await get_property_status();
  var status_op = `<option value='-1'> وضعیت ابزار /اموال را انتخاب نمایید</option>`;
  for (var k in tool_status) {
    var status = tool_status[k];
    status_op += `<option value="${status["value"]}">${status["desc"]}</option>`;
  }

  var tools_list = await get_property_tools_list();
  var tools_op = `<option value='-1'>  ابزار /اموال را انتخاب نمایید</option>`;
  for (var k in tools_list) {
    var tool = tools_list[k];
    tools_op += `<option value="${tool["RowID"]}">${tool["code"]} - ${tool["tool_name"]} </option>`;
  }

  var personel_list = await get_personells();
  var per_op_deliver = `<option value='-1'> تحویل گیرنده را انتخاب نمایید</option>`;
  var per_op_receiver = `<option value='-1'> تحویل دهنده را انتخاب نمایید</option>`;
  for (var k in personel_list) {
    var personel = personel_list[k];
    per_op_deliver += `<option value="${personel["RowID"]}">${personel["fname"]} ${personel["lname"]} </option>`;
    per_op_receiver += `<option value="${personel["RowID"]}">${personel["fname"]} ${personel["lname"]} </option>`;
  }

  var form = `
    <form id="prop_form" enctype="multipart/form-data">
  <div class="form-group row">
    <label for="tool_name" class="col-sm-4 col-form-label">نام ابزار/اموال</label>
    <div class="col-sm-8">
      <select class="form-control" id="tool_name" onchange="get_tools_info(this.value)">
      ${tools_op}
      </select>
     
    </div>
  </div>
  <div class="form-group row">
    <label for="tool_name" class="col-sm-4 col-form-label">شماره اموال</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="property_num" placeholder="شماره اموال"/>
     
    </div>
  </div>
  <div class="form-group row">
    <label for="receiver" class="col-sm-4 col-form-label">تحویل گیرنده.</label>
    <div class="col-sm-8">
      <select class="form-control" id="p_receiver">
      ${per_op_deliver}
     
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="deliver" class="col-sm-4 col-form-label">تحویل دهنده</label>
    <div class="col-sm-8">
      <select class="form-control" id="deliver">
      ${per_op_receiver}
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="receiver" class="col-sm-4 col-form-label">تاریخ تحویل </label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="deliver_date" placeholder="تاریخ تحویل ">
    </div>
  </div>
  <!--<div class="form-group row">
    <label for="receiver" class="col-sm-4 col-form-label">تاریخ عودت </label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="receiver_date" placeholder="تاریخ عودت ">
    </div>
  </div>-->
  <div class="form-group row">
    <label for="receiver" class="col-sm-4 col-form-label"> وضعیت اموال/ابزار </label>
    <div class="col-sm-8">
      <select class="form-control" id="tool_status">${status_op}</select>
    </div>
  </div>
  <div class="form-group row">
    <label for="receiver" class="col-sm-4 col-form-label">توضیحات </label>
    <div class="col-sm-8">
      <textarea class="form-control" id="desc" name="desc" placeholder=" توضیحات"></textarea>
    </div>
  </div>
  <div class="form-group row">
    <label for="receiver" class="col-sm-4 col-form-label">آپلود مستندات </label>
    <div class="col-sm-8">
      <input class="form-control" id="props_doc_file"  name="props_doc_file" type="file" multiple/>
    </div>
  </div>
  <input type="hidden" id="props_doc_file_count" value="0">
  <div id="prop_attachments"></div>
  <div class="d-flex justify-content-between p-4 border-top">
    <input type="hidden" value="0" id="p_id">
    <button type="button" class="btn btn-primary" id="doEditCreatePropertyManage_btn" onclick="doEditCreatePropertyManage()">تایید</button>
    <!--<button type="button" class="btn btn-danger" id="abort" onclick="close_modal('dynamicModal')">انصراف</button>-->
  </div>
  
</form>`;
  return form;
}
async function doEditCreatePropertyManage() {
  var action = "doEditCreatePropertyManage";
  var prop_num = $("#property_num").val();
  var receiver = $("#p_receiver").val();
  var deliver = $("#deliver").val();
  var tool_id = $("#tool_name").val();
  var deliver_date = $("#deliver_date").val();
  var tool_status = $("#tool_status").val();
  var desc = $("#desc").val();
  var pid= $("#p_id").val()
  var prop_doc_file = document.getElementById("props_doc_file").files;

  if (tool_id == -1) {
    notice1Sec("نام ابزار را وارد نمایید", "yellow");
    return false;
  }
  if (prop_num.trim() == null || prop_num.trim() == "") {
    notice1Sec("کد اموال   را وارد نمایید", "yellow");
    return false;
  }
  if (deliver == -1) {
    notice1Sec("نام تحویل گیرنده را وارد نمایید", "yellow");
    return false;
  }
  if (receiver == -1) {
    notice1Sec("نام تحویل دهنده را وارد نمایید", "yellow");
    return false;
  }
  if (deliver_date.trim() == null || deliver_date.trim() == "") {
    notice1Sec("تاریخ تحویل   را وارد نمایید", "yellow");
    return false;
  }
  if (tool_status == -1) {
    notice1Sec("وضعیت  ابزار را وارد نمایید", "yellow");
    return false;
  }

  if ($("#props_doc_file").val() == "" && $("#props_doc_file_count").val() == 0) {
    notice1Sec("بارگزاری مستندات الزامی می باشد", "yellow");
    return false;
  }
  if(parseInt(deliver) == parseInt(receiver)){
    notice1Sec("تحویل دهنده و تحویل گیرنده نباید یکسان باشند", "yellow");
    return false;
  }
  var formData = new FormData();
  formData.append("action", action);
  formData.append("prop_num", prop_num);
  formData.append("receiver", receiver);
  formData.append("deliver", deliver);
  formData.append("tool_id", tool_id);
  formData.append("deliver_date", deliver_date);
  formData.append("tool_status", tool_status);
  formData.append("desc", desc);
  formData.append("pid", pid);
  for (var k = 0; k < prop_doc_file.length; k++) {
    formData.append("prop_doc_file[]", prop_doc_file[k]);
  }

  try {
    var response = await fetch("php/managemantproccess.php", {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error(`خطایی رخ داده است`);
    } else {
      var res = await response.json().then((data) => {
        return data;
      });
      console.log(res);
      if (res.res == "false") {
        notice1Sec(res.data, "yellow");
        return false;
      } else {
         notice1Sec(res.data, "green");
        await showPropertyManageList();
        $("#dynamicModal").modal('hide')
        $("#dynamicModal").remove();
        return false;
       
      }
    }
  } catch (error) {
    //  swal_custom('خطا',error,'w');
    notice1Sec("خطای غیر منتظره !!!", "red");
  }
}
async function deletePropertyManageAttachment(fid,pid){
  
  if($("#prop_manage_rows").val()>1){
  Swal.fire({
  title: " حذف پیوست",
  text: "پیوست تراکنش حذف شود",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "بله حذف شود!",
   cancelButtonText: "انصراف"
}).then(async (result) => {
    if (result.isConfirmed) {
      var action = "deletePropertyManageAttachment";
      var param = { action: action, fid: fid };
      var res = await manageAjaxRequestCustom(param);
      if(res.res=='true'){
        await get_transaction_attach(pid)
        notice1Sec(res.data,'green')
        return ;
      }
      notice1Sec(res.data,'yellow')
    }
});
}
else{
  notice1Sec("حداقل یک پیوست باید وجود داشته باشد", "yellow");
  return false;
}
  
}

async function deletePropertyManageRow(row_id) {
 
  Swal.fire({
    title: " حذف تراکنش",
    text: "تراکنش حذف شود",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "بله حذف شود!",
    cancelButtonText: "انصراف"
  }).then(async (result) => {
      if (result.isConfirmed) {
        var action = "deletePropertyManageRow";
        var param = { action: action, row_id: row_id };
        var res = await manageAjaxRequestCustom(param);
        if(res.res=='true'){
          await showPropertyManageList();
          notice1Sec(res.data,'green')
          return ;
        }
        notice1Sec(res.data,'yellow')
      }
  });
}


async function print_prop_report(){
  var form = document.querySelector("#frm_search");
  var form_data = new FormData(form);
  form_data.append("action", "print_prop_report");
  var res = await manageAjaxRequestCustom(form_data);
  if (res.res == "true") {
   window.open('print/print.php');
  }
}

async function get_transaction_attach(row_id) {
  var action = "get_transaction_attach";
  var param = { action: action, row_id: row_id };
  var res = await manageAjaxRequestCustom(param);
  
  if (res.res == "true") {
    if($("#prop_attachments").is(":visible")){
      $("#prop_attachments").html("");
      $("#prop_attachments").html(res.data.html);
      $("#props_doc_file_count").val(res.data.file_count);
      return ;
   
  }else{
    
     ut.showModal({  
      
      title: "مستندات  پیوست شده",
      body: res.data.html,
    });
  }
}
}

async function prop_download_file(row_id) {
  var action = "prop_download_file";
  var param = { action: action, row_id: row_id };
  var res = await manageAjaxRequestCustom(param);
  if (res.res == "true") {
    var base64Data = res.data.file_data;
    // جدا کردن هدر base64 (اگر وجود دارد)
    if (base64Data.includes("base64,")) {
      base64Data = base64Data.split("base64,")[1];
    }
    var fileName = res.data.file_name;
    // ایجاد لینک دانلود
    const link = document.createElement("a");
    link.href = `data:application/octet-stream;base64,${base64Data}`;
    link.download = fileName;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }
}

async function showPropertyManageList(page = 1) {
  add_loading('showPropertyManageList');
  var action = "showPropertyManageList";
  var form = document.getElementById("frm_search");
  if (form && form.length) {
    var formData = new FormData(form);
  } else {
    var formData = new FormData();
  }

  formData.append("action", action);
  formData.append("page", page);

  var res = await manageAjaxRequestCustom(formData);
  $("#PropertyManageBody").html(res.data);
  select_data_picker("#tools_id");
  select_data_picker("#p_receiver");
  select_data_picker("#deliver");
  select_data_picker("#tools_status");
  set_persian_date_new("#fdate");
  set_persian_date_new("#edate");
  remove_loading('showPropertyManageList');
}

async function get_tools_info(tool_id) {
  var action = "get_tools_info";
  var param = { action: action, tool_id: tool_id };
  var res = await manageAjaxRequestCustom(param);
  if (res.res == "true") {
    $("#deliver").val(res.data);
    $("#deliver").attr("disabled", true);
    select_data_picker("#deliver");
  }
}

async function get_property_status() {
  var action = "get_property_status";
  var param = { action: action };
  var res = await manageAjaxRequestCustom(param);
  return res.data;
}

async function get_property_tools_list() {
  var action = "get_property_tools_list";
  var param = { action: action };
  var res = await manageAjaxRequestCustom(param);
  return res.data;
}

async function get_personells() {
  var action = "get_personells";
  var param = { action: action };
  var res = await manageAjaxRequestCustom(param);
  return res.data;
}

async function create_prop_excel_report() {
  var form = document.querySelector("#frm_search");
  var form_data = new FormData(form);
  form_data.append("action", "create_prop_excel_report");
  var res = await manageAjaxRequestCustom(form_data);
  if (res.res == "true") {
    window.open(res.data);
  }
}



//******************** ثبت وقایع انتظامات ********************

function showRecordEventsList(page) {
  if (typeof page == "undefined") {
    page = 1;
  }
  var sDate = $("#RecordEventsManageSDateSearch").val();
  var eDate = $("#RecordEventsManageEDateSearch").val();
  var desc = $("#RecordEventsManageDescSearch").val();
  var event_type = $("#RecordEventsManageTypeSearch").val();
  var action = "showRecordEventsList";
  var param = {
    action: action,
    sDate: sDate,
    eDate: eDate,
    desc: desc,
    event_type: event_type,
    page: page,
  };
  var res = manageAjaxRequest(param);
  if (res != false) {
    $("#recordEventManageBody").html("");
    $("#recordEventManageBody").html(res);
  }
}

function createEvents() {
  $("#recordEventsManageHiddenEid").val("");
  $("#recordEventsManageTime").val("");
  $("#recordEventsManageDesc").val("");
  $("#recordEventsManageType").val(-1);
  $("#recordEventsManageDate").MdPersianDateTimePicker({
    targetTextSelector: "#recordEventsManageDate",
    disableAfterDate: new Date(),
  });
  // $("#recordEventsManageDate")
  $("#recordEventsManageModal").modal("show");
}

function editEvents(infoID) {
  var res = Main.getEventsInfo(infoID);
  if (res != false) {
    $("#recordEventsManageModal").modal("show");
    $("#recordEventsManageHiddenEid").val(res["infoID"]);
    $("#recordEventsManageTime").val(res["cTime"]);
    $("#recordEventsManageDesc").val(res["event"]);
    $("#recordEventsManageType").val(res["e_type"] ?? -1);
    $("#recordEventsManageDate").val(res["event_date"]);
    $("#recordEventsManageDate").MdPersianDateTimePicker({
      targetTextSelector: "#recordEventsManageDate",

      disableAfterDate: new Date(),
    });
  }
}

async function get_units() {
  var action = "get_units";
  var param = { action: action };
  var res = await manageAjaxRequestCustom(param);
  return res.data;
}

async function manageEventsType() {
  var action = "createEventsType";
  if ($("#createEventsTypeModal")) {
    $("#createEventsTypeModal").remove();
  }

  var modal_id = "createEventsTypeModal";
  var modal = `<div class="modal fade" id="${modal_id}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary text-light">
          <h5 class="modal-title" id="exampleModalLabel">مدیریت نوع رویداد</h5>
          <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">X</span>
          </button>
        </div>
        <div class="modal-body">
        <button type="button" class="btn btn-primary" id="createEventsType_btn" onclick="createEventsType()">ایجاد رویداد</button>
        <div id="createEventsTypeForm-body" ></div>
          
        </div>
        <div class="modal-footer">
          ${await getEnventsTypeList()}
         
        </div>
      </div>
    </div>
  </div>`;
  $("body").append(modal);
  $("#createEventsTypeModal").modal(
    { backdrop: "static", keyboard: false },
    "show"
  );
  // $("#createEventsTypeForm-body").addClass("event_form_body");
  // $("#createEventsType_btn").show();
}

async function getEnventsTypeList() {
  var action = "getEnventsTypeList";
  var res = await manageAjaxRequestCustom({ action: action });
  return res.data;
}

async function createEventsType(event_type_detailes = []) {
  var form = await createEventsTypeFrom();
  $("#createEventsTypeForm-body").html(form);

  var unit_allowed = "";
  if (event_type_detailes) {
    var units = event_type_detailes.units_allowed;
    var row_id = event_type_detailes.RowID;
    if (parseInt(row_id) > 0) {
      $("#event_type_id").val(row_id);
      if (units) {
        unit_allowed = units.split(",");
      }

      $("#event_type_div").html(
        `<label for="recipient-name" class="col-form-label"> نوع رویداد :  </label>${event_type_detailes.desc}<label>`
      );
    }
    $("#event_type_allowed_unit").val(unit_allowed);
    select_data_picker("#event_type_allowed_unit");
    $("#createEventsType_btn").hide();
    $("#createEventsTypeForm-body").addClass("event_form_body");
  }

  //$("#event_type_allowed_unit").selectpicker("refresh");
}
async function createEventsTypeFrom() {
  var units = await get_units();
  var options = ``;
  for (i in units) {
    var unit = units[i];
    options += `<option value="${unit.RowID}">${unit.Uname}</option>`;
  }
  var form = `
    <form id="createEventsTypeForm" >
         
          
    <div class="form-group" id="event_type_div">
    <label for="recipient-name" class="col-form-label">نوع رویداد</label>
    <input type="text" class="form-control" id="event_type_text">
   
  </div>
  <div class="form-group">
    <label for="message-text" class="col-form-label">واحد های مجاز برای مشاهده</label>
    <select id="event_type_allowed_unit" class="form-control" multiple>
      ${options}
    </select>
  </div>
  </form>
  <div style="display:flex;justify-content:space-between">
   <input type="hidden" id="event_type_id" value="-1">
           <button type="button" class="btn btn-primary" onclick="doEditCreateEventType()">تایید</button>
            <button type="button" onclick="close_event_form()" class="btn btn-danger">انصراف</button>
  </div>`;
  return form;
}

async function doEditCreateEventType() {
  var action = "doEditCreateEventType";
  var event_type = $("#event_type_text").val();
  var event_type_allowed_unit = $("#event_type_allowed_unit").val();
  var event_type_id = $("#event_type_id").val();
  if (event_type_id == -1) {
    if (!event_type || event_type.trim().length == 0) {
      notice1Sec("نوع رویداد مشخص نشده است !", "yellow");
      return false;
    }
  }
  var param = {
    action: action,
    event_type: event_type,
    event_type_allowed_unit: event_type_allowed_unit,
    event_type_id: event_type_id,
  };
  var res = await manageAjaxRequestCustom(param);

  if (res.res == "true") {
    notice1Sec(res.data, "green");
    var histor = await getEnventsTypeList();

    $("#createEventsTypeModal .modal-footer").html(histor);

    $("#createEventsTypeForm-body").html("").removeClass("event_form_body");
    $("#createEventsType_btn").show();
  }
}

function close_event_form() {
  $("#createEventsTypeForm-body").removeClass("event_form_body");

  $("#createEventsType_btn").show();
}

async function manage_allowed_users(row_id) {
  var action = "manage_allowed_users";
  var param = { action: action, row_id: row_id };
  var res = await manageAjaxRequestCustom(param);
  await createEventsType(res.data);
}
async function get_events_report(page = 1) {
  var from_date = $("#from_date").val() ?? "";
  var to_date = $("#to_date").val() ?? "";
  var event_type = $("#event_type").val() ?? -1;
  var event = $("#event").val() ?? "";
  $("#EventsReport").html(
    `<section style="padding:1rem;width:100%;min-height:80vh;background:#fff" id="events_report_section"></section>`
  );
  var action = "get_events_report";
  var param = {
    action: action,
    page: page,
    from_date: from_date,
    to_date: to_date,
    event_type: event_type,
    event: event,
  };
  var res = await manageAjaxRequestCustom(param);
  $("#events_report_section").html(res.data);
  set_persian_date_new("#from_date");
  set_persian_date_new("#to_date");
}

function doEditCreateEvents() {
  var eid = $("#recordEventsManageHiddenEid").val();
  var cTime = $("#recordEventsManageTime").val();
  var cDate = $("#recordEventsManageDate").val();
  var e_type = $("#recordEventsManageType").val();
  var desc = $("#recordEventsManageDesc").val();
  if (!parseInt(cTime.trim().length)) {
    notice1Sec("ساعت مشخص نشده است !", "yellow");
    return false;
  }
  if (!parseInt(cDate.trim().length)) {
    notice1Sec("تاریخ مشخص نشده است !", "yellow");
    return false;
  }
  if (!parseInt(desc.trim().length)) {
    notice1Sec("شرح واقعه مشخص نشده است !", "yellow");
    return false;
  }
  if (parseInt(e_type) == -1) {
    notice1Sec("نوع واقعه مشخص نشده است !", "yellow");
    return false;
  }
  var action = "doEditCreateEvents";
  var param = {
    action: action,
    eid: eid,
    cTime: cTime,
    desc: desc,
    e_type: e_type,
    cDate: cDate,
  };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    if (isNaN(parseInt(eid))) {
      $("#recordEventsManageTime").val("");
      $("#recordEventsManageDesc").val("");
    } else {
      $("#recordEventsManageModal").modal("hide");
      $("body").removeClass("modal-open");
      $(".modal-backdrop").remove();
    }
    showRecordEventsList();
  }
}

//******************** مدیریت آژانس ها ********************

function showAgencyManageList(page) {
  if (typeof page == "undefined") {
    page = 1;
  }
  var sDate = $("#agencyManageSDateSearch").val();
  var eDate = $("#agencyManageEDateSearch").val();
  var billNum = $("#agencyManageBillNumSearch").val();
  var agencyType = $("#agencyManageAgencyTypeSearch").val();
  var serviceType = $("#agencyManageServiceTypeSearch").val();
  var personnel = $("#agencyManagePersonnelSearch").val();
  var action = "showAgencyManageList";
  var param = {
    action: action,
    sDate: sDate,
    eDate: eDate,
    billNum: billNum,
    agencyType: agencyType,
    serviceType: serviceType,
    personnel: personnel,
    page: page,
  };
  var res = manageAjaxRequest(param);
  if (res != false) {
    $("#agencyManageBody").html("");
    $("#agencyManageBody").html(res);
  }
}

function createAgency() {
  $("#agencyManageHiddenAid").val("");
  $("#agencyManageCreateDate").val("");
  $("#agencyManageBillNumber").val("");
  $("#agencyManageAgencyID").val(-1);
  $("#agencyManageServiceID").val(-1);
  $("#agencyManageStopMinute").val("");
  $("#agencyManageAmount").val("");
  $("#agencyManageDescription").val("");
  $("#agencyManageGuest").val("");
  $("#agencyManagePassenger").selectpicker("deselectAll");
  $("#agencyManagePassenger").selectpicker("refresh");
  $("#agencyManagePassenger" + "-div").css("display", "none");
  $("#agencyManageGuest" + "-div").css("display", "none");
  $("#agencyManageModal").modal("show");
}

function editAgency() {
  var ch = $("#agencyManageBody-table").find("input");
  var aid = new Array();
  for (var c = 0; c < ch.length; c++) {
    if (ch[c].checked) {
      aid[aid.length] = ch[c].attributes.rid.value;
    }
  }
  if (aid.length > 1) {
    notice1Sec("فقط یک آژانس باید انتخاب شده باشد !", "red");
    return false;
  }
  if (aid.length == 0) {
    notice1Sec("هیچ آژانسی انتخاب نشده است !", "red");
    return false;
  }
  aid = aid[0];
  var res = Main.getAgencyInfo(aid);
  if (res != false) {
    $("#agencyManageModal").modal("show");
    $("#agencyManageHiddenAid").val(res["aid"]);
    $("#agencyManageCreateDate").val(res["createDate"]);
    $("#agencyManageBillNumber").val(res["bNumber"]);
    $("#agencyManageAgencyID").val(res["agencyID"]);
    $("#agencyManageServiceID").val(res["serviceID"]);
    $("#agencyManageStopMinute").val(res["stopMinute"]);
    $("#agencyManageAmount").val(res["amount"]);
    $("#agencyManageDescription").val(res["description"]);
    if (
      parseInt(res["serviceID"]) == 0 ||
      parseInt(res["serviceID"]) == 1 ||
      parseInt(res["serviceID"]) == 3
    ) {
      // ماموریت یا اضافه کار یا شیفت کاری
      $("#agencyManagePassenger").val(res["passenger"].split(","));
      $("#agencyManagePassenger").selectpicker("refresh");
      $("#agencyManageGuest").val("");
      $("#agencyManagePassenger" + "-div").css("display", "");
      $("#agencyManageGuest" + "-div").css("display", "none");
    } else if (parseInt(res["serviceID"]) == 2) {
      // میهمان
      $("#agencyManagePassenger").selectpicker("deselectAll");
      $("#agencyManagePassenger").selectpicker("refresh");
      $("#agencyManageGuest").val(res["guest"]);
      $("#agencyManagePassenger" + "-div").css("display", "none");
      $("#agencyManageGuest" + "-div").css("display", "");
    } else {
      $("#agencyManagePassenger").selectpicker("deselectAll");
      $("#agencyManagePassenger").selectpicker("refresh");
      $("#agencyManageGuest").val("");
      $("#agencyManagePassenger" + "-div").css("display", "none");
      $("#agencyManageGuest" + "-div").css("display", "none");
    }
  }
}

function doEditCreateAgency() {
  var aid = $("#agencyManageHiddenAid").val();
  var cDate = $("#agencyManageCreateDate").val();
  var billNum = $("#agencyManageBillNumber").val();
  var agencyID = $("#agencyManageAgencyID").val();
  var serviceID = $("#agencyManageServiceID").val();
  var stopMinute = $("#agencyManageStopMinute").val();
  var amount = $("#agencyManageAmount").val();
  var guest = $("#agencyManageGuest").val();
  var passenger = $("#agencyManagePassenger").val();
  var passenger = passenger.join(",");
  var desc = $("#agencyManageDescription").val();
  if (!parseInt(cDate.trim().length)) {
    notice1Sec("تاریخ مشخص نشده است !", "yellow");
    return false;
  }
  if (!parseInt(billNum.trim().length)) {
    notice1Sec("شماره قبض مشخص نشده است !", "yellow");
    return false;
  }
  if (parseInt(agencyID) == -1) {
    notice1Sec("نام آژانس مشخص نشده است !", "yellow");
    return false;
  }
  if (parseInt(serviceID) == -1) {
    notice1Sec("نوع سرویس مشخص نشده است !", "yellow");
    return false;
  }
  if (!parseInt(stopMinute.trim().length)) {
    notice1Sec("توقف به دقیقه مشخص نشده است !", "yellow");
    return false;
  }
  if (
    parseInt(serviceID) == 0 ||
    parseInt(serviceID) == 1 ||
    parseInt(serviceID) == 3
  ) {
    //  ماموریت یا اضافه کار یا شیفت کاری
    if (!parseInt(passenger.trim().length)) {
      notice1Sec("نام مسافر/مسافران مشخص نشده است !", "yellow");
      return false;
    }
  }
  if (parseInt(serviceID) == 2) {
    // میهمان
    if (!parseInt(guest.trim().length)) {
      notice1Sec("نام میهمان/میهمانان مشخص نشده است !", "yellow");
      return false;
    }
  }
  var action = "doEditCreateAgency";
  var param = {
    action: action,
    aid: aid,
    cDate: cDate,
    billNum: billNum,
    agencyID: agencyID,
    serviceID: serviceID,
    stopMinute: stopMinute,
    amount: amount,
    passenger: passenger,
    guest: guest,
    desc: desc,
  };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    if (isNaN(parseInt(aid))) {
      $("#agencyManageCreateDate").val("");
      $("#agencyManageBillNumber").val("");
      $("#agencyManageAgencyID").val(-1);
      $("#agencyManageServiceID").val(-1);
      $("#agencyManageStopMinute").val("");
      $("#agencyManageAmount").val("");
      $("#agencyManageGuest").val("");
      $("#agencyManageDescription").val("");
      $("#agencyManagePassenger").selectpicker("deselectAll");
      $("#agencyManagePassenger").selectpicker("refresh");
    } else {
      $("#agencyManageModal").modal("hide");
      $("body").removeClass("modal-open");
      $(".modal-backdrop").remove();
    }
    showAgencyManageList();
  }
}

function attachedFileToAgency(aid) {
  $("#agencyAttachmentFileAttachedID").val(aid);
  $("#agencyAttachmentFileAttached").val("");
  var res = Main.getAttachedAgencyFile(aid);
  $("#agencyAttachmentFile-body").html("");
  $("#agencyAttachmentFile-body").html(res);
  $("#agencyAttachmentFileModal").modal("show");
}

function doAttachedFileToAgency() {
  var aid = $("#agencyAttachmentFileAttachedID").val();
  var formData = new FormData();
  if (
    $("#agencyAttachmentFileAttached").val() != "" ||
    $("#agencyAttachmentFileAttached")[0].files.length != 0
  ) {
    var fileSelect = document.getElementById("agencyAttachmentFileAttached");
    var files = fileSelect.files;
    if (!window.File && window.FileReader && window.FileList && window.Blob) {
      //if browser doesn't supports File API
      notice1Sec(
        "Your browser does not support new File API! Please upgrade.",
        "yellow"
      );
      return false;
    } else {
      var total_selected_files = files.length;
      if (parseInt(total_selected_files) > 1) {
        notice1Sec("فقط یک فایل قابل انتخاب می باشد !", "yellow");
        return false;
      }
      for (var x = 0; x < total_selected_files; x++) {
        formData.append("files[]", files[x]);
      }
    }
  }
  formData.append("action", "doAttachedFileToAgency");
  formData.append("aid", aid);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "php/managemantproccess.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      var res = JSON.parse(xhr.responseText);
      if (res["res"] == "false") {
        notice1Sec(res["data"], "yellow");
      } else {
        notice1Sec("بارگذاری انجام شد.", "green");
        var rst = Main.getAttachedAgencyFile(aid);
        $("#agencyAttachmentFile-body").html("");
        $("#agencyAttachmentFile-body").html(rst);
        $("#agencyAttachmentFileAttached").val("");
      }
    } else {
      notice1Sec("خطایی رخ داده است !", "yellow");
    }
  };
  xhr.send(formData);
}

function deleteAttachAgencyFile(aid) {
  var action = "deleteAttachAgencyFile";
  var param = { action: action, aid: aid };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    var res = Main.getAttachedAgencyFile(aid);
    $("#agencyAttachmentFile-body").html("");
    $("#agencyAttachmentFile-body").html(res);
  }
}

function getPassengerOrGuest() {
  var serviceID = $("#agencyManageServiceID").val();
  if (parseInt(serviceID) == 2) {
    // میهمان
    $("#agencyManagePassenger" + "-div").css("display", "none");
    $("#agencyManageGuest" + "-div").css("display", "");
    $("#agencyManagePassenger").selectpicker("deselectAll");
    $("#agencyManagePassenger").selectpicker("refresh");
  } else if (
    parseInt(serviceID) == -1 ||
    parseInt(serviceID) == 4 ||
    parseInt(serviceID) == 5
  ) {
    $("#agencyManagePassenger" + "-div").css("display", "none");
    $("#agencyManageGuest" + "-div").css("display", "none");
    $("#agencyManageGuest").val("");
    $("#agencyManagePassenger").selectpicker("deselectAll");
    $("#agencyManagePassenger").selectpicker("refresh");
  } else {
    $("#agencyManagePassenger" + "-div").css("display", "");
    $("#agencyManageGuest" + "-div").css("display", "none");
    $("#agencyManageGuest").val("");
  }
}

function printAgency() {
  var sDate = $("#agencyManageSDateSearch").val();
  var eDate = $("#agencyManageEDateSearch").val();
  var billNum = $("#agencyManageBillNumSearch").val();
  var agencyType = $("#agencyManageAgencyTypeSearch").val();
  var serviceType = $("#agencyManageServiceTypeSearch").val();
  var personnel = $("#agencyManagePersonnelSearch").val();
  var action = "printAgency";
  var param = {
    action: action,
    sDate: sDate,
    eDate: eDate,
    billNum: billNum,
    agencyType: agencyType,
    serviceType: serviceType,
    personnel: personnel,
  };
  var res = manageAjaxRequest(param);
  if (res != false) {
    $("#hiddenAgencyPrintBody").html("");
    $("#hiddenAgencyPrintBody").html(res);
    $(".demoAgency").printThis({
      // base: "http://localhost:8012/BOM/administrator.php"
      base: base_url,
    });
  }
}
function getAgencyExcel2() {
  getAgencyExcel(1);
}

function getAgencyExcel(group_method = 0) {
  var sDate = $("#agencyManageSDateSearch").val();
  var eDate = $("#agencyManageEDateSearch").val();
  var billNum = $("#agencyManageBillNumSearch").val();
  var agencyType = $("#agencyManageAgencyTypeSearch").val();
  var serviceType = $("#agencyManageServiceTypeSearch").val();
  var personnel = $("#agencyManagePersonnelSearch").val();
  var action = "getAgencyExcel";
  var group_method = group_method;
  var param = {
    action: action,
    sDate: sDate,
    eDate: eDate,
    billNum: billNum,
    agencyType: agencyType,
    serviceType: serviceType,
    personnel: personnel,
    group_method: group_method,
  };
  var res = manageAjaxRequest(param);
  if (res != false) {
    window.open(res);
  }
}

function getUnitAgencyAmountExcel() {
  var sDate = $("#agencyManageSDateSearch").val();
  var eDate = $("#agencyManageEDateSearch").val();
  var agencyType = $("#agencyManageAgencyTypeSearch").val();
  var serviceType = $("#agencyManageServiceTypeSearch").val();
  var action = "getUnitAgencyAmountExcel";
  var param = {
    action: action,
    sDate: sDate,
    eDate: eDate,
    agencyType: agencyType,
    serviceType: serviceType,
  };
  var res = manageAjaxRequest(param);
  if (res != false) {
    window.open(res);
  }
}

function finalTickAgency() {
  $("#finalTickAgencySDate").val("");
  $("#finalTickAgencyEDate").val("");
  $("#finalTickAgencyModal").modal("show");
}

function doFinalTickAgency() {
  var sDate = $("#finalTickAgencySDate").val();
  var eDate = $("#finalTickAgencyEDate").val();
  if (!parseInt(sDate.trim().length)) {
    notice1Sec("بازه زمانی درست انتخاب نشده است !", "yellow");
    return false;
  }
  if (!parseInt(eDate.trim().length)) {
    notice1Sec("بازه زمانی درست انتخاب نشده است !", "yellow");
    return false;
  }
  var action = "doFinalTickAgency";
  var param = { action: action, sDate: sDate, eDate: eDate };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    $("#finalTickAgencySDate").val("");
    $("#finalTickAgencyEDate").val("");
    showAgencyManageList();
  }
}

//******************** رستوران ها ********************

function showRestaurantManageList(page) {
  if (typeof page == "undefined") {
    page = 1;
  }
  var action = "showRestaurantManageList";
  var param = { action: action, page: page };
  var res = manageAjaxRequest(param);
  if (res != false) {
    $("#restaurantManageBody").html("");
    $("#restaurantManageBody").html(res);
  }
}

function createRestaurant() {
  $("#restaurantManageHiddenRid").val("");
  $("#restaurantManageRName").val("");
  $("#restaurantManageModal").modal("show");
}

function editRestaurant() {
  var ch = $("#restaurantManageBody-table").find("input");
  var rid = new Array();
  for (var c = 0; c < ch.length; c++) {
    if (ch[c].checked) {
      rid[rid.length] = ch[c].attributes.rid.value;
    }
  }
  if (rid.length > 1) {
    notice1Sec("فقط یک رستوران باید انتخاب شده باشد !", "red");
    return false;
  }
  if (rid.length == 0) {
    notice1Sec("هیچ رستورانی انتخاب نشده است !", "red");
    return false;
  }
  rid = rid[0];
  var res = Main.getRestaurantInfo(rid);
  if (res != false) {
    $("#restaurantManageModal").modal("show");
    $("#restaurantManageHiddenRid").val(res["rid"]);
    $("#restaurantManageRName").val(res["restaurant_Name"]);
  }
}

function doEditCreateRestaurant() {
  var rid = $("#restaurantManageHiddenRid").val();
  var rName = $("#restaurantManageRName").val();
  if (!parseInt(rName.trim().length)) {
    notice1Sec("نام رستوران مشخص نشده است !", "yellow");
    return false;
  }
  var action = "doEditCreateRestaurant";
  var param = { action: action, rid: rid, rName: rName };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    $("#restaurantManageModal").modal("hide");
    $("body").removeClass("modal-open");
    $(".modal-backdrop").remove();
    showRestaurantManageList();
  }
}

//******************** غذا ها ********************

function showFoodManageList(page) {
  if (typeof page == "undefined") {
    page = 1;
  }
  var action = "showFoodManageList";
  var param = { action: action, page: page };
  var res = manageAjaxRequest(param);
  if (res != false) {
    $("#foodManageBody").html("");
    $("#foodManageBody").html(res);
  }
}

function createFood() {
  $("#foodManageHiddenFid").val("");
  $("#foodManageFName").val("");
  $("#foodManageAmount").val("");
  $("#foodManageModal").modal("show");
}

function editFood() {
  var ch = $("#foodManageBody-table").find("input");
  var fid = new Array();
  for (var c = 0; c < ch.length; c++) {
    if (ch[c].checked) {
      fid[fid.length] = ch[c].attributes.rid.value;
    }
  }
  if (fid.length > 1) {
    notice1Sec("فقط یک غذا باید انتخاب شده باشد !", "red");
    return false;
  }
  if (fid.length == 0) {
    notice1Sec("هیچ غذایی انتخاب نشده است !", "red");
    return false;
  }
  fid = fid[0];
  var res = Main.getFoodInfo(fid);
  if (res != false) {
    $("#foodManageModal").modal("show");
    $("#foodManageHiddenFid").val(res["fid"]);
    $("#foodManageFName").val(res["food_Name"]);
    $("#foodManageAmount").val(res["amount"]);
  }
}

function doEditCreateFood() {
  var fid = $("#foodManageHiddenFid").val();
  var fName = $("#foodManageFName").val();
  var amount = $("#foodManageAmount").val();
  if (!parseInt(fName.trim().length)) {
    notice1Sec("نام غذا مشخص نشده است !", "yellow");
    return false;
  }
  if (!parseInt(amount.trim().length)) {
    notice1Sec("قیمت غذا مشخص نشده است !", "yellow");
    return false;
  }
  var action = "doEditCreateFood";
  var param = { action: action, fid: fid, fName: fName, amount: amount };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    $("#foodManageModal").modal("hide");
    $("body").removeClass("modal-open");
    $(".modal-backdrop").remove();
    showFoodManageList();
  }
}

//******************** نوشیدنی ها ********************

function showDrinkManageList(page) {
  if (typeof page == "undefined") {
    page = 1;
  }
  var action = "showDrinkManageList";
  var param = { action: action, page: page };
  var res = manageAjaxRequest(param);
  if (res != false) {
    $("#drinkManageBody").html("");
    $("#drinkManageBody").html(res);
  }
}

function createDrink() {
  $("#drinkManageHiddenDid").val("");
  $("#drinkManageDName").val("");
  $("#drinkManageAmount").val("");
  $("#drinkManageModal").modal("show");
}

function editDrink() {
  var ch = $("#drinkManageBody-table").find("input");
  var did = new Array();
  for (var c = 0; c < ch.length; c++) {
    if (ch[c].checked) {
      did[did.length] = ch[c].attributes.rid.value;
    }
  }
  if (did.length > 1) {
    notice1Sec("فقط یک نوشیدنی باید انتخاب شده باشد !", "red");
    return false;
  }
  if (did.length == 0) {
    notice1Sec("هیچ نوشیدنی انتخاب نشده است !", "red");
    return false;
  }
  did = did[0];
  var res = Main.getDrinkInfo(did);
  if (res != false) {
    $("#drinkManageModal").modal("show");
    $("#drinkManageHiddenDid").val(res["did"]);
    $("#drinkManageDName").val(res["drink_Name"]);
    $("#drinkManageAmount").val(res["amount"]);
  }
}

function doEditCreateDrink() {
  var did = $("#drinkManageHiddenDid").val();
  var dName = $("#drinkManageDName").val();
  var amount = $("#drinkManageAmount").val();
  if (!parseInt(dName.trim().length)) {
    notice1Sec("نام نوشیدنی مشخص نشده است !", "yellow");
    return false;
  }
  if (!parseInt(amount.trim().length)) {
    notice1Sec("قیمت نوشیدنی مشخص نشده است !", "yellow");
    return false;
  }
  var action = "doEditCreateDrink";
  var param = { action: action, did: did, dName: dName, amount: amount };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    $("#drinkManageModal").modal("hide");
    $("body").removeClass("modal-open");
    $(".modal-backdrop").remove();
    showDrinkManageList();
  }
}

//******************** مسیرهای سرویس دهی ********************

function showServiceRouteManageList(page) {
  if (typeof page == "undefined") {
    page = 1;
  }
  var action = "showServiceRouteManageList";
  var param = { action: action, page: page };
  var res = manageAjaxRequest(param);
  if (res != false) {
    $("#serviceRouteManageBody").html("");
    $("#serviceRouteManageBody").html(res);
  }
}

function createServiceRoute() {
  $("#serviceRouteManageHiddenSRid").val("");
  $("#serviceRouteManageRName").val("");
  $("#serviceRouteManageModal").modal("show");
}

function editServiceRoute() {
  var ch = $("#serviceRouteManageBody-table").find("input");
  var srid = new Array();
  for (var c = 0; c < ch.length; c++) {
    if (ch[c].checked) {
      srid[srid.length] = ch[c].attributes.rid.value;
    }
  }
  if (srid.length > 1) {
    notice1Sec("فقط یک مسیر باید انتخاب شده باشد !", "red");
    return false;
  }
  if (srid.length == 0) {
    notice1Sec("هیچ مسیری انتخاب نشده است !", "red");
    return false;
  }
  srid = srid[0];
  var res = Main.getServiceRouteInfo(srid);
  if (res != false) {
    $("#serviceRouteManageModal").modal("show");
    $("#serviceRouteManageHiddenSRid").val(res["srid"]);
    $("#serviceRouteManageRName").val(res["routeName"]);
  }
}

function doEditCreateServiceRoute() {
  var srid = $("#serviceRouteManageHiddenSRid").val();
  var rName = $("#serviceRouteManageRName").val();
  if (!parseInt(rName.trim().length)) {
    notice1Sec("نام مسیر مشخص نشده است !", "yellow");
    return false;
  }
  var action = "doEditCreateServiceRoute";
  var param = { action: action, srid: srid, rName: rName };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    $("#serviceRouteManageModal").modal("hide");
    $("body").removeClass("modal-open");
    $(".modal-backdrop").remove();
    showServiceRouteManageList();
  }
}

function personnelServiceRoute() {
  var action = "getPersonnelServiceRoute";
  var param = { action: action };
  var res = manageAjaxRequest(param);
  if (res != false) {
    $("#personnelServiceRouteBody").html("");
    $("#personnelServiceRouteBody").html(res);
    $("#personnelServiceRouteModal").modal("show");
  }
}

function doPersonnelServiceRoute() {
  var ch = $("#personnelServiceRoute-tableID").find("input:checked");
  var information = new Array();
  for (var c = 0; c < ch.length; c++) {
    var grade = ch[c].attributes.rid.value;
    var routeID = $("#personnelServiceRoute-" + grade).val();
    if (parseInt(routeID) == 0) {
      
      $("#personnelServiceRoute-"+grade).focus();
      $("#personnelServiceRoute-"+grade).addClass('has-error');
      notice1Sec("یک یا چند مسیر مشخص نشده است !", "yellow");
      return false;
    }
    var olid = $("#psrid-" + grade + "-Hidden").val();
    information[information.length] = [olid, routeID];
  }
  var myJsonString = JSON.stringify(information);
  var action = "doPersonnelServiceRoute";
  var param = { action: action, myJsonString: myJsonString };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    $("#personnelServiceRouteModal").modal("hide");
    $("body").removeClass("modal-open");
    $(".modal-backdrop").remove();
    showServiceRouteManageList();
  }
}

//******************** ثبت ناهار اضافه کار ********************

function showOvertimeLunchManageList(page) {
  if (typeof page == "undefined") {
    page = 1;
  }
  var sDate = $("#overtimeLunchManageSDateSearch").val();
  var eDate = $("#overtimeLunchManageEDateSearch").val();
  var personnel = $("#overtimeLunchManagePersonnelSearch").val();
  var restaurant = $("#overtimeLunchManageRestaurantSearch").val();
  var meal = $("#overtimeLunchManageMealSearch").val();
  var action = "showOvertimeLunchManageList";
  var param = {
    action: action,
    sDate: sDate,
    eDate: eDate,
    personnel: personnel,
    restaurant: restaurant,
    meal: meal,
    page: page,
  };
  var res = manageAjaxRequest(param);
  if (res != false) {
    $("#overtimeLunchManageBody").html("");
    $("#overtimeLunchManageBody").html(res);
  }
}

function getPersonnelOfUnit() {
  var unit = $("#overtimeLunchManageUnit").val();
  $("#overtimeLunchManagePersonnel").empty();
  var rstPOU = Main.personnelOfUnit(unit);
  for (var e = 0; e < rstPOU.length; e++) {
    $("#overtimeLunchManagePersonnel").append(
      $("<option>", {
        value: rstPOU[e]["RowID"],
        text: rstPOU[e]["Fname"] + " " + rstPOU[e]["Lname"],
      })
    );
  }
  $("#overtimeLunchManagePersonnel").selectpicker("deselectAll");
  $("#overtimeLunchManagePersonnel").selectpicker("refresh");
}

function createOvertimeLunchPersonnel() {
  $("#overtimeLunchManageDate").val("");
  $("#overtimeLunchManageUnit").val(0);
  $("#overtimeLunchManagePersonnel").empty();
  //personnelOfUnit();
  getPersonnelOfUnit();
  $("#overtimeLunchManagePersonnel").selectpicker("refresh");
  $("#overtimeLunchManageMeal").val(-1);
  $("#overtimeLunchPersonnelModal").modal("show");
}
function toggle_restaurant_div(input) {
  if ($(input).val() == 2) {
    $("#overtimeLunchManagerestaurant-div").hide();
    $("#overtimeLunchManagerestaurant").val(0);
  } else {
    $("#overtimeLunchManagerestaurant-div").show();
  }
}

function doCreateOvertimeLunchPersonnel() {
  var olDate = $("#overtimeLunchManageDate").val();
  var unit = $("#overtimeLunchManageUnit").val();
  var unit = 0;
  var meal = $("#overtimeLunchManageMeal").val();
  var personnel = $("#overtimeLunchManagePersonnel").val();
  var restaurant = $("#overtimeLunchManagerestaurant").val();
  var personnel = personnel.join(",");
  // if (parseInt(unit) == 0){
  //     notice1Sec("واحد مشخص نشده است !","yellow");
  //     return false;
  // }
  if (!parseInt(personnel.trim().length)) {
    notice1Sec("پرسنل مشخص نشده است !", "yellow");
    return false;
  }
  if (parseInt(meal) == -1) {
    notice1Sec("وعده مشخص نشده است !", "yellow");
    return false;
  }
  if (parseInt(meal) != 2) {
    if (parseInt(restaurant) == 0) {
      notice1Sec("رستوران مشخص نشده است!", "yellow");
      return false;
    }
  } else {
    restaurant = 0;
  }
  var action = "doCreateOvertimeLunchPersonnel";
  var param = {
    action: action,
    personnel: personnel,
    olDate: olDate,
    unit: unit,
    meal: meal,
    restaurant: restaurant,
  };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    $("#overtimeLunchManageUnit").val(0);
    $("#overtimeLunchManageMeal").val(-1);
    $("#overtimeLunchManagePersonnel").empty();
    $("#overtimeLunchManagePersonnel").selectpicker("refresh");
    showOvertimeLunchManageList();
  }
}

function createOvertimeLunchGuest() {
  $("#overtimeLunchGuestDate").val("");
  $("#overtimeLunchGuestUnit").val(0);
  $("#overtimeLunchGuestFName").val("");
  $("#overtimeLunchGuestLName").val("");
  $("#overtimeLunchGuestDesc").val("");
  $("#overtimeLunchGuestModal").modal("show");
}

function doCreateOvertimeLunchGuest() {
  var olDate = $("#overtimeLunchGuestDate").val();
  var unit = $("#overtimeLunchGuestUnit").val();
  var fname = $("#overtimeLunchGuestFName").val();
  var lname = $("#overtimeLunchGuestLName").val();
  var restaurant = $("#overtimeLunchManagerestaurantGest").val();

  var desc = $("#overtimeLunchGuestDesc").val();
  if (parseInt(unit) == 0) {
    notice1Sec("واحد مشخص نشده است !", "yellow");
    return false;
  }
  if (!parseInt(fname.trim().length)) {
    notice1Sec("نام میهمان مشخص نشده است !", "yellow");
    return false;
  }
  if (!parseInt(lname.trim().length)) {
    notice1Sec("نام خانوادگی میهمان مشخص نشده است !", "yellow");
    return false;
  }
  if (!parseInt(restaurant.trim().length)) {
    notice1Sec("رستوران  مشخص نشده است !", "yellow");
    return false;
  }
  var action = "doCreateOvertimeLunchGuest";
  var param = {
    action: action,
    olDate: olDate,
    unit: unit,
    fname: fname,
    lname: lname,
    desc: desc,
    restaurant: restaurant,
  };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    $("#overtimeLunchGuestUnit").val(0);
    $("#overtimeLunchGuestFName").val("");
    $("#overtimeLunchGuestLName").val("");
    $("#overtimeLunchGuestDesc").val("");
    showOvertimeLunchManageList();
  }
}

function createOvertimeLunchDetails() {
  $("#overtimeLunchDetailsDate").val("");
  $("#overtimeLunchDetailsBreadNum").val("");
  $("#overtimeLunchDetailsBody").html("");
  $("#overtimeLunchDetailsModal").modal("show");
}

function getLunchOfPersonnel() {
  var ddate = $("#overtimeLunchDetailsDate").val();
  var action = "getLunchOfPersonnel";
  var param = { action: action, ddate: ddate };
  var res = manageAjaxRequest(param);
  if (res != false) {
    $("#overtimeLunchDetailsBody").html("");
    $("#overtimeLunchDetailsBody").html(res[0]);
    $("#overtimeLunchDetailsBreadNum").val(res[1]);
  }
}

function changeRestaurantType() {
  var resID = $("#OvertimeLunchRestaurant-1").val();
  var ddate = $("#overtimeLunchDetailsDate").val();
  var action = "changeRestaurantType";
  var param = { action: action, ddate: ddate };
  var res = manageAjaxRequest(param);
  if (res != false) {
    for (var x = 2; x <= res; x++) {
      $("#OvertimeLunchRestaurant-" + x).val(resID);
    }
  }
}

// function changeFoodType() {
//     var resID = $("#OvertimeLunchFood-1").val();
//     var ddate = $("#overtimeLunchDetailsDate").val();
//     var action = "changeRestaurantType";
//     var param = {action:action,ddate:ddate};
//     var res = manageAjaxRequest(param);
//     if (res != false) {
//         for (var x = 2; x <= res; x++) {
//             $("#OvertimeLunchFood-"+x).val(resID);
//         }
//     }
// }
function changeFoodType(input) {
  //var resID = $("#OvertimeLunchFood-1").val();
  var resID = $(input).val();
  var counter_array = $(input).attr("id").split("-");
  var counter = counter_array[1];
  var ddate = $("#overtimeLunchDetailsDate").val();
  var action = "changeRestaurantType";
  var param = { action: action, ddate: ddate };
  var res = manageAjaxRequest(param);
  if (res != false) {
    for (var x = counter; x <= res; x++) {
      $("#OvertimeLunchFood-" + x).val(resID);
    }
  }
}

async function changeDrinkType(input) {
  var conf = await swal_custom(
    "",
    "تغییرات بر روی فیلد های  قبلی و بعدی نوشیدنی ها اعمال گردد؟",
    "c",
    "خیر "
  );
  if (!conf) {
    return false;
  }
  var resID = $(input).attr("id");
  var input_id_array = resID.split("-");
  var input_index = parseInt(input_id_array[1]) - 1;
  $("#lunchOfPersonnelHtm-tableID")
    .find('select[id^="OvertimeLunchDrink"]')
    .each(function () {
      var index_arr = $(this).attr("id").split("-");
      var index = index_arr[1];
      var diabled = $(this).attr("disabled") ? 1 : 0;
      // if(diabled==0 && index>=input_index){
      //     $(this).val($(input).val());
      // }
      if (diabled == 0 && index >= input_index) {
        $(this).val($(input).val());
      }
    });

  // var ddate = $("#overtimeLunchDetailsDate").val();
  // var action = "changeRestaurantType";
  // var param = {action:action,ddate:ddate};
  //  var res = manageAjaxRequest(param);
  //if (res != false) {
  //     for (var x = input_index; x <= res; x++) {
  //         $("#OvertimeLunchDrink-"+x).val(resID);
  //     }
  // //}
}

function doCreateOvertimeLunchDetails() {
  var BreadNum = $("#overtimeLunchDetailsBreadNum").val();
  var ch = $("#lunchOfPersonnelHtm-tableID").find("input:checked");
  var information = new Array();
  for (var c = 0; c < ch.length; c++) {
    var grade = ch[c].attributes.rid.value;
    var food_status = ch[c].attributes.food_status.value;
    var restaurantID = $("#OvertimeLunchRestaurant-" + grade).val();
    var foodID = $("#OvertimeLunchFood-" + grade).val();
    var drinkID = $("#OvertimeLunchDrink-" + grade).val();
    var olid = $("#olid-" + grade + "-Hidden").val();
    information[information.length] = [
      olid,
      restaurantID,
      foodID,
      drinkID,
      food_status,
    ];
  }
  var myJsonString = JSON.stringify(information);
  var action = "doCreateOvertimeLunchDetails";
  var param = {
    action: action,
    myJsonString: myJsonString,
    BreadNum: BreadNum,
  };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    $("#overtimeLunchDetailsModal").modal("hide");
    $("body").removeClass("modal-open");
    $(".modal-backdrop").remove();
    showOvertimeLunchManageList();
  }
}

function deleteOvertimeLunch(olid) {
  var action = "deleteOvertimeLunch";
  var param = { action: action, olid: olid };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    showOvertimeLunchManageList();
  }
}

function printOvertimeLunch() {
  var sDate = $("#overtimeLunchManageSDateSearch").val();
  var eDate = $("#overtimeLunchManageEDateSearch").val();
  var personnel = $("#overtimeLunchManagePersonnelSearch").val();
  var restaurant = $("#overtimeLunchManageRestaurantSearch").val();
  var meal = $("#overtimeLunchManageMealSearch").val();
  var action = "printOvertimeLunch";
  var param = {
    action: action,
    sDate: sDate,
    eDate: eDate,
    personnel: personnel,
    restaurant: restaurant,
    meal: meal,
  };
  var res = manageAjaxRequest(param);
  if (res != false) {
    $("#hiddenAgencyPrintBody").html("");
    $("#hiddenAgencyPrintBody").html(res);
    $(".demoOvertimeLunch").printThis({
      //base: "http://localhost:8012/BOM/administrator.php"
      base: base_url,
    });
  }
}

function getOvertimeLunchExcel() {
  var sDate = $("#overtimeLunchManageSDateSearch").val();
  var eDate = $("#overtimeLunchManageEDateSearch").val();
  var personnel = $("#overtimeLunchManagePersonnelSearch").val();
  var restaurant = $("#overtimeLunchManageRestaurantSearch").val();
  var meal = $("#overtimeLunchManageMealSearch").val();
  var action = "getOvertimeLunchExcel";
  var param = {
    action: action,
    sDate: sDate,
    eDate: eDate,
    personnel: personnel,
    restaurant: restaurant,
    meal: meal,
  };
  var res = manageAjaxRequest(param);
  if (res != false) {
    window.open(res);
  }
}

function finalTickLunch() {
  $("#finalTickLunchSDate").val("");
  $("#finalTickLunchEDate").val("");
  $("#finalTickLunchModal").modal("show");
}

function doFinalTickLunch() {
  var sDate = $("#finalTickLunchSDate").val();
  var eDate = $("#finalTickLunchEDate").val();
  if (!parseInt(sDate.trim().length)) {
    notice1Sec("بازه زمانی درست انتخاب نشده است !", "yellow");
    return false;
  }
  if (!parseInt(eDate.trim().length)) {
    notice1Sec("بازه زمانی درست انتخاب نشده است !", "yellow");
    return false;
  }
  var action = "doFinalTickLunch";
  var param = { action: action, sDate: sDate, eDate: eDate };
  var res = manageAjaxRequest(param);
  if (res != false) {
    notice1Sec(res, "green");
    $("#finalTickLunchSDate").val("");
    $("#finalTickLunchEDate").val("");
    showOvertimeLunchManageList();
  }
}

function getUnitOvertimeLunchExcel() {
  var sDate = $("#overtimeLunchManageSDateSearch").val();
  var eDate = $("#overtimeLunchManageEDateSearch").val();
  var action = "getUnitOvertimeLunchExcel";
  var param = { action: action, sDate: sDate, eDate: eDate };
  var res = manageAjaxRequest(param);
  if (res != false) {
    window.open(res);
  }
}
