<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018/12/18
 */

namespace App\Action\User;


use Kite\Action\AbstractAction;
use Kite\Commons\Page;

class ActiveList extends AbstractAction
{
    protected $getRules = [
        'page' => [
            'desc' => '分页页码',
            'message' => '页码错误',
            'rules' => ['Logic:gt:0'],
            'default' => 1
        ],
        'limit' => [
            'desc' => '每一页的数据存储',
            'rules' => ['Logic:gt:0'],
            'default' => 5
        ],
    ];

    public function doGet()
    {
        $this->validate($this->getRules);
        $service = $this->Service('User\ActiveList');
        $service->page = $this->params['page'];
        $service->limit = $this->params['limit'];
        $result = $service->run();
        $url = $this->cycle->config()['rootUrl'] . '/user/active?';
        list($result['prev'], $result['next']) = Page::simple($result['meta'], $url, $this->params);
        unset($result['meta']);
        $this->response($result);
        $this->code(200);
    }
}