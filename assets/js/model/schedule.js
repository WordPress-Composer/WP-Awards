import ScheduleItem from './schedule-item';
import ChosenDate from './chosen-date';

/**
 * Schedule model
 * @author Gemma Black <gblackuk@gmail.com>
 */
export default class {

    constructor() {
        this.nomination_start = new ScheduleItem('Nomination Start', ChosenDate.today());
        this.nomination_end = new ScheduleItem('Nomination End', ChosenDate.today());
        this.voting_start = new ScheduleItem('Voting Start', ChosenDate.today());
        this.voting_end = new ScheduleItem('Voting End', ChosenDate.today());
    }

    /**
     * Set nomination start date and time
     * @param {ScheduleItem} nomination_start 
     */
    setNominationStart(nomination_start)
    {
        this.checkIsScheduleItem(nomination_start);
        this.nomination_start = nomination_start;
    }

    /**
     * Sets nomination end date and time
     * @param {ScheduleItem} nomination_end 
     */
    setNominationEnd(nomination_end)
    {
        this.checkIsScheduleItem(nomination_end);
        this.nomination_end = nomination_end;
    }

    /**
     * Sets voting start date and time
     * @param {ScheduleItem} voting_start 
     */
    setVotingStart(voting_start)
    {
        this.checkIsScheduleItem(voting_start);
        this.voting_start = voting_start;
    }

    /**
     * Sets voting end date and time
     * @param {ScheduleItem} voting_end 
     */
    setVotingEnd(voting_end)
    {
        this.checkIsScheduleItem(voting_end);
        this.voting_end = voting_end;
    }

    /**
     * Checks item is instanceof ScheduleItem
     * @param {ScheduleItem} item 
     */
    checkIsScheduleItem(item)
    {
        if (item instanceof ScheduleItem === false) {
            throw new Error('Schedule Item must be an instance of the Schedule Item class');
        }
    }
}