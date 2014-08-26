jQuery(function () {
    jQuery('.spreadlink').on('click', function (e) {
        e.preventDefault();

        jQuery.ajax({
            url: '/wp-content/plugins/ml-tarot/php/tarotreading.php',
            type: 'post',
            data: { 'action': 'follow', 'spreadid': '1' },
            success: function (data, status) {
                document.location.href = '/tarot/legging?ml_reading=' + data.readingdata;
            },
            error: function (xhr, desc, err) {
                alert("error");
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });
        // end ajax call
    });
});   // end function