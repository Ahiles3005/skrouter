<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$ALX = "FID".$arParams["FORM_ID"];

if($arParams['JQUERY_EN'] == 'jquery2')
	CJSCore::Init(array('jquery2'));
elseif($arParams['JQUERY_EN'] != 'N')
	CJSCore::Init(array("jquery"));

if(is_array($arParams["PROPERTY_FIELDS"]) && !empty($arParams["MASKED_INPUT_PHONE"]) && is_array($arParams["MASKED_INPUT_PHONE"]))
{
	$APPLICATION->AddHeadScript('/bitrix/js/altasib.feedback/jquery.maskedinput/jquery.maskedinput.min.js');
}

if($arParams["USE_CAPTCHA"] == "Y" && $arParams["CAPTCHA_TYPE"] == "recaptcha")
{
	$APPLICATION->AddHeadScript('https://www.google.com/recaptcha/api.js?onload=AltasibFeedbackOnload_'.$ALX.'&render=explicit&hl='.LANGUAGE_ID);
}

if(!empty($arParams['COLOR_OTHER']) || !empty($arParams['COLOR_THEME']))
{
	if(file_exists(__DIR__ . '/class.php'))
		include_once __DIR__ . '/class.php';
	CAltasibFeedbackThemes::Generate($arParams['COLOR_OTHER'], $arParams['COLOR_THEME'], $arParams['COLOR_SCHEME'], $ALX);
}

?>