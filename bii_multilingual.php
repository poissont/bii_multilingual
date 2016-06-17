<?php
/*
  Plugin Name: bii_multilingual
  Description: Ajoute des fonctions multilingues
  Version: 0.2
  Author: Biilink Agency
  Author URI: http://biilink.com/
  License: GPL2
 */
define('bii_multilingual_version', '0.2');
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
		$ret .= "<span class='bii_select_languages ' data-value='$lang'>$selected <img src='".bii_multilingual_url. "flags/$lang.png' /> $libelle</span>";
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
	if ($wp_query->query_vars["lang"]) {
		echo "bii_lang = '" . $wp_query->query_vars["lang"] . "';";
	}
	if ($_REQUEST["lang"]) {
		echo "bii_lang = '" . $_REQUEST["lang"] . "';";
	}
	echo "bii_multilingual_activated = true;";
//	echo "</script>";
//	global $post;
//	consoleDump($post);
//	echo "<script>";
}

function bii_multilingual_display_flag($lang) {
	if (!get_option("bii_multilingual_$lang" . "_name")) {
		update_option("bii_multilingual_$lang" . "_name", $dl[$lang]);
	}
	if (!get_option("bii_multilingual_$lang" . "_flag")) {
		update_option("bii_multilingual_$lang" . "_flag", bii_multilingual_url . "flags/$lang" . ".png");
	}
	$flag = get_option("bii_multilingual_$lang" . "_flag");
	$alt = get_option("bii_multilingual_$lang" . "_name");
	$url = apply_filters("bii_multilingual_real_baseurl", get_bloginfo("url"));
	$contents = "<span class='bii-select-flag'>"
		. "<a href='$url/?lang=$lang'>"
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

function bii_multilingual_real_baseurl($url) {
	$url = explode("?", $url);
	return $url[0];
}

add_filter("bii_multilingual_real_baseurl", "bii_multilingual_real_baseurl");
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

add_action("bii_options_submit", "bii_multilingual_option_submit", 5);

add_action("bii_additionnal_js_var", "bii_multilingual_additionnal_js_var");
add_action('wp_enqueue_scripts', "bii_multilingual_enqueueJS");
add_action('wp_enqueue_scripts', "bii_multilingual_css_front");
add_action("bii_options_title", "bii_add_multilingual_option_title", 10);
add_action("bii_options", "bii_add_multilingual_options");

function bii_multilingual_current_language() {
	$lang = 'fr';
	global $wp_query;
	if ($wp_query->query_vars["lang"]) {
		$lang = $wp_query->query_vars["lang"];
	}
	if ($_REQUEST["lang"]) {
		$lang = $_REQUEST["lang"];
	}
	return $lang;
}

function bii_multilingual_add_translation($search_term,$replace_term,&$bii_search,&$bii_replace){
	$bii_search[] = $search_term;
	$bii_replace[] = $replace_term;
}

function bii_multilingual_adaptative_text($attrs,$content){
	$lang = bii_multilingual_current_language();
	if(isset($attrs["lang"])){
		$lang = $attrs["lang"];
	}
	return bii_multilingual_more_translation(__($content));
}
function bii_multilingual_list_shortcodes($attrs,$content){
	?><tr>
		<td><strong>[bii_autotranslate lang="<?= bii_multilingual_current_language(); ?>"]</strong></td>
		<td>Appelle la fonction de traduction du texte dans la lang choisie, par défaut lang correspond à la langue en cours du site</td>
	</tr><?php
}

add_shortcode("bii_autotranslate","bii_multilingual_adaptative_text");

add_action("bii_specific_shortcodes", "bii_multilingual_list_shortcodes");

function bii_multilingual_more_translation($text) {
	$bii_search = [];
	$bii_replace = [];
	$lang = bii_multilingual_current_language();
	if ($lang == "fr") {
		bii_multilingual_add_translation("Written by","Écrit par",$bii_search,$bii_replace);
		bii_multilingual_add_translation("read more","plus",$bii_search,$bii_replace);
		bii_multilingual_add_translation("Category","Catégorie ",$bii_search,$bii_replace);
		bii_multilingual_add_translation("Search","Recherche ",$bii_search,$bii_replace);
	}
	if ($lang == "en") {
		bii_multilingual_add_translation("Rechercher","Search",$bii_search,$bii_replace);
		bii_multilingual_add_translation("Votre Recherche","Search",$bii_search,$bii_replace);
	}
//	pre($bii_search);
//	pre($bii_replace);
	return str_replace($bii_search, $bii_replace, $text);
}

add_action("gettext", "bii_multilingual_more_translation");
