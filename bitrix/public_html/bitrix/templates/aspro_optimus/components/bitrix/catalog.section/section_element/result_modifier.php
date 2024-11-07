<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();


	$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'] , 'ID' => $arResult['ID']); // выберет потомков без учета активности
	$uf_name = array('UF_ELEMENT_NAME');
	$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc') ,$arFilter , false, $uf_name);
	while ($arSect = $rsSect->GetNext())
	{
		// получаем подразделы
		$arResult['UF_ELEMENT_NAME'] = $arSect['UF_ELEMENT_NAME'];
		// echo '<pre style="display:none;">';
		// print_r($arSect); 
		// echo '</pre>';
	}


// echo '<pre style="display:none;">';
		// print_r($arParams ); 
		// echo '</pre>';



