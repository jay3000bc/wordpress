(function($) {
    var file_frame;
    $(document).on("click", ".jjd_upload_image_button", function(e) {
        e.preventDefault();

        // Let's start over to make sure everything works
            if ( file_frame )
                file_frame.remove();

            file_frame = wp.media.frames.file_frame = wp.media( {
                title: $(this).data( 'JJD Social Buttons' ),
                button: {
                    text: $(this).data( 'Upload Photo' )
                },
                multiple: false
            } );

            file_frame.on( 'select', function() {
                var attachment = file_frame.state().get( 'selection' ).first().toJSON();
                $('.jjd_default_photo').val(attachment.url);
                $('#img_container img').attr('src',attachment.url);
            } );
        file_frame.open();
});
        
})(jQuery);