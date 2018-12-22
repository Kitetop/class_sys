<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/12/18
 */

namespace App\Service\User;

use App\Model\Sys_active;
use Kite\Commons\Page;
use Kite\Service\AbstractService;
use PDO;

class ActiveList extends AbstractService
{
    protected function execute()
    {
        $active = new Sys_active();
        $result = $active->find()
            ->order(['start_time' => 'desc'])
            ->page(($this->page - 1) * $this->limit, $this->limit)
            ->execute()->fetchAll(PDO::FETCH_ASSOC);
        $this->setType($result);
        $total = $active->count()['total'];
        Page::assemble($result, $total, $this->page, $this->limit);
        return $result;
    }

    /**
     * @param $rows 查询的活动记录
     * 根据字段值设定活动类型
     */
    private function setType(&$rows)
    {
        foreach ($rows as &$row) {
            if($row['type'] == Sys_active::SINGER_TYPE) {
                $row['type'] = '单盲';
            }elseif ($row['type'] == Sys_active::DOUBLE_TYPE) {
                $row['type'] = '双盲';
            }else {
                $row['type'] = '非盲';
            }
        }
    }
}
