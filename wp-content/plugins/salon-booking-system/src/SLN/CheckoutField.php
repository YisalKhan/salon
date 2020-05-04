<?php

class SLN_CheckoutField{

	protected $settings = [];
	protected $field_type = ['text','textarea','checkbox','select'];
	protected $field_widths = ['full'=>12,'half'=>6,'quarter'=>3];
	protected $default_atts = [
		"label"     => '',
        "type"      => 'text',
        "width"     => "half",
        "default"   => null,
        "required" 	=> false,
        "hidden" 	=> false,
        "options" 	=> [],
        "customer_profile" => false
	];

	public function __construct($opts = []){
		if(is_string($opts)){
			$opts = [
				"label" => $opts
			];
		}
		$this->settings = $this->validate( $opts );
	}

	protected function validate($opts = []){
		$ret =[];
		foreach ($this->default_atts as $key => $value) {
			if(array_key_exists($key, $opts)){
				if(!in_array($key,['default','customer_profile'])){
					$type = gettype($value);
								settype($opts[$key],$type);
				}
				if($key === 'type'){
					$opts[$key] = in_array($opts[$key],$this->field_type) ? $opts[$key] : $value;
				}
				if($key === 'width'){
					$opts[$key] = in_array($opts[$key],array_keys($this->field_widths)) ? $opts[$key] : $value;
				}
				$ret[$key] = $opts[$key];
			}else{
				$ret[$key] = $value;
			}
		}
		$ret['width'] = $this->field_widths[$ret['width']];
		return $ret;
	}

	public function get($key){
		return array_key_exists($key, $this->settings) ?  $this->settings[$key] : null;
	}

	public function getSettings(){
		return $this->settings;
	}
}
