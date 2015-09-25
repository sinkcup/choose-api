<?php
class LanguageLib {
    private function __construct() {
    }

    /**
     * 从字符串获得written_language_tag，如果不在映射表中，则返回默认的tag
     */
    public static function getWltFromStr($str) {
        $wlt_map = ConfigParserLib::get('language', 'written_language_tag_map');
        if(isset($wlt_map[strtolower($str)])) {
            return $wlt_map[strtolower($str)];
        }
        else {
            return ConfigParserLib::get('language', 'default_written_language_tag');
        }
    }
}
?>
