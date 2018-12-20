<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018/12/19
 */

namespace App\Service\Common;


use Kite\Service\AbstractService;
use PDO;

/**
 * Class ValidateRepeat
 * @package App\Service\Common
 * 验证数据库中是否有重复信息
 */
class ValidateRepeat extends AbstractService
{
    public function execute()
    {
        $res = $this->model->find()->where($this->where)->execute()->fetch(PDO::FETCH_ASSOC);
        if(!$res) {
            return false;
        }
        return true;
    }
}