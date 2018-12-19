<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: 0.1
 * Date: 2018/11/26
 */

namespace Kite\Model;

use PDO;

Abstract class AbstractModel
{
    protected $config = [];
    /**
     * @var 数据库对象
     */
    protected $model;
    /**
     * @var 表名
     */
    protected $table;
    /**
     * @var 查询的结果集
     */
    protected $rows;
    /**
     * @var 数据库的类型
     */
    protected $dataBase = 'MySQL';
    /**
     * @var bool 修改字段
     */
    protected $update = [];
    /**
     * @var string 数据表的主键
     */
    protected $primary = 'id';

    public function __construct($where = null)
    {
        $this->init($where, $this->dataBase);
    }

    public function __get($name)
    {
        if (!isset($this->rows[$name])) {
            throw new \Exception('this value not exist in your table', 500);
        }
        return $this->rows[$name];
    }

    public function __set($name, $value)
    {
        if (empty($this->rows)) {
            throw new \Exception('you have not select any date', 500);
        }
        $this->update[$name] = $value;
        $this->rows[$name] = $value;
    }

    protected function config()
    {
        if (isset($this->dataBase)) {
            $config = require APP . '/Config/dev.php';
            $this->config = $config[$this->dataBase];
            return $this->config;
        } else {
            throw new \Exception('DataBase type can not be null', 500);
        }
    }

    /**
     * @param $dbType
     * @throws \Exception
     * model 的前置操作，初始化，得到对应的数据库对象、设置操作表名
     */
    protected function init($where)
    {
        $this->config();
        $this->table = $this->table();
        $this->model = $this->connect($this->config);
        if (isset($where)) {
            $this->rows = $this->find()->where($where)->execute()->fetch(PDO::FETCH_ASSOC);
        }
    }

    /**
     * @param $dbType 数据库类型
     * @param array $config 配置信息
     * @throws
     * @return mixed 对应的数据库对象
     */
    protected function connect(array $config)
    {
        $class = 'Kite\\Model\\' . $this->dataBase . '\\' . $this->dataBase;
        if (class_exists($class)) {
            $db = new $class($config);
            $db->connect($this->table);
            return $db;
        } else {
            throw new \Exception('This model is not exit', 500);
        }
    }

    /**
     * @param array $array 需要更新的数据
     */
    public function import(array $array)
    {
        $this->model->import($array);
        return $this;
    }

    /**
     * @return mixed 查询到的结果集
     */
    public function find()
    {
        return $this->model->select();
    }

    public function remove(array $where = [])
    {
        if (empty($where)) {
            $this->model->delete()->where([$this->primary => $this->rows[$this->primary]])->execute();
        } else {
            $this->model->delete()->where($where)->execute();
        };
    }

    /**
     * @return bool 判断查询的结果集是否为空
     */
    public function exist()
    {
        if (!$this->rows) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $value 插入数据库的字段
     * 表的更新以及插入操作的统一操作
     */
    public function save()
    {
        if (empty($this->update)) {
            $this->model->create()->execute();
        } else {
            $this->model->update($this->update)->where([$this->primary => $this->rows[$this->primary]])->execute();
        }
    }

    public function count($where = [])
    {
       return $this->model->count()->where($where)->execute()->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $key 主键的名字
     * 设置主键
     */
    public function setPrimary(string $key)
    {
        $this->primary = $key;
    }

    /**
     * @return mixed 操作的表名或者集合名
     */
    abstract protected function table();
}
