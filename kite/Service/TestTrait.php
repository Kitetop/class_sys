<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.1.0
 * Date: 2019-01-04
 */

namespace Kite\Service;

use App\Kernel\App;

/**
 * Trait TestTrait
 * 使得测试类能够自适应框架内部的调用，可以无缝的调用APi的服务层
 */
trait TestTrait
{
    public function initEnv()
    {
        App::initApp();
        echo 'init Env success!';
    }
}