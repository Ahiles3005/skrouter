<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?
$fVerComposite = (defined("SM_VERSION") && version_compare(SM_VERSION, "14.5.0") >= 0 ? true : false);
if ($fVerComposite) {
    $this->setFrameMode(true);
}
$ALX = "FID" . $arParams["FORM_ID"];

if (isset($arResult["POST"]))
{ ?>

        <? if (count($arResult["FORM_ERRORS"]) == 0 && ($arResult["success_" . $ALX] == "yes" || $_REQUEST["success_" . $ALX] == "yes")): ?>
        <div class="afbf_success_block<? if ($arParams['ALX_LINK_POPUP'] !== 'Y'): ?> _without-popup<? endif; ?>">
            <div class="afbf_mess_ok">
                <div class="afbf_ok_icon"></div>
                <div class="mess"><?= $arParams["MESSAGE_OK"]; ?></div>
            </div>
        </div>
    <? if ($arParams['ALX_LINK_POPUP'] == 'Y'): ?>
        <div class="afbf_close_container">
            <button class="modal_close_ok">OK</button>
        </div>
    <? endif ?>
    <? if ($arParams['SHOW_LINK_TO_SEND_MORE'] == 'Y'): ?>
        <div class="afbf_send_another_message">
            <a href="<?= $APPLICATION->GetCurUri() ?>"><?= $arParams['LINK_SEND_MORE_TEXT'] ?></a>
        </div>
    <? endif; ?>
        <script type="text/javascript">
            var param = {'width': '350', 'filledWithErrors': 'N', 'fid': '<?=$ALX?>'}
            if (typeof ALXpopup_<?=$ALX?>== 'undefined' && typeof ALXpopup != 'undefined' && typeof BX != 'undefined')
                var ALXpopup_<?=$ALX?>= BX.clone(ALXpopup);

            if (typeof ALXpopup_<?=$ALX?>!= 'undefined')
                ALXpopup_<?=$ALX?>.ok_window(param);
            else
                ALXpopup.ok_window(param);
        </script>
    <? elseif ($arParams["CHECK_ERROR"] == "Y" && count($arResult["FORM_ERRORS"]) > 0): ?>
    <? if ($arParams["USE_CAPTCHA"]): ?>
        <script type="text/javascript">
            <?if($arParams["CAPTCHA_TYPE"] != 'recaptcha'):?>
            <?if($arParams["CHANGE_CAPTCHA"] == "Y"):?>
            <?/**/?>        ALX_ChangeCaptcha('<?=$ALX?>');<?/**/?>
            <?else:?>
            ALX_ReloadCaptcha('<?=$_SESSION['ALX_CAPTHA_CODE']?>', '<?=$ALX?>');
            <?endif;?>
            <?else:?>
            grecaptcha.reset();
            <?endif;?>
        </script>
    <? endif ?>
    <? if ($arParams['ALX_LINK_POPUP'] !== 'Y'): ?>
        <script type="text/javascript">
            if (typeof ALXpopup_<?=$ALX?>== 'undefined' && typeof ALXpopup != 'undefined' && typeof BX != 'undefined')
                var ALXpopup_<?=$ALX?>= BX.clone(ALXpopup);

            $(document).ready(function () {
                var param = {
                    'popupWindow': "N",
                    'filledWithErrors': 'Y'
                };

                if (typeof ALXpopup_<?=$ALX?>!= 'undefined')
                    ALXpopup_<?=$ALX?>.init(param);
                else
                    ALXpopup.init(param);
            });
        </script>
    <? endif; ?>
        <div class="afbf_error_block">
            <div class="afbf_error_icon"></div>
            <div class="afbf_error_text">
			
				<?
				if(count($arResult["FORM_ERRORS"]["EMPTY_FIELD"])>0):?>
					<?= GetMessage('ALX_FILL_INPUTS_MSG'); ?><br />
				<?
				else:
					if(count($arResult["FORM_ERRORS"]["OTHER"])>0):
						foreach($arResult["FORM_ERRORS"]["OTHER"] as $key => $val)
							echo $val."<br />";
					endif;		
				endif;	
				?>			
			</div>
			
			<?
			//p($arResult["FORM_ERRORS"]);
				

			?>
        </div>
    <? endif; ?>
        <script type="text/javascript">
            validateForm($('.alx-feedb-data, #alx_feed_back_<?=$ALX?>.alx_feed_back').find('form'));
            <?if (strlen($arResult["FORM_ERRORS"]["CAPTCHA_WORD"]["ALX_CP_WRONG_CAPTCHA"]) > 0):?>
            ALX_captcha_Error();
            <?endif?>

            <?if (!empty($arResult["FORM_ERRORS"]["ERROR_FIELD"])):

            if(isset($arResult["FORM_ERRORS"]["EMPTY_FIELD"]["alx_fb_agreement"]))
            {
            ?>ALX_fileError($('#alx_feed_back_<?=$ALX?> #alx_fb_agreement'));
            <?
            }
            foreach($arResult["FIELDS"] as $k=>$v)
            {
            if($v["TYPE"] == "F" && !empty($arResult["FORM_ERRORS"]["EMPTY_FIELD"][$v["CODE"]]))
            {
            ?>ALX_fileError($('#alx_feed_back_<?=$ALX?> #afbf_<?=mb_strtolower($v["CODE"])?>'));<?
            }
            }
            ?>
            <?endif?>
            <?                if($arParams['LOCAL_REDIRECT_ENABLE'] == 'Y' && strlen($arParams['LOCAL_REDIRECT_URL']) > 0
            && ($arResult["success_" . $ALX] == "yes" || $_REQUEST["success_" . $ALX] == "yes")
            ):?>
            function AltasibFeedbackRedirect_<?=$ALX?>() {
                document.location.href = '<?=(trim(htmlspecialcharsEx($arParams['LOCAL_REDIRECT_URL'])));?>';
            }

            AltasibFeedbackRedirect_<?=$ALX?>();
            <?                endif?>
        </script>
    <? } ?>
