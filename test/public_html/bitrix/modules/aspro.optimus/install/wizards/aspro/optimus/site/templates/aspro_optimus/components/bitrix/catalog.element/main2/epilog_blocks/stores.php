<?
$bShowStoresTab = $templateData["STORES"]['USE_STORES'] && $templateData["STORES"]["STORES"];
?>

<?if($bShowStoresTab && ($templateData["SHOW_CUSTOM_OFFER"] || !$templateData["HAS_OFFERS"])):?>
	<div class="drag_block">
		<div class="wrap_md wrap_md_row">
			<div class="iblock stores md-100">
				<h4><?=GetMessage("STORES_TAB");?></h4>
				<div class="stores_tab">
					<?if(!$templateData["HAS_OFFERS"]){?>
						<?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "main", array(
							"PER_PAGE" => "10",
							"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
							"SCHEDULE" => $arParams["SCHEDULE"],
							"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
							"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
							"ELEMENT_ID" => $templateData["STORES"]['ELEMENT_ID'],
							"STORE_PATH"  =>  $arParams["STORE_PATH"],
							"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
							"MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
							"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
							"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
							"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
							"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
							"USER_FIELDS" => $arParams['USER_FIELDS'],
							"FIELDS" => $arParams['FIELDS'],
							"STORES" => $arParams['STORES'],
						),
						$component
					);?>
					<?}?>
				</div>
			</div>
		</div>
	</div>
<?endif;?>