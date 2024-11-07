<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	__IncludeLang($_SERVER["DOCUMENT_ROOT"].$templateFolder."/lang/".LANGUAGE_ID."/template.php");

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;


$arBlockOrder = explode(",", $arParams["DETAIL_BLOCKS_ALL_ORDER"]);?>

<div class="tabs_section">

<?$arEpilogBlocks = Aspro\Functions\CAsproOptimus::showEpilogBlocks([
	'STATIC' => ['tizers'],
	'ORDERED' => $arBlockOrder,
], __DIR__, $this->__templateName);

foreach ($arEpilogBlocks['STATIC'] as $path) {
	include_once $path;
	
}
foreach ($arEpilogBlocks['ORDERED'] as $path) {
	include_once $path;
}
?>

</div>

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
						$('.tabs_section .stores_tab').html(data);
						if("oid" in arSearch)
							$('.stores_tab .sku_stores_'+arSearch.oid).show();
						else
							$('.stores_tab > div:first').show();
					}
				});
			}
		})
	);
</script>
<?if(isset($_GET["RID"])){?>
	<?if($_GET["RID"]){?>
		<script>
			$(document).ready(function() {
				$("<div class='rid_item' data-rid='<?=$_GET["RID"];?>'></div>").appendTo($('body'));
			});
		</script>
	<?}?>
<?}?>