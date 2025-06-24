<?php


require_once '../config.php';
require_once 'class.DBi.php';
require_once 'class.Utility.php';

$db=new DBi();
$done_com_sql="SELECT RowID from `pay_comment` where `transfer`=3 AND isEnable=1";
$res=$db->ArrayQuery($done_com_sql);
echo "start"."<br>";
$counter=0;
echo $counter;

foreach($res as $key=>$value){
   
    $sql="UPDATE `payment_workflow` set `done`=1 where pid={$value['RowID']}";
    $res=$db->Query($sql);
    $counter++;
}
echo "finished..."."<br>";

$get_not_done_sql="SELECT RowID FROM pay_comment where transfer !=3 AND isEnable=1";
$not_done_res=$db->ArrayQuery($get_not_done_sql);
foreach($not_done_res as $key=>$value){
    $workflow_status="SELECT * FROM payment_workflow where pid={$value['RowID']} order by RowID ASC";
    $res_workflow=$db->ArrayQuery($workflow_status);
    for($i=0;$i<count($res_workflow)-2;$i++){
        $update_workflow="UPDATE `payment_workflow` SET done=1 where RowID={$res_workflow[$i]['RowID']}";
       
        $res=$db->Query($update_workflow);
        if($res){
            echo $res_workflow[$i]['RowID']." <span style='color:green'> finished</span><br>";
        }
        else{
            echo " <span style='color:red'> ".$res_workflow[$i]['RowID']." - ".$update_workflow."</span><br>";
        }
    }
    echo $value['RowID']." finished"."<br>";
    
}



function show_array($array){
    echo "<pre>";
        print_r($array);
    echo "</pre>";
}
