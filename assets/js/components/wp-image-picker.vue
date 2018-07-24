<script type="text/javascript">
/**
 * v-on:image-selected
 *      @returns { url: {string}, id: {int} }
 * @todo internal imageId changes
 */
export default {
    props: {
        url: String,
        wpId: Number
    },

    data() {
        return {
            width: 0,
            height: 0,
            wpImageId: this.wpId || null,
            image: {},
            wpIframe: null,
            imageVisible: false
        }
    },

    computed: {},

    mounted() {
        this.image = this.$refs.selectedimage;
        this.handleUrlChange();
        this.handleWpIdChange();
    },

    watch: {
        url() {
            this.handleUrlChange();
        },
        wpImageId() {
            this.handleWpIdChange();
        }
    },

    methods: {

        handleUrlChange() {
            this.image.src = this.url;
            this.imageVisible = this.url !== '' && this.url !== null;
        },
        handleWpIdChange() {
            this.wpImageId = this.wpId || null
        },
        openMedia: function() {
            if (!this.wpIframe) {
                this.wpIframe = wp.media({
                    title: 'Select image',
                    multiple: false,
                    library: {
                        type: 'image'
                    },
                    button: {
                        text: 'Use selected image'
                    }
                });
            }

            this.wpIframe.on('select', this.populateImageWindow);
            this.wpIframe.open();
        },

        /**
        * Gathers the images selected from the media selection window
        * Loop through these images (only can be one image) and assign the values to inputs
        * This is so they can be collected out of scope later when saving the event
        */
        populateImageWindow: function() {
            var _this = this;

            var selection = this.wpIframe.state().get('selection');
            
            if (!selection) {
                return;
            }

            selection.each(function(attachment) {
                _this.image.src = attachment.attributes.url;
                _this.imageVisible = true;
                _this.wpImageId = attachment.attributes.id;
                _this.width = attachment.attributes.sizes.full.width;
                _this.height = attachment.attributes.sizes.full.height;
                var ratio = _this.height / _this.width;
                _this.image.width = 300;
                _this.image.height = 300 * ratio;
            });

            this.$emit('image-selected', {
                id: this.wpImageId,
                url: this.image.src
            });
        },

        /**
        * Triggered when user clicked remove image button
        * Hide the image element and set the image for the vent object to null
        */
        removeImage: function() {
            this.imageVisible = false;
            this.wpImageId = null;
            this.width = null;
            this.height = null;

            this.$emit('image-selected', {
                id: null,
                url: ''
            });
        }

    }
}
</script>

<style lang="scss" scoped>
img {
    display: block;
    height: auto;
    margin-top: 20px;
    max-width: 250px;
}
</style>

<template>
    <div class="addEvent__fieldset">
        <input hidden type="text" class="media-input" id="idInput"/>
        <input hidden type="text" class="media-input" id="imgX"/>
        <input hidden type="text" class="media-input" id="imgY"/>

        <button
            v-on:click="openMedia"
            v-show="!wpImageId"
            class="btn"
        >
            Select image
        </button>

        <button
            v-on:click="removeImage"
            v-show="wpImageId"
            class="btn"
            id="removeImgBtn"
        >
            Remove image
        </button>

        <img :style="!imageVisible ? 'display: none': ''" ref="selectedimage" src=""/>
    </div>
</template>