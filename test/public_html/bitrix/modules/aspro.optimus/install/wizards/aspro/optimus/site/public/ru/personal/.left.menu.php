<?
$aMenuLinks = Array(
	Array(
		"��� �������", 
		"#SITE_DIR#personal/index.php", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"������� ������", 
		"#SITE_DIR#personal/orders/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"������ ����", 
		"#SITE_DIR#personal/account/", 
		Array(), 
		Array(), 
		"CBXFeatures::IsFeatureEnabled('SaleAccounts')" 
	),
	Array(
		"������ ������", 
		"#SITE_DIR#personal/private/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"������� ������", 
		"#SITE_DIR#personal/change-password/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"������� �������", 
		"#SITE_DIR#personal/orders/?filter_history=Y", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"������� �������", 
		"#SITE_DIR#personal/profiles/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"�������", 
		"#SITE_DIR#basket/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"��������", 
		"#SITE_DIR#personal/subscribe/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"��������", 
		"#SITE_DIR#contacts/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"�����", 
		"?logout=yes&login=yes", 
		Array(), 
		Array("class"=>"exit"), 
		"\$GLOBALS[\"USER\"]->isAuthorized()" 
	)
);
?>