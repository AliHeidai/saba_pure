<?php
//error_reporting(E_ALL);
class FTP{

    public $ftp_server = '172.16.10.119'; // change this
    public $ftp_username = 'sabaftp'; // change this
    public $ftp_password = 'K%f&q1+u1T)B#*7G4j&A]x5Z'; // change this
    // public $ftp_username = 'administrator'; // change this
    // public $ftp_password = 'K%f&q1+u1T)B#*7G4j&A]x5Z'; // change this

    public function Upload($fileName,$local_file,$nowDate,$format, $return_link=false){
        $ut=new Utility();
        $date_array=explode('-',$nowDate);
        $year = $date_array[0];
        $month = sprintf('%02d',$date_array[1]);
        $server_file = "frp-saba/labels/".$year."/".$month."/".$format."/". $fileName;
        $ftpcon = ftp_connect($this->ftp_server) or die("Could not connect to $this->ftp_server");
        
        if (@ftp_login($ftpcon, $this->ftp_username, $this->ftp_password)){
                ftp_mkdir($ftpcon, "frp-saba/labels/".$year);
                ftp_mkdir($ftpcon, "frp-saba/labels/".$year."/".$month);
                ftp_mkdir($ftpcon, "frp-saba/labels/".$year."/".$month."/".$format);
             $server_file = "frp-saba/labels/".$year."/".$month."/".$format."/". $fileName;
            ftp_pasv($ftpcon, true);
            if (ftp_put($ftpcon, $server_file, $local_file, FTP_BINARY)) {
                if($return_link){
                    ftp_close($ftpcon);
                   return $this->ftp_server."/".$server_file;
                }
                else{
                    ftp_close($ftpcon);
                    return true;
                }
               
            } else {
               // error_log('Not Upload...');
                ftp_close($ftpcon);
                return false;
            }
            // Close FTP connection 
            ftp_close($ftpcon);
        } else {
            ftp_close($ftpcon);
           return false;
        }
    }

    public function ftp_directory_exists($ftp_conn, $dir) {
    // لیست فایل‌ها و پوشه‌های مسیر فعلی
    $contents = ftp_nlist($ftp_conn, ".");
    
    // بررسی وجود پوشه در لیست
    if (in_array($dir, $contents)) {
        return true;
    }
    return false;
}

    public function Download($fileName, $download = false) {


       // $server_file = "frp-saba/labels/".$fileName;
        $local_dir = "../temp_labeles/" ;  // Ensure this directory exists and is writable
        $current_date=date('Y-m-d H:i:s',strtotime('-2 hours'));
        $ut=new Utility();
        $db=new DBi();
        $sql="SELECT * FROM `label_attachment` WHERE `fileName` = '$fileName'";
        $res=$db->ArrayQuery($sql);
         $ut->fileRecorder($res);
        $file_date=$res[0]['createDate'];
        $date_array=explode('-',$file_date);
        $server_file="frp-saba/labels/".$date_array[0].'/'.sprintf("%02d",$date_array[1]).'/'.$res[0]['format'].'/'.$fileName;
     
        //-------------------------------delete all downloaded file  on dir label2-------------------------------------------------
       $files = glob($local_dir.'*'); // get all file names

       foreach($files as $file)
       {
            if (file_exists($file)) {
                $file_date=date ("Y-m-d H:i:s", filemtime($file));
                if($current_date>$file_date)
                {
                    error_log('unlink if');
                    unlink($file);
                
                }
                else{
                   // error_log('unlink else');
                }
            }

       }
 //-------------------------------delete all downloaded file  on dir label2-------------------------------------------------
        if (!$download) {
            return false;
        }

        $ftpcon = ftp_connect($this->ftp_server);
        if (!$ftpcon) {
           error_log('Could not connect to ' . $this->ftp_server);
            return false;
        }

        if (@ftp_login($ftpcon, $this->ftp_username, $this->ftp_password)) {
           // error_log('Logged in to FTP server');
            $ut->fileRecorder('login ftp');
            // Enable passive mode
            ftp_pasv($ftpcon, true);
            
            // Ensure the local directory exists and is writable
            if (!is_dir($local_dir) || !is_writable($local_dir)) {
               // error_log('Local directory does not exist or is not writable');
                ftp_close($ftpcon);
                return false;
            }

            // Try to download the file
            $local_file_path = $local_dir.$fileName;

            if (ftp_get($ftpcon, $local_file_path, $server_file, FTP_BINARY)) {
                error_log('File downloaded successfully: ' . $fileName);
                ftp_close($ftpcon);

                return $local_file_path;
            } else {
                error_log('Error downloading file: ' . $server_file);
                ftp_close($ftpcon);
                return false;
            }
        } else {
          error_log('FTP login failed');
          
            ftp_close($ftpcon);
            return false;
        }
    }

    public function Delete($fileName){
        $db=new DBi();
        $ut=new Utility();
        $sql="SELECT * FROM `label_attachment` WHERE `fileName` = '$fileName'";
        $res=$db->ArrayQuery($sql);
        $file_date=$res[0]['createDate'];
        $date_array=explode('-',$file_date);
        $file="frp-saba/labels/".$date_array[0].'/'.sprintf("%02d",$date_array[1]).'/'.$res[0]['format'].'/'.$fileName;
        
        $ftpcon = ftp_connect($this->ftp_server);
        if (!$ftpcon) {
           // error_log('Could not connect to ' . $this->ftp_server);
            return false;
        }
        if (@ftp_login($ftpcon, $this->ftp_username, $this->ftp_password)) {
            // error_log('Logged in to FTP server');
 
             // Enable passive mode
             ftp_pasv($ftpcon, true);
             //ftp_chdir($ftpcon, $logs_dir);
             if(ftp_delete($ftpcon, $file)){
                ftp_close($ftpcon);
                return true;
             }
             else{
                ftp_close($ftpcon);
                return false;
             }
        }
        else{
            ftp_close($ftpcon);
            return false;
        }
    }
}