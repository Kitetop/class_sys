<?php

namespace  Kite\Http;

/**
 * 输出内容的封装
 *
 * 管理所有输出的信息,包括格式 内容 头信息等
 *
 * @author huangjide <hjd@moxiu.net>
 * @copyright Copyright (c) 魔秀科技(北京)股份有限公司
 * @version mx-2.0
 * @license http://inner.imoxiu.cn/pm/projects/rds/knowledgebase/articles 后端研发知识库
 */
class Response
{
    /**
     * 输出的http头信息集合
     *
     * @var array
     */
    protected $headers;

    /**
     * 异常输出内容
     *
     * @var array
     */
    protected $fault;

    /**
     * 输出的数据内容
     *
     * @var array
     */
    protected $data;

    /**
     * 不输出的数据内容
     *
     * @var array
     */
    protected $hiddenData;

    /**
     * 输出内容的格式
     *
     * @var string
     */
    protected $format;

    /**
     * http返回码
     *
     * @var int
     */
    protected $code = 200;

    /**
     * 设置初始化所有要输出的内容
     *
     * @param mixed $val
     * @return null
     */
    public function setData($val)
    {
        $this->data = $val;
        return $this;
    }

    /**
     * 增加要输出的内容
     *
     * @param string $key
     * @param mixed $val
     * @param boolean $hidden
     * @return void
     */
    public function addDataWithKey($key, $val = null, $hidden = false)
    {
        $hidden ? $this->hiddenData[$key] = $val : $this->data[$key] = $val;
    }

    /**
     * 设置增加输出的头信息
     *
     * @param string $name
     * @param string $val
     * @return null
     */
    public function withHeader($name, $val)
    {
        $this->headers[$name] = $val;
    }

    public function withAddedHeader($name, $val)
    {
    }

    /**
     * 删除输出的头信息
     *
     * @param string $name
     * @return null
     */
    public function withoutHeader($name)
    {
        unset($this->headers[$name]);
    }

    /**
     * 获取所有要输出的头信息
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * 判断是否设置某头信息
     *
     * @param string $name
     * @return boolean
     */
    public function hasHeader($name)
    {
        return isset($this->headers[$name]) ? boolval($this->headers[$name]) : false;
    }

    /**
     * 获取所有要输出的数据内容
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 获取所有要隐藏输出的数据内容
     *
     * @return array
     */
    public function getHiddenData()
    {
        return $this->hiddenData;
    }

    /**
     * 设置异常输出值
     *
     * @param array $fault
     * @return void
     */
    public function fault($fault)
    {
        $this->fault = $fault;
    }

    /**
     * 获取输出异常的内容
     *
     * @return array
     */
    public function getFault()
    {
        return $this->fault;
    }

    /**
     * 重置 清理异常输出
     *
     * @return null
     */
    public function resetFault()
    {
        unset($this->fault);
    }

    /**
     * 是否存在异常输出
     *
     * @return boolean
     */
    public function isFault()
    {
        return boolval($this->fault);
    }

    /**
     * 设置输出数据内容的格式
     *
     * @param string $format
     * @return Mx\Http\Message\Response
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * 返回输出数据内容的格式
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    /**
     * 输出cookie
     *
     * @param string $key
     * @param string $val
     * @param int $life
     * @param string $domain
     * @return void
     */
    public function cookie($key, $val, $life = 0, $domain = null)
    {
        $httponly = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;

        if ($domain == null) {
            $domain = $_SERVER['SERVER_NAME'];
        }

        setcookie (
            $key,
            $val,
            $life ? $_SERVER['REQUEST_TIME'] + $life : 0,
            '/',
            $domain,
            $httponly
        );
    }
}
