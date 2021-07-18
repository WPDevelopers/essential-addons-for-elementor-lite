(function ($) {
    $(document).on('eael_select2_init', function (event, obj) {
        var ID = '#elementor-control-default-' + obj.data._cid;
        setTimeout(function () {
            var IDSelect2 = $(ID).select2({
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
                                let eaelSelect2Options = '';
                                ids.forEach(function (item, index){
                                    if(typeof response.data.results[item] != 'undefined'){
                                        const key = item;
                                        const value = response.data.results[item];
                                        eaelSelect2Options += `<option selected="selected" value="${key}">${value}</option>`;
                                    }
                                })

                                element.append(eaelSelect2Options);
                            }
							label.siblings('.elementor-control-spinner').remove();
                        });
                    }
                }
            });

            //Manual Sorting : Select2 drag and drop : starts
            // #ToDo Try to use promise in future
            setTimeout(function (){
                IDSelect2.next().children().children().children().sortable({
                    containment: 'parent',
                    stop: function(event, ui) {
                        ui.item.parent().children('[title]').each(function() {
                            var title = $(this).attr('title');
                            var original = $('option:contains(' + title + ')', IDSelect2).first();
                            original.detach();
                            IDSelect2.append(original)
                        });
                        IDSelect2.change();
                    }
                });

                $(ID).on("select2:select", function(evt) {
                    var element = evt.params.data.element;
                    var $element = $(element);

                    $element.detach();
                    $(this).append($element);
                    $(this).trigger("change");
                });
            },200);
            //Manual Sorting : Select2 drag and drop : ends

        }, 100);

    });
}(jQuery));