<?$bTab = isset($tabCode) && $tabCode === 'stores';
$bShowStoresTab =  $templateData["STORES"]['USE_STORES'] && $templateData["STORES"]["STORES"];

?>

<?if($bShowStoresTab && ($templateData['SHOW_CUSTOM_OFFER'] || !($templateData["STORES"]["OFFERS"] === 'Y'))):?>
		<?if($bTab):?>
				<?if(!isset($bShow_stores)):?>
						<?$bShow_stores = true;?>
				<?else:?>
						<li class="stores_tab<?=(!($iTab++) ? ' current' : '')?>" role="tabpanel" aria-labelledby="stores_tab" id="stores_panel">
								<div class="title-tab-heading aspro-bcolor-0099cc visible-xs"><?=GetMessage("STORES_TAB");?></div>
								<div>
										<?if($templateData["OFFERS_INFO"]['OFFERS']){?>
												<span></span>
										<?}else{?>
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
						</li>
				<?endif;?>
		<?endif;?>
<?endif;?>

<script type="text/javascript">
	if(!$(".stores_tab").length){
		$('.item-stock .store_view').removeClass('store_view');
	}
</script>

<?if($templateData["STORES"]["OFFERS"] === 'Y' && $arParams["TYPE_SKU"] !== "TYPE_1"):?>
	<script>
		$(document).ready(function() {
			$('.catalog_detail .tabs_section .tabs_content .form.inline input[data-sid="PRODUCT_NAME"]').attr('value', $('h1').text());
		});
	</script>
<?endif;?>
