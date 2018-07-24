import axios from 'axios';
import handleError from '../utils/error-response.js';
import {url, axiosWpAuthAccess} from '../utils/api.js';
import {Award} from '../model/award.js';
import {Category} from '../model/category.js';
import {Winner} from '../model/winner.js';
import {mapToModel as mapToFinalist} from './finalists.js';
import {mapToModel as mapToCategory} from './categories.js';

/**
 * 
 * @author Gemma Black <gblackuk@gmail.com>
 */
export const mapAward = data => {
    return new Award({
        id: data.id,
        title: data.title,
        year: parseInt(data.year),
        nomination_start_date: data.nominationStartDate,
        nomination_end_date: data.nominationEndDate,
        voting_start_date: data.votingStartDate,
        voting_end_date: data.votingEndDate,
        live: data.isLive,
        archived: data.isArchived,
        winners_announced_on: data.winnersAnnouncedOn
    });
}

export const mapAwardWinner = winner => {
    return new Winner({
        id: winner.id,
        award_id: winner.awardId,
        finalist_id: winner.finalistId,
        category_id: winner.categoryId,
        biography: winner.biography,
        youtube_url: winner.videoUrl,
        image_id: winner.imageId,
        image_url: winner.imageUrl
    })
}

export const mapWinnersMeta = meta => {
    return {
        id: meta.id,
        finalist_id: meta.finalistId,
        biography: meta.biography,
        video_url: meta.videoUrl,
        video_type: meta.videoType,
        image_id: meta.imageId,
        image_url: meta.imageUrl
    }
}

export const mapSingle = response => {
    return Promise.resolve({
        award: mapAward(response.data),
        categories: response.data.categories !== undefined && Array.isArray(response.data.categories)
            ? response.data.categories.map(mapToCategory) : [],
        finalists: response.data.finalists !== undefined && Array.isArray(response.data.finalists)
            ? response.data.finalists.map(mapToFinalist) : [],
        winners: response.data.winners !== undefined && Array.isArray(response.data.winners)
            ? response.data.winners.map(mapAwardWinner) : [],
        winnersMeta: response.data.winnersMeta !== undefined && Array.isArray(response.data.winnersMeta)
            ? response.data.winnersMeta.map(mapWinnersMeta) : []
    });
}

export const mapCollection = response => {
    return Promise.resolve(response.data.map(data => ({
        award: mapAward(data),
        categories: data.categories !== undefined ? data.categories.map(mapToCategory) : []
    })));
}

export default class AwardService {


    /**
     * Creates an award
     * @param object data 
     */
    create({title, year, nomination_start_date, nomination_end_date, voting_start_date, voting_end_date }) {
        let form = new FormData;
        form.append('action', 'create_award');
        form.append('title', title);
        form.append('year', year);
        form.append('nomination_start_date', nomination_start_date);
        form.append('nomination_end_date', nomination_end_date);
        form.append('voting_start_date', voting_start_date);
        form.append('voting_end_date', voting_end_date);

        return axios.post(ajaxurl, form)
            .catch(handleError);
    }


    /**
     * Updates an award
     * @param int id 
     * @param object data 
     */
    update(id, {title, year, nomination_start_date, nomination_end_date, voting_start_date, voting_end_date }) {
        let form = new FormData;
        form.append('action', 'patch_award');
        form.append('award_id', id);
        form.append('title', title);
        form.append('year', year);
        form.append('nomination_start_date', nomination_start_date);
        form.append('nomination_end_date', nomination_end_date);
        form.append('voting_start_date', voting_start_date);
        form.append('voting_end_date', voting_end_date);

        return axios.post(ajaxurl, form)
            .catch(handleError);
    }


    /**
     * Gets an award
     * @param int id 
     */
    get(id) {
        return axios.get(url + '/award/' + id, axiosWpAuthAccess)
        .then(mapSingle)
        .catch(handleError);
    }


    /**
     * Gets all awards
     */
    all() {
        return axios.get(url + '/awards', axiosWpAuthAccess)
        .then(mapCollection)
        .catch(handleError);
    }

    /**
     * Put award live
     * @param int id 
     */
    goLive(id) {
        let form = new FormData;
        form.append('action', 'go_live_with_award');
        form.append('award_id', id);
        return axios.post(ajaxurl, form)
            .then(mapSingle)
            .catch(handleError);
    }


    /**
     * Archive award
     * @param int id 
     */
    archive(id) {
        let form = new FormData;
        form.append('action', 'archive_award');
        form.append('award_id', id);
        return axios.post(ajaxurl, form)
            .then(mapSingle)
            .catch(handleError);
    }


    /**
     * Unpublish award
     * @param int id 
     */
    unpublish(id) {
        let form = new FormData;
        form.append('action', 'unpublish_award');
        form.append('award_id', id);
        return axios.post(ajaxurl, form)
            .then(mapSingle)
            .catch(handleError);
    }


    /**
     * Publishes winners
     * @param int id 
     */
    publishWinners(id) {
        let form = new FormData;
        form.append('action', 'publish_winners');
        form.append('award_id', id);
        return axios.post(ajaxurl, form)
            .then(mapSingle)
            .catch(handleError);
    }

    /**
     * Unpublish winners
     * @param int id 
     */
    unpublishWinners(id) {
        let form = new FormData;
        form.append('action', 'unpublish_winners');
        form.append('award_id', id);
        return axios.post(ajaxurl, form)
            .then(mapSingle)
            .catch(handleError);
    }

}