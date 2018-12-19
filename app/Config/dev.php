<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: 0.1
 * Date: 2018/10/3
 */

namespace App;

$config = [
    'env' => 'dev', //识别当前部署环境,需在dev|test中覆写
    'debug' => false,
    'root' => realpath(__DIR__ . '/../../')
];
/*
 * Action 路由配置
 */
$config['action'] = [
    'base' => '/', //基础路径 默认为根路径
    'default' => 'Main', //默认Action
    'catch' => true, //是否自动捕获异常
    'format' => 'json', //默认输出格式
    'namespace' => '\\' . __NAMESPACE__ . '\\Action' //action的子命名空间
];
if (PHP_SAPI != 'cli') {
    $config['rootUrl'] = "http://127.0.0.1:8090";
    $config['realUrl'] = $config['rootUrl'] . $config['action']['base'];
    $config['assetUrl'] = $config['rootUrl'] . '/assets';
} else {
    $config['rootUrl'] = $config['realUrl'] = $config['assetsUrl'] = '';
}
/**
 * 错误日志
 */
$config['logger'] = [
    'name' => 'errorlog',
    'write' => $config['root'] . '/runtime/logs/default_error.log',
    'level' => 7
];

/**
 * 文件上传路径
 */
$config['upload'] = __DIR__ . '/../../upload';

/**
 * 数据库配置信息
 */
#################
#    MONGODB    #
#################
$config['MongoDB'] = 'mongodb://127.0.0.1:27017?dbname=news';

###############
#    MYSQL    #
###############
$config['MySQL'] = [
    'dsn' => 'mysql:dbname=database;host=127.0.0.1',
    'user' => 'root',
    'password' => '',
];

return $config;
