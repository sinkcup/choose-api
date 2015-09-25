<?php
abstract class DbCrud {
    protected $db;
    protected $table_name;
    
    public function __construct() {
        if(empty($this->db)) {
            $db_engine = ConfigParserLib::get('db','using_db_engine');
            $db_engine_class_name = StrLib::underlineToClassName($db_engine . 'DbEngine');
            $db_server_name = ConfigParserLib::get('db','db_engine_to_server_name_map[\'' . $db_engine . '\']');
            $db_config = ConfigParserLib::get('system','db_servers[\''.$db_server_name.'\']');
            $this->db = new $db_engine_class_name($db_config);
        }
    }
    
    public function delete($where='') {
        return $this->db->delete($this->table_name,$where);
    }
    
    public abstract function insertRow($data);
    
    public abstract function insertRows($data);
    
    public function selectCount($where='', $column='*', $group_by='') {
        return $this->db->selectCount($this->table_name, $where, $column, $group_by);
    }
    
    public function selectRow($where='',$column='*',$limit_start=0,$group_by='',$order_by='') {
        return $this->db->selectRow($this->table_name,$where,$column,$limit_start,1,$group_by,$order_by);
    }
    
    public function selectRows($where='',$column='*',$limit_start=0,$limit_size='',$group_by='',$order_by='') {
        return $this->db->selectRows($this->table_name,$where,$column,$limit_start,$limit_size,$group_by,$order_by);
    }
    
    public abstract function update($where='',$data);
    
    public function query($sql) {
        return $this->db->query($sql);
    }
}
?>
