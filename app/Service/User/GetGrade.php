<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018-12-27
 */

namespace App\Service\User;


use App\Model\Sys_active;
use App\Model\Sys_article;
use App\Model\Sys_check;
use App\Model\Sys_user;
use Kite\Service\AbstractService;
use Kite\Commons\Page;
use PDO;
use Exception;

/**
 * Class GetGrade
 * @package App\Service\User
 * step1:检验此用户是否上传了这个文章
 * step2:检验是否能够查询成绩（时间）
 * step3:在check表中检索出给出评分的审核记录
 * step4:根据活动的审稿模式屏蔽评分人的信息
 */
class GetGrade extends AbstractService
{
    protected function execute()
    {
        $article = new Sys_article(['id' => $this->article_id, 'up_id' => $this->id]);
        if (!$article->exist()) {
            throw new Exception('该文章信息不存在', 200);
        }
        $active = new Sys_active(['id' => $article->active_id]);
        if (date('Y-m-d', time()) <= $active->end_check) {
            throw new Exception('还未到成绩公布的时间，请 ' . $active->end_check . ' 之后再来查看成绩', 200);
        }
        $check = new Sys_check();
        $where = ['article_id' => $this->article_id, 'up_id' => $this->id, 'state' => Sys_check::STATE_FINISH];
        $result = $check->find()->where($where)->page(($this->page - 1) * $this->limit, $this->limit)
            ->execute()->fetchAll(PDO::FETCH_ASSOC);
        $this->assembleDate($result);
        $total = $check->count($where)['total'];
        Page::assemble($result, $total, $this->page, $this->limit);
        return $result;
    }

    /**
     * @param $result
     * 根据审稿模式来屏蔽审稿者的信息
     */
    private function assembleDate(&$result)
    {
        foreach ($result as &$value) {
            unset($value['time']);
            unset($value['state']);
            unset($value['up_id']);
            unset($value['agree']);
            $grade = unserialize($value['grade']);
            $value['original_grade'] = $grade['original_grade'];
            $value['format_grade'] = $grade['format_grade'];
            $value['content_grade'] = $grade['content_grade'];
            $value['reference_grade'] = $grade['reference_grade'];
            $value['whole_grade'] = $grade['whole_grade'];
            $value['total'] = $grade['total'];
            unset($value['grade']);
            $active = new Sys_active(['id' => $value['active_id']]);
            if ($active->type == Sys_active::NONE_TYPE) {
                $value['check_name'] = (new Sys_user(['id' => $value['check_id']]))->name;
            } else {
                $value['check_id'] = '保密';
                $value['check_name'] = '保密';
            }
        }
    }
}