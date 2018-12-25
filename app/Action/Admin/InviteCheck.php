<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018-12-22
 */

namespace App\Action\Admin;


use Kite\Action\AbstractAction;

/**
 * Class InviteCheck
 * @package App\Action\Admin
 * 程序主席邀请审稿人审稿
 */
class InviteCheck extends AbstractAction
{
    private $postRules = [
        'id' => [
            'desc' => '程序主席的用户id',
            'rules' => ['required'],
            'message' => '用户id不能为空'
        ],
        'check_id' => [
            'desc' => '邀请审稿人的用户id',
            'rules' => ['required'],
            'message' => '审稿人id能为空'
        ],
        'up_id' => [
            'desc' => '被审文章的上传者id',
            'rules' => ['required'],
            'message' => '文章上传者不能为空'
        ],
        'active_id' => [
            'desc' => '活动id',
            'rules' => ['required'],
            'message' => '征稿活动id不能为空'
        ],
        'old_check_id' => ['desc' => '之前分配但是被拒绝的审稿的审稿人id']
    ];

    protected function doPost()
    {
        $this->validate($this->postRules);
        $service = $this->Service('Admin\InviteCheck');
        $service->id = $this->params['id'];
        $service->check_id = $this->params['check_id'];
        $service->up_id = $this->params['up_id'];
        $service->active_id = $this->params['active_id'];
        $service->old_check_id = $this->params['old_check_id'];
        $service->run();
        $this->response('message', '邀请成功，等待回应');
        $this->code(200);
    }
}