import axios from 'axios';
import handleError from '../utils/error-response.js';
import {url} from '../utils/api.js';
import {Winner} from '../model/winner.js';

export const mapToModel = (input) => {
    return new Winner({
        id: input.id,
        name: input.name,
        award_id: input.awardId,
        finalist_id: input.finalistId,
        category_id: input.categoryId,
        biography: input.biography,
        youtube_url: input.videoUrl,
        image_id: input.imageId,
        image_url: input.imageUrl
    })
}

export const mapCollection = response => Promise.resolve(response.data.map(mapToModel));

export const mapSingle = response => Promise.resolve({
    winner: mapToModel(response.data),
    response
});

/**
 * API request service for app winners
 * @author Gemma Black <gblackuk@gmail.com>
 */
export default class {
    allByAwardId(id) {
        return axios.get(url + '/award/' + id + '/winners')
            .then(mapCollection)
            .catch(handleError);
    }

    create({
        category_id, 
        award_id, 
        finalist_id, 
        biography, 
        image_id, 
        video_url
    }) {

        let form = new FormData;
        form.append('action', 'create_winner');
        form.append('finalist_id', finalist_id);
        form.append('award_id', award_id);
        form.append('category_id', category_id);
        form.append('biography', biography);
        form.append('image_id', image_id);
        form.append('video_url', video_url);
        return axios.post(ajaxurl, form)
            .then(mapSingle)
            .catch(handleError);
    }

    update(...args) {
        return this.create(...args);
    }

    delete({winner_id}) {
        let form = new FormData;
        form.append('action', 'delete_winner');
        form.append('winner_id', winner_id);
        return axios.post(ajaxurl, form)
            .then(response => ({
                winnerId: winner_id
            }))
            .catch(handleError);
    }

}