<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/5/3
 * Time: 15:15
 */
namespace cache;



use think\facade\Config;

class redis
{
    public  $prefix = 'redis:';
    private $_redis;

    /**
     * 初始化redis
     * @return bool|\Redis
     */
    public function getRedis()
    {
        if (!($this->_redis instanceof \Redis)) {
            $this->_redis   = new \Redis();
            $host           = Config::get('redis_host');
            $port           = Config::get('redis_port');
            $connect        = $this->_redis->connect($host, $port);
            if (!$connect) {
                die('redis not connected!');
            }

            $this->_redis->setOption(\Redis::OPT_PREFIX, $this->prefix);
        }
        return $this->_redis;
    }

    /**
     * 获取redis缓存
     * @param $key
     * @return bool|string
     */
    public function get($key)
    {
        return $this->getRedis()->get($key);
    }

    /**
     * 设置redis缓存
     * @param     $key
     * @param     $value
     * @param int $timeout
     * @return bool
     */
    public function set($key, $value, $timeout = 0)
    {
        return $this->getRedis()->set($key, $value, $timeout);
    }
}