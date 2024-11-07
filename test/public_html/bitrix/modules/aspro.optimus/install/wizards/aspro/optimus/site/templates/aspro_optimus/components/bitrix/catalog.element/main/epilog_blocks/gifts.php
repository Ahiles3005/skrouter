<?use \Bitrix\Main\Localization\Loc;?>

<div class="gifts">
    <?if ($templateData['GIFTS_PARAMS']['CATALOG'] && $arParams['USE_GIFTS_DETAIL'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled("sale")){
        $APPLICATION->IncludeComponent("bitrix:sale.gift.product", "main", array(
                "SHOW_UNABLE_SKU_PROPS"=>$arParams["SHOW_UNABLE_SKU_PROPS"],
                'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
                'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
                'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
                'BUY_URL_TEMPLATE' => $templateData['GIFTS_PARAMS']['BUY_URL_TEMPLATE'],
                'ADD_URL_TEMPLATE' => $templateData['GIFTS_PARAMS']['ADD_URL_TEMPLATE'],
                'SUBSCRIBE_URL_TEMPLATE' => $templateData['GIFTS_PARAMS']['SUBSCRIBE_URL_TEMPLATE'],
                'COMPARE_URL_TEMPLATE' => $templateData['GIFTS_PARAMS']['COMPARE_URL_TEMPLATE'],
                "OFFER_HIDE_NAME_PROPS" => $arParams["OFFER_HIDE_NAME_PROPS"],
                'SHOW_DISCOUNT_TIME_EACH_SKU' => $arParams['SHOW_DISCOUNT_TIME_EACH_SKU'],

                "SHOW_DISCOUNT_PERCENT" => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
                "SHOW_OLD_PRICE" => $arParams['GIFTS_SHOW_OLD_PRICE'],
                "PAGE_ELEMENT_COUNT" => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
                "LINE_ELEMENT_COUNT" => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
                "HIDE_BLOCK_TITLE" => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
                "BLOCK_TITLE" => $arParams['GIFTS_DETAIL_BLOCK_TITLE'],
                "TEXT_LABEL_GIFT" => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
                "SHOW_NAME" => $arParams['GIFTS_SHOW_NAME'],
                "SHOW_IMAGE" => $arParams['GIFTS_SHOW_IMAGE'],
                "MESS_BTN_BUY" => $arParams['GIFTS_MESS_BTN_BUY'],

                "SHOW_PRODUCTS_{$arParams['IBLOCK_ID']}" => "Y",
                "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
                "PRODUCT_SUBSCRIPTION" => $arParams["PRODUCT_SUBSCRIPTION"],
                "MESS_BTN_DETAIL" => $arParams["MESS_BTN_DETAIL"],
                "MESS_BTN_SUBSCRIBE" => $arParams["MESS_BTN_SUBSCRIBE"],
                "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                "PRICE_CODE" => $arParams["PRICE_CODE"],
                "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                "BASKET_URL" => $arParams["BASKET_URL"],
                "ADD_PROPERTIES_TO_BASKET" => $arParams["ADD_PROPERTIES_TO_BASKET"],
                "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                "PARTIAL_PRODUCT_PROPERTIES" => $arParams["PARTIAL_PRODUCT_PROPERTIES"],
                "USE_PRODUCT_QUANTITY" => 'N',
                "OFFER_TREE_PROPS_{$templateData['OFFERS_INFO']["OFFERS_IBLOCK"]}" => $arParams['OFFER_TREE_PROPS'],
                "CART_PROPERTIES_{$templateData['OFFERS_INFO']["OFFERS_IBLOCK"]}" => $arParams['OFFERS_CART_PROPERTIES'],
                "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
                "SALE_STIKER" => $arParams["SALE_STIKER"],
                "SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
                "SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
                "DISPLAY_TYPE" => "block",
                "SHOW_RATING" => $arParams["SHOW_RATING"],
                "DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
                "DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
                "DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
                "TYPE_SKU" => "Y",

                "POTENTIAL_PRODUCT_TO_BUY" => array(
                    'ID' =>  $templateData['GIFTS_PARAMS']['ID'],
                    'MODULE' => $templateData['GIFTS_PARAMS']['MODULE'],
                    'PRODUCT_PROVIDER_CLASS' => $templateData['GIFTS_PARAMS']['PRODUCT_PROVIDER_CLASS'],
                    'QUANTITY' => $templateData['GIFTS_PARAMS']['QUANTITY'],
                    'IBLOCK_ID' => $templateData['GIFTS_PARAMS']['IBLOCK_ID'],

                    'PRIMARY_OFFER_ID' => isset($templateData['OFFERS_INFO']['OFFERS'][0]['ID']) ? $templateData['OFFERS_INFO']['OFFERS'][0]['ID'] : null,
                    'SECTION' => array(
                        'ID' => $templateData['GIFTS_PARAMS']['SECTION_ID'],
                        'IBLOCK_ID' => $templateData['GIFTS_PARAMS']['SECTION_IBLOCK_ID'],
                        'LEFT_MARGIN' => $templateData['GIFTS_PARAMS']['SECTION_LEFT_MARGIN'],
                        'RIGHT_MARGIN' => $templateData['GIFTS_PARAMS']['SECTION_RIGHT_MARGIN'],
                    ),
                )
            ), $component, array("HIDE_ICONS" => "Y"));
    }
    // Показывать блок "Товары к подарку" в детальном просмотре:
    if ($templateData['GIFTS_PARAMS']['CATALOG'] && $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled("sale")){
        $APPLICATION->IncludeComponent(
                "bitrix:sale.gift.main.products",
                "main",
                array(
                    "SHOW_UNABLE_SKU_PROPS"=>$arParams["SHOW_UNABLE_SKU_PROPS"],
                    "PAGE_ELEMENT_COUNT" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
                    "BLOCK_TITLE" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],
                    'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],

                    "OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
                    "OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
                    'SHOW_DISCOUNT_TIME_EACH_SKU' => $arParams['SHOW_DISCOUNT_TIME_EACH_SKU'],

                    "AJAX_MODE" => $arParams["AJAX_MODE"],
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],

                    "ELEMENT_SORT_FIELD" => 'ID',
                    "ELEMENT_SORT_ORDER" => 'DESC',
                    //"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                    //"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                    "FILTER_NAME" => 'searchFilter',
                    "SECTION_URL" => $arParams["SECTION_URL"],
                    "DETAIL_URL" => $arParams["DETAIL_URL"],
                    "BASKET_URL" => $arParams["BASKET_URL"],
                    "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                    "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                    "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],

                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],

                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "SET_TITLE" => $arParams["SET_TITLE"],
                    "PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
                    "PRICE_CODE" => $arParams["PRICE_CODE"],
                    "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                    "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

                    "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                    "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                    "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                    "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
                    "TEMPLATE_THEME" => (isset($arParams["TEMPLATE_THEME"]) ? $arParams["TEMPLATE_THEME"] : ""),

                    "ADD_PICT_PROP" => (isset($arParams["ADD_PICT_PROP"]) ? $arParams["ADD_PICT_PROP"] : ""),

                    "LABEL_PROP" => (isset($arParams["LABEL_PROP"]) ? $arParams["LABEL_PROP"] : ""),
                    "OFFER_ADD_PICT_PROP" => (isset($arParams["OFFER_ADD_PICT_PROP"]) ? $arParams["OFFER_ADD_PICT_PROP"] : ""),
                    "OFFER_TREE_PROPS" => (isset($arParams["OFFER_TREE_PROPS"]) ? $arParams["OFFER_TREE_PROPS"] : ""),
                    "SHOW_DISCOUNT_PERCENT" => (isset($arParams["SHOW_DISCOUNT_PERCENT"]) ? $arParams["SHOW_DISCOUNT_PERCENT"] : ""),
                    "SHOW_OLD_PRICE" => (isset($arParams["SHOW_OLD_PRICE"]) ? $arParams["SHOW_OLD_PRICE"] : ""),
                    "MESS_BTN_BUY" => (isset($arParams["MESS_BTN_BUY"]) ? $arParams["MESS_BTN_BUY"] : ""),
                    "MESS_BTN_ADD_TO_BASKET" => (isset($arParams["MESS_BTN_ADD_TO_BASKET"]) ? $arParams["MESS_BTN_ADD_TO_BASKET"] : ""),
                    "MESS_BTN_DETAIL" => (isset($arParams["MESS_BTN_DETAIL"]) ? $arParams["MESS_BTN_DETAIL"] : ""),
                    "MESS_NOT_AVAILABLE" => (isset($arParams["MESS_NOT_AVAILABLE"]) ? $arParams["MESS_NOT_AVAILABLE"] : ""),
                    'ADD_TO_BASKET_ACTION' => (isset($arParams["ADD_TO_BASKET_ACTION"]) ? $arParams["ADD_TO_BASKET_ACTION"] : ""),
                    'SHOW_CLOSE_POPUP' => (isset($arParams["SHOW_CLOSE_POPUP"]) ? $arParams["SHOW_CLOSE_POPUP"] : ""),
                    'DISPLAY_COMPARE' => (isset($arParams['DISPLAY_COMPARE']) ? $arParams['DISPLAY_COMPARE'] : ''),
                    'COMPARE_PATH' => (isset($arParams['COMPARE_PATH']) ? $arParams['COMPARE_PATH'] : ''),
                    "SHOW_DISCOUNT_TIME" => $arParams["SHOW_DISCOUNT_TIME"],
                    "SALE_STIKER" => $arParams["SALE_STIKER"],
                    "SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
                    "DISPLAY_TYPE" => "block",
                    "SHOW_RATING" => $arParams["SHOW_RATING"],
                    "DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
                    "DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
                )
                + array(
                    'OFFER_ID' => $templateData['GIFTS_PARAMS']['OFFER_ID'],
                    'SECTION_ID' => $templateData['GIFTS_PARAMS']['SECTION_ID'],
                    'ELEMENT_ID' => $templateData['GIFTS_PARAMS']['ID'],
                ),
                $component,
                array("HIDE_ICONS" => "Y")
        );
    }
    ?>
    </div>