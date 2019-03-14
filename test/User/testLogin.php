<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2019-01-03
 */

use Kite\Service\AbstractTest;
use Kite\Cycle;
use App\Service\User\Login;

require_once __DIR__ . '/../../kite/Service/AbstractTest.php';

class testLogin extends AbstractTest
{

    public function testInit()
    {
        $this->initEnv();
        $this->assertNotNull(ROOT);
    }

    /**
     * @dataProvider getData
     */
    public function testLoginMessage($account, $password)
    {
        $login = new Login((new Cycle())->config());
        $login->account = $account;
        $login->password = $password;
        $result = $login->run();
        $this->assertNotEmpty($result['id']);
    }

    public function getData()
    {
        return [
            ['18852865877', md5('123456')],
            [18852865877, md5('123456')],
        ];
    }
}