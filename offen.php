<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/*
Plugin Name: Open
Description: Display the company details and the opening hours at your website.
Version: 3.9
Author: OeffnungszeitenBuch.de
Author URI: https://www.oeffnungszeitenbuch.de/
Text Domain: offen
Domain Path: /lang
*/

# Define constants
define('BUSINESS_HOURS_PLUGIN_BASE_URL',   plugins_url('', __FILE__));
define('BUSINESS_HOURS_PLUGIN_BASE_PATH',  plugin_dir_path(__FILE__ ));
define('BUSINESS_HOURS_PLUGIN_LANG_PATH',  dirname(plugin_basename(__FILE__)) . "/lang");

# Include translations
load_plugin_textdomain("offen", false, BUSINESS_HOURS_PLUGIN_LANG_PATH);

# Include Helpers
require_once BUSINESS_HOURS_PLUGIN_BASE_PATH . '/Helpers/DayHelper.php';
require_once BUSINESS_HOURS_PLUGIN_BASE_PATH . '/Helpers/LanguageHelper.php';
require_once BUSINESS_HOURS_PLUGIN_BASE_PATH . '/Helpers/ArrayHelper.php';

# Create admin menu
require_once BUSINESS_HOURS_PLUGIN_BASE_PATH . '/Admin/AdminArea.php';

# Create the widget
require_once BUSINESS_HOURS_PLUGIN_BASE_PATH . '/Admin/Widget.php';

# Create the shortcode
require_once BUSINESS_HOURS_PLUGIN_BASE_PATH . '/Admin/Shortcode.php';

# Default values for settings after new installation
$gbhserial = 'a:7:{s:6:"monday";a:1:{i:0;a:2:{s:4:"from";s:4:"8:00";s:2:"to";s:5:"17:00";}}s:7:"tuesday";a:1:{i:0;a:2:{s:4:"from";s:4:"8:00";s:2:"to";s:5:"17:00";}}s:9:"wednesday";a:1:{i:0;a:2:{s:4:"from";s:4:"8:00";s:2:"to";s:5:"17:00";}}s:8:"thursday";a:1:{i:0;a:2:{s:4:"from";s:4:"8:00";s:2:"to";s:5:"17:00";}}s:6:"friday";a:1:{i:0;a:2:{s:4:"from";s:4:"8:00";s:2:"to";s:5:"17:00";}}s:8:"saturday";a:1:{i:0;a:2:{s:4:"from";s:5:"99:99";s:2:"to";s:5:"99:99";}}s:6:"sunday";a:1:{i:0;a:2:{s:4:"from";s:5:"99:99";s:2:"to";s:5:"99:99";}}}';
$gbharr = unserialize($gbhserial);

global $bh_options;
$bh_options = array(
	'general_title' => __("Opening hours", 'offen'),
	'general_open_hours_message' => __("Open", 'offen'),
	'general_closed_hours_message' => __("Closed", 'offen'),
	'general_open_closed_badges_open' => __("Open", 'offen'),
	'general_open_closed_badges_closed' => __("Closed", 'offen'),
	'general_open_24_text' => __("Open", 'offen'),
	'general_closed_all_day_text' => __("Closed", 'offen'),
	'general_business_hours' => $gbharr,
	'business_name' => __("Company", 'offen'),
	'styling_colors_background_primary' => '#3f90ff',
	'styling_colors_background_secondary' => '#dddddd',
	'styling_colors_background_tertiary' => '#f4f4f4',
	'styling_colors_text_primary' => 'black',
	'styling_colors_text_secondary' => 'black',
	'styling_colors_text_tertiary' => 'black',
	'styling_colors_text_badge_open' => 'black',
	'styling_colors_text_badge_closed' => 'black',
	'styling_colors_background_badge_open' => '#26a300',
	'styling_colors_background_badge_closed' => '#ff4747',
	'styling_icon_header' => 'fa fa-clock-o fa-5x'
);

function business_hours_activate()
{
	global $bh_options;
    
	// we ADD instead of update options so data is not overwritten on reinstall
	foreach($bh_options as $bh_op_name => $bh_op_value) {
		if(!get_option($bh_op_name)) {
			add_option($bh_op_name,$bh_op_value);
		}
	}

	// update adds option if not exists, so this always runs:
	update_option("bh_activated", date('d-m-y H:i'));

	// if we have previously uninstalled, remove that option...
	delete_option("bh_uninstalled");
}

function business_hours_uninstall()
{
	global $bh_options;

	foreach($bh_options as $bh_op_name => $bh_op_value) {
		delete_option($bh_op_name);
	}
	
	delete_option("bh_activated");
	
	if(!get_option("bh_uninstalled")) {
		add_option("bh_uninstalled",date('d-m-y H:i'));
	} else {
		update_option("bh_uninstalled",date('d-m-y H:i'));
	}
}

register_activation_hook(__FILE__, 'business_hours_activate');
register_uninstall_hook(__FILE__, 'business_hours_uninstall');
