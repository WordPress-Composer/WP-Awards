<template>
    <div class="date-picker">
        <div class="date-picker__group">
            <select v-model="dateChosen.day">
                <option v-for="(day, key) in days" v-bind:value="day">{{day}}</option>
            </select>

            <select v-model="dateChosen.month">
                <option v-for="(month, key) in months" v-bind:value="month.number">{{month.name}}</option>
            </select>

            <select v-model="dateChosen.year">
                <option v-for="year in years" v-bind:value="year">{{year}}</option>
            </select>
            
            <span>@ </span>
        </div>

        <div class="date-picker__group">

            <select v-model="dateChosen.hours">
                <option v-for="hour in hours" v-bind:value="hour">{{hour}}</option>
            </select>

            <select v-model="dateChosen.minutes">
                <option v-for="minute in minutes" v-bind:value="minute">{{minute}}</option>
            </select>
        </div>
    </div>
</template>

<script>
import ChosenDate from '../model/chosen-date.js';
import {getYears, hours, minutes, months, days} from '../utils/date-utils.js';

export default {
    props: {
        dateChosen: {
            type: ChosenDate,
            default() {
                return new ChosenDate;
            }
        }
    },
    watch: {
        dateChosen: {
            handler() {
                this.$emit('date-picked', this.dateChosen);
            },
            deep: true
        }
    },
    mounted() {
        this.$emit('date-picked', this.dateChosen);
    },
    data() {
        return {
            days: days(),
            months: months(),
            years: getYears(),
            hours: hours(),
            minutes: minutes()
        }
    }
}
</script>

<style lang="scss">
.date-picker__group {
    display: inline-block;
}
</style>