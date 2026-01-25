<?php
/**
 * @author darkfriend <hi@darkfriend.ru>
 * @version 1.1.0
 */

if (!$USER->isAdmin()) {
	$APPLICATION->authForm('Nope');
}
CModule::IncludeModule("dev2fun.zen");

use \Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

$app = \Bitrix\Main\Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();
$curModuleName = "dev2fun.zen";

IncludeModuleLangFile(__FILE__);
//require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");
if($request->isPost() && check_bitrix_sessid()) {


    if($request->getPost('clear_cache')) {
        Dev2funYandexZen::clearCache();
		LocalRedirect($APPLICATION->GetCurPageParam('cache_success=Y',['save_success']));

	} else {

		if($blogName = $request->getPost('blog_name')) {
			Option::set($curModuleName,'blog_name', $blogName);
		}
		if($description = $request->getPost('blog_description')) {
			Option::set($curModuleName,'blog_description', $description);
		}
		if($previewLength = $request->getPost('preview_text_length')) {
			Option::set($curModuleName,'preview_text_length', $previewLength);
		}
		if($ageRating = $request->getPost('age_rating')) {
			Option::set($curModuleName,'age_rating', $ageRating);
		}
		if($cat = $request->getPost('blog_categories')) {
			$cat = serialize($cat);
			Option::set($curModuleName,'blog_categories',$cat);
		}
		if($allow = $request->getPost('tags_allow')) {
			Option::set($curModuleName,'tags_allow',$allow);
		}
		if($utmSource = $request->getPost('utm_source')) {
			Option::set($curModuleName,'utm_source',$utmSource);
		}
		if($utmMedium = $request->getPost('utm_medium')) {
			Option::set($curModuleName,'utm_medium',$utmMedium);
		}
		if($zenCategories = $request->getPost('zen_categories')) {
			Option::set($curModuleName,'zen_categories',$zenCategories);
		}
		$utmTerm = $request->getPost('utm_term');
		if(!$utmTerm) $utmTerm = 'N';
        Option::set($curModuleName,'utm_term',$utmTerm);

		Dev2funYandexZen::clearCache();
		LocalRedirect($APPLICATION->GetCurPageParam('save_success=Y',['cache_success']));
    }
}

if(!empty($_REQUEST['save_success'])) {
	\CAdminMessage::showMessage(array(
		"MESSAGE" => Loc::getMessage("D2F_YANDEXZEN_OPTIONS_SAVED"),
		"TYPE" => "OK",
	));
}

if(!empty($_REQUEST['cache_success'])) {
	\CAdminMessage::showMessage(array(
		"MESSAGE" => Loc::getMessage("D2F_YANDEXZEN_OPTIONS_CLEARED"),
		"TYPE" => "OK",
	));
}

$aTabs = array(
	array(
		"DIV" => "main",
		"TAB" => Loc::getMessage("DEV2FUN_YANDEXZEN_SEC_MAIN_TAB"),
		"ICON"=>"main_user_edit",
		"TITLE"=>Loc::getMessage("DEV2FUN_YANDEXZEN_SEC_MAIN_TAB_TITLE"),
	),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs, true, true);
$bVarsFromForm = false;

//require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");
?>

<link rel="stylesheet" href="https://unpkg.com/blaze@4.0.0-6/scss/dist/components.cards.min.css">
<link rel="stylesheet" href="https://unpkg.com/blaze@4.0.0-6/scss/dist/objects.grid.min.css">
<link rel="stylesheet" href="https://unpkg.com/blaze@4.0.0-6/scss/dist/objects.grid.responsive.min.css">
<link rel="stylesheet" href="https://unpkg.com/blaze@4.0.0-6/scss/dist/objects.containers.min.css">
<link rel="stylesheet" href="https://unpkg.com/blaze@4.0.0-6/scss/dist/components.tables.min.css">

<!--<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />-->
<!--<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />-->
<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->

<?
$tabControl->Begin();
//$tabControl->BeginNextTab();
?>

<form method="post" action="<?=sprintf('%s?mid=%s&lang=%s', $request->getRequestedPage(), urlencode($mid), LANGUAGE_ID)?>">
    <?php
    echo bitrix_sessid_post();
    $tabControl->BeginNextTab();
    ?>
    <tr>
        <td colspan="2" align="left">
            <table class="adm-detail-content-table edit-table">

                <? if(file_exists($_SERVER['DOCUMENT_ROOT'].'/yandex.zen/')) {?>
                <tr>
                    <td class="adm-detail-content-cell-l">
                        <label for="blog_name">
							<?=Loc::getMessage("D2F_MODULE_ZEN_OPTIONS_LINK_TO_RSS") ?>:
                        </label>
                    </td>
                    <td width="60%" class="adm-detail-content-cell-r">
                        <a href="/yandex.zen/" target="_blank">Yandex.Zen RSS</a>
                    </td>
                </tr>
                <? } ?>

                <tr>
                    <td class="adm-detail-content-cell-l">
                        <label for="blog_name">
							<?=Loc::getMessage("D2F_MODULE_ZEN_OPTIONS_BLOG_NAME") ?>:
                        </label>
                    </td>
                    <td width="60%" class="adm-detail-content-cell-r">
                        <table class="nopadding" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td>
                                    <input type="text"
                                           name="blog_name"
                                           value="<?=Option::get($curModuleName, "blog_name");?>"
                                    />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="adm-detail-content-cell-l">
                        <label for="blog_description">
							<?=Loc::getMessage("D2F_MODULE_ZEN_OPTIONS_DESCRIPTION") ?>:
                        </label>
                    </td>
                    <td width="60%" class="adm-detail-content-cell-r">
                        <table class="nopadding" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td>
                                    <textarea rows="10" cols="30" name="blog_description"><?=Option::get($curModuleName, "blog_description");?></textarea>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="adm-detail-content-cell-l">
                        <label for="preview_text_length">
							<?=Loc::getMessage("D2F_MODULE_ZEN_OPTIONS_PREVIEW_TEXT_LENGTH")?>:
                        </label>
                    </td>
                    <td width="60%" class="adm-detail-content-cell-r">
                        <table class="nopadding" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td>
                                    <input type="text"
                                           size="20"
                                           name="preview_text_length"
                                           value="<?=Option::get($curModuleName, "preview_text_length", '200');?>"
                                    />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="adm-detail-content-cell-l">
                        <label for="age_rating">
							<?=Loc::getMessage("D2F_MODULE_ZEN_OPTIONS_AGE_RATING")?>:
                        </label>
                    </td>
                    <td width="60%" class="adm-detail-content-cell-r">
                        <table class="nopadding" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td>
									<? $selectAgeRating = Option::get($curModuleName, "age_rating", 'nonadult'); ?>
                                    <select name="age_rating">
										<? foreach(['adult','nonadult'] as $val) {?>
                                            <option value="<?=$val?>" <?=($val==$selectAgeRating)?'selected':''?>>
												<?=Loc::getMessage("D2F_MODULE_ZEN_OPTIONS_AGE_RATING_{$val}")?>
                                            </option>
										<? } ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="adm-detail-content-cell-l">
                        <label for="blog_categories">
							<?=Loc::getMessage("D2F_MODULE_ZEN_OPTIONS_CATEGORIES")?>:
                        </label>
                    </td>
                    <td width="60%" class="adm-detail-content-cell-r">
                        <table class="nopadding" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td>
									<?
									$selectCategories = Option::get($curModuleName, "blog_categories");
									if($selectCategories) {
										$selectCategories = unserialize($selectCategories);
										if(!is_array($selectCategories)) {
											$selectCategories = array();
										}
									} else {
										$selectCategories = array();
									}
									?>
                                    <select name="blog_categories[]" multiple="multiple" style="width:30%">
										<? foreach(Dev2funYandexZen::getCategories() as $val) {?>
                                            <option value="<?=$val?>" <?=(in_array($val,$selectCategories))?'selected':''?>>
												<?=$val?>
                                            </option>
										<? } ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="adm-detail-content-cell-l">
                        <label for="tags_allow">
							<?=Loc::getMessage("D2F_MODULE_ZEN_OPTIONS_TAGS_ALLOW") ?>:
                        </label>
                    </td>
                    <td width="60%" class="adm-detail-content-cell-r">
                        <table class="nopadding" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td>
                                    <input type="text"
                                           name="tags_allow"
                                           value="<?=Option::get($curModuleName, "tags_allow", '<a><img><iframe><blockquotes><figure><p><h1><h2><h3><h4><h5><h6><br>');?>"
                                    />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="adm-detail-content-cell-l">
                        <label for="zen_categories">
							<?=Loc::getMessage("D2F_MODULE_ZEN_OPTIONS_ZEN_CATEGORIES") ?>:
                        </label>
                    </td>
                    <td width="60%" class="adm-detail-content-cell-r">
                        <table class="nopadding" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td>
                                    <input type="text"
                                           name="zen_categories"
                                           value="<?=Option::get($curModuleName, "zen_categories", 'index, comment-all, format-article');?>"
                                    />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

<!-- 
                <tr>
                    <td class="adm-detail-content-cell-l">
                        <label for="utm_source">
							<?=Loc::getMessage("D2F_MODULE_ZEN_OPTIONS_UTM_SOURCE") ?>:
                        </label>
                    </td>
                    <td width="60%" class="adm-detail-content-cell-r">
                        <table class="nopadding" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td>
                                    <input type="text"
                                           name="utm_source"
                                           value="<?=Option::get($curModuleName, "utm_source", 'zen');?>"
                                    />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="adm-detail-content-cell-l">
                        <label for="utm_medium">
							<?=Loc::getMessage("D2F_MODULE_ZEN_OPTIONS_UTM_MEDIUM") ?>:
                        </label>
                    </td>
                    <td width="60%" class="adm-detail-content-cell-r">
                        <table class="nopadding" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td>
                                    <input type="text"
                                           name="utm_medium"
                                           value="<?=Option::get($curModuleName, "utm_medium", 'referral');?>"
                                    />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="adm-detail-content-cell-l">
                        <label for="utm_term">
							<?=Loc::getMessage("D2F_MODULE_ZEN_OPTIONS_UTM_TERM") ?>:
                        </label>
                    </td>
                    <td width="60%" class="adm-detail-content-cell-r">
                        <table class="nopadding" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td>
									<? $selectUtmTerm = Option::get($curModuleName, "utm_term", 'Y'); ?>
                                    <input type="checkbox"
                                           name="utm_term"
                                           value="Y"
										<?=($selectUtmTerm=='Y')?'checked':''?>
                                    />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr> -->


            </table>
        </td>
    </tr>
	<?php
	//$tabControl->buttons();
	?>
    <tr>
        <td colspan="2">
            <div style="margin: 20px auto 0 auto; display: table;">
                <input type="submit"
                       name="save"
                       value="<?=Loc::getMessage("MAIN_SAVE") ?>"
                       title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE") ?>"
                       class="adm-btn-save"
                       style="margin-right: 10px;"
                />
                <input type="submit"
                       name="clear_cache"
                       value="<?=Loc::getMessage("D2F_MODULE_ZEN_OPTIONS_CLEAR_CACHE")?>"
                />
            </div>
        </td>
    </tr>
</form>

<?
$tabControl->End();
?>