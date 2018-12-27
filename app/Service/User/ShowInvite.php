<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018-12-22
 */

namespace App\Service\User;


use App\Model\Sys_active;
use App\Model\Sys_article;
use App\Model\Sys_check;
use App\Model\Sys_user;
use Kite\Service\AbstractService;
use PDO;

/**
 * Class ShowInvite
 * @package App\Service\User
 * 显示邀请记录
 */
class ShowInvite extends AbstractService
{
    protected function execute()
    {
        $user = new Sys_user(['id' => $this->id, 'status' => Sys_user::USER_CHECK]);
        if(!$user->exist()) {
            throw new Exception('你没有权限查看邀请', 200);
        }
        $check = new Sys_check();
        $result = $check->find()->where(['check_id' => $this->id, 'agree' => Sys_check::STATUS_WAITE])
            ->page(($this->page - 1) * $this->limit, $this->limit)
            ->execute()->fetchAll(PDO::FETCH_ASSOC);
        $total = $check->count(['check_id' => $this->id, 'agree' => Sys_check::STATUS_WAITE])['total'];
        $this->assembleDate($result);
        $result['total'] = $total;
        $result['meta'] = [
            'page' => $this->page,
            'total' => $total,
            'pages' => ceil($total / $this->limit)
        ];
        return $result;
    }

    /**
     * @param array $result
     * 返回的article_id用来给审稿者下载论文
     * 返回的active_id用来查看活动主题等信息
     */
    private function assembleDate(array &$result)
    {
        foreach ($result as &$value) {
            unset($value['agree']);
            unset($value['grade']);
            unset($value['state']);
            unset($value['check_id']);
            $active = new Sys_active(['id' => $value['active_id']]);
            $value['active_title'] = $active->title;
            $value['author_id'] = '保密';
            $active->type == Sys_active::DOUBLE_TYPE || $value['author_id'] = $value['up_id'];
            unset($value['up_id']);
            $value['article_message'] = (new Sys_article(['id' => $value['article_id']]))->article_message;
        }
    }
}