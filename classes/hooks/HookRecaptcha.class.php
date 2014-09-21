<?php
/* ---------------------------------------------------------------------------
 * @Plugin Name: reCAPTCHA
 * @Description: Replaces the standard captcha for reCAPTCHA
 * @Author URI: http://livestreet.net
 * @LiveStreet Version: 1.0.3
 * @Plugin Version:	4.0.0
 * ----------------------------------------------------------------------------
*/
class PluginRecaptcha_HookRecaptcha extends Hook {

    /*
     * Регистрация событий на хуки
     */
    public function RegisterHook() {
        $this->addHook('template_block_registration_captcha', 'Recaptcha');
        $this->addHook('template_block_popup_registration_captcha', 'Recaptcha_modal');
    }

    public function Recaptcha() {
        $recaptcha = recaptcha_get_html(Config::Get('plugin.recaptcha.public_key'), null, Config::Get('plugin.recaptcha.use_ssl'));
        $this->Viewer_Assign('recaptcha', $recaptcha);
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'inject.recaptcha.tpl');
    }

    public function Recaptcha_modal() {
        $recaptcha = recaptcha_get_html(Config::Get('plugin.recaptcha.public_key'), null, Config::Get('plugin.recaptcha.use_ssl'));
        $this->Viewer_Assign('recaptcha_modal', $recaptcha);
        return $this->Viewer_Fetch(Plugin::GetTemplatePath(__CLASS__).'inject.recaptcha_modal.tpl');
    }
}
?>
