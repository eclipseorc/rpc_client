<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/14
 * Time: 0:05
 */
namespace oauth;

use OAuth2\GrantType\AuthorizationCode;
use OAuth2\Request;
use OAuth2\Server;
use OAuth2\Storage\Pdo;

class OauthServer
{
    const DSN       = 'mysql:dbname=oauth;host=127.0.0.1';
    const USERNAME  = 'starttogame';
    const PASSWORD  = 'Zll_850118';

    public $server;

    public function __construct()
    {
        $storage        = new Pdo(['dsn' => self::DSN, 'username' => self::USERNAME, 'password' => self::PASSWORD]);

        $this->server   = new Server($storage);

        $this->server->addGrantType(new AuthorizationCode($storage));
    }

    /**
     * 获取access_token
     */
    public function getAccessToken()
    {
        $this->server->handleTokenRequest(Request::createFromGlobals())->send();
    }

}
