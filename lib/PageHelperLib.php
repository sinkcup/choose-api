<?php
class PageHelperLib {
    private function __construct() {
    }
    
    /**
     * 检查页面的参数是否足够，包括GET、POST、PUT、DELETE
     * 
     */
    public static function isParamEnough($need_param) {
        if(isset($need_param['post'])&&!empty($need_param['post'])) {
            foreach($need_param['post'] as $name) {
                if(!isset($_POST[$name])) {
                    return false;
                    break;
                }
            }
        }
        if(isset($need_param['get'])&&!empty($need_param['get'])) {
            foreach($need_param['get'] as $name) {
                if(!isset($_GET[$name])) {
                    return false;
                    break;
                }
            }
        }
        if(isset($need_param['delete'])&&!empty($need_param['delete'])) {
            parse_str(file_get_contents('php://input'),$delete);
            foreach($need_param['delete'] as $name) {
                if(!isset($delete[$name])) {
                    return false;
                    break;
                }
            }
        }
        return true;
    }
}
?>
