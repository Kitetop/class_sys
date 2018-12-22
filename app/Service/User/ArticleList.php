<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018/12/19
 */

namespace App\Service\User;


use App\Model\Sys_active;
use App\Model\Sys_article;
use Kite\Commons\Page;
use Kite\Service\AbstractService;
use PDO;

class ArticleList extends AbstractService
{
    protected function execute()
    {
        $article = new Sys_article();
        $result = $article->find()
            ->where(['up_id' => $this->id])
            ->order(['id' => 'DESC'])
            ->page(($this->page - 1) * $this->limit, $this->limit)
            ->execute()->fetchAll(PDO::FETCH_ASSOC);
        $this->assembleDate($result);
        $total = $article->count(['up_id' => $this->id])['total'];
        Page::assemble($result, $total, $this->page, $this->limit);
        return $result;
    }

    /**
     * @param $rows 查询到的用户的文章信息
     * 对
     */
    private function assembleDate(&$rows)
    {
        foreach ($rows as &$value) {
            unset($value['up_id']);
            unset($value['article_url']);
            unset($value['state']);
            $value['title'] = (new Sys_active(['id' => $value['active_id']]))->title;
            $value['second_author'] != null || $value['second_author'] = '未设置';
            $value['third_author'] != null || $value['third_author'] = '未设置';
            $value['wish'] != null || $value['wish'] = '未设置';
        }
    }
}