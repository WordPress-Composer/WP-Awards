/**
 * Winner model (dto)
 * @author Gemma Black <gblackuk@gmail.com>
 */
export class Winner {
    constructor({
        id = null,
        finalist_id,
        award_id,
        category_id,
        biography = '',
        youtube_url = '',
        image_id = '',
        image_url = ''
    }) {
        this.id = id;
        this.finalist_id = finalist_id;
        this.award_id = award_id;
        this.category_id = category_id;
        this.biography = biography;
        this.youtube_url = youtube_url;
        this.image_id = image_id;
        this.image_url = image_url;
    }
}