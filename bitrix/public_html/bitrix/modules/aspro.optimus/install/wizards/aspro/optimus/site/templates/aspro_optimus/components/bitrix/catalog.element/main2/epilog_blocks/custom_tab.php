<?if($arParams["SHOW_ADDITIONAL_TAB"] == "Y"):?>
	<div class="drag_block">
		<div class="wrap_md wrap_md_row">
			<div class="iblock md-100">
				<h4><?=GetMessage("ADDITIONAL_TAB");?></h4>
				<?$APPLICATION->IncludeFile(SITE_DIR."include/additional_products_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ADDITIONAL_DESCRIPTION')));?>
			</div>
		</div>
	</div>
<?endif;?>