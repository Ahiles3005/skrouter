<?$bTab = isset($tabCode) && $tabCode === 'properties';

?>
<?if($templateData['CHARACTERISTICS'] && $arParams["PROPERTIES_DISPLAY_LOCATION"] == "TAB"):?>
    <?if($bTab):?>
        <?if(!isset($bShow_properties)):?>
            <?$bShow_properties = true;?>
        <?else:?>
            <li class="<?=(!($iTab++) ? ' current' : '')?>" role="tabpanel" aria-labelledby="properties_tab" id="properties_panel">
                <div class="title-tab-heading aspro-bcolor-0099cc visible-xs"><?=GetMessage("PROPERTIES_TAB");?></div><div>
                <?$APPLICATION->ShowViewContent('PRODUCT_PROPS_INFO_TAB')?>
            </li>
            <?endif;?>
    <?endif;?>
<?endif;?>