<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018-12-27
 */

namespace App\Action\User;


use Kite\Action\AbstractAction;

/**
 * Class CheckTask
 * @package App\Action\User
 * 查看当前审稿者的审稿任务
 */
class CheckTask extends AbstractAction
{
    private $getRules = [
        'id' => [
            'rules' => ['required'],
            'message' => '用户id不能为空',
            'desc' => '当前登陆的用户的id'
        ],
        'page' => [
            'rules' => ['Logic:gt:0'],
            'default' => 1
        ],
        'limit' => [
            'rules' => ['Logic:gt:0'],
            'default' => 5
        ]
    ];

    protected function doGet()
    {
        $this->validate($this->getRules);
        $service = $this->Service('User\CheckTak');
        $service->id = $this->params['id'];
        $service->page = $this->params['page'];
        $service->limit = $this->params['limit'];
        $result = $service->run();
        $url = $this->cycle->config()['rootUrl'] . '/user/task?';
        list($result['prev'], $result['next']) = Page::simple($result['meta'], $url, $this->params);
        unset($result['meta']);
        $this->response($result);
        $this->code(200);
    }
}