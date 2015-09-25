<?php
class StrLib
{
    private function __construct()
    {

    }
    
    public static function firstToUpper($s)
    {
        $first = substr ( $s, 0, 1 );
        $left = substr ( $s, 1 );
        return strtoupper ( $first ) .$left;
    }
    
    public static function firstToLower($s)
    {
        $first = substr ( $s, 0, 1 );
        $left = substr ( $s, 1 );
        return strtolower ( $first ) .$left;
    }
    
    public static function decodeUnicode($str)
    {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
            create_function(
                '$matches',
                'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
            ),
            $str
        );
    }
    
    public static function underlineToFunctionName($s)
    {
        $tmp = explode('_',$s);
        $result = $tmp[0];
        unset($tmp[0]);
        foreach($tmp as $value) {
            $result .= self::firstToUpper($value);
        }
        return $result;
    }
    
    public static function underlineToClassName($s)
    {
        $tmp = explode('_',$s);
        $result = '';
        foreach($tmp as $value) {
            $result .= self::firstToUpper($value);
        }
        return $result;
    }
    
    /**
     * 获得一段话中at提到的人
     * @todo 用户名中可有用@符号吗？
     */
    public static function getMentions($str)
    {
        $tmp = explode(' ',$str); //先按空格切割，比如'@user1 asdf @@user2 qwer@user3'
        $data =array();
        foreach($tmp as $value) {
            $start = strpos($value, '@');
            if($start!==false) {
                $data[] = substr($value, $start + 1);
            }
        }
        return $data; //将获得user1 @user2 user3
    }

    /**
     * 获得一段话中的话题
     * @todo 是否考虑一个#和一个空格来表示话题，有的网站这么做。不过没啥必要，两个#也挺好。能同时兼容吗？
     */
    public static function getTopics($str) {
        //比如 '### #asdf#自拍。#asdf#风 景#qwer#拍手#拍车'
        $start = strpos($str, '#'); //取第一个#的位置，这里是0
        $tmp = $str;
        $topics =array();
        while($start !== false) {
            $tmp = substr($tmp, $start + 1); //从#后面开始截取
            $end = strpos($tmp, '#'); //再取#，即是这个话题的结尾
            if($end !== false) { //如果这个话题有结尾。
                $result = str_replace(array(' '), array(''), substr($tmp, 0, $end)); //截取到这个话题的结尾，删除空格，如果话题是空格，替换完了就是空
                if(!empty($result)) {
                    $topics[] = $result;
                }
            }
            else { //如果只有一个#符号，跳过。
                break;
            }
            $tmp = substr($tmp, $end + 1); //截取这个话题后面的字符串
            $start = strpos($tmp, '#'); //后面还有#吗？如果没有，循环结束。
        }
        return $topics;
    }
}
?>
