<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/12/19
 */

namespace App\Action\User;


use Kite\Action\AbstractAction;
use Kite\Commons\Page;

class ArticleList extends AbstractAction
{
    private $getRules = [
        'page' => [
            'desc' => '所在页面的页数',
            'rules' => ['Logic:gt:0'],
            'message' => '页码错误',
            'default' => 1
        ],
        'limit' => [
            'desc' => '每页条数限制',
            'rules' => ['Logic:gt:0'],
            'message' => '显示条数数目错误',
            'default' => 10,
        ],
        'id' => [
            'desc' => '需要查询文章的用户id',
            'rules' => ['required'],
            'message' => '用户id不能为空'
        ]
    ];

    public function doGet()
    {
        $this->validate($this->getRules);
        $service = $this->Service('User\ArticleList');
        $service->page = $this->params['page'];
        $service->limit = $this->params['limit'];
        $service->id = $this->params['id'];
        $result = $service->run();
        $url = $this->cycle->config()['rootUrl'] . '/user/article?';
        list($result['prev'], $result['next']) = Page::simple($result['meta'], $url, $this->params);
        unset($result['meta']);
        $this->response($result);
        $this->code(200);
    }
}