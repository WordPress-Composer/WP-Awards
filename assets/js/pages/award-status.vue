<template>
    <div>
        <h1>Award Status: {{title}} {{year}}</h1>

        <subnav v-bind:id="awardId"></subnav>

        <span class="voting-spinner" v-if="loading"></span>

        <div class="panel" v-if="!loading">
            <span class="error">{{errorMessage}}</span>

            <div :class="'panel__item ' + status">
                This award is {{status}}.
            </div>
            <div class="panel__item">
                <button
                    v-on:click="goLive"
                    v-show="!this.live">
                    Go Live
                </button>

                <button
                    v-on:click="archive"
                    v-show="!this.archived">
                    Archive
                </button>

                <button
                    v-on:click="unpublish"
                    v-show="this.live || this.archived">
                    Unpublish
                </button>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import SubNav from './award-subnav.vue';

export default {
    computed: {
        status() {
            if (this.live) {
                return 'live';
            } else if (this.archived) {
                return 'archived';
            } else {
                return 'unpublished';
            }
        }
    },
    data() {
        return {
            errorMessage: '',
            live: false,
            archived: false,
            title: '',
            year: '',
            awardId: 0,
            loading: true
        }
    },

    components: {
        subnav: SubNav
    },

    mounted() {
        this.$api.awards.get(this.$route.params.id)
            .then(({award}) => {
                if (!award) {
                    return this.$router.push({ path: '/' });
                }

                this.loading = false;
                this.live = award.live;
                this.archived = award.archived;
                this.title = award.title;
                this.year = award.year;
                this.awardId = award.id
            })
            .catch(err => {
                this.errorMessage = err.message;
            });
    },
    methods: {
        /**
         * Handle status change from server
         * @param {Object} award
         */
        handleStatusChanges({award}) {
            this.live = award.live;
            this.archived = award.archived;
            this.errorMessage = '';
        },

        /**
         * Handle error object (from promise)
         * @param {Error} Error object
         */
        handleErrorWhenSaving(err) {
            this.errorMessage = err.message;
        },

        /**
         * Handles finally (from promise) once promise has completed
         */
        handleFinishRequest() {
            this.loading = false;
        },

        /**
         * Goes live with the award
         */
        goLive() {
            this.loading = true;
            this.$api.awards.goLive(this.$route.params.id)
                .then(this.handleStatusChanges)
                .catch(this.handleErrorWhenSaving)
                .finally(this.handleFinishRequest)
        },

        /**
         * Archives the award
         */
        archive() {
            this.loading = true;
            this.$api.awards.archive(this.$route.params.id)
                .then(this.handleStatusChanges)
                .catch(this.handleErrorWhenSaving)
                .finally(this.handleFinishRequest)
        },

        /**
         * Unpublished the award
         */
        unpublish() {
            this.loading = true;
            this.$api.awards.unpublish(this.$route.params.id)
                .then(this.handleStatusChanges)
                .catch(this.handleErrorWhenSaving)
                .finally(this.handleFinishRequest)
        }
    }
}
</script>

<style scoped>
.live {
    color: white !important;
    background-color: orange !important;
}

.archived {
    background-color: lightgrey !important;
}

button {
    margin-bottom: 0;
}
</style>