<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/3/29
 * Time: 13:56
 */

namespace encrypt;

class RC4
{
    /**
     * rc4加密函数
     * @param $str
     * @param $pwd
     * @return string
     */
    public static function encode($str, $pwd)
    {
        return strtoupper(bin2hex(self::RC4($str, $pwd)));
    }

    /**
     * rc4解密函数
     * @param $str
     * @param $pwd
     * @return string
     */
    public static function decode($str, $pwd)
    {
        $data   = '';
        $str    = join('', explode('\x', $str));
        $len    = strlen($str);
        for ($i = 0; $i < $len; $i+=2) {
            $data .= chr(hexdec(substr($str, $i, 2)));
        }
        return self::RC4($data, $pwd);
    }

    /**
     * rc4算法
     * @param $str - 待加密字符串
     * @param $pwd - 密钥
     * @return string - 加密后的字符串
     */
    private static function RC4($str, $pwd)
    {
        $key    = array();
        $box    = array();

        $pwdLen = strlen($pwd);
        $dataLen= strlen($str);

        for ($i = 0; $i < 256; $i++) {
            $key[$i]    = ord($pwd[$i % $pwdLen]);
            $box[$i]    = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j      = ($j + $box[$i] + $key[$i]) % 256;
            $tmp    = $box[$i];
            $box[$i]= $box[$j];
            $box[$j]= $tmp;
        }

        $cipher = '';
        for ($a = $j = $i = 0; $i < $dataLen; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;

            $tmp        = $box[$a];
            $box[$a]    = $box[$j];
            $box[$j]    = $tmp;

            $k = $box[(($box[$a] + $box[$j]) % 256)];
            $cipher .= chr(ord($str[$i]) ^ $k);
        }
        return $cipher;
    }
}