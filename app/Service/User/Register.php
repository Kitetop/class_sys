<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/12/17
 */

namespace App\Service\User;

use App\Model\Sys_user;
use Kite\Service\AbstractService;

class Register extends AbstractService
{
    protected function execute()
    {

        $user = new Sys_user();
        if((new Sys_user(['phone' => $this->phone]))->exist() || (new Sys_user(['email' => $this->email]))->exist()) {
            throw new \Exception('This user is exist, you can login ', 400);
        }
        $user->import([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'password' => $this->password,
            'status' => Sys_user::USER_NORMAL,
        ])->save();
        $data = $user->find()->where(['or'=>[
            ['phone' => $this->phone],
            ['email' => $this->email]
        ]])->execute()->fetch(\PDO::FETCH_ASSOC);
        return ['id' => $data['id'], 'name' => $data['name']];
    }
}