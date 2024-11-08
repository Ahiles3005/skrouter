<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<hr class="long"/>
<ul class="brands_list">
	<?foreach( $arResult["ITEMS"] as $arItem ){
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		$bDetailLink = ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] == 'Y' || $arParams['HIDE_LINK_WHEN_NO_DETAIL']  == 1) ? (strlen($arItem['DETAIL_TEXT'])) : true;
		?>
		<li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<?/*if($bDetailLink):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?endif;*/?>
				<?if( is_array($arItem["PREVIEW_PICTURE"]) ){?>
					<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"]?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"]?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
				<?}elseif( is_array($arItem["DETAIL_PICTURE"]) ){?>
					<img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=($arItem["DETAIL_PICTURE"]["ALT"]?$arItem["DETAIL_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=($arItem["DETAIL_PICTURE"]["TITLE"]?$arItem["DETAIL_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
				<?}else{?>
					<span><?=$arItem["NAME"]?></span>
				<?}?>
			<?/*if($bDetailLink):?></a><?endif;*/?>
		</li>
	<?}?>
</ul>

<?=$arResult["NAV_STRING"]?>