<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Utility{

    public function __construct(){
        //do nothing
    }

    public function generatePagination($total_items, $items_per_page, $current_page, $js_navigate_func, $section,$max_pages = 5) {
        
         /**
     * تابع ایجاد pagination
     * 
     * @param int $total_items تعداد کل آیتم‌ها
     * @param int $items_per_page تعداد آیتم‌ها در هر صفحه
     * @param int $current_page صفحه فعلی
     * @param string $base_url آدرس پایه برای لینک‌ها
     * @param int $max_pages تعداد حداکثر صفحات نمایش داده شده در pagination
     * @return string کد HTML تولید شده برای pagination
     */
        // محاسبه تعداد کل صفحات
        $total_pages = ceil($total_items / $items_per_page);
        
        // اگر فقط یک صفحه وجود دارد یا هیچ آیتمی نیست، خروجی نداریم
        if ($total_pages <= 1) {
            return '';
        }
        
        // اطمینان از اینکه صفحه جاری معتبر است
        $current_page = max(1, min($current_page, $total_pages));
        
        // محاسبه محدوده صفحات برای نمایش
        $half = floor($max_pages / 2);
        $start_page = max(1, $current_page - $half);
        $end_page = min($total_pages, $start_page + $max_pages - 1);
        
        // تنظیم مجدد در صورتی که محدوده کافی نباشد
        if ($end_page - $start_page + 1 < $max_pages) {
            $start_page = max(1, $end_page - $max_pages + 1);
        }
        
        // شروع ساخت HTML
        $html = '<ul class="pagination">';
        
        // دکمه قبلی
        if ($current_page > 1) {
            $html .= '<li class="page-item"><a class="page-link" onclick="' . $js_navigate_func . "(".($current_page - 1).")" . '">&laquo; قبلی</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">&laquo; قبلی</span></li>';
        }
        
        // لینک به صفحه اول
        if ($start_page > 1) {
            $html .= '<li class="page-item"><a class="page-link"  onclick="' .  $js_navigate_func . '(1)">1</a></li>';
            if ($start_page > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        // لینک صفحات
        for ($i = $start_page; $i <= $end_page; $i++) {
            if ($i == $current_page) {
                $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link"  onclick="' .  $js_navigate_func . '('. $i . ')">' . $i . '</a></li>';
            }
        }
        
        // لینک به صفحه آخر

        if ($end_page < $total_pages) {

            if ($end_page < $total_pages - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $html .= '<li class="page-item"><a class="page-link"  onclick="' .  $js_navigate_func . '('. $total_pages . ')">' . $total_pages . '</a></li>';
        }
        
        // دکمه بعدی
        if ($current_page < $total_pages) {
            $html .= '<li class="page-item"><a class="page-link" onclick="' . $js_navigate_func . '('.($current_page + 1).') ">بعدی &raquo;</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">بعدی &raquo;</span></li>';
        }
        
        $html .= '</ul>';
        
        return $html;
    }

    public function MackHash($pass){
        $options = [
            'cost' => COST
        ];
        $value = password_hash($pass,PASSWORD_BCRYPT,$options);
        return $value;
    }

    function Redirect($url){
        @header('Location: ' . $url);
        exit('<meta http-equiv="Refresh" content="0; url=' . $url . '" />');
    }

    public function fileRecorder($text){
        //return false;
 
        $allow_users=$this->get_full_access_users(24);

        $allowed_log=in_array($_SESSION['userid'],$allow_users);

        if($allowed_log)
        {
            $fp = fopen("output.txt","a+");
            if(is_array($text)){
                fwrite($fp,"\n--------------------------------------------------------vvvv----------\n"."user id :".$_SESSION['userid']."\n".print_r($text,true)."\n---------------------------------------------^^^^----------");
            }
            else{
                error_log('u3');
                fwrite($fp,"\n--------------------------------------------------------vvvv----------\n"."user id :".$_SESSION['userid']."\n".$text."\n--------------------------------------------------------^^^^----------");
            }
            fclose($fp);
        }
    }

    public function varCleanInput($param){
        if(!count($param)){
            return array();
        }
        $db = new DBi();
        $result = array();
        $cnt = count($param);
        for($p=0;$p<$cnt;$p++){
            $result[$p] = $db->Escape(trim($_POST[$param[$p]]));
        }
        return $result;
    }

    public function numberFormat($number){
        return number_format($number);
    }

    public function jal_to_greg_to_ts($dateTxt){
        if(!strlen($dateTxt)){
            return false;
        }
        $dateArr = explode("/",$dateTxt);
        $gregDate = jalali_to_gregorian($dateArr[0],$dateArr[1],$dateArr[2]);
        $geDate =  $gregDate[0]."-".$gregDate[1]."-".$gregDate[2]." 00:00:00 ";
        $geDate = strtotime($geDate);
        return $geDate;
    }

    public function jal_to_greg_to_ts_EndDay($dateTxt){
        if(!strlen($dateTxt)){
            return false;
        }
        $dateArr = explode("/",$dateTxt);
        $gregDate = jalali_to_gregorian($dateArr[0],$dateArr[1],$dateArr[2]);
        $geDate =  $gregDate[0]."-".$gregDate[1]."-".$gregDate[2]." 23:59:59 ";
        $geDate = strtotime($geDate);
        return $geDate;
    }

    public function greg_to_jal($d){
        if(!strlen($d) || $d=="0000-00-00" ||$d=="0000-00-00 00:00:00"){
            return false;
        }
        $dateArr = explode("-",$d);
        $jaldate = gregorian_to_jalali($dateArr[0],$dateArr[1],$dateArr[2]);
        return $jaldate[0]."/".$jaldate[1]."/".$jaldate[2];
    }

    public function jal_to_greg($dateTxt){
        if(!strlen($dateTxt)){
            return false;
        }
        $dateArr = explode("/",$dateTxt);
        $gregDate = jalali_to_gregorian($dateArr[0],$dateArr[1],$dateArr[2]);
        $geDate =  $gregDate[0]."-".$gregDate[1]."-".$gregDate[2];
        return $geDate;
    }

    public function getHtmlOfDefaultManagementPage($pagename='',$pageIcon='',$contentId='',$buttons=array(),$headerSearch=array(),$inputs='',$changeableFields=array(),$hiddenContentId=''){
        $htm = '';
        $htm .= '<br/>';
        $htm .= '<div class="card text-warning bg-secondary">';
        $htm .= '<div class="card-header">';
        $htm .= '<i class="fas '.$pageIcon.' fa-lg" ></i>&nbsp;&nbsp;'.$pagename.'';
        $htm .= '</div>';
        $htm .= '<div class="card-body">';
        if(count($buttons) > 0){
            $Countbuttons = count($buttons);
            $htm .= '<div class="btn-group mb-2" role="group" style="display: flow-root !important;" aria-label="Basic example">';
            for($b=0 ;$b<$Countbuttons;$b++){
                $htm .= '<button type="button" onclick="'.$buttons[$b]["jsf"].'('.$buttons[$b]["params"].')" class="btn btn-light" '.(isset($buttons[$b]['id']) ? $buttons[$b]['id'] : '').' '.(isset($buttons[$b]['style']) ? $buttons[$b]['style'] : '').' title="'.$buttons[$b]["title"].'"><i class="fas '.$buttons[$b]["icon"].'"></i>&nbsp;&nbsp;'.$buttons[$b]["title"].'</button>';
            }
            $htm .= '</div>';
        }
        if(count($headerSearch) > 0 || count($changeableFields) > 0) {
            $htm .= '<div class="card mb-2" style="flex-flow: row wrap">';
            //++++++++++++++++++Start Content ++++++++++++++++++++++++++++
            if(count($headerSearch) > 0) {
                $htm .= $this->createHeaderSearch($headerSearch);
            }
            if(count($changeableFields) > 0) {
                $htm .= $this->createChangeableFields($changeableFields);
            }
            $htm .= '</div>';
        }
        $htm .= '<div id="'.$contentId.'">';
        $htm .= '';
        $htm .= '</div>';
        if (strlen(trim($inputs)) > 0){
            $htm .= '<div>';
            $htm .= '<input type="hidden" id="'.$inputs.'" />';
            $htm .= '</div>';
        }

        $htm .= '<div id="'.$hiddenContentId.'" style="display: none;">';
        $htm .= '';
        $htm .= '</div>';

        $htm .= '</div>';
        $htm .= '</div>';
        return $htm;
    }

    public function getHtmlOfDefaultManagementMultiPage($pagename=array(),$pageIcon=array(),$contentId=array(),$menuItems=array(),$bottons=array(),$manifold=0,$headerSearch=array(),$hiddenContentId=array(),$inputs=array()){
        if($manifold > 1) {
            $htm = "<br/>";
            $htm .= '<ul class="nav nav-tabs" style="margin-bottom: 1px;">';
            for ($i = 0; $i < $manifold; $i++) {
                $htm .= '<li class="nav-item"><a '.($i == 0 ? 'class="nav-link active"' : 'class="nav-link"').' style="color: #000;" href="#'.$menuItems[$i].'" id="'.$menuItems[$i].'-alink" data-toggle="tab">'.$pagename[$i].'</a></li>';
            }
            $htm .= '</ul>';
            $htm .= '<div class="tab-content">';
            for ($j = 0; $j < $manifold; $j++) {
                $htm .= '<div class="tab-pane'.($j == 0 ? ' active' : '').'" id="'.$menuItems[$j].'">';
                $htm .= '<br/>';
                $htm .= '<div class="card text-warning bg-secondary">';
                $htm .= '<div class="card-header">';
                $htm .= '<i class="fas '.$pageIcon[$j].' fa-lg" ></i>&nbsp;&nbsp;&nbsp;'.$pagename[$j];
                $htm .= '</div>';
                $htm .= '<div class="card-body" id="'.$contentId[$j].'-body">';
                if (count($bottons) > 0) {
                    $htm .= '<div class="btn-group" style="margin-bottom: 10px;" role="group" aria-label="...">';
                    for ($b = 0; $b < count($bottons[$j]); $b++) {
                        $htm .= '<button type="button" id="'.$bottons[$j][$b]["id"].'" onclick="' . $bottons[$j][$b]["jsf"] . '()" class="btn btn-light" title="' . $bottons[$j][$b]["title"] . '" '.(isset($bottons[$j][$b]['id']) ? $bottons[$j][$b]['id'] : '').' ><i class="fas ' . $bottons[$j][$b]["icon"] . '"></i>&nbsp;&nbsp;' . $bottons[$j][$b]["title"] . '</button>';
                    }
                    $htm .= '</div>';
                }
                if(count($headerSearch[$j]) > 0) {
                    $htm .= '<div class="card mb-2">';
                    //++++++++++++++++++Start Content ++++++++++++++++++++++++++++
                    $htm .= $this->createHeaderSearch($headerSearch[$j]);
                    $htm .= '</div>';
                }
                $htm .= '<div id="'.$contentId[$j].'" style="" >';
                $htm .= '';
                $htm .= '</div>';

                if (count($inputs) > 0){
                    $htm .= '<div>';
                    $htm .= '<input type="hidden" id="'.$inputs.'" />';
                    $htm .= '</div>';
                }

                $htm .= '<div id="'.$hiddenContentId[$j].'" style="display: none;">';
                $htm .= '';
                $htm .= '</div>';

                $htm .= '</div>'; //body
                $htm .= '</div>'; //card
                $htm .= '</div>'; //tab-pane
            }
            $htm .= '</div>';  //content
        }else{
            $htm = '<br/>';
            $htm .= '<div class="card text-warning bg-secondary">';
            $htm .= '<div class="card-header">';
            $htm .= '<i class="fas '.$pageIcon[0].' fa-lg" ></i>&nbsp;&nbsp;'.$pagename[0].'';
            $htm .= '</div>';
            $htm .= '<div class="card-body">';
            if(count($bottons)>0){
                $Countbuttons = count($bottons[0]);
                $htm .= '<div class="btn-group mb-2" role="group" style="display: flow-root !important;" aria-label="Basic example">';
                for($b=0 ;$b<$Countbuttons;$b++){
                    $htm .= '<button type="button" onclick="'.$bottons[0][$b]["jsf"].'()" class="btn btn-light" title="'.$bottons[0][$b]["title"].'" '.(isset($buttons[0][$b]['id']) ? $buttons[0][$b]['id'] : '').'><i class="fas '.$bottons[0][$b]["icon"].'"></i>&nbsp;&nbsp;'.$bottons[0][$b]["title"].'</button>';
                }
                $htm .= '</div>';
            }
            if(count($headerSearch[0]) > 0) {
                $htm .= '<div class="card mb-2">';
                //++++++++++++++++++Start Content ++++++++++++++++++++++++++++
                $htm .= $this->createHeaderSearch($headerSearch[0]);
                $htm .= '</div>';
            }
            $htm .= '<div id="'.$contentId[0].'">';
            $htm .= '';
            $htm .= '</div>';

            $htm .= '<div id="'.$hiddenContentId[0].'" style="display: none;">';
            $htm .= '';
            $htm .= '</div>';

            $htm .= '</div>';
            $htm .= '</div>';
        }
        return $htm;
    }

    private function createHeaderSearch($headerSearch){
        $CountheaderSearch = count($headerSearch);
        $htm = '';
        $htm .= '<form class="form-inline" style="margin: 10px 0 0 20px;">';
        for($i=0;$i<$CountheaderSearch;$i++){
            switch($headerSearch[$i]['type']){
                case "text" :
                case "password" :
                    $htm .= '<div id="'.$headerSearch[$i]['id'].'-div" '.(isset($headerSearch[$i]['style']) ? $headerSearch[$i]['style'] : '').'>';
                    $htm .= '<label class="sr-only" for="'.$headerSearch[$i]['id'].'">'.$headerSearch[$i]['title'].'</label>';
                    $htm .= '<input type="'.$headerSearch[$i]['type'].'" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="'.$headerSearch[$i]['id'].'" autocomplete="off" style="width:'.$headerSearch[$i]['width'].';" placeholder="'.$headerSearch[$i]['placeholder'].'" '.( isset($headerSearch[$i]['onkeyup']) ? $headerSearch[$i]['onkeyup'] : '').' '.( isset($headerSearch[$i]['onchange']) ? $headerSearch[$i]['onchange'] : '').'>';
                    $htm .= '</div>';
                    break;
                case "textarea" :
                    $htm .= '<div id="'.$headerSearch[$i]['id'].'-div" '.(isset($headerSearch[$i]['style']) ? $headerSearch[$i]['style'] : '').'>';
                    $htm .= '<label class="sr-only" for="'.$headerSearch[$i]['id'].'">'.$headerSearch[$i]['title'].'</label>';
                    $htm .= '<textarea autocomplete="off" rows="3" style="width:'.$headerSearch[$i]['width'].';" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="'.$headerSearch[$i]['id'].'" placeholder="'.$headerSearch[$i]['placeholder'].'"></textarea>';
                    $htm .= '</div>';
                    break;
                case "select" :
                    $htm .= '<div id="'.$headerSearch[$i]['id'].'-div" '.(isset($headerSearch[$i]['style']) ? $headerSearch[$i]['style'] : '').'>';
                    $htm .= '<label class="sr-only" for="'.$headerSearch[$i]['id'].'">'.$headerSearch[$i]['title'].' </label>';
                    $htm .= '<select '.( isset($headerSearch[$i]['multiple']) ? 'class="mb-2 mr-sm-2 headsearch" multiple title="'.$headerSearch[$i]['title'].'" data-selected-text-format="count" data-live-search="true"' : 'class="form-control form-control-sm mb-2 mr-sm-2 headsearch"').' '.( isset($headerSearch[$i]['LimitNumSelections']) ? 'multiple data-max-options="'.$headerSearch[$i]['LimitNumSelections'].'"' : '').' '.( isset($headerSearch[$i]['actionsBox']) ? 'data-actions-box="true"' : '').' style="width:'.$headerSearch[$i]['width'].'" id="'.$headerSearch[$i]['id'].'" '.( isset($headerSearch[$i]['onchange']) ? $headerSearch[$i]['onchange'] : '').'>';
                    for($o=0;$o<count($headerSearch[$i]['options']);$o++){
                        $htm .= '<option value="'.$headerSearch[$i]['options'][$o]['value'].'">'.$headerSearch[$i]['options'][$o]['title'].'</option>';
                    }
                    $htm .= '</select>';
                    $htm .= '</div>';
                    break;
                case "hidden" :
                    $htm .= '<input type="hidden" id="'.$headerSearch[$i]['id'].'" />';
                    break;
                case "btn":
                    $htm .= '<button type="button" class="btn btn-sm btn-secondary mb-2 mr-2" style="padding: 0.25rem 0.5rem;" onclick="'.$headerSearch[$i]['jsf'].'();">'.$headerSearch[$i]['title'].'</button>';
                    break;
            }

        }
        $htm .= '</form>';
        return $htm;
    }

    private function createChangeableFields($changeableFields){
        $cnt = count($changeableFields);
        $htm = '';
        $htm .= '<form class="form-inline" style="margin-top: 10px;">';
        for($i=0;$i<$cnt;$i++){
            switch($changeableFields[$i]['type']){
/*                case "text" :
                case "password" :
                    $htm .= '<div id="'.$headerSearch[$i]['id'].'-div" '.(isset($headerSearch[$i]['style']) ? $headerSearch[$i]['style'] : '').'>';
                    $htm .= '<label class="sr-only" for="'.$headerSearch[$i]['id'].'">'.$headerSearch[$i]['title'].'</label>';
                    $htm .= '<input type="'.$headerSearch[$i]['type'].'" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="'.$headerSearch[$i]['id'].'" autocomplete="off" style="width:'.$headerSearch[$i]['width'].';" placeholder="'.$headerSearch[$i]['placeholder'].'" '.( isset($headerSearch[$i]['onkeyup']) ? $headerSearch[$i]['onkeyup'] : '').'>';
                    $htm .= '</div>';
                    break;
                case "textarea" :
                    $htm .= '<div id="'.$headerSearch[$i]['id'].'-div" '.(isset($headerSearch[$i]['style']) ? $headerSearch[$i]['style'] : '').'>';
                    $htm .= '<label class="sr-only" for="'.$headerSearch[$i]['id'].'">'.$headerSearch[$i]['title'].'</label>';
                    $htm .= '<textarea autocomplete="off" rows="3" style="width:'.$headerSearch[$i]['width'].';" class="form-control form-control-sm mb-2 mr-sm-2 headsearch" id="'.$headerSearch[$i]['id'].'" placeholder="'.$headerSearch[$i]['placeholder'].'"></textarea>';
                    $htm .= '</div>';
                    break;*/
                case "select" :
                    $htm .= '<div id="'.$changeableFields[$i]['id'].'-div" '.(isset($changeableFields[$i]['style']) ? $changeableFields[$i]['style'] : '').'>';
                    $htm .= '<label class="sr-only" for="'.$changeableFields[$i]['id'].'">'.$changeableFields[$i]['title'].' </label>';
                    $htm .= '<select '.( isset($changeableFields[$i]['multiple']) ? 'class="mb-2 mr-sm-2 headsearch" multiple data-selected-text-format="count"' : 'class="form-control form-control-sm mb-2 mr-sm-2 headsearch"').' style="width:'.$changeableFields[$i]['width'].'" id="'.$changeableFields[$i]['id'].'" '.( isset($changeableFields[$i]['onchange']) ? $changeableFields[$i]['onchange'] : '').'>';
                    for($o=0;$o<count($changeableFields[$i]['options']);$o++){
                        $htm .= '<option value="'.$changeableFields[$i]['options'][$o]['value'].'">'.$changeableFields[$i]['options'][$o]['title'].'</option>';
                    }
                    $htm .= '</select>';
                    $htm .= '</div>';
                    break;
/*                case "hidden" :
                    $htm .= '<input type="hidden" id="'.$headerSearch[$i]['id'].'" />';
                    break;
                case "btn":
                    $htm .= '<button type="button" class="btn btn-sm btn-secondary mb-2 mr-2" style="padding: 0.25rem 0.5rem;" onclick="'.$headerSearch[$i]['jsf'].'();">'.$headerSearch[$i]['title'].'</button>';
                    break;*/
            }

        }
        $htm .= '</form>';
        return $htm;
    }

    public function getHtmlModal($modalID,$modalTitle,$items=array(),$footerBottons=array(),$txt='',$contentDivId='',$style='',$ShowDescription='',$enctype='',$styleModal='',$topperBottons=array(),$tabular_show=0){
        
        $htm = "";
        $htm .= '<div id="'.$modalID.'" class="modal fade" tabindex="-1" '.$styleModal.' role="dialog" aria-labelledby="'.$modalID.'-title" aria-hidden="true"  data-keyboard="false" data-backdrop="static"> ';
        $htm .= '<div class="modal-dialog" '.$style.' role="document">';
        $htm .= '<div class="modal-content" >';
        $htm .= '<div class="modal-header bg-secondary">';
        $htm .= '<h5 class="modal-title text-light" id="'.$modalID.'-title">'.$modalTitle.'</h5>';
        $htm .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true">&times;</button>';
        $htm .= '</div>';
        $htm .= '<div class="modal-body" id="'.$contentDivId.'" >';
        $style="";
        if($tabular_show>0){
            $style="display: inline-grid;column-gap: 8px;justify-content: space-between;align-items: center;width: 100%;padding: 10px;margin:0 auto;";
            switch($tabular_show){
                case "1":
                    $style.="grid-template-columns: 50% 50%";
                    break;
                case "2":
                    $style.="grid-template-columns: 33% 33% 33%";
                    break;
                case "3":
                    $style.="grid-template-columns: 25% 25% 25% 25%";
                    break;   
            }
        }
        if(count($items)>0){
            $htm .= '<form style="'.($style?$style:'').'" class="modal-form-m" '.(strlen(trim($enctype)) > 0 ? $enctype : '').'>';
            for($i=0;$i<count($items);$i++){
                $countOption = count($items[$i]['options']);
                switch($items[$i]['type']){
					case "text" :
                    case "password" :
                        $htm .= '<div class="form-group '.(isset($items[$i]['asterisk']) ? $items[$i]['asterisk'] : '').' row" id="'.$items[$i]['id'].'-div" >';
                        $htm .= '<label for="'.$items[$i]['id'].'" class="col-sm-6 col-form-label text-align-form" id="'.$items[$i]['id'].'-txt" style="text-align:center;">'.$items[$i]['title'].' </label>';
                        $htm .= '<div class="col-sm-6" id="'.$items[$i]['id'].'-col" >';
                        $htm .= '<input type="'.$items[$i]['type'].'" class="form-control shadow-color" id="'.$items[$i]['id'].'" autocomplete="off" placeholder="'.$items[$i]['placeholder'].'" '.(isset($items[$i]['style']) ? $items[$i]['style'] : '').' '.(isset($items[$i]['data-format']) ? $items[$i]['data-format'] : '').' '.(isset($items[$i]['data-provide']) ? $items[$i]['data-provide'] : '').' '.( isset($items[$i]['disabled']) ? $items[$i]['disabled'] : '').' '.( isset($items[$i]['onfocus']) ? $items[$i]['onfocus'] : '').' '.( isset($items[$i]['onchange']) ? $items[$i]['onchange'] : '').' '.( isset($items[$i]['onkeyup']) ? $items[$i]['onkeyup'] : '').' '. ( isset($items[$i]['onblur']) ? $items[$i]['onblur'] : '').' '.(isset($items[$i]['value']) ? $items[$i]['value'] : '').'>';
                        if(isset($items[$i]['add-on'])) {
                            $htm .= '<span class="add-on p-1 mr-0"><img src="./css/images/pclock.png" style="cursor: pointer;" /></span>';
                        }
                        $htm .= '</div>';
                        $htm .= '</div>';
                        break;
                    case "number" :
						$minMax="";
						if($items[$i]['type']=="number"){$form_container_box="col-sm-2"; $minMax='min="0" max="100"';}else{$form_container_box="col-sm-6";}
                        $htm .= '<div class="form-group '.(isset($items[$i]['asterisk']) ? $items[$i]['asterisk'] : '').' row" id="'.$items[$i]['id'].'-div" >';
                        $htm .= '<label for="'.$items[$i]['id'].'" class="col-sm-6 col-form-label text-align-form" id="'.$items[$i]['id'].'-txt" style="text-align:center;">'.$items[$i]['title'].' </label>';
                        $htm .= '<div class="'.$form_container_box.'" id="'.$items[$i]['id'].'-col" >';
                        $htm .= '<input 
									type="'.$items[$i]['type'].'" 
										class="form-control shadow-color" id="'.$items[$i]['id'].'" 
											autocomplete="off"
												'.$minMax.'
												placeholder="'.$items[$i]['placeholder'].'" '.(isset($items[$i]['style']) ? $items[$i]['style'] : '').' '.(isset($items[$i]['data-format']) ? $items[$i]['data-format'] : '').' '.(isset($items[$i]['data-provide']) ? $items[$i]['data-provide'] : '').' '.( isset($items[$i]['disabled']) ? $items[$i]['disabled'] : '').'onchange="'.( isset($items[$i]['onchange']) ? $items[$i]['onchange'] : '').'" '.( isset($items[$i]['onkeyup']) ? $items[$i]['onkeyup'] : '').'value="'.(isset($items[$i]['value']) ? $items[$i]['value'] : '').'">';
                        if(isset($items[$i]['add-on'])) {
                            $htm .= '<span class="add-on p-1 mr-0"><img src="./css/images/pclock.png" style="cursor: pointer;" /></span>';
                        }
                        $htm .= '</div>';
                        $htm .= '</div>';
                        break;
                    case "inputGroup" :
                        $htm .= '<div class="form-group row" id="'.$items[$i]['id'].'-div" >';
                        $htm .= '<label for="'.$items[$i]['id'].'" class="col-sm-6 col-form-label text-align-form" id="'.$items[$i]['id'].'-txt" style="text-align:center;">'.$items[$i]['title'].' </label>';
                        $htm .= '<div class="input-group col-sm-6" id="'.$items[$i]['id'].'-col" >';
                        $htm .= '<input type="text" class="form-control shadow-color" id="'.$items[$i]['id'].'" placeholder="'.$items[$i]['placeholder'].'" '.(isset($items[$i]['style']) ? $items[$i]['style'] : '').' '.(isset($items[$i]['value']) ? $items[$i]['value'] : '').'>';
                        $htm .= '<div class="input-group-prepend" '.(isset($items[$i]['style']) ? $items[$i]['style'] : '').'>';
                        $htm .= '<span class="input-group-text" '.(isset($items[$i]['SpanStyle']) ? $items[$i]['SpanStyle'] : 'style="cursor: pointer;"').' id="'.$items[$i]['id'].'-span" '.(isset($items[$i]['onclick']) ? $items[$i]['onclick'] : '').'><i class="fas '.$items[$i]['icon'].'"></i></span>';
                        $htm .= '</div>'; // input-group-prepend
                        $htm .= '</div>'; // input-group
                        $htm .= '</div>'; // form-group row
                        break;
                    case "inputSelectGroup" :
                        $htm .= '<div class="form-group row" id="'.$items[$i]['id'].'-div" >';
                        $htm .= '<label for="'.$items[$i]['id'].'" class="col-sm-6 col-form-label text-align-form" id="'.$items[$i]['id'].'-txt" style="text-align:center;">'.$items[$i]['title'].' </label>';
                        $htm .= '<div class="input-group col-sm-6" id="'.$items[$i]['id'].'-col" >';
                        $htm .= '<select '.( isset($items[$i]['multiple']) ? 'class="shadow-color" multiple data-selected-text-format="count" data-live-search="true"' : 'class="form-control shadow-color"').' '.( isset($items[$i]['LimitNumSelections']) ? 'multiple data-max-options="'.$items[$i]['LimitNumSelections'].'"' : '').' id="'.$items[$i]['id'].'" '.(isset($items[$i]['style']) ? $items[$i]['style'] : '').' '.(isset($items[$i]['onchange']) ? $items[$i]['onchange'] : '').'>';
                        $selected = '';
                        for($o=0;$o<$countOption;$o++){
                            if($items[$i]['options'][$o]['selected'] == true){
                                $selected = "selected";
                            }
                            $htm .= '<option value="'.$items[$i]['options'][$o]['value'].'"  '.$selected.'>'.$items[$i]['options'][$o]['title'].'</option>';
                        }
                        $htm .= '</select>';
                        $htm .= '<div class="input-group-prepend" '.(isset($items[$i]['style']) ? $items[$i]['style'] : '').'>';
                        $htm .= '<span class="input-group-text" '.(isset($items[$i]['SpanStyle']) ? $items[$i]['SpanStyle'] : 'style="cursor: pointer;"').' id="'.$items[$i]['id'].'-span" '.(isset($items[$i]['onclick']) ? $items[$i]['onclick'] : '').' title="'.$items[$i]['btn_title'].'"><i class="fas '.$items[$i]['icon'].'"></i></span>';
                        $htm .= '</div>'; // input-group-prepend
                        $htm .= '</div>'; // input-group
                        $htm .= '</div>'; // form-group row
                        break;
                    case "select" :
                        $htm .= '<div class="form-group '.(isset($items[$i]['asterisk']) ? $items[$i]['asterisk'] : '').' row" id="'.$items[$i]['id'].'-div">';
                        $htm .= '<label for="'.$items[$i]['id'].'" class="col-sm-6 col-form-label text-align-form" style="text-align:center;">'.$items[$i]['title'].'</label>';
                        $htm .= '<div class="col-sm-6" >';

                        $htm .= '<select '.( isset($items[$i]['multiple']) ? 'class="shadow-color" multiple data-selected-text-format="count" data-live-search="true" ' : 'class="form-control shadow-color"').' '.( isset($items[$i]['LimitNumSelections']) ? 'data-max-options="'.$items[$i]['LimitNumSelections'].'"' : '').' '.( isset($items[$i]['actionsBox']) ? 'data-actions-box="true"' : '').' id="'.$items[$i]['id'].'" '.(isset($items[$i]['style']) ? $items[$i]['style'] : '').' '.(isset($items[$i]['onchange']) ? $items[$i]['onchange'] : '').'>';
                        $selected = '';
                        for($o=0;$o<$countOption;$o++){
                            if($items[$i]['options'][$o]['selected'] == true){
                                $selected = "selected";
                            }
                            $htm .= '<option value="'.$items[$i]['options'][$o]['value'].'"  '.$selected.'>'.$items[$i]['options'][$o]['title'].'</option>';
                        }
                        $htm .= '</select>';
                        $htm .= '</div>';
                        $htm .= '</div>';
                        break;
                    case "textarea":
                        $htm .= '<div class="form-group row" id="'.$items[$i]['id'].'-div">';
                        $htm .= '<label for="'.$items[$i]['id'].'" class="col-sm-6 col-form-label text-align-form" style="text-align:center;"> '.$items[$i]['title'].'</label>';
                        $htm .= '<div class="col-sm-6">';
                        $htm .= '<textarea rows="4" class="form-control shadow-color" id="'.$items[$i]['id'].'" placeholder="'.$items[$i]['placeholder'].'" '.(isset($items[$i]['style']) ? $items[$i]['style'] : '').' '.( isset($items[$i]['disabled']) ? $items[$i]['disabled'] : '').' ></textarea>';
                        $htm .= '</div>';
                        $htm .= '</div>';
                        break;
                    case "checkbox" :
                        $cntOption = count($items[$i]['options']);
                        $htm .= '<div class="form-group '.(isset($items[$i]['asterisk']) ? $items[$i]['asterisk'] : '').' row" id="'.$items[$i]['id'].'-div">';
                        if(intval($cntOption) > 1) {
                            $htm .= '<label class="col-sm-6 col-form-label text-align-form" style="text-align:center;">'.$items[$i]['title'].' </label>';
                            $htm .= '<div class="form-check form-check-inline col-sm-6" style="margin-right: 0;">';
                            for($o = 0; $o < $cntOption; $o++) {
                                $htm .= '<label class="form-check-label" for="' . $items[$i]['options'][$o]['id'] . '" style="cursor: pointer;">' . $items[$i]['options'][$o]['title'] . '</label>';
                                $htm .= '<input class="form-check-input" type="checkbox" ' . (isset($items[$i]['name']) ? $items[$i]['name'] : '') . ' id="' . $items[$i]['options'][$o]['id'] . '" value="' . $items[$i]['options'][$o]['value'] . '" style="margin-left: 10px;">';
                            }
                        }else{
                            $htm .= '<label class="col-sm-6 col-form-label text-align-form" for="' . $items[$i]['id'] . '" style="text-align:center;cursor: pointer;">'.$items[$i]['title'].' </label>';
                            $htm .= '<div class="form-check form-check-inline col-sm-6" style="margin-right: 0;">';
                            $htm .= '<input class="form-check-input" type="checkbox" id="' . $items[$i]['id'] . '" style="margin-left: 10px;">';
                        }
                        $htm .= '</div>';
                        $htm .= '</div>';
                        break;
                    case "radio" :// ***********************************************رویداد onclick  به  radio اضافه شد*************************************************************
                        $htm .= '<div class="form-group row" id="'.$items[$i]['name'].'-div">';
                        $htm .= '<label class="col-sm-6 col-form-label text-align-form" style="text-align:center;">'.$items[$i]['title'].' </label>';
                        $htm .= '<div class="col-sm-6 mr-0 form-check form-check-inline">';
                        for($o=0;$o<count($items[$i]['options']);$o++){
                            $htm .= '<label class="form-check-label" for="'.$items[$i]['name'].'-id-'.$items[$i]['options'][$o]['value'].'" id="'.$items[$i]['name'].'-label-'.$items[$i]['options'][$o]['value'].'" style="cursor: pointer;" data-toggle="tooltip" data-placement="bottom" title="'.(isset($items[$i]['options'][$o]['headline']) ? $items[$i]['options'][$o]['headline'] : '').'">'.$items[$i]['options'][$o]['title'].'</label>';
                            $htm .= '<input class="form-check-input" type="radio" name="'.$items[$i]['name'].'" id="'.$items[$i]['name'].'-id-'.$items[$i]['options'][$o]['value'].'"  value="'.$items[$i]['options'][$o]['value'].'"'.($items[$i]['options'][$o]['onclick']? 'onclick="'.$items[$i]['options'][$o]['onclick'].'"':'').'>&nbsp;&nbsp;&nbsp;&nbsp;';
                        }
                        $htm .= '</div>';
                        $htm .= '</div>';
                        break;
                    case "hidden" :
                        $htm .= '<input type="hidden" id="'.$items[$i]['id'].'" '.(isset($items[$i]['value']) ? 'value="'.$items[$i]['value'].'"' : '').' />';
                        break;
                    case "file" :
                        $htm .= '<div class="form-group row" id="'.$items[$i]['id'].'-div" >';
                        $htm .= '<label class="col-sm-6 col-form-label text-align-form" style="text-align:center;">'.$items[$i]['title'].' </label>';
                        $htm .= '<div class="col-sm-6">';
                        $htm .= '<input type="'.$items[$i]['type'].'" style="margin-top: 5px;" '.( isset($items[$i]['name']) ? $items[$i]['name'] : 'name="photo"').' '.( isset($items[$i]['accept']) ? $items[$i]['accept'] : '').'  id="'.$items[$i]['id'].'" '.( isset($items[$i]['disabled']) ? $items[$i]['disabled'] : '').' '.( isset($items[$i]['multiple']) ? $items[$i]['multiple'] : '').'>';
                        $htm .= '<p class="help-block" style="font-family: dubai-Regular;">'.$items[$i]['helpText'].'</p>';
                        $htm .= '</div>';
                        $htm .= '</div>';
                        break;
                    case "twoText" :
                        $htm .= '<div class="form-group '.(isset($items[$i]['asterisk']) ? $items[$i]['asterisk'] : '').' row" id="'.$items[$i]['id'].'-div" >';
                        $htm .= '<label for="'.$items[$i]['id'].'" class="col-sm-6 col-form-label text-align-form" id="'.$items[$i]['id'].'-txt" style="text-align:center;">'.$items[$i]['title'].' </label>';
                        $htm .= '<div class="col-sm-6" id="'.$items[$i]['id'].'-col" >';
                        $htm .= '<input type="'.$items[$i]['type'].'" class="form-control shadow-color col-sm-6 ml-2" id="'.$items[$i]['id1'].'" autocomplete="off" placeholder="'.$items[$i]['placeholder1'].'" '.(isset($items[$i]['style']) ? $items[$i]['style'] : '').' '.( isset($items[$i]['onchange1']) ? $items[$i]['onchange1'] : '').' '.( isset($items[$i]['onkeyup']) ? $items[$i]['onkeyup'] : '').'>';
                        $htm .= '<input type="'.$items[$i]['type'].'" class="form-control shadow-color col-sm-6" id="'.$items[$i]['id2'].'" autocomplete="off" placeholder="'.$items[$i]['placeholder2'].'" '.(isset($items[$i]['style']) ? $items[$i]['style'] : '').' '.( isset($items[$i]['onchange2']) ? $items[$i]['onchange2'] : '').' '.( isset($items[$i]['onkeyup']) ? $items[$i]['onkeyup'] : '').'>';
                        $htm .= '</div>';
                        $htm .= '</div>';
                        break;
					case "progress" :
                        $htm .= '<div class="form-group '.(isset($items[$i]['asterisk']) ? $items[$i]['asterisk'] : '').' row" id="'.$items[$i]['id'].'-div" >';
                        $htm .= '<label for="'.$items[$i]['id'].'" class="col-sm-6 col-form-label text-align-form" id="'.$items[$i]['id'].'-txt" style="text-align:center;">'.$items[$i]['title'].' </label>';
                        $htm .= '<div class="col-sm-6" id="'.$items[$i]['id'].'-col" >';
                       // $htm .= '<input  type="number" value="" class="col-sm-2" max="100" min="0" onchange="'.$items[$i]['jsf'].'(event)" value="'.(isset($items[$i]['value']) ? $items[$i]['value'] : '').'">';
                        $htm .= '<div id="'.$items[$i]['progress_id'].'" class="progress" style="height:30px">
									<div class="progress-bar" role="progressbar" aria-label="Example with label" style="width:0px" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">0%</div>
								</div>';
                       //$htm .= '<input type="'.$items[$i]['type'].'" class="form-control shadow-color col-sm-6" id="'.$items[$i]['id2'].'" autocomplete="off" placeholder="'.$items[$i]['placeholder2'].'" '.(isset($items[$i]['style']) ? $items[$i]['style'] : '').' '.( isset($items[$i]['onchange2']) ? $items[$i]['onchange2'] : '').' '.( isset($items[$i]['onkeyup']) ? $items[$i]['onkeyup'] : '').'>';
                        $htm .= '</div>';
                        $htm .= '</div>';
                        break;
                    case "box":
                        $box_style_tabular="";
                        if($tabular_show>0){
                            $grid_strach=$tabular_show+1;
                            $box_style_tabular='display:none;grid-column: 1 / span '.($grid_strach);
                        }
                        
                        $htm.='<div id="'.$items[$i]['id'].'_container"style="'.$box_style_tabular.'"><fieldset style="'.$items[$i]['BoxStyle'].'" id="'.$items[$i]['id']."-ID".'"><legend style="'.$items[$i]['TitleStyle'].'">'.$items[$i]['title'].'</legend>';
                        $box_array="";
                        $box_array=$items[$i]['children'];
                        for($box_item_index=0;$box_item_index<count($box_array);$box_item_index++){
                            switch($box_array[$box_item_index]['type']){
                               case "text":
                                    $htm .= '<div class="form-group '.(isset($items[$i]['asterisk']) ? $items[$i]['asterisk'] : '').' row" id="'.$box_array[$box_item_index]['id'].'-div" >';
                                    $htm .= '<label for="'.$box_array[$box_item_index]['id'].'" class="col-sm-5 col-form-label text-align-form" id="'.$box_array[$box_item_index]['id'].'-txt" style="text-align:center;">'.
                                        $box_array[$box_item_index]['title'].' </label>';
                                    $htm .= '<div class="col-sm-7" id="'.$box_array[$box_item_index]['id'].'-col" >';
                                    $htm .= '<input type="'.$box_array[$box_item_index]['type'].'" class="form-control shadow-color" id="'.$box_array[$box_item_index]['id'].'" autocomplete="off" placeholder="'.$box_array[$box_item_index]['placeholder'].'" '.(isset($box_array[$box_item_index]['style']) ? $box_array[$box_item_index]['style'] : '').' '.(isset($box_array[$box_item_index]['data-format']) ? $box_array[$box_item_index]['data-format'] : '').' '.(isset($box_array[$box_item_index]['data-provide']) ? $box_array[$box_item_index]['data-provide'] : '').' '.( isset($box_array[$box_item_index]['disabled']) ? $box_array[$box_item_index]['disabled'] : '').' '.( isset($box_array[$box_item_index]['onchange']) ? $box_array[$box_item_index]['onchange'] : '').' '.( isset($box_array[$box_item_index]['onkeyup']) ? $box_array[$box_item_index]['onkeyup'] : '').' '.(isset($box_array[$box_item_index]['value']) ? $box_array[$box_item_index]['value'] : '').'>';
                                    if(isset($box_array[$box_item_index]['add-on'])) {
                                        $htm .= '<span class="add-on p-1 mr-0"><img src="./css/images/pclock.png" style="cursor: pointer;" /></span>';
                                    }
                                    $htm .= '</div>';
                                    $htm .= '</div>';
                                    break;
                                case "inputGroup":
                                    $htm .= '<div class="form-group row" id="'.$box_array[$box_item_index]['id'].'-div" >';
                                    $htm .= '<label for="'.$box_array[$box_item_index]['id'].'" class="col-sm-6 col-form-label text-align-form" id="'.$box_array[$box_item_index].'-txt" style="text-align:center;">'.$box_array[$box_item_index]['title'].' </label>';
                                    $htm .= '<div class="input-group col-sm-5" id="'.$box_array[$box_item_index]['id'].'-col" >';
                                    $htm .= '<input type="'.(isset($box_array[$box_item_index]['inputType']) ? $box_array[$box_item_index]['inputType'] : 'text').'" class="form-control shadow-color" id="'.$box_array[$box_item_index]['id'].'" placeholder="'.$box_array[$box_item_index]['placeholder'].'" '.(isset($box_array[$box_item_index]['style']) ? $box_array[$box_item_index]['style'] : '').' '.(isset($box_array[$box_item_index]['value']) ? $box_array[$box_item_index]['value'] : '').'>';
                                    $htm .= '<div class="input-group-prepend" '.(isset($box_array[$box_item_index]['style']) ? $items[$i]['style'] : '').'>';
                                    $htm .= '<span class="input-group-text" '.($box_array[$box_item_index]['SpanStyle'] ? $box_array[$box_item_index]['SpanStyle'] : 'style="cursor: pointer;"').' id="'.$box_array[$box_item_index]['id'].'-span" '.(isset($box_array[$box_item_index]['onclick']) ? $box_array[$box_item_index]['onclick'] : '').'><i class="fas '.$box_array[$box_item_index]['icon'].'"></i></span>';
                                    $htm .= '</div>'; // input-group-prepend
                                    $htm .= '</div>'; // input-group
                                    $htm .= '</div>'; // form-group row
                                    break;
                                case "radio":
                                    $htm .= '<div class="form-group row" id="'.$box_array[$box_item_index]['name'].'-div">';
                                    $htm .= '<label class="col-sm-5 col-form-label text-align-form" style="text-align:center;">'.$box_array[$box_item_index]['title'].' </label>';
                                    $htm .= '<div class="col-sm-7 mr-0 form-check form-check-inline">';
                                    //$this->fileRecorder($box_array);
                                    for($o=0;$o<count($box_array[$box_item_index]['options']);$o++){
                                        $htm .= '<label class="form-check-label" for="'.$box_array[$box_item_index]['name'].'-id-'.$box_array[$box_item_index]['options'][$o]['value'].'" id="'.$box_array[$box_item_index]['name'].'-label-'.$box_array[$box_item_index]['options'][$o]['value'].'" style="cursor: pointer;" data-toggle="tooltip" data-placement="bottom" title="'.(isset($box_array[$box_item_index]['options'][$o]['headline']) ? $box_array[$box_item_index][$o]['headline'] : '').'">'.$box_array[$box_item_index]['options'][$o]['title'].'</label>';
                                        $htm .= '<input class="form-check-input" type="radio" name="'.$box_array[$box_item_index]['name'].'" id="'.$box_array[$box_item_index]['name'].'-id-'.$box_array[$box_item_index]['options'][$o]['value'].'"  value="'.$box_array[$box_item_index]['options'][$o]['value'].'"'.($box_array[$box_item_index]['options'][$o]['onclick']? 'onclick="'.$box_array[$box_item_index]['options'][$o]['onclick'].'"':'').'>&nbsp;&nbsp;&nbsp;&nbsp;';
                                    }
                                    $htm .= '</div>';
                                    $htm .= '</div>';
                                    break;
                                case "btn":
                                    $htm .= '<button type="button" class="btn btn-sm btn-secondary mb-2 mr-2" style="padding: 0.25rem 0.5rem;" 
                                            onclick="'.$box_array[$box_item_index]['onclick'].'">'.$box_array[$box_item_index]['title'].'</button>';
                                    break;
                                case "paragraph" :
                                    $htm.='<p id="'.$box_array[$box_item_index]['id'].'" style="'.$box_array[$box_item_index]['style'].'"><span>'.$box_array[$box_item_index]['title'].'</span></p>';
                                    break;  
                            }
                        }
                        $htm.="</fieldset></div>";
                        break;
                    case "button" :
                        $htm .= '<div class="form-group row" id="'.$items[$i]['id'].'-div" >';
                       // $htm .= '<label class="col-sm-6 col-form-label text-align-form" style="text-align:center;">'.$items[$i]['title'].' </label>';
                        $htm .= '<div class="col-sm-12">';
                        $htm .= '<input '.(isset($items[$i]['class'])?'class="'.$items[$i]['class'].'"':'').' type="'.$items[$i]['type'].'" style="margin-top: 5px;'.(isset($items[$i]['style'])?$items[$i]['style']:"").'" '.( isset($items[$i]['name']) ? $items[$i]['name'] : '').'   id="'.$items[$i]['id'].'" '.( isset($items[$i]['disabled']) ? $items[$i]['disabled'] : '').' value="'.(( isset($items[$i]['title']) ? $items[$i]['title'] : 'Run')).'" '.(isset($items[$i]['onclick'])? $items[$i]['onclick']:'').'>';
                        $htm .= '</div>';
                        $htm .= '</div>';
                        break;
                    case "checkbox_group":
                        $htm .= '<div class="form-group row" id="'.$items[$i]['id'].'-div" >';
                        // $htm .= '<label class="col-sm-6 col-form-label text-align-form" style="text-align:center;">'.$items[$i]['title'].' </label>';
                         $htm .= '<div class="col-sm-6 text-center">'.$items[$i]['title'];
                         $htm .= '</div><div class="col-sm-6">';
                         $counter=0;
                         foreach($items[$i]['label'] as $key=>$value){

                            $htm .= '<label style="padding:5px" >
                                        <input '.(isset($items[$i]['class'])?'class="'.$items[$i]['class'].'"':'').' type="checkbox" 
                                            style="margin-top: 5px;'.(isset($value['style'])?'style="'.$value['style']:"").'" '.( isset($value['name']) ?'name="'. $value['name'].'"' : '').' 
                                                  id="'.$value['name'].'-'.$counter.'" '.( isset($value[$i]['disabled']) ? $value[$i]['disabled'] : '').'
                                                     value="'.(( isset($value['value']) ? $value['value'] : '')).'" '.(isset($value['onclick'])? 'onclick="'.$value['onclick']:'').'>'.$value['title'].'</label>';
                            $counter++;
                        }
                        
                       
                         $htm .= '</div></div>';
                        break;
						 case "paragraph" :
                                    $htm.='<div class="form-group p-1 '.$items[$i]['class'].'"><p style="width:100%;padding:10px;color:#fff;text-align:center"id="'.$items[$i]['id'].'" style="'.$items[$i]['style'].'"><span>'.$items[$i]['title'].' : </span><span>'.$items[$i]['text'].'</span></p></div>';
						break;  
                }
            }
            $htm .= '</form>';
        }
        if(strlen(trim($txt))>0){
            $htm .= "<div class='hoshdar' id='".$modalID."-under-modal'>".$txt."</div>";
        }
        if (count($topperBottons) > 0){
            $htm .= '<div class="modal-footer" >';
            for($i=0;$i<count($topperBottons);$i++){
                switch($topperBottons[$i]['type']){
                    case "btn":
                        $htm .= '<button type="button" id="'.$topperBottons[$i]['jsf'].'-btn" onclick="'.$topperBottons[$i]['jsf'].'()" class="btn btn-primary" '.(isset($topperBottons[$i]['data-dismiss']) ? '' : 'data-dismiss="modal"').' >'.$topperBottons[$i]['title'].'</button>';
                        break;
                    case "btn-success":
                        $htm .= '<button type="button" id="'.$topperBottons[$i]['jsf'].'-btn" onclick="'.$topperBottons[$i]['jsf'].'()" class="btn btn-success" '.(isset($topperBottons[$i]['data-dismiss']) ? '' : 'data-dismiss="modal"').' >'.$topperBottons[$i]['title'].'</button>';
                        break;
                    case "btn-danger":
                        $htm .= '<button type="button" id="'.$topperBottons[$i]['jsf'].'-btn" onclick="'.$topperBottons[$i]['jsf'].'()" class="btn btn-danger" '.(isset($topperBottons[$i]['data-dismiss']) ? '' : 'data-dismiss="modal"').' >'.$topperBottons[$i]['title'].'</button>';
                        break;
                    case "dismis":
                        $htm .= '<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">'.$topperBottons[$i]['title'].'</button>';
                        break;
                }
            }
            $htm .= '</div>';
        }
        if(strlen(trim($ShowDescription)) > 0) {
            $htm .= "<br />";
            $htm .= "<div id='".$ShowDescription."'></div>";
        }
        $htm .= '</div>';
        $htm .= '<div class="modal-footer" >';
        for($i=0;$i<count($footerBottons);$i++){
            switch($footerBottons[$i]['type']){
                case "btn":
                    $htm .= '<button type="button" id="'.$footerBottons[$i]['jsf'].'-btn" onclick="'.$footerBottons[$i]['jsf'].'()" class="btn btn-primary" '.(isset($footerBottons[$i]['data-dismiss']) ? '' : 'data-dismiss="modal"').' >'.$footerBottons[$i]['title'].'</button>';
                    break;
                case "btn-success":
                    $htm .= '<button type="button" id="'.$footerBottons[$i]['jsf'].'-btn" onclick="'.$footerBottons[$i]['jsf'].'()" class="btn btn-success" '.(isset($footerBottons[$i]['data-dismiss']) ? '' : 'data-dismiss="modal"').' >'.$footerBottons[$i]['title'].'</button>';
                    break;
                case "btn-danger":
                    $htm .= '<button type="button" id="'.$footerBottons[$i]['jsf'].'-btn" onclick="'.$footerBottons[$i]['jsf'].'()" class="btn btn-danger" '.(isset($footerBottons[$i]['data-dismiss']) ? '' : 'data-dismiss="modal"').' >'.$footerBottons[$i]['title'].'</button>';
                    break;
                case "btn-warning":
                    $htm .= '<button type="button" id="'.$footerBottons[$i]['jsf'].'-btn" onclick="'.$footerBottons[$i]['jsf'].'()" class="btn btn-warning" '.(isset($footerBottons[$i]['data-dismiss']) ? '' : 'data-dismiss="modal"').' >'.$footerBottons[$i]['title'].'</button>';
                    break;
                case "btn-info":
                    $htm .= '<button type="button" id="'.$footerBottons[$i]['jsf'].'-btn" onclick="'.$footerBottons[$i]['jsf'].'()" class="btn btn-info" '.(isset($footerBottons[$i]['data-dismiss']) ? '' : 'data-dismiss="modal"').' >'.$footerBottons[$i]['title'].'</button>';
                    break;
                case "btn-secondary":
                    $htm .= '<button type="button" id="'.$footerBottons[$i]['jsf'].'-btn" onclick="'.$footerBottons[$i]['jsf'].'()" class="btn btn-secondary" '.(isset($footerBottons[$i]['data-dismiss']) ? '' : 'data-dismiss="modal"').' >'.$footerBottons[$i]['title'].'</button>';
                    break;
                case "dismis":
                    $htm .= '<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">'.$footerBottons[$i]['title'].'</button>';
                    break;
            }
        }
        $htm .= '</div></div></div></div>';
        $htm .= '</div>';
        return $htm;
    }

    public function createExcel($hd,$content,$fieldsName,$name,$additionalFields=array(),$footerFields=array()){
        // $this->fileRecorder($hd);
        // $this->fileRecorder($content);
        // $this->fileRecorder($fieldsName);
        $tempPath = "../excelTemp/" ;
        $countHD = count($hd);
        $countContent = count($content);
        $countFN = count($fieldsName);
        if ($handle=opendir($tempPath)) {
            while (false!==($file=readdir($handle))) {
                if ($file<>"." AND $file<>"..") {
                    if (is_file($tempPath.'/'.$file))  {
                        @unlink($tempPath.'/'.$file);
                    }
                }
            }
        }
        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->getActiveSheet()->setRightToLeft(true);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $alpha = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        $countAlpha =  count($alpha);
        for($i=0;$i<$countAlpha;$i++){
            for ($j=0;$j<$countAlpha;$j++){
                $alpha[] = $alpha[$i] . $alpha[$j];
            }
        }
        $style = array('font' => array('size' => 12,'bold' => true,'color' => array('rgb' => 'ff0000')));
        for($a=0;$a<$countHD;$a++){
            $currentAlpha = $alpha[$a];
            $objPHPExcel->getActiveSheet()->getColumnDimension($currentAlpha)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($currentAlpha.'1')->applyFromArray($style);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue(''.$currentAlpha.'1', ''.$hd[$a].'');
        }

        $rowCounter = 2;
        for($c=0;$c<$countContent;$c++){
            //$alphaCounter = 0;
            for($f=0;$f<$countFN;$f++){
                $al = $alpha[$f].($rowCounter);
                $cn = $content[$c][$fieldsName[$f]];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(''.$al.'', ''.$cn.'');
            }
            $rowCounter++;
        }
        if(count($additionalFields)>0){
            $alphaCounter = 0;
            foreach($additionalFields as $param=>$val){
                $al1 = $alpha[$alphaCounter].($rowCounter);
                $alphaCounter++;
                $al2 = $alpha[$alphaCounter].($rowCounter);
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue(''.$al1.'', ''.$param.'')
                    ->setCellValue(''.$al2.'', ''.$val.'');
                $alphaCounter++;
            }
        }
        if(count($footerFields)>0){
            $alphaCounter = 0;
            foreach($footerFields as $param=>$val){
                $al1 = $alpha[$alphaCounter].($rowCounter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(''.$al1.'', ''.$val.'');
                $alphaCounter++;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $path = '/excelTemp/'.$name.'.xlsx';
        $writer = new Xlsx($objPHPExcel);
        $writer->save("..".$path);
        //return ($_SESSION['HttpHost'] == '192.168.2.20:8013' ? ADDR : ADDRR).$path;
        return ADDR.$path;
    }

    public function createBOMExcel($goods,$Pieces,$ColRow,$name){
        $tempPath = "../excelTemp/";
        $countGood = count($goods);
        $countPiece = count($Pieces);
        $countCR = count($ColRow);

        if ($handle=opendir($tempPath)) {
            while (false!==($file=readdir($handle))) {
                if ($file<>"." AND $file<>"..") {
                    if (is_file($tempPath.'/'.$file))  {
                        @unlink($tempPath.'/'.$file);
                    }
                }
            }
        }
        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->getActiveSheet()->setRightToLeft(true);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //$objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $alpha = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        $countAlpha =  count($alpha);
        for($i=0;$i<$countAlpha;$i++){
            for ($j=0;$j<$countAlpha;$j++){
                $alpha[] = $alpha[$i] . $alpha[$j];
            }
        }
        $countAlpha = count($alpha);
        for($k=26;$k<$countAlpha;$k++){
            for ($m=0;$m<26;$m++){
                $alpha[] = $alpha[$k].$alpha[$m];
            }
        }

        $style = array('font' => array('size' => 12,'bold' => true,'color' => array('rgb' => 'ff0000')));
        $style1 = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => (\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'color' => array('argb' => '00000000'),
                ),
            ),
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'a9ece9')
            )
        );
        $style2 = array('font' => array('size' => 12,'bold' => true,'color' => array('rgb' => '000000')));

        $range = 'D1:'.$alpha[$countGood+22].'1';
        $range1 = 'D2:'.$alpha[$countGood+22].'2';
        $range2 = 'A3:V3';
        $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle($range1)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle($range2)->applyFromArray($style1);

        $z = 22;
        for($x=0;$x<$countGood;$x++){
            $currentAlphaCol = $alpha[$z];
            //$objPHPExcel->getActiveSheet()->getColumnDimension($currentAlpha)->setWidth(14);
            $objPHPExcel->getActiveSheet()->getStyle($currentAlphaCol.'3')->applyFromArray($style1);
            $objPHPExcel->getActiveSheet()->getStyle($currentAlphaCol.'2')->applyFromArray((intval($goods[$x]['isEnable']) == 0 ? $style : $style2));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(intval($goods[$x]['Col']),1, ''.$goods[$x]['HCode'].'');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(intval($goods[$x]['Col']),2, ''.$goods[$x]['gName'].'');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(intval($goods[$x]['Col']),3, ''.$goods[$x]['gCode'].'');
            $z++;
        }

        for($k=0;$k<$countPiece;$k++){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(1,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['rawCode'].'')
                ->setCellValueByColumnAndRow(2,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['forgingCode'].'')
                ->setCellValueByColumnAndRow(3,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['machiningCode'].'')
                ->setCellValueByColumnAndRow(4,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['polishingCode'].'')
                ->setCellValueByColumnAndRow(5,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['nickelCode'].'')
                ->setCellValueByColumnAndRow(6,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['platingCode'].'')
                ->setCellValueByColumnAndRow(7,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['pushplatingCode'].'')
                ->setCellValueByColumnAndRow(8,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['goldenCode'].'')
                ->setCellValueByColumnAndRow(9,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['mattgoldenCode'].'')
                ->setCellValueByColumnAndRow(10,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['lightgoldenCode'].'')
                ->setCellValueByColumnAndRow(11,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['darkgoldenCode'].'')
                ->setCellValueByColumnAndRow(12,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['paintCode'].'')
                ->setCellValueByColumnAndRow(13,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['decoralCode'].'')
                ->setCellValueByColumnAndRow(14,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['steelCode'].'')
                ->setCellValueByColumnAndRow(15,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['rawppCode'].'')
                ->setCellValueByColumnAndRow(16,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['finalppCode'].'')
                ->setCellValueByColumnAndRow(17,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['pCode'].'')
                ->setCellValueByColumnAndRow(18,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['pName'].'')
                ->setCellValueByColumnAndRow(19,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['HPCode'].'')
                ->setCellValueByColumnAndRow(20,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['Custom_dimensions'].'')
                ->setCellValueByColumnAndRow(21,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['Weight_materials'].'')
                ->setCellValueByColumnAndRow(22,intval($Pieces[$k]['Row']), ''.$Pieces[$k]['pUnit'].'');
        }

        for($m=0;$m<$countCR;$m++){
            $CR = explode(',',$ColRow[$m]['col_row']);
            $col = $CR[0];
            $row = $CR[1];
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col,$row, ''.$ColRow[$m]['amount'].'');
        }

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3', 'سیاهتاب')
                    ->setCellValue('B3', 'فورج')
                    ->setCellValue('C3', 'ماشین کاری')
                    ->setCellValue('D3', 'پرداخت')
                    ->setCellValue('E3', 'نیکل خورده')
                    ->setCellValue('F3', 'آب کاری شده')
                    ->setCellValue('G3', 'آب برداری شده')
                    ->setCellValue('H3', 'طلایی')
                    ->setCellValue('I3', 'طلایی مات')
                    ->setCellValue('J3', 'طلایی روشن')
                    ->setCellValue('K3', 'طلایی تیره')
                    ->setCellValue('L3', 'رنگی')
                    ->setCellValue('M3', 'دکورال')
                    ->setCellValue('N3', 'استیل')
                    ->setCellValue('O3', 'قطعه خام تزریق پلاستیک')
                    ->setCellValue('P3', 'قطعه نهایی تزریق پلاستیک')
                    ->setCellValue('Q3', 'کد مهندسی')
                    ->setCellValue('R3', 'نام قطعه')
                    ->setCellValue('S3', 'کد قطعه')
                    ->setCellValue('T3', 'ابعاد سفارشی')
                    ->setCellValue('U3', 'وزن مواد اولیه (گرم)')
                    ->setCellValue('V3', 'واحد');

        //$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(14);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(14);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(50);

        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $path = '/excelTemp/'.$name.'.xlsx';
        $writer = new Xlsx($objPHPExcel);
        $writer->save("..".$path);
        //return ($_SESSION['HttpHost'] == '192.168.2.20:8013' ? ADDR : ADDRR).$path;
        return ADDR.$path;
    }

    public function createBOMFinancialExcel($goods,$Pieces,$ColRow,$name){
        $tempPath = "../excelTemp/";
        $countGood = count($goods);
        $countPiece = count($Pieces);
        $countCR = count($ColRow);

        if ($handle=opendir($tempPath)) {
            while (false!==($file=readdir($handle))) {
                if ($file<>"." AND $file<>"..") {
                    if (is_file($tempPath.'/'.$file))  {
                        @unlink($tempPath.'/'.$file);
                    }
                }
            }
        }
        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->getActiveSheet()->setRightToLeft(true);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //$objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $alpha = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        $countAlpha =  count($alpha);
        for($i=0;$i<$countAlpha;$i++){
            for ($j=0;$j<$countAlpha;$j++){
                $alpha[] = $alpha[$i] . $alpha[$j];
            }
        }
        $countAlpha = count($alpha);
        for($k=26;$k<$countAlpha;$k++){
            for ($m=0;$m<26;$m++){
                $alpha[] = $alpha[$k].$alpha[$m];
            }
        }

        $style = array('font' => array('size' => 12,'bold' => true,'color' => array('rgb' => 'ff0000')));
        $style1 = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => (\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'color' => array('argb' => '00000000'),
                ),
            ),
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'a9ece9')
            )
        );
        $style2 = array('font' => array('size' => 12,'bold' => true,'color' => array('rgb' => '000000')));

        $range = 'D1:'.$alpha[$countGood+22].'1';
        $range1 = 'D2:'.$alpha[$countGood+22].'2';
        $range2 = 'A3:V3';
        $objPHPExcel->getActiveSheet()->getStyle($range)->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle($range1)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle($range2)->applyFromArray($style1);

        $z = 22;
        for($x=0;$x<$countGood;$x++){
            $currentAlphaCol = $alpha[$z];
            //$objPHPExcel->getActiveSheet()->getColumnDimension($currentAlpha)->setWidth(14);
            $objPHPExcel->getActiveSheet()->getStyle($currentAlphaCol.'3')->applyFromArray($style1);
            $objPHPExcel->getActiveSheet()->getStyle($currentAlphaCol.'2')->applyFromArray((intval($goods[$x]['isEnable']) == 0 ? $style : $style2));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(intval($goods[$x]['Col']),1, ''.$goods[$x]['HCode'].'');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(intval($goods[$x]['Col']),2, ''.$goods[$x]['gName'].'');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(intval($goods[$x]['Col']),3, ''.$goods[$x]['gCode'].'');
            $z++;
        }

        $rNum = 5;
        for($k=0;$k<$countPiece;$k++){
            $motafareghe = '';
            if (intval($Pieces[$k]['Hose_timing']) > 0){
                $motafareghe = $Pieces[$k]['Hose_timing'];
            }
            if (intval($Pieces[$k]['Pipe_timing']) > 0){
                $motafareghe = $Pieces[$k]['Pipe_timing'];
            }
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValueByColumnAndRow(1,($rNum-1), ''.$Pieces[$k]['rawCode'].'')
                ->setCellValueByColumnAndRow(1,$rNum, ''.$motafareghe.'')
                ->setCellValueByColumnAndRow(2,($rNum-1), ''.$Pieces[$k]['forgingCode'].'')
                ->setCellValueByColumnAndRow(2,$rNum, ''.$Pieces[$k]['Forging_timing'].'')
                ->setCellValueByColumnAndRow(3,($rNum-1), ''.$Pieces[$k]['machiningCode'].'')
                ->setCellValueByColumnAndRow(3,$rNum, ''.$Pieces[$k]['Machining_timing'].'')
                ->setCellValueByColumnAndRow(4,($rNum-1), ''.$Pieces[$k]['polishingCode'].'')
                ->setCellValueByColumnAndRow(4,$rNum, ''.$Pieces[$k]['Polishing_timing'].'')
                ->setCellValueByColumnAndRow(5,($rNum-1), ''.$Pieces[$k]['nickelCode'].'')
                ->setCellValueByColumnAndRow(5,$rNum, '')
                ->setCellValueByColumnAndRow(6,($rNum-1), ''.$Pieces[$k]['platingCode'].'')
                ->setCellValueByColumnAndRow(6,$rNum, ''.$Pieces[$k]['Plating_timing'].'')
                ->setCellValueByColumnAndRow(7,($rNum-1), ''.$Pieces[$k]['pushplatingCode'].'')
                ->setCellValueByColumnAndRow(7,$rNum, '')
                ->setCellValueByColumnAndRow(8,($rNum-1), ''.$Pieces[$k]['goldenCode'].'')
                ->setCellValueByColumnAndRow(8,$rNum, ''.$Pieces[$k]['PVD_timing'].'')
                ->setCellValueByColumnAndRow(9,($rNum-1), ''.$Pieces[$k]['mattgoldenCode'].'')
                ->setCellValueByColumnAndRow(9,$rNum, '')
                ->setCellValueByColumnAndRow(10,($rNum-1), ''.$Pieces[$k]['lightgoldenCode'].'')
                ->setCellValueByColumnAndRow(10,$rNum, '')
                ->setCellValueByColumnAndRow(11,($rNum-1), ''.$Pieces[$k]['darkgoldenCode'].'')
                ->setCellValueByColumnAndRow(11,$rNum, '')
                ->setCellValueByColumnAndRow(12,($rNum-1), ''.$Pieces[$k]['paintCode'].'')
                ->setCellValueByColumnAndRow(12,$rNum, ''.$Pieces[$k]['Paint_timing'].'')
                ->setCellValueByColumnAndRow(13,($rNum-1), ''.$Pieces[$k]['decoralCode'].'')
                ->setCellValueByColumnAndRow(13,$rNum, '')
                ->setCellValueByColumnAndRow(14,($rNum-1), ''.$Pieces[$k]['steelCode'].'')
                ->setCellValueByColumnAndRow(14,$rNum, '')
                ->setCellValueByColumnAndRow(15,($rNum-1), ''.$Pieces[$k]['rawppCode'].'')
                ->setCellValueByColumnAndRow(15,$rNum, ''.$Pieces[$k]['PlasticInjection_timing'].'')
                ->setCellValueByColumnAndRow(16,($rNum-1), ''.$Pieces[$k]['finalppCode'].'')
                ->setCellValueByColumnAndRow(16,$rNum, '')
                ->setCellValueByColumnAndRow(17,($rNum-1), ''.$Pieces[$k]['pCode'].'')
                ->setCellValueByColumnAndRow(17,$rNum, '')
                ->setCellValueByColumnAndRow(18,($rNum-1), ''.$Pieces[$k]['pName'].'')
                ->setCellValueByColumnAndRow(18,$rNum, '')
                ->setCellValueByColumnAndRow(19,($rNum-1), ''.$Pieces[$k]['HPCode'].'')
                ->setCellValueByColumnAndRow(19,$rNum, '')
                ->setCellValueByColumnAndRow(20,($rNum-1), ''.$Pieces[$k]['Custom_dimensions'].'')
                ->setCellValueByColumnAndRow(20,$rNum, '')
                ->setCellValueByColumnAndRow(21,($rNum-1), ''.$Pieces[$k]['Weight_materials'].'')
                ->setCellValueByColumnAndRow(21,$rNum, '')
                ->setCellValueByColumnAndRow(22,($rNum-1), ''.$Pieces[$k]['pUnit'].'')
                ->setCellValueByColumnAndRow(22,$rNum, '');

            $objPHPExcel->setActiveSheetIndex(0)->mergeCells("Q".($rNum-1).":Q".$rNum);
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells("R".($rNum-1).":R".$rNum);
            $rNum += 2;
        }

        for($m=0;$m<$countCR;$m++){
            $CR = explode(',',$ColRow[$m]['col_row']);
            $col = $CR[0];
            $row = intval(($CR[1] - 4) + $CR[1] );
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($col,$row, ''.$ColRow[$m]['amount'].'');
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A3', 'سیاهتاب')
            ->setCellValue('B3', 'فورج')
            ->setCellValue('C3', 'ماشین کاری')
            ->setCellValue('D3', 'پرداخت')
            ->setCellValue('E3', 'نیکل خورده')
            ->setCellValue('F3', 'آب کاری شده')
            ->setCellValue('G3', 'آب برداری شده')
            ->setCellValue('H3', 'طلایی')
            ->setCellValue('I3', 'طلایی مات')
            ->setCellValue('J3', 'طلایی روشن')
            ->setCellValue('K3', 'طلایی تیره')
            ->setCellValue('L3', 'رنگی')
            ->setCellValue('M3', 'دکورال')
            ->setCellValue('N3', 'استیل')
            ->setCellValue('O3', 'قطعه خام تزریق پلاستیک')
            ->setCellValue('P3', 'قطعه نهایی تزریق پلاستیک')
            ->setCellValue('Q3', 'کد مهندسی')
            ->setCellValue('R3', 'نام قطعه')
            ->setCellValue('S3', 'کد قطعه')
            ->setCellValue('T3', 'ابعاد سفارشی')
            ->setCellValue('U3', 'وزن مواد اولیه (گرم)')
            ->setCellValue('V3', 'واحد');

        //$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(14);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(14);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
        //$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(50);

        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $path = '/excelTemp/'.$name.'.xlsx';
        $writer = new Xlsx($objPHPExcel);
        $writer->save("..".$path);
        //return ($_SESSION['HttpHost'] == '192.168.2.20:8013' ? ADDR : ADDRR).$path;
        return ADDR.$path;
    }

    public function getBudgetPriceManageExcel($name,$bid){
        $tempPath = "../excelTemp/";
        if ($handle=opendir($tempPath)) {
            while (false!==($file=readdir($handle))) {
                if ($file<>"." AND $file<>"..") {
                    if (is_file($tempPath.'/'.$file))  {
                        @unlink($tempPath.'/'.$file);
                    }
                }
            }
        }
        $db = new DBi();
        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->getActiveSheet()->setRightToLeft(true);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //$objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $alpha = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        $countAlpha =  count($alpha);
        for($i=0;$i<$countAlpha;$i++){
            for ($j=0;$j<$countAlpha;$j++){
                $alpha[] = $alpha[$i] . $alpha[$j];
            }
        }
        $countAlpha = count($alpha);
        for($k=26;$k<$countAlpha;$k++){
            for ($m=0;$m<26;$m++){
                $alpha[] = $alpha[$k].$alpha[$m];
            }
        }

        $style = array(
            'font' => array('size' => 12,'bold' => true,'color' => array('rgb' => '000000'),'name'  => 'B Nazanin'),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => (\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'color' => array('argb' => '00000000'),
                ),
            )
        );
        $style1 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'a9ece9')
            )
        );
        $style2 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'DDD9C4')
            )
        );
        $style3 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'CCC0DA')
            )
        );
        $style4 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => '92D050')
            )
        );
        $style5 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'FFC000')
            )
        );
        $style6 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => '8CDABA')
            )
        );
        $style7 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'CCBD00')
            )
        );
        $style8 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'FFA7FB')
            )
        );
        $style9 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'D9D9D9')
            )
        );
        $style10 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'FFC9CA')
            )
        );
        $style11 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => '00FFFF')
            )
        );
        $style12 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'FFFFE1')
            )
        );

        $objPHPExcel->getActiveSheet()->mergeCells("A1:A2");  // نام کالا
        $objPHPExcel->getActiveSheet()->mergeCells("B1:H1");  // فروردین
        $objPHPExcel->getActiveSheet()->mergeCells("I1:O1");  // اردیبهشت
        $objPHPExcel->getActiveSheet()->mergeCells("P1:V1");  // خرداد
        $objPHPExcel->getActiveSheet()->mergeCells("W1:AC1");  // تیر
        $objPHPExcel->getActiveSheet()->mergeCells("AD1:AJ1");  // مرداد
        $objPHPExcel->getActiveSheet()->mergeCells("AK1:AQ1");  // شهریور
        $objPHPExcel->getActiveSheet()->mergeCells("AR1:AX1");  // مهر
        $objPHPExcel->getActiveSheet()->mergeCells("AY1:BE1");  // آبان
        $objPHPExcel->getActiveSheet()->mergeCells("BF1:BL1");  // آذر
        $objPHPExcel->getActiveSheet()->mergeCells("BM1:BS1");  // دی
        $objPHPExcel->getActiveSheet()->mergeCells("BT1:BZ1");  // بهمن
        $objPHPExcel->getActiveSheet()->mergeCells("CA1:CG1");  // اسفند

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'نام کالا');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'فروردین');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'اردیبهشت');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'خرداد');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W1', 'تیر');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD1', 'مرداد');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK1', 'شهریور');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AR1', 'مهر');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AY1', 'آبان');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('BF1', 'آذر');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('BM1', 'دی');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('BT1', 'بهمن');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('CA1', 'اسفند');

        $x = 1;
        for ($i=0;$i<12;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'مقدار');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'قیمت لیست فروش');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'قیمت با تخفیف خرید مدت دار');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'قیمت با تخفیف نمایندگی');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'قیمت با تخفیف پرداخت نقدی');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'قیمت با تخفیف خرید لوله و اتصالات با هم');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'قیمت با تخفیف پایان دوره');
            $x++;
        }

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);

        $sql = "SELECT `RowID` FROM `budget` WHERE `year`={$bid}";
        $res = $db->ArrayQuery($sql);

        $sql1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$res[0]['RowID']} AND `finalTick`=1";
        $res1 = $db->ArrayQuery($sql1);
        $ccnt = count($res1);
        $rids = array();
        for ($i=0;$i<$ccnt;$i++){
            $rids[] = $res1[$i]['RowID'];
        }
        $rids = implode(',',$rids);

        $sql2 = "SELECT `budget_components_details`.*,`salesListPrice` FROM `budget_components_details` INNER JOIN `good` ON (`budget_components_details`.`goodID`=`good`.`RowID`) WHERE `bcid` IN ({$rids}) AND `finalTick`=1";
        $res2 = $db->ArrayQuery($sql2);
        $cnt = count($res2) + 3;
        $cnt8 = count($res2);

        $farvardinNum = 0;
        $ordibeheshtNum = 0;
        $khordadNum = 0;
        $tirNum = 0;
        $mordadNum = 0;
        $shahrivarNum = 0;
        $mehrNum = 0;
        $abanNum = 0;
        $azarNum = 0;
        $deyNum = 0;
        $bahmanNum = 0;
        $esfandNum = 0;

        $price1farvardin = 0;
        $price1ordibehesht = 0;
        $price1khordad = 0;
        $price1tir = 0;
        $price1mordad = 0;
        $price1shahrivar = 0;
        $price1mehr = 0;
        $price1aban = 0;
        $price1azar = 0;
        $price1dey = 0;
        $price1bahman = 0;
        $price1esfand = 0;

        $price2farvardin = 0;
        $price2ordibehesht = 0;
        $price2khordad = 0;
        $price2tir = 0;
        $price2mordad = 0;
        $price2shahrivar = 0;
        $price2mehr = 0;
        $price2aban = 0;
        $price2azar = 0;
        $price2dey = 0;
        $price2bahman = 0;
        $price2esfand = 0;

        $price3farvardin = 0;
        $price3ordibehesht = 0;
        $price3khordad = 0;
        $price3tir = 0;
        $price3mordad = 0;
        $price3shahrivar = 0;
        $price3mehr = 0;
        $price3aban = 0;
        $price3azar = 0;
        $price3dey = 0;
        $price3bahman = 0;
        $price3esfand = 0;

        $price4farvardin = 0;
        $price4ordibehesht = 0;
        $price4khordad = 0;
        $price4tir = 0;
        $price4mordad = 0;
        $price4shahrivar = 0;
        $price4mehr = 0;
        $price4aban = 0;
        $price4azar = 0;
        $price4dey = 0;
        $price4bahman = 0;
        $price4esfand = 0;

        $price5farvardin = 0;
        $price5ordibehesht = 0;
        $price5khordad = 0;
        $price5tir = 0;
        $price5mordad = 0;
        $price5shahrivar = 0;
        $price5mehr = 0;
        $price5aban = 0;
        $price5azar = 0;
        $price5dey = 0;
        $price5bahman = 0;
        $price5esfand = 0;

        $price6farvardin = 0;
        $price6ordibehesht = 0;
        $price6khordad = 0;
        $price6tir = 0;
        $price6mordad = 0;
        $price6shahrivar = 0;
        $price6mehr = 0;
        $price6aban = 0;
        $price6azar = 0;
        $price6dey = 0;
        $price6bahman = 0;
        $price6esfand = 0;

        for ($i=0;$i<$cnt8;$i++){
            $counter = $i+3;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$counter, $res2[$i]['gName']);

            $sqq = "SELECT `perDiscount1`,`perDiscount2`,`perDiscount3`,`perDiscount4`,`perDiscount5`,`Priority1`,`Priority2`,`Priority3`,`Priority4`,`Priority5` FROM `discounts` WHERE `brand`='{$res2[$i]['brand']}' AND `subGroup`='{$res2[$i]['ggroup']}'";
            $rsq = $db->ArrayQuery($sqq);

            $Priority = array();
            if (count($rsq) > 0) {
                $Priority = array($rsq[0]['Priority1'], $rsq[0]['perDiscount1'], $rsq[0]['Priority2'], $rsq[0]['perDiscount2'], $rsq[0]['Priority3'], $rsq[0]['perDiscount3'], $rsq[0]['Priority4'], $rsq[0]['perDiscount4'], $rsq[0]['Priority5'], $rsq[0]['perDiscount5']);
                $x = 0;
                for ($j = 0; $j < 5; $j++) {
                    switch ($Priority[$x]) {
                        case 1:
                            $t1 = $Priority[$x + 1] / 100;
                            break;
                        case 2:
                            $t2 = $Priority[$x + 1] / 100;
                            break;
                        case 3:
                            $t3 = $Priority[$x + 1] / 100;
                            break;
                        case 4:
                            $t4 = $Priority[$x + 1] / 100;
                            break;
                        case 5:
                            $t5 = $Priority[$x + 1] / 100;
                            break;
                    }
                    $x += 2;
                }

                $pt1 = $res2[$i]['salesListPrice'] * (1 - $t1);
                $pt2 = $pt1 * (1 - $t2);
                $pt3 = $pt2 * (1 - $t3);
                $pt4 = $pt3 * (1 - $t4);
                $pt5 = $pt4 * (1 - $t5);

                $x = 0;
                for ($j = 0; $j < 5; $j++) {
                    switch ($Priority[$x]) {
                        case 1:
                            $Priority[$x + 1] = $pt1;
                            break;
                        case 2:
                            $Priority[$x + 1] = $pt2;
                            break;
                        case 3:
                            $Priority[$x + 1] = $pt3;
                            break;
                        case 4:
                            $Priority[$x + 1] = $pt4;
                            break;
                        case 5:
                            $Priority[$x + 1] = $pt5;
                            break;
                    }
                    $x += 2;
                }
            }else{
                $Priority[1] = 0;
                $Priority[3] = 0;
                $Priority[5] = 0;
                $Priority[7] = 0;
                $Priority[9] = 0;
            }

            $totalArray = array();

            for ($j=1;$j<13;$j++) {
                $query = "SELECT SUM(`number`) AS `bNum` FROM `budget_product_entry` WHERE `bid`={$res[0]['RowID']} AND `bcdid`={$res2[$i]['RowID']} AND `month`={$j}";
                $rst = $db->ArrayQuery($query);
                switch (intval($j)){
                    case 1:
                        $baceNumber = (intval($res2[$i]['finalTick']) == 1 ? intval($res2[$i]['farvardinTotal']) : intval($res2[$i]['farvardin']));
                        $farvardinNum += $rst[0]['bNum'] + $baceNumber;
                        $price1farvardin += $res2[$i]['salesListPrice'] * ($rst[0]['bNum'] + $baceNumber);
                        $price2farvardin += $Priority[1] * ($rst[0]['bNum'] + $baceNumber);
                        $price3farvardin += $Priority[3] * ($rst[0]['bNum'] + $baceNumber);
                        $price4farvardin += $Priority[5] * ($rst[0]['bNum'] + $baceNumber);
                        $price5farvardin += $Priority[7] * ($rst[0]['bNum'] + $baceNumber);
                        $price6farvardin += $Priority[9] * ($rst[0]['bNum'] + $baceNumber);
                        break;
                    case 2:
                        $baceNumber = (intval($res2[$i]['finalTick']) == 1 ? intval($res2[$i]['ordibeheshtTotal']) : intval($res2[$i]['ordibehesht']));
                        $ordibeheshtNum += $rst[0]['bNum'] + $baceNumber;
                        $price1ordibehesht += $res2[$i]['salesListPrice'] * ($rst[0]['bNum'] + $baceNumber);
                        $price2ordibehesht += $Priority[1] * ($rst[0]['bNum'] + $baceNumber);
                        $price3ordibehesht += $Priority[3] * ($rst[0]['bNum'] + $baceNumber);
                        $price4ordibehesht += $Priority[5] * ($rst[0]['bNum'] + $baceNumber);
                        $price5ordibehesht += $Priority[7] * ($rst[0]['bNum'] + $baceNumber);
                        $price6ordibehesht += $Priority[9] * ($rst[0]['bNum'] + $baceNumber);
                        break;
                    case 3:
                        $baceNumber = (intval($res2[$i]['finalTick']) == 1 ? intval($res2[$i]['khordadTotal']) : intval($res2[$i]['khordad']));
                        $khordadNum += $rst[0]['bNum'] + $baceNumber;
                        $price1khordad += $res2[$i]['salesListPrice'] * ($rst[0]['bNum'] + $baceNumber);
                        $price2khordad += $Priority[1] * ($rst[0]['bNum'] + $baceNumber);
                        $price3khordad += $Priority[3] * ($rst[0]['bNum'] + $baceNumber);
                        $price4khordad += $Priority[5] * ($rst[0]['bNum'] + $baceNumber);
                        $price5khordad += $Priority[7] * ($rst[0]['bNum'] + $baceNumber);
                        $price6khordad += $Priority[9] * ($rst[0]['bNum'] + $baceNumber);
                        break;
                    case 4:
                        $baceNumber = (intval($res2[$i]['finalTick']) == 1 ? intval($res2[$i]['tirTotal']) : intval($res2[$i]['tir']));
                        $tirNum += $rst[0]['bNum'] + $baceNumber;
                        $price1tir += $res2[$i]['salesListPrice'] * ($rst[0]['bNum'] + $baceNumber);
                        $price2tir += $Priority[1] * ($rst[0]['bNum'] + $baceNumber);
                        $price3tir += $Priority[3] * ($rst[0]['bNum'] + $baceNumber);
                        $price4tir += $Priority[5] * ($rst[0]['bNum'] + $baceNumber);
                        $price5tir += $Priority[7] * ($rst[0]['bNum'] + $baceNumber);
                        $price6tir += $Priority[9] * ($rst[0]['bNum'] + $baceNumber);
                        break;
                    case 5:
                        $baceNumber = (intval($res2[$i]['finalTick']) == 1 ? intval($res2[$i]['mordadTotal']) : intval($res2[$i]['mordad']));
                        $mordadNum += $rst[0]['bNum'] + $baceNumber;
                        $price1mordad += $res2[$i]['salesListPrice'] * ($rst[0]['bNum'] + $baceNumber);
                        $price2mordad += $Priority[1] * ($rst[0]['bNum'] + $baceNumber);
                        $price3mordad += $Priority[3] * ($rst[0]['bNum'] + $baceNumber);
                        $price4mordad += $Priority[5] * ($rst[0]['bNum'] + $baceNumber);
                        $price5mordad += $Priority[7] * ($rst[0]['bNum'] + $baceNumber);
                        $price6mordad += $Priority[9] * ($rst[0]['bNum'] + $baceNumber);
                        break;
                    case 6:
                        $baceNumber = (intval($res2[$i]['finalTick']) == 1 ? intval($res2[$i]['shahrivarTotal']) : intval($res2[$i]['shahrivar']));
                        $shahrivarNum += $rst[0]['bNum'] + $baceNumber;
                        $price1shahrivar += $res2[$i]['salesListPrice'] * ($rst[0]['bNum'] + $baceNumber);
                        $price2shahrivar += $Priority[1] * ($rst[0]['bNum'] + $baceNumber);
                        $price3shahrivar += $Priority[3] * ($rst[0]['bNum'] + $baceNumber);
                        $price4shahrivar += $Priority[5] * ($rst[0]['bNum'] + $baceNumber);
                        $price5shahrivar += $Priority[7] * ($rst[0]['bNum'] + $baceNumber);
                        $price6shahrivar += $Priority[9] * ($rst[0]['bNum'] + $baceNumber);
                        break;
                    case 7:
                        $baceNumber = (intval($res2[$i]['finalTick']) == 1 ? intval($res2[$i]['mehrTotal']) : intval($res2[$i]['mehr']));
                        $mehrNum += $rst[0]['bNum'] + $baceNumber;
                        $price1mehr += $res2[$i]['salesListPrice'] * ($rst[0]['bNum'] + $baceNumber);
                        $price2mehr += $Priority[1] * ($rst[0]['bNum'] + $baceNumber);
                        $price3mehr += $Priority[3] * ($rst[0]['bNum'] + $baceNumber);
                        $price4mehr += $Priority[5] * ($rst[0]['bNum'] + $baceNumber);
                        $price5mehr += $Priority[7] * ($rst[0]['bNum'] + $baceNumber);
                        $price6mehr += $Priority[9] * ($rst[0]['bNum'] + $baceNumber);
                        break;
                    case 8:
                        $baceNumber = (intval($res2[$i]['finalTick']) == 1 ? intval($res2[$i]['abanTotal']) : intval($res2[$i]['aban']));
                        $abanNum += $rst[0]['bNum'] + $baceNumber;
                        $price1aban += $res2[$i]['salesListPrice'] * ($rst[0]['bNum'] + $baceNumber);
                        $price2aban += $Priority[1] * ($rst[0]['bNum'] + $baceNumber);
                        $price3aban += $Priority[3] * ($rst[0]['bNum'] + $baceNumber);
                        $price4aban += $Priority[5] * ($rst[0]['bNum'] + $baceNumber);
                        $price5aban += $Priority[7] * ($rst[0]['bNum'] + $baceNumber);
                        $price6aban += $Priority[9] * ($rst[0]['bNum'] + $baceNumber);
                        break;
                    case 9:
                        $baceNumber = (intval($res2[$i]['finalTick']) == 1 ? intval($res2[$i]['azarTotal']) : intval($res2[$i]['azar']));
                        $azarNum += $rst[0]['bNum'] + $baceNumber;
                        $price1azar += $res2[$i]['salesListPrice'] * ($rst[0]['bNum'] + $baceNumber);
                        $price2azar += $Priority[1] * ($rst[0]['bNum'] + $baceNumber);
                        $price3azar += $Priority[3] * ($rst[0]['bNum'] + $baceNumber);
                        $price4azar += $Priority[5] * ($rst[0]['bNum'] + $baceNumber);
                        $price5azar += $Priority[7] * ($rst[0]['bNum'] + $baceNumber);
                        $price6azar += $Priority[9] * ($rst[0]['bNum'] + $baceNumber);
                        break;
                    case 10:
                        $baceNumber = (intval($res2[$i]['finalTick']) == 1 ? intval($res2[$i]['deyTotal']) : intval($res2[$i]['dey']));
                        $deyNum += $rst[0]['bNum'] + $baceNumber;
                        $price1dey += $res2[$i]['salesListPrice'] * ($rst[0]['bNum'] + $baceNumber);
                        $price2dey += $Priority[1] * ($rst[0]['bNum'] + $baceNumber);
                        $price3dey += $Priority[3] * ($rst[0]['bNum'] + $baceNumber);
                        $price4dey += $Priority[5] * ($rst[0]['bNum'] + $baceNumber);
                        $price5dey += $Priority[7] * ($rst[0]['bNum'] + $baceNumber);
                        $price6dey += $Priority[9] * ($rst[0]['bNum'] + $baceNumber);
                        break;
                    case 11:
                        $baceNumber = (intval($res2[$i]['finalTick']) == 1 ? intval($res2[$i]['bahmanTotal']) : intval($res2[$i]['bahman']));
                        $bahmanNum += $rst[0]['bNum'] + $baceNumber;
                        $price1bahman += $res2[$i]['salesListPrice'] * ($rst[0]['bNum'] + $baceNumber);
                        $price2bahman += $Priority[1] * ($rst[0]['bNum'] + $baceNumber);
                        $price3bahman += $Priority[3] * ($rst[0]['bNum'] + $baceNumber);
                        $price4bahman += $Priority[5] * ($rst[0]['bNum'] + $baceNumber);
                        $price5bahman += $Priority[7] * ($rst[0]['bNum'] + $baceNumber);
                        $price6bahman += $Priority[9] * ($rst[0]['bNum'] + $baceNumber);
                        break;
                    case 12:
                        $baceNumber = (intval($res2[$i]['finalTick']) == 1 ? intval($res2[$i]['esfandTotal']) : intval($res2[$i]['esfand']));
                        $esfandNum += $rst[0]['bNum'] + $baceNumber;
                        $price1esfand += $res2[$i]['salesListPrice'] * ($rst[0]['bNum'] + $baceNumber);
                        $price2esfand += $Priority[1] * ($rst[0]['bNum'] + $baceNumber);
                        $price3esfand += $Priority[3] * ($rst[0]['bNum'] + $baceNumber);
                        $price4esfand += $Priority[5] * ($rst[0]['bNum'] + $baceNumber);
                        $price5esfand += $Priority[7] * ($rst[0]['bNum'] + $baceNumber);
                        $price6esfand += $Priority[9] * ($rst[0]['bNum'] + $baceNumber);
                        break;
                }
                $tNumber = $rst[0]['bNum'] + $baceNumber;
                $totalArray[] = $tNumber;
                $totalArray[] = number_format($res2[$i]['salesListPrice'] * $tNumber);
                $totalArray[] = number_format($Priority[1] * $tNumber);
                $totalArray[] = number_format($Priority[3] * $tNumber);
                $totalArray[] = number_format($Priority[5] * $tNumber);
                $totalArray[] = number_format($Priority[7] * $tNumber);
                $totalArray[] = number_format($Priority[9] * $tNumber);
            }

            $cntTotal = count($totalArray);
            $e = 1;
            for ($m=0;$m<$cntTotal;$m++){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$e].$counter, $totalArray[$m]);
                $e++;
            }
        }

        $monthNums = array($farvardinNum,$ordibeheshtNum,$khordadNum,$tirNum,$mordadNum,$shahrivarNum,$mehrNum,$abanNum,$azarNum,$deyNum,$bahmanNum,$esfandNum);
        $onePrices = array($price1farvardin,$price1ordibehesht,$price1khordad,$price1tir,$price1mordad,$price1shahrivar,$price1mehr,$price1aban,$price1azar,$price1dey,$price1bahman,$price1esfand);
        $twoPrices = array($price2farvardin,$price2ordibehesht,$price2khordad,$price2tir,$price2mordad,$price2shahrivar,$price2mehr,$price2aban,$price2azar,$price2dey,$price2bahman,$price2esfand);
        $threePrices = array($price3farvardin,$price3ordibehesht,$price3khordad,$price3tir,$price3mordad,$price3shahrivar,$price3mehr,$price3aban,$price3azar,$price3dey,$price3bahman,$price3esfand);
        $fourPrices = array($price4farvardin,$price4ordibehesht,$price4khordad,$price4tir,$price4mordad,$price4shahrivar,$price4mehr,$price4aban,$price4azar,$price4dey,$price4bahman,$price4esfand);
        $fivePrices = array($price5farvardin,$price5ordibehesht,$price5khordad,$price5tir,$price5mordad,$price5shahrivar,$price5mehr,$price5aban,$price5azar,$price5dey,$price5bahman,$price5esfand);
        $sixPrices = array($price6farvardin,$price6ordibehesht,$price6khordad,$price6tir,$price6mordad,$price6shahrivar,$price6mehr,$price6aban,$price6azar,$price6dey,$price6bahman,$price6esfand);

        $width1 = array('B','I','P','W','AD','Ak','AR','AY','BF','BM','BT','CA');
        $width2 = array('C','J','Q','X','AE','AL','AS','AZ','BG','BN','BU','CB');
        $width3 = array('D','K','R','Y','AF','AM','AT','BA','BH','BO','BV','CC');
        $width4 = array('E','L','S','Z','AG','AN','AU','BB','BI','BP','BW','CD');
        $width5 = array('F','M','T','AA','AH','AO','AV','BC','BJ','BQ','BX','CE');
        $width6 = array('G','N','U','AB','AI','AP','AW','BD','BK','BR','BY','CF');
        $width7 = array('H','O','V','AC','AJ','AQ','AX','BE','BL','BS','BZ','CG');

        $cnt1 = count($width1);
        $cnt2 = count($width2);
        $cnt3 = count($width3);
        $cnt4 = count($width4);
        $cnt5 = count($width5);
        $cnt6 = count($width6);
        $cnt7 = count($width7);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$cnt,'جمع کل');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.($cnt+1),'جمع کل سال');
        for ($i=0;$i<$cnt1;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width1[$i])->setWidth(14);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($width1[$i].$cnt,$monthNums[$i]);
        }
        for ($i=0;$i<$cnt2;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width2[$i])->setWidth(25);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($width2[$i].$cnt,$onePrices[$i]);
        }
        for ($i=0;$i<$cnt3;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width3[$i])->setWidth(25);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($width3[$i].$cnt,$twoPrices[$i]);
        }
        for ($i=0;$i<$cnt4;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width4[$i])->setWidth(25);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($width4[$i].$cnt,$threePrices[$i]);
        }
        for ($i=0;$i<$cnt5;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width5[$i])->setWidth(25);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($width5[$i].$cnt,$fourPrices[$i]);
        }
        for ($i=0;$i<$cnt6;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width6[$i])->setWidth(40);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($width6[$i].$cnt,$fivePrices[$i]);
        }
        for ($i=0;$i<$cnt7;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width7[$i])->setWidth(25);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($width7[$i].$cnt,$sixPrices[$i]);
        }

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.($cnt+1),array_sum($monthNums));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($cnt+1),array_sum($onePrices));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.($cnt+1),array_sum($twoPrices));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.($cnt+1),array_sum($threePrices));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.($cnt+1),array_sum($fourPrices));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($cnt+1),array_sum($fivePrices));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.($cnt+1),array_sum($sixPrices));

        $objPHPExcel->getActiveSheet()->getStyle('A1:CG'.($cnt+1))->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('B1:H'.($cnt+1))->applyFromArray($style1);
        $objPHPExcel->getActiveSheet()->getStyle('I1:O'.($cnt+1))->applyFromArray($style2);
        $objPHPExcel->getActiveSheet()->getStyle('P1:V'.($cnt+1))->applyFromArray($style3);
        $objPHPExcel->getActiveSheet()->getStyle('W1:AC'.($cnt+1))->applyFromArray($style4);
        $objPHPExcel->getActiveSheet()->getStyle('AD1:AJ'.($cnt+1))->applyFromArray($style5);
        $objPHPExcel->getActiveSheet()->getStyle('AK1:AQ'.($cnt+1))->applyFromArray($style6);
        $objPHPExcel->getActiveSheet()->getStyle('AR1:AX'.($cnt+1))->applyFromArray($style7);
        $objPHPExcel->getActiveSheet()->getStyle('AY1:BE'.($cnt+1))->applyFromArray($style8);
        $objPHPExcel->getActiveSheet()->getStyle('BF1:BL'.($cnt+1))->applyFromArray($style9);
        $objPHPExcel->getActiveSheet()->getStyle('BM1:BS'.($cnt+1))->applyFromArray($style10);
        $objPHPExcel->getActiveSheet()->getStyle('BT1:BZ'.($cnt+1))->applyFromArray($style11);
        $objPHPExcel->getActiveSheet()->getStyle('CA1:CG'.($cnt+1))->applyFromArray($style12);

        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $path = '/excelTemp/'.$name.'.xlsx';
        $writer = new Xlsx($objPHPExcel);
        $writer->save("..".$path);
        //return ($_SESSION['HttpHost'] == '192.168.2.20:8013' ? ADDR : ADDRR).$path;
        return ADDR.$path;
    }
    
	//*********************************************************************
    
	//***************************************************************************************************
public function createBOMFinancialExcelReport($goods,$name){
    $tempPath = "../excelTemp/";
   
    if ($handle=opendir($tempPath)) {
      
        while (false!==($file=readdir($handle))) {
            if ($file<>"." AND $file<>"..") {
                $this->fileRecorder($tempPath.'/'.$file);
                if (is_file($tempPath.'/'.$file))  {

                    //@unlink($tempPath.'/'.$name);
                    @unlink($tempPath.'/'.$file);
                }
            }
        }
    }
    $this->fileRecorder('goods');
    $this->fileRecorder($goods);
    $db = new DBi();
    $objPHPExcel = new Spreadsheet();
    $objPHPExcel->getActiveSheet()->setRightToLeft(true);
    $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    //$objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");
    $alpha = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    $countAlpha =  count($alpha);
    for($i=0;$i<$countAlpha;$i++){
        for ($j=0;$j<$countAlpha;$j++){
            $alpha[] = $alpha[$i] . $alpha[$j];
        }
    }
    $countAlpha = count($alpha);
    for($k=26;$k<$countAlpha;$k++){
        for ($m=0;$m<26;$m++){
            $alpha[] = $alpha[$k].$alpha[$m];
        }
    }
    $style = array(
        'font' => array('size' => 12,'bold' => true,'color' => array('rgb' => '000000'),'name'  => 'B Nazanin'),
        'borders' => array(
            'allBorders' => array(
                'borderStyle' => (\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                'color' => array('argb' => '00000000'),
            ),
        )
    );
    $style1 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'a9ece9')
        )
    );
    $style2 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'DDD9C4')
        )
    );
    $style3 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'CCC0DA')
        )
    );
    $style4 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => '92D050')
        )
    );
    $style5 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'FFC000')
        )
    );
    $style6 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => '8CDABA')
        )
    );
    $style7 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'CCBD00')
        )
    );
    $style8 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'FFA7FB')
        )
    );
    $style9 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'D9D9D9')
        )
    );
    $style10 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'FFC9CA')
        )
    );
    $style11 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => '00FFFF')
        )
    );
    $style12 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'FFFFE1')
        )
    );
    $cnt=count($goods);
    // انبــار	نام انبار	كـد كـالا	نام كـالا	واحد كـالا	کد قطعه	نام قطعه	گروه 	واحد	 ضریب  مصرف 

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ردیف');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'کد انبار');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'نام انبار');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', ' کد کالا');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'نام کالا');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'واحد کالا');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'کد قطعه');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'نام قطعه');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'واحد قطعه');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'گروه');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'وزن نهایی');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'متریال');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'اولین مرحله ساخت');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'ضریب مصرف');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', ' کد وضعیت');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', ' شرح وضعیت');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', '  کد مهندسی');
    for ($i=0;$i<$cnt;$i++)
    {
        $pieceCode="";
        $amount=0;
        switch($goods[$i]['M']){
            case 3:
            case 6:
            case 7:
            case 12:
            $pieceCode = $goods[$i]['F'];
               $amount = $goods[$i]['V'];
                if(empty($amount) || $amount==0){
                   
                   $amount=$goods[$i]['U'];
                  
                }
                if(empty($amount) || $amount==0){
                    $amount=$goods[$i]['Weight_materials'];
                   
                }
                if(empty($amount) || $amount==0){
                    $amount=$goods[$i]['amount'];
                    
                }
                $final_amount= round($amount*$goods[$i]['amount'],3);
                break;
                case 0:
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                case 13:
                    $pieceCode=$goods[$i]['p_code'];
                    $final_amount= $goods[$i]['amount'];

            default:
                $pieceCode=$goods[$i]['p_code'];
                $amount=$goods[$i]['amount'];
                
                break;
        }
 
        
       // $this->fileRecorder('amount:'.$amount."**6");
        $pieceCode=!empty($goods[$i]['p_code'])?$goods[$i]['p_code']:$goods[$i]['F'];
        $counter = $i+2;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$counter, ($i+1));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 3);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'انبار محصول');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$counter, $goods[$i]['g_code']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$counter, $goods[$i]['gName']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$counter, '');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$counter, $pieceCode);//$goods[$i]['p_code']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$counter, $goods[$i]['P']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$counter, $goods[$i]['K']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$counter, $goods[$i]['ggroup']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$counter, round($amount,6));//$goods[$i]['Weight_Final']
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$counter, $goods[$i]['material']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$counter, $goods[$i]['first_stage_construction']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$counter, round($final_amount,6));
        // $this->fileRecorder('amount:'.$amount."****6");
        // $this->fileRecorder('Code:'.$goods[$i]['A']."****6");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$counter, $goods[$i]['M']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$counter, $goods[$i]['N']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$counter, $goods[$i]['A']);
    
    }
    $objPHPExcel->getActiveSheet()->setTitle('ProductBom');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $path = '/excelTemp/'.$name.'.xlsx';
        
        $writer = new Xlsx($objPHPExcel);
        $writer->save("..".$path);
        
        //return ($_SESSION['HttpHost'] == '192.168.2.20:8013' ? ADDR : ADDRR).$path;
        return ADDR.$path;

}

//---------------------------------------------------------------------------------------------------
public function getPieceForGeneralExcelBOM($piece,$name){
    $tempPath = "../excelTemp/";
    if ($handle=opendir($tempPath)) {
        while (false!==($file=readdir($handle))) {
            if ($file<>"." AND $file<>"..") {
               
                if (is_file($tempPath.'/'.$file))  {
                    //@unlink($tempPath.'/'.$name);
                    @unlink($tempPath.'/'.$file);
                }
            }
        }
    }
    $db = new DBi();
    $objPHPExcel = new Spreadsheet();
    $objPHPExcel->getActiveSheet()->setRightToLeft(true);
    $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    //$objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");
    $alpha = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    $countAlpha =  count($alpha);
    for($i=0;$i<$countAlpha;$i++){
        for ($j=0;$j<$countAlpha;$j++){
            $alpha[] = $alpha[$i] . $alpha[$j];
        }
    }
    $countAlpha = count($alpha);
    for($k=26;$k<$countAlpha;$k++){
        for ($m=0;$m<26;$m++){
            $alpha[] = $alpha[$k].$alpha[$m];
        }
    }
    $style = array(
        'font' => array('size' => 12,'bold' => true,'color' => array('rgb' => '000000'),'name'  => 'B Nazanin'),
        'borders' => array(
            'allBorders' => array(
                'borderStyle' => (\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                'color' => array('argb' => '00000000'),
            ),
        )
    );
    $style1 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'a9ece9')
        )
    );
    $style2 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'DDD9C4')
        )
    );
    $style3 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'CCC0DA')
        )
    );
    $style4 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => '92D050')
        )
    );
    $style5 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'FFC000')
        )
    );
    $style6 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => '8CDABA')
        )
    );
    $style7 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'CCBD00')
        )
    );
    $style8 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'FFA7FB')
        )
    );
    $style9 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'D9D9D9')
        )
    );
    $style10 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'FFC9CA')
        )
    );
    $style11 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => '00FFFF')
        )
    );
    $style12 = array(
        'fill' => array(
            'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
            'startColor' => array('argb' => 'FFFFE1')
        )
    );
    $cnt=count($piece);
    // انبــار	نام انبار	كـد كـالا	نام كـالا	واحد كـالا	کد قطعه	نام قطعه	گروه 	واحد	 ضریب  مصرف 

//    

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ردیف');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'کد انبار');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'نام انبار');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'کد قطعه');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'نام قطعه');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'واحد قطعه');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'گروه');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'وزن متریال');
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'وزن ماشینکاری');
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'وزن سیستمی');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'وزن نهایی');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'متریال');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'کد ماده اولیه');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'اولین مرحله ساخت');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'ضریب مصرف');

for ($i=0;$i<$cnt;$i++)
{
    if(!empty($piece[$i]['Weight_Final'])){


    $counter = $i+2;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$counter, ($i+1));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$counter, 5);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$counter, 'انبار پیش ساخته');
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$counter, $piece[$i]['pCode']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$counter, $piece[$i]['Subset_name']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$counter, $piece[$i]['p_unit']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$counter, $piece[$i]['group']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$counter, $piece[$i]['Weight_materials']);
 //   $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$counter, $piece[$i]['Weight_Machining']);
  //  $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$counter, $piece[$i]['System_weight']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$counter, $piece[$i]['Weight_Final']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$counter, $piece[$i]['material']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$counter, $piece[$i]['RawMaterialCode']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$counter, $piece[$i]['first_stage_construction']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$counter, $piece[$i]['Weight_Final']);
    }

}


    $objPHPExcel->getActiveSheet()->setTitle('PieceBom');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $path = '/excelTemp/'.$name.'.xlsx';
        
        $writer = new Xlsx($objPHPExcel);
        $writer->save("..".$path);
        
        //return ($_SESSION['HttpHost'] == '192.168.2.20:8013' ? ADDR : ADDRR).$path;
        return ADDR.$path;
    
    }
    
//---------------------------------------------------------------------------------------------------
public function getFinalBudgetManageExcel($name,$bid){
 
    $tempPath = "../excelTemp/";
    if ($handle=opendir($tempPath)) {
        while (false!==($file=readdir($handle))) {
            if ($file<>"." AND $file<>"..") {
                if (is_file($tempPath.'/'.$file))  {
                    @unlink($tempPath.'/'.$file);
                }
            }
        }
    }
    $db = new DBi();
    $objPHPExcel = new Spreadsheet();
    $month_array=["اسفند","بهمن","دی","آذر","آبان","مهر","شهریور","مرداد","تیر","خرداد","اردیبهشت","فروردین"];
   // $month_array=["فروردین","اردیبهشت","خرداد","تیر","مرداد","شهریور","مهر","آبان","آذر","دی","بهمن","فروردین"];
    //$excelSheetTitles=["کد مهندسی","کد کالا","نام کالا","بودجه فروش اولیه","مقدار خارج از برنامه","جابجایی","  تاخیر در تولید"," اصلاحیه بودجه","مقدار کل تحقق یافته","مقدار کل تحقق نیافته"," درصد تحقق بودجه نهایی","درصد انحراف از بودجه اولیه"];
    $excelSheetTitles=["کد مهندسی","کد کالا","نام کالا","بودجه فروش اولیه","مقدار خارج از برنامه","جابجایی","  تاخیر در تولید"," اصلاحیه بودجه","مقدار بودجه نهایی","مقدار کل تحقق یافته","مقدار کل تحقق نیافته"," درصد تحقق بودجه نهایی","درصد انحراف از بودجه اولیه","درصد انحراف از بودجه نهایی"];
	$alpha = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    //-----------------------------------------------------------------get out of program ------------------------------------------------
    $sql="SELECT sum(number) as number, month,bcdid FROM budget_out_program where bid=1  group by bcdid,month";
    $out_res=$db->ArrayQuery($sql);
    $out_array=[];
    foreach($out_res as $k=>$v){
        $out_array[$v['bcdid']][$v['month']]=$v['number'];
    }
    //-----------------------------------------------------------------get out of program ------------------------------------------------
   //-----------------------------------------------------------------get budget_displacement ------------------------------------------------
   $sql_dis="SELECT sum(number) as number,bcdid,fromYear,fromMonth,toMonth
     FROM `budget_displacement` where finalTick=1 AND fromYear=1 GROUP BY bcdid,fromMonth";
   $dis_res=$db->ArrayQuery($sql_dis);
   $dis_from_array=[];
   $dis_to_array=[];
   foreach($dis_res as $key=>$v_d){
       $dis_from_array[$v_d['bcdid']][$v_d['fromMonth']]=$v_d['number'];
       $dis_to_array[$v_d['bcdid']][$v_d['toMonth']]=$v_d['number'];
   }
   //----------------------------------------------------get budget_displacement------------------------------------------------
   //----------------------------------------------------------------- budget delay--------------------------------------
   $delay_qry="SELECT bcdid,fromMonth,toMonth,number,finalTick FROM budget_delay";
   $delay_res=$db->ArrayQuery($delay_qry);
   foreach($delay_res as $key_delay=>$v_delay){
       $delay_from_array[$v_delay['bcdid']][$v_delay['fromMonth']]=$v_delay['number'];
       $delay_to_array[$v_delay['bcdid']][$v_delay['toMonth']]=$v_delay['number'];
   }
   //----------------------------------------------------------------- budget delay--------------------------------------
   //----------------------------------------------------------------- budget_product_entry--------------------------------------
   $bud_entry="SELECT bcdid,month,sum(number) as number,stock,remaining FROM budget_product_entry WHERE bid=1 GROUP BY bcdid,month";
   $entry_res=$db->ArrayQuery($bud_entry);
   foreach($entry_res as $key_entry=>$v_entry){
       $budget_entry_array[$v_entry['bcdid']][$v_entry['month']]=$v_entry['number'];
   }
  // //$this->fileRecorder($budget_entry_array);
   //----------------------------------------------------------------- budget delay--------------------------------------
   //----------------------------------------------------------------- budget_amendment--------------------------------------
   $bud_amend="SELECT bcdid,month,sum(number) as number FROM budget_amendment WHERE bid=1 AND finalTick=1 GROUP BY bcdid,month";
   $amend_res=$db->ArrayQuery($bud_amend);
   foreach($amend_res as $key_amend=>$v_amend){
       $budget_amend_array[$v_amend['bcdid']][$v_amend['month']]=$v_amend['number'];
   }
   //$this->fileRecorder($budget_amend_array);
   //----------------------------------------------------------------- budget_amendment--------------------------------------
    $get_gname_query="SELECT * FROM  budget_components_details WHERE bcid=1";
    $result=$db->ArrayQuery($get_gname_query);
    
    for($i=0;$i<count($month_array);$i++){
        
      // $sheet=$objPHPExcel->createSheet();
        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $month_array[$i]);
        $objPHPExcel->addSheet($myWorkSheet, 0);
        $ews = $objPHPExcel->getSheet(0)->setRightToLeft(true);
        
        for($j=0;$j<count($excelSheetTitles);$j++){
            $total_month=0;
            //$index=$j++;
            $out_program=0;
            $index=2;
            $ews->getColumnDimension($alpha[$j])->setWidth(20);
            $ews->getColumnDimension($alpha[$j])->setWidth(20);
            $ews->setCellValue($alpha[$j]."1", $excelSheetTitles[$j]);
            $ews->getStyle($alpha[$j]."1")->getAlignment()->setWrapText(true);
            for($k=0;$k<count($result);$k++){

                $month_budget=0;
                $out_program=0;
                $displac_number=0;
                $bcdid=$result[$k]['RowID'];
                switch($i){
                    case 0:
                        $month_budget=$result[$k]['esfand'];
                        $out_program= $out_array[$bcdid][12?:0];
                        $displac_number=$dis_to_array[$bcdid][12]-$dis_from_array[$bcdid][12];
                        $delay_number=$delay_to_array[$bcdid][12]-$delay_from_array[$bcdid][12];
                        $entry_number=$budget_entry_array[$bcdid][12];
                        $ammend_number=$budget_amend_array[$bcdid][12];
                        $total_month=$result[$k]['esfandTotal'];
                       // $total_month_e=$month_budget;
                        break;
                    case 1:
                        $month_budget=$result[$k]['bahman'];
                        $out_program= $out_array[$bcdid][11?:0];
                         $displac_number=$dis_to_array[$bcdid][11]-$dis_from_array[$bcdid][11];
                         $delay_number=$delay_to_array[$bcdid][11]-$delay_from_array[$bcdid][11];
                         $entry_number=$budget_entry_array[$bcdid][11];
                         $ammend_number=$budget_amend_array[$bcdid][11];
                         $total_month=$result[$k]['bahmanTotal'];
                       //$total_month+=$month_budget;
                        break;
                    case 2:
                        $month_budget=$result[$k]['dey'];
                        $out_program= $out_array[$bcdid][10?:0];
                         $displac_number=$dis_to_array[$bcdid][10]-$dis_from_array[$bcdid][10];
                         $delay_number=$delay_to_array[$bcdid][10]-$delay_from_array[$bcdid][10];
                         $entry_number=$budget_entry_array[$bcdid][10];
                         $ammend_number=$budget_amend_array[$bcdid][10];
                         $total_month=$result[$k]['deyTotal'];
                        //$total_month+=$month_budget;
                        break;
                    case 3:
                        $month_budget=$result[$k]['azar'];
                        $out_program= $out_array[$bcdid][9?:0];
                         $displac_number=$dis_to_array[$bcdid][9]-$dis_from_array[$bcdid][9];
                         $delay_number=$delay_to_array[$bcdid][9]-$delay_from_array[$bcdid][9];
                         $entry_number=$budget_entry_array[$bcdid][9];
                         $ammend_number=$budget_amend_array[$bcdid][9];
                         $total_month=$result[$k]['azarTotal'];
                        //$total_month+=$month_budget;
                        break;
                    case 4:
                        $month_budget=$result[$k]['aban'];
                        $out_program= $out_array[$bcdid][8?:0];
                         $displac_number=$dis_to_array[$bcdid][8]-$dis_from_array[$bcdid][8];
                         $delay_number=$delay_to_array[$bcdid][8]-$delay_from_array[$bcdid][8];
                         $entry_number=$budget_entry_array[$bcdid][8];
                         $ammend_number=$budget_amend_array[$bcdid][8];
                         $total_month=$result[$k]['abanTotal'];
                        //$total_month+=$month_budget;
                        break;
                    case 5:
                        $month_budget=$result[$k]['mehr'];
                        $out_program= $out_array[$bcdid][7?:0];
                         $displac_number=$dis_to_array[$bcdid][7]-$dis_from_array[$bcdid][7];
                         $delay_number=$delay_to_array[$bcdid][7]-$delay_from_array[$bcdid][7];
                        $entry_number=$budget_entry_array[$bcdid][7];
                        $ammend_number=$budget_amend_array[$bcdid][7];
                        $total_month=$result[$k]['mehrTotal'];
                        //$total_month+=$month_budget;
                        break;
                    case 6:
                        $month_budget=$result[$k]['shahrivar'];
                        $out_program= $out_array[$bcdid][6?:0];
                         $displac_number=$dis_to_array[$bcdid][6]-$dis_from_array[$bcdid][6];
                         $delay_number=$delay_to_array[$bcdid][6]-$delay_from_array[$bcdid][6];
                        $entry_number=$budget_entry_array[$bcdid][6];
                        $ammend_number=$budget_amend_array[$bcdid][6];
                        $total_month=$result[$k]['shahrivarTotal'];
                       // $total_month+=$month_budget;
                        break;
                    case 7:
                        $month_budget=$result[$k]['mordad'];
                        $out_program= $out_array[$bcdid][5?:0];
                         $displac_number=$dis_to_array[$bcdid][5]-$dis_from_array[$bcdid][5];
                         $delay_number=$delay_to_array[$bcdid][5]-$delay_from_array[$bcdid][5];
                        $entry_number=$budget_entry_array[$bcdid][5];
                        $ammend_number=$budget_amend_array[$bcdid][5];
                        $total_month=$result[$k]['mordadTotal'];
                        //$total_month+=$month_budget;
                        break;
                    case 8:
                        $month_budget=$result[$k]['tir'];
                        $out_program= $out_array[$bcdid][4?:0];
                        $displac_number=$dis_to_array[$bcdid][4]-$dis_from_array[$bcdid][4];
                        $delay_number=$delay_to_array[$bcdid][4]-$delay_from_array[$bcdid][4];
                        $entry_number=$budget_entry_array[$bcdid][4];
                        $ammend_number=$budget_amend_array[$bcdid][4];
                        $total_month=$result[$k]['tirTotal'];
                        //$total_month+=$month_budget;
                        break;
                    case 9:
                        $month_budget=$result[$k]['khordad'];
                        $out_program= $out_array[$bcdid][3?:0];
                         $displac_number=$dis_to_array[$bcdid][3]-$dis_from_array[$bcdid][3];
                         $delay_number=$delay_to_array[$bcdid][3]-$delay_from_array[$bcdid][3];
                         $entry_number=$budget_entry_array[$bcdid][3];
                         $ammend_number=$budget_amend_array[$bcdid][3];
                         $total_month=$result[$k]['khordadTotal'];
                       // $total_month+=$month_budget;
                        break;
                    case 10:
                        $month_budget=$result[$k]['ordibehesht'];
                        $out_program= $out_array[$bcdid][2?:0];
                         $displac_number=$dis_to_array[$bcdid][2]-$dis_from_array[$bcdid][2];
                         $delay_number=$delay_to_array[$bcdid][2]-$delay_from_array[$bcdid][2];
                         $entry_number=$budget_entry_array[$bcdid][2];
                         $ammend_number=$budget_amend_array[$bcdid][2];
                         $total_month=$result[$k]['ordibeheshtTotal'];
                        //$total_month+=$month_budget;
                        break;
                    case 11:
                        $month_budget=$result[$k]['farvardin'];
                        $out_program= $out_array[$bcdid][1]?:0;
                        $displac_number=$dis_to_array[$bcdid][1]-$dis_from_array[$bcdid][1];
                        $delay_number=$delay_to_array[$bcdid][1]-$delay_from_array[$bcdid][1];
                        $entry_number=$budget_entry_array[$bcdid][1];
                        $ammend_number=$budget_amend_array[$bcdid][1];
                        $total_month=$result[$k]['farvardinTotal'];
                        //$total_month+=$month_budget;
                        break;
                    // case 12:
                    //     $month_budget=$total_month;
                    //     $out_program= $out_array[$bcdid][1]?:0;
                    //     $displac_number=$dis_to_array[$bcdid][1]-$dis_from_array[$bcdid][1];
                    //     $delay_number=$delay_to_array[$bcdid][1]-$delay_from_array[$bcdid][1];
                    //     $entry_number=$budget_entry_array[$bcdid][1];
                        
                    //     break;
                }
                
$styleArray = array(
    'font'  => array(

        'color' => array('rgb' => 'FF0000'),
        'size'  => 12,
        'name'  => 'Verdana'
    ));
$styleArray2 = array(
    'font'  => array(
        
        'color' => array('rgb' => '008000'),
        'size'  => 12,
        'name'  => 'Verdana'
    ));
//$phpExcel->getActiveSheet()->getCell('A1')->setValue('Some text');
//$phpExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
                $ultimate_buget=$month_budget+$out_program+$displac_number+$delay_number;
                $ews->setCellValue($alpha[0].$index, $result[$k]['gCode']);
                $ews->setCellValue($alpha[1].$index, $result[$k]['HCode']);
                $ews->setCellValue($alpha[2].$index, $result[$k]['gName']);
                $ews->setCellValue($alpha[3].$index, $month_budget);
                $ews->setCellValue($alpha[4].$index, $out_program);

                $ews->setCellValue($alpha[5].$index, $displac_number);
                if($displac_number<0)
                    $ews->getStyle($alpha[5].$index)->applyFromArray($styleArray);
                if($displac_number>0)
                    $ews->getStyle($alpha[5].$index)->applyFromArray($styleArray2);

                $ews->setCellValue($alpha[6].$index, $delay_number);
                if($delay_number>0)
                    $ews->getStyle($alpha[6].$index)->applyFromArray($styleArray2);
                if($delay_number<0)
                    $ews->getStyle($alpha[6].$index)->applyFromArray($styleArray);

                $ews->setCellValue($alpha[7].$index, $ammend_number);
				$ews->setCellValue($alpha[8].$index, $ultimate_buget);
                $ews->setCellValue($alpha[9].$index, $entry_number);
               
                $ews->setCellValue($alpha[10].$index, $month_budget+$out_program+$displac_number+$delay_number-$entry_number);
                $ews->setCellValue($alpha[11].$index, $ultimate_buget>0? $entry_number/$ultimate_buget*100:'-');
                $not_achive_primary_budget_percent=100-($entry_number/$month_budget*100);// درصد انحراف از بودجه اولیه
                if($not_achive_primary_budget_percent>0){
                    $ews->getStyle($alpha[12].$index)->applyFromArray($styleArray);
                }
                $ews->setCellValue($alpha[12].$index, $month_budget>0? $not_achive_primary_budget_percent:'-');
                $not_achive_ultimate_budget_percent=($month_budget+$out_program+$displac_number+$delay_number-$entry_number) /$ultimate_buget*100;//درصد انحراف از بودجه نهایی 
                if($not_achive_ultimate_budget_percent>0){
                    $ews->getStyle($alpha[13].$index)->applyFromArray($styleArray);
                }
                $ews->setCellValue($alpha[13].$index, $ultimate_buget>0?$not_achive_ultimate_budget_percent:'-');
                $index++;

            }
           

        }
    }

    $sheetIndex = $objPHPExcel->getIndex(
        $objPHPExcel->getSheetByName('Worksheet')
    );
    $objPHPExcel->removeSheetByIndex($sheetIndex);

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="01simple.xls"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0
    $path = '/excelTemp/'.$name.'.xlsx';
    $writer = new Xlsx($objPHPExcel);
    $writer->save("..".$path);
    //return ($_SESSION['HttpHost'] == '192.168.2.20:8013' ? ADDR : ADDRR).$path;
    return ADDR.$path;
 }
// //***************************************************************************************************
	
	
	//***********************************************************************
    public function getFinalBudgetManageExcel1
	($name,$bid){
        $tempPath = "../excelTemp/";
        if ($handle=opendir($tempPath)) {
            while (false!==($file=readdir($handle))) {
                if ($file<>"." AND $file<>"..") {
                    if (is_file($tempPath.'/'.$file))  {
                        @unlink($tempPath.'/'.$file);
                    }
                }
            }
        }
        $db = new DBi();
        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->getActiveSheet()->setRightToLeft(true);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //$objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $alpha = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        $countAlpha =  count($alpha);
        for($i=0;$i<$countAlpha;$i++){
            for ($j=0;$j<$countAlpha;$j++){
                $alpha[] = $alpha[$i] . $alpha[$j];
            }
        }
        $countAlpha = count($alpha);
        for($k=26;$k<$countAlpha;$k++){
            for ($m=0;$m<26;$m++){
                $alpha[] = $alpha[$k].$alpha[$m];
            }
        }

        $style = array(
            'font' => array('size' => 12,'bold' => true,'color' => array('rgb' => '000000'),'name'  => 'B Nazanin'),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => (\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'color' => array('argb' => '00000000'),
                ),
            )
        );
        $style1 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'a9ece9')
            )
        );
        $style2 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'DDD9C4')
            )
        );
        $style3 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'CCC0DA')
            )
        );
        $style4 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => '92D050')
            )
        );
        $style5 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'FFC000')
            )
        );
        $style6 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => '8CDABA')
            )
        );
        $style7 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'CCBD00')
            )
        );
        $style8 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'FFA7FB')
            )
        );
        $style9 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'D9D9D9')
            )
        );
        $style10 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'FFC9CA')
            )
        );
        $style11 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => '00FFFF')
            )
        );
        $style12 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'FFFFE1')
            )
        );

        $objPHPExcel->getActiveSheet()->mergeCells("A1:A2");  // کد مهندسی
        $objPHPExcel->getActiveSheet()->mergeCells("B1:B2");  // کد کالا
        $objPHPExcel->getActiveSheet()->mergeCells("C1:C2");  // نام کالا
        $objPHPExcel->getActiveSheet()->mergeCells("D1:O1");  // فروردین
        $objPHPExcel->getActiveSheet()->mergeCells("P1:AA1");  // اردیبهشت
        $objPHPExcel->getActiveSheet()->mergeCells("AB1:AM1");  // خرداد
        $objPHPExcel->getActiveSheet()->mergeCells("AN1:AY1");  // تیر
        $objPHPExcel->getActiveSheet()->mergeCells("AZ1:BK1");  // مرداد
        $objPHPExcel->getActiveSheet()->mergeCells("BL1:BW1");  // شهریور
        $objPHPExcel->getActiveSheet()->mergeCells("BX1:CI1");  // مهر
        $objPHPExcel->getActiveSheet()->mergeCells("CJ1:CU1");  // آبان
        $objPHPExcel->getActiveSheet()->mergeCells("CV1:DG1");  // آذر
        $objPHPExcel->getActiveSheet()->mergeCells("DH1:DS1");  // دی
        $objPHPExcel->getActiveSheet()->mergeCells("DT1:EE1");  // بهمن
        $objPHPExcel->getActiveSheet()->mergeCells("EF1:EQ1");  // اسفند

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'کد مهندسی');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'کد کالا');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'نام کالا');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'فروردین');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'اردیبهشت');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB1', 'خرداد');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN1', 'تیر');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AZ1', 'مرداد');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('BL1', 'شهریور');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('BX1', 'مهر');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('CJ1', 'آبان');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('CV1', 'آذر');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('DH1', 'دی');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('DT1', 'بهمن');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('EF1', 'اسفند');

        $x = 3;
        for ($i=0;$i<12;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'بودجه فروش اولیه');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'خارج از برنامه');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'جابجایی (کاهش)');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'جابجایی (افزایش)');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'تاخیر در تحویل (کاهش)');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'تاخیر در تحویل (افزایش)');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'مقدار در زمان اصلاحیه');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'مقدار اصلاح شده به');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'ما به التفاوت');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'مقدار پس از اعمال کلیه تغییرات');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'مقدار کل تحویل نشده');
            $x++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$x].'2', 'مقدار تحویل به انبار');
            $x++;
        }

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(60);

        $sql = "SELECT `RowID` FROM `budget` WHERE `year`={$bid}";
        $res = $db->ArrayQuery($sql);

        $sql1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$res[0]['RowID']} AND `finalTick`=1";
        $res1 = $db->ArrayQuery($sql1);
        $ccnt = count($res1);
        $rids = array();
        for ($i=0;$i<$ccnt;$i++){
            $rids[] = $res1[$i]['RowID'];
        }
        $rids = implode(',',$rids);

        $sql2 = "SELECT `budget_components_details`.* FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `finalTick`=1";
        $res2 = $db->ArrayQuery($sql2);
        $cnt = count($res2) + 2;
        $cnt8 = count($res2);

        for ($i=0;$i<$cnt8;$i++){
            $counter = $i+3;

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$counter, $res2[$i]['gCode']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$counter, $res2[$i]['HCode']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$counter, $res2[$i]['gName']);

            $totalArray = array();

            for ($j=1;$j<13;$j++) {

                $sql1 = "SELECT `number` FROM `budget_out_program` WHERE `bcdid`={$res2[$i]['RowID']} AND `month`={$j} AND `finalTick`=1";
                $res1 = $db->ArrayQuery($sql1);
                if (count($res1) <= 0){
                    $res1[0]['number'] = '';
                }

                $sql22 = "SELECT `number` FROM `budget_displacement` WHERE `bcdid`={$res2[$i]['RowID']} AND `fromMonth`={$j} AND `finalTick`=1";
                $res22 = $db->ArrayQuery($sql22);
                if (count($res22) > 0){
                    $cnt22 = count($res22);
                    $number2 = 0;
                    for ($w=0;$w<$cnt22;$w++){
                        $number2 += $res22[$w]['number'];
                    }
                }else{
                    $number2 = '';
                }

                $number3 = 0;
                $queryy = "SELECT `number`,`bcdid` FROM `budget_displacement` WHERE `fromYear`!={$res[0]['RowID']} AND `toYear`={$res[0]['RowID']} AND `toMonth`={$j} AND `finalTick`=1";
                $rsty = $db->ArrayQuery($queryy);
                if (count($rsty) > 0){
                    $ccnty = count($rsty);
                    for ($q=0;$q<$ccnty;$q++){
                        $query1 = "SELECT `goodID` FROM `budget_components_details` WHERE `RowID`={$rsty[$q]['bcdid']}";
                        $rst1 = $db->ArrayQuery($query1);
                        if (intval($rst1[0]['goodID']) == intval($res[0]['goodID'])){
                            $number3 += $rsty[$q]['number'];
                        }
                    }
                }

                $sql3 = "SELECT `number` FROM `budget_displacement` WHERE `bcdid`={$res2[$i]['RowID']} AND `toMonth`={$j} AND `finalTick`=1";
                $res3 = $db->ArrayQuery($sql3);

                if (count($res3) > 0){
                    $cnt33 = count($res3);
                    for ($w=0;$w<$cnt33;$w++){
                        $number3 += $res3[$w]['number'];
                    }
                }
                if (count($res3) <= 0 && count($rsty) <= 0){
                    $number3 = '';
                }

                $sql4 = "SELECT `number` FROM `budget_delay` WHERE `bcdid`={$res2[$i]['RowID']} AND `fromMonth`={$j} AND `finalTick`=1";
                $res4 = $db->ArrayQuery($sql4);
                if (count($res4) > 0){
                    $cnt44 = count($res4);
                    $number4 = 0;
                    for ($w=0;$w<$cnt44;$w++){
                        $number4 +=  $res4[$w]['number'];
                    }
                }else{
                    $number4 = '';
                }

                $sql5 = "SELECT `number` FROM `budget_delay` WHERE `bcdid`={$res2[$i]['RowID']} AND `toMonth`={$j} AND `finalTick`=1";
                $res5 = $db->ArrayQuery($sql5);
                if (count($res5) > 0){
                    $cnt5 = count($res5);
                    $number5 = 0;
                    for ($w=0;$w<$cnt5;$w++){
                        $number5 +=  $res5[$w]['number'];
                    }
                }else{
                    $number5 = '';
                }

                $sql6 = "SELECT `number` FROM `budget_product_entry` WHERE `bcdid`={$res2[$i]['RowID']} AND `month`={$j}";
                $res6 = $db->ArrayQuery($sql6);
                if (count($res6) > 0){
                    $cnt66 = count($res6);
                    $number6 = 0;
                    for ($w=0;$w<$cnt66;$w++){
                        $number6 +=  $res6[$w]['number'];
                    }
                }else{
                    $number6 = '';
                }

                $sql8 = "SELECT `number`,`currentNumber`,`DifferenceNumber` FROM `budget_amendment` WHERE `bcdid`={$res2[$i]['RowID']} AND `month`={$j} AND `finalTick`=1";
                $res8 = $db->ArrayQuery($sql8);
                if (count($res8) <= 0){
                    $res8[0]['number'] = '';
                    $res8[0]['currentNumber'] = '';
                    $res8[0]['DifferenceNumber'] = '';
                }

                switch (intval($j)){
                    case 1:
                        $baceNumber = intval($res2[$i]['farvardin']);
                        $baceNumber1 = intval($res2[$i]['farvardinTotal']);
                        break;
                    case 2:
                        $baceNumber = intval($res2[$i]['ordibehesht']);
                        $baceNumber1 = intval($res2[$i]['ordibeheshtTotal']);
                        break;
                    case 3:
                        $baceNumber = intval($res2[$i]['khordad']);
                        $baceNumber1 = intval($res2[$i]['khordadTotal']);
                        break;
                    case 4:
                        $baceNumber = intval($res2[$i]['tir']);
                        $baceNumber1 = intval($res2[$i]['tirTotal']);
                        break;
                    case 5:
                        $baceNumber = intval($res2[$i]['mordad']);
                        $baceNumber1 = intval($res2[$i]['mordadTotal']);
                        break;
                    case 6:
                        $baceNumber = intval($res2[$i]['shahrivar']);
                        $baceNumber1 = intval($res2[$i]['shahrivarTotal']);
                        break;
                    case 7:
                        $baceNumber = intval($res2[$i]['mehr']);
                        $baceNumber1 = intval($res2[$i]['mehrTotal']);
                        break;
                    case 8:
                        $baceNumber = intval($res2[$i]['aban']);
                        $baceNumber1 = intval($res2[$i]['abanTotal']);
                        break;
                    case 9:
                        $baceNumber = intval($res2[$i]['azar']);
                        $baceNumber1 = intval($res2[$i]['azarTotal']);
                        break;
                    case 10:
                        $baceNumber = intval($res2[$i]['dey']);
                        $baceNumber1 = intval($res2[$i]['deyTotal']);
                        break;
                    case 11:
                        $baceNumber = intval($res2[$i]['bahman']);
                        $baceNumber1 = intval($res2[$i]['bahmanTotal']);
                        break;
                    case 12:
                        $baceNumber = intval($res2[$i]['esfand']);
                        $baceNumber1 = intval($res2[$i]['esfandTotal']);
                        break;
                }

                $totalArray[] = $baceNumber;
                $totalArray[] = $res1[0]['number'];
                $totalArray[] = $number2;
                $totalArray[] = $number3;
                $totalArray[] = $number4;
                $totalArray[] = $number5;
                $totalArray[] = $res8[0]['currentNumber'];
                $totalArray[] = $res8[0]['number'];
                $totalArray[] = $res8[0]['DifferenceNumber'];
                $totalArray[] = $baceNumber1;
                $totalArray[] = $baceNumber1;
                $totalArray[] = $number6;
            }

            $cntTotal = count($totalArray);
            $e = 3;
            for ($m=0;$m<$cntTotal;$m++){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($alpha[$e].$counter, $totalArray[$m]);
                $e++;
            }
        }

        $width1 = array('D','P','AB','AN','AZ','BL','BX','CJ','CV','DH','DT','EF');
        $width2 = array('E','Q','AC','AO','BA','BM','BY','CK','CW','DI','DU','EG');
        $width3 = array('F','R','AD','AP','BB','BN','BZ','CL','CX','DJ','DV','EH');
        $width4 = array('G','S','AE','AQ','BC','BO','CA','CM','CY','DK','DW','EI');
        $width5 = array('H','T','AF','AR','BD','BP','CB','CN','CZ','DL','DX','EJ');
        $width6 = array('I','U','AG','AS','BE','BQ','CC','CO','DA','DM','DY','EK');
        $width7 = array('J','V','AH','AT','BF','BR','CD','CP','DB','DN','DZ','EL');
        $width8 = array('K','W','AI','AU','BG','BS','CE','CQ','DC','DO','EA','EM');
        $width9 = array('L','X','AJ','AV','BH','BT','CF','CR','DD','DP','EB','EN');
        $width10 = array('M','Y','AK','AW','BI','BU','CG','CS','DE','DQ','EC','EO');
        $width11 = array('N','Z','AL','AX','BJ','BV','CH','CT','DF','DR','ED','EP');
        $width12 = array('O','AA','AM','AY','BK','BW','CI','CU','DG','DS','EE','EQ');

        $cnt1 = count($width1);
        $cnt2 = count($width2);
        $cnt3 = count($width3);
        $cnt4 = count($width4);
        $cnt5 = count($width5);
        $cnt6 = count($width6);
        $cnt7 = count($width7);
        $cnt8 = count($width8);
        $cnt9 = count($width9);
        $cnt10 = count($width10);
        $cnt11 = count($width11);
        $cnt12 = count($width12);

        for ($i=0;$i<$cnt1;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width1[$i])->setWidth(15);
        }
        for ($i=0;$i<$cnt2;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width2[$i])->setWidth(15);
        }
        for ($i=0;$i<$cnt3;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width3[$i])->setWidth(15);
        }
        for ($i=0;$i<$cnt4;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width4[$i])->setWidth(15);
        }
        for ($i=0;$i<$cnt5;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width5[$i])->setWidth(20);
        }
        for ($i=0;$i<$cnt6;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width6[$i])->setWidth(20);
        }
        for ($i=0;$i<$cnt7;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width7[$i])->setWidth(20);
        }
        for ($i=0;$i<$cnt8;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width8[$i])->setWidth(20);
        }
        for ($i=0;$i<$cnt9;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width9[$i])->setWidth(15);
        }
        for ($i=0;$i<$cnt10;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width10[$i])->setWidth(30);
        }
        for ($i=0;$i<$cnt11;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width11[$i])->setWidth(20);
        }
        for ($i=0;$i<$cnt12;$i++){
            $objPHPExcel->getActiveSheet()->getColumnDimension($width12[$i])->setWidth(20);
        }

        $objPHPExcel->getActiveSheet()->getStyle('A1:EE'.($cnt))->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle('D1:O'.($cnt))->applyFromArray($style1);
        $objPHPExcel->getActiveSheet()->getStyle('P1:AA'.($cnt))->applyFromArray($style2);
        $objPHPExcel->getActiveSheet()->getStyle('AB1:AM'.($cnt))->applyFromArray($style3);
        $objPHPExcel->getActiveSheet()->getStyle('AN1:AY'.($cnt))->applyFromArray($style4);
        $objPHPExcel->getActiveSheet()->getStyle('AZ1:BK'.($cnt))->applyFromArray($style5);
        $objPHPExcel->getActiveSheet()->getStyle('BL1:BW'.($cnt))->applyFromArray($style6);
        $objPHPExcel->getActiveSheet()->getStyle('BX1:CI'.($cnt))->applyFromArray($style7);
        $objPHPExcel->getActiveSheet()->getStyle('CJ1:CU'.($cnt))->applyFromArray($style8);
        $objPHPExcel->getActiveSheet()->getStyle('CV1:DG'.($cnt))->applyFromArray($style9);
        $objPHPExcel->getActiveSheet()->getStyle('DH1:DS'.($cnt))->applyFromArray($style10);
        $objPHPExcel->getActiveSheet()->getStyle('DT1:EE'.($cnt))->applyFromArray($style11);
        $objPHPExcel->getActiveSheet()->getStyle('EF1:EQ'.($cnt))->applyFromArray($style12);

        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $path = '/excelTemp/'.$name.'.xlsx';
        $writer = new Xlsx($objPHPExcel);
        $writer->save("..".$path);
        //return ($_SESSION['HttpHost'] == '192.168.2.20:8013' ? ADDR : ADDRR).$path;
        return ADDR.$path;
    }

    public function getFinalBudgetNumberExcel($name,$bid){
        $tempPath = "../excelTemp/";
        if ($handle=opendir($tempPath)) {
            while (false!==($file=readdir($handle))) {
                if ($file<>"." AND $file<>"..") {
                    if (is_file($tempPath.'/'.$file))  {
                        @unlink($tempPath.'/'.$file);
                    }
                }
            }
        }
        $db = new DBi();
        $objPHPExcel = new Spreadsheet();
        $objPHPExcel->getActiveSheet()->setRightToLeft(true);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //$objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $alpha = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        $countAlpha =  count($alpha);
        for($i=0;$i<$countAlpha;$i++){
            for ($j=0;$j<$countAlpha;$j++){
                $alpha[] = $alpha[$i] . $alpha[$j];
            }
        }
        $countAlpha = count($alpha);
        for($k=26;$k<$countAlpha;$k++){
            for ($m=0;$m<26;$m++){
                $alpha[] = $alpha[$k].$alpha[$m];
            }
        }

        $style = array(
            'font' => array('size' => 12,'bold' => true,'color' => array('rgb' => '000000'),'name'  => 'B Nazanin'),
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => (\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN),
                    'color' => array('argb' => '00000000'),
                ),
            )
        );
        $style1 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'a9ece9')
            )
        );
        $style2 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'DDD9C4')
            )
        );
        $style3 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'CCC0DA')
            )
        );
        $style4 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => '92D050')
            )
        );
        $style5 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'FFC000')
            )
        );
        $style6 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => '8CDABA')
            )
        );
        $style7 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'CCBD00')
            )
        );
        $style8 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'FFA7FB')
            )
        );
        $style9 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'D9D9D9')
            )
        );
        $style10 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'FFC9CA')
            )
        );
        $style11 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => '00FFFF')
            )
        );
        $style12 = array(
            'fill' => array(
                'fillType' => (\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID),
                'startColor' => array('argb' => 'FFFFE1')
            )
        );


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', ' ردیف');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'کد مهندسی');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'کد کالا');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'نام کالا');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', ' برند');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', ' گروه');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', ' زیرگروه');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'سری ');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'فروردین');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'اردیبهشت');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'خرداد');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'تیر');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'مرداد');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'شهریور');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'مهر');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'آبان');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'آذر');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'دی');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', 'بهمن');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', 'اسفند');

        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

        $sql = "SELECT `RowID` FROM `budget` WHERE `year`={$bid}";
        $res = $db->ArrayQuery($sql);

        $sql1 = "SELECT `RowID` FROM `budget_components` WHERE `budgetID`={$res[0]['RowID']} AND `finalTick`=1";
        $res1 = $db->ArrayQuery($sql1);
        $ccnt = count($res1);
        $rids = array();
        for ($i=0;$i<$ccnt;$i++){
            $rids[] = $res1[$i]['RowID'];
        }
        $rids = implode(',',$rids);

        $sql2 = "SELECT `budget_components_details`.* FROM `budget_components_details` WHERE `bcid` IN ({$rids}) AND `finalTick`=1 ORDER BY RowID ASC";
        $res2 = $db->ArrayQuery($sql2);
        $cnt8 = count($res2);
        //*********************************************************************************** */
        // $budgect_entry_sql="SELECT `bcdid`,`month`,sum(`number`) as `number`,sum(`remaining`) as `remaining` FROM `budget_product_entry` where `bid`={$res[0]['RowID']} group by `bcdid`,`month`";
        // $budgect_entry_result_array = $db->ArrayQuery($budgect_entry_sql);
        // $productEntryArray=[];
        // foreach($budgect_entry_result_array as $key=>$value){
        //     $productEntryArray[$value['bcdid']][$value['month']]=array
        //                                                             (
        //                                                                 'month'=>$value['month'],
        //                                                                 'number'=>$value['number'],
        //                                                                 'remaining'=>$value['remaining']
        //                                                             );
        // }
        //-----------------------------------------------------------
        // $delayBudget="SELECT * FROM `budget_delay` WHERE `bid`={$res[0]['RowID']}";
        // //$this->fileRecorder($delayBudget);
        // $delayBudgetresult_array = $db->ArrayQuery($delayBudget);
        // $delayBudgetArray=[];
        // foreach($delayBudgetresult_array as $b_delay_key=>$b_delay_value){
        //     $delayBudgetArray[$b_delay_value['bcdid']][]=array
        //                                                     (
        //                                                         'fromMonth'=>$b_delay_value['fromMonth'],
        //                                                         "toMonth"=>$b_delay_value['toMonth'],
        //                                                         'number'=>$b_delay_value['number']
        //                                                     );
        // }
        // //$this->fileRecorder($delayBudgetArray);
        // //-----------------------------------------------------------
        // $displac_budget_query="SELECT * from `budget_displacement` WHERE fromYear={$res[0]['RowID']} AND toYear={$res[0]['RowID']}";
        // $displac_budget_array = $db->ArrayQuery($displac_budget_query);
        // $displace_array=[];
        // foreach($displac_budget_array as $displace_key=>$displace_value){
        //     $displace_array[$displace_value['bcdid']][]=array(
        //                                                         'fromMonth'=>$displace_value['fromMonth'],
        //                                                         "toMonth"=>$displace_value['toMonth'],
        //                                                         'number'=>$displace_value['number']

        //                                                      );
        // }
       //  //$this->fileRecorder($displace_array);
        //*********************************************************************************** */
        for ($i=0;$i<$cnt8;$i++)
        {
            $counter = $i+2;
            $get_budget_delay_sql="SELECT * FROM budget_delay where bcdid={$res2[$i]['RowID']} AND bid={$res[0]['RowID']}";
            $delayBudgetresult_array = $db->ArrayQuery($get_budget_delay_sql);
            $delay_array=[];
			if(count($delayBudgetresult_array[0])>0){
                $current_delay_array=$delayBudgetresult_array[0];
                
          
                foreach($current_delay_array as $k=>$value){
                    $delay_array[$current_delay_array['toMonth']]=$current_delay_array['number'];
                    $delay_array[$current_delay_array['FromMonth']]=$current_delay_array['number'];
                    
                }
            }
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$counter, $res2[$i]['RowID']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$counter, $res2[$i]['gCode']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$counter, $res2[$i]['HCode']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$counter, $res2[$i]['gName']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$counter, $res2[$i]['brand']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$counter, $res2[$i]['ggroup']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$counter, $res2[$i]['gsgroup']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$counter, $res2[$i]['series']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$counter, $res2[$i]['farvardin']+$delay_array[1]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$counter, $res2[$i]['ordibehesht']+$delay_array[2]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$counter, $res2[$i]['khordad']+$delay_array[3]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$counter, $res2[$i]['tir']+$delay_array[4]);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$counter, $res2[$i]['mordad']+$delay_array[5]);
            //$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$counter, $res2[$i]['mordad']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$counter, $res2[$i]['shahrivar']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$counter, $res2[$i]['mehr']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$counter, $res2[$i]['aban']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$counter, $res2[$i]['azar']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$counter, $res2[$i]['dey']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$counter, $res2[$i]['bahman']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$counter, $res2[$i]['esfand']);
            
        }

        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="01simple.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $path = '/excelTemp/'.$name.'.xlsx';
        
        $writer = new Xlsx($objPHPExcel);
        $writer->save("..".$path);
        
        //return ($_SESSION['HttpHost'] == '192.168.2.20:8013' ? ADDR : ADDRR).$path;
        return ADDR.$path;
    }

    public function createVerifyCode($phone){
        $db = new DBi();
        $code = rand(10001, 99999);
        $cTime = date('H:i:s');

        $sqq = "DELETE FROM `verify_code` WHERE `tel`='{$phone}'";
        $db->Query($sqq);

        $sql = "INSERT INTO `verify_code` (`tel`,`code`,`timing`) VALUES ('{$phone}',{$code},'{$cTime}')";
        $res = $db->Query($sql);
        if (intval($res) > 0) {
            $client = new SoapClient("https://ippanel.com/class/sms/wsdlservice/server.php?wsdl");
            $user = "09155238839";-
           // $pass = "hW$8%jlq@nA&zP4z3r6E";
			$pass = "hW$8%jlq@nA&zP4z3r6Eabrash";
            $fromNum = "+983000505";
            $toNum = array($phone);
            $pattern_code = "72czui8lft";
            $input_data = array("code" => $code);
            //$client->sendPatternSms($fromNum, $toNum, $user, $pass, $pattern_code, $input_data);
            return true;
        }else{
            return false;
        }
    }

    public function sendAllBudgetElements($phone,$cartable){
        
        $client = new SoapClient("https://ippanel.com/class/sms/wsdlservice/server.php?wsdl");
        $user = "09155238839";
         // $pass = "hW$8%jlq@nA&zP4z3r6E";
		$pass = "hW$8%jlq@nA&zP4z3r6Eabrash";
        $fromNum = "+983000505";
        $toNum = array($phone);
        $pattern_code = "cjtp5in07dul99f";
       $pattern_code = "upkhx40ruznhkw3"; //pattern  برای  پیامک جلسات نرمال
        $input_data = array("cartable" => $cartable);
		////$this->fileRecorder('input_data:'.print_r($input_data,true));
        $client->sendPatternSms($fromNum, $toNum, $user, $pass, $pattern_code, $input_data);
    }
	
    public function sendToMeetingMembers($phone,$subject,$mdate,$mtime,$mplace){
        $context = stream_context_create(array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
    ));
        $client = new SoapClient("https://ippanel.com/class/sms/wsdlservice/server.php?wsdl",array('stream_context'=>$context));
        $user = "09155238839";
          // $pass = "hW$8%jlq@nA&zP4z3r6E";
		$pass = "hW$8%jlq@nA&zP4z3r6Eabrash";
        $fromNum = "+983000505";
        $toNum = array($phone);
        $pattern_code = "lzosnt05my0we4n";
        $input_data = array("subject" => $subject,"mdate" => $mdate,"mtime" => $mtime,"mplace" => $mplace);
        $client->sendPatternSms($fromNum, $toNum, $user, $pass, $pattern_code, $input_data);
        
    }
    
	public function get_full_access_users($RowID){
		$db=new DBi();
		$query="SELECT * FROM `full_access_users` where RowID={$RowID}";
		$res=$db->ArrayQuery($query);
		return json_decode($res[0]['full_access_user_json'],true);
	}

	public function get_client_mac_address(){
		$client_ip = $_SERVER['REMOTE_ADDR'];
		exec("arp -a $client_ip", $output);
		$mac_address = $output[1];
		return $mac_address;
	}

	public function createJsonFile($array=[]){
        $db=new DBi();
        $current_date=date("Y-m-d H:i:s");
        if(count($array)>0){
            $sql="SELECT * FROM users_login_info where user_id={$array['userID']}";
            $res=$db->ArrayQuery($sql);
            if(count($res)>0){
                $u_sql="UPDATE users_login_info set ip='{$array['ip']}',enter_date='{$current_date}' where user_id={$array['userID']} ";
                $u_res=$db->Query($u_sql);
            }
            else{
                $i_sql="INSERT INTO users_login_info(`user_id`,`ip`,`enter_date`)VALUES('{$array['userID']}','{$array['ip']}','{$current_date}')";
                $i_res=$db->Query($i_sql);
            }
        }
     
    }

    public function get_sms_credit(){
        $url = "https://ippanel.com/services.jspd";
        $param = array
                    (
                        'uname'=>'09155238839',
                        'pass'=>'hW$8%jlq@nA&zP4z3r6Eabrash',
                        'op'=>'credit'
                    );
                    
        $handler = curl_init($url);             
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $param);                       
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response2 = curl_exec($handler);
    
        $response2 = json_decode($response2);
        $res_code = $response2[0];
        $res_data = $response2[1];
        return $res_data;
    }

    public function get_user_fullname($user_id,$perfix=0)
    {
       $db=new DBi();
       $user_full_name_qry="SELECT fname,lname,gender from users  where RowID={$user_id}";
       $res=$db->ArrayQuery($user_full_name_qry);
       if(count($res>0)){
            $perfix_text="";
            if($perfix==1){
                if($res[0]['gender']==1){
                    $perfix_text=" خانم ";
                }    
                if($res[0]['gender']==0){
                    $perfix_text=" آقای ";
                }
            }
            return $perfix_text." ".$res[0]['fname']." ".$res[0]['lname'];
        }
    }

    public function get_pieces_product_bom_update_history($array=[]){
      
        $db  = new DBi();
        $acm = new acm();
        extract($array);
        if(!$acm->hasAccess('GetFullUpdateHistory')){
            $developer_access=0;
            //$sql="SELECT * FROM `eng_update_history` WHERE `type`='{$update_type}' AND `status`=1 ORDER BY RowID DESC";
            $sql="SELECT * FROM `eng_update_history` WHERE `type`='{$update_type}' AND `status`=1 ORDER BY RowID DESC";
        }
        else{
            $developer_access=1;
            $sql="SELECT * FROM `eng_update_history` WHERE `type`='{$update_type}' ORDER BY RowID DESC";
        }
        
        $res=$db->ArrayQuery($sql);
        $html_out="<table border='1' class='table table-borderd' id='pieces_history_tbl'>";
        if(count($res)>0){
            $html_out.=
            "<thead><tr>
            <th style='width:10%'>ردیف</th>
            <th style='width:50%'>شرح پیام</th>
            <th style='width:15%'>تاریخ و ساعت</th>
            <th style='width:15%'>وضعیت بروزرسانی</th>";
            if($developer_access==1){
                $html_out.="<th style='width:10%'>مشاهده خطا</th>";
            } 
            $html_out.="</tr>
                </thead>";
               
    
            $html_out.="<tbody>"; 
            foreach($res as $key=>$value){
                $update_date_time_array=explode(' ',$value['update_date_time']);
                $update_date_time=$this->greg_to_jal($update_date_time_array[0])." ".$update_date_time_array[1];
                $update_status=$value['status']==1?'<span class="text-success">بروزرسانی موفق</span>':'<span class="text-danger">خطا در بروزرسانی</span>';
                $html_out.="<tr>";
                $html_out.="<td style='width:10%'>".($key+1)."</td>";
                $html_out.="<td style='width:50%'>{$value['result']}</td>";
                $html_out.="<td style='width:15%'>{$update_date_time}</td>";
                $html_out.="<td style='width:15%'>{$update_status}</td>";
                if($developer_access==1){
                    if($value['status']==0 && !empty($value['error_text'])){
                        $error_btn="<a style='cursor:pointer' class='text-danger' onclick='display_eng_update_error({$value['RowID']})'>مشاهده خطا</a>";
                    }
                    else{
                        $error_btn="<span class='text-success'>فاقد خطا</span>";
                    }
                    $html_out.="<td style='width:10%'>{$error_btn}</div></td>";
                } 
               
                $html_out.="</tr>";

            }

            $html_out.="</tbody>";
            $html_out.="</table>";
        }
        else
        {
            $html_out= "<p class='text-center text-danger p-4'> موردی ثبت نشده است </p>";
        }
        return $html_out;
    }

    public function display_eng_update_error($arr){
        $db=new DBi();
        extract($arr);
        $sql="SELECT `error_text` FROM `eng_update_history` where RowID={$RowID}" ;
        $res=$db->ArrayQuery($sql);
        $html="<div><p style=' word-wrap: break-word;color:red; overflow:scroll;padding:10px'>{$res[0]['error_text']}</p></div>";
        return $html;

    }
}