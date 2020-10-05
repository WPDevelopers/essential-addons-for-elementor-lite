(function ($) {
	$(document).on('ea_select2_init', function (event, obj) {
		var ID = '#elementor-control-default-' + obj.data._cid;
		setTimeout(function () {
			$(ID).select2({
				minimumInputLength: 3,
				ajax: {
					url: ea_select2_localize.ajaxurl+"?action=ea_select2_search_post&post_type=" + obj.data.source_type,
					dataType: 'json'
				},
				initSelection: function (element, callback) {
					if (obj.currentID > 0) {
						var label = $("label[for='elementor-control-default-"+ obj.data._cid+"']");
						element.attr('disabled','disabled');
						label.after('<span class="elementor-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>');
						$.ajax({
							method: "POST",
							url: ea_select2_localize.ajaxurl+"?action=ea_select2_get_title",
							data: {post_type: obj.data.source_type, id: obj.currentID}
						}).done(function (response) {
							if (response.success) {
								element.append('<option selected="selected" value="' + response.data.id + '">' + response.data.text + '</option>');
								callback({id: response.data.id, text: response.data.text});
								element.removeAttr('disabled');
								label.siblings('.elementor-control-spinner').remove();
							}
						});
					} else {
						callback({id: '', text: 'Search...'});
					}
				}
			});
		}, 1);
	});
}(jQuery));