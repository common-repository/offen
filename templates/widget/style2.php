<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="bh-page">
	<div class="widget_item strappy">
		<div class="widget_3">
			
		
			
			
			<div class="container-fluid">
				<div class="widget_header" style="background-color: <?php echo $styling_colors_background_primary; ?>; color: <?php echo $styling_colors_text_primary; ?>;">
				
				<?php if($bh_title): ?>
				<div class="col-sm-12 notop">
					<p class= "bhtitle" style="color:<?php echo $styling_colors_text_primary; ?>"><?php echo $bh_title; ?></p>
				</div>
				<?php endif; ?>	

					<div class="row">
						<div class="col-md-4 col-sm-2 col-xs-12">
							<div class="header_icon">
								<div class="width_35_per">
									<i class="<?php echo $styling_icon_header; ?>" aria-hidden="true"></i>
								</div>
							</div>
						</div>
						
						<div class="col-md-8 col-sm-8 col-xs-12">
							<div class="header_working_schedule pad_lf_rgh_none">
								<p class="today">
									<span class="text-uppercase"><?php _e("Currently", 'offen'); ?></span>

									<?php
										if(isset($isOpen) && $isOpen) {
											echo sprintf('<span class="badge" style="background-color: %s; color: %s">%s</span>', $styling_colors_background_badge_open, $styling_colors_text_badge_open, $general_open_closed_badges_open);
										} else {
											echo sprintf('<span class="badge" style="background-color: %s; color: %s">%s</span>', $styling_colors_background_badge_closed, $styling_colors_text_badge_closed, $general_open_closed_badges_closed);
										}
									?>
								</p>
								<div class="working_hours working_days">
									<?php echo $openHours; ?>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="container-fluid">
				<div class="widget_body clear_fix" style="background-color: <?php echo $styling_colors_background_secondary; ?>; color: <?php echo $styling_colors_text_secondary; ?>;">
					<div class="col-6">
						<div class="company_name margin_bt_25">
							<p class="text-uppercase margin_none font_bold font_size_18" style="color: <?php echo $styling_colors_text_secondary; ?>;">
								<?php if(isset($business_name) && !empty($business_name)) echo $business_name ?>
							</p>

							<p class="text-capitalize margin_none">
								<?php if(isset($business_contact_person) && !empty($business_contact_person)) echo $business_contact_person ?>
							</p>
						</div>
						<div class="company_details">
							<?php if(isset($business_phone_number) && !empty($business_phone_number)) echo sprintf('<p class="phone"><span>%s: </span><span>%s</span></p>', __('Telephone', 'offen'), $business_phone_number); ?>

							<?php if(isset($business_fax_number) && !empty($business_fax_number)) echo sprintf('<p class="fax margin_none"><span class="fax_first_child">%s:</span><span>%s</span></p>', __('Fax', 'offen'), $business_fax_number); ?>

						</div>
					</div>
					<div class="col-6">
						<div class="company_details margin_bt_25">
							<?php if(isset($business_email) && !empty($business_email)) {
								echo sprintf('<p class="email"><span>%s</span></p>', $business_email);
							} ?>

							<?php if(isset($business_website) && !empty($business_website)) {
								echo sprintf('<p class="website margin_bt_4"><span>%s</span></p>', $business_website);
							} ?>

						</div>


						<div class="company_details">
							<?php if(isset($business_address) && !empty($business_address)) {
								echo sprintf('<p class="address margin_none"><span>%s</span><br><span>%s %s</span>', $business_address, $business_address_postal_code, $business_city);
							} ?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="container-fluid">
				<div class="text-center widget_footer" style="background-color: <?php echo $styling_colors_background_tertiary; ?>; color: <?php echo $styling_colors_text_tertiary ?>;">
					<p class="margin_none force-center">
						<?php if(isset($openMessage) && !empty($openMessage)) echo $openMessage ?>
					</p>
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
</div>