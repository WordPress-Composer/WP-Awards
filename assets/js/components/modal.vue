<template>
    <div class="voting-modal" v-show="isActive">
        <div class="voting-modal__box">
            <div class="voting-modal__box-content">
                {{message}}
            </div>
            <button class="voting-modal__box-button" v-on:click="actionModal">
                {{cta}}
            </button>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        isActive: {
            type: Boolean,
            default: false
        },
        message: {
            type: String,
            default: ''
        },
        cta: {
            type: String,
            default: 'Close'
        }
    },
    methods: {
        actionModal() {
            this.$emit('modal-button-clicked');
        }
    }
}
</script>

<style lang="scss">
.voting-modal {
    background-color: rgba(119, 136, 153, 0.7); // lightslategrey
    box-sizing: border-box;
    font-size: 1.25em;
    height: 100%;
    left: 0;
    padding: 1rem;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 999999;

    &__box {
        background-color: white;
        border-radius: 3px;
        box-sizing: border-box;
        margin: 1rem auto;
        max-width: 350px;
        overflow: hidden;
        padding: 3rem 3rem 6rem 3rem;
        position: relative;
        transform: translateY(-50%);
        top: 40%;
        width: 100%;
    }

    &__box-content {
        color: steelblue;
    }

    &__box-button {
        background-color: gainsboro;
        border: 0;
        border-radius: 0;
        bottom: 0;
        color: steelblue;
        cursor: pointer;
        left: 0;
        margin: 0;
        padding: 1rem;
        position: absolute;
        width: 100%;

        &:focus,
        &:active {
            outline: 0;
        }

        &:hover {
            opacity: .8;
        }
    }
}
</style>