<?php
if (empty($_SESSION['token'])) {
  session_start();
}

if (empty($_SESSION['token']) || empty($_GET['token']) || @$_SESSION['token'] != @$_GET['token']) {
  die("HTTP/1.1 403 Forbidden
Content-Type: text/html

<html>
   <head><title>403 Forbidden</title></head>
   <body>
    <div style='color:red;width:100vw;height:100vh;background:linear-gradient(#0000ff4f,#ff000057); display:flex;justify-content:center;align-items:center;font-family:Iransans'> 
     <div> 
        <h1>Forbidden</h1>
        <p>You don't have permission to access /transferFrom1403.php on this server.</p>
      </div>
    </div>
   </body>
</html>");
}
$msg = [];
$msg2 = [];
require_once 'config.php';
require_once 'inc/class.DBi.php';
class manange_sabaold
{
  public $servername = "172.16.10.206";
  public $username = "programer";
  public $password = "@#yg6YMux&43MiEi*4KzK";
  public $db_name = "abrash_bom";
  public $conn;
  public function __construct()
  {
    try {
      $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->db_name", $this->username, $this->password);
      // set the PDO error mode to exception
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  function fetchAll($query)
  {
    try {
      $stmt = $this->conn->prepare($query);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }
  function runQuery($query)
  {
    try {
      $stmt = $this->conn->prepare($query);
      return $stmt->execute();
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }
}

function get_acc_data($code)
{
  $new_db = new DBi();
  $query = "SELECT * FROM account WHERE old_code1403='{$code}'";
  $res = $new_db->ArrayQuery($query);
  return @$res[0];
}

function transfer_pay_comment($result)
{
  $pid = 0;
  $db_new = new DBi();
  $get_sql = "SELECT * FROM `pay_comment` WHERE `unCode`='{$result[0]['unCode']}' AND `isEnable`=1 AND `paymentStatus`=0 ";
  $get_res = $db_new->ArrayQuery($get_sql);
  if (count($get_res) > 0) {
    echo "<p style='color:red'>اظهارنظر با کد {$result[0]['unCode']} قبلا انتقال داده شده است</p>";
    die();
  }

  foreach ($result as $key => $row) {
    $cFor = '';
    $pid = 0;
    @$accunt_data = get_acc_data($row['codeTafzili']);

    if (count($accunt_data) == 0) {
      die("طرف حساب  با کد تفضیلی : {$row['codeTafzili']} در سامانه تعریف نشده است<br>");
      continue;
    }
    $cFor_arr = explode(',', $row['cFor']);
    if (count($cFor_arr) > 0) {
      $cFor = $accunt_data['RowID'] . "," . $cFor_arr[1];
    } else {
      $cFor = $accunt_data['RowID'] . ",0";
    }

    $sql = "INSERT INTO `pay_comment` (
              `type`,cDate,Toward,Amount,CashSection,NonCashSection,Transactions,Unit,`desc`
              ,`uid`
              ,BillingID
              ,PaymentID
              ,cFor
              ,accNumber
              ,accName
              ,accBank
              ,codeTafzili
              ,nationalCode
              ,paymentMaturityCash
              ,paymentMaturityCheck
              ,RequestSource
              ,RequestNumbers
              ,unCode
              ,`transfer`
              ,receiverDate
              ,receiverTime
              ,senderUid
              ,descKesho
              ,payType
              ,sendType
              ,consumerUnit
              ,layer1
              ,layer2
              ,clearingFundDate
              ,multipleComment
              ,lastReceiver
              ,contractNumber
              ,tick
              ,checkNumber
              ,checkDate
              ,checkCarcass
              ,checkDeliveryDate
              ,sabtDarHesabType
              ,cardNumber
              ,layer3
              ,accountID
              ,isEnable
              ,goodLoan
              ,priorityLevel
              ,totalAmount
              ,isPaid
              ,paymentStatus
              ,checkOutType
              ,related_project
              ,related_vat
              ,PropertyNumber
              ,creditor_id
              ,set_prority_date
              ,child_sabt_dar_hesab_id
) VALUES (      '{$row['type']}'
              ,'{$row['cDate']}'
              ,'{$row['Toward']}'
              ,'{$row['Amount']}'
              ,'{$row['CashSection']}'
              ,'{$row['NonCashSection']}'
              ,'{$row['Transactions']}'
              ,'{$row['Unit']}'
              ,'{$row['desc']}'
              ,'{$row['uid']}'
              ,'{$row['BillingID']}'
              ,'{$row['PaymentID']}'
              ,'{$cFor}'
              ,'{$accunt_data['accountNumber']}'
              ,'{$accunt_data['Name']}'
              ,'{$accunt_data['bankName']}'
              ,'{$accunt_data['code']}'
              ,'{$accunt_data['codeMelli']}'
              ,'{$row['paymentMaturityCash']}'
              ,'{$row['paymentMaturityCheck']}'
              ,'{$row['RequestSource']}'
              ,'{$row['RequestNumbers']}'
              ,'{$row['unCode']}'
              ,'{$row['transfer']}'
              ,'{$row['receiverDate']}'
              ,'{$row['receiverTime']}'
              ,'{$row['senderUid']}'
              ,'{$row['descKesho']}'
              ,'{$row['payType']}'
              ,'{$row['sendType']}'
              ,'{$row['consumerUnit']}'
              ,'{$row['layer1']}'
              ,'{$row['layer2']}'
              ,'{$row['clearingFundDate']}'
              ,'{$row['multipleComment']}'
              ,'{$row['lastReceiver']}'
              ,'{$row['contractNumber']}'
              ,'{$row['tick']}'
              ,'{$row['checkNumber']}'
              ,'{$row['checkDate']}'
              ,'{$row['checkCarcass']}'
              ,'{$row['checkDeliveryDate']}'
              ,'{$row['sabtDarHesabType']}'
              ,'{$row['cardNumber']}'
              ,'{$row['layer3']}'
              ,'{$accunt_data['RowID']}'
              ,'{$row['isEnable']}'
              ,'{$row['goodLoan']}'
              ,'{$row['priorityLevel']}'
              ,'{$row['totalAmount']}'
              ,'{$row['isPaid']}'
              ,'{$row['paymentStatus']}'
              ,'{$row['checkOutType']}'
              ,'{$row['related_project']}'
              ,'{$row['related_vat']}'
              ,'{$row['PropertyNumber']}'
              ,'{$row['creditor_id']}'
              ,'{$row['set_prority_date']}'
              ,'{$row['child_sabt_dar_hesab_id']}')";


    $res = $db_new->Query($sql);

    if ($res) {
      $pid = $db_new->InsertrdID();
    }
    if ($pid == 0) {
      echo "<p style='color:red'>خطا در انتقال اظهارنظر با کد {$row['unCode']}</p>";
    } else {

      return $pid;
    }
  }
}

function custom_echo($str)
{
  if (is_array($str)) {
    echo "<p style='color:red'><pre>";
    print_r($str);
    echo "</p></pre>";
  } else
    echo "<p style='color:red'>{$str}</p>";
}

function transfer_comment_workflow($new_pid, $old_pid) //   انتقال پارف ها و گردش ها
{
  $db_old = new manange_sabaold();
  $query = "SELECT * FROM `payment_workflow` WHERE `pid`='{$old_pid}'";
  $res = $db_old->fetchAll($query);
  if ($res) {
    foreach ($res as $key => $row) {
      $sql = "INSERT INTO `payment_workflow`  
    (`pid`, `sender`, `receiver`, `status`, `createDate`, `createTime`, `description`, `temp`, `auto_send`, `view_history`, `done`)
      VALUES ('{$new_pid}', '{$row['sender']}', '{$row['receiver']}', '{$row['status']}', '{$row['createDate']}', '{$row['createTime']}', '{$row['description']}', '{$row['temp']}', '{$row['auto_send']}', '{$row['view_history']}', '{$row['done']}')";
      $db_new = new DBi();
      $res = $db_new->Query($sql);
    }
  }
}

function transfer_comment_attachments($new_pid, $old_pid) //   انتقال پیوست ها
{
  $db_old = new manange_sabaold();
  $query = "SELECT * FROM `payment_attachment` WHERE `pid`='{$old_pid}'";
  $res = $db_old->fetchAll($query);
  if ($res) {
    foreach ($res as $key => $row) {
      $sql = "INSERT INTO `payment_attachment`  
    (`pid`, `fileName`, `fileInfo`, `abilityDelete`, `createDate`, `createTime`, `uid`)
      VALUES ('{$new_pid}', '{$row['fileName']}', '{$row['fileInfo']}', '{$row['abilityDelete']}', '{$row['createDate']}', '{$row['createTime']}', '{$row['uid']}')";
      $db_new = new DBi();
      $res = $db_new->Query($sql);
      if ($res) {
        $old_file_path = 'http://172.16.10.206/attachment/' . $row['fileName'];
        $new_file_path = __DIR__ . '\/attachment/' . $row['fileName'];
        transfer_files($row['fileName'], $old_file_path, $new_file_path);
      }
    }
  }
}


function fetch_comment($un)
{
  $db_old = new manange_sabaold();
  $query = "SELECT * FROM pay_comment WHERE `unCode` = '{$un}' AND `isEnable`=1";
  $result = $db_old->fetchAll($query);
  if ($result[0]['sendType'] == 0) {
    die('<p style="color:red">اظهارنظر فورج نقدی قابلیت انتقال ندارد</p>');
  } elseif ($result[0]['sendType'] == 1) {
    die('<p style="color:red">اظهارنظر فورج چک قابلیت انتقال ندارد</p>');
  } elseif ($result[0]['sendType'] == 3) {
    die('<p style="color:red">اظهارنظر ثبت در حساب قابلیت انتقال ندارد</p>');
  }

  if ($result) {
    $pid = transfer_pay_comment($result);
    if ($pid) {
      transfer_comment_workflow($pid, $result[0]['RowID']);
      transfer_comment_attachments($pid, $result[0]['RowID']);
      if ($result[0]['layer1'] == 4 && in_array($result[0]['layer2'], [13, 14, 318])) {
        transfer_funds($pid, $result[0]['RowID']);
      }
      transfer_comment_payed($pid, $result[0]['RowID']);
    } else {
      $msg[] = "<p style='color:red'>خطا در انتقال اظهارنظر با کد {$un}</p>";
      // } else {
      //   $msg[]= "<p style='color:red'>خطا در انتقال اظهارنظر با کد {$un}</p>";
      // }
    }
    $update_qry = "UPDATE `pay_comment` SET `isEnable`=3 WHERE `unCode`='{$un}'";
    $result = $db_old->runQuery($update_qry);
    if ($result) {
      $msg[] = "<p style='color:green'>اظهارنظر با کد {$un} به حالت غیر فعال تغییر یافت</p>";
    } else {
      $msg[] = "<p style='color:red'>خطا در غیر فعال کردن اظهارنظر با کد {$un}</p>";
    }
  }
}

function transfer_files($file_name, $old_file_path, $new_file_path) //   انتقال پیوست ها
{
  $file_name = pathinfo($file_name, PATHINFO_FILENAME);

  $fileData = file_get_contents($old_file_path);
  $base64 = base64_encode($fileData);

  $fileData = base64_decode($base64);
  $result = file_put_contents($new_file_path, $fileData);
  if (!$result) {
    $msg[] = 'خطا در ذخیره عکس.';
  } else {

    $msg[] = 'عکس با موفقیت کپی شد.';
  }
}

function transfer_funds($new_pid, $old_pid)
{
  $db_old = new manange_sabaold();
  $query = "SELECT * FROM `fund_list` WHERE `pid`='{$old_pid}'";

  $res = $db_old->fetchAll($query);
  if (count($res) > 0) {

    foreach ($res as $key => $row) {
      $old_fid = $row['RowID'];
      $sql = "INSERT INTO `fund_list`  
    (`pid`,`uid`, `fundName`,`unCode`,`layer1`,`layer2`,`layer3`,`layer4`,`finalAmount`,`cDate`)
      VALUES ('{$new_pid}','{$row['uid']}','{$row['fundName']}','{$row['unCode']}','{$row['layer1']}','{$row['layer2']}','{$row['layer3']}','{$row['layer4']}','{$row['finalAmount']}','{$row['cDate']}')";
      $db_new = new DBi();

      $res = $db_new->Query($sql);
      if ($res) {
        $new_fid = $db_new->InsertrdID();

        transfer_fund_detailes($new_fid, $old_fid);
        // transfer_fund_attachment($new_fid,$old_fid);
      }
    }
  }
}

function transfer_fund_detailes($new_fid, $old_fid)
{
  $db_old = new manange_sabaold();
  $query = "SELECT * FROM `fund_list_details` WHERE `fid`='{$old_fid}'";


  $res = $db_old->fetchAll($query);
  if (count($res) > 0) {
    foreach ($res as $key => $row) {
      $sql = "INSERT INTO `fund_list_details`  (`fid`,`uid`,`createDate`,`description`,`reqNumber`,`placeUse`,`fundAmount`)VALUES ('{$new_fid}','{$row['uid']}','{$row['createDate']}','{$row['description']}','{$row['reqNumber']}','{$row['placeUse']}','{$row['fundAmount']}')";

      $db_new = new DBi();
      $res = $db_new->Query($sql);
      if ($res > 0) {
        $new_fdid = $db_new->InsertrdID();
        transfer_fund_attachment($new_fdid, $row['RowID']);
      }
    }
  }
}


function transfer_fund_attachment($new_fdid, $old_fdid)
{
  $db_old = new manange_sabaold();
  $query = "SELECT * FROM `fund_details_attach` WHERE `fdid`='{$old_fdid}'";

  $res = $db_old->fetchAll($query);
  if (count($res) > 0) {
    foreach ($res as $key => $row) {
      $sql = "INSERT INTO `fund_details_attach`  (`fdid`,`fileName`,`uid`)VALUES ('{$new_fdid}','{$row['fileName']}','{$row['uid']}')";

      $db_new = new DBi();
      $res = $db_new->Query($sql);
      if ($res) {
        $old_file_path = 'http://172.16.10.206/attachment/' . $row['fileName'];
        transfer_files($row['fileName'], $old_file_path, __DIR__ . '\/attachment/' . $row['fileName']);
      }
    }
  }
}

function transfer_comment_payed($new_pid, $old_pid) //   انتقال پیوست ها
{
  $db_old = new manange_sabaold();
  $query = "SELECT * FROM `deposit` WHERE `pid`='{$old_pid}'";
  $res = $db_old->fetchAll($query);
  if ($res) {
    foreach ($res as $key => $row) {
      $sql = "INSERT INTO `deposit`  
    (`pid`,`dDate`,`depositor`,`dBank`,`dAmount`,`dDesc`,`codeTafzili`,`uid`,`status`,`maliRecord`,`maliRecordDate`,`maliRecordTime`,`accID`,`dAmount_payed_by_others`)
      VALUES ('{$new_pid}','{$row['dDate']}','{$row['depositor']}','{$row['dBank']}','{$row['dAmount']}','{$row['dDesc']}','{$row['codeTafzili']}','{$row['uid']}','{$row['status']}','{$row['maliRecord']}','{$row['maliRecordDate']}','{$row['maliRecordTime']}','{$row['accID']}','{$row['dAmount_payed_by_others']}')";
     custom_echo(date("H:i:s").$sql);
     $db_new = new DBi();
      $res = $db_new->Query($sql);
    }
  }
}
//---------------------------------------------------------transfer_contract-------------------------------------------------------------
function transfer_contract($contract_num)
{
  // $old_contract = "SELECT * FROM `contract` where `number`='{$contract_num}'";
  // $db_old = new manange_sabaold();
  // $res = $db_old->fetchAll($old_contract);
  // if ($res) {
  //   foreach ($res as $key => $row) {
  insert_contract_row($contract_num);
  //   $sql = "INSERT INTO `contract`  
  // (`pid`,`dDate`,`depositor`,`dBank`,`dAmount`,`dDesc`,`codeTafzili`,`uid`,`status`,`maliRecord`,`maliRecordDate`,`maliRecordTime`,`accID`,`dAmount_payed_by_others`)
  //   VALUES ('{$new_pid}','{$row['dDate']}','{$row['depositor']}','{$row['dBank']}','{$row['dAmount']}','{$row['dDesc']}','{$row['codeTafzili']}','{$row['uid']}','{$row['status']}','{$row['maliRecord']}','{$row['maliRecordDate']}','{$row['maliRecordTime']}','{$row['accID']}','{$row['dAmount_payed_by_others']}')";
  //   $db_new = new DBi();
  //   $res = $db_new->Query($sql);
  //   }
  // }
}

function insert_contract_row($contract_num)
{
  $db_old = new manange_sabaold();
  $db = new DBi();
  $contract_num=trim($contract_num);
  $old_contract = "SELECT * FROM `contract` where `number`='{$contract_num}'";
  $res_old = $db_old->fetchAll($old_contract);
  custom_echo($old_contract);
  custom_echo($res_old);
  if ($res_old) {
    foreach ($res_old as $key => $row) {

      $sql = "INSERT INTO `contract` (`number`,`accountName`,`totalAmount`,`creditPeriod`,`csDate`,`ceDate`,`subject`,`codeTafzili`,`accNum`,
      `uid`,`hourAmount`,`maxHour`,`contractType`,`monthlyAmount`,`isEnable`,`archiveDescription`,`archiveDate`,
      `archiveTime`,`archiveUid`,`has_payment_formula`,`pay_method_type`,`description`
      ) VALUES('{$row['number']}','{$row['accountName']}','{$row['totalAmount']}','{$row['creditPeriod']}','{$row['csDate']}','{$row['ceDate']}','{$row['subject']}','{$row['codeTafzili']}','{$row['accNum']}','{$row['uid']}','{$row['hourAmount']}','{$row['maxHour']}','{$row['contractType']}','{$row['monthlyAmount']}','{$row['isEnable']}','{$row['archiveDescription']}','{$row['archiveDate']}','{$row['archiveTime']}','{$row['archiveUid']}','{$row['has_payment_formula']}','{$row['pay_method_type']}','{$row['description']}'
      )";
      $res = $db->Query($sql);
      if ($res) {
        $new_id = $db->InsertrdID();
        insert_contract_file_attachment($row['RowID'], $new_id);
      } else {
        $msg2[] = 'خطا در ذخیره قرارداد';
      }
    }
    $get_comment="SELECT unCode from pay_comment where `contractNumber` ='{$contract_num}'";
    $comment_old=  $db_old->fetchAll($get_comment);
    custom_echo($comment_old);
    foreach($comment_old as $row_comment){
      
      $get_sql="SELECT RowID FROM pay_comment where unCode={$row_comment['unCode']}";
      $res_count=$db->ArrayQuery($get_sql);
      if(count($res_count)==0){
        fetch_comment($row_comment['unCode']);
        // get_deposit_comment($row_comment['unCode']);
      }
    }
    $sql="UPDATE contract set isEnable=2 where `number`='{$contract_num}'";
    $res=$db_old->runQuery($sql);
  }
}

function insert_contract_file_attachment($old_id, $new_id)
{
  $db_old = new manange_sabaold();
  $db = new DBi();
  $old_file_attach = "SELECT * FROM `contract_attachment` WHERE `cid`='{$old_id}'";
  $res_old = $db_old->fetchAll($old_file_attach);
  if ($res_old) {
    foreach ($res_old as $key => $row) {
      //custom_echo($row);
      $ins_sql="INSERT INTO `contract_attachment` (`cid`,`fileName`,`fileInfo`) VALUES('{$new_id}','{$row['fileName']}','{$row['fileInfo']}')";
     // custom_echo($ins_sql);
      $res=$db->Query($ins_sql);
      if($res){
        $old_file_path = 'http://172.16.10.206/attachment/' . $row['fileName'];
        transfer_files($row['fileName'], $old_file_path, __DIR__ . '\/attachment/' . $row['fileName']);
      //  transfer_contract_addendum($old_id,$new_id);
      }

    }
  }
}

// function transfer_contract_addendum($old_id,$new_id){
//   $db_old = new manange_sabaold();
//   $db = new DBi();
//   $old_addendum="SELECT * FROM `contract_addendum`";
// }


//----------------------------------------------------get_deposit------------------------------------------------------


function get_deposit_data($code)
{
  $new_db = new DBi();
  $query = "SELECT * FROM `depositors` WHERE `old_code_1403`='{$code}'";
  //custom_echo($query);

  $res = $new_db->ArrayQuery($query);
  //custom_echo($res);
  return $res[0];
}

function get_deposit_comment($unCode)
{

  $db_old = new manange_sabaold();
  $db = new DBi();
  $old_sql = "SELECT RowID from `pay_comment` where `unCode`={$unCode}";
  $res_old = $db_old->fetchAll($old_sql);
  $old_id = $res_old[0]['RowID'];
  $new_sql = "SELECT RowID from `pay_comment` where `unCode`={$unCode}";
  $res_new = $db->ArrayQuery($new_sql);
  $new_id = $res_new[0]['RowID'];
  //------------------------------------------------------------------
  $get_old_deposit = "SELECT * from `deposit` where pid='{$old_id}'";
  $d_res = $db_old->fetchAll($get_old_deposit);
//-------------------------------------------------------------
  $get_old_check = "SELECT * from `bank_check` where cid='{$old_id}'";
  $c_res = $db_old->fetchAll($get_old_check);

  foreach ($d_res as $key => $row) {
    transfer_deposits($row,$new_id);
  }
  foreach ($c_res as $key => $row_check) {
    transfer_bank_check($row_check,$new_id);
  }
}

function transfer_deposits($row,$new_id){
  $db=new DBi();
  $db_old=new manange_sabaold();
   $deposit_arr = get_deposit_data($row['codeTafzili']);
    $code_taf = $deposit_arr['codeTafzili'];
    $depo_name = $deposit_arr['Name'];
    $acc_arr = get_acc_data($row['accID']);
    $acc_id = @$acc_arr['RowID'];
    $sql = "INSERT INTO deposit (`pid`,`dDate`,`depositor`,`dBank`,`dAmount`,`dDesc`,`codeTafzili`,`uid`,`status`,`maliRecord`,`maliRecordDate`,`maliRecordTime`,`accID`,`dAmount_payed_by_others`)VALUES(
            '{$new_id}','{$row['dDate']}','{$depo_name}','{$row['dBank']}','{$row['dAmount']}','{$row['dDesc']}','{$code_taf}','{$row['uid']}','{$row['status']}','{$row['maliRecord']}','{$row['maliRecordDate']}','{$row['maliRecordTime']}','{$acc_id}','{$row['dAmount_payed_by_others']}')";
    $res = $db->Query($sql);
    if ($res) {
      $new_deposit_id = $db->InsertrdID();
      $sql_pa = "SELECT * FROM `payment_receipt` where `did`='{$row['RowID']}'";
      $d_res = $db_old->fetchAll($sql_pa);
      foreach ($d_res as $key => $old_files) {
        $ins_file = "insert into `payment_receipt` (
                    `did`
                    ,`fileName`
                    )VALUES(
                  '{$new_deposit_id}',
                  '{$old_files['fileName']}'
                  )";
        $res_n = $db->Query($ins_file);
        if ($res_n) {
          $old_file_path = 'http://172.16.10.206/paymentReceipt/' . $old_files['fileName'];
          $new_file_path = __DIR__ . '\/paymentReceipt/' . $old_files['fileName'];
          transfer_files($old_files['fileName'], $old_file_path, $new_file_path);
        }
      }
    }
}

function transfer_bank_check($row,$new_id){
  $db=new DBi();
  $db_old=new manange_sabaold();
  $acc_arr = get_acc_data($row['accID']);
  $acc_id = $acc_arr['RowID'];
  $sql = "INSERT INTO `bank_check` (`cid`,`check_amount`,`check_date`,`check_number`,`uid`,`accID`,`checkType`,`description`) VALUES(
            '{$new_id}','{$row['check_amount']}','{$row['check_date']}','{$row['check_number']}','{$row['uid']}','{$acc_id}','{$row['checkType']}','{$row['description']}')";
    $res = $db->Query($sql);
    // if ($res) {
    //   $new_deposit_id = $db->InsertrdID();
    //   $sql_pa = "SELECT * FROM `payment_receipt` where `did`='{$row['RowID']}'";
    //   $d_res = $db_old->fetchAll($sql_pa);
    //   foreach ($d_res as $key => $old_files) {
    //     $ins_file = "insert into `payment_receipt` (
    //                 `did`
    //                 ,`fileName`
    //                 )VALUES(
    //               '{$new_deposit_id}',
    //               '{$old_files['fileName']}'
    //               )";
    //     $res_n = $db->Query($ins_file);
    //     if ($res_n) {
    //       $old_file_path = 'http://172.16.10.206/paymentReceipt/' . $old_files['fileName'];
    //       $new_file_path = __DIR__ . '\/paymentReceipt/' . $old_files['fileName'];
    //       transfer_files($old_files['fileName'], $old_file_path, $new_file_path);
    //     }
    //   }
    // }
}
  
//----------------------------------------------------------------------------------------------------------
//---------------------------------------------------------transfer_contract-------------------------------------------------------------
if (isset($_POST['transfer'])) {
  if (!empty($_POST['uncode'])) {
    fetch_comment($_POST['uncode']);
    get_deposit_comment($_POST['uncode']);
  } else {
    $msg[] = 'کد یکتا وارد نشده است';
  }
}

if (isset($_POST['transfer_contract'])) {
  if (!empty($_POST['contract_num'])) {
    transfer_contract($_POST['contract_num']);
  } else {
    $msg2[] = 'شماره قرارداد وارد نشده است';
  }
}

if (isset($_POST['transfer_Deposit_btn'])) {
  if (!empty($_POST['transfer_Deposit_Comment'])) {
    get_deposit_comment($_POST['transfer_Deposit_Comment']);
    $msg3[] = 'انتقال اظهارنظر با کد یکتا ' . $_POST['transfer_Deposit_Comment'] . ' با موفقیت انجام شد';
  } else {
    $msg2[] = 'کد یکتای اظهارنظر وارد نشده است';
  }
}

else{
  
}


//transfer_comment_workflow(81, 10173);
//transfer_comment_attachments(81, 10173);
//transfer_funds(81,10953);
// Close the connection 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>انتقال اظهارنظر از 1403</title>
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="css/bootstrap.rtl.css" />
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <style>
    * {
      font-family: IranSans;
    }
  </style>
</head>

<body>
  <div style="width:100vw;height:100vh;background:linear-gradient(#0000ff4f,#ff000057); display:flex;justify-content:center;align-items:center;flex-direction:column ">
<div>
  <fieldset>
  <legend>انتقال  اظهارنظر ها</legend> 
    <form method="POST" class="my-2" style="width:500px;background:#fff;padding:1.5rem; border-radius:10px">
      <div class="form-group row">
        <label for="staticEmail" class="col-sm-4 col-form-label">کد یکتا</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="uncode" name="uncode" value="">
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-12">
          <input type="submit" class="btn btn-primary col-sm-12" id="inputPassword" name="transfer" value="انتقال" />
          <?php
          $text = "<br><br>";
          foreach ($msg as $row) {

            $text .= "<p style='color:red;text-align:center'>" . $row . "</p>";
          }
          echo $text;
          ?>
        </div>
    </form>
 
  </div>
  </fieldset>
  <!----------------------------------- contract form------------------------->
  <div>
  <fieldset>
  <legend>انتقال قرارداد ها</legend>
    <form class="my-2" method="POST" style="width:500px;background:#fff;padding:1.5rem; border-radius:10px">
      <div class="form-group row">
        <label for="staticEmail" class="col-sm-4 col-form-label"> شماره قرارداد</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="contract_num" name="contract_num" value="">
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-12">
          <input type="submit" class="btn btn-primary col-sm-12" id="inputPassword" name="transfer_contract" value="انتقال" />
          <?php
          $text = "<br><br>";
          foreach ($msg2 as $row) {

            $text .= "<p style='color:red;text-align:center'>" . $row . "</p>";
          }
          echo $text;
          ?>
        </div>
      </div>
  </div>

  </form>
        </fieldset>

  <!-- </div>

<div> -->
  <fieldset>
    <legend>انتقال پرداخت ها</legend>
    <form class="my-2" method="POST" style="width:500px;background:#fff;padding:1.5rem; border-radius:10px">
      <div class="form-group row">
        <label for="staticEmail" class="col-sm-4 col-form-label">کد یکتا اظهارنظر</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="contract_num" name="transfer_Deposit_Comment" value="">
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-12">
          <input type="submit" class="btn btn-primary col-sm-12" id="inputPassword" name="transfer_Deposit_btn" value="انتقال" />
          <?php
          $text = "<br><br>";
          foreach ($msg2 as $row) {

            $text .= "<p style='color:red;text-align:center'>" . $row . "</p>";
          }
          echo $text;
          ?>
        </div>
      </div>
  </div>

  </form>
        </fieldset>


  </div><!---------------------------------------------------------------------------------------------------------------





  </div>
  <script src="js/bootstrap.min.js"></script>
</body>

</html>