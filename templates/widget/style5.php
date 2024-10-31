<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// Get the german days
$germanDays = bhp_getWeekdayTranslated();

$currentDay = date('d');
$currentDayName = $germanDays[strtolower(date('l'))];

$minOpenHours = minimizeOpenHours($openHours);

?>
<style>
    /* minor style correction for live preview in admin,
        does not affect in-page layout.
     */
    .strappy .row{
        margin:0px;
    }
</style>


<div class="minimal strappy">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 minwidgettitle">
                <h2 style="color:<?php echo $styling_colors_text_primary; ?>"><?php echo $bh_title; ?></h2>
            </div>
        </div>
        <div id="mincontent" style="color:<?php echo $styling_colors_text_primary; ?>">
            <?php echo $minOpenHours; ?>
        </div>

        <div class="row">
            <div class="col-xs-12">
	            <p class="gimmeroom">
                <?php
	            if(isset($isOpen) && $isOpen) {
		            echo sprintf('<span class="minbadge" style="color: %s">%s</span>', $styling_colors_text_badge_open, $general_open_closed_badges_open);
	            } else {
		            echo sprintf('<span class="minbadge" style="color: %s">%s</span>', $styling_colors_text_badge_closed, $general_open_closed_badges_closed);
	            }
	            ?>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div style="color:<?php echo $styling_colors_text_secondary; ?>">
                    <h2>
			            <?php if(isset($business_name) && !empty($business_name)) echo $business_name ?>
                    </h2>
                    <p class="text-capitalize">
			            <?php if(isset($business_contact_person) && !empty($business_contact_person)) echo $business_contact_person ?>
                    </p>
                </div>
                <div class="company_details" style="color:<?php echo $styling_colors_text_secondary; ?>">
		            <?php if(isset($business_phone_number) && !empty($business_phone_number)) echo sprintf('<p class="phone"><span>%s: </span><span>%s</span></p>', __('Telephone', 'offen'), $business_phone_number); ?>

		            <?php if(isset($business_fax_number) && !empty($business_fax_number)) echo sprintf('<p class="fax"><span class="fax_first_child">%s:</span><span>%s</span></p>', __('Fax', 'offen'), $business_fax_number); ?>

		            <?php if(isset($business_email) && !empty($business_email)) echo sprintf('<p class="email"><span>%s: %s</span></p>', __('E-Mail', 'offen'), $business_email); ?>

		            <?php if(isset($business_website) && !empty($business_website)) echo sprintf('<p class="website"><span>%s</span></p>', $business_website); ?>

		            <?php if(isset($business_address) && !empty($business_address)) echo sprintf('<p class="address"><span>%s</span><br><span>%s %s</span>', $business_address, $business_address_postal_code, $business_city); ?>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-7">
                <!-- empty -->
            </div>
            <div class="col-xs-5">
                <p class="powerdby"><?php echo _e('Powered by crosswordsolver.com', 'offen') ?></p>
            </div>
        </div>

    </div>
</div>