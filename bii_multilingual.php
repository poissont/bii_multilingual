<?php
/*
  Plugin Name: bii_multilingual
  Description: Ajoute des fonctions multilingues
  Version: 0.1
  Author: Biilink Agency
  Author URI: http://biilink.com/
  License: GPL2
 */
define('bii_multilingual_version', '0.1');
define('bii_multilingual_path', plugin_dir_path(__FILE__));
define('bii_multilingual_url', plugin_dir_url(__FILE__));
//logQueryVars();

function bii_add_multilingual_option_title() {
	?>
	<li role="presentation" class="hide-relative hide-publier" data-relative="pl-Lang"><i class="fa fa-language"></i> Langues</li>

	<?php
}

function bii_add_multilingual_options() {
	?>
	<div class="col-xxs-12 pl-Lang bii_option">
		
	</div>
	<?php
}
function bii_multilingual_enqueueJS() {
	wp_enqueue_script('bii_multilingual', bii_multilingual_url . "js/multilingual.js", array('jquery', 'util'), false, true);
}

function bii_multilingual_additionnal_js_var(){
	global $wp_query;
	echo "var bii_lang = '".$wp_query->query_vars["lang"]."';";	
}

add_action("bii_additionnal_js_var","bii_multilingual_additionnal_js_var");
add_action('wp_enqueue_scripts', "bii_multilingual_enqueueJS");
add_action("bii_options_title", "bii_add_multilingual_option_title", 10);
add_action("bii_options", "bii_add_multilingual_options");
