<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018-12-24
 */

namespace App\Action\Admin;


use App\Model\Sys_active;
use Kite\Action\AbstractAction;

/**
 * Class AddActive
 * @package App\Action\Admin
 * 系统主席发布新的征稿活动
 */
class AddActive extends AbstractAction
{
    private $postRules = [
        'title' => [
            'desc' => '活动的标题',
            'rules' => ['required'],
            'message' => '活动标题不能为空',
        ],
        'theme' => [
            'rules' => ['required'],
            'desc' => '活动征稿主题',
            'message' => '征稿主题不能为空',
        ],
        'type' => [
            'rules' => ['required'],
            'message' => '审稿模式不能为空',
        ],
        'start_time' => [
            'rules' => ['required'],
            'message' => '开始接稿时间设定不能为空',
        ],
        'end_time' => [
            'rules' => ['required'],
            'message' => '结束接稿时间设定不能为空',
        ],
        'end_check' => [
            'rules' => ['required'],
            'message' => '结束审核时间设定不能为空',
        ]
    ];

    protected function doPost()
    {
        $this->validate($this->postRules);
        if ($this->params['type'] != Sys_active::SINGER_TYPE
            && $this->params['type'] != Sys_active::DOUBLE_TYPE
            && $this->params['type'] != Sys_active::NONE_TYPE) {
            throw new \Exception('非法的论文审稿模式', 400);
        }
        $start_time = substr($this->params['start_time'], 0, 10);
        $end_time = substr($this->params['end_time'], 0, 10);
        $end_check = substr($this->params['end_check'], 0 , 10);
        if ($start_time < $end_time && $start_time < $end_check && $end_time < $end_check) {
            $service = $this->Service('Admin\AddActive');
            $service->title = $this->params['title'];
            $service->theme = $this->params['theme'];
            $service->type = $this->params['type'];
            $service->start_time = $start_time;
            $service->end_time = $end_time;
            $service->end_check = $end_check;
            $service->run();
            $this->response('message', '活动发布成功');
        } else {
            $this->response('message', '活动发布失败，请检查时间先后是否有错');
        }
        $this->code(201);

    }
}