<div class="wrap">

<div class="cf7utm-card">
	<img src="<?php echo CF7_UTM_Tracking::$PLUGIN_URL; ?>/cf7utm-logo.png" alt="Contact Form 7 UTM tracking Logo" width="60" height="60" class="icon">
	<h3 class="title">
		Contact Form 7 UTM tracking (version <?php echo CF7_UTM_Tracking::$VERSION ?>)
	</h3>

	<br class="clear">
	<div class="inside">
	<p>After activating plugin it will instantly start work and save visitors first page Referrer (called "Landing" page) and other data.</p>
	<p>To add this info into the Admin emails, <a href="https://res.cloudinary.com/dxo61viuo/image/upload/v1559999389/wp-vote.net/CF7_utm_mail_tag.jpg" target="_blank">put here a tag</a> <code>[utm_and_referer]</code></p>
	<p>On each page visit he checks user Cookies, and store referrer for new visitors and UTM tags, if they exists in URL (else save "utm_source" as "(none)").</p>
	<p>If user already have "__utmz" cookies, that sets Google Analytic classic, then script copies data from it.</p>

	<p>Here you can find more details and create URL with UTM tags: <a target="_blank" href="https://support.google.com/analytics/answer/1033867?hl=en">https://support.google.com/analytics/answer/1033867?hl=en</a>.</p>
	
		<h3>
			Debug info:
		</h3>
		<p>
			<strong>Last submitted message date (WP time):</strong> <?php echo CF7_UTM_Tracking::get_opt('last_sent', '-'); ?>
		</p>
		<p>
			<strong>Last submitted message UTM tags:</strong> <?php echo esc_html(CF7_UTM_Tracking::get_opt('last_utm', '-')); ?>
		</p>
		<p>
			<strong>Last submitted message <a target="_blank" href="http://www.ppchero.com/what-the-gclid/">gclid</a>:</strong> <?php echo esc_html(CF7_UTM_Tracking::get_opt('last_gclid', '-')); ?>
		</p>
		<p> 
			<strong>Last submitted message Referrer:</strong> <?php echo esc_url(CF7_UTM_Tracking::get_opt('last_referrer', '-')); ?>
		</p>
		<p>
			<strong>Last submitted message Landing:</strong> <?php echo esc_attr(CF7_UTM_Tracking::get_opt('last_landing', '-')); ?>
		</p>
		<p>
			<strong>Note:</strong> After the plugin deactivating, this info will be deleted.
		</p>		
	</div>
</div>

<style>
	.cf7utm-card {
		background: #fff none repeat scroll 0 0;
		border: 1px solid #e5e5e5;
		border-left: 4px solid #e5e5e5;
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
		margin-top: 20px;
		width: auto;
		padding: 0.7em 2em 1em;
		position: relative;
	}
	.cf7utm-card h3.title {
		float: left;
		line-height: 73px;
		margin: 0;
	}
	.cf7utm-card img.icon {
		float: left;
		margin: 8px 16px 8px -8px;
	}	
</style>
</div>