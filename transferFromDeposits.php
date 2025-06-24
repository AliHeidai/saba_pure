<?php

$msg = [];
// $msg2 = [];
require_once 'config_test.php';
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
  $query = "SELECT * FROM account WHERE old_code_1403='{$code}'";
  $res = $new_db->ArrayQuery($query);
  return $res[0];
}

function get_deposit_data($code)
{
  $new_db = new DBi();
  $query = "SELECT * FROM `depositors` WHERE `old_code_1403`='{$code}'";
  custom_echo($query);

  $res = $new_db->ArrayQuery($query);
  custom_echo($res);
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
  $get_old_deposit = "SELECT * from `deposit` where pid='{$old_id}'";
  $d_res = $db_old->fetchAll($get_old_deposit);
  foreach ($d_res as $key => $row) {
    $deposit_arr = get_deposit_data($row['codeTafzili']);
    $code_taf = $deposit_arr['codeTafzili'];
    $depo_name = $deposit_arr['Name'];
    $acc_arr = get_acc_data($row['accID']);
    $acc_id = $acc_arr['RowID'];
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


if (isset($_POST['transfer'])) {
  if (!empty($_POST['uncode'])) {
    get_deposit_comment($_POST['uncode']);
  } else {
    $msg[] = 'کد یکتا وارد نشده است';
  }
}

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

  </div>
  <script src="js/bootstrap.min.js"></script>
</body>

</html>