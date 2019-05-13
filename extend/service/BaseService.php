<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/2/19
 * Time: 11:53
 */
namespace service;

class BaseService
{
    protected static $instance;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (!(static::$instance instanceof static)) {
            return static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * service模块通用返回数据函数
     * @param string $data
     * @param int    $code
     * @param string $msg
     * @return string
     */
    public function returnData($data = array(), $code = 200, $msg = '')
    {
        if (intval($code) >= 600) {

        }
        $resultData = array(
            'code'  => $code,
            'msg'   => $msg,
            'data'  => $data,
        );
        return json_encode($resultData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}