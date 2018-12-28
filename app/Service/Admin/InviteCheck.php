<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018-12-22
 */

namespace App\Service\Admin;


use App\Model\Sys_active;
use App\Model\Sys_article;
use App\Model\Sys_check;
use App\Model\Sys_user;
use Kite\Service\AbstractService;
use Exception;

/**
 * Class InviteCheck
 * @package App\Service\Admin
 * 邀请审核者为此文章进行审核
 * step1：根据active_id，up_id确定审核表的article_id
 * step2：根据active_id确定审稿的截止日期
 */
class InviteCheck extends AbstractService
{
    protected function execute()
    {
        $owner = new Sys_user(['id' => $this->id, 'status' => Sys_user::USER_OWNER]);
        if(!$owner->exist()) {
            throw new Exception('你没有权限进行如下操作', 400);
        }
        if($this->old_check_id != null) {
            $check = new Sys_check(['up_id' => $this->up_id, 'active_id' => $this->active_id, 'check_id' => $this->old_check_id]);
            if($check->exist()) {
                $check->agree = Sys_check::STATUS_REOEDER;
                $check->save();
            }
        }
        $article = new Sys_article(['up_id' => $this->up_id, 'active_id' => $this->active_id]);
        $active = new Sys_active(['id' => $this->active_id]);
        $check = new Sys_check(['active_id' => $this->active_id, 'up_id' => $this->up_id, 'check_id' => $this->check_id]);
        if($check->exist()) {
            throw new Exception('你已经邀请过这个人了，不能重复邀请', 400);
        }
        $check->import([
            'article_id' => $article->id,
            'time' => $active->end_check,
            'up_id' => $this->up_id,
            'active_id' => $this->active_id,
            'grade' => Sys_check::STATE_GRADE,
            'state' => Sys_check::STATE_DEFAULT,
            'agree' => Sys_check::STATUS_WAITE,
            'check_id' => $this->check_id
        ])->save();
        $article->state = $article->state + 1;
        $article->save();
    }
}