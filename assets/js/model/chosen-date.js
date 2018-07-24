/**
 * Chosen date model
 * @author Gemma Black <gblackuk@gmail.com>
 */
export class ChosenDate {
    constructor(input) {
        input = input || {};
        this.day = input.day ? this.doubleDigitize(input.day) : '01';
        this.month = input.month ? this.doubleDigitize(input.month) : '01';
        this.year = input.year ? input.year.toString() : (new Date()).getFullYear();
        this.hours = input.hours ? this.doubleDigitize(input.hours) : '00';
        this.minutes = input.minutes ? this.doubleDigitize(input.minutes) : '00';
    }

    /**
     * Converts a number into a double digit string
     * @param {any} item 
     * @return {string}
     */
    doubleDigitize(item) {
        let number = parseInt(item);
        return number.toString().length < 2 ? '0' + number.toString() : number.toString();
    }

    /**
     * Converts date into format YYYY-MM-DD HH:MM
     * @return {string}
     */
    toString() {
        return this.year
            + '-' + this.doubleDigitize(this.month)
            + '-' + this.doubleDigitize(this.day)
            + ' ' + this.doubleDigitize(this.hours)
            + ':' + this.doubleDigitize(this.minutes);
    }

    /**
     * When format of string is YYYY-MM-DD HH:MM:SS
     * @todo add validation to structure of string
     * @param dateString 
     */
    static fromString(dateString) {
        let datePart = dateString.split(' ')[0].split('-');
        let timePart = dateString.split(' ')[1].split(':');
        return new this({
            year: datePart[0],
            month: datePart[1],
            day: datePart[2],
            hours: timePart[0],
            minutes: timePart[1]
        });
    }

    /**
     * Creates a date from today's date
     * @return {ChosenDate}
     */
    static today() {
        let date = new Date;
        return new this({
            year: date.getUTCFullYear(),
            month: date.getUTCMonth() + 1,
            day: date.getUTCDate(),
            hours: '00',
            minutes: '00'
        });
    }
}

export default ChosenDate;