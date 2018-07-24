/**
 * Winners meta model (dto)
 * @author Gemma Black <gblackuk@gmail.com>
 */
export class WinnersMeta {
    constructor({
        award_id,
        biography,
        finalist_id,
        id,
        image_id,
        image_url,
        video_type = 'youtube',
        video_url
    }) {
        this.award_id = award_id;
        this.biography = biography;
        this.finalist_id = finalist_id;
        this.id = id;
        this.image_id = image_id;
        this.image_url = image_url;
        this.video_type = video_type;
        this.video_url = video_url;
    }
}