<?php
    require_once('config.php');
    require_once(ROOT . 'inc/jdf.php');
    function AutoLoad($className)
{
    if (file_exists(ROOT . 'inc/class.' . $className . '.php')) {

        require_once ROOT . 'inc/class.' . $className . '.php';
    }
}
date_default_timezone_set("Asia/Tehran");
spl_autoload_register('AutoLoad');

function update_fund_name(){
    $db=new DBi();
    $cm=new Comment();
    $sql="SELECT `RowID`,`Unit` FROM `pay_comment` where `isEnable`=1 ANd `layer1`=4 AND (`layer2`=13 OR `layer2`=14)";
    $res=$db->ArrayQuery($sql);
    foreach($res as $key=>$value){
        $meat_sql="SELECT count(`RowID`) as `count` FROM  `pay_comment_meta` where `key`='fund_number' AND pay_comment_id={$value['RowID']} AND `value` IS NOT NULL";
        $res=$db->ArrayQuery($meat_sql);
    
        if(intval($res[0]['count'])==0){
            $fund_num=$cm->create_fund_number($value['Unit'],$value['RowID']);
            $insert_sql="INSERT INTO `pay_comment_meta` (`pay_comment_id`,`key`,`value`,`description`) VALUES('{$value['RowID']}','fund_number','{$fund_num}','شماره تنخواه')";
            $res=$db->Query($insert_sql);
            echo $fund_num."<br>";
        }
    }
}

update_fund_name();
    
?>