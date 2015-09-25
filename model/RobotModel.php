<?php 
class RobotModel
{
    public static function getAmazonProduct($id, $locale)
    {
        $paa = ConfigParserLib::get('amazon', 'paa');
        $aws = ConfigParserLib::get('amazon', 'aws');

        // The region you are interested in
        $endpoint = $paa['marketplace'][$locale];

        $uri = "/onca/xml";

        $params = array(
            "Service" => "AWSECommerceService",
            "Operation" => "ItemLookup",
            "AWSAccessKeyId" => $aws['access_key_id'],
            "AssociateTag" => $paa['associate_tag'],
            "ItemId" => $id,
            "IdType" => "ASIN",
            "ResponseGroup" => "Images,ItemAttributes,Offers",
            "Version" => "2011-08-01"
        );

        // Set current timestamp if not set
        if (!isset($params["Timestamp"])) {
            $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
        }

        // Sort the parameters by key
        ksort($params);

        $pairs = array();

        foreach ($params as $key => $value) {
            array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
        }

        // Generate the canonical query
        $canonical_query_string = join("&", $pairs);

        // Generate the string to be signed
        $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

        // Generate the signature required by the Product Advertising API
        $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws['secret_key'], true));

        // Generate the signed URL
        $url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        $xml = simplexml_load_string($content);
        return array(
            'amazon_cn_id' => $id,
            'name' => strval($xml->Items->Item->ItemAttributes->Title),
            'img' => strval($xml->Items->Item->ImageSets->ImageSet->LargeImage->URL),
            'price' => $xml->Items->Item->OfferSummary->LowestNewPrice->Amount / 100,
        );
    }

    public static function getJdProduct($id)
    {
        $data = array(
            'jd_id' => $id,
        );
        $url = 'http://item.jd.com/' . $id . '.html';
         
        $ip = self::get_rand_ip(); //随机ip
        $content = self::get_content_by_url($url, $ip);
         
        //获取标题
        preg_match("/<div id=\"name\">[\s]*<h1>(.*?)<\/h1>/i", $content, $match_name);
        if(isset($match_name[1]) && $match_name[1]){
            $data['name'] = mb_convert_encoding($match_name[1], 'UTF-8', 'GBK');
            $name = $match_name[1];
        } else {
            return false;
        }
         
        //获取价格
        $price_url = "http://p.3.cn/prices/mgets?skuIds=J_$id,J_&type=1";
        $price_content = file_get_contents($price_url);
        if(!empty($price_content)) {
            $price_content_arr = json_decode($price_content);
            $data['price'] = $price_content_arr[0]->p;
        }
         
        $img_url = "http://www.jd.com/bigimage.aspx?id=$id";
        $img_content = self::get_content_by_url($img_url, $ip);
        $name = preg_replace(array("/\(/", "/\)/", "/\./", "/\*/", "/\?/", "/\//"), array("\(", "\)", "\.", "\*", "\?", "\/"), $name);
        preg_match("/<img src=\"(.*?)\" alt=\"$name\"/i", $img_content, $match_img);
        if(isset($match_img[1]) && $match_img[1]){
            $data['img'] = $match_img[1];
        }
        return $data;
    } 

    public static function get_rand_ip()
    {
        return rand(1, 254) . '.' . rand(1, 254) . '.' . rand(1, 254) . '.' . rand(1, 254);
    }
     
    public static function get_content_by_url($url, $ip = '127.0.0.1'){
        if(empty($url)){
            return;
        }
         
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.93 Safari/537.36');
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        if(!empty($ip)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:' . $ip, 'CLIENT-IP:' . $ip));  //构造IP
        }
         
        $content = curl_exec($ch);
        return $content;
    }
}
