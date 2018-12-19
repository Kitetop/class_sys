<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/12/17
 */

namespace App\Action\User;

use Kite\Action\AbstractAction;

class Login extends AbstractAction
{
    private $getRules = [
        'account' => [
            'desc' => '注册邮箱 | 注册号码',
            'rules' => ['required'],
            'message' => '请输入你的账号信息'
        ],
        'password' => [
            'desc' => '用户密码',
            'rules' => ['required'],
            'message' => '密码不能为空'
        ]
    ];

    protected function doGet()
    {
        $this->validate($this->getRules);
        $this->params['password'] = md5($this->params['password']);
        $service = $this->Service('User\Login');
        $service->account = $this->params['account'];
        $service->password = $this->params['password'];
        $result = $service->run();
        $this->response($result);
        $this->code(200);
    }
}