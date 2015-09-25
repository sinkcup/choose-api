<?php
class PdoMysqlDbEngine {
    private $connection;
    
    public function __construct($db_config) {
        $this->connection = $this->getConnection($db_config);
    }
    
    public function delete($table_name,$where='') {
        $sql = 'DELETE FROM `'.$table_name.'`';
        if(!empty($where)) {
            $sql .= ' WHERE ' . MysqliDbEngine::implodeToWhere($where);
        }
        $result = $this->connection->exec($sql);
        if($result > 0) {
            return true;
        }else {
            $r = $this->selectCount($table_name,$where);
            if($r == 0) {
                return true;
            }
        }
        return false;
    }
    
    public function getConnection($db_config) {
        $str = 'mysql:host='.$db_config['host'].';port='.$db_config['port'].';dbname='.$db_config['db_name'];
        $connection = new PDO($str, $db_config['username'], $db_config['password']);
        try {
            $connection->exec('SET NAMES \'' . $db_config['encoding'].'\';');
            return $connection;
        }
        catch(PDOException $e) {
            throw new Exception($connection->errorCode()); //todo 出错时未定义变量
        }
    }
    
    public function insertRow($table_name,$data) {
        $sql = 'INSERT INTO `'.$table_name.'` ('.MysqliDbEngine::implodeToColumn(array_keys($data)).') VALUES '.MysqliDbEngine::implodeToRowValues(array_values($data));
        $result = $this->connection->exec($sql);
        if($result===false) {
            return false;
        }
        else {
            return $this -> connection -> lastInsertId();
            //return true;
        }
    }
    
    public function insertRows($table_name,$data) {
        $column_name_array = array();
        $data_for_query = array();
        $column_name_array = array_keys($data[0]);
        foreach($data as $one_row) {
            $data_for_query[] = array_values($one_row);
        }
        $sql = 'INSERT INTO `'.$table_name.'` ('.MysqliDbEngine::implodeToColumn(array_values($column_name_array)).') VALUES '.MysqliDbEngine::implodeToRowsValues($data_for_query);
        $result = $this->connection->exec($sql);
        if($result===false) {
            return false;
        }
        else {
            return true;
        }
    }

    public function query($sql) {
        $result = $this->connection->query($sql);
        if(empty($result)) {
            return array();
        }
        $result->setFetchMode(PDO::FETCH_ASSOC);
        return $result->fetchAll();
    }

    public function selectCount($table_name, $where='', $column='*', $group_by='') {
        if(!empty($column) && $column!='*') {
            $sql = 'SELECT COUNT(`' . $column . '`) as count';
        }
        else {
            $sql = 'SELECT COUNT(*) as count';
        }
        $sql .= empty($group_by) ? '' : ',' . $group_by;
        $sql .= ' FROM `'.$table_name.'`';
        if(!empty($where)) {
            $sql .= ' WHERE ' . MysqliDbEngine::implodeToWhere($where);
        }
        $sql = !empty($group_by) ? $sql . ' GROUP BY ' . $group_by : $sql;
        $result = $this->connection->query($sql);
        if(empty($group_by)) {
            return $result->fetchColumn();
        }
        else {
            return $result->fetchAll();
        }
    }
    
    public function selectRow($table_name,$where='',$column='*',$limit_start=0,$group_by='',$order_by='') {
        $result = $this->selectRows($table_name,$where,$column,$limit_start,1,$group_by,$order_by);
        if($result===false) {
            return false;
        }
        else {
            return empty($result) ? array() : $result[0];
        }
    }
    
    public function selectRows($table_name,$where='',$column='*',$limit_start=0,$limit_size='',$group_by='',$order_by='') {
        $sql = 'SELECT '.MysqliDbEngine::implodeToColumn($column).' FROM `'.$table_name.'`';
        if(!empty($where)) {
            $sql .= ' WHERE '.MysqliDbEngine::implodeToWhere($where);
        }
        $sql = !empty($group_by) ? $sql.' GROUP BY '.$group_by : $sql;
        $sql = !empty($order_by) ? $sql.' ORDER BY '.$order_by : $sql;
        if(!empty($limit_size)) {
            $sql .= ' LIMIT '.$limit_start.','.$limit_size;
        }
        $result = $this->connection->query($sql);
        if($result === false) {
            return false;
        }
        else {
            $result->setFetchMode(PDO::FETCH_ASSOC);
            return $result->fetchAll();
        }
    }
    
    public function update($table_name,$where,$data) {
        $sql = 'UPDATE `'.$table_name.'` SET '.MysqliDbEngine::implodeToUpdate($data);
        if(!empty($where)) {
            $sql .= ' WHERE '.MysqliDbEngine::implodeToWhere($where);
        }
        $result = $this->connection->exec($sql);
        if($result===false) {
            return false;
        }
        else {
            return true;
        }
    }
}
?>
