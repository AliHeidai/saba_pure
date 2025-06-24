function display_saba_panel_admin(){
    var html=`<nav class="navbar navbar-expand-sm bg-light"> 

        <ul class="navbar-nav ml-auto"> 
            <li class="nav-item"> 
                <a class="nav-link" href="#"> 
                  مدیریت کاربران 
                </a> 
                
            </li> 
            <li class="nav-item"> 
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                  ارسال پیامک 
                </a> 
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#">اطلاعات اولیه</a></li>
          <li><a class="dropdown-item" onclick="send_sms_saba_users()">ارسال پیامک به کاربران سبا</a></li>
           <li><a class="dropdown-item" onclick="send_sms_personels()">ارسال پیامک به پرسنل سبا</a></li>
            <li><a class="dropdown-item" href="#">ارسال پیامک به سایرین </a></li>
        </ul>
            </li> 
            <li class="nav-item"> 
                <a class="nav-link" href="#"> 
                  سایر تنظیمات 
                </a> 
            </li> 
        </ul> 
    </nav><div class="container-flouid" id="panel_content"></div>`;

    $("#myTabContent").html(html);
}

function send_sms_personels(){
    var html="";
    html=`
            <div class="row m-4">
            <div class="col-md-2 p-4">

                <button onclick="send_simple_sms()" class="btn col-md-12 active">ارسال پیام یکسان برای همه</button>
                <button button class="btn col-md-12">ارسال پیام   p2p</button>
            </div>
             <div class="col-md-10" id="sms_content"></div>
            </div>
    `
    $("#panel_content").html(html);

}

function send_simple_sms(){
    var sms_info=get_sms__clients_info();
    var opt=`<option value='0'>انتخاب نمایید</option>`;
    for(k in sms_info){
        opt+=`<option value='${sms_info[k]['RowID']}'> ${sms_info[k]['FullName']}</option>`
    }
    var form=`<form class="w-50 m-auto">
  <div class="form-group row">
    <label for="from_number" class="col-sm-4 col-form-label">انتخاب سر شماره</label>
    <div class="col-sm-8">
      <select id="from_number" class="form-control"><option value="983000505">983000505</option></select>
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-4 col-form-label">انتخاب پرسنل</label>
    <div class="col-sm-8">
      
       <select class="shadow-color" multiple="" data-selected-text-format="count" data-live-search="true" id="sms_reception_personel" tabindex="-98">${opt}</select>
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-4 col-form-label">متن پیام </label>
    <div class="col-sm-8">
      <textarea class="form-control" id="sms_msg"></textarea>
    </div>
  </div>
  <div class="form-group row">
    <button onclick="do_send_simple_sms();" type="button" class="btn btn-primary">ارسال پیام</button>
  </div>
 
</form>`;
$("#sms_content").html(form);
select_data_picker( $("#sms_reception_personel"))
}

function get_sms__clients_info(){
    var action="get_sms__clients_info"
    var param={action:action}
    var res=ajaxHandler(param);
    if(res){
        return res;
    }
    
}

async function do_send_simple_sms(){
    var action ='do_send_simple_sms';
    var receptions=$("#sms_reception_personel").val().join(',');
    var from_number=$("#from_number").val();
    var msg=$("#sms_msg").val();
    var param = {action:action,receptions:receptions,from_number:from_number,msg:msg}
    var res=await manageAjaxRequestCustom(param);
    console.log(res)
}