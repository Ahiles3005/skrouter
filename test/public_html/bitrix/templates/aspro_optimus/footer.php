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
		"FB_TEXT_NAME" => "Текст сообщения",
		"FB_TEXT_SOURCE" => "PREVIEW_TEXT",
		"FORM_ID" => "1",
		"IBLOCK_ID" => "16",
		"IBLOCK_TYPE" => "altasib_feedback",
		"INPUT_APPEARENCE" => array(
			0 => "DEFAULT",
		),
		"JQUERY_EN" => "N",
		"LINK_SEND_MORE_TEXT" => "Отправить другое сообщение?",
		"LOCAL_REDIRECT_ENABLE" => "N",
		"MASKED_INPUT_PHONE" => array(
			0 => "PHONE",
		),
		"MESSAGE_OK" => "Сообщение успешно добавлено",
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
		"USER_CONSENT_INPUT_LABEL" => "Согласие на обработку персональных данных",
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
		"ALX_NAME_LINK" => "Напишите нам",
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
			<p>г.Зеленоград, 2-й Западный пр-д,<br>
 д.1, стр.1</p>
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
				<div class="hardkod"><a href="https://hardkod.ru/">Hardkod.ru</a>&nbsp;— Техническая поддержка сайтов</div>
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
		hintContent: 'г. Москва',
		balloonContent: '<ul><li>• МГУ «ИнуМит» г. Москва. 4230</li><li>• МГТУ «СТАНКИН» г. Москва. Установка для напыления волноводов</li><li>• МГТУ «МИРЭА» г. Москва 3020ВЗ</li><li>• ГНЦ РФ ФГУП «НАМИ» г. Москва. 4030</li><li>• МВТУ им. Баумана «НПИ ГИПЕРИОН» г.Москва. 9565 ШВП</li><li>• МВТУ им. Баумана «ЦМИТ КУБ» г.Москва. 9565 (2 шт.)</li><li>• МВТУ им. Баумана «CМ - 12» Ракетно – космическое машиностроение г. Москва. 3020ВЗ</li><li>• РТУ МИРЭА « Российский Технологический Университет» г. Москва 3525</li><li>• МИТ АО «Корпорация» Московский институт теплотехники» г. Москва (МО России). 6040С</li><li>• ФИРЭ им. В.А. Котельникова РАН «Констэл» г. Фрязино. 6040САО «ВНИИНМ» ИОФ РАН г.Москва (ГК «Роскосмос») 3020ВЗ</li><li>• АО «ВНИИНМ» ИОФ РАН г.Москва (ГК «Роскосмос») 3020ВЗ</li><li>• НИЦ « Институт им. Курчатова» « ИТ в Судостроении » г. Москва. 1216</li><li>• Национальный исследовательский ядерный университет «МИФИ» г. Москва. 4030</li><li>• ФАНО ФГБУН ФИ им. П.Н. Лебедева г. Москва 6040 ВЗКМ АТС Серво Дельта (5+1)</li><li>• ФГУП НПО 52 «ВНИИА имени Н.П. Духова» ( ГК АЭ «Росатом») г. Москва 4030 РС Серво</li><li>• ОАО АВЭКС «Авиационная электроника и коммуникационные системы» г.Москва. 4030РС</li><li>• ОАО « Особое Конструкторское Бюро МЭИ» (ОАО «РКРКП и ИС» ФКА) г. Москва. 3020 ВЗ</li><li>• ОАО «2 МПЗ» Второй приборостроительный завод» ( ПА и СК) г. Москва. 4030</li><li>• ОАО МРЗ «Темп». г. Москва. 4030</li><li>• ОАО «Опытное производство» («ГСКБ «Алмаз-Антей») г. Москва. 4030 РС Серво</li><li>• ФГУП «НАМИ» г.Москва. 4030</li><li>• ОАО НПЦ «ЭХО+» Технопарк – Строгино, г. Москва. 4030РС</li><li>• ФГУП « КОСМОСАВИАСПЕЦСТРОЙ», г. Москва (Ростехнологии). 9565ШВП, 6040С</li><li>• ФГУП НПО 52 «ВНИИА имени Н.П. Духова» ( ГК АЭ «Росатом») г. Москва 4030 РС Серво</li><li>• ФГУП « НПЦАП имени Н.А. Пилюгина » ( РКП ФКА ПВО РФ ) г. Москва 6040АТС</li><li>• ФГУП « ВНИИА имени Н.Л. Духова » ( ГК по атомной энергии «Росатом» ) г. Москва 6040С</li><li>• ГМКБ «Вымпел» имени И.И. Торопова (ПВО и ПРО Россия) г. Москва. 3020ВЗ</li><li>• ОАО «Авиационная электроника и коммуникационные системы» (ОПК РФ) г. Москва.</li><li>• ПАО «НПО « АЛМАЗ » (ОПК, ЗРК и ПВО России) г. Москва 1070ВЗ</li><li>• ОАО МНПК «Авионика» имени О.В. Успенского ( ГК «Ростех») г. Москва. 3020 ВЗ</li><li>• ОАО «Ил» Авиационный комплекс им С.В.Ильюшина (ОАК) г. Москва.</li><li>• ЗАО « Гражданские Самолеты Сухого Аления Аэронаутика » г. Москва. ( Росавиа). 6040 Серво ( 26 мкм.)</li><li>• ПАО НПО «Андроидная техника» г. Москва. ( Роскосмос ). 4230</li><li>• АО «ОКБ МЭИ» (Российские космические системы), г.Москва. 7846, 6040М</li><li>• АО «МОСВОДОКАНАЛ» г. Москва 1070ВЗ</li><li>• ЗАО «ОКБ АТОМ» (ГК АЭ «РОСАТОМ»), г. Москва, 4030</li><li>• АО «НПО «ЛЭМЗ» (НПЦ «Утёс – Радары»), г. Москва 1070, 1070ВЗ, 1070ВЗ Серво</li><li>• ФГУП «Гохран РФ» Алмазный фонд России.</li><li>• Воинская часть № 145 г. Москвы. 4030 – 3 шт.</li><li>• ОАО «121 АРЗ» Отдел ИТ. Московская область, Одинцовский район. ( Росавиа). 3020 ВЗ</li><li>• ОАО «ЭКА» г. Юбилейный, Московская область (Роскосмос).</li><li>• АО НПП «Стрела», Московская область, р.п. Быково, БЛА МО РФ, ФСБ РФ, 6040 ВЗКМ</li><li>• НИТУ «МИССиС» Московский институт стали и сплавов. 4030 РС</li><li>• ФГУП « Радиоприбор», Московская область, г. Боровичи. 4030 РС Серво</li><li>• МГУ физический факультет. 7846</li><li>• МОКБ ФГУП «Марс» (Роскосмос). 4030</li><li>• ЗАО «НИИ «ЭСТО» ( Концерн Электронного и Микроэлектронного Машиностроения). 4030 РС Серво</li><li>• МГТУ «СТАНКИН». 4030</li><li>• ФГУП « НПО «ТЕХНОМАШ». ( РКА ) 4030РС Серво</li><li>• Институт космических технологий РУДН. 3020ВЗ</li><li>• ФГУП « ЦНИТИ «ТЕХНОМАШ». ( РКА ) 6040 Серво</li><li>• ОАО «Улан Удэнский Авиационный завод» (ОПК «Оборонпром», АВИ«Вертолеты России»)</li><li>• МГПУ факультет предпринимательства. 4230</li><li>• ФГБУ науки «Институт прикладной математики им. М.В. Келдыша». 7846</li><li>• Гос. НИААС (Глонасс). 3020</li><li>• ОАО ЦИАМ «Центральный институт авиационного моторостроения имени П.И. Баранова» 3020</li><li>• ФГУП «НПЦ автоматики и приборостроения имени академика Н.А.Пилюгина» 6040 С</li><li>• ФСБ России ФГУП НТЦ «Базис». 4030 РС Серво</li><li>• РХТУ имени Д.И. Менделеева. 7846</li><li>• ОАО « КОРПОРАЦИЯ « АЭРОКОСМИЧЕСКОЕ ОБОРУДОВАНИЕ» РФ.</li><li>• ОАО МНПК «Авионика» имени О.В. Успенского (ОАО «Концерн АВИОНИКА») 3020 ВЗ</li><li>• ФГУП КБ АТО (Росатом). 3020 ВЗ</li><li>• ФГБОУ ВПО МГТУ «СТАНКИН». 4030</li><li>• АО «ВНИИНМ им. Академика А.А. Бочвара» ( ГК «Росатом») 3020ВЗ</li><li>• НЦВИ ИОФ им. А.М. Прохорова РАН. 3020ВЗ</li><li>• АО «Концерн Радиоэлектронные Технологии (ГК «Ростех») 6040ВЗ</li><li>• ОАО «РАДИОФИЗИКА» (ОАО “Концерн ПВО «Алмаз-Антей»). 4230</li><li>• АО ГМКБ Государственное машиностроительное конструкторское Бюро им. И.И. Торопова 3020 ВЗ + 6040ВЗ</li><li>• ФГУП ФНПЦ «ПО СТАРТ им. М.В. Проценко».</li><li>• ОАО «Аэрокомпозит» (Росавиакосмос). 6040 Серво ( 26 мкм.)</li><li>• ОАО «ЕПК Волжский» (спецподшипники для ВПК и Авиакосмического комплекса) 3020 ВЗ</li><li>• ОАО « Кадошкинский Электротехнический Завод » Мордовия РФ. 1015 Серво</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/72.png',
		iconImageSize: [23, 23],
		iconImageOffset: [-15, 0]
	});
	myPlacemark2 = new ymaps.Placemark([59.939095, 30.315868], {
		hintContent: 'г. Санкт-Петербург',
		balloonContent: '<ul><li>• ФГБУН « Институт электрофизики и электроэнергетики РАН » г. Санкт-Петербург.</li><li>• ГБОУ «Президентский физико-математический лицей №239» г. Санкт-Петербург.</li><li>• ОАО «ТЕХПРИБОР» г. Санкт-Петербург (Росавиа). 6040С</li><li>• ЗАО «МОРСКИЕ НАВИГАЦИОННЫЕ СИСТЕМЫ» г.Санкт-Петербург(ВМФ России).</li><li>• СПб ГТИ(ТУ),Санкт-Петербургский государственный технологический институт (технический университет), г. Санкт-Петербург, 6040 Серво Дельта</li><li>• ФГБУН Лаборатория № 6003 ИЭЭ РАН г. Санкт-Петербург.</li><li>• ФГУП «Гознак» г. Санкт – Петербург.</li><li>• НИУ «ОНТИ» СПбГБУ г.Санкт – Петербург. Z – принтер</li><li>• ОАО «НИИ «Гириконд» ( РК РФ, Российская электроника) г. Санкт – Петербург.</li><li>• ЗАО «ПФ Созвездие» ( «ГК ПОЛИГОН» ) г. Санкт – Петербург. 3020 ВЗ – 2 шт.</li><li>• ФГУП «НПП Гамма» ( КИИ в ФСБ, МВД, РЭСТЭК, МинОбр.) г.Санкт – Петербург 6040С</li><li>• ФГАОУ ВО СПбПУ Санкт – Петербург. 3131</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/12-7.png',
		iconImageSize: [14, 14],
		iconImageOffset: [-15, 0]
	});
	myPlacemark3 = new ymaps.Placemark([55.991893, 37.214382], {
		hintContent: 'г. Зеленоград',
		balloonContent: '<ul><li>• ОАО « НПО Зенит» г. Зеленоград.3020</li><li>• ОАО «НИИ «Субмикрон» НЦ, г. Зеленоград. 3020ВЗ</li><li>• МИЭТ (ЦТПО) г. Зеленоград. 4030</li><li>• ОАО «НИИ «Элпа» г. Зеленоград 3020</li><li>• ОАО « НПО Ангстрем» г. Зеленоград. 3020</li><li>• ОАО « НИИМЭ и Микрон » ( Ситроникс, КМЭ, РТИ ) г. Зеленоград. 3020</li><li>• НПО «РОКОР» г. Зеленоград (АЭС, ТЭЦ, ГРЭС) 3525</li><li>• ОАО « НИИТМ » г. Зеленоград. 3020ВЗ</li><li>• ОАО НПК «НИИДАР», «Оборонные решения» Концерн «РТИ системы» г. Зеленоград. 4030</li><li>• ОАО « НПО Элма» г. Зеленоград.3020</li><li>• АО "НТЦ ЭЛИНС", г. Зеленоград, проектирование, разработка и производство промышленных компьютерных продуктов, 4030 РС Серво</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/12-7.png',
		iconImageSize: [14, 14],
		iconImageOffset: [-15, 0]
	});
	myPlacemark4 = new ymaps.Placemark([56.326797, 44.006516], {
		hintContent: 'г. Нижний Новгород',
		balloonContent: '<ul><li>• ЦКБ по СПК им. Р.Е. Алексеева, АКС ИНВЕСТ г. Нижний Новгород (Судостроение) 1216 Серво</li><li>• ОАО «Завод им. Г.И. Петровского» г. Нижний Новгород (Росавиа).4030<li>• ФГУП "ФНПЦ НИИИС им. Ю.Е. Седакова" г. Нижний Новгород (Росатом) 4030РС Серво</li><li>• ЗАО « НПП «САЛЮТ МИКРО» ПЭЭА КМ г. Нижний Новгород. 4030</li><li>• ОАО НПП « ПРИМА » г. Нижний Новгород (Росавиа).<li>• АО «НПО «ЭРКОН» (завод «Орбита»), г. Нижний Новгород, микроэлектроника, 3020ВЗКМ</li><li>• ОАО «Нижегородский авиастроительный завод «Сокол» (РСК МИГ «Цех №20 и 50»). 4030 РС Серво</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/12-7.png',
		iconImageSize: [14, 14],
		iconImageOffset: [-15, 0]
	});
	myPlacemark5 = new ymaps.Placemark([52.858248, 27.701393], {
		hintContent: 'Беларусь',
		balloonContent: '<ul><li>• ОАО «Витебский Завод Радиодеталей Монолит» Республика Белоруссия. 3020</li><li>• ОАО «Белаз» Республика Белоруссия.</li><li>• ОАО «Витебский Приборостроительный Завод» Республика Белоруссия. 3020ВЗ</li><li>• ИООО «Кроноспан» Сморгонь, Республика Белоруссия. 9565 ШВП</li><li>• УП «Цветлит» (Белорусское Общество Глухих) Гродно, Республика Белоруссия. 6040<li>• РУП «УНПЦ Технолаб» Гродно, Министерство Образования Республики Белоруссии. 4030РС</li><li>• РУП «Сморгонский агрегатный завод» Республика Белоруссия. 3020 ВЗ</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/12-7.png',
		iconImageSize: [14, 14],
		iconImageOffset: [-15, 0]
	});
	myPlacemark6 = new ymaps.Placemark([55.597475, 38.119802], {
		hintContent: 'г. Жуковский',
		balloonContent: '<ul><li>• ОАО «НИИ Авиационного оборудования» г. Жуковский ( ГК «Ростех») 4030РС</li><li>• ФГОУ СПО "ЖАТ им. В.А КАЗАКОВА" г. Жуковский ( Росавиа). 9565 ШВП<li>• НТЦ НПК ФГУП «ЦАГИ» г. Жуковский (Росавиа). 4030 РС Серво</li><li>• АО «НИИП им В.В. Тихомирова» г.Жуковский ( ГК «Ростех») 6040С</li><li>• ФГБОУ НИИ Приборостроения им. Тихомирова г. Жуковский. 4030 РС</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark7 = new ymaps.Placemark([52.721219, 41.452274], {
		hintContent: 'г. Тамбов',
		balloonContent: '<ul><li>• ОАО ТНИИР «Тамбовский научно-исследовательский институт радиотехники «ЭФИР» 4230<li>• ОАО « Тамбовский завод « ЭЛЕКТРОПРИБОР» 1216</li><li>• ОАО Тамбовский Приборостроительный завод «ТВЕС» 1216</li><li>• ПАО Завод « ЭЛЕКТРОПРИБОР», г. Тамбов ( Ростехнологии ). 1015</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark8 = new ymaps.Placemark([51.660781, 39.200269], {
		hintContent: 'г. Воронеж',
		balloonContent: '<ul><li>• ПАО «АВТОМАТИКА» г. Воронеж (Роскосмос). 3020 ВЗ</li><li>• ВВД «Всевеликое Войско Донское» ст. Новохоперская, Воронежская область. 9565 ШВП</li><li>• ОАО НПП «ВНИИ «ВЕГА»» «Созвездие» г. Воронеж (Роскосмос) 3020 ВЗ</li><li>• ОАО «Концерн «Созвездие» г. Воронеж ( ВС РФ ПВО и ПРО ). 4030 РС</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark9 = new ymaps.Placemark([53.902496, 27.561481], {
		hintContent: 'г. Минск',
		balloonContent: '<ul><li>• ЧУП «ЭнергоИнвестСервис» Минск, Республика Белоруссия. 7846 ШВП</li><li>• ЗАО «Алтимед» Минск, (Медицинское оборудование), Республика Белоруссия. 4030<li>• БНТУ «Белорусский национальный технический университет» г. Минск. 4030 РС</li><li>• БНТУ Белорусский Национальный Университет РБ г.Минск. 4030 РС</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark10 = new ymaps.Placemark([53.901460, 27.612631], {
		hintContent: 'г. Урал',
		balloonContent: '<ul><li>• ОАО НПП «УРАЛТЕХНОЛОГИЯ» (Уральский оптико – мехнический завод). 1216 Серво</li><li>• ПАО «Елецгидроагрегат» ( ФГУП «Уралвагонзавод» ) г. Липецк. 6040С</li><li>• ОАО « УРАЛЭЛЕМЕНТ» г. Верхний Уфалей (Гидроприбор, ВМФ РФ) 1216 Серво</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark11 = new ymaps.Placemark([54.513845, 36.261215], {
		hintContent: 'г. Калуга',
		balloonContent: '<ul><li>• ФГУП СПЗ «Сосенский приборостроительный завод», г. Калуга, 6040ВЗ</li><li>• ФГУП « КЭМЗ - ОГТ », Московская область, г. Калуга. 4030РС Серво</li><li>• ОАО ОНПП «Технологии», г. Калуга (Ростехнологии). 9565ШВП</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark12 = new ymaps.Placemark([55.933564, 37.514104], {
		hintContent: 'г. Долгопрудный',
		balloonContent: '<ul><li>• ПАО «ДНПП» г. Долгопрудный (ОАО “Концерн ВКО «Алмаз-Антей»). 4030 РС Серво – 2 шт.</li><li>• МФТИ «Физтех – школа аэрокосмических технологий» г. Долгопрудный. 3525 КМ</li><li>• ПАО «ДНПП», г.Долгопрудный ( ВКО «Алмаз-антей») 4030 РС Серво</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark13 = new ymaps.Placemark([56.010563, 92.852572], {
		hintContent: 'г. Красноярск',
		balloonContent: '<ul><li>• ФГБОУ СибТЦ «Сибирский государственный Технологический Центр» г. Красноярск. 1070</li><li>• ФГБОУ ВО «СГУНиТ им. Академика М.Ф. Решетнева», г. Красноярск, 3390</li><li>• ФГБОУ СибГАУ «Сибирский государственный аэрокосмический университет» г. Красноярск. 1225 Серво 4-х осевой</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark14 = new ymaps.Placemark([53.195538, 50.101783], {
		hintContent: 'г. Самара',
		balloonContent: '<ul><li>• ЦСКБ «ПРОГРЕСС» (Ракетно-космический центр) г. Самара Установка 3/1</li><li>• АО «РКЦ «Прогресс» г. Самара. изготовление и поставка спецоборудования для ГК «РОСКОСМОС».</li><li>• АО « НИИ «Экран» (ГК «Ростех») г. Самара. 6040 ВЗ</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark15 = new ymaps.Placemark([55.030199, 82.920430], {
		hintContent: 'г. Новосибирск',
		balloonContent: '<ul><li>• ФМВТ «НГТУ» г.Новосибирск 4230ШВП</li><li>• ОАО «НПО «Луч» г. Новосибирск (МО РФ) 3020ВЗКМ</li><li>• ОАО «Институт прикладной физики» г. Новосибирск ( МО РФ)</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark16 = new ymaps.Placemark([54.314192, 48.403123], {
		hintContent: 'г. Ульяновск',
		balloonContent: '<ul><li>• ЗАО « Аэрокомпозит» г.Ульяновск ( Росавиа). 6040 Серво ( 26 мкм.)</li><li>• УлГУ « Ульяновский Государственный Университет» г. Ульяновск. 4030 РС</li><li>• ОАО «АВИАСТАР – СП» г. Ульяновск (ОАК РФ). 1212 Серво Балт-систем</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/5-3.png',
		iconImageSize: [12, 12],
		iconImageOffset: [-15, 0]
	});
	myPlacemark17 = new ymaps.Placemark([54.193122, 37.617348], {
		hintContent: 'г. Тула',
		balloonContent: '<ul><li>• ТЛМЗ « ГАЗМОДЕЛЬ» (Тульский Литейно-Механический Завод) г. Тула 1070ВЗ</li><li>• АО «КЗЛМК» (Завод легких металлоконструкций) г.Киреев, Тульская область, 1225 Серво</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark18 = new ymaps.Placemark([53.195063, 45.018316], {
		hintContent: 'г. Пенза',
		balloonContent: '<ul><li>• ФГУП « ПНИЭИ» «Пензенский научно - исследовательский электротехнический институт» 4030 РС</li><li>• ОАО ПНИЭИ «Пензенский научно - исследовательский электротехнический институт» 4030 РС</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark19 = new ymaps.Placemark([51.730361, 36.192647], {
		hintContent: 'г. Курск',
		balloonContent: '<ul><li>• ФГБОУ ВПО «Юго–Западный Государственный Университет» г. Курск. 4030 РС</li><li>• ФГУП «18 ЦНИИ» г. Курск ( МО РФ) 6040ВЗ АТС</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark20 = new ymaps.Placemark([58.603591, 49.668014], {
		hintContent: 'г. Киров',
		balloonContent: '<ul><li>• ОАО ВПМЗ «Вятско-Полянский машиностроительный завод» « МОЛОТ» г. Киров.</li><li>• ФГБОУ ВО ВЯТГУ «Вятский Государственный Университет» г. Киров 6040С</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark21 = new ymaps.Placemark([48.707073, 44.516930], {
		hintContent: 'г. Волгоград',
		balloonContent: '<ul><li>• ОАО ПО « ПК «Ахтуба» г. Волгоград. ( ВМФ России). 3020 ВЗ</li><li>• ОАО « ОКЕАНПРИБОР» г. Волгоград. ( ВМФ РФ ).</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark22 = new ymaps.Placemark([56.838011, 60.597465], {
		hintContent: 'г. Екатеринбург',
		balloonContent: '<ul><li>• ОАО "НПП "Старт" им. А.И. Яскина" г. Екатеринбург (МО РФ) 4030РС</li><li>• АО «АтомПроект», г. Екатеринбург, Роутер 1225</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark23 = new ymaps.Placemark([54.629216, 39.736375], {
		hintContent: 'г. Рязань',
		balloonContent: '<ul><li>• ПАО завод «Красное знамя» (Бюро микросхем) г. Рязань 9565ШВП</li><li>• ОАО «РАДАРАВИАСЕРВИС» г. Рязань. (производство и обслуживание РЛС, ЭРП ). 1220 Серво</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark24 = new ymaps.Placemark([59.122612, 37.903461], {
		hintContent: 'г. Череповец',
		balloonContent: '<ul><li>• ФГБОУ ВО «Череповецкий государственный университет» г. Череповец, 6040ВЗ</li><li>• ЧГУ Череповецкий Государственный Университет, г. Черповец, 6040ВЗКМ</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark25 = new ymaps.Placemark([54.187433, 45.183938], {
		hintContent: 'г. Саранск',
		balloonContent: '<ul><li>• ФГУП «Электровыпрямитель ЗСП» г. Саранск.</li><li>• ФГУП « Орбита» г. Саранск. 3020</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/2.png',
		iconImageSize: [9, 9],
		iconImageOffset: [-15, 0]
	});
	myPlacemark26 = new ymaps.Placemark([55.914484, 36.827594], {
		hintContent: 'г. Истра',
		balloonContent: '<ul><li>• ОАО «НИИЭМ» (Ракетно-космическая промышленность ФКА) г. Истра. 3020</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark27 = new ymaps.Placemark([56.259643, 38.215006], {
		hintContent: 'г. Сергиев Посад',
		balloonContent: '<ul><li>• ОАО «ОК – Лоза» г. Сергиев Посад (Межреспубликанский Концерн «Подшипник»). 3020 ВЗ</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark28 = new ymaps.Placemark([52.290773, 104.307370], {
		hintContent: 'г. Иркутск',
		balloonContent: '<ul><li>• ПАО «ПО «ИЗТМ» Иркутский завод тяжёлого машиностроения. г. Иркутск.</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark29 = new ymaps.Placemark([55.105778, 60.123204], {
		hintContent: 'г. Миасс',
		balloonContent: '<ul><li>• НПО ЗАО «АМС» ( Медицинские асептические системы ) г. Миасс. 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark30 = new ymaps.Placemark([54.981910, 57.687171], {
		hintContent: 'г. Сим',
		balloonContent: '<ul><li>• ОАО «Агрегат» г. Сим ( концерн «Сатурн», Авиа/аэрокосмический комплекс ). 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark31 = new ymaps.Placemark([52.324420, 35.377592], {
		hintContent: 'г. Железногорск',
		balloonContent: '<ul><li>• ОАО «РУДОАВТОМАТИКА» г. Железногорск 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark32 = new ymaps.Placemark([47.244009, 39.709297], {
		hintContent: 'г. Ростов-на-Дону',
		balloonContent: '<ul><li>• ФГУП "Федеральный Научно-Производственный Центр Рниирс", Ростов-на-Дону. 6040М</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark33 = new ymaps.Placemark([43.204479, 46.118716], {
		hintContent: 'г. Грозный',
		balloonContent: '<ul><li>• ФГУП "ПАО «КЭМЗ» Курчалойский электромеханический завод» г. Грозный. 3020 ВЗ</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark34 = new ymaps.Placemark([55.831903, 37.331639], {
		hintContent: 'г. Красногорск',
		balloonContent: '<ul><li>• ФНПЦ ОАО «Красногорский Механический Завод им. С.А. Зверева»</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark35 = new ymaps.Placemark([59.469516, 40.103518], {
		hintContent: 'г. Сокол',
		balloonContent: '<ul><li>• БОУ СПО ВО «Сокольский ЛПТ» Вологодская область, г. Сокол. 4030 РС Серво</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark36 = new ymaps.Placemark([55.663036, 38.031345], {
		hintContent: 'г. Люберцы',
		balloonContent: '<ul><li>• АО «МТЛ» ( Инновационное Медицинское оборудование) г. Люберцы. 4030РС Серво</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark37 = new ymaps.Placemark([56.331693, 36.728716], {
		hintContent: 'г. Клин',
		balloonContent: '<ul><li>• ПАО «ЕЛОЧКА» г. Клин. 4230ШВП Elte</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark38 = new ymaps.Placemark([56.021935, 38.390229], {
		hintContent: 'г. Черноголовка',
		balloonContent: '<ul><li>• ФГУП РАМН ИТТФ г. Черноголовка (Физика Твердого Тела). 3020</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark39 = new ymaps.Placemark([55.867985, 49.110913], {
		hintContent: 'г. Казань',
		balloonContent: '<ul><li>• ЗАО « КАПО – композит» г. Казань ( Росавиа). 6040 Серво ( 26 мкм.)</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark40 = new ymaps.Placemark([56.362556, 41.352443], {
		hintContent: 'г. Ковров',
		balloonContent: '<ul><li>• ОАО «ЗАВОД им В.А. ДЯГТЕРЕВА» г. Ковров. 4030 РС</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark41 = new ymaps.Placemark([57.808807, 28.358384], {
		hintContent: 'г. Псков',
		balloonContent: '<ul><li>• АО « Псковский завод АДС» ( АО Российская электроника, ГК «Ростех») г. Псков. 8060</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark42 = new ymaps.Placemark([45.076209, 38.999894], {
		hintContent: 'г. Краснодар',
		balloonContent: '<ul><li>• ЗАО ОКБ «ИКАР» г. Краснодар ( МО РФ ) 1216 Серво</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark43 = new ymaps.Placemark([42.818180, 47.134498], {
		hintContent: 'г. Буйнакск',
		balloonContent: '<ul><li>• ОАО «Буйнакский агрегатный завод» г. Буйнакск, Республика Дагестан. 3020</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark44 = new ymaps.Placemark([54.926458, 43.323747], {
		hintContent: 'г. Саров',
		balloonContent: '<ul><li>• ФГУП РФЯЦ ВНИИЭФ г. Саров ( Росатом) 4030 РС Серво</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark45 = new ymaps.Placemark([55.969500, 38.039954], {
		hintContent: 'г. Фрязино',
		balloonContent: '<ul><li>• ИРЭ «РАН» КОНСТЕЛ г.Фрязино. 6040С</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark46 = new ymaps.Placemark([56.465394, 84.950395], {
		hintContent: 'г. Томск',
		balloonContent: '<ul><li>• ТПУ Томский политехнический университет г. Томск. 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark47 = new ymaps.Placemark([47.255100, 39.597198], {
		hintContent: 'г. Чалтырь',
		balloonContent: '<ul><li>• ОАО « НТЦ РАДАР» в ЮФО г. Чалтырь 4030 РС</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark48 = new ymaps.Placemark([54.752318, 56.012157], {
		hintContent: 'г. Уфа',
		balloonContent: '<ul><li>• АО «УППО» ( ГК «Ростех» ) г. Уфа. 6040ВЗ</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark49 = new ymaps.Placemark([54.724484, 20.527575], {
		hintContent: 'г. Калининград',
		balloonContent: '<ul><li>• БФУ «Балтийский Федеральный Университет им. Иммануила Канта» г. Калининград. 1216</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark50 = new ymaps.Placemark([50.300371, 57.154555], {
		hintContent: 'г. Актобе',
		balloonContent: '<ul><li>• ТОО «АктюбСтройХимМонтаж» (Трубный завод) г. Актобе. Казахстан 7846</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark51 = new ymaps.Placemark([51.580544, 45.970054], {
		hintContent: 'г. Саратов',
		balloonContent: '<ul><li>• ПАО «СЭПО – ЗЭМ» Завод электроагрегатного машиностроения г. Саратов. 3020 ВЗ</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark52 = new ymaps.Placemark([54.931214, 37.381027], {
		hintContent: 'г. Серпухов',
		balloonContent: '<ul><li>• ОАО « ХИМВОЛОКНО » г. Серпухов. 3020 ВЗ</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark53 = new ymaps.Placemark([43.254967, 76.856709], {
		hintContent: 'г. Алматы',
		balloonContent: '<ul><li>• ДТОО «Институт Космической Техники и Технологий» (ИКТТ), г. Алматы, 6040С</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark54 = new ymaps.Placemark([57.159568, 65.531158], {
		hintContent: 'г. Тюмень',
		balloonContent: '<ul><li>• ТюмГУ ФабЛаб Тюменский Государственный Университет. 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark55 = new ymaps.Placemark([56.146536, 40.378993], {
		hintContent: 'г. Владимир',
		balloonContent: '<ul><li>• ФГБОУ ВПО «Владимирский государственный университет им. А. и Н. Столетовых» 6040</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark56 = new ymaps.Placemark([56.815390, 37.346748], {
		hintContent: 'г. Кимры',
		balloonContent: '<ul><li>• ЗАО «НПП "Кимрский Машиностроительный Завод» г. Кимры, Тверская область. 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark57 = new ymaps.Placemark([56.110583, 47.340452], {
		hintContent: 'г. Чебоксары',
		balloonContent: '<ul><li>• ЗАО «НПО «Каскад» ( «ГК КАСКАД» ) г. Чебоксары. 1216</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark58 = new ymaps.Placemark([54.855294, 46.600546], {
		hintContent: 'г. Алатырь',
		balloonContent: '<ul><li>• ОАО «Завод « Электроприбор» Чувашия, г. Алатырь (РосАвиа). 7846ШВП</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark59 = new ymaps.Placemark([57.664551, 63.086718], {
		hintContent: 'г. Ирбит',
		balloonContent: '<ul><li>• ОАО «Ирбитский мотоциклетный завод» г. Ирбит. 4030</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark60 = new ymaps.Placemark([45.042690, 41.962497], {
		hintContent: 'г. Ставрополь',
		balloonContent: '<ul><li>• ФГАОУ ВПО "Северо-Кавказский федеральный университет" г. Ставрополь. Пять кафедр, пять станков 4230, 7846, 4030, 6040, 1216.</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark61 = new ymaps.Placemark([55.894582, 37.430579], {
		hintContent: 'г. Химки',
		balloonContent: '<ul><li>• ФГУП «НПО им. С.А. Лавочкина», г. Химки (Росавиа). 3020 ВЗ</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark62 = new ymaps.Placemark([55.749699, 38.031857], {
		hintContent: 'г. Железнодорожный',
		balloonContent: '<ul><li>• ФГУП НИТИ им. П.И. Снегирева г. Железнодорожный.</li></ul>'
	}, {
		iconLayout: 'default#image',
		iconImageHref: 'images/baluns/1.png',
		iconImageSize: [7, 7],
		iconImageOffset: [-15, 0]
	});
	myPlacemark63 = new ymaps.Placemark([66.526564, 66.601868], {
		hintContent: 'г. Салехард',
		balloonContent: '<ul><li>• МАОУ СОШ № 1 Имени Героя Советского союза И.В. Королькова, г. Салехард, 7846 ГеММа</li></ul>'
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

   /* Записываем в переменные массив элементов-кнопок и подложку.
      Подложке зададим id, чтобы не влиять на другие элементы с классом overlay*/
   var modalButtons = document.querySelectorAll('.js-open-modal'),
       overlay      = document.querySelector('.js-overlay-modal'),
       closeButtons = document.querySelectorAll('.js-modal-close');


   /* Перебираем массив кнопок */
   modalButtons.forEach(function(item){

      /* Назначаем каждой кнопке обработчик клика */
      item.addEventListener('click', function(e) {

         /* Предотвращаем стандартное действие элемента. Так как кнопку разные
            люди могут сделать по-разному. Кто-то сделает ссылку, кто-то кнопку.
            Нужно подстраховаться. */
         e.preventDefault();

         /* При каждом клике на кнопку мы будем забирать содержимое атрибута data-modal
            и будем искать модальное окно с таким же атрибутом. */
         var modalId = this.getAttribute('data-modal'),
             modalElem = document.querySelector('.modal[data-modal="' + modalId + '"]');


         /* После того как нашли нужное модальное окно, добавим классы
            подложке и окну чтобы показать их. */
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
	<h2>Оставить заявку на товар</h2>
<form>
<input type="hidden" id="2orderurl" value="">
<input type="hidden" id="2ordername" value="">
<input type="hidden" id="2orderid" value="">
<label for="name">Имя</label>
<input id="add2ordername" name="name" type="text" placeholder="Ваше имя">
<label for="phone">Телефон</label>
<input id="add2orderphone" name="phone" type="text">
<script>
jQuery(function($){
$('#add2orderphone').mask("+7 (999) 999-9999");
$('#PHONE_FID11').mask("+7 (999) 999-9999");
});
</script>
<label for="message">Сообщение</label>
<textarea name="message" id="add2ordertxt" placeholder="Текст сообщения"></textarea>
<div id="add2order" class="btn btn-primary" type="submit">Отправить</div>
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
    swal("Невозможно отправить заявку!", "Вы не ввели текст сообщения!", "error");
	}
	if (!$("#add2orderphone").val()) {
    swal("Невозможно отправить заявку!", "Вы не указали номер телефона!", "error");
	}
	if (!$("#add2ordername").val()) {
    swal("Невозможно отправить заявку!", "Вы не указали ваше имя!", "error");
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
"name": "СК Роутер",
"email": "info@skrouter.ru",
"logo": "https://skrouter.ru/upload/aspro.optimus/ca9/ca9e835d878b51b42074c6d8b4fee175.jpg",
"description": "Многофункциональные фрезерные станки с ЧПУ от ООО СК РОУТЕР. Сайт официального производителя.","address": {
"@type": "PostalAddress",
"addressLocality": "Зеленоград, Россия",
"postalCode": "124460",
"streetAddress": "2-й Западный пр-д, д.1, стр.1"
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