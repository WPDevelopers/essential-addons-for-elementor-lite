(function ($) {
    $(document).on('eael_select2_init', function (event, obj) {
        var ID = '#elementor-control-default-' + obj.data._cid;
        setTimeout(function () {
            $(ID).select2({
                minimumInputLength: 3,
                ajax: {
                    url: eael_select2_localize.ajaxurl + "?action=eael_select2_search_post&post_type=" + obj.data.source_type + '&source_name=' + obj.data.source_name,
                    dataType: 'json'
                },
                initSelection: function (element, callback) {
                    if (!obj.multiple) {
                        callback({id: '', text: eael_select2_localize.search_text});
                    }else{
						callback({id: '', text: ''});
					}
					var ids = [];
                    if(!Array.isArray(obj.currentID) && obj.currentID != ''){
						 ids = [obj.currentID];
					}else if(Array.isArray(obj.currentID)){
						 ids = obj.currentID.filter(function (el) {
							return el != null;
						})
					}

                    if (ids.length > 0) {
                        var label = $("label[for='elementor-control-default-" + obj.data._cid + "']");
                        label.after('<span class="elementor-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>');
                        $.ajax({
                            method: "POST",
                            url: eael_select2_localize.ajaxurl + "?action=eael_select2_get_title",
                            data: {post_type: obj.data.source_type, source_name: obj.data.source_name, id: ids}
                        }).done(function (response) {
                            if (response.success && typeof response.data.results != 'undefined') {
                                Object.entries(response.data.results).forEach(entry => {
                                    const [key, value] = entry;
                                    element.append('<option selected="selected" value="' + key + '">' + value + '</option>');
                                });
                            }
							label.siblings('.elementor-control-spinner').remove();
                        });
                    }
                }
            });
        }, 100);
    });
}(jQuery));