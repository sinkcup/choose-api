<?php
class ProductModel
{
    private $product_db;
    private $product_detail_db;
    private $product_file_db;
    private $product_attribute_value_wlp_db;
    private $written_language_id;
    
    public function __construct($category_name, $written_language_tag)
    {
        $language_model = new LanguageModel();
        $this->written_language_id = $language_model->getWrittenLanguageIdByTag($written_language_tag);
        $product_db_class_name = StrLib::underlineToClassName($category_name).'Db';
        $product_detail_db_class_name = StrLib::underlineToClassName($category_name).'DetailDb';
        $product_file_db_class_name = StrLib::underlineToClassName($category_name).'FileDb';
        $product_attribute_value_wlp_db_class_name = StrLib::underlineToClassName($category_name).'AttributeValueWlpDb';
        $this->product_db = new $product_db_class_name ();
        $this->product_detail_db = new $product_detail_db_class_name ();
        $this->product_file_db = new $product_file_db_class_name ();
        $this->product_attribute_value_wlp_db = new $product_attribute_value_wlp_db_class_name ();
    }

    public function addProduct($data)
    {
        $r = $this->product_db->insertRow($data);
        if($r === false)
        {
            throw new Exception(); //todo 应该在insertRow里面抛出
        }
        $data['product_id'] = $r;
        $data['written_language_id'] = $this->written_language_id;
        $this->product_detail_db->insertRow($data);
        if(isset($data['preview_img']))
        {
            $tmp = array
            (
                'product_id' => $r,
                'purpose' => 'preview_img',
            );
            foreach($data['preview_img'] as $one)
            {
                $tmp['filename'] = $one;
                $file_data[] = $tmp;
            }
            $this->product_file_db->insertRows($file_data);
        }
        return $r;
    }
    
    public function getProducts($where, $fields='*', $skip=0, $limit=1, $order_by='')
    {
        $columns = '*'; //todo
        $r = $this->product_db->selectRows($where, $columns, $skip, $limit, '',$order_by);
        if(empty($r))
        {
            if (isset($where['jd_id'])) {
                return RobotModel::getJdProduct($where['jd_id']);
            } elseif (isset($where['amazon_cn_id'])) {
                return RobotModel::getAmazonProduct($where['amazon_cn_id'], 'cn');
            }
            return array();
        }
        $products_id = array();
        $basic = array();
        foreach($r as $one)
        {
            $products_id[] = $one['id'];
            $basic[$one['id']] = $one;
        }
        $detail = $this->getProductsDetail($products_id);
        $data = array();
        foreach($basic as $key=>$value)
        {
            if(isset($detail[$key])) //如果basic里有，detail里没有，属于数据不完整。
            {
                $data[$key] = array_merge($value, $detail[$key]);
            }
        }
        $where = array
        (
            'product_id' => $products_id
        );
        $file = $this->product_file_db->selectRows($where);
        foreach($file as $one)
        {
            $data[$one['product_id']][$one['purpose']][] = $one['filename'];
        }
        return $data;
    }
    
    public function getProductsId($where,$limit_start,$limit_size,$order_by='')
    {
        $column = array(
            'id'
        );
        $result = $this->product_db->selectRows($where,$column,$limit_start,$limit_size);
        foreach($result as $row)
        {
            $products_id[] = $row['id'];
        }
        return $products_id;
    }
    
    public function getProductsDetail($products_id)
    {
        $where = array(
            'product_id' => $products_id,
            'written_language_id' => $this->written_language_id,
        );
        $column = array(
            'name',
            'product_id',
        );
        $result = $this->product_detail_db->selectRows($where,$column);
        foreach($result as $row)
        {
            $data[$row['product_id']] = $row;
        }
        return $data;
    }
    
    public function getProductsFileUri($products_id, $purpose)
    {
        $where = array(
            'product_id' => $products_id,
            'purpose' => $purpose,
        );
        $column = array(
            'product_id',
            'filename',
        );
        $result = $this->product_file_db->selectRows($where,$column);
        $static_server_uri = ConfigParser::get('system','static_server_uri');
        foreach($result as $row)
        {
            $data[$row['product_id']] = $static_server_uri . $row['filename'];
        }
        return $data;
    }
    
    public function updateById($id, $data)
    {
        $where = array
        (
            'id' => $id,
        );
        $r = $this->product_db->update($where, $data);
        $where = array
        (
            'product_id' => $id,
        );
        $r = $this->product_detail_db->update($where, $data);
        return $this->updateProductFile($id, $data);
    }
    
    public function updateProductFile($id, $data)
    {
        if(!isset($data['preview_img']))
        {
            return true;
        }
        $where = array
        (
            'product_id' => $id,
            'purpose' => 'preview_img',
        );
        $this->product_file_db->delete($where);
        foreach($data['preview_img'] as $one)
        {
            $d[] = array(
                'product_id' => $id,
                'filename' => $one,
                'purpose' => 'preview_img',
            );
        }
        return $this->product_file_db->insertRows($d);
    }
}
?>
