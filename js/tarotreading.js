jQuery(function () {
    jQuery('.spreadpanel').on('click', function (e) {
        e.preventDefault();
        var linkId = jQuery(this).attr("id");
        var spreadId = linkId.replace("spreadpanel-", "");

        var data = {
            'action': 'ml_generatereading',
            'spreadid': spreadId,
            'whatever': ajax_object.we_value      // We pass php values differently!
        }

        jQuery.post(ajax_object.ajax_url, data, function (response) {
            var obj = jQuery.parseJSON(response);
            document.location.href = '/tarot/legging?ml_reading=' + obj.readingdata;
        });
    });

    jQuery("div.spreadpanel")
        .mouseover(function () {
            jQuery(this).addClass("over");
        })
        .mouseout(function () {
            jQuery(this).removeClass("over");
        });
});          // end function