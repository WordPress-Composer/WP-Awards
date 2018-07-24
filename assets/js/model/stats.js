/**
 * Statistics model
 * @author Gemma Black <gblackuk@gmail.com>
 */
export class Stats {
    constructor({
        awardId = null,
        submissions = '-',
        votes = '-'
    }) {
        this.awardId = awardId;
        this.submissions = submissions;
        this.votes = votes;
    }
}