<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018/12/17
 */

namespace App\Model;

use Kite\Model\AbstractModel;

class Sys_active extends AbstractModel
{
    const SINGER_TYPE = 1; //单盲
    const  DOUBLE_TYPE = 2; //双盲
    const NONE_TYPE = 3; //非盲

    protected function table()
    {
        return 'sys_active';
    }
}