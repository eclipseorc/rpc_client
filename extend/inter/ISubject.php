<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/31
 * Time: 0:18
 */
namespace inter;

interface ISubject
{
    public function registerObserver($obj);

    public function removeObserver($obj);

    public function notifyObservers();
}