<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018-12-22
 */

namespace App\Action\Admin;


use Kite\Action\AbstractAction;
use Kite\Commons\Page;

/**
 * Class InviteRefuse
 * @package App\Action\Admin
 * 查看被拒绝的审稿邀请
 */
class InviteRefuse extends AbstractAction
{
    private $getRules = [
        'page' => [
            'rulse' => ['Logic:gt:0'],
            'default' => 1,
            'message' => '非法的页面页码',
        ],
        'limit' => [
            'rules' => ['Logic:gr:0'],
            'default' => 10,
            'message' => '非法的页面显示条数',
        ],
        'id' => [
            'rules' => ['required'],
            'desc' => '当前登陆用户的id',
            'message' => '用户id不能为空',
        ]
    ];

    protected function doGet()
    {
        $this->validate($this->getRules);
        $service = $this->Service('Admin\InviteRefuse');
        $service->page = $this->params['page'];
        $service->limit = $this->params['limit'];
        $service->id = $this->params['id'];
        $result = $service->run();
        $url = $this->cycle->config('rootUrl') . '/admin/refuse?';
        list($result['pre'], $result['next']) = Page::simple($result['meta'], $url, $this->params);
        unset($result['meta']);
        $this->response($result);
        $this->code(200);
    }
}