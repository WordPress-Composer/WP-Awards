<template>
    <div class="choice">

        <h3 class="choice__title">
            {{categoryName}}
        </h3>

        <div class="choice__option">
            <div
                :class="{ 'tab--selected' : selectedFinalistId === null }" 
                class="tab">

                <div class="tab__body tab__body--middle">
                    <h4 class="tab__title">No winner</h4>
                </div>

                <div 
                    v-on:click="select($event, null)"
                    class="tab__status"></div>
            </div>
        </div>

        <div 
            class="choice__option choice__option--selectable"
            :class="{ 'choice__option--open': metaboxOpen[candidate.finalist_id] }"
            v-for="(candidate, index) in candidates"
            :key="candidate.finalist_id">
    
            <div class="tab"
                v-on:click="toggleMetaBox($event, candidate.finalist_id)"
                :class="{ 'tab--selected' : selectedFinalistId === candidate.finalist_id}">

                <div class="tab__body">
                    <h4 class="tab__title">
                        {{ candidate.name }}
                    </h4>

                    <div class="tab__votes">
                        <span class="votes">{{ candidate.votes }}</span>
                        votes
                    </div>
                </div>

                <div class="tab__image-holder">
                    <img 
                        class="tab__image"
                        width="50"
                        height="50"
                        :src="candidate.image_url ? candidate.image_url : placeholderImage" />
                </div>

                <div 
                    v-on:click="select($event, candidate.finalist_id)"
                    class="tab__status"></div>
            </div>

            <WinnerMetabox
                class="metabox"
                :id="candidate.finalist_id"
                :categoryId="categoryId"
                :imageId="candidate.image_id"
                :imageUrl="candidate.image_url"
                :videoUrl="candidate.video_url"
                :biography="candidate.biography"
                v-show="metaboxOpen[candidate.finalist_id]"
                v-on:update-meta="updateMeta"
            />

        </div>

        <button
            v-on:click="saveWinner"
            class="btn choice__save">Update</button>

        <span 
            v-if="submitting"
            class="voting-spinner choice__loader"></span>

        <span 
            v-if="errorMessage"
            class="choice__error">{{errorMessage}}</span>

    </div>
</template>

<script>
import {maybe} from 'maybes';
import WinnerMetabox from './winner-metabox.vue';
import {PossibleWinner} from '../model/possible-winner.js';
import {Winner} from '../model/winner.js';

const resetMetaboxOpens 
    = _this => Array.isArray(_this.finalists) ? _this.finalists.reduce((accumulator, finalist) => {
        if (finalist.finalist_id !== undefined) {
            accumulator[finalist.finalist_id] = false;
        }
        return accumulator;
    }, {}) : {}

const candidateProp
    = (candidates, id, prop) => maybe(candidates.find(candidate => candidate.finalist_id === id))
        .map(data => data[prop])
        .orJust(null);

export default {
    props: {
        categoryId: {
            type: Number,
            required: true
        },
        categoryName: {
            type: String,
            required: true   
        },
        errorMessage: {
            type: String
        },
        finalists: {
            type: Array,
            required: true,
            validator(finalists)
            {
                if (finalists.length === 0) {
                    return true;
                }
                
                let res = maybe(finalists.find(finalist => finalist instanceof PossibleWinner === false))
                    .map(result => false)
                    .orJust(true);

                return res;
            }
        },
        winner: {
            type: [Winner],
            required: false
        },
        winnersMeta: {
            type: Array,
            required: true
        }
    },

    components: {
        WinnerMetabox
    },

    watch: {
        winner() {
            this.submitting = false;
        },

        errorMessage() {
            this.submitting = false;
        }
    },

    data() {
        return {
            PossibleWinner,

            candidates: this.finalists,

            selectedFinalistId: maybe(this.winner)
                .map(winner => winner.finalist_id)
                .orJust(null),

            metaboxOpen: resetMetaboxOpens(this),

            placeholderImage: encodeURI('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 299.997 299.997"><path fill="gainsboro" d="M149.996 0C67.157 0 .001 67.158.001 149.997c0 82.837 67.156 150 149.995 150s150-67.163 150-150C299.996 67.156 232.835 0 149.996 0zm.457 220.763v-.002H85.465c0-46.856 41.152-46.845 50.284-59.097l1.045-5.587c-12.83-6.502-21.887-22.178-21.887-40.512 0-24.154 15.712-43.738 35.089-43.738s35.089 19.584 35.089 43.738c0 18.178-8.896 33.756-21.555 40.361l1.19 6.349c10.019 11.658 49.802 12.418 49.802 58.488h-64.069z"/></svg>'),

            submitting: false
        }
    },

    methods: {

        /**
         * Selects a winner
         * @param {Event} event Dom event
         * @param {number} finalistId
         */
        select(event, finalistId) {
            event.preventDefault();
            this.selectedFinalistId = finalistId;
            if (!finalistId) {
                this.metaboxOpen = resetMetaboxOpens(this);
            }
        },

        /**
         * Toggles metabox (show/hide)
         * @param {Event} event Dom event
         * @param {Number} finalistId
         */
        toggleMetaBox(event, finalistId) {
            event.preventDefault();

            let currentState = this.metaboxOpen[finalistId];
            this.metaboxOpen = resetMetaboxOpens(this);

            if (finalistId) {
                this.metaboxOpen[finalistId] = !currentState;
                this.selectedFinalistId = finalistId;
            }
        },

        /**
         * Updates the candidates (aka finalists)
         */
        updateMeta({finalistId, value, fieldname, image}) {

            this.candidates = this.candidates.map(candidate => {

                if (candidate.finalist_id === finalistId && value) {
                    candidate[fieldname] = value;
                }

                if (candidate.finalist_id === finalistId && image !== null) {
                    candidate.image_id = image.id;
                    candidate.image_url = image.url;
                }
                return candidate;
            });
        },

        /**
         * Save winner
         */
        saveWinner() {
            if (this.submitting === true) {
                return;
            }

            this.submitting = true;
            
            let winnerExists = maybe(this.winner).orJust(false);

            let isCurrentWinner = maybe(this.winner)
                .map(winner => winner.finalist_id === this.selectedFinalistId)
                .orJust(false);

            let finalistId = this.selectedFinalistId;

            let data = {
                winner_id: maybe(this.winner).flatMap(winner => maybe(winner.id)).orJust(null),
                finalist_id: finalistId,
                category_id: candidateProp(this.candidates, finalistId, 'category_id'),
                biography: candidateProp(this.candidates, finalistId, 'biography'),
                image_id: candidateProp(this.candidates, finalistId, 'image_id'),
                image_url: candidateProp(this.candidates, finalistId, 'image_url'),
                video_url: candidateProp(this.candidates, finalistId, 'video_url')
            }

            let status = {
                create: !isCurrentWinner && finalistId !== null,
                update: winnerExists && isCurrentWinner,
                delete: winnerExists && finalistId === null
            }

            if (status.update) {
                this.$emit('handle-winner', 'update', data)
            } else if (status.delete) {
                this.$emit('handle-winner', 'delete', data);
            } else if (status.create) {
                this.$emit('handle-winner', 'create', data);
            } else {
                this.submitting = false;
            }

        }
    }
}
</script>

<style lang="scss" scoped>
$lightgrey: #9c9c9c;
.choice {
    $this: &;

    &:after {
        clear: both;
        content: '';
        display: table;
    }

    &__title {
        background-color: white;
        color: $lightgrey;
        font-size: 12px;
        margin: 0;
        padding: 20px;
        text-transform: uppercase;
    }

    &__option {
        background-color: white;
        padding: 20px 0;
        transition: background-color .3s ease-in;
    }

    &__option--selectable {
        #{$this}__option {

            &:hover {
                background-color: #f9f9f9;
            }

            &--open {
                background-color: #f9f9f9;
            }
        }
    }

    &__option + &__option {
        border-top: 1px solid gainsboro;
    }

    &__save {
        float: right;
        margin-top: 20px;
    }

    &__loader {
        float: right;
        margin-right: 20px;
        margin-top: 20px;
    }

    &__error {
        color: red;
        float: right;
        font-weight: bold;
        margin-right: 20px;
        margin-top: 25px;
    }
}

.tab {
    $this: &;
    clear: both;
    cursor: pointer;
    display: table;
    position: relative;
    width: 100%;

    &:after,
    &:before {
        content: '';
        display: table;
        width: 100%;
    }

    &__title {
        margin: 0 0 10px;
    }

    &__image-holder {
        display: table-cell;
        margin-right: 20px;
        overflow: hidden;
        padding-left: 55px;
        padding-right: 20px;
        width: 50px;
    }

    &__image {
        object-fit: cover;
    }

    &__body {
        display: table-cell;
        padding-left: 55px;
        vertical-align: top;
        width: auto;

        &--middle {
            vertical-align: middle;
        }
    }

    &__status {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 174.239 174.239'%3E%3Cpath fill='%23ffffff' d='M146.537 1.047a3.6 3.6 0 0 0-5.077 0L89.658 52.849a3.6 3.6 0 0 1-5.077 0L32.78 1.047a3.6 3.6 0 0 0-5.077 0L1.047 27.702a3.6 3.6 0 0 0 0 5.077l51.802 51.802a3.6 3.6 0 0 1 0 5.077L1.047 141.46a3.6 3.6 0 0 0 0 5.077l26.655 26.655a3.6 3.6 0 0 0 5.077 0l51.802-51.802a3.6 3.6 0 0 1 5.077 0l51.801 51.801a3.6 3.6 0 0 0 5.077 0l26.655-26.655a3.6 3.6 0 0 0 0-5.077L121.39 89.658a3.6 3.6 0 0 1 0-5.077l51.801-51.801a3.6 3.6 0 0 0 0-5.077L146.537 1.047z'/%3E%3C/svg%3E");
        background-color: red;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: 10px 10px;
        height: 16px;
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
    }

    &--selected {
        #{$this}__status {
            background-color: #11bb11;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 174.239 174.239'%3E%3Cpath fill='%23ffffff' d='M74.439 157.519a4.199 4.199 0 0 1-6.111.313L1.38 94.468a4.447 4.447 0 0 1-.173-6.267l21.33-22.539a4.447 4.447 0 0 1 6.267-.173L65.375 100.1a4.199 4.199 0 0 0 6.111-.313l71.447-83.015a4.445 4.445 0 0 1 6.251-.468l23.518 20.242a4.446 4.446 0 0 1 .468 6.252L74.439 157.519z'/%3E%3C/svg%3E");
        }
    }
}

.votes {
    background-color: gainsboro;
    color: slategrey;
    display: inline-block;
    font-size: 9px;
    font-weight: bold;
    margin-right: 10px;
    padding: 2px 15px;
    vertical-align: top;
}

.metabox {
    padding: 20px 20px 0 20px;
}
</style>