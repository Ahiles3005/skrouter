<?if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") die();?><?IncludeTemplateLangFile(__FILE__);?>
							<?if(!COptimus::IsMainPage()):?>
								</div> <?// .container?>
							<?endif;?>
						</div>
					<?if(!COptimus::IsOrderPage() && !COptimus::IsBasketPage()):?>
							<div class="clearfix"></div>
								</div> <?// .right_block?>
					<?endif;?>
				</div> <?// .wrapper_inner?>	
			</div> <?// #content?>
		</div><?// .wrapper?>


<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-feedback-block", "");?>
<section id="contacts">
<div class="wrapper_inner">
<?

global $hiden_footer_form ;

if(!$hiden_footer_form){
?>
<div class="form__contact">
		<div class="text-center">
			<h2><?=GetMessage('QUESTION')?></h2>
			<p><?=GetMessage('QUESTION2')?></p>
		</div>

<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-feedback-block");?> <?$APPLICATION->IncludeComponent(
	"bitrix:form.result.new", 
	"inline", 
	array(
		"CACHE_TIME" => "3600000",
		"CACHE_TYPE" => "A",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"EDIT_URL" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"LIST_URL" => "",
		"SEF_MODE" => "N",
		"SUCCESS_URL" => "?send=ok",
		"USE_EXTENDED_ERRORS" => "Y",
		"WEB_FORM_ID" => "3",
		"COMPONENT_TEMPLATE" => "inline",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"VARIABLE_ALIASES" => array(
			"WEB_FORM_ID" => "WEB_FORM_ID",
			"RESULT_ID" => "RESULT_ID",
		)
	),
	false
);?> <?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-feedback-block", "");?>
<?/*


$APPLICATION->IncludeComponent(
	"altasib:feedback.form", 
	"theme2021", 
	array(
		"ACTIVE_ELEMENT" => "Y",
		"ADD_HREF_LINK" => "N",
		"ALX_LINK_POPUP" => "N",
		"BBC_MAIL" => "skrouter@mail.ru",
		"CATEGORY_SELECT_NAME" => "",
		"CHECKBOX_TYPE" => "CHECKBOX",
		"CHECK_ERROR" => "Y",
		"COLOR_SCHEME" => "BRIGHT",
		"COLOR_THEME" => "",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"EVENT_TYPE" => "ALX_FEEDBACK_FORM",
		"FB_TEXT_NAME" => "����� ���������",
		"FB_TEXT_SOURCE" => "PREVIEW_TEXT",
		"FORM_ID" => "1",
		"IBLOCK_ID" => "16",
		"IBLOCK_TYPE" => "altasib_feedback",
		"INPUT_APPEARENCE" => array(
			0 => "DEFAULT",
		),
		"JQUERY_EN" => "N",
		"LINK_SEND_MORE_TEXT" => "��������� ������ ���������?",
		"LOCAL_REDIRECT_ENABLE" => "N",
		"MASKED_INPUT_PHONE" => array(
			0 => "PHONE",
		),
		"MESSAGE_OK" => "��������� ������� ���������",
		"NAME_ELEMENT" => "ALX_DATE",
		"PROPERTY_FIELDS" => array(
			0 => "NAME",
			1 => "EMAIL",
			2 => "PHONE",
			3 => "FEEDBACK_TEXT",
		),
		"PROPERTY_FIELDS_REQUIRED" => array(
			0 => "NAME",
			1 => "EMAIL",
			2 => "PHONE",
			3 => "FEEDBACK_TEXT",
		),
		"PROPS_AUTOCOMPLETE_EMAIL" => array(
			0 => "EMAIL",
		),
		"PROPS_AUTOCOMPLETE_NAME" => array(
			0 => "NAME",
		),
		"PROPS_AUTOCOMPLETE_PERSONAL_PHONE" => array(
			0 => "PHONE",
		),
		"PROPS_AUTOCOMPLETE_VETO" => "N",
		"SECTION_FIELDS_ENABLE" => "N",
		"SECTION_MAIL_ALL" => "info@skrouter.ru",
		"SEND_IMMEDIATE" => "N",
		"SEND_MAIL" => "N",
		"SHOW_LINK_TO_SEND_MORE" => "N",
		"SHOW_MESSAGE_LINK" => "N",
		"USERMAIL_FROM" => "N",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "1",
		"USER_CONSENT_INPUT_LABEL" => "�������� �� ��������� ������������ ������",
		"USER_CONSENT_IS_CHECKED" => "N",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_CAPTCHA" => "Y",
		"WIDTH_FORM" => "100",
		"COMPONENT_TEMPLATE" => "theme2021",
		"COLOR_OTHER" => "#0076A4",
		"LOCAL_REDIRECT_URL" => "yandex.ru",
		"CAPTCHA_TYPE" => "recaptcha",
		"NOT_CAPTCHA_AUTH" => "N",
		"CHANGE_CAPTCHA" => "Y",
		"USER_EVENT" => "ALX_FEEDBACK_FORM",
		"ADD_EVENT_FILES" => "N",
		"ALX_NAME_LINK" => "�������� ���",
		"ALX_LOAD_PAGE" => "Y",
		"POPUP_ANIMATION" => "4",
		"POPUP_DELAY" => "0",
		"RECAPTCHA_THEME" => "light",
		"RECAPTCHA_TYPE" => "image",
		"REQUIRED_SECTION" => "N",
		"ADD_LEAD" => "N",
		"SPEC_CHAR" => "Y",
		"SPEC_CHAR_LIST" => "@,/,<,>",
		"RECAPTCHA_VALID_SCORE" => "0.5"
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);*/
}
?>



</div>
<div class="row icons footer__icons">
		<div class="col-md-4 col-sm-4 text-center slideInLeft wow animated" data-wow-offset="100" data-wow-duration="2s">
			<span class="icons fa fa-phone"></span>
			<p><a href="tel:+74959740761">+7 (495) 974-07-61</a></p>
		</div>
		<div class="col-md-4 col-sm-4 text-center slideInUp wow animated" data-wow-offset="100" data-wow-duration="2s">
			<span class="icons fa fa-mail-reply-all"></span>
			<p><a href="mailto:info@skrouter.ru">info@skrouter.ru</a></p>
		</div>
		<div class="col-md-4 col-sm-4 text-center slideInRight wow animated" data-wow-offset="100" data-wow-duration="2s">
			<span class="icons fa fa-home"></span>
			<p>�.����������, 2-� �������� ��-�,<br>
 �.1, ���.1</p>
		</div>
</div>
</div>
 </section>
		<div class="clearfix"></div>
		
		
		<footer id="footer">
			<div class="footer_inner <?=strtolower($TEMPLATE_OPTIONS["BGCOLOR_THEME_FOOTER_SIDE"]["CURRENT_VALUE"]);?>">

				<?if($APPLICATION->GetProperty("viewed_show")=="Y" || defined("ERROR_404")):?>
					<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
						array(
							"COMPONENT_TEMPLATE" => ".default",
							"PATH" => SITE_DIR."include/footer/comp_viewed.php",
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "",
							"AREA_FILE_RECURSIVE" => "Y",
							"EDIT_TEMPLATE" => "standard.php"
						),
						false
					);?>					
				<?endif;?>
				<div class="wrapper_inner">
					<div class="footer_bottom_inner">
						<div class="left_block">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/footer/copyright.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "standard.php"
								),
								false
							);?>							
							<div id="bx-composite-banner"></div>
						</div>
						<div class="right_block">
							<div class="middle">
								<div class="rows_block">
									<div class="item_block col-75 menus">
										<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu_top", array(
											"ROOT_MENU_TYPE" => "bottom",
											"MENU_CACHE_TYPE" => "Y",
											"MENU_CACHE_TIME" => "3600000",
											"MENU_CACHE_USE_GROUPS" => "N",
											"MENU_CACHE_GET_VARS" => array(),
											"MAX_LEVEL" => "1",
											"USE_EXT" => "N",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N"
											),false
										);?>
										<div class="rows_block">
											<div class="item_block col-3">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_company",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "3600000",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
											<div class="item_block col-3">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_info",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "3600000",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
											<div class="item_block col-3">
												<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_submenu", array(
													"ROOT_MENU_TYPE" => "bottom_help",
													"MENU_CACHE_TYPE" => "Y",
													"MENU_CACHE_TIME" => "3600000",
													"MENU_CACHE_USE_GROUPS" => "N",
													"MENU_CACHE_GET_VARS" => array(),
													"MAX_LEVEL" => "1",
													"USE_EXT" => "N",
													"DELAY" => "N",
													"ALLOW_MULTI_SELECT" => "N"
													),false
												);?>
											</div>
										</div>
									</div>
									<div class="item_block col-4 soc">
										<div class="soc_wrapper">
											<div class="phones">
												<div class="phone_block">
													<span class="phone_wrap">
														<span class="icons fa fa-phone"></span>
														<span>
															<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
																array(
																	"COMPONENT_TEMPLATE" => ".default",
																	"PATH" => SITE_DIR."include/phone_bottom.php",
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
											<div class="social_wrapper">
												<div class="social">
													<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
														array(
															"COMPONENT_TEMPLATE" => ".default",
															"PATH" => SITE_DIR."include/footer/social.info.optimus.default.php",
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
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="mobile_copy">
						<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
							array(
								"COMPONENT_TEMPLATE" => ".default",
								"PATH" => SITE_DIR."include/footer/copyright.php",
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "",
								"AREA_FILE_RECURSIVE" => "Y",
								"EDIT_TEMPLATE" => "standard.php"
							),
							false
						);?>
					</div>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include1.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_1"))); ?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/bottom_include2.php", Array(), Array("MODE" => "text", "NAME" => GetMessage("ARBITRARY_2"))); ?>
				</div>
				<div class="hardkod"><a href="https://hardkod.ru/">Hardkod.ru</a>&nbsp;� ����������� ��������� ������</div>
			</div>
		</footer>
		<div class="overlay js-overlay-modal"></div>
		<?
		COptimus::setFooterTitle();
		COptimus::showFooterBasket();
		COptimus::bottomActions();
		?>
		
		<!-- Yandex.Metrika counter -->
		<script>
					/*(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
					m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
					(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
		
					ym(55160641, "init", {
										clickmap:true,
										trackLinks:true,
										accurateTrackBounce:true,
										webvisor:true
					});*/
		</script>
		<!-- /Yandex.Metrika counter -->
<script src="https://api-maps.yandex.ru/2.0/?apikey=dd1e6fe6-99d3-4fa1-86c8-b97251e68b4b&load=package.full&lang=ru-RU"></script>

<script>
if(document.getElementById("map")){
ymaps.ready(function () {
	var myMap = new ymaps.Map('map', {
		center: [55.755814, 37.617635],
		zoom: 5
	}, {
		searchControlProvider: 'yandex#search'
	});
	
	myMap.controls
        .add('zoomControl', { left: 5, top: 5 });
         
	myPlacemark1 = new ymaps.Placemark([55.755814, 37.617635], {
		hintContent: '�. ������',
		balloonContent: '<ul><li>� ��� ������� �. ������. 4230</li><li>� ���� �������ͻ �. ������. ��������� ��� ��������� ����������</li><li>� ���� ������� �. ������ 3020��</li><li>� ��� �� ���� ����Ȼ �. ������. 4030</li><li>� ���� ��. ������� ���� �������ͻ �.������. 9565 ���</li><li>� ���� ��. ������� ����� ���� �.������. 9565 (2 ��.)</li><li>� ���� ��. ������� �C� - 12� ������� � ����������� �������������� �. ������. 3020��</li><li>� ��� ����� � ���������� ��������������� ����������� �. ������ 3525</li><li>� ��� �� ������������ ���������� �������� ������������ �. ������ (�� ������). 6040�</li><li>� ���� ��. �.�. ������������ ��� �������� �. �������. 6040��� ������̻ ��� ��� �.������ (�� ����������) 3020��</li><li>� �� ������̻ ��� ��� �.������ (�� ����������) 3020��</li><li>� ��� � �������� ��. ��������� � �� � ������������ � �. ������. 1216</li><li>� ������������ ����������������� ������� ����������� ����Ȼ �. ������. 4030</li><li>� ���� ����� �� ��. �.�. �������� �. ������ 6040 ���� ��� ����� ������ (5+1)</li><li>� ���� ��� 52 ������ ����� �.�. ������ ( �� �� ��������) �. ������ 4030 �� �����</li><li>� ��� ����� ������������ ����������� � ���������������� �������� �.������. 4030��</li><li>� ��� � ������ ��������������� ���� ��Ȼ (��� ������ � �ѻ ���) �. ������. 3020 ��</li><li>� ��� �2 ��ǻ ������ ������������������� ����� ( �� � ��) �. ������. 4030</li><li>� ��� ��� �����. �. ������. 4030</li><li>� ��� �������� ������������ (����� ������-�����) �. ������. 4030 �� �����</li><li>� ���� ����Ȼ �.������. 4030</li><li>� ��� ��� ����+� ��������� � ��������, �. ������. 4030��</li><li>� ���� � ������������������ɻ, �. ������ (�������������). 9565���, 6040�</li><li>� ���� ��� 52 ������ ����� �.�. ������ ( �� �� ��������) �. ������ 4030 �� �����</li><li>� ���� � ����� ����� �.�. �������� � ( ��� ��� ��� �� ) �. ������ 6040���</li><li>� ���� � ����� ����� �.�. ������ � ( �� �� ������� ������� �������� ) �. ������ 6040�</li><li>� ���� ������� ����� �.�. �������� (��� � ��� ������) �. ������. 3020��</li><li>� ��� ������������ ����������� � ���������������� �������� (��� ��) �. ������.</li><li>� ��� ���� � ����� � (���, ��� � ��� ������) �. ������ 1070��</li><li>� ��� ���� ��������� ����� �.�. ���������� ( �� ��������) �. ������. 3020 ��</li><li>� ��� ��� ����������� �������� �� �.�.�������� (���) �. ������.</li><li>� ��� � ����������� �������� ������ ������ ����������� � �. ������. ( �������). 6040 ����� ( 26 ���.)</li><li>� ��� ��� ����������� ������� �. ������. ( ��������� ). 4230</li><li>� �� ���� ��Ȼ (���������� ����������� �������), �.������. 7846, 6040�</li><li>� �� ������������˻ �. ������ 1070��</li><li>� ��� ���� ���̻ (�� �� �������̻), �. ������, 4030</li><li>� �� ���� ����ǻ (��� ���� � �������), �. ������ 1070, 1070��, 1070�� �����</li><li>� ���� ������� �Ի �������� ���� ������.</li><li>� �������� ����� � 145 �. ������. 4030 � 3 ��.</li><li>� ��� �121 ��ǻ ����� ��. ���������� �������, ����������� �����. ( �������). 3020 ��</li><li>� ��� ����� �. ���������, ���������� ������� (���������).</li><li>� �� ��� �������, ���������� �������, �.�. ������, ��� �� ��, ��� ��, 6040 ����</li><li>� ���� ������ѻ ���������� �������� ����� � �������. 4030 ��</li><li>� ���� � �����������, ���������� �������, �. ��������. 4030 �� �����</li><li>� ��� ���������� ���������. 7846</li><li>� ���� ���� ����� (���������). 4030</li><li>� ��� ���� ����λ ( ������� ������������ � ����������������� ��������������). 4030 �� �����</li><li>� ���� �������ͻ. 4030</li><li>� ���� � ��� ��������ػ. ( ��� ) 4030�� �����</li><li>� �������� ����������� ���������� ����. 3020��</li><li>� ���� � ����� ��������ػ. ( ��� ) 6040 �����</li><li>� ��� ����� �������� ����������� ����� (��� �����������, ��ȫ��������� ������)</li><li>� ���� ��������� �������������������. 4230</li><li>� ���� ����� ��������� ���������� ���������� ��. �.�. �������. 7846</li><li>� ���. ����� (�������). 3020</li><li>� ��� ���� ������������ �������� ������������ �������������� ����� �.�. �������� 3020</li><li>� ���� ���� ���������� � ��������������� ����� ��������� �.�.�������� 6040 �</li><li>� ��� ������ ���� ��� ������. 4030 �� �����</li><li>� ���� ����� �.�. ����������. 7846</li><li>� ��� � ���������� � ��������������� �����������Ż ��.</li><li>� ��� ���� ��������� ����� �.�. ���������� (��� �������� ���������) 3020 ��</li><li>� ���� �� ��� (�������). 3020 ��</li><li>� ����� ��� ���� �������ͻ. 4030</li><li>� �� ������� ��. ��������� �.�. ������� ( �� ��������) 3020��</li><li>� ���� ��� ��. �.�. ��������� ���. 3020��</li><li>� �� �������� ���������������� ���������� (�� ��������) 6040��</li><li>� ��� ������������� (��� �������� ��� ������-�����). 4230</li><li>� �� ���� ��������������� ������������������ ��������������� ���� ��. �.�. �������� 3020 �� + 6040��</li><li>� ���� ���� ��� ����� ��. �.�. ��������.</li><li>� ��� ������������� (�������������). 6040 ����� ( 26 ���.)</li><li>� ��� ���� �������� (�������������� ��� ��� � ���������������� ���������) 3020 ��</li><li>� ��� � ������������ ������������������ ����� � �������� ��. 1015 �����</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/72.png',
		iconImageSize: [23, 23],
		iconImageOffset: [-15, 0]
	});
	myPlacemark2 = new ymaps.Placemark([59.939095, 30.315868], {
		hintContent: '�. �����-���������',
		balloonContent: '<ul><li>� ����� � �������� ������������� � ����������������� ��� � �. �����-���������.</li><li>� ���� �������������� ������-�������������� ����� �239� �. �����-���������.</li><li>� ��� ���������л �. �����-��������� (�������). 6040�</li><li>� ��� �������� ������������� ������ۻ �.�����-���������(��� ������).</li><li>� ��� ���(��),�����-������������� ��������������� ��������������� �������� (����������� �����������), �. �����-���������, 6040 ����� ������</li><li>� ����� ����������� � 6003 ��� ��� �. �����-���������.</li><li>� ���� ������� �. ����� � ���������.</li><li>� ��� ����Ȼ ������ �.����� � ���������. Z � �������</li><li>� ��� ���� ��������� ( �� ��, ���������� �����������) �. ����� � ���������.</li><li>� ��� ��� ��������� ( ��� ������ͻ ) �. ����� � ���������. 3020 �� � 2 ��.</li><li>� ���� ���� ����� ( ��� � ���, ���, ������, ������.) �.����� � ��������� 6040�</li><li>� ����� �� ����� ����� � ���������. 3131</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/12-7.png',
		iconImageSize: [14, 14],
		iconImageOffset: [-15, 0]
	});
	myPlacemark3 = new ymaps.Placemark([55.991893, 37.214382], {
		hintContent: '�. ����������',
		balloonContent: '<ul><li>� ��� � ��� ����� �. ����������.3020</li><li>� ��� ���� ���������� ��, �. ����������. 3020��</li><li>� ���� (����) �. ����������. 4030</li><li>� ��� ���� ����� �. ���������� 3020</li><li>� ��� � ��� �������� �. ����������. 3020</li><li>� ��� � ����� � ������ � ( ���������, ���, ��� ) �. ����������. 3020</li><li>� ��� �����л �. ���������� (���, ���, ����) 3525</li><li>� ��� � ����� � �. ����������. 3020��</li><li>� ��� ��� ������л, ���������� �������� ������� ���� �������� �. ����������. 4030</li><li>� ��� � ��� ���� �. ����������.3020</li><li>� �� "��� �����", �. ����������, ��������������, ���������� � ������������ ������������ ������������ ���������, 4030 �� �����</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/12-7.png',
		iconImageSize: [14, 14],
		iconImageOffset: [-15, 0]
	});
	myPlacemark4 = new ymaps.Placemark([56.326797, 44.006516], {
		hintContent: '�. ������ ��������',
		balloonContent: '<ul><li>� ��� �� ��� ��. �.�. ���������, ��� ������ �. ������ �������� (������������) 1216 �����</li><li>� ��� ������ ��. �.�. ����������� �. ������ �������� (�������).4030<li>� ���� "���� ����� ��. �.�. ��������" �. ������ �������� (�������) 4030�� �����</li><li>� ��� � ��� ������ ����λ ���� �� �. ������ ��������. 4030</li><li>� ��� ��� � ����� � �. ������ �������� (�������).<li>� �� ���� �����ͻ (����� �������), �. ������ ��������, ����������������, 3020����</li><li>� ��� �������������� ���������������� ����� ������ (��� ��� ���� �20 � 50�). 4030 �� �����</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/12-7.png',
		iconImageSize: [14, 14],
		iconImageOffset: [-15, 0]
	});
	myPlacemark5 = new ymaps.Placemark([52.858248, 27.701393], {
		hintContent: '��������',
		balloonContent: '<ul><li>� ��� ���������� ����� ������������ ������� ���������� ����������. 3020</li><li>� ��� ������ ���������� ����������.</li><li>� ��� ���������� ������������������� ����� ���������� ����������. 3020��</li><li>� ���� ���������� ��������, ���������� ����������. 9565 ���</li><li>� �� �������� (����������� �������� ������) ������, ���������� ����������. 6040<li>� ��� ����� �������� ������, ������������ ����������� ���������� ����������. 4030��</li><li>� ��� ������������ ���������� ����� ���������� ����������. 3020 ��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/12-7.png',
		iconImageSize: [14, 14],
		iconImageOffset: [-15, 0]
	});
	myPlacemark6 = new ymaps.Placemark([55.597475, 38.119802], {
		hintContent: '�. ���������',
		balloonContent: '<ul><li>� ��� ���� ������������ ������������� �. ��������� ( �� ��������) 4030��</li><li>� ���� ��� "��� ��. �.� ��������" �. ��������� ( �������). 9565 ���<li>� ��� ��� ���� ����Ȼ �. ��������� (�������). 4030 �� �����</li><li>� �� ����� �� �.�. ���������� �.��������� ( �� ��������) 6040�</li><li>� ����� ��� ��������������� ��. ���������� �. ���������. 4030 ��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark7 = new ymaps.Placemark([52.721219, 41.452274], {
		hintContent: '�. ������',
		balloonContent: '<ul><li>� ��� ����� ����������� ������-����������������� �������� ������������ ����л 4230<li>� ��� � ���������� ����� � ������������л 1216</li><li>� ��� ���������� ������������������� ����� ����ѻ 1216</li><li>� ��� ����� � ������������л, �. ������ ( ������������� ). 1015</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark8 = new ymaps.Placemark([51.660781, 39.200269], {
		hintContent: '�. �������',
		balloonContent: '<ul><li>� ��� ������������ �. ������� (���������). 3020 ��</li><li>� ��� ����������� ������ ������� ��. �������������, ����������� �������. 9565 ���</li><li>� ��� ��� ����� ������� ���������� �. ������� (���������) 3020 ��</li><li>� ��� �������� ���������� �. ������� ( �� �� ��� � ��� ). 4030 ��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark9 = new ymaps.Placemark([53.902496, 27.561481], {
		hintContent: '�. �����',
		balloonContent: '<ul><li>� ��� ������������������� �����, ���������� ����������. 7846 ���</li><li>� ��� �������� �����, (����������� ������������), ���������� ����������. 4030<li>� ���� ������������ ������������ ����������� ����������� �. �����. 4030 ��</li><li>� ���� ����������� ������������ ����������� �� �.�����. 4030 ��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark10 = new ymaps.Placemark([53.901460, 27.612631], {
		hintContent: '�. ����',
		balloonContent: '<ul><li>� ��� ��� ��������������߻ (��������� ������ � ����������� �����). 1216 �����</li><li>� ��� ����������������� ( ���� ��������������� ) �. ������. 6040�</li><li>� ��� � ����������һ �. ������� ������ (�����������, ��� ��) 1216 �����</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark11 = new ymaps.Placemark([54.513845, 36.261215], {
		hintContent: '�. ������',
		balloonContent: '<ul><li>� ���� ��� ���������� ������������������� �����, �. ������, 6040��</li><li>� ���� � ���� - ��� �, ���������� �������, �. ������. 4030�� �����</li><li>� ��� ���� �����������, �. ������ (�������������). 9565���</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark12 = new ymaps.Placemark([55.933564, 37.514104], {
		hintContent: '�. ������������',
		balloonContent: '<ul><li>� ��� ����ϻ �. ������������ (��� �������� ��� ������-�����). 4030 �� ����� � 2 ��.</li><li>� ���� ������� � ����� ��������������� ���������� �. ������������. 3525 ��</li><li>� ��� ����ϻ, �.������������ ( ��� ������-�����) 4030 �� �����</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark13 = new ymaps.Placemark([56.010563, 92.852572], {
		hintContent: '�. ����������',
		balloonContent: '<ul><li>� ����� ����� ���������� ��������������� ��������������� ����� �. ����������. 1070</li><li>� ����� �� ������� ��. ��������� �.�. ���������, �. ����������, 3390</li><li>� ����� ������ ���������� ��������������� ��������������� ����������� �. ����������. 1225 ����� 4-� ������</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark14 = new ymaps.Placemark([53.195538, 50.101783], {
		hintContent: '�. ������',
		balloonContent: '<ul><li>� ���� ��������ѻ (�������-����������� �����) �. ������ ��������� 3/1</li><li>� �� ���� ��������� �. ������. ������������ � �������� ���������������� ��� �� ���������ѻ.</li><li>� �� � ��� ������ (�� ��������) �. ������. 6040 ��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark15 = new ymaps.Placemark([55.030199, 82.920430], {
		hintContent: '�. �����������',
		balloonContent: '<ul><li>� ���� ����ӻ �.����������� 4230���</li><li>� ��� ���� ����� �. ����������� (�� ��) 3020����</li><li>� ��� ��������� ���������� ������ �. ����������� ( �� ��)</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark16 = new ymaps.Placemark([54.314192, 48.403123], {
		hintContent: '�. ���������',
		balloonContent: '<ul><li>� ��� � ������������ �.��������� ( �������). 6040 ����� ( 26 ���.)</li><li>� ���� � ����������� ��������������� ����������� �. ���������. 4030 ��</li><li>� ��� ��������� � �ϻ �. ��������� (��� ��). 1212 ����� ����-������</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark17 = new ymaps.Placemark([54.193122, 37.617348], {
		hintContent: '�. ����',
		balloonContent: '<ul><li>� ���� � ��������ܻ (�������� �������-������������ �����) �. ���� 1070��</li><li>� �� �����ʻ (����� ������ ������������������) �.������, �������� �������, 1225 �����</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark18 = new ymaps.Placemark([53.195063, 45.018316], {
		hintContent: '�. �����',
		balloonContent: '<ul><li>� ���� � ����Ȼ ����������� ������ - ����������������� ������������������ �������� 4030 ��</li><li>� ��� ����� ����������� ������ - ����������������� ������������������ �������� 4030 ��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark19 = new ymaps.Placemark([51.730361, 36.192647], {
		hintContent: '�. �����',
		balloonContent: '<ul><li>� ����� ��� ������������ ��������������� ����������� �. �����. 4030 ��</li><li>� ���� �18 ���Ȼ �. ����� ( �� ��) 6040�� ���</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark20 = new ymaps.Placemark([58.603591, 49.668014], {
		hintContent: '�. �����',
		balloonContent: '<ul><li>� ��� ���� �������-��������� ������������������ ����� � ����һ �. �����.</li><li>� ����� �� ����� �������� ��������������� ����������� �. ����� 6040�</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark21 = new ymaps.Placemark([48.707073, 44.516930], {
		hintContent: '�. ���������',
		balloonContent: '<ul><li>� ��� �� � �� ������� �. ���������. ( ��� ������). 3020 ��</li><li>� ��� � ����������л �. ���������. ( ��� �� ).</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark22 = new ymaps.Placemark([56.838011, 60.597465], {
		hintContent: '�. ������������',
		balloonContent: '<ul><li>� ��� "��� "�����" ��. �.�. ������" �. ������������ (�� ��) 4030��</li><li>� �� �����������, �. ������������, ������ 1225</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark23 = new ymaps.Placemark([54.629216, 39.736375], {
		hintContent: '�. ������',
		balloonContent: '<ul><li>� ��� ����� �������� ������ (���� ���������) �. ������ 9565���</li><li>� ��� ���������������ѻ �. ������. (������������ � ������������ ���, ��� ). 1220 �����</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark24 = new ymaps.Placemark([59.122612, 37.903461], {
		hintContent: '�. ���������',
		balloonContent: '<ul><li>� ����� �� ������������� ��������������� ����������� �. ���������, 6040��</li><li>� ��� ������������ ��������������� �����������, �. ��������, 6040����</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark25 = new ymaps.Placemark([54.187433, 45.183938], {
		hintContent: '�. �������',
		balloonContent: '<ul><li>� ���� ������������������� ��ϻ �. �������.</li><li>� ���� � ������ �. �������. 3020</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark26 = new ymaps.Placemark([55.914484, 36.827594], {
		hintContent: '�. �����',
		balloonContent: '<ul><li>� ��� �����̻ (�������-����������� �������������� ���) �. �����. 3020</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark27 = new ymaps.Placemark([56.259643, 38.215006], {
		hintContent: '�. ������� �����',
		balloonContent: '<ul><li>� ��� ��� � ���� �. ������� ����� (������������������ ������� ����������). 3020 ��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark28 = new ymaps.Placemark([52.290773, 104.307370], {
		hintContent: '�. �������',
		balloonContent: '<ul><li>� ��� ��� ����̻ ��������� ����� ������� ��������������. �. �������.</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark29 = new ymaps.Placemark([55.105778, 60.123204], {
		hintContent: '�. �����',
		balloonContent: '<ul><li>� ��� ��� ���ѻ ( ����������� ������������ ������� ) �. �����. 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark30 = new ymaps.Placemark([54.981910, 57.687171], {
		hintContent: '�. ���',
		balloonContent: '<ul><li>� ��� �������� �. ��� ( ������� �������, ����/��������������� �������� ). 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark31 = new ymaps.Placemark([52.324420, 35.377592], {
		hintContent: '�. ������������',
		balloonContent: '<ul><li>� ��� ���������������� �. ������������ 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark32 = new ymaps.Placemark([47.244009, 39.709297], {
		hintContent: '�. ������-��-����',
		balloonContent: '<ul><li>� ���� "����������� ������-���������������� ����� ������", ������-��-����. 6040�</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark33 = new ymaps.Placemark([43.204479, 46.118716], {
		hintContent: '�. �������',
		balloonContent: '<ul><li>� ���� "��� ����ǻ ������������ ������������������� ����� �. �������. 3020 ��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark34 = new ymaps.Placemark([55.831903, 37.331639], {
		hintContent: '�. �����������',
		balloonContent: '<ul><li>� ���� ��� �������������� ������������ ����� ��. �.�. �������</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark35 = new ymaps.Placemark([59.469516, 40.103518], {
		hintContent: '�. �����',
		balloonContent: '<ul><li>� ��� ��� �� ����������� ��һ ����������� �������, �. �����. 4030 �� �����</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark36 = new ymaps.Placemark([55.663036, 38.031345], {
		hintContent: '�. �������',
		balloonContent: '<ul><li>� �� ���˻ ( ������������� ����������� ������������) �. �������. 4030�� �����</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark37 = new ymaps.Placemark([56.331693, 36.728716], {
		hintContent: '�. ����',
		balloonContent: '<ul><li>� ��� �������� �. ����. 4230��� Elte</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark38 = new ymaps.Placemark([56.021935, 38.390229], {
		hintContent: '�. ������������',
		balloonContent: '<ul><li>� ���� ���� ���� �. ������������ (������ �������� ����). 3020</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark39 = new ymaps.Placemark([55.867985, 49.110913], {
		hintContent: '�. ������',
		balloonContent: '<ul><li>� ��� � ���� � �������� �. ������ ( �������). 6040 ����� ( 26 ���.)</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark40 = new ymaps.Placemark([56.362556, 41.352443], {
		hintContent: '�. ������',
		balloonContent: '<ul><li>� ��� ������ �� �.�. ���������� �. ������. 4030 ��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark41 = new ymaps.Placemark([57.808807, 28.358384], {
		hintContent: '�. �����',
		balloonContent: '<ul><li>� �� � ��������� ����� ��ѻ ( �� ���������� �����������, �� ��������) �. �����. 8060</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark42 = new ymaps.Placemark([45.076209, 38.999894], {
		hintContent: '�. ���������',
		balloonContent: '<ul><li>� ��� ��� ����л �. ��������� ( �� �� ) 1216 �����</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark43 = new ymaps.Placemark([42.818180, 47.134498], {
		hintContent: '�. ��������',
		balloonContent: '<ul><li>� ��� ����������� ���������� ����� �. ��������, ���������� ��������. 3020</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark44 = new ymaps.Placemark([54.926458, 43.323747], {
		hintContent: '�. �����',
		balloonContent: '<ul><li>� ���� ���� ������ �. ����� ( �������) 4030 �� �����</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark45 = new ymaps.Placemark([55.969500, 38.039954], {
		hintContent: '�. �������',
		balloonContent: '<ul><li>� ��� ���ͻ ������� �.�������. 6040�</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark46 = new ymaps.Placemark([56.465394, 84.950395], {
		hintContent: '�. �����',
		balloonContent: '<ul><li>� ��� ������� ��������������� ����������� �. �����. 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark47 = new ymaps.Placemark([47.255100, 39.597198], {
		hintContent: '�. �������',
		balloonContent: '<ul><li>� ��� � ��� ����л � ��� �. ������� 4030 ��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark48 = new ymaps.Placemark([54.752318, 56.012157], {
		hintContent: '�. ���',
		balloonContent: '<ul><li>� �� ����λ ( �� �������� ) �. ���. 6040��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark49 = new ymaps.Placemark([54.724484, 20.527575], {
		hintContent: '�. �����������',
		balloonContent: '<ul><li>� ��� ����������� ����������� ����������� ��. ��������� ����� �. �����������. 1216</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark50 = new ymaps.Placemark([50.300371, 57.154555], {
		hintContent: '�. ������',
		balloonContent: '<ul><li>� ��� �������������������� (������� �����) �. ������. ��������� 7846</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark51 = new ymaps.Placemark([51.580544, 45.970054], {
		hintContent: '�. �������',
		balloonContent: '<ul><li>� ��� ����� � ��̻ ����� ������������������ �������������� �. �������. 3020 ��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark52 = new ymaps.Placemark([54.931214, 37.381027], {
		hintContent: '�. ��������',
		balloonContent: '<ul><li>� ��� � ���������� � �. ��������. 3020 ��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark53 = new ymaps.Placemark([43.254967, 76.856709], {
		hintContent: '�. ������',
		balloonContent: '<ul><li>� ���� ��������� ����������� ������� � ���������� (����), �. ������, 6040�</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark54 = new ymaps.Placemark([57.159568, 65.531158], {
		hintContent: '�. ������',
		balloonContent: '<ul><li>� ����� ������ ��������� ��������������� �����������. 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark55 = new ymaps.Placemark([56.146536, 40.378993], {
		hintContent: '�. ��������',
		balloonContent: '<ul><li>� ����� ��� ������������� ��������������� ����������� ��. �. � �. ����������� 6040</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark56 = new ymaps.Placemark([56.815390, 37.346748], {
		hintContent: '�. �����',
		balloonContent: '<ul><li>� ��� ���� "�������� ������������������ ����� �. �����, �������� �������. 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark57 = new ymaps.Placemark([56.110583, 47.340452], {
		hintContent: '�. ���������',
		balloonContent: '<ul><li>� ��� ���� ������� ( ��� �����Ļ ) �. ���������. 1216</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark58 = new ymaps.Placemark([54.855294, 46.600546], {
		hintContent: '�. �������',
		balloonContent: '<ul><li>� ��� ������ � ������������� �������, �. ������� (�������). 7846���</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark59 = new ymaps.Placemark([57.664551, 63.086718], {
		hintContent: '�. �����',
		balloonContent: '<ul><li>� ��� ���������� ������������� ����� �. �����. 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark60 = new ymaps.Placemark([45.042690, 41.962497], {
		hintContent: '�. ����������',
		balloonContent: '<ul><li>� ����� ��� "������-���������� ����������� �����������" �. ����������. ���� ������, ���� ������� 4230, 7846, 4030, 6040, 1216.</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark61 = new ymaps.Placemark([55.894582, 37.430579], {
		hintContent: '�. �����',
		balloonContent: '<ul><li>� ���� ���� ��. �.�. ���������, �. ����� (�������). 3020 ��</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark62 = new ymaps.Placemark([55.749699, 38.031857], {
		hintContent: '�. ���������������',
		balloonContent: '<ul><li>� ���� ���� ��. �.�. ��������� �. ���������������.</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark63 = new ymaps.Placemark([66.526564, 66.601868], {
		hintContent: '�. ��������',
		balloonContent: '<ul><li>� ���� ��� � 1 ����� ����� ���������� ����� �.�. ����������, �. ��������, 7846 �����</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	
	myMap.geoObjects.add(myPlacemark1);
	myMap.geoObjects.add(myPlacemark2);
	myMap.geoObjects.add(myPlacemark3);
	myMap.geoObjects.add(myPlacemark4);
	myMap.geoObjects.add(myPlacemark5);
	myMap.geoObjects.add(myPlacemark6);
	myMap.geoObjects.add(myPlacemark7);
	myMap.geoObjects.add(myPlacemark8);
	myMap.geoObjects.add(myPlacemark9);
	myMap.geoObjects.add(myPlacemark10);
	myMap.geoObjects.add(myPlacemark11);
	myMap.geoObjects.add(myPlacemark12);
	myMap.geoObjects.add(myPlacemark13);
	myMap.geoObjects.add(myPlacemark14);
	myMap.geoObjects.add(myPlacemark15);
	myMap.geoObjects.add(myPlacemark16);
	myMap.geoObjects.add(myPlacemark17);
	myMap.geoObjects.add(myPlacemark18);
	myMap.geoObjects.add(myPlacemark19);
	myMap.geoObjects.add(myPlacemark20);
	myMap.geoObjects.add(myPlacemark21);
	myMap.geoObjects.add(myPlacemark22);
	myMap.geoObjects.add(myPlacemark23);
	myMap.geoObjects.add(myPlacemark24);
	myMap.geoObjects.add(myPlacemark25);
	myMap.geoObjects.add(myPlacemark26);
	myMap.geoObjects.add(myPlacemark27);
	myMap.geoObjects.add(myPlacemark28);
	myMap.geoObjects.add(myPlacemark29);
	myMap.geoObjects.add(myPlacemark30);
	myMap.geoObjects.add(myPlacemark31);
	myMap.geoObjects.add(myPlacemark32);
	myMap.geoObjects.add(myPlacemark33);
	myMap.geoObjects.add(myPlacemark34);
	myMap.geoObjects.add(myPlacemark35);
	myMap.geoObjects.add(myPlacemark36);
	myMap.geoObjects.add(myPlacemark37);
	myMap.geoObjects.add(myPlacemark38);
	myMap.geoObjects.add(myPlacemark39);
	myMap.geoObjects.add(myPlacemark40);
	myMap.geoObjects.add(myPlacemark41);
	myMap.geoObjects.add(myPlacemark42);
	myMap.geoObjects.add(myPlacemark43);
	myMap.geoObjects.add(myPlacemark44);
	myMap.geoObjects.add(myPlacemark45);
	myMap.geoObjects.add(myPlacemark46);
	myMap.geoObjects.add(myPlacemark47);
	myMap.geoObjects.add(myPlacemark48);
	myMap.geoObjects.add(myPlacemark49);
	myMap.geoObjects.add(myPlacemark50);
	myMap.geoObjects.add(myPlacemark51);
	myMap.geoObjects.add(myPlacemark52);
	myMap.geoObjects.add(myPlacemark53);
	myMap.geoObjects.add(myPlacemark54);
	myMap.geoObjects.add(myPlacemark55);
	myMap.geoObjects.add(myPlacemark56);
	myMap.geoObjects.add(myPlacemark57);
	myMap.geoObjects.add(myPlacemark58);
	myMap.geoObjects.add(myPlacemark59);
	myMap.geoObjects.add(myPlacemark60);
	myMap.geoObjects.add(myPlacemark61);
	myMap.geoObjects.add(myPlacemark62);
	myMap.geoObjects.add(myPlacemark63);
});
}
</script> 
<script>

!function(e){"function"!=typeof e.matches&&(e.matches=e.msMatchesSelector||e.mozMatchesSelector||e.webkitMatchesSelector||function(e){for(var t=this,o=(t.document||t.ownerDocument).querySelectorAll(e),n=0;o[n]&&o[n]!==t;)++n;return Boolean(o[n])}),"function"!=typeof e.closest&&(e.closest=function(e){for(var t=this;t&&1===t.nodeType;){if(t.matches(e))return t;t=t.parentNode}return null})}(window.Element.prototype);


document.addEventListener('DOMContentLoaded', function() {

   /* ���������� � ���������� ������ ���������-������ � ��������.
      �������� ������� id, ����� �� ������ �� ������ �������� � ������� overlay*/
   var modalButtons = document.querySelectorAll('.js-open-modal'),
       overlay      = document.querySelector('.js-overlay-modal'),
       closeButtons = document.querySelectorAll('.js-modal-close');


   /* ���������� ������ ������ */
   modalButtons.forEach(function(item){

      /* ��������� ������ ������ ���������� ����� */
      item.addEventListener('click', function(e) {

         /* ������������� ����������� �������� ��������. ��� ��� ������ ������
            ���� ����� ������� ��-�������. ���-�� ������� ������, ���-�� ������.
            ����� ���������������. */
         e.preventDefault();

         /* ��� ������ ����� �� ������ �� ����� �������� ���������� �������� data-modal
            � ����� ������ ��������� ���� � ����� �� ���������. */
         var modalId = this.getAttribute('data-modal'),
             modalElem = document.querySelector('.modal[data-modal="' + modalId + '"]');


         /* ����� ���� ��� ����� ������ ��������� ����, ������� ������
            �������� � ���� ����� �������� ��. */
         modalElem.classList.add('active');
         overlay.classList.add('active');
      }); // end click

   }); // end foreach


   closeButtons.forEach(function(item){

      item.addEventListener('click', function(e) {
         var parentModal = this.closest('.modal');

         parentModal.classList.remove('active');
         overlay.classList.remove('active');
      });

   }); // end foreach


    document.body.addEventListener('keyup', function (e) {
        var key = e.keyCode;

        if (key == 27) {

            document.querySelector('.modal.active').classList.remove('active');
            document.querySelector('.overlay').classList.remove('active');
        };
    }, false);


	overlay.addEventListener('click', function() {
	   document.querySelector('.modal.active').classList.remove('active');
	   this.classList.remove('active');
	});




}); // end ready

</script>

<? /*<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>-->
*/ ?>
<link rel="stylesheet" href="/bitrix/templates/aspro_optimus/css/jquery.fancybox.css">
<script src="/bitrix/templates/aspro_optimus/js/jquery.maskedinput.js"></script>
<script src="/bitrix/templates/aspro_optimus/js/sweetalert.min.js"></script>
<script src="/bitrix/templates/aspro_optimus/js/jquery.fancybox.min.js"></script>
<script src="/bitrix/templates/aspro_optimus/js/jquery.fancybox.js"></script>

<div id="modalZayavka">
	<h2>�������� ������ �� �����</h2>
<form>
<input type="hidden" id="2orderurl" value="">
<input type="hidden" id="2ordername" value="">
<input type="hidden" id="2orderid" value="">
<label for="name">���</label>
<input id="add2ordername" name="name" type="text" placeholder="���� ���">
<label for="phone">�������</label>
<input id="add2orderphone" name="phone" type="text">
<script>
jQuery(function($){
$('#add2orderphone').mask("+7 (999) 999-9999");
$('#PHONE_FID11').mask("+7 (999) 999-9999");
});
</script>
<label for="message">���������</label>
<textarea name="message" id="add2ordertxt" placeholder="����� ���������"></textarea>
<div id="add2order" class="btn btn-primary" type="submit">���������</div>
</form>
</div>
<div id="add2orderrez"></div>
<script>
$(document).on('click','.2order',function(){
var ordrurl = "https://<?=$_SERVER["HTTP_HOST"]?>"+$(this).attr("data-url");
var ordrname = $(this).attr("data-name");
var ordrid = $(this).attr("data-id");
$("#2orderurl").val(ordrurl);
$("#2ordername").val(ordrname);
$("#2orderid").val(ordrid);
});
$(document).on('click','#add2order',function(){

	if (!$("#add2ordertxt").val()) {
    swal("���������� ��������� ������!", "�� �� ����� ����� ���������!", "error");
	}
	if (!$("#add2orderphone").val()) {
    swal("���������� ��������� ������!", "�� �� ������� ����� ��������!", "error");
	}
	if (!$("#add2ordername").val()) {
    swal("���������� ��������� ������!", "�� �� ������� ���� ���!", "error");
	}
	if ($("#add2ordertxt").val() && $("#add2orderphone").val() && $("#add2ordername").val()) {
    $.ajax({
    type: "POST",
    url: "/include/2ordrloader.php",
		data: ({itemid:$("#2orderid").val(), itemurl:$("#2orderurl").val(), itemname:$("#2ordername").val(), name:$("#add2ordername").val(), phone:$("#add2orderphone").val(), txt:$("#add2ordertxt").val()}),
    success: function(add2orderrez){
    $('#add2orderrez').html(add2orderrez);
    }
	});
}

});
</script>
<script src="//code.jivo.ru/widget/uA4UHXezO8" async></script>
<script src="//cdn.callibri.ru/callibri.js" charset="utf-8"></script>
<?if ($APPLICATION->GetCurPage()=='/' || $APPLICATION->GetCurPage()=='/contacts/') {?>
<script type="application/ld+json">
{
"@context": "https://schema.org",
"@type": "Organization",
"url": "https://skrouter.ru/",
"name": "�� ������",
"email": "info@skrouter.ru",
"logo": "https://skrouter.ru/upload/aspro.optimus/ca9/ca9e835d878b51b42074c6d8b4fee175.jpg",
"description": "������������������� ��������� ������ � ��� �� ��� �� ������. ���� ������������ �������������.","address": {
"@type": "PostalAddress",
"addressLocality": "����������, ������",
"postalCode": "124460",
"streetAddress": "2-� �������� ��-�, �.1, ���.1"
},"contactPoint" : [
{
"@type" : "ContactPoint",
"telephone" : "+7 (495) 974-07-61",
"contactType" : "customer service"
},{
"@type" : "ContactPoint",
"telephone" : "+7 (495) 799-78-77",
"contactType" : "customer service"
},{
"@type" : "ContactPoint",
"telephone" : "+7 (495) 728-10-05",
"contactType" : "customer service"
}],
"sameAs" : [
"https://vk.com/scrouter","https://www.youtube.com/@skrouter"]
}
</script>
 
<script type="application/ld+json">
{
"@context": "https://schema.org",
"@type": "WebSite",
"url": "https://skrouter.ru/",
"potentialAction": {
"@type": "SearchAction",
"target": "https://skrouter.ru/catalog/?q={search_term_string}",
"query-input": "required name=search_term_string"
}
}
</script>
<?}?>
	</body>
</html>