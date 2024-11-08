<?
/**
 * Optimus module
 * @copyright 2016 Aspro
 */

if(!defined('OPTIMUS_MODULE_ID')){
	define('OPTIMUS_MODULE_ID', 'aspro.optimus');
}

IncludeModuleLangFile(__FILE__);
use \Bitrix\Main\Type\Collection,
	\Bitrix\Main\Loader,
	\Bitrix\Main\IO\File,
	\Bitrix\Main\Localization\Loc,
	\Bitrix\Main\Config\Option;

class COptimus {
	use \Aspro\Optimus\Traits\Serialize;

    const partnerName	= "aspro";
    const solutionName	= "optimus";
	const moduleID		= OPTIMUS_MODULE_ID;
    const wizardID		= "aspro:optimus";
	const devMode 		= false;

	private static $arMetaParams = array();

    public static function ShowPanel(){
        if($GLOBALS["USER"]->IsAdmin() && COption::GetOptionString("main", "wizard_solution", "", SITE_ID) == self::solutionName){
            $GLOBALS["APPLICATION"]->SetAdditionalCSS("/bitrix/wizards/".self::partnerName."/".self::solutionName."/css/panel.css");
            $arMenu = array(
                array(
                    "ACTION" => "jsUtils.Redirect([], '".CUtil::JSEscape("/bitrix/admin/wizard_install.php?lang=".LANGUAGE_ID."&wizardSiteID=".SITE_ID."&wizardName=".self::wizardID."&".bitrix_sessid_get())."')",
                    "ICON" => "bx-popup-item-wizard-icon",
                    "TITLE" => GetMessage("STOM_BUTTON_TITLE_W1"),
                    "TEXT" => GetMessage("STOM_BUTTON_NAME_W1"),
                ),
            );
            $GLOBALS["APPLICATION"]->AddPanelButton(array(
                "HREF" => "/bitrix/admin/wizard_install.php?lang=".LANGUAGE_ID."&wizardName=".self::wizardID."&wizardSiteID=".SITE_ID."&".bitrix_sessid_get(),
                "ID" => self::solutionName."_wizard",
                "ICON" => "bx-panel-site-wizard-icon",
                "MAIN_SORT" => 2500,
                "TYPE" => "BIG",
                "SORT" => 10,
                "ALT" => GetMessage("SCOM_BUTTON_DESCRIPTION"),
                "TEXT" => GetMessage("SCOM_BUTTON_NAME"),
                "MENU" => $arMenu,
            ));
        }
    }

	public static function PrepareItemProps($arProps){
		if(is_array($arProps) && $arProps)
		{
			foreach($arProps as $PCODE => $arProperty)
			{
				if(in_array($PCODE, array('PERIOD', 'TITLE_BUTTON', 'LINK_BUTTON', 'REDIRECT', 'LINK_PROJECTS', 'LINK_REVIEWS', 'DOCUMENTS', 'FORM_ORDER', 'FORM_QUESTION', 'PHOTOPOS', 'TASK_PROJECT', 'PHOTOS', 'LINK_COMPANY', 'GALLEY_BIG', 'LINK_SERVICES', 'LINK_GOODS', 'LINK_STAFF', 'LINK_SALE', 'SERVICES', 'HIT', 'RECOMMEND', 'NEW', 'STOCK', 'VIDEO', 'VIDEO_YOUTUBE', 'CML2_ARTICLE','ARTICLE')))
					unset($arProps[$PCODE]);
				elseif(!$arProperty['VALUE'])
					unset($arProps[$PCODE]);
			}
		}
		else
			$arProps = array();

		return $arProps;
	}

	public static function getDataItem($el, $toJSON = true){
		$name = $el['NAME'];
		$priceFilter = $el['PROPERTIES']['FILTER_PRICE']['VALUE'] ?? $el['DISPLAY_PROPERTIES']['FILTER_PRICE']['VALUE'];
		$price = $el['PROPERTIES']['PRICE']['VALUE'] ?? $el['DISPLAY_PROPERTIES']['PRICE']['VALUE'];
		$priceOld = $el['PROPERTIES']['PRICEOLD']['VALUE'] ?? $el['DISPLAY_PROPERTIES']['PRICEOLD']['VALUE'];
		$currency = $el['PROPERTIES']['PRICE_CURRENCY']['VALUE'] ?? $el['DISPLAY_PROPERTIES']['PRICE_CURRENCY']['VALUE'];

		// tariff default price
		if(
			$el &&
			is_array($el['DEFAULT_PRICE'])
		) {
			if ($el['DEFAULT_PRICE']['FILTER_PRICE'] !== false) {
				$priceFilter = $el['DEFAULT_PRICE']['FILTER_PRICE'];
			}

			if ($el['DEFAULT_PRICE']['PRICE'] !== false) {
				$price = $el['DEFAULT_PRICE']['PRICE'];
			}

			if ($el['DEFAULT_PRICE']['OLDPRICE'] !== false) {
				$priceOld = $el['DEFAULT_PRICE']['OLDPRICE'];
			}

			$name = $name.' ('.$el['DEFAULT_PRICE']['TITLE'].')';
		}

		$dataItem = [
			"IBLOCK_ID" => $el['IBLOCK_ID'],
			"ID" => $el['ID'],
			"NAME" => $name,
			"DETAIL_PAGE_URL" => $el['DETAIL_PAGE_URL'],
			"PREVIEW_PICTURE" => (is_array($el['PREVIEW_PICTURE']) ? $el['PREVIEW_PICTURE']['ID'] : $el['PREVIEW_PICTURE']),
			"DETAIL_PICTURE" => (is_array($el['DETAIL_PICTURE']) ? $el['DETAIL_PICTURE']['ID'] : $el['DETAIL_PICTURE']),
			"PROPERTY_FILTER_PRICE_VALUE" => $priceFilter,
			"PROPERTY_PRICE_VALUE" => $price,
			"PROPERTY_PRICEOLD_VALUE" => $priceOld,
			"PROPERTY_PRICE_CURRENCY_VALUE" => $currency,
			"PROPERTY_ARTICLE_VALUE" => $el['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'],
			"PROPERTY_STATUS_VALUE" => $el['DISPLAY_PROPERTIES']['STATUS']['VALUE_ENUM_ID'],
		];

		global $APPLICATION;
		if ($toJSON) {
			$dataItem = $APPLICATION->ConvertCharsetArray($dataItem, SITE_CHARSET, 'UTF-8');
			$dataItem = json_encode($dataItem);
			$dataItem = htmlspecialchars($dataItem);
		}
		return $dataItem;
	}

	public static function ShowPageType($type = 'indexblocks'){
        global $APPLICATION, $TEMPLATE_OPTIONS;
        $path = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/page_blocks/'.$type.'_';
        $file = null;

        if(is_array($TEMPLATE_OPTIONS) && $TEMPLATE_OPTIONS){
            switch($type){
                case 'page_contacts':
                    $path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'contacts/'.$type);
                    $file = $path.'_'.$TEMPLATE_OPTIONS['PAGE_CONTACTS']['VALUE'].'.php';
                    break;
                case 'search_title_component':
                    $path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer/');
                    $file = $path.'site-search.php';
                    break;
                case 'basket_component':
                    $path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer/');
                    $file = $path.'site-basket.php';
                    break;
                case 'auth_component':
                    $path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer/');
                    $file = $path.'site-auth.php';
                    break;
                case 'bottom_counter':
                    $bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ',') === false); // is indexed yandex/google bot
                    if(!$bIndexBot)
                    {
                        $path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/');
                        $file = $path.'invis-counter.php';
                    }
                    break;
                case 'page_width':
                    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/width-'.$TEMPLATE_OPTIONS['PAGE_WIDTH']['VALUE'].'.css');
                    break;
                case 'h1_style':
                    if ($TEMPLATE_OPTIONS['H1_STYLE']['VALUE']=='Normal')
                        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/h1-normal.css');
                    else
                        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/h1-bold.css');
                    break;
                case 'footer':
                    $file = $path.(isset($TEMPLATE_OPTIONS['FOOTER_TYPE']['VALUE']) && $TEMPLATE_OPTIONS['FOOTER_TYPE']['VALUE'] ? $TEMPLATE_OPTIONS['FOOTER_TYPE']['VALUE'] : $TEMPLATE_OPTIONS['FOOTER_TYPE']).'.php';
                    break;
                case 'header':
                    $file = $path.$TEMPLATE_OPTIONS['HEADER_TYPE']['VALUE'].'.php';
                    break;
                case 'header_fixed':
                    $file = $path.'.php';
                    break;
                case 'header_fixed_mobile':
                    $file = $path.'.php';
                    break;
                case 'header_mobile_menu':
                    $file = $path.$TEMPLATE_OPTIONS['HEADER_MOBILE_MENU']['VALUE'].'.php';
                    break;
                case 'header_mobile_menu':
                    $file = $path.$TEMPLATE_OPTIONS['HEADER_MOBILE_MENU']['VALUE'].'.php';
                    break;
                case 'page_title':
                    $file = $path.$TEMPLATE_OPTIONS['PAGE_TITLE']['VALUE'].'.php';
                    break;
                case 'left_block':
                    $file = $path.$TEMPLATE_OPTIONS['LEFT_BLOCK']['VALUE'].'.php';
                    break;
                default:
                    $path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.$type);
                    $file = $path.'_'.$TEMPLATE_OPTIONS['INDEX_TYPE']['VALUE'].'.php';
                    break;
			}

            if ($file)
                @include_once $file;

        }
    }

    public static function BeforeSendEvent(\Bitrix\Main\Event $event){
		if(isset($_REQUEST["ONE_CLICK_BUY"]) && method_exists('\Bitrix\Sale\Compatible\EventCompatibility', 'setDisableMailSend')){
			\Bitrix\Sale\Compatible\EventCompatibility::setDisableMailSend(true);
			if(method_exists('\Bitrix\Sale\Notify', 'setNotifyDisable'))
				\Bitrix\Sale\Notify::setNotifyDisable(true);
		}
	}

	public static function Check(){
	}

	public static function Start($siteID){
		global  $APPLICATION, $SITE_THEME, $TEMPLATE_OPTIONS, $THEME_SWITCHER, $STARTTIME;
		$STARTTIME = time() * 1000;
		$SITE_THEME = COption::GetOptionString(self::moduleID, "COLOR_THEME", 'BLUE', $siteID);
		$SITE_THEMEBG = COption::GetOptionString(self::moduleID, "BGCOLOR_THEME", 'LIGHT', $siteID);
		$TEMPLATE_OPTIONS = self::GetTemplateOptions($siteID);
		$THEME_SWITCHER = COption::GetOptionString(self::moduleID, 'THEME_SWITCHER', 'N', $siteID);

		define("ASPRO_TEMPLATE_LOADED", true);

		if($TEMPLATE_OPTIONS && is_array($TEMPLATE_OPTIONS)){
			// reset theme
			if($_REQUEST["theme"] == "default"){
				foreach($TEMPLATE_OPTIONS as $templateOptionKey => $templateOptionValue){
					if(isset($templateOptionValue["DEFAULT"])){
						$default = $templateOptionValue["DEFAULT"];
						if (!is_array($default)) {
							$TEMPLATE_OPTIONS[$templateOptionKey]["CURRENT_VALUE"] = strToUpper($default);
							$_SESSION[SITE_ID][strToUpper($templateOptionKey)] = strToUpper($default);
						}
					}
				}
				COption::SetOptionString(self::moduleID, "NeedGenerateCustomTheme", 'Y', '', $siteID);
				COption::SetOptionString(self::moduleID, "NeedGenerateCustomThemeBG", 'Y', '', $siteID);
			}
			else{
				foreach($TEMPLATE_OPTIONS as $templateOptionKey => $templateOptionValue){
					// read theme from $_SESSION if $THEME_SWITCHER == Y
					$arOptionValues = array();
					if($templateOptionValue["VALUES"] && is_array($templateOptionValue["VALUES"])){
						foreach($templateOptionValue["VALUES"] as  $i => $j){
							$arOptionValues[] = $j["VALUE"];
						}
					}
					if(($THEME_SWITCHER == "Y") && $_SESSION[$siteID] && is_array($_SESSION[$siteID])){
						foreach($_SESSION[SITE_ID] as $sessionKey => $sessionValue){
							if($sessionKey == $templateOptionValue["ID"] && (($templateOptionValue["ID"] == "CUSTOM_COLOR_THEME" || $templateOptionValue["ID"] == "CUSTOM_BGCOLOR_THEME") || in_array($sessionValue, $arOptionValues))){
								$TEMPLATE_OPTIONS[$templateOptionKey]["CURRENT_VALUE"] = $sessionValue;
							}
						}
					}

					// save theme changes in $_SESSION if $THEME_SWITCHER == Y
					if($_REQUEST && is_array($_REQUEST)){
						foreach($_REQUEST as $requestKey => $requestValue){
							if(strToUpper($requestKey) == $templateOptionValue["ID"] && (($templateOptionValue["ID"] == "CUSTOM_COLOR_THEME" || $templateOptionValue["ID"] == "CUSTOM_BGCOLOR_THEME") || in_array(strToUpper($requestValue), $arOptionValues))){
								if(($templateOptionValue["ID"] == "CUSTOM_COLOR_THEME") || ($templateOptionValue["ID"] == "CUSTOM_BGCOLOR_THEME")){
									$requestValue = str_replace('#', '', $requestValue);
									$requestValue = (strlen($requestValue) ? $requestValue : $templateOptionValue['DEFAULT']);
								}

								if($templateOptionValue["ID"] == "COLOR_THEME" && $requestValue == 'CUSTOM'){
									COption::SetOptionString(self::moduleID, "NeedGenerateCustomTheme", 'Y', '', $siteID);
								}
								if($templateOptionValue["ID"] == "BGCOLOR_THEME" && $requestValue == 'CUSTOM'){
									COption::SetOptionString(self::moduleID, "NeedGenerateCustomThemeBG", 'Y', '', $siteID);
								}

								if($THEME_SWITCHER == "Y"){
									$_SESSION[$siteID][strToUpper($requestKey)] = strToUpper($requestValue);
									$TEMPLATE_OPTIONS[$templateOptionKey]["CURRENT_VALUE"] = strToUpper($requestValue);
								}
							}
						}
					}
				}
			}
		}
		
		// global flag for OnEndBufferContentHandler		
		$GLOBALS['_USE_LAZY_LOAD_OPTIMUS_'] = $TEMPLATE_OPTIONS['USE_LAZY_LOAD']["CURRENT_VALUE"] === 'Y';

		if(isset($_REQUEST["color_theme"]) && $_REQUEST["color_theme"]){
			LocalRedirect($_SERVER["HTTP_REFERER"]);
		}

		$SITE_THEME = $TEMPLATE_OPTIONS["COLOR_THEME"]["CURRENT_VALUE"];
		$SITE_THEMEBG = $TEMPLATE_OPTIONS["BGCOLOR_THEME"]["CURRENT_VALUE"];
		$SITE_TYPE_HOVER_IMG = $TEMPLATE_OPTIONS["DETAIL_PICTURE_MODE"]["CURRENT_VALUE"];

		$SITE_THEME_PATH = SITE_TEMPLATE_PATH.'/themes/'.strToLower($SITE_THEME.($SITE_THEME !== 'CUSTOM' ? '' : '_'.$siteID));
		$SITE_THEMEBG_PATH = SITE_TEMPLATE_PATH.'/bg_color/'.strToLower($SITE_THEMEBG.($SITE_THEMEBG !== 'CUSTOM' ? '' : '_'.$siteID));
		$APPLICATION->SetAdditionalCSS($SITE_THEME_PATH.'/theme.css', true);
		$APPLICATION->SetAdditionalCSS($SITE_THEMEBG_PATH.'/bgcolors.css', true);
		COptimus::GenerateThemes($siteID);

		if(CModule::IncludeModuleEx(self::moduleID) == 1){

			$bNAjaxMode = ((!isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) && (strtolower($_REQUEST['ajax']) != 'y'));

			$bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false); // is indexed yandex/google bot

			$APPLICATION->SetPageProperty("viewport", "initial-scale=1.0, width=device-width");
			$APPLICATION->SetPageProperty("HandheldFriendly", "true");
			$APPLICATION->SetPageProperty("apple-mobile-web-app-capable", "yes");
			$APPLICATION->SetPageProperty("apple-mobile-web-app-status-bar-style", "black");
			$APPLICATION->SetPageProperty("SKYPE_TOOLBAR", "SKYPE_TOOLBAR_PARSER_COMPATIBLE");

			if(!$bIndexBot)
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jquery.fancybox.css');

			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/styles.css');
			if($SITE_TYPE_HOVER_IMG == 'MAGNIFIER')
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/xzoom.css');
			if($_REQUEST && isset($_REQUEST['print'])){
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/print.css', true);
			}else{
				$APPLICATION->SetAdditionalCSS(((COption::GetOptionString('main', 'use_minified_assets', 'N', $siteID) === 'Y') && file_exists($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/css/media.min.css')) ? SITE_TEMPLATE_PATH.'/css/media.min.css' : SITE_TEMPLATE_PATH.'/css/media.css', true);
			}

			$sCustomFont = \Bitrix\Main\Config\Option::get('aspro.optimus', 'CUSTOM_FONT', '');
			if($bNAjaxMode)
			{
				if(!$bIndexBot)
				{

					if($GLOBALS['_USE_LAZY_LOAD_OPTIMUS_']){
						$APPLICATION->AddHeadString('<script>window.lazySizesConfig = window.lazySizesConfig || {};lazySizesConfig.loadMode = 2;lazySizesConfig.expand = 100;lazySizesConfig.expFactor = 1;lazySizesConfig.hFac = 0.1;window.lazySizesConfig.lazyClass = "lazy";</script>');
						$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/lazysizes.min.js');
						$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/ls.unveilhooks.min.js');
					}

					if($sCustomFont)
						$APPLICATION->AddHeadString('<'.$sCustomFont.'>');
					$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/fonts/font-awesome/css/font-awesome.min.css', true);
					$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/print.css', true);
					$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/animation/animation_ext.css');
					$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jquery.mCustomScrollbar.min.css');
					$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/custom.css', true);
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.actual.min.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jqModal.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.fancybox.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.history.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.flexslider.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.validate.min.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.inputmask.bundle.min.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.easing.1.3.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/equalize.min.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.alphanumeric.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.cookie.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.plugin.min.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.countdown.min.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.countdown-ru.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.ikSelect.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/sly.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/equalize_ext.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.mousewheel-3.0.6.min.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.mCustomScrollbar.min.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.dotdotdot.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/velocity.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/velocity.ui.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.appear.js');
					// $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/zoomsl-3.0.js');
					if($SITE_TYPE_HOVER_IMG == 'MAGNIFIER')
						$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/xzoom.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/main.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/custom.js', true);
				}

				if(strlen($TEMPLATE_OPTIONS['FAVICON_IMAGE']['CURRENT_IMG'])){
					
					$file_ext = pathinfo($TEMPLATE_OPTIONS['FAVICON_IMAGE']['CURRENT_IMG'], PATHINFO_EXTENSION);
					$fav_ext = $file_ext ? $file_ext : 'ico';
					$fav_type = '';

					switch ($fav_ext) {
						case 'ico': 
							$fav_type = 'image/x-icon';
							break;
						case 'svg': 
							$fav_type = 'image/svg+xml';
							break;
						case 'png': 
							$fav_type = 'image/png';
							break;
						case 'jpg': 
							$fav_type = 'image/jpeg';
							break;
						case 'gif': 
							$fav_type = 'image/gif';
							break;
						case 'bmp': 
							$fav_type = 'image/bmp';
							break;
					}

					$APPLICATION->AddHeadString('<link rel="shortcut icon" href="'.$TEMPLATE_OPTIONS['FAVICON_IMAGE']['CURRENT_IMG'].'" type="'.$fav_type.'" />', true);
				}
				if(strlen($TEMPLATE_OPTIONS['APPLE_TOUCH_ICON_IMAGE']['CURRENT_IMG'])){
					$APPLICATION->AddHeadString('<link rel="apple-touch-icon" sizes="180x180" href="'.$TEMPLATE_OPTIONS['APPLE_TOUCH_ICON_IMAGE']['CURRENT_IMG'].'" />', true);
				}
			}

			if(!$bIndexBot)
			{
				CJSCore::Init(array("jquery", "ls"));
				CAjax::Init();
			}
			else
			{
				CJSCore::Init(array("jquery"));
			}
			\Bitrix\Main\Loader::includeModule('iblock');
			\Bitrix\Main\Loader::includeModule('sale');
			\Bitrix\Main\Loader::includeModule('catalog');

			if(self::IsBasketPage())
			{
				CJSCore::Init(array('fx'));
			}

			self::showBgImage($siteID);?>

			<?//clear basket session counter in basket page?>
			<?if($_COOKIE['click_basket'] && $_COOKIE['click_basket'] == 'Y'):?>
				<?self::clearBasketCounters();?>
				<?unset($_COOKIE['click_basket'])?>
				<script>
					$.removeCookie('click_basket', {path: '/'});
				</script>
			<?endif;?>
			<?

			self::setCssVariables();

			// register js and css libs
			\Aspro\Functions\Extensions::register();

			//cart notice
			\Aspro\Functions\Extensions::init('notice');
			?>

			<?return true;
		}
		else{
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/styles.css');
			$APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE"));
			$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php", Array(), Array()); die();
		}
	}

	public static function checkBgImage($siteID){
		global $APPLICATION;
		static $arBanner;
		if($arBanner===NULL){
			$arItems = COptimusCache::CIBLockElement_GetList(array("SORT" => "ASC", 'CACHE' => array('TAG' => COptimusCache::GetIBlockCacheTag(COptimusCache::$arIBlocks[$siteID]["aspro_optimus_adv"]["aspro_optimus_bg_images"][0]))), array('IBLOCK_ID' => COptimusCache::$arIBlocks[$siteID]["aspro_optimus_adv"]["aspro_optimus_bg_images"][0], "ACTIVE"=>"Y"), false, false, array("ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_URL", "PROPERTY_FIXED_BANNER", "PROPERTY_URL_NOT_SHOW"));
			$arBanner=array();
			if($arItems){
				$curPage=$APPLICATION->GetCurPage();
				foreach($arItems as $arItem){
					if(isset($arItem["PROPERTY_URL_VALUE"]) && $arItem["PREVIEW_PICTURE"]){
						if(!is_array($arItem["PROPERTY_URL_VALUE"]))
							$arItem["PROPERTY_URL_VALUE"]=array($arItem["PROPERTY_URL_VALUE"]);
						if($arItem["PROPERTY_URL_VALUE"]){
							foreach($arItem["PROPERTY_URL_VALUE"] as $url){
								$url=str_replace("SITE_DIR", SITE_DIR, $url);
								if($arItem["PROPERTY_URL_NOT_SHOW_VALUE"]){
									if(!is_array($arItem["PROPERTY_URL_NOT_SHOW_VALUE"]))
										$arItem["PROPERTY_URL_NOT_SHOW_VALUE"]=array($arItem["PROPERTY_URL_NOT_SHOW_VALUE"]);
									foreach($arItem["PROPERTY_URL_NOT_SHOW_VALUE"] as $url_not_show){
										$url_not_show=str_replace("SITE_DIR", SITE_DIR, $url_not_show);
										if(CSite::InDir($url_not_show)){
											break 2;
										}
									}
									foreach($arItem["PROPERTY_URL_NOT_SHOW_VALUE"] as $url_not_show){
										$url_not_show=str_replace("SITE_DIR", SITE_DIR, $url_not_show);
										if(CSite::InDir($url_not_show)){
											// continue;
											break 2;
										}else{
											if(CSite::InDir($url)){
												$arBanner=$arItem;
												break;
											}
										}
									}
								}else{
									if(CSite::InDir($url)){
										$arBanner=$arItem;
										break;
									}
								}
							}
						}
					}
				}
			}
		}
		return $arBanner;
	}

	public static function showBgImage($siteID){
		global $APPLICATION;
		$arBanner=self::checkBgImage($siteID);
		if($arBanner){
			$image=CFile::GetFileArray($arBanner["PREVIEW_PICTURE"]);
			$class="bg_image_site";
			if($arBanner["PROPERTY_FIXED_BANNER_VALUE"]=="Y")
				$class.=" fixed";
			if(self::IsMainPage())
				$class.=" opacity";
			$APPLICATION->AddHeadString('<script>$(document).ready(function(){$(\'body\').append("<span class=\''.$class.'\' style=\'background-image:url('.$image["SRC"].');\'></span>");})</script>');
		}
		return true;
	}

	public static function ShowLogo(){
		global $arSite, $TEMPLATE_OPTIONS;

		$text = '<a href="'.SITE_DIR.'">';
		if($arImg =  self::unserialize(Option::get(self::moduleID, "LOGO_IMAGE", serialize(array()))))
			$text .= '<img src="'.CFile::GetPath($arImg[0]).'" alt="'.$arSite["SITE_NAME"].'" title="'.$arSite["SITE_NAME"].'" data-src="" />';
		elseif(self::checkContentFile(SITE_DIR.'/include/logo_svg.php'))
			$text .= File::getFileContents($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/include/logo_svg.php');
		else
			$text .= '<img src="'.$TEMPLATE_OPTIONS["LOGO_IMAGE"]["CURRENT_IMG"].'" alt="'.$arSite["SITE_NAME"].'" title="'.$arSite["SITE_NAME"].'" data-src="" />';
		$text .= '</a>';

		echo $text;
		?>
	<?}

	public static  function getCurrentPageClass(){
		static $result;
		global $TEMPLATE_OPTIONS, $APPLICATION;
		$arHidePage = array("front_page", "catalog_page");

		if(!isset($result)){
			if(self::IsMainPage())
				$result = 'front';
			if(self::IsAuthSection())
				$result = 'auth';
			if(self::IsBasketPage())
				$result = 'basket';
			if(self::IsCatalogPage())
				$result = 'catalog';
			if(self::IsPersonalPage())
				$result = 'personal';
			if(self::IsOrderPage())
				$result = 'order';
			if($result)
				$result.='_page';

			if($APPLICATION->GetProperty('HIDE_LEFT_BLOCK') == 'Y')
				$result .= ' wides';

			if($TEMPLATE_OPTIONS['MENU_POSITION_MAIN']['CURRENT_VALUE'] == 'HIDE' && in_array($result, $arHidePage))
				$result = 'hide_catalog';

			$result .= ($TEMPLATE_OPTIONS['USE_FAST_VIEW_PAGE_DETAIL']['CURRENT_VALUE'] != 'N' ? ' with_fast_view ' : '');
			$result .= (\Bitrix\Main\Config\Option::get(self::moduleID, 'SHOW_DELAY', 'Y') != 'N' ? '' : ' hidden-delay');
			$result .= (\Bitrix\Main\Config\Option::get(self::moduleID, 'SHOW_COMPARE', 'Y') != 'N' ? '' : ' hidden-compare');
			$result .= ($TEMPLATE_OPTIONS['MOBILE_CATALOG_BLOCK_COMPACT']['CURRENT_VALUE'] != 'N' ? ' compact-catalog' : '');
		}
		return $result;
	}

	public static  function IsMainPage(){
		static $result;

		if(!isset($result)){
			$result = CSite::InDir(SITE_DIR.'index.php');
		}

		return $result;
	}

	public static function IsAuthSection(){
		static $result;

		if(!isset($result)){
			$result = CSite::InDir(SITE_DIR.'auth/');
		}

		return $result;
	}

	public static function IsBasketPage($page = ''){
		static $result;

		if(!isset($result)){
			if(!$page)
			{
				$page = \Bitrix\Main\Config\Option::get(self::moduleID, 'BASKET_PAGE_URL', SITE_DIR.'basket/', SITE_ID);
				$page = str_replace('#SITE_DIR#', SITE_DIR, $page);
			}
			$result = CSite::InDir($page);
		}

		return $result;
	}

	public static function IsCatalogPage($page = ''){
		static $result;

		if(!isset($result)){
			if(!$page)
			{
				$page = \Bitrix\Main\Config\Option::get(self::moduleID, 'CATALOG_PAGE_URL', SITE_DIR.'catalog/', SITE_ID);
				$page = str_replace('#SITE_DIR#', SITE_DIR, $page);
			}
			$result = CSite::InDir($page);
		}

		return $result;
	}

	public static function IsOrderPage($page = ''){
		static $result;
		global $APPLICATION;

		if(!isset($result)){
			if(!$page)
			{
				$page = \Bitrix\Main\Config\Option::get(self::moduleID, 'ORDER_PAGE_URL', SITE_DIR.'order/', SITE_ID);
				$page = str_replace('#SITE_DIR#', SITE_DIR, $page);
			}
			$result = CSite::InDir($page);

			if(!$result)
			{
				if($APPLICATION->GetProperty('HIDE_LEFT_BLOCK') == 'Y')
					$result = true;
			}
		}

		return $result;
	}

	public static function IsPersonalPage(){
		static $result;

		if(!isset($result)){
			if(!$page)
			{
				$page = \Bitrix\Main\Config\Option::get(self::moduleID, 'PERSONAL_PAGE_URL', SITE_DIR.'personal/', SITE_ID);
				$page = str_replace('#SITE_DIR#', SITE_DIR, $page);
			}
			$result = CSite::InDir($page);
		}

		return $result;
	}

	public static function GenerateMinCss($file){
		if(file_exists($file)){
			$content = @file_get_contents($file);
			if($content !== false){
				$content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
				$content = str_replace(array("\r\n", "\r", "\n", "\t"), '', $content);
				$content = preg_replace('/ {2,}/', ' ', $content);
				$content = str_replace(array(' : ', ': ', ' :',), ':', $content);
				$content = str_replace(array(' ; ', '; ', ' ;'), ';', $content);
				$content = str_replace(array(' > ', '> ', ' >'), '>', $content);
				$content = str_replace(array(' + ', '+ ', ' +'), '+', $content);
				$content = str_replace(array(' { ', '{ ', ' {'), '{', $content);
				$content = str_replace(array(' } ', '} ', ' }'), '}', $content);
				$content = str_replace(array(' ( ', '( ', ' ('), '(', $content);
				$content = str_replace(array(' ) ', ') ', ' )'), ')', $content);
				$content = str_replace('and(', 'and (', $content);
				$content = str_replace(')li', ') li', $content);
				$content = str_replace(').', ') .', $content);
				@file_put_contents(dirname($file).'/'.basename($file, '.css').'.min.css', $content);
			}
		}

		return false;
	}

	public static function GenerateThemes($siteID){
		global $SITE_THEME, $TEMPLATE_OPTIONS, $THEME_SWITCHER;
		$arBaseColors = $TEMPLATE_OPTIONS['COLOR_THEME']['VALUES'];
		$arBaseBgColors = $TEMPLATE_OPTIONS['BGCOLOR_THEME']['VALUES'];
		$isCustomTheme = $TEMPLATE_OPTIONS['COLOR_THEME']['CURRENT_VALUE'] === 'CUSTOM';
		$isCustomThemeBG = $TEMPLATE_OPTIONS['BGCOLOR_THEME']['CURRENT_VALUE'] === 'CUSTOM';

		$bNeedGenerateAllThemes = COption::GetOptionString(self::moduleID, 'NeedGenerateThemes', 'N', $siteID) === 'Y';
		$bNeedGenerateCustomTheme = COption::GetOptionString(self::moduleID, 'NeedGenerateCustomTheme', 'N', $siteID) === 'Y';
		$bNeedGenerateCustomThemeBG = COption::GetOptionString(self::moduleID, 'NeedGenerateCustomThemeBG', 'N', $siteID) === 'Y';

		$baseColorCustom = $baseColorBGCustom = '';
		$lastGeneratedBaseColorCustom = COption::GetOptionString(self::moduleID, 'LastGeneratedBaseColorCustom', '', $siteID);
		if(isset($TEMPLATE_OPTIONS['CUSTOM_COLOR_THEME'])){
			$baseColorCustom = str_replace('#', '', $TEMPLATE_OPTIONS['CUSTOM_COLOR_THEME']['CURRENT_VALUE']);
			$baseColorCustom = '#'.(strlen($baseColorCustom) ? $baseColorCustom : $TEMPLATE_OPTIONS['CUSTOM_COLOR_THEME']['DEFAULT']);
		}
		$lastGeneratedBaseColorBGCustom = COption::GetOptionString(self::moduleID, 'LastGeneratedBaseColorBGCustom', '', $siteID);
		if(isset($TEMPLATE_OPTIONS['CUSTOM_BGCOLOR_THEME'])){
			$baseColorBGCustom = str_replace('#', '', $TEMPLATE_OPTIONS['CUSTOM_BGCOLOR_THEME']['CURRENT_VALUE']);
			$baseColorBGCustom = (strlen($baseColorBGCustom) ? $baseColorBGCustom : $TEMPLATE_OPTIONS['CUSTOM_BGCOLOR_THEME']['DEFAULT']);
		}

		$bGenerateAll = self::devMode || $bNeedGenerateAllThemes;
		$bGenerateCustom = $bGenerateAll || $bNeedGenerateCustomTheme || ($THEME_SWITCHER === 'Y' && $isCustomTheme && strlen($baseColorCustom) && $baseColorCustom != $lastGeneratedBaseColorCustom);
		$bGenerateCustomBG = $bGenerateAll || $bNeedGenerateCustomThemeBG || ($THEME_SWITCHER === 'Y' && $isCustomThemeBG && strlen($baseColorBGCustom) && $baseColorBGCustom != $lastGeneratedBaseColorBGCustom);
		if($arBaseColors && is_array($arBaseColors) && ($bGenerateAll || $bGenerateCustom || $isCustomThemeBG)){
			if(!class_exists('lessc')){
				include_once 'lessc.inc.php';
			}
			$less = new lessc;
			try{

				if(defined('SITE_TEMPLATE_PATH'))
				{
					$templateName = array_pop(explode('/', SITE_TEMPLATE_PATH));
				}

				foreach($arBaseColors as $arColor){
					if(($bCustom = ($arColor['VALUE'] == 'CUSTOM')) && $bGenerateCustom){
						if(strlen($baseColorCustom)){
							$less->setVariables(array('bcolor' => $baseColorCustom));
						}
					}
					elseif($bGenerateAll){
						$less->setVariables(array('bcolor' => $arColor['COMPONENT_VALUE']));
					}

					if($bGenerateAll || ($bCustom && $bGenerateCustom)){
						if(defined('SITE_TEMPLATE_PATH')){
							$themeDirPath = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/themes/'.strToLower($arColor['VALUE'].($arColor['VALUE'] !== 'CUSTOM' ? '' : '_'.$siteID)).'/';
							if(!is_dir($themeDirPath)) mkdir($themeDirPath, 0755, true);
							$output = $less->compileFile(__DIR__.'/../../css/theme.less', $themeDirPath.'theme.css');
							if($output){
								if($bCustom){
									COption::SetOptionString(self::moduleID, 'LastGeneratedBaseColorCustom', $baseColorCustom, '', $siteID);
								}
								self::GenerateMinCss($themeDirPath.'theme.css');
							}

							if($templateName && $templateName != 'aspro_optimus') {

								$themeDirPath = $_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/aspro_optimus'.'/themes/'.strToLower($colorCode.($colorCode !== 'CUSTOM' ? '' : '_'.$siteID)).'/';
								if(!is_dir($themeDirPath))
									mkdir($themeDirPath, 0755, true);
								$output = $less->compileFile(__DIR__.'/../../css/theme.less', $themeDirPath.'theme.css');
								if($output)
								{
									self::GenerateMinCss($themeDirPath.'theme.css');
								}

							}
						}
					}
				}
				foreach($arBaseBgColors as $arColor){
					if(($bCustom = ($arColor['VALUE'] == 'CUSTOM')) && $bGenerateCustomBG){
						if(strlen($baseColorBGCustom)){
							$footerBgColor = $baseColorBGCustom === "FFFFFF" ? "F6F6F7" : $baseColorBGCustom;
							$less->setVariables(array('bcolor' => '#'.$baseColorBGCustom, 'fcolor' => '#'.$footerBgColor));
						}
					}
					elseif($bGenerateAll){
						$less->setVariables(array('bcolor' => $arColor['COMPONENT_VALUE'], 'fcolor' => $arColor['COMPONENT_VALUE']));
					}

					if($bGenerateAll || ($bCustom && $bGenerateCustomBG)){
						if(defined('SITE_TEMPLATE_PATH')){
							$themeDirPath = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/bg_color/'.strToLower($arColor['VALUE'].($arColor['VALUE'] !== 'CUSTOM' ? '' : '_'.$siteID)).'/';
							if(!is_dir($themeDirPath)) mkdir($themeDirPath, 0755, true);
							$output = $less->compileFile(__DIR__.'/../../css/bgtheme.less', $themeDirPath.'bgcolors.css');
							if($output){
								if($bCustom){
									COption::SetOptionString(self::moduleID, 'LastGeneratedBaseColorBGCustom', $baseColorBGCustom, '', $siteID);
								}
								self::GenerateMinCss($themeDirPath.'bgcolors.css');
							}

							if($templateName && $templateName != 'aspro_optimus') {

								$themeDirPath = $_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/aspro_optimus'.'/bg_color/'.strToLower($colorCode.($colorCode !== 'CUSTOM' ? '' : '_'.$siteID)).'/';
								if(!is_dir($themeDirPath))
									mkdir($themeDirPath, 0755, true);
								$output = $less->compileFile(__DIR__.'/../../css/bgtheme.less', $themeDirPath.'bgcolors.css');
								if($output)
								{
									self::GenerateMinCss($themeDirPath.'bgcolors.css');
								}

							}
						}
					}
				}
			}
			catch(exception $e){
				echo 'Fatal error: '.$e->getMessage();
				die();
			}

			if($bNeedGenerateAllThemes){
				COption::SetOptionString(self::moduleID, "NeedGenerateThemes", 'N', '', $siteID);
			}
			if($bNeedGenerateCustomTheme){
				COption::SetOptionString(self::moduleID, "NeedGenerateCustomTheme", 'N', '', $siteID);
			}
			if($bNeedGenerateCustomThemeBG){
				COption::SetOptionString(self::moduleID, "NeedGenerateCustomThemeBG", 'N', '', $siteID);
			}
		}
	}

	public static function getChilds($input, &$start = 0, $level = 0){
		$childs = array();

		if(!$level){
			$lastDepthLevel = 1;
			if(is_array($input)){
				foreach($input as $i => $arItem){
					if($arItem["DEPTH_LEVEL"] > $lastDepthLevel){
						if($i > 0){
							$input[$i - 1]["IS_PARENT"] = 1;
						}
					}
					$lastDepthLevel = $arItem["DEPTH_LEVEL"];
				}
			}
		}

		for($i = $start, $count = count($input); $i < $count; ++$i){
			$item = $input[$i];
			if($level > $item['DEPTH_LEVEL'] - 1){
				break;
			}
			elseif(!empty($item['IS_PARENT'])){
				++$i;
				$item['CHILD'] = self::getChilds($input, $i, $level + 1);
				--$i;
			}
			$childs[] = $item;
		}

		$start = $i;
		return $childs;
	}

	public static function unique_multidim_array($array, $key) {
	    $temp_array = array();
	    $i = 0;
	    $key_array = array();

	    foreach($array as $val) {
	        if (!in_array($val[$key], $key_array)) {
	            $key_array[$i] = $val[$key];
	            $temp_array[$i] = $val;
	        }
	        $i++;
	    }
	    return $temp_array;
	}

	public static function convertArray($array, $charset){
		global $APPLICATION;
	    if(is_array($array) && $array){
		    foreach($array as $key=>$arVal) {
		    	foreach($arVal as $key2=>$value){
					$array[$key][$key2]=$APPLICATION->ConvertCharset($value, 'UTF-8', $charset);
		    	}
		    }
		}else{
			$array=array();
		}
	    return $array;
	}

	public static function getChilds2($input, &$start = 0, $level = 0){
		static $arIblockItemsMD5 = array();

		if(!$level){
			$lastDepthLevel = 1;
			if($input && is_array($input)){
				foreach($input as $i => $arItem){
					if($arItem['DEPTH_LEVEL'] > $lastDepthLevel){
						if($i > 0){
							$input[$i - 1]['IS_PARENT'] = 1;
						}
					}
					$lastDepthLevel = $arItem['DEPTH_LEVEL'];
				}
			}
		}

		$childs = array();
		$count = count($input);
		for($i = $start; $i < $count; ++$i){
			$item = $input[$i];
			if(!isset($item)){
				continue;
			}
			if($level > $item['DEPTH_LEVEL'] - 1){
				break;
			}
			else{
				if(!empty($item['IS_PARENT'])){
					$i++;
					$item['CHILD'] = self::getChilds($input, $i, $level+1);
					$i--;
				}

				$childs[] = $item;
			}
		}
		$start = $i;

		if(is_array($childs)){
			foreach($childs as $j => $item){
				if($item['PARAMS']){
					$md5 = md5($item['TEXT'].$item['LINK'].$item['SELECTED'].$item['PERMISSION'].$item['ITEM_TYPE'].$item['IS_PARENT'].serialize($item['ADDITIONAL_LINKS']).serialize($item['PARAMS']));
					if(isset($arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']])){
						if(isset($arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']][$level]) || ($item['DEPTH_LEVEL'] === 1 && !$level)){
							unset($childs[$j]);
							continue;
						}
					}
					if(!isset($arIblockItemsMD5[$md5])){
						$arIblockItemsMD5[$md5] = array($item['PARAMS']['DEPTH_LEVEL'] => array($level => true));
					}
					else{
						$arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']][$level] = true;
					}
				}
			}
		}

		if(!$level){
			$arIblockItemsMD5 = array();
		}

		return $childs;
	}

	public static function formatPriceMatrix($arItem = array()){
		if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX'])
		{
			$result = false;
			$minPrice = 0;
			foreach($arItem['PRICE_MATRIX']['MATRIX'] as $key => $arPriceGroup)
			{
				foreach($arPriceGroup as $key2 => $arPrice)
				{
					if($arPrice['PRICE'])
					{
						if($arItem['PRICE_MATRIX']['CAN_BUY'] && in_array($key, $arItem['PRICE_MATRIX']['CAN_BUY']) && $arItem['CATALOG_TYPE'] == 1 || $arItem['CATALOG_TYPE'] == 2)
						{
							if (empty($result))
							{
								$minPrice = ($arPrice['DISCOUNT_PRICE'] != $arPrice['PRICE'] ? $arPrice['DISCOUNT_PRICE'] : $arPrice['PRICE']);
								$result = $minPrice;
							}
							else
							{
								$comparePrice = ($arPrice['DISCOUNT_PRICE'] != $arPrice['PRICE'] ? $arPrice['DISCOUNT_PRICE'] : $arPrice['PRICE']);
								if ($minPrice > $comparePrice)
								{
									$minPrice = $comparePrice;
									$result = $minPrice;
								}
							}
							$arItem['MIN_PRICE']['VALUE'] = $result;
							$arItem['MIN_PRICE']['DISCOUNT_VALUE'] = $result;
							$arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] = CCurrencyLang::CurrencyFormat($result, $arPrice['CURRENCY'], true);
							$arItem['MIN_PRICE']['CURRENCY'] = $arPrice['CURRENCY'];
							$arItem['MIN_PRICE']['CAN_BUY'] = 'Y';
						}
						$arItem['PRICE_MATRIX']['MATRIX'][$key][$key2]['PRINT_PRICE'] =  CCurrencyLang::CurrencyFormat($arPrice['PRICE'], $arPrice['CURRENCY'], true);
					}
					if($arPrice['DISCOUNT_PRICE'])
						$arItem['PRICE_MATRIX']['MATRIX'][$key][$key2]['PRINT_DISCOUNT_PRICE'] =  CCurrencyLang::CurrencyFormat($arPrice['DISCOUNT_PRICE'], $arPrice['CURRENCY'], true);
				}
			}
		}
		return $arItem;
	}

	public static function showPriceMatrix($arItem = array(), $arParams, $strMeasure = '', $arAddToBasketData = array()){
		$html = '';
		if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX'])
		{
			ob_start();?>
				<div class="price_matrix_block">
					<?
					$sDiscountPrices = \Bitrix\Main\Config\Option::get(OPTIMUS_MODULE_ID, 'DISCOUNT_PRICE');
					$emptyPriceText = \Bitrix\Main\Config\Option::get(OPTIMUS_MODULE_ID, "EXPRESSION_FOR_EMPTY_PRICE", "", SITE_ID);
					$arDiscountPrices = array();
					if($sDiscountPrices)
						$arDiscountPrices = array_flip(explode(',', $sDiscountPrices));

					$iCountPriceGroup = count($arItem['PRICE_MATRIX']['COLS']);
					$bPriceRows = (count($arItem['PRICE_MATRIX']['ROWS']) > 1);?>
					<?foreach($arItem['PRICE_MATRIX']['COLS'] as $arPriceGroup):?>
						<?if($iCountPriceGroup > 1):?>
							<div class="price_group <?=$arPriceGroup['XML_ID']?>"><div class="price_name"><?=$arPriceGroup["NAME_LANG"];?></div>
						<?endif;?>
						<div class="price_matrix_wrapper <?=($arDiscountPrices ? (isset($arDiscountPrices[$arPriceGroup['ID']]) ? 'strike_block' : '') : '');?>">
							<?$iCountPriceInterval = count($arItem['PRICE_MATRIX']['MATRIX'][$arPriceGroup['ID']]);?>
							<?foreach($arItem['PRICE_MATRIX']['MATRIX'][$arPriceGroup['ID']] as $key => $arPrice):?>
								<?if($iCountPriceInterval > 1):?>
									<div class="price_wrapper_block">
										<div class="price_interval">
											<?
											$quantity_from = $arItem['PRICE_MATRIX']['ROWS'][$key]['QUANTITY_FROM'];
											$quantity_to = $arItem['PRICE_MATRIX']['ROWS'][$key]['QUANTITY_TO'];
											$text = ($quantity_to ? ($quantity_from ? $quantity_from.'-'.$quantity_to : '<'.$quantity_to ) : '>'.$quantity_from );
											?>
											<?=$text?><?if(($arParams["SHOW_MEASURE"]=="Y") && $strMeasure):?> <?=$strMeasure?><?endif;?>
										</div>
									<?endif;?>
									<?if($arPrice["PRICE"] > $arPrice["DISCOUNT_PRICE"]){?>
										<div class="price" data-currency="<?=$arPrice["CURRENCY"];?>" data-value="<?=$arPrice["DISCOUNT_PRICE"];?>">
											<?if(strlen($arPrice["DISCOUNT_PRICE"])):?>
												<span class="values_wrapper"><?=\Aspro\Functions\CAsproOptimusItem::getCurrentPrice("DISCOUNT_PRICE", $arPrice);?></span><?if(($arParams["SHOW_MEASURE"]=="Y") && $strMeasure && $arPrice["DISCOUNT_PRICE"]):?><span class="price_measure">/<?=$strMeasure?></span><?endif;?>
											<?endif;?>
										</div>
										<?if($arParams["SHOW_OLD_PRICE"]=="Y"):?>
											<div class="price discount" data-currency="<?=$arPrice["CURRENCY"];?>" data-value="<?=$arPrice["PRICE"];?>">
												<span class="values_wrapper"><?=\Aspro\Functions\CAsproOptimusItem::getCurrentPrice("PRICE", $arPrice);?></span>
											</div>
										<?endif;?>
									<?}else{?>
										<div class="price" data-currency="<?=$arPrice["CURRENCY"];?>" data-value="<?=$arPrice["DISCOUNT_PRICE"];?>">
											<span><span class="values_wrapper"><?=\Aspro\Functions\CAsproOptimusItem::getCurrentPrice("DISCOUNT_PRICE", $arPrice);?></span><?if(($arParams["SHOW_MEASURE"]=="Y") && $strMeasure && $arPrice["PRICE"]):?><span class="price_measure">/<?=$strMeasure?></span><?endif;?></span>
											<?if(!$arPrice["PRICE"] && strlen($emptyPriceText)):?>
												<span class="empty_price_text"><?=$emptyPriceText;?></span>
											<?endif;?>
										</div>
									<?}?>
								<?if($iCountPriceInterval > 1):?>
									</div>
								<?else:
									if($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y' && $arPrice["PRICE"] > $arPrice["DISCOUNT_PRICE"]):?>
										<?$ratio = (!$bPriceRows ? $arAddToBasketData["MIN_QUANTITY_BUY"] : 1);
										$diff = $arPrice["PRICE"] - $arPrice["DISCOUNT_PRICE"];
										$percent=round(($diff/$arPrice["PRICE"])*100, 0);?>
										<div class="sale_block">
											<div class="sale_wrapper">
												<?if($percent && $percent<100):?>
													<div class="value">-<span><?=$percent;?></span>%</div>
												<?endif;?>
												<div class="text"><span class="title"><?=GetMessage("CATALOG_ECONOMY");?></span> <span class="values_wrapper" data-currency="<?=$arPrice["CURRENCY"];?>" data-value="<?=(($arPrice["PRICE"] - $arPrice["DISCOUNT_PRICE"])*$ratio);?>"><?=CCurrencyLang::CurrencyFormat($diff, $arPrice['CURRENCY'])?></span></div>
											</div>
										</div>
									<?endif;?>
								<?endif;?>
							<?endforeach;?>
						</div>
						<?if($iCountPriceGroup > 1):?>
							</div>
						<?endif;?>
					<?endforeach;?>
				</div>
			<?$html = ob_get_contents();
			ob_end_clean();

			foreach(GetModuleEvents(OPTIMUS_MODULE_ID, 'OnAsproShowPriceMatrix', true) as $arEvent) // event for manipulation price matrix
				ExecuteModuleEventEx($arEvent, array($arItem, $arParams, $strMeasure, $arAddToBasketData, &$html));
		}
		return $html;
	}

	public static function showPriceRangeTop($arItem, $arParams, $mess = ''){
		$html = '';
		if($arItem)
		{
			if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX'])
			{
				ob_start();
					$rangSelected = $arItem['ITEM_QUANTITY_RANGE_SELECTED'];
					$priceSelected = $arItem['ITEM_PRICE_SELECTED'];
					if(isset($arItem['FIX_PRICE_MATRIX']) && $arItem['FIX_PRICE_MATRIX'])
					{
						$rangSelected = $arItem['FIX_PRICE_MATRIX']['RANGE_SELECT'];
						$priceSelected = $arItem['FIX_PRICE_MATRIX']['PRICE_SELECT'];
					}
					?>
					<div class="with_matrix">
						<div class="price">
							<div class="price_value_block"><?=\Aspro\Functions\CAsproOptimusItem::getCurrentPrice("PRICE", $arItem['ITEM_PRICES'][$priceSelected]);?></div><?if (($arParams['SHOW_MEASURE'] == 'Y') && $arItem['CATALOG_MEASURE_NAME'] && $arItem['ITEM_PRICES'][$priceSelected]["PRICE"]):?><span class="price_measure">/<?=$arItem['CATALOG_MEASURE_NAME']?></span><?endif;?>
						</div>
						<?if($arParams['SHOW_OLD_PRICE'] == 'Y' && $arItem['ITEM_PRICES'][$priceSelected]['BASE_PRICE'] > $arItem['ITEM_PRICES'][$priceSelected]['PRICE']):?>
							<div class="price discount"><?=\Aspro\Functions\CAsproOptimusItem::getCurrentPrice("BASE_PRICE", $arItem['ITEM_PRICES'][$priceSelected]);?></div>
						<?endif;?>
						<?if($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y' && $arItem['ITEM_PRICES'][$priceSelected]['DISCOUNT']):?>
							<div class="sale_block matrix">
								<div class="sale_wrapper">
									<?if($arItem['ITEM_PRICES'][$priceSelected]['PERCENT'] && $arItem['ITEM_PRICES'][$priceSelected]['PERCENT'] < 100):?>
										<div class="value">-<span><?=$arItem['ITEM_PRICES'][$priceSelected]['PERCENT'];?></span>%</div>
									<?endif;?>
									<div class="text">
									<span class="title"><?=$mess;?></span>
									<span class="values_wrapper"><?=\Aspro\Functions\CAsproOptimusItem::getCurrentPrice("DISCOUNT", $arItem['ITEM_PRICES'][$priceSelected]);?></span></div>
									<div class="clearfix"></div>
								</div>
							</div>
						<?endif;?>
					</div>
				<?$html = ob_get_contents();
				ob_end_clean();
				foreach(GetModuleEvents(OPTIMUS_MODULE_ID, 'OnAsproShowPriceRangeTop', true) as $arEvent) // event for manipulation price matrix top
					ExecuteModuleEventEx($arEvent, array($arItem, $arParams, $mess, &$html));
			}
		}
		return $html;
	}

	public static function checkPriceRangeExt($arResult = array()){
		$arData = array();
		if($arResult)
		{
			if(isset($arResult['ITEM_PRICE_MODE']) && $arResult['ITEM_PRICE_MODE'] == 'Q')
			{
				$arRang = array();
				$bFound = false;
				$quantity = (int)$arResult['CATALOG_MEASURE_RATIO'];

				$rangSelected = $arResult['ITEM_QUANTITY_RANGE_SELECTED'];
				$priceSelected = $arResult['ITEM_PRICE_SELECTED'];

				foreach($arResult['ITEM_QUANTITY_RANGES'] as $key => $arItemRang)
				{
					$arRang = $arItemRang;
					if($quantity >= (int)$arRang['SORT_FROM'] && (strpos($arRang['SORT_TO'], 'INF') !== false || $quantity <= (int)$arRang['SORT_TO']))
					{
						$bFound = true;
						$rangSelected = $arRang['HASH'];
						break;
					}
				}
				if(!$bFound && ($arRang = self::getMinPriceRangeExt($arResult['ITEM_QUANTITY_RANGES'])))
				{
					$rangSelected = $arRang['HASH'];
				}

				foreach($arResult['ITEM_PRICES'] as $key => $arPrice)
				{
					if ($arPrice['QUANTITY_HASH'] == $rangSelected)
					{
						$priceSelected = $key;
						break;
					}
				}

				$arData = array(
					'RANGE_SELECT' => $rangSelected,
					'PRICE_SELECT' => $priceSelected,
				);
			}
		}
		return $arData;
	}

	public static function getMinPriceRangeExt($arPriceRange = array()){
		$arRang = array();
		if($arPriceRange)
		{
			foreach($arPriceRange as $key => $arItemRang)
			{
				if(!$arRang || ((int)$arItemRang['SORT_FROM'] < (int)$arRang['SORT_FROM']))
				{
					$arRang = $arItemRang;
				}
			}
		}
		return $arRang;
	}

	public static function getSectionChilds($PSID, &$arSections, &$arSectionsByParentSectionID, &$arItemsBySectionID, &$aMenuLinksExt){
		if($arSections && is_array($arSections)){
			foreach($arSections as $arSection){
				if($arSection['IBLOCK_SECTION_ID'] == $PSID){
					$arItem = array($arSection['NAME'], $arSection['SECTION_PAGE_URL'], array(), array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => $arSection['DEPTH_LEVEL']));
					$arItem[3]['IS_PARENT'] = (isset($arItemsBySectionID[$arSection['ID']]) || isset($arSectionsByParentSectionID[$arSection['ID']]) ? 1 : 0);
					if($arSection["PICTURE"])
						$arItem[3]["PICTURE"]=$arSection["PICTURE"];
					$aMenuLinksExt[] = $arItem;
					if($arItem[3]['IS_PARENT']){
						// subsections
						self::getSectionChilds($arSection['ID'], $arSections, $arSectionsByParentSectionID, $arItemsBySectionID, $aMenuLinksExt);
						// section elements
						if($arItemsBySectionID[$arSection['ID']] && is_array($arItemsBySectionID[$arSection['ID']])){
							foreach($arItemsBySectionID[$arSection['ID']] as $arItem){
								if(is_array($arItem['DETAIL_PAGE_URL'])){
									if(isset($arItem['CANONICAL_PAGE_URL'])){
										$arItem['DETAIL_PAGE_URL'] = $arItem['CANONICAL_PAGE_URL'];
									}
									else{
										$arItem['DETAIL_PAGE_URL'] = $arItem['DETAIL_PAGE_URL'][key($arItem['DETAIL_PAGE_URL'])];
									}
								}
								$aMenuLinksExt[] = array($arItem['NAME'], $arItem['DETAIL_PAGE_URL'], array(), array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => ($arSection['DEPTH_LEVEL'] + 1), 'IS_ITEM' => 1));
							}
						}
					}
				}
			}
		}
	}

	public static function GetDirMenuParametrs($dir){
		if(strlen($dir)){
			$file = str_replace('//', '/', $dir.'/.section.php');
			if(file_exists($file)){
				@include($file);
				return $arDirProperties;
			}
		}

		return false;
	}


	public static  function cmpByID($a, $b){
		return ($b['ID'] - $a['ID']);
	}

	public static  function cmpBySort($a, $b){
		return ($a['SORT'] - $b['SORT']);
	}

	public static function cmpByIDFilter($a, $b){
		global $IDFilter;
		$ak = array_search($a['ID'], $IDFilter);
		$bk = array_search($b['ID'], $IDFilter);
		if($ak === $bk){
			return 0;
		}
		else{
			return ($ak > $bk ? 1 : -1);
		}
	}

	public static function getChainNeighbors($curSectionID, $chainPath){
		static $arSections, $arSectionsIDs, $arSubSections;
		$arResult = array();

		if($arSections === NULL){
			$arSections = $arSectionsIDs = $arSubSections = array();
			$IBLOCK_ID = false;
			$nav = CIBlockSection::GetNavChain(false, $curSectionID, array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "SECTION_PAGE_URL"));
			while($ar = $nav->GetNext()){
				$arSections[] = $ar;
				$arSectionsIDs[] = ($ar["IBLOCK_SECTION_ID"] ? $ar["IBLOCK_SECTION_ID"] : 0);
				$IBLOCK_ID = $ar["IBLOCK_ID"];
			}

			if($arSectionsIDs){
				$resSubSection = CIBlockSection::GetList(array('SORT' => 'ASC'), array("ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "IBLOCK_ID" => $IBLOCK_ID, "SECTION_ID" => $arSectionsIDs), false, array("ID", "NAME", "IBLOCK_SECTION_ID", "SECTION_PAGE_URL"));
				while($arSubSection = $resSubSection->GetNext()){
					$arSubSection["IBLOCK_SECTION_ID"] = ($arSubSection["IBLOCK_SECTION_ID"] ? $arSubSection["IBLOCK_SECTION_ID"] : 0);
					$arSubSections[$arSubSection["IBLOCK_SECTION_ID"]][] = $arSubSection;
				}

				if(in_array(0, $arSectionsIDs)){
					$resSubSection = CIBlockSection::GetList(array('SORT' => 'ASC'), array("ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "IBLOCK_ID" => $IBLOCK_ID, "SECTION_ID" => false), false, array("ID", "NAME", "IBLOCK_SECTION_ID", "SECTION_PAGE_URL"));
					while($arSubSection = $resSubSection->GetNext()){
						$arSubSections[$arSubSection["IBLOCK_SECTION_ID"]][] = $arSubSection;
					}
				}
			}
		}

		if($arSections && strlen($chainPath)){
			foreach($arSections as $arSection){
				if($arSection["SECTION_PAGE_URL"] == $chainPath){
					if($arSubSections[$arSection["IBLOCK_SECTION_ID"]]){
						foreach($arSubSections[$arSection["IBLOCK_SECTION_ID"]] as $arSubSection){
							if($curSectionID !== $arSubSection["ID"]){
								$arResult[] = array("NAME" => $arSubSection["NAME"], "LINK" => $arSubSection["SECTION_PAGE_URL"]);
							}
						}
					}
					break;
				}
			}
		}

		return $arResult;
	}

	public static function drawFormField($FIELD_SID, $arQuestion){
		?>
		<?$arQuestion["HTML_CODE"] = str_replace('name=', 'data-sid="'.$FIELD_SID.'" name=', $arQuestion["HTML_CODE"]);?>
		<?$arQuestion["HTML_CODE"] = str_replace('left', '', $arQuestion["HTML_CODE"]);?>
		<?$arQuestion["HTML_CODE"] = str_replace('size="0"', '', $arQuestion["HTML_CODE"]);?>
		<?if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):?>
			<?=$arQuestion["HTML_CODE"];?>
		<?else:?>
			<div class="form-control">
				<label><span><?=$arQuestion["CAPTION"]?><?=($arQuestion["REQUIRED"] == "Y" ? '&nbsp;<span class="star">*</span>' : '')?></span></label>
				<?
				if(is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS']))
					$arQuestion["HTML_CODE"] = str_replace('class="', 'class="error ', $arQuestion["HTML_CODE"]);

				if($arQuestion["REQUIRED"] == "Y")
					$arQuestion["HTML_CODE"] = str_replace('name=', 'required name=', $arQuestion["HTML_CODE"]);

				if($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "email")
					$arQuestion["HTML_CODE"] = str_replace('type="text"', 'type="email" placeholder="mail@domen.com"', $arQuestion["HTML_CODE"]);

				if((strpos($arQuestion["HTML_CODE"], "phone") !== false) || (strpos(strToLower($FIELD_SID), "phone") !== false))
					$arQuestion["HTML_CODE"] = str_replace('type="text"', 'type="tel"', $arQuestion["HTML_CODE"]);
				?>
				<?=$arQuestion["HTML_CODE"]?>
			</div>
		<?endif;?>
		<?
	}

	public static function GetValidFormIDForSite(&$form_id){
		if(!is_numeric($form_id) && !in_array($form_id, array('auth', 'one_click_buy'))){
			CModule::IncludeModule('form');
			$rsForm = CForm::GetList($by = "id", $order = "asc", array("ACTIVE" => "Y", "SID" => $form_id, "SITE" => array(SITE_ID)), $is_filtered);
			if($item = $rsForm->Fetch()){
				$form_id = $item["ID"];
			}
		}

		return $form_id;
	}

	public static function CheckTypeCount($totalCount){
		if(is_float($totalCount)){
			return floatval($totalCount);
		}
		else{
			return intval($totalCount);
		}
	}

	public static function GetTotalCount(&$arItem){
		$totalCount = 0;
		if($arItem["OFFERS"])
		{
			foreach($arItem["OFFERS"] as $arOffer)
				$totalCount += $arOffer["CATALOG_QUANTITY"];

		}
		elseif($arItem["PRICES"])
		{
			$totalCount = ( $arItem["~CATALOG_QUANTITY"] != $arItem["CATALOG_QUANTITY"] ? $arItem["~CATALOG_QUANTITY"] : $arItem["CATALOG_QUANTITY"] );
		}
		else
		{
			$totalCount = ( $arItem["~CATALOG_QUANTITY"] != $arItem["CATALOG_QUANTITY"] ? $arItem["~CATALOG_QUANTITY"] : $arItem["CATALOG_QUANTITY"] );
		}

		foreach(GetModuleEvents(OPTIMUS_MODULE_ID, 'OnAsproGetTotalQuantity', true) as $arEvent) // event for manipulation total quantity
			ExecuteModuleEventEx($arEvent, array($arItem, &$totalCount));

		return self::CheckTypeCount($totalCount);
	}

	public static function GetQuantityArray($totalCount, $arItemIDs = array(), $useStoreClick="N"){
		static $arQuantityOptions, $arQuantityRights;
		if($arQuantityOptions === NULL){
			$arQuantityOptions = array(
				"USE_WORD_EXPRESSION" => COption::GetOptionString("aspro.optimus", "USE_WORD_EXPRESSION", "Y", SITE_ID),
				"MAX_AMOUNT" => COption::GetOptionString("aspro.optimus", "MAX_AMOUNT", "10", SITE_ID),
				"MIN_AMOUNT" => COption::GetOptionString("aspro.optimus", "MIN_AMOUNT", "2", SITE_ID),
				"EXPRESSION_FOR_MIN" => COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_MIN", GetMessage("EXPRESSION_FOR_MIN_DEFAULT"), SITE_ID),
				"EXPRESSION_FOR_MID" => COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_MID", GetMessage("EXPRESSION_FOR_MID_DEFAULT"), SITE_ID),
				"EXPRESSION_FOR_MAX" => COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_MAX", GetMessage("EXPRESSION_FOR_MAX_DEFAULT"), SITE_ID),
				"EXPRESSION_FOR_EXISTS" => COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_EXISTS", GetMessage("EXPRESSION_FOR_EXISTS_DEFAULT"), SITE_ID),
				"EXPRESSION_FOR_NOTEXISTS" => COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_NOTEXISTS", GetMessage("EXPRESSION_FOR_NOTEXISTS_DEFAULT"), SITE_ID),
				"SHOW_QUANTITY_FOR_GROUPS" => (($tmp = COption::GetOptionString("aspro.optimus", "SHOW_QUANTITY_FOR_GROUPS", "", SITE_ID)) ? explode(",", $tmp) : array()),
				"SHOW_QUANTITY_COUNT_FOR_GROUPS" => (($tmp = COption::GetOptionString("aspro.optimus", "SHOW_QUANTITY_COUNT_FOR_GROUPS", "", SITE_ID)) ? explode(",", $tmp) : array()),
			);
			$arQuantityRights = array(
				"SHOW_QUANTITY" => false,
				"SHOW_QUANTITY_COUNT" => false,
			);

			global $USER;
			$res = CUser::GetUserGroupList($USER->GetID());
			while ($arGroup = $res->Fetch()){
				if(in_array($arGroup["GROUP_ID"], $arQuantityOptions["SHOW_QUANTITY_FOR_GROUPS"])){
					$arQuantityRights["SHOW_QUANTITY"] = true;
				}
				if(in_array($arGroup["GROUP_ID"], $arQuantityOptions["SHOW_QUANTITY_COUNT_FOR_GROUPS"])){
					$arQuantityRights["SHOW_QUANTITY_COUNT"] = true;
				}
			}
		}

		$indicators = 0;
		$totalAmount = $totalText = $totalHTML = $totalHTMLs = '';

		if($arQuantityRights["SHOW_QUANTITY"]){
			if($totalCount > $arQuantityOptions["MAX_AMOUNT"]){
				$indicators = 3;
				$totalAmount = $arQuantityOptions["EXPRESSION_FOR_MAX"];
			}
			elseif($totalCount < $arQuantityOptions["MIN_AMOUNT"] && $totalCount > 0){
				$indicators = 1;
				$totalAmount = $arQuantityOptions["EXPRESSION_FOR_MIN"];
			}
			else{
				$indicators = 2;
				$totalAmount = $arQuantityOptions["EXPRESSION_FOR_MID"];
			}

			if($totalCount > 0){
				if($arQuantityRights["SHOW_QUANTITY_COUNT"]){
					$totalHTML = '<span class="first'.($indicators >= 1 ? ' r' : '').'"></span><span class="'.($indicators >= 2 ? ' r' : '').'"></span><span class="last'.($indicators >= 3 ? ' r' : '').'"></span>';
				}
				else{
					$totalHTML = '<span class="first r"></span>';
				}
			}
			else{
				$totalHTML = '<span class="null"></span>';
			}

			//$totalText = ($totalCount > 0 ? $arQuantityOptions["EXPRESSION_FOR_EXISTS"] : $arQuantityOptions["EXPRESSION_FOR_NOTEXISTS"]);
			if($totalCount > 0){
				$totalText = $arQuantityOptions["EXPRESSION_FOR_EXISTS"];
			}else{
				if($useStoreClick=="Y"){
					$totalText = "<span class='store_view'>".$arQuantityOptions["EXPRESSION_FOR_NOTEXISTS"]."</span>";
				}else{
					$totalText = $arQuantityOptions["EXPRESSION_FOR_NOTEXISTS"];
				}
			}

			if($arQuantityRights["SHOW_QUANTITY_COUNT"] && $totalCount > 0){
				if($arQuantityOptions["USE_WORD_EXPRESSION"] == "Y"){
					if(strlen($totalAmount)){
						if($useStoreClick=="Y"){
							$totalText = "<span class='store_view'>".$totalAmount."</span>";
						}else{
							$totalText = $totalAmount;
						}
					}
				}
				else{
					if($useStoreClick=="Y"){
						$totalText .= (strlen($totalText) ? " <span class='store_view'>(".$totalCount.")</span>" : "<span class='store_view'>".$totalCount."</span>");
					}else{
						$totalText .= (strlen($totalText) ? " (".$totalCount.")" : $totalCount);
					}
				}
			}
			$totalHTMLs ='<div class="item-stock" '.($arItemIDs["STORE_QUANTITY"] ? "id=".$arItemIDs["STORE_QUANTITY"] : "").'>';
			$totalHTMLs .= '<span class="icon '.$arClass[1].($totalCount > 0 ? 'stock' : ' order').'"></span><span class="value">'.$totalText.'</span>';
			$totalHTMLs .='</div>';
		}

		$arOptions = array("OPTIONS" => $arQuantityOptions, "RIGHTS" => $arQuantityRights, "TEXT" => $totalText, "HTML" => $totalHTMLs);

		foreach(GetModuleEvents(OPTIMUS_MODULE_ID, 'OnAsproGetTotalQuantityBlock', true) as $arEvent) // event for manipulation store quantity block
			ExecuteModuleEventEx($arEvent, array($totalCount, &$arOptions));

		return $arOptions;
	}

	public static function GetAvailiableStore($totalCount = 0, $arItemIDs=array(), $detail=false){
		static $arQuantityOptions;
		if($arQuantityOptions === NULL){
			$arQuantityOptions = array(
				"EXPRESSION_FOR_EXISTS" => COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_EXISTS", GetMessage("EXPRESSION_FOR_EXISTS_DEFAULT"), SITE_ID),
				"EXPRESSION_FOR_NOTEXISTS" => COption::GetOptionString("aspro.optimus", "EXPRESSION_FOR_NOTEXISTS", GetMessage("EXPRESSION_FOR_NOTEXISTS_DEFAULT"), SITE_ID),
			);
		}
		$totalHTML='<div class="item-stock" '.($arItemIDs["STORE_QUANTITY"] ? "id=".$arItemIDs["STORE_QUANTITY"] : "").'>';
		if($totalCount){
			$totalHTML.='<span class="icon stock"></span><span>'.$arQuantityOptions["EXPRESSION_FOR_EXISTS"];
			if($detail=="Y"){
				$totalHTML.='<span class="store_link"> ('.$totalCount.')</span>';
			}else{
				$totalHTML.=' ('.$totalCount.')';
			}
			$totalHTML.='</span>';
		}else{
			$totalHTML.='<span class="icon order"></span><span>'.$arQuantityOptions["EXPRESSION_FOR_NOTEXISTS"].'</span>';
		}
		$totalHTML.='</div>';

		return array( "OPTIONS" => $arQuantityOptions, "HTML" => $totalHTML );
	}

	public static function GetPropertyViewType($IBLOCK_ID){
		global $DB;
		$IBLOCK_ID = intval($IBLOCK_ID);
		$SECTION_ID=64;
		// $IBLOCK_ID = 15;
        $result = array();
		/*$rs = $DB->Query($s = "
			SELECT
				B.SECTION_PROPERTY,
				BP.ID PROPERTY_ID,
				BSP.SECTION_ID LINK_ID,
				BSP.SMART_FILTER,
				BSP.DISPLAY_TYPE,
				BSP.DISPLAY_EXPANDED,
				BSP.FILTER_HINT,
				BP.SORT,
				BP.PROPERTY_TYPE,
				BP.USER_TYPE
			FROM
				b_iblock B
				INNER JOIN b_iblock_property BP ON BP.IBLOCK_ID = B.ID
				INNER JOIN b_iblock_section_property BSP ON  BSP.PROPERTY_ID = BP.ID
			WHERE
				B.ID = ".$IBLOCK_ID."
			ORDER BY
				BP.SORT ASC, BP.ID ASC
		");*/
		$rs = $DB->Query($s = "
			SELECT
                    B.SECTION_PROPERTY,
                    BP.ID PROPERTY_ID,
                    BSP.SECTION_ID LINK_ID,
                    BSP.SMART_FILTER,
                    BSP.DISPLAY_TYPE,
                    BSP.DISPLAY_EXPANDED,
                    BSP.FILTER_HINT,
                    BP.SORT,
                    BS.LEFT_MARGIN,
                    BS.NAME LINK_TITLE,
                    BP.PROPERTY_TYPE,
                    BP.USER_TYPE
                FROM
                    b_iblock B
                    INNER JOIN b_iblock_property BP ON BP.IBLOCK_ID = B.ID
                    INNER JOIN b_iblock_section M ON M.ID = ".$SECTION_ID."
                    INNER JOIN b_iblock_section BS ON BS.IBLOCK_ID = M.IBLOCK_ID
                        AND M.LEFT_MARGIN >= BS.LEFT_MARGIN
                        AND M.RIGHT_MARGIN <= BS.RIGHT_MARGIN
                    INNER JOIN b_iblock_section_property BSP ON BSP.IBLOCK_ID = BS.IBLOCK_ID AND BSP.SECTION_ID = BS.ID AND BSP.PROPERTY_ID = BP.ID
                WHERE
                    B.ID = ".$IBLOCK_ID."
                ORDER BY
                    BP.SORT ASC, BP.ID ASC, BS.LEFT_MARGIN DESC
		");
		while ($ar = $rs->Fetch()){
			$result[$ar["PROPERTY_ID"]] = array(
				"PROPERTY_ID" => $ar["PROPERTY_ID"],
				"SMART_FILTER" => $ar["SMART_FILTER"],
				"DISPLAY_TYPE" => $ar["DISPLAY_TYPE"],
				"DISPLAY_EXPANDED" => $ar["DISPLAY_EXPANDED"],
				"FILTER_HINT" => $ar["FILTER_HINT"],
				"INHERITED_FROM" => $ar["LINK_ID"],
				"SORT" => $ar["SORT"],
				"PROPERTY_TYPE" => $ar["PROPERTY_TYPE"],
			);
		}
		return $result;
	}

	public static function GetSKUPropsArray(&$arSkuProps, $iblock_id=0, $type_view="list", $hide_title_props="N", $group_iblock_id="N", $arItem = array(), $offerShowPreviewPictureProps = array()){
		$arSkuTemplate = array();
		$class_title=($hide_title_props=="Y" ? "hide_class" : "show_class");
		$class_title.=' bx_item_section_name';

		if($iblock_id){
			$arPropsSku=CIBlockSectionPropertyLink::GetArray($iblock_id);
			if($arPropsSku){
				foreach ($arSkuProps as $key=>$arProp){
					if($arPropsSku[$arProp["ID"]]){
						$arSkuProps[$key]["DISPLAY_TYPE"]=$arPropsSku[$arProp["ID"]]["DISPLAY_TYPE"];
					}
				}
			}
		}

		$bTextViewProp = (Option::get(self::moduleID, "VIEW_TYPE_HIGHLOAD_PROP", "N", SITE_ID) == "Y");

		$arCurrentOffer = $arItem['OFFERS'][$arItem['OFFERS_SELECTED']];
		$j = 0;
		$arFilter = $arShowValues = array();

		/*get correct values*/
		foreach ($arSkuProps as $key => $arProp){
			$strName = 'PROP_'.$arProp['ID'];
			$arShowValues = self::GetRowValues($arFilter, $strName, $arItem);

			if(in_array($arCurrentOffer['TREE'][$strName], $arShowValues))
			{
				$arFilter[$strName] = $arCurrentOffer['TREE'][$strName];
			}
			else
			{
				$arFilter[$strName] = $arShowValues[0];
			}

			$arCanBuyValues = $tmpFilter = array();
			$tmpFilter = $arFilter;
			foreach($arShowValues as $value)
			{
				$tmpFilter[$strName] = $value;
				if(self::GetCanBuy($tmpFilter, $arItem))
				{
					$arCanBuyValues[] = $value;
				}
			}

			$arSkuProps[$key] = self::UpdateRow($arFilter[$strName], $arShowValues, $arCanBuyValues, $arProp, $type_view);

		}
		/**/
		?>

		<?
		if($group_iblock_id=="Y"){
			foreach ($arSkuProps as $iblockId => $skuProps){
				$arSkuTemplate[$iblockId] = array();
				foreach ($skuProps as $key=>&$arProp){
					$templateRow = '';
					$class_title.= (($arProp["HINT"] && $arProp["SHOW_HINTS"] == "Y") ? ' whint char_name' : '');
					$hint_block = (($arProp["HINT"] && $arProp["SHOW_HINTS"]=="Y") ? '<div class="hint"><span class="icon"><i>?</i></span><div class="tooltip">'.$arProp["HINT"].'</div></div>' : '');

					if($arProp['VALUES'])
					{
						foreach($arProp['VALUES'] as $propKey => $arOneValue)
						{
							$arProp['NAME'] = htmlspecialcharsex($arProp['NAME']);

							if($arOneValue['CLASS'] && strpos($arOneValue['CLASS'], 'active') !== false)
							{
								$arProp['TITLE'] = $arProp['NAME'].': '.$arOneValue['NAME'];
								$arProp['VALUE'] = $arOneValue['NAME'];
							}
						}
					}

					if(($arProp["DISPLAY_TYPE"]=="P" || $arProp["DISPLAY_TYPE"]=="R" ) && $type_view!= 'block' ){
						$templateRow .= '<div class="bx_item_detail_size" id="#ITEM#_prop_'.$arProp['ID'].'_cont" data-id="'.$arProp['ID'].'">'.
		'<span class="'.$class_title.'">'.$hint_block.($arProp['TITLE'] ? $arProp['TITLE'] : $arProp['NAME']).'</span>'.
		'<div class="bx_size_scroller_container form-control bg"><div class="bx_size"><select id="#ITEM#_prop_'.$arProp['ID'].'_list" class="list_values_wrapper">';
						foreach ($arProp['VALUES'] as $arOneValue){
							//if($arOneValue['ID']>0){
								$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
								$templateRow .= '<option '.$arOneValue['SELECTED'].' '.$arOneValue['DISABLED'].' data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-showtype="select" data-onevalue="'.$arOneValue['ID'].'" ';
								if($arProp["DISPLAY_TYPE"]=="R"){
									$templateRow .= 'data-img_src="'.$arOneValue["PICT"]["SRC"].'" ';
								}
								$templateRow .= 'title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'">';
								$templateRow .= '<span class="cnt">'.$arOneValue['NAME'].'</span>';
								$templateRow .= '</option>';
							//}
						}
						$templateRow .= '</select></div>'.
		'</div></div>';
					}elseif ('TEXT' == $arProp['SHOW_MODE']){
						$templateRow .= '<div class="bx_item_detail_size" id="#ITEM#_prop_'.$arProp['ID'].'_cont" data-id="'.$arProp['ID'].'">'.
		'<span class="'.$class_title.'">'.$hint_block.($arProp['TITLE'] ? $arProp['TITLE'] : $arProp['NAME']).'</span>'.
		'<div class="bx_size_scroller_container"><div class="bx_size"><ul id="#ITEM#_prop_'.$arProp['ID'].'_list" class="list_values_wrapper">';
						foreach ($arProp['VALUES'] as $arOneValue){
							//if($arOneValue['ID']>0){
								$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
								$templateRow .= '<li class="item '.$arOneValue['CLASS'].'" '.$arOneValue['STYLE'].' data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-showtype="li" data-onevalue="'.$arOneValue['ID'].'" title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"><i></i><span class="cnt">'.$arOneValue['NAME'].'</span></li>';
							//}
						}
						$templateRow .= '</ul></div>'.
		'</div></div>';
					}elseif ('PICT' == $arProp['SHOW_MODE']){

						$arCurrentTree = array();
						if($offerShowPreviewPictureProps && is_array($offerShowPreviewPictureProps)){
							if(in_array($arProp['CODE'], $offerShowPreviewPictureProps)){
								if($arCurrentOffer && $arCurrentOffer['TREE']){
									$arCurrentTree = $arCurrentOffer['TREE'];
								}
							}
						}

						$isHasPicture = true;
						foreach($arProp['VALUES'] as &$arOneValue){
							$boolOneSearch = false;
							if($arCurrentTree && $arOneValue['ID'] != 0){
								$arRowTree = $arCurrentTree;
								$arRowTree['PROP_'.$arProp['ID']] = $arOneValue['ID'];

								foreach($arItem['OFFERS'] as &$arOffer){
									$boolOneSearch = true;
									foreach($arRowTree as $rkey => $rval){
										if($rval !== $arOffer['TREE'][$rkey]){
											$boolOneSearch = false;
											break;
										}
									}
									if($boolOneSearch){
										if($arOffer['PREVIEW_PICTURE_FIELD'] && is_array($arOffer['PREVIEW_PICTURE_FIELD']) && $arOffer['PREVIEW_PICTURE_FIELD']['SRC']){
											$arOneValue['NEW_PICT'] = $arOffer['PREVIEW_PICTURE_FIELD'];
										}
										else{
											$boolOneSearch = false;
										}
										break;
									}
								}
								unset($arOffer);
							}

							if(!$boolOneSearch){
								//if($arOneValue['ID']>0){
									if(!isset($arOneValue['PICT']['SRC']) || !$arOneValue['PICT']['SRC'])
									{
										if(!$bTextViewProp)
										{
											$arOneValue['PICT']['SRC'] = SITE_TEMPLATE_PATH.'/images/no_photo_small.png';
											$arOneValue['NO_PHOTO'] = 'Y';
										}
										else
										{
											$isHasPicture = false;
										}
									}
								//}
							}
						}
						unset($arOneValue);
						
						if($isHasPicture)
						{
							$templateRow .= '<div class="bx_item_detail_scu" '.$arProp['STYLE'].' id="#ITEM#_prop_'.$arProp['ID'].'_cont" data-display_type="LI" data-id="'.$arProp['ID'].'">'.
		'<span class="'.$class_title.'">'.$hint_block.'<span>'.htmlspecialcharsex($arProp['NAME']).'</span></span>'.
		'<div class="bx_scu_scroller_container"><div class="bx_scu"><ul id="#ITEM#_prop_'.$arProp['ID'].'_list" class="list_values_wrapper">';
						}
						else
						{
							$templateRow .= '<div class="bx_item_detail_size" '.$arProp['STYLE'].' id="#ITEM#_prop_'.$arProp['ID'].'_cont" data-display_type="LI" data-id="'.$arProp['ID'].'">'.
		'<span class="'.$class_title.'">'.htmlspecialcharsex($arProp['NAME']).'</span>'.
		'<div class="bx_size_scroller_container"><div class="bx_size"><ul id="#ITEM#_prop_'.$arProp['ID'].'_list" class="list_values_wrapper">';
						}

						foreach ($arProp['VALUES'] as $arOneValue){
							//if($arOneValue['ID']>0){
								$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
								if(isset($arOneValue['PICT']['SRC']) && $arOneValue['PICT']['SRC'])
								if($isHasPicture && ($arOneValue['NEW_PICT'] || (isset($arOneValue['PICT']['SRC']) && $arOneValue['PICT']['SRC'])))
								{									
									$str = '<span class="cnt1"><span class="cnt_item'.($arOneValue['NEW_PICT'] ? ' pp' : '').'" style="background-image:url(\''.($arOneValue['NEW_PICT'] ? $arOneValue['NEW_PICT']['SRC'] : $arOneValue['PICT']['SRC']).'\');" data-obgi="url(\''.$arOneValue['PICT']['SRC'].'\')" title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"></span></span>';
									if(isset($arOneValue['NO_PHOTO']) && $arOneValue['NO_PHOTO'] == 'Y')
										$str = '<span class="cnt1 nf"><span class="cnt_item" title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"><span class="bg" style="background-image:url(\''.$arOneValue['PICT']['SRC'].'\');"></span></span></span>';
									$templateRow .= '<li class="item '.$arOneValue['CLASS'].'" '.$arOneValue['STYLE'].' data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-showtype="li" data-onevalue="'.$arOneValue['ID'].'"><i title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"></i>'.$str.'</li>';

								}
								else
								{									
									//$templateRow .= '<li class="item '.$arOneValue['CLASS'].'" '.$arOneValue['STYLE'].' data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-showtype="li" data-onevalue="'.$arOneValue['ID'].'" title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"><i></i><span class="cnt1"><span class="cnt_item" style="background-image:url(\''.SITE_TEMPLATE_PATH.'/images/no_photo_small.png\');" title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"></span></span></li>';
									$templateRow .= '<li class="item '.$arOneValue['CLASS'].'" '.$arOneValue['STYLE'].' data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-showtype="li" data-onevalue="'.$arOneValue['ID'].'" title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"><i></i><span class="cnt">'.$arOneValue['NAME'].'</span></li>';
								}
							//}
						}
						$templateRow .= '</ul></div>'.
		'</div></div>';
					}
					$arSkuTemplate[$iblockId][$arProp['CODE']] = $templateRow;
				}
			}
		}else{
			foreach ($arSkuProps as $key=>&$arProp){
				$templateRow = '';
				$class_title.= (($arProp["HINT"] && $arProp["SHOW_HINTS"] == "Y") ? ' whint char_name' : '');
				$hint_block = (($arProp["HINT"] && $arProp["SHOW_HINTS"]=="Y") ? '<div class="hint"><span class="icon"><i>?</i></span><div class="tooltip">'.$arProp["HINT"].'</div></div>' : '');

			
				if($arProp['VALUES'])
				{
					foreach($arProp['VALUES'] as $propKey => $arOneValue)
					{
						$arProp['NAME'] = htmlspecialcharsex($arProp['NAME']);

						if($arOneValue['CLASS'] && strpos($arOneValue['CLASS'], 'active') !== false)
						{
							$arProp['TITLE'] = $arProp['NAME'].': '.$arOneValue['NAME'];
							$arProp['VALUE'] = $arOneValue['NAME'];
						}
					}
				}

				if(($arProp["DISPLAY_TYPE"]=="P" || $arProp["DISPLAY_TYPE"]=="R" ) && $type_view!= 'block' ){
					$templateRow .= '<div class="bx_item_detail_size" id="#ITEM#_prop_'.$arProp['ID'].'_cont" data-id="'.$arProp['ID'].'">'.
	'<span class="'.$class_title.'">'.$hint_block.($arProp['TITLE'] ? $arProp['TITLE'] : $arProp['NAME']).'</span>'.
	'<div class="bx_size_scroller_container form-control bg"><div class="bx_size"><select id="#ITEM#_prop_'.$arProp['ID'].'_list" class="list_values_wrapper">';
					foreach ($arProp['VALUES'] as $arOneValue){
						//if($arOneValue['ID']>0){
							$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
							$templateRow .= '<option '.$arOneValue['SELECTED'].' '.$arOneValue['DISABLED'].' data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-showtype="select" data-onevalue="'.$arOneValue['ID'].'" ';
							if($arProp["DISPLAY_TYPE"]=="R"){
								$templateRow .= 'data-img_src="'.$arOneValue["PICT"]["SRC"].'" ';
							}
							$templateRow .= 'title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'">';
							$templateRow .= '<span class="cnt">'.$arOneValue['NAME'].'</span>';
							$templateRow .= '</option>';
						//}
					}
					$templateRow .= '</select></div>'.
	'</div></div>';
				}elseif ('TEXT' == $arProp['SHOW_MODE']){
					$templateRow .= '<div class="bx_item_detail_size" id="#ITEM#_prop_'.$arProp['ID'].'_cont" data-id="'.$arProp['ID'].'">'.
	'<span class="'.$class_title.'">'.$hint_block.($arProp['TITLE'] ? $arProp['TITLE'] : $arProp['NAME']).'</span>'.
	'<div class="bx_size_scroller_container"><div class="bx_size"><ul id="#ITEM#_prop_'.$arProp['ID'].'_list" class="list_values_wrapper">';
					foreach ($arProp['VALUES'] as $arOneValue){
						//if($arOneValue['ID']>0){
							$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
							$templateRow .= '<li class="item '.$arOneValue['CLASS'].'" '.$arOneValue['STYLE'].' data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-showtype="li" data-onevalue="'.$arOneValue['ID'].'" title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"><i></i><span class="cnt">'.$arOneValue['NAME'].'</span></li>';
						//}
					}
					$templateRow .= '</ul></div>'.
	'</div></div>';
				}elseif ('PICT' == $arProp['SHOW_MODE']){

					$arCurrentTree = array();
					if($offerShowPreviewPictureProps && is_array($offerShowPreviewPictureProps)){
						if(in_array($arProp['CODE'], $offerShowPreviewPictureProps)){
							if($arCurrentOffer && $arCurrentOffer['TREE']){
								$arCurrentTree = $arCurrentOffer['TREE'];
							}
						}
					}

					$isHasPicture = true;
					foreach($arProp['VALUES'] as &$arOneValue){
						$boolOneSearch = false;
						if($arCurrentTree && $arOneValue['ID'] != 0){
							$arRowTree = $arCurrentTree;
							$arRowTree['PROP_'.$arProp['ID']] = $arOneValue['ID'];

							foreach($arItem['OFFERS'] as &$arOffer){
								$boolOneSearch = true;
								foreach($arRowTree as $rkey => $rval){
									if($rval !== $arOffer['TREE'][$rkey]){
										$boolOneSearch = false;
										break;
									}
								}
								if($boolOneSearch){
									if($arOffer['PREVIEW_PICTURE_FIELD'] && is_array($arOffer['PREVIEW_PICTURE_FIELD']) && $arOffer['PREVIEW_PICTURE_FIELD']['SRC']){
										$arOneValue['NEW_PICT'] = $arOffer['PREVIEW_PICTURE_FIELD'];
									}
									else{
										$boolOneSearch = false;
									}
									break;
								}
							}
							unset($arOffer);
						}

						if(!$boolOneSearch){
							//if($arOneValue['ID']>0){
								if(!isset($arOneValue['PICT']['SRC']) || !$arOneValue['PICT']['SRC'])
								{
									if(!$bTextViewProp)
									{
										$arOneValue['PICT']['SRC'] = SITE_TEMPLATE_PATH.'/images/no_photo_small.png';
										$arOneValue['NO_PHOTO'] = 'Y';
									}
									else
									{
										$isHasPicture = false;
									}
								}
							//}
						}

						foreach($arItem['OFFERS'] as &$arOffer)
						{
							if($arRowTree['PROP_'.$arProp['ID']] == $arOffer['TREE']['PROP_'.$arProp['ID']] && !$boolOneSearch)
							{
								if($arOffer['PREVIEW_PICTURE_FIELD'] && is_array($arOffer['PREVIEW_PICTURE_FIELD']) && $arOffer['PREVIEW_PICTURE_FIELD']['SRC'])
									$arOneValue['NEW_PICT'] = $arOffer['PREVIEW_PICTURE_FIELD'];
								break;
							}
						}
					}
					unset($arOneValue);
					
					if($isHasPicture)
					{
						$templateRow .= '<div class="bx_item_detail_scu" '.$arProp['STYLE'].' id="#ITEM#_prop_'.$arProp['ID'].'_cont" data-display_type="LI" data-id="'.$arProp['ID'].'">'.
	'<span class="'.$class_title.'">'.$hint_block.'<span>'.htmlspecialcharsex($arProp['NAME']).'</span></span>'.
	'<div class="bx_scu_scroller_container"><div class="bx_scu"><ul id="#ITEM#_prop_'.$arProp['ID'].'_list" class="list_values_wrapper">';
					}
					else
					{
						$templateRow .= '<div class="bx_item_detail_size" '.$arProp['STYLE'].' id="#ITEM#_prop_'.$arProp['ID'].'_cont" data-display_type="LI" data-id="'.$arProp['ID'].'">'.
	'<span class="'.$class_title.'">'.htmlspecialcharsex($arProp['NAME']).'</span>'.
	'<div class="bx_size_scroller_container"><div class="bx_size"><ul id="#ITEM#_prop_'.$arProp['ID'].'_list" class="list_values_wrapper">';

					}


					foreach ($arProp['VALUES'] as $arOneValue){
						//if($arOneValue['ID']>0){
							$arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
							//if(isset($arOneValue['PICT']['SRC']) && $arOneValue['PICT']['SRC'])
							if($isHasPicture && ($arOneValue['NEW_PICT'] || (isset($arOneValue['PICT']['SRC']) && $arOneValue['PICT']['SRC'])))
							{								
								$str = '<span class="cnt1"><span class="cnt_item'.($arOneValue['NEW_PICT'] ? ' pp' : '').'" style="background-image:url(\''.($arOneValue['NEW_PICT'] ? $arOneValue['NEW_PICT']['SRC'] : $arOneValue['PICT']['SRC']).'\');" data-obgi="url(\''.$arOneValue['PICT']['SRC'].'\')" title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"></span></span>';
								if(isset($arOneValue['NO_PHOTO']) && $arOneValue['NO_PHOTO'] == 'Y')
									$str = '<span class="cnt1 nf"><span class="cnt_item" title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"><span class="bg" style="background-image:url(\''.$arOneValue['PICT']['SRC'].'\');"></span></span></span>';
								$templateRow .= '<li class="item '.$arOneValue['CLASS'].'" '.$arOneValue['STYLE'].' data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-showtype="li" data-onevalue="'.$arOneValue['ID'].'"><i title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"></i>'.$str.'</li>';
							}
							else
							{
								//$templateRow .= '<li class="item '.$arOneValue['CLASS'].'" '.$arOneValue['STYLE'].' data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-showtype="li" data-onevalue="'.$arOneValue['ID'].'" title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"><i></i><span class="cnt1"><span class="cnt_item" style="background-image:url(\''.SITE_TEMPLATE_PATH.'/images/no_photo_small.png\');" title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"></span></span></li>';
								$templateRow .= '<li class="item '.$arOneValue['CLASS'].'" '.$arOneValue['STYLE'].' data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-showtype="li" data-onevalue="'.$arOneValue['ID'].'" title="'.$arProp['NAME'].': '.$arOneValue['NAME'].'"><i></i><span class="cnt">'.$arOneValue['NAME'].'</span></li>';
							}
						//}
					}
					$templateRow .= '</ul></div>'.
	'</div></div>';
				}
				$arSkuTemplate[$arProp['CODE']] = $templateRow;
			}
		}
		unset($templateRow, $arProp);
		return $arSkuTemplate;
	}

	public static function UpdateRow($arFilter, $arShowValues, $arCanBuyValues, $arProp, $type_view){
		$isCurrent = false;
		$showI = 0;

		if($arProp['VALUES']){
			foreach($arProp['VALUES'] as $key => $arValue)
			{
				$value = $arValue['ID'];
				// $isCurrent = ($value === $arFilter && $value != 0);
				$isCurrent = ($value === $arFilter);
				$selectMode = (($arProp["DISPLAY_TYPE"] == "P" || $arProp["DISPLAY_TYPE"] == "R" ) && $type_view != 'block' );

				if(in_array($value, $arCanBuyValues))
				{
					$arProp['VALUES'][$key]['CLASS'] = ($isCurrent ? 'active' : '');
				}
				else
				{
					$arProp['VALUES'][$key]['CLASS'] = ($isCurrent ? 'active missing' : 'missing');
				}
				if($selectMode)
				{
					$arProp['VALUES'][$key]['DISABLED'] = 'disabled';
					$arProp['VALUES'][$key]['SELECTED'] = ($isCurrent ? 'selected' : '');
				}
				else
				{
					$arProp['VALUES'][$key]['STYLE'] = 'style="display: none"';
				}

				if(in_array($value, $arShowValues))
				{
					if($selectMode)
					{
						$arProp['VALUES'][$key]['DISABLED'] = '';
					}
					else
					{
						$arProp['VALUES'][$key]['STYLE'] = '';
					}

					if($value != 0)
						++$showI;
				}
			}
			if(!$showI)
				$arProp['STYLE'] = 'style="display: none"';
			else
				$arProp['STYLE'] = 'style=""';
		}

		return $arProp;
	}

	public static function GetRowValues($arFilter, $index, $arItem){
		$i = 0;
		$arValues = array();
		$boolSearch = false;
		$boolOneSearch = true;


		if(!$arFilter)
		{
			if($arItem['OFFERS']){
				foreach($arItem['OFFERS'] as $arOffer)
				{
					if(!in_array($arOffer['TREE'][$index], $arValues))
					{
						$arValues[] = $arOffer['TREE'][$index];
					}
				}
			}
			$boolSearch = true;
		}
		else
		{	
			if($arItem['OFFERS']){
				foreach($arItem['OFFERS'] as $arOffer)
				{
					$boolOneSearch = true;
					foreach($arFilter as $propName => $filter)
					{
						if ($filter !== $arOffer['TREE'][$propName])
						{
							$boolOneSearch = false;
							break;
						}
					}
					if ($boolOneSearch)
					{
						if(!in_array($arOffer['TREE'][$index], $arValues))
						{
							$arValues[] = $arOffer['TREE'][$index];
						}
						$boolSearch = true;
					}
				}
			}
		}
		return ($boolSearch ? $arValues : [false]);
	}

	public static function GetCanBuy($arFilter, $arItem){
		$i = 0;
		$boolSearch = false;
		$boolOneSearch = true;

		foreach($arItem['OFFERS'] as $arOffer)
		{
			$boolOneSearch = true;
			foreach($arFilter as $propName => $filter)
			{
				if ($filter !== $arOffer['TREE'][$propName])
				{
					$boolOneSearch = false;
					break;
				}
			}

			if($boolOneSearch)
			{
				if($arOffer['CAN_BUY'])
				{
					$boolSearch = true;
					break;
				}
			}
		}
		return $boolSearch;
	}

	public static function GetItemsIDs($arItem, $detail="N"){
		$arAllIDs=array();
		$arAllIDs["strMainID"] = $arItem['strMainID'];
		$arAllIDs["strObName"] = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $arAllIDs["strMainID"]);

		if($detail=="Y"){
			$arAllIDs["ALL_ITEM_IDS"] = array(
				'ID' => $arAllIDs["strMainID"],
				'PICT' => $arAllIDs["strMainID"].'_pict',
				'DISCOUNT_PICT_ID' => $arAllIDs["strMainID"].'_dsc_pict',
				'STICKER_ID' => $arAllIDs["strMainID"].'_sticker',
				'BIG_SLIDER_ID' => $arAllIDs["strMainID"].'_big_slider',
				'BIG_IMG_CONT_ID' => $arAllIDs["strMainID"].'_bigimg_cont',
				'SLIDER_CONT_ID' => $arAllIDs["strMainID"].'_slider_cont',
				'SLIDER_LIST' => $arAllIDs["strMainID"].'_slider_list',
				'SLIDER_LEFT' => $arAllIDs["strMainID"].'_slider_left',
				'SLIDER_RIGHT' => $arAllIDs["strMainID"].'_slider_right',
				'OLD_PRICE' => $arAllIDs["strMainID"].'_old_price',
				'PRICE' => $arAllIDs["strMainID"].'_price',
				'DISCOUNT_PRICE' => $arAllIDs["strMainID"].'_price_discount',
				'SLIDER_CONT_OF_ID' => $arAllIDs["strMainID"].'_slider_cont_',
				'SLIDER_LIST_OF_ID' => $arAllIDs["strMainID"].'_slider_list_',
				'SLIDER_LEFT_OF_ID' => $arAllIDs["strMainID"].'_slider_left_',
				'SLIDER_RIGHT_OF_ID' => $arAllIDs["strMainID"].'_slider_right_',
				'SLIDER_CONT_OFM_ID' => $arAllIDs["strMainID"].'_sliderm_cont_',
				'SLIDER_LIST_OFM_ID' => $arAllIDs["strMainID"].'_sliderm_list_',
				'SLIDER_LEFT_OFM_ID' => $arAllIDs["strMainID"].'_sliderm_left_',
				'SLIDER_RIGHT_OFM_ID' => $arAllIDs["strMainID"].'_sliderm_right_',
				'QUANTITY' => $arAllIDs["strMainID"].'_quantity',
				'QUANTITY_DOWN' => $arAllIDs["strMainID"].'_quant_down',
				'QUANTITY_UP' => $arAllIDs["strMainID"].'_quant_up',
				'QUANTITY_MEASURE' => $arAllIDs["strMainID"].'_quant_measure',
				'QUANTITY_LIMIT' => $arAllIDs["strMainID"].'_quant_limit',
				'BASIS_PRICE' => $arAllIDs["strMainID"].'_basis_price',
				'BUY_LINK' => $arAllIDs["strMainID"].'_buy_link',
				'BASKET_LINK' => $arAllIDs["strMainID"].'_basket_link',
				'ADD_BASKET_LINK' => $arAllIDs["strMainID"].'_add_basket_link',
				'BASKET_ACTIONS' => $arAllIDs["strMainID"].'_basket_actions',
				'NOT_AVAILABLE_MESS' => $arAllIDs["strMainID"].'_not_avail',
				'COMPARE_LINK' => $arAllIDs["strMainID"].'_compare_link',
				'PROP' => $arAllIDs["strMainID"].'_prop_',
				'PROP_DIV' => $arAllIDs["strMainID"].'_skudiv',
				'DISPLAY_PROP_DIV' => $arAllIDs["strMainID"].'_sku_prop',
				'DISPLAY_PROP_ARTICLE_DIV' => $arAllIDs["strMainID"].'_sku_article_prop',
				'OFFER_GROUP' => $arAllIDs["strMainID"].'_set_group_',
				'BASKET_PROP_DIV' => $arAllIDs["strMainID"].'_basket_prop',
				'SUBSCRIBE_DIV' => $arAllIDs["strMainID"].'_subscribe_div',
				'SUBSCRIBED_DIV' => $arAllIDs["strMainID"].'_subscribed_div',
				'STORE_QUANTITY' => $arAllIDs["strMainID"].'_store_quantity',
			);
		}else{
			$arAllIDs["ALL_ITEM_IDS"] = array(
				'ID' => $arAllIDs["strMainID"],
				'PICT' => $arAllIDs["strMainID"].'_pict',
				'SECOND_PICT' => $arAllIDs["strMainID"].'_secondpict',
				'STICKER_ID' => $arAllIDs["strMainID"].'_sticker',
				'SECOND_STICKER_ID' => $arAllIDs["strMainID"].'_secondsticker',
				'QUANTITY' => $arAllIDs["strMainID"].'_quantity',
				'QUANTITY_DOWN' => $arAllIDs["strMainID"].'_quant_down',
				'QUANTITY_UP' => $arAllIDs["strMainID"].'_quant_up',
				'QUANTITY_MEASURE' => $arAllIDs["strMainID"].'_quant_measure',
				'BUY_LINK' => $arAllIDs["strMainID"].'_buy_link',
				'BASKET_LINK' => $arAllIDs["strMainID"].'_basket_link',
				'BASKET_ACTIONS' => $arAllIDs["strMainID"].'_basket_actions',
				'NOT_AVAILABLE_MESS' => $arAllIDs["strMainID"].'_not_avail',
				'SUBSCRIBE_LINK' => $arAllIDs["strMainID"].'_subscribe',
				'COMPARE_LINK' => $arAllIDs["strMainID"].'_compare_link',
				'STORE_QUANTITY' => $arAllIDs["strMainID"].'_store_quantity',
				'PRICE' => $arAllIDs["strMainID"].'_price',
				'PRICE_OLD' => $arAllIDs["strMainID"].'_price_old',
				'DSC_PERC' => $arAllIDs["strMainID"].'_dsc_perc',
				'SECOND_DSC_PERC' => $arAllIDs["strMainID"].'_second_dsc_perc',
				'PROP_DIV' => $arAllIDs["strMainID"].'_sku_tree',
				'PROP' => $arAllIDs["strMainID"].'_prop_',
				'DISPLAY_PROP_DIV' => $arAllIDs["strMainID"].'_sku_prop',
				'BASKET_PROP_DIV' => $arAllIDs["strMainID"].'_basket_prop',
				'SUBSCRIBE_DIV' => $arAllIDs["strMainID"].'subscribe_div',
				'SUBSCRIBED_DIV' => $arAllIDs["strMainID"].'subscribed_div',
			);
		}

		$arAllIDs["TITLE_ITEM"] = (
			isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])&& $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
			? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
			: $arItem['NAME']
		);
		return $arAllIDs;
	}

	public static function GetSKUJSParams($arResult, $arParams, $arItem, $detail="N", $group_iblock_id="N"){
		$arSkuProps = array();

		if($group_iblock_id=="Y"){
			$arResult['SKU_PROPS']=reset($arResult['SKU_PROPS']);
		}

		foreach ($arResult['SKU_PROPS'] as $arOneProp){
			if (!isset($arItem['OFFERS_PROP'][$arOneProp['CODE']]))
				continue;
			$arSkuProps[] = array(
				'ID' => $arOneProp['ID'],
				'CODE' => $arOneProp['CODE'],
				'SHOW_MODE' => $arOneProp['SHOW_MODE'],
				'VALUES_COUNT' => $arOneProp['VALUES_COUNT'],
				'DISPLAY_TYPE' => ((($arOneProp['DISPLAY_TYPE'] == "P" || $arOneProp['DISPLAY_TYPE'] == "R") && $arParams["DISPLAY_TYPE"] != 'block' ) ? "SELECT" : "LI" ),
			);
		}

		foreach ($arItem['JS_OFFERS'] as &$arOneJs){
			if (0 < $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'])
			{
				$arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'] = '-'.$arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'].'%';
				$arOneJs['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = '-'.$arOneJs['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'].'%';
			}
		}
		unset($arOneJs);
		if ($arItem['OFFERS_PROPS_DISPLAY']){
			foreach ($arItem['JS_OFFERS'] as $keyOffer => $arJSOffer){
				$strProps = '';
				$arArticle=array();
				if (!empty($arJSOffer['DISPLAY_PROPERTIES'])){
					foreach ($arJSOffer['DISPLAY_PROPERTIES'] as $arOneProp){
						if($arOneProp['CODE']=='ARTICLE'){
							$arArticle=$arOneProp;
							continue;
						}
						$strProps .= '<tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue"><td><span itemprop="name">'.$arOneProp['NAME'].'</span></td><td><span itemprop="value">'.(
							is_array($arOneProp['VALUE'])
							? implode(' / ', $arOneProp['VALUE'])
							: $arOneProp['VALUE']
						).'</span></td></tr>';

					}
				}
				if($arArticle){
					$strArticle = '';
					$strArticle .= $arArticle['NAME'].': '.(
							is_array($arArticle['VALUE'])
							? implode(' / ', $arArticle['VALUE'])
							: $arArticle['VALUE']
						);

					$arItem['JS_OFFERS'][$keyOffer]['ARTICLE'] = $strArticle;
				}

				$arItem['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;

			}
		}
		if ($arItem['SHOW_OFFERS_PROPS']){
			foreach ($arItem['JS_OFFERS'] as $keyOffer => $arJSOffer){
				$strProps = '';
				if (!empty($arJSOffer['DISPLAY_PROPERTIES'])){
					foreach ($arJSOffer['DISPLAY_PROPERTIES'] as $arOneProp){
						if($arOneProp['VALUE']){
							$arOneProp['VALUE_FORMAT']='<span class="block_title" itemprop="name">'.$arOneProp['NAME'].': </span><span class="value" itemprop="value">'.$arOneProp['VALUE'].'</span>';
							if($arOneProp['CODE']!='ARTICLE'){
								$strProps .='<tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue"><td class="char_name"><span itemprop="name">'.$arOneProp['NAME'].'</span></td><td class="char_value"><span itemprop="value">'.$arOneProp['VALUE'].'</span></td></tr>';
							}
						}
						$arItem['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES_CODE'][$arOneProp["CODE"]] = $arOneProp;
					}
				}
				$arItem['JS_OFFERS'][$keyOffer]['TABLE_PROP']=$strProps;
			}
			foreach ($arItem['JS_OFFERS'] as $keyOffer => $arJSOffer){
				if (!empty($arJSOffer['DISPLAY_PROPERTIES'])){
					foreach ($arJSOffer['DISPLAY_PROPERTIES'] as $keyProp => $arOneProp){
						if($arOneProp['VALUE']){
							if($arOneProp['CODE']=='ARTICLE')
								unset($arItem['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'][$keyProp]);
						}
					}
				}
			}
		}

		$arItemIDs=self::GetItemsIDs($arItem);
		if($detail=="Y"){
			$arJSParams = array(
				'CONFIG' => array(
					'USE_CATALOG' => $arResult['CATALOG'],
					'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
					'SHOW_PRICE' => true,
					'SHOW_DISCOUNT_PERCENT' => ($arParams['SHOW_DISCOUNT_PERCENT'] == 'Y'),
					'SHOW_OLD_PRICE' => ($arParams['SHOW_OLD_PRICE'] == 'Y'),
					'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
					'SHOW_SKU_PROPS' => $arItem['SHOW_OFFERS_PROPS'],
					'OFFER_GROUP' => $arItem['OFFER_GROUP'],
					'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
					'SHOW_BASIS_PRICE' => ($arParams['SHOW_BASIS_PRICE'] == 'Y'),
					'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
					'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y')
				),
				'SHOW_UNABLE_SKU_PROPS' => $arParams['SHOW_UNABLE_SKU_PROPS'],
				'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
				'VISUAL' => array(
					'ID' => $arItemIDs["ALL_ITEM_IDS"]['ID'],
				),
				'DEFAULT_COUNT' => $arParams['DEFAULT_COUNT'],
				'DEFAULT_PICTURE' => array(
					'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
					'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
				),
				'STORE_QUANTITY' => $arItemIDs["ALL_ITEM_IDS"]['STORE_QUANTITY'],
				'PRODUCT' => array(
					'ID' => $arResult['ID'],
					'NAME' => $arResult['~NAME']
				),
				'BASKET' => array(
					'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
					'BASKET_URL' => $arParams['BASKET_URL'],
					'SKU_PROPS' => $arItem['OFFERS_PROP_CODES'],
					'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
					'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
				),
				'OFFERS' => $arItem['JS_OFFERS'],
				'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
				'SKU_DETAIL_ID' => $arParams['SKU_DETAIL_ID'],
				'TREE_PROPS' => $arSkuProps
			);
		}else{
			$arJSParams = array(
				'SHOW_UNABLE_SKU_PROPS' => $arParams['SHOW_UNABLE_SKU_PROPS'],
				'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
				'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
				'DEFAULT_COUNT' => $arParams['DEFAULT_COUNT'],
				'SHOW_ADD_BASKET_BTN' => false,
				'SHOW_BUY_BTN' => true,
				'SHOW_ABSENT' => true,
				'SHOW_SKU_PROPS' => $arItem['OFFERS_PROPS_DISPLAY'],
				'SECOND_PICT' => $arItem['SECOND_PICT'],
				'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
				'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
				'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
				'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
				'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
				'BASKET_URL' => $arParams['BASKET_URL'],
				'DEFAULT_PICTURE' => array(
					'PICTURE' => $arItem['PRODUCT_PREVIEW'],
					'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
				),
				'VISUAL' => array(
					'ID' => $arItemIDs["ALL_ITEM_IDS"]['ID'],
					'PICT_ID' => $arItemIDs["ALL_ITEM_IDS"]['PICT'],
					'SECOND_PICT_ID' => $arItemIDs["ALL_ITEM_IDS"]['SECOND_PICT'],
					'QUANTITY_ID' => $arItemIDs["ALL_ITEM_IDS"]['QUANTITY'],
					'QUANTITY_UP_ID' => $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP'],
					'QUANTITY_DOWN_ID' => $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN'],
					'QUANTITY_MEASURE' => $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_MEASURE'],
					'STORE_QUANTITY' => $arItemIDs["ALL_ITEM_IDS"]['STORE_QUANTITY'],
					'PRICE_ID' => $arItemIDs["ALL_ITEM_IDS"]['PRICE'],
					'PRICE_OLD_ID' => $arItemIDs["ALL_ITEM_IDS"]['PRICE_OLD'],
					'TREE_ID' => $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV'],
					'TREE_ITEM_ID' => $arItemIDs["ALL_ITEM_IDS"]['PROP'],
					'BUY_ID' => $arItemIDs["ALL_ITEM_IDS"]['BUY_LINK'],
					'BASKET_LINK' => $arItemIDs["ALL_ITEM_IDS"]['BASKET_LINK'],
					'ADD_BASKET_ID' => $arItemIDs["ALL_ITEM_IDS"]['ADD_BASKET_ID'],
					'DSC_PERC' => $arItemIDs["ALL_ITEM_IDS"]['DSC_PERC'],
					'SECOND_DSC_PERC' => $arItemIDs["ALL_ITEM_IDS"]['SECOND_DSC_PERC'],
					'DISPLAY_PROP_DIV' => $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV'],
					'BASKET_ACTIONS_ID' => $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS'],
					'NOT_AVAILABLE_MESS' => $arItemIDs["ALL_ITEM_IDS"]['NOT_AVAILABLE_MESS'],
					'COMPARE_LINK_ID' => $arItemIDs["ALL_ITEM_IDS"]['COMPARE_LINK'],
					'SUBSCRIBE_ID' => $arItemIDs["ALL_ITEM_IDS"]['SUBSCRIBE_DIV'],
					'SUBSCRIBED_ID' => $arItemIDs["ALL_ITEM_IDS"]['SUBSCRIBED_DIV'],
				),
				'BASKET' => array(
					'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
					'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
					'SKU_PROPS' => $arItem['OFFERS_PROP_CODES'],
					'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
					'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
				),
				'PRODUCT' => array(
					'ID' => $arItem['ID'],
					'NAME' => $arItemIDs["TITLE_ITEM"]
				),
				'OFFERS' => $arItem['JS_OFFERS'],
				'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
				'TREE_PROPS' => $arSkuProps,
				'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
			);
		}
		$arJSParams['OFFER_SHOW_PREVIEW_PICTURE_PROPS'] = $arParams['OFFER_SHOW_PREVIEW_PICTURE_PROPS'];
		if ($arParams['DISPLAY_COMPARE']){
			$arJSParams['COMPARE'] = array(
				'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
				'COMPARE_URL_TEMPLATE_DEL' => str_replace("ADD_TO_COMPARE_LIST", "DELETE_FROM_COMPARE_LIST", $arResult['~COMPARE_URL_TEMPLATE']),
				'COMPARE_PATH' => $arParams['COMPARE_PATH']
			);
		}
		return $arJSParams;
	}

	public static function GetAddToBasketArray(&$arItem, $totalCount = 0, $defaultCount = 1, $basketUrl = '', $bDetail = false, $arItemIDs = array(), $class_btn = "small", $arParams=array()){
		static $arAddToBasketOptions, $bUserAuthorized;
		if($arAddToBasketOptions === NULL){
			$arAddToBasketOptions = array(
				"SHOW_BASKET_ONADDTOCART" => COption::GetOptionString("aspro.optimus", "SHOW_BASKET_ONADDTOCART", "Y", SITE_ID) == "Y",
				"USE_PRODUCT_QUANTITY_LIST" => COption::GetOptionString("aspro.optimus", "USE_PRODUCT_QUANTITY_LIST", "Y", SITE_ID) == "Y",
				"USE_PRODUCT_QUANTITY_DETAIL" => COption::GetOptionString("aspro.optimus", "USE_PRODUCT_QUANTITY_DETAIL", "Y", SITE_ID) == "Y",
				"BUYNOPRICEGGOODS" => COption::GetOptionString("aspro.optimus", "BUYNOPRICEGGOODS", "NOTHING", SITE_ID),
				"BUYMISSINGGOODS" => COption::GetOptionString("aspro.optimus", "BUYMISSINGGOODS", "ADD", SITE_ID),
				"EXPRESSION_ORDER_BUTTON" => COption::GetOptionString("aspro.optimus", "EXPRESSION_ORDER_BUTTON", GetMessage("EXPRESSION_ORDER_BUTTON_DEFAULT"), SITE_ID),
				"EXPRESSION_ORDER_TEXT" => COption::GetOptionString("aspro.optimus", "EXPRESSION_ORDER_TEXT", GetMessage("EXPRESSION_ORDER_TEXT_DEFAULT"), SITE_ID),
				"EXPRESSION_SUBSCRIBE_BUTTON" => COption::GetOptionString("aspro.optimus", "EXPRESSION_SUBSCRIBE_BUTTON", GetMessage("EXPRESSION_SUBSCRIBE_BUTTON_DEFAULT"), SITE_ID),
				"EXPRESSION_SUBSCRIBED_BUTTON" => COption::GetOptionString("aspro.optimus", "EXPRESSION_SUBSCRIBED_BUTTON", GetMessage("EXPRESSION_SUBSCRIBED_BUTTON_DEFAULT"), SITE_ID),
			);

			global $USER;
			$bUserAuthorized = $USER->IsAuthorized();
		}

		$buttonText = $buttonHTML = $buttonACTION = '';
		$quantity=$ratio=1;
		$max_quantity=0;
		$float_ratio=is_double($arItem["CATALOG_MEASURE_RATIO"]);

		$minPriceRangeQty = 0;
		if (isset($arItem['ITEM_PRICE_MODE']) && $arItem['ITEM_PRICE_MODE'] === 'Q') {
			$priceSelected = $arItem['ITEM_PRICE_SELECTED'];
			if (isset($arItem['FIX_PRICE_MATRIX']) && $arItem['FIX_PRICE_MATRIX']) {
				$priceSelected = $arItem['FIX_PRICE_MATRIX']['PRICE_SELECT'];
			}
			
			if (isset($arItem['ITEM_PRICES']) && $arItem['ITEM_PRICES'][$priceSelected]['MIN_QUANTITY'] != 1) {
				$minPriceRangeQty = $arItem['ITEM_PRICES'][$priceSelected]['MIN_QUANTITY'];
			}
		}

		$setMinQty = false;

		if ($arItem["CATALOG_MEASURE_RATIO"] || $minPriceRangeQty) {
			if ($minPriceRangeQty && ($minPriceRangeQty > $arItem["CATALOG_MEASURE_RATIO"])) {
				$quantity=$minPriceRangeQty;
				$setMinQty = true;
			} else {
				$quantity=$arItem["CATALOG_MEASURE_RATIO"];
			}
			if ($arItem["CATALOG_MEASURE_RATIO"]) {
				$ratio=$arItem["CATALOG_MEASURE_RATIO"];
			}
		} else {
			$quantity=$defaultCount;
		}
		if($arItem["CATALOG_QUANTITY_TRACE"]=="Y"){
			if($totalCount < $quantity){
				$quantity=($totalCount>$arItem["CATALOG_MEASURE_RATIO"] ? $totalCount : $arItem["CATALOG_MEASURE_RATIO"] );
			}
			$max_quantity=$totalCount;
		}

		$arItemProps = (($arParams['PRODUCT_PROPERTIES']) ? implode(';', $arParams['PRODUCT_PROPERTIES']) : "");
		$partProp=($arParams["PARTIAL_PRODUCT_PROPERTIES"] ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : "" );
		$addProp=($arParams["ADD_PROPERTIES_TO_BASKET"] ? $arParams["ADD_PROPERTIES_TO_BASKET"] : "" );
		$emptyProp=$arItem["EMPTY_PROPS_JS"];

		global $TEMPLATE_OPTIONS;
		if($arItem["OFFERS"]){
			if(!$bDetail && $arItem["OFFERS_MORE"] != "Y" && $TEMPLATE_OPTIONS["TYPE_SKU"]["CURRENT_VALUE"] != "TYPE_2"){
				$buttonACTION = 'ADD';
				$buttonText = array(GetMessage('EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT'), GetMessage('EXPRESSION_ADDEDTOBASKET_BUTTON_DEFAULT'));
				$buttonHTML = '<span class="button transition_bg '.$class_btn.' read_more1 to-cart" id="'.$arItemIDs['BUY_LINK'].'" data-offers="N" data-iblockID="'.$arItem["IBLOCK_ID"].'" data-item="'.$arItem["ID"].'"><i></i><span>'.$buttonText[0].'</span></span><a rel="nofollow" href="'.$basketUrl.'" id="'.$arItemIDs['BASKET_LINK'].'" class="'.$class_btn.' in-cart button transition_bg" data-item="'.$arItem["ID"].'"  style="display:none;"><i></i><span>'.$buttonText[1].'</span></a>';
			}
			elseif(($bDetail && $arItem["FRONT_CATALOG"] == "Y") || $arItem["OFFERS_MORE"]=="Y" || $TEMPLATE_OPTIONS["TYPE_SKU"]["CURRENT_VALUE"] == "TYPE_2"){
				$buttonACTION = 'MORE';
				$buttonText = array(GetMessage('EXPRESSION_READ_MORE_OFFERS_DEFAULT'));
				$buttonHTML = '<a class="button transition_bg basket read_more" rel="nofollow" href="'.$arItem["DETAIL_PAGE_URL"].'" data-item="'.$arItem["ID"].'">'.$buttonText[0].'</a>';
			}
		}
		else{
			if($bPriceExists = ((isset($arItem["MIN_PRICE"]) && $arItem["MIN_PRICE"]["VALUE"] > 0) || (isset($arItem['1'])))){
				// price exists
				if($totalCount > 0){
					// rest exists
					if((isset($arItem["CAN_BUY"]) && $arItem["CAN_BUY"]) || (isset($arItem["MIN_PRICE"]) && $arItem["MIN_PRICE"]["CAN_BUY"] == "Y")){
						if($bDetail && $arItem["FRONT_CATALOG"] == "Y"){
							$buttonACTION = 'MORE';
							$buttonText = array(GetMessage('EXPRESSION_READ_MORE_OFFERS_DEFAULT'));
							$rid=($arItem["RID"] ? "?RID=".$arItem["RID"] : "");
							$buttonHTML = '<a class="button transition_bg basket read_more" rel="nofollow" href="'.$arItem["DETAIL_PAGE_URL"].$rid.'" data-item="'.$arItem["ID"].'">'.$buttonText[0].'</a>';
						}
						else{
							$arItem["CAN_BUY"] = 1;
							$buttonACTION = 'ADD';
							$buttonText = array(GetMessage('EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT'), GetMessage('EXPRESSION_ADDEDTOBASKET_BUTTON_DEFAULT'));
							$buttonHTML = '<span data-value="'.$arItem["MIN_PRICE"]["DISCOUNT_VALUE"].'" data-currency="'.$arItem["MIN_PRICE"]["CURRENCY"].'" class="'.$class_btn.' to-cart button transition_bg" data-item="'.$arItem["ID"].'" data-float_ratio="'.$float_ratio.'" data-ratio="'.$ratio.'" data-bakset_div="bx_basket_div_'.$arItem["ID"].'" data-props="'.$arItemProps.'" data-part_props="'.$partProp.'" data-add_props="'.$addProp.'"  data-empty_props="'.$emptyProp.'" data-offers="'.$arItem["IS_OFFER"].'" data-iblockID="'.$arItem["IBLOCK_ID"].'"  data-quantity="'.$quantity.'"><i></i><span>'.$buttonText[0].'</span></span><a rel="nofollow" href="'.$basketUrl.'" class="'.$class_btn.' in-cart button transition_bg" data-item="'.$arItem["ID"].'"  style="display:none;"><i></i><span>'.$buttonText[1].'</span></a>';
						}
					}
					elseif($arItem["CATALOG_SUBSCRIBE"] == "Y"){
						$buttonACTION = 'SUBSCRIBE';
						$buttonText = array($arAddToBasketOptions['EXPRESSION_SUBSCRIBE_BUTTON'], $arAddToBasketOptions['EXPRESSION_SUBSCRIBED_BUTTON']);
						$buttonHTML = '<span class="'.$class_btn.' ss to-subscribe'.(!$bUserAuthorized ? ' auth' : '').(self::checkVersionModule('16.5.3', 'catalog') ? ' nsubsc' : '').' button transition_bg" rel="nofollow" data-param-form_id="subscribe" data-name="subscribe" data-param-id="'.$arItem["ID"].'" data-item="'.$arItem["ID"].'"><i></i><span>'.$buttonText[0].'</span></span><span class="'.$class_btn.' ss in-subscribe button transition_bg" rel="nofollow" style="display:none;" data-item="'.$arItem["ID"].'"><i></i><span>'.$buttonText[1].'</span></span>';
					}
				}
				else{
					if(!strlen($arAddToBasketOptions['EXPRESSION_ORDER_BUTTON'])){
						$arAddToBasketOptions['EXPRESSION_ORDER_BUTTON']=GetMessage("EXPRESSION_ORDER_BUTTON_DEFAULT");
					}
					// no rest
					if($bDetail && $arItem["FRONT_CATALOG"] == "Y"){
						$buttonACTION = 'MORE';
						$buttonText = array(GetMessage('EXPRESSION_READ_MORE_OFFERS_DEFAULT'));
						$rid=($arItem["RID"] ? "?RID=".$arItem["RID"] : "");
						$buttonHTML = '<a class="button transition_bg basket read_more" rel="nofollow" href="'.$arItem["DETAIL_PAGE_URL"].$rid.'" data-item="'.$arItem["ID"].'">'.$buttonText[0].'</a>';
					}
					else{
						$buttonACTION = $arAddToBasketOptions["BUYMISSINGGOODS"];
						if($arAddToBasketOptions["BUYMISSINGGOODS"] == "ADD" /*|| $arItem["CAN_BUY"]*/){
							if($arItem["CAN_BUY"]){
								$buttonText = array(GetMessage('EXPRESSION_ADDTOBASKET_BUTTON_DEFAULT'), GetMessage('EXPRESSION_ADDEDTOBASKET_BUTTON_DEFAULT'));
								$buttonHTML = '<span data-value="'.$arItem["MIN_PRICE"]["DISCOUNT_VALUE"].'" data-currency="'.$arItem["MIN_PRICE"]["CURRENCY"].'" class="'.$class_btn.' to-cart button transition_bg" data-item="'.$arItem["ID"].'" data-float_ratio="'.$float_ratio.'" data-ratio="'.$ratio.'" data-bakset_div="bx_basket_div_'.$arItem["ID"].'" data-props="'.$arItemProps.'" data-part_props="'.$partProp.'" data-add_props="'.$addProp.'"  data-empty_props="'.$emptyProp.'" data-offers="'.$arItem["IS_OFFER"].'" data-iblockID="'.$arItem["IBLOCK_ID"].'" data-quantity="'.$quantity.'"><i></i><span>'.$buttonText[0].'</span></span><a rel="nofollow" href="'.$basketUrl.'" class="'.$class_btn.' in-cart button transition_bg" data-item="'.$arItem["ID"].'"  style="display:none;"><i></i><span>'.$buttonText[1].'</span></a>';
							}else{
								if($arAddToBasketOptions["BUYMISSINGGOODS"] == "SUBSCRIBE" && $arItem["CATALOG_SUBSCRIBE"] == "Y"){
									$buttonText = array($arAddToBasketOptions['EXPRESSION_SUBSCRIBE_BUTTON'], $arAddToBasketOptions['EXPRESSION_SUBSCRIBED_BUTTON']);
									$buttonHTML = '<span class="'.$class_btn.' ss to-subscribe'.(!$bUserAuthorized ? ' auth' : '').(self::checkVersionModule('16.5.3', 'catalog') ? ' nsubsc' : '').' button transition_bg" rel="nofollow" data-param-form_id="subscribe" data-name="subscribe" data-param-id="'.$arItem["ID"].'" data-item="'.$arItem["ID"].'"><i></i><span>'.$buttonText[0].'</span></span><span class="'.$class_btn.' ss in-subscribe button transition_bg" rel="nofollow" style="display:none;" data-item="'.$arItem["ID"].'"><i></i><span>'.$buttonText[1].'</span></span>';
								}else{
									$buttonText = array($arAddToBasketOptions['EXPRESSION_ORDER_BUTTON']);
									$buttonHTML = '<span class="'.$class_btn.' to-order button transition_bg transparent" data-name="'.$arItem["NAME"].'" data-item="'.$arItem["ID"].'"><i></i><span>'.$buttonText[0].'</span></span>';
									if($arAddToBasketOptions['EXPRESSION_ORDER_TEXT']){
										$buttonHTML .='<div class="more_text">'.$arAddToBasketOptions['EXPRESSION_ORDER_TEXT'].'</div>';
									}
								}
							}

						}
						elseif($arAddToBasketOptions["BUYMISSINGGOODS"] == "SUBSCRIBE" && $arItem["CATALOG_SUBSCRIBE"] == "Y"){
							$buttonText = array($arAddToBasketOptions['EXPRESSION_SUBSCRIBE_BUTTON'], $arAddToBasketOptions['EXPRESSION_SUBSCRIBED_BUTTON']);
							$buttonHTML = '<span class="'.$class_btn.' ss to-subscribe'.(!$bUserAuthorized ? ' auth' : '').(self::checkVersionModule('16.5.3', 'catalog') ? ' nsubsc' : '').' button transition_bg" rel="nofollow" data-param-form_id="subscribe" data-name="subscribe" data-param-id="'.$arItem["ID"].'" data-item="'.$arItem["ID"].'"><i></i><span>'.$buttonText[0].'</span></span><span class="'.$class_btn.' ss in-subscribe button transition_bg" rel="nofollow" style="display:none;" data-item="'.$arItem["ID"].'"><i></i><span>'.$buttonText[1].'</span></span>';
						}
						elseif($arAddToBasketOptions["BUYMISSINGGOODS"] == "ORDER"){
							$buttonText = array($arAddToBasketOptions['EXPRESSION_ORDER_BUTTON']);
							$buttonHTML = '<span class="'.$class_btn.' to-order button transition_bg transparent" data-name="'.$arItem["NAME"].'" data-item="'.$arItem["ID"].'"><i></i><span>'.$buttonText[0].'</span></span>';
							if($arAddToBasketOptions['EXPRESSION_ORDER_TEXT']){
								$buttonHTML .='<div class="more_text">'.$arAddToBasketOptions['EXPRESSION_ORDER_TEXT'].'</div>';
							}
						}
					}
				}
			}
			else{
				// no price or price <= 0
				if($bDetail && $arItem["FRONT_CATALOG"] == "Y"){
					$buttonACTION = 'MORE';
					$buttonText = array(GetMessage('EXPRESSION_READ_MORE_OFFERS_DEFAULT'));
					$buttonHTML = '<a class="button transition_bg basket read_more" rel="nofollow" href="'.$arItem["DETAIL_PAGE_URL"].'" data-item="'.$arItem["ID"].'">'.$buttonText[0].'</a>';
				}
				else{
					$buttonACTION = $arAddToBasketOptions["BUYNOPRICEGGOODS"];
					if($arAddToBasketOptions["BUYNOPRICEGGOODS"] == "ORDER"){
						$buttonText = array($arAddToBasketOptions['EXPRESSION_ORDER_BUTTON']);
						$buttonHTML = '<span class="'.$class_btn.' to-order button transition_bg transparent" data-name="'.$arItem["NAME"].'" data-item="'.$arItem["ID"].'"><i></i><span>'.$buttonText[0].'</span></span>';
						if($arAddToBasketOptions['EXPRESSION_ORDER_TEXT']){
							$buttonHTML .='<div class="more_text">'.$arAddToBasketOptions['EXPRESSION_ORDER_TEXT'].'</div>';
						}
					}
				}
			}
		}

		$arOptions = array("OPTIONS" => $arAddToBasketOptions, "TEXT" => $buttonText, "HTML" => $buttonHTML, "ACTION" => $buttonACTION, "RATIO_ITEM" => $ratio, "MIN_QUANTITY_BUY" => $quantity, "MAX_QUANTITY_BUY" => $max_quantity, "CAN_BUY" => $arItem['CAN_BUY']);

		if ($setMinQty) {
			$arOptions["SET_MIN_QUANTITY_BUY"] = true;
		}

		foreach(GetModuleEvents(OPTIMUS_MODULE_ID, 'OnAsproGetBuyBlockElement', true) as $arEvent) // event for manipulation with buy block element
			ExecuteModuleEventEx($arEvent, array($arItem, $totalCount, $arParams, &$arOptions));

		return $arOptions;
	}

	public static function checkVersionExt($template="main", $module="catalog"){
		if($info = CModule::CreateModuleObject($module)){
			$testVersion = '16.0.14';
			if(CheckVersion($testVersion, $info->MODULE_VERSION)){
				$templateInclude=$template;
			}
			else{
				$templateInclude=$template."_new";
			}
		}
		return $templateInclude;
	}

	public static function checkVersionModule($version = '1.0.0', $module="catalog"){
		if($info = CModule::CreateModuleObject($module))
		{
			if(!CheckVersion($version, $info->MODULE_VERSION))
				return true;
		}
		return false;
	}

	public static function GetFileInfo($arItem){
		$arTmpItem = CFile::GetFileArray($arItem);

		switch($arTmpItem["CONTENT_TYPE"]){
            case 'application/pdf': $type="pdf"; break;
            case 'application/vnd.ms-excel': $type="excel"; break;
            case 'application/xls': $type="excel"; break;
            case 'application/octet-stream': $type="word"; break;
            case 'application/msword': $type="word"; break;
            case 'application/x-zip-compressed': $type="zip"; break;    
            case 'image/jpeg': $type="jpg"; break;
            case 'image/tiff': $type="tiff"; break;
            case 'image/png': $type="png"; break;
            case 'image/bmp': $type="bmp"; break;
            case 'image/gif': $type="gif"; break;
            case 'text/plain': $type="txt"; break;
            
            default: $type="default"; break;
        }

        if($arTmpItem["CONTENT_TYPE"] === 'application/octet-stream' || $type === 'default'){
            $frm = explode('.', $arTmpItem['FILE_NAME']);
            $frm = $frm[1];
            if($frm == 'doc' || $frm == 'docx')
                $type = 'word';
            elseif($frm == 'ppt')
                $type = 'ppt';
            elseif($frm == 'pptx')
                $type = 'pptx';
            elseif($frm == 'tif')
                $type = 'tif';
            elseif($frm == 'xls' || $frm == 'xlsx')
            $type = 'excel';
            elseif($frm == 'rar')
                $type = 'rar';
            elseif($frm == 'rtf')
                $type = 'rtf';
            else
                $type = 'file';
        }

        $filesize = $arTmpItem["FILE_SIZE"];
        if($filesize > 1024){
            $filesize = ($filesize / 1024);
            if($filesize > 1024){
                $filesize = ($filesize / 1024);
                if($filesize > 1024){
                    $filesize = ($filesize / 1024);
                    $filesize = round($filesize, 1);
                    $filesize_format=str_replace(".", ",", $filesize).GetMessage('CT_NAME_GB');
                }
                else{
                    $filesize = round($filesize, 1);
                    $filesize_format=str_replace(".", ",", $filesize).GetMessage('CT_NAME_MB');
                }
            }
            else{
                $filesize = round($filesize, 1);
                $filesize_format=str_replace(".", ",", $filesize).GetMessage('CT_NAME_KB');
            }
        }
        else{
            $filesize = round($filesize, 1);
            $filesize_format=str_replace(".", ",", $filesize).GetMessage('CT_NAME_b');
        }
        $fileName = substr($arTmpItem["ORIGINAL_NAME"], 0, strrpos($arTmpItem["ORIGINAL_NAME"], '.'));
        return array("TYPE" => $type, "FILE_SIZE" => $filesize, "FILE_SIZE_FORMAT" => $filesize_format, "DESCRIPTION" => ( $arTmpItem["DESCRIPTION"] ? $arTmpItem["DESCRIPTION"] : $fileName), "SRC" => $arTmpItem["SRC"]);
	}

	public static function getMinPriceFromOffersExt(&$offers, $currency, $replaceMinPrice = true){
		$replaceMinPrice = ($replaceMinPrice === true);
		$result = false;
		$minPrice = 0;
		if (!empty($offers) && is_array($offers))
		{
			$doubles = array();
			foreach ($offers as $oneOffer)
			{
				if(!$oneOffer["MIN_PRICE"])
					continue;
				$oneOffer['ID'] = (int)$oneOffer['ID'];
				if (isset($doubles[$oneOffer['ID']]))
					continue;
				/*if (!$oneOffer['CAN_BUY'])
					continue;*/

				CIBlockPriceTools::setRatioMinPrice($oneOffer, $replaceMinPrice);

				$oneOffer['MIN_PRICE']['CATALOG_MEASURE_RATIO'] = $oneOffer['CATALOG_MEASURE_RATIO'];
				$oneOffer['MIN_PRICE']['CATALOG_MEASURE'] = $oneOffer['CATALOG_MEASURE'];
				$oneOffer['MIN_PRICE']['CATALOG_MEASURE_NAME'] = $oneOffer['CATALOG_MEASURE_NAME'];
				$oneOffer['MIN_PRICE']['~CATALOG_MEASURE_NAME'] = $oneOffer['~CATALOG_MEASURE_NAME'];

				if (empty($result))
				{
					$minPrice = ($oneOffer['MIN_PRICE']['CURRENCY'] == $currency
						? $oneOffer['MIN_PRICE']['DISCOUNT_VALUE']
						: CCurrencyRates::ConvertCurrency($oneOffer['MIN_PRICE']['DISCOUNT_VALUE'], $oneOffer['MIN_PRICE']['CURRENCY'], $currency)
					);
					$result = $oneOffer['MIN_PRICE'];
				}
				else
				{
					$comparePrice = ($oneOffer['MIN_PRICE']['CURRENCY'] == $currency
						? $oneOffer['MIN_PRICE']['DISCOUNT_VALUE']
						: CCurrencyRates::ConvertCurrency($oneOffer['MIN_PRICE']['DISCOUNT_VALUE'], $oneOffer['MIN_PRICE']['CURRENCY'], $currency)
					);
					if ($minPrice > $comparePrice)
					{
						$minPrice = $comparePrice;
						$result = $oneOffer['MIN_PRICE'];
					}
				}
				$doubles[$oneOffer['ID']] = true;
			}
		}
		return $result;
	}

	public static function getSliderForItemExt(&$item, $propertyCode, $addDetailToSlider, $encode = true)
    {
        $encode = ($encode === true);
        $result = array();

        if (!empty($item) && is_array($item))
        {
            if (
                '' != $propertyCode &&
                isset($item['PROPERTIES'][$propertyCode]) &&
                'F' == $item['PROPERTIES'][$propertyCode]['PROPERTY_TYPE']
            )
            {
                if ('MORE_PHOTO' == $propertyCode && isset($item['MORE_PHOTO']) && !empty($item['MORE_PHOTO']))
                {

                    foreach ($item['MORE_PHOTO'] as &$onePhoto)
                    {
                        $result[] = array(
                            'ID' => (int)$onePhoto['ID'],
                            'SRC' => ($encode ? CHTTP::urnEncode($onePhoto['SRC'], 'utf-8') : $onePhoto['SRC']),
                            'WIDTH' => (int)$onePhoto['WIDTH'],
                            'HEIGHT' => (int)$onePhoto['HEIGHT'],
                            'ALT' => ($onePhoto["DESCRIPTION"] ? $onePhoto["DESCRIPTION"] : ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] : $item["NAME"])),
                            'TITLE' => ($onePhoto["DESCRIPTION"] ? $onePhoto["DESCRIPTION"] : ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] : $item["NAME"]))
                        );
                    }
                    unset($onePhoto);
                }
                else
                {
                    if (
                        isset($item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']) &&
                        !empty($item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE'])
                    )
                    {
                        $fileValues = (
                        isset($item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']['ID']) ?
                            array(0 => $item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']) :
                            $item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']
                        );
                        foreach ($fileValues as &$oneFileValue)
                        {
                            $result[] = array(
                                'ID' => (int)$oneFileValue['ID'],
                                'SRC' => ($encode ? CHTTP::urnEncode($oneFileValue['SRC'], 'utf-8') : $oneFileValue['SRC']),
                                'WIDTH' => (int)$oneFileValue['WIDTH'],
                                'HEIGHT' => (int)$oneFileValue['HEIGHT'],
                                'ALT' => ($oneFileValue["DESCRIPTION"] ? $oneFileValue["DESCRIPTION"] : ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] : $item["NAME"])),
                          		'TITLE' => ($oneFileValue["DESCRIPTION"] ? $oneFileValue["DESCRIPTION"] : ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] : $item["NAME"]))
                            );
                        }
                        if (isset($oneFileValue))
                            unset($oneFileValue);
                    }
                    else
                    {
                        $propValues = $item['PROPERTIES'][$propertyCode]['VALUE'];
                        if (!is_array($propValues))
                            $propValues = array($propValues);
                        foreach ($propValues as &$oneValue)
                        {
                            $oneFileValue = CFile::GetFileArray($oneValue);
                            if (isset($oneFileValue['ID']))
                            {
                                $result[] = array(
                                    'ID' => (int)$oneFileValue['ID'],
                                    'SRC' => ($encode ? CHTTP::urnEncode($oneFileValue['SRC'], 'utf-8') : $oneFileValue['SRC']),
                                    'WIDTH' => (int)$oneFileValue['WIDTH'],
                                    'HEIGHT' => (int)$oneFileValue['HEIGHT'],
                                    'ALT' => ($oneFileValue["DESCRIPTION"] ? $oneFileValue["DESCRIPTION"] : ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'] : $item["NAME"])),
                          			'TITLE' => ($oneFileValue["DESCRIPTION"] ? $oneFileValue["DESCRIPTION"] : ($item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] ? $item['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'] : $item["NAME"]))
                                );
                            }
                        }
                        if (isset($oneValue))
                            unset($oneValue);
                    }
                }
            }
            if(isset($item['OFFERS']) && $item['OFFERS'] && !$addDetailToSlider){
            	if(empty($result))
            		unset($item['DETAIL_PICTURE']);
            }

            if ($addDetailToSlider || empty($result))
            {
                if (!empty($item['DETAIL_PICTURE']))
                {
                    if (!is_array($item['DETAIL_PICTURE']))
                        $item['DETAIL_PICTURE'] = CFile::GetFileArray($item['DETAIL_PICTURE']);
                    if (isset($item['DETAIL_PICTURE']['ID']))
                    {
                        array_unshift(
                            $result,
                            array(
                                'ID' => (int)$item['DETAIL_PICTURE']['ID'],
                                'SRC' => ($encode ? CHTTP::urnEncode($item['DETAIL_PICTURE']['SRC'], 'utf-8') : $item['DETAIL_PICTURE']['SRC']),
                                'WIDTH' => (int)$item['DETAIL_PICTURE']['WIDTH'],
                                'HEIGHT' => (int)$item['DETAIL_PICTURE']['HEIGHT'],
                                'ALT' => ($item['DETAIL_PICTURE']['DESCRIPTION'] ? $item['DETAIL_PICTURE']['DESCRIPTION'] : ($item['DETAIL_PICTURE']['ALT'] ? $item['DETAIL_PICTURE']['ALT'] : $item['NAME'] )),
                                'TITLE' => ($item['DETAIL_PICTURE']['DESCRIPTION'] ? $item['DETAIL_PICTURE']['DESCRIPTION'] : ($item['DETAIL_PICTURE']['TITLE'] ? $item['DETAIL_PICTURE']['TITLE'] : $item['NAME'] ))
                            )
                        );
                    }
                }
            }
        }
        return $result;
    }

	public static function GetTemplateOptions($siteID){
		// check stores
		static $bStores;
		if ($bStores === null){
			$bStores = false;
			if(CModule::IncludeModule('catalog')){
				if(class_exists('CCatalogStore')){
					$dbRes = CCatalogStore::GetList(array(), array(), false, false, array());
					if($c = $dbRes->SelectedRowsCount()){
						$bStores = true;
					}
				}
			}
		}

		$res = self::getModuleOptionsList();
		$arComponentOptions = $res["TEMPLATE_OPTIONS"];
		$arTemplateOptions = array();
		foreach($arComponentOptions as $value){
			$arTemplateOptions[$value["ID"]] = $value;
			if ($value['ID'] === 'STORES_SOURCE' && !$bStores) {
				$arTemplateOptions[$value["ID"]]["CURRENT_VALUE"] = 'IBLOCK';
			}
			elseif($value['ID'] === 'LOGO_IMAGE' || $value['ID'] === 'FAVICON_IMAGE' || $value['ID'] === 'APPLE_TOUCH_ICON_IMAGE'){
				$arTemplateOptions[$value["ID"]]["CURRENT_VALUE"] = COption::GetOptionString(self::moduleID, $value["ID"], $value["DEFAULT"], $siteID);
				$arValue =  self::unserialize($arTemplateOptions[$value["ID"]]["CURRENT_VALUE"]);
				$arValue = (array)$arValue;
				$fileID = $arValue ? current($arValue) : false;
				if($fileID){
					if($value['ID'] === 'FAVICON_IMAGE'){
						$faviconFile = CFIle::GetPath($fileID);
						$file_ext = pathinfo($faviconFile, PATHINFO_EXTENSION);
						$fav_ext = $file_ext ? $file_ext : 'ico';						
						$arTemplateOptions[$value["ID"]]["CURRENT_IMG"] = str_replace('//', '/', SITE_DIR.'/favicon.'.$file_ext);
					}else{
						$arTemplateOptions[$value["ID"]]["CURRENT_IMG"] = CFIle::GetPath($fileID);
					}
				}
				else{
					if($value['ID'] === 'LOGO_IMAGE'){

					}
					elseif($value['ID'] === 'FAVICON_IMAGE'){
						$arTemplateOptions[$value["ID"]]["CURRENT_IMG"] = str_replace('//', '/', SITE_DIR.'/include/favicon.ico');
					}
					elseif($value['ID'] === 'APPLE_TOUCH_ICON_IMAGE'){
						$arTemplateOptions[$value["ID"]]["CURRENT_IMG"] = str_replace('//', '/', SITE_DIR.'/include/apple-touch-icon.png');
					}
				}

				if(!file_exists(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$arTemplateOptions[$value["ID"]]["CURRENT_IMG"]))){
					$arTemplateOptions[$value["ID"]]["CURRENT_IMG"] = '';
				}
			}
			else{
				$arTemplateOptions[$value["ID"]]["CURRENT_VALUE"] = COption::GetOptionString(self::moduleID, $value["ID"], $value["DEFAULT"], $siteID);
				if($value['ID'] === 'HEAD'){
					if($value["VALUES"] && is_array($value["VALUES"])){
						foreach($value["VALUES"] as $arValue){
							if($arValue["VALUE"] === $arTemplateOptions['HEAD']["CURRENT_VALUE"]){
								$arTemplateOptions["LOGO_IMAGE"]["CURRENT_IMG"] = ($arTemplateOptions["LOGO_IMAGE"]["CURRENT_VALUE"] !== serialize(array())) ? $arTemplateOptions["LOGO_IMAGE"]["CURRENT_IMG"] : str_replace('//', '/', SITE_DIR.$arValue["IMG"]);
								$arTemplateOptions["HEAD"]["CURRENT_MENU"] = $arValue["MENU_TYPE"];
								$arTemplateOptions["HEAD"]["CURRENT_HEAD_COLOR"] = $arValue["HEAD_COLOR"];
								$arTemplateOptions["HEAD"]["CURRENT_MENU_COLOR"] = ($arValue["MENU_COLOR"] ? $arValue["MENU_COLOR"] : "none");
								break;
							}
						}
					}
				}
			}
		}

		return $arTemplateOptions;
	}

	public static  function checkBreadcrumbsChain(&$arParams, $arSection = array(), $arElement = array()){
		global $APPLICATION;

		if(COption::GetOptionString("aspro.optimus", "SHOW_BREADCRUMBS_CATALOG_CHAIN", "H1", SITE_ID) == "NAME"){
			$APPLICATION->arAdditionalChain = false;
			if($arParams['INCLUDE_IBLOCK_INTO_CHAIN'] == 'Y' && isset(COptimusCache::$arIBlocksInfo[$arParams['IBLOCK_ID']]['NAME'])){
				$APPLICATION->AddChainItem(COptimusCache::$arIBlocksInfo[$arParams['IBLOCK_ID']]['NAME'], $arElement['~LIST_PAGE_URL']);
			}
			if($arParams['ADD_SECTIONS_CHAIN'] == 'Y' && $arSection){
				$rsPath = CIBlockSection::GetNavChain($arParams['IBLOCK_ID'], $arSection['ID']);
				$rsPath->SetUrlTemplates('', $arParams['SECTION_URL']);
				while($arPath = $rsPath->GetNext()){
					$APPLICATION->AddChainItem($arPath['NAME'], $arPath['~SECTION_PAGE_URL']);
				}
			}
			if($arParams['ADD_ELEMENT_CHAIN'] == 'Y' && $arElement){
				$APPLICATION->AddChainItem($arElement['NAME']);
			}
		}
	}

	public static  function getModuleOptionsList($onlyTemplate = false){
		// check stores
		static $arIsSiteWithStores;
		$by = "id"; $sort = "asc"; $arSites = array();
		$db_res = CSite::GetList($by , $sort ,array("ACTIVE"=>"Y"));
		while($res = $db_res->GetNext()){
			$arSites[] = $res;
		}

		foreach($arSites as $key => $arSite){
			if(COption::GetOptionString(self::moduleID, "SITE_INSTALLED", false, $arSite["ID"]) != "Y"){
				unset($arSites[$key]);
			}
			else{
				if ($arIsSiteWithStores[$arSite["ID"]] === null){
					$arIsSiteWithStores[$arSite["ID"]] = false;
					if(CModule::IncludeModule('catalog')){
						if(class_exists('CCatalogStore')){
							$dbRes = CCatalogStore::GetList(array(), array('ACTIVE' => 'Y', 'SITE_ID' => $arSite["ID"]), false, false, array());
							if($c = $dbRes->SelectedRowsCount()){
								$arIsSiteWithStores[$arSite["ID"]] = true;
							}
						}
					}
				}
			}
		}

		// template options
		$arTemplateOptions = array(
			array(
				"ID" => "COLOR_THEME",
				"NAME" => GetMessage("COLOR_THEME"),
				"TYPES" => array("COMPONENT"=>"COLOR", "MODULE" => "SELECTBOX"),
				"VALUES" => array(
					array("VALUE" => "YELLOW", "COMPONENT_VALUE" => "#ffad00", "NAME" => GetMessage("COLOR_THEME_YELLOW")),
					array("VALUE" => "ORANGE", "COMPONENT_VALUE" => "#ff6d00", "NAME" => GetMessage("COLOR_THEME_ORANGE")),
					array("VALUE" => "RED", "COMPONENT_VALUE" => "#de002b", "NAME" => GetMessage("COLOR_THEME_RED")),
					array("VALUE" => "MAGENTA", "COMPONENT_VALUE" => "#b82945", "NAME" => GetMessage("COLOR_THEME_MAGENTA")),
					array("VALUE" => "ORCHID", "COMPONENT_VALUE" => "#d75cb6", "NAME" => GetMessage("COLOR_THEME_ORCHID")),
					array("VALUE" => "NAVY", "COMPONENT_VALUE" => "#006dca", "NAME" => GetMessage("COLOR_THEME_NAVY")),
					array("VALUE" => "BLUE", "COMPONENT_VALUE" => "#01aae3", "NAME" => GetMessage("COLOR_THEME_BLUE")), //#00aed7
					array("VALUE" => "GREEN_SEA", "COMPONENT_VALUE" => "#01b1af", "NAME" => GetMessage("COLOR_THEME_GREEN_SEA")),
					array("VALUE" => "GREEN", "COMPONENT_VALUE" => "#009f4f", "NAME" => GetMessage("COLOR_THEME_GREEN")),
					array("VALUE" => "IRISH_GREEN", "COMPONENT_VALUE" => "#6db900", "NAME" => GetMessage("COLOR_THEME_IRISH_GREEN")),
					array("VALUE" => "CUSTOM", "COMPONENT_VALUE" => "", "NAME" => GetMessage("COLOR_THEME_CUSTOM")),
				),
				"DEFAULT" => "NAVY",
			),
			array(
				"ID" => "CUSTOM_COLOR_THEME",
				"NAME" => GetMessage("CUSTOM_COLOR_THEME"),
				"TYPES" => array("COMPONENT"=>"COLOR", "MODULE" => "text"),
				"DEFAULT" => "01aae3",
			),
			array(
				"ID" => "BGCOLOR_THEME",
				"NAME" => GetMessage("BGCOLOR_THEME"),
				"TYPES" => array("COMPONENT"=>"COLOR", "MODULE" => "SELECTBOX"),
				"VALUES" => array(
					array("VALUE" => "LIGHT", "COMPONENT_VALUE" => "#f6f6f7", "NAME" => GetMessage("BGCOLOR_THEME_LIGHT")),
					array("VALUE" => "DARK", "COMPONENT_VALUE" => "#272a39", "NAME" => GetMessage("BGCOLOR_THEME_DARK")),
					array("VALUE" => "CUSTOM", "COMPONENT_VALUE" => "", "NAME" => GetMessage("COLOR_THEME_CUSTOM")),
				),
				"DEFAULT" => "LIGHT",
			),
			array(
				"ID" => "BGCOLOR_THEME_FOOTER_SIDE",
				"NAME" => GetMessage("BGCOLOR_THEME_FOOTER_SIDE"),
				"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
				"VALUES" => array(
					array("VALUE" => "FILL", "COMPONENT_VALUE" => "", "NAME" => GetMessage("YES_OPTION")),
					array("VALUE" => "NO_FILL", "COMPONENT_VALUE" => "", "NAME" => GetMessage("NO_OPTION")),
				),
				"DEFAULT" => "NO_FILL",
			),
			array(
				"ID" => "CUSTOM_BGCOLOR_THEME",
				"NAME" => GetMessage("CUSTOM_BGCOLOR_THEME"),
				"TYPES" => array("COMPONENT"=>"COLOR", "MODULE" => "text"),
				"DEFAULT" => "f6f6f7",
			),
			array(
				"ID" => "MENU_COLOR",
				"NAME" => GetMessage("MENU_COLOR"),
				"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
				"VALUES" => array(
					array("VALUE" => "COLORED", "COMPONENT_VALUE" => "", "NAME" => GetMessage("MENU_COLOR_COLORED")),
					array("VALUE" => "LIGHT", "COMPONENT_VALUE" => "", "NAME" => GetMessage("MENU_COLOR_LIGHT")),
					array("VALUE" => "DARK", "COMPONENT_VALUE" => "", "NAME" => GetMessage("MENU_COLOR_DARK")),
				),
				"DEFAULT" => "COLORED",
			),
		);

		if(!$onlyTemplate){
			$arTemplateOptions[] = array(
				"ID" => "LOGO_IMAGE",
				"NAME" => GetMessage("LOGO_IMAGE"),
				"TYPES" => array("COMPONENT" => "TEXT", "MODULE" => "file"),
				"DEFAULT" => serialize(array()),
				"THEME" => "N",
			);
			$arTemplateOptions[] = array(
				"ID" => "LOGO_IMAGE_SVG",
				"NAME" => GetMessage("LOGO_IMAGE_SVG"),
				"TYPES" => array(
					"MODULE" => "includefile",
				),
				"INCLUDEFILE" => "#SITE_DIR#include/logo_svg.php",
				"DEFAULT" => array(),
			);
			$arTemplateOptions[] = array(
				"ID" => "FAVICON_IMAGE",
				"NAME" => GetMessage("FAVICON_IMAGE"),
				"TYPES" => array("COMPONENT" => "TEXT", "MODULE" => "file"),
				"DEFAULT" => serialize(array()),
				"THEME" => "N",
			);
			$arTemplateOptions[] = array(
				"ID" => "APPLE_TOUCH_ICON_IMAGE",
				"NAME" => GetMessage("APPLE_TOUCH_ICON_IMAGE"),
				"TYPES" => array("COMPONENT" => "TEXT", "MODULE" => "file"),
				"DEFAULT" => serialize(array()),
				"THEME" => "N",
			);
			/*$arTemplateOptions[] = array(
				"ID" => "LOGO_IMAGE_FIXED",
				"NAME" => GetMessage("T_LOGO_IMAGE_FIXED"),
				"TYPES" => array("COMPONENT" => "TEXT", "MODULE" => "file"),
				"DEFAULT" => serialize(array()),
			);*/
			$arTemplateOptions[] = array(
				"ID" => "LOGO_IMAGE_EMAIL",
				"NAME" => GetMessage("T_LOGO_IMAGE_EMAIL"),
				"TYPES" => array("COMPONENT" => "TEXT", "MODULE" => "file"),
				"DEFAULT" => serialize(array()),
				"THEME" => "N",
			);
		}

		$arTemplateOptions[] = [
			"ID" => "HEAD_FIXED_CHECK",
			"NAME" => GetMessage("T_HEAD_FIXED_CHECK"),
			"TYPES" => [
				"COMPONENT" => "TEXT",
				"MODULE" => "SELECTBOX"
			],
			"VALUES" => [
				["VALUE" => "Y", "COMPONENT_VALUE" => "", "NAME" => GetMessage("YES")],
				["VALUE" => "N", "COMPONENT_VALUE" => "", "NAME" => GetMessage("NO")],
			],
			"DEFAULT" => "N"
		];

		$arTemplateOptions[] = [
			"ID" => "HEAD_MOBILE_CHECK",
			"NAME" => GetMessage("T_HEAD_MOBILE_CHECK"),
			"TYPES" => [
				"COMPONENT" => "TEXT",
				"MODULE" => "SELECTBOX"
			],
			"VALUES" => [
				["VALUE" => "Y", "COMPONENT_VALUE" => "", "NAME" => GetMessage("YES")],
				["VALUE" => "N", "COMPONENT_VALUE" => "", "NAME" => GetMessage("NO")],
			],
			"DEFAULT" => "N"
		];

		$arTemplateOptions = array_merge($arTemplateOptions, array(
				array(
					"ID" => "BASKET",
					"NAME" => GetMessage("BASKET"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "NORMAL", "COMPONENT_VALUE" => "", "NAME" => GetMessage("BASKET_NORMAL")),
						array("VALUE" => "FLY", "COMPONENT_VALUE" => "", "NAME" => GetMessage("BASKET_FLY")),
					),
					"DEFAULT" => "FLY",
				),
				array(
					"ID" => "BASKET_ECONOMY",
					"NAME" => GetMessage("BASKET_ECONOMY"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "Y", "COMPONENT_VALUE" => "", "NAME" => GetMessage("YES")),
						array("VALUE" => "N", "COMPONENT_VALUE" => "", "NAME" => GetMessage("NO")),
					),
					"DEFAULT" => "N",
				)
			)
		);

		$arTemplateOptions = array_merge($arTemplateOptions, array(
				array(
					"ID" => "TYPE_SKU",
					"NAME" => GetMessage("TYPE_SKU"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "TYPE_1", "COMPONENT_VALUE" => "", "NAME" => GetMessage("TYPE_1")),
						array("VALUE" => "TYPE_2", "COMPONENT_VALUE" => "", "NAME" => GetMessage("TYPE_2")),
					),
					"DEFAULT" => "TYPE_1",
				),
				array(
					"ID" => "DETAIL_PICTURE_MODE",
					"NAME" => GetMessage("DETAIL_PICTURE_MODE"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "IMG", "COMPONENT_VALUE" => "", "NAME" => GetMessage("DETAIL_PICTURE_MODE_IMG")),
						array("VALUE" => "POPUP", "COMPONENT_VALUE" => "", "NAME" => GetMessage("DETAIL_PICTURE_MODE_POPUP")),
						array("VALUE" => "MAGNIFIER", "COMPONENT_VALUE" => "", "NAME" => GetMessage("DETAIL_PICTURE_MODE_MAGNIFIER")),
					),
					"DEFAULT" => "POPUP",
				),
				array(
					"ID" => "TYPE_VIEW_FILTER",
					"NAME" => GetMessage("TYPE_VIEW_FILTER"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "VERTICAL", "COMPONENT_VALUE" => "", "NAME" => GetMessage("VERTICAL")),
						array("VALUE" => "HORIZONTAL", "COMPONENT_VALUE" => "", "NAME" => GetMessage("HORIZONTAL")),
						array("VALUE" => "COMPACT", "COMPONENT_VALUE" => "", "NAME" => GetMessage("COMPACT")),
					),
					"DEFAULT" => "VERTICAL",
				),
				array(
					"ID" => "USE_COMPARE_GROUP",
					"NAME" => GetMessage("T_USE_COMPARE_GROUP"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "Y", "COMPONENT_VALUE" => "", "NAME" => GetMessage("YES")),
						array("VALUE" => "N", "COMPONENT_VALUE" => "", "NAME" => GetMessage("NO")),
					),
					"DEFAULT" => "Y",
				),
				array(
					"ID" => "MOBILE_FILTER_COMPACT",
					"NAME" => GetMessage("MOBILE_FILTER_COMPACT"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "Y", "COMPONENT_VALUE" => "", "NAME" => GetMessage("YES")),
						array("VALUE" => "N", "COMPONENT_VALUE" => "", "NAME" => GetMessage("NO")),
					),
					"DEFAULT" => "N",
				),
				array(
					"ID" => "SHOW_TOTAL_SUMM",
					"NAME" => GetMessage("SHOW_TOTAL_SUMM"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "ALWAYS", "COMPONENT_VALUE" => "", "NAME" => GetMessage("SHOW_TOTAL_SUMM_ALWAYS")),
						array("VALUE" => "Y", "COMPONENT_VALUE" => "", "NAME" => GetMessage("SHOW_TOTAL_SUMM_CHANGE")),
						array("VALUE" => "N", "COMPONENT_VALUE" => "", "NAME" => GetMessage("NO")),
					),
					"DEFAULT" => "N",
				),
				array(
					"ID" => "USE_FAST_VIEW_PAGE_DETAIL",
					"NAME" => GetMessage("USE_FAST_VIEW_TITLE"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "FAST_VIEW_1", "COMPONENT_VALUE" => "", "NAME" => GetMessage("YES")),
						array("VALUE" => "N", "COMPONENT_VALUE" => "", "NAME" => GetMessage("NO")),
					),
					"DEFAULT" => "N",
				),
				array(
					"ID" => "CATALOG_PAGE_SECTIONS",
					"NAME" => GetMessage("T_CATALOG_PAGE_SECTIONS_TITLE"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "SECTIONS_1", "COMPONENT_VALUE" => "", "NAME" => GetMessage("T_CATALOG_PAGE_SECTIONS_1")),
						array("VALUE" => "SECTIONS_2", "COMPONENT_VALUE" => "", "NAME" => GetMessage("T_CATALOG_PAGE_SECTIONS_2")),
					),
					"DEFAULT" => "SECTIONS_1",
					"THEME" => "N",
				),
				array(
					"ID" => "CATALOG_PAGE_SUBSECTIONS",
					"NAME" => GetMessage("T_CATALOG_PAGE_SUBSECTIONS_TITLE"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "SECTION_1", "COMPONENT_VALUE" => "", "NAME" => GetMessage("T_CATALOG_PAGE_SUBSECTIONS_1")),
						array("VALUE" => "SECTION_2", "COMPONENT_VALUE" => "", "NAME" => GetMessage("T_CATALOG_PAGE_SUBSECTIONS_2")),
						array("VALUE" => "SECTION_3", "COMPONENT_VALUE" => "", "NAME" => GetMessage("T_CATALOG_PAGE_SUBSECTIONS_3")),
					),
					"DEFAULT" => "SECTION_1",
					"THEME" => "N",
				),
				array(
					"ID" => "CATALOG_PAGE_DETAIL",
					"NAME" => GetMessage("CATALOG_PAGE_DETAIL_TITLE"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "ELEMENT_1", "COMPONENT_VALUE" => "", "NAME" => GetMessage("CATALOG_PAGE_DETAIL_TAB")),
						array("VALUE" => "ELEMENT_2", "COMPONENT_VALUE" => "", "NAME" => GetMessage("CATALOG_PAGE_DETAIL_NOTAB")),
					),
					"DEFAULT" => "ELEMENT_1",
				),
				array(
					"ID" => "CHANGE_TITLE_ITEM",
					"NAME" => GetMessage("CHANGE_TITLE_ITEM"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "Y", "COMPONENT_VALUE" => "", "NAME" => GetMessage("YES")),
						array("VALUE" => "N", "COMPONENT_VALUE" => "", "NAME" => GetMessage("NO")),
					),
					"DEFAULT" => "N",
				),
				array(
					'ID' => 'CALCULATE_DELIVERY',
					'NAME' => GetMessage('CALCULATE_DELIVERY_TITLE'),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "NOT", "COMPONENT_VALUE" => "", "NAME" => GetMessage("CALCULATE_DELIVERY_NOT")),
						array("VALUE" => "ONLY_POPUP", "COMPONENT_VALUE" => "", "NAME" => GetMessage("CALCULATE_DELIVERY_ONLY_POPUP")),
						array("VALUE" => "WITH_PREVIEW", "COMPONENT_VALUE" => "", "NAME" => GetMessage("CALCULATE_DELIVERY_WITH_PREVIEW")),
					),
					'DEFAULT' => 'NOT',
					'THEME' => 'Y',
				),
				array(
					'ID' => 'EXPRESSION_FOR_CALCULATE_DELIVERY',
					'NAME' => GetMessage('EXPRESSION_FOR_CALCULATE_DELIVERY_TITLE'),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "text"),
					'DEFAULT' => GetMessage('EXPRESSION_FOR_CALCULATE_DELIVERY_DEFAULT'),
					'THEME' => 'N',
				),
				array(
					"ID" => "MENU_POSITION_MAIN",
					"NAME" => GetMessage("MENU_POSITION_MAIN"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "SHOW", "COMPONENT_VALUE" => "", "NAME" => GetMessage("YES")),
						array("VALUE" => "HIDE", "COMPONENT_VALUE" => "", "NAME" => GetMessage("NO")),
					),
					"DEFAULT" => "SHOW",
				),
				array(
					"ID" => "MENU_POSITION",
					"NAME" => GetMessage("MENU_POSITION"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "TOP", "COMPONENT_VALUE" => "", "NAME" => GetMessage("TOP_MENU_HOVER")),
						array("VALUE" => "LINE", "COMPONENT_VALUE" => "", "NAME" => GetMessage("LINE_MENU_HOVER")),
					),
					"DEFAULT" => "LINE",
				),
				array(
					"ID" => "MENU_TYPE_VIEW",
					"NAME" => GetMessage("MENU_TYPE_VIEW"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "HOVER", "COMPONENT_VALUE" => "", "NAME" => GetMessage("MENU_TYPE_VIEW_HOVER")),
						array("VALUE" => "BOTTOM", "COMPONENT_VALUE" => "", "NAME" => GetMessage("MENU_TYPE_VIEW_BOTTOM")),
					),
					"DEFAULT" => "HOVER",
				),
				array(
					"ID" => "VIEWED_TYPE",
					"NAME" => GetMessage("VIEWED_TYPE"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "LOCAL", "COMPONENT_VALUE" => "", "NAME" => GetMessage("VIEWED_TYPE_LOGIC_COOKIE")),
						array("VALUE" => "COMPONENT", "COMPONENT_VALUE" => "", "NAME" => GetMessage("VIEWED_TYPE_LOGIC_COMPONENT")),
					),
					"DEFAULT" => "COMPONENT",
				),
				array(
					"ID" => "STORES_SOURCE",
					"NAME" => GetMessage("STORES_SOURCE"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => (
						$arIsSiteWithStores[$arSite["ID"]] ?
							array(
								array("VALUE" => "STORES", "COMPONENT_VALUE" => "", "NAME" => GetMessage("STORES_SOURCE_STORES")),
								array("VALUE" => "IBLOCK", "COMPONENT_VALUE" => "", "NAME" => GetMessage("STORES_SOURCE_STORES_IBLOCK")),
							) :
							array(
								array("VALUE" => "IBLOCK", "COMPONENT_VALUE" => "", "NAME" => GetMessage("STORES_SOURCE_STORES_IBLOCK")),
							)
						),
					"DEFAULT" => "IBLOCK",
				),
				array(
					"ID" => "NLO_MENU",
					"NAME" => GetMessage("NLO_MENU"),
					"TYPES" => array("MODULE" => "CHECKBOX"),
					"VALUES" => (
						array(
							array("VALUE" => "Y", "NAME" => GetMessage("NLO_MENU_Y")),
							array("VALUE" => "N", "NAME" => GetMessage("NLO_MENU_N")),
						)
					),
					"DEFAULT" => "Y",
				),
				array(
					"ID" => "USE_LAZY_LOAD",
					"NAME" => GetMessage("USE_LAZY_LOAD_TITLE"),
					"TYPES" => array("MODULE" => "CHECKBOX"),
					"VALUES" => (
						array(
							array("VALUE" => "Y", "NAME" => GetMessage("USE_LAZY_LOAD_Y")),
							array("VALUE" => "N", "NAME" => GetMessage("USE_LAZY_LOAD_N")),
						)
					),
					"DEFAULT" => "N",
				),
				array(
					"ID" => "MOBILE_CATALOG_BREADCRUMBS",
					"NAME" => GetMessage("MOBILE_CATALOG_BREADCRUMBS"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "Y", "COMPONENT_VALUE" => "", "NAME" => GetMessage("YES")),
						array("VALUE" => "N", "COMPONENT_VALUE" => "", "NAME" => GetMessage("NO")),
					),
					"DEFAULT" => "N",
				),
				array(
					"ID" => "MOBILE_CATALOG_BLOCK_COMPACT",
					"NAME" => GetMessage("MOBILE_CATALOG_BLOCK_COMPACT"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "Y", "COMPONENT_VALUE" => "", "NAME" => GetMessage("YES")),
						array("VALUE" => "N", "COMPONENT_VALUE" => "", "NAME" => GetMessage("NO")),
					),
					"DEFAULT" => "N",
				),
				array(
					"ID" => "LEFT_BLOCK_CATALOG_ICONS",
					"NAME" => GetMessage("LEFT_BLOCK_CATALOG_ICONS"),
					"TYPES" => array("COMPONENT"=>"TEXT", "MODULE" => "SELECTBOX"),
					"VALUES" => array(
						array("VALUE" => "Y", "COMPONENT_VALUE" => "", "NAME" => GetMessage("YES")),
						array("VALUE" => "N", "COMPONENT_VALUE" => "", "NAME" => GetMessage("NO")),
					),
					"DEFAULT" => "N",
				),
			)
		);

		$arComponentOptions = array();
		$arModuleOptions = array(GetMessage("TEMPLATE_OPTIONS"));
		$arModuleOptions[] = array("THEME_SWITCHER", GetMessage("THEME_SWITCHER"), 'N', array('checkbox'));

		foreach($arTemplateOptions as $key => $arTemplateOption){
			$arModuleValues = array(strToLower($arTemplateOption["TYPES"]["MODULE"]), isset($arTemplateOption["INCLUDEFILE"]) ? array("INCLUDEFILE" => $arTemplateOption["INCLUDEFILE"]) : array());
			$useTheme = isset($arTemplateOption["THEME"]) && $arTemplateOption["THEME"] === "N" ? "N" : "Y";

			if($arTemplateOption["VALUES"] && is_array($arTemplateOption["VALUES"])){
				foreach($arTemplateOption["VALUES"] as $key => $value){
					$arModuleValues[1][$value["VALUE"]] = $value["NAME"];
				}
			}
			$arComponentValues = array();
			if($arTemplateOption["VALUES"] && is_array($arTemplateOption["VALUES"])){
				$arComponentValues = $arTemplateOption["VALUES"];
			}
			if($arTemplateOption["ID"] !== 'BANNER_WIDTH' && $arTemplateOption["ID"] !== 'STORES'){
				$arModuleOptions[] = array($arTemplateOption["ID"], $arTemplateOption["NAME"], $arTemplateOption["DEFAULT"], $arModuleValues);
			}
			$arComponentOptions[] = array("ID" => $arTemplateOption["ID"], "NAME" => $arTemplateOption["NAME"], "TYPE" => $arTemplateOption["TYPES"]["COMPONENT"], "VALUES" => $arComponentValues, "DEFAULT" => $arTemplateOption["DEFAULT"], "THEME" => $useTheme);
		}
		$arModuleOptions[] = array("NO_LOGO_BG", GetMessage("NO_LOGO_BG"), "N", array("checkbox"));
		$arModuleOptions[] = array("CUSTOM_FONT", GetMessage("T_GOOGLE_FONTS"), "", array("text", 50));
		$arModuleOptions[] = array("PHONE_MASK", GetMessage("PHONE_MASK"), "+7 (999) 999-99-99", array("text", 15));
		$arModuleOptions[] = array("VALIDATE_PHONE_MASK", GetMessage("VALIDATE_PHONE_MASK"), "^[+][7] [(][0-9]{3}[)] [0-9]{3}[-][0-9]{2}[-][0-9]{2}$", array("text", 50));
		$arModuleOptions[] = array("SHOW_LICENCE", GetMessage("SHOW_LICENCE_TITLE"), "N", array("checkbox"));
		$arModuleOptions[] = array("LICENCE_CHECKED", GetMessage("LICENCE_CHECKED_TITLE"), "N", array("checkbox"));
		$arModuleOptions[] = array("LICENCE_TEXT", GetMessage("LICENCE_TEXT_TITLE"), "", array("includefile", array("INCLUDEFILE" => "#SITE_DIR#include/licenses_text.php")));
		$arModuleOptions[] = array("SHOW_BREADCRUMBS_CATALOG_SUBSECTIONS", GetMessage("SHOW_BREADCRUMBS_CATALOG_SUBSECTIONS"), "Y", array("checkbox"));
		$arModuleOptions[] = array("SHOW_BREADCRUMBS_CATALOG_CHAIN", GetMessage("SHOW_BREADCRUMBS_CATALOG_CHAIN"), "H1", array("selectbox", array('H1' => GetMessage('SHOW_BREADCRUMBS_CATALOG_CHAIN_H1'), 'NAME' => GetMessage('SHOW_BREADCRUMBS_CATALOG_CHAIN_NAME'))));
		$arModuleOptions[] = array("SHOW_BASKET_PRINT", GetMessage("SHOW_BASKET_PRINT"), "Y", array("checkbox"));
		$arModuleOptions[] = array("SHOW_SECTION_DESCRIPTION", GetMessage("SHOW_SECTION_DESCRIPTION"), "BOTTOM", array("selectbox", array('TOP' => GetMessage('TOP_SECTION'), 'BOTTOM' => GetMessage('BOTTOM_SECTION'), 'BOTH' => GetMessage('BOTH_SECTION'))));

		$arFielDescription = array(
			"DESCRIPTION" => GetMessage("DESCRIPTION_SECTION")."(DESCRIPTION)",
			"UF_SECTION_DESCR" => GetMessage("SEO_DESCRIPTION_SECTION")."(UF_SECTION_DESCR)",

		);

		$arModuleOptions[] = array("TOP_SECTION_DESCRIPTION_POSITION", GetMessage("TOP_SECTION_DESCRIPTION"), "UF_SECTION_DESCR", array("selectbox", $arFielDescription));
		$arModuleOptions[] = array("BOTTOM_SECTION_DESCRIPTION_POSITION", GetMessage("BOTTOM_SECTION_DESCRIPTION"), "DESCRIPTION", array("selectbox", $arFielDescription));

		$arModuleOptions[] = array("MAX_DEPTH_MENU", GetMessage("MAX_DEPTH_MENU"), "4", array("selectbox", array(2=>2,3=>3,4=>4)));
		$arModuleOptions[] = array("CATALOG_IBLOCK_ID", GetMessage("CATALOG_IBLOCK_ID"), "", array("selectbox_iblock"));
		$arModuleOptions[] = array("DISCOUNT_PRICE", GetMessage("DISCOUNT_PRICE"), "", array("selectbox_price"));

		$arModuleOptions[] = array("HIDE_SITE_NAME_TITLE", GetMessage("HIDE_SITE_NAME_TITLE"), "N", array("checkbox"));
		$arModuleOptions[] = array("LOGIN_EQUAL_EMAIL", GetMessage("LOGIN_EQUAL_EMAIL"), "Y", array("checkbox"));

		$arModuleOptions[] = array("HIDE_IMAGES_IN_MENU", GetMessage("HIDE_IMAGES_IN_MENU"), "N", array("checkbox"));
		$arModuleOptions[] = array("SHOW_FIRST_SKU_PICTURE", GetMessage("T_SHOW_FIRST_SKU_PICTURE"), "N", array("checkbox"));
		$arModuleOptions[] = array("SHOW_DELAY", GetMessage("T_SHOW_DELAY"), "Y", array("checkbox"));
		$arModuleOptions[] = array("SHOW_COMPARE", GetMessage("T_SHOW_COMPARE"), "Y", array("checkbox"));

		$arModuleOptions[] = array("SHOW_OFFER", GetMessage("SHOW_OFFER_TITLE"), "N", array("checkbox"));
		$arModuleOptions[] = array("OFFER_CHECKED", GetMessage("OFFER_CHECKED_TITLE"), "N", array("checkbox"));
		$arModuleOptions[] = array("OFFER_TEXT", GetMessage("OFFER_TEXT_TITLE"), "", array("includefile", array("INCLUDEFILE" => "#SITE_DIR#include/offer_text.php")));
		$arModuleOptions[] = array('OFFER_NOTE', "note" => GetMessage("OFFER_NOTE"));
		$arModuleOptions[] = array("MAP_TYPE", GetMessage("T_MAP_TYPE"), 0, array("selectbox", array("Yandex", "Google")));
		$arModuleOptions[] = array("VIEW_TYPE_HIGHLOAD_PROP", GetMessage("T_VIEW_TYPE_HIGHLOAD_PROP"), "N", array("checkbox"));


		if(!$onlyTemplate){
			
			// scroll to top button options
			$arModuleOptions[] = GetMessage("GOOGLE_RECAPTCHA");
			$arModuleOptions[] = array("USE_GOOGLE_RECAPTCHA", GetMessage("USE_GOOGLE_RECAPTCHA_TITLE"), "N", array("checkbox"));
			$arModuleOptions[] = array("GOOGLE_RECAPTCHA_VERSION", GetMessage('GOOGLE_RECAPTCHA_VERSION_TITLE'), "2", array("selectbox", array('2' => GetMessage('GOOGLE_RECAPTCHA_VERSION2_TITLE'), '3' => GetMessage('GOOGLE_RECAPTCHA_VERSION3_TITLE'))));
			$arModuleOptions[] = array("GOOGLE_RECAPTCHA_PUBLIC_KEY", GetMessage("GOOGLE_RECAPTCHA_PUBLIC_KEY_TITLE"), "", array("text", 50));
			$arModuleOptions[] = array("GOOGLE_RECAPTCHA_PRIVATE_KEY", GetMessage("GOOGLE_RECAPTCHA_PRIVATE_KEY_TITLE"), "", array("text", 50));
			$arModuleOptions[] = array("GOOGLE_RECAPTCHA_MASK_PAGE", GetMessage("GOOGLE_RECAPTCHA_MASK_PAGE_TITLE"), "", array("textarea", 5, 80));
			$arModuleOptions[] = array("GOOGLE_RECAPTCHA_MIN_SCORE", GetMessage('GOOGLE_RECAPTCHA_MIN_SCORE_TITLE'), '0.5', 
				array("selectbox", 
					array(
						'0.1' => '0.1',
						'0.3' => '0.3',
						'0.5' => '0.5',
						'0.7' => '0.7',
						'0.9' => '0.9',
					)
				)
			);
			$arModuleOptions[] = array(
				"GOOGLE_RECAPTCHA_COLOR",
				GetMessage("GOOGLE_RECAPTCHA_COLOR_TITLE"),
				"LIGHT",
				array(
					"selectbox",
					array(
						'DARK' => GetMessage('GOOGLE_RECAPTCHA_COLOR_DARK_TITLE'),
						'LIGHT' => GetMessage('GOOGLE_RECAPTCHA_COLOR_LIGHT_TITLE'),
					),
				),
			);
			$arModuleOptions[] = array(
				"GOOGLE_RECAPTCHA_SIZE",
				GetMessage("GOOGLE_RECAPTCHA_SIZE_TITLE"),
				"NORMAL",
				array(
					"selectbox",
					array(
						'NORMAL' => GetMessage('GOOGLE_RECAPTCHA_SIZE_NORMAL_TITLE'),
						'COMPACT' => GetMessage('GOOGLE_RECAPTCHA_SIZE_COMPACT_TITLE'),
						'INVISIBLE' => GetMessage('GOOGLE_RECAPTCHA_SIZE_INVISIBLE_TITLE'),
					),
				),
			);
			$arModuleOptions[] = array("GOOGLE_RECAPTCHA_SHOW_LOGO", GetMessage("GOOGLE_RECAPTCHA_SHOW_LOGO_TITLE"), "Y", array("checkbox"));
			$arModuleOptions[] = array(
				"GOOGLE_RECAPTCHA_BADGE",
				GetMessage("GOOGLE_RECAPTCHA_BADGE_TITLE"),
				"LIGHT",
				array(
					"selectbox",
					array(
						'BOTTOMRIGHT' => GetMessage('GOOGLE_RECAPTCHA_BADGE_BOTTOMRIGHT_TITLE'),
						'BOTTOMLEFT' => GetMessage('GOOGLE_RECAPTCHA_BADGE_BOTTOMLEFT_TITLE'),
						'INLINE' => GetMessage('GOOGLE_RECAPTCHA_BADGE_INLINE_TITLE'),
					),
				),
			);
			$arModuleOptions[] = array('GOOGLE_RECAPTCHA_NOTE', "note" => GetMessage("GOOGLE_RECAPTCHA_NOTE_TEXT"));

			$arModuleOptions[] = GetMessage("SCROLLTOTOP_OPTIONS");
			$arModuleOptions[] = array(
				"SCROLLTOTOP_TYPE",
				GetMessage("SCROLLTOTOP_TYPE"),
				"ROUND_COLOR",
				array(
					"selectbox",
					array(
						'NONE' => GetMessage('SCROLLTOTOP_TYPE_NONE'),
						'ROUND_COLOR' => GetMessage('SCROLLTOTOP_TYPE_ROUND_COLOR'),
						'ROUND_GREY' => GetMessage('SCROLLTOTOP_TYPE_ROUND_GREY'),
						'ROUND_WHITE' => GetMessage('SCROLLTOTOP_TYPE_ROUND_WHITE'),
						'RECT_COLOR' => GetMessage('SCROLLTOTOP_TYPE_RECT_COLOR'),
						'RECT_GREY' => GetMessage('SCROLLTOTOP_TYPE_RECT_GREY'),
						'RECT_WHITE' => GetMessage('SCROLLTOTOP_TYPE_RECT_WHITE'),
					),
				),
			);
			$arModuleOptions[] = array(
				"SCROLLTOTOP_POSITION",
				GetMessage("SCROLLTOTOP_POSITION"),
				"PADDING",
				array(
					"selectbox",
					array(
						'TOUCH' => GetMessage('SCROLLTOTOP_POSITION_TOUCH'),
						'PADDING' => GetMessage('SCROLLTOTOP_POSITION_PADDING'),
					),
				),
			);

			// oneClick Buy
			$arModuleOptions[] = GetMessage("ONECLICKBUY_OPTIONS");
			$arModuleOptions[] = array("ONE_CLICK_BUY_CAPTCHA", GetMessage("ONE_CLICK_BUY_CAPTCHA_TITLE"), "N", array("checkbox"));
			$arModuleOptions[] = array("SHOW_ONECLICKBUY_ON_BASKET_PAGE", GetMessage("SHOW_ONECLICKBUY_ON_BASKET_PAGE_TITLE"), "Y", array("checkbox"));
			$arModuleOptions[] = array("ONECLICKBUY_SHOW_DELIVERY_NOTE", GetMessage("ONECLICKBUY_SHOW_DELIVERY_NOTE"), "N", array("checkbox"));
			$arModuleOptions[] = array("ONECLICKBUY_PERSON_TYPE", GetMessage("ONECLICKBUY_PERSON_TYPE"), "1", array("selectbox", array()));
			$arModuleOptions[] = array("ONECLICKBUY_DELIVERY", GetMessage("ONECLICKBUY_DELIVERY"), "2", array("selectbox", array()));
			$arModuleOptions[] = array("ONECLICKBUY_PAYMENT", GetMessage("ONECLICKBUY_PAYMENT"), "1", array("selectbox", array()));
			// $arModuleOptions[] = array("ONECLICKBUY_CURRENCY", GetMessage("ONECLICKBUY_CURRENCY"), "RUB", array("selectbox", array()));
			$arModuleOptions[] = array("ONECLICKBUY_PROPERTIES", GetMessage("ONECLICKBUY_PROPERTIES"),
				$arOneClickPropertiesList = "FIO,PHONE,EMAIL,COMMENT",
				array("multiselectbox",
					$arOneClickPropertiesList = array(
						"FIO" => GetMessage('ONECLICKBUY_PROPERTIES_FIO'),
						"PHONE" => GetMessage('ONECLICKBUY_PROPERTIES_PHONE'),
						"EMAIL" => GetMessage('ONECLICKBUY_PROPERTIES_EMAIL'),
						"COMMENT" => GetMessage('ONECLICKBUY_PROPERTIES_COMMENT'),
					),
				)
			);
			$arModuleOptions[] = array("ONECLICKBUY_REQUIRED_PROPERTIES", GetMessage("ONECLICKBUY_REQUIRED_PROPERTIES"), "FIO,PHONE", array("multiselectbox", $arOneClickPropertiesList));

			// main index page options
			$arModuleOptions[] = GetMessage("INDEXPAGE_OPTIONS");

			$arModuleOptions[] = array(
				"BANNER_ANIMATIONTYPE",
				GetMessage("BANNER_ANIMATIONTYPE"),
				"SLIDE_HORIZONTAL",
				array(
					"selectbox",
					array(
						"SLIDE_HORIZONTAL" => GetMessage("ANIMATION_SLIDE_HORIZONTAL"),
						"SLIDE_VERTICAL" => GetMessage("ANIMATION_SLIDE_VERTICAL"),
						"FADE" => GetMessage("ANIMATION_FADE"),
					),
				),
			);
			$arModuleOptions[] = array("BANNER_SLIDESSHOWSPEED", GetMessage("BANNER_SLIDESSHOWSPEED"), "5000", array("text", "5"));
			$arModuleOptions[] = array("BANNER_ANIMATIONSPEED", GetMessage("BANNER_ANIMATIONSPEED"), "600", array("text", "5"));
			// instagram main page options
			$arModuleOptions[] = GetMessage("INDEXPAGE_INSTAGRAM_OPTIONS");
			$arModuleOptions[] = array('INSTAGRAM_RKN_NOTE', "note" => GetMessage("INSTAGRAM_RKN_NOTE_TEXT"));
			$arModuleOptions[] = array("API_TOKEN_INSTAGRAMM", GetMessage("API_TOKEN_INSTAGRAMM"), "", array("text", "50"));
			$arModuleOptions[] = array("INSTAGRAMM_TITLE_BLOCK", GetMessage("INSTAGRAMM_TITLE_BLOCK"), "", array("text", "50", 'DEFAULT' => GetMessage('INSTAGRAMM_VALUE'),));
			$arModuleOptions[] = array("INSTAGRAMM_ITEMS_VISIBLE", GetMessage("INSTAGRAMM_ITEMS_VISIBLE"), "4", array("selectbox", array(
				'3' => 3,
				'4' => 4,
			)));

			// add to basket options
			$arModuleOptions[] = GetMessage("ADDTOBASKET_OPTIONS");
			$arModuleOptions[] = array("SHOW_BASKET_ONADDTOCART", GetMessage("SHOW_BASKET_ONADDTOCART"), "Y", array("checkbox"));
			$arModuleOptions[] = array("USE_PRODUCT_QUANTITY_LIST", GetMessage("USE_PRODUCT_QUANTITY_LIST"), "Y", array("checkbox"));
			$arModuleOptions[] = array("USE_PRODUCT_QUANTITY_DETAIL", GetMessage("USE_PRODUCT_QUANTITY_DETAIL"), "Y", array("checkbox"));
			$arModuleOptions[] = array(
				"BUYNOPRICEGGOODS",
				GetMessage("BUYNOPRICEGGOODS"),
				"NOTHING",
				array(
					"selectbox",
					array(
						"ORDER" => GetMessage("BUYNOPRICEGGOODS_ORDER"),
						"NOTHING" => GetMessage("BUYNOPRICEGGOODS_NOTHING"),
					),
				),
			);
			$arModuleOptions[] = array(
				"BUYMISSINGGOODS",
				GetMessage("BUYMISSINGGOODS"),
				"ADD",
				array(
					"selectbox",
					array(
						"ADD" => GetMessage("BUYMISSINGGOODS_ADD"),
						"ORDER" => GetMessage("BUYMISSINGGOODS_ORDER"),
						"SUBSCRIBE" => GetMessage("BUYMISSINGGOODS_SUBSCRIBE"),
						"NOTHING" => GetMessage("BUYMISSINGGOODS_NOTHING"),
					),
				),
			);


			// show quantity options
			$arModuleOptions[] = GetMessage("QUANTITY_OPTIONS");
			$arGroups = array();
			$DefaultGroupID = 0;
			$rsGroups = CGroup::GetList($by = "id", $order = "asc", array("ACTIVE" => "Y"));
			while($arItem = $rsGroups->Fetch()){
				$arGroups[$arItem["ID"]] = $arItem["NAME"];
				if($arItem["ANONYMOUS"] == "Y"){
					$DefaultGroupID = $arItem["ID"];
				}
			}
			$arGroupMultiselect = array("multiselectbox", $arGroups);
			$arModuleOptions[] = array("SHOW_QUANTITY_FOR_GROUPS", GetMessage("SHOW_QUANTITY_FOR_GROUPS"), $DefaultGroupID, $arGroupMultiselect);
			$arModuleOptions[] = array("SHOW_QUANTITY_COUNT_FOR_GROUPS", GetMessage("SHOW_QUANTITY_COUNT_FOR_GROUPS"), $DefaultGroupID, $arGroupMultiselect);
			$arModuleOptions[] = array('SHOW_QUANTITY_NOTE', "note" => GetMessage("SHOW_QUANTITY_NOTE"));
			$arModuleOptions[] = array("USE_WORD_EXPRESSION", GetMessage("USE_WORD_EXPRESSION"), "Y", array("checkbox"));
			$arModuleOptions[] = array("MAX_AMOUNT", GetMessage("MAX_AMOUNT"), "10", array("text", "4"));
			$arModuleOptions[] = array("MIN_AMOUNT", GetMessage("MIN_AMOUNT"), "2", array("text", "4"));

			// expression options
			$arModuleOptions[] = GetMessage("EXPRESSIONS_OPTIONS");
			$arModuleOptions[] = array("EXPRESSION_SUBSCRIBE_BUTTON", GetMessage("EXPRESSION_SUBSCRIBE_BUTTON"), GetMessage("EXPRESSION_SUBSCRIBE_BUTTON_DEFAULT"), array("text", "20"));
			$arModuleOptions[] = array("EXPRESSION_SUBSCRIBED_BUTTON", GetMessage("EXPRESSION_SUBSCRIBED_BUTTON"), GetMessage("EXPRESSION_SUBSCRIBED_BUTTON_DEFAULT"), array("text", "20"));
			$arModuleOptions[] = array("EXPRESSION_ORDER_BUTTON", GetMessage("EXPRESSION_ORDER_BUTTON"), GetMessage("EXPRESSION_ORDER_BUTTON_DEFAULT"), array("text", "20"));
			$arModuleOptions[] = array("EXPRESSION_ORDER_TEXT", GetMessage("EXPRESSION_ORDER_TEXT"), GetMessage("EXPRESSION_ORDER_TEXT_DEFAULT"), array("textarea", "2", "50"));
			$arModuleOptions[] = array("EXPRESSION_FOR_EXISTS", GetMessage("EXPRESSION_FOR_EXISTS"), GetMessage("EXPRESSION_FOR_EXISTS_DEFAULT"), array("text", "20"));
			$arModuleOptions[] = array("EXPRESSION_FOR_NOTEXISTS", GetMessage("EXPRESSION_FOR_NOTEXISTS"), GetMessage("EXPRESSION_FOR_NOTEXISTS_DEFAULT"), array("text", "20"));
			$arModuleOptions[] = array("EXPRESSION_FOR_MIN", GetMessage("EXPRESSION_FOR_MIN"), GetMessage("EXPRESSION_FOR_MIN_DEFAULT"), array("text", "20"));
			$arModuleOptions[] = array("EXPRESSION_FOR_MID", GetMessage("EXPRESSION_FOR_MID"), GetMessage("EXPRESSION_FOR_MID_DEFAULT"), array("text", "20"));
			$arModuleOptions[] = array("EXPRESSION_FOR_MAX", GetMessage("EXPRESSION_FOR_MAX"), GetMessage("EXPRESSION_FOR_MAX_DEFAULT"), array("text", "20"));
			// $arModuleOptions[] = array("EXPRESSION_FOR_FREE_DELIVERY", GetMessage("EXPRESSION_FOR_FREE_DELIVERY"), GetMessage("EXPRESSION_FOR_FREE_DELIVERY_DEFAULT"), array("text", "20"));
			$arModuleOptions[] = array("EXPRESSION_FOR_FAST_VIEW", GetMessage("EXPRESSION_FOR_FAST_VIEW"), GetMessage("EXPRESSION_FOR_FAST_VIEW_DEFAULT"), array("text", "20"));
			$arModuleOptions[] = array("EXPRESSION_FOR_EMPTY_PRICE", GetMessage("EXPRESSION_FOR_EMPTY_PRICE"), '', array("text", "20"));

			// show min prices order
			$arModuleOptions[] = GetMessage("MIN_ORDER_PRICES");
			$arModuleOptions[] = array("MIN_ORDER_PRICE", GetMessage("MIN_ORDER_PRICE"), 1000, array("text", "20"));
			$arModuleOptions[] = array("MIN_ORDER_PRICE_TEXT", GetMessage("MIN_ORDER_PRICE_TEXT"), GetMessage("MIN_ORDER_PRICE_TEXT_EXAMPLE"), array("textarea", "3", "70"));

			// show min prices order
			$arModuleOptions[] = GetMessage("SITE_PAGES");
			$arModuleOptions[] = array("CATALOG_PAGE_URL", GetMessage("CATALOG_PAGE_URL"), '#SITE_DIR#catalog/', array("text", "20"));
			$arModuleOptions[] = array("BASKET_PAGE_URL", GetMessage("BASKET_PAGE_URL"), '#SITE_DIR#basket/', array("text", "20"));
			$arModuleOptions[] = array("ORDER_PAGE_URL", GetMessage("ORDER_PAGE_URL"), '#SITE_DIR#order/', array("text", "20"));
			$arModuleOptions[] = array("PERSONAL_PAGE_URL", GetMessage("PERSONAL_PAGE_URL"), '#SITE_DIR#personal/', array("text", "20"));

			// yandex
			$arModuleOptions[] = GetMessage('COUNTERS_GOALS_BLOCK');
			$arModuleOptions[] = array('USE_YA_COUNTER', GetMessage('USE_YA_COUNTER_TITLE'), 'N', array('checkbox'));
			$arModuleOptions[] = array('YANDEX_COUNTER', GetMessage('YANDEX_COUNTER_CODE_TITLE'), '', array('textarea', '3', '50'));
			$arModuleOptions[] = array('YA_COUNTER_ID', GetMessage('YA_COUNTER_ID_TITLE'), '', array('text', '20'));
			$arModuleOptions[] = array('YANDEX_ECOMERCE', GetMessage('YANDEX_ECOMERCE_TITLE'), 'N', array('checkbox'));
			$arModuleOptions[] = array(
				'USE_FORMS_GOALS',
				GetMessage('USE_FORMS_GOALS_TITLE'),
				'COMMON',
				array(
					'selectbox',
					array(
						'NONE' => GetMessage('USE_FORMS_GOALS_NONE'),
						'COMMON' => GetMessage('USE_FORMS_GOALS_COMMON'),
						'SINGLE' => GetMessage('USE_FORMS_GOALS_SINGLE'),
					),
				),
			);
			$arModuleOptions[] = array('USE_BASKET_GOALS', GetMessage('USE_BASKET_GOALS_TITLE'), 'Y', array('checkbox'));
			$arModuleOptions[] = array('USE_1CLICK_GOALS', GetMessage('USE_1CLICK_GOALS_TITLE'), 'Y', array('checkbox'));
			$arModuleOptions[] = array('USE_FASTORDER_GOALS', GetMessage('USE_FASTORDER_GOALS_TITLE'), 'Y', array('checkbox'));
			$arModuleOptions[] = array('USE_FULLORDER_GOALS', GetMessage('USE_FULLORDER_GOALS_TITLE'), 'Y', array('checkbox'));
			$arModuleOptions[] = array('USE_DEBUG_GOALS', GetMessage('USE_DEBUG_GOALS_TITLE'), 'N', array('checkbox'));
			$arModuleOptions[] = array('GOALS_NOTE', 'note' => GetMessage('GOALS_NOTE_TITLE'));

			// google
			$arModuleOptions[] = array("GOOGLE_COUNTER", GetMessage("GOOGLE_COUNTER_TITLE"), '', array("textarea", "3", "50"));
			$arModuleOptions[] = array("GOOGLE_ECOMERCE", GetMessage("GOOGLE_ECOMERCE_TITLE"), 'N', array("checkbox"));
			$arModuleOptions[] = array("BASKET_ADD_EVENT", GetMessage("BASKET_ADD_EVENT_TITLE"), "addToCart", array("text", "20"));
			$arModuleOptions[] = array("BASKET_REMOVE_EVENT", GetMessage("BASKET_REMOVE_EVENT_TITLE"), "removeFromCart", array("text", "20"));
			$arModuleOptions[] = array("CHECKOUT_ORDER_EVENT", GetMessage("CHECKOUT_ORDER_EVENT_TITLE"), "checkout", array("text", "20"));
			$arModuleOptions[] = array("PURCHASE_ORDER_EVENT", GetMessage("PURCHASE_ORDER_EVENT_TITLE"), "gtm.dom", array("text", "20"));

			$arTabsForView = COption::GetOptionString(self::moduleID, 'TABS_FOR_VIEW_ASPRO_OPTIMUS', '');
			if($arTabsForView) {
				$arTabsForView = explode(',' , $arTabsForView);
			}

			$arTabs = array();
			foreach($arSites as $key => $arSite){
				if($arTabsForView) {
					if(in_array($arSite['ID'], $arTabsForView)) {
						$arTabs[] = array(
							"DIV" => "edit".($key+1),
							"TAB" => GetMessage("MAIN_OPTIONS", array("#SITE_NAME#" => $arSite["NAME"], "#SITE_ID#" => $arSite["ID"])),
							"ICON" => "settings",
							"TITLE" => GetMessage("MAIN_OPTIONS_TITLE"),
							"PAGE_TYPE" => "site_settings",
							"SITE_ID" => $arSite["ID"],
							"SITE_DIR" => str_replace('//', '/', '/'.$arSite['DIR']),
							"OPTIONS" => $arModuleOptions,
						);
					}
				} else {
					$arTabs[] = array(
						"DIV" => "edit".($key+1),
						"TAB" => GetMessage("MAIN_OPTIONS", array("#SITE_NAME#" => $arSite["NAME"], "#SITE_ID#" => $arSite["ID"])),
						"ICON" => "settings",
						"TITLE" => GetMessage("MAIN_OPTIONS_TITLE"),
						"PAGE_TYPE" => "site_settings",
						"SITE_ID" => $arSite["ID"],
						"SITE_DIR" => str_replace('//', '/', '/'.$arSite['DIR']),
						"OPTIONS" => $arModuleOptions,
					);
				}
			}
		}

		return array("TABS" => $arTabs, "TEMPLATE_OPTIONS" => $arComponentOptions);
	}

	public static function SetJSOptions(){
		global $APPLICATION, $TEMPLATE_OPTIONS, $THEME_SWITCHER, $STARTTIME;
		$MESS["MIN_ORDER_PRICE_TEXT"]=COption::GetOptionString(self::moduleID, "MIN_ORDER_PRICE_TEXT", GetMessage("MIN_ORDER_PRICE_TEXT_EXAMPLE"), SITE_ID);
		\Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID('basketitems-component-block');
			if(self::getShowBasket()):
				$APPLICATION->IncludeComponent( "bitrix:sale.basket.basket.line", "actual", Array(
					"PATH_TO_BASKET" => SITE_DIR."basket/",
					"PATH_TO_ORDER" => SITE_DIR."order/",
					"SHOW_DELAY" => "Y",
					"SHOW_PRODUCTS"=>"Y",
					"SHOW_EMPTY_VALUES" => "Y",
					"SHOW_NOTAVAIL" => "N",
					"SHOW_SUBSCRIBE" => "N",
					"SHOW_IMAGE" => "Y",
					"SHOW_PRICE" => "Y",
					"SHOW_SUMMARY" => "Y",
					"SHOW_NUM_PRODUCTS" => "Y",
					"SHOW_TOTAL_PRICE" => "Y",
					"HIDE_ON_BASKET_PAGES" => "N"
				) );
			endif;
		\Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID('basketitems-component-block', '');
		if(COption::GetOptionString(self::moduleID, "SHOW_LICENCE", 'N', SITE_ID) == "Y"){
			$MESS["LICENSES_TEXT"] = file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_DIR."include/licenses_text.php");
		}

		if(COption::GetOptionString(self::moduleID, "SHOW_OFFER", 'N', SITE_ID) == "Y"){
			$MESS["OFFER_TEXT"] = file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_DIR."include/offer_text.php");
		}
		$bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false); // is indexed yandex/google bot
		?>
		<?if(!$bIndexBot):?>
			<script type="text/javascript">
			BX.message(<?=CUtil::PhpToJSObject( $MESS, false )?>);

			var arOptimusOptions = ({
				"SITE_DIR" : "<?=SITE_DIR?>",
				"SITE_ID" : "<?=SITE_ID?>",
				"SITE_TEMPLATE_PATH" : "<?=SITE_TEMPLATE_PATH?>",
				"FORM" : ({
					"ASK_FORM_ID" : "ASK",
					"SERVICES_FORM_ID" : "SERVICES",
					"FEEDBACK_FORM_ID" : "FEEDBACK",
					"CALLBACK_FORM_ID" : "CALLBACK",
					"RESUME_FORM_ID" : "RESUME",
					"TOORDER_FORM_ID" : "TOORDER",
					"CHEAPER_FORM_ID" : "CHEAPER",
					"DELIVERY_FORM_ID" : "DELIVERY",
				}),
				"PAGES" : ({
					"FRONT_PAGE" : "<?=self::IsMainPage()?>",
					"BASKET_PAGE" : "<?=self::IsBasketPage()?>",
					"ORDER_PAGE" : "<?=self::IsOrderPage()?>",
					"PERSONAL_PAGE" : "<?=self::IsPersonalPage()?>",
					"CATALOG_PAGE" : "<?=self::IsCatalogPage()?>",
					"CATALOG_PAGE_URL" : "<?=(str_replace('#SITE_DIR#', SITE_DIR, \Bitrix\Main\Config\Option::get(self::moduleID, 'CATALOG_PAGE_URL', SITE_DIR.'catalog/', SITE_ID)));?>",
				}),
				"PRICES" : ({
					"MIN_PRICE" : "<?=trim(COption::GetOptionString(self::moduleID, "MIN_ORDER_PRICE", "1000", SITE_ID));?>",
				}),
				"THEME" : ({
					"THEME_SWITCHER" : "<?=strToLower(trim($THEME_SWITCHER))?>",
					"COLOR_THEME" : "<?=strToLower(trim($TEMPLATE_OPTIONS["COLOR_THEME"]["CURRENT_VALUE"]))?>",
					"CUSTOM_COLOR_THEME" : "<?=strToLower(trim($TEMPLATE_OPTIONS["CUSTOM_COLOR_THEME"]["CURRENT_VALUE"]))?>",
					"LOGO_IMAGE" : "<?=trim($TEMPLATE_OPTIONS["LOGO_IMAGE"]["CURRENT_IMG"])?>",
					"FAVICON_IMAGE" : "<?=trim($TEMPLATE_OPTIONS["FAVICON_IMAGE"]["CURRENT_IMG"])?>",
					"APPLE_TOUCH_ICON_IMAGE" : "<?=trim($TEMPLATE_OPTIONS["APPLE_TOUCH_ICON_IMAGE"]["CURRENT_IMG"])?>",
					"BANNER_WIDTH" : "<?=strToLower(trim($TEMPLATE_OPTIONS["BANNER_WIDTH"]["CURRENT_VALUE"]))?>",
					"BANNER_ANIMATIONTYPE" : "<?=trim(COption::GetOptionString(self::moduleID, "BANNER_ANIMATIONTYPE", "SLIDE_HORIZONTAL", SITE_ID));?>",
					"BANNER_SLIDESSHOWSPEED" : "<?=trim(COption::GetOptionString(self::moduleID, "BANNER_SLIDESSHOWSPEED", "5000", SITE_ID));?>",
					"BANNER_ANIMATIONSPEED" : "<?=trim(COption::GetOptionString(self::moduleID, "BANNER_ANIMATIONSPEED", "600", SITE_ID));?>",
					"HEAD" : ({
						"VALUE" : "<?=strToLower(trim($TEMPLATE_OPTIONS["HEAD"]["CURRENT_VALUE"]))?>",
						"MENU" : "<?=strToLower(trim($TEMPLATE_OPTIONS["HEAD"]["CURRENT_MENU"]))?>",
						"MENU_COLOR" : "<?=strToLower(trim($TEMPLATE_OPTIONS["HEAD"]["CURRENT_MENU_COLOR"]))?>",
						"HEAD_COLOR" : "<?=strToLower(trim($TEMPLATE_OPTIONS["HEAD"]["CURRENT_HEAD_COLOR"]))?>",
					}),
					"BASKET" : "<?=strToLower(trim($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"]))?>",
					"STORES" : "<?=strToLower(trim($TEMPLATE_OPTIONS["STORES"]["CURRENT_VALUE"]))?>",
					"STORES_SOURCE" : "<?=strToLower(trim($TEMPLATE_OPTIONS["STORES_SOURCE"]["CURRENT_VALUE"]))?>",
					"TYPE_SKU" : "<?=strToLower(trim($TEMPLATE_OPTIONS["TYPE_SKU"]["CURRENT_VALUE"]))?>",
					"TYPE_VIEW_FILTER" : "<?=strToLower(trim($TEMPLATE_OPTIONS["TYPE_VIEW_FILTER"]["CURRENT_VALUE"]))?>",
					"MOBILE_CATALOG_BLOCK_COMPACT" : "<?=strToLower(trim($TEMPLATE_OPTIONS["MOBILE_CATALOG_BLOCK_COMPACT"]["CURRENT_VALUE"]))?>",
					"SHOW_BASKET_ONADDTOCART" : "<?=trim(COption::GetOptionString(self::moduleID, "SHOW_BASKET_ONADDTOCART", "Y", SITE_ID))?>",
					"SHOW_BASKET_PRINT" : "<?=trim(COption::GetOptionString(self::moduleID, "SHOW_BASKET_PRINT", "Y", SITE_ID))?>",
					"SHOW_ONECLICKBUY_ON_BASKET_PAGE" : "<?=trim(COption::GetOptionString(self::moduleID, "SHOW_ONECLICKBUY_ON_BASKET_PAGE", "Y", SITE_ID))?>",
					"PHONE_MASK" : "<?=trim(COption::GetOptionString(self::moduleID, "PHONE_MASK", "+9 (999) 999-99-99", SITE_ID));?>",
					"VALIDATE_PHONE_MASK" : "<?=trim(COption::GetOptionString(self::moduleID, "VALIDATE_PHONE_MASK", "^[+][0-9] [(][0-9]{3}[)] [0-9]{3}[-][0-9]{2}[-][0-9]{2}$", SITE_ID));?>",
					"SCROLLTOTOP_TYPE" : "<?=trim(COption::GetOptionString(self::moduleID, "SCROLLTOTOP_TYPE", "ROUND_COLOR", SITE_ID))?>",
					"SCROLLTOTOP_POSITION" : "<?=trim(COption::GetOptionString(self::moduleID, "SCROLLTOTOP_POSITION", "PADDING", SITE_ID))?>",
					"MENU_POSITION" : "<?=strToLower(trim($TEMPLATE_OPTIONS["MENU_POSITION"]["CURRENT_VALUE"]))?>",
					"MENU_TYPE_VIEW" : "<?=strToLower(trim($TEMPLATE_OPTIONS["MENU_TYPE_VIEW"]["CURRENT_VALUE"]))?>",
					"SHOW_LICENCE" : "<?=COption::GetOptionString(self::moduleID, "SHOW_LICENCE", "N", SITE_ID)?>",
					"LICENCE_CHECKED" : "<?=COption::GetOptionString(self::moduleID, "LICENCE_CHECKED", "N", SITE_ID)?>",
					"SHOW_OFFER" : "<?=COption::GetOptionString(self::moduleID, "SHOW_OFFER", "N", SITE_ID)?>",
					"OFFER_CHECKED" : "<?=COption::GetOptionString(self::moduleID, "OFFER_CHECKED", "N", SITE_ID)?>",
					"DISCOUNT_PRICE" : "<?=COption::GetOptionString(self::moduleID, "DISCOUNT_PRICE", "", SITE_ID)?>",
					"DETAIL_PICTURE_MODE" : "<?=trim($TEMPLATE_OPTIONS["DETAIL_PICTURE_MODE"]["CURRENT_VALUE"])?>",
					"SHOW_TOTAL_SUMM" : "<?=trim($TEMPLATE_OPTIONS["SHOW_TOTAL_SUMM"]["CURRENT_VALUE"])?>",
					"CHANGE_TITLE_ITEM" : "<?=trim($TEMPLATE_OPTIONS["CHANGE_TITLE_ITEM"]["CURRENT_VALUE"])?>",
					"ONE_CLICK_BUY_CAPTCHA" :"<?=trim(COption::GetOptionString(self::moduleID, "ONE_CLICK_BUY_CAPTCHA", "N", SITE_ID))?>",
					"EMPTY_PRICE_TEXT" : "<?=trim(COption::GetOptionString(self::moduleID, "EXPRESSION_FOR_EMPTY_PRICE", "", SITE_ID))?>",
					'LOGIN_EQUAL_EMAIL': '<?=trim(COption::GetOptionString(self::moduleID, "LOGIN_EQUAL_EMAIL", "", SITE_ID))?>',
				}),
				"COUNTERS":({
					"YANDEX_COUNTER" : "<?=strlen(trim(COption::GetOptionString(self::moduleID, "YANDEX_COUNTER", false, SITE_ID)))?>",
					"YANDEX_ECOMERCE" : "<?=trim(COption::GetOptionString(self::moduleID, "YANDEX_ECOMERCE", false, SITE_ID))?>",
					"USE_YA_COUNTER" : "<?=trim(COption::GetOptionString(self::moduleID, "USE_YA_COUNTER", 'N', SITE_ID))?>",
					"YA_COUNTER_ID" : "<?=trim(COption::GetOptionString(self::moduleID, "YA_COUNTER_ID", '', SITE_ID))?>",
					"USE_FORMS_GOALS" : "<?=trim(COption::GetOptionString(self::moduleID, "USE_FORMS_GOALS", 'COMMON', SITE_ID))?>",
					"USE_BASKET_GOALS" : "<?=trim(COption::GetOptionString(self::moduleID, "USE_BASKET_GOALS", 'Y', SITE_ID))?>",
					"USE_1CLICK_GOALS" : "<?=trim(COption::GetOptionString(self::moduleID, "USE_1CLICK_GOALS", 'Y', SITE_ID))?>",
					"USE_FASTORDER_GOALS" : "<?=trim(COption::GetOptionString(self::moduleID, "USE_FASTORDER_GOALS", 'Y', SITE_ID))?>",
					"USE_FULLORDER_GOALS" : "<?=trim(COption::GetOptionString(self::moduleID, "USE_FULLORDER_GOALS", 'Y', SITE_ID))?>",
					"USE_DEBUG_GOALS" : "<?=trim(COption::GetOptionString(self::moduleID, "USE_DEBUG_GOALS", 'N', SITE_ID))?>",
					"GOOGLE_COUNTER" : "<?=strlen(trim(COption::GetOptionString(self::moduleID, "GOOGLE_COUNTER", false, SITE_ID)))?>",
					"GOOGLE_ECOMERCE" : "<?=trim(COption::GetOptionString(self::moduleID, "GOOGLE_ECOMERCE", false, SITE_ID))?>",
					"TYPE":{
						"ONE_CLICK":"<?=GetMessage("ONE_CLICK_BUY");?>",
						"QUICK_ORDER":"<?=GetMessage("QUICK_ORDER");?>",
					},
					"GOOGLE_EVENTS":{
						"ADD2BASKET": "<?=trim(COption::GetOptionString(self::moduleID, "BASKET_ADD_EVENT", "addToCart", SITE_ID))?>",
						"REMOVE_BASKET": "<?=trim(COption::GetOptionString(self::moduleID, "BASKET_REMOVE_EVENT", "removeFromCart", SITE_ID))?>",
						"CHECKOUT_ORDER": "<?=trim(COption::GetOptionString(self::moduleID, "CHECKOUT_ORDER_EVENT", "checkout", SITE_ID))?>",
						"PURCHASE": "<?=trim(COption::GetOptionString(self::moduleID, "PURCHASE_ORDER_EVENT", "gtm.dom", SITE_ID))?>",
					}
				}),
				"JS_ITEM_CLICK":({
					"precision" : 6,
					"precisionFactor" : Math.pow(10,6)
				})
			});

			$(document).ready(function(){
				$.extend( $.validator.messages, {
					required: BX.message('JS_REQUIRED'),
					email: BX.message('JS_FORMAT'),
					equalTo: BX.message('JS_PASSWORD_COPY'),
					minlength: BX.message('JS_PASSWORD_LENGTH'),
					remote: BX.message('JS_ERROR')
				});

				$.validator.addMethod(
					'regexp', function( value, element, regexp ){
						var re = new RegExp( regexp );
						return this.optional( element ) || re.test( value );
					},
					BX.message('JS_FORMAT')
				);

				$.validator.addMethod(
					'filesize', function( value, element, param ){
						return this.optional( element ) || ( element.files[0].size <= param )
					},
					BX.message('JS_FILE_SIZE')
				);

				$.validator.addMethod(
					'date', function( value, element, param ) {
						var status = false;
						if(!value || value.length <= 0){
							status = false;
						}
						else{
							// html5 date allways yyyy-mm-dd
							var re = new RegExp('^([0-9]{4})(.)([0-9]{2})(.)([0-9]{2})$');
							var matches = re.exec(value);
							if(matches){
								var composedDate = new Date(matches[1], (matches[3] - 1), matches[5]);
								status = ((composedDate.getMonth() == (matches[3] - 1)) && (composedDate.getDate() == matches[5]) && (composedDate.getFullYear() == matches[1]));
							}
							else{
								// firefox
								var re = new RegExp('^([0-9]{2})(.)([0-9]{2})(.)([0-9]{4})$');
								var matches = re.exec(value);
								if(matches){
									var composedDate = new Date(matches[5], (matches[3] - 1), matches[1]);
									status = ((composedDate.getMonth() == (matches[3] - 1)) && (composedDate.getDate() == matches[1]) && (composedDate.getFullYear() == matches[5]));
								}
							}
						}
						return status;
					}, BX.message('JS_DATE')
				);

				$.validator.addMethod(
					'extension', function(value, element, param){
						param = typeof param === 'string' ? param.replace(/,/g, '|') : 'png|jpe?g|gif';
						return this.optional(element) || value.match(new RegExp('.(' + param + ')$', 'i'));
					}, BX.message('JS_FILE_EXT')
				);

				$.validator.addMethod(
					'captcha', function( value, element, params ){
						return $.validator.methods.remote.call(this, value, element,{
							url: arOptimusOptions['SITE_DIR'] + 'ajax/check-captcha.php',
							type: 'post',
							data:{
								captcha_word: value,
								captcha_sid: function(){
									return $(element).closest('form').find('input[name="captcha_sid"]').val();
								}
							}
						});
					},
					BX.message('JS_ERROR')
				);

				$.validator.addMethod(
					'recaptcha', function(value, element, param){
						var id = $(element).closest('form').find('.g-recaptcha').attr('data-widgetid');
						if(typeof id !== 'undefined'){
							return grecaptcha.getResponse(id) != '';
						}
						else{
							return true;
						}
					}, BX.message('JS_RECAPTCHA_ERROR')
				);

				$.validator.addClassRules({
					'phone':{
						regexp: arOptimusOptions['THEME']['VALIDATE_PHONE_MASK']
					},
					'confirm_password':{
						equalTo: 'input[name="REGISTER\[PASSWORD\]"]',
						minlength: 6
					},
					'password':{
						minlength: 6
					},
					'inputfile':{
						extension: arOptimusOptions['THEME']['VALIDATE_FILE_EXT'],
						filesize: 5000000
					},
					'captcha':{
						captcha: ''
					},
					'recaptcha':{
						recaptcha: ''
					}
				});

				if(arOptimusOptions['THEME']['PHONE_MASK']){
					$('input.phone').inputmask('mask', {'mask': arOptimusOptions['THEME']['PHONE_MASK']});
				}

				jqmEd('feedback', arOptimusOptions['FORM']['FEEDBACK_FORM_ID']);
				jqmEd('ask', arOptimusOptions['FORM']['ASK_FORM_ID'], '.ask_btn');
				jqmEd('services', arOptimusOptions['FORM']['SERVICES_FORM_ID'], '.services_btn','','.services_btn');
				if($('.resume_send').length){
					$(document).on('click', '.resume_send', function(e) {	
						$("body").append("<span class='resume_send_wr' style='display:none;'></span>");
						jqmEd('resume', arOptimusOptions['FORM']['RESUME_FORM_ID'], '.resume_send_wr','', this);
						$("body .resume_send_wr").click();
						$("body .resume_send_wr").remove();
					})
				}
				jqmEd('callback', arOptimusOptions['FORM']['CALLBACK_FORM_ID'], '.callback_btn');
				//jqmEd('cheaper', arOptimusOptions['FORM']['CHEAPER_FORM_ID'], '.cheaper');

			});
			</script>
		<?endif;?>
		<?/*fix reset POST*/
		if($_SERVER["REQUEST_METHOD"]=="POST" && $_POST["color_theme"]){
			LocalRedirect($_SERVER["HTTP_REFERER"]);
		}?>
	<?}

	public static function checkAllowDelivery($summ, $currency){
		$ERROR = false;
		$min_price = \Bitrix\Main\Config\Option::get(self::moduleID, "MIN_ORDER_PRICE", 1000, SITE_ID);
		$error_text = "";
		if( $summ < $min_price ){
			$ERROR = true;
			$error_text = \Bitrix\Main\Config\Option::get(self::moduleID, "MIN_ORDER_PRICE_TEXT", GetMessage("MIN_ORDER_PRICE_TEXT_EXAMPLE") );
			$error_text = str_replace( '#PRICE#', SaleFormatCurrency($min_price,$currency), $error_text );
			if($currency)
				$error_text = str_replace( '#PRICE#', SaleFormatCurrency($min_price,$currency), $error_text );
			else
				$error_text = str_replace( '#PRICE#', $min_price, $error_text );
		}
		return $arError=array( "ERROR" => $ERROR, "TEXT" => $error_text );
	}

	public static function showMoreText( $text ){
		$arText = explode( "#MORE_TEXT#", $text);
		if( $arText[1] ){
			$str = $arText[0];
			$str .= '<div class="wrap_more_item">';
				$str .= '<div class="more_text_item">';
				$str .= $arText[1];
				$str .= '</div>';
				$str .= '<div class="open_more"><span class="text"><i class="arrow"></i><span class="pseudo">'.GetMessage("EXPAND_BLOCK").'</span></span></div>';
			$str .= '</div>';
		}else{
			$str = $text;
		}
		return $str;
	}

	public static function getShowBasket(){
		static $bShowBasket;
		if($bShowBasket === NULL)
		{
			$bShowBasket = !self::IsBasketPage() && !self::IsOrderPage();
		}
		return $bShowBasket;
	}

	public static function GetIndexPageBlocks($pageAbsPath, $pageBlocksPrefix, $pageBlocksDirName = 'page_blocks'){
    	$arResult = array();

    	if($pageAbsPath && $pageBlocksPrefix){
    		$pageAbsPath = str_replace('//', '//', $pageAbsPath).'/';
    		if(is_dir($pageBlocksAbsPath = str_replace('', '', $pageAbsPath.(strlen($pageBlocksDirName) ? $pageBlocksDirName : '')))){
    			if($arPageBlocks = glob($pageBlocksAbsPath.'/*.php')){
		    		foreach($arPageBlocks as $file){
						$file = str_replace('.php', '', basename($file));
						if(strpos($file, $pageBlocksPrefix) !== false){
							$arResult[$file] = $file;
						}
					}
    			}
    		}
    	}

    	return $arResult;
    }

    public static function GetComponentTemplatePageBlocks($templateAbsPath, $pageBlocksDirName = 'page_blocks'){
    	$arResult = array('SECTIONS' => array(), 'SUBSECTIONS' => array(), 'ELEMENTS' => array(), 'ELEMENT' => array());

    	if($templateAbsPath){
    		$templateAbsPath = str_replace('//', '//', $templateAbsPath).'/';
    		if(is_dir($pageBlocksAbsPath = str_replace('//', '/', $templateAbsPath.(strlen($pageBlocksDirName) ? $pageBlocksDirName : '')))){
    			if($arPageBlocks = glob($pageBlocksAbsPath.'/*.php')){
		    		foreach($arPageBlocks as $file){
						$file = str_replace('.php', '', basename($file));
						if(strpos($file, 'sections_') !== false){
							$arResult['SECTIONS'][$file] = $file;
						}
						elseif(strpos($file, 'section_') !== false){
							$arResult['SUBSECTIONS'][$file] = $file;
						}
						elseif(strpos($file, 'list_elements_') !== false){
							$arResult['ELEMENTS'][$file] = $file;
						}
						elseif(strpos($file, 'element_') !== false){
							$arResult['ELEMENT'][$file] = $file;
						}
						elseif(strpos($file, 'fast_view_') !== false){
							$arResult['FAST_VIEW_ELEMENT'][$file] = $file;
						}
						elseif(strpos($file, 'bigdata_') !== false){
							$arResult['BIGDATA'][$file] = $file;
						}
					}
    			}
    		}
    	}

    	return $arResult;
    }

    public static function GetComponentTemplatePageBlocksParams($arPageBlocks){
    	$arResult = array();

    	if($arPageBlocks && is_array($arPageBlocks)){
    		if(isset($arPageBlocks['SECTIONS']) && $arPageBlocks['SECTIONS'] && is_array($arPageBlocks['SECTIONS'])){
    			$arResult['SECTIONS_TYPE_VIEW'] = array(
					'PARENT' => 'BASE',
					'SORT' => 1,
					'NAME' => GetMessage('M_SECTIONS_TYPE_VIEW'),
					'TYPE' => 'LIST',
					'VALUES' => $arPageBlocks['SECTIONS'],
					'DEFAULT' => key($arPageBlocks['SECTIONS']),
				);
    		}
    		if(isset($arPageBlocks['SUBSECTIONS']) && $arPageBlocks['SUBSECTIONS'] && is_array($arPageBlocks['SUBSECTIONS'])){
    			$arResult['SECTION_TYPE_VIEW'] = array(
					'PARENT' => 'BASE',
					'SORT' => 1,
					'NAME' => GetMessage('M_SECTION_TYPE_VIEW'),
					'TYPE' => 'LIST',
					'VALUES' => $arPageBlocks['SUBSECTIONS'],
					'DEFAULT' => key($arPageBlocks['SUBSECTIONS']),
				);
    		}
    		if(isset($arPageBlocks['ELEMENTS']) && $arPageBlocks['ELEMENTS'] && is_array($arPageBlocks['ELEMENTS'])){
    			$arResult['SECTION_ELEMENTS_TYPE_VIEW'] = array(
					'PARENT' => 'BASE',
					'SORT' => 1,
					'NAME' => GetMessage('M_SECTION_ELEMENTS_TYPE_VIEW'),
					'TYPE' => 'LIST',
					'VALUES' => $arPageBlocks['ELEMENTS'],
					'DEFAULT' => key($arPageBlocks['ELEMENTS']),
				);
    		}
    		if(isset($arPageBlocks['ELEMENT']) && $arPageBlocks['ELEMENT'] && is_array($arPageBlocks['ELEMENT'])){
    			$arResult['ELEMENT_TYPE_VIEW'] = array(
					'PARENT' => 'BASE',
					'SORT' => 1,
					'NAME' => GetMessage('M_ELEMENT_TYPE_VIEW'),
					'TYPE' => 'LIST',
					'VALUES' => $arPageBlocks['ELEMENT'],
					'DEFAULT' => key($arPageBlocks['ELEMENT']),
				);
    		}
    	}

    	return $arResult;
    }

    protected static function IsComponentTemplateHasModuleSectionsPageBlocksParam($templateName, $arExtParams = array()){
    	global $TEMPLATE_OPTIONS;
    	$siteID = 's1';
    	if(isset($_REQUEST) && (isset($_REQUEST['src_site']) && $_REQUEST['src_site']))
    		$siteID = $_REQUEST['src_site'];
    	if(!$TEMPLATE_OPTIONS)
    		$TEMPLATE_OPTIONS = self::GetTemplateOptions($siteID);

    	$section_param = ((isset($arExtParams['SECTION']) && $arExtParams['SECTION']) ? $arExtParams['SECTION'] : 'SECTION');
    	$template_param = ((isset($arExtParams['OPTION']) && $arExtParams['OPTION']) ? $arExtParams['OPTION'] : strtoupper($templateName));
	    return $templateName && isset($TEMPLATE_OPTIONS[$template_param.'_PAGE_SECTIONS']);
    }

    protected static function IsComponentTemplateHasModuleSectionPageBlocksParam($templateName, $arExtParams = array()){
    	global $TEMPLATE_OPTIONS;
    	$siteID = 's1';
    	if(isset($_REQUEST) && (isset($_REQUEST['src_site']) && $_REQUEST['src_site']))
    		$siteID = $_REQUEST['src_site'];
    	if(!$TEMPLATE_OPTIONS)
    		$TEMPLATE_OPTIONS = self::GetTemplateOptions($siteID);

    	$section_param = ((isset($arExtParams['SECTION']) && $arExtParams['SECTION']) ? $arExtParams['SECTION'] : 'SECTION');
    	$template_param = ((isset($arExtParams['OPTION']) && $arExtParams['OPTION']) ? $arExtParams['OPTION'] : strtoupper($templateName));
	    return $templateName && isset($TEMPLATE_OPTIONS[$template_param.'_PAGE_SUBSECTIONS']);
    }

    protected static function IsComponentTemplateHasModuleElementsPageBlocksParam($templateName, $arExtParams = array()){
    	global $TEMPLATE_OPTIONS;
    	$siteID = 's1';
    	if(isset($_REQUEST) && (isset($_REQUEST['src_site']) && $_REQUEST['src_site']))
    		$siteID = $_REQUEST['src_site'];
    	if(!$TEMPLATE_OPTIONS)
    		$TEMPLATE_OPTIONS = self::GetTemplateOptions($siteID);

    	$section_param = ((isset($arExtParams['SECTION']) && $arExtParams['SECTION']) ? $arExtParams['SECTION'] : 'SECTION');
    	$template_param = ((isset($arExtParams['OPTION']) && $arExtParams['OPTION']) ? $arExtParams['OPTION'] : strtoupper($templateName));
	    return $templateName && isset($TEMPLATE_OPTIONS[$template_param.'_PAGE']);
    }

    protected static function IsComponentTemplateHasModuleElementPageBlocksParam($templateName, $arExtParams = array()){
    	global $TEMPLATE_OPTIONS;
    	$siteID = 's1';
    	if(isset($_REQUEST) && (isset($_REQUEST['src_site']) && $_REQUEST['src_site']))
    		$siteID = $_REQUEST['src_site'];
    	if(!$TEMPLATE_OPTIONS)
    		$TEMPLATE_OPTIONS = self::GetTemplateOptions($siteID);

    	$section_param = ((isset($arExtParams['SECTION']) && $arExtParams['SECTION']) ? $arExtParams['SECTION'] : 'SECTION');
    	$template_param = ((isset($arExtParams['OPTION']) && $arExtParams['OPTION']) ? $arExtParams['OPTION'] : strtoupper($templateName));
	    return $templateName && isset($TEMPLATE_OPTIONS[$template_param.'_PAGE_DETAIL']);
    }

    public static function AddComponentTemplateModulePageBlocksParams($templateAbsPath, &$arParams, $arExtParams = array()){
    	if($templateAbsPath && $arParams && is_array($arParams)){
    		$templateAbsPath = str_replace('//', '//', $templateAbsPath).'/';
    		$templateName = basename($templateAbsPath);
    		if(self::IsComponentTemplateHasModuleSectionsPageBlocksParam($templateName, $arExtParams)){
				$arParams['SECTIONS_TYPE_VIEW']['VALUES'] = array_merge(array('FROM_MODULE' => GetMessage('M_FROM_MODULE_PARAMS')), $arParams['SECTIONS_TYPE_VIEW']['VALUES']);
				$arParams['SECTIONS_TYPE_VIEW']['DEFAULT'] = 'FROM_MODULE';
			}
    		if(self::IsComponentTemplateHasModuleSectionPageBlocksParam($templateName, $arExtParams)){
				$arParams['SECTION_TYPE_VIEW']['VALUES'] = array_merge(array('FROM_MODULE' => GetMessage('M_FROM_MODULE_PARAMS')), $arParams['SECTION_TYPE_VIEW']['VALUES']);
				$arParams['SECTION_TYPE_VIEW']['DEFAULT'] = 'FROM_MODULE';
			}
			if(self::IsComponentTemplateHasModuleElementsPageBlocksParam($templateName, $arExtParams)){
    			$arParams['SECTION_ELEMENTS_TYPE_VIEW']['VALUES'] = array_merge(array('FROM_MODULE' => GetMessage('M_FROM_MODULE_PARAMS')), $arParams['SECTION_ELEMENTS_TYPE_VIEW']['VALUES']);
    			$arParams['SECTION_ELEMENTS_TYPE_VIEW']['DEFAULT'] = 'FROM_MODULE';
    		}
    		if(self::IsComponentTemplateHasModuleElementPageBlocksParam($templateName, $arExtParams)){
    			$arParams['ELEMENT_TYPE_VIEW']['VALUES'] = array_merge(array('FROM_MODULE' => GetMessage('M_FROM_MODULE_PARAMS')), $arParams['ELEMENT_TYPE_VIEW']['VALUES']);
    			$arParams['ELEMENT_TYPE_VIEW']['DEFAULT'] = 'FROM_MODULE';
    		}
    	}
    }

    public static function CheckComponentTemplatePageBlocksParams(&$arParams, $templateAbsPath, $pageBlocksDirName = 'page_blocks'){
    	$arPageBlocks = self::GetComponentTemplatePageBlocks($templateAbsPath, $pageBlocksDirName);

    	if(!isset($arParams['SECTIONS_TYPE_VIEW']) || !$arParams['SECTIONS_TYPE_VIEW'] || (!isset($arPageBlocks['SECTIONS'][$arParams['SECTIONS_TYPE_VIEW']]) && $arParams['SECTIONS_TYPE_VIEW'] !== 'FROM_MODULE')){
    		$arParams['SECTIONS_TYPE_VIEW'] = key($arPageBlocks['SECTIONS']);
    	}
    	if(!isset($arParams['SECTION_TYPE_VIEW']) || !$arParams['SECTION_TYPE_VIEW'] || (!isset($arPageBlocks['SUBSECTIONS'][$arParams['SECTION_TYPE_VIEW']]) && $arParams['SECTION_TYPE_VIEW'] !== 'FROM_MODULE')){
    		$arParams['SECTION_TYPE_VIEW'] = key($arPageBlocks['SUBSECTIONS']);
    	}
    	if(!isset($arParams['SECTION_ELEMENTS_TYPE_VIEW']) || !$arParams['SECTION_ELEMENTS_TYPE_VIEW'] || (!isset($arPageBlocks['ELEMENTS'][$arParams['SECTION_ELEMENTS_TYPE_VIEW']]) && $arParams['SECTION_ELEMENTS_TYPE_VIEW'] !== 'FROM_MODULE')){
    		$arParams['SECTION_ELEMENTS_TYPE_VIEW'] = key($arPageBlocks['ELEMENTS']);
    	}
    	if(!isset($arParams['ELEMENT_TYPE_VIEW']) || !$arParams['ELEMENT_TYPE_VIEW'] || (!isset($arPageBlocks['ELEMENT'][$arParams['ELEMENT_TYPE_VIEW']]) && $arParams['ELEMENT_TYPE_VIEW'] !== 'FROM_MODULE')){
    		$arParams['ELEMENT_TYPE_VIEW'] = key($arPageBlocks['ELEMENT']);
    	}
    }

    public static function Add2OptionCustomComponentTemplatePageBlocks(&$arOption, $templateAbsPath){
		if($arOption && isset($arOption['LIST'])){
			if($arPageBlocks = self::GetComponentTemplatePageBlocks($templateAbsPath)){
				foreach($arPageBlocks['ELEMENTS'] as $page => $value){
					if(!isset($arOption['LIST'][$page])){
						$arOption['LIST'][$page] = array(
							'TITLE' => $value,
							'HIDE' => 'Y',
							'IS_CUSTOM' => 'Y',
						);
					}
				}
				if(!$arOption['DEFAULT'] && $arOption['LIST']){
					$arOption['DEFAULT'] = key($arOption['LIST']);
				}
			}
		}
    }

    public static function Add2OptionCustomComponentTemplatePageBlocksElement(&$arOption, $templateAbsPath, $field = 'ELEMENT'){
		if($arOption && isset($arOption[3][1])){
			if($arPageBlocks = self::GetComponentTemplatePageBlocks($templateAbsPath)){
				foreach($arPageBlocks[$field] as $page => $value){
					if(!isset($arOption[3][1][strToUpper($page)])){
						$arOption[3][1][$page] = $value;
					}
				}

				if(!$arOption[2] && $arOption[3][1]){
					$arOption[2] = key($arOption[3][1]);
				}
			}
		}
    }

	public static function IsCompositeEnabled(){
		if($arHTMLCacheOptions = self::GetCompositeOptions()){
			if(method_exists('CHTMLPagesCache', 'isOn')){
				if (CHTMLPagesCache::isOn()){
					if(isset($arHTMLCacheOptions['AUTO_COMPOSITE']) && $arHTMLCacheOptions['AUTO_COMPOSITE'] === 'Y'){
						return 'AUTO_COMPOSITE';
					}
					else{
						return 'COMPOSITE';
					}
				}
			}
			else{
				if($arHTMLCacheOptions['COMPOSITE'] === 'Y'){
					return 'COMPOSITE';
				}
			}
		}
	
		return false;
	}

	public static function EnableComposite($auto = false, $arHTMLCacheOptions = array()){
		if(class_exists('CHTMLPagesCache')){
			if(method_exists('CHTMLPagesCache', 'GetOptions')){
				$arHTMLCacheOptions = is_array($arHTMLCacheOptions) ? $arHTMLCacheOptions : array();
				$arHTMLCacheOptions = array_merge(CHTMLPagesCache::GetOptions(), $arHTMLCacheOptions);
	
				$arHTMLCacheOptions['COMPOSITE'] = isset($arHTMLCacheOptions['COMPOSITE']) ? $arHTMLCacheOptions['COMPOSITE'] : 'Y';
				$arHTMLCacheOptions['AUTO_UPDATE'] = isset($arHTMLCacheOptions['AUTO_UPDATE']) ? $arHTMLCacheOptions['AUTO_UPDATE'] : 'Y'; // standart mode
				$arHTMLCacheOptions['AUTO_UPDATE_TTL'] = isset($arHTMLCacheOptions['AUTO_UPDATE_TTL']) ? $arHTMLCacheOptions['AUTO_UPDATE_TTL'] : '0'; // no ttl delay
				$arHTMLCacheOptions['AUTO_COMPOSITE'] = ($auto ? 'Y' : 'N'); // auto composite mode
	
				CHTMLPagesCache::SetEnabled(true);
				CHTMLPagesCache::SetOptions($arHTMLCacheOptions);
				bx_accelerator_reset();
			}
		}
	}

	public static function GetCompositeOptions(){
		if(class_exists('CHTMLPagesCache')){
			if(method_exists('CHTMLPagesCache', 'GetOptions')){
				return CHTMLPagesCache::GetOptions();
			}
		}
	
		return array();
	}

	public static function __AdmSettingsSaveOption_EX($module_id, $arOption){
		if(!is_array($arOption)){
			return false;
		}

		$arControllerOption = CControllerClient::GetInstalledOptions($module_id);
		if(isset($arControllerOption[$arOption[0]])){
			return false;
		}

		$name = $arOption[0];
		$val = $_REQUEST[$name];
		$siteID = end(explode("_", $arOption[0]));
		$name = substr($name, 0, (strlen($name)-strlen($siteID)-1));

		if(array_key_exists(4, $arOption) && $arOption[4] == 'Y'){
			if($arOption[3][0] == 'checkbox'){
				$val = 'N';
			}
			else{
				return false;
			}
		}

		if($arOption[3][0] == "checkbox" && $val != "Y"){
			$val = "N";
		}
		elseif($arOption[3][0] == "multiselectbox" || $arOption[3][0] == "selectbox_price"){
			$val = @implode(",", (array)$val);
		}
		elseif($arOption[3][0] == "file"){
			$val = $arValueDefault = serialize(array());
			if(
				isset($_REQUEST[$name.'_'.$siteID.'_del']) || 
				(isset($_FILES[$arOption[0]]) && strlen($_FILES[$arOption[0]]['tmp_name']['0'])) || 
				(isset($_REQUEST[$arOption[0]]) && $_REQUEST[$arOption[0]]['n0'])
			){
				$arValues =  self::unserialize(COption::GetOptionString($module_id, $name, $arValueDefault, $siteID));
				$arValues = (array)$arValues;
				foreach($arValues as $fileID){
					CFile::Delete($fileID);
				}
			}
			if(
				isset($_FILES[$arOption[0]]) && (strlen($_FILES[$arOption[0]]['tmp_name']['n0']) || strlen($_FILES[$arOption[0]]['tmp_name']['0'])) ||
				(isset($_REQUEST[$arOption[0]]) && ($_REQUEST[$arOption[0]]['n0'] || $_REQUEST[$arOption[0]]))
				
			){
				$arValues = array();
				if (isset($_FILES[$arOption[0]])) {
					$absFilePath = (strlen($_FILES[$arOption[0]]['tmp_name']['n0']) ? $_FILES[$arOption[0]]['tmp_name']['n0'] : $_FILES[$arOption[0]]['tmp_name']['0']);
					$arOriginalName = (strlen($_FILES[$arOption[0]]['name']['n0']) ? $_FILES[$arOption[0]]['name']['n0'] : $_FILES[$arOption[0]]['name']['0']);
				} else {
					$fp = isset($_REQUEST[$arOption[0]]['n0']) ? $_REQUEST[$arOption[0]]['n0'] : $_REQUEST[$arOption[0]][0];
					
					$absFilePath = $_SERVER['DOCUMENT_ROOT'].htmlspecialcharsbx($fp);
					$arOriginalName = strstr(array_pop(explode('/', $fp)), '.', true);
				}
				if(file_exists($absFilePath)){
					$arFile = CFile::MakeFileArray($absFilePath);
					$arFile['name'] = $arOriginalName; // for original file extension

					if($bIsIco = strpos($arOriginalName, '.ico') !== false){
						$script_files = COption::GetOptionString("fileman", "~script_files", "php,php3,php4,php5,php6,phtml,pl,asp,aspx,cgi,dll,exe,ico,shtm,shtml,fcg,fcgi,fpl,asmx,pht,py,psp,var");
						$arScriptFiles = explode(',', $script_files);
						if(($p = array_search('ico', $arScriptFiles)) !== false){
							unset($arScriptFiles[$p]);
						}
						$tmp = implode(',', $arScriptFiles);
						COption::SetOptionString("fileman", "~script_files", $tmp);
					}

					if($fileID = CFile::SaveFile($arFile, self::moduleID)){
						$arValues[] = $fileID;
					}

					if($bIsIco){
						COption::SetOptionString("fileman", "~script_files", $script_files);
					}
				}
				$val = serialize($arValues);
			}

			if(!isset($_FILES[$arOption[0]]) || (!strlen($_FILES[$arOption[0]]['tmp_name']['n0']) && !strlen($_FILES[$arOption[0]]['tmp_name']['0']) && !isset($_REQUEST[$name.'_'.$siteID.'_del']))){
					if((!isset($_REQUEST[$arOption[0]]) && (!$_REQUEST[$arOption[0]]['n0'] || !$_REQUEST[$arOption[0]]))){
						return;
					}
			}
		}
		if($name === 'FAVICON_IMAGE'){
			self::CopyFaviconToSiteDir($val, $siteID); //copy favicon for search bots
		}

		if($name == 'YA_COUNTER_ID' && strlen($val)){
			$val = str_replace('yaCounter', '', $val);
		}

		if(strpos($name, 'CUSTOM_COLOR_THEME') !== false || strpos($name, 'CUSTOM_BGCOLOR_THEME') !== false){
			$val = str_replace('#', '', $val);
			$val = strlen($val) ? $val : $arOption[2];
		}

		if($name == 'CUSTOM_FONT')
		{
			$val = str_replace('<', '', $val);
			$val = str_replace('>', '', $val);

			$path = \Bitrix\Main\Application::getDocumentRoot().'/bitrix/components/aspro/theme.optimus/css/user_font_'.$siteID.'.css';
			$content = '';
			if($val)
			{
				$string = str_replace(array('link href=', '&display=swap'), '', $val);
				$stringLength = strlen($string);
				$startLetter = strpos($string, '=');
				$string = substr($string, $startLetter + 1, $stringLength);
				$endLetter = strpos($string, ':');
				$string = ($endLetter ? substr($string, 0, $endLetter) : $string);
				$string = str_replace('" rel="stylesheet"', '', $string);
				$endLetter = strpos($string, '&amp');
				$string = ($endLetter ? substr($string, 0, $endLetter) : $string);
				$string = trim($string, '"');
				$content = "body,h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .popup-window,body div.bx-yandex-map,.fancybox-title, ul.menu .child a, button.button, input.button, .button,#basket-root .basket-item-info-name, #basket-root .basket-item-info-name-link, #basket-root .basket-items-list-header-filter-item, #basket-root .basket-item-property-value, #basket-root .basket-item-property-custom-value, #basket-root .basket-item-property-name, #basket-root .basket-item-property-custom-name, #basket-root .basket-item-price-current-text, #basket-root .basket-item-price-title, #basket-root .basket-item-amount-field-description, #basket-root .basket-coupon-block-field-description, #basket-root .basket-coupon-alert, #basket-root .basket-coupon-alert .close-link, #basket-root .basket-checkout-block-total-title, #basket-root .basket-checkout-block-total-description, #basket-root .basket-coupon-block-total-price-current, #basket-root .basket-coupon-block-total-price-difference, #basket-root .basket-coupon-block-total-price-old, #basket-root .basket-item-price-old-text, #basket-root .basket-item-price-difference, #basket-root .basket-item-property-scu-text .basket-item-scu-item-inner, #basket-root .basket-item-amount-filed, #basket-root .basket-item-block-amount.disabled .basket-item-amount-filed:hover, body .bx-soa-section-title, body #bx-soa-order div[class*=bx-sls] .form-control.dropdown-block .bx-ui-sls-container input, body .personal_wrapper .orders_wrapper .sale-order-title, .personal_wrapper .orders_wrapper .sale-order-list-container .sale-order-list-accomplished-title, .personal_wrapper .orders_wrapper .sale-order-list-container .sale-order-list-title, .personal_wrapper .orders_wrapper .sale-order-detail-general-item, .personal_wrapper .orders_wrapper .sale-order-list-payment-title, .personal_wrapper .orders_wrapper .sale-order-list-shipment-title, .personal_wrapper .orders_wrapper .row.col-md-12.col-sm-12 > a, .sale-order-list-status-restricted, .sale-order-list-status-alert, .sale-order-list-status-success, .sale-order-list-shipment-status-block, .sale-order-detail-about-order-title-element, .sale-order-detail-payment-options-title-element, .sale-order-detail-payment-options-shipment-composition-title-element, .sale-order-detail-payment-options-order-content-title-element, .sale-order-detail-general-item, .personal_wrapper .orders_wrapper .sale-order-list-button, .personal_wrapper .orders_wrapper .sale-order-detail-about-order-inner-container-repeat > a, .sale-order-detail-payment-options-methods-button-element, .sale-order-detail-payment-options-methods-button-element-new-window, .sale-order-detail-payment-options-methods-info-title-status-alert, .sale-order-detail-payment-options-methods-info-title-status-restricted, .sale-order-detail-payment-options-methods-info-title-status-success, .sale-order-detail-payment-options-info-order-number, .sale-order-detail-payment-options-info-total-price, .personal_wrapper .orders_wrapper .sale-order-detail-about-order-inner-container-name-detail, .sale-order-detail-about-order-inner-container-status-detail, .sale-order-detail-about-order-inner-container-price-detail, .personal_wrapper .sale-order-detail-payment-options-methods-info-title, .personal_wrapper .sale-order-detail-payment-options-methods-shipment-list-item-title, .personal_wrapper .sale-order-payment-change-payment-title, .personal_wrapper .sale-order-detail-total-payment-list-left-item, .personal_wrapper .sale-order-detail-total-payment-list-right-item, .sale-order-payment-change-status-alert, .sale-order-payment-change-status-restricted, .sale-order-payment-change-status-success, .personal_wrapper .orders_wrapper .sale-order-list-inner-row > div >a, .personal_wrapper .orders_wrapper .sale-order-list-change-payment, body .sale-order-detail-payment-options-methods-info-change-link, *{font-family: '".str_replace('+', ' ', $string)."', sans-serif;}";
			}
			\Bitrix\Main\IO\File::putFileContents($path, $content);
		}

		COption::SetOptionString($module_id, $name, $val, $arOption[1], $siteID);
	}

	public static  function CopyFaviconToSiteDir($arValue, $siteID = ''){
		if(($siteID)){
			$arValue =  self::unserialize($arValue);
			if($arValue[0]){
				$imageSrc = $_SERVER['DOCUMENT_ROOT'].CFile::GetPath($arValue[0]);
			}
			else{
				if($arTemplate = self::GetSiteTemplate($siteID)){
					$imageSrc = str_replace('//', '/', $arTemplate['PATH'].'/images/favicon.ico');
				}
			}
			$arSite = CSite::GetByID($siteID)->Fetch();


			$arFavExtFiles = array('ico', 'png', 'gif', 'bmp', 'jpg', 'svg');
			foreach( $arFavExtFiles as $favExt){
				$imageDest = $arSite['ABS_DOC_ROOT'].'/'.$arSite['DIR'].'/favicon.'.$favExt;
				if(file_exists($imageDest)){
					@unlink($imageDest);
				}
			}
			
			
			if(file_exists($imageSrc)){
				$file_ext = pathinfo($imageSrc, PATHINFO_EXTENSION);
				$fav_ext = $file_ext ? $file_ext : 'ico';
				@copy($imageSrc, $arSite['ABS_DOC_ROOT'].'/'.$arSite['DIR'].'/favicon.'.$fav_ext);
			}else{
				@copy($arSite['ABS_DOC_ROOT'].'/'.$arSite['DIR'].'/include/favicon.ico', $arSite['ABS_DOC_ROOT'].'/'.$arSite['DIR'].'/favicon.ico');
			}
		}
	}

	public static  function GetSiteTemplate($siteID = ''){
		$arTemplate = array();

		if(strlen($siteID)){
			$dbRes = CSite::GetTemplateList($siteID);
			while($arTemplate = $dbRes->Fetch()){
				if(!strlen($arTemplate['CONDITION'])){
					if(file_exists(($arTemplate['PATH'] = $_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/'.$arTemplate['TEMPLATE']))){
						break;
					}
					elseif(file_exists(($arTemplate['PATH'] = $_SERVER['DOCUMENT_ROOT'].'/local/templates/'.$arTemplate['TEMPLATE']))){
						break;
					}
				}
			}
		}

		return $arTemplate;
	}

	public static function __AdmSettingsDrawRow_EX($module_id, $Option, $siteID, $siteDir){
		$arControllerOption = CControllerClient::GetInstalledOptions($module_id);
		if(!is_array($Option)):?><tr class="heading"><td colspan="2"><?=$Option?></td></tr><?
		elseif(isset($Option["note"])):
			$name  = substr($Option[0], 0, (strlen($Option[0]) - strlen($siteID) - 1));
			if($name == 'GOALS_NOTE'){
				$FORMS_GOALS_LIST = '';
				if(CModule::IncludeModule('form')){
					if($siteID){
						if($arForms = COptimusCache::CForm_GetList($by = array('by' => 's_id', 'CACHE' => array('TAG' => 'forms')), $order = 'asc', array('SITE' => $siteID, 'SITE_EXACT_MATCH' => 'Y'), $is_filtered)
						){
							foreach($arForms as $arForm){
								$FORMS_GOALS_LIST .= $arForm['NAME'].' - <i>goal_webform_success_'.$arForm['ID'].'</i><br />';
							}
						}
					}
				}

				$Option['note'] = str_replace('#FORMS_GOALS_LIST#', $FORMS_GOALS_LIST, $Option['note']);
			}
		?>
			<tr data-optioncode="<?=$name?>">
				<td colspan="2" align="center">
					<?echo BeginNote('align="center"');?>
					<?=$Option["note"]?>
					<?echo EndNote();?>
				</td>
			</tr>
		<?
		else:
			$name  = substr($Option[0], 0, (strlen($Option[0])-strlen($siteID)-1));
			$val = COption::GetOptionString($module_id, $name, $Option[2], $siteID);
			$type = $Option[3];
			$disabled = array_key_exists(4, $Option) && $Option[4] == 'Y' ? ' disabled' : '';
			$sup_text = array_key_exists(5, $Option) ? $Option[5] : '';
			$style = "";
			if($name == "TOP_SECTION_DESCRIPTION_POSITION" || $name == "BOTTOM_SECTION_DESCRIPTION_POSITION")
			{
				$type_description = \Bitrix\Main\Config\Option::get(self::moduleID, "SHOW_SECTION_DESCRIPTION", "BOTTOM", $siteID );
				if($type_description != "BOTH")
					$style = "style='display:none;'";
			}
		?>
			<tr <?=$style;?> data-optioncode="<?=$name?>">
				<td<?if($type[0]=="multiselectbox" || $type[0]=="textarea" || $type[0]=="statictext" || $type[0]=="statichtml") echo ' class="adm-detail-valign-top"'?> width="50%"><?
					if($type[0]=="checkbox") echo "<label for='".htmlspecialcharsbx($Option[0])."'>".$Option[1]."</label>";
					else echo $Option[1];
					if (strlen($sup_text) > 0) { ?><span class="required"><sup><?=$sup_text?></sup></span><? }
				?></td>
				<td width="50%"><?
				if($type[0]=="checkbox"): ?><input type="checkbox" <?if(isset($arControllerOption[$Option[0]]))echo ' disabled title="'.GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT").'"';?> id="<?echo htmlspecialcharsbx($Option[0])?>" name="<?echo htmlspecialcharsbx($Option[0])?>" value="Y"<?if($val=="Y")echo" checked";?><?=$disabled?><?if($type[2]<>'') echo " ".$type[2]?>><?
				elseif($type[0]=="text" || $type[0]=="password"): ?><input type="<?echo $type[0]?>"<?if(isset($arControllerOption[$Option[0]]))echo ' disabled title="'.GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT").'"';?> size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($Option[0])?>"<?=$disabled?><?=($type[0]=="password"? ' autocomplete="off"':'')?>><?
				elseif($type[0]=="selectbox"):
					$arr = $type[1];
					if(!is_array($arr))
						$arr = array();
					$arr_keys = array_keys($arr);
					?><select name="<?echo htmlspecialcharsbx($Option[0])?>" <?if(isset($arControllerOption[$Option[0]]))echo ' disabled title="'.GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT").'"';?> <?=$disabled?>><?
						$count = count($arr_keys);
						for($j=0; $j<$count; $j++): ?><option value="<?echo $arr_keys[$j]?>"<?if($val==$arr_keys[$j])echo" selected"?>><?echo htmlspecialcharsbx($arr[$arr_keys[$j]])?></option><? endfor;
						?></select>
				<?elseif($type[0]=="selectbox_iblock"):

					\Bitrix\Main\Loader::includeModule('iblock');
					$rsIBlock=CIBlock::GetList(array("SORT"=>"ASC", "ID"=>"DESC"), array("LID"=>$siteID));
					$arIBlocks=array();
					while($arIBlock=$rsIBlock->Fetch()){
						$arIBlocks[$arIBlock["ID"]]["NAME"]="(".$arIBlock["ID"].") ".$arIBlock["NAME"]." [".$arIBlock["CODE"]."]";
						$arIBlocks[$arIBlock["ID"]]["CODE"]=$arIBlock["CODE"];
					}
					$arr_keys = array_keys($arIBlocks);
					?>
					<select name="<?echo htmlspecialcharsbx($Option[0])?>" <?if(isset($arControllerOption[$Option[0]]))echo ' disabled title="'.GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT").'"';?> <?=$disabled?>>
						<?if($arIBlocks){
							foreach($arIBlocks as $key => $arValue) {
								$selected="";
								if(!$val && $arValue["CODE"]=="aspro_optimus_catalog"){
									$selected="selected";
								}elseif($val && $val==$key){
									$selected="selected";
								}?>
								<option value="<?=$key;?>" <?=$selected;?>><?=htmlspecialcharsbx($arValue["NAME"]);?></option>
							<?}
						}else{?>
							<option value="">-</option>
						<?}?>
					</select><?
				elseif($type[0]=="selectbox_price"):

					\Bitrix\Main\Loader::includeModule('catalog');
					$arPrices = array();
					$rsPrice = CCatalogGroup::GetList(array("SORT" => "ASC"), array());
					while($arPrice = $rsPrice->GetNext())
					{
						$name = ($arPrice["NAME_LANG"] ? $arPrice["NAME_LANG"] : $arPrice["NAME"]);
						$arPrices[$arPrice["ID"]]["NAME"] = "(".$arPrice["ID"].") ".$name." [".$arPrice["XML_ID"]."]";
					}
					$arr_keys = array_keys($arPrices);
					$arr_val = explode(",",$val);
					?>
					<select name="<?echo htmlspecialcharsbx($Option[0])?>[]" multiple size="6" <?if(isset($arControllerOption[$Option[0]]))echo ' disabled title="'.GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT").'"';?> <?=$disabled?>>
						<?if($arPrices){
							foreach($arPrices as $key => $arValue) {
								$selected="";
								if($val && (in_array($key, $arr_val)))
									$selected="selected";
								?>
								<option value="<?=$key;?>" <?=$selected;?>><?=htmlspecialcharsbx($arValue["NAME"]);?></option>
							<?}
						}else{?>
							<option value="">-</option>
						<?}?>
					</select><?
				elseif($type[0]=="multiselectbox"):
					$arr = $type[1];
					if(!is_array($arr)) $arr = array();
					$arr_keys = array_keys($arr);
					$arr_val = explode(",",$val);
					?><select size="5" <?if(isset($arControllerOption[$Option[0]]))echo ' disabled title="'.GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT").'"';?> multiple name="<?echo htmlspecialcharsbx($Option[0])?>[]"<?=$disabled?>><?
						$count = count($arr_keys);
						for($j=0; $j<$count; $j++): ?><option value="<?echo $arr_keys[$j]?>"<?if(in_array($arr_keys[$j],$arr_val)) echo " selected"?>><?echo htmlspecialcharsbx($arr[$arr_keys[$j]])?></option><? endfor;
					?></select><?
				elseif($type[0]=="includefile"):
					if(!is_array($type[1]['INCLUDEFILE'])){
						$type[1]['INCLUDEFILE'] = array($type[1]['INCLUDEFILE']);
					}
					foreach($type[1]['INCLUDEFILE'] as $includefile){
						$includefile = str_replace('//', '/', str_replace('#SITE_DIR#', $siteDir.'/', $includefile));
						if(strpos($includefile, '#') === false){
							$template = (isset($type[1]['TEMPLATE']) && strlen($type[1]['TEMPLATE']) ? $type[1]['TEMPLATE'] : '');
							$href = (!strlen($includefile) ? "javascript:;" : "javascript: new BX.CAdminDialog({'content_url':'/bitrix/admin/public_file_edit.php?site=".$siteID."&bxpublic=Y&from=includefile&noeditor=Y&templateID=".$arTab['TEMPLATE']['ID']."&path=".$includefile."&lang=".LANGUAGE_ID."&template=".$template."&subdialog=Y&siteTemplateId=".$arTab['TEMPLATE']['ID']."','width':'1009','height':'503'}).Show();");
							?><a class="adm-btn" href="<?=$href?>" name="<?=htmlspecialcharsbx($name)."_".$siteID?>" title="<?=GetMessage('OPTIONS_EDIT_BUTTON_TITLE')?>"><?=GetMessage('OPTIONS_EDIT_BUTTON_TITLE')?></a>&nbsp;<?
						}
					}
				elseif($type[0]=="textarea"):?><textarea <?if(isset($arControllerOption[$Option[0]]))echo ' disabled title="'.GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT").'"';?> rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($Option[0])?>"<?=$disabled?>><?echo htmlspecialcharsbx($val)?></textarea><?
				elseif($type[0]=='file'):
					$val =  self::unserialize($val);
					$Option['MULTIPLE'] = 'N';
					if(strpos($Option[0], 'LOGO_IMAGE') !== false) {
						$arOption['WIDTH'] = 163;
						$arOption['HEIGHT'] = 36;
					}
					elseif(strpos($Option[0], 'FAVICON_IMAGE') !== false) {
						$arOption['WIDTH'] = 16;
						$arOption['HEIGHT'] = 16;
					}
					elseif(strpos($Option[0], 'APPLE_TOUCH_ICON_IMAGE') !== false) {
						$arOption['WIDTH'] = 180;
						$arOption['HEIGHT'] = 180;
					}
					self::__ShowFilePropertyField($Option[0], $Option, $val);
				elseif($type[0]=="statictext"): echo htmlspecialcharsbx($val);
				elseif($type[0]=="statichtml"): echo $val;
				endif;
				?></td>
			</tr>
		<?
		endif;
	}

	public static function __AdmSettingsDrawCustomRow($html){
		echo '<tr><td colspan="2">'.$html.'</td></tr>';
	}

	protected static function __ShowFilePropertyField($name, $arOption, $values){
		global $bCopy, $historyId;

		if(!is_array($values)){
			$values = array($values);
		}

		if($bCopy || empty($values)){
			$values = array('n0' => 0);
		}

		$optionWidth = $arOption['WIDTH'] ? $arOption['WIDTH'] : 200;
		$optionHeight = $arOption['HEIGHT'] ? $arOption['HEIGHT'] : 100;

		if($arOption['MULTIPLE'] == 'N'){
			foreach($values as $key => $val){
				if(is_array($val)){
					$file_id = $val['VALUE'];
				}
				else{
					$file_id = $val;
				}
				if($historyId > 0){
					echo CFileInput::Show($name.'['.$key.']', $file_id,
						array(
							'IMAGE' => $arOption['IMAGE'],
							'PATH' => 'Y',
							'FILE_SIZE' => 'Y',
							'DIMENSIONS' => 'Y',
							'IMAGE_POPUP' => 'Y',
							'MAX_SIZE' => array(
								'W' => $optionWidth,
								'H' => $optionHeight,
							),
						)
					);
				}
				else{
					echo CFileInput::Show($name.'['.$key.']', $file_id,
						array(
							'IMAGE' => $arOption['IMAGE'],
							'PATH' => 'Y',
							'FILE_SIZE' => 'Y',
							'DIMENSIONS' => 'Y',
							'IMAGE_POPUP' => 'Y',
							'MAX_SIZE' => array(
							'W' => $optionWidth,
							'H' => $optionHeight,
							),
						),
						array(
							'upload' => true,
							'medialib' => true,
							'file_dialog' => true,
							'cloud' => true,
							'del' => true,
							'description' => $arOption['WITH_DESCRIPTION'] == 'Y',
						)
					);
				}
				break;
			}
		}
		else{
			$inputName = array();
			foreach($values as $key => $val){
				if(is_array($val)){
					$inputName[$name.'['.$key.']'] = $val['VALUE'];
				}
				else{
					$inputName[$name.'['.$key.']'] = $val;
				}
			}
			if($historyId > 0){
				echo CFileInput::ShowMultiple($inputName, $name.'[n#IND#]',
					array(
						'IMAGE' => $arOption['IMAGE'],
						'PATH' => 'Y',
						'FILE_SIZE' => 'Y',
						'DIMENSIONS' => 'Y',
						'IMAGE_POPUP' => 'Y',
						'MAX_SIZE' => array(
							'W' => $optionWidth,
							'H' => $optionHeight,
						),
					),
				false);
			}
			else{
				echo CFileInput::ShowMultiple($inputName, $name.'[n#IND#]',
					array(
						'IMAGE' => $arOption['IMAGE'],
						'PATH' => 'Y',
						'FILE_SIZE' => 'Y',
						'DIMENSIONS' => 'Y',
						'IMAGE_POPUP' => 'Y',
						'MAX_SIZE' => array(
							'W' => $optionWidth,
							'H' => $optionHeight,
						),
					),
				false,
					array(
						'upload' => true,
						'medialib' => true,
						'file_dialog' => true,
						'cloud' => true,
						'del' => true,
						'description' => $arOption['WITH_DESCRIPTION'] == 'Y',
					)
				);
			}
		}
	}

	public static  function InsertCounters(&$html){
		$bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false);
		if(defined("ADMIN_SECTION") || $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest" || strpos($_SERVER['REQUEST_URI'], 'bitrix/') !== false || $bIndexBot || !defined("ASPRO_TEMPLATE_LOADED")){
			return;
		}

		define("LOAD_COUNTERS", true);
		global $APPLICATION;
		if(defined("LOAD_COUNTERS")  && (strpos($APPLICATION->GetCurPage(),'personal')===false)){
			$counter = $yandex_counter = $google_counter = '';

			$useYACounter = \Bitrix\Main\Config\Option::get(self::moduleID, "USE_YA_COUNTER", 'N', SITE_ID);
			if($useYACounter == 'Y'){
				$yandex_counter = \Bitrix\Main\Config\Option::get(self::moduleID, "YANDEX_COUNTER", false, SITE_ID);
			}
			if($yandex_counter){
				$counter .= $yandex_counter;
			}

			$google_counter = \Bitrix\Main\Config\Option::get(self::moduleID, "GOOGLE_COUNTER", false, SITE_ID);
			if( $google_counter && $google_ecomerce=="Y" ){
				$counter1 .=$google_counter;
				$html = str_replace( '<body id="main">', '<body id="main">'.$counter1, $html );
			}
			if($google_counter){
				$counter .= $google_counter;
			}

			if(strlen($counter)){
				$arParts = explode('</body>', $html);
				if(count($arParts) > 1){
					$arParts[0] .= $counter;
					$html = implode('</body>', $arParts);
				}
			}
		}
	}

	public static function clearBasketCacheHandler($orderID, $arFields, $arParams = array()){
		COptimusCache::ClearCacheByTag('sale_basket');
		unset($_SESSION['ASPRO_BASKET_COUNTERS'][SITE_ID]);
		if(isset($arFields) && $arFields)
		{
			if(isset($arFields["ID"]) && $arFields["ID"])
			{
				\Bitrix\Main\Loader::includeModule("sale");
				global $USER;
				$USER_ID = ($USER_ID = self::GetUserID()) ? $USER_ID : 0;
				$arUser = $arUser = COptimusCache::CUser_GetList(array("SORT" => "ASC", "CACHE" => array("MULTI" => "N", "TAG" => COptimusCache::GetUserCacheTag($USER_ID))), array("ID" => $USER_ID), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
				if(!$arUser["PERSONAL_PHONE"])
				{
					$rsOrder = CSaleOrderPropsValue::GetList(array(), array("ORDER_ID" => $arFields["ID"]));
					$arOrderProps = array();
					while($item = $rsOrder->Fetch())
					{
						$arOrderProps[$item["CODE"]] = $item;
					}
					if(isset($arOrderProps["PHONE"]) && $arOrderProps["PHONE"] && (isset($arOrderProps["PHONE"]["VALUE"]) && $arOrderProps["PHONE"]["VALUE"]))
					{
						$user = new CUser;
						$fields = Array(
							"PERSONAL_PHONE" => $arOrderProps["PHONE"]["VALUE"],
						);
						$user->Update($arUser["ID"], $fields);
					}

				}
			}
		}
	}

	public static function OnBeforeUserUpdateHandler(&$arFields){
		$bTmpUser = false;

		$bAdminSection = (defined('ADMIN_SECTION') && ADMIN_SECTION === true);

		if(strlen($arFields["NAME"]))
			$arFields["NAME"] = trim($arFields["NAME"]);

		$siteID = SITE_ID;

		if($bAdminSection)
	    {
	    	// include CMainPage
	        require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/mainpage.php");
	        // get site_id by host
	        $CMainPage = new \CMainPage();
	        $siteID = $CMainPage->GetSiteByHost();
	        if(!$siteID)
	            $siteID = "s1";

			$sChangeLogin = COption::GetOptionString(OPTIMUS_MODULE_ID, 'LOGIN_EQUAL_EMAIL', 'Y', $siteID);
        }
		else
		{
			$sChangeLogin = COption::GetOptionString(OPTIMUS_MODULE_ID, 'LOGIN_EQUAL_EMAIL', 'Y', $siteID);
		}

		if(strlen($arFields["NAME"]))
			$arFields["NAME"] = trim($arFields["NAME"]);
		if(strlen($arFields["NAME"]) && !strlen($arFields["LAST_NAME"]) && !strlen($arFields["SECOND_NAME"])){
			$arName = explode(' ', $arFields["NAME"]);

			if($arName){
				$arFields["NAME"] = "";
				$arFields["SECOND_NAME"] = "";
				foreach($arName as $i => $name){
					if(!$i){
						$arFields["LAST_NAME"] = $name;
					}
					else{
						if(!strlen($arFields["NAME"])){
							$arFields["NAME"] = $name;
						}
						elseif(!strlen($arFields["SECOND_NAME"])){
							$arFields["SECOND_NAME"] = $name;
						}
					}
				}
			}
		}
		if($_REQUEST["confirmorder"]=="Y"  && !strlen($arFields["SECOND_NAME"]) && $_REQUEST["ORDER_PROP_1"]){
			$arNames = explode(' ', $_REQUEST["ORDER_PROP_1"]);
			if($arNames[2]){
				$arFields["SECOND_NAME"]=$arNames[2];
			}
		}

		if(isset($_REQUEST["soa-action"]) && $_REQUEST["soa-action"] == "saveOrderAjax") // set correct phone in user field
		{
			$arPhoneProp = CSaleOrderProps::GetList(
				array('SORT' => 'ASC'),
				array(
						'PERSON_TYPE_ID' => $_REQUEST['PERSON_TYPE'],
						'IS_PHONE' => 'Y',
					),
				false,
				false,
				array()
			)->fetch();
			if($arPhoneProp)
			{
				if($_REQUEST['ORDER_PROP_'.$arPhoneProp['ID']])
				{
					$arFields["PERSONAL_PHONE"] = $_REQUEST['ORDER_PROP_'.$arPhoneProp['ID']];
				}
			}
		}

		if(strlen($arFields["EMAIL"]))
		{
			if($sChangeLogin != "N")
			{
				$bEmailError = false;

				if(\Bitrix\Main\Config\Option::get('main', 'new_user_email_uniq_check', 'N') == 'Y')
				{
					$rsUser = CUser::GetList($by = "ID", $order = "ASC", array("=EMAIL" => $arFields["EMAIL"], "!ID" => $arFields["ID"]));
					if(!$bEmailError = intval($rsUser->SelectedRowsCount()) > 0)
					{
						$rsUser = CUser::GetList($by = "ID", $order = "ASC", array("LOGIN_EQUAL" => $arFields["EMAIL"], "!ID" => $arFields["ID"]));
						$bEmailError = intval($rsUser->SelectedRowsCount()) > 0;
					}
				}

				if($bEmailError){
					global $APPLICATION;
					$APPLICATION->throwException(GetMessage("EMAIL_IS_ALREADY_EXISTS", array("#EMAIL#" => $arFields["EMAIL"])));
					return false;
				}
				else{
					// !admin
					if (!isset($GLOBALS["USER"]) || !is_object($GLOBALS["USER"])){
						$bTmpUser = True;
						$GLOBALS["USER"] = new \CUser;
					}

					if($bAdminSection)
					{
						if(isset($arFields['ID']) && $arFields['ID'])
						{
							if(!in_array(1, CUser::GetUserGroup($arFields['ID'])))
								$arFields['LOGIN'] = $arFields['EMAIL'];
						}
						elseif(isset($arFields['GROUP_ID']) && $arFields['GROUP_ID'])
						{
							$arUserGroups = array();
							$arTmpGroups = (array)$arFields['GROUP_ID'];
							foreach($arTmpGroups as $arGroup)
							{
								if(is_array($arGroup))
									$arUserGroups[] = $arGroup['GROUP_ID'];
								else
									$arUserGroups[] = $arGroup;
							}

							if(count(array_intersect($arUserGroups, array(1)))<=0)
								$arFields['LOGIN'] = $arFields['EMAIL'];
						}
						else
							$arFields['LOGIN'] = $arFields['EMAIL'];
					}
					else
					{
						if(!$GLOBALS['USER']->IsAdmin())
							$arFields["LOGIN"] = $arFields["EMAIL"];
					}
				}
			}
			else
			{
				if(!$arFields["LOGIN"] || $arFields["LOGIN"] == 1)
				{
					$newLogin = $arFields['EMAIL'];
					$pos = strpos($newLogin, '@');
					if ($pos !== false)
						$newLogin = substr($newLogin, 0, $pos);

					if (strlen($newLogin) > 47)
						$newLogin = substr($newLogin, 0, 47);

					if (strlen($newLogin) < 3)
						$newLogin .= '_';

					if (strlen($newLogin) < 3)
						$newLogin .= '_';
					$arFields["LOGIN"] = $newLogin;
				}
			}
		}

		if ($bTmpUser)
			unset($GLOBALS["USER"]);

		return $arFields;
	}

	public static function GetYearsItems($iblock_id){
		$arYears=array();
		$rsItems=CIBlockElement::GetList(array(), array("IBLOCK_ID" => $iblock_id, "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y"), false, false, array("ID", "DATE_ACTIVE_FROM"));
		while($arItem=$rsItems->Fetch()){
			if($arItem["DATE_ACTIVE_FROM"]){
				$date = explode(' ', $arItem["DATE_ACTIVE_FROM"]);
				$date = $date[0];
				$date = explode('.', $date);
				$arYears[$date[2]] = $date[2];
			}
		}
		return $arYears;
	}

	public static function removeDirectory($dir){
		if($objs = glob($dir."/*")){
			foreach($objs as $obj){
				if(is_dir($obj)){
					self::removeDirectory($obj);
				}
				else{
					if(!unlink($obj)){
						if(chmod($obj, 0777)){
							unlink($obj);
						}
					}
				}
			}
		}
		if(!rmdir($dir)){
			if(chmod($dir, 0777)){
				rmdir($dir);
			}
		}
	}

    public static function inputClean($input, $sql = false){
       /* $input = htmlentities($input, ENT_QUOTES, LANG_CHARSET);
        if(get_magic_quotes_gpc ())
        {
            $input = stripslashes ($input);
        }
        if ($sql)
        {
            $input = mysql_real_escape_string ($input);
        }
        $input = strip_tags($input);
        $input=str_replace ("\n"," ", $input);
        $input=str_replace ("\r","", $input); */
        return $input;
    }

    public static function getBasketCounters(){
    	global $USER;
    	$USER_ID = ($USER_ID = self::GetUserID()) ? $USER_ID : 0;
    	$arResult = false;

    	if(isset($_SESSION['ASPRO_BASKET_COUNTERS'][SITE_ID])){
    		if(!is_array($_SESSION['ASPRO_BASKET_COUNTERS'][SITE_ID]) || (is_array($_SESSION['ASPRO_BASKET_COUNTERS'][SITE_ID]) && count($_SESSION['ASPRO_BASKET_COUNTERS'][SITE_ID]) && !isset($_SESSION['ASPRO_BASKET_COUNTERS'][SITE_ID][$USER_ID]))){
    			unset($_SESSION['ASPRO_BASKET_COUNTERS'][SITE_ID]);
    		}
    		else{
		    	$arResult = $_SESSION['ASPRO_BASKET_COUNTERS'][SITE_ID][$USER_ID];
    		}
    	}

    	if(!$arResult || !is_array($arResult)){
    		// set default value
    		$arResult = array('READY' => array('COUNT' => 0, 'TITLE' => '', 'HREF' => SITE_DIR.'basket/'), 'DELAY' => array('COUNT' => 0, 'TITLE' => '', 'HREF' => SITE_DIR.'basket/#delayed'), 'COMPARE' => array('COUNT' => 0, 'TITLE' => GetMessage('COMPARE_BLOCK'), 'HREF' => SITE_DIR.'catalog/compare.php'), 'PERSONAL' => array('ID' => $USER_ID, 'SRC' => '', 'TITLE' => GetMessage("USER_AUTH"), 'HREF' => SITE_DIR.'auth/'), 'DEFAULT' => true);

    		// get user avatar
	    	if($isAuthorized = ($USER->isAuthorized() ? true : false)){
				$arResult['PERSONAL']['TITLE'] = GetMessage("USER_CABINET");

	    		$arUser = COptimusCache::CUser_GetList(array("SORT" => "ASC", "CACHE" => array("MULTI" => "N", "TAG" => COptimusCache::GetUserCacheTag($USER_ID))), array("ID" => $USER_ID), array("FIELDS" => array("ID", "PERSONAL_PHOTO")));
				if($arUser["PERSONAL_PHOTO"]){
					$arPhoto = CFile::ResizeImageGet($arUser["PERSONAL_PHOTO"], array("width" => 50, "height" => 50), BX_RESIZE_IMAGE_EXACT, true);
					$arResult['PERSONAL']['SRC'] = $arPhoto['src'];
				}
	    	}
    	}

		$_SESSION['ASPRO_BASKET_COUNTERS'][SITE_ID] = array($USER_ID => $arResult);

    	return $arResult;
    }

    public static function clearFormatPrice($price){
    	$strPrice = '';
    	if($price)
    	{
    		$arPrice = array();
	    	preg_match('/<span class=\'price_value\'>(.+?)<\/span>/is', $price, $arVals);
			if($arVals[1])
				$arPrice[] = $arVals[1];
			preg_match('/<span class=\'price_currency\'>(.+?)<\/span>/is', $price, $arVals);

			if($arVals[1])
				$arPrice[] = $arVals[1];
			if($arPrice)
				$strPrice = implode('', $arPrice);
    	}
    	return $strPrice;
    }

    public static function updateBasketCounters($arValue){
    	global $USER;
    	$USER_ID = ($USER_ID = self::GetUserID()) ? $USER_ID : 0;

    	$arResult = self::getBasketCounters();
    	if($arValue && is_array($arValue)){
    		$arResult = array_replace_recursive($arResult, $arValue);
    	}
    	$arResult['DEFAULT'] = false;
    	$_SESSION['ASPRO_BASKET_COUNTERS'][SITE_ID] = array($USER_ID => $arResult);
    	return $arResult;
    }

    public static function clearBasketCounters(){
    	unset($_SESSION['ASPRO_BASKET_COUNTERS'][SITE_ID]);
	}
	
	public static function GetUserID(){
		static $userID;
		if($userID === NULL)
		{
			global $USER;
			$userID = $USER->GetID();
			$userID = ($userID > 0 ? $userID : 0);
		}
		return $userID;
	}

	public static function correctInstall(){
		if(COption::GetOptionString(self::moduleID, "WIZARD_DEMO_INSTALLED") == "Y"){
			if(CModule::IncludeModule("main")){
				require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/general/wizard.php");
				@set_time_limit(0);
				if(!CWizardUtil::DeleteWizard(self::wizardID)){if(!DeleteDirFilesEx($_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/".self::partnerName."/".self::solutionName."/")){self::removeDirectory($_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/".self::partnerName."/".self::solutionName."/");}}
				UnRegisterModuleDependences("main", "OnBeforeProlog", self::moduleID, get_class(), "correctInstall");
				COption::SetOptionString(self::moduleID, "WIZARD_DEMO_INSTALLED", "N");
			}
		}
	}

	public static function newAction($action = "unknown"){
		$socket = fsockopen('bi.aspro.ru', 80, $errno, $errstr, 10);
		if($socket){
			if(CModule::IncludeModule("main")){
				global $APPLICATION;
				require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/update_client.php");
				$errorMessage = "";
				$serverIP = ($_SERVER["HTTP_X_REAL_IP"] ? $_SERVER["HTTP_X_REAL_IP"] : $_SERVER["SERVER_ADDR"]);
				$arUpdateList = CUpdateClient::GetUpdatesList($errorMessage, "ru", "Y");
				if(array_key_exists("CLIENT", $arUpdateList) && $arUpdateList["CLIENT"][0]["@"]["LICENSE"]){
					$edition = $arUpdateList["CLIENT"][0]["@"]["LICENSE"];
				}
				else{
					$edition = "UNKNOWN";
				}
				$data = json_encode(
					array(
						"client" => "aspro",
						"install_date" => date("Y-m-d H:i:s"),
						"solution_code" => self::moduleID,
						"ip" => $serverIP,
						"http_host" => $_SERVER["HTTP_HOST"],
						"bitrix_version" => SM_VERSION,
						"bitrix_edition" => $APPLICATION->ConvertCharset($edition, SITE_CHARSET, "utf-8"),
						"bitrix_key_hash" => md5(CUpdateClient::GetLicenseKey()),
						"site_name" => $APPLICATION->ConvertCharset(COption::GetOptionString("main", "site_name"), SITE_CHARSET, "utf-8"),
						"site_url" => $APPLICATION->ConvertCharset(COption::GetOptionString("main", "server_name"), SITE_CHARSET, "utf-8"),
						"email_default" => $APPLICATION->ConvertCharset(COption::GetOptionString("main", "email_from"), SITE_CHARSET, "utf-8"),
						"action" => $action,
					)
				);
				fwrite($socket, "POST /rest/bitrix/installs HTTP/1.1\r\n");
				fwrite($socket, "Host: bi.aspro.ru\r\n");
				fwrite($socket, "Content-type: application/x-www-form-urlencoded\r\n");
				fwrite($socket, "Content-length:".strlen($data)."\r\n");
				fwrite($socket, "Accept:*/*\r\n");
				fwrite($socket, "User-agent:Bitrix Installer\r\n");
				fwrite($socket, "Connection:Close\r\n");
				fwrite($socket, "\r\n");
				fwrite($socket, "$data\r\n");
				fwrite($socket, "\r\n");
				$answer = '';
				while(!feof($socket)){
					$answer.= fgets($socket, 4096);
				}
				fclose($socket);
			}
		}
	}

	public static function AddMeta($arParams = array()){
		self::$arMetaParams = array_merge((array)self::$arMetaParams, (array)$arParams);
	}

	public static function SetMeta(){
		global $APPLICATION, $arSite, $TEMPLATE_OPTIONS;

		$PageH1 = $APPLICATION->GetTitle();
		$PageMetaTitleBrowser = $APPLICATION->GetPageProperty('title');
		$DirMetaTitleBrowser = $APPLICATION->GetDirProperty('title');
		$PageMetaDescription = $APPLICATION->GetPageProperty('description');
		$DirMetaDescription = $APPLICATION->GetDirProperty('description');

		// set title
		if(!CSite::inDir(SITE_DIR.'index.php')){
			if(!strlen($PageMetaTitleBrowser)){
				if(!strlen($DirMetaTitleBrowser)){
					$PageMetaTitleBrowser = $PageH1.((strlen($PageH1) && strlen($arSite['SITE_NAME'])) ? ' - ' : '' ).$arSite['SITE_NAME'];
					// $APPLICATION->SetPageProperty('title', $PageMetaTitleBrowser);
				}
			}
		}
		else{
			if(!strlen($PageMetaTitleBrowser)){
				if(!strlen($DirMetaTitleBrowser)){
					$PageMetaTitleBrowser = $arSite['SITE_NAME'].((strlen($arSite['SITE_NAME']) && strlen($PageH1)) ? ' - ' : '' ).$PageH1;
					// $APPLICATION->SetPageProperty('title', $PageMetaTitleBrowser);
				}
			}
		}
		$addr = (CMain::IsHTTPS() ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];
		// check Open Graph required meta properties
		if(!strlen(self::$arMetaParams['og:title'])){
			self::$arMetaParams['og:title'] = $PageMetaTitleBrowser;
		}
		if(!strlen(self::$arMetaParams['og:type'])){
			self::$arMetaParams['og:type'] = 'website';
		}
		if(!strlen(self::$arMetaParams['og:image'])){
			$logo = $TEMPLATE_OPTIONS['LOGO_IMAGE']['CURRENT_IMG'];
			if($logo)
				self::$arMetaParams['og:image'] = $logo; // site logo
			else
				self::$arMetaParams['og:image'] = SITE_DIR.'include/logo.png'; // site logo
		}
		if(!strlen(self::$arMetaParams['og:url'])){
			self::$arMetaParams['og:url'] = $_SERVER['REQUEST_URI'];
		}
		if(!strlen(self::$arMetaParams['og:description'])){
			self::$arMetaParams['og:description'] = (strlen($PageMetaDescription) ? $PageMetaDescription : $DirMetaDescription);
		}

		if(self::$arMetaParams['og:description'])
		{
			$obParser = new CTextParser;
			self::$arMetaParams['og:description'] = $obParser->html_cut(self::$arMetaParams['og:description'], 300);
		}

		foreach(self::$arMetaParams as $metaName => $metaValue){
			if(strlen($metaValue = strip_tags($metaValue))){
				$metaValue = str_replace('//', '/', $metaValue);
				if($metaName === 'og:image' || $metaName === 'og:url')
					$metaValue = $addr.$metaValue;
				$APPLICATION->AddHeadString('<meta property="'.$metaName.'" content="'.$metaValue.'" />', true);
				if($metaName === 'og:image'){
					$APPLICATION->AddHeadString('<link rel="image_src" href="'.$metaValue.'"  />', true);
				}
			}
		}
	}


	public static function DoIBlockAfterSave($arg1, $arg2 = false){
		$ELEMENT_ID = false;
		$IBLOCK_ID = false;
		$OFFERS_IBLOCK_ID = false;
		$OFFERS_PROPERTY_ID = false;
		if (CModule::IncludeModule('currency'))
			$strDefaultCurrency = CCurrency::GetBaseCurrency();

		//Check for catalog event
		if(is_array($arg2) && $arg2["PRODUCT_ID"] > 0){
			//Get iblock element
			$rsPriceElement = CIBlockElement::GetList(
				array(),
				array(
					"ID" => $arg2["PRODUCT_ID"],
				),
				false,
				false,
				array("ID", "IBLOCK_ID")
			);
			if($arPriceElement = $rsPriceElement->Fetch()){
				$arCatalog = CCatalog::GetByID($arPriceElement["IBLOCK_ID"]);
				if(is_array($arCatalog)){
					//Check if it is offers iblock
					if($arCatalog["OFFERS"] == "Y"){
						//Find product element
						$rsElement = CIBlockElement::GetProperty(
							$arPriceElement["IBLOCK_ID"],
							$arPriceElement["ID"],
							"sort",
							"asc",
							array("ID" => $arCatalog["SKU_PROPERTY_ID"])
						);
						$arElement = $rsElement->Fetch();
						if($arElement && $arElement["VALUE"] > 0)
						{
							$ELEMENT_ID = $arElement["VALUE"];
							$IBLOCK_ID = $arCatalog["PRODUCT_IBLOCK_ID"];
							$OFFERS_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
							$OFFERS_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];
						}
					}
					//or iblock which has offers
					elseif($arCatalog["OFFERS_IBLOCK_ID"] > 0){
						$ELEMENT_ID = $arPriceElement["ID"];
						$IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
						$OFFERS_IBLOCK_ID = $arCatalog["OFFERS_IBLOCK_ID"];
						$OFFERS_PROPERTY_ID = $arCatalog["OFFERS_PROPERTY_ID"];
					}
					//or it's regular catalog
					else{
						$ELEMENT_ID = $arPriceElement["ID"];
						$IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
						$OFFERS_IBLOCK_ID = false;
						$OFFERS_PROPERTY_ID = false;
					}
				}
			}
		}
		//Check for iblock event
		elseif(is_array($arg1) && $arg1["ID"] > 0 && $arg1["IBLOCK_ID"] > 0){
			//Check if iblock has offers
			$arOffers = CIBlockPriceTools::GetOffersIBlock($arg1["IBLOCK_ID"]);
			if(is_array($arOffers)){
				$ELEMENT_ID = $arg1["ID"];
				$IBLOCK_ID = $arg1["IBLOCK_ID"];
				$OFFERS_IBLOCK_ID = $arOffers["OFFERS_IBLOCK_ID"];
				$OFFERS_PROPERTY_ID = $arOffers["OFFERS_PROPERTY_ID"];
			}
		}

		if($ELEMENT_ID){
			static $arPropCache = array();
			static $arPropArray=array();

			if(!array_key_exists($IBLOCK_ID, $arPropCache)){
				//Check for MINIMAL_PRICE property
				$rsProperty = CIBlockProperty::GetByID("MINIMUM_PRICE", $IBLOCK_ID);
				$arProperty = $rsProperty->Fetch();
				if($arProperty){
					$arPropCache[$IBLOCK_ID] = $arProperty["ID"];
					$arPropArray["MINIMUM_PRICE"]=$arProperty["ID"];
				}else{
					$arPropCache[$IBLOCK_ID] = false;
				}
				$rsProperty = CIBlockProperty::GetByID("IN_STOCK", $IBLOCK_ID);
				$arProperty = $rsProperty->Fetch();
				if($arProperty){
					$arPropCache[$IBLOCK_ID] = $arProperty["ID"];
					$arPropArray["IN_STOCK"]=$arProperty["ID"];
				}else{
					if(!$arPropCache[$IBLOCK_ID])
						$arPropCache[$IBLOCK_ID] = false;
				}
			}

			if($arPropCache[$IBLOCK_ID]){
				//Compose elements filter
				if($OFFERS_IBLOCK_ID){
					$rsOffers = CIBlockElement::GetList(
						array(),
						array(
							"IBLOCK_ID" => $OFFERS_IBLOCK_ID,
							"PROPERTY_".$OFFERS_PROPERTY_ID => $ELEMENT_ID,
							"ACTIVE" => "Y"
						),
						false,
						false,
						array("ID")
					);
					while($arOffer = $rsOffers->Fetch())
						$arProductID[] = $arOffer["ID"];

					if (!is_array($arProductID))
						$arProductID = array($ELEMENT_ID);
				}
				else
					$arProductID = array($ELEMENT_ID);

				if($arPropArray["MINIMUM_PRICE"]){
					$minPrice = false;
					$maxPrice = false;
					//Get prices
					$rsPrices = CPrice::GetList(
						array(),
						array(
							"PRODUCT_ID" => $arProductID,
						)
					);
					while($arPrice = $rsPrices->Fetch()){
						if (CModule::IncludeModule('currency') && $strDefaultCurrency != $arPrice['CURRENCY'])
							$arPrice["PRICE"] = CCurrencyRates::ConvertCurrency($arPrice["PRICE"], $arPrice["CURRENCY"], $strDefaultCurrency);

						$PRICE = $arPrice["PRICE"];

						if($minPrice === false || $minPrice > $PRICE)
							$minPrice = $PRICE;

						if($maxPrice === false || $maxPrice < $PRICE)
							$maxPrice = $PRICE;
					}

					//Save found minimal price into property
					if($minPrice !== false){
						CIBlockElement::SetPropertyValuesEx(
							$ELEMENT_ID,
							$IBLOCK_ID,
							array(
								"MINIMUM_PRICE" => $minPrice,
								"MAXIMUM_PRICE" => $maxPrice,
							)
						);
					}
				}
				if($arPropArray["IN_STOCK"]){
					$quantity=0;
					$rsQuantity = CCatalogProduct::GetList(
				        array("QUANTITY" => "DESC"),
				        array("ID" => $arProductID),
				        false,
				        false,
				        array("QUANTITY")
				    );
					while($arQuantity = $rsQuantity->Fetch()){
						if($arQuantity["QUANTITY"]>0)
							$quantity+=$arQuantity["QUANTITY"];
					}

					$rsPropStock = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>"IN_STOCK"));
					while($arPropStockArr=$rsPropStock->GetNext()){
						$idPropArr[]=$arPropStockArr["ID"];
					}



					if($quantity>0){


						CIBlockElement::SetPropertyValuesEx(
							$ELEMENT_ID,
							$IBLOCK_ID,
							array(
								"IN_STOCK" => $idPropArr[0],//$idProp,
							)
						);
					}else{
						CIBlockElement::SetPropertyValuesEx(
							$ELEMENT_ID,
							$IBLOCK_ID,
							array(
								"IN_STOCK" => ((is_array($idPropArr) && count($idPropArr)>1) ? $idPropArr[1] : "" ),
							)
						);
					}

					if(class_exists('\Bitrix\Iblock\PropertyIndex\Manager')){
						\Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex($IBLOCK_ID, $ELEMENT_ID);
					}
				}
			}
		}
	}

	public static function setStockProduct($ID, $arFields){
		//Get iblock element
		$rsPriceElement = CIBlockElement::GetList(
			array(),
			array(
				"ID" => $ID,
			),
			false,
			false,
			array("ID", "IBLOCK_ID")
		);
		if($arPriceElement = $rsPriceElement->Fetch()){
			$arCatalog = CCatalog::GetByID($arPriceElement["IBLOCK_ID"]);
			if(is_array($arCatalog)){
				//Check if it is offers iblock
				if($arCatalog["OFFERS"] == "Y"){
					//Find product element
					$rsElement = CIBlockElement::GetProperty(
						$arPriceElement["IBLOCK_ID"],
						$arPriceElement["ID"],
						"sort",
						"asc",
						array("ID" => $arCatalog["SKU_PROPERTY_ID"])
					);
					$arElement = $rsElement->Fetch();
					if($arElement && $arElement["VALUE"] > 0)
					{
						$ELEMENT_ID = $arElement["VALUE"];
						$IBLOCK_ID = $arCatalog["PRODUCT_IBLOCK_ID"];
						$OFFERS_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
						$OFFERS_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];
					}
				}
				//or iblock which has offers
				elseif($arCatalog["OFFERS_IBLOCK_ID"] > 0){
					$ELEMENT_ID = $arPriceElement["ID"];
					$IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
					$OFFERS_IBLOCK_ID = $arCatalog["OFFERS_IBLOCK_ID"];
					$OFFERS_PROPERTY_ID = $arCatalog["OFFERS_PROPERTY_ID"];
				}
				//or it's regular catalog
				else{
					$ELEMENT_ID = $arPriceElement["ID"];
					$IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
					$OFFERS_IBLOCK_ID = false;
					$OFFERS_PROPERTY_ID = false;
				}
			}
		}
		if($ELEMENT_ID){
			static $arPropCache = array();
			static $arPropArray=array();

			if(!array_key_exists($IBLOCK_ID, $arPropCache)){
				//Check for IN_STOCK property
				$rsProperty = CIBlockProperty::GetByID("IN_STOCK", $IBLOCK_ID);
				$arProperty = $rsProperty->Fetch();
				if($arProperty){
					$arPropCache[$IBLOCK_ID] = $arProperty["ID"];
					$arPropArray["IN_STOCK"]=$arProperty["ID"];
				}else{
					if(!$arPropCache[$IBLOCK_ID])
						$arPropCache[$IBLOCK_ID] = false;
				}
			}
			if($arPropCache[$IBLOCK_ID]){
				//Compose elements filter
				if($OFFERS_IBLOCK_ID){
					$rsOffers = CIBlockElement::GetList(
						array(),
						array(
							"IBLOCK_ID" => $OFFERS_IBLOCK_ID,
							"PROPERTY_".$OFFERS_PROPERTY_ID => $ELEMENT_ID,
							"ACTIVE" => "Y"
						),
						false,
						false,
						array("ID")
					);
					while($arOffer = $rsOffers->Fetch())
						$arProductID[] = $arOffer["ID"];

					if (!is_array($arProductID))
						$arProductID = array($ELEMENT_ID);
				}
				else
					$arProductID = array($ELEMENT_ID);
				if($arPropArray["IN_STOCK"]){
					$quantity=0;
					$rsQuantity = CCatalogProduct::GetList(
				        array("QUANTITY" => "DESC"),
				        array("ID" => $arProductID),
				        false,
				        false,
				        array("QUANTITY")
				    );
					while($arQuantity = $rsQuantity->Fetch()){
						if($arQuantity["QUANTITY"]>0)
							$quantity+=$arQuantity["QUANTITY"];
					}


					$rsPropStock = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>"IN_STOCK"));
					while($arPropStockArr=$rsPropStock->GetNext()){
						$idPropArr[]=$arPropStockArr["ID"];
					}



					if($quantity>0){

						CIBlockElement::SetPropertyValuesEx(
							$ELEMENT_ID,
							$IBLOCK_ID,
							array(
								"IN_STOCK" => $idPropArr[0],//$idProp,
							)
						);
					}else{
						CIBlockElement::SetPropertyValuesEx(
							$ELEMENT_ID,
							$IBLOCK_ID,
							array(
								"IN_STOCK" => ((is_array($idPropArr) && count($idPropArr)>1) ? $idPropArr[1] : "" ),
							)
						);
					}


					/*if($quantity>0){
						$rsPropStock = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>"IN_STOCK"));
						if($arPropStock=$rsPropStock->Fetch()){
							$idProp=$arPropStock["ID"];
						}

						CIBlockElement::SetPropertyValuesEx(
							$ELEMENT_ID,
							$IBLOCK_ID,
							array(
								"IN_STOCK" => $idProp,
							)
						);
					}else{
						CIBlockElement::SetPropertyValuesEx(
							$ELEMENT_ID,
							$IBLOCK_ID,
							array(
								"IN_STOCK" => "",
							)
						);
					}*/



					if(class_exists('\Bitrix\Iblock\PropertyIndex\Manager')){
						\Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex($IBLOCK_ID, $ELEMENT_ID);
					}
				}
			}
		}
	}

	public static function getViewedProducts($userID=false, $siteID=false){
		global $TEMPLATE_OPTIONS, $STARTTIME;
		$arResult = array();
		$siteID = $siteID ? $siteID : SITE_ID;
		$localKey = 'OPTIMUS_VIEWED_ITEMS_'.$siteID;

		if($IsViewedTypeLocal = ($TEMPLATE_OPTIONS['VIEWED_TYPE']['CURRENT_VALUE'] === 'LOCAL')){
			$arViewed = (isset($_COOKIE[$localKey]) && strlen($_COOKIE[$localKey])) ? json_decode($_COOKIE[$localKey], true) : array();

			if($arViewed && is_array($arViewed)){
				$viewedDays = COption::GetOptionString("sale", "viewed_time", "5");
				$viewedCntMax = COption::GetOptionString("sale", "viewed_count", "10");
				$DIETIME = $STARTTIME - $viewedDays * 86400000;

				// delete old items
				foreach($arViewed as $ID => $arItem){
					if($arItem[0] < $DIETIME){
						unset($arViewed[$ID]);
						continue;
					}

					$arResult[$ID] = $arItem[0];
				}

				// sort by ACTIVE_FROM
				arsort($arResult);

				// make IDs array
				$arResult = array_keys($arResult);

				// only $viewedCntMax items
				$arResult = array_slice($arResult, 0, $viewedCntMax);
			}
		}
		else{
			\Bitrix\Main\Loader::includeModule('sale');
			\Bitrix\Main\Loader::includeModule('catalog');
			$userID = $userID ? $userID : (int)CSaleBasket::GetBasketUserID(false);

			$viewedIterator = \Bitrix\Catalog\CatalogViewedProductTable::GetList(array(
				'select' => array('PRODUCT_ID', 'ELEMENT_ID'),
				'filter' => array('=FUSER_ID' => $userID, '=SITE_ID' => $siteID),
				'order' => array('DATE_VISIT' => 'DESC'),
				'limit' => 8
			));
			while($viewedProduct = $viewedIterator->fetch()){
				$viewedProduct['ELEMENT_ID'] = (int)$viewedProduct['ELEMENT_ID'];
				$viewedProduct['PRODUCT_ID'] = (int)$viewedProduct['PRODUCT_ID'];
				$arResult[$viewedProduct['PRODUCT_ID']] = $viewedProduct['ELEMENT_ID'];
			}
		}

		return $arResult;
	}

	public static function setFooterTitle(){
		global $APPLICATION, $arSite;
		if(\Bitrix\Main\Config\Option::get("aspro.optimus", "HIDE_SITE_NAME_TITLE", "N")=="N"){
			if(!COptimus::IsMainPage()){
				if(strlen($APPLICATION->GetPageProperty('title')) > 1){
					$title = $APPLICATION->GetPageProperty('title');
				}
				else{
					$title = $APPLICATION->GetTitle();
				}
				$APPLICATION->SetPageProperty("title", $title.' - '.$arSite['SITE_NAME']);
			}
			else{
				if(strlen($APPLICATION->GetPageProperty('title')) > 1){
					$title =  $APPLICATION->GetPageProperty('title');
				}
				else{
					$title =  $APPLICATION->GetTitle();
				}
				if(!empty($title)){
					$APPLICATION->SetPageProperty("title", $title);
				}
				else{
					$APPLICATION->SetPageProperty("title", $arSite['SITE_NAME']);
				}
			}
		}

		self::SetMeta();
	}

	public static function bottomActions(){
        include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer/bottom_footer.php'));
    }

	public static function getBasketItems($iblockID=0, $field="PRODUCT_ID"){
		$basket_items = $delay_items = $subscribe_items = $not_available_items = array();
		$arItems=array();
		$bUseSubscribeManager = ($arSubscribeList = self::getUserSubscribeList()) !== false;
		if(CModule::IncludeModule("sale")){
			$arBasketItems=array();
			$dbRes = CSaleBasket::GetList(array("NAME" => "ASC", "ID" => "ASC"), array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, array("ID", "PRODUCT_ID", "DELAY", "SUBSCRIBE", "CAN_BUY", "TYPE", "SET_PARENT_ID"));
			while($item = $dbRes->Fetch()){
				$arBasketItems[] = $item;
			}

			global $compare_items;
			if(!is_array($compare_items)){
				$compare_items = array();
				$iblockID=((isset($iblockID) && $iblockID) ? $iblockID : \Bitrix\Main\Config\Option::get(self::moduleID, "CATALOG_IBLOCK_ID", COptimusCache::$arIBlocks[SITE_ID]['aspro_optimus_catalog']['aspro_optimus_catalog'][0], SITE_ID ));
				if($iblockID && isset($_SESSION["CATALOG_COMPARE_LIST"][$iblockID]["ITEMS"])){
					$compare_items = array_keys($_SESSION["CATALOG_COMPARE_LIST"][$iblockID]["ITEMS"]);
				}
			}
			if($arBasketItems){
				foreach($arBasketItems as $arBasketItem){
					if(CSaleBasketHelper::isSetItem($arBasketItem)) // set item
						continue;
					if($arBasketItem["DELAY"]=="N" && $arBasketItem["CAN_BUY"] == "Y" && $arBasketItem["SUBSCRIBE"] == "N"){
						$basket_items[] = $arBasketItem[$field];
					}
					elseif($arBasketItem["DELAY"]=="Y" && $arBasketItem["CAN_BUY"] == "Y" && $arBasketItem["SUBSCRIBE"] == "N"){
						$delay_items[] = $arBasketItem[$field];
					}
					elseif($arBasketItem["SUBSCRIBE"]=="Y"){
						$subscribe_items[] = $arBasketItem[$field];
					}else{
						$not_available_items[] = $arBasketItem[$field];
					}
				}
			}
			$arItems["BASKET"]=array_combine($basket_items, $basket_items);
			$arItems["DELAY"]=array_combine($delay_items, $delay_items);
			$arItems["SUBSCRIBE"]=array_combine($subscribe_items, $subscribe_items);
			$arItems["NOT_AVAILABLE"]=array_combine($not_available_items, $not_available_items);
			$arItems["COMPARE"]=array_combine($compare_items, $compare_items);
		}

		if($bUseSubscribeManager && $arSubscribeList){
			foreach($arSubscribeList as $PRODUCT_ID => $arIDs){
				$arItems['SUBSCRIBE'][$PRODUCT_ID] = $PRODUCT_ID;
			}
		}

		return $arItems;
	}

	public static function getUserSubscribeList($userId = false){
		if(CModule::IncludeModule('catalog')){
			if(class_exists('\Bitrix\Catalog\Product\SubscribeManager')){
				global $USER, $DB;
				$userId = $userId ? intval($userId) : (($USER && is_object($USER) && $USER->isAuthorized()) ? $USER->getId() : false);

				$bSubscribeProducts = (isset($_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID']) && $_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID']);

				if($userId || $bSubscribeProducts)
				{
					$arSubscribeList = array();
					$subscribeManager = new \Bitrix\Catalog\Product\SubscribeManager;

					$filter = array(
						'USER_ID' => $userId,
						'=SITE_ID' => SITE_ID,
						array(
							'LOGIC' => 'OR',
							array('=DATE_TO' => false),
							array('>DATE_TO' => date($DB->dateFormatToPHP(\CLang::getDateFormat('FULL')), time()))
						),
					);

					$resultObject = \Bitrix\Catalog\SubscribeTable::getList(
						array(
							'select' => array(
								'ID',
								'ITEM_ID',
							),
							'filter' => $filter,
						)
					);

					while($arItem = $resultObject->fetch()){
						$arSubscribeList[$arItem['ITEM_ID']][] = $arItem['ID'];
					}
					if(!$userId && $bSubscribeProducts)
					{
						foreach($arSubscribeList as $key => $id)
						{
							if(!$_SESSION['SUBSCRIBE_PRODUCT']['LIST_PRODUCT_ID'][$key])
								unset($arSubscribeList[$key]);
						}
					}

					return $arSubscribeList;
				}
			}
		}

		return false;
	}

	public static function showFooterBasket(){
		Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("basketitems-block");

		$arItems=self::getBasketItems();

		if(CModule::IncludeModule("currency")){
			CJSCore::Init(array('currency'));
			$currencyFormat = CCurrencyLang::GetFormatDescription(CSaleLang::GetLangCurrency(SITE_ID));
		}
		?>
		<script type="text/javascript">
			<?if(is_array($currencyFormat)):?>
				function jsPriceFormat(_number){
					BX.Currency.setCurrencyFormat('<?=CSaleLang::GetLangCurrency(SITE_ID);?>', <? echo CUtil::PhpToJSObject($currencyFormat, false, true); ?>);
					return BX.Currency.currencyFormat(_number, '<?=CSaleLang::GetLangCurrency(SITE_ID);?>', true);
				}
			<?endif;?>
		</script>
		<script type="text/javascript">
			var arBasketAspro = <? echo CUtil::PhpToJSObject($arItems, false, true); ?>;
			$(document).ready(function(){
				<?if(is_array($arItems["BASKET"]) && !empty($arItems["BASKET"])):?>
					<?foreach( $arItems["BASKET"] as $key=>$item ){?>
						$('.to-cart[data-item=<?=$key?>]').hide();
						$('.counter_block[data-item=<?=$key?>]').hide();
						$('.in-cart[data-item=<?=$key?>]').show();
						$('.in-cart[data-item=<?=$key?>]').closest('.button_block').addClass('wide');
					<?}?>
				<?endif;?>
				<?if(is_array($arItems["DELAY"]) && !empty($arItems["DELAY"])):?>
					<?foreach( $arItems["DELAY"] as $key=>$item ){?>
						$('.wish_item.to[data-item=<?=$key?>]').hide();
						$('.wish_item.in[data-item=<?=$key?>]').show();
						if ($('.wish_item[data-item=<?=$key?>]').find(".value.added").length) {
							$('.wish_item[data-item=<?=$key?>]').addClass("added");
							$('.wish_item[data-item=<?=$key?>]').find(".value").hide();
							$('.wish_item[data-item=<?=$key?>]').find(".value.added").css('display','block');
						}
					<?}?>
				<?endif;?>
				<?if(is_array($arItems["SUBSCRIBE"]) && !empty($arItems["SUBSCRIBE"])):?>
					<?foreach( $arItems["SUBSCRIBE"] as $key=>$item ){?>
						$('.to-subscribe[data-item=<?=$key?>]').hide();
						$('.in-subscribe[data-item=<?=$key?>]').show();
					<?}?>
				<?endif;?>
				<?if(is_array($arItems["COMPARE"]) && !empty($arItems["COMPARE"])):?>
					<?foreach( $arItems["COMPARE"] as $key=>$item ){?>
						$('.compare_item.to[data-item=<?=$key?>]').hide();
						$('.compare_item.in[data-item=<?=$key?>]').show();
						if ($('.compare_item[data-item=<?=$key?>]').find(".value.added").length){
							$('.compare_item[data-item=<?=$key?>]').addClass("added");
							$('.compare_item[data-item=<?=$key?>]').find(".value").hide();
							$('.compare_item[data-item=<?=$key?>]').find(".value.added").css('display','block');
						}
					<?}?>
				<?endif;?>
			});
		</script>
		<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("basketitems-block", "");
	}

	public static function GetIBlockAllElementsFilter(&$arParams){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'Y');
		if(isset($arParams['CHECK_DATES']) && $arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(isset($arParams['SHOW_DEACTIVATED']) && $arParams['SHOW_DEACTIVATED'] === 'N'){ // for catalog component
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y'));
		}
		if(strlen($arParams['FILTER_NAME']) && (array)$GLOBALS[$arParams['FILTER_NAME']]){
			$arFilter = array_merge($arFilter, (array)$GLOBALS[$arParams['FILTER_NAME']]);
		}
		return $arFilter;
	}

	public static function ShowTopDetailBanner($arResult, $arParams){
		$bg = ((isset($arResult['PROPERTIES']['BNR_TOP_BG']) && $arResult['PROPERTIES']['BNR_TOP_BG']['VALUE']) ? CFile::GetPath($arResult['PROPERTIES']['BNR_TOP_BG']['VALUE']) : SITE_TEMPLATE_PATH.'/images/top-bnr.jpg');
		$bShowBG = (isset($arResult['PROPERTIES']['BNR_TOP_IMG']) && $arResult['PROPERTIES']['BNR_TOP_IMG']['VALUE']);
		$title = ($arResult['IPROPERTY_VALUES'] && strlen($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arResult['NAME']);
		$text_color_style = ((isset($arResult['PROPERTIES']['CODE_TEXT']) && $arResult['PROPERTIES']['CODE_TEXT']['VALUE']) ? 'style="color:'.$arResult['PROPERTIES']['CODE_TEXT']['VALUE'].'"' : '');
		$bLanding = (isset($arResult['IS_LANDING']) && $arResult['IS_LANDING'] == 'Y');
		?>
		<div class="banners-content">
			<div class="maxwidth-banner" style="background: url(<?=$bg;?>) 50% 50% no-repeat;">
				<div class="row">
					<div class="maxwidth-theme">
						<div class="col-md-<?=($bShowBG ? 5 : 12);?> text animated delay06 duration08 item_block fadeInUp">
							<div class="inner">
								<h1 <?=$text_color_style;?>><?=$title?></h1>
								<div class="intro-text" <?=$text_color_style;?>>
									<?if($bLanding):?>
										<p><?=$arResult['PROPERTIES']['ANONS']['VALUE'];?></p>
									<?else:?>
										<?if($arResult['PREVIEW_TEXT_TYPE'] == 'text'):?>
											<p><?=$arResult['FIELDS']['PREVIEW_TEXT'];?></p>
										<?else:?>
											<?=$arResult['FIELDS']['PREVIEW_TEXT'];?>
										<?endif;?>
									<?endif;?>
								</div>
								<p>
									<?if($bLanding):?>
										<?if($arResult['PROPERTIES']['BUTTON_TEXT']['VALUE']):?>
											<span>
												<span class="button wide scroll_btn"><?=$arResult['PROPERTIES']['BUTTON_TEXT']['VALUE'];?></span>
											</span>
										<?endif;?>
									<?else:?>
										<?if($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES'):?>
											<span>
												<span class="button wide animate-load" data-event="jqm" data-param-form_id="<?=($arParams["FORM_ID_ORDER_SERVISE"] ? $arParams["FORM_ID_ORDER_SERVISE"] : 'SERVICES');?>" data-name="order_services" data-autoload-service="<?=self::formatJsName($arResult['NAME']);?>" data-autoload-project="<?=self::formatJsName($arResult['NAME']);?>" data-autoload-product="<?=self::formatJsName($arResult['NAME']);?>"><span><?=(strlen($arParams['S_ORDER_SERVISE']) ? $arParams['S_ORDER_SERVISE'] : Loc::getMessage('S_ORDER_SERVISE'))?></span></span>
											</span>
										<?endif;?>

										<?if($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
											<span>
												<span class="button wide grey animate-load" data-event="jqm" data-param-form_id="ASK" data-autoload-need_product="<?=self::formatJsName($arResult['NAME']);?>" data-name="question"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : Loc::getMessage('S_ASK_QUESTION'))?></span></span>
											</span>
										<?endif;?>
									<?endif;?>
								</p>
							</div>
						</div>
						<?if($bShowBG):?>
							<div class="col-md-7 hidden-xs hidden-sm img animated delay09 duration08 item_block fadeInUp">
								<div class="inner">
									<img src="<?=CFile::GetPath($arResult['PROPERTIES']['BNR_TOP_IMG']['VALUE']);?>" alt="<?=$title;?>" title="<?=$title;?>" draggable="false">
								</div>
							</div>
						<?endif;?>
					</div>
				</div>
			</div>
		</div>
	<?}

	public static function utf8_substr_replace($original, $replacement, $position, $length){
		$startString = mb_substr($original, 0, $position, 'UTF-8');
		$endString = mb_substr($original, $position + $length, mb_strlen($original), 'UTF-8');

		$out = $startString.$replacement.$endString;

		return $out;
	}

	public static function formatJsName($name = ''){
		return htmlspecialcharsbx($name);
	}

	public static function prepareShopListArray($arShops){
		$arFormatShops=array();

		$arPlacemarks = array();

		if(is_array($arShops)){
			foreach($arShops as $i => $arShop){
				if(isset($arShop['IBLOCK_ID'])){
					$arShop['TITLE'] = ($arShop['FIELDS']['NAME'] ? $arShop['NAME'] : '');
					$imageID = (($arShop['FIELDS']['PREVIEW_PICTURE'] && $arShop["PREVIEW_PICTURE"]['ID']) ? $arShop["PREVIEW_PICTURE"]['ID'] : (($arShop['FIELD_CODE']['DETAIL_PICTURE'] && $arShop["DETAIL_PICTURE"]['ID']) ? $arShop["DETAIL_PICTURE"]['ID'] : false));
					$arShop['IMAGE'] = ($imageID ? CFile::ResizeImageGet($imageID, array('width' => 100, 'height' => 69), BX_RESIZE_IMAGE_EXACT) : []);
					$arShop['ADDRESS'] = $arShop['DISPLAY_PROPERTIES']['ADDRESS']['VALUE'];
					$arShop['ADDRESS'] = $arShop['TITLE'].((strlen($arShop['TITLE']) && strlen($arShop['ADDRESS'])) ? ', ' : '').$arShop['ADDRESS'];
					$arShop['PHONE'] = $arShop['DISPLAY_PROPERTIES']['PHONE']['VALUE'];
					$arShop['EMAIL'] = $arShop['DISPLAY_PROPERTIES']['EMAIL']['VALUE'];
					if($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['VALUE']['TYPE'] == 'html'){
						$arShop['SCHEDULE'] = htmlspecialchars_decode($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT']);
					}
					else{
						$arShop['SCHEDULE'] = nl2br($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT']);
					}
					$arShop['URL'] = $arShop['DETAIL_PAGE_URL'];
					$arShop['METRO_PLACEMARK_HTML'] = '';
					if($arShop['METRO'] = $arShop['DISPLAY_PROPERTIES']['METRO']['VALUE']){
						if(!is_array($arShop['METRO'])){
							$arShop['METRO'] = array($arShop['METRO']);
						}
						foreach($arShop['METRO'] as $metro){
							$arShop['METRO_PLACEMARK_HTML'] .= '<div class="metro"><i></i>'.$metro.'</div>';
						}
					}
					$arShop['DESCRIPTION'] = $arShop['DETAIL_TEXT'];
					$arShop['GPS_S'] = false;
					$arShop['GPS_N'] = false;
					if($arStoreMap = explode(',', $arShop['DISPLAY_PROPERTIES']['MAP']['VALUE'])){
						$arShop['GPS_S'] = $arStoreMap[0];
						$arShop['GPS_N'] = $arStoreMap[1];
					}

					if($arShop['GPS_S'] && $arShop['GPS_N']){
						$mapLAT += $arShop['GPS_S'];
						$mapLON += $arShop['GPS_N'];
						$str_phones = '';
						if($arShop['PHONE'])
						{
							$str_phones .= '<div class="phone">';
							foreach($arShop['PHONE'] as $phone)
							{
								$str_phones .= '<br><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a>';
							}
							$str_phones .= '</div>';
						}
						$arPlacemarks[] = array(
							"ID" => $arShop["ID"],
							"LAT" => $arShop['GPS_S'],
							"LON" => $arShop['GPS_N'],
							"TEXT" => $arShop["TITLE"],
							"HTML" => '<div class="title">'.(strlen($arShop["URL"]) ? '<a href="'.$arShop["URL"].'">' : '').$arShop["ADDRESS"].(strlen($arShop["URL"]) ? '</a>' : '').'</div><div class="info-content">'.($arShop['METRO'] ? $arShop['METRO_PLACEMARK_HTML'] : '').(strlen($arShop['SCHEDULE']) ? '<div class="schedule">'.$arShop['SCHEDULE'].'</div>' : '').$str_phones.(strlen($arShop['EMAIL']) ? '<div class="email"><a rel="nofollow" href="mailto:'.$arShop['EMAIL'].'">'.$arShop['EMAIL'].'</a></div>' : '').'</div>'.(strlen($arShop['URL']) ? '<a rel="nofollow" class="button" href="'.$arShop["URL"].'"><span>'.GetMessage('DETAIL').'</span></a>' : '')
						);
					}
				}
				else{
					$arShop['IMAGE'] = $arShop['IMAGE'] ?: [];

					$str_phones = '';
					if($arShop['PHONE'])
					{
						$arShop['PHONE'] = explode(",", $arShop['PHONE']);
						$str_phones .= '<div class="phone">';
						foreach($arShop['PHONE'] as $phone)
						{
							$str_phones .= '<br><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a>';
						}
						$str_phones .= '</div>';
					}
					if($arShop['GPS_S'] && $arShop['GPS_N']){
						$mapLAT += $arShop['GPS_N'];
						$mapLON += $arShop['GPS_S'];
						$arPlacemarks[] = array(
							"ID" => $arShop["ID"],
							"LON" => $arShop['GPS_S'],
							"LAT" => $arShop['GPS_N'],
							"TEXT" => $arShop["TITLE"],
							"HTML" => '<div class="title">'.(strlen($arShop["URL"]) ? '<a href="'.$arShop["URL"].'">' : '').$arShop["ADDRESS"].(strlen($arShop["URL"]) ? '</a>' : '').'</div><div class="info-content">'.($arShop['METRO'] ? $arShop['METRO_PLACEMARK_HTML'] : '').(strlen($arShop['SCHEDULE']) ? '<div class="schedule">'.$arShop['SCHEDULE'].'</div>' : '').$str_phones.(strlen($arShop['EMAIL']) ? '<div class="email"><a rel="nofollow" href="mailto:'.$arShop['EMAIL'].'">'.$arShop['EMAIL'].'</a></div>' : '').'</div>'.(strlen($arShop['URL']) ? '<a rel="nofollow" class="button" href="'.$arShop["URL"].'"><span>'.GetMessage('DETAIL').'</span></a>' : '')
						);
					}
				}
				$arShops[$i] = $arShop;
			}
		}
		$arFormatShops["SHOPS"]=$arShops;
		$arFormatShops["PLACEMARKS"]=$arPlacemarks;
		$arFormatShops["POINTS"]=array(
			"LAT" => $mapLAT,
			"LON" => $mapLON,
		);

		return $arFormatShops;
	}

	public static function prepareShopDetailArray($arShop, $arParams){
		$mapLAT = $mapLON = 0;
		$arPlacemarks = array();
		$arPhotos = array();
		$arFormatShops=array();

		if(is_array($arShop)){
			if(isset($arShop['IBLOCK_ID'])){
				$arShop['LIST_URL'] = $arShop['LIST_PAGE_URL'];
				$arShop['TITLE'] = (in_array('NAME', $arParams['FIELD_CODE']) ? $arShop['NAME'] : '');
				$arShop['ADDRESS'] = $arShop['DISPLAY_PROPERTIES']['ADDRESS']['VALUE'];
				$arShop['ADDRESS'] = $arShop['TITLE'].((strlen($arShop['TITLE']) && strlen($arShop['ADDRESS'])) ? ', ' : '').$arShop['ADDRESS'];
				$arShop['PHONE'] = $arShop['DISPLAY_PROPERTIES']['PHONE']['VALUE'];
				$arShop['EMAIL'] = $arShop['DISPLAY_PROPERTIES']['EMAIL']['VALUE'];
				if($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['VALUE']['TYPE'] == 'html'){
					$arShop['SCHEDULE'] = htmlspecialchars_decode($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT']);
				}
				else{
					$arShop['SCHEDULE'] = nl2br($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT']);
				}
				$arShop['URL'] = $arShop['DETAIL_PAGE_URL'];
				$arShop['METRO_PLACEMARK_HTML'] = '';
				if($arShop['METRO'] = $arShop['DISPLAY_PROPERTIES']['METRO']['VALUE']){
					if(!is_array($arShop['METRO'])){
						$arShop['METRO'] = array($arShop['METRO']);
					}
					foreach($arShop['METRO'] as $metro){
						$arShop['METRO_PLACEMARK_HTML'] .= '<div class="metro"><i></i>'.$metro.'</div>';
					}
				}
				$arShop['GPS_S'] = false;
				$arShop['GPS_N'] = false;
				if($arStoreMap = explode(',', $arShop['DISPLAY_PROPERTIES']['MAP']['VALUE'])){
					$arShop['GPS_S'] = $arStoreMap[0];
					$arShop['GPS_N'] = $arStoreMap[1];
				}

				if($arShop['GPS_S'] && $arShop['GPS_N']){
					$mapLAT += $arShop['GPS_S'];
					$mapLON += $arShop['GPS_N'];
					$str_phones = '';
					if($arShop['PHONE'])
					{
						$str_phones .= '<div class="phone">';
						foreach($arShop['PHONE'] as $phone)
						{
							$str_phones .= '<br><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a>';
						}
						$str_phones .= '</div>';
					}
					$arPlacemarks[] = array(
						"ID" => $arShop["ID"],
						"LAT" => $arShop['GPS_S'],
						"LON" => $arShop['GPS_N'],
						"TEXT" => $arShop["TITLE"],
						"HTML" => '<div class="title">'.(strlen($arShop["URL"]) ? '<a href="'.$arShop["URL"].'">' : '').$arShop["ADDRESS"].(strlen($arShop["URL"]) ? '</a>' : '').'</div><div class="info-content">'.($arShop['METRO'] ? $arShop['METRO_PLACEMARK_HTML'] : '').(strlen($arShop['SCHEDULE']) ? '<div class="schedule">'.$arShop['SCHEDULE'].'</div>' : '').$str_phones.(strlen($arShop['EMAIL']) ? '<div class="email"><a rel="nofollow" href="mailto:'.$arShop['EMAIL'].'">'.$arShop['EMAIL'].'</a></div>' : '').'</div>'.(strlen($arShop['URL']) ? '<a rel="nofollow" class="button" href="'.$arShop["URL"].'"><span>'.GetMessage('DETAIL').'</span></a>' : '')
					);
				}
			}
			else{
				$arShop["TITLE"] = htmlspecialchars_decode($arShop["TITLE"]);
				$arShop["ADDRESS"] = htmlspecialchars_decode($arShop["ADDRESS"]);
				$arShop["ADDRESS"] = (strlen($arShop["TITLE"]) ? $arShop["TITLE"].', ' : '').$arShop["ADDRESS"];
				$arShop["DESCRIPTION"] = htmlspecialchars_decode($arShop['DESCRIPTION']);
				$arShop['SCHEDULE'] = htmlspecialchars_decode($arShop['SCHEDULE']);

				$str_phones = '';
				if($arShop['PHONE'])
				{
					$arShop['PHONE'] = explode(",", $arShop['PHONE']);
					$str_phones .= '<div class="phone">';
					foreach($arShop['PHONE'] as $phone)
					{
						$str_phones .= '<br><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a>';
					}
					$str_phones .= '</div>';
				}
				if($arShop['GPS_S'] && $arShop['GPS_N']){
					$mapLAT += $arShop['GPS_N'];
					$mapLON += $arShop['GPS_S'];
					$arPlacemarks[] = array(
						"ID" => $arShop["ID"],
						"LON" => $arShop['GPS_S'],
						"LAT" => $arShop['GPS_N'],
						"TEXT" => $arShop["TITLE"],
						"HTML" => '<div class="title">'.(strlen($arShop["URL"]) ? '<a href="'.$arShop["URL"].'">' : '').$arShop["ADDRESS"].(strlen($arShop["URL"]) ? '</a>' : '').'</div><div class="info-content">'.($arShop['METRO'] ? $arShop['METRO_PLACEMARK_HTML'] : '').(strlen($arShop['SCHEDULE']) ? '<div class="schedule">'.$arShop['SCHEDULE'].'</div>' : '').$str_phones.(strlen($arShop['EMAIL']) ? '<div class="email"><a rel="nofollow" href="mailto:'.$arShop['EMAIL'].'">'.$arShop['EMAIL'].'</a></div>' : '').'</div>'.(strlen($arShop['URL']) ? '<a rel="nofollow" class="button" href="'.$arShop["URL"].'"><span>'.GetMessage('DETAIL').'</span></a>' : '')
					);
				}
			}
		}
		$arFormatShops["SHOP"]=$arShop;
		$arFormatShops["PLACEMARKS"]=$arPlacemarks;
		$arFormatShops["POINTS"]=array(
			"LAT" => $mapLAT,
			"LON" => $mapLON,
		);

		return $arFormatShops;

	}

	public static function drawShopsList($arShops, $arParams, $showMap="Y"){
		global $APPLICATION;
		$mapLAT = $mapLON = 0;
		$arPlacemarks = array();

		if(is_array($arShops)){
			foreach($arShops as $i => $arShop){
				if(isset($arShop['IBLOCK_ID'])){
					$arShop['TITLE'] = (in_array('NAME', $arParams['FIELD_CODE']) ? $arShop['NAME'] : '');

					$imageID = ((in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE']) && $arShop["PREVIEW_PICTURE"]['ID']) ? $arShop["PREVIEW_PICTURE"]['ID'] : ((in_array('DETAIL_PICTURE', $arParams['FIELD_CODE']) && $arShop["DETAIL_PICTURE"]['ID']) ? $arShop["DETAIL_PICTURE"]['ID'] : false));
					$arShop['IMAGE'] = ($imageID ? CFile::ResizeImageGet($imageID, array('width' => 100, 'height' => 69), BX_RESIZE_IMAGE_EXACT) : []);
					$arShop['ADDRESS'] = $arShop['DISPLAY_PROPERTIES']['ADDRESS']['VALUE'];
					$arShop['ADDRESS'] = $arShop['TITLE'].((strlen($arShop['TITLE']) && strlen($arShop['ADDRESS'])) ? ', ' : '').$arShop['ADDRESS'];
					$arShop['PHONE'] =  $arShop['DISPLAY_PROPERTIES']['PHONE']['VALUE'];
					$arShop['EMAIL'] = $arShop['DISPLAY_PROPERTIES']['EMAIL']['VALUE'];

					if(strToLower($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['VALUE']['TYPE']) == 'html'){
						$arShop['SCHEDULE'] = htmlspecialchars_decode($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT']);
					}
					else{
						$arShop['SCHEDULE'] = nl2br($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT']);
					}
					$arShop['URL'] = $arShop['DETAIL_PAGE_URL'];
					$arShop['METRO_PLACEMARK_HTML'] = '';
					if($arShop['METRO'] = $arShop['DISPLAY_PROPERTIES']['METRO']['VALUE']){
						if(!is_array($arShop['METRO'])){
							$arShop['METRO'] = array($arShop['METRO']);
						}
						foreach($arShop['METRO'] as $metro){
							$arShop['METRO_PLACEMARK_HTML'] .= '<div class="metro"><i></i>'.$metro.'</div>';
						}
					}
					$arShop['DESCRIPTION'] = $arShop['DETAIL_TEXT'];
					$arShop['GPS_S'] = false;
					$arShop['GPS_N'] = false;
					if($arStoreMap = explode(',', $arShop['DISPLAY_PROPERTIES']['MAP']['VALUE'])){
						$arShop['GPS_S'] = $arStoreMap[0];
						$arShop['GPS_N'] = $arStoreMap[1];
					}

					if($arShop['GPS_S'] && $arShop['GPS_N']){
						$mapLAT += $arShop['GPS_S'];
						$mapLON += $arShop['GPS_N'];
						$str_phones = '';
						if($arShop['PHONE'])
						{
							foreach($arShop['PHONE'] as $phone)
							{
								$str_phones .= '<div class="phone"><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a></div>';
							}
						}
						$arPlacemarks[] = array(
							"ID" => $arShop["ID"],
							"LAT" => $arShop['GPS_S'],
							"LON" => $arShop['GPS_N'],
							"TEXT" => $arShop["TITLE"],
							"HTML" => '<div class="title">'.(strlen($arShop["URL"]) ? '<a href="'.$arShop["URL"].'">' : '').$arShop["ADDRESS"].(strlen($arShop["URL"]) ? '</a>' : '').'</div><div class="info-content">'.($arShop['METRO'] ? $arShop['METRO_PLACEMARK_HTML'] : '').(strlen($arShop['SCHEDULE']) ? '<div class="schedule">'.$arShop['SCHEDULE'].'</div>' : '').$str_phones.(strlen($arShop['EMAIL']) ? '<div class="email"><a rel="nofollow" href="mailto:'.$arShop['EMAIL'].'">'.$arShop['EMAIL'].'</a></div>' : '').'</div>'.(strlen($arShop['URL']) ? '<a rel="nofollow" class="button" href="'.$arShop["URL"].'"><span>'.GetMessage('DETAIL').'</span></a>' : '')
						);
					}
				}
				else{
					$arShop["IMAGE"] = $arShop["IMAGE"] ?: [];

					$str_phones = '';
					if($arShop['PHONE'])
					{
						$arShop['PHONE'] = explode(",", $arShop['PHONE']);
						foreach($arShop['PHONE'] as $phone)
						{
							$str_phones .= '<div class="phone"><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a></div>';
						}
					}
					if($arShop['GPS_S'] && $arShop['GPS_N']){
						$mapLAT += $arShop['GPS_N'];
						$mapLON += $arShop['GPS_S'];
						$arPlacemarks[] = array(
							"ID" => $arShop["ID"],
							"LON" => $arShop['GPS_S'],
							"LAT" => $arShop['GPS_N'],
							"TEXT" => $arShop["TITLE"],
							"HTML" => '<div class="title">'.(strlen($arShop["URL"]) ? '<a href="'.$arShop["URL"].'">' : '').$arShop["ADDRESS"].(strlen($arShop["URL"]) ? '</a>' : '').'</div><div class="info-content">'.($arShop['METRO'] ? $arShop['METRO_PLACEMARK_HTML'] : '').(strlen($arShop['SCHEDULE']) ? '<div class="schedule">'.$arShop['SCHEDULE'].'</div>' : '').$str_phones.(strlen($arShop['EMAIL']) ? '<div class="email"><a rel="nofollow" href="mailto:'.$arShop['EMAIL'].'">'.$arShop['EMAIL'].'</a></div>' : '').'</div>'.(strlen($arShop['URL']) ? '<a rel="nofollow" class="button" href="'.$arShop["URL"].'"><span>'.GetMessage('DETAIL').'</span></a>' : '')
						);
					}
				}
				$arShops[$i] = $arShop;
			}
			?>
			<?if($arShops):?>
				<?if(abs($mapLAT) > 0 && abs($mapLON) > 0 && $showMap=="Y"):?>
					<?
						$arMapTypes = ['YANDEX', 'GOOGLE'];
						
						$mapLAT = floatval($mapLAT / count($arShops));
						$mapLON = floatval($mapLON / count($arShops));
						$sMapType = isset($arParams["MAP_TYPE_FROM_MODULE"]) && $arParams["MAP_TYPE_FROM_MODULE"] === "Y" 
							? $arMapTypes[\Bitrix\Main\Config\Option::get("aspro.optimus", "MAP_TYPE", "0", SITE_ID)]
							: $arMapTypes[$arParams["MAP_TYPE"]];

					?>
					<div class="contacts_map">
						<?if($sMapType === "GOOGLE"):?>
							<?$APPLICATION->IncludeComponent(
								"bitrix:map.google.view",
								"map",
								array(
									"INIT_MAP_TYPE" => "ROADMAP",
									"MAP_DATA" => serialize(array("google_lat" => $mapLAT, "google_lon" => $mapLON, "google_scale" => 15, "PLACEMARKS" => $arPlacemarks)),
									"MAP_WIDTH" => "100%",
									"MAP_HEIGHT" => "400",
									"CONTROLS" => array(
									),
									"OPTIONS" => array(
										0 => "ENABLE_DBLCLICK_ZOOM",
										1 => "ENABLE_DRAGGING",
									),
									"MAP_ID" => "",
									"ZOOM_BLOCK" => array(
										"POSITION" => "right center",
									),
									"API_KEY" => $arParams["GOOGLE_API_KEY"],
									"COMPOSITE_FRAME_MODE" => "A",
									"COMPOSITE_FRAME_TYPE" => "AUTO"
								),
								false, array("HIDE_ICONS" =>"Y")
							);?>
						<?else:?>
							<?$APPLICATION->IncludeComponent(
								"bitrix:map.yandex.view",
								"map",
								array(
									"INIT_MAP_TYPE" => "ROADMAP",
									"MAP_DATA" => serialize(array("yandex_lat" => $mapLAT, "yandex_lon" => $mapLON, "yandex_scale" => 4, "PLACEMARKS" => $arPlacemarks)),
									"MAP_WIDTH" => "100%",
									"MAP_HEIGHT" => "400",
									"CONTROLS" => array(
										0 => "ZOOM",
										1 => "SMALLZOOM",
										3 => "TYPECONTROL",
										4 => "SCALELINE",
									),
									"OPTIONS" => array(
										0 => "ENABLE_DBLCLICK_ZOOM",
										1 => "ENABLE_DRAGGING",
									),
									"MAP_ID" => "",
									"ZOOM_BLOCK" => array(
										"POSITION" => "right center",
									),
									"COMPONENT_TEMPLATE" => "map",
									"API_KEY" => $arParams["GOOGLE_API_KEY"],
									"COMPOSITE_FRAME_MODE" => "A",
									"COMPOSITE_FRAME_TYPE" => "AUTO"
								),
								false, array("HIDE_ICONS" =>"Y")
							);?>
						<?endif;?>
					</div>
				<?endif;?>
				<div class="wrapper_inner">
					<div class="shops list">
						<div class="items">
							<?foreach($arShops as $arShop):?>
								<div class="item<?=(!$arShop["IMAGE"] || !strlen($arShop["IMAGE"]["src"]) ? ' wi' : '')?>" data-ID="<?=$arShop['ID']?>">
									<div class="image">
										<?if($arShop["IMAGE"] && strlen($arShop["IMAGE"]["src"])):?>
											<?if(strlen($arShop['URL'])):?>
												<a href="<?=$arShop['URL']?>"><img src="<?=$arShop["IMAGE"]["src"]?>" alt="<?=$arShop["ADDRESS"]?>" title="<?=$arShop["ADDRESS"]?>" /></a>
											<?else:?>
												<img src="<?=$arShop["IMAGE"]["src"]?>" alt="<?=$arShop["ADDRESS"]?>" title="<?=$arShop["ADDRESS"]?>" />
											<?endif;?>
										<?endif;?>
									</div>
									<div class="rubber">
										<div class="title_metro">
											<?if(strlen($arShop["ADDRESS"])):?>
												<?if(strlen($arShop['URL'])):?>
													<a href="<?=$arShop['URL']?>"><div class="title"><?=$arShop["ADDRESS"]?></div></a>
												<?else:?>
													<div class="title"><?=$arShop["ADDRESS"]?></div>
												<?endif;?>
											<?endif;?>
											<?if($arShop["METRO"]):?>
												<?foreach($arShop['METRO'] as $metro):?>
													<div class="metro"><i></i><?=$metro?></div>
												<?endforeach;?>
											<?endif;?>
										</div>
										<div class="schedule_phone_email">
											<div class="schedule"><?=$arShop["SCHEDULE"]?></div>
											<div class="phone_email">
												<?if($arShop["PHONE"]):?>
													<?foreach($arShop["PHONE"] as $phone):?>
														<div class="phone"><a rel="nofollow" href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $phone);?>"><?=$phone;?></a></div>
													<?endforeach;?>
												<?endif;?>
												<?if(strlen($arShop["EMAIL"])):?>
													<div class="email"><a rel="nofollow" href="mailto:<?=$arShop["EMAIL"]?>"><?=$arShop["EMAIL"]?></a></div>
												<?endif;?>
											</div>
										</div>
									</div>
								</div>
							<?endforeach;?>
						</div>
					</div>
				</div>
				<div class="clearboth"></div>
			<?endif;?>
			<?
		}
		else{
			LocalRedirect(SITE_DIR.'contacts/');
		}
	}

	public static function drawShopDetail($arShop, $arParams, $showMap="Y"){
		global $APPLICATION;
		$mapLAT = $mapLON = 0;
		$arPlacemarks = array();
		$arPhotos = array();

		if(is_array($arShop)){
			if(isset($arShop['IBLOCK_ID'])){
				$arShop['LIST_URL'] = $arShop['LIST_PAGE_URL'];
				$arShop['TITLE'] = (in_array('NAME', $arParams['FIELD_CODE']) ? $arShop['NAME'] : '');
				$arShop['ADDRESS'] = $arShop['DISPLAY_PROPERTIES']['ADDRESS']['VALUE'];
				$arShop['ADDRESS'] = $arShop['TITLE'].((strlen($arShop['TITLE']) && strlen($arShop['ADDRESS'])) ? ', ' : '').$arShop['ADDRESS'];
				$arShop['PHONE'] = $arShop['DISPLAY_PROPERTIES']['PHONE']['VALUE'];
				$arShop['EMAIL'] = $arShop['DISPLAY_PROPERTIES']['EMAIL']['VALUE'];
				if(strToLower($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['VALUE']['TYPE']) == 'html'){
					$arShop['SCHEDULE'] = htmlspecialchars_decode($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT']);
				}
				else{
					$arShop['SCHEDULE'] = nl2br($arShop['DISPLAY_PROPERTIES']['SCHEDULE']['~VALUE']['TEXT']);
				}
				$arShop['URL'] = $arShop['DETAIL_PAGE_URL'];
				$arShop['METRO_PLACEMARK_HTML'] = '';
				if($arShop['METRO'] = $arShop['DISPLAY_PROPERTIES']['METRO']['VALUE']){
					if(!is_array($arShop['METRO'])){
						$arShop['METRO'] = array($arShop['METRO']);
					}
					foreach($arShop['METRO'] as $metro){
						$arShop['METRO_PLACEMARK_HTML'] .= '<div class="metro"><i></i>'.$metro.'</div>';
					}
				}
				$arShop['DESCRIPTION'] = $arShop['DETAIL_TEXT'];
				$imageID = ((in_array('DETAIL_PICTURE', $arParams['FIELD_CODE']) && $arShop["DETAIL_PICTURE"]['ID']) ? $arShop["DETAIL_PICTURE"]['ID'] : false);
				if($imageID){
					$arShop['IMAGE'] = CFile::ResizeImageGet($imageID, array('width' => 210, 'height' => 143), BX_RESIZE_IMAGE_EXACT);
					$arPhotos[] = array(
						'ID' => $arShop["DETAIL_PICTURE"]['ID'],
						'ORIGINAL' => ($arShop["DETAIL_PICTURE"]['SRC'] ? $arShop["DETAIL_PICTURE"]['SRC'] : $arShop['IMAGE']),
						'PREVIEW' => $arShop['IMAGE'],
						'DESCRIPTION' => (strlen($arShop["DETAIL_PICTURE"]['DESCRIPTION']) ? $arShop["DETAIL_PICTURE"]['DESCRIPTION'] : $arShop['ADDRESS']),
					);
				}
				if(is_array($arShop['DISPLAY_PROPERTIES']['MORE_PHOTOS']['VALUE'])) {
					foreach($arShop['DISPLAY_PROPERTIES']['MORE_PHOTOS']['VALUE'] as $i => $photoID){
						$arPhotos[] = array(
							'ID' => $photoID,
							'ORIGINAL' => CFile::GetPath($photoID),
							'PREVIEW' => CFile::ResizeImageGet($photoID, array('width' => 210, 'height' => 143), BX_RESIZE_IMAGE_EXACT),
							'DESCRIPTION' => $arShop['DISPLAY_PROPERTIES']['MORE_PHOTOS']['DESCRIPTION'][$i],
						);
					}
				}

				$arShop['GPS_S'] = false;
				$arShop['GPS_N'] = false;
				if($arStoreMap = explode(',', $arShop['DISPLAY_PROPERTIES']['MAP']['VALUE'])){
					$arShop['GPS_S'] = $arStoreMap[0];
					$arShop['GPS_N'] = $arStoreMap[1];
				}

				if($arShop['GPS_S'] && $arShop['GPS_N']){
					$mapLAT += $arShop['GPS_S'];
					$mapLON += $arShop['GPS_N'];
					$str_phones = '';
					if($arShop['PHONE'])
					{
						foreach($arShop['PHONE'] as $phone)
						{
							$str_phones .= '<div class="phone"><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a></div>';
						}
					}
					$arPlacemarks[] = array(
						"ID" => $arShop["ID"],
						"LAT" => $arShop['GPS_S'],
						"LON" => $arShop['GPS_N'],
						"TEXT" => $arShop["TITLE"],
						"HTML" => '<div class="title">'.(strlen($arShop["URL"]) ? '<a href="'.$arShop["URL"].'">' : '').$arShop["ADDRESS"].(strlen($arShop["URL"]) ? '</a>' : '').'</div><div class="info-content">'.($arShop['METRO'] ? $arShop['METRO_PLACEMARK_HTML'] : '').(strlen($arShop['SCHEDULE']) ? '<div class="schedule">'.$arShop['SCHEDULE'].'</div>' : '').$str_phones.(strlen($arShop['EMAIL']) ? '<div class="email"><a rel="nofollow" href="mailto:'.$arShop['EMAIL'].'">'.$arShop['EMAIL'].'</a></div>' : '').'</div>'.(strlen($arShop['URL']) ? '<a rel="nofollow" class="button" href="'.$arShop["URL"].'"><span>'.GetMessage('DETAIL').'</span></a>' : '')
					);
				}
			}
			else{
				$arShop["TITLE"] = htmlspecialchars_decode($arShop["TITLE"]);
				$arShop["ADDRESS"] = htmlspecialchars_decode($arShop["ADDRESS"]);
				$arShop["ADDRESS"] = (strlen($arShop["TITLE"]) ? $arShop["TITLE"].', ' : '').$arShop["ADDRESS"];
				$arShop["DESCRIPTION"] = htmlspecialchars_decode($arShop['DESCRIPTION']);
				$arShop['SCHEDULE'] = htmlspecialchars_decode($arShop['SCHEDULE']);
				if($arShop["IMAGE_ID"]  && $arShop["IMAGE_ID"] != "null"){
					$arShop['IMAGE'] = CFile::ResizeImageGet($arShop["IMAGE_ID"], array('width' => 210, 'height' => 143), BX_RESIZE_IMAGE_EXACT);
					$arPhotos[] = array(
						'ID' => $arShop["PREVIEW_PICTURE"]['ID'],
						'ORIGINAL' => CFile::GetPath($arShop["IMAGE_ID"]),
						'PREVIEW' => $arShop['IMAGE'],
						'DESCRIPTION' => (strlen($arShop["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arShop["PREVIEW_PICTURE"]['DESCRIPTION'] : $arShop["ADDRESS"]),
					);
				}
				if(is_array($arShop['MORE_PHOTOS'])) {
					foreach($arShop['MORE_PHOTOS'] as $photoID){
						$arPhotos[] = array(
							'ID' => $photoID,
							'ORIGINAL' => CFile::GetPath($photoID),
							'PREVIEW' => CFile::ResizeImageGet($photoID, array('width' => 210, 'height' => 143), BX_RESIZE_IMAGE_EXACT),
							'DESCRIPTION' => $arShop["ADDRESS"],
						);
					}
				}

				$str_phones = '';
				if($arShop['PHONE'])
				{
					$arShop['PHONE'] = explode(",", $arShop['PHONE']);
					foreach($arShop['PHONE'] as $phone)
					{
						$str_phones .= '<div class="phone"><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a></div>';
					}
				}
				if($arShop['GPS_S'] && $arShop['GPS_N']){
					$mapLAT += $arShop['GPS_N'];
					$mapLON += $arShop['GPS_S'];
					$arPlacemarks[] = array(
						"ID" => $arShop["ID"],
						"LON" => $arShop['GPS_S'],
						"LAT" => $arShop['GPS_N'],
						"TEXT" => $arShop["TITLE"],
						"HTML" => '<div class="title">'.(strlen($arShop["URL"]) ? '<a href="'.$arShop["URL"].'">' : '').$arShop["ADDRESS"].(strlen($arShop["URL"]) ? '</a>' : '').'</div><div class="info-content">'.($arShop['METRO'] ? $arShop['METRO_PLACEMARK_HTML'] : '').(strlen($arShop['SCHEDULE']) ? '<div class="schedule">'.$arShop['SCHEDULE'].'</div>' : '').$str_phones.(strlen($arShop['EMAIL']) ? '<div class="email"><a rel="nofollow" href="mailto:'.$arShop['EMAIL'].'">'.$arShop['EMAIL'].'</a></div>' : '').'</div>'.(strlen($arShop['URL']) ? '<a rel="nofollow" class="button" href="'.$arShop["URL"].'"><span>'.GetMessage('DETAIL').'</span></a>' : '')
					);
				}
			}
			?>
			<?if(abs($mapLAT) > 0 && abs($mapLON) > 0 && $showMap=="Y"):?>
				<? 
					$arMapTypes = ['YANDEX', 'GOOGLE'];
					$sMapType = isset($arParams["MAP_TYPE_FROM_MODULE"]) && $arParams["MAP_TYPE_FROM_MODULE"] === "Y" 
						? $arMapTypes[\Bitrix\Main\Config\Option::get("aspro.optimus", "MAP_TYPE", "0", SITE_ID)]
						: $arMapTypes[$arParams["MAP_TYPE"]];
				?>
				<div class="contacts_map">
					<?if($sMapType === "GOOGLE"):?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:map.google.view",
							"map",
							array(
								"INIT_MAP_TYPE" => "ROADMAP",
								"MAP_DATA" => serialize(array("google_lat" => $mapLAT, "google_lon" => $mapLON, "google_scale" => 16, "PLACEMARKS" => $arPlacemarks)),
								"MAP_WIDTH" => "100%",
								"MAP_HEIGHT" => "400",
								"CONTROLS" => array(
								),
								"OPTIONS" => array(
									0 => "ENABLE_DBLCLICK_ZOOM",
									1 => "ENABLE_DRAGGING",
								),
								"MAP_ID" => "",
								"ZOOM_BLOCK" => array(
									"POSITION" => "right center",
								),
								"COMPONENT_TEMPLATE" => "map",
								"API_KEY" => $arParams["GOOGLE_API_KEY"],
								"COMPOSITE_FRAME_MODE" => "A",
								"COMPOSITE_FRAME_TYPE" => "AUTO"
							),
							false, array("HIDE_ICONS" =>"Y")
						);?>
					<?else:?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:map.yandex.view",
							"",
							array(
								"INIT_MAP_TYPE" => "ROADMAP",
								"MAP_DATA" => serialize(array("yandex_lat" => $mapLAT, "yandex_lon" => $mapLON, "yandex_scale" => 17, "PLACEMARKS" => $arPlacemarks)),
								"MAP_WIDTH" => "100%",
								"MAP_HEIGHT" => "400",
								"CONTROLS" => array(
									0 => "ZOOM",
									1 => "SMALLZOOM",
									3 => "TYPECONTROL",
									4 => "SCALELINE",
								),
								"OPTIONS" => array(
									0 => "ENABLE_DBLCLICK_ZOOM",
									1 => "ENABLE_DRAGGING",
								),
								"MAP_ID" => "",
								"ZOOM_BLOCK" => array(
									"POSITION" => "right center",
								),
								"COMPONENT_TEMPLATE" => "map",
								"API_KEY" => $arParams["GOOGLE_API_KEY"],
								"COMPOSITE_FRAME_MODE" => "A",
								"COMPOSITE_FRAME_TYPE" => "AUTO"
							),
							false, array("HIDE_ICONS" =>"Y")
						);?>
					<?endif;?>
				</div>
			<?endif;?>
			<div class="wrapper_inner shop detail">
				<div class="contacts_left">
					<div class="store_description">
						<?if(strlen($arShop['ADDRESS'])):?>
							<div class="store_property">
								<div class="title"><?=GetMessage('ADDRESS')?></div>
								<div class="value"><?=$arShop['ADDRESS']?></div>
							</div>
						<?endif;?>
						<?if($arShop['METRO']):?>
							<div class="store_property metro">
								<div class="title"><?=GetMessage('METRO')?></div>
								<?foreach($arShop['METRO'] as $metro):?>
									<div class="value"><i></i><?=$metro?></div>
								<?endforeach;?>
							</div>
						<?endif;?>
						<?if($arShop['PHONE']):?>
							<div class="store_property">
								<div class="title"><?=GetMessage('PHONE')?></div>
								<div class="value">
									<?foreach($arShop["PHONE"] as $phone):?>
										<div class="phone"><a rel="nofollow" href="tel:<?=str_replace(array(' ', ',', '-', '(', ')'), '', $phone);?>"><?=$phone;?></a></div>
									<?endforeach;?>
								</div>
							</div>
						<?endif;?>
						<?if(strlen($arShop['EMAIL'])):?>
							<div class="store_property">
								<div class="title">Email</div>
								<div class="value"><a rel="nofollow" href="mailto:<?=$arShop['EMAIL']?>"><?=$arShop['EMAIL']?></a></div>
							</div>
						<?endif;?>
						<?if(strlen($arShop['SCHEDULE'])):?>
							<div class="store_property">
								<div class="title"><?=GetMessage('SCHEDULE')?></div>
								<div class="value"><?=$arShop['SCHEDULE']?></div>
							</div>
						<?endif;?>
					</div>
				</div>
				<div class="contacts_right">
					<?if($arShop['DESCRIPTION']):?>
						<blockquote><?=$arShop['DESCRIPTION']?></blockquote>
					<?endif;?>
					<?if($arPhotos):?>
						<!-- noindex-->
						<?foreach($arPhotos as $arPhoto):?>
							<a class="fancy" data-fancybox-group="item_slider" title="<?=$arPhoto['DESCRIPTION']?>" href="<?=$arPhoto['ORIGINAL']?>"><img title="<?=$arPhoto['DESCRIPTION']?>" alt="<?=$arPhoto['DESCRIPTION']?>" src="<?=$arPhoto['PREVIEW']['src']?>"></a>
						<?endforeach;?>
						<!-- /noindex-->
					<?endif;?>
				</div>
				<div class="clearboth"></div>
				<!-- noindex--><a rel="nofollow" href="<?=$arShop['LIST_URL']?>" class="back"><?=GetMessage('BACK_STORE_LIST')?></a><!-- /noindex-->
			</div>
			<div class="clearboth"></div>
			<?
		}
		else{
			LocalRedirect(SITE_DIR.'contacts/');
		}
	}

	public static function showIconSvg($options){
		$text ='';

		$class = ( isset($options['CLASS']) ? $options['CLASS'] : 'phone' );
		$path = ( isset($options['PATH']) ? $options['PATH'] : '' );
		$title = ( isset($options['TITLE']) ? $options['TITLE'] : '' );
		$class_icon = ( isset($options['CLASS_ICON']) ? $options['CLASS_ICON'] : '' );
		$show_wrapper = ( isset($options['SHOW_WRAPPER']) ? $options['SHOW_WRAPPER'] : true );
		$binline = ( isset($options['INLINE']) ? $options['INLINE'] : true );

		if(self::checkContentFile($path))
		{
			static $svg_call;
			$iSvgID = ++$svg_call;

			$content = File::getFileContents($_SERVER['DOCUMENT_ROOT'].$path);

			$pattern = '/(<svg[^\>]*?|<path[^\>]*?)(id\s*=[\'\"].*?[\'\"])/i';
			$content = preg_replace($pattern, '${1}', $content);

			$pattern = '/(<svg[^>]*?>[^\/]*?)<metadata>.*?<\/metadata>/is';
			$content = preg_replace($pattern, '${1}', $content);

			$pattern = '/>(\s+?)</i';
			$content = preg_replace($pattern, '><', $content);

			$pattern = '/<style>.*?<\//is';
			preg_match_all($pattern, $content, $matches);
			$need = str_replace(array(' ', PHP_EOL), '', $matches[0]);
			$content = str_replace($matches[0], $need, $content);

			$content = str_replace('markID', $iSvgID, $content);

			if($show_wrapper)
				$text = '<i class="svg '.($binline ? 'inline ' : '').$class_icon.' svg-inline-'.$class.'" aria-hidden="true" '.($title ? 'title="'.$title.'"' : '').'>';

				$text .= $content;

			if($show_wrapper)
				$text .= '</i>';
		}

		return $text;
	}

	public static function checkContentFile($path){
		if(File::isFileExists($_SERVER['DOCUMENT_ROOT'].$path))
			$content = File::getFileContents($_SERVER['DOCUMENT_ROOT'].$path);
		return (!empty($content));
	}

	public static function nlo($code, $attrs = ''){
		static $arAvailable, $isStarted, $arNlo;

		if(!isset($arAvailable)){
			$arAvailable = array(
				'menu-catalog' => $GLOBALS['TEMPLATE_OPTIONS']['NLO_MENU']['CURRENT_VALUE'] === 'Y',
			);

			$arNlo = array();
		}

		if($arAvailable[$code]){
			if(
				isset($_REQUEST['nlo']) &&
				$_REQUEST['nlo'] === $code
			){
				if(isset($isStarted)){
					die();
				}

				$isStarted = true;
				$GLOBALS['APPLICATION']->RestartBuffer();
				return true;
			}
			else{
				if($arNlo[$code]){
					if($code == 'menu-catalog'){
						echo '</li>';
					}else{
						echo '</div>';
					}
						
				}
				else{
					$arNlo[$code] = true;
					if($code == 'menu-catalog'){
						echo '<li '.(strlen($attrs) ? $attrs : '').' data-nlo="'.$code.'">';
					}else{
						echo '<div '.(strlen($attrs) ? $attrs : '').' data-nlo="'.$code.'">';
					}					
				}

				return false;
			}
		}

		return true;
	}

	public static function ShowBasketWithCompareLink($class_link='top-btn hover', $class_icon='', $show_price = false, $class_block='', $force_show = false, $bottom = false){?>
		<?global $APPLICATION, $arBasketPrices;
		$arCounters = self::getBasketCounters();

		static $basket_call;
		$type_svg = '';
		if($class_icon)
		{
			$tmp = explode(' ', $class_icon);
			$type_svg = '_'.$tmp[0];
		}

		$iCalledID = ++$basket_call;?>
			<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID('header-basket-with-compare-block'.$iCalledID);?>

				<?if(COption::GetOptionString(self::moduleID, "SHOW_COMPARE", SITE_ID) != 'N'):?>
					<?
						$compare_items = "";
						ob_start();
						$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
							array(
								"COMPONENT_TEMPLATE" => ".default",
								"PATH" => SITE_DIR."ajax/show_compare_preview_top.php",
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "",
								"AREA_FILE_RECURSIVE" => "Y",
								"CLASS_LINK" => $class_link,
								"CLASS_ICON" => $class_icon,
								"FROM_MODULE" => "Y",
								"EDIT_TEMPLATE" => "standard.php",
							),
							false, array('HIDE_ICONS' => 'Y')
						);
						$compare_items = trim(ob_get_contents());
						ob_end_clean();
					?>
					<div class="basket-link compare<?= strpos($compare_items, 'count') === false ? ' no-items' : null; ?>">
						<?= $compare_items; ?>
					</div>
				<?endif;?>
				<?if(self::getShowBasket()):?>
					<!-- noindex -->
					<?if($class_block):?>
						<span class="<?=$class_block;?>">
					<?endif;?>
						<div class="basket-link delay<?= !$arBasketPrices['DELAY_COUNT'] ? ' no-items' : null; ?>">
							<div class="wraps_icon_block delay">
								<a rel="nofollow" 
									class="link"
									href="<?= $arCounters['DELAY']['HREF']; ?>" title="<?=$arBasketPrices['DELAY_SUMM_TITLE'];?>"
								></a>
								<span class="js-basket-block">
									<span class="title dark_link"><?=Loc::getMessage('JS_BASKET_DELAY_TITLE');?></span>
									<span class="count <?= $arBasketPrices['DELAY_COUNT'] === 0 ? 'hidden' : null; ?>">
										<span>
											<div class="items">
												<div class="text"><?= $arBasketPrices['DELAY_COUNT']; ?></div>
											</div>
										</span>
									</span>
								</span>
							</div>
						</div>
					<?if($class_block):?>
						</span>
					<?endif;?>
					<?if($class_block):?>
						<span class="<?=$class_block;?>">
					<?endif;?>
						<div class="basket-link basket<?= !$arBasketPrices['BASKET_COUNT'] ? ' no-items' : null; ?>" >
							<div class="wraps_icon_block basket">
								<a rel="nofollow" 
									class="link"
									href="<?= $arCounters['READY']['HREF']; ?>" 
									title="<?= $arBasketPrices['BASKET_SUMM_TITLE']; ?>"
								></a>
								<span class="js-basket-block">
									<?if($show_price):?>
										<span class="wrap">
									<?endif;?>
									<span class="title dark_link"><?=Loc::getMessage('JS_BASKET_TITLE');?><span class="count"><?=$arBasketPrices['BASKET_COUNT'];?></span></span>
									<?if($show_price):?>
										<span class="prices"><?=($arBasketPrices['BASKET_COUNT'] ? $arBasketPrices['BASKET_SUMM'] : $arBasketPrices['BASKET_SUMM_TITLE_SMALL'] )?></span>
										</span>
									<?endif;?>
									<span class="count <?= $arBasketPrices['BASKET_COUNT'] === 0 ? 'hidden' : null; ?>">
										<span>
											<div class="items">
												<div class="text"><?=$arBasketPrices['BASKET_COUNT'];?></div>
											</div>
										</span>
									</span>
								</span>
							</div>
						</div>
					<?if($class_block):?>
						</span>
					<?endif;?>
					<!-- /noindex -->
				<?endif;?>
			<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID('header-basket-with-compare-block'.$iCalledID, '');?>
	<?}

	public static function GetCurrentSectionElementFilter(&$arVariables, &$arParams, $CurrentSectionID = false) {
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'N');
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'SECTION_GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(!$arFilter['SECTION_ID'] = ($CurrentSectionID !== false ? $CurrentSectionID : ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false))){
			unset($arFilter['SECTION_GLOBAL_ACTIVE']);
		}
		if(strlen($arParams['FILTER_NAME'])){
			$GLOBALS[$arParams['FILTER_NAME']] = (array)$GLOBALS[$arParams['FILTER_NAME']];
			foreach($arUnsetFilterFields = array('SECTION_ID', 'SECTION_CODE', 'SECTION_ACTIVE', 'SECTION_GLOBAL_ACTIVE') as $filterUnsetField){
				foreach($GLOBALS[$arParams['FILTER_NAME']] as $filterField => $filterValue){
					if(($p = strpos($filterUnsetField, $filterField)) !== false && $p < 2){
						unset($GLOBALS[$arParams['FILTER_NAME']][$filterField]);
					}
				}
			}
			if($GLOBALS[$arParams['FILTER_NAME']]){
				$arFilter = array_merge($arFilter, $GLOBALS[$arParams['FILTER_NAME']]);
			}
		}
		return $arFilter;
	}

	public static function GetCurrentSectionSubSectionFilter(&$arVariables, &$arParams, $CurrentSectionID = false){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
		if($arParams['CHECK_DATES'] == 'Y')
		{
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(!$arFilter['SECTION_ID'] = ($CurrentSectionID !== false ? $CurrentSectionID : ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false)))
		{
			$arFilter['INCLUDE_SUBSECTIONS'] = 'N';array_merge($arFilter, array('INCLUDE_SUBSECTIONS' => 'N', 'DEPTH_LEVEL' => '1'));
			$arFilter['DEPTH_LEVEL'] = '1';
			unset($arFilter['GLOBAL_ACTIVE']);
		}
		return $arFilter;
	}

	public static function getFieldImageData(array &$arItem, array $arKeys, $entity = 'ELEMENT', $ipropertyKey = 'IPROPERTY_VALUES'){
		if (empty($arItem) || empty($arKeys))
            return;

        $entity = (string)$entity;
        $ipropertyKey = (string)$ipropertyKey;

        foreach ($arKeys as $fieldName)
        {
            if(!isset($arItem[$fieldName]) || (!isset($arItem['~'.$fieldName]) || !$arItem['~'.$fieldName]))
                continue;
            $imageData = false;
            $imageId = (int)$arItem['~'.$fieldName];
            if ($imageId > 0)
                $imageData = \CFile::getFileArray($imageId);
            unset($imageId);
            if (is_array($imageData))
            {
                if (isset($imageData['SAFE_SRC']))
                {
                    $imageData['UNSAFE_SRC'] = $imageData['SRC'];
                    $imageData['SRC'] = $imageData['SAFE_SRC'];
                }
                else
                {
                    $imageData['UNSAFE_SRC'] = $imageData['SRC'];
                    $imageData['SRC'] = \CHTTP::urnEncode($imageData['SRC'], 'UTF-8');
                }
                $imageData['ALT'] = '';
                $imageData['TITLE'] = '';

                if ($ipropertyKey != '' && isset($arItem[$ipropertyKey]) && is_array($arItem[$ipropertyKey]))
                {
                    $entityPrefix = $entity.'_'.$fieldName;
                    if (isset($arItem[$ipropertyKey][$entityPrefix.'_FILE_ALT']))
                        $imageData['ALT'] = $arItem[$ipropertyKey][$entityPrefix.'_FILE_ALT'];
                    if (isset($arItem[$ipropertyKey][$entityPrefix.'_FILE_TITLE']))
                        $imageData['TITLE'] = $arItem[$ipropertyKey][$entityPrefix.'_FILE_TITLE'];
                    unset($entityPrefix);
                }
                if ($imageData['ALT'] == '' && isset($arItem['NAME']))
                    $imageData['ALT'] = $arItem['NAME'];
                if ($imageData['TITLE'] == '' && isset($arItem['NAME']))
                    $imageData['TITLE'] = $arItem['NAME'];
            }
            $arItem[$fieldName] = $imageData;
            unset($imageData);
        }

        unset($fieldName);
	}

	public static function GetCurrentSectionFilter(&$arVariables, &$arParams){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if($arVariables['SECTION_ID']){
			$arFilter['ID'] = $arVariables['SECTION_ID'];
		}
		if(strlen($arVariables['SECTION_CODE'])){
			$arFilter['CODE'] = $arVariables['SECTION_CODE'];
		}
		if(!$arVariables['SECTION_ID'] && !strlen($arFilter['CODE'])){
			$arFilter['ID'] = 0; // if section not found
		}
		return $arFilter;
	}


	public static function setThemeColorsValues($siteId = '') {
		$siteId = strlen($siteId) ? $siteId : (defined('SITE_ID') ? SITE_ID : '');

		$css = '';
		foreach (self::getThemeColorsValues($siteId) as $property => $value) {
			$css .= $property.':'.$value.';';
		}

		$GLOBALS['APPLICATION']->AddHeadString('<style>html {'.$css.'}</style>');
	}

	
	public static function setCssVariables(){		
		// set theme colors vars values
		self::setThemeColorsValues();
	}

	public static function getThemeColorsValues($siteId = '') {
		global $TEMPLATE_OPTIONS;

		$siteId = strlen($siteId) ? $siteId : (defined('SITE_ID') ? SITE_ID : '');
		$isBaseCustom = $TEMPLATE_OPTIONS['COLOR_THEME']['CURRENT_VALUE'] == 'CUSTOM';

		if ($isBaseCustom) {
			$customColorCurrent = $TEMPLATE_OPTIONS['CUSTOM_COLOR_THEME']['CURRENT_VALUE'];
			$baseColor = (strpos($customColorCurrent, '#') === false ? '#' : '').$customColorCurrent;

		} else {
	
			$defaultColor = $TEMPLATE_OPTIONS['COLOR_THEME']['DEFAULT'];
			$baseColorSelectKey = array_search($defaultColor, array_column($TEMPLATE_OPTIONS['COLOR_THEME']['VALUES'], 'VALUE'));
			$baseColorCurrentValue = trim($TEMPLATE_OPTIONS['COLOR_THEME']['CURRENT_VALUE']);

			if($baseColorCurrentValue){
				$baseColorSelectKey = array_search($baseColorCurrentValue, array_column($TEMPLATE_OPTIONS['COLOR_THEME']['VALUES'], 'VALUE'));
			}
			$baseColor = $TEMPLATE_OPTIONS['COLOR_THEME']['VALUES'][$baseColorSelectKey]['COMPONENT_VALUE'];
		}


		return [
			'--theme-base-color' => $baseColor,
		];
	}
}
?>