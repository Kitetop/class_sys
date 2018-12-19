<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: 0.1
 * Date: 2018/10/2
 */

namespace App;

use App\Kernel\App;

date_default_timezone_set('Asia/Shanghai');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Connection, User-Agent, Cookie');
// 加载框架的核心的文件、启动框架
require_once __DIR__ . '/../app/Kernel/App.php';
//类的自动加载
spl_autoload_register('\App\Kernel\App::load');
App::run();