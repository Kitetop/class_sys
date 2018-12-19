<?php

namespace Kite\Http;

use Closure;
use Kite\Commons\Arr;

/**
 * 验证器
 *
 * 用于对表单数据进行验证, todo 支持更多的验证&对文件进行支持
 * todo: 优化validateXxxxx 的代码实现 和增加更多常用
 *
 * @author renzhenguo <renzhenguo@moxiu.net>
 * @license proprietary
 * @copyright Copyright (c) 魔秀科技(北京)股份有限公司
 */
class Validator
{
    /*
     * 要验证的数据
     *
     * @var array
     */
    protected $data;

    /**
     * 验证过后数据
     *
     * @var array
     */
    protected $validatedData = [];

    /*
     * 验证规则
     *
     * @var array
     */
    protected $ruleList;

    /**
     * 运行参数
     *
     * @var array
     */
    protected $options = [
        'raw' => false         //是否返回原始数据 默认会进行
    ];

    /**
     * 最后一次错误信息
     *
     * @var string
     */
    protected $lastError;

    /**
     * 验证方法的前缀
     *
     * @var string
     */
    protected $validateMethodPrefix = 'validate';

    /**
     * 构造函数
     *
     * @param array $ruleList 规则
     * @param array $data    数据
     * @param array $options 行为选项
     * @return Validator
     */
    public function __construct(array $ruleList = [], array $data = [], array $options = [])
    {
        $this->ruleList = $ruleList;
        $this->data = $data;
        $this->options = array_merge($this->options, $options);
    }

    /*
     * 设置要验证的数据
     *
     * @param array $data
     * @return Validator
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /*
     * 设置验证规则
     *
     * @param array $ruleList
     * @return Validator
     */
    public function setRule(array $ruleList)
    {
        $this->ruleList = $ruleList;
        return $this;
    }

    /**
     * 获取验证过后的数据
     *
     * @return array
     */
    public function validatedData()
    {
        return $this->validatedData;
    }

    /**
     * 返回最后一次错误信息
     *
     * @return string
     */
    public function lastError()
    {
        return $this->lastError;
    }

    /*
     * 执行数据验证方法
     *
     * @return boolean
     */
    public function make()
    {
        foreach ($this->ruleList as $key => $item) {
            $value = Arr::get($this->data, $key);
            isset($item['rules']) || $item['rules'] = [];

            //可选字段 并无参数 直接通过
            if (false == $this->validateRequired($value) && !in_array('required', $item['rules'])) {
                if (!empty($item['default'])) {
                    $this->validatedData[$key] = $item['default'];
                }
                continue;
            }

            if (isset($item['array']) && $item['array'] == true) {
                if (!is_array($value) && count($value) == 0) {
                    $this->lastError = isset($item['message']) ? $item['message'] : "$key validate error";
                    return false;
                }
                foreach ((array) $value as $subVal) {
                    if (false == $this->genItem($key, $item, $subVal)) {
                        return false;
                    }
                }
            } else {
                if (false == $this->genItem($key, $item, $value)) {
                    return false;
                }
            }

            if (empty($this->options['raw']) &&
                empty($item['raw']) &&
                is_string($value)) {
                $value = strip_tags($value);
            }
            $this->validatedData[$key] = $value;
        }

        return true;
    }

    /**
     * 处理一个验证字段
     *
     * @param string $key
     * @param array $item
     * @param mixed $value
     * @return boolean
     */
    protected function genItem($key, $item, $value)
    {
        foreach ($item['rules'] as $rule) {
            $result = true;
            if ($rule instanceof Closure) {
                $result = $rule($value);
            } else {
                $result = $this->validate($rule, $value);
            }

            if (false == $result) {
                $this->lastError = isset($item['message']) ? $item['message'] : "$key validate error";
                return false;
            }
        }
        return true;
    }

    /*
     * 验证的路由
     *
     * @param string $rule 验证规则
     * @param mixed $value 验证的内容
     * @return boolean
     */
    protected function validate($rule, $value)
    {
        @list($type, $parameters) = explode(':', $rule, 2);

        if (!$type) {
            throw new ValidationExc('error:rule_format');
        }

        $method = $this->validateMethodPrefix . ucfirst($type);

        if (method_exists($this, $method)) {
            return (bool)$this->$method($value, $parameters);
        } else {
            throw new ValidationExc('error:rule_type');
        }
    }

    /*
     * 验证必填项
     *
     * @param mixed $value
     * @return boolean
     */
    protected function validateRequired($value)
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        } elseif ((is_array($value) || $value instanceof Countable) && count($value) < 1) {
            return false;
        }
        return true;
    }

    /**
     * 验证mongoID
     *
     * @param string $value
     * @return mongoID
     */
    protected function validateMongoid($value)
    {
        $regex = '/^[0-9a-fA-F]{24}$/';
        return $this->validateRegex($value, $regex);
    }

    /**
     * 国内手机号
     *
     * @param int $value
     * @return boolean
     */
    protected function validateMobile($value)
    {
        $regex = '/^1\d{10}$/';
        return $this->validateRegex($value, $regex);
    }


    /**
     * 字符串最大值
     *
     * @param string $value
     * @param int $max
     * @return boolean
     */
    protected function validateMax($value, $max)
    {
        if (is_null($value)) {
            return false;
        } elseif (!is_string($value) || trim($value) === '') {
            return false;
        }
        return mb_strlen($value) <= $max;
    }

    /**
     * 字符串最小值
     *
     * @param string $value
     * @param int $max
     * @return mixed
     */
    protected function validateMin($value, $min)
    {
        if (is_null($value)) {
            return false;
        } elseif (!is_string($value) || trim($value) === '') {
            return false;
        }
        return mb_strlen($value) >= $min;
    }

    /*
     * 验证是否邮箱格式
     *
     * @param mixed $value
     * @return boolean
     */
    protected function validateEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * 验证是否日期格式
     *
     * @param mixed $value
     * @return bool
     */
    protected function validateDate($value)
    {
        if ($value instanceof DateTime) {
            return true;
        }
        if ((!is_string($value) && !is_numeric($value)) || strtotime($value) === false) {
            return false;
        }

        $date = date_parse($value);
        return checkdate($date['month'], $date['day'], $date['year']);
    }

    /**
     * 验证是否ip格式
     *
     * @param mixed $value
     * @return bool
     */
    protected function validateIp($value)
    {
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * 验证是否url格式
     *
     * @param mixed $value
     * @return bool
     */
    protected function validateActiveUrl($value)
    {
        if (! is_string($value)) {
            return false;
        }

        if ($url = parse_url($value, PHP_URL_HOST)) {
            //todo: dsn慢情况处理
            return count(dns_get_record($url, DNS_A | DNS_AAAA)) > 0;
        }

        return false;
    }

    /**
     * 枚举类型
     *
     * @param string $value
     * @param string $parameters
     * @return boolean
     */
    protected function validateEnum($value, $parameters)
    {
        return in_array($value, explode(',', $parameters));
    }

    /**
     * 使用正则的方式验证
     *
     * @param mixed $value
     * @param string $parameters
     * @return bool
     */
    protected function validateRegex($value, $parameters)
    {
        if (!is_string($value) && !is_numeric($value)) {
            return false;
        }

        return preg_match($parameters, $value);
    }

    /**
     * 进行简单的逻辑运算
     *
     * @param mixed $value
     * @param string $parameters
     * @return bool
     */
    protected function validateLogic($value, $parameters)
    {
        list($type, $parameter) = explode(':', $parameters, 2);
        switch ($type) {
            case 'gt':
                return $value > $parameter;
                break;
            case 'lt':
                return $value < $parameter;
                break;
            case 'gte':
                return $value >= $parameter;
                break;
            case 'lte':
                return $value <= $parameter;
                break;
            default:
                throw new ValidationExc('error:rule_type');
        }
    }

    /*
     * 判断是否在数组中的数据, 数据使用英文半角逗号分隔
     *
     * @param string $value
     * @param string $parameters
     * @return bool
     */
    protected function validateIn($value, $parameters)
    {
        $parameters = explode(',', $parameters);
        return in_array($value, $parameters);
    }
}
