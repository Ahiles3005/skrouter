<?if($arParams["USE_REVIEW"] == "Y" && IsModuleInstalled("forum")):?>
	<div class="drag_block">
		<div class="wrap_md wrap_md_row">
			<div class="iblock serv md-100">
				<div id="product_reviews_tab">
					<h4><?=GetMessage("REVIEW_TAB")?></span><span class="count empty"></h4>
					<div id="reviews_content" style="display:block;padding-top:0px;">
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
				</div>
			</div>
		</div>
	</div>
<?endif;?>
