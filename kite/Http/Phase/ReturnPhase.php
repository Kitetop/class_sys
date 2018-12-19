<?php

namespace Kite\Http\Phase;

use Kite\Cycle;
use Kite\Http\Response;

/**
 * 内容输出阶段
 *
 * 对输入内容对象 输出内容对象及输出格式进行初始化设置
 *
 * @author huangjide <hjd@moxiu.net>
 * @copyright Copyright (c) 魔秀科技(北京)股份有限公司
 * @version mx-2.0
 * @license http://inner.imoxiu.cn/pm/projects/rds/knowledgebase/articles 后端研发知识库
 */
class ReturnPhase implements PhaseInterface
{
    private $cycle;

    /**
     * 输出阶段处理的方法
     *
     * @param Mx\Http\Cycle $cycle
     * @return null
     */
    public function run(Cycle $cycle)
    {
        $this->cycle = $cycle;
        $response = $cycle->getResponse();
        $this->genHeaders($response);
        $this->genOutput($response);
    }

    /**
     * 输出http头信息
     *
     * @param Response $response
     * @return void
     */
    protected function genHeaders(Response $response)
    {
        $headers = $response->getHeaders();
        if (is_array($headers)) {
            foreach ($headers as $key => $val) {
                header($key . ': ' . $val);
            }
        }
    }

    /**
     * 输出数据内容的实现
     *
     * @param object Response $response
     * @return null
     * @throws
     */
    protected function genOutput($response)
    {
        $format = $response->getFormat();

        switch ($format) {
            case Cycle::FT_JSON:
                return $this->outputJson($response);
            case Cycle::FT_BINARY:
                return $this->outputBinary($response);
            default :
                throw new \Exception('format not support');
        }
    }


    /**
     * json格式类型的内容输出
     *
     * @param Mx\Http\Message\Response $response
     * @return null
     */
    protected function outputJson($response)
    {
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code($response->getCode());
        $data = $response->getData();
        if ($data !== null) {
            echo json_encode($response->getData(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 二进制格式类型的内容输出
     *
     * @param Mx\Http\Message\Response $response
     * @return null
     * @throws
     */
    protected function outputBinary($response)
    {
        http_response_code($response->getCode());
        $data = $response->getData();
        if ($data !== null || is_string($data)) {
            echo $data;
        } else {
            throw new \Exception('data error');
        }
    }
}
