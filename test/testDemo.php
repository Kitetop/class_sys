<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2019-01-03
 */

require_once '../kite/Service/AbstractTest.php';

use Kite\Service\AbstractTest;

class testDemo extends AbstractTest
{
    private $stack = [];

    public function setUp()
    {
        $this->stack = [];
    }

    public function testEmpty()
    {
        $this->assertEmpty($this->stack);
        return $this->stack;
    }

    /**
     * @depends testEmpty
     */
    public function testPush(array $stack)
    {
        array_push($stack, 'kitetop');
        $this->assertNotEmpty($stack);
        $this->assertEquals('kitetop', $stack[count($stack) - 1]);
        return $stack;
    }

    /**
     * @depends testPush
     */
    public function testPop(array $stack)
    {
        $this->assertEquals('kitetop', array_pop($stack));
        $this->testEmpty($stack);
    }
}