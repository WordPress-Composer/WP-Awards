import {Award} from '../model/award';

/**
 * Sorts award by year ascending
 * @param {Award} a 
 * @param {Award} b 
 */
export let sortAwardsByYear = (ascending) => (a, b) => {
    let A = a.year;
    let B = b.year;
    return sort(A, B, !ascending);
}

/**
 * Sorts the award by year descending
 * @param {Award} a 
 * @param {Award} b 
 */
export let sortAwardsByYearReversed = (a, b) => {
    let A = a.year;
    let B = b.year;
    return sort(A, B);
}

export const sortAwardsByCategories = (ascending) => (a, b) => {
    let A = a.categories.length;
    let B = b.categories.length;
    return sort(A, B, !ascending);
}

export const sortStatus = (ascending) => (a, b) => {
    let A = a.archived + '' + a.live;
    let B = b.archived + '' + b.live;
    return sort(A, B, !ascending);
}

export const sortAwardsBySubmissions = (ascending) => (a, b) => {
    let A = a.submissions;
    let B = b.submissions;
    return sort(A, B, !ascending);
}

export const sortAwardsByVotes = (ascending) => (a, b) => {
    let A = a.votes;
    let B = b.votes;
    return sort(A, B, !ascending);
}

export const sortAwardsByTitle = (ascending) => (a, b) => {
    let A = a.title;
    let B = b.title;
    return sort(A, B, !ascending);
}

const sort = (A, B, reverse = false) => {
    let reversable = reverse ? -1 : 1;
    if (A > B) {
        return -1 * reversable;
    } else if (A < B) {
        return 1 * reversable;
    }
    return 0;
}