<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018/11/26
 */

namespace App\Model;


use Kite\Model\AbstractModel;

class Index extends AbstractModel
{
    protected $primary = 'Mphone';

    protected function table()
    {
        return 'manage';
    }
}
