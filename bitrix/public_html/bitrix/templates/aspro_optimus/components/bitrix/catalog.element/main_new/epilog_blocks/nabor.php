<?if($templateData['OFFERS_INFO']['OFFERS']):?>
    <?if($templateData['OFFERS_INFO']['OFFER_GROUP']):?>
        <?foreach($templateData['OFFERS_INFO']['OFFERS'] as $arOffer):?>
            <?if(!$arOffer['OFFER_GROUP']) continue;?>
            <span id="<?=$templateData['ID_OFFER_GROUP'].$arOffer['ID']?>" style="display: none;">
                <?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor", "",
                    array(
                        "IBLOCK_ID" => $templateData['OFFERS_INFO']["OFFERS_IBLOCK"],
                        "ELEMENT_ID" => $arOffer['ID'],
                        "PRICE_CODE" => $arParams["PRICE_CODE"],
                        "BASKET_URL" => $arParams["BASKET_URL"],
                        "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        "SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
                        "SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
                        "SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
                        "CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
                        "CURRENCY_ID" => $arParams["CURRENCY_ID"]
                    ), $component, array("HIDE_ICONS" => "Y")
                );?>
            </span>
        <?endforeach;?>
    <?endif;?>
    <?else:?>
        <?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor", "",
            array(
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "ELEMENT_ID" => $arResult["ID"],
                "PRICE_CODE" => $arParams["PRICE_CODE"],
                "BASKET_URL" => $arParams["BASKET_URL"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
                "SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
                "SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
                "CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
                "CURRENCY_ID" => $arParams["CURRENCY_ID"]
            ), $component, array("HIDE_ICONS" => "Y")
        );?>
<?endif;?>