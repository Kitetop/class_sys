<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: 0.1
 * Date: 2018/10/3
 */

namespace Kite\Http;

/**
 * 路由表配置管理
 * Class BaseRouter
 * @package Kite\Http\Website
 */
class BaseRouter
{
    /**
     * @var 自定义路由的配置信息
     */
    private $routers;
    private $config;
    /**
     * @var 发起请求的路由信息
     */
    private $router = [];
    protected $params = [];

    /**
     * BaseRouter constructor.
     * @param array $routers [自定义路由列表]
     * @param $config [框架的基本配置信息]
     * 1. 隐藏index.php
     * 2. 获取URL的参数部分
     * 3. 返回对应控制器以及方法
     */
    public function __construct(array $routers, $config)
    {
        $this->routers = $routers;
        $this->config = $config;
    }

    /**
     * 取得路由的信息
     * @return object $this
     * @throws \Exception
     */
    public function router()
    {
        if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
            $path = $this->analyseRouter($_SERVER['PATH_INFO']);
            foreach ($this->routers as $item) {
                if ($item['path'] == $path && $item['method'] == $_SERVER['REQUEST_METHOD']) {
                    $this->router['action'] = $item['action'];
                    $this->router['path'] = $item['path'];
                    $this->router['method'] = $item['method'];
                    return $this;
                }
            }
            throw new \Exception('This url or function is not set',500);
        } else {
            // 使用配置的默认Action
            $this->router['action'] = $this->config['action']['default'];
            $this->router['path'] = $this->config['action']['base'];
            $this->router['method'] = $_SERVER['REQUEST_METHOD'];
        }
        return $this;
    }

    /**
     * 解析路由
     * @return
     */
    private function analyseRouter($router)
    {
        $routerArr = explode('/', trim($router, '/'));
        $routerNum = count($routerArr);
        // 只有第一位是目录结构，其他的都是参数
        if ($routerNum > 2 && (($routerNum - 2) % 2) != 0) {
            $router = '/' . $routerArr[0];
            for ($i = 1; $i < $routerNum; $i = $i + 2) {
                $this->params[$routerArr[$i]] = $routerArr[$i + 1];
                $router .= '/:' . $routerArr[$i];
            }
        } else {
            if (isset($routerArr[1])) {
                $router = '/' . $routerArr[0] . '/' . $routerArr[1];
            } else {
                $router = '/' . $routerArr[0];
            }
            for ($i = 2; $i < $routerNum; $i = $i + 2) {
                $this->params[$routerArr[$i]] = $routerArr[$i + 1];
                $router .= '/:' . $routerArr[$i];
            }
        }
        return $router;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getRouter()
    {
        return $this->router;
    }

}
