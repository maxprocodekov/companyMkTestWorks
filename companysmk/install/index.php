<?php

use Bitrix\Main\Config\Option;

Class companysmk extends CModule
{
    public $MODULE_ID = "companysmk";
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    function __construct()
    {
        $arModuleVersion = array();

        include(__DIR__.'/version.php');

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = "Виджет – cписок компаний";
        $this->MODULE_DESCRIPTION = "После установки вы сможете пользоваться Виджетом";
    }

    function InstallFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/companysmk/install/components",
            $_SERVER["DOCUMENT_ROOT"]."/local/components/mk/companysmk", true, true);
        return true;

    }

    function UnInstallFiles()
    {
        DeleteDirFilesEx("/local/components/mk/companysmk");
        return true;
    }

    function DoInstall()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->registerEventHandler('main', 'onEpilog', $this->MODULE_ID, 'ComponetMk', 'include', '100', '/local/modules/companysmk/include.php');

        Option::set($this->MODULE_ID, "amount", "5");
        Option::set($this->MODULE_ID, "height", "200");
        Option::set($this->MODULE_ID, "switch_on", "Y");

        global $DOCUMENT_ROOT, $APPLICATION;
        $this->InstallFiles();
        RegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile("Установка модуля companysmk", $DOCUMENT_ROOT."/local/modules/companysmk/install/step.php");
    }

    function DoUninstall()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();
        $eventManager->unRegisterEventHandler('main', 'onEpilog', $this->MODULE_ID, 'ComponetMk', 'include', '/local/modules/companysmk/include.php');

        COption::RemoveOption($this->MODULE_ID, "amount");
        COption::RemoveOption($this->MODULE_ID, "height");
        COption::RemoveOption($this->MODULE_ID, "switch_on");

        global $DOCUMENT_ROOT, $APPLICATION;
        $this->UnInstallFiles();
        UnRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile("Деинсталляция модуля companysmk", $DOCUMENT_ROOT."/local/modules/companysmk/install/unstep.php");
    }
}
?>