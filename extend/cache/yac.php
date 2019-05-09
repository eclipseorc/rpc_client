<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/5/3
 * Time: 14:56
 */
namespace cache;

use think\Exception;

class yac
{
    public  $prefix = '_yac:';
    private $_cache;

    public function getCache()
    {
        if ((!$this->_cache instanceof \Yac)) {
            try {
                $this->_cache = new \Yac($this->prefix);
            } catch (Exception $e) {
                die('yac extension not found!');
            }
        }
        return $this->_cache;
    }

    /**
     * 缓存key超过长度后，取hash后的值
     * @param $key
     * @return string
     */
    private function _formatKey($key)
    {
        if (strlen($key) > 48) {
            return md5($key);
        }
        return $key;
    }

    /**
     * 设置缓存内容
     * @param     $key
     * @param     $value
     * @param int $timeout
     * @return mixed
     */
    public function set($key, $value, $timeout = 0)
    {
        $key = $this->_formatKey($key);
        return $this->getCache()->set($key, $value, $timeout);
    }

    /**
     * 获取缓存内容
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        $key = $this->_formatKey($key);
        return $this->getCache()->get($key);
    }

    /**
     * 所有缓存都失效
     * @return mixed
     */
    public function flush()
    {
        return $this->getCache()->flush();
    }

    /**
     * 获取缓存服务器信息
     * @return mixed
     */
    public function info()
    {
        return $this->getCache()->info();
    }
}