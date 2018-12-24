<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018-12-24
 */

namespace App\Service\Admin;


use App\Model\Sys_active;
use Kite\Service\AbstractService;

class AddActive extends AbstractService
{
    protected function execute()
    {
        $active = new Sys_active();
        $active->import([
            'title' => $this->title,
            'theme' => $this->theme,
            'type' => $this->type,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'end_check' => $this->end_check,
        ])->save();
    }
}