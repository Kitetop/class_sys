<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018-12-22
 */

namespace App\Action\User;


use Kite\Action\AbstractAction;
use Kite\Commons\Page;

/**
 * Class ShowInvite
 * @package App\Action\User
 * 提醒登陆用户是否收到了需要审核的邀请（主要针对审稿者）
 */
class ShowInvite extends AbstractAction
{
    private $getRules = [
        'id' => [
            'desc' => '当前用户的登陆id',
            'rules' => ['required'],
            'message' => '用户id不能为空',
        ],
        'page' => [
            'rules' => ['Logic:gt:0'],
            'default' => 1,
        ],
        'limit' => [
            'rules' => ['Logic:gt:0'],
            'default' => 10,
        ]
    ];

    protected function doGet()
    {
        $this->validate($this->getRules);
        $service = $this->Service('User\ShowInvite');
        $service->page = $this->params['page'];
        $service->limit = $this->params['limit'];
        $service->id = $this->params['id'];
        $result = $service->run();
        $url = $this->cycle->config()['rootUrl'] . '/user/invite?';
        list($result['prev'], $result['next']) = Page::simple($result['meta'], $url, $this->params);
        unset($result['meta']);
        $this->response($result);
        $this->code(200);
    }
}