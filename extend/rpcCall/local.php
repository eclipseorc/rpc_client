<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/2/20
 * Time: 10:25
 */
namespace rpcCall;

use service\BackendServer;

/**
 * 本地过程调用
 * Class local
 * @package rpcCall
 */
class local
{
    /**
     * 构造函数
     * local constructor.
     */
    public function __construct()
    {

    }

    /**
     * 本地单一调用
     * @param array $rawData
     * @return mixed
     */
    public function call(array $rawData)
    {
        $backendServer = new BackendServer();
        $response = $backendServer->callService($rawData);
        return $response;
    }

    /**
     * 本地并行调用
     * @param array $rawData
     * @return array|bool
     */
    public function callMulti(array $rawData)
    {
        if (empty($rawData)) {
            return false;
        }
        $result = array();
        foreach ($rawData as $key => $value) {
            $parameters = array(
                'service'   => $value['service'],
                'method'    => $value['method'],
                'args'      => $value['args']
            );
            $result[$key + 1] = $this->call($parameters);
        }
        return $result;
    }
}