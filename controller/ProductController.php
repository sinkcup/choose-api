<?php
/**
 * product 商品
 * @author sink <sink.cup@gmail.com>
 */
class ProductController extends BasicController
{
    private $product_model;

    public function __construct()
    {
        parent::__construct();
    }

    public function router($uri)
    {
        if(0 < preg_match('/^\/products\/\w+\/basic$/', $uri))
        {
            $tmp = explode('/', $uri);
            $map = ConfigParserLib::get('category', 'category_map');
            $category_name = $map[$tmp[2]];
            $this->product_model = new ProductModel($category_name, self::$written_language_tag);
            switch (strtolower($_SERVER['REQUEST_METHOD']))
            {
                case 'get':
                    return $this->getBasic();
                    break;
            }
        }
        if(0 < preg_match('/^\/products\/\w+$/', $uri))
        {
            $tmp = explode('/', $uri);
            $map = ConfigParserLib::get('category', 'category_map');
            $category_name = $map[$tmp[2]];
            $this->product_model = new ProductModel($category_name, self::$written_language_tag);
            switch (strtolower($_SERVER['REQUEST_METHOD']))
            {
                case 'post':
                    return $this->add();
                    break;
            }
        }

        if(0 < preg_match('/^\/products\/\w+\/[0-9]+$/', $uri))
        {
            $tmp = explode('/', $uri);
            $id = $tmp[3];
            $map = ConfigParserLib::get('category', 'category_map');
            $category_name = $map[$tmp[2]];
            $this->product_model = new ProductModel($category_name, self::$written_language_tag);
            switch (strtolower($_SERVER['REQUEST_METHOD']))
            {
                case 'get':
                    return $this->get($id);
                    break;
                case 'put':
                    return $this->update($id);
                    break;
            }
        }
        $error = array
        (
            'code' => '501',
            'msg' => '没有这个功能'
        );
        throw new ControllerException(json_encode($error));
    }

    /**
     * api GET products/{category_name}
     *
     * 添加一个商品
     *
     * demo curl -d 'preview_img=["99469ff033e2760b3a2778338270df18.jpg","8877c0521eb0aa8e577246cc9a604acc.png"]&name=HTC EVO 3D&os=1&brand=1&is_support_wcdma=1&is_support_gsm=1&display_size=4.3&cpu_core_count=2&ram=1024&rom=1000' -H 'Accept:application/json; version=0.2' -H 'Accept-Language:zh-cmn-Hans-CN' 'http://api.shaixuan.org/products/cell_phones'
     *
     */
    public function add()
    {
        try
        {
            $data = self::$input;
            if(isset($data['preview_img']))
            {
                $data['preview_img'] = json_decode($data['preview_img'], true);
            }
            return $this->product_model->addProduct($data);
        }
        catch (Exception $e)
        {
            throw new ControllerException(202);
        }
    }

    /**
     * api GET products/{category_name}/basic
     *
     * 按条件查一些商品的基本信息
     *
     * demo curl -H 'Accept:application/json; version=0.2' -H 'Accept-Language:zh-cmn-Hans-CN' 'http://api.shaixuan.org/products/cell_phones/basic?os=1&brand=1&skip=0&limit=2&order_by=price&order_desc=1'
     *
     */
    public function getBasic()
    {
        try
        {
            $where = self::$input;
            if (isset($where['uri'])) {
                $tmp = explode('/', $where['uri']);
                switch ($tmp[2]) {
                    case 'item.jd.com':
                        $tmp1 = explode('.', $tmp[3]);
                        $where['jd_id'] = $tmp1[0];
                    case 'www.amazon.cn':
                        //亚马逊国外采用一种链接格式：
                        // http://www.amazon.com/dp/B00OQVZDJM/ref=ods_fs_kp_m
                        //而亚马逊中国有两种链接：
                        // 1、 http://www.amazon.cn/gp/product/B00QJDOLIO/ref=fs_km
                        // 2、 http://www.amazon.cn/dp/B00DPAZAT8?psc=1
                        // 第1种如果把product去掉，dp改成gp，也可以打开。所以全都改成第2种。
                        $path = parse_url($where['uri'])['path'];
                        $tmp1 = explode('/dp/', $path);
                        if (!isset($tmp1[1])) {
                            $tmp1 = explode('/gp/product/', $path);
                        }
                        $tmp2 = explode('/', $tmp1[1]);
                        $where['amazon_cn_id'] = $tmp2[0];
                }
            }
            $skip = isset($where['skip']) ? $where['skip'] : 0;
            $limit = isset($where['limit']) ? $where['limit'] : 1;
            $fields = '*'; //todo
            $order_by = '';
            if(isset($where['order_by']))
            {
                $order_by = $where['order_by'];
                if(isset($where['order_desc']) && $where['order_desc'] == 1)
                {
                    $order_by .= ' desc';
                }
            }
            unset($where['skip']);
            unset($where['limit']);
            unset($where['order_by']);
            unset($where['order_desc']);
            return $this->product_model->getProducts($where, $fields, $skip, $limit, $order_by);
        }
        catch (Exception $e)
        {
            throw new ControllerException(202);
        }
    }

    /**
     * api GET products/{category_name}/{id}
     *
     * 查一个商品
     *
     * demo curl -H 'Accept:application/json; version=0.2' -H 'Accept-Language:zh-cmn-Hans-CN' 'http://api.shaixuan.org/products/cell_phones/9'
     *
     */
    public function get($id)
    {
        try
        {
            $where = array
            (
                'id' => $id
            );
            $r = $this->product_model->getProducts($where);
            $tmp = array_values($r);
            return $tmp[0];
        }
        catch (Exception $e)
        {
            throw new ControllerException(404);
        }
    }

    /**
     * api PUT products/{category_name}/{id}
     *
     * 修改一个商品
     *
     * demo curl -X PUT -d 'preview_img=["65f48f50293c7e3594ddc659f81704b6.jpg"]' -H 'Accept:application/json; version=0.2' -H 'Accept-Language:zh-cmn-Hans-CN' 'http://api.shaixuan.org/products/monitors/2'
     *
     */
    public function update($id)
    {
        try
        {
            $d = self::$input;
            if(isset($d['preview_img']))
            {
                $d['preview_img'] = json_decode($d['preview_img'], true);
            }
            $r = $this->product_model->updateById($id, $d);
            return array();
        }
        catch (Exception $e)
        {
            throw new ControllerException(404);
        }
    }
}
?>
