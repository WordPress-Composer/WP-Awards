<template>
    <div class="s-voting-content">
        <h1 class="">Categories</h1>

        <p class="error" v-show="errorMessage">{{ errorMessage }}</p>

        <span class="voting-spinner" v-if="loading"></span>

        <div class="admin-board">
            <div class="admin-board__main">
                <div class="panel">
                    <div class="panel__item" v-show="categories.length === 0 && !loading">
                        <p>Hi there,</p>
                        <p>Please create your first award category.</p>
                    </div>
                </div>
                <ul class="panel">
                    <li 
                        class="panel__item" 
                        v-for="category in categories"
                        v-bind:key="category.id">
                        {{category.name}}
                        <button class="panel__item-button" v-on:click="edit(category.id)">
                            Edit
                        </button>
                    </li>
                </ul>
            </div>
            <div class="admin-board__side">
                <div class="voting-form-box">
                    <h3 class="voting-form-box__title">Add new category</h3>
                    <label>Name: 
                        <input type="text" class="voting-form-box__input-field" v-model="newCategoryName" />
                    </label>

                    <label>Short label: 
                        <input type="text" class="voting-form-box__input-field" v-model="newCategoryLabel" />
                    </label>

                    <label>Description: 
                        <textarea class="voting-form-box__input-field" v-model="newCategoryDescription" ></textarea>
                    </label>

                    <button v-on:click="addNew" class="voting-form-box__button">Add New</button>

                    <p>These categories are available across multiple awards. If you make a mistake,
                    simply delete it and create another one. However, you cannot delete a category 
                    that has been assigned to an award.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            errorMessage: '',
            newCategoryName: '',
            newCategoryDescription: '',
            newCategoryLabel: '',
            submitting: false,
            categories: [],
            loading: true
        }
    },
    mounted() {
        this.$api.categories.all()
            .then(({categories}) => {
                this.categories = categories;
            })
            .catch(err => {
                this.errorMessage = err.message;
            })
            .finally(() => {
                this.loading = false;
            });
    },
    methods: {

        /**
         * Redirects to edit page 
         * @param {number} id
         */
        edit(id) {
            this.$router.push('edit/' + id);
        },

        /**
         * Adds a new category
         */
        addNew() {
            if (this.submitting) {
                return;
            }

            this.submitting = true;

            this.$api.categories
                .create({
                    name: this.newCategoryName,
                    description: this.newCategoryDescription,
                    short_label: this.newCategoryLabel
                })
                .then(({category}) => {
                    this.categories.push(category);
                    this.newCategoryName = '';
                    this.newCategoryLabel = '';
                    this.newCategoryDescription = '';
                    
                })
                .catch(err => {
                    this.errorMessage = err.message;
                })
                .finally(() => {
                    this.submitting = false;
                })
        }
    }
}
</script>