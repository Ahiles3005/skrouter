<?$bTab = isset($tabCode) && $tabCode === 'product_reviews';?>
<?ob_start();?>
	<?if($arParams["USE_REVIEW"] == "Y" && IsModuleInstalled("forum")):?>
		<div id="reviews_content">
			<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("area");?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:forum.topic.reviews",
					"main2",
					Array(
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"MESSAGES_PER_PAGE" => $arParams["MESSAGES_PER_PAGE"],
						"USE_CAPTCHA" => $arParams["USE_CAPTCHA"],
						"FORUM_ID" => $arParams["FORUM_ID"],
						"ELEMENT_ID" => $arResult["ID"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"AJAX_POST" => $arParams["REVIEW_AJAX_POST"],
						"SHOW_RATING" => "N",
						"SHOW_MINIMIZED" => "Y",
						"SECTION_REVIEW" => "Y",
						"POST_FIRST_MESSAGE" => "Y",
						"MINIMIZED_MINIMIZE_TEXT" => GetMessage("HIDE_FORM"),
						"MINIMIZED_EXPAND_TEXT" => GetMessage("ADD_REVIEW"),
						"SHOW_AVATAR" => "N",
						"SHOW_LINK_TO_FORUM" => "N",
						"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
					),	false
				);?>
			<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("area", "");?>
		</div>
	<?endif;?>
	<?$html_reviews_main2 = trim(ob_get_clean());?>
	<?$bShowReviewsMain2 = $html_reviews_main2 && strpos($html_reviews_main2, 'error') === false;?>
	
<?if($arParams["USE_REVIEW"] == "Y"):?>

    <?if($bTab):?>
        <?if(!isset($bShow_product_reviews)):?>
                <?$bShow_product_reviews = true;?>
        <?else:?>
			<li class="product_reviews_tab<?=(!($iTab++) ? ' current' : '')?>" role="tabpanel" aria-labelledby="product_reviews_tab" id="product_reviews_panel"><div class="title-tab-heading js-reviews-tab aspro-bcolor-0099cc visible-xs"><?=($arParams["TAB_REVIEW_NAME"] ? $arParams["TAB_REVIEW_NAME"] : GetMessage("REVIEW_TAB"))?><span class="count empty"></span></div>
			<div id="for_product_reviews_tab" data-reviews-moved>		
			<?=$bShowReviewsMain2 ? $html_reviews_main2 : '';?>
					</div>
				</li>
        <?endif;?>
    <?endif;?>
<?endif;?>
