<?php

namespace Arthuryinzhen\UEditor;

use OSS\Core\OssException;
use OSS\OssClient;

/**
 * 列表文件 for 阿里OSS
 * Class ListsQiniu
 * @package Stevenyangecho\UEditor
 */
class ListsAlioss
{
    public function __construct($allowFiles, $listSize, $path, $request)
    {
        $this->allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);
        $this->listSize = $listSize;
        $this->path = env('APP_ENV', 'develop') . '/' . ltrim($path,'/');
        $this->request = $request;
    }

    public function getList()
    {
        $config = (object)config('UEditorUpload.core.alioss');

        $size = $this->request->get('size', $this->listSize);
        $start = $this->request->get('start', '');
        $ossClient = new OssClient($config->accessKeyId, $config->accessKeySecret, $config->endpoint);


        $prefix = $this->path;
        $delimiter = '/';
        $nextMarker = '';
        $maxkeys = 10;
        $options = array(
            'delimiter' => $delimiter,
            'prefix' => $prefix,
            'max-keys' => $maxkeys,
            'marker' => $nextMarker,
        );
        try {
            $listObjectInfo = $ossClient->listObjects($config->bucket, $options);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
        $objectList = $listObjectInfo->getObjectList(); // object list
        $prefixList = $listObjectInfo->getPrefixList(); // directory list
        if (!empty($objectList)) {
            print("objectList:\n");
            foreach ($objectList as $objectInfo) {
                print($objectInfo->getKey() . "\n");
            }
        }
        if (!empty($prefixList)) {
            print("prefixList: \n");
            foreach ($prefixList as $prefixInfo) {
                print($prefixInfo->getPrefix() . "\n");
            }
        }

    }


}
