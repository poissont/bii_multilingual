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
	
	public static function get_translation_of($id_post,$lang){
		$ret = null;
		$req = "element_id = '$id_post' AND language_code = '$lang'";
		$nb = static::nb($req);
		if($nb){
			$ids = static::all_id($req);
			$item = new static($ids[0]);
			$ret = $item->trid();
		}
		return $ret;
	}
	
	public static function get_trad_base_of($id_post){
		$ret = null;
		$req = "element_id = '$id_post'";
		$nb = static::nb($req);
		if($nb){
			$ids = static::all_id($req);
			$item = new static($ids[0]);
			$ret = $item->trid();
		}
		return $ret;
	}

}
