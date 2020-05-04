<?php

class SLN_Enum_AbstractEnum{

	public static function getLabels(){
		if(is_null(($labels = static::$labels)) || !$labels ){
			throw new Exception("Called ".static::class);
		}
		return $labels;
	}

	public static function isInited(){
		return static::$inited;
	}

	public function __get($property){
		throw new Exception("Call $property in ".static::class);
	}
}