jQuery(document).ready(function() {
	setTimeout(
		function() {
			$('#registration-user-captcha').html($('#popup-registration-captcha').clone(true,true));
		},2000);
});
