<?php
echo "1"."<br>";
use SMS\IPPanel\Client;
use SMS\IPPanel\Errors\Error;
use SMS\IPPanel\Errors\ResponseCodes;
echo "5"."<br>";
require_once 'SMS/autoload.php';
echo "1"."<br>";
class SMS{
    public function __construct(){
       
    $client = new Client("2F7czAKwBgPAy_TPjwin2yMqRqLOYfA75CmKpDXSriI=");
   
    try {
      
        $pattern = $client->sendPattern("cjtp5in07dul99f", "+983000505", "09380523062", ['cartable' => "اظهارنظرها"]);
        var_dump($pattern);
    } catch (Error $e) {
      
        var_dump($e->unwrap());
        echo $e->getCode();

        if ($e->code() == ResponseCodes::ErrUnprocessableEntity) {
            echo "Unprocessable entity";
        }
    } catch (Exception $e) {
        var_dump($e->getMessage());
        echo $e->getCode();
    }
}
}
