<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
class OrganizationalProcedures{

    public function __construct(){
        // do nothing
    }

    public function OrganizationalProceduresHtml(){
        $db=new DBi();
        $ut=new Utility();
        $add_btn_units="";
        $acm=new acm();
        $sql= "SELECT * FROM organization_department where isEnable =1";
        $res=$db->ArrayQuery($sql);
        $unit_pro_count=array();
        $sql_pro_count="SELECT count(unit_id) as p_count,unit_id from procedures where `status`=1 group by unit_id";
        $res_count=$db->ArrayQuery($sql_pro_count);
        foreach($res_count as $key_c=>$value){
            $unit_pro_count[$value['unit_id']]=$value['p_count'];
        }

        $htm='<div class="container-flouid"><div  id="departments">
                <div style="background-color: #f8f9fa !important;width: 100%;">
                <div class="procedures_header row">
                <div class="col-md-12">
                    <h5 style="text-align:center;font-family:IranSans;padding:20px;"> مستندات سازمانی </h5>
                </div>
                <div class="col-md-12" style="display: flex;justify-content: center;align-items: center;">';
        if ($acm->hasAccess('add_organization_department')) 
        {
            $add_btn_units='<a style="color:#fff; margin:10px" class="btn btn-success" onclick="add_organization_department()">افزودن واحد جدید</a>';
          
            $add_btn_units.='<a style="color:#fff; margin:10px" class="btn btn-success" onclick="search_organization_department()"> جستجوی رویه ها </a>';
        }
        $htm.=$add_btn_units;
        $htm.=
            '</div> 
                </div>
		    </div>
		<div class="procedures_box">';
       
        for($i= 0;$i<count($res);$i++){
            $htm.='<div  style="height:10rem;margin:0.7rem; display:flex;justify-content:center;align-items:center;" class="col-md-2">';
                        //<span  class="register_procedure_count">'.($this->get_unit_procedure_count($res[$i]['RowID'])>0? $this->get_unit_procedure_count($res[$i]['RowID'])." رویه جاری شده":"").'</span>
            $htm.='<input type="hidden" id="unit_name_'.$res[$i]['RowID'].'" value="'.$res[$i]['department_name'].'">
                        <button   onclick="open_department_procedures('.$res[$i]['RowID'].')">'.$res[$i]['department_name'].'<p class="p_title_count"> '.$unit_pro_count[$res[$i]['RowID']].' مورد  جاری ثبت شده</p></button>
                    </div>';
        }
        $htm.="</div></div></div>";
        return $htm;
    }

    public function get_unit_procedure_count($unit_id){
        $db=new DBi();
        $procedure_count="SELECT RowID from procedures where status=1 AND unit_id={$unit_id}";
        $res=$db->ArrayQuery($procedure_count);
        return count($res);
    }
    
    public function SearchOrganizationalProceduresHtml(){
        $db=new DBi();
        $ut=new Utility();
        $add_btn_units="";
        $acm=new acm();
        $sql= "SELECT * FROM organization_department where isEnable =1";
        $res=$db->ArrayQuery($sql);
        $htm='<div class="container-flouid"><div  id="departments"><div style="background-color: #f8f9fa !important; width: 100%; z-index: 1000;">
                <div class="row">
                    <div class="col-md-2" style="display: flex;justify-content: center;align-items: center;">';
        $htm.=
                '</div>
                <div class="col-md-10">
                    <h5 style="text-align:center;font-family:IranSans;padding:20px;">  جستجوی رویه های سازمانی  </h5>
                </div>
                </div>
		    </div>
		<div  class="procedures_box">';
       
        for($i= 0;$i<count($res);$i++){
            $htm.='<div  style="height:10rem;margin:0.7rem; display:flex;justify-content:center;align-items:center;" class="col-md-2"><input type="hidden" id="unit_name_'.$res[$i]['RowID'].'" value="'.$res[$i]['department_name'].'"><button   onclick="search_department_procedures('.$res[$i]['RowID'].')">'.$res[$i]['department_name'].'</button></div>';
        }
        $htm.="</div></div></div>";
        return $htm;
    }

    public function save_organization_department($dep_name,$dep_desc){ // ذخیره موقت  در جدول  procedures
        $db=new DBi();
        $curr_date=date("Y-m-d H:i:s");
        $ut=new Utility();
        $insert_sql="insert into organization_department  (`department_name`,`description`,`created_datetime`,`userid`) VALUES('{$dep_name}','{$dep_desc}','{$curr_date}','{$_SESSION['userid']}')";
     
        $res=$db->Query($insert_sql);
        if($res){
            return 1;
        }
        else{
            return 0;
        }
    }

    public function save_organization_procedure_type($procedure_type,$procedure_type_desc,$procedure_allowed_admins){
      
        $db=new DBi();
        $curr_date=date("Y-m-d H:i:s");
        $ut=new Utility();
        //$ut->fileRecorder('save_organization_procedure_type1');
        //$ut->fileRecorder($procedure_allowed_admins);
       // $procedure_allowed_admins=json_encode($procedure_allowed_admins[0]);
        //$ut->fileRecorder('save_organization_procedure_type2');
        $insert_sql="insert into procedure_type (`procedure_type_name`,`description`,`created_datetime`,`user_id`,`allowed_admins`) VALUES('{$procedure_type}','{$procedure_type_desc}','{$curr_date}','{$_SESSION['userid']}'";
        if(!empty($procedure_allowed_admins)){
        $insert_sql.="',{$procedure_allowed_admins}')";
        }
        else{
            $insert_sql.=",NULL)";
        }
       // $ut->fileRecorder($insert_sql);
        $res=$db->Query($insert_sql);
        if($res){
            return 1;
        }
        else{
            return 0;
        }
    }

    public function save_unit_procedure($procedure_name,$unit_id,$procedure_type){
        $db=new DBi();
        $ut=new Utility();
        $regester_date=date("Y-m-d H:i:s");
        $procedure_code=date("Y").strtotime($regester_date);
        $sql="insert into procedures (procedure_name,unit_id,user_id,procedure_type,register_date,status,procedure_code) 
                values('{$procedure_name}','{$unit_id}','{$_SESSION['userid']}','{$procedure_type}','{$regester_date}',10,'{$procedure_code}')";
        $res=$db->query($sql);
        if($res){
            $last_id_query="SELECT RowID FROM procedures  ORDER BY RowID DESC LIMIT 1";
            $lastID=$db->ArrayQuery($last_id_query);
            return $lastID[0]['RowID'];
        }
        else{
            return 0;
        }
    }
    public function manage_attach_procedure_file($ProcedureRowID){
        $db=new DBi();
        $acm = new acm();
        $sql="SELECT * FROM procedures_file WHERE procedure_id= {$ProcedureRowID} AND isEnable=1";
        $file_res=$db->ArrayQuery($sql);

        $sql_history="SELECT * FROM procedures_file WHERE procedure_id= {$ProcedureRowID} AND isEnable=0";
        $file_res_history=$db->ArrayQuery($sql_history);
           //---------------------------------------- چک کردن  دسترسی برای پیوست فایل-----------------------------------------------------
        $add_file_btn="";
           if ($acm->hasAccess('attach_unit_procedure_file')) {
            $add_file_btn='<button style="position:absolute;top:0" onclick="toggle_form(\'procedure_entry_file\',this)" class="btn btn-primary"><i class="fa fa-plus"></i ></button><br><br>';
            $html.=$add_file_btn;
       }
        //---------------------------------------- چک کردن  دسترسی برای پیوست فایل-----------------------------------------------------

        $html.='<table class="table table-bordered table-striped"><tr>
        <td>#</td>
        <td>عنوان  فایل</td>
        <td> توضیحات</td>
        <td>download</td>';
        //---------------------------------------- چک کردن  دسترسی برای حذف فایل-----------------------------------------------------
      
        if ($acm->hasAccess('remove_unit_prosedure_file')) {
             $html.='<td>حذف فایل</td>';
        }
         //---------------------------------------- چک کردن  دسترسی برای حذف فایل-----------------------------------------------------
        $html.='</tr>'
        ;
        if(count($file_res)>0){
        for($i=0;$i<count($file_res);$i++){
            $html.='<tr>
                <td>'.($i+1).'</td>
                <td>'.$file_res[$i]['file_title'].'</td>
                <td>'.$file_res[$i]['description'].'</td>
                <td><button class="btn btn-primary"><a style="color:#fff" href="'.PROCEDURES_DIR.'/'.$file_res[$i]['file_name'].'" target="_blank"><i class="fa fa-download"></i></a></button></td>';
                $acm = new acm();
                if ($acm->hasAccess('remove_unit_prosedure_file')) {
                    $html.='<td><button onclick="cancellation_procedure_file('.$file_res[$i]['RowID'].','.$ProcedureRowID.')" class="btn btn-danger"><i class="fa fa-trash"></i> ابطال</button></td>';//,\''.$file_res[$i]['file_name'].\'')">Download</dutton></td>
                }
                // <td><button onclick="downloadFile(\''.$file_res[$i]['file'].'\')">Download</button></td></tr>';//,\''.$file_res[$i]['file_name'].\'')">Download</dutton></td>
           // </tr>';
                $html.="</tr>";
            }
            $html.="</table>";
        }
    else{
            $html= $add_file_btn;
            $html.='<p style="color:red">موردی ثبت نشده است</p>';
        }
        return $html.'<input type="hidden" id="procedures_files_count" value="'.count($file_res).'"><input type="hidden" id="procedures_history_count" value="'.count($file_res_history).'">';
    }
   
    //public function attach_procedure_file($cid,$info,$file_title,$files,$file_code){
   //************************************************************************************************************************ */
    public function attach_procedure_file($p_id,$file_info,$file_name,$files,$start_date,$start_time,$start_now,$form_number,$level_of_changes,$current_procedure_status,$last_review_date,$form_description,$insert_mode,$reason_reversion="",$uploaded_file=[])
    {
        
        $ut=new Utility();
        $last_review_date=$ut->jal_to_greg($last_review_date);
        $cid=$p_id;
        $db = new DBi(); 
        $get_procedure_info="SELECT `procedure_name`, `unit_id`,`procedure_type`,procedure_code FROM  procedures WHERE RowID={$cid}";
        $res=$db->ArrayQuery($get_procedure_info);
        $procedure_type=$res[0]['procedure_type'];
        if($start_now==1){
            $start_date=date('Y-m-d');
            $start_time=date('H:i:s');
        }
        else
        {
            $start_date=$ut->jal_to_greg($start_date);  
        }
        if($insert_mode==1)
        {
            //-------------------------------------reversion --------------------------------------------
           // error_log("************1*************");

            
            $unit_id=$res[0]['unit_id'];
            $procedure_type=$res[0]['procedure_type'];
            $procedure_name=$res[0]['procedure_name'];
            $procedure_code=$res[0]['procedure_code'];
            $current_date=date('Y-md H:i:s');
            $new_start_date_time = date('Y-m-d H:i:s', strtotime("$start_date $start_time"));
            $diff=strtotime($new_start_date_time)-strtotime($current_date);
            $status_new_version="";
            $status_old_version="";
            if($satrt_now==1 || $diff<=0){
               
                //رویه جاری بلافاصله منسوخ شده و رویه ورزن جدید جاری می گردد
                $status_new_version=1;//رویه فرزند فعال
                $status_old_version=2;// رویه ریورژن شده والد غیر فعال
            }
            else{
                     //رویه جاری در زمان  تعیین شده منسوخ شده و رویه ورزن جدید جاری می گردد
                $status_new_version=3;//رویه فرزند درحال پیگیری
                $status_old_version=1;// رویه ریورژن شده والد  فعال
               
            }
            //die();
            $sql_reversion_insert="INSERT INTO procedures (`procedure_name`,
                                                            `start_date`,
                                                            `parent_id`,
                                                            `unit_id`,
                                                            `description`,
                                                            `user_id`,
                                                            `procedure_type`,
                                                            `start_time`,
                                                            `form_number`,
                                                             `register_date`,
                                                             `status`,
                                                             `current_procedure_status`,`last_review_date`,
                                                             `level_of_changes`,
                                                             `procedure_code`)VALUES(
                '{$procedure_name}','{$start_date}','{$cid}','{$unit_id}','{$form_description}','{$_SESSION['userid']}','{$procedure_type}','{$start_time}','{$form_number}','{$current_date}'
                ,'{$status_new_version}','{$current_procedure_status}','{$last_review_date}','{$level_of_changes}','{$procedure_code}')";
            //$ut->fileRecorder('sql_reversion_insert:'.$sql_reversion_insert);
            $insert_res=$db->Query($sql_reversion_insert);
        
            if($insert_res)
            {
                $last_insert_id="SELECT RowID FROM procedures ORDER BY RowID DESC LIMIT 1";
                $lastID=$db->ArrayQuery($last_insert_id);
                $new_cid=$lastID[0]['RowID'];
                if($status_old_version==2){
                    $update_procedures="UPDATE procedures set  delete_cancel_description='{$reason_reversion}',end_date='{$current_date}',status='{$status_old_version}'
                    WHERE `RowID`={$cid}";
                }
                else{
                    $update_procedures="UPDATE procedures set  delete_cancel_description='{$reason_reversion}',status='{$status_old_version}'
                    WHERE `RowID`={$cid}";
                }
                
               $update_res= $db->Query($update_procedures);
               if(!$update_res){
                    return 0;
               }
            }
            else
            {
                return 0;
            }

            $cid=$new_cid;
        }  
        else//   --------------------------------------------insert new procedure-------------------------
        {
            $update_sql="UPDATE procedures set `start_date`='{$start_date}',`start_time`='{$start_time}',`description`='{$form_description}',`form_number`='{$form_number}',`status`=1,
            `current_procedure_status`='{$current_procedure_status}',`last_review_date`='{$last_review_date}',`level_of_changes`='{$level_of_changes}'
                        WHERE `RowID`={$cid}";
            $res= $db->Query($update_sql); 
            //ut->fileRecorder('update_sql:'.$update_sql);
            if(!$res)  {
                    return 0;
            }         
           
        }
        
       //-----------------------------------------------------
    
        $cDate = date('Y/m/d');
        $cTime = date('H:i:s');
        $SFile = array();
        $allowedTypes = ['png','jpg','jpeg','jfif','pdf','xlsx','docx','zip','wav','PNG','JPG','JPEG','JFIF','PDF','XLSX','DOCX','WAV'];
        $sql="SELECT RowID ,unit_id from procedures where RowID={$cid}";
        $res_file_name=$db->ArrayQuery($sql);
        $procedure_row="procedure-".$res_file_name[0]['RowID'];
        $unit_file_name="unit-".$res_file_name[0]['unit_id'];
        if (isset($files) && !empty($files)) {
            $no_files = count($files['name']);
        
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files["tmp_name"][$i];
                if ($files["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                
                $format = substr($files['name'][$i], strpos($files['name'][$i], ".") + 1);
        
                if(!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                }
               // $SFile[] = "attach" . rand(0, time()).'.'.$format;
                //$SFile[] = "procedure_" . rand(0, time()).'.'.$format;
                $SFile[] = $unit_file_name."_pt_".$procedure_type."_".$procedure_row ."_".rand(0, time()).'.'.$format;

            } 
        } 
       $dir=PROCEDURES_DIR_PATH;
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $dir_name="organizition_procedures_attachment";
        $cnt = count($SFile);
       
        for ($i=0;$i<$cnt;$i++) 
        {
            //$upload = move_uploaded_file($files["tmp_name"][$i],'../attachment/'.$SFile[$i]);
            $upload = move_uploaded_file($files["tmp_name"][$i],$dir.$SFile[$i]);
            //----------------------------------------
            $path=$dir.$SFile[$i];
            $type=pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            //$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
           // $base64 = base64_encode($data);
            $current_date=date('Y-m-d H:i:s');
            //-------------------------------------------------------
            $sql4 = "INSERT INTO `procedures_file`(`procedure_id`,`file_name`,`file_title`,`file`,`date_time_attach`)
            VALUES ({$cid},'{$SFile[$i]}','{$file_name[$i]}','{$SFile[$i]}','{$current_date}')";
            $res=$db->Query($sql4);
            if(!$res){
                return -10;
            }
            //--------------------------------------------------------
        }
        if(count($uploaded_file)>0){
            foreach($uploaded_file as $file_id){
                $Select_files_query="SELECT * from procedures_file where RowID ={$file_id}";
             
                $Select_files = $db->ArrayQuery($Select_files_query);
               //$ut->fileRecorder('Select_files');
               //$ut->fileRecorder($Select_files);
                if(count($Select_files)>0){
               //$uploaded_SFile[] = $unit_file_name."_".$procedure_row ."_".rand(0, time()).'.'.$format;
                    $insert="INSERT INTO `procedures_file`(`procedure_id`,`file_name`,`file_title`,`file`,`date_time_attach`) 
                    VALUES ({$cid},'{$Select_files[0]['file_name']}','{$Select_files[0]['file_title']}','{$Select_files[0]['file_name']}','{$current_date}')";
                    $res=$db->Query($insert);
                    if(!$res){
                        return -10;
                    }
                }
        }
        }
        return true;
    }

    public function do_cancellation_procedure_file($remove_reason,$file_procedures_id){
        $db=new DBi();
        $current_date=date('Y-m-d H:i:s');
        $sql="UPDATE procedures_file SET `isEnable`=0 , `date_time_cancellation`='{$current_date}', `cancel_description`='{$remove_reason}' WHERE RowID={$file_procedures_id}";
        $res=$db->Query($sql);
        $result=$db->AffectedRows();
        return $result;
    }

    public function get_procedure_history($procedure_code){
       $db=new DBi();
       $ut=new Utility();
      
       $SQL="SELECT * from procedures where  procedure_code='{$procedure_code}' ORDER BY parent_id DESC";
       $res=$db->ArrayQuery($SQL);
      
       //$ut->fileRecorder($SQL);
       $html='<table class="table table-striped table-borderd">';
        $html.= 
            '<tr>
                <th>#</th>
                <th>نام رویه</th>
                <th> تاریخ و ساعت جاری شدن </th>
                <th>  توضیحات تکمیلی </th>
                <th>  وضعیت</th>
                <th>  نوع رویه</th>
                <th>  علت ایجاد تغییرات </th>
                <th>مشاهده رویه</th>
            </tr>';
        for($i=1;$i<count($res);$i++){
            $html.='<tr>
                <td>'.($i+1).'</td>
                <td>'.$res[$i]['procedure_name'].'</td>
                <td>'.$res[$i]['start_date']." ". $res[$i]['start_time'].'</td>
                <td>'.$res[$i]['description'].'</td>
                <td>'.$res[$i]['status'].'</td>
                <td>'.$res[$i]['procedure_type'].'</td>
                <td>'.$res[$i]['delete_cancel_description'].'</td>
                <td>
                    <button title="مشاهده"  style="position:relative;width:40px" onclick="display_procedure_info('.$res[$i]['RowID'].')" class="btn btn-primary"><i class="fa fa-info" aria-hidden="true"></i></button>
                </td>
            </tr>';
        }

        $html.= '</table>';
        return $html;
    }

    public function get_procedures_list($unit_id,$procedure_type,$page_number,$procedure_name="",$from_date="",$to_date="",$form_number="",$procedure_status=""){
        $acm=new acm();
        $ut=new Utility();
        $db=new DBi();
        $update_pending="SELECT  RowID,parent_id,start_date,start_time,status from procedures where status=3";
        $pendings_res=$db->ArrayQuery($update_pending);
        $full_access_users_array=[1,20,4,67];
   
        foreach($pendings_res as $pending){
            $start_date=$pending['start_date'];
            $start_time=$pending['start_time'];
            $current_date=date("Y-m-d H:i:s");
            $start_date_time=date("Y-m-d H:i:s", strtotime("$start_date $start_time"));
            $diff=strtotime($start_date_time)-strtotime($current_date);
            if($diff<=0){
                $update_sql1="UPDATE procedures set status=1 where RowID= {$pending['RowID']}";
                $db->Query($update_sql1);
                $update_sql2= "UPDATE procedures set status=2,end_date='{$current_date}' where RowID={$pending['parent_id']}";
                $db->Query($update_sql2);
            }

        }
        $page_records=10;
        if($page_number==1){
            $start=0;
            $end=$start+$page_records;
        }
        else{
            $start=($page_number*$page_records)-$page_records;
            $end=$page_records;
        }
        
        $sql= "SELECT p.*,  pt.procedure_type_name FROM procedures as p left join procedure_type as pt   on pt.RowID=p.procedure_type ";
        $sql_count= "SELECT * FROM procedures as p ";
        $where_array=["p.unit_id='{$unit_id}'"];
        if(!empty($procedure_type) && $procedure_type!="all"){
             $where_array[]="p.procedure_type='{$procedure_type}'";
        }
         if(!empty($unit_id)){
            $where_array[]="p.unit_id={$unit_id}";
        }
         if(!empty($procedure_name)){
             $where_array[]="p.procedure_type LIKE '%{$procedure_name}%'";
        }
         if(!empty($from_date) && !empty($to_date) ){
            $from_date=$ut->jal_to_greg($from_date);
            $to_date=$ut->jal_to_greg($to_date);
             $where_array[]="p.start_date BETWEEN '{$from_date}' AND '{$to_date}'";
        }
         if(!empty($form_number)){
             $where_array[]="p.form_number LIKE '%{$form_number}%'";
        }
         if(!empty($procedure_status) && $procedure_status != "all"){
            $where_array[]="p.status='{$procedure_status}'";
        }
        else{
           $where_array[]="(p.status=1 OR p.status=2)"; 
        }
        
        if(count($where_array)==1){
            $where_array['(`p.status`=1 OR `p.status`=3)'];
        }
        $where =implode(' AND ',$where_array);
        $sql_count.=" WHERE {$where} ";
        $sql.= " WHERE {$where} ORDER BY p.RowID ASC LIMIT {$start},{$end} ";
        $result=$db->ArrayQuery($sql);
   
        $sql_count_res=$db->ArrayQuery($sql_count);
        $row_count=count($sql_count_res)?count($sql_count_res):0;

        unset($sql_count_res);
        $all_pages=ceil($row_count/$page_records);
        $html="";
            $get_procedurs_type_sql="SELECT * FROM procedure_type where isEnable=1 ";
            $p_result=$db->ArrayQuery($get_procedurs_type_sql);
            $options_p_type='<option value="all"> همه رویه ها</option>';
            foreach($p_result as $p_res){
                $options_p_type.='<option value="'.$p_res['RowID'].'">'.$p_res['procedure_type_name'].'</option>';
            }

            $get_procedurs_status_sql="SELECT * FROM procedures_status where user_display_status=1";
            $p_result_status=$db->ArrayQuery($get_procedurs_status_sql);
            $options_p_status='<option value="all"> همه وضعیت ها</option>';
            foreach($p_result_status as $p_res_status){
                $options_p_status.='<option value="'.$p_res_status['procedure_status'].'">'.$p_res_status['title_fa'].'</option>';
            }

            $html.='
            <div id="procedure_list">
                <div style="margin-block:10px" class="row">
                    <input type="hidden" value="'.$unit_id.'" id="unit_id_hidden_users">
                    <div class="col">
                        <select id="procedure_type_user"  class="form-control" >
                            '.$options_p_type.' 
                        </select>
                    </div>
                <div class="col">
                    <input id="procedure_name_user" type="text" class="form-control" placeholder="نام رویه">
                </div>
                <div class="col">
                    <input id="from_date_user" type="text" class="form-control" placeholder="  از تاریخ جاری شدن">
                </div>
                <div class="col">
                    <input title="   تا تاریخ جاری شدن"  id="to_date_user"  type="text" class="form-control" placeholder="تا تاریخ جاری شدن ">
                </div>
                <div class="col">
                    <input id="form_number_user" type="text" class="form-control" placeholder="شماره فرم">
                </div>
                <div class="col">
                    <select  id="procedure_status_user"  class="form-control" >
                        '.$options_p_status.'
                    </select>
                </div>
                <div class="col">
                    <button id="procedure_search_btn" onclick="search_unit_procedurs_users(this,'.$unit_id.')" class="form-control btn btn-primary">جستجو</button>
                </div>
            </div>
            <div style="padding-block:10px"><h6 style="font-family:IranSans">'.($row_count>0?'<span style="color:green;padding-inline:5px;">'.$row_count.'</span>':'<span style="color:red;padding-inline:5px">'.$row_count.'</span>').'رکورد یافت شد  </h6></div>
            
            <div id="result_body">';
    if(count($result)>0){
                $html.='<table style="border:2px solid gray" id="public_procedures_tbl" class="table table-borderd table-striped">
                    <tr>
                        <th>#</th>
                        <th>نام رویه</th>
                        <th>مشاهده رویه</th>
                        <th>شماره فرم </th>
                        <th>   تاریخ آخرین بازنگری</th>
                        <th>     سطح تغییرات</th>
                        <th>  وضعیت</th>
                        <th>  توضیحات تکمیلی </th>
                        <th> تاریخ و ساعت جاری شدن </th>
                        <th>  نوع رویه</th>
                        <th>آخرین نسخه منسوخ شده</th>';
            if($acm->hasAccess('users_procedures_download_manang')){
                $html.='<th>مدیریت دانلود فایل</th>';
            }

            if($acm->hasAccess('delete_reversion_cancellation_procedures')){
                $html.=
                        '<th>حذف رویه</th>
                        <th> ابطال رویه</th>
                        <th>ایجاد تغییرات</th>
                        <th>مشاهده تاریخچه  </th>';
            }
            
            $html.='</tr>';

            $i=0;
            foreach( $result as $row ){
                $created_user=$row['user_id']  ;              
                $allowed_user=explode(',',$row['user_allow_download']);
                if($_SESSION['userid']==$created_user || in_array($_SESSION['userid'] ,$full_access_users_array)){
                    $display_row=1;
                }
                else{
                    $display_row=in_array($_SESSION['userid'],$allowed_user)?1:0;
                }
                if($row["status"]==1){
                    $status_html='<span style="color:green">جاری</span>';
                }
                if($row["status"]==3){
                    $status_html='<span style="color:orange">در حال بررسی ..</span>';
                }
                $prosedure_code=$row['procedure_code'];
                $parent_html= $this->get_procedure_parents($prosedure_code,$row['RowID']);
               
                $has_parent=0;
                $parent_html?$has_parent=1:0;
                if($display_row==1)
                {       
                    $html.= 
                    "<tr>
                        <td>"
                            .($start+=1).
                        "</td>
                        <td>"
                            .$row["procedure_name"].
                        '</td>
                        <td>
                            <button title="مشاهده"  style="position:relative;width:40px" onclick="display_procedure_info('.$row['RowID'].')" class="btn btn-primary"><i class="fa fa-info" aria-hidden="true"></i></button>
                        </td>
                        <td>
                           <span>'.$row['form_number'].'</span>
                        </td>
                        <td>
                            <span>'.($row['last_review_date']==0?"":$ut->greg_to_jal($row['last_review_date'])).'</span>
                            
                        </td>
                        <td>
                            <span>'.$row['level_of_changes'].'</span>
                        </td>
                        <td>'
                            .$row['current_procedure_status'].
                        '</td>
                        <td>'
                            .$row["description"].
                        '</td>

                        <td>'
                            .$ut->greg_to_jal($row['start_date'])." ".$row['start_time'] .
                        '</td>
                        
                        <td>'
                            .$row['procedure_type_name'].
                        '</td>
                        <td>
                        <button title="آخرین نسخه منسوخ شده" style="position:relative;width:40px" onclick="manage_last_cancelled_version_procedure('.$row['RowID'].')" class="btn btn-danger"><i class="fa fa-history" aria-hidden="true"></i></button>
                        </td>';
                        if($acm->hasAccess('users_procedures_download_manang')){

                            if(!empty($row['user_allow_download'])){
                                $user_count=count(explode(",",$row['user_allow_download']));
                                $class="btn btn-primary";
                                $color="green";
                                $message='<p class="download_msg"> تعداد '.'<span style="color:red">'.$user_count.'</span>'.'  کاربر مجوز دانلود این سند را دارند برای   اطلاعات بیشتر بر روی کلید دانلود  کلیک نمایید </p>';
                            }
                            else{
                                $class="btn btn-warning";
                                
                                $color="red";
                                $message='<p class="download_msg">هیچ کاربری مجوز دانلود این سند را ندارد جهت اضافه کردن کاربران  روی کلید دانلود  کلیک نمایید</p>';
                            }
                            $html.='<td style="position:relative">
                                <button title=" مدیریت  دانلود فایل" style="position:relative;width:40px" onclick="manage_users_download('.$row['RowID'].')" class="'.$class.'"><i class="fa fa-download" aria-hidden="true"></i></button>
                                <i style="color:'.$color.';cursor:pointer" class="fa fa-question-circle show_sumary_users" onmouseover="show_help(this)" onmouseout="hide_help(this)">
                                   
                                </i>
                                '.$message.'
                                </td>';
                        }

                        if($acm->hasAccess('delete_reversion_cancellation_procedures')){
                        $html.='<td>
                            <button title="حذف" style="position:relative;width:40px" onclick="delete_procedure('.$row['RowID'].')" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </td>
                       
                        <td>
                            <button title="ابطال" style="position:relative;width:40px" onclick="cancellation_procedure('.$row['RowID'].')" class="btn btn-warning"><i class="fa fa-recycle" aria-hidden="true"></i>
                            </button>
                        </td>
                        <td>
                            <button title="ایجاد تغییرات" style="position:relative;width:40px" onclick="reversion_procedure('.$row['RowID'].')" class="btn btn-success"><i class="fa fa-edit" aria-hidden="true"></i>
                            </button>
                        </td>
                        
                       
                       <td>
                        <button  title="مشاهده تاریخچه رویه" style="position:relative;width:40px" onclick="get_procedure_history('.$row['RowID'].',this,'.$has_parent.')" class="btn btn-success history-btn"><i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </td>';
                        }
                        $html.= '</tr>';
                    
                    if($parent_html){
                        $html.='<tr  class="procedure_parents" id="parents_'.$row['RowID'].'" style="display:none"><td colspan="20" style="position:relative">'.$parent_html.'</td></tr>';
                    }
                }
            }
            $html.='</table>';
        
            $html.= '
            <div style="position:relative;height:50px">
            <div class="form-inline" style="position:absolute;bottom:0;top:0;right:20%">
                <div class="form-group">
                    <button type="button" class="btn btn-danger" 
                        onclick="page_navigate(\'first\')" 
                            style="width:30px;height:20px;margin:5px;padding:0px;">
                        <i class="fa fa-fast-forward"></i>
                    </button>
                    <button type="button" class="btn btn-success" onclick="page_navigate(\'prev\')" 
                        style="width:30px;height:20px;margin:5px;padding:0px;">
                            <i class="fa fa-step-forward"></i>
                    </button>
                    <span style=""> صفحه 
                        <input type="text" class="form-control" id="goToPageInput" onkeyup="page_navigate(\'custom\')" 
                            style="width:50px;height:25px;" value="'.$page_number.'"> از 
                            '.$all_pages.'
                    </span>
                    <button type="button" class="btn btn-success" onclick="page_navigate(\'next\')"  style="width:30px;height:20px;margin:5px;padding:0px;">
                        <i class="fa fa-step-backward fa-sm"></i></button><button type="button" class="btn btn-danger" onclick="page_navigate(\'last\')"  style="width:30px;height:20px;margin:5px;padding:0px;"><i class="fa fa-fast-backward"></i>
                    </button>
                </div>
                
                <input type="hidden" id="unit_id_hidden" value="'.$unit_id.'"/>
                <input type="hidden" id="page_number_hidden" value="'.$page_number.'"/>
                <input type="hidden" id="all_page_hidden" value="'.$all_pages.'"/>
                
            </div>
            </div>
        ';
        }
        $html.="</div>";
        return array($row_count,$html);
    }

   public function get_users_procedures_download_info($p_id){
    $db=new DBi();
    $ut=new Utility();
    $all_users="SELECT RowID from users where IsEnable=1";
    $all_users_res=$db->ArrayQuery($all_users);
    $all_users=[];
    foreach($all_users_res as $user_id){//get all active users
        $all_users[]=$user_id['RowID'];
    }

    $sql="SELECT `user_allow_download` FROM `procedures` WHERE `RowID`='{$p_id}'";
    $res=$db->ArrayQuery($sql);
    $users_download_array=[];
    if(!empty($res[0]['user_allow_download'])){
        $users_download_array=explode(",",str_replace(',,',",",$res[0]['user_allow_download']));
    }
   
    $html='<table id="users_download_info" class="table table-borderd table-striped">';
    $counter=1;
    if(count($users_download_array)>0)
    {
        $html.=
        '<thead>
            <tr style="background:#182372;color:#fff">
                <th >#</th>
                <th>کاربران مجاز به دانلود</th>
                <th>مدیریت مشاهده</th>
            </tr>
        </thead>
        <tbody>';
        foreach($users_download_array as $user){
            $html.=
            '<tr>
                    <td>'.$counter.'</td>
                    <td>'.$ut->get_user_fullname($user).'</td>
                    <td><button  onclick="not_allow_download('.$user.','.$p_id.')" class="btn btn-danger"> ابطال مجوز دانلود</button></td>
            </tr>';
            $counter++;
        }
        $html.="</tbody></table>";
    }
    else
    {
        $html.='<p style="color:red">هیچ موردی یافت نشد</p>';

    }
    $not_allow_users=count($users_download_array)>0?array_diff($all_users,$users_download_array):$all_users;
    $select_options="";
    foreach($not_allow_users as $not_allow_user){
        $select_options.='<option value="'.$not_allow_user.'">'.$ut->get_user_fullname($not_allow_user).'</option>';
    }
  
    return array('table_html'=>$html,"select_option"=>$select_options);
    }

   public function not_allow_download($user_id,$p_id){
    $db = new DBi();
    $sql = "SELECT user_allow_download FROM procedures WHERE RowID={$p_id}";
    $res = $db->ArrayQuery($sql);
    $users = $res[0]['user_allow_download'];
    $users_array = explode(",",$users);
    if(($key = array_search($user_id, $users_array)) !== false) {
        unset($users_array[$key]);
    }
    $new_user_array=$users_array;
    $implode_users=implode(",",$new_user_array);
    $update_sql="UPDATE procedures  SET user_allow_download='{$implode_users}' WHERE RowID={$p_id}";
    $res=$db->Query($update_sql);
    return $res;

   }

    public function add_users_download_procedure($pid,$new_users_ides){
        $db=new DBi();
        $ut=new Utility();
        
        $sql="SELECT `user_allow_download` from `procedures` where RowID={$pid}";
        $result=$db->ArrayQuery($sql);
        $old_user_ids=$result[0]['user_allow_download'];
        $old_users_array=[];
        if(!empty($old_user_ids)){
            $old_users_array=explode(",",str_replace(",,",",",$old_user_ids));
        }
        //------------------------------------------old user_array End ----------------------------
        if(!empty($new_users_ides)){
            $new_users_array=explode(",",$new_users_ides);
        }
      
        if(count($new_users_array)>0){
            foreach($new_users_array as $new_user_id){
                if(!in_array($new_user_id,$old_users_array)){
                    $old_users_array[]=$new_user_id;
                }
            }
        }
        else{
            $old_users_array=$new_users_array;

        }
        $new_users_ides=implode(',',$old_users_array);
        $update_sql="UPDATE `procedures` set `user_allow_download`='{$new_users_ides}' where RowID={$pid} ";
        $ut->fileRecorder($update_sql);
        $result_update=$db->Query($update_sql);
        if($result_update){
            return true;
        }
        else{
            return false;
        } 
        
    }
    public function get_procedure_parents($procedure_code,$RowID)
    {
        $db=new DBi();
        $ut=new Utility();
        $acm=new acm();
        if($acm->hasAccess('delete_reversion_cancellation_procedures')){
        $SQL="SELECT p.*,pt.procedure_type_name from procedures as p left join procedure_type as pt on pt.RowID=p.procedure_type where p.RowID<{$RowID} AND p.procedure_code='{$procedure_code}' AND p.status=2 ORDER BY p.parent_id DESC";
        }
        // else{
        //     $SQL="SELECT * from procedures where  procedure_code='{$procedure_code}' AND status=2 ORDER BY parent_id DESC LIMIT 1";
        // }
        $result=$db->ArrayQuery($SQL);
        $html="";
       // unset($result[0]);
        if(count($result)>0)
        {
            $html='<div style="
                    position: absolute;
                    z-index: 1000;
                    background: gray;
                    width: 100%;
                    top: 0;
                    background:#9adee9;
                    border-radius:0 0 10px 10px;
                    box-shadow:2px 3px 4px gray;
                    right: 0;"><p style="padding:10px;text-align:center;background:#539898;color:#fff">تاریخچه رویه ها</p><table style="width:100%">';
                $html.='
                <tr>
                        <th>#</th>
                        <th>نام رویه</th>
                        <th> تاریخ و ساعت جاری شدن </th>
                        <th>  توضیحات تکمیلی </th>
                        <th>  وضعیت</th>
                        <th>  نوع رویه</th>
                        <th>مشاهده رویه</th>
                        
                    </tr>';
             
            foreach( $result as $row ){
                 if($row["status"]==1){
                     $status_html='<span style="color:green">جاری</span>';
                 }
                 if($row["status"]==3){
                     $status_html='<span style="color:orange">در حال بررسی ..</span>';
                 }
                 
                 if($row["status"]==2){
                    $status_html='<span style="color:red"> منسوخ شده</span>';
                }
                

                 $start=0;
                 $html.= 
                     '<tr ">
                         <td>'
                             .($start+=1).
                         "</td>
                         <td>"
                             .$row["procedure_name"].
                         "</td>
                         <td>"
                             .$ut->greg_to_jal($row['start_date'])." ".$row['start_time'] .
                         "</td>
                         <td>"
                             .$row["description"].
                         '</td>
                         <td>'
                             .$status_html.
                         '</td>
                         <td>'
                             .$row['procedure_type_name'].
                         '</td>
                         <td>
                             <button  title="مشاهده"  style="position:relative;width:40px" onclick="display_procedure_info('.$row['RowID'].')" class="btn btn-primary"><i class="fa fa-info" aria-hidden="true"></i></button>
                         </td>
                         
                     </tr>';
 
             }
             $html.="</table></div>";
            
            return $html;
        }
        else 
            return false;
    }

    public function get_unit_detailes($unit_id)
    {
        $user_id=$_SESSION['userid'];
        $acm=new acm();
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT department_name FROM organization_department WHERE RowID={$unit_id}";
        $res=$db->ArrayQuery($sql);
        $procedure_type="SELECT * FROM `procedure_type` ";
        $p_type_arr=$db->ArrayQuery($procedure_type);
        $allowed_users=[];
        // foreach($p_type_arr as $p_type){
        //     $allowed_admins[$p_type['RowID']]=explode(',',$p_type['allowed_admins']);
        // }
        $add_btn_units_type='<button style="color:#fff; margin:10px" class="btn btn-success" onclick="add_organization_procedure_type()"> تعریف  نوع مستند جدید</button>';
        $tab_page='
        <div>
        <ul id="procedures_type_box" style="padding:1rem 0px">
        <input type="hidden" id="procedures_type_hidden" value="all"/>
            <div class="form-check form-check-inline" data-toggle="tooltip" data-placement="right" title="مشاهده همه رویه های  قابل مشاهده">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio0" value="all" style="height:20px; width:20px;" onclick="go_to_page_op(this,\'all\','.$unit_id.')">
                <label class="form-check-label" for="inlineRadio0">همه رویه ها  </label>
            </div>';
       foreach($p_type_arr as $k=>$v){
            $tab_page.='<div class="form-check form-check-inline">';
            $tab_page.='<input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio'.$v['RowID'].'" value="'.$v['RowID'].'" style="height:20px; width:20px;" onclick="go_to_page_op(this,\''.$v['RowID'].'\','.$unit_id.');">
            <label class="form-check-label" for="inlineRadio'.$v['RowID'].'">'.$v['procedure_type_name'].'</label>';
            $tab_page.='</div>';
        }
    
        $tab_page.='</ul>';
        if ($acm->hasAccess('add_unit_prosedure')) {
                $tab_page.='<div> <button class="btn btn-success" onclick="add_unit_procedures(\''.$unit_id.'\',\'add\')">ایجاد رویه جدید</button>';
        }
        if ($acm->hasAccess('add_unit_prosedure_type')) {
            $tab_page.=$add_btn_units_type;
        }
        $tab_page.='</div></div>';
       
        return array('unit_name'=>$res[0]['department_name'],"html_res"=>$tab_page);
    }

    public function get_all_active_users(){
        $db=new DBi();
        $usql="SELECT RowID ,fname,lname FROM users WHERE IsEnable=1 ";
        $res=$db->ArrayQuery($usql);
        return $res;

    }
    
    public function get_procedure_info($procedure_id)
    {
        $ut=new Utility();
        $db=new DBi();
        $acm =new acm();
        
        $sql="SELECT * FROM procedures where RowID = {$procedure_id}";
        $result=$db->ArrayQuery($sql);

        $file_query="SELECT * FROM procedures_file where procedure_id={$procedure_id}";
        $file_result= $db->ArrayQuery($file_query);
        if(count($result)>0)
        {
            $res_row=$result[0];
            $dir2="";
            switch($res_row["status"])
            {
                case "0":
                    $title_date="تاریخ حذف";
                    $title_desc="علت حذف";
                    $title_uid="کاربر حذف کننده";
                    $dir2="deleted_procedure_files/".$res_row['RowID']."/";
                    $alert='<p>این رویه <span class="blinker">حذف شده است </span> و قابل استناد نمی باشد  در صورت استناد به رویه مذکور برای انجام هر عملی مسئولیت آن بر عهده کاربر  می باشد</p>';
                   
                    break;
                case "-1":
                    $title_date="تاریخ ابطال";
                    $title_desc="علت ابطال";
                    $title_uid="کاربر ابطال کننده";
                     $dir2="canceled_procedure_files/".$res_row['RowID']."/";
                     $alert='<p>این رویه <span class="blinker">ابطال شده است </span> و قابل استناد نمی باشد  در صورت استناد به رویه مذکور برای انجام هر عملی مسئولیت آن بر عهده کاربر  می باشد</p>';
                    break;
                case "2":
                    $title_date="تاریخ ری ورژن";
                    $title_desc="علت ری  ورژن";
                    $title_uid="کاربر  ری ورژن";
                    $alert='<p>این رویه <span class="blinker">منسوخ شده است </span> و قابل استناد نمی باشد  در صورت استناد به رویه مذکور برای انجام هر عملی مسئولیت آن بر عهده کاربر  می باشد</p>';
                    break;
            }
            
            $html='<fieldset style="border:2px solid gray;border-radius:10px;padding:0"><legend style=" width: auto;color: green;font-size: 1.2rem;padding: 10px;">جزییات  رویه </legend>
            <table class="table bg-light">';
            

            $start_date=($res_row['start_date']!=0?$ut->greg_to_jal($res_row['start_date']):"");
            $start_time=$res_row['start_time'];
            $html.=
            '<h6 style="color:red;padding:10px">'.$alert .'</h6>
            <tr>
                <td><span>نام رویه</span></td>
                <td><span>'.$res_row['procedure_name'].'</span></td>
            </tr>
            <tr>
                <td><span> شماره فرم </span></td>
                <td><span>'.$res_row['procedure_name'].'</span></td>
            </tr>
            <tr>
                <td><span> تاریخ آخرین بازنگری</span></td>
                <td><span>'.($res_row['last_review_date']==0?"":$ut->greg_to_jal($res_row['last_review_date'])).'</span></td>
            </tr>
            <tr>
                <td><span> سطح تغییرات</span></td>
                <td><span>'.$res_row['level_of_changes'].'</span></td>
            </tr>
            <tr>
                <td><span> وضعیت</span></td>
                <td><span>'.$res_row['current_procedure_status'].'</span></td>
            </tr>
            <tr>
                <td><span> تاریخ و ساعت جاری شدن</span></td>
                <td><span>'.$start_date." - ".$start_time.'</span></td>
            </tr>
            <tr>
                <td><span>کاربر ثبت کننده</span></td>
                <td><span>'.$this->get_user_fullname($res_row['user_id']).'</span></td>
            </tr>
            <tr>
                <td><span>توضیحات </span></td>
                <td><p>'.$res_row['description'].'</p></td>
            </tr>';
            if($res_row['status']==-1 ||$res_row['status']==0 ||$res_row['status']==2){
                
           $html.='<tr>
                <td><span>'.$title_date.' </span></td>
                <td><span>'.$ut->greg_to_jal($res_row['end_date']).'</span></td>
            </tr>
             <tr>
                <td><span>'.$title_uid.' </span></td>
                <td><p>'.$this->get_user_fullname($res_row['delete_cancel_userid']).'</p></td>
            </tr>
             <tr>
                <td><span>'.$title_desc.' </span></td>
                <td><p>'.$res_row['delete_cancel_description'].'</p></td>
            </tr>';
            }
            if($acm->hasAccess('display_cancel_reactive_history')){
            $html.='<tr><td colspan="3"><button class="btn btn-primary"  onclick="display_records_procedure('.$procedure_id.')">مشاهده سوابق فعالیت رویه</button></td></tr>';
            }
           $html.=' </table></fieldset>';
            $html_file='<fieldset style="border:2px solid gray;border-radius:10px;padding:0"><legend style=" width: auto;color:blue;font-size: 1.2rem;padding: 10px;">فایلهای پیوست شده</legend>';
            if(count($file_result )>0)
            {
                $html_file.='<table class="table bg-light">';
                $counter=0;
                $html_file.="<tr>
                                <th>
                                    #
                                </th>
                                <th>
                                    عنوان فایل
                                </th>
                                <th>
                                        تاریخ پیوست
                                </th>
                                
                                <th>
                                    دانلود پیوست  
                                </th>
                            </tr>";
                foreach($file_result as $file_row)
                {
                    $html_file.=
                    '<tr>
                        <td>
                            <span>'.($counter+=1).'</span>
                        </td>
                        <td>
                            <span>'.$file_row['file_title'].'</span>
                        </td>
                        <td>
                            <span>'.($file_row['date_time_attach']==0?"":$ut->greg_to_jal($file_row['date_time_attach'])).'</span>
                        </td>
                        
                        <td>
                            <a href="'.PROCEDURES_DIR.'/'.$dir2.$file_row['file_name'].'" class="btn btn-primary" target="_blank"> <i class="fa fa-download"></i></a></span>
                        </td>

                    </tr>';
                }
            
                $html_file.="</table>"; 
            }
            else
            {
                $html_file.='<p style="color:red;padding:10px">فایلی به این رویه پیوست نشده است </p>';
            }
            $html_file.="</fielsdet>"; 
        }
        return $html."<hr>".$html_file;
    }
    public function get_user_fullname($user_id){
        $db=new DBi();
        $sql="SELECT fname,lname FROM users where RowID={$user_id}";
        $res=$db->ArrayQuery($sql);
        return $res[0]['fname']." ".$res[0]['lname'];
    }

    public function delete_unit_procedure($p_id,$desc){
        $db=new DBi();
        $ut=new Utility();
        $delete_date=date('Y-m-d H:i:s');
        $get_registerd_user="SELECT user_id FROM procedures where RowID={$p_id}";
        $result=$db->ArrayQuery($get_registerd_user);
        if($result[0]['user_id']==$_SESSION['userid']){

            mysqli_autocommit($db->Getcon(),FALSE);
            $sql="update procedures set `status`=0, `delete_cancel_description` = '{$desc}',`end_date`='{$delete_date}',`delete_cancel_userid`='{$_SESSION['userid']}' WHERE RowID={$p_id}";
            $result = $db->Query($sql);
            $affected_row=$db->AffectedRows();
            $ut->fileRecorder($sql);
            if($affected_row==1)
            {
                mysqli_commit($db->Getcon());
                $Delete_files="Select file_name from procedures_file where procedure_id={$p_id}";
                $res_delete_file=$db->ArrayQuery($Delete_files);
                $delete_filename=[];
                $deleted_file_count=0;
                foreach($res_delete_file as $row){
                    $file_path=trim("../".PROCEDURES_DIR."/".$row['file_name']);
                    if(file_exists($file_path))
                    {
                        if(!is_dir("../".PROCEDURES_DIR."/deleted_procedure_files/$p_id")){
                            mkdir("../".PROCEDURES_DIR."/deleted_procedure_files/$p_id",0777,true);
                        }
                        rename($file_path,"../".PROCEDURES_DIR."/deleted_procedure_files/$p_id/".$row['file_name']);
                        $deleted_file_count++;
                    }
                }
                return 1;
            }

            if($affected_row>1)
            {
                mysqli_rollback($db->Getcon());
                return -1;
            }
            else
            {
                return 0;
            }
        }
        else{
            return -3;
        }
    }
    public function cancellation_unit_procedure($p_id,$desc){
        $db=new DBi();
        $ut=new Utility();
        $cancel_date=date('Y-m-d H:i:s');
        $sql_p="select procedure_code,user_id from procedures where RowId={$p_id}";
        $res=$db->ArrayQuery($sql_p);
        $procedure_code=$res[0]['procedure_code'];
        $user_id=$res[0]['user_id'];
        if($user_id==$_SESSION['userid'])
        {
            mysqli_autocommit($db->Getcon(),FALSE);
            $sql="update procedures set `status`=-1, delete_cancel_description = '{$desc}' ,delete_cancel_userid='{$_SESSION['userid']}' ,end_date='{$cancel_date}' WHERE RowID={$p_id}";
            $result = $db->Query($sql);
            $affected_row=$db->AffectedRows();
            if($affected_row==1)
            {
                mysqli_commit($db->Getcon());
                $cancel_files="Select file_name from procedures_file where procedure_id={$p_id}";
                $res_cancel_file=$db->ArrayQuery($cancel_files);
                $cancel_file_count=0;

                foreach($res_cancel_file as $row){
                    $file_path=trim("../".PROCEDURES_DIR."/".$row['file_name']);
                    if(file_exists($file_path))
                    {
                    if(!is_dir("../".PROCEDURES_DIR."/canceled_procedure_files/$p_id")){
                            mkdir("../".PROCEDURES_DIR."/canceled_procedure_files/$p_id",0777,true);
                    }
                        rename($file_path,"../".PROCEDURES_DIR."/canceled_procedure_files/$p_id/".$row['file_name']);
                        $cancel_file_count++;
                    }
                }
                $insert_sql="insert into procedure_reaction_detailes( procedure_id,reason_of_reaction,last_status,procedure_code,user_id,registerition_date)VALUES(
                '{$p_id}','{$desc}','-1','{$procedure_code}','{$_SESSION['userid']}','{$cancel_date}')";
                $res=$db->Query($insert_sql);
                return 1;
            }

            if($affected_row>1)
            {
                mysqli_rollback($db->Getcon());
                return -1;
            }
            else
            {
                return 0;
            }
        }
        else{
            return -3;
        }
    }

    public function reversion_procedure($procedure_id)
    {
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT *   FROM procedures WHERE RowID={$procedure_id}";
        $p_res=$db->ArrayQuery($sql);
        if($p_res[0]['user_id']==$_SESSION['userid']){
            $final_array=[];
            if(count($p_res[0]>0)){
                foreach($p_res[0] as $res_key => $res_value){
                    $final_array['procedure_info'][$res_key] = $res_value;
                }
            }
            $file_sql = "SELECT * FROM procedures_file WHERE isEnable=1 AND procedure_id={$procedure_id}";
            $file_res = $db->ArrayQuery($file_sql);
            $file_html="";
            $file_html.='<table id="uploaded_file_tbl" class="table table-borderd"><tr>
                        <th>انتخاب فایل </th>
                        <th>نام فایل</th>
                        <th>مشاهده </th>
                    </tr>';
            foreach($file_res as $row){
                $file_html.='<tr>
                                <td><input type="checkbox" checked value="'.$row['RowID'].'" class="uploaded_file_row" id="file_'.$row['RowID'].'"></td>
                                <td>'.$row['file_title'].'</td>
                                <td><button class="btn btn-primary"><a style="color:#fff" href="'.PROCEDURES_DIR.'/'.$row['file_name'].'" target="_blank"><i class="fa fa-download"></i></a></button></td>
                            </tr>';
            }
            $file_html.="</table>";
            $final_array['files_info']=$file_html;
            return $final_array;
        }
        else{
            return -3;
        }
        
    }

    public function get_procedures_type(){
        $db=new DBi();
        $sql="SELECT * FROM procedure_type ";
        $res=$db->ArrayQuery($sql);
        if(count($res)>0){
            error_log(print_r($res,true));
            return $res;
        }
        else{
            return 0;
        }
    }

    public function get_search_select_params(){
        $db=new DBi();
        $sql_unit= "SELECT RowID, procedure_type_name  FROM procedure_type ";
        $unit_res= $db->ArrayQuery($sql_unit);
        return $unit_res;

    }

    public function search_unit_procedurs($unit,$procedure_name,$from_date,$to_date,$form_number,$procedure_status,$page_number,$procedure_type_admin){
        $db=new DBi();
        $ut=new Utility();
        $acm=new acm();
        $from_date=$ut->jal_to_greg($from_date);
        $to_date=$ut->jal_to_greg($to_date);
       // $row_count="SELECT RowID FROM procedures where status<>10";
        //$row_count=count($db->ArrayQuery($row_count));
        //$//ut->fileRecorder('row_count');
        //$//ut->fileRecorder($row_count);
       // $row_count=count($row_count);
        $search_sql="SELECT p.*,pt.procedure_type_name FROM procedures as p left join procedure_type as pt on pt.RowID=p.procedure_type where";
        
        if(!empty($unit)){
            $search_sql.=" p.unit_id ='{$unit}'";

        }
        if(!empty($procedure_name)){
            $search_sql.=" AND  p.procedure_name LIKE'%{$procedure_name}%'";

        }
        if(!empty($from_date) && !empty($to_date)){
            $search_sql.="AND  (p.start_date BETWEEN '{$from_date}' AND '{$to_date}') ";

        }
        if(!empty($form_number)){
            $search_sql.=" AND p.form_number ='{$form_number}'";

        }
        if($procedure_status!="all"){
            $search_sql.=" AND p.status ='{$procedure_status}'";

        }
        if($procedure_type_admin!=0){
            $search_sql.=" AND p.procedure_type ='{$procedure_type_admin}'";

        }
        $page_records=10;
        
        if($page_number==1){
            $start=0;
            $end=$start+$page_records;
        }
        else{
            $start=($page_number*$page_records)-$page_records;
            $end=$page_records;
        }
        $search_sql.= " AND p.status<>10 ORDER BY p.RowID DESC LIMIT {$start},{$end} ";
        //$//ut->fileRecorder('search_sql');
        //$//ut->fileRecorder($search_sql);
       $result= $db->ArrayQuery($search_sql);
       $row_count=count($result);
       $html="";
       
       if(count($result)>0)
       {
            
            //$//ut->fileRecorder('search:'.$row_count);
            $all_pages=ceil($row_count/$page_records);
            $html.='<br><table id="public_procedures_tbl" class="table table-borderd table-striped">
                    <tr>
                        <th>#</th>
                        <th>کد یکتای رویه</th>
                        <th>نام رویه</th>
                        <th> تاریخ و ساعت جاری شدن </th>
                        <th>  توضیحات تکمیلی </th>
                        <th>  وضعیت</th>
                        <th>  نوع رویه</th>
                        <th> علت ری ورژن/حذف/ابطال</th>
                        <th>مشاهده رویه</th>';
            if($acm->hasAccess('return_from_cancellation')){
                    $html.=    '<th> خروج از ابطال</th>';
            }
             $html.='          
                        <th>مشاهده تاریخچه  </th>
                    </tr>';

            $start=0;
            foreach( $result as $row ){
                $row_color="";
                if($row["status"]==1){
                    $status_html='<span style="color:green">جاری</span>';
                    $row_color="#bbdabb";
                }
                if($row["status"]==3){
                    $status_html='<span style="color:orange">در حال بررسی ..</span>';
                    $row_color="#9ec0f6";
                }
                 if($row["status"]==0){
                    $status_html='<span style="color:red">حذف شده</span>';
                    $row_color="#cdd1de";
                }
                if($row["status"]==-1){
                    $status_html='<span style="color:gray">ابطال شده</span>';
                    $row_color="#f6d395";
                }
                if($row["status"]==2){
                    $status_html='<span style="color:gary"> منسوخ شده </span>';
                    $row_color= '#ebebb7';
                }
                $prosedure_code=$row['procedure_code'];
                $parent_html= $this->get_procedure_parents($prosedure_code,$row['RowID']);
                $ut->fileRecorder('parent_html'." -".$parent_html);
                $has_parent=0;
                $parent_html?$has_parent=1:0;
                $prosedure_code=$row['procedure_code'];
                $html.= 
                    '<tr style="background-color:'.$row_color.'">
                        <td>'
                            .($start+=1).
                        "</td>
                        <td>"
                            .$row['procedure_code'].
                        "</td>
                        <td>"
                            .$row["procedure_name"].
                        "</td>
                        <td>"
                            .$ut->greg_to_jal($row['start_date'])." ".$row['start_time'] .
                        "</td>
                        <td>"
                            .$row["description"].
                        '</td>
                        <td>'
                            .$status_html.
                        '</td>
                        <td>'
                            .$row['procedure_type_name'].
                        '</td>
                        <td>'
                            .$row['delete_cancel_description'].
                        '</td>
 
                        <td>
                            <button title="مشاهده"  style="position:relative;width:40px" onclick="display_procedure_info('.$row['RowID'].')" class="btn btn-primary"><i class="fa fa-info" aria-hidden="true"></i></button>
                        </td>';
                if($acm->hasAccess('return_from_cancellation')){
                        $html.='<td>'
                            .($row["status"]==-1?
                            '<button title="فعالسازی" style="position:relative;width:40px" onclick="active_canceled_procedure('.$row['RowID'].')" class="btn btn-warning"><i class="fa fa-recycle" aria-hidden="true"></i>
                            </button>':"").
                        '</td>';
                }
                $html.=       '<td>
                        <button  title="مشاهده تاریخچه رویه" style="position:relative;width:40px" onclick="get_procedure_history('.$row['RowID'].',this,'.$has_parent.')" class="btn btn-success history-btn"><i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </td>
                    </tr>';
            
                    //$parent_html= $this->get_procedure_parents($prosedure_code);
                    if($parent_html){
                        $html.='<tr  class="procedure_parents" id="parents_'.$row['RowID'].'" style="display:none"><td colspan="20" style="position:relative">'.$parent_html.'</td></tr>';
                    }
            }
            $html.='</table>';
            $html.= '
            <br><br><div class="form-inline" style="position:absolute;bottom:10px;right:20%">
                <div class="form-group">
                    <button type="button" class="btn btn-danger" 
                        onclick="page_navigate_search(\'first\')" 
                            style="width:30px;height:20px;margin:5px;padding:0px;">
                        <i class="fa fa-fast-forward"></i>
                    </button>
                    <button type="button" class="btn btn-success" onclick="page_navigate_search(\'prev\')" 
                        style="width:30px;height:20px;margin:5px;padding:0px;">
                            <i class="fa fa-step-forward"></i>
                    </button>
                    <span style=""> صفحه 
                        <input type="text" class="form-control" id="goToPageInput" onkeyup="page_navigate_search(\'custom\')" 
                            style="width:50px;height:25px;" value="'.$page_number.'"> از 
                            '.$all_pages.'
                    </span>
                    <button type="button" class="btn btn-success" onclick="page_navigate_search(\'next\')"  style="width:30px;height:20px;margin:5px;padding:0px;">
                        <i class="fa fa-step-backward fa-sm"></i></button><button type="button" class="btn btn-danger" onclick="page_navigate(\'last\')"  style="width:30px;height:20px;margin:5px;padding:0px;"><i class="fa fa-fast-backward"></i>
                    </button>
                </div>
                <input type="hidden" id="procedure_name_hidden" value="'.$procedure_name.'"/>
                <input type="hidden" id="unit_hidden" value="'.$unit.'"/>
                <input type="hidden" id="page_number_hidden" value="'.$page_number.'"/>
                <input type="hidden" id="from_date_hidden" value="'.$from_date.'"/>
                <input type="hidden" id="to_date_hidden" value="'.$to_date.'"/>
                <input type="hidden" id="form_number_hidden" value="'.$form_number.'"/>
                <input type="hidden" id="procedure_status_hidden" value="'.$procedure_status.'"/>
                <input type="hidden" id="all_page_hidden" value="'.$all_pages.'"/>
                
                
            </div>';
        }
   
        return $html;

    }

     public function reactive_procedure($reason_reactive,$start_date,$start_time,$start_now,$RowID,$current_procedure_status,$last_review_date,$level_of_changes){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT * From procedures where RowID={$RowID}";
        $res=$db->ArrayQuery($sql);
        $current_date=date('Y-m-d h:i:s');
        $procedure_code=$res[0]['procedure_code'];
        $last_satus=$res[0]['status'];
        $description = $res[0]['description'];
        if($start_now==1){
            $start_date=date("Y-m-d");
            $start_time=date("H:i:S");
            $status=1;
        }
        else{
            $start_date=$ut->jal_to_greg($start_date);
            $status=3;
        }

        $update_query="UPDATE procedures set procedure_code='{$procedure_code}' ,start_date='{$start_date}',start_time='{$start_time}',status='{$status}',current_procedure_status='{$current_procedure_status}',last_review_date='{$ut->jal_to_greg($last_review_date)}',level_of_changes='{$level_of_changes}'
         where RowID={$RowID}";
        $res_update=$db->Query($update_query);
        if($res_update){$flag++;}
        $add_file="select * FROM procedures_file where procedure_id ={$RowID}";
        $file_res=$db->ArrayQuery($add_file);
        foreach($file_res as $row)
        {
            $current_file_path= "../".PROCEDURES_DIR."/canceled_procedure_files/$RowID/".$row['file_name'];//select files from canceled directory
            $transfer=copy($current_file_path, "../".PROCEDURES_DIR."/".$row['file_name']);// move from cancelled directory to main file directory 
        }
        $insert_sql="insert into procedure_reaction_detailes( procedure_id,reason_of_reaction,last_status,procedure_code,user_id,registerition_date)VALUES(
            '{$RowID}','{$reason_reactive}','{$status}','{$procedure_code}','{$_SESSION['userid']}','{$current_date}')";
            $res=$db->Query($insert_sql);
            return true;
    

     }
/*
    public function reactive_procedure($reason_reactive,$start_date,$start_time,$start_now,$RowID){
        $db=new DBi();
        $sql="SELECT * From procedures where RowID={$RowID}";
        $ut=new Utility();
        $res=$db->ArrayQuery($sql);
        $current_date=date('Y-m-d h:i:s');
        $procedure_code=date("Y").strtotime($current_date);
        $description = $res[0]['description'];
        if($start_now==1){
            $start_date=date("Y-m-d");
            $start_time=date("H:i:S");
            $status=1;
        }
        else{
            $start_date=$ut->jal_to_greg($start_date);
            $status=3;
          
        }

       $flag=0;
        $insert_active_procedure="insert into procedures (procedure_name,start_date,parent_id,unit_id,description,user_id,procedure_type,start_time,form_title,form_number,status,register_date,procedure_code) 
                                    VALUES('{$res[0]['procedure_name']}','{$start_date}','{$RowID}','{$res[0]['unit_id']}','{$description}',{$_SESSION['userid']},'{$res[0]['procedure_type']}',
                                    '{$start_time}','{$res[0]['form_title']}','{$res[0]['form_number']}','{$status}','{$current_date}','{$procedure_code}')";
        $res_insert=$db->Query($insert_active_procedure);
        if($res_insert){$flag++;}
        $update_query="UPDATE procedures set procedure_code='{$procedure_code}' ,status=2 where RowID={$RowID}";
        $res_update=$db->Query($update_query);
        if($res_update){$flag++;}
        $add_file="select * FROM procedures_file where procedure_id ={$RowID}";
        $file_res=$db->ArrayQuery($add_file);
      error_log('flag');
      error_log($flag);
      
        $get_p_id="SELECT RowID from Procedures ORDER BY RowID DESC LIMIT 1";
        $p_res=$db->ArrayQuery($get_p_id);
        $procedure_id=$p_res[0]['RowID'];
        foreach($file_res as $row){
            $insert_sql="insert into procedures_file (
            procedure_id,
            file_title,
            file_name,
            file,
            description,
            date_time_attach,
            date_time_cancellation,
            isEnable,
            file_code,
            cancel_description
            )
            VALUES(
                '{$procedure_id}',
                '{$row['file_title']}',
                '{$row['file_name']}',
                '{$row['file']}',
                '{$row['description']}',
                '{$row['date_time_attach']}',
                '{$row['date_time_cancellation']}',
                '{$row['isEnable']}',
                '{$row['file_code']}',
                '{$row['cancel_description']}'
            )";
           $res_d= $db->Query($insert_sql);
           $current_file_path= "../".PROCEDURES_DIR."/canceled_procedure_files/$RowID/".$row['file_name'];
           $transfer=copy($current_file_path, "../".PROCEDURES_DIR."/".$row['file_name']);
        }

        if($flag==2){
            return true;
        }
        return false;
    }
*/
    public function display_records_procedure($procedure_id){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT * FROM  procedure_reaction_detailes where procedure_id={$procedure_id}";
       //$ut->fileRecorder($sql);
        $res=$db->ArrayQuery($sql);
        if(count($res)>0)
        {
            $html='<table class="table table-borderd">
            <tr>
            <th>#</th>
            <th>کد یکتای رویه</th>
            <th>علت تغییر وضعیت</th>
            <th>وضعیت جاری</th>
            <th>کاربر ثیت کننده</th>
            <th>تاریخ ثیت</th>
            </tr>';
            $start=0;
            foreach($res as $row){
            
                $html.=
                '<tr>
                <td>'.($start+1).' </td>
                <td>'.$row['procedure_code'].' </td>
                <td>'.$row['reason_of_reaction'].' </td>
                <td>'.($row['last_status']==1?"فعال":"غیر فعال").' </td>
                <td>'.$this->get_user_fullname($row['user_id']).' </td>
                <td>'.$ut->greg_to_jal($row['registerition_date']).' </td>
                </tr>';
                $start++;
            }
            $html.="</table>";
           //$ut->fileRecorder($html);
            return $html;
        }
        return false;
    } 

    public function manage_last_cancelled_version_procedure($RowID)
    {
        $db = new DBi();
        $ut = new Utility();
        $acm=new acm();

        $get_cancelled_files="SELECT  * FROM procedures_cancelled_files where procedure_id={$RowID} ";
        $disable="";
        if(!$acm->hasAccess('manage_procedure_cancelled_files')){
            $get_cancelled_files.="AND  display_for_user=1";
            $disable="disabled";
        }
        $file_res = $db->ArrayQuery($get_cancelled_files);
        $procedure_id = $file_res[0]['procedure_id'];
        $ut->fileRecorder($get_cancelled_files);
        $get_reg_procedure_user_id="SELECT `user_id` FROM procedures WHERE RowID={$RowID}";
        $ut->fileRecorder($get_reg_procedure_user_id);
        $user_id_res=$db->ArrayQuery($get_reg_procedure_user_id);
        $add_permission=0;
        $ut->fileRecorder($_SESSION['userid']);
        $ut->fileRecorder($user_id_res[0]['user_id']);
        if(intval($_SESSION['userid'])===intval($user_id_res[0]['user_id'])){
            $add_permission=1;
        }
        else{
            $add_permission=0;
        }
        $file_html="";
        $file_html.='<table id="cancelled_file_tbl" class="table table-borderd">
                    <tr>
                        <th>انتخاب فایل </th>
                        <th>نام فایل</th>
                        <th>مشاهده </th>
                    </tr>';
        foreach($file_res as $row){
            $file_html.='<tr>
                            <td><input '.$disable.' type="checkbox"'.($row['display_for_user']==1?'checked':"").' value="'.$row['RowID'].'" class="uploaded_file_row" id="file_'.$row['RowID'].'"></td>
                            <td>'.$row['file_title'].'</td>
                            <td><button class="btn btn-primary"><a style="color:#fff" href="'.PROCEDURES_DIR.'/'.$row['file_name'].'" target="_blank"><i class="fa fa-download"></i></a></button></td>
                        </tr>';
        }
        $file_html.="</table>";
        $final_array['files_info']=$file_html;
        $final_array['add_permission']=$add_permission;
        return $final_array;

    }

   public function  attach_cancelled_procedure_file($prosedure_id,$file_info,$file_name,$files,$display_files_array,$not_display_files_array,$display_file)
   {
        $ut=new Utility();
        $cid=$prosedure_id;
        $db = new DBi(); 
        $cDate = date('Y/m/d');
        $cTime = date('H:i:s');
        $SFile = array();
        $allowedTypes = ['png','jpg','jpeg','jfif','pdf','xlsx','docx','zip','wav','PNG','JPG','JPEG','JFIF','PDF','XLSX','DOCX','WAV'];
        $sql="SELECT RowID ,unit_id,user_id from procedures where RowID={$cid}";
        $res_file_name=$db->ArrayQuery($sql);
        if($res_file_name[0]['user_id']==$_SESSION['userid'])
        {
            $procedure_row="cancelled-procedure-".$res_file_name[0]['RowID'];
            $unit_file_name="unit-".$res_file_name[0]['unit_id'];
            if (isset($files) && !empty($files)) {
                $no_files = count($files['name']);
            
                for ($i = 0; $i < $no_files; $i++) {
                    $filepath = $files["tmp_name"][$i];
                    if ($files["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                        return -1;
                    }
                    if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                        return -2;
                    }
                    
                    $format = substr($files['name'][$i], strpos($files['name'][$i], ".") + 1);
            
                    if(!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    }
                // $SFile[] = "attach" . rand(0, time()).'.'.$format;
                    //$SFile[] = "procedure_" . rand(0, time()).'.'.$format;
                    $SFile[] = $unit_file_name."_".$procedure_row ."_".rand(0, time()).'.'.$format;

                } 
            } 
            $dir=PROCEDURES_DIR_PATH;
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $dir_name="organizition_procedures_attachment";
            $cnt = count($SFile);
        
            for ($i=0;$i<$cnt;$i++) 
            {
                $upload = move_uploaded_file($files["tmp_name"][$i],$dir.$SFile[$i]);
                $path=$dir.$SFile[$i];
                $type=pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $current_date=date('Y-m-d H:i:s');
                $sql4 = "INSERT INTO `procedures_cancelled_files`(`procedure_id`,`file_name`,`file_title`,`file`,`date_time_attach`,`display_for_user`)
                VALUES ({$cid},'{$SFile[$i]}','{$file_name[$i]}','{$SFile[$i]}','{$current_date}','{$display_file[$i]}')";
                $res=$db->Query($sql4);
                if(!$res){
                    return -10;
                }
            }
            if(count($display_files_array)>0){
                foreach($display_files_array as $file_id)
                {
                    $update="UPDATE procedures_cancelled_files SET `display_for_user`=1 where RowID={$file_id}";
                //$ut->fileRecorder("update:".$update);
                    $res=$db->ArrayQuery($update);
                }
                
            }

            if(count($not_display_files_array)>0){
                foreach($not_display_files_array as $file_id)
                {
                    $update="UPDATE procedures_cancelled_files SET `display_for_user`=0 where RowID={$file_id}";
                //$ut->fileRecorder("update:".$update);
                    $res=$db->ArrayQuery($update);

                }
                
            }
        return true;
    }
else{
    return -4;
    }
}

public function get_procedures_manages()
{
    $db=new DBi();
    $ut=new Utility();
    $manager_sql="SELECT full_access_user_json FROM full_access_users where RowID=8";
    $manager_json=$db->ArrayQuery($manager_sql);
    $manager_array=json_decode($manager_json[0]['full_access_user_json'],true);
    $manger_ids=implode(",",$manager_array);
    $ut->fileRecorder($manger_ids);
    $full_access_user_sql="SELECT RowID, fname,lname FROM users where RowID IN ({$manger_ids})";
    $ut->fileRecorder($full_access_user_sql);
    $result=$db->ArrayQuery($full_access_user_sql);
    return $result;
}
}