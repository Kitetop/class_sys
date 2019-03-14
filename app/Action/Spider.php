<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018-12-30
 */

namespace App\Action;


use Kite\Action\AbstractAction;

/**
 * Class Spider
 * @package App\Action
 * 使用http请求方式得到指定网页数据
 * http://www.ujs.edu.cn
 */
class Spider extends AbstractAction
{
    protected function doGet()
    {
        $service = $this->Service('Spider');
        $result = $service->run();
        $this->response($result);
        $this->code(200);
    }
}