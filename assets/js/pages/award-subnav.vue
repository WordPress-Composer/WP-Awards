<template>
    <ul class="voting-nav" v-show="id">
        <li class="voting-nav__item">
            <router-link class="voting-nav__link" :to="'/award/' + id">Basic Information</router-link>
        </li>
        <li class="voting-nav__item">
            <router-link class="voting-nav__link" :to="'/award/' + id + '/categories'">Categories</router-link>
        </li>
        <li class="voting-nav__item">
            <router-link class="voting-nav__link" :to="'/award/' + id + '/finalists'">Finalists</router-link>
        </li>
        <li class="voting-nav__item">
            <router-link class="voting-nav__link" :to="'/award/' + id + '/winners'">Winners</router-link>
        </li>
        <li class="voting-nav__item">
            <router-link class="voting-nav__link" :to="'/award/' + id + '/statistics'">Statistics</router-link>
        </li>
        <li class="voting-nav__item">
            <router-link class="voting-nav__link" :to="'/award/' + id + '/status'">Status</router-link>
        </li>
    </ul>
</template>

<script>
export default {
    props: {
        id: Number
    }
}
</script>