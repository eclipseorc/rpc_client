<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/5/3
 * Time: 14:52
 */
namespace cache;

class cache
{
    /**
     * 设置缓存
     * @param $key
     * @return string
     */
    public static function set($key, $value, $timeout = 0)
    {
        if (extension_loaded('yac')) {
            $yac    = new yac();
            return $yac->set($key, $value, $timeout);
        }
        $redis  = new redis();
        return $redis->set($key, $value, $timeout);
    }

    /**
     * 获取缓存值
     * @param $key
     * @return bool|mixed|string
     */
    public static function get($key)
    {
        if (extension_loaded('yac')) {
            $yac = new yac();
            $res = $yac->get($key);
        }
        if (!isset($res) || (isset($res) && $res === false)) {
            $redis = new redis();
            $res = $redis->get($key);
        }
        return isset($res) ? $res : false;
    }
}