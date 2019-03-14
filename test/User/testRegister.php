<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2019-01-04
 */

use Kite\Service\AbstractTest;
use Kite\Cycle;
use App\Service\User\Register;

require_once '../../kite/Service/AbstractTest.php';

class testRegister extends AbstractTest
{

    public function testInit()
    {
        $this->initEnv();
        $this->assertNotEmpty(ROOT);
    }

    /**
     * testRegister constructor.
     * @depends      testInit
     * @dataProvider getData
     * @param $account
     * @param $password
     * @param $type
     */
    public function testReg($account, $password)
    {
        $register = new Register((new Cycle())->config());
        $register->phone = $account;
        $register->email = null;
        $register->name = 'kk';
        $register->password = md5($password);
        $this->Exception();
        $register->run();
    }

    /**
     * @param $all
     */
    public function Exception()
    {
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage('This user is exist, you can login');
    }

    public function getData()
    {
        return [
            ['11852865877', '1234'],
            ['11852865877', '1234'],
        ];
    }
}