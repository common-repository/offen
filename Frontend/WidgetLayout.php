<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function bhp_getWidgetLayout($style)
{
	include_once (sprintf(BUSINESS_HOURS_PLUGIN_BASE_PATH . '/templates/widget/style%u.php', $style));
}