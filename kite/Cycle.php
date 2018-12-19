<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v0.1
 * Date: 2018/11/22
 */

namespace Kite;

use Kite\Http\BaseRouter;
use Kite\Http\Phase\PhaseInterface;
use Kite\Http\Request;
use Kite\Http\Response;

class Cycle
{
    const CONFIG = 'config';
    const LOGGER = 'logger';

    /**
     * 输出内容格式的常量
     */
    /**
     * ajax的json数据格式输出
     */
    const FT_JSON = 'json';
    /**
     * 二进制格式输出
     */
    const FT_BINARY = 'binary';

    /**
     * @var array 生命周期中注册的各个变量
     */
    private $cycleObject = [];
    /**
     * @var object Request
     */
    protected $request;
    /**
     * @var object Response
     */
    protected $response;
    /**
     * @var object Router
     */
    protected $router;
    /**
     * @var config message
     */
    protected $config = [];

    /**
     * @param PhaseInterface $phase
     * @throws \Exception
     * 用来注册各个阶段，并将其对象进行保存
     */
    public function registerPhase(PhaseInterface $phase)
    {
        if ($phase instanceof PhaseInterface) {
            $this->cycleObject[] = $phase;
        } else {
            throw new \Exception('Illegal Phase', 500);
        }
    }

    /**
     * 顺序执行注册的各个阶段
     */
    public function run()
    {
        foreach ($this->cycleObject as $phase) {
            $phase->run($this);
        }
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setRouter(BaseRouter $router)
    {
        $this->router = $router;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function config($key = null)
    {
        $this->config = require APP . '/Config/dev.php';
        if ($key != null) {
            return $this->config[$key];
        }
        return $this->config;
    }
}