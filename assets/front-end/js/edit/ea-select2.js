(function ($) {
	$(document).on('eael_select2_init', function (event, obj) {
		var ID = '#elementor-control-default-' + obj.data._cid;
		setTimeout(function () {
			$(ID).select2({
				minimumInputLength: 3,
				ajax: {
					url: ea_select2_localize.ajaxurl+"?action=eael_select2_search_post&post_type=" + obj.data.source_type,
					dataType: 'json'
				},
				initSelection: function (element, callback) {
					if (obj.currentID > 0) {
						element.attr('disabled','disabled');
						$.ajax({
							method: "POST",
							url: ea_select2_localize.ajaxurl+"?action=eael_select2_search_title",
							data: {post_type: obj.data.source_type, id: obj.currentID}
						}).done(function (response) {
							if (response.success) {
								element.append('<option selected="selected" value="' + response.data.id + '">' + response.data.text + '</option>');
								callback({id: response.data.id, text: response.data.text});
								element.removeAttr('disabled');
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