<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
__IncludeLang($_SERVER["DOCUMENT_ROOT"].$templateFolder."/lang/".LANGUAGE_ID."/template.php");
	
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
	<div id="ask_block_content">
		<?$APPLICATION->IncludeComponent(
			"bitrix:form.result.new",
			"inline",
			Array(
				"WEB_FORM_ID" => $arParams["ASK_FORM_ID"],
				"IGNORE_CUSTOM_TEMPLATE" => "N",
				"USE_EXTENDED_ERRORS" => "N",
				"SEF_MODE" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "3600000",
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
<?endif;?>	

<?$bShowDetailTextTab = $templateData['DETAIL_TEXT'] || !empty($templateData['FILES']);

$arBlockOrder = explode(",", $arParams["DETAIL_BLOCKS_ORDER"]);
$arTabOrder = explode(",", $arParams["DETAIL_BLOCKS_TAB_ORDER"]);

$arEpilogBlocks = Aspro\Functions\CAsproOptimus::showEpilogBlocks([
'STATIC' => ['tizers'],
'ORDERED' => $arBlockOrder,
],__DIR__, $this->__templateName);

foreach ($arEpilogBlocks['STATIC'] as $path) {
	if(file_exists($path))
		include_once $path;
}
foreach ($arEpilogBlocks['ORDERED'] as $path) {
	if(file_exists($path))
		include_once $path;
}

?>

<?if($arResult["ID"]):?>
	<script type="text/javascript">
		viewItemCounter('<?=$arResult["ID"];?>','<?=current($arParams["PRICE_CODE"]);?>');
	</script>
<?endif;?>			

<?if (isset($templateData['TEMPLATE_LIBRARY']) && !empty($templateData['TEMPLATE_LIBRARY'])){
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
		$loadCurrency = Loader::includeModule('currency');
	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency){?>
		<script type="text/javascript">
			BX.Currency.setCurrencies(<? echo $templateData['CURRENCIES']; ?>);
		</script>
	<?}
}?>

<script type="text/javascript">
	if($(".specials_tabs_section.specials_slider_wrapp").length && $("#reviews_content").length){
		$("#reviews_content").after($(".specials_tabs_section.specials_slider_wrapp"));
	}
	
	if($("#reviews_content").length && !$(".tabs_section ul.tabs_content li.current").length){
		$(".shadow.common").hide();
		$("#reviews_content").show();
	}

	if($("#ask_block_content").length && $("#ask_block").length){
        $("#ask_block_content").appendTo($("#ask_block"));
    }
</script>

<script type="text/javascript">
	var viewedCounter = {
		path: '/bitrix/components/bitrix/catalog.element/ajax.php',
		params: {
			AJAX: 'Y',
			SITE_ID: "<?= SITE_ID ?>",
			PRODUCT_ID: "<?= $arResult['ID'] ?>",
			PARENT_ID: "<?= $arResult['ID'] ?>"
		}
	};
	BX.ready(
		BX.defer(function(){
			$('body').addClass('detail_page');
			<?//if(!isset($templateData['JS_OBJ'])){?>
				BX.ajax.post(
					viewedCounter.path,
					viewedCounter.params
				);
			<?//}?>
			if( $('.stores_tab').length ){
				$.ajax({
					type:"POST",
					url:arOptimusOptions['SITE_DIR']+"ajax/productStoreAmount.php",
					data:<?=CUtil::PhpToJSObject($templateData["STORES"], false, true, true)?>,
					success: function(data){
						var arSearch=parseUrlQuery();
						$('.tabs_content .stores_tab > div:eq(1)').html(data);
						if("oid" in arSearch)
							$('.stores_tab > div:eq(1) .sku_stores_'+arSearch.oid).show();
						else
							$('.stores_tab > div:eq(1) > div:first').show();
					}
				});
			}
		})
		
	);
</script>

<?if($_REQUEST && isset($_REQUEST['formresult'])):?>
	<script>
	$(document).ready(function() {
		if($('#ask_block .form_result').length){
			$('.product_ask_tab').trigger('click');
		}
	});
	</script>
<?endif;?>

<?if(isset($_GET["RID"])){?>
	<?if($_GET["RID"]){?>
		<script>
			$(document).ready(function() {
				$("<div class='rid_item' data-rid='<?=htmlspecialcharsbx($_GET["RID"]);?>'></div>").appendTo($('body'));
			});
		</script>
	<?}?>
<?}?>