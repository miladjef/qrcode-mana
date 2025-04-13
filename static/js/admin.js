// Add to wp-content/plugins/qrcode-mana/static/js/admin.js

jQuery(document).ready(function($) {
    // PDF upload functionality
    $('.wqm-upload-pdf').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var urlField = button.siblings('input[name="wqm_datasheet_url"]');
        
        var mediaUploader = wp.media({
            title: 'Select or Upload PDF Datasheet',
            button: {
                text: 'Use this PDF'
            },
            library: {
                type: 'application/pdf'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            urlField.val(attachment.url);
            
            // Update preview if available
            if ($('#wqm-qr-code-preview').length) {
                // You could trigger a preview update here
            }
        });
        
        mediaUploader.open();
    });
});
