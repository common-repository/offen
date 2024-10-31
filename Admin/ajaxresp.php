<?php

if ( ! defined( 'ABSPATH' ) ) exit;
check_ajax_referer('bhp_generate_preview');
if( ! current_user_can('manage_options')){
	wp_die("verboten");
}


$formdata = $_POST['preview_formdata'];
foreach ($formdata as $apair) {
    $fname = sanitize_text_field($apair['name']);
    $fvalue = sanitize_text_field($apair['value']);

    /*
     * because of the way we have to use serializeArray() instead of serialize()
     * in the jQuery code that submits to this (to satisfy WP.org requirements),
     * if we passed any array structures from the form, we will need to unpack them.
     */

    $sploder = preg_split("/\[|\]/",$fname,null,PREG_SPLIT_NO_EMPTY); // split on [ AND ]
    if(count($sploder) > 1){
        $basename = array_shift($sploder); // $sploder still has the remains of the array!
        if(!is_array($formdata[$basename])){
            // we need to set up the array
            $formdata[$basename] = array();
        }
        // we need to use & references to dynamically build out the array...
        $reftoarr = & $formdata[$basename];
        foreach($sploder as $segment){
            $reftoarr = & $reftoarr[$segment];
        }
        $reftoarr = $fvalue;
    } else {
        $formdata[$fname] = $fvalue;
    }
}

// all of our options are now in $formdata, including hours arrays.

function bhp_ajax_option_helper($formdata, $optname, $optdefault)
{
    if ($formdata[$optname] && trim($formdata[$optname]) != '') {
        return $formdata[$optname];
    } else {
        return $optdefault;
    }
}

function bhp_ajax_groupSameDays($openHours,$formdata) {
	/**
	 * New option determines whether we group similar days or not.
	 * Even if not, we need this function to build the $summaries
	 * array to get passed back to everything else.
	 */

	$exopt = $formdata['expanddays'];

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

function generateOpeningHours_ajax($formdata, $openingHoursGrouped, $htmlDays, $htmlHours)
{
    $openHoursGenerated = [];
    foreach ($openingHoursGrouped as $summary) {
        $hours = is_null($summary['hours']) ? sprintf(' %s <br/>', esc_attr(bhp_ajax_option_helper($formdata, 'general_closed_all_day_text', ''))) : $summary['hours'];
        if ($hours == '<span>99:99 - 99:99</span>') $hours = esc_attr(bhp_ajax_option_helper($formdata, 'general_closed_all_day_text', ''));
        if ($hours == '<span>00:00 - 24:00</span>' || $hours == '<span>0:00 - 0:00</span>') $hours = esc_attr(bhp_ajax_option_helper($formdata, 'general_open_24_text', ''));

        if (count($summary['days']) === 1) {
#die(var_dump($summary));
            $openHoursGenerated[] = sprintf($htmlDays, reset($summary['days'])) . sprintf($htmlHours, $hours) . PHP_EOL;
        } else {
            $days = sprintf('%s - %s', reset($summary['days']), end($summary['days']));
            $openHoursGenerated[] = sprintf($htmlDays, $days) . sprintf($htmlHours, $hours) . PHP_EOL;
        }
    }

    $openHoursOutput = "";
    foreach ($openHoursGenerated as $hoursrow) {
        $openHoursOutput .= "\n\n" . '<!-- div class="row" --><div class="col-xs-12">' . $hoursrow . "</div><!-- /div -->\n\n";
    }
    return $openHoursOutput;
}

// ---------------------------------------------------------------------------

// Set the style
if ($formdata['ajaxstyle']) {
    $style = $formdata['ajaxstyle'];
} else {
    $style = 1;
}


$general_business_hours = bhp_sortArrayByDay($formdata['general_business_hours']);

$saisonal_business_hours = bhp_sortSaisonalArrayByDay($formdata['saisonal_business_hours']);



foreach ($saisonal_business_hours as $keySaisonalBusinessHours => $valueSaisonalBusinessHours) {
	if ( bhp_isBetweenDate( $valueSaisonalBusinessHours['from'], $valueSaisonalBusinessHours['to'] ) ) {
		$isOpen = bhp_isOpenCheck( $valueSaisonalBusinessHours );

		if ( $formdata[ 'shortdays' ] == 'full' ) {
			$openHours = bhp_groupSameDays( ( bhp_convertToGermanDays( bhp_multiDimensionalDayArrayToOneDimensionalArray( $valueSaisonalBusinessHours ) ) ) );
		} else {
			$openHours = bhp_groupSameDays( bhp_firstTwoDayChars( bhp_convertToGermanDays( bhp_multiDimensionalDayArrayToOneDimensionalArray( $valueSaisonalBusinessHours ) ) ) );
		}
	}
}

// Generate the business hours and add them to the widget layout
// note new option, we may skip abbreviation
if($formdata['shortdays'] == 'full') {
	$openHours = bhp_ajax_groupSameDays( bhp_convertToGermanDays( bhp_multiDimensionalDayArrayToOneDimensionalArray( $general_business_hours ) ),$formdata );
} else {
	$openHours = bhp_ajax_groupSameDays( bhp_firstTwoDayChars( bhp_convertToGermanDays( bhp_multiDimensionalDayArrayToOneDimensionalArray( $general_business_hours ) ) ),$formdata );
}

$openHours = generateOpeningHours_ajax($formdata, $openHours, '<span class="weekdays">%s:</span>', '<span class="times">%s</span>');



// Get the options
// Styling
$styling_colors_background_primary = esc_attr(bhp_ajax_option_helper($formdata, 'styling_colors_background_primary', 'DimGrey'));
$styling_colors_background_secondary = esc_attr(bhp_ajax_option_helper($formdata, 'styling_colors_background_secondary', 'White'));
$styling_colors_background_tertiary = esc_attr(bhp_ajax_option_helper($formdata, 'styling_colors_background_tertiary', '#3C3C3B'));
$styling_colors_text_primary = esc_attr(bhp_ajax_option_helper($formdata, 'styling_colors_text_primary', 'black'));
$styling_colors_text_secondary = esc_attr(bhp_ajax_option_helper($formdata, 'styling_colors_text_secondary', 'red'));
$styling_colors_text_tertiary = esc_attr(bhp_ajax_option_helper($formdata, 'styling_colors_text_tertiary', 'yellow'));

// Styling: Badges
$styling_colors_text_badge_open = esc_attr(bhp_ajax_option_helper($formdata, 'styling_colors_text_badge_open', 'White'));
$styling_colors_text_badge_closed = esc_attr(bhp_ajax_option_helper($formdata, 'styling_colors_text_badge_closed', 'White'));
$styling_colors_background_badge_open = esc_attr(bhp_ajax_option_helper($formdata, 'styling_colors_background_badge_open', 'Green'));
$styling_colors_background_badge_closed = esc_attr(bhp_ajax_option_helper($formdata, 'styling_colors_background_badge_closed', 'Red'));
$styling_icon_header = esc_attr(bhp_ajax_option_helper($formdata, 'styling_icon_header', 'fa fa-clock-o clock-icon'));

// Business data
$bh_title = esc_attr(bhp_ajax_option_helper($formdata, 'general_title', 'Opening hours'));
$business_name = esc_attr(bhp_ajax_option_helper($formdata, 'business_name', ''));
$business_contact_person = esc_attr(bhp_ajax_option_helper($formdata, 'business_contact_person', ''));
$business_phone_number = esc_attr(bhp_ajax_option_helper($formdata, 'business_phone_number', ''));
$business_fax_number = esc_attr(bhp_ajax_option_helper($formdata, 'business_fax_number', ''));
$business_email = esc_attr(bhp_ajax_option_helper($formdata, 'business_email', ''));
$business_website = esc_attr(bhp_ajax_option_helper($formdata, 'business_website', ''));
$business_address = esc_attr(bhp_ajax_option_helper($formdata, 'business_address', ''));
$business_address_postal_code = esc_attr(bhp_ajax_option_helper($formdata, 'business_address_postal_code', ''));
$business_city = esc_attr(bhp_ajax_option_helper($formdata, 'business_city', ''));
$business_style = esc_attr(bhp_ajax_option_helper($formdata, 'business_style', ''));

// Texts
$general_open_hours_message = esc_attr(bhp_ajax_option_helper($formdata, 'general_open_hours_message', ''));
$general_closed_hours_message = esc_attr(bhp_ajax_option_helper($formdata, 'general_closed_hours_message', ''));
$general_open_closed_badges_open = esc_attr(bhp_ajax_option_helper($formdata, 'general_open_closed_badges_open', ''));
$general_open_closed_badges_closed = esc_attr(bhp_ajax_option_helper($formdata, 'general_open_closed_badges_closed', ''));

// Generate open status
$isOpen = bhp_isOpen($general_business_hours, $saisonal_business_hours);
if ($isOpen) {
    $openMessage = $general_open_hours_message;
} else {
    $openMessage = $general_closed_hours_message;
}

// Render the widget
ob_start();
include sprintf(BUSINESS_HOURS_PLUGIN_BASE_PATH . '/templates/widget/style%u.php', $style);
$widgetout = ob_get_clean();
// return $widgetout;
echo $widgetout;
wp_die();