<?php
/**
 * Created by PhpStorm.
 * User: MajidEbrahimi
 * Date: 2/26/2018
 * Time: 14:07
 */
if(!isset($_SESSION)){
    session_start();
    session_regenerate_id(true);
}
date_default_timezone_set("Asia/Tehran");
require_once str_replace('\\', '/', dirname(__FILE__)) . '/config.php';
function AutoLoad($className) {
    if(file_exists(ROOT . 'inc/class.' . $className . '.php')) {
        require_once ROOT . 'inc/class.' . $className . '.php';
    }
}
spl_autoload_register('AutoLoad');

if(!isset($_SESSION['username']) || strlen(trim($_SESSION['username']))==0 || !intval($_SESSION['userid']) || intval($_SESSION['IsAdmin'])==1 || $_SESSION['userAuten'] != "ok"){
    $ut = new Utility();
    $ut->Redirect(ADDR."login.php");
}else{
    $_SESSION['userAuten'] = '';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="پنل برنامه ریزی خط مونتاژ گروه کارخانجات ابرش">
    <meta name="author" content="www.abrashco.com">
    <link rel="icon" href="images/favicon.ico">

    <title>پنل کاربری ثبت وقایع انتظامات ابرش</title>

    <!-- Bootstrap CSS -->
    <link href="<?php echo ADDR ?>css/bootstrap.css" rel="stylesheet"  crossorigin="anonymous">
    <link href="<?php echo ADDR ?>css/bootstrap.rtl.css" rel="stylesheet" crossorigin="anonymous">
    <link href="<?php echo ADDR ?>css/fontawesome-all.css" rel="stylesheet" crossorigin="anonymous">
    <link href="<?php echo ADDR ?>css/jBox.css" rel="stylesheet" crossorigin="anonymous">
    <link href="<?php echo ADDR ?>css/jBox.Notice.css" rel="stylesheet" crossorigin="anonymous">
    <link href="<?php echo ADDR ?>css/style.css" rel="stylesheet" crossorigin="anonymous">
    <link href="<?php echo ADDR ?>css/js-persian-cal.css" rel="stylesheet" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link href="<?php echo ADDR ?>css/navbar-top-fixed.css" rel="stylesheet" crossorigin="anonymous">
    <link href="<?php echo ADDR ?>css/timedropper.css" rel="stylesheet" crossorigin="anonymous">
	<link href="<?php echo ADDR ?>css/bootstrap-datetimepicker.min.css" rel="stylesheet" crossorigin="anonymous">

</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-info">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="nav navbar-brand" href="" style="pointer-events: none;">پنل کاربری <?php echo $_SESSION['name']?></a>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="nav navbar-nav mr-auto" role="tablist">
                <?php
                $ac = new acm();
                if($ac->hasAccess('reportEventManage')){
                ?>
                    <li class="nav-item" style="border-left: 1px solid white;">
                        <a class="nav-link" id="linkid-ReportEvent" data-toggle="tab" href="#ReportEventID" role="tab" aria-controls="ReportEventID" aria-selected="false" onclick="ReportEventManage()">گزارشات وقایع</a>
                    </li>
                <?php
                }
                ?>
                <li class="nav-item" style="border-left: 1px solid white;">
                    <a class="nav-link" id="linkid-ReportLunchInvoice" data-toggle="tab" href="#ReportLunchInvoiceID" role="tab" aria-controls="ReportLunchInvoiceID" aria-selected="false" onclick="ReportLunchInvoiceManage()">گزارشات فیش غذا</a>
                </li>
                <li class="nav-item" style="border-left: 1px solid white;">
                    <a class="nav-link" id="linkid-LunchInvoice" data-toggle="tab" href="#LunchInvoiceID" role="tab" aria-controls="LunchInvoiceID" aria-selected="false" onclick="RecordLunchInvoiceManage()">ثبت فیش غذا</a>
                </li>
                <li class="nav-item" style="border-left: 1px solid white;">
                    <a class="nav-link" id="linkid-RecordKm" data-toggle="tab" href="#RecordKmID" role="tab" aria-controls="RecordKmID" aria-selected="false" onclick="RecordKilometerManage()">ثبت کیلومتر خودرو</a>
                </li>
                <li class="nav-item" style="border-left: 1px solid white;">
                    <a class="nav-link" id="linkid-RecordEvents" data-toggle="tab" href="#RecordEventsID" role="tab" aria-controls="RecordEventsID" aria-selected="false" onclick="RecordEventsManage()">ثبت وقایع</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="linkid-logout" data-toggle="tab" href="#logout" role="tab" aria-controls="logout" aria-selected="false" onclick="logout()">خروج</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main role="main" class="container mt-5" id="main-index">
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" role="tabpanel">
            <div class="row">
                <div class="col-md-12">
                    <div class="jumbotron">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title" style="color: red;text-align: center;font-size: 20px;">اطلاعیه ها</h5>
                                <hr>
                                <?php
                                $db = new DBi();
                                $nowDate = date('Y/m/d');
                                $sql10 = "SELECT `nDesc` FROM `notification` WHERE `nDate`='{$nowDate}' ";
                                $res = $db->ArrayQuery($sql10);
                                $CountRes = count($res);
                                for ($r = 0; $r < $CountRes; $r++) {
                                    $x = $r+1;
                                    echo '<p class="card-text" style="text-align: right;">'.$x.'_&nbsp;'.$res[$r]['nDesc'].'</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="jumbotron">
                        <h3>ثبت وقایع انتظامات</h3>
                        <p style="margin: 10px 0;font-size: 15px;">از طریق لینک زیر می توانید وقایع انتظامات را ثبت کنید.</p>
                        <?php
                        echo '<a onclick="programClick(\'linkid-RecordEvents\')" class="btn btn-info text-light" style="cursor: pointer;">&laquo;&nbsp; کلیک کنید</a>';
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="jumbotron">
                        <h3>ثبت کیلومتر خودرو</h3>
                        <p style="margin: 10px 0;font-size: 15px;">از طریق لینک زیر می توانید کیلومتر خودرو را ثبت کنید.</p>
                        <?php
                        echo '<a onclick="programClick(\'linkid-RecordKm\')" class="btn btn-info text-light" style="cursor: pointer;">&laquo;&nbsp; کلیک کنید</a>';
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="jumbotron">
                        <h3>ثبت فیش غذا</h3>
                        <p style="margin: 10px 0;font-size: 15px;">از طریق لینک زیر می توانید فیش غذا را ثبت کنید.</p>
                        <?php
                        echo '<a onclick="programClick(\'linkid-LunchInvoice\')" class="btn btn-info text-light" style="cursor: pointer;">&laquo;&nbsp; کلیک کنید</a>';
                        ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="jumbotron">
                        <h3>گزارشات فیش غذا</h3>
                        <p style="margin: 10px 0;font-size: 15px;">از طریق لینک زیر می توانید گزارشات فیش غذا را مشاهده کنید.</p>
                        <?php
                        echo '<a onclick="programClick(\'linkid-ReportLunchInvoice\')" class="btn btn-info text-light" style="cursor: pointer;">&laquo;&nbsp; کلیک کنید</a>';
                        ?>
                    </div>
                </div>
            </div> <!-- row -->
        </div><!-- tab-pane fade show active -->
        <div class="tab-pane fade" id="RecordEventsID" role="tabpanel" aria-labelledby="linkid-RecordEvents"></div>
        <div class="tab-pane fade" id="RecordKmID" role="tabpanel" aria-labelledby="linkid-RecordKm"></div>
        <div class="tab-pane fade" id="LunchInvoiceID" role="tabpanel" aria-labelledby="linkid-LunchInvoice"></div>
        <div class="tab-pane fade" id="ReportLunchInvoiceID" role="tabpanel" aria-labelledby="linkid-ReportLunchInvoice"></div>
        <?php
        $ac = new acm();
        if($ac->hasAccess('reportEventManage')) {
            ?>
            <div class="tab-pane fade" id="ReportEventID" role="tabpanel" aria-labelledby="linkid-ReportEvent"></div>
            <?php
        }
        ?>
    </div><!-- tab-content -->
</main>
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php echo ADDR ?>js/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script src="<?php echo ADDR ?>js/popper.min.js" crossorigin="anonymous"></script>
<script src="<?php echo ADDR ?>js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="<?php echo ADDR ?>js/js-persian-cal.min.js" crossorigin="anonymous"></script>
<script src="<?php echo ADDR ?>js/jBox.min.js" crossorigin="anonymous"></script>
<script src="<?php echo ADDR ?>js/jBox.Notice.js" crossorigin="anonymous"></script>
<script src="<?php echo ADDR ?>js/cjs.js" crossorigin="anonymous"></script>
<script src="<?php echo ADDR ?>js/Main.js" crossorigin="anonymous"></script>
</body>
</html>