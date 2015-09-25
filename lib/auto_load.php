<?php
function oldAutoload($class_name) {
    $src_dir = dirname(__FILE__) . '/../';
    if(0 < preg_match('/Lib$/', $class_name))
    {
        include $src_dir . 'lib/' . $class_name . '.php';
        return true;
    }
    if('ControllerException' == $class_name)
    {
        include $src_dir . 'controller/' . $class_name . '.php';
        return true;
    }
    if(0 < preg_match('/Controller$/', $class_name))
    {
        include $src_dir . 'controller/' . $class_name . '.php';
        return true;
    }
    if(0 < preg_match('/Model$/', $class_name)) {
        include $src_dir . 'model/' . $class_name . '.php';
        return true;
    }
    if(0 < preg_match('/File$/', $class_name)) {
        include $src_dir . 'model/dao/file/' . $class_name . '.php';
        return true;
    }

    if(0 < preg_match('/Db$/', $class_name)) {
        include $src_dir . 'model/dao/db/' . $class_name . '.php';
        return true;
    }
    if(0 < preg_match('/Partner$/', $class_name)) {
        include $src_dir . 'model/dao/partner/' . $class_name . '.php';
        return true;
    }
    if(0 < preg_match('/Apple$/', $class_name)) {
        include $src_dir . 'model/dao/apple/' . $class_name . '.php';
        return true;
    }
    if(0 < preg_match('/DbEngine$/', $class_name)) {
        include $src_dir . 'model/dao/db/inc/' . $class_name . '.php';
        return true;
    }
    if('DbCrud' == $class_name) {
        include $src_dir . 'model/dao/db/inc/' . $class_name . '.php';
        return true;
    }
    if(0 < preg_match('/CacheEngine$/', $class_name)) {
        include $src_dir . 'model/dao/cache/inc/' . $class_name . '.php';
        return true;
    }
    if('CacheCrud' == $class_name) {
        include $src_dir . 'model/dao/cache/inc/' . $class_name . '.php';
        return true;
    }
    if(0 < preg_match('/Cache$/', $class_name)) {
        include $src_dir . 'model/dao/cache/' . $class_name . '.php';
        return true;
    }
    if('NosqlCrud' == $class_name) {
        include $src_dir . 'model/dao/nosql/inc/' . $class_name . '.php';
        return true;
    }
    if(0 < preg_match('/Nosql$/', $class_name)) {
        include $src_dir . 'model/dao/nosql/' . $class_name . '.php';
        return true;
    }
}
spl_autoload_register('oldAutoload');
require dirname(__FILE__).'/../vendor/autoload.php';
