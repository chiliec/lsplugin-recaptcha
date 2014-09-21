<?php
/* ---------------------------------------------------------------------------
 * @Plugin Name: reCAPTCHA
 * @Description: Replaces the standard captcha for reCAPTCHA
 * @Author URI: http://livestreet.net
 * @LiveStreet Version: 1.0.3
 * @Plugin Version:	4.0.0
 * ----------------------------------------------------------------------------
*/

class PluginRecaptcha_ModuleUser_EntityUser extends PluginRecaptcha_Inherit_ModuleUser_EntityUser {
	/**
	 * Определяем дополнительные правила валидации
	 *
	 * @param array
	 */
	public function __construct($aParam = false) {
		$this->aValidateRules[] = array('captcha', 'recaptcha', 'on' => array('registration'));
		parent::__construct($aParam);
	}

    public function ValidateRecaptcha($sValue) {
        $resp = recaptcha_check_answer(
            Config::Get('plugin.recaptcha.private_key'),
            $_SERVER["REMOTE_ADDR"],
            getRequestStr('recaptcha_challenge_field'),
            getRequestStr('recaptcha_response_field')
        );
        if (!$resp->is_valid) {
            return $this->Lang_Get('validate_captcha_not_valid',null,false);
        }
        return true;
    }
}
?>