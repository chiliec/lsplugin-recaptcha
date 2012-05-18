<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: reCAPTCHA
 * @Description: Replaces the standard captcha for reCAPTCHA
 * @Author URI: http://chiliec.ru
 * @LiveStreet Version: 0.5.1
 * @Plugin Version:	3.0.0
 * ----------------------------------------------------------------------------
 */

if (! class_exists ( "Plugin" )) {
	die ( "Hacking attemp!" );
}

class PluginreCAPTCHA extends Plugin {

	public $aDelegates;
	
  protected $aInherits=array(
			'action' => array(
					'ActionRegistration'=>'_ActionRegistration'
			),
	);
  public function Activate() {
			return true;
	}
	
  public function Deactivate() {
			return true;
  }
  
	public function Init() {
			$this->aDelegates = array(
					'template'=>array(
							'actions/ActionRegistration/index.tpl'=>'_actions/ActionRegistration/index.tpl'
					),
			);		
	}
}