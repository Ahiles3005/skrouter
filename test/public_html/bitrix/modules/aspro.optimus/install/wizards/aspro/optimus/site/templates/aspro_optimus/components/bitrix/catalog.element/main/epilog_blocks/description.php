<?$bTab = isset($tabCode) && $tabCode === 'description';
?>

<?if($bTab):?>
    <?if(!isset($bShow_description)):?>
        <?$bShow_description = true;?>
    <?else:?>
        <?if($templateData['DETAIL_TEXT'] || !empty($templateData['FILES']) || $templateData["STOCK"] || ($templateData['CHARACTERISTICS'] && $arParams["PROPERTIES_DISPLAY_LOCATION"] != "TAB")):?>
            <li class="<?=(!($iTab++) ? ' current' : '')?>" role="tabpanel" aria-labelledby="description_tab" id="description_panel">
                <div class="title-tab-heading aspro-bcolor-0099cc visible-xs"><?=GetMessage("DESCRIPTION_TAB");?></div>
                <?$APPLICATION->ShowViewContent('PRODUCT_DETAIL_TEXT_INFO')?>
            </li>
        <?endif;?>
    <?endif;?>
<?endif;?>

