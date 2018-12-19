<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/12/19
 */

namespace App\Service\Common;


use Kite\Service\AbstractService;

class ValidateTime extends AbstractService
{
    protected function execute()
    {
        $now = (int)str_replace('-', '', date('Y-m-d', time()));
        $time = (int)str_replace('-', '', $this->time);
        if ($now > $time) {
            throw new \Exception('已过截止日期，希望您下次能够早来', 400);
        }
    }
}