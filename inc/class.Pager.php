<?php

class Pager{

    public $count;
    public $currentPage;

    public function __construct ($count, $currentPage = 1){
        $this->count = $count;
        if(!intval($currentPage)) {
            $currentPage = 1;
        }
        $this->currentPage = $currentPage;
    }

    public function creat($click){
        if(intval($this->count)){
            $pageCount = floor($this->count / LISTCNT);
            if (intval($this->count % LISTCNT)) {
                $pageCount++;
            }
            $htm = "";
            $htm .= '<ul class="pagination">';
            $Pclass = "";
            $Pclick = "onclick=" . $click . "(" . ($this->currentPage - 1) . ")";
            if ($this->currentPage == 1) {
                $Pclass = " class='disabled' ";
                $Pclick = "";
            }
            $htm .= '<li ' . $Pclass . '><a ' . $Pclick . ' title="قبلی" style="cursor:pointer">&laquo;</a></li>';
            for ($c = 1; $c <= $pageCount; $c++) {
                if ($this->currentPage == ($c)) {
                    $htm .= '<li class="active"><a>' . ($c) . '<span class="sr-only">(current)</span></a></li>';
                } else {
                    $htm .= '<li><a onclick="' . $click . '(' . $c . ')" style="cursor:pointer" >' . ($c) . '</a></li>';
                }

            }
            $Nclick = "onclick=" . $click . "(" . ($this->currentPage + 1) . ")";
            $Nclass = "";
            if ($this->currentPage == $pageCount) {
                $Nclass = " class='disabled' ";
                $Nclick = "";
            }
            $htm .= '<li ' . $Nclass . ' ><a title="بعدی" ' . $Nclick . ' style="cursor:pointer">&raquo;</a></li>';
            $htm .= '</ul>';
            return $htm;
        }
    }

    public function createGoToPage($func){
        if(intval($this->count)){
            $pageCount = floor($this->count / LISTCNT);
            if (intval($this->count % LISTCNT)) {
                $pageCount++;
            }
            $htm = "";
            $htm .= '<div class="form-inline">';
            $htm .= '<div class="form-group">';
            $htm .= '<button type="button" class="btn btn-danger" onClick="goToFirstPage(' . $this->currentPage . ',\'' . $func . '\')" style="width:30px;height:20px;margin:5px;padding:0px;" ><i class="fa fa-fast-forward"></i></button>';
            $htm .= '<button type="button" class="btn btn-success" onClick="prev(' . $this->currentPage . ',\'' . $func . '\')" style="width:30px;height:20px;margin:5px;padding:0px;" ><i class="fa fa-step-forward"></i></button>';
            $htm .= "<span style=''> صفحه ";
            $htm .= '<input type="text" class="form-control" id="goToPageInput_'.rand(1000000,9999999).'" onKeyDown="goToPage(event,this,\'' . $func . '\',' . $pageCount . ',' . $this->currentPage . ');" style="width:50px;height:25px;" value="' . $this->currentPage . '">';
            $htm .= " از " . $pageCount . "</span>";
            $htm .= '<button type="button" class="btn btn-success" onClick="next(' . $this->currentPage . ',' . $pageCount . ',\'' . $func . '\')" style="width:30px;height:20px;margin:5px;padding:0px;" ><i class="fa fa-step-backward fa-sm"></i></button>';
            $htm .= '<button type="button" class="btn btn-danger" onClick="goToLastPage(' . $this->currentPage . ',' . $pageCount . ',\'' . $func . '\' )" style="width:30px;height:20px;margin:5px;padding:0px;" ><i class="fa fa-fast-backward"></i></button>';
            $htm .= '</div>';
            $htm .= '</div>';
            return $htm;
        }
    }
}  

