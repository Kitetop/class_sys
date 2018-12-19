<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018/11/24
 */

namespace App\Console;

use Kite\Console\AbstractConsole;
use Kite\Cycle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class Index
 * @package App\Console
 *
 * 项目入口文件
 */
class Index extends AbstractConsole
{
    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('start')->setDescription('This is Index.php console script');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = $this->Service('Index');
        $service->username = 'kite';
        $data = $service->run();
        echo $data;

    }
}