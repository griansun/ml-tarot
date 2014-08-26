jQuery(function () {
    jQuery('.spreadlink').on('click', function (e) {
        e.preventDefault();

        jQuery.ajax({
        url: '/wp-content/plugins/ml-tarot/php/tarotreading.php',
        type: 'post',
        data: {'action': 'follow', 'spreadid': '1'},
        success: function(data, status) {
        if(data == "ok") {
            alert("ok");
        }
        },
        error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
        }
        });
        // end ajax call
    });
}); // end function