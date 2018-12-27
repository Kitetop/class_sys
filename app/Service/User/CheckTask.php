<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018-12-27
 */

namespace App\Service\User;


use App\Model\Sys_active;
use App\Model\Sys_article;
use App\Model\Sys_check;
use App\Model\Sys_user;
use Kite\Service\AbstractService;
use Exception;
use PDO;
use Kite\Commons\Page;

/**
 * Class CheckTask
 * @package App\Service\User
 * step1:根据传入的用户id查看该用户是否有审稿任务权限
 * step2:查出任务列表
 */
class CheckTask extends AbstractService
{
    protected function execute()
    {
        $user = new Sys_user(['id' => $this->id, 'status' => Sys_user::USER_CHECK]);
        if(!$user->exist()) {
            throw new Exception('你没有权限查看审稿任务', 200);
        }
        $check = new Sys_check();
        $where = ['check_id' => $this->id,
            'agree' => Sys_check::STATUS_AGREE,
            'state' => Sys_check::STATE_DEFAULT,
            ['time', '>' , date('Y-m-d', time())]];
        $result = $check->find()->where($where)->page(($this->page - 1) * $this->limit, $this->limit)
            ->execute()->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            throw new Exception('你暂时没有任务需要处理', 200);
        }
        $this->assembleDate($result);
        $total = $check->count($where)['total'];
        Page::assemble($result, $total, $this->page, $this->limit);
        return $result;
    }

    /**
     * @param $result
     * 根据审稿模式来屏蔽上传者的信息
     */
    private function assembleDate(&$result)
    {
        foreach ($result as &$value) {
            unset($value['agree']);
            unset($value['state']);
            unset($value['grade']);
            unset($value['check_id']);
            $active = new Sys_active(['id' => $value['active_id']]);
            if ($active->type == Sys_active::DOUBLE_TYPE) {
                $value['up_id'] = '保密';
                $value['author_name'] = '保密';
            } else {
                $value['author_name'] = (new Sys_user(['id' => $value['up_id']]))->name;
            }
            $value['article_message'] = (new Sys_article(['id' => $value['article_id']]))->article_message;
            $value['active_title'] = $active->title;
        }
    }
}