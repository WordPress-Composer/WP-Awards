import {url} from '../utils/api.js';

/**
 * @author Gemma Black <gblackuk@gmail.com>
 */
export default class NominationsService {
    getCSVURL(id) {
        return url + '/award/' + id + '/nominations?format=csv';
    }
}