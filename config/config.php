<?php
/* ---------------------------------------------------------------------------
 * @Plugin Name: reCAPTCHA
 * @Description: Replaces the standard captcha for reCAPTCHA
 * @Author URI: http://livestreet.net
 * @LiveStreet Version: 1.0.3
 * @Plugin Version:	4.0.0
 * ----------------------------------------------------------------------------
*/

$config = array();

// Ключи можно получить здесь https://www.google.com/recaptcha/admin/create
$config['public_key']  = '6LcX6MwSAAAAAF5ldirAJPg5kPvhzsqjlnECHK1c';
$config['private_key'] = '6LcX6MwSAAAAALQZew7mdsD4Uif-4K4c29fMLPjJ';

$config['use_ssl'] = false; // запрос капчи с https-сервера

return $config;