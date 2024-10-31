<?php

if ( ! defined( 'ABSPATH' ) ) exit;

add_action('admin_menu', 'business_hours_setup_menu');

function business_hours_setup_menu()
{
	//create new top-level menu
	add_menu_page(__('Open-Configuration', 'offen'), __('Open', 'offen'), 'administrator', __FILE__, 'plugin_business_hours_settings_page');

	//call register settings function
	add_action( 'admin_init', 'register_plugin_business_hours_settings' );
	add_action( 'admin_init', 'bhp_register_assets' );
}

function register_plugin_business_hours_settings()
{
	// Register our settings
    // General
	register_setting( 'business-hours-settings-group', 'general_title' );
	register_setting( 'business-hours-settings-group', 'general_open_hours_message' );
	register_setting( 'business-hours-settings-group', 'general_closed_hours_message' );
	register_setting( 'business-hours-settings-group', 'general_open_closed_badges_open' );
	register_setting( 'business-hours-settings-group', 'general_open_closed_badges_closed' );
	register_setting( 'business-hours-settings-group', 'general_open_24_text' );
	register_setting( 'business-hours-settings-group', 'general_closed_all_day_text' );

    // Opening hours
	register_setting( 'business-hours-settings-group', 'general_business_hours', 'validate_general_business_hours' );
	register_setting( 'business-hours-settings-group', 'saisonal_business_hours', 'validate_saisonal_business_hours' );

	// Business data
	register_setting( 'business-hours-settings-group', 'business_name');
	register_setting( 'business-hours-settings-group', 'business_contact_person');
	register_setting( 'business-hours-settings-group', 'business_phone_number');
	register_setting( 'business-hours-settings-group', 'business_fax_number');
	register_setting( 'business-hours-settings-group', 'business_email');
	register_setting( 'business-hours-settings-group', 'business_website');
	register_setting( 'business-hours-settings-group', 'business_address');
	register_setting( 'business-hours-settings-group', 'business_address_postal_code');
	register_setting( 'business-hours-settings-group', 'business_city');

	// Styling
	register_setting( 'business-hours-settings-group', 'styling_colors_background_primary');
	register_setting( 'business-hours-settings-group', 'styling_colors_background_secondary');
	register_setting( 'business-hours-settings-group', 'styling_colors_background_tertiary');
	register_setting( 'business-hours-settings-group', 'styling_colors_text_primary');
	register_setting( 'business-hours-settings-group', 'styling_colors_text_secondary');
	register_setting( 'business-hours-settings-group', 'styling_colors_text_tertiary');

	// Styling: Badges
	register_setting( 'business-hours-settings-group', 'styling_colors_text_badge_open');
	register_setting( 'business-hours-settings-group', 'styling_colors_text_badge_closed');
	register_setting( 'business-hours-settings-group', 'styling_colors_background_badge_open');
	register_setting( 'business-hours-settings-group', 'styling_colors_background_badge_closed');

	// Styling: Icons
	register_setting( 'business-hours-settings-group', 'styling_icon_header');

	// extra option for tz offset
	register_setting( 'business-hours-settings-group', 'tzoffset');

	// new option for collapse/expand days with same open times
    register_setting( 'business-hours-settings-group', 'expanddays');

    // new option controlling day name abbreviation
    register_setting('business-hours-settings-group', 'shortdays');

    // full date display (only some styles)?
    register_setting('business-hours-settings-group', 'fulldate');

    // 00:00 or 24:00?
    register_setting('business-hours-settings-group', 'twentyfour');
}

function validate_saisonal_business_hours($input)
{
	$input = bhp_clearSaisonalOpenHours($input);
	return $input;
}

function validate_general_business_hours($input)
{
	$input = bhp_clearOpenHours($input);
	return $input;
}

function bhp_register_assets()
{
	# Styles
	wp_register_style('business-hours-navigation-style', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/vendor/navigation/css/style.css');
	wp_register_style('timedropper-style', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/vendor/timedropper/timedropper.min.css');
	wp_register_style('jquery-ui-style', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/vendor/jquery-ui/jquery-ui.css');
	wp_register_style('spectrum-style', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/vendor/spectrum/spectrum.css');
    wp_register_style('font-awesome', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/vendor/font-awesome/css/font-awesome.min.css');
    wp_register_style('preview-helper-style', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/css/devmode.css');
    wp_register_style('strappy-style', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/widget/css/strappy.css');
    wp_register_style('widget-style', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/widget/css/style.css');

	wp_enqueue_style('jquery-ui-datepicker');
	wp_enqueue_style('font-awesome');
	wp_enqueue_style('timedropper-style');
	wp_enqueue_style('jquery-ui-style');
	wp_enqueue_style('spectrum-style');
	wp_enqueue_style('business-hours-navigation-style');
	wp_enqueue_style('preview-helper-style');
	wp_enqueue_style('strappy-style');
	wp_enqueue_style('widget-style');

	# Scripts
	wp_register_script('modernizr', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/vendor/modernizr/js/modernizr.js', array('jquery'));
	wp_register_script('spectrum', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/vendor/spectrum/spectrum.js', array('jquery'));
	wp_register_script('timedropper', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/vendor/timedropper/timedropper.min.js', array('jquery'));
	wp_register_script('business-hours-navigation-main', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/vendor/navigation/js/main.js', array('jquery'));

    $langBusinessHoursJS = bhp_getWeekdayTranslated();
    $langBusinessHoursJS['error_start_end_too_low'] = __('The closing time cannot be below the opening time', 'offen');
    $langBusinessHoursJS['remove_entry'] = __('Remove entry', 'offen');
    $langBusinessHoursJS['add_entry'] = __('New entry', 'offen');
    $langBusinessHoursJS['from'] = __('From', 'offen');
    $langBusinessHoursJS['to'] = __('To', 'offen');
    wp_register_script('business-hours', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/js/business_hours.js', array('jquery'));
    wp_localize_script('business-hours', 'lang', $langBusinessHoursJS);

    wp_register_script('live-preview', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/js/devmode.js', array('jquery'),'0.1',TRUE);

	wp_enqueue_script("jquery-ui-datepicker", FALSE, array('jquery'));
	wp_enqueue_script("modernizr", FALSE, array('jquery'));
	wp_enqueue_script("spectrum", FALSE, array('jquery'));
	wp_enqueue_script("timedropper", FALSE, array('jquery'));
	wp_enqueue_script("business-hours-navigation-main", FALSE, array('jquery', 'modernizr', 'timedropper'));
	wp_enqueue_script("business-hours", FALSE, array('jquery'));
	wp_enqueue_script("live-preview", FALSE, array('jquery'));

	wp_localize_script('live-preview','lpreview_vars',array(
	        'urltopost' => BUSINESS_HOURS_PLUGIN_BASE_URL . '/Admin/ajaxresp.php'
    ));
}

function bhp_sortSaisonalArrayByDay($input)
{
    $output = [];
    $i = 0;
    if(is_array($input)){
        foreach($input as $key => $value) {
            $output[$i] = bhp_sortArrayByDay($value);
            $output[$i]['from'] = $value['from'];
            $output[$i]['to']   = $value['to'];
            $i++;
        }
    }
    return $output;
}

function bhp_sortArrayByDay($input)
{
    if(!is_array($input)) return $input;
    $input = array_change_key_case($input, CASE_LOWER);
	$arr2 = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');

	foreach($arr2 as $v) {
		if(array_key_exists($v, $input)) {
			$output[$v]= $input[$v];
		}
	}

	return isset($output) ? $output : $input;
}

function plugin_business_hours_settings_page() {

    $daysTranslated = bhp_getWeekdayTranslated();
    $daysEnglish = bhp_getEnglishDays();

	$general_business_hours = bhp_sortArrayByDay(get_option('general_business_hours'));
	$saisonal_business_hours = get_option('saisonal_business_hours', []);

	// Set default values
    foreach ($daysTranslated as $keyDay => $valueDay) {
        if(!isset($general_business_hours[$keyDay])) $general_business_hours[$keyDay] = bhp_getEmptyDay();
    }

    #die(var_dump($general_business_hours));

	?>

    <style>
        *, *::after, *::before {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        *::after, *::before {
            content: '';
        }
    </style>

    <div class="business_hours">
        <form method="post" action="options.php" id="workHours">
            <input type="hidden" name="action" value="bhp_live_preview">
			<?php wp_nonce_field('bhp_generate_preview'); ?>
            <div class="cd-tabs">
                <nav>
                    <ul class="cd-tabs-navigation">
                        <li><a data-content="general" class="selected" href="#0"><i class="fa fa-cog"></i><br><?php echo __("General",'offen'); ?></a></li>
                        <li><a data-content="business" href="#0"><i  class="fa fa-database"></i><br><?php echo __("Company",'offen'); ?></a></li>
                        <li><a data-content="business_hours" href="#0"><i class="fa fa-clock-o"></i><br><?php echo __("Opening hours",'offen'); ?></a></li>
                        <li><a data-content="saisonal_business_hours" href="#0"><i class="fa fa-clock-o"></i><br><?php echo __("Seasonal opening hours",'offen'); ?></a></li>
                        <li><a data-content="styling" href="#0"><i class="fa fa-paint-brush"></i><br><?php echo __("Styling",'offen'); ?></a></li>
                        <li><a data-content="displaying" id="tabdisplaying" href="#0"><i class="fa fa-eye"></i><br><?php echo __("Preview",'offen'); ?></a></li>
                        <li><a data-content="widget" href="#0"><i class="fa fa-file"></i><br><?php echo __("Widget",'offen'); ?></a></li>
                        <li><a data-content="code" href="#0"><i class="fa fa-code"></i><br><?php echo __("Code",'offen'); ?></a></li>
                    </ul> <!-- cd-tabs-navigation -->
                </nav>


				<?php settings_fields( 'business-hours-settings-group' ); ?>
				<?php do_settings_sections( 'business-hours-settings-group' ); ?>
                <ul class="cd-tabs-content">
                    <li data-content="general" class="selected">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php _e("Title", 'offen'); ?></th>
                                <td><input type="text" name="general_title" value="<?php echo esc_attr( get_option('general_title') ); ?>" /></td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Text: Currently opened", 'offen'); ?></th>
                                <td><input type="text" name="general_open_hours_message" value="<?php echo esc_attr( get_option('general_open_hours_message') ); ?>" /></td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Text: Currently closed", 'offen'); ?></th>
                                <td><input type="text" name="general_closed_hours_message" value="<?php echo esc_attr( get_option('general_closed_hours_message') ); ?>" /></td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Texts: Opened/Closed", 'offen'); ?></th>
                                <td>
                                    <input type="text" name="general_open_closed_badges_open" value="<?php echo esc_attr( get_option('general_open_closed_badges_open') ); ?>" />
                                    <input type="text" name="general_open_closed_badges_closed" value="<?php echo esc_attr( get_option('general_open_closed_badges_closed') ); ?>" />
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Opening hours text: Open all day", 'offen'); ?></th>
                                <td>
                                    <input type="text" name="general_open_24_text" value="<?php echo esc_attr( get_option('general_open_24_text') ); ?>" />
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Opening hours text: Closed all day", 'offen'); ?></th>
                                <td>
                                    <input type="text" name="general_closed_all_day_text" value="<?php echo esc_attr( get_option('general_closed_all_day_text') ); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e("Opening hours: Group same days or show them separately", 'offen'); ?></th>
                                <td>
                                    <select name="expanddays">
                                        <?php
                                            $expand = get_option('expanddays');
                                            foreach(array("collapse" => __("Group", 'offen'), "expand" => __("Show separately", 'offen')) as $exopt => $label){
                                                if($expand == $exopt){
                                                    $issel = "selected";
                                                } else {
                                                    $issel = "";
                                                }
                                                echo "<option value='$exopt' $issel>$label</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e("Weekdays: Short or full spelling", 'offen'); ?></th>
                                <td>
                                    <select name="shortdays">
				                        <?php
				                        $shortdays = get_option('shortdays');
				                        foreach(array("short"=> __("Short", 'offen'), "full" => __("Full", 'offen')) as $abbrevopt => $label){
					                        if($shortdays == $abbrevopt){
						                        $issel = "selected";
					                        } else {
						                        $issel = "";
					                        }
					                        echo "<option value='$abbrevopt' $issel>$label</option>";
				                        }
				                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e("Timezone", 'offen'); ?></th>
                                <td>
                                    <?php _e("Offset", 'offen'); ?>:&nbsp;<?= offset_select() ?><br>
                                    (<?php _e("WordPress Server Time", 'offen'); ?>: <?= date('H:i') ?>)&nbsp;
                                    <p><?php _e('The server time needs to be set to the current time to ensure that the display of "opened" and "closed" functions properly. You can either specify the server time in the WordPress settings or set an offset above.', 'offen'); ?></p>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Date format", 'offen'); ?></th>
                                <td>
                                    <p class="dlabel">
                                        <?php
                                            if(get_option('fulldate') == 1){
                                                $fdzero = "";
                                                $fdone = "checked";
                                            } else {
                                                $fdzero = "checked";
                                                $fdone = "";
                                            }
                                        ?>
                                    <input class="fixr" type="radio" name="fulldate" value="0" <?php echo $fdzero ?>> <?php _e('Short (e.g. "12")', 'offen'); ?><br>

                                    <input class="fixr" type="radio" name="fulldate" value="1" <?php echo $fdone ?>> <?php _e('Full (e.g. "12.02")', 'offen'); ?><br>
                                    </p>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Midnight time format", 'offen'); ?></th>
                                <td>
	                                <?php
	                                if(get_option('twentyfour') == 1){
		                                $tfzero = "";
		                                $tfone = "checked";
	                                } else {
		                                $tfzero = "checked";
		                                $tfone = "";
	                                }
	                                ?>
                                    <p class="dlabel">
                                        <input class="fixr" type="radio" name="twentyfour" value="0" <?php echo $tfzero ?>> <?php _e("00:00", 'offen'); ?><br>

                                        <input class="fixr" type="radio" name="twentyfour" value="1" <?php echo $tfone ?>> <?php _e("24:00", 'offen'); ?><br>
                                    </p>
                                </td>
                            </tr>

                        </table>
                    </li>

                    <li data-content="business_hours">
                        <table class="form-table">
                            <tr valign="top" id="weekDaysArea">
				                <?php

				                $processed = [];
				                foreach ($general_business_hours as $key => $value) {
					                if(!isset($processed[$key])) {
						                echo sprintf('<div class="label %s" id="%s_area">', $daysEnglish[$key], $daysEnglish[$key]);
						                echo sprintf('<span class="field">%s:</span>', $daysTranslated[$key]);
					                }
					                $i = 0;

					                foreach ($value as $keyInner => $valueInner) {
						                if(empty($valueInner['from']) && empty($valueInner['last'])) $lastEmpty = true;
						                $i++;

						                echo '<div class="input_area">';
						                echo sprintf('<input type="text" class="time" name="general_business_hours[%s][%s][from]" value="%s">', $key, $keyInner, esc_attr($valueInner['from']));
						                echo sprintf('<input type="text" class="time" name="general_business_hours[%s][%s][to]" value="%s">', $key, $keyInner, esc_attr($valueInner['to']));
						                echo sprintf('<span class="plus_btn pl_btn" style=\'display:none\' data-number="%s" data-id="%s">+</span>', $keyInner, $daysEnglish[$key]);
						                echo '</div>';
					                }

					                if(!isset($processed[$key])) {
						                echo '<button type=\'button\' class="new_entry" style="display:none">' . __("New entry", 'offen') . '</button>';

						                echo '</div><br/><br/>';
					                }

					                $processed[$key] = true;
				                }
				                ?>
                            </tr>
                        </table>
                    </li>

                    <li data-content="saisonal_business_hours">

                        <div id="append_forms">
	                        <?php
	                        foreach($saisonal_business_hours as $keySaisonalBusinessHours => $valueSaisonalBusinessHours) {
		                        $saisonal_business_hours_week = bhp_sortArrayByDay($valueSaisonalBusinessHours);

		                        if(!empty($valueSaisonalBusinessHours['from']) && !empty($valueSaisonalBusinessHours['to']) && !isset($processed[$keySaisonalBusinessHours])) {
			                        echo sprintf("
                                            <div class='new_inputes'>
                                                <div class='date_input_area'>
                                                    <span><label> " . __("From", 'offen') . ": </label><input type='text' name='saisonal_business_hours[%s][from]' class='datepicker' value='%s'> </span>
                                                    <span><label> " . __("To", 'offen') . ": </label><input type='text' name='saisonal_business_hours[%s][to]' class='datepicker' value='%s'> </span>
                                                    <button  class='btn close_button button button-secondary killbutton'>" . __("Remove entry", 'offen') . "</button>
                                                </div>", $keySaisonalBusinessHours, $valueSaisonalBusinessHours['from'], $keySaisonalBusinessHours, $valueSaisonalBusinessHours['to']);

			                        foreach ($saisonal_business_hours_week as $saisonalBusinessHoursKey => $saisonalBusinessHoursValue) {
				                        $processed = [];

				                        if(!isset($processed[$saisonalBusinessHoursKey])) {
					                        echo sprintf('<div class="label %s" id="%s_area%s">', $daysEnglish[$saisonalBusinessHoursKey], $daysEnglish[$saisonalBusinessHoursKey], $keySaisonalBusinessHours);
					                        echo sprintf('<span class="field">%s:</span>', $daysTranslated[$saisonalBusinessHoursKey]);
				                        }

				                        $i = 0;
				                        foreach($saisonalBusinessHoursValue as $keyInner => $valueInner) {
					                        if(empty($valueInner['from']) && empty($valueInner['last'])) $lastEmpty = true;
					                        $i++;

					                        echo '<div class="input_area">';
					                        echo sprintf('<input type="text" class="time" name="saisonal_business_hours[%s][%s][%s][from]" value="%s">', $keySaisonalBusinessHours, $saisonalBusinessHoursKey, $keyInner, esc_attr($valueInner['from']));
					                        echo sprintf('<input type="text" class="time" name="saisonal_business_hours[%s][%s][%s][to]" value="%s">', $keySaisonalBusinessHours, $saisonalBusinessHoursKey, $keyInner, esc_attr($valueInner['to']));
					                        echo sprintf('<span class="new_plus_btn pl_btn" style=\'display:none\' data-number="%s" data-id="%s">+</span>', $keyInner, $daysEnglish[$saisonalBusinessHoursKey]);
					                        echo '</div>';
				                        }

				                        if(!isset($processed[$saisonalBusinessHoursKey])) {
					                        echo '<button type=\'button\' class="new_entry button button-secondary" style="display:none">' . __("New entry", 'offen') . '</button>';
					                        echo '</div><br/><br/>';
				                        }

				                        $processed[$saisonalBusinessHoursKey] = true;

			                        }
			                        echo '</div>';
		                        }
	                        }
	                        ?>
                        </div>
                        <button id="append_form_button" class="button button-secondary" onclick="return false;"><?php _e("Add new entry", 'offen'); ?></button>
                    </li>

                    <li data-content="business">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php _e("Company name", 'offen'); ?></th>
                                <td><input type="text" name="business_name" value="<?php echo esc_attr( get_option('business_name') ); ?>" /></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e("Contact person", 'offen'); ?></th>
                                <td><input type="text" name="business_contact_person" value="<?php echo esc_attr( get_option('business_contact_person') ); ?>" /></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e("Telephone number", 'offen'); ?></th>
                                <td><input type="text" name="business_phone_number" value="<?php echo esc_attr( get_option('business_phone_number') ); ?>" /></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e("Fax number", 'offen'); ?></th>
                                <td><input type="text" name="business_fax_number" value="<?php echo esc_attr( get_option('business_fax_number') ); ?>" /></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e("E-Mail", 'offen'); ?></th>
                                <td><input type="text" name="business_email" value="<?php echo esc_attr( get_option('business_email') ); ?>" /></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e("Website", 'offen'); ?></th>
                                <td><input type="text" name="business_website" value="<?php echo esc_attr( get_option('business_website') ); ?>" /></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e("Address", 'offen'); ?></th>
                                <td><input type="text" name="business_address" value="<?php echo esc_attr( get_option('business_address') ); ?>" /></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e("Postal code", 'offen'); ?></th>
                                <td><input type="text" name="business_address_postal_code" value="<?php echo esc_attr( get_option('business_address_postal_code') ); ?>" /></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e("City", 'offen'); ?></th>
                                <td><input type="text" name="business_city" value="<?php echo esc_attr( get_option('business_city') ); ?>" /></td>
                            </tr>

                        </table>
                    </li>

                    <li data-content="styling">
                        <table class="form-table">

							<tr><td colspan="2">
								<p><em><?php _e("There are 4 styles for embedding the plugin as a widget or a shortcode. Style 1 - Style 3 need more space and are recommended for separate sub-pages, for example a dedicated page for the opening hours. Style 4 is recommended for usage within the sidebar widget or as a footer widget.", 'offen'); ?></em></p>
							</td></tr>
                            <tr valign="top">
                                <th scope="row"><?php _e("Color: Background primary", 'offen'); ?></th>
                                <td><input type="text" class="spectrum" name="styling_colors_background_primary" value="<?php echo esc_attr( get_option('styling_colors_background_primary') ); ?>" /></td>
                            </tr>
                             <tr valign="top">
                                <th scope="row"><?php _e("Color: Background secondary", 'offen'); ?></th>
                                <td><input type="text" class="spectrum" name="styling_colors_background_secondary" value="<?php echo esc_attr( get_option('styling_colors_background_secondary') ); ?>" /></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e("Color: Background tertiary", 'offen'); ?></th>
                                <td><input type="text" class="spectrum" name="styling_colors_background_tertiary" value="<?php echo esc_attr( get_option('styling_colors_background_tertiary') ); ?>" /></td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Color: Background Badge - Open", 'offen'); ?></th>
                                <td><input type="text" class="spectrum" name="styling_colors_background_badge_open" value="<?php echo esc_attr( get_option('styling_colors_background_badge_open') ); ?>" /></td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Color: Background Badge - Closed", 'offen'); ?></th>
                                <td><input type="text" class="spectrum" name="styling_colors_background_badge_closed" value="<?php echo esc_attr( get_option('styling_colors_background_badge_closed') ); ?>" /></td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Color: Text primary", 'offen'); ?></th>
                                <td><input type="text" class="spectrum" name="styling_colors_text_primary" value="<?php echo esc_attr( get_option('styling_colors_text_primary') ); ?>" /></td>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php _e("Color: Text secondary", 'offen'); ?></th>
                                <td><input type="text" class="spectrum" name="styling_colors_text_secondary" value="<?php echo esc_attr( get_option('styling_colors_text_secondary') ); ?>" /></td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Color: Text Tertiary", 'offen'); ?></th>
                                <td><input type="text" class="spectrum" name="styling_colors_text_tertiary" value="<?php echo esc_attr( get_option('styling_colors_text_tertiary') ); ?>" /></td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Color: Text Badge - Open", 'offen'); ?></th>
                                <td><input type="text" class="spectrum" name="styling_colors_text_badge_open" value="<?php echo esc_attr( get_option('styling_colors_text_badge_open') ); ?>" /></td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Color: Text Badge - Closed", 'offen'); ?></th>
                                <td><input type="text" class="spectrum" name="styling_colors_text_badge_closed" value="<?php echo esc_attr( get_option('styling_colors_text_badge_closed') ); ?>" /></td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><?php _e("Header Icon Class (FontAwesome)", 'offen'); ?></th>
                                <td><input type="text" name="styling_icon_header" style="width: 200px;" value="<?php echo esc_attr( get_option('styling_icon_header') ); ?>" />
								<p><?php _e("You can find more icon-codes at", 'offen'); ?> <a target="_blank" href="http://fontawesome.io">http://fontawesome.io</a>.</p>
								</td>
                            </tr>

                        </table>
                    </li>

					<!-- TEMPORARY STYLES AND SCRIPTS FOR DEVELOPMENT! -->
					<!-- link rel="stylesheet" href="/wp-content/plugins/business_hours/devmode.css" />
					<script src="/wp-content/plugins/business_hours/devmode.js"></script -->


                    <li data-content="displaying" id="displaying">
                     	<select name="ajaxstyle" id="ajaxstyle">
							<option value="1"><?php _e("Sub page - Style 1", 'offen'); ?></option>
							<option value="2"><?php _e("Sub page - Style 2", 'offen'); ?></option>
							<option value="3"><?php _e("Sub page - Style 3", 'offen'); ?></option>
							<option value="4"><?php _e("Sidebar/Footer Style", 'offen'); ?></option>
                            <option value="5"><?php _e("Tabular form", 'offen'); ?></option>
						</select>


						<div id="devoutput" class="devoutput">

							<?

							wp_register_style('business-hours-widget-style', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/widget/css/style.css');
							wp_register_style('business-hours-widget-fonts', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/widget/css/fonts.css');
                            wp_register_style('font-awesome', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/vendor/font-awesome/css/font-awesome.min.css');

							wp_enqueue_style('business-hours-widget-style');
							wp_enqueue_style('business-hours-widget-fonts');
							wp_enqueue_style('font-awesome');

							?>

							<div id="jqconsole">
                                <?php _e("Please wait.", 'offen'); ?>
							</div>
						</div>
                    </li>

                    <li data-content="widget">
                        <p><?php echo '<a href="widgets.php">' . __('The widget is named "Open" and can be set here', 'offen') . '</a>'; ?></p>
                    </li>

                    <li data-content="code">
                        <p><?php _e("The opening hours can be displayed at any post. A separate style can be specified for each post.", 'offen'); ?></p>
                        <p><?php _e("The following text (shortcode) needs to be added to the post:", 'offen'); ?></p>

                        [business-hours style="1"]

                        <p><?php _e('The number 1 can be replaced with the number of the desired styled. You can find all 5 styles at the "Preview" tab.', 'offen'); ?></p>
                    </li>
                </ul> <!-- cd-tabs-content -->
                <div style="margin-left: 15%;"> <?php submit_button(__('Save settings', 'offen'), 'primary', 'plugin-business-hours-submit'); ?></div>


            </div> <!-- cd-tabs -->
        </form>

    </div>
<?php }

// need to handle the live preview via the built-in WP ajax capability to satisfy requirements.

add_action('wp_ajax_bhp_live_preview','bhp_live_preview');

function bhp_live_preview()
{
    require_once(BUSINESS_HOURS_PLUGIN_BASE_PATH . "/Admin/ajaxresp.php");
}

function offset_select()
{
    $storedtz = get_option('tzoffset');
    $selfield = "<select name='tzoffset'>";

    for ($ofs = -11; $ofs <= 11; $ofs = $ofs++) {

        if((integer) $storedtz == (integer) $ofs || (!$storedtz && $ofs == 0)) {
            $selected = "selected";
        } else {
            $selected = "";
        }

        if((integer) $ofs < 0) {
            $disp = (string) $ofs . "h";
        } elseif ((integer) $ofs == 0) {
            $disp = $ofs;
        } else {
            $disp = "+" . $ofs . "h";
        }

        $selfield .= "<option value='$ofs' $selected>$disp</option>";
        $ofs++;
    }

    $selfield .= "</select>";

    return $selfield;
}

?>
