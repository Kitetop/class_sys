<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: 0.1
 * Date: 2018/10/5
 */

namespace Kite\Action;

use Kite\Cycle;
use Kite\Http\Validator;
use Kite\Http\ValidatorUploadFile;


/**
 * 处理Action的基础类
 * Class BaseAction
 * @package Kite\Action
 * @param $method [请求的方法类型]
 */
abstract class AbstractAction
{
    protected $cycle;
    protected $request;
    protected $response;
    protected $params = [];
    protected $validatedFiles = [];

    public function __construct(Cycle $cycle)
    {
        $this->cycle = $cycle;
        $this->request = $cycle->getRequest();
        $this->response = $cycle->getResponse();
    }

    public function execute($method)
    {
        $func = 'do' . ucfirst(strtolower($method));
        $this->$func();
    }

    /**
     * 返回对应的Service对象
     * @return Service
     * @throws [调用的服务是否存在]
     */

    public function Service(string $name)
    {
        $class = 'App\\Service\\'.$name;
        if (class_exists($class)) {
            return new $class($this->cycle->config());
        } else {
            throw new \Exception('This Service is Invalid',500);
        }
    }

    /*
 * 表单验证,默认对输入内容验证
 *
 * @param array $rule 验证规则
 * @return array
 */
    protected function validate(array $rules)
    {
        $data = $this->request->only(array_keys($rules));
        $validator = new Validator($rules, $data);
        if (false === $validator->make()) {
            throw new \Exception($validator->lastError(), 400);
        }
        $this->params = $validator->validatedData();
    }

    /**
     * 设置输出内容
     *
     * @final
     * @param string $key
     * @param mixed $val
     * @param boolean $hidden
     * @return null
     */
    final protected function response($key, $val = null, $hidden = false)
    {
        $paramsNum = func_num_args();
        if ($paramsNum == 1) {
            $this->response->setData($key, $val, $hidden);
        } else {
            $this->response->addDataWithKey($key, $val, $hidden);
        }
    }

    /**
     * 表单文件的验证
     *
     * @param array $rules
     * @return mixed
     */
    protected function validateUploadFile(array $rules)
    {
        $data = [];
        $keys = array_keys($rules);

        foreach ($keys as $key) {
            $data[$key] = $this->request->file($key);
        }
        $validator = new ValidatorUploadFile($rules, $data);
        if (false === $validator->make()) {
            throw new \Exception($validator->lastError(), 400);
        }
        $this->validatedFiles = $validator->validatedData();
    }

    /**
     * 异常输出
     *
     * @param int $code
     * @param string $message
     * @return void
     */
    protected function fault($code, $message)
    {
        $this->code($code);
        $this->response(['message' => $message]);
        $this->interrupt = true;
    }

    /**
     * 设置http返回码
     *
     * @param code $code
     * @return mixed
     */
    protected function code($code)
    {
        $this->response->setCode($code);
        return $this;
    }

    /**
     * format 指定输出格式
     *
     * @param string $format
     * @return null
     */
    protected function format($format)
    {
        $this->response->setFormat($format);
    }

    protected function doGet()
    {
    }

    protected function doPost()
    {
    }

    protected function doDelete()
    {
    }

    protected function doPatch()
    {
    }
}
