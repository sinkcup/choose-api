<?php
/**
 * img
 * @author sink <sink.cup@gmail.com>
 */
class QiniuController extends BasicController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function router()
    {
        if(0 < preg_match('/\/qiniu\/auth$/', $_SERVER['REQUEST_URI'])) {
            switch (strtolower($_SERVER['REQUEST_METHOD']))
            {
                case 'post':
                    return $this->grantToken();
                    break;
            }
        } elseif(0 < preg_match('/\/qiniu\/fetch$/', $_SERVER['REQUEST_URI'])) {
            switch (strtolower($_SERVER['REQUEST_METHOD']))
            {
                case 'post':
                    return $this->fetch();
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
     * api POST /qiniu/auth
     *
     * @example shell curl -X 'POST' -H 'Accept:application/json; version=0.2' 'http://api.shaixuan.org/qiniu/auth'
     *
     */
    public function grantToken()
    {
        try {
            $bucket = 'com-163-sinkcup-img-agc';
            $qiniuConfig = ConfigParserLib::get('system', 'qiniu');
            $auth = new Qiniu\Auth($qiniuConfig['accessKey'], $qiniuConfig['secretKey']);
            return array(
                'token' => $auth->uploadToken($bucket),
            );
        }
        catch (Exception $e)
        {
            $error = array
            (
                'code' => '202',
                'msg' => ''
            );
            throw new ControllerException(json_encode($error));
        }
    }

    /**
     * api POST /qiniu/auth
     *
     * @example shell curl -d 'uri=http://ec4.images-amazon.com/images/I/61j8Hc4SVXL._SX425_.jpg' -H 'Accept:application/json; version=0.2' 'http://api.shaixuan.org/qiniu/fetch'
     *
     */
    public function fetch()
    {
        try {
            $bucket = 'com-163-sinkcup-img-agc';
            $qiniuConfig = ConfigParserLib::get('system', 'qiniu');
            $auth = new Qiniu\Auth($qiniuConfig['accessKey'], $qiniuConfig['secretKey']);
            $ext = 'jpg';
            $newFilename = md5($_POST['uri']) . '.' . $ext;
            $bucketMgr = new Qiniu\Storage\BucketManager($auth);
            $bucketMgr->fetch($_POST['uri'], $bucket, 'shaixuan/' . $newFilename);
            return array(
                'filename' => $newFilename,
            );
        }
        catch (Exception $e)
        {
            $error = array
            (
                'code' => '202',
                'msg' => ''
            );
            throw new ControllerException(json_encode($error));
        }
    }
}
