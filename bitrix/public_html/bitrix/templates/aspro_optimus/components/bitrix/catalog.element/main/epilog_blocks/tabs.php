<?

//show tabs block
$countTab = 0;
foreach ($arTabOrder as $iTab => $tabCode) {
	include $tabCode.'.php';

	$bShowVar = 'bShow_'.$tabCode;
	if (isset($$bShowVar) && $$bShowVar) {
		$countTab++;
	} else {
		unset($arTabOrder[$iTab]);
	}
}?>

<?/*if($countTab):?>
	<div class="tabs_section drag_block_detail">
		<ul class="tabs1 main_tabs1 nav-tabs tabs-head" role="tablist">
			<?$iTab = 0;?>
			<?foreach($arTabOrder as $tabCode):?>
				<?$upperTabCode = mb_strtoupper($tabCode);?>
				<li class="<?=$tabCode?>_tab<?=(!($iTab++) ? ' current' : '')?>" id="<?=$tabCode?>_tab" role="tab" aria-controls="<?=$tabCode?>_panel">
					<span><?=$arParams['T_'.$upperTabCode]?></span>
				</li>
			<?endforeach;?>
		</ul>
		<ul class="tabs_content tabs-body">
			<?$iTab = 0;?>
			<?foreach($arTabOrder as $tabCode):?>
				<?include $tabCode.'.php';?>
			<?endforeach;?>
		</ul>
	</div>
<?endif;*/?>
