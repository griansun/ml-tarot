jQuery(function () {
    jQuery('.spreadlink').on('click', function (e) {
        e.preventDefault();
        var linkId = jQuery(this).attr("id");
        var spreadId = linkId.replace("spreadlink-", "");

        var data = {
            'action': 'my_action',
            'spreadid': spreadId,
            'whatever': ajax_object.we_value      // We pass php values differently!
        }

        jQuery.post(ajax_object.ajax_url, data, function (response) {
            var obj = jQuery.parseJSON(response);
            //console.log(obj.totalcards);
            document.location.href = '/tarot/legging?ml_reading=' + obj.readingdata;//alert('Got this from the server: ' + response);
        });

        /*jQuery.post({
        url: '/wp-content/plugins/ml-tarot/php/tarotreading.php',
        type: 'post',
        data: { 'action': 'follow', 'spreadid': spreadId },
        success: function (data, status) {
        document.location.href = '/tarot/legging?ml_reading=' + data.readingdata;
        },
        error: function (xhr, desc, err) {
        alert("error");
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
        }
        });*/
        // end ajax call
    });
});        // end function