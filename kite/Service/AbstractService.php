<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: 0.1
 * Date: 2018/10/8
 */

namespace Kite\Service;


abstract class AbstractService
{
    public $params = [];
    /**
     * @var array [全局的配置信息]
     */
    protected $config = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function run()
    {
        return $this->execute();
    }

    /**
     * 在服务内部调用其他服务
     * @param String $name
     * @param array $params
     * @throws
     * @return AbstractService
     */
    public function call(string $name, array $params)
    {
        $class = 'App\\Service\\' . $name;
        if (class_exists($class)) {
            $service = new $class($this->config);
            $service->params = $params;
            return $service->execute();
        } else {
            throw new \Exception('This Service is Invalid');
        }
    }

    public function __set($name, $value)
    {
        $this->params[$name] = $value;
    }

    public function __get($name)
    {
        return $this->params[$name];
    }

    /**
     * @return mixed [抽象的方法，需要子类进行重写]
     */
    abstract protected function execute();
}