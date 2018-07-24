<script>
    /**
     * Alerts users when they have been idle for too long, and athorisation
     * will no longer be valid. A simple refresh message is sent in case they've
     * had the admin open for longer than nonce refresh time. A "day" by default.
     */
    if (jQuery) {
        var $ = jQuery;
        window['voting-idle-alert-shown'] = false;
        $(function() {
            $(document).on('heartbeat-tick', function (event, data) {
                if (!data['wp-auth-check'] && !window['voting-idle-alert-shown']) {
                    alert('Very sorry. This page has been idle for too long. Please refresh to make further updates.');
                    window['voting-idle-alert-shown'] = true;
                }
            });
        });
    }
</script>