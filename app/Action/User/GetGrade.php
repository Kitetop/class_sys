<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018-12-27
 */

namespace App\Action\User;

use Kite\Action\AbstractAction;
use Kite\Commons\Page;

/**
 * Class GetGrade
 * @package App\Action\User
 * 查看用户投稿论文的成绩
 */
class GetGrade extends AbstractAction
{
    private $getRules = [
        'id' => [
            'rules' => ['required'],
            'desc' => '当前登陆的用户的id',
            'message' => '用户id不能为空',
        ],
        'article_id' => [
            'rules' => ['required'],
            'desc' => '查看文章成绩的文章的id',
            'message' => '文章id不能为空',
        ],
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

    protected function doGet()
    {
        $this->validate($this->getRules);
        $service = $this->Service('User\GetGrade');
        $service->id = $this->params['id'];
        $service->article_id = $this->params['article_id'];
        $service->page = $this->params['page'];
        $service->limit = $this->params['limit'];
        $result = $service->run();
        $url = $this->cycle->config()['rootUrl'] . '/user/mygrade?';
        list($result['prev'], $result['next']) = Page::simple($result['meta'], $url, $this->params);
        unset($result['meta']);
        $this->response($result);
        $this->code(200);
    }
}