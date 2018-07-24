<template>
<div class="s-voting-content">
    <h1>Award Finalists: {{award.title}} {{award.year}}</h1>

    <subnav v-bind:id="award.id"></subnav>

    <span class="voting-spinner" v-if="loading"></span>
    
    <div class="panel" v-show="categories.length === 0 && !loading">
        <div class="panel__item">
            There are currently no categories selected for this award. Once categories have been 
            added, finalists can be assigned here. Categories can be managed within the awards area.
            Select categories <a v-bind:href="'?page=voting-awards#\/award\/' + award.id + '/categories'">here</a>
        </div>
    </div>
    <div 
        v-if="categories.length > 0 && canStartEditing"
        v-for="(category, categoryIndex) in categories"
        v-bind:key="categoryIndex">
        <h3>{{category.name}}</h3>

        <div 
            class="finalists"
            v-for="orderNumber in (1, numberOfFinalistsToSelect)"
            v-bind:key="orderNumber"
        >
            <FinalistComponent
                v-if="findFinalist(category.id, orderNumber)"
                v-on:create-finalist-requested="createFinalist"
                v-on:update-finalist-requested="updateFinalist"
                v-on:delete-finalist-requested="deleteFinalist"
                v-on:unset-finalist-requested="unsetFinalist"
                v-bind:componentKey="orderNumber"
                v-bind:finalist="findFinalist(category.id, orderNumber)"
                v-bind:asyncResponse="asyncResponse"
            ></FinalistComponent>

            <button 
                v-on:click="addEmptyFinalist(category.id, orderNumber)"
                v-if="!findFinalist(category.id, orderNumber)" 
                class="empty-finalist">+</button>
        </div>
    </div>

    <div
        v-if="pageErrorMessage"
        class="error">
        {{pageErrorMessage}}
    </div>
</div>
</template>

<script type="text/javascript">
import SubNav from './award-subnav.vue';
import WpImagePicker from '../components/wp-image-picker.vue';
import {Finalist} from '../model/finalist.js';
import Modal from '../components/modal.vue';
import FinalistComponent from '../components/finalist.vue';
import {
    AsyncResponse
} from '../view-helper/finalist-edit.js';

/**
 * @todo numberOfFinalists should come from the database
 */
export default {
    data() {
        return {
            finalists: [],
            award: [],
            categories: [],
            numberOfFinalistsToSelect: 3,
            canStartEditing: false,
            loading: true,
            pageErrorMessage: '',
            asyncResponse: new AsyncResponse
        }
    },

    components: {
        WpImagePicker,
        Modal,
        FinalistComponent,
        subnav: SubNav
    },

    methods: {

        /**
         * Gets the award and populates the vue data
         * @return {Promise}
         */
        getAward() {
            return this.$api.awards.get(this.$route.params.id)
                .then(({award, categories}) => {

                    if (!award) {
                        return this.$router.push({ path: '/' });
                    }

                    this.award = award;
                    this.categories = categories;
    
                    return Promise.resolve(award);
                })
        },

        /**
         * Gets and sets finalists
         * @return {Promise}
         */
        getFinalists() {
            return this.$api.finalists.allByAwardId(this.$route.params.id)
                .then(({finalists, err}) => {
                    this.finalists = finalists;
                    return Promise.resolve(finalists);
                });
        },

        /**
         * Creates a finalist on server
         * @param {Object} data
         * @param {number} key
         * @todo remove key if not needed and have a defined Object structure for clarity
         */
        createFinalist(data, key) {
            let args = [data.categoryId, data.orderNum];

            this.$api.finalists
                .create(this.$route.params.id, data)
                .then(response => {
                    this.handleSuccessfulSave(...args, 'Finalist created')
                })
                .catch(err => {
                    this.handleFailedSave(...args, 'Cannot create finalist. ' + err.message)
                });
        },

        /**
         * Updates finalist on server
         * @param {Object} data
         * @param {number} key
         * @todo remove key if not needed and have a defined Object structure for clarity
         */
        updateFinalist(data, key) {
            let args = [data.categoryId, data.orderNum];

            this.$api.finalists
                .update(data)
                .then(response => {
                    this.handleSuccessfulSave(...args, 'Finalist updated')
                })
                .catch(err => {
                    this.handleFailedSave(...args, 'Cannot make changes. ' + err.message)
                });
        },

        /**
         * Unsets a finalist box (if user changes their mind)
         * @param {Object} data
         * @todo remove key if not needed and have a defined Object structure for clarity
         */
        unsetFinalist(data) {
            this.finalists = this.finalists.filter(finalist => !(finalist.categoryId === data.categoryId && finalist.orderNum === data.orderNum));
        },

        /**
         * Deletes a finalist from server
         * @param {Object} data
         * @todo remove key if not needed and have a defined Object structure for clarity
         */
        deleteFinalist(data, key) {
            let args = [data.categoryId, data.orderNum];

            this.$api.finalists
                .delete(data.id)
                .then(response => {
                    this.handleSuccessfulSave(...args, 'Finalist deleted')
                })
                .catch(err => {
                    this.handleFailedSave(...args, 'Cannot delete finalist. ' + err.message)
                });
        },

        /**
         * Finds a finalist by category id and order number 
         * @param {number} categoryId
         * @param {number} orderNumber
         * @return {Finalist} finalist
         */
        findFinalist(categoryId, orderNumber) {
            return this.finalists.find(
                finalist => finalist.categoryId === categoryId && finalist.orderNum == orderNumber
            );
        },

        /**
         * Finds finalists by category id
         * @param {number} categoryId
         * @param {number} orderNumber
         * @return {Finalist[]} finalist
         */
        findFinalistIndex(categoryId, orderNumber) {
            return this.finalists.findIndex(
                finalist => finalist.categoryId === categoryId && finalist.orderNum == orderNumber
            );
        },

        /**
         * Adds a new finalist metabox
         * @param {number} categoryId
         * @param {number} orderNumber
         */
        addEmptyFinalist(categoryId, orderNumber) {
            let finalist = new Finalist({
                categoryId: categoryId,
                orderNum: orderNumber
            });
            this.finalists.push(finalist);
        },

        /**
         * Handles a successful save on server
         * @param {number} categoryId
         * @param {number} orderNumber
         * @param {string} message
         * @return {Promise} Gets finalists
         */
        handleSuccessfulSave(categoryId, orderNumber, message) {
            this.asyncResponse = new AsyncResponse(
                categoryId,
                orderNumber,
                message,
                false
            );

            return this.getFinalists();
        },

        /**
         * Handles failed saving of finalists
         * @param {number} categoryId
         * @param {number} orderNumber
         * @param {string} message
         */
        handleFailedSave(categoryId, orderNumber, message) {
            this.asyncResponse = new AsyncResponse(
                categoryId,
                orderNumber,
                message,
                true
            );

            this.finalists = this.finalists.map(finalist => new Finalist(finalist));
        }
    },

    mounted() {
        this.getAward()
            .then(this.getFinalists)
            .catch(err => {
                this.pageErrorMessage = err.message;
            })
            .finally(() => {
                this.loading = false;
                this.canStartEditing = true
            });
    }
}

</script>

<style lang="scss">
.avatar {
    display: inline-block;
    margin-bottom: 2rem;
}

button {
    margin-bottom: 1rem;
}

.empty-finalist {
    background-color: white;
    border: 1px dotted grey;
    cursor: pointer;
    height: 200px;
    width: 200px;

    &:hover {
        background-color: silver;
    }
}
</style>