<?

$bTab = isset($tabCode) && $tabCode === 'offer_prices';?>

<?if($templateData['OFFERS_INFO']['OFFERS'] && $arParams["TYPE_SKU"] !== "TYPE_1"):?>
    <?if($bTab):?>
        <?if(!isset($bShow_offer_prices)):?>
            <?$bShow_offer_prices = true;?>
        <?else:?>
                <?$APPLICATION->ShowViewContent('OFFERS_SHOW_BLOCK')?>
        <?endif;?>
    <?endif;?>
<?endif;?>