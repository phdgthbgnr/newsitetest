(function($) {
	'use strict';

	$(document).ready(function($) {
		// site key prod
		var sitekey = WQverifcaptcha_ajax.sitekey;
		var URL = WQverifcaptcha_ajax.url;
		// site key preprod
		grecaptcha.ready(function() {
			grecaptcha.execute(sitekey, { action: 'contact' }).then(function(token) {
				// Verify the token on the server.
				$.ajax({
					url: URL,
					type: 'POST',
					data: {
						action: 'WQrecaptcha',
						token: token
						// queryvars: verifcaptcha_ajax.queryvars
					},
					success: function(res) {
						if (res == 'success') $('.et_pb_contact_submit').prop('disabled', false);
						// if(res=='error') ...;
					},
					error: function(err) {
						console.log('error',err);
					}
				});
			});
		});
	});
})(jQuery);
