<?php

class Project{

    public function __construct(){
        // do nothing
    }

    public function getProjectManagementHtm(){
        $acm = new acm();
        if(!$acm->hasAccess('projectManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $rate = new Rates();

        $pagename = "مدیریت پروژه ها";
        $pageIcon = "fa-project-diagram";
        $contentId = "projectManagementBody";

        $units = $rate->getUnits();
        $cntu = count($units);

        $bottons = array();
        $c = 0;

        if($acm->hasAccess('editCreateProject')) {
            $bottons[$c]['title'] = "افزودن پروژه جدید";
            $bottons[$c]['jsf'] = "createProject";
            $bottons[$c]['icon'] = "fa-plus-square";
            $c++;

            $bottons[$c]['title'] = "ویرایش پروژه";
            $bottons[$c]['jsf'] = "editProject";
            $bottons[$c]['icon'] = "fa-edit";
            $c++;

            $bottons[$c]['title'] = "پیوست فایل گردش کار";
            $bottons[$c]['jsf'] = "projectWorkflowAttachment";
            $bottons[$c]['icon'] = "fa-link";
            $c++;

            $bottons[$c]['title'] = "پیوست فایل فیلد ها";
            $bottons[$c]['jsf'] = "projectFieldsAttachment";
            $bottons[$c]['icon'] = "fa-link";
            $c++;

            $bottons[$c]['title'] = "تایید نمایش به مدیر عامل";
            $bottons[$c]['jsf'] = "projectBossTick";
            $bottons[$c]['icon'] = "fa-check";
            $c++;

            $bottons[$c]['title'] = "تایید نهایی";
            $bottons[$c]['jsf'] = "projectFinalTick";
            $bottons[$c]['icon'] = "fa-check";
        }

        $headerSearch = array();

        $htm = $ut->getHtmlOfDefaultManagementPage($pagename,$pageIcon,$contentId,$bottons,$headerSearch);
        //++++++++++++++++++++++++++++++++++EDIT CREATE MODAL++++++++++++++++++++++++++++++++
        $modalID = "projectManagementModal";
        $modalTitle = "فرم ایجاد/ویرایش پروژه ها";
        $style = 'style="max-width: 651px;"';
        $c = 0;

        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "projectManagementName";
        $items[$c]['title'] = "موضوع پروژه";
        $items[$c]['placeholder'] = "موضوع";
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "projectManagementOwner";
        $items[$c]['title'] = "مالک پروژه";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cntu;$i++){
            $items[$c]['options'][$i+1]["title"] = $units[$i]['Uname'];
            $items[$c]['options'][$i+1]["value"] = $units[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "select";
        $items[$c]['id'] = "projectManagementUnits";
        $items[$c]['multiple'] = "multiple";
        $items[$c]['title'] = "واحدهای دخیل در پروژه";
        $items[$c]['options'] = array();
        $items[$c]['options'][0]["title"] = 'انتخاب کنید';
        $items[$c]['options'][0]["value"] = 0;
        for ($i=0;$i<$cntu;$i++){
            $items[$c]['options'][$i+1]["title"] = $units[$i]['Uname'];
            $items[$c]['options'][$i+1]["value"] = $units[$i]['RowID'];
        }
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "projectManagementDescription";
        $items[$c]['title'] = "توضیحات";
        $items[$c]['placeholder'] = "متن";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "projectManagementHiddenPid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doEditCreateProject";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "No";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $editCreateModal = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF EDIT CREATE MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++ project Workflow Attachment Modal ++++++++++++++++++++++
        $modalID = "projectWorkflowAttachmentModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'projectWorkflowAttachment-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "projectWorkflowAttachmentName";
        $items[$c]['title'] = "نام فایل";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "projectWorkflowAttachmentFile";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PNG , JPG , JPEG باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "projectWorkflowAttachmentID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachFileToProject";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $projectWorkflowAttachment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End project Workflow Attachment Modal ++++++++++++++++++++++++
        //++++++++++++++++++ project Fields Attachment Modal ++++++++++++++++++++++
        $modalID = "projectFieldsAttachmentModal";
        $modalTitle = "پیوست فایل";
        $ShowDescription = 'projectFieldsAttachment-body';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "text";
        $items[$c]['id'] = "projectFieldsAttachmentName";
        $items[$c]['title'] = "نام فایل";
        $items[$c]['placeholder'] = "نام";
        $c++;

        $items[$c]['type'] = "file";
        $items[$c]['id'] = "projectFieldsAttachmentFile";
        $items[$c]['title'] = "بارگذاری فایل های پیوست";
        $items[$c]['name'] = 'name="files[]"';
        $items[$c]['helpText'] = "نوع فایل باید PNG , JPG , JPEG باشد.";
        $items[$c]['multiple'] = 'multiple';
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "projectFieldsAttachmentID";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال فایل";
        $footerBottons[0]['jsf'] = "doAttachFieldsFileToProject";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "بستن";
        $footerBottons[1]['type'] = "dismis";
        $projectFieldsAttachment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','','',$ShowDescription);
        //+++++++++++++++++ End project Fields Attachment Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show project Workflow Modal ++++++++++++++++++++++
        $modalID = "showProjectWorkflowModal";
        $modalTitle = "گردش کار";
        $ShowDescription = 'showProjectWorkflowModal-body';
        $style = 'style="max-width: 900px;"';

        $items = array();

        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showProjectWorkflow = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show project Workflow Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ confirm Project Workflow File MODAL ++++++++++++++++++++++++++++++++
        $modalID = "confirmProjectWorkflowFileModal";
        $modalTitle = "فرم تایید / عدم تایید و ثبت نظر";
        $style = 'style="max-width: 651px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "confirmProjectWorkflowFileType";
        $items[$c]['title'] = "انتخاب نمایید";
        $items[$c]['options'][0]['title'] = "تایید";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "عدم تایید";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "confirmProjectWorkflowFileWarning";
        $items[$c]['title'] = "هشدار";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['placeholder'] = "در صورت تایید، نظر شما ثبت خواهد شد و امکان اعلام نظر نخواهید داشت.";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "confirmProjectWorkflowFileDesc";
        $items[$c]['title'] = "ثبت نظرات";
        $items[$c]['placeholder'] = "نظرات خود را بنویسید";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "confirmProjectWorkflowFileHiddenPwid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doConfirmProjectWorkflowFile";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $confirmProjectWorkflowFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF confirm Project Workflow File MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++ show project Workflow Comment Modal ++++++++++++++++++++++
        $modalID = "showProjectWorkflowCommentModal";
        $modalTitle = "نظرات";
        $ShowDescription = 'showProjectWorkflowComment-body';
        $style = 'style="max-width: 900px;"';

        $items = array();

        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showProjectWorkflowComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show project Workflow Comment Modal ++++++++++++++++++++++++
        //++++++++++++++++++ show project Fields Modal ++++++++++++++++++++++
        $modalID = "showProjectFieldsModal";
        $modalTitle = "فیلد ها";
        $ShowDescription = 'showProjectFieldsModal-body';
        $style = 'style="max-width: 900px;"';

        $items = array();

        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showProjectFields = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show project Fields Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++++++ confirm Project Fields File MODAL ++++++++++++++++++++++++++++++++
        $modalID = "confirmProjectFieldsFileModal";
        $modalTitle = "فرم تایید / عدم تایید و ثبت نظر";
        $style = 'style="max-width: 651px;"';

        $c = 0;
        $items = array();
        $items[$c]['type'] = "radio";
        $items[$c]['name'] = "confirmProjectFieldsFileType";
        $items[$c]['title'] = "انتخاب نمایید";
        $items[$c]['options'][0]['title'] = "تایید";
        $items[$c]['options'][0]['value'] = 1;
        $items[$c]['options'][1]['title'] = "عدم تایید";
        $items[$c]['options'][1]['value'] = 0;
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "confirmProjectFieldsFileWarning";
        $items[$c]['title'] = "هشدار";
        $items[$c]['disabled'] = "disabled";
        $items[$c]['placeholder'] = "در صورت تایید، نظر شما ثبت خواهد شد و امکان اعلام نظر نخواهید داشت.";
        $c++;

        $items[$c]['type'] = "textarea";
        $items[$c]['id'] = "confirmProjectFieldsFileDesc";
        $items[$c]['title'] = "ثبت نظرات";
        $items[$c]['placeholder'] = "نظرات خود را بنویسید";
        $c++;

        $items[$c]['type'] = "hidden";
        $items[$c]['id'] = "confirmProjectFieldsFileHiddenPwid";

        $footerBottons = array();
        $footerBottons[0]['title'] = "ارسال";
        $footerBottons[0]['jsf'] = "doConfirmProjectFieldsFile";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[0]['data-dismiss'] = "NO";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $confirmProjectFieldsFile = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style);
        //++++++++++++++++++++++++++++++ END OF confirm Project Fields File MODAL +++++++++++++++++++++++++++++++++++++
        //++++++++++++++++++ show project Fields Comment Modal ++++++++++++++++++++++
        $modalID = "showProjectFieldsCommentModal";
        $modalTitle = "نظرات";
        $ShowDescription = 'showProjectFieldsComment-body';
        $style = 'style="max-width: 900px;"';

        $items = array();

        $footerBottons = array();
        $footerBottons[0]['title'] = "بستن";
        $footerBottons[0]['type'] = "dismis";
        $showProjectFieldsComment = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,'','',$style,$ShowDescription);
        //+++++++++++++++++ End show project Fields Comment Modal ++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ project Boss Tick MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "projectBossTickModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به تایید مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "projectBossTick_PidHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doProjectBossTick";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $projectBossTick = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF project Boss Tick MODAL ++++++++++++++++++++++++++++
        //++++++++++++++++++++++++++++++ project Final Tick MODAL ++++++++++++++++++++++++++++++++++++++++
        $modalID = "projectFinalTickModal";
        $modalTitle = "هشدار";
        $modalTxt = "آیا نسبت به تایید مطمئن هستید؟ ";
        $items = array();
        $items[0]['type'] = "hidden";
        $items[0]['id'] = "projectFinalTick_PidHidden";
        $footerBottons = array();
        $footerBottons[0]['title'] = "تایید";
        $footerBottons[0]['jsf'] = "doProjectFinalTick";
        $footerBottons[0]['type'] = "btn";
        $footerBottons[1]['title'] = "انصراف";
        $footerBottons[1]['type'] = "dismis";
        $projectFinalTick = $ut->getHtmlModal($modalID,$modalTitle,$items,$footerBottons,$modalTxt);
        //++++++++++++++++++++++++++++++END OF project Final Tick MODAL ++++++++++++++++++++++++++++
        $htm .= $editCreateModal;
        $htm .= $projectWorkflowAttachment;
        $htm .= $projectFieldsAttachment;
        $htm .= $showProjectWorkflow;
        $htm .= $confirmProjectWorkflowFile;
        $htm .= $showProjectWorkflowComment;
        $htm .= $showProjectFields;
        $htm .= $confirmProjectFieldsFile;
        $htm .= $showProjectFieldsComment;
        $htm .= $projectBossTick;
        $htm .= $projectFinalTick;
        return $htm;
    }

    public function getProjectManagementList($page=1){
        $acm = new acm();
        if(!$acm->hasAccess('projectManagement')){
            die("access denied");
            exit;
        }
        $ut = new Utility();
        $db = new DBi();
        $numRows = LISTCNT;
        $start = ($page-1)*$numRows;

        $query = "SELECT `unitID` FROM `users` WHERE `RowID`={$_SESSION['userid']}";
        $rst = $db->ArrayQuery($query);

        $sql = "SELECT * FROM `project`";
        $sql .= " ORDER BY `RowID` DESC LIMIT $start,".$numRows;
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $finalRes = array();

        for($y=0;$y<$listCount;$y++){
            if (intval($_SESSION['userid']) == 4 && intval($res[$y]['bossTick']) == 0){
                continue;
            }
            if (intval($_SESSION['userid']) !== 1 && intval($_SESSION['userid']) !== 4){
                $projectUnits = explode(',',$res[$y]['projectUnits']);
                if ((intval($res[$y]['projectOwner']) !== intval($rst[0]['unitID'])) && (!in_array($rst[0]['unitID'],$projectUnits)) ){
                    continue;
                }
            }
            $query1 = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$res[$y]['projectOwner']}";
            $rst1 = $db->ArrayQuery($query1);

            $Uname = array();
            $query2 = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID` IN ({$res[$y]['projectUnits']})";
            $rst2 = $db->ArrayQuery($query2);
            $cnt = count($rst2);
            for ($j=0;$j<$cnt;$j++){
                $Uname[] = $rst2[$j]['Uname'];
            }

            $finalRes[$y]['RowID'] = $res[$y]['RowID'];
            $finalRes[$y]['projectName'] = $res[$y]['projectName'];
            $finalRes[$y]['projectOwner'] = $rst1[0]['Uname'];
            $finalRes[$y]['projectUnits'] = implode(' - ',$Uname);
            $finalRes[$y]['createDate'] = $ut->greg_to_jal($res[$y]['createDate']);
            $finalRes[$y]['createTime'] = $res[$y]['createTime'];
            $finalRes[$y]['description'] = $res[$y]['description'];
        }
        return array_values($finalRes);
    }

    public function getProjectManagementListCountRows(){
        $db = new DBi();
        $query = "SELECT `unitID` FROM `users` WHERE `RowID`={$_SESSION['userid']}";
        $rst = $db->ArrayQuery($query);

        $sql = "SELECT `RowID`,`projectOwner`,`projectUnits` FROM `project`";
        $res = $db->ArrayQuery($sql);
        $listCount = count($res);
        $iterator = 0;
        for($y=0;$y<$listCount;$y++){
            if (intval($_SESSION['userid']) == 4 && intval($res[$y]['bossTick']) == 0){
                continue;
            }
            if (intval($_SESSION['userid']) !== 1 && intval($_SESSION['userid']) !== 4){
                $projectUnits = explode(',',$res[$y]['projectUnits']);
                if ((intval($res[$y]['projectOwner']) !== intval($rst[0]['unitID'])) && (!in_array($rst[0]['unitID'],$projectUnits)) ){
                    continue;
                }
            }
            $iterator++;
        }
        return $iterator;
    }

    public function projectInfo($pid){
        $db = new DBi();
        $sql = "SELECT * FROM `project` WHERE `RowID`=".$pid;
        $res = $db->ArrayQuery($sql);
        if(count($res) == 1){
            $res = array("pid"=>$pid,"projectName"=>$res[0]['projectName'],"projectOwner"=>$res[0]['projectOwner'],"projectUnits"=>$res[0]['projectUnits'],"description"=>$res[0]['description']);
            return $res;
        }else{
            return false;
        }
    }

    public function createProject($name,$owner,$desc,$units){
        $acm = new acm();
        if(!$acm->hasAccess('projectManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');
        $sql = "INSERT INTO `project` (`projectName`,`projectOwner`,`projectUnits`,`createDate`,`createTime`,`description`) VALUES ('{$name}',{$owner},'{$units}','{$nowDate}','{$nowTime}','{$desc}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function editProject($pid,$name,$owner,$desc,$units){
        $acm = new acm();
        if(!$acm->hasAccess('projectManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();
        $sql = "UPDATE `project` SET `projectName`='{$name}',`projectOwner`={$owner},`projectUnits`='{$units}',`description`='{$desc}' WHERE `RowID`={$pid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function projectWorkflowFileHtm($cid){
        $db = new DBi();
        $sql = "SELECT `RowID`,`fileName`,`fileInfo` FROM `project_workflow_attachment` WHERE `pid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="projectWorkflowFile-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 55%;">نام فایل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">لینک دانلود</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">حذف فایل</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $link = ADDR.'attachment/'.$res[$i]['fileName'];

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteProjectWorkflowFile('.$res[$i]['RowID'].')" ><i class="fas fa-trash"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function attachFileToProject($pid,$info,$files){
        $db = new DBi();
        $cDate = date('Y/m/d');
        $cTime = date('H:i:s');

        $SFile = array();
        $allowedTypes = ['png','jpg','jpeg','PNG','JPG','JPEG'];

        if (isset($files) && !empty($files)) {
            $no_files = count($files['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files["tmp_name"][$i];
                if ($files["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files['name'][$i], strpos($files['name'][$i], ".") + 1);
                if(!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile[] = "project" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $cnt = count($SFile);
        for ($i=0;$i<$cnt;$i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i],'../attachment/'.$SFile[$i]);
            $sql4 = "INSERT INTO `project_workflow_attachment` (`pid`,`fileName`,`fileInfo`,`createDate`,`createTime`,`uid`) VALUES ({$pid},'{$SFile[$i]}','{$info}','{$cDate}','{$cTime}',{$_SESSION['userid']})";
            $db->Query($sql4);
        }
        return true;
    }

    public function deleteProjectWorkflowFile($fid){
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `project_workflow_attachment` WHERE `RowID`={$fid}";
        $res = $db->ArrayQuery($sql);
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/attachment/' . $res[0]['fileName'];
        $result = unlink($file_to_delete);
        if ($result) {
            $query = "DELETE FROM `project_workflow_attachment` WHERE `RowID`={$fid}";
            $db->Query($query);
            return true;
        }else{
            return false;
        }
    }

    public function getProjectFieldsFileHtm($cid){
        $db = new DBi();
        $sql = "SELECT `RowID`,`fileName`,`fileInfo` FROM `project_fields_attachment` WHERE `pid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="projectFieldsFile-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 55%;">نام فایل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">لینک دانلود</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">حذف فایل</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $link = ADDR.'attachment/'.$res[$i]['fileName'];

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-danger" onclick="deleteProjectFieldsFile('.$res[$i]['RowID'].')" ><i class="fas fa-trash"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function attachFieldsFileToProject($pid,$info,$files){
        $db = new DBi();
        $cDate = date('Y/m/d');
        $cTime = date('H:i:s');

        $SFile = array();
        $allowedTypes = ['png','jpg','jpeg','PNG','JPG','JPEG'];

        if (isset($files) && !empty($files)) {
            $no_files = count($files['name']);
            for ($i = 0; $i < $no_files; $i++) {
                $filepath = $files["tmp_name"][$i];
                if ($files["error"][$i] > 0) {  // اگر یک فایل ارور داشت
                    return -1;
                }
                if (filesize($filepath) === 0) {  // اگر سایز یک فایل صفر بود
                    return -2;
                }
                $format = substr($files['name'][$i], strpos($files['name'][$i], ".") + 1);
                if(!in_array($format, $allowedTypes)) {  // اگر پسوند فایل نادرست بود
                    return -3;
                }
                $SFile[] = "project" . rand(0, time()).'.'.$format;
            } // for()
        } //  if (isset($files) && !empty($files))

        $cnt = count($SFile);
        for ($i=0;$i<$cnt;$i++) {
            $upload = move_uploaded_file($files["tmp_name"][$i],'../attachment/'.$SFile[$i]);
            $sql4 = "INSERT INTO `project_fields_attachment` (`pid`,`fileName`,`fileInfo`,`createDate`,`createTime`,`uid`) VALUES ({$pid},'{$SFile[$i]}','{$info}','{$cDate}','{$cTime}',{$_SESSION['userid']})";
            $db->Query($sql4);
        }
        return true;
    }

    public function deleteProjectFieldsFile($fid){
        $db = new DBi();
        $sql = "SELECT `fileName` FROM `project_fields_attachment` WHERE `RowID`={$fid}";
        $res = $db->ArrayQuery($sql);
        $file_to_delete = str_replace('\\', '/', dirname(__DIR__)) . '/attachment/' . $res[0]['fileName'];
        $result = unlink($file_to_delete);
        if ($result) {
            $query = "DELETE FROM `project_fields_attachment` WHERE `RowID`={$fid}";
            $db->Query($query);
            return true;
        }else{
            return false;
        }
    }

    public function projectWorkflowInfoHtm($cid){
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT `RowID`,`fileName`,`fileInfo`,`createDate`,`createTime` FROM `project_workflow_attachment` WHERE `pid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="projectWorkflowInfo-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">نام فایل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ساعت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">لینک دانلود</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">تایید / عدم تایید و ثبت نظر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">نظرات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $link = ADDR.'attachment/'.$res[$i]['fileName'];

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res[$i]['createDate']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="confirmProjectWorkflowFile('.$res[$i]['RowID'].')" ><i class="fas fa-list"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showProjectWorkflowComments('.$res[$i]['RowID'].')" ><i class="fas fa-comment"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function confirmProjectWorkflowFile($pwid,$desc,$radioValue){
        $acm = new acm();
        if(!$acm->hasAccess('projectManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        $sqq = "SELECT `RowID` FROM `project_workflow_workflow` WHERE `uid`={$_SESSION['userid']}";
        $rsq = $db->ArrayQuery($sqq);
        if (count($rsq) > 0){
            $res = "شما قبلا نظر خود را ثبت نموده اید !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $query = "SELECT `unitID` FROM `users` WHERE `RowID`={$_SESSION['userid']}";
        $rst = $db->ArrayQuery($query);

        $sql = "INSERT INTO `project_workflow_workflow` (`pwid`,`uid`,`unitID`,`status`,`createDate`,`createTime`,`description`) VALUES ({$pwid},{$_SESSION['userid']},{$rst[0]['unitID']},{$radioValue},'{$nowDate}','{$nowTime}','{$desc}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function projectWorkflowCommentHtm($pwid){
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT * FROM `project_workflow_workflow` WHERE `pwid`={$pwid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="projectWorkflowComment-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">ثبت کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">واحد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ساعت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">وضعیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 40%;">نظرات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $sql1 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$i]['uid']}";
            $res1 = $db->ArrayQuery($sql1);

            $sql2 = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$res[$i]['unitID']}";
            $res2 = $db->ArrayQuery($sql2);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[0]['fname'].' '.$res1[0]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res2[0]['Uname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res[$i]['createDate']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(intval($res[$i]['status']) == 0 ? 'عدم تایید' : 'تایید').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function projectFieldsInfoHtm($cid){
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT `RowID`,`fileName`,`fileInfo`,`createDate`,`createTime` FROM `project_fields_attachment` WHERE `pid`={$cid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="projectFieldsInfo-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 35%;">نام فایل</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ساعت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">لینک دانلود</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 20%;">تایید / عدم تایید و ثبت نظر</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">نظرات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $link = ADDR.'attachment/'.$res[$i]['fileName'];

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['fileInfo'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res[$i]['createDate']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><a class="btn btn-info" href="'.$link.'" target="_blank"><i class="fas fa-download"></i></a></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="confirmProjectFieldsFile('.$res[$i]['RowID'].')" ><i class="fas fa-list"></i></button></td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;"><button class="btn btn-info" onclick="showProjectFieldsComments('.$res[$i]['RowID'].')" ><i class="fas fa-comment"></i></button></td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function confirmProjectFieldsFile($pwid,$desc,$radioValue){
        $acm = new acm();
        if(!$acm->hasAccess('projectManagement')){
            die("access denied");
            exit;
        }
        $db = new DBi();

        $nowDate = date('Y-m-d');
        $nowTime = date('H:i:s');

        $sqq = "SELECT `RowID` FROM `project_fields_workflow` WHERE `uid`={$_SESSION['userid']}";
        $rsq = $db->ArrayQuery($sqq);
        if (count($rsq) > 0){
            $res = "شما قبلا نظر خود را ثبت نموده اید !";
            $out = "false";
            response($res,$out);
            exit;
        }

        $query = "SELECT `unitID` FROM `users` WHERE `RowID`={$_SESSION['userid']}";
        $rst = $db->ArrayQuery($query);

        $sql = "INSERT INTO `project_fields_workflow` (`pwid`,`uid`,`unitID`,`status`,`createDate`,`createTime`,`description`) VALUES ({$pwid},{$_SESSION['userid']},{$rst[0]['unitID']},{$radioValue},'{$nowDate}','{$nowTime}','{$desc}')";
        $res = $db->Query($sql);
        if (intval($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function projectFieldsCommentHtm($pwid){
        $db = new DBi();
        $ut = new Utility();

        $sql = "SELECT * FROM `project_fields_workflow` WHERE `pwid`={$pwid}";
        $res = $db->ArrayQuery($sql);
        $cnt = count($res);
        $iterator = 0;
        $htm = '';
        $htm .= '<table class="table table-bordered table-hover table-sm" id="projectFieldsComment-tableID">';
        $htm .= '<thead>';
        $htm .= '<tr class="bg-info">';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 5%;">ردیف</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 15%;">ثبت کننده</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">واحد</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">تاریخ</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">ساعت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 10%;">وضعیت</td>';
        $htm .= '<td style="text-align: center;color: #ffc107;font-family: dubai-Bold;width: 40%;">نظرات</td>';
        $htm .= '</tr>';
        $htm .= '</thead>';
        $htm .= '<tbody>';
        for ($i=0;$i<$cnt;$i++){
            $iterator++;
            $sql1 = "SELECT `fname`,`lname` FROM `users` WHERE `RowID`={$res[$i]['uid']}";
            $res1 = $db->ArrayQuery($sql1);

            $sql2 = "SELECT `Uname` FROM `official_productive_units` WHERE `RowID`={$res[$i]['unitID']}";
            $res2 = $db->ArrayQuery($sql2);

            $htm .= '<tr class="table-secondary">';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$iterator.'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res1[0]['fname'].' '.$res1[0]['lname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res2[0]['Uname'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$ut->greg_to_jal($res[$i]['createDate']).'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['createTime'].'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.(intval($res[$i]['status']) == 0 ? 'عدم تایید' : 'تایید').'</td>';
            $htm .= '<td style="text-align: center;font-family: dubai-Regular;padding: 10px;">'.$res[$i]['description'].'</td>';
            $htm .= '</tr>';
        }
        $htm .= '</tbody>';
        $htm .= '</table>';
        return $htm;
    }

    public function projectBossTick($pid){
        $db = new DBi();
        $sql = "UPDATE `project` SET `bossTick`=1 WHERE `RowID`={$pid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function projectFinalTick($pid){
        $db = new DBi();
        $sql = "UPDATE `project` SET `finalTick`=1 WHERE `RowID`={$pid}";
        $db->Query($sql);
        $aff = $db->AffectedRows();
        $aff = (($aff == -1 || $aff == 0) ? 0 : 1);
        if (intval($aff) > 0){
            return true;
        }else{
            return false;
        }
    }

}