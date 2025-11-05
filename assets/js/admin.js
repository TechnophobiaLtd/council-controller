/**
 * Council Controller Admin JavaScript
 */
(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        var mediaUploader;
        
        /**
         * Handle logo upload
         */
        $('.council-upload-logo-button').on('click', function(e) {
            e.preventDefault();
            
            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            // Create the media uploader
            mediaUploader = wp.media({
                title: 'Choose Council Logo',
                button: {
                    text: 'Use this logo'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });
            
            // When an image is selected, run a callback
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                
                // Set the attachment ID
                $('#council_logo_id').val(attachment.id);
                
                // Display the image
                var imgElement = $('.council-logo-preview img');
                imgElement.attr('src', attachment.url);
                imgElement.show();
                
                // Show remove button
                $('.council-remove-logo-button').show();
            });
            
            // Open the uploader dialog
            mediaUploader.open();
        });
        
        /**
         * Handle logo removal
         */
        $('.council-remove-logo-button').on('click', function(e) {
            e.preventDefault();
            
            // Clear the attachment ID
            $('#council_logo_id').val('');
            
            // Hide the image
            var imgElement = $('.council-logo-preview img');
            imgElement.attr('src', '');
            imgElement.hide();
            
            // Hide remove button
            $(this).hide();
        });
        
    });
    
})(jQuery);
