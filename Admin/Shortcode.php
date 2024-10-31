<?php

if ( ! defined( 'ABSPATH' ) ) exit;

add_shortcode('business-hours', 'business_hours_from_widget');

function business_hours_from_widget($args) {
	ob_start();
	$business_hours_widget = new business_hours_widget();
	$business_hours_widget->widget($args, []);
	return ob_get_clean();
}