<?php
/**
 * Created by PhpStorm.
 * User: 张亮亮
 * Date: 2019/6/14
 * Time: 11:03
 */
namespace controller;

use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\Scope;
use OAuth2\Server;
use OAuth2\Storage\Memory;
use OAuth2\Storage\Pdo;

class Oauth
{
    const DSN       = 'mysql:dbname=oauth;host=127.0.0.1';
    const USERNAME  = 'starttogame';
    const PASSWORD  = 'Zll_850118';

    public $server;

    private $defaultScope   = 'basic';
    private $supportedScopes= [
        'basic',
        'postonwall',
        'accessphonenumber'
    ];

    public function __construct()
    {
        // 创建存储对象
        $storage = new Pdo([
            'dsn'       => self::DSN,
            'username'  => self::USERNAME,
            'password'  => self::PASSWORD,
        ]);

        // 创建服务器对象
        $this->server = new Server($storage);

        // 授权码模式
        $this->server->addGrantType(new AuthorizationCode($storage));

        // 客户端模式
        $this->server->addGrantType(new ClientCredentials($storage));


        $memory     = new Memory([
            'default_scope'     => $this->defaultScope,
            'supported_scopes'  => $this->supportedScopes,
        ]);
        $scopeUtil  = new Scope($memory);
        $this->server->setScopeUtil($scopeUtil);
    }
}