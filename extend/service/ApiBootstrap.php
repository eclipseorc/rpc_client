<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/2/19
 * Time: 11:12
 */
namespace service;

class ApiBootstrap extends AbstractBootstrap
{
    public function init()
    {

    }

    protected function execYar()
    {
        $backend = new BackendServer();
        $application = new \Yar_Server($backend);
        $application->handle();
    }
}