<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/2/19
 * Time: 11:21
 */
namespace service;

class BackendServer
{
    protected function callByParams($serviceClass, $method, $request)
    {
        // 构造服务实例名称（此处需要加上根命名空间，否则找不到该类）
        $serviceClass = app()->getNamespace() . '\\' . $serviceClass . 'Service';
        if (is_array($request)) {
            $class = new \ReflectionClass($serviceClass);
            if (!$class->getMethod($method)) {
                throw new \Exception('Service class: method definition is invalid. Detail: ' . $serviceClass . ' : ' . $method);
            }
        } else {
            throw new \Exception('Request is not right format: ' . json_encode($request));
        }
        //获取服务实例
        $serviceObj = $serviceClass::getInstance();
        // 是否可以调用
        if (is_callable(array($serviceObj, $method))) {
            $response = call_user_func_array(array($serviceObj, $method), $request);
            return $response;
        } else {
            throw new \Exception('Service: method not found. Detail: ' . $serviceClass . ' : ' . $method);
        }
    }

    public function callService($rawData)
    {
        $service    = trim($rawData['service']);
        $method     = trim($rawData['method']);
        $request    = $rawData['args'];
        file_put_contents(app()->getRuntimePath() . '/ddd.txt', json_encode($rawData));
        if (empty($service) || empty($method)) {
            throw new \Exception('Service: method must be exists.');
        }
        $response = $this->callByParams($service, $method, $request);
        return $response;
    }
}