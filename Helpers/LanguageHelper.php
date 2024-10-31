<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function bhp_convertToGermanDays($openHours)
{
	$converted = [];
	$germanDays = bhp_getWeekdayTranslated();
	foreach($openHours as $key => $value) {
		$converted[$germanDays[$key]] = $value;
	}

	return $converted;
}

function bhp_getEnglishDays()
{
	$englishDays = array_keys(bhp_getWeekdayTranslated());
	return array_combine($englishDays, $englishDays);
}

function bhp_getWeekdayTranslated()
{
	return [
		'monday'    => __('Monday', 'offen'),
		'tuesday'   => __('Tuesday', 'offen'),
		'wednesday' => __('Wednesday', 'offen'),
		'thursday'  => __('Thursday', 'offen'),
		'friday'    => __('Friday', 'offen'),
		'saturday'  => __('Saturday', 'offen'),
		'sunday'    => __('Sunday', 'offen'),
	];
}