<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.1.0
 * Date: 2019-01-04
 */

namespace Kite\Service;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/Kernel/App.php';
// 加载公用的函数库
require_once __DIR__ . '/../../kite/Commons/format.php';

require_once 'TestTrait.php';

spl_autoload_register('\App\Kernel\App::load');

class AbstractTest extends TestCase
{
    use TestTrait;
}