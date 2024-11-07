<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true );


 ?>
<?if (count($arResult["ITEMS"])):?>
<div class="grid__news">
		<?
			foreach($arResult["ITEMS"] as $arItem){
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				$arSize=array("WIDTH"=>376, "HEIGHT" => 212);
				$img = array();;
				
					if(!empty($arItem['DISPLAY_PROPERTIES']['HOME_IMG']['FILE_VALUE']['SRC'])
						&& !empty($arItem['DISPLAY_PROPERTIES']['HOME_IMG'])
						&& !empty($arItem['DISPLAY_PROPERTIES'])
					
					){
						
						$img["src"] = $arItem['DISPLAY_PROPERTIES']['HOME_IMG']['FILE_VALUE']['SRC'];
						 
						
					}elseif($arItem["PREVIEW_PICTURE"]){?>
						<?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array( "width" => $arSize["WIDTH"], "height" => $arSize["HEIGHT"] ), BX_RESIZE_IMAGE_EXACT, true );?>
						 
					<?}elseif($arItem["DETAIL_PICTURE"]){?>
						<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => $arSize["WIDTH"], "height" => $arSize["HEIGHT"] ), BX_RESIZE_IMAGE_EXACT, true );?>
						 
					<?}else{?>
						 
							 <?$img["src"] = SITE_TEMPLATE_PATH. "/images/no_photo_medium.png" ?> 
					<?}
					
					// echo '<pre  >';
		// print_r($arItem ); 
		// print_r($img ); 
		// echo '</pre>';
					
					?>
		
			<div class="news__item"  id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="news__img" style="background-image:url('<?=$img["src"]?>');"></a>
				<div class="news__date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="news__name"><?=$arItem["NAME"]?></a>
			</div>
		
		<?}?>
</div>

<?php /*
	<div class="articles-list lists_block news <?=($arParams["IS_VERTICAL"]=="Y" ? "vertical row" : "")?> <?=($arParams["SHOW_FAQ_BLOCK"]=="Y" ? "faq" : "")?> ">
		<?
			foreach($arResult["ITEMS"] as $arItem){
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				$arSize=array("WIDTH"=>280, "HEIGHT" => 190);
				if($arParams["SHOW_FAQ_BLOCK"]=="Y"){
					if($arParams["IS_VERTICAL"]!="Y")
						$arSize=array("WIDTH"=>175, "HEIGHT" => 120);
				}else{
					if($arParams["IS_VERTICAL"]!="Y")
						$arSize=array("WIDTH"=>190, "HEIGHT" => 130);
				}
		?>
			<div class="item clearfix item_block" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<div class="wrapper_inner_block">
					<?if($arItem["PREVIEW_PICTURE"]):?>
						<?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array( "width" => $arSize["WIDTH"], "height" => $arSize["HEIGHT"] ), BX_RESIZE_IMAGE_EXACT, true );?>
						<div class="left-data">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"])?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"])?>" /></a>
						</div>
					<?elseif($arItem["DETAIL_PICTURE"]):?>
						<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => $arSize["WIDTH"], "height" => $arSize["HEIGHT"] ), BX_RESIZE_IMAGE_EXACT, true );?>
						<div class="left-data">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItem["DETAIL_PICTURE"]["ALT"] ? $arItem["DETAIL_PICTURE"]["ALT"] : $arItem["NAME"])?>" title="<?=($arItem["DETAIL_PICTURE"]["TITLE"] ? $arItem["DETAIL_PICTURE"]["TITLE"] : $arItem["NAME"])?>" /></a>
						</div>
					<?else:?>
						<div class="left-data">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb"><img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" height="90" /></a>
						</div>
					<?endif;?>
					<div class="right-data">
						<?if($arParams["DISPLAY_DATE"]=="Y"){?>
							<?if( $arItem["PROPERTIES"]["PERIOD"]["VALUE"] ){?>
								<div class="date_small"><?=$arItem["PROPERTIES"]["PERIOD"]["VALUE"]?></div>
							<?}elseif($arItem["DISPLAY_ACTIVE_FROM"]){?>
								<div class="date_small"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></div>
							<?}?>
						<?}?>
						<div class="item-title"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span><?=$arItem["NAME"]?></span></a></div>
						<div class="preview-text"><?=$arItem["PREVIEW_TEXT"]?></div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		<?}?>
	</div>
	*/?>
	
	<?if( $arParams["DISPLAY_BOTTOM_PAGER"] ){?><?=$arResult["NAV_STRING"]?><?}?>
<?endif;?>