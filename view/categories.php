<div id="award-plugin" class="wrap">
    <div id="app">
        <router-view></router-view>
    </div>
</div>

<script>
var app = new Vue({
    el: '#app',
    router: voting.router.categories
});
</script>

<?php include 'partial/_idle-page-notice.php'; ?>