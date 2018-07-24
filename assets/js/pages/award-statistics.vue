<template>
    <div>
        <h1>Award Statistics: {{title}} {{year}}</h1>

        <subnav v-bind:id="awardId"></subnav>

        <span class="voting-spinner" v-if="loading"></span>

        <div class="panel" v-if="!loading">
            <span class="error">{{errorMessage}}</span>
        </div>

        <table class="voting-list-table" cellspacing="0">
            <thead>
                <tr>
                    <td>Statistic</td>
                    <td>Count</td>
                    <td>View Data</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Submissions</td>
                    <td>{{submissions}}</td>
                    <td v-if="parseInt(submissions) > 0">
                        <form method="GET" :action="submissionsURL" v-on:submit="handleSubmission" target="_blank">
                            <input type="hidden" name="format" value="csv" />
                            <input type="submit" value="Download">
                        </form>
                    </td>
                    <td v-else>-</td>
                </tr>
                <tr>
                    <td>Votes</td>
                    <td>{{votes}}</td>
                    <td v-if="parseInt(votes) > 0">
                        <form method="GET" :action="votesURL" v-on:submit="handleVote" target="_blank">
                            <input type="hidden" name="format" value="csv" />
                            <input type="submit" value="Download">
                        </form>
                    </td>
                    <td v-else>-</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import SubNav from './award-subnav.vue';

export default {

    data() {
        return {
            errorMessage: '',
            title: '',
            year: '',
            awardId: 0,
            loading: true,
            votes: '...',
            submissions: '...'
        }
    },

    computed: {
        submissionsURL() {
            return this.$api.downloads.nominationsCSVURL(this.$route.params.id);
        },
        votesURL() {
            return this.$api.downloads.votesCSVURL(this.$route.params.id);
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

                this.title = award.title;
                this.year = award.year;
                this.awardId = award.id
            })
            .then(() => {
                return this.$api.statistics.get(this.$route.params.id)
            })
            .then(({stats}) => {
                if (!stats) {
                    throw new Error('Could not access stats');
                }

                this.votes = stats.votes;
                this.submissions = stats.submissions;
            })
            .catch(this.handleError)
            .finally(() => {
                this.loading = false;
            })
    },

    methods: {
        handleSubmission(e) {
            e.preventDefault();
            this.$api.downloads.getRestrictedDownload(e.target.action, 'submissions-count')
                .catch(this.handleError)
        },

        handleVote(e) {
            e.preventDefault();
            this.$api.downloads.getRestrictedDownload(e.target.action, 'votes-count')
                .catch(this.handleError)
        },
        
        handleError(err) {
            this.errorMessage = err.message;
        }
    }
}
</script>