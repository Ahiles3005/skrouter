<?
if(!isset($arProperty["NUM_AMOUNT"])){
	$arSelect=array("ID", "PRODUCT_AMOUNT");
	if($arParams["SHOW_GENERAL_STORE_INFORMATION"] != "Y"){
		foreach($arResult["STORES"] as $pid => $arProperty){
			$arStore = CCatalogStore::GetList(array('TITLE' => 'ASC', 'ID' => 'ASC'), array("ACTIVE" => "Y", "PRODUCT_ID" => $arParams["ELEMENT_ID"], "ID" => $arProperty["ID"]), false, false, $arSelect)->Fetch();
			$arResult["STORES"][$pid]["NUM_AMOUNT"] = $arStore["PRODUCT_AMOUNT"];
		}
	}else{
		$filter = array( "ACTIVE" => "Y", "PRODUCT_ID" => $arParams["ELEMENT_ID"], "+SITE_ID" => SITE_ID, "ISSUING_CENTER" => 'Y' );
		$rsProps = CCatalogStore::GetList( array('TITLE' => 'ASC', 'ID' => 'ASC'), $filter, false, false, $arSelect );
		while ($prop = $rsProps->GetNext()){
			$amount = (is_null($prop["PRODUCT_AMOUNT"])) ? 0 : $prop["PRODUCT_AMOUNT"];
			$quantity += $amount;
		}
		unset($arResult["STORES"]);
		$arResult["STORES"][0]["NUM_AMOUNT"] =$arResult["STORES"][0]["AMOUNT"] = $quantity;
	}
}
?>
