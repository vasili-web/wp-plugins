<?php
/*
Plugin Name: Cookies baner
Description: Cookies baner powered by TermsFeed
Version: 1.0
Author: kapitanweb.pl
*/
function enqueue_cookies_baner_styles() {
    wp_enqueue_style('cookies-baner', plugin_dir_url(__FILE__) . 'css/cookies-baner.css');
}
add_action('wp_enqueue_scripts', 'enqueue_cookies_baner_styles');



function insert_cookies_baner() { 
$locale = substr( get_bloginfo ( 'language' ), 0, 2 );
?>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){
	dataLayer.push(arguments);
}
gtag('consent', 'default', {
	'ad_storage': 'denied',
	'ad_user_data': 'denied',
	'ad_personalization': 'denied',
	'analytics_storage': 'denied'
});
</script>
<!-- Cookie Consent by TermsFeed https://www.TermsFeed.com -->
<script type="text/javascript" src="//www.termsfeed.com/public/cookie-consent/4.2.0/cookie-consent.js" charset="UTF-8"></script>
<script type="text/javascript" charset="UTF-8">
document.addEventListener('DOMContentLoaded', function () {
cookieconsent.run({"notice_banner_type":"simple","consent_type":"express","palette":"dark","language":"<?php echo $locale; ?>","page_load_consent_levels":["strictly-necessary"],"notice_banner_reject_button_hide":false,"preferences_center_close_button_hide":false,"page_refresh_confirmation_buttons":false,"callbacks": {
	"scripts_specific_loaded": (level) => {
		switch(level) {
			case 'targeting':
				gtag('consent', 'update', {
					'ad_storage': 'granted',
					'ad_user_data': 'granted',
					'ad_personalization': 'granted',
					'analytics_storage': 'granted'
				});
				break;
		}
	}
},
"callbacks_force": true});
});
</script>
<!-- End Cookie Consent by TermsFeed https://www.TermsFeed.com -->
<span id="open_preferences_center"></span>
<?php } add_action('wp_footer', 'insert_cookies_baner',99);?>