<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("�������� �� �������");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:subscribe.edit",
	"main",
	Array(
		"AJAX_MODE" => "N",
		"SHOW_HIDDEN" => "N",
		"ALLOW_ANONYMOUS" => "Y",
		"SHOW_AUTH_LINKS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"SET_TITLE" => "N",
		"AJAX_OPTION_SHADOW" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N"
	),
false
);?>
<?if((COptimus::checkVersionModule('16.5.3', 'catalog') && !$GLOBALS['USER']->isAuthorized()) || $GLOBALS['USER']->isAuthorized()):?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.product.subscribe.list",
		"main",
		Array(
			"PRICE_CODE" => array(
				0 => "BASE",
			),
			"SHOW_PRICE_COUNT" => "1",
			"PRICE_VAT_INCLUDE" => "Y",
			"PRICE_VAT_SHOW_VALUE" => "N",
			"CONVERT_CURRENCY" => "Y",
			"CURRENCY_ID" => "RUB",
			"SHOW_OLD_PRICE" => "Y",
			"OFFER_HIDE_NAME_PROPS" => "N",
			"SHOW_MEASURE" => "Y",
			"DISPLAY_COMPARE" => "Y",
			"CACHE_TIME" => "3600",
			"CACHE_TYPE" => "A",
			"SET_TITLE" => "N",
			"LINE_ELEMENT_COUNT" => "3"
		),
		false
	);?>
<?endif;?>
<div class="clearfix"></div>
<div class="personal_menu">
	<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
		array(
			"COMPONENT_TEMPLATE" => ".default",
			"PATH" => SITE_DIR."include/left_block/menu.left_menu.php",
			"AREA_FILE_SHOW" => "file",
			"AREA_FILE_SUFFIX" => "",
			"AREA_FILE_RECURSIVE" => "Y",
			"EDIT_TEMPLATE" => "standard.php"
		),
		false
	);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>