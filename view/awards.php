<div id="app" class="wrap">
    <ul class="voting-nav">
        <li class="voting-nav__item">
            <router-link class="voting-nav__link" to="/create-award">Create new Award</router-link>
        </li>
        <li class="voting-nav__item">
            <router-link class="voting-nav__link" to="/">View All</router-link>
        </li>
    </ul>

    <router-view></router-view>
</div>

<script>
var app = new Vue({
    el: '#app',
    router: voting.router.awards,
    watch: {
        '$route': function(to, from) {
            this.awardId = to.params.id
        }
    },
    data: function() {
        return {
            awardId: this.$route.params.id
        }
    }
});
</script>

<?php include 'partial/_idle-page-notice.php'; ?>