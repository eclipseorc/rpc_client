<?php
namespace app\index\controller;

use app\index\service\IndexService;
use cache\yac;
use rabbitmq\Rabbitmq;
use rpcCall\local;
use rpcCall\remote;
use think\Controller;

class Index extends Controller
{
    // 实时热点排行榜
    const HOT_NEWS_URL  = 'http://top.baidu.com/buzz?b=1&fr=topindex';
    // 今日热门搜索排行榜
    const HOT_SEARCH_URL= 'http://top.baidu.com/buzz?b=2&fr=topindex';
    // 今日美女排行榜
    const HOT_GIRL_URL  = 'http://top.baidu.com/buzz?b=3&fr=topindex';
    // 今日电视剧排行榜
    const HOT_VIDEO_URL = 'http://top.baidu.com/buzz?b=4&fr=topindex';
    // 今日移动热门搜索排行榜
    const HOT_MOBILE_URL= 'http://top.baidu.com/buzz?b=5&fr=topindex';

    public function index()
    {
        $yac = new yac();
        $newsArr = $this->getBaiduHot();
        if (!empty($newsArr) && is_array($newsArr)) {
            $Rabbitmq   = new Rabbitmq();
            foreach ($newsArr as $key => $value) {
                $Rabbitmq->publish(json_encode($value, JSON_UNESCAPED_UNICODE));
            }
        }
    }

    /**
     * 获取百度热点
     * @return array
     */
    private function getBaiduHot()
    {
        $res = file_get_contents(self::HOT_NEWS_URL);
        // 转换编码
        $res = iconv('GBK', 'utf-8', $res);
        $data= array();
        if (!empty($res)) {
            // 匹配table内容
            $patternTable = "/<table[^>]*?>(.*?)<\/table>/s";
            preg_match($patternTable, $res, $matches);
            // 匹配第一项
            if (isset($matches[0]) && !empty($matches[0])) {
                $patternTr = '/<tr[^>]*?>(.*?)<\/tr>/s';
                preg_match_all($patternTr, $matches[0], $matchesTr);
                if (isset($matchesTr) && is_array($matchesTr)) {
                    $patternWord = '/<a[^>]*?class="list-title"[^>]*>(.*?)<\/a>/i';
                    $patternIcon = '/<span[^>]*?class="icon-[^>]*"[^>]*>(.*?)<\/span>/s';
                    $patternHref = '/<a.*?href="(.*?)".*?>/i';
                    foreach ($matchesTr[0] as $key => $value) {
                        preg_match($patternIcon, $value, $matchesIcon);
                        preg_match_all($patternWord, $value, $matchesWord);
                        if (isset($matchesWord[0][0])) {
                            preg_match($patternHref, $matchesWord[0][0], $matchesHref);
                        }
                        if (isset($matchesWord) && !empty($matchesWord[1][0])) {
                            $data[$key]['title']    = $matchesWord[1][0];
                            $data[$key]['href']     = isset($matchesHref[1]) ? $matchesHref[1] : '';
                            $data[$key]['hot_num']  = $matchesIcon[1];
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function hello($name = 'ThinkPHP5')
    {
        $data = array(
            'service'   => 'service\index\Index',
            'method'    => 'index',
            'args'      => ['ddd'],
        );
        $data1 = array(
            'service'   => 'service\index\Index',
            'method'    => 'add',
            'args'      => array(1, 2)
        );
        $mergeData = array($data, $data1);
        $local = new local();
        $res = $local->call($data);
        $res = $local->callMulti($mergeData);
        print_r($res);
        die();
        return 'hello,' . $name;
    }

    public function hotNews()
    {
        $data = array(
            'service'   => 'service\index\Index',
            'method'    => 'index',
            'args'      => ['ddd'],
        );
        $data1 = array(
            'service'   => 'service\index\Index',
            'method'    => 'add',
            'args'      => array(1, 2)
        );
        //print_r($data);
        $rpc = new remote();
        $url = 'http://www.unbec.com/index/test/test';
        $res = $rpc->call($url, $data1);
        print_r($res);
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
