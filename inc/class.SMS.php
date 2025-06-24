<?php


class SMS{
    public function __construct(){
       

    }

    private function sendByCurl($url,$param=[]){
        $handler = curl_init($url);             
		curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($handler, CURLOPT_POSTFIELDS, $param);                       
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		$response2 = curl_exec($handler);
		$response2 = json_decode($response2);
		$res_code = $response2[0];
		$res_data = $response2[1];
		return  $res_data;
    }
    private function getPanelInfo($property_key){
        $db=new DBi();
        $sql="SELECT `property_key`,`property_value` FROM `sms_panel_info` WHERE `property_key`='{$property_key}' ";
        $res=$db->ArrayQuery($sql);

        if(count($res)>0){
            return $res[0]['property_value'];
        }
        return 0;
    }

    public function sendBySOAP($message="",$mobile=[])
    {
        //$ut=new Utility();
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $client = new SoapClient("http://ippanel.com/class/sms/wsdlservice/server.php?wsdl");
            $user = "09155238839";
            $pass = "hW$8%jlq@nA&zP4z3r6Eabrash";
            $fromNum = "+983000505";
            $toNum = $mobile;
            $messageContent = $message;
            $op  = "send";
            $pattern_code="cjtp5in07dul99f";
            $input_data=array('cartable'=>' اظهارنظرها ');
            
           
            
            $res= $client->SendSMS($fromNum, $toNum, $user, $pass, $pattern_code, $input_data);
           
            return $res;
           
        } catch (SoapFault $ex) {
            return $ex->faultstring;
        }
    }

    public function sendBySOAP2($phone,$pattern_array)
    {
        //$ut=new Utility();
        ini_set("soap.wsdl_cache_enabled", "0");
        try {
            $client = new SoapClient("http://ippanel.com/class/sms/wsdlservice/server.php?wsdl");
            $user = "09155238839";
            $pass = "hW$8%jlq@nA&zP4z3r6Eabrash";
            $fromNum = "+983000505";
            $toNum = $mobile;
            $messageContent = $message;
            $op  = "send";
            $pattern_code="y5qiinp7p2k67cm";
            $input_data = $pattern_array;
            
           
            
            $res= $client->SendSMS($fromNum, $toNum, $user, $pass, $pattern_code, $input_data);
           
            return $res;
           
        } catch (SoapFault $ex) {
            return $ex->faultstring;
        }
    }

    public function send_by_pattern_api($phone,$pattern_code,$pattern_json){ //send sms by api
        $ut=new Utility();
        
        $username=$this->get_sms_info('username');
        $pass=$this->get_sms_info('password');
        $fromNum=$this->get_sms_info('from');
       // $fromNum="9850866";
        $api_url=$this->get_sms_info('send_api_url');  
        try
        {
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "op" : "pattern",
                "user" : "'.$username.'",
                "pass":  "'.$pass.'",
                "fromNum" : "'.$fromNum.'",
                "toNum": "'.$phone.'",
                "patternCode": "'.$pattern_code.'",
                "inputData" : 	[
                '.$pattern_json.'
                ]
            }
            ',
            CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            
            ),
            ));

            $response = curl_exec($curl);
            error_log($response);
            curl_close($curl);
            $ut->fileRecorder('EX..........................ok :'.$response);
            return $response;
        }
        catch (Exeption $e){
            $ut->fileRecorder('EX..........................',$Exeption);
        }
    }

    public function send_by_pattern($phone,$pattern_code,$pattern_array){ 
        $ut=new Utility();
        try{
            $client = new SoapClient("http://ippanel.com/class/sms/wsdlservice/server.php?wsdl");
            $user = "09155238839";
            $pass = "hW$8%jlq@nA&zP4z3r6Eabrash";
            $fromNum = "+983000505"; 
            $toNum = $phone; 
            $pattern_code = "hyg4w6794bvh1ws"; 
            $input_data =$pattern_array;//array("cyekta"=>"123456789","sabauser"=>"حیدری"); 

            $res=$client->sendPatternSms($fromNum,$toNum,$user,$pass,$pattern_code,$input_data);
            
        }
        catch(Exception $e){
            $ut->fileRecorder($phone);
           
            $ut->fileRecorder($e);
        }
    }

    public function get_sms_clients_info(){
        $db=new DBi();
        $personels="SELECT RowID, Fname, Lname FROM personnel where isEnable =1";
        $res=$db->ArrayQuery($personels);
        foreach($res as $k=>$v){
            $final_array[]=array('RowID'=>$v['RowID'],'FullName'=>$v['Fname']." ".$v['Lname']);
           // $final_array['FullName']=$v['Fname']." ".$v['Lname'];
        }
        return $final_array;
    }

    public function get_sms_info($key){
        $db = new DBi();
        $sql = "SELECT `property_value` FROM `sms_panel_info` where `property_key`='{$key}'";
        $res=$db->ArrayQuery($sql);
        return $res[0]['property_value'];
    }

    public function do_send_simple_sms($receptions,$from_number,$message){ 
        $f_number_arr=explode(",",$receptions);
        $receptions_arr="[";
        $db=new DBi();
        $ut=new Utility();
        foreach($f_number_arr as $k=>$v){
            $numbers="SELECT mobile FROM personnel where isEnable=1 AND RowID={$v}";
            $res=$db->ArrayQuery($numbers);
            $receptions_arr.='"'.$res[0]['mobile'].'",';
        }
        $receptions_arr=rtrim($receptions_arr,",");
        $receptions_arr.="]";
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api2.ippanel.com/api/v1/sms/send/webservice/single',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "recipient":'.$receptions_arr.'
        ,
        "sender": "+'.$from_number.'",
        "time": "",
        "message": "'.$message.'"
        }',
        CURLOPT_HTTPHEADER => array(
            'apikey: 2F7czAKwBgPAy_TPjwin2yMqRqLOYfA75CmKpDXSriI=',
            'Content-Type: application/json'
        ),
        ));

$response = curl_exec($curl);

curl_close($curl);
return  $response;


    }

    public function get_sms_credit(){
        $user=$this->get_sms_info('username');
        $pass=$this->get_sms_info('password');
        $service_url=$this->get_sms_info('service_url');
        $ut=new Utility();
            $param = array
                        (
                            'uname'=>$user,
                            'pass'=>$pass,
                            'op'=>'credit'
                        );
                        
            $handler = curl_init($service_url);             
            curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($handler, CURLOPT_POSTFIELDS, $param);                       
            curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
            $response2 = curl_exec($handler);
            
            $response2 = json_decode($response2);
            return number_format(round($response2[1]));
        
    }
    public function get_lines(){

        $user=$this->get_sms_info('username');
        $pass=$this->get_sms_info('password');
        $service_url=$this->get_sms_info('service_url');
        $ut=new Utility();
            $param = array
                        (
                            'uname'=>$user,
                            'pass'=>$pass,
                            'op'=>'lines'
                        );
		$handler = curl_init($service_url);             
		curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($handler, CURLOPT_POSTFIELDS, $param);                       
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		$response2 = curl_exec($handler);
		
		$response2 = json_decode($response2);
		$res_code = $response2[0];
		$res_data = $response2[1];
		
		
		return $res_data;
    }

    public function get_sms_panel_info(){
        $array=[];
        $lines=[];
        $ut=new Utility();
        $array['credit']=$this->get_sms_credit();
        $lines_arr=json_decode($this->get_lines(),true);
        foreach($lines_arr as $key=>$value){
            $ut->fileRecorder($value);
            $lines[]=json_decode($value,true);
        }

        $array['lines']=$lines;

        return $array;
    }
    
}