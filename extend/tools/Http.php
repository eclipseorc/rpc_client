<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/3/29
 * Time: 15:57
 */
namespace tools;

class Http
{
    /**
     * get请求
     * @param     $url - 请求地址
     * @param int $timeOut - 超时时间
     * @param int $noBody - 是否返回内容，特殊用法
     * @return mixed
     */
    public static function get($url, $timeOut = 5, $noBody = 0)
    {
        $timeOut    = (int)$timeOut <= 0 ? 0 : (int)$timeOut;
        $ch         = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; .' .
            'zh-CN; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeOut);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
        if ($noBody != 0) {
            curl_setopt($ch, CURLOPT_NOBODY, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $str    = curl_exec($ch);
        curl_close($ch);
        return $str;
    }

    /**
     * post请求
     * @param       $url
     * @param       $data
     * @param array $header
     * @param int   $timeOut
     * @return mixed
     */
    public static function post($url, $data, $header = array(), $timeOut = 5)
    {
        $data   = empty($data) ? array() : (array)$data;
        $header = empty($header) ? array() : (array)$header;
        $timeOut= (int)$timeOut <= 0 ? 0 : (int)$timeOut;
        $ch     = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; .' .
            'zh-CN; rv:1.9.2.8) Gecko/20100722 Firefox/3.6.8');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeOut);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $str    = curl_exec($ch);
        curl_close($ch);
        return $str;
    }
}