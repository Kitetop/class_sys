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
 * Class Distribute
 * @package App\Action\Admin
 * 分配审核人列表
 */
class Distribute extends AbstractAction
{
    private $getRules = [
        'page' => [
            'rules' => ['Logic:gt:0'],
            'default' => 1,
            'message' => '非法的页面页码',
        ],
        'limit' => [
            'rules' => ['Logic:gt:0'],
            'default' => 10,
            'message' => '非法的页面显示数目',
        ],
        'id' => [
            'rules' => ['required'],
            'desc' => '当前登陆用户的用户id',
            'message' => '用户id不能为空',
        ]
    ];

    protected function doGet()
    {
        $this->validate($this->getRules);
        $service = $this->Service('Admin\Distribute');
        $service->id = $this->params['id'];
        $service->page = $this->params['page'];
        $service->limit = $this->params['limit'];
        $result = $service->run();
        $url = $this->cycle->config('rootUrl') . '/admin/distribute?';
        list($result['prev'], $result['next']) = Page::simple($result['meta'], $url, $this->params);
        unset($result['meta']);
        $this->response($result);
        $this->code(200);
    }
}