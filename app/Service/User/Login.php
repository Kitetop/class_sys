<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018/12/17
 */

namespace App\Service\User;

use Kite\Service\AbstractService;
use App\Model\Sys_user;

class Login extends AbstractService
{
    protected function execute()
    {
        $where = [
            'or' => [
                ['email' => $this->account, 'password' => $this->password],
                ['phone' => $this->account, 'password' => $this->password],
            ]
        ];
        $user = new Sys_user($where);
        if ($user->exist()) {
            return ['id' => $user->id,
                'name' => $user->name,
            ];
        }
        throw new \Exception('This account not exist', 401);
    }
}