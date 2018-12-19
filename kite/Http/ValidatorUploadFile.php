<?php

namespace Kite\Http;

use Finfo;
use RuntimeException;
use Kite\Commons\UploadFile;

/**
 * UploadFile的验证
 *
 * @see Validator
 * @author huangjide <hjd@moxiu.net>
 * @license proprietary
 * @copyright Copyright (c) 魔秀科技(北京)股份有限公司
 */
class ValidatorUploadFile extends Validator
{
    protected $validateMethodPrefix = 'validateFile';


    protected function genItem($key, $item, $value)
    {
        //进行文件是否正常的基本验证
        try {
            $this->baseValidate($value);
        } catch (RuntimeException $e) {
            $this->lastError = isset($item['message']) ? $item['message'] : $e->getMessage();
            return false;
        }

        return parent::genItem($key, $item, $value);
    }

    /**
     * 验证文件大小
     *
     * @param UploadFile $uploadFile
     * @param int $params
     * @return boolean
     */
    protected function validateFileSize($uploadFile, $params)
    {
        return $uploadFile->getSize() <= (int) $params;
    }

    /**
     * 验证文件mime类型
     * doc => mime:application/msword
     * rar => mime:application/octet-stream
     * pdf => mime:application/pdf
     * zip => mime:application/zip
     * gif => mime:image/gif
     * jpeg => mime:image/jpeg
     * jpg => mime:image/jpg
     * @param UploadFile $uploadFile
     * @param string $params
     * @return boolean
     */
    protected function validateFileMime($uploadFile, $params)
    {
        $mimes = explode(',', $params);
        $finfo = new Finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($uploadFile->getRealPath());
        return in_array(strtolower($mime), $mimes);
    }

    /**
     * 验证扩展名字
     *
     * @param mixed $uploadFile
     * @param mixed $params
     * @return mixed
     */
    protected function validateFileExt($uploadFile, $params)
    {
        $exts = explode(',', $params);
        $ext = pathinfo($uploadFile->getUploadInfo()['name'], PATHINFO_EXTENSION);
        return in_array(strtolower($ext), $exts);
    }

    /**
     * 基本验证
     *
     * @param UploadFile $uploadFile
     * @return void
     */
    private function baseValidate($uploadFile)
    {
        if (!$uploadFile instanceof UploadFile) {
            throw new RuntimeException('param must be instanceof UploadFile');
        }

        $info = $uploadFile->getUploadInfo();

        switch ($info['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }
    }

    /**
     * 扩展的 Required判断
     *
     * @param UploadFile $value
     * @return boolean
     */
    protected function validateFileRequired($value)
    {
        if (is_null($value)) {
            return false;
        } else {
            return $value instanceof UploadFile;
        }
    }
}
