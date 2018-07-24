import ChosenDate from './chosen-date';

/**
 * Schedule Item which is a item name, and its corresponding date
 * @property {string} name
 * @property {ChosenDate} date
 */
export default class {

    /**
     * @param {string} name 
     * @param {ChosenDate} date 
     */
    constructor(name, date) {
        if (date instanceof ChosenDate === false) {
            throw new Error('Date must be an instance of ChosenDate');
        }

        this.name = name;
        this.date = date;
    }

    /**
     * @param {ChosenDate} date
     */
    setDate(date) {
        if (date instanceof ChosenDate === false) {
            throw new Error('Date must be an instance of ChosenDate');
        }

        this.date = date;
    }
}