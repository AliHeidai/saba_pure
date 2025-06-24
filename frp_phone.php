<?php


// error_reporting(E_ALL);
    include_once ('config.php');
    include_once ('inc/class.Utility.php');
    include_once ('inc/class.DBi.php');
    include_once ('inc/class.acm.php');
    $db=new DBi();
   // $get_units_sql="SELECT unitName from relatedunits ";
    $mobile_organ_sql="SELECT o.`RowID`,o.`organization_mobile`,o.`post`,u.`Uname`,o.`fname`,o.`lname` FROM `organization_mobiles` as o
        LEFT JOIN `official_productive_units` AS u ON u.`RowID`=o.`unit_id`
       
        WHERE o.`organization_mobile` IS NOT NULL AND o.`status`=1 AND o.`office_name`='frp'";
    $mobile_organ_res=$db->ArrayQuery($mobile_organ_sql);
    session_start();
	
?>
<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="description" content="">
    <meta name="author" content="Mahdi Rezvani">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="icon" href="images/favicon.ico">
    <!-- Bootstrap CSS -->
    <link href="<?php  echo ADDR ?>css/bootstrap.css" rel="stylesheet">
    <link href="<?php  echo ADDR ?>css/fontawesome.css" rel="stylesheet" type="text/css">
    <link href="<?php  echo ADDR ?> css/fontawesome.css" rel="stylesheet" type="text/css" crossorigin="anonymous">
    <link href="<?php  echo ADDR ?> css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <script src="<?php  echo ADDR ?>js/jquery-3.3.1.min.js"></script>
    <script src="<?php  echo ADDR ?>js/jquery-ui.min.js"></script>
    <script src="<?php  echo ADDR ?>js/popper.min.js"></script>
    <script src="<?php  echo ADDR ?>js/bootstrap.min.js" ></script>
    <script src="<?php  echo ADDR ?>js/bootstrap-select.min.js"></script>
    <script src="<?php  echo ADDR ?>js/jquery.dataTables.min.js"></script>
    <script src="<?php  echo ADDR ?>js/fa.json"></script>
    
    <title>ارتباط با پرسنل</title>
</head>
<script>

    $(window).resize(function() {
        if ($(window).width() <= 1200 && $(window).width() >= 767) {
            $(".stly").css("font-size", "150%");        }
        else {
            $(".stly").css("font-size", "210%");
            $("#black-link").css("");
        }

        if ($(window).width() <= 760) {
            $("#exit").css("width", "80px");

        }else{
            $("#exit").css("width", "150px");

        }
    });
    $("document").ready(function(){
  
        create_data_table("organization_mobiles")

    })
    function create_data_table(table_id){
    new DataTable('#'+table_id, {
         language:{
           url: 'js/fa.json',
           //url:'//cdn.datatables.net/plug-ins/1.13.7/i18n/fa.json',
        },
        pagingType: 'full_numbers',
       
    });
}

</script>
<style>
    td{
        font-weight: bold;
    }
    .sh:hover{
        box-shadow: 5px 10px black;
    }
    .stly{
        font-size: 210%;
        font-family: 'BNazanin',serif;
        border-radius: 30px;
        color: white;
        width: 100%;
        height: 150px;
    }
    button, h2,span{
        font-family: 'BNazanin', serif;
        font-size:30px;
    }
	.close-btn {
		width: 50px !important;
		height: 50px;
		border-radius: 100%;
		background-color: #00a1ff;
		color: #fff;
		text-align: center;
		display: flex;
		justify-content: center;
		align-items: center;
		float: left;
	}
	.close-btn a{
		font-weight:700;
		font-size:22px;
		text-decoration:none;
		color:#fff;
	}
    .modal-header{
        justify-content: right;
    }
	.table-bordered td{
		font-size:1.3rem;
	}
</style>
<body style="background-color: #2d3748;">
<div class="container">
    <div>
		<div class="row" style="margin-top: 3%;direction:rtl">
   
			<div class="col-sm-5">

				<h2 style="color: white;font-family: 'BNazanin', serif"> ارتباط با پرسنل (فورج فلزات رنگین پارسیان) </h2>
			</div>
				 <div class="col-sm-7">
				<span id="exit"  class="close-btn"><a href="login.php" title="بازگشت">&#10149;</a></span>
			</div>
		</div>
        <div class="row" style="margin-top: 3%">
            <div class="col-sm-3">
                
				<button id="test" type="button" class="sh btn .btn-default stly"  style="background-color: #3068FF;" data-toggle="modal" data-target="#managmentmodal">واحد مدیریت</button>

            </div>
            <!-- <div class="col-sm-3">
                
				<button id="test" type="button" class="sh btn .btn-default stly"  style="background-color: #3068cc;" data-toggle="modal" data-target="#viramanagmentmodal"> <span>ویرا تجارت  نوین ابرش </span> <br><span>(دفتر تهران)</span></button>

            </div> -->
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #FF0000" data-toggle="modal" data-target="#hesabrasimodal">واحد حسابرسی</button>

            </div>
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #FFB20E" data-toggle="modal" data-target="#malimodal">واحد مالی</button>

            </div>
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #FF3298" data-toggle="modal" data-target="#ansanimodal">واحد منابع انسانی و اداری</button>

            </div>
           
        </div>
        <div class="row" style="margin-top: 3%">
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #37CDC1" data-toggle="modal" data-target="#fanavarimodal">واحد فناوری اطلاعات</button>

            </div>
        
        
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #D333E7" data-toggle="modal" data-target="#bazarganiforoshmodal">واحد بازرگانی فروش</button>

            </div>
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #23B1D1" data-toggle="modal" data-target="#poshtibanimodal">واحد پشتیبانی</button>

            </div>
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #FF5BD7" data-toggle="modal" data-target="#hoghoghmodal">واحد حقوقی</button>

            </div>

        </div>
        <div class="row" style="margin-top: 3%">
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: rgb(79,129,70)" data-toggle="modal" data-target="#netmodal">واحد نت</button>

            </div>
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #3981FF" data-toggle="modal" data-target="#kharidmodal">واحد بازرگانی خرید</button>

            </div>
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #CF2ECF" data-toggle="modal" data-target="#mohandesimodal">واحد مهندسی</button>

            </div>
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #9b8300" data-toggle="modal" data-target="#barnamehmodal">واحد برنامه ریزی</button>

            </div>

        </div>
        <div class="row" style="margin-top: 3%">
            <!-- <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #FEA62F" data-toggle="modal" data-target="#ghalebmodal">واحد قالبسازی</button>

            </div> -->
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #646A7A" data-toggle="modal" data-target="#anbarmodal"> انبارها</button>

            </div>
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: rgb(124,111,37)" data-toggle="modal" data-target="#omranmodal">واحد امور عمرانی</button>

            </div>
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: red" data-toggle="modal" data-target="#poshtforoshmodal">واحد پشتیبانی فروش</button>

            </div>
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #3EC118" data-toggle="modal" data-target="#hsemodal">واحد HSE</button>

            </div>

        </div>
        <div class="row" style="margin-top: 3%">
            <!-- <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #3EC118" data-toggle="modal" data-target="#hsemodal">واحد HSE</button>

            </div> -->
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #2D12B0" data-toggle="modal" data-target="#tolidmodal">واحد تولید</button>

            </div>
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #0C7AD6" data-toggle="modal" data-target="#ravabetmodal">روابط عمومی</button>

            </div>
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #00BA2D" data-toggle="modal" data-target="#keyfiatmodal">واحد تضمین کیفیت</button>

            </div>
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #237C8E" data-toggle="modal" data-target="#controlmodal">واحد کنترل کیفیت</button>

            </div>

        </div>
        <div class="row" style="margin-top: 3%">
            <!-- <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #37CDC1" data-toggle="modal" data-target="#graphicmodal">واحد گرافیک و تولید محتوا</button>

            </div> -->
            
           
            <div class="col-sm-3">
                <button type="button" class="sh btn .btn-default stly" style="background-color: #8476B3" data-toggle="modal" data-target="#R_and_D_modal">واحد تحقیق و توسعه (R&D) </button>

            </div>
            
        
        <!------- کانال های بی سیم ------------------->
        <!-- <div class="row" style="margin-top: 3%"> -->
        <!-- <div class="col-sm-3">
            <button type="button" class="sh btn .btn-default stly" style="background-color: #FF0000" data-toggle="modal" data-target="#hesabrasimodal">واحد حسابرسی</button>

        </div> -->
        <div class="col-sm-3">
        <button type="button" class="sh btn .btn-default stly" style="background-color: rgb(225, 230, 238);color:red;font-size:3rem !important;font-width:bold" data-toggle="modal" data-target="#bisimmodal">کانال های بیسیم</button>
    </div>
    <div class="col-sm-6" >
        <button type="button" class="sh btn .btn-default stly" style="background-color: rgb(225, 230, 100);color:green;font-size:3rem !important;font-width:bold" data-toggle="modal" data-target="#oragan_mobile_modal"> شماره  همراه سازمانی</button>

    </div>
</div>
<!-- <div class="row">
    <div class="col-sm-12" style="margin-top: 3%;direction:rtl">
        <button type="button" class="sh btn .btn-default stly" style="background-color: rgb(225, 230, 100);color:green;font-size:3rem !important;font-width:bold" data-toggle="modal" data-target="#oragan_mobile_modal"> شماره  همراه سازمانی</button>

    </div>
   
            
</div> -->
        <!-- </div> -->
         <!------- کانال های بی سیم ------------------->
</div>

<hr>
<div >
        <!------- کانال های بی سیم ------------------->
        <!-- <div class="row" style="margin-top: 3%">
            <div class="col-sm-12">
                    <button type="button" class="sh btn .btn-default stly" style="background-color: rgb(225, 230, 238);color:red;font-size:4rem !important;font-width:bold" data-toggle="modal" data-target="#bisimmodal">کانال های بیسیم</button>

            </div>
        </div> -->
         <!------- کانال های بی سیم ------------------->
    </div>

    viramanagmentmodal
    <div class="modal fade" id="graphicmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <!-- <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد گرافیک و تولید محتوا</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color: rgb(255, 70, 60);color: black">
                        <thead>
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>خانم هوشمند</td>
                            <td>کارشناس گرافیک و تولید محتوا</td>
                            <td>۳۳۲</td>
                        </tr>
                         <tr>
                            <td>آقای ایمان ظریف</td>
                            <td>کارشناس گرافیک و تولید محتوا</td>
                            <td>۳۳۱</td>
                        </tr> 
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div> -->

        </div>
    </div>

    <div class="modal fade" id="viramanagmentmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ویرا تجارت نوین ابرش (دفتر تهران)</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color: rgb(48, 104, 204);color: black">
                        <thead>
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>خانم  سارا صفاری</td>
                            <td> مدیر عامل</td>
                            <td>1503</td>
                        </tr>
                        <tr>
                            <td>آقای ناصر عزتی</td>
                            <td>  مسئول اداری </td>
                            <td>1100</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>









    <div class="modal fade" id="bisimmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">کانال های بیسیم</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped text-center" style="background-color: rgb(225, 230, 238);color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">واحد</th>
                            <th class="text-center" style="background-color: #646A7A;color: black">کانال</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>فناوری اطلاعات</td>
                            <td>14</td>
                        </tr>
                        <tr>
                            <td>نت</td>
                            <td>۵</td>
                        </tr>
                        <tr>
                            <td>پشتیبانی</td>
                            <td>۷</td>
                        </tr>
                        <tr>
                            <td> خدمات اداری و روابط عمومی</td>
                            <td>۹</td>
                        </tr>
						    <tr>
                            <td>تدارکات</td>
                            <td>10</td>
                        </tr>
                        <tr>
                            <td>انبار محصول</td>
                            <td>۱۱</td>
                        </tr>
                        <tr>
                            <td>کنترل کیفیت</td>
                            <td>۱۳</td>
                        </tr>
						    <tr>
                            <td>انبارها</td>
                            <td>15</td>
                        </tr>
						<tr>
                            <td>واحد تولید و برنامه ریزی</td>
                            <td>۱۶</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد مدیریت-->
    <div class="modal fade" id="managmentmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد مدیریت</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color: #9fb9ff;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
						   <tr>
                            <td>آقای سید جواد رضوی</td>
                            <td>معاونت بازرگانی</td>
                            <td> </td>
                        </tr>
                        <tr>
                            <td>آقای سید جمال رضوی</td>
                            <td>مدیریت عامل</td>
                            <td> </td>
                        </tr>
                     
                        <tr>
                            <td>آقای محمدعلی میرزائیان</td>
                            <td>مدیر کارخانه</td>
                            <td>۵۰۵ − ۸۰۵</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد مالی-->
    <div class="modal fade" id="malimodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد مالی</h4>
                </div>
                <div class="modal-body">

                    <table class="table table-bordered text-center"  style="background-color: #FFB20E;color: black">
                        <thead>
                        <tr style="background-color: #929292;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای جلال عباسی</td>
                            <td>مدیر مالی</td>
                            <td>۱۲۰</td>
                        </tr>
                        
                        <tr>
                            <td>  آقای کاظم بیداد </td>
                            <td> سرپرست واحد حسابداری</td>
                            <td>۱۲۱</td>
                        </tr>
                        <tr>
                            <td>خانم اعظم ملکی</td>
                            <td>جمعدار اموال</td>
                            <td>۱۲۲</td>
                        </tr>
                        <tr>
                            <td> بدون همکار</td>
                            <td> -----</td>
                            <td>123</td>
                        </tr>
                        <tr>
                            <td> خانم حسینی</td>
                            <td>کارشناس حسابداری</td>
                            <td>۱۲۴</td>
                        </tr>
                        <tr>
                            <td>خانم زهره رجبی</td>
                            <td>کارشناس حسابداری</td>
                            <td>۱۲۵</td>
                        </tr>
                        <tr>
                            <td>آقای  درویش</td>
                            <td>کارشناس حسابداری</td>
                            <td>۱۲۶</td>
                        </tr>
                        <tr>
                            <td>  خانم سمیه دولتی </td>
                            <td>کارشناس حسابداری</td>
                            <td>۱۲۷</td>
                        </tr>
                        <tr>
                            <td>آقای سراج احراری</td>
                            <td>کارمند حسابداری </td>
                            <td>128</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد فناوری اطلاعات-->
    <div class="modal fade" id="fanavarimodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد فناوری اطلاعات</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center"  style="background-color: #37CDC1;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای میثم خطیبیان</td>
                            <td>مدیر فناوری اطلاعات</td>
                            <td>۷۷۰</td>
                        </tr>
                        <tr>
                            <td>فناوری  اطلاعات</td>
                            <td> فناوری اطلاعات</td>
                            <td>۷۷۷</td>
                        </tr>
                        <tr>
                            <td>آقای محمد علی حیدری</td>
                            <td>کارشناس ارشد ERP</td>
                            <td>۷۷۱</td>
                        </tr>
                        <tr>
                            <td>آقای مهدی رضوانی</td>
                            <td>کارشناس ERP</td>
                            <td>۷۷۲</td>
                        </tr>
                        <tr>
                            <td>آقای میثم عفتی</td>
                            <td>تکنسین سخت افزار</td>
                            <td>۷۷۵</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد نیروی انسانی-->
    <div class="modal fade" id="ansanimodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد منابع انسانی و اداری</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center"  style="background-color: #FF3298;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-right">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>خانم اکرم حقیقت</td>
                            <td>سرپرست کارگزینی</td>
                            <td>۱۰۶</td>
                        </tr>
                        <tr>
                            <td>خانم سارا الیاسی</td>
                            <td>مسئول دفتر مدیرعامل</td>
                            <td>۱۰۰ − ۴۰۰</td>
                        </tr>
                        <tr>
                            <td>آقای   شاهسونی</td>
                            <td>کارشناس اداری</td>
                            <td>۱۰۳</td>
                        </tr>
                        <tr>
                            <td>آقای سید اسماعیل موسوی</td>
                            <td>کارشناس اداری</td>
                            <td>۱۰۷</td>
                        </tr>
                        <tr>
                            <td>خدمات</td>
                            <td>خدمات</td>
                            <td>۱۱۱-411</td>
                        </tr>
                        <tr>
                            <td>خانم فرشته حسن زاده</td>
                            <td>کارشناس اداری</td>
                            <td>۱۰۴</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد بازرگانی فروش-->
    <div class="modal fade" id="bazarganiforoshmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد بازرگانی فروش</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center"  style="background-color: #D333E7;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای صالح معروف</td>
                            <td>مدیر بازرگانی فروش </td>
                            <td>130</td>
                        </tr>
                        <tr>
                            <td>  خانم الله قلی زاده</td>
                            <td>کارشناس برنامه ریزی فروش</td>
                            <td>131</td>
                        </tr>
                        <tr>
                            <td>آقای ابراهیم سالارپور</td>
                            <td>کارمند بازرگانی فروش</td>
                            <td>۱۳۳</td>
                        </tr>
                        <tr>
                            <td>خانم منتصری  </td>
                            <td>کارشناس بازرگانی فروش</td>
                            <td>۱۳۴</td>
                        </tr>
                        <tr>
                            <td>  خانم طاهره محمودیان</td>
                            <td>کارشناس بازرگانی فروش</td>
                            <td>۱۳۵</td>
                        </tr>
                        <tr>
                            <td> آقای حسنی</td>
                            <td>کارشناس بازرگانی فروش</td>
                            <td>۱۳۶</td>
                        </tr>
                        
                        <tr>
                            <td>آقای  حسینیان</td>
                            <td>کارشناس بازاریابی فروش</td>
                            <td>۱۳۷</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد پشتیبانی-->
    <div class="modal fade" id="poshtibanimodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد پشتیبانی</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center"  style="background-color: #23B1D1;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای حسین پورحسین</td>
                            <td>سرپرست پشتیبانی</td>
                            <td>۱۱۸</td>
                        </tr>
                        <tr>
                            <td>انتظامات</td>
                            <td>انتظامات</td>
                            <td>۱۰۸</td>
                        </tr>
                        <tr>
                            <td>انتظامات</td>
                            <td>انتظامات</td>
                            <td>109</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد حقوقی-->
    <div class="modal fade" id="hoghoghmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد حقوقی</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center"  style="background-color: #FF5BD7;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای مهدی ملت</td>
                            <td>کارشناس واحد حقوقی </td>
                            <td>۱۱۰</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد نت-->
    <div class="modal fade" id="netmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد نت</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color: #FFB439;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای ابوالحسن چاجی</td>
                            <td>سرپرست واحد نگهداری و تعمیرات</td>
                            <td>۱۸۵ − ۴۸۵</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد بازرگانی خرید-->
    <div class="modal fade" id="kharidmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد بازرگانی خرید</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color: #9fb9ff;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای سید قاسم مصطفوی</td>
                            <td>مدیر بازرگانی خرید</td>
                            <td>۱۴۰</td>
                        </tr>
                        <tr>
                            <td>خانم ساناز رستمیان</td>
                            <td>کارشناس بازرگانی خرید</td>
                            <td>۱۴۲</td>
                        </tr>
                        <tr>
                            <td>آقای حسین رضوانی</td>
                            <td>پرسنل بازرگانی خرید</td>
                            <td>۱۴۳</td>
                        </tr>

                        <tr>
                            <td>آقای احسان جابر</td>
                            <td>کارشناس بازرگانی خرید</td>
                            <td>۱۴۴</td>
                        </tr>

                        <tr>
                            <td>آقای مهران مهماندار</td>
                            <td>کارشناس بازرگانی خرید</td>
                            <td>۱۴۵</td>
                        </tr>

                        <tr>
                            <td>آقای اشرفی  </td>
                            <td>کارشناس بازرگانی خرید</td>
                            <td>146</td>
                        </tr>

                        <tr>
                            <td>آقای مصیب صمصامی</td>
                            <td>کارشناس بازرگانی خرید</td>
                            <td>۱۴۷</td>
                        </tr>

                        <tr>
                            <td>آقای عاکف </td>
                            <td>کارشناس بازرگانی خرید</td>
                            <td>148</td>
                        </tr> 
						
                        <tr>
                            <td>خانم لیلا فیض آبادی</td>
                            <td> کارشناس بازرگانی خرید خارجی  </td>
                            <td>149</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد مهندسی-->
    <div class="modal fade" id="mohandesimodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد مهندسی</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color: #CF2ECF;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای جواد مغنی نژاد</td>
                            <td>مدیر مهندسی</td>
                            <td>۱۵۰</td>
                        </tr>

                    
                        <tr>
                            <td>واحد مهندسی</td>
                            <td> بدون همکار</td>
                            <td>۱۵۲</td>
                        </tr>
                        <tr>
                            <td>قالب سازی    </td>
                            <td>بدون همکار  </td>
                            <td>۱۵۳</td>
                        </tr>
                        <tr>
                            <td>قالب سازی</td>
                            <td>سرپرست قالب سازی</td>
                            <td>۴۵۲</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد قالبسازی-->
    <!-- <div class="modal fade" id="ghalebmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <!--<div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">انبار قالب</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center"  style="background-color: #FEA62F;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- <tr>
                            <td>قالب سازی</td>
                            <td>سرپرست قالب سازی</td>
                            <td>۴۵۲</td>
                        </tr> -->

                        <!-- <tr>
                            <td>انبار قالب</td>
                            <td>انبار قالب</td>
                            <td>۱۵۶</td>
                        </tr> -->
                        <!--</tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div> -->

    <!--  واحد برنامه ریزی-->
    <div class="modal fade" id="barnamehmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد برنامه ریزی</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center"  style="background-color: #CD7600;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                       
                        <tr>
                            <td>خانم فائزه سادات تیموری</td>
                            <td>سرپرست برنامه ریزی</td>
                            <td>۴۶۱</td>
                        </tr>
                        <tr>
                            <td>  آقای نوید بهشتی </td>
                            <td>کارشناس برنامه ریزی</td>
                            <td>161</td>
                        </tr>

                        <tr>
                            <td>خانم کاظمی</td>
                            <td>  کارشناس  برنامه ریزی</td>
                            <td>162</td>
                        </tr>

                        </tbody>
                    </table>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد انبارها-->
    <div class="modal fade" id="anbarmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد انبارها</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center"  style="background-color: #9fb9ff;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای حسن آزاد مهر</td>
                            <td>مدیر انبارها</td>
                            <td>۱۶۶</td>
                        </tr>

                        <tr>
                            <td>آقای ذوالفقاری -خانم عباسی</td>
                            <td> انبار مواد اولیه</td>
                            <td>۱۶۳</td>
                        </tr>

                        <tr>
                            <td> آقای عزیزی </td>
                            <td>انبار در جریان</td>
                            <td>۱۶۴</td>
                        </tr>
                        <tr>
                            <td>آقای وحید زنگنه</td>
                            <td>انبار مواد مصرفی</td>
                            <td>۱۶۵</td>
                        </tr>

                        <tr>
                            <td>آقای کاظم الهی</td>
                            <td> مسئول انبار محصول(زیر مجموعه بازرگانی فروش) </td>
                            <td>۱۱۲</td>
                        </tr>
                        <tr>
                            <td>انبار قالب</td>
                            <td>انبار قالب</td>
                            <td>۱۵۶</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد حسابرسی-->
    <div class="modal fade" id="hesabrasimodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد حسابرسی</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color: #FF0000;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>واحد حسابرسی</td>
                            <td>واحد حسابرسی</td>
                            <td>۴۲۱</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد امور عمرانی-->
    <div class="modal fade" id="omranmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد امور عمرانی</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color:  #FFD800;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td> بدون همکار </td>
                            <td>----</td>
                            <td>۱۳۹</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد پشتیبانی فروش-->
    <div class="modal fade" id="poshtforoshmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد پشتیبانی فروش</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color: #D3A335;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای حمید ایمان</td>
                            <td>کارشناس حسابداری بازرگانی فروش</td>
                            <td>۱۳۲</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد HSE-->
    <div class="modal fade" id="hsemodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد HSE</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color: #3EC118;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای ناصر رئوف</td>
                            <td> HSEکارشناس ایمنی و بهداشت</td>
                            <td>۱۱۵</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد تولید-->
    <div class="modal fade" id="tolidmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد تولید</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color: #2D12B0;color: white">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>مهندس بیک پور</td>
                            <td> مدیر تولید </td>
                            <td>174</td>
                        </tr>
                        <tr>
                            <td> مجید صفایی</td>
                            <td>سرپرست تولید</td>
                            <td>۱۷۰-۴۷۰</td>
                        </tr>
                        <tr>
                            <td>واحد پرداخت</td>
                            <td>واحد پرداخت</td>
                            <td>۱۷۱</td>
                        </tr>

                        <tr>
                            <td>واحد مونتاژ</td>
                            <td>واحد مونتاژ</td>
                            <td>۱۷۲</td>
                        </tr>
                        <tr>
                            <td>واحد آبکاری</td>
                            <td>واحد آبکاری</td>
                            <td>۱۷۳</td>
                        </tr>
                        <tr>
                            <td>خانم الهام تقوی</td>
                            <td>کارشناس ثبت اطلاعات تولید</td>
                            <td>۱۷۷</td>
                        </tr>

                        <tr>
                            <td>واحد PVD</td>
                            <td>PVD واحد</td>
                            <td>۱۷۹</td>
                        </tr>
                        <!--<tr>
                            <td>واحد خط تزریق پلاستیک</td>
                            <td>واحد خط تزریق پلاستیک</td>
                            <td>۱۸۰</td>
                        </tr>

                        <tr>
                            <td>واحد فورج</td>
                            <td>واحد فورج</td>
                            <td>۴۸۲</td>
                        </tr>-->
                        

                       <!-- <tr>
                            <td>واحد خط لوله</td>
                            <td>واحد خط لوله</td>
                            <td>۴۷۸</td>
                        </tr>-->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد روابط عمومی-->
    <div class="modal fade" id="ravabetmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">روابط عمومی</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color: #646A7A;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای قاسمیان  </td>
                            <td>   سرپرست روابط عمومی </td>
                            <td>330</td>
                        </tr>
                        <tr>
                            <td> آقای اسماعیلی</td>
                            <td> کارشناس روابط عمومی </td>
                            <td>331</td>
                        </tr>
                        <tr>
                            <td>خانم هوشمند</td>
                            <td>   کارشناس روابط عمومی </td>
                            <td>332</td>
                        </tr>
                        
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>

    <!--  واحد تضمین کیفیت-->
    <div class="modal fade" id="keyfiatmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد تضمین کیفیت</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color:  #00BA2D;color:black;">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td> خانم قوچانیان</td>
                            <td>کارشناس تضمین کیفیت</td>
                            <td>۴۶۷</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>
    
    <!------------------------------------------------------------organ_mobile------------------->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="oragan_mobile_modal" id="oragan_mobile_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
           
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">  شماره های همراه سازمانی</h4>
                </div>
                <div class="modal-body">
                    
                    <?php 

                        if(isset($_SESSION)){
                            $acm=new acm();
                            if($acm->hasAccess('manage_organization_mobiles')){
                    ?>
                    <form class='p-4 bg-light mb-4'>
                        <div class="form-row">
                            <input type="hidden" id='rowid'/>
                            <div class="form-group col-md-6 text-right">
                            <label for="inputEmail4">نام </label>
                            <input type="text" class="form-control" id="fname" placeholder="نام ">
                            </div>
                            <div class="form-group col-md-6 text-right">
                            <label for="inputPassword4">نام خانوادگی</label>
                            <input type="text" class="form-control" id="lname" placeholder="نام خانوادگی">
                            </div>
                            <div class="form-group col-md-6 text-right">
                            <label for="inputEmail4">واحد محل فعالیت</label>
                            <input type="text" class="form-control" id="unit" placeholder="واحد محل فعالیت ">
                            </div>
                            <div class="form-group col-md-6 text-right">
                            <label for="inputEmail4">پست سازمانی </label>
                            <input type="text" class="form-control" id="post" placeholder="نام ">
                            </div>
                            <div class="form-group col-md-6 text-right">
                            <label for="inputPassword4">شماره همراه</label>
                            <input type="text" class="form-control" id="mobile" placeholder="09XXXXXXXXX">
                            </div>
                            <!-- <div class="form-group col-md-6 text-right">
                            <label for="inputPassword4">شماره داخلی</label>
                            <input type="password" class="form-control" id="mobaile" placeholder="شماره داخلی">
                            </div> -->
                        </div>
                        <div class="form-group text-right">
                            <label for="inputAddress">توضیحات</label>
                            <input type="text" class="form-control" id="description" placeholder="توضیحات">
                        </div>
                        <button type="submit" class="btn btn-primary">ذخیره </button>
                        </form>
                    <?php            

                            }
                        } 
                    ?>
                    <table class="table table-bordered text-center" id="organization_mobiles" style="background-color: #acbabd;color: black;font-size:12px">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th style="width:5% !important"  class="text-center" >ردیف</th>
                            <th style="width:25% !important" class="text-center">نام و نام خانوادگی</th>
                            <th style="width:40% !important" class="text-center">سمت / واحد</th>
                            <th style="width:10% !important" class="text-center">شماره همراه</th>
                            <th style="width:20% !important" class="text-center"> مدیریت</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $counter=1; 
                            foreach($mobile_organ_res as $phon_key=>$phon_value){
                               // print_r($phon_value);
                                $full_name=$phon_value['fname']." ".$phon_value['lname'];
                                if($phon_value['Name']=='Entezamat'){
                                    $full_name="انتظامات";
                                }
                                echo "
                                 <tr>
                                 <td style='width:5% ;font-size:12px !important'>".$counter."</td>
                                 <td style='width:25%;font-size:12px  !important'>".$full_name."</td>
                                 <td style='width:40%;font-size:12px !important'>".$phon_value['Uname']." - ".$phon_value['post']." </td>
                                 <td style='width:10%;font-size:12px !important'>".$phon_value['organization_mobile']." </td>
                                 <td style='width:20% ;font-size:12px !important'><button class='btn btn-info' onclick='get_detailes(".$phon_value['RowID'].")'>ویرایش </button> <button class='btn btn-danger'>حذف </button></td>
                                </tr>";
                                $counter++;
                            }
                        
                        ?>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>
        </div>
    </div>
    <!------------------------------------------------------------organ_mobile-----------------
    <!--  واحد کنترل کیفیت-->
    <div class="modal fade" id="controlmodal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد کنترل کیفیت</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color: #237C8E;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای مهران مهدیزاده</td>
                            <td>مدیر کنترل کیفیت</td>
                            <td>۱۹۰ </td>
                        </tr>
                        <tr>
                            <td>آزمایشگاه خط لوله</td>
                            <td>آزمایشگاه خط لوله</td>
                            <td>۱۹۱</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>
    <!--------------------------واحد R&D--------------------------------->
    <div class="modal fade" id="R_and_D_modal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">واحد تحقیق و توسعه (R&D) </h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered text-center" style="background-color: #8476B3;color: black">
                        <thead>
                        <tr style="background-color: #2d3748;color: white">
                            <th class="text-center">نام و نام خانوادگی</th>
                            <th class="text-center">سمت / واحد</th>
                            <th class="text-center">داخلی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>آقای بهارعلی </td>
                            <td> واحد تحقیق و توسعه </td>
                            <td>155 </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خروج</button>
                </div>
            </div>

        </div>
    </div>
    
     <script>
        function ajaxManage(data){
          
            var res= $.ajax({
                url: 'php/get_organization_mobile.php',
                type: 'POST',
                data: data,
                success: function(response) {
                      res=JSON.parse(response);
                      return res;
                    
                }
            });
            return JSON.parse(res.responseText)
          
        }
        function get_detailes(rowid){
            var res=ajaxManage({action:'get_mobile',rowid: rowid})
            console.log('res',res);
          //  var rowid=$(this).attr('rowid');
           
                    $("#rowid").val(res.RowID)
                    $("#fname").val(res.fname)
                    $("#lname").val(res.lname)
                    $("#mobile").val(res.organization_mobile)
                    $("#unit").val(res.unit_id)
                    $("#post").val(res.post)
                    $("#description").val(res.description)
        }
                    
           

        function edit_create_mobile(){
            var params={
                action:'edit_create_mobile',
                rowid:$("#rowid").val(),
                fname:$("#fname").val(),
                lname:$("#lname").val(),
                mobile:$("#organization_mobile").val(),
                unit:$("#unit").val(),
                post:$("#post").val(),
                description:$("#description").val(),
            }
            var res=ajaxManage(params)
        }
        
     </script>  
   
</body>
</html>
