<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018-12-22
 */

namespace App\Service\Admin;


use App\Model\Sys_active;
use App\Model\Sys_article;
use App\Model\Sys_user;
use Kite\Commons\Page;
use Kite\Service\AbstractService;
use Exception;
use PDO;

/**
 * Class Distribute
 * @package App\Service\Admin
 * 显示分配审核人的待审核列表
 */
class Distribute extends AbstractService
{
    protected function execute()
    {
        $owner = new Sys_user(['id' => $this->id, 'status' => Sys_user::USER_OWNER]);
        if (!$owner->exist()) {
            throw new Exception('你没有权限进行如下操作', 400);
        }
        $time = date('Y-m-d', time());
        $active = new Sys_active();
        $actives = $active->find()->where([
            ['end_time', '<', $time],
            ['end_check', '>', $time]
        ])->execute()->fetchAll(PDO::FETCH_ASSOC);
        $article = new Sys_article();
        $where = $this->getWhere($actives);
        $result = $article->find()->where($where)->order(['state' => 'ASC'])->execute()->fetchAll(PDO::FETCH_ASSOC);
        $total = $article->count($where)['total'];
        $this->assembleDate($result);
        Page::assemble($result, $total, $this->page, $this->limit);
        return $result;
    }

    /**
     * @param array $array
     * @return array
     * 组装where的查询语句
     */
    private function getWhere(array $array)
    {
        $where = [];
        foreach ($array as $value) {
            $where['or'][] = ['active_id' =>  $value['id']];
        }
        return $where;
    }

    private function assembleDate(array &$result)
    {
        foreach ($result as &$value) {
            unset($value['article_url']);
            $value['second_author'] == null && $value['second_author'] = '未设置';
            $value['third_author'] == null && $value['third_author'] = '未设置';
            $value['wish'] == null && $value['wish'] = '未设置';
            $value['title'] = (new Sys_active(['id' => $value['active_id']]))->title;
        }
    }
}