/**
 * Gets years from a specific year until a future year
 * @param {number} from 
 * @param {number} yearsAhead 
 * @return {number[]}
 */
export const getYears = (from = 2015, yearsAhead = 3) => {
    let latestYear = thisYear() + yearsAhead;
    let range = latestYear - from + 1; // + 1 to include first year
    return Array.from(Array(range)).map((def, index) => {
        return latestYear - index;
    });
}

/**
 * Gets this year
 * @return {number}
 */
export const thisYear = () => {
    return (new Date).getFullYear();
}

/**
 * Gets list of hours
 * @return {string[]}
 */
export const hours = () => [...Array(24)].map((item, hour) => {
    return hour.toString().length < 2 ? '0' + hour.toString() : hour.toString()
});

/**
 * Gets a list of minutes
 * @return {string[]}
 */
export const minutes = () => [...Array(4)].map((item, minute) => {
    let minutes = (minute * 15);
    return minutes.toString().length < 2 ? '0' + minutes.toString() : minutes.toString();
});

/**
 * Gets a list of months
 * @return {object[]}
 */
export const months = () => [
    { number: '01', name: 'January'},
    { number: '02', name: 'February'},
    { number: '03', name: 'March'},
    { number: '04', name: 'April'},
    { number: '05', name: 'May'},
    { number: '06', name: 'June'},
    { number: '07', name: 'July'},
    { number: '08', name: 'August'},
    { number: '09', name: 'September'},
    { number: '10', name: 'October'},
    { number: '11', name: 'November'},
    { number: '12', name: 'December'}
];

/**
 * Gets a list of days
 * @return {string[]}
 */
export const days = () => [...Array(31)].map((item, index) => {
    let day = index + 1;
    return day.toString().length < 2 ? '0' + day.toString() : day.toString();
});