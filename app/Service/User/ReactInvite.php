<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018-12-22
 */

namespace App\Service\User;


use App\Model\Sys_check;
use Kite\Service\AbstractService;
use Exception;

/**
 * Class ReactInvite
 * @package App\Service\User
 * 根据用户的回复更新数据表中的内容
 */
class ReactInvite extends AbstractService
{
    protected function execute()
    {
        $this->type == 'refuse' && $this->type = Sys_check::STATUS_REFUSE;
        $this->type == 'agree'  && $this->type = Sys_check::STATUS_AGREE;
        $check = new Sys_check(['check_id' => $this->id, 'article_id' => $this->article_id, 'agree' => Sys_check::STATUS_WAITE]);
        if (!$check->exist()) {
            throw new Exception('审核记录为空,无需修改', 400);
        }
        $check->agree = $this->type;
        $check->save();
    }
}