<template>
    <div class="wrap s-voting-content">

        <subnav v-bind:id="award.id"></subnav>

        <div v-if="award instanceof NotRequestedYet">
            <h1>Choose Winners: </h1>
            <p>
                <span class="voting-spinner"></span>
                Please wait as the information is being loaded...
            </p>
        </div>

        <h1 v-else-if="award instanceof ErrorOnLoad === false">
            Choose Winners: {{ award.title }} {{ award.year }}
        </h1>

        <div 
            v-if="awardLoadError" 
            class="error">
            {{awardLoadError}}
        </div>

        <div 
            class="admin-board" 
            v-if="categories instanceof NotRequestedYet === false && categories instanceof ErrorOnLoad === false">

            <div class="admin-board__main">

                <div 
                    class="admin-board__content-area"
                    v-if="finalists.length === 0">
                    <p>There are no finalists from which to select.</p>
                </div>

                <div 
                    class="category" 
                    v-for="category in categories"
                    v-bind:key="category.id">
                    <EditCategory
                        :categoryId="category.id"
                        :categoryName="category.name"
                        :finalists="winnerMetaFinalists(winnersMeta, getFinalistsByCategory(finalists, category.id))"
                        :winnersMeta="winnersMeta"
                        :winner="getWinnerByCategory(winners, category.id)"
                        :errorMessage="getError(saveError, category.id)"
                        v-on:handle-winner="saveWinnerChange"
                    />
                </div>
            </div>

            <div class="admin-board__side" v-if="finalists.length > 0">

                <div class="admin-board__content-area" v-if="!award.winners_announced_on">
                    <h3>Publish Winners</h3>
                    <p>
                        Are you really ready to publish winners? Once you publish 
                        winners, these individuals will be live for all to see.
                        Only publish when you are sure.
                    </p>

                    <button
                        v-if="!prePublishing"
                        v-on:click="prePublishing = true">
                        Publish
                    </button>
                    
                    <span class="error" v-if="prePublishing">
                        Are you sure you want to publish winners?
                    </span>

                    <button 
                        class="btn btn--danger" 
                        v-on:click="publishWinners"
                        v-if="prePublishing">
                        Yes, publish
                    </button>

                    <button 
                        v-if="prePublishing"
                        v-on:click="prePublishing = false">
                        No
                    </button>
                </div>

                <div class="admin-board__content-area" v-if="award.winners_announced_on">
                    <p>Winners have been published.</p>
                    <p>Published on {{award.winners_announced_on}}</p>
                    <a 
                        v-on:click="unpublishWinner"
                        class="btn btn--dark">
                        Unpublish
                    </a>
                </div>

                <div class="admin-board__content-area error" v-if="publishError">
                    {{publishError}}
                </div>

            </div>
        </div>
        
    </div>
</template>

<script type="text/javascript">
import SubNav from './award-subnav.vue';

/**
 * @todo handle ErrorOnLoad and ensure it works correctly
 */
import modal from '../components/modal.vue';
import WinnerMetaBox from '../components/winner-metabox.vue';
import EditCategory from '../components/winners-edit-category.vue';
import * as model from '../view-helper/winners-edit.js';

export default {

    data() {
        return model.Init()
    },

    components: {
        modal,
        WinnerMetaBox,
        EditCategory,
        subnav: SubNav
    },

    mounted() {
        this.$api.awards.get(this.$route.params.id)
            .then(this.handleGetAwards)
            .catch(this.handleErrorGettingAwards)
    },

    methods: {
        
        /**
         * Handles getting award
         */
        handleGetAwards({award, categories, finalists, winners, winnersMeta}) {
            this.award = award;
            this.categories = categories;
            this.finalists = finalists;
            this.winners = winners;
            this.winnersMeta = winnersMeta;
        },

        /**
         * Handles error getting awards
         * @param {Error} err
         */
        handleErrorGettingAwards(err) {
            this.awardLoadError = 'There was an error retrieving the award. ' + err.message;
            this.award = new this.ErrorOnLoad;
        },

        /**
         * Save changes to winner
         * @param {string} action Should be 'create', 'update', 'delete'
         * @param {object} winner
         */
        saveWinnerChange(action, winner) {

            let data = Object.assign({}, winner, {
                award_id: this.$route.params.id
            });

            this.$api.winners[action](data)
                .then(response => {
                    return this.$api.awards.get(this.$route.params.id);
                })
                .then(({winners, winnersMeta}) => {
                    this.winners = winners;
                    this.winnersMeta = winnersMeta;
                })
                .catch(err => {
                    this.saveError.category_id = winner.category_id;
                    this.saveError.message = err.message ? err.message : 'We cannot ' + action + ' winner ';
                });
        },

        /**
         * Publishes winner on server
         */
        publishWinners() {
            this.$api.awards.publishWinners(this.$route.params.id)
                .then(({award}) => {
                    if (!award) {
                        throw new Exception('Error retrieving award data after saving')
                    }
                    this.award = award;
                })
                .catch(err => {
                    this.publishError = err.message;
                })
                .finally(() => {
                    this.prePublishing = false;
                })
        },

        unpublishWinner() {

            if (this.unpublishing) {
                return;
            }

            this.unpublishing = true;

            this.$api.awards.unpublishWinners(this.$route.params.id)
                .then(({award}) => {
                    if (!award) {
                        throw new Exception('Error retrieving award data after saving')
                    }
                    this.award = award;
                })
                .catch(err => {
                    this.publishError = err.message;
                })
                .finally(() => {
                    this.unpublishing = false;
                });
        }
    }
}
</script>

<style lang="scss" scoped>
    .voting-spinner {
        display: inline-block;
        vertical-align: middle;
    }

    .save-button {
        margin-top: 1rem;
    }

    .category {
        margin-bottom: 60px;
    }
</style>