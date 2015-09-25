<?php
/**
 * mysqldump -hlocalhost -uroot -p1 -d test >mysql_schema.sql
 * php mysql_auto_process.php
 * @author sink
 */
//todo datatime timestamp update
function firstToUpper($s)
{
    $first = substr($s,0,1);
    $left = substr($s,1);
    return strtoupper($first).$left;
}
function tableNameToClassName($s)
{
    $tmp = explode('_',$s);
    $result = '';
    foreach($tmp as $value)
    {
        $result .= firstToUpper($value);
    }
    return $result.'Db';
}
function underlineToHump($s)
{
    $tmp = explode('_',$s);
    $result = $tmp[0];
    unset($tmp[0]);
    foreach($tmp as $value)
    {
        $result .= firstToUpper($value);
    }
    return $result;
}
function getInsertRowFunction($one_line,$column_name)
{
    $result = '';
    if((stripos($one_line,'char(')!==false)||(stripos($one_line,'` text')!==false)||(stripos($one_line,' datatime ')!==false)||(stripos($one_line,' timestamp ')!==false))
    {
        $result .= "        ".'if(isset($data[\''.$column_name.'\'])) {'
        ."\n            ".'$data_for_query[\''.$column_name.'\'] = trim($data[\''.$column_name.'\']);'
        ."\n        }\n";
    }
    else
    {
        if((stripos($one_line,'int(')!==false)||(stripos($one_line,'float(')!==false) ||(stripos($one_line,'double(')!==false))
        {
            $result .= "        ".'if(isset($data[\''.$column_name.'\'])) {'
            ."\n            ".'$data_for_query[\''.$column_name.'\'] = trim($data[\''.$column_name.'\']);'
            ."\n        }\n";
        }
    }
    if((stripos($one_line,'AUTO_INCREMENT')===false))
    {
        if((stripos($one_line,'NOT NULL')!==false)&&(stripos($one_line,' DEFAULT ')==false))
        {
            $result .= "        ".'else'
            ." {"
            ."\n            ".'return false;'
            ."\n        }\n";
        }
    }
    return $result;
}
function getInsertRowsFunction($one_line,$column_name)
{
    $result = '';
    if((stripos($one_line,'char(')!==false)||(stripos($one_line,'` text')!==false)||(stripos($one_line,' datatime ')!==false)||(stripos($one_line,' timestamp ')!==false))
    {
        $result .= "            ".'if(isset($value[\''.$column_name.'\']))'
        ." {"
        ."\n                ".'$tmp_data[\''.$column_name.'\'] = trim($value[\''.$column_name.'\']);'
        ."\n            }\n";
    }
    else
    {
        if((stripos($one_line,'int(')!==false)||(stripos($one_line,'float(')!==false) ||(stripos($one_line,'double(')!==false))
        {
            $result .= "            ".'if(isset($value[\''.$column_name.'\'])&&is_numeric(trim($value[\''.$column_name.'\'])))'
            ." {"
            ."\n                ".'$tmp_data[\''.$column_name.'\'] = trim($value[\''.$column_name.'\']);'
            ."\n            }\n";
        }
    }
    if((stripos($one_line,'AUTO_INCREMENT')===false))
    {
        if((stripos($one_line,'NOT NULL')!==false)&&(stripos($one_line,' DEFAULT ')==false))
        {
            $result .= "            ".'else {'
            ."\n                ".'return false;'
            ."\n            }\n";
        }
    }
    return $result;
}
function getUpdateFunction($one_line,$column_name)
{
    $result = '';
    if((stripos($one_line,'char(')!==false)||(stripos($one_line,'` text')!==false))
    {
        if((stripos($one_line,'NOT NULL')!==false))
        {
            $result .= "        ".'if(isset($data[\''.$column_name.'\']))';
        }
        else
        {
            $result .= "        ".'if(isset($data[\''.$column_name.'\']))';
        }
        $result .= " {"
        ."\n            ".'$data_for_query[\''.$column_name.'\'] = trim($data[\''.$column_name.'\']);'
        ."\n        }\n";
    }
    else
    {
        if((stripos($one_line,'int(')!==false)||(stripos($one_line,'float(')!==false))
        {
            if((stripos($one_line,'NOT NULL')!==false))
            {
                $result .= "        ".'if(isset($data[\''.$column_name.'\'])&&is_numeric(trim($data[\''.$column_name.'\'])))';
            }
            else
            {
                $result .= "        ".'if(isset($data[\''.$column_name.'\'])&&is_numeric(trim($data[\''.$column_name.'\'])))';
            }
            $result .= " {"
            ."\n            ".'$data_for_query[\''.$column_name.'\'] = trim($data[\''.$column_name.'\']);'
            ."\n        }\n";
        }else{
                
            if((stripos($one_line,'NOT NULL')!==false))
            {
                $result .= "        ".'if(isset($data[\''.$column_name.'\'])&&trim($data[\''.$column_name.'\']))';
            }
            else    
        {
                $result .= "        ".'if(isset($data[\''.$column_name.'\'])&&trim($data[\''.$column_name.'\']))';
                $result .= " {"
                ."\n            ".'$data_for_query[\''.$column_name.'\'] = trim($data[\''.$column_name.'\']);'
                ."\n        }\n";
            }
        }
    }
    return $result;
}
function convertComment($one_line,$column_name)
{
    $result = '';
    $end_pos = strripos($one_line,'`');
    $new = substr($one_line,$end_pos+1);
    if((stripos($new,'COMMENT')!==false))
    {
        $arr = explode('COMMENT',$new);
        $content = trim($arr[1]);
        $content = str_replace(array('\'',','),'',$content);
        $arr_2 = explode(' ',$content);
        $result .= "    ".'public static $'.$column_name.' = array(';

        for($i=1,$l=count($arr_2);$i<$l;$i=$i+2)
        {
            $result .= "\n        ".'\''.$arr_2[$i].'\' => \''.$arr_2[$i-1].'\'';
            if($i+2<$l)
            {
                $result .= ',';
            }
        }
        $result .= "\n    ".');'."\n";
    }
    return $result;
}
$file = fopen("mysql_schema.sql","r");
while(! feof($file))
{
    $one_line = '';
    $one_line = trim(fgets($file));
    if(stripos($one_line,'CREATE TABLE')!==false)
    {
        $start_pos = stripos($one_line,'`');
        $end_pos = strripos($one_line,'`');
        $l = $end_pos - $start_pos - 1;
        $table_name = substr($one_line,$start_pos+1,$l);
        $class_name = tableNameToClassName($table_name);
        $file_name = $class_name.'.php';
        $dao_file_content = '<?php'
        ."\n".'class '.$class_name.' extends DbCrud {'
        ."\n    ".'protected $table_name = \''.$table_name.'\';'."\n";
        echo $file_name."\n";
        $comment_result = '';
        $insert_row_function = "    ".'public function insertRow($data) {'."\n";
        $insert_rows_function = "    ".'public function insertRows($data) {'
        ."\n        ".'$column_name_array = array();'
        ."\n        ".'$data_for_query = array();'
        ."\n        ".'foreach($data as $value) {'
        ."\n            ".'$tmp_data = array();'."\n";
        //$select_rows_function = '';//继承
        //$select_row_function = '';//继承
        //$select_count_function = '';//继承
        $update_function = "    ".'public function update($where,$data) {'."\n";
        //$delete_function = '';//继承
        continue;
    }
    else
    {
        if(stripos($one_line,'`')===0)
        {
            $start_pos = stripos($one_line,'`');
            $end_pos = strripos($one_line,'`');
            $l = $end_pos - $start_pos - 1;
            $column_name = substr($one_line,$start_pos+1,$l);
            //echo $column_name.'';
            $comment_result .= convertComment($one_line,$column_name);
            $insert_row_function .= getInsertRowFunction($one_line,$column_name);
            $insert_rows_function .= getInsertRowsFunction($one_line,$column_name);
            $update_function .= getUpdateFunction($one_line,$column_name);
        }
        if(stripos($one_line,')')===0)
        {
            $insert_row_function .= "        ".'return $this->db->insertRow($this->table_name,$data_for_query);'
            ."\n    ".'}'."\n";
            $insert_rows_function .=
            "            ".'$data_for_query[] = $tmp_data;'
            ."\n        ".'}'
            ."\n        ".'return $this->db->insertRows($this->table_name,$data_for_query);'
            ."\n    ".'}'."\n";
            $update_function .= '        if(empty($data_for_query)) {';
            $update_function .= '            return true;';
            $update_function .= '        }';
            $update_function .= '        return $this->db->update($this->table_name,$where,$data_for_query);'
            ."\n    ".'}'."\n";
            $dao_file_content .= $comment_result."    \n".$insert_row_function."    \n".$insert_rows_function."    \n".$update_function.'}'."\n".'?>';
            file_put_contents('../'.$file_name,$dao_file_content);
            //break;
        }

    }
}
fclose($file);
?>
