<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function bhp_array_remove_empty($haystack)
{
	foreach ($haystack as $key => $value) {
		if (is_array($value)) {
			$haystack[$key] = bhp_array_remove_empty($haystack[$key]);
		}

		if (empty($haystack[$key])) {
			unset($haystack[$key]);
		}
	}

	return $haystack;
}

// well this is a formatting helper but it still uses arrays... :-O
function minimizeOpenHours($openHours){
	$outarr = array();
	$sploded = explode("<!-- xplodeit -->",$openHours);
	foreach($sploded as $chunk){
		$tempchunk = str_replace(array("\r\n", "\n", "\r"),"",$chunk);
		$tempchunk = str_replace('<div class="col-xs-12"><span class="weekdays">',
			'<div class="row"><div class="minday col-xs-6">' . "\n\n",
			$tempchunk);
		$tempchunk = str_replace('</span><span class="times"><span>',
			'</div><div class="col-xs-6 minhours">' . "\n\n",
			$tempchunk);
		$tempchunk = str_replace('</span><span class="times">',
			'</div><div class="col-xs-6 minhours">' . "\n\n",
			$tempchunk);
		$tempchunk = str_replace('</span></span></div>',
			'</div></div><div class="afterrow col-xs-12"></div>' . "\n\n",
			$tempchunk);
		$tempchunk = str_replace('</span></div>',
			'</div></div><div class="afterrow col-xs-12"></div>' . "\n\n" . "\n\n",
			$tempchunk);
		$tempchunk = str_replace("</span><span>","<br>",$tempchunk);
		$outarr[] = $tempchunk;
	}
	$mangled = implode("",$outarr);
	return $mangled;
}