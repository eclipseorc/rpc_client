<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/2/20
 * Time: 10:46
 */
namespace rpcCall;

/**
 * 远程过程调用
 * Class remote
 * @package rpcCall
 */
class remote
{
    private static $result;

    private $apiUrl = '';

    /**
     * 构造函数
     * remote constructor.
     * @param string $apiName
     */
    public function __construct($apiName = '')
    {
        if (empty($apiName)) {
            $this->apiUrl   = config($apiName);
        } else {
            $this->apiUrl   = config('default');
        }
    }

    /**
     * 单一远程调用
     * @param string $remoteUri
     * @param array $rawData
     * @return array
     * @throws \Exception
     */
    public function call($remoteUri = '', array $rawData)
    {
        if (!is_array($rawData)) {
            throw new \Exception('second argument must be array.');
        }
        if (empty($remoteUri)) {
            $remoteUri = $this->apiUrl;
        }

        $client = new \Yar_Client($remoteUri);
        try {
            $resultData = $client->callService($rawData);
        } catch (\Exception $e) {
            $resultData = array(
                'code'  => $e->getCode(),
                'data'  => '',
                'msg'   => $e->getMessage(),
            );
            // 写入kafka错误日志
            //ApiLog::pushError($rawData, $resultData);
        }
        // 增加调用日志
        //ApiLog::addLog($rawData, array());
        return $resultData;
    }

    /**
     * 并行批量远程调用
     * @param array $rawData
     * @return mixed
     * @throws \Exception
     */
    public function callMulti(array $rawData)
    {
        if (!is_array($rawData)) {
            throw new \Exception('argument must be array.');
        }
        foreach ($rawData as $key => $value)
        {
            $url            = isset($value['remote_uri']) ? $value['remote_uri'] : $this->apiUrl;
            $callback       = isset($value['callback']) ? $value['callback'] : null;
            $errorCallback  = isset($value['error_callback']) ? $value['error_callback'] : null;
            $parameters     = array(
                'service'   => $value['service'],
                'method'    => $value['method'],
                'args'      => isset($value['args']) ? $value['args'] : array()
            );
            // 注册一个rpc调用，但是并不会发送
            \Yar_Concurrent_Client::call($url, 'callService', array($parameters), $callback, $errorCallback);
        }
        \Yar_Concurrent_Client::loop(array($this, 'callback'), array($this, 'errorCallback'));
        \Yar_Concurrent_Client::reset();
        $resultData     = self::$result;
        self::$result   = array();

        // 增加调用日志
        ApiLog::addLog($rawData, $resultData);
        return $resultData;
    }

    /**
     * 并行批量远程调用回调函数
     * @param $retVal
     * @param $callInfo
     * @return bool
     */
    public function callback($retVal, $callInfo)
    {
        if (empty($callInfo)) {
            return true;
        }
        return self::$result[$callInfo['sequence']] = $retVal;
    }

    /**
     * 并行批量远程回调错误函数
     * @param $type
     * @param $error
     * @param $callInfo
     */
    public function errorCallback($type, $error, $callInfo)
    {
        if (is_array($error)) {
            self::$result[$callInfo['sequence']]['code']  = $error['code'] ? $error['code'] : 600;
            self::$result[$callInfo['sequence']]['msg']   = $error['message'];
            self::$result[$callInfo['sequence']]['trace'] = $error['file'] . ' In line:' . $error['line'];
        } else {
            self::$result[$callInfo['sequence']]['code']  = '600';
            self::$result[$callInfo['sequence']]['msg']   = $error;
            self::$result[$callInfo['sequence']]['trace'] = '';
        }
        self::$result[$callInfo['sequence']]['data']    = '';
        self::$result[$callInfo['sequence']]['runTime'] = '';
    }
}