<?php
/* ---------------------------------------------------------------------------
 * @Plugin Name: reCAPTCHA
 * @Description: Replaces the standard captcha for reCAPTCHA
 * @Author URI: http://livestreet.net
 * @LiveStreet Version: 1.0.3
 * @Plugin Version:	4.0.0
 * ----------------------------------------------------------------------------
*/

/**
 * Запрещаем напрямую через браузер обращение к этому файлу.
 */
if (!class_exists('Plugin')) {
    die('Hacking attemp!');
}

class PluginRecaptcha extends Plugin {

    protected $aInherits=array(
        'entity'  =>array('ModuleUser_EntityUser')
    );

    public function Activate() {
        return true;
    }

    public function Deactivate(){
        return true;
    }

    public function Init() {
		$this->Viewer_AppendScript(Plugin::GetTemplatePath(__CLASS__)."js/script.js");
        Config::Set('module.user.captcha_use_registration', false);
    }
}
?>
