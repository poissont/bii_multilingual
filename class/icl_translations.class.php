<?php

class icl_translations extends global_class {

	protected $translation_id;
	protected $element_type;
	protected $element_id;
	protected $trid;
	protected $language_code;
	protected $source_language_code;

	public static function identifiant() {
		return "translation_id";
	}

	public static function get_translation_of($id_post, $lang) {
		$ret = null;
		$req = "element_id = '$id_post' AND language_code = '$lang'";
		$nb = static::nb($req);
		if ($nb) {
			$ids = static::all_id($req);
			$item = new static($ids[0]);
			$ret = $item->trid();
		}
		return $ret;
	}

	public static function get_trad_base_of($id_post) {
		$ret = null;
		$req = "element_id = '$id_post'";
		$nb = static::nb($req);
		if ($nb) {
			$ids = static::all_id($req);
			$item = new static($ids[0]);
			$ret = $item->trid();
		}
		return $ret;
	}

	public static function get_posts_lang($lang = null) {
		if ($lang == null) {
			$lang = bii_multilingual_current_language();
		}
		$ret = [];
		$req = " language_code = '$lang'";
		$nb = static::nb($req);
		if ($nb) {
			$ids = static::all_id($req);
			foreach ($ids as $id) {
				$item = new static($id);
				$ret[] = $item->element_id();
			}
		}
		return $ret;
	}
	
	public static function get_post_not_lang($lang=null){
		if ($lang == null) {
			$lang = bii_multilingual_current_language();
		}
		$ret = [];
		$req = " language_code not in ('$lang') and element_id in (select ID from wp_cfwww_posts where post_type IN ('page','post'))";
		$nb = static::nb($req);
		if ($nb) {
			$ids = static::all_id($req);
			foreach ($ids as $id) {
				$item = new static($id);
				$ret[] = $item->element_id();
			}
		}
		return array_unique($ret);
	}
	
	
}
