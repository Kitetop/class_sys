<?php

namespace  Kite\Commons;

use SplFileInfo;

/**
 * UploadFile 对$_FILE上传内容的对象使用
 *
 * @see SplFileInfo
 * @author huangjide <hjd@moxiu.net>
 * @license proprietary
 * @copyright Copyright (c) 魔秀科技(北京)股份有限公司
 */
class UploadFile extends SplFileInfo
{
    /**
     * 上传的file信息
     *
     * @var array
     */
    private $info;

    /**
     * 构造函数
     *
     * @param array $info
     * @return UploadFile
     */
    public function __construct($info)
    {
        $this->info = $info;
        parent::__construct($info['tmp_name']);
    }

    public function getUploadInfo($key = null)
    {
        return null === $key ? $this->info : $this->info[$key];
    }

    /**
     * 文件是否上传成功
     *
     * @return boolean
     */
    public function isValid()
    {
        return UPLOAD_ERR_OK === $this->info['error'] &&
            is_uploaded_file($this->getPathname());
    }

    /**
     * 移动到新路径
     *
     * @param string $path
     * @return boolean
     */
    public function moveTo($path)
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return move_uploaded_file($this->info['tmp_name'], $path);
    }

    public function getExt()
    {
        return pathinfo($this->info['name'], PATHINFO_EXTENSION);
    }
}
