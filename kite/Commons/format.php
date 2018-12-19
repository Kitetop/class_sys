<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: 0.1
 * Date: 2018/10/2
 */

/**
 * 将类名的开头字母变成小写，确定能够找到真实的文件路径
 * @param $className [包含了命名空间的类名]
 * @return string
 */
function format($className)
{
    return lcfirst(
        str_replace('\\', '/', $className)
    );
}
