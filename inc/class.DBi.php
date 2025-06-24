<?php
class DBi {
    /**
     * @property Resource The connection field
     */
    private $con;

    /**
     * Database constructor
     * Initializes the object and connects to MySQL
     */
    public function __construct() {
        /*
        $this->con = new mysqli(HOST,USER,PASS,NAME);
        if($this->con->connect_errno){
            die('<p style="direction: ltr;font-weight: bold;">Failed to Connect , the error is : #'.$this->con->connect_errno.' = '.$this->con->connect_error.'</p>');
        }
        $this->con->query('SET NAMES \'utf8\'');
        $this->con->set_charset('utf8');
        */
		// if(isset($_SESSION['userid'])){
        //     if($_SESSION['userid']==141){
        //         $user="moradi_x";
        //         $pass="j*RaiIY@4Dfr$mj%76";
               
        //     }
        //     else{
                $user=USER;
                $pass=PASS;
           // }
       // }
        $this->con = mysqli_connect(HOST, $user, $pass) or die('Connection error');
        mysqli_select_db($this->con,NAME) or die('Database error');
        $this->Query('SET NAMES \'utf8\'');
        mysqli_set_charset($this->con,'utf8');
        mysqli_options($this->con, MYSQLI_OPT_LOCAL_INFILE, true);
       
    }

    /**
     * How many rows affected?
     * @return mysqli
     */
    public function Getcon(){
        return $this->con;
    }

    public function AffectedRows() {
        if($this->con) {
            return mysqli_affected_rows($this->con);
        }
        return 0;
    }

    /**
     * Executes a select query and return the result as standard PHP array
     * @param string $query The select query to execute
     * @return array The result array
     */
    public function ArrayQuery($query) {
      
        $result = array();
        if($this->con) {
            $rows = $this->Query($query);
            if($rows && mysqli_num_rows($rows) > 0) {
                while($row = mysqli_fetch_assoc($rows)) {
                    $result[] = $row;
                }
            }
        }
        error_log($query);
        return $result;
    }

    public function ArrayQueryCustom($query,$col_name) {
        $result = array();
        if($this->con) {
            $rows = $this->Query($query);
            if($rows && mysqli_num_rows($rows) > 0) {
                while($row = mysqli_fetch_assoc($rows)) {
                    $result[] = $row[$col_name];
                }
            }
        }
        return $result;
    }

    /**
     * Execute a query and return the result as a MySQL resource
     * @param string $query The query to execute
     * @return resource|boolean The result resource if connection exists, false otherwise
     */
  /*   public function Query($query) {
        if($this->con) {
            return mysqli_query($this->con,$query);
        }
        return 0;
    } */

    function getTableNameFromQuery($query) {
        // تبدیل کوئری به حروف کوچک برای ساده‌تر کردن تجزیه و تحلیل
        $query = strtolower($query);
    
        // تعریف الگوهای رایج برای شناسایی نام جدول
        $patterns = [
            '/from\s+([a-z0-9_]+)/', // برای SELECT, DELETE, UPDATE
            '/into\s+([a-z0-9_]+)/', // برای INSERT INTO
            //'/join\s+([a-z0-9_]+)/',  // برای JOIN
        ];
    
        // جستجو در کوئری برای یافتن نام جدول
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $query, $matches)) {
                return $matches[1]; // بازگرداندن نام جدول
            }
        }
    
        return null; // اگر نام جدول پیدا نشد
    }
    
    // مثال استفاده
    // $query = "SELECT * FROM users WHERE id = 1";
    // $tableName = getTableNameFromQuery($query);
    // echo "Table Name: " . $tableName; // خروجی: Table Name: users
    public function get_edit_create_status(){

        $query="SELECT * FROM menu_crud_setting";
        if($this->con) {
            $rows= mysqli_query($this->con,$query);
          
            if($rows && mysqli_num_rows($rows) > 0) {
                while($row = mysqli_fetch_assoc($rows)) {
                    $result[] = $row;
                }
            }
        }
       
        return $result[0];
    }

    public function redirect_force($url="../error.php")
    {
        if(curl_init($url) == false)
        {
            $url='../error.php';
        } 
        if (!headers_sent()){
            header("Location: $url");
           
        }else{
            echo "<script type='text/javascript'>window.location.href='$url'</script>";
            echo "<noscript><meta http-equiv='refresh' content='0;url=$url'/></noscript>";
        }
        exit;
    }

    	
	public function Query($query,$forc=0) {
        $query_flag=0;
       // $table_name=$this->getTableNameFromQuery($query);
        
        // if($forc==0)
        // {
          
        //     if (preg_match('/^INSERT\\s+INTO/i', $query)) {
        //         $create_setting=$this->get_edit_create_status();
        //         if($create_setting['create']==0){
        //         $this->redirect_force();
        //             return false;

        //         }
                    
        //     } 

        //     if (preg_match('/\bupdate\b/i',$query)) {
            
        //         $update_setting = $this->get_edit_create_status();
                
        //         if($update_setting['update']==0){
        //             $this->redirect_force();
        //             return false;
        //         }
                
        //     } 
        
        //     if (preg_match('/\bdelete\b/i',$query)) {
        //         $delete_setting=$this->get_edit_create_status();
            
        //         if($delete_setting['delete']==0){
        //             $this->redirect_force();
        //             return false;
        //         }
            
        //     }
        //     $query_flag=1; 
    // }
    // else{
    //     $query_flag=1;
    // }
     $query_flag=1;
       

        if($this->con &&  $query_flag==1) {
            $res= mysqli_query($this->con,$query);
        }
        if($res){		
            return $res;
        }
        else
        {
            $origin_file_info=debug_backtrace()[1];
            $origin_file_info_final=array('file_name'=>$origin_file_info['file'],'line_number'=>$origin_file_info['line'],'function'=>$origin_file_info['function']);
            $origin_file_info_final=json_encode($origin_file_info_final);
            $origin_file_info_final=$this->con->real_escape_string($origin_file_info_final);
     
            $cur_date=date('Y-m-d H:i:s');
            $query=$this->con->real_escape_string($query);
            $error=$this->con->error;
            $error=$this->con->real_escape_string($error);
            $query_log="insert into abrash_log (
                                query,
                                system_ip,
                                username,
                                dateTimeCreated,
                                error_message,
                                origin
                            )
                            VALUES(
                                '{$query}',
                                '{$_SERVER['REMOTE_ADDR']}',
                                '{$_SESSION['userid']}',
                                '{$cur_date}',
                                '{$error}',
                                '{$origin_file_info_final}'
                            )";
        
            $result=mysqli_query($this->con,$query_log);
           
            return 0;
        }
    }

	
	// public function Query($query) {
	// 	$ut=new Utility();
    //     if($this->con) {
    //         $res= mysqli_query($this->con,$query);
    //     }
    //     if($res){		
    //         return $res;
    //     }
    //     else
    //     {
    //         $origin_file_info=debug_backtrace()[1];
    //         $origin_file_info_final=array('file_name'=>$origin_file_info['file'],'line_number'=>$origin_file_info['line'],'function'=>$origin_file_info['function']);
    //         $origin_file_info_final=json_encode($origin_file_info_final);
    //         $origin_file_info_final=$this->con->real_escape_string($origin_file_info_final);
     
    //         $cur_date=date('Y-m-d H:i:s');
    //         $query=$this->con->real_escape_string($query);
    //         $error=$this->con->error;
    //         $error=$this->con->real_escape_string($error);
    //         $query_log="insert into abrash_log (
    //                             query,
    //                             system_ip,
    //                             username,
    //                             dateTimeCreated,
    //                             error_message,
    //                             origin
    //                         )
    //                         VALUES(
    //                             '{$query}',
    //                             '{$_SERVER['REMOTE_ADDR']}',
    //                             '{$_SESSION['userid']}',
    //                             '{$cur_date}',
    //                             '{$error}',
    //                             '{$origin_file_info_final}'
    //                         )";
        
    //         $result=mysqli_query($this->con,$query_log);
           
    //         return 0;
    //     }
    // }

    /**
     * Escape a value to use safely in queries
     * @param string $value The value to escape
     * @return string|boolean The escaped value if connection exists, false otherwise
     */
    public function Escape($value) {
        if($this->con) {
            return mysqli_real_escape_string($this->con,$value);
        }
        return false;
    }

    public function InsertrdID(){
        if($this->con) {
            return mysqli_insert_id($this->con);
        }
        return false;
    }

    public function FetchAssoc($result) {
        if($this->con) {
            return mysqli_fetch_assoc($result);
        }
        return 0;
    }
}