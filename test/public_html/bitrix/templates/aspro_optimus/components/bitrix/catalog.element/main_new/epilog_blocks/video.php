<?$bTab = isset($tabCode) && $tabCode === 'video';

if($templateData['VIDEO']):?>
    <?if($bTab):?>
        <?if(!isset($bShow_video)):?>
            <?$bShow_video = true;?>
        <?else:?>
            <li class="<?=(!($iTab++) ? ' current' : '')?>" role="tabpanel" aria-labelledby="video_tab" id="video_panel">
                <?$APPLICATION->ShowViewContent('VIDEO')?>
            </li>
        <?endif;?>
    <?endif;?>
<?endif;?>