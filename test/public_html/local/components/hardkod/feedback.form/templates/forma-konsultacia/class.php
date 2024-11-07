<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (!class_exists('CAltasibFeedbackThemes')) { 
class CAltasibFeedbackThemes
{
	protected function getColorValues($color_)
	{
		$arColors = array();
		$rgbColor = "";
		for($i = 0 ; $i < 3 ; $i++)
		{
			$arr[$i] = substr($color_, $i * 2 + 1, 2);
		}
		$rgbColor .= hexdec($arr[0]) . hexdec($arr[1]) . hexdec($arr[2]);
		for ($i = 0; $i < 3; $i++)
		{
			if ($rgbColor % 1000 > 255)
			{
				$arColors[] = $rgbColor % 100;
				$rgbColor = intval($rgbColor / 100);
			}
			else
			{
				$arColors[] = $rgbColor % 1000;
				$rgbColor = intval($rgbColor / 1000);
			}
		}
		return array_reverse($arColors);
	}

	protected function lighterColor($color_)
	{
		$cols = self::getColorValues($color_);
		$arRgb = array();
		for ($i = 0; $i < count($cols); $i++) {
			$arRgb[] = $cols[$i] + 91 > 255 ? 255 : $cols[$i] + 91;
		}
		if ($arRgb[0] < 130) $arRgb[0] = $arRgb[0] + 80 > 255 ? 255 : $arRgb[0] + 80;
		if ($arRgb[0] > 165 && $arRgb[0] < 170 &&
			$arRgb[3] > 170 && $arRgb[3] < 190) {
			$arRgb[0] += 30;
			$arRgb[3] += 10;
		}
		if ($arRgb[1] > 150 && $arRgb[1] < 170) $arRgb[1]+= 20;
		return sprintf('#%02X%02X%02X', $arRgb[0],$arRgb[1],$arRgb[2]);
	}

	protected function darkerColor($color_)
	{
		$cols = self::getColorValues($color_);
		$arRgb = array();
		for ($i = 0; $i < count($cols); $i++) {
			$arRgb[] = $cols[$i] - 53 < 0 ? 0 : $cols[$i] - 53;
		}
		return sprintf('#%02X%02X%02X', $arRgb[0],$arRgb[1],$arRgb[2]);
	}

	protected function textColor($color_)
	{
		$cols = self::getColorValues($color_);
		$arRgb = array();
		for ($i = 0; $i < count($cols); $i++) {
			$arRgb[] = $cols[$i] - 53 < 0 ? 0 : $cols[$i] - 53;
		}
		return ($arRgb[0] * 0.229 + $arRgb[1] *0.587 + $arRgb[2] * 0.114);
	}
    public function test_i(){
        echo 'string3333';
    }

	public function Generate($color = false, $theme = false, $scheme = false, $ALX)
	{
		$tColor = '#fff';
		if($theme)
		{
			switch($theme)
			{
				case 'c1':
					$brighter = '#b2dfdb';
					$normal = '#009688';
					$darker = '#006257';
					break;
				case 'c2':
					$brighter = '#bbdefb';
					$normal = '#2196f3';
					$darker = '#0B5E9E';
					break;
				case 'c3':
					$brighter = '#ffcdd2';
					$normal = '#f44336';
					$darker = '#9F1C12';
					break;
				case 'c4':
					$brighter = '#c8e6c9';
					$normal = '#4caf50';
					$darker = '#19721F';
					break;
				case 'c5':
					$brighter = '#d1c4e9';
					$normal = '#673ab7';
					$darker = '#371377';
					break;
				case 'c6':
					$brighter = '#ffccbc';
					$normal = '#ff5722';
					$darker = '#A6300B';
					break;
				case 'c7':
					$brighter = '#f5f5f5';
					$normal = '#9e9e9e';
					$darker = '#545454';
					$tColor = '#000';
					break;
				case 'c8':
					$brighter = '#cfd8dc';
					$normal = '#607d8b';
					$darker = '#1F475A';
					break;
				default:
					$brighter = '#b2dfdb';
					$normal = '#009688';
					$darker = '#006257';
				break;
			}
		}
		else
		{
			$normal = $color;
			$brighter = self::lighterColor($color);
			$darker = self::darkerColor($color);

			$light = self::textColor($color);
			if($light>127)
				$tColor = '#000';
		}
		if($scheme == "PALE" && $theme)
		{
			$darker = $normal;
			$normal = $brighter;
		}

		$style = "#alx_feed_back_$ALX .afbf_radio_circle
{
	border-color:$normal;
}
#alx_feed_back_$ALX .afbf_checkbox.toggle label input[type=checkbox]:checked + .afbf_checkbox_box:after,
#alx_feed_back_$ALX .afbf_radio_check
{
	background-color:$normal;
}
#alx_feed_back_$ALX .afbf_feedback_poles .afbf_btn
{
	color:$tColor !important;
	background:$normal !important;
}
#alx_feed_back_$ALX .afbf_feedback_poles .afbf_btn:hover
{
	color:#fff !important;
	background:$darker !important;
}
#alx_feed_back_$ALX .afbf_checkbox.toggle label input[type=checkbox]:checked + .afbf_checkbox_box
{
	background-color:$brighter;
}
#alx_feed_back_$ALX .afbf_checkbox input[type=checkbox]:checked+.afbf_checkbox_box .afbf_checkbox_check:before,
#alx_feed_back_$ALX.floating_labels .afbf_item_pole.is_filled .afbf_name,
#alx_feed_back_$ALX.floating_labels .afbf_item_pole.is_focused .afbf_name
{
	color:$darker;
}
#alx_feed_back_$ALX.form_inputs_line .afbf_select,
#alx_feed_back_$ALX.form_inputs_line .afbf_textarea,
#alx_feed_back_$ALX.form_inputs_line .afbf_inputtext,
#alx_feed_back_$ALX.form_inputs_line .afbf_item_pole.is_focused .afbf_select,
#alx_feed_back_$ALX.form_inputs_line .afbf_item_pole.is_focused .afbf_textarea,
#alx_feed_back_$ALX.form_inputs_line .afbf_item_pole.is_focused .afbf_inputtext{
	background-image:-webkit-gradient(linear, left top, left bottom, from($normal), to($normal)), -webkit-gradient(linear, left top, left bottom, from(#e0e0e0), to(#e0e0e0));
	background-image:-webkit-linear-gradient($normal, $normal), -webkit-linear-gradient(#e0e0e0, #e0e0e0);
	background-image:-o-linear-gradient($normal, $normal), -o-linear-gradient(#e0e0e0, #e0e0e0);
	background-image:linear-gradient($normal, $normal), linear-gradient(#e0e0e0, #e0e0e0);
}
#alx_feed_back_$ALX.form_inputs_line .afbf_item_pole.error_pole .afbf_select,
#alx_feed_back_$ALX.form_inputs_line .afbf_item_pole.error_pole .afbf_textarea,
#alx_feed_back_$ALX.form_inputs_line .afbf_item_pole.error_pole .afbf_inputtext,
#alx_feed_back_$ALX.form_inputs_line .afbf_item_pole.error_pole.is_focused .afbf_select,
#alx_feed_back_$ALX.form_inputs_line .afbf_item_pole.error_pole.is_focused .afbf_textarea,
#alx_feed_back_$ALX.form_inputs_line .afbf_item_pole.error_pole.is_focused .afbf_inputtext{
	background-image:-webkit-gradient(linear, left top, left bottom, from(#f80000), to(#f80000)), -webkit-gradient(linear, left top, left bottom, from(#e0e0e0), to(#e0e0e0));
	background-image:-webkit-linear-gradient(#f80000, #f80000), -webkit-linear-gradient(#e0e0e0, #e0e0e0);
	background-image:-o-linear-gradient(#f80000, #f80000), -o-linear-gradient(#e0e0e0, #e0e0e0);
	background-image:linear-gradient(#f80000, #f80000), linear-gradient(#e0e0e0, #e0e0e0);
	-moz-background-size:100% 2px, 100% 1px;
	background-size:100% 2px, 100% 1px;
}
#alx_feed_back_$ALX .afbf_select, .afbf_textarea, .afbf_inputtext,
#alx_feed_back_$ALX .afbf_checkbox label
{
	color:#212121;
}
#alx_feed_back_$ALX .afbf_item_pole.is_focused .afbf_select,
#alx_feed_back_$ALX .afbf_item_pole.is_focused .afbf_textarea,
#alx_feed_back_$ALX .afbf_item_pole.is_focused .afbf_inputtext{
	border-color:$normal;
}";

		// $filelist = self::deleteOtherThemeCss(false);
		$filename = __DIR__.'/themes/theme_'.md5($theme.'_'.$color.'_'.$scheme).'.css';
		if(!file_exists($filename))
		{
			if($filelist && count($filelist) > 0)
			{
				foreach ($filelist as $item)
					unlink($item);
			}
			file_put_contents($filename, $style);
		}

	}

	public function deleteOtherThemeCss($delete = true)
	{
		$entries = scandir(__DIR__.'/themes/');
		$filelist = array();
		foreach($entries as $entry)
		{
			if(strpos($entry, "theme_") === 0)
			{
				$filelist[] = __DIR__.'/themes/'.$entry;
			}
		}
		if($delete)
		{
			if(count($filelist) > 0)
			{
				foreach($filelist as $item)
					unlink($item);
			}
		}
		return $filelist;
	}
}
}
?>