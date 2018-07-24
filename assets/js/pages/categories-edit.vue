<template>
<div class="s-voting-content">
    <h1>Edit Category</h1>

    <span class="voting-spinner" v-if="loading"></span>
    
    <span class="error">{{errorMessage}}</span>
    <p v-if="successMessage">{{successMessage}}</p>

    <div class="voting-form-box" v-if="!loading">   
        <label>Name (not editable):</label>
        <input type="text" v-model="category.name" class="form__field" readonly />

        <label>Slug (not editable):</label>
        <input type="text" v-model="category.slug" class="form__field" readonly />

        <label>Short label:</label>
        <input 
            type="text" 
            v-model="category.short_label" 
            v-on:focus="clearGlobalMessages"
            class="form__field" />

        <label>Description:</label>
        <textarea 
            type="text" 
            v-model="category.description"
            v-on:focus="clearGlobalMessages"
            class="form__field"></textarea>

        <button v-on:click="update">Update</button>
    </div>

    <div class="voting-form-box" v-if="!loading">  
        <button class="danger" v-on:click="deleteCategory">Delete</button> (if you are sure)
    </div>
</div>
</template>

<script type="text/javascript">
export default {
    data() {
        return {
            category: {}, // Category Model
            errorMessage: '',
            successMessage: '',
            submitting: false,
            loading: true
        }
    },
    mounted() {
        this.$api.categories.get(this.$route.params.id)
            .then(({category}) => {
                if (!category) {
                    this.$router.push({ path: '/' });
                }
                this.category = category;
            })
            .catch(err => {
                this.errorMessage = err.message;
            })
            .finally(() => {
                this.loading = false;
            })
    },
    methods: {

        clearGlobalMessages() {
            this.errorMessage = '';
            this.successMessage = '';
        },

        update() {
            if (this.submitting) {
                return;
            }

            this.submitting = true;

            this.$api.categories.update(this.$route.params.id, this.category)
                .then(response => {
                    this.successMessage = 'This category has updated successfully';
                })
                .catch(err => {
                    this.errorMessage = err.message;
                })
                .finally(() => {
                    this.submitting = false;
                })
        },

        deleteCategory() {

            this.submitting = true;

            this.$api.categories.delete(this.$route.params.id)
                .then(response => {
                    this.$router.push({ path: '/' });
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

<style lang="scss" scoped>
.voting-form-box {
    margin-bottom: 1rem;
}

button {
    margin: 0;

    &.danger {
        background-color: red;
        color: white;
    }
}
</style>