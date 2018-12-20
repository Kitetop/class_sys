<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/12/18
 */

namespace App\Service\User;

use App\Model\Sys_active;
use App\Model\Sys_article;
use App\Model\Sys_user;
use Kite\Service\AbstractService;
use PDO;

class UploadArticle extends AbstractService
{
    protected function execute()
    {
        $active = new Sys_active(['id' => $this->active_id]);
        $article = new Sys_article();
        if (!(new Sys_user(['id' => $this->up_id]))->exist() ||
            !$active->exist()) {
            throw new \Exception('此用户或者活动不存在', 400);
        }
        $this->call('Common\ValidateTime', ['time' => $active->end_time]);
        if ($this->call('Common\ValidateRepeat',
            ['model' => $article, 'where' => ['up_id' => $this->up_id, 'active_id' => $this->active_id]]
        )) {
            throw new \Exception('您已经提交过，请勿重新提交', 400);
        }
        $url = $this->setUrl();
        if (!($this->article->moveTo($this->config['upload'] . '/' . $url))) {
            throw new \Exception('文件上传失败，请稍后再试', 500);
        }
        $article->import([
            'up_id' => $this->up_id,
            'active_id' => $this->active_id,
            'article_message' => $this->article_message,
            'first_author' => $this->first_author,
            'second_author' => $this->second_author,
            'third_author' => $this->third_author,
            'wish' => $this->wish,
            'article_url' => $url,
            'state' => 0,
        ])->save();
    }

    private function setUrl()
    {
        return md5($this->up_id) . time() . md5($this->active_id) . '.' . $this->article->getExt();
    }

}