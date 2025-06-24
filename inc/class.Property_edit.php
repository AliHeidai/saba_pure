<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
class Property
{

    public function __construct() {}

    public function get_property_status()
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

    public function get_personells()
    {
        $db = new DBi();
        $ut = new Utility();
        $sql = "SELECT `RowID`,`fname`,`lname` from `personnel` where `isEnable` =1";
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
        $deliver_date = $ut->jal_to_greg($deliver_date);
        $sql = "INSERT INTO `property_transaction` (`tool_id`,`deliver`,`receiver`,`tool_status`,`deliver_date`,`prop_num`,`desc`)
        VALUES('{$tool_id}','{$deliver}','{$receiver}','{$tool_status}','{$deliver_date}','{$prop_num}','{$desc}')";
        $res = $db->Query($sql);
        if ($res) {
            $transaction_id=$db->InsertrdID();
            $u_sql="UPDATE `property_tools` set `property_num`='{$prop_num}',`last_receiver`={$receiver}";
            $res=$db->Query($u_sql);
            foreach ($files['tmp_name'] as $key => $tmpName) {
                $fileData = base64_encode(file_get_contents($tmpName));
                $sql_attach = "INSERT INTO `property_attachment` (`transaction_id`,`file_name`,`file_type`,`file_size`,`file_data`) 
                VALUES('{$transaction_id}','{$files['name'][$key]}','{$files['type'][$key]}','{$files['size'][$key]}','{$fileData}')";
                $res = $db->Query($sql_attach);
            }
        } else {
            return false;
        }
    }
    public function get_tools_info($arr)
    {
        extract($arr);
        $db = new DBi();
        $sql = "SELECT * FROM `property_tools` where RowID='{$tool_id}'";
        $res = $db->ArrayQuery($sql);
        return $res[0]['last_receiver'];
    }
    public function showPropertyManageList($arr)
    {
        $db = new DBi();
        $ut=new Utility();
        extract($arr);
        $w=[];
        if(count($arr)>1){
           if($tools_id >-1){
                $w[]=" `tool_id`='{$tools_id}' ";
           }
           if($deliver >-1){
                 $w[]=" `deliver`='{$deliver}' ";
           }
           if($receiver >-1){
                $w[]=" `receiver`='{$receiver}' ";
           }
           if($tools_status >-1){
                 $w[]=" `tool_status`='{$tools_status}' ";
           }
           if(!empty($fdate)){
                $w[]=" `deliver_date`>='{$fdate}' ";
           }
           if(!empty($tdate)){
                 $w[]=" `deliver_date`<='{$tdate}' ";
           }
           if(!empty($p_num)){
                $w[]=" `prop_num`='{$p_num}' ";
           }
        
        }
        $w[]=" status=1";
        $where=implode(' AND ',$w);
        $per_page_rec=5;
        $start=($page*$per_page_rec)-$per_page_rec;
        $sql_count="SELECT COUNT(`RowID`) as `count` FROM `property_transaction` where {$where}  ";
        $c_res=$db->ArrayQuery($sql_count);
        $total_records=$c_res[0]['count'];
        $sql = "SELECT * FROM `property_transaction` where {$where} ORDER BY RowID DESC LIMIT $start,$per_page_rec ";
        $res = $db->ArrayQuery($sql);
        $htm="";
       
        //------------------------------------------------------------
        $sql_rd="SELECT RowID,concat(fname,' ',lname) as fullname  FROM personnel where isEnable=1";
        $res_rb=$db->ArrayQuery($sql_rd);
        $p_option='<option value="0">انبار دارایی ثابت</option>';
        foreach($res_rb as $rd_key=>$rd_value){
            $selected=($rd_value['RowID']==$deliver?'selected':'');
            $p_option.="<option {$selected} value='{$rd_value['RowID']}'>{$rd_value['fullname']}</option>";
        }

        $sql_t="SELECT RowID,tool_name   FROM property_tools where `status`=1";
        $res_t=$db->ArrayQuery($sql_t);

         foreach($res_t as $t_key=>$t_value){
             $selected=($t_value['RowID']==$tools_id?'selected':'');
            $t_option.="<option {$selected} value='{$t_value['RowID']}'>{$t_value['tool_name']}</option>";
        }

        $sql_s="SELECT `value`,`desc`   FROM property_tools_meta where `key`='transaction_type' AND `status`=1";
        $res_s=$db->ArrayQuery($sql_s);

         foreach($res_s as $s_key=>$s_value){
            $selected=($s_value['value']==$tools_status?'selected':'');
            $s_option.="<option value='{$s_value['value']}'>{$s_value['desc']}</option>";
        }


        //------------------------------------------------------------
        $counter=$start+1;
        $search_form=
             '<div style="padding:10px 0;background:#80808036" class="border ">
                    <form style="margin:10px" id="frm_search">
                        <div class="form-row align-items-center">
                            <div class="col-auto">
                                <select name="tools_id" id="tools_id" class="form-control mb-2">
                                <option value="-1"> ابزار را انتخاب نمایید</option>
                                '.$t_option.'
                                </select>
                            </div>
                            <!--<div class="col-auto">
                                <select name="deliver" id="deliver" class="form-control mb-2">
                                    <option value="-1"> تحویل دهنده را انتخاب نمایید</option>
                                     '.$p_option.'
                                </select>
                            </div>-->
                            <div class="col-auto">
                                <select  name="receiver" id="receiver" class="form-control mb-2">
                                    <option value="-1"> تحویل گیرنده را انتخاب نمایید</option>
                                     '.$p_option.'
                                </select>
                            </div>
                            <div class="col-auto">
                                <select  name="tools_status" id="tools_status"  class="form-control mb-2">
                                    <option value="-1">  وضعیت اموال /ابزار را انتخاب نمایید</option>
                                     '.$s_option.'
                                </select>
                            </div>
                            <div class="col-auto">
                                <input  name="fdate" id="fdate" class="form-control mb-2" type="text" value="'.$fdate.'" placeholder=" تاریخ شروع">
                            </div>
                            <div class="col-auto">
                                <input name="tdate" id="edate" class="form-control mb-2" type="text" value="'.$edate.'" placeholder=" تاریخ پایان">
                            </div>
                            <div class="col-auto">
                                <input name="p_num" id="p_num" class="form-control mb-2" type="text"  value="'.$p_num.'" placeholder="شماره اموال">
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary mb-2 "><i class="fa fa-search" onclick="showPropertyManageList()"></i></button>
                            </div>
                        </div>
                    </form>
                </div>';
        if(count($res)>0){
             
            $htm.="<table class='table table-bordered table-striped'>";
            $htm.="<tr>
                    <th>ردیف</th> 
                    <th>مشخصات ابزار/دارایی</th>
                    <th>تحویل دهنده</th>
                    <th>تحویل گیرنده</th>
                    <th>وضعیت دارایی/ابزار</th>
                    <th>تاریخ تراکنش</th>
                    <th>شماره اموال</th>
                    <th>توضیحات</th>
                    <th>عملیات</th>
                   
                </tr>";
        $ut->fileRecorder($sql);
        foreach($res as $key=>$value){
            $htm.="<tr>
            <td>{$counter}</td>
            <td>{$this->get_tools_name($value['tool_id'])}</td>
            <td>{$this->get_personel_name($value['deliver'])}</td>
            <td>{$this->get_personel_name($value['receiver'])}</td>
            <td>{$this->get_tools_status($value['tool_status'])}</td>
            <td>{$ut->greg_to_jal($value['deliver_date'])}</td>
            <td>{$value['prop_num']}</td>
            <td>{$value['desc']}</td>
            <td>
                <div class='d-flex justify-content-between'>
                <button title='ویرایش' onclick='editcreatePropertyManage({$value['RowID']})' class='btn btn-info'><i class='fa fa-edit'></i></button>
                <button title='حذف' onclick='deletePropertyManage({$value['RowID']})' class='btn btn-danger'><i class='fa fa-trash'></i></button>
                <button title='دانلود مستندات'onclick='get_transaction_attach({$value['RowID']})' class='btn btn-primary'><i class=' fa fa-link' ></i></button></td>
                </div>
            </tr>";
            $counter++;
        }
        $htm.='</table>';
       
        $htm.=$ut->generatePagination($total_records, $per_page_rec, $page, 'showPropertyManageList', '',$max_pages = 5);
      
    }
    else{
        $htm.="<p  style='padding:3rem' class='text-danger'>موردی ثبت نشده است .<p>";
    }
        $final_html= "<section style='width:100%;min-height:50vh;background-color:#fff;border-radius:10px;padding:10px'>".$search_form.$htm."</section>";
        return $final_html;
    }

    public function get_tools_name($tool_id){
        $db=new DBi();
        $sql="SELECT `tool_name` FROM `property_tools` where `RowID`='{$tool_id}'";
        $res=$db->ArrayQuery($sql);
        return $res[0]['tool_name'];
    }

    public function get_personel_name($pid){
        
        if($pid==0){
           
            return "انبار دارایی ثابت";
        }
        $db=new DBi();
        $sql="SELECT `fname`,`lname` FROM `personnel` where `RowID`='{$pid}'";
        $res=$db->ArrayQuery($sql);
        return $res[0]['fname']." ". $res[0]['lname'];
    }
    public function get_tools_status($status){
    
       
        $db=new DBi();
        $sql="SELECT `value`,`desc` FROM `property_tools_meta` where `value`='{$status}' AND `key`='transaction_type'";
        $res=$db->ArrayQuery($sql);
        return $res[0]['fname']." ". $res[0]['desc'];
    }

    public function get_transaction_attach($arr){
         $db=new DBi();
         $ut=new Utility();
         extract($arr);
        $sql="SELECT * from `property_attachment` where RowID='{$row_id}'";
       
        $res=$db->ArrayQuery($sql);
        $html="<table class='table table-bordered'>";
        $html.="<tr><th>ردیف</th><th>نام فایل</th><th>عملیات</th></tr>";
        $counter=1;
        foreach($res as $key=>$value){
            $html.="<tr><td>{$counter}</td><td>{$value['file_name']}</td><td><button class='btn btn-info' onclick='prop_download_file({$value['RowID']});'><i class=\"fa fa-download\"></i></button><button class='btn btn-danger' onclick='prop_download_file({$value['RowID']});'><i class=\"fa fa-trash\"></i></button></td></tr>";
            $counter++;
        }
        $html.="</table>";
        return $html;
    }

    public function prop_download_file($arr){
         $db=new DBi();
         $ut=new Utility();
         extract($arr);
         $ut->fileRecorder($arr);
         $sql="SELECT * from property_attachment where RowID={$row_id}";
         $res=$db->ArrayQuery($sql);
      
        return $res[0];
    
    }

    public function get_property_info($arr){
         $db=new DBi();
         $ut=new Utility();
         extract($arr);
        
         $sql="SELECT * from property_transaction where RowID={$id}";
         $res=$db->ArrayQuery($sql);
         $res[0]['deliver_date']=$ut->greg_to_jal($res[0]['deliver_date']);
         $arr2=[];
         $arr2['row_id']=$arr['id'];
         $file_attach=$this->get_transaction_attach($arr2);
      
        return array('p_info'=>$res[0],'p_file'=>$file_attach);
    }
}
