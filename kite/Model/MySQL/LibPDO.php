<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018/12/10
 */

namespace Kite\Model\MySQL;


class LibPDO
{
    private static $instance = [];

    /**
     * @return static
     */
    public static function getInstance()
    {
        $class_name = get_called_class();
        if (empty(self::$instance[$class_name])) {
            self::$instance[$class_name] = new $class_name;
        }
        return self::$instance[$class_name];
    }

    /**
     * 解析转换where条件
     * @param array $where
     * @example $where = [['id', '=', '1'], ['age', '>', '50']]
     * @example $where = ['id', 'in', [1, 2, 3]];
     * @example $where = ['name', 'like', '%a']
     * @example $where = ['or'=>[[['id', '=', '1'], ['age', '>', '50']], ['name', 'like', '%a']]] // 转化为查询: ((id=1 and age>50) or name like '%a')
     * @return array
     */
    public function parseWhere($where)
    {
        $ret = [];
        $a_op = ['=', '!=', '<>', '<', '>', '<=', '>=', 'in', 'notin', 'like'];
        if (is_array($where) && !empty($where)) {
            if (count($where) === 3 && isset($where[1]) && !is_array($where[1]) && in_array(trim($where[1]), $a_op)) {
                $where = [$where];
            }

            foreach ($where as $key => $value) {
                // or查询
                if ('or' === strtolower($key)) {
                    $arr_condition_or = $this->parseOr($value);
                    if (!empty($arr_condition_or['where'])) {
                        $ret['where'][] = $arr_condition_or['where'];
                        if (!empty($arr_condition_or['params'])) {
                            $ret['params'] = empty($ret['params']) ? $arr_condition_or['params'] : array_merge($ret['params'], $arr_condition_or['params']);
                        }
                    }
                    continue;
                }

                // 统一 $where 的数据格式为 [[$k1, $op1, $v1], [$k1, $op1, $v1]]
                if (is_string($key)) {
                    if (is_array($value)) {
                        $value = [$key, 'in', $value];
                    } else {
                        $value = [$key, '=', $value];
                    }
                }

                if (count($value) != 3) {
                    continue;
                }

                $value[0] = $this->quoteColumnName($value[0]);
                $value[1] = trim($value[1]);
                if (in_array($value[1], ['in', 'notin'])) {
                    // 数组需要转义
                    if (is_array($value[2])) {
                        if ($value[2]) {
                            foreach ($value[2] as &$v2) {
                                $v2 = $this->quoteValue($v2);
                            }
                            $value[2] = implode(',', $value[2]);
                        } else {
                            $value[2] = $this->quoteValue('');
                        }
                    } else {
                        $value[2] = $this->quoteValue($value[2]);
                    }
                }

                switch (strtolower($value[1])) {
                    case 'in':
                        $ret['where'][] = " {$value[0]} IN ({$value[2]}) ";
                        break;
                    case 'notin':
                        $ret['where'][] = " {$value[0]} NOT IN ({$value[2]}) ";
                        break;
                    case 'like':
                        $ret['where'][] = " {$value[0]} LIKE ? ";
                        $ret['params'][] = $value[2];
                        break;
                    default:
                        $ret['where'][] = " {$value[0]} {$value[1]} ? ";
                        $ret['params'][] = $value[2];
                }
            }
        }

        if (empty($ret) || empty($ret['where'])) {
            $ret['where'] = '1';
            $ret['params'] = [];
        } else {
            $ret['where'] = implode(' AND ', $ret['where']);
        }
        return $ret;
    }


    /**
     * 解析or语句
     * @param type $arr_or
     * @return array
     */
    private function parseOr($arr_or)
    {
        $ret = [
            'params' => [],
            'where' => ''
        ];
        if (!$arr_or || !is_array($arr_or)) {
            return $ret;
        }

        $arr_condition = [];
        foreach ($arr_or as $v) {
            if (!$v) {
                continue;
            }
            $arr_parse = $this->parseWhere($v);
            $arr_condition[] = '(' . $arr_parse['where'] . ')';
            if (is_array($arr_parse['params'])) {
                $ret['params'] = array_merge($ret['params'], $arr_parse['params']);
            }
        }

        if ($arr_condition) {
            $s_or = implode(' OR ', $arr_condition);
            $ret['where'] = '(' . $s_or . ')';
        }
        return $ret;
    }

    /**
     * 解析order规则 支持string 和 数组2种形式
     * @param string $order id DESC,age ASC
     *        array  $order array('id' => 'DESC' , 'age' => 'ASC')
     * @return string
     */
    public function parseOrder($order = null)
    {
        $ret = '';
        if (!empty($order)) {
            if (!is_array($order)) {
                $ret = " ORDER BY {$order}";
            } else {
                $temp = [];
                $sort_rule = ['ASC' => 1, 'DESC' => 1];
                foreach ($order as $key => $value) {
                    if (isset($sort_rule[strtoupper($value)])) {
                        $temp[] = " `{$key}` {$value} ";
                    }
                }
                if (!empty($temp)) {
                    $ret = ' ORDER BY ' . implode(",", $temp);
                }
            }
        }
        return $ret;
    }

    /**
     * 解析字段列表规则 支持string 和 数组2种形式
     * @param string $fields = id,name,sex 字段列表
     *        array  $fields array('id' ， 'name' , 'sex')
     * @return string
     */
    public function parseFields($fields = null)
    {
        $ret = '';
        if (!empty($fields)) {
            if (is_array($fields)) {
                $temp = [];
                foreach ($fields as $key => $value) {
                    $temp[] = $this->quoteColumnName($value);
                }
                if (!empty($temp)) {
                    $ret = implode(",", $temp);
                }
            } else {
                $ret = $fields;
            }
        }
        return $ret;
    }

    public function quoteColumnName($name)
    {
        return strpos($name, '`') !== false || $name === '*' ? $name : "`$name`";
    }

    /**
     * Quotes a string value for use in a query.
     * @param string $str string to be quoted
     * @return string the properly quoted string
     * @see http://www.php.net/manual/en/function.PDO-quote.php
     */
    public function quoteValue($str)
    {
        if (!is_string($str)) {
            return $str;
        }

        if (($value = $this->quote($str)) !== false) {
            return $value;
        } else {
            // 驱动不支持 (e.g. oci)
            return "'" . addcslashes(str_replace("'", "''", $str), "\000\n\r\\\032") . "'";
        }
    }
}