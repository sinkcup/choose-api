<?php
/**
 * 入口
参考HTTP status code，方案1：一直正常返回，在内容中使用code。方案2：直接使用HTTP status code。
200 正常
202 结果错误
400 参数不足
401 未登录
404 没找到此blog、user
501 没有这个功能
规则：
uri表示资源
uri里不使用动词，因为动作使用GET、POST、PUT、DELETE表示
比如login是动词，不能出现在uri里，可以使用“生成认证”，即POST oauth/token，不能使用GET，因为认证不是一个资源。
参考资料：
http://www.ruanyifeng.com/blog/2011/09/restful.html
 */

/**
 * 获取环境变量
 * @param $key
 * @param null $default
 * @return null|string
 */
function env($key, $default = null)  
{
    $value = getenv($key);
    if ($value === false) {
        return $default;
    }
    return $value;
}

require dirname(__FILE__) . '/../lib/auto_load.php';

$tmp = explode(';', $_SERVER['HTTP_ACCEPT']); //比如application/json; version=0.2
//考虑复杂情况，比如text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
$content_type = '';
foreach($tmp as $one)
{
    $one = strtolower(trim($one));
    if(empty($content_type) && stripos($one, 'application/json')!==false)
    {
        $content_type = 'application/json';
        continue;
    }
    if(empty($content_type) && stripos($one, 'application/xml')!==false)
    {
        $content_type = 'application/xml';
        continue;
    }
    if(stripos($one, 'version')===0)
    {
        $version = substr($one, stripos($one, '=')+1);
        continue;
    }
}
$controller_map = array
(
    'categories' => 'category',
    'products' => 'product',
    'qiniu' => 'qiniu',
);
try{
    $request_method = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI']; //比如 /users/1
    $pos = strpos($uri,'?');
    if($pos !== false){
        $uri = substr($uri, 0, $pos);
    }
    $uri_array = explode('/',$uri);
    
    //移除空
    array_shift($uri_array); //结果array('users', '1')
    //判断类是否存在
    if(!isset($controller_map[$uri_array[0]]))
    {
        throw new ControllerException(501);
    }
    $controller_prefix = $controller_map[$uri_array[0]];
    $class_name = StrLib::underlineToClassName($controller_map[$uri_array[0]]) . 'Controller';
    $controller = new $class_name ();
    $data = $controller->router($uri);
    $result = array(
        'result' => array(
            'status' => array(
                'code' => 200,
                'msg' => ''
            ),
            'data' => $data
        )
    );
    BasicController::output($result, $content_type);
}catch(ControllerException $e){
    $error = $e -> getMessage();
    $data= array(
        'result' => array(
            'status' => json_decode($error, true)
            )
        );
    BasicController::output($data, $content_type);
}
exit;
?>
