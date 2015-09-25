<?php
class CategoryModel
{
    private $product_db;
    private $product_attribute_value_wlp_db;
    private $written_language_id;
    
    public function __construct($category_name,$written_language_tag)
    {
        $language_model = new LanguageModel();
        $this->written_language_id = $language_model->getWrittenLanguageIdByTag($written_language_tag);
        $product_db_class_name = StrLib::underlineToClassName($category_name) . 'Db';
        $product_attribute_value_wlp_db_class_name = StrLib::underlineToClassName($category_name).'AttributeValueWlpDb';
        $this->product_db = new $product_db_class_name ();
        $this->product_attribute_value_wlp_db = new $product_attribute_value_wlp_db_class_name ();
    }
    
    /**
     * 
     * @return array(
        'vendor' => array(
            '1' => 'AMD',
            '2' => 'Intel（英特尔）',
        ),
        'socket' => array(
            '1' => 'AM3',
            '2' => 'LGA 1155',
        ),
        'cores_count' => array(
            '1' => '单核',
            '2' => '双核',
            '3' => '3核',
        )
    )
     */
    public function getAttributes()
    {
        $where = array
        (
            'written_language_id' => $this->written_language_id
        );
        $column = array('attribute_name', 'attribute_value', 'display');error_log(var_export($where, true));
        $r = $this->product_attribute_value_wlp_db->selectRows($where, $column);
        $data = array();
        foreach($r as $value)
        {
            $data[$value['attribute_name']][$value['attribute_value']] = $value['display'];
        }
        return $data;
    }
    
    public function getCategoryNameById($category_id)
    {
        $where = array(
            'id' => $category_id
        );
        $column = array('name');
        $result = $this->category_db->selectRow($where,$column);
        return $result['name'];
    }
    
    public function getAllCategoriesIdAndName()
    {
        $where = array(
            
        );
        $result = $this->category_db->selectRows($where);
        $data = array();
        foreach($result as $value)
        {
            $data[$value['id']] = $value['name'];
        }
        return $data;
    }
    
    public function getAllCategoriesWlp($written_language_id)
    {
        return array(
            '1' => 'CPU',
            '2' => '显卡',
        );
        $where = array(
            'name' => $name
        );
        $result = $this->user_db->selectRow($where);
        return $result;
    }
    
    public function getAllCategoriesNameAndDisplay($written_language_id)
    {
        $where = array(
            'written_language_id' => $written_language_id
        );
        $column = array('display','name');
        $result = $this->category_wlp_db->selectRows($where,$column);
        $data = array();
        foreach($result as $value)
        {
            $data[$value['name']] = $value['display'];
        }
        return $data;
    }
}
?>
