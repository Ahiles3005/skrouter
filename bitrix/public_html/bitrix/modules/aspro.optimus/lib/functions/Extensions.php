<?
namespace Aspro\Functions;

class Extensions extends \COptimus
{
	public static function register()
	{
		$arConfig = [
			'owl_carousel' => [
				'js' => [
					SITE_TEMPLATE_PATH . '/vendor/js/carousel/owl/owl.carousel.js'
				],
				'css' => [
					SITE_TEMPLATE_PATH . '/vendor/css/carousel/owl/owl.carousel.css',
					SITE_TEMPLATE_PATH . '/vendor/css/carousel/owl/owl.theme.default.css',
				],
			],
			'left_menu_aim' => [
				'js' => SITE_TEMPLATE_PATH. '/js/leftMenuAim.js',
			],
			'menu_aim' => [
				'js' => SITE_TEMPLATE_PATH. '/vendor/js/jquery.menu-aim.js',
			],
			'smart_position_dropdown' => [
				'js' => SITE_TEMPLATE_PATH.'/js/smartPositionDropdown.js',
			],

		];

		foreach ($arConfig as $ext => $arExt) {
			\CJSCore::RegisterExt(self::partnerName . '_' . $ext, array_merge($arExt, ['skip_core' => true]));
		}
	}

	public static function init($arExtensions)
	{

		$arExtensions = is_array($arExtensions) ? $arExtensions : (array) $arExtensions;

		if ($arExtensions) {
			$arExtensions = array_map(function ($ext) {
				return strpos($ext, self::partnerName) !== false ? $ext : self::partnerName . '_' . $ext;
			}, $arExtensions);

			\CJSCore::Init($arExtensions);
		}
	}
}
?>