<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: 0.1
 * Date: 2018/11/26
 */

namespace Kite\Model\MySQL;


final class MySQL
{
    private $dsn;
    private $user;
    private $password;
    private $table;
    /**
     * @var LibPDO object
     */
    private $pdo;
    /**
     * @var 数据库的操作语句
     */
    protected $query = '';
    /**
     * @var array 用于防止sql注入后续传入的值
     */
    protected $bindValues = [];

    public function __construct(array $config)
    {
        $this->dsn = $config['dsn'];
        $this->user = $config['user'];
        $this->password = $config['password'];
    }

    public function connect($table)
    {
        try {
            $this->table = $table;
            $this->pdo = new \PDO($this->dsn, $this->user, $this->password);
            $this->pdo->query('set names utf8');
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function select()
    {
        $this->bindValues = [];
        $this->query = 'select * from ' . $this->table;
        return $this;
    }

    public function count()
    {
        $this->bindValues = [];
        $this->query = 'select count(*) as total from ' . $this->table;
        return $this;
    }

    public function update(array $arr)
    {
        $this->bindValues = [];
        foreach ($arr as $key => $value) {
            $update[] = "`{$key}` = ?";
            $this->bindValues[] = $value;
        }
        $this->query = 'update ' . $this->table . ' set ' . implode(',' , $update) ;
        return $this;
    }

    /**
     * @return $this
     * 删除指定的数据
     */
    public function delete()
    {
        $this->bindValues = [];
        $this->query = 'delete from ' . $this->table;
        return $this;
    }

    /**
     * 向表中插入数据
     */
    public function create()
    {
        $this->query = 'insert into ' . $this->table . $this->query . ' ';
        return $this;
    }

    public function execute()
    {
        $exec = $this->pdo->prepare($this->query);
        if (!$exec->execute($this->bindValues)) {
            throw new \Exception('SQL operation error :' . $exec->errorInfo()[2], 500);
        }
        $this->bindValues = [];
        return $exec;
    }

    #################################################
    #                  查询构造器                    #
    #                  构造SQL语句                  #
    #               调用顺序请和mysql保存一致        #
    ###############################################
    public function where(array $where)
    {
        $where = LibPDO::getInstance()->parseWhere($where);
        $this->query = $this->query . ' where ' . $where['where'];
        foreach ($where['params'] as $value) {
            $this->bindValues[] = $value;
        }
        return $this;
    }

    public function group(array $group)
    {

    }

    public function order(array $order)
    {
        $order = LibPDO::getInstance()->parseOrder($order);
        $this->query = $this->query . ' ' . $order;
        return $this;
    }

    /**
     * @param $offset 偏移量
     * @param $limit  每页的数量
     * @return $this  当前对象
     * 数据分页
     */
    public function page($offset, $limit)
    {
        $this->query = $this->query . ' limit ' . $offset . ',' . $limit;
        return $this;
    }

    /**
     * @param array $array 需要插入的数据
     */
    public function import(array $array)
    {
        foreach ($array as $key => $value) {
            $insert_keys[] = "`{$key}`";
            $insert_values[] = '?';
            $this->bindValues[] = $value;
        }
        $this->query = '(' . implode(',', $insert_keys) . ') values (' . implode(',', $insert_values) . ')';
    }
}