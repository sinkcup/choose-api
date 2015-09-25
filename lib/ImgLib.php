<?php
class ImgLib {
    private function __construct() {
    }

    public static function makeGifFromZip($zip_file_path, $delay) {
        $dir = $zip_file_path . 'dir/';
        $zip = new ZipArchive();
        $res = $zip->open($zip_file_path);
        if ($res === TRUE) 
        {
            $zip->extractTo($dir);
            $zip->close();
        }
        $files = glob($dir . '/*');
        ksort($files);

        $mw = NewMagickWand();

        for($i=0,$l=count($files); $i<$l; $i++) {
            $rw = NewMagickWand();
            MagickReadImage($rw,$files[$i]);
            MagickSetImageDelay($rw, intval($delay)/10); //magickwand比较特殊，>用的不是毫秒，所以毫秒需要转成1/100秒
            MagickAddImage($mw,$rw);
            DestroyMagickWand($rw);
        }
        MagickSetFormat($mw, 'gif');
        $gif_file_path = $zip_file_path . '.gif';
        MagickWriteImages($mw, $gif_file_path, true);
        DestroyMagickWand($mw);
        //todo 删除目录
        return $gif_file_path;
    }
}
?>
