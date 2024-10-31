<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class business_hours_widget extends WP_Widget {

	// constructor
	function __construct() {
		parent::WP_Widget(false, $name = __('Opening hours', 'offen'));
	}

	// widget form creation
	function form( $instance ) {
		// PART 1: Extract the data from the instance variable
		$instance = wp_parse_args( (array) $instance);
		$style = $instance['style'];

		// PART 2-3: Display the fields
		?>

		<!-- PART 3: Widget style field START -->
		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>">Style:
				<select class='widefat' id="<?php echo $this->get_field_id('style'); ?>"
				        name="<?php echo $this->get_field_name('style'); ?>" type="text">
					<option value='1' <?php echo ($style == '1')? 'selected' : ''; ?>>
                        <?php _e("Sub page - Style 1", 'offen'); ?>
					</option>
                    <option value='2' <?php echo ($style ==' 2')? 'selected' : ''; ?>>
                        <?php _e("Sub page - Style 2", 'offen'); ?>
                    </option>
                    <option value='3' <?php echo ($style ==' 3')? 'selected' : ''; ?>>
                        <?php _e("Sub page - Style 3", 'offen'); ?>
                    </option>
                    <option value='4' <?php echo ($style ==' 4')? 'selected' : ''; ?>>
                        <?php _e("Sidebar/Footer Style", 'offen'); ?>
                    </option>
                    <option value='5' <?php echo ($style ==' 5')? 'selected' : ''; ?>>
                        <?php _e("Tabular form", 'offen'); ?>
                    </option>
				</select>
			</label>
		</p>
		<!-- Widget style field END -->
		<?php
	}

	// widget update
	function update($new_instance, $old_instance) {
		// Validate the selection
		$styleNew = $new_instance['style'];
		if($styleNew != '1' && $styleNew != '2' && $styleNew != '3' && $styleNew != '4' && $styleNew != '5') return $old_instance;

		$instance = $old_instance;
		$instance['style'] = $styleNew;
		return $instance;
	}

	// widget display
	// display widget
	function widget($args, $instance) {
		// Set the style
		if($instance && isset($instance['style']) && !empty($instance['style'])) {
			// Widget settings
			$style = $instance['style'];
		} else {
			// Shortcode settings
			$style = isset($args['style']) && !empty($args['style']) ? $args['style'] : esc_attr(get_option('style', '1'));
		}

		// Validate selected style number and get the path for the corresponding template
		$stylePath = sprintf(BUSINESS_HOURS_PLUGIN_BASE_PATH . '/templates/widget/style%u.php', $style);
		if(!is_readable($stylePath)) {
		    echo sprintf('<span style="border: 1px solid red;">%s</span>', _("Error: Invalid style selected for opening hours", 'offen'));
		    return;
        }
		
		// Include the css files
		wp_register_style('business-hours-widget-style', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/widget/css/style.css');
		wp_register_style('business-hours-widget-fonts', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/widget/css/fonts.css');
		wp_register_style('font-awesome', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/vendor/font-awesome/css/font-awesome.min.css');
		// wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
		wp_register_style('strappy', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/widget/css/strappy.css');

		wp_enqueue_style('business-hours-widget-style');
		wp_enqueue_style('business-hours-widget-fonts');
		wp_enqueue_style('font-awesome');
		// wp_enqueue_style('prefix_bootstrap');
		wp_enqueue_style('strappy');

		//wp_register_script('prefix_bootstrap', BUSINESS_HOURS_PLUGIN_BASE_URL . '/templates/assets/vendor/bootstrap/bootstrap.min.js');
		//wp_enqueue_script('prefix_bootstrap');

		// Get the opening hours
		$general_business_hours = bhp_sortArrayByDay($this->fixtf('general_business_hours'));
		$saisonal_business_hours = bhp_sortSaisonalArrayByDay($this->fixtf('saisonal_business_hours', true));



		// Generate the business hours and add them to the widget layout
		// note new option, we may skip abbreviation
        if(get_option('shortdays') == 'full') {
			$openHours = bhp_groupSameDays( bhp_convertToGermanDays( bhp_multiDimensionalDayArrayToOneDimensionalArray( $general_business_hours ) ) );
		} else {
			$openHours = bhp_groupSameDays( bhp_firstTwoDayChars( bhp_convertToGermanDays( bhp_multiDimensionalDayArrayToOneDimensionalArray( $general_business_hours ) ) ) );
		}

        foreach ($saisonal_business_hours as $keySaisonalBusinessHours => $valueSaisonalBusinessHours) {
            if(bhp_isBetweenDate($valueSaisonalBusinessHours['from'], $valueSaisonalBusinessHours['to'])) {
                $isOpen = bhp_isOpenCheck( $valueSaisonalBusinessHours );

                if ( get_option( 'shortdays' ) == 'full' ) {
                    $openHours = bhp_groupSameDays( ( bhp_convertToGermanDays( bhp_multiDimensionalDayArrayToOneDimensionalArray( $valueSaisonalBusinessHours ) ) ) );
                } else {
                    $openHours = bhp_groupSameDays( bhp_firstTwoDayChars( bhp_convertToGermanDays( bhp_multiDimensionalDayArrayToOneDimensionalArray( $valueSaisonalBusinessHours ) ) ) );
                }
            }
        }

		$openHours = bhp_generateOpeningHours($openHours, '<span class="weekdays">%s:</span>', '<span class="times">%s</span>');

		// Get the options
		// Styling
		$styling_colors_background_primary = esc_attr(get_option('styling_colors_background_primary', 'DimGrey'));
		$styling_colors_background_secondary = esc_attr(get_option('styling_colors_background_secondary', 'White'));
		$styling_colors_background_tertiary = esc_attr(get_option('styling_colors_background_tertiary', '#3C3C3B'));
		$styling_colors_text_primary = esc_attr(get_option('styling_colors_text_primary', 'DimGrey'));
		$styling_colors_text_secondary = esc_attr(get_option('styling_colors_text_secondary', 'DimGrey'));
		$styling_colors_text_tertiary = esc_attr(get_option('styling_colors_text_tertiary', 'White'));

		// Styling: Badges
		$styling_colors_text_badge_open = esc_attr(get_option('styling_colors_text_badge_open', 'White'));
		$styling_colors_text_badge_closed = esc_attr(get_option('styling_colors_text_badge_closed', 'White'));
		$styling_colors_background_badge_open = esc_attr(get_option('styling_colors_background_badge_open', 'Green'));
		$styling_colors_background_badge_closed = esc_attr(get_option('styling_colors_background_badge_closed', 'Red'));
		$styling_icon_header = esc_attr(get_option('styling_icon_header', 'fa fa-clock-o clock-icon'));

		// Business data
		$bh_title = esc_attr(get_option('general_title', 'Opening hours'));
		$business_name = esc_attr(get_option('business_name', ''));
		$business_contact_person = esc_attr(get_option('business_contact_person', ''));
		$business_phone_number = esc_attr(get_option('business_phone_number', ''));
		$business_fax_number = esc_attr(get_option('business_fax_number', ''));
		$business_email = esc_attr(get_option('business_email', ''));
		$business_website = esc_attr(get_option('business_website', ''));
		$business_address = esc_attr(get_option('business_address', ''));
		$business_address_postal_code = esc_attr(get_option('business_address_postal_code', ''));
		$business_city = esc_attr(get_option('business_city', ''));
		$business_style = esc_attr(get_option('business_style', ''));

		// Texts
		$general_open_hours_message = esc_attr(get_option('general_open_hours_message', ''));
		$general_closed_hours_message = esc_attr(get_option('general_closed_hours_message', ''));
		$general_open_closed_badges_open = esc_attr(get_option('general_open_closed_badges_open', ''));
		$general_open_closed_badges_closed = esc_attr(get_option('general_open_closed_badges_closed', ''));

		// Generate open status
		$isOpen = bhp_isOpen($general_business_hours, $saisonal_business_hours);
		if($isOpen) {
			$openMessage = $general_open_hours_message;
		} else {
			$openMessage = $general_closed_hours_message;
		}

		// Render the widget
		include sprintf(BUSINESS_HOURS_PLUGIN_BASE_PATH . '/templates/widget/style%u.php', $style);
	}

	/* Convert 0:00 to 24:00 if that wp_option is set.

	 * Note the assignments by reference - I know a lot of coders don't like this,
	 * but it makes quick work of the foreach loops modifying the original array
	 * directly for return.
	 */
    function fixtf($opname, $oddarray = null) {
		if(get_option('twentyfour') == 1){
			$searchtimes = get_option($opname);

			if(isset($oddarray)){
			    $worktimes =& $searchtimes[0];
            } else {
			    $worktimes =& $searchtimes;
            }

			if(!is_array($worktimes)) {
			    return null;
            }

            foreach ($worktimes as &$day) {
			    if(is_array($day)) {
				    foreach ( $day as &$fromto ) {
					    if ( $fromto['from'] == "0:00" ) {
						    $fromto['from'] = "24:00";
					    }
					    if ( $fromto['to'] == "0:00" ) {
						    $fromto['to'] = "24:00";
					    }
				    }
			    }
			}
		return $searchtimes;
		} else {
			return get_option($opname);
		}
	}

}

// register widget
add_action('widgets_init', function()
{
	register_widget("business_hours_widget");
});
