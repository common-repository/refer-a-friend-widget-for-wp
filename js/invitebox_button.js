(function() {

    var widget_script = "<script id='invitebox-script' type='text/javascript'>" +
        "(function() {" +
        "    var ib = document.createElement('script');" +
        "    ib.type = 'text/javascript';" +
        "    ib.async = true;" +
        "    ib.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'invitebox.com/invitation-camp/{ib_url}/invitebox.js?key={ib_key}&jquery='+(typeof(jQuery)=='undefined');" +
        "    var s = document.getElementsByTagName('script')[0];" +
        "    s.parentNode.insertBefore(ib, s);" +
        "})();" +
        "</script>" +
        "<a id='invitebox-href' href='https://invitebox.com/widget/{ib_url}/share' >[Referral Program - {ib_name}]</a>";

    var tracking_script = "<div id='wp_ib_tracking'>[Tracking Script - {ib_name}]</div><script id='invitebox-track' type='text/javascript'>" +
        "(function() {" +
        "    var ibl = document.createElement('script');" +
        "    ibl.type = 'text/javascript'; ibl.async = true;" +
        "    ibl.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'invitebox.com/invitation-camp/{ib_url}/invitebox-landing.js?hash='+escape(window.location.hash);" +
        "    var s = document.getElementsByTagName('script')[0];" +
        "    s.parentNode.insertBefore(ibl, s);" +
        "document.getElementById('wp_ib_tracking').style.display='none';})();</script>";

    var widget_submenu = [];
    var tracking_submenu = [];
    var len = all_campaigns.length;
    if( len > 0 ){
        for (var i = 0; i < len; i++) {
            var c_name = all_campaigns[i].name;
            var c_url = all_campaigns[i].url;
            var c_key =  all_campaigns[i].pkey;
            var w_script = widget_script.replace(/{ib_url}/g, c_url).replace(/{ib_key}/g, c_key).replace(/{ib_name}/g, c_name);
            var t_script = tracking_script.replace(/{ib_url}/g, c_url).replace(/{ib_name}/g, c_name);
            widget_submenu.push({
                text: c_name,
                value: w_script,
                onclick: function() {
                    if(tinymce.activeEditor.dom.get('invitebox-href') == null){
                        tinymce.activeEditor.getBody().appendChild(tinymce.activeEditor.dom.create('div', {}, this.value() ));
                    }
                }
            });
            tracking_submenu.push({
                text: c_name,
                value: t_script,
                onclick: function() {
                    if(tinymce.activeEditor.dom.get('invitebox-track') == null){
                        tinymce.activeEditor.getBody().appendChild(tinymce.activeEditor.dom.create('div', {}, this.value()));
                    }
                }
            });
        }
        tinymce.PluginManager.add('invitebox_button', function( editor, url ) {
            editor.addButton( 'invitebox_button', {
                title: 'InviteBox',
                icon: 'invitebox-icon',
                type: 'menubutton',
                classes: 'widget btn wp_ib_button',
                menu: [
                    {text: 'Add Widget Script', menu: widget_submenu },
                    {text: 'Add Tracking Script', menu: tracking_submenu},
                    {text: 'Remove Widget Script', onclick: function() {
                        var element = tinymce.activeEditor.dom.get('invitebox-script');
                        if (element)
                            element.remove();

                        var element_title = tinymce.activeEditor.dom.get('invitebox-href');
                        if(element_title)
                            element_title.parentElement.remove();
                    }},
                    {text: 'Remove Tracking Script', onclick: function() {
                        var element = tinymce.activeEditor.dom.get('invitebox-track');
                        if (element)
                            element.remove();

                        var element_title = tinymce.activeEditor.dom.get('wp_ib_tracking');
                        if(element_title)
                            element_title.parentElement.remove();
                    }}]
            });
        });
    }
})();
