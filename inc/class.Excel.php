
<?php /** @noinspection MultiAssignmentUsageInspection */

use Shuchkin\SimpleXLSX;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);
require_once('../config.php');
require_once __DIR__.'/class.Utility.php';
require_once __DIR__.'/SimpleXLSX.php';
require_once __DIR__.'/class.DBi.php';
require_once __DIR__.'/jdf.php';
$begin_date_of_contract="1403/03/01";
$end_date_of_contract="1404/02/31";
if ($xlsx = SimpleXLSX::parse('hoghoogh.xlsx')) 
{
    $excel_array=$xlsx->rows();
	unset($excel_array[0]);
	$final_excel_array=[];
	$header_array=['old_PersonnelCode','new_PersonnelCode','cost_center','fullname','empolyment_date','Marital_Status','wage','years','dailyWages','monthlyWages','RightHousing','Grocery','personnelRightMarry','numberChildren','Child_Allowance','SalaryInofList'];
	foreach($excel_array as $key=>$value){
		$array_handler=[];
		for($i=0;$i<count($value);$i++){
			$array_handler[$header_array[$i]]=$value[$i];
		}
		$final_excel_array[$value[0]]=$array_handler;
	}
	$ut=new Utility();
	$db=new DBi();
	$backup_tbl_name='backup_personnel_'.strtotime(date('Y-m-d H:i:s'));
	$back_up_personel_sql="CREATE TABLE `{$backup_tbl_name}` as SELECT * FROM personnel";
	$result=$db->Query($back_up_personel_sql);
	if($result){
		foreach($final_excel_array as $key_excel=>$value_excel){
			$sql="SELECT * FROM personnel where `isEnable`=1 AND `PersonnelCode`={$key_excel}";
			$res=$db->ArrayQuery($sql);
			if(count($res)>0)
			{
				$sql="UPDATE personnel SET 
					`wage`='{$value_excel['wage']}',
					`yearsCost`='{$value_excel['years']}',
					`dailyWages`='{$value_excel['dailyWages']}',
					`monthlyWages`='{$value_excel['monthlyWages']}',
					`RightHousing`='{$value_excel['RightHousing']}',
					`numberChildren`='{$value_excel['numberChildren']}',
					`Child_Allowance`='{$value_excel['Child_Allowance']}',
					`Grocery`='{$value_excel['Grocery']}',
					`SalaryInofList`='{$value_excel['SalaryInofList']}',
					`RecruitmentDate`='{$ut->jal_to_greg(check_shamsi_date($value_excel['empolyment_date']))}',
					`BeginDateContract`='{$ut->jal_to_greg($begin_date_of_contract)}',
					`EndDateContract`='{$ut->jal_to_greg($end_date_of_contract)}',
					`Marital_Status`='{$value_excel['Marital_Status']}',
					`personnelRightMarry`='{$value_excel['personnelRightMarry']}',
					`new_PersonnelCode`='{$value_excel['new_PersonnelCode']}'
				WHERE `PersonnelCode`={$key_excel}";
				$res=$db->Query($sql);
				if(!$res){
					echo $sql;
					die('error');
				}
			}
			else{
				echo $key_excel."</br>";
			}
		}
	}
} 
else 
{
    echo SimpleXLSX::parseError();
}

function show_array($array){
	echo "<pre>";
		print_r($array);
	echo "</pre>";
}

function check_shamsi_date($shamsi_date){
	$date_array=explode('/',$shamsi_date);
	$year=$date_array[0];
	$shamsi_full_date=$shamsi_date;
	if($year>0 && $year<=99){
		$year="13".$year;
		$shamsi_full_date=$year."/".$date_array[1]."/".$date_array['2'];
	}
	return $shamsi_full_date;
}

	
