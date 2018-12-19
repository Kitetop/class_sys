<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/23
 * Time: 10:13
 */

namespace Kite\Http\Phase;
use Kite\Cycle;

/**
 * Interface PhaseInterface
 * @package Kite\Http\Phase
 * 阶段的公共接口
 */
interface PhaseInterface
{
    public function run(Cycle $cycle);
}