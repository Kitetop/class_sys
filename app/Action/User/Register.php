<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/12/17
 */

namespace App\Action\User;

use Kite\Action\AbstractAction;


class Register extends AbstractAction
{
    private $postRules = [
        'email' => [
            'desc' => '注册邮箱',
            'rules' => ['email'],
            'message' => '请输入正确的邮箱格式'
        ],
        'phone' => [
            'desc' => '注册号码',
            'rules' => ['mobile'],
            'message' => '无效的电话号码'
        ],
        'password' => [
            'desc' => '用户密码',
            'rules' => ['required'],
            'message' => '密码不能为空'
        ],
        'name' => [
            'desc' => '用户名',
            'rules' => ['required'],
            'message' => '用户名不能为空'
        ]
    ];

    public function doPost()
    {
        $this->validate($this->postRules);
        if (!isset($this->params['email'])
            && !isset($this->params['phone'])) {
            throw new \Exception('电话号码 | 邮箱不能均为空', 400);
        }
        $service = $this->Service('User\Register');
        $service->email = $this->params['email'];
        $service->phone = $this->params['phone'];
        $service->name = $this->params['name'];
        $service->password = md5($this->params['password']);
        $result = $service->run();
        $result['message'] = '用户创建成功';
        $this->response($result);
        $this->code(201);
    }
}