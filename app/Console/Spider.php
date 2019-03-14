<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018-12-30
 */

namespace App\Console;

use Kite\Console\AbstractConsole;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Spider extends AbstractConsole
{
    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('spider')->setDescription('This is Spider console script');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = $this->Service('Spider');
        $data = $service->run();
        var_dump($data);

    }
}