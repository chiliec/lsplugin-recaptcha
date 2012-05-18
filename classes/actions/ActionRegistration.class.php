<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: reCAPTCHA
 * @Description: Replaces the standard captcha for reCAPTCHA
 * @Author URI: http://chiliec.ru
 * @LiveStreet Version: 0.5.1
 * @Plugin Version:	3.0.0
 * ----------------------------------------------------------------------------
*/

class PluginreCAPTCHA_ActionRegistration extends PluginreCAPTCHA_Inherit_ActionRegistration {

    /**
     * Показывает страничку регистрации и обрабатывает её
     *
     * @return unknown
     */
    protected function EventIndex() {
    				
				require_once(Config::Get('path.root.server').'/plugins/recaptcha/include/recaptchalib.php');

        /**
         * Если нажали кнопку "Зарегистрироваться"
         */
        if (isPost('submit_register')) {
            //Проверяем  входные данные
            $bError=false;
            /**
             * Проверка логина
             */
            if (!func_check(getRequest('login'),'login',3,30)) {
                $this->Message_AddError($this->Lang_Get('registration_login_error'),$this->Lang_Get('error'));
                $bError=true;
            }
            /**
             * Проверка мыла
             */
            if (!func_check(getRequest('mail'),'mail')) {
                $this->Message_AddError($this->Lang_Get('registration_mail_error'),$this->Lang_Get('error'));
                $bError=true;
            }
            /**
             * Создание или проверка пароля
             */
            if(Config::Get('plugin.recaptcha.hide_password')) {
								$password = func_generator(Config::Get('plugin.recaptcha.length_password'));
							}
						else {
								$password = getRequest('password');
								if (!func_check($password,'password',5)) {
										$this->Message_AddError($this->Lang_Get('registration_password_error'),$this->Lang_Get('error'));
										$bError=true;
								}
								elseif ($password!=getRequest('password_confirm')) {
										$this->Message_AddError($this->Lang_Get('registration_password_error_different'),$this->Lang_Get('error'));
										$bError=true;
								}						
						}
            /**
             * Проверка капчи
             */
            if(Config::Get('plugin.recaptcha.allow_recaptcha')) {
								$resp = null;
								if (array_key_exists('recaptcha_response_field',$_POST)){
										$resp = recaptcha_check_answer(Config::Get('plugin.recaptcha.private_key'),$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
								}
								if ($resp==null or !$resp->is_valid){
										$this->Message_AddError($this->Lang_Get('registration_captcha_error'),$this->Lang_Get('error'));
										$bError=true;
								}
            }
						else {
								if (!isset($_SESSION['captcha_keystring']) or $_SESSION['captcha_keystring']!=strtolower(getRequest('captcha'))) {
										$this->Message_AddError($this->Lang_Get('registration_captcha_error'),$this->Lang_Get('error'));
										$bError=true;
								}
						}
            /**
             * А не занят ли логин?
             */
            if ($this->User_GetUserByLogin(getRequest('login'))) {
                $this->Message_AddError($this->Lang_Get('registration_login_error_used'),$this->Lang_Get('error'));
                $bError=true;
            }
            /**
             * А не занято ли мыло?
             */
            if ($this->User_GetUserByMail(getRequest('mail'))) {
                $this->Message_AddError($this->Lang_Get('registration_mail_error_used'),$this->Lang_Get('error'));
                $bError=true;
            }
            /**
             * Если всё то пробуем зарегить
             */
            if (!$bError) {
                /**
                 * Создаем юзера
                 */
                $oUser=Engine::GetEntity('User');
                $oUser->setLogin(getRequest('login'));
                $oUser->setMail(getRequest('mail'));
                $oUser->setPassword(func_encrypt($password));
                $oUser->setDateRegister(date("Y-m-d H:i:s"));
                $oUser->setIpRegister(func_getIp());
                /**
                 * Если используется активация, то генерим код активации
                 */
                if (Config::Get('general.reg.activation')) {
                    $oUser->setActivate(0);
                    $oUser->setActivateKey(md5(func_generator().time()));
                } else {
                    $oUser->setActivate(1);
                    $oUser->setActivateKey(null);
                }
                /**
                 * Регистрируем
                 */
                if ($this->User_Add($oUser)) {
										/**
										* Сбрасываем сессию каптчи
										*/
										if (!Config::Get('plugin.recaptcha.allow_recaptcha')) {
												unset($_SESSION['captcha_keystring']);
										}

                    /**
                     * Создаем персональный блог
                     */
                    $this->Blog_CreatePersonalBlog($oUser);                    
                    /**
                     * Подписываем пользователя к нужным блогам
                     */
                    if(Config::Get('plugin.recaptcha.allow_autojoin')) {
												foreach (Config::Get('plugin.recaptcha.blogs_autojoin') as $BlogTitle) {
														$oBlogUserNew=Engine::GetEntity('Blog_BlogUser');
														$oBlogAdd=$this->Blog_GetBlogByTitle($BlogTitle);														
														$oBlogUserNew->setBlogId($oBlogAdd->getId());
														$oBlogUserNew->setUserId($oUser->getId());
														$oBlogUserNew->setUserRole(ModuleBlog::BLOG_USER_ROLE_USER);
														$this->Blog_AddRelationBlogUser($oBlogUserNew);
														$oBlogAdd->setCountUser($oBlogAdd->getCountUser()+1);
														$this->Blog_UpdateBlog($oBlogAdd);	
												}
										}
                    /**
                     * Если юзер зарегистрировался по приглашению то обновляем инвайт
                     */
                    if (Config::Get('general.reg.invite') and $oInvite=$this->User_GetInviteByCode($this->GetInviteRegister())) {
                        $oInvite->setUserToId($oUser->getId());
                        $oInvite->setDateUsed(date("Y-m-d H:i:s"));
                        $oInvite->setUsed(1);
                        $this->User_UpdateInvite($oInvite);

                        $oUserInvite=$this->User_GetUserById($oInvite->getUserFromId());
                        $oUserInvite->setRating($oUserInvite->getRating()+0.5);
                        $this->User_Update($oUserInvite);
                    }
                    /**
                     * Если стоит регистрация с активацией то проводим её
                     */
                    if (Config::Get('general.reg.activation')) {
                        /**
                         * Отправляем на мыло письмо о подтверждении регистрации
                         */
                        $this->Notify_SendRegistrationActivate($oUser,$password);
                        Router::Location(Router::GetPath('registration').'confirm/');
                    } else {
                        $this->Notify_SendRegistration($oUser,$password);
                        $this->Viewer_Assign('bRefreshToHome',true);
                        $oUser=$this->User_GetUserById($oUser->getId());
                        $this->User_Authorization($oUser,false);
                        $this->SetTemplateAction('ok');
                        $this->DropInviteRegister();
                    }
                } else {
                    $this->Message_AddErrorSingle($this->Lang_Get('system_error'));
                    return Router::Action('error');
                }
            }
        }
			$this->Viewer_Assign('public_key',Config::Get('plugin.recaptcha.public_key'));
			$this->Viewer_Assign('theme',Config::Get('plugin.recaptcha.theme'));
			$this->Viewer_Assign('lang',Config::Get('plugin.recaptcha.lang'));
    }
}
?>
