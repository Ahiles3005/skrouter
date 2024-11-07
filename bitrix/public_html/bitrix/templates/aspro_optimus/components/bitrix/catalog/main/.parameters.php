<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	/** @var array $arCurrentValues */
	/** @global CUserTypeManager $USER_FIELD_MANAGER */
	global $USER_FIELD_MANAGER;
	use Bitrix\Main\Loader;
	use Bitrix\Main\ModuleManager;
	use Bitrix\Main\Web\Json;
	Loader::includeModule('iblock');
	$arSKU = false;
	$boolSKU = false;


	$arSort = CIBlockParameters::GetElementSortFields(
		array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
		array('KEY_LOWERCASE' => 'Y')
	);

	$arIBlocks=Array();
	$db_iblock = CIBlock::GetList(Array("SORT"=>"ASC"), Array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_BANNERS_TYPE"]!="-"?$arCurrentValues["IBLOCK_BANNERS_TYPE"]:"")));
	while($arRes = $db_iblock->Fetch()) $arIBlocks[$arRes["ID"]] = $arRes["NAME"];

	$arTypes = array();
	if ($arCurrentValues["IBLOCK_BANNERS_TYPE_ID"])
	{
		$rsTypes=CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_BANNERS_TYPE_ID"], "ACTIVE" =>"Y"), false, false, array("ID", "IBLOCK_ID", "NAME", "CODE"));
		while($arr=$rsTypes->Fetch()) $arTypes[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
	}
	$arTypesEx = CIBlockParameters::GetIBlockTypes(Array("-"=>" "));


	$arPrice = array();
	if (Loader::includeModule("catalog"))
	{
		$arSort = array_merge($arSort, CCatalogIBlockParameters::GetCatalogSortFields());
		$rsPrice=CCatalogGroup::GetList($v1="sort", $v2="asc");
		while($arr=$rsPrice->Fetch()) $arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
		if ((isset($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID']) > 0)
		{
			$arSKU = CCatalogSKU::GetInfoByProductIBlock($arCurrentValues['IBLOCK_ID']);
			$boolSKU = !empty($arSKU) && is_array($arSKU);
		}
	} else {$arPrice = $arProperty_N;}
	$arPrice  = array_merge(array("MINIMUM_PRICE"=>GetMessage("SORT_PRICES_MINIMUM_PRICE"), "MAXIMUM_PRICE"=>GetMessage("SORT_PRICES_MAXIMUM_PRICE")), $arPrice);

	$arProperty_S = array();
	if (0 < intval($arCurrentValues['IBLOCK_ID']))
	{
		$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"], "ACTIVE"=>"Y"));
		while ($arr=$rsProp->Fetch())
		{
			if($arr["PROPERTY_TYPE"]=="S")
				$arProperty_S[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
		}
	}


	$arUserFields_S = array();
	$arUserFields_E = array();
	$arUserFields = $USER_FIELD_MANAGER->GetUserFields("IBLOCK_".$arCurrentValues["IBLOCK_ID"]."_SECTION");
	foreach($arUserFields as $FIELD_NAME=>$arUserField) {
		if($arUserField["USER_TYPE"]["BASE_TYPE"]=="enum")
			{ $arUserFields_E[$FIELD_NAME] = $arUserField["LIST_COLUMN_LABEL"]? $arUserField["LIST_COLUMN_LABEL"]: $FIELD_NAME; }
		if($arUserField["USER_TYPE"]["BASE_TYPE"]=="string")
			{ $arUserFields_S[$FIELD_NAME] = $arUserField["LIST_COLUMN_LABEL"]? $arUserField["LIST_COLUMN_LABEL"]: $FIELD_NAME; }
	}

	$arTemplateParametersParts = array();

	/* get component template pages & params array */
	$arPageBlocksParams = array();
	if(\Bitrix\Main\Loader::includeModule('aspro.optimus')){
		$arPageBlocks = COptimus::GetComponentTemplatePageBlocks(__DIR__);

		$arPageBlocksParams = COptimus::GetComponentTemplatePageBlocksParams($arPageBlocks);
		COptimus::AddComponentTemplateModulePageBlocksParams(__DIR__, $arPageBlocksParams, array('SECTION' => 'CATALOG_PAGE', 'OPTION' => 'CATALOG')); // add option value FROM_MODULE
	}

	$arTemplateParametersParts[] = array_merge($arPageBlocksParams, array(
		"IBLOCK_STOCK_ID" => Array(
			"NAME" => GetMessage("IBLOCK_STOCK_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"SHOW_MEASURE" => Array(
				"NAME" => GetMessage("SHOW_MEASURE"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "N",
		),
		"SORT_BUTTONS" => Array(
			"SORT" => 100,
			"NAME" => GetMessage("SORT_BUTTONS"),
			"TYPE" => "LIST",
			"VALUES" => array("POPULARITY"=>GetMessage("SORT_BUTTONS_POPULARITY"), "NAME"=>GetMessage("SORT_BUTTONS_NAME"), "PRICE"=>GetMessage("SORT_BUTTONS_PRICE"), "QUANTITY"=>GetMessage("SORT_BUTTONS_QUANTITY")),
			"DEFAULT" => array("POPULARITY", "NAME", "PRICE"),
			"PARENT" => "LIST_SETTINGS",
			"TYPE" => "LIST",
			"REFRESH" => "Y",
			"MULTIPLE" => "Y",
		),
	));


	if(is_array($arCurrentValues["SORT_BUTTONS"])){
		if (in_array("PRICE", $arCurrentValues["SORT_BUTTONS"])){
			$arTemplateParametersParts[]["SORT_PRICES"] = Array(
				"SORT"=>200,
				"NAME" => GetMessage("SORT_PRICES"),
				"TYPE" => "LIST",
				"VALUES" => $arPrice,
				"DEFAULT" => array("MINIMUM_PRICE"),
				"PARENT" => "LIST_SETTINGS",
				"MULTIPLE" => "N",
			);
		}
	}

	$detailPictMode = array(
		'IMG' => GetMessage('DETAIL_PICTURE_MODE_IMG'),
		'POPUP' => GetMessage('DETAIL_PICTURE_MODE_POPUP'),
		'MAGNIFIER' => GetMessage('DETAIL_PICTURE_MODE_MAGNIFIER')
	);

	$arTemplateParametersParts[] = array(
		"DEFAULT_LIST_TEMPLATE" => Array(
				"NAME" => GetMessage("DEFAULT_LIST_TEMPLATE"),
				"TYPE" => "LIST",
				"VALUES" => array("block"=>GetMessage("DEFAULT_LIST_TEMPLATE_BLOCK"), "list"=>GetMessage("DEFAULT_LIST_TEMPLATE_LIST"), "table"=>GetMessage("DEFAULT_LIST_TEMPLATE_TABLE")),
				"DEFAULT" => "list",
				"PARENT" => "LIST_SETTINGS",
		),
		"SECTION_DISPLAY_PROPERTY" => Array(
				"NAME" => GetMessage("SECTION_DISPLAY_PROPERTY"),
				"TYPE" => "LIST",
				"VALUES" => $arUserFields_E,
				"DEFAULT" => "list",
				"MULTIPLE" => "N",
				"PARENT" => "LIST_SETTINGS",
		),
		"SECTION_TOP_BLOCK_TITLE" => Array(
				"NAME" => GetMessage("SECTION_TOP_BLOCK_TITLE"),
				"TYPE" => "STRING",
				"DEFAULT" => GetMessage("SECTION_TOP_BLOCK_TITLE_VALUE"),
				"PARENT" => "TOP_SETTINGS",
		),
		"USE_RATING" => array(
				"NAME" => GetMessage("USE_RATING"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "Y",
		),
		"SHOW_UNABLE_SKU_PROPS" => array(
				"NAME" => GetMessage("SHOW_UNABLE_SKU_PROPS"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "Y",
		),
		"SHOW_ARTICLE_SKU" => array(
			"NAME" => GetMessage("SHOW_ARTICLE_SKU"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"LIST_DISPLAY_POPUP_IMAGE" => array(
			"NAME" => GetMessage("LIST_DISPLAY_POPUP_IMAGE"),
			"PARENT" => "LIST_SETTINGS",
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "N",
			"DEFAULT" => "Y",
		),
		"DISPLAY_WISH_BUTTONS" => array(
			"NAME" => GetMessage("DISPLAY_WISH_BUTTONS"),
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"ADDITIONAL_VALUES" => "N",
			"DEFAULT" => "Y",
		),
		"DEFAULT_COUNT" => array(
			"NAME" => GetMessage("DEFAULT_COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
		),
		"DISPLAY_ELEMENT_SLIDER" => Array(
			"NAME" => GetMessage("DISPLAY_ELEMENT_SLIDER"),
			"TYPE" => "STRING",
			"DEFAULT" => "10",
			"PARENT" => "DETAIL_SETTINGS",
		),
		"PROPERTIES_DISPLAY_LOCATION" => Array(
			"NAME" => GetMessage("PROPERTIES_DISPLAY_LOCATION"),
			"TYPE" => "LIST",
			"VALUES" => array("DESCRIPTION"=>GetMessage("PROPERTIES_DISPLAY_LOCATION_DESCRIPTION"), "TAB"=>GetMessage("PROPERTIES_DISPLAY_LOCATION_TAB")),
			"DEFAULT" => "DESCRIPTION",
			"PARENT" => "DETAIL_SETTINGS",
		),
		"DETAIL_ADD_DETAIL_TO_SLIDER" => array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_DETAIL_ADD_DETAIL_TO_SLIDER'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N'
		),
		"SHOW_BRAND_PICTURE" => Array(
				"NAME" => GetMessage("SHOW_BRAND_PICTURE"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "Y",
				"PARENT" => "DETAIL_SETTINGS",
		),
		"SHOW_ASK_BLOCK" => Array(
				"NAME" => GetMessage("SHOW_ASK_BLOCK"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "Y",
				"PARENT" => "DETAIL_SETTINGS",
		),
		"ASK_FORM_ID" => Array(
				"NAME" => GetMessage("ASK_FORM_ID"),
				"TYPE" => "STRING",
				"DEFAULT" => "",
				"PARENT" => "DETAIL_SETTINGS",
		),
		"SHOW_CHEAPER_FORM" => Array(
				"NAME" => GetMessage("SHOW_CHEAPER_FORM"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => "N",
				"PARENT" => "DETAIL_SETTINGS",
		),
		"CHEAPER_FORM_NAME" => Array(
				"NAME" => GetMessage("CHEAPER_FORM_NAME"),
				"TYPE" => "STRING",
				"DEFAULT" => "",
				"PARENT" => "DETAIL_SETTINGS",
		),
		"DETAIL_OFFERS_LIMIT" => Array(
				"NAME" => GetMessage("DETAIL_OFFERS_LIMIT"),
				"TYPE" => "STRING",
				"DEFAULT" => "0",
				"PARENT" => "DETAIL_SETTINGS",
		),
		"DETAIL_EXPANDABLES_TITLE" => Array(
				"NAME" => GetMessage("DETAIL_EXPANDABLES_TITLE"),
				"TYPE" => "STRING",
				"DEFAULT" => GetMessage("DETAIL_EXPANDABLES_VALUE"),
				"PARENT" => "DETAIL_SETTINGS",
		),
		"DETAIL_ASSOCIATED_TITLE" => Array(
				"NAME" => GetMessage("DETAIL_ASSOCIATED_TITLE"),
				"TYPE" => "STRING",
				"DEFAULT" => GetMessage("DETAIL_ASSOCIATED_VALUE"),
				"PARENT" => "DETAIL_SETTINGS",
		),
		"SALE_STIKER" =>array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("SALE_STIKER"),
			"TYPE" => "LIST",
			"DEFAULT" => "-",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => array_merge(Array("-"=>" "), $arProperty_S),
		),
		"SHOW_ADDITIONAL_TAB" => Array(
			"NAME" => GetMessage("SHOW_ADDITIONAL_TAB"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"PARENT" => "DETAIL_SETTINGS",
		),
		"SHOW_HINTS" => Array(
			"NAME" => GetMessage("SHOW_HINTS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"PROPERTIES_DISPLAY_TYPE" => Array(
			"NAME" => GetMessage("PROPERTIES_DISPLAY_TYPE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => array("BLOCK"=>GetMessage("PROPERTIES_DISPLAY_TYPE_BLOCK"), "TABLE"=>GetMessage("PROPERTIES_DISPLAY_TYPE_TABLE")),
			"DEFAULT" => "BLOCK",
			"PARENT" => "DETAIL_SETTINGS",
		),
		"SHOW_DISCOUNT_PERCENT" => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_SHOW_DISCOUNT_PERCENT'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
		),
		"SHOW_MEASURE_WITH_RATIO" => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('SHOW_MEASURE_WITH_RATIO'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
		),
		/*"DETAIL_PICTURE_MODE" => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BCE_TPL_DETAIL_PICTURE_MODE'),
			'TYPE' => 'LIST',
			'DEFAULT' => 'POPUP',
			'VALUES' => $detailPictMode
		),*/
		"SHOW_DISCOUNT_TIME" => Array(
			'PARENT' => 'VISUAL',
			"NAME" => GetMessage("SHOW_DISCOUNT_TIME"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"SHOW_COUNTER_LIST" => Array(
			'PARENT' => 'VISUAL',
			"NAME" => GetMessage("SHOW_COUNTER_LIST"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"SHOW_DISCOUNT_TIME_EACH_SKU" => Array(
			"NAME" => GetMessage("SHOW_DISCOUNT_TIME_EACH_SKU"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"SORT" => 100,
			"PARENT" => "VISUAL",
		),
		"SHOW_RATING" => Array(
			'PARENT' => 'VISUAL',
			"NAME" => GetMessage("SHOW_RATING"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"SHOW_OLD_PRICE" => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_SHOW_OLD_PRICE'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
		),
		"RESTART" => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('RESTART'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'PARENT' => 'SEARCH_SETTINGS',
		),
		"USE_LANGUAGE_GUESS" => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('USE_LANGUAGE_GUESS'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
			'PARENT' => 'SEARCH_SETTINGS',
		),
		"NO_WORD_LOGIC" => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('NO_WORD_LOGIC'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
			'PARENT' => 'SEARCH_SETTINGS',
		),
		'SEARCH_SHOW_FILTER_LEFT' => array(
			"NAME" => GetMessage("SEARCH_SHOW_FILTER_LEFT"),
			"TYPE" => "CHECKBOX",
			"PARENT" => "SEARCH_SETTINGS",
			"DEFAULT" => "N",
		),
		"SEARCH_SHOW_ITEM_SECTION_LEFT" => array(
			"NAME" => GetMessage("SEARCH_SHOW_ITEM_SECTION_LEFT"),
			"TYPE" => "CHECKBOX",
			"PARENT" => "SEARCH_SETTINGS",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
		"SHOW_SORT_RANK_BUTTON" => array(
			"PARENT" => "SEARCH_SETTINGS",
			"NAME" => GetMessage("SHOW_SORT_RANK_BUTTON_TITLE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
	);

	if($arCurrentValues["SEARCH_SHOW_ITEM_SECTION_LEFT"] === 'Y'){
		$arTemplateParametersParts[]['SEARCH_DEPTH_LEVEL_BRAND'] = array(
			"NAME" => GetMessage("SEARCH_DEPTH_LEVEL_BRAND"),
			"TYPE" => "STRING",
			"PARENT" => "SEARCH_SETTINGS",
			"DEFAULT" => "3"
		);
	}

	$arTemplateParametersParts[]["SECTIONS_LIST_ROOT_PREVIEW_PROPERTY"] = Array(
		"NAME" => GetMessage("SHOW_SECTION_PREVIEW_PROPERTY"),
		"VALUES" => array_merge(array("DESCRIPTION"=>GetMessage("SHOW_SECTION_PREVIEW_PROPERTY_DESCRIPTION")), $arUserFields_S),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"DEFAULT" => "UF_SECTION_DESCR",
		"PARENT" => "SECTIONS_SETTINGS",
	);

	$arTemplateParametersParts[]["SECTION_PREVIEW_PROPERTY"] = Array(
		"NAME" => GetMessage("SHOW_SECTION_PREVIEW_PROPERTY"),
		"VALUES" => array_merge(array("DESCRIPTION"=>GetMessage("SHOW_SECTION_PREVIEW_PROPERTY_DESCRIPTION")), $arUserFields_S),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"DEFAULT" => "DESCRIPTION",
		"PARENT" => "LIST_SETTINGS");
	$arTemplateParametersParts[]["SECTIONS_LIST_PREVIEW_PROPERTY"] = Array(
		"NAME" => GetMessage("SHOW_SECTIONS_LIST_PREVIEW_PROPERTY"),
		"VALUES" => array_merge(array("DESCRIPTION"=>GetMessage("SHOW_SECTION_PREVIEW_PROPERTY_DESCRIPTION")), $arUserFields_S),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"DEFAULT" => "DESCRIPTION",
		"PARENT" => "LIST_SETTINGS");
	$arTemplateParametersParts[]["SECTIONS_LIST_PREVIEW_DESCRIPTION"] = Array(
		"NAME" => GetMessage("SHOW_SECTION_ROOT_PREVIEW"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"PARENT" => "SECTIONS_SETTINGS");
	$arTemplateParametersParts[]["SUBSECTION_PREVIEW_DESCRIPTION"] = Array(
		"NAME" => GetMessage("SHOW_SUBSECTION_ROOT_PREVIEW"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"PARENT" => "LIST_SETTINGS");
	$arTemplateParametersParts[]["SECTION_PREVIEW_DESCRIPTION"] = Array(
		"NAME" => GetMessage("SHOW_SECTION_ROOT_PREVIEW"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"PARENT" => "LIST_SETTINGS");


	$arTemplateParametersParts[] = Array(
		"SHOW_SECTION_LIST_PICTURES" => Array(
			"NAME" => GetMessage("SHOW_SECTION_PICTURES"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"PARENT" => "SECTIONS_SETTINGS",
		),
		"SHOW_SECTION_PICTURES" => Array(
			"NAME" => GetMessage("SHOW_SECTION_PICTURES"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"PARENT" => "LIST_SETTINGS",
		),
		/*"SHOW_SECTION_SIBLINGS" => Array(
			"NAME" => GetMessage("SHOW_SECTION_SIBLINGS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "N",
			"PARENT" => "LIST_SETTINGS",
		),*/
		"LANDING_IBLOCK_ID" => Array(
			"NAME" => GetMessage("LANDING_IBLOCK_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "LIST_SETTINGS",
		),
		"SHOW_KIT_PARTS" => Array(
			"NAME" => GetMessage("SHOW_KIT_PARTS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "N",
			"PARENT" => "DETAIL_SETTINGS",
		),
		"SHOW_KIT_PARTS_PRICES" => Array(
			"NAME" => GetMessage("SHOW_KIT_PARTS_PRICES"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "N",
			"PARENT" => "DETAIL_SETTINGS",
		),
		"SHOW_ONE_CLICK_BUY" => Array(
			"NAME" => GetMessage("SHOW_ONE_CLICK_BUY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "N",
			"PARENT" => "DETAIL_SETTINGS",
		),
		"SKU_DETAIL_ID" => Array(
			"NAME" => GetMessage("SKU_DETAIL_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "oid",
			"PARENT" => "DETAIL_SETTINGS",
		),
		'USE_DETAIL_PREDICTION' => array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('USE_DETAIL_PREDICTION_TITLE'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
		),
		"AJAX_FILTER_CATALOG" => Array(
			"NAME" => GetMessage("AJAX_FILTER_CATALOG_TITLE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "N",
			"PARENT" => "FILTER_SETTINGS",
		),
		"DISPLAY_ELEMENT_COUNT" => Array(
			"NAME" => GetMessage("DISPLAY_ELEMENT_COUNT_TITLE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "N",
			"PARENT" => "FILTER_SETTINGS",
		),
		"SHOW_LANDINGS" => array( 
			'PARENT' => 'LIST_SETTINGS', 
			'NAME' => GetMessage('SHOW_LANDINGS_TITLE'), 
			'TYPE' => 'CHECKBOX', 
			'DEFAULT' => 'Y', 
			'REFRESH' => 'Y', 
		),
	);
	
	
	if($arCurrentValues["SHOW_LANDINGS"] !== 'N'){
		$arTemplateParametersParts[] = Array(
			"LANDING_TITLE" => Array(
				"NAME" => GetMessage("LANDING_TITLE"),
				"TYPE" => "STRING",
				"DEFAULT" => "",
				"PARENT" => "LIST_SETTINGS",
			),
			"LANDING_SECTION_COUNT" => Array(
				"NAME" => GetMessage("LANDING_SECTION_COUNT"),
				"TYPE" => "STRING",
				"DEFAULT" => "8",
				"PARENT" => "LIST_SETTINGS",
			),
			"LANDING_PAGE_ELEMENT_COUNT" => Array(
				"NAME" => GetMessage("LANDING_PAGE_ELEMENT_COUNT"),
				"TYPE" => "STRING",
				"DEFAULT" => "20",
				"PARENT" => "LIST_SETTINGS",
			),
			"LANDINGS_POSITION" => array( 
				'PARENT' => 'LIST_SETTINGS', 
				'NAME' => GetMessage('LANDINGS_POSITION_TITLE'),
				'TYPE' => 'LIST',
				'MULTIPLE' => 'N',
				'ADDITIONAL_VALUES' => 'N',
				'DEFAULT' => 'BOTTOM',
				'VALUES' => array(
					'BOTTOM' => GetMessage("LANDINGS_POSITION_BOTTOM"),
					'TOP' => GetMessage("LANDINGS_POSITION_TOP"),
				),
			),
		);
	}

	$arAllPropList = array();
	$arFilePropList = array(
		'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
	);
	$arListPropList = array(
		'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
	);
	$arHighloadPropList = array(
		'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
	);
	$rsProps = CIBlockProperty::GetList(
		array('SORT' => 'ASC', 'ID' => 'ASC'),
		array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], 'ACTIVE' => 'Y')
	);
	while ($arProp = $rsProps->Fetch())
	{
		$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
		if ('' == $arProp['CODE'])
			$arProp['CODE'] = $arProp['ID'];
		$arAllPropList[$arProp['CODE']] = $strPropName;
		if ('F' == $arProp['PROPERTY_TYPE'])
			$arFilePropList[$arProp['CODE']] = $strPropName;
		if ('L' == $arProp['PROPERTY_TYPE'])
			$arListPropList[$arProp['CODE']] = $strPropName;
		if ('S' == $arProp['PROPERTY_TYPE'] && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
			$arHighloadPropList[$arProp['CODE']] = $strPropName;
	}

	$arTemplateParametersParts[] = array(
		'ADD_PICT_PROP' => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_ADD_PICT_PROP'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '-',
			'VALUES' => $arFilePropList
		)
	);

	if ($boolSKU)
	{
		$arAllOfferPropList = array();
		$arFileOfferPropList = array(
			'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
		);
		$arTreeOfferPropList = $arShowPreviewPictuteTreeOfferPropList = array(
			'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
		);
		$rsProps = CIBlockProperty::GetList(
			array('SORT' => 'ASC', 'ID' => 'ASC'),
			array('IBLOCK_ID' => $arSKU['IBLOCK_ID'], 'ACTIVE' => 'Y')
		);
		while ($arProp = $rsProps->Fetch())
		{
			if ($arProp['ID'] == $arSKU['SKU_PROPERTY_ID'])
				continue;
			$arProp['USER_TYPE'] = (string)$arProp['USER_TYPE'];
			$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
			if ('' == $arProp['CODE'])
				$arProp['CODE'] = $arProp['ID'];
			if ('F' == $arProp['PROPERTY_TYPE'])
				$arFileOfferPropList[$arProp['CODE']] = $strPropName;
			if ('N' != $arProp['MULTIPLE'])
				continue;
			if (
				'L' == $arProp['PROPERTY_TYPE']
				|| 'E' == $arProp['PROPERTY_TYPE']
				|| ('S' == $arProp['PROPERTY_TYPE'] && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
			)
				$arTreeOfferPropList[$arProp['CODE']] = $strPropName;

				if ('S' == $arProp['PROPERTY_TYPE'] && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp) && strlen($arProp['USER_TYPE_SETTINGS']['TABLE_NAME'])){
					$arShowPreviewPictuteTreeOfferPropList[$arProp['CODE']] = $strPropName;
				}
		}
		$arTemplateParametersParts[] = array(
			'OFFER_ADD_PICT_PROP' => array(
				'PARENT' => 'VISUAL',
				'NAME' => GetMessage('CP_BC_TPL_OFFER_ADD_PICT_PROP'),
				'TYPE' => 'LIST',
				'MULTIPLE' => 'N',
				'ADDITIONAL_VALUES' => 'N',
				'REFRESH' => 'N',
				'DEFAULT' => '-',
				'VALUES' => $arFileOfferPropList
			)
		);
		$arTemplateParametersParts[]=array(
			'OFFER_TREE_PROPS' => array(
				'PARENT' => 'OFFERS_SETTINGS',
				'NAME' => GetMessage('OFFERS_SETTINGS'),
				'TYPE' => 'LIST',
				'MULTIPLE' => 'Y',
				'ADDITIONAL_VALUES' => 'N',
				'REFRESH' => 'N',
				'DEFAULT' => '-',
				'VALUES' => $arTreeOfferPropList
			)
		);
		$arTemplateParametersParts[]=array(
			'OFFER_HIDE_NAME_PROPS' => array(
				'PARENT' => 'OFFERS_SETTINGS',
				'NAME' => GetMessage('OFFER_HIDE_NAME_PROPS_TITLE'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'N',
			)
		);
		$arTemplateParametersParts[]=array(
			'OFFER_SHOW_PREVIEW_PICTURE_PROPS' => array(
				'PARENT' => 'OFFERS_SETTINGS',
				'NAME' => GetMessage('OFFER_SHOW_PREVIEW_PICTURE_PROPS_TITLE'),
				'TYPE' => 'LIST',
				'MULTIPLE' => 'Y',
				'ADDITIONAL_VALUES' => 'N',
				'REFRESH' => 'N',
				'DEFAULT' => '-',
				'VALUES' => $arShowPreviewPictuteTreeOfferPropList
			)
		);
	}
	if (ModuleManager::isModuleInstalled("sale"))
	{
		$arTemplateParametersParts[]=array(
			'USE_BIG_DATA' => array(
				'PARENT' => 'BIG_DATA_SETTINGS',
				'NAME' => GetMessage('CP_BC_TPL_USE_BIG_DATA'),
				'TYPE' => 'CHECKBOX',
				'DEFAULT' => 'Y',
				'REFRESH' => 'Y'
			)
		);
		if (!isset($arCurrentValues['USE_BIG_DATA']) || $arCurrentValues['USE_BIG_DATA'] == 'Y')
		{
			$rcmTypeList = array(
				'bestsell' => GetMessage('CP_BC_TPL_RCM_BESTSELLERS'),
				'personal' => GetMessage('CP_BC_TPL_RCM_PERSONAL'),
				'similar_sell' => GetMessage('CP_BC_TPL_RCM_SOLD_WITH'),
				'similar_view' => GetMessage('CP_BC_TPL_RCM_VIEWED_WITH'),
				'similar' => GetMessage('CP_BC_TPL_RCM_SIMILAR'),
				'any_similar' => GetMessage('CP_BC_TPL_RCM_SIMILAR_ANY'),
				'any_personal' => GetMessage('CP_BC_TPL_RCM_PERSONAL_WBEST'),
				'any' => GetMessage('CP_BC_TPL_RCM_RAND')
			);
			$arTemplateParametersParts[]=array(
				'BIG_DATA_RCM_TYPE' => array(
					'PARENT' => 'BIG_DATA_SETTINGS',
					'NAME' => GetMessage('CP_BC_TPL_BIG_DATA_RCM_TYPE'),
					'TYPE' => 'LIST',
					'VALUES' => $rcmTypeList
				)
			);
			unset($rcmTypeList);
		}
	}

	$arTemplateParametersParts[] = [
		'USE_COMPARE' => [
			'HIDDEN' => 'Y'
		],
	];

	$arTemplateParametersParts[] = [
		"TAB_DESCR_NAME_VIDEO" => [
			"NAME" => GetMessage("TAB_DESCR_NAME_VIDEO"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "DETAIL_SETTINGS",
		],
	];

	$arTemplateParametersParts[] = [
		"TAB_DESCR_NAME_PRODUCT_REVIEWS" => [
			"NAME" => GetMessage("TAB_DESCR_NAME_PRODUCT_REVIEWS"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "DETAIL_SETTINGS",
		],
	];

	$arTemplateParametersParts[] = [
		"TAB_DESCR_NAME_DESCRIPTION" => [
			"NAME" => GetMessage("TAB_DESCR_NAME_DESCRIPTION"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "DETAIL_SETTINGS",
		],
	];

	$arTemplateParametersParts[] = [
		"TAB_DESCR_NAME_PROPERTIES" => [
			"NAME" => GetMessage("TAB_DESCR_NAME_PROPERTIES"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "DETAIL_SETTINGS",
		],
	];

	$arTemplateParametersParts[] = [
		"TAB_DESCR_NAME_ASK" => [
			"NAME" => GetMessage("TAB_DESCR_NAME_ASK"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "DETAIL_SETTINGS",
		],
	];

	$arTemplateParametersParts[] = [
		"TAB_DESCR_NAME_STORES" => [
			"NAME" => GetMessage("TAB_DESCR_NAME_STORES"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "DETAIL_SETTINGS",
		],
	];

	$arTemplateParametersParts[] = [
		"TAB_DESCR_NAME_CUSTOM_TAB" => [
			"NAME" => GetMessage("TAB_DESCR_NAME_CUSTOM_TAB"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "DETAIL_SETTINGS",
		],
	];

	$arTemplateParametersParts[] = [
		"TAB_DESCR_NAME_OFFER_PRICES" => [
			"NAME" => GetMessage("TAB_DESCR_NAME_OFFER_PRICES"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "DETAIL_SETTINGS",
		],
	];



	$arTemplateParametersParts[] = array(
		'DETAIL_BLOCKS_ORDER' => array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_PRODUCT_BLOCKS_ORDER'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => \Bitrix\Main\Page\Asset::getInstance()->getFullAssetPath('/bitrix/js/aspro.optimus/settings/dragdrop_order/script.min.js'),
			'JS_EVENT' => 'initDraggableOrderControl',
			'JS_DATA' => Json::encode(array(
				'tabs' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_TABS'),
				'nabor' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_NABOR'),
				'complect' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_COMPLECT'),
				'gifts' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_GIFTS'),
			)),
			'DEFAULT' => 'complect,nabor,tabs,gifts'
		),
	);
	
	$arTemplateParametersParts[] = array(
		'DETAIL_BLOCKS_TAB_ORDER' => array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_PRODUCT_BLOCKS_TAB_ORDER'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => \Bitrix\Main\Page\Asset::getInstance()->getFullAssetPath('/bitrix/js/aspro.optimus/settings/dragdrop_order/script.min.js'),
			'JS_EVENT' => 'initDraggableOrderControl',
			'JS_DATA' => Json::encode(array(
				'offer_prices' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_OFFERS'),
				'description' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_DESC'),
				'properties' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_CHAR'),
				'video' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_VIDEO'),
				'product_reviews' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_REVIEWS'),
				'ask' =>  GetMessage('CP_BC_TPL_PRODUCT_BLOCK_ASK'),
				'stores' =>  GetMessage('CP_BC_TPL_PRODUCT_BLOCK_STORES'),
				'custom_tab' =>  GetMessage('CP_BC_TPL_PRODUCT_BLOCK_CUSTOM_TAB'),
			)),
			'DEFAULT' => 'offer_prices,description,properties,video,product_reviews,ask,stores,custom_tab',
		)
	);

	$arTemplateParametersParts[] = array(
		'DETAIL_BLOCKS_ALL_ORDER' => array(
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => GetMessage('CP_BC_TPL_PRODUCT_BLOCKS_ALL_ORDER'),
			'TYPE' => 'CUSTOM',
			'JS_FILE' => \Bitrix\Main\Page\Asset::getInstance()->getFullAssetPath('/bitrix/js/aspro.optimus/settings/dragdrop_order/script.min.js'),
			'JS_EVENT' => 'initDraggableOrderControl',
			'JS_DATA' => Json::encode(array(
				'offer_prices' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_OFFERS'),
				'description' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_DESC'),
				'services' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_SERVICES'),
				'docs' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_DOCS'),
				'video' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_VIDEO'),
				'product_reviews' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_REVIEWS'),
				'ask' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_ASK'),
				'custom_tab' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_CUSTOM_TAB'),
				'complect' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_COMPLECT'),
				'nabor' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_NABOR'),
				'gifts' => GetMessage('CP_BC_TPL_PRODUCT_BLOCK_GIFTS'),
				'stores' =>  GetMessage('CP_BC_TPL_PRODUCT_BLOCK_STORES'),
			)),
			'DEFAULT' => 'offer_prices,complect,nabor,description,services,docs,video,product_reviews,ask,stores,custom_tab,gifts'
		)
	);


	//merge parameters to one array
	$arTemplateParameters = array();
	foreach($arTemplateParametersParts as $i => $part) { $arTemplateParameters = array_merge($arTemplateParameters, $part); }
?>
