<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function bhp_isOpen($generalBusinessHours, $saisonalBusinessHours)
{
	// Check the seasonal opening hours
	foreach ($saisonalBusinessHours as $keySaisonalBusinessHours => $valueSaisonalBusinessHours) {
		if(bhp_isBetweenDate($valueSaisonalBusinessHours['from'], $valueSaisonalBusinessHours['to'])) {
			$isOpen = bhp_isOpenCheck($valueSaisonalBusinessHours);
			if($isOpen) return true;
		}
	}

	return bhp_isOpenCheck($generalBusinessHours);
}

// Internal method for isOpen()
function bhp_isOpenCheck($openHours) {
	$currentDay = strtolower(date('l'));
	$today = $openHours[$currentDay];
	foreach($today as $key => $value) {
		if(!empty($value['from']) || !empty($value['to'])) {
			$from = $value['from'];
			$to = $value['to'];

			// Check if we are opened all day long
			if(($from == '0:00' || $from == '24:00') && ($to == '0:00' || $to == '24:00')) return true;

			// Check if we are currently opened
			if(bhp_isBetween($from, $to)) return true;
		}
	}

	return false;
}

function bhp_firstTwoDayChars($openHours)
{
	if(is_array($openHours)) {
		$return = [];
		foreach($openHours as $key => $value) {
			$firstTwoChars = substr($key, 0, 2);
			$return[$firstTwoChars] = $value;
		}

		return $return;
	}

	return substr($openHours, 0, 2);
}

function bhp_multiDimensionalDayArrayToOneDimensionalArray($openHours)
{
	$output = [];
	foreach($openHours as $keyOuter => $valueOuter) {
		$temp = '';
		if(is_array($valueOuter)) {
			foreach($valueOuter as $keyInner => $valueInner) {
				if(!empty($valueInner['to']) || !empty($valueInner['from'])) {
					$temp .= sprintf('<span>%s - %s</span>', $valueInner['from'], $valueInner['to']);
				}
			}

			$output[$keyOuter] = empty($temp) ? NULL : $temp;
		}
	}

	return $output;
}

/**
 * Determines whether we group similar days or not.
 * Even if not, we need this function to build the $summaries
 * array to get passed back to everything else.
 */
function bhp_groupSameDays($openHours)
{
	$exopt = get_option('expanddays');

	$summaries = array();
	foreach ($openHours as $day => $hours) {

		if($exopt == "expand"){ // default is collapse, so even if the option doesn't exist this is fine.
			$summaries[] = array('hours' => $hours, 'days' => array($day));
		} else {
			if ( count( $summaries ) === 0 ) {
				$current = false;
			} else {
				$current = &$summaries[ count( $summaries ) - 1 ];
			}
			if ( $current === false || $current['hours'] !== $hours ) {
				$summaries[] = array( 'hours' => $hours, 'days' => array( $day ) );
			} else {
				$current['days'][] = $day;
			}
		}
	}

	return $summaries;
}

function bhp_generateOpeningHours($openingHoursGrouped, $htmlDays, $htmlHours)
{
	$openHoursGenerated = [];
	foreach ($openingHoursGrouped as $summary) {
		$hours = is_null($summary['hours']) ? sprintf(' %s <br/>', esc_attr( get_option('general_closed_all_day_text'))) : $summary['hours'];
		if($hours == '<span>99:99 - 99:99</span>') $hours = esc_attr( get_option('general_closed_all_day_text'));
		if($hours == '<span>00:00 - 24:00</span>' || $hours == '<span>0:00 - 0:00</span>') $hours = esc_attr( get_option('general_open_24_text'));

		if(count($summary['days']) === 1) {
			$openHoursGenerated[] = sprintf($htmlDays, reset($summary['days'])) . sprintf($htmlHours,$hours) . PHP_EOL;
		} else {
			$days = sprintf('%s - %s', reset($summary['days']), end($summary['days']));
			$openHoursGenerated[] = sprintf($htmlDays, $days) . sprintf($htmlHours,$hours) . PHP_EOL;
		}
	}

	$openHoursOutput = "";	
	foreach($openHoursGenerated as $hoursrow) {
		$openHoursOutput .= "\n\n" . '<!-- xplodeit --><div class="col-xs-12">' . $hoursrow . "</div>\n\n";
	}

	return $openHoursOutput;
}

function bhp_getEmptyDay()
{
	return [0 => ['from' => '', 'to' => '']];
}

function bhp_clearSaisonalOpenHours($openHours)
{
	$output = [];

	if(!is_array($openHours)) return $output;

	$iOuter = 0;
	foreach ($openHours as $keyInput => $valueInput) {
		foreach ($valueInput as $key => $value) {

			$i = 0;
			$temp = [];

			if(is_array($value)) {
				foreach ($value as $keyInner => $valueInner) {
					if(!empty($valueInner['from']) || !empty($valueInner['to'])) {
						$temp[$i] = $valueInner;
						$i++;
					}
				}

				$output[$iOuter][$key] = !empty($temp) ? $temp : bhp_getEmptyDay();
			} else {
				$output[$iOuter][$key] = $value;
			}

		}
		$iOuter++;
	}

	return $output;
}

function bhp_clearOpenHours($openHours)
{
	$output = [];
	foreach ($openHours as $key => $value) {

		$i = 0;
		$temp = [];
		foreach ($value as $keyInner => $valueInner) {
			if(!empty($valueInner['from']) || !empty($valueInner['to'])) {
				$temp[$i] = $valueInner;
				$i++;
			}
		}

		$output[$key] = !empty($temp) ? $temp : bhp_getEmptyDay();
	}

	return $output;
}

function bhp_isBetweenDate($from, $till, $input = null)
{
	if(is_null($input)) $input = date("d-m-Y");

	$f = DateTime::createFromFormat('!d-m-Y', $from);
	$t = DateTime::createFromFormat('!d-m-Y', $till);
    if($f === false || $t === false) return false;

    // If seasonal hours end on current day they are not used, we therefore add a day
    $t->add(new DateInterval('P1D'));

	$i = DateTime::createFromFormat('!d-m-Y', $input);

	if (($i >= $f) && ($i < $t)) {
		return true;
	}

	return false;
}

/*
 * Check if the supplied/current time is within the supplied time-frame.
 *
 * This function respects the timezone offset that might
 * have been specified inside the admin area
 *
 */
function bhp_isBetween($from, $till, $input = null) {
	$tzoffset = get_option('tzoffset');
	$fixedtime = date("H:i", strtotime('+'. (integer) $tzoffset . ' hours'));

    if(is_null($input)) $input = $fixedtime;

	$f = DateTime::createFromFormat('!H:i', $from);
	$t = DateTime::createFromFormat('!H:i', $till);
	$i = DateTime::createFromFormat('!H:i', $input);

    if($f === false || $t === false || $i === false) return false;

    if ($f > $t) $t->modify('+1 day');

	return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
}