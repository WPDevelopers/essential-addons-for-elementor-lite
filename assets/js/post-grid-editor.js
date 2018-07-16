elementor.hooks.addAction( 'panel/open_editor/widget/eael-post-grid', function( panel, model, view ) {
    console.log(  panel );
    var inputEl = panel.$el.find( '#elementor-panel-content-wrapper #elementor-panel-page-editor #elementor-controls .elementor-control-eael_post_other_site_url input' );
    var otherCat = panel.$el.find( '#elementor-panel-content-wrapper #elementor-panel-page-editor #elementor-controls .elementor-control-eael_other_category select' );

    var html = '';
    inputEl.on( 'blur', function( e ){
        var url = e.currentTarget.value.replace(/^\/|\/$/g, '');
        $.ajax({
            url: url + '/wp-json/wp/v2/categories?hide_empty=1',
            type: 'GET',
            success: function( res ) {
                res.forEach(function( elem ){
                    html += '<option value="'+ elem.id +'" data-select2-id="'+ elem.id +'">'+ elem.name +'</option>';
                });
                otherCat.append( html );
                console.log( res );
                html = '';
            },
            error: function( err ) {
                console.log( 'Something went wrong!' );
            }
        });
    } );
    
 } );