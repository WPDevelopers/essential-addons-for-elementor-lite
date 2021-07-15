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

                    $("ul.select2-selection__rendered").sortable({
                        containment: 'parent',
                        update: function() {
                            // jQuery('#elementor-control-default-c762').val();
                            // console.log(elementor.settings.page.getItems('category_ids'));

                            $select = $(this).closest(".select2-container").prev();
                            var options = $select.find("option");
                            var newoptions = [];
                            // Clear option
                            $(this).find(".select2-selection__choice").each(function (i, tag) {
                                options.each(function (j, option) {
                                    if($(tag).attr("title") == $(option).text()){
                                        newoptions.push(option);
                                    }
                                });
                            });
                            $select.empty();
                            $select.append(newoptions);
                            $(ID).trigger('change');
                            // jQuery(this).trigger('input');
                        }
                    });
                    // console.log($(ID).select2('data'));
                    //Drag and drop feature : implemented for select2
                    // $("ul.select2-selection__rendered").sortable({
                    //     containment: 'parent',
                    //     update: function(event) {
                    //         // jQuery('#elementor-control-default-c762').select2('data');
                    //         jQuery(this).trigger('input');
                    //         jQuery(this).trigger('select');
                    //         jQuery(this).trigger('select2');
                    //
                    //         let selectedVal1 = jQuery(ID).select2('data');
                    //
                    //         console.log(selectedVal1);
                    //
                    //         // let selectedVal2 = $(ID).find(':selected');
                    //
                    //         // $('input[name=eae-file-link]').val(resp.data.url).trigger('input');
                    //         // let select2Val = $(ID).select2().val();
                    //         // console.log(selectedVal);
                    //         //
                    //         // console.log(obj.data);
                    //         // $(ID).trigger('change');
                    //         // let container = view.getContainer();
                    //         // let settings = view.getContainer().settings.attributes;
                    //         // console.log(container);
                    //         // console.log('okk');
                    //         // Object.keys(value).forEach((key) => {
                    //         //     settings[key] = value[key];
                    //         // });
                    //         //
                    //         // parent.window.$e.run("document/elements/settings", {
                    //         //     ID,
                    //         //     settings,
                    //         //     options: {
                    //         //         external: refresh,
                    //         //     },
                    //         // });
                    //
                    //         // $select = $(this).closest(".select2-container").prev();
                    //         // var options = $select.find("option");
                    //         // var newoptions = [];
                    //         // // Clear option
                    //         // $(this).find(".select2-selection__choice").each(function (i, tag) {
                    //         //     options.each(function (j, option) {
                    //         //         if($(tag).attr("title") == $(option).text()){
                    //         //             newoptions.push(option);
                    //         //         }
                    //         //     });
                    //         // });
                    //         // $select.empty();
                    //         // $select.append(newoptions);
                    //
                    //     }
                    // });

                    // $("ul.select2-selection__rendered").sortable({
                    //     containment: 'parent',
                    //     // stop: function(event, ui) {
                    //     //     // event target would be the <ul> which also contains a list item for searching (which has to be excluded)
                    //     //     var arr = Array.from($(event.target).find('li:not(.select2-search)').map(function () {
                    //     //         return {name: $(this).data('data').text, value: $(this).data('data').id };
                    //     //     }))
                    //     //     console.log(arr);
                    //     // }
                    //
                    //     // $select = $(this).closest(".select2-container").prev();
                    //     //         // var options = $select.find("option");
                    //     //         // var newoptions = [];
                    //     //         // // Clear option
                    //     //         // $(this).find(".select2-selection__choice").each(function (i, tag) {
                    //     //         //     options.each(function (j, option) {
                    //     //         //         if($(tag).attr("title") == $(option).text()){
                    //     //         //             newoptions.push(option);
                    //     //         //         }
                    //     //         //     });
                    //     //         // });
                    //     //         // $select.empty();
                    //     //         // $select.append(newoptions);
                    // });

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

        // function handleMenuItemColor ( newValue ) {
        //     console.log( newValue );
        //     elementor.reloadPreview();
        // }
        //
        // elementor.settings.page.addChangeCallback( 'category_ids', handleMenuItemColor );
    });
}(jQuery));