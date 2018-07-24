import axios from 'axios';
import {url} from '../utils/api.js';
import {Finalist} from '../model/finalist.js';
import handleError from '../utils/error-response.js';

export const mapToModel = (input) => {
    return new Finalist({
        id: input.id,
        name: input.name,
        categoryId: input.categoryId,
        imageUrl: input.imageUrl,
        description: input.biography,
        orderNum: input.orderNumber,
        imageId: input.imageId,
        votes: input.votes
    })
}

export const mapCollection = (response) => Promise.resolve({
    finalists: response.data.map(mapToModel)
});

export const mapSingle = response => Promise.resolve({
    finalist: mapToModel(response.data)
});

/**
 * API request service for app finalists
 * @author Gemma Black <gblackuk@gmail.com>
 */
export default class {

    update(finalist) {
        if (finalist instanceof Finalist === false) {
            throw new Error('finalist must be instanceof Finalist');
        }
        let form = new FormData;
        form.append('action', 'patch_award_finalist');
        form.append('award_finalist_id', finalist.id);
        form.append('name', finalist.name);
        form.append('biography', finalist.description);
        form.append('image_id', finalist.imageId);
        form.append('order_number', finalist.orderNum);

        return axios.post(ajaxurl, form)
            .then(mapSingle)
            .catch(handleError)

    }

    create(award_id, finalist) {
        if (finalist instanceof Finalist === false) {
            throw new Error('finalist must be instanceof Finalist');
        }
        let form = new FormData;
        form.append('action', 'create_award_finalist');
        form.append('name', finalist.name);
        form.append('biography', finalist.description);
        form.append('image_id', finalist.imageId);
        form.append('order_number', finalist.orderNum);
        form.append('category_id', finalist.categoryId);
        form.append('award_id', award_id);

        return axios.post(ajaxurl, form)
            .then(mapSingle)
            .catch(handleError)

    }

    delete(id) {
        let form = new FormData;
        form.append('action', 'delete_award_finalist');
        form.append('id', id);
        return axios.post(ajaxurl, form)
            .catch(handleError)
    }

    allByAwardId(id) {
        return axios.get(url + '/award/' + id + '/finalists')
            .then(mapCollection)
            .catch(handleError)
    }
}