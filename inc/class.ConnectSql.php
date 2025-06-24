
<?php
class ConnectSql {
    private $conn;
    public function __construct(){
        try {
            $serverName = "192.168.43.129";
            $database = "empoly";
            $username = "sa";
            $password = "123456";
            
            $this->conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          
            //echo "اتصال موفقیت‌آمیز بود";
        } catch (PDOException $e) {
            die("خطا در اتصال: " . $e->getMessage());
        }
    }

    public function sql_ArrayQuery($query){
        try {
           
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            // تنظیم حالت بازیابی به آرایه انجمنی
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            return $stmt->fetchAll();
            foreach($stmt->fetchAll() as $row) {
                echo "ID: " . $row['id'] ."-". 'title:'.$row['title']. "<br>";
            }
        } catch(PDOException $e) {
           // echo "خطا در بازیابی داده: " . $e->getMessage();
        }
    }

}

