<?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
	<div class="drag_block">
		<div class="wrap_md wrap_md_row">
			<div class="iblock serv md-100">
				<h4><?=GetMessage("ASK_TAB");?></h4>
				<div class="wrap_md forms">
					<div class="iblock text_block">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/ask_tab_detail_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ASK_DESCRIPTION')));?>
					</div>
					<div class="iblock form_block">
						<?$APPLICATION->IncludeComponent(
							"bitrix:form.result.new",
							"inline",
							Array(
								"WEB_FORM_ID" => $arParams["ASK_FORM_ID"],
								"IGNORE_CUSTOM_TEMPLATE" => "N",
								"USE_EXTENDED_ERRORS" => "N",
								"SEF_MODE" => "N",
								"CACHE_TYPE" => "A",
								"CACHE_TIME" => "3600",
								"LIST_URL" => "",
								"EDIT_URL" => "",
								"SUCCESS_URL" => "?send=ok",
								"CHAIN_ITEM_TEXT" => "",
								"CHAIN_ITEM_LINK" => "",
								"VARIABLE_ALIASES" => Array("WEB_FORM_ID" => "WEB_FORM_ID", "RESULT_ID" => "RESULT_ID"),
								"AJAX_MODE" => "Y",
								"AJAX_OPTION_JUMP" => "N",
								"AJAX_OPTION_STYLE" => "Y",
								"AJAX_OPTION_HISTORY" => "N",
							)
						);?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?endif;?>