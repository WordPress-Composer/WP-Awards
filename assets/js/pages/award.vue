<template>
    <div class="s-voting-content">

        <h1>Awards</h1>

        <span class="error">{{errorMessage}}</span>

        <div class="voting-table-container">
            <table class="voting-list-table" cellspacing="0">
                <thead>
                    <tr>
                        <td class="pointable" v-on:click="sortByTitle">Title</td>
                        <td class="pointable" v-on:click="sortByYear">Year</td>
                        <td class="pointable" v-on:click="sortByCategories">Categories</td>
                        <td class="pointable" v-on:click="sortByStatus">Status</td>
                        <td class="pointable" v-on:click="sortBySubmissions">Submissions</td>
                        <td class="pointable" v-on:click="sortByVotes">Votes</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="award in awards">
                        <td class="voting-list-table__main-item">{{award.title}}</td>
                        <td>{{award.year}}</td>
                        <td>
                            <router-link class="status-link"
                                :to="'/award/' + award.id + '/categories'">
                                {{award.categories.length}}
                            </router-link>
                        </td>
                        <td>
                            <router-link class="status-link"
                                :to="'/award/' + award.id + '/status'">
                                <span v-show="award.live">LIVE</span>
                                <span v-show="award.archived">ARCHIVED</span>
                                <span v-show="!award.archived && !award.live">UNPUBLISHED</span>
                            </router-link>
                        </td>
                        <td>
                            {{award.submissions}}
                        </td>
                        <td>
                            {{award.votes}}
                        </td>
                        <td>
                            <router-link v-bind:to="award.link">
                                <button>Manage</button>
                            </router-link>
                        </td>
                    </tr>
                </tbody>
            </table>
            <span class="voting-spinner" v-if="loading">loading</span>
        </div>
    </div>
</template>

<script>
import {
    sortAwardsByYear,
    sortAwardsByYearReversed,
    sortAwardsByCategories,
    sortStatus,
    sortAwardsBySubmissions,
    sortAwardsByVotes,
    sortAwardsByTitle
} from '../utils/sort.js';

export default {

    data() {
        return {
            awards: [],
            loading: true,
            errorMessage: '',
            sortAscending: false,
            sortField: 'year'
        }
    },

    mounted() {
        this.$api.awards.all()
            .then(response => {
                this.awards = response.map(({award, categories}) => {
                    award.link = 'award/' + award.id;
                    award.categories = categories;
                    return award;
                }).sort(sortAwardsByYearReversed);
            })
            .then(() => {
                return this.$api.statistics.all()
            })
            .then(({stats}) => {
                if (!stats) {
                    throw new Error('Could not access stats')
                }

                this.awards = this.awards.map(award => {
                    let result = stats.find(stat => stat.awardId === award.id)
                    award.submissions = result.submissions;
                    award.votes = result.votes;
                    return award;
                });
            })
            .catch(err => {
                this.errorMessage = err.message;
            })
            .finally(() => {
                this.loading = false
            })
            
    },

    methods: {

        sortByCategories() {
            this.sort('categories', sortAwardsByCategories);
        },

        sortByStatus() {
            this.sort('status', sortStatus);
        },

        sortByYear() {
            this.sort('year', sortAwardsByYear);
        },

        sortBySubmissions() {
            this.sort('submissions', sortAwardsBySubmissions);
        },

        sortByVotes() {
            this.sort('votes', sortAwardsByVotes);
        },

        sortByTitle() {
            this.sort('title', sortAwardsByTitle);
        },

        sort(field, sortable) {
            this.sortAscending = this.sortField === field ? !this.sortAscending : this.sortAscending;
            this.sortField = field;
            this.awards = this.awards.sort(sortable(this.sortAscending));
        }
    }
}
</script>

<style lang="scss">
.pointable {
    cursor: pointer;
}

.voting-table-container {
    overflow-x: auto;
}

.status-link {
    text-decoration: none;
}
</style>