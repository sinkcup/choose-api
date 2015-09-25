<?php
class MysqliDbEngine {
    private $connection;
    
    public function __construct($db_config) {
        $this->connection = $this->getConnection($db_config);
    }
    
    public function delete($table_name,$where='') {
        $sql = 'DELETE FROM `'.$table_name.'`';
        if(!empty($where)) {
            $sql .= ' WHERE '.self::implodeToWhere($where);
        }
        $result = $this->connection->query($sql);
        if($result === false) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function getConnection($db_config) {
        try {
            $connection = new mysqli($db_config['host'], $db_config['username'], $db_config['password'], $db_config['db_name'],$db_config['port']);
            $connection->set_charset($db_config['encoding']);
            return $connection;
        }
        catch(Exception $e) {
            throw new Exception($connection->error);
        }
    }
    
    public static function implodeToColumn($data) {
        if($data=='*') {
            return '*';
        }
        if(is_array($data)) {
            $a = implode('`,`', $data);
            $b = '`'.$a.'`';
        }
        else {
            $b = '`'.$data.'`';
        }
        
        return $b;
    }
    
    public static function implodeToRowValues($data) {
        if(is_array($data)) {
            $tmp = array();
            foreach($data as $one) {
                $tmp[] = addslashes($one);
            }
            $a = implode("','", $tmp);
        }
        else {
            $a = addslashes($data);
        }
        $b = "('".$a."')";
        return $b;
    }
    
    public static function implodeToRowsValues($data) {
        foreach($data as $value) {
            $tmp = self::implodeToRowValues($value);
            $row_array[] = $tmp;
        }
        $rows = implode(',',$row_array);
        return $rows;
    }
    
    public static function implodeToWhere($data) {
        $a = '1';
        if(is_array($data)) {
            $a = '';
            foreach($data as $key=>$value) {
                if(is_array($value)) {
                    $tmp = array();
                    foreach($value as $one) {
                        $tmp[] = '\'' . addslashes($one) . '\'';
                    }
                    $part_1 = '`' . $key . '` in (' . implode(',', $tmp) . ') ';
                }
                else
                {
                    if(0 < preg_match('/[0-9]+\.[0-9]$/', $value)) //float不能用单引号
                    {
                        $part_1 = '`' . $key . '` = ' . $value;
                    }
                    else
                    {
                        $part_1 = '`'.$key."` = '" . addslashes($value) . "' ";
                    }
                }
                if(empty($a))
                {
                    $a = $part_1;
                }
                else
                {
                    $a .= ' AND '.$part_1;
                }
            }
        }
        return $a;
    }
    public static function implodeToUpdate($data) {
        if(empty($data)) {
            return '';
        }
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
    
    public function insertRow($table_name,$data) {
        $sql = 'INSERT INTO `'.$table_name.'` ('.self::implodeToColumn(array_keys($data)).') VALUES '.self::implodeToRowValues(array_values($data));
        $result = $this->connection->query($sql);
        if($result === false) {
            return false;
        }
        else {
            return $this->connection->insert_id;
        }
    }
    
    public function insertRows($table_name,$data) {
        $column_name_array = array();
        $data_for_query = array();
        $column_name_array = array_keys($data[0]);
        foreach($data as $one_row) {
            $data_for_query[] = array_values($one_row);
        }
        $sql = 'INSERT INTO `'.$table_name.'` ('.self::implodeToColumn(array_values($column_name_array)).') VALUES '.self::implodeToRowsValues($data_for_query);
        $result = $this->connection->query($sql);
        if($result === false) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function query($sql) {
        $result = $this->connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function selectCount($table_name,$where='') {
        $sql = 'SELECT COUNT(*) FROM `'.$table_name.'`';
        if(!empty($where)) {
            $sql .= ' WHERE '.self::implodeToWhere($where);
        }
        $result = $this->connection->query($sql);
        $result_1 = $result->fetch_row();
        return $result_1[0];
    }
    
    public function selectRow($table_name,$where='',$column='*',$limit_start=0,$group_by='',$order_by='') {
        $result = $this->selectRows($table_name,$where,$column,$limit_start,1,$group_by,$order_by);
        if($result === false) {
            return false;
        }
        else {
            return empty($result) ? array() : $result[0];
        }
    }
    
    public function selectRows($table_name,$where='',$column='*',$limit_start=0,$limit_size='',$group_by='',$order_by='') {
        
        $sql = 'SELECT '.self::implodeToColumn($column).' FROM `'.$table_name.'`';
        if(!empty($where)) {
            $sql .= ' WHERE '.self::implodeToWhere($where);
        }
        $sql = !empty($group_by) ? $sql.' GROUP BY '.$group_by : $sql;
        $sql = !empty($order_by) ? $sql.' ORDER BY '.$order_by : $sql;
        if(!empty($limit_size)) {
            $sql .= ' LIMIT '.$limit_start.','.$limit_size;
        }
        $result = $this->connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function update($table_name,$where,$data) {
        $sql = 'UPDATE `'.$table_name.'` SET '.self::implodeToUpdate($data);
        if(!empty($where)) {
            $sql .= ' WHERE '.self::implodeToWhere($where);
        }
        $result = $this->connection->query($sql);
        if($result === false) {
            return false;
        }
        else {
            return true;
        }
    }
}
?>
