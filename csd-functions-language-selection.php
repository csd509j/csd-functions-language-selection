<?php
/*
Plugin Name: CSD Functions - Language Preference Selection
Version: 1.3
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
 * Bot checker
 *
 * @since CSD Schools 3.5.6
 */
function is_bot() {
	
	$botlist = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi",
		"looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory",
		"Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot",
		"crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
		"msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz",
		"Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
		"Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","TweetmemeBot",
		"Butterfly","Twitturls","Me.dium","Twiceler","bot","Bot");
	
	foreach( $botlist as $bot ) {
	
		if( strpos( $_SERVER['HTTP_USER_AGENT'], $bot ) !== false ) {
	
			return true;
		}
	}
	
	return false;
}
/**
 * Language toggle button
 *
 * @since CSD Schools 1.4.5
 */
function languages_toggle() {
	global $wp;
	$current_url = home_url('/');
	$url = $wp->request;
  	
  	$theme = wp_get_theme();
  	
  	if ( $theme->name == 'Lincoln Elementary School' || $theme->name == 'Garfield Elementary School' && !isBot() ) {
	  	
	  	if (function_exists('icl_object_id')) {
			
			$languages = icl_get_languages('skip_missing=1');
		
		}

	  	?>
	  	
	  	<div id="language-toggle" class="bg-primary">
		  	<div id="language-toggle-container">
			  	
			  	<?php if ( $languages['en']['active'] ): ?>
			  		
			  		<a class="btn btn-light disabled" aria-disabled="true" data-lang="<?php echo $languages['en']['code']; ?>" href="<?php echo $languages['en']['url']; ?>"><?php echo $languages['en']['translated_name']; ?></a>

			  	<?php else: ?>
				  	
				  <a class="btn btn-primary" data-lang="<?php echo $languages['en']['code']; ?>" href="<?php echo $languages['en']['url']; ?>"><?php echo $languages['en']['translated_name']; ?></a>
				  	
				<?php endif; ?>	
				
				<?php if ( $languages['es']['active'] ): ?>
				
		  			<a class="btn btn-light disabled" aria-disabled="true" data-lang="<?php echo $languages['es']['code']; ?>" href="<?php echo $languages['es']['url']; ?>"><?php echo $languages['es']['translated_name']; ?></a>
				
				<?php else: ?>
				
		  			<a class="btn btn-primary" data-lang="<?php echo $languages['es']['code']; ?>" href="<?php echo $languages['es']['url']; ?>">Español</a>
		  	
		  		<?php endif; ?>
		  		
		  	</div>
	  	</div>
	  	
	  	<?php
	  	
  	} else {
	  	
	  	if ( function_exists('icl_object_id') ) {
			
			$languages = icl_get_languages('skip_missing=1');
	  	
	  	} else {
		  	
		  	$languages = array();
	  	
	  	}
	  	
	  	$google_languages = array(
		  	'googtrans(en|es)' => 'Spanish',
		  	'googtrans(en|ar)' => 'ترجمه',
		  	'googtrans(en|zh-CN)' => 'Chinese',
		  	'googtrans(en|fr)' => 'French',
		  	'googtrans(en|de)' => 'German',
		  	'googtrans(en|ko)' => 'Korean',
		  	'googtrans(en|vi)' => 'Vietnamese'
	  	);
	  	
		if( 1 < count($languages) ){
			
			foreach( $languages as $l ) {
				
				if( $l['active'] ) {
				
					$active = $l['native_name'];
				
				}
			
			}
		} else {
			
			if( strpos($url, "#") === false ) {
				
				$active = "English";
			
			} else {
				
				$key = explode("#", $url)[0];
				$active = $google_languages[$key];
			
			}	
		}
		
		?>
	  	
	  	<div class="translated-btn">
			<div class="dropdown">
				<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
					<i class="fa fa-comment"></i> Translate <span class="caret"></span>
				</button>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
				<?php if(1 < count($languages)): ?>
					<?php foreach($languages as $l): ?>
						<?php if(!$l['active']): ?>
							<li><a data-lang="<?php echo $l['code']; ?>" href="<?php echo $l['url']; ?>"><?php echo $l['translated_name']; ?></a></li>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				
				<?php foreach($google_languages as $key => $val): ?>
					<?php if ($current_url == 'https://linuspauling.csd509j.net/' || $current_url == 'https://linuspauling.csd509j.net/es/'): ?>
						<?php if($val != 'Spanish'): ?>
							<li><a href="<?php echo home_url(); ?>/#<?php echo $key; ?>" target="_blank"><?php echo $val; ?></a></li>
						<?php endif; ?>
					<?php else: ?>
						<li><a href="<?php echo home_url(); ?>/#<?php echo $key; ?>" target="_blank"><?php echo $val; ?></a></li>
					<?php endif; ?>
				<?php endforeach; ?>
				</ul>
			</div>
		</div>
		
	<?php
	}
}

/**
 * Check language cookie
 *
 * @since CSD Schools 1.4.5
 */
function csd_check_default_language() {
	
	$theme = wp_get_theme();
	$themes = array('Linus Pauling Middle School', 'Garfield Elementary School');
	
	if ( !is_admin() && in_array($theme->name, $themes) ) {
		
		global $sitepress;
	    global $wp;
	    
	    $current_lang = $sitepress->get_current_language(); //set the default language code
	    $correct_lang = 'en'; // Start with default language
	    $supported_lang = array('en', 'es'); //set the allowed language codes 
	
	    $langtemp = @$_COOKIE['csd_translation_preference'];
		
		if ( !$langtemp && !is_page('select-language') ) {
			
			//Redirect 
            $referrer = home_url($_SERVER['REQUEST_URI']);
            $url = home_url('/select-language') . '?redirect=' . $referrer;
            $url = str_replace('/es/', '/', $url);
			wp_redirect($url);        
			exit;
			
		} elseif ( !is_page('select-language') ) {
		    
		    if ( in_array($langtemp, $supported_lang) ) {
			   
			    // If cookie is set and contains valid value, update correct language
				$correct_lang = $langtemp;
			
			}
		
		    if ( $current_lang != $correct_lang ) {
			   
			    // If current viewed language is not preferred, change to preferred.
		    	$sitepress->switch_lang($correct_lang, true);
		
				if ( $correct_lang == 'en' ) {
					
					// Handle when viewing spanish url with English set as preferred language.
					$url = home_url($_SERVER['REQUEST_URI']);
					$url = str_replace('/es/', '/', $url);
					wp_redirect($url);
					exit;
				
				} elseif ( $correct_lang == 'es' ) {
				
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
add_action( 'wp', 'csd_check_default_language' );