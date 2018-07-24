import axios from 'axios';
import handleError from '../utils/error-response.js';
import {url} from '../utils/api.js';
import Category from '../model/category.js';

export const mapToModel = (input) => {
    return new Category(
        input.id,
        input.name,
        input.description,
        input.slug,
        input.shortLabel
    );
}

export const mapSingle = (response) => Promise.resolve({
    category: mapToModel(response.data)
});

export const mapCollection = (response) => Promise.resolve({
    categories: response.data.map(mapToModel)
});

/**
 * Categories service
 * @author Gemma Black <gblackuk@gmail.com>
 */
export default class {

    /**
     * Creates a category
     * @param object
     */
    create({name, description, short_label}) {
        
        let form = new FormData;
        form.append('action', 'create_category');
        form.append('name', name);
        form.append('description', description);
        form.append('short_label', short_label);
            
        return axios.post(ajaxurl, form)
            .then(mapSingle)
            .catch(handleError);
    }


    /**
     * Gets all categories
     * @return promise of response data
     */
    all() {
        return axios.get(url + '/categories')
            .then(mapCollection)
            .catch(handleError);
    }


    /**
     * Deletes an id
     * @param int id 
     * @return promise
     */
    delete(id) {
        let form = new FormData;
        form.append('action', 'delete_category');
        form.append('category_id', id);
        return axios.post(ajaxurl, form)
            .catch(handleError);
    }

    get(id) {
        return axios.get(url + '/category/' + id)
            .then(mapSingle)
            .catch(handleError)
    }

    update(id, category) {
        if (category instanceof Category === false) {
            throw new Exception('category must be instanceof Category model');
        }
        let form = new FormData;
        form.append('action', 'patch_category');
        form.append('description', category.description);
        form.append('short_label', category.short_label);
        form.append('id', category.id);

        return axios.post(ajaxurl, form)
            .catch(handleError);
    }


    createAwardCategory(awardId, categoryId) {
        let form = new FormData;
        form.append('action', 'create_award_category');
        form.append('category_id', categoryId);
        form.append('award_id', awardId);
        return axios.post(ajaxurl, form)
            .then(mapCollection)
            .catch(handleError);
    }


    deleteAwardCategory(awardId, categoryId) {
        let form = new FormData;
        form.append('action', 'delete_award_category');
        form.append('category_id', categoryId);
        form.append('award_id', awardId);
        return axios.post(ajaxurl, form)
            .catch(handleError);
    }
}