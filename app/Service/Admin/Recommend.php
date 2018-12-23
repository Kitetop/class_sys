<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018-12-23
 */

namespace App\Service\Admin;


use App\Model\Sys_article;
use App\Model\Sys_check;
use App\Model\Sys_user;
use Kite\Service\AbstractService;
use PDO;
use Exception;

/**
 * Class Recommend
 * @package App\Service\Admin
 * step1:搜索出该作者三年内的所以论文合作者的名字
 * step2:去除掉有合作的审稿者
 */
class Recommend extends AbstractService
{
    protected function execute()
    {
        $article = new Sys_article(['up_id' => $this->up_id, 'active_id' => $this->active_id]);
        if (!$article->exist()) {
            throw new Exception('错误的文章请求审稿信息', 400);
        }
        $check = new Sys_check();
        $author = $check->find()
            ->where(['up_id' => $this->up_id, ['time', '>', date('Y-m-d', strtotime('- 3 years'))]])
            ->execute()->fetchAll(PDO::FETCH_ASSOC);
        $hasCheck = $check->find()->where(['up_id' => $this->up_id, 'active_id' => $this->active_id])
            ->execute()->fetchAll(PDO::FETCH_ASSOC);
        $hasChecks = $this->findCheck($hasCheck);
        $authors = $this->findAuthor($author);
        $checkers = (new Sys_user())->find()->where(['status' => Sys_user::USER_CHECK])
            ->execute()->fetchAll(PDO::FETCH_ASSOC);
        $result = $this->getResult($authors, $checkers, $hasChecks);
        if(empty($result)) {
            throw new Exception('暂时没有符合条件的审核者可以为此论文审稿', 404);
        }
        return $result;

    }

    /**
     * @param $author
     * @return array
     * 根据传入的数据寻找它的合作作者
     */
    private function findAuthor($author)
    {
        $authors = [];
        if (is_array($author)) {
            foreach ($author as $value) {
                $article = new Sys_article(['id' => $value['article_id']]);
                $authors[] = $article->first_author;
                $article->second_author != null && $authors[] = $article->second_author;
                $article->third_author != null && $authors[] = $article->third_author;
            }
            $authors = array_unique($authors);
        }
        return $authors;
    }

    /**
     * @param $hasCheck
     * @return array
     * 根据传入的数据寻找该列表中已经为改文章分配审核的审核人id
     */
    private function findCheck($hasCheck)
    {
        $hasChecks = [];
        if(is_array($hasCheck)) {
            foreach ($hasCheck as $value) {
                $hasChecks[] = $value['check_id'];
            }
        }
        return $hasChecks;
    }

    /**
     * @param array $authors
     * @param array $checkers
     * @return array
     * 得到可以为该文章审核的人的用户名和用户id
     */
    private function getResult(array $authors, array $checkers, array $hasChecks)
    {
        $result = [];
        foreach ($checkers as $values) {
            if(in_array($values['name'], $authors) || in_array($values['id'], $hasChecks)) {
                continue;
            }
            $result[] = ['id' => $values['id'], 'name' => $values['name']];
        }
        return $result;
    }
}