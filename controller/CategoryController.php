<?php
/**
 * category 分类
 * 只有1级分类，比如插排就是一个分类，而不是家电分类下属的插排。因为插排也会出现在电脑配件里。
 * @author sink <sink.cup@gmail.com>
 */
class CategoryController extends BasicController
{
    private $category_model;

    public function __construct()
    {
        parent::__construct();
    }

    public function router($uri)
    {
        if(0 < preg_match('/^\/categories\/\w+\/attributes$/', $uri))
        {
            $tmp = explode('/', $uri);
            $this->category_model = new CategoryModel($tmp[2], self::$written_language_tag);
            switch (strtolower($_SERVER['REQUEST_METHOD']))
            {
                case 'get':
                    return $this->getAttributes($tmp[2]);
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
     * api GET categories/xxx/attibutes
     *
     * 取某个分类的属性
     *
     * demo curl -H 'Accept:application/json; version=0.2' -H 'Accept-Language:zh-cmn-Hans-CN' http://api.shaixuan.org/categories/cell_phone/attributes
     *
     */
    public function getAttributes()
    {
        try{
            return $this->category_model->getAttributes();
        }
        catch (ModelException $e)
        {
            throw new ControllerException(202);
        }
    }
}
?>
