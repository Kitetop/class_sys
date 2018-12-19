<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/12/17
 */

namespace App\Model;


use Kite\Model\AbstractModel;

class Sys_user extends AbstractModel
{
    const USER_OWNER = 1; //程序主席
    const USER_CHECK = 2; //审稿人
    const USER_NORMAL = 3;//普通用户

    protected function table()
    {
        return 'sys_user';
    }
}