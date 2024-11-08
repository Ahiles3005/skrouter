<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

Loader::includeModule("iblock");
Loader::includeModule("highloadblock");

// get current section & element
global $OptimusSectionID;
$arSection = $arElement = array();
$arParams["LANDING_IBLOCK_ID"] = ($arParams["LANDING_IBLOCK_ID"] ? $arParams["LANDING_IBLOCK_ID"] : COptimusCache::$arIBlocks[SITE_ID]["aspro_optimus_catalog"]["aspro_optimus_landing"][0]);

$arElementSelect = [
	"ID", 
	"IBLOCK_ID", 
	"IBLOCK_SECTION_ID", 
	"NAME", 
	"PREVIEW_TEXT", 
	"PREVIEW_PICTURE", 
	"DETAIL_PICTURE", 
	"PROPERTY_FILTER_URL", 
	"PROPERTY_SECTION", 
	"ElementValues", 
];

//$arElementSelect = array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_FILTER_URL", "PROPERTY_SECTION");

if($arResult["VARIABLES"]["SECTION_ID"] > 0){
	$arSection = COptimusCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => COptimusCache::GetIBlockCacheTag($arParams["LANDING_IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arResult["VARIABLES"]["SECTION_ID"], "IBLOCK_ID" => $arParams["LANDING_IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "UF_TIZERS", "NAME", "DEPTH_LEVEL", "LEFT_MARGIN", "RIGHT_MARGIN", "UF_OFFERS_TYPE"));
}
elseif(strlen(trim($arResult["VARIABLES"]["SECTION_CODE"])) > 0){
	$arSection = COptimusCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => COptimusCache::GetIBlockCacheTag($arParams["LANDING_IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "=CODE" => $arResult["VARIABLES"]["SECTION_CODE"], "IBLOCK_ID" => $arParams["LANDING_IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "UF_TIZERS", "NAME", "DEPTH_LEVEL", "LEFT_MARGIN", "RIGHT_MARGIN", "UF_OFFERS_TYPE"));
}

if($arResult["VARIABLES"]["ELEMENT_ID"] > 0){
	$arElement = COptimusCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"Y", "TAG" => COptimusCache::GetIBlockCacheTag($arParams["LANDING_IBLOCK_ID"]))), array("IBLOCK_ID" => $arParams["LANDING_IBLOCK_ID"], "ACTIVE"=>"Y", "ID" => $arResult["VARIABLES"]["ELEMENT_ID"]), false, false, $arElementSelect);
}
elseif(strlen(trim($arResult["VARIABLES"]["ELEMENT_CODE"])) > 0){
	$arElement = COptimusCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" =>"Y", "TAG" => COptimusCache::GetIBlockCacheTag($arParams["LANDING_IBLOCK_ID"]))), array("IBLOCK_ID" => $arParams["LANDING_IBLOCK_ID"], "ACTIVE"=>"Y", "=CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"]), false, false, $arElementSelect);

}

if(!$arSection){
	if($arElement["IBLOCK_SECTION_ID"]){
		$sid = ((isset($arElement["IBLOCK_SECTION_ID_SELECTED"]) && $arElement["IBLOCK_SECTION_ID_SELECTED"]) ? $arElement["IBLOCK_SECTION_ID_SELECTED"] : $arElement["IBLOCK_SECTION_ID"]);
		$arSection = COptimusCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => COptimusCache::GetIBlockCacheTag($arParams["LANDING_IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $sid, "IBLOCK_ID" => $arElement["LANDING_IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "UF_TIZERS", "NAME"));
	}
}
if($arElement)
	$arElement = current($arElement);

$OptimusSectionID = $arSection["ID"];

global $TEMPLATE_OPTIONS;
$arParams["DISPLAY_WISH_BUTTONS"] = \Bitrix\Main\Config\Option::get('aspro.optimus', 'SHOW_DELAY', 'Y');

if(\Bitrix\Main\Config\Option::get('aspro.optimus', 'SHOW_COMPARE', 'Y') == 'N')
	$arParams["USE_COMPARE"] = 'N';

?>
<?COptimus::AddMeta(
	array(
		'og:description' => $arElement['PREVIEW_TEXT'],
		'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
	)
);?>

<div class="catalog_detail landing" itemscope itemtype="http://schema.org/Product">
	<?$ElementID = $APPLICATION->IncludeComponent(
		"bitrix:catalog.element",
		"landing",
		Array(
			"SECTION_TIZER"=>$arSection["UF_TIZERS"],
			"SHOW_UNABLE_SKU_PROPS"=>$arParams["SHOW_UNABLE_SKU_PROPS"],
			"SHOW_DISCOUNT_TIME"=>$arParams["SHOW_DISCOUNT_TIME"],
			"TYPE_SKU" => ($typeSKU ? $typeSKU : $TEMPLATE_OPTIONS["TYPE_SKU"]["CURRENT_VALUE"]),
			// "SKU_DETAIL_ID" => (isset($_GET[$arParams["SKU_DETAIL_ID"]]) && strlen($arParams["SKU_DETAIL_ID"]) && $TEMPLATE_OPTIONS["TYPE_SKU"]["CURRENT_VALUE"] == "TYPE_1" ? $_GET[$arParams["SKU_DETAIL_ID"]] : "" ),
			"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
			"SHOW_DISCOUNT_TIME_EACH_SKU" => $arParams["SHOW_DISCOUNT_TIME_EACH_SKU"],
			"SHOW_ARTICLE_SKU" => $arParams["SHOW_ARTICLE_SKU"],
			"SHOW_MEASURE_WITH_RATIO" => $arParams["SHOW_MEASURE_WITH_RATIO"],
			"IBLOCK_REVIEWS_TYPE" => $arParams["IBLOCK_REVIEWS_TYPE"],
			"IBLOCK_REVIEWS_ID" => $arParams["IBLOCK_REVIEWS_ID"],
			"SHOW_ONE_CLICK_BUY" => $arParams["SHOW_ONE_CLICK_BUY"],
			"SEF_MODE_BRAND_SECTIONS" => $arParams["SEF_MODE_BRAND_SECTIONS"],
			"SEF_MODE_BRAND_ELEMENT" => $arParams["SEF_MODE_BRAND_ELEMENT"],
			"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => ($arParams["LANDING_IBLOCK_ID"] ? $arParams["LANDING_IBLOCK_ID"] : COptimusCache::$arIBlocks[SITE_ID]["aspro_optimus_catalog"]["aspro_optimus_landing"][0]),
			"MESSAGES_PER_PAGE" => $arParams["MESSAGES_PER_PAGE"],
			"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
			"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
			"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
			"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
			'CHECK_SECTION_ID_VARIABLE' => (isset($arParams['DETAIL_CHECK_SECTION_ID_VARIABLE']) ? $arParams['DETAIL_CHECK_SECTION_ID_VARIABLE'] : ''),
			"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SET_TITLE" => $arParams["SET_TITLE"],
			"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
			"SET_LAST_MODIFIED" => "Y",
			"SET_STATUS_404" => $arParams["SET_STATUS_404"],
			"MESSAGE_404" => $arParams["MESSAGE_404"],
			"SHOW_404" => $arParams["SHOW_404"],
			"FILE_404" => $arParams["FILE_404"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
			"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
			"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
			"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
			"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],
			"USE_ALSO_BUY" => $arParams["USE_ALSO_BUY"],
			'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
			'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
			"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
			"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
			"SKU_DISPLAY_LOCATION" => $arParams["SKU_DISPLAY_LOCATION"],
			"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
			"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
			"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
			"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
			"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
			"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
			"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
			"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
			"USE_STORE" => $arParams["USE_STORE"],
			"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
			"USE_STORE_SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
			"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
			"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
			"STORE_PATH" => $arParams["STORE_PATH"],
			"MAIN_TITLE" => $arParams["MAIN_TITLE"],
			"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"IBLOCK_STOCK_ID" => $arParams["IBLOCK_STOCK_ID"],
			"SEF_MODE_STOCK_SECTIONS" => $arParams["SEF_MODE_STOCK_SECTIONS"],
			"SHOW_QUANTITY" => $arParams["SHOW_QUANTITY"],
			"SHOW_QUANTITY_COUNT" => $arParams["SHOW_QUANTITY_COUNT"],
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
			'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
			'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
			"USE_ELEMENT_COUNTER" => $arParams["USE_ELEMENT_COUNTER"],
			'STRICT_SECTION_CHECK' => (isset($arParams['DETAIL_STRICT_SECTION_CHECK']) ? $arParams['DETAIL_STRICT_SECTION_CHECK'] : ''),
			'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),

			"USE_RATING" => $arParams["USE_RATING"],
			"USE_REVIEW" => $arParams["USE_REVIEW"],
			"FORUM_ID" => $arParams["FORUM_ID"],
			"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
			"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
			"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
			"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
			"SHOW_BRAND_PICTURE" => $arParams["SHOW_BRAND_PICTURE"],
			"PROPERTIES_DISPLAY_LOCATION" => $arParams["PROPERTIES_DISPLAY_LOCATION"],
			"PROPERTIES_DISPLAY_TYPE" => $arParams["PROPERTIES_DISPLAY_TYPE"],
			"SHOW_ADDITIONAL_TAB" => $arParams["SHOW_ADDITIONAL_TAB"],
			"SHOW_ASK_BLOCK" => $arParams["SHOW_ASK_BLOCK"],
			"ASK_FORM_ID" => $arParams["ASK_FORM_ID"],
			"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
			"SHOW_HINTS" => $arParams["SHOW_HINTS"],
			"OFFER_HIDE_NAME_PROPS" => $arParams["OFFER_HIDE_NAME_PROPS"],
			"SHOW_KIT_PARTS" => $arParams["SHOW_KIT_PARTS"],
			"SHOW_KIT_PARTS_PRICES" => $arParams["SHOW_KIT_PARTS_PRICES"],
			"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
			"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
			'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
			'ADD_DETAIL_TO_SLIDER' => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
			"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
			"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
			"USER_FIELDS" => $arParams['USER_FIELDS'],
			"FIELDS" => $arParams['FIELDS'],
			"STORES" => $arParams['STORES'],
			"BIG_DATA_RCM_TYPE" => $arParams['BIG_DATA_RCM_TYPE'],
			"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
			"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
			"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
			"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
			"SALE_STIKER" => $arParams["SALE_STIKER"],
			"SHOW_RATING" => $arParams["SHOW_RATING"],

			'MAIN_BLOCK_PROPERTY_CODE' => (isset($arParams['DETAIL_MAIN_BLOCK_PROPERTY_CODE']) ? $arParams['DETAIL_MAIN_BLOCK_PROPERTY_CODE'] : ''),
			'MAIN_BLOCK_OFFERS_PROPERTY_CODE' => (isset($arParams['DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE']) ? $arParams['DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE'] : ''),

			"OFFERS_LIMIT" => $arParams["DETAIL_OFFERS_LIMIT"],

			'SHOW_BASIS_PRICE' => (isset($arParams['DETAIL_SHOW_BASIS_PRICE']) ? $arParams['DETAIL_SHOW_BASIS_PRICE'] : 'Y'),
			"DETAIL_PICTURE_MODE" => (isset($TEMPLATE_OPTIONS["DETAIL_PICTURE_MODE"]["CURRENT_VALUE"]) ? $TEMPLATE_OPTIONS["DETAIL_PICTURE_MODE"]["CURRENT_VALUE"] : 'POPUP'),
			'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
			'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
			'SET_VIEWED_IN_COMPONENT' => (isset($arParams['DETAIL_SET_VIEWED_IN_COMPONENT']) ? $arParams['DETAIL_SET_VIEWED_IN_COMPONENT'] : ''),

			'SHOW_SLIDER' => (isset($arParams['DETAIL_SHOW_SLIDER']) ? $arParams['DETAIL_SHOW_SLIDER'] : ''),
			'SLIDER_INTERVAL' => (isset($arParams['DETAIL_SLIDER_INTERVAL']) ? $arParams['DETAIL_SLIDER_INTERVAL'] : ''),
			'SLIDER_PROGRESS' => (isset($arParams['DETAIL_SLIDER_PROGRESS']) ? $arParams['DETAIL_SLIDER_PROGRESS'] : ''),
			'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
			'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),

			"LANDING_TITLE" => ($arParams["LANDING_TITLE"] ? $arParams["LANDING_TITLE"] : GetMessage("POPULAR_CATEGORYS")),
			"LANDING_SECTION_COUNT" => $arParams["LANDING_SECTION_COUNT"],
			"LANDING_PAGE_ELEMENT_COUNT" => $arParams["LANDING_PAGE_ELEMENT_COUNT"],

			"USE_GIFTS_DETAIL" => $arParams['USE_GIFTS_DETAIL']?: 'Y',
			"USE_GIFTS_MAIN_PR_SECTION_LIST" => $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST']?: 'Y',
			"GIFTS_SHOW_DISCOUNT_PERCENT" => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
			"GIFTS_SHOW_OLD_PRICE" => $arParams['GIFTS_SHOW_OLD_PRICE'],
			"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
			"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
			"GIFTS_DETAIL_TEXT_LABEL_GIFT" => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
			"GIFTS_DETAIL_BLOCK_TITLE" => $arParams["GIFTS_DETAIL_BLOCK_TITLE"],
			"GIFTS_SHOW_NAME" => $arParams['GIFTS_SHOW_NAME'],
			"GIFTS_SHOW_IMAGE" => $arParams['GIFTS_SHOW_IMAGE'],
			"GIFTS_MESS_BTN_BUY" => $arParams['GIFTS_MESS_BTN_BUY'],

			"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
			"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],
			"SHOW_LANDINGS" => $arParams['SHOW_LANDINGS'],
		),
		$component
	);?>

	<?$arTransferParams = array(
		"SHOW_ABSENT" => $arParams["SHOW_ABSENT"],
		"HIDE_NOT_AVAILABLE_OFFERS" => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"OFFER_TREE_PROPS" => $arParams["OFFER_TREE_PROPS"],
		"OFFER_SHOW_PREVIEW_PICTURE_PROPS" => $arParams["OFFER_SHOW_PREVIEW_PICTURE_PROPS"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"LIST_OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"LIST_OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
		"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
		"SHOW_COUNTER_LIST" => $arParams["SHOW_COUNTER_LIST"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
		"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
		"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
		"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
		"USE_REGION" => $arParams["USE_REGION"],
		"STORES" => $arParams["STORES"],
		"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"SHOW_GALLERY" => $arParams["SHOW_GALLERY"],
		"MAX_GALLERY_ITEMS" => $arParams["MAX_GALLERY_ITEMS"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
		"ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
		"SHOW_ONE_CLICK_BUY" => $arParams["SHOW_ONE_CLICK_BUY"],
		"SHOW_DISCOUNT_TIME_EACH_SKU" => $arParams["SHOW_DISCOUNT_TIME_EACH_SKU"],
		"SHOW_ARTICLE_SKU" => $arParams["SHOW_ARTICLE_SKU"],
		"ADD_PICT_PROP" => $arParams["ADD_PICT_PROP"],
		"ADD_DETAIL_TO_SLIDER" => $arParams["DETAIL_ADD_DETAIL_TO_SLIDER"],
		"OFFER_ADD_PICT_PROP" => $arParams["OFFER_ADD_PICT_PROP"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"IBINHERIT_TEMPLATES" => $arSeoItem ? $arIBInheritTemplates : array(),
		"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
	);?>

	<div class="js-wrapper-block" data-params='<?=str_replace('\'', '"', CUtil::PhpToJSObject($arTransferParams, false))?>'>
		<div class="catalog" id="right_block_ajax">
			<?include_once(__DIR__."/filter.php");?>

			<?=$APPLICATION->ShowViewContent('langing_title')?>

			<?$isAjax="N";?>
			<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest"  && isset($_GET["ajax_get"]) && $_GET["ajax_get"] == "Y" || (isset($_GET["ajax_basket"]) && $_GET["ajax_basket"]=="Y")){
				$isAjax="Y";
			}?>
			<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest" && isset($_GET["ajax_get_filter"]) && $_GET["ajax_get_filter"] == "Y" ){
				$isAjaxFilter="Y";
			}?>
			<div class="inner_wrapper">

				<?if($isAjax=="N"){
					$frame = new \Bitrix\Main\Page\FrameHelper("viewtype-block");
					$frame->begin();?>
				<?}?>

				<?include_once(__DIR__."/sort.php");?>

				<?if($isAjax=="Y"){
					$APPLICATION->RestartBuffer();
				}?>
				<?$show = $arParams["PAGE_ELEMENT_COUNT_LANDING"];?>
				<?if($isAjax=="N"){?>
					<div class="ajax_load cur <?=$display;?>" data-code="<?=$display;?>">
				<?}?>
				
				<?$GLOBALS[$arParams["FILTER_NAME"]]['SECTION_ID'] = $arElement['PROPERTY_SECTION_VALUE'];?>
				<?$GLOBALS[$arParams["FILTER_NAME"]]['INCLUDE_SUBSECTIONS'] = 'Y';?>
					<?$APPLICATION->IncludeComponent(
						"bitrix:catalog.section",
						$template,
						Array(
							"STORES" => $arParams['STORES'],
							"SHOW_UNABLE_SKU_PROPS"=>$arParams["SHOW_UNABLE_SKU_PROPS"],
							"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
							"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
							"IBLOCK_ID" => $arParams["IBLOCK_ID"],
							// "SECTION_ID" => $arElement['PROPERTY_SECTION_VALUE'],
							"SHOW_COUNTER_LIST" => $arParams["SHOW_COUNTER_LIST"],
							"SECTION_CODE" => "",
							"AJAX_REQUEST" => $isAjax,
							"ELEMENT_SORT_FIELD" => $sort,
							"ELEMENT_SORT_ORDER" => $sort_order,
							"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
							"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
							"FILTER_NAME" => $arParams["FILTER_NAME"],
							"INCLUDE_SUBSECTIONS" => "Y",
							"PAGE_ELEMENT_COUNT" => $show,
							"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
							"DISPLAY_TYPE" => $display,
							"TYPE_SKU" => $TEMPLATE_OPTIONS["TYPE_SKU"]["CURRENT_VALUE"],
							"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
							"SHOW_DISCOUNT_TIME_EACH_SKU" => $arParams["SHOW_DISCOUNT_TIME_EACH_SKU"],

							"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
							"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
							"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
							"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
							"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
							"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
							'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],

							"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

							"SECTION_URL" => "",
							"DETAIL_URL" => "",
							"BASKET_URL" => $arParams["BASKET_URL"],
							"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
							"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
							"PRODUCT_QUANTITY_VARIABLE" => "quantity",
							"PRODUCT_PROPS_VARIABLE" => "prop",
							"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
							"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
							"AJAX_MODE" => $arParams["AJAX_MODE"],
							"AJAX_OPTION_JUMP" => $arParams["AJAX_OPTION_JUMP"],
							"AJAX_OPTION_STYLE" => $arParams["AJAX_OPTION_STYLE"],
							"AJAX_OPTION_HISTORY" => $arParams["AJAX_OPTION_HISTORY"],
							"CACHE_TYPE" =>$arParams["CACHE_TYPE"],
							"CACHE_TIME" => $arParams["CACHE_TIME"],
							"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
							"CACHE_FILTER" => "Y",
							"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
							"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
							"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
							"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
							"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
							'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
							"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
							"SET_TITLE" => "N",
							"SET_STATUS_404" => "N",
							"SHOW_404" => "N",
							"MESSAGE_404" => $arParams["MESSAGE_404"],
							"FILE_404" => $arParams["FILE_404"],
							"PRICE_CODE" => $arParams['PRICE_CODE'],
							"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
							"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
							"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
							"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
							"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
							"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
							"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],

							"PAGER_TITLE" => $arParams["PAGER_TITLE"],
							"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
							"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
							"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
							"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
							"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],

							"AJAX_OPTION_ADDITIONAL" => "",
							"ADD_CHAIN_ITEM" => "N",
							"SHOW_QUANTITY" => $arParams["SHOW_QUANTITY"],
							"SHOW_QUANTITY_COUNT" => $arParams["SHOW_QUANTITY_COUNT"],
							"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
							"SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
							"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
							"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
							"CURRENCY_ID" => $arParams["CURRENCY_ID"],
							"USE_STORE" => $arParams["USE_STORE"],
							"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
							"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
							"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
							"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
							"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
							"LIST_DISPLAY_POPUP_IMAGE" => $arParams["LIST_DISPLAY_POPUP_IMAGE"],
							"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
							"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
							"SHOW_HINTS" => $arParams["SHOW_HINTS"],
							"OFFER_HIDE_NAME_PROPS" => $arParams["OFFER_HIDE_NAME_PROPS"],
							"SHOW_SECTIONS_LIST_PREVIEW" => $arParams["SHOW_SECTIONS_LIST_PREVIEW"],
							"SECTIONS_LIST_PREVIEW_PROPERTY" => $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"],
							"SHOW_SECTION_LIST_PICTURES" => $arParams["SHOW_SECTION_LIST_PICTURES"],
							"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
							"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
							"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
							"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
							"SALE_STIKER" => $arParams["SALE_STIKER"],
							"STIKERS_PROP" => $arParams["STIKERS_PROP"],
							"SHOW_RATING" => $arParams["SHOW_RATING"],
							'SHOW_ALL_WO_SECTION' => "Y",
							"SET_META_DESCRIPTION" => "N",
							"SET_META_KEYWORDS" => "N",
							"SET_BROWSER_TITLE" => "N",
							"OFFER_SHOW_PREVIEW_PICTURE_PROPS" => $arParams["OFFER_SHOW_PREVIEW_PICTURE_PROPS"],
							"COMPATIBLE_MODE" => "Y",
						), $component, array("HIDE_ICONS" => $isAjax)
					);?>
				<?if($isAjax=="N"){?>
					<div class="clear"></div>
					</div>
				<?}?>
				<?if($isAjax!="Y"){?>
					<?$frame->end();?>
				<?}?>
				<?if($isAjax=="Y"){
					die();
				}?>
			</div>
			<?=$APPLICATION->ShowViewContent('langing_detail_text')?>
			<?
			$langing_seo_h1 = ($arElement["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != "" ? $arElement["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] : $arElement["NAME"]);
			$langing_seo_title = ($arElement["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"] != "" ? $arElement["IPROPERTY_VALUES"]["ELEMENT_META_TITLE"] : $arElement["NAME"]);
			
			$APPLICATION->SetTitle($langing_seo_h1);
			$APPLICATION->SetPageProperty("title", $langing_seo_title);
			?>
		</div>
	</div>

</div>
<?COptimus::checkBreadcrumbsChain($arParams, $arSection, $arElement);?>
<div class="clearfix"></div>

<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/animation/animate.min.css');?>