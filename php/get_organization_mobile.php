<?php
require_once('../config.php');
spl_autoload_register(function ($class_name) {
    include '../inc/class.' . $class_name . '.php';
});
switch ($_REQUEST['action']) {
    case "get_mobile":

        get_mobile($_REQUEST['rowid']);
        break;
    case "edit_create_mobile":
        edit_create_mobile($_POST);
        break;
}

function get_mobile($rowid)
{
    $db = new DBi();
    $sql = "select * from organization_mobiles where RowID={$rowid}";
    $res = $db->ArrayQuery($sql);
    die(json_encode($res[0]));
}

function edit_create_mobile($arr)
{
    $db = new DBi();
    if (intval($arr['rowid']) > 0) {
        $sql = "UPDATE organization_mobiles set 
    `organization_mobile = {$arr['organization_mobile']},
    `post`={$arr['post']},
    `fname`={$arr['fname']}
    `lname`={$arr['lname']}
    `unit_id`={$arr['unit']}
    `description`={$arr['description']}
    `uid`={$_SESSION['userid']}

    ";
    }
    $res = $db->Query($sql);
    if ($res) {
        die(true);
    } else {
        die(false);
    }
}
