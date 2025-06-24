<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
class Property
{

    public function __construct() {}

    public function get_transaction_type_list()
    {
        $db = new DBi();
        $sql = "SELECT * from `property_tools_meta` where `key` = 'transaction_type'";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

    public function get_property_tools_list()
    {
        $db = new DBi();
        $sql = "SELECT * from `property_tools` where `status` =1";
        $res = $db->ArrayQuery($sql);
        return $res;
    }

        public function get_tools_status_list(){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT `value`,`desc` FROM `property_tools_meta` where `key`='tools_status' ";
        $res=$db->ArrayQuery($sql);
        return $res;
    }

    public function get_personells()
    {
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `RowID`,`fname`,`lname` from `personnel`";
        $res = $db->ArrayQuery($sql);
        $property_w = ['RowID' => 0, 'fname' => 'انبار ', 'lname' => 'دارایی ثابت'];
        array_unshift($res, $property_w);
        return $res;
    }

    public function doEditCreatePropertyManage($arr, $files)
    {
        extract($arr);
        $db = new DBi();
        $ut = new Utility();
        $total_size = 0;
        $allowed_files = ['png', 'jpg', 'jpeg', 'pdf', 'xlsx', 'xls', 'rar', 'zip'];
        for ($i = 0; $i < count($files['size']); $i++) {
            $ext = pathinfo($files["name"][$i]);
            
            if (!in_array(strtolower($ext['extension']), $allowed_files)) {
                return -1;
            }
             $total_size += $files['size'][$i];
            if ($total_size > 5097152) {
                return -2;
            }
        }

        
        $deliver_date = $ut->jal_to_greg($deliver_date);
        if($pid==0){
            $sql = "INSERT INTO `property_transaction` (`tool_id`,`deliver`,`receiver`,`tool_status`,`transaction_type`,`damage_desc`,`deliver_date`,`prop_num`,`desc`)
            VALUES('{$tool_id}','{$deliver}','{$receiver}','{$tool_status}','{$transaction_type}','{$damage_desc}','{$deliver_date}','{$prop_num}','{$desc}')";
            $res = $db->Query($sql);
             $transaction_id = $db->InsertrdID();
        }
        elseif($pid>0){
           $sql="UPDATE `property_transaction` 
                SET `tool_id`='{$tool_id}',`deliver`='{$deliver}',`receiver`='{$receiver}',`tool_status`='{$tool_status}',
                `deliver_date`='{$deliver_date}',`prop_num`='{$prop_num}',`desc`='{$desc}',`transaction_type`='{$transaction_type}',`damage_desc`='{$damage_desc}' WHERE RowID={$pid} ";
           $res=$db->Query($sql);
            $transaction_id = $pid;
        }
        if ($res) {
           
            $u_sql = "UPDATE `property_tools` set `property_num`='{$prop_num}',`last_receiver`={$receiver} where RowID={$tool_id}";
            $res = $db->Query($u_sql);
            foreach ($files['tmp_name'] as $key => $tmpName) {
                $fileData = base64_encode(file_get_contents($tmpName));
                $sql_attach = "INSERT INTO `property_attachment` (`transaction_id`,`file_name`,`file_type`,`file_size`,`file_data`) 
                VALUES('{$transaction_id}','{$files['name'][$key]}','{$files['type'][$key]}','{$files['size'][$key]}','{$fileData}')";
                $res = $db->Query($sql_attach);
                $ut->fileRecorder($sql_attach);
            }
            return 1;
        } else {
            return false;
        }
    }

    
    public function doEditCreatePropertyTools($arr)
    {
        extract($arr);
        $db = new DBi();
        $ut = new Utility();
        if($tid==0){
           $sql_check_p_num="SELECT RowID FROM `property_tools` where `property_num`='{$prop_num}'";
        }
        else{
            $sql_check_p_num="SELECT RowID FROM `property_tools` where `property_num`='{$prop_num}' AND `RowID`!={$tid}";
        }
       $res_check_p_num = $db->ArrayQuery($sql_check_p_num);
       
        if($tid==0){
            if(count($res_check_p_num)>0){
                return -1; // property number already exists
            }
            $sql = "INSERT INTO `property_tools` (`code`,`tool_name`,`status`,`property_num`,`last_receiver`,`description`)
            VALUES('{$prop_num}','{$tool_name}','{$tool_status}','{$prop_num}','{$t_receiver}','{$damage_desc}')";
            $res = $db->Query($sql);
             $ut->fileRecorder($sql);
             if($res){
                return 1;
             }
             else{
                return false;
             }
           
        }
        elseif($tid>0){
            if(count($res_check_p_num)>1){
                return -1; // property number already exists
            }
           $sql="UPDATE `property_tools` 
                SET `code`='{$prop_num}',`tool_name`='{$tool_name}',`status`='{$tool_status}',`property_num`='{$prop_num}',
                `last_receiver`='{$t_receiver}',`description`='{$damage_desc}' WHERE RowID={$tid} ";
           $res=$db->Query($sql);
           $ut->fileRecorder($sql);
          if($res){
                return 1;
             }
             else{
                return false;
             }
        }
    }

    public function get_tools_info($arr)
    {
        extract($arr);
        $db = new DBi();
        $sql = "SELECT * FROM `property_tools` where RowID='{$tool_id}'";
        $res = $db->ArrayQuery($sql);
        return array('last_receiver'=>$res[0]['last_receiver'], 'property_num'=>$res[0]['property_num']);
    }

    public function showPropertyManageList($arr)
    {
        $db = new DBi();
        $ut = new Utility();
        $acm=new acm();
        extract($arr);
        $ut->fileRecorder($arr);
        $w = [];
        if (count($arr) > 1) {
            if ($tools_id > -1) {
                $w[] = " `tool_id`='{$tools_id}' ";
            }
            if ($deliver > -1) {
                $w[] = " `deliver`='{$deliver}' ";
            }
            if ($receiver > -1) {
                $w[] = " `receiver`='{$receiver}' ";
            }
            if ($tools_status > -1) {
                $w[] = " `tool_status`='{$tools_status}' ";
            }

            if ($transaction_type > -1) {
                $w[] = " `transaction_type`='{$transaction_type}' ";
            }

            if (!empty($fdate)) {
                $w[] = " `deliver_date`>='{$fdate}' ";
            }
            if (!empty($tdate)) {
                $w[] = " `deliver_date`<='{$tdate}' ";
            }
            if (!empty($p_num)) {
                $w[] = " `prop_num`='{$p_num}' ";
            }
        }
        $w[] = " status=1";
        $where = implode(' AND ', $w);
        $ut->fileRecorder('where:'.$where);
        $per_page_rec = 20;
        $start = ($page * $per_page_rec) - $per_page_rec;
        $sql_count = "SELECT COUNT(`RowID`) as `count` FROM `property_transaction` where {$where}  ";
        $c_res = $db->ArrayQuery($sql_count);
        $total_records = $c_res[0]['count'];
        $sql_trans = "SELECT * FROM `property_transaction` where {$where} ORDER BY RowID DESC LIMIT $start,$per_page_rec ";
        $ut->fileRecorder('where:'.$sql_trans);
        $res_trans = $db->ArrayQuery($sql_trans);
        $htm = "";

        //------------------------------------------------------------
        $sql_rd = "SELECT PersonnelCode, RowID,concat(fname,' ',lname) as fullname  FROM personnel where isEnable=1";
        $res_rb = $db->ArrayQuery($sql_rd);
        $p_option = '<option value="0">انبار دارایی ثابت</option>';
        foreach ($res_rb as $rd_key => $rd_value) {
            $selected = ($rd_value['RowID'] == $deliver ? 'selected' : '');
            $p_option .= "<option {$selected} value='{$rd_value['RowID']}'>{$rd_value['fullname']} - {$rd_value['PersonnelCode']}</option>";
        }

        $sql_t = "SELECT RowID,tool_name,property_num   FROM property_tools where `status`=1";
        $res_t = $db->ArrayQuery($sql_t);

        foreach ($res_t as $t_key => $t_value) {
            $selected = ($t_value['RowID'] == $tools_id ? 'selected' : '');
            $t_option .= "<option {$selected} value='{$t_value['RowID']}'>{$t_value['tool_name']} - {$t_value['property_num']}</option>";
        }

        $sql_s = "SELECT `value`,`desc`   FROM property_tools_meta where `key`='transaction_type' AND `status`=1";
        $res_s = $db->ArrayQuery($sql_s);

        foreach ($res_s as $s_key => $s_value) {
            $selected = ($s_value['value'] == $transaction_type ? 'selected' : '');
            $s_option .= "<option value='{$s_value['value']}'>{$s_value['desc']}</option>";
        }

        $sql_ts= $sql_s = "SELECT `value`,`desc`   FROM property_tools_meta where `key`='tools_status' AND `status`=1";
        $res_ts = $db->ArrayQuery($sql_ts);
        $ts_option="";
        foreach ($res_ts as $ts_key => $ts_value) {
            $selected = ($ts_value['value'] == $tools_status ? 'selected' : '');
            $ts_option .= "<option value='{$ts_value['value']}'>{$ts_value['desc']}</option>";
        }

        //------------------------------------------------------------
        $counter = $start + 1;
        $search_form =

            '<div class="btn-group" style="margin-bottom: 10px;" role="group" aria-label="...">
                <button type="button" id="" onclick="editcreatePropertyManage()" class="btn btn-light" title="ثبت تراکنش"><i class="fas fa-plus-square"></i>&nbsp;&nbsp;ثبت تراکنش</button>
                <button type="button" id="" onclick="create_prop_excel_report()" class="btn btn-light" title="گزارش اکسل"><i class="fas fa-file-excel"></i>&nbsp;&nbsp;گزارش اکسل</button>
                <button type="button" id="" onclick="print_prop_report()" class="btn btn-light" title="پرینت "><i class="fas fa-print"></i>&nbsp;&nbsp;پرینت </button>
            </div>
            <div style="padding:10px 0;background:#80808036" class="border ">
                    <form style="margin:10px" id="frm_search">
                        <div class="form-row align-items-center">
                            <div class="col-auto">
                                <select name="tools_id" id="tools_id_s" class="form-control mb-2">
                                <option value="-1"> ابزار را انتخاب نمایید</option>
                                ' . $t_option . '
                                </select>
                            </div>
                            <!--<div class="col-auto">
                                <select name="deliver" id="deliver_s" class="form-control mb-2">
                                    <option value="-1"> تحویل دهنده را انتخاب نمایید</option>
                                     ' . $p_option . '
                                </select>
                            </div>-->
                            <div class="col-auto">
                                <select  name="receiver" id="receiver_s" class="form-control mb-2">
                                    <option value="-1"> تحویل گیرنده را انتخاب نمایید</option>
                                     ' . $p_option . '
                                </select>
                            </div>
                            <div class="col-auto">
                                <select  name="transaction_type" id="transaction_type_s"  class="form-control mb-2">
                                    <option value="-1">نوع تراکنش را  انتخاب نمایید </option>
                                     ' . $s_option . '
                                </select>
                            </div>
                             <div class="col-auto">
                                <select  name="tools_status" id="tools_status_s"  class="form-control mb-2">
                                    <option value="-1">  وضعیت اموال /ابزار را انتخاب نمایید</option>
                                     ' . $ts_option . '
                                </select>
                            </div>
                            <div class="col-auto">
                                <input  name="fdate" id="fdate_s" class="form-control mb-2" type="text" value="' . $fdate . '" placeholder=" تاریخ شروع">
                            </div>
                            <div class="col-auto">
                                <input name="tdate" id="edate_s" class="form-control mb-2" type="text" value="' . $edate . '" placeholder=" تاریخ پایان">
                            </div>
                            <div class="col-auto">
                                <input name="p_num" id="p_num_s" class="form-control mb-2" type="text"  value="' . $p_num . '" placeholder="شماره اموال">
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary mb-2 "><i class="fa fa-search" onclick="showPropertyManageList()"></i></button>
                            </div>
                        </div>
                    </form>
                </div>';
                $ut->fileRecorder('count:'.count($res));
        if (count($res_trans) > 0) {

            $htm .= "<table class='table table-bordered table-striped'>";
            $htm .= "<tr>
                    <th>ردیف</th> 
                     <th>شماره اموال</th>
                    <th>مشخصات ابزار/دارایی</th>
                    <th>تحویل دهنده</th>
                    <th>تحویل گیرنده</th>
                    <th>وضعیت دارایی/ابزار</th>
                    <th>تاریخ تراکنش</th>
                   
                    <th>توضیحات</th>
                    <th>عملیات</th>
                   
                </tr>";
            $ut->fileRecorder('sssqqqlll:'.$sql);
            foreach ($res_trans as $key => $value) {
                $htm .= "<tr>
                <td>{$counter}</td>
                 <td>{$value['prop_num']}</td>
                <td>{$this->get_tools_name($value['tool_id'])}</td>
                <td>{$this->get_personel_name($value['deliver'])}</td>
                <td>{$this->get_personel_name($value['receiver'])}</td>
                <td>{$this->get_transaction_type($value['transaction_type'])}</td>
                <td>{$ut->greg_to_jal($value['deliver_date'])}</td>
               
                <td>{$value['desc']}</td>
                <td>
                <div class='d-flex justify-content-between'>";
            if($acm->hasAccess('property_manage_edit')){
                $htm.="<button title='ویرایش' onclick='editcreatePropertyManage({$value['RowID']})' class='btn btn-info'><i class='fa fa-edit'></i></button>";
            }
            if($acm->hasAccess('property_manage_delete')){
                $htm.="<button title='حذف' onclick='deletePropertyManageRow({$value['RowID']})' class='btn btn-danger'><i class='fa fa-trash'></i></button>";
            }
                $htm.="
                        <button title='دانلود مستندات'onclick='get_transaction_attach({$value['RowID']})' class='btn btn-primary'><i class=' fa fa-link' ></i></button>
                    </td>
                </div>
            </tr>";
                $counter++;
            }
            $htm .= '</table>';

            $htm .= $ut->generatePagination($total_records, $per_page_rec, $page, 'showPropertyManageList', '', $max_pages = 5);
        } else {
            $htm .= "<p  style='padding:3rem' class='text-danger'>موردی ثبت نشده است .<p>";
        }
        $final_html = "<section style='width:100%;min-height:50vh;background-color:#fff;border-radius:10px;padding:10px'>" . $search_form . $htm . "</section>";
        return $final_html;
    }
    //--------------------------------------------------------------------

    public function showPropertyToolsList($arr)
    {
        $db = new DBi();
        $ut = new Utility();
        $acm=new acm();
        extract($arr);
        $ut->fileRecorder($arr);
        $w = [];
        $page=intval($page)>0?$page:1;
        if (count($arr) > 1) {
            if ($tools_id > -1) {
                $w[] = " `RowID`='{$tools_id}' ";
            }
       
            if ($receiver > -1) {
                $w[] = " `last_receiver`='{$receiver}' ";
            }
            // if($tools_status==-1 || empty($tools_status)){
            //     $tools_status=1;
            // }
            if ($tools_status > -1) {
                $w[] = " `status`='{$tools_status}' ";
            }
            if (!empty($p_num)) {
                $w[] = " `property_num`='{$p_num}' ";
            }
        }
       
        $where = implode(' AND ', $w);
        if(!empty($where)){
            $where=" where {$where} ";
        }
        $per_page_rec = 20;
        $start = ($page * $per_page_rec) - $per_page_rec;
        $sql_count = "SELECT COUNT(`RowID`) as `count` FROM `property_tools` {$where}  ";
        $c_res = $db->ArrayQuery($sql_count);
        $total_records = $c_res[0]['count'];
        $sql_trans = "SELECT * FROM `property_tools`  {$where} ORDER BY RowID DESC LIMIT $start,$per_page_rec ";
        $ut->fileRecorder('where:'.$sql_count);
        $res_trans = $db->ArrayQuery($sql_trans);
        $htm = "";

        //------------------------------------------------------------
        $sql_rd = "SELECT PersonnelCode, RowID,concat(fname,' ',lname) as fullname  FROM personnel where isEnable=1";
        $res_rb = $db->ArrayQuery($sql_rd);
        $p_option = '<option value="0">انبار دارایی ثابت</option>';
        foreach ($res_rb as $rd_key => $rd_value) {
            $selected = ($rd_value['RowID'] == $deliver ? 'selected' : '');
            $p_option .= "<option {$selected} value='{$rd_value['RowID']}'>{$rd_value['fullname']} - {$rd_value['PersonnelCode']}</option>";
        }

        $sql_t = "SELECT RowID,tool_name,property_num   FROM property_tools where `status`=1";
        $res_t = $db->ArrayQuery($sql_t);

        foreach ($res_t as $t_key => $t_value) {
            $selected = ($t_value['RowID'] == $tools_id ? 'selected' : '');
            $t_option .= "<option {$selected} value='{$t_value['RowID']}'>{$t_value['tool_name']} - {$t_value['property_num']}</option>";
        }

        

        $sql_ts= $sql_s = "SELECT `value`,`desc`   FROM property_tools_meta where `key`='tools_status' AND `status`=1";
        $res_ts = $db->ArrayQuery($sql_ts);
        $ts_option="";
        foreach ($res_ts as $ts_key => $ts_value) {
            $selected = ($ts_value['value'] == $tools_status ? 'selected' : '');
            $ts_option .= "<option value='{$ts_value['value']}'>{$ts_value['desc']}</option>";
        }

        //------------------------------------------------------------
        $counter = $start + 1;
        $search_form =
            '<div class="btn-group" style="margin-bottom: 10px;" role="group" aria-label="...">
                <button type="button" id="" onclick="editcreatePropertyTools()" class="btn btn-light" title=" ثبت اموال /ابزار جدید"><i class="fas fa-plus-square"></i>&nbsp;&nbsp;ثبت اموال /ابزار جدید</button>
                <!--<button type="button" id="" onclick="create_prop_excel_report()" class="btn btn-light" title="گزارش اکسل"><i class="fas fa-file-excel"></i>&nbsp;&nbsp;گزارش اکسل</button>
                <button type="button" id="" onclick="print_prop_report()" class="btn btn-light" title="پرینت "><i class="fas fa-print"></i>&nbsp;&nbsp;پرینت </button>-->
            </div>
            <div style="padding:10px 0;background:#80808036" class="border ">
                    <form style="margin:10px" id="frm_search_tools">
                        <div class="form-row align-items-center">
                            <div class="col-auto">
                                <select name="tools_id" id="tools_id" class="form-control mb-2">
                                <option value="-1"> ابزار را انتخاب نمایید</option>
                                ' . $t_option . '
                                </select>
                            </div>
                           
                            <div class="col-auto">
                                <select  name="receiver" id="receiver" class="form-control mb-2">
                                    <option value="-1"> تحویل گیرنده را انتخاب نمایید</option>
                                     ' . $p_option . '
                                </select>
                            </div>
                            <!--<div class="col-auto">
                                <select  name="transaction_type" id="transaction_type"  class="form-control mb-2">
                                    <option value="-1">نوع تراکنش را  انتخاب نمایید </option>
                                     ' . $s_option . '
                                </select>
                            </div>-->
                             <div class="col-auto">
                                <select  name="tools_status" id="tools_status"  class="form-control mb-2">
                                    <option value="-1">  وضعیت اموال /ابزار را انتخاب نمایید</option>
                                     ' . $ts_option . '
                                </select>
                            </div>
                        
                            <div class="col-auto">
                                <input name="p_num" id="p_num" class="form-control mb-2" type="text"  value="' . $p_num . '" placeholder="شماره اموال">
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary mb-2 "><i class="fa fa-search" onclick="showPropertyToolsList()"></i></button>
                            </div>
                        </div>
                    </form>
                </div>';
               
        if (count($res_trans) > 0) {

            $htm .= "<table class='table table-bordered table-striped'>";
            $htm .= "<tr>
                    <th>ردیف</th> 
                     <th>شماره اموال</th>
                    <th>مشخصات ابزار/دارایی</th>
                    <th>آخرین تحویل گیرنده</th>
                    <th>وضعیت دارایی/ابزار</th>
                    <th>عملیات</th>
                </tr>";
            $ut->fileRecorder('sssqqqlll:'.$sql);
            foreach ($res_trans as $key => $value) {
                $htm .= "<tr>
                <td>{$counter}</td>
                 <td>{$value['property_num']}</td>
                <td>{$this->get_tools_name($value['RowID'])}</td>
                
                <td>{$this->get_personel_name($value['last_receiver'])}</td>
               
                <td>{$this->get_tools_status($value['status'])}</td>
               
                
                <td>
                <div class='d-flex justify-content-between'>";
            if($acm->hasAccess('property_tools_edit')){
                $htm.="<button title='ویرایش' onclick='editcreatePropertyTools({$value['RowID']})' class='btn btn-info'><i class='fa fa-edit'></i></button>";
            }
            if($acm->hasAccess('property_tools_delete')){
                $htm.="<button title='حذف' onclick='deletePropertyManageRow({$value['RowID']})' class='btn btn-danger'><i class='fa fa-trash'></i></button>";
            }
                // $htm.="
                //         <button title='دانلود مستندات'onclick='get_transaction_attach({$value['RowID']})' class='btn btn-primary'><i class=' fa fa-link' ></i></button>
                //     </td>
                // </div>
                $htm.="</td>
            </tr>";
                $counter++;
            }
            $htm .= '</table>';

            $htm .= $ut->generatePagination($total_records, $per_page_rec, $page, 'showPropertyToolsList', '', $max_pages = 5);
        } else {
            $htm .= "<p  style='padding:3rem' class='text-danger'>موردی ثبت نشده است .<p>";
        }
        $final_html = "<section style='width:100%;min-height:50vh;background-color:#fff;border-radius:10px;padding:10px'>" . $search_form . $htm . "</section>";
        return $final_html;
    }
    //--------------------------------------------------------------------
    public function get_tools_name($tool_id)
    {
        $db = new DBi();
        $sql = "SELECT `tool_name` FROM `property_tools` where `RowID`='{$tool_id}'";
        $res = $db->ArrayQuery($sql);
        return $res[0]['tool_name'];
    }

    public function deletePropertyManageRow($arr)
    {
        extract($arr);
        $ut=new Utility();
        $db = new DBi();
        $sql = "UPDATE `property_transaction` SET `status`=0 WHERE `RowID`='{$row_id}'";
        $ut->fileRecorder($sql);
        $res = $db->Query($sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public function get_personel_name($pid)
    {

        if ($pid == 0) {

            return "انبار دارایی ثابت";
        }
        $db = new DBi();
        $sql = "SELECT `fname`,`lname` FROM `personnel` where `RowID`='{$pid}'";
        $res = $db->ArrayQuery($sql);
        return $res[0]['fname'] . " " . $res[0]['lname'];
    }
    public function get_tools_status($status)
    {


        $db = new DBi();
        $sql = "SELECT `value`,`desc` FROM `property_tools_meta` where `value`='{$status}' AND `key`='tools_status'";
        $res = $db->ArrayQuery($sql);
        return $res[0]['fname'] . " " . $res[0]['desc'];
    }

    public function get_transaction_type($status)
    {
        $db = new DBi();
        $sql = "SELECT `value`,`desc` FROM `property_tools_meta` where `value`='{$status}' AND `key`='transaction_type'";
        $res = $db->ArrayQuery($sql);
        return $res[0]['fname'] . " " . $res[0]['desc'];
    }

    public function get_transaction_attach($arr)
    {
        $db = new DBi();
        $ut = new Utility();
        $acm=new acm();
        extract($arr);
        $sql = "SELECT * from `property_attachment` where `transaction_id`='{$row_id}' AND `deleted`=0";
    

        $res = $db->ArrayQuery($sql);
        if (count($res) == 0) {
            return array('file_count'=>0,"html"=>"<p class='text-danger'>هیچ فایلی برای این تراکنش ثبت نشده است.</p>");
        }
        $html = "<table class='table table-bordered' id='prop_manage_attach_table'>";
        $html .= "<tr><th>ردیف</th><th>نام فایل</th><th>عملیات</th></tr>";
        $counter = 1;
        $count_res=count($res);
        foreach ($res as $key => $value) {
            $html .= "<tr>
                            <td>{$counter}</td>
                            <td>{$value['file_name']}</td><td class='d-flex justify-content-between'>
                                    <button class='btn btn-info' type='button' onclick='prop_download_file({$value['RowID']});'><i class=\"fa fa-download\"></i></button>";
                            if($acm->hasAccess('property_attachment_delete')){
                                $html.="<button type=\"button\" class='btn btn-danger' onclick='deletePropertyManageAttachment({$value['RowID']},{$value['transaction_id']});'><i class=\"fa fa-trash\"></i></button>";
                            }
            $html.="</td></tr>";
            $counter++;
        }
        $html .= "</table><input type='hidden' id='prop_manage_rows' value='{$count_res}' />";
        return array('file_count'=>$count_res,'html'=>$html);
    }

    public function prop_download_file($arr)
    {
        $db = new DBi();
        $ut = new Utility();
        extract($arr);
        $ut->fileRecorder($arr);
        $sql = "SELECT * from property_attachment where RowID={$row_id}";
        $res = $db->ArrayQuery($sql);

        return $res[0];
    }

    public function get_property_info($arr)
    {
        $db = new DBi();
        $ut = new Utility();
        extract($arr);

        $sql = "SELECT * from property_transaction where RowID={$id}";
        $res = $db->ArrayQuery($sql);
        $res[0]['deliver_date'] = $ut->greg_to_jal($res[0]['deliver_date']);
        $arr2 = [];
        $arr2['row_id'] = $arr['id'];
        $ut->fileRecorder('ROWID');
        $ut->fileRecorder($arr2);
        $file_attach = $this->get_transaction_attach($arr2);

        return array('p_info' => $res[0], 'p_file' => $file_attach);
    }

    public function get_property_tools($arr)
    {
        $db = new DBi();
        $ut = new Utility();
        extract($arr);

        $sql = "SELECT * from property_tools where RowID={$id}";
        $res = $db->ArrayQuery($sql);
       // $res[0]['deliver_date'] = $ut->greg_to_jal($res[0]['deliver_date']);
        //$arr2 = [];
       // $arr2['row_id'] = $arr['id'];
       // $ut->fileRecorder('ROWID');
        //$ut->fileRecorder($arr2);
       // $file_attach = $this->get_transaction_attach($arr2);

        return  $res[0];
    }

    

    public function create_prop_excel_report($arr)
    {
        $db = new DBi();
        $ut = new Utility();
        $w = [];
        extract($arr);
        if (count($arr) > 1) {
            if ($tools_id > -1) {
                $w[] = " `tool_id`='{$tools_id}' ";
            }
            if ($deliver > -1) {
                $w[] = " `deliver`='{$deliver}' ";
            }
            if ($receiver > -1) {
                $w[] = " `receiver`='{$receiver}' ";
            }
            if ($tools_status > -1) {
                $w[] = " `tool_status`='{$tools_status}' ";
            }
            if (!empty($fdate)) {
                $w[] = " `deliver_date`>='{$fdate}' ";
            }
            if (!empty($tdate)) {
                $w[] = " `deliver_date`<='{$tdate}' ";
            }
            if (!empty($p_num)) {
                $w[] = " `prop_num`='{$p_num}' ";
            }
        }
        $w[] = " status=1";
        $where = implode(' AND ', $w);
        $sql = "SELECT `tool_id`,`deliver`,`receiver`,`tool_status`,`deliver_date`,`prop_num`,`desc` FROM property_transaction where  {$where}";
        $content = [];
        $res = $db->ArrayQuery($sql);

        foreach ($res as $key => $value) {
            $value['tool_id'] = $this->get_tools_name($value['tool_id']);
            $value['deliver'] = $this->get_personel_name($value['deliver']);
            $value['receiver'] = $this->get_personel_name($value['deliver']);
            $value['tool_status'] = $this->get_tools_status($value['tool_status']);
            $value['deliver_date'] = $ut->greg_to_jal($value['deliver_date']);
            $content[] = $value;
        }
        $hd = ['نام ابزار/اموال', 'تحویل دهنده', 'تحویل گیرنده', 'وضعیت اابزار/اموال', 'تاریخ تراکنش', 'شماره ابزار', 'توضیحات'];
        $fieldsName = ['tool_id', 'deliver', 'receiver', 'tool_status', 'deliver_date', 'prop_num', 'desc'];
        $name = "excel_report_" . rand();
        $filepath = $ut->createExcel($hd, $content, $fieldsName, $name);

        return $filepath;
    }
    public function deletePropertyManageAttachment($arr){
        $ut=new Utility();
        $db=new DBi();
        extract($arr);
        $sql="SELECT * from property_attachment where RowID=$fid";
    
        $sql="UPDATE `property_attachment` set deleted=1 where RowID=$fid";
        
        $res=$db->Query($sql);
        if($res){
            return true;
        }
        else{
            return false;
        }
    }

    public function print_prop_report($arr){
         $db = new DBi();
        $ut = new Utility();
        $w = [];
        extract($arr);
        if (count($arr) > 1) {
            if ($tools_id > -1) {
                $w[] = " `tool_id`='{$tools_id}' ";
            }
            if ($deliver > -1) {
                $w[] = " `deliver`='{$deliver}' ";
            }
            if ($receiver > -1) {
                $w[] = " `receiver`='{$receiver}' ";
            }
            if ($tools_status > -1) {
                $w[] = " `tool_status`='{$tools_status}' ";
            }
            if (!empty($fdate)) {
                $w[] = " `deliver_date`>='{$fdate}' ";
            }
            if (!empty($tdate)) {
                $w[] = " `deliver_date`<='{$tdate}' ";
            }
            if (!empty($p_num)) {
                $w[] = " `prop_num`='{$p_num}' ";
            }
        }
        $w[] = " status=1";
        $where = implode(' AND ', $w);
        $sql = "SELECT `tool_id`,`deliver`,`receiver`,`tool_status`,`deliver_date`,`prop_num`,`desc` FROM property_transaction where  {$where}";
        $content = [];
        $res = $db->ArrayQuery($sql);
        $hd = ['نام ابزار/اموال', 'تحویل دهنده', 'تحویل گیرنده', 'وضعیت اابزار/اموال', 'تاریخ تراکنش', 'شماره ابزار', 'توضیحات'];
        $html='<table class="table table-bordered">';
      
            $html.="<thead>
                <tr>";
                  foreach($hd as $h){
                    $html.="<th>{$h}</th>";
                  }
                $html.="</tr>
            </thead><tbody>";
        
        $ccounter = 1;          
        foreach ($res as $key => $value) {
            $html.="<tr>
             <td>{$counter}</td>
            <td>{$this->get_tools_name($value['tool_id'])}</td>
             <td>{$this->get_personel_name($value['deliver'])}</td>
             <td>{$this->get_personel_name($value['deliver'])}</td>
             <td>{$this->get_tools_status($value['tool_status'])}</td>
             <td>{$ut->greg_to_jal($value['deliver_date'])}</td>
             <td>{$value['prop_num']}</td>
             <td>{$value['desc']}</td>";
           
        }
        $html.="<tbody></table>";
        $print_array['title']="";
        $print_array['content']=$html;
         $_SESSION['PrintHtml']=$print_array;
       

        return true;
    }
}
