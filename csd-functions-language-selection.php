<?php
/*
Plugin Name: CSD Functions - Language Preference Selection
Version: 1.0
Description: Store language preference
Author: Josh Armentano
Author URI: https://abidewebdesign.com
Plugin URI: https://abidewebdesign.com
*/
require WP_CONTENT_DIR . '/plugins/plugin-update-checker-master/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/csd509j/CSD-functions-language-selection',
	__FILE__,
	'CSD-functions-language-selection'
);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check language cookie
 *
 * @since CSD Schools 1.4.5
 */
function csd_check_default_language() {
	if(!is_admin()) {
		global $sitepress;
	    global $wp;
	    
	    $current_lang = $sitepress->get_current_language(); //set the default language code
	    $correct_lang = 'en'; // Start with default language
	    $supported_lang = array('en', 'es'); //set the allowed language codes 
	
	    $langtemp = @$_COOKIE['csd_translation_preference'];
		
		if (!$langtemp && !is_page('select-language')) {
			//Redirect 
            $referrer = home_url($_SERVER['REQUEST_URI']);
            $url = home_url('/select-language') . '?redirect=' . $referrer;
            $url = str_replace('/es/', '/', $url);
			wp_redirect($url);        
			exit;
		} elseif (!is_page('select-language')) {
		    if (in_array($langtemp, $supported_lang)) {
			    // If cookie is set and contains valid value, update correct language
				$correct_lang = $langtemp;
			}
		
		    if ( $current_lang != $correct_lang ) {
			    // If current viewed language is not preferred, change to preferred.
		    	$sitepress->switch_lang($correct_lang, true);
		
				if ($correct_lang == 'en') {
					// Handle when viewing spanish url with English set as preferred language.
					$url = home_url($_SERVER['REQUEST_URI']);
					$url = str_replace('/es/', '/', $url);
					wp_redirect($url);
					exit;
				} elseif ($correct_lang == 'es') {
					$url = home_url($_SERVER['REQUEST_URI']);
					$new_url = home_url() . '/es';
					$url = str_replace(home_url(), $new_url, $url);
					wp_redirect($url);
					exit;
				}
		    }
		}
	}
}
add_action('wp', 'csd_check_default_language');