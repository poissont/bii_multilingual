<?php
/*
  Plugin Name: bii_multilingual
  Description: Ajoute des fonctions multilingues
  Version: 0.5
  Author: Biilink Agency
  Author URI: http://biilink.com/
  License: GPL2
 */
define('bii_multilingual_version', '0.5');
define('bii_multilingual_path', plugin_dir_path(__FILE__));
define('bii_multilingual_url', plugin_dir_url(__FILE__));

//logQueryVars();

function bii_multilingual_available_languages() {
	$langs = [
		"fr" => "Français",
		"en" => "Anglais",
		"es" => "Espagnol",
		"pt" => "Portugais",
		"ru" => "Russe",
		"de" => "Allemand",
		"nl" => "Néerlandais",
	];
	return $langs;
}

function bii_multilingual_default_language_selection_admin_script($text) {
	ob_start();
	?>
	<script>
		jQuery(function ($) {
			//			$("#bii_multilingual_languages").hide(0);
			$(".bii_select_languages").on("click", function () {
				var value = $("#bii_multilingual_languages").val();
				var exp = value.split(",");
				var newtab = [];
				var valuetoadd = $(this).attr("data-value");
				if (value.indexOf(valuetoadd) == -1) {

					exp.push(valuetoadd);
					newtab = exp;
					$(this).find(".fa").addClass("fa-check-square-o").removeClass("fa-square-o");
				} else {
					$.each(exp, function (index, v) {
						if (v != valuetoadd) {
							newtab.push(v);
						}
					});
					$(this).find(".fa").removeClass("fa-check-square-o").addClass("fa-square-o");
				}
				bii_CL(newtab);
				var newval = newtab.join();
				$("#bii_multilingual_languages").val(newval);
			});
		});
	</script>
	<?php
	$contents = ob_get_contents();
	ob_end_clean();
	return $contents;
}

function bii_multilingual_default_language_admin_selection() {
	$langs = bii_multilingual_available_languages();
	$ret = "<span class='bii_select_languages_wrapper '>";
	foreach ($langs as $lang => $libelle) {
		$selected = "<span class='fa fa-square-o'></span>";
		if (strpos(get_option("bii_multilingual_languages"), $lang) !== false) {
			$selected = "<span class='fa fa-check-square-o'></span>";
		}
		$ret .= "<span class='bii_select_languages ' data-value='$lang'>$selected <img src='" . bii_multilingual_url . "flags/$lang.png' /> $libelle</span>";
	}
	$ret.="</span>";
	$ret .= apply_filters("bii_multilingual_default_language_selection_admin_script", $ret);
	return $ret;
}

add_filter("bii_multilingual_default_language_selection_admin_script", "bii_multilingual_default_language_selection_admin_script");
add_filter("bii_multilingual_default_language_admin_selection", "bii_multilingual_default_language_admin_selection");

function bii_add_multilingual_option_title() {
	?>
	<li role="presentation" class="hide-relative" data-relative="pl-Lang"><i class="fa fa-language"></i> Langues</li>
	<?php
}

function bii_add_multilingual_options() {
	?>
	<div class="col-xxs-12 pl-Lang bii_option hidden">
		<?= bii_makestuffbox("bii_multilingual_languages", "Langues", "text", "col-xxs-12", [], "hidden", apply_filters("bii_multilingual_default_language_admin_selection", null)); ?>
		<?php
		$langs = maybe_unserialize(explode(",", get_option("bii_multilingual_languages")));
//		pre($langs);
		$dl = bii_multilingual_available_languages();
		foreach ($langs as $lang) {
			if (!get_option("bii_multilingual_$lang" . "_name")) {
				update_option("bii_multilingual_$lang" . "_name", $dl[$lang]);
			}
			if (!get_option("bii_multilingual_$lang" . "_flag")) {
				update_option("bii_multilingual_$lang" . "_flag", bii_multilingual_url . "flags/$lang" . ".png");
			}
			bii_makestuffbox("bii_multilingual_$lang" . "_name", "Nom pour $lang", "text", "col-xxs-12 col-xs-6 col-sm-3");
			bii_makestuffbox("bii_multilingual_$lang" . "_flag", "Drapeau pour $lang", "text", "col-xxs-12 col-xs-6 col-sm-3");
		}
		?>
	</div>
	<?php
}

function bii_multilingual_enqueueJS() {
	wp_enqueue_script('bii_multilingual', bii_multilingual_url . "js/multilingual.js", array('jquery', 'util'), false, true);
}

function bii_multilingual_css_front() {
	wp_enqueue_style('bii_multilingual', bii_multilingual_url . '/css/bii_multilingual.css');
}

function bii_multilingual_additionnal_js_var() {
	global $wp_query;
	if (isset($wp_query->query_vars["lang"])) {
		echo "bii_lang = '" . $wp_query->query_vars["lang"] . "';";
	}
	if (isset($_REQUEST["lang"])) {
		echo "bii_lang = '" . $_REQUEST["lang"] . "';";
	}
	echo "bii_multilingual_activated = true;";
//	
//	echo "</script>";
//	
//	consoleLog(get_the_ID());
//	echo "<script>";
}

function bii_multilingual_display_flag($lang) {
	if (!get_option("bii_multilingual_$lang" . "_name")) {
		$dl = bii_multilingual_available_languages();
		update_option("bii_multilingual_$lang" . "_name", $dl[$lang]);
	}
	if (!get_option("bii_multilingual_$lang" . "_flag")) {
		update_option("bii_multilingual_$lang" . "_flag", bii_multilingual_url . "flags/$lang" . ".png");
	}
	$flag = get_option("bii_multilingual_$lang" . "_flag");
	$alt = get_option("bii_multilingual_$lang" . "_name");
	$url = apply_filters("bii_multilingual_real_baseurl", get_bloginfo("url"), $lang);
	$contents = "<span class='bii-select-flag'>"
		. "<a href='$url'>"
		. "<img width='18' height='12' class='lazyquick' alt='$alt' src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' data-original='$flag' />"
		. "</a>"
		. "</span>";
	return $contents;
}

function bii_multilingual_select_flags($val) {
	$langs = maybe_unserialize(explode(",", get_option("bii_multilingual_languages")));
	$output = "<div class='bii-front-select-language'>";
	foreach ($langs as $lang) {
		$output .= apply_filters("bii_multilingual_display_flag", $lang);
	}
	$output .= "</div>";
	return $output;
}

function bii_multilingual_real_baseurl($url, $lang) {
	$url = explode("?", $url);
	$nopoint = $url[0];
//	$id = get_the_ID();
//	$id_translation = icl_translations::get_translation_of($id,$lang);
//	bii_write_log("[id_translation] ". $id_translation);
//	if($id && $id_translation ){
//		return get_post($id_translation)->guid;
//	}
	return $nopoint . "/?lang=$lang";
}

add_filter("bii_multilingual_real_baseurl", "bii_multilingual_real_baseurl", 10, 2);
add_filter("bii_multilingual_display_flag", "bii_multilingual_display_flag");
add_filter("bii_multilingual_select_flags", "bii_multilingual_select_flags");

function bii_multilingual_option_submit() {
	$tableaucheck = ["bii_multilingual_languages"];
	foreach ($tableaucheck as $itemtocheck) {
		if (isset($_POST[$itemtocheck])) {
			update_option($itemtocheck, $_POST[$itemtocheck]);
		}
	}

	update_option("bii_multilingual_languages_serialized", serialize(explode(",", $_POST["bii_multilingual_languages"])));
	$langs = maybe_unserialize(explode(",", get_option("bii_multilingual_languages")));
	foreach ($langs as $lang) {
		update_option("bii_multilingual_$lang" . "_name", $_POST["bii_multilingual_$lang" . "_name"]);
		update_option("bii_multilingual_$lang" . "_flag", $_POST["bii_multilingual_$lang" . "_flag"]);
	}
}

function bii_multilingual_body_class($classes, $class) {
	$id = get_the_ID();
	$id_base_trad = icl_translations::get_trad_base_of($id);
	$classes[] = "bii-traduit-de-" . $id_base_trad;
	return $classes;
}

add_action("bii_options_submit", "bii_multilingual_option_submit", 5);

add_action("bii_additionnal_js_var", "bii_multilingual_additionnal_js_var");
add_action('wp_enqueue_scripts', "bii_multilingual_enqueueJS");
add_action('wp_enqueue_scripts', "bii_multilingual_css_front");
add_action("bii_options_title", "bii_add_multilingual_option_title", 10);
add_action("bii_options", "bii_add_multilingual_options");

add_filter("body_class", "bii_multilingual_body_class");

function bii_multilingual_current_language() {
	$lang = 'fr';
	global $wp_query;
	if (isset($wp_query->query_vars["lang"])) {
		$lang = $wp_query->query_vars["lang"];
	}
	if (isset($_REQUEST["langchanged"])) {
		$_REQUEST["lang"] = $_REQUEST["langchanged"];
	}
	if (isset($_REQUEST["lang"])) {
		$lang = $_REQUEST["lang"];
	}
	return $lang;
}

function bii_multilingual_add_translation($search_term, $replace_term, &$bii_search, &$bii_replace) {
	$bii_search[] = $search_term;
	$bii_replace[] = $replace_term;
}

function bii_multilingual_adaptative_text($attrs, $content) {
	$lang = bii_multilingual_current_language();
	if (isset($attrs["lang"])) {
		$lang = $attrs["lang"];
	}
	return bii_multilingual_more_translation(__($content));
}

function bii_multilingual_list_shortcodes($attrs, $content) {
	?><tr>
		<td><strong>[bii_autotranslate lang="<?= bii_multilingual_current_language(); ?>"]</strong></td>
		<td>Appelle la fonction de traduction du texte dans la lang choisie, par défaut lang correspond à la langue en cours du site</td>
	</tr><?php
}

function bii_multilingual_include_classes() {
	require_once(bii_multilingual_path . "class/icl_translations.class.php");
	if (strpos(get_permalink(), "/user-information/")) {
		$_REQUEST["lang"] = "en";
	}
}

add_action("bii_after_include_class", "bii_multilingual_include_classes", 11);

add_shortcode("bii_autotranslate", "bii_multilingual_adaptative_text");

add_action("bii_specific_shortcodes", "bii_multilingual_list_shortcodes");

function bii_multilingual_more_translation($text) {
	$bii_search = [];
	$bii_replace = [];
	$lang = bii_multilingual_current_language();
	if ($lang == "fr") {


		include(bii_multilingual_path . "/translations/fr.php");
	}
	if ($lang == "en") {
		bii_multilingual_add_translation("Rechercher", "Search", $bii_search, $bii_replace);
		bii_multilingual_add_translation("Votre Recherche", "Search", $bii_search, $bii_replace);

		bii_multilingual_add_translation("Abonnés", "Followers", $bii_search, $bii_replace);
		bii_multilingual_add_translation("Catégories", "Categories", $bii_search, $bii_replace);

		bii_multilingual_add_translation("Activité", "Activity", $bii_search, $bii_replace);

		bii_multilingual_add_translation("Fil d’actualités", "Activity", $bii_search, $bii_replace);
		bii_multilingual_add_translation("Membres", "Members", $bii_search, $bii_replace);
		bii_multilingual_add_translation("Mon Profil", "My Account", $bii_search, $bii_replace);
		bii_multilingual_add_translation("Ville", "City", $bii_search, $bii_replace);
		bii_multilingual_add_translation("Code postal", "Zip code", $bii_search, $bii_replace);
		bii_multilingual_add_translation("Pays", "Country", $bii_search, $bii_replace);
		bii_multilingual_add_translation("Nom", "Last Name", $bii_search, $bii_replace);
		bii_multilingual_add_translation("Prénom", "Surname", $bii_search, $bii_replace);
		bii_multilingual_add_translation("Adresse", "Address", $bii_search, $bii_replace);
		bii_multilingual_add_translation("Date d'inscription", "Registration date", $bii_search, $bii_replace);
		bii_multilingual_add_translation("Statut en ligne", "Online status", $bii_search, $bii_replace);
	}
//	pre($bii_search);
//	pre($bii_replace);
	return str_replace($bii_search, $bii_replace, $text);
}

add_action("gettext", "bii_multilingual_more_translation");



add_filter('wp_nav_menu_items', 'bii_nav_menu_items', 10, 2);

function bii_nav_menu_items($items, $args) {
//	logQueryVars();
	// uncomment this to find your theme's menu location
	//echo "args:<pre>"; print_r($args); echo "</pre>";
	// get languages
	$languages = apply_filters('wpml_active_languages', NULL, 'skip_missing=0');

	// add $args->theme_location == 'primary-menu' in the conditional if we want to specify the menu location.

	if ($languages && $args->theme_location == 'primary') {

		if (!empty($languages)) {

			foreach ($languages as $l) {
				if (!$l['active']) {
					// flag with native name
					$items = $items . '<li class="menu-item"><a href="' . apply_filters("bii_multilingual_link_selector_translation",$l['url'],$l['language_code'] ) . '"><img data-original="' . $l['country_flag_url'] . '" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" height="12" alt="' . $l['language_code'] . '" width="18" class="lazyquick" /><span class="hidden-lg hidden-md"> ' . $l['native_name'] . '</span></a></li>';
					//only flag
					//$items = $items . '<li class="menu-item menu-item-language"><a href="' . $l['url'] . '"><img src="' . $l['country_flag_url'] . '" height="12" alt="' . $l['language_code'] . '" width="18" /></a></li>';
				}
			}
		}
	}

	return $items;
}

function bii_multilingual_widget_titles($title) {
//	$lang = bii_multilingual_current_language();
	$return = __($title);
	return $return;
}

add_filter('widget_title', "bii_multilingual_widget_titles");

function bii_multilingual_link_traduction($link) {
	$post_id = url_to_postid($link);
	$trad_id = icl_object_id($post_id);
	return get_permalink($trad_id);
}

add_filter('bii_trad_link', "bii_multilingual_link_traduction");

function bii_multilingual_filter_um_localize_permalink($url) {
	$lang = bii_multilingual_current_language();
	$url = str_replace("?lang=$lang", "", $url);

	if ($lang != "fr") {
		$url .= "?langchanged=$lang";
	}
	return $url;
}

add_filter('bii_multilingual_filter_um_localize_permalink', "bii_multilingual_filter_um_localize_permalink", 10, 3);

function bii_um_multilingual_fix_menu($nav_link) {
	$lang = bii_multilingual_current_language();
	$url = str_replace("?lang=$lang", "", $nav_link);

	if ($lang != "fr") {
		$url .= "&langchanged=$lang";
	}
	return $url;
}

function bii_um_multilingual_tabs($tabs) {
//	pre($tabs);
	$lang = bii_multilingual_current_language();
	
	if ($lang != "fr") {
		foreach ($tabs as $tab) {
			$tab["name"] = __($tab["name"]);
		}
	}
	return $tabs;
}

function bii_um_multilingual_add_rewrite_rules($aRules) {
	$aNewRules = array("^voir-un-utilisateur/([a-zA-Z0-9]*)/?lang=en" => "http://wonderwomenworld.com/user-information/([a-zA-Z0-9]*)/?lang=en");
	$aRules = $aNewRules + $aRules;
}

function bii_multilingual_filter_date_i18n($j, $req_format, $i, $gmt) {
	$lang = bii_multilingual_current_language();
	if ($lang == "fr") {
		if ($req_format == "F d, Y") {
			$req_format = "d/m/Y";
		}
	}

	return date($req_format, $i);
}

function bii_um_multilingual_label_title($label){
	
	return __($label);
}

function bii_multilingual_link_selector_translation($url,$lang){
	global $wp_query;
	if ($lang == "fr") {
		$url = str_replace("?langchanged=en", "", $url);
		$url = str_replace("?lang=en", "", $url);
	}
	
	if ($lang == "en") {
		if(strpos($url,"voir-un-utilisateur" ) !== false){
			$permalink = get_permalink();
			$user = "";
			if(isset($wp_query->query_vars["um_user"])){
				$user = $wp_query->query_vars["um_user"]."/";
			}
			$url = $permalink.$user."?langchanged=en";
//			$url = $permalink.
		}
	}
	return $url;
}

add_filter('date_i18n', 'bii_multilingual_filter_date_i18n', 10, 4);
add_filter('bii_multilingual_link_selector_translation', 'bii_multilingual_link_selector_translation', 10, 2);
if (get_option("bii_use_um")) {
	add_filter("um_profile_menu_link_main", "bii_um_multilingual_fix_menu");
	add_filter("um_profile_menu_link_activity", "bii_um_multilingual_fix_menu");
	add_filter("um_profile_menu_link_posts", "bii_um_multilingual_fix_menu");
	add_filter("um_profile_menu_link_comments", "bii_um_multilingual_fix_menu");
	add_filter("um_profile_menu_link_messages", "bii_um_multilingual_fix_menu");

	add_filter("um_user_profile_tabs", "bii_um_multilingual_tabs");
	
	add_filter("um_view_label_user_registered", "bii_um_multilingual_label_title");
	add_filter("um_view_label_online_status", "bii_um_multilingual_label_title");
	add_filter("um_view_label_first_name", "bii_um_multilingual_label_title");
	add_filter("um_view_label_last_name", "bii_um_multilingual_label_title");
	add_filter("um_view_label_country", "bii_um_multilingual_label_title");
	add_filter("um_view_label_bii_cover", "bii_um_multilingual_label_title");
	add_filter("um_view_label_adresse", "bii_um_multilingual_label_title");
	add_filter("um_view_label_code_postal", "bii_um_multilingual_label_title");
	add_filter("um_view_label_ville", "bii_um_multilingual_label_title");
}