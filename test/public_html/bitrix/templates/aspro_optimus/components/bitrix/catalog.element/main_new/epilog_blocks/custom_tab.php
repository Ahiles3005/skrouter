<?$bTab = isset($tabCode) && $tabCode === 'custom_tab';?>

<?if($arParams["SHOW_ADDITIONAL_TAB"] == "Y"):?>
    <?if($bTab):?>
        <?if(!isset($bShow_custom_tab)):?>
            <?$bShow_custom_tab = true;?>
        <?else:?>
            <li class="<?=(!($iTab++) ? ' current' : '')?>" role="tabpanel" aria-labelledby="additional_tab" id="additional_panel">
                <div class="title-tab-heading aspro-bcolor-0099cc visible-xs"><?=GetMessage("ADDITIONAL_TAB");?></div>
                <div><?$APPLICATION->IncludeFile(SITE_DIR."include/additional_products_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ADDITIONAL_DESCRIPTION')));?></div>
            </li>
            <?endif;?>
    <?endif;?>
<?endif;?>

