<script>
/**
 * Winners Metabox Component
 * 
 * v-on:update-meta
 *      @example in template:
 *          v-on:update-meta="updateMeta" 
 *      @example in method handler:
 *          updateMeta(fieldname, event, catId, finalistId) { ... }
 *
 * @author Gemma Black <gblackuk@gmail.com>
 */
import WPImagePicker from './wp-image-picker.vue';

export default {
    props: {
        id: Number,
        categoryId: Number,
        imageId: Number,
        imageUrl: String,
        videoUrl: String,
        biography: String
    },

    components: {
        WPImagePicker
    },
    
    data() {
        return {
            metaboxImageId: this.imageId,
            metaboxImageUrl: this.imageUrl,
            metaboxVideoUrl: this.videoUrl,
            metaboxBiography: this.biography
        }
    },

    methods: {
        update(fieldname, event, finalistId) {

            let value = event.target !== undefined && event.target.value !== undefined
                    ? event.target.value : null;

            let image = event.id !== undefined && event.url !== undefined ? {
                id: event.id,
                url: event.url
            } : null;

            if (image) {
                this.metaboxImageId = image.id;
                this.metaboxImageUrl = image.url;
            }

            this.$emit('update-meta', {fieldname, finalistId, image, value});
        }
    }
}
</script>

<template>
    <div class="metabox">
        <div class="metabox__col">

            <div class="metabox__fields">
                <label class="metabox__label">Biography</label>
                <textarea 
                    class="metabox__textarea" 
                    type="text"
                    v-model="metaboxBiography"
                    v-on:keyup="update('biography', $event, id)"
                ></textarea>
            </div>

            <div class="metabox__fields">
                <label class="metabox__label">Youtube URL</label>
                <input
                    class="metabox__input"
                    type="text"
                    placeholder="http(s)://"
                    v-model="metaboxVideoUrl"
                    v-on:keyup="update('video_url', $event, id)"
                />
            </div>
        </div>

        <div class="metabox__col">
            <div class="metabox__fields">
                <label class="metabox__label">Image</label>
                <WPImagePicker
                    class="metabox__image" 
                    v-bind:url="metaboxImageUrl"
                    v-bind:wpId="metaboxImageId"
                    v-on:image-selected="update('image_id', $event, id)"
                />
                <p>
                    The existing finalist image will be used unless you prefer
                    to show a different one here.
                </p>
            </div>
        </div>
    </div>
</template>


<style lang="scss" scoped>

.metabox {

    &__fields {
        margin-bottom: 20px;
    }
    
    &__label {
        display: block;
        font-weight: bold;
        margin-bottom: 10px;
    }

    &__textarea {
        font-size: inherit;
        min-height: 80px;
        width: 100%;
    }

    &__input {
        font-size: inherit;
        width: 100%;
    }
}
</style>