<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if($GET["debug"] == "y"){
	error_reporting(E_ERROR | E_PARSE);
}
$filePath = $_SERVER['DOCUMENT_ROOT'] . '/index.php';

// �������� ����� ���������� ��������� ����� 
$fileModifiedTime = filemtime($filePath);

// ����������� ����� � ������ GMT
$lastModifiedGMT = gmdate("D, d M Y H:i:s", $fileModifiedTime) . " GMT";

// ������������� ��������� Last-Modified
header("Last-Modified: $lastModifiedGMT");

// ��������� If-Modified-Since ��������� � ���������� ������ 304, ���� �������� �� ����������
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $fileModifiedTime) {
    header("HTTP/1.1 304 Not Modified");
    exit;
}
IncludeTemplateLangFile(__FILE__);
global $APPLICATION, $TEMPLATE_OPTIONS, $arSite;
$arSite = CSite::GetByID(SITE_ID)->Fetch();
$htmlClass = ($_REQUEST && isset($_REQUEST['print']) ? 'print' : false);
?><!DOCTYPE html>
<html xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" xmlns="http://www.w3.org/1999/xhtml" <?=($htmlClass ? 'class="'.$htmlClass.'"' : '')?>>
<head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-MK2T17MDGL"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
 
  gtag('config', 'G-MK2T17MDGL');
</script>
<?/*$requests_keys = array_keys($_REQUEST);
foreach ($requests_keys as $request_key) {
    if (mb_ereg_match('PAGEN_', $request_key)) {
        echo '<link href="https://'.$_SERVER['HTTP_HOST'].strtok($_SERVER[REQUEST_URI], '?').'" rel="canonical" />';
               break;
    }
}*/?>
<?$canonical = $APPLICATION->GetCurPage();
if ((!stripos($canonical, 'catalog/')) && (!stripos($APPLICATION->GetCurUri(), 'PAGEN'))) {?>
<link href="<?=$canonical?>" rel="canonical" />
<?}?>
<meta name="facebook-domain-verification" content="s7sbh01ahj8j8hb6uvjic6zfl1pq0p">
<!-- Yandex.Metrika counter -->
<script>
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(82347739, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<?/*!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '335985968216589');
fbq('track', 'PageView');
</script>
<!-- End Facebook Pixel Code --*/?>
	<title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->ShowMeta("viewport");?>
	<?$APPLICATION->ShowMeta("HandheldFriendly");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
	<?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
	<link href="<?=SITE_TEMPLATE_PATH?>/css/owl.carousel.min.css" rel="stylesheet">
	<?$APPLICATION->ShowHead();?>
	<?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject( $MESS, false ).')</script>', true);?>
	<link href="<?=SITE_TEMPLATE_PATH?>/css/bootstrap.css" rel="stylesheet">
	<link href="<?=SITE_TEMPLATE_PATH?>/css/style.css" rel="stylesheet">
	<link href="<?=SITE_TEMPLATE_PATH?>/css/animate.css" rel="stylesheet">
	<link href="<?=SITE_TEMPLATE_PATH?>/css/xoverlay.css" rel="stylesheet">
	<script src="<?=SITE_TEMPLATE_PATH?>/js/wow.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/owl.carousel.min.js"></script>
	<?/*script src="<?=SITE_TEMPLATE_PATH?>/js/bootstrap.js"></script*/?>
    
	<?if(CModule::IncludeModule("aspro.optimus")) {COptimus::Start(SITE_ID);}?>
	<!--[if gte IE 9]><style type="text/css">.basket_button, .button30, .icon {filter: none;}</style><![endif]-->
	<?$bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false);?>
<?if(!$bIndexBot):?><link href='<?=CMain::IsHTTPS() ? 'https' : 'http'?>://fonts.googleapis.com/css?family=Ubuntu:400,500,700,400italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'><?endif;?>
	
</head>
	<body class='<?=($bIndexBot ? "wbot" : "");?>' id="main">
<?/*noscript><img alt="" height="1" width="1" class="display_none"
src="https://www.facebook.com/tr?id=335985968216589&ev=PageView&noscript=1"
/></noscript*/?>
<noscript><div><img src="https://mc.yandex.ru/watch/82347739" class="metrika_style" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<noscript><div><img src="https://mc.yandex.ru/watch/82347739" class="metrika_style" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
		<div id="panel"><?$APPLICATION->ShowPanel();?></div>
		<?if(!CModule::IncludeModule("aspro.optimus")){?><center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center></body></html><?die();?><?}?>
		<?$APPLICATION->IncludeComponent("aspro:theme.optimus", ".default", array("COMPONENT_TEMPLATE" => ".default"), false);?>
		<?COptimus::SetJSOptions();?>
			<?if($TEMPLATE_OPTIONS["MOBILE_FILTER_COMPACT"]["CURRENT_VALUE"] === "Y"):?>
			    <div id="mobilefilter" class="visible-xs visible-sm scrollbar-filter"></div>
			<?endif;?>
		<div class="wrapper <?=(COptimus::getCurrentPageClass());?> basket_<?=strToLower($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"]);?> <?=strToLower($TEMPLATE_OPTIONS["MENU_COLOR"]["CURRENT_VALUE"]);?> banner_auto">
			<div class="header_wrap <?=strtolower($TEMPLATE_OPTIONS["HEAD_COLOR"]["CURRENT_VALUE"])?>">
				<?if($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"]=="NORMAL"){?>
					<div class="top-h-row">
						<div class="wrapper_inner">
							<div class="top_inner">
								<div class="content_menu">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
										array(
											"COMPONENT_TEMPLATE" => ".default",
											"PATH" => SITE_DIR."include/topest_page/menu.top_content_row.php",
											"AREA_FILE_SHOW" => "file",
											"AREA_FILE_SUFFIX" => "",
											"AREA_FILE_RECURSIVE" => "Y",
											"EDIT_TEMPLATE" => "standard.php"
										),
										false
									);?>
								</div>
								<div class="phones">
									<div class="phone_block">
										<span class="phone_wrap">
											<span class="phone_text">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
													array(
														"COMPONENT_TEMPLATE" => ".default",
														"PATH" => SITE_DIR."include/phone.php",
														"AREA_FILE_SHOW" => "file",
														"AREA_FILE_SUFFIX" => "",
														"AREA_FILE_RECURSIVE" => "Y",
														"EDIT_TEMPLATE" => "standard.php"
													),
													false
												);?>
											</span>
										</span>
									</div>
								</div>
								<div class="h-user-block" id="personal_block">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
										array(
											"COMPONENT_TEMPLATE" => ".default",
											"PATH" => SITE_DIR."include/topest_page/auth.top.php",
											"AREA_FILE_SHOW" => "file",
											"AREA_FILE_SUFFIX" => "",
											"AREA_FILE_RECURSIVE" => "Y",
											"EDIT_TEMPLATE" => "standard.php"
										),
										false
									);?>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				<?}?>
				<header id="header" class="page-header">
					<div class="wrapper_inner">
					
						<div class="top_br"></div>
						
						<table class="middle-h-row">
						
							<tr>
								<td class="logo_wrapp">
									<div class="logo nofill_<?=strtolower(\Bitrix\Main\Config\Option::get('aspro.optimus', 'NO_LOGO_BG', 'N'));?>">
										<?COptimus::ShowLogo();?>
									</div>
								</td>
								<td class="text_wrapp">
									<div class="slogan">
										<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
											array(
												"COMPONENT_TEMPLATE" => ".default",
												"PATH" => SITE_DIR."include/top_page/slogan.php",
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "",
												"AREA_FILE_RECURSIVE" => "Y",
												"EDIT_TEMPLATE" => "standard.php"
											),
											false
										);?>	
									</div>
								</td>
								<td  class="center_block">																	
									<div class="search">
										<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
											array(
												"COMPONENT_TEMPLATE" => ".default",
												"PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "",
												"AREA_FILE_RECURSIVE" => "Y",
												"EDIT_TEMPLATE" => "standard.php"
											),
											false
										);?>
									</div>
								</td>
								<td class="basket_wrapp">
									<?if($TEMPLATE_OPTIONS["BASKET"]["CURRENT_VALUE"] == "NORMAL"){?>
										<div class="wrapp_all_icons">
											<div class="header-compare-block icon_block iblock compare-line" id="compare_line">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
													array(
														"COMPONENT_TEMPLATE" => ".default",
														"PATH" => SITE_DIR."include/top_page/catalog.compare.list.compare_top.php",
														"AREA_FILE_SHOW" => "file",
														"AREA_FILE_SUFFIX" => "",
														"AREA_FILE_RECURSIVE" => "Y",
														"EDIT_TEMPLATE" => "standard.php"
													),
													false
												);?>
											</div>
											<div class="header-cart basket-line" id="basket_line">
												<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
													array(
														"COMPONENT_TEMPLATE" => ".default",
														"PATH" => SITE_DIR."include/top_page/comp_basket_top.php",
														"AREA_FILE_SHOW" => "file",
														"AREA_FILE_SUFFIX" => "",
														"AREA_FILE_RECURSIVE" => "Y",
														"EDIT_TEMPLATE" => "standard.php"
													),
													false
												);?>												
											</div>
										</div>
									<?}else{?>
										<div class="header-cart fly basket-line" id="basket_line">
											<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
												array(
													"COMPONENT_TEMPLATE" => ".default",
													"PATH" => SITE_DIR."include/top_page/comp_basket_top.php",
													"AREA_FILE_SHOW" => "file",
													"AREA_FILE_SUFFIX" => "",
													"AREA_FILE_RECURSIVE" => "Y",
													"EDIT_TEMPLATE" => "standard.php"
												),
												false
											);?>											
										</div>
										<div class="middle_phone">
											<div class="phones">
												<span class="phone_wrap">
													<span class="phone">
														<a class="whatsapp" target="_blank" rel="nofollow" href="https://api.whatsapp.com/send?phone=79039740761"></a>
														<span class="phone_text">
															<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
																array(
																	"COMPONENT_TEMPLATE" => ".default",
																	"PATH" => SITE_DIR."include/phone.php",
																	"AREA_FILE_SHOW" => "file",
																	"AREA_FILE_SUFFIX" => "",
																	"AREA_FILE_RECURSIVE" => "Y",
																	"EDIT_TEMPLATE" => "standard.php"
																),
																false
															);?>
														</span>
													</span>
												</span>
											</div>
										</div>
									<?}?>
									<div class="clearfix"></div>
								</td>
							</tr>
						</table>
					
					</div>
					
					
					
					<div class="catalog_menu menu_<?=strToLower($TEMPLATE_OPTIONS["MENU_COLOR"]["CURRENT_VALUE"]);?>">
						<div class="wrapper_inner">
							<div class="wrapper_middle_menu wrap_menu">
								<ul class="menu adaptive">
									<li class="menu_opener"><?$APPLICATION->ShowViewContent('search_in_menu');?><div class="text">
										<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
											array(
												"COMPONENT_TEMPLATE" => ".default",
												"PATH" => SITE_DIR."include/menu/menu.mobile.title.php",
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "",
												"AREA_FILE_RECURSIVE" => "Y",
												"EDIT_TEMPLATE" => "standard.php"
											),
											false
										);?>
								</div></li>
								</ul>				
								<div class="catalog_menu_ext">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
										array(
											"COMPONENT_TEMPLATE" => ".default",
											"PATH" => SITE_DIR."include/menu/menu.catalog.php",
											"AREA_FILE_SHOW" => "file",
											"AREA_FILE_SUFFIX" => "",
											"AREA_FILE_RECURSIVE" => "Y",
											"EDIT_TEMPLATE" => "standard.php"
										),
										false
									);?>
								</div>
								<div class="inc_menu">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
										array(
											"COMPONENT_TEMPLATE" => ".default",
											"PATH" => SITE_DIR."include/menu/menu.top_content_multilevel.php",
											"AREA_FILE_SHOW" => "file",
											"AREA_FILE_SUFFIX" => "",
											"AREA_FILE_RECURSIVE" => "Y",
											"EDIT_TEMPLATE" => "standard.php"
										),
										false
									);?>
								</div>
							</div>
						</div>
					</div>
					
					
					
				</header>
				<div class="dummy"></div>

				<? if( $TEMPLATE_OPTIONS['HEAD_FIXED_CHECK']['CURRENT_VALUE'] === 'Y' ): ?>
					<div id="headerfixed" class="page-header page-header__fixed">
						<? COptimus::ShowPageType('header_fixed'); ?>
					</div>
				<? endif; ?>
				<? if( $TEMPLATE_OPTIONS['HEAD_MOBILE_CHECK']['CURRENT_VALUE'] === 'Y' ): ?>
					<div id="headerfixed_mobile" class="page-header page-header__fixed--mobile">
						<? COptimus::ShowPageType('header_fixed_mobile') ?>
					</div>
				<? endif; ?>
			</div>
			
			
			<!--END HEADER -->
			<?if(COptimus::IsMainPage()){?>
		<!--main-slider -->

<!--VIDEO -->
			
			<?if(COptimus::IsMainPage()){?>
				<div class="fullscreen-bg">

					<video loop muted autoplay class="fullscreen-bg__video">
						<?/*<source src="video/plane.avi" type="video/avi">*/?>
						<source src="video/new_video.mp4" type="video/mp4">
						<?/*<source src="video/1.webm" type="video/webm">*/?>
					</video>
				</div>
			<?}?><!--END VIDEO -->


<div class="main__page">

<?$APPLICATION->ShowViewContent('section_bnr_content');?>
			<!--title_content-->
			<h1>������������������� ��������� ������ �� �������������� �������������</h1>
			<!--end-title_content-->

<p class="sub__h1">������������������� � ������������ ��������-�������������� ������������ �� �������� �� ������</p>

<div class="grid__half">
 
	<div class="bg__grey">
<h3>����� ����������</h3>
<ul class="marked__ul">
<li>
<span><img src="/images/tank.png" width="30" alt=""></span>������-������������ �������� (���)
</li>
<li>
<span><img src="/images/shuttle.png" width="28" alt=""></span>�������-����������� �������
</li>
<li>
<span><img src="/images/microscope.png" width="25" alt=""></span>�����
</li>
<li>
<span><img src="/images/airplane.png" width="32" alt=""></span>������������
</li>
<li>
<span><img src="/images/assembly.png" width="30" alt=""></span>��������������
</li>
<li>
<span><img src="/images/ship.png" width="28" alt=""></span>������������
</li>
</ul>
</div>
 	<img alt="lizing-logo.jpg" src="/images/site-main.jpg" title="lizing-logo.jpg">
</div>




<div class="grid__prod">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-lg-4">
				<div class="prod__item">
					<a class="bg__img" href="/catalog/frezernye_stanki/">
<div>
<img width="141" height="141" src="/upload/medialibrary/7d4/44p280ojhuzkooq3jqr9r4743pqz5llo/1.jpg" alt="item_background">
</div>
</a>
					<div class="prod__info">
						<a href="/catalog/frezernye_stanki/" class="prod__name">��������� ������</a>
						<div class="prod__description">��������� ������ ��� ��������� ������� � �������, � ����� ��������������� ����������</div>				
					</div>
				</div>
			</div>
<div class="col-xs-12 col-lg-4">
				<div class="prod__item">
					<a class="bg__img" href="/catalog/frezernye_stanki/">
<div>
<img width="141" height="141" src="/upload/medialibrary/d48/jlv4q8bpmq7flsfirg6813rkos04hb3f/2.jpg" alt="item_background">
</div>
</a>
					<div class="prod__info">
						<a href="/catalog/spetsialnoe_oborudovanie/" class="prod__name">����������������</a>
						<div class="prod__description">���������� � ������������ ������������ ������� �� ����������� ��� �������������, ��� ������ � ������� ��������</div>				
					</div>
				</div>
			</div>
<div class="col-xs-12 col-lg-4">
				<div class="prod__item">
					<a class="bg__img" href="/catalog/frezernye_stanki/">
<div>
<img width="141" height="141" src="/upload/medialibrary/498/2as25jky653rkb0t83klc2go10ocxr2c/3.jpg" alt="item_background">
</div>
</a>
					<div class="prod__info">
						<a href="/catalog/stanki_v_nalichii/" class="prod__name">������ � �������</a>
						<div class="prod__description">����������� ������ �� ������� ������������� �� ���������� ������</div>				
					</div>
				</div>
			</div>
		</div>
<hr>
	</div>
</div>

<!-- popular --> 
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"popular",
	Array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "-",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "/personal/basket.php",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPATIBLE_MODE" => "Y",
		"COMPONENT_TEMPLATE" => "popular",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CONVERT_CURRENCY" => "N",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[{\"CLASS_ID\":\"CondIBProp:14:127\",\"DATA\":{\"logic\":\"Equal\",\"value\":30}}]}",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_COMPARE" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "arrFilter",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => "14",
		"IBLOCK_TYPE" => "aspro_optimus_catalog",
		"INCLUDE_SUBSECTIONS" => "Y",
		"LABEL_PROP" => array(),
		"LAZY_LOAD" => "N",
		"LINE_ELEMENT_COUNT" => "3",
		"LOAD_ON_SCROLL" => "N",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "� �������",
		"MESS_BTN_BUY" => "������",
		"MESS_BTN_DETAIL" => "���������",
		"MESS_BTN_LAZY_LOAD" => "�������� ���",
		"MESS_BTN_SUBSCRIBE" => "�����������",
		"MESS_NOT_AVAILABLE" => "��� � �������",
		"MESS_NOT_AVAILABLE_SERVICE" => "����������",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_LIMIT" => "5",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "������",
		"PAGE_ELEMENT_COUNT" => "18",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array(0=>"MINIMUM_PRICE",1=>"",),
		"PROPERTY_CODE_MOBILE" => array(0=>"MINIMUM_PRICE",),
		"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
		"RCM_TYPE" => "personal",
		"SECTION_CODE" => "",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(0=>"",1=>"",),
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "N",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_FROM_SECTION" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "Y",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"TEMPLATE_THEME" => "blue",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N"
	)
);?> 
<!-- popular -->

<div class="main__ask">
<div class="ask__img bg_site"></div>
<div class="ask__text"><div class="ask__text-inner">
<h4>�������� ������ � ��������</h4>
<p>� 2007 ���� ��� �� ������ �� ���� ������������ ������������ ��������� ���������� � ������� ��������������. �� ������ ������ ���������� �������� ����� ����������� ����������� ����� 280 ��������������� �����������, �������� � ����� �������� ��� �� ����������, �� ������������ �������� ������, ���� ������-�����, �������������������� � �.�.
</p>
<div class="ask__action"><a href="#contacts" class="ask__btn">������ ������</a>
</div>
</div>
</div>
</div>


	<div class="container">		
<hr>
	</div>



<div class="talk__block">
	<div class="wraps">
		<div class="wrapper_inner">
			<div class="grid__talk">
<div class="talk__item">
	<div class="talk__img"><img src="/images/chmr.png" width="71" alt=""></div>
	<p>���� ����� ���������������� ������</p>
</div>
<div class="talk__item">
	<div class="talk__img"><img src="/images/eumab.png" width="82" alt=""></div>
	<p>�������� ����������� ��������� �������������� ������������ �Eumabois�</p>
</div>
<div class="talk__item">
	<div class="talk__img"><img src="/images/drev.png" width="47" alt=""></div>
	<p>���� ����� ���������� ���������</p>
</div>
</div>
		</div>
	</div>
</div>
<div class="main__slider">
	<div class="owl-carousel">
		<a href="/arenda-frezernogo-stanka-s-chpu.php" class="main__item new_grid">
			<img src="/images/1.jpg">
			<div class="new_grid_blue_block">
				<div class="new_grid_blue_block_title">������ ���������� ������</div>
				<div class="new_grid_blue_block_text">�� ����������� ������������������� ������</div>
			</div>
		</a>
		<a href="/frezernye-stanki-s-chpu-dlya-zavoda-v-lizing-v-moskve-i-oblasti.php" class="main__item new_grid">
			<img src="/images/2.jpg">
			<div class="new_grid_blue_block">
				<div class="new_grid_blue_block_title">������ � ������</div>
				<div class="new_grid_blue_block_text">�� ����������� ������������������� ������ "������"</div>
			</div>
		</a>
		<a href="/kupit-frezernyy-stanok-s-chpu-v-rassrochku.php" class="main__item new_grid">
			<img src="/images/3.jpg">
			<div class="new_grid_blue_block">
				<div class="new_grid_blue_block_title">������ � ���������</div>
				<div class="new_grid_blue_block_text">�� ����������� ������������������� ������ "������"</div>
			</div>
		</a>
<?/*
		<div class="main__item bg_7">
<div class="mask"></div>
			<div class="wraps">
				<div class="wrapper_inner">
					<p><strong>������ 6040����</strong></p>
					<p>��� ���������, ������� � ������� ������� ��������� (�������) �� ������������, ��������, ��������������.</p>
					<a href="/catalog/290/">���������</a>
				</div>
			</div>
		</div>
		<div class="main__item bg_8">
<div class="mask"></div>
			<div class="wraps">
				<div class="wrapper_inner">
					<p><strong>������ 4030 PS</strong></p>
					<p>������������ ����������� ������� ��� ��������� ������� (������������, ������������, �����)...</p>
					<a href="/catalog/289/">���������</a>
				</div>
			</div>
		</div>
		<div class="main__item bg_9">
<div class="mask"></div>
			<div class="wraps">
				<div class="wrapper_inner">
					<p><strong>������ 3525��</strong></p>
					<p>��������-�������������� ����-/������������� ������ � ��� ��� ��������� ���������� ���������� �� 56 HRC.</p>
					<a href="/catalog/frezernye_stanki/seriya_router_3525/299/">���������</a>
				</div>
			</div>
		</div>
*/?>
		<div class="main__item bg_10">
<div class="mask"></div>
			<div class="wraps">
				<div class="wrapper_inner">
					<p><strong>������ 1070��</strong></p>
					<p>1070 + ����������� ������� ��� �� ��� Z: 360 ��.</p>
					<a href="/catalog/frezernye_stanki/seriya_router_1070/204/">���������</a>
				</div>
			</div>
		</div>
		<div class="main__item bg_11">
<div class="mask"></div>
			<div class="wraps">
				<div class="wrapper_inner">
					<p><strong>������ 1216</strong></p>
					<p>��������� ����/������� ������ ������ � ��� ������������ ��� ������� � ������������ ����������������.</p>
					<a href="/catalog/frezernye_stanki/seriya_router_1216/227/">���������</a>
				</div>
			</div>
		</div>
	</div>
</div>
			
			<!--END main-slider-->	
<!--VIDEO -->
			


<!-- Tizers -->
<div class="wraps tizers__block">

	<div class="wrapper_inner front">
		<div class="grid__tizers">
			<div class="tizers__item">
				<div class="img"><img src="/images/3e7571cc6e2523daa04480cbbe515181.png" alt="���� ����� ������������� ������ ���" title="���� ����� ������������� ������ ���"></div>
				<div class="tizers__title">���� ����� ������������� ������ ���</div>
				<div class="tizers__description">������������ ��������� ������������� ������. �������� � ���������</div>
			</div>
			<div class="tizers__item">
				<div class="img"><img src="/images/93ee1739d02c3091d0a619321def4ce3.png" alt="����� ������ � ������" title="����� ������ � ������"></div>
				<div class="tizers__title">����� ������<br>� ������</div>
				<div class="tizers__description">������ � ������ ������: ��������� 10% � �����</div>
			</div>
			<div class="tizers__item">
				<div class="img"><img src="/images/1c6568ab650f8460d8af8b3dad9812bb.png" alt="�������� ������� �� ���������" title="�������� ������� �� ���������"></div>
				<div class="tizers__title">�������� ������� �� ���������</div>
				<div class="tizers__description">���������: ������ ����� 60%, ������� �� 4 ������, ��������� 0%</div>
			</div>
			<div class="tizers__item">
				<div class="img"><img src="/images/4f44e76be11c988d6ca05e88c6ef0da7.png" alt="�������� �� ��� ���������" title="�������� �� ��� ���������"></div>
				<div class="tizers__title">�������� �� ��� ���������</div>
				<div class="tizers__description">�������� 12 ������� �� �� ��������� ������������� � ����� ��������</div>
			</div>
		</div>
	</div>

</div>
<!-- Tizers -->

<!-- Projects -->
<div class="wraps projects__block">
	<div class="wrapper_inner front">

		<div class="projects__header">
			<h2>����������� �������</h2>
			<a href="/projects/">��� ����������� �������</a>
		</div>
			<hr>
		<div class="projects__carousel">
			<div class="owl-carousel">
				
				<div class="projects__item">
					<div class="projects__half">
						<div class="projects__img bg_12">
							<span class="projects__label"><img src="/images/label-kamaz.png" alt=""></span>
						</div>
						<div class="projects__description">
							<div class="projects__name">3D-������� ��� ������ ������������ ����������� ���������</div>
							<div class="projects__date">1 ����� 2019</div>
							<div class="projects__text">������ ��������� ������ ���������� ��� ��������������: ��-������, ������������ ������� �������� ���� (������� ����������) � 3100 � 3100 ��, � ��-������, ���������� � ������ 3D-������� ������������ ��� ������������ (������) ����������� �������� ��� ����������� ���������.</div>
							<a href="/projects/3d_printer_dlya_pechati_spetsosnastki_kosmicheskikh_apparatov/">���������</a>
						</div>
					</div>
				</div>
				
				<div class="projects__item">
					<div class="projects__half">
						<div class="projects__img bg_13">
							<span class="projects__label"><img src="/images/label-kamaz.png" alt=""></span>
						</div>
						<div class="projects__description">
							<div class="projects__name">������������ ��� �������� ��������� (��� ������� �������� RuhrPumpen)</div>
							<div class="projects__date">1 ����� 2019</div>
							<div class="projects__text">��� "������" ��������� �� ������� ������� ��������� ������: ���������� �������� ��� ���������� ����� � ������������ ������� �� ��������� �������, �������������� � ����������� ��������� �������� ������������, ������������ ��������� ������������: �����������, ��������� ������� ������������ ��� ����� � ����������������, �������� ������� ������������, �������� ������������ � ������������ � ������������ ���������.</div>
							<a href="/projects/izgotovlenie_ram_nasosnykh_agregatov_dlya_zakazov_kompanii_ruhrpumpen/">���������</a>
						</div>
					</div>
				</div>

				<div class="projects__item">
					<div class="projects__half">
						<div class="projects__img bg_14">
							<span class="projects__label"><img src="/images/label-kamaz.png" alt=""></span>
						</div>
						<div class="projects__description">
							<div class="projects__name">��������� ��� ������������ ������������� ��� ����������� �����ǻ</div>
							<div class="projects__date">1 ����� 2019</div>
							<div class="projects__text">������������ ������� ������������ �������� ������ ������������� �������� ������������ �������. ��� ����� ��������� �� �����, ������ ������� �������� ��������� ���������������� �������� ��� ����������, ����������� ��������� ������������, � ��������� ���������������.</div>
							<a href="/projects/ustanovka_dlya_izgotovleniya_komplektuyushchikh_dlya_avtomobiley_kamaz/">���������</a>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<!-- Projects -->

<!-- brands -->
<div class="wraps brands__block">
	<div class="wrapper_inner front">
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	".default", 
	array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/mainpage/comp_brands.php",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
</div>
</div>
<!-- brands -->
<!-- news -->
<div class="wraps news__block">
	<div class="wrapper_inner front">
		<div class="projects__header">
			<h2>������� ��������</h2>
			<a href="/news/">��� �������</a>
		</div>
		<hr>
		<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"home",
	Array(
		"ACTIVE_DATE_FORMAT" => "j F Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array("",""),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "12",
		"IBLOCK_TYPE" => "aspro_optimus_content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "3",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "�������",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array("HOME_IMG",""),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ID",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	)
);?>
	</div>
</div>
<!-- news -->






</div>
<?}?>			




			<div class="wraps" id="content">
				<div class="wrapper_inner <?=(COptimus::IsMainPage() ? "front" : "");?> <?=((COptimus::IsOrderPage() || COptimus::IsBasketPage()) ? "wide_page" : "");?>">
					<?if(!COptimus::IsOrderPage() && !COptimus::IsBasketPage() && !COptimus::IsMainPage()){?>
						<?$APPLICATION->ShowViewContent('detail_filter');?>
						<div class="left_block">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/left_block/menu.left_menu.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);?>					

							<?$APPLICATION->ShowViewContent('left_menu');?>

							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/left_block/comp_banners_left.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/left_block/comp_subscribe.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/left_block/comp_news.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);?>
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/left_block/comp_news_articles.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);?>
						</div>
						<div class="right_block">
					<?}?>
						<div class="middle">
							<?if(!COptimus::IsMainPage()):?>
								<div class="container">
									<div class="breadcrumbs" id="navigation" itemscope="" itemtype="http://schema.org/BreadcrumbList">
										<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "optimus", array(
											"START_FROM" => "0",
											"PATH" => "",
											"SITE_ID" => "-",
											"SHOW_SUBSECTIONS" => "N"
											),
											false
										);?>
									</div>
									<?$APPLICATION->ShowViewContent('section_bnr_content');?>
			<!--title_content-->
			<h1 id="pagetitle"><?=$APPLICATION->ShowTitle(false);?></h1>
			<!--end-title_content-->								
							<?endif;?>
<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") $APPLICATION->RestartBuffer();?>
