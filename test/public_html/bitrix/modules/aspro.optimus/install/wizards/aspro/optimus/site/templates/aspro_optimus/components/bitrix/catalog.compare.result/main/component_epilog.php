<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $templateData */
/** @var @global CMain $APPLICATION */
global $APPLICATION;

$arExtensions = ['owl_carousel'];
\Aspro\Functions\Extensions::init($arExtensions);

$APPLICATION->AddHeadString('<link href="'.SITE_TEMPLATE_PATH.'/css/owl-styles.css"'.' type="text/css" rel="stylesheet" />');
?>

