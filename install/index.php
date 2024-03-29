<?
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

Class uni_recaptcha extends CModule
{
	var $MODULE_ID = "uni.recaptcha_2";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";

	function uni_recaptcha()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("CAPTCHA_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("CAPTCHA_INSTALL_DESCRIPTION");
		$this->PARTNER_NAME = GetMessage("SPER_PARTNER");
		$this->PARTNER_URI = GetMessage("PARTNER_URI");
	}

  function InstallDB()
    {
        RegisterModule("uni.recaptcha_2");
        return true;
    }

    function UnInstallDB()
    {
		COption::RemoveOption("uni.recaptcha_2");
        UnRegisterModule("uni.recaptcha_2");
        return true;
    }

    function InstallEvents()
    {
		RegisterModuleDependences("main", "OnPageStart", "uni.recaptcha_2", "ReCaptchaTwoGoogle", "OnVerificContent");
		RegisterModuleDependences("main", "OnEndBufferContent", "uni.recaptcha_2", "ReCaptchaTwoGoogle", "OnAddContentCaptcha");
        return true;
    }

    function UnInstallEvents()
    {
		UnRegisterModuleDependences("main", "OnPageStart", "uni.recaptcha_2", "ReCaptchaTwoGoogle", "OnVerificContent");
		UnRegisterModuleDependences("main", "OnEndBufferContent", "uni.recaptcha_2", "ReCaptchaTwoGoogle", "OnAddContentCaptcha");
        return true;
    }

    function InstallFiles()
    {
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/uni.recaptcha_2/install/js", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js", true, true);
        return true;
    }

    function UnInstallFiles()
    {
		DeleteDirFilesEx("/bitrix/js/uni.recaptcha_2/");
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;

        if (!IsModuleInstalled("uni.recaptcha_2"))
        {
            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();
        }
    }

    function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
    }
}
?>
