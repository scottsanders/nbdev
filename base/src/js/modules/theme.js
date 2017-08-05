// ------------------------------------
//
// Theme
//
// ------------------------------------

(function($) {

	if (typeof window.Theme == 'undefined') window.Theme = {};

	Theme = {

		settings: {},

		init: function() {

			$(".field-target").change(function(){
				if($(this).val() == "direct")
					$(".custom-recipient").show();
				else
					$(".custom-recipient").hide();
			});

		}
	};
	
	module.exports = Theme;

})(jQuery);