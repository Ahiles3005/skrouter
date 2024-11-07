<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<? 
// echo '<pre>'; print_r($arResult); echo '</pre>';
?>
<div id="lofslidecontent46" class="lof-slidecontent  hidden-xs">
	<div class="preload"><div></div></div>

	<!-- MAIN CONTENT --> 
    <div class="main-slider-content lof-main-outer" style="width:725px; height:100%;">
		<ul class="sliders-wrap-inner lof-main-wapper">
<!--<li> 
	<img src="'+intprojObj.adres[i]+'" title="'+intprojObj.title[i]+'" >
	<div class="slider-description"> 
		<h4>'+intprojObj.title[i]+'</h4> 
		<p>'+intprojObj.full[i]+'<a class="readmore" href="#">Read more</a></p>
	</div>
</li> -->

<!-- 
<li> 
	<div class="short-discr"> 
		<img src="images/slider_int_proj/smal_01.jpg" /> 
		<h3>'+intprojObj.title[i]+'</h3> 
		'+intprojObj.short[1]+'
	</div> 
</li>  -->
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	
	<li>
		<?if($arItem['DETAIL_PICTURE']):?>
			<img
						class="detail_picture"
						border="0"
						src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>"
						alt="<?=$arItem["DETAIL_PICTURE"]["ALT"]?>"
						title="<?=$arItem["DETAIL_PICTURE"]["TITLE"]?>"
						style="float:left"
						>
		<?endif;?>
		<div class="lof-main-item-desc slider-description">
			<h4>
				<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
					<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
						<?echo $arItem["NAME"]?>
					<?else:?>
						<?echo $arItem["NAME"]?>
					<?endif;?>
				<?endif;?>
			</h4>
				<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
					<p><?echo $arItem["PREVIEW_TEXT"];?></p>
				<?endif;?>
			<a class="readmore" href="<?echo $arItem["DETAIL_PAGE_URL"];?>">Подробнее</a>
		</div>
	</li>
<?endforeach;?>
</ul>
</div>

<div class="navigator-content">
	<div class="navigator-wrapper lof-navigator-outer">
		<ul class="navigator-wrap-inner lof-navigator">

<?foreach($arResult["ITEMS"] as $arItem):?>
			<li> 
				<div class="short-discr"> 
				
				<img
					class="preview_picture"
					border="0"
					src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
					alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
					title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
					style="float:left"
					>
				
					<h3>
						<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
							<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
								<?echo $arItem["NAME"]?>
							<?else:?>
								<?echo $arItem["NAME"]?>
							<?endif;?>
						<?endif;?>
					</h3> 
					<p>
						<?=htmlspecialcharsBack($arItem["PROPERTIES"]["supersmall"]["VALUE"]["TEXT"])?>
					</p>
				</div> 
			</li>
<?endforeach;?>		

		</ul>
	</div>
</div>
<div class="button-control"><span></span></div>
<!-- /////////////////////////////// -->
</div>




<div class="container visible-xs">
<div class="row visible-xs inmob">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<div class="col-xs-6">
		<img
					class="preview_picture"
					border="0"
					src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
					width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
					height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
					alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
					title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
					><br>
		<a class="readmore" href="<?echo $arItem["DETAIL_PAGE_URL"];?>"><?echo $arItem["NAME"]?></a>
		
	</div>
<?endforeach;?>	
</div></div>