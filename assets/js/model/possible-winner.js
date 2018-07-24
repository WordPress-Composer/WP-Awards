/**
 * Possible winner model (dto)
 * @author Gemma Black <gblackuk@gmail.com>
 */
export class PossibleWinner {
    constructor({
        id = null,
        category_id = null,
        finalist_id = null,
        name = '',
        biography = '',
        image_id = null,
        image_url = '',
        video_url = '',
        video_type = 'youtube',
        votes = 0
    } = {}) {
        this.id = id;
        this.category_id = category_id;
        this.finalist_id = finalist_id;
        this.name = name;
        this.biography = biography;
        this.image_id = image_id;
        this.image_url = image_url;
        this.video_url = video_url;
        this.video_type = video_type;
        this.votes = votes;
    }
}