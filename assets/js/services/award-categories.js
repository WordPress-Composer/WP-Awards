import axios from 'axios';
import handleError from '../utils/error-response.js';
import {url} from '../utils/api.js';
import Category from '../model/category.js';

export const dto = data => {
    return new Category(
        data.id,
        data.name,
        data.description,
        data.slug,
        data.shortLabel
    );
}

export const mapCollection = response => Promise.resolve(response.data.map(dto));

/**
 * Award categories service
 * @author Gemma Black <gblackuk@gmail.com>
 */
export default class AwardCategoriesService {

    /**
     * Get all award categories
     */
    all(id) {
        return axios.get(url + '/award/' + id + '/categories')
            .then(mapCollection)
            .catch(handleError);
    }
}