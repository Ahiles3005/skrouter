<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);
if ($arParams['BX_EDITOR_RENDER_MODE'] == 'Y'):?>
	<img src="/bitrix/components/bitrix/map.yandex.view/templates/.default/images/screenshot.png" border="0" />
<?else:
	$arTransParams = array(
		'KEY' => $arParams['KEY'],
		'INIT_MAP_TYPE' => $arParams['INIT_MAP_TYPE'],
		'INIT_MAP_LON' => $arResult['POSITION']['yandex_lon'],
		'INIT_MAP_LAT' => $arResult['POSITION']['yandex_lat'],
		'INIT_MAP_SCALE' => $arResult['POSITION']['yandex_scale'],
		'MAP_WIDTH' => $arParams['MAP_WIDTH'],
		'MAP_HEIGHT' => $arParams['MAP_HEIGHT'],
		'CONTROLS' => $arParams['CONTROLS'],
		'OPTIONS' => $arParams['OPTIONS'],
		'MAP_ID' => $arParams['MAP_ID'],
		'LOCALE' => $arParams['LOCALE'],
		'ONMAPREADY' => 'BX_SetPlacemarks_'.$arParams['MAP_ID'],
	);

	if ($arParams['DEV_MODE'] == 'Y')
	{
		$arTransParams['DEV_MODE'] = 'Y';
		if ($arParams['WAIT_FOR_EVENT'])
			$arTransParams['WAIT_FOR_EVENT'] = $arParams['WAIT_FOR_EVENT'];
	}
?>
<script type="text/javascript">
var clusterer;
var clusterSVG = '<div class="cluster_custom"><span>$[properties.geoObjects.length]</span>'
				+'<svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56">'
					+'<defs><style>.cls-cluster, .cls-cluster3 {fill: #fff;}.cls-cluster {opacity: 0.5;}</style></defs>'
					+'<circle class="cls-cluster" cx="28" cy="28" r="28"/>'
					+'<circle data-name="Ellipse 275 copy 2" class="cls-cluster2 colored_theme_fill" cx="28" cy="28" r="25"/>'
					+'<circle data-name="Ellipse 276 copy" class="cls-cluster3" cx="28" cy="28" r="18"/>'
				+'</svg>'
			+'</div>';
			
function BX_SetPlacemarks_<?echo $arParams['MAP_ID']?>(map){
	if(typeof window["BX_YMapAddPlacemark"] != 'function'){
		(function(d, s, id){
			var js, bx_ym = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "<?=$templateFolder.'/script.js'?>";
			bx_ym.parentNode.insertBefore(js, bx_ym);
		}(document, 'script', 'bx-ya-map-js'));

		var ymWaitIntervalId = setInterval( function(){
				if(typeof window["BX_YMapAddPlacemark"] == 'function')
				{
					BX_SetPlacemarks_<?echo $arParams['MAP_ID']?>(map);
					clearInterval(ymWaitIntervalId);
				}
			}, 300
		);
		return;
	}

	var arObjects = {PLACEMARKS:[],POLYLINES:[]};
	clusterer = new ymaps.Clusterer();

	<?if( is_array($arResult['POSITION']['PLACEMARKS']) && ($cnt = count($arResult['POSITION']['PLACEMARKS'])) ){
		for( $i = 0; $i < $cnt; $i++ ){?>
			arObjects.PLACEMARKS[arObjects.PLACEMARKS.length] = BX_YMapAddPlacemark(map, <?echo CUtil::PhpToJsObject($arResult['POSITION']['PLACEMARKS'][$i])?><?if(count($arResult['POSITION']['PLACEMARKS'])>1):?>, true<?endif;?>);<?	
		}
	}?>

	<?if(isset($arResult['POSITION']['POLYLINES']) && is_array($arResult['POSITION']['POLYLINES']) && ($cnt = count($arResult['POSITION']['POLYLINES']))):
		for($i = 0; $i < $cnt; $i++):?>
			arObjects.POLYLINES[arObjects.POLYLINES.length] = BX_YMapAddPolyline(map, <?echo CUtil::PhpToJsObject($arResult['POSITION']['POLYLINES'][$i])?>);
		<?endfor;
	endif;

	if($arParams['ONMAPREADY']):?>
		if(window.<?echo $arParams['ONMAPREADY']?>){
			window.<?echo $arParams['ONMAPREADY']?>(map, arObjects);
		}
	<?endif;?>
	/* set dynamic zoom for ballons */
	   
	map.geoObjects.events.add('click', function (e) {
		setTimeout(function(){
			$('.ymaps-b-balloon .ymaps-b-balloon__close').addClass('close_custom').html(closeSVG);
		}, 100);
	});
	<?if(count($arResult['POSITION']['PLACEMARKS'])>1):?>
		 var clusterIcons = [{
			size: [56, 56],
			offset: [-28, -28]
		},];

		clusterer = new ymaps.Clusterer({
		   clusterIcons: clusterIcons,
		   clusterIconContentLayout: ymaps.templateLayoutFactory.createClass(clusterSVG),
	   });
	   clusterer.add(arObjects.PLACEMARKS);
	    map.geoObjects.add(clusterer);
		map.setBounds(clusterer.getBounds(), {
			zoomMargin: 40,
		});

	<?endif;?>	
	$('.block_container .items .item').addClass('initied');
}

$(document).ready(function(){
	$('.contacts-stores .item .top-wrap .show_on_map>span[data-coordinates]').on('click', function(){
		var arCoordinates = $(this).data('coordinates').split(','),
			mapOffsetTop = $('.contacts-page-map').offset().top;
		$('html, body').animate({scrollTop: mapOffsetTop - (isMobile ? 20 : 180)}, 300);
		map.setCenter([arCoordinates[0], arCoordinates[1]], '<?=$arResult['POSITION']['yandex_scale']?>');
	});

	$('.stores-list1 .item .top-wrap .show_on_map>span[data-coordinates]').on('click', function(){
		var arCoordinates = $(this).data('coordinates').split(','),
			mapOffsetTop = $('.contacts_map').offset().top;			
		$('html, body').animate({scrollTop: mapOffsetTop - (isMobile ? 20 : 180)}, 300);
		map.setCenter([arCoordinates[0], arCoordinates[1]], '17');
	});
});

</script>
<div class="bx-yandex-view-layout">
	<div class="bx-yandex-view-map">
		<?$APPLICATION->IncludeComponent('bitrix:map.yandex.system', '.default', $arTransParams, false, array('HIDE_ICONS' => 'Y'));?>
	</div>
</div>
<?endif;?>