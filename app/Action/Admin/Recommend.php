<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018-12-23
 */

namespace App\Action\Admin;


use Kite\Action\AbstractAction;

/**
 * Class Recommend
 * @package App\Action\Admin
 * 推荐审稿人
 */
class Recommend extends AbstractAction
{
    private $postRules = [
        'up_id' => [
            'rules' => ['required'],
            'message' => '论文作者id不能为空',
            'desc' => '论文上传者的id',
        ],
        'active_id' => [
            'rules' => ['required'],
            'message' => '活动id不能为空',
        ]
    ];

    protected function doPost()
    {
        $this->validate($this->postRules);
        $service = $this->Service('Admin\Recommend');
        $service->up_id = $this->params['up_id'];
        $service->active_id = $this->params['active_id'];
        $result = $service->run();
        $this->response($result);
        $this->code(200);
    }
}