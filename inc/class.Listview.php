<?php
class Listview{

    public function __construct(){
        // do nothing
    }

    public function creat($listTitle,$rows,$columns,$totalRowsNum=0,$pagerType=0,$pageNumber=1,$tableID="",$jsf="",$footerTxt="",$footerButtons=array(),$scroll='',$headerTxt='',$chart=''){
        $ut = new Utility();
        $countCol = count($columns);
        $countRow = count($rows);
        $htm = '';
        if(strlen(trim($listTitle)) > 0){
            $htm .= '<div class="card text-dark bg-light">';
            $htm .= '<div class="card-header">'.$listTitle.'</div>';
            $htm .= '<div class="card-body">';

            //------PAGER----------
            if(intval($pagerType)==1){
                $htm .= "<div style='float:left;padding:0px;'>";
                $pager = new Pager($totalRowsNum,intval($pageNumber));
                $htm .= $pager->createGoToPage($jsf);
                $htm .= "</div>";
            }
            //---------------------
            $htm .= "<div class='table-responsive' ".(isset($scroll) ? $scroll : '').">";
            $htm .= "<table class='table table-bordered table-hover table-sm' id='".$tableID."'>";
            $htm .= "<thead>";
            if(strlen($headerTxt) > 0){
                $htm .= "<tr class='bg-light'>";
                $htm .= "<td colspan=".$countCol." style='font-family:dubai-Regular;font-weight:bold;color: #c10000;font-size:16px;padding:10px;border:1px solid #ffffff;line-height: 35px;background-color: #fff !important;'> ".$headerTxt."</td>";
                $htm .= "</tr>";
            }

            if(strlen($chart) > 0){
                $htm .= "<tr class='bg-light'>";
                $htm .= "<td colspan=".$countCol." style='font-family:dubai-Regular;font-weight:bold;color: #c10000;font-size:16px;padding:10px;border:1px solid #ffffff;line-height: 35px;background-color: #fff !important;text-align: center;'>";
                $htm .= '<div id="wrapper-chart">';
                $htm .= '<canvas id="chrt"></canvas>';
                $htm .= '</div>';
                $htm .= '</td>';
                $htm .= "</tr>";
            }

            $htm .= "<tr class='bg-secondary text-warning'>";
            $farray = array();
            for($f=0;$f<$countCol;$f++){
                $onclick = '';
                $cursor = '';
                if($columns[$f]['order']!="none"){
                    $cursor = " cursor:pointer;";
                    $onclick = " onClick='".$jsf."(".$pageNumber.",".$columns[$f]['order'].")' ";
                }
                $farray[] = $columns[$f]['f'];
                $htm .= "<td style='width:".$columns[$f]['width'].";text-align: center;font-family: dubai-Regular;font-weight: bold;".$cursor."' ".$onclick."  ".(isset($columns[$f]['id']) ? $columns[$f]['id'] : '').">".$columns[$f]['title']."</td>";
               //$ut->fileRecorder($columns[$f]['title']);
            }
            
            $countFar = count($farray);
            $htm .= "</tr>";
            $htm .= "</thead>";
            $htm .= "<tbody>";

            for($d=0;$d<$countRow;$d++){
                $blinker = '';
                $txtcolor = '';
                $bgcolor = 'table-secondary';
                $trcolor = '';
                $btnType = '';
                $icon = '';
                $disabled = '';
                if(isset($rows[$d]['blinker'])){
                    $blinker = $rows[$d]['blinker'];
                }
                if(isset($rows[$d]['txtco'])){
                    $txtcolor = $rows[$d]['txtco'];
                }
                if(isset($rows[$d]['btnType'])){
                    $btnType = $rows[$d]['btnType'];
                }
                if(isset($rows[$d]['icon'])){
                    $icon = $rows[$d]['icon'];
                }
                if(isset($rows[$d]['disabled'])){
                    $disabled = $rows[$d]['disabled'];
                }
                if(isset($rows[$d]['bgColor'])){
                    $bgcolor = $rows[$d]['bgColor'];
                }
                if(isset($rows[$d]['trColor'])){
                    $bgcolor = '';
                    $trcolor = $rows[$d]['trColor'];
                }
                if(isset($rows[$d]['barname'])){
                   
                    $barname = $rows[$d]['barname'];
                }
                if(isset($rows[$d]['sendType'])){
                   
                    $sendType = $rows[$d]['sendType'];
                }
                if(isset($rows[$d]['is_vat'])){
                   
                    $is_vat = $rows[$d]['is_vat'];
                }
                $htm .= "<tr class='".$bgcolor."' style='background-color: ".$trcolor."'>";
                $w = 0;
                for($f=0;$f<$countFar;$f++){
                    $currentField = $farray[$f];
                    $props="";
                    $params = array();
                    switch ($currentField){
                     //   
                        case 'checkBox':
                            $mtstyle = '';
							if(isset($columns[$f]['class'])){
								
                                $class = $columns[$f]['class'];
                            }
                            if(isset($columns[$f]['mt'])){
								
                                $mtstyle=' mt-2 ';
                            }
							$mtstyle=$mtstyle.$class;
							
							if(isset($columns[$f]['onclick'])){
								
                                $onclick = 'onclick="'.$columns[$f]['onclick'].'"';
                            }
                            if(isset($columns[$f]['barname'])){
								
                                $props.= 'barname="'.$barname.'"';
                            }
                            if(isset($columns[$f]['sendType'])){
								
                                $props.= 'sendType="'.$sendType.'"';
                            }
                            if(isset($columns[$f]['is_vat'])){
								
                                $props.= 'is_vat="'.$is_vat.'"';
                            }
                            
							
                            $htm .= "<td style='border:1px solid #fff;text-align: center;'><input ".$props." class='".$mtstyle."' type='checkbox' ".$onclick."  rid='".$rows[$d]['RowID']."'>&nbsp;</td>";
                            break;
                            case 'radio':
                                $props_radio="";
                                if(isset($columns[$f]['Name'])){
								
                                    $props_radio.= 'name="'.$columns[$f]['Name'].'"';
                                }
                                if(isset($columns[$f]['onclick'])){
								
                                    $props_radio.= 'onclick="'.$columns[$f]['onclick'].'"';
                                }
                                $htm .= "<td style='border:1px solid #fff;text-align: center;'><label><input type='radio' ".$props_radio."  value='".$rows[$d]['RowID']."'></label></td>";
                                break;
                        case 'btn':
                           
                            if (isset($columns[$f]['btnShowStatus'])){
                               
                                if ($btnType != 'NotShow'){
                                   
                                    $param = explode(',',($columns[$f]['param']));
                                   
                                    
                                    $countparam = count($param);
                                    if(intval($countparam) == 1){
                                        $btnClick = $columns[$f]['onclick'].'(\''.$rows[$d][$columns[$f]['param']].'\')';
                                    }else{
                                        
                                        for($x=0;$x<$countparam;$x++){
                                          
                                            $params[] = $rows[$d][$param[$x]];
                                        }
                                        $params = implode(',',$params);
                                        $btnClick = $columns[$f]['onclick'].'('.$params.')';
                                    }
                                    if (!isset($columns[$f]['manyColors'])) {
                                        $imgBtn = '<button type="button" class="btn ' . (isset($columns[$f]['icon']) ? 'btn-info' : $btnType) . ' btn-sm" ' . (isset($columns[$f]['disabled']) ? $disabled : '') . ' onclick=' . $btnClick . '><i class="fas ' . (isset($columns[$f]['icon']) ? $columns[$f]['icon'] : $icon) . ' fa-lg" style="margin: 4px 4px 0 0;"></i>&nbsp;&nbsp;' . $columns[$f]['txt'] . '</button>';
                                    }else{
                                        $imgBtn = '<button type="button" class="btn ' . $rows[$d][$w]['btnType'] . ' btn-sm" ' . (isset($columns[$f]['disabled']) ? $disabled : '') . ' onclick=' . $btnClick . '><i class="fas ' . (isset($columns[$f]['icon']) ? $columns[$f]['icon'] : $rows[$d][$w]['icon']) . ' fa-lg" style="margin: 4px 4px 0 0;"></i>&nbsp;&nbsp;' . $columns[$f]['txt'] . '</button>';
                                    }
                                    $htm .= "<td style='font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;'>".$imgBtn."&nbsp;</td>";
                                }else{
                                    $htm .= "<td style='font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;'>------</td>";
                                }
                            }else{
                                $btnClass="btn-info";
                                if(isset($columns[$f]['btnClass'])){
                                    $btnClass=$columns[$f]['btnClass'];
                                }
                               
                              
                                $param = explode(',',$columns[$f]['param']);
                                $countparam = count($param);
                                if(intval($countparam) == 1){
                                    $btnClick = $columns[$f]['onclick'].'(\''.$rows[$d][$columns[$f]['param']].'\')';
                                }else{
                                    for($x=0;$x<$countparam;$x++){
                                        
                                       // $params[] = $rows[$d][$param[$x]];
                                        $params[] = (array_key_exists($param[$x],$rows[$d])?$rows[$d][$param[$x]]:$param[$x]);
                                    }
                                    $params = implode(',',$params);
                                    $btnClick = $columns[$f]['onclick'].'('.$params.')';
                                }
                                if (!isset($columns[$f]['manyColors'])) {
                                    $imgBtn = '<button type="button" class="btn ' . (isset($columns[$f]['icon']) ? $btnClass : $btnType) . ' btn-sm" ' . (isset($columns[$f]['disabled']) ? $disabled : '') . ' onclick=' . $btnClick . '><i class="fas ' . (isset($columns[$f]['icon']) ? $columns[$f]['icon'] : $icon) . ' fa-lg" style="margin: 4px 4px 0 0;"></i>&nbsp;&nbsp;' . $columns[$f]['txt'] . '</button>';
                                }else{
                                    $imgBtn = '<button type="button" class="btn ' . $rows[$d][$w]['btnType'] . ' btn-sm" ' . (isset($columns[$f]['disabled']) ? $disabled : '') . ' onclick=' . $btnClick . '><i class="fas ' . (isset($columns[$f]['icon']) ? $columns[$f]['icon'] : $rows[$d][$w]['icon']) . ' fa-lg" style="margin: 4px 4px 0 0;"></i>&nbsp;&nbsp;' . $columns[$f]['txt'] . '</button>';
                                }
                                $htm .= "<td style='font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;'>".$imgBtn."&nbsp;</td>";
                            }
                            $w++;
                            break;
                        case 'select':
                            $val = $rows[$d][$columns[$f]['fname']];
                            $cntval = count($val);
                            $btnChange = (isset($columns[$f]['onchange']) ? 'onchange='.$columns[$f]['onchange'].'(\''.$rows[$d][$columns[$f]['param']].'\')' : '');
                            $ss = '<select class="form-control shadow-color" id="'.$columns[$f]['fname'].'-'.$rows[$d]['RowID'].'" '.$btnChange.'>';
                            for($o=0;$o<$cntval;$o+=2){
                                $ss .= '<option value="'.$val[$o].'">'.$val[$o+1].'</option>';
                            }
                            $htm .= "<td style='font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;'>".$ss."</td>";
                            break;
                        case 'status':
                        case 'isEnableTxt':
                           
                            $htm .= "<td style='font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;".(strlen(trim($txtcolor)) > 0 ? 'color:'.$txtcolor : '')."'>".$rows[$d][$currentField]."</td>";
                            break;
                        default:
                           
                            $htm .= "<td ".(isset($columns[$f]['blink']) ? 'class="'.$blinker.'"' : '')." style='font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;".(isset($columns[$f]['dir']) ? $columns[$f]['dir'] : '').(isset($columns[$f]['color']) ? 'color:'.$txtcolor : '')."'>".$rows[$d][$currentField]."</td>";
                    }
                }
                $htm .="</tr>";
                $htm .= "</tbody>";
            }
            if(strlen($footerTxt) > 0){
                $htm .= "<tr style='background-color:#FFFFFF'>";
                $htm .= "<td colspan=".count($farray)." style='font-family:dubai-Regular;font-weight:bold;color: #c10000;font-size:16px;padding:10px;border:1px solid #ffffff;line-height: 35px;'> ".$footerTxt."</td>";
                $htm .= "</tr>";
                $htm .= "</tbody>";
            }
            if ((count($footerButtons) > 0)){
                $htm .= "<tr style='background-color:#e3effd'>";
                $htm .= "<td colspan=".count($farray)." style='border:1px solid #FFFFFF;line-height: 35px;'>";
                $CountButton = count($footerButtons);
                for($i=0;$i<$CountButton;$i++){
                    $btnClick = $footerButtons[$i]['jsf'].'(\''.$rows[$i][$footerButtons[$i]['parameter']].'\')';
                    $htm .= "<button type='button' onclick=".$btnClick." class='".$footerButtons[$i]['class']."'>".$footerButtons[$i]['title']."</button>";
                }
                $htm .= "</td>";
                $htm .= "</tr>";
                $htm .= "</tbody>";
            }
            $htm .= "</table>";
            $htm .= "</div>";
            //------PAGER----------
            if(intval($pagerType)==1){
                $htm .= "<div style='float:left;padding:0px;'>";
                $pager = new Pager($totalRowsNum,intval($pageNumber));
                $htm .= $pager->createGoToPage($jsf);
                $htm .= "</div>";
            }
            //-------------PAGER-------------------
            if(intval($pagerType)==2){
                $pager = new Pager($totalRowsNum,intval($pageNumber));
                $htm .= $pager->creat($jsf);
            }
            //-------------------------------------
            $htm .= '</div>';
            $htm .= '</div>';
        }else{
            $htm .= "<div class='table-responsive'>";
            $htm .= "<table class='table table-bordered table-sm' id='".$tableID."'>";
            $htm .= "<thead>";
            $htm .= "<tr class='bg-info'>";
            $farray = array();
            for($f=0;$f<$countCol;$f++){
                $onclick = '';
                $cursor = '';
                if($columns[$f]['order']!="none"){
                    $cursor = " cursor:pointer;";
                    $onclick = " onClick='".$jsf."(".$pageNumber.",".$columns[$f]['order'].")' ";
                }
                $farray[] = $columns[$f]['f'];
                $htm .= "<td style='width:".$columns[$f]['width'].";text-align: center;font-family: dubai-Regular;font-weight: bold;".$cursor."' ".$onclick." >".$columns[$f]['title']."</td>";
            }
            $countFar = count($farray);
            $htm .= "</tr>";
            $htm .= "</thead>";
            $htm .= "<tbody>";
            for($d=0;$d<$countRow;$d++){
                $txtcolor = '';
                $btnType = '';
                $icon = '';
                if(isset($rows[$d]['txtco'])){
                    $txtcolor = $rows[$d]['txtco'];
                }
                if(isset($rows[$d]['btnType'])){
                    $btnType = $rows[$d]['btnType'];
                }
                if(isset($rows[$d]['icon'])){
                    $icon = $rows[$d]['icon'];
                }
                $htm .= "<tr class='table-primary'>";
                for($f=0;$f<$countFar;$f++){
                    $currentField = $farray[$f];
                    $params = array();
                    switch ($currentField){
                        case 'checkBox':
                            $htm .= "<td style='border:1px solid #fff;text-align: center;'><input type='checkbox' rid='".$rows[$d]['RowID']."'>&nbsp;</td>";
                            break;
                        case 'radio':
                            $htm .= "<td style='border:1px solid #fff;text-align: center;'><input type='radio' name='".$rows[$d]['Name']."' value='".$rows[$d]['RowID']."'>&nbsp;</td>";
                            break;
                        case 'btn':
                           
                            $param = explode(',',$columns[$f]['param']);
                           
                            $countparam = count($param);
                            if(intval($countparam) == 1){
                                $btnClick = $columns[$f]['onclick'].'(\''.$rows[$d][$columns[$f]['param']].'\')';
                            }else{
                                for($x=0;$x<$countparam;$x++){
                                  
                                    $params[] = $rows[$d][$param[$x]];
                                }
                                $params = implode(',',$params);
                                $btnClick = $columns[$f]['onclick'].'('.$params.')';
                            }
                            $imgBtn = '<button type="button" class="btn '.(strlen(trim($btnType)) > 0 ? $btnType : 'btn-info').' btn-sm" '.(isset($rows[$d]['disabled']) ? $rows[$d]['disabled'] : '').' onclick='.$btnClick.'><i class="fas '.(strlen(trim($icon)) > 0 ? $icon : $columns[$f]['icon']).' fa-lg"></i>'.$columns[$f]['txt'].'</button>';
                            $htm .= "<td style='font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;'>".$imgBtn."&nbsp;</td>";
                            break;
                        case 'isEnableTxt':
                            $htm .= "<td style='font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;".(strlen(trim($txtcolor)) > 0 ? 'color:'.$txtcolor : '')."'>".$rows[$d][$currentField]."</td>";
                            break;
                        case 'status':
                            $htm .= "<td style='font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;".(strlen(trim($txtcolor)) > 0 ? 'color:'.$txtcolor : '')."'>".$rows[$d][$currentField]."</td>";
                            break;
                        default:
                            $htm .= "<td style='font-family:dubai-Regular;border:1px solid #fff;text-align: center;font-weight: bold;'>".$rows[$d][$currentField]."</td>";
                    }
                }
                $htm .="</tr>";
                $htm .= "</tbody>";
            }
            if(strlen($footerTxt) > 0){
                $htm .= "<tr style='background-color:#FFFFFF'>";
                $htm .= "<td colspan=".count($farray)." style='font-family:dubai-Regular;font-weight:bold;color: #c10000;font-size:16px;padding:10px;border:1px solid #ffffff;line-height: 35px;'> ".$footerTxt."</td>";
                $htm .= "</tr>";
                $htm .= "</tbody>";
            }
            $htm .= "</table>";
        }
        return $htm;
    }
}
