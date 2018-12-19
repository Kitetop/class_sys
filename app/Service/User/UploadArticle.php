<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/12/18
 */

namespace App\Service\User;

use App\Model\Sys_active;
use App\Model\Sys_user;
use Kite\Service\AbstractService;

class UploadArticle extends AbstractService
{
    protected function execute()
    {
        $active = new Sys_active(['id' => $this->active_id]);
        if (!(new Sys_user(['id' => $this->up_id]))->exist() ||
            !$active->exist()) {
            throw new \Exception('此用户或者活动不存在', 400);
        }
        $this->call('Common\ValidateTime', ['time' => $active->end_time]);
        $url = $this->setUrl();
        if (!$this->article->moveTo($this->config['upload'] . '/' . $url)) {
            throw new \Exception('文件上传失败，请稍后再试', 500);
        }
    }

    private function setUrl()
    {
        return md5($this->up_id) . time() . md5($this->active_id) . '.' . $this->article->getExt();
    }

}