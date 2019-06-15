<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/5/31
 * Time: 0:22
 */
namespace inter;

interface IObservable
{
    public function registerObserver($obj);

    public function removeObserver($obj);

    public function notifyObservers();
}