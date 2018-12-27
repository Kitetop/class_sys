<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/12/17
 */

namespace App\Model;

use Kite\Model\AbstractModel;

class Sys_check extends AbstractModel
{
    /**
     * 邀请审稿人的状态
     */
    const STATUS_AGREE = 1;
    const STATUS_REFUSE = 2;
    const STATUS_WAITE = 3;
    const STATUS_REOEDER = 4;

    /**
     * 初始分数以及默认的完成与否状态
     */
    const STATE_GRADE = 0;
    const STATE_DEFAULT = 2;
    const STATE_FINISH = 1;

    /**
     * 分数等级
     */
    const REJECT = -2;
    const MINOR_REJECT = -1;
    const NEUTRAL = 0;
    const MINOR_ACCEPT = 1;
    const ACCEPT = 2;

    protected function table()
    {
        return 'sys_check';
    }
}