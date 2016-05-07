(function($) {
    var file_frame;
    $(document).on("click", ".upload_image_button", function(e) {
        e.preventDefault();

        // Let's start over to make sure everything works
            if ( file_frame )
                file_frame.remove();

            file_frame = wp.media.frames.file_frame = wp.media( {
                title: $(this).data( 'Advanced User Bio' ),
                button: {
                    text: $(this).data( 'Upload Image' )
                },
                multiple: false
            } );

            file_frame.on( 'select', function() {
                var attachment = file_frame.state().get( 'selection' ).first().toJSON();
                $('.author_photo').val(attachment.url);
                //$('#author_photo').attr('src',attachment.url);
                //$('#img_container').html( '<img src="' + attachment.url + '" alt="" style="max-width:100%;" />' );
                $('#img_container img').attr('src',attachment.url);
            } );
        file_frame.open();
});
        
})(jQuery);
