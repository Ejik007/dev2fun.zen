<?php
/**
 * @author dev2fun <darkfriend>
 * @copyright (c) 2026, darkfriend <hi@darkfriend.ru>
 * @version 1.1.1
 */
IncludeModuleLangFile(__FILE__);

\Bitrix\Main\Loader::registerAutoLoadClasses(
    "dev2fun.zen",
    [
        'Dev2funYandexZen' => 'include.php',
    ]
);

if (class_exists('Dev2funYandexZen')) return;

use \Bitrix\Main\Localization\Loc;

class Dev2funYandexZen
{

    private static $instance;
    public static $module_id = 'dev2fun.zen';

    /**
     * Singleton instance
     * @return self
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Dev2funYandexZen();
        }
        return self::$instance;
    }

    public static function getOption($name, $serialize = false)
    {
        $option = \Bitrix\Main\Config\Option::get(self::$module_id, $name);
        if ($serialize) $option = unserialize($option);
        return $option;
    }

    public static function clearCache()
    {
        $obCache = \Bitrix\Main\Data\Cache::createInstance();
        $obCache->cleanDir('dev2fun.zen');
        $obCache->cleanDir('/dev2fun.zen/');
        
        if(isset($GLOBALS['CACHE_MANAGER'])) {
            $GLOBALS['CACHE_MANAGER']->CleanDir('dev2fun.zen');
            $GLOBALS['CACHE_MANAGER']->CleanDir('/dev2fun.zen/');
        }

        if (class_exists('\CBitrixComponent')) {
            \BXClearCache(true, "/dev2fun.zen/");
            \BXClearCache(true, "dev2fun.zen");
        }

        $rsSites = \CSite::GetList($by="sort", $order="desc", array("ACTIVE" => "Y"));
        while ($arSite = $rsSites->Fetch()) {
            $obCache->cleanDir('dev2fun.zen/'.$arSite["LID"]);
            $obCache->cleanDir('/dev2fun.zen/'.$arSite["LID"].'/');
            if(isset($GLOBALS['CACHE_MANAGER'])) {
                $GLOBALS['CACHE_MANAGER']->CleanDir('dev2fun.zen/'.$arSite["LID"]);
            }
            if (class_exists('\CBitrixComponent')) {
                \BXClearCache(true, "/dev2fun.zen/".$arSite["LID"]."/");
            }
        }

        if (class_exists('\Bitrix\Main\Data\StaticHtmlCache')) {
            $staticHtmlCache = \Bitrix\Main\Data\StaticHtmlCache::getInstance();
            $staticHtmlCache->deleteAll();
        }
        return true;
    }

    public static function ShowThanksNotice()
    {
        global $APPLICATION;
        \CAdminNotify::Add([
            'MESSAGE' => Loc::getMessage('D2F_YANDEXZEN_DONATE_MESSAGE', ['#URL#' => '/bitrix/admin/settings.php?mid=dev2fun.zen&mid_menu=1&tabControl_active_tab=donate']),
            'TAG' => 'dev2fun_yandexzen_update',
            'MODULE_ID' => 'dev2fun.zen',
        ]);
    }
}