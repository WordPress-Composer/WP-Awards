<template>
    <div class="finalists__avatar">
        <div class="avatar">

            <span 
                class="avatar__remove"
                v-on:click="prepareToDelete"
            ></span>

            <div class="avatar__fieldset">
                <label class="avatar__label">Name/Organisation</label>
                <input
                    class="avatar__text-field"
                    v-model="iFinalist.name"
                    type="text"
                />
            </div>

            <div class="avatar__fieldset">
                <label class="avatar__label">Description</label>
                <textarea 
                    class="avatar__text-field" 
                    v-model="iFinalist.description"
                    type="text"
                ></textarea>
            </div>

            <div class="avatar__fieldset">
                <label class="avatar__label">Image</label>
                <WPImagePicker
                    v-bind:url="iFinalist.imageUrl" 
                    v-bind:wpId="iFinalist.imageId"
                    v-on:image-selected="onImageSelected"
                    class="avatar__image-picker" 
                />
            </div>

            <div
                class="avatar__remove-check"
                v-if="canBeDeleted"
            >
                Are you sure you want to remove them?

                <button class="btn btn--danger" v-on:click="deleteFinalist">
                    Yes, Remove!
                </button>
                
                <button v-on:click="canBeDeleted = false">
                    No
                </button>
            </div>

            <div v-if="canSave && !canBeDeleted">
                <button v-on:click="createFinalist">Add</button>
            </div>

            <div v-if="canUpdate && !canBeDeleted">
                <button v-on:click="updateFinalist">Update</button>
                <span
                    v-if="submitting" 
                    class="voting-spinner"></span>
            </div>

            <span 
                v-bind:class="{ error: asyncResponse.error, success: !asyncResponse.error }"
                v-if="asyncResponse.canShow(finalist.categoryId, finalist.orderNum)">
                {{asyncResponse.message}}
            </span>
        </div>
    </div>
</template>

<script type="text/javascript">
import {Finalist} from '../model/finalist.js';
import WPImagePicker from './wp-image-picker.vue';
import {AsyncResponse} from '../view-helper/finalist-edit.js';

export default {
    computed: {
        canSave() {
            return this.iFinalist.name !== '' && this.iFinalist.id === null;
        },

        canUpdate() {
            return this.iFinalist.name !== '' && this.iFinalist.id !== null;
        }
    },

    props: {
        componentKey: {
            type: Number
        },
        finalist: {
            type: Finalist,
            default() {
                return new Finalist
            }
        },
        asyncResponse: {
            type: AsyncResponse
        }
    },
    watch: {
        finalist() {
            this.submitting = false;
            this.iFinalist = this.finalist;
        }
    },

    data() {
        return {
            iFinalist: this.finalist,
            canBeDeleted: false,
            submitting: false,
            successMessage: ''
        }
    },

    components: {
        WPImagePicker
    },

    methods: {

        /**
         * Handles when images are selected
         * @param {any} data - comes from the vue instance
         * @todo define data strcuture
         */
        onImageSelected(data) {
            this.iFinalist.imageId = data.id;
            this.iFinalist.imageUrl = data.url
        },

        /**
         * Prepares finalist for deltion
         */
        prepareToDelete() {
            if (this.iFinalist.id === null) {
                return this.$emit('unset-finalist-requested', this.iFinalist, this.componentKey);
            }
            this.canBeDeleted = true;
        },

        /**
         * Deletes finalist
         */
        deleteFinalist() {
            if (this.submitting) {
                return;
            }
            this.submitting = true;
            this.$emit('delete-finalist-requested', this.iFinalist, this.componentKey);
        },

        /**
         * Creates a finalist
         */
        createFinalist() {
            if (this.submitting) {
                return;
            }
            this.submitting = true;
            this.$emit('create-finalist-requested', this.iFinalist, this.componentKey);
        },

        /**
         * Updates a finalist
         */
        updateFinalist() {
            if (this.submitting) {
                return;
            }
            this.submitting = true;
            this.$emit('update-finalist-requested', this.iFinalist, this.componentKey);
        }
    }
}
</script>

<style lang="scss" scoped>
.voting-spinner {
    display: inline-block;
    vertical-align: middle;

    &:after,
    &:before {
        background-color: white;
    }
}
.success {
    color: green;
}
</style>