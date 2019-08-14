<?php

class MnServiceBootstrapBootstrap
{
    private $loadException = array();

    public function __construct()
    {
        $this->loadException[] = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/Mn/Service/Bootstrap/Bootstrap.php';
        $this->initFileSystem();
    }

    private function initFileSystem()
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/Mn/Lib/FileSystem/FileSystem.php';

        require_once($filePath);
        $this->loadException[] = $filePath;
    }

    public function initLib()
    {
        MnLibFileSystemFileSystem::loadSource($_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/Mn/Lib', $this->loadException);

        return $this;
    }

    public function initModel()
    {
        MnLibFileSystemFileSystem::loadSource($_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/Mn/Model', $this->loadException);

        return $this;
    }

    public function initService()
    {
        MnLibFileSystemFileSystem::loadSource($_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/Mn/Service', $this->loadException);

        return $this;
    }

    public function initHelper()
    {
        MnLibFileSystemFileSystem::loadSource($_SERVER['DOCUMENT_ROOT'] . '/bitrix/php_interface/include/Mn/Helper', $this->loadException);

        return $this;
    }
}
