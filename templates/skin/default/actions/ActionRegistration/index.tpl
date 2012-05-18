{assign var="noSidebar" value=true}
{include file='header.light.tpl'}


<div class="center">
	<form action="{router page='registration'}" method="POST" enctype="multipart/form-data">
		<h2>{$aLang.registration}</h2>

		{$test2}<br />

		{hook run='form_registration_begin'}

		<p><label>{$aLang.registration_login}<br />
		<input type="text" name="login" value="{$_aRequest.login}" class="input-text input-wide" /><br />
		<span class="note">{$aLang.registration_login_notice}</span></label></p>

		<p><label>{$aLang.registration_mail}<br />
		<input type="text" name="mail" value="{$_aRequest.mail}" class="input-text input-wide" /><br />
		<span class="note">{$aLang.registration_mail_notice}</span></label></p>
		
		{if !$oConfig->GetValue('plugin.recaptcha.hide_password')}
		<p><label>{$aLang.registration_password}<br />
		<input type="password" name="password" value="" class="input-text input-wide" /><br />
		
		<span class="note">{$aLang.registration_password_notice}</span></label></p>
		<p><label>{$aLang.registration_password_retry}<br />
		<input type="password" value="" id="repass" name="password_confirm" class="input-text input-wide" /></label></p>
		{/if}
		
		{$aLang.registration_captcha}<br />
		{if $oConfig->GetValue('plugin.recaptcha.allow_recaptcha')}
		<script type="text/javascript">
        var RecaptchaOptions = {
						lang : '{$lang}',
						theme : '{$theme}'
        };
		</script>
		<script type="text/javascript" src="http://api.recaptcha.net/challenge?k={$public_key}"></script>
		<noscript>{$aLang.js_not_allowed}</noscript>
		{else}
		<img src="{cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}" onclick="this.src='{cfg name='path.root.engine_lib'}/external/kcaptcha/index.php?{$_sPhpSessionName}={$_sPhpSessionId}&amp;n='+Math.random();" alt="it's img captcha" />
		<p><input type="text" name="captcha" value="" maxlength="3" class="input-text input-100" /></p>
		{/if}			

		{hook run='form_registration_end'}

		<input type="submit" name="submit_register" class="button" value="{$aLang.registration_submit}" />
	</form>
</div>


{include file='footer.light.tpl'}