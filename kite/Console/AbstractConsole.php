<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/11/24
 */

namespace Kite\Console;

use Symfony\Component\Console\Command\Command;

abstract class AbstractConsole extends Command
{
    protected $config = [];
    public function __construct($name)
    {
        parent::__construct($name);
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     * 脚本中调用Service
     */
    public function Service($name)
    {
        $class = 'App\\Service\\'.$name;
        if (class_exists($class)) {
            return new $class($this->config());
        } else {
            throw new \Exception('This Service is Invalid',500);
        }
    }

    /**
     * @param null $key
     * @return array|mixed
     * 获得配置文件的配置信息
     */
    public function config($key = null)
    {
        $this->config = require APP . '/Config/dev.php';
        if ($key != null) {
            return $this->config[$key];
        }
        return $this->config;
    }
}