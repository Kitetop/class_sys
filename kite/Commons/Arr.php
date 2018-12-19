<?php

namespace Kite\Commons;

/**
 * array基础类
 *
 * 对数组深度使用 提供类jquery对象形式的数组key使用
 *
 * @final
 * @author huangjide <hjd@moxiu.net>
 * @copyright Copyright (c) 魔秀科技(北京)股份有限公司
 * @version mx-2.0
 * @license http://inner.imoxiu.cn/pm/projects/rds/knowledgebase/articles 后端研发知识库
 */
final class Arr
{
    /**
     * 设置数组元素值
     *
     * @static
     * @param array|ArrayAccess $array 传址原数据
     * @param string $key 要设置的key
     * @param $value 设置的值
     * @return array|boolean
     */
    public static function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
        return $array;
    }

    /**
     * 读取数组元素值
     *
     * @static
     * @param array|ArrayAccess $array 读取的原始数据
     * @param string $key 读取的元素key
     * @param $default key不存在时的默认值
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        if (!static::accessible($array)) {
            return $default;
        }

        if (is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * 判断是数组还是对象
     *
     * @static
     * @param array|ArrayAccess $value
     * @return boolean
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * 判断key是否存在于数组中
     *
     * @static
     * @param array|ArrayAccess $array
     * @return boolean
     */
    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }
        return array_key_exists($key, $array);
    }
}
