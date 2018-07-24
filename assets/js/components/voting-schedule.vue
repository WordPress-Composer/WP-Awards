<template>
    <div>
        <div 
            class="schedule-date" 
            v-for="(event, index) in schedule"
            v-bind:key="index">
            <label class="schedule-date__label">
                {{event.name}}
            </label>
            <div class="schedule-date__picker">
                <date-time-picker 
                    v-bind:dateChosen="schedule[index].date"
                    v-on:date-picked="setDate(index, $event)"
                ></date-time-picker>
            </div>
        </div>
    </div>
</template>

<script>
import DateTimePicker from './date-time-picker.vue';
import ChosenDate from '../model/chosen-date.js';
import Schedule from '../model/schedule.js';

export default {
    props: {
        schedule: {
            type: [Schedule, null],
            default: function() {
                return new Schedule
            }
        }
    },
    data: function() {
        return {}
    },
    computed: {
        clonedSchedule() {
            return Object.assign({}, this.schedule);
        }
    },
    watch: {
        schedule: {
            handler() {
                this.$emit('voting-scheduled', this.clonedSchedule);
            },
            deep: true
        }
    },
    components: {
        DateTimePicker
    },
    mounted() {
        this.$emit('voting-scheduled', this.clonedSchedule);
    },
    methods: {
        setDate(key, date) {
            this.schedule[key].setDate(date);
        }
    }
}
</script>

<style lang="scss">
    .schedule-date {
        display: block;

        &__label {
            display: inline-block;
        }

        &__picker {
            display: inline-block;
        }

        + .schedule-date {
            margin-top: 20px;
        }
    }
</style>