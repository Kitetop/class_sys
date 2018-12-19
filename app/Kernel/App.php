<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: 0.1
 * Date: 2018/10/2
 */

namespace App\Kernel;

use Kite\Cycle;
use Kite\Http\Phase\InitPhase;
use Kite\Http\Phase\ReturnPhase;
use Kite\Http\Phase\RouterPhase;

class App
{
    //确定此类是否已经被引入进来了
    public static $classMap = [];

    public static function initApp()
    {
        define('ROOT', str_replace('\\', '/', __DIR__).'/../..');
        // 核心的函数库文件
        define('KERNEL', ROOT . '/app/Kernel');
        //控制器等所处目录
        define('APP', ROOT . '/app');
        // 调试模式
        define('DEBUG', true);
        if (DEBUG) {
            ini_set('display_errors', 'On');
        } else {
            ini_set('display_errors', 'Off');
        }
        // 加载公用的函数库
        require_once ROOT . '/kite/Commons/format.php';
        // 加载第三方类库
        require_once ROOT . '/vendor/autoload.php';
    }

    /**
     * 框架的启动函数
     * @return string
     */
    public static function run()
    {
        self::initApp();
        $cycle = new Cycle();
        $cycle->registerPhase(new InitPhase());
        $cycle->registerPhase(new RouterPhase());
        $cycle->registerPhase(new ReturnPhase());
        $cycle->run();
    }

    /**
     * 类的自动加载
     * @param $class [需要加载的类的名字]
     * @return bool
     */
    public static function load($class)
    {
        $class = format($class);
        // 避免重复引入
        if (isset($classMap[$class])) {
            return true;
        }
        $filePath = ROOT . '/' . $class . '.php';
        if (is_file($filePath)) {
            require_once $filePath;
            self::$classMap[$class] = $class;
        } else {
            return false;
        }
    }
}
