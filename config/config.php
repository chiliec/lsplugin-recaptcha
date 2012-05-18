<?php

/* ---------------------------------------------------------------------------
 * @Plugin Name: reCAPTCHA
 * @Description: Replaces the standard captcha for reCAPTCHA
 * @Author URI: http://chiliec.ru
 * @LiveStreet Version: 0.5.1
 * @Plugin Version:	3.0.0
 * ----------------------------------------------------------------------------
*/

$config = array();

$config['allow_recaptcha'] = true; // Включить reCAPTCHA, ключи здесь https://www.google.com/recaptcha/admin/create
$config['public_key']  = '6LcX6MwSAAAAAF5ldirAJPg5kPvhzsqjlnECHK1c';
$config['private_key'] = '6LcX6MwSAAAAALQZew7mdsD4Uif-4K4c29fMLPjJ';

$config['theme'] = 'clean'; // Различные темы каптчи под ваш дизайн: red, white, blackglass, clean
$config['lang']  = 'ru';  // Поддерживаемые языки: ru, en, nl, fr, de, pt, es, tr

$config['hide_password']   = true; // Убрать поля ввода пароля и заменить пароль на генерируемый автоматически
$config['length_password'] = 7; // Длина пароля (от 5 до 32 символов). Не имеет смысла при $config['hide_password'] = false;

$config['allow_autojoin'] = false;  // Новые пользователи будут автоматически присоеденятся к заданным блогам
$config['blogs_autojoin'] = array( // Массив названий блогов для автоматического присоединения
	'Первый коллективный блог' //Другие блоги перечисляются через запятую, после последнего запятая НЕ ставится
//'Второй коллективный блог' //Если раскоментируете эту строчку - добавьте запятую после предыдущей
);

return $config;