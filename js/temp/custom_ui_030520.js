
function create_form_element(selector,attrs_array){
    var element="";
    switch(attrs_array['type']){
        case "button":
        case "submit":
            var attrs="";
            var title = attrs_array['title'] ? attrs_array['title']:"";
            var parent=attrs_array['parent'] && attrs_array['parent']==1 ?attrs_array['parent']:0;
            for(k in attrs_array){
                if(k !="title"&& k!="parent")
                    attrs+=` ${k}="${attrs_array[k]}" `;
            }
            if( parent==0){
                element+=`<input value="${title}" ${attrs}>`
            }
            else
            {
               // alert('tedst');
                element+=`<div style="float:left;width:100%;height: 100%" class="row form-group p-0 bg-transparent border-0">`
                element+=`<input value="${title}" ${attrs}></div>`
            }
            break;
        case "btn":
            var attrs="";
            var title = attrs_array['title'] ? attrs_array['title']:"";
            var parent=attrs_array['parent'] && attrs_array['parent']==1 ?attrs_array['parent']:0;

            attrs_array['type']="button";
            for(k in attrs_array){
                if(k !="title"&& k!="parent")
                    attrs+=` ${k}="${attrs_array[k]}" `;

            }
            if(parent=='0'){
                element+=`<button  ${attrs}>${title}</button>`
            }
            else
            {

                element+=`<div style="float:left;width:100%" class="row form-group p-0 bg-transparent border-0">`
                element+= element+=`<button  ${attrs}>${title}</button></div>`
            }
            break;
        case "text":
        case "email":
        case "number":
        case "password":

            var attrs="";
            var row_class="";
            var title = attrs_array['title'] ? attrs_array['title']:"";
            for(k in attrs_array){
                if(k !="title" && k!="row_class")
                    attrs+=` ${k}="${attrs_array[k]}" `;
            }
            element += `<div class="row  bg-transparent border-0">`
            if (title) {
                row_class= attrs_array['row_class'] ? attrs_array['row_class']:"col-md-8"
                element += `<label class="bg-transparent border-0 form-control col-md-4">${title}</label>`
                element += `<input class="form-control ${row_class}"  ${attrs}></div>`
            }
            else{
                element += `<input class="form-control "  ${attrs}></div>`
            }
            break;
        case "textarea":

            var attrs="";
            var title = attrs_array['title'] ? attrs_array['title']:"";
            for(k in attrs_array){
                if(k !="title")
                    attrs+=` ${k}="${attrs_array[k]}" `;
            }
            element += `<div  style="padding-block:5px" class="row  bg-transparent border-0 ">`
            if (title) {
                element += `<label class="bg-transparent border-0 form-control col-md-4">${title}</label>`
                element += `<textarea class="col-md-8 form-control "  ${attrs}></textarea></div>`
            }
            else{
                element += `<textarea class="form-control"  ${attrs}></textarea></div>`
            }
            break;
        case "hidden":
            var attrs="";
            var title = attrs_array['title'] ? attrs_array['title']:"";
            for(k in attrs_array){
                if(k !="title")
                    attrs+=` ${k}="${attrs_array[k]}" `;
            }
            element+=`<input  ${attrs}>`
            break;
        case "txt_info":
            var attrs="";
            var text=attrs_array['text'];
            for(k in attrs_array){
                if(k!="text")
                    attrs+=` ${k}="${attrs_array[k]}" `;
            }
            element+=`<div class="form-control bg-transparent border-0"><p ${attrs}>${text}</p></div>`
            break;

        case "form":
            var attrs="";
            for(k in attrs_array){
                if(k!="text")
                    attrs+=` ${k}="${attrs_array[k]}" `;
            }
            element+=`<form ${attrs}></form>`
            break;

        case "section":
            var attrs="";
            for(k in attrs_array){
                if(k!="text")
                    attrs+=` ${k}="${attrs_array[k]}" `;
            }
            element+=`<section ${attrs}></section>`
            break;

        case "div":
            var attrs="";
            for(k in attrs_array){
                if(k!="text")
                    attrs+=` ${k}="${attrs_array[k]}" `;
            }
            element+=`<div ${attrs}></div>`
            break;
        case "line":
            var attrs="";
            for(k in attrs_array){
                if(k!="text")
                    attrs+=` ${k}="${attrs_array[k]}" `;
            }
            element+=`<br>`
            break;
        case "select":
            var attrs="";
            var title=attrs_array['title'];
            var options=attrs_array['options'];
            for(k in attrs_array){
                if(k!="title" && k!="options")
                    attrs+=` ${k}="${attrs_array[k]}" `;
            }
            element+=`<div class="row  bg-transparent border-0">`
            if(title){
                element+=`<label class="bg-transparent border-0 form-control col-md-4">${title}</label>`
                element+=`<select class="form-control col-md-8" ${attrs}>${options}</select></div>`
            }
            else{
                element+=`<select class="form-control "  ${attrs}>${options}</select></div>`
            }

            break;
        case "button_group":
            var element='<div style="display: flex;justify-content: space-between;align-items: center;" class=" row bg-transparent border-0">';
            elements=attrs_array['elements']
            for(l in elements){
                var attrs="";
                var title = elements[l]['title'] ? elements[l]['title']:"";
                for(k in elements[l]){

                    if(k !="title")
                     attrs+=` ${k}="${elements[l][k]}" `;
                }
            
                element+=`<input  value="${title}" ${attrs}>`
            }
            element+="</div>";
            break;
        // case "text_btn":
        //     var element='<div style="display: flex;justify-content: space-between;align-items: center;" class=" row bg-transparent border-0">';
        //     elements=attrs_array['elements']
        //     for(l in elements){
        //         var attrs="";
        //         var title = elements[l]['title'] ? elements[l]['title']:"";
        //         for(k in elements[l]){
        //
        //             if(k !="title")
        //                 attrs+=` ${k}="${elements[l][k]}" `;
        //         }
        //
        //         element+=`<input  value="${title}" ${attrs}>`
        //     }
        //     element+="</div>";
        //     break;
    }
    $(selector).append(element)
}
function change_label_style(input,enter=0){
    if(enter==0)
    {
        var parent=$(input).parent().css('background','green')
        $(parent).children('label').css('color','red !important')
        $(parent).children('.format_up').css({'top':"-10px",'transaction':'1s','z-index':'10','background':'#fff !important','padding-inline':'10px'})
    }
}
function create_table(selector,data_array,style="",thead=0,border=1){
    var container="";
    var className="";
    if(border==1) {className+="table borderd  border-striped";}
    container+=`<div class="w-100 p-2"><table style="${style}" class="${className}">`
    if(data_array.length>0)
    {
        if(thead==1 && data_array.length>1 ){
            container+="<thead><tr>" 
            for(th in data_array[0]){
                container+=`<th>${data_array[0][th]}</th>` 
            }
            container+="</tr></thead>"
            data_array.splice(0, 1);
        }
        container+="<tbody>" 
        for(k in data_array){
            container+="<tr>" 
            for(l in data_array[k]){
                container+=`<td>${data_array[k][l]}</td>`   
            }
            container+="</tr>" 
        }
        container+=`</tbody></table>`
    }
    else{
        container+=`<p style="padding:2rem;margin:2rem auto;color:red;width:100%;text-align:center;">موردی ثبت نشده است</p>` ;
    }
    container+="</div>";
    $(selector).append(container);
}

 function set_steps_form(selector_form,steps,current_step,callback_array,section_ides_array,active_steps=1){
    $(selector_form).children('section').hide();
    $(selector_form).children('section:nth('+current_step+')').show();

    var steps_ui="";
    var pub_circle_style=
    `width: 40px;
    height: 40px;
    background-color: #e5e3e3;
    color: #fff;
    border-radius: 50%;
    text-align: center;
    line-height: 40px;
    cursor: pointer;`;
    var pub_line_style=
    `
        width: 60px;
        height: 1px;
        background-color: #e5e3e3;
    `;
    var steps_parent_style=
    `
        display: flex;
        justify-content: center;
        align-items: center;
    `;
    var line=0;
    for(var i=0;i<parseInt(steps);i++){
        line +=1;
        var line_ui="";
        var current_class_step="";
        if(parseInt(line)!=parseInt(steps))
        {
            line_ui=` <span class="custom_line"  style="${pub_line_style}"></span>`;
        }
        
        if(i==parseInt(current_step)){
            current_class_step="active_step";
        }
        steps_ui+=` <span area_control_box="${section_ides_array[i]}" class="circle_step ${current_class_step}" style="${pub_circle_style}"> ${line}</span>${line_ui}`
    }
    //$(selector_form).children('section:nth('+current_step+')').append(`<button step="${current_step}" type="button" id="step_btn_${current_step}" style="float:left" class="btn btn-primary">تایید و ادامه</button>`);
    $(selector_form).children('section').each(function(index){
        $(this).find('.footer').append(`<button step="${parseInt(index)+1}" type="button" id="step_btn_${index}" style="float:left" class="btn btn-primary">تایید و ادامه</button>`)

        $("#step_btn_"+index).on('click',async function(e){//-------------- for transfer from next step

            var current_step=parseInt(index);
            var next_step = parseInt(current_step)+1;
            if(typeof callback_array[index] ==="function")
            {
                var  res= await  callback_array[index]();

                if(res==false || res=="undefined"){

                    return false;
                }
                else
                {
                    if(next_step==steps){
                        return false;
                    }
                    $(e.target).parents('form').children('section').hide(1000);
                    $(e.target).parents('form').children("section:nth('"+next_step+"')").show(1000);

                        $(e.target).parents('form').children().find('span.circle_step:nth(' + next_step + ')').addClass("active_step");
                        $(e.target).parents('form').children().find('span.custom_line:nth(' + current_step + ')').addClass("active_step");
                    }
                }

        });
    });
    $(selector_form).prepend(`<div class='custom_form_steps' style="${steps_parent_style}">${steps_ui}</div>`);
    $('.circle_step').each(function () {
        $(this).on('click', function (evt) {
            if($(evt.target).hasClass('active_step')) {
                if (!$("#" + $(this).attr('area_control_box')).is(":visible")) {
                    $(evt.target).parents('form').children('section').hide(500);
                    $("#" + $(this).attr('area_control_box')).show(500);
                    if(active_steps>1){
                        if($(this).attr('area_control_box')=="form_section_inq_two") {
                            if ($("#inq_id_hidden") && $("#inq_id_hidden").val() > 0) {
                                add_inq_row_good($("#add_good"), 1)
                                handler_seller_data();

                            }
                        }
                        if($(this).attr('area_control_box')=="form_section_inq_three")
                        {//add_inq_row_good($("#add_good"),)
                            }

                    }
                }
            }
        })
    })
     if(active_steps>0){
         for(var i=1;i<active_steps;i++) {

             $("#form_create_export_inq").children().find('span.circle_step:nth(' + i + ')').addClass("active_step");
             $("#form_create_export_inq").children().find('span.custom_line:nth(' + (i-1) + ')').addClass("active_step");
         }
     }
}

function create_tab(selector,tab_array)
{
    var navbar=`<div class="custom_navbar"> <ul class="nav nav-tabs">`;
    var attrs="";
    var sections="";
    var section_id="";
    var display="";
    var className="";
    var span_id="";
    for(k in tab_array)
    {
        var attrs_arr=tab_array[k]['attrs'];
        section_id="";
        if(attrs_arr)
        {
            attrs="";
            for(n in attrs_arr){
                if(n=="id"){
                    section_id = attrs_arr[n]+"-section";
                    span_id=attrs_arr[n]+"_"+'count';
                }
                if(n=="class"){
                    className = attrs_arr[n];
                }
                attrs+=`${n} = "${attrs_arr[n]}" `;
            }
        }      
        navbar+=
        `<li class="nav-item" style="position:relative;width:auto;padding-inline:10px" >
            <a href="#" ${attrs} >${tab_array[k]['title']}</a><span style="position: absolute;background: #f75e65;width: 20px;height: 20px;border-radius: 100%;bottom: 0;left: 6px;color:#fff;text-align:center;display:none" class="count_inqs" id=${span_id}>0</span>
        </li>`
        var class_array=className.split(" ");
        if($.inArray("active-tab", class_array) !== -1){
            display='block';
        }
        else{
            display='none';
        }
        sections+=`<section style="width:100%;min-height:auto;display:${display};background:#fff" class="tab-section" id="${section_id}"></section>`
    }
    navbar+=`</ul><div>`;

    $(selector).append(navbar)
    $(selector).append(sections)
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
        credentials: 'include' ,
        headers: new Headers({
            'Content-Type': 'application/x-www-form-urlencoded', // <-- Specifying the Content-Type
        }),
        body: prms
    });
    const content = await rawResponse.json().then((data)=>{

      res = data;


    }).catch((e)=>{
        res={msg_type: 'error', message: 'خطای غیر منتظره '};

    });
    if(loading==1){
        remove_loading(formData.action);
    }
    return res;


}

function go_next(input,status,last_page){
    var selector=$(input).parents('section');
    var next_page = parseInt($('.paginate_input').val())+1
    var inq_name=$("#inq_name_search").val();
    var inq_code=$("#inq_code_search").val();
    var inq_from=$("#inq_from_date_search").val();
    var inq_to=$("#inq_to_date_search").val();
    var inq_reciever=$("#inq_reciever_search").val();
    if((inq_from =="" && inq_to=="") || (inq_from && inq_to)){
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

        if(next_page>last_page){
            return false;
        }
        get_inquires(status,selector,next_page,other_params)
    }
    else{
        return false;
    }
}

function go_previous(input,status){
    var selector=$(input).parents('section');
    var pre_page = parseInt($('.paginate_input').val())-1
    if(pre_page<1){
        return false;
    }
    var inq_name=$("#inq_name_search").val();
    var inq_code=$("#inq_code_search").val();
    var inq_from=$("#inq_from_date_search").val();
    var inq_to=$("#inq_to_date_search").val();
    var inq_reciever=$("#inq_reciever_search").val();

    if((inq_from =="" && inq_to=="") || (inq_from && inq_to)){
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

    }
    else{
        return false;
    }
    //var other_params={inq_name:inq_name,inq_code:inq_code,inq_from:inq_from,inq_to:inq_to,inq_reciever:inq_reciever}
    get_inquires(status,selector,pre_page,other_params)
}
function go_first(input,status){
    var selector=$(input).parents('section');
    var next_page = 1;
    if(next_page<1){
        return false;
    }
    var inq_name=$("#inq_name_search").val();
    var inq_code=$("#inq_code_search").val();
    var inq_from=$("#inq_from_date_search").val();
    var inq_to=$("#inq_to_date_search").val();
    var inq_reciever=$("#inq_reciever_search").val();
    if((inq_from =="" && inq_to=="") || (inq_from && inq_to)){
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
    }
    else{
        return false;
    }
    //var other_params={inq_name:inq_name,inq_code:inq_code,inq_from:inq_from,inq_to:inq_to,inq_reciever:inq_reciever}
    get_inquires(status,selector,next_page,other_params)
}
function go_last(input,status,last_page){
    var selector=$(input).parents('section');
    if(last_page<1){
        return false;
    }
    var inq_name=$("#inq_name_search").val();
    var inq_code=$("#inq_code_search").val();
    var inq_from=$("#inq_from_date_search").val();
    var inq_to=$("#inq_to_date_search").val();
    var inq_reciever=$("#inq_reciever_search").val();

    if((inq_from =="" && inq_to=="") || (inq_from && inq_to)){
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
    }
    else{
        return false;
    }
    //var other_params={inq_name:inq_name,inq_code:inq_code,inq_from:inq_from,inq_to:inq_to,inq_reciever:inq_reciever}
    get_inquires(status,selector,last_page,other_params)
}
function go_to_page(input,status,last_page){
    var selector=$(input).parents('section');
    var current_page=$(input).val();
    if(parseInt(current_page)>last_page || parseInt(current_page)<1){
        return false;
    }
    var inq_name=$("#inq_name_search").val();
    var inq_code=$("#inq_code_search").val();
    var inq_from=$("#inq_from_date_search").val();
    var inq_to=$("#inq_to_date_search").val();
    var inq_reciever=$("#inq_reciever_search").val();

    if((inq_from =="" && inq_to=="") || (inq_from && inq_to)){
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
    }
    else{
        return false;
    }

    get_inquires(status,selector,current_page,other_params)

}

// async function go_to_next_step(input,current_step,callback){
//     var next_step = parseInt(current_step)+1;
//     if (typeof callback === "function") {
//         var res = await callback();
//
//         if (res == false) {
//             return false;
//         } else {
//
//             $(input).parents('form').children('section').hide(1000);
//             $(input).parents('form').children("section:nth('" + next_step + "')").show(1000);
//             $(input).parents('form').children().find('span.circle_step:nth(' + next_step + ')').addClass("active_step");
//             $(input).parents('form').children().find('span.custom_line:nth(' + current_step + ')').addClass("active_step");
//         }
//     }
//
// }



