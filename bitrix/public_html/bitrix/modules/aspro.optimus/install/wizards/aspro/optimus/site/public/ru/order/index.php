<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("���������� ������");
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.ajax", 
	".default", 
	array(
		"PAY_FROM_ACCOUNT" => "N",
		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
		"COUNT_DELIVERY_TAX" => "Y",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"ALLOW_AUTO_REGISTER" => "Y",
		"SEND_NEW_USER_NOTIFY" => "Y",
		"DELIVERY_NO_AJAX" => "Y",
		"DELIVERY_NO_SESSION" => "N",
		"TEMPLATE_LOCATION" => "popup",
		"DELIVERY_TO_PAYSYSTEM" => "d2p",
		"USE_PREPAYMENT" => "N",
		"PROP_1" => "",
		"PROP_3" => "",
		"PROP_2" => "",
		"PROP_4" => "",
		"SHOW_STORES_IMAGES" => "Y",
		"PATH_TO_BASKET" => SITE_DIR."basket/",
		"PATH_TO_PERSONAL" => SITE_DIR."personal/",
		"PATH_TO_PAYMENT" => SITE_DIR."order/payment/",
		"PATH_TO_AUTH" => SITE_DIR."auth/",
		"SET_TITLE" => "Y",
		"PRODUCT_COLUMNS" => "",
		"DISABLE_BASKET_REDIRECT" => "N",
		"DISPLAY_IMG_WIDTH" => "90",
		"DISPLAY_IMG_HEIGHT" => "90",
		"COMPONENT_TEMPLATE" => ".default",
		"ALLOW_NEW_PROFILE" => "Y",
		"SHOW_PAYMENT_SERVICES_NAMES" => "Y",
		"COMPATIBLE_MODE" => "Y",
		"BASKET_IMAGES_SCALING" => "adaptive",
		"ALLOW_USER_PROFILES" => "Y",
		"TEMPLATE_THEME" => "blue",
		"SHOW_TOTAL_ORDER_BUTTON" => "Y",
		"SHOW_PAY_SYSTEM_LIST_NAMES" => "Y",
		"SHOW_PAY_SYSTEM_INFO_NAME" => "Y",
		"SHOW_DELIVERY_LIST_NAMES" => "Y",
		"SHOW_DELIVERY_INFO_NAME" => "Y",
		"SHOW_DELIVERY_PARENT_NAMES" => "Y",
		"BASKET_POSITION" => "after",
		"SHOW_BASKET_HEADERS" => "Y",
		"DELIVERY_FADE_EXTRA_SERVICES" => "Y",
		"SHOW_COUPONS_BASKET" => "Y",
		"SHOW_COUPONS_DELIVERY" => "Y",
		"SHOW_COUPONS_PAY_SYSTEM" => "Y",
		"SHOW_NEAREST_PICKUP" => "Y",
		"DELIVERIES_PER_PAGE" => "8",
		"PAY_SYSTEMS_PER_PAGE" => "8",
		"PICKUPS_PER_PAGE" => "5",
		"SHOW_MAP_IN_PROPS" => "Y",
		"SHOW_MAP_FOR_DELIVERIES" => array(
			0 => "1",
			1 => "2",
		),
		"PROPS_FADE_LIST_1" => array(
			0 => "1",
			1 => "2",
			2 => "3",
			3 => "4",
			4 => "7",
		),
		"PROPS_FADE_LIST_2" => array(
		),
		"PRODUCT_COLUMNS_VISIBLE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "PROPS",
			2 => "NOTES",
			3 => "DISCOUNT_PRICE_PERCENT_FORMATED",
			4 => "PRICE_FORMATED",
			5 => "WEIGHT_FORMATED",
		),
		"ADDITIONAL_PICT_PROP_13" => "-",
		"ADDITIONAL_PICT_PROP_14" => "-",
		"PRODUCT_COLUMNS_HIDDEN" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DETAIL_PICTURE",
			2 => "PREVIEW_TEXT",
			3 => "PROPS",
			4 => "NOTES",
			5 => "DISCOUNT_PRICE_PERCENT_FORMATED",
			6 => "PRICE_FORMATED",
			7 => "WEIGHT_FORMATED",
			8 => "PROPERTY_MINIMUM_PRICE",
			9 => "PROPERTY_MAXIMUM_PRICE",
			10 => "PROPERTY_HIT",
			11 => "PROPERTY_BRAND",
			12 => "PROPERTY_IN_STOCK",
			13 => "PROPERTY_PROP_2033",
		),
		"USE_YM_GOALS" => "N",
		"USE_CUSTOM_MAIN_MESSAGES" => "N",
		"USE_CUSTOM_ADDITIONAL_MESSAGES" => "N",
		"USE_CUSTOM_ERROR_MESSAGES" => "N",
		"SHOW_ORDER_BUTTON" => "final_step",
		"SKIP_USELESS_BLOCK" => "Y",
		"SERVICES_IMAGES_SCALING" => "standard",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>