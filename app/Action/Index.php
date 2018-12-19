<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: 0.1
 * Date: 2018/10/5
 */

namespace App\Action;
use Kite\Action\AbstractAction;

/**
 * 测试Action类
 * Class index
 * @package App\Action
 */
class Index extends AbstractAction
{
    protected $getRules = [
        'id' => [
            'desc' => '分页页码',
            'message' => 'id不能为空',
            'rules' => ['required'],
        ],
    ];
    protected function doGet()
    {
        $this->validate($this->getRules);
        $service = $this->Service('Index');
        $service->username = $this->params['username'];
        $data = $service->run();
        $this->response('message', $data);
        $this->code(201);
    }

    protected $modelFileRules = [
        'modelFile' => [
            'rules' => ['required', 'mime:image/jpeg'],
            'desc' => '模型文件',
        ]
    ];

    protected function doPost()
    {
        $this->validateUploadFile($this->modelFileRules);
        var_dump($this->validatedFiles['modelFile']);exit();
    }
}