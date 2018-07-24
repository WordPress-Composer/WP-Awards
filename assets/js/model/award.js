/**
 * Award model (dto)
 * @author Gemma Black <gblackuk@gmail.com>
 */
export class Award {
    constructor({
        id = null,
        year = (new Date).getFullYear(),
        title = '',
        nomination_start_date = null,
        nomination_end_date = null,
        voting_start_date = null,
        voting_end_date = null,
        winners_announced_on = null,
        live = false,
        archived = false
    } = {})
    {
        this.id = id;
        this.title = title;
        this.year = year;
        this.nomination_start_date = nomination_start_date;
        this.nomination_end_date = nomination_end_date;
        this.voting_start_date = voting_start_date;
        this.voting_end_date = voting_end_date;
        this.winners_announced_on = winners_announced_on;
        this.live = live;
        this.archived = archived;
        this.categories = []
    }
}