<?php
namespace app\index\controller;

use app\index\service\IndexService;
use cache\cache;
use cache\yac;
use login\login;
use login\qqLogin;
use login\wxLogin;
use rabbitmq\Rabbitmq;
use rpcCall\local;
use rpcCall\remote;
use state\contextState;
use state\paymentState;
use think\Controller;
use think\facade\Config;

class Index extends Controller
{
    public function index()
    {
        $orderState = new paymentState();
        $context    = new contextState($orderState);
        $orderId    = 1;
        $context->getOrderInfo($orderId);
        $context->action();
        $context->printInfo();
        die();
        $qqLogin    = new qqLogin();
        $wxLogin    = new wxLogin();
        $extendLogin= new login();
        $extendLogin->extendLogin();
        $login      = new login($wxLogin);
        $login->doLogin();
    }

    public function hotNews()
    {
        $data = array(
            'service'   => 'service\index\Index',
            'method'    => 'realTimeHot',
            'args'      => [],
        );
        $rpc    = new remote();
        $url    = Config::get('api_url');
        $res    = $rpc->call($url, $data);
        $deRes  = json_decode($res, true);
        $assign = [];
        if ($deRes && is_array($deRes)) {
            if ($deRes['code']  == 200) {
                $assign = [
                    'list'  => $deRes['data']
                ];
            }
        }
        return view('hot_news', $assign);
    }

    public function hotSearch()
    {
        echo "hot search";
    }

    public function hotGirl()
    {
        $type   = $this->request->get('type', '', 'string');
        $type   = 1;
        echo "hot girl";
        $this->_getHot($type);
    }

    private function _getHot($type, $page = 1, $offset = 20)
    {
        $type   = $type <= 0 ? $type : 1;
        $page   = $page <= 0 ? 1 : $page;
        $offset = $offset <= 0 || $offset > 200 ? 20 : $offset;
        $limit  = ($page - 1) * $offset;
        $listWhere = array(
            'conditions'    => "type = {$type}",
            'order'         => 'id desc',
            'limit'         => "{$limit}, $offset",
        );
        // 请求服务
        $param  = array(
            'service'   => 'service\hotnews\Index',
            'method'    => 'getHotNews',
            'args'      => [$listWhere],
        );
        $local  = new local();
        $res    = $local->call($param);
        print_r($res);
    }
}
