<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/11/23
 */

namespace Kite\Http\Phase;

use Kite\Cycle;
use App\Kernel\Router;
use Kite\Http\Request;
use Kite\Http\Response;

/**
 * Class InitPhase
 * @package Kite\Http\Phase
 * 对Request的数据进行处理
 */
class InitPhase implements PhaseInterface
{
    public function run(Cycle $cycle)
    {
        // TODO: Implement run() method.
        $params = $_REQUEST;
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;
        $config = $cycle->config();

        $response = new Response();
        $response->setFormat($config['action']['format']);

        //json方式body自动解析
        if ($contentType == 'application/json') {
            $rawInput = file_get_contents("php://input");
            if ($rawInput) {
                $params = array_merge($params, @json_decode($rawInput, true));
            }
        }

        //支持非POST方式的form参数解析， 文件的话还用POST 或者单个文件对应的CONTENT_TYPE
        if ($contentType == 'application/x-www-form-urlencoded' && $_SERVER['REQUEST_METHOD'] != 'POST') {
            $parsedParams = [];
            $rawInput = file_get_contents("php://input");
            $rawInput && parse_str($rawInput, $parsedParams);
            $params = array_merge($params, $parsedParams);
        }
        try {
            $cycle->setResponse($response);
            //合并router 的path 参数
            $cycle->setRouter(new Router($cycle->config()));
            $params = array_merge($params, $cycle->getRouter()->router()->getParams());
            $cycle->setRequest(new Request($params));
        } catch (\Exception $e) {
            $cycle->getResponse()->setCode($e->getCode() ?: 500)
                ->setData(['message' => $e->getMessage()]);
        }
    }
}