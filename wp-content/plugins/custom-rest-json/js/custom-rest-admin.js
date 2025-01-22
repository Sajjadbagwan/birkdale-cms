jQuery(document).ready(function($) {
    $('#my-custom-button').on('click', function() {
        $.ajax({
            url: myCustomAdmin.ajax_url,
            type: 'POST',
            data: {
                action: 'my_custom_action'
                // Add nonce or additional data here if needed
            },
            success: function(response) {
                $('#response').html('<p>' + response.message + '</p>');
            },
            error: function(xhr, status, error) {
                $('#response').html('<p>An error occurred: ' + error + '</p>');
            }
        });
    });
    jQuery('.start-update').on('click', function() {
        var paramValue = jQuery(this).attr('data-id');
        jQuery(this).siblings('.spinner-show').css('display', 'inline-block');
        jQuery(this).prop('disabled', true);
        // Start the update process
        fetch(myCustomAdmin.ajax_url+'?action=bpu_update_products&parmas='+paramValue)
            .then(response => response.json())
            .then(data => {                
                jQuery(this).siblings('.spinner-show').css('display', 'none');
                jQuery(this).siblings('.status').text('Update complete!');
                jQuery(this).prop('disabled', false);
            });
    });
});