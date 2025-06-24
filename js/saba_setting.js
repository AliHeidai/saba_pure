async function get_sab_setting (){
   var action= "get_saba_settings";
   var info=await manageAjaxRequestCustom({action:action})
  document.querySelector("#SabaSetting").innerHTML=info.data;
}

async function set_crud_enable(input,type){
    var action="set_crud_enable";
    var crud_status=0;
    if(input.checked){
        crud_status=1;
    }
    var res=await manageAjaxRequestCustom({action:action,crud_type:type,crud_status:crud_status});
    if(res.res=="true"){
        notice1Sec('عملیات با موفقیت انجام شد','green',auto_close=2000)
        document.querySelector("#SabaSetting").innerHTML=res.data;
    }
    else{
        notice1Sec(res.data,'red',auto_close=2000)
    }
}