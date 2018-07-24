<template>
<div class="s-voting-content">

    <h1>Select Award Categories: {{award.title}} {{award.year}}</h1>

    <SubNav v-bind:id="award.id"></SubNav>

    <span class="voting-spinner" v-if="loading"></span>

    <div class="panel">
        <div class="panel__item" v-show="allCategories.length === 0 && !loading">
            <p>There are no categories to choose from. Please add some categories first.</p>
        </div>
    </div>

    <div class="message">{{message}}</div>

    <div v-show="errorMessage !== ''" class="error">{{errorMessage}}</div>
    
    <div class="admin-board" v-show="!loading">
        <div class="admin-board__main">
            <div class="panel">
                <div 
                    class="panel__item" 
                    v-for="(category, index) in allCategories"
                    v-bind:key="index">
                    
                    <input type="checkbox" 
                        v-bind:value="category.id" 
                        v-bind:checked="categories.find(id => id === category.id)"
                        v-on:click="update($event)"
                    />

                    {{category.name}}

                </div>
            </div>
        </div>
    </div>
    
</div>
</template>

<script type="text/javascript">
import Category from '../model/category.js';
import SubNav from './award-subnav.vue';
import {Award} from '../model/award.js';

/**
 * @todo duplication of code in create and delete categories
 */
export default {
    data() {
        return {
            message: '',
            award: new Award,
            categories: [],
            allCategories: [],
            errorMessage: '',
            loading: true,
            isSubmitting: false
        }
    },
    watch: {
        categories() {
            this.errorMessage = ''
        }
    },
    components: {
        SubNav
    },
    mounted() {
        this.$api.awards.get(this.$route.params.id)
            .then(response => {
                this.award = response.award
                this.categories = response.categories.map(cat => cat.id);
            })
            .then(this.$api.categories.all)
            .then(({categories}) => {
                this.allCategories = categories;
            })
            .catch(err => {
                this.errorMessage = err.message;
            })
            .finally(err => {
                this.loading = false;
            });
    },
    methods: {

        /**
         * Updates a category
         * @param {Event} e
         */
        update(e) {
            if (this.isSubmitting) {
                return;
            }

            this.isSubmitting = true;

            let tickedId = parseInt(e.target.value);
            let inCategory = this.categories.find(id => id === tickedId);

            let update = (inCategory) 
                ? this.deleteCategory.bind(this)
                : this.createCategory.bind(this);
        
            update(e.target.value, e.target)
                .finally(() => {
                    this.isSubmitting = false;
                })
        },

        /**
         * Deletes a category server
         * @param {number} categoryId
         * @param {Dom} inputElement
         */
        deleteCategory(categoryId, inputElement) {
            return this.$api.categories.deleteAwardCategory(this.award.id, categoryId)
                .then(res => {
                    this.message = 'Category unselected';
                    this.categories = this.categories.filter(res => res != categoryId);
                    inputElement.checked = false;
                })
                .catch(err => {
                    this.errorMessage = err.message;
                    inputElement.checked = true;
                })
        },

        /**
         * Creates category on server
         * @param {number} categoryId
         * @param {Dom} inputElement
         */
        createCategory(categoryId, inputElement) {
            return this.$api.categories.createAwardCategory(this.award.id, categoryId)
                .then(res => {
                    this.message = 'Category selected';
                    this.categories.push(parseInt(categoryId));
                    inputElement.checked = true;
                })
                .catch(err => {
                    this.errorMessage = err.message;
                    inputElement.checked = false;
                })
        }
    }
}
</script>

<style lang="scss" scoped>
.message {
    margin-bottom: 1rem;
}
.panel {
    margin-bottom: 1rem;
}
</style>