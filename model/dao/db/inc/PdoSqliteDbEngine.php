<?php
class PdoSqliteDbEngine {
    private $connection;
    
    public function __construct($db_config) {
        $this->db_file_path = $db_config['path'];
        $this->getConnection();
    }
    
    public final function exec($sql) {
        return $this->connection->exec($sql);
    }
    
    public function getConnection() {
        $dsn = 'sqlite:' . $this->db_file_path;
        try {
            $this->connection = new PDO($dsn);
        }
        catch(PDOException $e) {
            error_log($e->getMessage());
        }
        return $this->connection;
    }
    
    public final function query($sql) {
        return $this->connection->query($sql);
    }

    public function implodeToColumn($data) {
        if($data=='*') {
            return '*';
        }
        if(is_array($data)) {
            $a = implode('`,`',$data);
            $b = '`'.$a.'`';
        }
        else {
            $b = '`'.$data.'`';
        }
        
        return $b;
    }
    
    public function implodeToRowValues($data) {
        if(is_array($data)) {
            $a = implode("','",$data);
        }
        else {
            $a = $data;
        }
        $b = "('".$a."')";
        return $b;
    }
    
    public function implodeToRowsValues($data) {
        foreach($data as $value) {
            $tmp = $this->implodeToRowValues($value);
            $row_array[] = $tmp;
        }
        $rows = implode(',',$row_array);
        return $rows;
    }
    
    public function implodeToWhere($data) {
        $a = '1';
        if(is_array($data)) {
            $a = '';
            foreach($data as $key=>$value) {
                if(is_array($value)) {
                    $part_1 = '`'.$key."` in (".implode(',',$value).') ';
                }
                else {
                    $part_1 = '`'.$key."` = '".$value."' ";
                }
                if(empty($a)) {
                        $a = $part_1;
                }
                else {
                        $a .= ' AND '.$part_1;
                }
            }
        }
        return $a;
    }
    public function implodeToUpdate($data) {
        $a = '';
        foreach($data as $column=>$value) {
            if(empty($a)) {
                $a = '`'.$column."`='".$value."'";
            }
            else {
                $a .= ',`'.$column."`='".$value."'";
            }
        }
        return $a;
    }
}
?>
