<?php

namespace Arthuryinzhen\UEditor\Uploader;

use OSS\Core\OssException;
use OSS\OssClient;

/**
 *
 *
 * trait UploadAlioss
 *
 * 阿里OSS 上传 类
 *
 * @package Stevenyangecho\UEditor\Uploader
 */
trait UploadAlioss
{

    public function uploadAlioss($key, $file)
    {
        $config = (object)config('UEditorUpload.core.alioss');

        try{
            $ossClient = new OssClient($config->accessKeyId, $config->accessKeySecret, $config->endpoint);
            $ossClient = $ossClient->uploadFile($config->bucket, env('APP_ENV', 'develop') . '/' . $key, $file->getRealPath());

            $this->fullName = $ossClient['oss-request-url'];
            $this->stateInfo = 'SUCCESS';

        } catch(OssException $e) {
            return $e->getMessage();
        }

        return true;
    }
}