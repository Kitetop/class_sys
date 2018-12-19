<?php

namespace  Kite\Http;

use Kite\Commons\UploadFile;

/**
 * http请求参数封装
 *
 * request 管理请求的来源参数 $_GET $_POST $_FILE,在init的处理阶段cycle中实例化
 *
 * @author huangjide <hjd@moxiu.net>
 * @copyright Copyright (c) 魔秀科技(北京)股份有限公司
 * @version mx-2.0
 * @license http://inner.imoxiu.cn/pm/projects/rds/knowledgebase/articles 后端研发知识库
 */
class Request
{
    /**
     * 所有来源参数
     *
     * @var array
     */
    protected $raw;

    /**
     * 构造函数
     *
     * @param array $raw
     * @return Request
     */
    public function __construct(array $raw = null)
    {
        $this->raw = $raw === null ? $_REQUEST : $raw;
    }

    /*
     * 获取某个请求的参数值
     *
     * @param string $keys
     * @param mixed $default
     * @return mixed
     */
    public function input($key, $default = null)
    {
        return isset($this->raw[$key]) ? $this->raw[$key] : $default;
    }

    /**
     * 检查参数是否存在
     *
     * @param string $key
     * @return boolean
     */
    public function exist(string $key)
    {
        return isset($this->raw[$key]);
    }

    public function has(string $key)
    {
        //todo: 支持key 为array
        return isset($this->raw[$key]) ||
            (is_bool($this->raw[$key]) || is_array($this->raw[$key]));
    }

    /*
     * 获取多个指定请求的参数值
     *
     * @param array $keys
     * @return array
     */
    public function only($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();
        $results = [];

        foreach ($this->raw as $key => $val) {
            if (in_array($key, $keys)) {
                $results[$key] = $this->raw[$key];
            }
        }
        return $results;
    }

    /*
     * 获取过滤指定参数后 所有请求参数值
     *
     * @param array $keys
     * @return array
     */
    public function except($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();
        $results = $this->raw;

        foreach ($keys as $key) {
            unset($results[$key]);
        }
        return $results;
    }

    /**
     * 获取上传的file对象
     *
     * @param string $key
     * @return UploadFile
     */
    public function file($key)
    {
        if (!$this->hasFile($key)) {
            return null;
        }

        //普通的单个文件
        if (is_string($_FILES[$key]['tmp_name'])) {
            return new UploadFile($_FILES[$key]);
        }

        //file[] 数组形式的文件 返回一个UploadFile数组
        if (is_array($_FILES[$key]['tmp_name'])) {
            $len = count($_FILES[$key]['tmp_name']);
            $attrs = ['name', 'type', 'tmp_name', 'error', 'size'];
            $return = [];
            for ($index = 0; $index < $len; $index++) {
                $info = [];
                foreach ($attrs as $attr) {
                    $info[$attr] = $_FILES[$key][$attr][$index];
                }
                $return[] = new UploadFile($info);
            }
            return $return;
        }
        return null;
    }

    /**
     * 是否有对应上传的file
     *
     * @param string $key
     * @return boolean
     */
    public function hasFile($key)
    {
        if (isset($_FILES[$key])) {
            return true;
        }
        return false;
    }

    /**
     * 获取Http请求真实IP
     *
     * @return string
     */
    public function ip()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * 当前请求的url
     *
     * @return
     */
    public function url()
    {
        $pageURL = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';

        if ($_SERVER['SERVER_PORT'] != '80') {
            $pageURL .= $_SERVER["SERVER_NAME"] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    /*
     * 获取请求类型GET/POST
     *
     * @return string
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
