<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/11/23
 */

namespace Kite\Http\Phase;

use Kite\Cycle;

/**
 * Class RouterPhase
 * @package Kite\Http\Phase
 * 进行路由分发
 */
class RouterPhase implements PhaseInterface
{
    public function run(Cycle $cycle)
    {
        //还不支持携带动态参数的跳转
        $config = $cycle->config('action');
        if ($config['catch']) {
            try {
                $this->call($cycle);
            } catch (\Exception $e) {
                $cycle->getResponse()->setCode($e->getCode() ?: 500)
                    ->setData(['message' => $e->getMessage()]);
            }
        } else {
            $this->call($cycle);
        }
    }

    /**
     * @param Cycle $cycle
     * @throws \Exception
     * 调用设定的Action
     */
    protected function call(Cycle $cycle)
    {
        $router = $cycle->getRouter();
        $actionMessage = $router->router()->getRouter();
        //$path = APP . '/Action/' . $actionMessage['action'] . '.php';
        $class = 'App\\Action\\' . $actionMessage['action'];
        if (class_exists($class)) {
            $action = new $class($cycle);
            $action->execute($actionMessage['method']);
        } else {
            throw new \Exception('This action not exit');
        }
    }
}