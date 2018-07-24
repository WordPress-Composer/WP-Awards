<template>
    <div class="s-voting-content">

        <h1>Edit Award Settings: {{title}} {{year}}</h1>

        <SubNav v-bind:id="id"></SubNav>

        <span class="voting-spinner" v-if="loading"></span>

        <div v-if="!loading">

            <span v-if="errormessage" class="error">{{errormessage}}</span>
        
            <div class="main">

                <h3>Basic info</h3>
        
                <div class="voting-form-box">
                    <label>Title:</label>
                    <input type="text" v-model="title" class="form__field" />

                    <label>Year:</label>
                    <select v-model="year" class="form__field">
                        <option 
                            v-for="option in selectableYears"
                            v-bind:key="option">{{option}}</option>
                    </select>
                </div>
                
                <h3>Dates</h3>

                <div class="voting-form-box">
                    <voting-schedule
                        v-bind:schedule="schedule"
                    ></voting-schedule>
                </div>
            </div>

            <button v-on:click="update">Update</button>

            <button v-on:click="deleteAward">Delete</button>
        </div>
    </div>
</template>

<script>
import VotingSchedule from '../components/voting-schedule.vue';
import Schedule from '../model/schedule.js';
import ScheduleItem from '../model/schedule-item.js';
import ChosenDate from '../model/chosen-date.js';
import SubNav from './award-subnav.vue';
import {getYears} from '../utils/date-utils.js';

export default {
    data() {
        return {
            id: null,
            errormessage: '',
            title: '',
            year: '',
            schedule: new Schedule,
            selectableYears: getYears(),
            loading: true
        }
    },
    
    mounted() {
        this.$api.awards.get(this.$route.params.id)
            .then(({award}) => {
                if (!award) {
                    return this.$router.push({ path: '/' });
                }

                this.id = award.id;
                this.title = award.title;
                this.year = award.year;
                this.schedule.setNominationStart(new ScheduleItem('Nomination Start', ChosenDate.fromString(award.nomination_start_date)));
                this.schedule.setNominationEnd(new ScheduleItem('Nomination End', ChosenDate.fromString(award.nomination_end_date)));
                this.schedule.setVotingStart(new ScheduleItem('Voting Start', ChosenDate.fromString(award.voting_start_date)));
                this.schedule.setVotingEnd(new ScheduleItem('Voting End', ChosenDate.fromString(award.voting_end_date)));
            })
            .catch(err => {
                this.errormessage = err.message;
            })
            .finally(() => {
                this.loading = false;
            });
    },
    methods: {

        /**
         * Updates the award
         */
        update() {
            this.loading = true;
            this.errormessage = '';
            this.$api.awards.update(this.$route.params.id, {
                title: this.title,
                year: this.year,
                nomination_start_date: this.schedule.nomination_start.date.toString(),
                nomination_end_date: this.schedule.nomination_end.date.toString(),
                voting_start_date: this.schedule.voting_start.date.toString(),
                voting_end_date: this.schedule.voting_end.date.toString()
            })
            .catch(err => {
                this.errormessage = err.message;
            })
            .finally(() => {
                this.loading = false;
            })
        },

        /**
         * Deletes the award
         */
        deleteAward() {
            alert('You are not allowed to delete an award yet');
        }
    },
    components: {
        VotingSchedule,
        SubNav
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