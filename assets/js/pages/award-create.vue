<template>
    <div class="s-voting-content main">
        <h1>Create Award</h1>

        <span v-if="errormessage" class="error">{{errormessage}}</span>
    
        <h3>Basic info</h3>
    
        <div class="voting-form-box">
            <label>Title:</label>
            <input type="text" v-model="title" class="form__field" />

            <label>Year:</label>
            <select v-model="year" class="form__field">
                <option 
                    v-for="(option, index) in selectableYears"
                    v-bind:key="index">{{option}}</option>
            </select>
        </div>

        <div class="voting-form-box">
            <voting-schedule
                v-on:voting-scheduled="setNominations"
            ></voting-schedule>
        </div>

        <button v-on:click="create">Create New Award</button>

        <Modal
            v-bind:isActive="modalActive"
            v-bind:message="modalMessage"
            cta="ok"
            v-on:modal-button-clicked="redirectUser"
        ></Modal>
    </div>
</template>

<script>
import VotingSchedule from '../components/voting-schedule.vue';
import {getYears, thisYear} from '../utils/date-utils.js';
import Modal from '../components/modal.vue';

export default {
    data: function() {
        return {
            submitting: false,
            errormessage: '',
            title: '',
            year: thisYear(),
            schedule: {},
            selectableYears: getYears(),
            modalActive: false,
            modalMessage: '',
            saved: {}
        }
    },
    methods: {

        /**
         * Set nominations
         * @param {Object} data
         */
        setNominations(data) {
            this.schedule = data;
        },

        /**
         * Creates a new award
         */
        create() {
            if (this.submitting) {
                return false;
            }

            this.submitting = true;

            this.$api.awards.create({
                title: this.title,
                year: this.year,
                nomination_start_date: this.schedule.nomination_start.date.toString(),
                nomination_end_date: this.schedule.nomination_end.date.toString(),
                voting_start_date: this.schedule.voting_start.date.toString(),
                voting_end_date: this.schedule.voting_end.date.toString()
            })
            .then(response => {
                this.modalActive = true;
                this.modalMessage = 'Your content has been saved';
                this.saved = response.data;
            })
            .catch(err => {
                this.errormessage = err.message;
            })
            .finally(() => {
                this.submitting = false;
            })
        },

        /**
         * Redirects user
         */
        redirectUser() {
            this.$router.push({ path: '/award/' + this.saved.id });
        }
    },

    components: {
        VotingSchedule,
        Modal
    }
}
</script>

<style lang="scss" scoped>
.main {
    @media only screen and (min-width: 768px) {
        width: (100% / 2);
    }
}

.voting-form-box {
    margin-bottom: 2rem;
}
</style>