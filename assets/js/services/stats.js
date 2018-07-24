import axios from 'axios';
import {url} from '../utils/api.js';
import {Stats} from '../model/stats.js';
import handleError from '../utils/error-response.js';

/**
 * @author Gemma Black <gblackuk@gmail.com>
 */

/**
 * Maps response to the data model
 * @param {*} data
 * @return Stats
 */
export const mapToModel = (data) => {
    return new Stats({
        awardId: data.id,
        submissions: data.submissions,
        votes: data.votes
    })
}

export const mapCollection = (response) => Promise.resolve({
    stats: response.data.map(mapToModel)
});

export const mapSingle = response => Promise.resolve({
    stats: mapToModel(response.data)
});

export default class StatsService {
    get(id) {

        if (!wpApiSettings && !wpApiSettings.nonce) {
            return Promise.reject('wpApiSettings must be set for security');
        }

        return axios.get(url + '/award/' + id + '/statistics', {
            withCredentials: true,
            headers: {
                'X-WP-Nonce': wpApiSettings.nonce
            }
        })
        .then(mapSingle)
        .catch(handleError)
    }

    all() {

        if (!wpApiSettings && !wpApiSettings.nonce) {
            return Promise.reject('wpApiSettings must be set for security');
        }

        return axios.get(url + '/award/statistics', {
            withCredentials: true,
            headers: {
                'X-WP-Nonce': wpApiSettings.nonce
            }
        })
        .then(mapCollection)
        .catch(handleError)
    }
}