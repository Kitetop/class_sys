<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/11/24
 */

namespace App\Kernel;

use Symfony\Component\Console\Application;


class Console extends Application
{
    public function __construct()
    {
        $name = 'KITE PHP APP Command Line Tool';
        parent::__construct($name);
    }
}