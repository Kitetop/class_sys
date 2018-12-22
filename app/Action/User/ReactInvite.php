<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018-12-22
 */

namespace App\Action\User;


use Kite\Action\AbstractAction;
use Exception;

/**
 * Class ReactInvite
 * @package App\Action\User
 * 根据传递过来的参数确定是同意审稿还是拒绝审稿
 */
class ReactInvite extends AbstractAction
{
    private $getRules = [
        'type' => [
            'desc' => '用来标识审稿者的反应到底是同意审稿还是拒绝',
            'rules' => ['required'],
            'message' => '用户标识参数不能为空',
        ],
        'id' => [
            'desc' => '审稿者的用户id',
            'rules' => ['required'],
            'message' => '用户id不能为空'
        ],
        'article_id' => [
            'desc' => '需要审稿的文章id',
            'rules' => ['required'],
            'message' => '文章id不能为空'
        ]
    ];

    protected function doGet()
    {
        $this->validate($this->getRules);
        if (!in_array($this->params['type'], ['refuse', 'agree'])) {
            throw new Exception('无效的用户行为标识', 400);
        }
        $service = $this->Service('User\ReactInvite');
        $service->type = $this->params['type'];
        $service->id = $this->params['id'];
        $service->article_id = $this->params['article_id'];
        $service->run();
        $this->response('message', '你的反馈已收到，谢谢的你的配合');
        $this->code(200);
    }
}