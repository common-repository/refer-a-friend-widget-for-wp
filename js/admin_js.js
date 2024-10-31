jQuery(function () {

    var ib_body = jQuery('body');
    /**
     * Add New Campaign
     */
    ib_body.on('click', '#add_new_campaign_button', function (e) {
        e.preventDefault();

        var campaign_table = jQuery('.wp-campaign-table');
        var tr = '<tr>' +
                    '<td class="campaign_name">' +
                        '<input type="text" name="campaign_name" disabled />' +
                    '</td>' +
                    '<td class="campaign_secret_key">' +
                        '<input type="text" name="campaign_secret_key" />' +
                    '</td>' +
                    '<th class="campaign_buttons">' +
                        '<input type="submit" value="Save" class="button button-primary button-save"/> ' +
                        '<input type="submit" value="Remove" class="button button-secondary button-remove" />' +
                    '</th>' +
                '</tr>';


        campaign_table.append(tr);
        campaign_table.find('[name="campaign_secret_key"]:last').focus();

        jQuery(this).attr('disabled', 'disabled');
    });


    /**
     * Save Campaign
     */
    ib_body.on('click', '.button-save', function (e) {
        e.preventDefault();

        var _this = jQuery(this);
        _this.attr('disabled', 'disabled');
        jQuery('#message-wrap').html('');

        var campaign_secret_key = _this.closest('tr').find('[name=campaign_secret_key]').val();
        var campaign_default = _this.closest('tr').find('[name=campaign_default]').is(':checked');

        if (campaign_secret_key != '') {
            jQuery.post(
                ajaxparams.ajaxurl,
                {
                    action: 'ajax_actions',
                    sub_action: 'add_campaign',
                    secret_key: campaign_secret_key
                },
                function (response) {
                    var data = jQuery.parseJSON(response);
                    if (data.message) {
                        var message = data.message ? data.message : '';
                        window.location = window.location + "&message=" + message + '&success=' + data.success;
                    } else {
                        window.location.reload();
                    }
                }
            );
        } else {
            var message = 'Secret Key field is empty!';
            window.location = window.location + "&message=" + message + '&success=false';
        }
    });

    /**
     * Remove Campaign
     */
    ib_body.on('click', '.button-remove', function (e) {
        e.preventDefault();
        var _this = jQuery(this);
        _this.attr('disabled', 'disabled');
        jQuery('#message-wrap').html('');
        jQuery('#add_new_campaign_button').removeAttr('disabled');
        var campaign_secret_key = _this.closest('tr').find('[name=campaign_secret_key]').val();

        if(campaign_secret_key == ''){
            _this.closest('tr').fadeOut('slow', function () {
                jQuery(this).remove();
            });
        }else{
            if(confirm("Deleted campaign will not be removed from the pages!") && campaign_secret_key){
                _this.closest('tr').fadeOut('slow', function () {
                    jQuery(this).remove();
                });
                jQuery.post(
                    ajaxparams.ajaxurl,
                    {
                        action: 'ajax_actions',
                        sub_action: 'remove_campaign',
                        secret_key: campaign_secret_key
                    },
                    function (response) {
                        var data = jQuery.parseJSON(response);
                        if (data.message) {
                            var message = data.message ? data.message : '';
                            window.location = window.location + "&message=" + message + '&success=' + data.success;
                        } else {
                            window.location.reload();
                        }
                    }
                );
            }else{
                _this.removeAttr('disabled');
            }
        }
    });

    /**
     *  Set default Campaign
     */
    ib_body.on('change', '#wp_ib_default_campaign', function () {
        var _this = jQuery(this);
        _this.closest('.wp_ib_default_campaign_label').addClass('loading');
        var def_campaign_secret_key = _this.val();
        jQuery.post(
            ajaxparams.ajaxurl,
            {
                action: 'ajax_actions',
                sub_action: 'set_default_campaign',
                default_campaign_secret_key: def_campaign_secret_key
            },
            function (response) {
                var data = jQuery.parseJSON(response);
                _this.closest('.wp_ib_default_campaign_label').removeClass('loading');
                if (data.message) {
                    var message = data.message ? data.message : '';
                    window.location = window.location + "&message=" + message + '&success=' + data.success;
                } else {
                    window.location.reload();
                }
            }
        );
    });

});