<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018-12-22
 */

namespace App\Service\Admin;


use App\Model\Sys_check;
use App\Model\Sys_user;
use Kite\Commons\Page;
use Kite\Service\AbstractService;
use App\Model\Sys_article;
use App\Model\Sys_active;
use Exception;
use PDO;

/**
 * Class InviteRefuse
 * @package App\Service\Admin
 * 得到邀请审核被拒绝的记录
 */
class InviteRefuse extends AbstractService
{
    protected function execute()
    {
        $owner = new Sys_user(['id' => $this->id, 'status' => Sys_user::USER_OWNER]);
        if (!$owner->exist()) {
            throw new Exception('你没有权限查看', 400);
        }
        $where = ['agree' => Sys_check::STATUS_REFUSE, ['time', '>', date('Y-m-d', time())]];
        $check = new Sys_check();
        $result = $check->find()->where($where)->order(['id' => 'DESC'])->page(($this->page - 1) * $this->limit, $this->limit)
            ->execute()->fetchAll(PDO::FETCH_ASSOC);
        $this->assembleDate($result);
        $total = $check->count($where)['total'];
        Page::assemble($result, $total, $this->page, $this->limit);
        return $result;
    }

    private function assembleDate(array &$result)
    {
        foreach ($result as &$value) {
            unset($value['agree']);
            unset($value['grade']);
            unset($value['state']);
            $active = new Sys_active(['id' => $value['active_id']]);
            $value['active_title'] = $active->title;
            $value['author_id'] = $value['up_id'];
            unset($value['up_id']);
            $value['article_message'] = (new Sys_article(['id' => $value['article_id']]))->article_message;
            $value['check_name'] = (new Sys_user(['id' => $value['check_id']]))->name;
        }
    }
}