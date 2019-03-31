(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function () {
		//
		// select current domain
		$('#domains').change(function (e) {
			// change value sitekey / secretkey
			var selected = $(this).find('option:selected');
			$('#sitekey').val(selected.data('site'));
			$('#secretkey').val(selected.data('secret'));
			// change value current domain
			$('#currentdomain').val(selected.val());
		});

		// refill hidden field currentdomain with new domain or selected domain
		$('#newdomain').on('input', function (e) {
			var selected = $('#domains').find('option:selected');
			if ($('#newdomain').val() == '') {
				$('#currentdomain').val(selected.val());
			} else {
				$('#currentdomain').val($('#newdomain').val());
			}
		});

		// unlock google api input field
		$('#lockapi').click(function (e) {
			$("input[name$='urlapi']").prop('disabled', function (i, v) {
				return !v;
			});
			return false;
		});

		// delete domain selected
		$('#delete_dom').click(function (e) {
			var URL = WQ_admin_recaptcha_ajax.url;
			var nonce = WQ_admin_recaptcha_ajax.nonce;
			$.ajax({
				url: URL,
				type: 'POST',
				data: {
					// definie dans enqueue
					action: 'WQ_admin_recaptcha',
					_ajax_nonce:nonce,
					// cible de la requete
					requestTarget:'removeDomain',

					// queryvars: verifcaptcha_ajax.queryvars
				},
				success: function (res) {
					if (res == 'success') console.log('success ', res);
					// if(res=='error') ...;
				},
				error: function (err) {
					console.log('error', err);
				}
			});
			return false;
		});
	});
})(jQuery);