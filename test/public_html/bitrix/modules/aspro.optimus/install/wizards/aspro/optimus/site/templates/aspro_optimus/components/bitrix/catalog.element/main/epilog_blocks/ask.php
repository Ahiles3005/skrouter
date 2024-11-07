<?$bTab = isset($tabCode) && $tabCode === 'ask';?>

<?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
    <?if($bTab):?>
        <?if(!isset($bShow_ask)):?>
            <?$bShow_ask = true;?>
        <?else:?>            
            <li class="<?=(!($iTab++) ? ' current' : '')?>" role="tabpanel" aria-labelledby="ask_tab" id="ask_panel">
                <div class="title-tab-heading aspro-bcolor-0099cc visible-xs"><?=GetMessage('ASK_TAB')?></div>
                <div class="wrap_md forms">
					<div class="iblock text_block">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/ask_tab_detail_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ASK_DESCRIPTION')));?>
					</div>
					<div class="iblock form_block">
						<div id="ask_block"></div>
					</div>
				</div>
            </li>
            <?endif;?>
    <?endif;?>
<?endif;?>