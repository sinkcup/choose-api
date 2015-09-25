<?php
/**
 * controller基础类，别的controller都继承此类。
 *
 * controller里的每个函数都是一个action动作。
 * @todo 此类中要包含哪些功能，还没有界定清楚，可能变成垃圾箱。
 */
abstract class BasicController
{
    protected static $input;
    protected static $written_language_tag;
    
    protected function __construct()
    {
        $input = array();
        self::$written_language_tag = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        error_log($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        switch($_SERVER['REQUEST_METHOD'])
        {
            case 'GET':
                $input = $_GET;
                break;
            case 'POST':
                $input = $_POST;
                break;
            case 'PUT':
            case 'DELETE':
                parse_str(file_get_contents('php://input'), $input); 
                break;
        }
        self::$input = $input;
    }
    
    /**
     * http输出。
     * @param array $data 要输出的数据
     * @param string $content_type 输出格式json，xml，plain
     * @todo 既然是static，应该放lib里？
     */
    public static function output($r, $content_type)
    {
        //参考http://cn2.php.net/manual/en/function.header.php
        if($r['result']['status']['code'] != 200)
        {
            header('HTTP/1.1 ' . $r['result']['status']['code'] . ' ' . $r['result']['status']['msg']); //module
            header('Status: ' . $r['result']['status']['code'] . ' ' . $r['result']['status']['msg']); //cgi
        }
        switch ($content_type)
        {
            case 'application/json' :
                header('Content-Type: application/json; charset=UTF-8'); //google.com用的是UTF-8。没找到标准规定大小写。todo
                if((!isset($r['result']['data']))||(!is_array($r['result']['data'])))
                {
                    $r['result']['data'] = (object)array(); //一直输出json对象
                }
                echo StrLib::decodeUnicode(json_encode($r));
                break;
            case 'application/xml' :
                header('Content-Type: application/xml; charset=UTF-8');
                //todo array to xml
                break;
            case 'text/plain' :
                header('Content-Type: text/plain; charset=UTF-8');
                //todo
                break;
        }
        return true;
    }
    
    
    /**
     * 检查页面的参数是否足够，包括GET、POST、PUT、DELETE
     */
    protected function checkParam($need_param) {
        if(empty($need_param)) {
            return true;
        }
        foreach($need_param as $name) {
            if(!isset(self::$input[$name])) {
                throw new ExceptionLib(400);
                break;
            }
        }
        return true;
    }

    /**
     * 检查登录，如果未登录则输出status code 401，退出。
     */
    public function checkLogin() {
        $auth = self::$input['auth'];
        $is_auth_valid = AuthModel::isAuthValid($auth);
        if($is_auth_valid === false) {
            throw new ExceptionLib('401');
        }
        self :: $decoded_auth = AuthModel::decodeAuth($auth);
        return true;
    }
}
?>
